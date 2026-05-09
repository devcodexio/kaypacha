<?php
// controladores/cliente/PagosYapeClienteController.php

session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 3) {
    header("Location: /clientes/vistas/auth/login.php");
    exit;
}

require_once __DIR__ . '/../../conexion.php';

$usuario_id = (int) $_SESSION['usuario_id'];
$accion = $_GET['accion'] ?? 'index';

// 🔴 IMPORTANTE: si en tu tabla reservas la columna es cliente_id,
// cambia "usuario_id" por "cliente_id" en las consultas.

switch ($accion) {

    // ==========================
    // LISTAR PAGOS YAPE DEL CLIENTE
    // ==========================
    case 'index':
        $pagos = [];

        // 1. Obtener pagos YAPE
        $sqlYape = "SELECT p.id, p.reserva_id, p.monto, p.imagen, 'Yape' as metodo,
                           r.fecha, r.hora, r.estado AS estado_reserva,
                           m.numero_mesa
                    FROM pagos_yape p
                    INNER JOIN reservas r ON r.id = p.reserva_id
                    INNER JOIN mesas m    ON m.id = r.mesa_id
                    WHERE r.usuario_id = ?";

        $stmt = $conexion->prepare($sqlYape);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $resYape = $stmt->get_result();
        while ($row = $resYape->fetch_assoc()) {
            $pagos[] = $row;
        }
        $stmt->close();

        // 2. Obtener pagos STRIPE
        $sqlStripe = "SELECT p.id, p.reserva_id, p.monto, 'Stripe' as metodo,
                             r.fecha, r.hora, r.estado AS estado_reserva,
                             m.numero_mesa
                      FROM pagos_stripe p
                      INNER JOIN reservas r ON r.id = p.reserva_id
                      INNER JOIN mesas m    ON m.id = r.mesa_id
                      WHERE r.usuario_id = ?";

        $stmt = $conexion->prepare($sqlStripe);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $resStripe = $stmt->get_result();
        while ($row = $resStripe->fetch_assoc()) {
            $row['imagen'] = ''; // Stripe no tiene imagen de comprobante manual
            $pagos[] = $row;
        }
        $stmt->close();

        // Ordenar por ID descendente (asumiendo que IDs más altos son más recientes)
        usort($pagos, function($a, $b) {
            return $b['id'] <=> $a['id'];
        });

        include __DIR__ . '/../../vistas/cliente/pagos/index.php';
        break;

    // ==========================
    // MOSTRAR FORMULARIO DE PAGO PARA UNA RESERVA
    // ==========================
    case 'form':
        if (!isset($_GET['reserva_id'])) {
            // en vez de morir, regresamos a las reservas
            header("Location: /clientes/controladores/cliente/ReservasClienteController.php?accion=index");
            exit;
        }

        $reserva_id = (int) $_GET['reserva_id'];

        // verificar que la reserva sea del cliente
        $sql = "SELECT r.*, m.numero_mesa
                FROM reservas r
                INNER JOIN mesas m ON m.id = r.mesa_id
                WHERE r.id = ? AND r.usuario_id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ii", $reserva_id, $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $reserva = $result->fetch_assoc();
        $stmt->close();

        if (!$reserva) {
            die("No tienes acceso a esta reserva.");
        }

        include __DIR__ . '/../../vistas/cliente/pagos/yape.php';
        break;

    // ==========================
    // GUARDAR PAGO YAPE
    // ==========================
    case 'guardar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $reserva_id = (int) $_POST['reserva_id'];
            $monto      = (float) $_POST['monto'];

            // verificar reserva del cliente
            $sql = "SELECT id FROM reservas WHERE id = ? AND usuario_id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ii", $reserva_id, $usuario_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $res = $result->fetch_assoc();
            $stmt->close();

            if (!$res) {
                die("Reserva no válida.");
            }

            // carpeta para comprobantes
            $rutaUploads = __DIR__ . '/../../uploads/';
            if (!file_exists($rutaUploads)) {
                mkdir($rutaUploads, 0777, true);
            }

            // subir archivo
            $nombre_archivo = time() . "_" . basename($_FILES['imagen']['name']);
            $destino = $rutaUploads . $nombre_archivo;

            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
                die("Error al subir el comprobante.");
            }

            // guardar pago
            $sqlPago = "INSERT INTO pagos_yape (reserva_id, imagen, monto) VALUES (?, ?, ?)";
            $stmt2 = $conexion->prepare($sqlPago);
            $stmt2->bind_param("isd", $reserva_id, $nombre_archivo, $monto);
            $stmt2->execute();
            $stmt2->close();

            // actualizar estado de la reserva
            $sqlRes = "UPDATE reservas SET estado = 'pagado_yape', comprobante_yape = ? WHERE id = ?";
            $stmt3 = $conexion->prepare($sqlRes);
            $stmt3->bind_param("si", $nombre_archivo, $reserva_id);
            $stmt3->execute();
            $stmt3->close();
        }

        header("Location: /clientes/controladores/cliente/ReservasClienteController.php?accion=index");
        exit;

    // ==========================
    // CUALQUIER OTRA ACCIÓN → index
    // ==========================
    default:
        header("Location: /clientes/controladores/cliente/PagosYapeClienteController.php?accion=index");
        exit;
}

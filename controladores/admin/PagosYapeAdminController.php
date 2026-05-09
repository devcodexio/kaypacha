<?php
session_start();

// Verificar que el usuario sea administrador (rol = 1)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    header("Location: /clientes/vistas/auth/login.php");
    exit;
}

require_once __DIR__ . '/../../conexion.php';

$accion = $_GET['accion'] ?? 'index';

switch ($accion) {

    case 'index':
    default:
        // Obtener todos los pagos con información de reserva, cliente y mesa
        $pagos = [];
        $sql = "SELECT p.*, r.fecha, r.hora, r.id AS reserva_id,
                       u.nombre AS cliente, m.numero_mesa
                FROM pagos_yape p
                INNER JOIN reservas r ON r.id = p.reserva_id
                INNER JOIN usuarios u ON u.id = r.usuario_id
                INNER JOIN mesas m ON m.id = r.mesa_id
                ORDER BY p.id DESC";
        $result = $conexion->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $pagos[] = $row;
            }
        }
        require __DIR__ . '/../../vistas/admin/pagos_yape/index.php';
        break;

    case 'confirmar':
        // Verificar que se haya pasado un id de pago
        if (!isset($_GET['id'])) {
            header("Location: PagosYapeAdminController.php?accion=index");
            exit;
        }
        $id_pago = (int)$_GET['id'];

        // Obtener la reserva asociada al pago
        $stmt = $conexion->prepare("SELECT reserva_id FROM pagos_yape WHERE id = ?");
        $stmt->bind_param("i", $id_pago);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($row) {
            $reserva_id = (int)$row['reserva_id'];

            // Obtener la mesa de la reserva
            $stmt2 = $conexion->prepare("SELECT mesa_id FROM reservas WHERE id = ?");
            $stmt2->bind_param("i", $reserva_id);
            $stmt2->execute();
            $res = $stmt2->get_result()->fetch_assoc();
            $stmt2->close();

            if ($res) {
                $mesa_id = (int)$res['mesa_id'];

                // Actualizar el estado de la reserva a confirmado
                $stmt3 = $conexion->prepare("UPDATE reservas SET estado='confirmado' WHERE id=?");
                $stmt3->bind_param("i", $reserva_id);
                $stmt3->execute();
                $stmt3->close();

                // Actualizar el estado de la mesa a reservada
                $stmt4 = $conexion->prepare("UPDATE mesas SET estado='reservada' WHERE id=?");
                $stmt4->bind_param("i", $mesa_id);
                $stmt4->execute();
                $stmt4->close();

                // Marcar el pago como verificado
                $stmt5 = $conexion->prepare("UPDATE pagos_yape SET verificado = 1 WHERE id = ?");
                $stmt5->bind_param("i", $id_pago);
                $stmt5->execute();
                $stmt5->close();
            }
        }

        // Redirigir con mensaje de confirmación
        header("Location: PagosYapeAdminController.php?accion=index&msg=confirmado");
        exit;
}
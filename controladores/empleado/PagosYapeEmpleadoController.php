<?php
// controladores/empleado/PagosYapeEmpleadoController.php

session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) {
    header("Location: /clientes/vistas/auth/login.php");
    exit;
}

require_once __DIR__ . '/../../conexion.php';

$accion = $_GET['accion'] ?? 'index';

switch ($accion) {

    case 'index':
    default:
        $pagos = [];
        $sql = "SELECT p.*, r.fecha, r.hora, r.id AS reserva_id,
                       u.nombre AS cliente, m.numero_mesa
                FROM pagos_yape p
                INNER JOIN reservas r ON r.id = p.reserva_id
                INNER JOIN usuarios u ON u.id = r.usuario_id
                INNER JOIN mesas m ON m.id = r.mesa_id
                ORDER BY p.id DESC";
        $result = $conexion->query($sql);
        while ($row = $result->fetch_assoc()) {
            $pagos[] = $row;
        }
        require_once __DIR__ . '/../../vistas/empleado/pagos_yape/index.php';
        break;

    // confirmar reserva asociada al pago
    case 'confirmar':
        if (!isset($_GET['id'])) {
            header("Location: PagosYapeEmpleadoController.php?accion=index");
            exit;
        }

        $id_pago = (int)$_GET['id'];

        // obtener reserva asociada
        $stmt = $conexion->prepare("SELECT reserva_id FROM pagos_yape WHERE id = ?");
        $stmt->bind_param("i", $id_pago);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($row) {
            $reserva_id = (int)$row['reserva_id'];

            // obtener mesa
            $stmt2 = $conexion->prepare("SELECT mesa_id FROM reservas WHERE id = ?");
            $stmt2->bind_param("i", $reserva_id);
            $stmt2->execute();
            $res = $stmt2->get_result()->fetch_assoc();
            $stmt2->close();

            if ($res) {
                $mesa_id = (int)$res['mesa_id'];

                $stmt3 = $conexion->prepare("UPDATE reservas SET estado = 'confirmado' WHERE id = ?");
                $stmt3->bind_param("i", $reserva_id);
                $stmt3->execute();
                $stmt3->close();

                $stmt4 = $conexion->prepare("UPDATE mesas SET estado = 'reservada' WHERE id = ?");
                $stmt4->bind_param("i", $mesa_id);
                $stmt4->execute();
                $stmt4->close();
            }
        }

        header("Location: PagosYapeEmpleadoController.php?accion=index");
        exit;
}

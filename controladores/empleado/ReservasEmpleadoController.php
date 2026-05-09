<?php
// controladores/empleado/ReservasEmpleadoController.php

session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) {
    header("Location: /clientes/vistas/auth/login.php");
    exit;
}

require_once __DIR__ . '/../../conexion.php';

$accion = $_GET['accion'] ?? 'index';

switch ($accion) {

    // LISTAR RESERVAS
    case 'index':
    default:
        $reservas = [];
        $sql = "SELECT r.*, u.nombre AS cliente, m.numero_mesa
                FROM reservas r
                INNER JOIN usuarios u ON u.id = r.usuario_id
                INNER JOIN mesas m ON m.id = r.mesa_id
                ORDER BY r.fecha DESC, r.hora DESC";
        $result = $conexion->query($sql);
        while ($row = $result->fetch_assoc()) {
            $reservas[] = $row;
        }

        require_once __DIR__ . '/../../vistas/empleado/reservas/index.php';
        break;

    // CAMBIAR ESTADO (confirmar / cancelar / finalizado)
    case 'estado':
        if (!isset($_GET['id']) || !isset($_GET['nuevo'])) {
            header("Location: ReservasEmpleadoController.php?accion=index");
            exit;
        }

        $id    = (int)$_GET['id'];
        $nuevo = $_GET['nuevo'];

        // obtener mesa de esa reserva
        $stmt = $conexion->prepare("SELECT mesa_id FROM reservas WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($res) {
            $mesa_id = (int)$res['mesa_id'];

            $stmt2 = $conexion->prepare("UPDATE reservas SET estado = ? WHERE id = ?");
            $stmt2->bind_param("si", $nuevo, $id);
            $stmt2->execute();
            $stmt2->close();

            // si se cancela, liberar mesa
            if ($nuevo === 'cancelado') {
                $stmt3 = $conexion->prepare("UPDATE mesas SET estado='disponible' WHERE id = ?");
                $stmt3->bind_param("i", $mesa_id);
                $stmt3->execute();
                $stmt3->close();
            }

            // si se confirma, asegurar estado 'reservada'
            if ($nuevo === 'confirmado') {
                $stmt3 = $conexion->prepare("UPDATE mesas SET estado='reservada' WHERE id = ?");
                $stmt3->bind_param("i", $mesa_id);
                $stmt3->execute();
                $stmt3->close();
            }
        }

        header("Location: ReservasEmpleadoController.php?accion=index");
        exit;
}

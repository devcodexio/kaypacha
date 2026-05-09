<?php
// controladores/empleado/DashboardEmpleadoController.php

session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) {
    header("Location: /clientes/vistas/auth/login.php");
    exit;
}

require_once __DIR__ . '/../../conexion.php';

// Contadores básicos para el panel del empleado
$totales = [
    'reservas_pendientes' => 0,
    'reservas_confirmadas' => 0,
    'pagos_yape' => 0,
];

$result = $conexion->query("SELECT COUNT(*) AS total FROM reservas WHERE estado = 'pendiente'");
$totales['reservas_pendientes'] = $result ? (int)$result->fetch_assoc()['total'] : 0;

$result = $conexion->query("SELECT COUNT(*) AS total FROM reservas WHERE estado = 'confirmado'");
$totales['reservas_confirmadas'] = $result ? (int)$result->fetch_assoc()['total'] : 0;

$result = $conexion->query("SELECT COUNT(*) AS total FROM pagos_yape");
$totales['pagos_yape'] = $result ? (int)$result->fetch_assoc()['total'] : 0;

require_once __DIR__ . '/../../vistas/empleado/panel.php';

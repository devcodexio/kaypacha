<?php
// controladores/admin/DashboardAdminController.php

session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    header("Location: /clientes/vistas/auth/login.php");
    exit;
}

require_once __DIR__ . '/../../conexion.php';

// Contadores para el dashboard
$totales = [
    'usuarios'   => 0,
    'mesas'      => 0,
    'platos'     => 0,
    'reservas'   => 0,
    'pagos_yape' => 0,
];

$result = $conexion->query("SELECT COUNT(*) AS total FROM usuarios");
$totales['usuarios'] = $result ? (int)$result->fetch_assoc()['total'] : 0;

$result = $conexion->query("SELECT COUNT(*) AS total FROM mesas");
$totales['mesas'] = $result ? (int)$result->fetch_assoc()['total'] : 0;

$result = $conexion->query("SELECT COUNT(*) AS total FROM platos");
$totales['platos'] = $result ? (int)$result->fetch_assoc()['total'] : 0;

$result = $conexion->query("SELECT COUNT(*) AS total FROM reservas");
$totales['reservas'] = $result ? (int)$result->fetch_assoc()['total'] : 0;

$result = $conexion->query("SELECT COUNT(*) AS total FROM pagos_yape");
$totales['pagos_yape'] = $result ? (int)$result->fetch_assoc()['total'] : 0;

// Cargar la vista del panel admin
require_once __DIR__ . '/../../vistas/admin/panel.php';

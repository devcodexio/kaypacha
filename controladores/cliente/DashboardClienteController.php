<?php
// controladores/cliente/DashboardClienteController.php

session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 3) {
    header("Location: /clientes/vistas/auth/login.php");
    exit;
}

require_once __DIR__ . '/../../conexion.php';

$usuario_id = (int) $_SESSION['usuario_id'];

// últimas 5 reservas del cliente
$reservasRecientes = [];
$sql = "SELECT r.*, m.numero_mesa
        FROM reservas r
        INNER JOIN mesas m ON m.id = r.mesa_id
        WHERE r.usuario_id = ?
        ORDER BY r.id DESC
        LIMIT 5";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $reservasRecientes[] = $row;
}
$stmt->close();

include __DIR__ . '/../../vistas/cliente/panel.php';

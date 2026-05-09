<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) {
    header("Location: /clientes/vistas/auth/login.php");
    exit;
}

require_once __DIR__ . '/../../conexion.php';

$pagos = [];
$sql = "SELECT p.*, r.fecha, r.hora, r.id AS reserva_id,
               u.nombre AS cliente, m.numero_mesa
        FROM pagos_paypal p
        INNER JOIN reservas r ON r.id = p.reserva_id
        INNER JOIN usuarios u ON u.id = r.usuario_id
        INNER JOIN mesas m ON m.id = r.mesa_id
        ORDER BY p.id DESC";

$result = $conexion->query($sql);
while ($row = $result->fetch_assoc()) {
    $pagos[] = $row;
}

require __DIR__ . '/../../vistas/empleado/pagos_paypal/index.php';
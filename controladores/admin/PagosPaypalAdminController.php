<?php
session_start();

/* ===============================
   VALIDAR ACCESO ADMIN
================================ */
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    header("Location: /clientes/vistas/auth/login.php");
    exit;
}

/* ===============================
   CONEXION BD
================================ */
require_once __DIR__ . '/../../conexion.php';

$pagos = [];

/* ===============================
   CONSULTA PAGOS PAYPAL
================================ */
$sql = "SELECT 
            p.id,
            p.reserva_id,
            p.paypal_order_id,
            p.paypal_capture_id,
            p.monto,
            p.moneda,
            p.estado,
            p.fecha_pago,

            r.fecha,
            r.hora,
            r.id AS reserva_codigo,

            u.nombre AS cliente,

            m.numero_mesa

        FROM pagos_paypal p
        INNER JOIN reservas r ON r.id = p.reserva_id
        INNER JOIN usuarios u ON u.id = r.usuario_id
        INNER JOIN mesas m ON m.id = r.mesa_id
        ORDER BY p.fecha_pago DESC";

$result = $conexion->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $pagos[] = $row;
    }
}

/* ===============================
   CARGAR VISTA
================================ */
require __DIR__ . '/../../vistas/admin/pagos_paypal/index.php';
<?php
// controladores/admin/PagosStripeAdminController.php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
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
                FROM pagos_stripe p
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
        require __DIR__ . '/../../vistas/admin/pagos_stripe/index.php';
        break;

    case 'ver_detalles':
        // Lógica opcional para ver detalles del intent de Stripe
        break;
}

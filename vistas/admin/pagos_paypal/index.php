<?php
// vistas/admin/pagos_paypal/index.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* Verificar acceso admin */
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    header("Location: /clientes/vistas/auth/login.php");
    exit;
}

require_once __DIR__ . '/../../../conexion.php';

// 🔹 Recoger búsqueda
$search = trim($_GET['q'] ?? '');

// Traer pagos PayPal con info del cliente y mesa
$sql = "SELECT p.id, p.reserva_id, u.nombre AS cliente, m.numero_mesa,
               r.fecha, r.hora, p.monto, p.moneda, p.estado,
               p.paypal_order_id, p.paypal_capture_id, p.fecha_pago
        FROM pagos_paypal p
        INNER JOIN reservas r ON r.id = p.reserva_id
        INNER JOIN usuarios u ON u.id = r.usuario_id
        INNER JOIN mesas m ON m.id = r.mesa_id
        ORDER BY p.fecha_pago DESC";

$result = $conexion->query($sql);
$pagos = [];

if($result){
    while($row = $result->fetch_assoc()){
        $pagos[] = $row;
    }
}

// 🔍 Filtrar en PHP si hay búsqueda
if ($search !== '') {
    $pagos = array_filter($pagos, function($p) use ($search) {
        $searchLower = strtolower($search);
        $idBusqueda = (string)(int)$search;
        
        // Buscar por: ID de pago, ID de reserva, o nombre de cliente
        return 
            (string)$p['id'] === $idBusqueda ||
            (string)$p['reserva_id'] === $idBusqueda ||
            stripos($p['cliente'] ?? '', $searchLower) !== false;
    });
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Pagos PayPal</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

<h2 class="mb-4">Pagos realizados con PayPal</h2>

<!-- 🔍 Buscador Simple -->
<form method="GET" class="mb-4">
    <div class="input-group">
        <input type="search" name="q" class="form-control" 
               placeholder="Buscar por cliente o ID de reserva..." 
               value="<?= htmlspecialchars($search) ?>">
        <button class="btn btn-primary" type="submit">
            <i class="bi bi-search"></i> Buscar
        </button>
        <?php if ($search): ?>
        <a href="?" class="btn btn-outline-secondary">Limpiar</a>
        <?php endif; ?>
    </div>
    <small class="text-muted">
        Ejemplo: escribe <code>123</code> para ID o <code>María</code> para nombre
    </small>
</form>

<!-- Contador de resultados -->
<?php if ($search): ?>
<div class="alert alert-info py-2 mb-3">
    <i class="bi bi-filter"></i> 
    Mostrando <?= count($pagos) ?> resultado<?= count($pagos) !== 1 ? 's' : '' ?> para: 
    <strong>"<?= htmlspecialchars($search) ?>"</strong>
</div>
<?php endif; ?>

<table class="table table-bordered table-striped">
<thead class="table-dark">
<tr>
<th>ID</th>
<th>Cliente</th>
<th>Mesa</th>
<th>Fecha Reserva</th>
<th>Hora</th>
<th>Monto</th>
<th>Moneda</th>
<th>Estado</th>
<th>Order ID</th>
<th>Capture ID</th>
<th>Fecha Pago</th>
</tr>
</thead>
<tbody>
<?php if (!empty($pagos)): ?>
    <?php foreach ($pagos as $pago): ?>
    <tr>
        <td><?= $pago['id'] ?></td>
        <td><?= htmlspecialchars($pago['cliente']) ?></td>
        <td>Mesa <?= $pago['numero_mesa'] ?></td>
        <td><?= $pago['fecha'] ?></td>
        <td><?= $pago['hora'] ?></td>
        <td>$<?= $pago['monto'] ?></td>
        <td><?= $pago['moneda'] ?></td>
        <td>
            <?php if(strtolower($pago['estado'])=="completado"): ?>
                <span class="badge bg-success">Completado</span>
            <?php else: ?>
                <span class="badge bg-warning"><?= $pago['estado'] ?></span>
            <?php endif; ?>
        </td>
        <td><?= $pago['paypal_order_id'] ?></td>
        <td><?= $pago['paypal_capture_id'] ?></td>
        <td><?= $pago['fecha_pago'] ?></td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
<tr>
    <td colspan="11" class="text-center">
        <?= $search ? '🔍 No se encontraron resultados con esta búsqueda' : 'No hay pagos registrados' ?>
    </td>
</tr>
<?php endif; ?>
</tbody>
</table>

</div>
</body>
</html>
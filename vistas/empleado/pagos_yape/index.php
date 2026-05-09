<?php
require_once __DIR__ . '/../../layout/header.php';
require_once __DIR__ . '/../../layout/sidebar_empleado.php';
?>

<style>
.center-box {
    max-width: 1100px;
    margin: 40px auto;
}

.table-card {
    background: #ffffff;
    padding: 25px 30px;
    border-radius: 18px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}

.table-card h2 {
    font-size: 28px;
    font-weight: 700;
    color: #1d3557;
    margin-bottom: 20px;
}

/* TABLA */
.table thead th {
    background: #4a69bd;
    color: white;
    padding: 12px;
    font-size: 15px;
}

.table tbody td {
    padding: 12px;
    font-size: 15px;
    vertical-align: middle;
}

/* BOTONES */
.btn-action {
    padding: 8px 14px;
    border-radius: 10px;
    text-decoration: none;
    color: white !important;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    font-weight: 600;
}

.btn-confirmar {
    background: #27ae60;
}
.btn-confirmar:hover {
    background: #1e8449;
}

/* LINK VER COMPROBANTE */
.link-comprobante {
    color: #2980b9;
    font-weight: 600;
    text-decoration: none;
}
.link-comprobante:hover {
    text-decoration: underline;
}

/* CELDA DE ACCIONES */
.table td:last-child {
    min-width: 160px;
}
</style>

<div class="page-container">
    <main class="main-content">

        <div class="center-box">
            <div class="table-card">

                <h2><i class="fas fa-money-bill-wave"></i> Pagos Yape</h2>

                <table class="table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th>ID Pago</th>
                            <th>Reserva</th>
                            <th>Cliente</th>
                            <th>Mesa</th>
                            <th>Fecha / Hora</th>
                            <th>Monto</th>
                            <th>Comprobante</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($pagos)): ?>
                            <?php foreach ($pagos as $p): ?>
                                <tr>
                                    <td><?= (int)$p['id'] ?></td>

                                    <td>#<?= (int)$p['reserva_id'] ?></td>

                                    <td>
                                        <strong><?= htmlspecialchars($p['cliente']) ?></strong>
                                    </td>

                                    <td>Mesa <?= htmlspecialchars($p['numero_mesa']) ?></td>

                                    <td>
                                        <?= htmlspecialchars($p['fecha']) ?><br>
                                        <small><?= htmlspecialchars($p['hora']) ?></small>
                                    </td>

                                    <td>
                                        <strong>S/ <?= number_format($p['monto'], 2) ?></strong>
                                    </td>

                                    <td>
                                        <a class="link-comprobante"
                                           href="/clientes/uploads/<?= htmlspecialchars($p['imagen']) ?>"
                                           target="_blank">
                                            <i class="fas fa-receipt"></i> Ver comprobante
                                        </a>
                                    </td>

                                    <td>
                                        <a class="btn-action btn-confirmar"
                                           href="/clientes/controladores/empleado/PagosYapeEmpleadoController.php?accion=confirmar&id=<?= $p['id'] ?>">
                                            <i class="fas fa-check-circle"></i> Confirmar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        <?php else: ?>
                            <tr>
                                <td colspan="8">No hay pagos registrados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>

            </div>
        </div>

    </main>
</div>

<?php
require_once __DIR__ . '/../../layout/footer.php';
?>

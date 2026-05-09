<?php
// vistas/admin/pagos_stripe/index.php
require_once __DIR__ . '/../../layout/headerr.php';
require_once __DIR__ . '/../../layout/sidebar_admin.php';
?>

<div class="content-wrapper">
    <!-- 🔹 HEADER PRO -->
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h6 class="text-indigo fw-800 text-uppercase mb-2" style="letter-spacing: 3px; font-size: 0.75rem;">Gestión Financiera</h6>
            <h1 class="fw-800 text-slate-900 display-5 mb-0">Transacciones Stripe</h1>
        </div>
        <div class="d-flex gap-3">
            <div class="stripe-badge-pro">
                <i class="fa-brands fa-stripe"></i>
                <span>Verificado por Stripe</span>
            </div>
            <button class="btn btn-white shadow-sm border-0 rounded-16 px-4 py-3 fw-800" onclick="window.location.reload()">
                <i class="fas fa-rotate me-2 text-indigo"></i> Actualizar
            </button>
        </div>
    </div>

    <!-- 🔹 TABLA PROFESIONAL v2 -->
    <div class="table-responsive">
        <table class="table-pro">
            <thead>
                <tr>
                    <th>ID Pago</th>
                    <th>Reserva</th>
                    <th>Cliente</th>
                    <th>Mesa</th>
                    <th>Fecha / Hora</th>
                    <th>Monto</th>
                    <th>Estado Stripe</th>
                    <th class="text-end">ID Transacción</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pagos)): ?>
                    <?php foreach ($pagos as $p): ?>
                        <tr>
                            <td><span class="fw-800 text-slate-900">#<?= (int)$p['id'] ?></span></td>
                            <td>
                                <span class="badge bg-indigo-subtle text-indigo fw-800 rounded-pill px-3 py-2">
                                    REF #<?= (int)$p['reserva_id'] ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-pro">
                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($p['cliente']) ?>&background=random&bold=true" alt="">
                                    </div>
                                    <span class="fw-800 text-slate-900 fs-6"><?= htmlspecialchars($p['cliente']) ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="mesa-badge-pro">
                                    <i class="fas fa-chair text-slate-400"></i>
                                    <span><?= htmlspecialchars($p['numero_mesa']) ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-700 text-slate-800"><?= htmlspecialchars($p['fecha']) ?></span>
                                    <span class="text-slate-400 small fw-600"><?= htmlspecialchars($p['hora']) ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="fw-800 text-slate-900 fs-5">S/ <?= number_format($p['monto'], 2) ?></span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="status-dot bg-success"></div>
                                    <span class="fw-800 text-success" style="font-size: 0.7rem; letter-spacing: 1px;">SUCCEEDED</span>
                                </div>
                            </td>
                            <td class="text-end">
                                <code class="stripe-id-pro"><?= htmlspecialchars($p['stripe_id']) ?></code>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="8" class="text-center py-5 text-muted fw-500">No se han procesado transacciones digitales vía Stripe.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .avatar-pro img { width: 44px; height: 44px; border-radius: 12px; }
    
    .mesa-badge-pro {
        display: inline-flex; align-items: center; gap: 8px;
        background: #f8fafc; padding: 8px 16px; border-radius: 12px;
        font-weight: 800; font-size: 0.85rem; color: #1e293b;
    }

    .stripe-badge-pro {
        display: flex; align-items: center; gap: 10px;
        background: #635bff; color: white;
        padding: 10px 20px; border-radius: 16px;
        font-weight: 800; font-size: 0.85rem;
    }

    .stripe-id-pro {
        background: #f1f5f9; color: #475569;
        padding: 6px 12px; border-radius: 8px;
        font-size: 0.75rem; font-family: 'Courier New', monospace;
    }

    .status-dot { width: 8px; height: 8px; border-radius: 50%; }
    .bg-indigo-subtle { background: #eef2ff; }
</style>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>

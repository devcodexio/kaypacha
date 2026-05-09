<?php
// vistas/admin/pagos_yape/index.php
require_once __DIR__ . '/../../layout/headerr.php';
require_once __DIR__ . '/../../layout/sidebar_admin.php';
?>

<div class="content-wrapper">
    <!-- 🔹 HEADER PRO -->
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h6 class="text-indigo fw-800 text-uppercase mb-2" style="letter-spacing: 3px; font-size: 0.75rem;">Finanzas</h6>
            <h1 class="fw-800 text-slate-900 display-5 mb-0">Pagos Yape</h1>
        </div>
        <div class="d-flex gap-3">
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
                    <th>ID</th>
                    <th>Reserva</th>
                    <th>Pagador</th>
                    <th>Mesa</th>
                    <th>Fecha / Hora</th>
                    <th>Monto</th>
                    <th>Evidencia</th>
                    <th class="text-end">Operaciones</th>
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
                                <?php if (!empty($p['imagen'])): ?>
                                    <div class="comprobante-preview-pro" onclick="window.open('/clientes/uploads/<?= $p['imagen'] ?>', '_blank')">
                                        <img src="/clientes/uploads/<?= htmlspecialchars($p['imagen']) ?>" alt="Yape">
                                        <div class="overlay"><i class="fas fa-expand"></i></div>
                                    </div>
                                <?php else: ?>
                                    <span class="text-slate-300 small fw-800 text-uppercase">Sin imagen</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <a href="/clientes/controladores/admin/PagosYapeAdminController.php?accion=confirmar&id=<?= $p['id'] ?>" 
                                   class="action-btn-pro btn-confirm w-auto px-4 gap-2 fw-800" title="Validar Pago">
                                    <i class="fas fa-check-double"></i>
                                    <span>VALIDAR</span>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="8" class="text-center py-5 text-muted fw-500">No se detectaron transacciones de Yape pendientes.</td></tr>
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

    .comprobante-preview-pro {
        width: 60px; height: 60px; border-radius: 12px; overflow: hidden;
        position: relative; cursor: pointer; border: 2px solid #fff; shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .comprobante-preview-pro img { width: 100%; height: 100%; object-fit: cover; }
    .comprobante-preview-pro .overlay {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(99, 102, 241, 0.8); display: flex; align-items: center; justify-content: center;
        color: white; opacity: 0; transition: 0.3s;
    }
    .comprobante-preview-pro:hover .overlay { opacity: 1; }

    .action-btn-pro {
        height: 48px; display: inline-flex; align-items: center; justify-content: center;
        border-radius: 16px; transition: all 0.3s ease; text-decoration: none;
    }
    .btn-confirm { background: #ecfdf5; color: #10b981; border: 1px solid #d1fae5; }
    .btn-confirm:hover { background: #10b981; color: #fff; transform: translateY(-3px); box-shadow: 0 10px 20px rgba(16, 185, 129, 0.2); }

    .bg-indigo-subtle { background: #eef2ff; }
</style>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
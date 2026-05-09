<?php
// vistas/admin/reservas/index.php
require_once __DIR__ . '/../../layout/headerr.php';
require_once __DIR__ . '/../../layout/sidebar_admin.php';
?>

<div class="content-wrapper">
    <!-- 🔹 TÍTULO Y ACCIONES PRO -->
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h6 class="text-indigo fw-800 text-uppercase mb-2" style="letter-spacing: 3px; font-size: 0.75rem;">Administración</h6>
            <h1 class="fw-800 text-slate-900 display-5 mb-0">Reservas</h1>
        </div>
        <div class="d-flex gap-3">
            <button class="btn btn-white shadow-sm border-0 rounded-16 px-4 py-3 fw-700" onclick="window.location.reload()">
                <i class="fas fa-sync-alt me-2 text-indigo"></i> Sincronizar
            </button>
            <a href="?accion=exportar" class="btn btn-primary shadow-pro border-0 rounded-16 px-4 py-3 fw-800" style="background: var(--slate-900);">
                <i class="fas fa-file-export me-2"></i> Exportar CSV
            </a>
        </div>
    </div>

    <!-- 🔹 TABLA PROFESIONAL v2 -->
    <div class="table-responsive">
        <table class="table-pro">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Mesa</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th class="text-center">Personas</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($reservas)): ?>
                    <?php foreach ($reservas as $r): 
                        $st = strtolower($r['estado']);
                        $pillColor = ($st === 'confirmado') ? '#10b981' : (($st === 'cancelado') ? '#ef4444' : '#f59e0b');
                    ?>
                        <tr>
                            <td>
                                <span class="fw-800 text-slate-900" style="font-size: 0.9rem;">#<?= (int)$r['id'] ?></span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-pro">
                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($r['cliente']) ?>&background=random&bold=true" alt="">
                                    </div>
                                    <span class="fw-700 text-slate-900"><?= htmlspecialchars($r['cliente']) ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="mesa-badge-pro">
                                    <i class="fas fa-chair"></i>
                                    <span><?= htmlspecialchars($r['numero_mesa']) ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="fw-600 text-slate-800"><?= htmlspecialchars($r['fecha']) ?></span>
                            </td>
                            <td>
                                <span class="text-slate-500 fw-500"><?= htmlspecialchars($r['hora']) ?></span>
                            </td>
                            <td class="text-center">
                                <span class="pax-badge"><?= (int)$r['cantidad_personas'] ?></span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="status-dot" style="background: <?= $pillColor ?>;"></div>
                                    <span class="fw-800 text-uppercase" style="color: <?= $pillColor ?>; font-size: 0.7rem; letter-spacing: 1px;"><?= $st ?></span>
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="/clientes/controladores/admin/ReservasAdminController.php?accion=estado&id=<?= $r['id'] ?>&nuevo=confirmado" 
                                       class="action-btn-pro btn-confirm" title="Confirmar Reserva">
                                        <i class="fas fa-check-circle"></i>
                                    </a>
                                    <a href="/clientes/controladores/admin/ReservasAdminController.php?accion=estado&id=<?= $r['id'] ?>&nuevo=cancelado" 
                                       class="action-btn-pro btn-cancel" title="Cancelar Reserva"
                                       onclick="return confirm('¿Seguro que desea cancelar?')">
                                        <i class="fas fa-minus-circle"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="8" class="text-center py-5 text-muted fw-500">No hay registros activos en este momento.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .avatar-pro img { width: 44px; height: 44px; border-radius: 14px; object-fit: cover; }
    
    .mesa-badge-pro {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--slate-50);
        padding: 8px 16px;
        border-radius: 12px;
        color: var(--slate-900);
        font-weight: 800;
        font-size: 0.85rem;
    }

    .pax-badge {
        background: #f1f5f9;
        color: #475569;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-weight: 800;
        font-size: 0.8rem;
    }

    .status-dot { width: 8px; height: 8px; border-radius: 50%; }

    .action-btn-pro {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        font-size: 1.2rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-confirm { color: #10b981; background: #ecfdf5; }
    .btn-confirm:hover { background: #10b981; color: #fff; transform: translateY(-3px); }

    .btn-cancel { color: #ef4444; background: #fef2f2; }
    .btn-cancel:hover { background: #ef4444; color: #fff; transform: translateY(-3px); }

    .btn-white { background: #fff; color: var(--slate-900); }
</style>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>

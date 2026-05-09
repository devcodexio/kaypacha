<?php
// vistas/admin/testimonios/index.php
require_once __DIR__ . '/../../layout/headerr.php';
require_once __DIR__ . '/../../layout/sidebar_admin.php';

$testimonios = $testimonios ?? [];
?>

<div class="content-wrapper">
    <!-- 🔹 HEADER PRO -->
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h6 class="text-indigo fw-800 text-uppercase mb-2" style="letter-spacing: 3px; font-size: 0.75rem;">Reputación Online</h6>
            <h1 class="fw-800 text-slate-900 display-5 mb-0">Moderación de Reseñas</h1>
            <p class="text-muted fw-600 mt-2">Los clientes envían sus experiencias desde su panel. Aprueba las mejores para mostrarlas en la web.</p>
        </div>
        <div class="d-flex gap-3">
            <button class="btn btn-white shadow-sm border-0 rounded-16 px-4 py-3 fw-700" onclick="window.location.reload()">
                <i class="fas fa-sync-alt me-2 text-indigo"></i> Sincronizar
            </button>
        </div>
    </div>

    <!-- 🔹 TABLA DE MODERACIÓN -->
    <div class="card-pro p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table-pro mb-0">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th class="text-center">Calificación</th>
                        <th style="width: 40%;">Comentario Original</th>
                        <th>Estado Actual</th>
                        <th class="text-end">Moderación</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($testimonios)): ?>
                        <?php foreach ($testimonios as $t): 
                            $status = strtolower($t['estado']);
                            $isActive = ($status === 'activo');
                            $stColor = $isActive ? '#10b981' : '#f59e0b';
                        ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-pro">
                                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($t['cliente']) ?>&background=random&bold=true" alt="">
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="fw-800 text-slate-900"><?= htmlspecialchars($t['cliente']) ?></span>
                                            <span class="text-slate-400 small fw-600"><?= htmlspecialchars($t['correo']) ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="stars-pro justify-content-center">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star <?= $i <= $t['calificacion'] ? 'active' : '' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="p-3 bg-slate-50 rounded-16 italic text-slate-600 fw-500" style="font-size: 0.9rem; line-height: 1.5; border: 1px dashed var(--slate-200);">
                                        "<?= htmlspecialchars($t['mensaje']) ?>"
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="status-dot" style="background: <?= $stColor ?>;"></div>
                                        <span class="fw-800 text-uppercase" style="color: <?= $stColor ?>; font-size: 0.7rem; letter-spacing: 1px;">
                                            <?= $status ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <?php if(!$isActive): ?>
                                            <a href="?accion=estado&id=<?= $t['id'] ?>&nuevo=activo" class="action-btn-pro btn-approve" title="Aprobar para la web">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="?accion=estado&id=<?= $t['id'] ?>&nuevo=pendiente" class="action-btn-pro btn-reject" title="Ocultar de la web">
                                                <i class="fas fa-eye-slash"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <a href="?accion=eliminar&id=<?= $t['id'] ?>" class="action-btn-pro btn-delete" title="Eliminar permanentemente" onclick="return confirm('¿Eliminar esta reseña definitivamente?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center py-5 text-muted fw-600">No hay nuevas reseñas para moderar.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .avatar-pro img { width: 44px; height: 44px; border-radius: 14px; border: 2px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
    .stars-pro { color: #e2e8f0; font-size: 0.8rem; display: flex; gap: 2px; }
    .stars-pro .active { color: #f59e0b; }
    .status-dot { width: 8px; height: 8px; border-radius: 50%; }

    .action-btn-pro {
        width: 42px; height: 42px; border-radius: 12px; display: flex;
        align-items: center; justify-content: center; text-decoration: none;
        transition: 0.3s;
    }
    .btn-approve { background: #dcfce7; color: #16a34a; }
    .btn-approve:hover { background: #16a34a; color: #fff; transform: translateY(-3px); }
    
    .btn-reject { background: #fef9c3; color: #ca8a04; }
    .btn-reject:hover { background: #ca8a04; color: #fff; transform: translateY(-3px); }

    .btn-delete { background: #fee2e2; color: #dc2626; }
    .btn-delete:hover { background: #dc2626; color: #fff; transform: translateY(-3px); }
</style>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>

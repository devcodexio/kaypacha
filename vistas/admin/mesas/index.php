<?php
// vistas/admin/mesas/index.php
require_once __DIR__ . '/../../layout/headerr.php';
require_once __DIR__ . '/../../layout/sidebar_admin.php';

$mesas = $mesas ?? [];
?>

<div class="content-wrapper">
    <!-- 🔹 HEADER PRO -->
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h6 class="text-indigo fw-800 text-uppercase mb-2" style="letter-spacing: 3px; font-size: 0.75rem;">Configuración de Salón</h6>
            <h1 class="fw-800 text-slate-900 display-5 mb-0">Gestión de Mesas</h1>
        </div>
        <div class="d-flex gap-3">
            <a href="?accion=croquis" class="btn btn-white shadow-sm border-0 rounded-16 px-4 py-3 fw-800">
                <i class="fas fa-map-location-dot me-2 text-indigo"></i> Ver Croquis
            </a>
            <a href="?accion=crear" class="btn btn-primary shadow-pro border-0 rounded-16 px-4 py-3 fw-800" style="background: var(--slate-900);">
                <i class="fas fa-plus me-2"></i> Nueva Mesa
            </a>
        </div>
    </div>

    <!-- 🔹 TABLA PROFESIONAL v2 -->
    <div class="table-responsive">
        <table class="table-pro">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Identificador</th>
                    <th>Capacidad Máxima</th>
                    <th>Zona / Área</th>
                    <th>Estado Operativo</th>
                    <th class="text-end">Operaciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($mesas)): ?>
                    <?php foreach($mesas as $m): 
                        $st = strtolower($m['estado'] ?? 'libre');
                        $stColor = ($st === 'libre') ? '#10b981' : (($st === 'ocupada') ? '#ef4444' : '#f59e0b');
                    ?>
                        <tr>
                            <td><span class="fw-800 text-slate-900">#<?= (int)$m['id'] ?></span></td>
                            <td>
                                <div class="mesa-circle-pro">
                                    <span><?= htmlspecialchars($m['numero_mesa']) ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-user-group text-slate-400"></i>
                                    <span class="fw-800 text-slate-900"><?= (int)$m['capacidad'] ?> Asientos</span>
                                </div>
                            </td>
                            <td>
                                <div class="zona-badge-pro">
                                    <i class="fas fa-layer-group me-2"></i>
                                    <?= htmlspecialchars($m['zona'] ?? 'Área General') ?>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="status-dot" style="background: <?= $stColor ?>;"></div>
                                    <span class="fw-800 text-uppercase" style="color: <?= $stColor ?>; font-size: 0.7rem; letter-spacing: 1.5px;"><?= $st ?></span>
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="?accion=editar&id=<?= $m['id'] ?>" class="action-btn-pro btn-edit" title="Modificar Parámetros">
                                        <i class="fas fa-gear"></i>
                                    </a>
                                    <form method="POST" action="?accion=eliminar" class="d-inline">
                                        <input type="hidden" name="id" value="<?= $m['id'] ?>">
                                        <button type="submit" class="action-btn-pro btn-delete" onclick="return confirm('¿Desactivar esta unidad operativa?')" title="Retirar de Servicio">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center py-5 text-muted fw-500">No se han configurado unidades de mesa.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .mesa-circle-pro {
        width: 48px; height: 48px;
        background: var(--slate-900);
        color: white;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 1.2rem;
    }

    .zona-badge-pro {
        display: inline-flex; align-items: center;
        background: #f1f5f9; color: #475569;
        padding: 8px 16px; border-radius: 12px;
        font-weight: 800; font-size: 0.8rem;
    }

    .status-dot { width: 10px; height: 10px; border-radius: 50%; }

    .action-btn-pro {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        font-size: 1.1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        border: none;
    }

    .btn-edit { color: #6366f1; background: #eef2ff; }
    .btn-edit:hover { background: #6366f1; color: #fff; transform: translateY(-3px); }

    .btn-delete { color: #ef4444; background: #fef2f2; }
    .btn-delete:hover { background: #ef4444; color: #fff; transform: translateY(-3px); }

    .btn-white { background: #fff; color: var(--slate-900); }
</style>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
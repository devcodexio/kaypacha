<?php
// vistas/empleado/reservas/index.php
require_once __DIR__ . '/../../layout/headerr.php';
require_once __DIR__ . '/../../layout/sidebar_empleado.php';

$reservas = $reservas ?? [];
?>

<div class="content-wrapper">
    <!-- 🔹 HEADER PRO -->
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h6 class="text-indigo fw-800 text-uppercase mb-2" style="letter-spacing: 3px; font-size: 0.75rem;">Operaciones de Salón</h6>
            <h1 class="fw-800 text-slate-900 display-5 mb-0">Gestión de Reservas</h1>
        </div>
        <div class="d-flex gap-3">
            <button class="btn btn-white shadow-sm border-0 rounded-16 px-4 py-3 fw-700" onclick="window.location.reload()">
                <i class="fas fa-sync-alt me-2 text-indigo"></i> Sincronizar
            </button>
            <a href="/clientes/controladores/empleado/ReservasEmpleadoController.php?accion=crear" class="btn btn-primary shadow-pro border-0 rounded-16 px-4 py-3 fw-800" style="background: var(--slate-900);">
                <i class="fas fa-plus me-2"></i> Nueva Reserva
            </a>
        </div>
    </div>

    <!-- 🔹 TABLA PROFESIONAL -->
    <div class="card-pro p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table-pro mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Mesa</th>
                        <th>Fecha / Hora</th>
                        <th class="text-center">Personas</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($reservas)): ?>
                        <?php foreach ($reservas as $r): 
                            $statusClass = '';
                            $st = strtolower($r['estado']);
                            if($st === 'confirmado') $statusClass = 'text-success';
                            elseif($st === 'pendiente') $statusClass = 'text-warning';
                            else $statusClass = 'text-danger';
                        ?>
                            <tr>
                                <td class="fw-800 text-slate-400">#<?= (int)$r['id'] ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-indigo-subtle text-indigo rounded-circle fw-800 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; font-size: 0.8rem;">
                                            <?= strtoupper(substr($r['cliente'], 0, 2)) ?>
                                        </div>
                                        <span class="fw-800 text-slate-900"><?= htmlspecialchars($r['cliente']) ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-inline-flex align-items-center gap-2 bg-slate-50 px-3 py-2 rounded-12 border border-light">
                                        <i class="fas fa-chair text-slate-400"></i>
                                        <span class="fw-800 text-slate-900">Mesa <?= htmlspecialchars($r['numero_mesa']) ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-800 text-slate-900"><?= htmlspecialchars($r['fecha']) ?></div>
                                    <div class="text-muted small fw-600"><?= htmlspecialchars($r['hora']) ?></div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-slate-100 text-slate-700 rounded-pill px-3 py-2 fw-800">
                                        <?= (int)$r['cantidad_personas'] ?> pax
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="status-dot <?= $st === 'confirmado' ? 'bg-success' : ($st === 'pendiente' ? 'bg-warning' : 'bg-danger') ?>"></div>
                                        <span class="fw-800 text-uppercase <?= $statusClass ?>" style="font-size: 0.7rem; letter-spacing: 1px;"><?= $st ?></span>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <?php if($st === 'pendiente'): ?>
                                            <a href="?accion=estado&id=<?= $r['id'] ?>&nuevo=confirmado" class="action-btn-pro btn-confirm" title="Confirmar">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="?accion=estado&id=<?= $r['id'] ?>&nuevo=cancelado" 
                                           class="action-btn-pro btn-delete" 
                                           onclick="return confirm('¿Cancelar esta reserva?')" 
                                           title="Anular">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center py-5 text-muted fw-600">No hay reservas registradas para el periodo actual.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .status-dot { width: 8px; height: 8px; border-radius: 50%; }
    .bg-indigo-subtle { background: #e0e7ff; }
    
    .action-btn-pro {
        width: 40px; height: 40px; border-radius: 12px; display: flex;
        align-items: center; justify-content: center; text-decoration: none;
        transition: 0.3s; border: none;
    }
    .btn-confirm { background: #dcfce7; color: #16a34a; }
    .btn-confirm:hover { background: #16a34a; color: #fff; transform: translateY(-2px); }
    .btn-delete { background: #fee2e2; color: #dc2626; }
    .btn-delete:hover { background: #dc2626; color: #fff; transform: translateY(-2px); }
</style>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>

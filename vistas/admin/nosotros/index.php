<?php
// vistas/admin/nosotros/index.php
require_once __DIR__ . '/../../layout/headerr.php';
require_once __DIR__ . '/../../layout/sidebar_admin.php';
?>

<div class="content-wrapper">
    <!-- 🔹 HEADER PRO -->
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h6 class="text-indigo fw-800 text-uppercase mb-2" style="letter-spacing: 3px; font-size: 0.75rem;">Identidad Corporativa</h6>
            <h1 class="fw-800 text-slate-900 display-5 mb-0">Sobre Nosotros</h1>
        </div>
        <div>
            <a href="/clientes/controladores/admin/NosotrosAdminController.php?accion=crear" class="btn btn-primary shadow-pro border-0 rounded-16 px-4 py-3 fw-800" style="background: var(--slate-900);">
                <i class="fas fa-plus me-2"></i> Nueva Sección
            </a>
        </div>
    </div>

    <!-- 🔹 TABLA PROFESIONAL v2 -->
    <div class="table-responsive">
        <table class="table-pro">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Bloque Operativo</th>
                    <th>Título de Sección</th>
                    <th>Contenido Narrativo</th>
                    <th class="text-end">Operaciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($bloques)): ?>
                    <?php foreach ($bloques as $b): ?>
                        <tr>
                            <td><span class="fw-800 text-slate-900">#<?= (int)$b['id'] ?></span></td>
                            <td>
                                <div class="type-badge-pro">
                                    <i class="fas fa-fingerprint me-2"></i>
                                    <?= strtoupper(htmlspecialchars($b['tipo'])) ?>
                                </div>
                            </td>
                            <td>
                                <span class="fw-800 text-slate-900 fs-6"><?= htmlspecialchars($b['titulo']) ?></span>
                            </td>
                            <td>
                                <p class="text-slate-500 fw-500 mb-0" style="max-width: 450px;">
                                    <?= nl2br(htmlspecialchars(mb_strimwidth($b['descripcion'], 0, 120, "..."))) ?>
                                </p>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="/clientes/controladores/admin/NosotrosAdminController.php?accion=editar&id=<?= $b['id'] ?>" 
                                       class="action-btn-pro btn-edit" title="Editar Contenido">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="/clientes/controladores/admin/NosotrosAdminController.php?accion=eliminar&id=<?= $b['id'] ?>" 
                                       class="action-btn-pro btn-delete" title="Archivar Sección"
                                       onclick="return confirm('¿Seguro que desea eliminar esta sección?')">
                                        <i class="fas fa-archive"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center py-5 text-muted fw-500">No hay bloques informativos configurados.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .type-badge-pro {
        display: inline-flex; align-items: center;
        background: #f1f5f9; color: #475569;
        padding: 8px 16px; border-radius: 12px;
        font-weight: 800; font-size: 0.75rem; letter-spacing: 1px;
    }

    .action-btn-pro {
        width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;
        border-radius: 16px; font-size: 1.1rem; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
    }
    .btn-edit { color: #6366f1; background: #eef2ff; }
    .btn-edit:hover { background: #6366f1; color: #fff; transform: scale(1.1); }

    .btn-delete { color: #ef4444; background: #fef2f2; }
    .btn-delete:hover { background: #ef4444; color: #fff; transform: scale(1.1); }
</style>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>

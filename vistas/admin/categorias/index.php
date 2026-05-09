<?php
// vistas/admin/categorias/index.php
require_once __DIR__ . '/../../layout/headerr.php';
require_once __DIR__ . '/../../layout/sidebar_admin.php';
?>

<div class="content-wrapper">
    <!-- 🔹 HEADER PRO -->
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h6 class="text-indigo fw-800 text-uppercase mb-2" style="letter-spacing: 3px; font-size: 0.75rem;">Arquitectura de Menú</h6>
            <h1 class="fw-800 text-slate-900 display-5 mb-0">Categorización</h1>
        </div>
        <div>
            <a href="/clientes/controladores/admin/CategoriasAdminController.php?accion=crear" class="btn btn-primary shadow-pro border-0 rounded-16 px-4 py-3 fw-800" style="background: var(--slate-900);">
                <i class="fas fa-folder-plus me-2"></i> Crear Categoría
            </a>
        </div>
    </div>

    <!-- 🔹 TABLA PROFESIONAL v2 -->
    <div class="table-responsive">
        <table class="table-pro">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de Categoría</th>
                    <th>Descripción Detallada</th>
                    <th class="text-end">Operaciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($categorias)): ?>
                    <?php foreach($categorias as $c): ?>
                        <tr>
                            <td><span class="fw-800 text-slate-900">#<?= (int)$c['id'] ?></span></td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="cat-icon-pro">
                                        <i class="fas fa-tag"></i>
                                    </div>
                                    <span class="fw-800 text-slate-900 fs-5"><?= htmlspecialchars($c['nombre']) ?></span>
                                </div>
                            </td>
                            <td>
                                <p class="text-slate-500 fw-500 mb-0" style="max-width: 400px;"><?= htmlspecialchars($c['descripcion']) ?></p>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="/clientes/controladores/admin/CategoriasAdminController.php?accion=editar&id=<?= $c['id'] ?>" 
                                       class="action-btn-pro btn-edit" title="Editar Categoría">
                                        <i class="fas fa-pen-nib"></i>
                                    </a>
                                    <a href="/clientes/controladores/admin/CategoriasAdminController.php?accion=eliminar&id=<?= $c['id'] ?>" 
                                       class="action-btn-pro btn-delete" title="Archivar Categoría"
                                       onclick="return confirm('¿Seguro que desea eliminar esta categoría?')">
                                        <i class="fas fa-box-archive"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center py-5 text-muted fw-500">No se han definido categorías de menú.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .cat-icon-pro {
        width: 48px; height: 48px;
        background: #f1f5f9;
        color: #6366f1;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
    }

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
    }

    .btn-edit { color: #6366f1; background: #eef2ff; }
    .btn-edit:hover { background: #6366f1; color: #fff; transform: rotate(15deg) scale(1.1); }

    .btn-delete { color: #ef4444; background: #fef2f2; }
    .btn-delete:hover { background: #ef4444; color: #fff; transform: rotate(-15deg) scale(1.1); }
</style>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>

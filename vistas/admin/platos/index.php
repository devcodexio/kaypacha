<?php
// vistas/admin/platos/index.php
require_once __DIR__ . '/../../layout/headerr.php';
require_once __DIR__ . '/../../layout/sidebar_admin.php';
?>

<div class="content-wrapper">
    <!-- 🔹 HEADER PRO -->
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h6 class="text-indigo fw-800 text-uppercase mb-2" style="letter-spacing: 3px; font-size: 0.75rem;">Operaciones de Cocina</h6>
            <h1 class="fw-800 text-slate-900 display-5 mb-0">Catálogo Gastronómico</h1>
        </div>
        <div>
            <a href="/clientes/controladores/admin/PlatosAdminController.php?accion=crear" class="btn btn-primary shadow-pro border-0 rounded-16 px-4 py-3 fw-800" style="background: var(--slate-900);">
                <i class="fas fa-utensils me-2"></i> Añadir Plato
            </a>
        </div>
    </div>

    <!-- 🔹 TABLA PROFESIONAL v2 -->
    <div class="table-responsive">
        <table class="table-pro">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Previsualización</th>
                    <th>Nombre del Item</th>
                    <th>Categoría</th>
                    <th>Precio de Venta</th>
                    <th>Estado Stock</th>
                    <th class="text-end">Operaciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($platos)): ?>
                    <?php foreach($platos as $p): 
                        $disponible = (bool)$p['disponible'];
                        $stColor = $disponible ? '#10b981' : '#ef4444';
                        $stLabel = $disponible ? 'ACTIVO / DISPONIBLE' : 'AGOTADO / PAUSADO';
                    ?>
                        <tr>
                            <td><span class="fw-800 text-slate-900">#<?= (int)$p['id'] ?></span></td>
                            <td>
                                <div class="plato-img-pro">
                                    <?php if (!empty($p['imagen'])): ?>
                                        <img src="/clientes/uploads/<?= htmlspecialchars($p['imagen']) ?>" alt="">
                                    <?php else: ?>
                                        <div class="no-img-pro"><i class="fas fa-image"></i></div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <span class="fw-800 text-slate-900 fs-6"><?= htmlspecialchars($p['nombre']) ?></span>
                            </td>
                            <td>
                                <div class="category-badge-pro">
                                    <?= htmlspecialchars($p['category_name'] ?? $p['categoria']) ?>
                                </div>
                            </td>
                            <td>
                                <span class="fw-800 text-slate-900 fs-5">S/ <?= number_format($p['precio'], 2) ?></span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="status-dot" style="background: <?= $stColor ?>;"></div>
                                    <span class="fw-800" style="color: <?= $stColor ?>; font-size: 0.7rem; letter-spacing: 1px;"><?= $stLabel ?></span>
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="/clientes/controladores/admin/PlatosAdminController.php?accion=editar&id=<?= $p['id'] ?>" 
                                       class="action-btn-pro btn-edit" title="Editar Plato">
                                        <i class="fas fa-sliders"></i>
                                    </a>
                                    <a href="/clientes/controladores/admin/PlatosAdminController.php?accion=eliminar&id=<?= $p['id'] ?>" 
                                       class="action-btn-pro btn-delete" title="Eliminar del Menú"
                                       onclick="return confirm('¿Eliminar este plato permanentemente?')">
                                        <i class="fas fa-trash-can"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center py-5 text-muted fw-500">No se encontraron productos en el catálogo.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .plato-img-pro { width: 80px; height: 80px; border-radius: 20px; overflow: hidden; background: #f8fafc; border: 1px solid #f1f5f9; }
    .plato-img-pro img { width: 100%; height: 100%; object-fit: cover; }
    .no-img-pro { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #cbd5e1; font-size: 1.5rem; }

    .category-badge-pro {
        display: inline-flex;
        background: #f1f5f9;
        color: #64748b;
        padding: 6px 14px;
        border-radius: 10px;
        font-weight: 800;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .status-dot { width: 8px; height: 8px; border-radius: 50%; }

    .action-btn-pro {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-edit { color: #6366f1; background: #eef2ff; }
    .btn-edit:hover { background: #6366f1; color: #fff; transform: scale(1.1); }

    .btn-delete { color: #ef4444; background: #fef2f2; }
    .btn-delete:hover { background: #ef4444; color: #fff; transform: scale(1.1); }
</style>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>

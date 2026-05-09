<?php
// vistas/admin/categorias/form.php
require_once __DIR__ . '/../../layout/headerr.php';
require_once __DIR__ . '/../../layout/sidebar_admin.php';

$editando = isset($categoria);
$action = "/clientes/controladores/admin/CategoriasAdminController.php?accion=guardar";
?>

<div class="content-wrapper d-flex align-items-center justify-content-center">
    <div class="card-pro w-100" style="max-width: 600px;">
        
        <!-- 🔹 HEADER FORM -->
        <div class="text-center mb-5">
            <div class="form-icon-pro bg-primary-subtle mb-3">
                <i class="fas fa-tags text-primary"></i>
            </div>
            <h2 class="fw-800 text-slate-900"><?= $editando ? "Editar Categoría" : "Nueva Clasificación" ?></h2>
            <p class="text-muted fw-600">Define grupos lógicos para los platos del menú.</p>
        </div>

        <form action="<?= $action ?>" method="POST" class="pro-form-layout">
            <?php if ($editando): ?>
                <input type="hidden" name="id" value="<?= (int)$categoria['id'] ?>">
            <?php endif; ?>

            <div class="row g-4">
                <!-- NOMBRE -->
                <div class="col-12">
                    <div class="form-group-pro">
                        <label class="label-pro">Nombre de la Categoría</label>
                        <div class="input-with-icon">
                            <i class="fas fa-font"></i>
                            <input type="text" name="nombre" class="input-pro" placeholder="Ej. Entradas, Postres, etc." required value="<?= $editando ? htmlspecialchars($categoria['nombre']) : '' ?>">
                        </div>
                    </div>
                </div>

                <!-- DESCRIPCION -->
                <div class="col-12">
                    <div class="form-group-pro">
                        <label class="label-pro">Descripción</label>
                        <textarea name="descripcion" rows="4" class="input-pro" placeholder="Breve descripción de los platos que pertenecen a este grupo..."><?= $editando ? htmlspecialchars($categoria['descripcion']) : '' ?></textarea>
                    </div>
                </div>

                <!-- ACCIONES -->
                <div class="col-12 mt-5">
                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-16 fw-800 shadow-pro border-0" style="background: var(--slate-900);">
                        <?= $editando ? "GUARDAR CAMBIOS" : "CREAR CATEGORÍA" ?>
                    </button>
                    <a href="/clientes/controladores/admin/CategoriasAdminController.php?accion=index" class="btn btn-link w-100 mt-2 text-muted fw-700 text-decoration-none">
                        Descartar y volver
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .form-icon-pro { width: 70px; height: 70px; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-size: 1.8rem; }
    .bg-primary-subtle { background: #f0f9ff; }

    .form-group-pro { display: flex; flex-direction: column; gap: 8px; }
    .label-pro { font-weight: 800; font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px; }
    
    .input-pro {
        width: 100%; padding: 14px 20px; border-radius: 16px;
        border: 1px solid #e2e8f0; background: #f8fafc;
        font-weight: 600; color: #1e293b; transition: all 0.3s ease;
    }
    
    .input-with-icon { position: relative; }
    .input-with-icon i { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
    .input-with-icon .input-pro { padding-left: 50px; }

    .input-pro:focus {
        border-color: #6366f1; background: #fff;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        outline: none;
    }
</style>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>

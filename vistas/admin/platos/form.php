<?php
// vistas/admin/platos/form.php
require_once __DIR__ . '/../../layout/headerr.php';
require_once __DIR__ . '/../../layout/sidebar_admin.php';

$editando = isset($plato);
$action = "/clientes/controladores/admin/PlatosAdminController.php?accion=guardar";
$listaCategorias = $categorias ?? [];
?>

<div class="content-wrapper d-flex align-items-center justify-content-center">
    <div class="card-pro w-100" style="max-width: 800px;">
        
        <!-- 🔹 HEADER FORM -->
        <div class="text-center mb-5">
            <div class="form-icon-pro bg-warning-subtle mb-3">
                <i class="fas fa-utensils text-warning"></i>
            </div>
            <h2 class="fw-800 text-slate-900"><?= $editando ? "Optimizar Plato" : "Nuevo Item de Menú" ?></h2>
            <p class="text-muted fw-600">Define los atributos gastronómicos y visuales del producto.</p>
        </div>

        <form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="pro-form-layout">
            <?php if ($editando): ?>
                <input type="hidden" name="id" value="<?= (int)$plato['id'] ?>">
                <input type="hidden" name="imagen_actual" value="<?= htmlspecialchars($plato['imagen']) ?>">
            <?php endif; ?>

            <div class="row g-4">
                <!-- COLUMNA IZQUIERDA: DATOS -->
                <div class="col-lg-7">
                    <div class="row g-3">
                        <!-- CATEGORÍA -->
                        <div class="col-12">
                            <div class="form-group-pro">
                                <label class="label-pro">Clasificación</label>
                                <select name="categoria_id" class="select-pro" required>
                                    <option value="">Seleccione una categoría...</option>
                                    <?php
                                    $catActual = $editando ? (int)$plato['categoria_id'] : 0;
                                    foreach ($listaCategorias as $cat): ?>
                                        <option value="<?= (int)$cat['id'] ?>" <?= $catActual == $cat['id'] ? 'selected' : '' ?>>
                                            <?= strtoupper(htmlspecialchars($cat['nombre'])) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- NOMBRE -->
                        <div class="col-12">
                            <div class="form-group-pro">
                                <label class="label-pro">Nombre del Plato</label>
                                <input type="text" name="nombre" class="input-pro" placeholder="Ej. Lomo Saltado Premium" value="<?= $editando ? htmlspecialchars($plato['nombre']) : '' ?>" required>
                            </div>
                        </div>

                        <!-- PRECIO -->
                        <div class="col-md-6">
                            <div class="form-group-pro">
                                <label class="label-pro">Precio (S/)</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-coins"></i>
                                    <input type="number" step="0.01" min="0" name="precio" class="input-pro ps-5" placeholder="0.00" value="<?= $editando ? htmlspecialchars($plato['precio']) : '' ?>" required>
                                </div>
                            </div>
                        </div>

                        <!-- DISPONIBILIDAD -->
                        <div class="col-md-6">
                            <div class="form-group-pro">
                                <label class="label-pro">Estado Inicial</label>
                                <?php $disp = $editando ? (int)$plato['disponible'] : 1; ?>
                                <select name="disponible" class="select-pro" required>
                                    <option value="1" <?= $disp === 1 ? 'selected' : '' ?>>✓ DISPONIBLE</option>
                                    <option value="0" <?= $disp === 0 ? 'selected' : '' ?>>✕ AGOTADO</option>
                                </select>
                            </div>
                        </div>

                        <!-- DESCRIPCION -->
                        <div class="col-12">
                            <div class="form-group-pro">
                                <label class="label-pro">Descripción Detallada</label>
                                <textarea name="descripcion" rows="4" class="input-pro" placeholder="Describe los ingredientes, preparación o alérgenos..."><?= $editando ? htmlspecialchars($plato['descripcion']) : '' ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COLUMNA DERECHA: MEDIA -->
                <div class="col-lg-5">
                    <div class="form-group-pro h-100 d-flex flex-column">
                        <label class="label-pro mb-3">Imagen del Plato</label>
                        <div class="image-upload-wrapper-pro flex-grow-1">
                            <?php 
                                $imgPath = ($editando && !empty($plato['imagen'])) ? "/clientes/uploads/".htmlspecialchars($plato['imagen']) : "/clientes/img/no-image.png";
                            ?>
                            <div class="preview-container-pro shadow-sm">
                                <img src="<?= $imgPath ?>" id="previewImg" alt="Preview">
                                <div class="upload-overlay-pro">
                                    <i class="fas fa-camera"></i>
                                    <span>CAMBIAR IMAGEN</span>
                                    <input type="file" name="imagen" class="file-input-pro" accept="image/*" onchange="previewImage(event)">
                                </div>
                            </div>
                            <p class="text-muted small mt-3 text-center fw-600">Formatos: JPG, PNG. Máx 2MB.</p>
                        </div>
                    </div>
                </div>

                <!-- ACCIONES -->
                <div class="col-12 mt-4">
                    <hr class="border-slate-100 mb-4">
                    <div class="d-flex gap-3">
                        <a href="/clientes/controladores/admin/PlatosAdminController.php?accion=index" class="btn btn-white border rounded-16 px-4 py-3 fw-800 text-muted">
                            DESCARTAR
                        </a>
                        <button type="submit" class="btn btn-primary flex-grow-1 py-3 rounded-16 fw-800 shadow-pro border-0" style="background: var(--slate-900);">
                            <?= strtoupper($editando ? 'Guardar Cambios' : 'Publicar en el Menú') ?>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .form-icon-pro { width: 70px; height: 70px; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-size: 1.8rem; }
    .bg-warning-subtle { background: #fffbeb; }

    .form-group-pro { display: flex; flex-direction: column; gap: 8px; }
    .label-pro { font-weight: 800; font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px; }
    
    .input-pro, .select-pro {
        width: 100%; padding: 14px 20px; border-radius: 16px;
        border: 1px solid #e2e8f0; background: #f8fafc;
        font-weight: 600; color: #1e293b; transition: all 0.3s ease;
    }
    
    .input-with-icon { position: relative; }
    .input-with-icon i { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #94a3b8; }

    .input-pro:focus, .select-pro:focus {
        border-color: #6366f1; background: #fff;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        outline: none;
    }

    /* IMAGE UPLOAD STYLES */
    .preview-container-pro {
        width: 100%; height: 100%; min-height: 250px;
        border-radius: 24px; overflow: hidden; position: relative;
        border: 2px dashed #e2e8f0; background: #f8fafc;
    }
    .preview-container-pro img { width: 100%; height: 100%; object-fit: cover; }
    
    .upload-overlay-pro {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px);
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        color: white; opacity: 0; transition: 0.3s; cursor: pointer;
    }
    .preview-container-pro:hover .upload-overlay-pro { opacity: 1; }
    .upload-overlay-pro i { font-size: 2rem; margin-bottom: 10px; }
    .upload-overlay-pro span { font-weight: 800; font-size: 0.8rem; letter-spacing: 1px; }

    .file-input-pro {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        opacity: 0; cursor: pointer;
    }

    .btn-white { background: #fff; color: var(--slate-900); }
</style>

<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function () {
        document.getElementById('previewImg').src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>

<?php
// vistas/admin/nosotros/form.php
require_once __DIR__ . '/../../layout/headerr.php';
require_once __DIR__ . '/../../layout/sidebar_admin.php';

$editando = isset($bloque);
$action = "/clientes/controladores/admin/NosotrosAdminController.php?accion=guardar";
?>

<div class="content-wrapper d-flex align-items-center justify-content-center">
    <div class="card-pro w-100" style="max-width: 750px;">
        
        <!-- 🔹 HEADER FORM -->
        <div class="text-center mb-5">
            <div class="form-icon-pro bg-indigo-subtle mb-3">
                <i class="fas fa-building text-indigo"></i>
            </div>
            <h2 class="fw-800 text-slate-900"><?= $editando ? "Editar Sección" : "Configurar Identidad" ?></h2>
            <p class="text-muted fw-600">Define los pilares institucionales y la narrativa de la marca.</p>
        </div>

        <form action="<?= $action ?>" method="POST" class="pro-form-layout">
            <?php if ($editando): ?>
                <input type="hidden" name="id" value="<?= (int)$bloque['id'] ?>">
            <?php endif; ?>

            <div class="row g-4">
                <!-- TIPO DE SECCIÓN -->
                <div class="col-md-5">
                    <div class="form-group-pro">
                        <label class="label-pro">Tipo de Bloque</label>
                        <div class="input-with-icon">
                            <i class="fas fa-layer-group"></i>
                            <?php $tipo = $editando ? $bloque['tipo'] : 'mision'; ?>
                            <select name="tipo" class="input-pro" required>
                                <option value="mision"   <?= $tipo === 'mision' ? 'selected' : '' ?>>Misión Corporativa</option>
                                <option value="vision"   <?= $tipo === 'vision' ? 'selected' : '' ?>>Visión a Futuro</option>
                                <option value="valores"  <?= $tipo === 'valores' ? 'selected' : '' ?>>Valores & Ética</option>
                                <option value="historia" <?= $tipo === 'historia' ? 'selected' : '' ?>>Nuestra Historia</option>
                                <option value="otro"     <?= $tipo === 'otro' ? 'selected' : '' ?>>Otro Componente</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- TÍTULO -->
                <div class="col-md-7">
                    <div class="form-group-pro">
                        <label class="label-pro">Título Principal</label>
                        <div class="input-with-icon">
                            <i class="fas fa-heading"></i>
                            <input type="text" name="titulo" class="input-pro" placeholder="Ej. El inicio de nuestra pasión..." value="<?= $editando ? htmlspecialchars($bloque['titulo']) : '' ?>" required>
                        </div>
                    </div>
                </div>

                <!-- DESCRIPCION / CONTENIDO -->
                <div class="col-12">
                    <div class="form-group-pro">
                        <label class="label-pro">Narrativa Detallada</label>
                        <textarea name="descripcion" rows="8" class="input-pro" placeholder="Escribe aquí el contenido extenso de esta sección..." required><?= $editando ? htmlspecialchars($bloque['descripcion']) : '' ?></textarea>
                    </div>
                </div>

                <!-- ACCIONES -->
                <div class="col-12 mt-5">
                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-16 fw-800 shadow-pro border-0" style="background: var(--slate-900);">
                        <?= $editando ? "GUARDAR MODIFICACIONES" : "PUBLICAR SECCIÓN" ?>
                    </button>
                    <a href="/clientes/controladores/admin/NosotrosAdminController.php?accion=index" class="btn btn-link w-100 mt-2 text-muted fw-700 text-decoration-none">
                        Cancelar y salir
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .form-icon-pro { width: 70px; height: 70px; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-size: 1.8rem; }
    .bg-indigo-subtle { background: #eef2ff; }

    .form-group-pro { display: flex; flex-direction: column; gap: 8px; }
    .label-pro { font-weight: 800; font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px; }
    
    .input-pro {
        width: 100%; padding: 14px 20px; border-radius: 16px;
        border: 1px solid #e2e8f0; background: #f8fafc;
        font-weight: 600; color: #1e293b; transition: all 0.3s ease;
        appearance: none;
    }
    
    .input-with-icon { position: relative; }
    .input-with-icon i { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none; }
    .input-with-icon .input-pro { padding-left: 50px; }

    .input-pro:focus {
        border-color: #6366f1; background: #fff;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        outline: none;
    }

    select.input-pro { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 15px center; background-size: 18px; }
</style>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>

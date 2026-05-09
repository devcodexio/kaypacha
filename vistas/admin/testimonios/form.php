<?php
// vistas/admin/testimonios/form.php
require_once __DIR__ . '/../../layout/headerr.php';
require_once __DIR__ . '/../../layout/sidebar_admin.php';

$editando = isset($testimonio);
$action = "/clientes/controladores/admin/TestimoniosAdminController.php?accion=guardar";
$clientes = $clientes ?? [];
?>

<div class="content-wrapper d-flex align-items-center justify-content-center">
    <div class="card-pro w-100" style="max-width: 700px;">
        
        <!-- 🔹 HEADER FORM -->
        <div class="text-center mb-5">
            <div class="form-icon-pro bg-warning-subtle mb-3">
                <i class="fas fa-star text-warning"></i>
            </div>
            <h2 class="fw-800 text-slate-900"><?= $editando ? "Editar Feedback" : "Registrar Testimonio" ?></h2>
            <p class="text-muted fw-600">Gestiona la reputación pública y las reseñas de los clientes.</p>
        </div>

        <form action="<?= $action ?>" method="POST" class="pro-form-layout">
            <?php if ($editando): ?>
                <input type="hidden" name="id" value="<?= (int)$testimonio['id'] ?>">
            <?php endif; ?>

            <div class="row g-4">
                <!-- CLIENTE SELECT -->
                <div class="col-md-8">
                    <div class="form-group-pro">
                        <label class="label-pro">Cliente Asociado</label>
                        <div class="input-with-icon">
                            <i class="fas fa-user-circle"></i>
                            <select name="usuario_id" class="input-pro" required>
                                <option value="">Seleccione el autor...</option>
                                <?php
                                $cliActual = $editando ? (int)$testimonio['usuario_id'] : 0;
                                foreach ($clientes as $cli): ?>
                                    <option value="<?= (int)$cli['id'] ?>" <?= $cliActual === (int)$cli['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cli['nombre']) ?> (<?= htmlspecialchars($cli['correo']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- CALIFICACION -->
                <div class="col-md-4">
                    <div class="form-group-pro">
                        <label class="label-pro">Puntuación</label>
                        <div class="input-with-icon">
                            <i class="fas fa-award"></i>
                            <?php $cal = $editando ? (int)$testimonio['calificacion'] : 5; ?>
                            <select name="calificacion" class="input-pro" required>
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <option value="<?= $i ?>" <?= $i === $cal ? 'selected' : '' ?>>
                                        <?= $i ?> Estrellas
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- MENSAJE -->
                <div class="col-12">
                    <div class="form-group-pro">
                        <label class="label-pro">Contenido de la Reseña</label>
                        <textarea name="mensaje" rows="5" class="input-pro" placeholder="Escribe el testimonio exactamente como el cliente lo proporcionó..." required><?= $editando ? htmlspecialchars($testimonio['mensaje']) : '' ?></textarea>
                    </div>
                </div>

                <!-- ESTADO -->
                <div class="col-md-6">
                    <div class="form-group-pro">
                        <label class="label-pro">Visibilidad</label>
                        <div class="input-with-icon">
                            <i class="fas fa-eye"></i>
                            <?php $estado = $editando ? $testimonio['estado'] : 'activo'; ?>
                            <select name="estado" class="input-pro" required>
                                <option value="activo" <?= $estado === 'activo' ? 'selected' : '' ?>>Publicado / Visible</option>
                                <option value="oculto" <?= $estado === 'oculto' ? 'selected' : '' ?>>Borrador / Oculto</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- ACCIONES -->
                <div class="col-12 mt-5">
                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-16 fw-800 shadow-pro border-0" style="background: var(--slate-900);">
                        <?= $editando ? "ACTUALIZAR TESTIMONIO" : "CONFIRMAR REGISTRO" ?>
                    </button>
                    <a href="/clientes/controladores/admin/TestimoniosAdminController.php?accion=index" class="btn btn-link w-100 mt-2 text-muted fw-700 text-decoration-none">
                        Regresar al listado
                    </a>
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

<?php
// vistas/admin/usuarios/form.php
require_once __DIR__ . '/../../layout/headerr.php';
require_once __DIR__ . '/../../layout/sidebar_admin.php';
require_once __DIR__ . '/../../../conexion.php';

$usuario = $usuario ?? [];
$editando = !empty($usuario['id']);
$action = "/clientes/controladores/admin/UsuariosAdminController.php?accion=guardar";

$roles = [];
$resultRoles = $conexion->query("SELECT id, nombre FROM roles ORDER BY id ASC");
if ($resultRoles) {
    while ($r = $resultRoles->fetch_assoc()) { $roles[] = $r; }
}
?>

<div class="content-wrapper d-flex align-items-center justify-content-center">
    <div class="card-pro w-100" style="max-width: 600px;">
        
        <!-- 🔹 HEADER FORM -->
        <div class="text-center mb-5">
            <div class="form-icon-pro bg-indigo-subtle mb-3">
                <i class="fas <?= $editando ? 'fa-user-pen' : 'fa-user-plus' ?> text-indigo"></i>
            </div>
            <h2 class="fw-800 text-slate-900"><?= $editando ? "Editar Perfil" : "Nuevo Usuario" ?></h2>
            <p class="text-muted fw-600">Completa la información técnica del usuario.</p>
        </div>

        <form action="<?= $action ?>" method="POST" class="pro-form-layout">
            <?php if($editando): ?>
                <input type="hidden" name="id" value="<?= (int)$usuario['id'] ?>">
            <?php endif; ?>

            <div class="row g-4">
                <!-- ROL -->
                <div class="col-12">
                    <div class="form-group-pro">
                        <label class="label-pro">Rol de Acceso</label>
                        <?php $rolActual = $editando ? (int)$usuario['rol_id'] : 3; ?>
                        <select name="rol_id" class="select-pro" required>
                            <?php foreach($roles as $rol): ?>
                                <option value="<?= $rol['id'] ?>" <?= $rolActual == $rol['id'] ? 'selected' : '' ?>>
                                    <?= strtoupper(htmlspecialchars($rol['nombre'])) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- NOMBRE -->
                <div class="col-12">
                    <div class="form-group-pro">
                        <label class="label-pro">Nombre Completo</label>
                        <div class="input-with-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" name="nombre" class="input-pro" placeholder="Ej. Juan Pérez" required value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <!-- CORREO -->
                <div class="col-md-6">
                    <div class="form-group-pro">
                        <label class="label-pro">Correo Electrónico</label>
                        <div class="input-with-icon">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="correo" class="input-pro" placeholder="usuario@email.com" required value="<?= htmlspecialchars($usuario['correo'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <!-- TELEFONO -->
                <div class="col-md-6">
                    <div class="form-group-pro">
                        <label class="label-pro">Teléfono</label>
                        <div class="input-with-icon">
                            <i class="fas fa-phone"></i>
                            <input type="text" name="telefono" class="input-pro" placeholder="+51 999..." value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <!-- DIRECCION -->
                <div class="col-12">
                    <div class="form-group-pro">
                        <label class="label-pro">Dirección</label>
                        <div class="input-with-icon">
                            <i class="fas fa-location-dot"></i>
                            <input type="text" name="direccion" class="input-pro" placeholder="Calle, Distrito..." value="<?= htmlspecialchars($usuario['direccion'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <!-- PASSWORD -->
                <div class="col-12">
                    <div class="form-group-pro">
                        <label class="label-pro">Contraseña <?= $editando ? "(Solo para cambiar)" : "" ?></label>
                        <div class="input-with-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" class="input-pro" placeholder="••••••••" <?= $editando ? "" : "required" ?>>
                        </div>
                    </div>
                </div>

                <!-- ACCIONES -->
                <div class="col-12 mt-5">
                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-16 fw-800 shadow-pro border-0" style="background: var(--slate-900);">
                        <?= $editando ? "ACTUALIZAR REGISTRO" : "CREAR CUENTA" ?>
                    </button>
                    <a href="/clientes/controladores/admin/UsuariosAdminController.php?accion=index" class="btn btn-link w-100 mt-2 text-muted fw-700 text-decoration-none">
                        Cancelar y volver
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .form-icon-pro {
        width: 70px; height: 70px; border-radius: 20px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto; font-size: 1.8rem;
    }

    .form-group-pro { display: flex; flex-direction: column; gap: 8px; }
    .label-pro { font-weight: 800; font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px; }
    
    .input-pro, .select-pro {
        width: 100%; padding: 14px 20px; border-radius: 16px;
        border: 1px solid #e2e8f0; background: #f8fafc;
        font-weight: 600; color: #1e293b; transition: all 0.3s ease;
    }
    
    .input-with-icon { position: relative; }
    .input-with-icon i { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.9rem; }
    .input-with-icon .input-pro { padding-left: 50px; }

    .input-pro:focus, .select-pro:focus {
        border-color: #6366f1; background: #fff;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        outline: none;
    }

    .bg-indigo-subtle { background: #eef2ff; }
</style>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
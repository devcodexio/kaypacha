<?php
// vistas/cliente/perfil/editar.php
require_once __DIR__ . '/../../layout/headerr.php';
require_once __DIR__ . '/../../layout/sidebar_cliente.php';

$usuario = $usuario ?? [];
?>

<div class="content-wrapper">
    <div class="container-fluid">
        
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
                
                <div class="mb-4">
                    <a href="/clientes/controladores/cliente/PerfilClienteController.php?accion=ver" class="text-decoration-none text-muted fw-600">
                        <i class="fas fa-arrow-left me-2"></i> Volver a mi perfil
                    </a>
                </div>

                <div class="card border-0 shadow-sm rounded-24">
                    <div class="card-body p-4 p-xl-5">
                        
                        <div class="row align-items-center mb-5">
                            <div class="col-md-auto text-center text-md-start mb-3 mb-md-0">
                                <div class="profile-avatar-edit">
                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($usuario['nombre'] ?? 'U') ?>&background=6366f1&color=fff&size=100&bold=true" alt="Avatar">
                                    <button class="btn-change-avatar"><i class="fas fa-camera"></i></button>
                                </div>
                            </div>
                            <div class="col-md">
                                <h2 class="fw-800 text-dark mb-1">Editar Perfil</h2>
                                <p class="text-secondary mb-0">Actualiza tu información personal y configuración de seguridad.</p>
                            </div>
                        </div>

                        <form action="/clientes/controladores/cliente/PerfilClienteController.php?accion=actualizar" method="POST" id="editProfileForm">
                            
                            <div class="section-divider mb-4">
                                <span class="fw-700 text-uppercase small text-muted ls-1">Información Básica</span>
                            </div>

                            <div class="row g-4 mb-5">
                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <label for="nombre" class="form-label fw-600">Nombre Completo</label>
                                        <div class="input-group-custom">
                                            <i class="fas fa-user"></i>
                                            <input type="text" class="form-control-custom" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>" required placeholder="Tu nombre">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <label for="correo" class="form-label fw-600">Correo Electrónico</label>
                                        <div class="input-group-custom">
                                            <i class="fas fa-envelope"></i>
                                            <input type="email" class="form-control-custom" id="correo" name="correo" value="<?= htmlspecialchars($usuario['correo'] ?? '') ?>" required placeholder="correo@ejemplo.com">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <label for="telefono" class="form-label fw-600">Teléfono</label>
                                        <div class="input-group-custom">
                                            <i class="fas fa-phone"></i>
                                            <input type="text" class="form-control-custom" id="telefono" name="telefono" value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>" placeholder="987 654 321">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <label for="direccion" class="form-label fw-600">Dirección</label>
                                        <div class="input-group-custom">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <input type="text" class="form-control-custom" id="direccion" name="direccion" value="<?= htmlspecialchars($usuario['direccion'] ?? '') ?>" placeholder="Av. Siempre Viva 123">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="section-divider mb-4">
                                <span class="fw-700 text-uppercase small text-muted ls-1">Seguridad</span>
                            </div>

                            <div class="row g-4 mb-5">
                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <label for="nueva_contraseña" class="form-label fw-600">Nueva Contraseña</label>
                                        <div class="input-group-custom">
                                            <i class="fas fa-lock"></i>
                                            <input type="password" class="form-control-custom" id="nueva_contraseña" name="nueva_contraseña" placeholder="Dejar vacío para no cambiar">
                                        </div>
                                        <div class="form-text mt-2 text-muted small">Mínimo 8 caracteres para mayor seguridad.</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <label for="confirm_contraseña" class="form-label fw-600">Confirmar Contraseña</label>
                                        <div class="input-group-custom">
                                            <i class="fas fa-shield-alt"></i>
                                            <input type="password" class="form-control-custom" id="confirm_contraseña" placeholder="Repite la contraseña">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-3 mt-5">
                                <a href="/clientes/controladores/cliente/PerfilClienteController.php?accion=ver" class="btn btn-light rounded-16 px-4 fw-600">Cancelar</a>
                                <button type="submit" class="btn btn-primary rounded-16 px-5 fw-700">Guardar Cambios</button>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    .rounded-24 { border-radius: 24px; }
    .rounded-16 { border-radius: 16px; }
    .fw-600 { font-weight: 600; }
    .fw-700 { font-weight: 700; }
    .fw-800 { font-weight: 800; }
    .ls-1 { letter-spacing: 1px; }

    .profile-avatar-edit {
        position: relative;
        display: inline-block;
    }

    .profile-avatar-edit img {
        width: 100px;
        height: 100px;
        border-radius: 30px;
        object-fit: cover;
        border: 4px solid #fff;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .btn-change-avatar {
        position: absolute;
        bottom: -5px;
        right: -5px;
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: var(--primary-gradient);
        color: white;
        border: 2px solid #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .btn-change-avatar:hover {
        transform: scale(1.1);
    }

    .section-divider {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .section-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #f1f5f9;
    }

    .input-group-custom {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-group-custom i {
        position: absolute;
        left: 20px;
        color: #94a3b8;
        font-size: 1rem;
        transition: color 0.3s ease;
    }

    .form-control-custom {
        width: 100%;
        padding: 16px 20px 16px 55px;
        border-radius: 18px;
        border: 2px solid #f1f5f9;
        background: #f8fafc;
        color: #1e293b;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .form-control-custom:focus {
        outline: none;
        border-color: #6366f1;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    .form-control-custom:focus + i {
        color: #6366f1;
    }

    .btn-primary {
        background: var(--primary-gradient);
        border: none;
        box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(99, 102, 241, 0.4);
    }

    .btn-light {
        background: #f1f5f9;
        border: none;
        color: #475569;
    }
</style>

<script>
document.getElementById('editProfileForm').addEventListener('submit', function(e) {
    const pass = document.getElementById('nueva_contraseña').value;
    const confirm = document.getElementById('confirm_contraseña').value;

    if (pass !== '' && pass !== confirm) {
        e.preventDefault();
        alert('Las contraseñas no coinciden. Por favor, verifica e intenta de nuevo.');
    }
});
</script>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>

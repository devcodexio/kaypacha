<?php
// vistas/cliente/perfil/ver.php
require_once __DIR__ . '/../../layout/headerr.php';
require_once __DIR__ . '/../../layout/sidebar_cliente.php';

$usuario = $usuario ?? [];
?>

<div class="content-wrapper">
    <div class="container-fluid">
        
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
                
                <div class="profile-header mb-5 text-center text-lg-start d-lg-flex align-items-center gap-4">
                    <div class="profile-avatar-large shadow-lg">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($usuario['nombre'] ?? 'U') ?>&background=6366f1&color=fff&size=200&bold=true" alt="Avatar">
                    </div>
                    <div class="profile-intro mt-4 mt-lg-0">
                        <h1 class="display-5 fw-800 text-dark mb-1"><?= htmlspecialchars($usuario['nombre'] ?? 'Usuario') ?></h1>
                        <p class="text-secondary fs-5 mb-0">Gestiona tu información personal y seguridad de la cuenta.</p>
                        <div class="mt-3">
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill">
                                <i class="fas fa-check-circle me-1"></i> Cuenta Verificada
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Información Personal -->
                    <div class="col-md-7">
                        <div class="card border-0 shadow-sm rounded-24 h-100">
                            <div class="card-body p-4 p-xl-5">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h3 class="fw-700 mb-0">Datos Personales</h3>
                                    <a href="/clientes/controladores/cliente/PerfilClienteController.php?accion=editar" class="btn btn-primary rounded-16 px-4">
                                        <i class="fas fa-edit me-2"></i> Editar
                                    </a>
                                </div>

                                <div class="info-grid">
                                    <div class="info-item mb-4">
                                        <label class="text-muted text-uppercase small fw-700 ls-1 d-block mb-1">Nombre Completo</label>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="info-icon bg-primary-subtle text-primary">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <span class="fs-5 fw-500"><?= htmlspecialchars($usuario['nombre'] ?? 'No especificado') ?></span>
                                        </div>
                                    </div>

                                    <div class="info-item mb-4">
                                        <label class="text-muted text-uppercase small fw-700 ls-1 d-block mb-1">Correo Electrónico</label>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="info-icon bg-info-subtle text-info">
                                                <i class="fas fa-envelope"></i>
                                            </div>
                                            <span class="fs-5 fw-500"><?= htmlspecialchars($usuario['correo'] ?? 'No especificado') ?></span>
                                        </div>
                                    </div>

                                    <div class="info-item mb-4">
                                        <label class="text-muted text-uppercase small fw-700 ls-1 d-block mb-1">Teléfono Móvil</label>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="info-icon bg-warning-subtle text-warning">
                                                <i class="fas fa-phone"></i>
                                            </div>
                                            <span class="fs-5 fw-500"><?= htmlspecialchars($usuario['telefono'] ?? 'No registrado') ?></span>
                                        </div>
                                    </div>

                                    <div class="info-item">
                                        <label class="text-muted text-uppercase small fw-700 ls-1 d-block mb-1">Dirección de Envío</label>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="info-icon bg-danger-subtle text-danger">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </div>
                                            <span class="fs-5 fw-500"><?= htmlspecialchars($usuario['direccion'] ?? 'No registrada') ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Seguridad y Otros -->
                    <div class="col-md-5">
                        <div class="card border-0 shadow-sm rounded-24 mb-4">
                            <div class="card-body p-4">
                                <h4 class="fw-700 mb-4">Seguridad</h4>
                                <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-20 mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="info-icon bg-white text-dark shadow-sm">
                                            <i class="fas fa-lock"></i>
                                        </div>
                                        <div>
                                            <span class="d-block fw-600">Contraseña</span>
                                            <span class="text-muted small">Actualizada recientemente</span>
                                        </div>
                                    </div>
                                    <a href="/clientes/controladores/cliente/PerfilClienteController.php?accion=editar" class="btn btn-sm btn-outline-dark rounded-pill px-3">Cambiar</a>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-24 bg-dark text-white">
                            <div class="card-body p-4 text-center">
                                <div class="mb-3">
                                    <i class="fas fa-shield-alt fa-3x text-warning"></i>
                                </div>
                                <h5 class="fw-700">Tu cuenta está protegida</h5>
                                <p class="text-white-50 small mb-0">Mantenemos tus datos seguros bajo encriptación de grado militar.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    .rounded-24 { border-radius: 24px; }
    .rounded-20 { border-radius: 20px; }
    .rounded-16 { border-radius: 16px; }
    .fw-700 { font-weight: 700; }
    .fw-800 { font-weight: 800; }
    .ls-1 { letter-spacing: 1px; }

    .profile-avatar-large {
        width: 150px;
        height: 150px;
        border-radius: 40px;
        overflow: hidden;
        border: 5px solid #fff;
    }

    .profile-avatar-large img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .info-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .bg-primary-subtle { background: rgba(99, 102, 241, 0.1); }
    .bg-info-subtle { background: rgba(14, 165, 233, 0.1); }
    .bg-warning-subtle { background: rgba(245, 158, 11, 0.1); }
    .bg-danger-subtle { background: rgba(239, 68, 68, 0.1); }
    .bg-success-subtle { background: rgba(34, 197, 94, 0.1); }

    .card {
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
    }
</style>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>

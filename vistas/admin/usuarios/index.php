<?php
// vistas/admin/usuarios/index.php
require_once __DIR__ . '/../../layout/headerr.php';
require_once __DIR__ . '/../../layout/sidebar_admin.php';

$usuarios = $usuarios ?? [];
?>

<div class="content-wrapper">
    <!-- 🔹 HEADER PRO -->
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h6 class="text-indigo fw-800 text-uppercase mb-2" style="letter-spacing: 3px; font-size: 0.75rem;">Administración</h6>
            <h1 class="fw-800 text-slate-900 display-5 mb-0">Control de Usuarios</h1>
        </div>
        <div>
            <a href="/clientes/controladores/admin/UsuariosAdminController.php?accion=crear" class="btn btn-primary shadow-pro border-0 rounded-16 px-4 py-3 fw-800" style="background: var(--slate-900);">
                <i class="fas fa-user-plus me-2"></i> Registrar Usuario
            </a>
        </div>
    </div>

    <!-- 🔹 TABLA PROFESIONAL v2 -->
    <div class="table-responsive">
        <table class="table-pro">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Colaborador / Cliente</th>
                    <th>Nivel de Acceso</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th class="text-end">Operaciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($usuarios)): ?>
                    <?php foreach($usuarios as $u): 
                        $rol = strtolower($u['nombre_rol'] ?? '');
                        $rolColor = ($rol === 'administrador') ? '#ef4444' : (($rol === 'empleado') ? '#f59e0b' : '#10b981');
                    ?>
                        <tr>
                            <td><span class="fw-800 text-slate-900">#<?= (int)$u['id'] ?></span></td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-pro">
                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($u['nombre']) ?>&background=random&bold=true" alt="">
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-800 text-slate-900 fs-6"><?= htmlspecialchars($u['nombre']) ?></span>
                                        <span class="text-muted small fw-600"><?= htmlspecialchars($u['direccion'] ?? 'Sin dirección') ?></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="status-dot" style="background: <?= $rolColor ?>;"></div>
                                    <span class="fw-800 text-uppercase" style="color: <?= $rolColor ?>; font-size: 0.7rem; letter-spacing: 1.5px;"><?= $rol ?></span>
                                </div>
                            </td>
                            <td><span class="fw-600 text-slate-600"><?= htmlspecialchars($u['correo']) ?></span></td>
                            <td><span class="text-slate-500 fw-600"><?= htmlspecialchars($u['telefono'] ?? '-') ?></span></td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="/clientes/controladores/admin/UsuariosAdminController.php?accion=editar&id=<?= $u['id'] ?>" 
                                       class="action-btn-pro btn-confirm" title="Editar Perfil">
                                        <i class="fas fa-user-edit"></i>
                                    </a>
                                    <a href="/clientes/controladores/admin/UsuariosAdminController.php?accion=eliminar&id=<?= $u['id'] ?>" 
                                       class="action-btn-pro btn-cancel" title="Revocar Acceso"
                                       onclick="return confirm('¿Seguro que desea eliminar este usuario?')">
                                        <i class="fas fa-user-slash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center py-5 text-muted fw-500">Base de datos de usuarios vacía.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .avatar-pro img { width: 50px; height: 50px; border-radius: 16px; object-fit: cover; }
    .status-dot { width: 10px; height: 10px; border-radius: 50%; }

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

    .btn-confirm { color: #6366f1; background: #eef2ff; }
    .btn-confirm:hover { background: #6366f1; color: #fff; transform: translateY(-3px) rotate(8deg); }

    .btn-cancel { color: #ef4444; background: #fef2f2; }
    .btn-cancel:hover { background: #ef4444; color: #fff; transform: translateY(-3px) rotate(-8deg); }
</style>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
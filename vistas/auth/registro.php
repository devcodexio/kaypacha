<?php
require_once __DIR__ . '/../layout/header.php';
?>
<link rel="stylesheet" href="/clientes/assets/Style-login.css?v=3">

<div class="page-container registro-page">

    <main class="main-content auth-content">

        <div class="auth-card">

            <h2 class="auth-title">Crear Cuenta</h2>
            <p class="auth-subtitle">Únete y disfruta de los mejores sabores</p>

            <?php if (!empty($_GET['error'])): ?>
                <div class="alert alert-error">
                    <?php
                    switch ($_GET['error']) {
                        case 'campos':
                            echo "Todos los campos marcados con * son obligatorios.";
                            break;
                        case 'password':
                            echo "Las contraseñas no coinciden.";
                            break;
                        case 'correo':
                            echo "El correo ya está registrado.";
                            break;
                        default:
                            echo "Ocurrió un error al registrarse.";
                            break;
                    }
                    ?>
                </div>
            <?php endif; ?>

            <form action="/clientes/controladores/auth/registro.php"
                  method="POST"
                  class="form-auth">

                <div class="input-group">
                    <label>Nombre completo</label>
                    <input type="text" name="nombre" class="input-auth" placeholder="Tu nombre" required>
                </div>

                <div class="input-group">
                    <label>Correo electrónico</label>
                    <input type="email" name="correo" class="input-auth" placeholder="correo@ejemplo.com" required>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <label>Contraseña</label>
                            <input type="password" name="password" class="input-auth" placeholder="••••••••" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <label>Confirmar</label>
                            <input type="password" name="password_confirm" class="input-auth" placeholder="••••••••" required>
                        </div>
                    </div>
                </div>

                <div class="input-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" class="input-auth" placeholder="Ej: 987654321">
                </div>

                <button type="submit" class="btn-auth">Crear Cuenta</button>
            </form>

            <p class="auth-bottom">
                ¿Ya tienes cuenta?
                <a href="/clientes/vistas/auth/login.php">Inicia sesión aquí</a>
            </p>

        </div>

    </main>

</div>

<?php
require_once __DIR__ . '/../layout/footer.php';
?>

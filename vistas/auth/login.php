<?php
require_once __DIR__ . '/../layout/header.php';
?>
<link rel="stylesheet" href="<?= BASE_URL ?>assets/style-login.css?v=3">

<div class="page-container login-page">

    <main class="main-content auth-content">

        <div class="auth-card fade-in">

            <h2 class="auth-title">Iniciar Sesión</h2>
            <p class="auth-subtitle">Bienvenido de nuevo</p>

            <?php if (!empty($_GET['error'])): ?>
                <div class="alert alert-error">
                    <?php
                    if ($_GET['error'] == 'credenciales') {
                        echo "Correo o contraseña incorrectos.";
                    } elseif ($_GET['error'] == 'campos') {
                        echo "Completa todos los campos.";
                    } else {
                        echo "Ocurrió un error al iniciar sesión.";
                    }
                    ?>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>controladores/auth/login.php"
                  method="POST"
                  class="form-auth">

                <div class="input-group">
                    <label>Correo electrónico</label>
                    <input type="email" name="correo" class="input-auth" required>
                </div>

                <div class="input-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" class="input-auth" required>
                </div>

                <button type="submit" class="btn-auth">Ingresar</button>
            </form>

            <p class="auth-bottom">
                ¿No tienes cuenta?
                <a href="<?= BASE_URL ?>vistas/auth/registro.php">Regístrate aquí</a>
            </p>

        </div>

    </main>

</div>

<?php
require_once __DIR__ . '/../layout/footer.php';
            ?>

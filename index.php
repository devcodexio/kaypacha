<?php
// index.php
session_start();
require_once __DIR__ . '/conexion.php';

// Redirección si ya está logueado
if (isset($_SESSION['usuario_id'])) {
    if ($_SESSION['rol'] == 1) header("Location: /clientes/controladores/admin/DashboardAdminController.php");
    elseif ($_SESSION['rol'] == 2) header("Location: /clientes/controladores/empleado/DashboardEmpleadoController.php");
    elseif ($_SESSION['rol'] == 3) header("Location: /clientes/controladores/cliente/DashboardClienteController.php");
    exit;
}

require_once __DIR__ . '/vistas/layout/headers.php';
?>

<style>
    /* 💎 LANDING PRO SYSTEM 💎 */
    .hero-luxury {
        height: 100vh;
        width: 100%;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: #000;
        margin-top: calc(-1 * var(--nav-height));
    }

    .hero-luxury video, .hero-luxury img {
        position: absolute;
        width: 100%; height: 100%;
        object-fit: cover;
        opacity: 0.6;
        animation: slowZoom 20s infinite alternate;
    }

    @keyframes slowZoom { from { transform: scale(1); } to { transform: scale(1.1); } }

    .hero-overlay-lux {
        position: absolute; width: 100%; height: 100%;
        background: radial-gradient(circle, rgba(0,0,0,0.2) 0%, rgba(15,23,42,0.9) 100%);
    }

    .hero-content-lux {
        position: relative;
        z-index: 10;
        text-align: center;
        max-width: 900px;
        padding: 0 20px;
    }

    .hero-tag {
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 4px;
        color: var(--gold-pro);
        font-size: 0.8rem;
        margin-bottom: 20px;
        display: block;
    }

    .hero-title-lux {
        font-size: clamp(3rem, 10vw, 6rem);
        font-weight: 900;
        letter-spacing: -2px;
        line-height: 1;
        margin-bottom: 25px;
        color: #fff;
    }

    .hero-desc-lux {
        font-size: 1.2rem;
        color: rgba(255,255,255,0.7);
        font-weight: 500;
        margin-bottom: 40px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .lux-btn-primary {
        background: var(--gold-pro);
        color: var(--slate-900);
        padding: 18px 45px;
        border-radius: 99px;
        font-weight: 900;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-size: 0.9rem;
        transition: 0.4s;
        box-shadow: 0 20px 40px rgba(212, 175, 55, 0.2);
    }

    .lux-btn-primary:hover {
        background: #fff;
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(255, 255, 255, 0.15);
        color: var(--slate-900);
    }

    /* SECCIONES */
    .section-padding { padding: 120px 0; }
    
    .section-title-lux {
        text-align: center;
        margin-bottom: 80px;
    }

    .section-title-lux span {
        color: var(--gold-pro);
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 3px;
        font-size: 0.75rem;
        display: block;
        margin-bottom: 10px;
    }

    .section-title-lux h2 {
        font-size: 3rem;
        font-weight: 900;
        color: #fff;
        letter-spacing: -1px;
    }

    /* CARTAS GRID */
    .carta-grid-pro {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 30px;
    }

    .plato-card-lux {
        background: rgba(255,255,255,0.03);
        border-radius: 30px;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,0.05);
        transition: 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .plato-card-lux:hover {
        transform: translateY(-15px);
        background: rgba(255,255,255,0.06);
        border-color: rgba(212, 175, 55, 0.3);
        box-shadow: 0 30px 60px rgba(0,0,0,0.4);
    }

    .plato-img-lux {
        width: 100%;
        height: 250px;
        object-fit: cover;
        transition: 0.8s;
    }

    .plato-card-lux:hover .plato-img-lux { transform: scale(1.1); }

    .plato-info-lux { padding: 30px; }

    .plato-cat-lux {
        font-size: 0.65rem;
        font-weight: 800;
        color: var(--gold-pro);
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 10px;
        display: block;
    }

    .plato-name-lux { font-size: 1.5rem; font-weight: 800; margin-bottom: 15px; }

    .plato-price-lux {
        font-size: 1.25rem;
        font-weight: 900;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .plato-price-lux::before { content: ''; width: 30px; height: 2px; background: var(--gold-pro); }

    /* TESTIMONIOS PRO */
    .test-card-lux {
        background: #fff;
        border-radius: 30px;
        padding: 40px;
        color: var(--slate-900);
        position: relative;
        transition: 0.4s;
    }

    .test-card-lux:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.2); }

    .test-quote {
        position: absolute; top: 30px; right: 30px;
        font-size: 3rem; color: #f1f5f9; z-index: 0;
    }

    .test-content { position: relative; z-index: 1; }

    .test-stars { color: #f59e0b; margin-bottom: 15px; font-size: 0.8rem; }

    .test-text { font-style: italic; color: #475569; line-height: 1.6; margin-bottom: 25px; font-weight: 500; }

    .test-author { font-weight: 900; font-size: 1.1rem; text-transform: uppercase; color: var(--slate-900); }

    /* NOSOTROS */
    .nosotros-banner-lux {
        background: linear-gradient(rgba(15,23,42,0.8), rgba(15,23,42,0.8)), url('img/templo.jpg');
        background-size: cover;
        background-attachment: fixed;
        border-radius: 60px;
        padding: 100px 60px;
        text-align: center;
    }
</style>

<!-- 🎭 HERO LUXURY 🎭 -->
<section class="hero-luxury">
    <img src="/clientes/img/fondo.jpeg" alt="Kay-Pacha Background">
    <div class="hero-overlay-lux"></div>
    <div class="hero-content-lux">
        <span class="hero-tag" data-aos="fade-down">Donde el alma se alimenta</span>
        <h1 class="hero-title-lux" data-aos="zoom-out">KAY-PACHA</h1>
        <p class="hero-desc-lux" data-aos="fade-up" data-aos-delay="200">
            Una travesía culinaria inspirada en las tradiciones más profundas de nuestra tierra, reinventada para el paladar moderno.
        </p>
        <div data-aos="fade-up" data-aos-delay="400">
            <a href="#carta" class="lux-btn-primary">Explorar Nuestra Carta</a>
        </div>
    </div>
</section>

<!-- 🍽 NUESTRA CARTA 🍽 -->
<section class="section-padding" id="carta">
    <div class="container">
        <div class="section-title-lux">
            <span>Experiencia Gastronómica</span>
            <h2>Nuestra Carta</h2>
        </div>

        <div class="carta-grid-pro">
            <?php
            $platos = $conexion->query("
                SELECT p.*, c.nombre AS categoria
                FROM platos p
                INNER JOIN categorias_platos c ON c.id = p.categoria_id
                WHERE p.disponible = 1
                ORDER BY c.nombre, p.nombre
            ");
            while ($p = $platos->fetch_assoc()):
            ?>
                <div class="plato-card-lux" data-aos="fade-up">
                    <?php if (!empty($p['imagen'])): ?>
                        <img src="uploads/<?= htmlspecialchars($p['imagen']) ?>" class="plato-img-lux">
                    <?php else: ?>
                        <div class="plato-img-lux d-flex align-items-center justify-content-center bg-slate-800">
                            <i class="fas fa-utensils text-slate-700 display-4"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="plato-info-lux">
                        <span class="plato-cat-lux"><?= htmlspecialchars($p['categoria']) ?></span>
                        <h3 class="plato-name-lux"><?= htmlspecialchars($p['nombre']) ?></h3>
                        <p class="text-muted small fw-500 mb-4"><?= htmlspecialchars($p['descripcion']) ?></p>
                        <div class="plato-price-lux">
                            S/ <?= number_format($p['precio'], 2) ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- ⭐ OPINIONES ⭐ -->
<section class="section-padding bg-slate-800" id="testimonios" style="background: rgba(255,255,255,0.02);">
    <div class="container">
        <div class="section-title-lux">
            <span>Lo que dicen de nosotros</span>
            <h2>Opiniones</h2>
        </div>

        <div class="row g-4">
            <?php
            $test = $conexion->query("
                SELECT t.*, u.nombre AS cliente
                FROM testimonios t
                INNER JOIN usuarios u ON u.id = t.usuario_id
                WHERE t.estado = 'activo'
                ORDER BY t.id DESC
                LIMIT 3
            ");
            while ($t = $test->fetch_assoc()):
            ?>
                <div class="col-lg-4">
                    <div class="test-card-lux" data-aos="flip-left">
                        <i class="fas fa-quote-right test-quote"></i>
                        <div class="test-content">
                            <div class="test-stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="<?= $i <= $t['calificacion'] ? 'fas' : 'far' ?> fa-star"></i>
                                <?php endfor; ?>
                            </div>
                            <p class="test-text">"<?= htmlspecialchars($t['mensaje']) ?>"</p>
                            <h5 class="test-author"><?= htmlspecialchars($t['cliente']) ?></h5>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- 🏛 NOSOTROS 🏛 -->
<section class="section-padding" id="nosotros">
    <div class="container">
        <div class="nosotros-banner-lux" data-aos="zoom-in">
            <span class="hero-tag">Nuestra Identidad</span>
            <h2 class="display-3 fw-900 mb-5">El Legado de Kay-Pacha</h2>
            
            <div class="row text-start g-5">
                <?php
                $nos = $conexion->query("SELECT * FROM nosotros ORDER BY tipo ASC");
                while ($n = $nos->fetch_assoc()):
                ?>
                    <div class="col-md-6">
                        <h4 class="text-gold fw-800 text-uppercase mb-3" style="letter-spacing: 2px;">
                            <?= htmlspecialchars($n['titulo']) ?>
                        </h4>
                        <p class="text-white-50 fs-5 fw-500">
                            <?= nl2br(htmlspecialchars($n['descripcion'])) ?>
                        </p>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</section>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script>
    AOS.init({ duration: 1000, once: true });
</script>

<?php require_once __DIR__ . '/vistas/layout/footer.php'; ?>

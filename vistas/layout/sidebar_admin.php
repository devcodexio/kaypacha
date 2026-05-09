<?php
// vistas/layout/sidebar_admin.php
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) return;
$base = "/clientes/";
$current_page = $_SERVER['REQUEST_URI'];
?>

<style>
    /* ===== SIDEBAR ADMIN PREMIUM ===== */
    #sidebarAdmin {
        position: fixed;
        top: var(--navbar-height);
        left: 0;
        width: var(--sidebar-width);
        height: calc(100vh - var(--navbar-height));
        background: var(--dark-bg);
        color: white;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1500;
        overflow-y: auto;
        overflow-x: hidden;
        border-right: 1px solid rgba(255, 255, 255, 0.05);
        display: flex;
        flex-direction: column;
        padding: 30px 0;
    }

    #sidebarAdmin.mini {
        width: var(--sidebar-mini-width);
    }

    .sidebar-section-title {
        padding: 0 30px;
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 2.5px;
        color: rgba(255, 255, 255, 0.3);
        margin-bottom: 20px;
        font-weight: 800;
        transition: opacity 0.3s ease;
        white-space: nowrap;
    }

    #sidebarAdmin.mini .sidebar-section-title {
        opacity: 0;
    }

    .sidebar-nav {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .sidebar-nav-item {
        padding: 0 15px;
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        width: 100%;
        padding: 14px 18px;
        color: rgba(255, 255, 255, 0.6) !important;
        text-decoration: none;
        border-radius: 20px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        white-space: nowrap;
        position: relative;
    }

    .sidebar-link i {
        font-size: 1.2rem;
        min-width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 12px;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .sidebar-link span {
        font-weight: 600;
        font-size: 0.9rem;
        transition: opacity 0.3s ease;
        margin-left: 8px;
    }

    #sidebarAdmin.mini .sidebar-link {
        padding: 14px 0;
        justify-content: center;
        border-radius: 16px;
    }

    #sidebarAdmin.mini .sidebar-link span {
        display: none;
    }

    .sidebar-link:hover {
        background: rgba(255, 255, 255, 0.05);
        color: #fff !important;
        transform: translateX(5px);
    }

    .sidebar-link:hover i {
        background: rgba(255, 255, 255, 0.1);
        color: var(--primary-pro);
    }

    .sidebar-link.active {
        background: var(--primary-gradient);
        color: #fff !important;
        box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
        font-weight: 800;
    }

    .sidebar-link.active i {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        transform: scale(1.05);
    }

    /* Tooltip para modo mini */
    #sidebarAdmin.mini .sidebar-link::after {
        content: attr(data-title);
        position: absolute;
        left: 100%;
        margin-left: 20px;
        background: var(--slate-900);
        color: white;
        padding: 10px 16px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 700;
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s ease;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        z-index: 3000;
    }

    #sidebarAdmin.mini .sidebar-link:hover::after {
        opacity: 1;
        visibility: visible;
        margin-left: 15px;
    }

    @media (max-width: 992px) {
        #sidebarAdmin {
            left: -100%;
        }
        #sidebarAdmin.active-mobile {
            left: 0;
            width: var(--sidebar-width);
        }
    }
</style>

<aside id="sidebarAdmin" class="<?= isset($_COOKIE['sidebar_mini']) && $_COOKIE['sidebar_mini'] === '1' ? 'mini' : '' ?>">

    <div class="sidebar-section-title">Administración</div>
    
    <ul class="sidebar-nav">
        <li class="sidebar-nav-item">
            <a href="<?= BASE_URL ?>controladores/admin/DashboardAdminController.php" 
               class="sidebar-link <?= strpos($current_page, 'DashboardAdminController') !== false ? 'active' : '' ?>"
               data-title="Panel Principal">
                <i class="fas fa-th-large"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="sidebar-nav-item">
            <a href="<?= BASE_URL ?>controladores/admin/ReservasAdminController.php?accion=index" 
               class="sidebar-link <?= strpos($current_page, 'ReservasAdminController') !== false ? 'active' : '' ?>"
               data-title="Gestionar Reservas">
                <i class="fas fa-calendar-day"></i>
                <span>Reservas</span>
            </a>
        </li>

        <li class="sidebar-nav-item">
            <a href="<?= BASE_URL ?>controladores/admin/UsuariosAdminController.php?accion=index" 
               class="sidebar-link <?= strpos($current_page, 'UsuariosAdminController') !== false ? 'active' : '' ?>"
               data-title="Control de Usuarios">
                <i class="fas fa-user-gear"></i>
                <span>Usuarios</span>
            </a>
        </li>

        <li class="sidebar-nav-item">
            <a href="<?= BASE_URL ?>controladores/admin/PagosStripeAdminController.php?accion=index" 
               class="sidebar-link <?= strpos($current_page, 'PagosStripeAdminController') !== false ? 'active' : '' ?>"
               data-title="Verificar Pagos Digitales">
                <i class="fa-brands fa-stripe-s"></i>
                <span>Pagos Stripe</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-section-title mt-4">Restaurante</div>

    <ul class="sidebar-nav">
        <li class="sidebar-nav-item">
            <a href="<?= BASE_URL ?>controladores/admin/MesasAdminController.php?accion=index" 
               class="sidebar-link <?= strpos($current_page, 'MesasAdminController') !== false ? 'active' : '' ?>"
               data-title="Configurar Mesas">
                <i class="fas fa-map-location-dot"></i>
                <span>Layout Mesas</span>
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a href="<?= BASE_URL ?>controladores/admin/PlatosAdminController.php?accion=index" 
               class="sidebar-link <?= strpos($current_page, 'PlatosAdminController') !== false ? 'active' : '' ?>"
               data-title="Menú Digital">
                <i class="fas fa-bowl-food"></i>
                <span>Menú / Platos</span>
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a href="<?= BASE_URL ?>controladores/admin/CategoriasAdminController.php?accion=index" 
               class="sidebar-link <?= strpos($current_page, 'CategoriasAdminController') !== false ? 'active' : '' ?>"
               data-title="Categorías">
                <i class="fas fa-layer-group"></i>
                <span>Categorías</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-section-title mt-4">Contenido Web</div>

    <ul class="sidebar-nav">
        <li class="sidebar-nav-item">
            <a href="<?= BASE_URL ?>controladores/admin/TestimoniosAdminController.php?accion=index" 
               class="sidebar-link <?= strpos($current_page, 'TestimoniosAdminController') !== false ? 'active' : '' ?>"
               data-title="Reseñas">
                <i class="fas fa-quote-left"></i>
                <span>Testimonios</span>
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a href="<?= $base ?>controladores/admin/NosotrosAdminController.php?accion=index" 
               class="sidebar-link <?= strpos($current_page, 'NosotrosAdminController') !== false ? 'active' : '' ?>"
               data-title="Información">
                <i class="fas fa-id-card"></i>
                <span>Nosotros</span>
            </a>
        </li>
    </ul>

</aside>

<script>
// Manejo de responsive en móviles
document.addEventListener("DOMContentLoaded", () => {
    const toggleBtn = document.getElementById("mainSidebarToggle");
    const sidebar = document.getElementById("sidebarAdmin");
    
    if (window.innerWidth <= 992 && toggleBtn && sidebar) {
        toggleBtn.addEventListener("click", () => {
            sidebar.classList.toggle("active-mobile");
        });
    }
});
</script>

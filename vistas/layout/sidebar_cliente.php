<?php
// vistas/layout/sidebar_cliente.php
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 3) return;
$base = "/clientes/";
$current_page = $_SERVER['REQUEST_URI'];
?>

<style>
    /* ===== SIDEBAR MODERNO ===== */
    #sidebarCliente {
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
        padding: 20px 0;
    }

    #sidebarCliente.mini {
        width: var(--sidebar-mini-width);
        padding: 20px 0;
    }

    .sidebar-section-title {
        padding: 0 25px;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: rgba(255, 255, 255, 0.4);
        margin-bottom: 15px;
        transition: opacity 0.3s ease;
        white-space: nowrap;
    }

    #sidebarCliente.mini .sidebar-section-title {
        opacity: 0;
    }

    .sidebar-nav {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .sidebar-nav-item {
        padding: 0 12px;
        display: flex;
        justify-content: center;
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        width: 100%;
        padding: 12px;
        color: rgba(255, 255, 255, 0.7) !important;
        text-decoration: none;
        border-radius: 16px;
        transition: all 0.3s ease;
        white-space: nowrap;
        position: relative;
    }

    .sidebar-link i {
        font-size: 1.25rem;
        min-width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .sidebar-link span {
        font-weight: 500;
        font-size: 0.95rem;
        transition: opacity 0.3s ease;
    }

    #sidebarCliente.mini .sidebar-link {
        padding: 12px 0;
        justify-content: center;
        width: 54px;
        height: 54px;
        border-radius: 14px;
    }

    #sidebarCliente.mini .sidebar-link span {
        display: none;
    }

    .sidebar-link:hover {
        background: rgba(255, 255, 255, 0.05);
        color: #fff !important;
    }

    .sidebar-link.active {
        background: var(--primary-gradient);
        color: #fff !important;
        box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2);
    }

    .sidebar-link.active i {
        color: #fff;
    }

    /* Tooltip para modo mini */
    #sidebarCliente.mini .sidebar-link::after {
        content: attr(data-title);
        position: absolute;
        left: 100%;
        margin-left: 20px;
        background: #1e293b;
        color: white;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 0.85rem;
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s ease;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        z-index: 3000;
    }

    #sidebarCliente.mini .sidebar-link:hover::after {
        opacity: 1;
        visibility: visible;
        margin-left: 10px;
    }

    @media (max-width: 992px) {
        #sidebarCliente {
            left: -100%;
        }
        #sidebarCliente.active-mobile {
            left: 0;
            width: var(--sidebar-width);
        }
    }
</style>

<aside id="sidebarCliente" class="<?= isset($_COOKIE['sidebar_mini']) && $_COOKIE['sidebar_mini'] === '1' ? 'mini' : '' ?>">

    <div class="sidebar-section-title">Navegación</div>
    
    <ul class="sidebar-nav">
        <li class="sidebar-nav-item">
            <a href="<?= $base ?>controladores/cliente/DashboardClienteController.php" 
               class="sidebar-link <?= strpos($current_page, 'DashboardClienteController') !== false ? 'active' : '' ?>"
               data-title="Panel Principal">
                <i class="fas fa-th-large"></i>
                <span>Mi Panel</span>
            </a>
        </li>

        <li class="sidebar-nav-item">
            <a href="<?= $base ?>controladores/cliente/ReservasClienteController.php?accion=index" 
               class="sidebar-link <?= strpos($current_page, 'ReservasClienteController') !== false ? 'active' : '' ?>"
               data-title="Mis Reservas">
                <i class="fas fa-calendar-check"></i>
                <span>Mis Reservas</span>
            </a>
        </li>

        <li class="sidebar-nav-item">
            <a href="<?= $base ?>controladores/cliente/PagosYapeClienteController.php?accion=index" 
            <a href="<?= BASE_URL ?>controladores/cliente/PagosYapeClienteController.php?accion=index" 
               class="sidebar-link <?= strpos($current_page, 'PagosYapeClienteController') !== false ? 'active' : '' ?>"
               data-title="Mis Pagos">
                <i class="fas fa-wallet"></i>
                <span>Mis Pagos</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-section-title mt-4">Cuenta</div>

    <ul class="sidebar-nav">
        <li class="sidebar-nav-item">
            <a href="<?= BASE_URL ?>controladores/cliente/PerfilClienteController.php?accion=ver" 
               class="sidebar-link <?= strpos($current_page, 'PerfilClienteController') !== false ? 'active' : '' ?>"
               data-title="Mi Perfil">
                <i class="fas fa-user-circle"></i>
                <span>Mi Perfil</span>
            </a>
        </li>
        
        <li class="sidebar-nav-item">
            <a href="<?= $base ?>controladores/auth/logout.php" 
               class="sidebar-link text-danger"
               data-title="Cerrar Sesión">
                <i class="fas fa-sign-out-alt"></i>
                <span>Salir</span>
            </a>
        </li>
    </ul>

</aside>

<script>
// Manejo de responsive en móviles
document.addEventListener("DOMContentLoaded", () => {
    const toggleBtn = document.getElementById("mainSidebarToggle");
    const sidebar = document.getElementById("sidebarCliente");
    
    if (window.innerWidth <= 992) {
        toggleBtn.addEventListener("click", () => {
            sidebar.classList.toggle("active-mobile");
        });
    }
});
</script>

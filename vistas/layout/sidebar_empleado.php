<?php
// vistas/layout/sidebar_empleado.php
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) return;

$base = "/clientes/";
$current_page = basename($_SERVER['PHP_SELF'], ".php");
$accion = $_GET['accion'] ?? '';
?>

<style>
    /* 💎 PRO SIDEBAR SYSTEM 💎 */
    #sidebarEmpleado {
        position: fixed;
        top: var(--navbar-height);
        left: 0;
        width: var(--sidebar-width);
        height: calc(100vh - var(--navbar-height));
        background: var(--dark-bg);
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1040;
        border-right: 1px solid rgba(255, 255, 255, 0.05);
        overflow-y: auto;
        overflow-x: hidden;
    }

    #sidebarEmpleado.mini { width: var(--sidebar-mini-width); }

    .sidebar-content { padding: 30px 15px; }

    .sidebar-header-pro {
        padding: 0 15px 25px;
        margin-bottom: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .sidebar-label-pro {
        font-size: 0.65rem;
        font-weight: 800;
        color: rgba(255, 255, 255, 0.3);
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 15px;
        display: block;
        padding-left: 15px;
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        padding: 14px 18px;
        color: rgba(255, 255, 255, 0.6) !important;
        text-decoration: none;
        border-radius: 16px;
        margin-bottom: 8px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
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
        margin-right: 15px;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .sidebar-link span { font-weight: 600; font-size: 0.95rem; white-space: nowrap; transition: 0.3s; }

    .sidebar-link:hover {
        background: rgba(255, 255, 255, 0.05);
        color: #fff !important;
        transform: translateX(5px);
    }

    .sidebar-link:hover i {
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
        box-shadow: 0 8px 15px rgba(99, 102, 241, 0.3);
    }

    .sidebar-link.active {
        background: rgba(99, 102, 241, 0.1);
        color: #fff !important;
    }

    .sidebar-link.active i {
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
    }

    /* MINI STATE */
    #sidebarEmpleado.mini .sidebar-link span { opacity: 0; visibility: hidden; }
    #sidebarEmpleado.mini .sidebar-label-pro { opacity: 0; }
    #sidebarEmpleado.mini .sidebar-link { padding: 14px 12px; justify-content: center; }
    #sidebarEmpleado.mini .sidebar-link i { margin-right: 0; }
</style>

<aside id="sidebarEmpleado">
    <div class="sidebar-content">
        
        <div class="sidebar-header-pro">
            <div class="d-flex align-items-center gap-3 text-white">
                <div class="bg-indigo-pro p-2 rounded-12">
                    <i class="fas fa-id-badge"></i>
                </div>
                <div class="mini-hide">
                    <span class="d-block fw-800" style="font-size: 0.85rem;">Módulo Empleado</span>
                    <span class="d-block text-indigo fw-700" style="font-size: 0.65rem; text-transform: uppercase;">Operaciones</span>
                </div>
            </div>
        </div>

        <span class="sidebar-label-pro">Principal</span>
        
        <a href="<?= $base ?>controladores/empleado/DashboardEmpleadoController.php" class="sidebar-link">
            <i class="fas fa-house-chimney"></i>
            <span>Panel de Control</span>
        </a>

        <a href="<?= $base ?>controladores/empleado/ReservasEmpleadoController.php?accion=index" class="sidebar-link">
            <i class="fas fa-calendar-check"></i>
            <span>Gestión Reservas</span>
        </a>

        <span class="sidebar-label-pro mt-4">Piso / Salón</span>

        <a href="<?= $base ?>controladores/empleado/MesasEmpleadoController.php?accion=index" class="sidebar-link">
            <i class="fas fa-chair"></i>
            <span>Estado de Mesas</span>
        </a>

        <a href="<?= $base ?>controladores/empleado/MesasEmpleadoController.php?accion=croquis" class="sidebar-link">
            <i class="fas fa-map-location-dot"></i>
            <span>Mapa del Salón</span>
        </a>

        <span class="sidebar-label-pro mt-4">Sesión</span>
        
        <a href="<?= $base ?>controladores/auth/logout.php" class="sidebar-link text-danger-pro">
            <i class="fas fa-power-off"></i>
            <span>Cerrar Sesión</span>
        </a>

    </div>
</aside>

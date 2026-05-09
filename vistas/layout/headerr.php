<?php
// vistas/layout/headerr.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Incluir conexión para tener BASE_URL si no está definida
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../conexion.php';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kay-Pacha | Sistema de Reservas</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* 💎 PRO ADMIN DESIGN SYSTEM 💎 */
        :root {
            --sidebar-width: 300px;
            --sidebar-mini-width: 90px;
            --navbar-height: 80px;
            --primary-pro: #6366f1;
            --secondary-pro: #4f46e5;
            --success-pro: #10b981;
            --danger-pro: #ef4444;
            --warning-pro: #f59e0b;
            --slate-50: #f8fafc;
            --slate-100: #f1f5f9;
            --slate-200: #e2e8f0;
            --slate-800: #1e293b;
            --slate-900: #0f172a;
            --radius-pro: 32px;
            --shadow-pro: 0 20px 50px rgba(0, 0, 0, 0.04);
            --dark-bg: #0f172a;
            --primary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: #fbfcfe;
            color: var(--slate-800);
            margin: 0;
            padding-top: var(--navbar-height);
            transition: all 0.3s ease;
            -webkit-font-smoothing: antialiased;
        }

        .fw-800 { font-weight: 800; }
        .fw-700 { font-weight: 700; }
        .fw-600 { font-weight: 600; }
        .fw-300 { font-weight: 300; }

        /* NAVBAR MODERNA */
        .custom-navbar {
            height: var(--navbar-height);
            background: rgba(15, 23, 42, 0.95) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 0 24px;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 2000;
            display: flex;
            align-items: center;
        }

        .navbar-container {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .btn-toggle-sidebar {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-toggle-sidebar:hover {
            background: var(--primary-gradient);
            border-color: transparent;
            transform: scale(1.05);
        }

        .navbar-brand-img {
            height: 42px;
            transition: transform 0.3s ease;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-pill {
            background: rgba(255, 255, 255, 0.05);
            padding: 10px 20px;
            border-radius: 99px;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            text-decoration: none;
            color: white !important;
        }

        .user-pill:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .user-pill img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #6366f1;
        }

        .btn-logout {
            background: rgba(239, 68, 68, 0.1);
            color: #f87171 !important;
            padding: 12px 24px;
            border-radius: 16px;
            font-weight: 800;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid rgba(248, 113, 113, 0.2);
            display: flex;
            align-items: center;
            gap: 10px;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
        }

        .btn-logout:hover {
            background: #ef4444;
            color: white !important;
            box-shadow: 0 10px 20px rgba(239, 68, 68, 0.3);
        }

        /* 📐 LAYOUT */
        .content-wrapper {
            margin-left: var(--sidebar-width);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 60px 50px;
            min-height: calc(100vh - var(--navbar-height));
        }

        body.sidebar-mini .content-wrapper {
            margin-left: var(--sidebar-mini-width);
        }

        /* CARD PRO */
        .card-pro {
            background: #fff;
            border-radius: var(--radius-pro);
            border: 1px solid var(--slate-100);
            box-shadow: var(--shadow-pro);
            padding: 45px;
            transition: all 0.3s ease;
        }

        /* TABLE PRO */
        .table-pro {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 16px;
        }

        .table-pro thead th {
            background: transparent;
            border: none;
            color: #94a3b8;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 800;
            padding: 0 25px 10px;
        }

        .table-pro tbody tr {
            background: #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.02);
            border-radius: 24px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .table-pro tbody tr:hover {
            transform: scale(1.01) translateY(-2px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.06);
            z-index: 10;
        }

        .table-pro tbody td {
            padding: 24px 25px;
            border-top: 1px solid var(--slate-50);
            border-bottom: 1px solid var(--slate-50);
            vertical-align: middle;
        }

        .table-pro tbody td:first-child {
            border-left: 1px solid var(--slate-50);
            border-radius: 24px 0 0 24px;
        }

        .table-pro tbody td:last-child {
            border-right: 1px solid var(--slate-50);
            border-radius: 0 24px 24px 0;
        }

        @media (max-width: 992px) {
            .content-wrapper {
                margin-left: 0 !important;
                padding: 40px 20px;
            }
        }
    </style>
</head>
<body class="<?= isset($_COOKIE['sidebar_mini']) && $_COOKIE['sidebar_mini'] === '1' ? 'sidebar-mini' : '' ?>">

<nav class="custom-navbar">
    <div class="navbar-container">
        <div class="navbar-left">
            <button class="btn-toggle-sidebar" id="mainSidebarToggle">
                <i class="fas fa-bars-staggered"></i>
            </button>
            <div class="brand-container-pro ms-3">
                <a href="<?= BASE_URL ?>index.php" class="text-decoration-none d-flex align-items-center gap-3">
                    <img src="<?= BASE_URL ?>img/logo.png" class="navbar-brand-img" alt="Kay-Pacha">
                    <div class="brand-text-pro d-none d-lg-block">
                        <span class="d-block fw-800 text-white" style="letter-spacing: 1px; font-size: 1.1rem; line-height: 1;">KAY-PACHA</span>
                        <span class="d-block fw-600 text-indigo text-uppercase" style="letter-spacing: 3px; font-size: 0.6rem; opacity: 0.8;">Admin Suite</span>
                    </div>
                </a>
            </div>
        </div>

        <div class="navbar-right">
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <div class="d-flex align-items-center gap-4">
                    <!-- Notifications (Visual Only for now) -->
                    <div class="nav-icon-btn d-none d-sm-flex">
                        <i class="far fa-bell"></i>
                        <span class="pulse-dot"></span>
                    </div>

                    <div class="divider-v d-none d-md-block"></div>

                    <a href="#" class="user-pill-pro">
                        <div class="user-meta text-end d-none d-md-block">
                            <span class="user-name-pro"><?= htmlspecialchars($_SESSION['nombre']) ?></span>
                            <span class="user-role-pro">Administrador Master</span>
                        </div>
                        <div class="avatar-wrapper-pro">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['nombre']) ?>&background=6366f1&color=fff&bold=true" alt="Avatar">
                            <div class="online-indicator"></div>
                        </div>
                    </a>

                    <a href="<?= BASE_URL ?>controladores/auth/logout.php" class="btn-logout-pro" title="Cerrar Sesión">
                        <i class="fas fa-power-off"></i>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>

<style>
    .brand-container-pro { border-left: 1px solid rgba(255,255,255,0.1); padding-left: 20px; }
    .navbar-brand-img { height: 38px; width: auto; object-fit: contain; }
    
    .nav-icon-btn { 
        position: relative; width: 44px; height: 44px; 
        display: flex; align-items: center; justify-content: center;
        color: rgba(255,255,255,0.6); font-size: 1.2rem; cursor: pointer;
        transition: all 0.3s ease; border-radius: 12px;
    }
    .nav-icon-btn:hover { color: #fff; background: rgba(255,255,255,0.05); }
    .pulse-dot { position: absolute; top: 12px; right: 12px; width: 8px; height: 8px; background: #ef4444; border-radius: 50%; border: 2px solid var(--dark-bg); }

    .divider-v { width: 1px; height: 30px; background: rgba(255,255,255,0.1); }

    .user-pill-pro { display: flex; align-items: center; gap: 15px; text-decoration: none; transition: all 0.3s ease; }
    .user-name-pro { display: block; color: #fff; font-weight: 800; font-size: 0.9rem; line-height: 1.2; }
    .user-role-pro { display: block; color: #818cf8; font-weight: 700; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 1px; }
    
    .avatar-wrapper-pro { position: relative; }
    .avatar-wrapper-pro img { width: 44px; height: 44px; border-radius: 14px; border: 2px solid rgba(99, 102, 241, 0.3); }
    .online-indicator { position: absolute; bottom: -2px; right: -2px; width: 12px; height: 12px; background: #10b981; border: 2px solid var(--dark-bg); border-radius: 50%; }

    .btn-logout-pro {
        width: 44px; height: 44px; border-radius: 14px;
        background: rgba(239, 68, 68, 0.1); color: #f87171;
        display: flex; align-items: center; justify-content: center;
        text-decoration: none; transition: all 0.3s ease;
        border: 1px solid rgba(239, 68, 68, 0.1);
    }
    .btn-logout-pro:hover { background: #ef4444; color: #fff; transform: rotate(90deg); box-shadow: 0 10px 20px rgba(239, 68, 68, 0.2); }
</style>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const toggleBtn = document.getElementById("mainSidebarToggle");
    const body = document.body;
    const sidebar = document.getElementById("sidebarCliente") || document.getElementById("sidebarAdmin");

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener("click", () => {
            body.classList.toggle("sidebar-mini");
            sidebar.classList.toggle("mini");
            
            // Guardar estado en cookie
            const isMini = body.classList.contains("sidebar-mini");
            document.cookie = `sidebar_mini=${isMini ? '1' : '0'}; path=/; max-age=31536000`;
        });
    }
});
</script>

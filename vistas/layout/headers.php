<?php
// vistas/layout/headers.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
// $base ya no es necesario, usamos la constante BASE_URL definida en conexion.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAY-PACHA | Experiencia Gastronómica</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --gold-pro: #d4af37;
            --gold-hover: #b8860b;
            --slate-900: #0f172a;
            --glass-bg: rgba(15, 23, 42, 0.8);
            --nav-height: 80px;
        }

        body { font-family: 'Outfit', sans-serif; background-color: var(--slate-900); color: #fff; padding-top: var(--nav-height); }

        /* 💎 PRO NAV SYSTEM 💎 */
        .navbar-public {
            height: var(--nav-height);
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255,255,255,0.08);
            transition: all 0.4s ease;
            position: fixed;
            top: 0; left: 0; width: 100%;
            z-index: 2000;
        }

        .navbar-brand img { height: 50px; transition: 0.3s; }
        .navbar-brand:hover img { transform: scale(1.05); }

        .nav-link-pro {
            color: rgba(255,255,255,0.7) !important;
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 10px 20px !important;
            transition: 0.3s;
            position: relative;
        }

        .nav-link-pro::after {
            content: ''; position: absolute; bottom: 0; left: 50%;
            width: 0; height: 2px; background: var(--gold-pro);
            transition: 0.3s; transform: translateX(-50%);
        }

        .nav-link-pro:hover { color: #fff !important; }
        .nav-link-pro:hover::after { width: 30px; }

        .btn-reserve-pro {
            background: var(--gold-pro);
            color: var(--slate-900) !important;
            font-weight: 800;
            border-radius: 99px;
            padding: 12px 28px !important;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.85rem;
            transition: all 0.3s;
            box-shadow: 0 10px 20px rgba(212, 175, 55, 0.2);
            border: none;
        }

        .btn-reserve-pro:hover {
            background: #fff;
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(255, 255, 255, 0.15);
        }

        .user-pill-public {
            background: rgba(255,255,255,0.05);
            padding: 6px 16px;
            border-radius: 99px;
            border: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff;
            text-decoration: none;
            transition: 0.3s;
        }

        .user-pill-public:hover { background: rgba(255,255,255,0.1); border-color: var(--gold-pro); }

        .avatar-mini { width: 28px; height: 28px; background: var(--gold-pro); border-radius: 50%; color: var(--slate-900); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.7rem; }

        /* Mobile Menu */
        .navbar-toggler { border: none; padding: 0; }
        .navbar-toggler:focus { box-shadow: none; }
        .toggler-icon { width: 24px; height: 2px; background: #fff; display: block; position: relative; }
        .toggler-icon::before, .toggler-icon::after { content: ''; position: absolute; width: 24px; height: 2px; background: #fff; left: 0; transition: 0.3s; }
        .toggler-icon::before { top: -8px; }
        .toggler-icon::after { bottom: -8px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-public">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>index.php">
            <img src="<?= BASE_URL ?>img/logo.png" alt="Kay-Pacha">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link nav-link-pro" href="<?= BASE_URL ?>index.php">Inicio</a></li>
                <li class="nav-item"><a class="nav-link nav-link-pro" href="<?= BASE_URL ?>index.php#carta">Nuestra Carta</a></li>
                <li class="nav-item"><a class="nav-link nav-link-pro" href="<?= BASE_URL ?>index.php#nosotros">Nosotros</a></li>
                <li class="nav-item"><a class="nav-link nav-link-pro" href="<?= BASE_URL ?>index.php#testimonios">Opiniones</a></li>
            </ul>

            <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                <?php if (!isset($_SESSION['usuario_id'])): ?>
                    <a href="<?= BASE_URL ?>vistas/auth/login.php" class="nav-link-pro" style="font-size: 0.8rem;">Login</a>
                    <a href="<?= BASE_URL ?>vistas/auth/registro.php" class="btn btn-reserve-pro">Reservar Mesa</a>
                <?php else: ?>
                    <?php 
                        $panelUrl = "";
                        if($_SESSION['rol'] == 1) $panelUrl = "controladores/admin/DashboardAdminController.php";
                        elseif($_SESSION['rol'] == 2) $panelUrl = "controladores/empleado/DashboardEmpleadoController.php";
                        else $panelUrl = "controladores/cliente/DashboardClienteController.php";
                    ?>
                    <a href="<?= BASE_URL . $panelUrl ?>" class="user-pill-public">
                        <div class="avatar-mini"><?= strtoupper(substr($_SESSION['nombre'], 0, 1)) ?></div>
                        <span class="small fw-700 mini-hide"><?= htmlspecialchars($_SESSION['nombre']) ?></span>
                        <i class="fas fa-chevron-right ms-1 small opacity-50"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

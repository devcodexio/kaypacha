<?php
// vistas/empleado/panel.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) {
    header("Location: /clientes/vistas/auth/login.php");
    exit;
}

require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/../layout/headerr.php';
require_once __DIR__ . '/../layout/sidebar_empleado.php';

$totales = [
    'reservas_pendientes' => 0,
    'reservas_confirmadas' => 0,
    'pagos_stripe' => 0,
    'mesas_libres' => 0
];

// RESERVAS PENDIENTES
$res = $conexion->query("SELECT COUNT(*) FROM reservas WHERE estado='pendiente'")->fetch_row();
$totales['reservas_pendientes'] = $res[0] ?? 0;

// RESERVAS CONFIRMADAS
$res = $conexion->query("SELECT COUNT(*) FROM reservas WHERE estado='confirmado'")->fetch_row();
$totales['reservas_confirmadas'] = $res[0] ?? 0;

// PAGOS STRIPE (Antes era 'pagos', corregido a 'pagos_stripe')
$res = $conexion->query("SELECT COUNT(*) FROM pagos_stripe")->fetch_row();
$totales['pagos_stripe'] = $res[0] ?? 0;

// MESAS LIBRES
$res = $conexion->query("SELECT COUNT(*) FROM mesas WHERE estado='libre' AND activo=1")->fetch_row();
$totales['mesas_libres'] = $res[0] ?? 0;
?>

<div class="content-wrapper">
    <!-- 🔹 HEADER PRO -->
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h6 class="text-indigo fw-800 text-uppercase mb-2" style="letter-spacing: 3px; font-size: 0.75rem;">Centro de Operaciones</h6>
            <h1 class="fw-800 text-slate-900 display-5 mb-0">Panel del Empleado</h1>
            <p class="text-muted fw-600 mt-2">Bienvenido de nuevo, <span class="text-indigo"><?= htmlspecialchars($_SESSION['nombre']) ?></span>. Gestión de salón activa.</p>
        </div>
        <div class="d-none d-md-block">
            <div class="bg-white px-4 py-3 rounded-20 shadow-sm border border-light d-flex align-items-center gap-3">
                <div class="bg-success-subtle p-2 rounded-circle">
                    <div class="status-dot-pulse bg-success"></div>
                </div>
                <span class="fw-800 text-slate-700 small text-uppercase" style="letter-spacing: 1px;">Sistema Operativo</span>
            </div>
        </div>
    </div>

    <!-- 🔹 KEY METRICS -->
    <div class="row g-4 mb-5">
        <!-- RESERVAS PENDIENTES -->
        <div class="col-xl-3 col-md-6">
            <div class="metric-card-pro">
                <div class="m-icon bg-warning-subtle text-warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="m-data">
                    <span class="m-label">Pendientes</span>
                    <h3 class="m-value"><?= $totales['reservas_pendientes'] ?></h3>
                </div>
                <a href="/clientes/controladores/empleado/ReservasEmpleadoController.php?accion=index" class="m-link">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- RESERVAS CONFIRMADAS -->
        <div class="col-xl-3 col-md-6">
            <div class="metric-card-pro">
                <div class="m-icon bg-indigo-subtle text-indigo">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="m-data">
                    <span class="m-label">Confirmadas</span>
                    <h3 class="m-value"><?= $totales['reservas_confirmadas'] ?></h3>
                </div>
                <a href="/clientes/controladores/empleado/ReservasEmpleadoController.php?accion=index" class="m-link">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- MESAS DISPONIBLES -->
        <div class="col-xl-3 col-md-6">
            <div class="metric-card-pro">
                <div class="m-icon bg-success-subtle text-success">
                    <i class="fas fa-chair"></i>
                </div>
                <div class="m-data">
                    <span class="m-label">Mesas Libres</span>
                    <h3 class="m-value"><?= $totales['mesas_libres'] ?></h3>
                </div>
                <a href="/clientes/controladores/empleado/MesasEmpleadoController.php?accion=croquis" class="m-link">
                    <i class="fas fa-map"></i>
                </a>
            </div>
        </div>

        <!-- TOTAL TRANSACCIONES -->
        <div class="col-xl-3 col-md-6">
            <div class="metric-card-pro">
                <div class="m-icon bg-slate-900 text-white">
                    <i class="fas fa-credit-card"></i>
                </div>
                <div class="m-data">
                    <span class="m-label">Pagos Stripe</span>
                    <h3 class="m-value"><?= $totales['pagos_stripe'] ?></h3>
                </div>
                <div class="m-link disabled">
                    <i class="fas fa-lock"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- 🔹 ACCIONES RÁPIDAS -->
    <div class="row">
        <div class="col-12">
            <div class="card-pro">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="p-3 bg-slate-50 rounded-16">
                        <i class="fas fa-bolt text-indigo"></i>
                    </div>
                    <h4 class="fw-800 text-slate-900 mb-0">Atajos de Gestión</h4>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="/clientes/controladores/empleado/ReservasEmpleadoController.php?accion=crear" class="quick-action-pro bg-slate-50 text-decoration-none">
                            <div class="qa-icon"><i class="fas fa-plus"></i></div>
                            <div class="qa-text">
                                <span class="d-block fw-800 text-slate-900">Nueva Reserva</span>
                                <span class="d-block text-muted small fw-600">Registrar cliente manual</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="/clientes/controladores/empleado/MesasEmpleadoController.php?accion=index" class="quick-action-pro bg-slate-50 text-decoration-none">
                            <div class="qa-icon"><i class="fas fa-rotate"></i></div>
                            <div class="qa-text">
                                <span class="d-block fw-800 text-slate-900">Actualizar Mesas</span>
                                <span class="d-block text-muted small fw-600">Cambiar estados de piso</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="/clientes/controladores/empleado/MesasEmpleadoController.php?accion=croquis" class="quick-action-pro bg-slate-50 text-decoration-none">
                            <div class="qa-icon"><i class="fas fa-eye"></i></div>
                            <div class="qa-text">
                                <span class="d-block fw-800 text-slate-900">Vista de Salón</span>
                                <span class="d-block text-muted small fw-600">Monitor visual interactivo</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .metric-card-pro {
        background: #fff; border-radius: 24px; padding: 25px;
        display: flex; align-items: center; gap: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid var(--slate-100);
        position: relative; transition: 0.3s;
    }
    .metric-card-pro:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.06); }
    
    .m-icon { width: 60px; height: 60px; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    .m-label { font-size: 0.75rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; }
    .m-value { font-size: 1.8rem; font-weight: 900; color: var(--slate-900); margin: 0; }
    
    .m-link {
        position: absolute; top: 15px; right: 15px; width: 32px; height: 32px;
        background: var(--slate-50); border-radius: 10px; display: flex; align-items: center;
        justify-content: center; color: var(--slate-400); text-decoration: none; transition: 0.3s;
    }
    .m-link:not(.disabled):hover { background: var(--indigo-pro); color: white; }
    .m-link.disabled { opacity: 0.3; cursor: not-allowed; }

    .quick-action-pro {
        display: flex; align-items: center; gap: 20px; padding: 20px;
        border-radius: 20px; border: 1px solid transparent; transition: 0.3s;
    }
    .quick-action-pro:hover { background: #fff !important; border-color: var(--slate-200); transform: translateX(5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
    
    .qa-icon { width: 44px; height: 44px; background: #fff; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--indigo-pro); box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
    
    .status-dot-pulse { width: 10px; height: 10px; border-radius: 50%; animation: pulse-green 2s infinite; }
    @keyframes pulse-green { 0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); } 70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); } 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); } }
    
    .bg-success-subtle { background: #d1fae5; }
    .bg-warning-subtle { background: #ffedd5; }
    .bg-indigo-subtle { background: #e0e7ff; }
</style>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
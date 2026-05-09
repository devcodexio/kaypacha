<?php
/**
 * 📍 vistas/cliente/reservas/croquis.php
 * 🎯 Croquis interactivo para reservas de clientes
 * ✅ Estados: 🟢 Libre | 🟡 Reservada | 🔴 Ocupada | ⚫ Fuera de servicio
 */

require_once __DIR__.'/../../layout/headerr.php';
require_once __DIR__.'/../../layout/sidebar_cliente.php';

// 🔐 Session y CSRF
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['csrf_token_cliente'])) {
    $_SESSION['csrf_token_cliente'] = bin2hex(random_bytes(32));
}

// 🔹 Recoger y validar filtros
$fecha = isset($_GET['fecha']) ? trim($_GET['fecha']) : '';
$personas = isset($_GET['personas']) ? trim($_GET['personas']) : '';
$filtro = isset($_GET['filtro']) ? trim($_GET['filtro']) : 'disponibles';
$buscado = !empty($fecha);

// Validar formato de fecha (YYYY-MM-DD)
if ($fecha && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
    $fecha = '';
    $buscado = false;
}

// Validar número de personas (1-30)
$personas_filter = (!empty($personas) && $personas !== 'todas') 
    ? max(1, min(30, (int)$personas)) 
    : null;

// Mesas disponibles (viene del controller)
$mesasDisponibles = $mesasDisponibles ?? [];

// 🔹 Helpers
function getCapClass($cap) {
    $cap = (int)$cap;
    if ($cap <= 2) return 'cap-1-2';
    if ($cap <= 4) return 'cap-3-4';
    if ($cap <= 6) return 'cap-5-6';
    return 'cap-7-plus';
}

function getEstadoInfo($estado) {
    $estado = strtolower(trim($estado ?? ''));
    return [
        'libre' => [
            'clase' => 'libre', 
            'texto' => 'Disponible', 
            'click' => true, 
            'icono' => '🟢',
            'color' => '#2ecc71'
        ],
        'reservada' => [
            'clase' => 'reservada', 
            'texto' => 'Reservada', 
            'click' => false, 
            'icono' => '🟡',
            'color' => '#f1c40f'
        ],
        'ocupada' => [
            'clase' => 'ocupada', 
            'texto' => 'Ocupada', 
            'click' => false, 
            'icono' => '🔴',
            'color' => '#e74c3c'
        ],
        'fuera_servicio' => [
            'clase' => 'fuera_servicio', 
            'texto' => 'Fuera', 
            'click' => false, 
            'icono' => '⚫',
            'color' => '#6c757d'
        ]
    ][$estado] ?? [
        'clase' => 'libre', 
        'texto' => 'Disponible', 
        'click' => true, 
        'icono' => '🟢',
        'color' => '#2ecc71'
    ];
}
?>

<!-- Estilos específicos para el Croquis Interactvo -->
<style>
        /* ========================================
           🎨 VARIABLES CSS
           ======================================== */
        :root {
            /* Croquis */
            --croquis-height: 1100px;
            --croquis-height-mobile: 800px;
            --croquis-height-small: 600px;
            --grid-size: 25px;
            
            /* Colores de estado */
            --color-libre: #2ecc71;
            --color-libre-dark: #27ae60;
            --color-reservada: #f1c40f;
            --color-reservada-dark: #d4ac0d;
            --color-ocupada: #e74c3c;
            --color-ocupada-dark: #c0392b;
            --color-fuera: #6c757d;
            --color-fuera-dark: #495057;
            
            /* UI */
            --color-primary: #4a69bd;
            --color-primary-dark: #1e3799;
            --bg-page: #f4f6fb;
            --bg-card: #ffffff;
            --text-primary: #2c3e50;
            --text-secondary: #7f8c8d;
            --border: #dee2e6;
            
            /* Efectos */
            --shadow-card: 0 4px 16px rgba(0,0,0,0.08);
            --shadow-mesa: 0 2px 6px rgba(0,0,0,0.12);
            --shadow-hover: 0 8px 25px rgba(0,0,0,0.25);
            --transition-fast: 0.2s ease;
            --transition-normal: 0.25s ease;
            --radius: 14px;
            --radius-full: 9999px;
        }

        /* ========================================
           🔄 RESET & BASE
           ======================================== */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Outfit', sans-serif;
            background: #f8fafc;
            color: #1e293b;
        }

        .content-wrapper {
            transition: all 0.4s ease;
            min-height: 100vh;
            padding-top: 20px;
        }

        /* ========================================
           📐 LAYOUT PRINCIPAL
           ======================================== */
        .page-container {
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ========================================
           📋 CARD DE BÚSQUEDA
           ======================================== */
        .card-busqueda {
            max-width: 700px;
            margin: 0 auto 40px;
            background: white;
            padding: 40px;
            border-radius: 32px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.05);
            border: 1px solid #f1f5f9;
        }

        .card-busqueda h4 {
            margin: 0 0 10px;
            color: #0f172a;
            font-size: 24px;
            font-weight: 800;
        }

        .card-busqueda p {
            color: #64748b;
            margin-bottom: 30px;
            font-size: 16px;
        }

        .card-busqueda .form-label {
            font-size: 12px;
            font-weight: 600;
            color: #555;
            margin-bottom: 4px;
            text-align: left;
            display: block;
        }

        .card-busqueda .form-control,
        .card-busqueda .form-select {
            font-size: 15px;
            padding: 12px 16px;
            border-radius: 14px;
            border: 1.5px solid #e2e8f0;
            background: #f8fafc;
            width: 100%;
            transition: all 0.3s ease;
            color: #1e293b;
            font-weight: 500;
        }

        .card-busqueda .form-control:focus,
        .card-busqueda .form-select:focus {
            border-color: #6366f1;
            background: white;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            outline: none;
        }

        .card-busqueda .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            border: none;
            padding: 16px;
            font-weight: 700;
            font-size: 16px;
            border-radius: 16px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2);
        }

        .card-busqueda .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(99, 102, 241, 0.3);
        }

        .card-busqueda .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        /* ========================================
           🗺️ CROQUIS WRAPPER
           ======================================== */
        .croquis-wrapper {
            position: relative;
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            background: var(--bg-card);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .croquis-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #eee;
            flex-wrap: wrap;
            gap: 10px;
        }

        .croquis-header h4 {
            margin: 0;
            font-size: 16px;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
        }

        .croquis-controls {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .btn-zoom {
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 8px;
            background: white;
            border: 1px solid var(--border);
            font-size: 18px;
            cursor: pointer;
            transition: var(--transition-fast);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #555;
        }

        .btn-zoom:hover, .btn-zoom:focus {
            background: var(--color-primary);
            color: white;
            border-color: var(--color-primary);
            outline: none;
        }

        .btn-zoom:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .zoom-level {
            font-size: 12px;
            color: #666;
            min-width: 45px;
            text-align: center;
            font-weight: 500;
        }

        /* ========================================
           🎨 CANVAS DEL CROQUIS
           ======================================== */
        .croquis-canvas {
            position: relative;
            width: 100%;
            height: var(--croquis-height);
            background: 
                linear-gradient(90deg, #e0e7ff 1px, transparent 1px),
                linear-gradient(#e0e7ff 1px, transparent 1px);
            background-size: var(--grid-size) var(--grid-size);
            border: 2px solid var(--color-primary);
            border-radius: 10px;
            overflow: hidden;
            cursor: crosshair;
            box-shadow: inset 0 0 20px rgba(74,105,189,0.08);
            transform-origin: top left;
            transition: transform var(--transition-fast);
        }

        /* Elementos decorativos */
        .croquis-piscina {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 185px;
            height: 500px;
            background: linear-gradient(135deg, #87ceeb, #add8e6);
            border: 2px solid #007bff;
            border-radius: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            font-weight: 600;
            color: #0056b3;
            z-index: 1;
            pointer-events: none;
            user-select: none;
        }
        .croquis-piscina::after { content: "🏊"; font-size: 20px; margin-right: 2px; }

        .croquis-barra {
            position: absolute;
            bottom: 25px;
            left: 25px;
            width: 110px;
            height: 36px;
            background: linear-gradient(135deg, var(--color-fuera), var(--color-fuera-dark));
            border: 2px solid #343a40;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 10px;
            font-weight: 600;
            z-index: 1;
            pointer-events: none;
        }
        .croquis-barra::before { content: "🍸"; margin-right: 4px; }

        /* ========================================
           🪑 TARJETAS DE MESA - ESTADOS CORREGIDOS
           ======================================== */
        .mesa-card {
            position: absolute;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition-normal) ease;
            box-shadow: var(--shadow-mesa);
            z-index: 10;
            padding: 4px;
            text-align: center;
            line-height: 1.2;
            border-radius: 50%;
            border: 3px solid white;
            outline: none;
            user-select: none;
        }

        .mesa-card:focus {
            box-shadow: 0 0 0 4px rgba(74,105,189,0.5), var(--shadow-mesa);
            transform: scale(1.1);
            z-index: 25;
        }

        .mesa-card:hover {
            transform: scale(1.15) translateY(-3px);
            box-shadow: var(--shadow-hover);
            z-index: 20;
        }

        /* ✅ Estados NO clickeables */
        .mesa-card.reservada,
        .mesa-card.ocupada,
        .mesa-card.fuera_servicio {
            cursor: not-allowed;
            opacity: 0.95;
        }

        /* Tamaños por capacidad */
        .mesa-card.cap-1-2 { width: 48px; height: 48px; font-size: 10px; }
        .mesa-card.cap-3-4 { width: 58px; height: 58px; font-size: 11px; }
        .mesa-card.cap-5-6 { width: 68px; height: 68px; font-size: 12px; }
        .mesa-card.cap-7-plus { width: 78px; height: 78px; font-size: 13px; }

        .mesa-card.rectangular { border-radius: 12px; }

        /* ✅ COLORES POR ESTADO - CORREGIDOS */
        .mesa-card.libre { 
            background: linear-gradient(135deg, var(--color-libre), var(--color-libre-dark)); 
            color: white; 
        }
        .mesa-card.reservada { 
            background: linear-gradient(135deg, var(--color-reservada), var(--color-reservada-dark)); 
            color: #333; 
        }
        .mesa-card.ocupada { 
            background: linear-gradient(135deg, var(--color-ocupada), var(--color-ocupada-dark)); 
            color: white; 
        }
        .mesa-card.fuera_servicio { 
            background: var(--color-fuera); 
            color: white; 
            opacity: 0.7; 
            border-style: dashed;
        }

        /* Elementos internos de la mesa */
        .mesa-numero { 
            font-size: 1.1em; 
            font-weight: 700; 
            line-height: 1; 
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        .mesa-cap { 
            font-size: 0.85em; 
            opacity: 0.95; 
            margin-top: 2px; 
        }
        .mesa-estado { 
            font-size: 0.75em; 
            opacity: 0.9; 
            margin-top: 2px; 
            text-transform: uppercase;
            font-weight: 600;
        }

        /* ========================================
           📊 LEYENDA
           ======================================== */
        .leyenda {
            display: flex;
            justify-content: center;
            gap: 20px;
            padding: 12px 20px;
            background: #f8f9fa;
            border-top: 1px solid #eee;
            flex-wrap: wrap;
        }

        .leyenda-item { 
            display: flex; 
            align-items: center; 
            gap: 6px; 
            font-size: 12px; 
            color: #555;
            font-weight: 500;
        }

        .leyenda-dot { 
            width: 16px; 
            height: 16px; 
            border-radius: 50%; 
            border: 2px solid currentColor;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .leyenda-dot.libre { background: var(--color-libre); border-color: var(--color-libre-dark); }
        .leyenda-dot.reservada { background: var(--color-reservada); border-color: var(--color-reservada-dark); }
        .leyenda-dot.ocupada { background: var(--color-ocupada); border-color: var(--color-ocupada-dark); }
        .leyenda-dot.fuera { background: var(--color-fuera); opacity: 0.7; }

        /* ========================================
           📭 ESTADO SIN RESULTADOS
           ======================================== */
        .no-mesas {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-secondary);
        }
        .no-mesas h4 { 
            color: var(--color-ocupada); 
            margin-bottom: 10px;
            font-size: 18px;
        }
        .no-mesas p { 
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 15px;
        }
        .no-mesas .btn-outline {
            display: inline-block;
            padding: 8px 20px;
            border: 2px solid var(--color-primary);
            color: var(--color-primary);
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition-fast);
        }
        .no-mesas .btn-outline:hover {
            background: var(--color-primary);
            color: white;
        }

        /* ========================================
           💬 MODAL DE RESERVA
           ======================================== */
        .modal-reserva {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.65);
            align-items: center;
            justify-content: center;
            z-index: 1000;
            backdrop-filter: blur(4px);
            padding: 20px;
            animation: fadeIn 0.2s ease;
        }
        .modal-reserva.visible { display: flex; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        .modal-box {
            background: white;
            padding: 28px;
            border-radius: 16px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: modalSlide 0.3s ease;
            position: relative;
        }
        @keyframes modalSlide {
            from { opacity: 0; transform: translateY(-20px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .modal-box h4 { 
            margin: 0 0 4px; 
            color: var(--text-primary);
            font-size: 20px;
            font-weight: 600;
        }
        .modal-box .subtitle {
            color: var(--text-secondary);
            margin-bottom: 20px;
            font-size: 14px;
        }

        .modal-box .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #555;
            margin-bottom: 6px;
            display: block;
        }
        .modal-box .form-control {
            font-size: 15px;
            padding: 10px 14px;
            border-radius: 10px;
            border: 1.5px solid var(--border);
            width: 100%;
            transition: var(--transition-fast);
        }
        .modal-box .form-control:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(74,105,189,0.1);
            outline: none;
        }
        .modal-box .help-text {
            font-size: 12px;
            color: var(--text-secondary);
            margin-top: 4px;
            display: block;
        }

        .modal-box .btn-success {
            background: linear-gradient(135deg, var(--color-libre), var(--color-libre-dark));
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 10px;
            font-size: 14px;
            color: white;
            cursor: pointer;
            transition: var(--transition-fast);
            width: 100%;
        }
        .modal-box .btn-success:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(46,204,113,0.35);
        }
        .modal-box .btn-success:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .modal-box .btn-outline-secondary {
            background: white;
            border: 1.5px solid var(--border);
            color: #555;
            padding: 12px;
            font-weight: 500;
            border-radius: 10px;
            cursor: pointer;
            transition: var(--transition-fast);
            width: 100%;
        }
        .modal-box .btn-outline-secondary:hover {
            background: #f8f9fa;
            border-color: #adb5bd;
        }

        .modal-actions {
            display: grid;
            gap: 10px;
            margin-top: 24px;
        }

        .modal-close {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 32px;
            height: 32px;
            border: none;
            background: #f1f3f5;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
            color: #666;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition-fast);
        }
        .modal-close:hover {
            background: #e9ecef;
            color: #333;
        }

        /* ========================================
           🔔 TOAST NOTIFICATION
           ======================================== */
        #toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 2000;
            display: none;
            animation: slideIn 0.3s ease;
        }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        #toast .toast-content {
            background: white;
            padding: 12px 20px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }

        /* ========================================
           📱 RESPONSIVE
           ======================================== */
        @media (max-width: 1100px) {
            .croquis-canvas { height: var(--croquis-height-mobile); }
        }
        @media (max-width: 768px) {
            .croquis-canvas { height: var(--croquis-height-small); }
            .croquis-header { flex-direction: column; align-items: flex-start; gap: 12px; }
            .croquis-controls { width: 100%; justify-content: center; }
            .card-busqueda { padding: 20px; margin-bottom: 20px; }
        }
        @media (max-width: 576px) {
            .croquis-canvas { transform: scale(0.75); transform-origin: top left; }
            .mesa-card { font-size: 9px !important; }
            .mesa-card.cap-1-2 { width: 42px; height: 42px; }
            .mesa-card.cap-3-4 { width: 50px; height: 50px; }
            .mesa-card.cap-5-6 { width: 58px; height: 58px; }
            .mesa-card.cap-7-plus { width: 66px; height: 66px; }
            .croquis-piscina { width: 90px; height: 250px; font-size: 14px; }
            .croquis-piscina::after { font-size: 35px; }
            .croquis-barra { width: 60px; height: 28px; font-size: 8px; bottom: 15px; left: 15px; }
            .leyenda { gap: 12px; }
            .leyenda-item { font-size: 11px; }
        }
        @media (min-width: 1600px) {
            :root { --croquis-height: 1300px; }
        }

        /* ========================================
           ♿ ACCESIBILIDAD
           ======================================== */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        kbd {
            background: #f1f3f5;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 11px;
            border: 1px solid #dee2e6;
        }
    </style>
<div class="content-wrapper">
    <div class="page-container">

    <!-- 🔹 CARD DE BÚSQUEDA -->
    <div class="card-busqueda" role="search">
        <h4>🍽 Reservar Mesa</h4>
        <p>Selecciona fecha, número de personas y explora el croquis interactivo.</p>

        <form method="GET" action="/clientes/controladores/cliente/ReservasClienteController.php" id="formBusqueda">
            <input type="hidden" name="accion" value="crear">
            
            <div class="row g-2">
                <div class="col-6">
                    <label class="form-label" for="fechaInput">📅 Fecha</label>
                    <input type="date" 
                           name="fecha" 
                           id="fechaInput"
                           value="<?= htmlspecialchars($fecha, ENT_QUOTES, 'UTF-8') ?>" 
                           class="form-control form-control-sm" 
                           required
                           min="<?= date('Y-m-d') ?>"
                           aria-describedby="fechaHelp">
                    <small id="fechaHelp" class="sr-only">Selecciona una fecha futura para tu reserva</small>
                </div>
                <div class="col-6">
                    <label class="form-label" for="personasInput">👥 Personas</label>
                    <select name="personas" id="personasInput" class="form-select form-select-sm">
                        <option value="todas" <?= empty($personas) || $personas==='todas' ? 'selected' : '' ?>>Todas</option>
                        <?php for($i=1; $i<=7; $i++): ?>
                        <option value="<?= $i ?>" <?= $personas===(string)$i ? 'selected' : '' ?>>
                            <?= $i === 7 ? '7+ personas' : "$i persona".($i>1?'s':'') ?>
                        </option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            
            <div class="mt-3">
                <label class="form-label" for="filtroInput">🔎 Mostrar</label>
                <select name="filtro" id="filtroInput" class="form-select form-select-sm">
                    <option value="disponibles" <?= $filtro=="disponibles"?'selected':'' ?>>🟢 Solo disponibles</option>
                    <option value="todas" <?= $filtro=="todas"?'selected':'' ?>>📋 Todas las mesas</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary w-100 mt-3 btn-sm" id="btnBuscar">🔍 Buscar mesas</button>
        </form>
    </div>

    <!-- 🔹 LEYENDA DE ESTADOS -->
    <div class="leyenda" role="list" aria-label="Leyenda de estados de mesas">
        <div class="leyenda-item" role="listitem">
            <span class="leyenda-dot libre" aria-hidden="true"></span> 
            <span>Disponible</span>
        </div>
        <div class="leyenda-item" role="listitem">
            <span class="leyenda-dot reservada" aria-hidden="true"></span> 
            <span>Reservada</span>
        </div>
        <div class="leyenda-item" role="listitem">
            <span class="leyenda-dot ocupada" aria-hidden="true"></span> 
            <span>Ocupada</span>
        </div>
        <div class="leyenda-item" role="listitem">
            <span class="leyenda-dot fuera" aria-hidden="true"></span> 
            <span>Fuera de servicio</span>
        </div>
    </div>

    <!-- 🔹 CROQUIS INTERACTIVO (solo si hay búsqueda) -->
    <?php if($buscado): ?>
    <div class="croquis-container">
        <div class="croquis-wrapper">
            
            <div class="croquis-header">
                <h4>🗺️ Croquis del Local</h4>
                <div class="croquis-controls" role="group" aria-label="Controles de zoom">
                    <button class="btn-zoom" id="btnZoomIn" title="Acercar (Ctrl +)" aria-label="Acercar zoom">+</button>
                    <span class="zoom-level" id="zoomLevel" aria-live="polite">100%</span>
                    <button class="btn-zoom" id="btnZoomOut" title="Alejar (Ctrl -)" aria-label="Alejar zoom">−</button>
                    <button class="btn-zoom" id="btnZoomReset" title="Resetear vista (Ctrl 0)" aria-label="Resetear zoom" style="font-size:12px">⟲</button>
                </div>
            </div>

            <div class="croquis-canvas" id="croquisCanvas" tabindex="-1" role="application" aria-label="Mapa interactivo del restaurante">
                
                <div class="croquis-piscina" aria-hidden="true">PISCINA</div>
                <div class="croquis-barra" aria-hidden="true">BARRA</div>

                <?php 
                // 🔹 Aplicar filtros a las mesas
                $mesasFiltradas = $mesasDisponibles ?? [];
                
                // Filtro por capacidad mínima
                if($personas_filter !== null) {
                    $mesasFiltradas = array_filter($mesasFiltradas, fn($m) => (int)($m['capacidad'] ?? 0) >= $personas_filter);
                }
                
                // Filtro por estado (solo disponibles o todas)
                if($filtro === 'disponibles') {
                    $mesasFiltradas = array_filter($mesasFiltradas, fn($m) => ($m['estado'] ?? '') === 'libre');
                }
                ?>

                <?php if(empty($mesasFiltradas)): ?>
                    <div class="no-mesas" role="status" aria-live="polite">
                        <h4>😔 No encontramos mesas disponibles</h4>
                        <p class="small">
                            <?php if($personas_filter): ?>
                                Intenta con menos personas o cambia la fecha de tu visita.
                            <?php elseif($filtro === 'disponibles'): ?>
                                Prueba mostrando "Todas las mesas" para ver el estado completo.
                            <?php else: ?>
                                Modifica la fecha o los filtros para ver más opciones.
                            <?php endif; ?>
                        </p>
                        <a href="#" class="btn-outline" onclick="document.getElementById('formBusqueda').scrollIntoView({behavior:'smooth'}); return false;">↩️ Modificar búsqueda</a>
                    </div>
                <?php endif; ?>

                <?php foreach($mesasFiltradas as $mesa): 
                    $capClass = getCapClass($mesa['capacidad'] ?? 2);
                    $estadoInfo = getEstadoInfo($mesa['estado'] ?? 'libre');
                    
                    // Posiciones con validación
                    $pos_top = (int)rtrim($mesa['pos_top'] ?? '250', 'px');
                    $pos_left = (int)rtrim($mesa['pos_left'] ?? '250', 'px');
                    if($pos_top < 15) $pos_top = 250;
                    if($pos_left < 15) $pos_left = 250;
                    
                    // Datos sanitizados
                    $tipo_forma = htmlspecialchars($mesa['tipo_forma'] ?? 'circular', ENT_QUOTES, 'UTF-8');
                    $zona = htmlspecialchars($mesa['zona'] ?? 'general', ENT_QUOTES, 'UTF-8');
                    $numero = (int)($mesa['numero_mesa'] ?? 0);
                    $capacidad = (int)($mesa['capacidad'] ?? 2);
                    $id = (int)($mesa['id'] ?? 0);
                    $puedeReservar = $estadoInfo['click'];
                ?>
                
                <!-- ✅ MESA CON ESTADO CORRECTO -->
                <button type="button"
                     class="mesa-card <?= $estadoInfo['clase'] ?> <?= $capClass ?> <?= $tipo_forma ?>"
                     style="top: <?= $pos_top ?>px; left: <?= $pos_left ?>px;"
                     <?= $puedeReservar ? 'data-mesa-id="'.$id.'" data-mesa-nombre="Mesa '.$numero.'" data-mesa-cap="'.$capacidad.'"' : '' ?>
                     title="<?= $estadoInfo['icono'] ?> <?= $estadoInfo['texto'] ?> | Capacidad: <?= $capacidad ?> personas | Zona: <?= $zona ?>"
                     data-zona="<?= $zona ?>"
                     <?= $puedeReservar ? '' : 'disabled aria-disabled="true"' ?>
                     aria-label="Mesa <?= $numero ?>, <?= $capacidad ?> personas, <?= $estadoInfo['texto'] ?><?= $puedeReservar ? ', clic para reservar' : '' ?>"
                     role="button"
                     tabindex="0">
                    
                    <span class="mesa-numero">#<?= $numero ?></span>
                    <span class="mesa-cap">👥<?= $capacidad ?></span>
                    
                    <?php if(!$puedeReservar): ?>
                    <span class="mesa-estado"><?= strtoupper(substr($estadoInfo['texto'],0,3)) ?></span>
                    <?php endif; ?>
                    
                </button>
                
                <?php endforeach; ?>
                
            </div>

            <div class="leyenda">
                <small style="color:#6c757d">
                    💡 <strong>Navegación:</strong> Rueda del mouse para zoom • 
                    <kbd>Ctrl</kbd>+<kbd>+</kbd>/<kbd>-</kbd> para acercar/alejar • 
                    Click en 🟢 para reservar
                </small>
            </div>

        </div>
    </div>
    <?php endif; ?>

</div>

<!-- 🔹 MODAL DE RESERVA -->
<div id="modalReserva" class="modal-reserva" role="dialog" aria-modal="true" aria-labelledby="modalTitle" aria-describedby="modalDesc">
    <div class="modal-box">
        <button type="button" class="modal-close" onclick="cerrarModal()" aria-label="Cerrar modal">×</button>
        
        <h4 id="modalTitle">Reservar Mesa</h4>
        <p id="modalDesc" class="subtitle"></p>
        
        <?php if(isset($_GET['error'])): ?>
        <div style="background:#fee; color:#c00; padding:10px 14px; border-radius:8px; margin-bottom:16px; font-size:14px;" role="alert">
            ⚠️ <?= htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" 
              action="/clientes/controladores/cliente/ReservasClienteController.php?accion=guardar"
              id="formReserva"
              novalidate>
            
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token_cliente'] ?>">
            <input type="hidden" name="mesa_id" id="mesa_id">
            <input type="hidden" name="fecha" value="<?= htmlspecialchars($fecha, ENT_QUOTES, 'UTF-8') ?>">
            
            <label class="form-label" for="horaInput">⏰ Hora de reserva</label>
            <input type="time" 
                   name="hora" 
                   id="horaInput" 
                   class="form-control form-control-sm" 
                   min="09:00" 
                   max="23:00" 
                   step="1800"
                   required
                   aria-describedby="horaHelp">
            <small id="horaHelp" class="help-text">Horario: 09:00am - 23:00 pm </small>
            
            <div class="modal-actions">
                <button type="submit" class="btn btn-success btn-sm" id="btnConfirmar">✅ Confirmar reserva</button>
                <button type="button" onclick="cerrarModal()" class="btn btn-outline-secondary btn-sm">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<!-- 🔹 TOAST NOTIFICATION -->
<div id="toast" role="status" aria-live="polite">
    <div class="toast-content">
        <span id="toastIcon"></span>
        <span id="toastMessage"></span>
    </div>
</div>

<!-- 🔹 JAVASCRIPT FUNCIONAL -->
<script>
const CONFIG = {
    zoomMin: 0.5,
    zoomMax: 2,
    zoomStep: 0.1,
    horaMin: 9,
    horaMax: 23
};

const state = { zoom: 1 };

const els = {
    canvas: document.getElementById('croquisCanvas'),
    zoomLevel: document.getElementById('zoomLevel'),
    btnZoomIn: document.getElementById('btnZoomIn'),
    btnZoomOut: document.getElementById('btnZoomOut'),
    btnZoomReset: document.getElementById('btnZoomReset'),
    modal: document.getElementById('modalReserva'),
    formReserva: document.getElementById('formReserva'),
    toast: document.getElementById('toast')
};

// 🔹 Actualizar zoom visual
function updateZoom() {
    if(els.canvas) {
        els.canvas.style.transform = `scale(${state.zoom})`;
    }
    if(els.zoomLevel) {
        els.zoomLevel.textContent = Math.round(state.zoom * 100) + '%';
    }
    if(els.btnZoomIn) els.btnZoomIn.disabled = state.zoom >= CONFIG.zoomMax;
    if(els.btnZoomOut) els.btnZoomOut.disabled = state.zoom <= CONFIG.zoomMin;
}

// 🔹 Controles de zoom
function zoomIn() {
    if(state.zoom < CONFIG.zoomMax) {
        state.zoom = Math.min(state.zoom + CONFIG.zoomStep, CONFIG.zoomMax);
        updateZoom();
    }
}

function zoomOut() {
    if(state.zoom > CONFIG.zoomMin) {
        state.zoom = Math.max(state.zoom - CONFIG.zoomStep, CONFIG.zoomMin);
        updateZoom();
    }
}

function resetZoom() {
    state.zoom = 1;
    updateZoom();
}

// 🔹 Click en mesa disponible
function handleMesaClick(e) {
    const btn = e.target.closest('.mesa-card[data-mesa-id]');
    if(!btn || btn.disabled) return;
    abrirReserva(btn.dataset.mesaId, btn.dataset.mesaNombre, btn.dataset.mesaCap);
}

// 🔹 Abrir modal de reserva
function abrirReserva(id, nombre, capacidad) {
    document.getElementById('mesa_id').value = id;
    document.getElementById('modalTitle').textContent = nombre || 'Reservar Mesa';
    document.getElementById('modalDesc').textContent = `Capacidad: ${capacidad || '?'} personas`;
    if(els.formReserva) els.formReserva.reset();
    els.modal?.classList.add('visible');
    setTimeout(() => document.getElementById('horaInput')?.focus(), 300);
    document.body.style.overflow = 'hidden';
}

// 🔹 Cerrar modal
function cerrarModal() {
    els.modal?.classList.remove('visible');
    document.body.style.overflow = '';
}

// 🔹 Validar formulario de reserva
function validarFormReserva(e) {
    const hora = document.getElementById('horaInput')?.value;
    if(!hora) {
        e.preventDefault();
        showToast('⚠️ Por favor selecciona una hora', 'error');
        return false;
    }
    const [h, m] = hora.split(':').map(Number);
    const horaNum = h + m/60;
    if(horaNum < CONFIG.horaMin || horaNum > CONFIG.horaMax) {
        e.preventDefault();
        showToast(`⚠️ Horario permitido: ${CONFIG.horaMin}:00 - ${CONFIG.horaMax}:00`, 'error');
        return false;
    }
    const btn = document.getElementById('btnConfirmar');
    if(btn) {
        btn.disabled = true;
        btn.textContent = '⏳ Procesando...';
    }
    return true;
}

// 🔹 Mostrar toast notification
function showToast(mensaje, tipo = 'success') {
    if(!els.toast) return;
    document.getElementById('toastIcon').textContent = tipo === 'error' ? '⚠️' : '✓';
    document.getElementById('toastMessage').textContent = mensaje;
    els.toast.style.display = 'block';
    setTimeout(() => { els.toast.style.display = 'none'; }, 3000);
}

// 🔹 Atajos de teclado globales
function handleGlobalKeydown(e) {
    // Zoom con Ctrl
    if(e.ctrlKey && !e.target.matches('input, textarea, select')) {
        switch(e.key) {
            case '+': case '=': e.preventDefault(); zoomIn(); break;
            case '-': e.preventDefault(); zoomOut(); break;
            case '0': e.preventDefault(); resetZoom(); break;
        }
    }
    // Cerrar modal con Escape
    if(e.key === 'Escape' && els.modal?.classList.contains('visible')) {
        e.preventDefault();
        cerrarModal();
    }
}

// 🔹 Inicialización
function init() {
    // Event listeners de zoom
    els.btnZoomIn?.addEventListener('click', zoomIn);
    els.btnZoomOut?.addEventListener('click', zoomOut);
    els.btnZoomReset?.addEventListener('click', resetZoom);
    
    // Click en mesas
    els.canvas?.addEventListener('click', handleMesaClick);
    
    // Cerrar modal al hacer click fuera
    els.modal?.addEventListener('click', (e) => { 
        if(e.target === els.modal) cerrarModal(); 
    });
    
    // Validación de formulario
    els.formReserva?.addEventListener('submit', validarFormReserva);
    
    // Atajos de teclado
    document.addEventListener('keydown', handleGlobalKeydown);
    
    // Zoom con rueda del mouse + Ctrl
    els.canvas?.addEventListener('wheel', (e) => {
        if(e.ctrlKey) {
            e.preventDefault();
            e.deltaY < 0 ? zoomIn() : zoomOut();
        }
    }, { passive: false });
    
    // Estado inicial
    updateZoom();
}

// 🔹 Ejecutar cuando el DOM esté listo
if(document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}
</script>

<?php require_once __DIR__.'/../../layout/footer.php'; ?>    </div> <!-- end page-container -->
</div> <!-- end content-wrapper -->

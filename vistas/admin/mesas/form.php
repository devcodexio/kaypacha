<?php
// 📍 vistas/admin/mesas/form.php
// ✅ DRAG FUNCIONAL + CROQUIS GRANDE + FORM COMPACTO + MEJORAS UX + SEGURIDAD + ACCESIBILIDAD

require_once __DIR__ . '/../../layout/headerr.php';
require_once __DIR__ . '/../../layout/sidebar_admin.php';

// 🔒 Generar token CSRF si no existe
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$editando = isset($mesa);
$action = "/clientes/controladores/admin/MesasAdminController.php?accion=guardar";

// Valores con fallback seguro + escape para output
$numero = $editando ? htmlspecialchars($mesa['numero_mesa'] ?? '', ENT_QUOTES, 'UTF-8') : '';
$capacidad = $editando ? (int)($mesa['capacidad'] ?? 2) : 2;
$estado = $editando ? ($mesa['estado'] ?? 'libre') : 'libre';
$tipo_forma = $editando ? ($mesa['tipo_forma'] ?? 'circular') : 'circular';
$zona = $editando ? ($mesa['zona'] ?? 'general') : 'general';
$activo = $editando ? (bool)($mesa['activo'] ?? true) : true;

// Normalizar posiciones (quitar 'px' si existe)
$pos_top = $editando && !empty($mesa['pos_top']) ? (int)rtrim($mesa['pos_top'], 'px') : 250;
$pos_left = $editando && !empty($mesa['pos_left']) ? (int)rtrim($mesa['pos_left'], 'px') : 250;

// ✅ OBTENER MESAS - Optimizado: filtrar por zona + prepared statements
$all_mesas = [];
try {
    if (isset($conexion) && $conexion instanceof mysqli) {
        $stmt = $conexion->prepare("SELECT id, numero_mesa, capacidad, estado, zona, tipo_forma, pos_top, pos_left, activo FROM mesas WHERE zona = ? OR zona IS NULL ORDER BY numero_mesa ASC");
        $stmt->bind_param("s", $zona);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $row['pos_top_num'] = (int)rtrim($row['pos_top'] ?? '0', 'px');
                $row['pos_left_num'] = (int)rtrim($row['pos_left'] ?? '0', 'px');
                $all_mesas[] = $row;
            }
        }
        $stmt->close();
    } elseif (function_exists('getDatabaseConnection')) {
        $pdo = getDatabaseConnection();
        $stmt = $pdo->prepare("SELECT id, numero_mesa, capacidad, estado, zona, tipo_forma, pos_top, pos_left, activo FROM mesas WHERE zona = :zona OR zona IS NULL ORDER BY numero_mesa ASC");
        $stmt->execute(['zona' => $zona]);
        $raw_mesas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($raw_mesas as $row) {
            $row['pos_top_num'] = (int)rtrim($row['pos_top'] ?? '0', 'px');
            $row['pos_left_num'] = (int)rtrim($row['pos_left'] ?? '0', 'px');
            $all_mesas[] = $row;
        }
    }
} catch(Exception $e) {
    error_log("Error cargando mesas: " . $e->getMessage());
    $all_mesas = [];
}

function getCapClass($cap) {
    $cap = (int)$cap;
    if($cap <= 2) return 'cap-1-2';
    if($cap <= 4) return 'cap-3-4';
    if($cap <= 6) return 'cap-5-6';
    return 'cap-7-plus';
}
?>

<!-- 🎨 CSS con variables para mantenibilidad -->
<style>
:root {
    --croquis-height: 1100px;
    --croquis-height-mobile: 800px;
    --croquis-height-small: 600px;
    --grid-size: 25px;
    --min-margin: 15px;
    --mesa-edit-border: 3px;
    --color-primary: #4a69bd;
    --color-primary-dark: #1e3799;
    --color-success: #2ecc71;
    --color-warning: #f1c40f;
    --color-danger: #e74c3c;
    --color-gray: #6c757d;
    --shadow-card: 0 4px 16px rgba(0,0,0,.08);
    --shadow-mesa: 0 2px 6px rgba(0,0,0,0.12);
    --transition-fast: .2s;
    --transition-normal: .25s;
}

/* === 🎨 LAYOUT GENERAL === */
.form-wrapper {
    display: flex;
    justify-content: center;
    padding: 15px;
    background: #f4f6fb;
    min-height: calc(100vh - 80px);
}
.form-container {
    width: 100%;
    max-width: 1300px;
    display: grid;
    grid-template-columns: 380px 1fr;
    gap: 20px;
    align-items: start;
}

/* === 📋 FORMULARIO PRO === */
.form-card {
    background: white;
    border-radius: 32px;
    padding: 30px;
    box-shadow: var(--shadow-pro);
    border: 1px solid var(--slate-100);
}
.form-card h2 {
    margin-bottom: 30px;
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--slate-900);
    display: flex;
    align-items: center;
    gap: 12px;
}
.form-section { 
    margin-bottom: 24px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--slate-100);
}
.form-section h4 {
    font-size: 0.7rem;
    font-weight: 800;
    color: var(--primary-pro);
    margin-bottom: 15px;
    text-transform: uppercase;
    letter-spacing: 2px;
}
.form-label {
    font-weight: 800;
    font-size: 0.75rem;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 8px;
}
.form-control-pro, .form-select-pro {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid var(--slate-100);
    border-radius: 14px;
    font-size: 0.9rem;
    font-weight: 600;
    background: var(--slate-50);
    transition: all 0.3s ease;
}
.form-control-pro:focus, .form-select-pro:focus {
    outline: none;
    border-color: var(--primary-pro);
    background: #fff;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
}

/* === 🗺️ CROQUIS PRO === */
.croquis-panel {
    background: white;
    border-radius: 32px;
    padding: 30px;
    box-shadow: var(--shadow-pro);
    border: 1px solid var(--slate-100);
}
.croquis-canvas {
    position: relative;
    width: 100%;
    height: var(--croquis-height);
    background: #fdfdfd;
    background-image: 
        linear-gradient(rgba(99, 102, 241, 0.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(99, 102, 241, 0.05) 1px, transparent 1px);
    background-size: var(--grid-size) var(--grid-size);
    border: 1px solid var(--slate-200);
    border-radius: 20px;
    overflow: hidden;
}
.croquis-canvas.debug-mode {
    outline: 2px dashed #ff6b6b;
    background-image: 
        linear-gradient(90deg, rgba(74,105,189,0.2) 1px, transparent 1px),
        linear-gradient(rgba(74,105,189,0.2) 1px, transparent 1px);
}

/* Elementos decorativos */
.croquis-piscina {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%) rotate(-0deg);
    width: 185px; height: 500px;
    background: linear-gradient(135deg, #87ceeb, #add8e6);
    border: 2px solid #007bff;
    border-radius: 50px;
    display: flex; align-items: center; justify-content: center;
    font-size: 30px; font-weight: 600; color: #0056b3;
    z-index: 1; pointer-events: none;
}
.croquis-piscina::after { content: "🏊"; font-size: 20px; margin-right: 2px; }

.croquis-barra {
    position: absolute;
    bottom: 25px; left: 25px;
    width: 110px; height: 36px;
    background: linear-gradient(135deg, #6c757d, #495057);
    border: 2px solid #343a40;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 10px; font-weight: 600;
    z-index: 1; pointer-events: none;
}
.croquis-barra::before { content: "🍸"; margin-right: 4px; }

/* === MESAS EXISTENTES === */
.mesa-existente {
    position: absolute;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    font-weight: 600;
    z-index: 10;
    padding: 2px;
    text-align: center;
    border-radius: 50%;
    border: 2px solid white;
    opacity: 0.6;
    pointer-events: none;
    transition: opacity var(--transition-fast), transform var(--transition-fast);
    box-shadow: var(--shadow-mesa);
}
.mesa-existente:hover { opacity: 0.85; transform: scale(1.03); z-index: 15; }

/* Tamaños mesas existentes */
.mesa-existente.cap-1-2.circular { width: 36px; height: 36px; font-size: 8px; }
.mesa-existente.cap-3-4.circular { width: 46px; height: 46px; font-size: 9px; }
.mesa-existente.cap-5-6.circular { width: 56px; height: 56px; font-size: 10px; }
.mesa-existente.cap-7-plus.circular { width: 66px; height: 66px; font-size: 11px; }
.mesa-existente.rectangular { border-radius: 8px; }
.mesa-existente.cap-1-2.rectangular { width: 46px !important; height: 32px !important; }
.mesa-existente.cap-3-4.rectangular { width: 56px !important; height: 36px !important; }
.mesa-existente.cap-5-6.rectangular { width: 66px !important; height: 40px !important; }
.mesa-existente.grande { width: 66px !important; height: 66px !important; background: linear-gradient(135deg, #9b59b6, #8e44ad) !important; }

/* Estados mesas existentes */
.mesa-existente.libre { background: linear-gradient(135deg, var(--color-success), #27ae60); color: white; }
.mesa-existente.reservada { background: linear-gradient(135deg, var(--color-warning), #f39c12); color: #333; }
.mesa-existente.ocupada { background: linear-gradient(135deg, var(--color-danger), #c0392b); color: white; }
.mesa-existente.fuera_servicio { background: var(--color-gray); color: white; opacity: 0.3; border-style: dashed; }
.mesa-num { font-size: 0.9em; line-height: 1; font-weight: 700; }
.mesa-cap { font-size: 0.6em; opacity: 0.9; margin-top: 1px; }

/* === ✏️ MESA EDITABLE === */
.mesa-edit {
    position: absolute;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    font-weight: 700; color: white;
    cursor: grab;
    user-select: none;
    z-index: 50;
    padding: 3px;
    text-align: center;
    border-radius: 50%;
    border: var(--mesa-edit-border) solid #fff;
    box-shadow: 
        0 0 0 3px rgba(74, 105, 189, 0.85),
        0 0 20px rgba(74, 105, 189, 0.55),
        0 6px 24px rgba(0,0,0,0.35);
    animation: pulse-edit 2s ease-in-out infinite;
    position: relative;
    outline: none;
}
.mesa-edit:focus {
    box-shadow: 0 0 0 4px rgba(255,255,255,1), 0 0 35px rgba(74, 105, 189, 1), 0 12px 40px rgba(0,0,0,0.5);
}

@keyframes pulse-edit {
    0%, 100% { 
        box-shadow: 0 0 0 3px rgba(74, 105, 189, 0.85), 0 0 20px rgba(74, 105, 189, 0.55), 0 6px 24px rgba(0,0,0,0.35);
        transform: scale(1);
    }
    50% { 
        box-shadow: 0 0 0 5px rgba(74, 105, 189, 0.35), 0 0 30px rgba(74, 105, 189, 0.75), 0 10px 32px rgba(0,0,0,0.45);
        transform: scale(1.025);
    }
}

.mesa-edit:hover { 
    transform: scale(1.08); 
    z-index: 60;
    box-shadow: 0 0 0 4px rgba(74, 105, 189, 1), 0 0 35px rgba(74, 105, 189, 0.85), 0 12px 40px rgba(0,0,0,0.5);
}

.mesa-edit.dragging { 
    cursor: grabbing; 
    transform: scale(1.12) !important; 
    z-index: 70;
    animation: none;
    box-shadow: 0 0 0 5px rgba(255,255,255,1), 0 0 45px rgba(74, 105, 189, 1), 0 18px 50px rgba(0,0,0,0.65);
}

.mesa-edit::after {
    content: "✏️ EDITANDO";
    position: absolute;
    top: -26px;
    left: 50%;
    transform: translateX(-50%);
    background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
    color: white;
    font-size: 9px;
    font-weight: 700;
    padding: 3px 9px;
    border-radius: 16px;
    white-space: nowrap;
    box-shadow: 0 2px 8px rgba(0,0,0,0.25);
    z-index: 100;
    animation: float-label 2.5s ease-in-out infinite;
    pointer-events: none;
}

@keyframes float-label {
    0%, 100% { transform: translateX(-50%) translateY(0); }
    50% { transform: translateX(-50%) translateY(-2px); }
}

/* Tamaños mesa edit */
.mesa-edit.cap-1-2.circular { width: 44px; height: 44px; font-size: 10px; }
.mesa-edit.cap-3-4.circular { width: 54px; height: 54px; font-size: 11px; }
.mesa-edit.cap-5-6.circular { width: 64px; height: 64px; font-size: 12px; }
.mesa-edit.cap-7-plus.circular { width: 74px; height: 74px; font-size: 13px; }
.mesa-edit.rectangular { border-radius: 10px; }
.mesa-edit.cap-1-2.rectangular { width: 54px !important; height: 36px !important; }
.mesa-edit.cap-3-4.rectangular { width: 64px !important; height: 40px !important; }
.mesa-edit.cap-5-6.rectangular { width: 74px !important; height: 44px !important; }
.mesa-edit.grande { width: 74px !important; height: 74px !important; background: linear-gradient(135deg, #9b59b6, #8e44ad) !important; }

/* Estados mesa edit */
.mesa-edit.libre { background: linear-gradient(135deg, var(--color-success), #27ae60); }
.mesa-edit.reservada { background: linear-gradient(135deg, var(--color-warning), #f39c12); color: #333; }
.mesa-edit.ocupada { background: linear-gradient(135deg, var(--color-danger), #c0392b); }
.mesa-edit.fuera_servicio { background: var(--color-gray); opacity: 0.7; cursor: not-allowed; }
.mesa-edit.fuera_servicio::after { content: "🚫 BLOQUEADA"; background: var(--color-gray); }

.mesa-edit-num { font-size: 0.95em; line-height: 1; font-weight: 800; }
.mesa-edit-cap { font-size: 0.65em; opacity: 0.95; margin-top: 1px; }

/* === 📍 COORDENADAS Y AYUDA === */
.coords-box {
    margin-top: 12px;
    padding: 10px 12px;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 8px;
    font-size: 11px;
    color: #333;
    border-left: 3px solid var(--color-primary);
    border: 1.5px solid var(--color-primary);
}
.coords-box code {
    background: var(--color-primary);
    color: white;
    padding: 3px 8px;
    border-radius: 5px;
    font-family: 'Courier New', monospace;
    font-weight: 700;
    margin: 0 2px;
}
.coords-hint {
    margin-top: 6px;
    font-size: 10px;
    color: #666;
    padding-top: 6px;
    border-top: 1px dashed #ccc;
}
.coords-error {
    color: var(--color-danger);
    font-weight: 600;
    display: none;
    margin-top: 4px;
}
.coords-error.visible { display: block; animation: shake .3s; }
@keyframes shake { 0%,100%{transform:translateX(0)} 25%{transform:translateX(-3px)} 75%{transform:translateX(3px)} }

/* Toggle Activo compacto */
.toggle-wrap { display: flex; align-items: center; gap: 8px; }
.toggle-input { display: none; }
.toggle-label {
    width: 42px; height: 24px;
    background: #e9ecef;
    border-radius: 12px;
    position: relative;
    cursor: pointer;
    transition: var(--transition-normal);
}
.toggle-label::after {
    content: '';
    position: absolute;
    width: 18px; height: 18px;
    background: white;
    border-radius: 50%;
    top: 3px; left: 3px;
    transition: var(--transition-normal);
    box-shadow: 0 2px 4px rgba(0,0,0,.15);
}
.toggle-input:checked + .toggle-label { background: var(--color-success); }
.toggle-input:checked + .toggle-label::after { left: 21px; }

/* Botones compactos */
.form-actions { display: flex; gap: 8px; margin-top: 8px; }
.btn-submit {
    flex: 1;
    padding: 9px 12px;
    background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition-fast);
}
.btn-submit:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(74,105,189,.35); }
.btn-submit:disabled { opacity: .6; cursor: not-allowed; transform: none; }
.btn-cancel {
    padding: 9px 14px;
    background: #f8f9fa;
    color: #333;
    border: 1.5px solid #dee2e6;
    border-radius: 8px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
}
.btn-cancel:hover { background: #e9ecef; }

/* Leyenda compacta */
.croquis-legend {
    margin-top: 12px;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 8px;
    font-size: 10px;
    color: #555;
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}
.legend-item { display: flex; align-items: center; gap: 5px; }
.legend-dot { width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 1px 2px rgba(0,0,0,.15); }
.legend-dot.edit { background: var(--color-primary); animation: pulse-dot 2s infinite; }
@keyframes pulse-dot { 0%,100%{opacity:1} 50%{opacity:0.55} }
.legend-dot.ref { background: var(--color-gray); opacity: .65; }
.legend-dot.libre { background: var(--color-success); }
.legend-dot.ocupada { background: var(--color-danger); }

.hint-text {
    display: block;
    margin-top: 10px;
    color: #555;
    font-size: 10px;
    line-height: 1.45;
    background: #f8f9fa;
    padding: 9px;
    border-radius: 7px;
    border-left: 2.5px solid var(--color-primary);
}

/* === 📱 RESPONSIVE === */
@media (max-width: 1100px) {
    .form-container { grid-template-columns: 1fr; max-width: 500px; }
    .croquis-panel { position: static; }
    .croquis-canvas { height: var(--croquis-height-mobile); }
    .form-card { max-width: 100%; }
}
@media (max-width: 768px) {
    .form-wrapper { padding: 10px; }
    .croquis-canvas { height: var(--croquis-height-small); }
    .form-row { grid-template-columns: 1fr; }
    .form-card { padding: 15px 18px; }
}
@media (min-width: 1600px) {
    :root { --croquis-height: 1300px; }
}

/* === 🎯 UTILIDADES === */
.sr-only {
    position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px;
    overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0;
}
</style>

<div class="page-container">
<main class="main-content">

<div class="form-wrapper">
<div class="form-container">

    <!-- FORMULARIO COMPACTO -->
    <div class="form-card">
        <h2>
            <i class="fas fa-chair"></i>
            <?= $editando ? '✏️ Editar' : '➕ Nueva' ?>
        </h2>

        <form action="<?= $action ?>" method="POST" id="mesaForm" data-saved="false">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <?php if($editando): ?>
                <input type="hidden" name="id" value="<?= (int)$mesa['id'] ?>">
            <?php endif; ?>

            <div class="form-section">
                <h4>📐 Parámetros</h4>
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label" for="numeroInput">Mesa #</label>
                        <input type="number" name="numero_mesa" class="form-control-pro" 
                               id="numeroInput" value="<?= $numero ?>" required min="1">
                    </div>
                    <div class="col-6">
                        <label class="form-label" for="capacidadInput">Pax</label>
                        <input type="number" name="capacidad" class="form-control-pro" 
                               id="capacidadInput" value="<?= (int)$capacidad ?>" required min="1">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h4>🎯 Atributos</h4>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label" for="estadoSelect">Estado</label>
                        <select name="estado" class="form-select-pro" id="estadoSelect">
                            <option value="libre" <?= $estado=='libre'?'selected':'' ?>>LIBRE</option>
                            <option value="reservada" <?= $estado=='reservada'?'selected':'' ?>>RESERVADA</option>
                            <option value="ocupada" <?= $estado=='ocupada'?'selected':'' ?>>OCUPADA</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="tipoForma">Geometría</label>
                        <select name="tipo_forma" class="form-select-pro" id="tipoForma">
                            <option value="circular" <?= $tipo_forma=='circular'?'selected':'' ?>>CIRCULAR</option>
                            <option value="rectangular" <?= $tipo_forma=='rectangular'?'selected':'' ?>>RECTANGULAR</option>
                        </select>
                    </div>
                </div>
            </div>

            <input type="hidden" name="pos_top" id="pos_top" value="<?= (int)$pos_top ?>px">
            <input type="hidden" name="pos_left" id="pos_left" value="<?= (int)$pos_left ?>px">

            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-primary py-3 rounded-16 fw-800 shadow-pro border-0" style="background: var(--slate-900);">
                    <?= $editando ? 'ACTUALIZAR' : 'REGISTRAR' ?>
                </button>
                <a href="?accion=index" class="btn btn-link text-muted fw-700 text-decoration-none">Cancelar</a>
            </div>
        </form>
    </div>

    <!-- CROQUIS GRANDE -->
    <aside class="croquis-panel">
        <h4>🗺️ Croquis del local</h4>
        <small style="display:block; margin:-3px 0 12px 10px; color:#666; font-size:11px;">
            🔘 Grises = existentes • 🔵 Azul con ✏️ = la que editas
        </small>
        
        <div class="croquis-canvas" id="croquisCanvas" role="application" aria-label="Croquis interactivo para posicionar mesas">
            <div class="croquis-piscina">PISCINA</div>
            <div class="croquis-barra">BARRA</div>
            
            <!-- Mesas existentes -->
            <?php foreach($all_mesas as $m): 
                if($editando && isset($mesa['id']) && $m['id'] == $mesa['id']) continue;
                $capClass = getCapClass($m['capacidad']);
                $forma = $m['tipo_forma'] ?? 'circular';
                $estado = (!empty($m['activo']) ? ($m['estado'] ?? 'libre') : 'fuera_servicio');
                $top = $m['pos_top_num'] ?? 100;
                $left = $m['pos_left_num'] ?? 100;
            ?>
            <div class="mesa-existente <?= $capClass ?> <?= $forma ?> <?= $estado ?>"
                 style="top: <?= (int)$top ?>px; left: <?= (int)$left ?>px;"
                 title="Mesa #<?= (int)$m['numero_mesa'] ?> | <?= (int)$m['capacidad'] ?>p | <?= htmlspecialchars($m['zona'], ENT_QUOTES, 'UTF-8') ?>"
                 aria-hidden="true">
                <span class="mesa-num">#<?= (int)$m['numero_mesa'] ?></span>
                <span class="mesa-cap">👥<?= (int)$m['capacidad'] ?></span>
            </div>
            <?php endforeach; ?>
            
            <!-- ✅ MESA EDITABLE CON ACCESIBILIDAD -->
            <div class="mesa-edit <?= getCapClass($capacidad) ?> <?= $tipo_forma ?> <?= $estado ?>"
                 id="mesaEdit"
                 style="top: <?= (int)$pos_top ?>px; left: <?= (int)$pos_left ?>px;"
                 draggable="false"
                 role="button"
                 tabindex="0"
                 aria-label="Mesa <?= $numero ?: 'Nueva' ?>, capacidad <?= (int)$capacidad ?> personas, arrastrable con mouse o teclado"
                 aria-grabbed="false"
                 data-cap="<?= (int)$capacidad ?>"
                 data-forma="<?= htmlspecialchars($tipo_forma, ENT_QUOTES, 'UTF-8') ?>"
                 data-id="<?= $editando ? (int)$mesa['id'] : 'new' ?>"
                 data-original-top="<?= (int)$pos_top ?>"
                 data-original-left="<?= (int)$pos_left ?>">
                <span class="mesa-edit-num">#<?= $numero ?: 'Nueva' ?></span>
                <span class="mesa-edit-cap">👥<?= (int)$capacidad ?></span>
                <span class="sr-only">Presiona Enter para arrastrar, usa flechas para mover, Escape para cancelar</span>
            </div>
        </div>

        <!-- Coordenadas con accesibilidad -->
        <div class="coords-box" aria-live="polite">
            <div>📍 Posición: <code id="coordDisplay">Top: <?= (int)$pos_top ?>px | Left: <?= (int)$pos_left ?>px</code></div>
            <div class="coords-hint">
                💡 <strong>Tip:</strong> Arrastra la mesa 🔵 azul o usa <kbd>↑</kbd><kbd>↓</kbd><kbd>←</kbd><kbd>→</kbd>
            </div>
            <div class="coords-error" id="coordsError" role="alert"></div>
        </div>

        <div class="croquis-legend">
            <div class="legend-item"><span class="legend-dot edit"></span> Editando</div>
            <div class="legend-item"><span class="legend-dot ref"></span> Existente</div>
            <div class="legend-item"><span class="legend-dot libre"></span> Libre</div>
            <div class="legend-item"><span class="legend-dot ocupada"></span> Ocupada</div>
        </div>

        <span class="hint-text">
            <strong>🎮 Uso:</strong><br>
            1️⃣ Busca la mesa 🔵 con ✏️<br>
            2️⃣ <strong>Clic + arrastra</strong> o usa <kbd>Tab</kbd> + <kbd>Enter</kbd><br>
            3️⃣ Flechas para ajustar • <kbd>Shift</kbd> + flecha = movimiento rápido<br>
            4️⃣ Guarda con el botón ✅
        </span>
    </aside>

</div>
</div>
</main>
</div>

<script>
// === 🎛️ CONFIGURACIÓN CENTRALIZADA ===
const CONFIG = {
    gridSize: 25,
    minMargin: 15,
    canvasHeight: 1100,
    colors: {
        libre: '#2ecc71',
        ocupada: '#e74c3c',
        reservada: '#f1c40f',
        fuera: '#6c757d'
    },
    debug: window.location.hostname === 'localhost'
};

// === 📦 ELEMENTOS DOM ===
const elements = {
    mesaEdit: document.getElementById('mesaEdit'),
    canvas: document.getElementById('croquisCanvas'),
    form: document.getElementById('mesaForm'),
    inputs: {
        capacidad: document.getElementById('capacidadInput'),
        numero: document.getElementById('numeroInput'),
        estado: document.getElementById('estadoSelect'),
        forma: document.getElementById('tipoForma'),
        zona: document.getElementById('zonaSelect'),
        posTop: document.getElementById('pos_top'),
        posLeft: document.getElementById('pos_left')
    },
    coordDisplay: document.getElementById('coordDisplay'),
    coordsError: document.getElementById('coordsError'),
    btnSubmit: document.getElementById('btnSubmit')
};

// === 🔄 ACTUALIZAR VISTA PREVIA ===
function updateMesaPreview() {
    const { mesaEdit, inputs } = elements;
    if(!mesaEdit) return;
    
    const cap = parseInt(inputs.capacidad?.value) || 2;
    const num = inputs.numero?.value || 'Nueva';
    const estado = inputs.estado?.value || 'libre';
    const forma = inputs.forma?.value || 'circular';
    
    // Actualizar clases
    mesaEdit.className = `mesa-edit ${getCapClass(cap)} ${forma} ${estado}`;
    mesaEdit.dataset.cap = cap;
    mesaEdit.dataset.forma = forma;
    
    // Actualizar texto
    const numSpan = mesaEdit.querySelector('.mesa-edit-num');
    const capSpan = mesaEdit.querySelector('.mesa-edit-cap');
    if(numSpan) numSpan.textContent = `#${num}`;
    if(capSpan) capSpan.textContent = `👥${cap}`;
    
    // Accesibilidad y estado
    const isDisabled = estado === 'fuera_servicio';
    mesaEdit.style.pointerEvents = isDisabled ? 'none' : 'auto';
    mesaEdit.style.cursor = isDisabled ? 'not-allowed' : 'grab';
    mesaEdit.setAttribute('aria-disabled', isDisabled);
    
    // Actualizar label ARIA
    mesaEdit.setAttribute('aria-label', `Mesa ${num}, capacidad ${cap} personas, ${estado}, arrastrable`);
}

// Listeners para actualización en tiempo real
Object.values(elements.inputs).forEach(el => {
    if(el) {
        el.addEventListener('input', updateMesaPreview);
        el.addEventListener('change', updateMesaPreview);
    }
});
updateMesaPreview();

// === 🖱️ DRAG & DROP MEJORADO ===
let dragState = {
    isDragging: false,
    isKeyboardMode: false,
    startX: 0,
    startY: 0,
    startTop: 0,
    startLeft: 0,
    originalTop: 0,
    originalLeft: 0
};

function getPointerPos(e) {
    const isTouch = e.type.includes('touch');
    return {
        x: isTouch ? e.touches[0].clientX : e.clientX,
        y: isTouch ? e.touches[0].clientY : e.clientY
    };
}

function snapToGrid(value) {
    return Math.round(value / CONFIG.gridSize) * CONFIG.gridSize;
}

function getMesaDimensions() {
    const { mesaEdit } = elements;
    if(!mesaEdit) return { width: 50, height: 50 };
    const rect = mesaEdit.getBoundingClientRect();
    return { width: rect.width, height: rect.height };
}

function checkCollision(newTop, newLeft, mesaDims) {
    const { canvas, mesaEdit } = elements;
    if(!canvas || !mesaEdit) return false;
    
    const canvasRect = canvas.getBoundingClientRect();
    const existingMesas = canvas.querySelectorAll('.mesa-existente, .mesa-edit:not(#mesaEdit)');
    
    for(const m of existingMesas) {
        const mRect = m.getBoundingClientRect();
        const mTop = parseInt(m.style.top) || 0;
        const mLeft = parseInt(m.style.left) || 0;
        const mWidth = mRect.width;
        const mHeight = mRect.height;
        
        // Margen de colisión (20px)
        const margin = 20;
        
        if(!(newLeft + mesaDims.width + margin < mLeft || 
             newLeft > mLeft + mWidth + margin ||
             newTop + mesaDims.height + margin < mTop || 
             newTop > mTop + mHeight + margin)) {
            return true; // Colisión detectada
        }
    }
    return false;
}

function showCoordError(message) {
    const { coordsError } = elements;
    if(coordsError) {
        coordsError.textContent = message;
        coordsError.classList.add('visible');
        setTimeout(() => coordsError.classList.remove('visible'), 3000);
    }
}

function startDrag(e) {
    const { mesaEdit, canvas } = elements;
    if(!mesaEdit || mesaEdit.classList.contains('fuera_servicio')) return;
    
    // Prevenir si es clic derecho
    if(e.button === 2) return;
    
    e.preventDefault();
    e.stopPropagation();
    
    dragState.isDragging = true;
    dragState.isKeyboardMode = false;
    dragState.originalTop = parseInt(mesaEdit.style.top) || CONFIG.minMargin;
    dragState.originalLeft = parseInt(mesaEdit.style.left) || CONFIG.minMargin;
    
    // Posición inicial
    const currentStyle = window.getComputedStyle(mesaEdit);
    dragState.startTop = parseInt(currentStyle.top) || CONFIG.minMargin;
    dragState.startLeft = parseInt(currentStyle.left) || CONFIG.minMargin;
    
    // Posición del pointer
    const pointer = getPointerPos(e);
    dragState.startX = pointer.x;
    dragState.startY = pointer.y;
    
    // UI feedback
    mesaEdit.classList.add('dragging');
    mesaEdit.setAttribute('aria-grabbed', 'true');
    
    // Listeners globales
    document.addEventListener('mousemove', onDrag, { passive: false });
    document.addEventListener('touchmove', onDrag, { passive: false });
    document.addEventListener('mouseup', endDrag);
    document.addEventListener('touchend', endDrag);
    document.addEventListener('mouseleave', endDrag);
}

function onDrag(e) {
    if(!dragState.isDragging || dragState.isKeyboardMode) return;
    
    e.preventDefault();
    e.stopPropagation();
    
    const { mesaEdit, canvas, inputs, coordDisplay } = elements;
    if(!mesaEdit || !canvas) return;
    
    const pointer = getPointerPos(e);
    const canvasRect = canvas.getBoundingClientRect();
    const mesaDims = getMesaDimensions();
    
    // Calcular nueva posición
    const deltaX = pointer.x - dragState.startX;
    const deltaY = pointer.y - dragState.startY;
    
    let newTop = snapToGrid(dragState.startTop + deltaY);
    let newLeft = snapToGrid(dragState.startLeft + deltaX);
    
    // Límites del canvas
    const maxHeight = canvasRect.height - mesaDims.height - CONFIG.minMargin;
    const maxWidth = canvasRect.width - mesaDims.width - CONFIG.minMargin;
    
    newTop = Math.max(CONFIG.minMargin, Math.min(newTop, maxHeight));
    newLeft = Math.max(CONFIG.minMargin, Math.min(newLeft, maxWidth));
    
    // Verificar colisiones
    if(checkCollision(newTop, newLeft, mesaDims)) {
        showCoordError('⚠️ Posición ocupada por otra mesa');
        return; // No aplicar posición si hay colisión
    }
    
    // Aplicar posición
    mesaEdit.style.top = newTop + 'px';
    mesaEdit.style.left = newLeft + 'px';
    
    // Actualizar inputs y display
    inputs.posTop.value = newTop + 'px';
    inputs.posLeft.value = newLeft + 'px';
    if(coordDisplay) {
        coordDisplay.textContent = `Top: ${newTop}px | Left: ${newLeft}px`;
    }
}

function endDrag(e) {
    if(!dragState.isDragging) return;
    
    const { mesaEdit } = elements;
    
    dragState.isDragging = false;
    mesaEdit?.classList.remove('dragging');
    mesaEdit?.setAttribute('aria-grabbed', 'false');
    
    // Efecto visual de confirmación
    if(mesaEdit) {
        mesaEdit.animate([
            { transform: 'scale(1.15)', boxShadow: '0 0 30px rgba(46, 204, 113, 0.8)' },
            { transform: 'scale(1)', boxShadow: 'none' }
        ], { duration: 300, easing: 'ease-out' });
    }
    
    // Remover listeners
    document.removeEventListener('mousemove', onDrag);
    document.removeEventListener('touchmove', onDrag);
    document.removeEventListener('mouseup', endDrag);
    document.removeEventListener('touchend', endDrag);
    document.removeEventListener('mouseleave', endDrag);
}

// === ⌨️ SOPORTE DE TECLADO ===
function handleKeyboardNav(e) {
    const { mesaEdit, canvas, inputs, coordDisplay } = elements;
    if(!mesaEdit || mesaEdit.classList.contains('fuera_servicio')) return;
    
    // Enter/Space para iniciar modo teclado
    if((e.key === 'Enter' || e.key === ' ') && !dragState.isDragging) {
        e.preventDefault();
        dragState.isDragging = true;
        dragState.isKeyboardMode = true;
        dragState.originalTop = parseInt(mesaEdit.style.top) || CONFIG.minMargin;
        dragState.originalLeft = parseInt(mesaEdit.style.left) || CONFIG.minMargin;
        mesaEdit.classList.add('dragging');
        mesaEdit.setAttribute('aria-grabbed', 'true');
        mesaEdit.focus();
        return;
    }
    
    // Escape para cancelar
    if(e.key === 'Escape' && dragState.isDragging) {
        e.preventDefault();
        mesaEdit.style.top = dragState.originalTop + 'px';
        mesaEdit.style.left = dragState.originalLeft + 'px';
        inputs.posTop.value = dragState.originalTop + 'px';
        inputs.posLeft.value = dragState.originalLeft + 'px';
        endDrag(e);
        return;
    }
    
    // Flechas para mover (solo en modo drag)
    if(!dragState.isDragging) return;
    
    const step = e.shiftKey ? CONFIG.gridSize * 2 : CONFIG.gridSize;
    let currentTop = parseInt(mesaEdit.style.top) || CONFIG.minMargin;
    let currentLeft = parseInt(mesaEdit.style.left) || CONFIG.minMargin;
    const mesaDims = getMesaDimensions();
    const canvasRect = canvas?.getBoundingClientRect();
    
    let newTop = currentTop;
    let newLeft = currentLeft;
    
    switch(e.key) {
        case 'ArrowUp': newTop -= step; break;
        case 'ArrowDown': newTop += step; break;
        case 'ArrowLeft': newLeft -= step; break;
        case 'ArrowRight': newLeft += step; break;
        default: return;
    }
    
    e.preventDefault();
    
    // Snap y límites
    newTop = snapToGrid(newTop);
    newLeft = snapToGrid(newLeft);
    
    if(canvasRect) {
        const maxHeight = canvasRect.height - mesaDims.height - CONFIG.minMargin;
        const maxWidth = canvasRect.width - mesaDims.width - CONFIG.minMargin;
        newTop = Math.max(CONFIG.minMargin, Math.min(newTop, maxHeight));
        newLeft = Math.max(CONFIG.minMargin, Math.min(newLeft, maxWidth));
    }
    
    // Verificar colisiones
    if(checkCollision(newTop, newLeft, mesaDims)) {
        showCoordError('⚠️ Posición ocupada');
        return;
    }
    
    // Aplicar
    mesaEdit.style.top = newTop + 'px';
    mesaEdit.style.left = newLeft + 'px';
    inputs.posTop.value = newTop + 'px';
    inputs.posLeft.value = newLeft + 'px';
    if(coordDisplay) {
        coordDisplay.textContent = `Top: ${newTop}px | Left: ${newLeft}px`;
    }
}

// === 💾 GUARDAR BORRADOR EN LOCALSTORAGE ===
function saveDraft() {
    const { inputs } = elements;
    if(!inputs.posTop || !inputs.posLeft) return;
    
    const draft = {
        top: inputs.posTop.value,
        left: inputs.posLeft.value,
        timestamp: Date.now(),
        formId: elements.mesaEdit?.dataset.id || 'new'
    };
    localStorage.setItem(`mesa_draft_${draft.formId}`, JSON.stringify(draft));
}

function loadDraft() {
    const { mesaEdit, inputs } = elements;
    const formId = mesaEdit?.dataset.id || 'new';
    const draft = localStorage.getItem(`mesa_draft_${formId}`);
    
    if(draft && inputs.posTop && inputs.posLeft) {
        try {
            const data = JSON.parse(draft);
            // Solo cargar si es reciente (< 30 min)
            if(Date.now() - data.timestamp < 1800000) {
                inputs.posTop.value = data.top;
                inputs.posLeft.value = data.left;
                if(mesaEdit) {
                    mesaEdit.style.top = data.top;
                    mesaEdit.style.left = data.left;
                }
                console.log('📋 Borrador cargado');
            }
        } catch(e) {
            console.warn('Error cargando borrador:', e);
        }
    }
}

// === ✅ VALIDACIÓN DE FORMULARIO ===
function validateForm() {
    const { inputs } = elements;
    const errors = [];
    
    const num = parseInt(inputs.numero?.value);
    const cap = parseInt(inputs.capacidad?.value);
    const top = parseInt(inputs.posTop?.value) || 0;
    const left = parseInt(inputs.posLeft?.value) || 0;
    
    if(!num || num < 1 || num > 999) {
        errors.push('Número de mesa debe estar entre 1 y 999');
    }
    if(!cap || cap < 1 || cap > 30) {
        errors.push('Capacidad debe estar entre 1 y 30');
    }
    if(top < CONFIG.minMargin || left < CONFIG.minMargin) {
        errors.push('Posición inválida');
    }
    
    return errors;
}

// === 🎬 INICIALIZACIÓN ===
function init() {
    const { mesaEdit, canvas, form, inputs, btnSubmit } = elements;
    
    // Configurar eventos de drag
    if(mesaEdit) {
        mesaEdit.addEventListener('mousedown', startDrag);
        mesaEdit.addEventListener('touchstart', startDrag, { passive: false });
        mesaEdit.addEventListener('keydown', handleKeyboardNav);
        mesaEdit.addEventListener('blur', () => {
            if(dragState.isDragging && !dragState.isKeyboardMode) {
                endDrag(new Event('blur'));
            }
        });
    }
    
    // Canvas: clic para posicionar nueva mesa (solo si es nueva)
    if(canvas && !elements.mesaEdit?.dataset.id) {
        canvas.addEventListener('click', (e) => {
            if(e.target === canvas && mesaEdit) {
                const rect = canvas.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                let newLeft = snapToGrid(x - mesaEdit.offsetWidth / 2);
                let newTop = snapToGrid(y - mesaEdit.offsetHeight / 2);
                
                // Límites
                newLeft = Math.max(CONFIG.minMargin, Math.min(newLeft, rect.width - mesaEdit.offsetWidth - CONFIG.minMargin));
                newTop = Math.max(CONFIG.minMargin, Math.min(newTop, rect.height - mesaEdit.offsetHeight - CONFIG.minMargin));
                
                if(!checkCollision(newTop, newLeft, getMesaDimensions())) {
                    mesaEdit.style.top = newTop + 'px';
                    mesaEdit.style.left = newLeft + 'px';
                    inputs.posTop.value = newTop + 'px';
                    inputs.posLeft.value = newLeft + 'px';
                    if(elements.coordDisplay) {
                        elements.coordDisplay.textContent = `Top: ${newTop}px | Left: ${newLeft}px`;
                    }
                }
            }
        });
    }
    
    // Submit del formulario
    if(form) {
        form.addEventListener('submit', async (e) => {
            const errors = validateForm();
            if(errors.length > 0) {
                e.preventDefault();
                alert('⚠️ Errores:\n• ' + errors.join('\n• '));
                return false;
            }
            
            // Deshabilitar botón para prevenir doble submit
            if(btnSubmit) {
                btnSubmit.disabled = true;
                btnSubmit.textContent = '⏳ Guardando...';
            }
            
            // Marcar como guardado para beforeunload
            form.dataset.saved = 'true';
            
            // Auto-guardar borrador
            saveDraft();
        });
    }
    
    // Advertencia si hay cambios sin guardar
    window.addEventListener('beforeunload', (e) => {
        const { form, inputs } = elements;
        if(form?.dataset.saved === 'true') return;
        
        const topChanged = inputs.posTop?.value !== inputs.posTop?.defaultValue;
        const leftChanged = inputs.posLeft?.value !== inputs.posLeft?.defaultValue;
        
        if(topChanged || leftChanged) {
            e.preventDefault();
            e.returnValue = '¿Seguro? Hay cambios de posición sin guardar.';
            return '¿Seguro? Hay cambios de posición sin guardar.';
        }
    });
    
    // Cargar borrador si existe
    loadDraft();
    
    // Modo debug en localhost
    if(CONFIG.debug && canvas) {
        canvas.classList.add('debug-mode');
        console.log('🔧 Modo debug activado');
    }
    
    console.log('✅ Croquis inicializado - Mesa editable lista');
}

// === 🛠️ HELPERS ===
function getCapClass(cap) {
    cap = parseInt(cap) || 2;
    if(cap <= 2) return 'cap-1-2';
    if(cap <= 4) return 'cap-3-4';
    if(cap <= 6) return 'cap-5-6';
    return 'cap-7-plus';
}

// Inicializar cuando el DOM esté listo
if(document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}
</script>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
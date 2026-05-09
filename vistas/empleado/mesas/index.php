<?php
// 📍 vistas/empleado/mesas/croquis_empleado.php
// ✅ DISEÑO DEL CLIENTE + FUNCIONALIDAD TRABAJANDO (GET)

require_once __DIR__ . '/../../layout/header.php';
require_once __DIR__ . '/../../layout/sidebar_empleado.php';

if (session_status() === PHP_SESSION_NONE) session_start();

// Variables de filtro
$fecha = isset($_GET['fecha']) ? trim($_GET['fecha']) : date('Y-m-d');
$filtro = isset($_GET['filtro']) ? trim($_GET['filtro']) : 'todas';
$mesas = $mesas ?? [];

// ✅ Helper para estados (mismo que cliente)
function getEstadoInfo($estado) {
    $estados = [
        'libre' => ['clase' => 'libre', 'texto' => 'Disponible', 'icono' => '🟢'],
        'reservada' => ['clase' => 'reservada', 'texto' => 'Reservada', 'icono' => '🟡'],
        'ocupada' => ['clase' => 'ocupada', 'texto' => 'Ocupada', 'icono' => '🔴'],
        'fuera_servicio' => ['clase' => 'fuera_servicio', 'texto' => 'Fuera', 'icono' => '⚫']
    ];
    return $estados[$estado] ?? $estados['libre'];
}

function getCapClass($cap) {
    $cap = (int)$cap;
    if($cap <= 2) return 'cap-1-2';
    if($cap <= 4) return 'cap-3-4';
    if($cap <= 6) return 'cap-5-6';
    return 'cap-7-plus';
}
?>

<style>
/* ✅ CSS IDÉNTICO AL CLIENTE - CROQUIS PROFESIONAL */
:root {
    --croquis-height: 1100px;
    --croquis-height-mobile: 800px;
    --croquis-height-small: 600px;
    --grid-size: 25px;
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

.page {
    padding: 20px;
    background: #f4f6fb;
    min-height: 80vh;
}

.croquis-container {
    max-width: 900px;
    margin: 0 auto;
}

/* Card de filtros */
.card-filtros {
    max-width: 650px;
    margin: 0 auto 25px;
    background: white;
    padding: 25px;
    border-radius: 14px;
    box-shadow: var(--shadow-card);
    text-align: center;
}
.card-filtros h4 {
    margin: 0 0 8px;
    color: #2c3e50;
    font-size: 18px;
}
.card-filtros p { 
    color: #7f8c8d; 
    margin-bottom: 20px; 
    font-size: 14px; 
}
.card-filtros .form-label {
    font-size: 12px;
    font-weight: 600;
    color: #555;
    margin-bottom: 4px;
    text-align: left;
    display: block;
}
.card-filtros .form-control,
.card-filtros .form-select {
    font-size: 14px;
    padding: 8px 12px;
    border-radius: 8px;
    border: 1.5px solid #dee2e6;
}
.card-filtros .form-control:focus,
.card-filtros .form-select:focus {
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(74,105,189,0.1);
}
.card-filtros .btn-primary {
    background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
    border: none;
    padding: 10px;
    font-weight: 600;
    border-radius: 8px;
    transition: var(--transition-fast);
}
.card-filtros .btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(74,105,189,.35);
}

/* Wrapper del croquis */
.croquis-wrapper {
    position: relative;
    width: 100%;
    max-width: 100%;
    margin: 0 auto;
    background: white;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0,0,0,.08);
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
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 8px;
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
    border: 1px solid #dee2e6;
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

/* Canvas del croquis */
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
    cursor: default;
    box-shadow: inset 0 0 20px rgba(74,105,189,.08);
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
.croquis-piscina::after { 
    content: "🏊"; 
    font-size: 20px; 
    margin-right: 2px; 
}

.croquis-barra {
    position: absolute;
    bottom: 25px;
    left: 25px;
    width: 110px;
    height: 36px;
    background: linear-gradient(135deg, #6c757d, #495057);
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
.croquis-barra::before { 
    content: "🍸"; 
    margin-right: 4px; 
}

/* ✅ Tarjetas de mesa - ESTILO CLIENTE */
.mesa-card {
    position: absolute;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    font-weight: 600;
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
    cursor: pointer;
}

.mesa-card:hover {
    transform: scale(1.15) translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,.25);
    z-index: 20;
}

.mesa-card.fuera_servicio {
    cursor: not-allowed;
    opacity: 0.7;
}

/* Tamaños por capacidad */
.mesa-card.cap-1-2 { width: 48px; height: 48px; font-size: 10px; }
.mesa-card.cap-3-4 { width: 58px; height: 58px; font-size: 11px; }
.mesa-card.cap-5-6 { width: 68px; height: 68px; font-size: 12px; }
.mesa-card.cap-7-plus { width: 78px; height: 78px; font-size: 13px; }

.mesa-card.rectangular { border-radius: 12px; }

/* Estados - MISMOS COLORES QUE CLIENTE */
.mesa-card.libre { 
    background: linear-gradient(135deg, var(--color-success), #27ae60); 
    color: white; 
}
.mesa-card.reservada { 
    background: linear-gradient(135deg, var(--color-warning), #f39c12); 
    color: #333; 
}
.mesa-card.ocupada { 
    background: linear-gradient(135deg, var(--color-danger), #c0392b); 
    color: white; 
}
.mesa-card.fuera_servicio { 
    background: var(--color-gray); 
    color: white; 
    opacity: 0.7; 
    border-style: dashed;
}

/* Elementos internos */
.mesa-numero { 
    font-size: 1.1em; 
    font-weight: 700; 
    line-height: 1; 
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}
.mesa-cap { 
    font-size: 0.85em; 
    opacity: .95; 
    margin-top: 2px; 
}
.mesa-estado { 
    font-size: 0.75em; 
    opacity: .9; 
    margin-top: 2px; 
    text-transform: uppercase;
    font-weight: 600;
}

/* ✅ PANEL DE BOTONES - Solo visible al hover (para empleado) */
.mesa-actions {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0);
    display: flex;
    flex-direction: column;
    gap: 4px;
    background: rgba(255,255,255,0.95);
    padding: 8px;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    z-index: 30;
    transition: transform 0.15s ease;
    min-width: 90px;
}

.mesa-card:hover .mesa-actions {
    transform: translate(-50%, -50%) scale(1);
}

.mesa-actions .btn-estado {
    font-size: 10px;
    padding: 4px 8px;
    border-radius: 6px;
    text-decoration: none;
    color: white;
    font-weight: 500;
    transition: var(--transition-fast);
    white-space: nowrap;
}

.mesa-actions .btn-estado:hover {
    filter: brightness(0.95);
    transform: scale(1.05);
}

.mesa-actions .btn-libre { background: #27ae60; }
.mesa-actions .btn-reservada { background: #d4ac0d; color: #333; }
.mesa-actions .btn-ocupada { background: #c0392b; }
.mesa-actions .btn-fuera { background: #495057; }

/* Leyenda */
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
.leyenda-dot.libre { background: var(--color-success); border-color: #27ae60; }
.leyenda-dot.reservada { background: var(--color-warning); border-color: #f39c12; }
.leyenda-dot.ocupada { background: var(--color-danger); border-color: #c0392b; }
.leyenda-dot.fuera { background: var(--color-gray); opacity: 0.7; }

/* Toast */
#toast {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 2000;
    display: none;
    animation: slideIn .3s ease;
}
@keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}
#toast .toast-content {
    background: white;
    padding: 12px 20px;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0,0,0,.15);
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
}

/* Accesibilidad */
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

/* ✅ RESPONSIVE - IGUAL QUE CLIENTE */
@media (max-width: 1100px) {
    .croquis-canvas { height: var(--croquis-height-mobile); }
}
@media (max-width: 768px) {
    .croquis-canvas { height: var(--croquis-height-small); }
    .croquis-header { flex-direction: column; align-items: flex-start; gap: 12px; }
    .croquis-controls { width: 100%; justify-content: center; }
    .card-filtros { padding: 20px; margin-bottom: 20px; }
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
    .mesa-actions { transform: translate(-50%, -50%) scale(0.9); }
    .mesa-card:hover .mesa-actions { transform: translate(-50%, -50%) scale(0.9); }
}
@media (min-width: 1600px) {
    :root { --croquis-height: 1300px; }
}
</style>

<div class="page">

<!-- Card de filtros -->
<div class="card-filtros" role="search">
    <h4>🔧 Gestionar Mesas</h4>
    <p>Visualiza el croquis y cambia el estado de las mesas.</p>

    <form method="GET" action="" id="formFiltros">
        <div class="row g-2">
            <div class="col-6">
                <label class="form-label" for="fechaInput">📅 Fecha</label>
                <input type="date" 
                       name="fecha" 
                       id="fechaInput"
                       value="<?= htmlspecialchars($fecha, ENT_QUOTES, 'UTF-8') ?>" 
                       class="form-control form-control-sm">
            </div>
            <div class="col-6">
                <label class="form-label" for="filtroInput">🔎 Estado</label>
                <select name="filtro" id="filtroInput" class="form-select form-select-sm">
                    <option value="todas" <?= $filtro=="todas"?'selected':'' ?>>Todas</option>
                    <option value="libre" <?= $filtro=="libre"?'selected':'' ?>>🟢 Disponibles</option>
                    <option value="reservada" <?= $filtro=="reservada"?'selected':'' ?>>🟡 Reservadas</option>
                    <option value="ocupada" <?= $filtro=="ocupada"?'selected':'' ?>>🔴 Ocupadas</option>
                    <option value="fuera_servicio" <?= $filtro=="fuera_servicio"?'selected':'' ?>>⚫ Fuera</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100 mt-3 btn-sm">🔄 Actualizar</button>
    </form>
</div>

<!-- Leyenda -->
<div class="leyenda" role="list">
    <div class="leyenda-item" role="listitem">
        <span class="leyenda-dot libre"></span> <span>Disponible</span>
    </div>
    <div class="leyenda-item" role="listitem">
        <span class="leyenda-dot reservada"></span> <span>Reservada</span>
    </div>
    <div class="leyenda-item" role="listitem">
        <span class="leyenda-dot ocupada"></span> <span>Ocupada</span>
    </div>
    <div class="leyenda-item" role="listitem">
        <span class="leyenda-dot fuera"></span> <span>Fuera</span>
    </div>
</div>

<!-- ✅ CROQUIS CON DISEÑO DEL CLIENTE -->
<div class="croquis-container">
    <div class="croquis-wrapper">
        
        <div class="croquis-header">
            <h4>🗺️ Croquis - Gestión</h4>
            <div class="croquis-controls" role="group" aria-label="Controles de zoom">
                <button class="btn-zoom" id="btnZoomIn" title="Acercar">+</button>
                <span class="zoom-level" id="zoomLevel">100%</span>
                <button class="btn-zoom" id="btnZoomOut" title="Alejar">−</button>
                <button class="btn-zoom" id="btnZoomReset" title="Reset" style="font-size:12px">⟲</button>
            </div>
        </div>

        <div class="croquis-canvas" id="croquisCanvas" tabindex="-1">
            
            <div class="croquis-piscina">PISCINA</div>
            <div class="croquis-barra">BARRA</div>

            <?php 
            // Aplicar filtros
            $mesasFiltradas = $mesas;
            if($filtro !== 'todas' && !empty($filtro)) {
                $mesasFiltradas = array_filter($mesasFiltradas, fn($m) => ($m['estado'] ?? '') === $filtro);
            }
            ?>

            <?php foreach($mesasFiltradas as $mesa): 
                $estadoInfo = getEstadoInfo($mesa['estado'] ?? 'libre');
                $capClass = getCapClass($mesa['capacidad'] ?? 2);
                
                // Posiciones (mismo formato que cliente)
                $pos_top = (int)rtrim($mesa['pos_top'] ?? '250', 'px');
                $pos_left = (int)rtrim($mesa['pos_left'] ?? '250', 'px');
                if($pos_top < 15) $pos_top = 250;
                if($pos_left < 15) $pos_left = 250;
                
                $tipo_forma = htmlspecialchars($mesa['tipo_forma'] ?? 'circular', ENT_QUOTES, 'UTF-8');
                $zona = htmlspecialchars($mesa['zona'] ?? '', ENT_QUOTES, 'UTF-8');
                $numero = (int)($mesa['numero_mesa'] ?? 0);
                $capacidad = (int)($mesa['capacidad'] ?? 2);
                $id = (int)($mesa['id'] ?? 0);
            ?>
            
            <!-- ✅ MESA CON DISEÑO CLIENTE + BOTONES EMPLEADO -->
            <div class="mesa-card <?= $estadoInfo['clase'] ?> <?= $capClass ?> <?= $tipo_forma ?>"
                 style="top: <?= $pos_top ?>px; left: <?= $pos_left ?>px;"
                 title="<?= $estadoInfo['icono'] ?> <?= $estadoInfo['texto'] ?> | Cap: <?= $capacidad ?> | Zona: <?= $zona ?>"
                 role="button"
                 tabindex="0">
                
                <!-- Info visible siempre -->
                <span class="mesa-numero">#<?= $numero ?></span>
                <span class="mesa-cap">👥<?= $capacidad ?></span>
                <span class="mesa-estado"><?= strtoupper(substr($estadoInfo['texto'],0,3)) ?></span>
                
                <!-- ✅ BOTONES DE GESTIÓN - Solo al hover (funcionalidad GET que ya te funciona) -->
                <div class="mesa-actions">
                    <a class="btn-estado btn-libre" 
                       href="/clientes/controladores/empleado/MesasEmpleadoController.php?accion=estado&id=<?= $id ?>&nuevo=libre"
                       title="Marcar como disponible">🟢 Libre</a>
                    
                    <a class="btn-estado btn-reservada" 
                       href="/clientes/controladores/empleado/MesasEmpleadoController.php?accion=estado&id=<?= $id ?>&nuevo=reservada"
                       title="Marcar como reservada">🟡 Reservar</a>
                    
                    <a class="btn-estado btn-ocupada" 
                       href="/clientes/controladores/empleado/MesasEmpleadoController.php?accion=estado&id=<?= $id ?>&nuevo=ocupada"
                       title="Marcar como ocupada">🔴 Ocupada</a>
                    
                    <a class="btn-estado btn-fuera" 
                       href="/clientes/controladores/empleado/MesasEmpleadoController.php?accion=estado&id=<?= $id ?>&nuevo=fuera_servicio"
                       title="Marcar como fuera">⚫ Fuera</a>
                </div>
                
            </div>
            
            <?php endforeach; ?>
            
            <?php if(empty($mesasFiltradas)): ?>
                <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;color:#7f8c8d;">
                    <p>🔍 No hay mesas con este filtro</p>
                    <button onclick="document.getElementById('filtroInput').value='todas';document.getElementById('formFiltros').submit();" 
                            style="margin-top:10px;padding:8px 16px;background:var(--color-primary);color:white;border:none;border-radius:8px;cursor:pointer;">
                        Mostrar todas
                    </button>
                </div>
            <?php endif; ?>
            
        </div>

        <div class="leyenda">
            <small style="color:#6c757d">
                💡 <strong>Uso:</strong> Pasa el mouse sobre una mesa para ver opciones • 
                <kbd>Ctrl</kbd>+<kbd>+</kbd>/<kbd>-</kbd> para zoom
            </small>
        </div>

    </div>
</div>

</div>

<!-- Toast de notificación -->
<div id="toast">
    <div class="toast-content">
        <span id="toastIcon"></span>
        <span id="toastMessage"></span>
    </div>
</div>

<script>
// ✅ ZOOM - MISMO CÓDIGO QUE CLIENTE
const CONFIG = { zoomMin: 0.5, zoomMax: 2, zoomStep: 0.1 };
const state = { zoom: 1 };
const els = {
    canvas: document.getElementById('croquisCanvas'),
    zoomLevel: document.getElementById('zoomLevel'),
    btnZoomIn: document.getElementById('btnZoomIn'),
    btnZoomOut: document.getElementById('btnZoomOut'),
    btnZoomReset: document.getElementById('btnZoomReset'),
    toast: document.getElementById('toast')
};

function updateZoom() {
    if(els.canvas) els.canvas.style.transform = `scale(${state.zoom})`;
    if(els.zoomLevel) els.zoomLevel.textContent = Math.round(state.zoom * 100) + '%';
    if(els.btnZoomIn) els.btnZoomIn.disabled = state.zoom >= CONFIG.zoomMax;
    if(els.btnZoomOut) els.btnZoomOut.disabled = state.zoom <= CONFIG.zoomMin;
}

function zoomIn() { if(state.zoom < CONFIG.zoomMax) { state.zoom = Math.min(state.zoom + CONFIG.zoomStep, CONFIG.zoomMax); updateZoom(); } }
function zoomOut() { if(state.zoom > CONFIG.zoomMin) { state.zoom = Math.max(state.zoom - CONFIG.zoomStep, CONFIG.zoomMin); updateZoom(); } }
function resetZoom() { state.zoom = 1; updateZoom(); }

// Toast notification
function showToast(mensaje, tipo = 'success') {
    if(!els.toast) return;
    document.getElementById('toastIcon').textContent = tipo === 'error' ? '⚠️' : '✓';
    document.getElementById('toastMessage').textContent = mensaje;
    els.toast.style.display = 'block';
    setTimeout(() => { els.toast.style.display = 'none'; }, 3000);
}

// Atajos de teclado
document.addEventListener('keydown', (e) => {
    if(e.ctrlKey && !e.target.matches('input, textarea, select')) {
        switch(e.key) {
            case '+': case '=': e.preventDefault(); zoomIn(); break;
            case '-': e.preventDefault(); zoomOut(); break;
            case '0': e.preventDefault(); resetZoom(); break;
        }
    }
});

// Wheel zoom con Ctrl
els.canvas?.addEventListener('wheel', (e) => {
    if(e.ctrlKey) {
        e.preventDefault();
        e.deltaY < 0 ? zoomIn() : zoomOut();
    }
}, { passive: false });

// Click en botones de zoom
els.btnZoomIn?.addEventListener('click', zoomIn);
els.btnZoomOut?.addEventListener('click', zoomOut);
els.btnZoomReset?.addEventListener('click', resetZoom);

// Feedback al cargar
document.addEventListener('DOMContentLoaded', () => {
    updateZoom();
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.has('mensaje')) showToast('✓ ' + decodeURIComponent(urlParams.get('mensaje')), 'success');
    if(urlParams.has('error')) showToast('⚠️ ' + decodeURIComponent(urlParams.get('error')), 'error');
});
</script>

<?php require_once __DIR__.'/../../layout/footer.php'; ?>
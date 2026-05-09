<?php
require_once __DIR__ . '/../../layout/header.php';
require_once __DIR__ . '/../../layout/sidebar_empleado.php';

// 🔒 Sanitización del buscador único
$search = trim($_GET['q'] ?? '');
$search = htmlspecialchars($search, ENT_QUOTES, 'UTF-8');

$pagos = $pagos ?? [];

// 🔍 Filtrar pagos en PHP (si los datos ya vienen cargados)
// ⚡ Si tienes muchos registros, este filtrado debería hacerse en SQL (ver nota al final)
if ($search !== '') {
    $pagos = array_filter($pagos, function($p) use ($search) {
        $searchLower = strtolower($search);
        $idBusqueda = (string)(int)$search; // Para buscar por ID numérico
        
        // Buscar por: ID de pago, ID de reserva, o nombre de cliente
        return 
            (string)$p['id'] === $idBusqueda ||                    // Exact match ID pago
            (string)$p['reserva_id'] === $idBusqueda ||            // Exact match ID reserva
            stripos($p['cliente'] ?? '', $searchLower) !== false;  // Contiene en nombre cliente
    });
}

// 🔧 Función helper para badges
function renderBadge(string $estado): string {
    $estado = strtoupper($estado);
    $badges = [
        'COMPLETED' => '<span class="badge badge-success"><i class="fas fa-check-circle"></i> Completado</span>',
        'PENDING'   => '<span class="badge badge-pending"><i class="fas fa-clock"></i> Pendiente</span>',
        'FAILED'    => '<span class="badge badge-failed"><i class="fas fa-times-circle"></i> Fallido</span>'
    ];
    return $badges[$estado] ?? '<span class="badge">Desconocido</span>';
}

// 🔧 Función helper para formato de moneda
function formatMoney(float $amount): string {
    return '$' . number_format($amount, 2, '.', ',');
}

// 🔧 Resaltar texto encontrado (opcional, para UX)
function highlightMatch(string $text, string $search): string {
    if (empty($search)) return htmlspecialchars($text);
    $pattern = '/(' . preg_quote($search, '/') . ')/i';
    return preg_replace($pattern, '<mark class="highlight">$1</mark>', htmlspecialchars($text));
}
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
/* ===== LAYOUT ===== */
.page-container { padding: 25px 35px; }
.main-content {
    background: #fff; padding: 30px; border-radius: 18px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

/* ===== HEADER ===== */
.page-title {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 25px; flex-wrap: wrap; gap: 10px;
}
.page-title h2 { font-weight: 600; display: flex; align-items: center; gap: 10px; }

/* ===== BUSCADOR ===== */
.search-box {
    background: linear-gradient(135deg, #f7f8fc 0%, #eef1f8 100%);
    padding: 20px; border-radius: 14px; margin-bottom: 25px;
    border: 1px solid #e0e0e0;
}
.search-wrapper {
    display: flex; gap: 10px; align-items: center; flex-wrap: wrap;
}
.search-input-group {
    flex: 1; min-width: 250px; position: relative;
}
.search-input-group i {
    position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
    color: #6c757d; pointer-events: none;
}
.search-input {
    width: 100%; padding: 12px 12px 12px 42px; border-radius: 10px;
    border: 2px solid #ddd; font-size: 15px; transition: all 0.2s;
    box-sizing: border-box;
}
.search-input:focus {
    outline: none; border-color: #4a69bd; 
    box-shadow: 0 0 0 4px rgba(74,105,189,0.15);
}
.search-input::placeholder { color: #adb5bd; }

.btn-search {
    background: #4a69bd; color: white; border: none; padding: 12px 24px;
    border-radius: 10px; font-weight: 600; cursor: pointer;
    display: inline-flex; align-items: center; gap: 8px;
    transition: background 0.2s, transform 0.1s; white-space: nowrap;
}
.btn-search:hover { background: #1e3799; }
.btn-search:active { transform: scale(0.98); }

.btn-clear {
    background: #6c757d; color: white; padding: 12px 18px;
    border-radius: 10px; text-decoration: none; font-weight: 600;
    display: inline-flex; align-items: center; gap: 6px;
    transition: background 0.2s;
}
.btn-clear:hover { background: #545b62; }

.search-hint {
    font-size: 13px; color: #6c757d; margin-top: 8px;
    display: flex; align-items: center; gap: 5px;
}
.search-hint kbd {
    background: #e9ecef; padding: 2px 6px; border-radius: 4px;
    font-family: monospace; font-size: 12px; border: 1px solid #dee2e6;
}

/* ===== RESULTADOS ===== */
.result-header {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid #f0f0f0;
}
.result-count {
    font-weight: 600; color: #333; display: flex; align-items: center; gap: 8px;
}
.result-count i { color: #4a69bd; }
.search-term {
    background: #e7f1ff; color: #1e3799; padding: 3px 10px;
    border-radius: 6px; font-weight: 600; font-size: 14px;
}

/* ===== TABLA ===== */
.table-container { overflow-x: auto; border-radius: 12px; border: 1px solid #eee; }
.table-modern { width: 100%; border-collapse: collapse; background: #fff; }
.table-modern th {
    background: #4a69bd; color: white; padding: 14px 12px;
    text-align: left; font-weight: 600; font-size: 14px;
    position: sticky; top: 0; z-index: 1; cursor: pointer;
    user-select: none;
}
.table-modern th:hover { background: #3c5aa6; }
.table-modern td {
    padding: 12px; border-bottom: 1px solid #f0f0f0; font-size: 14px;
}
.table-modern tbody tr:hover { background: #f8f9ff; transition: background 0.15s; }
.table-modern tbody tr:last-child td { border-bottom: none; }

/* ===== BADGES ===== */
.badge {
    padding: 5px 12px; border-radius: 20px; font-size: 12px;
    font-weight: 600; display: inline-flex; align-items: center; gap: 5px;
}
.badge-success { background: #d4edda; color: #155724; }
.badge-pending { background: #fff3cd; color: #856404; }
.badge-failed { background: #f8d7da; color: #721c24; }

/* ===== HIGHLIGHT ===== */
mark.highlight {
    background: #fff3cd; color: #856404; padding: 0 2px; border-radius: 3px;
    font-weight: 600;
}

/* ===== UTILIDADES ===== */
.text-muted { color: #6c757d; font-size: 14px; }
.text-right { text-align: right; }
.font-weight-bold { font-weight: 700; }
.empty-state { text-align: center; padding: 40px 20px; color: #6c757d; }
.empty-state i { font-size: 48px; margin-bottom: 15px; opacity: 0.5; }
.empty-state .search-suggestion { 
    margin-top: 15px; font-size: 14px; 
}

/* ===== LOADING ===== */
.loading { opacity: 0.7; pointer-events: none; position: relative; }
.loading::after {
    content: ""; position: absolute; top: 50%; left: 50%;
    width: 24px; height: 24px; margin: -12px 0 0 -12px;
    border: 3px solid #e0e0e0; border-top-color: #4a69bd;
    border-radius: 50%; animation: spin 0.8s linear infinite;
    z-index: 10;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .page-container { padding: 15px; }
    .main-content { padding: 20px; }
    .search-wrapper { flex-direction: column; align-items: stretch; }
    .btn-search, .btn-clear { width: 100%; justify-content: center; }
    .table-modern th, .table-modern td { padding: 10px 8px; font-size: 13px; }
    .result-header { flex-direction: column; align-items: flex-start; gap: 10px; }
}
</style>

<div class="page-container">
<main class="main-content">

    <!-- TÍTULO -->
    <div class="page-title">
        <h2><i class="fab fa-paypal"></i> Pagos PayPal</h2>
    </div>

    <!-- 🔍 BUSCADOR ÚNICO -->
    <div class="search-box">
        <form method="GET" id="searchForm">
            <div class="search-wrapper">
                
                <div class="search-input-group">
                    <i class="fas fa-search"></i>
                    <input type="search" name="q" class="search-input" 
                           id="searchInput"
                           placeholder="Buscar por ID de reserva o nombre del cliente..." 
                           value="<?= $search ?>"
                           autocomplete="off" autofocus>
                </div>
                
                <button type="submit" class="btn-search">
                    <i class="fas fa-search"></i> Buscar
                </button>
                
                <?php if ($search): ?>
                <a href="?" class="btn-clear">
                    <i class="fas fa-times"></i> Limpiar
                </a>
                <?php endif; ?>
                
            </div>
            
            <div class="search-hint">
                <i class="fas fa-lightbulb"></i>
                Ejemplo: escribe <kbd>123</kbd> para buscar la reserva #123, 
                o <kbd>María</kbd> para buscar clientes con ese nombre
            </div>
        </form>
    </div>

    <!-- RESULTADOS -->
    <?php if ($search): ?>
    <div class="result-header">
        <div class="result-count">
            <i class="fas fa-filter"></i> 
            Resultados para: <span class="search-term"><?= htmlspecialchars($search) ?></span>
        </div>
        <span class="text-muted">
            <?= count($pagos) ?> encontrado<?= count($pagos) !== 1 ? 's' : '' ?>
        </span>
    </div>
    <?php else: ?>
    <div class="result-count">
        <i class="fas fa-list"></i> 
        Total: <strong><?= count($pagos) ?></strong> pagos registrados
    </div>
    <?php endif; ?>

    <!-- TABLA DE PAGOS -->
    <div class="table-container" id="resultsTable">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>ID <i class="fas fa-sort"></i></th>
                    <th>Reserva <i class="fas fa-sort"></i></th>
                    <th>Cliente <i class="fas fa-sort"></i></th>
                    <th>Mesa</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th class="text-right">Monto</th>
                    <th>Moneda</th>
                    <th>Estado</th>
                    <th>Fecha pago</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pagos)): ?>
                    <tr>
                        <td colspan="10">
                            <div class="empty-state">
                                <i class="fas fa-search d-block"></i>
                                <strong>
                                    <?= $search ? 'No se encontraron resultados' : 'No hay pagos registrados' ?>
                                </strong>
                                <?php if ($search): ?>
                                <p class="search-suggestion text-muted">
                                    💡 Prueba con otro ID de reserva o verifica el nombre del cliente
                                </p>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($pagos as $p): ?>
                    <tr>
                        <td class="font-weight-bold">#<?= (int)$p['id'] ?></td>
                        <td>
                            <strong>#<?= (int)$p['reserva_id'] ?></strong>
                            <?php if ($search && (string)(int)$search === (string)$p['reserva_id']): ?>
                                <i class="fas fa-bullseye text-muted ms-1" title="Coincide con tu búsqueda"></i>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= $search ? highlightMatch($p['cliente'] ?? 'N/A', $search) : htmlspecialchars($p['cliente'] ?? 'N/A') ?>
                        </td>
                        <td>Mesa <?= (int)($p['numero_mesa'] ?? 0) ?></td>
                        <td><?= htmlspecialchars($p['fecha'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($p['hora'] ?? '-') ?></td>
                        <td class="text-right font-weight-bold">
                            <?= formatMoney((float)($p['monto'] ?? 0)) ?>
                        </td>
                        <td><?= htmlspecialchars($p['moneda'] ?? 'USD') ?></td>
                        <td><?= renderBadge($p['estado'] ?? '') ?></td>
                        <td><?= htmlspecialchars($p['fecha_pago'] ?? '-') ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</main>
</div>

<!-- 🔄 Scripts para mejorar UX -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('searchForm');
    const input = document.getElementById('searchInput');
    const tableContainer = document.getElementById('resultsTable');
    
    // Efecto visual de carga al buscar
    form.addEventListener('submit', function(e) {
        const query = input.value.trim();
        
        // Validación mínima
        if (query.length > 0 && query.length < 2) {
            e.preventDefault();
            input.focus();
            input.style.borderColor = '#e74c3c';
            setTimeout(() => input.style.borderColor = '#ddd', 2000);
            return;
        }
        
        // Feedback visual
        tableContainer.classList.add('loading');
        setTimeout(() => tableContainer.classList.remove('loading'), 400);
    });

    // Búsqueda en tiempo real con debounce (opcional - descomentar para activar)
    /*
    let debounceTimer;
    input.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            if (this.value.length >= 2 || this.value === '') {
                form.requestSubmit();
            }
        }, 400);
    });
    */

    // Permitir limpiar con tecla ESC
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            window.location.href = '?';
        }
    });

    // Focus automático si no hay búsqueda previa
    if (!input.value) {
        input.focus();
    }
});
</script>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
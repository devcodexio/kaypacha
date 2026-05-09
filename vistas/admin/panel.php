<?php
// vistas/admin/panel.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    header("Location: /clientes/vistas/auth/login.php");
    exit;
}

require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/../layout/headerr.php';
require_once __DIR__ . '/../layout/sidebar_admin.php';

$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

$totales = ["usuarios" => 0, "mesas" => 0, "platos" => 0, "reservas" => 0, "pagos" => 0];
$totales['usuarios'] = $conexion->query("SELECT COUNT(*) FROM usuarios")->fetch_row()[0] ?? 0;
$totales['mesas'] = $conexion->query("SELECT COUNT(*) FROM mesas")->fetch_row()[0] ?? 0;
$totales['platos'] = $conexion->query("SELECT COUNT(*) FROM platos")->fetch_row()[0] ?? 0;
$totales['reservas'] = $conexion->query("SELECT COUNT(*) FROM reservas")->fetch_row()[0] ?? 0;
$totales['pagos'] = $conexion->query("SELECT COUNT(*) FROM pagos_stripe")->fetch_row()[0] ?? 0;

$reservas_mes = array_fill(1, 12, 0);
$sql = "SELECT MONTH(fecha) mes, COUNT(*) total FROM reservas WHERE YEAR(fecha) = ? GROUP BY MONTH(fecha)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $year);
$stmt->execute();
$result = $stmt->get_result();
while($row = $result->fetch_assoc()){ $reservas_mes[(int)$row['mes']] = (int)$row['total']; }
?>

<div class="content-wrapper">
    <!-- 🔹 HEADER PRO -->
    <div class="row align-items-center mb-5">
        <div class="col-md-7">
            <h6 class="text-indigo fw-800 text-uppercase mb-2" style="letter-spacing: 3px; font-size: 0.75rem;">Intelligence Center</h6>
            <h1 class="fw-800 text-slate-900 display-4 mb-0">Analytics Dashboard</h1>
        </div>
        <div class="col-md-5 text-md-end mt-4 mt-md-0">
            <form method="GET" class="d-inline-flex align-items-center bg-white p-2 rounded-20 shadow-sm border border-light">
                <span class="px-3 fw-800 text-muted small text-uppercase">Periodo</span>
                <select name="year" onchange="this.form.submit()" class="form-select border-0 fw-800 text-indigo" style="width: 120px; cursor: pointer;">
                    <?php for ($y = date('Y'); $y >= 2020; $y--): ?>
                        <option value="<?= $y ?>" <?= $year == $y ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </form>
        </div>
    </div>

    <!-- 🔹 KEY METRICS (PRO CARDS) -->
    <div class="row g-4 mb-5">
        <div class="col-xl-3 col-md-6">
            <div class="metric-card-pro">
                <div class="d-flex justify-content-between mb-4">
                    <div class="icon-box-pro bg-indigo-subtle"><i class="fas fa-users text-indigo"></i></div>
                    <span class="trend-badge pos">+12%</span>
                </div>
                <h3 class="fw-800 text-slate-900 display-6 mb-1"><?= $totales['usuarios'] ?></h3>
                <span class="text-muted fw-700 text-uppercase small" style="letter-spacing: 1px;">Total Usuarios</span>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="metric-card-pro">
                <div class="d-flex justify-content-between mb-4">
                    <div class="icon-box-pro bg-success-subtle"><i class="fas fa-calendar-check text-success"></i></div>
                    <span class="trend-badge pos">+5%</span>
                </div>
                <h3 class="fw-800 text-slate-900 display-6 mb-1"><?= $totales['reservas'] ?></h3>
                <span class="text-muted fw-700 text-uppercase small" style="letter-spacing: 1px;">Reservas Activas</span>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="metric-card-pro">
                <div class="d-flex justify-content-between mb-4">
                    <div class="icon-box-pro bg-warning-subtle"><i class="fas fa-utensils text-warning"></i></div>
                    <span class="trend-badge">Steady</span>
                </div>
                <h3 class="fw-800 text-slate-900 display-6 mb-1"><?= $totales['platos'] ?></h3>
                <span class="text-muted fw-700 text-uppercase small" style="letter-spacing: 1px;">Platos en Menú</span>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="metric-card-pro shadow-indigo">
                <div class="d-flex justify-content-between mb-4">
                    <div class="icon-box-pro bg-primary-subtle"><i class="fas fa-wallet text-primary"></i></div>
                    <span class="trend-badge pos">+24%</span>
                </div>
                <h3 class="fw-800 text-slate-900 display-6 mb-1"><?= $totales['pagos'] ?></h3>
                <span class="text-muted fw-700 text-uppercase small" style="letter-spacing: 1px;">Transacciones</span>
            </div>
        </div>
    </div>

    <!-- 🔹 DATA VISUALIZATION PRO -->
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-pro h-100">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <div>
                        <h4 class="fw-800 text-slate-900 mb-1">Crecimiento de Reservas</h4>
                        <p class="text-muted small mb-0">Visualización de la tendencia operativa mensual</p>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-light fw-800 border-0 active px-3">Mensual</button>
                        <button class="btn btn-sm btn-light fw-800 border-0 px-3">Anual</button>
                    </div>
                </div>
                <div style="height: 350px;">
                    <canvas id="reservasChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-pro mb-4">
                <h4 class="fw-800 text-slate-900 mb-4">Distribución</h4>
                <div style="height: 250px;">
                    <canvas id="sistemaChart"></canvas>
                </div>
            </div>
            <div class="card-pro" style="background: var(--slate-900);">
                <h4 class="fw-800 text-white mb-4">Quick Insights</h4>
                <div class="insight-item mb-3">
                    <span class="text-white-50 small d-block mb-1">Tasa de Conversión</span>
                    <div class="progress bg-white-10 rounded-pill" style="height: 8px;">
                        <div class="progress-bar bg-indigo rounded-pill" style="width: 75%;"></div>
                    </div>
                </div>
                <div class="insight-item">
                    <span class="text-white-50 small d-block mb-1">Ocupación Promedio</span>
                    <div class="progress bg-white-10 rounded-pill" style="height: 8px;">
                        <div class="progress-bar bg-success rounded-pill" style="width: 62%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .metric-card-pro {
        background: #fff;
        padding: 35px;
        border-radius: 32px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        transition: all 0.3s ease;
    }
    .metric-card-pro:hover { transform: translateY(-5px); box-shadow: 0 15px 40px rgba(0,0,0,0.05); }
    .shadow-indigo { box-shadow: 0 15px 35px rgba(99, 102, 241, 0.1); }

    .icon-box-pro {
        width: 56px; height: 56px;
        border-radius: 18px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
    }

    .trend-badge {
        font-size: 0.7rem; font-weight: 800;
        padding: 4px 12px; border-radius: 99px;
        background: #f1f5f9; color: #64748b;
        height: fit-content;
    }
    .trend-badge.pos { background: #ecfdf5; color: #10b981; }

    .bg-indigo-subtle { background: #eef2ff; }
    .bg-success-subtle { background: #ecfdf5; }
    .bg-warning-subtle { background: #fffbeb; }
    .bg-primary-subtle { background: #f0f9ff; }
    .bg-white-10 { background: rgba(255,255,255,0.1); }

    .rounded-20 { border-radius: 20px; }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    Chart.defaults.font.family = "'Outfit', sans-serif";
    Chart.defaults.color = '#94a3b8';

    const ctxRes = document.getElementById('reservasChart').getContext('2d');
    const grad = ctxRes.createLinearGradient(0,0,0,400);
    grad.addColorStop(0, 'rgba(99, 102, 241, 0.3)');
    grad.addColorStop(1, 'rgba(99, 102, 241, 0)');

    new Chart(ctxRes, {
        type: 'line',
        data: {
            labels: ['ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SEP','OCT','NOV','DIC'],
            datasets: [{
                data: [<?= implode(',', $reservas_mes) ?>],
                borderColor: '#6366f1',
                borderWidth: 5,
                backgroundColor: grad,
                fill: true,
                tension: 0.45,
                pointRadius: 6,
                pointBackgroundColor: '#fff',
                pointBorderWidth: 4,
                pointBorderColor: '#6366f1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: '#f1f5f9', drawBorder: false }, ticks: { padding: 10, font: { weight: '800' } }, beginAtZero: true },
                x: { grid: { display: false }, ticks: { padding: 10, font: { weight: '800' } } }
            }
        }
    });

    const ctxSis = document.getElementById('sistemaChart').getContext('2d');
    new Chart(ctxSis, {
        type: 'doughnut',
        data: {
            labels: ['Usuarios','Mesas','Platos','Reservas','Pagos'],
            datasets: [{
                data: [<?= $totales['usuarios'] ?>, <?= $totales['mesas'] ?>, <?= $totales['platos'] ?>, <?= $totales['reservas'] ?>, <?= $totales['pagos'] ?>],
                backgroundColor: ['#6366f1','#10b981','#f59e0b','#ef4444','#0ea5e9'],
                borderWidth: 8,
                borderColor: '#fff',
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 25, font: { weight: '800' } } } },
            cutout: '75%'
        }
    });
});
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
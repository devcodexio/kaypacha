<?php
// vistas/cliente/panel.php
require_once __DIR__ . '/../layout/headerr.php';
require_once __DIR__ . '/../layout/sidebar_cliente.php';

$reservasRecientes = $reservasRecientes ?? [];
$totales = $totales ?? [
    'reservas_totales'     => 0,
    'reservas_pendientes'  => 0,
    'reservas_confirmadas' => 0,
    'reservas_canceladas'  => 0,
];
?>

<div class="content-wrapper">
    <div class="container-fluid py-4">
        
        <!-- 🔹 BIENVENIDA PREMIUM -->
        <div class="welcome-banner mb-5">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-5 fw-800 text-white mb-2">¡Hola, <?= htmlspecialchars($_SESSION['nombre'] ?? 'Cliente') ?>!</h1>
                    <p class="text-white-50 fs-5 mb-0">Bienvenido a tu centro de control. Aquí tienes un resumen de tu actividad.</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="current-date-badge">
                        <i class="far fa-calendar-alt me-2"></i>
                        <?= date('d M, Y') ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- 🔹 TARJETAS DE ESTADÍSTICAS MODERNAS -->
        <div class="row g-4 mb-5">
            <!-- Total -->
            <div class="col-xl-3 col-sm-6">
                <div class="glass-stat-card">
                    <div class="stat-icon-wrapper bg-indigo">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Total Reservas</span>
                        <h2 class="stat-value"><?= $totales['reservas_totales'] ?></h2>
                    </div>
                    <div class="stat-progress">
                        <div class="progress-bar bg-indigo" style="width: 100%"></div>
                    </div>
                </div>
            </div>
            <!-- Confirmadas -->
            <div class="col-xl-3 col-sm-6">
                <div class="glass-stat-card">
                    <div class="stat-icon-wrapper bg-success">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Confirmadas</span>
                        <h2 class="stat-value"><?= $totales['reservas_confirmadas'] ?></h2>
                    </div>
                    <?php 
                        $pctConf = $totales['reservas_totales'] > 0 ? ($totales['reservas_confirmadas'] / $totales['reservas_totales']) * 100 : 0;
                    ?>
                    <div class="stat-progress">
                        <div class="progress-bar bg-success" style="width: <?= $pctConf ?>%"></div>
                    </div>
                </div>
            </div>
            <!-- Pendientes -->
            <div class="col-xl-3 col-sm-6">
                <div class="glass-stat-card">
                    <div class="stat-icon-wrapper bg-warning">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">En Espera</span>
                        <h2 class="stat-value"><?= $totales['reservas_pendientes'] ?></h2>
                    </div>
                    <?php 
                        $pctPend = $totales['reservas_totales'] > 0 ? ($totales['reservas_pendientes'] / $totales['reservas_totales']) * 100 : 0;
                    ?>
                    <div class="stat-progress">
                        <div class="progress-bar bg-warning" style="width: <?= $pctPend ?>%"></div>
                    </div>
                </div>
            </div>
            <!-- Canceladas -->
            <div class="col-xl-3 col-sm-6">
                <div class="glass-stat-card">
                    <div class="stat-icon-wrapper bg-danger">
                        <i class="fas fa-ban"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Canceladas</span>
                        <h2 class="stat-value"><?= $totales['reservas_canceladas'] ?></h2>
                    </div>
                    <?php 
                        $pctCan = $totales['reservas_totales'] > 0 ? ($totales['reservas_canceladas'] / $totales['reservas_totales']) * 100 : 0;
                    ?>
                    <div class="stat-progress">
                        <div class="progress-bar bg-danger" style="width: <?= $pctCan ?>%"></div>
                    </div>
                </div>
            </div>
               <!-- 🔹 GRÁFICOS Y ACCIONES -->
        <div class="row g-4 mb-5">
            <div class="col-lg-8">
                <div class="card-premium h-100">
                    <div class="card-premium-header">
                        <h4 class="fw-800 text-dark mb-0">Análisis de Tendencias</h4>
                        <div class="badge bg-light text-dark rounded-pill px-3">En vivo</div>
                    </div>
                    <div class="card-premium-body">
                        <canvas id="reservasTrendChart" height="350"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card-premium mb-4">
                    <div class="card-premium-header">
                        <h4 class="fw-800 text-dark mb-0">Acciones Rápidas</h4>
                    </div>
                    <div class="card-premium-body">
                        <div class="d-grid gap-3">
                            <a href="/clientes/controladores/cliente/ReservasClienteController.php?accion=crear" class="btn-action-premium primary">
                                <div class="icon"><i class="fas fa-plus"></i></div>
                                <div class="text">
                                    <span class="title">Nueva Reserva</span>
                                    <span class="desc">Separa tu mesa en segundos</span>
                                </div>
                            </a>
                            <a href="/clientes/controladores/cliente/PerfilClienteController.php?accion=ver" class="btn-action-premium secondary">
                                <div class="icon"><i class="fas fa-user-cog"></i></div>
                                <div class="text">
                                    <span class="title">Configurar Perfil</span>
                                    <span class="desc">Gestiona tus datos personales</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- 🔹 DEJAR TESTIMONIO -->
                <div class="card-premium">
                    <div class="card-premium-header">
                        <h4 class="fw-800 text-dark mb-0">Tu Experiencia</h4>
                    </div>
                    <div class="card-premium-body">
                        <form action="/clientes/controladores/cliente/TestimoniosClienteController.php?accion=guardar" method="POST">
                            <div class="mb-3">
                                <label class="fw-700 text-muted small text-uppercase mb-2">Calificación</label>
                                <div class="star-rating">
                                    <input type="radio" name="calificacion" value="5" id="5"><label for="5">☆</label>
                                    <input type="radio" name="calificacion" value="4" id="4"><label for="4">☆</label>
                                    <input type="radio" name="calificacion" value="3" id="3"><label for="3">☆</label>
                                    <input type="radio" name="calificacion" value="2" id="2"><label for="2">☆</label>
                                    <input type="radio" name="calificacion" value="1" id="1" required><label for="1">☆</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <textarea name="mensaje" class="form-control border-0 bg-light rounded-16 p-3 small fw-600" rows="3" placeholder="Cuéntanos qué te pareció Kay-Pacha..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-indigo w-100 py-3 rounded-16 fw-800">
                                Enviar Comentario
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-indigo { background: var(--indigo); color: white; border: none; transition: 0.3s; }
    .btn-indigo:hover { background: var(--dark); transform: translateY(-2px); box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2); }

    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        font-size: 1.8rem;
    }
    .star-rating input { display: none; }
    .star-rating label { color: #cbd5e1; cursor: pointer; transition: 0.3s; }
    .star-rating label:hover,
    .star-rating label:hover ~ label,
    .star-rating input:checked ~ label { color: #f59e0b; }

    /* VARIABLES ADICIONALES */
 ADICIONALES */
    :root {
        --indigo: #6366f1;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --dark: #0f172a;
    }

    .fw-800 { font-weight: 800; }
    .rounded-24 { border-radius: 24px; }

    /* BANNER DE BIENVENIDA */
    .welcome-banner {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        padding: 40px;
        border-radius: 32px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
    }

    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -50px; right: -50px;
        width: 200px; height: 200px;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 50%;
    }

    .current-date-badge {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        color: white;
        padding: 10px 20px;
        border-radius: 99px;
        display: inline-flex;
        align-items: center;
        font-weight: 600;
        font-size: 0.9rem;
    }

    /* ESTADÍSTICAS GLASS */
    .glass-stat-card {
        background: white;
        border-radius: 28px;
        padding: 25px;
        display: flex;
        align-items: center;
        gap: 20px;
        border: 1px solid #f1f5f9;
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(0,0,0,0.02);
        position: relative;
    }

    .glass-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.05);
    }

    .stat-icon-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .bg-indigo { background: var(--indigo); }
    .bg-success { background: var(--success); }
    .bg-warning { background: var(--warning); }
    .bg-danger { background: var(--danger); }

    .stat-label {
        display: block;
        font-size: 0.85rem;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--dark);
        margin: 0;
    }

    .stat-progress {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 4px;
        background: #f1f5f9;
        border-radius: 0 0 28px 28px;
        overflow: hidden;
    }

    .progress-bar { height: 100%; }

    /* CARD PREMIUM */
    .card-premium {
        background: white;
        border-radius: 32px;
        padding: 30px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    }

    .card-premium-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    /* BOTONES ACCIÓN PREMIUM */
    .btn-action-premium {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 20px;
        border-radius: 24px;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 1px solid #f1f5f9;
    }

    .btn-action-premium .icon {
        width: 50px; height: 50px;
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
    }

    .btn-action-premium.primary { background: #f5f3ff; }
    .btn-action-premium.primary .icon { background: var(--indigo); color: white; }
    .btn-action-premium.primary .title { color: var(--indigo); }

    .btn-action-premium.secondary { background: #f8fafc; }
    .btn-action-premium.secondary .icon { background: var(--dark); color: white; }
    .btn-action-premium.secondary .title { color: var(--dark); }

    .btn-action-premium .title { display: block; font-weight: 700; font-size: 1.05rem; }
    .btn-action-premium .desc { display: block; font-size: 0.85rem; color: #64748b; }

    .btn-action-premium:hover {
        transform: scale(1.02);
        box-shadow: 0 10px 20px rgba(0,0,0,0.04);
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // TENDENCIA
    const ctxTrend = document.getElementById('reservasTrendChart').getContext('2d');
    const grad = ctxTrend.createLinearGradient(0, 0, 0, 400);
    grad.addColorStop(0, 'rgba(99, 102, 241, 0.15)');
    grad.addColorStop(1, 'rgba(99, 102, 241, 0)');

    new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
            datasets: [{
                data: [4, 7, 5, 12, 8, 15],
                borderColor: '#6366f1',
                borderWidth: 4,
                backgroundColor: grad,
                fill: true,
                tension: 0.4,
                pointRadius: 0,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { display: false },
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Outfit', size: 12 }, color: '#94a3b8' }
                }
            }
        }
    });

    // DISTRIBUCIÓN
    const ctxStatus = document.getElementById('reservasStatusChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Confirmadas', 'Pendientes', 'Canceladas'],
            datasets: [{
                data: [
                    <?= (int)$totales['reservas_confirmadas'] ?>, 
                    <?= (int)$totales['reservas_pendientes'] ?>, 
                    <?= (int)$totales['reservas_canceladas'] ?>
                ],
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                borderWidth: 0,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            cutout: '80%'
        }
    });
});
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
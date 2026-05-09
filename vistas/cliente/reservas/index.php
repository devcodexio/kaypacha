<?php
// vistas/cliente/reservas/index.php
require_once __DIR__ . '/../../layout/headerr.php';

// ya no usamos sidebar_cliente.php porque el menú está en el logo
require_once __DIR__ . '/../../layout/sidebar_cliente.php';
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap');

    :root {
        --primary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        --danger-gradient: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
        --success-gradient: linear-gradient(135deg, #10b981 0%, #34d399 100%);
        --warning-gradient: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
        --glass-bg: rgba(255, 255, 255, 0.9);
        --glass-border: rgba(255, 255, 255, 0.4);
    }

    body {
        font-family: 'Outfit', sans-serif !important;
        background: #f1f5f9;
        color: #1e293b;
    }

    .page-container {
        padding: 60px 20px;
        min-height: 100vh;
    }

    .reservas-header {
        margin-bottom: 50px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .header-title h2 {
        font-weight: 800;
        letter-spacing: -0.5px;
        margin-bottom: 10px;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-size: 2.5rem;
    }

    .header-title p {
        color: #64748b;
        font-size: 1.1rem;
        margin: 0;
    }

    .btn-nueva-reserva {
        background: var(--primary-gradient);
        color: white !important;
        padding: 16px 32px;
        border-radius: 20px;
        font-weight: 700;
        border: none;
        box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 12px;
    }

    .btn-nueva-reserva:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 15px 30px rgba(99, 102, 241, 0.4);
    }

    .reservas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 30px;
    }

    .reserva-card {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        border: 1px solid var(--glass-border);
        border-radius: 30px;
        padding: 30px;
        transition: all 0.4s ease;
        position: relative;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
    }

    .reserva-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.08);
        border-color: rgba(99, 102, 241, 0.3);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .reserva-id {
        font-weight: 800;
        color: #0f172a;
        font-size: 1.3rem;
    }

    .estado-badge {
        padding: 8px 16px;
        border-radius: 14px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .estado-pendiente { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
    .estado-confirmado { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
    .estado-cancelado { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
    .estado-pagado { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }

    .reserva-info {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-bottom: 30px;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 15px;
        color: #334155;
        font-size: 1.05rem;
    }

    .info-item i {
        width: 32px;
        height: 32px;
        background: #f1f5f9;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6366f1;
        font-size: 0.9rem;
    }

    .card-actions {
        display: flex;
        gap: 12px;
        margin-top: 20px;
    }

    .btn-action {
        flex: 1;
        padding: 12px;
        border-radius: 15px;
        font-weight: 700;
        text-decoration: none;
        text-align: center;
        transition: all 0.3s;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-cancelar {
        background: #fff;
        color: #ef4444;
        border: 2px solid #fee2e2;
    }

    .btn-cancelar:hover {
        background: #fef2f2;
        border-color: #f87171;
        color: #b91c1c;
    }

    .btn-pagar {
        background: var(--primary-gradient);
        color: white !important;
        box-shadow: 0 5px 15px rgba(99, 102, 241, 0.2);
    }

    .btn-pagar:hover {
        box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
        transform: scale(1.02);
    }

    .empty-state {
        text-align: center;
        padding: 80px 40px;
        background: white;
        border-radius: 40px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.02);
    }

    .empty-state i {
        font-size: 5rem;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 30px;
        opacity: 0.8;
    }

    @media (max-width: 768px) {
        .header-title h2 { font-size: 2rem; }
        .reservas-grid { grid-template-columns: 1fr; }
    }
</style>
<div class="content-wrapper">
    <div class="container-fluid">
        
        <div class="reservas-header">
            <div class="header-title">
                <h2>Mis Reservas</h2>
                <p>Gestiona tus próximas experiencias gastronómicas</p>
            </div>

            <a class="btn-nueva-reserva"
               href="/clientes/controladores/cliente/ReservasClienteController.php?accion=crear">
                <i class="fas fa-plus"></i> Nueva Reserva
            </a>
        </div>

        <?php if (!empty($reservas)): ?>
            <div class="reservas-grid">
                <?php foreach ($reservas as $r): ?>
                    <?php
                        $estado = $r['estado'];
                        $claseEstado = 'estado-badge ';
                        if ($estado === 'pendiente')        $claseEstado .= 'estado-pendiente';
                        elseif ($estado === 'confirmado')  $claseEstado .= 'estado-confirmado';
                        elseif ($estado === 'cancelado')   $claseEstado .= 'estado-cancelado';
                        else $claseEstado .= 'estado-pagado';
                    ?>
                    <div class="reserva-card">
                        <div class="card-header">
                            <span class="reserva-id">#<?= (int)$r['id'] ?></span>
                            <span class="<?= $claseEstado ?>"><?= htmlspecialchars($estado) ?></span>
                        </div>

                        <div class="reserva-info">
                            <div class="info-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span><?= date('d M, Y', strtotime($r['fecha'])) ?></span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-clock"></i>
                                <span><?= htmlspecialchars($r['hora']) ?></span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-users"></i>
                                <span><?= (int)$r['cantidad_personas'] ?> Personas</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-couch"></i>
                                <span>Mesa <?= htmlspecialchars($r['numero_mesa']) ?></span>
                            </div>
                        </div>

                        <div class="card-actions">
                            <?php if ($r['estado'] === 'pendiente'): ?>
                                <a class="btn-action btn-pagar"
                                   href="/clientes/controladores/cliente/StripeClienteController.php?accion=pagar&reserva_id=<?= $r['id'] ?>">
                                    <i class="fas fa-wallet"></i> Pagar
                                </a>
                                
                                <a class="btn-action btn-cancelar"
                                   href="/clientes/controladores/cliente/ReservasClienteController.php?accion=cancelar&id=<?= $r['id'] ?>"
                                   onclick="return confirm('¿Estás seguro de cancelar esta reserva?');">
                                    <i class="fas fa-trash-alt"></i> Cancelar
                                </a>
                            <?php else: ?>
                                <div class="text-center w-100 py-2">
                                    <span class="text-muted fw-bold small">
                                        <i class="fas fa-info-circle me-1"></i> Reserva <?= htmlspecialchars($estado) ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-utensils"></i>
                <h3>¿Aún no has reservado?</h3>
                <p class="text-muted">Te estamos esperando con los mejores platos.</p>
                <a href="/clientes/controladores/cliente/ReservasClienteController.php?accion=crear" class="btn-nueva-reserva mt-4">Hacer mi reserva ahora</a>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php
require_once __DIR__ . '/../../layout/footer.php';
?>
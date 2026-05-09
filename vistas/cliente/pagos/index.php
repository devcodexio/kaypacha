<?php
// vistas/cliente/pagos/index.php
require_once __DIR__ . '/../../layout/headerr.php';
require_once __DIR__ . '/../../layout/sidebar_cliente.php';

$pagos = $pagos ?? [];
?>

<div class="content-wrapper">
    <div class="container-fluid">

        <!-- HEADER SECCIÓN -->
        <div class="d-flex justify-content-between align-items-end mb-5 flex-wrap gap-4">
            <div class="pagos-intro">
                <h1 class="display-5 fw-800 text-dark mb-2">Historial de Pagos</h1>
                <p class="text-secondary fs-5 mb-0">Gestiona tus transacciones y comprobantes de pago.</p>
            </div>
            <a href="/clientes/controladores/cliente/ReservasClienteController.php?accion=index" 
               class="btn btn-light rounded-16 px-4 fw-600 border shadow-sm">
                <i class="fas fa-arrow-left me-2"></i> Volver a Reservas
            </a>
        </div>

        <?php if (!empty($pagos)): ?>
            <div class="row g-4">
                <?php foreach ($pagos as $p): ?>
                    <?php
                        $estado = strtolower($p['estado_reserva'] ?? '');
                        $badgeClass = 'bg-secondary';
                        $statusIcon = 'fa-info-circle';
                        
                        if ($estado === 'pendiente') {
                            $badgeClass = 'bg-warning-subtle text-warning border-warning-subtle';
                            $statusIcon = 'fa-clock';
                        } elseif ($estado === 'pagado_yape' || $estado === 'confirmado') {
                            $badgeClass = 'bg-success-subtle text-success border-success-subtle';
                            $statusIcon = 'fa-check-circle';
                        } elseif ($estado === 'cancelado') {
                            $badgeClass = 'bg-danger-subtle text-danger border-danger-subtle';
                            $statusIcon = 'fa-times-circle';
                        }
                    ?>
                    <div class="col-xl-4 col-md-6">
                        <div class="payment-card shadow-sm border-0 rounded-24 overflow-hidden">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-4">
                                    <div class="payment-id-tag">
                                        <span class="text-muted small fw-700 ls-1">TRANSMISIÓN (<?= htmlspecialchars($p['metodo'] ?? 'Desconocido') ?>)</span>
                                        <h4 class="fw-800 mb-0">#<?= (int)$p['id'] ?></h4>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge <?= $badgeClass ?> border px-3 py-2 rounded-pill fw-600 d-block mb-2">
                                            <i class="fas <?= $statusIcon ?> me-1"></i> <?= strtoupper($estado) ?>
                                        </span>
                                        <?php if (($p['metodo'] ?? '') === 'Stripe'): ?>
                                            <span class="badge bg-primary border-0 px-3 py-1 rounded-pill fw-700" style="font-size: 0.65rem;">
                                                <i class="fab fa-stripe me-1"></i> STRIPE
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-purple-yape border-0 px-3 py-1 rounded-pill fw-700 text-white" style="font-size: 0.65rem; background: #742284;">
                                                <i class="fas fa-mobile-alt me-1"></i> YAPE
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="payment-details mb-4">
                                    <div class="detail-row d-flex justify-content-between mb-2">
                                        <span class="text-secondary">Reserva Vinculada</span>
                                        <span class="fw-700 text-dark">#<?= (int)$p['reserva_id'] ?></span>
                                    </div>
                                    <div class="detail-row d-flex justify-content-between mb-2">
                                        <span class="text-secondary">Mesa Asignada</span>
                                        <span class="fw-700 text-dark">Mesa <?= htmlspecialchars($p['numero_mesa']) ?></span>
                                    </div>
                                    <div class="detail-row d-flex justify-content-between mb-2">
                                        <span class="text-secondary">Fecha y Hora</span>
                                        <span class="fw-600"><?= date('d/m/Y', strtotime($p['fecha'])) ?> - <?= htmlspecialchars($p['hora']) ?></span>
                                    </div>
                                </div>

                                <div class="payment-amount-box bg-light rounded-20 p-3 mb-4 d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="amount-icon bg-white shadow-sm rounded-12">
                                            <i class="fas fa-coins text-warning"></i>
                                        </div>
                                        <span class="fw-600 text-secondary">Monto Pagado</span>
                                    </div>
                                    <h3 class="fw-800 text-dark mb-0">S/ <?= number_format((float)$p['monto'], 2) ?></h3>
                                </div>

                                <div class="payment-actions">
                                    <?php if (($p['metodo'] ?? '') === 'Stripe'): ?>
                                        <div class="bg-primary-subtle text-primary p-3 rounded-16 text-center fw-700">
                                            <i class="fas fa-check-circle me-1"></i> Verificado por Stripe
                                        </div>
                                    <?php elseif (!empty($p['imagen'])): ?>
                                        <a href="/clientes/uploads/<?= htmlspecialchars($p['imagen']) ?>" 
                                           target="_blank" 
                                           class="btn btn-primary w-100 rounded-16 py-3 fw-700 shadow-custom">
                                            <i class="fas fa-file-invoice me-2"></i> Ver Comprobante
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-secondary w-100 rounded-16 py-3 fw-700 disabled opacity-50">
                                            <i class="fas fa-eye-slash me-2"></i> Sin Comprobante
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-payments-state text-center py-5 bg-white rounded-30 shadow-sm border">
                <div class="mb-4">
                    <div class="empty-icon-circle mx-auto bg-light d-flex align-items-center justify-content-center">
                        <i class="fas fa-wallet fa-4x text-muted opacity-20"></i>
                    </div>
                </div>
                <h2 class="fw-800 text-dark">No hay pagos registrados</h2>
                <p class="text-secondary fs-5 mb-5">Tus transacciones de Yape o Stripe aparecerán aquí una vez completadas.</p>
                <a href="/clientes/controladores/cliente/ReservasClienteController.php?accion=index" class="btn btn-primary rounded-pill px-5 py-3 fw-700 shadow-custom">
                    Realizar un Pago
                </a>
            </div>
        <?php endif; ?>

    </div>
</div>

<style>
    .rounded-30 { border-radius: 30px; }
    .rounded-24 { border-radius: 24px; }
    .rounded-20 { border-radius: 20px; }
    .rounded-16 { border-radius: 16px; }
    .rounded-12 { border-radius: 12px; }
    .fw-600 { font-weight: 600; }
    .fw-700 { font-weight: 700; }
    .fw-800 { font-weight: 800; }
    .ls-1 { letter-spacing: 1px; }

    .payment-card {
        background: #fff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #f1f5f9 !important;
    }

    .payment-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.08) !important;
        border-color: #6366f1 !important;
    }

    .bg-warning-subtle { background: rgba(245, 158, 11, 0.1); }
    .bg-success-subtle { background: rgba(34, 197, 94, 0.1); }
    .bg-danger-subtle { background: rgba(239, 68, 68, 0.1); }

    .amount-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    .shadow-custom {
        box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2);
    }

    .empty-icon-circle {
        width: 150px;
        height: 150px;
        border-radius: 50%;
    }

    .opacity-20 { opacity: 0.2; }

    @media (max-width: 768px) {
        .display-5 { font-size: 2.2rem; }
    }
</style>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>

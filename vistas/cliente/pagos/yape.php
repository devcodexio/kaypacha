<?php
require_once __DIR__ . '/../../layout/headerr.php';
require_once __DIR__ . '/../../layout/sidebar_cliente.php';

?>

<style>
    .page-container {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 25px;
        background: #f4f4f8;
        min-height: calc(100vh - 56px);
    }

    .section-box {
        background: #ffffff;
        border-radius: 18px;
        padding: 25px 28px;
        box-shadow: 0 12px 30px rgba(0,0,0,0.10);
        width: 100%;
        max-width: 520px;
        text-align: center;
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .section-box h2 {
        margin-top: 0;
        font-size: 26px;
        font-weight: 700;
        color: #333;
    }

    .yape-img {
        width: 220px;
        height: auto;
        border-radius: 14px;
        margin-bottom: 8px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }

    .numero-yape {
        font-size: 22px;
        font-weight: 700;
        color: #ff7f50;
        margin-bottom: 18px;
    }

    .resumen-reserva {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 12px;
        font-size: 14px;
        margin-bottom: 22px;
        text-align: left;
    }

    .btn-primario {
        background: #ff7f50;
        border-color: #ff7f50;
        border-radius: 999px;
        font-weight: 600;
        padding: 10px 24px;
        font-size: 15px;
        transition: 0.25s;
    }

    .btn-primario:hover {
        background: #ff965f;
        border-color: #ff965f;
        transform: scale(1.05);
        box-shadow: 0 6px 18px rgba(0,0,0,0.15);
    }

    .btn-volver {
        border-radius: 999px;
        padding: 10px 24px;
        font-weight: 600;
    }
</style>

<div class="page-container">
    <div class="section-box">

        <!-- Imagen Yape -->
        <img src="/clientes/img/yape.png" class="yape-img" alt="Yape">

        <!-- Número grande -->
        <h2>Numero Yape <span> juan sulca</span> </h2>
        <div class="numero-yape">994265896</div>

        <h2>Pago por Yape</h2>
        <p class="text-muted">
            Sube tu comprobante de pago para confirmar la reserva.
        </p>

        <div class="resumen-reserva">
            <div><strong>Reserva #<?= (int)$reserva['id'] ?></strong></div>
            <div>Mesa: <strong><?= htmlspecialchars($reserva['numero_mesa']) ?></strong></div>
            <div>Fecha: <strong><?= htmlspecialchars($reserva['fecha']) ?></strong></div>
            <div>Hora: <strong><?= htmlspecialchars($reserva['hora']) ?></strong></div>
        </div>

        <form action="/clientes/controladores/cliente/PagosYapeClienteController.php?accion=guardar"
              method="POST" enctype="multipart/form-data" class="row g-3">

            <input type="hidden" name="reserva_id" value="<?= (int)$reserva['id'] ?>">

            <!-- Monto -->
            <div class="col-12">
                <label for="monto" class="form-label">Monto pagado (S/)</label>
                <input type="number" step="0.10" min="0" name="monto" id="monto"
                       class="form-control" required>
            </div>

            <!-- Archivo -->
            <div class="col-12">
                <label for="imagen" class="form-label">Comprobante</label>
                <input type="file" name="imagen" id="imagen" class="form-control"
                       accept="image/*" required>
            </div>

            <div class="col-12 d-flex justify-content-between mt-3">
                <a href="/clientes/controladores/cliente/ReservasClienteController.php?accion=index"
                   class="btn btn-outline-secondary btn-volver">
                    ⬅ Volver
                </a>

                <button type="submit" class="btn btn-primario">
                    Enviar pago
                </button>
            </div>

        </form>
    </div>
</div>

<?php
require_once __DIR__ . '/../../layout/footer.php';
?>

<?php
if (!isset($reserva) || !is_array($reserva)) {
    header("Location: /clientes/controladores/cliente/ReservasClienteController.php?accion=index");
    exit;
}

$reserva_id = (int) $reserva['id'];
$monto = (float) ($monto ?? 10.00);
$moneda = 'USD';
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Pago PayPal</title>

<script src="https://www.paypal.com/sdk/js?client-id=<?= $_ENV['PAYPAL_CLIENT_ID'] ?? '' ?>&currency=USD"></script>

<style>
body{
    margin:0;
    font-family: Arial, sans-serif;
    background:#f5f7fa;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.container{
    background:#fff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 10px 25px rgba(0,0,0,0.1);
    text-align:center;
    width:350px;
}

h2{
    margin-bottom:10px;
}

.monto{
    font-size:18px;
    margin-bottom:20px;
    color:#333;
}

#success{
    display:none;
    margin-top:20px;
    padding:15px;
    background:#e6ffed;
    color:#1a7f37;
    border-radius:8px;
    font-weight:bold;
}

#loading{
    display:none;
    margin-top:15px;
    color:#555;
}
</style>
</head>

<body>

<div class="container">

    <h2>Pago reserva #<?= $reserva_id ?></h2>
    <div class="monto">Total: $<?= number_format($monto,2) ?> USD</div>

    <div id="paypal-button-container"></div>

    <div id="loading">⏳ Procesando pago...</div>
    <div id="success">✅ Pago completado correctamente</div>

</div>

<script>
const RESERVA_ID = <?= json_encode($reserva_id) ?>;
const MONTO = <?= json_encode($monto) ?>;

paypal.Buttons({

    createOrder: function(data, actions) {
        return actions.order.create({
            purchase_units: [{
                amount: {
                    value: MONTO.toFixed(2)
                }
            }]
        });
    },

    onApprove: async function(data) {

        document.getElementById('loading').style.display = 'block';

        try {

            // Capturar pago
            const res = await fetch('/clientes/controladores/cliente/PaypalClienteController.php?accion=capturar', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `orderID=${data.orderID}`
            });

            const details = await res.json();

            const captureID = details.purchase_units?.[0]?.payments?.captures?.[0]?.id;

            // Guardar en BD
            await fetch('/clientes/controladores/cliente/PaypalClienteController.php?accion=guardar', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `reserva_id=${RESERVA_ID}&paypal_order_id=${data.orderID}&paypal_capture_id=${captureID}&monto=${MONTO}&estado=COMPLETED`
            });

            document.getElementById('loading').style.display = 'none';
            document.getElementById('success').style.display = 'block';

            setTimeout(() => {
                window.location.href = "/clientes/controladores/cliente/ReservasClienteController.php?accion=index";
            }, 2500);

        } catch (error) {
            console.error(error);
            alert("❌ Error al procesar el pago");
        }
    }

}).render('#paypal-button-container');
</script>

</body>
</html>
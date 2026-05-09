<?php
// vistas/cliente/pagos/stripe.php
if (!isset($reserva) || !is_array($reserva)) {
    header("Location: /clientes/controladores/cliente/ReservasClienteController.php?accion=index");
    exit;
}

$reserva_id = (int) $reserva['id'];
$monto = (float) ($monto ?? 10.00);
$stripe_public_key = $_ENV['STRIPE_PUBLIC_KEY'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Seguro | Stripe</title>
    <script src="https://js.stripe.com/v3/"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #0f172a;
            --bg-page: #f8fafc;
            --glass: rgba(255, 255, 255, 0.95);
            --border: #e2e8f0;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg-page);
            background-image: radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0, transparent 50%), 
                              radial-gradient(at 50% 0%, rgba(168, 85, 247, 0.1) 0, transparent 50%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .checkout-container {
            width: 100%;
            max-width: 480px;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .payment-card {
            background: var(--glass);
            backdrop-filter: blur(10px);
            border-radius: 32px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            border: 1px solid white;
        }

        .brand-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .stripe-logo {
            font-size: 2.5rem;
            color: #635bff;
            margin-bottom: 15px;
        }

        .brand-header h2 {
            font-weight: 800;
            color: var(--secondary);
            font-size: 1.75rem;
            margin-bottom: 5px;
        }

        .reserva-badge {
            display: inline-block;
            background: #f1f5f9;
            color: #475569;
            padding: 6px 16px;
            border-radius: 99px;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .order-summary {
            background: #fff;
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 30px;
            border: 1px solid var(--border);
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 0.95rem;
            color: #64748b;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px dashed var(--border);
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--secondary);
        }

        #payment-form {
            text-align: left;
        }

        .form-label {
            display: block;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            margin-bottom: 12px;
        }

        #card-element {
            padding: 18px;
            border: 2px solid var(--border);
            border-radius: 16px;
            background: white;
            transition: all 0.3s ease;
            margin-bottom: 10px;
        }

        #card-element--focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        #card-errors {
            color: #ef4444;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 20px;
            min-height: 20px;
        }

        .submit-btn {
            background: linear-gradient(135deg, var(--primary) 0%, #a855f7 100%);
            color: white;
            border: none;
            padding: 20px;
            border-radius: 18px;
            font-size: 1.1rem;
            font-weight: 700;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(99, 102, 241, 0.4);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .submit-btn:disabled {
            background: #cbd5e1;
            box-shadow: none;
            cursor: not-allowed;
        }

        #spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 0.8s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .trust-badges {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            opacity: 0.6;
        }

        .trust-badges i {
            font-size: 1.5rem;
            color: #94a3b8;
        }

        .security-note {
            margin-top: 20px;
            text-align: center;
            font-size: 0.8rem;
            color: #94a3b8;
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="checkout-container">
    <div class="payment-card">
        <div class="brand-header">
            <div class="stripe-logo"><i class="fa-brands fa-stripe"></i></div>
            <h2>Checkout Seguro</h2>
            <div class="reserva-badge">ID RESERVA: #<?= $reserva_id ?></div>
        </div>

        <div class="order-summary">
            <div class="summary-item">
                <span>Reserva de Mesa</span>
                <span>$<?= number_format($monto, 2) ?></span>
            </div>
            <div class="summary-item">
                <span>Impuestos (Incl.)</span>
                <span>$0.00</span>
            </div>
            <div class="summary-total">
                <span>Total a Pagar</span>
                <span>$<?= number_format($monto, 2) ?> USD</span>
            </div>
        </div>

        <form id="payment-form">
            <label class="form-label">Información de Tarjeta</label>
            <div id="card-element">
                <!-- Stripe Element -->
            </div>
            <div id="card-errors" role="alert"></div>

            <button id="submit-button" class="submit-btn">
                <div id="spinner"></div>
                <span id="button-text">Confirmar Pago</span>
            </button>
        </form>

        <div class="security-note">
            <i class="fas fa-lock me-1"></i> Encriptación SSL de 256 bits. Tus datos están seguros.
        </div>

        <div class="trust-badges">
            <i class="fa-brands fa-cc-visa"></i>
            <i class="fa-brands fa-cc-mastercard"></i>
            <i class="fa-brands fa-cc-amex"></i>
            <i class="fa-brands fa-cc-apple-pay"></i>
        </div>
    </div>
</div>

<script>
    const stripe = Stripe('<?= $stripe_public_key ?>');
    const elements = stripe.elements();

    const style = {
        base: {
            color: "#0f172a",
            fontFamily: "'Outfit', sans-serif",
            fontSmoothing: "antialiased",
            fontSize: "17px",
            "::placeholder": {
                color: "#94a3b8"
            }
        },
        invalid: {
            color: "#ef4444",
            iconColor: "#ef4444"
        }
    };

    const card = elements.create("card", { 
        style: style,
        hidePostalCode: true
    });
    card.mount("#card-element");

    const form = document.getElementById('payment-form');
    const submitBtn = document.getElementById('submit-button');
    const spinner = document.getElementById('spinner');
    const buttonText = document.getElementById('button-text');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        setLoading(true);

        try {
            const response = await fetch('/clientes/controladores/cliente/StripeClienteController.php?accion=crear_intent', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `reserva_id=<?= $reserva_id ?>`
            });

            const data = await response.json();

            if (data.error) {
                showError(data.error);
                setLoading(false);
                return;
            }

            const result = await stripe.confirmCardPayment(data.clientSecret, {
                payment_method: {
                    card: card,
                }
            });

            if (result.error) {
                showError(result.error.message);
                setLoading(false);
            } else {
                if (result.paymentIntent.status === 'succeeded') {
                    await savePayment(result.paymentIntent);
                }
            }
        } catch (e) {
            showError("Error técnico en el proceso de pago.");
            setLoading(false);
        }
    });

    async function savePayment(intent) {
        try {
            const res = await fetch('/clientes/controladores/cliente/StripeClienteController.php?accion=guardar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `reserva_id=<?= $reserva_id ?>&stripe_id=${intent.id}&monto=${intent.amount / 100}`
            });
            const result = await res.json();
            if (result.success) {
                buttonText.innerText = "¡Pago Confirmado!";
                submitBtn.style.background = "#22c55e";
                setTimeout(() => {
                    window.location.href = "/clientes/controladores/cliente/ReservasClienteController.php?accion=index";
                }, 2000);
            } else {
                showError(result.message);
                setLoading(false);
            }
        } catch (e) {
            showError("Error al sincronizar con el servidor.");
            setLoading(false);
        }
    }

    function showError(message) {
        const errorDiv = document.getElementById('card-errors');
        errorDiv.textContent = message;
        setTimeout(() => errorDiv.textContent = "", 6000);
    }

    function setLoading(isLoading) {
        submitBtn.disabled = isLoading;
        spinner.style.display = isLoading ? 'block' : 'none';
        buttonText.innerText = isLoading ? 'Validando...' : 'Confirmar Pago';
    }
</script>

</body>
</html>

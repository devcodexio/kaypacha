<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 3) {
    header("Location: /clientes/vistas/auth/login.php");
    exit;
}

require_once __DIR__ . '/../../conexion.php';

$usuario_id = (int) $_SESSION['usuario_id'];
$accion = $_GET['accion'] ?? 'index';

switch ($accion) {

    case 'pagar':
        if (!isset($_GET['reserva_id'])) {
            header("Location: /clientes/controladores/cliente/ReservasClienteController.php?accion=index");
            exit;
        }

        $reserva_id = (int) $_GET['reserva_id'];

        $sql = "SELECT r.*, m.numero_mesa
                FROM reservas r
                INNER JOIN mesas m ON m.id = r.mesa_id
                WHERE r.id = ? AND r.usuario_id = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ii", $reserva_id, $usuario_id);
        $stmt->execute();
        $reserva = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$reserva) {
            header("Location: /clientes/controladores/cliente/ReservasClienteController.php?accion=index");
            exit;
        }

        $monto = 10.00; // Monto fijo o dinámico según lógica
        include __DIR__ . '/../../vistas/cliente/pagos/stripe.php';
        break;

    case 'crear_intent':
        header('Content-Type: application/json');
        
        $reserva_id = (int)($_POST['reserva_id'] ?? 0);
        $monto = 1000; // $10.00 en centavos

        $secret_key = $_ENV['STRIPE_SECRET_KEY'] ?? '';

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => "https://api.stripe.com/v1/payment_intents",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_USERPWD => $secret_key . ":",
            CURLOPT_POSTFIELDS => http_build_query([
                'amount' => $monto,
                'currency' => 'usd',
                'description' => "Reserva #$reserva_id",
                'metadata' => ['reserva_id' => $reserva_id]
            ])
        ]);

        $response = curl_exec($ch);
        $data = json_decode($response, true);

        if (isset($data['error'])) {
            echo json_encode(['error' => $data['error']['message']]);
        } else {
            echo json_encode(['clientSecret' => $data['client_secret']]);
        }
        exit;

    case 'guardar':
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reserva_id = (int) $_POST['reserva_id'];
            $stripe_id = $_POST['stripe_id'] ?? '';
            $monto = (float) $_POST['monto'];

            if ($reserva_id > 0 && $stripe_id !== '') {
                $conexion->begin_transaction();
                try {
                    // 1. Guardar pago (reutilizamos la tabla o creamos una similar)
                    // Para simplificar, si no existe tabla 'pagos_stripe', podemos crearla o usar la de paypal con un flag
                    // Pero mejor crear una genérica o específica. El usuario ya tenía pagos_paypal.
                    
                    $sqlPago = "INSERT INTO pagos_stripe (reserva_id, stripe_id, monto, moneda, estado) VALUES (?, ?, ?, 'USD', 'succeeded')";
                    $stmt = $conexion->prepare($sqlPago);
                    $stmt->bind_param("isd", $reserva_id, $stripe_id, $monto);
                    $stmt->execute();
                    $stmt->close();

                    // 2. Actualizar reserva
                    $stmt = $conexion->prepare("UPDATE reservas SET estado = 'confirmado', metodo_pago = 'stripe', pagado = 1 WHERE id = ?");
                    $stmt->bind_param("i", $reserva_id);
                    $stmt->execute();
                    $stmt->close();

                    $conexion->commit();
                    echo json_encode(["success" => true]);
                    exit;
                } catch (Exception $e) {
                    $conexion->rollback();
                    echo json_encode(["success" => false, "message" => $e->getMessage()]);
                    exit;
                }
            }
        }
        echo json_encode(["success" => false, "message" => "Datos inválidos"]);
        exit;
}

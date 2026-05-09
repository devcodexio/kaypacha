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

    // 🔹 MOSTRAR PANTALLA DE PAGO
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

        $monto = 10.00;

        include __DIR__ . '/../../vistas/cliente/pagos/paypal.php';
        break;

    // 🔥 NUEVO: CAPTURAR PAGO (CLAVE)
    case 'capturar':

        header('Content-Type: application/json');

        $orderID = $_POST['orderID'] ?? '';

        if (!$orderID) {
            echo json_encode(["error" => "orderID vacío"]);
            exit;
        }

        $clientId = $_ENV['PAYPAL_CLIENT_ID'] ?? "";
        $secret   = $_ENV['PAYPAL_SECRET'] ?? "";

        // 🔹 1. Obtener ACCESS TOKEN
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => "https://api-m.sandbox.paypal.com/v1/oauth2/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD => "$clientId:$secret",
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => "grant_type=client_credentials"
        ]);

        $response = curl_exec($ch);

        if (!$response) {
            echo json_encode(["error" => "Error cURL TOKEN"]);
            exit;
        }

        $data = json_decode($response, true);
        $accessToken = $data['access_token'] ?? null;

        if (!$accessToken) {
            echo json_encode(["error" => "No se obtuvo access_token"]);
            exit;
        }

        // 🔹 2. CAPTURAR ORDEN
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => "https://api-m.sandbox.paypal.com/v2/checkout/orders/$orderID/capture",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer $accessToken"
            ]
        ]);

        $captureResponse = curl_exec($ch);

        if (!$captureResponse) {
            echo json_encode(["error" => "Error cURL CAPTURE"]);
            exit;
        }

        echo $captureResponse;
        exit;

    // 🔹 GUARDAR PAGO EN BD
    case 'guardar':

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $reserva_id = (int) $_POST['reserva_id'];
            $paypal_order_id = $_POST['paypal_order_id'] ?? '';
            $paypal_capture_id = $_POST['paypal_capture_id'] ?? '';
            $monto = (float) $_POST['monto'];
            $estado = $_POST['estado'] ?? 'COMPLETED';

            if ($reserva_id > 0 && $paypal_order_id !== '') {

                $conexion->begin_transaction();

                try {

                    // 🔹 Guardar pago
                    $sqlPago = "INSERT INTO pagos_paypal 
                                (reserva_id, paypal_order_id, paypal_capture_id, monto, moneda, estado)
                                VALUES (?, ?, ?, ?, 'USD', ?)";

                    $stmt = $conexion->prepare($sqlPago);
                    $stmt->bind_param("issds", $reserva_id, $paypal_order_id, $paypal_capture_id, $monto, $estado);
                    $stmt->execute();
                    $stmt->close();

                    // 🔹 Obtener mesa
                    $stmt2 = $conexion->prepare("SELECT mesa_id FROM reservas WHERE id = ?");
                    $stmt2->bind_param("i", $reserva_id);
                    $stmt2->execute();

                    $res = $stmt2->get_result()->fetch_assoc();
                    $stmt2->close();

                    if (!$res) {
                        throw new Exception("Reserva no encontrada");
                    }

                    $mesa_id = (int)$res['mesa_id'];

                    // 🔹 Actualizar reserva
                    $stmt3 = $conexion->prepare("UPDATE reservas 
                        SET estado = 'confirmado', metodo_pago = 'paypal', pagado = 1
                        WHERE id = ?");

                    $stmt3->bind_param("i", $reserva_id);
                    $stmt3->execute();
                    $stmt3->close();

                    // 🔹 Actualizar mesa
                    $stmt4 = $conexion->prepare("UPDATE mesas SET estado = 'reservada' WHERE id = ?");
                    $stmt4->bind_param("i", $mesa_id);
                    $stmt4->execute();
                    $stmt4->close();

                    $conexion->commit();

                    echo json_encode([
                        "success" => true,
                        "message" => "Pago guardado correctamente"
                    ]);
                    exit;

                } catch (Exception $e) {

                    $conexion->rollback();

                    echo json_encode([
                        "success" => false,
                        "message" => $e->getMessage()
                    ]);
                    exit;
                }
            }
        }

        echo json_encode([
            "success" => false,
            "message" => "Datos inválidos"
        ]);
        exit;
}
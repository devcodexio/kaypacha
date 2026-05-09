<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 3) {
    echo json_encode(["error" => "No autorizado"]);
    exit;
}

require_once __DIR__ . '/../../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $mesa_id = (int)$_POST['mesa_id'];
    $nombre_cliente = $_POST['nombre_cliente'];
    $telefono = $_POST['telefono'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $personas = (int)$_POST['personas'];

    $estado = "confirmada";

    $stmt = $conexion->prepare("
        INSERT INTO reservas 
        (mesa_id, nombre_cliente, telefono, fecha_reserva, hora_reserva, personas, estado)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "issssis",
        $mesa_id,
        $nombre_cliente,
        $telefono,
        $fecha,
        $hora,
        $personas,
        $estado
    );

    if ($stmt->execute()) {

        // cambiar estado mesa
        $updateMesa = $conexion->prepare("
            UPDATE mesas 
            SET estado = 'reservada'
            WHERE id = ?
        ");

        $updateMesa->bind_param("i", $mesa_id);
        $updateMesa->execute();

        echo json_encode([
            "success" => true,
            "mensaje" => "Reserva creada correctamente"
        ]);

    } else {

        echo json_encode([
            "success" => false
        ]);
    }
}
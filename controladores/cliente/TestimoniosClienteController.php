<?php
// controladores/cliente/TestimoniosClienteController.php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 3) {
    header("Location: /clientes/vistas/auth/login.php");
    exit;
}
require_once __DIR__ . '/../../conexion.php';

$accion = $_GET['accion'] ?? '';

if ($accion === 'guardar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $mensaje = $_POST['mensaje'] ?? '';
    $calificacion = (int)($_POST['calificacion'] ?? 5);

    if (!empty($mensaje)) {
        $stmt = $conexion->prepare("INSERT INTO testimonios (usuario_id, mensaje, calificacion, estado) VALUES (?, ?, ?, 'pendiente')");
        $stmt->bind_param("isi", $usuario_id, $mensaje, $calificacion);
        $stmt->execute();
        $stmt->close();
        header("Location: /clientes/controladores/cliente/DashboardClienteController.php?msg=testimonio_enviado");
    } else {
        header("Location: /clientes/controladores/cliente/DashboardClienteController.php?error=mensaje_vacio");
    }
    exit;
}
?>

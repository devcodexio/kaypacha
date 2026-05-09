<?php
// controladores/auth/login.php

session_start();
require_once __DIR__ . '/../../conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /clientes/vistas/auth/login.php");
    exit;
}

$correo   = trim($_POST['correo'] ?? '');
$password = $_POST['password'] ?? '';

if ($correo === '' || $password === '') {
    header("Location: /clientes/vistas/auth/login.php?error=campos");
    exit;
}

$sql = "SELECT * FROM usuarios WHERE correo = ? LIMIT 1";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$stmt->close();

if (!$usuario) {
    header("Location: /clientes/vistas/auth/login.php?error=credenciales");
    exit;
}

// la columna de contraseña en BD debe ser 'contraseña' (hash con password_hash)
if (!password_verify($password, $usuario['contraseña'])) {
    header("Location: /clientes/vistas/auth/login.php?error=credenciales");
    exit;
}

// Login correcto: guardar datos en sesión
$_SESSION['usuario_id'] = $usuario['id'];
$_SESSION['rol']        = $usuario['rol_id'];
$_SESSION['nombre']     = $usuario['nombre'];

// Redirigir según rol
if ($usuario['rol_id'] == 1) {
    header("Location: /clientes/controladores/admin/DashboardAdminController.php");
} elseif ($usuario['rol_id'] == 2) {
    header("Location: /clientes/controladores/empleado/DashboardEmpleadoController.php");
} else {
    header("Location: /clientes/controladores/cliente/DashboardClienteController.php");
}
exit;

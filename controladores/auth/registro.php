<?php
// controladores/auth/registro.php

session_start();
require_once __DIR__ . '/../../conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "vistas/auth/registro.php");
    exit;
}

$nombre            = trim($_POST['nombre'] ?? '');
$correo            = trim($_POST['correo'] ?? '');
$password          = $_POST['password'] ?? '';
$password_confirm  = $_POST['password_confirm'] ?? '';
$telefono          = trim($_POST['telefono'] ?? '');
$direccion         = trim($_POST['direccion'] ?? '');

// Validar campos obligatorios
if ($nombre === '' || $correo === '' || $password === '' || $password_confirm === '') {
    header("Location: " . BASE_URL . "vistas/auth/registro.php?error=campos");
    exit;
}

// Validar contraseñas iguales
if ($password !== $password_confirm) {
    header("Location: " . BASE_URL . "vistas/auth/registro.php?error=password");
    exit;
}

// Verificar que el correo no exista
$sql = "SELECT id FROM usuarios WHERE correo = ? LIMIT 1";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();
$existe = $result->fetch_assoc();
$stmt->close();

if ($existe) {
    header("Location: " . BASE_URL . "vistas/auth/registro.php?error=correo");
    exit;
}

// Rol cliente = 3
$rol_id = 3;
$hash = password_hash($password, PASSWORD_BCRYPT);

$sqlInsert = "INSERT INTO usuarios (rol_id, nombre, correo, contraseña, telefono, direccion)
              VALUES (?, ?, ?, ?, ?, ?)";
$stmt2 = $conexion->prepare($sqlInsert);
$stmt2->bind_param("isssss", $rol_id, $nombre, $correo, $hash, $telefono, $direccion);
$stmt2->execute();
$stmt2->close();

// Opcional: podrías loguearlo directamente aquí, pero por ahora lo mandamos al login
header("Location: " . BASE_URL . "vistas/auth/login.php?registro=ok");
exit;

<?php
// controladores/cliente/PerfilClienteController.php

session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 3) {
    header("Location: /clientes/vistas/auth/login.php");
    exit;
}

require_once __DIR__ . '/../../conexion.php';

$usuario_id = (int) $_SESSION['usuario_id'];
$accion = $_GET['accion'] ?? 'ver';

switch ($accion) {

    case 'ver':
    default:
        $sql = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();
        $stmt->close();

        include __DIR__ . '/../../vistas/cliente/perfil/ver.php';
        break;

    case 'editar':
        $sql = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();
        $stmt->close();

        include __DIR__ . '/../../vistas/cliente/perfil/editar.php';
        break;

    case 'actualizar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre   = $_POST['nombre'];
            $correo   = $_POST['correo'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];
            $nueva_contra = $_POST['nueva_contraseña'] ?? '';

            $sqlUpd = "UPDATE usuarios SET nombre = ?, correo = ?, telefono = ?, direccion = ? WHERE id = ?";
            $stmt = $conexion->prepare($sqlUpd);
            $stmt->bind_param("ssssi", $nombre, $correo, $telefono, $direccion, $usuario_id);
            $stmt->execute();
            $stmt->close();

            if (!empty($nueva_contra)) {
                $hash = password_hash($nueva_contra, PASSWORD_BCRYPT);
                $sqlPass = "UPDATE usuarios SET contraseña = ? WHERE id = ?";
                $stmt2 = $conexion->prepare($sqlPass);
                $stmt2->bind_param("si", $hash, $usuario_id);
                $stmt2->execute();
                $stmt2->close();
            }

            $_SESSION['nombre'] = $nombre;
        }

        header("Location: /clientes/controladores/cliente/PerfilClienteController.php?accion=ver");
        exit;
}

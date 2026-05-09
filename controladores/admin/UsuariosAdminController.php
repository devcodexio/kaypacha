<?php
// controladores/admin/UsuariosAdminController.php

session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    header("Location: /clientes/vistas/auth/login.php");
    exit;
}

require_once __DIR__ . '/../../conexion.php';

$accion = $_GET['accion'] ?? 'index';

switch ($accion) {

    // ======================= LISTAR USUARIOS =======================
    case 'index':
    default:
        $usuarios = [];

        $sql = "SELECT u.*, r.nombre AS nombre_rol
                FROM usuarios u
                LEFT JOIN roles r ON r.id = u.rol_id
                ORDER BY u.id DESC";

        $result = $conexion->query($sql);

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $usuarios[] = $row;
            }
        }

        require_once __DIR__ . '/../../vistas/admin/usuarios/index.php';
        break;


    // ======================= FORM CREAR =======================
    case 'crear':
        require_once __DIR__ . '/../../vistas/admin/usuarios/form.php';
        break;


    // ======================= FORM EDITAR =======================
    case 'editar':

        if (!isset($_GET['id'])) {
            header("Location: UsuariosAdminController.php?accion=index");
            exit;
        }

        $id = (int)$_GET['id'];

        $sql = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();

        $stmt->close();

        if (!$usuario) {
            header("Location: UsuariosAdminController.php?accion=index");
            exit;
        }

        require_once __DIR__ . '/../../vistas/admin/usuarios/form.php';
        break;


    // ======================= GUARDAR (CREAR/EDITAR) =======================
    case 'guardar':

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: UsuariosAdminController.php?accion=index");
            exit;
        }

        $id        = $_POST['id'] ?? null;
        $rol_id    = (int)($_POST['rol_id'] ?? 3);
        $nombre    = trim($_POST['nombre'] ?? '');
        $correo    = trim($_POST['correo'] ?? '');
        $telefono  = trim($_POST['telefono'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $password  = $_POST['password'] ?? '';

        if ($nombre === '' || $correo === '') {
            header("Location: UsuariosAdminController.php?accion=index");
            exit;
        }

        // ================= CREAR =================
        if (empty($id)) {

            $sql = "SELECT id FROM usuarios WHERE correo = ? LIMIT 1";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("s", $correo);
            $stmt->execute();

            $result = $stmt->get_result();
            $existe = $result->fetch_assoc();

            $stmt->close();

            if ($existe) {
                header("Location: UsuariosAdminController.php?accion=index");
                exit;
            }

            $hash = password_hash($password, PASSWORD_BCRYPT);

            $sqlIns = "INSERT INTO usuarios
                       (rol_id, nombre, correo, contraseña, telefono, direccion)
                       VALUES (?, ?, ?, ?, ?, ?)";

            $stmt2 = $conexion->prepare($sqlIns);
            $stmt2->bind_param("isssss", $rol_id, $nombre, $correo, $hash, $telefono, $direccion);
            $stmt2->execute();
            $stmt2->close();

        }

        // ================= EDITAR =================
        else {

            $id = (int)$id;

            $sqlUpd = "UPDATE usuarios
                       SET rol_id = ?, nombre = ?, correo = ?, telefono = ?, direccion = ?
                       WHERE id = ?";

            $stmt = $conexion->prepare($sqlUpd);
            $stmt->bind_param("issssi", $rol_id, $nombre, $correo, $telefono, $direccion, $id);
            $stmt->execute();
            $stmt->close();

            if (!empty($password)) {

                $hash = password_hash($password, PASSWORD_BCRYPT);

                $sqlPass = "UPDATE usuarios SET contraseña = ? WHERE id = ?";

                $stmt2 = $conexion->prepare($sqlPass);
                $stmt2->bind_param("si", $hash, $id);
                $stmt2->execute();
                $stmt2->close();
            }
        }

        header("Location: UsuariosAdminController.php?accion=index");
        exit;



    // ======================= CREAR RECEPCIONISTA =======================
    case 'crear_recepcionista':

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $nombre = trim($_POST['nombre'] ?? '');
            $correo = trim($_POST['correo'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($nombre === '' || $correo === '' || $password === '') {
                echo "Todos los campos son obligatorios";
                exit;
            }

            // verificar correo duplicado
            $sqlCheck = "SELECT id FROM usuarios WHERE correo = ?";
            $stmt = $conexion->prepare($sqlCheck);
            $stmt->bind_param("s", $correo);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "El correo ya existe";
                exit;
            }

            $hash = password_hash($password, PASSWORD_BCRYPT);

            $rol_id = 2; // rol recepcionista

            $sql = "INSERT INTO usuarios (rol_id, nombre, correo, contraseña)
                    VALUES (?, ?, ?, ?)";

            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("isss", $rol_id, $nombre, $correo, $hash);

            if ($stmt->execute()) {
                header("Location: UsuariosAdminController.php?accion=index");
            } else {
                echo "Error al crear recepcionista";
            }

            $stmt->close();
        }

        break;



    // ======================= ELIMINAR =======================
    case 'eliminar':

        if (!isset($_GET['id'])) {
            header("Location: UsuariosAdminController.php?accion=index");
            exit;
        }

        $id = (int)$_GET['id'];

        if ($id == $_SESSION['usuario_id']) {
            header("Location: UsuariosAdminController.php?accion=index");
            exit;
        }

        $sql = "DELETE FROM usuarios WHERE id = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        header("Location: UsuariosAdminController.php?accion=index");
        exit;
}
<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    header("Location: /clientes/vistas/auth/login.php");
    exit;
}
require_once __DIR__ . '/../../conexion.php';

$accion = $_GET['accion'] ?? 'index';

switch ($accion) {

    case 'index':
    default:
        $categorias = [];
        $result = $conexion->query("SELECT * FROM categorias_platos ORDER BY nombre ASC");
        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row;
        }
        require __DIR__ . '/../../vistas/admin/categorias/index.php';
        break;

    case 'crear':
        require __DIR__ . '/../../vistas/admin/categorias/form.php';
        break;

    case 'editar':
        if (!isset($_GET['id'])) {
            header("Location: CategoriasAdminController.php?accion=index");
            exit;
        }
        $id = (int)$_GET['id'];
        $stmt = $conexion->prepare("SELECT * FROM categorias_platos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $categoria = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$categoria) {
            header("Location: CategoriasAdminController.php?accion=index");
            exit;
        }

        require __DIR__ . '/../../vistas/admin/categorias/form.php';
        break;

    case 'guardar':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: CategoriasAdminController.php?accion=index");
            exit;
        }

        $id   = $_POST['id'] ?? null;
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');

        if ($nombre === '') {
            header("Location: CategoriasAdminController.php?accion=index");
            exit;
        }

        if (empty($id)) {
            $stmt = $conexion->prepare("INSERT INTO categorias_platos (nombre, descripcion) VALUES (?, ?)");
            $stmt->bind_param("ss", $nombre, $descripcion);
            $stmt->execute();
            $stmt->close();
        } else {
            $id = (int)$id;
            $stmt = $conexion->prepare("UPDATE categorias_platos SET nombre=?, descripcion=? WHERE id=?");
            $stmt->bind_param("ssi", $nombre, $descripcion, $id);
            $stmt->execute();
            $stmt->close();
        }

        header("Location: CategoriasAdminController.php?accion=index");
        exit;

    case 'eliminar':
        if (!isset($_GET['id'])) {
            header("Location: CategoriasAdminController.php?accion=index");
            exit;
        }
        $id = (int)$_GET['id'];
        $stmt = $conexion->prepare("DELETE FROM categorias_platos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        header("Location: CategoriasAdminController.php?accion=index");
        exit;
}

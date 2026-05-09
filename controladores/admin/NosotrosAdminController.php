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
        $bloques = [];
        $result = $conexion->query("SELECT * FROM nosotros ORDER BY tipo ASC");
        while ($row = $result->fetch_assoc()) {
            $bloques[] = $row;
        }
        require __DIR__ . '/../../vistas/admin/nosotros/index.php';
        break;

    case 'crear':
        require __DIR__ . '/../../vistas/admin/nosotros/form.php';
        break;

    case 'editar':
        if (!isset($_GET['id'])) {
            header("Location: NosotrosAdminController.php?accion=index");
            exit;
        }
        $id = (int)$_GET['id'];
        $stmt = $conexion->prepare("SELECT * FROM nosotros WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $bloque = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$bloque) {
            header("Location: NosotrosAdminController.php?accion=index");
            exit;
        }

        require __DIR__ . '/../../vistas/admin/nosotros/form.php';
        break;

    case 'guardar':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: NosotrosAdminController.php?accion=index");
            exit;
        }

        $id          = $_POST['id'] ?? null;
        $tipo        = $_POST['tipo'] ?? 'mision';
        $titulo      = trim($_POST['titulo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');

        if ($titulo === '' || $descripcion === '') {
            header("Location: NosotrosAdminController.php?accion=index");
            exit;
        }

        if (empty($id)) {
            $stmt = $conexion->prepare("INSERT INTO nosotros (tipo, titulo, descripcion)
                                        VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $tipo, $titulo, $descripcion);
            $stmt->execute();
            $stmt->close();
        } else {
            $id = (int)$id;
            $stmt = $conexion->prepare("UPDATE nosotros
                                        SET tipo=?, titulo=?, descripcion=?
                                        WHERE id=?");
            $stmt->bind_param("sssi", $tipo, $titulo, $descripcion, $id);
            $stmt->execute();
            $stmt->close();
        }

        header("Location: NosotrosAdminController.php?accion=index");
        exit;

    case 'eliminar':
        if (!isset($_GET['id'])) {
            header("Location: NosotrosAdminController.php?accion=index");
            exit;
        }
        $id = (int)$_GET['id'];
        $stmt = $conexion->prepare("DELETE FROM nosotros WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        header("Location: NosotrosAdminController.php?accion=index");
        exit;
}

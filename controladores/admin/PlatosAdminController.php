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
        $platos = [];
        $sql = "SELECT p.*, c.nombre AS categoria
                FROM platos p
                INNER JOIN categorias_platos c ON c.id = p.categoria_id
                ORDER BY c.nombre, p.nombre";
        $result = $conexion->query($sql);
        while ($row = $result->fetch_assoc()) {
            $platos[] = $row;
        }
        require __DIR__ . '/../../vistas/admin/platos/index.php';
        break;

    case 'crear':
        $categorias = [];
        $result = $conexion->query("SELECT * FROM categorias_platos ORDER BY nombre ASC");
        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row;
        }
        require __DIR__ . '/../../vistas/admin/platos/form.php';
        break;

    case 'editar':
        if (!isset($_GET['id'])) {
            header("Location: PlatosAdminController.php?accion=index");
            exit;
        }
        $id = (int)$_GET['id'];

        $stmt = $conexion->prepare("SELECT * FROM platos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $plato = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$plato) {
            header("Location: PlatosAdminController.php?accion=index");
            exit;
        }

        $categorias = [];
        $result = $conexion->query("SELECT * FROM categorias_platos ORDER BY nombre ASC");
        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row;
        }

        require __DIR__ . '/../../vistas/admin/platos/form.php';
        break;

    case 'guardar':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: PlatosAdminController.php?accion=index");
            exit;
        }

        $id           = $_POST['id'] ?? null;
        $categoria_id = (int)($_POST['categoria_id'] ?? 0);
        $nombre       = trim($_POST['nombre'] ?? '');
        $descripcion  = trim($_POST['descripcion'] ?? '');
        $precio       = (float)($_POST['precio'] ?? 0);
        $disponible   = (int)($_POST['disponible'] ?? 1);
        $imagen       = $_POST['imagen_actual'] ?? '';

        // manejar nueva imagen
        if (!empty($_FILES['imagen']['name'])) {
            $uploadDir = __DIR__ . '/../../uploads/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $nombre_archivo = time() . "_" . basename($_FILES['imagen']['name']);
            $destino = $uploadDir . $nombre_archivo;

            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
                $imagen = $nombre_archivo;
            }
        }

        if (empty($id)) {
            $sql = "INSERT INTO platos (categoria_id, nombre, descripcion, precio, imagen, disponible)
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("issdsi", $categoria_id, $nombre, $descripcion, $precio, $imagen, $disponible);
            $stmt->execute();
            $stmt->close();
        } else {
            $id = (int)$id;
            $sql = "UPDATE platos
                    SET categoria_id=?, nombre=?, descripcion=?, precio=?, imagen=?, disponible=?
                    WHERE id=?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("issdsii", $categoria_id, $nombre, $descripcion, $precio, $imagen, $disponible, $id);
            $stmt->execute();
            $stmt->close();
        }

        header("Location: PlatosAdminController.php?accion=index");
        exit;

    case 'eliminar':
        if (!isset($_GET['id'])) {
            header("Location: PlatosAdminController.php?accion=index");
            exit;
        }
        $id = (int)$_GET['id'];
        $stmt = $conexion->prepare("DELETE FROM platos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        header("Location: PlatosAdminController.php?accion=index");
        exit;
}

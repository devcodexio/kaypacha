<?php
// controladores/admin/TestimoniosAdminController.php
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
        $testimonios = [];
        $sql = "SELECT t.*, u.nombre AS cliente, u.correo
                FROM testimonios t
                INNER JOIN usuarios u ON u.id = t.usuario_id
                ORDER BY t.id DESC";
        $result = $conexion->query($sql);
        while ($row = $result->fetch_assoc()) {
            $testimonios[] = $row;
        }
        require __DIR__ . '/../../vistas/admin/testimonios/index.php';
        break;

    case 'estado':
        if (!isset($_GET['id']) || !isset($_GET['nuevo'])) {
            header("Location: TestimoniosAdminController.php?accion=index");
            exit;
        }
        $id = (int)$_GET['id'];
        $nuevo = $_GET['nuevo']; // 'activo' o 'pendiente' (o 'inactivo')
        
        $stmt = $conexion->prepare("UPDATE testimonios SET estado = ? WHERE id = ?");
        $stmt->bind_param("si", $nuevo, $id);
        $stmt->execute();
        $stmt->close();
        
        header("Location: TestimoniosAdminController.php?accion=index&msg=estado_actualizado");
        exit;
        break;

    case 'eliminar':
        if (!isset($_GET['id'])) {
            header("Location: TestimoniosAdminController.php?accion=index");
            exit;
        }
        $id = (int)$_GET['id'];
        $stmt = $conexion->prepare("DELETE FROM testimonios WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        header("Location: TestimoniosAdminController.php?accion=index&msg=eliminado");
        exit;
        break;
}
?>

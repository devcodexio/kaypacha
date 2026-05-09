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
        $reservas = [];
        $sql = "SELECT r.*, u.nombre AS cliente, m.numero_mesa
                FROM reservas r
                INNER JOIN usuarios u ON u.id = r.usuario_id
                INNER JOIN mesas m ON m.id = r.mesa_id
                ORDER BY r.id DESC";
        $result = $conexion->query($sql);
        while ($row = $result->fetch_assoc()) {
            $reservas[] = $row;
        }
        require __DIR__ . '/../../vistas/admin/reservas/index.php';
        break;

    // Cambiar estado de una reserva (ej: confirmar, cancelar)
    case 'estado':
        if (!isset($_GET['id']) || !isset($_GET['nuevo'])) {
            header("Location: ReservasAdminController.php?accion=index");
            exit;
        }
        $id        = (int)$_GET['id'];
        $nuevo     = $_GET['nuevo'];

        // obtener mesa para actualizar estado
        $stmt = $conexion->prepare("SELECT mesa_id FROM reservas WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($res) {
            $mesa_id = (int)$res['mesa_id'];

            $stmt2 = $conexion->prepare("UPDATE reservas SET estado = ? WHERE id = ?");
            $stmt2->bind_param("si", $nuevo, $id);
            $stmt2->execute();
            $stmt2->close();

            if ($nuevo === 'cancelado') {
                $stmt3 = $conexion->prepare("UPDATE mesas SET estado='disponible' WHERE id=?");
                $stmt3->bind_param("i", $mesa_id);
                $stmt3->execute();
                $stmt3->close();
            } elseif ($nuevo === 'confirmado') {
                $stmt3 = $conexion->prepare("UPDATE mesas SET estado='reservada' WHERE id=?");
                $stmt3->bind_param("i", $mesa_id);
                $stmt3->execute();
                $stmt3->close();
            }
        }
        header("Location: ReservasAdminController.php?accion=index");
        exit;
        break;

    case 'exportar':
        ob_end_clean();
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=reporte_reservas.csv');
        $o = fopen('php://output', 'w');
        // UTF-8 BOM para que Excel reconozca tildes
        fprintf($o, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($o, ['ID','Cliente','Mesa','Fecha','Hora','Pax','Estado']);
        $q = $conexion->query("SELECT r.*, u.nombre as cl, m.numero_mesa as me FROM reservas r JOIN usuarios u ON u.id=r.usuario_id JOIN mesas m ON m.id=r.mesa_id ORDER BY r.id DESC");
        while($r = $q->fetch_assoc()) {
            fputcsv($o, [$r['id'], $r['cl'], $r['me'], $r['fecha'], $r['hora'], $r['personas'], strtoupper($r['estado'])]);
        }
        fclose($o);
        exit;
        break;
}
?>

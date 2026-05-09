<?php
session_start();

/* ================================
   VALIDAR LOGIN ADMIN
================================ */
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    header("Location: /clientes/vistas/auth/login.php");
    exit;
}

/* ================================
   CONEXIÓN
================================ */
require_once __DIR__ . '/../../conexion.php';

/* ================================
   ACCIÓN
================================ */
$accion = $_GET['accion'] ?? 'index';

switch ($accion) {

/* ======================================
   LISTAR MESAS - ✅ MOSTRAR TODAS (activas + inactivas)
======================================*/
case 'index':
default:
    $mesas = [];

    // ✅ CAMBIO CLAVE: Quitar "WHERE activo = 1" para ver TODAS las mesas
    // Orden: primero activas (activo=1), luego inactivas (activo=0), por número
    $sql = "SELECT * FROM mesas ORDER BY activo DESC, numero_mesa ASC";
    $result = $conexion->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $mesas[] = $row;
        }
    }

    require __DIR__ . '/../../vistas/admin/mesas/index.php';
    break;

/* ======================================
   FORM CREAR
======================================*/
case 'crear':
    $mesa = null;
    require __DIR__ . '/../../vistas/admin/mesas/form.php';
    break;

/* ======================================
   FORM EDITAR
======================================*/
case 'editar':
    if (!isset($_GET['id'])) {
        header("Location: MesasAdminController.php?accion=index");
        exit;
    }

    $id = (int) $_GET['id'];
    $stmt = $conexion->prepare("SELECT * FROM mesas WHERE id=? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $mesa = $resultado->fetch_assoc();
    $stmt->close();

    if (!$mesa) {
        header("Location: MesasAdminController.php?accion=index");
        exit;
    }

    require __DIR__ . '/../../vistas/admin/mesas/form.php';
    break;

/* ======================================
   GUARDAR
======================================*/
case 'guardar':
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: MesasAdminController.php?accion=index");
        exit;
    }

    $id           = $_POST['id'] ?? null;
    $numero_mesa  = (int)($_POST['numero_mesa'] ?? 0);
    $capacidad    = (int)($_POST['capacidad'] ?? 0);
    $estado       = $_POST['estado'] ?? 'libre';
    $pos_top      = $_POST['pos_top'] ?? '0px';
    $pos_left     = $_POST['pos_left'] ?? '0px';
    $tipo_forma   = $_POST['tipo_forma'] ?? 'cuadrada';
    $zona         = $_POST['zona'] ?? 'general';
    $activo       = isset($_POST['activo']) ? 1 : 0;

    if (empty($id)) {
        // CREAR
        $stmt = $conexion->prepare("
            INSERT INTO mesas
            (numero_mesa, capacidad, estado, pos_top, pos_left, tipo_forma, zona, activo)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iisssssi", $numero_mesa, $capacidad, $estado, $pos_top, $pos_left, $tipo_forma, $zona, $activo);
        $stmt->execute();
        $stmt->close();
        header("Location: MesasAdminController.php?accion=index&msg=creado");
        exit;
    } else {
        // EDITAR
        $stmt = $conexion->prepare("
            UPDATE mesas SET
            numero_mesa=?, capacidad=?, estado=?, pos_top=?, pos_left=?, tipo_forma=?, zona=?, activo=?
            WHERE id=?
        ");
        $stmt->bind_param("iisssssii", $numero_mesa, $capacidad, $estado, $pos_top, $pos_left, $tipo_forma, $zona, $activo, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: MesasAdminController.php?accion=index&msg=editado");
        exit;
    }
    break;

/* ======================================
   ELIMINAR MESA (Soft Delete - pone activo=0)
======================================*/
case 'eliminar':
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: MesasAdminController.php?accion=index");
        exit;
    }
    if (!isset($_POST['id'])) {
        header("Location: MesasAdminController.php?accion=index");
        exit;
    }

    $id = (int) $_POST['id'];
    $stmt = $conexion->prepare("UPDATE mesas SET activo = 0 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: MesasAdminController.php?accion=index&msg=eliminado");
    exit;
    break;

/* ======================================
   ✅ NUEVO: RESTAURAR MESA (pone activo=1)
======================================*/
case 'restaurar':
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: MesasAdminController.php?accion=index");
        exit;
    }
    if (!isset($_POST['id'])) {
        header("Location: MesasAdminController.php?accion=index");
        exit;
    }

    $id = (int) $_POST['id'];
    $stmt = $conexion->prepare("UPDATE mesas SET activo = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: MesasAdminController.php?accion=index&msg=restaurada");
    exit;
    break;

/* ======================================
   MAPA INTERACTIVO (Croquis Full Page)
======================================*/
case 'croquis':
    $all_mesas = [];
    $sql = "SELECT id, numero_mesa, capacidad, estado, zona, tipo_forma, pos_top, pos_left, activo FROM mesas ORDER BY numero_mesa ASC";
    $result = $conexion->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $row['pos_top_num'] = (int)rtrim($row['pos_top'] ?? '0', 'px');
            $row['pos_left_num'] = (int)rtrim($row['pos_left'] ?? '0', 'px');
            $all_mesas[] = $row;
        }
    }
    require __DIR__ . '/../../vistas/admin/mesas/croquis.php';
    break;

/* ======================================
   GUARDAR POSICIÓN (AJAX)
======================================*/
case 'guardar_posicion':
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') exit;
    $id = (int)$_POST['id'];
    $top = $_POST['pos_top'];
    $left = $_POST['pos_left'];

    $stmt = $conexion->prepare("UPDATE mesas SET pos_top=?, pos_left=? WHERE id=?");
    $stmt->bind_param("ssi", $top, $left, $id);
    $success = $stmt->execute();
    $stmt->close();

    echo json_encode(['success' => $success]);
    exit;
    break;
}
?>
<?php
// controladores/empleado/MesasEmpleadoController.php

session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) {
    header("Location: /clientes/vistas/auth/login.php");
    exit;
}

require_once __DIR__ . '/../../conexion.php';

$accion = $_GET['accion'] ?? 'index';

switch ($accion) {

    case 'index':
    default:
        $mesas = [];
        $result = $conexion->query("SELECT * FROM mesas ORDER BY numero_mesa ASC");
        while ($row = $result->fetch_assoc()) {
            $mesas[] = $row;
        }
        require_once __DIR__ . '/../../vistas/empleado/mesas/index.php';
        break;

    // cambiar estado de la mesa manualmente
    case 'estado':
        if (!isset($_GET['id']) || !isset($_GET['nuevo'])) {
            header("Location: MesasEmpleadoController.php?accion=index");
            exit;
        }

        $id = (int)$_GET['id'];
        $nuevo = $_GET['nuevo'];

        $stmt = $conexion->prepare("UPDATE mesas SET estado = ? WHERE id = ?");
        $stmt->bind_param("si", $nuevo, $id);
        $stmt->execute();
        $stmt->close();

        header("Location: MesasEmpleadoController.php?accion=index");
        exit;
}

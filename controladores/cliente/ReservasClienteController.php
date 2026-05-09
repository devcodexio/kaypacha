<?php

session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 3) {
    header("Location: /clientes/vistas/auth/login.php");
    exit;
}

require_once __DIR__ . '/../../conexion.php';

$usuario_id = (int)$_SESSION['usuario_id'];
$accion = $_GET['accion'] ?? 'index';

switch ($accion) {

/* ======================================
MIS RESERVAS
====================================== */

case 'index':

$reservas = [];

$sql = "SELECT r.*, m.numero_mesa
        FROM reservas r
        INNER JOIN mesas m ON m.id = r.mesa_id
        WHERE r.usuario_id=?
        ORDER BY r.fecha DESC, r.hora DESC";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i",$usuario_id);
$stmt->execute();

$res = $stmt->get_result();

while($r = $res->fetch_assoc()){
$reservas[] = $r;
}

include __DIR__.'/../../vistas/cliente/reservas/index.php';

break;


/* ======================================
CREAR RESERVA
====================================== */

case 'crear':

$fecha = $_GET['fecha'] ?? null;
$personas = $_GET['personas'] ?? null;

$mesasDisponibles = [];

if($fecha && $personas){

$personas = (int)$personas;

/* horario restaurante 7am a 11pm */

$horariosBase = [];

for($h=7;$h<=23;$h++){
$horariosBase[] = str_pad($h,2,'0',STR_PAD_LEFT).":00";
}

/* obtener mesas */

$sql = "SELECT * FROM mesas ORDER BY capacidad ASC";
$result = $conexion->query($sql);

$mesas = [];

while($m = $result->fetch_assoc()){
$mesas[] = $m;
}

/* sugerir mesas */

$mesasSugeridas = [];

foreach($mesas as $mesa){

$diferencia = $mesa['capacidad'] - $personas;

if($mesa['capacidad'] >= $personas || abs($diferencia) <= 3){
$mesasSugeridas[] = $mesa;
}

}

/* ordenar */

usort($mesasSugeridas,function($a,$b) use ($personas){
return abs($a['capacidad']-$personas) <=> abs($b['capacidad']-$personas);
});


foreach($mesasSugeridas as $mesa){

/* obtener reservas */

$sql2 = "SELECT hora
FROM reservas
WHERE mesa_id=?
AND fecha=?
AND estado IN ('pendiente','confirmada')";

$stmt2 = $conexion->prepare($sql2);
$stmt2->bind_param("is",$mesa['id'],$fecha);
$stmt2->execute();

$res = $stmt2->get_result();

$horasOcupadas = [];

while($r = $res->fetch_assoc()){
$horasOcupadas[] = $r['hora'];
}

/* calcular timeline */

$timeline = [];

foreach($horariosBase as $hora){

$ocupado = false;

$nuevoInicio = strtotime($fecha." ".$hora);
$nuevoFin = strtotime("+2 hours",$nuevoInicio);

foreach($horasOcupadas as $existente){

$reservaInicio = strtotime($fecha." ".$existente);
$reservaFin = strtotime("+2 hours",$reservaInicio);

/* detectar solapamiento */

if($nuevoInicio < $reservaFin && $nuevoFin > $reservaInicio){
$ocupado = true;
break;
}

}

$timeline[] = [
'hora'=>$hora,
'ocupado'=>$ocupado
];

}

$mesa['timeline'] = $timeline;

$mesasDisponibles[] = $mesa;

}

}

include __DIR__.'/../../vistas/cliente/reservas/crear.php';

break;


/* ======================================
GUARDAR
====================================== */

case 'guardar':

if($_SERVER['REQUEST_METHOD']=="POST"){

$mesa_id = (int)$_POST['mesa_id'];
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];
$personas = (int)$_POST['personas'];
$notas = $_POST['notas'] ?? '';

$inicio = strtotime($fecha." ".$hora);
$fin = strtotime("+2 hours",$inicio);

$sql = "SELECT hora
FROM reservas
WHERE mesa_id=?
AND fecha=?
AND estado IN ('pendiente','confirmada')";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("is",$mesa_id,$fecha);
$stmt->execute();

$res = $stmt->get_result();

while($r = $res->fetch_assoc()){

$resInicio = strtotime($fecha." ".$r['hora']);
$resFin = strtotime("+2 hours",$resInicio);

if($inicio < $resFin && $fin > $resInicio){

header("Location: ReservasClienteController.php?accion=crear&fecha=$fecha&personas=$personas&error=horario_ocupado");
exit;

}

}

$stmt->close();

/* guardar */

$sql = "INSERT INTO reservas
(usuario_id,mesa_id,fecha,hora,cantidad_personas,estado,notas)
VALUES (?,?,?,?,?,'pendiente',?)";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("iissis",$usuario_id,$mesa_id,$fecha,$hora,$personas,$notas);
$stmt->execute();

$reserva_id = $stmt->insert_id;

header("Location: /clientes/controladores/cliente/StripeClienteController.php?accion=pagar&reserva_id=".$reserva_id);

}

break;


/* ======================================
CANCELAR
====================================== */

case 'cancelar':

$id = (int)$_GET['id'];

$sql = "UPDATE reservas
SET estado='cancelado'
WHERE id=? AND usuario_id=?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii",$id,$usuario_id);
$stmt->execute();

header("Location: ReservasClienteController.php");

break;

}
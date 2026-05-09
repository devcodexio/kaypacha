<?php
require_once __DIR__ . '/env.php';

// Intentar obtener configuración desde DATABASE_URL
if (isset($_ENV['DATABASE_URL']) && !empty($_ENV['DATABASE_URL'])) {
    $db_url = parse_url($_ENV['DATABASE_URL']);
    $host = $db_url['host'] ?? "127.0.0.1";
    $usuario = $db_url['user'] ?? "root";
    $password = $db_url['pass'] ?? "";
    $bd = ltrim($db_url['path'], '/');
} else {
    // Fallback a variables individuales
    $host = $_ENV['DB_HOST'] ?? "127.0.0.1";
    $usuario = $_ENV['DB_USER'] ?? "root";
    $password = $_ENV['DB_PASS'] ?? "";
    $bd = $_ENV['DB_NAME'] ?? "restaurante";
}

$conexion = new mysqli($host, $usuario, $password, $bd);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8mb4");


/* =========================================
   LIBERAR MESAS CUANDO LA RESERVA YA PASÓ
========================================= */

$sqlLiberar = "
UPDATE mesas m
INNER JOIN reservas r ON r.mesa_id = m.id
SET 
    m.estado = 'disponible',
    r.estado = 'finalizada'
WHERE 
    CONCAT(r.fecha,' ',r.hora) < NOW()
    AND r.estado != 'finalizada'
";

$conexion->query($sqlLiberar);


/* =========================================
   OPCIONAL: LIBERAR DESPUÉS DE 2 HORAS
   (más real para restaurantes)
========================================= */

/*
$sqlLiberar = "
UPDATE mesas m
INNER JOIN reservas r ON r.mesa_id = m.id
SET 
    m.estado = 'disponible',
    r.estado = 'finalizada'
WHERE 
    DATE_ADD(CONCAT(r.fecha,' ',r.hora), INTERVAL 2 HOUR) < NOW()
    AND r.estado != 'finalizada'
";

$conexion->query($sqlLiberar);
*/

?>
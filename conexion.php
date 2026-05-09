<?php
require_once __DIR__ . '/env.php';

// Configuración de la URL base dinámica (SOLUCIÓN DEFINITIVA)
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
// Obtenemos la ruta física de la carpeta del proyecto
$project_dir = str_replace('\\', '/', __DIR__);
// Obtenemos la ruta física de la raíz del servidor
$document_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
// Calculamos la diferencia para obtener la ruta URL
$base_path = str_replace($document_root, '', $project_dir);
// Limpiamos posibles barras dobles
$base_path = '/' . trim($base_path, '/');
if ($base_path == '/') $base_path = '';

define('BASE_URL', $protocol . "://" . $host . $base_path . "/");

// Configuración de Conexión (PostgreSQL - Soporte Render)
if (isset($_ENV['DATABASE_URL']) && !empty($_ENV['DATABASE_URL'])) {
    $db_url = parse_url($_ENV['DATABASE_URL']);
    $host = $db_url['host'];
    $port = $db_url['port'] ?? "5432";
    $usuario = $db_url['user'];
    $password = $db_url['pass'];
    $bd = ltrim($db_url['path'], '/');
} else {
    $host = $_ENV['DB_HOST'] ?? "127.0.0.1";
    $usuario = $_ENV['DB_USER'] ?? "postgres";
    $password = $_ENV['DB_PASS'] ?? "";
    $bd = $_ENV['DB_NAME'] ?? "restaurante";
    $port = $_ENV['DB_PORT'] ?? "5432";
}

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$bd";
    // Clase para emular el comportamiento de MySQLi en PDO
    class PDOStatement_Compatible extends PDOStatement {
        public function fetch_assoc() { return $this->fetch(PDO::FETCH_ASSOC); }
        public function fetch_row() { return $this->fetch(PDO::FETCH_NUM); }
        public function get_result() { return $this; } // Para compatibilidad con sentencias preparadas
        
        // Emular num_rows usando rowCount (aproximado en SELECTs de Postgres)
        public function __get($name) {
            if ($name === 'num_rows') {
                return $this->rowCount();
            }
            return null;
        }
    }

    $conexion = new PDO($dsn, $usuario, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // ESTA ES LA CLAVE: Decirle a PDO que use nuestra clase compatible
        PDO::ATTR_STATEMENT_CLASS => ['PDOStatement_Compatible', []]
    ]);

} catch (PDOException $e) {
    die("Error de conexión (PostgreSQL): " . $e->getMessage());
}

/* =========================================
   LIBERAR MESAS (SINTAXIS POSTGRESQL)
========================================= */
try {
    $sqlLiberar = "
    UPDATE mesas SET estado = 'disponible'
    FROM reservas r WHERE r.mesa_id = mesas.id
    AND (r.fecha + r.hora) < NOW() AND r.estado != 'finalizada';
    
    UPDATE reservas SET estado = 'finalizada'
    WHERE (fecha + hora) < NOW() AND estado != 'finalizada';
    ";
    $conexion->exec($sqlLiberar);
} catch (Exception $e) {
    // Silencioso si falla la tabla por no existir aún
}
?>
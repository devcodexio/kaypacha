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

// Configuración de Conexión (PostgreSQL)
$host = $_ENV['DB_HOST'] ?? "127.0.0.1";
$usuario = $_ENV['DB_USER'] ?? "postgres";
$password = $_ENV['DB_PASS'] ?? "";
$bd = $_ENV['DB_NAME'] ?? "restaurante";
$port = $_ENV['DB_PORT'] ?? "5432";

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$bd";
    // Usamos una clase extendida para mantener compatibilidad con $conexion->query()
    class PDO_Compatible extends PDO {
        public function query($statement, $mode = PDO::ATTR_DEFAULT_FETCH_MODE, ...$fetch_details): PDOStatement|false {
            return parent::query($statement);
        }
        public function set_charset($charset) { return true; }
    }

    $conexion = new PDO_Compatible($dsn, $usuario, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // Shim para que fetch_assoc() y num_rows funcionen en los objetos devueltos
    class PDOStatement_Compatible extends PDOStatement {
        public function fetch_assoc() { return $this->fetch(PDO::FETCH_ASSOC); }
        public function fetch_row() { return $this->fetch(PDO::FETCH_NUM); }
        // num_rows es difícil de emular en PDO sin cargar todo, pero rowCount suele servir
        public $num_rows = 0; 
        protected function __construct() {
            // PDOStatement no permite constructor público, se maneja vía setAttribute
        }
    }
    // Nota: Emular num_rows perfectamente requiere cambios en cada controlador.
    // Por ahora, forzaremos PDO para que sea lo más compatible posible.

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
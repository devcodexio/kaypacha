<?php
// Archivo temporal para crear un usuario ADMIN

require_once __DIR__ . '/conexion.php';

// DATOS DEL ADMIN QUE QUIERES CREAR
$nombre  = "Administrador";
$correo  = "admin@correo.com";     // <-- CAMBIA SI QUIERES
$clave_plana = "123456";           // <-- CONTRASEÑA QUE USARÁS PARA ENTRAR
$telefono = "999999999";
$direccion = "Oficina principal";
$rol_id = 1; // 1 = ADMIN, 2 = EMPLEADO, 3 = CLIENTE

// Verificar si ya existe un usuario con ese correo
$sql = "SELECT id FROM usuarios WHERE correo = ? LIMIT 1";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();
$existe = $result->fetch_assoc();
$stmt->close();

if ($existe) {
    echo "Ya existe un usuario con el correo $correo";
    exit;
}

// Encriptar contraseña
$hash = password_hash($clave_plana, PASSWORD_BCRYPT);

// Insertar admin
$sqlInsert = "INSERT INTO usuarios (rol_id, nombre, correo, contraseña, telefono, direccion)
              VALUES (?, ?, ?, ?, ?, ?)";
$stmt2 = $conexion->prepare($sqlInsert);
$stmt2->bind_param("isssss", $rol_id, $nombre, $correo, $hash, $telefono, $direccion);

if ($stmt2->execute()) {
    echo "✅ Admin creado correctamente.<br>";
    echo "Correo: $correo<br>";
    echo "Contraseña: $clave_plana<br>";
} else {
    echo "❌ Error al crear admin: " . $stmt2->error;
}
$stmt2->close();

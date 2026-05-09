<?php
require_once 'conexion.php';

echo "<h2>Diagnóstico de Conexión y Usuario</h2>";

try {
    // 1. Probar conexión
    echo "1. Probando conexión... OK<br>";

    // 2. Buscar al administrador
    $correo = 'admin@correo.com';
    $sql = "SELECT id, nombre, correo, contraseña FROM usuarios WHERE correo = :correo";
    $stmt = $conexion->prepare($sql);
    $stmt->execute(['correo' => $correo]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo "2. Usuario administrador encontrado: <b>" . $user['correo'] . "</b><br>";
        
        // 3. Probar contraseña 'admin123'
        $pass_test = 'admin123';
        if (password_verify($pass_test, $user['contraseña'])) {
            echo "3. Verificación de contraseña 'admin123': <b style='color:green;'>¡ÉXITO!</b><br>";
        } else {
            echo "3. Verificación de contraseña 'admin123': <b style='color:red;'>FALLIDO</b><br>";
            echo "Hash en DB: " . $user['contraseña'] . "<br>";
        }
    } else {
        echo "2. Usuario administrador: <b style='color:red;'>NO ENCONTRADO</b><br>";
        
        // Listar todos los usuarios para ver qué hay
        echo "<br><b>Usuarios actuales en la base de datos:</b><br>";
        $all = $conexion->query("SELECT correo FROM usuarios")->fetchAll(PDO::FETCH_ASSOC);
        foreach($all as $u) echo "- " . $u['correo'] . "<br>";
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}

<?php
require_once 'conexion.php';
$res = $conexion->query("SHOW TABLES");
while($row = $res->fetch_row()) {
    echo "TABLE: " . $row[0] . "\n";
    $res2 = $conexion->query("DESCRIBE " . $row[0]);
    while($row2 = $res2->fetch_assoc()) {
        print_r($row2);
    }
    echo "------------------\n";
}
?>

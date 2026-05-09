<?php
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host_url = $_SERVER['HTTP_HOST'];
$script_name = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$base_path = ($script_name == '/' || $script_name == '\\') ? '' : $script_name;

echo "Protocol: $protocol\n";
echo "Host: $host_url\n";
echo "Script Name: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "Dirname: " . dirname($_SERVER['SCRIPT_NAME']) . "\n";
echo "Base Path: $base_path\n";
echo "BASE_URL: " . $protocol . "://" . $host_url . $base_path . "/\n";
?>

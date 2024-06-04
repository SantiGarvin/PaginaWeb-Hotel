<?php
$servername = "localhost";
$username = "santicolle2324";
$password = "CWhJBacwdinsATcQ";
$dbname = "santicolle2324";
$charset = 'utf8mb4';


$conn = new mysqli($servername, $username, $password);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}


$conn->select_db($dbname);
?>

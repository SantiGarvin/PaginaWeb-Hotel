<?php
$servername = "localhost";
$username = "santicolle2324";
$password = "CWhJBacwdinsATcQ";
$dbname = "santicolle2324";

// Crear conexión inicial
$conn = new mysqli($servername, $username, $password);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// // Crear la base de datos
// $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
// if ($conn->query($sql) === TRUE) {
//     echo "Base de datos creada exitosamente o ya existía.";
// } else {
//     die("Error al crear la base de datos: " . $conn->error);
// }

$conn->select_db($dbname);
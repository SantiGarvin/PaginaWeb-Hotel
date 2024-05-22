<?php
$servername = "localhost";
$username = "santicolle2324";
$password = "CWhJBacwdinsATcQ";
$dbname = "ProyectoFinal";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
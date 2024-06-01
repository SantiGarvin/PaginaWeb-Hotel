<?php
$servername = "localhost";
$username = "santicolle2324";
$password = "CWhJBacwdinsATcQ";
$dbname = "santicolle2324";
$charset = 'utf8mb4';

/*
$dsn = "mysql:host=$servername;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
// echo "Conexión realizada con éxito.";
*/

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
?>

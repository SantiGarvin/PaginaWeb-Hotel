<?php
// Incluir el archivo de conexión a la base de datos
include 'db-connection.php'; // Asegúrate de que este archivo contiene la conexión global $conn
include 'cifrar_claves.php';

global $conn;

// Drop all tables from the database
$conn->query("DROP TABLE IF EXISTS Reservas");
$conn->query("DROP TABLE IF EXISTS Fotografias");
$conn->query("DROP TABLE IF EXISTS Logs");
$conn->query("DROP TABLE IF EXISTS Usuarios");
$conn->query("DROP TABLE IF EXISTS Habitaciones");


// Ruta al archivo SQL
$filePath = '../sql/backup.sql';

// Verificar si el archivo existe
if (!file_exists($filePath)) {
    die("El archivo SQL no existe.");
}

// Leer el contenido del archivo SQL
$sql = file_get_contents($filePath);

if ($sql === false) {
    die("Error al leer el archivo SQL.");
}

// Dividir el contenido en múltiples consultas
$queries = explode(';', $sql);

// Desactivar autocommit
$conn->autocommit(FALSE);

try {
    // Ejecutar cada consulta
    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query)) {
            if ($conn->query($query) === FALSE) {
                throw new Exception("Error al ejecutar la consulta: " . $conn->error);
            }
        }
    }
    // Confirmar la transacción
    $conn->commit();
    echo "Restauración de la base de datos completada con éxito.";
} catch (Exception $e) {
    // Revertir la transacción en caso de error
    $conn->rollback();
    echo "Falló la restauración de la base de datos: " . $e->getMessage();
} finally {
    // Restaurar autocommit
    $conn->autocommit(TRUE);
}

cifrarClaves();

header('Location: ../index.php');

<?php
include('db-connection.php');

function procesarSubidaFotos($conn, $id_habitacion) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['foto'])) {
        $fileName = $_FILES['foto']['name'];
        $fileTmpName = $_FILES['foto']['tmp_name'];
        $fileType = $_FILES['foto']['type'];

        if (strpos($fileType, 'image') !== false) {
            $fileContent = file_get_contents($fileTmpName);
            $query = "INSERT INTO Fotografias (id_habitacion, nombre_archivo, imagen) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('iss', $id_habitacion, $fileName, $fileContent);
            $stmt->execute();
        }
    }
}

function obtenerFotos($conn, $id_habitacion) {
    $query = "SELECT * FROM Fotografias WHERE id_habitacion=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id_habitacion);
    $stmt->execute();
    return $stmt->get_result();
}

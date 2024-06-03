<?php
include('db-connection.php');

$images = [
    'habitacion_doble.jpg' => 'Habitación Doble',
    'habitacion_individual.jpg' => 'Habitación Individual',
    'habitacion_suite.jpg' => 'Habitación Suite'
];

foreach ($images as $filename => $description) {
    $filePath = 'img/' . $filename;
    if (file_exists($filePath)) {
        $fileContent = file_get_contents($filePath);
        $fileType = mime_content_type($filePath);

        if (strpos($fileType, 'image') !== false) {
            $id_habitacion = getHabitacionIdByDescription($description, $conn);
            if ($id_habitacion) {
                $query = "INSERT INTO Fotografias (id_habitacion, nombre_archivo, imagen) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('iss', $id_habitacion, $filename, $fileContent);
                $stmt->execute();
            } else {
                echo "Habitación no encontrada para la descripción: $description<br>";
            }
        } else {
            echo "El archivo no es una imagen: $filename<br>";
        }
    } else {
        echo "El archivo no existe: $filename<br>";
    }
}

function getHabitacionIdByDescription($description, $conn) {
    $query = "SELECT id_habitacion FROM Habitaciones WHERE descripcion LIKE ?";
    $stmt = $conn->prepare($query);
    $description = "%" . $description . "%";
    $stmt->bind_param('s', $description);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row['id_habitacion'];
    }
    return false;
}

echo "Carga de imágenes completada.";

?>
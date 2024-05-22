<?php

require_once 'db-connection.php';

// Función para ejecutar archivos SQL
function ejecutarSQLArchivo($conn, $archivo) {
    $sql = file_get_contents($archivo);
    if ($conn->multi_query($sql)) {
        do {
            // Store first result set
            if ($result = $conn->store_result()) {
                $result->free();
            }
            // If there are more result-sets, the print a divider
            if ($conn->more_results()) {
                printf("-----------------\n");
            }
        } while ($conn->more_results() && $conn->next_result());
    } else {
        echo "Error al ejecutar el archivo $archivo: " . $conn->error;
    }
}

// Listado de archivos SQL
$sqlFiles = [
    '../sql/usuarios.sql',
    '../sql/habitaciones.sql',
    '../sql/fotografias.sql',
    '../sql/reservas.sql',
    '../sql/logs.sql'
];

// Ejecutar cada archivo SQL
foreach ($sqlFiles as $file) {
    ejecutarSQLArchivo($conn, $file);
}

echo "Tablas creadas exitosamente.";

$conn->close();
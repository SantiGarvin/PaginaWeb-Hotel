<?php

require_once 'db-connection.php';


function cifrarClaves() {
    global $conn;
    // Solo ejecuta esto una vez para encriptar las contraseñas existentes
    $result = $conn->query('SELECT id_usuario, clave FROM Usuarios');
    while ($row = $result->fetch_assoc()) {
        $hashed_password = password_hash($row['clave'], PASSWORD_DEFAULT);
        $update_stmt = $conn->prepare('UPDATE Usuarios SET clave = ? WHERE id_usuario = ?');
        $update_stmt->bind_param('si', $hashed_password, $row['id_usuario']);
        $update_stmt->execute();
    }

    echo "Contraseñas actualizadas.";
}

// Función para ejecutar archivos SQL
function ejecutarSQLArchivo($conn, $archivo) {
    $sql = file_get_contents($archivo);
    if ($conn->multi_query($sql)) {
        do {
            // Store first result set
            if ($result = $conn->store_result()) {
                $result->free();
            }
            // // If there are more result-sets, then print a divider
            // if ($conn->more_results()) {
            //     printf("-----------------\n");
            // }
        } while ($conn->more_results() && $conn->next_result());
    } else {
        echo "Error al ejecutar el archivo $archivo: " . $conn->error;
    }
}

// Listado de archivos SQL
$sqlFiles = [
    '../sql/tablas.sql'
];

// Ejecutar cada archivo SQL
foreach ($sqlFiles as $file) {
    ejecutarSQLArchivo($conn, $file);
}

echo "Tablas creadas exitosamente.";

cifrarClaves();

?>
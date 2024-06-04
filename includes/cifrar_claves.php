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

?>
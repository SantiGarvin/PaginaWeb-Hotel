<?php
require_once 'db-connection.php'; // Asegúrate de tener este archivo configurado correctamente

// Solo ejecuta esto una vez para encriptar las contraseñas existentes
$stmt = $pdo->query('SELECT id_usuario, clave FROM Usuarios');
while ($row = $stmt->fetch()) {
    $hashed_password = password_hash($row['clave'], PASSWORD_DEFAULT);
    $update_stmt = $pdo->prepare('UPDATE Usuarios SET clave = :clave WHERE id_usuario = :id_usuario');
    $update_stmt->execute(['clave' => $hashed_password, 'id_usuario' => $row['id_usuario']]);
}

echo "Contraseñas actualizadas.";
?>

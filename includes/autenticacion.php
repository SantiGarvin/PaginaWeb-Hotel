<?php

require_once 'db-connection.php'; // Asegúrate de tener este archivo configurado correctamente



if (!isset($_POST['username'], $_POST['password'])) {
    // Si no hay datos, redirigir al formulario de login
    header('Location: index.php');
    exit();
}

$username = $_POST['username'];
$password = $_POST['password'];

// Consulta para verificar las credenciales del usuario
$stmt = $pdo->prepare('SELECT id_usuario, clave, rol FROM Usuarios WHERE email = :email');
$stmt->execute(['email' => $username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['clave'])) {
    // Credenciales válidas, actualizar la sesión
    session_regenerate_id();
    $_SESSION['tipo_usuario'] = $user['rol'];
    $_SESSION['user_id'] = $user['id_usuario'];
    $_SESSION['name'] = $username;
    header('Location: index.php');
    exit();
} else {
    // Credenciales inválidas, redirigir con error
    header('Location: index.php?error=invalid_credentials');
    exit();
}
?>

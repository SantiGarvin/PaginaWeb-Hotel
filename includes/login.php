<?php
require_once 'db-connection.php'; // Asegúrate de tener este archivo configurado correctamente

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consulta para verificar las credenciales del usuario
    $stmt = $pdo->prepare('SELECT id_usuario, clave, rol FROM Usuarios WHERE email = :email');
    $stmt->execute(['email' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['clave'])) {
        // Credenciales válidas, actualizar la sesión
        $_SESSION['user_id'] = $user['id_usuario'];
        $_SESSION['tipo_usuario'] = $user['rol'];
        header('Location: index.php');
        exit();
    } else {
        // Credenciales inválidas, redirigir con error
        header('Location: index.php?error=invalid_credentials');
        exit();
    }
}
?>

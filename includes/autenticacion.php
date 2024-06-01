<?php
require_once 'db-connection.php'; 
require_once 'Session.php'; 

function autenticacion(){
    if (!isset($_POST['username'], $_POST['password'])) {
        // No se enviaron los datos necesarios
        echo 'Por favor, complete ambos campos correctamente';
        exit();
    }
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Consulta para verificar las credenciales del usuario
    $stmt = $pdo->prepare('SELECT id_usuario, clave, rol FROM Usuarios WHERE email = :username');
    $stmt->execute(['email' => $username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['clave'])) {
        // Credenciales válidas, actualizar la sesión
        Session::init();
        Session::set('tipo_usuario', $user['rol']);
        Session::set('user_id', $user['id_usuario']);
        Session::set('name', $username);
        header('Location: index.php');
        exit();
    } else {
        // Credenciales inválidas, redirigir con error
        header('Location: index.php?error=invalid_credentials');
        exit();
    }
}

?>

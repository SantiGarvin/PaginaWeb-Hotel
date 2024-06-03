<?php
require_once 'db-connection.php'; 
require_once 'Session.php'; 

function autenticacion(){
    global $conn;

    if (!isset($_POST['username'], $_POST['password'])) {
        // No se enviaron los datos necesarios
        echo 'Por favor, complete ambos campos correctamente';
        exit();
    }
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Consulta para verificar las credenciales del usuario
    $stmt = $conn->prepare('SELECT id_usuario, nombre, apellidos, dni, email, num_tarjeta_credito, clave, rol FROM Usuarios WHERE email = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user && password_verify($password, $user['clave'])) {
        // Credenciales válidas, actualizar la sesión
        Session::init();
        Session::set('user', $user);
    } else {
        // Credenciales inválidas, redirigir con error
        return false;
    }
    
    return true;
}

?>

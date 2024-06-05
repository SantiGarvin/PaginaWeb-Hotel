<?php
require_once 'includes/Session.php';
require_once 'includes/db-connection.php';

function createLogIdentificacion($userId) {
    global $conn;

    $description = "Usuario logged in";
    $query = "INSERT INTO Logs (id_usuario, descripcion) VALUES (?, ?)";
    $statement = $conn->prepare($query);
    $statement->bind_param("is", $userId, $description);
    $statement->execute();
    $statement->close();
}

function createLogDesidentificacion($userId) {
    global $conn;

    $description = "Usuario logged out";
    $query = "INSERT INTO Logs (id_usuario, descripcion) VALUES (?, ?)";
    $statement = $conn->prepare($query);
    $statement->bind_param("is", $userId, $description);
    $statement->execute();
    $statement->close();
}


function createLogRegistro($userId) {
    global $conn;

    $description = "Usuario registrado";
    $query = "INSERT INTO Logs (id_usuario, descripcion) VALUES (?, ?)";
    $statement = $conn->prepare($query);
    $statement->bind_param("is", $userId, $description);
    $statement->execute();
    $statement->close();
}

function createLogAccion($userId, $accion) {
    global $conn;

    $description = "Usuario realizó la acción: " . $accion;
    $query = "INSERT INTO Logs (id_usuario, descripcion) VALUES (?, ?)";
    $statement = $conn->prepare($query);
    $statement->bind_param("is", $userId, $description);
    $statement->execute();
    $statement->close();
}


?>

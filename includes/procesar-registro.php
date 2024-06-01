<?php

require_once 'registro.php';

function validateDNI($dni) {
    $numbers = substr($dni, 0, -1);
    $letter = substr($dni, -1);
    $validLetters = "TRWAGMYFPDXBNJZSQVHLCKE";
    return $validLetters[$numbers % 23] === $letter;
}

function validateCreditCard($number) {
    $number = str_replace(' ', '', $number);
    $sum = 0;
    for ($i = 0; $i < strlen($number); $i++) {
        $digit = $number[strlen($number) - $i - 1];
        if ($i % 2 == 1) {
            $digit *= 2;
            if ($digit > 9) {
                $digit -= 9;
            }
        }
        $sum += $digit;
    }
    return $sum % 10 == 0;
}

$errores = [];

$datos = [
    'nombre' => $_POST['nombre'] ?? '',
    'apellidos' => $_POST['apellidos'] ?? '',
    'dni' => $_POST['dni'] ?? '',
    'fecha_nacimiento' => $_POST['fecha_nacimiento'] ?? '',
    'nacionalidad' => $_POST['nacionalidad'] ?? 'España',
    'tarjetaC' => $_POST['tarjetaC'] ?? '',
    'correo' => $_POST['correo'] ?? '',
    'password' => $_POST['password'] ?? '',
    'password2' => $_POST['password2'] ?? ''
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validar nombre
    if (empty($datos['nombre'])) {
        $errores['nombre'] = "El nombre es obligatorio";
    }

    // Validar apellidos
    if (empty($datos['apellidos'])) {
        $errores['apellidos'] = "Los apellidos son obligatorios";
    }

    // Validar DNI
    if (!preg_match("/^[0-9]{8}[A-Z]$/", $datos['dni']) || !validateDNI($datos['dni'])) {
        $errores['dni'] = "El DNI no es válido";
    }

    // Validar tarjeta de crédito
    if (!validateCreditCard($datos['tarjetaC'])) {
        $errores['tarjetaC'] = "La tarjeta de crédito no es válida";
    }

    // Validar email
    if (!filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)) {
        $errores['correo'] = "El email no es válido";
    }

    // Validar contraseña
    if (strlen($datos['password']) < 5) {
        $errores['password'] = "La contraseña debe tener al menos 5 caracteres";
    } elseif ($datos['password'] !== $datos['password2']) {
        $errores['password'] = "Las contraseñas no coinciden";
    }

    if (empty($errores)) {
        // Aquí puedes insertar los datos en la base de datos o realizar otras acciones necesarias
        echo "Registro exitoso.";
    } else {
        echo HTMLregistro($datos, $errores);
    }
}
?>

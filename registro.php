<?php

require_once 'includes/db-connection.php';
require_once 'includes/log.php';

function obtenerIdUsuario($correo) {
    global $conn;

    $sql = "SELECT id_usuario FROM Usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // El usuario existe, devuelve su ID
        $row = $result->fetch_assoc();
        return $row['id_usuario'];
    } else {
        // El usuario no existe
        return null;
    }
}

function validarDNI($dni)
{
    $numbers = substr($dni, 0, -1);
    $letter = substr($dni, -1);
    $validLetters = "TRWAGMYFPDXBNJZSQVHLCKE";
    return $validLetters[$numbers % 23] === $letter;
}

function validarTarjetaCredito($number)
{
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

function validarDatos($datos)
{
    $errores = [];

    // Validar nombre
    if (empty($datos['nombre'])) {
        $errores['nombre'] = "El nombre es obligatorio";
    }

    // Validar apellidos
    if (empty($datos['apellidos'])) {
        $errores['apellidos'] = "Los apellidos son obligatorios";
    }

    // Validar DNI
    if (!preg_match("/^[0-9]{8}[A-Z]$/", $datos['dni']) || !validarDNI($datos['dni'])) {
        $errores['dni'] = "El DNI no es válido";
    }

    // Validar tarjeta de crédito
    if (!validarTarjetaCredito($datos['tarjetaC'])) {
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

    return $errores;
}

function createUser($data)
{
    global $conn;

    if ($data['id']) {
        $query = "UPDATE Usuarios SET nombre = ?, apellidos = ?, dni = ?, email = ?, clave = ?, num_tarjeta_credito = ?, rol = ? WHERE id_usuario = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssssssi', $data['nombre'], $data['apellidos'], $data['dni'], $data['email'], $hashed_password, $data['tarjetaC'], $data['role'], $data['id']);
    } else {
        $query = "INSERT INTO Usuarios (nombre, apellidos, dni, email, clave, num_tarjeta_credito, rol) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $hashed_password = password_hash($data['password'], PASSWORD_BCRYPT);
        $stmt->bind_param('sssssss', $data['nombre'], $data['apellidos'], $data['dni'], $data['email'], $hashed_password, $data['tarjetaC'], $data['role']);
    }

    if ($stmt->execute()) {
        return $conn->insert_id;
    } else {
        return false;
    }
}

function procesarRegistro(&$datos, &$errores, &$confirmacion)
{
    global $conn;
    if(isset($_POST['accion']) && $_POST['accion'] == 'editar') {
        $id = $_POST['id'];

        // Obtener los datos del usuario con el id especificado
        $query = "SELECT * FROM Usuarios WHERE id_usuario = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            $datos = [
                'id' => $_POST['id'],
                'nombre' => $row['nombre'] ?? '',
                'apellidos' => $row['apellidos'] ?? '',
                'dni' => $row['dni'] ?? '',
                'tarjetaC' => $row['num_tarjeta_credito'] ?? '',
                'correo' => $row['email'] ?? '',
                'password' => $row['clave'] ?? '',
                'password2' => $row['clave'] ?? ''
            ];
        } else {
            $errores = ['general' => 'No se encontró el usuario especificado'];
        }
    }else{
        $datos = [
            'nombre' => $_POST['nombre'] ?? '',
            'apellidos' => $_POST['apellidos'] ?? '',
            'dni' => $_POST['dni'] ?? '',
            'tarjetaC' => $_POST['tarjetaC'] ?? '',
            'correo' => $_POST['correo'] ?? '',
            'password' => $_POST['password'] ?? '',
            'password2' => $_POST['password2'] ?? ''
        ];
    }


    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        if (!isset($_POST['confirmar'])) {
            $errores = validarDatos($datos);

            if (empty($errores)) {
                $confirmacion = true;
            }
        } else {
            if (empty($errores)) {

                if($_POST['accion'] == 'editar'){
                    $userData = [
                        'id' => $datos['id'],
                        'nombre' => $datos['nombre'],
                        'apellidos' => $datos['apellidos'],
                        'dni' => $datos['dni'],
                        'email' => $datos['correo'],
                        'password' => $datos['password'],
                        'tarjetaC' => $datos['tarjetaC'],
                        'role' => 'Cliente' // Aquí puedes asignar el rol que necesites //se tiene que poder editar
                    ];
                }else{
                    // Preparar los datos para la función createUser
                    $userData = [
                        'nombre' => $datos['nombre'],
                        'apellidos' => $datos['apellidos'],
                        'dni' => $datos['dni'],
                        'email' => $datos['correo'],
                        'password' => $datos['password'],
                        'tarjetaC' => $datos['tarjetaC'],
                        'role' => 'Cliente' // Aquí puedes asignar el rol que necesites
                    ];
                }

                // Llamar a la función createUser
                $userId = createUser($userData);

                if ($userId) {
                    // Crear un log de registro
                    if(Session::get('user')['id_usuario']){
                        $id_para_log = Session::get('user')['id_usuario'];
                    }else{
                        $id_para_log = obtenerIdUsuario($userData['email']);
                    }
                    createLogRegistro($id_para_log);
                    header("Location: index.php");
                    // exit();
                } else {
                    // Manejar el error en caso de que la creación del usuario falle
                    $errores['general'] = "Error al crear el usuario. Inténtelo de nuevo más tarde.";
                }
            }
        }
    }
}

// Mostrar el formulario HTML
function HTMLregistro($datos = [], $errores = [], $confirmacion = false)
{

    procesarRegistro($datos, $errores, $confirmacion);
    ob_start(); // Iniciar el output buffering
?>
    <main class="main-content">
        <form action="" method="POST" enctype="multipart/form-data" novalidate>
            <input type="hidden" id="version_formulario" name="version_formulario" value="1.0">

            <?php if ($confirmacion ) : ?>
                <fieldset class="datos-personales">
                    <legend>Confirmación de datos personales</legend>
                    <div class="fila">
                        <div class="columna columna-nombre-apellidos">
                            <label for="nombre">
                                Nombre:
                                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($datos['nombre']) ?>" $readonly>
                            </label>

                            <label for="apellidos">
                                Apellidos:
                                <input type="text" id="apellidos" name="apellidos" value="<?= htmlspecialchars($datos['apellidos']) ?>" $readonly>
                            </label>
                        </div>
                    </div>
                    <div class="fila">
                        <div class="columna">
                            <label for="dni">
                                DNI:
                                <input type="text" id="dni" name="dni" value="<?= htmlspecialchars($datos['dni']) ?>" $readonly>
                            </label>

                        </div>
                        <div class="columna">
                            <label for="tarjetaC">
                                Tarjeta:
                                <input type="text" id="tarjetaC" name="tarjetaC" value="<?= htmlspecialchars($datos['tarjetaC']) ?>" $readonly>
                            </label>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="datos-acceso">
                    <legend>Confirmación de datos de acceso</legend>
                    <div class="fila correo">
                        <div class="columna">
                            <label for="correo">
                                E-mail:
                                <input type="email" id="correo" name="correo" value="<?= htmlspecialchars($datos['correo']) ?>" $readonly>
                            </label>
                        </div>
                    </div>
                    <div class="fila clave">
                        <div class="columna">
                            <label for="password">
                                Clave:
                                <input type="password" id="password" name="password" value="<?= htmlspecialchars($datos['password']) ?>" $readonly>
                            </label>
                        </div>
                    </div>
                </fieldset>
                <input type="hidden" name="confirmar" value="1">
                <input type="submit" name="enviar" value="Confirmar datos">
            <?php else : ?>
                <fieldset class="datos-personales">
                    <legend>Datos personales</legend>
                    <div class="fila">
                        <div class="columna columna-nombre-apellidos">
                            <label for="nombre">
                                Nombre:
                                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($datos['nombre'] ?? '') ?>" placeholder="(Obligatorio)" required size="20" maxlength="40">
                            </label>
                            <?php if (!empty($errores['nombre'])) : ?>
                                <span class="error"><?= htmlspecialchars($errores['nombre']) ?></span>
                            <?php endif; ?>

                            <label for="apellidos">
                                Apellidos:
                                <input type="text" id="apellidos" name="apellidos" value="<?= htmlspecialchars($datos['apellidos'] ?? '') ?>" title="Este campo es opcional">
                            </label>
                            <?php if (!empty($errores['apellidos'])) : ?>
                                <span class="error"><?= htmlspecialchars($errores['apellidos']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="fila">
                        <div class="columna">
                            <label for="dni">
                                DNI:
                                <input type="text" id="dni" name="dni" value="<?= htmlspecialchars($datos['dni'] ?? '') ?>" placeholder="12345678A" required pattern="[0-9]{8}[A-Z]">
                            </label>
                            <?php if (!empty($errores['dni'])) : ?>
                                <span class="error"><?= htmlspecialchars($errores['dni']) ?></span>
                            <?php endif; ?>

                        </div>
                        <div class="columna">
                            <label for="tarjetaC">
                                Tarjeta:
                                <input type="text" id="tarjetaC" name="tarjetaC" value="<?= htmlspecialchars($datos['tarjetaC'] ?? '') ?>" placeholder="#### #### #### ####" required pattern="[0-9]{16}">
                            </label>
                            <?php if (!empty($errores['tarjetaC'])) : ?>
                                <span class="error"><?= htmlspecialchars($errores['tarjetaC']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="datos-acceso">
                    <legend>Datos de acceso</legend>
                    <div class="fila correo">
                        <div class="columna">
                            <label for="correo">
                                E-mail:
                                <input type="email" id="correo" name="correo" value="<?= htmlspecialchars($datos['correo'] ?? '') ?>" placeholder="correo@example.com" required>
                            </label>
                            <?php if (!empty($errores['correo'])) : ?>
                                <span class="error"><?= htmlspecialchars($errores['correo']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="fila clave">
                        <div class="columna">
                            <label for="password">
                                Clave:
                                <input type="password" id="password" name="password" placeholder="Introduzca una clave" required>
                            </label>
                            <?php if (!empty($errores['password'])) : ?>
                                <span class="error"><?= htmlspecialchars($errores['password']) ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="columna">
                            <label for="password2">
                                Repita clave:
                                <input type="password" id="password2" name="password2" placeholder="Escriba la misma clave" required>
                            </label>
                        </div>
                    </div>
                </fieldset>
                <input type="submit" name="enviar" value="Enviar datos">
            <?php endif; ?>
        </form>
    </main>
<?php
    return ob_get_clean(); // Obtener el contenido del buffer y limpiarlo
}
?>
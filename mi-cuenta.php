<?php

require_once 'includes/db-connection.php';
require_once 'includes/Session.php';
require_once 'registro.php';
require_once 'includes/log.php';

function HTMLmicuenta()
{
    global $conn;

    $nombre = isset(Session::get('user')['nombre']) ? Session::get('user')['nombre'] : '';
    $apellidos = isset(Session::get('user')['apellidos']) ? Session::get('user')['apellidos'] : '';
    $dni = isset(Session::get('user')['dni']) ? Session::get('user')['dni'] : '';
    $email = isset(Session::get('user')['email']) ? Session::get('user')['email'] : '';
    $tarjeta = isset(Session::get('user')['num_tarjeta_credito']) ? Session::get('user')['num_tarjeta_credito'] : '';
    $password = isset(Session::get('user')['clave']) ? Session::get('user')['clave'] : '';
    
    // Actualizar datos personales/////////////////////

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar'])) {
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $dni = $_POST['dni'];
        $email = $_POST['correo'];
        $tarjeta = $_POST['tarjetaC'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];


        $errores = validarDatos($_POST);
        if (count($errores) === 0) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $sql = "UPDATE Usuarios SET nombre = ?, apellidos = ?, dni = ?, email = ?, num_tarjeta_credito = ?, clave = ? WHERE id_usuario = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssi", $nombre, $apellidos, $dni, $email, $tarjeta, $hashed_password, Session::get('user')['id_usuario']);
            $stmt->execute();

            // Actualizar los datos de la sesión
            $user = Session::get('user');
            $user['nombre'] = $nombre;
            $user['apellidos'] = $apellidos;
            $user['dni'] = $dni;
            $user['email'] = $email;
            $user['num_tarjeta_credito'] = $tarjeta;
            Session::set('user', $user);
            echo "<p>Datos actualizados correctamente.</p>";
            createlogAccion(Session::get('user')['id_usuario'], 'Actualización de datos personales');
        } else {
            // Manejar los errores
            foreach ($errores as $campo => $mensaje) {
                echo "<p>Error en el campo $campo: $mensaje</p>";
            }
        }
    }


    // Obtener reservas del usuario/////////////////////
    $sql = "SELECT id_reserva, dia_entrada, dia_salida, estado, comentarios, num_personas FROM Reservas WHERE id_cliente = ?";    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", Session::get('user')['id_usuario']);
    $stmt->execute();
    $result = $stmt->get_result();

    // Iniciar la cadena de la tabla
    $tableRows = "";

    // Mostrar reservas
    while ($row = $result->fetch_assoc()) {
        $tableRows .= "<tr>
                            <td>{$row['id_reserva']}</td>
                            <td>{$row['dia_entrada']}</td>
                            <td>{$row['dia_salida']}</td>
                            <td>{$row['estado']}</td>
                            <td>{$row['comentarios']}</td>
                            <td>{$row['num_personas']}</td>
                            <td>
                                <form method='post' action=''>
                                    <input type='hidden' name='id' value='{$row['id_reserva']}'>
                                    <button type='submit' name='accion' value='cancelar'>Cancelar</button>
                                </form>
                                <form method='post' action='index.php?p=5'>
                                    <input type='hidden' name='id' value='{$row['id_reserva']}'>
                                    <button type='submit' name='accion' value='modificar'>Modificar</button>
                                </form>
                            </td>
                        </tr>";
    }

    //Funcion para cancelar reserva
    if (isset($_POST['accion']) && $_POST['accion'] == 'cancelar') {
        $id_reserva = $_POST['id'];
        $sql = "DELETE FROM Reservas WHERE id_reserva = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_reserva);
        $stmt->execute();
        $stmt->close();
        createlogAccion(Session::get('user')['id_usuario'], 'Cancelación de reserva');
        header('Location: ' . $_SERVER['REQUEST_URI']);
    }

    //Funcion para modificar reserva
    if (isset($_POST['accion']) && $_POST['accion'] == 'modificar') {
        $id_reserva = $_POST['id'];
    }

    return <<<HTML
    <main class="main-content">
        <section class="seccion-datos">
            <h2>Gestión de Datos Personales</h2>
            <p>En esta sección puede modificar sus datos personales y de acceso.</p>
            <form method="post" action="" novalidate>
                <fieldset class="datos-personales">
                    <legend>Datos personales</legend>
                    <div class="fila">
                        <div class="columna columna-nombre-apellidos">
                            <label for="nombre">
                                Nombre:
                                <input type="text" id="nombre" name="nombre" placeholder="(Obligatorio)" required size="20" maxlength="40" value="$nombre" readonly>
                            </label>
                            <label for="apellidos">
                                Apellidos:
                                <input type="text" id="apellidos" name="apellidos" title="Este campo es opcional" value="$apellidos" readonly>
                            </label>
                        </div>
                    </div>
                    <div class="fila">
                        <div class="columna">
                            <label for="dni">
                                DNI:
                                <input type="text" id="dni" name="dni" placeholder="12345678A" required pattern="[0-9]{8}[A-Z]" value="$dni" readonly>
                            </label>

                        </div>
                        <div class="columna">
                            <label for="dni">
                                Tarjeta:
                                <input type="text" id="dni" name="tarjetaC" placeholder="#### #### #### ####" required pattern="[0-9]{12}" value="$tarjeta" >
                            </label>
                        </div>
                    </div>
                    <span class="texto-hover texto-personales">En cumplimiento del Real Decreto 933/2021, de 26 de octubre, estos datos serán comunicados al centro de datos de la Dirección General de la Policía.</span>
                </fieldset>
                <fieldset class="datos-acceso">
                    <legend>Datos de acceso</legend>
                    <div class="fila correo">
                        <div class="columna">
                            <label for="correo">
                                E-mail:
                                <input type="email" id="correo" name="correo" placeholder="correo@example.com" value="$email" required>
                            </label>
                        </div>
                    </div>
                    <div class="fila clave">
                        <div class="columna">
                            <label for="password">
                                Clave:
                                <input type="password" id="password" name="password" placeholder="Introduzca una clave" required>
                            </label>
                        </div>
                        <div class="columna">
                            <label for="password2">
                                Repita clave:
                                <input type="password" id="password2" name="password2" placeholder="Escriba la misma clave" required>
                            </label>
                        </div>
                    </div>
                    <span class="texto-hover texto-acceso">Usted podrá acceder al sistema en cualquier momento mediante estos datos. Asegúrese de escribir una clave que pueda recordar con posterioridad. Si la olvida siempre podrá recuperarla a través de su correo electrónico.</span>
                </fieldset>
                <input type="submit" name="enviar" value="Enviar datos">
            </form>
        </section>

        <section class="seccion-reservas">
            <h2>Gestión de Reservas</h2>
            <form method="post" action="index.php?p=5">
                <button type="submit" name="accion" value="realizar">Realizar una reserva</button>
            </form>
            <table>
                <thead>
                    <tr>
                        <th>ID Reserva</th>
                        <th>Fecha In</th>
                        <th>Fecha Out</th>
                        <th>Estado</th>
                        <th>Comentarios</th>
                        <th>Personas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    {$tableRows}
                </tbody>
            </table>
        </section>
    </main>
HTML;
}

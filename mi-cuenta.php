<?php

require_once 'includes/db-connection.php';
require_once 'includes/Session.php';

function HTMLmicuenta()
{
    global $conn;

    $nombre = Session::get('user')['nombre'];
    $apellidos = Session::get('user')['apellidos'];
    $dni = Session::get('user')['dni'];
    $email = Session::get('user')['email'];
    $tarjeta = Session::get('user')['num_tarjeta_credito'];
    $fecha_nacimiento = Session::get('user')['fecha_nacimiento'];
    $nacionalidad = Session::get('user')['nacionalidad'];




    // Obtener reservas del usuario
    $sql = "SELECT id_reserva, dia_entrada, dia_salida FROM Reservas WHERE id_cliente = ?";
    $stmt = $conn->prepare($sql);
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
                            <td>
                                <form method='post' action='cancelar_reserva.php'>
                                    <input type='hidden' name='id' value='{$row['id']}'>
                                    <button type='submit' name='accion' value='cancelar'>Cancelar</button>
                                </form>
                                <form method='post' action='modificar_reserva.php'>
                                    <input type='hidden' name='id' value='{$row['id']}'>
                                    <button type='submit' name='accion' value='modificar'>Modificar</button>
                                </form>
                            </td>
                        </tr>";
    }

    $stmt->close();
    $conn->close();

    return <<<HTML
    <main class="main-content">
        <section class="seccion-datos">
            <h2>Gestión de Datos Personales</h2>
            <p>En esta sección puede modificar sus datos personales y de acceso.</p>
            <form method="post" action="procesar_datos.php">
                <fieldset class="datos-personales">
                    <legend>Datos personales</legend>
                    <div class="fila">
                        <div class="columna columna-nombre-apellidos">
                            <label for="nombre">
                                Nombre:
                                <input type="text" id="nombre" name="nombre" placeholder="(Obligatorio)" required size="20" maxlength="40" value="$nombre" disabled>
                            </label>
                            <label for="apellidos">
                                Apellidos:
                                <input type="text" id="apellidos" name="apellidos" title="Este campo es opcional" value="$apellidos" disabled>
                            </label>
                        </div>
                    </div>
                    <div class="fila">
                        <div class="columna">
                            <label for="dni">
                                DNI:
                                <input type="text" id="dni" name="dni" placeholder="12345678A" required pattern="[0-9]{8}[A-Z]" value="$dni" disabled>
                            </label>
                            <label for="fecha-nacimiento">
                                F. nacimiento:
                                <input type="date" id="fecha-nacimiento" name="fecha_nacimiento" value="$fecha_nacimiento" required>
                            </label>
                        </div>
                        <div class="columna">
                            <label for="nacionalidad">
                                Nacionalidad:
                                <input type="text" id="nacionalidad" name="nacionalidad" value="España">
                            </label>
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

?>

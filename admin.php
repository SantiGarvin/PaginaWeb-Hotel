<?php 

require_once 'includes/db-connection.php';

function HTMLtusuarios(){
    global $conn;

    // Consulta para obtener todos los usuarios
    $query = "SELECT * FROM Usuarios";
    $result = $conn->query($query);

    // Crear la tabla HTML
    $table = "<table>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>DNI</th>
                    <th>Email</th>
                    <th>Clave</th>
                    <th>Número de tarjeta de crédito</th>
                    <th>Rol</th>
                </tr>";

    // Rellenar la tabla con los datos de los usuarios
    while ($row = $result->fetch_assoc()) {
        $table .= "<tr>
                    <td>{$row['id_usuario']}</td>
                    <td>{$row['nombre']}</td>
                    <td>{$row['apellidos']}</td>
                    <td>{$row['dni']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['clave']}</td>
                    <td>{$row['num_tarjeta_credito']}</td>
                    <td>{$row['rol']}</td>
                </tr>";
    }

    $table .= "</table>";

    return $table;
}


function HTMLthabitaciones() {
    global $conn;

    $query = "SELECT * FROM Habitaciones";
    $result = $conn->query($query);

    $table = "<table>
                <tr>
                    <th>ID</th>
                    <th>Número</th>
                    <th>Capacidad</th>
                    <th>Precio por noche</th>
                    <th>Descripción</th>
                </tr>";

    while ($row = $result->fetch_assoc()) {
        $table .= "<tr>
                    <td>{$row['id_habitacion']}</td>
                    <td>{$row['numero']}</td>
                    <td>{$row['capacidad']}</td>
                    <td>{$row['precio_por_noche']}</td>
                    <td>{$row['descripcion']}</td>
                </tr>";
    }

    $table .= "</table>";

    return $table;
}

function HTMLtfotografias() {
    global $conn;

    $query = "SELECT * FROM Fotografias";
    $result = $conn->query($query);

    $table = "<table>
                <tr>
                    <th>ID</th>
                    <th>ID Habitación</th>
                    <th>Ruta Foto</th>
                </tr>";

    while ($row = $result->fetch_assoc()) {
        $table .= "<tr>
                    <td>{$row['id_fotografia']}</td>
                    <td>{$row['id_habitacion']}</td>
                    <td>{$row['ruta_foto']}</td>
                </tr>";
    }

    $table .= "</table>";

    return $table;
}

function HTMLtreservas() {
    global $conn;

    $query = "SELECT * FROM Reservas";
    $result = $conn->query($query);

    $table = "<table>
                <tr>
                    <th>ID</th>
                    <th>ID Cliente</th>
                    <th>ID Habitación</th>
                    <th>Número de personas</th>
                    <th>Comentarios</th>
                    <th>Día de entrada</th>
                    <th>Día de salida</th>
                    <th>Estado</th>
                    <th>Marca de tiempo</th>
                </tr>";

    while ($row = $result->fetch_assoc()) {
        $table .= "<tr>
                    <td>{$row['id_reserva']}</td>
                    <td>{$row['id_cliente']}</td>
                    <td>{$row['id_habitacion']}</td>
                    <td>{$row['num_personas']}</td>
                    <td>{$row['comentarios']}</td>
                    <td>{$row['dia_entrada']}</td>
                    <td>{$row['dia_salida']}</td>
                    <td>{$row['estado']}</td>
                    <td>{$row['marca_tiempo']}</td>
                </tr>";
    }

    $table .= "</table>";

    return $table;
}

function HTMLtlogs() {
    global $conn;

    $query = "SELECT * FROM Logs";
    $result = $conn->query($query);

    $table = "<table>
                <tr>
                    <th>ID</th>
                    <th>ID Usuario</th>
                    <th>Fecha y hora</th>
                    <th>Descripción</th>
                </tr>";

    while ($row = $result->fetch_assoc()) {
        $table .= "<tr>
                    <td>{$row['id_log']}</td>
                    <td>{$row['id_usuario']}</td>
                    <td>{$row['fecha_hora']}</td>
                    <td>{$row['descripcion']}</td>
                </tr>";
    }

    $table .= "</table>";

    return $table;
}

function HTMLadmin(){
    $usuarios = HTMLtusuarios();
    $habitaciones = HTMLthabitaciones();
    $fotografias = HTMLtfotografias();
    $reservas = HTMLtreservas();
    $logs = HTMLtlogs();

    return <<<HTML
    <main class="main-content">
        <section class="seccion-datos">
            <h2>Administración de la base de datos</h2>
            <p>En esta sección puede gestionar los datos de la base de datos.</p>
            <h3>Usuarios</h3>
            $usuarios
            <h3>Habitaciones</h3>
            $habitaciones
            <h3>Fotografías</h3>
            $fotografias
            <h3>Reservas</h3>
            $reservas
            <h3>Logs</h3>
            $logs
        </section>
    </main>
HTML;
}

?>
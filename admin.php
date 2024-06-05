<?php 

require_once 'includes/db-connection.php';
require_once 'includes/cifrar_claves.php';

function HTMLtusuarios(){
    global $conn;
    $sql = "SELECT id_usuario, nombre, apellidos, dni, email, rol, clave FROM Usuarios";
    $result = $conn->query($sql);
    $output = "<table><tr><th>ID</th><th>Nombre</th><th>Apellidos</th><th>DNI</th><th>Email</th><th>Rol</th><th>Acciones</th></tr>";
    while ($row = $result->fetch_assoc()) {
        $output .= "<tr><td>{$row['id_usuario']}</td><td>{$row['nombre']}</td><td>{$row['apellidos']}</td><td>{$row['dni']}</td><td>{$row['email']}</td><td>{$row['rol']}</td>";
        $output .= "<td>
                        <form method='post' action=''>
                            <input type='hidden' name='id' value='{$row['id_usuario']}'>
                            <button type='submit' name='accion' value='eliminar'>Eliminar</button>
                        </form>
                        <form method='post' action=''>
                            <input type='hidden' name='id' value='{$row['id_usuario']}'>
                            <button type='submit' name='accion' value='editar'>Editar</button>
                        </form>
                    </td></tr>";
    }
    $output .= "</table>";
    return $output;
}
function editUserForm($id)
{
    global $conn;
    $sql = "SELECT nombre, apellidos, dni, email, num_tarjeta_credito, rol, clave FROM Usuarios WHERE id_usuario=$id";
    $result = $conn->query($sql);
    if ($row = $result->fetch_assoc()) {
        return '
        <h2>Editar Usuario</h2>
        <form action="" method="post" novalidate>
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="' . $row['nombre'] . '" required>
            <label for="apellidos">Apellidos:</label>
            <input type="text" id="apellidos" name="apellidos" value="' . $row['apellidos'] . '" required>
            <label for="dni">DNI:</label>
            <input type="text" id="dni" name="dni" value="' . $row['dni'] . '" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="' . $row['email'] . '" required>
            <label for="num_tarjeta_credito">Número de Tarjeta de Crédito:</label>
            <input type="text" id="num_tarjeta_credito" name="num_tarjeta_credito" value="' . $row['num_tarjeta_credito'] . '" required>
            <label for="rol">Rol:</label>
            <select id="rol" name="rol">
                <option value="Cliente" ' . ($row['rol'] === 'Cliente' ? 'selected' : '') . '>Cliente</option>
                <option value="Administrador" ' . ($row['rol'] === 'Administrador' ? 'selected' : '') . '>Administrador</option>
                <option value="Recepcionista" ' . ($row['rol'] === 'Recepcionista' ? 'selected' : '') . '>Recepcionista</option>
            </select>
            
            <label for="clave">Clave:</label>
            <input type="password" id="clave" name="clave" required>
            <input type="hidden" name="edicion" value="edicion">
            <input type="hidden" name="id" value="' . $id . '">

            <button type="submit">Guardar</button>
        </form>';
    } else {
        return "<p>Usuario no encontrado.</p>";
    }
}

function updateUser($id)
{
    global $conn;
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $dni = $_POST['dni'];
    $email = $_POST['email'];
    $num_tarjeta_credito = $_POST['num_tarjeta_credito'];
    $rol = $_POST['rol'];
    $clave = $_POST['clave'];

    if (empty($clave)) {
        $sql = "UPDATE Usuarios SET nombre='$nombre', apellidos='$apellidos', dni='$dni', email='$email', num_tarjeta_credito='$num_tarjeta_credito', rol='$rol' WHERE id_usuario=$id AND rol='Cliente'";
    } else {
        $hashed_password = password_hash($clave, PASSWORD_BCRYPT);
        $sql = "UPDATE Usuarios SET nombre='$nombre', apellidos='$apellidos', dni='$dni', email='$email', num_tarjeta_credito='$num_tarjeta_credito', rol='$rol', clave='$hashed_password' WHERE id_usuario=$id AND rol='Cliente'";
    }

    if ($conn->query($sql) === TRUE) {
        return "<p>Cliente actualizado correctamente.</p>";
    } else {
        return "<p>Error al actualizar cliente: " . $conn->error . "</p>";
    }
}
function deleteUser($id)
{
    global $conn;

    $sql = "DELETE FROM Usuarios WHERE id_usuario=$id";

    if ($conn->query($sql) === TRUE) {
        return "<p>Cliente eliminado correctamente.</p>";
    } else {
        return "<p>Error al eliminar cliente: " . $conn->error . "</p>";
    }
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
    $query = "SELECT * FROM Logs ORDER BY id_log DESC LIMIT 20";
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
    $logs = HTMLtlogs();
    $usuarios_edit = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['accion']) && $_POST['accion'] === 'editar' && isset($_POST['id'])) {
            $usuarios_edit = editUserForm($_POST['id']);
        } elseif (isset($_POST['edicion']) && $_POST['edicion'] === 'edicion') {
            $usuarios_edit = updateUser($_POST['id']);
        }elseif (isset($_POST['accion']) && $_POST['accion'] === 'eliminar' && isset($_POST['id'])) {
            $usuarios_edit = deleteUser($_POST['id']);
        }
    }

    $AUX =  <<<HTML
    <main class="main-content">
        <section class="seccion-datos">
            <h2>Administración de la base de datos</h2>
            <p>En esta sección puede gestionar los datos de la base de datos.</p>
            <h3>Usuarios</h3>
            $usuarios
            <a href="index.php?p=3">Añadir usuario</a>
            $usuarios_edit
            <h3>Habitaciones</h3>
            $habitaciones
            <h3>Logs</h3>
            $logs
        </section>
    </main>
HTML;

    return $AUX;
}
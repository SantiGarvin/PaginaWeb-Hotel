<!-- function HTMLreservations() {
    return <<<HTML
        <head>
        <link rel="stylesheet" href="css/styles-reservas.css">
        </head>
        <main class="main-content">
            <h2>Estado de Reservas</h2>
            <div class="reservations">
                <div class="reservation">
                    <div class="reservation__id">1234</div>
                    <div class="reservation__name"><strong>Juan Pérez</strong></div>
                    <div class="reservation__room-type">Doble</div>
                    <div class="reservation__arrival-date">2024-08-01</div>
                    <div class="reservation__departure-date">2024-08-05</div>
                    <div class="reservation__status confirmed">Confirmada</div>
                </div>
                <div class="reservation">
                    <div class="reservation__id">2345</div>
                    <div class="reservation__name"><strong>María González</strong></div>
                    <div class="reservation__room-type">Individual</div>
                    <div class="reservation__arrival-date">2024-08-03</div>
                    <div class="reservation__departure-date">2024-08-07</div>
                    <div class="reservation__status pending">Pendiente</div>
                </div>
                <div class="reservation">
                    <div class="reservation__id">3456</div>
                    <div class="reservation__name"><strong>Carlos Ruiz</strong></div>
                    <div class="reservation__room-type">Suite</div>
                    <div class="reservation__arrival-date">2024-08-10</div>
                    <div class="reservation__departure-date">2024-08-14</div>
                    <div class="reservation__status confirmed">Confirmada</div>
                </div>
                <div class="reservation">
                    <div class="reservation__id">4567</div>
                    <div class="reservation__name"><strong>Laura Torres</strong></div>
                    <div class="reservation__room-type">Doble</div>
                    <div class="reservation__arrival-date">2024-08-15</div>
                    <div class="reservation__departure-date">2024-08-20</div>
                    <div class="reservation__status pending">Pendiente</div>
                </div>
                <div class="reservation">
                    <div class="reservation__id">5678</div>
                    <div class="reservation__name"><strong>Andrés Gómez</strong></div>
                    <div class="reservation__room-type">Individual</div>
                    <div class="reservation__arrival-date">2024-08-22</div>
                    <div class="reservation__departure-date">2024-08-25</div>
                    <div class="reservation__status cancelled">Cancelada</div>
                </div>
            </div>
        </main>
    HTML;
} -->

<?php

function HTMLreservations() {
    ob_start();
    $id_rece = Session::get('user')['id_usuario'];
    ?>
    <div class="main-content">
        <h1>Gestión de Reservas</h1>
        <ul>
            <li><a href="?p=4&action=view_clients">Ver Clientes</a></li>
            <li><a href="?p=4&action=add_client">Añadir Cliente</a></li>
            <li><a href="?p=4&action=view_rooms">Ver Habitaciones</a></li>
            <li><a href="?p=4&action=add_room">Añadir Habitación</a></li>
            <li><a href="?p=4&action=view_reservations">Ver Reservas</a></li>
            <li><a href="#" onclick="submitAddReservationForm(); return false;">Añadir Reserva</a></li>
        </ul>
        <form id="addReservationForm" action="index.php?p=5" method="post" style="display:none;">
            <input type="hidden" name="id_rece" value="<?= $id_rece ?>">
            <input type="hidden" name="accion" value="add-reserva">
        </form>
        <?= handleReceptionistActions(); ?>
    </div>
    <script>
        function submitAddReservationForm() {
            document.getElementById('addReservationForm').submit();
        }
    </script>
    <?php
    return ob_get_clean();
}

function handleReceptionistActions()
{
    if (isset($_GET['action'])) {
        return match ($_GET['action']) {
            'view_clients'          => viewClients(),
            'add_client'            => addClientForm(),
            'edit_client'           => editClientForm($_GET['id']),
            'delete_client'         => deleteClient($_GET['id']),
            'view_rooms'            => viewRooms(),
            'add_room'              => addRoomForm(),
            'edit_room'             => editRoomForm($_GET['id']),
            'delete_room'           => deleteRoom($_GET['id']),
            'view_reservations'     => viewReservations(),
            'add_reservation'       => addReservationForm(),
            'edit_reservation'      => editReservationForm($_GET['id']),
            'delete_reservation'    => deleteReservation($_GET['id']),
            default => '<p>Acción no reconocida.</p>',
        };
    } else {
        return '<p>Seleccione una acción del menú.</p>';
    }
}

function viewClients()
{
    global $conn;
    $sql = "SELECT id_usuario, nombre, apellidos, dni, email FROM Usuarios WHERE rol='Cliente'";
    $result = $conn->query($sql);
    $output = "<h2>Lista de Clientes</h2><table><tr><th>ID</th><th>Nombre</th><th>Apellidos</th><th>DNI</th><th>Email</th><th>Acciones</th></tr>";
    while ($row = $result->fetch_assoc()) {
        $output .= "<tr><td>{$row['id_usuario']}</td><td>{$row['nombre']}</td><td>{$row['apellidos']}</td><td>{$row['dni']}</td><td>{$row['email']}</td>";
        $output .= "<td><a href='?p=4&action=edit_client&id={$row['id_usuario']}'>Editar</a> | <a href='?p=4&action=delete_client&id={$row['id_usuario']}'>Eliminar</a></td></tr>";
    }
    $output .= "</table>";
    return $output;
}

function addClientForm()
{
    return '
    <h2>Añadir Cliente</h2>
    <form action="index.php?p=4&action=save_client" method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
        <label for="apellidos">Apellidos:</label>
        <input type="text" id="apellidos" name="apellidos" required>
        <label for="dni">DNI:</label>
        <input type="text" id="dni" name="dni" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="clave">Clave:</label>
        <input type="password" id="clave" name="clave" required>
        <label for="num_tarjeta_credito">Número de Tarjeta de Crédito:</label>
        <input type="text" id="num_tarjeta_credito" name="num_tarjeta_credito" required>
        <button type="submit">Guardar</button>
    </form>';
}

function saveClient()
{
    global $conn;
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $dni = $_POST['dni'];
    $email = $_POST['email'];
    $clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);
    $num_tarjeta_credito = $_POST['num_tarjeta_credito'];
    $sql = "INSERT INTO Usuarios (nombre, apellidos, dni, email, clave, num_tarjeta_credito, rol) VALUES ('$nombre', '$apellidos', '$dni', '$email', '$clave', '$num_tarjeta_credito', 'Cliente')";
    if ($conn->query($sql) === TRUE) {
        return "<p>Cliente añadido correctamente.</p>";
    } else {
        return "<p>Error al añadir cliente: " . $conn->error . "</p>";
    }
}

function editClientForm($id)
{
    global $conn;
    $sql = "SELECT nombre, apellidos, dni, email, num_tarjeta_credito FROM Usuarios WHERE id_usuario=$id AND rol='Cliente'";
    $result = $conn->query($sql);
    if ($row = $result->fetch_assoc()) {
        return '
        <h2>Editar Cliente</h2>
        <form action="index.php?p=4&action=update_client&id=' . $id . '" method="post">
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
            <button type="submit">Guardar</button>
        </form>';
    } else {
        return "<p>Cliente no encontrado.</p>";
    }
}

function updateClient($id)
{
    global $conn;
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $dni = $_POST['dni'];
    $email = $_POST['email'];
    $num_tarjeta_credito = $_POST['num_tarjeta_credito'];
    $sql = "UPDATE Usuarios SET nombre='$nombre', apellidos='$apellidos', dni='$dni', email='$email', num_tarjeta_credito='$num_tarjeta_credito' WHERE id_usuario=$id AND rol='Cliente'";
    if ($conn->query($sql) === TRUE) {
        return "<p>Cliente actualizado correctamente.</p>";
    } else {
        return "<p>Error al actualizar cliente: " . $conn->error . "</p>";
    }
}

function deleteClient($id)
{
    global $conn;
    $sql = "DELETE FROM Usuarios WHERE id_usuario=$id AND rol='Cliente'";
    if ($conn->query($sql) === TRUE) {
        return "<p>Cliente eliminado correctamente.</p>";
    } else {
        return "<p>Error al eliminar cliente: " . $conn->error . "</p>";
    }
}

function viewRooms()
{
    global $conn;
    $sql = "SELECT * FROM Habitaciones";
    $result = $conn->query($sql);
    $output = "<h2>Lista de Habitaciones</h2><table><tr><th>ID</th><th>Número</th><th>Capacidad</th><th>Precio por Noche</th><th>Descripción</th><th>Imágenes</th><th>Estado</th><th>Acciones</th></tr>";
    while ($row = $result->fetch_assoc()) {
        $output .= "<tr><td>{$row['id_habitacion']}</td><td>{$row['numero']}</td><td>{$row['capacidad']}</td><td>{$row['precio_por_noche']}</td><td>{$row['descripcion']}</td><td>{$row['n-imagenes']}</td><td>{$row['estado']}</td>";
        $output .= "<td><a href='?p=4&action=edit_room&id={$row['id_habitacion']}'>Editar</a> | <a href='?p=4&action=delete_room&id={$row['id_habitacion']}'>Eliminar</a></td></tr>";
    }
    $output .= "</table>";
    return $output;
}

function addRoomForm()
{
    return '
    <h2>Añadir Habitación</h2>
    <form action="index.php?p=4&action=save_room" method="post">
        <label for="numero">Número de Habitación:</label>
        <input type="text" id="numero" name="numero" required>
        <label for="capacidad">Capacidad:</label>
        <input type="number" id="capacidad" name="capacidad" required>
        <label for="precio_por_noche">Precio por Noche:</label>
        <input type="text" id="precio_por_noche" name="precio_por_noche" required>
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required></textarea>
        <label for="n-imagenes">Número de Imágenes:</label>
        <input type="number" id="n-imagenes" name="n-imagenes" required>
        <button type="submit">Guardar</button>
    </form>';
}

function saveRoom()
{
    global $conn;
    $numero = $_POST['numero'];
    $capacidad = $_POST['capacidad'];
    $precio_por_noche = $_POST['precio_por_noche'];
    $descripcion = $_POST['descripcion'];
    $n_imagenes = $_POST['n-imagenes'];
    $sql = "INSERT INTO Habitaciones (numero, capacidad, precio_por_noche, descripcion, `n-imagenes`) VALUES ('$numero', $capacidad, $precio_por_noche, '$descripcion', $n_imagenes)";
    if ($conn->query($sql) === TRUE) {
        return "<p>Habitación añadida correctamente.</p>";
    } else {
        return "<p>Error al añadir habitación: " . $conn->error . "</p>";
    }
}

function editRoomForm($id)
{
    global $conn;
    $sql = "SELECT * FROM Habitaciones WHERE id_habitacion=$id";
    $result = $conn->query($sql);
    if ($row = $result->fetch_assoc()) {
        return '
        <h2>Editar Habitación</h2>
        <form action="index.php?p=4&action=update_room&id=' . $id . '" method="post">
            <label for="numero">Número de Habitación:</label>
            <input type="text" id="numero" name="numero" value="' . $row['numero'] . '" required>
            <label for="capacidad">Capacidad:</label>
            <input type="number" id="capacidad" name="capacidad" value="' . $row['capacidad'] . '" required>
            <label for="precio_por_noche">Precio por Noche:</label>
            <input type="text" id="precio_por_noche" name="precio_por_noche" value="' . $row['precio_por_noche'] . '" required>
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" required>' . $row['descripcion'] . '</textarea>
            <label for="n-imagenes">Número de Imágenes:</label>
            <input type="number" id="n-imagenes" name="n-imagenes" value="' . $row['n-imagenes'] . '" required>
            <label for="estado">Estado:</label>
            <select id="estado" name="estado" required>
                <option value="Operativa" ' . ($row['estado'] == 'Operativa' ? 'selected' : '') . '>Operativa</option>
                <option value="Pendiente" ' . ($row['estado'] == 'Pendiente' ? 'selected' : '') . '>Pendiente</option>
                <option value="Confirmada" ' . ($row['estado'] == 'Confirmada' ? 'selected' : '') . '>Confirmada</option>
                <option value="Mantenimiento" ' . ($row['estado'] == 'Mantenimiento' ? 'selected' : '') . '>Mantenimiento</option>
            </select>
            <button type="submit">Guardar</button>
        </form>';
    } else {
        return "<p>Habitación no encontrada.</p>";
    }
}

function updateRoom($id)
{
    global $conn;
    $numero = $_POST['numero'];
    $capacidad = $_POST['capacidad'];
    $precio_por_noche = $_POST['precio_por_noche'];
    $descripcion = $_POST['descripcion'];
    $n_imagenes = $_POST['n-imagenes'];
    $estado = $_POST['estado'];
    $sql = "UPDATE Habitaciones SET numero='$numero', capacidad=$capacidad, precio_por_noche=$precio_por_noche, descripcion='$descripcion', `n-imagenes`=$n_imagenes, estado='$estado' WHERE id_habitacion=$id";
    if ($conn->query($sql) === TRUE) {
        return "<p>Habitación actualizada correctamente.</p>";
    } else {
        return "<p>Error al actualizar habitación: " . $conn->error . "</p>";
    }
}

function deleteRoom($id)
{
    global $conn;
    $sql = "DELETE FROM Habitaciones WHERE id_habitacion=$id";
    if ($conn->query($sql) === TRUE) {
        return "<p>Habitación eliminada correctamente.</p>";
    } else {
        return "<p>Error al eliminar habitación: " . $conn->error . "</p>";
    }
}


function addReservationForm()
{
    $id_rece = Session::get('user')['id_usuario'];

    return <<<HTML
        <h2>Añadir Reserva</h2>
        <form id="addReservationForm" action="index.php?p=4&action=save_reservation" method="post">
            <input type='hidden' name='id_rece' value='{$id_rece}'>
            <label for="id_cliente">ID Cliente:</label>
            <input type="number" id="id_cliente" name="id_cliente" required>
            <label for="id_habitacion">ID Habitación:</label>
            <input type="number" id="id_habitacion" name="id_habitacion" required>
            <label for="num_personas">Número de Personas:</label>
            <input type="number" id="num_personas" name="num_personas" required>
            <label for="comentarios">Comentarios:</label>
            <textarea id="comentarios" name="comentarios"></textarea>
            <label for="dia_entrada">Día de Entrada:</label>
            <input type="date" id="dia_entrada" name="dia_entrada" required>
            <label for="dia_salida">Día de Salida:</label>
            <input type="date" id="dia_salida" name="dia_salida" required>
            <button type="submit">Guardar</button>
        </form>
        <a href="#" onclick="document.getElementById('addReservationForm').submit(); return false;">Enviar</a>
    HTML;
}

function viewReservations()
{
    global $conn;
    $sql = "SELECT r.id_reserva, u.nombre, u.apellidos, h.numero, r.dia_entrada, r.dia_salida, r.estado FROM Reservas r
            JOIN Usuarios u ON r.id_cliente = u.id_usuario
            JOIN Habitaciones h ON r.id_habitacion = h.id_habitacion";
    $result = $conn->query($sql);
    $output = "<h2>Lista de Reservas</h2><table><tr><th>ID</th><th>Cliente</th><th>Habitación</th><th>Día de Entrada</th><th>Día de Salida</th><th>Estado</th><th>Acciones</th></tr>";
    while ($row = $result->fetch_assoc()) {
        $output .= "<tr><td>{$row['id_reserva']}</td><td>{$row['nombre']} {$row['apellidos']}</td><td>{$row['numero']}</td><td>{$row['dia_entrada']}</td><td>{$row['dia_salida']}</td><td>{$row['estado']}</td>";
        $output .= "<td><a href='?p=4&action=edit_reservation&id={$row['id_reserva']}'>Editar</a> | <a href='?p=4&action=delete_reservation&id={$row['id_reserva']}'>Eliminar</a></td></tr>";
    }
    $output .= "</table>";
    return $output;
}

function editReservationForm($id)
{
    global $conn;
    $sql = "SELECT id_cliente, id_habitacion, num_personas, comentarios, dia_entrada, dia_salida, estado FROM Reservas WHERE id_reserva=$id";
    $result = $conn->query($sql);
    if ($row = $result->fetch_assoc()) {
        return '
        <h2>Editar Reserva</h2>
        <form action="index.php?p=4&action=update_reservation&id=' . $id . '" method="post">
            <label for="id_cliente">ID Cliente:</label>
            <input type="number" id="id_cliente" name="id_cliente" value="' . $row['id_cliente'] . '" required>
            <label for="id_habitacion">ID Habitación:</label>
            <input type="number" id="id_habitacion" name="id_habitacion" value="' . $row['id_habitacion'] . '" required>
            <label for="num_personas">Número de Personas:</label>
            <input type="number" id="num_personas" name="num_personas" value="' . $row['num_personas'] . '" required>
            <label for="comentarios">Comentarios:</label>
            <textarea id="comentarios" name="comentarios">' . $row['comentarios'] . '</textarea>
            <label for="dia_entrada">Día de Entrada:</label>
            <input type="date" id="dia_entrada" name="dia_entrada" value="' . $row['dia_entrada'] . '" required>
            <label for="dia_salida">Día de Salida:</label>
            <input type="date" id="dia_salida" name="dia_salida" value="' . $row['dia_salida'] . '" required>
            <label for="estado">Estado:</label>
            <select id="estado" name="estado" required>
                <option value="Pendiente" ' . ($row['estado'] == 'Pendiente' ? 'selected' : '') . '>Pendiente</option>
                <option value="Confirmada" ' . ($row['estado'] == 'Confirmada' ? 'selected' : '') . '>Confirmada</option>
                <option value="Mantenimiento" ' . ($row['estado'] == 'Mantenimiento' ? 'selected' : '') . '>Mantenimiento</option>
            </select>
            <button type="submit">Guardar</button>
        </form>';
    } else {
        return "<p>Reserva no encontrada.</p>";
    }
}

function updateReservation($id)
{
    global $conn;
    $id_cliente = $_POST['id_cliente'];
    $id_habitacion = $_POST['id_habitacion'];
    $num_personas = $_POST['num_personas'];
    $comentarios = $_POST['comentarios'];
    $dia_entrada = $_POST['dia_entrada'];
    $dia_salida = $_POST['dia_salida'];
    $estado = $_POST['estado'];
    $sql = "UPDATE Reservas SET id_cliente=$id_cliente, id_habitacion=$id_habitacion, num_personas=$num_personas, comentarios='$comentarios', dia_entrada='$dia_entrada', dia_salida='$dia_salida', estado='$estado' WHERE id_reserva=$id";
    if ($conn->query($sql) === TRUE) {
        return "<p>Reserva actualizada correctamente.</p>";
    } else {
        return "<p>Error al actualizar reserva: " . $conn->error . "</p>";
    }
}

function deleteReservation($id)
{
    global $conn;
    $sql = "DELETE FROM Reservas WHERE id_reserva=$id";
    if ($conn->query($sql) === TRUE) {
        return "<p>Reserva eliminada correctamente.</p>";
    } else {
        return "<p>Error al eliminar reserva: " . $conn->error . "</p>";
    }
}

?>
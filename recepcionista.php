<?php

function NHabitacionesLibres()
{
    global $conn;

    $fecha_actual = date('Y-m-d');

    $sql = "SELECT COUNT(*) AS total FROM Habitaciones 
            WHERE estado = 'Operativa' 
            AND id_habitacion NOT IN (
                SELECT id_habitacion FROM Reservas 
                WHERE '$fecha_actual' BETWEEN dia_entrada AND dia_salida
                AND estado IN ('Pendiente', 'Confirmada')
            )";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return $result->fetch_assoc()['total'];
    } else {
        return 0;
    }
}

function NHabitaciones()
{
    global $conn;

    $sql = "SELECT COUNT(*) AS total FROM Habitaciones";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return $result->fetch_assoc()['total'];
    } else {
        return 0;
    }
}

function CapacidadTotal()
{
    global $conn;

    $sql = "SELECT SUM(capacidad) AS total FROM Habitaciones";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return $result->fetch_assoc()['total'];
    } else {
        return 0;
    }
}

function NHuespedesAlojados()
{
    global $conn;

    $sql = "SELECT SUM(num_personas) AS total FROM Reservas WHERE estado = 'Confirmada'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return $result->fetch_assoc()['total'];
    } else {
        return 0;
    }
}

function HTMLreservations()
{
    ob_start();
    $id_rece = Session::get('user')['id_usuario'];
?>
    <div class="main-content">
        <h1>Gestión de Reservas</h1>
        <ul>
            <li><a href="?p=4&action=view_clients">Ver Clientes</a></li>
            <li><a href="?p=3">Añadir Cliente</a></li>
            <li><a href="?p=4&action=view_rooms">Ver Habitaciones</a></li>
            <li><a href="?p=4&action=add_room">Añadir Habitación</a></li>
            <li><a href="?p=4&action=view_reservations">Ver Reservas</a></li>
            <li><a href="#" onclick="document.getElementById('addReservationForm').submit(); return false;">Añadir Reserva</a></li>
        </ul>
        <form id="addReservationForm" action="index.php?p=5" method="post" style="display:none;">
            <input type="hidden" name="id_rece" value="<?= $id_rece ?>">
            <input type="hidden" name="accion" value="add-reserva">
        </form>
        <?= handleReceptionistActions(); ?>
    </div>
<?php
    return ob_get_clean();
}

function handleReceptionistActions()
{
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
        $id = isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id_habitacion']) ? $_POST['id_habitacion'] : null);
        return match ($action) {
            'view_clients'          => viewClients(),
            'edit_client'           => editClientForm($id),
            'delete_client'         => deleteClient($id),
            'view_rooms'            => viewRooms(),
            'add_room'              => addRoomForm(),
            'save_room'             => saveRoom(),
            'edit_room'             => editRoomForm($id),
            'update_room'           => updateRoom($id),
            'delete_room'           => deleteRoom($id),
            'view_reservations'     => viewReservations(),
            'add_reservation'       => addReservationForm(),
            'edit_reservation'      => editReservationForm($id),
            'delete_reservation'    => deleteReservation($id),
            'update_reservation'    => updateReservation($id),
            'upload_photos'         => uploadPhotos(),
            'delete_photo'          => deletePhoto(),
            default                 => '<span>Acción no reconocida.</span>',
        };
    } else {
        return '<p>Seleccione una acción del menú.</p>';
    }
}

function uploadPhotos()
{
    global $conn;
    $id_habitacion = isset($_POST['id_habitacion']) ? $_POST['id_habitacion'] : null;

    if (empty($id_habitacion)) {
        echo "ID de habitación no proporcionado.";
        return;
    }

    if (!isset($_FILES['photos'])) {
        echo "No se han subido fotos.";
        return;
    }

    // Check the size of each image
    $max_size = 16 * 1024 * 1024; // 16MB
    foreach ($_FILES['photos']['tmp_name'] as $index => $tmpName) {
        if (is_uploaded_file($tmpName)) {
            if ($_FILES['photos']['size'][$index] > $max_size) {
                echo "La imagen " . $_FILES['photos']['name'][$index] . " es demasiado grande.";
                return;
            }
        } else {
            echo "Error al subir el archivo " . $_FILES['photos']['name'][$index] . ".";
            return;
        }
    }

    foreach ($_FILES['photos']['tmp_name'] as $index => $tmpName) {
        if (is_uploaded_file($tmpName)) {
            $fileName = $_FILES['photos']['name'][$index];
            $fileData = file_get_contents($tmpName);
            $sql = "INSERT INTO Fotografias (id_habitacion, nombre_archivo, imagen) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $id_habitacion, $fileName, $fileData);
            if ($stmt->execute()) {
                $photo_id = $stmt->insert_id;
            } else {
                echo "Error al guardar la foto " . $fileName . " en la base de datos.";
                return;
            }
            $stmt->close();
        } else {
            echo "Error al subir el archivo " . $_FILES['photos']['name'][$index] . ".";
            return;
        }
    }

    echo "Fotos subidas y guardadas exitosamente.";
}

function deletePhoto()
{
    global $conn;
    $id_fotografia = $_POST['id_fotografia'];

    if (empty($id_fotografia)) {
        echo "ID de fotografía no proporcionado.";
        return;
    }

    $sql = "DELETE FROM Fotografias WHERE id_fotografia = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_fotografia);
    if ($stmt->execute()) {
        echo "Foto eliminada correctamente.";
    } else {
        echo "Error al eliminar la foto de la base de datos.";
    }
    $stmt->close();
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
    $sql = "SELECT nombre, apellidos, dni, email, num_tarjeta_credito, clave FROM Usuarios WHERE id_usuario=$id";
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edicion'])) {
        $id = $_POST['id'];
        echo updateClient($id);
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
    $clave = $_POST['clave'];

    if (empty($clave)) {
        $sql = "UPDATE Usuarios SET nombre='$nombre', apellidos='$apellidos', dni='$dni', email='$email', num_tarjeta_credito='$num_tarjeta_credito', WHERE id_usuario=$id AND rol='Cliente'";
    } else {
        $hashed_password = password_hash($clave, PASSWORD_BCRYPT);
        $sql = "UPDATE Usuarios SET nombre='$nombre', apellidos='$apellidos', dni='$dni', email='$email', num_tarjeta_credito='$num_tarjeta_credito', clave='$hashed_password' WHERE id_usuario=$id AND rol='Cliente'";
    }

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
    $output = "<h2>Lista de Habitaciones</h2><table><tr><th>ID</th><th>Número</th><th>Capacidad</th><th>Precio por Noche</th><th>Descripción</th><th>Número de Imágenes</th><th>Estado</th><th>Acciones</th></tr>";
    while ($row = $result->fetch_assoc()) {
        $output .= "<tr>
            <td>{$row['id_habitacion']}</td>
            <td>{$row['numero']}</td>
            <td>{$row['capacidad']}</td>
            <td>{$row['precio_por_noche']}</td>
            <td>{$row['descripcion']}</td>
            <td>{$row['n-imagenes']}</td>
            <td>{$row['estado']}</td>
            <td>
                <form action='index.php?p=4&action=edit_room' method='post' style='display:inline;'>
                    <input type='hidden' name='id_habitacion' value='{$row['id_habitacion']}'>
                    <button type='submit'>Editar</button>
                </form>
                <form action='index.php?p=4&action=delete_room' method='post' style='display:inline;'>
                    <input type='hidden' name='id_habitacion' value='{$row['id_habitacion']}'>
                    <button type='submit'>Eliminar</button>
                </form>
            </td>
        </tr>";
    }
    $output .= "</table>";
    return $output;
}

function addRoomForm()
{
    return '
    <h2>Añadir Habitación</h2>
    <form id="addRoomForm" action="index.php?p=4&action=save_room" method="post" novalidate>
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
        <span id="error-message" class="error-message"></span>
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
    if (empty($id)) {
        return "<p>ID de habitación no proporcionado.</p>";
    }

    $sql = "SELECT * FROM Habitaciones WHERE id_habitacion=$id";
    $result = $conn->query($sql);

    if ($result === false) {
        return "<p>Error en la consulta: " . $conn->error . "</p>";
    }

    if ($row = $result->fetch_assoc()) {
        // Fetch existing photos
        $photos_sql = "SELECT id_fotografia, nombre_archivo, imagen FROM Fotografias WHERE id_habitacion=$id";
        $photos_result = $conn->query($photos_sql);
        $photos_html = "";
        while ($photo = $photos_result->fetch_assoc()) {
            $photos_html .= "<div id='photo_{$photo['id_fotografia']}'>
                <img src='data:image/jpeg;base64," . base64_encode($photo['imagen']) . "' alt='{$photo['nombre_archivo']}' width='100'>
                <form action='index.php?p=4&action=delete_photo' method='post'>
                    <input type='hidden' name='id_fotografia' value='{$photo['id_fotografia']}'>
                    <button type='submit'>Eliminar</button>
                </form>
            </div>";
        }

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
        </form>
        <h2>Fotografías</h2>
        <div id="photoList">
            ' . $photos_html . '
        </div>
        <form id="uploadPhotoForm" action="index.php?p=4&action=upload_photos" enctype="multipart/form-data" method="post">
            <input type="hidden" name="id_habitacion" value="' . $id . '">
            <input type="file" id="photos" name="photos[]" multiple required>
            <button type="submit">Subir Fotos</button>
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
    $sql = "SELECT r.id_reserva, u.nombre, u.apellidos, h.numero, r.num_personas, r.dia_entrada, r.dia_salida, r.estado FROM Reservas r
            JOIN Usuarios u ON r.id_cliente = u.id_usuario
            JOIN Habitaciones h ON r.id_habitacion = h.id_habitacion";
    $result = $conn->query($sql);
    $output = "<h2>Lista de Reservas</h2><table><tr><th>ID</th><th>Cliente</th><th>Habitación</th><th>Número de Personas</th><th>Día de Entrada</th><th>Día de Salida</th><th>Estado</th><th>Acciones</th></tr>";
    while ($row = $result->fetch_assoc()) {
        $output .= "<tr><td>{$row['id_reserva']}</td><td>{$row['nombre']} {$row['apellidos']}</td><td>{$row['numero']}</td><td>{$row['num_personas']}</td><td>{$row['dia_entrada']}</td><td>{$row['dia_salida']}</td><td>{$row['estado']}</td>";
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

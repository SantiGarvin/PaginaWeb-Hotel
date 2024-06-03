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
    ?>
    <div class="main-content">
        <h1>Gestión de Reservas</h1>
        <ul>
            <li><a href="?p=4&action=view_clients">Ver Clientes</a></li>
            <li><a href="?p=4&action=add_client">Añadir Cliente</a></li>
            <li><a href="?p=4&action=view_rooms">Ver Habitaciones</a></li>
            <li><a href="?p=4&action=add_room">Añadir Habitación</a></li>
            <li><a href="?p=4&action=view_reservations">Ver Reservas</a></li>
            <li><a href="?p=4&action=add_reservation">Añadir Reserva</a></li>
        </ul>
        <?= handleReceptionistActions(); ?>
    </div>
    <?php
    return ob_get_clean();
}

function handleReceptionistActions() {
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

function viewClients() {
    // Código para mostrar la lista de clientes
}

function addClientForm() {
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

function editClientForm($id) {
    // Código para obtener los datos del cliente y mostrarlos en el formulario
}

function deleteClient($id) {
    // Código para eliminar el cliente
}

function viewRooms() {
    // Código para mostrar la lista de habitaciones
}

function addRoomForm() {
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

function editRoomForm($id) {
    // Código para obtener los datos de la habitación y mostrarlos en el formulario
}

function deleteRoom($id) {
    // Código para eliminar la habitación
}

function viewReservations() {
    // Código para mostrar la lista de reservas
}

function addReservationForm() {
    return '
    <h2>Añadir Reserva</h2>
    <form action="index.php?p=4&action=save_reservation" method="post">
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
    </form>';
}

function editReservationForm($id) {
    // Código para obtener los datos de la reserva y mostrarlos en el formulario
}

function deleteReservation($id) {
    // Código para eliminar la reserva
}

?>

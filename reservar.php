<?php

require_once 'includes/db-connection.php';

function InsertarReserva($fecha_entrada, $fecha_salida, $capacidad)
{
    global $conn;

    // Obtener id_cliente a partir del DNI
    $sql = "SELECT id_usuario FROM Usuarios WHERE dni = '$capacidad'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $id_cliente = $result->fetch_assoc()['id_usuario'];
    } else {
        echo "No se encontró el usuario con DNI: $capacidad";
        return;
    }

    // Obtener id_habitacion a partir del número de personas
    $sql = "SELECT id_habitacion FROM Habitaciones WHERE capacidad >= $capacidad LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $id_habitacion = $result->fetch_assoc()['id_habitacion'];
    } else {
        echo "No se encontró una habitación con capacidad para $capacidad personas";
        return;
    }

    // Insertar la reserva
    $sql = "INSERT INTO Reservas (id_cliente, id_habitacion, num_personas, dia_entrada, dia_salida, estado) VALUES ($id_cliente, $id_habitacion, $capacidad, '$fecha_entrada', '$fecha_salida', 'Pendiente')";
    if ($conn->query($sql) === TRUE) {
        echo "Reserva creada correctamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

}

function HTMLreservar() {
    if (isset($_POST['enviar'])) {
        $fecha_entrada = $_POST['fecha_entrada'];
        $fecha_salida = $_POST['fecha_salida'];
        $capacidad = $_POST['n-personas'];

        InsertarReserva($fecha_entrada, $fecha_salida, $capacidad);
    }

    return <<<HTML
    <main class="main-content">
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="version_formulario" name="version_formulario" value="1.0">

            <fieldset class="datos-personales">
                <legend>Datos reserva</legend>

                <div class="fila">
                    <div class="columna columna-nombre-apellidos">
                    <label for="fecha-nacimiento">
                            F. entrada:
                            <input type="date" id="fecha-nacimiento" name="fecha_entrada" required>
                        </label>

                        <label for="fecha-nacimiento">
                            F. salida:
                            <input type="date" id="fecha-nacimiento" name="fecha_salida" required>
                        </label>
                    </div>
                </div>
                <div class="fila">
                    <div class="columna">
                        <label for="dni">
                            Cantidad de personas:
                            <input type="text" id="dni" name="n-personas" placeholder="Nº de camas necesarias" required>
                        </label>
                    </div>
                </div>
                <span class="texto-hover texto-personales">En cumplimiento del Real Decreto 933/2021, de 26 de octubre,
                    estos datos serán comunicados al centro de datos de la Dirección General de la Policía.</span>
            </fieldset>

            <fieldset class="preferencias">
                <legend>Preferencias</legend>

                <div class="columna idiomas">
                    <label class="enunciado">Idioma para comunicaciones:</label>
                    <div class="seleccion">
                        <label for="idioma_es">
                            <input type="radio" id="idioma_es" name="idioma" value="español" checked>
                            Español
                        </label>
                        <label for="idioma_en">
                            <input type="radio" id="idioma_en" name="idioma" value="inglés">
                            Inglés
                        </label>
                        <label for="idioma_fr">
                            <input type="radio" id="idioma_fr" name="idioma" value="francés">
                            Francés
                        </label>
                    </div>
                </div>

                <div class="columna habitacion">
                    <label class="enunciado">Preferencias de habitación:</label>
                    <div class="seleccion">
                        <label for="preferencia1">
                            <input type="checkbox" id="preferencia1" name="preferencias[]" value="Opción 1">
                            Para fumadores
                        </label>
                        <label for="preferencia2">
                            <input type="checkbox" id="preferencia2" name="preferencias[]" value="Opción 2">
                            Que permita mascotas
                        </label>
                        <label for="preferencia3">
                            <input type="checkbox" id="preferencia3" name="preferencias[]" value="Opción 3">
                            Con vistas
                        </label>
                        <label for="preferencia4">
                            <input type="checkbox" id="preferencia4" name="preferencias[]" value="Opción 4">
                            Con moqueta
                        </label>
                    </div>
                </div>
            </fieldset>
            
            <input type="submit" name="enviar" value="Enviar datos">
        </form>
    </main>
HTML;
}
?>
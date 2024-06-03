<?php

require_once 'includes/db-connection.php';
require_once 'includes/Session.php';

function NHabitacionesLibres() {
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

function NHabitaciones() {
    global $conn;

    $sql = "SELECT COUNT(*) AS total FROM Habitaciones";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return $result->fetch_assoc()['total'];
    } else {
        return 0;
    }
}

function CapacidadTotal() {
    global $conn;

    $sql = "SELECT SUM(capacidad) AS total FROM Habitaciones";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return $result->fetch_assoc()['total'];
    } else {
        return 0;
    }
}

function NHuespedesAlojados() {
    global $conn;

    $sql = "SELECT COUNT(*) AS total FROM Reservas WHERE estado = 'Confirmada'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return $result->fetch_assoc()['total'];
    } else {
        return 0;
    }
}


function ValidarReserva($fecha_entrada, $fecha_salida, $capacidad)
{
    $errores = "";

    if ($fecha_entrada < date('Y-m-d')) {
        $errores .= "La fecha de entrada no puede ser anterior a la fecha actual. \n";
    }

    if ($fecha_salida < $fecha_entrada) {
        $errores .= "La fecha de salida no puede ser anterior a la fecha de entrada. \n";
    }

    if ($capacidad <= 0) {
        $errores .= "La capacidad debe ser mayor que 0. \n";
    }

    return $errores ? $errores : true;
}

function BuscarHabitacion($capacidad, $fecha_inicio, $fecha_fin)
{
    global $conn;

    $sql = "SELECT id_habitacion, numero, precio_por_noche FROM Habitaciones 
        WHERE capacidad >= $capacidad 
        AND estado = 'Operativa' 
        AND id_habitacion NOT IN (
            SELECT id_habitacion FROM Reservas 
            WHERE (estado IN ('Pendiente', 'Confirmada'))
            AND (
                (dia_entrada <= '$fecha_fin' AND dia_salida >= '$fecha_inicio')
            )
        )
        ORDER BY capacidad ASC
        LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        // Ver si hay alguna habitacion disponible 
        $sql = "SELECT id_habitacion, numero, precio_por_noche FROM Habitaciones 
                WHERE estado = 'Operativa' 
                AND id_habitacion NOT IN (
                    SELECT id_habitacion FROM Reservas 
                    WHERE (estado IN ('Pendiente', 'Confirmada'))
                    AND (
                        (dia_entrada <= '$fecha_fin' AND dia_salida >= '$fecha_inicio')
                    )
                )";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // Hay disponibles pero no con la capacidad requerida
            return 'capacidad_insuficiente';
        } else {
            // No hay habitaciones disponibles
            return 'no_disponible';
        }
    }
}

function obtenerNumeroHabitacion($id_habitacion) {
    global $conn;

    $sql = "SELECT numero FROM Habitaciones WHERE id_habitacion = $id_habitacion LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return $result->fetch_assoc()['numero'];
    } else {
        echo "No se encontró una habitación con el id $id_habitacion";
        return;
    }
}

function obtenerIdHabitacion($numero_habitacion) {
    global $conn;

    $sql = "SELECT id_habitacion FROM Habitaciones WHERE numero = $numero_habitacion LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return $result->fetch_assoc()['id_habitacion'];
    } else {
        echo "No se encontró una habitación con el número $numero_habitacion";
        return;
    }
}

function InsertarReserva($id_habitacion, $num_personas, $comentarios, $dia_entrada, $dia_salida, $id_usuario) {
    global $conn;

    // Insertar la reserva
    $sql = "INSERT INTO Reservas (id_cliente, id_habitacion, num_personas, comentarios, dia_entrada, dia_salida, estado) 
            VALUES ($id_usuario, $id_habitacion, $num_personas, '$comentarios', '$dia_entrada', '$dia_salida', 'Pendiente')";    
    if ($conn->query($sql) === TRUE) {
        //echo "Reserva creada correctamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}


function HTMLreservar() {
    global $conn;

    // Initialize variables
    $fecha_entrada = '';
    $fecha_salida = '';
    $capacidad = '';
    $comentarios = '';
    $errorDiv = '';
    $boton = "Enviar datos";
    $reserva_en_proceso = [];
    $reserva_creada = '';
    $modificar = false;

    if(isset($_POST['id'])){
        $id_modificacion = $_POST['id'];
        Session::set('id_modificacion', $id_modificacion);
    }

    $id_modificacion = Session::get('id_modificacion');

    if (isset($_POST['accion']) && $_POST['accion'] == 'modificar') {
        $modificar = true;
        $boton = "Modificar datos";

        $sql = "SELECT * FROM Reservas WHERE id_reserva = $id_modificacion";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $fecha_entrada = $row['dia_entrada'];
            $fecha_salida = $row['dia_salida'];
            $capacidad = $row['num_personas'];
            $comentarios = $row['comentarios'];
        } else {
            $error_div = '<div class="error">No se encontró la reserva a modificar</div>';
            return;
        }
    }

    if ($modificar) {
        $accion = 'modificar';
        $readonly = 'readonly';
        $readonly = 'readonly';
    } else {
        $accion = 'nuevo';
        $readonly = '';
    }
    
    if (isset($_POST['enviar'])) {
        $fecha_entrada = $_POST['fecha_entrada'];
        $fecha_salida = $_POST['fecha_salida'];
        $capacidad = $_POST['n-personas'];
        $comentarios = $_POST['comentarios'];
        
        if ($_POST['accion'] == 'modificar') {
        
            $sql = "UPDATE Reservas SET  comentarios = '$comentarios' WHERE id_reserva = $id_modificacion";
            if ($conn->query($sql) === TRUE) {
                $reserva_creada = '<div class="error">Reserva modificada correctamente</div>';
            } else {
                $error_div = '<div class="error">Error al modificar la reserva</div>';
            }

        }else{
            $validacion = ValidarReserva($fecha_entrada, $fecha_salida, $capacidad);
            if($validacion === true){

                $reserva_en_proceso = BuscarHabitacion($capacidad, $fecha_entrada, $fecha_salida);

                if (is_array($reserva_en_proceso)) {
                    InsertarReserva($reserva_en_proceso['id_habitacion'], $capacidad, $comentarios, $fecha_entrada, $fecha_salida, Session::get('user')['id_usuario']);
                    Session::set('id_reserva_reciente', $conn->insert_id); // Guardar el ID de la reserva recién insertada
                    $reserva_creada = '<div class="error">Reserva creada correctamente</div>';        
                } else {
                    // Store the error message
                    $errorDiv = $reserva_en_proceso;
                }
            }else{
                $error_div = '<div class="error">' . nl2br($validacion) . '</div>';
                $boton = "Reintenta enviar datos";
            }
        }
    }


    if (isset($_POST['confirmar'])) {
        // Actualizar el estado de la reserva a 'Confirmada'
        $id_reserva_reciente = Session::get('id_reserva_reciente');

        $sql = "UPDATE Reservas SET estado = 'Confirmada' WHERE id_reserva = $id_reserva_reciente";
        if ($conn->query($sql) === TRUE) {
            $reserva_creada = '<div class="error">Reserva confirmada correctamente</div>';
            Session::set('id_reserva_reciente', null); // Limpiar la variable de sesión
        } else {
            $error_div = '<div class="error">Error al confirmar la reserva</div>';
        }
    }
    
    if (isset($_POST['cancelar'])) {
        // Obtener el ID de la reserva reciente
        $id_reserva_reciente = Session::get('id_reserva_reciente');
    
        // Eliminar la reserva
        $sql = "DELETE FROM Reservas WHERE id_reserva = $id_reserva_reciente";
        if ($conn->query($sql) === TRUE) {
            $reserva_creada = '<div class="error">Reserva cancelada correctamente</div>';
            Session::set('id_reserva_reciente', null); // Limpiar la variable de sesión
        } else {
            $error_div = '<div class="error">Error al cancelar la reserva</div>';
        }
    }
    



    $AUX = <<<HTML
    <main class="main-content">
        <form action="" method="POST" enctype="multipart/form-data" novalidate>
            <input type="hidden" id="version_formulario" name="version_formulario" value="1.0">
            <input type="hidden" id="accion" name="accion" value="$accion">
            
            $errorDiv
    
            <fieldset class="datos-reserva">
                <legend>Datos reserva</legend>
    
                <div class="fila">
                    <div class="columna columna-nombre-apellidos">
                        <label for="fecha-nacimiento">
                            F. entrada:
                            <input type="date" id="fecha-nacimiento" name="fecha_entrada" required $readonly value="$fecha_entrada">
                        </label>

                        <label for="fecha-nacimiento">
                            F. salida:
                            <input type="date" id="fecha-nacimiento" name="fecha_salida" required $readonly value="$fecha_salida">
                        </label>
                    </div>
                </div>
                <div class="fila">
                    <div class="columna">
                        <label for="n-personas">
                            Cantidad de personas:
                            <input type="text" id="n-personas" name="n-personas" placeholder="Nº de camas necesarias" required $readonly value="$capacidad">
                        </label>
                    </div>
                </div>
                <span class="texto-hover texto-personales">En cumplimiento del Real Decreto 933/2021, de 26 de octubre,
                    estos datos serán comunicados al centro de datos de la Dirección General de la Policía.</span>
            </fieldset>
    
            <fieldset class="preferencias">
                <legend>Preferencias</legend>
    
                <div class="columna">
                    <label for="comentarios">
                        Comentarios:
                        <textarea id="comentarios" name="comentarios" rows="4" cols="50" placeholder="Escriba aquí sus comentarios">$comentarios</textarea>
                    </label>
                </div>
            </fieldset>
    HTML;

    if (isset($reserva_en_proceso['numero']) && isset($reserva_en_proceso['precio_por_noche'])) {
        $boton = "Confirmar reserva";
        $AUX .= <<<HTML
        <fieldset class="datos-habitacion">
            <legend>Datos de la habitación</legend>
    
            <div class="fila">
                <div class="columna">
                <label for="n-personas">
                        Número de habitación:
                        <input type="text" value="{$reserva_en_proceso['numero']}" readonly>
                    </label>
    
                    <label for="n-personas">
                        Precio por noche:
                        <input type="text" value="{$reserva_en_proceso['precio_por_noche']}" readonly>
                    </label>
                </div>
            </div>
        </fieldset>
        <input type="submit" name="confirmar" value="Confirmar reserva">
        <input type="submit" name="cancelar" value="Cancelar reserva">
        HTML;
    }else{
        $AUX .= <<<HTML
        <input type="submit" name="enviar" value= "$boton">
        HTML;
    }
    

    $AUX .= <<<HTML
            $reserva_creada
        </form>
    </main>
    HTML;

    return $AUX;
}
?>
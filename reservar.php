<?php

require_once 'includes/db-connection.php';
require_once 'includes/Session.php';

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


function InsertarReserva($fecha_entrada, $fecha_salida, $capacidad ,$comentario ,$id_usuario)
{
    global $conn;

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
    $sql = "INSERT INTO Reservas (id_cliente, id_habitacion, num_personas, comentarios, dia_entrada, dia_salida, estado) VALUES ($id_usuario, $id_habitacion, $capacidad, '$comentario', '$fecha_entrada', '$fecha_salida', 'Pendiente')";    if ($conn->query($sql) === TRUE) {
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
                InsertarReserva($fecha_entrada, $fecha_salida, $capacidad, $comentarios, Session::get('user')['id_usuario']);
                $reserva_creada = '<div class="error">Reserva creada correctamente</div>';        }else{
                $error_div = '<div class="error">' . nl2br($validacion) . '</div>';
                $boton = "Reintenta enviar datos";
            }
        }
    }

    return <<<HTML
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
            
            <input type="submit" name="enviar" value= "$boton">
            $reserva_creada
        </form>
    </main>
    HTML;
}
?>
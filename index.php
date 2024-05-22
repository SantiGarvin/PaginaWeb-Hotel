<?php
require 'includes/header.php';
require 'includes/footer.php';
require 'includes/nav.php';
require 'inicio.php';
require 'room.php';
require 'servicios.php';
require 'reservations.php';

// Variables con los datos del autor y los enlaces
$nombres = "Diego Sánchez Vargas y Santiago Garvin Pérez";
$enlace = ".";
$enlace2 = "";

$permisosDeUsuarioactual = false; 

$opc = 0;
if (isset($_GET["p"]) && ($_GET["p"] >= 0 || $_GET["p"] <= 3)) {
    $opc = $_GET['p'];
}

$menu = [
  ['Inicio', 'index.php?p=0'],
  ['Habitaciones', 'index.php?p=1'],
  ['Servicio', 'index.php?p=2'],
  ['Registro', 'index.php?p=4']
];

if($permisosDeUsuarioactual) {
  $menu[] = ['RecepcionistaSOLO', 'index.php?p=3'];
}

$estilos = glob('css/*.css'); // Array con los estilos CSS
$head = HTMLhead("Proyecto Final", $estilos);
$header = HTMLheader();
$menu = HTMLnavegacion($menu, $opc, 'activo');
$footer = HTMLfooter($Diego, $enlace, $enlace2);

switch ($opc) {
    case 0:
        $cuerpo = HTMLpag_inicio();
        break;
    case 1:
        $cuerpo = HTMLhabitaciones();
        break;
    case 2:
        $cuerpo = HTMLservicios();
        break;
    case 3:
        $cuerpo = HTMLreservations();
        break;
    case 4:
        $cuerpo = HTMLregistro();
        break;
}

echo <<<HTML
<!DOCTYPE html>
<html>
  $head
  <body>
    $header
    $menu
    $cuerpo
    $footer
  </body>
</html>
HTML;
?>

<?php
require 'includes/header.php';
require 'includes/footer.php';
require 'includes/nav.php';
require 'inicio.php';
require 'room.php';
require 'servicios.php';

// Variables con los datos del autor y los enlaces
$nombres = "Diego Sánchez Vargas y Santiago Garvin Pérez";
$enlace = ".";
$enlace2 = "";

$opc = 0;
if (isset($_GET["p"]) && ($_GET["p"] >= 0 || $_GET["p"] <= 2)) {
    $opc = $_GET['p'];
}

$menu = [
    ['Inicio', 'index.php?p=0'],
    ['Habitaciones', 'index.php?p=1'],
    ['Servicio', 'index.php?p=2']
];


$estilos = glob('css/*.css'); // Array con los estilos CSS
$head = HTMLhead("Proyecto Final", $estilos);
$header = HTMLheader();
$menu = HTMLnavegacion($menu, $opc, 'activo');
$footer = HTMLfooter($nombres, $enlace, $enlace2);

$cuerpo = match ($opc) {
    0 => HTMLpag_inicio(),
    1 => HTMLhabitaciones(),
    2 => HTMLservicios(),
};

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

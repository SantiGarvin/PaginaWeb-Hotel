<?php
require 'includes/header.php';
require 'includes/footer.php';
require 'includes/nav.php';
require 'inicio.php';
require 'room.php';
require 'servicios.php';

// Variables con los datos del autor y los enlaces
$Diego = "Diego Sánchez Vargas y Santiago Garvin Pérez";
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

$head = HTMLhead("Proyecto Final", ["css/styles.css"]);
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

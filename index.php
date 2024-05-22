<?php
require 'includes/header.php';
require 'includes/footer.php';
require 'includes/nav.php';
require 'inicio.php';
require 'room.php';

//Variables con los datos del autor y los enlaces
$Diego = "Diego Sánchez Vargas y Santiago Garvin Pérez";
$enlace = ".";
$enlace2 = "";

$opc = 0;
if (isset($_GET["p"]) && ($_GET["p"]>=0 || $_GET["p"]<=3))
  $opc = $_GET['p'];

$head = HTMLhead("Proyecto Final", ["css/styles.css"]);
$menu = HTMLnavegacion($menu,$opc,'activo');
$footer = HTMLfooter($Diego, $enlace, $enlace2);

switch ($opc) {
    case 0: $cuerpo = HTMLpag_inicio();   break;
    case 1: $cuerpo = HTMLhabitaciones();   break;
    //case 2: $cuerpo = HTMLpag_ada();   break;
    //case 3: $cuerpo = HTMLpag_contacto();   break;
}


?>.

<!DOCTYPE html>
<html>
  <?php echo $head; ?>
  <body>
    <?php
    echo $menu;
    echo $cuerpo;
    echo $footer;
    ?>
  </body>
</html>

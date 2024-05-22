<?php
require 'includes/header.php';
require 'includes/footer.php';

//Variables con los datos del autor y los enlaces
$Diego = "Diego Sánchez Vargas y Santiago Garvin Pérez";
$enlace = ".";
$enlace2 = "";
?>.

<!DOCTYPE html>
<html>
  <?php HTMLhead("Proyecto Final", ["css/styles.css"]); ?>
  <body>
    <?php
    //HTMLnavegacion('Menú',$menu,0,'activo');    // Cambiamos opción activa
    //HTMLpag_inicio();                           // Cambiamos contenido principal
    HTMLfooter($Diego, $enlace, $enlace2);
    ?>
  </body>
</html>

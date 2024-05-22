<?php
/***************************
 * Generar elemento HEAD de la página.
 *   $titulo : Es un string con el título (title) de la página.
 *   $estilos : Es un array de cadenas con los nombres de los ficheros de estilo.
 */
function HTMLhead($titulo,$estilos) {
    echo <<< HTML
    <head>
      <meta charset="utf-8">
    HTML;
    foreach ($estilos as $f)
      echo '  <link rel="stylesheet" href="'.$f.'">';
    echo <<< HTML
      <title>$titulo</title>
    </head>
    HTML;
}
?>
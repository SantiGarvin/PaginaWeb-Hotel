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

/***************************
 * Generar elemento HEADER de la página.
 */
function HTMLheader() {
  echo <<< HTML
  <header class="header">
    <a href="index.php" class="header__link">
      <img class="header__icon" src="img/icono.png" alt="Icono del hotel">
    </a>
    <h1 class="header__title">Hotel Granada View</h1>
  </header>
  HTML;
}
?>
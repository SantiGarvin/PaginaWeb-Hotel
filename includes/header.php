<?php

/***************************
 * Generar elemento HEAD de la página.
 *   $titulo : Es un string con el título (title) de la página.
 *   $estilos : Es un array de cadenas con los nombres de los ficheros de estilo.
 */
function HTMLhead($titulo, $estilos)
{
  $links = '';
  foreach ($estilos as $f) {
    $links .= '<link rel="stylesheet" href="' . $f . '">';
  }

  return <<<HTML
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>$titulo</title>
    $links
  </head>
HTML;
}

/***************************
 * Generar elemento HEADER de la página.
 */
function HTMLheader()
{
  return <<<HTML
  <header class="header">
    <a href="index.php" class="header__link">
      <img class="header__icon" src="img/icono.png" alt="Icono del hotel">
    </a>
    <h1 class="header__title">Hotel Granada View</h1>
  </header>
  HTML;
}


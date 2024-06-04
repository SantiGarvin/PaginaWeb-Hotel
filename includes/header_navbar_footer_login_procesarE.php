<?php

require_once 'db-connection.php'; // Asegúrate de tener este archivo configurado correctamente


/***************************
 * Generar pagina de error
 */
function HTMLpag_error()
{
  return <<<HTML
  <section class="error">
    <h2 class="error__title">Página no encontrada</h2>
    <p class="error__message">La página que buscas no existe.</p>
  </section>
  HTML;
}


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

/*
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

/***************************
 * Generar menú de navegación.
 * Parámetros:
 *   $titulo : Es un string con el título del menú
 *   $opciones : Es un array de la forma [ [$etiqueta,$link], ... ]
 *     Cada elemento del array es un array de dos elementos: la etiqueta a mostrar y el enlace al que saltar cuando lo pulse el usuario
 *   $activo : Es el número del elemento del array $opciones que se mostrará resaltado (0, 1, ...)
 *   $activoclass : Es el nombre de la clase CSS que se pondrá al elemento $activo
 */
function HTMLnavbar($opciones, $activo, $activoclass)
{
  $navbar = <<<HTML
  <nav class="navbar">
    <ul class="navbar__list">
  HTML;

  foreach ($opciones as $idx => $item) {
    $itemClass = $idx == $activo ? $activoclass : '';
    $navbar .= <<<HTML
      <li class="navbar__item $itemClass"><a class="navbar__link" href="{$item[1]}">{$item[0]}</a></li>
  HTML;
  }

  $navbar .= <<<HTML
    </ul>
  </nav>
  HTML;

  return $navbar;
}

/***************************
 * Generar el pie de página de la página.
 *  $autor : Es un string con el nombre del autor o autores.
 * $enlaceDocumentacion : Es un string con la URL del documento PDF de la documentación.
 * $enlaceRestauracion : Es un string con la URL del fichero de datos para restaurar la BBDD.
 */
function HTMLfooter($autor, $enlaceDocumentacion, $enlaceRestauracion) {
    return <<< HTML
    <footer>
        <p>Autor/es:
            $autor
        </p>
        <p><a href="$enlaceDocumentacion">Documento PDF documentación</a></p>
        <p><a href="includes/restaurar-bbdd.php?restore=true">Fichero de datos restauración BBDD</a></p>
    </footer>
    HTML;
}
?>
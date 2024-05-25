<?php
/***************************
 * Generar elemento HEAD de la página.
 *   $titulo : Es un string con el título (title) de la página.
 *   $estilos : Es un array de cadenas con los nombres de los ficheros de estilo.
 */
function HTMLhead($titulo,$estilos) {
  return <<< HTML
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
  return <<< HTML
  <header class="header">
    <a href="index.php" class="header__link">
      <img class="header__icon" src="img/icono.png" alt="Icono del hotel">
    </a>
    <h1 class="header__title">Hotel Granada View</h1>
  </header>
  HTML;
}

/**
 * Generar pagina de error
 */
function HTMLpag_error() {
  return <<< HTML
  <section class="error">
    <h2 class="error__title">Página no encontrada</h2>
    <p class="error__message">La página que buscas no existe.</p>
  </section>
  HTML;
}

/**
 * Generar aside para login 
 */
function HTMLaside($totalHabitaciones, $habitacionesLibres, $capacidadTotal, $huespedesAlojados) {
  return <<<HTML
  <aside class="aside_login">
      <h2>Login</h2>
      <form action="login.php" method="post">
          <label for="username">Username:</label>
          <input type="text" id="username" name="username" required><br>
          <label for="password">Password:</label>
          <input type="password" id="password" name="password" required><br>
          <input type="submit" value="Login">
      </form>
      <div class="info_hotel">
          <h3>Información del Hotel</h3>
          <p>Nº total de habitaciones: {$totalHabitaciones}</p>
          <p>Nº de habitaciones libres: {$habitacionesLibres}</p>
          <p>Capacidad total del hotel: {$capacidadTotal} huéspedes</p>
          <p>Nº de huéspedes alojados: {$huespedesAlojados}</p>
      </div>
  </aside>
  HTML;
}
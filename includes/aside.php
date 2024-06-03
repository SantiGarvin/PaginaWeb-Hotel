<?php
require_once 'includes/Session.php';
require_once 'includes/autenticacion.php';
include_once 'reservations.php';

/**
 * Generar aside para login 
 */
function HTMLaside()
{

  $totalHabitaciones = NHabitaciones(); 
  $habitacionesLibres = NHabitacionesLibres();
  $capacidadTotal = CapacidadTotal();
  $huespedesAlojados = NHuespedesAlojados();



  $error = '';
  if (isset($_POST['username']) && isset($_POST['password'])) {
    if (autenticacion()) {
      $user =  Session::get('user');
      header("Location: " . $_SERVER['PHP_SELF']);
    } else {
      $error = 'Usuario o contraseña incorrectos';
    }
  }



  if (!Session::isSessionOpen()) {
    Session::set('nombre', $username);
  } else {
    $nombreUsuario = isset(Session::get('user')['nombre']) ? Session::get('user')['nombre'] : '';
  }


  if (isset($_POST['logout'])) {
    Session::destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
  }


  //Html del aside

  if (null !== Session::get('user') && Session::get('user')['rol'] != 'anonimo') {
    return <<<HTML
      <aside class="aside_login">
        <div class="login">
        <h2>Bienvenido/a, {$nombreUsuario}</h2>
          <form action="" method="post">
            <input type="submit" value="Logout" name="logout" >
          </form>
        </div>
        <div class="info_hotel">
          <h3>Información del Hotel</h3>
          <p>Nº total de habitaciones: {$totalHabitaciones}</p>
          <p>Nº de habitaciones libres: {$habitacionesLibres}</p>
          <p>Capacidad total del hotel: {$capacidadTotal} huéspedes</p>
          <p>Nº de huéspedes alojados: {$huespedesAlojados}</p>
        </div>
      </aside>
    HTML;
  } else {
    ob_start(); // Iniciar el output buffering
?>
    <aside class="aside_login">
      <div class="login">
        <h2>Iniciar sesión</h2>
        <form action="" method="post" novalidate>
          <input type="text" id="username" name="username" placeholder="Usuario" required><br>
          <input type="password" id="password" name="password" placeholder="Contraseña" required><br>
          <input type="submit" value="Login">
          <?php if ($error != '') : ?>
            <div class="">
              <span class="error error-login"><?= htmlspecialchars($error) ?></span>
            </div>
          <?php endif; ?>
        </form>
      </div>
      <div class="info_hotel">
        <h3>Información del Hotel</h3>
        <p>Nº total de habitaciones: <?= htmlspecialchars($totalHabitaciones) ?></p>
        <p>Nº de habitaciones libres: <?= htmlspecialchars($habitacionesLibres) ?></p>
        <p>Capacidad total del hotel: <?= htmlspecialchars($capacidadTotal) ?> huéspedes</p>
        <p>Nº de huéspedes alojados: <?= htmlspecialchars($huespedesAlojados) ?></p>
      </div>
    </aside>
<?php
    return ob_get_clean(); // Obtener el contenido del buffer y limpiarlo
  }
}

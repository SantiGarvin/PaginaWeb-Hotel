<?php
require_once 'includes/Session.php';
require_once 'includes/autenticacion.php';



if(isset($_POST['username']) && isset($_POST['password'])){
  autenticacion();
  $username =  Session::get('name');
}

if(Session::isSessionOpen()){
  $usuario = Session::get('usuario');
}else{
  Session::set('name', $username);
}

/**
 * Generar aside para login 
 */
function HTMLaside($totalHabitaciones, $habitacionesLibres, $capacidadTotal, $huespedesAlojados)
{
  return <<<HTML
  <aside class="aside_login">
      <div class="login">
        <h2>Sign in</h2>
        <form action="" method="post">
          <input type="text" id="username" name="username" placeholder="Usuario" required><br>
          <input type="password" id="password" name="password" placeholder="Contraseña" required><br>
          <input type="submit" value="Login">
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
}

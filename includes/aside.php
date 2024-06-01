<?php
require_once 'includes/Session.php';
require_once 'includes/autenticacion.php';

/**
 * Generar aside para login 
 */
function HTMLaside($totalHabitaciones, $habitacionesLibres, $capacidadTotal, $huespedesAlojados)
{
  $error = '';
  if(isset($_POST['username']) && isset($_POST['password'])){
    if(autenticacion()){
      $user =  Session::get('user');
    }
    else{
      $error = 'Usuario o contraseña incorrectos';
    }
  }
  
  if(!Session::isSessionOpen()){
    Session::set('nombre', $username);
  }


  if(isset($_POST['logout'])){
    Session::destroy();
  }





  //Html del aside

  if(null !== Session::get('user') && Session::get('user')['tipo_usuario'] != 'anonimo'){
      $AUX .= <<<HTML
      <aside class="aside_login">
        <div class="login">
          <h2>Bienvenido, {$user['nombre']}</h2>
          <form action="" method="post">
            <input type="submit" value="Logout" name="logout" >
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
    return $AUX;
  }else{
    $AUX = <<<HTML
    <aside class="aside_login">
        <div class="login">
          <h2>Sign in</h2>
          <form action="" method="post" novalidate>
            <input type="text" id="username" name="username" placeholder="Usuario" required><br>
            <input type="password" id="password" name="password" placeholder="Contraseña" required><br>
            <input type="submit" value="Login">
          </form>
        </div>
    HTML;

    if($error != ''){
      $AUX .= <<<HTML
        <div class="">
          <p>{$error}</p>
        </div>
      HTML;
    }

    $AUX .= <<<HTML
        <div class="info_hotel">
          <h3>Información del Hotel</h3>
          <p>Nº total de habitaciones: {$totalHabitaciones}</p>
          <p>Nº de habitaciones libres: {$habitacionesLibres}</p>
          <p>Capacidad total del hotel: {$capacidadTotal} huéspedes</p>
          <p>Nº de huéspedes alojados: {$huespedesAlojados}</p>
        </div>
    </aside>
    HTML;
    return $AUX;
  }
}

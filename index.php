<?php
session_start();

// Simulación de tipos de usuario
// Esto generalmente vendría de una base de datos o un sistema de autenticación
// Los valores podrían ser 'anonimo', 'registrado', 'recepcionista', 'administrador'
if (!isset($_SESSION['tipo_usuario'])) {
    $_SESSION['tipo_usuario'] = 'anonimo'; // Valor predeterminado
}

$tipo_usuario = $_SESSION['tipo_usuario'];

require 'includes/header.php';
require 'includes/footer.php';
require 'includes/nav.php';
require 'inicio.php';
require 'room.php';
require 'servicios.php';
require 'reservations.php';
require 'register.php';

$debug = false;

// Variables con los datos del autor y los enlaces
$nombres = "Diego Sánchez Vargas y Santiago Garvin Pérez";
$enlace = ".";
$enlace2 = "";

$opc = 0;
if (isset($_GET["p"]) && ($_GET["p"] >= 0 && $_GET["p"] <= 6)) {
    $opc = (int)$_GET['p'];
}

$menu = [
    ['Inicio', 'index.php?p=0'],
    ['Habitaciones', 'index.php?p=1'],
    ['Servicio', 'index.php?p=2'],
];

switch ($tipo_usuario) {
    case 'anonimo':
        $menu[] = ['Registro', 'index.php?p=3'];
        break;
    case 'registrado':
        $menu[] = ['Mi cuenta', 'index.php?p=5'];
        break;
    case 'recepcionista':
        $menu[] = ['Recepcionista', 'index.php?p=4'];
        break;
    case 'administrador':
        $menu[] = ['Admin', 'index.php?p=6'];
        break;
}

$estilos = glob('css/*.css'); // Array con los estilos CSS
$head = HTMLhead("Proyecto Final", $estilos);
$header = HTMLheader();
$menu_html = HTMLnavegacion($menu, $opc, 'activo');
$aside = HTMLaside();
$footer = HTMLfooter($nombres, $enlace, $enlace2);

$cuerpo = match ($opc) {
    0 => HTMLpag_inicio(),
    1 => HTMLhabitaciones(),
    2 => HTMLservicios(),
    3 => HTMLregistro(),
    4 => $tipo_usuario === 'recepcionista' || $tipo_usuario === 'administrador' ? HTMLreservations() : HTMLpag_error(),
    5 => $tipo_usuario === 'registrado' ? HTMLmiCuenta() : HTMLpag_error(),
    6 => $tipo_usuario === 'administrador' ? HTMLadmin() : HTMLpag_error(),
    default => HTMLpag_error()
};

if ($debug) {
    echo "<pre>";
    echo "<h2>DEBUG</h2>";
    echo "<h3>Variables POST</h3>";
    print_r($_POST);
    echo "<h3>Variables GET</h3>";
    print_r($_GET);
    echo "</pre>";

    echo "<h2>Variables</h2>";
    echo "<h3>opc: $opc</h3>";
    echo "<h3>menu:</h3>";
    print_r($menu);
}

echo <<<HTML
<!DOCTYPE html>
<html>
  $head
  <body>
    $header
    $menu_html
    $cuerpo
    $aside
    $footer
  </body>
</html>
HTML;
 
?>
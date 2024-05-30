<?php
require_once 'includes/header.php';
require_once 'includes/footer.php';
require_once 'includes/nav.php';
require_once 'inicio.php';
require_once 'room.php';
require_once 'servicios.php';
require_once 'reservations.php';
require_once 'register.php';
require_once 'reservar.php';
require_once 'miCuenta.php';
require_once 'admin.php';

session_start();

// Simulación de tipos de usuario
// Esto generalmente vendría de una base de datos o un sistema de autenticación
// Los valores podrían ser 'anonimo', 'registrado', 'recepcionista', 'administrador'
if (!isset($_SESSION['tipo_usuario'])) {
    $_SESSION['tipo_usuario'] = 'anonimo'; // Valor predeterminado
}

$tipo_usuario = $_SESSION['tipo_usuario'];

// Variables de DEBUG y simulación de usuario
$debug = false;
$tipo_usuario = 'administrador'; // Simulación de usuario registrado

// Variables con los datos del autor y los enlaces (información para el footer)
$autores = "Diego Sánchez Vargas y Santiago Garvin Pérez";
$enlace = ".";
$enlace2 = "";

$opc = 0;
if (isset($_GET["p"]) && ($_GET["p"] >= 0 && $_GET["p"] <= 7)) {
    $opc = (int)$_GET['p'];
}

$menu = [
    ['Inicio', 'index.php?p=0'],
    ['Habitaciones', 'index.php?p=1'],
    ['Servicio', 'index.php?p=2'],
];

$itemsAdicionalesMenu = match ($tipo_usuario) {
    'anonimo'           => [['Registro', 'index.php?p=3']],
    'registrado'        => [
        ['Reservar', 'index.php?p=5'],
        ['Mi cuenta', 'index.php?p=6']
    ],
    'recepcionista'     => [['Recepcionista', 'index.php?p=4']],
    'administrador'     => [
        ['Reservar', 'index.php?p=5'],
        ['Mi cuenta', 'index.php?p=6'],
        ['Recepcionista', 'index.php?p=4'],
        ['Admin', 'index.php?p=7']
    ],
    default             => []
};

$menu = array_merge($menu, $itemsAdicionalesMenu);

$estilos = glob('css/*.css'); // Array con los estilos CSS
$head = HTMLhead("Proyecto Final", $estilos);
$header = HTMLheader();
$menu = HTMLnavbar($menu, $opc, 'activo');
$aside = HTMLaside(3, 2, 9, 5); //$totalHabitaciones, $habitacionesLibres, $capacidadTotal, $huespedesAlojados
$footer = HTMLfooter($autores, $enlace, $enlace2);

$cuerpo = match ($opc) {
    0 => HTMLpag_inicio(),
    1 => HTMLhabitaciones(),
    2 => HTMLservicios(),
    3 => HTMLregistro(),
    4 => $tipo_usuario === 'recepcionista' || $tipo_usuario === 'administrador' ? HTMLreservations() : HTMLpag_error(),
    5 => $tipo_usuario === 'registrado' || $tipo_usuario === 'administrador' ? HTMLreservar() : HTMLpag_error(),
    6 => $tipo_usuario === 'registrado' || $tipo_usuario === 'administrador' ? HTMLmicuenta() : HTMLpag_error(),
    7 => $tipo_usuario === 'administrador' || $tipo_usuario === 'administrador' ? HTMLadmin() : HTMLpag_error(),
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
    echo "<h3>tipo_usuario: $tipo_usuario</h3>";
}
?>

<!DOCTYPE html>
<html>
  <?= $head ?>
<body>
    <?= $header ?>
    <?= $menu ?>
    <div class="content">
        <?= $cuerpo ?>
        <?= $aside ?>
    </div>
    <?= $footer ?>
  </body>
</html>
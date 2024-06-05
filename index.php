<?php
require_once 'includes/header_navbar_footer_login_procesarE.php';
require_once 'includes/aside.php';
require_once 'inicio.php';
require_once 'room.php';
require_once 'servicios.php';
require_once 'recepcionista.php';
require_once 'registro.php';
require_once 'reservar.php';
require_once 'mi-cuenta.php';
require_once 'admin.php';
require_once 'includes/Session.php';

Session::init();

if (!Session::get('user')) {
    Session::set('user', ['rol' => 'anonimo']); // Valor predeterminado
}

$tipo_usuario = Session::get('user')['rol'];

$error = '';

// Variables con los datos del autor y los enlaces (información para el footer)
$autores = "Diego Sánchez Vargas y Santiago Garvín Pérez";

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
    'Cliente'           => [
        ['Reservar', 'index.php?p=5'],
        ['Mi cuenta', 'index.php?p=6']
    ],
    'Recepcionista'     => [
        ['Reservar', 'index.php?p=5'],
        ['Mi cuenta', 'index.php?p=6'],
        ['Recepcionista', 'index.php?p=4']
    ],
    'Administrador'     => [
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
$aside = HTMLaside(); 
$footer = HTMLfooter($autores, "ProyectoFinalTW.pdf", "");
$intervalo_tiempo_borrado_reservas = '30 SECOND';  // Intervalo de tiempo para borrar las reservas antiguas

$cuerpo = match ($opc) {
    0 => HTMLpag_inicio(),
    1 => HTMLhabitaciones(),
    2 => HTMLservicios(),
    3 => HTMLregistro(),
    4 => $tipo_usuario === 'Recepcionista' || $tipo_usuario === 'Administrador' ? HTMLreservations() : HTMLpag_error(),
    5 => $tipo_usuario === 'Cliente' || $tipo_usuario === 'Recepcionista' || $tipo_usuario === 'Administrador' ? HTMLreservar($intervalo_tiempo_borrado_reservas) : HTMLpag_error(),
    6 => $tipo_usuario === 'Cliente' || $tipo_usuario === 'Recepcionista' || $tipo_usuario === 'Administrador' ? HTMLmicuenta() : HTMLpag_error(),
    7 => $tipo_usuario === 'Administrador' ? HTMLadmin() : HTMLpag_error(),
    default => HTMLpag_error()
};

$debug = false;

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

    echo "<h3>Variables de sesión</h3>";
    echo "<pre>";
    print_r(Session::get('user'));
    echo "</pre>";
    print_r(Session::getAll());
    echo "<h3>Variables de sesión</h3>";
}
?>

<!DOCTYPE html>
<html>
<?= $head ?>

<body>
    <?= $header ?>
    <?= $menu ?>
    <div class="content-wrapper">
        <?php if ($error) : ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?= $cuerpo ?>
        <?= $aside ?>
    </div>
    <?= $footer ?>
</body>

</html>
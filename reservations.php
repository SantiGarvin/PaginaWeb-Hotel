<?php
function HTMLreservations() {
    return <<<HTML
        <head>
        <link rel="stylesheet" href="css/styles-reservas.css">
        </head>
        <main class="main-content">
            <h2>Estado de Reservas</h2>
            <div class="reservations">
                <div class="reservation">
                    <div class="reservation__id">1234</div>
                    <div class="reservation__name"><strong>Juan Pérez</strong></div>
                    <div class="reservation__room-type">Doble</div>
                    <div class="reservation__arrival-date">2024-08-01</div>
                    <div class="reservation__departure-date">2024-08-05</div>
                    <div class="reservation__status confirmed">Confirmada</div>
                </div>
                <div class="reservation">
                    <div class="reservation__id">2345</div>
                    <div class="reservation__name"><strong>María González</strong></div>
                    <div class="reservation__room-type">Individual</div>
                    <div class="reservation__arrival-date">2024-08-03</div>
                    <div class="reservation__departure-date">2024-08-07</div>
                    <div class="reservation__status pending">Pendiente</div>
                </div>
                <div class="reservation">
                    <div class="reservation__id">3456</div>
                    <div class="reservation__name"><strong>Carlos Ruiz</strong></div>
                    <div class="reservation__room-type">Suite</div>
                    <div class="reservation__arrival-date">2024-08-10</div>
                    <div class="reservation__departure-date">2024-08-14</div>
                    <div class="reservation__status confirmed">Confirmada</div>
                </div>
                <div class="reservation">
                    <div class="reservation__id">4567</div>
                    <div class="reservation__name"><strong>Laura Torres</strong></div>
                    <div class="reservation__room-type">Doble</div>
                    <div class="reservation__arrival-date">2024-08-15</div>
                    <div class="reservation__departure-date">2024-08-20</div>
                    <div class="reservation__status pending">Pendiente</div>
                </div>
                <div class="reservation">
                    <div class="reservation__id">5678</div>
                    <div class="reservation__name"><strong>Andrés Gómez</strong></div>
                    <div class="reservation__room-type">Individual</div>
                    <div class="reservation__arrival-date">2024-08-22</div>
                    <div class="reservation__departure-date">2024-08-25</div>
                    <div class="reservation__status cancelled">Cancelada</div>
                </div>
            </div>
        </main>
    HTML;
}
?>
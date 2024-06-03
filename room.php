<?php
function HTMLhabitaciones()
{
    return <<<HTML
    <main class="main-content">
        <h2>Nuestras Habitaciones</h2>

        <section class="habitacion">
            <h3>Habitación Individual</h3>
            <img src="img/habitacion_individual.jpg" alt="Habitación Individual" width="600" height="400">
            <p class="capacidad"><strong>Capacidad:</strong> 1 persona</p>
            <p>Nuestra habitación individual ofrece un espacio acogedor y funcional para el viajero solitario. Equipada
                    con una cama individual cómoda, un escritorio para trabajar y un baño privado con ducha.</p>
        </section>

        <section class="habitacion">
            <h3>Habitación Doble</h3>
            <img src="img/habitacion_doble.jpg" alt="Habitación Doble" width="600" height="400">
            <p class="capacidad"><strong>Capacidad:</strong> 2 personas</p>
            <p>Perfecta para parejas o compañeros de viaje, nuestra habitación doble cuenta con dos camas individuales o
                    una cama matrimonial, según su preferencia. Disfrute de un amplio espacio con todas las comodidades,
                    incluyendo un baño privado completo.</p>
        </section>

        <section class="habitacion">
            <h3>Suite</h3>
            <img src="img/habitacion_suite.jpg" alt="Suite" width="600" height="400">
            <p class="capacidad"><strong>Capacidad:</strong> 2-4 personas</p>
            <p>Para una experiencia de lujo, elija nuestra espaciosa suite. Con un dormitorio separado, sala de estar y
                    un amplio baño con bañera y ducha, es ideal para familias o para aquellos que buscan más espacio y
                    comodidad.</p>
        </section>
    </main>
HTML;
}

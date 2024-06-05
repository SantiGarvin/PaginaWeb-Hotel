<?php
function HTMLpag_inicio()
{
	return <<<HTML
	<main class="main-content">
		<section class="main-section">
			<h2>Bienvenido a Granada View</h2>
			<p><strong>Granada View</strong>, su hotel con las mejores vistas de la Alhambra. Situado en pleno corazón
				de la histórica ciudad de Granada, nuestro hotel ofrece una experiencia única donde podrá disfrutar de
				atardeceres inolvidables con el emblemático palacio nazarí como telón de fondo.</p>
			<p>Disfrute de nuestras habitaciones amplias y luminosas, equipadas con todo el confort para una estancia
				perfecta.</p>
			<img src="img/hotel_fachada.jpg" alt="Fachada del Hotel Granada View" width="900">
		</section>

		<section class="galeria">
			<h2>Galería de imágenes</h2>
			<ul>
				<li><img src="img/piscina.jpg" alt="Piscina" width="500" height="350"></li>
				<li><img src="img/restaurante.jpg" alt="Restaurante" width="500" height="350"></li>
			</ul>
		</section>

		<section class="eventos">
			<h2>Eventos de interés</h2>
			<ul>
				<li><a href="https://granadafestival.org/" target="_blank">Festival Internacional de Música y Danza de Granada</a></li>
				<li><a href="https://granadasound.com/" target="_blank">Festival de Música Electrónica Granada Sound</a></li>
				<li><a href="https://www.festivaldecinegranada.com/" target="_blank">Festival de Cine de Granada</a></li>
			</ul>
		</section>
	</main>
	HTML;
}
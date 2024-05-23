<?php
function HTMLreservar() {
    return <<<HTML
    <main class="main-content">
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="version_formulario" name="version_formulario" value="1.0">

            <fieldset class="datos-personales">
                <legend>Datos reserva</legend>

                <div class="fila">
                    <div class="columna columna-nombre-apellidos">
                    <label for="fecha-nacimiento">
                            F. entrada:
                            <input type="date" id="fecha-nacimiento" name="fecha_entrada" required>
                        </label>

                        <label for="fecha-nacimiento">
                            F. salida:
                            <input type="date" id="fecha-nacimiento" name="fecha_salida" required>
                        </label>
                    </div>
                </div>
                <div class="fila">
                    <div class="columna">
                        <label for="dni">
                            Cantidad de personas:
                            <input type="text" id="dni" name="dni" placeholder="Nº de camas necesarias" required>
                        </label>
                    </div>
                </div>
                <span class="texto-hover texto-personales">En cumplimiento del Real Decreto 933/2021, de 26 de octubre,
                    estos datos serán comunicados al centro de datos de la Dirección General de la Policía.</span>
            </fieldset>

            <fieldset class="preferencias">
                <legend>Preferencias</legend>

                <div class="columna idiomas">
                    <label class="enunciado">Idioma para comunicaciones:</label>
                    <div class="seleccion">
                        <label for="idioma_es">
                            <input type="radio" id="idioma_es" name="idioma" value="español" checked>
                            Español
                        </label>
                        <label for="idioma_en">
                            <input type="radio" id="idioma_en" name="idioma" value="inglés">
                            Inglés
                        </label>
                        <label for="idioma_fr">
                            <input type="radio" id="idioma_fr" name="idioma" value="francés">
                            Francés
                        </label>
                    </div>
                </div>

                <div class="columna habitacion">
                    <label class="enunciado">Preferencias de habitación:</label>
                    <div class="seleccion">
                        <label for="preferencia1">
                            <input type="checkbox" id="preferencia1" name="preferencias[]" value="Opción 1">
                            Para fumadores
                        </label>
                        <label for="preferencia2">
                            <input type="checkbox" id="preferencia2" name="preferencias[]" value="Opción 2">
                            Que permita mascotas
                        </label>
                        <label for="preferencia3">
                            <input type="checkbox" id="preferencia3" name="preferencias[]" value="Opción 3">
                            Con vistas
                        </label>
                        <label for="preferencia4">
                            <input type="checkbox" id="preferencia4" name="preferencias[]" value="Opción 4">
                            Con moqueta
                        </label>
                    </div>
                </div>
            </fieldset>
            
            <input type="submit" name="enviar" value="Enviar datos">
        </form>
    </main>
HTML;
}
?>
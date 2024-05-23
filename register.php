<?php
function HTMLregistro() {
    return <<<HTML
    <main class="main-content">
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="version_formulario" name="version_formulario" value="1.0">

            <fieldset class="datos-personales">
                <legend>Datos personales</legend>

                <div class="fila">
                    <div class="columna columna-nombre-apellidos">
                        <label for="nombre">
                            Nombre:
                            <input type="text" id="nombre" name="nombre" placeholder="(Obligatorio)" required size="20"
                                maxlength="40">
                        </label>

                        <label for="apellidos">
                            Apellidos:
                            <input type="text" id="apellidos" name="apellidos" title="Este campo es opcional">
                        </label>
                    </div>
                </div>
                <div class="fila">
                    <div class="columna">
                        <label for="dni">
                            DNI:
                            <input type="text" id="dni" name="dni" placeholder="12345678A" required
                                pattern="[0-9]{8}[A-Z]">
                        </label>

                        <label for="fecha-nacimiento">
                            F. nacimiento:
                            <input type="date" id="fecha-nacimiento" name="fecha_nacimiento" required>
                        </label>
                    </div>
                    <div class="columna">
                        <label for="nacionalidad">
                            Nacionalidad:
                            <input type="text" id="nacionalidad" name="nacionalidad" value="España">
                        </label>

                        <label for="sexo">
                            <label for="dni">
                                Tarjeta:
                                <input type="text" id="dni" name="tarjetaC" placeholder="#### #### #### ####" required
                                    pattern="[0-9]{12}">
                            </label>
                        </label>
                    </div>
                </div>
                <span class="texto-hover texto-personales">En cumplimiento del Real Decreto 933/2021, de 26 de octubre,
                    estos datos serán comunicados al centro de datos de la Dirección General de la Policía.</span>
            </fieldset>

            <fieldset class="datos-acceso">
                <legend>Datos de acceso</legend>

                <div class="fila correo">
                    <div class="columna">
                        <label for="correo">
                            E-mail:
                            <input type="email" id="correo" name="correo" placeholder="correo@example.com" required>
                        </label>
                    </div>
                </div>
                <div class="fila clave">
                    <div class="columna">
                        <label for="password">
                            Clave:
                            <input type="password" id="password" name="password" placeholder="Introduzca una clave"
                                required>
                        </label>
                    </div>

                    <div class="columna">
                        <label for="password2">
                            Repita clave:
                            <input type="password" id="password2" name="password2" placeholder="Escriba la misma clave"
                                required>
                        </label>
                    </div>
                </div>
                <span class="texto-hover texto-acceso">Usted podrá acceder al sistema en cualquier momento mediante
                    estos
                    datos. Asegúrese de escribir una clave que pueda recordar con posterioridad. Si la olvida siempre
                    podrá
                    recuperarla a través de su correo electrónico.
                </span>
            </fieldset>
            <input type="submit" name="enviar" value="Enviar datos">
        </form>
    </main>
HTML;
}
?>
<?php
//Definimos la función HTMLfooter con los parámetros $autor, $enlaceDocumentacion y $enlaceRestauracion
function HTMLfooter($autor, $enlaceDocumentacion, $enlaceRestauracion) {
    echo <<< HTML
    <footer>
        <p>Autor/es: $autor</p>
        <p><a href="$enlaceDocumentacion">Documento PDF documentación</a></p>
        <p><a href="$enlaceRestauracion">Fichero de datos restauración BBDD</a></p>
    </footer>.
    HTML;
}

?>
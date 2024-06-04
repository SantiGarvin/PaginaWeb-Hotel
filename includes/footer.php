<?php
/**
 * Generar el pie de página de la página.
 *  $autor : Es un string con el nombre del autor o autores.
 * $enlaceDocumentacion : Es un string con la URL del documento PDF de la documentación.
 * $enlaceRestauracion : Es un string con la URL del fichero de datos para restaurar la BBDD.
 */
function HTMLfooter($autor, $enlaceDocumentacion, $enlaceRestauracion) {
    return <<< HTML
    <footer>
        <p>Autor/es:
            $autor
        </p>
        <p><a href="$enlaceDocumentacion">Documento PDF documentación</a></p>
        <p><a href="includes/restaurar.php?restore=true">Fichero de datos restauración BBDD</a></p>
    </footer>
    HTML;
}

?>
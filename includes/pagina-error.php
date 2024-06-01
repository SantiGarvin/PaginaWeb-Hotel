<?php
/**
 * Generar pagina de error
 */
function HTMLpag_error()
{
  return <<<HTML
  <section class="error">
    <h2 class="error__title">Página no encontrada</h2>
    <p class="error__message">La página que buscas no existe.</p>
  </section>
  HTML;
}

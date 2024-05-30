<?php

/***************************
 * Generar menú de navegación.
 * Parámetros:
 *   $titulo : Es un string con el título del menú
 *   $opciones : Es un array de la forma [ [$etiqueta,$link], ... ]
 *     Cada elemento del array es un array de dos elementos: la etiqueta a mostrar y el enlace al que saltar cuando lo pulse el usuario
 *   $activo : Es el número del elemento del array $opciones que se mostrará resaltado (0, 1, ...)
 *   $activoclass : Es el nombre de la clase CSS que se pondrá al elemento $activo
 */
function HTMLnavbar($opciones, $activo, $activoclass)
{
  $navbar = <<<HTML
  <nav class="navbar">
    <ul class="navbar__list">
  HTML;

  foreach ($opciones as $idx => $item) {
    $itemClass = $idx == $activo ? $activoclass : '';
    $navbar .= <<<HTML
      <li class="navbar__item $itemClass"><a class="navbar__link" href="{$item[1]}">{$item[0]}</a></li>
  HTML;
  }

  $navbar .= <<<HTML
    </ul>
  </nav>
  HTML;

  return $navbar;
}
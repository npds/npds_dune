<?php
/************************************************************************/
/* Theme for NPDS / Net Portal Dynamic System                           */
/* =====================================================================*/
/*                                                                      */
/* Theme : "Mouse-IT2" version 2011 Dev / based on Marina               */
/* This theme use the NPDS theme-dynamic engine (DynaMot)               */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

$theme      = "Mouse-IT2";
$long_chain = "25"; // Nombre de caractères affiché avant troncature pour certains blocs

// Par defaut NPDS utilise pour une partie de sa présentation les fonctions OpenTable() - CloseTable() ET OpenTable2() - CloseTable2()
// SAUF si le theme propose ces propres fonction ! - Ce nouveau moteur de theme n'a pas besoin de cela donc les 4 fonctions sont vides
function opentable_theme() { }   function closetable_theme() { }
function opentable2_theme() { }  function closetable2_theme() { }

// ne pas supprimer cette ligne / Don't remove this line
require_once("themes/themes-dynamic/theme.php");
// ne pas supprimer cette ligne / Don't remove this line
?>
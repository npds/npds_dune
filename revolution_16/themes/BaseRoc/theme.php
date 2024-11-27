<?php
/************************************************************************/
/* Theme for NPDS / Net Portal Dynamic System                           */
/* =====================================================================*/
/*        Theme : jpb Jireck Bmag     BaseRoc                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
/*
$theme          = Nom du thème (un suffix _sk permet l'utilisation des skins)
$theme_darkness = 'light' ou 'dark' ou 'auto' 
                  light  : (utilise les classes défaut de bootstrap)
                  dark : (utilise les classes dark de bootstrap)
                  auto : (utilise automatiquement et alternativement (light/dark) les classes de bootsrap en fonction de la configuration de l'application|système)
$long_chain     = Nombre de caractères affichés avant troncature pour certains blocs
*/

$theme          = 'BaseRoc';
$theme_darkness = 'light';
$long_chain     = '15';

// ne pas supprimer cette ligne / Don't remove this line
require_once("themes/themes-dynamic/theme.php");
// ne pas supprimer cette ligne / Don't remove this line
?>
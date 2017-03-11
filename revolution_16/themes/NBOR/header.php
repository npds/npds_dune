<?php
/************************************************************************/
/* Theme for NPDS / Net Portal Dynamic System     NBOR                  */
/*======================================================================*/
/* This theme use the NPDS theme-dynamic engine (Meta-Lang)             */
/*                                                                      */
/* Theme : jpb Jireck Bmag     NBOR                                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

global $pdst;
$pdst=4;

$blg_actif = sql_query("SELECT * FROM ".$NPDS_Prefix."lblocks WHERE actif ='1'");
$nb_blg_actif = sql_num_rows($blg_actif);
$bld_actif = sql_query("SELECT * FROM ".$NPDS_Prefix."rblocks WHERE actif ='1'");
$nb_bld_actif = sql_num_rows($bld_actif);

/*
La variable $pdst permet de gérer le nombre et la disposition des colonnes
 "-1" -> col_princ
 "2"  -> col_princ + col_RB
 "4"  -> col_princ + col_LB + col_RB
 "6"  -> col_princ + col_LB
 
 La gestion de ce paramètre s'effectue dans le fichier "pages.php" du dossier "themes

 Nomination des div :
 col_princ contient le contenu principal
 col_LB contient les blocs historiquement dit de gauche
 col_RB contient les blocs historiquement dit de droite
*/

if ($nb_blg_actif == 0) {
    switch ($pdst)
    {
    case '4': $pdst='2'; break;
    }
}
if ($nb_bld_actif == 0) {
    switch ($pdst)
    {
    case "2": $pdst='-1'; break;
    case "4": $pdst='6'; break;
    }
}

// ContainerGlobal permet de transmettre ‡ Theme-Dynbamic un ÈlÈment de personnalisation avant
// le chargement de header.html / Si vide alors la class body est chargÈe par dÈfaut par Theme dynamique
$ContainerGlobal="\n<div id=\"container\">\n";

// Ne supprimez pas cette ligne / Don't remove this line
   require_once("themes/themes-dynamic/header.php");
   global $powerpack;
   if (!isset($powerpack)) {include ("powerpack.php");}
// Ne supprimez pas cette ligne / Don't remove this line

/************************************************************************/
/*     Le corps de page de votre Site - En dessous du Header            */
/*     On Ouvre les DiffÈrent Blocs en Fonction de la Variable $pdst    */
/*                         Le corps englobe :                           */
/*                 col_LB + col_princ + col_RB                          */
/*           Si Aucune variable pdst dans pages.php                     */
/*   ==> Alors affichage par defaut : col_LB + col_princ soit $pdst=0   */
/* =====================================================================*/
     echo '<section id="corps" class="row1 row " role="main">';
switch ($pdst) {
case '-1':
   echo '<section id="col_princ" class="col-12 ">';
break;
case '2':
   echo '<section id="col_princ" class="col-lg-9 w81 ">';
break;
case '4':
   echo '<section id="col_princ" class="col-lg-6 w62 ">';
break;
case '6':
   echo '<section id="col_princ" class="col-lg-9 w81 ">';
 break;
default:
     echo '<aside id="col_LB" class="n-c col-lg-3 w19 notablet">';
        leftblocks();
     echo '</aside>';
     echo '<section id="col_princ" class="col-lg-9 col w81 ">';
break;
}
?>
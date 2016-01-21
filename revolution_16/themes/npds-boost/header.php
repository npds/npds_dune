<?php
/************************************************************************/
/* Theme for NPDS / Net Portal Dynamic System                           */
/*======================================================================*/
/* This theme use the NPDS theme-dynamic engine (Meta-Lang)             */
/*                                                                      */
/* Theme : npds-boost 2015 by jpb                                       */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

global $NPDS_Prefix, $pdst;
$blg_actif = sql_query("SELECT * FROM ".$NPDS_Prefix."lblocks WHERE actif ='1'");
$nb_blg_actif = sql_num_rows($blg_actif);
$bld_actif = sql_query("SELECT * FROM ".$NPDS_Prefix."rblocks WHERE actif ='1'");
$nb_bld_actif = sql_num_rows($bld_actif);

/*
La variable $pdst permet de gÈrer le nombre et la disposition des colonnes 
 "-1" -> col_princ
 "0"  -> col_LB + col_princ
 "1"  -> col_LB + col_princ + col_RB
 "2"  -> col_princ + col_RB
 "3"  -> col_LB + col_RB + col_princ
 "4"  -> col_princ + col_LB + col_RB
 "5"  -> col_RB + col_princ
 "6"  -> col_princ + col_LB
 
 La gestion de ce paramÈtre s'effectue dans le fichier "pages.php" du dossier "themes

 Nomination des div :
 col_princ contient le contenu principal
 col_LB contient les blocs historiquement dit de gauche
 col_RB contient les blocs historiquement dit de droite
*/

if ($nb_blg_actif == 0) {
    switch ($pdst)
    {
    case "0": $pdst='-1'; break;
    case "1": $pdst='2'; break;
    case "3": $pdst='5'; break;
    case "4": $pdst='2'; break;
    case "6": $pdst='-1'; break;
    }
}
if ($nb_bld_actif == 0) {
    switch ($pdst)
    {
    case "1": $pdst='0'; break;
    case "2": $pdst='-1'; break;
    case "3": $pdst='0'; break;
    case "4": $pdst='6'; break;
    case "5": $pdst='-1'; break;
    }
}

// ContainerGlobal permet de transmettre ‡ Theme-Dynbamic un »l»ment de personnalisation avant
// le chargement de header.html / Si vide alors la class body est charg»e par d»faut par TD
$ContainerGlobal="\n".'<div id="container" class="">'."\n";

// Ne supprimez pas cette ligne / Don't remove this line
   require_once("themes/themes-dynamic/header.php");
   global $powerpack;
   if (!isset($powerpack)) {include ("powerpack.php");}
// Ne supprimez pas cette ligne / Don't remove this line

   
/************************************************************************/
/*     Le corps de page de votre Site - En dessous du Header            */
/*     On Ouvre les Diffªrent Blocs en Fonction de la Variable $pdst    */
/*                         Le corps englobe :                           */
/*                 col_LB + col_princ + col_RB                          */
/*           Si Aucune variable pdst dans pages.php                     */
/*   ==> Alors affichage par defaut : col_LB + col_princ soit $pdst=0   */
/* =====================================================================*/
     echo "\n".'   <div id="corps" class="container-fluid"><div class="row">'."\n";

switch ($pdst) {
case "-1":
     echo '<div id="col_princ" class="col-xs-12">'."\n";
break;
case "1":
     echo '<div id="col_LB" class="col2 collapse navbar-toggleable-sm col-sm-3">'."\n";
        leftblocks();
     echo "\n".'</div>'."\n";
     echo '<div id="col_princ" class="col-xs-12 col-sm-6">';
break;
case "2":
case "6":
     echo '<div id="col_princ" class="col-xs-12 col-sm-9">'."\n";
break;
case "3":
     echo '<div id="col_LB" class="col2 collapse navbar-toggleable-sm col-sm-3">'."\n";
        leftblocks();
     echo "\n".'</div>'."\n";
     echo '<div id="col_RB" class="collapse navbar-toggleable-sm col-sm-3">'."\n";
        rightblocks();
     echo '</div>'."\n";
     echo '<div id="col_princ" class="col-xs-12 col-sm-6">'."\n";
break;
case "4":
     echo '<div id="col_princ" class="col-xs-12 col-sm-6">'."\n";
break;
case "5":
     echo '<div id="col_RB" class="col-xs-12 col-sm-3">'."\n";
        rightblocks();
     echo '</div>'."\n";
     echo '<div id="col_princ" class="col-xs-12 col-sm-9">'."\n";
break;
default:
     echo '<div id="col_LB" class="col3 collapse navbar-toggleable-sm col-sm-3">'."\n";
        leftblocks();
     echo "\n".'</div>'."\n";
     echo '<div id="col_princ" class="col-xs-12 col-sm-9">'."\n";
break;
}
?>
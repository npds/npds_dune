<?php
/************************************************************************/
/* Theme for NPDS / Net Portal Dynamic System     Rocssti2015           */
/*======================================================================*/
/* This theme use the NPDS theme-dynamic engine (Meta-Lang)             */
/*                                                                      */
/* Theme :  Bmag jpb Jireck      Rev2016                                */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
global $NPDS_Prefix, $pdst;
$pdst='2'; 

$blg_actif = sql_query("SELECT * FROM ".$NPDS_Prefix."lblocks WHERE actif ='1' AND member ='0'");
$nb_blg_actif = sql_num_rows($blg_actif);
$bld_actif = sql_query("SELECT * FROM ".$NPDS_Prefix."rblocks WHERE actif ='1' AND member ='0'");
$nb_bld_actif = sql_num_rows($bld_actif);

/*
La variable $pdst permet de gérer le nombre et la disposition des colonnes
 "-1" -> col_princ
 "0"  -> col_LB + col_princ
 "1"  -> col_LB + col_princ + col_RB
 "2"  -> col_princ + col_RB
 "3"  -> col_LB + col_RB + col_princ
 "4"  -> col_princ + col_LB + col_RB
 "5"  -> col_RB + col_princ
 "6"  -> col_princ + col_LB
 
 La gestion de ce paramètre s'effectue dans le fichier "pages.php" du dossier "themes

 Nomination des div :
 col_princ contient le contenu principal
 col_LB contient les blocs historiquement dit de gauche
 col_RB contient les blocs historiquement dit de droite
*/

if (($nb_blg_actif == 0) and ($nb_bld_actif == 0)){
    switch ($pdst) {
    case '2': $pdst='-1'; break;
    }
}

// ContainerGlobal permet de transmettre à Theme-Dynamic un élément de personnalisation avant
// le chargement de header.html / Si vide alors la class body est chargée par défaut par Theme dynamique
$ContainerGlobal='
<div id="container">';

// Ne supprimez pas cette ligne / Don't remove this line
   require_once("themes/themes-dynamic/header.php");
   global $powerpack;
   if (!isset($powerpack)) {include ("powerpack.php");}

/************************************************************************/
/*     Le corps de page de votre Site - En dessous du Header            */
/*     On Ouvre les Différent Blocs en Fonction de la Variable $pdst    */
/*                         Le corps englobe :                           */
/*                 col_princ + col_RB                                   */
/*           Si Aucune variable pdst dans pages.php                     */
/*   ==> Alors affichage par defaut : col_princ + col_RB soit $pdst=0   */
/* =====================================================================*/
   echo '
   <div id="corps" class="row" role="main">';
   switch ($pdst) {
      case '-1':
        echo '
     <div id="col_princ" class="col-sm-12">';
      break;
      case '2':
        echo '
     <div id="col_princ" class="col-md-9">';
      break;
      default:
        echo '
     <div id="col_princ" class="col-md-9">';
      break;
   }
?>
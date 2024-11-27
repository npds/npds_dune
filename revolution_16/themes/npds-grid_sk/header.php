<?php
/************************************************************************/
/* Theme for NPDS / Net Portal Dynamic System                           */
/*======================================================================*/
/* This theme use the NPDS theme-dynamic engine (Meta-Lang)             */
/*                                                                      */
/* Theme : npds-grid_sk 2022 by jpb                                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

global $NPDS_Prefix, $pdst;
$moreclass = 'col';
$blg_actif = sql_query("SELECT * FROM ".$NPDS_Prefix."lblocks WHERE actif ='1'");
$nb_blg_actif = sql_num_rows($blg_actif);
sql_free_result($blg_actif);
$bld_actif = sql_query("SELECT * FROM ".$NPDS_Prefix."rblocks WHERE actif ='1'");
$nb_bld_actif = sql_num_rows($bld_actif);
sql_free_result($bld_actif);

/*
 Nomination des div par l'attribut id:
 #col_princ contient le contenu principal
 #col_LB contient les blocs historiquement dit de gauche
 #col_RB contient les blocs historiquement dit de droite

Dans ce thème la variable $pdst permet de gérer le nombre et la disposition initiale des colonnes (de gauche à droite  pour une largeur supérieur à 768px | de haut en bas pour une largeur inférieur à 768px).
 "-1" -> col_princ
 "0"  -> col_LB + col_princ
 "1"  -> col_LB + col_princ + col_RB
 "2"  -> col_princ + col_RB
 "3"  -> col_LB + col_RB + col_princ
 "4"  -> col_princ + col_LB + col_RB
 "5"  -> col_RB + col_princ
 "6"  -> col_princ + col_LB
 
La gestion de ce paramètre s'effectue dans le fichier "pages.php" du dossier "themes
*/

if ($nb_blg_actif == 0) {
   switch ($pdst) {
      case '0': $pdst='-1'; break;
      case '1': $pdst='2'; break;
      case '3': $pdst='5'; break;
      case '4': $pdst='2'; break;
      case '6': $pdst='-1'; break;
   }
}
if ($nb_bld_actif == 0) {
   switch ($pdst) {
      case '1': $pdst='0'; break;
      case '2': $pdst='-1'; break;
      case '3': $pdst='0'; break;
      case '4': $pdst='6'; break;
      case '5': $pdst='-1';break;
   }
}

// ContainerGlobal permet de transmettre à Theme-Dynamic un élément de personnalisation avant
// le chargement de header.html / Si vide alors la class body est chargée par défaut par TD
$ContainerGlobal='
      <div id="container" class="maingrid">';

// Ne supprimez pas cette ligne / Don't remove this line
   require_once("themes/themes-dynamic/header.php");
   global $powerpack;
   if (!isset($powerpack)) include ("powerpack.php");
// Ne supprimez pas cette ligne / Don't remove this line

   
/************************************************************************/
/*     Le corps de page de votre Site - En dessous du Header            */
/*     On Ouvre les Différent Blocs en Fonction de la Variable $pdst    */
/*                         Le corps englobe :                           */
/*                 col_LB + col_princ + col_RB                          */
/*           Si Aucune variable pdst dans pages.php                     */
/*   ==> Alors affichage par defaut : col_LB + col_princ soit $pdst=0   */
/* =====================================================================*/

switch ($pdst) {
   case '0':  $classgrid = 'corps0'; break;
   case '1':  $classgrid = 'corps1'; break;
   case '-1': $classgrid = 'corps-1'; break;
   case '2':  $classgrid = 'corps2'; break;
   case '3':  $classgrid = 'corps3'; break;
   case '4':  $classgrid = 'corps4'; break;
   case '5':  $classgrid = 'corps5'; break;
   case '6':  $classgrid = 'corps6'; break;
   default:   $classgrid = 'corps0'; break;
}

echo '
         <div id="corps" class="corps '.$classgrid.' px-3">';
switch ($pdst) {
   case '-1':
      echo '
            <div id="col_princ" class="col-princ">';
   break;
   case '1':
      echo '
            <div id="col_LB" class="col-g">';
      leftblocks($moreclass);
      echo '
            </div>
            <div id="col_princ" class="col-princ">';
   break;
   case '2': case '6':
      echo '
            <div id="col_princ" class="col-princ">';
   break;
   case '3':
      echo '
            <div id="col_LB" class="col-g">';
      leftblocks($moreclass);
      echo '
            </div>';
      echo' 
            <div id="col_RB" class="col-d">';
      rightblocks($moreclass);
      echo '
            </div>
            <div id="col_princ" class="col-princ">';
   break;
   case '4':
      echo '
            <div id="col_princ" class="col-princ">';
   break;
   case '5':
      echo '
            <div id="col_RB" class="col-d">';
      rightblocks($moreclass);
      echo '
            </div>
            <div id="col_princ" class="col-princ">';
   break;
   default:
      echo '
            <div id="col_LB" class="col-g">';
      leftblocks($moreclass);
      echo '
            </div>
            <div id="col_princ" class="col-princ">';
   break;
}
?>
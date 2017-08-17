<?php
/************************************************************************/
/* Theme for NPDS / Net Portal Dynamic System                           */
/*======================================================================*/
/* This theme use the NPDS theme-dynamic engine (Meta-Lang)             */
/*                                                                      */
/* Theme : MyBlogNPDS 2017 by Bmag                                      */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
/*
   Ce thème :
   NE permet PAS la gestion de la variable $pdst dans page.php.
   La variable $pdst :
   "-1" -> col_princ (en cas de désactivation de TOUS les blocs!)
   "2"  -> col_princ + col_LB (bloc "gauche" et "droit")
   Nomination des div :
   col_princ contient le contenu principal
   col_LB contient les blocs historiquement dit de gauche et de droite
*/
global $NPDS_Prefix, $pdst;
$pdst='2'; 

$blg_actif = sql_query("SELECT * FROM ".$NPDS_Prefix."lblocks WHERE actif ='1'");
$nb_blg_actif = sql_num_rows($blg_actif);
$bld_actif = sql_query("SELECT * FROM ".$NPDS_Prefix."rblocks WHERE actif ='1'");
$nb_bld_actif = sql_num_rows($bld_actif);

if (($nb_blg_actif == 0) and ($nb_bld_actif == 0)){
   switch ($pdst) {
      case '2': $pdst='-1'; break;
   }
}

function colsyst($coltarget) {
   $coltoggle ='
      <div class="col d-lg-none mr-2 my-2">
         <hr />
         <a class=" small float-right" href="#" data-toggle="collapse" data-target="'.$coltarget.'"><span class="plusdecontenu trn">Plus de contenu</span></a>
      </div>
   ';
   echo $coltoggle;
}

// ContainerGlobal permet de transmettre à Theme-Dynamic un élément de personnalisation avant
// le chargement de header.html / Si vide alors la class body est chargée par défaut par TD
$ContainerGlobal='
<div id="container">';

// Ne supprimez pas cette ligne / Don't remove this line
   require_once("themes/themes-dynamic/header.php");
   global $powerpack;
   if (!isset($powerpack)) {include ("powerpack.php");}
// Ne supprimez pas cette ligne / Don't remove this line

   echo '
   <div id="corps" class="container n-hyphenate">
      <div class="row">';
switch ($pdst) {
   case '-1':
      echo '
         <div id="col_princ" class="col-12">';
   break;
   default:
      echo '
         <div id="col_princ" class="col-12 col-lg-9">';
   break;
}
?>
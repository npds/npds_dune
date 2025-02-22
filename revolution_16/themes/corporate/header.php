<?php
/************************************************************************/
/* Theme for NPDS / Net Portal Dynamic System                           */
/*======================================================================*/
/* This theme use the NPDS theme-dynamic engine (Meta-Lang)             */
/*                                                                      */
/* Theme : corporate 2015 by Bmag                                       */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

global $NPDS_Prefix, $pdst;
$moreclass = 'col-12';
$blg_actif = sql_query("SELECT * FROM ".$NPDS_Prefix."lblocks WHERE actif ='1'");
$nb_blg_actif = sql_num_rows($blg_actif);
sql_free_result($blg_actif);
$bld_actif = sql_query("SELECT * FROM ".$NPDS_Prefix."rblocks WHERE actif ='1'");
$nb_bld_actif = sql_num_rows($bld_actif);
sql_free_result($bld_actif);

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
$coltarget='';

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
function colsyst($coltarget) {
   $coltoggle ='
      <div class="col d-lg-none me-2 my-2">
         <hr />
         <a class=" small float-end" href="#" data-bs-toggle="collapse" data-bs-target="'.$coltarget.'"><span class="plusdecontenu trn">Plus de contenu</span></a>
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

   
/************************************************************************/
/*     Le corps de page de votre Site - En dessous du Header            */
/*     On Ouvre les Différent Blocs en Fonction de la Variable $pdst    */
/*                         Le corps englobe :                           */
/*                 col_LB + col_princ + col_RB                          */
/*           Si Aucune variable pdst dans pages.php                     */
/*   ==> Alors affichage par defaut : col_LB + col_princ soit $pdst=0   */
/* =====================================================================*/
   echo '
   <div id="corps" class="container n-hyphenate">
      <div class="row">';
switch ($pdst) {
case '-1':
   echo '
         <div id="col_princ" class="col-12">';
break;
case '1':
   colsyst('#col_LB');
   echo '
         <div id="col_LB" class="collapse show col-lg-3">';
     leftblocks($moreclass);
   echo '
         </div>
         <div id="col_princ" class="col-lg-6">';
break;
case '2': case '6':
   echo '
      <div id="col_princ" class="col-lg-9">';
break;
case '3':
   colsyst('#col_LB');
   echo '
      <div id="col_LB" class="collapse show col-lg-3">';
   leftblocks($moreclass);
   echo '
      </div>';
   colsyst('#col_RB');
   echo' 
      <div id="col_RB" class="collapse show col-lg-3">';
   rightblocks();
   echo '
      </div>
      <div id="col_princ" class="col-lg-6">';
break;
case '4':
   echo '
      <div id="col_princ" class="col-lg-6">';
break;
case '5':
   colsyst('#col_RB');
   echo '
      <div id="col_RB" class="collapse show col-lg-3">';
   rightblocks($moreclass);
   echo '
      </div>
      <div id="col_princ" class="col-lg-9">';
break;
default:
   colsyst('#col_LB');
   echo '
         <div id="col_LB" class="collapse show col-lg-3">';
   leftblocks($moreclass);
   echo '
         </div>
         <div id="col_princ" class="col-lg-9">';
break;
}
?>
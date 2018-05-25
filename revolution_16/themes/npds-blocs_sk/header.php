<?php
/************************************************************************/
/* Theme for NPDS / Net Portal Dynamic System                           */
/*======================================================================*/
/* This theme use the NPDS theme-dynamic engine (Meta-Lang)             */
/*                                                                      */
/* Theme : npds-blocs_sk 2018 by jpb                                    */
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

   $listblocs_g='';
    while (list(,$title_g)=sql_fetch_row($blg_actif)) {
       $listblocs_g.= $title_g.'<br />';
    }
   $listblocs_d='';
    while (list(,$title_d)=sql_fetch_row($bld_actif)) {
       $listblocs_d.= $title_d.'<br />';
    }

/*
Ce thème dispose donc les blocs en ligne en bas du corps, la variable $pdst permet 5 affichages de contenu différents

 "-1" -> col_princ
 "0"  -> col_princ + col_LB 
 "1"  -> col_princ  + col_LB + col_RB
 "2"  -> col_princ + col_RB
 "3"  -> col_princ + col_RB + col_LB
 
 La gestion de ce paramètre s'effectue dans le fichier "pages.php" du dossier "themes

 Nomination des div :
 col_princ contient le contenu principal
 col_LB contient les blocs historiquement dit de gauche
 col_RB contient les blocs historiquement dit de droite
*/

if ($nb_blg_actif == 0) {
   switch ($pdst) {
   case '0': $pdst='-1'; break;
   case '1':
   case '3': $pdst='2'; break;
   }
}
if ($nb_bld_actif == 0) {
   switch ($pdst) {
   case '1': 
   case '3': $pdst='0'; break;
   case '2': $pdst='-1'; break;
   }
}

$listblocs='';
   switch ($pdst) {
      case '-1': $listblocs=$listblocs; break;
      case '0': $listblocs=$listblocs_g; break;
      case '1': $listblocs=$listblocs_g.$listblocs_d; break;
      case '2': $listblocs=$listblocs_d; break;
      case '3': $listblocs=$listblocs_d.$listblocs_g; break;
      default: $listblocs=$listblocs_g.$listblocs_d; break;
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
/*     Le corps englobe :                                               */
/*               col_princ (toujours)                                   */
/*               col_LB (en fonction $pdst)                             */
/*               col_RB (en fonction $pdst)                             */
/*           Si Aucune variable pdst dans pages.php                     */
/*   ==> Alors affichage par defaut : col_princ col_LB + col_RB         */
/*               col_princ                                              */
/*               col_LB                                                 */
/*               col_RB                                                 */
/* =====================================================================*/
echo '
   <div id="corps" class="container-fluid n-hyphenate">
      <div class="row justify-content-center">';
switch ($pdst) {
   default:
      echo '
         <div id="col_princ" class="col-lg-11">
            <div id="btn_haut" class="text-right mb-2" style="padding-top:4rem;">
               <a class="btn btn-outline-primary btn-sm rounded-circle" href="#allblocs" data-toggle="popover" data-trigger="hover" data-content="'.aff_langue($listblocs).'" data-html="true" title="Plus de contenu ..."  data-offset="180,2" ><i class="fa fa-angle-down fa-lg"></i></a>
            </div>';
   break;
}
?>
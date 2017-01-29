<?php
/************************************************************************/
/* Theme for NPDS / Net Portal Dynamic System     npds-roc2016                   */
/*======================================================================*/
/* This theme use the NPDS theme-dynamic engine (Meta-Lang)             */
/*                                                                      */
/* Theme : jpb Jireck Bmag     npds-roc2016            */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

global $pdst;
/* $pdst=2; */
if(!$user){

$blg_actif = sql_query("SELECT * FROM ".$NPDS_Prefix."lblocks WHERE actif ='1' AND member ='0'");
$nb_blg_actif = sql_num_rows($blg_actif);
$bld_actif = sql_query("SELECT * FROM ".$NPDS_Prefix."rblocks WHERE actif ='1' AND member ='0'");
$nb_bld_actif = sql_num_rows($bld_actif);

/*
La variable $pdst permet de gŽrer le nombre et la disposition des colonnes
 "-1" -> col_princ
 "0"  -> col_LB + col_princ
 "1"  -> col_LB + col_princ + col_RB
 "2"  -> col_princ + col_RB
 "3"  -> col_LB + col_RB + col_princ
 "4"  -> col_princ + col_LB + col_RB
 "5"  -> col_RB + col_princ
 "6"  -> col_princ + col_LB
 
 La gestion de ce paramŽtre s'effectue dans le fichier "pages.php" du dossier "themes

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
}

// ContainerGlobal permet de transmettre à Theme-Dynbamic un élément de personnalisation avant
// le chargement de header.html / Si vide alors la class body est chargée par défaut par Theme dynamique
$ContainerGlobal="\n<div id=\"container\">\n";

// Ne supprimez pas cette ligne / Don't remove this line
   require_once("themes/themes-dynamic/header.php");
   global $powerpack;
   if (!isset($powerpack)) {include ("powerpack.php");}
// Ne supprimez pas cette ligne / Don't remove this line

// Insertion Message "chat en cours" juste en dessous du header - avant la Partie Centrale
// if (if_chat()) echo "<b>-: Chat en Cours :-</b>";
   
/************************************************************************/
/*     Le corps de page de votre Site - En dessous du Header            */
/*     On Ouvre les Différent Blocs en Fonction de la Variable $pdst    */
/*                         Le corps englobe :                           */
/*                 col_LB + col_princ + col_RB                          */
/*           Si Aucune variable pdst dans pages.php                     */
/*   ==> Alors affichage par defaut : col_LB + col_princ soit $pdst=0   */
/* =====================================================================*/
     echo '<section id="corps" class="row1 w100 automobile  n-hyphenate" role="main">';
switch ($pdst) {
case "-1":
     echo '<section id="centralcol" class="col w100 autotablet">';   /* la partie centrale */
break;
case "0":
     echo '<aside id="leftcol" class="col w22 notablet nomobile  aside">';  /* la colonne de gauche qui aura les BLOCS de gauche */
        leftblocks();
     echo '</aside>';
     echo '<section id="centralcol" class="col w78 autotablet content">';
break;

case "1":
     echo '<aside id="leftcol" class="col w22 notablet nomobile  aside">';  /* la colonne de gauche qui aura les BLOCS de gauche */
        leftblocks();
     echo '</aside>';
     echo '<section id="centralcol" class="col  w56 autotablet">';
break;
case "2":
     echo '<section id="centralcol" class="col w78 autotablet">';
break;
case "3":
     echo '<aside id="leftcol" class="col w22 notablet nomobile  aside">';
        leftblocks();
     echo '</aside>';
     echo '<aside  class="col w22 notablet nomobile   aside">';    /* la colonne de droite qui aura les BLOCS de droites */
        rightblocks();
     echo '</aside>';
     echo '<section id="centralcol" class="col w56 autotablet content">';
break;
case "4":
     echo '<section id="centralcol" class="col w56 autotablet content">';
break;

case "5":
     echo '<aside  class="col w22 notablet nomobile   aside">';    /* la colonne de droite qui aura les BLOCS de droites */
        rightblocks();
     echo '</aside>';
     echo '<section id="centralcol" class="col w78 autotablet content">';
break;

 case "6":
      echo '<section id="centralcol" class="col w78  content">';
 break;
 


default:
     echo '<aside id="leftcol" class="col w22 notablet nomobile  aside">';
        leftblocks();
     echo '</aside>';
     echo '<section id="centralcol" class="col w78 autotablet content">';
break;
}
?>
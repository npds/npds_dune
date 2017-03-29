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

/* La variable $pdst permet de mettre automatiquement le site en 1, 2 ou 3 colonnes :

Nomination des div :
col_princ contient le contenu principal
col_LB contient les blocs historiquement dit de gauche
col_RB contient les blocs historiquement dit de droite

 "-1" -> col_princ
 "0"  -> col_LB + col_princ
 "1"  -> col_LB + col_princ + col_RB
 "2"  -> col_princ + col_RB
 "3"  -> col_LB + col_RB + col_princ
 "4"  -> col_princ + col_LB + col_RB
*/
//  Pour une gestion plus complète de ce paramètre, en fonction des différentes pages du site,
//  cette variable est maintenant renseignée dans le fichier "pages.php" du dossier "themes".
//  C'est donc sur cette page "pages.php" qu'il faut intervenir en fonction de vos envies.
global $pdst;
$pdst=0;
// ContainerGlobal permet de transmettre à Theme-Dynbamic un élément de personnalisation avant
// le chargement de header.html / Si vide alors la class body est chargée par défaut par Theme dynamique
$ContainerGlobal="\n<div id=\"container\">\n";


// Ne supprimez pas cette ligne / Don't remove this line
   require_once("themes/themes-dynamic/header.php");
   global $powerpack;
   if (!isset($powerpack)) {include ("powerpack.php");}
// Ne supprimez pas cette ligne / Don't remove this line

// Insertion Message "chat en cours" juste en dessous du header - avant la Partie Centrale
if (if_chat()) echo "<b>-: Chat en Cours :-</b>";
   
/************************************************************************/
/*     Le corps de page de votre Site - En dessous du Header            */
/*     On Ouvre les Différent Blocs en Fonction de la Variable $pdst    */
/*                         Le corps englobe :                           */
/*                 col_LB + col_princ + col_RB                          */
/*           Si Aucune variable pdst dans pages.php                     */
/*   ==> Alors affichage par defaut : col_LB + col_princ soit $pdst=0   */
/* =====================================================================*/
     echo '<div id="corps">';
     echo '<div id="col_LB">';
        leftblocks();
      rightblocks();
     echo '</div>';
     echo '<div id="col_princ" class="duo">';
?>
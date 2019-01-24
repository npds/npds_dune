<?php
/************************************************************************/
/* Theme for NPDS / Net Portal Dynamic System                           */
/* =====================================================================*/
/*                                                                      */
/* Theme : "NBOR" version 2016 par bmag                                 */
/* This theme use the NPDS theme-dynamic engine (DynaMot)               */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

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

*/

$PAGES['index.php']['blocs']="1";
 
 
 // CSS sur fichiers particuliers car n'utilisant pas header.php
$PAGES['chatrafraich.php']['css']="chat.css-";
$PAGES['chatinput.php']['css']="chat.css-";

// CSS sur fichiers particuliers car n'utilisant pas header.php
$PAGES['central.html']['css']="screen.css-";
$PAGES['central.html']['css']="grid.css-";

?>
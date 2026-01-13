<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/*                                                                      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2026 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/*                                                                      */
/* monmodule version 3.0                                                */
/* adm_alertes.php file 2008-2024 by moi                                */
/* dev team : xxx, yyy, zzz                                             */
/************************************************************************/
/*
NB : ne pas changer le nom et la position de ce fichier /modules/monmodule/admin/adm_alertes.php
$reqalertes est un tableau où chaque tableau correspond à un état du module qui nécessite une intervention de l'administrateur.
Ces requêtes généreront une notification/alerte dans l'administration et le bloc admin 
*/
#autodoc $reqalertes = array(array("requête","retour de l'alerte","tooltip de l'alerte"), array("","","")...)
#autodoc  NB : si l'élément [1] du tableau "retour de l'alerte" est à "1" il renverra au final le nombre de ligne trouvé par la requete "requete" de l'élément [0] de son tableau ... tout autre valeur sera interprété telle quelle

global $NPDS_Prefix;
$reqalertes = array(array("SELECT id FROM ".$NPDS_Prefix."tabledemonmodule WHERE unchamp=1","1","Contenu du tooltip de l'alerte"));
?>
<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/*                                                                      */
/* NPDS Copyright (c) 2002-2023 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/*                                                                      */
/* NMIG : NPDS Module Installer Generator                               */
/* ---------------------------------------------------------------------*/
/* Version 2.0 - 2015                                                   */
/* Générateur de fichier de configuration pour Module-Install 1.1       */
/* Développé par Boris - http://www.lordi-depanneur.com                 */
/* Module-Install est un installeur inspiré du programme d'installation */
/* d'origine du module Hot-Projet développé par Hotfirenet              */
/* ---------------------------------------------------------------------*/
/* Version 2.1 for NPDS 16 jpb 2017                                     */
/************************************************************************/

#autodoc $name_module: Nom du module
$name_module = "marquetapage";

#autodoc $path_adm_module: chemin depuis $ModInstall #required SI admin avec interface
$path_adm_module = '';

#autodoc $affich: pour l'affichage du nom du module dans l'admin
$affich = '';

#autodoc $icon: icon pour l'admin : c'est un nom de fichier(sans extension) !! #required SI admin avec interface
$icon='';

#autodoc $list_fich : Modifications de fichiers: Dans le premier tableau, tapez le nom du fichier
#autodoc et dans le deuxième, A LA MEME POSITION D'INDEX QUE LE PREMIER, tapez le code à insérer dans le fichier.
#autodoc Si le fichier doit être créé, n'oubliez pas les < ? php et ? > !!! (sans espace!).
#autodoc Synopsis: $list_fich = array(array("nom_fichier1","nom_fichier2"), array("contenu_fichier1","contenu_fichier2"));
$list_fich = array(array(''), array(''));

#autodoc $sql = array(""): Si votre module doit exécuter une ou plusieurs requêtes SQL, tapez vos requêtes ici.
#autodoc Attention! UNE requête par élément de tableau!
#autodoc Synopsis: $sql = array("requête_sql_1","requête_sql_2");
#autodoc Syntaxe création de table : 'CREATE TABLE "' ou 'CREATE TABLE IF NOT EXISTS "' <br /> tout les noms de table(s) utilisés doivent être concatené à gauche avec la variable $NPDS_Prefix

global $NPDS_Prefix;
$sql = array("CREATE TABLE ".$NPDS_Prefix."marquetapage (uid int(11) NOT NULL default '0',
 uri varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 topic varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 PRIMARY KEY (uid,uri(100)),
 KEY uid (uid)) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

#autodoc $blocs = array(array(""), array(""), array(""), array(""), array(""), array(""), array(""), array(""), array(""))
#autodoc                titre      contenu    membre     groupe     index      rétention  actif      aide       description
#autodoc Configuration des blocs
$blocs = array(array("marquetapage"), array("include#modules/marquetapage/marquetapage.php\r\nfunction#marquetapage"), array("1"), array(""), array("0"), array("0"), array("1"), array("Vous permet de g&#xE9;rer vos marques-pages"), array("Bloc affichant marquetapage"));

#autodoc $txtdeb : Vous pouvez mettre ici un texte de votre choix avec du html qui s'affichera au début de l'install
#autodoc Si rien n'est mis, le texte par défaut sera automatiquement affiché
$txtdeb = '';

#autodoc $txtfin : Vous pouvez mettre ici un texte de votre choix avec du html qui s'affichera à la fin de l'install
$txtfin = '';

#autodoc $end_link: Lien sur lequel sera redirigé l'utilisateur à la fin de l'install (si laissé vide, redirigé sur index.php)
#autodoc N'oubliez pas les '\' si vous utilisez des guillemets !!!
$end_link = '';
?>
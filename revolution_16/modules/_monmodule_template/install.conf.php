<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
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

global $ModInstall;

#autodoc $name_module: Nom du module
$name_module = 'monmodule';

#autodoc $path_adm_module: chemin depuis $ModInstall #required si admin avec interface
$path_adm_module = 'admin/monmodule_admin';

#autodoc $affich: pour l'affichage du nom du module dans l'admin
$affich = 'Mon module';

#autodoc $icon: icon pour l'admin : c'est un nom de fichier(sans extension) !! #required SI admin avec interface
$icon = 'monmodule';

#autodoc $list_fich : Modifications de fichiers: Dans le premier tableau, tapez le nom du fichier
#autodoc et dans le deuxième, A LA MEME POSITION D'INDEX QUE LE PREMIER, tapez le code à insérer dans le fichier.
#autodoc Si le fichier doit être créé, n'oubliez pas les < ? php et ? > !!! (sans espace!).
#autodoc Synopsis: $list_fich = array(array("nom_fichier1","nom_fichier2"), array("contenu_fchier1","contenu_fichier2"));
$list_fich = array(array('',''), array('',''));

#autodoc $sql = array(""): Si votre module doit exécuter une ou plusieurs requêtes SQL, tapez vos requêtes ici.
#autodoc Attention! UNE requête par élément de tableau!
#autodoc Synopsis: $sql = array("requête_sql_1","requête_sql_2");
$sql = array('','');

#autodoc $blocs = array(array(""), array(""), array(""), array(""), array(""), array(""), array(""), array(""), array(""))
#autodoc                titre      contenu    membre     groupe     index      rétention  actif      aide       description
#autodoc Configuration des blocs
$blocs = array(array("[french]Mon module[/french][english]My plugin[/english]"), array("include#modules/monmodule/monmodule_bloc.php"), array(""), array(""), array(""), array(""), array("1"), array(""), array("[french]Mon module[/french][english]My addon[/english]"));

#autodoc $txtdeb : Vous pouvez mettre ici un texte de votre choix avec du html qui s'affichera au début de l'install
#autodoc Si rien n'est mis, le texte par défaut sera automatiquement affiché
$txtdeb = '';

#autodoc $txtfin : Vous pouvez mettre ici un texte de votre choix avec du html qui s'affichera à la fin de l'install
$txtfin = "[french]Vous pouvez maintenant aller &#xE0; <a href= \"admin.php?op=Extend-Admin-SubModule&amp;ModPath=monmodule&amp;ModStart=admin/monmodule_admin\"> l'administration </a> du module pour le param&#xE9;trer.[/french][english]Now you can go to the <a href= \"admin.php?op=Extend-Admin-SubModule&amp;ModPath=monmodule&amp;ModStart=admin/monmodule_admin\">settings page</a> of this module.[/english]";

#autodoc $link: Lien sur lequel sera redirigé l'utilisateur à la fin de l'install (si laissé vide, redirigé sur index.php)
#autodoc N'oubliez pas les '\' si vous utilisez des guillemets !!!
$end_link = "admin.php?op=Extend-Admin-SubModule&amp;ModPath=monmodule&amp;ModStart=admin/monmodule_admin";
?>
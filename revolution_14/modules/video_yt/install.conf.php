<?php
/************************************************************************/
/*                                                                      */
/* NMIG : NPDS Module Installer Generator                               */
/* --------------------------------------                               */
/*                                                                      */
/* Version 1.1 - 15 Mars 2005                                           */
/* --------------------------                                           */
/*                                                                      */
/* Générateur de fichier de configuration pour Module-Install 1.1       */
/*                                                                      */
/* Développé par Boris - http://www.lordi-depanneur.com                 */
/*                                                                      */
/* Module-Install est un installeur inspiré du programme d'installation */
/* d'origine du module Hot-Projet développé par Hotfirenet              */
/*                                                                      */
/************************************************************************/
/*                                                                      */
/* NPDS : Net Portal Dynamic System                                     */
/* ================================                                     */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001 by Philippe Brunier        */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/*                                                                      */
/************************************************************************/

#autodoc $name_module: Nom du module
$name_module = 'video_yt'; // requis !!! nom du dossier, $ModePath unique et conforme ˆ un nom de fichier
$name_module_aff = 'Videos Youtube';  // requis !!! nom d'affichage
$name_module_adm = 'admin/video_yt_set'; // requis !!! chemin dossier/fichier (sans extension) du fichier admin du module
#autodoc $list_fich : Modifications de fichiers: Dans le premier tableau, tapez le nom du fichier
#autodoc et dans le deuxième, A LA MEME POSITION D'INDEX QUE LE PREMIER, tapez le code à insérer dans le fichier.
#autodoc Si le fichier doit être créé, n'oubliez pas les < ? php et ? > !!! (sans espace!).
#autodoc Synopsis: $list_fich = array(array("nom_fichier1","nom_fichier2"), array("contenu_fchier1","contenu_fichier2"));
$list_fich = array(array("admin/extend-modules.txt"), array("[module]\n   [nom]Video[/nom]\n   [ModPath]video_yt[/ModPath]\n   [ModStart]admin/video_yt_set[/ModStart]\n[/module]\n"));

#autodoc $sql = array(""): Si votre module doit exécuter une ou plusieurs autres requêtes SQL, tapez vos requêtes ici.
#autodoc Attention! UNE requête par élément de tableau!
#autodoc Synopsis: $sql = array("requête_du core_1","votre_requête_sql_2","votre_requête_sql_3");
$sql = array("INSERT INTO fonctions VALUES (NULL, '".$name_module."', 0, '', 1, 1, '', '', '".$name_module_aff."', '".$name_module."', 'href=\"admin.php?op=Extend-Admin-SubModule&ModPath=".$name_module."&ModStart=".$name_module_adm."\"', 6, 'Modules', 0);");

#autodoc $blocs = array(array(""), array(""), array(""), array(""), array(""), array(""), array(""), array(""), array(""))
#autodoc                titre      contenu    membre     groupe     index      rétention  actif      aide       description

#autodoc Configuration des blocs
$blocs = array(array("[french]Vid&#xE9;o al&#xE9;atoire[/french][english]Random video[/english][chinese]Video[/chinese]"), array("include#modules/video_yt/video_bloc.php"), array(""), array(""), array(""), array(""), array("1"), array(""), array("[french]Ce bloc permet l'affichage d'une vid&#xE9;o al&#xE9;atoire de votre vid&#xE9;oth&#xE8;que[/french][english]This block allow to watch a random video.[/english][chinese]This block allow to watch a random video.[/chinese]"));

#autodoc $txtdeb : Vous pouvez mettre ici un texte de votre choix avec du html qui s'affichera au début de l'install
#autodoc Si rien n'est mis, le texte par défaut sera automatiquement affiché
$txtdeb = "[french]<strong>Installation automatique du module video_yt et cr&#xE9;ation ou non d'un bloc d'affichage d'une vid&#xE9;o al&#xE9;atoire de votre vid&#xE9;oth&#xE8;que.</strong><br /><br />
Ce module n&#xE9;cessite l'ouverture d'un compte Youtube (pour que vous puissiez stocker vos vid&#xE9;os)  [l'obtention d'un ID d&#xE9;velopeur n'est plus n&#xE9;cessaire pour la version 2 sauf pour les op&#xE9;rations d'&#xE9;criture].<br /><br />
- Si vous avez cet &#xE9;l&#xE9;ment n&#xE9;cessaire passez directement &#xE0; <a href=\"admin.php?op=Module-Install&amp;ModInstall=video_yt&amp;nmig=e2\" class=\"noir\">l'&#xE9;tape suivante</a>.<br />
- Si vous n'avez pas cet &#xE9;l&#xE9;ment n&#xE9;cessaire suivez la proc&#xE9;dure A<br />
<br />
A. Proc&#xE9;dure Youtube :
<br />
1. Ouvrir un compte <a href= \"http://www.youtube.com/signup\" class=\"noir\" target=\"blank\">ici </a>
http://www.youtube.com/signup
<br /><br />
2. Suivez les indications du mail d'activation que vous recevez
<br /><br />
Vous avez maintenant l'&#xE9;l&#xE9;ment n&#xE9;cessaire au fonctionnement de cette passerelle :<br />
  - YouTube Username<br /><br />
3. Passez &#xE0; <a href=\"admin.php?op=Module-Install&amp;ModInstall=video_yt&amp;nmig=e2\" class=\"noir\">l'&#xE9;tape suivante</a>.<br /><br />[/french]
[english]<strong> Video_yt module automatic install. Create or not a block to watch a random video of your video library.</strong><br /><br />To use this module you need a Youtube account (to store your video), [with version 2 developper ID is no more necessary except for the writing operation].<br />
- If you already have this go directly to the next step.<br />
- If you have not follow the process A.<br />
A. Youtube Process:<br />
1. Open an acount<a href= \"http://www.youtube.com/signup\" class=\"noir\" target=\"blank\">here </a>
http://www.youtube.com/signup
<br /><br />
2. Follow the steps of the activation email.
<br /><br />
You have now the required element to use this bridge :<br />
  - YouTube Username<br /><br />
3. Go to <a href=\"admin.php?op=Module-Install&amp;ModInstall=video_yt&amp;nmig=e2\" class=\"noir\">the next step/a>.<br /><br />
[/english]
";
#autodoc $txtfin : Vous pouvez mettre ici un texte de votre choix avec du html qui s'affichera à la fin de l'install

//[french]Vous pouvez maintenant aller &#xE0; <a href= \"admin.php?op=Extend-Admin-SubModule&amp;ModPath=video_yt&amp;ModStart=admin/video_yt_set\"> l'administration </a> du module pour le param&#xE9;trer.[/french]
$txtfin = "";
#autodoc $link: Lien sur lequel sera redirigé l'utilisateur à la fin de l'install (si laissé vide, redirigé sur index.php)

#autodoc N'oubliez pas les '\' si vous utilisez des guillemets !!!
$end_link = "admin.php?op=Extend-Admin-SubModule&amp;ModPath=video_yt&amp;ModStart=admin/video_yt_set";
?>
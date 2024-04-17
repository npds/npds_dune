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
#autodoc $name_module: Nom du module #required (no space and exotism)
$name_module = 'bloc-notes';

#autodoc $path_adm_module: chemin depuis $ModInstall #required SI admin avec interface
$path_adm_module = '';

#autodoc $affich: pour l'affichage du nom du module dans l'admin
$affich = '';

#autodoc $icon: icon pour l'admin : c'est un nom de fichier(sans extension) !! #required SI admin avec interface
$icon='';

#autodoc $list_fich : Modifications de fichiers: Dans le premier tableau, tapez le nom du fichier
#autodoc et dans le deuxème, A LA MEME POSITION D'INDEX QUE LE PREMIER, tapez le code à insérer dans le fichier.
#autodoc Si le fichier doit être créé, n'oubliez pas les < ? php et ? > !!! (sans espace!).
#autodoc Synopsis: $list_fich = array(array("nom_fichier1","nom_fichier2"), array("contenu_fichier1","contenu_fichier2"));
$list_fich = array(array(''), array(''));

#autodoc $sql = array(""): Si votre module doit exécuter une ou plusieurs requêtes SQL, tapez vos requêtes ici.
#autodoc Attention! UNE requête par élément de tableau! 
#autodoc Synopsis: $sql = array("requête_sql_1","requête_sql_2");
#autodoc Syntaxe création de table : 'CREATE TABLE "' ou 'CREATE TABLE IF NOT EXISTS "' <br /> tout les noms de table(s) utilisés doivent être concatené à gauche avec la variable $NPDS_Prefix

global $NPDS_Prefix;
$sql = array("CREATE TABLE IF NOT EXISTS ".$NPDS_Prefix."blocnotes (
bnid text COLLATE utf8mb4_unicode_ci NOT NULL,
texte text COLLATE utf8mb4_unicode_ci,
PRIMARY KEY  (bnid(32))) 
ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
"INSERT INTO ".$NPDS_Prefix."metalang VALUES ('!blocnote!', 'function MM_blocnote(\$arg) {\r\n      global \$REQUEST_URI;\r\n      if (!stristr(\$REQUEST_URI,\"admin.php\")) {\r\n         return(@oneblock(\$arg,\"RB\"));\r\n      } else {\r\n         return(\"\");\r\n      }\r\n}',
'meta',
'-',
NULL,
'[french]Fabrique un blocnote contextuel en lieu et place du meta-mot / syntaxe : !blocnote!ID - ID = Id du bloc de droite dans le gestionnaire de bloc de NPDS[/french]',
'0') ON DUPLICATE KEY UPDATE type_meta='meta'");

#autodoc $blocs = array(array(""), array(""), array(""), array(""), array(""), array(""), array(""), array(""), array(""))
#autodoc                titre      contenu    membre     groupe     index      rétention  actif      aide       description
#autodoc Configuration des blocs
$blocs = array(array(''), array(''), array(''), array(''), array(''), array(''), array(''), array(''), array(''));

#autodoc $txtdeb : Vous pouvez mettre ici un texte de votre choix avec du html qui s'affichera au début de l'install
#autodoc Si rien n'est mis, le texte par défaut sera automatiquement affiché
$txtdeb = '';

#autodoc $txtfin : Vous pouvez mettre ici un texte de votre choix avec du html qui s'affichera à la fin de l'install
$txtfin = "Pensez &agrave; consulter le fichier modules/bloc-notes/install/install.txt pour apprendre &agrave; utiliser Bloc-Notes !";

#autodoc $end_link: Lien sur lequel sera redirigé l'utilisateur à la fin de l'install (si laissé vide, redirigé sur index.php)
#autodoc N'oubliez pas les '\' si vous utilisez des guillemets !!!
$end_link = '';
?>
<?php
/************************************************************************/
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
/*                                                                      */#   N      N  M      M  IIIII     GGG
/* Module-Install est un installeur inspiré du programme d'installation */#   NN     N  MM    MM    I     GG   GG
/* d'origine du module Hot-Projet développé par Hotfirenet              */#   N N    N  M M  M M    I    G       G
/*                                                                      */#   N  N   N  M  MM  M    I    G
/************************************************************************/#   N   N  N  M      M    I    G   GGGGGG
/*                                                                      */#   N    N N  M      M    I    G      GG
/* NPDS : Net Portal Dynamic System                                     */#   N     NN  M      M    I     GG   GG
/* ================================                                     */#   N      N  M      M  IIIII     GGG
/*                                                                      */
/* This version name NPDS Copyright (c) 2001 by Philippe Brunier        */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/*                                                                      */
/************************************************************************/
/************************************************************************/

#autodoc $name_module: Nom du module

$name_module = "bloc-notes";


#autodoc $list_fich : Modifications de fichiers: Dans le premier tableau, tapez le nom du fichier
#autodoc et dans le deuxième, A LA MEME POSITION D'INDEX QUE LE PREMIER, tapez le code à insérer dans le fichier.
#autodoc Si le fichier doit être créé, n'oubliez pas les < ? php et ? > !!! (sans espace!).
#autodoc Synopsis: $list_fich = array(array("nom_fichier1","nom_fichier2"), array("contenu_fchier1","contenu_fichier2"));

$list_fich = array(array(""), array(""));


#autodoc $sql = array(""): Si votre module doit exécuter une ou plusieurs requêtes SQL, tapez vos requêtes ici.
#autodoc Attention! UNE requête par élément de tableau!
#autodoc Synopsis: $sql = array("requête_sql_1","requête_sql_2");

global $NPDS_Prefix;
$sql = array("CREATE TABLE ".$NPDS_Prefix."blocnotes (bnid tinytext NOT NULL,texte text,PRIMARY KEY  (bnid(32))) type=MyISAM",
  "INSERT INTO ".$NPDS_Prefix."metalang VALUES ('!blocnote!', 'function MM_blocnote(\$arg) {\r\n      global \$REQUEST_URI;\r\n      if (!stristr(\$REQUEST_URI,\"admin.php\")) {\r\n         return(@oneblock(\$arg,\"RB\"));\r\n      } else {\r\n         return(\"\");\r\n      }\r\n}',
  'meta',
  '-',
  NULL,
  '[french]Fabrique un blocnote contextuel en lieu et place du meta-mot / syntaxe : !blocnote!ID - ID = Id du bloc de droite dans le gestionnaire de bloc de NPDS[/french]',
  '0')");


#autodoc $blocs = array(array(""), array(""), array(""), array(""), array(""), array(""), array(""), array(""), array(""))
#autodoc                titre      contenu    membre     groupe     index      rétention  actif      aide       description
#autodoc Configuration des blocs

$blocs = array(array(""), array(""), array(""), array(""), array(""), array(""), array(""), array(""), array(""));


#autodoc $txtdeb : Vous pouvez mettre ici un texte de votre choix avec du html qui s'affichera au début de l'install
#autodoc Si rien n'est mis, le texte par défaut sera automatiquement affiché

$txtdeb = "";


#autodoc $txtfin : Vous pouvez mettre ici un texte de votre choix avec du html qui s'affichera à la fin de l'install

$txtfin = "Pensez &agrave; consulter le fichier modules/bloc-notes/install/install.txt pour apprendre &agrave; utiliser Bloc-Notes !";


#autodoc $link: Lien sur lequel sera redirigé l'utilisateur à la fin de l'install (si laissé vide, redirigé sur index.php)
#autodoc N'oubliez pas les '\' si vous utilisez des guillemets !!!

$end_link = "";
?>
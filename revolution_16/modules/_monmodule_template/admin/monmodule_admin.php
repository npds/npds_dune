<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/*                                                                      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/*                                                                      */
/* monmodule version 3.0                                                */
/* monmodule_admin.php file 2008-2024 by moi                            */
/* dev team : xxx, yyy, zzz                                             */
/************************************************************************/

// cartouche de sécurité ==> requis !!
if (!strstr($_SERVER['PHP_SELF'],'admin.php')) Access_Error();
if (strstr($ModPath,'..') || strstr($ModStart,'..') || stristr($ModPath, 'script') || stristr($ModPath, 'cookie') || stristr($ModPath, 'iframe') || stristr($ModPath, 'applet') || stristr($ModPath, 'object') || stristr($ModPath, 'meta') || stristr($ModStart, 'script') || stristr($ModStart, 'cookie') || stristr($ModStart, 'iframe') || stristr($ModStart, 'applet') || stristr($ModStart, 'object') || stristr($ModStart, 'meta'))
   die();

//==> contrôle droit : requis !!
$f_meta_nom ='monmodule';
admindroits($aid,$f_meta_nom);
//<== contrôle droit

// inclusion fichier langue suivant language demandé par $language
if (file_exists('modules/'.$ModPath.'/lang/monmodule-'.$language.'.php'))
   include_once('modules/'.$ModPath.'/lang/monmodule-'.$language.'.php');
else
   include_once('modules/'.$ModPath.'/lang/monmodule-french.php');

$f_titre= monmodule_translate("Configuration de monmodule");
global $hlpfile, $language, $f_meta_nom, $f_titre, $adminimg;
GraphicAdmin($hlpfile);
adminhead ($f_meta_nom, $f_titre, $adminimg);
// ==> le code de monmodule
...
// <== le code de monmodule
adminfoot('','','','');
?>
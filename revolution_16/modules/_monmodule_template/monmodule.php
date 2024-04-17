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
/* monmodule.php file 2008-2024 by moi                                  */
/* dev team : xxx, yyy, zzz                                             */
/************************************************************************/

/*
Commentaires sur monmodule
*/
// cartouche de sécurité ==> requis !!
if (!stristr($_SERVER['PHP_SELF'],"modules.php")) die();
if (strstr($ModPath,'..') || strstr($ModStart,'..') || stristr($ModPath, 'script') || stristr($ModPath, 'cookie') || stristr($ModPath, 'iframe') || stristr($ModPath, 'applet') || stristr($ModPath, 'object') || stristr($ModPath, 'meta') || stristr($ModStart, 'script') || stristr($ModStart, 'cookie') || stristr($ModStart, 'iframe') || stristr($ModStart, 'applet') || stristr($ModStart, 'object') || stristr($ModStart, 'meta'))
   die();

// variables ...
global $pdst, $language;

// inclusion pages.php si besoin
if (file_exists('modules/'.$ModPath.'/admin/pages.php'))
   include ('modules/'.$ModPath.'/admin/pages.php');

// inclusion fichier de conf de monmodule si existe
include ('modules/'.$ModPath.'/monmodule_conf.php');

// inclusion fichier langue suivant language demandé par $language
if (file_exists('modules/'.$ModPath.'/lang/monmodule-'.$language.'.php'))
   include_once('modules/'.$ModPath.'/lang/monmodule-'.$language.'.php');
else
   include_once('modules/'.$ModPath.'/lang/monmodule-french.php');

// ==> le code de monmodule
...
// <== le code de monmodule
?>
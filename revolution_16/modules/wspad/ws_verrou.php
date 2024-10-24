<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Collab WS-Pad 1.5 by Developpeur and Jpb                             */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
include_once ('../../grab_globals.php');
// For More security
if (!stristr($_SERVER['HTTP_REFERER'],"modules.php?ModPath=wspad&ModStart=wspad")) die();
settype($verrou_groupe, 'integer');
$verrou_page=stripslashes(htmlspecialchars(urldecode($verrou_page),ENT_QUOTES,'UTF-8'));
$verrou_user=stripslashes(htmlspecialchars(urldecode($verrou_user),ENT_QUOTES,'UTF-8'));
// For More security

// For IE cache control
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-chek=0", false);
header("Pragma: no-cache");
// For IE cache control

$fp=fopen("locks/$verrou_page-vgp-$verrou_groupe.txt",'w');
fwrite($fp,$verrou_user);
fclose($fp);
?>
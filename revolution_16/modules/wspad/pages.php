<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Collab WS-Pad 1.5 by Developpeur and Jpb                             */
/*                                                                      */
/* NPDS Copyright (c) 2002-2022 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
global $nuke_url;
$PAGES['modules.php?ModPath='.$ModPath.'&ModStart=wspad*']['title']="[french]WS-Pad[/french][english]WS-PAd[/english][spanish]WS-Pad[/spanish][german]WS-Pad[/german][chinese]WS-Pad[/chinese]+|$title+";
$PAGES['modules.php?ModPath='.$ModPath.'&ModStart=wspad*']['run']="yes";
$PAGES['modules.php?ModPath='.$ModPath.'&ModStart=wspad*']['blocs']="0";
$PAGES['modules.php?ModPath='.$ModPath.'&ModStart=wspad*']['TinyMce']=1;
$PAGES['modules.php?ModPath='.$ModPath.'&ModStart=wspad*']['TinyMce-theme']="full+setup";
$PAGES['modules.php?ModPath='.$ModPath.'&ModStart=wspad']['css']=[$nuke_url."/lib/bootstrap/dist/css/bootstrap-icons.css+"];
?>
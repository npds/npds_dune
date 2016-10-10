<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/*                                                                      */
/* module geoloc version 3                                              */
/* pages.php file 2008 2015 by Jean Pierre Barbary jpb                  */
/************************************************************************/

$PAGES['modules.php?ModPath='.$ModPath.'&ModStart=geoloc*']['js']=array("http://maps.google.com/maps/api/js?v=3.22&amp;&amp;language=fr&amp;libraries=weather,geometry","modules/geoloc/include/fontawesome-markers.min.js","");
$PAGES['modules.php?ModPath='.$ModPath.'&ModStart=geoloc*']['title']="[french]Localisation[/french][english]Geolocalisation[/english]+|$title+";
$PAGES['modules.php?ModPath='.$ModPath.'&ModStart=geoloc*']['run']="yes";
$PAGES['modules.php?ModPath='.$ModPath.'&ModStart=geoloc*']['blocs']="-1";
$PAGES['modules.php?ModPath='.$ModPath.'&ModStart=geoloc*']['css']=array("geoloc_style.css+","modules/geoloc/include/css/glyphicons.css+");

$PAGES['modules.php?ModPath='.$ModPath.'&ModStart=distance*']['js']=array("http://maps.google.com/maps/api/js?v=3.22&amp;&amp;language=fr&amp;libraries=weather,geometry","modules/geoloc/include/fontawesome-markers.min.js","");
$PAGES['modules.php?ModPath='.$ModPath.'&ModStart=distance*']['title']="[french]Localisation[/french][english]Geolocalisation[/english]+|$title+";
$PAGES['modules.php?ModPath='.$ModPath.'&ModStart=distance*']['run']="yes";
$PAGES['modules.php?ModPath='.$ModPath.'&ModStart=distance*']['blocs']="-1";
$PAGES['modules.php?ModPath='.$ModPath.'&ModStart=distance*']['css']=array("geoloc_style.css+","modules/geoloc/include/css/glyphicons.css+");




?>
<?php
/************************************************************************/
/* NPDS DUNE : Net Portal Dynamic System .                              */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2018 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
// old version
/*
   $time_end = getmicrotime(true);
   $time = round($time_end - $time_start, 3);
   // or better
   $time = round($time_end - TIMESTART,3);
*/
// for php => 5
   $time_end = microtime(true);
   $time = round($time_end - TIMESTART,3);

// this for php => 5.4 more simple ($time_start ou TIMESTART remplacé par $_SERVER["REQUEST_TIME_FLOAT"] toujours dispo !
//   $time = round(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"],5);

   echo '
   <span class="small" id="build_time">'.aff_langue('[french]Temps :[/french][english]Time:[/english][german]Zeit in Sekunden[/german][spanish]Tiempo en segundos :[/spanish][chinese]&#x5728;&#x51E0;&#x79D2;&#x949F;&#x7684;&#x65F6;&#x95F4; :[/chinese] '.$time.' [french]seconde(s)[/french][english]second(s)[/english]').'</span>
</footer>
</div>
<script type="text/javascript" src="lib/js/npds_adapt.js"></script>';
?>
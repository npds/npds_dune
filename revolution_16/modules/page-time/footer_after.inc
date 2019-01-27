<?php
/************************************************************************/
/* NPDS DUNE : Net Portal Dynamic System .                              */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2019 by Philippe Brunier   */
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
?>
<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2019 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
   $handle=opendir('themes');
   while (false!==($file = readdir($handle))) {
      if ( ($file[0]!=='_') and (!strstr($file,'.')) and (!strstr($file,'themes-dynamic')) and (!strstr($file,'documentations')) and (!strstr($file,'default')) )
         $themelist[] = $file;
   }
   natcasesort($themelist);
   $themelist=implode(' ',$themelist);
   closedir($handle);
?>
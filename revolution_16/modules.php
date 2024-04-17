<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
include ("grab_globals.php");

function Access_Error () {
  include("admin/die.php");
}

function filtre_module($strtmp) {
   if (strstr($strtmp,'..') || stristr($strtmp,'script') || stristr($strtmp,'cookie') || stristr($strtmp,'iframe') || stristr($strtmp,'applet') || stristr($strtmp,'object'))
      Access_Error();
   else
      return $strtmp!='' ? true : false ;
}

if (filtre_module($ModPath) and filtre_module($ModStart)) {
   if (!function_exists("Mysql_Connexion"))
      include ("mainfile.php");
   if (file_exists("modules/$ModPath/$ModStart.php")) {
      include("modules/$ModPath/$ModStart.php");
      die();
   } else
      Access_Error();
} 
else
   Access_Error();
?>
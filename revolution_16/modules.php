<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2017 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
include ("grab_globals.php");

function Access_Error () {
  include("admin/die.php");
}
function filtre_module($strtmp) {
   if (strstr($strtmp,"..") || stristr($strtmp,"script") || stristr($strtmp,"cookie") || stristr($strtmp,"iframe") || stristr($strtmp,"applet") || stristr($strtmp,"object")) {
      Access_Error();
   } else {
      if ($strtmp!="") {
         return (true);
      } else {
         return (false);
      }
   }
}
if (filtre_module($ModPath) and filtre_module($ModStart)) {
   if (!function_exists("Mysql_Connexion")) {include ("mainfile.php");}
   if (file_exists("modules/$ModPath/$ModStart.php")) {
      include("modules/$ModPath/$ModStart.php");
      die();
   } else {
      Access_Error();
   }
} elseif (filtre_module($name) and filtre_module($file)) {
   // phpnuke compatibility
   if (!function_exists("Mysql_Connexion")) {include ("mainfile.php");}
   if (file_exists("modules/$name/$file.php")) {
      include("modules/$name/$file.php");
      die();
   } else {
      Access_Error();
   }
} else {
   Access_Error();
}
?>
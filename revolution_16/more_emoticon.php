<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* snipe 2004                                                           */
/*                                                                      */
/* NPDS Copyright (c) 2002-2017 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion")) {
   include ("mainfile.php");
}
include("functions.php");

   if (isset($user)) {
      if ($cookie[9]=="") $cookie[9]=$Default_Theme;
      if (isset($theme)) $cookie[9]=$theme;
      $tmp_theme=$cookie[9];
      if (!$file=@opendir("themes/$cookie[9]")) {
         $tmp_theme=$Default_Theme;
      }
   } else {
      $tmp_theme=$Default_Theme;
   }
   include('meta/meta.php');
   global $site_font;
   echo import_css($tmp_theme, $language, $site_font, "","");

   include('lib/formhelp.java.php');
   echo '
      </head>
      <body style="padding: 10px;">
      '.putitems_more().'
      </body>
   </html>';
?>
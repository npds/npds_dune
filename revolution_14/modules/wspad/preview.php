<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Collab WS-Pad 1.44 by Developpeur and Jpb                            */
/*                                                                      */
/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
// For More security
if (!stristr($_SERVER['PHP_SELF'],"modules.php")) { die(); }
if (strstr($ModPath,"..") || strstr($ModStart,"..") || stristr($ModPath, "script") || stristr($ModPath, "cookie") || stristr($ModPath, "iframe") || stristr($ModPath, "applet") || stristr($ModPath, "object") || stristr($ModPath, "meta") || stristr($ModStart, "script") || stristr($ModStart, "cookie") || stristr($ModStart, "iframe") || stristr($ModStart, "applet") || stristr($ModStart, "object") || stristr($ModStart, "meta")) {
   die();
}

global $language, $NPDS_Prefix;
include_once("modules/$ModPath/lang/$language.php");
// For More security

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
   $Titlesitename="NPDS wspad";
   include("meta/meta.php");
   echo "<link rel=\"shortcut icon\" href=\"images/favicon.ico\" type=\"image/x-icon\" />\n";
   global $site_font;
   echo import_css($tmp_theme, $language, $site_font, '','');

   echo '
   </head>
   <body style="padding: 10px; background:#ffffff;">';
      $wspad=rawurldecode(decrypt($pad));
      $wspad=explode("#wspad#",$wspad);
      $row=sql_fetch_assoc(sql_query("SELECT content, modtime, editedby, ranq  FROM ".$NPDS_Prefix."wspad WHERE page='".$wspad[0]."' and member='".$wspad[1]."' and ranq='".$wspad[2]."'"));
      echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n";
      echo $wspad[0]."&nbsp;&nbsp;[ ".wspad_trans("rÈvision")." : ".$row['ranq']." - ".$row['editedby']." / ".date(translate("dateinternal"),$row['modtime']+($gmt*3600))." ]";
      echo "</td></tr></table>\n";
      echo aff_langue($row['content']);
   echo '
   </body>
</html>';
?>
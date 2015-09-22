<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2010 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],"modules.php")) { die(); }

   global $user, $Default_Theme, $language, $site_font;
   $userX = base64_decode($user);
   $userdata = explode(":", $userX);
   if (isset($userdata[9])) {
      if (!$file=@opendir("themes/$userdata[9]")) {
         $tmp_theme=$Default_Theme;
      } else {
         $tmp_theme=$userdata[9];
      }
   } else {
      $tmp_theme=$Default_Theme;
   }
   include("themes/$tmp_theme/theme.php");
   $Titlesitename="META-LANG";
   include("meta/meta.php");
   echo import_css($tmp_theme, $language, $site_font, "","");

   global $NPDS_Prefix;
   $Q = sql_query("SELECT def, content, type_meta, type_uri, uri, description FROM ".$NPDS_Prefix."metalang order by 'type_meta','def' ASC");
   echo "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\" class=\"ligna\"><tr><td>\n";
   echo "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"6\" class=\"lignb\"><tr><td>\n";
   echo "<b>META</b><hr noshade=\"noshade\" class=\"ongl\" /></td><td><b>Type<hr noshade=\"noshade\" class=\"ongl\" /></b></td><td><b>Description<hr noshade=\"noshade\" class=\"ongl\" /></b></td></tr>";
   $cur_type=""; $ibid=0;
   while (list($def, $content, $type_meta, $type_uri, $uri, $description)= sql_fetch_row($Q)) {
      if ($cur_type=="")
         $cur_type=$type_meta;
      if ($type_meta!=$cur_type) {
         echo "</tr><tr><td colspan=\"3\"><hr noshade=\"noshade\" class=\"ongl\" /></td></tr>";
         $cur_type=$type_meta;
      }
      $rowcolor=tablos();
      echo "<tr $rowcolor><td valign=\"top\" align=\"left\"><b>$def</b></td>";
      echo "<td valign=\"top\" align=\"left\">$type_meta</td>";
      if ($type_meta=="smil") {
         eval($content);
         echo "<td valign=\"top\" align=\"left\">".$cmd."</td></tr>";
      } else if ($type_meta=="mot")
         echo "<td valign=\"top\" align=\"left\">$content</td></tr>";
      else
         echo "<td valign=\"top\" align=\"left\">".aff_langue($description)."</td></tr>";
      $ibid++;
   }
   echo "<tr><td colspan=\"3\" class=\"header\">Meta-lang pour <a href=\"http://www.npds.org\" class=\"noire\">NPDS</a> ==> $ibid META(s)";
   echo "</td></tr></table></td></tr></table>\n";
?>
<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/*                                                                      */
/* NPDS Copyright (c) 2001-2008 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion")) {
   include ("mainfile.php");
}

function L_encrypt($txt) {
   global $userdata;

   $key=substr($userdata[2],8,8);
   return (encryptK($txt, $key));
}

   global $user, $Default_Theme;
   if (!$user) {
        Header("Location: user.php");
   } else {
      $userX = base64_decode($user);
      $userdata = explode(":", $userX);
      if ($userdata[9]!="") {
         if (!$file=@opendir("themes/$userdata[9]")) {
            $tmp_theme=$Default_Theme;
         } else {
            $tmp_theme=$userdata[9];
         }
      } else {
         $tmp_theme=$Default_Theme;
      }
      include("themes/$tmp_theme/theme.php");
      $Titlesitename=translate("Bookmark");
      include("meta/meta.php");
      echo import_css($tmp_theme, $language, $site_font, "","");
      include("lib/formhelp.java.php");

      $fic="users_private/".$userdata[1]."/mns/carnet.txt";
      echo "\n</head>\n<body topmargin=\"2\" bottommargin=\"2\" leftmargin=\"2\" rightmargin=\"2\" style=\"background-color: #FFFFFF;\">";
      if (file_exists($fic)) {
         $fp=fopen($fic,"r");
            if (filesize($fic)>0)
               $contents=fread($fp, filesize($fic));
         fclose($fp);
         if (substr($contents,0,5)!="CRYPT") {
            $fp=fopen($fic,"w");
               fwrite($fp, "CRYPT".L_encrypt($contents));
            fclose($fp);
         } else {
            $contents=decryptK(substr($contents,5),substr($userdata[2],8,8));
         }
         echo "<table width=\"100%\">";
         $contents=explode("\n",$contents);
         foreach($contents as $tab) {
            $tabi=explode(";",$tab);
            if ($tabi[0]!="") {
               $rowcolor=tablos();
               echo "<tr $rowcolor><td nowrap=\"nowrap\">&nbsp;<a href=\"javascript: DoAdd(1,'to_user','$tabi[0],')\";><b>$tabi[0]</b></a></td><td nowrap=\"nowrap\"><a href=\"mailto:$tabi[1]\" class=\"noir\"><b>$tabi[1]</a></td><td nowrap=\"nowrap\">$tabi[2]&nbsp;</td></tr>\n";
            }
         }
         echo "</table>";
      } else {
         echo "<table width=\"100%\"><tr><td>";
         echo "<span class=\"noir\">".translate("You can upload a file <b>carnet.txt</b> in your Mini-Web site.<br /><br />the data structure of any line :<br />&nbsp;&nbsp;<b>name_of_the_member;email;comments</b>")."</span>";
         echo "</td></tr></table>";
      }
      echo "</body></html>";
}
?>
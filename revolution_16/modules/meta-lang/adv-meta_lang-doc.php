<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2017 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],"modules.php")) { die(); }

   global $user, $Default_Theme, $language, $site_font;
   $userX = base64_decode($user);
   $userdata = explode(':', $userX);
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
//   echo import_css($tmp_theme, $language, $site_font, "","");
echo '<link rel="stylesheet" href="lib/bootstrap/dist/css/bootstrap.min.css">';// en dur lol en attendant
   global $NPDS_Prefix;
   $Q = sql_query("SELECT def, content, type_meta, type_uri, uri, description FROM ".$NPDS_Prefix."metalang ORDER BY 'type_meta','def' ASC");
   echo '
   <table class="table table-striped table-responsive table-hover table-sm" border="0" >
      <thead class="thead-default">
         <tr>
            <th>META</th>
            <th>Type</th>
            <th>Description</th>
         </tr>
      </thead>';
   $cur_type=''; $ibid=0;
   while (list($def, $content, $type_meta, $type_uri, $uri, $description)= sql_fetch_row($Q)) {
      if ($cur_type=="")
         $cur_type=$type_meta;
      if ($type_meta!=$cur_type) {
         echo '
         </tr>
         <tr>
            <td class="table-info" colspan="3"></td>
         </tr>
      <tbody>';
         $cur_type=$type_meta;
      }
         if ($tiny_mce)
            $def_modifier="<a href=\"#\" onclick=\"javascript:parent.tinymce.activeEditor.selection.setContent(' ".$def." ');\" >$def</a>";
         else 
            $def_modifier=$def;
      echo '
         <tr>
            <td valign="top" align="left"><strong>'.$def_modifier.'</strong></td>
            <td class="table-info" valign="top" align="left">'.$type_meta.'</td>';
      if ($type_meta=="smil") {
         eval($content);
         echo '
            <td valign="top" align="left">'.$cmd.'</td>
         </tr>';
      } else if ($type_meta=="mot")
         echo '
            <td valign="top" align="left">'.$content.'</td>
         </tr>';
      else
         echo '
            <td valign="top" align="left">'.aff_langue($description).'</td>
         </tr>';
      $ibid++;
   }
   echo '
         <tr><td colspan="3" >Meta-lang pour <a href="http://www.npds.org" >NPDS</a> ==> '.$ibid.' META(s)
            </td>
         </tr>
      </tbody>
   </table>';
?>
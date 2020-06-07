<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2019 by Philippe Brunier                     */
/* Great mods by snipe                                                  */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion"))
   include ("mainfile.php");

include('functions.php');
if ($SuperCache)
   $cache_obj = new cacheManager();
else
   $cache_obj = new SuperCacheEmpty();

global $NPDS_Prefix;
include('auth.php');

$rowQ1=Q_Select ("SELECT forum_id FROM ".$NPDS_Prefix."forumtopics WHERE topic_id='$topic'", 3600);
if (!$rowQ1)
   forumerror('0001');
$myrow = $rowQ1[0];
$forum=$myrow['forum_id'];

$rowQ1=Q_Select ("SELECT forum_name, forum_moderator, forum_type, forum_pass, forum_access, arbre FROM ".$NPDS_Prefix."forums WHERE forum_id = '$forum'", 3600);
if (!$rowQ1)
   forumerror('0001');
$myrow = $rowQ1[0];
$forum_name = $myrow['forum_name'];
$mod = $myrow['forum_moderator'];
$forum_type=$myrow['forum_type'];
$forum_access=$myrow['forum_access'];

if ( ($forum_type == 1) and ($Forum_passwd != $myrow['forum_pass']) )
   header("Location: forum.php");

if (($forum_type == 5) or ($forum_type == 7)) {
   $ok_affiche=false;
   $tab_groupe=valid_group($user);
   $ok_affiche=groupe_forum($myrow['forum_pass'], $tab_groupe);
   if (!$ok_affiche)
      header("location: forum.php");
}
if (($forum_type==9) and (!$user))
   header("location: forum.php");
   
// Moderator
if (isset($user)) {
   $userX = base64_decode($user);
   $userdata = explode(':', $userX);
}
$moderator=get_moderator($mod);
$moderator=explode(' ',$moderator);
$Mmod=false;
if (isset($user)) {
   for ($i = 0; $i < count($moderator); $i++) {
      if (($userdata[1]==$moderator[$i])) { $Mmod=true; break;}
   }
}

$sql = "SELECT topic_title, topic_status FROM ".$NPDS_Prefix."forumtopics WHERE topic_id = '$topic'";
if (!$result = sql_query($sql))
   forumerror(0001);
$myrow = sql_fetch_assoc($result);
$topic_subject = stripslashes($myrow['topic_title']);
$lock_state = $myrow['topic_status'];

   if (isset($user)) {
      if ($cookie[9]=='') $cookie[9]=$Default_Theme;
      if (isset($theme)) $cookie[9]=$theme;
      $tmp_theme=$cookie[9];
      if (!$file=@opendir("themes/$cookie[9]")) {
         $tmp_theme=$Default_Theme;
      }
   } else {
      $tmp_theme=$Default_Theme;
   }
   include("meta/meta.php");
   global $site_font;
    echo '
   <link rel="stylesheet" href="lib/bootstrap/dist/css/bootstrap.min.css" />';
//   echo import_css($tmp_theme, $language, $site_font, '','');
   echo '
   </head>
   <body>
      <div max-width="640" class="container p-1 n-hyphenate">
         <div>';
   $pos = strpos($site_logo, '/');
   if ($pos)
      echo '<img class="img-fluid d-block mx-auto" src="'.$site_logo.'" alt="website logo" />';
   else
      echo '<img class="img-fluid d-block mx-auto" src="images/'.$site_logo.'" alt="website logo" />';

   echo '<p class="mt-4">'.translate("Index du forum").'&nbsp;&raquo;&nbsp;&raquo;&nbsp;';
   echo stripslashes($forum_name);
   echo '</p>';

   echo "
   <table border=\"0\" >
      <tr>
         <td width=\"15%\"><hr />".translate("Auteur")."</td>
         <td><hr />$topic_subject</td>
      </tr>";
   if ($Mmod) {
      $post_aff=' ';
   } else {
      $post_aff=" AND post_aff='1' ";
   }
   $sql = "SELECT * FROM ".$NPDS_Prefix."posts WHERE topic_id='$topic' and post_id='$post_id'".$post_aff;
   if (!$result = sql_query($sql))
      forumerror(0001);
   $myrow = sql_fetch_assoc($result);

   if ($allow_upload_forum) {
      $visible = '';
      if (!$Mmod) {
         $visible = ' AND visible = 1';
      }
      $sql = "SELECT att_id FROM $upload_table WHERE apli='forum_npds' && topic_id = '$topic' $visible";
      $att = sql_num_rows(sql_query($sql));
      if ($att>0) {
         include ("modules/upload/include_forum/upload.func.forum.php");
      }
   }

   echo "<tr align=\"left\">";
   $posterdata = get_userdata_from_id($myrow['poster_id']);
   echo "<td width=\"15%\" valign=\"top\">";
   $posts = $posterdata['posts'];

   echo $posterdata['uname'];
   echo '<br />';
   echo member_qualif($posterdata['uname'], $posts, $posterdata['rank']);
   echo '<br /><br />';
   if ($smilies) {
      if ($posterdata['user_avatar'] != '') {
         if (stristr($posterdata['user_avatar'],"users_private")) {
            $imgtmp=$posterdata['user_avatar'];
         } else {
            if ($ibid=theme_image("forum/avatar/".$posterdata['user_avatar'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/avatar/".$posterdata['user_avatar'];}
         }
         if ($posterdata['mns']) {
            echo "<p align=\"center\"><a href=\"minisite.php?op=".$posterdata['uname']."\" target=\"_blank\"><img src=\"$imgtmp\" alt=\"\" border=\"0\" /></a></p>";
         } else {
            echo "<p align=\"center\"><img src=\"$imgtmp\" border=\"0\" alt=\"\" /></p>";
         }
      }
   }

   echo "</td><td valign=\"top\" width=\"100%\" height=\"100%\">";

   if ($myrow['image'] != '') {
      if ($ibid=theme_image("forum/subject/".$myrow['image'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/subject/".$myrow['image'];}
      echo "<img src=\"$imgtmp\" border=\"0\" alt=\"\" />";
   } else {
      echo "<img src=\"$imgtmpPI\" border=\"0\" alt=\"\" />";
   }
   echo "&nbsp;&nbsp;".translate("Posté : ").convertdate($myrow['post_time']);
   echo "<br /><br />\n";
   $message=stripslashes($myrow['post_text']);
   if ($allow_bbcode) {
      $message = smilie($message);
      $message = str_replace('[video_yt]','https://www.youtube.com/watch?v=',$message);
      $message = str_replace('[/video_yt]','',$message);
   }
   // <a href in the message
   if (stristr($message,"<a href")) {
      $message=preg_replace('#_blank(")#i','_blank\1 class=\1\1',$message);
   }
   $message=split_string_without_space($message, 80);
   if (($forum_type=='6') or ($forum_type=='5')) {
      highlight_string(stripslashes($myrow['post_text'])).'<br /><br />';
   } else {
      echo $message=str_replace('[addsig]', '<br /><br />' . nl2br($posterdata['user_sig']), $message);
   }
   if ($allow_upload_forum and ($att>0)) {
      $post_id=$myrow['post_id'];
      echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"1\" width=\"100%\">
      <tr><td>";
      echo display_upload("forum_npds",$post_id,$Mmod);
      echo "</td>
      </tr>
      </table>";
   }
   echo "
   </td></tr></table>
         </td></tr></table>
         </td></tr></table>
         </td></tr></table>";
   echo '
          </div>
          <hr />
          <p class="text-center">'.translate("Cet article provient de").' '.$sitename.'<br />
         <a href="'.$nuke_url.'">'.$nuke_url.'/viewtopic.php?topic='.$topic.'&forum='.$forum.'</a></p>
   ';
       echo '
      </div>
   </body>
</html>';
?>
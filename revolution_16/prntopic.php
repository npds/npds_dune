<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/* Great mods by snipe                                                  */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion"))
   include ("mainfile.php");

include('functions.php');
$cache_obj = $SuperCache ? new cacheManager() : new SuperCacheEmpty() ;

global $NPDS_Prefix;
include('auth.php');

$rowQ1=Q_Select ("SELECT forum_id FROM ".$NPDS_Prefix."forumtopics WHERE topic_id='$topic'", 3600);
if (!$rowQ1)
   forumerror('0001');
$myrow = $rowQ1[0];
$forum = $myrow['forum_id'];

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
   forumerror('0001');
$myrow = sql_fetch_assoc($result);
$topic_subject = stripslashes($myrow['topic_title']);
$lock_state = $myrow['topic_status'];

   if (isset($user)) {
      if ($cookie[9]=='') $cookie[9] = $Default_Theme;
      if (isset($theme)) $cookie[9]=$theme;
      $cookie[9] = explode('+', urldecode($cookie[9]));
      $tmp_theme = $cookie[9][0];
      if (!$file = @opendir("themes/$cookie[9][0]"))
         $tmp_theme = $Default_Theme;
   } else
      $tmp_theme=$Default_Theme;
   $post_aff = $Mmod ? ' ' : " AND post_aff='1' " ;

   $sql = "SELECT * FROM ".$NPDS_Prefix."posts WHERE topic_id='$topic' AND post_id='$post_id'".$post_aff;
   if (!$result = sql_query($sql))
      forumerror('0001');
   $myrow = sql_fetch_assoc($result);

   if ($allow_upload_forum) {
      $visible = !$Mmod ? ' AND visible = 1' : '' ;
      $sql = "SELECT att_id FROM $upload_table WHERE apli='forum_npds' && topic_id = '$topic' $visible";
      $att = sql_num_rows(sql_query($sql));
      if ($att>0)
         include ("modules/upload/include_forum/upload.func.forum.php");
   }

   if($myrow['poster_id'] != 0) {
      $posterdata = get_userdata_from_id($myrow['poster_id']);
      $posts = $posterdata['posts'];
   }

   include("meta/meta.php");
   echo '
   <link rel="stylesheet" href="lib/bootstrap/dist/css/bootstrap.min.css" />
   '.import_css($tmp_theme, $language, '', '','').'
   </head>
   <body>
      <div max-width="640" class="container p-3 n-hyphenate">
         <div>';
   $pos = strpos($site_logo, '/');
   if ($pos)
      echo '<img class="img-fluid d-block mx-auto" src="'.$site_logo.'" alt="website logo" loading="lazy" />';
   else
      echo '<img class="img-fluid d-block mx-auto" src="images/'.$site_logo.'" alt="website logo" loading="lazy" />';

   echo '
   <div class="row mt-4">
      <div class="col-md-2 text-sm-center">
         <strong>'.translate("Auteur").'</strong><br />';
   if ($smilies) {
      if($myrow['poster_id'] != 0) {
         if ($posterdata['user_avatar'] != '') {
            if (stristr($posterdata['user_avatar'],"users_private"))
               $imgtmp=$posterdata['user_avatar'];
            else {
               if ($ibid=theme_image("forum/avatar/".$posterdata['user_avatar'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/avatar/".$posterdata['user_avatar'];}
            }
            echo '<img class="n-ava-48 border my-2" src="'.$imgtmp.'" alt="avatar" /><br />';
         }
      }
      else
         echo '<img class="n-ava-48 border my-2" src="images/forum/avatar/blank.gif" alt="avatar" /><br />';
   }
   echo $myrow['poster_id'] != 0 ? $posterdata['uname'] : $anonymous ;
   echo '
      </div>
      <div class="col-md-10">
      <hr />
         <p class="">'.translate("Forum").'&nbsp;&raquo;&nbsp;&raquo;&nbsp;'.stripslashes($forum_name).'&nbsp;&raquo;&nbsp;&raquo;&nbsp;<strong>'.$topic_subject.'</strong></p>
         <hr />
         <p class="text-end">
         <small>'.translate("Posté : ").formatTimes($myrow['post_time'], IntlDateFormatter::MEDIUM, IntlDateFormatter::SHORT).'</small> ';

   if ($myrow['image'] != '') {
      if ($ibid=theme_image("forum/subject/".$myrow['image'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/subject/".$myrow['image'];}
      echo '<img class="n-smil" src="'.$imgtmp.'" alt="icone du post" />';
   } else
      echo '<img class="n-smil" src="images/forum/subject/00.png" alt="icone du post" />';
   echo '</p>';

   $message=stripslashes($myrow['post_text']);
   if ($allow_bbcode) {
      $message = smilie($message);
      $message = str_replace('[video_yt]','https://www.youtube.com/watch?v=',$message);
      $message = str_replace('[/video_yt]','',$message);
   }

//   if (stristr($message,'<a href'))
//      $message=preg_replace('#_blank(")#i','_blank\1 class=\1\1',$message);
//   $message=split_string_without_space($message, 80);// fonction génère erreur !!
   if (($forum_type=='6') or ($forum_type=='5'))
      highlight_string(stripslashes($myrow['post_text'])).'<br /><br />';
   else
      if($myrow['poster_id'] != 0)
         if(array_key_exists('user_sig', $posterdata))
            $message = str_replace('[addsig]','<div class="n-signature">'.nl2br($posterdata['user_sig']).'</div>', $message);

   echo $message;

   if ($allow_upload_forum and ($att>0)) {
      $post_id=$myrow['post_id'];
      echo display_upload("forum_npds",$post_id,$Mmod);
   }

   echo '
            <hr />
            <p class="text-center">'.translate("Cet article provient de").' '.$sitename.'<br />
            <a href="'.$nuke_url.'/viewtopic.php?topic='.$topic.'&amp;forum='.$forum.'&amp;post_id='.$post_id.'">'.$nuke_url.'/viewtopic.php?topic='.$topic.'&amp;forum='.$forum.'</a></p>
         </div>
      </div>
   </body>
</html>';
?>
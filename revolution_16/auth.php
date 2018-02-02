<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2018 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion"))
   header("location: index.php");

$rowQ1=Q_Select ("SELECT * FROM ".$NPDS_Prefix."config", 3600);
if ($rowQ1) {
   list(,$myrow) = each($rowQ1);
      $allow_html = $myrow['allow_html'];
      $allow_bbcode = $myrow['allow_bbcode'];
      $allow_sig = $myrow['allow_sig'];
      $posts_per_page = $myrow['posts_per_page'];
      $hot_threshold = $myrow['hot_threshold'];
      $topics_per_page = $myrow['topics_per_page'];
      $allow_forum_hide = $myrow['allow_forum_hide'];
      $allow_upload_forum = $myrow['allow_upload_forum'];
      $upload_table = $NPDS_Prefix.$myrow['upload_table'];
      $rank1 = $myrow['rank1'];
      $rank2 = $myrow['rank2'];
      $rank3 = $myrow['rank3'];
      $rank4 = $myrow['rank4'];
      $rank5 = $myrow['rank5'];
      $anti_flood = $myrow['anti_flood'];
      $solved = $myrow['solved'];
}
settype($forum,'integer');
if ($allow_upload_forum) {
   $rowQ1=Q_Select ("SELECT attachement FROM ".$NPDS_Prefix."forums WHERE forum_id='$forum'", 3600);
   if ($rowQ1) {
      list(,$myrow) = each($rowQ1);
      $allow_upload_forum=$myrow['attachement'];
   }
}

$rowQ1=Q_Select ("SELECT forum_pass FROM ".$NPDS_Prefix."forums WHERE forum_id='$forum' AND forum_type='1'", 3600);
if ($rowQ1) {
   if (isset($Forum_Priv[$forum])) {
      $Xpasswd=base64_decode($Forum_Priv[$forum]);
      list(,$myrow) = each($rowQ1);
      $forum_xpass=$myrow['forum_pass'];
      if (md5($forum_xpass)==$Xpasswd) {
         $Forum_passwd=$forum_xpass;
      } else {
         setcookie("Forum_Priv[$forum]",'',0);
      }
   } else {
      if (isset($Forum_passwd)) {
         list(,$myrow) = each($rowQ1);
         if ($myrow['forum_pass']==$Forum_passwd) {
           setcookie("Forum_Priv[$forum]",base64_encode(md5($Forum_passwd)),time()+900);
         }
      }
   }
}
?>

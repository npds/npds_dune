<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/* Based on Parts of phpBB                                              */
/*                                                                      */
/* NPDS Copyright (c) 2002-2012 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion")) {
   die();
}
include_once('functions.php');
include_once('auth.php');

settype($forum,"integer");
if ($forum>=0)
   die();

// gestion des params du 'forum' : type, accès, modérateur ...
$forum_name = "comments";
$forum_type=0;
$allow_to_post=false;
if ($anonpost)
   $forum_access=0;
else
   $forum_access=1;

if (($moderate==1) and $admin)
   $Mmod=true;
elseif ($moderate==2) {
   $userX=base64_decode($user);
   $userdata=explode(":", $userX);
   $result=sql_query("SELECT level FROM ".$NPDS_Prefix."users_status where uid='".$userdata[0]."'");
   list($level)=sql_fetch_row($result);
   if ($level>=2)
      $Mmod=true;
} else
   $Mmod=false;

function Caff_pub($topic, $file_name, $archive) {
   global $language;
   if ($ibid=theme_image("menu/$language/comment.gif")) {$imgtmpR=$ibid;} else {$imgtmpR="images/menu/$language/comment.gif";}
   $tmp="<a href=\"modules.php?ModPath=comments&amp;ModStart=reply&amp;topic=$topic&amp;file_name=$file_name&amp;archive=$archive\"><img src=\"$imgtmpR\" border=\"0\" alt=\"\" /></a>";
   return ($tmp);
}
    if ($forum_access==0) {
       $allow_to_post=true;
    } else {
       if ($user) {
           $allow_to_post=true;
       }
    }
    global $anonymous;
    settype($archive,"integer");
    if ($allow_to_post) {
       echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr>\n";
       echo "<td colspan=\"2\" align=\"right\">\n";
       echo Caff_pub($topic,$file_name, $archive)."\n";
       echo "</td></tr>\n";
       echo "</table>\n";
    }

    // Pagination
    settype($C_start,"integer");
    settype($comments_per_page,"integer");
    $result=sql_query ("SELECT count(*) AS total FROM ".$NPDS_Prefix."posts WHERE forum_id='$forum' and topic_id='$topic' and post_aff='1'");
    list($total)=sql_fetch_row($result);
    if ($total > $comments_per_page) {
       $times = 1; $current_page=0;
       $pages_rapide="";
       for ($x = 0; $x < $total; $x += $comments_per_page) {
           if (($x>=$C_start) and ($current_page==0)) {
              $current_page=$times;
           }
          if ($times!= 1)
             $pages_rapide.=" | ";
          if ($current_page!=$times)
             $pages_rapide.="<a href=\"".rawurldecode($url_ret)."&amp;C_start=$x\" class=\"noir\"><b>$times</b></a>";
          else
             $pages_rapide.="<b class=\"rouge\">$times</b>";
          $times++;
       }
    }

    if ($Mmod) {
       $post_aff=" ";
    } else {
       $post_aff=" and post_aff='1' ";
    }
    $sql = "SELECT * FROM ".$NPDS_Prefix."posts WHERE forum_id='$forum' and topic_id = '$topic'".$post_aff."ORDER BY post_id LIMIT $C_start, $comments_per_page";
    if (!$result = sql_query($sql))
       forumerror(0001);
    $mycount = sql_num_rows($result);
    $myrow = sql_fetch_assoc($result);
    $count = 0;

if ($mycount) {
    echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"1\" width=\"100%\">";

    if ($ibid=theme_image("forum/icons/posticon.gif")) {$imgtmpPI=$ibid;} else {$imgtmpPI="images/forum/icons/posticon.gif";}
    if ($ibid=theme_image("forum/icons/profile.gif")) {$imgtmpPR=$ibid;} else {$imgtmpPR="images/forum/icons/profile.gif";}
    if ($ibid=theme_image("forum/icons/www_icon.gif")) {$imgtmpWW=$ibid;} else {$imgtmpWW="images/forum/icons/www_icon.gif";}
    if ($ibid=theme_image("forum/icons/quote.gif")) {$imgtmpQU=$ibid;} else {$imgtmpQU="images/forum/icons/quote.gif";}
    if ($ibid=theme_image("forum/icons/ip_logged.gif")) {$imgtmpIP=$ibid;} else {$imgtmpIP="images/forum/icons/ip_logged.gif";}
    if ($ibid=theme_image("forum/icons/unlock_post.gif")) {$imgtmpUP=$ibid;} else {$imgtmpUP="images/forum/icons/unlock_post.gif";}
    if ($ibid=theme_image("forum/icons/lock_post.gif")) {$imgtmpLP=$ibid;} else {$imgtmpLP="images/forum/icons/lock_post.gif";}

    do {
      $rowcolor=tablos();
      echo "<tr $rowcolor align=\"left\">";
      $posterdata = get_userdata_from_id($myrow['poster_id']);
      echo "<td width=\"15%\" valign=\"top\">";
      echo "<a name=\"".$forum.$topic.$myrow['post_id']."\"></a>";
      if (($count+2)==$mycount) echo "<a name=\"last-post\"></a>";
      $posts = $posterdata['posts'];
      if ($posterdata['uname']!=$anonymous) {
         echo "<a href=\"powerpack.php?op=instant_message&amp;to_userid=".$posterdata['uname']."\" class=\"noir\">".$posterdata['uname']."</a>";
      } else {
         echo $posterdata['uname'];
      }
      echo "<br />";
      echo member_qualif($posterdata['uname'], $posts, $posterdata['rank']);
      echo "<br /><br />";
      global $smilies;
      if ($smilies) {
          if ($posterdata['user_avatar'] != '') {
             if (stristr($posterdata['user_avatar'],"users_private")) {
                $imgtmp=$posterdata['user_avatar'];
             } else {
                if ($ibid=theme_image("forum/avatar/".$posterdata['user_avatar'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/avatar/".$posterdata['user_avatar'];}
             }
             if ($posterdata['mns']) {
                echo "<div class=\"avatar_cadre\"><a href=\"minisite.php?op=".$posterdata['uname']."\" target=\"_blank\"><img src=\"".$imgtmp."\" alt=\"".$posterdata['uname']."\" border=\"0\" /></a></div>";
             } else {
                echo "<div class=\"avatar_cadre\"><img src=\"".$imgtmp."\" alt=\"".$posterdata['uname']."\" border=\"0\" /></div>";
             }
          }
      }

      echo "</td><td valign=\"top\" width=\"100%\" height=\"100%\">";
      echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"height: 100%; width: 100%;\">";
      echo "<tr><td valign=\"top\" width=\"100%\" height=\"100%\">";
      echo "<img src=\"$imgtmpPI\" border=\"0\" alt=\"\" />";

      $date_post=convertdateTOtimestamp($myrow['post_time']);
      echo "&nbsp;&nbsp;".translate("Posted: ").post_convertdate($date_post);
      echo "<br /><br />\n";
      $message=stripslashes($myrow['post_text']);
      if ($allow_bbcode) {
         $message = smilie($message);
         $message = aff_video_yt($message);
      }
      // <a href in the message
      if (stristr($message,"<a href")) {
         $message=preg_replace('#_blank(")#i','_blank\1 class=\1noir\1',$message);
      }
      $message=split_string_without_space($message, 80);
      echo $message=str_replace("[addsig]", "<br /><br />" . nl2br($posterdata['user_sig']), $message);
      echo "</td></tr><tr><td valign=\"bottom\">";
      echo "<hr noshade=\"noshade\" size=\"1\" class=\"ongl\" />";
      if ($posterdata['uid']!= 1 and $posterdata['uid']!="") {
         echo "&nbsp;&nbsp;<a href=\"user.php?op=userinfo&amp;uname=".$posterdata['uname']."\" class=\"noir\" target=\"_blank\"><img src=\"$imgtmpPR\" border=\"0\" alt=\"\" />".translate("Profile")."</a>";
      }

      if ($posterdata['url']!="") {
         if (strstr("http://", $posterdata['url']))
            $posterdata['url'] = "http://" . $posterdata['url'];
         echo "&nbsp;&nbsp;<a href=\"".$posterdata['url']."\" target=\"_blank\" class=\"noir\"><img src=\"$imgtmpWW\" border=\"0\" alt=\"\" />www</a>";
      }

      if ($allow_to_post and $posterdata['uid']!="") {
         if ($formulaire=="") {
            echo "&nbsp;&nbsp;<a href=\"modules.php?ModPath=comments&amp;ModStart=reply&amp;topic=$topic&amp;file_name=$file_name&amp;post=".$myrow['post_id']."&amp;citation=1&amp;archive=$archive\" class=\"noir\"><img src=\"$imgtmpQU\" border=\"0\" alt=\"\" />".translate("Quote")."</a>\n";
         } else
            echo "&nbsp;&nbsp;";
      }

      if ($Mmod) {
         if ($formulaire=="")
            echo "&nbsp;|&nbsp;";
         echo "<a href=\"modules.php?ModPath=comments&amp;ModStart=admin&amp;mode=viewip&amp;topic=$topic&amp;post=".$myrow['post_id']."&amp;file_name=$file_name&amp;archive=$archive\"><img src=\"$imgtmpIP\" border=\"0\" alt=\"\" /></a>&nbsp;<span style=\"display: inline; font-size: 10px;\">ip</span>\n";
         if (!$myrow['post_aff']) {
            echo "&nbsp;<a href=\"modules.php?ModPath=comments&amp;ModStart=admin&amp;mode=aff&amp;topic=$topic&amp;post=".$myrow['post_id']."&amp;ordre=1&amp;file_name=$file_name&amp;archive=$archive\" class=\"noir\"><img src=\"$imgtmpUP\" border=\"0\" alt=\"\" /></a>&nbsp;<span style=\"display: inline; font-size: 10px; color: red;\">".translate("Hidden post")."</span>\n";
         } else {
            echo "&nbsp;<a href=\"modules.php?ModPath=comments&amp;ModStart=admin&amp;mode=aff&amp;topic=$topic&amp;post=".$myrow['post_id']."&amp;ordre=0&amp;file_name=$file_name&amp;archive=$archive\" class=\"noir\"><img src=\"$imgtmpLP\" border=\"0\" alt=\"\" /></a>&nbsp;<span style=\"display: inline; font-size: 10px;\">".translate("Normal post")."</span>\n";
         }
      }
      echo "</td></tr></table>";
      echo "</td></tr>";
      $count++;
    } while($myrow = sql_fetch_assoc($result));
    unset ($tmp_imp);

    if ($total > $comments_per_page) {
       echo "<tr align=\"right\" colspan=\"2\"><td colspan=\"2\">".translate("Goto Page: ")." [ ";
       echo $pages_rapide." ] </td></tr>\n";
    }

    echo "</table><table border=\"0\" cellpadding=\"2\" cellspacing=\"1\" width=\"100%\"><tr>";
    echo "<td align=\"left\" valign=\"top\">";
    if ($ibid=theme_image("forum/icons/lock_topic.gif")) {$imgtmpLT=$ibid;} else {$imgtmpLT="images/forum/icons/lock_topic.gif";}
    if ($allow_to_post) {
       echo Caff_pub($topic,$file_name, $archive)."\n";
    }
    echo "</td></tr>";
    echo "<tr><td  class=\"header\" align=\"center\">".translate("The comments are owned by the poster. We aren't responsible for their content.")."</td></tr>";
    echo "</table>";
    if ($Mmod) {
       opentable();
       echo "<p align=\"center\"><b>".translate("Administration Tools")."</b><br />";
       echo "-------------------------<br />";
       if ($ibid=theme_image("forum/icons/del_topic.gif")) {$imgtmpDT=$ibid;} else {$imgtmpDT="images/forum/icons/del_topic.gif";}
       echo "<a href=\"modules.php?ModPath=comments&amp;ModStart=admin&amp;mode=del&amp;topic=$topic&amp;file_name=$file_name&amp;archive=$archive\"><img src=\"$imgtmpDT\" alt=\"".translate("Delete this Topic")."\" border=\"0\" /></a></p>";
       closetable();
    }
}
?>
<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2012 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion")) {
   include ("mainfile.php");
}

include('functions.php');
if ($SuperCache) {
   $cache_obj = new cacheManager();
} else {
   $cache_obj = new SuperCacheEmpty();
}
include('auth.php');

function cache_ctrl() {
   global $cache_verif;
   if ($cache_verif) {
      header("Expires: Sun, 01 Jul 1990 00:00:00 GMT");
      header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
      header("Cache-Control: no-cache, must revalidate");
      header("Pragma: no-cache");
   }
}

function show_imm($op) {
   global $smilies, $user, $allow_bbcode, $language, $Default_Theme, $theme, $site_font, $short_user, $Titlesitename;
   global $NPDS_Prefix;
   if (!$user) {
      Header("Location: user.php");
   } else {
      $userX = base64_decode($user);
      $userdata = explode(":", $userX);
      if ($userdata[9]!="") {
         if (!$file=@opendir("themes/$userdata[9]")) {
            $theme=$Default_Theme;
         } else {
            $theme=$userdata[9];
         }
      } else {
         $theme=$Default_Theme;
      }
      include("themes/$theme/theme.php");
      $userdata = get_userdata($userdata[1]);
      if ($op!="new_msg") {
         $sql = "SELECT * FROM ".$NPDS_Prefix."priv_msgs WHERE to_userid = '".$userdata['uid']."' AND read_msg='1' AND type_msg='0' AND dossier='...' ORDER BY msg_id DESC";
      } else {
         $sql = "SELECT * FROM ".$NPDS_Prefix."priv_msgs WHERE to_userid = '".$userdata['uid']."' AND read_msg='0' AND type_msg='0' ORDER BY msg_id ASC";
      }
      $result = sql_query($sql);
      $pasfin=false;
      while ($myrow = sql_fetch_assoc($result)) {
         if ($pasfin==false) {
            $pasfin=true;
            cache_ctrl();
            include("meta/meta.php");
            echo import_css($theme, $language, $site_font, "","");
            echo "</head>\n<body topmargin=\"0\" leftmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" style=\"background-color: #ffffff;\">";
         }
         opentable();
         echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n";
         $posterdata = get_userdata_from_id($myrow['from_userid']);
         echo translate("Private Message")." ".translate("From");
         if ($posterdata['uid']==1) {
            global $sitename;
            echo "&nbsp;".$sitename;
         }
         echo "</td></tr></table>\n";
         echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"1\" border=\"0\">";
         echo "<tr class=\"ligna\">";
         $myrow['subject']=strip_tags($myrow['subject']);
         echo "<td valign=\"top\" style=\"width: 15%;\"><b>";
         if ($posterdata['uid']<>1) echo $posterdata['uname']."</b><br />";
         $posts = $posterdata['posts'];
         if ($posterdata['uid']<>1) echo member_qualif($posterdata['uname'], $posts, $posterdata['rank']);
         echo "<br /><br />";
         if ($smilies) {
            if ($posterdata['user_avatar'] != '') {
               if (stristr($posterdata['user_avatar'],"users_private")) {
                  $imgtmp=$posterdata['user_avatar'];
               } else {
                  if ($ibid=theme_image("forum/avatar/".$posterdata['user_avatar'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/avatar/".$posterdata['user_avatar'];}
               }
               echo "<div class=\"avatar_cadre\"><img src=\"".$imgtmp."\" alt=\"".$posterdata['uname']."\" border=\"0\" /></div>";
            }
         }
         echo "</td><td valign=\"top\" style=\"width: 85%;\" height=\"100%\">";
         echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"height: 100%; width: 100%;\">";
         echo "<tr><td valign=\"top\" width=\"100%\" height=\"100%\">";
         if ($smilies) {
            if ($myrow['msg_image']!="") {
               if ($ibid=theme_image("forum/subject/".$myrow['msg_image'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/subject/".$myrow['msg_image'];}
               echo "<img src=\"$imgtmp\" border=\"0\" alt=\"\" />&nbsp;";
            }
         }
         echo translate("Sent")." : ".$myrow['msg_time']."&nbsp;&nbsp;&nbsp";
         echo "<hr noshade=\"noshade\" class=\"ongl\" /><b>".aff_langue($myrow['subject'])."</b><br /><br />\n";
         $message = stripslashes($myrow['msg_text']);
         if ($allow_bbcode) {
            $message = smilie($message);
            $message = aff_video_yt($message);
         }
         $message = str_replace("[addsig]", "<br /><br />" . nl2br($posterdata['user_sig']), aff_langue($message));
         echo $message."<br />";

         echo "</td></tr><tr><td valign=\"bottom\">";

         if ($posterdata['uid']<>1) {
            echo "<hr noshade=\"noshade\" class=\"ongl\">";
            if ($ibid=theme_image("forum/icons/profile.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/icons/profile.gif";}
            echo "&nbsp;&nbsp<a href=\"user.php?op=userinfo&amp;uname=".$posterdata['uname']."\" class=\"noir\" target=\"_blank\"><img src=\"$imgtmp\" border=\"0\" alt=\"\" />".translate("Profile")."</a>";

            if ($posterdata["femail"]!="") {
               if ($ibid=theme_image("forum/icons/email.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/icons/email.gif";}
               echo "&nbsp;&nbsp;<a href=\"mailto:".$posterdata['femail']."\" class=\"noir\" target=\"_blank\"><img src=\"$imgtmp\" border=\"0\" alt=\"\" />".translate("Email")."</a>";
            }

            if ($posterdata['url']!="") {
               if (strstr("http://", $posterdata['url']))
                  $posterdata['url'] = "http://" . $posterdata['url'];
               if ($ibid=theme_image("forum/icons/www_icon.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/icons/www_icon.gif";}
               echo "&nbsp;&nbsp;<a href=\"".$posterdata['url']."\" class=\"noir\" target=\"_blank\"><img src=\"$imgtmp\" border=\"0\" alt=\"\" />www</a>";
            }

            if (!$short_user) {
               if ($posterdata["user_icq"]!="") {
                  if ($ibid=theme_image("forum/icons/icq_on.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/icons/icq_on.gif";}
                  echo "&nbsp;&nbsp;<a href=\"http://wwp.mirabilis.com/".$posterdata['icq']."\" target=\"_blank\" class=\"noir\"><img src=\"$imgtmp\" border=\"0\" alt=\"\" />icq</a>";
               }

               if ($posterdata["user_aim"]!="") {
                  if ($ibid=theme_image("forum/icons/aim.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/icons/aim.gif";}
                  echo "&nbsp;&nbsp;<a href=\"aim:goim?screenname=".$posterdata['user_aim']."&amp;message=Hi+".$posterdata['user_aim'].".+Are+you+there?\" class=\"noir\" target=\"_blank\"><img src=\"$imgtmp\" border=\"0\" alt=\"\" />aim</a>";
               }

               if ($posterdata["user_yim"]!="") {
                  if ($ibid=theme_image("forum/icons/yim.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/icons/yim.gif";}
                  echo "&nbsp;&nbsp;<a href=\"http://edit.yahoo.com/config/send_webmesg?.target=".$posterdata['user_yim']."&amp;.src=pg\" target=\"_blank\"><img src=\"$imgtmp\" border=\"0\" alt=\"\" /></a>";
               }

               if ($posterdata["user_msnm"] != '') {
                  if ($ibid=theme_image("forum/icons/msnm.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/icons/msnm.gif";}
                  echo "&nbsp;&nbsp;<a href=\"user.php?op=userinfo&amp;uname=".$posterdata['uname']."\" target=\"_blank\"><img src=\"$imgtmp\" border=\"0\" alt=\"\" /></a>";
               }
            }
         }
         echo "</td></tr></table>";
         echo "</td></tr>";
         echo "<tr align=\"left\"><td colspan=\"2\" align=\"left\">";

         if ($posterdata['uid']<>1) {
            if ($ibid=theme_image("forum/icons/$language/reply.gif")) {$imgtmpD=$ibid;} else {$imgtmpD="images/forum/icons/$language/reply.gif";}
            echo "&nbsp;<a href=\"readpmsg_imm.php?op=read_msg&amp;msg_id=".$myrow['msg_id']."&amp;op_orig=$op&amp;sub_op=reply\"><img src=\"$imgtmpD\" border=\"0\" alt=\"\" /></a>";
         }
         if ($ibid=theme_image("forum/icons/$language/delete.gif")) {$imgtmpD=$ibid;} else {$imgtmpD="images/forum/icons/$language/delete.gif";}
         echo "&nbsp<a href=\"readpmsg_imm.php?op=delete&amp;msg_id=".$myrow['msg_id']."&amp;op_orig=$op\"><img src=\"$imgtmpD\" border=\"0\" alt=\"\" /></a>";

         if ($ibid=theme_image("forum/icons/$language/msg_read.gif")) {$imgtmpD=$ibid;} else {$imgtmpD="images/forum/icons/$language/msg_read.gif";}
         echo "&nbsp;<a href=\"readpmsg_imm.php?op=read_msg&amp;msg_id=".$myrow['msg_id']."&amp;op_orig=$op&amp;sub_op=read\"><img src=\"$imgtmpD\" border=\"0\" alt=\"\" /></a>";

         echo "</td></tr></table>";
         closetable();
      }
      if ($pasfin!=true) {
         cache_ctrl();
         echo "<body style=\"background-color: #ffffff;\" onload=\"self.close();\">";
      }
   }
   echo "</body></html>";
}

function sup_imm($msg_id) {
   global $cookie, $NPDS_Prefix;
   if (!$cookie) {
      Header("Location: user.php");
   } else {
      $sql="DELETE FROM ".$NPDS_Prefix."priv_msgs WHERE msg_id='$msg_id' AND to_userid='$cookie[0]'";
      if (!sql_query($sql))
         forumerror('0021');
   }
}

function read_imm($msg_id, $sub_op) {
   global $cookie, $NPDS_Prefix;
   if (!$cookie) {
      Header("Location: user.php");
   } else {
      $sql="UPDATE ".$NPDS_Prefix."priv_msgs SET read_msg='1' WHERE msg_id='$msg_id' AND to_userid='$cookie[0]'";
      if (!sql_query($sql))
         forumerror('0021');
      if ($sub_op=="reply") {
         echo "<script type=\"text/javascript\">
               //<![CDATA[
               window.location='replypmsg.php?reply=1&msg_id=$msg_id&userid=$cookie[0]&full_interface=short';
               //]]>
               </script>";
               die();
      }
      echo "<script type=\"text/javascript\">
            //<![CDATA[
            window.location='readpmsg_imm.php?op=new_msg';
            //]]>
            </script>";
      die();
   }
}

settype($op,'string');
switch ($op) {
   case "new_msg":
      show_imm($op);
      break;

   case "read_msg":
      read_imm($msg_id, $sub_op);
      break;

   case "delete":
      sup_imm($msg_id);
      show_imm($op_orig);
      break;

   default:
      show_imm($op);
      break;
}
?>

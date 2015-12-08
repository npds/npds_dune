<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */
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

   if (!$user) {
      Header("Location: user.php");
   } else {
      include('header.php');
      $userX = base64_decode($user);
      $userdata = explode(":", $userX);
      $userdata = get_userdata($userdata[1]);

      settype($start,"integer");
      settype($type,"string");
      if ($type=="outbox") {
         $sql = "SELECT * FROM ".$NPDS_Prefix."priv_msgs WHERE from_userid='".$userdata['uid']."' and type_msg='1' ORDER BY msg_id DESC LIMIT $start,1";
      } else {
         if ($dossier=="All") {$ibid="";} else {$ibid="and dossier='$dossier'";}
         if (!$dossier) {$ibid="and dossier='...'";}
         $sql = "SELECT * FROM ".$NPDS_Prefix."priv_msgs WHERE to_userid='".$userdata['uid']."' and type_msg='0' $ibid ORDER BY msg_id DESC LIMIT $start,1";
      }
      $resultID = sql_query($sql);
      if (!$resultID) {
         forumerror(0005);
      } else {
         $myrow = sql_fetch_assoc($resultID);
         if ($myrow['read_msg']!="1") {
            $sql = "UPDATE ".$NPDS_Prefix."priv_msgs SET read_msg='1' WHERE msg_id='".$myrow['msg_id']."'";
            $result = sql_query($sql);
            if (!$result) {
               forumerror(0005);
            }
         }
      }
      $myrow['subject']=strip_tags($myrow['subject']);
      opentable();
      echo '<h3>';
      if ($dossier=="All") {$Xdossier=translate("All Topics");} else {$Xdossier=StripSlashes($dossier);}
      echo translate("Private Message");
      echo '</h3>
         <p class="lead">
         <a href="viewpmsg.php">'.translate("Private Messages").'</a>&nbsp;&raquo;&raquo;&nbsp;'.$Xdossier.'&nbsp;&raquo;&raquo;&nbsp;'.aff_langue($myrow['subject']).'
         </p>';
      echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"1\" width=\"100%\">";
      if ($type=="outbox") {
         $posterdata = get_userdata_from_id($myrow['to_userid']);
         echo "<tr><td colspan=\"2\" class=\"ongl\">".translate("To")."</td></tr>";
      } else {
         $posterdata = get_userdata_from_id($myrow['from_userid']);
         echo "<tr><td colspan=\"2\" class=\"ongl\">".translate("From");
         if ($posterdata['uid']==1) echo "&nbsp;".$sitename;
         echo "</td></tr>";
      }
      if (!sql_num_rows($resultID)) {
         echo "<tr><td colspan=\"2\" align=\"center\">".translate("You don't have any Messages.");
      } else {
         echo "<tr class=\"ligna\">";
         echo "<td valign=\"top\" style=\"width: 15%;\"><b>";
         if ($posterdata['uid']<>1) echo $posterdata['uname']."</b><br />";
         $posts = $posterdata['posts'];
         if ($posterdata['uid']<>1) echo member_qualif($posterdata['uname'], $posts, $posterdata['rank']);
         echo "<br /><br />";
         if ($smilies) {
            if ($posterdata['user_avatar']!="") {
               if (stristr($posterdata['user_avatar'],"users_private")) {
                  $imgtmp=$posterdata['user_avatar'];
               } else {
                  if ($ibid=theme_image("forum/avatar/".$posterdata['user_avatar'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/avatar/".$posterdata['user_avatar'];}
               }
               echo '<img width="64" height="64" class=" img-thumbnail img-fluid n-ava" src="'.$imgtmp.'" alt="'.$posterdata['uname'].'" />';
            }
         }

         echo "</td><td valign=\"top\" style=\"width: 85%;\" height=\"100%\">";
         echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"height: 100%; width: 100%;\">";
         echo "<tr><td valign=\"top\" width=\"100%\" height=\"100%\">";
         if ($smilies) {
            if ($myrow['msg_image']!="") {
               if ($ibid=theme_image("forum/subject/".$myrow['msg_image'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/subject/".$myrow['msg_image'];}
               echo "<img src=\"$imgtmp\" alt=\"\" />&nbsp;";
            }
         }
         echo translate("Sent")." : ".$myrow['msg_time']."&nbsp;&nbsp;&nbsp";
         echo "<hr noshade=\"noshade\" class=\"ongl\" /><b>".aff_langue($myrow['subject'])."</b><br /><br />";
         $message = stripslashes($myrow['msg_text']);
         if ($allow_bbcode) {
            $message = smilie($message);
            $message = aff_video_yt($message);
         }
         $message = str_replace("[addsig]", "<br /><br />" . nl2br($posterdata['user_sig']), aff_langue($message));
         echo $message;
         echo "</td></tr><tr><td valign=\"bottom\">";

         if ($posterdata['uid']<>1) {
            echo "<hr noshade=\"noshade\" class=\"ongl\" />";
            if ($ibid=theme_image("forum/icons/profile.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/icons/profile.gif";}
            echo "&nbsp;&nbsp<a href=\"user.php?op=userinfo&amp;uname=".$posterdata['uname']."\" class=\"noir\"><img src=\"$imgtmp\" border=\"0\" alt=\"\" />".translate("Profile")."</a>";

            if ($posterdata["femail"]!="") {
               if ($ibid=theme_image("forum/icons/email.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/icons/email.gif";}
               echo "&nbsp;&nbsp;<a href=\"mailto:".$posterdata['femail']."\" class=\"noir\"><img src=\"$imgtmp\" border=\"0\" alt=\"\" />".translate("Email")."</a>";
            }

            if ($posterdata["url"]!="") {
               if (strstr("http://", $posterdata["url"]))
                  $posterdata["url"] = "http://" . $posterdata["url"];
               if ($ibid=theme_image("forum/icons/www_icon.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/icons/www_icon.gif";}
               echo "&nbsp;&nbsp;<a href=\"".$posterdata['url']."\" target=\"_blank\" class=\"noir\"><img src=\"$imgtmp\" border=\"0\" alt=\"\" />www</a>";
            }

            if (!$short_user) {
               if ($posterdata["user_icq"]!="") {
                  if ($ibid=theme_image("forum/icons/icq_on.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/icons/icq_on.gif";}
                  echo "&nbsp;&nbsp;<a href=\"http://wwp.mirabilis.com/".$posterdata['icq']."\" target=\"_blank\" class=\"noir\"><img src=\"$imgtmp\" border=\"0\"\" alt=\"\" />icq</a>";
               }

               if ($posterdata["user_aim"]!="") {
                  if ($ibid=theme_image("forum/icons/aim.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/icons/aim.gif";}
                  echo "&nbsp;&nbsp;<a href=\"aim:goim?screenname=".$posterdata['user_aim']."&amp;message=Hi+".$posterdata['user_aim'].".+Are+you+there?\" class=\"noir\"><img src=\"$imgtmp\" border=\"0\" alt=\"\" />aim</a>";
               }

               if ($posterdata["user_yim"]!="") {
                  if ($ibid=theme_image("forum/icons/yim.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/icons/yim.gif";}
                  echo "&nbsp;&nbsp;<a href=\"http://edit.yahoo.com/config/send_webmesg?.target=".$posterdata['user_yim']."&amp;.src=pg\"><img src=\"$imgtmp\" border=\"0\" alt=\"\" /></a>";
               }

               if ($posterdata["user_msnm"] != '') {
                  if ($ibid=theme_image("forum/icons/msnm.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/icons/msnm.gif";}
                  echo "&nbsp;&nbsp;<a href=\"user.php?op=userinfo&amp;uname=".$posterdata['uname']."\"><img src=\"$imgtmp\" border=\"0\" alt=\"\" /></a>";
               }
            }
         }
         echo '
         </td></tr>
         </table>
         </td></tr>';

         echo "<tr class=\"lignb\"><td colspan=\"2\" align=\"right\">";
         $previous = $start-1;
         $next = $start+1;
         if ($type=="outbox") {
            $tmpx="&amp;type=outbox";
         } else {
            $tmpx="&amp;dossier=".urlencode(StripSlashes($dossier));
         }
         echo '
         <ul class="pagination pagination-sm">';
         if ($previous >= 0) echo '<li><a href="readpmsg.php?start='.$previous.'&amp;total_messages='.$total_messages.$tmpx.'" class="noir">'.translate("Previous Messages").'</a></li>';
         else echo '<li class="disabled"><a href="#">'.translate("Previous Messages").'</a></li>';
         if ($next < $total_messages) echo "<li><a href='readpmsg.php?start=$next&amp;total_messages=$total_messages$tmpx' class=\"noir\">".translate("Next Messages").'</a></li>';
         else echo '<li class="disabled"><a href="#">'.translate("Next Messages").'</a></li>';
         echo '
         </ul>
         </td></tr>';

         echo "<tr><td colspan=\"2\" align=\"left\">";
         if ($ibid=theme_image("forum/icons/$language/reply.gif")) {$imgtmpR=$ibid;} else {$imgtmpR="images/forum/icons/$language/reply.gif";}
         if ($ibid=theme_image("forum/icons/$language/delete.gif")) {$imgtmpD=$ibid;} else {$imgtmpD="images/forum/icons/$language/delete.gif";}
         if ($type!="outbox") {
            echo '
            <form action="replypmsg.php" method="post">';
            if ($posterdata['uid']<>1) 
               echo '<a href="replypmsg.php?reply=1&amp;msg_id='.$myrow['msg_id'].'"><i class="fa fa-reply fa-lg"></i></a>';
            echo '&nbsp;<a href="replypmsg.php?delete=1&amp;msg_id='.$myrow['msg_id'].'"><img src="'.$imgtmpD.'" border="0" alt="" style="vertical-align: middle;" /></a>';
            // Classement
            $sql = "SELECT distinct dossier FROM ".$NPDS_Prefix."priv_msgs WHERE to_userid='".$userdata['uid']."' and type_msg='0' ORDER BY dossier";
            $result = sql_query($sql);
            echo '&nbsp;&nbsp;<strong>'.translate("Topic").'</strong> : <select class="form-control" name="dossier">';
            while (list($dossier)=sql_fetch_row($result)) {
               echo '
               <option value="'.$dossier.'">'.$dossier.'</option>';
            }
            echo '</select>
            <input type="submit" class="btn-primary" name="classe" value="OK" />';
            echo "&nbsp;<input type=\"texte\" class=\"form-control\" name=\"nouveau_dossier\" value=\"\" size=\"24\" />";
            echo '
            <input type="hidden" name="msg_id" value="'.$myrow['msg_id'].'" />
            <input type="hidden" name="classement" value="1" />
            </form>';
         } else {
            echo "&nbsp;<a href=\"replypmsg.php?delete=1&amp;msg_id=".$myrow['msg_id']."&amp;type=outbox\"><img src=\"$imgtmpD\" border=\"0\" alt=\"\" /></a>";
         }
      }
      echo '</td></tr></table>';
      closetable();
      include('footer.php');
   }
?>
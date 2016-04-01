<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x and PhpBB integration source code               */
/*                                                                      */
/* NPDS Copyright (c) 2002-2013 by Philippe Brunier                     */
/* Great mods by snipe                                                  */
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
global $NPDS_Prefix;

settype($cancel,'string');
if ($cancel) {
   header("Location: viewtopicH.php?topic=$topic&forum=$forum");
}

$rowQ1=Q_Select ("SELECT forum_name, forum_moderator, forum_type, forum_pass, forum_access, arbre FROM ".$NPDS_Prefix."forums WHERE forum_id = '$forum'", 3600);
if (!$rowQ1)
   forumerror('0001');
list(,$myrow) = each($rowQ1);
$forum_name = $myrow['forum_name'];
$forum_access = $myrow['forum_access'];
$forum_type=$myrow['forum_type'];
$mod=$myrow['forum_moderator'];

if ( ($forum_type == 1) and ($Forum_passwd != $myrow['forum_pass']) ) {
   header("Location: forum.php");
}
if ($forum_access==9) {
   header("Location: forum.php");
}
if (is_locked($topic)) {
   forumerror('0025');
}
if (!does_exists($forum, "forum") || !does_exists($topic, "topic")) {
   forumerror('0026');
}

settype($submitS,'string');
settype($stop,'integer');
if ($submitS) {
   if ($message=='') $stop=1;
   if (!isset($user)) {
      if ($forum_access==0) {
         $userdata = array("uid" => 1);
         $modo="";
         include("header.php");
      } else {
         if (($username=="") or ($password=="")) {
            forumerror('0027');
         } else {
            $result = sql_query("select pass FROM ".$NPDS_Prefix."users WHERE uname='$username'");
            list($pass) = sql_fetch_row($result);
            if (!$system) {
               $passwd=crypt($password,$pass);
            } else {
               $passwd=$password;
            }
            if ((strcmp($passwd,$pass)==0) and ($pass != "")) {
               $userdata = get_userdata($username);
               if ($userdata['uid']==1)
                  forumerror('0027');
               else
                  include("header.php");
            } else {
               forumerror('0028');
            }
            $modo=user_is_moderator($username,$pass,$forum_access);
            if ($forum_access==2) {
               if (!$modo)
                  forumerror('0027');
            }
         }
      }
   } else {
      $userX = base64_decode($user);
      $userdata = explode(":", $userX);
      $modo=user_is_moderator($userdata[0],$userdata[2],$forum_access);
      if ($forum_access==2) {
         if (!$modo)
            forumerror('0027');
      }
      $userdata = get_userdata($userdata[1]);
      include("header.php");
   }

   // Either valid user/pass, or valid session. continue with post.
   if ($stop != 1) {
      $poster_ip =  getip();
      if ($dns_verif)
         $hostname=@gethostbyaddr($poster_ip);
      else
         $hostname="";

      // anti flood
      anti_flood ($modo, $anti_flood, $poster_ip, $userdata, $gmt);
      //anti_spambot
      if (!R_spambot($asb_question, $asb_reponse, $message)) {
         Ecr_Log("security", "Forum Anti-Spam : forum=".$forum." / topic=".$topic, "");
         redirect_url("index.php");
         die();
      }

      if ($allow_html == 0 || isset($html)) $message = htmlspecialchars($message,ENT_COMPAT|ENT_HTML401,cur_charset);
      if (isset($sig) && $userdata['uid'] != 1) $message .= " [addsig]";
      if (($forum_type!="6") and ($forum_type!="5")) {
         $message = aff_code($message);
         $message = str_replace("\n", "<br />", $message);
      }
      if (($allow_bbcode==1) and ($forum_type!="6") and ($forum_type!="5")) {
         $message = smile($message);
      }
      if (($forum_type!="6") and ($forum_type!="5")){
         $message = make_clickable($message);
         $message = removeHack($message);
      }
      $image_subject=removeHack($image_subject);
      $message = addslashes($message);
      $time = date("Y-m-d H:i:s",time()+($gmt*3600));
      $sql = "INSERT INTO ".$NPDS_Prefix."posts (topic_id, image, forum_id, poster_id, post_text, post_time, poster_ip, poster_dns, post_idH) VALUES ('$topic', '$image_subject', '$forum', '".$userdata['uid']."', '$message', '$time', '$poster_ip', '$hostname', $post)";
      if (!$result = sql_query($sql)) {
         forumerror('0020');
      } else {
         $IdPost=sql_last_id();
      }
      $sql = "UPDATE ".$NPDS_Prefix."forumtopics SET topic_time = '$time', current_poster = '".$userdata['uid']."' WHERE topic_id = '$topic'";
      if (!$result = sql_query($sql)) {
         forumerror('0020');
      }
      $sql = "UPDATE ".$NPDS_Prefix."forum_read SET status='0' where topicid = '$topic' and uid <> '".$userdata['uid']."'";
      if (!$r = sql_query($sql)) {
         forumerror('0001');
      }
      $sql = "UPDATE ".$NPDS_Prefix."users_status SET posts=posts+1 WHERE (uid = '".$userdata['uid']."')";
      $result = sql_query($sql);
      if (!$result) {
         forumerror('0029');
      }
      $sql = "SELECT t.topic_notify, u.email, u.uname, u.uid, u.user_langue FROM ".$NPDS_Prefix."forumtopics t, ".$NPDS_Prefix."users u WHERE t.topic_id = '$topic' AND t.topic_poster = u.uid";
      if (!$result = sql_query($sql)) {
         forumerror('0022');
      }
      $m = sql_fetch_assoc($result);
      $sauf = "";
      if ( ($m['topic_notify'] == 1) && ($m['uname'] != $userdata['uname']) ) {
         include_once("language/lang-multi.php");
         $resultZ=sql_query("SELECT topic_title FROM ".$NPDS_Prefix."forumtopics WHERE topic_id='$topic'");
         list($title_topic)=sql_fetch_row($resultZ);
         $subject = strip_tags($forum_name)."/".$title_topic." : ".translate_ml($m['user_langue'], "Une réponse à votre dernier Commentaire a été posté.");
         $message = $m['uname']."\n\n";
         $message .= translate_ml($m['user_langue'], "Vous recevez ce Mail car vous avez demandé à être informé lors de la publication d'une réponse.")."\n";
         $message .= translate_ml($m['user_langue'], "Pour lire la réponse")." : ";
         $message .= "<a href=\"$nuke_url/viewtopicH.php?topic=$topic&forum=$forum\">$nuke_url/viewtopicH.php?topic=$topic&forum=$forum</a>\n\n";
         include("signat.php");
         if (!$system) {
            send_email($m['email'], $subject, $message, "", true, "html");
            $sauf=$m['uid'];
         }
      }
      global $subscribe;
      if ($subscribe) {
         if (subscribe_query($userdata['uid'],"forum",$forum)) {
            $sauf=$userdata['uid'];
         }
         subscribe_mail("forum",$topic,$forum,"",$sauf);
      }
      if (isset($upload)) {
         include("modules/upload/upload_forum.php");
         win_upload("forum_npds",$IdPost,$forum,$topic,"win");
      }
      redirect_url("viewtopicH.php?forum=$forum&topic=$topic");
   } else {
      opentable();
      echo "<p align=\"center\">".translate("You must type a message to post.")."<br /><br />";
      echo "[ <a href=\"javascript:history.go(-1)\" class=\"noir\">".translate("Go Back")."</a> ]</p>";
      closetable();
   }
} else {
   include('header.php');
   if ($allow_bbcode==1) {
      include("lib/formhelp.java.php");
   }

   list($topic_title, $topic_status) = sql_fetch_row(sql_query("select topic_title, topic_status from ".$NPDS_Prefix."forumtopics where topic_id='$topic'"));
   $userX = base64_decode($user);
   $userdata = explode(":", $userX);
   $moderator = get_moderator($mod);
   $moderator=explode(" ",$moderator);
   $Mmod=false;
   echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n";
   echo "<b>".translate("Moderated By: ")."</b>";
   for ($i = 0; $i < count($moderator); $i++) {
      echo "<a href=user.php?op=userinfo&amp;uname=$moderator[$i] class=\"box\">$moderator[$i]</a> ";
      if (isset($user))
         if (($userdata[1]==$moderator[$i])) { $Mmod=true;}
   }
   echo "</td></tr></table><br />";
   echo "<b>".translate("Post Reply in Topic:")."</b>";
   echo "&nbsp;<a href=\"viewforum.php?forum=$forum\" class=\"noir\">".stripslashes($forum_name)."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
   echo "<a href=\"forum.php\" class=\"noir\">".translate("Forum Index")."</a>\n";
   echo "<br />";
   echo "<form action=\"replyH.php\" method=\"post\" name=\"coolsus\">";
   echo "<table border=\"0\" cellspacing=\"1\" cellpadding=\"2\" width=\"100%\">";
   echo "<tr><td class=\"header\" colspan=\"2\" class=\"ongl\">".translate("About Posting:")."</td></tr><tr>";
   if ($forum_access == 0) {
      echo "<td colspan=\"2\">".translate("Anonymous users can post new topics and replies in this forum.")."</td>";
   } else if($forum_access == 1) {
      echo "<td colspan=\"2\">".translate("All registered users can post new topics and replies to this forum.")."</td>";
   } else if($forum_access == 2) {
      echo "<td colspan=\"2\">".translate("Only Moderators can post new topics and replies in this forum.")."</td>";
   }
   echo "</tr>";
   $allow_to_reply=false;
   if ($forum_access==0) {
      $allow_to_reply=true;
   } elseif ($forum_access==1) {
      if (isset($user)) {
         $allow_to_reply=true;
      }
   } elseif ($forum_access==2) {
      if (user_is_moderator($userdata[0],$userdata[2],$forum_access)) {
         $allow_to_reply=true;
      }
   }
   if ($topic_status!=0)
      $allow_to_reply=false;

   settype($submitP,'string');
   settype($citation,'integer');
   if ($allow_to_reply) {
      if ($submitP) {
         $acc = "reply";
         $message=stripslashes($message);
         echo "<tr><td colspan=\"2\">";
         include ("preview.php");
         echo "</td></tr>";
      } else {
         $message='';
      }
      echo "<tr align=\"left\">";
      echo "<td class=\"lignb\" width=\"25%\"><b>".translate("Nickname: ")."<b></td>";
      echo "<td class=\"lignb\">";
      if (isset($user))
         echo $userdata[1];
      else
         echo $anonymous;
      echo "</td></tr>";
      if ($smilies) {
         echo "<tr align=\"left\" valign=\"top\">
         <td class=\"lignb\" width=\"25%\"><b>".translate("Message Icon: ")."<b></td>
         <td class=\"lignb\">";
         settype($image_subject,'string');
         echo emotion_add($image_subject);
         echo "</td></tr>";
      }
      echo "<tr align=\"left\" valign=\"top\">";
      echo "<td class=\"lignb\" width=\"25%\"><b>".translate("Message")."</b><br /><br />";
      echo "<span>";
      echo "HTML : ";
      if ($allow_html==1) {
         echo translate("On")."<br />";
         echo HTML_Add();
      } else
         echo translate("Off")."<br />";
      if ($citation && !$submitP) {
         $sql = "SELECT p.post_text, p.post_time, u.uname FROM ".$NPDS_Prefix."posts p, ".$NPDS_Prefix."users u WHERE post_id = '$post' AND p.poster_id = u.uid";
         if ($r = sql_query($sql)) {
            $m = sql_fetch_assoc($r);
            $text = $m['post_text'];
            if (($allow_bbcode) and ($forum_type!=6) and ($forum_type!=5)) {
               $text = smile($text);
               $text = str_replace("<br />", "\n", $text);
            } else {
               $text = htmlspecialchars($text,ENT_COMPAT|ENT_HTML401,cur_charset);
            }
            $text = stripslashes($text);
            if ($m['post_time']!="" && $m['uname']!="") {
               $reply = "<div class=\"quote\">".translate("Quote")." : <b>".$m['uname']."</b>&nbsp;\n\n$text&nbsp;\n</div>";
            } else {
               $reply = "$text\n";
            }
            $reply = preg_replace("#\[hide\](.*?)\[\/hide\]#si","",$reply);
         } else {
            $reply = translate("Error Connecting to DB")."\n";
         }
         $message = $reply;
      }
      echo "</span></td>";
      if ($allow_bbcode)
         $xJava = 'name="message" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" onfocus="storeForm(this)"';
      echo "<td class=\"lignb\"><textarea class=\"textbox\" $xJava name=\"message\" rows=\"10\" cols=\"60\">$message</textarea><br />";
      if ($allow_bbcode)
         putitems();
      echo "</td></tr><tr align=\"left\">";
      echo "<td class=\"lignb\" width=\"25%\"><b>".translate("Options: ")."</b></td>";
      echo "<td class=\"lignb\">";
      if (($allow_html==1) and ($forum_type!="6") and ($forum_type!="5")) {
         if (isset($html)) {
            $sethtml = "checked";
         } else {
            $sethtml = "";
         }
         echo "<input type=\"checkbox\" name=\"html\" ".$sethtml.">".translate("Disable HTML on this Post")."<br />";
      }
      if ($user) {
         if ($allow_sig == 1) {
            $asig = sql_query("select attachsig from ".$NPDS_Prefix."users_status where uid='$cookie[0]'");
            list($attachsig) = sql_fetch_row($asig);
            if ($attachsig == 1||$sig == "on") {
               $s = "checked=\"checked\"";
            }
            if (($forum_type!="6") and ($forum_type!="5")) {
               echo "<input type=\"checkbox\" name=\"sig\" $s />".translate("Show signature")." <span style=\"font-size: 10px;\">(".translate("This can be altered or added in your profile").")</span><br />";
            }
         }
         if ($allow_upload_forum) {
            if ($upload == "on") {
               $up = "checked=\"checked\"";
            }
            echo "<input type=\"checkbox\" name=\"upload\" $up />".translate("Upload file after send accepted")."<br />";
         }
      }
      echo "</td></tr><tr>";
      echo "<td class=\"ligna\" align=\"center\">".Q_spambot()."</td>";
      echo "<td class=\"ligna\" align=\"center\">";
      echo "<input type=\"hidden\" name=\"forum\" value=\"$forum\" />";
      echo "<input type=\"hidden\" name=\"topic\" value=\"$topic\" />";
      echo "<input type=\"hidden\" name=\"post\" value=\"$post\" />";
      echo "<br /><input class=\"bouton_standard\" type=\"submit\" name=\"submitS\" value=\"".translate("Submit")."\" accesskey=\"s\" />&nbsp;";
      echo "&nbsp;<input class=\"bouton_standard\" type=\"submit\" name=\"submitP\" value=\"".translate("Preview")."\" />&nbsp;";
      echo "&nbsp;<input class=\"bouton_standard\" type=\"reset\" value=\"".translate("Clear")."\" />&nbsp;";
      echo "&nbsp;<input class=\"bouton_standard\" type=\"submit\" name=\"cancel\" value=\"".translate("Cancel Post")."\" /><br /><br />";
      echo "</td></tr>";
   } else {
      echo "<tr>";
      echo "<td class=\"ligna\" colspan=\"2\" align=\"center\">".translate("You are not allowed to reply in this forum")."</td>";
      echo "</tr>";
   }
   echo "</table></form>";
}
include('footer.php');
?>
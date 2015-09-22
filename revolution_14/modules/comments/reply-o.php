<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/* Based on Parts of phpBB                                              */
/*                                                                      */
/* NPDS Copyright (c) 2002-2013 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion")) {
   die();
}

include('functions.php');
include('auth.php');

filtre_module($file_name);
if (file_exists("modules/comments/$file_name.conf.php")) {
   include ("modules/comments/$file_name.conf.php");
} else {
   die();
}

settype($cancel,'string');
settype($url_ret,'string');
if ($cancel) {
   header("Location: $url_ret");
}

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

global $user;
if (($moderate==1) and $admin)
   $Mmod=true;
elseif ($moderate==2) {
   $userX=base64_decode($user);
   $userdata=explode(":", $userX);
   $result=sql_query("SELECT level FROM ".$NPDS_Prefix."users_status where uid='".$userdata[0]."'");
   list($level)=sql_fetch_row($result);
   if ($level>=2)
      $Mmod=true;
   else
      $Mmod=false;
} else
   $Mmod=false;
// gestion des params du 'forum' : type, accès, modérateur ...

if (isset($submitS)) {
   $stop=0;
   if ($message=='') $stop=1;
   if (!$user) {
      if ($forum_access==0) {
         $userdata = array("uid" => 1);
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
               include("header.php");
            } else {
               forumerror('0028');
            }
         }
      }
   } else {
      $userX = base64_decode($user);
      $userdata = explode(":", $userX);
      $userdata = get_userdata($userdata[1]);
      include("header.php");
   }

   // Either valid user/pass, or valid session. continue with post.
   if ($stop != 1) {
      $poster_ip =  getip();
      if ($dns_verif)
         $hostname=@gethostbyaddr($poster_ip);
      else
         $hostname=$poster_ip;

      // anti flood
      anti_flood ($Mmod, $anti_flood, $poster_ip, $userdata, $gmt);
      //anti_spambot
      if (isset($asb_question) and isset($asb_reponse)) {
         if (!R_spambot($asb_question, $asb_reponse, $message)) {
            Ecr_Log("security", "Forum Anti-Spam : forum=".$forum." / topic=".$topic, "");
            redirect_url("$url_ret");
            die();
         }
      }

      if ($formulaire!="") {
         include ("modules/comments/comments_extender.php");
      }

      if ($allow_html == 0 || isset($html)) $message = htmlspecialchars($message,ENT_COMPAT|ENT_HTML401,cur_charset);
      if (isset($sig) && $userdata['uid'] != 1) $message .= " [addsig]";
      $message = aff_code($message);
      $message = str_replace("\n", "<br />", $message);
      if ($allow_bbcode) {
         $message = smile($message);
      }
      $message = make_clickable($message);
      $message = removeHack($message);
      $image_subject="";
      $message = addslashes($message);
      $time = date("Y-m-d H:i:s",time()+($gmt*3600));
      $sql = "INSERT INTO ".$NPDS_Prefix."posts (post_idH, topic_id, image, forum_id, poster_id, post_text, post_time, poster_ip, poster_dns) VALUES ('0', '$topic', '$image_subject', '$forum', '".$userdata['uid']."', '$message', '$time', '$poster_ip', '$hostname')";
      if (!$result = sql_query($sql)) {
         forumerror('0020');
      } else {
         $IdPost=sql_last_id();
      }

      $sql = "UPDATE ".$NPDS_Prefix."users_status SET posts=posts+1 WHERE (uid = '".$userdata['uid']."')";
      $result = sql_query($sql);
      if (!$result) {
         forumerror('0029');
      }

      // ordre de mise à jour d'un champ externe ?
      if ($comments_req_add!="")
         sql_query("UPDATE ".$NPDS_Prefix.$comments_req_add);

      redirect_url("$url_ret");
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

   if ($formulaire=="")
      echo "<form action=\"modules.php\" method=\"post\" name=\"coolsus\">";
   echo "<table border=\"0\" cellspacing=\"1\" cellpadding=\"2\" width=\"100%\">";
   $allow_to_reply=false;
   if ($forum_access==0) {
      $allow_to_reply=true;
   } else {
      if (isset($user)) {
         $allow_to_reply=true;
      }
   }

   if ($allow_to_reply) {
     if (isset($submitP)) {
        $acc = "reply";
        $message=stripslashes($message);
        echo "<tr><td colspan=\"2\">";
        include ("preview.php");
        echo "</td></tr>";
     } else {
        $message="";
     }

     if ($formulaire!="") {
        echo "<tr align=\"left\" valign=\"top\">";
        echo "<td colspan=\"2\">";
        include ("modules/comments/comments_extender.php");
     } else {
        echo "<tr align=\"left\" valign=\"top\">";
        echo "<td class=\"lignb\" width=\"25%\"><b>".translate("Message: ")."</b><br /><br />";
        echo "<span style=\"font-size: 10px;\">";
        echo "HTML : ";
        if ($allow_html==1) {
           echo translate("On")."<br />";
           echo HTML_Add(false);
        } else
           echo translate("Off")."<br />";
        if (isset($citation) && !isset($submitP)) {
           $sql = "SELECT p.post_text, p.post_time, u.uname FROM ".$NPDS_Prefix."posts p, ".$NPDS_Prefix."users u WHERE post_id='$post' AND p.poster_id = u.uid";
           if ($r = sql_query($sql)) {
              $m = sql_fetch_assoc($r);
              $text = $m['post_text'];
              $text = smile($text);
              $text = str_replace("<br />", "\n", $text);
              $text = stripslashes($text);
              if ($m['post_time']!="" && $m['uname']!="") {
                 $reply = "<div class=\"quote\">".translate("Quote")." : <b>".$m['uname']."</b>&nbsp;\n\n$text&nbsp;\n</div>";
              } else {
                 $reply = $text."\n";
              }
           } else {
              $reply = translate("Error Connecting to DB")."\n";
           }
        }
        if (!isset($reply)) {$reply=$message;}
        echo "</span></td>";
        if ($allow_bbcode)
           $xJava = 'name="message" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" onfocus="storeForm(this)"';
        echo "<td class=\"lignb\"><textarea class=\"textbox\" $xJava name=\"message\" rows=\"10\" cols=\"60\">$reply</textarea><br />";
        if ($allow_bbcode)
           putitems();
        echo "</td></tr><tr align=\"left\">";
        echo "<td class=\"lignb\" width=\"25%\"><b>".translate("Options: ")."</b></td>";
        echo "<td class=\"lignb\">";
        if ($allow_html==1) {
           if (isset($html)) {
              $sethtml = "checked=\"checked\"";
           } else {
              $sethtml = "";
           }
           echo "<input type=\"checkbox\" name=\"html\" ".$sethtml." />".translate("Disable HTML on this Post")."<br />";
        }
        if ($user) {
           if ($allow_sig == 1||isset($sig)) {
              $asig = sql_query("select attachsig from ".$NPDS_Prefix."users_status where uid='$cookie[0]'");
              list($attachsig) = sql_fetch_row($asig);
              if ($attachsig == 1 or isset($sig)) {
                 $s = "checked=\"checked\"";
              } else
                 $s="";
              echo "<input type=\"checkbox\" name=\"sig\" $s />".translate("Show signature")." <span style=\"font-size: 10px;\">(".translate("This can be altered or added in your profile").")</span><br />";
           }
        }
        echo "</td></tr>";
        echo "<tr><td class=\"ligna\" align=\"center\">".Q_spambot()."</td>";
        echo "<td class=\"ligna\" align=\"center\">";
        echo "<input type=\"hidden\" name=\"ModPath\" value=\"comments\" />";
        echo "<input type=\"hidden\" name=\"ModStart\" value=\"reply\" />";
        echo "<input type=\"hidden\" name=\"topic\" value=\"$topic\" />";
        echo "<input type=\"hidden\" name=\"file_name\" value=\"$file_name\" />";
        echo "<input type=\"hidden\" name=\"archive\" value=\"$archive\" />";
        echo "<br /><input class=\"bouton_standard\" type=\"submit\" name=\"submitS\" value=\"".translate("Submit")."\" />&nbsp;";
        echo "&nbsp;<input class=\"bouton_standard\" type=\"submit\" name=\"submitP\" value=\"".translate("Preview")."\" />&nbsp;";
        echo "&nbsp;<input class=\"bouton_standard\" type=\"reset\" value=\"".translate("Clear")."\" />&nbsp;";
        echo "&nbsp;<input class=\"bouton_standard\" type=\"submit\" name=\"cancel\" value=\"".translate("Cancel Post")."\" /><br /><br />";
        echo "</td></tr>";
     }
   } else {
     echo "<tr>";
     echo "<td class=\"ligna\" colspan=\"2\" align=\"center\">".translate("You are not allowed to reply in this forum")."</td>";
     echo "</tr>";
   }
   echo "</table>";
   if ($formulaire=="")
      echo "</form>";
   if ($allow_to_reply) {
      if ($Mmod) {
         $post_aff="";
      } else {
         $post_aff=" and post_aff='1' ";
      }
      $sql = "SELECT * FROM ".$NPDS_Prefix."posts WHERE topic_id='$topic'".$post_aff." AND forum='$forum' ORDER BY post_id DESC limit 0,10";
      $result = sql_query($sql);
      if (sql_num_rows($result)) {
         echo "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">";
         echo "<tr><td class=\"header\" colspan=\"2\" class=\"ongl\" align=\"center\">".translate("Topic Review")."</td></tr>";

         while($myrow = sql_fetch_assoc($result)) {
            $rowcolor=tablos();
            $posterdata = get_userdata_from_id($myrow['poster_id']);
            echo "<tr $rowcolor align=\"left\">";
            echo "<td valign=\"top\" width=\"15%\">";
            if ($posterdata['uname']!=$anonymous) {
               echo "<a href=\"powerpack.php?op=instant_message&amp;to_userid=".$posterdata['uname']."\" class=\"noir\">".$posterdata['uname']."</a>";
            } else {
               echo $posterdata['uname'];
            }
            echo "<br />";
            $posts = $posterdata['posts'];
            echo member_qualif($posterdata['uname'], $posts, $posterdata['rank']);
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

            echo "</td><td valign=\"top\">";
            echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">";
            echo "<tr><td valign=\"top\" width=\"100%\" height=\"100%\">";

            if ($myrow['image'] != "") {
               if ($ibid=theme_image("forum/subject/".$myrow['image'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/subject/".$myrow['image'];}
               echo "<img src=\"$imgtmp\" alt=\"\" />";
            } else {
               if ($ibid=theme_image("forum/subject/icons/posticon.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/icons/posticon.gif";}
               echo "<img src=\"$imgtmp\" border=\"0\" alt=\"\" />";
            }
            echo "&nbsp;".translate("Posted: ").convertdate($myrow['post_time']);
            echo "<hr noshade=\"noshade\" size=\"1\" class=\"ongl\" />";
            $message = stripslashes($myrow['post_text']);
            if ($allow_bbcode) {
               $message = smilie($message);
            }
            // <a href in the message
            if (stristr($message,"<a href")) {
               $message=preg_replace('#_blank(")#i','_blank\1 class=\1noir\1',$message);
            }
            $message=split_string_without_space($message, 80);
            $message = str_replace("[addsig]", "<br /><br />" . nl2br($posterdata['user_sig']), $message);
            echo $message."<br />";
            echo "</td></tr></table>";
            echo "</td></tr>";
         }
         echo "</table>";
      }
   }
}
include('footer.php');
?>
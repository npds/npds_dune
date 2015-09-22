<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2013 by Philippe Brunier                     */
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

   if ($cancel) {
      if ($full_interface!="short") {
         header("Location: viewpmsg.php");
      } else {
         header("Location: readpmsg_imm.php?op=new_msg");
      }
      die();
   }

   if (!$user) {
      Header("Location: user.php");
   } else {
      $userX = base64_decode($user);
      $userdataX = explode(":", $userX);
      $userdata = get_userdata($userdataX[1]);

      if ($submitS) {
         if ($subject == '') {
            forumerror('0017');
         }
         $subject = removeHack($subject);

         if ($smilies) {
            if ($image == '' ) {
               forumerror('0018');
            }
         }

         if ($message == '') {
            forumerror('0019');
         }

         if ($allow_html == 0 || isset($html)) $message = htmlspecialchars($message,ENT_COMPAT|ENT_HTML401,cur_charset);
         if ($sig) {
            $message .= "<br /><br />".$userdata['user_sig'];
         }
         $message = aff_code($message);
         $message = str_replace("\n", "<br />", $message);
         if ($allow_bbcode) {
            $message = smile($message);
         }
         $message = make_clickable($message);
         $message = removeHack(addslashes($message));
         $time = date(translate("dateinternal"),time()+($gmt*3600));

         include_once("language/lang-multi.php");
         if (strstr($to_user,",")) {
            $tempo=explode(",",$to_user);
            while (list(,$to_user) = each($tempo)) {
               $res = sql_query("select uid, user_langue from ".$NPDS_Prefix."users where uname='$to_user'");
               list($to_userid, $user_langue) = sql_fetch_row($res);
               if (($to_userid != "") and ($to_userid != 1)) {
                  $sql = "INSERT INTO ".$NPDS_Prefix."priv_msgs (msg_image, subject, from_userid, to_userid, msg_time, msg_text) ";
                  $sql .= "VALUES ('$image', '$subject', '".$userdata['uid']."', '$to_userid', '$time', '$message')";
                  if(!$result = sql_query($sql)) {
                    forumerror('0020');
                  }
                  if ($copie) {
                     $sql = "INSERT INTO ".$NPDS_Prefix."priv_msgs (msg_image, subject, from_userid, to_userid, msg_time, msg_text, type_msg, read_msg) ";
                     $sql .= "VALUES ('$image', '$subject', '".$userdata['uid']."', '$to_userid', '$time', '$message', '1', '1')";
                     if (!$result = sql_query($sql)) {
                        forumerror('0020');
                     }
                  }
                  global $nuke_url, $subscribe;
                  if ($subscribe) {
                     $old_message=$message;
                     $sujet=translate_ml($user_langue, "Vous avez un nouveau message.");
                     $message=translate_ml($user_langue, "Bonjour").",<br /><br /><a href=\"$nuke_url/viewpmsg.php\">".translate_ml($user_langue, "Cliquez ici pour lire votre nouveau message.")."</a><br /><br />";
                     include("signat.php");
                     copy_to_email($to_userid,$sujet,$message);
                     $message=$old_message;
                  }
               }
            }
         } else {
            $res = sql_query("select uid, user_langue from ".$NPDS_Prefix."users where uname='$to_user'");
            list($to_userid, $user_langue) = sql_fetch_row($res);

            if (($to_userid == "") or ($to_userid == 1)) {
               forumerror('0016');
            } else {
               $sql = "INSERT INTO ".$NPDS_Prefix."priv_msgs (msg_image, subject, from_userid, to_userid, msg_time, msg_text) ";
               $sql .= "VALUES ('$image', '$subject', '".$userdata['uid']."', '$to_userid', '$time', '$message')";
               if (!$result = sql_query($sql)) {
                  forumerror('0020');
               }
               if ($copie) {
                  $sql = "INSERT INTO ".$NPDS_Prefix."priv_msgs (msg_image, subject, from_userid, to_userid, msg_time, msg_text, type_msg, read_msg) ";
                  $sql .= "VALUES ('$image', '$subject', '".$userdata['uid']."', '$to_userid', '$time', '$message', '1', '1')";
                  if (!$result = sql_query($sql)) {
                     forumerror('0020');
                  }
               }
               global $nuke_url, $subscribe;
               if ($subscribe) {
                  $sujet=translate_ml($user_langue, "Vous avez un nouveau message.");
                  $message=translate_ml($user_langue, "Bonjour").",<br /><br /><a href=\"$nuke_url/viewpmsg.php\">".translate_ml($user_langue, "Cliquez ici pour lire votre nouveau message.")."</a><br /><br />";
                  include("signat.php");
                  copy_to_email($to_userid,$sujet,$message);
               }
            }
         }
         unset($message);unset($sujet);
         if ($full_interface!="short") {
            header("Location: viewpmsg.php");
         } else {
            header("Location: readpmsg_imm.php?op=new_msg");
         }
      }

      if ($delete_messages.x && $delete_messages.y) {
         for ($i=0;$i<$total_messages;$i++) {
            if ($type=="outbox") {
               $sql = "DELETE FROM ".$NPDS_Prefix."priv_msgs WHERE msg_id='$msg_id[$i]' AND from_userid='".$userdata['uid']."' and type_msg='1'";
            } else {
               $sql = "DELETE FROM ".$NPDS_Prefix."priv_msgs WHERE msg_id='$msg_id[$i]' AND to_userid='".$userdata['uid']."'";
            }
            if (!sql_query($sql)) {
               forumerror('0021');
            } else {
               $status=1;
            }
         }
         if ($status) {
            header("Location: viewpmsg.php");
         }
      }

      if ($delete) {
         if ($type=="outbox") {
            $sql = "DELETE FROM ".$NPDS_Prefix."priv_msgs WHERE msg_id='$msg_id' AND from_userid='".$userdata['uid']."' and type_msg='1'";
         } else {
            $sql = "DELETE FROM ".$NPDS_Prefix."priv_msgs WHERE msg_id='$msg_id' AND to_userid='".$userdata['uid']."'";
         }
         if (!sql_query($sql)) {
            forumerror('0021');
         } else {
            header("Location: viewpmsg.php");
         }
      }

      if ($classement) {
         if ($nouveau_dossier!="") {$dossier=$nouveau_dossier;}
         $dossier=strip_tags($dossier);
         $sql = "UPDATE ".$NPDS_Prefix."priv_msgs SET dossier='$dossier' WHERE msg_id='$msg_id' AND to_userid='".$userdata['uid']."'";
         $result = sql_query($sql);
         if (!$result) {
            forumerror(0005);
         }
         header("Location: viewpmsg.php");
      }

      // Interface
      if ($full_interface=="short") {
         if ($userdataX[9]!="") {
            if (!$file=@opendir("themes/$userdataX[9]")) {
               $tmp_theme=$Default_Theme;
            } else {
               $tmp_theme=$userdataX[9];
            }
         } else {
            $tmp_theme=$Default_Theme;
         }
         include("themes/$tmp_theme/theme.php");
         include("meta/meta.php");
         echo import_css($tmp_theme, $language, $site_font, "","");
         echo "</head>\n<body topmargin=\"0\" leftmargin=\"0\" marginwidth=\"0\" marginheight=\"0\">";
      } else {
         include('header.php');
      }
      if ($reply || $send || $to_user) {
         if ($allow_bbcode) {
            include("lib/formhelp.java.php");
         }
         if ($reply) {
            $sql = "SELECT msg_image, subject, from_userid, to_userid FROM ".$NPDS_Prefix."priv_msgs WHERE to_userid='".$userdata['uid']."' and msg_id='$msg_id' and type_msg='0'";
            $result = sql_query($sql);
            if (!$result) {
               forumerror('0022');
            }
            $row = sql_fetch_assoc($result);
            if (!$row) {
               forumerror('0023');
            }
            $fromuserdata = get_userdata_from_id($row['from_userid']);
            if ($fromuserdata[0]==1) {
               forumerror('0101');
            }
            $touserdata = get_userdata_from_id($row['to_userid']);

            if (($user) and ($userdata['uid']!=$touserdata['uid'])) {
               forumerror('0024');
            }
         }
         opentable();
         echo "<form action=\"replypmsg.php\" method=\"post\" name=\"coolsus\">";
         echo "<table border=\"0\" cellspacing=\"1\" cellpadding=\"2\" width=\"100%\">";
         echo "<tr>";
         echo "<td colspan=\"2\" class=\"header\">".translate("About Posting:")."</td></tr>";
         echo "<tr><td colspan=\"2\">".translate("All registered users can post private messages.")."</td>";
         echo "</tr>";
         if ($submitP) {
            echo "<tr><td colspan=\"2\">";
            echo "<hr noshade=\"noshade\" class=\"ongl\" /><p align=\"center\" class=\"header\">".translate("Preview")."</p><table border=\"0\" cellpadding=\"2\" cellspacing=\"1\" width=\"100%\">";
            echo "<tr><td>";
            echo "<b>".StripSlashes($subject)."</b><br /><br />\n";
            $Xmessage=$message=StripSlashes($message);
            if ($allow_html == 0 || isset($html)) $Xmessage = htmlspecialchars($Xmessage,ENT_COMPAT|ENT_HTML401,cur_charset);
            if ($sig) {
               $Xmessage .= "<br /><br />".$userdata['user_sig'];
            }
            $Xmessage = aff_code($Xmessage);
            $Xmessage = str_replace("\n", "<br />", $Xmessage);
            if ($allow_bbcode) {
               $Xmessage = smilie($Xmessage);
               $Xmessage = aff_video_yt($Xmessage);
            }
            $Xmessage = make_clickable($Xmessage);
            echo $Xmessage;
            echo"<hr noshade=\"noshade\" class=\"ongl\" /></td></tr></table>";
            echo "</td></tr>";
         }
         echo "<tr align=\"left\">";
         echo "<td class=\"ligna\" width=\"25%\"><b>".translate("To: ")."<b>";
         if (!$reply) {
            $carnet=JavaPopUp("carnet.php","CARNET",300,350);
            $carnet="&nbsp;&nbsp;[ <a href=\"javascript:void(0);\" onclick=\"window.open($carnet);\">";
            echo $carnet."<span style=\"font-size: 10px;\">".translate("Bookmark")."</a></span> ]";
         }
         echo "</td>";
         if ($reply) {
            echo "<td class=\"ligna\"><input type=\"hidden\" name=\"to_user\" value=\"".$fromuserdata['uname']."\" />".$fromuserdata['uname'];
         } else {
            if ($send!=1) { $Xto_user=$send;}
            if ($to_user) { $Xto_user=$to_user; }
            echo "<td class=\"ligna\"><input class=\"textbox_standard\" type=\"text\" name=\"to_user\" value=\"$Xto_user\" size=\"30\" maxlength=\"100\" />";
         }

         if ($copie) {$checked="checked=\"checked\"";} else {$checked="";}
         echo " - <input type=\"checkbox\" name=\"copie\" $checked /> ".translate("Send a copy to me")."</td>";

         echo "</tr><tr align=\"left\"><td class=\"ligna\" width=\"22%\"><b>".translate("Subject: ")."<b></td>";
         if ($subject) {$tmp=StripSlashes($subject);} else {if ($reply) $tmp="Re: ".StripSlashes($row['subject']); else $tmp="";}
         echo "<td class=\"lignb\"><input class=\"textbox\" type=\"text\" name=\"subject\" value=\"$tmp\" size=\"45\" maxlength=\"100\" /></td>";
         echo "</tr>";

         if ($smilies) {
            echo "<tr align=\"left\" valign=\"top\">
            <td class=\"ligna\" width=\"25%\"><b>".translate("Message Icon: ")."<b></td>
            <td class=\"lignb\">";
            if ($ibid=theme_image("forum/subject/index.html")) {$imgtmp="themes/$theme/images/forum/subject";} else {$imgtmp="images/forum/subject";}
            $handle=opendir($imgtmp);
            while (false!==($file=readdir($handle))) {
               $filelist[] = $file;
            }
            asort($filelist);
            $a=1; $count=1;
            while (list ($key, $file) = each ($filelist)) {
               if (!preg_match('#\.gif|\.jpg|\.png$#i', $file)) continue;
               settype($image,'string');
               if ($file==$image) {
                  echo "<input type=\"radio\" name=\"image\" value=\"$file\" checked=\"checked\" /><img src=\"$imgtmp/$file\" border=\"0\" alt=\"\" />&nbsp;";
                  $a++;
               } else if ($file==$row['msg_image'] && $row['msg_image']!="") {
                  echo "<input type=\"radio\" name=\"image\" value=\"$file\" checked=\"checked\" /><img src=\"$imgtmp/$file\" border=\"0\" alt=\"\" />&nbsp;";
               } else {
                  if ($a==1 && $row['msg_image']=="") {
                     $sel="checked=\"checked\"";
                  } else {
                     $sel="";
                  }
                  echo "<input type=\"radio\" name=\"image\" value=\"$file\" $sel /><img src=\"$imgtmp/$file\" border=\"0\" alt=\"\" />&nbsp;";
                  $a++;
               }
               if ($count>= 11) {$count=0; echo "<br />";}
               $count++;
           }
         }
         echo "</td></tr><tr align=\"left\" valign=\"top\"><td class=\"ligna\" width=\"22%\"><b>".translate("Message: ")."</b><br /><br />";
         echo "HTML : ";
         if ($allow_html == 1) {
            echo translate("On")."<br />";
            echo HTML_Add(false);
         } else
            echo translate("Off")."<br />";

         if ($reply and $message=="") {
            $sql = "SELECT p.msg_text, p.msg_time, u.uname FROM ".$NPDS_Prefix."priv_msgs p, ".$NPDS_Prefix."users u ";
            $sql .= "WHERE (p.msg_id='$msg_id') AND (p.from_userid=u.uid)  and (p.type_msg='0')";
            if ($result = sql_query($sql)) {
               $row = sql_fetch_assoc($result);
               $text = smile($row['msg_text']);
               $text = str_replace("<br />", "\n", $text);
               $text = str_replace("<BR />", "\n", $text);
               $text = str_replace("<BR>", "\n", $text);
               $text = stripslashes($text);
               if ($row['msg_time']!="" && $row['uname']!="") {
                  $Xreply = $row['msg_time'].", ".$row['uname']." ".translate("wrote:")."\n$text\n";
               } else {
                  $Xreply = "$text\n";
               }
               $Xreply = "<div class=\"quote\">\n".$Xreply."</div>";
            } else {
               $Xreply = translate("Could not connect to the forums database.")."\n";
            }
         } elseif ($message!="") {
            $Xreply = $message;
         }
         if ($allow_bbcode)
            $xJava = 'name="message" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" onfocus="storeForm(this)"';
         echo "</td><td class=\"lignb\"><textarea class=\"textbox\" $xJava name=\"message\" rows=\"10\" cols=\"60\">";
         if ($Xreply) echo $Xreply;
         echo "</textarea><br />";
         if ($allow_bbcode)
            putitems();

         echo "</td></tr>";
         echo "<tr align=\"left\"><td class=\"ligna\" width=\"25%\"><b>".translate("Options: ")."</b></td>";
         echo "<td class=\"lignb\">";
         if ($allow_html==1) {
            if ($html) {$checked="checked";} else {$checked="";}
            echo "<input type=\"checkbox\" name=\"html\" $checked>".translate("Disable HTML on this Post")."<br />";
         }

         if ($allow_sig==1) {
            $asig = sql_query("select attachsig from ".$NPDS_Prefix."users_status where uid='$cookie[0]'");
            list($attachsig) = sql_fetch_row($asig);
            if ($attachsig == 1) {
               $s = "checked";
            }
            if ($sig) {$checked="checked=\"checked\"";} else {$checked="";}
            echo "<input type=\"checkbox\" name=\"sig\" $checked />".translate("Show signature")." <span style=\"font-size: 10px;\">(".translate("This can be altered or added in your profile").")</span><br />";
         }

         echo "</td></tr><tr><td class=\"lignb\" colspan=\"2\">
              <br />
              <input type=\"hidden\" name=\"msg_id\" value=\"$msg_id\" />
              <input type=\"hidden\" name=\"full_interface\" value=\"$full_interface\" />";
              if ($send==1)
                 echo "<input type=\"hidden\" name=\"send\" value=\"1\" />";
              if ($reply==1)
                 echo "<input type=\"hidden\" name=\"reply\" value=\"1\" />";
              echo "<input class=\"bouton_standard\" type=\"submit\" name=\"submitS\" value=\"".translate("Submit")."\" accesskey=\"s\" />
              &nbsp;<input class=\"bouton_standard\" type=\"submit\" name=\"submitP\" value=\"".translate("Preview")."\" />
              &nbsp;<input class=\"bouton_standard\" type=\"reset\" value=\"".translate("Clear")."\" />";
         if ($reply)
            echo "&nbsp;<input class=\"bouton_standard\" type=\"submit\" name=\"cancel\" value=\"".translate("Cancel Reply")."\" />";
         else
            echo "&nbsp;<input class=\"bouton_standard\" type=\"submit\" name=\"cancel\" value=\"".translate("Cancel Send")."\" />";

         echo "<br /><br /></td></tr></table></form>";
         closetable();
         if ($full_interface!="short") {
            include('footer.php');
         }
      }
   }
?>
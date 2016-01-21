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
               $res = sql_query("SELECT uid, user_langue FROM ".$NPDS_Prefix."users WHERE uname='$to_user'");
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
            $res = sql_query("SELECT uid, user_langue FROM ".$NPDS_Prefix."users WHERE uname='$to_user'");
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
               $sql = "DELETE FROM ".$NPDS_Prefix."priv_msgs WHERE msg_id='$msg_id[$i]' AND from_userid='".$userdata['uid']."' AND type_msg='1'";
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
            $sql = "DELETE FROM ".$NPDS_Prefix."priv_msgs WHERE msg_id='$msg_id' AND from_userid='".$userdata['uid']."' AND type_msg='1'";
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
            $sql = "SELECT msg_image, subject, from_userid, to_userid FROM ".$NPDS_Prefix."priv_msgs WHERE to_userid='".$userdata['uid']."' AND msg_id='$msg_id' AND type_msg='0'";
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
         echo '
         <blockquote class="blockquote">'.translate("About Posting:").'<br />'.
         translate("All registered users can post private messages.").'</blockquote>';
         echo '
         <form action="replypmsg.php" method="post" name="coolsus">';
         if ($submitP) {
            echo "<hr noshade=\"noshade\" class=\"ongl\" /><p align=\"center\" class=\"header\">".translate("Preview")."</p><table border=\"0\" cellpadding=\"2\" cellspacing=\"1\" width=\"100%\">";
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
         }
         echo '
       <div class="form-group row">
         <div class="col-sm-3">
            <label class="form-control-label" for="to_user">'.translate("Destinataire").'</label>
         </div>
      <div class="col-sm-9">';
         if (!$reply) {
            $carnet=JavaPopUp("carnet.php","CARNET",300,350);
            $carnet='&nbsp;<a href="javascript:void(0);" onclick="window.open('.$carnet.'); ">';
            echo $carnet.'<span class="small">'.translate("Bookmark").'</a></span>';
         }
         if ($reply) {
            echo '<input type="hidden" name="to_user" value="'.$fromuserdata['uname'].'" />'.$fromuserdata['uname'];
         } else {
            if ($send!=1) { $Xto_user=$send;}
            if ($to_user) { $Xto_user=$to_user; }
            echo '<input class="form-control" type="text" name="to_user" value="'.$Xto_user.'" maxlength="100" />';
         }
         echo '
         </div>
      </div>';

         if ($copie) {$checked='checked="checked"';} else {$checked='';}
         echo '
      <div class="form-group row">
         <input type="checkbox" name="copie" '.$checked.' /> '.translate("Send a copy to me").'</label>';

         echo '
      <div class="form-group row">
         <div class="col-sm-3">
            <label class="form-control-label" for="subject">'.translate("Subject: ").'</label>
         </div>
         <div class="col-sm-9">';
         if ($subject) {$tmp=StripSlashes($subject);} else {if ($reply) $tmp="Re: ".StripSlashes($row['subject']); else $tmp="";}
         echo '
            <input class="form-control" type="text" name="subject" value="'.$tmp.'" maxlength="100" />
         </div>
      </div>';

         if ($smilies) {
            echo '
      <div class="form-group row">
         <div class="col-sm-3">
            <label class="form-control-label">'.translate("Message Icon: ").'</label>
         </div>
         <div class="col-sm-9">
         ';
            if ($ibid=theme_image("forum/subject/index.html")) {$imgtmp="themes/$theme/images/forum/subject";} else {$imgtmp="images/forum/subject";}
            $handle=opendir($imgtmp);
            while (false!==($file=readdir($handle))) {
               $filelist[] = $file;
            }
            asort($filelist);
            $a=1;
            while (list ($key, $file) = each ($filelist)) {
               if (!preg_match('#\.gif|\.jpg|\.png$#i', $file)) continue;
               settype($image,'string');
               if ($file==$image) {
                  echo "<input type=\"radio\" name=\"image\" value=\"$file\" checked=\"checked\" /><img class=\"smile\" src=\"$imgtmp/$file\" border=\"0\" alt=\"\" />&nbsp;";
                  $a++;
               } else if ($file==$row['msg_image'] && $row['msg_image']!="") {
                  echo "<input type=\"radio\" name=\"image\" value=\"$file\" checked=\"checked\" /><img class=\"smil\" src=\"$imgtmp/$file\" border=\"0\" alt=\"\" />&nbsp;";
               } else {
                  if ($a==1 && $row['msg_image']=="") {
                     $sel='checked="checked"';
                  } else {
                     $sel='';
                  }
                  echo '<input type="radio" name="image" value="'.$file.'" '.$sel.' /> <img class="smil" src="'.$imgtmp.'/'.$file.'" alt="" />&nbsp;';
                  $a++;
               }
           }
         }
         echo '
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-3">
            <label class="form-control-label" for="message">'.translate("Message: ").'</label>
         </div>
         <div class="col-sm-9">
            <div class="card">
               <div class="card-header">';
         if ($allow_html == 1) {
            echo '<span class="text-success pull-right" title="HTML '.translate("On").'" data-toggle="tooltip"><i class="fa fa-code fa-lg"></i></span>'.HTML_Add();
            //echo HTML_Add(false);
         } else
            echo '<span class="text-danger pull-right" title="HTML '.translate("Off").'" data-toggle="tooltip"><i class="fa fa-code fa-lg"></i></span>';
         echo '
            </div>
            <div class="card-block">';
            
         if ($reply and $message=="") {
            $sql = "SELECT p.msg_text, p.msg_time, u.uname FROM ".$NPDS_Prefix."priv_msgs p, ".$NPDS_Prefix."users u ";
            $sql .= "WHERE (p.msg_id='$msg_id') AND (p.from_userid=u.uid) AND (p.type_msg='0')";
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
         echo '<textarea class="form-control" '.$xJava.' name="message" rows="15">';
         if ($Xreply) echo $Xreply;
         echo '
            </textarea>
               </div>
               <div class="card-footer text-muted">';
         if ($allow_bbcode)
            putitems();
      echo '
               </div>
            </div>
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-3">
            <label class="form-control-label">'.translate("Options: ").'</label>
         </div>';
         if ($allow_html==1) {
            if ($html) {$checked="checked";} else {$checked="";}
            echo '
         <div class="col-sm-9">
            <div class="checkbox">
               <label class="">
                  <input type="checkbox" name="html" '.$checked.'>'.translate("Disable HTML on this Post").'
               </label>
            </div>';
         }

         if ($allow_sig==1) {
            $asig = sql_query("SELECT attachsig FROM ".$NPDS_Prefix."users_status WHERE uid='$cookie[0]'");
            list($attachsig) = sql_fetch_row($asig);
            if ($attachsig == 1) {
               $s = "checked";
            }
            if ($sig) {$checked='checked="checked"';} else {$checked="";}
            echo '
            <div class="checkbox">
               <label class="">
                  <input type="checkbox" name="sig" '.$checked.' /> '.translate("Show signature").' :<br /><small>'.translate("This can be altered or added in your profile").'</small>
               </label>
            </div>';
         }

         echo '
         </div>
      </div>
         
              <input type="hidden" name="msg_id" value="'.$msg_id.'" />
              <input type="hidden" name="full_interface" value="'.$full_interface.'" />';
              if ($send==1)
                 echo '<input type="hidden" name="send" value="1" />';
              if ($reply==1)
                 echo '<input type="hidden" name="reply" value="1" />';
              echo "<input class=\"btn btn-secondary\" type=\"submit\" name=\"submitS\" value=\"".translate("Submit")."\" accesskey=\"s\" />
              &nbsp;<input class=\"btn btn-secondary\" type=\"submit\" name=\"submitP\" value=\"".translate("Preview")."\" />
              &nbsp;<input class=\"btn btn-secondary\" type=\"reset\" value=\"".translate("Clear")."\" />";
         if ($reply)
            echo "&nbsp;<input class=\"btn btn-secondary\" type=\"submit\" name=\"cancel\" value=\"".translate("Cancel Reply")."\" />";
         else
            echo "&nbsp;<input class=\"btn btn-secondary\" type=\"submit\" name=\"cancel\" value=\"".translate("Cancel Send")."\" />";

         echo '</form>';
         if ($full_interface!="short") {
            include('footer.php');
         }
      }
   }
?>
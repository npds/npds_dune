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
   header("Location: viewtopic.php?topic=$topic&forum=$forum");
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
         $modo="";;
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
      if (isset($sig) && $userdata['uid']!= 1) $message .= " [addsig]";
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

      $sql = "INSERT INTO ".$NPDS_Prefix."posts (post_idH, topic_id, image, forum_id, poster_id, post_text, post_time, poster_ip, poster_dns) VALUES ('0', '$topic', '$image_subject', '$forum', '".$userdata['uid']."', '$message', '$time', '$poster_ip', '$hostname')";
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
         $message .= "<a href=\"$nuke_url/viewtopic.php?topic=$topic&forum=$forum&start=9999#last-post\">$nuke_url/viewtopic.php?topic=$topic&forum=$forum&start=9999</a>\n\n";
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
         redirect_url("viewtopic.php?forum=$forum&topic=$topic&start=9999#last-post");
         die();
      }
      redirect_url("viewforum.php?forum=$forum");
   } else {
      echo '<p class="text-center">'.translate("You must type a message to post.").'<br /><br />';
      echo "[ <a href=\"javascript:history.go(-1)\" class=\"noir\">".translate("Go Back")."</a> ]</p>";
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

   echo '<h3>'.translate("Moderated By: ").'';
   for ($i = 0; $i < count($moderator); $i++) {
      echo '<a href="user.php?op=userinfo&amp;uname='.$moderator[$i].'">'.$moderator[$i].'</a></h3>';	  
      if (isset($user))
         if (($userdata[1]==$moderator[$i])) { $Mmod=true;}
   }
   echo '<strong>'.translate("Post Reply in Topic:").'</strong>';
   echo '&nbsp;<a href="viewforum.php?forum='.$forum.'">'.stripslashes($forum_name).'</a>&nbsp;&nbsp;|&nbsp;&nbsp;';
   echo '<a href="forum.php" class="noir">'.translate("Forum Index").'</a>';
   echo '<br />';
   

   echo '
		<form class="form" role="form" action="reply.php" method="post" name="coolsus">';
   echo '<div class="form-group">';

   echo '<br />';

       if ($forum_access == 0) {
      echo '<span class="text-warning">'.translate("Anonymous users can post new topics and replies in this forum.").'</span>';
   } else if($forum_access == 1) {
      echo '<span class="text-warning">'.translate("All registered users can post new topics and replies to this forum.").'</span>';
   } else if($forum_access == 2) {
      echo '<span class="text-warning">'.translate("Only Moderators can post new topics and replies in this forum.").'</span>';
   }
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

        include ("preview.php");

     } else {
        $message="";
     }
	 echo '<br />';
     echo '<strong>'.translate("Nickname: ").'</strong>';
     if (isset($user))
        echo $userdata[1];
     else
        echo $anonymous;	 
         if ($smilies) {		 
		  echo '	 
            <div class="form-group">
			            <div class="row">			
               <label class="control-label col-sm-2 col-md-2" for="aid">'.translate("Message Icon: ").'</label>
               <div class="col-sm-8 col-md-10">';
				settype($image_subject,'string');
				echo emotion_add($image_subject);			   
		  echo '			   
               </div>
			   </div>
            </div>
		';	 
     }	 
	 
        echo ' 
            <div class="form-group">
			            <div class="row">			
               <label class="control-label col-sm-2 col-md-2" for="aid">';
     echo "HTML : ";
     if ($allow_html==1) {
        echo translate("On")."<br /></span>";
        echo HTML_Add();
     } else
        echo translate("Off")."<br /></span>";
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
              $reply = "<div class=\"quote\">".translate("Quote")." : <strong>".$m['uname']."</strong>&nbsp;\n\n$text&nbsp;\n</div>";
           } else {
              $reply = $text."\n";
           }
           $reply = preg_replace("#\[hide\](.*?)\[\/hide\]#si","",$reply);
        } else {
           $reply = translate("Error Connecting to DB")."\n";
        }
     }
     if (!isset($reply)) {$reply=$message;}
     if ($allow_bbcode)
        $xJava = ' onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" onfocus="storeForm(this)"';
		echo '
			</label>
            <div class="col-sm-10 col-md-10">		
			<textarea class="form-control" '.$xJava.' name="message" rows="12">'.$reply.'</textarea>
			</div>
			</div>
			</div>
			';	
	 
     if ($allow_bbcode)
        putitems();
	
         echo '
		 <h3>'.translate("Options: ").'</h3>	 
		 ';
     if (($allow_html==1) and ($forum_type!="6") and ($forum_type!="5")) {
        if (isset($html)) {
           $sethtml = "checked";
        } else {
           $sethtml = "";
        }
         echo '	 
			<div class="form-group">
		            <div class="row">
               <label class="control-label col-sm-4 col-md-4" for="aid">'.translate("Disable HTML on this Post").' :</label>
               <div class="col-sm-1 col-md-1">
                  <input class="form-control" type="checkbox" name="html" '.$sethtml.' />
               </div>
			   </div>
            </div>
		 ';
     }
     if ($user) {
        if ($allow_sig == 1||$sig == "on") {
           $asig = sql_query("select attachsig from ".$NPDS_Prefix."users_status where uid='$cookie[0]'");
           list($attachsig) = sql_fetch_row($asig);
           if ($attachsig == 1) {
              $s = "checked=\"checked\"";
           } else {
              $s = "";
           }
           if (($forum_type!="6") and ($forum_type!="5")) {
         echo '	 
			<div class="form-group">
			            <div class="row">			
               <label class="control-label col-sm-4 col-md-4" for="aid">'.translate("Show signature").' :</label>
               <div class="col-sm-1 col-md-1">
                  <input class="form-control" type="checkbox" name="html" '.$s.' />
               </div>
			   </div>
            </div>
		 ';
           }
        }
        if ($allow_upload_forum) {
           if ($upload == "on") {
              $up = "checked=\"checked\"";
           }
         echo '	 
			<div class="form-group">
			            <div class="row">			
               <label class="control-label col-sm-4 col-md-4" for="aid">'.translate("Upload file after send accepted").' :</label>
               <div class="col-sm-1 col-md-1">
                  <input class="form-control" type="checkbox" name="html" '.$up.' />
               </div>
			   </div>
            </div>
		 ';
        }
     }

     echo "".Q_spambot()."";
	 
		echo'	
			<fieldset>
			<div class="btn-group-sm text-center" role="group">
				<input type="hidden" name="forum" value="'.$forum.'" />
				<input type="hidden" name="topic" value="'.$topic.'" />				
				<input class="btn btn-primary" type="submit" name="submitS" value="'.translate("Submit").'" accesskey="s" />
				<input class="btn btn-default" type="submit" name="submitP" value="'.translate("Preview").'" />
				<input class="btn btn-warning" type="reset" value="'.translate("Clear").'" />
				<input class="btn btn-danger" type="submit" name="cancel" value="'.translate("Cancel Post").'" />			
			</div>
			</fieldset>			
			';		 
	 
   } else {

     echo ''.translate("You are not allowed to reply in this forum").'';
   }
   echo "
		</div>
		</form>";
	
   if ($allow_to_reply) {

      echo '<h3 class="text-center">'.translate("Topic Review").'</h3>';
 	  
      if ($Mmod) {
         $post_aff="";
      } else {
         $post_aff=" and post_aff='1' ";
      }
      $sql = "SELECT * FROM ".$NPDS_Prefix."posts WHERE topic_id='$topic' and forum_id='$forum'".$post_aff."ORDER BY post_id DESC limit 0,10";
      if (!$result = sql_query($sql))
         forumerror('0001');
      $myrow = sql_fetch_assoc($result);
      $count=0;
      do {		  
echo '	  
<div class="row">
<div class="col-md-2">
';		  
         $posterdata = get_userdata_from_id($myrow['poster_id']);
         if ($posterdata['uname']!=$anonymous) {
            echo '<a href="powerpack.php?op=instant_message&amp;to_userid='.$posterdata['uname'].'" class="noir">'.$posterdata['uname'].'</a>';
         } else {
            echo $posterdata['uname'];
         }
         echo '<br />';
         $posts = $posterdata['posts'];
         echo member_qualif($posterdata['uname'], $posts, $posterdata['rank']);
         echo '<br /><br />';
         if ($smilies) {
            if ($posterdata['user_avatar'] != '') {
               if (stristr($posterdata['user_avatar'],"users_private")) {
                  $imgtmp=$posterdata['user_avatar'];
               } else {
                  if ($ibid=theme_image("forum/avatar/".$posterdata['user_avatar'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/avatar/".$posterdata['user_avatar'];}
               }
               echo '<img class="img-responsive" src="'.$imgtmp.'" alt="'.$posterdata['uname'].'" />';
            }
         }
echo '
</div>
<div class="col-md-10">
';
         if ($myrow['image'] != "") {
            if ($ibid=theme_image("forum/subject/".$myrow['image'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/subject/".$myrow['image'];}
            echo '<img src="'.$imgtmp.'" alt="" />';
         } else {
            if ($ibid=theme_image("forum/subject/icons/posticon.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/icons/posticon.gif";}
            echo '<img src="'.$imgtmp.'" border="0" alt="" />';
         }
         echo "&nbsp;".translate("Posted: ").convertdate($myrow['post_time']);
         echo '<br /><br />';
         $message = stripslashes($myrow['post_text']);
         if (($allow_bbcode) and ($forum_type!=6) and ($forum_type!=5)) {
            $message = smilie($message);
            $message = aff_video_yt($message);
         }
         // <a href in the message
         if (stristr($message,"<a href")) {
            $message=preg_replace('#_blank(")#i','_blank\1 class=\1noir\1',$message);
         }
         $message=split_string_without_space($message, 80);
         if (($forum_type=="6") or ($forum_type=="5")) {
            highlight_string(stripslashes($myrow['post_text']))."<br /><br />";
         } else {
            $message = str_replace("[addsig]", "<br /><br />" . nl2br($posterdata['user_sig']), $message);
            echo $message;
         }		 
echo '
</div>
<div class="col-md-12">
<hr />
</div>
';
echo '
</div>
';
         $count++;
      } while($myrow = sql_fetch_assoc($result));  
   }
}
include('footer.php');
?>
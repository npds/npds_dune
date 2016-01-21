<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x and PhpBB integration source code               */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2015 by Philippe Brunier   */
/* Great mods by snipe                                                  */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
settype($cancel, "string");
if ($cancel) {
   header("Location: viewforum.php?forum=$forum");
}

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

$rowQ1=Q_Select ("SELECT forum_name, forum_moderator, forum_type, forum_pass, forum_access, arbre FROM ".$NPDS_Prefix."forums WHERE forum_id = '$forum'", 3600);
if (!$rowQ1)
   forumerror('0001');

list(,$myrow) = each($rowQ1);
$forum_name = $myrow['forum_name'];
$forum_access = $myrow['forum_access'];
$moderator = get_moderator($myrow['forum_moderator']);
$moderator=explode(' ',$moderator);
$moderatorX = get_moderator($myrow['forum_moderator']);


if (isset($user)) {
   $userX = base64_decode($user);
   $userdata = explode(':', $userX);
   $Mmod=false;
   for ($i = 0; $i < count($moderator); $i++) {
       if (($userdata[1]==$moderator[$i])) { $Mmod=true; break;}
   }
   $userdata = get_userdata($userdata[1]);
}

if ( ($myrow['forum_type'] == 1) and ($Forum_passwd != $myrow['forum_pass']) ) {
   header("Location: forum.php");
}
if ($forum_access== 9) {
   header("Location: forum.php");
}
if (!does_exists($forum, "forum")) {
   forumerror('0030');
}
// Forum ARBRE
if ($myrow['arbre'])
   $hrefX="viewtopicH.php";
else
   $hrefX="viewtopic.php";

settype($submitS,'string');
settype($stop,'integer');
if ($submitS) {
   if ($message == '')  {
      $stop=1;
   }
   if ($subject == '') {
      $stop=1;
   }
   if (!isset($user)) {
      if ($forum_access == 0) {
         $userdata = array("uid" => 1);
         $modo='';
         include('header.php');
      } else {
         if (($username=="") or ($password=="")) {
            forumerror('0027');
         } else {
            $result = sql_query("SELECT pass FROM ".$NPDS_Prefix."users WHERE uname='$username'");
            list($pass) = sql_fetch_row($result);
            if (!$system) {
               $passwd=crypt($password,$pass);
            } else {
              $passwd=$password;
            }
            if ((strcmp($passwd,$pass)==0) and ($pass != '')) {
               $userdata = get_userdata($username);
               include('header.php');
            } else {
               forumerror('0028');
            }
         }
      }
   } else {
      $modo=user_is_moderator($userdata['uid'],$userdata['uname'],$forum_access);
      include('header.php');
   }
   // Either valid user/pass, or valid session. continue with post.
   if ($stop != 1) {
      $poster_ip =  getip();
      if ($dns_verif)
         $hostname=@gethostbyaddr($poster_ip);
      else
         $hostname='';

     // anti flood
      anti_flood ($modo, $anti_flood, $poster_ip, $userdata, $gmt);

      //anti_spambot
      if (!R_spambot($asb_question, $asb_reponse, $message)) {
         Ecr_Log("security", "Forum Anti-Spam : forum=".$forum." / topic_title=".$subject, "");
         redirect_url("index.php");
         die();
      }

      if ($myrow['forum_type']==8) {
         $formulaire=$myrow['forum_pass'];
         include ("modules/sform/forum/forum_extender.php");
      }
      if ($allow_html == 0 || isset($html))
         $message = htmlspecialchars($message,ENT_COMPAT|ENT_HTML401,cur_charset);
      if (isset($sig) && $userdata['uid'] != 1 && $myrow['forum_type']!=6 && $myrow['forum_type']!=5) {
         $message .= " [addsig]";
      }
      if (($myrow['forum_type']!=6) and ($myrow['forum_type']!=5)) {
         $message = aff_code($message);
         $message = str_replace("\n", "<br />", $message);
      }
      if (($allow_bbcode) and ($myrow['forum_type']!=6) and ($myrow['forum_type']!=5)) {
         $message = smile($message);
      }
      if (($myrow['forum_type']!=6) and ($myrow['forum_type']!=5)) {
         $message = make_clickable($message);
         $message = removeHack($message);
      }
      $message = addslashes($message);
      if (!isset($Mmod)) {
         $subject = removeHack(strip_tags($subject));
      }

      $Msubject = $subject;
      $time = date("Y-m-d H:i",time()+($gmt*3600));
      $sql = "INSERT INTO ".$NPDS_Prefix."forumtopics (topic_title, topic_poster, current_poster, forum_id, topic_time, topic_notify) VALUES ('$subject', '".$userdata['uid']."', '".$userdata['uid']."', '$forum', '$time'";
      if (isset($notify2) && $userdata['uid'] != 1) {
         $sql .= ", '1'";
      } else {
         $sql .= ", '0'";
      }
      $sql .= ')';
      if(!$result = sql_query($sql)) {
         forumerror('0020');
      }
      $topic_id = sql_last_id();
      $sql = "INSERT INTO ".$NPDS_Prefix."posts (topic_id, image, forum_id, poster_id, post_text, post_time, poster_ip, poster_dns) VALUES ('$topic_id', '$image_subject', '$forum', '".$userdata['uid']."', '$message', '$time', '$poster_ip', '$hostname')";
      if (!$result = sql_query($sql)) {
         forumerror('0020');
      } else {
         $IdPost=sql_last_id();
      }
      $sql = "UPDATE ".$NPDS_Prefix."users_status SET posts=posts+1 WHERE (uid='".$userdata['uid']."')";
      $result = sql_query($sql);
      if (!$result) {
         forumerror('0029');
      }
      $topic = $topic_id;
      global $subscribe;
      if ($subscribe) {
         subscribe_mail("forum",$topic,stripslashes($forum),stripslashes($Msubject),$userdata['uid']);
      }
      if (isset($upload)) {
         include("modules/upload/upload_forum.php");
         win_upload("forum_npds",$IdPost,$forum,$topic,"win");
      }
      redirect_url($hrefX."?forum=$forum&topic=$topic");
   } else {
      echo '
      <div class="alert alert-danger lead" role="alert">
         <i class="fa fa-exclamation-triangle fa-lg"></i>&nbsp;
         '.translate("You must provide subject and message to post your topic.").'
      </div>';
   }
} else {
   include('header.php');
   if ($allow_bbcode) include("lib/formhelp.java.php");
   echo '
   <p class="lead">
      <a href="forum.php" >'.translate("Forum Index").'</a>&nbsp;&raquo;&raquo;&nbsp;<a href="viewforum.php?forum='.$forum.'">'.stripslashes($forum_name).'</a>
   </p>
      <div class="card">
         <div class="card-block-small">
         '.translate("Moderated By: ");
   $moderator_data=explode(' ',$moderatorX);
   for ($i = 0; $i < count($moderator_data); $i++) {
      $modera = get_userdata($moderator_data[$i]);
      if ($modera['user_avatar'] != '') {
         if (stristr($modera['user_avatar'],"users_private")) {
            $imgtmp=$modera['user_avatar'];
         } else {
            if ($ibid=theme_image("forum/avatar/".$modera['user_avatar'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/avatar/".$modera['user_avatar'];}
         }
      }
            echo '<a href="user.php?op=userinfo&amp;uname='.$moderator_data[$i].'"><img width="48" height="48" class=" img-thumbnail img-fluid n-ava" src="'.$imgtmp.'" alt="'.$modera['uname'].'" title="'.$modera['uname'].'" data-toggle="tooltip" /></a>';
      }
   echo '
         </div>
      </div>';

   echo '<h4>'.translate("Post New Topic in:").' '.stripslashes($forum_name).'<span class="text-muted">&nbsp;#'.$forum.'</span></h4>';

   echo '<blockquote class="blockquote"><p>'.translate("About Posting:").'<br />';
   if ($forum_access == 0) {
      echo translate("Anonymous users can post new topics and replies in this forum.");
   } else if($forum_access == 1) {
      echo translate("All registered users can post new topics and replies to this forum.");
   } else if($forum_access == 2) {
      echo translate("Only Moderators can post new topics and replies in this forum.");
   }
   echo '</p></blockquote>';

   echo '
   <form id="new_top" class="" role="form" action="newtopic.php" method="post" name="coolsus">
   ';

   echo '<br />';
   $userX = base64_decode($user);
   $userdata = explode(':', $userX);
   if ($forum_access == 1) {
      if (!isset($user)) {
      echo '
         <fieldset>
            <div class="form-group row">
               <label class="control-label col-sm-2" for="username">'.translate("Nickname: ").'</label>
               <div class="col-sm-8 col-md-4">
                  <input class="form-control" type="text" name="username" placeholder="'.translate("Nickname").'" required="required" value="'.$username.'" />
               </div>
            </div>
            <div class="form-group row">
               <label class="control-label col-sm-2" for="password">'.translate("Password: ").'</label>
               <div class="col-sm-8">
                  <input class="form-control" type="password" name="password" placeholder="'.translate("Password").'" required="required" value="'.$password.'" />
               </div>
            </div>
         </fieldset>';
         $allow_to_post = 1;
      } else {
         echo '<strong>'.translate("Author").' :</strong>';
         echo $userdata[1];
         $allow_to_post = 1;
      }
   } elseif ($forum_access==2) {
         if (user_is_moderator($userdata[0],$userdata[2],$forum_access)) {
            echo '<strong>'.translate("Author").' :</strong>';
            echo $userdata[1];
            $allow_to_post = 1;
         }
   } elseif ($forum_access == 0) {
      $allow_to_post = 1;
   }
   settype($submitP,'string');
   if ($allow_to_post) {
      if ($submitP) {
         $acc = 'newtopic';
         $subject=stripslashes($subject);
         $message=stripslashes($message);
         if (isset($username))
            $username=stripslashes($username);
         else
            $username='';
         if (isset($password))
            $password=stripslashes($password);
         else
            $password='';
         include ("preview.php");
      } else {
        $username='';
        $password='';
        $subject='';
        $message='';
      }
      echo '';
      if ($myrow['forum_type']==8) {
         $formulaire=$myrow['forum_pass'];
         include ("modules/sform/forum/forum_extender.php");
      } else {
      echo ' 
         <div class="form-group row">
            <div class="col-sm-3">
               <label class="form-control-label" for="subject">'.translate("Subject").'</label>
            </div>
            <div class="col-sm-9">
               <input class="form-control" type="text" name="subject" placeholder="'.translate("Subject").'" required="required" value="'.$subject.'" />
            </div>
         </div>';
         if ($smilies) {
         echo '
            <div class="form-group row">
               <div class="col-sm-3">
                  <label class="form-control-label">'.translate("Message Icon: ").'</label>
               </div>
               <div class="col-sm-9">';
               settype($image_subject,'string');
               echo emotion_add($image_subject);
         echo '
               </div>
            </div>';
         }
        echo ' 
            <div class="form-group row">
               <div class="col-sm-3">
                  <label class="form-control-label" for="message">'.translate("Message: ").'</label>
               </div>';
         if ($allow_bbcode)
            $xJava = 'name="message" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" onfocus="storeForm(this)"';
   echo '
         <div class="col-sm-9">
            <div class="card">
               <div class="card-header">';
   if ($allow_html==1) {
      echo '<span class="text-success pull-right" title="HTML '.translate("On").'" data-toggle="tooltip"><i class="fa fa-code fa-lg"></i></span>'.HTML_Add();
   } else
      echo '<span class="text-danger pull-right" title="HTML '.translate("Off").'" data-toggle="tooltip"><i class="fa fa-code fa-lg"></i></span>';
         echo '
               </div>
               <div class="card-block">
                  <textarea class="form-control" '.$xJava.' name="message" rows="12">'.$message.'</textarea>
               </div>
               <div class="card-footer text-muted">';
                 if ($allow_bbcode) putitems();
         echo '
               </div>
            </div>
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-3">
            <label class="form-control-label">'.translate("Options: ").'</label>
         </div>
         <div class="col-sm-9">';

         if (($allow_html==1) and ($myrow['forum_type']!=6) and ($myrow['forum_type']!=5)) {
         if (isset($html)) {
            $sethtml = 'checked="checked"';
         } else {
            $sethtml = '';
         }
         echo '
            <div class="checkbox">
               <label>
                  <input class="" type="checkbox" name="html" '.$sethtml.' />&nbsp;'.translate("Disable HTML on this Post").'
               </label>
            </div>';
         }
         if ($user) {
            if ($allow_sig == 1||$sig == "on") {
               $asig = sql_query("select attachsig from ".$NPDS_Prefix."users_status where uid='$cookie[0]'");
               list($attachsig) = sql_fetch_row($asig);
               if ($attachsig == 1) {
                  $s = 'checked="checked"';
               } else {
                  $s = '';
               }
               if (($myrow['forum_type']!=6) and ($myrow['forum_type']!=5)) {
         echo '
            <div class="checkbox">
               <label>
                  <input class="" type="checkbox" name="html" '.$s.' />&nbsp;'.translate("Show signature").'
               </label>
            </div>';
               }
            }
            if ($allow_upload_forum) {
               if ($upload == "on") {
                  $up = 'checked="checked"';
               }
         echo '
            <div class="checkbox">
               <label>
                  <input class="" type="checkbox" name="html" '.$up.' />&nbsp;'.translate("Upload file after send accepted").'
               </label>
            </div>';
            }
            if (isset($notify2)) {
               $selnot='checked="checked"';
            } else {
               $selnot='';
            }
         echo '
            <div class="checkbox">
               <label>
                  <input class="" type="checkbox" name="html" '.$selnot.' />&nbsp;'.translate("Notify by email when replies are posted").'
               </label>
            </div>';
         }
         echo '
         </div>
      </div>';

   echo ''.Q_spambot().'';

   echo'
      <fieldset>
      <div class="btn-group-sm text-xs-center" role="group">
         <input type="hidden" name="forum" value="'.$forum.'" />
         <input class="btn btn-primary" type="submit" name="submitS" value="'.translate("Submit").'" accesskey="s" />
         <input class="btn btn-secondary" type="submit" name="submitP" value="'.translate("Preview").'" />
         <input class="btn btn-warning" type="reset" value="'.translate("Clear").'" />
         <input class="btn btn-danger" type="submit" name="cancel" value="'.translate("Cancel Post").'" />
      </div>
      </fieldset>';
      }
   }
   echo '
      </form>';
}
include('footer.php');
?>
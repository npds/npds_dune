<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x and PhpBB integration source code               */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2024 by Philippe Brunier   */
/* Great mods by snipe                                                  */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
settype($cancel, 'string');
if ($cancel)
   header("Location: viewforum.php?forum=$forum");
if (!function_exists("Mysql_Connexion"))
   include ("mainfile.php");
include('functions.php');
$cache_obj = ($SuperCache) ? new cacheManager() : new SuperCacheEmpty() ;
include('auth.php');
global $NPDS_Prefix;

$rowQ1=Q_Select ("SELECT forum_name, forum_moderator, forum_type, forum_pass, forum_access, arbre FROM ".$NPDS_Prefix."forums WHERE forum_id = '$forum'", 3600);
if (!$rowQ1)
   forumerror('0001');
$myrow=$rowQ1[0];
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

if ( ($myrow['forum_type'] == 1) and ($Forum_passwd != $myrow['forum_pass']) )
   header("Location: forum.php");
if ($forum_access== 9)
   header("Location: forum.php");
if (!does_exists($forum, "forum"))
   forumerror('0030');
// Forum ARBRE
$hrefX = ($myrow['arbre']) ? 'viewtopicH.php' : 'viewtopic.php' ;

settype($stop,'integer');
if (isset($submitS)) {
   if ($message == '')
      $stop=1;
   if ($subject == '')
      $stop=1;
   if (!isset($user)) {
      if ($forum_access == 0) {
         $userdata = array("uid" => 1);
         $modo='';
         include('header.php');
      } else {
         if (($username=='') or ($password==''))
            forumerror('0027');
         else {
            $modo ='';
            $result = sql_query("SELECT pass FROM ".$NPDS_Prefix."users WHERE uname='$username'");
            list($pass) = sql_fetch_row($result);
            if ((password_verify($password, $pass)) and ($pass != '')) {
               $userdata = get_userdata($username);
               include('header.php');
            }
            else
               forumerror('0028');
         }
      }
   } else {
      $modo=user_is_moderator($userdata['uid'],$userdata['uname'],$forum_access);
      include('header.php');
   }
   // Either valid user/pass, or valid session. continue with post.
   if ($stop != 1) {
      $poster_ip = getip();
      $hostname = ($dns_verif) ? gethostbyaddr($poster_ip) : '' ;
     // anti flood
      anti_flood ($modo, $anti_flood, $poster_ip, $userdata, $gmt);
      //anti_spambot
      if (!R_spambot($asb_question, $asb_reponse, $message)) {
         Ecr_Log('security', 'Forum Anti-Spam : forum='.$forum.' / topic_title='.$subject, '');
         redirect_url("index.php");
         die();
      }

      if ($myrow['forum_type']==8) {
         $formulaire=$myrow['forum_pass'];
         include ("modules/sform/forum/forum_extender.php");
      }
/*
      if ($allow_html == 0 || isset($html))
         $message = htmlspecialchars($message,ENT_COMPAT|ENT_HTML401,'UTF-8');
*/
      if (isset($sig) && $userdata['uid'] != 1 && $myrow['forum_type']!=6 && $myrow['forum_type']!=5)
         $message .= " [addsig]";
      if (($myrow['forum_type']!=6) and ($myrow['forum_type']!=5)) {
//         $message = af_cod($message);
      }
      if (($allow_bbcode) and ($myrow['forum_type']!=6) and ($myrow['forum_type']!=5))
         $message = smile($message);
      if (($myrow['forum_type']!=6) and ($myrow['forum_type']!=5)) {
         $message = make_clickable($message);
         $message = removeHack($message);
      }
      $message = addslashes($message);
      if (!isset($Mmod))
         $subject = removeHack(strip_tags($subject));

      $Msubject = $subject;
      $time = date("Y-m-d H:i",time()+((integer)$gmt*3600));
      $sql = "INSERT INTO ".$NPDS_Prefix."forumtopics (topic_title, topic_poster, current_poster, forum_id, topic_time, topic_notify) VALUES ('$subject', '".$userdata['uid']."', '".$userdata['uid']."', '$forum', '$time'";
      $sql .= (isset($notify2) && $userdata['uid'] != 1) ? ", '1'" : ", '0'" ;
      $sql .= ')';
      if(!$result = sql_query($sql))
         forumerror('0020');
      $topic_id = sql_last_id();
      $image_subject = isset($image_subject) ? $image_subject : '00.png' ;
      $sql = "INSERT INTO ".$NPDS_Prefix."posts (topic_id, image, forum_id, poster_id, post_text, post_time, poster_ip, poster_dns) VALUES ('$topic_id', '$image_subject', '$forum', '".$userdata['uid']."', '$message', '$time', '$poster_ip', '$hostname')";
      if (!$result = sql_query($sql))
         forumerror('0020');
      else
         $IdPost=sql_last_id();

      $sql = "UPDATE ".$NPDS_Prefix."users_status SET posts=posts+1 WHERE (uid='".$userdata['uid']."')";
      $result = sql_query($sql);
      if (!$result)
         forumerror('0029');
      $topic = $topic_id;
      global $subscribe;
      if ($subscribe)
         subscribe_mail("forum",$topic,stripslashes($forum),stripslashes($Msubject),$userdata['uid']);
      if (isset($upload)) {
         include("modules/upload/upload_forum.php");
         win_upload("forum_npds",$IdPost,$forum,$topic,"win");
      }
      redirect_url($hrefX."?forum=$forum&topic=$topic");
   } else {
      echo '
      <div class="alert alert-danger lead" role="alert">
         <i class="fa fa-exclamation-triangle fa-lg"></i>&nbsp;
         '.translate("Vous devez choisir un titre et un message pour poster votre sujet.").'
      </div>';
   }
} else {
   include('header.php');
   if ($allow_bbcode) include("lib/formhelp.java.php");
   $userX = base64_decode($user);
   $userdata = explode(':', $userX);
   $posterdata = get_userdata_from_id($userdata[0]);
   if ($smilies) {
      if(isset($user)) {
         if ($posterdata['user_avatar'] != '') {
            if (stristr($posterdata['user_avatar'],"users_private"))
               $imgava=$posterdata['user_avatar'];
            else
               if ($ibid=theme_image("forum/avatar/".$posterdata['user_avatar'])) {$imgava=$ibid;} else {$imgava="images/forum/avatar/".$posterdata['user_avatar'];}
         }
      }
      else
         if ($ibid=theme_image("forum/avatar/blank.gif")) {$imgava=$ibid;} else {$imgava="images/forum/avatar/blank.gif";}
   }

   echo '
   <p class="lead">
      <a href="forum.php" >'.translate("Index du forum").'</a>&nbsp;&raquo;&raquo;&nbsp;<a href="viewforum.php?forum='.$forum.'">'.stripslashes($forum_name).'</a>
   </p>
      <div class="card">
         <div class="card-block-small">
         '.translate("Modéré par : ");
   $moderator_data=explode(' ',$moderatorX);
   for ($i = 0; $i < count($moderator_data); $i++) {
      $modera = get_userdata($moderator_data[$i]);
      if ($modera['user_avatar'] != '') {
         if (stristr($modera['user_avatar'],"users_private"))
            $imgtmp=$modera['user_avatar'];
         else
            if ($ibid=theme_image("forum/avatar/".$modera['user_avatar'])) $imgtmp=$ibid; else $imgtmp="images/forum/avatar/".$modera['user_avatar'];
      }
      echo '<a href="user.php?op=userinfo&amp;uname='.$moderator_data[$i].'"><img width="48" height="48" class=" img-thumbnail img-fluid n-ava me-1 mx-1" src="'.$imgtmp.'" alt="'.$modera['uname'].'" title="'.$modera['uname'].'" data-bs-toggle="tooltip" loading="lazy" /></a>';
   }
   echo '
         </div>
      </div>
      <h4 class="my-3"><img width="48" height="48" class=" rounded-circle me-3" src="'.$imgava.'" alt="" />'.translate("Poster un nouveau sujet dans :").' '.stripslashes($forum_name).'<span class="text-body-secondary">&nbsp;#'.$forum.'</span></h4>
         <blockquote class="blockquote">'.translate("A propos des messages publiés :").'<br />';
   if ($forum_access == 0)
      echo translate("Les utilisateurs anonymes peuvent poster de nouveaux sujets et des réponses dans ce forum.");
   else if($forum_access == 1)
      echo translate("Tous les utilisateurs enregistrés peuvent poster de nouveaux sujets et répondre dans ce forum.");
   else if($forum_access == 2)
      echo translate("Seuls les modérateurs peuvent poster de nouveaux sujets et répondre dans ce forum.");
   echo '
      </blockquote>
      <form id="new_top" action="newtopic.php" method="post" name="coolsus">';

   echo '<br />';
   
   if ($forum_access == 1) {
      if (!isset($user)) {
         echo '
         <fieldset>
            <div class="mb-3 row">
               <label class="control-label col-sm-2" for="username">'.translate("Identifiant : ").'</label>
               <div class="col-sm-8 col-md-4">
                  <input class="form-control" type="text" id="username" name="username" placeholder="'.translate("Identifiant").'" required="required" />
               </div>
            </div>
            <div class="mb-3 row">
               <label class="control-label col-sm-2" for="password">'.translate("Mot de passe : ").'</label>
               <div class="col-sm-8">
                  <input class="form-control" type="password" id="password" name="password" placeholder="'.translate("Mot de passe").'" required="required" />
               </div>
            </div>
         </fieldset>';
         $allow_to_post = 1;
      } else
         $allow_to_post = 1;
   }
   elseif ($forum_access==2) {
      if (user_is_moderator($userdata[0],$userdata[2],$forum_access)) {
         echo '<strong>'.translate("Auteur").' :</strong>';
         echo $userdata[1];
         $allow_to_post = 1;
      }
   }
   elseif ($forum_access == 0)
      $allow_to_post = 1;

   settype($submitP,'string');
   if ($allow_to_post) {
      if ($submitP) {
         $acc = 'newtopic';
         $subject=stripslashes($subject);
         $message=stripslashes($message);
         $username = (isset($username)) ? stripslashes($username) : '' ;
         $password = (isset($password)) ? stripslashes($password) : '' ;
         include ("preview.php");
      } else {
        $username=''; $password=''; $subject=''; $message='';
      }
      if ($myrow['forum_type']==8) {
         $formulaire=$myrow['forum_pass'];
         include ("modules/sform/forum/forum_extender.php");
      } else {
         echo ' 
         <div class="mb-3 row">
            <label class="form-label" for="subject">'.translate("Sujet").'</label>
            <div class="col-sm-12">
               <input class="form-control" type="text" id="subject" name="subject" placeholder="'.translate("Sujet").'" required="required" value="'.$subject.'" />
            </div>
         </div>';
         if ($smilies) {
            settype($image_subject,'string');
            echo '
         <div class="d-none d-sm-block mb-3 row">
            <label class="form-label">'.translate("Icone du message").'</label>
            <div class="col-sm-12">
               <div class="border rounded pt-3 px-2 n-fond_subject d-flex flex-row flex-wrap">
               '.emotion_add($image_subject).'
               </div>
            </div>
         </div>';
         }
         echo ' 
         <div class="mb-3 row">
            <label class="form-label" for="message">'.translate("Message").'</label>';
         if ($allow_bbcode)
            $xJava = 'name="message" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" onfocus="storeForm(this)"';
         echo '
         <div class="col-sm-12">
            <div class="card">
               <div class="card-header">
                  <div class="float-start">';
   putitems('ta_newtopic');
      echo '
                  </div>';
   if ($allow_html==1)
      echo '<span class="text-success float-end mt-2" title="HTML '.translate("On").'" data-bs-toggle="tooltip"><i class="fa fa-code fa-lg"></i></span>'.HTML_Add();
   else
      echo '<span class="text-danger float-end mt-2" title="HTML '.translate("Off").'" data-bs-toggle="tooltip"><i class="fa fa-code fa-lg"></i></span>';
   echo '
               </div>
               <div class="card-body">
                  <textarea id="ta_newtopic" class="form-control" '.$xJava.' name="message" rows="12">'.$message.'</textarea>
               </div>
               <div class="card-footer p-0">
                  <span class="d-block">
                     <button class="btn btn-link" type="submit" value="'.translate("Prévisualiser").'" name="submitP" title="'.translate("Prévisualiser").'" data-bs-toggle="tooltip" ><i class="fa fa-eye fa-lg"></i></button>
                  </span>
               </div>
            </div>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="form-label">'.translate("Options").'</label>
         <div class="col-sm-12">
            <div class="custom-controls-stacked">';
         if (($allow_html==1) and ($myrow['forum_type']!=6) and ($myrow['forum_type']!=5)) {
            $sethtml = (isset($html)) ? 'checked="checked"' : '' ;
            echo '
               <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="html" name="html" '.$sethtml.' />
                  <label class="form-check-label" for="html">'.translate("Désactiver le html pour cet envoi").'</label>
               </div>';
         }
         if ($user) {
            if ($allow_sig == 1 || $sig == 'on') {
               $asig = sql_query("SELECT attachsig FROM ".$NPDS_Prefix."users_status WHERE uid='$cookie[0]'");
               list($attachsig) = sql_fetch_row($asig);
               $s = ($attachsig == 1) ? 'checked="checked"' : '' ;
               if (($myrow['forum_type']!=6) and ($myrow['forum_type']!=5)) {
                  echo '
               <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="sig" name="sig" '.$s.' />
                  <label class="form-check-label" for="sig">'.translate("Afficher la signature").'</label>
               </div>';
               }
            }
            settype($up,'string');
            settype($upload,'string');
            if ($allow_upload_forum) {
               if ($upload == "on")
                  $up = 'checked="checked"';
               echo '
               <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="upload" name="upload" '.$up.' />
                  <label class="form-check-label" for="upload">'.translate("Charger un fichier une fois l'envoi accepté").'</label>
               </div>';
            }
            $selnot = isset($notify2) ? 'checked="checked"' : '' ;
            echo '
               <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="notify2" name="notify2" '.$selnot.' />
                  <label class="form-check-label" for="notify2">'.translate("Prévenir par Email quand de nouvelles réponses sont postées").'</label>
               </div>';
         }
         echo '
            </div>
         </div>
      </div>
      '.Q_spambot().'
      <div class="mb-3 row">
         <div class="col-sm-12">
            <input type="hidden" name="forum" value="'.$forum.'" />
            <input class="btn btn-primary" type="submit" name="submitS" value="'.translate("Valider").'" accesskey="s" />
            <input class="btn btn-danger" type="submit" name="cancel" value="'.translate("Annuler la contribution").'" />
         </div>
      </div>';
      }
   }
   echo '
      </form>';
}
include('footer.php');
?>

<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/* Based on Parts of phpBB                                              */
/*                                                                      */
/* NPDS Copyright (c) 2002-2025 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion"))
   die();

include('functions.php');
include('auth.php');

filtre_module($file_name);
if (file_exists("modules/comments/$file_name.conf.php"))
   include ("modules/comments/$file_name.conf.php");
else
   die();
if (isset($cancel))
   header("Location: $url_ret");

settype($forum,'integer');
if ($forum>=0)
   die();

// gestion des params du 'forum' : type, accès, modérateur ...
$forum_name = 'comments';
$forum_type=0;
$allow_to_post=false;
$forum_access = $anonpost ? 0 : 1;
global $user;
if ($moderate==1 and isset($admin))
   $Mmod=true;
elseif ($moderate==2) {
   $userX=base64_decode($user);
   $userdata=explode(':', $userX);
   $result=sql_query("SELECT level FROM ".$NPDS_Prefix."users_status WHERE uid='".$userdata[0]."'");
   list($level)=sql_fetch_row($result);
   $Mmod = $level>=2 ? true : false;
} else
   $Mmod=false;
// gestion des params du 'forum' : type, accès, modérateur ...

if (isset($submitS)) {
   $stop=0;
   if ($message=='') $stop=1;
   if (!$user) {
      if ($forum_access==0) {
         $userdata = array('uid' => 1);
         include('header.php');
      } else {
         if (($username=='') or ($password==''))
            forumerror('0027');
         else {
            $result = sql_query("SELECT pass FROM ".$NPDS_Prefix."users WHERE uname='$username'");
            list($pass) = sql_fetch_row($result);
            $passwd = (!$system) ? crypt($password,$pass) : $password ;
            if ((strcmp($passwd,$pass)==0) and ($pass != '')) {
               $userdata = get_userdata($username);
               include('header.php');
            } else
               forumerror('0028');
         }
      }
   } else {
      $userX = base64_decode($user);
      $userdata = explode(':', $userX);
      $userdata = get_userdata($userdata[1]);
      include("header.php");
   }

   // Either valid user/pass, or valid session. continue with post.
   if ($stop != 1) {
      $poster_ip =  getip();
      $hostname = $dns_verif ? gethostbyaddr($poster_ip) : $poster_ip ;
      // anti flood
      anti_flood ($Mmod, $anti_flood, $poster_ip, $userdata, $gmt);
      //anti_spambot
      if (isset($asb_question) and isset($asb_reponse)) {
         if (!R_spambot($asb_question, $asb_reponse, $message)) {
            Ecr_Log('security', "Forum Anti-Spam : forum=".$forum." / topic=".$topic, '');
            redirect_url("$url_ret");
            die();
         }
      }

      if ($formulaire!='')
         include ("modules/comments/comments_extender.php");

      if ($allow_html == 0 || isset($html)) $message = htmlspecialchars($message,ENT_COMPAT|ENT_HTML401,'UTF-8');
      if (isset($sig) && $userdata['uid'] != 1) $message .= ' [addsig]';
      $message=af_cod($message);
      $message = smile($message);
      $message = make_clickable($message);
      $message = removeHack($message);
      $image_subject='';
      $message = addslashes(dataimagetofileurl($message,'modules/upload/upload/co'));
      $time = date("Y-m-d H:i:s",time()+((integer)$gmt*3600));
      $sql = "INSERT INTO ".$NPDS_Prefix."posts (post_idH, topic_id, image, forum_id, poster_id, post_text, post_time, poster_ip, poster_dns) VALUES ('0', '$topic', '$image_subject', '$forum', '".$userdata['uid']."', '$message', '$time', '$poster_ip', '$hostname')";
      if (!$result = sql_query($sql))
         forumerror('0020');
      else
         $IdPost=sql_last_id();

      $sql = "UPDATE ".$NPDS_Prefix."users_status SET posts=posts+1 WHERE (uid = '".$userdata['uid']."')";
      $result = sql_query($sql);
      if (!$result)
         forumerror('0029');
      // ordre de mise à jour d'un champ externe ?
      if ($comments_req_add!='')
         sql_query("UPDATE ".$NPDS_Prefix.$comments_req_add);
      // envoi mail alerte
      if ($notify) {
         global $notify_email, $nuke_url, $notify_from, $url_ret;
         $csubject = html_entity_decode(translate("Nouveau commentaire"),ENT_COMPAT | ENT_HTML401,'UTF-8').' ==> '. $nuke_url;
         $cmessage = '🔔 '.translate("Nouveau commentaire").' ==> <a href="'.$nuke_url.'/'.$url_ret.'">'.$nuke_url.'/'.$url_ret.'</a>';
         send_email($notify_email, $csubject, $cmessage, $notify_from , false, "html",'');
      }
      redirect_url("$url_ret");
   } else {
      echo '
   <h2><i class="far fa-comment text-body-secondary fa-lg me-2"></i>'.translate("Commentaire").'</h2>
   <hr />
   <div class="alert alert-danger" >'.translate("Vous devez taper un message à poster.").'</div>
   <p><a href="javascript:history.go(-1)" class="btn btn-primary">'.translate("Retour en arrière").'</a></p>';
   }
} else {
   include('header.php');
   if ($allow_bbcode==1)
      include("lib/formhelp.java.php");
   echo '
   <h2><i class="far fa-comment text-body-secondary fa-lg me-2"></i>'.translate("Commentaire").'</h2>
   <hr />';
   if ($formulaire=='')
      echo '
   <form action="modules.php" method="post" name="coolsus">';
   echo '<div class="mb-3 ">';
   $allow_to_reply=false;
   if ($forum_access==0)
      $allow_to_reply=true;
   else
      if (isset($user))
         $allow_to_reply=true;
   if ($allow_to_reply) {
      if (isset($submitP)) {
         $time=formatTimes(time(), IntlDateFormatter::MEDIUM, IntlDateFormatter::SHORT);
         if(isset($user)){
            $userY = base64_decode($user);
            $userdata = explode(':', $userY);
            $userdata = get_userdata($userdata[1]);
         } else {
            $userdata = array('uid' => 1);
            $userdata = get_userdata($userdata['uid']);
         }
         $theposterdata = get_userdata_from_id($userdata['uid']);
         $messageP=$message;
         $messageP=af_cod($messageP);
         echo '
      <h4>'.translate("Prévisualiser").'</h4>
      <div class="row">
         <div class="col-12">
            <div class="card">
               <div class="card-header">';
         if ($smilies) {
            if ($theposterdata['user_avatar'] != '') {
               if (stristr($theposterdata['user_avatar'],"users_private"))
                  $imgtmp=$theposterdata['user_avatar'];
               else {
                  if ($ibid=theme_image("forum/avatar/".$theposterdata['user_avatar'])) $imgtmp=$ibid; else $imgtmp="images/forum/avatar/".$theposterdata['user_avatar'];
               }
               echo '
                   <a style="position:absolute; top:1rem;" tabindex="0" data-bs-toggle="popover" data-bs-html="true" data-bs-title="'.$theposterdata['uname'].'" data-bs-content=\''.member_qualif($theposterdata['uname'], $theposterdata['posts'],$theposterdata['rang']).'\'><img class=" btn-secondary img-thumbnail img-fluid n-ava" src="'.$imgtmp.'" alt="'.$theposterdata['uname'].'" /></a>';
            }
         }
         echo'
                  &nbsp;<span style="position:absolute; left:6rem;" class="text-body-secondary"><strong>'.$theposterdata['uname'].'</strong></span>
      </div>
      <div class="card-body">
         <span class="text-body-secondary float-end small" style="margin-top:-1rem;">'.translate("Commentaires postés : ").$time.'</span>
         <div id="post_preview" class="card-text pt-3">';
      $messageP=stripslashes($messageP);
      if (($forum_type=='6') or ($forum_type=='5'))
         highlight_string(stripslashes($messageP));
      else {
         if ($allow_bbcode) $messageP=smilie($messageP);
         if ($allow_sig == 1 and isset($sig))
            $messageP .= '<div class="n-signature">'.nl2br($theposterdata['user_sig']).'</div>';
         echo $messageP.'
         </div>';
      }
      echo '
            </div>
         </div>
      </div>
   </div>';
      }
     else
        $message='';
        
      if ($formulaire!='') {
         echo'<div class="col">';
         include ("modules/comments/comments_extender.php");
         echo'</div></div>';
      } else {
         if ($allow_bbcode)
            $xJava = 'name="message" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" onfocus="storeForm(this)"';
         if (isset($citation) && !isset($submitP)) {
            $sql = "SELECT p.post_text, p.post_time, u.uname FROM ".$NPDS_Prefix."posts p, ".$NPDS_Prefix."users u WHERE post_id='$post' AND ((p.poster_id = u.uid) XOR (p.poster_id=0))";
            if ($r = sql_query($sql)) {
               $m = sql_fetch_assoc($r);
               $text = $m['post_text'];
               $text = smile($text);
               $text = str_replace('<br />', "\n", $text);
               $text = stripslashes($text);
               $text=desaf_cod($text);
               $reply = ($m['post_time']!='' && $m['uname']!='') ?
                  '<div class="blockquote">'.translate("Citation").' : <strong>'.$m['uname'].'</strong>'."\n".$text.'</div>' :
                  $text."\n" ;
            } else
               $reply = translate("Erreur de connexion à la base de données")."\n";
         }
         if (!isset($reply)) $reply=$message;

         echo '
      </div>
      <div class="mb-3 row">
         <label class="form-label" for="message">'.translate("Message").'</label>
         <div class="col-sm-12">
            <div class="card">
               <div class="card-header">
                  <div class="float-start">';
         putitems('ta_comment');
         echo '
                  </div>';
         echo ($allow_html == 1) ?
                  '
                  <span class="text-success float-end mt-2" title="HTML '.translate("Activé").'" data-bs-toggle="tooltip"><i class="fa fa-code fa-lg"></i></span>'.HTML_Add() :
                  '
                  <span class="text-danger float-end mt-2" title="HTML '.translate("Désactivé").'" data-bs-toggle="tooltip"><i class="fa fa-code fa-lg"></i></span>' ;
         echo '
               </div>
               <div class="card-body">
                  <textarea id="ta_comment" class="form-control" '.$xJava.' name="message" rows="12">'.stripslashes($reply).'</textarea>
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
         <label class="form-label">'.translate("Options").'</label>';
         if ($allow_html==1) {
            if (isset($html)) $sethtml = 'checked="checked"'; else $sethtml = '';
         echo '
         <div class="col-sm-12 my-2">
            <div class="checkbox">
               <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="html" name="html" '.$sethtml.' />
                  <label class="form-check-label" for="html">'.translate("Désactiver le html pour cet envoi").'</label>
               </div>
            </div>';
         }
         if ($user) {
            if ($allow_sig == 1||isset($sig)) {
               $asig = sql_query("SELECT attachsig FROM ".$NPDS_Prefix."users_status WHERE uid='$cookie[0]'");
               list($attachsig) = sql_fetch_row($asig);
               if ($attachsig == 1 or isset($sig)) $s = 'checked="checked"'; else $s='';
               echo '
            <div class="checkbox my-2">
               <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="sig" name="sig" '.$s.' />
                  <label class="form-check-label" for="sig"> '.translate("Afficher la signature").'</label>
               </div>
               <span class="help-block"><small>'.translate("Cela peut être retiré ou ajouté dans vos paramètres personnels").'</small></span>
            </div>';
            }
         }
      echo '</div>
      </div>';

      echo Q_spambot();
      echo '
      <div class="mb-3 row">
         <div class="col-sm-12">
            <input type="hidden" name="ModPath" value="comments" />
            <input type="hidden" name="ModStart" value="reply" />
            <input type="hidden" name="topic" value="'.$topic.'" />
            <input type="hidden" name="file_name" value="'.$file_name.'" />
            <input type="hidden" name="archive" value="'.$archive.'" />
            <input class="btn btn-primary" type="submit" name="submitS" value="'.translate("Valider").'" />
            <input class="btn btn-danger" type="submit" name="cancel" value="'.translate("Annuler la contribution").'" />
         </div>
      </div>';
      }
   }
   else
     echo '
     <div class="alert alert-danger">'.translate("Vous n'êtes pas autorisé à participer à ce forum").'</div>';

   if ($formulaire=='')
      echo '
      </form>';
   if ($allow_to_reply) {
      $post_aff = $Mmod ? '' : " AND post_aff='1' " ;
      $sql = "SELECT * FROM ".$NPDS_Prefix."posts WHERE topic_id='$topic'".$post_aff." AND forum_id='$forum' ORDER BY post_id DESC LIMIT 0,10";
      $result = sql_query($sql);
      if (sql_num_rows($result)) {
         echo translate("Aperçu des sujets :");
         while($myrow = sql_fetch_assoc($result)) {
            $posterdata = get_userdata_from_id($myrow['poster_id']);
            echo '
            <div class="card my-3">
               <div class="card-header">';
            if ($smilies)
               echo $posterdata['uid'] != 1 ? userpopover($posterdata['uname'],'48',2) : '<img class=" btn-outline-primary img-fluid n-ava-48 me-0" src="images/forum/avatar/blank.gif" alt="KellyP" loading="lazy">';
            echo $posterdata['uid'] != 1 ? $posterdata['uname'] : $anonymous ;
            echo '<span class="float-end text-body-secondary small">'.translate("Posté : ").formatTimes($myrow['post_time'], IntlDateFormatter::MEDIUM, IntlDateFormatter::SHORT).'</span>
               </div>
               <div class="card-body">';
            $message = stripslashes($myrow['post_text']);
            if ($allow_bbcode)
               $message = smilie($message);
            // <a href in the message
            if (stristr($message,'<a href'))
               $message=preg_replace('#_blank(")#i','_blank\1 class=\1 \1',$message);
            if($posterdata['uid'] != 1)
               $message = str_replace('[addsig]', '<div class="n-signature">'.nl2br($posterdata['user_sig']).'</div>', $message);
            echo $message.'<br />
               </div>
            </div>';
         }
      }
   }
}
include('footer.php');
?>
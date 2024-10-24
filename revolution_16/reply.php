<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x and PhpBB integration source code               */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/* Great mods by snipe                                                  */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

if (!function_exists("Mysql_Connexion"))
   include ("mainfile.php");

include('functions.php');
$cache_obj = $SuperCache ? new cacheManager() : new SuperCacheEmpty() ;
include('auth.php');
global $NPDS_Prefix;

settype($cancel,'string');
if ($cancel)
   header("Location: viewtopic.php?topic=$topic&forum=$forum");

$rowQ1=Q_Select ("SELECT forum_name, forum_moderator, forum_type, forum_pass, forum_access, arbre FROM ".$NPDS_Prefix."forums WHERE forum_id = '$forum'", 3600);
if (!$rowQ1)
   forumerror('0001');
$myrow=$rowQ1[0];
$forum_name = $myrow['forum_name'];
$forum_access = $myrow['forum_access'];
$forum_type=$myrow['forum_type'];
$mod=$myrow['forum_moderator'];

if ( ($forum_type == 1) and ($Forum_passwd != $myrow['forum_pass']) )
   header("Location: forum.php");
if ($forum_access==9)
   header("Location: forum.php");
if (is_locked($topic))
   forumerror('0025');
if (!does_exists($forum, "forum") || !does_exists($topic, "topic"))
   forumerror('0026');

settype($submitS,'string');
settype($stop,'integer');
if ($submitS) {
   if ($message=='') $stop=1;
   if (!isset($user)) {
      if ($forum_access==0) {
         $userdata = array("uid" => 1);
         $modo='';;
         include("header.php");
      } else {
         if (($username=='') or ($password==''))
            forumerror('0027');
         else {
            $result = sql_query("SELECT pass FROM ".$NPDS_Prefix."users WHERE uname='$username'");
            list($pass) = sql_fetch_row($result);

           if ((password_verify($password, $pass)) and ($pass != '')) {
              $userdata = get_userdata($username);
              if ($userdata['uid']==1)
                 forumerror('0027');
              else
                 include("header.php");
           }
           else
              forumerror('0028');

            $modo=user_is_moderator($username,$pass,$forum_access);
            if ($forum_access==2) {
               if (!$modo)
                  forumerror('0027');
            }
         }
      }
   } else {
      $userX = base64_decode($user);
      $userdata = explode(':', $userX);
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
      $hostname = $dns_verif ? gethostbyaddr($poster_ip) : '' ;
      // anti flood
      anti_flood ($modo, $anti_flood, $poster_ip, $userdata, $gmt);
      //anti_spambot
      if (!R_spambot($asb_question, $asb_reponse, $message)) {
         Ecr_Log('security', 'Forum Anti-Spam : forum='.$forum.' / topic='.$topic, '');
         redirect_url("index.php");
         die();
      }

      if ($allow_html == 0 || isset($html)) $message = htmlspecialchars($message,ENT_COMPAT|ENT_HTML401,'UTF-8');
      if (isset($sig) && $userdata['uid']!= 1) $message .= ' [addsig]';
      if (($forum_type!='6') and ($forum_type!='5')) {
//         $message = af_cod($message);
//         $message =  str_replace(array("\r\n", "\r", "\n"), "<br />", $message);
      }
      if (($allow_bbcode==1) and ($forum_type!='6') and ($forum_type!='5'))
         $message = smile($message);
      if (($forum_type!='6') and ($forum_type!='5')){
         $message = make_clickable($message);
         $message = removeHack($message);
      }
      $image_subject=removeHack($image_subject);
      $message = addslashes($message);
      $time = date("Y-m-d H:i:s",time()+((integer)$gmt*3600));

      $sql = "INSERT INTO ".$NPDS_Prefix."posts (post_idH, topic_id, image, forum_id, poster_id, post_text, post_time, poster_ip, poster_dns) VALUES ('0', '$topic', '$image_subject', '$forum', '".$userdata['uid']."', '$message', '$time', '$poster_ip', '$hostname')";
      if (!$result = sql_query($sql))
         forumerror('0020');
      else
         $IdPost=sql_last_id();

      $sql = "UPDATE ".$NPDS_Prefix."forumtopics SET topic_time = '$time', current_poster = '".$userdata['uid']."' WHERE topic_id = '$topic'";
      if (!$result = sql_query($sql))
         forumerror('0020');
      $sql = "UPDATE ".$NPDS_Prefix."forum_read SET status='0' where topicid = '$topic' and uid <> '".$userdata['uid']."'";
      if (!$r = sql_query($sql))
         forumerror('0001');

      $sql = "UPDATE ".$NPDS_Prefix."users_status SET posts=posts+1 WHERE (uid = '".$userdata['uid']."')";
      $result = sql_query($sql);
      if (!$result)
         forumerror('0029');
      $sql = "SELECT t.topic_notify, u.email, u.uname, u.uid, u.user_langue FROM ".$NPDS_Prefix."forumtopics t, ".$NPDS_Prefix."users u WHERE t.topic_id = '$topic' AND t.topic_poster = u.uid";
      if (!$result = sql_query($sql))
         forumerror('0022');

      $m = sql_fetch_assoc($result);
      $sauf = '';
      if ( ($m['topic_notify'] == 1) && ($m['uname'] != $userdata['uname']) ) {
         include_once("language/lang-multi.php");
         $resultZ=sql_query("SELECT topic_title FROM ".$NPDS_Prefix."forumtopics WHERE topic_id='$topic'");
         list($title_topic)=sql_fetch_row($resultZ);
         $subject = strip_tags($forum_name)."/".$title_topic." : ".html_entity_decode(translate_ml($m['user_langue'], "Une réponse à votre dernier Commentaire a été posté."),ENT_COMPAT | ENT_HTML401,'UTF-8');
         $message = $m['uname']."\n\n";
         $message .= translate_ml($m['user_langue'], "Vous recevez ce Mail car vous avez demandé à être informé lors de la publication d'une réponse.")."\n";
         $message .= translate_ml($m['user_langue'], "Pour lire la réponse")." : ";
         $message .= "<a href=\"$nuke_url/viewtopic.php?topic=$topic&forum=$forum&start=9999#lastpost\">$nuke_url/viewtopic.php?topic=$topic&forum=$forum&start=9999</a>\n\n";
         include("signat.php");
         send_email($m['email'], $subject, $message, '', true, "html", '');
         $sauf=$m['uid'];
      }
      global $subscribe;
      if ($subscribe) {
         if (subscribe_query($userdata['uid'],"forum",$forum)) {
            $sauf=$userdata['uid'];
         }
         subscribe_mail('forum',$topic,$forum,'',$sauf);
      }
      if (isset($upload)) {
         include("modules/upload/upload_forum.php");
         win_upload("forum_npds",$IdPost,$forum,$topic,"win");
         redirect_url("viewtopic.php?forum=$forum&topic=$topic&start=9999#lastpost");
         die();
      }
      redirect_url("viewforum.php?forum=$forum");
   } else {
      echo '
   <h4 class="my-3">'.translate("Poster une réponse dans le sujet").'</h4>
   <p class="alert alert-danger">'.translate("Vous devez taper un message à poster.").'</p>
   <a class="btn btn-outline-primary" href="javascript:history.go(-1)" >'.translate("Retour en arrière").'</a>';
   }
} else {
   include('header.php');
   if ($allow_bbcode==1)
      include("lib/formhelp.java.php");

   list($topic_title, $topic_status) = sql_fetch_row(sql_query("SELECT topic_title, topic_status FROM ".$NPDS_Prefix."forumtopics WHERE topic_id='$topic'"));
   if (isset($user)) {
      $userX = base64_decode($user);
      $userdata = explode(':', $userX);
   } else
      $userdata[0] = 0;
   
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

   $moderator = get_moderator($mod);
   $moderator=explode(' ',$moderator);
   $Mmod=false;

   echo '
   <p class="lead">
      <a href="forum.php">'.translate("Index du forum").'</a>&nbsp;&raquo;&raquo;&nbsp;
      <a href="viewforum.php?forum='.$forum.'">'.stripslashes($forum_name).'</a>&nbsp;&raquo;&raquo;&nbsp;'.$topic_title.'
   </p>
   <div class="card">
      <div class="card-body p-1">
            '.translate("Modérateur(s)");
   for ($i = 0; $i < count($moderator); $i++) {
      $modera = get_userdata($moderator[$i]);
      if ($modera['user_avatar'] != '') {
         if (stristr($modera['user_avatar'],"users_private"))
            $imgtmp=$modera['user_avatar'];
         else {
            if ($ibid=theme_image("forum/avatar/".$modera['user_avatar'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/avatar/".$modera['user_avatar'];}
         }
      }
      echo '<a href="user.php?op=userinfo&amp;uname='.$moderator[$i].'"><img width="48" height="48" class=" img-thumbnail img-fluid n-ava me-1" src="'.$imgtmp.'" alt="'.$modera['uname'].'" title="'.$modera['uname'].'" data-bs-toggle="tooltip" /></a>';
      if (isset($user))
         if (($userdata[1]==$moderator[$i])) $Mmod=true;
   }
   echo '
      </div>
   </div>
   <h4 class="d-none d-sm-block my-3"><img width="48" height="48" class=" rounded-circle me-3" src="'.$imgava.'" alt="" />'.translate("Poster une réponse dans le sujet").'</h4>
   <form action="reply.php" method="post" name="coolsus">';

   echo '<blockquote class="blockquote d-none d-sm-block"><p>'.translate("A propos des messages publiés :").'<br />';
   if ($forum_access == 0)
      echo translate("Les utilisateurs anonymes peuvent poster de nouveaux sujets et des réponses dans ce forum.");
   else if($forum_access == 1)
      echo translate("Tous les utilisateurs enregistrés peuvent poster de nouveaux sujets et répondre dans ce forum.");
   else if($forum_access == 2)
      echo translate("Seuls les modérateurs peuvent poster de nouveaux sujets et répondre dans ce forum.");
   echo '</blockquote>';

   $allow_to_reply=false;
   if ($forum_access==0)
      $allow_to_reply=true;
   elseif ($forum_access==1) {
      if (isset($user))
         $allow_to_reply=true;
   } elseif ($forum_access==2) {
      if (user_is_moderator($userdata[0],$userdata[2],$forum_access))
         $allow_to_reply=true;
   }
   if ($topic_status!=0)
      $allow_to_reply=false;

   settype($submitP,'string');
   settype($citation,'integer');
   if ($allow_to_reply) {
     if ($submitP) {
        $acc = 'reply';
        $message=stripslashes($message);
        include ("preview.php");
     }
     else
        $message='';

      settype($image_subject,'string');
      if ($smilies) {
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
         <label class="form-label" for="message">'.translate("Message").'</label>
         <div class="col-sm-12">
            <div class="card">
               <div class="card-header">
                  <div class="float-start">';
      putitems('ta_replypost');
      echo '
                  </div>';
   if ($allow_html == 1)
      echo '<span class="text-success float-end mt-2" title="HTML '.translate("Activé").'" data-bs-toggle="tooltip"><i class="fa fa-code fa-lg"></i></span>'.HTML_Add();
   else
      echo '<span class="text-danger float-end mt-2" title="HTML '.translate("Désactivé").'" data-bs-toggle="tooltip"><i class="fa fa-code fa-lg"></i></span>';
   echo '
               </div>
               <div class="card-body">';
      if ($citation && !$submitP) {
         $sql = "SELECT p.post_text, p.post_time, u.uname FROM ".$NPDS_Prefix."posts p, ".$NPDS_Prefix."users u WHERE post_id = '$post' AND ((p.poster_id = u.uid) XOR (p.poster_id=0)) ";
         if ($r = sql_query($sql)) {
            $m = sql_fetch_assoc($r);
            $text = $m['post_text'];
            if (($allow_bbcode) and ($forum_type!=6) and ($forum_type!=5)) {
               $text = smile($text);
               $text = str_replace('<br />', "\n", $text);
            } else
               $text = htmlspecialchars($text,ENT_COMPAT|ENT_HTML401,'UTF-8');
            $text = stripslashes($text);
            $reply = ($m['post_time']!='' && $m['uname']!='') ?
               '<blockquote class="blockquote">'.translate("Citation").' : <strong>'.$m['uname'].'</strong><br />'.$text.'</blockquote>' :
               $text."\n" ;
            $reply = preg_replace("#\[hide\](.*?)\[\/hide\]#si",'',$reply);
         } else
            $reply = translate("Erreur de connexion à la base de données")."\n";
      }
      if (!isset($reply)) $reply=$message;
      if ($allow_bbcode)
         $xJava = ' onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" onfocus="storeForm(this)"';
      echo '
                  <textarea id="ta_replypost" class="form-control" '.$xJava.' name="message" rows="15">'.$reply.'</textarea>
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
         <div class="col-sm-12">';
      if (($allow_html==1) and ($forum_type!='6') and ($forum_type!='5')) {
         if (isset($html)) $sethtml = 'checked'; else $sethtml = '';
         echo '
            <div class="checkbox my-2">
               <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="html" name="html" '.$sethtml.' />
                  <label class="form-check-label" for="html">'.translate("Désactiver le html pour cet envoi").'</label>
               </div>
            </div>';
      }
      if ($user) {
         if ($allow_sig == 1||$sig == 'on') {
            $asig = sql_query("SELECT attachsig FROM ".$NPDS_Prefix."users_status WHERE uid='$cookie[0]'");
            list($attachsig) = sql_fetch_row($asig);
            if ($attachsig == 1) $s = 'checked="checked"'; else $s = '';
            if (($forum_type!='6') and ($forum_type!='5')) {
               echo '
            <div class="checkbox my-2">
               <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="sig" name="sig" '.$s.' />
                  <label class="form-check-label" for="sig">'.translate("Afficher la signature").'</label>
                  <small class="help-block">'.translate("Cela peut être retiré ou ajouté dans vos paramètres personnels").'</small>
               </div>
            </div>';
           }
        }
        settype($upload,'string');
        settype($up,'string');
        if ($allow_upload_forum) {
           if ($upload == 'on') $up = 'checked="checked"';
         echo '
            <div class="checkbox my-2">
               <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="upload" name="upload" '.$up.' />
                  <label class="form-check-label" for="upload">'.translate("Charger un fichier une fois l'envoi accepté").'</label>
               </div>
            </div>';
        }
     }
     echo '
         </div>
      </div>
      '.Q_spambot().'
      <div class="mb-3 row">
         <div class="col-sm-12">
            <input type="hidden" name="forum" value="'.$forum.'" />
            <input type="hidden" name="topic" value="'.$topic.'" />
            <button class="btn btn-primary" type="submit" value="'.translate("Valider").'" name="submitS" accesskey="s" title="'.translate("Valider").'" data-bs-toggle="tooltip" >'.translate("Valider").'</button>&nbsp;
            <button class="btn btn-danger" type="submit" value="'.translate("Annuler la contribution").'" name="cancel" title="'.translate("Annuler la contribution").'" data-bs-toggle="tooltip" >'.translate("Annuler la contribution").'</button>
         </div>
      </div>';
   } else
      echo '
      <div class="alert alert-danger">'.translate("Vous n'êtes pas autorisé à participer à ce forum").'</div>';
   echo '
   </form>';
   if ($allow_to_reply) {
      echo '
      <h4 class="my-3">'.translate("Aperçu des sujets :").'</h4>';
      $post_aff = $Mmod ? '' : " AND post_aff='1' " ;
      $sql = "SELECT * FROM ".$NPDS_Prefix."posts WHERE topic_id='$topic' AND forum_id='$forum'".$post_aff."ORDER BY post_id DESC limit 0,10";
      if (!$result = sql_query($sql))
         forumerror('0001');
      $myrow = sql_fetch_assoc($result);
      $count=0;

      do {
         echo '
      <div class="row">
         <div class="col-12">
            <div class="card mb-3">
               <div class="card-header">';
         $posterdata = get_userdata_from_id($myrow['poster_id']);
         if($myrow['poster_id'] !== '0') {
            $posts = $posterdata['posts'];
            $socialnetworks=array(); $posterdata_extend=array();$res_id=array();$my_rs='';
            if (!$short_user) {
               $posterdata_extend = get_userdata_extend_from_id($myrow['poster_id']);
               include('modules/reseaux-sociaux/reseaux-sociaux.conf.php');
               if($user or autorisation(-127)) {
                  if (array_key_exists('M2', $posterdata_extend)) {
                     if ($posterdata_extend['M2']!='') {
                        $socialnetworks= explode(';',$posterdata_extend['M2']);
                        foreach ($socialnetworks as $socialnetwork) {
                           $res_id[] = explode('|',$socialnetwork);
                        }
                        sort($res_id);
                        sort($rs);
                        foreach ($rs as $v1) {
                           foreach($res_id as $y1) {
                              $k = array_search( $y1[0],$v1);
                              if (false !== $k) {
                                 $my_rs.='<a class="me-2" href="';
                                 if($v1[2]=='skype') $my_rs.= $v1[1].$y1[1].'?chat'; else $my_rs.= $v1[1].$y1[1];
                                 $my_rs.= '" target="_blank"><i class="fab fa-'.$v1[2].' fa-lg fa-fw mb-2"></i></a> ';
                                 break;
                              } else $my_rs.='';
                           }
                        }
                     }
                  }
               }
            }
            include('modules/geoloc/geoloc.conf');
            settype($ch_lat,'string');

            $useroutils = '';
            if ($posterdata['uid']!= 1 and $posterdata['uid']!='')
               $useroutils .= '<hr />';
            if($user or autorisation(-127)) {
               if ($posterdata['uid']!= 1 and $posterdata['uid']!='')
                  $useroutils .= '<a class="list-group-item text-primary text-center text-md-start" href="user.php?op=userinfo&amp;uname='.$posterdata['uname'].'" target="_blank" title="'.translate("Profil").'" data-bs-toggle="tooltip"><i class="fa fa-user fa-2x align-middle fa-fw"></i><span class="ms-3 d-none d-md-inline">'.translate("Profil").'</span></a>';
               if ($posterdata['uid']!= 1)
                  $useroutils .= '<a class="list-group-item text-primary text-center text-md-start" href="powerpack.php?op=instant_message&amp;to_userid='.$posterdata["uname"].'" title="'.translate("Envoyer un message interne").'" data-bs-toggle="tooltip"><i class="far fa-envelope fa-2x align-middle fa-fw"></i><span class="ms-3 d-none d-md-inline">'.translate("Message").'</span></a>';
               if ($posterdata['femail']!='')
                  $useroutils .= '<a class="list-group-item text-primary text-center text-md-start" href="mailto:'.anti_spam($posterdata['femail'],1).'" target="_blank" title="'.translate("Email").'" data-bs-toggle="tooltip"><i class="fa fa-at fa-2x align-middle fa-fw"></i><span class="ms-3 d-none d-md-inline">'.translate("Email").'</span></a>';
               if ($myrow['poster_id']!=1 and array_key_exists($ch_lat, $posterdata_extend)) {
                  if ($posterdata_extend[$ch_lat] !='')
                     $useroutils .= '<a class="list-group-item text-primary text-center text-md-start" href="modules.php?ModPath=geoloc&amp;ModStart=geoloc&amp;op=u'.$posterdata['uid'].'" title="'.translate("Localisation").'" ><i class="fas fa-map-marker-alt fa-2x align-middle fa-fw">&nbsp;</i><span class="ms-3 d-none d-md-inline">'.translate("Localisation").'</span></a>';
               }
            }
            if ($posterdata['url']!='')
               $useroutils .= '<a class="list-group-item text-primary text-center text-md-start" href="'.$posterdata['url'].'" target="_blank" title="'.translate("Visiter ce site web").'" data-bs-toggle="tooltip"><i class="fas fa-external-link-alt fa-2x align-middle fa-fw"></i><span class="ms-3 d-none d-md-inline">'.translate("Visiter ce site web").'</span></a>';
            if ($posterdata['mns'])
               $useroutils .= '<a class="list-group-item text-primary text-center text-md-start" href="minisite.php?op='.$posterdata['uname'].'" target="_blank" target="_blank" title="'.translate("Visitez le minisite").'" data-bs-toggle="tooltip"><i class="fa fa-2x fa-desktop align-middle fa-fw"></i><span class="ms-3 d-none d-md-inline">'.translate("Visitez le minisite").'</span></a>';
         }
         if ($smilies) {
            if($myrow['poster_id'] !== '0') {
               if ($posterdata['user_avatar'] != '') {
                  if (stristr($posterdata['user_avatar'],"users_private"))
                     $imgtmp=$posterdata['user_avatar'];
                  else {
                     if ($ibid=theme_image("forum/avatar/".$posterdata['user_avatar'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/avatar/".$posterdata['user_avatar'];}
                  }
               }

               echo '
               <a style="position:absolute; top:1rem;" tabindex="0" data-bs-toggle="popover" data-bs-trigger="focus" data-bs-html="true" data-bs-title="'.$posterdata['uname'].'" data-bs-content=\'<div class="my-2 border rounded p-2">'.member_qualif($posterdata['uname'], $posts,$posterdata['rang']).'</div><div class="list-group mb-3 text-center">'.$useroutils.'</div><div class="mx-auto text-center" style="max-width:170px;">'.$my_rs.'</div>\'><img class=" btn-outline-primary img-thumbnail img-fluid n-ava" src="'.$imgtmp.'" alt="'.$posterdata['uname'].'" /></a>
               <span style="position:absolute; left:6em;" class="text-body-secondary"><strong>'.$posterdata['uname'].'</strong></span>';
            } else {
               echo '
               <a style="position:absolute; top:1rem;" title="'.$anonymous.'" data-bs-toggle="tooltip"><img class=" btn-outline-primary img-thumbnail img-fluid n-ava" src="images/forum/avatar/blank.gif" alt="'.$anonymous.'" /></a>
               <span style="position:absolute; left:6em;" class="text-body-secondary"><strong>'.$anonymous.'</strong></span>';
            }
         } else {
            echo $myrow['poster_id'] !== '0' ?
               '<span style="position:absolute; left:6em;" class="text-body-secondary"><strong>'.$posterdata['uname'].'</strong></span>' :
               '<span class="text-body-secondary"><strong>'.$anonymous.'</strong></span>' ;
         }
         echo '
                  <span class="float-end">';
         if ($myrow['image'] != '') {
            if ($ibid=theme_image("forum/subject/".$myrow['image'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/subject/".$myrow['image'];}
            echo '<img class="n-smil" src="'.$imgtmp.'"  alt="" />';
         } 
         else {
            if ($ibid=theme_image("forum/subject/icons/posticon.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/icons/posticon.gif";}
            echo '<img class="n-smil" src="'.$imgtmp.'" alt="" />';
         }

         echo '
                  </span>
               </div>
               <div class="card-body">
                  <span class="text-body-secondary float-end small" style="margin-top:-1rem;">'.translate("Posté : ").formatTimes($myrow['post_time'], IntlDateFormatter::MEDIUM, IntlDateFormatter::SHORT).'</span>
                  <div class="card-text pt-4">';
         $message = stripslashes($myrow['post_text']);
         if (($allow_bbcode) and ($forum_type!=6) and ($forum_type!=5)) {
            $message = smilie($message);
            $message = aff_video_yt($message);
            $message = af_cod($message);
            $message= str_replace("\n",'<br />',$message);
         }
         if (($forum_type=='6') or ($forum_type=='5'))
            highlight_string(stripslashes($myrow['post_text'])).'<br /><br />';
         else {
            if(array_key_exists('user_sig', $posterdata))
               $message = str_replace('[addsig]','<div class="n-signature">'.nl2br($posterdata['user_sig']).'</div>', $message);
            echo $message.'
                  </div>';
         }
         echo '
               </div>
            </div>
         </div>
      </div>';
         $count++;
      } while($myrow = sql_fetch_assoc($result));
   }
}
include('footer.php');
?>
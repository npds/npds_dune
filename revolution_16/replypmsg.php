<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
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

settype($cancel,'string');
settype($submitS,'string');
//$msg_id = array('');// voir si c'est le bon type
settype($reply,'string');
settype($Xreply,'string');
settype($to_user,'string');
//settype($send,'string');
settype($sig,'string');
settype($copie,'string');

if ($cancel) {
   if ($full_interface!='short')
      header("Location: viewpmsg.php");
   else
      header("Location: readpmsg_imm.php?op=new_msg");
   die();
}

if (isset($user)) {
   $userX = base64_decode($user);
   $userdataX = explode(':', $userX);
   $userdata = get_userdata($userdataX[1]);
   $usermore = get_userdata_from_id($cookie[0]);

   if ($submitS) {
      if ($subject == '')
         forumerror('0017');
      $subject = removeHack($subject);

      if ($smilies) {
         if ($image_subject == '' )
            forumerror('0018');
      }

      if ($message == '')
         forumerror('0019');

      if ($allow_html == 0 || isset($html)) $message = htmlspecialchars($message,ENT_COMPAT|ENT_HTML401,'UTF-8');
      if ($sig)
         $message .= '<br /><br />'.$userdata['user_sig'];
      $message = aff_code($message);
      $message = str_replace("\n", '<br />', $message);
      if ($allow_bbcode)
         $message = smile($message);
      $message = make_clickable($message);
      $message = removeHack(addslashes($message));
      $time = getPartOfTime(time(), 'yyyy-MM-dd H:mm:ss');

      include_once("language/lang-multi.php");
      if (strstr($to_user,',')) {
         $tempo=explode(',',$to_user);
         foreach($tempo as $to_user) {
            $res = sql_query("SELECT uid, user_langue FROM ".$NPDS_Prefix."users WHERE uname='$to_user'");
            list($to_userid, $user_langue) = sql_fetch_row($res);
            if (($to_userid != '') and ($to_userid != 1)) {
               $sql = "INSERT INTO ".$NPDS_Prefix."priv_msgs (msg_image, subject, from_userid, to_userid, msg_time, msg_text) ";
               $sql .= "VALUES ('$image_subject', '$subject', '".$userdata['uid']."', '$to_userid', '$time', '$message')";
               if(!$result = sql_query($sql))
                 forumerror('0020');
               if ($copie) {
                  $sql = "INSERT INTO ".$NPDS_Prefix."priv_msgs (msg_image, subject, from_userid, to_userid, msg_time, msg_text, type_msg, read_msg) ";
                  $sql .= "VALUES ('$image_subject', '$subject', '".$userdata['uid']."', '$to_userid', '$time', '$message', '1', '1')";
                  if (!$result = sql_query($sql))
                     forumerror('0020');
               }
               global $nuke_url, $subscribe, $sitename;;
               if ($subscribe) {
                  $old_message=$message; // what this
                  $sujet=html_entity_decode(translate_ml($user_langue, "Notification message privé."),ENT_COMPAT | ENT_HTML401,'UTF-8').'['.$usermore['uname'].'] / '.$sitename;
                  $message=translate_ml($user_langue, "Bonjour").'<br />'.translate_ml($user_langue, "Vous avez un nouveau message.").'<br />'.$time.'<br /><br /><b>'.$subject.'</b><br /><br /><a href="'.$nuke_url.'/viewpmsg.php">'.translate_ml($user_langue, "Cliquez ici pour lire votre nouveau message.").'</a><br /><br />';
                  include("signat.php");
                  copy_to_email($to_userid,$sujet,stripslashes($message));
                  $message=$old_message; // what this
               }
            }
         }
      } else {
         $res = sql_query("SELECT uid, user_langue FROM ".$NPDS_Prefix."users WHERE uname='$to_user'");
         list($to_userid, $user_langue) = sql_fetch_row($res);

         if (($to_userid == '') or ($to_userid == 1))
            forumerror('0016');
         else {
            $sql = "INSERT INTO ".$NPDS_Prefix."priv_msgs (msg_image, subject, from_userid, to_userid, msg_time, msg_text) ";
            $sql .= "VALUES ('$image_subject', '$subject', '".$userdata['uid']."', '$to_userid', '$time', '$message')";
            if (!$result = sql_query($sql))
               forumerror('0020');
            if ($copie) {
               $sql = "INSERT INTO ".$NPDS_Prefix."priv_msgs (msg_image, subject, from_userid, to_userid, msg_time, msg_text, type_msg, read_msg) ";
               $sql .= "VALUES ('$image_subject', '$subject', '".$userdata['uid']."', '$to_userid', '$time', '$message', '1', '1')";
               if (!$result = sql_query($sql))
                  forumerror('0020');
            }
            global $nuke_url, $subscribe, $sitename;
            if ($subscribe) {
               $sujet=html_entity_decode(translate_ml($user_langue, "Notification message privé."), ENT_COMPAT | ENT_HTML401,'UTF-8').'['.$usermore['uname'].'] / '.$sitename;
               $message=translate_ml($user_langue, "Bonjour").'<br />'.translate_ml($user_langue, "Vous avez un nouveau message.").'<br />'.$time.'<br /><br /><b>'.$subject.'</b><br /><br /><a href="'.$nuke_url.'/viewpmsg.php">'.translate_ml($user_langue, "Cliquez ici pour lire votre nouveau message.").'</a><br /><br />';
               include("signat.php");
               copy_to_email($to_userid,$sujet,stripslashes($message));
            }
         }
      }
      unset($message);unset($sujet);
      if ($full_interface!='short')
         header("Location: viewpmsg.php");
      else
         header("Location: readpmsg_imm.php?op=new_msg");
   }

   settype($delete_messages,'string');
   if ($delete_messages and $msg_id) {
   settype($status,'integer');
      foreach ($msg_id as $v) {
         if ($type=='outbox')
            $sql = "DELETE FROM ".$NPDS_Prefix."priv_msgs WHERE msg_id='".$v."' AND from_userid='".$userdata['uid']."' AND type_msg='1'";
         else
            $sql = "DELETE FROM ".$NPDS_Prefix."priv_msgs WHERE msg_id='".$v."' AND to_userid='".$userdata['uid']."'";
         if (!sql_query($sql))
            forumerror('0021');
         else
            $status=1;
      }
      if ($status==1)
         header("Location: viewpmsg.php");
   } else if ($delete_messages ='' and !$msg_id) {
      header("Location: viewpmsg.php");
   }

//   settype($delete,'integer');
   if (isset($delete)) {
      if (isset($type) and $type=='outbox')
         $sql = "DELETE FROM ".$NPDS_Prefix."priv_msgs WHERE msg_id='$msg_id' AND from_userid='".$userdata['uid']."' AND type_msg='1'";
      else
         $sql = "DELETE FROM ".$NPDS_Prefix."priv_msgs WHERE msg_id='$msg_id' AND to_userid='".$userdata['uid']."'";
      if (!sql_query($sql))
         forumerror('0021');
      else
         header("Location: viewpmsg.php");
   }
   settype($classement,'string');
   if ($classement) {
      if ($nouveau_dossier!='') $dossier=$nouveau_dossier;
      $dossier=strip_tags($dossier);
      $sql = "UPDATE ".$NPDS_Prefix."priv_msgs SET dossier='$dossier' WHERE msg_id='$msg_id' AND to_userid='".$userdata['uid']."'";
      $result = sql_query($sql);
      if (!$result)
         forumerror('0005');
      header("Location: viewpmsg.php");
   }

   // Interface
   settype($full_interface,'string');
   if ($full_interface=='short') {
      if ($userdataX[9]!='') {
         $ibix=explode('+', urldecode($userdataX[9]));
         if (array_key_exists(0, $ibix)) $theme=$ibix[0]; else $theme=$Default_Theme;
         if (array_key_exists(1, $ibix)) $skin=$ibix[1]; else $skin=$Default_Skin; 
         $tmp_theme=$theme;
         if (!$file=@opendir("themes/$theme")) $tmp_theme=$Default_Theme;
      } else
         $tmp_theme=$Default_Theme;
      $skin = $skin =='' ? 'default' : $skin ;

      include("themes/$tmp_theme/theme.php");
      include("meta/meta.php");
      include("modules/include/header_before.inc");
      include("modules/include/header_head.inc");
      echo import_css($tmp_theme, $language, $skin, '','');
      echo '
   </head>
   <body class="my-4 mx-4">';
   } else
      include('header.php');

   if ($reply || $send || $to_user) {
      if ($allow_bbcode)
         include("lib/formhelp.java.php");
      if ($reply) {
         $sql = "SELECT msg_image, subject, from_userid, to_userid FROM ".$NPDS_Prefix."priv_msgs WHERE to_userid='".$userdata['uid']."' AND msg_id='$msg_id' AND type_msg='0'";
         $result = sql_query($sql);
         if (!$result)
            forumerror('0022');
         $row = sql_fetch_assoc($result);
         if (!$row)
            forumerror('0023');
         $fromuserdata = get_userdata_from_id($row['from_userid']);
         if(array_key_exists(0,$fromuserdata)) {
            if ($fromuserdata[0]==1)
               forumerror('0101');
         }
         $touserdata = get_userdata_from_id($row['to_userid']);
         if (($user) and ($userdata['uid']!=$touserdata['uid']))
            forumerror('0024');
      }
      echo '
      <h2><a href="viewpmsg.php"><i class="me-2 fa fa-inbox"></i></a>'.translate("Message personnel").'</h2>
      <hr />
      <blockquote class="blockquote">'.translate("A propos des messages publiés :").'<br />'.
         translate("Tous les utilisateurs enregistrés peuvent poster des messages privés.").'</blockquote>';
      settype($submitP,'string');
      if ($submitP) {
         echo '
         <hr />
         <h3>'.translate("Prévisualiser").'</h3>
         <p class="lead">'.StripSlashes($subject).'</p>';
         $Xmessage=$message=StripSlashes($message);
         if ($allow_html == 0 || isset($html)) $Xmessage = htmlspecialchars($Xmessage,ENT_COMPAT|ENT_HTML401,'UTF-8');
         if ($sig=='on')
            $Xmessage .= '<div class="n-signature">'.nl2br($userdata['user_sig']).'</div>';

         $Xmessage = aff_code($Xmessage);
         $Xmessage = str_replace("\n", '<br />', $Xmessage);
         if ($allow_bbcode) {
            $Xmessage = smilie($Xmessage);
            $Xmessage = aff_video_yt($Xmessage);
         }
         $Xmessage = make_clickable($Xmessage);
         echo $Xmessage;
            echo '<hr />';
         }
         echo '
         <form id="pmessage" action="replypmsg.php" method="post" name="coolsus">
         <div class="mb-3 row">
            <label class="col-form-label col-sm-3" for="to_user">'.translate("Destinataire").'</label>
            <div class="col-sm-9">';
         if ($reply)
            echo userpopover($fromuserdata['uname'],48,2).'
               <input class="form-control-plaintext d-inline-block w-75" type="text" id="to_user" name="to_user" value="'.$fromuserdata['uname'].'" readonly="readonly" />';
         else {
            settype($Xto_user,'string');
            if ($send!=1) $Xto_user=$send;
            if ($to_user) $Xto_user=$to_user;
            echo '
               <input class="form-control" type="text" id="to_user" name="to_user" value="'.$Xto_user.'" maxlength="100" required="required"/>';
         }
         if (!$reply) {
            $carnet=JavaPopUp("carnet.php","CARNET",300,350);
            $carnet='<a href="javascript:void(0);" onclick="window.open('.$carnet.'); ">';
            echo $carnet.'<span class="small">'.translate("Carnet d'adresses").'</span></a>';
         }
         echo '
            </div>
         </div>';
         settype($copie,'string');
         if ($copie) $checked='checked="checked"'; else $checked='';
         echo '
         <div class="mb-3 row">
            <div class="col-sm-9 ms-auto">
            <div class="form-check">
               <input class="form-check-input" type="checkbox" id="copie" name="copie" '.$checked.' />
               <label class="form-check-label" for="copie"> '.translate("Conserver une copie").'</label>
            </div>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-12" for="subject">'.translate("Sujet").'</label>
            <div class="col-sm-12">';
         settype($subject,'string');
         if ($subject) {$tmp=StripSlashes($subject);} else {if ($reply) $tmp="Re: ".StripSlashes($row['subject']); else $tmp='';}
         echo '
               <input class="form-control" type="text" id="subject" name="subject" value="'.$tmp.'" maxlength="100" required="required" />
               <span class="help-block" id ="countcar_subject"></span>
            </div>
         </div>';
         settype($image_subject,'string');
         if ($smilies) {
            echo '
         <div class="mb-3 row">
            <label class="col-form-label col-sm-12">'.translate("Icone du message").'</label>
            <div class="col-sm-12">
               <div class="border rounded pt-3 px-2 n-fond_subject d-flex flex-row flex-wrap">
               '.emotion_add($image_subject).'
               </div>
            </div>
         </div>';
         }
      echo '
      <div class="mb-3 row">
         <label class="col-form-label col-sm-12" for="message">'.translate("Message").'</label>
         <div class="col-sm-12">
            <div class="card">
               <div class="card-header">';
         if ($allow_html == 1)
            echo '<span class="text-success float-end" title="HTML '.translate("Activé").'" data-bs-toggle="tooltip"><i class="fa fa-code fa-lg"></i></span>'.HTML_Add();
         else
            echo '<span class="text-danger float-end" title="HTML '.translate("Désactivé").'" data-bs-toggle="tooltip"><i class="fa fa-code fa-lg"></i></span>';
         echo '
            </div>
            <div class="card-body">';

         settype($message,'string');
         if ($reply and $message=='') {
            $sql = "SELECT p.msg_text, p.msg_time, u.uname FROM ".$NPDS_Prefix."priv_msgs p, ".$NPDS_Prefix."users u ";
            $sql .= "WHERE (p.msg_id='$msg_id') AND (p.from_userid=u.uid) AND (p.type_msg='0')";
            if ($result = sql_query($sql)) {
               $row = sql_fetch_assoc($result);
               $text = smile($row['msg_text']);
               $text = str_replace("<br />", "\n", $text);
               $text = str_replace("<BR />", "\n", $text);
               $text = str_replace("<BR>", "\n", $text);
               $text = stripslashes($text);
               if ($row['msg_time']!='' && $row['uname']!='')
                  $Xreply = $row['msg_time'].', '.$row['uname'].' '.translate("a écrit :")."\n$text\n";
               else
                  $Xreply = $text;
               $Xreply = '
               <div class="blockquote">
               '.$Xreply.'
               </div>';
            } else
               $Xreply = translate("Pas de connexion à la base forums.")."\n";
         } elseif ($message!='') {
            $Xreply = $message;
         }
         if ($allow_bbcode)
            $xJava = 'name="message" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" onfocus="storeForm(this)"';
         echo '
         <textarea id="ta_replypm" class="form-control" '.$xJava.' name="message" rows="15">';
         if ($Xreply) echo $Xreply;
         echo '
         </textarea>
         <span class="help-block text-end">
            <button class="btn btn-outline-danger btn-sm" type="reset" value="'.translate("Annuler").'" title="'.translate("Annuler").'" data-bs-toggle="tooltip" ><i class="fas fa-times " ></i></button>
            <button class="btn btn-outline-primary btn-sm" type="submit" value="'.translate("Prévisualiser").'" name="submitP" title="'.translate("Prévisualiser").'" data-bs-toggle="tooltip" ><i class="fa fa-eye "></i></button>
         </span>
               </div>
               <div class="card-footer text-body-secondary">';
         if ($allow_bbcode)
            putitems('ta_replypm');
         echo '
               </div>
            </div>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-3">'.translate("Options").'</label>';
         if ($allow_html==1) {
            settype($html,'string');
            if ($html=='on') $checked='checked="checked"'; else $checked='';
            echo '
         <div class="col-sm-9 my-2">
            <div class="form-check">
               <input class="form-check-input" type="checkbox" id="html" name="html" '.$checked.' />
               <label class="form-check-label" for="html">'.translate("Désactiver le html pour cet envoi").'</label>
            </div>';
         }

         if ($usermore['attachsig']=='1') {
            $checked = 'checked="checked"';
            settype($sig,'string');
            if ($submitP) {if($sig=='on') $checked = 'checked="checked"'; else $checked = '';}
            echo '
            <div class="form-check">
               <input class="form-check-input" type="checkbox" id="sig" name="sig" '.$checked.' />
               <label class="form-check-label" for="sig">'.translate("Afficher la signature").'</label>
            </div>
            <small class="help-block">'.translate("Cela peut être retiré ou ajouté dans vos paramètres personnels").'</small>';
         }

         settype($msg_id,'integer');
         echo '
         </div>
      </div>
      <div class="mb-3 row">
         <div class="col-sm-12">
            <input type="hidden" name="msg_id" value="'.$msg_id.'" />
            <input type="hidden" name="full_interface" value="'.$full_interface.'" />';

         settype($send,'integer');
         if ($send==1)
            echo '
            <input type="hidden" name="send" value="1" />';
         settype($reply,'integer');
         if ($reply==1)
            echo '
            <input type="hidden" name="reply" value="1" />';
         echo '
            <input class="btn btn-primary" type="submit" name="submitS" value="'.translate("Valider").'" />&nbsp;';
         if ($reply)
            echo '
            <input class="btn btn-danger ms-2" type="submit" name="cancel" value="'.translate("Annuler la réponse").'" />';
         else {
            echo '
            <input class="btn btn-danger ms-2" type="submit" name="cancel" value="'.translate("Annuler l'envoi").'" />';
            echo auto_complete ('membre','uname','users','to_user','86400');
         }
         echo '
         </div>
      </div>
   </form>';
         $arg1='
         var formulid=["pmessage"]
         inpandfieldlen("subject",100);';

         adminfoot('','',$arg1,'foo');
      }
   }
else
   Header("Location: user.php");
?>
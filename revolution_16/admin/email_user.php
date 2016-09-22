<?php
/************************************************************************/
/* DUNE by NPDS - admin prototype                                       */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!stristr($_SERVER['PHP_SELF'],"admin.php")) { Access_Error(); }
$f_meta_nom ='email_user';
$f_titre = adm_translate("Diffusion d'un Message Interne");
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
global $language;
$hlpfile = "manuels/$language/email_user.html";

function email_user() {
    global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;

    include ('header.php');
       GraphicAdmin($hlpfile);
       adminhead ($f_meta_nom, $f_titre, $adminimg);
       echo '
      <hr />
      <form id="fad_emailuser" action="admin.php" method="post" name="AdmMI">
         <fieldset>
            <legend>'.adm_translate("Message").'</legend>
            <input type="hidden" name="op" value="send_email_to_user" />
            <div class="form-group row">
               <label class="form-control-label col-sm-4" for="expediteur">'.adm_translate("Expédier en tant").'</label>
               <div class="col-sm-8">
                  <label class="radio-inline">
                     <input type="radio" name="expediteur" value="1" checked="checked">'.adm_translate("qu'administrateur").' / 
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="expediteur" value="0">'.adm_translate("que membre").'
                  </label>
               </div>
            </div>
            <div id="div_username" class="form-group row">
               <label class="form-control-label col-sm-4" for="username">'.adm_translate("Utilisateur").'</label>
               <div class="col-sm-8">
                  <input id="username" class="form-control" type="text" name="username" value="" />
               </div>
            </div>
            <div id="div_groupe" class="form-group row">
               <label class="form-control-label col-sm-4" for="groupe">'.adm_translate("Groupe").'</label>
               <div class="col-sm-8">
                  <select id="groupe" class="custom-select form-control" name="groupe" >
                     <option value="0" selected="selected">'.adm_translate("Choisir un groupe");
       $resultID = sql_query("SELECT groupe_id, groupe_name FROM ".$NPDS_Prefix."groupes ORDER BY groupe_id ASC");
       while (list($groupe_id, $groupe_name)=sql_fetch_row($resultID)) {
          echo '
                     <option value="'.$groupe_id.'">'.$groupe_id.' - '.aff_langue($groupe_name);
       }
       echo '
                  </select>
               </div>
            </div>
            <div id="div_all" class="form-group row">
               <label class="form-control-label checkbox col-sm-4" for="all">'.adm_translate("Envoyer à tous les membres").'</label>
               <div class="col-sm-8 ">
                  <input id="all" type="checkbox" name="all" value="1" />
               </div>
            </div>
            <div class="form-group row">
               <label class="form-control-label col-sm-4" for="subject">'.adm_translate("Sujet").'</label>
               <div class="col-sm-8">
                  <input id="subject" class="form-control" type="text" maxlength="100" name="subject" />
                  <span class="help-block text-xs-right"><span id="countcar_subject"></span></span>
               </div>
            </div>
            <div class="form-group row">
               <label class="form-control-label col-sm-12" for="message">'.adm_translate("Corps de message").'</label>
               <div class="col-sm-12">
                  <textarea id="message" class="tin form-control" rows="25" name="message"></textarea>
               </div>
            </div>';
      echo aff_editeur('AdmMI', '');
      echo '
            <div class="form-group row">
               <div class="col-sm-12">
                  <button type="submit" class="btn btn-primary">'.adm_translate("Envoyer").'</button>
               </div>
            </div>
         </fieldset>
      </form>
   <script type="text/javascript">
   //<![CDATA[
   $("#all").on("click", function(){
    check = $("#all").is(":checked");
    if(check) {
    $("#div_username").addClass("collapse");
    $("#div_groupe").addClass("collapse");
    } else {
        $("#div_username").removeClass("collapse in");
        $("#div_groupe").removeClass("collapse in");
    }
   }); 
   $("#groupe").on("change", function(){
    sel = $("#groupe").val();
    if(sel!=0) {
    $("#div_username").addClass("collapse");
    $("#div_all").addClass("collapse");
    } else {
        $("#div_username").removeClass("collapse in");
        $("#div_all").removeClass("collapse in");
    }
   });
   $("#username").bind("change paste keyup", function() {
    ibid = $(this).val();
    if(ibid!="") {
    $("#div_groupe").addClass("collapse");
    $("#div_all").addClass("collapse");
    } else {
        $("#div_groupe").removeClass("collapse in");
        $("#div_all").removeClass("collapse in");
    }
   });
    
      $(document).ready(function() {
         inpandfieldlen("subject",100);
      });
   //]]>
   </script>';
   echo auto_complete ('membre','uname','users','username','86400');
   adminfoot('fv','','','');
}

function send_email_to_user($username, $subject, $message, $all, $groupe, $expediteur) {
   global $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;

   if ($subject!='') {
      if ($expediteur==1) {
         $emetteur=1;
      } else {
         global $user;
         if ($user) {
            $userX = base64_decode($user);
            $userdata = explode(':', $userX);
            $emetteur=$userdata[0];
         } else {
            $emetteur=1;
         }
      }
      if ($all) {
         $result=sql_query("SELECT uid, user_langue FROM ".$NPDS_Prefix."users");
         while (list($to_userid, $user_langue)=sql_fetch_row($result)) {
            $tab_to_userid[]=$to_userid.':'.$user_langue;
         }
      } else {
         if ($groupe) {
            $result = sql_query("SELECT s.uid, s.groupe, u.user_langue FROM ".$NPDS_Prefix."users_status s, ".$NPDS_Prefix."users u WHERE s.uid=u.uid AND s.groupe!='' ORDER BY s.uid ASC");
            while(list($to_userid, $groupeX, $user_langue) = sql_fetch_row($result)) {
               $tab_groupe=explode(',',$groupeX);
               if ($tab_groupe) {
                  foreach($tab_groupe as $groupevalue) {
                     if ($groupevalue==$groupe) {
                        $tab_to_userid[]=$to_userid.':'.$user_langue;
                     }
                  }
               }
            }
         } else {
            $result = sql_query("SELECT uid, user_langue FROM ".$NPDS_Prefix."users WHERE uname='$username'");
            while (list($to_userid, $user_langue)=sql_fetch_row($result)) {
               $tab_to_userid[]=$to_userid.':'.$user_langue;
            }
         }
      }
      if (($subject=='') or ($message=='')) {
         header("location: admin.php");
      }
      $message = str_replace('\n','<br />', $message);
      global $gmt;
      $time = date(translate("dateinternal"),time()+($gmt*3600));
      $pasfin=false;
      $count=0;
      include_once("language/lang-multi.php");
      while ($count<sizeof($tab_to_userid)) {
         $to_tmp=explode(':',$tab_to_userid[$count]);
         $to_userid=$to_tmp[0];
         if (($to_userid != '') and ($to_userid != 1)) {
            $sql = "INSERT INTO ".$NPDS_Prefix."priv_msgs (msg_image, subject, FROM_userid, to_userid, msg_time, msg_text) ";
            $sql .= "VALUES ('$image', '$subject', '$emetteur', '$to_userid', '$time', '$message')";
            if ($resultX = sql_query($sql)) {
               $pasfin=true;
            }
              // A copy in email if necessary
              global $nuke_url, $subscribe;
              if ($subscribe) {
                 $old_message=$message;
                 $sujet=translate_ml($to_tmp[1], 'Vous avez un nouveau message.');
                 $message=translate_ml($to_tmp[1], 'Bonjour').",<br /><br /><a href=\"$nuke_url/viewpmsg.php\">".translate_ml($to_tmp[1], "Cliquez ici pour lire votre nouveau message.")."</a><br /><br />";
                 include("signat.php");
                 copy_to_email($to_userid,$sujet,$message);
                 $message=$old_message;
            }
         }
         $count++;
      }
   }
   global $aid; Ecr_Log('security', "SendEmailToUser($subject) by AID : $aid", '');

   global $hlpfile;
   include ('header.php');
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '
   <hr />';
   if ($pasfin) {
   echo '
   <div class="alert alert-success"><strong>"'.stripslashes($subject).'"</strong> '.adm_translate("a été envoyée").'.</div>';
   } else {
   echo '
   <div class="alert alert-danger"><strong>"'.stripslashes($subject).'"</strong>' .adm_translate("n'a pas été envoyée").'.</div>';
   }
   adminfoot('','','','');
}

switch ($op){
   case 'send_email_to_user':
   send_email_to_user($username, $subject, $message, $all, $groupe, $expediteur);
   break;
   case 'email_user':
   email_user();
   break;
}
?>
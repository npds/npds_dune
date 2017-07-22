<?php
/************************************************************************/
/* SFORM Extender for NPDS USER                                         */
/* ===========================                                          */
/* NPDS Copyright (c) 2002-2017 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
/* Dont modify this file is you dont know what you make                 */
/************************************************************************/
global $NPDS_Prefix;

$m->add_title(translate("User"));
$m->add_mess(translate("* for mandatory field"));
$m->add_form_field_size(50);

$m->add_field('name', translate("Real Name").' '.translate("(optional)"),$userinfo['name'],'text',false,60,'','');
$m->add_extender('name', '', '<span class="help-block"><span class="float-right" id="countcar_name"></span></span>');

$m->add_field('email', translate("Real Email"),$userinfo['email'],'email',true,60,'','');
$m->add_extender('email', '','<span class="help-block">'.translate("(This Email will not be public but is required, will be used to send your password if you lost it)").'<span class="float-right" id="countcar_email"></span></span>');
$m->add_field('femail',translate("Fake Email"),$userinfo['femail'],'email',false,60,"","");
$m->add_extender('femail', '','<span class="help-block">'.translate("(This Email will be public. Just type what you want, Spam proof)").'<span class="float-right" id="countcar_femail"></span></span>');

if ($userinfo['user_viewemail']) {$checked=true;} else {$checked=false;}
$m->add_checkbox('user_viewemail',translate("Allow other users to view my email address"), 1, false, $checked);

$m->add_field('url', translate("Your HomePage"),$userinfo['url'],'url',false,100,'','');
$m->add_extender('url', '','<span class="help-block"><span class="float-right" id="countcar_url"></span></span>');

// ---- SUBSCRIBE and INVISIBLE
if ($subscribe) {
   if ($userinfo['send_email']==1) {$checked=true;} else {$checked=false;}
   $m->add_checkbox('usend_email',translate("Send me an email when Internal Message arrive"), 1, false, $checked);
}
if ($member_invisible) {
   if ($userinfo['is_visible']==1) {$checked=false;} else {$checked=true;}
   $m->add_checkbox('uis_visible',translate("Invisible' member")." (".translate("not showed in memberlist, members' message bloc ...").")", 1, false, $checked);
}
// ---- SUBSCRIBE and INVISIBLE

// LNL
if ($userinfo['user_lnl']) {$checked=true;} else {$checked=false;}
$m->add_checkbox('user_lnl',translate("Register to web site' mailing list"), 1, false, $checked);
// LNL

// ---- AVATAR
if ($smilies) {
   if (stristr($userinfo['user_avatar'],"users_private")) {
      $m->add_field('user_avatar',translate("Your Avatar"), $userinfo['user_avatar'],'show-hidden',false,30,'','');
      $m->add_extender('user_avatar', '', '<img class="img-thumbnail n-ava" src="'.$userinfo['user_avatar'].'" name="avatar" alt="avatar" />');
   } else {
      global $theme;
      $direktori="images/forum/avatar";
      if (function_exists("theme_image")) {
         if (theme_image("forum/avatar/blank.gif"))
            $direktori="themes/$theme/images/forum/avatar";
      }
      $handle=opendir($direktori);
      while (false!==($file = readdir($handle))) {
         $filelist[] = $file;
      }
      asort($filelist);
      while (list ($key, $file) = each ($filelist)) {
         if (!preg_match('#\.gif|\.jpg|\.png$#i', $file)) continue;
            $tmp_tempo[$file]['en']=$file;
            if ($userinfo['user_avatar']==$file) {$tmp_tempo[$file]['selected']=true;} else {$tmp_tempo[$file]['selected']=false;}
      }
      $m->add_select('user_avatar',translate("Your Avatar"), $tmp_tempo, false, '', false);
      $m->add_extender('user_avatar', 'onkeyup="showimage();" onchange="showimage();"', '<div class="help-block"><img class="img-thumbnail n-ava" src="'.$direktori.'/'.$userinfo['user_avatar'].'" name="avatar" align="top" title="" /></div>');
   }

   // Permet à l'utilisateur de télécharger un avatar (photo) personnel
   // - si vous mettez un // devant les deux lignes B1 et raz_avatar celà équivaut à ne pas autoriser cette fonction de NPDS
   // - le champ B1 est impératif ! La taille maxi du fichier téléchargeable peut-être changée (le dernier paramètre) et est en octets (par exemple 20480 = 20 Ko)
   $taille_fichier=8192;
   if (!$avatar_size) {$avatar_size='80*100';}
   $m->add_upload('B1', '', '30', $taille_fichier);
   $m->add_extender('B1', '', '<span class="help-block">taille maximum du fichier image :&nbsp;=>&nbsp;<strong>'.$taille_fichier.'</strong> octects et <strong>'.$avatar_size.'</strong> pixels</span>');
   $m->add_checkbox('raz_avatar',translate("Re-activate the standard'avatars"), 1, false, false);
   // ----------------------------------------------------------------------------------------------
}
// ---- AVATAR

$m->add_field('user_from', translate("Your Location"),$userinfo['user_from'],'text',false,100,'','');
$m->add_extender('user_from', '', '<span class="help-block"><span class="float-right" id="countcar_user_from"></span></span>');
$m->add_field('user_occ', translate("Your Occupation"),$userinfo['user_occ'],'text',false,100,'','');
$m->add_extender('user_occ', '', '<span class="help-block"><span class="float-right" id="countcar_user_occ"></span></span>');
$m->add_field('user_intrest', translate("Your Interest"),$userinfo['user_intrest'],'text',false,150,'','');
$m->add_extender('user_intrest', '', '<span class="help-block"><span class="float-right" id="countcar_user_intrest"></span></span>');

// ---- SIGNATURE
$asig = sql_query("SELECT attachsig FROM ".$NPDS_Prefix."users_status WHERE uid='".$userinfo['uid']."'");
list($attsig) = sql_fetch_row($asig);
if ($attsig==1) {$checked=true;} else {$checked=false;}
$m->add_checkbox('attach',translate("Show signature"), 1, false, $checked);
$m->add_field('user_sig', translate("Signature"),$userinfo['user_sig'],'textarea',false,255,4,'','');
$m->add_extender('user_sig', '', '<span class="help-block">'.translate("(255 characters max. Type your signature with HTML coding)").'<span class="float-right" id="countcar_user_sig"></span></span>');
// ---- SIGNATURE

$m->add_field('bio',translate("Extra Info"),$userinfo['bio'],'textarea',false,255,4,'','');
$m->add_extender('bio', '', '<span class="help-block">'.translate("(255 characters max. Type what others can know about yourself)").'<span class="float-right" id="countcar_bio"></span></span>');
$m->add_field('pass', translate("Password"),'','password',false,40,'','');
$m->add_extra('<div class="form-group row"><div class="col-sm-8 ml-sm-auto" ><div class="progress"><div id="passwordMeter_cont" class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%; height: 10px;"></div></div></div></div>');
$m->add_extender('pass', '', '<span class="help-block"><span class="float-right" id="countcar_pass"></span></span>');

$m->add_field('vpass', translate("Retype Password"),'','password',false,40,'','');
$m->add_extender('vpass', '', '<span class="help-block"><span class="float-right" id="countcar_vpass"></span></span>');


// --- EXTENDER
if (file_exists("modules/sform/extend-user/extender/formulaire.php")) {
   include("modules/sform/extend-user/extender/formulaire.php");
}
// --- EXTENDER

// ----------------------------------------------------------------
// CES CHAMPS sont indispensables --- Don't remove these fields
// Champ Hidden
$m->add_field('op','','saveuser','hidden',false);
$m->add_field('uname','',$userinfo['uname'],'hidden',false);
$m->add_field('uid','',$userinfo['uid'],'hidden',false);
// Submit bouton
$m->add_extra('
      <div class="form-group row">
         <div class="col-sm-8 ml-sm-auto" >');
$m->add_field('Submit','',translate('Submit'),'submit',false);

include_once('modules/geoloc/geoloc_conf.php');

$m->add_extra('
         </div>
      </div>');
$m->add_extra('
      <script type="text/javascript" src="lib/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
      <script type="text/javascript" src="lib/bootstrap-datepicker/dist/locales/bootstrap-datepicker.'.language_iso(1,"","").'.min.js"></script>
      <script type="text/javascript">
      //<![CDATA[
         $(document).ready(function() {
            $("<link>").appendTo("head").attr({type: "text/css", rel: "stylesheet",href: "lib/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css"});
            $("#embeddingDatePicker input").datepicker({
               format: "dd/mm/yyyy",
               autoclose: "true",
               language:"'.language_iso(1,'','').'"
            })
            .on("changeDate", function(e) {
               $("#register").formValidation("revalidateField", "T1");
            });
         
            inpandfieldlen("name",60);
            inpandfieldlen("email",60);
            inpandfieldlen("femail",60);
            inpandfieldlen("url",100);
            inpandfieldlen("user_from",100);
            inpandfieldlen("user_occ",100);
            inpandfieldlen("user_intrest",150);
            inpandfieldlen("bio",255);
            inpandfieldlen("user_sig",255);
            inpandfieldlen("pass",40);
            inpandfieldlen("vpass",40);
            inpandfieldlen("C2",40);
            inpandfieldlen("C1",100);
            inpandfieldlen("T1",40);
         });
      //]]>
      </script>');
$fv_parametres ='
         T1: {
            excluded: false,
            validators: {
               date: {
                  format: "DD/MM/YYYY",
                  message: "The date is not a valid"
               }
            }
         },
         pass: {
            validators: {
               callback: {
                  callback: function(value, validator, $field) {
                     var score = 0;
                     if (value === "") {
                        return {
                           valid: true,
                           score: null
                        };
                     }
                     // Check the password strength
                     score += ((value.length >= 8) ? 1 : -1);
                     if (/[A-Z]/.test(value)) {score += 1;}
                     if (/[a-z]/.test(value)) {score += 1;}
                     if (/[0-9]/.test(value)) {score += 1;}
                     if (/[!#$%&^~*_]/.test(value)) {score += 1;}
                     return {
                     valid: true,
                     score: score    // We will get the score later
                     };
                  }
               }
            }
         },
         vpass: {
            validators: {
                identical: {
                    field: "pass",
                    message: "The password and its confirm are not the same"
                }
            }
         },
       '.$ch_lat.': {
            validators: {
               between: {
                  min: -90,
                  max: 90,
                  message: "The latitude must be between -90.0 and 90.0"
               }
            }
         },
         '.$ch_lon.': {
            validators: {
               between: {
                  min: -180,
                  max: 180,
                  message: "The longitude must be between -180.0 and 180.0"
               }
            }
         },';
$m->add_extra(adminfoot('fv',$fv_parametres,'','1'));

// ----------------------------------------------------------------
?>
<?php
/************************************************************************/
/* SFORM Extender for NPDS USER                                         */
/* ===========================                                          */
/* NPDS Copyright (c) 2002-2018 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
/* Dont modify this file if you dont know what you do                   */
/************************************************************************/
$m->add_title(translate("Registration"));
$m->add_mess(translate("* for mandatory field"));
$m->add_form_field_size(50);

$m->add_field('uname', translate("User ID"),$uname,'text',true,25,'','');
$m->add_field('name', translate("Real Name"),$name,'text',false,60,'','');
$m->add_extender('name', '', '<span class="help-block"><span class="float-right" id="countcar_name"></span></span>');
$m->add_field('email', translate("Real Email"),$email,'email',true,60,'','');
$m->add_extender('email', '','<span class="help-block">'.translate("(This Email will not be public but is required, will be used to send your password if you lost it)").'<span class="float-right" id="countcar_email"></span></span>');
$m->add_checkbox('user_viewemail',translate("Allow other users to view my email address"), "1", false, false);

// ---- AVATAR
if ($smilies) {
   global $theme;
   $direktori="images/forum/avatar";
   if (function_exists("theme_image")) {
      if (theme_image("forum/avatar/blank.gif"))
         $direktori="themes/$theme/images/forum/avatar";
   }
   $handle=opendir($direktori);
   while (false!==($file = readdir($handle))) {$filelist[] = $file;}
   asort($filelist);
   while (list ($key, $file) = each ($filelist)) {
      if (!preg_match('#\.gif|\.jpg|\.png$#i', $file)) continue;
         $tmp_tempo[$file]['en']=$file;
         $tmp_tempo[$file]['selected']=false;
         if ($file=='blank.gif') {$tmp_tempo[$file]['selected']=true;}
   }
   $m->add_select('user_avatar', translate("Your Avatar"), $tmp_tempo, false, '', false);
   $m->add_extender('user_avatar', 'onkeyup="showimage();" onchange="showimage();"', '<img class="img-thumbnail n-ava mt-3" src="'.$direktori.'/blank.gif" name="avatar" alt="avatar" />');
   $m->add_field('B1','B1','','hidden',false);
}
// ---- AVATAR

$m->add_field('user_from', translate("Your Location"),StripSlashes($user_from),'text',false,100,'','');
$m->add_extender('user_from', '', '<span class="help-block"><span class="float-right" id="countcar_user_from"></span></span>');

$m->add_field('user_occ', translate("Your Occupation"),StripSlashes($user_occ),'text',false,100,'','');
$m->add_extender('user_occ', '', '<span class="help-block"><span class="float-right" id="countcar_user_occ"></span></span>');
$m->add_field('user_intrest', translate("Your Interest"),StripSlashes($user_intrest),'text',false,150,'','');
$m->add_extender('user_intrest', '', '<span class="help-block"><span class="float-right" id="countcar_user_intrest"></span></span>');

$m->add_field('user_sig', translate("Signature"),StripSlashes($user_sig),'textarea',false,255,'7','');
$m->add_extender('user_sig', '', '<span class="help-block">'.translate("(255 characters max. Type your signature with HTML coding)").'<span class="float-right" id="countcar_user_sig"></span></span>');

// --- MEMBER-PASS
if ($memberpass) {
   $m->add_field('pass', translate("Password"),'','password',true,40,'','');
   $m->add_extra('<div class="form-group row"><div class="col-sm-8 ml-sm-auto" ><div class="progress" style="height: 10px;"><div id="passwordMeter_cont" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div></div></div></div>');
   $m->add_field('vpass', translate("Retype Password"),'','password',true,40,'','');
}

// --- MEMBER-PASS

// --- CHARTE du SITE
$m->add_checkbox('user_lnl',translate("Register to web site' mailing list"), "1", false, true);
$m->add_checkbox('charte','<a href="static.php?op=charte.html" target="_blank" class="text-danger">'.translate("You must accept the terms of use of this website").'</a>', "1", false, false);
// --- CHARTE du SITE

// --- EXTENDER
if (file_exists("modules/sform/extend-user/extender/formulaire.php")) {
   include("modules/sform/extend-user/extender/formulaire.php");
}
// --- EXTENDER

// ----------------------------------------------------------------
// CES CHAMPS sont indispensables --- Don't remove these fields
// Champ Hidden
$m->add_field('op','','new user','hidden',false);
$m->add_extra('
      <div class="form-group row">
         <div class="col-sm-8 ml-sm-auto" >');
$m->add_field('Submit','',translate("Submit"),'submit',false);
$m->add_extra('&nbsp;');
$m->add_field('Reset','',translate("Cancel"),'reset',false);
$m->add_extra('
         </div>
      </div>
      <br />');
$m->add_extra('
      <script type="text/javascript">
      //<![CDATA[
         $(document).ready(function() {
            
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

         })
      //]]>
      </script>');
      $fv_parametres ='
/*
         T1: {
            excluded: false,
            validators: {
               date: {
                  format: "DD/MM/YYYY",
                  message: "The date is not a valid"
               }
            }
         },
*/
/*
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
*/
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
         $arg1='
               var formulid = ["register"];
         '
      
//      $m->add_extra(adminfoot('fv',$fv_parametres,$arg1,'1'));

// ----------------------------------------------------------------
?>
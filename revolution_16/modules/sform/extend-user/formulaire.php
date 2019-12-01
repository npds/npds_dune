<?php
/************************************************************************/
/* SFORM Extender for NPDS USER                                         */
/* ===========================                                          */
/* NPDS Copyright (c) 2002-2019 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
/* Dont modify this file if you dont know what you do                   */
/************************************************************************/
$m->add_title(translate("Registration"));
$m->add_mess(translate("* for mandatory field"));

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
   foreach($filelist as $key => $file) {
//   while (list ($key, $file) = each ($filelist)) {
      if (!preg_match('#\.gif|\.jpg|\.png$#i', $file)) continue;
         $tmp_tempo[$file]['en']=$file;
         $tmp_tempo[$file]['selected']=false;
         if ($file=='blank.gif') $tmp_tempo[$file]['selected']=true;
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
   $m->add_extra('<div class="form-group row"><div class="col-sm-8 ml-sm-auto" ><div class="progress" style="height: 0.2rem;"><div id="passwordMeter_cont" class="progress-bar bg-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div></div></div></div>');
   $m->add_field('vpass', translate("Retype Password"),'','password',true,40,'','');
}

// --- MEMBER-PASS

// --- CHARTE du SITE
$m->add_checkbox('user_lnl',translate("Register to web site' mailing list"), "1", false, true);
$m->add_checkbox('charte','<a href="static.php?op=charte.html" target="_blank">'.translate("You must accept the terms of use of this website").'</a>', "1", true, false);
// --- CHARTE du SITE

// --- EXTENDER
if (file_exists("modules/sform/extend-user/extender/formulaire.php"))
   include("modules/sform/extend-user/extender/formulaire.php");
// --- EXTENDER

// ----------------------------------------------------------------
// CES CHAMPS sont indispensables --- Don't remove these fields
// Champ Hidden
$m->add_field('op','','new user','hidden',false);
$m->add_extra('
      <div class="form-group row">
         <div class="col-sm-8 ml-sm-auto" >
            <button class="btn btn-primary" type="submit">'.translate("Submit").'</button>
         </div>
      </div>');
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
            inpandfieldlen("C2",5);
            inpandfieldlen("C1",100);
            inpandfieldlen("T1",40);
         })
      //]]>
      </script>');
      /*
      test encodage de l'input : btoa(input.value) dans la recherche dans tableau is ok from IE9
      encodé en php dans la fonction autocomplete du mainfile ...
      */
      $fv_parametres ='
         uname: {
            validators: {
               callback: {
                  message: "Ce surnom n\'est pas disponible",
                  callback: function(input) {
                     if($.inArray(btoa(input.value), aruser) !== -1)
                        return false;
                     else
                        return true;
                  }
               }
            }
         },
         pass: {
            validators: {
               checkPassword: {
                  message: "Le mot de passe est trop simple."
               },
            }
         },
         vpass: {
            validators: {
                identical: {
                  compare: function() {
                 return register.querySelector(\'[name="pass"]\').value;
                },
                }
            }
         },
         '.$ch_lat.': {
            validators: {
               regexp: {
                  regexp: /^[-]?([1-8]?\d(\.\d+)?|90(\.0+)?)$/,
                  message: "La latitude doit être entre -90.0 and 90.0"
               },
               numeric: {
                   thousandsSeparator: "",
                   decimalSeparator: "."
               },
               between: {
                  min: -90,
                  max: 90,
                  message: "La latitude doit être entre -90.0 and 90.0"
               }
            }
         },
         '.$ch_lon.': {
            validators: {
               regexp: {
                  regexp: /^[-]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$/,
                  message: "La longitude doit être entre -180.0 and 180.0"
               },
               numeric: {
                   thousandsSeparator: "",
                   decimalSeparator: "."
               },
               between: {
                  min: -180,
                  max: 180,
                  message: "La longitude doit être entre -180.0 and 180.0"
               }
            }
         },
         !###!
         register.querySelector(\'[name="pass"]\').addEventListener("input", function() {
            fvitem.revalidateField("vpass");
         });
         flatpickr("#T1", {
            altInput: true,
            altFormat: "l j F Y",
            maxDate:"today",
            minDate:"'.date("Y-m-d",(time()-3784320000)).'",
            dateFormat:"d/m/Y",
            "locale": "'.language_iso(1,'','').'",
         });
         ';
         $arg1='
         var formulid = ["register"];
         '.auto_complete ('aruser', 'uname', 'users', '', '0');

// ----------------------------------------------------------------
?>
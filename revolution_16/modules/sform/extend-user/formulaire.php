<?php
/************************************************************************/
/* SFORM Extender for NPDS USER                                         */
/* ===========================                                          */
/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */
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
$m->add_field('email', translate("Real Email"),$email,'text',true,60,'','');
$m->add_extra('<div class="row"><div class="col-sm-8 offset-sm-4"><span class="help-block">'.translate("(This Email will not be public but is required, will be used to send your password if you lost it)").'</span></div></div>');

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
   $m->add_extender('user_avatar', "onkeyup=\"showimage();\" onchange=\"showimage();\"", "&nbsp;&nbsp;<img src=\"$direktori/blank.gif\" name=\"avatar\" align=\"top\" title=\"\" />");
   $m->add_field('B1','B1','','hidden',false);
}
// ---- AVATAR

$m->add_field('user_from', translate("Your Location"),StripSlashes($user_from),'text',false,100,'','');
$m->add_field('user_occ', translate("Your Occupation"),StripSlashes($user_occ),'text',false,100,'','');
$m->add_field('user_intrest', translate("Your Interest"),StripSlashes($user_intrest),'text',false,150,'','');
$m->add_checkbox('user_viewemail',translate("Allow other users to view my email address"), "1", false, false);
$m->add_field('user_sig', translate("Signature"),StripSlashes($user_sig),'textarea',false,255,7,'','');

// --- MEMBER-PASS
if ($memberpass) {
   $m->add_field('pass', translate("Password"),'','password',true,40,'','');
   $m->add_extra('<div class="form-group row"><div class="col-sm-8 offset-sm-4" ><progress id="passwordMeter_cont" class="progress password-meter" value="0" max="100"><div class="progress"><span id="passwordMeter" class="progress-bar" style="width: 0%;"></span></div></progress></div></div>');
   $m->add_field('vpass', translate("Retype Password"),'','password',true,40,'','');
}
// --- MEMBER-PASS

// --- CHARTE du SITE
$m->add_checkbox('user_lnl',translate("Register to web site' mailing list"), "1", false, true);
$m->add_checkbox('charte','<a href="static.php?op=charte.html" target="_blank" class="rouge">'.translate("You must accept the terms of use of this website").'</a>', "1", false, false);
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
         <div class="col-sm-8 offset-sm-4" >');
$m->add_field('Submit','',translate("Submit"),'submit',false);
$m->add_extra('&nbsp;');
$m->add_field('Reset','',translate("Cancel"),'reset',false);
$m->add_extra('
         </div>
      </div>
      <br />');
// ----------------------------------------------------------------
?>
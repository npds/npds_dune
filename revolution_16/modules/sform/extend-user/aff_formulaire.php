<?php
/************************************************************************/
/* SFORM Extender for NPDS USER                                         */
/* ===========================                                          */
/* NPDS Copyright (c) 2002-2013 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
/* Dont modify this file is you dont know what you make                 */
/************************************************************************/
$m->add_title(translate("User"));
$m->add_form_field_size(50);

settype($op,'string');
if (($op=="userinfo") and ($user)) {
   global $act_uname;
   $act_uname="<a href='powerpack.php?op=instant_message&amp;to_userid=$uname' title='".translate("Send internal Message")."'>$uname</a>";
   $m->add_field('act_uname', translate("User ID"),"$act_uname",'text',true,25,"","");
} else {
   $m->add_field('uname', translate("User ID"),"$uname",'text',true,25,"","");
}
$m->add_field('name', translate("Real Name"),"$name",'text',false,60,"","");
$m->add_field('email', translate("Real Email"),"$email",'text',true,60,"","");
settype($url,'string');
$url="<a href=\"$url\" target=\"_blank\">$url</a>";
$m->add_field('url',  translate("Your HomePage"),"$url",'text',false,100,"","");

// ---- AVATAR
if ($smilies) {
   $m->add_field('user_avatarX', translate("Your Avatar"),"",'text',true,25,"","");
   if (stristr($user_avatar,"users_private")) {
      $direktori="";
   } else {
      global $theme;
      $direktori="images/forum/avatar/";
      if (function_exists("theme_image")) {
         if (theme_image("forum/avatar/blank.gif"))
            $direktori="themes/$theme/images/forum/avatar/";
      }
   }
   if ($user_avatar)
      $m->add_extender("user_avatarX", "", "<img src=\"".$direktori.$user_avatar."\" name=\"avatar\" align=\"top\" title=\"\" />");
}
// ---- AVATAR

// ---- SHORT-USER
if ($short_user=="yes") {
   $m->add_field('user_icq', translate("Your ICQ"),"$user_icq",'text',false,15,"","");
   $m->add_field('user_aim', translate("Your AIM"),"$user_aim",'text',false,18,"","");
   $m->add_field('user_yim', translate("Your YIM"),"$user_yim",'text',false,50,"","");
   $m->add_field('user_msnm', translate("Your MSNM"),"$user_msnm",'text',false,50,"","");
} else {
   $m->add_field('user_icq',"user_icq","",'hidden',false);
   $m->add_field('user_aim',"user_aim","",'hidden',false);
   $m->add_field('user_yim',"user_yim","",'hidden',false);
   $m->add_field('user_msnm',"user_msnm","",'hidden',false);
}
// ---- SHORT-USER

$m->add_field('user_from', translate("Your Location"),"$user_from",'text',false,100,"","");
$m->add_field('user_occ', translate("Your Occupation"),"$user_occ",'text',false,100,"","");
$m->add_field('user_intrest', translate("Your Interest"),"$user_intrest",'text',false,150,"","");
$m->add_field('user_sig',translate("Signature"),"$user_sig",'textarea',false,255,7,"","");

if ($op=="userinfo")
   $m->add_field('bio',translate("Extra Info"),"$bio",'textarea',false,255,7,"","");
// ----------------------------------------------------------------
?>
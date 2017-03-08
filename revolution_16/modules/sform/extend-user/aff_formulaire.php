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
/* Dont modify this file if you dont know what you make                 */
/************************************************************************/
$m->add_form_field_size(50);

settype($op,'string');
if (($op=="userinfo") and ($user)) {
   global $act_uname;
   $act_uname="<a href='powerpack.php?op=instant_message&amp;to_userid=$uname' title='".translate("Send internal Message")."'>$uname</a>";
   $m->add_field('act_uname', translate("User ID"),$act_uname,'text',true,25,'','');
} else {
   $m->add_field('uname', translate("User ID"),$uname,'text',true,25,'','');
}
$m->add_field('name', translate("Identity"),$name,'text',false,60,'','');
$m->add_field('email', translate("Real Email"),$email,'text',true,60,'','');
settype($url,'string');
$url='<a href="'.$url.'" target="_blank">'.$url.'</a>';
$m->add_field('url',  translate("HomePage"),$url,'text',false,100,'','');
if($user_from!='')
   $m->add_field('user_from', translate("Location"),$user_from,'text',false,100,'','');
if($user_occ!='')
   $m->add_field('user_occ', translate("Occupation"),$user_occ,'text',false,100,'','');
if($user_intrest!='')
   $m->add_field('user_intrest', translate("Interest"),$user_intrest,'text',false,150,'','');
if ($op=='userinfo' and $bio!='')
   $m->add_field('bio',translate("Extra Info"),$bio,'textarea',false,255,7,'','');
if($C7!='')
   $m->add_field('C7','Latitude',$C7,'text',false,100,'','','');
if($C8!='')
   $m->add_field('C8','Longitude',$C8,'text',false,100,'','','');
?>
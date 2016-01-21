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
/* Dont modify this file iF you dont know what you make                 */
/************************************************************************/
global $NPDS_Prefix;
// quand un form est utilisé plusieurs fois dans des context différents add/mod/new les variables ne sont pas toujours defini ce qui entraine des notices php dans les if ...solution peu élégante mais efficace
if(!isset($chng_uname)) $chng_uname='';
if(!isset($chng_name)) $chng_name='';
if(!isset($chng_email)) $chng_email='';
if(!isset($chng_femail)) $chng_femail='';
if(!isset($chng_level)) $chng_level='';
if(!isset($chng_rank)) $chng_rank='';
if(!isset($chng_user_icq)) $chng_user_icq='';
if(!isset($chng_user_aim)) $chng_user_aim='';
if(!isset($chng_user_yim)) $chng_user_yim='';
if(!isset($chng_user_msnm)) $chng_user_msnm='';
if(!isset($chng_user_from)) $chng_user_from='';
if(!isset($chng_user_occ)) $chng_user_occ='';
if(!isset($chng_user_intrest)) $chng_user_intrest='';
if(!isset($attach)) $attach='';
if(!isset($chng_user_sig)) $chng_user_sig='';
if(!isset($chng_bio)) $chng_bio='';
if(!isset($user_lnl)) $user_lnl='';
if(!isset($attach)) $attach='';
if(!isset($chng_user_viewemail)) $chng_user_viewemail='';
if(!isset($mns)) $mns='';
if(!isset($chng_avatar)) $chng_avatar='';
if(!isset($chng_send_email)) $chng_send_email='';
if(!isset($chng_url)) $chng_url='';

$m->add_title(adm_translate("Utilisateur"));
$m->add_mess(adm_translate("* Désigne un champ obligatoire"));
$m->add_form_field_size(60);

// return to the memberslist.php if necessary
$m->add_field("referer","",basename($referer),'hidden',false);

$m->add_field('add_uname', adm_translate("Surnom"),$chng_uname,'text',true,25,'','');
$m->add_field('add_name', adm_translate("Nom"),$chng_name,'text',false,60,'','');
$m->add_field('add_email', adm_translate("E-mail"),$chng_email,'text',false,60,'','');
$m->add_field('add_femail',adm_translate("Adresse E-mail masquée"),$chng_femail,'text',false,60,'','');
if ($op=="ModifyUser")
   $m->add_checkbox('raz_avatar',adm_translate("Revenir aux avatars standards"), 1, false, false);

$r = sql_query("SELECT access_id, access_title FROM ".$NPDS_Prefix."access");
if ($mX = sql_fetch_assoc($r)) {
   do {
      $tmp_tempo[$mX['access_id']]['en']=$mX['access_title'];
      if ($mX['access_id']==$chng_level)
         $tmp_tempo[$mX['access_id']]['selected']=true;
      else
         $tmp_tempo[$mX['access_id']]['selected']=false;

   } while($mX = sql_fetch_assoc($r));
}
$m->add_select("add_level", adm_translate("Niveau de l'Utilisateur"), $tmp_tempo, false, "", false);

// ---- Rôles
unset($tmp_tempo);
$r = sql_query("SELECT rank1, rank2, rank3, rank4, rank5 FROM ".$NPDS_Prefix."config");
list($rank1,$rank2,$rank3,$rank4,$rank5) = sql_fetch_row($r);

$tmp_tempo[0]['en']="-> ".adm_translate("Supprimer")."/".adm_translate("Choisir un rôle")." <-";
if (($chng_rank=="") or ($chng_rank=="0")) $tmp_tempo[0]['selected']=true; else $tmp_tempo[0]['selected']=false;
$tmp_tempo[1]['en']=aff_langue($rank1);
if ($chng_rank==1) $tmp_tempo[1]['selected']=true; else $tmp_tempo[1]['selected']=false;
$tmp_tempo[2]['en']=aff_langue($rank2);
if ($chng_rank==2) $tmp_tempo[2]['selected']=true; else $tmp_tempo[2]['selected']=false;
$tmp_tempo[3]['en']=aff_langue($rank3);
if ($chng_rank==3) $tmp_tempo[3]['selected']=true; else $tmp_tempo[3]['selected']=false;
$tmp_tempo[4]['en']=aff_langue($rank4);
if ($chng_rank==4) $tmp_tempo[4]['selected']=true; else $tmp_tempo[4]['selected']=false;
$tmp_tempo[5]['en']=aff_langue($rank5);
if ($chng_rank==5) $tmp_tempo[5]['selected']=true; else $tmp_tempo[5]['selected']=false;
$m->add_select("chng_rank", adm_translate("Rôle de l'Utilisateur"), $tmp_tempo, false, "", false);

// ---- Groupes
$les_groupes=explode(",",$groupe);
$mX=liste_group();
$nbg=0;
   while (list($groupe_id, $groupe_name)=each($mX)) {
      $tmp_groupe[$groupe_id]['en']=$groupe_name;
      $selectionne=0;
      if ($les_groupes) {
         foreach ($les_groupes as $groupevalue) {
            if (($groupe_id==$groupevalue) and ($groupe_id!=0)) {$selectionne=1;}
         }
      }
      if ($selectionne==1) {$tmp_groupe[$groupe_id]['selected']=true;}
      $nbg++;
   }
if ($nbg>7) {$nbg=7;}
$m->add_select("add_group", adm_translate("Groupe"), $tmp_groupe, false, $nbg, true);
// ---- Groupes

if ($open_user) {$checked=true;} else {$checked=false;}
$m->add_checkbox('add_open_user',adm_translate("Autoriser la connexion"), 1, false, $checked);
if ($mns) {$checked=true;} else {$checked=false;}
$m->add_checkbox('add_mns',adm_translate("Activer son MiniSite"), 1, false, $checked);

// LNL
if ($user_lnl) {$checked=true;} else {$checked=false;}
$m->add_checkbox('user_lnl',translate("Register to web site' mailing list"), 1, false, $checked);
// LNL

if ($chng_user_viewemail) {$checked=true;} else {$checked=false;}
$m->add_checkbox('add_user_viewemail',adm_translate("Autoriser les autres Utilisateurs à voir son adresse E-mail ?"), 1, false, $checked);

$m->add_field('add_url',"URL","$chng_url",'text',false,100,"","");

// ---- SUBSCRIBE and INVISIBLE
if ($chng_send_email==1) {$checked=true;} else {$checked=false;}
$m->add_checkbox('add_send_email',adm_translate("M'envoyer un Mel lorsque qu'un Msg Int. arrive"), 1, false, $checked);
if ($chng_is_visible==1) {$checked=false;} else {$checked=true;}
$m->add_checkbox('add_is_visible',adm_translate("Membre invisible"), 1, false, $checked);
// ---- SUBSCRIBE and INVISIBLE

$m->add_field('add_user_icq', adm_translate("Adresse ICQ"),"$chng_user_icq",'text',false,15,"","");
$m->add_field('add_user_aim', adm_translate("Référence AIM"),"$chng_user_aim",'text',false,18,"","");
$m->add_field('add_user_yim', adm_translate("Référence YIM"),"$chng_user_yim",'text',false,50,"","");
$m->add_field('add_user_msnm', adm_translate("Référence MSNM"),"$chng_user_msnm",'text',false,50,"","");
$m->add_field('add_user_from', adm_translate("Situation géographique"),"$chng_user_from",'text',false,100,"","");
$m->add_field('add_user_occ', adm_translate("Activité"),"$chng_user_occ",'text',false,100,"","");
$m->add_field('add_user_intrest', adm_translate("Centres d'interêt"),"$chng_user_intrest",'text',false,150,"","");

if ($attach==1) {$checked=true;} else {$checked=false;}
$m->add_checkbox('attach',adm_translate("Afficher signature"), 1, false, $checked);
$m->add_field('add_user_sig', adm_translate("Signature")."<br /><span style=\"font-size: 10px;\">".adm_translate("Description :  (255 caractères max)")."</span>","$chng_user_sig",'textarea',false,255,7,"","");
$m->add_field('add_bio',adm_translate("Informations supplémentaires : ")."<br /><span style=\"font-size: 10px;\">".adm_translate("Description :  (255 caractères max)")."</span>","$chng_bio",'textarea',false,255,7,"","");
$m->add_field('add_pass', adm_translate("Mot de Passe"),"",'password',false,40,"","");
if ($op=="ModifyUser")
   $m->add_field('add_pass2', adm_translate("Entrez à nouveau le Mot de Passe")."&nbsp;<span style=\"font-size: 10px;\">".adm_translate("(seulement pour modifications)")."</span>","",'password',false,40,"","");

// --- EXTENDER
if (file_exists("modules/sform/extend-user/extender/formulaire.php")) {
   include("modules/sform/extend-user/extender/formulaire.php");
}
// --- EXTENDER

// ----------------------------------------------------------------
// CES CHAMPS sont indispensables --- Don't remove these fields
// Champ Hidden
if ($op=="displayUsers")
   $m->add_field("op","","addUser",'hidden',false);
if ($op=="ModifyUser") {
   $m->add_field("op","","updateUser",'hidden',false);
   $m->add_field("chng_uid","","$chng_uid",'hidden',false);
}
if ($chng_avatar!="")
   $m->add_field("add_avatar","","$chng_avatar",'hidden',false);
else
   $m->add_field("add_avatar","","blank.gif",'hidden',false);

$m->add_extra("<br />");
// Submit bouton
$m->add_field('Submit',"",adm_translate("Valider"),'submit',false);
//$m->add_extra(adminfoot('fv','','',''));

// ----------------------------------------------------------------
?>
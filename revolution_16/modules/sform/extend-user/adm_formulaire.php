<?php
/************************************************************************/
/* SFORM Extender for NPDS USER                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
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
if(!isset($open_user)) $open_user='';
if(!isset($referer)) $referer='';
if(!isset($groupe)) $groupe='';

$m->add_title(adm_translate("Utilisateur"));
$m->add_mess(adm_translate("* Désigne un champ obligatoire"));
$m->add_form_field_size(60);

// return to the memberslist.php if necessary
$m->add_field('referer','',basename($referer),'hidden',false);

$m->add_field('add_uname', adm_translate("Surnom"),$chng_uname,'text',true,25,'','');
$m->add_extender('add_uname', '', '<span class="help-block"><span class="float-end" id="countcar_add_uname"></span></span>');

$m->add_field('add_name', adm_translate("Nom"),$chng_name,'text',false,60,'','');
$m->add_extender('add_name', '', '<span class="help-block"><span class="float-end" id="countcar_add_name"></span></span>');

$m->add_field('add_email', adm_translate("E-mail"),$chng_email,'email',true,60,'','');
$m->add_extender('add_email', '', '<span class="help-block text-end" id="countcar_add_email"></span>');

$m->add_field('add_femail',adm_translate("Adresse E-mail masquée"),$chng_femail,'email',false,60,'','');
$m->add_extender('add_femail', '', '<span class="help-block"><span class="float-end" id="countcar_add_femail"></span></span>');

if ($op=='ModifyUser')
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
$m->add_select('add_level', adm_translate("Niveau de l'Utilisateur"), $tmp_tempo, false, '', false);

// ---- Rôles
unset($tmp_tempo);
$r = sql_query("SELECT rank1, rank2, rank3, rank4, rank5 FROM ".$NPDS_Prefix."config");
list($rank1,$rank2,$rank3,$rank4,$rank5) = sql_fetch_row($r);

$tmp_tempo[0]['en']='-> '.adm_translate("Supprimer").'/'.adm_translate("Choisir un rôle").' <-';
if (($chng_rank=='') or ($chng_rank=='0')) $tmp_tempo[0]['selected']=true; else $tmp_tempo[0]['selected']=false;
if (!is_null($rank1)) {
   $tmp_tempo[1]['en']=aff_langue($rank1);
   $tmp_tempo[1]['selected'] = $chng_rank==1 ? true : false ;
}
if (!is_null($rank2)) {
   $tmp_tempo[2]['en']=aff_langue($rank2);
   $tmp_tempo[2]['selected'] = $chng_rank==2 ? true : false ;
}
if (!is_null($rank3)) {
   $tmp_tempo[3]['en']=aff_langue($rank3);
   $tmp_tempo[3]['selected'] = $chng_rank==3 ? true : false ;
}
if (!is_null($rank4)) {
   $tmp_tempo[4]['en']=aff_langue($rank4);
   $tmp_tempo[4]['selected'] = $chng_rank==4 ? true : false ;
}
if (!is_null($rank5)) {
   $tmp_tempo[5]['en']=aff_langue($rank5);
   $tmp_tempo[5]['selected'] = $chng_rank==5 ? true : false ;
}
$m->add_select('chng_rank', adm_translate("Rôle de l'Utilisateur"), $tmp_tempo, false, '', false);

// ---- Groupes
$les_groupes=explode(',',$groupe);
$mX=liste_group();
$nbg=0;
   foreach($mX as $groupe_id => $groupe_name) {
      $tmp_groupe[$groupe_id]['en']=$groupe_name;
      $selectionne=0;
      if ($les_groupes) {
         foreach ($les_groupes as $groupevalue) {
            if (($groupe_id==$groupevalue) and ($groupe_id!=0)) $selectionne=1;
         }
      }
      if ($selectionne==1) $tmp_groupe[$groupe_id]['selected']=true;
      $nbg++;
   }
if ($nbg>7) $nbg=7;
$m->add_select('add_group', adm_translate("Groupe"), $tmp_groupe, false, $nbg, true);
// ---- Groupes

if ($open_user) $checked=true; else $checked=false;
$m->add_checkbox('add_open_user',adm_translate("Autoriser la connexion"), 1, false, $checked);
if ($mns) {$checked=true;} else {$checked=false;}
$m->add_checkbox('add_mns',adm_translate("Activer son MiniSite"), 1, false, $checked);

// LNL
$cky = $user_lnl==1 ? true : false;
$ckn = $user_lnl==0 ? true : false;
$tmp = array(
   "1"=>array('en'=>adm_translate("Oui"), 'checked'=>$cky),
   "0"=>array('en'=>adm_translate("Non"), 'checked'=>$ckn),
);
$m->add_radio('user_lnl', translate("S'inscrire à la liste de diffusion du site"), $tmp, false);
// LNL
if ($chng_user_viewemail) $checked=true; else $checked=false;
$m->add_checkbox('add_user_viewemail',adm_translate("Autoriser les autres utilisateurs à voir son adresse E-mail"), 1, false, $checked);

$m->add_field('add_url','URL',$chng_url,'url',false,100,'','');
$m->add_extender('add_url', '', '<span class="help-block text-end" id="countcar_add_url"></span>');

// ---- SUBSCRIBE and INVISIBLE
if ($chng_send_email==1) $checked=true; else $checked=false;
$m->add_checkbox('add_send_email',adm_translate("M'envoyer un Mel lorsque qu'un Msg Int. arrive"), 1, false, $checked);
if ($chng_is_visible==1) $checked=false; else $checked=true;
$m->add_checkbox('add_is_visible',adm_translate("Membre invisible"), 1, false, $checked);
// ---- SUBSCRIBE and INVISIBLE

$m->add_field('add_user_from', adm_translate("Situation géographique"),$chng_user_from,'text',false,100,'','');
$m->add_extender('add_user_from', '', '<span class="help-block text-end" id="countcar_add_user_from"></span>');

$m->add_field('add_user_occ', adm_translate("Activité"),$chng_user_occ,'text',false,100,'','');
$m->add_extender('add_user_occ', '', '<span class="help-block text-end" id="countcar_add_user_occ"></span>');

$m->add_field('add_user_intrest', adm_translate("Centres d'intérêt"),$chng_user_intrest,'text',false,150,'','');
$m->add_extender('add_user_intrest', '', '<span class="help-block text-end" id="countcar_add_user_intrest"></span>');

if ($attach==1) $checked=true; else $checked=false;
$m->add_checkbox('attach',adm_translate("Afficher signature"), 1, false, $checked);
$m->add_field('add_user_sig', adm_translate("Signature"),$chng_user_sig,'textarea',false,255,7,'','');
$m->add_extender('add_user_sig', '', '<span class="help-block text-end" id="countcar_add_user_sig"></span>');

$m->add_field('add_bio',adm_translate("Informations supplémentaires"),$chng_bio,'textarea',false,255,7,'','');
$m->add_extender('add_bio', '', '<span class="help-block text-end" id="countcar_add_bio" ></span>');

$requi='';
if ($op=="ModifyUser") $requi=false; else $requi=true;
$m->add_field('add_pass', adm_translate("Mot de Passe"),'','password',$requi,'40','','');
$m->add_extra('<div class="mb-3 row"><div class="col-sm-8 ms-sm-auto" ><div class="progress" style="height: 0.2rem;"><div id="passwordMeter_cont" class="progress-bar bg-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div></div></div></div>');
$m->add_extender('add_pass', '', '<span class="help-block text-end" id="countcar_add_pass"></span>');

if ($op=="ModifyUser") {
   $m->add_field('add_pass2', adm_translate("Entrez à nouveau le Mot de Passe").'&nbsp;<span class="small">'.adm_translate("(seulement pour modifications)").'</span>','','password',false,40,'','');
   $m->add_extender('add_pass2', '', '<span class="help-block text-end" id="countcar_add_pass2"></span>');
}
// --- EXTENDER
if (file_exists("modules/sform/extend-user/extender/formulaire.php"))
   include("modules/sform/extend-user/extender/formulaire.php");
// --- EXTENDER

// ----------------------------------------------------------------
// CES CHAMPS sont indispensables --- Don't remove these fields
// Champ Hidden
if ($op=='displayUsers')
   $m->add_field('op','','addUser','hidden',false);
if ($op=="ModifyUser") {
   $m->add_field('op','','updateUser','hidden',false);
   $m->add_field("chng_uid",'',$chng_uid,'hidden',false);
}
if ($chng_avatar!='')
   $m->add_field('add_avatar','',$chng_avatar,'hidden',false);
else
   $m->add_field('add_avatar','','blank.gif','hidden',false);
include_once('modules/geoloc/geoloc.conf');

$m->add_extra('
      <div class="mb-3 row">
         <div class="col-sm-8 ms-sm-auto" >
            <button type="submit" class="btn btn-primary">'.translate("Valider").'</button>
         </div>
      </div>
      <script type="text/javascript">
      //<![CDATA[
         $(document).ready(function() {
            inpandfieldlen("add_uname",25);
            inpandfieldlen("add_name",60);
            inpandfieldlen("add_email",60);
            inpandfieldlen("add_femail",60);
            inpandfieldlen("add_user_from",100);
            inpandfieldlen("add_user_occ",100);
            inpandfieldlen("add_user_intrest",150);
            inpandfieldlen("add_bio",255);
            inpandfieldlen("add_user_sig",255);
            inpandfieldlen("add_pass",40);
            inpandfieldlen("add_pass2",40);
            inpandfieldlen("add_url",100);
            inpandfieldlen("C2",5);
            inpandfieldlen("C1",100);
         });
      //]]>
      </script>');
$arg1 ='
      var formulid = ["Register"];';
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
         add_pass: {
            validators: {
               checkPassword: {
                  message: "The password is too weak"
               },
            }
         },';
if ($op=="ModifyUser")
   $fv_parametres .='
         add_pass2: {
            validators: {
               identical: {
                  compare: function() {
                     return Register.querySelector(\'[name="add_pass"]\').value;
                  },
               }
            }
         },';
$fv_parametres .='
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
         !###!';
if ($op=="ModifyUser")
   $fv_parametres .='
         Register.querySelector(\'[name="add_pass"]\').addEventListener("input", function() {
            fvitem.revalidateField("add_pass2");
         });';
$fv_parametres .='
         flatpickr("#T1", {
            altInput: true,
            altFormat: "l j F Y",
            maxDate:"today",
            minDate:"'.date("Y-m-d",(time()-3784320000)).'",
            dateFormat:"d/m/Y",
            "locale": "'.language_iso(1,'','').'",
         });
         ';
$m->add_extra(adminfoot('fv',$fv_parametres,$arg1,'1'));
// ----------------------------------------------------------------
?>
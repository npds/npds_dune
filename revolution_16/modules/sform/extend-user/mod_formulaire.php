<?php
/************************************************************************/
/* SFORM Extender for NPDS USER                                         */
/* ===========================                                          */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
/* Dont modify this file if you dont know what you do                   */
/************************************************************************/
global $NPDS_Prefix,$minpass;

$m->add_title(translate("Utilisateur"));
$m->add_mess(translate("* Désigne un champ obligatoire"));
//$m->add_form_field_size(50);

$m->add_field('name', translate("Votre véritable identité").' '.translate("(optionnel)"),$userinfo['name'],'text',false,60,'','');
$m->add_extender('name', '', '<span class="help-block"><span class="float-end" id="countcar_name"></span></span>');

$m->add_field('email', translate("Véritable adresse Email"),$userinfo['email'],'email',true,60,'','');
$m->add_extender('email', '','<span class="help-block">'.translate("(Cette adresse Email ne sera pas divulguée, mais elle nous servira à vous envoyer votre Mot de Passe si vous le perdez)").'<span class="float-end" id="countcar_email"></span></span>');
$m->add_field('femail',translate("Votre adresse mèl 'truquée'"),$userinfo['femail'],'email',false,60,"","");
$m->add_extender('femail', '','<span class="help-block">'.translate("(Cette adresse Email sera publique. Vous pouvez saisir ce que vous voulez mais attention au Spam)").'<span class="float-end" id="countcar_femail"></span></span>');

if ($userinfo['user_viewemail']) {$checked=true;} else {$checked=false;}
$m->add_checkbox('user_viewemail',translate("Autoriser les autres utilisateurs à voir mon Email"), 1, false, $checked);

$m->add_field('url', translate("Votre page Web"),$userinfo['url'],'url',false,100,'','');
$m->add_extender('url', '','<span class="help-block"><span class="float-end" id="countcar_url"></span></span>');

// ---- SUBSCRIBE and INVISIBLE
include_once('functions.php');
if ($subscribe)
   if(isbadmailuser($userinfo['uid'])===false) {//proto
      if ($userinfo['send_email']==1) $checked=true; else $checked=false;
      $m->add_checkbox('usend_email',translate("M'envoyer un Email lorsqu'un message interne arrive"), 1, false, $checked);
}
if ($member_invisible) {
   if ($userinfo['is_visible']==1) $checked=false; else $checked=true;
   $m->add_checkbox('uis_visible',translate("Membre invisible")." (".translate("pas affiché dans l'annuaire, message à un membre, ...").")", 1, false, $checked);
}
// ---- SUBSCRIBE and INVISIBLE

// LNL
if(isbadmailuser($userinfo['uid'])===false) {//proto
   if ($userinfo['user_lnl']) {$checked=true;} else {$checked=false;}
   $m->add_checkbox('user_lnl',translate("S'inscrire à la liste de diffusion du site"), 1, false, $checked);
}
// LNL

// ---- AVATAR
if ($smilies) {
   if (stristr($userinfo['user_avatar'],"users_private")) {
      $m->add_field('user_avatar',translate("Votre Avatar"), $userinfo['user_avatar'],'show-hidden',false,30,'','');
      $m->add_extender('user_avatar', '', '<img class="img-thumbnail n-ava" src="'.$userinfo['user_avatar'].'" name="avatar" alt="avatar" /><span class="ava-meca lead"><i class="fa fa-angle-right fa-lg text-body-secondary mx-3"></i></span><img class="ava-meca img-thumbnail n-ava" id="ava_perso" src="#" alt="Your next avatar" />
');
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
      foreach($filelist as $key => $file) {
         if (!preg_match('#\.gif|\.jpg|\.jpeg|\.png$#i', $file)) continue;
            $tmp_tempo[$file]['en']=$file;
            if ($userinfo['user_avatar']==$file) {$tmp_tempo[$file]['selected']=true;} else {$tmp_tempo[$file]['selected']=false;}
      }
      $m->add_select('user_avatar',translate("Votre Avatar"), $tmp_tempo, false, '', false);
      $m->add_extender('user_avatar', 'onkeyup="showimage();$(\'#avatar,#tonewavatar\').show();" onchange="showimage();$(\'#avatar,#tonewavatar\').show();"', '<div class="help-block"><img class="img-thumbnail n-ava" src="'.$direktori.'/'.$userinfo['user_avatar'].'" align="top" title="" /><span id="tonewavatar" class="lead"><i class="fa fa-angle-right fa-lg text-body-secondary mx-3"></i></span><img class="img-thumbnail n-ava " src="'.$direktori.'/'.$userinfo['user_avatar'].'" name="avatar" id="avatar" align="top" title="Your next avatar" data-bs-placement="right" data-bs-toggle="tooltip" /><span class="ava-meca lead"><i class="fa fa-angle-right fa-lg text-body-secondary mx-3"></i></span><img class="ava-meca img-thumbnail n-ava" id="ava_perso" src="#" alt="your next avatar" title="Your next avatar" data-bs-placement="right" data-bs-toggle="tooltip" /></div>');
   }

   // Permet à l'utilisateur de télécharger un avatar (photo) personnel
   // - si vous mettez un // devant les deux lignes B1 et raz_avatar celà équivaut à ne pas autoriser cette fonction de NPDS
   // - le champ B1 est impératif ! La taille maxi du fichier téléchargeable peut-être changée (le dernier paramètre) et est en octets (par exemple 20480 = 20 Ko)
   // - on a une incohérence la dimension de l'image est fixé dans les préférences du site et son poids ici....

   $taille_fichier = 81920;
   if (!$avatar_size) $avatar_size='80*100';
   $avatar_wh = explode('*',$avatar_size);
   $m->add_upload('B1', '', '30', $taille_fichier);
   $m->add_extender('B1', '', '<span class="help-block text-end">Taille maximum du fichier image :&nbsp;=>&nbsp;<strong>'.$taille_fichier.'</strong> octets et <strong>'.$avatar_size.'</strong> pixels</span>');
   $m->add_extra('<div id="avatarPreview" class="preview"></div>');
   $m->add_checkbox('raz_avatar',translate("Revenir aux avatars standards"), 1, false, false);
   // ----------------------------------------------------------------------------------------------
}
// ---- AVATAR

$m->add_field('user_from', translate("Votre situation géographique"),$userinfo['user_from'],'text',false,100,'','');
$m->add_extender('user_from', '', '<span class="help-block text-end" id="countcar_user_from"></span>');
$m->add_field('user_occ', translate("Votre activité"),$userinfo['user_occ'],'text',false,100,'','');
$m->add_extender('user_occ', '', '<span class="help-block text-end" id="countcar_user_occ"></span>');
$m->add_field('user_intrest', translate("Vos centres d'intérêt"),$userinfo['user_intrest'],'text',false,150,'','');
$m->add_extender('user_intrest', '', '<span class="help-block text-end" id="countcar_user_intrest"></span>');

// ---- SIGNATURE
$asig = sql_query("SELECT attachsig FROM ".$NPDS_Prefix."users_status WHERE uid='".$userinfo['uid']."'");
list($attsig) = sql_fetch_row($asig);
if ($attsig==1) {$checked=true;} else {$checked=false;}
$m->add_checkbox('attach',translate("Afficher la signature"), 1, false, $checked);
$m->add_field('user_sig', translate("Signature"),$userinfo['user_sig'],'textarea',false,255,4,'','');
$m->add_extender('user_sig', '', '<span class="help-block">'.translate("(255 caractères max. Entrez votre signature (mise en forme html))").'<span class="float-end" id="countcar_user_sig"></span></span>');
// ---- SIGNATURE

$m->add_field('bio',translate("Informations supplémentaires"),$userinfo['bio'],'textarea',false,255,4,'','');
$m->add_extender('bio', '', '<span class="help-block">'.translate("(255 caractères max). Précisez qui vous êtes, ou votre identification sur ce site)").'<span class="float-end" id="countcar_bio"></span></span>');
$m->add_field('pass', translate("Mot de passe"),'','password',false,40,'','');
$m->add_extra('<div class="mb-3 row"><div class="col-sm-8 ms-sm-auto" ><div class="progress" style="height: 0.2rem;"><div id="passwordMeter_cont" class="progress-bar bg-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div></div></div></div>');
$m->add_extender('pass', '', '<span class="help-block text-end" id="countcar_pass"></span>');

$m->add_field('vpass', translate("Entrez à nouveau votre mot de Passe"),'','password',false,40,'','');
$m->add_extender('vpass', '', '<span class="help-block text-end" id="countcar_vpass"></span>');


// --- EXTENDER
if (file_exists("modules/sform/extend-user/extender/formulaire.php"))
   include("modules/sform/extend-user/extender/formulaire.php");
// --- EXTENDER

// ----------------------------------------------------------------
// CES CHAMPS sont indispensables --- Don't remove these fields
// Champ Hidden
$m->add_field('op','','saveuser','hidden',false);
$m->add_field('uname','',$userinfo['uname'],'hidden',false);
$m->add_field('uid','',$userinfo['uid'],'hidden',false);
include_once('modules/geoloc/geoloc.conf');
// --- CONSENTEMENT
$m->add_checkbox('consent',aff_langue('[french]En soumettant ce formulaire j\'accepte que les informations saisies soient exploit&#xE9;es dans le cadre de l\'utilisation et du fonctionnement de ce site.[/french][english]By submitting this form, I accept that the information entered will be used in the context of the use and operation of this website.[/english][spanish]Al enviar este formulario, acepto que la informaci&oacute;n ingresada se utilizar&aacute; en el contexto del uso y funcionamiento de este sitio web.[/spanish][german]Mit dem Absenden dieses Formulars erkl&auml;re ich mich damit einverstanden, dass die eingegebenen Informationen im Rahmen der Nutzung und des Betriebs dieser Website verwendet werden.[/german][chinese]&#x63D0;&#x4EA4;&#x6B64;&#x8868;&#x683C;&#x5373;&#x8868;&#x793A;&#x6211;&#x63A5;&#x53D7;&#x6240;&#x8F93;&#x5165;&#x7684;&#x4FE1;&#x606F;&#x5C06;&#x5728;&#x672C;&#x7F51;&#x7AD9;&#x7684;&#x4F7F;&#x7528;&#x548C;&#x64CD;&#x4F5C;&#x8303;&#x56F4;&#x5185;&#x4F7F;&#x7528;&#x3002;[/chinese]'), "1", true, false);
// --- CONSENTEMENT

$m->add_extra('
      <div class="mb-3 row">
         <div class="col-sm-8 ms-sm-auto" >
            <button type="submit" class="btn btn-primary">'.translate("Valider").'</button>
         </div>
      </div>
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
         });
         $(".ava-meca, #avatar, #tonewavatar").hide();
         function readURL(input) {
           if (input.files && input.files[0]) {
               var reader = new FileReader();
               reader.onload = function (e) {
                   $("#ava_perso").attr("src", e.target.result);
                   $(".ava-meca").show();
               }
            }
            reader.readAsDataURL(input.files[0]);
         }

         $("#B1").change(function() {
            readURL(this);
            $("#user_avatar option[value=\''.$userinfo['user_avatar'].'\']").prop("selected", true);
            $("#user_avatar").prop("disabled", "disabled");
            $("#avatar,#tonewavatar").hide();
            $("#avava .fv-plugins-message-container").removeClass("d-none").addClass("d-block");
         });

         window.reset2 = function (e,f) {
            e.wrap("<form>").closest("form").get(0).reset();
            e.unwrap();
            event.preventDefault();
            $("#B1").removeClass("is-valid is-invalid");
            $("#user_avatar option[value=\''.$userinfo['user_avatar'].'\']").prop("selected", true);
            $("#user_avatar").prop("disabled", false);
            $("#avava").removeClass("fv-plugins-icon-container has-success");
            $(".ava-meca").hide();
            $("#avava .fv-plugins-message-container").addClass("d-none").removeClass("d-block");
         };

      //]]>
      </script>
      ');
$m->add_extra(aff_langue('
      <div class="mb-3 row">
         <div class="col-sm-8 ms-sm-auto small" >
[french]Pour conna&icirc;tre et exercer vos droits notamment de retrait de votre consentement &agrave; l\'utilisation des donn&eacute;es collect&eacute;es veuillez consulter notre <a href="static.php?op=politiqueconf.html&amp;npds=1&amp;metalang=1">politique de confidentialit&eacute;</a>.[/french][english]To know and exercise your rights, in particular to withdraw your consent to the use of the data collected, please consult our <a href="static.php?op=politiqueconf.html&amp;npds=1&amp;metalang=1">privacy policy</a>.[/english][spanish]Para conocer y ejercer sus derechos, en particular para retirar su consentimiento para el uso de los datos recopilados, consulte nuestra <a href="static.php?op=politiqueconf.html&amp;npds=1&amp;metalang=1">pol&iacute;tica de privacidad</a>.[/spanish][german]Um Ihre Rechte zu kennen und auszu&uuml;ben, insbesondere um Ihre Einwilligung zur Nutzung der erhobenen Daten zu widerrufen, konsultieren Sie bitte unsere <a href="static.php?op=politiqueconf.html&amp;npds=1&amp;metalang=1">Datenschutzerkl&auml;rung</a>.[/german][chinese]&#x8981;&#x4E86;&#x89E3;&#x5E76;&#x884C;&#x4F7F;&#x60A8;&#x7684;&#x6743;&#x5229;&#xFF0C;&#x5C24;&#x5176;&#x662F;&#x8981;&#x64A4;&#x56DE;&#x60A8;&#x5BF9;&#x6240;&#x6536;&#x96C6;&#x6570;&#x636E;&#x7684;&#x4F7F;&#x7528;&#x7684;&#x540C;&#x610F;&#xFF0C;&#x8BF7;&#x67E5;&#x9605;&#x6211;&#x4EEC;<a href="static.php?op=politiqueconf.html&#x26;npds=1&#x26;metalang=1">&#x7684;&#x9690;&#x79C1;&#x653F;&#x7B56;</a>&#x3002;[/chinese]
         </div>
      </div>'));
$arg1 ='
      var formulid = ["register"];';
$fv_parametres ='

      B1: {
          validators: {
              file: {
                  extension: "jpeg,jpg,png,gif",
                  type: "image/jpeg,image/png,image/gif",
                  maxSize: '.$taille_fichier.',
                  message: "Type ou/et poids ou/et extension de fichier incorrect"
              },
             promise: {
                   promise: function (input) {
                       return new Promise(function(resolve, reject) {
                           const files = input.element.files
                           if (!files.length || typeof FileReader === "undefined") {
                               resolve({
                                   valid: true
                               });
                           }
                           const img = new Image();
                           img.addEventListener("load", function() {
                               const w = this.width;
                               const h = this.height;

                               resolve({
                                   valid: (w <= '.$avatar_wh[0].' && h <= '.$avatar_wh[1].'),
                                   message: "Dimension(s) incorrecte(s) largeur > '.$avatar_wh[0].' px ou/et hauteur > '.$avatar_wh[1].' px !",
                                   meta: {
                                       source: img.src,    // We will use it later to show the preview
                                       width: w,
                                       height: h,
                                   },
                               });
                           });
                           img.addEventListener("error", function() {
                               reject({
                                   valid: false,
                                   message: "Please choose an image",
                               });
                           });
                           const reader = new FileReader();
                           reader.readAsDataURL(files[0]);
                           reader.addEventListener("loadend", function(e) {
                               img.src = e.target.result;
                           });
                       });
                   }
               },
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
$m->add_extra(adminfoot('fv',$fv_parametres,$arg1,'1'));

// ----------------------------------------------------------------
?>
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
$m->add_title(translate("Inscription"));
$m->add_mess(translate("* Désigne un champ obligatoire"));

$m->add_field('uname', translate("ID utilisateur (pseudo)"),$uname,'text',true,25,'','');
$m->add_field('name', translate("Votre véritable identité"),$name,'text',false,60,'','');
$m->add_extender('name', '', '<span class="help-block"><span class="float-end" id="countcar_name"></span></span>');
$m->add_field('email', translate("Véritable adresse Email"),$email,'email',true,60,'','');
$m->add_extender('email', '','<span class="help-block">'.translate("(Cette adresse Email ne sera pas divulguée, mais elle nous servira à vous envoyer votre Mot de Passe si vous le perdez)").'<span class="float-end" id="countcar_email"></span></span>');
$m->add_checkbox('user_viewemail',translate("Autoriser les autres utilisateurs à voir mon Email"), "1", false, false);

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
      if (!preg_match('#\.gif|\.jpg|\.png$#i', $file)) continue;
         $tmp_tempo[$file]['en']=$file;
         $tmp_tempo[$file]['selected']=false;
         if ($file=='blank.gif') $tmp_tempo[$file]['selected']=true;
   }
   $m->add_select('user_avatar', translate("Votre Avatar"), $tmp_tempo, false, '', false);
   $m->add_extender('user_avatar', 'onkeyup="showimage();" onchange="showimage();"', '<img class="img-thumbnail n-ava mt-3" src="'.$direktori.'/blank.gif" name="avatar" alt="avatar" />');
   $m->add_field('B1','B1','','hidden',false);
}
// ---- AVATAR

$m->add_field('user_from', translate("Votre situation géographique"),StripSlashes($user_from ?? ''),'text',false,100,'','');
$m->add_extender('user_from', '', '<span class="help-block"><span class="float-end" id="countcar_user_from"></span></span>');

$m->add_field('user_occ', translate("Votre activité"),StripSlashes($user_occ ?? ''),'text',false,100,'','');
$m->add_extender('user_occ', '', '<span class="help-block"><span class="float-end" id="countcar_user_occ"></span></span>');
$m->add_field('user_intrest', translate("Vos centres d'intérêt"),StripSlashes($user_intrest ?? ''),'text',false,150,'','');
$m->add_extender('user_intrest', '', '<span class="help-block"><span class="float-end" id="countcar_user_intrest"></span></span>');

$m->add_field('user_sig', translate("Signature"),StripSlashes($user_sig ?? ''),'textarea',false,255,'7','');
$m->add_extender('user_sig', '', '<span class="help-block">'.translate("(255 characters max. Type your signature with HTML coding)").'<span class="float-end" id="countcar_user_sig"></span></span>');

// --- MEMBER-PASS
if ($memberpass) {
   $m->add_field('pass', translate("Mot de passe"),'','password',true,40,'','');
   $m->add_extra('<div class="mb-3 row"><div class="col-sm-8 ms-sm-auto" ><div class="progress" style="height: 0.2rem;"><div id="passwordMeter_cont" class="progress-bar bg-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div></div></div></div>');
   $m->add_field('vpass', translate("Entrez à nouveau votre mot de Passe"),'','password',true,40,'','');
}

// --- MEMBER-PASS

$m->add_checkbox('user_lnl',translate("S'inscrire à la liste de diffusion du site"), "1", false, true);

// --- EXTENDER
if (file_exists("modules/sform/extend-user/extender/formulaire.php"))
   include("modules/sform/extend-user/extender/formulaire.php");
// --- EXTENDER

// ----------------------------------------------------------------
// CES CHAMPS sont indispensables --- Don't remove these fields
// Champ Hidden
$m->add_field('op','','new user','hidden',false);

// --- CHARTE du SITE
$m->add_checkbox('charte','<a href="static.php?op=charte.html" target="_blank">'.translate("Vous devez accepter la charte d'utilisation du site").'</a>', "1", true, false);
// --- CHARTE du SITE
// --- CONSENTEMENT
$m->add_checkbox('consent',aff_langue('[french]En soumettant ce formulaire j\'accepte que les informations saisies soient exploit&#xE9;es dans le cadre de l\'utilisation et du fonctionnement de ce site.[/french][english]By submitting this form, I accept that the information entered will be used in the context of the use and operation of this website.[/english][spanish]Al enviar este formulario, acepto que la informaci&oacute;n ingresada se utilizar&aacute; en el contexto del uso y funcionamiento de este sitio web.[/spanish][german]Mit dem Absenden dieses Formulars erkl&auml;re ich mich damit einverstanden, dass die eingegebenen Informationen im Rahmen der Nutzung und des Betriebs dieser Website verwendet werden.[/german][chinese]&#x63D0;&#x4EA4;&#x6B64;&#x8868;&#x683C;&#x5373;&#x8868;&#x793A;&#x6211;&#x63A5;&#x53D7;&#x6240;&#x8F93;&#x5165;&#x7684;&#x4FE1;&#x606F;&#x5C06;&#x5728;&#x672C;&#x7F51;&#x7AD9;&#x7684;&#x4F7F;&#x7528;&#x548C;&#x64CD;&#x4F5C;&#x8303;&#x56F4;&#x5185;&#x4F7F;&#x7528;&#x3002;[/chinese]'), "1", true, false);
// --- CONSENTEMENT
$m->add_extra('
      <div class="mb-3 row">
         <div class="col-sm-8 ms-sm-auto" >
            <button class="btn btn-primary" type="submit">'.translate("Valider").'</button>
         </div>
      </div>');
$m->add_extra(aff_langue('
      <div class="mb-3 row">
         <div class="col-sm-8 ms-sm-auto small" >
[french]Pour conna&icirc;tre et exercer vos droits notamment de retrait de votre consentement &agrave; l\'utilisation des donn&eacute;es collect&eacute;es veuillez consulter notre <a href="static.php?op=politiqueconf.html&amp;npds=1&amp;metalang=1">politique de confidentialit&eacute;</a>.[/french][english]To know and exercise your rights, in particular to withdraw your consent to the use of the data collected, please consult our <a href="static.php?op=politiqueconf.html&amp;npds=1&amp;metalang=1">privacy policy</a>.[/english][spanish]Para conocer y ejercer sus derechos, en particular para retirar su consentimiento para el uso de los datos recopilados, consulte nuestra <a href="static.php?op=politiqueconf.html&amp;npds=1&amp;metalang=1">pol&iacute;tica de privacidad</a>.[/spanish][german]Um Ihre Rechte zu kennen und auszu&uuml;ben, insbesondere um Ihre Einwilligung zur Nutzung der erhobenen Daten zu widerrufen, konsultieren Sie bitte unsere <a href="static.php?op=politiqueconf.html&amp;npds=1&amp;metalang=1">Datenschutzerkl&auml;rung</a>.[/german][chinese]&#x8981;&#x4E86;&#x89E3;&#x5E76;&#x884C;&#x4F7F;&#x60A8;&#x7684;&#x6743;&#x5229;&#xFF0C;&#x5C24;&#x5176;&#x662F;&#x8981;&#x64A4;&#x56DE;&#x60A8;&#x5BF9;&#x6240;&#x6536;&#x96C6;&#x6570;&#x636E;&#x7684;&#x4F7F;&#x7528;&#x7684;&#x540C;&#x610F;&#xFF0C;&#x8BF7;&#x67E5;&#x9605;&#x6211;&#x4EEC;<a href="static.php?op=politiqueconf.html&#x26;npds=1&#x26;metalang=1">&#x7684;&#x9690;&#x79C1;&#x653F;&#x7B56;</a>&#x3002;[/chinese]
         </div>
      </div>'));
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
<?php
/************************************************************************/
/* SFORM Extender since NPDS SABLE Contact Example                      */
/* ===========================                                          */
/* NPDS Copyright (c) 2002-2020 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

settype($nom,'string');
settype($ad1,'string');
settype($ville,'string');
settype($dpt,'string');
settype($cpt,'string');
settype($tel,'string');
settype($fax,'string');
settype($mob,'string');
settype($email,'string');
settype($act,'string');
settype($des,'string');
settype($subok,'string');

$m->add_title("[french]Contactez-nous[/french][english]Contact us[/english]");
$m->add_field('nom', "[french]Nom / Raison Sociale[/french][english]Name/Corporate name[/english]",$nom,'text',true,150,'','');
$m->add_extender('nom', '', '<span class="help-block text-right" id="countcar_nom"></span>');
$m->add_field('ad1', "[french]Adresse[/french][english]more address[/english]",$ad1,'text',true,150,'','');
$m->add_extender('ad1', '', '<span class="help-block text-right" id="countcar_ad1"></span>');
$m->add_field('ville', "[french]Ville[/french][english]City[/english]",$ville,'text',false,150,'','');
$m->add_extender('ville', '', '<span class="help-block text-right" id="countcar_ville"></span>');
$m->add_field('dpt', "[french]D&#xE9;partement[/french][english]Department[/english]",$dpt,'text',true,50,'','');
$m->add_extender('dpt', '', '<span class="help-block text-right" id="countcar_dpt"></span>');
$m->add_field('cpt', "[french]Code Postal[/french][english]Postal code[/english]",$cpt,'text',true,5,'','');
$m->add_extender('cpt', '', '<span class="help-block text-right" id="countcar_cpt"></span>');
$m->add_field('tel', "[french]Tel[/french][english]Phone[/english]",$tel,'text',true,25,'',"0-9extend");
$m->add_extender('tel', '', '<span class="help-block text-right" id="countcar_tel"></span>');
$m->add_field('fax', "[french]Fax[/french][english]Fax[/english]",$fax,'text',false,25,'',"0-9extend");
$m->add_extender('fax', '', '<span class="help-block text-right" id="countcar_fax"></span>');
$m->add_field('mob', "[french]Mobile[/french][english]Gsm[/english]",$mob,'text',false,25,'',"0-9extend");
$m->add_extender('mob', '', '<span class="help-block text-right" id="countcar_mob"></span>');
$m->add_field('email', "[french]Adresse de messagerie[/french][english]Email address[/english]",$email,'text',true,255,'','email');
$m->add_extender('email', '', '<span class="help-block text-right" id="countcar_email"></span>');
$m->add_field('act', "[french]Activit&#xE9;[/french][english]Activity[/english]",$act,'text',true,150,'','');
$m->add_extender('act', '', '<span class="help-block text-right" id="countcar_act"></span>');
$m->add_field('des', "[french]Description de votre demande[/french][english]Your request[/english]",$des,'textarea',true,430,10,'');
$m->add_extender('des', '', '<span class="help-block text-right" id="countcar_des"></span>');

// ----------------------------------------------------------------
// CES CHAMPS sont indispensables --- Don't remove these fields
// Anti-Spam
$m->add_Qspam();

// --- CONSENTEMENT
$m->add_checkbox('consent',aff_langue('[french]En soumettant ce formulaire j\'accepte que les informations saisies soient exploit&#xE9;es dans le cadre de l\'utilisation et du fonctionnement de ce site.[/french][english]By submitting this form, I accept that the information entered will be used in the context of the use and operation of this website.[/english][spanish]Al enviar este formulario, acepto que la informaci&oacute;n ingresada se utilizar&aacute; en el contexto del uso y funcionamiento de este sitio web.[/spanish][german]Mit dem Absenden dieses Formulars erkl&auml;re ich mich damit einverstanden, dass die eingegebenen Informationen im Rahmen der Nutzung und des Betriebs dieser Website verwendet werden.[/german][chinese]&#x63D0;&#x4EA4;&#x6B64;&#x8868;&#x683C;&#x5373;&#x8868;&#x793A;&#x6211;&#x63A5;&#x53D7;&#x6240;&#x8F93;&#x5165;&#x7684;&#x4FE1;&#x606F;&#x5C06;&#x5728;&#x672C;&#x7F51;&#x7AD9;&#x7684;&#x4F7F;&#x7528;&#x548C;&#x64CD;&#x4F5C;&#x8303;&#x56F4;&#x5185;&#x4F7F;&#x7528;&#x3002;[/chinese]'), "1", true, false);
// --- CONSENTEMENT
$m->add_extra('
      <div class="form-group row">
         <div class="col-sm-8 ml-sm-auto" >');
$m->add_field('reset','',translate("Annuler"),'reset',false);
$m->add_extra('&nbsp;');
$m->add_field('','',"[french]Soumettre[/french][english]Submit[/english]",'submit',false);
$m->add_extra('
         </div>
      </div>');
$m->add_extra(aff_langue('
      <div class="form-group row">
         <div class="col-sm-8 ml-sm-auto small" >
[french]Pour conna&icirc;tre et exercer vos droits notamment de retrait de votre consentement &agrave; l\'utilisation des donn&eacute;es collect&eacute;es veuillez consulter notre <a href="static.php?op=politiqueconf.html&amp;npds=1&amp;metalang=1">politique de confidentialit&eacute;</a>.[/french][english]To know and exercise your rights, in particular to withdraw your consent to the use of the data collected, please consult our <a href="static.php?op=politiqueconf.html&amp;npds=1&amp;metalang=1">privacy policy</a>.[/english][spanish]Para conocer y ejercer sus derechos, en particular para retirar su consentimiento para el uso de los datos recopilados, consulte nuestra <a href="static.php?op=politiqueconf.html&amp;npds=1&amp;metalang=1">pol&iacute;tica de privacidad</a>.[/spanish][german]Um Ihre Rechte zu kennen und auszu&uuml;ben, insbesondere um Ihre Einwilligung zur Nutzung der erhobenen Daten zu widerrufen, konsultieren Sie bitte unsere <a href="static.php?op=politiqueconf.html&amp;npds=1&amp;metalang=1">Datenschutzerkl&auml;rung</a>.[/german][chinese]&#x8981;&#x4E86;&#x89E3;&#x5E76;&#x884C;&#x4F7F;&#x60A8;&#x7684;&#x6743;&#x5229;&#xFF0C;&#x5C24;&#x5176;&#x662F;&#x8981;&#x64A4;&#x56DE;&#x60A8;&#x5BF9;&#x6240;&#x6536;&#x96C6;&#x6570;&#x636E;&#x7684;&#x4F7F;&#x7528;&#x7684;&#x540C;&#x610F;&#xFF0C;&#x8BF7;&#x67E5;&#x9605;&#x6211;&#x4EEC;<a href="static.php?op=politiqueconf.html&#x26;npds=1&#x26;metalang=1">&#x7684;&#x9690;&#x79C1;&#x653F;&#x7B56;</a>&#x3002;[/chinese]
         </div>
      </div>'));
$m->add_extra('
      <script type="text/javascript">
      //<![CDATA[
         $(document).ready(function() {
            inpandfieldlen("nom",150);
            inpandfieldlen("ad1",150);
            inpandfieldlen("ville",150);
            inpandfieldlen("dpt",50);
            inpandfieldlen("cpt",5);
            inpandfieldlen("tel",25);
            inpandfieldlen("fax",25);
            inpandfieldlen("mob",25);
            inpandfieldlen("email",255);
            inpandfieldlen("act",150);
            inpandfieldlen("des",1024);
         });
      //]]>
      </script>');
// ----------------------------------------------------------------
?>
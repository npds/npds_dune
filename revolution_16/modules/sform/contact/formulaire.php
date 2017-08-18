<?php
/************************************************************************/
/* SFORM Extender for NPDS SABLE Contact Example                        */
/* ===========================                                          */
/* NPDS Copyright (c) 2002-2017 by Philippe Brunier                     */
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
settype($ok,'string');

$m->add_form_field_size(50);
$m->add_title("[french]Contactez-nous[/french][english]Contact us[/english]");
$m->add_field('nom', "[french]Nom / Raison Sociale[/french][english]Name/Corporate name[/english]",$nom,'text',true,150,'','');
$m->add_extender('nom', '', '<span class="help-block"><span class="float-right" id="countcar_nom"></span></span>');
$m->add_field('ad1', "[french]Adresse[/french][english]more address[/english]",$ad1,'text',true,150,'','');
$m->add_extender('ad1', '', '<span class="help-block"><span class="float-right" id="countcar_ad1"></span></span>');
$m->add_field('ville', "[french]Ville[/french][english]City[/english]",$ville,'text',false,150,'','');
$m->add_extender('ville', '', '<span class="help-block"><span class="float-right" id="countcar_ville"></span></span>');
$m->add_field('dpt', "[french]D&#xE9;partement[/french][english]Department[/english]",$dpt,'text',true,50,'','');
$m->add_extender('dpt', '', '<span class="help-block"><span class="float-right" id="countcar_dpt"></span></span>');
$m->add_field('cpt', "[french]Code Postal[/french][english]Postal code[/english]",$cpt,'number',true,5,'',"0-9");
$m->add_extender('cpt', '', '<span class="help-block"><span class="float-right" id="countcar_cpt"></span></span>');
$m->add_field('tel', "[french]Tel[/french][english]Phone[/english]",$tel,'text',true,25,'',"0-9extend");
$m->add_extender('tel', '', '<span class="help-block"><span class="float-right" id="countcar_tel"></span></span>');
$m->add_field('fax', "[french]Fax[/french][english]Fax[/english]",$fax,'text',false,25,'',"0-9extend");
$m->add_extender('fax', '', '<span class="help-block"><span class="float-right" id="countcar_fax"></span></span>');
$m->add_field('mob', "[french]Mobile[/french][english]Gsm[/english]",$mob,'text',false,25,'',"0-9extend");
$m->add_extender('mob', '', '<span class="help-block"><span class="float-right" id="countcar_mob"></span></span>');
$m->add_field('email', "[french]Adresse de messagerie[/french][english]Email address[/english]",$email,'email',false,255,'','email');
$m->add_extender('email', '', '<span class="help-block"><span class="float-right" id="countcar_email"></span></span>');
$m->add_field('act', "[french]Activit&#xE9;[/french][english]Activity[/english]",$act,'text',true,150,'','');
$m->add_extender('act', '', '<span class="help-block"><span class="float-right" id="countcar_act"></span></span>');
$m->add_field('des', "[french]Description de votre demande[/french][english]Your request[/english]",$des,'textarea',false,430,10,'','');
$m->add_extender('des', '', '<span class="help-block"><span class="float-right" id="countcar_des"></span></span>');

// ----------------------------------------------------------------
// CES CHAMPS sont indispensables --- Don't remove these fields
// Anti-Spam
$m->add_Qspam();
$m->add_extra('
      <div class="form-group row">
         <div class="col-sm-8 ml-sm-auto" >');
$m->add_field('Reset','',translate("Cancel"),'reset',false);
$m->add_extra('&nbsp;');
$m->add_field('','',"[french]Soumettre[/french][english]Submit[/english]",'submit',false);
$m->add_extra('
         </div>
      </div>');
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
            inpandfieldlen("des",430);
         });
      //]]>
      </script>');
$m->add_extra(adminfoot('fv','','','1'));
// ----------------------------------------------------------------
?>
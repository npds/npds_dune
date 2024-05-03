<?php
/************************************************************************/
/* SFORM Extender for NPDS Contact Example .                            */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
/* Dont modify this file if you dont know what you make                 */
/************************************************************************/

global $ModPath, $ModStart;
$sform_path='modules/sform/';
include_once($sform_path.'sform.php');
global $m;
$m=new form_handler();
//********************
$m->add_form_title('contact');
$m->add_form_id('formcontact');
$m->add_form_method('post');
$m->add_form_check('false');
$m->add_url('modules.php');
$m->add_field('ModStart','',$ModStart,'hidden',false);
$m->add_field('ModPath','',$ModPath,'hidden',false);
$m->add_submit_value('subok');
$m->add_field('subok','','Submit','hidden',false);

/************************************************/
include($sform_path.'contact/formulaire.php');
adminfoot('fv','','var formulid = ["'.$m->form_id.'"];','1');
/************************************************/
// Manage the <form>
switch($subok) {
   case 'Submit':
      settype($message,'string');
      settype($sformret,'string');
      if (!$sformret) {
         $m->make_response();
         //anti_spambot
         if (!R_spambot($asb_question, $asb_reponse, $message)) {
            Ecr_Log('security', 'Contact', '');
            $subok='';
         } else {
            $message=$m->aff_response('','not_echo','');
            global $notify_email;
            send_email($notify_email,"Contact site",aff_langue($message),'','',"html", '');
            echo '
            <div class="alert alert-success">
            '.aff_langue("[french]Votre demande est prise en compte. Nous y r&eacute;pondrons au plus vite[/french][english]Your request is taken into account. We will answer it as fast as possible.[/english][chinese]&#24744;&#30340;&#35831;&#27714;&#24050;&#34987;&#32771;&#34385;&#22312;&#20869;&#12290; &#25105;&#20204;&#20250;&#23613;&#24555;&#22238;&#22797;[/chinese][spanish]Su solicitud es tenida en cuenta. Le responderemos lo m&aacute;s r&aacute;pido posible.[/spanish][german]Ihre Anfrage wird ber&uuml;cksichtigt. Wir werden so schnell wie m&ouml;glich antworten[/german]").'
            </div>';
            break;
         }
      } else
         $subok='';

   default:
      echo aff_langue($m->print_form(''));
      break;
}
?>
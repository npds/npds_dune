<?php
/************************************************************************/
/* SFORM Extender for NPDS Contact Example .                            */
/* ===========================                                          */
/*                                                                      */
/* P. Brunier 2002 - 2015                                               */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
/* Dont modify this file is you dont know what you make                 */
/************************************************************************/

global $ModPath, $ModStart;
$sform_path='modules/sform/';
include_once($sform_path.'sform.php');
global $m;
$m=new form_handler();
//********************
$m->add_form_title('CONTACT');
$m->add_form_method('post');
$m->add_form_check('true');
$m->add_mess(utf8_java("[french] * d&#x00E9;signe un champ obligatoire [/french][english] * indicate an obligatory field [/english]"));
$m->add_submit_value('ok');
$m->add_url('modules.php');
$m->add_field('ModStart','',$ModStart,'hidden',false);
$m->add_field('ModPath','',$ModPath,'hidden',false);

/************************************************/
include($sform_path.'contact/formulaire.php');
/************************************************/
// Manage the <form>
switch($ok) {

   case 'Soumettre':
   case 'Submit':
      if (!$sformret) {
         $m->make_response();
         //anti_spambot
         if (!R_spambot($asb_question, $asb_reponse, $message)) {
            Ecr_Log('security', 'Contact', '');
            $ok='';
         } else {
            $message=$m->aff_response("class=\"ligna\"","not_echo","");
            global $notify_email;
            send_email($notify_email,"Contact site",aff_langue($message),'','',"html");

            echo "<p class=\"lead text-xs-center\">".aff_langue("[french]Votre demande est prise en compte. Nous y r&#xE9;pondrons au plus vite[/french][english]Your request is taken into account. We will answer it as fast as possible.[/english]")."</p>";

            break;
         }
      } else {
         $ok='';
      }

   default:
      echo aff_langue($m->print_form('class="ligna"'));
      break;
}
?>
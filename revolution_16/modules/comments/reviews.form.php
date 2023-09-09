<?php
/************************************************************************/
/* SFORM Extender for Dune comments - Review                            */
/* ===========================                                          */
/*                                                                      */
/* P. Brunier 2002 - 2023                                               */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

// Champ Combo : hauteur = 5 
$tmp=array(
  "a1"=>array('en'=>"1", 'selected'=>false),
  "a2"=>array('en'=>"2", 'selected'=>false),
  "a3"=>array('en'=>"3", 'selected'=>false),
  "a4"=>array('en'=>"4", 'selected'=>false),
  "a5"=>array('en'=>"5", 'selected'=>false),
  "a6"=>array('en'=>"6", 'selected'=>false),
  "a7"=>array('en'=>"7", 'selected'=>true),
  "a8"=>array('en'=>"8", 'selected'=>false),
  "a9"=>array('en'=>"9", 'selected'=>false),
  "a10"=>array('en'=>"10", 'selected'=>false),
);
$m->add_select('score', translate("Note"),$tmp,false,5,false);

// CE CHAMPS est indispensable --- Don't remove this field
// Champ text : Longueur = 800 / TextArea / Obligatoire / Pas de Vérification
$m->add_field('message', translate("Commentaire"),'','textarea',true,800,10,'','');

// ------------------------------------------------------------------------------
// Champs nécessaires au fonctionnement du formulaire / Don't remove these fields
$m->add_title(translate("Note"));
$m->add_field('ModStart','',$ModStart,'hidden',false);
$m->add_field('ModPath','',$ModPath,'hidden',false);
$m->add_field('topic','',$topic,'hidden',false);
$m->add_field('file_name','',$file_name,'hidden',false);
// Submit bouton and anti-spam
$m->add_extra('<tr><td align="center" colspan="2"><br />');
$m->add_extra_hidden(Q_spambot()."&nbsp;&nbsp;");
$m->add_field('SubmitS',"",translate("Valider"),'submit',false);
$m->add_extra('</td></tr>');
// ------------------------------------------------------------------------------
?>
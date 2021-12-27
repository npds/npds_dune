<?php
/************************************************************************/
/* SFORM Extender for NPDS FORUM                                        */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2020 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

// ---------------------------------------------------------------------
// CE CHAMPS est indispensable --- Don't remove this field
// Champ text : Longueur = 50 / obligatoire / Pas de vérification
$m->add_field('subject', 'Sujet','','text',true,50,'','');
// ---------------------------------------------------------------------

// Titre de la Grille de Formulaire
$m->add_title("NPDS Forum Extender :: Probl&egrave;mes ");

// Champ Combo : hauteur = 4 / Pas d'option par défaut / titre "Votre Système d'Exploitation"
$tmp=array(
  "a1"=>array('en'=>"-: Linux ", 'selected'=>false),
  "a2"=>array('en'=>"-: Windows Seven ", 'selected'=>false),
  "a3"=>array('en'=>"-: Windows Vista ", 'selected'=>false),
  "a4"=>array('en'=>"-: windows XP", 'selected'=>false),
  "a5"=>array('en'=>"-: Windows 2K ", 'selected'=>false),
  "a6"=>array('en'=>"-: Mac OS ", 'selected'=>false),
  "a7"=>array('en'=>"-: Autres ", 'selected'=>false),
);
$m->add_select('t1', "Votre Syst&egrave;me d'Exploitation",$tmp,false,4,false);

// Champ Combo : hauteur = 2 / Pas d'option par défaut / titre "Votre Package Web"
$tmp=array(
  "b1"=>array('en'=>"-: EasyPhp", 'selected'=>false),
  "b2"=>array('en'=>"-: WAMP ", 'selected'=>false),
  "b3"=>array('en'=>"-: XAMP ", 'selected'=>false),
  "b4"=>array('en'=>"-: Apache + Php + MySql ", 'selected'=>false),
  "b5"=>array('en'=>"-: Autres ", 'selected'=>false),
  "b6"=>array('en'=>"-: Je ne sais pas ! ", 'selected'=>false),
);
$m->add_select('t2', "Votre Package Web",$tmp,false,2,false);

// Champ Combo : hauteur = 2 / Pas d'option par défaut / titre "Votre version de PHP"
$tmp=array(
  "c1"=>array('en'=>"-: Php 3.x ", 'selected'=>false),
  "c2"=>array('en'=>"-: Php 4.x ", 'selected'=>false),
  "c3"=>array('en'=>"-: Php 5.x ", 'selected'=>false),
  "c4"=>array('en'=>"-: Php 7.x ", 'selected'=>false),
  "c5"=>array('en'=>"-: Je ne sais pas ! ", 'selected'=>false),
);
$m->add_select('t3', "Votre version de PHP",$tmp,false,2,false);

// Champ Combo : hauteur = 5 / Pas d'option par défaut / Mutli-selection active / titre "Version NPDS (voir statistiques du site)"
$tmp=array(
  "n1"=>array('en'=>"-: NPDS 4.x ", 'selected'=>false),
  "n2"=>array('en'=>"-: NPDS 5.x ", 'selected'=>false),
  "n3"=>array('en'=>"-: NPDS Evolution ", 'selected'=>false),
  "n4"=>array('en'=>"-: NPDS REvolution ", 'selected'=>false),
  "n5"=>array('en'=>"-: NPDS REvolution 11 ", 'selected'=>false),
  "n6"=>array('en'=>"-: NPDS RÉvolution 11 WS ", 'selected'=>false),
  "n7"=>array('en'=>"-: NPDS RÉvolution 13 ", 'selected'=>false),
  "n8"=>array('en'=>"-: NPDS RÉvolution 16.0 ", 'selected'=>false),
  "n9"=>array('en'=>"-: NPDS RÉvolution 16.1 ", 'selected'=>false),
  "n10"=>array('en'=>"-: NPDS RÉvolution 16.2 ", 'selected'=>false),
);
$m->add_select('t4', 'Version NPDS (<a href="stats.php">voir statistiques du site</a>)',$tmp,false,4,true);

// Champ Combo : hauteur = 6 / Pas d'option par défaut / titre "Type de problème"
$tmp=array(
  "d1"=>array('en'=>"-: Installation ", 'selected'=>false),
  "d2"=>array('en'=>"-: Erreur(s) MySql ", 'selected'=>false),
  "d3"=>array('en'=>"-: Erreur(s) Php ", 'selected'=>false),
  "d4"=>array('en'=>"-: Utilisation ", 'selected'=>false),
  "d5"=>array('en'=>"-: Administration ", 'selected'=>false),
  "d6"=>array('en'=>"-: Autre(s) Erreur(s) ", 'selected'=>false),
);
$m->add_select('t5', "Type de probl&egrave;me",$tmp,false,6,false);

// Champ Combo : hauteur = 5 / Pas d'option par défaut / titre "Votre hébergeur"
$tmp=array(
  "e1"=>array('en'=>"-: Free ", 'selected'=>false),
  "e2"=>array('en'=>"-: OVH ", 'selected'=>false),
  "e3"=>array('en'=>"-: Amen ", 'selected'=>false),
  "e4"=>array('en'=>"-: Chez-moi via ADSL ", 'selected'=>false),
  "e5"=>array('en'=>"-: Autre(s) ", 'selected'=>false),
);
$m->add_select('t6', 'Votre h&eacute;bergeur',$tmp,false,5,false);

// Champ Radio : Option par défaut = "OK, j'attends" / titre "Votre Priorité"
$tmp=array(
  "f1"=>array('en'=>"Urgentissime ", 'checked'=>false),
  "f2"=>array('en'=>"Urgent ", 'checked'=>false),
  "f3"=>array('en'=>"OK, j'attends ", 'checked'=>true),
  "f4"=>array('en'=>"Juste une infos ", 'checked'=>false),
);
$m->add_radio('r1', "Votre Priorité", $tmp, false);

// ---------------------------------------------------------------------
// CE CHAMP est indispensable --- Don't remove this field
// Champ text : Longueur = 800 / TextArea / Obligatoire / Pas de Vérification
$m->add_field('message', "Description du problème",'','textarea',true,800,15,'','');
// ---------------------------------------------------------------------

// Champ Boite à cocher / Valeur de retour true / coché
$m->add_checkbox('sig', "Inclure la signature", 'Oui', false, true);
// Champ Boite à cocher / Valeur de retour true / non-coché

$m->add_checkbox('notify2', "Notification par Email", 'Oui', false, false);

// ----------------------------------------------------------------
// CES CHAMPS sont indispensables --- Don't remove these fields
// Champ Hidden
$m->add_field('forum','',$forum,'hidden',false);

$m->add_extra('<br />');
// Anti-Spam
$m->add_Qspam();
// Reset bouton
$m->add_extra('
      <div class="mb-3 row">
         <div class="col-sm-8 ms-sm-auto" >');
$m->add_field('Reset','',translate("Annuler"),'reset',false);
$m->add_extra("&nbsp;&nbsp;&nbsp;");
// Submit bouton
$m->add_field('Submit','','Soumettre','submit',false);
$m->add_extra('
         </div>
      </div>');
// ----------------------------------------------------------------
?>
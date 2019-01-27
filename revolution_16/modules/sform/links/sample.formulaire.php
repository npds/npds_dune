<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2019 by Philippe Brunier   */
/*                                                                      */
/* New Links.php Module with SFROM extentions                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

// Titre de la Grille de Formulaire
$m->add_title('Fiche Compl&eacute;mentaire');

// Champ text : Longueur = 40 / Pas de vérification
$m->add_field('rso', 'Raison Sociale','','text',false,40,'','');

// Champ text : Longueur = 50 / Pas de vérification
$m->add_field('ad1', 'Adresse','','text',false,50,'','');

// Champ text : Longueur = 50 / Pas de vérification
$m->add_field('ad2', 'Adresse','','text',false,50,'','');

// Champ text : Longueur = 25 / Pas de vérification
$m->add_field('dpt', 'D&eacute;partement','','text',false,25,'','');

// Champ text : Longueur = 5 / Numérique seulement
$m->add_field('cpt', 'Code Postal','','text',false,5,'','0-9');

// Champ text : Longueur = 40 / Numérique étendu seulement
$m->add_field('fax', 'Fax','','text',false,40,'','0-9extend');

// Champ text : Longueur = 70 / Email seulement
$m->add_field('email', 'Adresse de messagerie','','text',false,50,'','email');

// Champ text : Longueur = 400 / TextArea / Pas de Vérification
$m->add_field('des', 'Description','','textarea',false,400,6,'','');

// Champ Combo : hauteur = 5 / option par défaut = Linux / titre 'Compétence techniques'
$tmp=array(
  's1'=>array('en'=>'Windows 9.x', 'selected'=>false),
  's2'=>array('en'=>'Windows NT', 'selected'=>false),
  's3'=>array('en'=>'windows 2000', 'selected'=>false),
  's4'=>array('en'=>'Linux', 'selected'=>true),
  's5'=>array('en'=>'Mac OS', 'selected'=>false),
  's6'=>array('en'=>'OS2', 'selected'=>false),
  's7'=>array('en'=>'Autres : BeOs, Prologue ...', 'selected'=>false)
);
$m->add_select('f4', 'Comp&eacute;tences techniques',$tmp,false,5,true);

// Champ Combo : hauteur = 1 / Pas d'option par défaut / titre 'Niveau de service'
$tmp=array(
  't1'=>array('en'=>'Installation', 'selected'=>false),
  't2'=>array('en'=>'Administration / Exploitation', 'selected'=>false),
  't3'=>array('en'=>'Maintenance', 'selected'=>false),
);
$m->add_select('f5', 'Niveau de service',$tmp,false,1,false);

// Champ Radio : Option par défaut = 'Hot Line' / titre 'Type d'Option''
$tmp=array(
  's1'=>array('en'=>'Hot Line', 'checked'=>true),
  's2'=>array('en'=>'Maintenance sur Site', 'checked'=>false)
);
$m->add_radio('f6', "Type d'Option", $tmp, false);

// Champ Boite à cocher / Valeur de retour 100 / coché
$m->add_checkbox('f7', 'Centre de Formation Agr&eacute;&eacute;', '100', false, true);

// Commentaire
$m->add_comment("<p class=\"text-center\">Ces informations sont publiques, mais vous disposez d'un droit permanent de modification.</p>");
?>
<?php
/************************************************************************/
/* SFORM Extender for NPDS USER                                         */
/* ===========================                                          */
/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

/************************************************************************/
/* Dont modify this file is you dont know what you make                 */
/************************************************************************/
/* Utilise une table complémentaire de la table user : users_extend
   C1  varchar(255)
   C2  varchar(255)
   C3  varchar(255)
   C4  varchar(255)
   C5  varchar(255)
   C6  varchar(255)
   C7  varchar(255)
   C8  varchar(255)

   M1  mediumtext
   M2  mediumtext // utilisé pour les réseaux sociaux

   T1  varchar(10) date standard
   T2  varchar(14) peut stocker un TimeStamp

   B1  BLOB peut stocker des fichiers (gif, exe ...)

   ==> Le nom des champs (C1, C2, M1, T1 ...) est IMPERATIF
   ==> un formulaire valide doit contenir au moins C1 ou M1 ou T1
*/

if(!isset($C1)) $C1='';
if(!isset($C2)) $C2='';
if(!isset($T1)) $T1='';
if(!isset($M2)) $M2='';

$m->add_comment(aff_langue('<div class="row"><p class="lead">[french]En savoir plus[/french][english]More[/english]</p></div>'));

$m->add_field('C1', aff_langue('[french]Activit&#x00E9; professionnelle[/french][english]Professional activity[/english]'),$C1,'text',false,100,'','');
$m->add_extender('C1', '', '<span class="help-block"><span class="pull-xs-right" id="countcar_C1"></span></span>');

$m->add_field('C2',aff_langue('[french]Code postal[/french][english]Postal code[/english]'), $C2, 'text',false,5,'','');
$m->add_extender('C2', '', '<span class="help-block"><span class="pull-xs-right" id="countcar_C2"></span></span>');

$m->add_date('T1', aff_langue('[french]Date de naissance[/french][english]Birth date[/english]'),$T1,'date','',false,20);
$m->add_extender('T1', '','<span class="help-block">JJ/MM/AAAA</span>');
$m->add_extra('<div class="form-group row collapse">');
$m->add_field('M2',"R&#x00E9;seaux sociaux",$M2,'text',false);
$m->add_extra('</div>');

$m->add_comment(aff_langue('<div class="row"><p class="lead"><a href="modules.php?ModPath=geoloc&amp;ModStart=geoloc"><i class="fa fa-map-marker fa-2x" title="[french]Modifier ou d&#xE9;finir votre position[/french][english]Define or change your geolocation[/english][chinese]Define or change your geolocation[/chinese]" data-toggle="tooltip"></i></a>&nbsp;[french]G&#xE9;olocalisation[/french][english]Geolocation[/english][chinese][/chinese][spanish][/spanish]</p></div>'));
$m->add_field('C7',aff_langue('[french]Latitude[/french][english]Latitude[/english][chinese]&#x7ECF;&#x5EA6;[/chinese]'),$C7,'text',false);
$m->add_field('C8',aff_langue('[french]Longitude[/french][english]Longitude[/english][chinese]&#x7EAC;&#x5EA6;[/chinese]'),$C8,'text',false);

// Si vous avez besoin des champs ci-dessous - les définir selon vos besoins - sinon les laisser en hidden
$m->add_field('C3','C3','','hidden',false);
$m->add_field('C4','C4','','hidden',false);
$m->add_field('C5','C5','','hidden',false);
$m->add_field('C6','C6','','hidden',false);
// idem pour les champ Mx
$m->add_field('M1','M1','','hidden',false);
$m->add_field('T2','T2','','hidden',false);

// Les champ B1 et M2 sont utilisé par NPDS dans le cadre des fonctions USERs
?>
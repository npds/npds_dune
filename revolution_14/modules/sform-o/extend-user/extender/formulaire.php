<?php
/************************************************************************/
/* SFORM Extender for NPDS USER                                         */
/* ===========================                                          */
/* NPDS Copyright (c) 2002-2010 by Philippe Brunier                     */
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
   M2  mediumtext

   T1  varchar(10) date standard
   T2  varchar(14) peut stocker un TimeStamp

   B1  BLOB peut stocker des fichiers (gif, exe ...)

   ==> Le nom des champs (C1, C2, M1, T1 ...) est IMPERATIF
   ==> un formulaire valide doit contenir au moins C1 ou M1 ou T1
*/

$m->add_comment("<p class=\"lignb\" align=\"center\"> .: Pour en savoir plus sur vous (facultatif) :. </p>");

$m->add_field('C1', "Votre activit&#x00E9; professionnelle",$C1,'text',false,100,"","");
$m->add_field('C2',"Code postal", $C2, 'text',false,5,"","");

// Si vous avez besoin des champs ci-dessous - les definir celon vos besoins - sinon les laisser en hidden
$m->add_field('C3',"C3","",'hidden',false);
$m->add_field('C4',"C4","",'hidden',false);
$m->add_field('C5',"C5","",'hidden',false);
$m->add_field('C6',"C6","",'hidden',false);
$m->add_field('C7',"C7","",'hidden',false);
$m->add_field('C8',"C8","",'hidden',false);
// idem pour les champ Mx
$m->add_field('M1',"M1","",'hidden',false);
$m->add_field('M2',"M2","",'hidden',false);

$m->add_date('T1', "Date de naissance",$T1,'date',"",false,20); $m->add_extender("T1", ""," au format JJ/MM/AAAA");

// Si vous avez besoin du champ ci-dessous - le definir celon vos besoins - sinon le laisser en hidden
$m->add_field('T2',"T2","",'hidden',false);

// Le champ B1 est utilisé par NPDS dans le cadre des fonctions USERs
?>
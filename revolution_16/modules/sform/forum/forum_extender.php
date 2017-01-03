<?php
/************************************************************************/
/* SFORM Extender for NPDS V Forum .                                    */
/* ===========================                                          */
/*                                                                      */
/* P. Brunier 2002 - 2017                                               */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
/* Dont modify this file is you dont know what you make                 */
/************************************************************************/

$sform_path="modules/sform/";
include_once($sform_path."sform.php");

global $m;
$m=new form_handler();
//********************
$m->add_form_title("Bugs_Report");
$m->add_form_method("post");
$m->add_form_check("false");
$m->add_mess(" * d�signe un champ obligatoire ");
$m->add_submit_value("submitS");
$m->add_url("newtopic.php");

/************************************************/
include($sform_path."forum/$formulaire");
/************************************************/

if (!$submitS) {
   echo $m->print_form("class=\"ligna\"");
} else {
   $message=$m->aff_response("class=\"ligna\"","not_echo","");
}
?>
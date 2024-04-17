<?php
/************************************************************************/
/* SFORM Extender for Dune comments.                                    */
/* ===========================                                          */
/*                                                                      */
/* P. Brunier 2002 - 2024                                               */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
/* Dont modify this file is you dont know what you make                 */
/************************************************************************/
include_once("modules/sform/sform.php");

global $m;
$m=new form_handler();
//********************
$m->add_form_title("coolsus");
$m->add_form_method("post");
$m->add_form_check("false");
$m->add_mess("[french]* dŽsigne un champ obligatoire[/french][english]* required field[/english]");
$m->add_submit_value("submitS");
$m->add_url("modules.php");

/************************************************/
include("modules/comments/$formulaire");
/************************************************/

if( !isset($GLOBALS["submitS"]) )
  echo aff_langue($m->print_form(''));
else
  $message=aff_langue($m->aff_response('',"not_echo",''));
?>
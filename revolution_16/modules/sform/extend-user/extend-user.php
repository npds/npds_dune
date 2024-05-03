<?php
/************************************************************************/
/* SFORM Extender for NPDS USER                                         */
/* ===========================                                          */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
/* Dont modify this file is you dont know what you make                 */
/************************************************************************/
$sform_path='modules/sform/';
include_once($sform_path.'sform.php');

global $m;
$m=new form_handler();
//********************
$m->add_form_title('Register');
$m->add_form_id('register');
$m->add_form_method('post');
$m->add_form_check('false');
$m->add_url('user.php');

/************************************************/
include($sform_path.'extend-user/formulaire.php');
/************************************************/
echo $m->print_form('');
?>
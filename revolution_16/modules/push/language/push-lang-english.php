<?php
/************************************************************************/
/* Language File for PUSH ADDON                                         */
/* This PHPNuke ADDON by Christopher Bradford (csb@wpsf.com) 2001       */
/* Be careful when making changes... pda browser platforms such as      */
/* Avantgo and others support as limited HTML set.                      */
//* ===========================                                         */
/*                                                                      */
/* File Copyright (c) 2001 - 2024 by Philippe Brunier                   */
/*                                                                      */
/************************************************************************/

function push_translate($phrase) {
   $tmp=push_translate_pass1($phrase);
   return ($tmp);
}

function push_translate_pass1($phrase) {
   return $phrase;
}
?>
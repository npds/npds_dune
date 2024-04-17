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
 switch($phrase) {
   case "Latest Poll Results": $tmp = "Dernier sondage"; break;
   case "Latest Articles": $tmp = "Derniers articles"; break;
   case "Total Votes:": $tmp = "Total des votes :"; break;
   case "Poll": $tmp = "Sondage"; break;
   case "Home": $tmp = "Retour"; break;
   case "Posted by": $tmp = "Posté par"; break;
   case "Member(s)": $tmp = "Annuaire"; break;
   case "Internal": $tmp = "Interne"; break;
   case "Next": $tmp = "Suite"; break;
   case "Web links": $tmp = "Liens Web"; break;

   default: $tmp = "nécessite une traduction <b>[** $phrase **]</b>"; break;
 }
 return $tmp;
}
?>
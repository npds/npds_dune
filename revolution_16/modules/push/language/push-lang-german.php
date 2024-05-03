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
   case "Latest Poll Results": $tmp = "Letzte Umfrage Ergebnisse"; break;
   case "Latest Articles": $tmp = "Letzte Artikel"; break;
   case "Total Votes:": $tmp = "Stimmen total :"; break;
   case "Poll": $tmp = "Umfrage"; break;
   case "Home": $tmp = "Zurück"; break;
   case "Posted by": $tmp = "Eingesendet von"; break;
   case "Member(s)": $tmp = "Benutzer"; break;
   case "Internal": $tmp = "Intern"; break;
   case "Next": $tmp = "Weiter"; break;
   case "Web links": $tmp = "Weblinks"; break;

   default: $tmp = "erfordert eine Übersetzung <b>[** $phrase **]</b>"; break;
 }
 return $tmp;
}
?>
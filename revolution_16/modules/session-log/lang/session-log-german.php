<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Session and log Viewer Copyright (c) 2004 - Tribal-Dolphin           */
/*                                                                      */
/* NPDS Copyright (c) 2002-2019 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

function SessionLog_translate($phrase) {
 switch ($phrase) {
   case "Liste des Sessions" : $tmp = "Liste der Sessions"; break;
   case "Nom" : $tmp = "Name"; break;
   case "@ IP" : $tmp = "@IP"; break;
   case "@ IP résolue" : $tmp = "entschlossen @IP"; break;
   case "Infos" : $tmp = "Infos"; break;
   case "Liste des Logs" : $tmp = "Liste der Logs"; break;
   case "SECURITE" : $tmp = "Sicherheit"; break;
   case "TELECHARGEMENT" : $tmp = "UPLOAD"; break;
   case "Gestion des Logs" : $tmp = "Log-Management"; break;
   case "Fournisseur" : $tmp = "FAI"; break;
   case "Informations sur l'IP" : $tmp = "Informationen ¸ber IP"; break;
   case "Vider le fichier" : $tmp = "Leere Datei"; break;
   case "Recevoir le fichier par mail" : $tmp = "Erhalten Sie die Datei per E-Mail"; break;
   case "Effacer les fichiers temporaires" : $tmp = "Temporäre Dateien löschen"; break;
   case "Fichier de Log de" : $tmp = "Log-Datei für"; break;

   default: $tmp = "Es gibt keine Übersetzung [** $phrase **]"; break;
 }
  return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
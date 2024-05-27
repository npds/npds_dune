<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Session and log Viewer Copyright (c) 2004 - Tribal-Dolphin           */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

function SessionLog_translate($phrase) {
   switch ($phrase) {
      case "@ IP résolue" : $tmp = "entschlossen @IP"; break;
      case "@ IP" : $tmp = "@IP"; break;
      case "Agent utilisateur" : $tmp = "User-Agent"; break;
      case "Agent" : $tmp = "User-Agent"; break;
      case "cette adresse IP a été déconnectée et bannie !" : $tmp = "Diese IP-Adresse wurde getrennt und gesperrt!"; break;
      case "Déconnecter et bannir cette adresse IP !" : $tmp = "Trennen und sperren Sie diese IP-Adresse!"; break;
      case "Effacer les fichiers temporaires" : $tmp = "Temporäre Dateien löschen"; break;
      case "Fichier de Log de" : $tmp = "Log-Datei für"; break;
      case "Fournisseur" : $tmp = "FAI"; break;
      case "Gestion des Logs" : $tmp = "Log-Management"; break;
      case "Informations sur l'IP" : $tmp = "Informationen ¸ber IP"; break;
      case "Infos" : $tmp = "Infos"; break;
      case "Liste des Logs" : $tmp = "Liste der Logs"; break;
      case "Liste des Sessions" : $tmp = "Liste der Sessions"; break;
      case "Nom" : $tmp = "Name"; break;
      case "Recevoir le fichier par mail" : $tmp = "Erhalten Sie die Datei per E-Mail"; break;
      case "SECURITE" : $tmp = "Sicherheit"; break;
      case "TELECHARGEMENT" : $tmp = "UPLOAD"; break;
      case "Vider le fichier" : $tmp = "Leere Datei"; break;
      case "Vide la table des sessions et interrompt les connexions." : $tmp = "Leert die Sitzungstabelle und unterbricht Verbindungen."; break;
      default: $tmp = "Es gibt keine Übersetzung [** $phrase **]"; break;
   }
   return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
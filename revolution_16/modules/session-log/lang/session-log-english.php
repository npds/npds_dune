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
      case "@ IP résolue" : $tmp = "Resolved @IP"; break;
      case "@ IP" : $tmp = "@IP"; break;
      case "Agent utilisateur" : $tmp = "User agent"; break;
      case "Agent" : $tmp = "Agent"; break;
      case "cette adresse IP a été déconnectée et bannie !" : $tmp = "this IP address has been disconnected and banned!"; break;
      case "Déconnecter et bannir cette adresse IP !" : $tmp = "Disconnect and ban this IP address!"; break;
      case "Effacer les fichiers temporaires" : $tmp = "Erase temporary files"; break;
      case "Fichier de Log de" : $tmp = "Log File for"; break;
      case "Fournisseur" : $tmp = "FAI"; break;
      case "Gestion des Logs" : $tmp = "Logs' administration"; break;
      case "Informations sur l'IP" : $tmp = "IP informations"; break;
      case "Infos" : $tmp = "Infos"; break;
      case "Liste des Logs" : $tmp = "Logs List"; break;
      case "Liste des Sessions" : $tmp = "Sessions List"; break;
      case "Nom" : $tmp = "Name"; break;
      case "Recevoir le fichier par mail" : $tmp = "Send file by email"; break;
      case "SECURITE" : $tmp = "SECURITY"; break;
      case "TELECHARGEMENT" : $tmp = "UPLOAD"; break;
      case "Vider le fichier" : $tmp = "Empty the file"; break;
      case "Vide la table des sessions et interrompt les connexions." : $tmp = "Empties the session table and interrupts connections."; break;
      default: $tmp = "Translation error [** $phrase **]"; break;
   }
   return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
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
   case "Liste des Sessions" : $tmp = "Sessions List"; break;
   case "Nom" : $tmp = "Name"; break;
   case "@ IP" : $tmp = "@IP"; break;
   case "@ IP résolue" : $tmp = "Resolved @IP"; break;
   case "Infos" : $tmp = "Infos"; break;
   case "Liste des Logs" : $tmp = "Logs List"; break;
   case "SECURITE" : $tmp = "SECURITY"; break;
   case "TELECHARGEMENT" : $tmp = "UPLOAD"; break;
   case "Gestion des Logs" : $tmp = "Logs' administration"; break;
   case "Fournisseur" : $tmp = "FAI"; break;
   case "Informations sur l'IP" : $tmp = "IP informations"; break;
   case "Vider le fichier" : $tmp = "Empty the file"; break;
   case "Recevoir le fichier par mail" : $tmp = "Send file by email"; break;
   case "Effacer les fichiers temporaires" : $tmp = "Erase temporary files"; break;
   case "Fichier de Log de" : $tmp = "Log File for"; break;

   default: $tmp = "需要翻译稿 [** $phrase **]"; break;
 }
  return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
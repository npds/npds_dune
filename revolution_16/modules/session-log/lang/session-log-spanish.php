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
      case "@ IP résolue" : $tmp = "Resuelto @IP"; break;
      case "@ IP" : $tmp = "@IP"; break;
      case "Agent utilisateur" : $tmp = "Agente de usuario"; break;
      case "Agent" : $tmp = "Agente de usuario"; break;
      case "cette adresse IP a été déconnectée et bannie !" : $tmp = "¡Desconecta y prohíbe esta dirección IP!"; break;
      case "Déconnecter et bannir cette adresse IP !" : $tmp = "¡Desconecta y prohíbe esta dirección IP!"; break;
      case "Effacer les fichiers temporaires" : $tmp = "Eliminar ficheros temporales"; break;
      case "Fichier de Log de" : $tmp = "Fichero Log de"; break;
      case "Fournisseur" : $tmp = "FAI"; break;
      case "Gestion des Logs" : $tmp = "Gestión de Logs"; break;
      case "Informations sur l'IP" : $tmp = "Información IP"; break;
      case "Infos" : $tmp = "Infos"; break;
      case "Liste des Logs" : $tmp = "Lista de Logs"; break;
      case "Liste des Sessions" : $tmp = "Lista de Sessions"; break;
      case "Nom" : $tmp = "Nombre"; break;
      case "Recevoir le fichier par mail" : $tmp = "Recibir el fichero por email"; break;
      case "SECURITE" : $tmp = "SEGURIDAD"; break;
      case "TELECHARGEMENT" : $tmp = "SUBIR"; break;
      case "Vider le fichier" : $tmp = "Fichero vacío"; break;
      case "Vide la table des sessions et interrompt les connexions." : $tmp = "Vacía la tabla de sesiones e interrumpe las conexiones."; break;
      default: $tmp = "Necesita una traducción [** $phrase **]"; break;
   }
   return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
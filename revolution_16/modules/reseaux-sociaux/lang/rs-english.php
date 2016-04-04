<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* reseaux-sociaux : jpb 2016                                           */
/*                                                                      */
/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

function rs_translate($phrase) {
 switch ($phrase) {
   case "Liste des réseaux sociaux mis à disposition par l'administrateur." : $tmp = ""; break;
   case "Ajouter ou supprimer votre identifiant à ces réseaux sociaux." : $tmp = ""; break;
   case "Identifiant" : $tmp = "Nickname"; break;
   case "Réseaux sociaux" : $tmp = "Social networks"; break;
   case "Ceci créera ou supprimera le lien d'accès dans toutes les pages du portail où ils sont disponibles (forums, articles, commentaires, liste des membres, etc) pour tous les utilisateurs." : $tmp = "Infos"; break;
   case "Editer" : $tmp = "Edit"; break;
   case "Fonctions" : $tmp = "Functions"; break;
   case "Name" : $tmp = "Nom"; break;
   case "Icône" : $tmp = "Icon"; break;
   case "Sauvegarder" : $tmp = "Save"; break;
   case "Informations sur l'IP" : $tmp = "IP informations"; break;
   case "Vider le fichier" : $tmp = "Empty the file"; break;
   case "Recevoir le fichier par mail" : $tmp = "Send file by email"; break;
   case "Effacer les fichiers temporaires" : $tmp = "Erase temporary files"; break;
   case "Fichier de Log de" : $tmp = "Log File for"; break;
   case "Agent utilisateur" : $tmp = "User agent"; break;
   case "Agent" : $tmp = "Agent"; break;
   default: $tmp = "Translation error [** $phrase **]"; break;
 }
  return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
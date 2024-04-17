<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* reseaux-sociaux : jpb 2016                                           */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

function rs_translate($phrase) {
 switch ($phrase) {
   case "Liste des réseaux sociaux mis à disposition par l'administrateur." : $tmp = "List of social networks provided by the administrator."; break;
   case "Ajouter ou supprimer votre identifiant à ces réseaux sociaux." : $tmp = "Add or remove your login to these social networks."; break;
   case "Identifiant" : $tmp = "Nickname"; break;
   case "Réseaux sociaux" : $tmp = "Social networks"; break;
   case "Ceci créera ou supprimera le lien d'accès dans toutes les pages du portail où ils sont disponibles (forums, articles, commentaires, liste des membres, etc) pour tous les utilisateurs." : $tmp = "This creates or deletes the access link in all the pages of the portal where they are available for all users (forums, articles, comments, list of members, etc)."; break;
   case "Editer" : $tmp = "Edit"; break;
   case "Effacer" : $tmp = "Delete"; break;
   case "Fonctions" : $tmp = "Functions"; break;
   case "Nom" : $tmp = "Name"; break;
   case "Icône" : $tmp = "Icon"; break;
   case "Sauvegarder" : $tmp = "Save"; break;
   
   default: $tmp = "Translation error [** $phrase **]"; break;
 }
  return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
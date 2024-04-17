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
   case "Liste des réseaux sociaux mis à disposition par l'administrateur." : $tmp = "Liste von sozialen Netzwerken gemacht vom Administrator zur Verfügung."; break;
   case "Ajouter ou supprimer votre identifiant à ces réseaux sociaux." : $tmp = "Hinzufügen oder Entfernen von Ihren Benutzernamen an diesen sozialen Netzwerken."; break;
   case "Identifiant" : $tmp = "Nickname"; break;
   case "Réseaux sociaux" : $tmp = "Soziale Netzwerke"; break;
   case "Ceci créera ou supprimera le lien d'accès dans toutes les pages du portail où ils sont disponibles (forums, articles, commentaires, liste des membres, etc) pour tous les utilisateurs." : $tmp = "Dies erzeugt oder entfernt den Zugangslink auf allen Seiten des Portals, wo sie verfügbar sind (Foren, Artikel, Kommentare, Mitgliederliste, etc.) für alle Benutzer."; break;
   case "Editer" : $tmp = "Bearbeiten"; break;
   case "Fonctions" : $tmp = "Funktionen"; break;
   case "Nom" : $tmp = "Name"; break;
   case "Icône" : $tmp = "Icon"; break;
   case "Sauvegarder" : $tmp = "Speichern"; break;

   default: $tmp = "Es gibt keine Übersetzung [** $phrase **]"; break;
 }
  return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
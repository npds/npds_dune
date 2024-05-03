<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2024 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

function fma_translate($phrase) {
 switch ($phrase) {
   case "Fichier interdit" : $tmp = "Verbotene Datei"; break;
   case "Type de fichier interdit" : $tmp = "Gebetteter Dateityp"; break;
   case "Ok" : $tmp = "Ok"; break;
   case "Renommer" : $tmp = "Umbenennen"; break;
   case "Supprimer" : $tmp = "Entfernen"; break;
   case "Chmoder" : $tmp = "Chmod"; break;
   case "Editer" : $tmp = "Bearbeiten"; break;
   case "Déplacer" : $tmp = "Bewegen"; break;
   case "Copier" : $tmp = "Kopie"; break;
   case "Déplacer / Copier" : $tmp = "Bewegen / Kopie"; break;
   case "Renommer un fichier" : $tmp = "Umbenennen einer Datei"; break;
   case "Déplacer / Copier un fichier" : $tmp = "Bewegen / Kopie einer Datei"; break;
   case "Renommer un répertoire" : $tmp = "Umbenennen eines Verzeichnisses"; break;
   case "Supprimer un fichier" : $tmp = "Löschen einer Datei"; break;
   case "Supprimer un répertoire" : $tmp = "Löschen ein Verzeichnis"; break;
   case "Confirmez-vous la suppression de" : $tmp = "Bestätigen Sie die Entfernung von"; break;
   case "Changer les droits d'un fichier" : $tmp = "Ändern der Rechte einer Datei"; break;
   case "Changer les droits d'un répertoire" : $tmp = "Ändern Sie die Rechte eines Verzeichnisses"; break;
   case "Editer un fichier" : $tmp = "Bearbeiten einer Datei"; break;
   case "Impossible d'appliquer le chmod" : $tmp = "Kann nicht gelten die chmod"; break;
   case "Impossible de renommer" : $tmp = "Kann nicht umbenannt werden"; break;
   case "Impossible de déplacer" : $tmp = "Kann sich nicht bewegen"; break;
   case "Impossible de créer" : $tmp = "Kann nicht erstellt werden"; break;
   case "Impossible de supprimer" : $tmp = "Kann nicht gelöscht werden"; break;
   case "Impossible de copier" : $tmp = "Kann nicht kopiert werden"; break;
   case "Le fichier n'existe pas" : $tmp = "Die Datei existiert nicht"; break;
   case "existe déjà" : $tmp = "bereits vorhanden"; break;
   case "Rafraîchir" : $tmp = "Aktualisierung"; break;
   case "Extensions autorisées : " : $tmp = "Autorisierter Dateien Typ : "; break;
   case "Go" : $tmp = "Go"; break;
   case "Mo" : $tmp = "Mo"; break;
   case "Ko" : $tmp = "Ko"; break;
   case "Copie de " : $tmp = "Kopieren von "; break;
   case "Taille maximum d'un fichier : " : $tmp = "Maximale Dateigröße : "; break;
   case "Images manager" : $tmp = "Bilder Manager"; break;
   case "Autoriser Pic-Manager" : $tmp = "Ermöglichen Bilder Manager"; break;
   case "Taille maximum (pixel) de l'imagette" : $tmp = "Maximale Größe (pixel) für thumb"; break;
   case "Cliquer ici pour charger le fichier dans le player" : $tmp = "Klicken Sie hier, um die Datei in den Player zu laden"; break;
   case "Temps de cache (en seconde) des imagettes" : $tmp = "Dauer der Cache (in Sekunden) für thumbs"; break;
   case "F-Manager": $tmp="F-Manager"; break;
   case "Gestionnaire de fichiers": $tmp="Dateimanager"; break;
   case "Sélectionner votre fichier": $tmp="Wählen Sie Ihre Datei aus"; break;

   default: $tmp = "Es gibt keine übersetzung [** $phrase **]"; break;
 }
 return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
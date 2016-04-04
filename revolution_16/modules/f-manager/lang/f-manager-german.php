<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2012 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

function fma_translate($phrase) {
 switch ($phrase) {
   case "Fichier interdit" : $tmp = "Gesch�tzte Datei"; break;
   case "Type de fichier interdit" : $tmp = "Dateityp erlaubt"; break;
   case "Ok" : $tmp = "Ok"; break;
   case "Renommer" : $tmp = "Umbenennen"; break;
   case "Supprimer" : $tmp = "Entfernen"; break;
   case "Chmoder" : $tmp = "Chmod"; break;
   case "Editer" : $tmp = "Bearbeiten"; break;
   case "D�placer" : $tmp = "Bewegen"; break;
   case "Copier" : $tmp = "Kopie"; break;
   case "D�placer / Copier" : $tmp = "Bewegen / Kopie"; break;
   case "Renommer un fichier" : $tmp = "Umbenennen einer Datei"; break;
   case "D�placer / Copier un fichier" : $tmp = "Bewegen / Kopie einer Datei"; break;
   case "Renommer un r�pertoire" : $tmp = "Umbenennen eines Verzeichnisses"; break;
   case "Supprimer un fichier" : $tmp = "L�schen einer Datei"; break;
   case "Supprimer un r�pertoire" : $tmp = "L�schen ein Verzeichnis"; break;
   case "Confirmez-vous la suppression de" : $tmp = "Best�tigen Sie die L�schung der"; break;
   case "Changer les droits d'un fichier" : $tmp = "�ndern Sie die Dateiberechtigungen"; break;
   case "Changer les droits d'un r�pertoire" : $tmp = "�ndern Sie die Rechte eines Verzeichnisses"; break;
   case "Editer un fichier" : $tmp = "Bearbeiten einer Datei"; break;
   case "Impossible d'appliquer le chmod" : $tmp = "Kann nicht gelten die chmod"; break;
   case "Impossible de renommer" : $tmp = "Kann nicht umbenannt werden"; break;
   case "Impossible de d�placer" : $tmp = "Bewegungsunf�hig"; break;
   case "Impossible de cr�er" : $tmp = "Kann nicht erstellt werden"; break;
   case "Impossible de supprimer" : $tmp = "Kann nicht gel�scht werden"; break;
   case "Impossible de copier" : $tmp = "Kann nicht kopiert werden"; break;
   case "Le fichier n'existe pas" : $tmp = "Die Datei existiert nicht"; break;
   case "existe d�j�" : $tmp = "bereits vorhanden"; break;
   case "Rafraichir" : $tmp = "Aktualisierung"; break;
   case "Extensions autoris�es : " : $tmp = "Autorisierter Dateien Typ : "; break;
   case "Go" : $tmp = "Go"; break;
   case "Mo" : $tmp = "Mo"; break;
   case "Ko" : $tmp = "Ko"; break;
   case "Copie de " : $tmp = "Kopieren von "; break;
   case "Taille maximum d'un fichier : " : $tmp = "Maximale Dateigr��e : "; break;
   case "Pic-Manager" : $tmp = "Pic-Manager"; break;
   case "Autoriser Pic-Manager" : $tmp = "Erm�glichen Pic-Manager"; break;
   case "Taille maximum (pixel) de l'imagette" : $tmp = "Maximale Gr��e (pixel) f�r thumb"; break;
   case "Cliquer ici pour charger le fichier dans le player" : $tmp = "Klicken Sie hier, um die Datei in den Player zu laden"; break;
   case "Temps de cache (en seconde) des imagettes" : $tmp = "Dauer der Cache (in Sekunden) f�r thumbs"; break;
   case "F-Manager": $tmp="F-Manager"; break;

   default: $tmp = "Es gibt keine �bersetzung <b>[** $phrase **]</b>"; break;
 }
 return $tmp;
}
?>
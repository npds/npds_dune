<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2017 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
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
   case "DŽplacer" : $tmp = "Bewegen"; break;
   case "Copier" : $tmp = "Kopie"; break;
   case "DŽplacer / Copier" : $tmp = "Bewegen / Kopie"; break;
   case "Renommer un fichier" : $tmp = "Umbenennen einer Datei"; break;
   case "DŽplacer / Copier un fichier" : $tmp = "Bewegen / Kopie einer Datei"; break;
   case "Renommer un rŽpertoire" : $tmp = "Umbenennen eines Verzeichnisses"; break;
   case "Supprimer un fichier" : $tmp = "Lšschen einer Datei"; break;
   case "Supprimer un rŽpertoire" : $tmp = "Lšschen ein Verzeichnis"; break;
   case "Confirmez-vous la suppression de" : $tmp = "BestŠtigen Sie die Entfernung von"; break;
   case "Changer les droits d'un fichier" : $tmp = "€ndern der Rechte einer Datei"; break;
   case "Changer les droits d'un rŽpertoire" : $tmp = "€ndern Sie die Rechte eines Verzeichnisses"; break;
   case "Editer un fichier" : $tmp = "Bearbeiten einer Datei"; break;
   case "Impossible d'appliquer le chmod" : $tmp = "Kann nicht gelten die chmod"; break;
   case "Impossible de renommer" : $tmp = "Kann nicht umbenannt werden"; break;
   case "Impossible de dŽplacer" : $tmp = "Kann sich nicht bewegen"; break;
   case "Impossible de crŽer" : $tmp = "Kann nicht erstellt werden"; break;
   case "Impossible de supprimer" : $tmp = "Kann nicht gelšscht werden"; break;
   case "Impossible de copier" : $tmp = "Kann nicht kopiert werden"; break;
   case "Le fichier n'existe pas" : $tmp = "Die Datei existiert nicht"; break;
   case "existe dŽjˆ" : $tmp = "bereits vorhanden"; break;
   case "Rafra”chir" : $tmp = "Aktualisierung"; break;
   case "Extensions autorisŽes : " : $tmp = "Autorisierter Dateien Typ : "; break;
   case "Go" : $tmp = "Go"; break;
   case "Mo" : $tmp = "Mo"; break;
   case "Ko" : $tmp = "Ko"; break;
   case "Copie de " : $tmp = "Kopieren von "; break;
   case "Taille maximum d'un fichier : " : $tmp = "Maximale Dateigrš§e : "; break;
   case "Pic-Manager" : $tmp = "Pic-Manager"; break;
   case "Autoriser Pic-Manager" : $tmp = "Ermšglichen Pic-Manager"; break;
   case "Taille maximum (pixel) de l'imagette" : $tmp = "Maximale Grš§e (pixel) fŸr thumb"; break;
   case "Cliquer ici pour charger le fichier dans le player" : $tmp = "Klicken Sie hier, um die Datei in den Player zu laden"; break;
   case "Temps de cache (en seconde) des imagettes" : $tmp = "Dauer der Cache (in Sekunden) fŸr thumbs"; break;
   case "F-Manager": $tmp="F-Manager"; break;

   default: $tmp = "Es gibt keine Ÿbersetzung [** $phrase **]"; break;
 }
 return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
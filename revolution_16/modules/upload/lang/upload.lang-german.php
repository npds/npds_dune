<?php
/************************************************************************/
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/*                                                                      */
/* UPLOAD Language File                                                 */
/*                                                                      */
/************************************************************************/

function upload_translate($phrase) {
 switch($phrase) {
   case "Adapter": $tmp="Aktualisierung"; break;
   case "Affichage intégré :": $tmp="Integriertes Display :"; break;
   case "Attention": $tmp="Warnung"; break;
   case "Ce type de fichier n'est pas autorisé": $tmp="Diese typ von Datei ist nicht autorisierte"; break;
   case "Ces Documents sont disponibles sur votre site": $tmp="Diese Bilder sind auf Ihre Website."; break;
   case "Ces Images et ces Documents sont rattachés à votre compte.": $tmp="Ihre Bilder und Dokumente."; break;
   case "Ces Images sont disponibles sur votre site": $tmp="Diese Bilder sind auf Ihre Website."; break;
   case "Cette page a déjà été envoyée, veuillez patienter": $tmp="Diese Seite wurde versandt, bitte haben Sie Geduld"; break;
   case "Erreur de téléchargement du fichier - fichier non sauvegardé.": $tmp="Fehler beim Hochladen der Datei - Datei nicht gespeichert"; break;
   case "Erreur de téléchargement du fichier %s (%s) - Le fichier n'a pas été sauvé": $tmp="Fehler beim Hochladen der Datei %s (%s) - Datei nicht gespeichert"; break;
   case "Etes vous certains de vouloir installer le thème": $tmp="Sind Sie sicher, dass Sie die Vorlage installieren"; break;
   case "Extensions autorisées"; $tmp="Erlaubte Dateierweiterungen"; break;
   case "Fichier {NAME} bien reçu ({SIZE} octets transférés)": $tmp="datei {NAME} hochgeladen ({SIZE} ¸bertragenen Bytes)"; break;
   case "Fichier joint": $tmp="Wählen Sie eine Datei anhängen :"; break;
   case "Fichier non trouvé": $tmp="Datei nicht gefunden"; break;
   case "Fichier non visible": $tmp="Datei nicht sichtbar"; break;
   case "Fichier": $tmp="Datei"; break;
   case "Installer": $tmp="Installieren"; break;
   case "Joindre le fichier maintenant ?": $tmp="Datei senden jetzt?"; break;
   case "Joindre": $tmp="Datei senden"; break;
   case "La taille de ce fichier excède la taille maximum autorisée": $tmp="Die Dateigröße ¸berschreitet die maximale Dateigröße"; break;
   case "Le code erreur est : %s": $tmp="Fehlercode war : %s"; break;
   case "Modèles": $tmp="Vorlagen"; break;
   case "Modifications enregistrées dans": $tmp="Änderungen gespeichert in"; break;
   case "Non": $tmp="Nein"; break;
   case "Oui": $tmp="Ja"; break;
   case "Pièces jointes": $tmp="Attachments"; break;
   case "Prévisualisation :": $tmp="Vorschau :"; break;
   case "Rafraîchir la page": $tmp="Laden Sie die Seite"; break;
   case "Session terminée.": $tmp="Session gestoppt."; break;
   case "Supprimer les fichiers sélectionnés ?": $tmp="Ausgewählten Dateien löschen?"; break;
   case "Supprimer les fichiers sélectionnés": $tmp="Löschen ausgewählten Dateien"; break;
   case "Taille": $tmp="Größe"; break;
   case "Télécharg.": $tmp="Download(s)"; break;
   case "Télécharger un fichier sur le serveur"; $tmp="Datei-Upload"; break;
   case "Total :": $tmp="Gesamt :"; break;
   case "Type": $tmp="Typ"; break;
   case "Visibilité": $tmp="Sichtbarkeit"; break;
   case "Vous devez sélectionner un fichier": $tmp="Sie müssen eine Datei auswählen"; break;
   case "Vous devez tout d'abord choisir la Pièce jointe à supprimer": $tmp="Sie müssen die Anlage, die Sie löschen möchten"; break;

   default: $tmp = "Es gibt keine übersetzung [** $phrase **]"; break;
 }
   return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
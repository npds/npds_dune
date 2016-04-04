<?php
/************************************************************************/
/* This version name NPDS Copyright (c) 2001-2011 by Philippe Brunier   */
/* ===========================                                          */
/*                                                                      */
/* UPLOAD Language File                                                 */
/*                                                                      */
/************************************************************************/

function upload_translate($phrase) {
 switch($phrase) {
   /////// fichier
   case "Pièces jointes :": $tmp="Attachments :"; break;
   case "Fichier :": $tmp="Datei :"; break;
   case "Type :": $tmp="Typ :"; break;
   case "Taille :": $tmp="Größe :"; break;
   case "Affichage intégré :": $tmp="Integriertes Display :"; break;
   case "Oui": $tmp="Ja"; break;
   case "Non": $tmp="Nein"; break;
   case "Supprimer les fichier sélectionnés": $tmp="Löschen ausgewählte Datei"; break;
   case "Fichier joint :": $tmp="Wählen Sie eine Datei anhängen :"; break;
   case "Joindre": $tmp="Datei senden"; break;
   case "Adapter": $tmp="Aktualisierung"; break;
   case "Visible :": $tmp="Sichtbarkeit :"; break;
   case "Total :": $tmp="Gesamt :"; break;
   case "Fichier non trouvé": $tmp="Datei nicht gefunden"; break;
   case "Fichier non visible": $tmp="Datei nicht sichtbar"; break;
   case "Télécharg.": $tmp="Download(s)"; break;
   case "Prévisualisation :": $tmp="Vorschau :"; break;
   case "Ces Images sont disponibles sur votre site": $tmp="Diese Bilder sind auf Ihre Website."; break;
   case "Ces Documents sont disponibles sur votre site": $tmp="Diese Bilder sind auf Ihre Website."; break;
   case "Ces Images et ces Documents sont rattachés à votre compte.": $tmp="Ihre Bilder und Dokumente."; break;
   case "Télécharger un fichier sur le serveur"; $tmp="Datei-Upload"; break;
   /////// javascript
   case "Supprimer les fichiers sélectionnés ?": $tmp="Ausgewählte Dateien löschen?"; break;
   case "Cette page a déjà été envoyée, veuillez patienter": $tmp="Diese Seite wurde versandt, bitte haben Sie Geduld"; break;
   case "Vous devez tout d'abord choisir la Pièce jointe à supprimer": $tmp="Sie müssen die Anlage, die Sie löschen möchten"; break;
   case "Vous devez selectionner un fichier": $tmp="Sie müssen eine Datei auswählen"; break;
   case "Joindre le fichier maintenant ?": $tmp="Datei senden jetzt?"; break;
   case "Raffraichir la page": $tmp="Laden Sie die Seite"; break;
   case "Modèles": $tmp="Vorlagen"; break;
   case "Installer": $tmp="Installieren"; break;
   case "Etes vous certains de vouloir installer le thème": $tmp="Sind Sie sicher, dass Sie die Vorlage installieren"; break;
   /////// class upload
   case "La taille de ce fichier excède la taille maximum autorisée": $tmp="Die Größe dieser Datei überschreitet die maximale Dateigröße"; break;
   case "Ce type de fichier n'est pas autorisé": $tmp="Diese typ von Datei ist nicht autorisierte"; break;
   case "Le code erreur est : %s": $tmp="Fehlercode war : %s"; break;
   case "Attention": $tmp="Warnung"; break;
   case "Session terminée.": $tmp="Session gestoppt."; break;
   case "Erreur de téléchargement du fichier <b>%s</b> (%s) - Le fichier n'a pas été sauvé": $tmp="Fehler beim Hochladen der Datei <b>%s</b> (%s) - Datei nicht gespeichert"; break;
   case "<center><b>Fichier {NAME} bien recu ({SIZE} octets transférés)</b></center>": $tmp="<center><b>datei {NAME} hochgeladen ({SIZE} übertragenen Bytes)</b></center>"; break;
   case "Erreur de téléchargement du fichier - fichier non sauvegardé.": $tmp="Fehler beim Hochladen der Datei - Datei nicht gespeichert"; break;

   // NPDS Sable
   case "Images & Documents": $tmp="Bilder & Dokumente"; break;
   case "Modules Additionnels": $tmp="PlugIns"; break;
   // NPDS Sable

   default: $tmp = "Es gibt keine Übersetzung <b>[** $phrase **]</b>"; break;
 }
 if (cur_charset=="utf-8") {
    return utf8_encode($tmp);
 } else {
    return ($tmp);
 }
}
?>
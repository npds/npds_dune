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
   case "Pi�ces jointes :": $tmp="Attachments :"; break;
   case "Fichier :": $tmp="Datei :"; break;
   case "Type :": $tmp="Typ :"; break;
   case "Taille :": $tmp="Gr��e :"; break;
   case "Affichage int�gr� :": $tmp="Integriertes Display :"; break;
   case "Oui": $tmp="Ja"; break;
   case "Non": $tmp="Nein"; break;
   case "Supprimer les fichier s�lectionn�s": $tmp="L�schen ausgew�hlte Datei"; break;
   case "Fichier joint :": $tmp="W�hlen Sie eine Datei anh�ngen :"; break;
   case "Joindre": $tmp="Datei senden"; break;
   case "Adapter": $tmp="Aktualisierung"; break;
   case "Visible :": $tmp="Sichtbarkeit :"; break;
   case "Total :": $tmp="Gesamt :"; break;
   case "Fichier non trouv�": $tmp="Datei nicht gefunden"; break;
   case "Fichier non visible": $tmp="Datei nicht sichtbar"; break;
   case "T�l�charg.": $tmp="Download(s)"; break;
   case "Pr�visualisation :": $tmp="Vorschau :"; break;
   case "Ces Images sont disponibles sur votre site": $tmp="Diese Bilder sind auf Ihre Website."; break;
   case "Ces Documents sont disponibles sur votre site": $tmp="Diese Bilder sind auf Ihre Website."; break;
   case "Ces Images et ces Documents sont rattach�s � votre compte.": $tmp="Ihre Bilder und Dokumente."; break;
   case "T�l�charger un fichier sur le serveur"; $tmp="Datei-Upload"; break;
   /////// javascript
   case "Supprimer les fichiers s�lectionn�s ?": $tmp="Ausgew�hlte Dateien l�schen?"; break;
   case "Cette page a d�j� �t� envoy�e, veuillez patienter": $tmp="Diese Seite wurde versandt, bitte haben Sie Geduld"; break;
   case "Vous devez tout d'abord choisir la Pi�ce jointe � supprimer": $tmp="Sie m�ssen die Anlage, die Sie l�schen m�chten"; break;
   case "Vous devez selectionner un fichier": $tmp="Sie m�ssen eine Datei ausw�hlen"; break;
   case "Joindre le fichier maintenant ?": $tmp="Datei senden jetzt?"; break;
   case "Raffraichir la page": $tmp="Laden Sie die Seite"; break;
   case "Mod�les": $tmp="Vorlagen"; break;
   case "Installer": $tmp="Installieren"; break;
   case "Etes vous certains de vouloir installer le th�me": $tmp="Sind Sie sicher, dass Sie die Vorlage installieren"; break;
   /////// class upload
   case "La taille de ce fichier exc�de la taille maximum autoris�e": $tmp="Die Gr��e dieser Datei �berschreitet die maximale Dateigr��e"; break;
   case "Ce type de fichier n'est pas autoris�": $tmp="Diese typ von Datei ist nicht autorisierte"; break;
   case "Le code erreur est : %s": $tmp="Fehlercode war : %s"; break;
   case "Attention": $tmp="Warnung"; break;
   case "Session termin�e.": $tmp="Session gestoppt."; break;
   case "Erreur de t�l�chargement du fichier <b>%s</b> (%s) - Le fichier n'a pas �t� sauv�": $tmp="Fehler beim Hochladen der Datei <b>%s</b> (%s) - Datei nicht gespeichert"; break;
   case "<center><b>Fichier {NAME} bien recu ({SIZE} octets transf�r�s)</b></center>": $tmp="<center><b>datei {NAME} hochgeladen ({SIZE} �bertragenen Bytes)</b></center>"; break;
   case "Erreur de t�l�chargement du fichier - fichier non sauvegard�.": $tmp="Fehler beim Hochladen der Datei - Datei nicht gespeichert"; break;

   // NPDS Sable
   case "Images & Documents": $tmp="Bilder & Dokumente"; break;
   case "Modules Additionnels": $tmp="PlugIns"; break;
   // NPDS Sable

   default: $tmp = "Es gibt keine �bersetzung <b>[** $phrase **]</b>"; break;
 }
 if (cur_charset=="utf-8") {
    return utf8_encode($tmp);
 } else {
    return ($tmp);
 }
}
?>
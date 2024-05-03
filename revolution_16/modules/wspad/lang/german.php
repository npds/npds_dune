<?php
/************************************************************************/
/* NPDS : Net Portal Dynamic System                                     */
/* ===========================                                          */
/*                                                                      */
/*    WS-PAD Language File Copyright (c) 2013-2024 by Developpeur       */
/*                                                                      */
/************************************************************************/

function wspad_trans($phrase) {
   switch ($phrase) {
      case "Vous allez supprimer le document": $tmp="Sie löschen das Dokument"; break;
      case "Document(s) et révision(s) disponible(s) pour le groupe": $tmp="Dokument(en) und Revision(en) für die Gruppe"; break;
      case "Document(s) et révision(s) disponible(s) pour les administrateurs": $tmp="Dokument(en) und Revision(en) für Administratoren"; break;
      case "Rev.": $tmp="Rev."; break;
      case "révision": $tmp="revision"; break;
      case "Auteur": $tmp="Autor"; break;
      case "Date": $tmp="Datum"; break;
      case "Actions": $tmp="Aktionen"; break;
      case "Supprimer le document et toutes ses révisions": $tmp="Löschen Sie das Dokument und alle Revisionen"; break;
      case "Renommer le document et toutes ses révisions": $tmp="Benennen Sie das Dokument und alle Revisionen"; break;
      case "Prévisualiser": $tmp="Vorschau"; break;
      case "Choisir": $tmp="Wählen"; break;
      case "Supprimer la révision": $tmp="Löschen Sie die Revision"; break;
      case "Exporter .doc": $tmp="Export .doc"; break;
      case "Verrouillé par : ": $tmp="Gesperrt von: "; break;
      case "Renommer": $tmp="Umbenennen"; break;
      case "Caractères autorisés : a-z, A-Z, 0-9, -_.": $tmp="Erlaubte Zeichen: a-z, A-Z, 0-9, -_."; break;
      case "Créer un document": $tmp="Erstellen Sie ein Dokument"; break;
      case "Nom du document": $tmp="Namen der Dokument"; break;
      case "Créer": $tmp="Erstellen"; break;
      case "note : Enregistrer votre travail": $tmp="Hinweis: Speichern Sie Ihre Arbeit"; break;
      case "Document : ": $tmp="Dokument: "; break;
      case "Sauvegarder": $tmp="Save"; break;
      case "Déplier la liste": $tmp="Klappen Sie die Liste"; break;
      case "Replier la liste": $tmp="Falten Sie die Liste"; break;
      case "sauvegardée": $tmp="gespeichert"; break;
      case "Abandonner": $tmp="Aufgeben"; break;
      case "Transformer en New": $tmp="In Artikel"; break;
      case "Mode lecture seulement": $tmp="Read only mode"; break;

      default: $tmp = "Benötigt Übersetzung [** $phrase **]"; break;
   }

   return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
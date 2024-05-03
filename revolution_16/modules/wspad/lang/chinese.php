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
      case "Vous allez supprimer le document": $tmp="You are deleting the document"; break;
      case "Document(s) et révision(s) disponible(s) pour le groupe": $tmp="Document(s) and revision(s) available for the group"; break;
      case "Document(s) et révision(s) disponible(s) pour les administrateurs": $tmp="Document(s( and revision(s) available for the administrators"; break;
      case "Rev.": $tmp="Rev."; break;
      case "révision": $tmp="revision"; break;
      case "Auteur": $tmp="Author"; break;
      case "Date": $tmp="Date"; break;
      case "Actions": $tmp="Actions"; break;
      case "Supprimer le document et toutes ses révisions": $tmp="Delete the document and all revisions"; break;
      case "Renommer le document et toutes ses révisions": $tmp="Rename the document and all revisions"; break;
      case "Prévisualiser": $tmp="Preview"; break;
      case "Choisir": $tmp="Choose"; break;
      case "Supprimer la révision": $tmp="Delete the revision"; break;
      case "Exporter .doc": $tmp="Export .doc"; break;
      case "Verrouillé par : ": $tmp="locked by: "; break;
      case "Renommer": $tmp="Rename"; break;
      case "Caractères autorisés : a-z, A-Z, 0-9, -_.": $tmp="characters allowed: a-z, A-Z, 0-9, -_."; break;
      case "Créer un document": $tmp="Create a document"; break;
      case "Nom du document": $tmp="Name of document"; break;
      case "Créer": $tmp="Create"; break;
      case "note : Enregistrer votre travail": $tmp="note: Save your work"; break;
      case "Document : ": $tmp="Document: "; break;
      case "Sauvegarder": $tmp="Save"; break;
      case "Déplier la liste": $tmp="Show list"; break;
      case "Replier la liste": $tmp="Hide list"; break;
      case "sauvegardée": $tmp="saved"; break;
      case "Abandonner": $tmp="Cancel"; break;
      case "Transformer en New": $tmp="Into new"; break;
      case "Mode lecture seulement": $tmp="Read only mode"; break;

      default: $tmp = "Translation error [** $phrase **]"; break;
   }

   return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
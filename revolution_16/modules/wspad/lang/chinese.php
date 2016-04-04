<?php
/************************************************************************/
/* NPDS : Net Portal Dynamic System                                     */
/* ===========================                                          */
/*                                                                      */
/*    WS-PAD Language File Copyright (c) 2013 by Developpeur            */
/*                                                                      */
/************************************************************************/

function wspad_trans($phrase) {
 switch ($phrase) {
    case "Vous allez supprimer le document": $tmp="You are deleting the document"; break;
    case "Document(s) et r�vision(s) disponible(s) pour le groupe": $tmp="Document(s) and revision(s) available for the group"; break;
    case "Document(s) et r�vision(s) disponible(s) pour les administrateurs": $tmp="Document(s( and revision(s) available for the administrators"; break;
    case "Rev.": $tmp="Rev."; break;
    case "r�vision": $tmp="revision"; break;
    case "Auteur": $tmp="Author"; break;
    case "Date": $tmp="Date"; break;
    case "Actions": $tmp="Actions"; break;
    case "Supprimer le document et toutes ses r�visions": $tmp="Delete the document and all revisions"; break;
    case "Renommer le document et toutes ses r�visions": $tmp="Rename the document and all revisions"; break;
    case "Pr�visualiser": $tmp="Preview"; break;
    case "Choisir": $tmp="Choose"; break;
    case "Supprimer la r�vision": $tmp="Delete the revision"; break;
    case "Exporter .doc": $tmp="Export .doc"; break;
    case "Verrouill� par : ": $tmp="locked by: "; break;
    case "Renommer": $tmp="Rename"; break;
    case "Caract�res autoris�s : a-z, A-Z, 0-9, -_.": $tmp="characters allowed: a-z, A-Z, 0-9, -_."; break;
    case "Cr�er un document": $tmp="Create a document"; break;
    case "Nom du document": $tmp="Name of document"; break;
    case "Cr�er": $tmp="Create"; break;
    case "note : Enregistrer votre travail": $tmp="note: Save your work"; break;
    case "Document : ": $tmp="Document: "; break;
    case "Sauvegarder": $tmp="Save"; break;
    case "D�plier la liste": $tmp="Show list"; break;
    case "Replier la liste": $tmp="Hide list"; break;
    case "sauvegard�e": $tmp="saved"; break;
    case "Abandonner": $tmp="Cancel"; break;
    case "Transformer en New": $tmp="Into new"; break;
    case "Mode lecture seulement": $tmp="Read only mode"; break;
    
    default: $tmp = "Translation error <b>[** $phrase **]</b>"; break;
 }

 if (cur_charset=="utf-8") {
    return utf8_encode($tmp);
 } else {
    return ($tmp);
 }
}
?>
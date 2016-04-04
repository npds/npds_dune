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
    case "Vous allez supprimer le document": $tmp="Sie l&ouml;schen das Dokument"; break;
    case "Document(s) et r�vision(s) disponible(s) pour le groupe": $tmp="Dokument(en) und Revision(en) f&uuml;r die Gruppe"; break;
    case "Document(s) et r�vision(s) disponible(s) pour les administrateurs": $tmp="Dokument(en) und Revision(en) f&uuml;r Administratoren"; break;
    case "Rev.": $tmp="Rev."; break;
    case "r�vision": $tmp="revision"; break;
    case "Auteur": $tmp="Autor"; break;
    case "Date": $tmp="Datum"; break;
    case "Actions": $tmp="Aktionen"; break;
    case "Supprimer le document et toutes ses r�visions": $tmp="L&ouml;schen Sie das Dokument und alle Revisionen"; break;
    case "Renommer le document et toutes ses r�visions": $tmp="Benennen Sie das Dokument und alle Revisionen"; break;
    case "Pr�visualiser": $tmp="Vorschau"; break;
    case "Choisir": $tmp="W&auml;hlen"; break;
    case "Supprimer la r�vision": $tmp="L&ouml;schen Sie die Revision"; break;
    case "Exporter .doc": $tmp="Export .doc"; break;
    case "Verrouill� par : ": $tmp="Gesperrt von: "; break;
    case "Renommer": $tmp="Umbenennen"; break;
    case "Caract�res autoris�s : a-z, A-Z, 0-9, -_.": $tmp="Erlaubte Zeichen: a-z, A-Z, 0-9, -_."; break;
    case "Cr�er un document": $tmp="Erstellen Sie ein Dokument"; break;
    case "Nom du document": $tmp="Namen der Dokument"; break;
    case "Cr�er": $tmp="Erstellen"; break;
    case "note : Enregistrer votre travail": $tmp="Hinweis: Speichern Sie Ihre Arbeit"; break;
    case "Document : ": $tmp="Dokument: "; break;
    case "Sauvegarder": $tmp="Save"; break;
    case "D�plier la liste": $tmp="Klappen Sie die Liste"; break;
    case "Replier la liste": $tmp="Falten Sie die Liste"; break;
    case "sauvegard�e": $tmp="gespeichert"; break;
    case "Abandonner": $tmp="Aufgeben"; break;
    case "Transformer en New": $tmp="In Artikel"; break;
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
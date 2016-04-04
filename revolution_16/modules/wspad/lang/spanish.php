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
    case "Vous allez supprimer le document": $tmp="Se elimina el documento"; break;
    case "Document(s) et r�vision(s) disponible(s) pour le groupe": $tmp="Documento(s) y la(s) revision(es) disponibles para el grupo"; break;
    case "Document(s) et r�vision(s) disponible(s) pour les administrateurs": $tmp="Documento(s) y la(s) revision(es) disponibles para los administradores"; break;
    case "Rev.": $tmp="Rev."; break;
    case "r�vision": $tmp="revisi&oacute;n"; break;
    case "Auteur": $tmp="Autor"; break;
    case "Date": $tmp="Fecha"; break;
    case "Actions": $tmp="Acciones"; break;
    case "Supprimer le document et toutes ses r�visions": $tmp="Eliminar el documento y todas las revisiones"; break;
    case "Renommer le document et toutes ses r�visions": $tmp="Cambie el nombre del documento y todas las revisiones"; break;
    case "Pr�visualiser": $tmp="Vista previa"; break;
    case "Choisir": $tmp="Elegir"; break;
    case "Supprimer la r�vision": $tmp="Eliminar la revisi&oacute;n"; break;
    case "Exporter .doc": $tmp="Exportar .doc"; break;
    case "Verrouill� par : ": $tmp="bloqueados por: "; break;
    case "Renommer": $tmp="Cambie el nombre"; break;
    case "Caract�res autoris�s : a-z, A-Z, 0-9, -_.": $tmp="Caracteres permitidos: a-z, A-Z, 0-9, -_."; break;
    case "Cr�er un document": $tmp="Crear un documento"; break;
    case "Nom du document": $tmp="Nombre del documento"; break;
    case "Cr�er": $tmp="Crear"; break;
    case "note : Enregistrer votre travail": $tmp="Nota: Guarde su trabajo"; break;
    case "Document : ": $tmp="Documento: "; break;
    case "Sauvegarder": $tmp="Guardar"; break;
    case "D�plier la liste": $tmp="Despliegue la lista"; break;
    case "Replier la liste": $tmp="Dobla la lista"; break;
    case "sauvegard�e": $tmp="guardado"; break;
    case "Abandonner": $tmp="Cancelar"; break;
    case "Transformer en New": $tmp="En el art&iacute;culo"; break;
    case "Mode lecture seulement": $tmp="S&oacute;lo lectura"; break;

    default: $tmp = "Necesita ser traducido <b>[** $phrase **]</b>"; break;
 }
 if (cur_charset=="utf-8") {
    return utf8_encode($tmp);
 } else {
    return ($tmp);
 }
}
?>
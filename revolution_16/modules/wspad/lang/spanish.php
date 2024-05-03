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
      case "Vous allez supprimer le document": $tmp="Se elimina el documento"; break;
      case "Document(s) et révision(s) disponible(s) pour le groupe": $tmp="Documento(s) y la(s) revision(es) disponibles para el grupo"; break;
      case "Document(s) et révision(s) disponible(s) pour les administrateurs": $tmp="Documento(s) y la(s) revision(es) disponibles para los administradores"; break;
      case "Rev.": $tmp="Rev."; break;
      case "révision": $tmp="revisión"; break;
      case "Auteur": $tmp="Autor"; break;
      case "Date": $tmp="Fecha"; break;
      case "Actions": $tmp="Acciones"; break;
      case "Supprimer le document et toutes ses révisions": $tmp="Eliminar el documento y todas las revisiones"; break;
      case "Renommer le document et toutes ses révisions": $tmp="Cambie el nombre del documento y todas las revisiones"; break;
      case "Prévisualiser": $tmp="Vista previa"; break;
      case "Choisir": $tmp="Elegir"; break;
      case "Supprimer la révision": $tmp="Eliminar la revisión"; break;
      case "Exporter .doc": $tmp="Exportar .doc"; break;
      case "Verrouillé par : ": $tmp="bloqueados por: "; break;
      case "Renommer": $tmp="Cambie el nombre"; break;
      case "Caractères autorisés : a-z, A-Z, 0-9, -_.": $tmp="Caracteres permitidos: a-z, A-Z, 0-9, -_."; break;
      case "Créer un document": $tmp="Crear un documento"; break;
      case "Nom du document": $tmp="Nombre del documento"; break;
      case "Créer": $tmp="Crear"; break;
      case "note : Enregistrer votre travail": $tmp="Nota: Guarde su trabajo"; break;
      case "Document : ": $tmp="Documento: "; break;
      case "Sauvegarder": $tmp="Guardar"; break;
      case "Déplier la liste": $tmp="Despliegue la lista"; break;
      case "Replier la liste": $tmp="Dobla la lista"; break;
      case "sauvegardée": $tmp="guardado"; break;
      case "Abandonner": $tmp="Cancelar"; break;
      case "Transformer en New": $tmp="En el artículo"; break;
      case "Mode lecture seulement": $tmp="Solo lectura"; break;

      default: $tmp = "Necesita una traducción [** $phrase **]"; break;
   }
   return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
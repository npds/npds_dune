<?php
/************************************************************************/
/* This version name NPDS Copyright (c) 2001-2019 by Philippe Brunier   */
/* ===========================                                          */
/*                                                                      */
/* UPLOAD Language File                                                 */
/*                                                                      */
/************************************************************************/

function upload_translate($phrase) {
 switch($phrase) {
   /////// fichier
   case "Pièces jointes": $tmp="Archivos adjuntos :"; break;
   case "Fichier": $tmp="Fichero"; break;
   case "Type": $tmp="Tipo"; break;
   case "Taille": $tmp="Tamaño"; break;
   case "Affichage intégré": $tmp="En línea"; break;
   case "Oui": $tmp="Sí"; break;
   case "Non": $tmp="No"; break;
   case "Supprimer les fichier sélectionnés": $tmp="Eliminar los ficheros seleccionados"; break;
   case "Fichier joint": $tmp="Seleccione un fichero para adjuntar :"; break;
   case "Joindre": $tmp="Enviar fichero"; break;
   case "Adapter": $tmp="Adaptar"; break;
   case "Visibilité": $tmp="Visibilidad"; break;
   case "Total :": $tmp="Total :"; break;
   case "Fichier non trouvé": $tmp="Fichero no encontrado"; break;
   case "Fichier non visible": $tmp="Fichero no es visible"; break;
   case "Télécharg.": $tmp="Descarga(s)"; break;
   case "Prévisualisation :": $tmp="Vista previa :"; break;
   case "Ces Images sont disponibles sur votre site": $tmp="Estas imágenes están disponibles en su sitio Web."; break;
   case "Ces Documents sont disponibles sur votre site": $tmp="Estos documentos están disponibles en su sitio Web."; break;
   case "Ces Images et ces Documents sont rattachés à votre compte.": $tmp="Sus imágenes y documentos."; break;
   case "Télécharger un fichier sur le serveur"; $tmp="Cargar el fichero"; break;
   case 'Extensions autorisées'; $tmp='Extensiones de archivo permitidas'; break;
   /////// javascript
   case "Supprimer les fichiers sélectionnés ?": $tmp="Eliminar los ficheros seleccionados ?"; break;
   case "Cette page a déjà été envoyée, veuillez patienter": $tmp="Esta página ha sido presentado, por favor sea paciente"; break;
   case "Vous devez tout d'abord choisir la Pièce jointe à supprimer": $tmp="Usted debe elegir el fichero adjunto que desea eliminar"; break;
   case "Vous devez selectionner un fichier": $tmp="Debe seleccionar un fichero"; break;
   case "Joindre le fichier maintenant ?": $tmp="Enviar el fichero ahora ?"; break;
   case "Rafraîchir la page": $tmp="Actualizar la página"; break;
   case "Modèles": $tmp="Plantillas"; break;
   case "Installer": $tmp="Instalar"; break;
   case "Etes vous certains de vouloir installer le thème": $tmp="Está seguro que desea instalar la plantilla ?"; break;
   /////// class upload
   case "La taille de ce fichier excède la taille maximum autorisée": $tmp="El tamaño de este fichero supera el tamaño máximo de fichero"; break;
   case "Ce type de fichier n'est pas autorisé": $tmp="Este tipo de fichero no se autorizado"; break;
   case "Le code erreur est : %s": $tmp="Código de error se : %s"; break;
   case "Attention": $tmp="Advertencia"; break;
   case "Session terminée.": $tmp="Sesión se detuvo."; break;
   case "Erreur de téléchargement du fichier %s (%s) - Le fichier n'a pas été sauvé": $tmp="Error al subir fichero %s (%s) - El fichero no guardado"; break;
   case "Fichier {NAME} bien reçu ({SIZE} octets transférés)": $tmp="Fichero {NAME} subida ({SIZE} bytes transferidos)"; break;
   case "Erreur de téléchargement du fichier - fichier non sauvegardé.": $tmp="Error al subir fichero - El fichero no guardado"; break;

   default: $tmp = "Necesita una traducción [** $phrase **]"; break;
 }
   return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
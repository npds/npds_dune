<?php
/************************************************************************/
/* This version name NPDS Copyright (c) 2001-2017 by Philippe Brunier   */
/* ===========================                                          */
/*                                                                      */
/* UPLOAD Language File                                                 */
/*                                                                      */
/************************************************************************/

function upload_translate($phrase) {
 switch($phrase) {
   /////// fichier
   case "Pi�ces jointes :": $tmp="Archivos adjuntos :"; break;
   case "Fichier :": $tmp="Fichero :"; break;
   case "Type :": $tmp="Tipo :"; break;
   case "Taille :": $tmp="Tama�o :"; break;
   case "Affichage int�gr� :": $tmp="En l�nea:"; break;
   case "Oui": $tmp="S�"; break;
   case "Non": $tmp="No"; break;
   case "Supprimer les fichier s�lectionn�s": $tmp="Eliminar los ficheros seleccionados"; break;
   case "Fichier joint :": $tmp="Seleccione un fichero para adjuntar :"; break;
   case "Joindre": $tmp="Enviar fichero"; break;
   case "Adapter": $tmp="Actualizaci�n"; break;
   case "Visible :": $tmp="Visibilidad :"; break;
   case "Total :": $tmp="Total :"; break;
   case "Fichier non trouv�": $tmp="Fichero no encontrado"; break;
   case "Fichier non visible": $tmp="Fichero no es visible"; break;
   case "T�l�charg.": $tmp="Descarga(s)"; break;
   case "Pr�visualisation :": $tmp="Vista previa :"; break;
   case "Ces Images sont disponibles sur votre site": $tmp="Estas im�genes est�n disponibles en su sitio Web."; break;
   case "Ces Documents sont disponibles sur votre site": $tmp="Estos documentos est�n disponibles en su sitio Web."; break;
   case "Ces Images et ces Documents sont rattach�s � votre compte.": $tmp="Sus im�genes y documentos."; break;
   case "T�l�charger un fichier sur le serveur"; $tmp="Cargar el fichero"; break;
   /////// javascript
   case "Supprimer les fichiers s�lectionn�s ?": $tmp="Eliminar los ficheros seleccionados ?"; break;
   case "Cette page a d�j� �t� envoy�e, veuillez patienter": $tmp="Esta p�gina ha sido presentado, por favor sea paciente"; break;
   case "Vous devez tout d'abord choisir la Pi�ce jointe � supprimer": $tmp="Usted debe elegir el fichero adjunto que desea eliminar"; break;
   case "Vous devez selectionner un fichier": $tmp="Debe seleccionar un fichero"; break;
   case "Joindre le fichier maintenant ?": $tmp="Enviar el fichero ahora ?"; break;
   case "Raffraichir la page": $tmp="Actualizar la p�gina"; break;
   case "Mod�les": $tmp="Plantillas"; break;
   case "Installer": $tmp="Instalar"; break;
   case "Etes vous certains de vouloir installer le th�me": $tmp="Est� seguro que desea instalar la plantilla ?"; break;
   /////// class upload
   case "La taille de ce fichier exc�de la taille maximum autoris�e": $tmp="El tama�o de este fichero supera el tama�o m�ximo de fichero"; break;
   case "Ce type de fichier n'est pas autoris�": $tmp="Este tipo de fichero no se autorizado"; break;
   case "Le code erreur est : %s": $tmp="C�digo de error se : %s"; break;
   case "Attention": $tmp="Advertencia"; break;
   case "Session termin�e.": $tmp="Sesi�n se detuvo."; break;
   case "Erreur de t�l�chargement du fichier <b>%s</b> (%s) - Le fichier n'a pas �t� sauv�": $tmp="Error al subir fichero <b>%s</b> (%s) - El fichero no guardado"; break;
   case "<center><b>Fichier {NAME} bien recu ({SIZE} octets transf�r�s)</b></center>": $tmp="<center><b>Fichero {NAME} subida ({SIZE} bytes transferidos)</b></center>"; break;
   case "Erreur de t�l�chargement du fichier - fichier non sauvegard�.": $tmp="Error al subir fichero - El fichero no guardado"; break;

   // NPDS Sable
   case "Images & Documents": $tmp="Im�genes y Documentos"; break;
   case "Modules Additionnels": $tmp="Plug-ins"; break;
   // NPDS Sable

   default: $tmp = "Necesita una traducci&oacute;n <b>[** $phrase **]</b>"; break;
 }
 if (cur_charset=="utf-8") {
    return utf8_encode($tmp);
 } else {
    return ($tmp);
 }
}
?>
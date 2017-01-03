<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2017 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

function fma_translate($phrase) {
 switch ($phrase) {
   case "Fichier interdit" : $tmp = "Fichero no permitido"; break;
   case "Type de fichier interdit" : $tmp = "Tipo de fichero no permitido"; break;
   case "Ok" : $tmp = "Ok"; break;
   case "Renommer" : $tmp = "Cambiar"; break;
   case "Supprimer" : $tmp = "Eliminar"; break;
   case "Chmoder" : $tmp = "Chmod"; break;
   case "Editer" : $tmp = "Editar"; break;
   case "D�placer" : $tmp = "Mover"; break;
   case "Copier" : $tmp = "Copiar"; break;
   case "D�placer / Copier" : $tmp = "Mover / Copiar"; break;
   case "Renommer un fichier" : $tmp = "Cambiar un fichero"; break;
   case "D�placer / Copier un fichier" : $tmp = "Mover / Copiar un fichero"; break;
   case "Renommer un r�pertoire" : $tmp = "Cambiar el directorio"; break;
   case "Supprimer un fichier" : $tmp = "Eliminar el fichero"; break;
   case "Supprimer un r�pertoire" : $tmp = "Borrar el directorio"; break;
   case "Confirmez-vous la suppression de" : $tmp = "Est� seguro que desea eliminar"; break;
   case "Changer les droits d'un fichier" : $tmp = "Chmod de fichero"; break;
   case "Changer les droits d'un r�pertoire" : $tmp = "Chmod directorio"; break;
   case "Editer un fichier" : $tmp = "Editar el fichero"; break;
   case "Impossible d'appliquer le chmod" : $tmp = "No se puede chmod"; break;
   case "Impossible de renommer" : $tmp = "No se puede cambiar"; break;
   case "Impossible de d�placer" : $tmp = "No se puede mover"; break;
   case "Impossible de cr�er" : $tmp = "No se puede crear"; break;
   case "Impossible de supprimer" : $tmp = "No se puede eliminar"; break;
   case "Impossible de copier" : $tmp = "No se puede copiar"; break;
   case "Le fichier n'existe pas" : $tmp = "El fichero no existe"; break;
   case "existe d�j�" : $tmp = "ya existen"; break;
   case "Rafraichir" : $tmp = "Actualizaci�n"; break;
   case "Extensions autoris�es : " : $tmp = "Tipo de fichero autorizado : "; break;
   case "Go" : $tmp = "Go"; break;
   case "Mo" : $tmp = "Mo"; break;
   case "Ko" : $tmp = "Ko"; break;
   case "Copie de " : $tmp = "Copia de "; break;
   case "Taille maximum d'un fichier : " : $tmp = "Tama�o m�ximo de fichero : "; break;
   case "Pic-Manager" : $tmp = "Pic-Manager"; break;
   case "Autoriser Pic-Manager" : $tmp = "permitir Pic-Manager"; break;
   case "Taille maximum (pixel) de l'imagette" : $tmp = "El tama�o m�ximo (pixel) para thumb"; break;
   case "Cliquer ici pour charger le fichier dans le player" : $tmp = "Haga clic aqu� para cargar el fichero en el reproductor"; break;
   case "Temps de cache (en seconde) des imagettes" : $tmp = "Duraci�n de la cach� (en segundos) para thumbs"; break;
   case "F-Manager": $tmp="F-Manager"; break;

   default: $tmp = "Necesita una traducci&oacute;n <b>[** $phrase **]</b>"; break;
 }
 return $tmp;
}
?>
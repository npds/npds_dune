<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2024 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

function fma_translate($phrase) {
   switch ($phrase) {
      case "Autoriser Pic-Manager" : $tmp = "Permitir gerente de fotos"; break;
      case "Changer les droits d'un fichier" : $tmp = "Chmod de fichero"; break;
      case "Changer les droits d'un répertoire" : $tmp = "Chmod directorio"; break;
      case "Chmoder" : $tmp = "Chmod"; break;
      case "Cliquer ici pour charger le fichier dans le player" : $tmp = "Haga clic aquí para cargar el fichero en el reproductor"; break;
      case "Confirmez-vous la suppression de" : $tmp = "Est· seguro que desea eliminar"; break;
      case "Copie de " : $tmp = "Copia de "; break;
      case "Copier" : $tmp = "Copiar"; break;
      case "Déplacer / Copier un fichier" : $tmp = "Mover / Copiar un fichero"; break;
      case "Déplacer / Copier" : $tmp = "Mover / Copiar"; break;
      case "Déplacer" : $tmp = "Mover"; break;
      case "Ecouter" : $tmp = "Escuchar"; break;
      case "Editer un fichier" : $tmp = "Editar el fichero"; break;
      case "Editer" : $tmp = "Editar"; break;
      case "existe déjà" : $tmp = "ya existen"; break;
      case "Extensions autorisées : " : $tmp = "Tipo de fichero autorizado : "; break;
      case "F-Manager": $tmp="F-Manager"; break;
      case "Fichier interdit" : $tmp = "Fichero no permitido"; break;
      case "Gestionnaire de fichiers": $tmp="Gestor de archivos"; break;
      case "Go" : $tmp = "Go"; break;
      case "Images manager" : $tmp = "Gerente de fotos"; break;
      case "Impossible d'appliquer le chmod" : $tmp = "No se puede chmod"; break;
      case "Impossible de copier" : $tmp = "No se puede copiar"; break;
      case "Impossible de créer" : $tmp = "No se puede crear"; break;
      case "Impossible de déplacer" : $tmp = "No se puede mover"; break;
      case "Impossible de renommer" : $tmp = "No se puede cambiar"; break;
      case "Impossible de supprimer" : $tmp = "No se puede eliminar"; break;
      case "Ko" : $tmp = "Ko"; break;
      case "Le fichier n'existe pas" : $tmp = "El fichero no existe"; break;
      case "Mo" : $tmp = "Mo"; break;
      case "Ok" : $tmp = "Ok"; break;
      case "Rafraîchir" : $tmp = "Actualización"; break;
      case "Renommer un fichier" : $tmp = "Cambiar un fichero"; break;
      case "Renommer un répertoire" : $tmp = "Cambiar el directorio"; break;
      case "Renommer" : $tmp = "Cambiar"; break;
      case "Sélectionner votre fichier": $tmp="Selecciona tu archivo"; break;
      case "Supprimer un fichier" : $tmp = "Eliminar el fichero"; break;
      case "Supprimer un répertoire" : $tmp = "Borrar el directorio"; break;
      case "Supprimer" : $tmp = "Eliminar"; break;
      case "Taille maximum (pixel) de l'imagette" : $tmp = "El tamaño máximo (pixel) para thumb"; break;
      case "Taille maximum d'un fichier : " : $tmp = "Tamaño máximo de fichero : "; break;
      case "Temps de cache (en seconde) des imagettes" : $tmp = "Duración de la caché (en segundos) para thumbs"; break;
      case "Type de fichier interdit" : $tmp = "Tipo de fichero no permitido"; break;
      case "Vous n'êtes pas autorisé à utiliser le gestionnaire de média. SVP contacter l'administrateur." : $tmp = "No está autorizado a utilizar el administrador de medios. Por favor contacte al administrador."; break;

      default: $tmp = "Requiere una traducción [** $phrase **]"; break;
   }
   return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,'UTF-8'));
}
?>
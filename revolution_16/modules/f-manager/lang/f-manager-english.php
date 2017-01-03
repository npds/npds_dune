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
   case "Fichier interdit" : $tmp = "File not allowed"; break;
   case "Type de fichier interdit" : $tmp = "File type not allowed"; break;
   case "Ok" : $tmp = "Ok"; break;
   case "Renommer" : $tmp = "Rename"; break;
   case "Supprimer" : $tmp = "Delete"; break;
   case "Chmoder" : $tmp = "Chmod"; break;
   case "Editer" : $tmp = "Edit"; break;
   case "D�placer" : $tmp = "Move"; break;
   case "Copier" : $tmp = "Copy"; break;
   case "D�placer / Copier" : $tmp = "Move / Copy"; break;
   case "Renommer un fichier" : $tmp = "Rename file"; break;
   case "D�placer / Copier un fichier" : $tmp = "Move / Copy file"; break;
   case "Renommer un r�pertoire" : $tmp = "Rename directory"; break;
   case "Supprimer un fichier" : $tmp = "Delete file"; break;
   case "Supprimer un r�pertoire" : $tmp = "Delete directory"; break;
   case "Confirmez-vous la suppression de" : $tmp = "Are you sure you want to delete"; break;
   case "Changer les droits d'un fichier" : $tmp = "Chmod file"; break;
   case "Changer les droits d'un r�pertoire" : $tmp = "Chmod directory"; break;
   case "Editer un fichier" : $tmp = "Edit file"; break;
   case "Impossible d'appliquer le chmod" : $tmp = "Cannot chmod"; break;
   case "Impossible de renommer" : $tmp = "Cannot rename"; break;
   case "Impossible de d�placer" : $tmp = "Cannot move"; break;
   case "Impossible de cr�er" : $tmp = "Cannot create"; break;
   case "Impossible de supprimer" : $tmp = "Cannot delete"; break;
   case "Impossible de copier" : $tmp = "Cannot copy"; break;
   case "Le fichier n'existe pas" : $tmp = "File doesn't exist"; break;
   case "existe d�j�" : $tmp = "already exist"; break;
   case "Rafraichir" : $tmp = "Update"; break;
   case "Extensions autoris�es : " : $tmp = "Authorised files type: "; break;
   case "Go" : $tmp = "Gb"; break;
   case "Mo" : $tmp = "Mb"; break;
   case "Ko" : $tmp = "Kb"; break;
   case "Copie de " : $tmp = "Copy of "; break;
   case "Taille maximum d'un fichier : " : $tmp = "Maximum file size: "; break;
   case "Pic-Manager" : $tmp = "Pic-Manager"; break;
   case "Autoriser Pic-Manager" : $tmp = "Enable Pic-Manager"; break;
   case "Taille maximum (pixel) de l'imagette" : $tmp = "Maximum size (pixel) for thumb"; break;
   case "Cliquer ici pour charger le fichier dans le player" : $tmp = "Click here to load the file in the player"; break;
   case "Temps de cache (en seconde) des imagettes" : $tmp = "Duration of cache (in second) for thumbs"; break;
   case "F-Manager": $tmp="F-Manager"; break;

   default: $tmp = "Translation error <b>[** $phrase **]</b>"; break;
 }
 return $tmp;
}
?>
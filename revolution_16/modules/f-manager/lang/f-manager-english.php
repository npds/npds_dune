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
      case "Fichier interdit" : $tmp = "File not allowed"; break;
      case "Type de fichier interdit" : $tmp = "File type not allowed"; break;
      case "Ok" : $tmp = "Ok"; break;
      case "Renommer" : $tmp = "Rename"; break;
      case "Supprimer" : $tmp = "Delete"; break;
      case "Chmoder" : $tmp = "Chmod"; break;
      case "Editer" : $tmp = "Edit"; break;
      case "Déplacer" : $tmp = "Move"; break;
      case "Copier" : $tmp = "Copy"; break;
      case "Déplacer / Copier" : $tmp = "Move / Copy"; break;
      case "Renommer un fichier" : $tmp = "Rename file"; break;
      case "Déplacer / Copier un fichier" : $tmp = "Move / Copy file"; break;
      case "Renommer un répertoire" : $tmp = "Rename directory"; break;
      case "Supprimer un fichier" : $tmp = "Delete file"; break;
      case "Supprimer un répertoire" : $tmp = "Delete directory"; break;
      case "Confirmez-vous la suppression de" : $tmp = "Are you sure you want to delete"; break;
      case "Changer les droits d'un fichier" : $tmp = "Chmod file"; break;
      case "Changer les droits d'un répertoire" : $tmp = "Chmod directory"; break;
      case "Editer un fichier" : $tmp = "Edit file"; break;
      case "Impossible d'appliquer le chmod" : $tmp = "Cannot chmod"; break;
      case "Impossible de renommer" : $tmp = "Cannot rename"; break;
      case "Impossible de déplacer" : $tmp = "Cannot move"; break;
      case "Impossible de créer" : $tmp = "Cannot create"; break;
      case "Impossible de supprimer" : $tmp = "Cannot delete"; break;
      case "Impossible de copier" : $tmp = "Cannot copy"; break;
      case "Le fichier n'existe pas" : $tmp = "File doesn't exist"; break;
      case "existe déjà" : $tmp = "already exist"; break;
      case "Rafraîchir" : $tmp = "Update"; break;
      case "Extensions autorisées : " : $tmp = "Authorised files type: "; break;
      case "Go" : $tmp = "Gb"; break;
      case "Mo" : $tmp = "Mb"; break;
      case "Ko" : $tmp = "Kb"; break;
      case "Copie de " : $tmp = "Copy of "; break;
      case "Taille maximum d'un fichier : " : $tmp = "Maximum file size: "; break;
      case "Images manager" : $tmp = "Pictures Manager"; break;
      case "Autoriser Pic-Manager" : $tmp = "Enable pictures Manager"; break;
      case "Taille maximum (pixel) de l'imagette" : $tmp = "Maximum size (pixel) for thumb"; break;
      case "Cliquer ici pour charger le fichier dans le player" : $tmp = "Click here to load the file in the player"; break;
      case "Temps de cache (en seconde) des imagettes" : $tmp = "Duration of cache (in second) for thumbs"; break;
      case "F-Manager": $tmp="F-Manager"; break;
      case "Gestionnaire de fichiers": $tmp="File Manager"; break;
      case "Sélectionner votre fichier": $tmp="Choose your file"; break;

      default: $tmp = "Translation error [** $phrase **]"; break;
   }
   return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
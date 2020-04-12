<?php
/************************************************************************/
/* This version name NPDS Copyright (c) 2001-2020 by Philippe Brunier   */
/* ===========================                                          */
/*                                                                      */
/* UPLOAD Language File                                                 */
/*                                                                      */
/************************************************************************/

function upload_translate($phrase) {
 switch($phrase) {
   /////// fichier
   case 'Pièces jointes': $tmp='Attachments:'; break;
   case 'Fichier': $tmp='File'; break;
   case 'Type': $tmp='Type'; break;
   case 'Taille': $tmp='Size'; break;
   case 'Affichage intégré': $tmp='Inline'; break;
   case 'Oui': $tmp='Yes'; break;
   case 'Non': $tmp='No'; break;
   case 'Supprimer les fichiers sélectionnés': $tmp='Delete selected files'; break;
   case 'Fichier joint': $tmp='Select a file to attach:'; break;
   case 'Joindre': $tmp='Send file'; break;
   case 'Adapter': $tmp='Update'; break;
   case 'Visibilité': $tmp='Visibility'; break;
   case 'Total :': $tmp='Total:'; break;
   case 'Fichier non trouvé': $tmp='File not found'; break;
   case 'Fichier non visible': $tmp='File not visible'; break;
   case 'Télécharg.': $tmp='Download(s)'; break;
   case 'Prévisualisation :': $tmp='Preview:'; break;
   case 'Ces Images sont disponibles sur votre site': $tmp='These Images are available on your Website.'; break;
   case 'Ces Documents sont disponibles sur votre site': $tmp='These Documents are available on your Website.'; break;
   case 'Ces Images et ces Documents sont rattachés à votre compte.': $tmp='Your Images and Documents.'; break;
   case 'Télécharger un fichier sur le serveur'; $tmp='File upload'; break;
   case 'Extensions autorisées'; $tmp='Allowed extension'; break;
   case 'Taille maxi du fichier'; $tmp='Max file size'; break;
   /////// javascript
   case "Supprimer les fichiers sélectionnés ?": $tmp="Delete selected files ?"; break;
   case "Cette page a déjà été envoyée, veuillez patienter": $tmp="This page has been submitted, please be patient"; break;
   case "Vous devez tout d'abord choisir la Pièce jointe à supprimer": $tmp="You must choose the attachment you want to delete"; break;
   case "Vous devez sélectionner un fichier": $tmp="You must select a file"; break;
   case "Joindre le fichier maintenant ?": $tmp="Send file now ?"; break;
   case "Rafraîchir la page": $tmp="Reload the page"; break;
   case "Modèles": $tmp="Templates"; break;
   case "Installer": $tmp="Install"; break;
   case "Etes vous certains de vouloir installer le thème": $tmp="Are you sure you want to install the template"; break;
   /////// class upload
   case "La taille de ce fichier excède la taille maximum autorisée": $tmp="The size of this file exceeds the maximum file size"; break;
   case "Ce type de fichier n'est pas autorisé": $tmp="This type of file is not authorized"; break;
   case "Le code erreur est : %s": $tmp="Error code was: %s"; break;
   case "Attention": $tmp="Warning"; break;
   case "Session terminée.": $tmp="Session halted."; break;
   case "Erreur de téléchargement du fichier %s (%s) - Le fichier n'a pas été sauvé": $tmp="Error while uploading file %s (%s) - File not saved"; break;
   case "Fichier {NAME} bien reçu ({SIZE} octets transférés)": $tmp="file {NAME} uploaded ({SIZE} bytes transferred)"; break;
   case "Erreur de téléchargement du fichier - fichier non sauvegardé.": $tmp="Error while uploading file - File not saved"; break;
   /////// admin
   case 'Configuration Upload'; $tmp='Upload settings'; break;
   case 'Chemin physique'; $tmp='Physical path'; break;
   case "Autorise l'upload DANS le répertoire personnel du membre"; $tmp="Allow Upload IN member's personal directory"; break;
   case 'Taille maxi des fichiers'; $tmp='Max files size'; break;
   case 'Activer Upload'; $tmp='Activate Upload'; break;
   case 'Répertoire de téléchargement'; $tmp='Upload directory'; break;
   case 'Répertoire de cache'; $tmp='Cache directory'; break;
   case 'Répertoire des log'; $tmp='Log directory'; break;
   case 'Largeur maxi des images'; $tmp='Images max width'; break;
   case 'Hauteur maxi des images'; $tmp='Images max height'; break;
   case "Nombre d'images"; $tmp='Images number'; break;
   case "Espace disque"; $tmp='Disk space'; break;
   case "Affichage"; $tmp='Display'; break;
   case "Extensions autorisées"; $tmp='Allowed extensions'; break;
   default: $tmp = "Need tanslation [** $phrase **]"; break;
 }
   return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
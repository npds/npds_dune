<?php
/************************************************************************/
/* This version name NPDS Copyright (c) 2001-2015 by Philippe Brunier   */
/* ===========================                                          */
/*                                                                      */
/* UPLOAD Language File                                                 */
/*                                                                      */
/************************************************************************/

function upload_translate($phrase) {
 switch($phrase) {
   /////// fichier
   case 'x': $tmp='Attachments:'; break;
   case 'Fichier': $tmp='File'; break;
   case 'Type': $tmp='Type'; break;
   case 'Taille': $tmp='Size'; break;
   case 'Affichage intégré': $tmp='Inline'; break;
   case 'Oui': $tmp='Yes'; break;
   case 'Non': $tmp='No'; break;
   case 'Supprimer les fichiers sélectionnés': $tmp='Delete selected files'; break;
   case 'Fichier joint :': $tmp='Select a file to attach:'; break;
   case 'Joindre': $tmp='Send file'; break;
   case 'Adapter': $tmp='Update'; break;
   case 'Visible': $tmp='Visibility'; break;
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
   /////// javascript
   case "Supprimer les fichiers sélectionnés ?": $tmp="Delete selected files ?"; break;
   case "Cette page a déjà été envoyée, veuillez patienter": $tmp="This page has been submitted, please be patient"; break;
   case "Vous devez tout d'abord choisir la Pièce jointe à supprimer": $tmp="You must choose the attachment you want to delete"; break;
   case "Vous devez selectionner un fichier": $tmp="You must select a file"; break;
   case "Joindre le fichier maintenant ?": $tmp="Send file now ?"; break;
   case "Raffraichir la page": $tmp="Reload the page"; break;
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
   // NPDS Sable
   case "Images & Documents": $tmp="Images & Documents"; break;
   case "Modules Additionnels": $tmp="PlugIns"; break;
   case "Pièces jointes": $tmp="Attached documents"; break;

   default: $tmp = "nécessite une traduction [** $phrase **]"; break;
 }
   return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
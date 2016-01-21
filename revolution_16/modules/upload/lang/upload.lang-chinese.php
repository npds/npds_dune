<?php
/************************************************************************/
/* This version name NPDS Copyright (c) 2001-2011 by Philippe Brunier   */
/* ===========================                                          */
/*                                                                      */
/* UPLOAD Language File                                                 */
/*                                                                      */
/************************************************************************/

function upload_translate($phrase) {
 switch($phrase) {
   /////// fichier
   /**/case "Pièces jointes :": $tmp="Attachments:"; break;
   case "Fichier :": $tmp="&#x6587;&#x4EF6;:"; break;
   case "Type :": $tmp="&#x7C7B;&#x578B;:"; break;
   case "Taille :": $tmp="&#x5927;&#x5C0F;:"; break;
   /**/case "Affichage intégré :": $tmp="Inline:"; break;
   case "Oui": $tmp="&#x662F;"; break;
   case "Non": $tmp="&#x5426;"; break;
   /**/case "Supprimer les fichier sélectionnés": $tmp="Delete selected files"; break;
   /**/case "Fichier joint :": $tmp="Select a file to attach:"; break;
   /**/case "Joindre": $tmp="Send file"; break;
   /**/case "Adapter": $tmp="Update"; break;
   /**/case "Visible :": $tmp="Visibility:"; break;
   case "Total :": $tmp="&#x603B;&#x8BA1;&#xFF1A;:"; break;
   /**/case "Fichier non trouvé": $tmp="File not found"; break;
   /**/case "Fichier non visible": $tmp="File not visible"; break;
   case "Télécharg.": $tmp="&#x4E0B;&#x8F09;&#x6B21;&#x6578;"; break;
   case "Prévisualisation :": $tmp="&#x9884;&#x89C8;:"; break;
   /**/case "Ces Images sont disponibles sur votre site": $tmp="These Images are available on your Website."; break;
   /**/case "Ces Documents sont disponibles sur votre site": $tmp="These Documents are available on your Website."; break;
   /**/case "Ces Images et ces Documents sont rattachés à votre compte.": $tmp="Your Images and Documents."; break;
   /**/case "Télécharger un fichier sur le serveur"; $tmp="File upload"; break;
   /////// javascript
   /**/case "Supprimer les fichiers sélectionnés ?": $tmp="Delete selected files ?"; break;
   /**/case "Cette page a déjà été envoyée, veuillez patienter": $tmp="This page has been submitted, please be patient"; break;
   /**/case "Vous devez tout d'abord choisir la Pièce jointe à supprimer": $tmp="You must choose the attachment you want to delete"; break;
   /**/case "Vous devez selectionner un fichier": $tmp="You must select a file"; break;
   /**/case "Joindre le fichier maintenant ?": $tmp="Send file now ?"; break;
   /**/case "Raffraichir la page": $tmp="Reload the page"; break;
   /**/case "Modèles": $tmp="Templates"; break;
   /**/case "Installer": $tmp="Install"; break;
   /**/case "Etes vous certains de vouloir installer le thème": $tmp="Are you sure you want to install the template"; break;
   /////// class upload
   /**/case "La taille de ce fichier excède la taille maximum autorisée": $tmp="The size of this file exceeds the maximum file size"; break;
   /**/case "Ce type de fichier n'est pas autorisé": $tmp="This type of file is not authorized"; break;
   /**/case "Le code erreur est : %s": $tmp="Error code was: %s"; break;
   case "Attention": $tmp="&#x8B66;&#x544A;"; break;
   /**/case "Session terminée.": $tmp="Session halted."; break;
   /**/case "Erreur de téléchargement du fichier <b>%s</b> (%s) - Le fichier n'a pas été sauvé": $tmp="Error while uploading file <b>%s</b> (%s) - File not saved"; break;
   /**/case "<center><b>Fichier {NAME} bien recu ({SIZE} octets transférés)</b></center>": $tmp="<center><b>file {NAME} uploaded ({SIZE} bytes transferred)</b></center>"; break;
   case "Erreur de téléchargement du fichier - fichier non sauvegardé.": $tmp="Error while uploading file - File not saved"; break;

   default: $tmp = "&#x9700;&#x8981;&#x7FFB;&#x8BD1;&#x7A3F; <b>[** $phrase **]</b>"; break;
 }
 return $tmp;
}
?>
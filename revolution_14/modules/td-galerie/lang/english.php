<?php
/************************************************************************/
/* NPDS : Net Portal Dynamic System                                     */
/* ===========================                                          */
/*                                                                      */
/* TD-Galerie Language File Copyright (c) 2004-2005 by Tribal-Dolphin   */
/*                                                                      */
/************************************************************************/

function gal_trans($phrase) {
    switch ($phrase) {
       case "Catégories": $tmp = "Categories"; break;
       case "Galeries": $tmp = "Galeries"; break;
       case "Aucune catégorie trouvée": $tmp = "No category found "; break;
       case "Créée le": $tmp = "Created on"; break;
       case "Accueil": $tmp = "Home"; break;
       case "Aucune galerie trouvée": $tmp = "No galery found "; break;
       case "Aucune image trouvée": $tmp = "No picture found "; break;
       case "affichage(s)": $tmp = "view(s)"; break;
       case "commentaire(s)": $tmp = "comment(s)"; break;
       case "Informations sur l'image": $tmp = "File information"; break;
       case "Taille du fichier :": $tmp = "File Size:"; break;
       case "Dimensions :": $tmp = "Dimensions:"; break;
       case "Affichées :": $tmp = "Displayed:"; break;
       case "fois": $tmp = "times"; break;
       case "Ajoutez votre commentaire": $tmp = "Add your comment"; break;
       case "Commentaire": $tmp = "Comment"; break;
       case "Noter cette image": $tmp = "Rate this file"; break;
       case "Note (": $tmp = "Rating ("; break;
       case "vote(s)": $tmp = "vote(s)"; break;
       case "Erreur": $tmp = "Error"; break;
       case "Vous avez déjà noté cette photo": $tmp = "Sorry but you have already rated this file"; break;
       case "Vous avez déjà commenté cette photo": $tmp = "Sorry but you have already commented this file"; break;
       case "Envoyer comme e-carte": $tmp = "Send an e-card"; break;
       case "De la part de": $tmp = "From"; break;
       case "Votre nom": $tmp = "Your name"; break;
       case "Votre adresse e-mail": $tmp = "Your email address"; break;
       case "A": $tmp = "To"; break;
       case "Nom du destinataire": $tmp = "Recipient name"; break;
       case "Adresse e-mail du destinataire": $tmp = "Recipient email address"; break;
       case "Sujet": $tmp = "Subject"; break;
       case "Message": $tmp = "Message"; break;
       case "Si votre e-carte ne s'affiche pas correctement, cliquez ici": $tmp = "If the e-card does not display correctly, click this link"; break;
       case "Résultat": $tmp = "Result"; break;
       case "Votre E-CARTE n'à pas été envoyé.": $tmp = "Your Ecard wasn't sent."; break;
       case "Votre E-CARTE à été envoyé.": $tmp = "Ecard was sent successfully"; break;
       case "Votre adresse mail est incorrecte.": $tmp = "Your address mail is incorrect."; break;
       case "Le nom du destinataire ne peut être vide.": $tmp = "Recipient name cannot be empty."; break;
       case "L'adresse mail du destinataire est incorrecte.": $tmp = "The mail address of recipient is incorrect."; break;
       case "Le sujet ne peut être vide.": $tmp = "Subject cannot be empty."; break;
       case "Le message ne peut être vide.": $tmp = "Message cannot be empty."; break;
       case "Une e-carte pour vous": $tmp = "An e-card for you"; break;
       case "Photos aléatoires": $tmp = "Random files"; break;
       case "Derniers ajouts": $tmp = "Last additions"; break;
       case "Suspendre le Diaporama": $tmp = "Stop Slideshow"; break;
       case "Diaporama": $tmp = "Slideshow"; break;
       case "E-carte": $tmp = "E-card"; break;
       case "Nombre d'images": $tmp = "Number of pictures"; break;
       case "Nombre de commentaires": $tmp = "Number of comments"; break; 
       case "Nombre de notes": $tmp = "Number of notes"; break; 
       case "Top-Commentaires": $tmp = "Top-Comments"; break;
       case "Top-Votes": $tmp = "Top-Vote"; break;
       case "Proposer des images": $tmp = "Propose some pictures"; break;
       case "Top": $tmp = "Top"; break;
       case "IMAGES": $tmp = "PICTURES"; break;
       case "des images les plus commentées": $tmp = "of most commented pictures"; break;
       case "des images les plus notées": $tmp = "of most noted pictures"; break;
       case "Ajouter": $tmp = "Add"; break;
       case "Envoyer": $tmp = "Send"; break;
       case "Vous n'avez accés à aucune galerie": $tmp = "You do not have access to any galery"; break;
       case "Proposer des images": $tmp = "To propose pictures"; break;
       case "Photo envoyée avec succés, elle sera traitée par le webmaster": $tmp = "Photo sent successfully, it will be treated by the webmaster"; break;
       case " proposé par ": $tmp = " proposed by "; break;
       case "Nouvelle soumission de Photos": $tmp = "New soumission of photo"; break;
       case "Des photos viennent d'être proposées dans la galerie photo du site ": $tmp = "Photos were submitted in the photo gallery of the site "; break;
       case " par ": $tmp = " by "; break;
       case "Galerie Privée, connectez vous": $tmp = " Private galery, connect you"; break;
       case "Galerie temporaire": $tmp = "Temporary gallery"; break; 
       case "Image :": $tmp = "Picture :"; break;
       case "Description :": $tmp = "Description :"; break;

       default: $tmp = "Translation error <b>[** $phrase **]</b>"; break;
    }
    return $tmp;
}
?>
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
       case "Catégories": $tmp = "Categor&iacute;as"; break;
       case "Galeries": $tmp = "Albumes"; break;
       case "Aucune catégorie trouvée": $tmp = "Ninguna categor&iacute;a encontrada "; break;
       case "Créée le": $tmp = "Creado el"; break;
       case "Accueil": $tmp = "Inicio"; break;
       case "Aucune galerie trouvée": $tmp = "Ningun album encontrado "; break;
       case "Aucune image trouvée": $tmp = "Ninguna imagen encontrada "; break;
       case "affichage(s)": $tmp = "vista(s)"; break;
       case "commentaire(s)": $tmp = "comentario(s)"; break;
       case "Informations sur l'image": $tmp = "Informaciones de la imagen"; break;
       case "Taille du fichier :": $tmp = "Tama&ntilde;o del fichero:"; break;
       case "Dimensions :": $tmp = "Tama&ntilde;o :"; break;
       case "Affichées :": $tmp = "Mostradas :"; break;
       case "fois": $tmp = "vez(ces)"; break;
       case "Ajoutez votre commentaire": $tmp = "A&ntilde;adir un comentario"; break;
       case "Commentaire": $tmp = "Comentario"; break;
       case "Noter cette image": $tmp = "Notar esta imagen"; break;
       case "Note (": $tmp = "Nota ("; break;
       case "votes) :": $tmp = "votos):"; break;
       case "Erreur": $tmp = "Error"; break;
       case "Vous avez déjà noté cette photo": $tmp = "Lo sentimos, pero ya not&oacute; esta foto"; break;
       case "Vous avez déjà commenté cette photo": $tmp = "Lo sentimos, pero ya coment&oacute; esta foto"; break;
       case "Envoyer comme e-carte": $tmp = "Enviar una E-tarjeta"; break;
       case "De la part de": $tmp = "De la parte de"; break;
       case "Votre nom": $tmp = "Su nombre"; break;
       case "Votre adresse e-mail": $tmp = "Su Email"; break;
       case "A": $tmp = "A"; break;
       case "Nom du destinataire": $tmp = "Nombre del destinatario"; break;
       case "Adresse e-mail du destinataire": $tmp = "Email del destinatario"; break;
       case "Sujet": $tmp = "Asunto"; break;
       case "Message": $tmp = "Mensaje"; break;
       case "Si votre e-carte ne s'affiche pas correctement, cliquez ici": $tmp = "Si su E-tarjeta no se v&eacute; correctamente, haga clic aqu&iacute;"; break;
       case "Résultat": $tmp = "Result"; break;
       case "Votre E-CARTE n'à pas été envoyé.": $tmp = "Su E-tarjeta no fu&eacute; enviada."; break;
       case "Votre E-CARTE à été envoyé.": $tmp = "Su E-tarjeta fu&eacute; enviada."; break;
       case "Votre adresse mail est incorrecte.": $tmp = "Su Email no es valida."; break;
       case "Le nom du destinataire ne peut être vide.": $tmp = "El nombre del destinatario no puede estar vacio."; break;
       case "L'adresse mail du destinataire est incorrecte.": $tmp = "El Email del destinatario no es correcto."; break;
       case "Le sujet ne peut être vide.": $tmp = "El asunto no puede estar vacio."; break;
       case "Le message ne peut être vide.": $tmp = "El mensaje no puede estar vacio."; break;
       case "Une e-carte pour vous": $tmp = "Una E-tarjeta para usted"; break;
       case "Photos aléatoires": $tmp = "Fotograf&iacute;as aleatorias"; break;
       case "Derniers ajouts": $tmp = "&Uacute;ltimas imagenes a&ntilde;adidas"; break;
       case "Suspendre le Diaporama": $tmp = "Suspender la Diapositiva"; break;
       case "Diaporama": $tmp = "Diapositiva"; break;
       case "E-carte": $tmp = "E-tarjeta"; break;
       case "Nombre d'images": $tmp = "Numero de cuadros"; break; 
       case "Nombre de commentaires": $tmp = "Numero de commentarios"; break; 
       case "Nombre de notes": $tmp = "Numero de votos"; break; 
       case "Top-Commentaires": $tmp = "Top-Comentario"; break; 
       case "Top-Votes": $tmp = "Top-Votos"; break;
       case "Proposer des images": $tmp = "Proponer algunos cuadros"; break;
       case "Top": $tmp = "Top"; break;
       case "IMAGES": $tmp = "CUADROS"; break;
       case "des images les plus commentées": $tmp = "la mayoria de los conocidos"; break;
       case "des images les plus notées": $tmp = "la mayoria de los cuadros"; break;
       case "Ajouter": $tmp = "Agregar"; break;
       case "Envoyer": $tmp = "Enviar"; break;
       case "Vous n'avez accés à aucune galerie": $tmp = "No tienes acceso"; break;<br>
       case "Proposer des images": $tmp = "Para proponer cuadros"; break;
       case "Photo envoyée avec succés, elle sera traitée par le webmaster": $tmp = "Foto enviada con exito, sera manejado por el webmaster";
       case " proposé par ": $tmp = " propuesto por "; break;
       case "Nouvelle soumission de Photos": $tmp = "Fotos nueva presentaci&oacute;n"; break;
       case "Des photos viennent d'être proposées dans la galerie photo du site ": $tmp = "Fotos fueron presentados en la galeria de fotos del sitio "; break;
       case " par ": $tmp = " por "; break;
       case "Galerie Privée, connectez vous": $tmp = " Galeria privado, Conectarle"; break;
       case "Galerie temporaire": $tmp = "Temporal de la galeria"; break; 
       case "Image :": $tmp = "Cuadro :"; break;
       case "Description :": $tmp = "Descripcion :"; break;

       default: $tmp = "Necesita ser traducido <b>[** $phrase **]</b>"; break;
    }
    return $tmp;
}
?>
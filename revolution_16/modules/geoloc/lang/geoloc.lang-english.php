<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/*                                                                      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/*                                                                      */
/* module geoloc version 4.1                                            */
/* geoloc.lang-english.php file 2008-2021 by Jean Pierre Barbary (jpb)  */
/* dev team : Philippe Revilliod (Phr), A.NICOL                         */
/************************************************************************/

function geoloc_translate($phrase) {
   switch($phrase) {
      case "Admin": $tmp="Admin"; break;
      case "Adresse IP bannie !": $tmp="IP address banned!"; break;
      case "Adresse IP signalée par l’antispam !": $tmp="IP address reported by Antispam !"; break;
      case "Aide": $tmp="Help"; break;
      case "Anonyme en ligne": $tmp="Anonymous on line"; break;
      case "Anonyme géoréférencé en ligne": $tmp="Georeferenced anonymous on line"; break;
      case "Anonyme": $tmp="Anonymous"; break;
      case "Anonyme(s) :": $tmp="Anonymous:"; break;
      case "Carte": $tmp="Map"; break;
      case "Centre carte :": $tmp="Map center:"; break;
      case "Centrer": $tmp="Center map here"; break;
      case "Champ de table pour latitude": $tmp="DB field for latitude"; break;
      case "Champ de table pour longitude": $tmp="DB field for longitude"; break;
      case "Chemin des images": $tmp="Images path"; break;
      case "Clef d'API": $tmp="API key"; break;
      case "Cliquer sur la carte pour définir votre géolocalisation.": $tmp="Click the map to define your geolocation."; break;
      case "Cliquer sur la carte pour modifier votre position.": $tmp="Click the map to change your geolocation."; break;
      case "Cliquez sur la carte pour vous localiser ou modifier votre position.": $tmp="Click on the map to or change your location."; break;
      case "Coins carte :": $tmp="Map corners:"; break;
      case "Configuration du module Geoloc": $tmp="Geoloc addon settings"; break;
      case "Coordonnées en pixel :": $tmp="Pixels coordinates:"; break;
      case "Coordonnées enregistrées :": $tmp="Registered coordinates:"; break;
      case "Côtes et frontières": $tmp="Coasts and borders"; break;
      case "Couches utilitaires": $tmp="Utility layers"; break;
      case "Couleur du trait": $tmp="Line color"; break;
      case "Couleur fond": $tmp="Background color"; break;
      case "Définir ou modifier votre position.": $tmp="Define or change your location."; break;
      case "Dernière visite": $tmp="Last visit"; break;
      case "Dessin": $tmp="Drawing"; break;
      case "Echelle": $tmp="Scale"; break;
      case "En ligne": $tmp="On line"; break;
      case "En visite ici": $tmp="Visiting this page"; break;
      case "Enregistrez": $tmp="Save"; break;
      case "Entrez une adresse": $tmp="Enter an address"; break;
      case "Envoyez un message interne": $tmp="Send an internal message"; break;
      case "Epaisseur du trait": $tmp="Line thickness"; break;
      case "Filtrer les résultats": $tmp="Filter results"; break;
      case "Filtrer les utilisateurs": $tmp="Filter users"; break;
      case "Fonctions": $tmp="Functions"; break;
      case "Géocodage a échoué pour la raison suivante": $tmp="Geocode was not successful for the following reason"; break;
      case "Géocodage": $tmp="Geocoding"; break;
      case "Géocoder": $tmp="Geocode"; break;
      case "Géolocalisation des IP": $tmp="IP geolocation"; break;
      case "Géolocalisation des membres du site": $tmp="Site members geolocation"; break;
      case "Géolocalisation": $tmp="Geolocation"; break;
      case "Géoréférencés : ": $tmp="Georeferenced:"; break;
      case "Grille": $tmp="Grid"; break;
      case "Hauteur de la carte dans le bloc": $tmp="Map height in block"; break;
      case "Hauteur icône des marqueurs": $tmp="Marker icon height"; break;
      case "Hôte": $tmp="Host"; break;
      case "Hybride": $tmp="Mixte"; break;
      case "Image anonyme géoréférencé en ligne": $tmp="Georeferenced anonymous on line image"; break;
      case "Image membre géoréférencé en ligne": $tmp="Georeferenced member on line image"; break;
      case "Image membre géoréférencé": $tmp="Georeferenced member image"; break;
      case "Infos carte": $tmp="Map info"; break;
      case "Interface bloc": $tmp="Map bloc UI"; break;
      case "Interface carte": $tmp="Map UI"; break;
      case "Ip liste": $tmp="Ip list"; break;
      case "IP non géoréférencé en ligne": $tmp="Ungeoreferenced IP on line"; break;
      case "Largeur icône des marqueurs": $tmp="Marker icon width"; break;
      case "Latitude :": $tmp="Latitude:"; break;
      case "Latitude": $tmp="Latitude"; break;
      case "Les adresses IP sont enregistrées.": $tmp="IP adress are registered."; break;
      case "Longitude :": $tmp="Longitude:"; break;
      case "Longitude": $tmp="Longitude"; break;
      case "Marqueur font SVG": $tmp="Marker SVG font"; break;
      case "Marqueur images de type png, gif, jpeg.": $tmp="Marker image png, gif, jpeg."; break;
      case "Marqueur SVG font ou objet vectoriel.": $tmp=" Marker SVG font or vector object."; break;
      case "Masquer": $tmp="Hide"; break;
      case "Membre en ligne": $tmp="Member on line"; break;
      case "Membre géoréférencé en ligne": $tmp="Georeferenced member on line"; break;
      case "Membre géoréférencé": $tmp="Georeferenced member"; break;
      case "Membre": $tmp="Member"; break;
      case "Membre(s) :": $tmp="Member:"; break;
      case "Membres du site": $tmp="Website members"; break;
      case "Modification administrateur": $tmp="Admin mode"; break;
      case "Noir et blanc": $tmp="Black and white"; break;
      case "Non géoréférencés : ": $tmp="Ungeoreferenced:"; break;
      case "Non": $tmp="No"; break;
      case "Opacité du fond": $tmp="Background opacity"; break;
      case "Opacité du trait": $tmp="Line opacity"; break;
      case "Opacité": $tmp="Opacity"; break;
      case "Oui": $tmp="Yes"; break;
      case "Paramètres système": $tmp="System parameters"; break;
      case "Pays": $tmp="Country"; break;
      case "Plan": $tmp="Plan"; break;
      case "Posts/Commentaires": $tmp="Posts/Comments"; break;
      case "Rappel : vous êtes en mode administrateur !": $tmp="Notice: you are in admin mode !"; break;
      case "Relief": $tmp="Terrain"; break;
      case "requis": $tmp="required"; break;
      case "Satellite": $tmp="Satellite"; break;
      case "Sauver": $tmp="Save"; break;
      case "Taille de la table": $tmp="Size of the database table"; break;
      case "Type de carte": $tmp="Map type"; break;
      case "Type de marqueur": $tmp="Marker type"; break;
      case "Unité des coordonnées": $tmp="Coordinates unit"; break;
      case "Vider la table des IP géoréférencées": $tmp="Truncate the table of georeferenced IP."; break;
      case "Ville": $tmp="City"; break;
      case "Visites": $tmp="Hits"; break;
      case "Visiteur en ligne": $tmp="On line visitor"; break;
      case "Visitez le minisite": $tmp="Visit the minisite"; break;
      case "Visitez le site": $tmp="Visit the site"; break;
      case "Voir ou masquer anonymes géoréférencés": $tmp="Show or hide anonymous on line"; break;
      case "Voir ou masquer les IP": $tmp="Show or hide IP"; break;
      case "Voir ou masquer membres géoréférencés en ligne": $tmp="Show or hide georeferenced member on line"; break;
      case "Voir ou masquer membres géoréférencés": $tmp="Show or hide georeferenced member"; break;
      case "Voir sur la carte": $tmp="Show on the map"; break;
      case "Voir": $tmp="Show"; break;
      case "Voulez vous changer pour :": $tmp="Would you change to:"; break;
      case "Voulez vous le faire à cette position :": $tmp="Would you get one at this position:"; break;
      case "Vous n'êtes pas géoréférencé.": $tmp="You have not geolocation."; break;
      case "Zoom arrière ici": $tmp="Zoom out here"; break;
      case "Zoom arrière": $tmp="Zoom out"; break;
      case "Zoom avant ici": $tmp="Zoom in here"; break;
      case "Zoom avant": $tmp="Zoom in"; break;
      case "Zoom": $tmp="Zoom"; break;
      default: $tmp = "Need to be translated [** $phrase **]"; break;
   }
   return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/*                                                                      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2017 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/*                                                                      */
/* module geoloc version 3.0                                            */
/* geoloc.lang-english.php file 2008-2015 by Jean Pierre Barbary (jpb)  */
/* dev team : Philippe Revilliod (phr)                                  */
/************************************************************************/

function geoloc_translate($phrase) {
 switch($phrase) {
case "Latitude": $tmp="Latitude"; break;
case "Longitude": $tmp="Longitude"; break;
case "Latitude :": $tmp="Latitude:"; break;
case "Longitude :": $tmp="Longitude:"; break;
case "Enregistrez": $tmp="Save"; break;
case "Membre(s) :": $tmp="Member:"; break;
case "Anonyme(s) :": $tmp="Anonymous:"; break;
case "Anonyme": $tmp="Anonymous"; break;
case "Voir": $tmp="Show"; break;
case "Masquer": $tmp="Hide"; break;
case "Visiteur en ligne": $tmp="On line visitor"; break;
case "En ligne : ": $tmp="On line: "; break;
case "Géoréférencés : ": $tmp="Georeferenced:"; break;
case "Non géoréférencés : ": $tmp="Ungeoreferenced:"; break;
case "Anonyme géoréférencé en ligne": $tmp="Georeferenced anonymous on line"; break;
case "Membre géoréférencé en ligne": $tmp="Georeferenced member on line"; break;
case "Membre géoréférencé": $tmp="Georeferenced member"; break;
case "IP non géoréférencé en ligne": $tmp="Ungeoreferenced IP on line"; break;
case "Vous n'êtes pas géoréférencé.": $tmp="You have not geolocation."; break;
case "Voulez vous le faire à cette position :": $tmp="Would you get one at this position:"; break;
case "Cliquer sur la carte pour définir votre géolocalisation.": $tmp="Click the map to define your geolocation."; break;
case "Cliquer sur la carte pour modifier votre position.": $tmp="Click the map to change your geolocation."; break;
case "Coordonnées enregistrées :": $tmp="Registered coordinates:"; break;
case "Voulez vous changer pour :": $tmp="Would you change to:"; break;
case "Cliquez sur la carte pour vous localiser ou modifier votre position.": $tmp="Click on the map to or change your location."; break;
case "Voir ou masquer membres géoréférencés": $tmp="Show or hide georeferenced member"; break;
case "Voir ou masquer anonymes géoréférencés": $tmp="Show or hide anonymous on line"; break;
case "Voir ou masquer membres géoréférencés en ligne": $tmp="Show or hide georeferenced member on line"; break;
case "Désolé les API de Google Maps ne sont pas compatibles avec votre navigateur": $tmp="Sorry, the Google Maps API is not compatible with this browser"; break;
case "En visite ici : ": $tmp="Visiting this page:"; break;
case "Visites : ": $tmp="Hits:"; break;
case "Dernière visite : ": $tmp="Last visite:"; break;
case "Pays :": $tmp="Country:"; break;
case "Ville": $tmp="City"; break;
case "Les adresses IP sont enregistrées.": $tmp="IP adress are registered."; break;
case "Chargement en cours...<br />Ou serveurs Google HS...<br />Ou erreur...": $tmp="Now Loading...<br />Or Google serveur are down...<br />Or error..."; break;
case "Hôte : ": $tmp="Host:"; break;
case "Zoom :": $tmp="Zoom:"; break;
case "Zoom avant": $tmp="Zoom in"; break;
case "Zoom arrière": $tmp="Zoom out"; break;
case "Zoom avant ici": $tmp="Zoom in here"; break;
case "Zoom arrière ici": $tmp="Zoom out here"; break;
case "Centrer": $tmp="Centre map here"; break;
case "Type de carte": $tmp="Map type"; break;
case "Satellite": $tmp="Satellite"; break;
case "Plan": $tmp="Plan"; break;
case "Hybride": $tmp="Mixte"; break;
case "Carte": $tmp="Map"; break;
case "Admin": $tmp="Admin"; break;
case "Centre carte :": $tmp="Map center:"; break;
case "Coins carte :": $tmp="Map corners:"; break;
case "Coordonnées en pixel :": $tmp="Pixels coordinates:"; break;
case "Tiles :": $tmp="Tiles:"; break;
case "Wrt tile :": $tmp="Wrt tile:"; break;
case "Coord tile :": $tmp="Coord tile :"; break;
case "Aide": $tmp="Help"; break;
case "Infos carte": $tmp="Map info"; break;
case "Modification administrateur": $tmp="Admin mode"; break;
case "Rappel : vous êtes en mode administrateur !": $tmp="Notice: you are in admin mode !"; break;
default: $tmp = "Need to be translated [** $phrase **]"; break;
 }
  return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
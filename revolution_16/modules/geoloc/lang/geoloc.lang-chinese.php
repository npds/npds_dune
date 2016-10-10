<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/*                                                                      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2008 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/*                                                                      */
/* module geoloc version 1.0                                            */
/* geoloc.lang-chinese.php file 2007 by Jean Pierre Barbary (jpb)       */
/* dev team : Philippe Revilliod (Arenan)                               */
/************************************************************************/

function geoloc_translate($phrase) {
 switch($phrase) {
  
case "Latitude": $tmp="&#x7ECF;&#x5EA6;"; break;
case "Longitude": $tmp="&#x7EAC;&#x5EA6;"; break;
case "Latitude :": $tmp="&#x7ECF;&#x5EA6;:"; break;
case "Longitude :": $tmp="&#x7EAC;&#x5EA6;:"; break;
case "Enregistrez": $tmp="&#x4FDD;&#x5B58;"; break;
case "Membre(s) :": $tmp="&#x4F4D;&#x7528;&#x6237;:"; break;
case "Anonyme(s) :": $tmp="&#x533F;&#x540D;&#x6E38;&#x5BA2;:"; break;
case "Anonyme": $tmp="&#x533F;&#x540D;&#x6E38;&#x5BA2;"; break;
case "Voir": $tmp="&#x663E;&#x793A;"; break;
case "Masquer": $tmp="Hide"; break;
case "En ligne : ": $tmp="&#x5728;&#x7EBF;: "; break;
case "G&#xE9;or&#xE9;f&#xE9;renc&#xE9;s : ": $tmp="Georeferenced:"; break;
case "Non g&#xE9;or&#xE9;f&#xE9;renc&#xE9;s : ": $tmp="Ungeoreferenced:"; break;
case "Anonyme g&#xE9;or&#xE9;f&#xE9;renc&#xE9; en ligne": $tmp="Georeferenced anonymous on line"; break;
case "Membre g&#xE9;or&#xE9;f&#xE9;renc&#xE9; en ligne": $tmp="Georeferenced member on line"; break;
case "Membre g&#xE9;or&#xE9;f&#xE9;renc&#xE9": $tmp="Georeferenced member"; break;
case "IP non g&#xE9;or&#xE9;f&#xE9;renc&#xE9; en ligne": $tmp="Ungeoreferenced IP on line"; break;
case "Vous n'&#xEA;tes pas g&#xE9;or&#xE9;f&#xE9;renc&#xE9.": $tmp="You have not geolocation."; break;
case "Voulez vous le faire &#xE0; cette position :": $tmp="Would you get one at this position:"; break;
case "Cliquer sur la carte pour d&#xE9;finir votre g&#xE9;olocalisation.": $tmp="Click the map to define your geolocation."; break;
case "Cliquer sur la carte pour modifier votre position.": $tmp="Click the map to change your geolocation."; break;
case "Coordonn&#xE9;es enregistr&#xE9;es :": $tmp="Registered coordinates:"; break;
case "Voulez vous changer pour :": $tmp="Would you change to:"; break;
case "Cliquez sur la carte pour vous localiser ou modifier votre position.": $tmp="Click on the map to or change your location."; break;
case "Voir ou masquer membres g&#xE9;or&#xE9;f&#xE9;renc&#xE9;s": $tmp="Show or hide georeferenced member"; break;
case "Voir ou masquer anonymes g&#xE9;or&#xE9;f&#xE9;renc&#xE9;s": $tmp="Show or hide anonymous on line"; break;
case "Voir ou masquer membres g&#xE9;or&#xE9;f&#xE9;renc&#xE9;s en ligne": $tmp="Show or hide georeferenced member on line"; break;
case "D&#xE9;sol&#xE9; les API de Google Maps ne sont pas compatibles avec votre navigateur": $tmp="Sorry, the Google Maps API is not compatible with this browser"; break;
case "En visite ici : ": $tmp="Visiting this page:"; break;
case "Visites : ": $tmp="Hits:"; break;
case "Derni&#xE8;re visite : ": $tmp="Last visite:"; break;
case "Pays :": $tmp="&#x56FD;&#x5BB6;:"; break;
case "Ville": $tmp="&#x57CE;&#x5E02;"; break;
case "Les adresses IP sont enregistr&#xE9;es.": $tmp="&#x60A8;&#x7684;IP&#x5730;&#x5740;&#x5DF2;&#x88AB;&#x767B;&#x8BB0;"; break;
case "Chargement en cours...<br />Ou serveurs Google HS...<br />Ou erreur...": $tmp="Now Loading...<br />Or Google serveur are down...<br />Or error..."; break;
case "H&#xF4;te : ": $tmp="Host:"; break;
case "Javascript doit &#xEA;tre activ&#xE9; pour utiliser les API de Google Maps.": $tmp="JavaScript must be enabled in order for you to use Google Maps API."; break;
case "Il semble qu'il soit d&#xE9;sactiv&#xE9; ou que votre navigateur ne soit pas compatible.": $tmp="However, it seems JavaScript is either disabled or not supported by your browser."; break;
case "Activez Javascript en modifiant les options de votre navigateur et r&#xE9;essayez.": $tmp="To view Google Maps, enable JavaScript by changing your browser options, and then try again."; break;
case "Zoom :": $tmp="Zoom:"; break;
case "Zoom avant": $tmp="Zoom in"; break;
case "Zoom arri&#xE8;re": $tmp="Zoom out"; break;
case "Zoom avant ici": $tmp="Zoom in here"; break;
case "Zoom arri&#xE8;re ici": $tmp="Zoom out here"; break;
case "Centrer": $tmp="Centre map here"; break;
case "Type de carte": $tmp="Map type"; break;
case "Satellite": $tmp="Satellite"; break;
case "Plan": $tmp="Plan"; break;
case "Hybride": $tmp="Mixte"; break;
case "Carte": $tmp="&#x5730;&#x56FE;"; break;
case "Admin": $tmp="Admin"; break;
case "Centre carte :": $tmp="Map center:"; break;
case "Coins carte :": $tmp="Map corners:"; break;
case "Coordonn&#xE9;es en pixel :": $tmp="Pixels coordinates:"; break;
case "Tiles :": $tmp="Tiles:"; break;
case "Wrt tile :": $tmp="Wrt tile:"; break;
case "Coord tile :": $tmp="Coord tile :"; break;
case "Aide": $tmp="Help"; break;
case "Infos carte": $tmp="Map info"; break;
default: $tmp = "Need to be translated <b>[** $phrase **]</b>"; break;
 }
 if (cur_charset=="utf-8") {
    return utf8_encode($tmp);
 } else {
    return ($tmp);
 }
}
?>
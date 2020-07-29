<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/*                                                                      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2019 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/*                                                                      */
/* module geoloc version 4.0                                            */
/* geoloc.lang-spanish.php file 2008-2019 by Jean Pierre Barbary (jpb)  */
/* dev team : Philippe Revilliod (phr)                                  */
/************************************************************************/

function geoloc_translate($phrase) {
   switch($phrase) {
      case "Latitude": $tmp="Latitud"; break;
      case "Longitude": $tmp="Longitud"; break;
      case "Latitude :": $tmp="Latitud:"; break;
      case "Longitude :": $tmp="Longitud:"; break;
      case "Enregistrez": $tmp="Salvar"; break;
      case "Membre(s) :": $tmp="Miembro:"; break;
      case "Anonyme(s) :": $tmp="Anónimo:"; break;
      case "Anonyme": $tmp="Anónimo"; break;
      case "Voir": $tmp="Mostrar"; break;
      case "Masquer": $tmp="Ocultar"; break;
      case "Visiteur en ligne": $tmp="Visitantes en línea"; break;
      case "En ligne": $tmp="En línea"; break;
      case "Géoréférencés : ": $tmp="Georeferenciada:"; break;
      case "Non géoréférencés : ": $tmp="No georeferenciada:"; break;
      case "Anonyme géoréférencé en ligne": $tmp="Georeferenced anonymous on line"; break;
      case "Membre géoréférencé en ligne": $tmp="Georeferenced member on line"; break;
      case "Membre géoréférencé": $tmp="Miembro geolocalizados"; break;
      case "IP non géoréférencé en ligne": $tmp="Ungeoreferenced IP on line"; break;
      case "Vous n'êtes pas géoréférencé.": $tmp="You have not geolocation."; break;
      case "Voulez vous le faire à cette position :": $tmp="Would you get one at this position:"; break;
      case "Cliquer sur la carte pour définir votre géolocalisation.": $tmp="Click the map to define your geolocation."; break;
      case "Cliquer sur la carte pour modifier votre position.": $tmp="Click the map to change your geolocation."; break;
      case "Coordonnées enregistrées :": $tmp="Registered coordinates:"; break;
      case "Voulez vous changer pour :": $tmp="¿Quieres cambiar:"; break;
      case "Cliquez sur la carte pour vous localiser ou modifier votre position.": $tmp="Haga clic en el mapa para localizar a usted o cambiar su posición."; break;
      case "Voir ou masquer membres géoréférencés": $tmp="Show or hide georeferenced member"; break;
      case "Voir ou masquer anonymes géoréférencés": $tmp="Show or hide anonymous on line"; break;
      case "Voir ou masquer membres géoréférencés en ligne": $tmp="Show or hide georeferenced member on line"; break;
      case "Désolé les API de Google Maps ne sont pas compatibles avec votre navigateur": $tmp="Sorry, the Google Maps API is not compatible with this browser"; break;
      case "En visite ici": $tmp="Visiting this page"; break;
      case "Visites": $tmp="Visitas"; break;
      case "Dernière visite : ": $tmp="Última visita:"; break;
      case "Pays": $tmp="País"; break;
      case "Ville": $tmp="Ciutad"; break;
      case "Les adresses IP sont enregistrées.": $tmp="Las direcciones IP son registradas."; break;
      case "Chargement en cours...Ou serveurs Google HS...Ou erreur...": $tmp="Now Loading...<br />Or Google serveur are down...<br />Or error..."; break;
      case "Hôte": $tmp="Proveedor de alojamiento web"; break;
      case "Zoom :": $tmp="Zoom:"; break;
      case "Zoom avant": $tmp="Zoom in"; break;
      case "Zoom arrière": $tmp="Zoom out"; break;
      case "Zoom avant ici": $tmp="Zoom in aquí"; break;
      case "Zoom arrière ici": $tmp="Zoom out aquí"; break;
      case "Centrer": $tmp="Centrar el mapa aquí"; break;
      case "Type de carte": $tmp="Tipo de mapa"; break;
      case "Satellite": $tmp="Satélite"; break;
      case "Plan": $tmp="Plan"; break;
      case "Hybride": $tmp="Mixto"; break;
      case "Carte": $tmp="Mapa"; break;
      case "Admin": $tmp="Administración"; break;
      case "Centre carte :": $tmp="Centro del mapa:"; break;
      case "Coins carte :": $tmp="Esquinas del mapa:"; break;
      case "Coordonnées en pixel :": $tmp="Coordenadas en píxeles:"; break;
      case "Tiles :": $tmp="Tiles:"; break;
      case "Wrt tile :": $tmp="Wrt tile:"; break;
      case "Coord tile :": $tmp="Coord tile :"; break;
      case "Aide": $tmp="Ayuda"; break;
      case "Infos carte": $tmp="Información sobre el mapa"; break;
      case "Modification administrateur": $tmp="Cambios en el modo de administrador"; break;
      case "Rappel : vous êtes en mode administrateur !": $tmp="Recordatorio: está en el modo de administrador !"; break;
      case "Configuration du module Geoloc": $tmp="La configuración del módulo Geoloc"; break;
      case "Paramètres système": $tmp="System parameters"; break;
      case "Clef d'API": $tmp="Clave de la API"; break;
      case "Champ de table pour latitude": $tmp="DB field for latitude"; break;
      case "Champ de table pour longitude": $tmp="DB field for longitude"; break;
      case "requis": $tmp="requerida"; break;
      case "Chemin des images": $tmp="imágenes ruta"; break;
      case "Unité des coordonnées": $tmp="Coordinates unit"; break;
      case "Type de marqueur": $tmp="Marker type"; break;
      case "Opacité du fond": $tmp="Opacidad fondo"; break;
      case "Opacité du trait": $tmp="Opacidad línea"; break;
      case "Couleur fond": $tmp="Color de fondo"; break;
      case "Couleur du trait": $tmp="Línea de color"; break;
      case "Epaisseur du trait": $tmp="Grosor de las líneas"; break;
      case "Membre": $tmp="Miembro"; break;
      case "Membre en ligne": $tmp="Miembros conectados"; break;
      case "Anonyme en ligne": $tmp="Anónimo conectados"; break;
      case "Marqueur font SVG": $tmp="Marker SVG font"; break;
      case "Marqueur SVG font ou objet vectoriel.": $tmp=" Marker SVG font or vector object."; break;
      case "Marqueur images de type png, gif, jpeg.": $tmp="Marker image png, gif, jpeg."; break;
      case "Interface carte": $tmp="Interfaz de la mapa"; break;
      case "Interface bloc": $tmp="Interfaz para el mapa de bloques"; break;
      case "Largeur icône des marqueurs": $tmp="Anchura del icono del marcador"; break;
      case "Hauteur icône des marqueurs": $tmp="Altura del icono del marcador"; break;
      case "Image membre géoréférencé": $tmp="Georeferenced member image"; break;
      case "Image membre géoréférencé en ligne": $tmp="Georeferenced member on line image"; break;
      case "Image anonyme géoréférencé en ligne": $tmp="Georeferenced anonymous on line image"; break;
      case "Echelle": $tmp="Escala"; break;
      case "Hauteur de la carte dans le bloc": $tmp="Altura del mapa en el bloque"; break;
      case "Oui": $tmp="Si"; break;
      case "Non": $tmp="No"; break;
      case "Géolocalisation": $tmp="Geolocalización"; break;
      case "Géolocalisation des IP": $tmp="Geolocalización IP"; break;
      case "Sauver": $tmp="Guardar"; break;
      case "Ip liste": $tmp="Lista de direcciones IP"; break;
      case "Taille de la table": $tmp="Tamaño de la tabla de base de datos"; break;
      case "Vider la table des IP géoréférencées": $tmp="Vaciar la tabla IP de base de datos de geolocalización IP."; break;
      case "Entrez une adresse": $tmp="Introducir una dirección"; break;
      case "Géolocalisation des membres du site": $tmp="Geolocalización miembros de la sitio web"; break;
      case "Géocodage": $tmp="Geocodificación"; break;
      case "Géocodage a échoué pour la raison suivante": $tmp="Error de geocodificación por la siguiente razón"; break;
      case "Géocoder": $tmp="Geocodificación"; break;
      case "Voir ou masquer les IP": $tmp="Mostrar u ocultar direcciones IP"; break;
      default: $tmp = "Ser necesario traducir [** $phrase **]"; break;
   }
   return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
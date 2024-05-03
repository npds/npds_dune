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
/* geoloc.lang-spanish.php file 2008-2021 by Jean Pierre Barbary (jpb)  */
/* dev team : Philippe Revilliod (Phr), A.NICOL                         */
/************************************************************************/

function geoloc_translate($phrase) {
   switch($phrase) {
      case "Admin": $tmp="Administración"; break;
      case "Adresse IP bannie !": $tmp="Dirección IP prohibida!"; break;
      case "Adresse IP signalée par l’antispam !": $tmp="Dirección IP reportada por Antispam !"; break;
      case "Aide": $tmp="Ayuda"; break;
      case "Anonyme en ligne": $tmp="Anónimo conectados"; break;
      case "Anonyme géoréférencé en ligne": $tmp="Anónimo georreferenciado en línea"; break;
      case "Anonyme": $tmp="Anónimo"; break;
      case "Anonyme(s) :": $tmp="Anónimo:"; break;
      case "Carte": $tmp="Mapa"; break;
      case "Centre carte :": $tmp="Centro del mapa:"; break;
      case "Centrer": $tmp="Centrar el mapa aquí"; break;
      case "Champ de table pour latitude": $tmp="Campo de tabla mysql para latitud"; break;
      case "Champ de table pour longitude": $tmp="Campo de tabla mysql para longitud"; break;
      case "Chemin des images": $tmp="Imágenes ruta"; break;
      case "Clef d'API": $tmp="Clave de la API"; break;
      case "Cliquer sur la carte pour définir votre géolocalisation.": $tmp="Haga clic en el mapa para definir su geolocalización."; break;
      case "Cliquer sur la carte pour modifier votre position.": $tmp="Haga clic en el mapa para cambiar su posición."; break;
      case "Cliquez sur la carte pour vous localiser ou modifier votre position.": $tmp="Haga clic en el mapa para localizar a usted o cambiar su posición."; break;
      case "Coins carte :": $tmp="Esquinas del mapa:"; break;
      case "Configuration du module Geoloc": $tmp="La configuración del módulo Geoloc"; break;
      case "Coordonnées en pixel :": $tmp="Coordenadas en píxeles:"; break;
      case "Coordonnées enregistrées :": $tmp="Coordenadas registradas:"; break;
      case "Côtes et frontières": $tmp="Costas y fronteras"; break;
      case "Couches utilitaires": $tmp="Capas de utilidad"; break;
      case "Couleur du trait": $tmp="Línea de color"; break;
      case "Couleur fond": $tmp="Color de fondo"; break;
      case "Définir ou modifier votre position.": $tmp="Definir o modificar posición."; break;
      case "Dernière visite": $tmp="Última Visita"; break;
      case "Dessin": $tmp="Dibujo"; break;
      case "Echelle": $tmp="Escala"; break;
      case "En ligne": $tmp="En línea"; break;
      case "En visite ici": $tmp="De visita aquí"; break;
      case "Enregistrez": $tmp="Salvar"; break;
      case "Entrez une adresse": $tmp="Introducir una dirección"; break;
      case "Envoyez un message interne": $tmp="Enviar un mensaje interno"; break;
      case "Epaisseur du trait": $tmp="Grosor de las líneas"; break;
      case "Filtrer les résultats": $tmp="filtrar los resultados"; break;
      case "Filtrer les utilisateurs": $tmp="Filtrar a los usuarios"; break;
      case "Fonctions": $tmp="Funciones"; break;
      case "Géocodage a échoué pour la raison suivante": $tmp="Error de geocodificación por la siguiente razón"; break;
      case "Géocodage": $tmp="Geocodificación"; break;
      case "Géocoder": $tmp="Geocodificación"; break;
      case "Géolocalisation des IP": $tmp="Geolocalización IP"; break;
      case "Géolocalisation des membres du site": $tmp="Geolocalización miembros de la sitio web"; break;
      case "Géolocalisation": $tmp="Geolocalización"; break;
      case "Géoréférencés : ": $tmp="Georeferenciada:"; break;
      case "Grille": $tmp="Cuadrícula"; break;
      case "Hauteur de la carte dans le bloc": $tmp="Altura del mapa en el bloque"; break;
      case "Hauteur icône des marqueurs": $tmp="Altura del icono del marcador"; break;
      case "Hôte": $tmp="Proveedor de alojamiento web"; break;
      case "Hybride": $tmp="Mixto"; break;
      case "Image anonyme géoréférencé en ligne": $tmp="Georeferenced anonymous on line image"; break;
      case "Image membre géoréférencé en ligne": $tmp="Georeferenced member on line image"; break;
      case "Image membre géoréférencé": $tmp="Georeferenced member image"; break;
      case "Infos carte": $tmp="Información sobre el mapa"; break;
      case "Interface bloc": $tmp="Interfaz para el mapa de bloques"; break;
      case "Interface carte": $tmp="Interfaz de la mapa"; break;
      case "Ip liste": $tmp="Lista de direcciones IP"; break;
      case "IP non géoréférencé en ligne": $tmp="IP no referenciada en línea"; break;
      case "Largeur icône des marqueurs": $tmp="Anchura del icono del marcador"; break;
      case "Latitude :": $tmp="Latitud:"; break;
      case "Latitude": $tmp="Latitud"; break;
      case "Les adresses IP sont enregistrées.": $tmp="Las direcciones IP son registradas."; break;
      case "Longitude :": $tmp="Longitud:"; break;
      case "Longitude": $tmp="Longitud"; break;
      case "Marqueur font SVG": $tmp="Marker SVG font"; break;
      case "Marqueur images de type png, gif, jpeg.": $tmp="Marcador de imagen png, gif, jpeg."; break;
      case "Marqueur SVG font ou objet vectoriel.": $tmp=" Marker SVG font or vector object."; break;
      case "Masquer": $tmp="Ocultar"; break;
      case "Membre en ligne": $tmp="Miembros conectados"; break;
      case "Membre géoréférencé en ligne": $tmp="Miembro georreferenciado en línea"; break;
      case "Membre géoréférencé": $tmp="Miembro geolocalizados"; break;
      case "Membre": $tmp="Miembro"; break;
      case "Membre(s) :": $tmp="Miembro:"; break;
      case "Membres du site": $tmp="Miembros del sitio"; break;
      case "Modification administrateur": $tmp="Cambios en el modo de administrador"; break;
      case "Noir et blanc": $tmp="Blanco y negro"; break;
      case "Non géoréférencés : ": $tmp="No georeferenciada:"; break;
      case "Non": $tmp="No"; break;
      case "Opacité du fond": $tmp="Opacidad fondo"; break;
      case "Opacité du trait": $tmp="Opacidad línea"; break;
      case "Opacité": $tmp="Opacidad"; break;
      case "Oui": $tmp="Si"; break;
      case "Paramètres système": $tmp="Parámetros del sistema"; break;
      case "Pays": $tmp="País"; break;
      case "Plan": $tmp="Plan"; break;
      case "Posts/Commentaires": $tmp="Posts/Comentarios"; break;
      case "Rappel : vous êtes en mode administrateur !": $tmp="Recordatorio: está en el modo de administrador !"; break;
      case "Relief": $tmp="Relieve"; break;
      case "requis": $tmp="requerida"; break;
      case "Satellite": $tmp="Satélite"; break;
      case "Sauver": $tmp="Guardar"; break;
      case "Taille de la table": $tmp="Tamaño de la tabla de base de datos"; break;
      case "Type de carte": $tmp="Tipo de mapa"; break;
      case "Type de marqueur": $tmp="Marker type"; break;
      case "Unité des coordonnées": $tmp="Unidad de coordenadas"; break;
      case "Vider la table des IP géoréférencées": $tmp="Vaciar la tabla IP de base de datos de geolocalización IP."; break;
      case "Ville": $tmp="Ciutad"; break;
      case "Visites": $tmp="Visitas"; break;
      case "Visiteur en ligne": $tmp="Visitantes en línea"; break;
      case "Visitez le minisite": $tmp="Visitar la minisitio web"; break;
      case "Visitez le site": $tmp="Visitar el sitio"; break;
      case "Voir ou masquer anonymes géoréférencés": $tmp="Ver u ocultar anónimos georeferenciados"; break;
      case "Voir ou masquer les IP": $tmp="Mostrar u ocultar direcciones IP"; break;
      case "Voir ou masquer membres géoréférencés en ligne": $tmp="Mostrar u ocultar miembro georeferenciado en línea"; break;
      case "Voir ou masquer membres géoréférencés": $tmp="Mostrar u ocultar miembro georeferenciado"; break;
      case "Voir sur la carte": $tmp="Mostrar en el mapa"; break;
      case "Voir": $tmp="Mostrar"; break;
      case "Voulez vous changer pour :": $tmp="¿Quieres cambiar:"; break;
      case "Voulez vous le faire à cette position :": $tmp="Would you get one at this position:"; break;
      case "Vous n'êtes pas géoréférencé.": $tmp="Usted no tiene geolocalización."; break;
      case "Zoom arrière ici": $tmp="Zoom out aquí"; break;
      case "Zoom arrière": $tmp="Zoom out"; break;
      case "Zoom avant ici": $tmp="Zoom in aquí"; break;
      case "Zoom avant": $tmp="Zoom in"; break;
      case "Zoom": $tmp="Zoom"; break;
      default: $tmp = "Ser necesario traducir [** $phrase **]"; break;
   }
   return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
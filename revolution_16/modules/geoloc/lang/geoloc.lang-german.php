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
/* geoloc.lang-german.php file 2008-2021                                */
/* dev team : Philippe Revilliod (Phr), A.NICOL                         */
/************************************************************************/

function geoloc_translate($phrase) {
   switch($phrase) {
      case "Admin": $tmp="Admin"; break;
      case "Adresse IP bannie !": $tmp="Die IP-Adresse ist verboten!"; break;
      case "Adresse IP signalée par l’antispam !": $tmp="Die IP-Adresse wurde vom Antispam gemeldet!"; break;
      case "Aide": $tmp="Hilfe"; break;
      case "Anonyme en ligne": $tmp="Anonym online"; break;
      case "Anonyme géoréférencé en ligne": $tmp="Anonym georeferenziert online"; break;
      case "Anonyme": $tmp="Anonym"; break;
      case "Anonyme(s) :": $tmp="Anonymous (n):"; break;
      case "Carte": $tmp="Karte"; break;
      case "Centre carte :": $tmp="Kartenzentrum:"; break;
      case "Centrer": $tmp="Zentrieren"; break;
      case "Champ de table pour latitude": $tmp="DB Tabellenfeld für Breitengrad"; break;
      case "Champ de table pour longitude": $tmp="DB Tabellenfeld für Längengrad"; break;
      case "Chemin des images": $tmp="Pfad der Bilder"; break;
      case "Clef d'API": $tmp="API-Schlüssel"; break;
      case "Cliquer sur la carte pour définir votre géolocalisation.": $tmp="Klicken Sie auf die Karte, um Ihre Geolokation zu definieren."; break;
      case "Cliquer sur la carte pour modifier votre position.": $tmp="Klicken Sie auf die Karte, um Ihren Standort zu ändern."; break;
      case "Cliquez sur la carte pour vous localiser ou modifier votre position.": $tmp="Klicken Sie auf die Karte, um Sie zu finden oder Ihren Standort zu ändern."; break;
      case "Coins carte :": $tmp="Kartenecken:"; break;
      case "Configuration du module Geoloc": $tmp="Geoloc-Modulkonfiguration"; break;
      case "Coordonnées en pixel :": $tmp="Pixelkoordinaten:"; break;
      case "Coordonnées enregistrées :": $tmp="Registrierte Kontaktdaten:"; break;
      case "Côtes et frontières": $tmp="Küsten und Grenzen"; break;
      case "Couches utilitaires": $tmp="Nutzschicht"; break;
      case "Couleur du trait": $tmp="Linienfarbe"; break;
      case "Couleur fond": $tmp="Farbiger Hintergrund"; break;
      case "Définir ou modifier votre position.": $tmp="Ihre Position festlegen oder ändern."; break;
      case "Dernière visite": $tmp="Letzter Besuch:"; break;
      case "Dessin": $tmp="Zeichnung"; break;
      case "Echelle": $tmp="Skala"; break;
      case "En ligne": $tmp="Online"; break;
      case "En visite ici": $tmp="Besuch hier"; break;
      case "Enregistrez": $tmp="Registrieren"; break;
      case "Entrez une adresse": $tmp="Geben eine Adresse"; break;
      case "Envoyez un message interne": $tmp="Senden Sie eine interne Nachricht"; break;
      case "Epaisseur du trait": $tmp="Dicke der Linie"; break;
      case "Filtrer les résultats": $tmp="Die Ergebnisse filtern"; break;
      case "Filtrer les utilisateurs": $tmp="Filter für die Benutzer"; break;
      case "Fonctions": $tmp="Funktionen"; break;
      case "Géocodage a échoué pour la raison suivante": $tmp="Geocodierung ist aus folgendem Grund fehlgeschlagen"; break;
      case "Géocodage": $tmp="Geocoding"; break;
      case "Géocoder": $tmp="Geotag"; break;
      case "Géolocalisation des IP": $tmp="Geolokalisierung IP"; break;
      case "Géolocalisation des membres du site": $tmp="Geolocation von Mitgliedern der Website"; break;
      case "Géolocalisation": $tmp="Geolokalisierung"; break;
      case "Géoréférencés : ": $tmp="Georeferenziert:"; break;
      case "Grille": $tmp="Gitter"; break;
      case "Hauteur de la carte dans le bloc": $tmp="Höhe der Karte im Block"; break;
      case "Hauteur icône des marqueurs": $tmp="Höhen markier ungs symbol"; break;
      case "Hôte": $tmp="Host"; break;
      case "Hybride": $tmp="Hybrid"; break;
      case "Image anonyme géoréférencé en ligne": $tmp="Georeferenziertes anonymous bild on line image"; break;
      case "Image membre géoréférencé en ligne": $tmp="georeferenziertes Mitglied Bild online"; break;
      case "Image membre géoréférencé": $tmp="Georeferenziertes Mitgliedsbild"; break;
      case "Infos carte": $tmp="Karteninfo"; break;
      case "Interface bloc": $tmp="Block-Schnittstelle"; break;
      case "Interface carte": $tmp="Kartenschnittstelle"; break;
      case "Ip liste": $tmp="IP-Liste"; break;
      case "IP non géoréférencé en ligne": $tmp="IP nicht georeferenziert online"; break;
      case "Largeur icône des marqueurs": $tmp="Breiten markier ungs symbol"; break;
      case "Latitude :": $tmp="Breite:"; break;
      case "Latitude": $tmp="Breite"; break;
      case "Les adresses IP sont enregistrées.": $tmp="IP-Adressen sind registriert."; break;
      case "Longitude :": $tmp="Länge:"; break;
      case "Longitude": $tmp="Länge"; break;
      case "Marqueur font SVG": $tmp="Marker machen SVG"; break;
      case "Marqueur images de type png, gif, jpeg.": $tmp="Markerbilder vom Typ png, gif, jpeg."; break;
      case "Marqueur SVG font ou objet vectoriel.": $tmp=" SVG Marker Schriftart oder Vektorobjekt."; break;
      case "Masquer": $tmp="verstecken"; break;
      case "Membre en ligne": $tmp="Mitglied Online"; break;
      case "Membre géoréférencé en ligne": $tmp="Online georeferenziertes Mitglied"; break;
      case "Membre géoréférencé": $tmp="Georeferenziertes Mitglied"; break;
      case "Membre": $tmp="Mitglied"; break;
      case "Membre(s) :": $tmp="Mitglied (er):"; break;
      case "Membres du site": $tmp="Mitglieder der Website"; break;
      case "Modification administrateur": $tmp="Administrator Änderung"; break;
      case "Noir et blanc": $tmp="Schwarz und weiß"; break;
      case "Non géoréférencés : ": $tmp="Nicht georeferenziert:"; break;
      case "Non": $tmp="Nein"; break;
      case "Opacité du fond": $tmp="Deckkraft des Bodens"; break;
      case "Opacité du trait": $tmp="Linientrübung"; break;
      case "Opacité": $tmp="Opazität"; break;
      case "Oui": $tmp="Ja"; break;
      case "Paramètres système": $tmp="Systemeinstellungen"; break;
      case "Pays": $tmp="Land"; break;
      case "Plan": $tmp="Karte"; break;
      case "Posts/Commentaires": $tmp="Posts/Kommentare"; break;
      case "Rappel : vous êtes en mode administrateur !": $tmp="Erinnerung: Sie sind im Administratormodus!"; break;
      case "Relief": $tmp="Relief"; break;
      case "requis": $tmp="Erforderlich"; break;
      case "Satellite": $tmp="Satellite"; break;
      case "Sauver": $tmp="Speicher"; break;
      case "Taille de la table": $tmp="Größe des Tabelle"; break;
      case "Type de carte": $tmp="Kartentyp"; break;
      case "Type de marqueur": $tmp="Art des Markers"; break;
      case "Unité des coordonnées": $tmp="Koordinateneinheit"; break;
      case "Vider la table des IP géoréférencées": $tmp="Leeren Sie die georeferenzierte IP-Tabelle."; break;
      case "Ville": $tmp="Stadt"; break;
      case "Visites": $tmp="Besuche"; break;
      case "Visiteur en ligne": $tmp="Online-Besucher"; break;
      case "Visitez le minisite": $tmp="Besuchen Sie die Mini-Website"; break;
      case "Visitez le site": $tmp="Besuchen Sie die Website"; break;
      case "Voir ou masquer anonymes géoréférencés": $tmp="Anonyme georeferenzierte anzeigen oder ausblenden"; break;
      case "Voir ou masquer les IP": $tmp="IP-Adressen anzeigen oder ausblenden"; break;
      case "Voir ou masquer membres géoréférencés en ligne": $tmp="Georeferenzierte Mitglieder online anzeigen oder ausblenden"; break;
      case "Voir ou masquer membres géoréférencés": $tmp="Georeferenzierte Mitglieder anzeigen oder ausblenden"; break;
      case "Voir sur la carte": $tmp="Auf der Karte zeigen"; break;
      case "Voir": $tmp="Ansicht"; break;
      case "Voulez vous changer pour :": $tmp="Möchtest du zu:"; break;
      case "Voulez vous le faire à cette position:": $tmp="Willst du es in dieser Position machen?"; break;
      case "Vous n'êtes pas géoréférencé.": $tmp="Sie sind nicht georeferenziert."; break;
      case "Zoom arrière ici": $tmp="Verkleinern Sie hier"; break;
      case "Zoom arrière": $tmp="Auszoomen"; break;
      case "Zoom avant ici": $tmp="Hier reinzoomen"; break;
      case "Zoom avant": $tmp="Einzoomen"; break;
      case "Zoom": $tmp="Zoom"; break;
      default: $tmp = "Es ist notwendig zu übersetzen [** $phrase **]"; break;
   }
   return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
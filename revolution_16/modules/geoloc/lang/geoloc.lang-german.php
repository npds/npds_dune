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
/* geoloc.lang-german.php file 2008-2019                                */
/* dev team : Philippe Revilliod (phr)                                  */
/************************************************************************/

function geoloc_translate($phrase) {
   switch($phrase) {
      case "Latitude": $tmp="Breite"; break;
      case "Longitude": $tmp="Länge"; break;
      case "Latitude :": $tmp="Breite:"; break;
      case "Longitude :": $tmp="Länge:"; break;
      case "Enregistrez": $tmp="Registrieren"; break;
      case "Membre(s) :": $tmp="Mitglied (er):"; break;
      case "Anonyme(s) :": $tmp="Anonymous (n):"; break;
      case "Anonyme": $tmp="anonym"; break;
      case "Voir": $tmp="Ansicht"; break;
      case "Masquer": $tmp="verstecken"; break;
      case "Visiteur en ligne": $tmp="Online-Besucher"; break;
      case "En ligne": $tmp="Online"; break;
      case "Géoréférencés : ": $tmp="Georeferenziert:"; break;
      case "Non géoréférencés : ": $tmp="Nicht georeferenziert:"; break;
      case "Anonyme géoréférencé en ligne": $tmp="Anonym georeferenziert online"; break;
      case "Membre géoréférencé en ligne": $tmp="Online georeferenziertes Mitglied"; break;
      case "Membre géoréférencé": $tmp="Georeferenziertes Mitglied"; break;
      case "IP non géoréférencé en ligne": $tmp="IP nicht georeferenziert online"; break;
      case "Vous n'êtes pas géoréférencé.": $tmp="Sie sind nicht georeferenziert."; break;
      case "Voulez vous le faire à cette position :": $tmp="Willst du es in dieser Position machen?"; break;
      case "Cliquer sur la carte pour définir votre géolocalisation.": $tmp="Klicken Sie auf die Karte, um Ihre Geolokation zu definieren."; break;
      case "Cliquer sur la carte pour modifier votre position.": $tmp="Klicken Sie auf die Karte, um Ihren Standort zu ändern."; break;
      case "Coordonnées enregistrées :": $tmp="Registrierte Kontaktdaten:"; break;
      case "Voulez vous changer pour :": $tmp="Möchtest du zu:"; break;
      case "Cliquez sur la carte pour vous localiser ou modifier votre position.": $tmp="Klicken Sie auf die Karte, um Sie zu finden oder Ihren Standort zu ändern."; break;
      case "Voir ou masquer membres géoréférencés": $tmp="Georeferenzierte Mitglieder anzeigen oder ausblenden"; break;
      case "Voir ou masquer anonymes géoréférencés": $tmp="Anonyme georeferenzierte anzeigen oder ausblenden"; break;
      case "Voir ou masquer membres géoréférencés en ligne": $tmp="Georeferenzierte Mitglieder online anzeigen oder ausblenden"; break;
      case "Désolé les API de Google Maps ne sont pas compatibles avec votre navigateur": $tmp="Sorry Maps APIs sind nicht mit Ihrem Browser kompatibel"; break;
      case "En visite ici": $tmp="Besuch hier"; break;
      case "Visites": $tmp="Besuche"; break;
      case "Dernière visite : ": $tmp="Letzter Besuch:"; break;
      case "Pays": $tmp="Land"; break;
      case "Ville": $tmp="City"; break;
      case "Les adresses IP sont enregistrées.": $tmp="IP-Adressen sind registriert."; break;
      case "Chargement en cours...Ou serveurs Google HS...Ou erreur...": $tmp="Loading ... Oder Google-Server HS ... Oder Fehler ..."; break;
      case "Hôte": $tmp="Host"; break;
      case "Zoom :": $tmp="Zoom:"; break;
      case "Zoom avant": $tmp="Einzoomen"; break;
      case "Zoom arrière": $tmp="Auszoomen"; break;
      case "Zoom avant ici": $tmp="Hier reinzoomen"; break;
      case "Zoom arrière ici": $tmp="Verkleinern Sie hier"; break;
      case "Centrer": $tmp="Zentrieren"; break;
      case "Type de carte": $tmp="Kartentyp"; break;
      case "Satellite": $tmp="Satellite"; break;
      case "Plan": $tmp="Karte"; break;
      case "Hybride": $tmp="Hybrid"; break;
      case "Carte": $tmp="Karte"; break;
      case "Admin": $tmp="Admin"; break;
      case "Centre carte :": $tmp="Kartenzentrum:"; break;
      case "Coins carte :": $tmp="Kartenecken:"; break;
      case "Coordonnées en pixel :": $tmp="Pixelkoordinaten:"; break;
      case "Aide": $tmp="Hilfe"; break;
      case "Infos carte": $tmp="Karteninfo"; break;
      case "Modification administrateur": $tmp="Administrator Änderung"; break;
      case "Rappel : vous êtes en mode administrateur !": $tmp="Erinnerung: Sie sind im Administratormodus!"; break;
      case "Configuration du module Geoloc": $tmp="Geoloc-Modulkonfiguration"; break;
      case "Paramètres système": $tmp="Systemeinstellungen"; break;
      case "Clef d'API": $tmp="API-Schlüssel"; break;
      case "Champ de table pour latitude": $tmp="DB Tabellenfeld für Breitengrad"; break;
      case "Champ de table pour longitude": $tmp="DB Tabellenfeld für Längengrad"; break;
      case "requis": $tmp="Required"; break;
      case "Chemin des images": $tmp="Pfad der Bilder"; break;
      case "Unité des coordonnées": $tmp="Koordinateneinheit"; break;
      case "Type de marqueur": $tmp="Art des Markers"; break;
      case "Opacité du fond": $tmp="Deckkraft des Bodens"; break;
      case "Opacité du trait": $tmp="Linientrübung"; break;
      case "Couleur fond": $tmp="Farbiger Hintergrund"; break;
      case "Couleur du trait": $tmp="Linienfarbe"; break;
      case "Epaisseur du trait": $tmp="Dicke der Linie"; break;
      case "Membre": $tmp="Mitglied"; break;
      case "Membre en ligne": $tmp="Mitglied Online"; break;
      case "Anonyme en ligne": $tmp="Anonym online"; break;
      case "Marqueur font SVG": $tmp="Marker machen SVG"; break;
      case "Marqueur SVG font ou objet vectoriel.": $tmp=" SVG Marker Schriftart oder Vektorobjekt."; break;
      case "Marqueur images de type png, gif, jpeg.": $tmp="Markerbilder vom Typ png, gif, jpeg."; break;
      case "Interface carte": $tmp="Kartenschnittstelle"; break;
      case "Interface bloc": $tmp="Block-Schnittstelle"; break;
      case "Largeur icône des marqueurs": $tmp="Breiten markier ungs symbol"; break;
      case "Hauteur icône des marqueurs": $tmp="Höhen markier ungs symbol"; break;
      case "Image membre géoréférencé": $tmp="Georeferenziertes Mitgliedsbild"; break;
      case "Image membre géoréférencé en ligne": $tmp="georeferenziertes Mitglied Bild online"; break;
      case "Image anonyme géoréférencé en ligne": $tmp="Georeferenziertes anonymous bild on line image"; break;
      case "Echelle": $tmp="Skala"; break;
      case "Hauteur de la carte dans le bloc": $tmp="Höhe der Karte im Block"; break;
      case "Oui": $tmp="Ja"; break;
      case "Non": $tmp="Nein"; break;
      case "Géolocalisation": $tmp="Geolokalisierung"; break;
      case "Géolocalisation des IP": $tmp="Geolokalisierung IP"; break;
      case "Sauver": $tmp="Speicher"; break;
      case "Ip liste": $tmp="IP-Liste"; break;
      case "Taille de la table": $tmp="Größe des Tisches"; break;
      case "Vider la table des IP géoréférencées": $tmp="Leeren Sie die georeferenzierte IP-Tabelle."; break;
      case "Geben Sie eine Adresse ein"; break;
      case "Géolocalisation des membres du site": $tmp="Geolocation von Mitgliedern der Website"; break;
      case "Géocodage": $tmp="Geocoding"; break;
      case "Géocodage a échoué pour la raison suivante": $tmp="Geocodierung ist aus folgendem Grund fehlgeschlagen"; break;
      case "Géocoder": $tmp="Geotag"; break;
      case "Voir ou masquer les IP": $tmp="IP-Adressen anzeigen oder ausblenden"; break;

      default: $tmp = "Es ist notwendig zu übersetzen [** $phrase **]"; break;
   }
   return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
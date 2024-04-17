<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/*                                                                      */
/* module npds_twi version 1.0                                          */
/* twi.lang-english.php file 2011 by Jean Pierre Barbary (jpb)          */
/* dev team :                                                           */
/************************************************************************/

function twi_trad($phrase) {
 switch($phrase) {
  case 'A propos de': $tmp='Da wir von'; break;
  case 'Activation de la publication auto des articles': $tmp='Aktivierung der automatischen Veröffentlichung von Artikeln'; break;
  case 'Activation de la publication auto des posts': $tmp='Aktivierung der automatischen Veröffentlichung von posts'; break;
  case 'Admin': $tmp='Admin'; break;
  case 'Configuration du module npds_twi': $tmp='Konfiguration des Moduls npds_twi'; break;
  case 'Enregistrez': $tmp='Speichern'; break;
  case 'Hauteur de la tweet box': $tmp='Höhe der Tweet Box'; break;
  case 'Interface Bloc': $tmp='Block Schnittstelle'; break;
  case 'Largeur de la tweet box': $tmp='Breite der Tweet Box'; break;
  case 'Non': $tmp='Nein'; break;
  case 'Oui': $tmp='Ja'; break;
  case 'requis': $tmp='erforderlich'; break;
  case 'sur twitter': $tmp='bei Twitter'; break;
  case 'Votre clef de consommateur': $tmp='Ihr Schlüssel für den Konsumenten'; break;
  case 'Votre clef secrète de consommateur': $tmp='Ihr geheimer Schlüssel des Konsumenten'; break;
  case "Ici": $tmp="Hier"; break;
  case "Jeton d'accès pour Open Authentification (oauth_token)": $tmp='Zugangstoken für Open Authentifikation (oauth_token)'; break;
  case "Jeton d'accès secret pour Open Authentification (oauth_token_secret)": $tmp='Geheimer Zugangstoken für Open Authentifikation (oauth_token_secret)'; break;
  case "La publication de vos news sur twitter est autorisée. Vous pouvez révoquer cette autorisation": $tmp="Die Veröffentlichung Ihrer Artikel auf Twitter ist erlaubt. Sie können diese Freigabe widerrufen"; break;
  case "La publication de vos news sur twitter n'est pas autorisée vous devez l'activer": $tmp="Veröffentlichung Ihrer Artikel auf Twitter ist nicht erlaubt Sie müssen diese aktivieren"; break;
  case "Méthode pour le raccourciceur d'URL": $tmp='Verfahren zur Verkürzung von URLs'; break;
  case "Réécriture d'url avec contrôleur Npds": $tmp='Url mit Npds-Controller überschreiben'; break;
  case "Réécriture d'url avec ForceType": $tmp='Url mit ForceType überschreiben'; break;
  case "Réécriture d'url avec mod_rewrite": $tmp='Url mit mod_rewrite überschreiben'; break;
  default: $tmp = "Es gibt keine Übersetzung [** $phrase **]"; break;
 }
  return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
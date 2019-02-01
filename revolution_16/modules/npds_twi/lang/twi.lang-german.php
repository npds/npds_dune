<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2019 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/*                                                                      */
/* module npds_twi version 1.0                                          */
/* twi.lang-english.php file 2011 by Jean Pierre Barbary (jpb)          */
/* dev team :                                                           */
/************************************************************************/

function twi_trad($phrase) {
 switch($phrase) {
  case 'Configuration du module npds_twi': $tmp='Module npds_twi settings'; break;
  case 'Activation de la publication auto des articles': $tmp='Activate automated publication for news'; break;
  case 'Activation de la publication auto des posts': $tmp='Activate automated publication for posts'; break;
  case 'requis': $tmp='required'; break;
  case 'Largeur de la tweet box': $tmp='Tweet box width'; break;
  case 'Hauteur de la tweet box': $tmp='Tweet box height:'; break;
  case 'Enregistrez': $tmp='Save'; break;
  case 'Admin': $tmp='Admin'; break;
  case 'Interface Bloc': $tmp='Bloc settings'; break;
  case "Réécriture d'url avec mod_rewrite": $tmp='Url rewriting with mod_rewrite'; break;
  case "Réécriture d'url avec ForceType": $tmp='Url rewriting with ForceType'; break;
  case "Réécriture d'url avec contrôleur Npds": $tmp='Url rewriting with Npds controleur'; break;
  case "Méthode pour le raccourciceur d'URL": $tmp='Used methode for the short URL engine'; break;
  case 'Votre clef de consommateur': $tmp='Consumer key'; break;
  case 'Votre clef secrète de consommateur': $tmp='Consumer secret key'; break;
  case "Jeton d'accès pour Open Authentification (oauth_token)": $tmp='Access token for Open Authentication (oauth_token)'; break;
  case "Jeton d'accès secret pour Open Authentification (oauth_token_secret)": $tmp='Access token  secret for Open Authentication (oauth_token)'; break;
  case 'Oui': $tmp='Yes'; break;
  case 'Non': $tmp='No'; break;
  case 'A propos de': $tmp='About'; break;
  case 'sur twitter': $tmp='on twitter'; break;

  default: $tmp = "Need to be translated [** $phrase **]"; break;
 }
  return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
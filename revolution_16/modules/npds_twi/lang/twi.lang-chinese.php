<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2017 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/*                                                                      */
/* module npds_twi version beta 1.1                                     */
/* twi.lang-chinese.php file 2012 by Jean Pierre Barbary (jpb)          */
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
  case 'R&#xE9;&#xE9;criture d\'url avec mod_rewrite': $tmp='Url rewriting with mod_rewrite'; break;
  case 'R&#xE9;&#xE9;criture d\'url avec ForceType': $tmp='Url rewriting with ForceType'; break;
  case 'R&#xE9;&#xE9;criture d\'url avec contr&#xF4;leur npds': $tmp='Url rewriting with npds controleur'; break;
  case 'M&#xE9;thode pour le raccourciceur d\'URL': $tmp='Used methode for the short URL engine'; break;
  case 'Votre clef de consommateur': $tmp='Consumer key'; break;
  case 'Votre clef secr&#xE8;te de consommateur':& nbsp;$tmp='Consumer secret key'; break;
  case 'Jeton d\'acc&#xE8;s pour Open Authentification (oauth_token)': $tmp='Access token for Open Authentication (oauth_token)'; break;
  case 'Jeton d\'acc&#xE8;s secret pour Open  Authentification (oauth_token_secret)': $tmp='Access token  secret for Open Authentication (oauth_token)'; break;
  case 'Oui': $tmp='Yes'; break;
  case 'Non': $tmp='No'; break;
  case 'A propos de': $tmp='About'; break;
  case 'sur twitter': $tmp='on twitter'; break;

  default: $tmp = 'Need to be translated <strong>[** $phrase **]</strong>'; break;
 }
 if (cur_charset=='utf-8') {
    return utf8_encode($tmp);
 } else {
    return ($tmp);
 }
}
?>
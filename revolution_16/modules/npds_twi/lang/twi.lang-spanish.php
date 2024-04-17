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
  case 'A propos de': $tmp='Hablando de'; break;
  case 'Activation de la publication auto des articles': $tmp='Activación de la publicación automática de los artículos'; break;
  case 'Activation de la publication auto des posts': $tmp='Activación de la publicación automática de los posts'; break;
  case 'Admin': $tmp='Administración'; break;
  case 'Configuration du module npds_twi': $tmp='Configuración del módulo npds_twi'; break;
  case 'Enregistrez': $tmp='Guarde'; break;
  case 'Hauteur de la tweet box': $tmp='Altura del tweet box'; break;
  case 'Interface Bloc': $tmp='Interfaz Bloque'; break;
  case 'Largeur de la tweet box': $tmp='Anchura del tweet box'; break;
  case 'Non': $tmp='No'; break;
  case 'Oui': $tmp='Sí'; break;
  case 'requis': $tmp='requerido'; break;
  case 'sur twitter': $tmp='en Twitter'; break;
  case 'Votre clef de consommateur': $tmp='Su llave del consumidor'; break;
  case 'Votre clef secrète de consommateur': $tmp='Su llave secreta del consumidor'; break;
  case "Ici": $tmp="Aquí"; break;
  case "Jeton d'accès pour Open Authentification (oauth_token)": $tmp='Token de acceso secreto para Open Authentification (oauth_token)'; break;
  case "Jeton d'accès secret pour Open Authentification (oauth_token_secret)": $tmp='Token de acceso secreto para Open Authentification (oauth_token_secret)'; break;
  case "La publication de vos news sur twitter est autorisée. Vous pouvez révoquer cette autorisation": $tmp="La publicación de tus artículos en twitter está permitida. Puedes revocar esta autorización"; break;
  case "La publication de vos news sur twitter n'est pas autorisée vous devez l'activer": $tmp="La publicación de tus artículos en twitter no está permitida debes activarla"; break;
  case "Méthode pour le raccourciceur d'URL": $tmp='Método para el atajo de URL'; break;
  case "Réécriture d'url avec contrôleur Npds": $tmp='Reescritura de url con controlador Npds'; break;
  case "Réécriture d'url avec ForceType": $tmp='Reescritura de url con ForceType'; break;
  case "Réécriture d'url avec mod_rewrite": $tmp='Reescritura de url con mod_rewrite'; break;
  default: $tmp = "Necesita una traducción [** $phrase **]"; break;
 }
  return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
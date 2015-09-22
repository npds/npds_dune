<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2010 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/*                                                                      */
/* Module video_yt                                                      */
/* video_yt.lang-english Language file 2007 by jpb                      */
/* Translated by : Jean Pierre Barbary                                  */
/* version 2.0 12/09                                                    */
/************************************************************************/

function video_yt_translate($phrase) {
 switch($phrase) {
  
case 'Vid&#xE9;os': $tmp='Videos'; break;
case 'Vid&#xE9;oth&#xE8;que de': $tmp='Videos'; break;
case 'Auteur :': $tmp='Author:'; break;
case 'Vu :': $tmp='Views:'; break;
case 'Titre :': $tmp='Title:'; break;
case 'Cat&#xE9;gorie :': $tmp='Category:'; break;

case 'Commentaire(s) :': $tmp='Comment(s):'; break;
case 'Dur&#xE9;e :': $tmp='Length :'; break;
case 'Votes :': $tmp='Ratings :'; break;
case 'Moyenne des votes :': $tmp='Ratings average:'; break;
case 'Description :': $tmp='Description :'; break;
case 'Mots-clefs :': $tmp='Tags:'; break;
case 'R&#xE9;ponse incorrecte du fichier xml de Youtube.': $tmp='The xml file answer send by youtube seems to be false...:'; break;
case 'Ajout&#xE9;e le :': $tmp='Added:'; break;
case 'Localisation :': $tmp='Location:'; break;
case 'D&#xE9;tail :': $tmp='Detail:'; break;
case 'Voir': $tmp='Watch'; break;
case 'Plus de d&#xE9;tail sur cette vid&#xE9;o': $tmp='More details about this video'; break;
case 'vues': $tmp='views'; break;
case 'Cherche vid&#xE9;os avec ce mot clef': $tmp='Look for videos with this tag'; break;
case 'Voir les commentaires de cette vid&#xE9;o': $tmp='Show the video comments'; break;
case 'Masquer les commentaires de cette vid&#xE9;o': $tmp='Hide the video comments'; break;
case 'Voir le panneau des outils': $tmp='Show the tools pannel'; break;
case 'Masquer le panneau des outils': $tmp='Hide the tools pannel'; break;
case 'Voir le panneau de visualisation': $tmp='Show the viewer'; break;
case 'Masquer le panneau de visualisation': $tmp='Hide the viewer'; break;
case 'Voir le panneau de navigation': $tmp='Show the brownser'; break;
case 'Masquer le panneau de navigation': $tmp='Hide the brownser'; break;

case 'Cr&#xE9;&#xE9;e le : ': $tmp='Created:'; break;
case 'Modifi&#xE9;e le :': $tmp='Modified:'; break;
case 'Abonn&#xE9;(s) : ': $tmp='Subscriber(s)'; break;
case 'Nombre de vid&#xE9;os : ': $tmp='Number of videos:'; break;
case 'Ma chaine video': $tmp='My Channel'; break;

case 'Cr&#xE9;&#xE9;e le': $tmp='created'; break;
case 'modifi&#xE9; le': $tmp='modified:'; break;
case 'abonn&#xE9;(s)': $tmp='subscriber(s)'; break;
case 'vue': $tmp='Views:'; break;
case 'vid&#xE9;os': $tmp='videos'; break;
case '&#xE9;crit': $tmp='wrote'; break;

case 'Premi&#xE8;res': $tmp='First'; break;
case 'Pr&#xE9;c&#xE9;dentes': $tmp='Previous page'; break;
case '&#xE0;': $tmp='to'; break;
case 'Fiches': $tmp='Records'; break;
case 'total': $tmp='total'; break;
case 'Suivantes': $tmp='Next page'; break;
case 'Derni&#xE8;res': $tmp='Last'; break;
case 'de': $tmp='from'; break;

case 'Pays :': $tmp='Country:'; break;
case 'Localisation :': $tmp='Location:'; break;
case 'Date :': $tmp='Date:'; break;

case 'Configuration du module vid&#xE9;o': $tmp='Module video configuration'; break;
case 'requis': $tmp='required'; break;
case 'Votre ID developpeur youtube': $tmp='Your Youtube developer ID'; break;
case 'Votre clef developpeur youtube': $tmp='Your Youtube developer key'; break;
case 'Votre username youtube': $tmp='Your Youtube username'; break;
case 'Username alternatif': $tmp='Alternatif username'; break;
case 'Couleur de fond zone recherche': $tmp='Background color for search panel'; break;
case 'Nombre de vid&#xE9;o dans planche': $tmp='Number of video in the'; break;

case 'Largeur de la vid&#xE9;o': $tmp='Video width:'; break;
case 'Hauteur de la vid&#xE9;o': $tmp='Video height:'; break;
case 'Largeur de la vid&#xE9;o dans le bloc': $tmp='Width of the video in block:'; break;
case 'Hauteur de la vid&#xE9;o dans le bloc': $tmp='Height of the video in block:'; break;
case 'Nombre de vid&#xE9;o par page': $tmp='Number of video per page'; break;
case 'Classe de style titre': $tmp='Class of style title'; break;
case 'Classe de style sous-titre': $tmp='Class of style sub_title:'; break;
case 'Classe de style commentaire': $tmp='Class of style comment'; break;
case 'Sauver': $tmp='Save'; break;

case 'Latitude :': $tmp='Latitude:'; break;
case 'Longitude :': $tmp='Longitude:'; break;
case 'Voir le profil @ YouTube.com': $tmp='Show user profil @ YouTube.com'; break;
case 'R&eacute;sultat de recherche pour :': $tmp='Search results for:'; break;

   default: $tmp = 'Need to be translated <b>[** '.$phrase.' **]</b>'; break;
 }
 if (cur_charset=='utf-8') {
    return utf8_encode($tmp);
 } else {
    return ($tmp);
 }
}
?>
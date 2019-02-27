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
/* geoloc.lang-chinese.php file 2008-2019 by Jean Pierre Barbary (jpb)  */
/* dev team : Philippe Revilliod (phr)                                  */
/************************************************************************/

function geoloc_translate($phrase) {
   switch($phrase) {
      case "Latitude": $tmp="经度"; break;
      case "Longitude": $tmp="纬度"; break;
      case "Latitude :": $tmp="经度:"; break;
      case "Longitude :": $tmp="纬度:"; break;
      case "Enregistrez": $tmp="保存"; break;
      case "Membre(s) :": $tmp="位用户:"; break;
      case "Anonyme(s) :": $tmp="匿名游客:"; break;
      case "Anonyme": $tmp="匿名游客"; break;
      case "Voir": $tmp="显示"; break;
      case "Masquer": $tmp="隐藏"; break;
      case "Visiteur en ligne": $tmp="在线访客"; break;
      case "En ligne : ": $tmp="在线: "; break;
      case "Géoréférencés : ": $tmp="Georeferenced:"; break;
      case "Non géoréférencés : ": $tmp="Ungeoreferenced:"; break;
      case "Anonyme géoréférencé en ligne": $tmp="Georeferenced anonymous on line"; break;
      case "Membre géoréférencé en ligne": $tmp="Georeferenced member on line"; break;
      case "Membre géoréférencé": $tmp="网站成员地理坐标定位"; break;
      case "IP non géoréférencé en ligne": $tmp="Ungeoreferenced IP on line"; break;
      case "Vous n'êtes pas géoréférencé.": $tmp="你没有地理定位。"; break;
      case "Voulez vous le faire à cette position :": $tmp="Would you get one at this position:"; break;
      case "Cliquer sur la carte pour définir votre géolocalisation.": $tmp="点击地图以定义您的地理位置。"; break;
      case "Cliquer sur la carte pour modifier votre position.": $tmp="Click the map to change your geolocation."; break;
      case "Coordonnées enregistrées :": $tmp="地理坐标已经注册"; break;
      case "Voulez vous changer pour :": $tmp="Would you change to:"; break;
      case "Cliquez sur la carte pour vous localiser ou modifier votre position.": $tmp="Click on the map to or change your location."; break;
      case "Voir ou masquer membres géoréférencés": $tmp="Show or hide georeferenced member"; break;
      case "Voir ou masquer anonymes géoréférencés": $tmp="Show or hide anonymous on line"; break;
      case "Voir ou masquer membres géoréférencés en ligne": $tmp="Show or hide georeferenced member on line"; break;
      case "Désolé les API de Google Maps ne sont pas compatibles avec votre navigateur": $tmp="Sorry, the Google Maps API is not compatible with this browser"; break;
      case "En visite ici : ": $tmp="Visiting this page:"; break;
      case "Visites : ": $tmp="Hits:"; break;
      case "Dernière visite": $tmp="上次访问"; break;
      case "Pays :": $tmp="国家:"; break;
      case "Ville": $tmp="城市"; break;
      case "Les adresses IP sont enregistrées.": $tmp="您的IP地址已被登记"; break;
      case "Chargement en cours...Ou serveurs Google HS...Ou erreur...": $tmp="现在载入中...或Google服务器正在关闭...或错误..."; break;
      case "Hôte : ": $tmp="Host:"; break;
      case "Zoom :": $tmp="Zoom:"; break;
      case "Zoom avant": $tmp="放大地图"; break;
      case "Zoom arrière": $tmp="Zoom out"; break;
      case "Zoom avant ici": $tmp="Zoom in here"; break;
      case "Zoom arrière ici": $tmp="Zoom out here"; break;
      case "Centrer": $tmp="Centre map here"; break;
      case "Type de carte": $tmp="地图类型"; break;
      case "Satellite": $tmp="卫星"; break;
      case "Plan": $tmp="Plan"; break;
      case "Hybride": $tmp="Mixte"; break;
      case "Carte": $tmp="地图"; break;
      case "Admin": $tmp="管理员"; break;
      case "Centre carte :": $tmp="地图中心:"; break;
      case "Coins carte :": $tmp="地图的角落:"; break;
      case "Coordonnées en pixel :": $tmp="Pixels coordinates:"; break;
      case "Tiles :": $tmp="Tiles:"; break;
      case "Wrt tile :": $tmp="Wrt tile:"; break;
      case "Coord tile :": $tmp="Coord tile :"; break;
      case "Aide": $tmp="帮助"; break;
      case "Infos carte": $tmp="地图信息"; break;
      case "Modification administrateur": $tmp="Admin mode"; break;
      case "Rappel : vous êtes en mode administrateur !": $tmp="提醒：你是在管理模式！"; break;
      case "Configuration du module Geoloc": $tmp="Geoloc addon settings"; break;
      case "Paramètres système": $tmp="System parameters"; break;
      case "Clef d'API": $tmp="API密钥"; break;
      case "Champ de table pour latitude": $tmp="DB field for latitude"; break;
      case "Champ de table pour longitude": $tmp="DB field for longitude"; break;
      case "requis": $tmp="需要"; break;
      case "Chemin des images": $tmp="Images path"; break;
      case "Unité des coordonnées": $tmp="Coordinates unit"; break;
      case "Type de marqueur": $tmp="Marker type"; break;
      case "Opacité du fond": $tmp="背景不透明度"; break;
      case "Opacité du trait": $tmp="该行的不透明度"; break;
      case "Couleur fond": $tmp="背景颜色"; break;
      case "Couleur du trait": $tmp="线条颜色"; break;
      case "Epaisseur du trait": $tmp="线粗细"; break;
      case "Membre": $tmp="位用户"; break;
      case "Membre en ligne": $tmp="Member on line"; break;
      case "Anonyme en ligne": $tmp="Anonymous on line"; break;
      case "Marqueur font SVG": $tmp="Marker SVG font"; break;
      case "Interface carte": $tmp="界面的地图"; break;
      case "Interface bloc": $tmp="Map bloc UI"; break;
      case "Largeur icône marqueur": $tmp="Marker icon width"; break;
      case "Hauteur icône marqueur": $tmp="Marker icon height"; break;
      case "Image membre géoréférencé": $tmp="Georeferenced member image"; break;
      case "Image membre géoréférencé en ligne": $tmp="Georeferenced member on line image"; break;
      case "Image anonyme géoréférencé en ligne": $tmp="Georeferenced anonymous on line image"; break;
      case "Echelle": $tmp="地图尺度"; break;
      case "Hauteur de la carte dans le bloc": $tmp="在块中的地图的高度"; break;
      case "Oui": $tmp="是"; break;
      case "Non": $tmp="不"; break;
      case "Géolocalisation": $tmp="地理位置"; break;
      case "Géolocalisation des IP": $tmp="IP地理位置"; break;
      case "Sauver": $tmp="保存"; break;
      case "Ip liste": $tmp="IP列表"; break;
      case "Taille de la table": $tmp="Size of the database table"; break;
      case "Vider la table des IP géoréférencées": $tmp="Truncate the table of georeferenced IP."; break;
      case "Entrez une adresse": $tmp="输入地址"; break;
      case "Géolocalisation des membres du site": $tmp="Site members geolocation"; break;
      case "Géocodage": $tmp="地理编码"; break;
      case "Géocodage a échoué pour la raison suivante": $tmp="Geocode was not successful for the following reason"; break;
      case "Géocoder": $tmp="地理编码"; break;
      case "Voir ou masquer les IP": $tmp="显示或隐藏IP地址"; break;

      default: $tmp = "需要翻译稿 [** $phrase **]"; break;
   }
   return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
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
/* geoloc.lang-chinese.php file 2008-2021 by Jean Pierre Barbary (jpb)  */
/* dev team : Philippe Revilliod (Phr), A.NICOL                         */
/************************************************************************/

function geoloc_translate($phrase) {
   switch($phrase) {
      case "Admin": $tmp="管理员"; break;
      case "Aide": $tmp="帮助"; break;
      case "Adresse IP bannie !": $tmp="IP地址被禁止!"; break;
      case "Adresse IP signalée par l’antispam !": $tmp="Antispam报告的IP地址!"; break;
      case "Anonyme en ligne": $tmp="Anonymous on line"; break;
      case "Anonyme géoréférencé en ligne": $tmp="Georeferenced anonymous on line"; break;
      case "Anonyme": $tmp="匿名游客"; break;
      case "Anonyme(s) :": $tmp="匿名游客:"; break;
      case "Carte": $tmp="地图"; break;
      case "Centre carte :": $tmp="地图中心:"; break;
      case "Centrer": $tmp="中央地图"; break;
      case "Champ de table pour latitude": $tmp="DB field for latitude"; break;
      case "Champ de table pour longitude": $tmp="DB field for longitude"; break;
      case "Chemin des images": $tmp="图像路径"; break;
      case "Clef d'API": $tmp="API密钥"; break;
      case "Cliquer sur la carte pour définir votre géolocalisation.": $tmp="点击地图以定义您的地理位置。"; break;
      case "Cliquer sur la carte pour modifier votre position.": $tmp="单击地图以更改您的位置。"; break;
      case "Cliquez sur la carte pour vous localiser ou modifier votre position.": $tmp="单击地图以定位您或更改您的位置。"; break;
      case "Coins carte :": $tmp="地图的角落:"; break;
      case "Configuration du module Geoloc": $tmp="配置Geoloc模块"; break;
      case "Coordonnées en pixel :": $tmp="Pixels coordinates:"; break;
      case "Coordonnées enregistrées :": $tmp="地理坐标已经注册"; break;
      case "Côtes et frontières": $tmp="海岸和边界"; break;
      case "Couches utilitaires": $tmp="Utility layers"; break;
      case "Couleur du trait": $tmp="线条颜色"; break;
      case "Couleur fond": $tmp="背景颜色"; break;
      case "Définir ou modifier votre position.": $tmp="设置或更改您的位置。"; break;
      case "Dernière visite": $tmp="上次访问"; break;
      case "Dessin": $tmp="绘图"; break;
      case "Echelle": $tmp="地图尺度"; break;
      case "En ligne : ": $tmp="在线: "; break;
      case "En visite ici : ": $tmp="访问此网页:"; break;
      case "Enregistrez": $tmp="保存"; break;
      case "Entrez une adresse": $tmp="输入地址"; break;
      case "Envoyez un message interne": $tmp="发送内部消息"; break;
      case "Epaisseur du trait": $tmp="线粗细"; break;
      case "Filtrer les résultats": $tmp="筛选结果"; break;
      case "Filtrer les utilisateurs": $tmp="筛选用户"; break;
      case "Fonctions": $tmp="职能"; break;
      case "Géocodage a échoué pour la raison suivante": $tmp="Geocode was not successful for the following reason"; break;
      case "Géocodage": $tmp="地理编码"; break;
      case "Géocoder": $tmp="地理编码"; break;
      case "Géolocalisation des IP": $tmp="IP地理位置"; break;
      case "Géolocalisation des membres du site": $tmp="网站成员的地理位置"; break;
      case "Géolocalisation": $tmp="地理位置"; break;
      case "Géoréférencés : ": $tmp="地理参考:"; break;
      case "Grille": $tmp="网格"; break;
      case "Hauteur de la carte dans le bloc": $tmp="在块中的地图的高度"; break;
      case "Hauteur icône marqueur": $tmp="Marker icon height"; break;
      case "Hôte : ": $tmp="Host:"; break;
      case "Hybride": $tmp="Mixte"; break;
      case "Image anonyme géoréférencé en ligne": $tmp="Georeferenced anonymous on line image"; break;
      case "Image membre géoréférencé en ligne": $tmp="Georeferenced member on line image"; break;
      case "Image membre géoréférencé": $tmp="Georeferenced member image"; break;
      case "Infos carte": $tmp="地图信息"; break;
      case "Interface bloc": $tmp="Map bloc UI"; break;
      case "Interface carte": $tmp="界面的地图"; break;
      case "Ip liste": $tmp="IP列表"; break;
      case "IP non géoréférencé en ligne": $tmp="Ungeoreferenced IP on line"; break;
      case "Largeur icône marqueur": $tmp="Marker icon width"; break;
      case "Latitude :": $tmp="经度:"; break;
      case "Latitude": $tmp="经度"; break;
      case "Les adresses IP sont enregistrées.": $tmp="您的IP地址已被登记"; break;
      case "Longitude :": $tmp="纬度:"; break;
      case "Longitude": $tmp="纬度"; break;
      case "Marqueur font SVG": $tmp="Marker SVG font"; break;
      case "Marqueur images de type png, gif, jpeg.": $tmp="Marker image png, gif, jpeg."; break;
      case "Marqueur SVG font ou objet vectoriel.": $tmp=" Marker SVG font or vector object."; break;
      case "Masquer": $tmp="隐藏"; break;
      case "Membre en ligne": $tmp="网站的在线成员"; break;
      case "Membre géoréférencé en ligne": $tmp="Georeferenced member on line"; break;
      case "Membre géoréférencé": $tmp="网站成员地理坐标定位"; break;
      case "Membre": $tmp="位用户"; break;
      case "Membre(s) :": $tmp="位用户:"; break;
      case "Membres du site": $tmp="网站成员"; break;
      case "Modification administrateur": $tmp="Admin mode"; break;
      case "Noir et blanc": $tmp="黑白"; break;
      case "Non géoréférencés : ": $tmp="没有地理参考资料:"; break;
      case "Non": $tmp="不"; break;
      case "Opacité du fond": $tmp="背景不透明度"; break;
      case "Opacité du trait": $tmp="该行的不透明度"; break;
      case "Opacité": $tmp="不透明"; break;
      case "Oui": $tmp="是"; break;
      case "Paramètres système": $tmp="系统设置"; break;
      case "Pays :": $tmp="国家:"; break;
      case "Plan": $tmp="Plan"; break;
      case "Posts/Commentaires": $tmp="Posts/Comments"; break;
      case "Rappel : vous êtes en mode administrateur !": $tmp="提醒：你是在管理模式！"; break;
      case "Relief": $tmp="景观"; break;
      case "requis": $tmp="需要"; break;
      case "Satellite": $tmp="卫星"; break;
      case "Sauver": $tmp="保存"; break;
      case "Taille de la table": $tmp="数据库表的大小"; break;
      case "Type de carte": $tmp="地图类型"; break;
      case "Type de marqueur": $tmp="Marker type"; break;
      case "Unité des coordonnées": $tmp="Coordinates unit"; break;
      case "Vider la table des IP géoréférencées": $tmp="Truncate the table of georeferenced IP."; break;
      case "Ville": $tmp="城市"; break;
      case "Visites : ": $tmp="Hits:"; break;
      case "Visiteur en ligne": $tmp="在线访客"; break;
      case "Visitez le minisite": $tmp="访问小型网站"; break;
      case "Visitez le site": $tmp="请访问网站"; break;
      case "Voir ou masquer anonymes géoréférencés": $tmp="Show or hide anonymous on line"; break;
      case "Voir ou masquer les IP": $tmp="显示或隐藏IP地址"; break;
      case "Voir ou masquer membres géoréférencés en ligne": $tmp="Show or hide georeferenced member on line"; break;
      case "Voir ou masquer membres géoréférencés": $tmp="Show or hide georeferenced member"; break;
      case "Voir sur la carte": $tmp="在地图上显示"; break;
      case "Voir": $tmp="显示"; break;
      case "Voulez vous changer pour :": $tmp="Would you change to:"; break;
      case "Voulez vous le faire à cette position :": $tmp="Would you get one at this position:"; break;
      case "Vous n'êtes pas géoréférencé.": $tmp="你没有地理定位。"; break;
      case "Zoom arrière ici": $tmp="Zoom out here"; break;
      case "Zoom arrière": $tmp="Zoom out"; break;
      case "Zoom avant ici": $tmp="Zoom in here"; break;
      case "Zoom avant": $tmp="放大地图"; break;
      case "Zoom": $tmp="Zoom"; break;
      default: $tmp = "需要翻译稿 [** $phrase **]"; break;
   }
   return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
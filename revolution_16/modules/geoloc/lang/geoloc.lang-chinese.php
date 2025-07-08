<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/*                                                                      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2025 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/*                                                                      */
/* module geoloc version 4.2                                            */
/* geoloc.lang-chinese.php file 2008-2025 by Jean Pierre Barbary (jpb)  */
/* dev team : Philippe Revilliod (Phr), A.NICOL                         */
/************************************************************************/

function geoloc_translate($phrase) {
   switch($phrase) {
      case "Admin": $tmp="管理员"; break;
      case "Aide": $tmp="帮助"; break;
      case "Adresse IP bannie !": $tmp="IP地址被禁止!"; break;
      case "Adresse IP signalée par l’antispam !": $tmp="反垃圾邮件系统报告的IP地址!"; break;
      case "Anonyme en ligne": $tmp="在线匿名用户"; break;
      case "Anonyme géoréférencé en ligne": $tmp="在线地理定位匿名用户"; break;
      case "Anonyme": $tmp="匿名游客"; break;
      case "Anonyme(s) :": $tmp="匿名用户:"; break;
      case "Carte": $tmp="地图"; break;
      case "Centre carte :": $tmp="地图中心:"; break;
      case "Centrer": $tmp="居中"; break;
      case "Champ de table pour latitude": $tmp="纬度数据库字段"; break;
      case "Champ de table pour longitude": $tmp="经度数据库字段"; break;
      case "Chemin des images": $tmp="图像路径"; break;
      case "Clef d'API": $tmp="API密钥"; break;
      case "Cliquer sur la carte pour définir votre géolocalisation.": $tmp="点击地图设置您的地理位置。"; break;
      case "Cliquer sur la carte pour modifier votre position.": $tmp="点击地图修改您的位置。"; break;
      case "Cliquez sur la carte pour vous localiser ou modifier votre position.": $tmp="点击地图定位或修改您的位置。"; break;
      case "Coins carte :": $tmp="地图边界:"; break;
      case "Configuration du module Geoloc": $tmp="地理定位模块配置"; break;
      case "Coordonnées en pixel :": $tmp="像素坐标:"; break;
      case "Coordonnées enregistrées :": $tmp="已保存坐标:"; break;
      case "Côtes et frontières": $tmp="海岸线与边界"; break;
      case "Couches utilitaires": $tmp="实用图层"; break;
      case "Couleur du trait": $tmp="线条颜色"; break;
      case "Couleur fond": $tmp="背景颜色"; break;
      case "Définir ou modifier votre position.": $tmp="设置或修改您的位置。"; break;
      case "Dernière visite": $tmp="最后访问"; break;
      case "Dessin": $tmp="手绘地图"; break;
      case "Echelle": $tmp="比例尺"; break;
      case "En ligne : ": $tmp="在线: "; break;
      case "En visite ici : ": $tmp="正在访问此页:"; break;
      case "Enregistrez": $tmp="保存"; break;
      case "Entrez une adresse": $tmp="输入地址"; break;
      case "Envoyez un message interne": $tmp="发送站内消息"; break;
      case "Epaisseur du trait": $tmp="线条粗细"; break;
      case "Filtrer les résultats": $tmp="筛选结果"; break;
      case "Filtrer les utilisateurs": $tmp="筛选用户"; break;
      case "Fonctions": $tmp="功能"; break;
      case "Géocodage a échoué pour la raison suivante": $tmp="地理编码失败，原因如下"; break;
      case "Géocodage": $tmp="地理编码"; break;
      case "Géocoder": $tmp="地理编码"; break;
      case "Géolocalisation des IP": $tmp="IP地理定位"; break;
      case "Géolocalisation des membres du site": $tmp="网站会员地理定位"; break;
      case "Géolocalisation": $tmp="地理定位"; break;
      case "Géoréférencés : ": $tmp="已定位用户:"; break;
      case "Grille": $tmp="网格"; break;
      case "Hauteur de la carte dans le bloc": $tmp="区块中地图高度"; break;
      case "Hauteur icône marqueur": $tmp="标记图标高度"; break;
      case "Hôte : ": $tmp="主机:"; break;
      case "Hybride": $tmp="混合地图"; break;
      case "Image anonyme géoréférencé en ligne": $tmp="在线匿名用户定位图标"; break;
      case "Image membre géoréférencé en ligne": $tmp="在线会员定位图标"; break;
      case "Image membre géoréférencé": $tmp="会员定位图标"; break;
      case "Infos carte": $tmp="地图信息"; break;
      case "Interface bloc": $tmp="区块界面"; break;
      case "Interface carte": $tmp="地图界面"; break;
      case "Ip liste": $tmp="IP列表"; break;
      case "IP non géoréférencé en ligne": $tmp="未定位的在线IP"; break;
      case "Largeur icône marqueur": $tmp="标记图标宽度"; break;
      case "Latitude :": $tmp="纬度:"; break;
      case "Latitude": $tmp="纬度"; break;
      case "Les adresses IP sont enregistrées.": $tmp="IP地址已记录"; break;
      case "Longitude :": $tmp="经度:"; break;
      case "Longitude": $tmp="经度"; break;
      case "Marqueur font SVG": $tmp="SVG字体标记"; break;
      case "Marqueur images de type png, gif, jpeg.": $tmp="PNG/GIF/JPEG格式标记"; break;
      case "Marqueur SVG font ou objet vectoriel.": $tmp="SVG字体或矢量标记"; break;
      case "Masquer": $tmp="隐藏"; break;
      case "Membre en ligne": $tmp="在线会员"; break;
      case "Membre géoréférencé en ligne": $tmp="已定位在线会员"; break;
      case "Membre géoréférencé": $tmp="已定位会员"; break;
      case "Membre": $tmp="会员"; break;
      case "Membre(s) :": $tmp="会员:"; break;
      case "Membres du site": $tmp="网站会员"; break;
      case "Modification administrateur": $tmp="管理员模式"; break;
      case "Noir et blanc": $tmp="黑白地图"; break;
      case "Non géoréférencés : ": $tmp="未定位用户:"; break;
      case "Non": $tmp="否"; break;
      case "Opacité du fond": $tmp="背景透明度"; break;
      case "Opacité du trait": $tmp="线条透明度"; break;
      case "Opacité": $tmp="透明度"; break;
      case "Oui": $tmp="是"; break;
      case "Paramètres système": $tmp="系统设置"; break;
      case "Pays :": $tmp="国家:"; break;
      case "Plan": $tmp="平面地图"; break;
      case "Posts/Commentaires": $tmp="帖子/评论"; break;
      case "Rappel : vous êtes en mode administrateur !": $tmp="提醒：您正处于管理员模式！"; break;
      case "Relief": $tmp="地形图"; break;
      case "requis": $tmp="必填"; break;
      case "Satellite": $tmp="卫星地图"; break;
      case "Sauver": $tmp="保存"; break;
      case "Taille de la table": $tmp="数据表大小"; break;
      case "Type de carte": $tmp="地图类型"; break;
      case "Type de marqueur": $tmp="标记类型"; break;
      case "Unité des coordonnées": $tmp="坐标单位"; break;
      case "Vider la table des IP géoréférencées": $tmp="清空IP定位数据表"; break;
      case "Ville": $tmp="城市"; break;
      case "Visites : ": $tmp="访问量:"; break;
      case "Visiteur en ligne": $tmp="在线访客"; break;
      case "Visitez le minisite": $tmp="访问微站"; break;
      case "Visitez le site": $tmp="访问网站"; break;
      case "Voir ou masquer anonymes géoréférencés": $tmp="显示/隐藏已定位匿名用户"; break;
      case "Voir ou masquer les IP": $tmp="显示/隐藏IP"; break;
      case "Voir ou masquer membres géoréférencés en ligne": $tmp="显示/隐藏已定位在线会员"; break;
      case "Voir ou masquer membres géoréférencés": $tmp="显示/隐藏已定位会员"; break;
      case "Voir sur la carte": $tmp="在地图查看"; break;
      case "Voir": $tmp="显示"; break;
      case "Voulez vous changer pour :": $tmp="是否要更改为:"; break;
      case "Voulez vous le faire à cette position :": $tmp="是否在此位置操作:"; break;
      case "Vous n'êtes pas géoréférencé.": $tmp="您尚未设置地理位置。"; break;
      case "Zoom arrière ici": $tmp="此处缩小"; break;
      case "Zoom arrière": $tmp="缩小"; break;
      case "Zoom avant ici": $tmp="此处放大"; break;
      case "Zoom avant": $tmp="放大"; break;
      case "Zoom": $tmp="缩放"; break;
      default: $tmp = "需要翻译 [** $phrase **]"; break;
   }
   return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,'UTF-8'));
}
?>
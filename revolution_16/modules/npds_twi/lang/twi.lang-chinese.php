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
/* twi.lang-chinese.php file 2011 by Jean Pierre Barbary (jpb)          */
/* dev team :                                                           */
/************************************************************************/

function twi_trad($phrase) {
 switch($phrase) {
  case 'A propos de': $tmp='关于'; break;
  case 'Activation de la publication auto des articles': $tmp='允许自动发布文章'; break;
  case 'Activation de la publication auto des posts': $tmp='启动自动发布帖子'; break;
  case 'Admin': $tmp='行政'; break;
  case 'Configuration du module npds_twi': $tmp='配置npds_twi模块'; break;
  case 'Enregistrez': $tmp='保存'; break;
  case 'Hauteur de la tweet box': $tmp='tweet框的高度'; break;
  case 'Interface Bloc': $tmp='阻止设置'; break;
  case 'Largeur de la tweet box': $tmp='tweet框的宽度'; break;
  case 'Non': $tmp='不'; break;
  case 'Oui': $tmp='对'; break;
  case 'requis': $tmp='所需'; break;
  case 'sur twitter': $tmp='在推特上'; break;
  case 'Votre clef de consommateur': $tmp='消费者密钥'; break;
  case 'Votre clef secrète de consommateur': $tmp='消费者秘密密钥'; break;
  case "Ici": $tmp="在这里"; break;
  case "Jeton d'accès pour Open Authentification (oauth_token)": $tmp='开放身份验证访问令牌（oauth_token）'; break;
  case "Jeton d'accès secret pour Open Authentification (oauth_token_secret)": $tmp='开放身份验证的秘密访问标记（oauth_token_secret）'; break;
  case "La publication de vos news sur twitter est autorisée. Vous pouvez révoquer cette autorisation": $tmp="允许在twitter上发布文章。您可以撤销此权限"; break;
  case "La publication de vos news sur twitter n'est pas autorisée vous devez l'activer": $tmp="不允许在twitter上发布新闻,您必须激活它"; break;
  case "Méthode pour le raccourciceur d'URL": $tmp='用于缩短时间的方法URL'; break;
  case "Réécriture d'url avec contrôleur Npds": $tmp='URL重写 : Npds controleur'; break;
  case "Réécriture d'url avec ForceType": $tmp='URL重写 : ForceType'; break;
  case "Réécriture d'url avec mod_rewrite": $tmp='URL重写 : mod_rewrite'; break;
  default: $tmp = "需要翻译稿 [** $phrase **]"; break;
 }
  return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
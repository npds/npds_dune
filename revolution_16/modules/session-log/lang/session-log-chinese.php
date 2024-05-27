<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Session and log Viewer Copyright (c) 2004 - Tribal-Dolphin           */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

function SessionLog_translate($phrase) {
   switch ($phrase) {
      case "@ IP résolue" : $tmp = "已解析的IP"; break;
      case "@ IP" : $tmp = "@IP"; break;
      case "Agent utilisateur" : $tmp = "用户代理"; break;
      case "Agent" : $tmp = "用户代理"; break;
      case "cette adresse IP a été déconnectée et bannie !" : $tmp = "该IP地址已被断开并禁止！"; break;
      case "Déconnecter et bannir cette adresse IP !" : $tmp = "断开并禁止此IP地址！"; break;
      case "Effacer les fichiers temporaires" : $tmp = "清除临时文件"; break;
      case "Fichier de Log de" : $tmp = "日志档案"; break;
      case "Fournisseur" : $tmp = "互联网服务提供商"; break;
      case "Gestion des Logs" : $tmp = "日志管理"; break;
      case "Informations sur l'IP" : $tmp = "IP地址信息"; break;
      case "Infos" : $tmp = "信息"; break;
      case "Liste des Logs" : $tmp = "日志列表.logs"; break;
      case "Liste des Sessions" : $tmp = "连接会话列表"; break;
      case "Nom" : $tmp = "姓名"; break;
      case "Recevoir le fichier par mail" : $tmp = "通过电子邮件接收文件"; break;
      case "SECURITE" : $tmp = "安全"; break;
      case "TELECHARGEMENT" : $tmp = "下载"; break;
      case "Vider le fichier" : $tmp = "清空文件。"; break;
      case "Vide la table des sessions et interrompt les connexions." : $tmp = "清空会话表并中断连接。"; break;
      default: $tmp = "需要翻译稿 [** $phrase **]"; break;
   }
   return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
<?php
/************************************************************************/
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/*                                                                      */
/* UPLOAD Language File                                                 */
/*                                                                      */
/************************************************************************/

function upload_translate($phrase) {
 switch($phrase) {
   case "Adapter": $tmp="更新"; break;
   case "Affichage intégré": $tmp="集成显示"; break;
   case "Attention": $tmp="警告"; break;
   case "Ce type de fichier n'est pas autorisé": $tmp="此类型的文件未被授权"; break;
   case "Ces Documents sont disponibles sur votre site": $tmp="这些文件可在您的网站上。"; break;
   case "Ces Images et ces Documents sont rattachés à votre compte.": $tmp="您的图片和文档。"; break;
   case "Ces Images sont disponibles sur votre site": $tmp="这些图片可在您的网站上。"; break;
   case "Cette page a déjà été envoyée, veuillez patienter": $tmp="此页面已发送，请稍候"; break;
   case "Erreur de téléchargement du fichier - fichier non sauvegardé.": $tmp="上传文件时出错 - 文件未保存"; break;
   case "Erreur de téléchargement du fichier %s (%s) - Le fichier n'a pas été sauvé": $tmp="上传文件时出错 %s (%s) - 文件未保存"; break;
   case "Etes vous certains de vouloir installer le thème": $tmp="您确定要安装模板"; break;
   case "Extensions autorisées"; $tmp="允许的文件扩展名"; break;
   case "Fichier {NAME} bien reçu ({SIZE} octets transférés)": $tmp="文件 {NAME} 上传 (文件传输 {SIZE} bytes )"; break;
   case "Fichier joint": $tmp="选择要附加的文件："; break;
   case "Fichier non trouvé": $tmp="文件未找到"; break;
   case "Fichier non visible": $tmp="文件不可见"; break;
   case "Fichier": $tmp="文件"; break;
   case "Installer": $tmp="安装"; break;
   case "Joindre le fichier maintenant ?": $tmp="立即发送文件？"; break;
   case "Joindre": $tmp="发送文件"; break;
   case "La taille de ce fichier excède la taille maximum autorisée": $tmp="此文件的大小超过最大文件大小"; break;
   case "Le code erreur est : %s": $tmp="错误代码是： %s"; break;
   case "Modèles": $tmp="模板"; break;
   case "Modifications enregistrées dans": $tmp="更改保存在"; break;
   case "Non": $tmp="否"; break;
   case "Oui": $tmp="是"; break;
   case "Pièces jointes": $tmp="附件"; break;
   case "Prévisualisation :": $tmp="预览:"; break;
   case "Rafraîchir la page": $tmp="重新加载页面"; break;
   case "Session terminée.": $tmp="会议上暂停."; break;
   case "Supprimer les fichiers sélectionnés ?": $tmp="删除所选文件 ?"; break;
   case "Supprimer les fichiers sélectionnés": $tmp="删除所选文件"; break;
   case "Taille": $tmp="大小"; break;
   case "Télécharg.": $tmp="下載次數"; break;
   case "Télécharger un fichier sur le serveur"; $tmp="下载文件的服务器上"; break;
   case "Total :": $tmp="总计：:"; break;
   case "Type": $tmp="类型"; break;
   case "Visibilité": $tmp="能见度"; break;
   case "Vous devez sélectionner un fichier": $tmp="你必须选择一个文件"; break;
   case "Vous devez tout d'abord choisir la Pièce jointe à supprimer": $tmp="您必须选择要删除的附件"; break;

   default: $tmp = "需要翻译稿 [** $phrase **]"; break;
 }
   return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2024 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

function fma_translate($phrase) {
   switch ($phrase) {
      case "Fichier interdit" : $tmp = "文件禁止"; break;
      case "Type de fichier interdit" : $tmp = "文件类型被禁止"; break;
      case "Ok" : $tmp = "Ok"; break;
      case "Renommer" : $tmp = "改名"; break;
      case "Supprimer" : $tmp = "清除"; break;
      case "Chmoder" : $tmp = "Chmod"; break;
      case "Editer" : $tmp = "编辑"; break;
      case "Déplacer" : $tmp = "移动"; break;
      case "Copier" : $tmp = "复制"; break;
      case "Déplacer / Copier" : $tmp = "移动/复制"; break;
      case "Renommer un fichier" : $tmp = "重命名文件"; break;
      case "Déplacer / Copier un fichier" : $tmp = "移动 / 复制文件"; break;
      case "Renommer un répertoire" : $tmp = "重命名一个目录"; break;
      case "Supprimer un fichier" : $tmp = "删除一个文件"; break;
      case "Supprimer un répertoire" : $tmp = "删除一个目录"; break;
      case "Confirmez-vous la suppression de" : $tmp = "你确认删除？"; break;
      case "Changer les droits d'un fichier" : $tmp = "更改文件的权限"; break;
      case "Changer les droits d'un répertoire" : $tmp = "更改目录的权限"; break;
      case "Editer un fichier" : $tmp = "编辑一个文件"; break;
      case "Impossible d'appliquer le chmod" : $tmp = "无法应用chmod"; break;
      case "Impossible de renommer" : $tmp = "无法重命名"; break;
      case "Impossible de déplacer" : $tmp = "无法移动"; break;
      case "Impossible de créer" : $tmp = "无法创建"; break;
      case "Impossible de supprimer" : $tmp = "无法删除"; break;
      case "Impossible de copier" : $tmp = "无法复制"; break;
      case "Le fichier n'existe pas" : $tmp = "文件不存在"; break;
      case "existe déjà" : $tmp = "已经存在"; break;
      case "Rafraîchir" : $tmp = "更新"; break;
      case "Extensions autorisées : " : $tmp = "允许的文件类型:"; break;
      case "Go" : $tmp = "Gb"; break;
      case "Mo" : $tmp = "Mb"; break;
      case "Ko" : $tmp = "Kb"; break;
      case "Copie de " : $tmp = "副本 "; break;
      case "Taille maximum d'un fichier : " : $tmp = "文件的最大大小: "; break;
      case "Images manager" : $tmp = "图片管理员"; break;
      case "Autoriser Pic-Manager" : $tmp = "启用图片管理员"; break;
      case "Taille maximum (pixel) de l'imagette" : $tmp = "缩略图的最大大小（像素）"; break;
      case "Cliquer ici pour charger le fichier dans le player" : $tmp = "点击此处在播放器中加载文件"; break;
      case "Temps de cache (en seconde) des imagettes" : $tmp = "缩略图缓存时间（以秒为单位）"; break;
      case "F-Manager": $tmp="F-Manager"; break;
      case "Gestionnaire de fichiers": $tmp="文件管理器"; break;
      case "Sélectionner votre fichier": $tmp="选择你的文件"; break;

      default: $tmp = "翻译错误 [** $phrase **]"; break;
   }
   return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* reseaux-sociaux : jpb 2016                                           */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

function rs_translate($phrase) {
 switch ($phrase) {
   case "Liste des réseaux sociaux mis à disposition par l'administrateur." : $tmp = "由管理员提供的社交网络的列表。"; break;
   case "Ajouter ou supprimer votre identifiant à ces réseaux sociaux." : $tmp = "添加或删除您的登录这些社交网络。"; break;
   case "Identifiant" : $tmp = "昵称"; break;
   case "Réseaux sociaux" : $tmp = "社交网络"; break;
   case "Ceci créera ou supprimera le lien d'accès dans toutes les pages du portail où ils sont disponibles (forums, articles, commentaires, liste des membres, etc) pour tous les utilisateurs." : $tmp = "This creates or deletes the access link in all the pages of the portal where they are available for all users (forums, articles, comments, list of members, etc)."; break;
   case "Editer" : $tmp = "编辑"; break;
   case "Effacer" : $tmp = "删除"; break;
   case "Fonctions" : $tmp = "功能"; break;
   case "Nom" : $tmp = "姓名"; break;
   case "Icône" : $tmp = "图标"; break;
   case "Sauvegarder" : $tmp = "保存"; break;

   default: $tmp = "需要翻译稿 [** $phrase **]"; break;
 }
  return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
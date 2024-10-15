<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* monmodule : moi 2016                                                 */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

function monmodule_translate($phrase) {
 switch ($phrase) {
   case "Français" : $tmp = "法国"; break;
   case "Anglais" : $tmp = "英语"; break;
   case "Allemand" : $tmp = "德国"; break;
   case "Espagnol" : $tmp = "西班牙语"; break;
   case "Chinois" : $tmp = "中国"; break;

   default: $tmp = "需要翻译稿 [** $phrase **]"; break;
 }
  return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,'UTF-8'));
}
?>
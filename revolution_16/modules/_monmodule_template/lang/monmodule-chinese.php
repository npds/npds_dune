<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* monmodule : moi 2016                                                 */
/*                                                                      */
/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

function monmodule_translate($phrase) {
 switch ($phrase) {
   case "Français" : $tmp = "Liste der Sessions"; break;
   case "Anglais" : $tmp = "Name"; break;
   case "Allemand" : $tmp = "@IP"; break;
   case "Espagnol" : $tmp = "entschlossen @IP"; break;
   case "Chinois" : $tmp = "entschlossen @IP"; break;

   default: $tmp = "需要翻译稿 [** $phrase **]"; break;
 }
  return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
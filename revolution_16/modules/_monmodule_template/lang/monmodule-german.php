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
   case "Français" : $tmp = "Französisch"; break;
   case "Anglais" : $tmp = "English"; break;
   case "Allemand" : $tmp = "Deutsch"; break;
   case "Espagnol" : $tmp = "Spanisch"; break;
   case "Chinois" : $tmp = "Chinesisch"; break;
   
   default: $tmp = "Es gibt keine Übersetzung [** $phrase **]"; break;
 }
  return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,'UTF-8'));
}
?>
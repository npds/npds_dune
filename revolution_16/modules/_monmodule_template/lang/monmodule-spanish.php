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
   case "Français" : $tmp = "Francés"; break;
   case "Anglais" : $tmp = "Inglés"; break;
   case "Allemand" : $tmp = "Alemán"; break;
   case "Espagnol" : $tmp = "Española"; break;
   case "Chinois" : $tmp = "Chino"; break;

   default: $tmp = "Necesita una traducción [** $phrase **]"; break;
 }
  return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,'UTF-8'));
}
?>
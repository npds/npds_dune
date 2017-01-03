<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2017 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

function translate($phrase) {
   $tmp=translate_pass1($phrase);
   include("language/lang-mods.php");

   if (cur_charset=="utf-8") {
      return utf8_encode($tmp);
   } else {
      return ($tmp);
   }
}

function translate_pass1($phrase) {
 settype($englishname,'string');
 switch($phrase) {
   case "$englishname": $tmp = "$englishname"; break;
   case "datestring": $tmp = "%A, %B %d @ %H:%M:%S"; break;
   case "linksdatestring": $tmp = "%Y-%m-%d"; break;
   case "datestring2": $tmp = "%A, %B %d"; break;
   case "dateinternal": $tmp = "Y-m-d H:i"; break;
   case "Chatdate": $tmp = "G:i m-d"; break;

   // NPDS Sable
   case "daydate": $tmp="%A, %B %e %Y"; break; // meta-lang !date!
   // NPDS Sable

   // WS
   case "0": $tmp="zero"; break;
   case "1": $tmp="one"; break;
   case "2": $tmp="twho"; break;
   case "3": $tmp="three"; break;
   case "4": $tmp="four"; break;
   case "5": $tmp="five"; break;
   case "6": $tmp="six"; break;
   case "7": $tmp="seven"; break;
   case "8": $tmp="eight"; break;
   case "9": $tmp="nine"; break;
   case "+": $tmp="plus"; break;
   case "-": $tmp="minus"; break;
   case "/": $tmp="divided by"; break;
   case "*": $tmp="multiplied by"; break;
   // WS
   default: $tmp = "$phrase"; break;
 }
 return $tmp;
}
?>
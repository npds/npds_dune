<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/*                                                                      */
/*                                                                      */
/* Translated by :                                                      */
/*                                                                      */
/************************************************************************/

function translate($phrase) {
   $tmp = translate_pass1($phrase);
   include("language/lang-mods.php");
   return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}

function translate_pass1($phrase) {
 settype($englishname,'string');
 switch($phrase) {
   case "$englishname": $tmp="$englishname"; break;
   case "datestring": $tmp="%A %d %B %Y @ %H:%M:%S"; break;
   case "datestring2": $tmp="%A, %d %B"; break;
   case "linksdatestring": $tmp="%d-%m-%Y"; break;
   case "dateinternal": $tmp="d-m-Y H:i"; break;
   case "Chatdate": $tmp="G:i d-m"; break;
   case "daydate": $tmp="%A %d %B %Y"; break;

   case "Monday": $tmp="Lundi"; break;
   case "Tuesday": $tmp="Mardi"; break;
   case "Wednesday": $tmp="Mercredi"; break;
   case "Thursday": $tmp="Jeudi"; break;
   case "Friday": $tmp="Vendredi"; break;
   case "Saturday": $tmp="Samedi"; break;
   case "Sunday": $tmp="Dimanche"; break;
   case "January": $tmp="Janvier"; break;
   case "February": $tmp="Février"; break;
   case "March": $tmp="Mars"; break;
   case "April": $tmp="Avril"; break;
   case "May": $tmp="Mai"; break;
   case "June": $tmp="Juin"; break;
   case "July": $tmp="Juillet"; break;
   case "August": $tmp="Août"; break;
   case "September": $tmp="Septembre"; break;
   case "October": $tmp="Octobre"; break;
   case "November": $tmp="Novembre"; break;
   case "December": $tmp="Décembre"; break;
   case "english": $tmp="Anglais"; break;
   case "french": $tmp="Français"; break;
   case "spanish": $tmp="Espagnol"; break;
   case "chinese": $tmp="Chinois"; break;
   case "german": $tmp="Allemand"; break;

   case "0": $tmp="zéro"; break;
   case "1": $tmp="un"; break;
   case "2": $tmp="deux"; break;
   case "3": $tmp="trois"; break;
   case "4": $tmp="quatre"; break;
   case "5": $tmp="cinq"; break;
   case "6": $tmp="six"; break;
   case "7": $tmp="sept"; break;
   case "8": $tmp="huit"; break;
   case "9": $tmp="neuf"; break;
   case "+": $tmp="plus"; break;
   case "-": $tmp="moins"; break;
   case "/": $tmp="divisé par"; break;
   case "*": $tmp="multiplié par"; break;

   default: $tmp=$phrase; break;
 }
 return $tmp;
}
?>
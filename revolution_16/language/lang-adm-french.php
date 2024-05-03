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
/************************************************************************/

function adm_translate($phrase) {
   settype($englishname,'string');
   switch($phrase) {
      case "$englishname": $tmp="$englishname"; break;
      case "datestring": $tmp="%A %d %B %Y @ %H:%M:%S %Z"; break;
      case "linksdatestring": $tmp="%d-%b-%Y"; break;
      case "datestring2": $tmp="%A, %d %B"; break;
      case "dateforop": $tmp="d-m-y"; break;
      case "english": $tmp="Anglais"; break;
      case "french": $tmp="Français"; break;
      case "spanish": $tmp="Espagnol"; break;
      case "chinese": $tmp="Chinois"; break;
      case "german": $tmp="Allemand"; break;
      default: $tmp = $phrase; break;
   }
   return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
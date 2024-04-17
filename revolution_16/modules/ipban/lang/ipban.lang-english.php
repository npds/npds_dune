<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

function ipban_translate($phrase) {
   switch ($phrase) {
      case "Administration de l'IpBan": $tmp = "IpBan Administration"; break;
      case "Liste des IP": $tmp = "IP list"; break;
      case "Chaque ligne ne doit contenir qu'une adresse IP (v4 ou v6) de forme : a.b.c.d|X (ex. v4 : 168.192.1.1|5) ; a:b:c:d:e:f:g:h|X (ex. v6 : 2001:0db8:0000:85a3:0000:0000:ac1f:8001|5).": $tmp = "Each line contain only one IP (v4 or v6) Adress like: a.b.c.d|X (ex. v4 : 168.192.1.1|5) ; a:b:c:d:e:f:g:h|X (ex. v6 : 2001:0db8:0000:85a3:0000:0000:ac1f:8001|5)."; break;
      case "Si X >= 5 alors l'accès sera refusé !": $tmp = "If X >= 5 then the access will be denied!"; break;
      case "Ce fichier est mis à jour automatiquement par l'anti-spam de NPDS.": $tmp = "This file is updated by the NPDS anti-spam engine."; break;
      default: $tmp = "Translation error [** $phrase **]"; break;
   }
  return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
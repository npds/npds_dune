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
      case "Administration de l'IpBan": $tmp = "IpBan Verwaltung"; break;
      case "Liste des IP": $tmp = "Liste der IP"; break;
      case "Chaque ligne ne doit contenir qu'une adresse IP (v4 ou v6) de forme : a.b.c.d|X (ex. v4 : 168.192.1.1|5) ; a:b:c:d:e:f:g:h|X (ex. v6 : 2001:0db8:0000:85a3:0000:0000:ac1f:8001|5).": $tmp = "Jede Zeile sollte Dass eine IP-Adresse (v4 oder v6) der Form : a.b.c.d|X (zb v4 : 168.192.1.1|5) ; a:b:c:d:e:f:g:h|X (zb v6 : 2001:0db8:0000:85a3:0000:0000:ac1f:8001|5)."; break;
      case "Si X >= 5 alors l'accès sera refusé !": $tmp = "Wenn X >= 5 dann Zugriff wird verweigert!"; break;
      case "Ce fichier est mis à jour automatiquement par l'anti-spam de NPDS.": $tmp = "Diese Datei wird automatisch durch das Anti-Spam-NPDS aktualisiert."; break;
   default: $tmp = "Es gibt keine Übersetzung [** $phrase **]"; break;
   }
  return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
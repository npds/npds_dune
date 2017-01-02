<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2017 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

function ipban_translate($phrase) {
   switch ($phrase) {
      case "Administration de l'IpBan": $tmp = "La administraci&oacute;n de IpBan"; break;
      case "Liste des IP": $tmp = "Lista de IP"; break;
      case "Chaque ligne ne doit contenir qu'une adresse IP de la forme : a.b.c.d:X (ex : 168.192.1.1:5).": $tmp = "Cada línea debe contener que una IP de la forma : a.b.c.d:X (ex : 168.192.1.1:5)."; break;
      case "Si X >= 5 alors l'accès sera refusé !": $tmp = "Si X >= 5 entonces el acceso será denegado !"; break;
      case "Ce fichier est mis à jour automatiquement par l'anti-spam de NPDS.": $tmp = "Este fichero se actualiza automáticamente por el NPDS anti-spam."; break;
      default: $tmp = "Necesita una traducción [** $phrase **]"; break;
   }
   return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
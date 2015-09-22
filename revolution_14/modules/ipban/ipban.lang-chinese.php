<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2011 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

function ipban_translate($phrase) {
    switch ($phrase) {
       case "Administration de l'IpBan": $tmp = "IpBan Administration"; break;
       case "Liste des IP": $tmp = "IP list"; break;
       case "Chaque ligne ne doit contenir qu'une adresse IP de la forme : a.b.c.d:<b>X</b> (ex : 168.192.1.1:5)<br />si <b>X</b> >= 5 alors l'accès sera refusé<br /><br />Ce fichier est mis à jour automatiquement par l'anti-spam de NPDS.": $tmp = "Each line contain only one IP Adress like: a.b.c.d:<b>X</b> (ex : 168.192.1.1:5)<br />If <b>X</b> >= 5 then te access ... denied<br /><br />This file is updated by the NPDS' anti-spam engine."; break;
       default: $tmp = "Translation error <b>[** $phrase **]</b>"; break;
    }
    return $tmp;
}
?>
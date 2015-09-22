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
       case "Administration de l'IpBan": $tmp = "IpBan Verwaltung"; break;
       case "Liste des IP": $tmp = "Liste der IP"; break;
       case "Chaque ligne ne doit contenir qu'une adresse IP de la forme : a.b.c.d:<b>X</b> (ex : 168.192.1.1:5)<br />si <b>X</b> >= 5 alors l'accès sera refusé<br /><br />Ce fichier est mis à jour automatiquement par l'anti-spam de NPDS.": $tmp = "Jede Zeile sollte Dass eine IP-Adresse der Form : a.b.c.d:<b>X</b> (ex : 168.192.1.1:5)<br />Wenn <b>X</b> >= 5 dann Zugriff wird verweigert<br /><br />Diese Datei wird automatisch durch das Anti-Spam-NPDS aktualisiert."; break;
       default: $tmp = "Es gibt keine Übersetzung <b>[** $phrase **]</b>"; break;
    }
    return $tmp;
}
?>
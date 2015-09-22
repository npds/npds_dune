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
       case "Administration de l'IpBan": $tmp = "La administraci&oacute;n de IpBan"; break;
       case "Liste des IP": $tmp = "Lista de IP"; break;
       case "Chaque ligne ne doit contenir qu'une adresse IP de la forme : a.b.c.d:<b>X</b> (ex : 168.192.1.1:5)<br />si <b>X</b> >= 5 alors l'accès sera refusé<br /><br />Ce fichier est mis à jour automatiquement par l'anti-spam de NPDS.": $tmp = "Cada l&iacute;nea debe contener que una IP de la forma : a.b.c.d:<b>X</b> (ex : 168.192.1.1:5)<br />Si <b>X</b> >= 5 entonces el acceso ser&aacute; denegado<br /></br />Este fichero se actualiza autom&aacute;ticamente por el NPDS anti-spam."; break;
       default: $tmp = "Necesita una traducci&oacute;n <b>[** $phrase **]</b>"; break;
    }
    return $tmp;
}
?>
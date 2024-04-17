<?php
/************************************************************************/
/* NPDS DUNE : Net Portal Dynamic System .                              */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2024 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
// OBJET : Ce fichier vous permet d'envoyer un MI personnalisé lorsqu'un nouveau membre s'inscrit sur votre site
// Configuration :
// $emetteur_id : id de l'émetteur du Message Interne lorsque un nouveau membre est créé : 1 = anonyme
// $sujet       : sujet du MI
// $message     : contenu du message (html, meta-mot, ...) SANS les <br />, NPDS fera le nécessaire


$emetteur_id="1";
$sujet="[french]Bonjour[/french][english]Hi[/english][chinese]你好[/chinese][spanish]Hola[/spanish][german]Hallo[/german]";
$message="[french]Vous &#xEA;tes maintenant un membre &#xE0; part enti&#xE8;re.
Ce site vous offre de nombreuses ressources alors ne vous privez pas : participez !

L'&#xE9;quipe du site.[/french][english]You are now a full member.
This website offers you many resources so do not deprive yourself: participate!

The site team.[/english][chinese]&#x60A8;&#x73B0;&#x5728;&#x662F;&#x6B63;&#x5F0F;&#x4F1A;&#x5458;&#x3002;
&#x672C;&#x7F51;&#x7AD9;&#x4E3A;&#x60A8;&#x63D0;&#x4F9B;&#x591A;&#x79CD;&#x8D44;&#x6E90;&#xFF0C;&#x6240;&#x4EE5;&#x4E0D;&#x8981;&#x5265;&#x593A;&#x81EA;&#x5DF1;&#xFF1A;&#x53C2;&#x52A0;&#xFF01;

&#x73B0;&#x573A;&#x56E2;&#x961F;&#x3002;[/chinese][spanish]Ahora eres miembro de pleno derecho.
Este sitio web te ofrece muchos recursos, as&#xED; que no te prives: &#xA1;participa!

El equipo del sitio.[/spanish][german]Sie sind jetzt Vollmitglied.
Diese Website bietet Ihnen viele Ressourcen, also berauben Sie sich nicht: Machen Sie mit!

Das Site-Team.[/german]";
?>
<?php
/************************************************************************/
/* NPDS DUNE : Net Portal Dynamic System .                              */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2017 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
// OBJET : Ce fichier vous permet d'envoyer un MI personnalisé lorsqu'un nouveau membre s'inscrit sur votre site
// Configuration :
// $emetteur_id : id de l'émetteur du Message Interne lorsque un nouveau membre est créé : 1 = anonyme
// $sujet       : sujet du MI
// $message     : contenu du message (html, meta-mot, ...) SANS les <br />, NPDS fera le nécessaire


$emetteur_id="1";
$sujet="Bonjour";
$message="Vous &#xEA;tes maintenant un membre &#xE0; part enti&#xE8;re.
Ce site vous offre de nombreuses ressources alors ne vous privez pas : participez !


L'&#xE9;quipe du site.";
?>
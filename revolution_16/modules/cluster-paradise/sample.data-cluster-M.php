<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Cluster Paradise - Manage Data-Cluster  / Mod by Tribal-Dolphin      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2017 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
/* -------------------------------------------------------------------------------------------------------------*/
/* chaque tableau décrit les relations dans le cluster                                                          */
/* WWW : doit contenir l'url complète de votre partenaire esclave (SANS http://)                                */
/* SUBSCRIBE : doit contenir le type d'abonnement (pour l'instant NEWS)                                         */
/* OP : doit contenir le sens de l'abonnement (EXPORT pour vous IMPORT pour le(s) site(s) esclave(s))           */
/* FROMTOPICID : ID du Topic dont les news sont envoyées. Si vide, tous les Topics sont envoyées                */
/* TOTOPIC     : Nom (long) du topic de destination sur le site esclave. Si vide, Topic par défaut              */
/* FROMCATID   : ID de la Catégorie dont les news sont envoyées. Si vide, toutes les catégories sont envoyées   */
/* TOCATEG     : Nom de la catégorie de destination sur le site esclave. Si vide, Catégorie par défaut          */
/*                                                                                                              */
/* AUTHOR : doit contenir le pseudo de l'Admin avec lequel sera publié la News - nous vous recommandons de      */
/*          créer un Admin spécifique (le même) sur le(s) site(s) esclave(s)                                    */
/*          /!\ ATTENTION : cet admin ne doit avoir QUE les droits de publication d'articles                    */
/*                                                                                                              */
/* MEMBER : doit contenir le pseudo du membre considéré comme l'auteur des News - nous vous recommandons de     */
/*          créer un MEMBRE spécifique (le même) sur le(s) site(s) esclave(s)                                   */
/*          /!\ ATTENTION : ce membre doit avoir un pseudo et un nom IDENTIQUE (zone véritable identité)        */
/*                                                                                                              */
/* le tableau ayant comme indice 0 est réservé exclusivement à vous (vous êtes le maitre du cluster)            */
/* $part[0]=array(                                                                                              */
/*  "WWW"=> "www.npds.org",                                                                                     */
/*  "KEY"=> "la_clef_de_npds.org",                                                                              */
/* );                                                                                                           */
/*                                                                                                              */
/* les tableaux suivants, vos partenaires                                                                       */
/* $part[1]=array(                                                                                              */
/*  "WWW"=> "modules.npds.org",                                                                                 */
/*  "SUBSCRIBE"=> "NEWS",                                                                                       */
/*  "OP"=> "EXPORT",                                                                                            */
/*  "FROMTOPICID"=> "5",                                                                                        */
/*  "TOTOPIC"=> "GNU / GPL",                                                                                    */
/*  "FROMCATID"=> "3",                                                                                          */
/*  "TOCATEG"=> "Nouvelles du Monde"                                                                            */
/*  "AUTHOR"=> "NPDS-Cluster",                                                                                  */
/*  "MEMBER"=> "NPDS"                                                                                           */
/* );                                                                                                           */
/* -------------------------------------------------------------------------------------------------------------*/

// Maitre (vous) 
$part[0]=array(
  "WWW"=> "www.npds.org",
  "KEY"=> "la_clef_de_npds.org",
);

// Esclave N°1
$part[1]=array(
  "WWW"=> "www.esclave-un.net",
  "SUBSCRIBE"=> "NEWS",
  "OP"=> "EXPORT",
  "FROMTOPICID"=> "5",
  "TOTOPIC"=> "GNU / GPL",
  "FROMCATID"=> "",
  "TOCATEG"=> "",
  "AUTHOR"=> "NPDS-Cluster",
  "MEMBER"=> "NPDS"
);

// Esclave N°2
$part[2]=array(
  "WWW"=> "www.esclave-deux.net",
  "SUBSCRIBE"=> "NEWS",
  "OP"=> "EXPORT",
  "FROMTOPICID"=> "",
  "TOTOPIC"=> "",
  "FROMCATID"=> "",
  "TOCATEG"=> "",
  "AUTHOR"=> "NPDS-Cluster",
  "MEMBER"=> "NPDS"
);
?>
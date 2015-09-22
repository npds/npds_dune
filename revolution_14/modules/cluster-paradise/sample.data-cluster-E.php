<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Cluster Paradise - Manage Data-Cluster  / Mod by Tribal-Dolphin      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2013 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
/* -------------------------------------------------------------------------------------------------------------*/
/* chaque tableau décrit l'un de vos partenaires dans le cluster (vous pouvez être membre de plusieurs cluster) */
/* WWW : doit contenir l'url complète de votre partenaire maitre (SANS http://)                                 */
/* KEY : doit contenir sa clef de sécurité (demandez là au webmestre du site maitre du cluster)                 */
/* SUBSCRIBE : doit contenir le type d'abonnement (pour l'instant NEWS)                                         */
/* OP : doit contenir le sens de l'abonnement (pour l'instant IMPORT pour vous EXPORT pour le maitre du cluster)*/
/*                                                                                                              */
/* $part[0]=array(                                                                                              */
/*  "WWW"=> "www.npds.org",                                                                                     */
/*  "KEY"=> "la_clef_de_npds.org",                                                                              */
/*  "SUBSCRIBE"=> "NEWS",                                                                                       */
/*  "OP"=> "IMPORT"                                                                                             */
/* );                                                                                                           */
/* -------------------------------------------------------------------------------------------------------------*/

// Maitre N°1
$part[0]=array(
  "WWW"=> "www.npds.org",
  "KEY"=> "la_clef_de_npds.org",
  "SUBSCRIBE"=> "NEWS",
  "OP"=> "IMPORT"
);
?>
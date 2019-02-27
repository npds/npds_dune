<?php 
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/*                                                                      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2019 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/*                                                                      */
/* module geoloc version 4.0                                            */
/* geoloc_conf.php file 2008-2019 by Jean Pierre Barbary (jpb)          */
/* dev team : Philippe Revilliod (Phr)                                  */
/************************************************************************/
$api_key = ""; // clef api google 
$api_key_bing = ""; // clef api bing maps 
$ch_lat = "C7"; // Champ lat dans sql 
$ch_lon = "C8"; // Champ long dans sql 
// interface carte 
$cartyp = "OSM"; // Type de carte 
$co_unit = "dms"; // Coordinates Units
$ch_img = "modules/geoloc/images/"; // Chemin des images 
$geo_ip = 0; // Autorisation de géolocalisation des IP 
$nm_img_acg = "acg.png"; // Nom fichier image anonyme géoréférencé en ligne 
$nm_img_mbcg = "mbcg.png"; // Nom fichier image membre géoréférencé en ligne 
$nm_img_mbg = "mbg.png"; // Nom fichier image membre géoréférencé 
$mark_typ = 0; // Type de marker 
$w_ico = "28"; // Largeur icone des markers 
$h_ico = "28"; // Hauteur icone des markers
$f_mbg = "uf007"; // Font SVG 
$mbg_sc = "0.5"; // Echelle du Font SVG du membre 
$mbg_t_ep = "1"; // Epaisseur trait Font SVG du membre 
$mbg_t_co = "rgba(10, 10, 10, 0.75)"; // Couleur trait SVG du membre 
$mbg_t_op = "1"; // Opacité trait SVG du membre 
$mbg_f_co = "rgba(237, 15, 58, 0.67)"; // Couleur fond SVG du membre 
$mbg_f_op = "0"; // Opacité fond SVG du membre 
$mbgc_sc = "0.5"; // Echelle du Font SVG du membre géoréférencé 
$mbgc_t_ep = "0"; // Epaisseur trait Font SVG du membre géoréférencé 
$mbgc_t_co = "rgb(101, 55, 55)"; // Couleur trait SVG du membre géoréférencé 
$mbgc_t_op = "1"; // Opacité trait SVG du membre géoréférencé 
$mbgc_f_co = "rgba(233, 22, 62, 0.87)"; // Couleur fond SVG du membre géoréférencé 
$mbgc_f_op = "1"; // Opacité fond SVG du membre géoréférencé 
$acg_sc = "0.5"; // Echelle du Font SVG pour anonyme en ligne 
$acg_t_ep = "0"; // Epaisseur trait Font SVG pour anonyme en ligne 
$acg_t_co = "rgb(153, 146, 142)"; // Couleur trait SVG pour anonyme en ligne 
$acg_t_op = "1"; // Opacité trait SVG pour anonyme en ligne 
$acg_f_co = "rgba(115, 115, 89, 0.85)"; // Couleur fond SVG pour anonyme en ligne 
$acg_f_op = "1"; // Opacité fond SVG pour anonyme en ligne 
// interface bloc 
$cartyp_b = "OSM"; // Type de carte pour le bloc 
$img_mbgb = "mbcg.png"; // Nom fichier image membre géoréférencé pour le bloc 
$w_ico_b = "28"; // Largeur icone marker dans le bloc 
$h_ico_b = "28"; // Hauteur icone marker dans le bloc
$h_b = "300"; // hauteur carte dans bloc
$z_b = "4"; // facteur zoom carte dans bloc
?>
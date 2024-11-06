<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This module don't use anyone of the config.php variable for security */
/* reasons                                                              */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

// Taille maxi des fichiers en octets
$max_size = 15728640;

// Si votre variable $DOCUMENT_ROOT n'est pas bonne (notamment en cas de redirection)
// vous pouvez en spécifier une ici (c'est le chemin physique d'accès à la racine de votre site en partant de / ou C:\)
// par exemple /data/web/mon_site OU c:\web\mon_site SINON LAISSER cette variable VIDE
$DOCUMENTROOT = '';

// Autorise l'upload DANS le répertoire personnel du membre (true ou false)
$autorise_upload_p = 'true';

// Sous répertoire : n'utiliser que si votre NPDS n'est pas directement dans la racine de votre site
// par exemple si : www.mon_site/npds/.... alors $racine="/npds" (avec le / DEVANT) sinon $racine="";
$racine = '';

// Répertoire de téléchargement (avec le / terminal)
$rep_upload = '/modules/upload/upload/';

// Répertoire de stockage des fichiers temporaires (avec le / terminal)
$rep_cache = '/modules/upload/tmp/';

// Répertoire/fichier de stockage de la log de téléchargement (par défaut /slogs/security.log)
$rep_log = '/slogs/security.log';

// URL HTTP de votre site (exemple : http://www.monsite.org)  !
$url_upload = 'http://localhost';

// URL de la feuille de style à utiliser pour la présentation de la fenetre d'upload (ou "")

global $cookie, $user, $Default_Theme, $theme;
if (isset($user)) {
   if ($cookie[9]=='') $cookie[9]=$Default_Theme;
   if (isset($theme)) $cookie[9]=$theme;
   $tmp_theme=$cookie[9];
   if (!$file=@opendir("themes/$cookie[9]")) $tmp_theme=$Default_Theme;
} else {
   $tmp_theme=$Default_Theme;
}
/*
pour une css dans le theme courant utiliser :
$url_upload_css = $racine."/themes/".$tmp_theme."/style/style.css";
*/
$url_upload_css = '/lib/bootstrap/dist/css/bootstrap.min.css';


/* -------------- DIVERS -------------- */
// Gére l'affichage de la Banque Images et Documents : "0000" => rien / "1111" => tous
// 1 (true) ou 0 (False)
// - 1er position   : afficher les images de !divers
// - 2ième position : afficher les images de !mime
// - 3ième position : afficher les images de la racine du répertoire (celles qui seront téléchargées)
// - 4ième position : afficher les documents
$ed_profil = '1111';

// Nombre d'image par ligne dans l'afficheur d'image de l'editeur HTML
$ed_nb_images = 10;

// suffix des fichiers autorisés (séparé par un espace)
$extension_autorise = 'doc docx gif jpeg jpg mpeg mp3 mp4 pdf png ppt pptx rtf svg swf sxd sxi sxw tar tgz txt xls xlsx zip';

// Taille maxi en affichage des images dans les banques de l'Editeur HTML
$width_max = 50;
$height_max = 50;

// Limite de l'espace disque alloué pour l'upload (en octects)
$quota=733999999;
?>
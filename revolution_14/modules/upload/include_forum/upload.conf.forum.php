<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2009 by Philippe Brunier                     */
/* Copyright Snipe 2003  base sources du forum w-agora de Marc Druilhe  */
/************************************************************************/
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

   include ("modules/upload/upload.conf.php");
   // Répertoire serveur de la racine du site (avec le / terminal)
   if ($DOCUMENTROOT=="") {
      global $DOCUMENT_ROOT;
      if ($DOCUMENT_ROOT) {
         $DOCUMENTROOT=$DOCUMENT_ROOT;
      } else {
         $DOCUMENTROOT=$_SERVER['DOCUMENT_ROOT'];
      }
   }
   // Répertoire de téléchargement (avec le / terminal)
   $rep_upload_forum = $racine."/modules/upload/upload_forum/";

   // Max size
   $MAX_FILE_SIZE_TOTAL = $quota;
   $MAX_FILE_SIZE = $max_size;

   // Divers / Don't modify !
   $insert_base = true;
   $visible_forum = 1;

/************************************************************************/
/* $bn_allowed_extensions : Autoriser les utilisateurs à uploader des   */
/* fichier dans la rédaction des messages. Dans les champs suivants,    */
/* vous pouvez alors préciser quelles extentions sont autorisées        */
/* (si rien n'est spécifié, toute les extentions sont autorisées) ou    */
/* $bn_banned_extensions quelles extentions sont interdites (si rien    */
/* n'est spécifié, aucune extention n'est interdite)                    */
/************************************************************************/
/************************************************************************/
/*(saisissez les extensions de fichiers (ex : .gif) que vous souhaitez  */
/*autoriser pour l'envoi des fichiers, séparés par des espaces, virgules*/
/* ou point-virgule)                                                    */
/************************************************************************/
$bn_allowed_extensions = ".".str_replace(" "," .",$extension_autorise);
$bn_banned_extensions = ".php .php3 .phps .htpasswd";
/************************************************************************/
/* $bn_allowed_mimetypes : Autoriser les utilisateurs à uploader des    */
/* fichier dans la rédaction des messages. Dans les champs suivants,    */
/* vous pouvez alors préciser quelles mimetypes sont autorisées         */
/* (si rien n'est spécifié, toute les mimetypes sont autorisées) ou     */
/* $bn_banned_mimetypes quelles mimetypes sont interdites (si rien      */
/* n'est spécifié, aucune mimetypes n'est interdite)                    */
/************************************************************************/
/************************************************************************/
/*(saisissez les types MIME (ex : image/gif, text/*) que vous souhaitez */
/*autoriser pour l'envoi des fichiers, séparés par des espaces, virgules*/
/* ou point-virgule)                                                    */
/************************************************************************/
$bn_allowed_mimetypes = "";
$bn_banned_mimetypes = "";

// --------------
$upload_conf = 1;
?>
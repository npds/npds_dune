<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/* Copyright Snipe 2003  base sources du forum w-agora de Marc Druilhe  */
/************************************************************************/
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

   include ("modules/upload/upload.conf.php");
   // Répertoire serveur de la racine du site (avec le / terminal)
   if ($DOCUMENTROOT=='') {
      global $DOCUMENT_ROOT;
      if ($DOCUMENT_ROOT)
         $DOCUMENTROOT=$DOCUMENT_ROOT;
      else
         $DOCUMENTROOT=$_SERVER['DOCUMENT_ROOT'];
   }

   // Répertoire de téléchargement (avec le / terminal)
   if (isset($groupe)) {
      settype($groupe, 'integer');
      $rep_upload_editeur =  $racine."/users_private/groupe/".$groupe."/";
      $path_upload_editeur = "users_private/groupe/".$groupe."/";
   } else {
      if ($user) {
         $userX = base64_decode($user);
         $userdata = explode(":", $userX);
         if (trim($userdata[1])!='') {
            $uname=$cookie[1];
         }
         if ($autorise_upload_p) {
            $user_dir=$racine."/users_private/".$uname;
            $path_upload_editeur = "users_private/".$uname."/";
            if (!is_dir($DOCUMENTROOT.$user_dir)) {
               @umask("0000");
               if (@mkdir($DOCUMENTROOT.$user_dir,0777)) {
                  $fp = fopen($DOCUMENTROOT.$user_dir."/index.html", 'w');
                  fclose($fp);
               } else
                  Access_Error();
            }
         } else
            Access_Error();
         $rep_upload_editeur = $user_dir."/";
      } else {
         $rep_upload_editeur = $racine."/modules/upload/upload/";
         $path_upload_editeur = "modules/upload/upload/";
      }
   }

   // Max size
   $MAX_FILE_SIZE = $max_size;

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
$bn_allowed_extensions = ".".str_replace(" "," .",$extension_autorise)." .html .bak";
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
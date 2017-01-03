<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2017 by Philippe Brunier                     */
/* Copyright Snipe 2003  base sources du forum w-agora de Marc Druilhe  */
/************************************************************************/
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

   include ("modules/upload/upload.conf.php");
   // R�pertoire serveur de la racine du site (avec le / terminal)
   if ($DOCUMENTROOT=="") {
      global $DOCUMENT_ROOT;
      if ($DOCUMENT_ROOT) {
         $DOCUMENTROOT=$DOCUMENT_ROOT;
      } else {
         $DOCUMENTROOT=$_SERVER['DOCUMENT_ROOT'];
      }
   }

   // R�pertoire de t�l�chargement (avec le / terminal)
   if (isset($groupe)) {
      settype($groupe, 'integer');
      $rep_upload_editeur =  $racine."/users_private/groupe/".$groupe."/";
      $path_upload_editeur = "users_private/groupe/".$groupe."/";
   } else {
      if ($user) {
         $userX = base64_decode($user);
         $userdata = explode(":", $userX);
         if (trim($userdata[1])!="") {
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
               } else {
                  Access_Error();
               }
            }
         } else {
            Access_Error();
         }
         $rep_upload_editeur = $user_dir."/";
      } else {
         $rep_upload_editeur = $racine."/modules/upload/upload/";
         $path_upload_editeur = "modules/upload/upload/";
      }
   }

   // Max size
   $MAX_FILE_SIZE = $max_size;

/************************************************************************/
/* $bn_allowed_extensions : Autoriser les utilisateurs � uploader des   */
/* fichier dans la r�daction des messages. Dans les champs suivants,    */
/* vous pouvez alors pr�ciser quelles extentions sont autoris�es        */
/* (si rien n'est sp�cifi�, toute les extentions sont autoris�es) ou    */
/* $bn_banned_extensions quelles extentions sont interdites (si rien    */
/* n'est sp�cifi�, aucune extention n'est interdite)                    */
/************************************************************************/
/************************************************************************/
/*(saisissez les extensions de fichiers (ex : .gif) que vous souhaitez  */
/*autoriser pour l'envoi des fichiers, s�par�s par des espaces, virgules*/
/* ou point-virgule)                                                    */
/************************************************************************/
$bn_allowed_extensions = ".".str_replace(" "," .",$extension_autorise)." .html .bak";
$bn_banned_extensions = ".php .php3 .phps .htpasswd";
/************************************************************************/
/* $bn_allowed_mimetypes : Autoriser les utilisateurs � uploader des    */
/* fichier dans la r�daction des messages. Dans les champs suivants,    */
/* vous pouvez alors pr�ciser quelles mimetypes sont autoris�es         */
/* (si rien n'est sp�cifi�, toute les mimetypes sont autoris�es) ou     */
/* $bn_banned_mimetypes quelles mimetypes sont interdites (si rien      */
/* n'est sp�cifi�, aucune mimetypes n'est interdite)                    */
/************************************************************************/
/************************************************************************/
/*(saisissez les types MIME (ex : image/gif, text/*) que vous souhaitez */
/*autoriser pour l'envoi des fichiers, s�par�s par des espaces, virgules*/
/* ou point-virgule)                                                    */
/************************************************************************/
$bn_allowed_mimetypes = "";
$bn_banned_mimetypes = "";

// --------------
$upload_conf = 1;
?>
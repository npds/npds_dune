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
if (!stristr($_SERVER['PHP_SELF'],'modules.php')) die();

global $Titlesitename;
/*****************************************************/
/* Include et définition                             */
/*****************************************************/
$forum=$IdForum;
include_once("auth.php");
include_once("functions.php");
include_once("modules/upload/lang/upload.lang-$language.php");
include_once("modules/upload/include_forum/upload.conf.forum.php");
include_once("modules/upload/include_forum/upload.func.forum.php");
include_once("lib/file.class.php");

$inline_list['1'] = upload_translate("Oui");
$inline_list['0'] = upload_translate("Non");

// Security
if (!$allow_upload_forum) Access_Error();
if (!autorize()) Access_Error();

/*****************************************************/
/* Entete                                            */
/*****************************************************/
ob_start();
$Titlesitename=upload_translate("Télécharg.");
include("meta/meta.php");
$userX = base64_decode($user);
$userdata = explode(':', $userX);
if ($userdata[9]!='') {
   $ibix=explode('+', urldecode($userdata[9]));
   if (array_key_exists(0, $ibix)) $theme=$ibix[0]; else $theme=$Default_Theme;
   if (array_key_exists(1, $ibix)) $skin=$ibix[1]; else $skin=$Default_Skin; 
   $tmp_theme=$theme;
   if (!$file=@opendir("themes/$theme")) $tmp_theme=$Default_Theme;
}
else 
   $tmp_theme=$Default_Theme;
$skin = $skin =='' ? 'default' : $skin ;

echo '
      <link rel="stylesheet" href="lib/font-awesome/css/all.min.css" />
      <link rel="stylesheet" href="lib/bootstrap/dist/css/bootstrap-icons.css" />
      <link rel="stylesheet" href="lib/bootstrap-table/dist/bootstrap-table.min.css" />';
echo import_css($tmp_theme, $language, $skin, '','');
echo '
   </head>
   <body class="bg-body-tertiary">';

// Moderator
global $NPDS_Prefix;
$sql = "SELECT forum_moderator FROM ".$NPDS_Prefix."forums WHERE forum_id = '$forum'";
if (!$result = sql_query($sql))
   forumerror('0001');
$myrow = sql_fetch_assoc($result);
$moderator=get_moderator($myrow['forum_moderator']);
$moderator=explode(' ',$moderator);
$Mmod=false;
for ($i = 0; $i < count($moderator); $i++) {
   if (($userdata[1]==$moderator[$i])) { $Mmod=true; break;}
}
$thanks_msg='';
settype($actiontype,'string');
settype($visible_att, 'array');
if ($actiontype) {
   switch ($actiontype) {
      case 'delete' : delete($del_att); break;
      case 'upload' : $thanks_msg = forum_upload(); break;
      case 'update' : update_inline($inline_att); break;
      case 'visible': if ($Mmod) { update_visibilite($visible_att,$visible_list); } break;
   }
}

include("modules/upload/include/minigf.php");

/*****************************************************/
/* Upload du fichier                                 */
/*****************************************************/
function forum_upload() {
   global $apli, $IdPost, $IdForum, $IdTopic, $pcfile, $pcfile_size, $pcfile_name, $pcfile_type, $att_count, $att_size, $total_att_count, $total_att_size;
   global $MAX_FILE_SIZE, $MAX_FILE_SIZE_TOTAL, $mimetypes, $mimetype_default, $upload_table,$rep_upload_forum;// mine......
   list($sum)=sql_fetch_row(sql_query("SELECT SUM(att_size ) FROM $upload_table WHERE apli = '$apli' AND post_id = '$IdPost'"));

   // gestion du quota de place d'un post
   if (($MAX_FILE_SIZE_TOTAL - $sum)<$MAX_FILE_SIZE)
      $MAX_FILE_SIZE = $MAX_FILE_SIZE_TOTAL - $sum;
   include "modules/upload/include/fileupload.php";
   settype($thanks_msg,'string');

   // Récupération des valeurs de PCFILE
   global $HTTP_POST_FILES, $_FILES;
   $fic = (!empty($HTTP_POST_FILES)) ? $HTTP_POST_FILES : $_FILES ;
   $pcfile_name = $fic['pcfile']['name'];
   $pcfile_type = $fic['pcfile']['type'];
   $pcfile_size = $fic['pcfile']['size'];
   $pcfile = $fic['pcfile']['tmp_name'];

   $fu = new FileUpload;
   $fu->init ($rep_upload_forum,$IdForum,$apli);

   $att_count = 0;
   $att_size = 0;
   $total_att_count = 0;
   $total_att_size = 0;

   $attachments = $fu->getUploadedFiles($IdPost,$IdTopic);
   if (is_array ($attachments) ) {
      $att_count = $attachments['att_count'];
      $att_size = $attachments['att_size'];
      if (is_array($pcfile_name)) {
         reset ($pcfile_name);
         $names = implode (', ', $pcfile_name);
         $pcfile_name = $names;
      }
      $pcfile_size = $att_size;
      $thanks_msg .= '<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'.str_replace ('{NAME}', '<strong>'.$pcfile_name.'</strong>', str_replace('{SIZE}', $pcfile_size, upload_translate("Fichier {NAME} bien reçu ({SIZE} octets transférés)"))).'</div>';
      $total_att_count += $att_count;
      $total_att_size += $att_size;
   }
   return ($thanks_msg);
}
?>
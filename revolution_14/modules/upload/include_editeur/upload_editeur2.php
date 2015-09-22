<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2012 by Philippe Brunier                     */
/* Copyright Snipe 2003  base sources du forum w-agora de Marc Druilhe  */
/************************************************************************/
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],"modules.php")) { die(); }

/*****************************************************/
/* Include et definition                             */
/*****************************************************/
   include_once("modules/upload/lang/upload.lang-$language.php");
   include_once("modules/upload/include_editeur/upload.conf.editeur.php");

/*****************************************************/
/* Entete                                            */
/*****************************************************/
   $Titlesitename=upload_translate("Télécharg.");
   include("meta/meta.php");
   if ($url_upload_css) {
      $url_upload_cssX=str_replace("style.css","$language-style.css",$url_upload_css);
      if (is_readable($url_upload.$url_upload_cssX))
         $url_upload_css=$url_upload_cssX;
      print ("<link href=\"".$url_upload.$url_upload_css."\" title=\"default\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />\n");
   }
   echo "</head>\n";

   if (isset($actiontype)) {
      switch ($actiontype) {
      case 'upload' :
         $ret=editeur_upload();
         if ($ret!="") {
            $suffix=strtoLower(substr(strrchr($ret,'.'),1));
            if ($suffix=="gif" or  $suffix=="jpg" or $suffix=="png") {
                echo  "<script type=\"text/javascript\">
                //<![CDATA[
                window.opener.tinyMCE.execCommand('mceInsertContent', false, '<img src=\"$ret\" alt=\"".basename($ret)."\" border=\"0\" />');
                //]]>
                </script>";
            } else {
                echo  "<script type=\"text/javascript\">
                //<![CDATA[
                window.opener.tinyMCE.execCommand('mceInsertContent', false, '<a href=\"$ret\" target=\"_blank\" class=\"noir\">".basename($ret)."</a>');
                //]]>
                </script>";
            }
         }
         echo "<script type=\"text/javascript\">
               //<![CDATA[
               top.close();
               //]]>
               </script>";
         die();
      break;
      }
   }
   echo "\n<body topmargin=\"0\" leftmargin=\"0\" rightmargin=\"0\">";
   echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\">\n";
   echo "<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\" enctype=\"multipart/form-data\" name=\"formEdit\">\n";
   echo "<input type=\"hidden\" name=\"ModPath\" value=\"$ModPath\" />\n<input type=\"hidden\" name=\"ModStart\" value=\"$ModStart\" />\n<input type=\"hidden\" name=\"apli\" value=\"$apli\" />\n";
   if (isset($groupe)) {
      echo "<input type=\"hidden\" name=\"groupe\" value=\"$groupe\" />\n";
   }
   echo "<input type=\"hidden\" name=\"actiontype\" value=\"upload\" />\n";
   echo "<tr><td align=\"left\" valign=\"middle\">
         <b>".upload_translate("Télécharger un fichier sur le serveur")."</b> :<br /><br />
         <table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\">
         <tr>
            <td>".upload_translate("Fichier :")."</td>
            <td><input name=\"pcfile\" class=\"textbox_standard\" type=\"file\" id=\"pcfile\" value=\"\"><br /></td>
         </tr>
         <tr>
            <td colspan=\"2\" align=\"center\"><br /><input type=\"submit\" class=\"bouton_standard\" name=\"insert\" value=\"".upload_translate("Joindre")."\" />
            </td>
         </tr>
         </table></td></tr>
         </table>";
   echo "</form></body></html>";

/*****************************************************/
/* Upload du fichier                                 */
/*****************************************************/
function load_mimetypes () {
   global $mimetypes, $mimetype_default, $mime_dspinl, $mime_dspfmt, $mime_renderers, $att_icons, $att_icon_default, $att_icon_multiple;
   if (defined ('ATT_DSP_LINK')) {
      return;
   }

   if (file_exists("modules/upload/include/mimetypes.php") ) {
      include ("modules/upload/include/mimetypes.php");
   }
}

function editeur_upload() {
   global $apli, $pcfile, $pcfile_size, $pcfile_name, $pcfile_type;
   global $MAX_FILE_SIZE, $MAX_FILE_SIZE_TOTAL, $mimetypes, $mimetype_default, $rep_upload_editeur, $path_upload_editeur;

   include "modules/upload/include/fileupload.php";

   // Récupération des valeurs de PCFILE
   global $HTTP_POST_FILES, $_FILES;
   if (!empty($HTTP_POST_FILES))
       $fic=$HTTP_POST_FILES;
   else
       $fic=$_FILES;
   $pcfile_name = $fic['pcfile']['name'];
   $pcfile_type = $fic['pcfile']['type'];
   $pcfile_size = $fic['pcfile']['size'];
   $pcfile = $fic['pcfile']['tmp_name'];

   $fu = new FileUpload;
   $fu->init ($rep_upload_editeur,"",$apli);

   $attachments = $fu->getUploadedFiles("","");
   if (is_array ($attachments) ) {
      $att_count = $attachments["att_count"];
      $att_size = $attachments["att_size"];
      if (is_array($pcfile_name)) {
         reset ($pcfile_name);
         $names = implode (", ", $pcfile_name);
         $pcfile_name = $names;
      }
      return ($path_upload_editeur.$pcfile_name);
   } else {
      return ("");
   }
}
?>
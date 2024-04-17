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
      $url_upload_cssX=str_replace('style.css',"$language-style.css",$url_upload_css);
      if (is_readable($url_upload.$url_upload_cssX))
         $url_upload_css=$url_upload_cssX;
      print ("<link href=\"".$url_upload.$url_upload_css."\" title=\"default\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />\n");
   }
   echo "</head>\n";

   if (isset($actiontype)) {
      switch ($actiontype) {
      case 'upload' :
         $ret=editeur_upload();
         $js='';
         if ($ret!='') {
            $suffix=strtoLower(substr(strrchr($ret,'.'),1));
            if ($suffix=='gif' or  $suffix=='jpg' or  $suffix=='jpeg' or $suffix=='png') {
               $js .= "parent.tinymce.activeEditor.selection.setContent('<img class=\"img-fluid\" src=\"$ret\" alt=".basename($ret)." loading=\"lazy\" />');";
            } else {
               $js .= "parent.tinymce.activeEditor.selection.setContent('<a href=\"$ret\" target=\"_blank\">".basename($ret)."</a>');";
            }
         }
         echo "<script type=\"text/javascript\">
               //<![CDATA[
               ".$js."
               top.tinymce.activeEditor.windowManager.close();
               //]]>
               </script>";
         die();
      break;
      }
   }
   echo '
   <body topmargin="3" leftmargin="3" rightmargin="3">
      <div class="card card-body mx-2 mt-3">
         <form method="post" action="'.$_SERVER['PHP_SELF'].'" enctype="multipart/form-data" name="formEdit">
            <input type="hidden" name="ModPath" value="'.$ModPath.'" />
            <input type="hidden" name="ModStart" value="'.$ModStart.'" />
            <input type="hidden" name="apli" value="'.$apli.'" />';
   if (isset($groupe)) {
      echo '
            <input type="hidden" name="groupe" value="'.$groupe.'" />';
   }
   echo '
            <div class="mb-3 row">
               <input type="hidden" name="actiontype" value="upload" />
               <label class="form-label">'.upload_translate("Fichier").'</label>
               <input class="form-control" name="pcfile" type="file" id="pcfile" value="" />
            </div>
            <div class="mb-3 row">
               <input type="submit" class="btn btn-primary btn-sm" name="insert" value="'.upload_translate("Joindre").'" />
            </div>
         </form>
      </div>
   </body>
</html>';

/*****************************************************/
/* Upload du fichier                                 */
/*****************************************************/
function load_mimetypes () {
   global $mimetypes, $mimetype_default, $mime_dspinl, $mime_dspfmt, $mime_renderers, $att_icons, $att_icon_default, $att_icon_multiple;
   if (defined ('ATT_DSP_LINK'))
      return;
   if (file_exists("modules/upload/include/mimetypes.php") )
      include ("modules/upload/include/mimetypes.php");
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
   $fu->init ($rep_upload_editeur,'',$apli);

   $attachments = $fu->getUploadedFiles('','');
   if (is_array ($attachments) ) {
      $att_count = $attachments['att_count'];
      $att_size = $attachments['att_size'];
      if (is_array($pcfile_name)) {
         reset ($pcfile_name);
         $names = implode (', ', $pcfile_name);
         $pcfile_name = $names;
      }
      return ($path_upload_editeur.$pcfile_name);
   } else {
      return ('');
   }
}
?>
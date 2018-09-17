<?php
/*
 ************************************************************************
 * © Sloppycode.net All rights reserved.
 *
 * This is a standard copyright header for all source code appearing
 * at sloppycode.net. This application/class/script may be redistributed,
 * as long as the above copyright remains intact.
 * Comments to sloppycode@sloppycode.net
 ************************************************************************
*/

/*
 * Upload class - API for uploading files. See accompanying docs
 * More features and better error checking will come in the next version.
 * Please note that in most cases you should chmod a folder to 755 or 777
 * (on unix) before uploading to there, in order for it to work.
 *
 * @author developpeur@npds.org / Specific version for NPDS + language translation
 * @author C.Small <chris@sloppycode.net>
 * @version 1.2 Changed the upload method to globalise HTTP_POST_FILES,
 * changed the copy() function to the safer (and less bug prone)
 * move_uploaded_file() function, which works better on shared hosting.
 * @version 1.1 - Added PEAR style comments. Some requests have been made
 * to have only one save method to combine save and saveas. Its been kept
 * with 2 methods as I think this is easier to use.
 * @version 1.0 - Initial class
 * @access public
 */

class Upload {
   var $maxupload_size;
   var $errors;
   var $isPosted;
   var $HTTP_POST_FILES;

   public function __construct() {
      global $HTTP_POST_FILES, $_FILES;

      if (!empty($HTTP_POST_FILES))
         $fic=$HTTP_POST_FILES;
      else
         $fic=$_FILES;

      $this->HTTP_POST_FILES = $fic;

      if (empty($fic))
         $this->isPosted = false;
      else
         $this->isPosted = true;
   }

   public function Upload() {
      self::__construct();
   }

   function saveAs($filename, $directory, $field, $overwrite,$mode=0766) {
      if ($this->isPosted) {
         if ($this->HTTP_POST_FILES[$field]['size'] < $this->maxupload_size && $this->HTTP_POST_FILES[$field]['size'] >0) {
            $noerrors = true;
            $tempName = $this->HTTP_POST_FILES[$field]['tmp_name'];
            $all      = $directory.$filename;
            if (file_exists($all)) {
               if ($overwrite) {
                  @unlink($all) || $noerrors=false; $this->errors  = upload_translate("Erreur de téléchargement du fichier - fichier non sauvegardé.");
                  @move_uploaded_file($tempName,$all) || $noerrors=false; $this->errors .= upload_translate("Erreur de téléchargement du fichier - fichier non sauvegardé.");
                  @chmod($all,$mode);
               }
            } else {
               @move_uploaded_file($tempName,$all) || $noerrors=false; $this->errors  = upload_translate("Erreur de téléchargement du fichier - fichier non sauvegardé.");
               @chmod($all,$mode);
            }
            return $noerrors;
         } elseif ($this->HTTP_POST_FILES[$field]['size'] > $this->maxupload_size) {
            $this->errors = upload_translate("La taille de ce fichier excède la taille maximum autorisée")." => ".number_format(($this->maxupload_size/1024),2)." Kbs";
            return false;
         } elseif ($this->HTTP_POST_FILES[$field]['size'] == 0) {
            $this->errors = upload_translate("Erreur de téléchargement du fichier - fichier non sauvegardé.");
            return false;
         }
      }
   }

   function getFilename($field) {
      return $this->HTTP_POST_FILES[$field]['name'];
   }

   function getFileMimeType($field) {
      return $this->HTTP_POST_FILES[$field]['type'];
   }

   function getFileSize($field) {
      return $this->HTTP_POST_FILES[$field]['size'];
   }
}
?>
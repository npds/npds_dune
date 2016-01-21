<?php
/************************************************************************/
/* NPDS V : Net Portal Dynamic System .                                 */
/* ===========================                                          */
/*                                                                      */
/* File Class Manipulation                                              */
/* Copyright (c) Ade (www.ade21.net) 2005                               */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
class File {
   var $Url = "";
   var $Extention = "";
   var $Size = 0;
   function File($Url) {
      $this->Url = $Url;
   }
   function Size() {
      $this->Size = @filesize($this->Url);
   }
   function Extention() {
      $extension=strtolower(substr(strrchr($this->Url,'.'),1));
      $this->Extention = $extension;
   }
/*
   function pretty_Size($Fsize) {
   $text=array(" o"," Ko"," Mo"," Go");
   
   
      if ($Fsize>1073741824) {
//         $Fsize = round(($Fsize/1073741824),2);
         return $Fsize;
//         return round(($Fsize)/1073741824,$Dec).$text[3];
         break;
      }
      if ($Fsize>1048576) {
//         $Fsize = round(($Fsize/1073741824),2);
      
         return $Fsize;
//         return round(($Fsize)/1048576,$Dec).$text[2];
         break;
      }
      if ($Fsize>1024) {
      
//         $Fsize = round(($Fsize/1073741824),2);

//         return round(($Fsize)/1024,$Dec).$text[1];
         break;
      }
      if ($Fsize>0) {
//         $Fsize = round(($Fsize/1073741824),2);
               return ($Fsize);

//         return ($Fsize).$text[0];
         break;
      }
   }
*/

//non compatible php7//
/*
   function pretty_Size($Fsize, $Dec=2, $text=array(" o"," Ko"," Mo"," Go")) {
      if ($Fsize>(1073741824)) {
         return round(($Fsize)/1073741824,$Dec).$text[3];
         break;
      }
      if ($Fsize>(1048576)) {
         return round(($Fsize)/1048576,$Dec).$text[2];
         break;
      }
      if ($Fsize>(1024)) {
         return round(($Fsize)/1024,$Dec).$text[1];
         break;
      }
      if ($Fsize>0) {
         return ($Fsize).$text[0];
         break;
      }
   }
   */
  function Affiche_Size($Format="CONVERTI") {
      $this->Size();
      if (!$this->Size) return '<span class="text-danger"><strong>?</strong></span>';

      switch ( $Format ) {
         case "CONVERTI": // en kilo/mega ou giga
//            return ($this->pretty_Size($this->Size));
            return ('!!bug!!');
         break;

         case "NORMAL": // en octet
            return $this->Size;
         break;
      }
   }

   function Affiche_Extention($Format="IMG") {
      $this->Extention();
      switch ($Format) {
         case "IMG":
            if ($ibid=theme_image("upload/file_types/".$this->Extention.".gif")) {$imgtmp=$ibid;} else {$imgtmp="images/upload/file_types/".$this->Extention.".gif";}
            if (@file_exists($imgtmp)) return ($imgtmp); else return ("images/upload/file_types/unknown.gif");
         break;
      }
   }

}

// essai class pour php7
class FileManagement
{
    public $units    =    array('B', 'KB', 'MB', 'GB', 'TB');

    function file_size_format($fileName, $precision) {
        $bytes    =   $fileName;
        $bytes    =    max($bytes, 0);
        $pow    =    floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow     =    min($pow, count($this->units) - 1);
        $bytes /= pow(1024, $pow);
      
        $retValue = round($bytes, $precision) . ' ' . $this->units[$pow];
        return $retValue;
    }

    function file_size_auto($fileName, $precision) {
        $bytes    =    @filesize($fileName);
        $bytes    =    max($bytes, 0);
        $pow    =    floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow     =    min($pow, count($this->units) - 1);
        $bytes /= pow(1024, $pow);
      
        $retValue = round($bytes, $precision) . ' ' . $this->units[$pow];
        return $retValue;
    }
    
    function file_size_option($fileName, $unitType) {
        switch($unitType) {
            case $this->units[0]: 
                $fileSize = number_format((filesize(trim($fileName))), 1) ; 
                break;
            case $this->units[1]: 
                $fileSize = number_format((filesize(trim($fileName))/1024), 1) ; 
                break;
            case $this->units[2]: 
                $fileSize = number_format((filesize(trim($fileName))/1024/1024), 1) ; 
                break;
            case $this->units[3]: 
                $fileSize = number_format((filesize(trim($fileName))/1024/1024/1024), 1) ; 
                break;
            case $this->units[4]: 
                $fileSize = number_format((filesize(trim($fileName))/1024/1024/1024/1024), 1) ; 
                break;
        }
        $retValue = $fileSize. ' ' .$unitType;
        return $retValue;
    }
}
?>
<?php
/************************************************************************/
/* NPDS V : Net Portal Dynamic System .                                 */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2024 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

// Multi-language
$local_path='';
settype($user_language,'string');
if (isset($module_mark))
   $local_path='../../';
if (file_exists($local_path.'cache/language.php'))
   include ($local_path.'cache/language.php');
else
   include ($local_path.'manuels/list.php');

if (isset($choice_user_language)) {
   if ($choice_user_language!='') {
      if ($user_cook_duration<=0) {$user_cook_duration=1;}
      $timeX=time()+(3600*$user_cook_duration);
      if ((stristr($languageslist,$choice_user_language)) and ($choice_user_language!=' ')) {
         setcookie('user_language',$choice_user_language,$timeX);
         $user_language=$choice_user_language;
      }
   }
}
if ($multi_langue) {
   if (($user_language!='') and ($user_language!=" ")) {
      $tmpML=stristr($languageslist,$user_language);
      $tmpML=explode(' ',$tmpML);
      if ($tmpML[0])
         $language=$tmpML[0];
   }
}
// Multi-language
?>
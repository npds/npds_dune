<?php
/************************************************************************/
/* NPDS V : Net Portal Dynamic System .                                 */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2015 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
$local_path=''; $languageslist='';
if (isset($module_mark)) {$local_path='../../';}
$handle=opendir($local_path.'manuels');
while (false!==($file = readdir($handle))) {
   if (!strstr($file,'.')) {
      $languageslist .= "$file ";
   }
}
closedir($handle);
$file=fopen($local_path.'cache/language.php', 'w');
   fwrite($file, "<?php \$languageslist=\"".trim($languageslist)."\"; ?>");
fclose($file);
?>
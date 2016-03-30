<?php
/************************************************************** **********/
/* Modification par Jireck      Rocssti2015                                          */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
global $theme; $rep=false;
if (file_exists("themes/".$theme."/html/central.html")) {$rep=$theme;}
elseif (file_exists("themes/default/html/central.html")) {$rep="default";}
else {
   echo "central.html manquant / not find !< br />";
   die();
}
if ($rep) {
   ob_start();
   include("themes/".$rep."/html/central.html");
   $Xcontent=ob_get_contents();
   ob_end_clean();
   echo meta_lang(aff_langue($Xcontent));
}

?>
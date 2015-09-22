<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* DYNAMIC THEME engine for NPDS                                        */
/* NPDS Copyright (c) 2002-2011 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
global $theme; $rep=false;
settype($ContainerGlobal,'string');
if (file_exists("themes/".$theme."/html/header.html")) {$rep=$theme;}
elseif (file_exists("themes/default/html/header.html")) {$rep="default";}
else {
   echo "header.html manquant / not find !<br />";
   die();
}
if ($rep) {
   if (file_exists("modules/include/body_onload.inc") or file_exists("themes/$theme/include/body_onload.inc")) {
      $onload_init=" onload=\"init();\"";
   } else {
      $onload_init="";
   }
   if (!$ContainerGlobal)
      echo "<body".$onload_init." class=\"body\">\n";
   else {
      echo "<body".$onload_init.">";
      echo $ContainerGlobal;
   }
   ob_start();
   include("themes/".$rep."/html/header.html");
   $Xcontent=ob_get_contents();
   ob_end_clean();
   echo meta_lang(aff_langue($Xcontent));
}
?>
<?php
/************************************************************************/
/* NPDS DUNE : Net Portal Dynamic System                                */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2022 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

// cette variable fonctionne si $url_fma_modifier=true;
// $url_modifier permet de modifier le comportement du lien (a href ....) se trouvant sur les fichiers affichés par FMA
$repw=str_replace($basedir_fma,"",$cur_nav);
if ($repw!="")
   if (substr($repw,0,1)=="/")
      $repw=substr($repw,1)."/".$obj->FieldName;
else
   $repw=$obj->FieldName;

$url_modifier="\"#\" onclick=\"javascript:window.opener.document.adminForm.durl.value='".$repw."'; window.opener.document.adminForm.dfilename.value='".extend_ascii($obj->FieldName)."';\"";

?>
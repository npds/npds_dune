<?php
/************************************************************************/
/* NPDS DUNE : Net Portal Dynamic System .                              */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2017 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

// cette variable fonctionne si $url_fma_modifier=true;
// $url_modifier permet de modifier le comportement du lien (a href ....) se trouvant sur les fichiers affichés par FMA

if (($obj->FieldView=="jpg") or ($obj->FieldView=="gif") or ($obj->FieldView=="png")) {
   if ($tiny_mce)
       $url_modifier="\"#\" onclick=\"javascript:parent.tinymce.activeEditor.selection.setContent('<img class=img-fluid src=".str_replace(" ","%20","users_private".str_replace(dirname($basedir_fma),"",$cur_nav_back)."/".basename($cur_nav)."/".$obj->FieldName)." />');\"";
   else
      $url_modifier="\"#\"";
} else {
   if ($tiny_mce)
      $url_modifier="\"#\" onclick=\"javascript:parent.tinymce.activeEditor.selection.setContent('<a href=".str_replace(" ","%20","users_private".str_replace(dirname($basedir_fma),"",$cur_nav_back)."/".basename($cur_nav)."/".$obj->FieldName)." target=_blank>".$obj->FieldName."</a>');\"";
   else
      $url_modifier="\"".str_replace(" ","%20","users_private".str_replace(dirname($basedir_fma),"",$cur_nav_back)."/".basename($cur_nav)."/".$obj->FieldName)."\"";
}
?>
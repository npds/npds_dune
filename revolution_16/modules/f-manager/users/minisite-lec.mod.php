<?php
/************************************************************************/
/* NPDS DUNE : Net Portal Dynamic System .                              */
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

if (($obj->FieldView=="jpg") or ($obj->FieldView=="gif") or ($obj->FieldView=="png") or ($obj->FieldView=="jpeg"))
   $url_modifier = $tiny_mce ? 
      "\"#\" onclick=\"javascript:parent.tinymce.activeEditor.selection.setContent('<img class=img-fluid src=getfile.php?att_id=$ibid&amp;apli=f-manager />');\"" :
      "\"#\"";
else
   $url_modifier = $tiny_mce ? 
      "\"#\" onclick=\"javascript:parent.tinymce.activeEditor.selection.setContent('<a href=getfile.php?att_id=$ibid&amp;apli=f-manager target=_blank>".$obj->FieldName."</a>');\"" :
      "\"getfile.php?att_id=$ibid&amp;apli=f-manager\"";
?>
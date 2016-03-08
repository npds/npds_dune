<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

// skins for advanced theme
// skin : "default" or "o2k7"
// skin variant : "" or "silver" or "black"
$tmp.='
skin : "lightgray",';

// Analyse the type of Theme from pages.php and determine if setup inclusion file is request
$setup=explode("+",$tiny_mce_theme);
$tiny_mce_theme=$setup[0];
if (!array_key_exists(1,$setup)) $setup[1]='';
// ----------

// Full Theme
if ($tiny_mce_theme=="full") {
   $tmp.= "
plugins: [
   'advlist autolink lists link image charmap print preview hr anchor pagebreak',
   'searchreplace wordcount visualblocks visualchars code fullscreen',
   'insertdatetime media nonbreaking save table contextmenu directionality',
   'emoticons template paste textcolor colorpicker textpattern imagetools npds fontawesome '
],

image_class_list: [
    {title: 'Responsive', value: 'img_fluid'}
  ],

style_formats: [
  {title: 'Image responsive', selector: 'img', styles: {
   'display' : 'block',
   'max-width': '100%',
   'height' : 'auto'
  }}],



content_css: ' lib/font-awesome-4.5.0/css/font-awesome.min.css,lib/dist/css/bootstrap.min.css',
toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent forecolor backcolor emoticons | link image | mybutton npds_img npds_perso npds_mns npds_upl npds_metal npds_plug npds_langue ',";

} else if ($tiny_mce_theme=='short') {
   // Short Theme
   $tmp.=" 
plugins : ['autolink wordcount npds image link media paste'],
toolbar: 'bold italic underline strikethrough | pastetext pasteword | justifyleft justifycenter justifyright justifyfull | fontsizeselect | bullist numlist outdent indent forecolor backcolor | search link unlink code | image media npds_img npds_perso npds_mns npds_upl npds_plug',\n";
}
$tmp.="
extended_valid_elements : \"hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]\",
directionality          : 'ltr',
auto_focus              : '".substr($tmp_Xzone,0,strpos($tmp_Xzone,",",0))."',
apply_source_formatting : true,
force_br_newlines       : true,
force_p_newlines        : false,
convert_newlines_to_brs : false,
remove_linebreaks       : false,\n";

if ($tiny_mce_relurl=="false")
   $tmp.='
relative_urls        : false,';
else
   $tmp.='
relative_urls        : true,';

if ($setup[1]=='setup') {
   global $ModPath;
   if (file_exists("modules/$ModPath/tiny_mce_setup.php")) {
      $tmp.="remove_script_host   : false,\n";
      include_once("modules/$ModPath/tiny_mce_setup.php");
   }
} else {
   $tmp.="remove_script_host   : false\n";
}
?>
<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2022 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
global $nuke_url, $multi_langue;

$tinylangmenu = $multi_langue!==false ? 'npds_langue' : '' ;
// skin : "oxide" ou "oxide-dark"
$tmp.='
            promotion : false,
            skin : "oxide-dark",
            template_popup_width: "800",
            toolbar_mode: "sliding",
            templates : [
               {
                  title: "Une colonne",
                  url: "'.$nuke_url.'/editeur/tinymce/plugins/template/txt_1_col_12.html",
                  description: "Texte sur une colonne"
               },
               {
                  title: "Deux colonnes (50% 50%)",
                  url: "'.$nuke_url.'/editeur/tinymce/plugins/template/txt_2_col_6-6.html",
                  description: "Texte sur deux colonnes de largeurs égales"
               },
               {
                  title: "Trois colonnes (33% 33% 33%)",
                  url: "'.$nuke_url.'/editeur/tinymce/plugins/template/txt_3_col_4-4-4.html",
                  description: "Texte sur trois colonnes de largeurs égales"
               },
               {
                  title: "Deux colonnes (33% 66%)",
                  url: "'.$nuke_url.'/editeur/tinymce/plugins/template/txt_2_col_4-8.html",
                  description: "Texte sur deux colonnes de largeurs inégales"
               },
               {
                  title: "Deux colonnes (66% 33%)",
                  url: "'.$nuke_url.'/editeur/tinymce/plugins/template/txt_2_col_8-4.html",
                  description: "Texte sur deux colonnes de largeurs inégales"
               },
               {
                  title: "Trois colonnes (25% 50% 25%)",
                  url: "'.$nuke_url.'/editeur/tinymce/plugins/template/txt_3_col_3-6-3.html",
                  description: "Texte sur trois colonnes de largeurs inégales"
               },
               {
                  title: "Deux colonnes image/texte (33% 66%)",
                  url: "'.$nuke_url.'/editeur/tinymce/plugins/template/img_txt_2_col_4-8.html",
                  description: "Image, texte sur deux colonnes de largeurs inégales"
               },
               {
                  title: "Deux colonnes image/texte (17% 83%)",
                  url: "'.$nuke_url.'/editeur/tinymce/plugins/template/img_txt_2_col_2-10.html",
                  description: "Image, texte sur deux colonnes de largeurs inégales"
               },
               {
                  title: "Trois colonnes image/texte/image (25% 50% 25%)",
                  url: "'.$nuke_url.'/editeur/tinymce/plugins/template/img_txt_img_3_col_3-6-3.html",
                  description: "Texte sur trois colonnes de largeurs inégales"
               }
               ],';

// Analyse the type of Theme from pages.php and determine if setup inclusion file is request
$setup=explode('+',$tiny_mce_theme);
$tiny_mce_theme=$setup[0];
if (!array_key_exists(1,$setup)) $setup[1]='';

// Full Theme
if ($tiny_mce_theme=='full') {
   $tmp.= "
            plugins: ['quickbars', 'autoresize', 'preview', 'importcss', 'searchreplace', 'autolink', 'autosave', 'save', 'directionality', 'code', 'visualblocks', 'visualchars', 'fullscreen', 'image', 'link', 'media', 'template', 'codesample', 'table', 'charmap', 'pagebreak', 'nonbreaking', 'anchor', 'insertdatetime', 'advlist', 'lists', 'wordcount', 'help', 'charmap', 'emoticons', 'npds'],
//            extended_valid_elements : 'img[class|src|alt|title|width|loading=lazy]',

  extended_valid_elements: 'img[class=img-fluid|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|loading=lazy]',

            image_class_list : [{title: 'Responsive', value: 'img_fluid'}],
            style_formats_merge : true,
            style_formats : [
               {title: 'Headers', items: [
                  {title: 'h1', block: 'h1'},
                  {title: 'h2', block: 'h2'},
                  {title: 'h3', block: 'h3'},
                  {title: 'h4', block: 'h4'},
                  {title: 'h5', block: 'h5'},
                  {title: 'h6', block: 'h6'}
               ]},
               {title: 'Image responsive', selector: 'img', styles: {
               'display' : 'block',
               'max-width': '100%',
               'height' : 'auto'
            }}],
            font_family_formats: 'Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats',
            font_size_formats: '0.4rem 0.5rem 0.6rem 0.7rem 0.8rem 0.9rem 1rem 1.1rem 1.2rem 1.3rem 1.4rem 1.5rem 1.6rem 1.7rem 1.8rem 1.9rem 2rem',
            toolbar: 'undo redo | bold italic underline strikethrough | fontfamily fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen preview save print | image media template link anchor codesample | ltr rtl help | npds_img npds_perso npds_metal npds_upl npds_mns ".$tinylangmenu."',";

} else if ($tiny_mce_theme=='short') {
   // Short Theme
   $tmp.=" 
            plugins : ['quickbars', 'autoresize', 'autolink', 'wordcount', 'image', 'table', 'link', 'media', 'npds'],
            toolbar : 'bold italic underline strikethrough | pastetext pasteword | justifyleft justifycenter justifyright justifyfull | fontsizeselect | bullist numlist outdent indent forecolor backcolor | search link unlink code | image media npds_img npds_perso npds_mns npds_upl npds_metal ".$tinylangmenu."',\n";
}
$tmp.="
            content_css : ' lib/font-awesome/css/all.min.css,lib/bootstrap/dist/css/bootstrap.min.css,editeur/tinymce/themes/advanced/npds.css',
            extended_valid_elements : 'hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]',
            directionality: 'ltr',
//            auto_focus: '".substr($tmp_Xzone,0,strpos($tmp_Xzone,",",0))."',
            apply_source_formatting : true,
            force_br_newlines : true,
            convert_newlines_to_brs : false,
            remove_linebreaks : false,";

 $tmp.= $tiny_mce_relurl=="false" ? 'relative_urls : false,' : 'relative_urls : true,';

if ($setup[1]=='setup') {
   global $ModPath;
   if (file_exists("modules/$ModPath/tiny_mce_setup.php")) {
      $tmp.="remove_script_host : false,\n";
      include_once("modules/$ModPath/tiny_mce_setup.php");
   }
} else {
   $tmp.="remove_script_host : false\n";
}
?>
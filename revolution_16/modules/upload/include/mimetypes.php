<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2011 by Philippe Brunier                     */
/* Copyright Snipe 2003  base sources du forum w-agora de Marc Druilhe  */
/************************************************************************/
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

// display mode if displayed inline
define   ('ATT_DSP_LINK', '1');        // displays as link (icon)
define   ('ATT_DSP_IMG', '2');         // display inline as a picture, using <img> tag.
define   ('ATT_DSP_HTML', '3');        // display inline as HTML, e.g. banned tags are stripped.
define   ('ATT_DSP_PLAINTEXT', '4');   // display inline as text, using <pre> tag.
define   ('ATT_DSP_SWF', '5');         // Embedded Macromedia Shockwave Flash

$mimetypes = array (
  'avi'   => 'video/x-msvideo',
  'bat'   => 'text/plain',
  'bak'   => 'text/plain',
  'bmp'   => 'image/bmp',
  'gif'   => 'image/gif',
  'gz'    => 'application/x-gzip',
  'htm'   => 'text/html',
  'html'  => 'text/html',
  'php'   => 'text/source',
  'conf'  => 'text/source',
  'js'    => 'text/source',
  'jpe'   => 'image/jpeg',
  'jpg'   => 'image/jpeg',
  'jpeg'  => 'image/jpeg',
  'mov'   => 'video/quicktime',
  'mpe'   => 'video/mpeg',
  'mpeg'  => 'video/mpeg',
  'mpg'   => 'video/mpeg',
  'mpga'  => 'audio/mpeg',
  'mp2'   => 'audio/mpeg',
  'mp3'   => 'audio/mpeg',
  'pdf'   => 'application/pdf',
  'png'   => 'image/png',
  'qt'    => 'video/quicktime',
  'rtf'   => 'text/rtf',
  'swf'   => 'application/x-shockwave-flash',
  'tar'   => 'application/x-tar',
  'tgz'   => 'application/x-gzip',
  'tif'   => 'image/tiff',
  'tiff'  => 'image/tiff',

  'txt'   => 'text/plain',
  'doc'   => 'application/msword',
  'ppt'   => 'application/vnd.ms-powerpoint',
  'xls'   => 'application/vnd.ms-excel',
  'xml'   => 'text/xml',
  'sxw'   => 'application/vnd.sun.xml.writer',
  'sxc'   => 'application/vnd.sun.xml.calc',
  'sxi'   => 'application/vnd.sun.xml.impress',
  'sxd'   => 'application/vnd.sun.xml.draw',
  'sxm'   => 'application/vnd.sun.xml.math',

  'zip'   => 'application/zip'
);

// mime type to be used if no other type known
// Do not modify without need!
$mimetype_default = 'application/octet-stream';
$mime_dspinl[$mimetype_default] = 'O';
$mime_dspfmt[$mimetype_default] = ATT_DSP_LINK;


// display mode if displayed inline
$mime_dspfmt['image/gif'] = ATT_DSP_IMG;
$mime_dspfmt['image/bmp'] = ATT_DSP_LINK;
$mime_dspfmt['image/png'] = ATT_DSP_IMG;
$mime_dspfmt['image/x-png'] = ATT_DSP_IMG;
$mime_dspfmt['image/jpeg'] = ATT_DSP_IMG;
$mime_dspfmt['image/pjpeg'] = ATT_DSP_IMG;
$mime_dspfmt['text/html'] = ATT_DSP_HTML;
$mime_dspfmt['text/plain'] = ATT_DSP_PLAINTEXT;
$mime_dspfmt['application/x-shockwave-flash'] = ATT_DSP_SWF;

// attachement

$mime_renderers[ATT_DSP_PLAINTEXT] = "<div class=\"list-group-item\"><div align=\"center\" style=\"background-color: #cccccc;\">\$att_name\$visible_wrn</div><pre>\$att_contents</pre></div>";
//$mime_renderers[ATT_DSP_PLAINTEXT] = "<table border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\"><tr><td style=\"background-color: #000000;\"><table border=\"0\" cellpadding=\"5\" cellspacing=\"1\" width=\"100%\"><tr><td align=\"center\" style=\"background-color: #cccccc;\">\$att_name\$visible_wrn</td></tr><tr><td style=\"background-color: #ffffff;\"><pre>\$att_contents</pre></td></tr></table></td></tr></table>";

$mime_renderers[ATT_DSP_HTML]      = "<table border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\"><tr><td style=\"background-color: #000000;\"><table border=\"0\" cellpadding=\"5\" cellspacing=\"1\" width=\"100%\"><tr><td align=\"center\" style=\"background-color: #cccccc;\">\$att_name\$visible_wrn</td></tr><tr><td style=\"background-color: #ffffff;\">\$att_contents</td></tr></table></td></tr></table>";
$mime_renderers[ATT_DSP_LINK]      = "
<a class=\"list-group-item\" href=\"\$att_url\" target=\"_blank\" >
\$att_icon<span title=\"".upload_translate("Télécharg.")." \$att_name (\$att_type - \$att_size)\" data-toggle=\"tooltip\" style=\"font-size: .85rem;\"><strong>&nbsp;\$att_name</strong></span><span class=\"label label-default label-pill pull-right\" style=\"font-size: .75rem;\">\$compteur &nbsp;<i class=\"fa fa-lg fa-download\"></i></span><br /><span align=\"center\">\$visible_wrn</span></a>";
$mime_renderers[ATT_DSP_IMG] = "<a class=\"list-group-item\" href=\"javascript:void(0);\" onclick=\"window.open('\$att_url','fullsizeimg','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=600,width=800,toolbar=no,scrollbars=yes,resizable=yes');\"><img src=\"\$att_url\" alt=\"\$att_name\" border=\"0\" \$img_size />\$visible_wrn </a>";
$mime_renderers[ATT_DSP_SWF] = "<p align=\"center\"><object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=4\,0\,2\,0\" \$img_size><param name=\"quality\" value=\"high\"><param name=\"SRC\" value=\"\$att_url\"><embed src=\"\$att_url\" quality=\"high\" pluginspage=\"http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash\" type=\"application/x-shockwave-flash\" \$img_size></embed></object>\$visible_wrn</p>";


// images
$att_icons="";
$handle=opendir("images/upload/file_types");
while (false!==($file = readdir($handle))) {
   if ($file!="." && $file!="..")  {
      $prefix = strtoLower(substr($file,0,strpos($file,'.')));
      $att_icons[$prefix]="<img src=\"images/upload/file_types/".$file."\" border=\"0\" align=\"center\" alt=\"\" />";

      $att_icons[$prefix]='
      <span class="fa-stack">
  <i class="fa fa-file fa-stack-2x"></i>
  <span class="fa-stack-1x filetype-text">'.$prefix.'</span>
</span>';

   }
}
closedir($handle);

$att_icon_default="<img src=\"images/upload/file_types/unknown.gif\" border=\"0\" align=\"center\" alt=\"\" />";
$att_icon_multiple="<img src=\"images/upload/file_types/multiple.gif\" border=\"0\" align=\"center\" alt=\"\" />";
$att_icon_dir="<img src=\"images/upload/file_types/dir.gif\" border=\"0\" align=\"center\" alt=\"\" />";
?>
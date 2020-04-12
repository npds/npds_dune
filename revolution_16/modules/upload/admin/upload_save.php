<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2020 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

function uploadSave($xmax_size, $xdocumentroot, $xautorise_upload_p, $xracine, $xrep_upload, $xrep_cache, $xrep_log, $xurl_upload, $xurl_upload_css, $xed_profil, $xed_nb_images, $xextension_autorise, $xwidth_max, $xheight_max, $xquota) {
   $file = file("upload.conf.php");
   $file[16] = '\$max_size = '.$xmax_size.';'."\n";
   $file[21] = '\$DOCUMENTROOT = "'.$xdocumentroot.'";'."\n";
   $file[24] = '\$autorise_upload_p = "'.$xautorise_upload_p.'";'."\n";
   $file[28] = '\$racine = "'.$xracine.'";'."\n";
   $file[31] = '\$rep_upload = \$racine."'.$xrep_upload.'";'."\n";
   $file[34] = '\$rep_cache = \$racine."'.$xrep_cache.'";'."\n";
   $file[37] = '\$rep_log = \$racine."'.$xrep_log.'";'."\n";
   $file[40] = '\$url_upload = '.$xurl_upload.';'."\n";
   $file[57] = '\$url_upload_css = "'.$xurl_upload_css.'";'."\n";
   $file[67] = '\$ed_profil = "'.$xed_profil.'";'."\n";
   $file[70] = '\$ed_nb_images = '.$xed_nb_images.';'."\n";
   $file[73] = '\$extension_autorise = "'.$xextension_autorise.'";'."\n";
   $file[76] = '\$width_max = '.$xwidth_max.';'."\n";
   $file[77] = '\$height_max = '.$xheight_max.';'."\n";
   $file[80] = '\$quota = '.$xquota.';'."\n";

   $fic = fopen("upload.conf.php", "w");
   foreach($file as $n => $ligne) {
      fwrite($fic, $ligne);
   }
   fclose($fic);
   Header("Location: admin.php?op=AdminMain");
}
?>
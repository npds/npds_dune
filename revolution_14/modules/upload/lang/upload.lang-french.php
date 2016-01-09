<?php
/************************************************************************/
/* This version name NPDS Copyright (c) 2001-2015 by Philippe Brunier   */
/* ===========================                                          */
/*                                                                      */
/* UPLOAD Language File                                                 */
/*                                                                      */
/************************************************************************/

function upload_translate($phrase) {
//  if (cur_charset=="utf-8") {
//     return utf8_encode($phrase);
//  } else {
//     return ($phrase);
//  }
//     return ($phrase);
      $tmp = $phrase;


   return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401));

}
?>
<?php
/************************************************************************/
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/*                                                                      */
/* UPLOAD Language File                                                 */
/*                                                                      */
/************************************************************************/

function upload_translate($phrase) {
   $tmp = $phrase;
   return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>
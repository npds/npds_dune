<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2008 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/*                                                                      */
/* Module video_yt                                                      */
/* video_yt.lang-french Language file 2007 by jpb                      */
/* Translated by : Jean Pierre Barbary                                  */
/* version 1.2 06/08                                                    */
/************************************************************************/

function video_yt_translate($phrase) {
if (cur_charset=="utf-8") {
    return utf8_encode($phrase);
 } else {
    return ($phrase);
 }
}
?>
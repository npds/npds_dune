<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/*                                                                      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2019 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/*                                                                      */
/* module geoloc version 3.0                                            */
/* geoloc_geoloc.php file 2008-2019 by Jean Pierre Barbary (jpb)        */
/* dev team : Philippe Revilliod (phr)                                  */
/************************************************************************/

//function wp_fill() {
    $wp_jso = $_POST['wp_json'];
    $wp_use = $_POST['wp_user'];
    $file = fopen($wp_use.'_wp.json','w');
    fwrite($file, $wp_jso);
    fclose($file);

//echo 'debug';
//}
?>
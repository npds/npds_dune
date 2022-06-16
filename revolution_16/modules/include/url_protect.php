<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2022 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

/************************************************************************/
// Dont modified these lines if you dont know exactly what you have to do
/************************************************************************/

$bad_uri_content=array(
   // To Filter "php WebWorm" and like Santy and other
   "perl",
   "chr(",

   // To prevent SQL-injection
   " union ",
   " into ",
   " select ",
   " update ",
   " from ",
   " where ",
   " insert ",
   " drop ",
   " delete ",
   // Comment inline SQL - shiney 2011
   "/*",

   // To prevent XSS
   "outfile",
   "/script",
   "url(",
   "/object",
   "img dynsrc",
   "img lowsrc",
   "/applet",
   "/style",
   "/iframe",
   "/frameset",
   "document.cookie",
   "document.location",
   "msgbox(",
   "alert(",
   "expression(",
   // some HTML5 tags - dev 2012
   "formaction",
   "autofocus",
   "onforminput",
   "onformchange",
   "history.pushstate("
);
$bad_uri_key = array_keys($_SERVER);
$bad_uri_name = array('GLOBALS','_SERVER','_REQUEST','_GET','_POST','_FILES','_ENV','_COOKIE','_SESSION');
$badname_in_uri = array_intersect(array_keys($_GET),$bad_uri_name);
?>
<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/*                                                                      */
/* NPDS Copyright (c) 2001-2015 by Philippe Brunier                     */
/* =========================                                            */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
  $Titlesitename="NPDS";
  if (file_exists("meta/meta.php"))
     include ("meta/meta.php");
  if (file_exists("meta/cur_charset.php"))
     include ("meta/cur_charset.php");
  echo "</head><body style=\"background-color: #FFFFFF;\"><br /><p align=\"center\"><span style=\"font-size: 14px; font-family: Arial; font-weight: bold; color: red;\">";
  echo "Access Denied / Acc&egrave;s Refus&eacute;";
  if (cur_charset=="utf-8") echo" / &#x901A;&#x5165;&#x88AB;&#x5426;&#x8BA4;";
  echo "</span>";
  echo "<br /><br /><span style=\"font-size: 12px; font-family: Arial; font-weight: bold; color: black;\">NPDS - Portal System";
  echo "</span></p></body></html>";
  die();
?>
<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2015 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
/* In this file you can include script or HTML just after the           */
/* SEARCH BOX in the Main View off the Web-links for extra functions    */
/* or for advertising. Find No ?                                        */
/************************************************************************/

// Le système de bannière
   global $banners;
   if (($banners) and function_exists("viewbanner")) {
      echo '<p align="center">';
         viewbanner();
      echo '</p>';
   }
?>
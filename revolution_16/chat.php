<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
// Pour le lancement du Chat : chat.php?id=gp_id&auto=token_de_securite
// gp_id=ID du groupe au sens NPDS du terme => 0 : tous / -127 : Admin / -1 : Anonyme / 1 : membre / 2 ... 126 : groupe de membre
// token_de_securite = encrypt(serialize(gp_id)) => Permet d'éviter le lancement du Chat sans autorisation

   if (!defined('NPDS_GRAB_GLOBALS_INCLUDED'))
      include("grab_globals.php");

   $Titlesitename='NPDS';
   $nuke_url='';
   $meta_op='';
   $meta_doctype="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Frameset///EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd\">\n<html xmlns=\"http://www.w3.org/1999/xhtml\">";
   include("meta/meta.php");
   echo '
         <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
      </head>
      <frameset rows="1%,60%,200">
         <frame src="chatrafraich.php?repere=0&amp;aff_entetes=1&amp;connectes=-1&amp;id=$id&amp;auto='.$auto.'" frameborder="0" scrolling="no" noresize="noresize" name="rafraich">
         <frame src="chattop.php" frameborder="0" scrolling="yes" noresize="noresize" name="haut">
         <frame src="chatinput.php?id=$id&amp;auto='.$auto.'" frameborder="0" scrolling="no" noresize="noresize" name="bas">
      </frameset>
   </html>';
?>
<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
// Pour le lancement du Chat : chat.php?id=gp_id&auto=token_de_securite
// gp_id=ID du groupe au sens NPDS du terme => 0 : tous / -127 : Admin / -1 : Anonyme / 1 : membre / 2 ... 126 : groupe de membre
// token_de_securite = encrypt(serialize(gp_id)) => Permet d'éviter le lancement du Chat sans autorisation

   //if (!defined('NPDS_GRAB_GLOBALS_INCLUDED'))
   //   include("grab_globals.php");

if (!function_exists("Mysql_Connexion"))
   include ("mainfile.php");

   $Titlesitename='NPDS';
   $meta_op='';
   $meta_doctype='<!DOCTYPE html>';
   include("meta/meta.php");
   echo '
         <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
      </head>  
         <div style="height:1vh;" class=""><iframe src="chatrafraich.php?repere=0&amp;aff_entetes=1&amp;connectes=-1&amp;id='.$id.'&amp;auto='.$auto.'" frameborder="0" scrolling="no" noresize="noresize" name="rafraich" width="100%" height="100%"></iframe></div>
         <div style="height:58vh;" class=""><iframe src="chattop.php" frameborder="0" scrolling="yes" noresize="noresize" name="haut" width="100%" height="100%"></iframe></div>
         <div style="height:39vh;" class=""><iframe src="chatinput.php?id='.$id.'&amp;auto='.$auto.'" frameborder="0" scrolling="yes" noresize="noresize" name="bas" width="100%" height="100%"></iframe></div>
   </html>';

?>
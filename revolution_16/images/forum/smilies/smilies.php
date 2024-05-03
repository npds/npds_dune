<?php
/************************************************************************/
/* NPDS V : Net Portal Dynamic System                                   */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2019 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

   // Une ligne pour chaque smilie, la dernière ligne ne devant pas comporter de , à la fin du tableau
   // colonne 1 : le code du smiliey
   // colonne 2 : l'icône
   // colonne 3 : si nécessaire la description (sinon "")
   // colonne 4 : visible (1) ou invisible (0)

   $smilies=array(
   array(":-)","icon_smile.gif","",1),
   array(":-]","icon_smile.gif","",0),
   array(";-)","icon_wink.gif","",1),
   array(";-]","icon_wink.gif","",0),
   array(":-P","icon_razz.gif","",1),
   array("8-)","icon_cool.gif","",1),
   array("8-]","icon_cool.gif","",0),
   array(":-D","icon_biggrin.gif","",1),
   array(":=!","yaisse.gif","",1),
   array(":b","icon_tongue.gif","",1),
   array(":D","icon_grin.gif","",1),
   array(":#","icon_ohwell.gif","",1),
   array(":-o","icon_eek.gif","",1),
   array(":-?","icon_confused.gif","",1),
   array(":-(","icon_frown.gif","",1),
   array(":-[","icon_frown.gif","",0),
   array(":|","icon_mad2.gif","",1),
   array(":-|","icon_mad.gif","",1),
   array(":paf","pafmur.gif","Mais c'est pas possible !",1)
   );
?>
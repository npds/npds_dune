<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2020 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion"))
   header("location: index.php");

$rowQ1=Q_Select ("SELECT * FROM ".$NPDS_Prefix."config", 3600);
if ($rowQ1) {
   foreach($rowQ1[0] as $key=>$value) {
      $$key = $value; 
   }
   $upload_table = $NPDS_Prefix.$upload_table;
}
settype($forum,'integer');
if ($allow_upload_forum) {
   $rowQ1=Q_Select ("SELECT attachement FROM ".$NPDS_Prefix."forums WHERE forum_id='$forum'", 3600);
   if ($rowQ1) {
      foreach($rowQ1[0] as $value) {
         $allow_upload_forum=$value;
      }
   }
}

$rowQ1=Q_Select ("SELECT forum_pass FROM ".$NPDS_Prefix."forums WHERE forum_id='$forum' AND forum_type='1'", 3600);
if ($rowQ1) {
   if (isset($Forum_Priv[$forum])) {
      $Xpasswd=base64_decode($Forum_Priv[$forum]);
      foreach($rowQ1[0] as $value) {
         $forum_xpass=$value;
      }

      if (md5($forum_xpass)==$Xpasswd)
         $Forum_passwd=$forum_xpass;
      else
         setcookie("Forum_Priv[$forum]",'',0);
   } else {
      if (isset($Forum_passwd)) {
         foreach($rowQ1[0] as $value) {
         if ($value==$Forum_passwd)
           setcookie("Forum_Priv[$forum]",base64_encode(md5($Forum_passwd)),time()+900);
         }
      }
   }
}
?>

<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2023 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion"))
   include ("mainfile.php");

include("powerpack_f.php");
global $powerpack; $powerpack=true;

settype($op,'string');
switch ($op) {
   // Instant Members Message
   case 'instant_message':
      Form_instant_message($to_userid);
   break;
   case 'write_instant_message':
      settype($copie,'string');
      settype($messages,'string');
         if (isset($user)) {
            $rowQ1=Q_Select("SELECT uid FROM ".$NPDS_Prefix."users WHERE uname='$cookie[1]'", 3600);
            $uid = $rowQ1[0];
            $from_userid=$uid['uid'];
            if (($subject!='') or ($message!='')) {
               $subject=FixQuotes($subject).'';
               $messages=FixQuotes($messages).'';
               writeDB_private_message($to_userid,'',$subject,$from_userid,$message,$copie);
            }
         }
      Header("Location: index.php");
   break;
   // Instant Members Message
   // Purge Chat Box
   case 'admin_chatbox_write':
      if ($admin) {
         $adminX = base64_decode($admin);
         $adminR = explode(':', $adminX);
         $Q = sql_fetch_assoc(sql_query("SELECT * FROM ".$NPDS_Prefix."authors WHERE aid='$adminR[0]' LIMIT 1"));
         if ($Q['radminsuper']==1)
            if ($chatbox_clearDB=='OK')
               sql_query("DELETE FROM ".$NPDS_Prefix."chatbox WHERE date <= ".(time()-(60*5))."");
      }
      Header("Location: index.php");
   break;
   // Purge Chat Box
}
?>
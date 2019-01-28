<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Cluster Paradise - Manage Data-Cluster  / Mod by Tribal-Dolphin      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2019 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
function V_secur_cluster($Xkey) {
   global $ModPath;
   $ModPath=str_replace('..','',$ModPath);
   $trouve=false;
   if (file_exists("modules/$ModPath/data-cluster-E.php")) {
      include("modules/$ModPath/data-cluster-E.php");
      $cpt=0;
      while (each($part) and !$trouve) {
         if (md5($part[$cpt]["WWW"].$part[$cpt]["KEY"])==decryptK($Xkey,$part[$cpt]["KEY"]))
            $trouve=true;
         else
            $cpt=$cpt+1;
      }
   }
   if ($trouve)
      return ($part[$cpt]);
   else
      return (false);
}

if ($tmp=V_secur_cluster($key)) {
   if (($Xop=="NEWS") and ($tmp['SUBSCRIBE']=="NEWS") and ($tmp['OP']=="IMPORT")) {
      // vérifie que le membre existe bien sur le site
      $author=decryptK(removeHack($Xauthor),$tmp['KEY']);
      $result = sql_query("SELECT name FROM ".$NPDS_Prefix."users WHERE uname='$author'");
      list($name) = sql_fetch_row($result);
      if ($name==$author) {$pasfinA=true;} else {$pasfinA=false;}

      // vérifie que le l'auteur existe bien et ne dispose que des droits minimum
      $aid=decryptK(removeHack($Xaid),$tmp['KEY']);
      $result = sql_query("SELECT radminarticle FROM ".$NPDS_Prefix."authors WHERE aid='$aid'");
      list($radminarticle) = sql_fetch_row($result);
      if ($radminarticle==1) {$pasfinB=true;} else {$pasfinB=false;}

      // vérifie que la catégorie existe : sinon met la catégorie générique
      $catid=decryptK(removeHack($Xcatid),$tmp['KEY']);
      $result = sql_query("SELECT catid FROM ".$NPDS_Prefix."stories_cat WHERE title='".addslashes($catid)."'");
      list($catid) = sql_fetch_row($result);

     // vérifie que le Topic existe : sinon met le Topic générique
      $topic=decryptK(removeHack($Xtopic),$tmp['KEY']);
      $result = sql_query("SELECT topicid FROM ".$NPDS_Prefix."topics WHERE topictext='".addslashes($topic)."'");
      list($topicid) = sql_fetch_row($result);

      // OK on fait la mise à jour
      if ($pasfinA and $pasfinB) {
         $subject=decryptK(removeHack($Xsubject),$tmp['KEY']);
         $hometext=decryptK(removeHack($Xhometext),$tmp['KEY']);
         $bodytext=decryptK(removeHack($Xbodytext),$tmp['KEY']);
         $notes=decryptK(removeHack($Xnotes),$tmp['KEY']);
         $ihome=decryptK(removeHack($Xihome),$tmp['KEY']);
         $date_finval=decryptK(removeHack($Xdate_finval),$tmp['KEY']);
         $epur=decryptK(removeHack($Xepur),$tmp['KEY']);

         // autonews ou pas ?
         $date_debval=decryptK(removeHack($Xdate_debval),$tmp['KEY']);
         if ($date_debval=='') {
            $result = sql_query("INSERT INTO ".$NPDS_Prefix."stories VALUES (NULL, '$catid', '$aid', '$subject', now(), '$hometext', '$bodytext', '0', '0', '$topicid', '$author', '$notes', '$ihome', '0', '$date_finval','$epur')");
            Ecr_Log("security", "Cluster Paradise : insert_stories ($subject - $date_finval) by AID : $aid", "");
            // Réseaux sociaux
            if (file_exists('modules/npds_twi/npds_to_twi.php')) {include ('modules/npds_twi/npds_to_twi.php');}
            if (file_exists('modules/npds_fbk/npds_to_fbk.php')) {include ('modules/npds_twi/npds_to_fbk.php');}
            // Réseaux sociaux
         } else {
            $result = sql_query("INSERT INTO ".$NPDS_Prefix."autonews VALUES (NULL, '$catid', '$aid', '$subject', now(), '$hometext', '$bodytext', '$topicid', '$author', '$notes', '$ihome','$date_debval','$date_finval','$epur')");
            Ecr_Log("security", "Cluster Paradise : insert_autonews ($subject - $date_debval - $date_finval) by AID : $aid", "");
         }

         sql_query("UPDATE ".$NPDS_Prefix."users SET counter=counter+1 WHERE uname='$author'");
         sql_query("UPDATE ".$NPDS_Prefix."authors SET counter=counter+1 WHERE aid='$aid'");
      }
   }
}

echo '
   <script type="text/javascript">
     //<![CDATA[
        self.close();
     //]]>
   </script>';
 ?>

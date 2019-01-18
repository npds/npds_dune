<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/* Based on Parts of phpBB                                              */
/*                                                                      */
/* NPDS Copyright (c) 2002-2019 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion"))
   include ("mainfile.php");

   if ($SuperCache)
      $cache_obj = new cacheManager();
   else
      $cache_obj = new SuperCacheEmpty();
   settype($op,'string');
   settype($Subforumid,'array');
   if ($op=="maj_subscribe") {
      if ($user) {
         settype($cookie[0],"integer");
         $result = sql_query("DELETE FROM ".$NPDS_Prefix."subscribe WHERE uid='$cookie[0]' AND forumid!='NULL'");
         $result = sql_query("SELECT forum_id FROM ".$NPDS_Prefix."forums ORDER BY forum_index,forum_id");
         while(list($forumid) = sql_fetch_row($result)) {
            if (is_array($Subforumid)) {
               if (array_key_exists($forumid,$Subforumid)) {
                  $resultX = sql_query("INSERT INTO ".$NPDS_Prefix."subscribe (forumid, uid) VALUES ('$forumid','$cookie[0]')");
               }
            }
         }
      }
   }

   include("header.php");
   // -- SuperCache
   if (($SuperCache) and (!$user)) {
      $cache_obj->startCachingPage();
   }

   if (($cache_obj->genereting_output==1) or ($cache_obj->genereting_output==-1) or (!$SuperCache) or ($user)) {
      $inclusion=false;
      settype($catid, 'integer');
      if ($catid!='') {
         if (file_exists("themes/$theme/html/forum-cat$catid.html")) {
            $inclusion="themes/$theme/html/forum-cat$catid.html";
         } elseif (file_exists("themes/default/html/forum-cat$catid.html")) {
            $inclusion="themes/default/html/forum-cat$catid.html";
         }      
      }
      if ($inclusion==false) {
         if (file_exists("themes/$theme/html/forum-adv.html")) {
            $inclusion="themes/$theme/html/forum-adv.html";
         } elseif (file_exists("themes/$theme/html/forum.html")) {
            $inclusion="themes/$theme/html/forum.html";
         } elseif (file_exists("themes/default/html/forum.html")) {
            $inclusion="themes/default/html/forum.html";
         } else {
            echo "html/forum.html / not find !<br />";
         }
      }
      if ($inclusion) {
         $Xcontent=join('',file($inclusion));
         echo meta_lang(aff_langue($Xcontent));
      }
   }

   // -- SuperCache
   if (($SuperCache) and (!$user))
      $cache_obj->endCachingPage();
   include("footer.php");
?>
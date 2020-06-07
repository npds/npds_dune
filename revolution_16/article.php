<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2020 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion"))
   include ("mainfile.php");

settype($sid, "integer");
settype($archive, "integer");
if (!isset($sid) && !isset($tid))
   header ("Location: index.php");

   if (!$archive)
      $xtab=news_aff("libre","WHERE sid='$sid'",1,1);
   else
      $xtab=news_aff("archive","WHERE sid='$sid'",1,1);

   list($sid, $catid, $aid, $title, $time, $hometext, $bodytext, $comments, $counter, $topic, $informant, $notes) = $xtab[0];
   if (!$aid)
      header ("Location: index.php");
   sql_query("UPDATE ".$NPDS_Prefix."stories SET counter=counter+1 WHERE sid='$sid'");

   include ("header.php");
   // Include cache manager
   global $SuperCache;
   if ($SuperCache) {
      $cache_obj = new cacheManager();
      $cache_obj->startCachingPage();
   } 
   else
      $cache_obj = new SuperCacheEmpty();
   if (($cache_obj->genereting_output==1) or ($cache_obj->genereting_output==-1) or (!$SuperCache)) {
      $title = aff_langue(stripslashes($title));
      $hometext = aff_code(aff_langue(stripslashes($hometext)));
      $bodytext = aff_code(aff_langue(stripslashes($bodytext)));
      $notes = aff_code(aff_langue(stripslashes($notes)));

      if ($notes!= '') $notes='<div class="note blockquote">'.translate("Note").' : '.$notes.'</div>';
      if ($bodytext == '')
         $bodytext = meta_lang($hometext.'<br />'.$notes);
      else
         $bodytext = meta_lang($hometext.'<br />'.$bodytext.'<br />'.$notes);
      if ($informant == '') $informant = $anonymous;

      getTopics($sid);

      if ($catid != 0) {
         $resultx = sql_query("SELECT title FROM ".$NPDS_Prefix."stories_cat WHERE catid='$catid'");
         list($title1) = sql_fetch_row($resultx);
         $title = '<a href="index.php?op=newindex&amp;catid='.$catid.'"><span>'.aff_langue($title1).'</span></a> : '.$title;
      }

      $boxtitle=translate("Liens relatifs");
      $boxstuff='
      <ul>';
      $result=sql_query("SELECT name, url FROM ".$NPDS_Prefix."related WHERE tid='$topic'");
      while(list($name, $url) = sql_fetch_row($result)) {
         $boxstuff .= '
         <li><a href="'.$url.'" target="new"><span>'.$name.'</span></a></li>';
      }
      $boxstuff .= '
      </ul>
      <ul>
         <li><a href="search.php?topic='.$topic.'" >'.translate("En savoir plus à propos de").' '.aff_langue($topictext).'</a></li>
         <li><a href="search.php?member='.$informant.'" >'.translate("Article de").' '.$informant.'</a></li>
      </ul>
      <div class="">'.translate("L'article le plus lu à propos de").'&nbsp;&nbsp;'.aff_langue($topictext).' :</div>';

      $xtab=news_aff("big_story","WHERE topic=$topic",0,1);
      list($topstory, $ttitle) = $xtab[0];
      $boxstuff .= '
      <ul>
         <li><a href="article.php?sid='.$topstory.'" >'.aff_langue($ttitle).'</a></li>
      </ul>
      <div class="">'.translate("Les dernières nouvelles à propos de").' '.aff_langue($topictext).' :</div>';

      if (!$archive)
         $xtab=news_aff("libre","WHERE topic=$topic AND archive='0' ORDER BY sid DESC LIMIT 0,5",0,5);
      else
         $xtab=news_aff("archive","WHERE topic=$topic AND archive='1' ORDER BY sid DESC LIMIT 0,5",0,5);

      $story_limit=0;
      $boxstuff .='
      <ul>';
      while (($story_limit<5) and ($story_limit<sizeof($xtab))) {
         list($sid1,$catid1,$aid1,$title1) = $xtab[$story_limit];
         $story_limit++;
         $title1=aff_langue(addslashes($title1));
         $boxstuff.='
         <li><a href="article.php?sid='.$sid1.'&amp;archive='.$archive.'" >'.aff_langue(stripslashes($title1)).'</a></li>';
      }
      $boxstuff .='
      </ul>
      <p align="center">
         <a href="print.php?sid='.$sid.'&amp;archive='.$archive.'" ><i class="fa fa-print fa-lg mr-1" title="'.translate("Page spéciale pour impression").'" data-toggle="tooltip"></i></a>
         <a href="friend.php?op=FriendSend&amp;sid='.$sid.'&amp;archive='.$archive.'"><i class="fa fa-lg fa-at" title="'.translate("Envoyer cet article à un ami").'" data-toggle="tooltip"></i></a>
      </p>';

      if (!$archive) {
         $previous_tab=news_aff("libre","WHERE sid<'$sid' ORDER BY sid DESC ",0,1);
         $next_tab=news_aff("libre","WHERE sid>'$sid' ORDER BY sid ASC ",0,1);
      } else {
         $previous_tab=news_aff("archive","WHERE sid<'$sid' ORDER BY sid DESC",0,1);
         $next_tab=news_aff("archive","WHERE sid>'$sid' ORDER BY sid ASC ",0,1);
      }

      if (array_key_exists(0,$previous_tab))
         list($previous_sid) = $previous_tab[0];
      else
         $previous_sid=0;

      if (array_key_exists(0,$next_tab))
         list($next_sid) = $next_tab[0];
      else
         $next_sid=0;
      themearticle($aid, $informant, $time, $title, $bodytext, $topic, $topicname, $topicimage, $topictext, $sid, $previous_sid, $next_sid, $archive);
      // theme sans le système de commentaire en meta-mot !
      if (!function_exists("Caff_pub")) {
         if (file_exists("modules/comments/article.conf.php")) {
            include ("modules/comments/article.conf.php");
            include ("modules/comments/comments.php");
         }
      }
   }
   if ($SuperCache)
      $cache_obj->endCachingPage();
   include ("footer.php");
?>
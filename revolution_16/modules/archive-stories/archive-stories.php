<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* From ALL STORIES Add-On ... ver. 1.4.1a                              */
/*                                                                      */
/* NPDS Copyright (c) 2002-2019 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (strstr($ModPath,'..') || strstr($ModStart,'..') || stristr($ModPath, 'script') || stristr($ModPath, 'cookie') || stristr($ModPath, 'iframe') || stristr($ModPath, 'applet') || stristr($ModPath, 'object') || stristr($ModPath, 'meta') || stristr($ModStart, 'script') || stristr($ModStart, 'cookie') || stristr($ModStart, 'iframe') || stristr($ModStart, 'applet') || stristr($ModStart, 'object') || stristr($ModStart, 'meta'))
   die();
if (!function_exists("Mysql_Connexion"))
   include ("mainfile.php");
   include_once('functions.php');
   include ("modules/$ModPath/archive-stories.conf.php");
   include ("modules/$ModPath/cache.timings.php");
   if (!isset($start)) $start=0;
   include("header.php");
   // Include cache manager
   if ($SuperCache) {
      $cache_obj = new cacheManager();
      $cache_obj->startCachingPage();
   } else
      $cache_obj = new SuperCacheEmpty();
   if (($cache_obj->genereting_output==1) or ($cache_obj->genereting_output==-1) or (!$SuperCache)) {
      if ($arch_titre) 
         echo $arch_titre;
      echo '
   <hr />
   <table id ="lst_art_arch" data-toggle="table"  data-striped="true" data-search="true" data-show-toggle="true" data-show-columns="true" data-mobile-responsive="true" data-icons-prefix="fa" data-buttons-class="outline-secondary" data-icons="icons">
      <thead>
         <tr>
            <th data-sortable="true" data-sorter="htmlSorter" data-halign="center" class="n-t-col-xs-4">'.translate("Articles").'</th>
            <th data-sortable="true" data-halign="center" data-align="right" class="n-t-col-xs-1">'.translate("lus").'</th>
            <th data-halign="center" data-align="right">'.translate("Posté le").'</th>
            <th data-sortable="true" data-halign="center" data-align="left">'.translate("Auteur").'</th>
            <th data-halign="center" data-align="center" class="n-t-col-xs-2">'.translate("Fonctions").'</th>
         </tr>
      </thead>
      <tbody>';

      if (!isset($count)) {
         $result0 = Q_select("SELECT COUNT(sid) AS count FROM ".$NPDS_Prefix."stories WHERE archive='$arch'",3600);
         $count= $result0[0];
         $count=$count['count'];
      }

   $nbPages = ceil($count/$maxcount);
   $current = 1;
   if ($start >= 1)
      $current=$start/$maxcount;
   else if ($start < 1)
      $current=0;
   else
      $current = $nbPages;

      if ($arch==0)
         $xtab=news_aff("libre", "WHERE archive='$arch' ORDER BY sid DESC LIMIT $start,$maxcount", $start, $maxcount);
      else
         $xtab=news_aff("archive", "WHERE archive='$arch' ORDER BY sid DESC LIMIT $start,$maxcount", $start, $maxcount);

      $ibid=0;
      $story_limit=0;

      while (($story_limit<$maxcount) and ($story_limit<sizeof($xtab))) {
         list($s_sid, $catid, $aid, $title, $time, $hometext, $bodytext, $comments, $counter, $topic, $informant) = $xtab[$story_limit];
         $story_limit++;
         if ($catid!=0)
         list($cattitle) = sql_fetch_row(sql_query("SELECT title FROM ".$NPDS_Prefix."stories_cat WHERE catid='$catid'"));
         $printP = '<a href="print.php?sid='.$s_sid.'&amp;archive='.$arch.'"><i class="fa fa-print fa-lg" title="'.translate("Page spéciale pour impression").'" data-toggle="tooltip" data-placement="left"></i></a>';
         $sendF = '<a class="ml-4" href="friend.php?op=FriendSend&amp;sid='.$s_sid.'&amp;archive='.$arch.'"><i class="fa fa-at fa-lg" title="'.translate("Envoyer cet article à un ami").'" data-toggle="tooltip" data-placement="left" ></i></a>';
         $sid = $s_sid;
         if ($catid != 0) {
            $resultm = sql_query("SELECT title FROM ".$NPDS_Prefix."stories_cat WHERE catid='$catid'");
            list($title1) = sql_fetch_row($resultm);
            $title = '<a href="article.php?sid='.$sid.'&amp;archive='.$arch.'" >'.aff_langue(ucfirst($title)).'</a> [ <a href="index.php?op=newindex&amp;catid='.$catid.'">'.aff_langue($title1).'</a> ]';
         }
         else
            $title = '<a href="article.php?sid='.$sid.'&amp;archive='.$arch.'" >'.aff_langue(ucfirst($title)).'</a>';
         setlocale (LC_TIME, aff_langue($locale));
         preg_match('#^(\d{4})-(\d{1,2})-(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$#', $time, $datetime);
         $datetime = strftime("%d-%m-%Y %H:%M:%S", mktime($datetime[4]+(integer)$gmt,$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
         if (cur_charset!="utf-8")
            $datetime = ucfirst($datetime);
         echo '
        <tr>
           <td>'.$title.'</td>
           <td>'.$counter.'</td>
           <td><small>'.$datetime.'</small></td>
           <td>'.userpopover($informant,40).' '.$informant.'</td>
           <td>'.$printP.$sendF.'</td>
        </tr>';
      }
      echo '
         </tbody>
      </table>
      <div class="d-flex my-3 justify-content-between flex-wrap">
      <ul class="pagination pagination-sm">
         <li class="page-item disabled"><a class="page-link" href="#" >'.translate("Nb. d'articles").' '.$count.' </a></li>
         <li class="page-item disabled"><a class="page-link" href="#" >'.$nbPages.' '.translate("pages").'</a></li>
      </ul>';

      echo paginate('modules.php?ModPath=archive-stories&amp;ModStart=archive-stories&amp;start=', '&amp;count='.$count, $nbPages, $current, 1, $maxcount, $start);
      echo '</div>';
   }
   if ($SuperCache)
      $cache_obj->endCachingPage();
   include("footer.php");
?>
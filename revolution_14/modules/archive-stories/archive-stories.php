<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* From ALL STORIES Add-On ... ver. 1.4.1a                              */
/*                                                                      */
/* NPDS Copyright (c) 2002-2013 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (strstr($ModPath,"..") || strstr($ModStart,"..") || stristr($ModPath, "script") || stristr($ModPath, "cookie") || stristr($ModPath, "iframe") || stristr($ModPath, "applet") || stristr($ModPath, "object") || stristr($ModPath, "meta") || stristr($ModStart, "script") || stristr($ModStart, "cookie") || stristr($ModStart, "iframe") || stristr($ModStart, "applet") || stristr($ModStart, "object") || stristr($ModStart, "meta")) {
   die();
}
if (!function_exists("Mysql_Connexion")) {
   include ("mainfile.php");
}

   include ("modules/$ModPath/archive-stories.conf.php");
   include ("modules/$ModPath/cache.timings.php");
   if (!isset($start)) {$start=0;}
   include("header.php");
   // Include cache manager
   if ($SuperCache) {
      $cache_obj = new cacheManager();
      $cache_obj->startCachingPage();
   } else {
      $cache_obj = new SuperCacheEmpty();
   }
   if (($cache_obj->genereting_output==1) or ($cache_obj->genereting_output==-1) or (!$SuperCache)) {
      opentable();
      if ($arch_titre) { echo $arch_titre; }
      echo '
   <table id ="lst_art_arch" data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-icons-prefix="fa" data-icons="icons">
      <thead>
         <tr>
            <th data-sortable="true">'.translate("Articles").'</th>
            <th data-sortable="true">'.translate("reads").'</th>
            <th>'.translate("Posted on").'</th>
            <th data-sortable="true">'.translate("Author").'</th>
            <th>&nbsp;</th>
         </tr>
      </thead>
      <tbody>';

      if (!isset($count)) {
         $result0 = Q_select("SELECT count(sid) as count FROM ".$NPDS_Prefix."stories WHERE archive='$arch'",3600);
         list(,$count)=each($result0);
         $count=$count['count'];
      }

      if ($arch==0) {
         $xtab=news_aff("libre", "WHERE archive='$arch' ORDER BY sid DESC LIMIT $start,$maxcount", $start, $maxcount);
      } else {
         $xtab=news_aff("archive", "WHERE archive='$arch' ORDER BY sid DESC LIMIT $start,$maxcount", $start, $maxcount);
      }

      $ibid=0;
      $story_limit=0;
      if ($ibid=theme_image("box/print.gif")) {$imgtmpP=$ibid;} else {$imgtmpP="images/print.gif";}
      if ($ibid=theme_image("box/friend.gif")) {$imgtmpF=$ibid;} else {$imgtmpF="images/friend.gif";}
      while (($story_limit<$maxcount) and ($story_limit<sizeof($xtab))) {
        list($s_sid, $catid, $aid, $title, $time, $hometext, $bodytext, $comments, $counter, $topic, $informant) = $xtab[$story_limit];
        $story_limit++;
        if ($catid!=0) {
            list($cattitle) = sql_fetch_row(sql_query("SELECT title FROM ".$NPDS_Prefix."stories_cat WHERE catid='$catid'"));
        }
        $printP = "<a href=\"print.php?sid=$s_sid&amp;archive=$arch\"><img src=\"".$imgtmpP."\" border=\"0\" alt=\"".translate("Printer Friendly Page")."\" /></a>&nbsp;";
        $sendF = "<a href=\"friend.php?op=FriendSend&amp;sid=$s_sid&amp;archive=$arch\"><img src=\"".$imgtmpF."\" border=\"0\" alt=\"".translate("Send this Story to a Friend")."\" /></a>";
        $sid = $s_sid;
        if ($catid != 0) {
            $resultm = sql_query("SELECT title FROM ".$NPDS_Prefix."stories_cat WHERE catid='$catid'");
            list($title1) = sql_fetch_row($resultm);
            $title = "<a href=\"article.php?sid=$sid&amp;archive=$arch\" class=\"noir\">$title</a> [ <a href=\"index.php?op=newindex&amp;catid=$catid\" class=\"ongl\">$title1</a> ]";
        }
        setlocale (LC_TIME, aff_langue($locale));
        preg_match('#^(\d{4})-(\d{1,2})-(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$#', $time, $datetime);
        $datetime = strftime("%d-%m-%Y %H:%M:%S", mktime($datetime[4]+$gmt,$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
        if (cur_charset!="utf-8") {
           $datetime = ucfirst($datetime);
        }
        echo "<tr align=\"center\">
        <td align=\"left\" width=\"50%\"><a href=\"article.php?sid=$sid&amp;archive=$arch\" class=\"noir\">".aff_langue($title)."</a></td>
        <td align=\"center\">$counter</td>
        <td align=\"center\" width=\"25%\">$datetime</td>
        <td align=\"left\"><a href=\"user.php?op=userinfo&amp;uname=$informant\" class=\"noir\">$informant</a></td>
        <td align=\"center\">$printP&nbsp;$sendF</td>
        </tr>";
      }
      echo '
         </tbody>
      </table><br />';
      $start=$start+$maxcount-1;
      if (($count-$start)>0) {
         if ($ibid=theme_image("box/right.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/download/right.gif";}
         echo "<a href=\"modules.php?ModPath=archive-stories&amp;ModStart=archive-stories&amp;start=$start&amp;count=$count\" class=\"noir\">".translate("next matches")." <img src=\"".$imgtmp."\" border=\"0\" align=\"center\" alt=\"\" /></a>";
      }
      echo "<p align=\"center\"><br />".translate("Nb of articles")." : $count - [ <a href=\"modules.php?ModPath=archive-stories&amp;ModStart=archive-stories\" class=\"noir\">".translate("Go Back")."</a> ]</p>";
      closetable();
   }
   if ($SuperCache) {
      $cache_obj->endCachingPage();
   }
   include("footer.php");
?>
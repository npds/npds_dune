<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2015 by Philippe Brunier   */
/*                                                                      */
/* New Links.php Module with SFROM extentions                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],"modules.php")) { die(); }

function NewLinksDate($selectdate) {
   global $ModPath, $ModStart, $links_DB, $admin;
   $dateDB = (date("d-M-Y", $selectdate));
   include("header.php");
   mainheader('nl');
   $filen="modules/$ModPath/links.ban_01.php";
   if (file_exists($filen)) {include($filen);}
   $newlinkDB = Date("Y-m-d", $selectdate);
   $result = sql_query("SELECT lid FROM ".$links_DB."links_links WHERE date LIKE '%$newlinkDB%'");
   $totallinks = sql_num_rows($result);
   $result=sql_query("SELECT lid, url, title, description, date, hits, topicid_card, cid, sid FROM ".$links_DB."links_links WHERE date LIKE '%$newlinkDB%' ORDER BY title ASC");
   $link_fiche_detail='';
   include_once("modules/$ModPath/links-view.php");
   include("footer.php");
}

function NewLinks($newlinkshowdays) {
   global $ModPath, $ModStart, $links_DB;
   include("header.php");
   mainheader('nl');
   $counter = 0;
   $allweeklinks = 0;
   while ($counter <= 7-1){
      $newlinkdayRaw = (time()-(86400 * $counter));
      $newlinkday = date("d-M-Y", $newlinkdayRaw);
      $newlinkView = date("F d, Y", $newlinkdayRaw);
      $newlinkDB = Date("Y-m-d", $newlinkdayRaw);
      $result = sql_query("SELECT * FROM ".$links_DB."links_links WHERE date LIKE '%$newlinkDB%'");
      $totallinks = sql_num_rows($result);
      $counter++;
      $allweeklinks = $allweeklinks + $totallinks;
   }

   $counter = 0;
   $allmonthlinks = 0;
   while ($counter <=30-1){
      $newlinkdayRaw = (time()-(86400 * $counter));
      $newlinkDB = Date("Y-m-d", $newlinkdayRaw);
      $result = sql_query("SELECT * FROM ".$links_DB."links_links WHERE date LIKE '%$newlinkDB%'");
      $totallinks = sql_num_rows($result);
      $allmonthlinks = $allmonthlinks + $totallinks;
      $counter++;
   }
   echo '
   
   <div class="card card-block">
   <h3>'.translate("New links").'</h3>
   '.translate("Total new links: Last week").' : '.$allweeklinks.' -/- '.translate("Last 30 days").' : '.$allmonthlinks;

   echo "<br />\n";

    echo "<blockquote>".translate("Show:")." [<a href=\"modules.php?ModStart=$ModStart&ModPath=$ModPath&op=NewLinks&newlinkshowdays=7\" class=\"noir\">".translate("week")."</a>, <a href=\"modules.php?ModStart=$ModStart&ModPath=$ModPath&op=NewLinks&newlinkshowdays=14\" class=\"noir\">2 ".translate("weeks")."</a>, <a href=\"modules.php?ModStart=$ModStart&ModPath=$ModPath&op=NewLinks&newlinkshowdays=30\" class=\"noir\">30 ".translate("days")."</a>]</<blockquote>";
    $counter = 0;
    $allweeklinks = 0;
    echo '
    <blockquote>
    <ul>';
   while ($counter <= $newlinkshowdays-1) {
      $newlinkdayRaw = (time()-(86400 * $counter));
      $newlinkday = date("d-M-Y", $newlinkdayRaw);
      $newlinkView = date(str_replace("%","",translate("linksdatestring")), $newlinkdayRaw);
      $newlinkDB = Date("Y-m-d", $newlinkdayRaw);
      $result = sql_query("SELECT * FROM ".$links_DB."links_links WHERE date LIKE '%$newlinkDB%'");
      $totallinks = sql_num_rows($result);
      $counter++;
      $allweeklinks = $allweeklinks + $totallinks;
      if ($totallinks>0)
      echo "<li><a href=\"modules.php?ModStart=$ModStart&ModPath=$ModPath&op=NewLinksDate&selectdate=$newlinkdayRaw\">$newlinkView</a>&nbsp( $totallinks )</li>";
   }
    echo '
    </blockquote>
    </ul>
    </div>';
   $counter = 0;
   $allmonthlinks = 0;
   include("footer.php");
}
?>
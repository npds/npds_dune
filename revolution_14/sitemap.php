<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2013 by Philippe Brunier   */
/* Based on Script for NPDS by Alexandre Pirard  / www.pascalex.net     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (stristr($_SERVER['PHP_SELF'],"sitemap.php")) { die(); }

function sitemapforum($prio) {
    global $NPDS_Prefix, $nuke_url;
    $tmp="";

    $result = sql_query("select forum_id from ".$NPDS_Prefix."forums where forum_access='0' order by forum_id");
    while (list($forum_id) = sql_fetch_row($result)) {
          // Forums
          $tmp .= "<url>\n";
          $tmp .= "<loc>$nuke_url/viewforum.php?forum=$forum_id</loc>\n";
          $tmp .= "<lastmod>".date("Y-m-d",time())."</lastmod>\n";
          $tmp .= "<changefreq>hourly</changefreq>\n";
          $tmp .= "<priority>$prio</priority>\n";
          $tmp .= "</url>\n\n";       
       $sub_result = sql_query("select topic_id, topic_time from ".$NPDS_Prefix."forumtopics where forum_id='$forum_id' and topic_status!='2' order by topic_id");
       while (list($topic_id, $topic_time) = sql_fetch_row($sub_result)) {
          // Topics
          $tmp .= "<url>\n";
          $tmp .= "<loc>$nuke_url/viewtopic.php?topic=$topic_id&amp;forum=$forum_id</loc>\n";
          $tmp .= "<lastmod>".substr($topic_time,0,10)."</lastmod>\n";
          $tmp .= "<changefreq>hourly</changefreq>\n";
          $tmp .= "<priority>$prio</priority>\n";
          $tmp .= "</url>\n\n";
       }
    }
    return($tmp);
}

function sitemaparticle($prio) {
    global $NPDS_Prefix, $nuke_url;
    $tmp="";
    
    $result = sql_query("select sid,time from ".$NPDS_Prefix."stories where ihome='0' and archive='0' order by sid");
    while (list($sid, $time) = sql_fetch_row($result)) {
       // Articles
       $tmp .= "<url>\n";
       $tmp .= "<loc>$nuke_url/article.php?sid=$sid</loc>\n";
       $tmp .= "<lastmod>".substr($time,0,10)."</lastmod>\n";
       $tmp .= "<changefreq>daily</changefreq>\n";
       $tmp .= "<priority>$prio</priority>\n";
       $tmp .= "</url>\n\n";
    }
    return ($tmp);
}

function sitemaprub($prio) {
    global $NPDS_Prefix, $nuke_url;
    $tmp="";

    // Sommaire des rubriques
    $tmp .= "<url>\n";
    $tmp .= "<loc>$nuke_url/sections.php</loc>\n";
    $tmp .= "<lastmod>".date("Y-m-d",time())."</lastmod>\n";
    $tmp .= "<changefreq>weekly</changefreq>\n";
    $tmp .= "<priority>$prio</priority>\n";
    $tmp .= "</url>\n\n";
    
    $result = sql_query("select artid, timestamp from ".$NPDS_Prefix."seccont where userlevel='0' order by artid");
    while (list($artid, $timestamp) = sql_fetch_row($result)) {   
       // Rubriques
       $tmp .= "<url>\n";
       $tmp .= "<loc>$nuke_url/sections.php?op=viewarticle&amp;artid=$artid</loc>\n";
       $tmp .= "<lastmod>".date("Y-m-d",$timestamp)."</lastmod>\n";
       $tmp .= "<changefreq>weekly</changefreq>\n";
       $tmp .= "<priority>$prio</priority>\n";
       $tmp .= "</url>\n\n";
    }
    return ($tmp);
}

function sitemapdown($prio) {
    global $NPDS_Prefix, $nuke_url;
    $tmp="";

    // Sommaire des downloads
    $tmp .= "<url>\n";
    $tmp .= "<loc>$nuke_url/download.php</loc>\n";
    $tmp .= "<lastmod>".date("Y-m-d",time())."</lastmod>\n";
    $tmp .= "<changefreq>weekly</changefreq>\n";
    $tmp .= "<priority>$prio</priority>\n";
    $tmp .= "</url>\n\n";

    $result = sql_query("select did, ddate from ".$NPDS_Prefix."downloads where perms='0' order by did");
    while (list($did, $ddate) = sql_fetch_row($result)) {
       $tmp .= "<url>\n";
       $tmp .= "<loc>$nuke_url/download.php?op=geninfo&amp;did=$did</loc>\n";
       $tmp .= "<lastmod>$ddate</lastmod>\n";
       $tmp .= "<changefreq>weekly</changefreq>\n";
       $tmp .= "<priority>$prio</priority>\n";
       $tmp .= "</url>\n\n";
    }
    return ($tmp);
}

function sitemapothers($PAGES) {
   global $nuke_url;
   $tmp="";   
   while (list($name,$loc)= each($PAGES)) {
      if (array_key_exists('sitemap',$PAGES[$name])) {
         if (($PAGES[$name]['run']=="yes") and ($name!="article.php") and ($name!="forum.php") and ($name!="sections.php") and ($name!="download.php")) {
            $tmp .= "<url>\n";
            $tmp .= "<loc>$nuke_url/".str_replace("&","&amp;",$name)."</loc>\n";
            $tmp .= "<lastmod>".date("Y-m-d",time())."</lastmod>\n";
            $tmp .= "<changefreq>daily</changefreq>\n";
            $tmp .= "<priority>".$PAGES[$name]['sitemap']."</priority>\n";
            $tmp .= "</url>\n\n";
         }
      }
   }   
   return ($tmp);
}

function sitemap_create($PAGES, $filename) {

   $ibid  ="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
   $ibid .= "<urlset\n";
   $ibid .= "xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n";
   $ibid .= "xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n";
   $ibid .= "xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\n http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n\n";
   
   if (array_key_exists('sitemap',$PAGES['article.php']))
      $ibid.=sitemaparticle($PAGES['article.php']['sitemap']);

   if (array_key_exists('sitemap',$PAGES['forum.php']))
      $ibid.=sitemapforum($PAGES['forum.php']['sitemap']);

   if (array_key_exists('sitemap',$PAGES['sections.php']))
      $ibid.=sitemaprub($PAGES['sections.php']['sitemap']);
      
   if (array_key_exists('sitemap',$PAGES['download.php']))
      $ibid.=sitemapdown($PAGES['download.php']['sitemap']);

   $ibid.=sitemapothers($PAGES);
   $ibid.="</urlset>";

   $file=fopen($filename, "w");
   fwrite($file, $ibid);
   fclose($file);
    
   Ecr_Log("sitemap", "sitemap generated : ".date("H:i:s", time()), "");
}

/* -----------------------------------------*/
// http://www.example.com/cache/sitemap.xml 
$filename="cache/sitemap.xml";
// delais = 6 heures (21600 secondes)
$refresh=21600;

if (file_exists($filename)) {
   if (time()-filemtime($filename)-$refresh > 0) {
      sitemap_create($PAGES, $filename);   
   }
} else {
   sitemap_create($PAGES, $filename);   
}
?>
<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2011 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion")) {
   include ("mainfile.php");
}

function mapsections() {
    global $NPDS_Prefix;
    $tmp="";
    $result=sql_query("select rubid, rubname from ".$NPDS_Prefix."rubriques where enligne='1' and rubname<>'Divers' and rubname<>'Presse-papiers' order by ordre");
    if (sql_num_rows($result) > 0) {
       while (list($rubid, $rubname) = sql_fetch_row($result)) {
          if ($rubname!="")
             $tmp.="<li>".aff_langue($rubname)."";
          $result2 = sql_query("SELECT secid, secname, image, userlevel, intro FROM ".$NPDS_Prefix."sections WHERE rubid='$rubid' and (userlevel='0' or userlevel='') order by ordre");
          if (sql_num_rows($result2) > 0) {
             while (list($secid, $secname, $userlevel) = sql_fetch_row($result2)) {
                if (autorisation($userlevel)) {
                   $tmp.="<ul><li>".aff_langue($secname);
                   $result3 = sql_query("select artid, title from ".$NPDS_Prefix."seccont where secid='$secid'");
                   while (list($artid, $title) = sql_fetch_row($result3)) {
                      $tmp.="<ul><li><a href=\"sections.php?op=viewarticle&amp;artid=$artid\">".aff_langue($title).'</a></li></ul>';
                   }
                   $tmp.='</li></ul>';
                }
             }	 
          }
          $tmp.='</li>';		  
       }
    }
    if ($tmp!="")
		echo '<div class="row">
				<div class="col-md-12">';
		echo '<a class="btn btn-block btn-lg btn-default" data-toggle="collapse" href="#collapseSections" aria-expanded="false" aria-controls="collapseSections">
				<i class="fa fa-arrow-down"></i>&nbsp;'.translate("Sections").'
			</a></div></div>
			<div class="collapse" id="collapseSections">
				<div class="well">
					<ul>'.$tmp.'</ul>
				</div>
			</div>';   

    sql_free_result($result);
    sql_free_result($result2);
    sql_free_result($result3);
}

function mapforum() {
    $tmp="";
    $tmp.=RecentForumPosts_fab("", 10, 0, false, 50, false, "<li>", false);
    if ($tmp!="")
		echo '<div class="row">
				<div class="col-md-12">';		
		echo '<a class="btn btn-block btn-lg btn-default" data-toggle="collapse" href="#collapseForums" aria-expanded="false" aria-controls="collapseForums">
				<i class="fa fa-arrow-down"></i>&nbsp;'.translate("Forums!").'
			</a></div></div>
			<div class="collapse" id="collapseForums">
				<div class="well">
					'.$tmp.'
				</div>
			</div>';
}

function maptopics() {
    global $NPDS_Prefix;

    $lis_top="";
    $result = sql_query("select topicid, topictext ".$NPDS_Prefix."from topics order by topicname");
    while (list($topicid, $topictext) = sql_fetch_row($result)) {
       $result2 = sql_query("select sid from ".$NPDS_Prefix."stories where topic='$topicid'");
       $nb_article = sql_num_rows($result2);
       $lis_top.="<li><a href=\"search.php?query=&amp;topic=$topicid\">".aff_langue($topictext)."</a>&nbsp;(".$nb_article.")</li>\n";
    }
    if ($lis_top!="")
		echo '<div class="row">
				<div class="col-md-12">';	
		echo '<a class="btn btn-block btn-lg btn-default" data-toggle="collapse" href="#collapseTopics" aria-expanded="false" aria-controls="collapseTopics">
				<i class="fa fa-arrow-down"></i>&nbsp;'.translate("Topics").'
			</a></div></div>
			<div class="collapse" id="collapseTopics">
				<div class="well">
					<ul>'.$lis_top.'</ul>
				</div>
			</div>';
    sql_free_result($result);
    sql_free_result($result2);
}

function mapcategories() {
    global $NPDS_Prefix;

    $lis_cat="";
    $result = sql_query("SELECT catid, title FROM ".$NPDS_Prefix."stories_cat ORDER BY title");
    while (list($catid, $title) = sql_fetch_row($result)) {
       $result2 = sql_query("select sid from stories where catid='$catid'");
       $nb_article = sql_num_rows($result2);
       $lis_cat.="<li><a href=\"index.php?op=newindex&amp;catid=$catid\">".aff_langue($title)."</a> (".$nb_article.") </li>\n";
    }
    if ($lis_cat!="")
		echo '<div class="row">
				<div class="col-md-12">';
		echo '<a class="btn btn-block btn-lg btn-default" data-toggle="collapse" href="#collapseCategories" aria-expanded="false" aria-controls="collapseCategories">
				<i class="fa fa-arrow-down"></i>&nbsp;'.translate("Categories").'
			</a></div></div>
			<div class="collapse" id="collapseCategories">
				<div class="well">
					<ul>'.$lis_cat.'</ul>
				</div>
			</div>';
    sql_free_result($result);
    sql_free_result($result2);
}

function mapfaq() {
    global $NPDS_Prefix;

    $lis_faq="";
    $result = sql_query("select id_cat, categories from ".$NPDS_Prefix."faqcategories ORDER BY id_cat ASC");
    while (list($id_cat, $categories) = sql_fetch_row($result)) {
       $catname = aff_langue($categories);
       $lis_faq.="<li><a href=\"faq.php?id_cat=$id_cat&amp;myfaq=yes&amp;categories=".urlencode($catname)."\">".$catname."</a></li>\n";
    }
    if ($lis_faq!="")
		echo '<div class="row">
				<div class="col-md-12">';
		echo '<a class="btn btn-block btn-lg btn-default" data-toggle="collapse" href="#collapseFaq" aria-expanded="false" aria-controls="collapseFaq">
				<i class="fa fa-arrow-down"></i>&nbsp;'.translate("FAQ (Frequently Ask Question)").'
			</a></div></div>
			<div class="collapse" id="collapseFaq">
				<div class="well">
					<ul>'.$lis_faq.'</ul>
				</div>
			</div>';
    sql_free_result($result);
}

include ('header.php');
// Include cache manager classe
global $SuperCache;
if ($SuperCache) {
    $cache_obj = new cacheManager();
    $CACHE_TIMINGS['map.php'] = 3600;
    $CACHE_QUERYS['map.php'] = "^";
    $cache_obj->startCachingPage();
} else {
    $cache_obj = new SuperCacheEmpty();
}
if (($cache_obj->genereting_output==1) or ($cache_obj->genereting_output==-1) or (!$SuperCache)) {
    
    echo '<h2>'.translate("Site map").'</h2>';

    // Vous pouvez enlever certaines parties en mettant // devant les lignes ci-dessous
    mapsections();
    mapforum();
    maptopics();
    mapcategories();
    mapfaq();
echo '<br />';
    if (file_exists("modules/include/user.inc")) {
       include ("modules/include/user.inc");   
    }
    
}
if ($SuperCache) {
   $cache_obj->endCachingPage();
}
include "footer.php";
?>
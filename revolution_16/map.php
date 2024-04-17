<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion"))
   include ("mainfile.php");

function mapsections() {
   global $NPDS_Prefix;
   $tmp='';
   $result=sql_query("SELECT rubid, rubname FROM ".$NPDS_Prefix."rubriques WHERE enligne='1' AND rubname<>'Divers' AND rubname<>'Presse-papiers' ORDER BY ordre");
   if (sql_num_rows($result) > 0) {
      while (list($rubid, $rubname) = sql_fetch_row($result)) {
         if ($rubname!='')
            $tmp.='<li>'.aff_langue($rubname);
         $result2 = sql_query("SELECT secid, secname, image, userlevel, intro FROM ".$NPDS_Prefix."sections WHERE rubid='$rubid' AND (userlevel='0' OR userlevel='') ORDER BY ordre");
         if (sql_num_rows($result2) > 0) {
            while (list($secid, $secname, $userlevel) = sql_fetch_row($result2)) {
               if (autorisation($userlevel)) {
                  $tmp.='<ul><li>'.aff_langue($secname);
                  $result3 = sql_query("SELECT artid, title FROM ".$NPDS_Prefix."seccont WHERE secid='$secid'");
                  while (list($artid, $title) = sql_fetch_row($result3)) {
                     $tmp.="<ul>
                     <li><a href=\"sections.php?op=viewarticle&amp;artid=$artid\">".aff_langue($title).'</a></li></ul>';
                  }
                  $tmp.='</li>
                  </ul>';
               }
            }
         }
         $tmp.='</li>';
      }
   }
   if ($tmp!='')
     echo '
        <h3>
           <a class="" data-bs-toggle="collapse" href="#collapseSections" aria-expanded="false" aria-controls="collapseSections">
           <i class="toggle-icon fa fa-caret-down"></i></a>&nbsp;'.translate("Rubriques").'
           <span class="badge bg-secondary float-end">'.sql_num_rows($result).'</span>
        </h3>
     <div class="collapse" id="collapseSections">
        <div class="card card-body">
           <ul class="list-unstyled">'.$tmp.'</ul>
        </div>
     </div>
     <hr />';
   sql_free_result($result);
   if (isset($result2))
      sql_free_result($result2);
   if (isset($result3))
      sql_free_result($result3);
}

function mapforum() {
   $tmp='';
   $tmp.=RecentForumPosts_fab('', 10, 0, false, 50, false, '<li>', false);
   if ($tmp!='')
      echo '
      <h3>
         <a data-bs-toggle="collapse" href="#collapseForums" aria-expanded="false" aria-controls="collapseForums"><i class="toggle-icon fa fa-caret-down"></i></a>&nbsp;'.translate("Forums").'
      </h3>
      <div class="collapse" id="collapseForums">
         <div class="card card-body">
            '.$tmp.'
         </div>
      </div>
      <hr />';
}

function maptopics() {
   global $NPDS_Prefix;
   $lis_top='';
   $result = sql_query("SELECT topicid, topictext FROM ".$NPDS_Prefix."topics ORDER BY topicname");
   while (list($topicid, $topictext) = sql_fetch_row($result)) {
      $result2 = sql_query("SELECT sid FROM ".$NPDS_Prefix."stories WHERE topic='$topicid'");
      $nb_article = sql_num_rows($result2);
      $lis_top.='
       <li><a href="search.php?query=&amp;topic='.$topicid.'">'.aff_langue($topictext).'</a>&nbsp;<span class="">('.$nb_article.')</span></li>';
   }
   if ($lis_top!='')
      echo '
      <h3>
         <a class="" data-bs-toggle="collapse" href="#collapseTopics" aria-expanded="false" aria-controls="collapseTopics"><i class="toggle-icon fa fa-caret-down"></i></a>&nbsp;'.translate("Sujets").'
         <span class="badge bg-secondary float-end">'.sql_num_rows($result).'</span>
      </h3>
      <div class="collapse" id="collapseTopics">
         <div class="card card-body">
            <ul class="list-unstyled">'.$lis_top.'</ul>
         </div>
      </div>
      <hr />';
   sql_free_result($result);
   sql_free_result($result2);
}

function mapcategories() {
   global $NPDS_Prefix;
   $lis_cat='';
   $result = sql_query("SELECT catid, title FROM ".$NPDS_Prefix."stories_cat ORDER BY title");
   while (list($catid, $title) = sql_fetch_row($result)) {
      $result2 = sql_query("SELECT sid FROM ".$NPDS_Prefix."stories WHERE catid='$catid'");
      $nb_article = sql_num_rows($result2);
      $lis_cat.='<li><a href="index.php?op=newindex&amp;catid='.$catid.'">'.aff_langue($title).'</a> <span class="float-end badge bg-secondary"> '.$nb_article.' </span></li>'."\n";
   }
   if ($lis_cat!='')
      echo '
      <h3>
         <a class="" data-bs-toggle="collapse" href="#collapseCategories" aria-expanded="false" aria-controls="collapseCategories"><i class="toggle-icon fa fa-caret-down"></i></a>&nbsp;'.translate("Catégories").'
         <span class="badge bg-secondary float-end">'.sql_num_rows($result).'</span>
      </h3>
      <div class="collapse" id="collapseCategories">
         <div class="card card-body">
            <ul class="list-unstyled">'.$lis_cat.'</ul>
         </div>
      </div>
      <hr />';
   sql_free_result($result);
   if (isset($result2))
      sql_free_result($result2);
}

function mapfaq() {
   global $NPDS_Prefix;
   $lis_faq='';
   $result = sql_query("SELECT id_cat, categories FROM ".$NPDS_Prefix."faqcategories ORDER BY id_cat ASC");
   while (list($id_cat, $categories) = sql_fetch_row($result)) {
      $catname = aff_langue($categories);
      $lis_faq.="<li><a href=\"faq.php?id_cat=$id_cat&amp;myfaq=yes&amp;categories=".urlencode($catname)."\">".$catname."</a></li>\n";
   }
   if ($lis_faq!='')
      echo '
      <h3>
         <a class="" data-bs-toggle="collapse" href="#collapseFaq" aria-expanded="false" aria-controls="collapseFaq"><i class="toggle-icon fa fa-caret-down"></i></a>&nbsp;'.translate("FAQ - Questions fréquentes").'
         <span class="badge bg-secondary float-end">'.sql_num_rows($result).'</span>
      </h3>
      <div class="collapse" id="collapseFaq">
         <div class="card card-body">
            <ul class="">'.$lis_faq.'</ul>
         </div>
      </div>
      <hr />';
   sql_free_result($result);
}

include ('header.php');
// Include cache manager classe
global $SuperCache;
if ($SuperCache) {
   $cache_obj = new cacheManager();
   $CACHE_TIMINGS['map.php'] = 3600;
   $CACHE_QUERYS['map.php'] = '^';
   $cache_obj->startCachingPage();
} else
   $cache_obj = new SuperCacheEmpty();

if (($cache_obj->genereting_output==1) or ($cache_obj->genereting_output==-1) or (!$SuperCache)) {
   echo '
   <h2>'.translate("Plan du site").'</h2>
   <hr />';
   mapsections();
   mapforum();
   maptopics();
   mapcategories();
   mapfaq();
   echo '<br />';
   if (file_exists("modules/include/user.inc"))
      include ("modules/include/user.inc");
}
if ($SuperCache)
   $cache_obj->endCachingPage();
include "footer.php";
?>
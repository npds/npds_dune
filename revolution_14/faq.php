<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2010 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion")) {
   include ("mainfile.php");
}

function ShowFaq($id_cat, $categories) {
    global $sitename;
    global $NPDS_Prefix;

	echo '<h2>'.translate("FAQ (Frequently Ask Question)").'</h2>';
	echo '<h3>'.translate("Categories").'</h3>';
    echo '<p class="lead"><a href="faq.php">'.translate("Main").'</a> <i class="fa fa-angle-double-right"></i> '.StripSlashes($categories).'</p>';

    $result = sql_query("select id, id_cat, question, answer from ".$NPDS_Prefix."faqanswer where id_cat='$id_cat'");
    while (list($id, $id_cat, $question, $answer) = sql_fetch_row($result)) {
    }
}

function ShowFaqAll($id_cat) {
    global $NPDS_Prefix;

    $result = sql_query("select id, id_cat, question, answer from ".$NPDS_Prefix."faqanswer where id_cat='$id_cat'");
    while(list($id, $id_cat, $question, $answer) = sql_fetch_row($result)) {
  		
echo '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
  <div class="panel panel-primary">
    <div class="panel-heading" role="tab">
      <h4 class="panel-title"><i class="fa fa-arrow-down"></i>
        <a data-toggle="collapse" data-parent="#accordion" href="#'.$id.'" aria-expanded="true" aria-controls="'.$id.'">
          '.aff_langue($question).'
        </a>
      </h4>
    </div>
    <div id="'.$id.'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
      <div class="panel-body">
          '.aff_langue($answer).'
      </div>
    </div>
  </div>
</div>';	
    }
    echo'<p><a href="faq.php" title="'.translate("Back to FAQ Index").'"><i class="fa fa-lg fa-refresh fa-spin"></i></a></p>';
}

 settype($myfaq,'string');
 if (!$myfaq) {
    include ("header.php");
    // Include cache manager
    if ($SuperCache) {
       $cache_obj = new cacheManager();
       $cache_obj->startCachingPage();
    } else {
       $cache_obj = new SuperCacheEmpty();
    }
    if (($cache_obj->genereting_output==1) or ($cache_obj->genereting_output==-1) or (!$SuperCache)) {

       echo '<h2>'.translate("FAQ (Frequently Ask Question)").'</h2>';
       echo '<h3>'.translate("Categories").'</h3>';
       $result = sql_query("select id_cat, categories from ".$NPDS_Prefix."faqcategories ORDER BY id_cat ASC");
       while(list($id_cat, $categories) = sql_fetch_row($result)) {
          
          $catname = urlencode(aff_langue($categories));
          echo'<h4><i class="fa fa-hand-o-right"></i>&nbsp;<a href="faq.php?id_cat='.$id_cat.'&amp;myfaq=yes&amp;categories='.$catname.'">'.aff_langue($categories).'</a></h4>';
       }       
    }
    if ($SuperCache) {
       $cache_obj->endCachingPage();
    }
    include ("footer.php");

 } else {
    $title="FAQ : ".removeHack(StripSlashes($categories));
    include("header.php");
    // Include cache manager
    if ($SuperCache) {
       $cache_obj = new cacheManager();
       $cache_obj->startCachingPage();
    } else {
       $cache_obj = new SuperCacheEmpty();
    }
    if (($cache_obj->genereting_output==1) or ($cache_obj->genereting_output==-1) or (!$SuperCache)) {      
       ShowFaq($id_cat, removeHack($categories));
       ShowFaqAll($id_cat);     
    }
    if ($SuperCache) {
       $cache_obj->endCachingPage();
    }
    include("footer.php");
 }
?>
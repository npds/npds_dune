<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2020 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion"))
   include ("mainfile.php");

function ShowFaq($id_cat, $categories) {
   global $sitename, $NPDS_Prefix;
   echo '
   <h2 class="mb-4">'.translate("FAQ - Questions fréquentes").'</h2>
   <hr />
   <h3 class="mb-3">'.translate("Catégorie").' <span class="text-muted"># '.StripSlashes($categories).'</span></h3>
   <p class="lead">
      <a href="faq.php" title="'.translate("Retour à l'index FAQ").'" data-toggle="tooltip">Index</a>&nbsp;&raquo;&raquo;&nbsp;'.StripSlashes($categories).'
   </p>';

    $result = sql_query("SELECT id, id_cat, question, answer FROM ".$NPDS_Prefix."faqanswer WHERE id_cat='$id_cat'");
    while (list($id, $id_cat, $question, $answer) = sql_fetch_row($result)) {
    }
}

function ShowFaqAll($id_cat) {
   global $NPDS_Prefix;
   $result = sql_query("SELECT id, id_cat, question, answer FROM ".$NPDS_Prefix."faqanswer WHERE id_cat='$id_cat'");
   while(list($id, $id_cat, $question, $answer) = sql_fetch_row($result)) {
      echo '
      <div class="card mb-3" id="accordion_'.$id.'" role="tablist" aria-multiselectable="true">
         <div class="card-body">
            <h4 class="card-title">
            <a data-toggle="collapse" data-parent="#accordion_'.$id.'" href="#faq_'.$id.'" aria-expanded="true" aria-controls="'.$id.'"><i class="fa fa-caret-down toggle-icon"></i></a>&nbsp;'.aff_langue($question).'
            </h4>
            <div class="collapse" id="faq_'.$id.'" >
               <div class="card-text">
               '.aff_langue($answer).'
               </div>
            </div>
         </div>
      </div>';
   }
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
       $result = sql_query("SELECT id_cat, categories FROM ".$NPDS_Prefix."faqcategories ORDER BY id_cat ASC");
       echo '
       <h2 class="mb-4">'.translate("FAQ - Questions fréquentes").'</h2>
       <hr />
       <h3 class="mb-3">'.translate("Catégories").'<span class="badge badge-secondary float-right">'.sql_num_rows($result).'</span></h3>
       <div class="list-group">';
       while(list($id_cat, $categories) = sql_fetch_row($result)) {
          $catname = urlencode(aff_langue($categories));
          echo'<a class="list-group-item list-group-item-action" href="faq.php?id_cat='.$id_cat.'&amp;myfaq=yes&amp;categories='.$catname.'"><h4 class="list-group-item-heading">'.aff_langue($categories).'</h4></a>';
       }
       echo'
       </div>';
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
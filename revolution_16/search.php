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
   $offset=3;//25 
   $limit_full_search=250;

   if (!isset($min)) $min=0;
   if (!isset($max)) $max=$min+$offset;
   if (!isset($member)) $member='';
   if (!isset($query)) {
      $query_title='';
      $query_body='';
      $query=$query_body;
      $limit=" LIMIT 0, $limit_full_search";
   } else {
      $query_title=removeHack(stripslashes(urldecode($query))); // electrobug
      $query_body=removeHack(stripslashes(htmlentities(urldecode($query),ENT_NOQUOTES,cur_charset))); // electrobug
      $query=$query_body;
      $limit='';
   }
   include("header.php");
   if ($topic>0) {
      $result = sql_query("SELECT topicimage, topictext FROM ".$NPDS_Prefix."topics WHERE topicid='$topic'");
      list($topicimage, $topictext) = sql_fetch_row($result);
   } else {
      $topictext = translate("Tous les sujets");
      $topicimage = "all-topics.gif";
   }
   settype($type,'string');
   if ($type == 'users')
      echo '<h2 class="mb-3">'.translate("Rechercher dans la base des utilisateurs").'</h2><hr />';
   elseif ($type == 'sections')
      echo '<h2 class="mb-3">'.translate("Rechercher dans les rubriques").'</h2><hr />';
   elseif ($type == 'reviews')
      echo '<h2 class="mb-3">'.translate("Rechercher dans les critiques").'</h2><hr />';
   elseif ($type == 'archive')
      echo '<h2 class="mb-3">'.translate("Rechercher dans").' <span class="text-lowercase">'.translate("Archives").'</span></h2><hr />';
   else
      echo '<h2 class="mb-3">'.translate("Rechercher dans").' '.aff_langue($topictext).'</h2><hr />';
   echo '
   <form action="search.php" method="get">';
   /*
   if (($type == 'users') OR ($type == 'sections') OR ($type == 'reviews')) {
      echo "<img src=\"".$tipath."all-topics.gif\" align=\"left\" border=\"0\" alt=\"\" />";
   } else {
      if ((($topicimage) or ($topicimage!="")) and (file_exists("$tipath$topicimage"))) {
         echo "<img src=\"$tipath$topicimage\" align=\"right\" border=\"0\" alt=\"".aff_langue($topictext)."\" />";
      }
   }
   */
   echo '
      <div class="form-group">
         <input class="form-control" type="text" name="query" value="'.$query.'" />
      </div>';

   $toplist = sql_query("SELECT topicid, topictext FROM ".$NPDS_Prefix."topics ORDER BY topictext");
   echo '
   <div class="form-group">
      <select class="custom-select form-control" name="topic">
         <option value="">'.translate("Tous les sujets").'</option>';
   $sel='';
   while(list($topicid, $topics) = sql_fetch_row($toplist)) {
      if ($topicid==$topic) $sel = 'selected="selected" ';
      echo '
         <option '.$sel.' value="'.$topicid.'">'.substr_replace(aff_langue($topics),'...',25,-1).'</option>';
      $sel ='';
   }
   echo '
      </select>
   </div>
   <div class="form-group">
      <select class="custom-select form-control" name="category">
         <option value="0">'.translate("Articles").'</option>';
   $catlist = sql_query("SELECT catid, title FROM ".$NPDS_Prefix."stories_cat ORDER BY title");
   settype($category,"integer");
   $sel='';
   while (list($catid, $title) = sql_fetch_row($catlist)) {
      if ($catid==$category) $sel = 'selected="selected" ';
      echo '
         <option '.$sel.' value="'.$catid.'">'.aff_langue($title).'</option>';
      $sel = '';
   }
   echo '
      </select>
   </div>';

   $thing = sql_query("SELECT aid FROM ".$NPDS_Prefix."authors ORDER BY aid");
   echo '
   <div class="form-group">
      <select class="custom-select form-control" name="author">
         <option value="">'.translate("Tous les auteurs").'</option>';
   settype($author,'string');
   $sel='';
   while (list($authors) = sql_fetch_row($thing)) {
      if ($authors==$author) $sel = 'selected="selected" ';
      echo '
         <option '.$sel.' value="'.$authors.'">'.$authors.'</option>';
      $sel = '';
   }
   echo '
      </select>
   </div>';
   settype($days,'integer');
   $sel1=''; $sel2=''; $sel3=''; $sel4=''; $sel5=''; $sel6='';
   if ($days == '0')
      $sel1 = 'selected="selected"';
   elseif ($days == "7")
      $sel2 = 'selected="selected"';
   elseif ($days == "14")
      $sel3 = 'selected="selected"';
   elseif ($days == "30")
      $sel4 = 'selected="selected"';
   elseif ($days == "60")
      $sel5 = 'selected="selected"';
   elseif ($days == "90")
      $sel6 = 'selected="selected"';

   echo '
      <div class="form-group">
         <select class="custom-select form-control" name="days">
            <option '.$sel1.' value="0">'.translate("Tous").'</option>
            <option '.$sel2.' value="7">1 '.translate("semaine").'</option>
            <option '.$sel3.' value="14">2 '.translate("semaines").'</option>
            <option '.$sel4.' value="30">1 '.translate("mois").'</option>
            <option '.$sel5.' value="60">2 '.translate("mois").'</option>
            <option '.$sel6.' value="90">3 '.translate("mois").'</option>
         </select>
      </div>';

   if (($type == 'stories') or ($type==''))
      $sel1 = 'checked="checked"';
   elseif ($type == 'sections')
      $sel3 = 'checked="checked"';
   elseif ($type == 'users')
      $sel4 = 'checked="checked"';
   elseif ($type == 'reviews')
      $sel5 = 'checked="checked"';
   elseif ($type == 'archive')
      $sel6 = 'checked="checked"';

   echo '
      <div class="form-group">
         <div class="custom-control custom-radio custom-control-inline">
            <input class="custom-control-input" type="radio" id="sto" name="type" value="stories" '.$sel1.' />
            <label class="custom-control-label" for="sto">'.translate("Articles").'</label>
         </div>
         <div class="custom-control custom-radio custom-control-inline">
            <input class="custom-control-input" type="radio" id="arc" name="type" value="archive" '.$sel6.' />
            <label class="custom-control-label" for="arc">'.translate("Archives").'</label>
         </div>
      </div>
      <div class="form-group">
         <div class="custom-control custom-radio custom-control-inline">
            <input class="custom-control-input" type="radio" id="sec" name="type" value="sections" '.$sel3.' />
            <label class="custom-control-label" for="sec">'.translate("Rubriques").'</label>
         </div>
         <div class="custom-control custom-radio custom-control-inline">
            <input class="custom-control-input" type="radio" id="use" name="type" value="users" '.$sel4.' />
            <label class="custom-control-label" for="use">'.translate("Utilisateurs").'</label>
         </div>
         <div class="custom-control custom-radio custom-control-inline">
            <input class="custom-control-input" type="radio" id="rev" name="type" value="reviews" '.$sel5.' />
            <label class="custom-control-label" for="rev">'.translate("Critiques").'</label>
         </div>
      </div>
      <div class="form-group">
         <input class="btn btn-primary" type="submit" value="'.translate("Recherche").'" />
      </div>
   </form>';

   settype($min,'integer');
   settype($offset,'integer');
   if ($type=="stories" OR $type=="archive" OR !$type) {
      if ($category > 0)
         $categ = "AND catid='$category' ";
      elseif ($category == 0)
         $categ = '';
      if ($type=='stories' OR !$type)
         $q = "SELECT s.sid, s.aid, s.title, s.time, a.url, s.topic, s.informant, s.ihome FROM ".$NPDS_Prefix."stories s, ".$NPDS_Prefix."authors a WHERE s.archive='0' AND s.aid=a.aid $categ";
      else
         $q = "SELECT s.sid, s.aid, s.title, s.time, a.url, s.topic, s.informant, s.ihome FROM ".$NPDS_Prefix."stories s, ".$NPDS_Prefix."authors a WHERE s.archive='1' AND s.aid=a.aid $categ";
      if (isset($query)) 
         $q .= "AND (s.title LIKE '%$query_title%' OR s.hometext LIKE '%$query_body%' OR s.bodytext LIKE '%$query_body%' OR s.notes LIKE '%$query_body%') ";
      // Membre OU Auteur
      if ($member!='')
         $q .= "AND s.informant='$member' ";
      else
         if ($author!= '') $q .= "AND s.aid='$author' ";

      if ($topic != '') $q .= "AND s.topic='$topic' ";
      if ($days != '' && $days!=0) $q .= "AND TO_DAYS(NOW()) - TO_DAYS(time) <= '$days' ";
      $q .= " ORDER BY s.time DESC".$limit;
      $t = $topic;
      $x=0;
      if ($SuperCache) {
         $cache_clef="[objet]==> $q";
         $CACHE_TIMINGS[$cache_clef]=3600;
         $cache_obj = new cacheManager();
         $tab_sid=$cache_obj->startCachingObjet($cache_clef);
         if ($tab_sid!='') $x=count($tab_sid);
      } else
         $cache_obj = new SuperCacheEmpty();
      if (($cache_obj->genereting_output==1) or ($cache_obj->genereting_output==-1) or (!$SuperCache)) {
         $result = sql_query($q);
         if ($result) {
            while (list($sid, $aid, $title, $time, $url, $topic, $informant, $ihome) = sql_fetch_row($result)) {
               if (ctrl_aff($ihome,0)) {
                  $tab_sid[$x]['sid']=$sid;
                  $tab_sid[$x]['aid']=$aid;
                  $tab_sid[$x]['title']=$title;
                  $tab_sid[$x]['time']=$time;
                  $tab_sid[$x]['url']=$url;
                  $tab_sid[$x]['topic']=$topic;
                  $tab_sid[$x]['informant']=$informant;
                  $x++;
               }
            }
         }
      }
      if ($SuperCache) {
         $cache_obj->endCachingObjet($cache_clef,$tab_sid);
      }
      echo '
      <table id ="search_result" data-toggle="table" data-striped="true" data-mobile-responsive="true" data-icons-prefix="fa" data-icons="icons">
         <thead>
            <tr>
               <th data-sortable="true">'.translate("Résultats").'</th>
            </tr>
         </thead>
         <tbody>';
      if ($x<$offset) {$increment=$x;}
      if (($min+$offset)<=$x) {$increment=$offset;}
      if (($x-$min)<$offset) {$increment=($x-$min);}

      for ($i=$min; $i<($increment+$min); $i++) {
         $furl = 'article.php?sid='.$tab_sid[$i]['sid'];
         if ($type=='archive') {$furl.='&amp;archive=1';}
         formatTimestamp($tab_sid[$i]['time']);
         echo '
            <tr>
               <td><span>['.($i+1).']</span>&nbsp;'.translate("Contribution de").' <a href="user.php?op=userinfo&amp;uname='.$tab_sid[$i]['informant'].'">'.$tab_sid[$i]['informant'].'</a> :<br /><strong><a href="'.$furl.'">'.aff_langue($tab_sid[$i]['title']).'</a></strong><br /><span>'.translate("Posté par ").'<a href="'.$tab_sid[$i]['url'].'" >'.$tab_sid[$i]['aid'].'</a></span> '.translate("le").' '.$datetime.'</td>
            </tr>';
      }
      echo '
         </tbody>
      </table>';

      if ($x==0) {
      echo '
         <div class="alert alert-danger lead" role="alert">
            <i class="fa fa-exclamation-triangle fa-lg mr-2"></i>'.translate("Aucune correspondance à votre recherche n'a été trouvée").' !
         </div>';
      }

      $prev=($min-$offset);
      echo '<br /><p align="left">('.translate("Total").' : '.$x.')&nbsp;&nbsp;';
      if ($prev>=0) {
         echo '<a href="search.php?author='.$author.'&amp;topic='.$t.'&amp;min='.$prev.'&amp;query='.$query.'&amp;type='.$type.'&amp;category='.$category.'&amp;member='.$member.'&amp;days='.$days.'">';
         echo $offset.' '.translate("réponses précédentes").'</a>';
      }
      if ($min+$increment<$x) {
         if ($prev>=0) echo "&nbsp;|&nbsp;";
         echo "<a href=\"search.php?author=$author&amp;topic=$t&amp;min=$max&amp;query=$query&amp;type=$type&amp;category=$category&amp;member=$member&amp;days=$days\">";
         echo translate("réponses suivantes")."</a>";
      }
      echo '</p>';

   // reviews
   }
   elseif ($type=='reviews') {
      $result = sql_query("SELECT id, title, text, reviewer FROM ".$NPDS_Prefix."reviews WHERE (title LIKE '%$query_title%' OR text LIKE '%$query_body%') ORDER BY date DESC LIMIT $min,$offset");
      if ($result)
         $nrows  = sql_num_rows($result);
      $x=0;
      if ($nrows>0) {
         echo '
      <table id ="search_result" data-toggle="table" data-striped="true" data-icons-prefix="fa" data-icons="icons">
         <thead>
            <tr>
               <th data-sortable="true">'.translate("Résultats").'</th>
            </tr>
         </thead>
         <tbody>';
         while (list($id, $title, $text, $reviewer) = sql_fetch_row($result)) {
            $furl = "reviews.php?op=showcontent&amp;id=$id";
            echo '
            <tr>
               <td><a href="'.$furl.'">'.$title.'</a> '.translate("par").' <i class="fa fa-user text-muted"></i>&nbsp;'.$reviewer.'</td>
            </tr>';
            $x++;
         }
      echo '
         </tbody>
      </table>';
      } else
         echo '
      <div class="alert alert-danger lead">'.translate("Aucune correspondance à votre recherche n'a été trouvée").'</div>';
      $prev=$min-$offset;
      echo '
      <p align="left">
         <ul class="pagination pagination-sm">
            <li class="page-item disabled"><a class="page-link" href="#">'.$nrows.'</a></li>';
      if ($prev>=0) {
         echo '
            <li class="page-item"><a class="page-link" href="search.php?author='.$author.'&amp;topic='.$t.'&amp;min='.$prev.'&amp;query='.$query.'&amp;type='.$type.'" >'.$offset.' '.translate("réponses précédentes").'</a></li>';
      }
      if ($x>=($offset-1)) {
         echo '
            <li class="page-item"><a class="page-link" href="search.php?author='.$author.'&amp;topic='.$t.'&amp;min='.$max.'&amp;query='.$query.'&amp;type='.$type.'" >'.translate("réponses suivantes").'</a></li>';
      }
      echo '
         </ul>
      </p>';
   // sections
   }
   elseif ($type=='sections') {
      $t='';
      $result = sql_query("SELECT artid, secid, title, content FROM ".$NPDS_Prefix."seccont WHERE (title LIKE '%$query_title%' OR content LIKE '%$query_body%') ORDER BY artid DESC LIMIT $min,$offset");
      if ($result)
         $nrows  = sql_num_rows($result);
      $x=0;
      if ($nrows>0) {
         echo '
      <table id ="search_result" data-toggle="table" data-striped="true" data-icons-prefix="fa" data-icons="icons">
         <thead>
            <tr>
               <th data-sortable="true">'.translate("Résultats").'</th>
            </tr>
         </thead>
         <tbody>';
         while (list($artid, $secid, $title, $content) = sql_fetch_row($result)) {
            $rowQ2=Q_Select ("SELECT secname, rubid FROM ".$NPDS_Prefix."sections WHERE secid='$secid'", 3600);
            $row2 = $rowQ2[0];
            $rowQ3=Q_Select ("SELECT rubname FROM ".$NPDS_Prefix."rubriques WHERE rubid='".$row2['rubid']."'", 3600);
            $row3 = $rowQ3[0];
            if ($row3['rubname']!='Divers' AND $row3['rubname']!='Presse-papiers') {
               $surl = "sections.php?op=listarticles&amp;secid=$secid";
               $furl = "sections.php?op=viewarticle&amp;artid=$artid";
               echo '
            <tr>
               <td><a href="'.$furl.'">'.aff_langue($title).'</a> '.translate("dans la sous-rubrique").' <a href="'.$surl.'">'.aff_langue($row2['secname']).'</a></td>
            </tr>';
               $x++;
            }
         }
            echo '
         </tbody>
      </table>';
         if ($x==0)
            echo '
      <div class="alert alert-danger lead">'.translate("Aucune correspondance à votre recherche n'a été trouvée").'</div>';
      } else
      echo '
      <div class="alert alert-danger lead">'.translate("Aucune correspondance à votre recherche n'a été trouvée").'</div>';
      $prev=$min-$offset;
      echo '
      <p align="left">
         <ul class="pagination pagination-sm">
            <li class="page-item disabled"><a class="page-link" href="#">'.$nrows.'</a></li>';
      if ($prev>=0)
         echo '
            <li class="page-item"><a class="page-link" href="search.php?author='.$author.'&amp;topic='.$t.'&amp;min='.$prev.'&amp;query='.$query.'&amp;type='.$type.'">'.$offset.' '.translate("réponses précédentes").'</a></li>';
      if ($x>=($offset-1))
         echo '
            <li class="page-item"><a class="page-link" href="search.php?author='.$author.'&amp;topic='.$t.'&amp;min='.$max.'&amp;query='.$query.'&amp;type='.$type.'">'.translate("réponses suivantes").'</a></li>';
      echo '
         </ul>
      </p>';
   // users
   }
   elseif ($type=='users') {
      if (($member_list and $user) or $admin) {
         $result = sql_query("SELECT uname, name FROM ".$NPDS_Prefix."users WHERE (uname LIKE '%$query_title%' OR name LIKE '%$query_title%' OR bio LIKE '%$query_title%') ORDER BY uname ASC LIMIT $min,$offset");
         if ($result) {$nrows  = sql_num_rows($result);}
         $x=0;
         if ($nrows>0) {
            echo '
      <table id ="search_result" data-toggle="table" data-striped="true" data-icons-prefix="fa" data-icons="icons">
         <thead>
            <tr>
               <th data-sortable="true">'.translate("Résultats").'</th>
            </tr>
         </thead>
         <tbody>';
            while (list($uname, $name) = sql_fetch_row($result)) {
               $furl = "user.php?op=userinfo&amp;uname=$uname";
               if ($name=='') $name = translate("Aucun nom n'a été entré");
               echo '
               <tr>
                  <td><a href="'.$furl.'"><i class="fa fa-user text-muted mr-2"></i>'.$uname.'</a> ('.$name.')</td>
               </tr>';
               $x++;
            }
            echo '
         <tbody>
      </table>';
         } else
            echo '
      <div class="alert alert-danger lead" role="alert">'.translate("Aucune correspondance à votre recherche n'a été trouvée").'</div>';
         $prev=$min-$offset;
         echo '
      <p align="left">
         <ul class="pagination pagination-sm">
            <li class="page-item disabled"><a class="page-link" href="#">'.$nrows.'</a></li>';
         if ($prev>=0)
            echo '
            <li class="page-item"><a class="page-link" href="search.php?author='.$author.'&amp;topic='.$t.'&amp;min='.$prev.'&amp;query='.$query.'&amp;type='.$type.'">'.$offset.' '.translate("réponses précédentes").'</a></li>';
         if ($x>=($offset-1))
            echo '
            <li class="page-item"><a class="page-link" href="search.php?author='.$author.'&amp;topic='.$t.'&amp;min='.$max.'&amp;query='.$query.'&amp;type='.$type.'" >'.translate("réponses suivantes").'</a></li>';
         echo '
         </ul>
      </p>';
      }
   }
   include("footer.php");
?>
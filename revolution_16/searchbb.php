<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/* Based on Parts of phpBB                                              */
/*                                                                      */
/* NPDS Copyright (c) 2002-2017 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion")) {
   include ("mainfile.php");
}

include("functions.php");
if ($SuperCache) {
   $cache_obj = new cacheManager();
} else {
   $cache_obj = new SuperCacheEmpty();
}
include("auth.php");
$Smax="99";

/*jules*/
function ancre($forum_id,$topic_id,$post_id,$posts_per_page) {
   global $NPDS_Prefix;

   $rowQ1=Q_Select ("SELECT post_id FROM ".$NPDS_Prefix."posts WHERE forum_id='$forum_id' and topic_id='$topic_id' order by post_id ASC", 600);
   if (!$rowQ1)
      forumerror('0015');
   $i=0;
   while (list(,$row) = each($rowQ1)) {
      if ($row['post_id']==$post_id)
         break;
      $i++;
   }
   $start=$i-($i%$posts_per_page);
   return ("&amp;ancre=1&amp;start=$start#".$forum_id.$topic_id.$post_id);
}
/*jules*/

include('header.php');
   $term = removeHack(stripslashes(htmlspecialchars(urldecode($term),ENT_QUOTES,cur_charset))); // electrobug
   echo '
   <h2>'.translate("Search in").' : Forums</h2>
   <form name="search" action="'.$_SERVER['PHP_SELF'].'" method="post">
      <div class="form-group row">
         <div class="col-sm-4">
            <label class="form-control-label" for="term">'.translate("Keyword").'</label>
         </div>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="term" name="term" value="'.$term.'" />
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-4">
            <label class="form-control-label" for="only_solved">'.translate("Topic status").'</label>
         </div>
         <div class="col-sm-8">
         <label class="c-input c-checkbox">
            <input type="checkbox" name="only_solved" value="ON" />
           <span class="c-indicator"></span>
           '.translate("Solved").'
         </label>
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-4">
            <label class="form-control-label" for="addterms">'.translate("Sort by").'</label>
         </div>
         <div class="col-sm-8">
            <div class="c-inputs-stacked">
               <label class="c-input c-radio">
                  <input type="radio" name="addterms" value="any" checked="checked" />
                  <span class="c-indicator"></span>
                  '.translate("Search for ANY of the terms (Default)").'
               </label>
               <label class="c-input c-radio">
                  <input type="radio" name="addterms" value="all" />
                  <span class="c-indicator"></span>
                  '.translate("Search for ALL of the terms").'
               </label>
            </div>
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-4">
            <label class="form-control-label" for="forum">'.translate("Forum").'</label>
         </div>
         <div class="col-sm-8">
            <select class="form-control custom-select" name="forum">
               <option value="all">'.translate("Search All Forums").'</option>';
   $rowQ1=Q_Select ("SELECT forum_name,forum_id FROM ".$NPDS_Prefix."forums", 3600);
   if (!$rowQ1)
      forumerror('0015');
   while (list(,$row) = each($rowQ1)) {
      echo '
               <option value="'.$row['forum_id'].'">'.$row['forum_name'].'</option>';
   }
   echo '
            </select>
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-4">
            <label class="form-control-label" for="username">'.translate("Author's Name").'</label>
         </div>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="username" name="username" />
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-4">
            <label class="form-control-label" for="sortby">'.translate("Sort by").'</label>
         </div>
         <div class="col-sm-8">
            <div class="c-inputs-stacked">';
   settype($sortby, "integer");
   echo '
            <label class="c-input c-radio">
               <input type="radio" name="sortby" value="0" ';
      if ($sortby=="0") echo 'checked="checked" ';
      echo '/>
               <span class="c-indicator"></span>
                  '.translate("Post Time").'
            </label>';
   echo '
            <label class="c-input c-radio">
               <input type="radio" name="sortby" value="1" ';
      if ($sortby=="1") echo 'checked="checked" ';
      echo '/>
               <span class="c-indicator"></span>
                  '.translate("Topics").'
            </label>';
   echo '
            <label class="c-input c-radio">
               <input type="radio" name="sortby" value="2" ';
      if ($sortby=="2") echo 'checked="checked" ';
      echo '/>
               <span class="c-indicator"></span>
                  '.translate("Forum").'
            </label>';
   echo '
            <label class="c-input c-radio">
               <input type="radio" name="sortby" value="3" ';
      if ($sortby=="3") echo 'checked="checked" ';
      echo '/>
               <span class="c-indicator"></span>
                  '.translate("Author").'
            </label>
            </div>
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-8 offset-sm-4">
            <button class="btn btn-primary" type="submit" name="submit">&nbsp;'.translate("Search").'</button>&nbsp;&nbsp;
            <button class="btn btn-secondary" type="reset" name="reset">'.translate("Clear").'</button>
         </div>
      </div>
   </form>';

   $query = "SELECT u.uid, f.forum_id, p.topic_id, p.post_id, u.uname, p.post_time, t.topic_title, f.forum_name, f.forum_type, f.forum_pass, f.arbre FROM ".$NPDS_Prefix."posts p, ".$NPDS_Prefix."users u, ".$NPDS_Prefix."forums f, ".$NPDS_Prefix."forumtopics t";
   if (isset($term)&&$term!="") {
      $terms = explode(" ",stripslashes(removeHack(trim($term))));
      $addquery = "( (p.post_text LIKE '%$terms[0]%' OR strcmp(soundex(p.post_text), soundex('$terms[0]'))=0)";
      if (isset($addterms)) {
         if ($addterms=="any")
            $andor = "OR";
         else
            $andor = "AND";
      }
      $size = sizeof($terms);
      for ($i=1;$i<$size;$i++)
          $addquery.=" $andor (p.post_text LIKE '%$terms[$i]%' OR strcmp(soundex(p.post_text), soundex('$terms[$i]'))=0)";
      $addquery.=")";
   }

   if (isset($forum)&&$forum!="all") {
      if (isset($addquery))
         $addquery.=" AND p.forum_id='$forum' AND f.forum_id='$forum'";
      else
         $addquery.=" p.forum_id='$forum' AND f.forum_id='$forum'";
   }
   if (isset($username)&&$username!="") {
      $username = removeHack(stripslashes(htmlspecialchars(urldecode($username),ENT_QUOTES,cur_charset))); // electrobug
      if (!$result = sql_query("SELECT uid FROM ".$NPDS_Prefix."users WHERE uname='$username'")) {
         forumerror(0001);
      }
      list($userid) = sql_fetch_row($result);
      if (isset($addquery))
         $addquery.=" AND p.poster_id='$userid' AND u.uname='$username'";
      else
         $addquery =" p.poster_id='$userid' AND u.uname='$username'";
   }

   if (!$user) {
      if (!isset($addquery)) $addquery="";
      $addquery.=" AND f.forum_type!='5' AND f.forum_type!='7' AND f.forum_type!='9'";
   }

   if (isset($addquery))
      $query.=" WHERE $addquery AND  ";
   else
      $query.=" WHERE ";

   settype($sortby, "integer");
   if ($sortby==0) $sortbyR="p.post_id";
   if ($sortby==1) $sortbyR="t.topic_title";
   if ($sortby==2) $sortbyR="f.forum_name";
   if ($sortby==3) $sortbyR="u.uname";
   if (isset($only_solved)) {
      $query.=" p.topic_id = t.topic_id AND p.forum_id = f.forum_id AND p.poster_id = u.uid AND t.topic_status='2' GROUP BY t.topic_title ORDER BY $sortbyR DESC";
   } else {
      $query.=" p.topic_id = t.topic_id AND p.forum_id = f.forum_id AND p.poster_id = u.uid AND t.topic_status!='2' ORDER BY $sortbyR DESC";
   }

   $Smax++;
   settype($Smax,"integer");
   $query.=" limit 0,$Smax";
   $result = sql_query($query);

   $affiche=true;
   if (!$row = sql_fetch_assoc($result)) {
      echo '
         <div class="alert alert-danger lead" role="alert">
            <i class="fa fa-exclamation-triangle fa-lg"></i>&nbsp;
            '.translate("No records match that query. Please broaden your search.").'
         </div>';
      $affiche=false;
   }
   if ($affiche) {
      $count=0;
      echo '
         <table id="cherch_trouve" data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-icons-prefix="fa" data-icons="icons">';
      do {
         if (($row['forum_type'] == 5) or ($row['forum_type'] == 7)) {
            $ok_affich=false;
            $tab_groupe=valid_group($user);
            $ok_affich=groupe_forum($row['forum_pass'], $tab_groupe);
         } else {
            $ok_affich=true;
         }
         if ($ok_affich) {
            if ($count==0) {
               echo '
         <thead>
            <tr>
               <th data-sortable="true">&nbsp;</th>
               <th data-sortable="true">'.translate("Forum").'</th>
               <th data-sortable="true">'.translate("Topic").'</th>
               <th data-sortable="true">'.translate("Author").'</th>
               <th data-sortable="true">'.translate("Posted").'</th>
            </tr>
         </thead>
         <tbody>';
            }
            echo '
            <tr>
               <td align="left">'.($count+1).'</td>
               <td align="left"><a href="viewforum.php?forum='.$row['forum_id'].'">'.stripslashes($row['forum_name']).'</a></td>';
            if ($row['arbre']) {$Hplus="H";} else {$Hplus="";}
            $ancre=ancre($row['forum_id'],$row['topic_id'],$row['post_id'],$posts_per_page);
            echo '
               <td align="left"><a href="viewtopic'.$Hplus.'.php?topic='.$row['topic_id'].'&amp;forum='.$row['forum_id'].$ancre.'" >'.stripslashes($row['topic_title']).'</a></td>
               <td align="left"><a href="user.php?op=userinfo&amp;uname='.$row['uname'].'" >'.$row['uname'].'</a></td>
               <td align="left">'.convertdate($row['post_time']).'</td>
            </tr>';
            $count++;
         }
      } while ($row=sql_fetch_assoc($result));
      echo '
         </tbody>
      </table>';
   }
   sql_free_result();
      echo auto_complete ('membre','uname','users','username','86400');

   include('footer.php');
?>
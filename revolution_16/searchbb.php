<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/* Based on Parts of phpBB                                              */
/*                                                                      */
/* NPDS Copyright (c) 2002-2020 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion"))
   include ("mainfile.php");

include("functions.php");
if ($SuperCache)
   $cache_obj = new cacheManager();
else
   $cache_obj = new SuperCacheEmpty();

include("auth.php");
$Smax='99';

/*jules*/
function ancre($forum_id,$topic_id,$post_id,$posts_per_page) {
   global $NPDS_Prefix;

   $rowQ1=Q_Select ("SELECT post_id FROM ".$NPDS_Prefix."posts WHERE forum_id='$forum_id' and topic_id='$topic_id' order by post_id ASC", 600);
   if (!$rowQ1)
      forumerror('0015');
   $i=0;
   foreach($rowQ1 as $row) {
      if ($row['post_id']==$post_id)
         break;
      $i++;
   }
   $start=$i-($i%$posts_per_page);
   return ("&amp;ancre=1&amp;start=$start#".$forum_id.$topic_id.$post_id);
}
/*jules*/

include('header.php');
settype($term,'string');
   $term = removeHack(stripslashes(htmlspecialchars(urldecode($term),ENT_QUOTES,cur_charset))); // electrobug
   echo '
   <h2>'.translate("Rechercher dans").' : Forums</h2>
   <hr />
   <form name="search" action="'.$_SERVER['PHP_SELF'].'" method="post" class="mt-3">
      <div class="form-group row">
         <label class="col-form-label col-sm-4" for="term">'.translate("Mot-clé").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="term" name="term" value="'.$term.'" />
         </div>
      </div>
      <div class="form-group row">
         <label class="col-form-label col-sm-4" for="only_solved">'.translate("Etat du topic").'</label>
         <div class="col-sm-8 pt-1">
            <div class="custom-control custom-checkbox">
               <input type="checkbox" id="only_solved" name="only_solved" class="custom-control-input" value="ON" />
               <label class="custom-control-label" for="only_solved">'.translate("Résolu").'</label>
            </div>
         </div>
      </div>
      <div class="form-group row">
         <label class="col-form-label col-sm-4" for="addterms">'.translate("Classé par").'</label>
         <div class="col-sm-8">
            <div class="custom-controls-stacked">
               <div class="custom-control custom-radio mb-2">
                  <input type="radio" id="any" name="addterms" class="custom-control-input" value="any" checked="checked" />
                  <label class="custom-control-label" for="any">'.translate("Chercher n'importe quel terme (par défaut)").'</label>
               </div>
               <div class="custom-control custom-radio mb-2">
                  <input type="radio" id="all" name="addterms" class="custom-control-input" value="all" />
                  <label class="custom-control-label" for="all">'.translate("Chercher tous les mots").'</label>
               </div>
            </div>
         </div>
      </div>
      <div class="form-group row">
         <label class="col-form-label col-sm-4" for="forum">'.translate("Forum").'</label>
         <div class="col-sm-8">
            <select class="form-control custom-select" name="forum" id="forum">
               <option value="all">'.translate("Rechercher dans tous les forums").'</option>';
   $rowQ1=Q_Select ("SELECT forum_name,forum_id FROM ".$NPDS_Prefix."forums", 3600);
   if (!$rowQ1)
      forumerror('0015');
   foreach($rowQ1 as $row) {
      echo '
               <option value="'.$row['forum_id'].'">'.$row['forum_name'].'</option>';
   }
   echo '
            </select>
         </div>
      </div>
      <div class="form-group row">
         <label class="col-form-label col-sm-4" for="username">'.translate("Nom d'auteur").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="username" name="username" />
         </div>
      </div>
      <div class="form-group row">
         <label class="col-form-label col-sm-4" for="sortby">'.translate("Classé par").'</label>
         <div class="col-sm-8">
            ';
   settype($sortby, "integer");
   echo '
            <div class="custom-control custom-radio custom-control-inline mt-2">
               <input type="radio" name="sortby" id="sbpt" class="custom-control-input" value="0" ';
      if ($sortby=="0") echo 'checked="checked" ';
   echo '/>
               <label class="custom-control-label" for="sbpt">'.translate("Heure de la soumission").'</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline  mt-2">
               <input type="radio" name="sortby" id="sbto" class="custom-control-input" value="1" ';
      if ($sortby=="1") echo 'checked="checked" ';
   echo '/>
               <label class="custom-control-label" for="sbto">'.translate("Sujets").'</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline  mt-2">
               <input type="radio" name="sortby" id="sbfo" class="custom-control-input" value="2" ';
      if ($sortby=="2") echo 'checked="checked" ';
   echo '/>
               <label class="custom-control-label" for="sbfo">'.translate("Forum").'</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline  mt-2">
               <input type="radio" name="sortby" id="sbau" class="custom-control-input" value="3" ';
      if ($sortby=="3") echo 'checked="checked" ';
   echo '/>
               <label class="custom-control-label" for="sbau">'.translate("Auteur").'</label>
            </div>
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-8 ml-sm-auto">
            <button class="btn btn-primary" type="submit" name="submit">&nbsp;'.translate("Recherche").'</button>&nbsp;&nbsp;
            <button class="btn btn-secondary" type="reset" name="reset">'.translate("Annuler").'</button>
         </div>
      </div>
   </form>';

   $query = "SELECT u.uid, f.forum_id, p.topic_id, p.post_id, u.uname, p.post_time, t.topic_title, f.forum_name, f.forum_type, f.forum_pass, f.arbre FROM ".$NPDS_Prefix."posts p, ".$NPDS_Prefix."users u, ".$NPDS_Prefix."forums f, ".$NPDS_Prefix."forumtopics t";
   if (isset($term)&&$term!='') {
      $andor='';
      $terms = explode(' ',stripslashes(removeHack(trim($term))));
      $addquery = "( (p.post_text LIKE '%$terms[0]%' OR strcmp(soundex(p.post_text), soundex('$terms[0]'))=0)";
      if (isset($addterms)) {
         if ($addterms=='any')
            $andor = 'OR';
         else
            $andor = 'AND';
      }
      $size = sizeof($terms);
      for ($i=1;$i<$size;$i++)
          $addquery.=" $andor (p.post_text LIKE '%$terms[$i]%' OR strcmp(soundex(p.post_text), soundex('$terms[$i]'))=0)";
      $addquery.=")";
   }

   if (isset($forum)&&$forum!='all') {
      if (isset($addquery))
         $addquery.=" AND p.forum_id='$forum' AND f.forum_id='$forum'";
      else
         $addquery.=" p.forum_id='$forum' AND f.forum_id='$forum'";
   }
   if (isset($username)&&$username!='') {
      $username = removeHack(stripslashes(htmlspecialchars(urldecode($username),ENT_QUOTES,cur_charset))); // electrobug
      if (!$result = sql_query("SELECT uid FROM ".$NPDS_Prefix."users WHERE uname='$username'"))
         forumerror('0001');
      list($userid) = sql_fetch_row($result);
      if (isset($addquery))
         $addquery.=" AND p.poster_id='$userid' AND u.uname='$username'";
      else
         $addquery =" p.poster_id='$userid' AND u.uname='$username'";
   }

   if (!$user) {
      if (!isset($addquery)) $addquery='';
      $addquery.=" AND f.forum_type!='5' AND f.forum_type!='7' AND f.forum_type!='9'";
   }

   if (isset($addquery))
      $query.=" WHERE $addquery AND  ";
   else
      $query.=' WHERE ';

   settype($sortby, "integer");
   if ($sortby==0) $sortbyR="p.post_id";
   if ($sortby==1) $sortbyR="t.topic_title";
   if ($sortby==2) $sortbyR="f.forum_name";
   if ($sortby==3) $sortbyR="u.uname";
   if (isset($only_solved))
      $query.=" p.topic_id = t.topic_id AND p.forum_id = f.forum_id AND p.poster_id = u.uid AND t.topic_status='2' GROUP BY t.topic_title ORDER BY $sortbyR DESC";
   else
      $query.=" p.topic_id = t.topic_id AND p.forum_id = f.forum_id AND p.poster_id = u.uid AND t.topic_status!='2' ORDER BY $sortbyR DESC";

   $Smax++;
   settype($Smax,'integer');
   $query.=" LIMIT 0,$Smax";
   $result = sql_query($query);

   $affiche=true;
   if (!$row = sql_fetch_assoc($result)) {
      echo '
         <div class="alert alert-danger lead alert-dismissible fade show" role="alert">
            '.translate("Aucune réponse pour les mots que vous cherchez. Elargissez votre recherche.").'
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>';
      $affiche=false;
   }
   if ($affiche) {
      $count=0;
      echo '
         <table id="cherch_trouve" data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-buttons-class="outline-secondary" data-icons-prefix="fa" data-icons="icons">';
      do {
         if (($row['forum_type'] == 5) or ($row['forum_type'] == 7)) {
            $ok_affich=false;
            $tab_groupe=valid_group($user);
            $ok_affich=groupe_forum($row['forum_pass'], $tab_groupe);
         } else
            $ok_affich=true;
         if ($ok_affich) {
            if ($count==0)
               echo '
         <thead>
            <tr>
               <th class="n-t-col-xs-1" data-halign="center" data-align="right">&nbsp;</th>
               <th data-halign="center" data-sortable="true" data-sorter="htmlSorter">'.translate("Forum").'</th>
               <th data-halign="center" data-sortable="true" data-sorter="htmlSorter">'.translate("Sujet").'</th>
               <th data-halign="center" data-sortable="true">'.translate("Auteur").'</th>
               <th class="n-t-col-xs-2" data-halign="center" data-align="right">'.translate("Posté").'</th>
            </tr>
         </thead>
         <tbody>';
            echo '
            <tr>
               <td><span class="badge badge-success">'.($count+1).'</span></td>
               <td><a href="viewforum.php?forum='.$row['forum_id'].'">'.stripslashes($row['forum_name']).'</a></td>';
            if ($row['arbre']) {$Hplus="H";} else {$Hplus="";}
            $ancre=ancre($row['forum_id'],$row['topic_id'],$row['post_id'],$posts_per_page);
            echo '
               <td><a href="viewtopic'.$Hplus.'.php?topic='.$row['topic_id'].'&amp;forum='.$row['forum_id'].$ancre.'" >'.stripslashes($row['topic_title']).'</a></td>
               <td><a href="user.php?op=userinfo&amp;uname='.$row['uname'].'" >'.$row['uname'].'</a></td>
               <td><small>'.convertdate($row['post_time']).'</small></td>
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
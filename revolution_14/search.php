<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2013 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion")) {
   include ("mainfile.php");
}
   $offset=25;
   $limit_full_search=250;

   if (!isset($min)) $min=0;
   if (!isset($max)) $max=$min+$offset;
   if (!isset($member)) $member="";
   if (!isset($query)) {
      $query_title="";
      $query_body="";
      $query=$query_body;
      $limit=" LIMIT 0, $limit_full_search";
   } else {
      $query_title=removeHack(stripslashes(urldecode($query))); // electrobug
      $query_body=removeHack(stripslashes(htmlentities(urldecode($query),ENT_NOQUOTES,cur_charset))); // electrobug
      $query=$query_body;
      $limit="";
   }
   include("header.php");
   if ($topic>0) {
      $result = sql_query("select topicimage, topictext from ".$NPDS_Prefix."topics where topicid='$topic'");
      list($topicimage, $topictext) = sql_fetch_row($result);
   } else {
      $topictext = translate("All Topics");
      $topicimage = "all-topics.gif";
   }
   opentable();
   settype($type,'string');
   echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n";
   if ($type == "users") {
      echo translate("Search in Users Database");
   } elseif ($type == "sections") {
      echo translate("Search in Sections");
   } elseif ($type == "reviews") {
      echo translate("Search in Reviews");
   } elseif ($type == "archive") {
      echo translate("Search in")." ".translate("Archives");
   } else {
      echo translate("Search in")." ".aff_langue($topictext);
   }
   echo "</td></tr></table>\n";
   echo "<br />\n";
   echo "<form action=\"search.php\" method=\"get\"><table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\"><tr><td>";
   if (($type == "users") OR ($type == "sections") OR ($type == "reviews")) {
      echo "<img src=\"".$tipath."all-topics.gif\" align=\"right\" border=\"0\" alt=\"\" />";
   } else {
      if ((($topicimage) or ($topicimage!="")) and (file_exists("$tipath$topicimage"))) {
         echo "<img src=\"$tipath$topicimage\" align=\"right\" border=\"0\" alt=\"".aff_langue($topictext)."\" />";
      }
   }
   echo "<input class=\"textbox_standard\" size=\"25\" type=\"text\" name=\"query\" value=\"".$query."\" /> ";
   echo "<input class=\"bouton_standard\" type=\"submit\" value=\"".translate("Search")."\" /><br /><br />";
   $toplist = sql_query("select topicid, topictext from ".$NPDS_Prefix."topics order by topictext");
   echo "<select class=\"textbox_standard\" name=\"topic\">";
   echo "<option value=\"\">".translate("All Topics")."</option>\n";
   $sel="";
   while(list($topicid, $topics) = sql_fetch_row($toplist)) {
      if ($topicid==$topic) { $sel = "selected=\"selected\" "; }
      echo "<option $sel value=\"$topicid\">".aff_langue($topics)."</option>\n";
      $sel = "";
   }
   echo "</select>";

   echo "<select class=\"textbox_standard\" name=\"category\">";
   echo "<option value=\"0\">".translate("Articles")."</option>\n";
   $catlist = sql_query("select catid, title from ".$NPDS_Prefix."stories_cat order by title");
   settype($category,"integer");
   $sel="";
   while (list($catid, $title) = sql_fetch_row($catlist)) {
      if ($catid==$category) { $sel = "selected=\"selected\" "; }
      echo "<option $sel value=\"$catid\">".aff_langue($title)."</option>\n";
      $sel = "";
   }
   echo "</select>";

   $thing = sql_query("select aid from ".$NPDS_Prefix."authors order by aid");
   echo "<select class=\"textbox_standard\" name=\"author\">";
   echo "<option value=\"\">".translate("All Authors")."</option>\n";
   settype($author,'string');
   $sel="";
   while (list($authors) = sql_fetch_row($thing)) {
         if ($authors==$author) { $sel = "selected=\"selected\" "; }
         echo "<option $sel value=\"$authors\">$authors</option>\n";
         $sel = "";
   }
   echo "</select>";
   settype($days,'integer');
   $sel1=""; $sel2=""; $sel3=""; $sel4=""; $sel5=""; $sel6="";
   if ($days == "0") {
      $sel1 = "selected=\"selected\"";
   } elseif ($days == "7") {
      $sel2 = "selected=\"selected\"";
   } elseif ($days == "14") {
      $sel3 = "selected=\"selected\"";
   } elseif ($days == "30") {
      $sel4 = "selected=\"selected\"";
   } elseif ($days == "60") {
      $sel5 = "selected=\"selected\"";
   } elseif ($days == "90") {
      $sel6 = "selected=\"selected\"";
   }
   echo "<select class=\"textbox_standard\" name=\"days\">
   <option $sel1 value=\"0\">".translate("All")."</option>
   <option $sel2 value=\"7\">1 ".translate("week")."</option>
   <option $sel3 value=\"14\">2 ".translate("weeks")."</option>
   <option $sel4 value=\"30\">1 ".translate("month")."</option>
   <option $sel5 value=\"60\">2 ".translate("months")."</option>
   <option $sel6 value=\"90\">3 ".translate("months")."</option>
   </select><br />";

   if (($type == "stories") or ($type=="")) {
      $sel1 = "checked=\"checked\"";
   } elseif ($type == "sections") {
      $sel3 = "checked=\"checked\"";
   } elseif ($type == "users") {
      $sel4 = "checked=\"checked\"";
   } elseif ($type == "reviews") {
      $sel5 = "checked=\"checked\"";
   } elseif ($type == "archive") {
      $sel6 = "checked=\"checked\"";
   }
   echo "<input type=\"radio\" name=\"type\" value=\"stories\" $sel1 /> ".translate("Stories")." ";
   echo "<input type=\"radio\" name=\"type\" value=\"archive\" $sel6 /> ".translate("Archives");
   echo "</td></tr>";
   echo "<tr><td><div class=\"separ\"></div></td></tr>";
   echo "<tr><td>";
   echo "[ <input type=\"radio\" name=\"type\" value=\"sections\" $sel3 /> ".translate("Sections")." ";
   echo "<input type=\"radio\" name=\"type\" value=\"users\" $sel4 /> ".translate("Users")." ";
   echo "<input type=\"radio\" name=\"type\" value=\"reviews\" $sel5 /> ".translate("Reviews")." ]";
   echo "</td></tr></table></form>";

   echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n";
   echo "&nbsp;</td></tr></table>\n";

   settype($min,"integer");
   settype($offset,"integer");
   if ($type=="stories" OR $type=="archive" OR !$type) {
      if ($category > 0) {
         $categ = "AND catid='$category' ";
      } elseif ($category == 0) {
         $categ = "";
      }
      if ($type=="stories" OR !$type) {
         $q = "select s.sid, s.aid, s.title, s.time, a.url, s.topic, s.informant, s.ihome from ".$NPDS_Prefix."stories s, ".$NPDS_Prefix."authors a where s.archive='0' and s.aid=a.aid $categ";
      } else {
         $q = "select s.sid, s.aid, s.title, s.time, a.url, s.topic, s.informant, s.ihome from ".$NPDS_Prefix."stories s, ".$NPDS_Prefix."authors a where s.archive='1' and s.aid=a.aid $categ";
      }
      if (isset($query)) $q .= "AND (s.title LIKE '%$query_title%' OR s.hometext LIKE '%$query_body%' OR s.bodytext LIKE '%$query_body%' OR s.notes LIKE '%$query_body%') ";
      // Membre OU Auteur
      if ($member!="")
         $q .= "AND s.informant='$member' ";
      else
         if ($author!= "") $q .= "AND s.aid='$author' ";

      if ($topic != "") $q .= "AND s.topic='$topic' ";
      if ($days != "" && $days!=0) $q .= "AND TO_DAYS(NOW()) - TO_DAYS(time) <= '$days' ";
      $q .= " ORDER BY s.time DESC".$limit;
      $t = $topic;
      $x=0;
      if ($SuperCache) {
         $cache_clef="[objet]==> $q";
         $CACHE_TIMINGS[$cache_clef]=3600;
         $cache_obj = new cacheManager();
         $tab_sid=$cache_obj->startCachingObjet($cache_clef);
         if ($tab_sid!="") $x=count($tab_sid);
      } else {
         $cache_obj = new SuperCacheEmpty();
      }
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
      echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\">\n";
      if ($x<$offset) {$increment=$x;}
      if (($min+$offset)<=$x) {$increment=$offset;}
      if (($x-$min)<$offset) {$increment=($x-$min);}

      for ($i=$min; $i<($increment+$min); $i++) {
         $rowcolor = tablos();
         $furl = "article.php?sid=".$tab_sid[$i]['sid'];
         if ($type=="archive") {$furl.="&amp;archive=1";}
         formatTimestamp($tab_sid[$i]['time']);
         echo "<tr $rowcolor><td><span>[".($i+1)."]</span>&nbsp;".translate("Contributed by")." <a href=\"user.php?op=userinfo&amp;uname=".$tab_sid[$i]['informant']."\" class=\"noir\">".$tab_sid[$i]['informant']."</a> : <a href=\"$furl\" class=\"noir\">".aff_langue($tab_sid[$i]['title'])."</a><br /><span>".translate("Posted by ")."<a href=\"".$tab_sid[$i]['url']."\" class=\"noir\">".$tab_sid[$i]['aid']."</a></span>";
         echo " ".translate("on")." $datetime <br /></td></tr>";
         echo "<tr><td><hr noshade=\"noshade\" class=\"ongl\" /></td></tr>\n";
      }
      if ($x==0) {
         echo "<tr><td align=\"center\" class=\"rouge\">".translate("No matches found to your query")."<br />";
         echo "</td></tr>";
      }
      echo "</table>";

      $prev=($min-$offset);
      echo "<br /><p align=\"right\">(".translate("Total")." : ".$x.")&nbsp;&nbsp;";
      if ($prev>=0) {
         echo "<a href=\"search.php?author=$author&amp;topic=$t&amp;min=$prev&amp;query=$query&amp;type=$type&amp;category=$category&amp;member=$member&amp;days=$days\" class=\"noir\">";
         echo "$offset ".translate("previous matches")."</a>";
      }
      if ($min+$increment<$x) {
         if ($prev>=0) echo "&nbsp;|&nbsp;";
         echo "<a href=\"search.php?author=$author&amp;topic=$t&amp;min=$max&amp;query=$query&amp;type=$type&amp;category=$category&amp;member=$member&amp;days=$days\" class=\"noir\">";
         echo translate("next matches")."</a>";
      }
      echo "</p>";
   // reviews
   } elseif ($type=="reviews") {
      $result = sql_query("select id, title, text, reviewer from ".$NPDS_Prefix."reviews where (title like '%$query_title%' OR text like '%$query_body%') order by date DESC limit $min,$offset");
      if ($result) {
         $nrows  = sql_num_rows($result);
      }
      $x=0;
      echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\">\n";
      if ($nrows>0) {
         while (list($id, $title, $text, $reviewer) = sql_fetch_row($result)) {
            $rowcolor = tablos();
            $furl = "reviews.php?op=showcontent&amp;id=$id";
            echo "<tr $rowcolor><td><a href=\"$furl\" class=\"noir\">$title</a> ".translate("by")." $reviewer</td></tr>\n";
            echo "<tr><td><hr noshade=\"noshade\" class=\"ongl\" /></td></tr>";
            $x++;
         }
      } else {
         echo "<tr><td align=\"center\" class=\"rouge\">".translate("No matches found to your query")."<br />";
         echo "</td></tr>";
      }
      echo "</table>";

      $prev=$min-$offset;
      echo "<br /><p align=\"right\">";
      if ($prev>=0) {
         echo "<a href=\"search.php?author=$author&amp;topic=$t&amp;min=$prev&amp;query=$query&amp;type=$type\" class=\"noir\">";
         echo "<b>$offset ".translate("previous matches")."</b></a>";
      }
      if ($x>=($offset-1)) {
         if ($prev>=0) echo "&nbsp;|&nbsp;";
         echo "<a href=\"search.php?author=$author&amp;topic=$t&amp;min=$max&amp;query=$query&amp;type=$type\" class=\"noir\">";
         echo "<b>".translate("next matches")."</b></a>";
      }
      echo "</p>";
   // sections
   } elseif ($type=="sections") {
      $result = sql_query("select artid, secid, title, content from ".$NPDS_Prefix."seccont WHERE (title like '%$query_title%' OR content like '%$query_body%') ORDER BY artid DESC limit $min,$offset");
      if ($result) {
         $nrows  = sql_num_rows($result);
      }
      $x=0;
      echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\">\n";
      if ($nrows>0) {
         while (list($artid, $secid, $title, $content) = sql_fetch_row($result)) {
            $rowcolor = tablos();
            $rowQ2=Q_Select ("select secname, rubid from ".$NPDS_Prefix."sections where secid='$secid'", 3600);
            list(,$row2) = each($rowQ2);
            $rowQ3=Q_Select ("select rubname from ".$NPDS_Prefix."rubriques where rubid='".$row2['rubid']."'", 3600);
            list(,$row3) = each($rowQ3);
            if ($row3['rubname']!="Divers" AND $row3['rubname']!="Presse-papiers") {
               $surl = "sections.php?op=listarticles&amp;secid=$secid";
               $furl = "sections.php?op=viewarticle&amp;artid=$artid";
               echo "<tr $rowcolor><td><a href=\"$furl\" class=\"noir\">".aff_langue($title)."</a> ".translate("in the sub-section")." <a href=\"$surl\" class=\"noir\">".aff_langue($row2['secname'])."</a></td></tr>\n";
               echo "<tr><td><hr noshade=\"noshade\" class=\"ongl\" /></td></tr>";
               $x++;
            }
         }
         if ($x==0) {
            echo "<tr><td align=\"center\" class=\"rouge\">".translate("No matches found to your query")."<br />";
            echo "</td></tr>";
         }
      } else {
         echo "<tr><td align=\"center\" class=\"rouge\">".translate("No matches found to your query")."<br />";
         echo "</td></tr>";
      }
      echo "</table>";

      $prev=$min-$offset;
      echo "<br /><p align=\"right\">";
      if ($prev>=0) {
         echo "<a href=\"search.php?author=$author&amp;topic=$t&amp;min=$prev&amp;query=$query&amp;type=$type\" class=\"noir\">";
         echo "<b>$offset ".translate("previous matches")."</b></a>";
      }
      if ($x>=($offset-1)) {
         if ($prev>=0) echo "&nbsp;|&nbsp;";
         echo  "<a href=\"search.php?author=$author&amp;topic=$t&amp;min=$max&amp;query=$query&amp;type=$type\" class=\"noir\">";
         echo  "<b>".translate("next matches")."</b></a>";
      }
      echo "</p>";
   // users
   } elseif ($type=="users") {
      if (($member_list and $user) or $admin) {
         $result = sql_query("select uname, name from ".$NPDS_Prefix."users where (uname like '%$query_title%' OR name like '%$query_title%' OR bio like '%$query_title%') order by uname ASC limit $min,$offset");
         if ($result) {
            $nrows  = sql_num_rows($result);
         }
         $x=0;
         echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\">\n";
         if ($nrows>0) {
            while (list($uname, $name) = sql_fetch_row($result)) {
               $rowcolor = tablos();
               $furl = "user.php?op=userinfo&amp;uname=$uname";
               if ($name=="") {
                  $name = "".translate("No name entered")."";
               }
               echo "<tr $rowcolor><td><a href=\"$furl\" class=\"noir\">$uname</a> ($name)</td></tr>\n";
               echo "<tr><td><hr noshade=\"noshade\" class=\"ongl\" /></td></tr>";
               $x++;
            }
         } else {
            echo "<tr><td align=\"center\" class=\"rouge\">".translate("No matches found to your query")."<br />";
            echo "</td></tr>";
         }
         echo "</table>";

         $prev=$min-$offset;
         echo "<br /><p align=\"right\">";
         if ($prev>=0) {
            echo "<a href=\"search.php?author=$author&amp;topic=$t&amp;min=$prev&amp;query=$query&amp;type=$type\" class=\"noir\">";
            echo "<b>$offset ".translate("previous matches")."</b></a>";
         }
         if ($x>=($offset-1)) {
            if ($prev>=0) echo "&nbsp;|&nbsp;";
            echo "<a href=\"search.php?author=$author&amp;topic=$t&amp;min=$max&amp;query=$query&amp;type=$type\" class=\"noir\">";
            echo "<b>".translate("next matches")."</b></a>";
         }
         echo "</p>";
      }
   }
   closetable();
   include("footer.php");
?>
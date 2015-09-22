<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/* Based on Parts of phpBB                                              */
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
   opentable();
   echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n";
   echo translate("Search in")." : Forums";
   echo "</td></tr></table>\n";
   echo "<br />";
   echo "<form name=\"search\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">";
   echo "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">";
   echo "<tr>";
   echo "<td class=\"lignb\" width=\"20%\" align=\"right\">";
   echo "<b>".translate("Keyword")."</b> :&nbsp;";
   echo "</td>";
   echo "<td width=\"80%\">";
   $term = removeHack(stripslashes(htmlspecialchars(urldecode($term),ENT_QUOTES,cur_charset))); // electrobug
   echo "<input class=\"textbox\" type=\"text\" name=\"term\" value=\"$term\" />";
   echo "</td>";
   echo "</tr>";
   echo "<tr>";
   echo "<td class=\"lignb\" width=\"20%\">&nbsp;</td>";
   echo "<td width=\"80%\">";
   echo "<input type=\"checkbox\" name=\"only_solved\" value=\"ON\" />";
   echo translate("Solved");
   echo "</td>";
   echo "</tr>";
   echo "<tr>";
   echo "<td class=\"lignb\" width=\"20%\">&nbsp;</td>";
   echo "<td width=\"80%\">";
   echo "<input type=\"radio\" name=\"addterms\" value=\"any\" checked=\"checked\" />";
   echo translate("Search for ANY of the terms (Default)");
   echo "</td>";
   echo "</tr>";
   echo "<tr>";
   echo "<td class=\"lignb\" width=\"20%\">&nbsp;</td>";
   echo "<td width=\"80%\">";
   echo "<input type=\"radio\" name=\"addterms\" value=\"all\" />";
   echo translate("Search for ALL of the terms");
   echo "</td>";
   echo "</tr>";
   echo "<tr>";
   echo "<td class=\"lignb\" width=\"20%\" align=\"right\">";
   echo "<b>".translate("Forum")."</b> :";
   echo "</td>";
   echo "<td width=\"80%\">";
   echo "<select class=\"textbox_standard\" name=\"forum\">";
   echo "<option value=\"all\">".translate("Search All Forums")."</option>";
   $rowQ1=Q_Select ("SELECT forum_name,forum_id FROM ".$NPDS_Prefix."forums", 3600);
   if (!$rowQ1)
      forumerror('0015');
   while (list(,$row) = each($rowQ1)) {
      echo "<option value=\"".$row['forum_id']."\">".$row['forum_name']."</option>";
   }
   echo "</select>";
   echo "</td>";
   echo "</tr>";
   echo "<tr>";
   echo "<td class=\"lignb\" width=\"20%\" align=\"right\">";
   echo "<b>".translate("Author's Name")."</b> :";
   echo "</td>";
   echo "<td width=\"80%\">";
   echo "<input class=\"textbox\" type=\"text\" name=\"username\" />";
   echo "</td>";
   echo "</tr>";

   echo "<tr>";
   echo "<td class=\"lignb\" width=\"20%\" align=\"right\">";
   echo "<b>".translate("Sort by")."</b> :";
   echo "</td>";
   echo "<td width=\"80%\">";
   settype($sortby, "integer");
   echo "<input type=\"radio\" name=\"sortby\" value=\"0\" ";
      if ($sortby=="0") echo "checked=\"checked\" ";
      echo "/>".translate("Post Time")."&nbsp;&nbsp;";
   echo "<input type=\"radio\" name=\"sortby\" value=\"1\" ";
      if ($sortby=="1") echo "checked=\"checked\" ";
      echo "/>".translate("Topics")."&nbsp;&nbsp;";
   echo "<input type=\"radio\" name=\"sortby\" value=\"2\" ";
      if ($sortby=="2") echo "checked=\"checked\" ";
      echo "/>".translate("Forum")."&nbsp;&nbsp;";
   echo "<input type=\"radio\" name=\"sortby\" value=\"3\" ";
      if ($sortby=="3") echo "checked=\"checked\" ";
      echo "/>".translate("Author")."&nbsp;&nbsp;";
   echo "</td>";
   echo "</tr>";

   echo "<tr>";
   echo "<td colspan=\"2\" align=\"center\">";
   echo "<input class=\"bouton_standard\" type=\"submit\" name=\"submit\" Value=".translate("Search")." />&nbsp;&nbsp;<input class=\"bouton_standard\" type=\"reset\" name=\"reset\" value=\"".translate("Clear")."\" />";
   echo "</td></tr>";
   echo "</table>";
   echo "</form>";

   $query = "SELECT u.uid, f.forum_id, p.topic_id, p.post_id, u.uname, p.post_time, t.topic_title, f.forum_name, f.forum_type, f.forum_pass, f.arbre FROM ".$NPDS_Prefix."posts p, ".$NPDS_Prefix."users u, ".$NPDS_Prefix."forums f, ".$NPDS_Prefix."forumtopics t";
   if (isset($term)&&$term!="") {
      $terms = explode(" ",stripslashes(removeHack(trim($term))));
      $addquery = "( (p.post_text LIKE '%$terms[0]%' or strcmp(soundex(p.post_text), soundex('$terms[0]'))=0)";
      if (isset($addterms)) {
         if ($addterms=="any")
            $andor = "OR";
         else
            $andor = "AND";
      }
      $size = sizeof($terms);
      for ($i=1;$i<$size;$i++)
          $addquery.=" $andor (p.post_text LIKE '%$terms[$i]%' or strcmp(soundex(p.post_text), soundex('$terms[$i]'))=0)";
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
      echo "<p align=\"center\" class=\"rouge\">".translate("No records match that query. Please broaden your search.")."</p><br />";
      $affiche=false;
   }
   if ($affiche) {
      $count=0;
      echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\">";
      do {
         $rowcolor = tablos();
         if (($row['forum_type'] == 5) or ($row['forum_type'] == 7)) {
            $ok_affich=false;
            $tab_groupe=valid_group($user);
            $ok_affich=groupe_forum($row['forum_pass'], $tab_groupe);
         } else {
            $ok_affich=true;
         }
         if ($ok_affich) {
            if ($count==0) {
               echo "<tr>";
               echo "<td class=\"ongl\" align=\"left\">&nbsp;</td>";
               echo "<td class=\"ongl\" align=\"left\" nowrap=\"nowrap\">".translate("Forum")."</td>";
               echo "<td class=\"ongl\" align=\"left\">".translate("Topic")."</td>";
               echo "<td class=\"ongl\" align=\"left\" nowrap=\"nowrap\">".translate("Author")."</td>";
               echo "<td class=\"ongl\" align=\"left\" nowrap=\"nowrap\">".translate("Posted")."</td>";
               echo "</tr>";
            }
            echo "<tr $rowcolor>";
            echo "<td align=\"left\">".($count+1)."</td><td align=\"left\"><a href=\"viewforum.php?forum=".$row['forum_id']."\" class=\"noir\">".stripslashes($row['forum_name'])."</a></td>";
            if ($row['arbre']) {$Hplus="H";} else {$Hplus="";}
            $ancre=ancre($row['forum_id'],$row['topic_id'],$row['post_id'],$posts_per_page);
            echo "<td align=\"left\"><a href=\"viewtopic$Hplus.php?topic=".$row['topic_id']."&amp;forum=".$row['forum_id']."$ancre\" class=\"noir\">".stripslashes($row['topic_title'])."</a></td>";
            echo "<td align=\"left\"><a href=\"user.php?op=userinfo&amp;uname=".$row['uname']."\" class=\"noir\">".$row['uname']."</a></td>";
            echo "<td align=\"left\">".convertdate($row['post_time'])."</td>";
            echo "</tr>";
            $count++;
         }
      } while ($row=sql_fetch_assoc($result));
      echo "</table>";
   }
   closetable();
   sql_free_result();
   include('footer.php');
?>
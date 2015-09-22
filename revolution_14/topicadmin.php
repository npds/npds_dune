<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/*                                                                      */
/* NPDS Copyright (c) 2001-2013 by Philippe Brunier                     */
/* =========================                                            */
/*                                                                      */
/* Based on PhpNuke 4.x and PhpBB integration source code               */
/* Great mods by snipe                                                  */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion")) {
   include ("mainfile.php");
}

include('functions.php');
if ($SuperCache) {
   $cache_obj = new cacheManager();
} else {
   $cache_obj = new SuperCacheEmpty();
}
include('auth.php');
global $NPDS_Prefix;

   if (isset($arbre) and ($arbre=="1")) {$url_ret="viewtopicH.php";} else {$url_ret="viewtopic.php";}

   $Mmod=false;
   $userX = base64_decode($user);
   $userdata = explode(":", $userX);
   settype($forum, "integer");
   $rowQ1=Q_Select ("SELECT forum_name, forum_moderator, forum_type, forum_pass, forum_access, arbre FROM ".$NPDS_Prefix."forums WHERE forum_id = '$forum'", 3600);
   if (!$rowQ1)
      forumerror('0001');
   list(,$myrow) = each($rowQ1);
   $moderator=explode(" ",get_moderator($myrow['forum_moderator']));
   for ($i = 0; $i < count($moderator); $i++) {
       if (($userdata[1] == $moderator[$i])) {
          if (user_is_moderator($userdata[0],$userdata[2],$myrow['forum_access'])) {
             $Mmod=true;
          }
          break;
       }
   }
   if (!$Mmod) {
      forumerror('0007');
   }

   if ((isset($submit)) and ($mode=="move")) {
      $sql = "UPDATE ".$NPDS_Prefix."forumtopics SET forum_id='$newforum' WHERE topic_id='$topic'";
      if (!$r = sql_query($sql))
         forumerro('0010');
      $sql = "UPDATE ".$NPDS_Prefix."posts SET forum_id='$newforum' WHERE topic_id='$topic' and forum_id='$forum'";
      if (!$r = sql_query($sql))
         forumerror('0010');
      $sql = "DELETE FROM ".$NPDS_Prefix."forum_read where topicid='$topic'";
      if (!$r = sql_query($sql))
         forumerror('0001');
      $sql = "UPDATE $upload_table SET forum_id='$newforum' WHERE apli='forum_npds' and topic_id='$topic' and forum_id='$forum'";
      sql_query($sql);
      $sql = "SELECT arbre FROM ".$NPDS_Prefix."forums where forum_id='$newforum'";
      $arbre=sql_fetch_assoc(sql_query($sql));
      if ($arbre['arbre']) {$url_ret="viewtopicH.php";} else {$url_ret="viewtopic.php";}
      include("header.php");
      opentable();
      echo "<p align=\"center\" class=\"noir\">".translate("The topic has been moved.")."</p><br /> - <a href=\"$url_ret?topic=$topic&amp;forum=$newforum\" class=\"noir\">".translate("Click here to view the updated topic.")."</a><br /><br /> - <a href=\"forum.php\" class=\"noir\">".translate("Click here to return to the forum index.")."</a>";
      closetable();
      Q_Clean();
      include("footer.php");
   } else {
      if ($Mmod) {
         switch ($mode) {
            case 'move':
               include("header.php");
               opentable();
               echo "<br /><form action=\"topicadmin.php\" method=\"post\">
                     <table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
                     <tr>
                     <td class=\"header\">".translate("Move Topic To: ")."</td>
                     <td class=\"header\"><select class=\"textbox_standard\" name=\"newforum\" size=\"0\">";
               $sql = "SELECT forum_id, forum_name FROM ".$NPDS_Prefix."forums WHERE forum_id!='$forum' ORDER BY cat_id,forum_index,forum_id";
               if ($result = sql_query($sql)) {
                  if ($myrow = sql_fetch_assoc($result)) {
                     do {
                        echo "<option value=\"".$myrow['forum_id']."\">".$myrow['forum_name']."</option>\n";
                     } while($myrow = sql_fetch_assoc($result));
                  } else {
                     echo "<option value=\"-1\">".translate("No More Forums")."</option>\n";
                  }
               } else {
                  echo "<option value=\"-1\">Database Error</OPTION>\n";
               }
               echo "</select>&nbsp;&nbsp;</td><td align=\"center\">
               <input type=\"hidden\" name=\"mode\" value=\"move\" />
               <input type=\"hidden\" name=\"topic\" value=\"$topic\" />
               <input type=\"hidden\" name=\"forum\" value=\"$forum\" />
               <input type=\"hidden\" name=\"arbre\" value=\"$arbre\" />
               <input class=\"bouton_standard\" type=\"submit\" name=\"submit\" value=\"".translate("Move Topic")."\" />
               </td></tr></table>
               </form>";
               closetable();
               include("footer.php");
               break;
            case 'del':
               $sql = "DELETE FROM ".$NPDS_Prefix."posts WHERE topic_id='$topic' and forum_id='$forum'";
               if (!$result = sql_query($sql))
                  forumerror('0009');
               $sql = "DELETE FROM ".$NPDS_Prefix."forumtopics WHERE topic_id='$topic'";
               if (!$result = sql_query($sql))
                  forumerror('0010');
               $sql = "DELETE FROM ".$NPDS_Prefix."forum_read where topicid='$topic'";
               if (!$r = sql_query($sql))
                  forumerror('0001');
               control_efface_post ("forum_npds", "",$topic,"");
               header("location: viewforum.php?forum=$forum");
               break;
            case 'lock':
               $sql = "UPDATE ".$NPDS_Prefix."forumtopics SET topic_status=1 WHERE topic_id='$topic'";
               if (!$r = sql_query($sql))
                  forumerror('0011');
               header("location: $url_ret?topic=$topic&forum=$forum");
               break;
            case 'unlock':
               $topic_title="";
               $sql = "SELECT topic_title from ".$NPDS_Prefix."forumtopics WHERE topic_id = '$topic'";
               $r=sql_fetch_assoc(sql_query($sql));
               $topic_title=str_replace("[".translate("Solved")."] - ","",$r['topic_title']);
               $sql = "UPDATE ".$NPDS_Prefix."forumtopics SET topic_status = '0', topic_first='1', topic_title='".addslashes ($topic_title)."' WHERE topic_id = '$topic'";
               if (!$r = sql_query($sql))
                  forumerror('0012');
               header("location: $url_ret?topic=$topic&forum=$forum");
               break;
            case 'first':
               $sql = "UPDATE ".$NPDS_Prefix."forumtopics SET topic_status = '1', topic_first='0' WHERE topic_id = '$topic'";
               if (!$r = sql_query($sql))
                  forumerror('0011');
               header("location: $url_ret?topic=$topic&forum=$forum");
               break;
            case 'viewip':
               include("header.php");
               opentable();
               $sql = "SELECT u.uname, p.poster_ip, p.poster_dns FROM ".$NPDS_Prefix."users u, ".$NPDS_Prefix."posts p WHERE p.post_id = '$post' AND u.uid = p.poster_id";
               if (!$r = sql_query($sql))
                  forumerror('0013');
               if (!$m = sql_fetch_assoc($r))
                  forumerror('0014');
               echo "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
                     <tr>
                       <td class=\"header\" colspan=\"2\">".translate("Users IP and Account information")."</td>
                     </tr><tr>
                       <td>".translate("Nickname: ")."</td>
                       <td>".$m['uname']."</td>
                     </tr><tr>
                       <td>".translate("User IP: ")."</td>
                       <td>".$m['poster_ip']." => <a href=\"topicadmin.php?mode=banip&topic=$topic&post=$post&forum=$forum&arbre=$arbre\" class=\"noir\">".translate("Ban this @IP")."</a></td>
                     </tr><tr>
                       <td>".translate("User DNS: ")."</td>
                       <td>".$m['poster_dns']."</td>
                     </tr><tr>
                       <td>GeoTool</td>
                       <td><a href=\"http://geoip.flagfox.net/?ip=".$m['poster_ip']."\" target=\"_blank\" class=\"noir\">FlagFox</a></td>
                     </tr>
                     </table>";
               echo "<br /><p align=\"center\"><a href=\"$url_ret?topic=$topic&amp;forum=$forum\" class=\"noir\">".translate("Go Back")."</a></p>";
               closetable();
               include("footer.php");
               break;
            case 'banip':
               $sql = "SELECT p.poster_ip FROM ".$NPDS_Prefix."users u, ".$NPDS_Prefix."posts p WHERE p.post_id = '$post' AND u.uid = p.poster_id";
               if (!$r = sql_query($sql))
                  forumerror('0013');
               if (!$m = sql_fetch_assoc($r))
                  forumerror('0014');
               L_spambot($m['poster_ip'],"ban");              
               header("location: $url_ret?topic=$topic&forum=$forum");
               break;
            case 'aff':
               $sql = "UPDATE ".$NPDS_Prefix."posts SET post_aff = '$ordre' WHERE post_id = '$post'";
               sql_query($sql);
               header("location: $url_ret?topic=$topic&forum=$forum");
               break;
         }
      } else {
         include("header.php");
         opentable();
         echo "<p align=\"center\">".translate("You are not the moderator of this forum therefor you cannot perform this function.")."<br /><br />";
         echo "<a href=\"javascript:history.go(-1)\" class=\"noir\">".translate("Go Back")."</a></p>";
         closetable();
         include("footer.php");
      }
   }
?>
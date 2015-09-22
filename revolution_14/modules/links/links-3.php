<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2010 by Philippe Brunier   */
/*                                                                      */
/* New Links.php Module with SFROM extentions                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],"modules.php")) { die(); }

function modifylinkrequest($lid, $modifylinkrequest_adv_infos, $author) {
   global $ModPath, $ModStart, $links_DB;
   global $NPDS_Prefix;

   if (autorise_mod($lid,false)) {
      if ($author=="-9") {
         Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath/admin&op=LinksModLink&lid=$lid");
      }
      include("header.php");
      opentable();
      mainheader();
      $result = sql_query("select cid, sid, title, url, description, topicid_card from ".$links_DB."links_links where lid='$lid'");
      list($cid, $sid, $title, $url, $description, $topicid_card) = sql_fetch_row($result);
      $title = stripslashes($title);
      echo "<br /><table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n";
      echo translate("Request Link Modification")." : ".$title;
      echo "</td></tr></table>\n";
      echo "<br />\n";
      $description = stripslashes($description);
      echo "<form action=\"modules.php\" method=\"post\" name=\"adminForm\">
      <input type=\"hidden\" name=\"ModPath\" value=\"$ModPath\" />
      <input type=\"hidden\" name=\"ModStart\" value=\"$ModStart\" />";
      echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\">";
      echo "<tr><td width=\"15%\">".translate("Title:")."</td><td><input class=\"textbox\" type=\"text\" name=\"title\" value=\"$title\" size=\"50\" maxlength=\"100\" /></td></tr>";
      global $links_url;
      if ($links_url)
         echo "<tr><td width=\"15%\">URL :</td><td><input class=\"textbox\" type=\"text\" name=\"url\" value=\"$url\" size=\"50\" maxlength=\"100\" /></td></tr>";

      echo "<tr><td width=\"15%\">".translate("Category: ")."</td><td><select class=\"textbox_standard\" name=\"cat\">";
      $result2=sql_query("select cid, title from ".$links_DB."links_categories order by title");
      while (list($ccid, $ctitle) = sql_fetch_row($result2)) {
         $sel = "";
         if ($cid==$ccid AND $sid==0) {
            $sel = "selected";
         }
         echo "<option value=\"$ccid\" $sel>".aff_langue($ctitle)."</option>";
         $result3=sql_query("select sid, title from ".$links_DB."links_subcategories where cid='$ccid' order by title");
         while (list($ssid, $stitle) = sql_fetch_row($result3)) {
            $sel = "";
            if ($sid==$ssid) {
               $sel = "selected=\"selected\"";
            }
            echo "<option value=\"$ccid-$ssid\" $sel>".aff_langue("$ctitle / $stitle")."</option>";
         }
      }
      echo "</select></td></tr>";
      global $links_topic;
      if ($links_topic) {
         echo "<tr><td width=\"15%\">".translate("Topics")." : </td><td><select class=\"textbox_standard\" name=\"topicL\">";
         $toplist = sql_query("select topicid, topictext from ".$NPDS_Prefix."topics order by topictext");
         echo "<option value=\"\">".translate("All Topics")."</option>\n";
         while(list($topicid, $topics) = sql_fetch_row($toplist)) {
           if ($topicid==$topicid_card) { $sel = "selected=\"selected\" "; }
           echo "<option $sel value=\"$topicid\">$topics</option>\n";
           $sel = "";
         }
         echo "</select></td></tr>";
      }
      echo "</table>";
      echo "<br />".translate("Description: (255 characters max)")."<br /><textarea class=\"textbox\" name=\"xtext\" cols=\"50\" rows=\"10\" style=\"width: 100%;\">$description</textarea>";
      aff_editeur("xtext","false");
      echo "<br />";
      echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\" />";
      echo "<input type=\"hidden\" name=\"modifysubmitter\" value=\"$author\" />";
      echo "<input type=\"hidden\" name=\"op\" value=\"modifylinkrequestS\" /><input type=\"submit\" class=\"bouton_standard\" value=\"".translate("Send Request")."\" /></form>";
      closetable();
      $browse_key=$lid;
      include ("modules/sform/$ModPath/link_maj.php");
      include("footer.php");
   } else {
      header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath");
   }
}

function modifylinkrequestS($lid, $cat, $title, $url, $description, $modifysubmitter, $topicL) {
   global $links_DB;
   if (autorise_mod($lid,false)) {
      $cat = explode("-", $cat);
      if (!array_key_exists(1,$cat)) {
         $cat[1] = 0;
      }
      $title = stripslashes(FixQuotes($title));
      $url = stripslashes(FixQuotes($url));
      $description = stripslashes(FixQuotes($description));
      if ($modifysubmitter==-9) {$modifysubmitter="";}
      $result=sql_query("insert into ".$links_DB."links_modrequest values (NULL, $lid, $cat[0], $cat[1], '$title', '$url', '$description', '$modifysubmitter', '0', '$topicL')");

      global $ModPath, $ModStart;
      include("header.php");
      opentable();
      echo "<br /><p align=\"center\">".translate("Thanks for this information. We'll look into your request shortly.")."</p><br />";
      closetable();
      include("footer.php");
   }
}

function brokenlink($lid) {
    global $ModPath, $ModStart, $links_DB, $anonymous;
    include("header.php");
    opentable();
    global $user;
    if (isset($user)) {
       global $cookie;
       $ratinguser=$cookie[1];
    } else {
       $ratinguser=$anonymous;
    }
    mainheader();
    echo "<br /><table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n";
    echo translate("Report Broken Link");
    echo "</td></tr></table>\n";
    echo "<br />\n";
    echo "<form method=\"post\" action=\"modules.php\">
          <input type=\"hidden\" name=\"ModPath\" value=\"$ModPath\" />
          <input type=\"hidden\" name=\"ModStart\" value=\"$ModStart\" />";
    echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\" />";
    echo "<input type=\"hidden\" name=\"modifysubmitter\" value=\"$ratinguser\" />";
    echo "<p align=\"center\">
          ".translate("Thank you for helping to maintain this directory's integrity.")."
          <br /><br />
          ".translate("For security reasons your user name and IP address will also be temporarily recorded.")."
          <br /><br />";
    echo "<input type=\"hidden\" name=\"op\" value=\"brokenlinkS\" /><input type=\"submit\" class=\"bouton_standard\" value=\"".translate("Report Broken Link")."\" /></p></form>";
    closetable();
    include("footer.php");
}

function brokenlinkS($lid, $modifysubmitter) {
    global $user, $links_DB;
    if (isset($user)) {
       global $cookie;
       $ratinguser = $cookie[1];
    } else {
       $ratinguser = $anonymous;
    }
    if ($modifysubmitter==$ratinguser) {
       settype($lid,"integer");
       sql_query("insert into ".$links_DB."links_modrequest values (NULL, $lid, 0, 0, '', '', '', '$ratinguser', 1,'')");
    }
    include("header.php");
    opentable();
    echo "<br /><p align=\"center\">".translate("Thanks for this information. We'll look into your request shortly.")."</p><br />";
    closetable();
    include("footer.php");
}
?>
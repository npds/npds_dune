<?php
/************************************************************************/                                                                                                                                  /* DUNE by NPDS                                                         */
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2013 by Philippe Brunier   */
/*                                                                      */
/* New Links.php Module with SFROM extentions                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],"modules.php")) { die(); }

function error_head($class) {
   global $ModPath, $ModStart;
   include("header.php");
   $mainlink = 1;
   
   menu($mainlink);
   SearchForm();
   
   echo "<p class=\"$class\" align=\"center\">";
}
function error_foot() {
   echo "</p>";
   
   
   include("footer.php");
}

function AddLink() {
    global $ModPath, $ModStart, $links_DB;
    global $NPDS_Prefix;
    global $links_anonaddlinklock;
    include("header.php");
    
    $mainlink = 1;
    global $user;
    mainheader();

    if (autorisation($links_anonaddlinklock)) {
		
		echo '<h3>Proposer un lien</h3>';
        echo "<ul>
        <li>".translate("Submit a unique link only once.")."</li>
        <li>".translate("All links are posted pending verification.")."</li>
        <li>".translate("Username and IP are recorded, so please don't abuse the system.")."</li>
        </ul>";
        echo "<form class=\"form-horizontal\" method=\"post\" action=\"modules.php\" name=\"adminForm\">
              <input type=\"hidden\" name=\"ModPath\" value=\"$ModPath\" />
              <input type=\"hidden\" name=\"ModStart\" value=\"$ModStart\" />";

        echo "".translate("Title:")."</td><td><input class=\"form-control\" type=\"text\" name=\"title\" size=\"50\" maxlength=\"100\" />";
        global $links_url;
        if (($links_url) or ($links_url==-1))
           echo "URL :<input class=\"form-control\" type=\"text\" name=\"url\" size=\"50\" maxlength=\"100\" value=\"http://\" />";
        $result=sql_query("select cid, title from ".$links_DB."links_categories order by title");
        echo "".translate("Category: ")."<select class=\"form-control\" name=\"cat\">";
        while (list($cid, $title) = sql_fetch_row($result)) {
           echo "<option value=\"$cid\">".aff_langue($title)."</option>";
           $result2=sql_query("select sid, title from ".$links_DB."links_subcategories where cid='$cid' order by title");
           while (list($sid, $stitle) = sql_fetch_row($result2)) {
              echo "<option value=\"$cid-$sid\">".aff_langue("$title / $stitle")."</option>";
           }
        }
        echo "</select>";
        global $links_topic;
        if ($links_topic) {
           echo "".translate("Topics")." : <select class=\"form-control\" name=\"topicL\">";
           $toplist = sql_query("select topicid, topictext from ".$NPDS_Prefix."topics order by topictext");
           echo "<option value=\"\">".translate("All Topics")."</option>\n";
           while(list($topicid, $topics) = sql_fetch_row($toplist)) {
             echo "<option value=\"$topicid\">$topics</option>\n";
           }
           echo "</select>";
        }
        echo "<br />
        ".translate("Description: (255 characters max)")."<br /><textarea class=\"form-control\" name=\"xtext\" cols=\"60\" rows=\"10\" style=\"width: 100%;\"></textarea>";
        echo aff_editeur("xtext","false");
        echo "<br />";
        global $cookie;
        echo "";
        echo "".translate("Your Name: ")."<input type=\"text\" class=\"form-control\" name=\"name\" size=\"40\" maxlength=\"60\" value=\"$cookie[1]\">
        ".translate("Your Email: ")."<input type=\"text\" class=\"form-control\" name=\"email\" size=\"40\" maxlength=\"60\">
        <br />";
        echo Q_spambot();
        echo "<input type=\"hidden\" name=\"op\" value=\"Add\" />
        <input type=\"submit\" class=\"bouton_standard\" value=".translate("Add URL")." />
        </form>";
    } else {
        echo "<p align=\"center\>".translate("You are not a registered user or you have not logged in.")."<br /><br />
        ".translate("If you were registered you could add links on this website.")."<br /></p>";
    }
    echo "";
    
    include("footer.php");
}

function Add($title, $url, $name, $cat, $description, $name, $email, $topicL, $asb_question, $asb_reponse) {
    global $ModPath, $ModStart, $links_DB, $troll_limit, $anonymous;

    global $user, $admin;
    if (!$user and !$admin) {
       //anti_spambot
       if (!R_spambot($asb_question, $asb_reponse, "")) {
          Ecr_Log("security", "Links Anti-Spam : url=".$url, "");
          redirect_url("index.php");
          die();
       }
    }

    $result = sql_query("select lid from ".$links_DB."links_newlink");
    $numrows = sql_num_rows($result);
    if ($numrows>=$troll_limit) {
       error_head("rouge");
       echo translate("ERROR: This URL is already listed in the Database!")."<br />";
       error_foot();
       exit();
    }
    global $user;
    if (isset($user)) {
       global $cookie;
       $submitter = $cookie[1];
    } else {
       $submitter = $anonymous;
    }
    if ($title=="") {
       error_head("rouge");
       echo translate("ERROR: You need to type a TITLE for your URL!")."<br />";
       error_foot();
       exit();
    }
    if ($email=="") {
       error_head("rouge");
       echo translate("ERROR: Invalid email")."<br />";
       error_foot();
       exit();
    }
    global $links_url;
    if (($url=="") and ($links_url==1)) {
       error_head("rouge");
       echo translate("ERROR: You need to type a URL for your URL!")."<br />";
       error_foot();
       exit();
    }
    if ($description=="") {
       error_head("rouge");
       echo translate("ERROR: You need to type a DESCRIPTION for your URL!")."<br />";
       error_foot();
       exit();
    }
    $cat = explode("-", $cat);
    if (!array_key_exists(1,$cat)) {
       $cat[1] = 0;
    }
    $title = removeHack(stripslashes(FixQuotes($title)));
    $url = removeHack(stripslashes(FixQuotes($url)));
    $description = removeHack(stripslashes(FixQuotes($description)));
    $name = removeHack(stripslashes(FixQuotes($name)));
    $email = removeHack(stripslashes(FixQuotes($email)));
    sql_query("insert into ".$links_DB."links_newlink values (NULL, '$cat[0]', '$cat[1]', '$title', '$url', '$description', '$name', '$email', '$submitter', '$topicL')");
    error_head("NOIR");
    echo translate("We received your Link submission. Thanks!")."<br />";
    echo translate("You'll receive and E-mail when it's approved.")."<br /><br />";
    error_foot();
}

function links_search($query, $topicL, $min, $max, $offset) {
    global $ModPath, $ModStart, $links_DB;

    include ("header.php");

    
    mainheader();
    $filen="modules/$ModPath/links.ban_02.php";
    if (file_exists($filen)) {include($filen);}
    $query = removeHack(stripslashes(htmlspecialchars($query,ENT_QUOTES,cur_charset))); // Romano et NoSP

    if ($topicL!="") {
       $result = sql_query("select lid, url, title, description, date, hits, topicid_card, cid, sid from ".$links_DB."links_links where topicid_card='$topicL' and (title LIKE '%$query%' OR description LIKE '%$query%') order by lid ASC LIMIT $min,$offset");
    } else {
       $result = sql_query("select lid, url, title, description, date, hits, topicid_card, cid, sid from ".$links_DB."links_links where title LIKE '%$query%' OR description LIKE '%$query%' order by lid ASC LIMIT $min,$offset");
    }
    if ($result) {
      $link_fiche_detail="";
      include_once("modules/$ModPath/links-view.php");
      $prev=$min-$offset;
      if ($prev>=0) {
          echo "$min <a href=\"modules.php?ModPath=$ModPath&amp;ModStart=$ModStart&amp;op=search&min=$prev&amp;query=$query&amp;topicL=$topicL\" class=\"noir\">";
          echo translate("previous matches")."</a>&nbsp;&nbsp;";
       }
       if ($x>=($offset-1)) {
          echo "<a href=\"modules.php?ModPath=$ModPath&amp;ModStart=$ModStart&amp;op=search&amp;min=$max&amp;query=$query&amp;topicL=$topicL\" class=\"noir\">";
          echo translate("next matches")."</a>";
       }
    }
    
    include("footer.php");
}
?>
<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2012 by Philippe Brunier   */
/*                                                                      */
/* New Links.php Module with SFROM extentions                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Access_Error")) { die(""); }
if (!stristr($_SERVER['PHP_SELF'],"modules.php")) { Access_Error(); }

global $language;
global $links_DB;
global $NPDS_Prefix;

$pos = strpos($ModPath, "/admin");
include_once('modules/'.substr($ModPath,0,$pos).'/links.conf.php');
if ($links_DB=="") {
   $links_DB=$NPDS_Prefix;
}
$hlpfile = "modules/".substr($ModPath,0,$pos)."/manual/$language/mod-weblinks.html";

$result = sql_query("select radminlink, radminsuper from ".$NPDS_Prefix."authors where aid='$aid'");
list($radminlink, $radminsuper) = sql_fetch_row($result);
if (($radminlink!=1) and ($radminsuper!=1)) {
   Access_Error();
}

function links() {
    global $ModPath, $ModStart, $links_DB;
    global $admin, $language, $hlpfile;
    global $NPDS_Prefix;

    include ("header.php");

    echo "<script type=\"text/javascript\">\n";
    echo "//<![CDATA[\n";
    echo "function openwindow(){\n";
    echo " window.open (\"$hlpfile\",\"Help\",\"toolbar=no,location=no,directories=no,status=no,scrollbars=yes,resizable=no,copyhistory=no,width=600,height=400\");\n";
    echo "}\n";
    echo "//]]>\n";
    echo "</script>\n";

    opentable();
    $result=sql_query("select * from ".$links_DB."links_links");
    $numrows = sql_num_rows($result);
    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\"><tr class=\"header\"><td>\n";
    echo "DB : $links_DB";
    echo translate("There are")." <b>$numrows</b> ".translate("Links in our Database")." ";
    echo "</td><td align=\"left\">[ <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath\" class=\"box\">".translate("Links Main")."</a> ]</td><td align=\"right\">[ <a href=\"javascript:openwindow();\" class=\"box\">".translate("Online Manual")."</a> ]";
    echo "</td></tr></table>\n";

    $result = sql_query("select * from ".$links_DB."links_modrequest where brokenlink=1");
    $totalbrokenlinks = sql_num_rows($result);
    $result2 = sql_query("select * from ".$links_DB."links_modrequest where brokenlink=0");
    $totalmodrequests = sql_num_rows($result2);
    echo "<br /><p align=\"center\">[ <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&op=LinksListBrokenLinks\" class=\"noir\">".translate("Broken Link Reports")." ($totalbrokenlinks)</a> - <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&op=LinksListModRequests\" class=\"noir\">".translate("Link Modification Requests")." ($totalmodrequests)</a> ]</p><br />";

    $result = sql_query("select lid, cid, sid, title, url, description, name, email, submitter, topicid_card from ".$links_DB."links_newlink ORDER BY lid ASC LIMIT 0,1");
    $numrows = sql_num_rows($result);
    $adminform="";
    if ($numrows>0) {
       $adminform="adminForm";
       echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n";
       echo translate("Links Waiting for Validation");
       echo "</td></tr></table>\n";
       list($lid, $cid, $sid, $title, $url, $description, $name, $email, $submitter, $topicid_card) = sql_fetch_row($result);
          // Le lien existe déja dans la table ?
          $resultAE = sql_query("select url from ".$links_DB."links_links where url='$url'");
          $numrowsAE = sql_num_rows($resultAE);
          $rowcolor = tablos();
          echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\">\n";
          echo "<form action=\"modules.php\" method=\"post\" name=\"$adminform\">
          <input type=\"hidden\" name=\"ModPath\" value=\"$ModPath\" />
          <input type=\"hidden\" name=\"ModStart\" value=\"$ModStart\" />";
          echo "<tr $rowcolor><td class=\"ongl\">".translate("Link ID: ")."<b>$lid</b>";
          if ($numrowsAE>0)
             echo "&nbsp;&nbsp;<span class=\"rouge\">".translate("ERROR: This URL is already listed in the Database!")."</span>";
          echo "</td></tr>";
          echo "<tr $rowcolor><td>".translate("Author")." : $submitter <br /></td></tr>";
          echo "<tr $rowcolor><td>".translate("Title:")."<br /><input class=\"textbox\" type=\"text\" name=\"title\" value=\"$title\" size=\"50\" maxlength=\"100\" /><br /><br />";
          global $links_url;
          if ($links_url)
             echo "URL :<br /><input class=\"textbox\" type=\"text\" name=\"url\" value=\"$url\" size=\"50\" maxlength=\"100\" /> [<a href=\"$url\" target=\"_blank\" class=\"noir\">".translate("Visit")."</a>]<br /><br />";
          $result2=sql_query("select cid, title from ".$links_DB."links_categories order by title");
          echo translate("Category: ")."<select class=\"textbox_standard\" name=\"cat\">";
          while (list($ccid, $ctitle) = sql_fetch_row($result2)) {
             $sel = "";
             if ($cid==$ccid AND $sid==0) {
                $sel = "selected=\"selected\"";
             }
             echo "<option value=\"$ccid\" $sel>".aff_langue($ctitle)."</option>";
             $result3=sql_query("select sid, title from ".$links_DB."links_subcategories where cid='$ccid' order by title");
             while (list($ssid, $stitle) = sql_fetch_row($result3)) {
                   $sel = "";
                if ($sid==$ssid) {
                   $sel = "selected=\"selected\"";
                }
                echo "<option value=\"$ccid-$ssid\" $sel>".aff_langue($ctitle)." / ".aff_langue($stitle)."</option>";
             }

          }
          echo "</select>";
          global $links_topic;
          if ($links_topic) {
             echo "&nbsp;&nbsp;".translate("Topics")." : <select class=\"textbox_standard\" name=\"topicL\">";
             $toplist = sql_query("select topicid, topictext from ".$NPDS_Prefix."topics order by topictext");
             echo "<option value=\"\">".translate("All Topics")."</option>\n";
             while(list($topicid, $topics) = sql_fetch_row($toplist)) {
               if ($topicid==$topicid_card) { $sel = "selected=\"selected\" "; }
               echo "<option $sel value=\"$topicid\">".aff_langue($topics)."</option>\n";
               $sel = "";
             }
             echo "</select><br /><br />";
          }
          echo translate("Description: ")."<br /><textarea class=\"textbox\" name=\"xtext\" cols=\"60\" rows=\"10\" style=\"width: 100%;\">$description</textarea><br /><br />";
          echo aff_editeur("xtext","false");
          echo translate("Name: ")."<input class=\"textbox_standard\" type=\"text\" name=\"name\" size=\"40\" maxlength=\"100\" value=\"$name\" />&nbsp;&nbsp;";
          echo translate("Email: ")."<input class=\"textbox_standard\" type=\"text\" name=\"email\" size=\"40\" maxlength=\"100\" value=\"$email\" /><br /><br />";

          echo "<input type=\"hidden\" name=\"new\" value=\"1\" />";
          echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\" />";
          echo "<input type=\"hidden\" name=\"submitter\" value=\"$submitter\" />";
          echo "<input type=\"hidden\" name=\"op\" value=\"LinksAddLink\" />";
          echo "<input class=\"bouton_standard\" type=\"submit\" value=\"".translate("Add")."\" /> [ <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&amp;op=LinksDelNew&amp;lid=$lid\" class=\"rouge\">".translate("Delete")."</a> ]</form>";
          echo "</td></tr></table><br />";
       // Fin de list
    }

    // Add a New Link to Database
    $result = sql_query("select cid, title from ".$links_DB."links_categories");
    $numrows = sql_num_rows($result);
    if ($numrows>0) {
       echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n";
       echo translate("Add a New Link");
       echo "</td></tr></table>\n
       <table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\">\n";
       if ($adminform=="") {
          echo "<form method=\"post\" action=\"modules.php\" name=\"adminForm\">";
       } else {
          echo "<form method=\"post\" action=\"modules.php\">";
       }
       echo "<input type=\"hidden\" name=\"ModPath\" value=\"$ModPath\" />
       <input type=\"hidden\" name=\"ModStart\" value=\"$ModStart\" /><tr><td class=\"ongl\">";
       echo translate("Title:")."<br /><input class=\"textbox\" type=\"text\" name=\"title\" size=\"50\" maxlength=\"100\" /><br />";
       global $links_url;
       if ($links_url)
          echo "URL : <br /><input class=\"textbox\" type=\"text\" name=\"url\" size=\"50\" maxlength=\"100\" value=\"http://\" /><br /><br />";
       $result=sql_query("select cid, title from ".$links_DB."links_categories order by title");
       echo translate("Category: ")." <select class=\"textbox_standard\" name=\"cat\">";
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
          echo " ".translate("Topics")." : <select class=\"textbox_standard\" name=\"topicL\">";
          $toplist = sql_query("select topicid, topictext from ".$NPDS_Prefix."topics order by topictext");
          echo "<option valuer=\"\">".translate("All Topics")."</option>\n";
          while(list($topicid, $topics) = sql_fetch_row($toplist)) {
            echo "<option value=\"$topicid\">".aff_langue($topics)."</option>\n";
          }
          echo "</select>";
       }
       echo "<br /><br />".translate("Description: (255 characters max)")."<br /><textarea class=\"textbox\" name=\"xtext\" cols=\"60\" rows=\"10\" style=\"width: 100%;\"></textarea>";
       if ($adminform=="")
          echo aff_editeur("xtext","false");
       echo translate("Name: ")."<input class=\"textbox_standard\" type=\"text\" name=\"name\" size=\"30\" maxlength=\"60\" />&nbsp;&nbsp;
       ".translate("E-Mail: ")."<input class=\"textbox_standard\" type=\"text\" name=\"email\" size=\"30\" maxlength=\"60\" /><br />
       <input type=\"hidden\" name=\"op\" value=\"LinksAddLink\" />
       <input type=\"hidden\" name=\"new\" value=\"0\" />
       <input type=\"hidden\" name=\"lid\" value=\"0\" />
       <br /><input class=\"bouton_standard\" type=\"submit\" value=\"".translate("Add URL")."\" />
       </form></td></tr></table><br />";
    }

    // Add a New Main Category
    echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n";
    echo translate("Add a MAIN Category");
    echo "</td></tr></table>\n
    <table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\">\n
    <form method=\"post\" action=\"modules.php\">
    <input type=\"hidden\" name=\"ModPath\" value=\"$ModPath\" />
    <input type=\"hidden\" name=\"ModStart\" value=\"$ModStart\" /><tr><td class=\"ongl\">".translate("Name: ")."<br /><input class=\"textbox\" type=\"text\" name=\"title\" size=\"30\" maxlength=\"100\" /><br />
    ".translate("Description: ")."<br /><textarea class=\"textbox_no_mceEditor\" name=\"cdescription\" cols=\"60\" rows=\"10\" style=\"width: 100%;\"></textarea><br />
    <input type=\"hidden\" name=\"op\" value=\"LinksAddCat\" />
    <input class=\"bouton_standard\" type=\"submit\" value=\"".translate("Add")."\" /><br /></td></tr>
    </form></table><br />\n";


    // Modify Category
    $result = sql_query("select * from ".$links_DB."links_categories");
    $numrows = sql_num_rows($result);
    if ($numrows>0) {
       echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n";
       echo translate("Modify Category");
       echo "</td></tr></table>\n
       <table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\">\n";
       echo "<form method=\"post\" action=\"modules.php\">
       <input type=\"hidden\" name=\"ModPath\" value=\"$ModPath\" />
       <input type=\"hidden\" name=\"ModStart\" value=\"$ModStart\" /><tr><td class=\"ongl\">";
       $result=sql_query("select cid, title from ".$links_DB."links_categories order by title");
       echo translate("Category: ")." <select class=\"textbox_standard\" name=cat>";
       while(list($cid, $title) = sql_fetch_row($result)) {
           echo "<option value=\"$cid\">".aff_langue($title)."</option>";
           $result2=sql_query("select sid, title from ".$links_DB."links_subcategories where cid='$cid' order by title");
           while(list($sid, $stitle) = sql_fetch_row($result2)) {
              echo "<option value=\"$cid-$sid\">".aff_langue("$title / $stitle")."</option>";
           }
       }
       echo "</select>
       <input type=\"hidden\" name=\"op\" value=\"LinksModCat\" />&nbsp;
       <input class=\"bouton_standard\" type=\"submit\" value=\"".translate("Modify")."\" /><br /></td></tr>
       </form></table><br />\n";
    }

    // Add a New Sub-Category
    $result = sql_query("select * from ".$links_DB."links_categories");
    $numrows = sql_num_rows($result);
    if ($numrows>0) {
       echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n";
       echo translate("Add a SUB-Category");
       echo "</td></tr></table>\n
       <table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\">\n";
       echo "<form method=\"post\" action=\"modules.php\">
       <input type=\"hidden\" name=\"ModPath\" value=\"$ModPath\" />
       <input type=\"hidden\" name=\"ModStart\" value=\"$ModStart\" /><tr><td class=\"ongl\">
       ".translate("Name: ")."<input class=\"textbox_standard\" type=\"text\" name=\"title\" size=\"30\" maxlength=\"100\" style=\"width:70%;\" />&nbsp;".translate("in")."&nbsp;";

       $result=sql_query("select cid, title from ".$links_DB."links_categories order by title");
       echo "<select class=\"textbox_standard\" name=\"cid\">";
       while (list($ccid, $ctitle) = sql_fetch_row($result)) {
          echo "<option value=\"$ccid\">".aff_langue($ctitle)."</option>";
       }
       echo "</select>
       <input type=\"hidden\" name=\"op\" value=\"LinksAddSubCat\" />&nbsp;
       <input class=\"bouton_standard\" type=\"submit\" value=\"".translate("Add")."\" /><br /></td></tr>
       </form></table>\n";
    }
    closetable();
    include ("footer.php");
}

// ------ Links
function LinksAddLink($new, $lid, $title, $url, $cat, $description, $name, $email, $submitter, $topicL) {
    global $ModPath, $ModStart, $links_DB;
    // Check if Title exist
    if ($title=="") {
       include("header.php");
       opentable();
       echo "<br /><span class=\"rouge\">".translate("ERROR: You need to type a TITLE for your URL!")."</span><br /><br />";
       echo "[ <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath\" class=\"noir\">".translate("Go Back")."</a> ]<br />";
       closetable();
       include("footer.php");
       exit();
    }
    // Check if URL exist
    global $links_url;
    if (($url=="") and ($links_url==1)) {
       include("header.php");
       opentable();
       echo "<br /><span class=\"rouge\">".translate("ERROR: You need to type a URL for your URL!")."</span><br /><br />";
       echo "[ <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath\" class=\"noir\">".translate("Go Back")."</a> ]<br />";
       closetable();
       include("footer.php");
       exit();
    }
    // Check if Description exist
    if ($description=="") {
       include("header.php");
       opentable();
       echo "<br /><span class=\"rouge\">".translate("ERROR: You need to type a DESCRIPTION for your URL!")."</span><br /><br />";
       echo "[ <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath\" class=\"noir\">".translate("Go Back")."</a> ]<br />";
       closetable();
       include("footer.php");
       exit();
    }
    $cat = explode("-", $cat);
    if (!array_key_exists(1,$cat)) {
       $cat[1] = 0;
    }
    $title = stripslashes(FixQuotes($title));
    $url = stripslashes(FixQuotes($url));
    $description = stripslashes(FixQuotes($description));
    $name = stripslashes(FixQuotes($name));
    $email = stripslashes(FixQuotes($email));
    sql_query("insert into ".$links_DB."links_links values (NULL, '$cat[0]', '$cat[1]', '$title', '$url', '$description', now(), '$name', '$email', '0','$submitter',0,0,0,'$topicL')");
    include("header.php");
    opentable();
    echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n";
    echo translate("New Link added to the Database");
    echo "</td></tr></table>\n";
    echo "<br />";
    echo "[ <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath\" class=\"noir\">".translate("Go Back")."</a> ]<br />";
    closetable();
    if ($new==1) {
       sql_query("delete from ".$links_DB."links_newlink where lid='$lid'");
       if ($email!="") {
          global $sitename, $nuke_url;
          $subject = translate("Your Link at")." : $sitename";
          $message = translate("Hello")." $name :\n\n".translate("We approved your link submission for our search engine.")."\n\n".translate("Page Title: ")."$title\n".translate("Page URL: ")."<a href=\"$url\">$url</a>\n".translate("Description: ")."$description\n".translate("You can browse our search engine at:")." <a href=\"$nuke_url/modules.php?ModPath=links&ModStart=links\">$nuke_url/modules.php?ModPath=links&ModStart=links</a>\n\n".translate("Thanks for your submission!")."\n";
          include("signat.php");
          send_email($email, $subject, $message, "", false, "html");
      }
    }
    include("footer.php");
}
function LinksModLink($lid, $modifylinkrequest_adv_infos) {
    global $ModPath, $ModStart, $links_DB, $hlpfile;
    global $NPDS_Prefix;

    include ("header.php");

    echo "<script type=\"text/javascript\">\n";
    echo "//<![CDATA[\n";
    echo "function openwindow(){\n";
    echo " window.open (\"$hlpfile\",\"Help\",\"toolbar=no,location=no,directories=no,status=no,scrollbars=yes,resizable=no,copyhistory=no,width=600,height=400\");\n";
    echo "}\n";
    echo "//]]>\n";
    echo "</script>\n";

    $result = sql_query("select cid, sid, title, url, description, name, email, hits, topicid_card from ".$links_DB."links_links where lid='$lid'");
    opentable();

    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\"><tr class=\"header\"><td>\n";
    echo translate("Modify Links");
    echo "</td><td align=\"left\">[ <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModStart\" class=\"box\">".translate("Links Main")."</a> ]</td><td align=\"right\">[ <a href=\"javascript:openwindow();\" class=\"box\">".translate("Online Manual")."</a> ]";
    echo "</td></tr></table>\n";

    while (list($cid, $sid, $title, $url, $description, $name, $email, $hits, $topicid_card) = sql_fetch_row($result)) {
       $title = stripslashes($title); $description = stripslashes($description);
       $rowcolor = tablos();
       echo "<br /><table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\">\n";
       echo "<form action=\"modules.php\" method=\"post\" name=\"adminForm\">
       <input type=\"hidden\" name=\"ModPath\" value=\"$ModPath\" />
       <input type=\"hidden\" name=\"ModStart\" value=\"$ModStart\" />";
       echo "<tr $rowcolor><td class=\"ongl\">";
       echo translate("Link ID: ")."<b>$lid</b><br /></td></tr>";
       echo "<tr $rowcolor><td class=\"ongl\">".translate("Title:")."<br /><input class=\"textbox_standard\" type=\"text\" name=\"title\" value=\"$title\" size=\"50\" maxlength=\"100\" />";
       global $links_url;
       if (($links_url) or ($links_url==-1))
          echo "<br />URL : <br /><input class=\"textbox_standard\" type=\"text\" name=\"url\" value=\"$url\" size=\"50\" maxlength=\"100\" />&nbsp;[<a href=\"$url\" target=\"_blank\" class=\"noir\">".translate("Visit")."</a>]<br /><br />";
       $result2=sql_query("select cid, title from ".$links_DB."links_categories order by title");
       echo translate("Category: ")." <select class=\"textbox_standard\" name=\"cat\">";
       while (list($ccid, $ctitle) = sql_fetch_row($result2)) {
          $sel = "";
          if ($cid==$ccid AND $sid==0) {
             $sel = "selected=\"selected\"";
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
       echo "</select>";
       global $links_topic;
       if ($links_topic) {
          echo "&nbsp;&nbsp;".translate("Topics")." : <select class=\"textbox_standard\" name=\"topicL\">";
          $toplist = sql_query("select topicid, topictext from ".$NPDS_Prefix."topics order by topictext");
          echo "<option value=\"\">".translate("All Topics")."</option>\n";
          while(list($topicid, $topics) = sql_fetch_row($toplist)) {
              if ($topicid==$topicid_card) { $sel = "selected=\"selected\" "; }
              echo "<option $sel value=\"$topicid\">".aff_langue($topics)."</option>\n";
              $sel = "";
          }
          echo "</select>";
       }
       echo "&nbsp;&nbsp;".translate("Hits: ")."<input class=\"textbox_standard\" type=\"text\" name=\"hits\" value=\"$hits\" size=\"12\" maxlength=\"11\" /><br />";
       echo "<br /><br />";
       echo translate("Description: ")."<br /><textarea class=\"textbox\" name=\"xtext\" cols=\"60\" rows=\"10\" style=\"width: 100%;\">$description</textarea>";
       echo aff_editeur("xtext","false");
       echo "<br />";

       echo translate("Name: ")."<input class=\"textbox_standard\" type=\"text\" name=\"name\" size=\"50\" maxlength=\"100\" value=\"$name\" /> ";
       echo translate("E-Mail: ")."<input class=\"textbox_standard\" type=\"text\" name=\"email\" size=\"50\" maxlength=\"100\" value=\"$email\" /><br />";
       echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\" />";
       echo "<br /><input type=\"hidden\" name=\"op\" value=\"LinksModLinkS\" /><input class=\"bouton_standard\" type=\"submit\" value=\"".translate("Modify")."\" /> [ <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&op=LinksDelLink&lid=$lid\" class=\"rouge\">".translate("Delete")."</a> ]</form>";
       echo "<hr noshade=\"noshade\" class=\"ongl\" />";
       $resulted2 = sql_query("select adminid, editorialtimestamp, editorialtext, editorialtitle from ".$links_DB."links_editorials where linkid='$lid'");
       $recordexist = sql_num_rows($resulted2);
       if ($recordexist == 0) {
          echo translate("Add Editorial")." :<br />";
          echo "<form action=\"modules.php\" method=\"post\">
          <input type=\"hidden\" name=\"ModPath\" value=\"$ModPath\" />
          <input type=\"hidden\" name=\"ModStart\" value=\"$ModStart\" />";
          echo "<input type=\"hidden\" name=\"linkid\" value=\"$lid\" />";
          echo translate("Title")." : <input class=\"textbox\" type=\"text\" name=\"editorialtitle\" value=\"\" size=\"50\" maxlength=\"100\" /><br />";
          echo translate("Full Text")." :<br /><textarea class=\"textbox_no_mceEditor\" name=\"editorialtext\" cols=\"60\" rows=\"10\" style=\"width: 100%;\"></textarea><br /><br />";
          echo "<input type=\"hidden\" name=\"op\" value=\"LinksAddEditorial\" /><input class=\"bouton_standard\" type=\"submit\" value=\"".translate("Add")."\" /></form>";
       } else {
          list($adminid, $editorialtimestamp, $editorialtext, $editorialtitle) = sql_fetch_row($resulted2);
          $formatted_date=formatTimestamp($editorialtimestamp);
          echo translate("Modify Editorial")." : ".translate("Author")." : $adminid / $formatted_date<br /><br />";
          echo "<form action=\"modules.php\" method=\"post\">
          <input type=\"hidden\" name=\"ModPath\" value=\"$ModPath\" />
          <input type=\"hidden\" name=\"ModStart\" value=\"$ModStart\" />";
          echo "<input type=\"hidden\" name=\"linkid\" value=\"$lid\" />";
          echo translate("Title")." : <input class=\"textbox\" type=\"text\" name=\"editorialtitle\" value=\"$editorialtitle\" size=\"50\" maxlength=\"100\" /><br />";
          echo translate("Full Text")." :<br /><textarea class=\"textbox_no_mceEditor\" name=\"editorialtext\" cols=\"60\" rows=\"10\" style=\"width: 100%;\">$editorialtext</textarea><br /><br />";
          echo "</select><input type=\"hidden\" name=\"op\" value=\"LinksModEditorial\" /><input class=\"bouton_standard\" type=\"submit\" value=\"".translate("Modify")."\" />   [ <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&op=LinksDelEditorial&linkid=$lid\" class=\"rouge\">".translate("Delete")."</a> ]</form>";
       }
       echo "<hr noshade=\"noshade\" class=\"ongl\" />";
       $pos = strpos($ModPath, "/admin");
       $browse_key=$lid;
       include ("modules/sform/".substr($ModPath,0,$pos)."/link_maj.php");
   }
   echo "</td></tr></table>";
   closetable();
   include ("footer.php");
}
function LinksModLinkS($lid, $title, $url, $description, $name, $email, $hits, $cat, $topicL) {
    global $ModPath, $ModStart, $links_DB;
    $cat = explode("-", $cat);
    if (!array_key_exists(1,$cat)) {
       $cat[1] = 0;
    }
    $title = stripslashes(FixQuotes($title));
    $url = stripslashes(FixQuotes($url));
    $description = stripslashes(FixQuotes($description));
    $name = stripslashes(FixQuotes($name));
    $email = stripslashes(FixQuotes($email));
    sql_query("update ".$links_DB."links_links set cid='$cat[0]', sid='$cat[1]', title='$title', url='$url', description='$description', name='$name', email='$email', hits='$hits', submitter='$name', topicid_card='$topicL' where lid='$lid'");
    Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath&op=LinksModLink&lid=$lid");
}
function LinksDelLink($lid) {
    global $ModPath, $ModStart, $links_DB;
    $pos = strpos($ModPath, "/admin");
    $modifylinkrequest_adv_infos="Supprimer_MySql";
    include_once("modules/sform/".substr($ModPath,0,$pos)."/link_maj.php");
    // Cette fonction fait partie du formulaire de SFROM !
    Supprimer_function($lid);
    sql_query("delete from ".$links_DB."links_editorials where linkid='$lid'");
    sql_query("delete from ".$links_DB."links_links where lid='$lid'");
}
function LinksDelNew($lid) {
    global $ModPath, $ModStart, $links_DB;
    sql_query("delete from ".$links_DB."links_newlink where lid='$lid'");
    Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath");
}

// ----- Editorial
function LinksModEditorial($linkid, $editorialtitle, $editorialtext) {
    global $ModPath, $ModStart, $links_DB;
    $editorialtext = stripslashes(FixQuotes($editorialtext));
    sql_query("update ".$links_DB."links_editorials set editorialtext='$editorialtext', editorialtitle='$editorialtitle' where linkid='$linkid'");
    Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath&op=LinksModLink&lid=$linkid");
}
function LinksDelEditorial($linkid) {
    global $ModPath, $ModStart, $links_DB;
    sql_query("delete from ".$links_DB."links_editorials where linkid='$linkid'");
    Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath&op=LinksModLink&lid=$linkid");
}
function LinksAddEditorial($linkid, $editorialtitle, $editorialtext) {
    global $ModPath, $ModStart, $links_DB;
    $editorialtext = stripslashes(FixQuotes($editorialtext));
    global $aid;
    sql_query("insert into ".$links_DB."links_editorials values ('$linkid', '$aid', now(), '$editorialtext', '$editorialtitle')");
    Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath&op=LinksModLink&lid=$linkid");
}

// ----- Categories
function LinksAddSubCat($cid, $title) {
    global $ModPath, $ModStart, $links_DB;
    $result = sql_query("select cid from ".$links_DB."links_subcategories where title='$title' AND cid='$cid'");
    $numrows = sql_num_rows($result);
    if ($numrows>0) {
        include("header.php");
        opentable();
        echo "<br /><span class=\"rouge\">".translate("ERROR: The SubCategory")." $title ".translate("already exist!")."</span><br /><br />";
        echo "[ <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath\" class=\"noir\">".translate("Go Back")."</a>]<br />";
        closetable();
        include("footer.php");
    } else {
        sql_query("insert into ".$links_DB."links_subcategories values (NULL, '$cid', '$title')");
        Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath");
    }
}
function LinksModCat($cat) {
    global $ModPath, $ModStart, $links_DB;
    include ("header.php");
    $cat = explode("-", $cat);
    if (!array_key_exists(1,$cat)) {
       $cat[1] = 0;
    }
    opentable();
    echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n";
    echo translate("Modify Category");
    echo "</td></tr></table>\n";
    echo "<br />";

    if ($cat[1]==0) {
        $result=sql_query("select title, cdescription from ".$links_DB."links_categories where cid='$cat[0]'");
        list($title,$cdescription) = sql_fetch_row($result);
        $cdescription = stripslashes($cdescription);
        echo "<form method=\"post\" action=\"modules.php\">
        <input type=\"hidden\" name=\"ModPath\" value=\"$ModPath\" />
        <input type=\"hidden\" name=\"ModStart\" value=\"$ModStart\" />
        <p class=\"ongl\">
        ".translate("Name: ")."<br /><input class=\"textbox\" type=\"text\" name=\"title\" value=\"$title\" size=\"51\" maxlength=\"50\" /><br /><br />
        ".translate("Description: ")."<br /><textarea class=\"textbox_no_mceEditor\" name=\"cdescription\" cols=\"60\" rows=\"10\" style=\"width: 100%;\">$cdescription</textarea></p><br />
        <input type=\"hidden\" name=\"sub\" value=\"0\" />
        <input type=\"hidden\" name=\"cid\" value=\"$cat[0]\" />
        <input type=\"hidden\" name=\"op\" value=\"LinksModCatS\" />
        <table border=\"0\"><tr><td><input class=\"bouton_standard\" type=\"submit\" value=\"".translate("Save Changes")."\" /></form></td><td>
        <form method=\"post\" action=\"modules.php\">
        <input type=\"hidden\" name=\"ModPath\" value=\"$ModPath\" />
        <input type=\"hidden\" name=\"ModStart\" value=\"$ModStart\" />
        <input type=\"hidden\" name=\"sub\" value=\"0\" />
        <input type=\"hidden\" name=\"cid\" value=\"$cat[0]\" />
        <input type=\"hidden\" name=\"op\" value=\"LinksDelCat\" />
        <input type=\"submit\" class=\"bouton_standard\" value=\"".translate("Delete")."\" /></form></td></tr></table>";
    } else {
        $result=sql_query("select title from ".$links_DB."links_categories where cid='$cat[0]'");
        list($ctitle) = sql_fetch_row($result);
        $result2=sql_query("select title from ".$links_DB."links_subcategories where sid='$cat[1]'");
        list($stitle) = sql_fetch_row($result2);
        echo "<form method=\"post\" action=\"modules.php\">
        <input type=\"hidden\" name=\"ModPath\" value=\"$ModPath\" />
        <input type=\"hidden\" name=\"ModStart\" value=\"$ModStart\" />
        <span class=\"ongl\">
        ".translate("Category Name: ").aff_langue($ctitle)."<br /><br />
        ".translate("Sub-Category Name: ")."<input class=\"textbox\" type=\"text\" name=\"title\" value=\"$stitle\" size=\"251\" maxlength=\"250\" /></span><br />
        <input type=\"hidden\" name=\"sub\" value=\"1\" />
        <input type=\"hidden\" name=\"cid\" value=\"$cat[0]\" />
        <input type=\"hidden\" name=\"sid\" value=\"$cat[1]\" />
        <input type=\"hidden\" name=\"op\" value=\"LinksModCatS\" />
        <table border=\"0\"><tr><td>
        <input type=\"submit\" class=\"bouton_standard\" value=\"".translate("Save Changes")."\"></form></td><td>";
        echo "<form method=\"post\" action=\"modules.php\">
        <input type=\"hidden\" name=\"ModPath\" value=\"$ModPath\" />
        <input type=\"hidden\" name=\"ModStart\" value=\"$ModStart\" />
        <input type=\"hidden\" name=\"sub\" value=\"1\" />
        <input type=\"hidden\" name=\"cid\" value=\"$cat[0]\" />
        <input type=\"hidden\" name=\"sid\" value=\"$cat[1]\" />
        <input type=\"hidden\" name=\"op\" value=\"LinksDelCat\" />
        <input type=\"submit\" class=\"bouton_standard\" value=\"".translate("Delete")."\" /></form></td></tr></table>";
    }
    closetable();
    include("footer.php");
}
function LinksAddCat($title, $cdescription) {
    global $ModPath, $ModStart, $links_DB;
    $result = sql_query("select cid from ".$links_DB."links_categories where title='$title'");
    $numrows = sql_num_rows($result);
    if ($numrows>0) {
        include("header.php");
        opentable();
        echo "<br /><span class=\"rouge\">".translate("ERROR: The Category")." $title ".translate("already exist!")."</span><br /><br />";
        echo "[ <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath\" class=\"noir\">".translate("Go Back")."</a>]<br /><br />";
        closetable();
        include("footer.php");
    } else {
        sql_query("insert into ".$links_DB."links_categories values (NULL, '$title', '$cdescription')");
        Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath");
    }
}
function LinksModCatS($cid, $sid, $sub, $title, $cdescription) {
    global $ModPath, $ModStart, $links_DB;
    if ($sub==0) {
        sql_query("update ".$links_DB."links_categories set title='$title', cdescription='$cdescription' where cid='$cid'");
    } else {
        sql_query("update ".$links_DB."links_subcategories set title='$title' where sid='$sid'");
    }

    Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath");
}
function LinksDelCat($cid, $sid, $sub, $ok=0) {
    global $ModPath, $ModStart, $links_DB;
    if ($ok==1) {
       $pos = strpos($ModPath, "/admin");
       $modifylinkrequest_adv_infos="Supprimer_MySql";
       include_once("modules/sform/".substr($ModPath,0,$pos)."/link_maj.php");
       if ($sub>0) {
          $result=sql_query("select lid from ".$links_DB."links_links where sid='$sid'");
          while (list($lid)=sql_fetch_row($result)) {
             LinksDelLink($lid);
          }
          sql_query("delete from ".$links_DB."links_subcategories where sid='$sid'");
          sql_query("delete from ".$links_DB."links_links where sid='$sid'");
       } else {
          $result=sql_query("select lid from ".$links_DB."links_links where cid='$cid'");
          while (list($lid)=sql_fetch_row($result)) {
             LinksDelLink($lid);
          }
          sql_query("delete from ".$links_DB."links_categories where cid='$cid'");
          sql_query("delete from ".$links_DB."links_subcategories where cid='$cid'");
       }
       Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath");
    } else {
       include("header.php");
       opentable();
       echo "<br /><span class=\"rouge\">".translate("WARNING: Are you sure you want to delete this Category and ALL its Links?")."</span><br /><br />";
       echo "[ <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&op=LinksDelCat&cid=$cid&sid=$sid&sub=$sub&ok=1\" class=\"rouge\">".translate("Yes")."</a> | <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath\" class=\"noir\">".translate("No")."</a> ]<br /><br />";
       closetable();
       include("footer.php");
   }
}

// ----- Broken and Changes
function LinksListModRequests() {
    global $ModPath, $ModStart, $links_DB;
    global $NPDS_Prefix;

    $resultX = sql_query("select requestid, lid, cid, sid, title, url, description, modifysubmitter, topicid_card from ".$links_DB."links_modrequest where brokenlink=0 order by requestid");
    $totalmodrequests = sql_num_rows($resultX);
    if ($totalmodrequests==0) {
       Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath");
    }
    include ("header.php");
    opentable();
    echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n";
    echo translate("User Link Modification Requests")." ($totalmodrequests)";
    echo "</td></tr></table>\n";
    echo "<br />";
    while (list($requestid, $lid, $cid, $sid, $title, $url, $description, $modifysubmitter, $topicid_card)=sql_fetch_row($resultX)) {
       $rowcolor = tablos();
       $rowcolor = tablos();
       $result2 = sql_query("select cid, sid, title, url, description, submitter, topicid_card from ".$links_DB."links_links where lid='$lid'");
       list($origcid, $origsid, $origtitle, $origurl, $origdescription, $owner, $oritopicid_card)=sql_fetch_row($result2);
       $result3 = sql_query("select title from ".$links_DB."links_categories where cid='$cid'");
       $result4 = sql_query("select title from ".$links_DB."links_subcategories where cid='$cid' and sid='$sid'");
       $result5 = sql_query("select title from ".$links_DB."links_categories where cid='$origcid'");
       $result6 = sql_query("select title from ".$links_DB."links_subcategories where cid='$origcid' and sid='$origsid'");
       $result7 = sql_query("select email from ".$NPDS_Prefix."users where uname='$modifysubmitter'");
       $result8 = sql_query("select email from ".$NPDS_Prefix."users where uname='$owner'");
       $result9 = sql_query("select topictext from ".$NPDS_Prefix."topics where topicid='$oritopicid_card'");
       $result9b = sql_query("select topictext from ".$NPDS_Prefix."topics where topicid='$topicid_card'");
       list($cidtitle)=sql_fetch_row($result3);
       list($sidtitle)=sql_fetch_row($result4);
       list($origcidtitle)=sql_fetch_row($result5);
       list($origsidtitle)=sql_fetch_row($result6);
       list($modifysubmitteremail)=sql_fetch_row($result7);
       list($owneremail)=sql_fetch_row($result8);
       list($oritopic)=sql_fetch_row($result9);
       list($topic)=sql_fetch_row($result9b);
            $title = stripslashes($title);
            $description = stripslashes($description);
            if ($owner=="") { $owner="administration"; }
            if ($origsidtitle=="") { $origsidtitle= "-----"; }
            if ($sidtitle=="") { $sidtitle= "-----"; }
            echo "
            <table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\">
              <tr $rowcolor>
               <td colspan=\"3\">
               <table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\">
                 <tr><td valign=\"top\" class=\"header\">".translate("Original")."</td></tr>
                 <tr><td valign=\"top\"><b>".translate("Description:")."</b><br />$origdescription</td></tr>
                 <tr><td valign=\"top\"><b>".translate("Title:")."</b> $origtitle</td></tr>
                 <tr><td valign=\"top\"><b>".translate("URL:")."</b> <a href=\"$origurl\" target=\"_blank\" class=\"noir\">$origurl</a></td></tr>";
                 global $links_topic;
                 if ($links_topic)
                    echo "<tr><td valign=\"top\"><b>".translate("Topic")." :</b> $oritopic</td></tr>";
                 echo "<tr><td valign=\"top\"><b>".translate("Cat:")."</b> $origcidtitle</td></tr>
                 <tr><td valign=\"top\"><b>".translate("Subcat:")."</b> $origsidtitle</td></tr>
               </table>
               </td>
              </tr>
              <tr $rowcolor>
               <td colspan=\"3\">
               <table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\">
                 <tr><td valign=\"top\" class=\"header\">".translate("Proposed")."</td>
                 <tr><td valign=\"top\"><b>".translate("Description:")."</b><br />$description</td></tr>
                 <tr><td valign=\"top\"><b>".translate("Title:")."</b> $title</td></tr>
                 <tr><td valign=\"top\"><b>".translate("URL:")."</b> <a href=\"$url\" target=\"_blank\" class=\"noir\">$url</a></td></tr>";
                 global $links_topic;
                 if ($links_topic)
                    echo "<tr><td valign=\"top\"><b>".translate("Topic")." :</b> $topic</td></tr>";
                 echo "<tr><td valign=\"top\"><b>".translate("Cat:")."</b> $cidtitle</td></tr>
                 <tr><td valign=\"top\"><b>".translate("Subcat:")."</b> $sidtitle</td></tr>
               </table>
               </td>
              </tr>
              <tr>";
                if ($modifysubmitteremail=="")
                   echo "<td align=\"left\" class=\"ongl\" width=\"30%\">".translate("Submitter")." :  $modifysubmitter</td>";
                else
                   echo "<td align=\"left\" class=\"ongl\" width=\"30%\">".translate("Submitter")." :  <a href=\"mailto:$modifysubmitteremail\" class=\"noir\">$modifysubmitter</a></td>";
                if ($owneremail=="")
                   echo "<td align=\"center\" class=\"ongl\" width=\"30%\">".translate("Owner")." :  $owner</td>";
                else
                   echo "<td align=\"center\" class=\"ongl\" width=\"30%\">".translate("Owner")." : <a href=\"mailto:$owneremail\" class=\"noir\">$owner</a></td>";
                echo "<td align=\"right\" class=\"ongl\">( <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&op=LinksChangeModRequests&requestid=$requestid\" class=\"noir\">".translate("Accept")."</a> / <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&op=LinksChangeIgnoreRequests&requestid=$requestid\" class=\"noir\">".translate("Ignore")."</a> )</td>
              </tr>
            </table><br />";
    }
    closetable();
    sql_free_result;
    include ("footer.php");
}

// ----- Broken
function LinksListBrokenLinks() {
    global $ModPath, $ModStart, $links_DB;
    global $NPDS_Prefix;

    $resultBrok = sql_query("select requestid, lid, modifysubmitter from ".$links_DB."links_modrequest where brokenlink=1 order by requestid");
    $totalbrokenlinks = sql_num_rows($resultBrok);
    if ($totalbrokenlinks==0) {
       Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath");
    } else {
       include ("header.php");
       opentable();
       echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n";
       echo translate("User Reported Broken Links")." ($totalbrokenlinks)";
       echo "</td></tr></table>\n";
       echo "<br />
       ".translate("Ignore (Deletes all <b>requests</b> for a given link)")."<br /><br />
       ".translate("Delete (Deletes <b>broken link</b> and <b>requests</b> for a given link)")."<br /><br />";
       echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\">";
       echo "<tr>
             <td class=\"ongl\">".translate("Links")."</td>
             <td class=\"ongl\">".translate("Submitter")."</td>
             <td class=\"ongl\">".translate("Owner")."</td>
             <td class=\"ongl\" align=\"center\">".translate("Ignore")."</td>
             <td class=\"ongl\" align=\"center\">".translate("Delete")."</td>
             </tr>";
       while (list($requestid, $lid, $modifysubmitter)=sql_fetch_row($resultBrok)) {
          $rowcolor = tablos();
          $result2 = sql_query("select title, url, submitter from ".$links_DB."links_links where lid='$lid'");
          if ($modifysubmitter != '$anonymous') {
             $result3 = sql_query("select email from ".$NPDS_Prefix."users where uname='$modifysubmitter'");
             list($email)=sql_fetch_row($result3);
          }
          list($title, $url, $owner)=sql_fetch_row($result2);
          $result4 = sql_query("select email from ".$NPDS_Prefix."users where uname='$owner'");
          list($owneremail)=sql_fetch_row($result4);
          echo "<tr $rowcolor>
                <td nowrap=\"nowrap\"><a href=\"$url\" class=\"noir\" target=\"_blank\">$title</a>
                </td>";
          if ($email=='') { echo "<td>$modifysubmitter"; }
          else { echo "<td><a href=\"mailto:$email\" class=\"noir\">$modifysubmitter</a>"; }
          echo "</td>";
          if ($owneremail=='') { echo "<td>$owner"; }
          else { echo "<td><a href=\"mailto:$owneremail\" class=\"noir\">$owner</a>"; }
          echo "</td>
                <td align=\"center\"><a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&op=LinksIgnoreBrokenLinks&lid=$lid\" class=\"noir\">".translate("Ignore")."</a>
                </td>
                <td align=\"center\"><a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&op=LinksDelBrokenLinks&lid=$lid\" class=\"rouge\">".translate("Delete")."</a>
                </td>
                </tr>";
       }
       echo "</table>";
       closetable();
       include ("footer.php");
    }
}
function LinksDelBrokenLinks($lid) {
    global $ModPath, $ModStart, $links_DB;
    sql_query("delete from ".$links_DB."links_modrequest where lid='$lid'");
    LinksDelLink($lid);
    Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath&op=LinksListBrokenLinks");
}

function LinksIgnoreBrokenLinks($lid) {
    global $ModPath, $ModStart, $links_DB;
    sql_query("delete from ".$links_DB."links_modrequest where lid='$lid' and brokenlink=1");
    Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath&op=LinksListBrokenLinks");
}

// ----- Change Links
function LinksChangeModRequests($Xrequestid) {
    global $ModPath, $ModStart, $links_DB;
    $result = sql_query("select requestid, lid, cid, sid, title, url, description, topicid_card from ".$links_DB."links_modrequest where requestid='$Xrequestid'");
    while (list($requestid, $lid, $cid, $sid, $title, $url, $description, $topicid_card)=sql_fetch_row($result)) {
       $title = stripslashes($title);
       $description = stripslashes($description);
       sql_query("UPDATE ".$links_DB."links_links SET cid=$cid, sid=$sid, title='$title', url='$url', description='$description', topicid_card='$topicid_card' WHERE lid='$lid'");
    }
    sql_query("delete from ".$links_DB."links_modrequest where requestid='$Xrequestid'");
    Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath&op=LinksListModRequests");
}
function LinksChangeIgnoreRequests($requestid) {
    global $ModPath, $ModStart, $links_DB;
    sql_query("delete from ".$links_DB."links_modrequest where requestid='$requestid'");
    Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath&op=LinksListModRequests");
}

settype($op,'string');
switch ($op) {
       case "LinksDelNew":
          LinksDelNew($lid);
          break;

       case "LinksAddCat":
          LinksAddCat($title, $cdescription);
          break;

       case "LinksAddSubCat":
          LinksAddSubCat($cid, $title);
          break;

       case "LinksAddLink":
          if ($xtext=="") $xtext=$description;
          LinksAddLink($new, $lid, $title, $url, $cat, $xtext, $name, $email, $submitter, $topicL);
          break;

       case "LinksAddEditorial":
          LinksAddEditorial($linkid, $editorialtitle, $editorialtext);
          break;

       case "LinksModEditorial":
          LinksModEditorial($linkid, $editorialtitle, $editorialtext);
          break;

       case "LinksDelEditorial":
          LinksDelEditorial($linkid);
          break;

       case "LinksListBrokenLinks":
          LinksListBrokenLinks();
          break;

       case "LinksDelBrokenLinks":
          LinksDelBrokenLinks($lid);
          break;

       case "LinksIgnoreBrokenLinks":
          LinksIgnoreBrokenLinks($lid);
          break;

       case "LinksListModRequests":
          LinksListModRequests();
          break;

       case "LinksChangeModRequests":
          LinksChangeModRequests($requestid);
          break;

       case "LinksChangeIgnoreRequests":
          LinksChangeIgnoreRequests($requestid);
          break;

       case "LinksDelCat":
          LinksDelCat($cid, $sid, $sub, $ok);
          break;

       case "LinksModCat":
          LinksModCat($cat);
          break;

       case "LinksModCatS":
          LinksModCatS($cid, $sid, $sub, $title, $cdescription);
          break;

       case "LinksModLink":
       case "modifylinkrequest":
          settype($modifylinkrequest_adv_infos,'string');
          LinksModLink($lid, $modifylinkrequest_adv_infos);
          break;

       case "LinksModLinkS":
          LinksModLinkS($lid, $title, $url, $xtext, $name, $email, $hits, $cat, $topicL);
          break;

       case "LinksDelLink":
          LinksDelLink($lid);
          Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath");
          break;

       default:
          links();
          break;
}
?>
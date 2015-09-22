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
global $NPDS_Prefix;

   $x=0;
   while (list($lid, $url, $title, $description, $time, $hits, $topicid_card, $xcid, $xsid)=sql_fetch_row($result)) {
      //compare the description with "nohtml description"
      if (!empty($query)) {
         $affichT=false; $affichD=false;
         if (strcasecmp($title,strip_tags($title))==0) {
            if ($title!=preg_replace("#$query#", "<b>$query</b>", $title)) {
               $title=preg_replace("#$query#", "<b>$query</b>", $title);
               $affichT=true;
            }
         } else {
            $affichT=true;
         }
         if (strcasecmp($description,strip_tags($description))==0) {
            if ($description!=preg_replace("#$query#", "<b>$query</b>", $description)) {
               $description=preg_replace("#$query#", "<b>$query</b>", $description);
               $affichD=true;
            }
         } else {
            $affichD=true;
         }
         if ($affichT or $affichD)
            $affich=true;
         else
            $affich=false;
      } else {
         $affich=true;
      }

      if ($affich) {
         $title = stripslashes($title); $description = stripslashes($description);
         echo "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\" class=\"header\"><tr><td>";
         echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\"><tr class=\"lignb\"><td colspan=\"2\">";
         if ($ibid=theme_image("links/urlgo.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/links/urlgo.gif";}
         if ($url=="") {
            echo "<img src=\"$imgtmp\" border=\"0\" alt=\"\" /> ".aff_langue($title);
         } else {
            echo "<img src=\"$imgtmp\" border=\"0\" alt=\"\" /> <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&amp;op=visit&amp;lid=$lid\" target=\"_blank\" class=\"noir\">".aff_langue($title)."</a>";
         }
         settype($datetime,'string');
         newlinkgraphic($datetime, $time);
         echo "</td>";
         $colspanX=2;
         if (!empty($xcid)) {
            $result3 = sql_query("select title from ".$links_DB."links_categories where cid='$xcid'");
            $result4 = sql_query("select title from ".$links_DB."links_subcategories where sid='$xsid'");
            list($ctitle) = sql_fetch_row($result3);
            list($stitle) = sql_fetch_row($result4);
            if ($stitle=="") {$slash = "";}else{$slash = "/";}
            echo "<td align=\"right\" nowrap=\"nowrap\">".translate("Category: ")."<b>".aff_langue($ctitle)."</b> $slash <b>".aff_langue($stitle)."</b></td>";
            $colspanX=3;
         }
         echo "<tr class=\"lignb\" height=\"4\"><td colspan=\"$colspanX\">&nbsp;</td></tr>";
         echo "<tr class=\"lignb\"><td colspan=\"$colspanX\">";
            echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">";
            echo "<tr><td width=\"15%\">".translate("Description: ")."</td><td wrap=\"wrap\">".aff_langue($description)."</td></tr>";
            global $links_topic;
            if ($links_topic and $topicid_card!=0) {
               list($topicLX)=sql_fetch_row(sql_query("select topictext from ".$NPDS_Prefix."topics where topicid='$topicid_card'"));
               echo "<tr><td>".translate("Topics")." :</td><td><b>$topicLX</b></td></tr>";
            }
            echo "</table>";
         echo "</td></tr>";
         $datetime=formatTimestampShort($time);
         echo "<tr class=\"lignb\"><td colspan=\"$colspanX\"><hr noshade=\"noshade\" class=\"ongl\" />";
            echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">";
            echo "<tr><td>".translate("Added on: ")."$datetime ";
            if ($url!="") {
               echo translate("Hits: ");
               global $popular;
               if ($ibid=theme_image("links/popular.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/links/popular.gif";}
               if ($hits>$popular) {
                  echo "<b>$hits</b> <img src=\"$imgtmp\" border=\"0\" alt=\"\" align=\"center\" />";
               } else {
                  echo $hits;
               }
               echo "</td>";

               echo "<td align=\"right\" nowrap=\"nowrap\"><a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&amp;op=brokenlink&amp;lid=$lid\" class=\"noir\"><span style=\"font-size: 10px;\">".translate("Report Broken Link")."</a></td>";
            } else {
               echo "</td>";
            }
            echo "</tr><tr>";
            echo "<td align=\"left\">";
            // Advance infos via the class sform.php
            autorise_mod($lid,true);
            $browse_key=$lid;
            include ("modules/sform/$ModPath/link_detail.php");

            detecteditorial($lid, urlencode($title));
            echo "</td><td align=\"right\">";
            echo "<a href=\"print.php?DB=$links_DB&amp;lid=$lid\"><img src=\"";
            if ($ibid=theme_image("box/print.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/print.gif";}
            echo "$imgtmp\" border=\"0\" align=\"center\" alt=\"".translate("Printer Friendly Page")."\" /></a>";
            echo "</td></tr></table>";
         echo "</td></tr><tr height=\"1\"><td class=\"lignb\" colspan=\"$colspanX\"></td></tr></table>";
         echo "</td></tr></table><br />";
        $x++;
     }
   }
   sql_free_result();
?>
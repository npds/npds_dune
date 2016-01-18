<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2015 by Philippe Brunier   */
/*                                                                      */
/* New Links.php Module with SFROM extentions                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],"modules.php")) { die(); }
global $NPDS_Prefix;
echo '
   <table class="table table-bordered table-striped table-hover">
      <thead>
      </thead>
      <tbody>';


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
         settype($datetime,'string');
         echo '
         <tr>
            <td>';
         if ($url=='') {
            echo '<h4 class="text-muted"><i class="fa fa-external-link"></i>&nbsp;'.aff_langue($title);
         } else {
            echo '<h4><a href="modules.php?ModStart='.$ModStart.'&amp;ModPath='.$ModPath.'&amp;op=visit&amp;lid='.$lid.'" target="_blank" ><i class="fa fa-external-link"></i>&nbsp;'.aff_langue($title).'</a>';
         }
         echo newlinkgraphic($datetime, $time).'</h4>';

         if (!empty($xcid)) {
            $result3 = sql_query("SELECT title FROM ".$links_DB."links_categories WHERE cid='$xcid'");
            $result4 = sql_query("SELECT title FROM ".$links_DB."links_subcategories WHERE sid='$xsid'");
            list($ctitle) = sql_fetch_row($result3);
            list($stitle) = sql_fetch_row($result4);
            if ($stitle=='') {$slash = '';}else{$slash = '/';}
            echo translate("Category: ")."<b>".aff_langue($ctitle)."</b> $slash <b>".aff_langue($stitle)."</b>";
         }
            echo '<p>'.aff_langue($description).'</p>';
            global $links_topic;
            if ($links_topic and $topicid_card!=0) {
               list($topicLX)=sql_fetch_row(sql_query("SELECT topictext FROM ".$NPDS_Prefix."topics WHERE topicid='$topicid_card'"));
               echo "".translate("Topics")." :</td><td><b>$topicLX</b>";
            }
         $datetime=formatTimestampShort($time);
            echo translate("Added on: ")."$datetime ";
            if ($url!='') {
               echo translate("Hits: ");
               global $popular;
               if ($ibid=theme_image("links/popular.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/links/popular.gif";}
               if ($hits>$popular) {
                  echo "<b>$hits</b> <img src=\"$imgtmp\" border=\"0\" alt=\"\" align=\"center\" />";
               } else {
                  echo $hits;
               }
               echo '<a href="modules.php?ModStart='.$ModStart.'&amp;ModPath='.$ModPath.'&amp;op=brokenlink&amp;lid='.$lid.'" title="'.translate("Report Broken Link").'" data-toggle="tooltip"><i class="fa fa-chain-broken fa-lg"></i></a>';
            }
            
            // Advance infos via the class sform.php
            autorise_mod($lid,true);
            $browse_key=$lid;
            include ("modules/sform/$ModPath/link_detail.php");

            detecteditorial($lid, urlencode($title));
            echo '
            <a href="print.php?DB='.$links_DB.'&amp;lid='.$lid.'" title="'.translate("Printer Friendly Page").'" data-toggle="tooltip"><i class="fa fa-print"></i></a>
            </td>
         </tr>';
        $x++;
     }
   }
   sql_free_result();
   echo '
      </tbody>
   </table>';
?>
<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2023 by Philippe Brunier   */
/*                                                                      */
/* New Links.php Module with SFROM extentions                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],'modules.php')) die();
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
         } else
            $affichT=true;
         if (strcasecmp($description,strip_tags($description))==0) {
            if ($description!=preg_replace("#$query#", "<b>$query</b>", $description)) {
               $description=preg_replace("#$query#", "<b>$query</b>", $description);
               $affichD=true;
            }
         } else
            $affichD=true;
         $affich = ($affichT or $affichD) ? true : false ;
      } else
         $affich=true;

      if ($affich) {
         $title = stripslashes($title); $description = stripslashes($description);
         settype($datetime,'string');
         echo '
         <div class="card mb-3">
            <div class="card-body ibid_descr">';
         if ($url=='')
            echo '
               <h4 class="text-muted"><i class="fas fa-external-link-alt"></i>&nbsp;'.aff_langue($title);
         else
            echo '
               <h4><a href="modules.php?ModStart='.$ModStart.'&amp;ModPath='.$ModPath.'&amp;op=visit&amp;lid='.$lid.'" target="_blank" ><i class="fas fa-external-link-alt"></i>&nbsp;'.aff_langue($title).'</a>';

         echo '&nbsp;'.newlinkgraphic($datetime, $time).'</h4>';

         if (!empty($xcid)) {
            $result3 = sql_query("SELECT title FROM ".$links_DB."links_categories WHERE cid='$xcid'");
            $result4 = sql_query("SELECT title FROM ".$links_DB."links_subcategories WHERE sid='$xsid'");
            list($ctitle) = sql_fetch_row($result3);
            list($stitle) = sql_fetch_row($result4);
            if ($stitle=='') $slash = ''; else $slash = '/';
            echo translate("Catégorie : ")."<strong>".aff_langue($ctitle)."</strong> $slash <b>".aff_langue($stitle)."</b>";
         }
         global $links_topic;
         if ($links_topic and $topicid_card!=0) {
            list($topicLX)=sql_fetch_row(sql_query("SELECT topictext FROM ".$NPDS_Prefix."topics WHERE topicid='$topicid_card'"));
            echo '<br />'.translate("Sujets").' : <strong>'.$topicLX.'</strong>';
         }
         echo '
               <div class="ibid_descr "><p>'.aff_langue($description).'</p></div>';
         if ($url!='') {
            echo '<div class="d-flex justify-content-between">';
            global $popular;
            if ($hits>$popular)
               echo '<span class="text-success"><i class="fa fa-star-o fa-lg"></i></span><span class="ms-auto">'.translate("Hits: ").'<span class=" badge bg-secondary">'.$hits.'</span></span>';
            else
               echo '<span class="ms-auto">'.translate("Nb hits : ").'<span class=" badge bg-secondary">'.$hits.'</span></span>';
            echo '</div>';
         }
         echo '
            </div>
            <div class="card-footer d-flex justify-content-start">';
         $datetime=formatTimestampShort($time);
         echo '
               <span class="small">'.translate("Ajouté le : ").$datetime.'</span>
               <span class="ms-auto">';
         if ($url!='')
            echo '
               <a class="me-3" href="modules.php?ModStart='.$ModStart.'&amp;ModPath='.$ModPath.'&amp;op=brokenlink&amp;lid='.$lid.'" title="'.translate("Rapporter un lien rompu").'" data-bs-toggle="tooltip"><i class="fas fa-unlink fa-lg"></i></a>';
         // Advance infos via the class sform.php
         $browse_key=$lid;
         include ("modules/sform/$ModPath/link_detail.php");
         detecteditorial($lid, urlencode($title));
         echo '
               <a class="me-3" href="print.php?DB='.$links_DB.'&amp;lid='.$lid.'" title="'.translate("Page spéciale pour impression").'" data-bs-toggle="tooltip"><i class="fa fa-print fa-lg"></i></a>';
         autorise_mod($lid,true);
         echo '
               </span>
            </div>
         </div>';
         $x++;
      }
   }
   sql_free_result($result);
   if (isset($result2))
      sql_free_result($result2);
   if (isset($result3))
      sql_free_result($result3);

?>
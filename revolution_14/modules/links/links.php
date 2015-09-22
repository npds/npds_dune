<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2011 by Philippe Brunier   */
/*                                                                      */
/* New Links.php Module with SFROM extentions                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (strstr($ModPath,"..") || strstr($ModStart,"..") || stristr($ModPath, "script") || stristr($ModPath, "cookie") || stristr($ModPath, "iframe") || stristr($ModPath, "applet") || stristr($ModPath, "object") || stristr($ModPath, "meta") || stristr($ModStart, "script") || stristr($ModStart, "cookie") || stristr($ModStart, "iframe") || stristr($ModStart, "applet") || stristr($ModStart, "object") || stristr($ModStart, "meta")) {
   die();
}
global $links_DB;
global $NPDS_Prefix;

include_once("modules/$ModPath/links.conf.php");
if ($links_DB=="") {
   $links_DB=$NPDS_Prefix;
}
//Menu bootstrapped phr
function menu($mainlink) {
   global $ModPath, $ModStart, $links_anonaddlinklock;
	echo '<ul class="nav nav-pills">';
   if ($mainlink>0) {
      echo "<li><a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath\" class=\"box\">".translate("Links Main")."</a></li>";
   }
   if (autorisation($links_anonaddlinklock))
      echo "<li><a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&amp;op=AddLink\" class=\"box\">".translate("Add Link")."</a></li>";
   echo "<li><a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&amp;op=NewLinks\" class=\"box\">".translate("New links")."</a></li>";
   echo '</ul>';
}
//recherche bootstrapped à affiner phr
function SearchForm() {
	global $ModPath, $ModStart;
	global $NPDS_Prefix;
	echo '<h3>Recherche</h3>';
	echo '<form class="form-horizontal" role="form" action="modules.php?ModStart='.$ModStart.'&amp;ModPath='.$ModPath.'&amp;op=search" method="post">';

	global $links_topic;
	if ($links_topic) {   
	echo '<div class="form-group">
			<div class="col-sm-4">
				<label class="control-label">Sélectionner un sujet</label>
			</div>
			<div class="col-sm-6">
				<select class="form-control" name="topicL">';
      $toplist = sql_query("select topicid, topictext from ".$NPDS_Prefix."topics order by topictext");
		echo '<option value="">'.translate("All Topics").'</option>';
		while (list($topicid, $topics) = sql_fetch_row($toplist)) {
        echo '<option value="'.$topicid.'>'.$topics.'</option>';
      }
      echo '</select>';
	echo '</div>
	  </div>';  
   }
	echo '<div class="form-group">
			<div class="col-sm-4">
				<label class="control-label">Votre demande</label>
			</div>	
			<div class="col-sm-6">
				<input class="form-control" type="text" name="query" />
			</div>
		</div>';   
   
   
	echo '<div class="form-group">
			<div class="col-sm-offset-4 col-sm-1">
				<input class="btn btn-primary" type="submit" value="'.translate("Search").'" />
			</div>
		</div>';
	echo '</form>';
}

function mainheader() {
   $mainlink = 1;
   menu($mainlink);
   SearchForm();
}

function autorise_mod($lid,$aff) {
   global $ModPath, $ModStart, $links_DB;
   global $NPDS_Prefix;
   global $user, $admin;

   if ($admin) {
      $Xadmin = base64_decode($admin);
      $Xadmin = explode(":", $Xadmin);
      $result = sql_query("select radminlink, radminsuper from ".$NPDS_Prefix."authors where aid='$Xadmin[0]'");
      list($radminlink, $radminsuper) = sql_fetch_row($result);
      if (($radminlink==1) or ($radminsuper==1)) {
         if ($aff) {
            echo '<a href="modules.php?ModStart='.$ModStart.'&amp;ModPath='.$ModPath.'&amp;op=modifylinkrequest&amp;lid='.$lid.'&amp;author=-9"><span class="text-warning">'.translate("Modify").'</span></a>';
         }
         return(true);
      } else {
         return(false);
      }
   } elseif ($user!="") {
      global $cookie;
      $resultX=sql_query("select submitter from ".$links_DB."links_links where submitter='$cookie[1]' and lid='$lid'");
      list($submitter) = sql_fetch_row($resultX);
      if ($submitter==$cookie[1]) {
         if ($aff) {
            echo "<a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&amp;op=modifylinkrequest&amp;lid=$lid&amp;author=$cookie[1]\"><span style=\"font-size: 10px;\">".translate("Modify")."</span></a> ";
         }
         return(true);
      } else {
         return(false);
      }
   } else {
      return(false);
   }
}

function index() {
   global $ModPath, $ModStart, $links_DB;
   include ("modules/$ModPath/links.conf.php");
   include("header.php");
   // Include cache manager
   global $SuperCache;
   if ($SuperCache) {
      $cache_obj = new cacheManager();
      $cache_obj->startCachingPage();
   } else {
      $cache_obj = new SuperCacheEmpty();
   }
   if (($cache_obj->genereting_output==1) or ($cache_obj->genereting_output==-1) or (!$SuperCache)) {
      
      $mainlink = 0;
      menu($mainlink);
      SearchForm();

      $filen="modules/$ModPath/links.ban_01.php";
      if (file_exists($filen)) {include($filen);}

      echo "<table class=\"table table-bordered\"><tr>";
      $result=sql_query("select cid, title, cdescription from ".$links_DB."links_categories order by title");
      if ($result) {
         $count = 0;
         while (list($cid, $title, $cdescription) = sql_fetch_row($result)) {
            $cresult = sql_query("select lid from ".$links_DB."links_links where cid='$cid'");
            $cnumrows = sql_num_rows($cresult);
            echo "<td valign=\"top\" width=\"200\" class=\"ongl\">
            <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&amp;op=viewlink&amp;cid=$cid\">".aff_langue($title)."</a> <span style=\"font-size: 10px;\">[$cnumrows]</span>";
            categorynewlinkgraphic($cid);
            if ($cdescription) {
                echo "<br /><span style=\"font-size: 10px;\">".aff_langue($cdescription)."</span><br />";
            } else {
                echo "<br />";
            }
            $result2 = sql_query("select sid, title from ".$links_DB."links_subcategories where cid='$cid' order by title $subcat_limit");
            $space = 0;
            while (list($sid, $stitle) = sql_fetch_row($result2)) {
               if ($space>0) {
                  echo "<br />";
               }
               $cresult3 = sql_query("select lid from ".$links_DB."links_links where sid='$sid'");
               $cnumrows="-: ".sql_num_rows($cresult3);
               if ($cnumrows=="-: 0") {$cnumrows="";}
               echo "&nbsp;<a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&amp;op=viewslink&amp;sid=$sid\">".aff_langue($stitle)."</a> <span style=\"font-size: 10px;\">$cnumrows</span>";
               $space++;
            }
            if ($count<1) {
               echo "</td><td width=\"20\">&nbsp;</td>";
            }
            $count++;
            if ($count==2) {
               echo "</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr>";
               $count = 0;
            }
         }
      }
      echo "</td></tr></table>";
      $result=sql_query("select lid from ".$links_DB."links_links");
      if ($result) {
         $numrows = sql_num_rows($result);
         echo "<br /><br /><p align=\"center\"><span style=\"font-size: 10px;\">".translate("There are")." <b>$numrows</b> ".translate("Links in our Database")." -::- ";
         if ($ibid=theme_image("links/newred.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/links/newred.gif";}
         echo "<img src=\"$imgtmp\" alt=\"".translate("New Links in this Category Added Today")."\" border=\"0\" /> -:-";
         if ($ibid=theme_image("links/newgreen.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/links/newgreen.gif";}
         echo "<img src=\"$imgtmp\" alt=\"".translate("New Links in this Category Added in the last 3 days")."\" border=\"0\" /> -:-";
         if ($ibid=theme_image("links/newblue.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/links/newblue.gif";}
         echo "<img src=\"$imgtmp\" alt=\"".translate("New Links in this Category Added this week")."\" border=\"0\" />";
         echo "</span></p>";
      }
      
   }
   if ($SuperCache) {
      $cache_obj->endCachingPage();
   }
   global $admin;
   if ($admin) {
      
      $result = sql_query("select requestid from ".$links_DB."links_modrequest where brokenlink=1");
      if ($result) {
         $totalbrokenlinks = sql_num_rows($result);
         $result2 = sql_query("select requestid from ".$links_DB."links_modrequest where brokenlink=0");
         $totalmodrequests = sql_num_rows($result2);
         $result = sql_query("select lid from ".$links_DB."links_newlink");
         $num = sql_num_rows($result);
         echo "<p align=\"center\"><span style=\"font-size: 10px;\">-: ".translate("Waiting Links")." : $num / $totalbrokenlinks / $totalmodrequests";
         echo " :: <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath/admin\">Admin</a> :: Ref Tables => <b>$links_DB</b> :-</span></p>";
      } else {
         echo "<p align=\"center\"><span style=\"font-size: 10px;\"> -: [ <a href=\"modules.php?ModStart=create_tables&amp;ModPath=$ModPath/admin/\">".translate("Create")."</a> Tables : $links_DB ] :-</span></p>";
      }
      
   }
   include("footer.php");
}

function FooterOrderBy($cid, $sid, $orderbyTrans, $linkop) {
   global $ModPath, $ModStart;
   echo "<p align=\"center\"><span style=\"font-size: 10px;\">".translate("Sort links by")." : ";
   if ($linkop=="viewlink") {
      echo translate("Title")." (<a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&amp;op=viewlink&amp;cid=$cid&amp;orderby=titleA\">A</a>\<a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&amp;op=viewlink&amp;cid=$cid&amp;orderby=titleD\">D</a>)
          ".translate("Date")." (<a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&amp;op=viewlink&amp;cid=$cid&amp;orderby=dateA\">A</a>\<a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&amp;op=viewlink&amp;cid=$cid&amp;orderby=dateD\">D</a>)";
   } else {
      echo translate("Title")." (<a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&amp;op=viewslink&amp;sid=$sid&amp;orderby=titleA\">A</a>\<a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&amp;op=viewslink&amp;sid=$sid&amp;orderby=titleD\">D</a>)
          ".translate("Date")." (<a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&amp;op=viewslink&amp;sid=$sid&amp;orderby=dateA\">A</a>\<a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&amp;op=viewslink&amp;sid=$sid&amp;orderby=dateD\">D</a>)";
   }
   echo "<br />".translate("Sites currently sorted by")." : $orderbyTrans</span></p>";
}

function viewlink($cid, $min, $orderby, $show) {
   global $ModPath, $ModStart, $links_DB;
   global $admin, $perpage;

   include("header.php");
   // Include cache manager
   global $SuperCache;
   if ($SuperCache) {
      $cache_obj = new cacheManager();
      $cache_obj->startCachingPage();
   } else {
      $cache_obj = new SuperCacheEmpty();
   }
   if (($cache_obj->genereting_output==1) or ($cache_obj->genereting_output==-1) or (!$SuperCache)) {
      
      if (!isset($max)) $max=$min+$perpage;
      mainheader();

      $filen="modules/$ModPath/links.ban_02.php";
      if (file_exists($filen)) {include($filen);}
      $result=sql_query("select title from ".$links_DB."links_categories where cid='$cid'");
      list($title) = sql_fetch_row($result);
      echo "<table class=\"table table-bordered\"><tr><td class=\"header\">\n";
      echo aff_langue($title)." : ".translate("SubCategories");
      echo "</td></tr></table>\n";

      $subresult=sql_query("select sid, title from ".$links_DB."links_subcategories where cid='$cid' order by title");
      $numrows = sql_num_rows($subresult);
      if ($numrows != 0) {
         echo "<table class=\"table table-bordered\">";
         while(list($sid, $title) = sql_fetch_row($subresult)) {
            
            $result2 = sql_query("select lid from ".$links_DB."links_links where sid='$sid'");
            $numrows="-: ".sql_num_rows($result2);
            if ($numrows=="-: 0") {$numrows="";}
            echo "<tr><td width=\"5%\">&nbsp;</td><td class=\"ongl\"><a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&amp;op=viewslink&amp;sid=$sid\">".aff_langue($title)."</a> $numrows</td></tr>";
         }
         echo "</table>";
      }
      $orderbyTrans = convertorderbytrans($orderby);
      settype($min,"integer");
      settype($perpage,"integer");
      $result=sql_query("select lid, url, title, description, date, hits, topicid_card, cid, sid from ".$links_DB."links_links where cid='$cid' AND sid=0 order by $orderby limit $min,$perpage");
      $fullcountresult=sql_query("select lid, title, description, date, hits from ".$links_DB."links_links where cid='$cid' AND sid=0");
      $totalselectedlinks = sql_num_rows($fullcountresult);
      echo "<br />\n";
      $link_fiche_detail="";
      include_once("modules/$ModPath/links-view.php");
      echo "<br />\n";

      $orderby = convertorderbyout($orderby);
      //Calculates how many pages exist.  Which page one should be on, etc...
      $linkpagesint = ($totalselectedlinks / $perpage);
      $linkpageremainder = ($totalselectedlinks % $perpage);

      if ($linkpageremainder != 0) {
         $linkpages = ceil($linkpagesint);
         if ($totalselectedlinks < $perpage) {
            $linkpageremainder = 0;
         }
      } else {
         $linkpages = $linkpagesint;
      }
      //Page Numbering
      if ($linkpages!=1 && $linkpages!=0) {
         echo "<p align=\"center\">";
         echo translate("Select page")." :&nbsp;&nbsp;";
         $prev=$min-$perpage;
         $counter = 1;
         $currentpage = ($max / $perpage);
         while ($counter<=$linkpages ) {
            $cpage = $counter;
            $mintemp = ($perpage * $counter) - $perpage;
            if ($counter == $currentpage) echo "<span class=\"rouge\">$counter</span>&nbsp;";
            else echo "<a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&amp;op=viewlink&amp;cid=$cid&amp;min=$mintemp&amp;orderby=$orderby&amp;show=$show\">$counter</a>&nbsp;";
            $counter++;
         }
      }
      echo "</p><br />";
      if (isset($sid))
         FooterOrderBy($cid, $sid, $orderbyTrans, "viewlink");
      
   }
   if ($SuperCache) {
      $cache_obj->endCachingPage();
   }
   include("footer.php");
}

function viewslink($sid, $min, $orderby, $show) {
   global $ModPath, $ModStart, $links_DB;
   global $admin, $perpage;

   include("header.php");
   // Include cache manager
   global $SuperCache;
   if ($SuperCache) {
      $cache_obj = new cacheManager();
      $cache_obj->startCachingPage();
   } else {
      $cache_obj = new SuperCacheEmpty();
   }
   if (($cache_obj->genereting_output==1) or ($cache_obj->genereting_output==-1) or (!$SuperCache)) {
      
      mainheader();

      $filen="modules/$ModPath/links.ban_03.php";
      if (file_exists($filen)) {include($filen);}

      if (!isset($max)) $max=$min+$perpage;

      $result = sql_query("select cid, title from ".$links_DB."links_subcategories where sid='$sid'");
      list($cid, $stitle) = sql_fetch_row($result);

      $result2 = sql_query("select cid, title from ".$links_DB."links_categories where cid='$cid'");
      list($cid, $title) = sql_fetch_row($result2);

      echo "<table class=\"table table-bordered\"><tr><td class=\"header\">\n";
      echo "<a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath\" class=\"box\">".translate("Main")."</a> / <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&amp;op=viewlink&amp;cid=$cid\" class=\"box\">".aff_langue($title)."</a> / ".aff_langue($stitle);
      echo "</td></tr></table>";

      $orderbyTrans = convertorderbytrans($orderby);
      settype($min,"integer");
      settype($perpage,"integer");
      $result=sql_query("select lid, url, title, description, date, hits, topicid_card, cid, sid from ".$links_DB."links_links where sid='$sid' order by $orderby limit $min,$perpage");
      $fullcountresult=sql_query("select lid, title, description, date, hits from ".$links_DB."links_links where sid='$sid'");
      $totalselectedlinks = sql_num_rows($fullcountresult);
      echo "<br />\n";
      $link_fiche_detail="";
      include_once("modules/$ModPath/links-view.php");
      echo "<br />\n";

      $orderby = convertorderbyout($orderby);
      //Calculates how many pages exist.  Which page one should be on, etc...
      $linkpagesint = ($totalselectedlinks / $perpage);
      $linkpageremainder = ($totalselectedlinks % $perpage);

      if ($linkpageremainder != 0) {
         $linkpages = ceil($linkpagesint);
         if ($totalselectedlinks < $perpage) {
            $linkpageremainder = 0;
         }
      } else {
         $linkpages = $linkpagesint;
      }
      //Page Numbering
      if ($linkpages!=1 && $linkpages!=0) {
         echo "<p align=\"center\">";
         echo translate("Select page")." :&nbsp;&nbsp;";
         $prev=$min-$perpage;
         $counter = 1;
         $currentpage = ($max / $perpage);
         while ($counter<=$linkpages ) {
            $cpage = $counter;
            $mintemp = ($perpage * $counter) - $perpage;
            if ($counter == $currentpage) echo "<font class=\"rouge\">$counter</font>&nbsp;";
            else echo "<a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&amp;op=viewslink&amp;sid=$sid&amp;min=$mintemp&amp;orderby=$orderby&amp;show=$show\">$counter</a>&nbsp;";
            $counter++;
         }
      }
      echo "</p><br />";
      FooterOrderBy($cid, $sid, $orderbyTrans, "viewslink");
      
   }
   if ($SuperCache) {
      $cache_obj->endCachingPage();
   }
   include("footer.php");
}

function fiche_detail ($Xlid) {
    global $ModPath, $ModStart;
    include("header.php");
    // Include cache manager
    global $SuperCache;
    if ($SuperCache) {
       $cache_obj = new cacheManager();
       $cache_obj->startCachingPage();
    } else {
       $cache_obj = new SuperCacheEmpty();
    }
    if (($cache_obj->genereting_output==1) or ($cache_obj->genereting_output==-1) or (!$SuperCache)) {
       
       settype($xlid,'integer');
       $browse_key=$Xlid;
       $link_fiche_detail="fiche_detail";
       $inter="cid";
       include ("modules/sform/links/link_detail.php");
       
    }
    if ($SuperCache) {
       $cache_obj->endCachingPage();
    }
    include("footer.php");
}

function categorynewlinkgraphic($cat) {
    global $OnCatNewLink, $locale, $links_DB;
    if ($OnCatNewLink=="1") {
       $newresult = sql_query("select date from ".$links_DB."links_links where cid='$cat' order by date desc limit 1");
       list($time)=sql_fetch_row($newresult);
       if (isset($ime)) {
          setlocale (LC_TIME, aff_langue($locale));
          preg_match('#^(\d{4})-(\d{1,2})-(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$#', $time, $datetime);
          $count = round((time()- mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]))/86400,0);
          popgraphics($count);
       }
    }
}

function popgraphics($count) {
   echo "&nbsp;";
   if ($ibid=theme_image("links/newred.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/links/newred.gif";}
   if ($count<1) echo "<img src=\"$imgtmp\" alt=\"".translate("New Links in this Category Added Today")."\" border=\"0\" />";
   if ($ibid=theme_image("links/newgreen.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/links/newgreen.gif";}
   if ($count<=3 && $count>=1) echo "<img src=\"$imgtmp\" alt=\"".translate("New Links in this Category Added in the last 3 days")."\" border=\"0\" />";
   if ($ibid=theme_image("links/newblue.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/links/newblue.gif";}
   if ($count<=7 && $count>3) echo "<img src=\"$imgtmp\" alt=\"".translate("New Links in this Category Added this week")."\" border=\"0\" />";
}

function newlinkgraphic($datetime, $time) {
   global $locale;
   setlocale (LC_TIME, aff_langue($locale));
   preg_match('#^(\d{4})-(\d{1,2})-(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$#', $time, $datetime);
   $count = round((time()- mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]))/86400,0);
   popgraphics($count);
}

function detecteditorial($lid, $ttitle) {
   global $ModPath, $ModStart, $links_DB;
   $resulted2 = sql_query("select adminid from ".$links_DB."links_editorials where linkid='$lid'");
   $recordexist = sql_num_rows($resulted2);
   if ($recordexist != 0) echo "<a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&amp;op=viewlinkeditorial&amp;lid=$lid&amp;ttitle=$ttitle\">".translate("EDITO")."</a> ";
}

//Reusable Link Sorting Functions
function convertorderbyin($orderby) {
    $orderbyIn = "title ASC";
    if ($orderby == "titleA")                       $orderbyIn = "title ASC";
    if ($orderby == "dateA")                        $orderbyIn = "date ASC";
    if ($orderby == "titleD")                       $orderbyIn = "title DESC";
    if ($orderby == "dateD")                        $orderbyIn = "date DESC";
    return $orderbyIn;
}

function convertorderbytrans($orderby) {
    $orderbyTrans = translate("Title (A to Z)");
    if ($orderby == "title ASC")                    $orderbyTrans = translate("Title (A to Z)");
    if ($orderby == "title DESC")                   $orderbyTrans = translate("Title (Z to A)");
    if ($orderby == "date ASC")                     $orderbyTrans = translate("Date (Old Links Listed First)");
    if ($orderby == "date DESC")                    $orderbyTrans = translate("Date (New Links Listed First)");
    return $orderbyTrans;
}

function convertorderbyout($orderby) {
    $orderbyOut = "titleA";
    if ($orderby == "title ASC")                   $orderbyOut = "titleA";
    if ($orderby == "date ASC")                    $orderbyOut = "dateA";
    if ($orderby == "title DESC")                  $orderbyOut = "titleD";
    if ($orderby == "date DESC")                   $orderbyOut = "dateD";
    return $orderbyOut;
}

function visit($lid) {
    global $links_DB;
    sql_query("update ".$links_DB."links_links set hits=hits+1 where lid='$lid'");
    $result = sql_query("select url from ".$links_DB."links_links where lid='$lid'");
    list($url) = sql_fetch_row($result);
    Header("Location: $url");
}

function viewlinkeditorial($lid, $ttitle) {
    global $ModPath, $ModStart, $links_DB;
    include("header.php");
    
    mainheader();

    $result=sql_query("SELECT adminid, editorialtimestamp, editorialtext, editorialtitle FROM ".$links_DB."links_editorials WHERE linkid = '$lid'");
    $recordexist = sql_num_rows($result);

    $displaytitle = stripslashes($ttitle);
    echo "<table class=\"table table-bordered\"><tr><td>\n";
    echo translate("EDITO")." : ".aff_langue($displaytitle);
    echo "</td></tr></table>\n";
    echo "<br />\n";
    if ($recordexist!= 0) {
       while (list($adminid, $editorialtimestamp, $editorialtext, $editorialtitle)=sql_fetch_row($result)) {
          $editorialtitle = stripslashes($editorialtitle); $editorialtext = stripslashes($editorialtext);
          $formatted_date=formatTimestamp($editorialtimestamp);
          echo "<table class=\"table table-bordered\">
                <tr><td align=\"center\">
                <b>'".aff_langue($editorialtitle)."'</b><br /><br />
                ".translate("Editorial by")." $adminid - $formatted_date</td></tr>
                <tr><td align=\"left\"><hr noshade=\"noshade\" class=\"ongl\" />".aff_langue($editorialtext)."</td></tr></table>";
       }
    } else {
       echo "<br /><p align=\"center\">".translate("No editorial is currently available for this website.")."</p><br />";
    }
    echo "<br />";
    $result = sql_query("select url from ".$links_DB."links_links where lid='$lid'");
    list($url) = sql_fetch_row($result);
    if ($url!="")
       echo "<a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&amp;op=visit&amp;lid=$lid\" target=\"_blank\">".translate("Visit this Website")."</a>";
    
    sql_free_result();
    include("footer.php");
}

function formatTimestampShort($time) {
   global $datetime, $locale, $gmt;
   setlocale (LC_TIME, aff_langue($locale));
   preg_match('#^(\d{4})-(\d{1,2})-(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$#', $time, $datetime);
   $datetime = strftime("".translate("linksdatestring")."", mktime($datetime[4]+$gmt,$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
   if (cur_charset!="utf-8") {
      $datetime = ucfirst($datetime);
   }
   return($datetime);
}

settype($op,'string');
switch ($op) {
    case "menu":
        menu($mainlink);
        break;

    case "AddLink":
        include_once("modules/$ModPath/links-1.php");
        AddLink();
        break;
    case "Add":
        include_once("modules/$ModPath/links-1.php");
        settype($asb_question,'string');
        settype($asb_reponse,'string');
        Add($title, $url, $name, $cat, $xtext, $name, $email, $topicL, $asb_question, $asb_reponse);
        break;

    case "NewLinks":
        include_once("modules/$ModPath/links-2.php");
        if (!isset($newlinkshowdays)) {$newlinkshowdays = 7;}
        NewLinks($newlinkshowdays);
        break;
    case "NewLinksDate":
        include_once("modules/$ModPath/links-2.php");
        NewLinksDate($selectdate);
        break;

    case "viewlink":
        if (!isset($min)) $min=0;
        if (isset($orderby)) $orderby = convertorderbyin($orderby); else $orderby = "title ASC";
        if (isset($show)) $perpage = $show; else $show=$perpage;
        viewlink($cid, $min, $orderby, $show);
        break;
    case "viewslink":
        if (!isset($min)) $min=0;
        if (isset($orderby)) $orderby = convertorderbyin($orderby); else $orderby = "title ASC";
        if (isset($show)) $perpage = $show; else $show=$perpage;
        viewslink($sid, $min, $orderby, $show);
        break;

    case "brokenlink":
        include_once("modules/$ModPath/links-3.php");
        brokenlink($lid);
        break;
    case "brokenlinkS":
        include_once("modules/$ModPath/links-3.php");
        brokenlinkS($lid, $modifysubmitter);
        break;

    case "modifylinkrequest":
        include_once("modules/$ModPath/links-3.php");
        settype($modifylinkrequest_adv_infos,'string');
        modifylinkrequest($lid, $modifylinkrequest_adv_infos, $author);
        break;
    case "modifylinkrequestS":
        include_once("modules/$ModPath/links-3.php");
        modifylinkrequestS($lid, $cat, $title, $url, $xtext, $modifysubmitter, $topicL);
        break;

    case "visit":
        visit($lid);
        break;

    case "search":
        include_once("modules/$ModPath/links-1.php");
        $offset=10;
        if (!isset($min)) $min=0;
        if (!isset($max)) $max=$min+$offset;
        links_search($query, $topicL, $min, $max, $offset);
        break;

    case "viewlinkeditorial":
        viewlinkeditorial($lid, $ttitle);
        break;
    case "fiche_detail":
        fiche_detail ($lid);
        break;

    default:
        index();
        break;
}
?>
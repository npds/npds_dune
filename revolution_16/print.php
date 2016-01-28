<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion")) {
   include ("mainfile.php");
}

function PrintPage($oper, $DB, $nl, $sid) {
    global $user,$cookie, $theme,$Default_Theme, $language, $site_logo, $sitename, $datetime, $nuke_url, $site_font, $Titlesitename;
    global $NPDS_Prefix;

    $aff=true;
    if ($oper=='news') {
       $xtab=news_aff("libre","where sid='$sid'",1,1);
       list($sid, $catid, $aid, $title, $time, $hometext, $bodytext, $comments, $counter, $topic, $informant, $notes) = $xtab[0];
       if ($topic!='') {
          $result2=sql_query("SELECT topictext FROM ".$NPDS_Prefix."topics WHERE topicid='$topic'");
          list($topictext) = sql_fetch_row($result2);
       } else {
          $aff=false;
       }
    }
    if ($oper=='archive') {
       $xtab=news_aff("archive","WHERE sid='$sid'",1,1);
       list($sid, $catid, $aid, $title, $time, $hometext, $bodytext, $comments, $counter, $topic, $informant, $notes) = $xtab[0];
       if ($topic!="") {
          $result2=sql_query("SELECT topictext FROM ".$NPDS_Prefix."topics WHERE topicid='$topic'");
          list($topictext) = sql_fetch_row($result2);
       } else {
          $aff=false;
       }
    }
    if ($oper=="links") {
       $DB=removeHack(stripslashes(htmlentities(urldecode($DB),ENT_NOQUOTES,cur_charset)));
       $result=sql_query("SELECT url, title, description, date FROM ".$DB."links_links WHERE lid='$sid'");
       list($url, $title, $description, $time)=sql_fetch_row($result);
       $title = stripslashes($title); $description = stripslashes($description);
    }   
    if ($oper=="static") {
       if (preg_match('#^[a-z0-9_\.-]#i',$sid) and !stristr($sid,".*://") and !stristr($sid,"..") and !stristr($sid,"../") and !stristr($sid, "script") and !stristr($sid, "cookie") and !stristr($sid, "iframe") and  !stristr($sid, "applet") and !stristr($sid, "object") and !stristr($sid, "meta"))  {
          if (file_exists("static/$sid")) {
             ob_start();
                include ("static/$sid");
                $remp=ob_get_contents();
             ob_end_clean();
             if ($DB)
                $remp=meta_lang(aff_code(aff_langue($remp)));
             if ($nl)
               $remp=nl2br(str_replace(" ","&nbsp;",htmlentities($remp,ENT_QUOTES,cur_charset)));
             $title=$sid;
          } else {
             $aff=false;
          }
       } else {
         $remp="<p align=\"center\" class=\"rouge\">".translate("Please enter information according to the specifications")."</p><br />";
         $aff=false;
       }
    }
    if ($aff==true) {
       $Titlesitename="NPDS - ".translate("Printer Friendly Page")." / ".$title;
       if (isset($time))
          formatTimestamp($time);
       include("meta/meta.php");
       if (isset($user)) {
          if ($cookie[9]=="") $cookie[9]=$Default_Theme;
          if (isset($theme)) $cookie[9]=$theme;
          $tmp_theme=$cookie[9];
          if (!$file=@opendir("themes/$cookie[9]")) {
             $tmp_theme=$Default_Theme;
          }
       } else {
          $tmp_theme=$Default_Theme;
       }
       echo import_css($tmp_theme, $language, $site_font, "","");
       echo "
       </head>
       <body style=\"background-color: #FFFFFF; background-image: none;\">
       <table border=\"0\"><tr><td>
       <table border=\"0\" width=\"640\" cellpadding=\"0\" cellspacing=\"1\" style=\"background-color: #000000;\"><tr><td>
       <table border=\"0\" width=\"640\" cellpadding=\"20\" cellspacing=\"1\" style=\"background-color: #FFFFFF;\"><tr><td>";
       echo "<p align=\"center\">";
       $pos = strpos($site_logo, "/");
       if ($pos)
          echo "<img src=\"$site_logo\" border=\"0\" alt=\"\" />";
       else
          echo "<img src=\"images/$site_logo\" border=\"0\" alt=\"\" />";

       echo "<br /><br /><b>".aff_langue($title)."</b><br /><br />";
       if (($oper=="news") or ($oper=="archive")) {
          $hometext=meta_lang(aff_code(aff_langue($hometext)));
          $bodytext=meta_lang(aff_code(aff_langue($bodytext)));
          echo "<span style=\"font-size: 10px;\"><b>".translate("Date:")."</b> $datetime :: <b>".translate("Topic:")."</b> ".aff_langue($topictext)."<br /><br />
          </span></p>$hometext<br /><br />";
          if ($bodytext!='') {
             echo "$bodytext<br /><br />";
          }
          echo meta_lang(aff_code(aff_langue($notes)));
          if ($oper=="news") {
             echo "</td></tr><tr><td><br /><br /><br /><hr noshade=\"noshade\" class=\"ongl\" /><br />
             <p align=\"center\">".translate("This article comes from")." $sitename<br /><br />
             ".translate("The URL for this story is:")."
             <a href=\"$nuke_url/article.php?sid=$sid\">$nuke_url/article.php?sid=$sid</a></p>";
          } else {
             echo "</td></tr><tr><td><br /><br /><br /><hr noshade=\"noshade\" class=\"ongl\" /><br />
             <p align=\"center\">".translate("This article comes from")." $sitename<br /><br />
             ".translate("The URL for this story is:")."
             <a href=\"$nuke_url/article.php?sid=$sid&amp;archive=1\">$nuke_url/article.php?sid=$sid&amp;archive=1</a></p>";
          }
       }
       if ($oper=="links") {
          echo "<span style=\"font-size: 10px;\"><b>".translate("Date:")."</b> $datetime";
          if ($url!="") {
             echo " :: <b>".translate("Links")." : </b> $url<br /><br />";
          }
          echo "</span></p>".aff_langue($description);
          echo "</td></tr><tr><td><br /><br /><br /><hr noshade=\"noshade\" class=\"ongl\" /><br />
          <p align=\"center\">".translate("This article comes from")." $sitename<br /><br />
          <a href=\"$nuke_url\">$nuke_url</a></p>";
       }
       if ($oper=="static") {
          echo "</p><span style=\"font-size: 10px;\">".$remp."</span>";
          echo "</td></tr><tr><td><br /><br /><br /><hr noshade=\"noshade\" class=\"ongl\" /><br />
          <p align=\"center\">".translate("This article comes from")." $sitename<br /><br />
          <a href=\"$nuke_url/static.php?op=$sid&npds=1\">$nuke_url/static.php?op=$sid&npds=1</a></p>";
       }
       echo "</td></tr></table></td></tr></table></td></tr></table></body></html>";
    } else {
       header("location: index.php");
    }
}
if (!empty($sid)) {
   $tab=explode(":",$sid);
   if ($tab[0]=="static") {
      settype ($metalang, 'integer');
      settype ($nl, 'integer');
      PrintPage("static", $metalang, $nl, $tab[1]);
   } else {
      settype ($sid, 'integer');
      if (!$archive) {
         PrintPage("news", "", "", $sid);
      } else {
         PrintPage("archive", "", "", $sid);
      }
   }
} elseif (!empty($lid)) {
   settype ($lid, "integer");
   PrintPage("links",$DB, "", $lid);
} else {
   header("location: index.php");
}
?>
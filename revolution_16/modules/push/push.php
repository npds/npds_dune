<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/* Base on pda Addon by Christopher Bradford (csb@wpsf.com)             */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion")) {
   include ("mainfile.php");
}

include("modules/push/language/push-lang-$language.php");
include("push.conf.php");

function push_menu() {
   global $options, $push_br;

   echo "document.write('<p align=\"center\" style=\"font-size: 11px;\">');\n";
   if (substr($options,0,1)==1) { echo "document.write('[<a href=\"#article\">Article(s)</a>]$push_br');\n"; }
   if (substr($options,1,1)==1) { echo "document.write('[<a href=\"#faq\">Faqs</a>]$push_br');\n"; }
   if (substr($options,2,1)==1) { echo "document.write('[<a href=\"#poll\">".push_translate("Poll")."</a>]$push_br');\n"; }
   if (substr($options,3,1)==1) { echo "document.write('[<a href=\"#member\">".push_translate("Member(s)")."</a>]$push_br');\n"; }
   if (substr($options,4,1)==1) { echo "document.write('[<a href=\"#link\">".push_translate("Web links")."</a>]');\n"; }
   echo "document.write('</p>');\n";
}

function index() {
   global $options;

   push_header("menu");
   push_menu();

   if (substr($options,0,1)==1) { push_news(); }
   if (substr($options,1,1)==1) {
      echo "document.write('<hr width=\"100%\" noshade=\"noshade\" />');\n";
      push_faq();
   }
   if (substr($options,2,1)==1) {
      echo "document.write('<hr width=\"100%\" noshade=\"noshade\" />');\n";
      push_poll();
   }
   if (substr($options,3,1)==1) {
      echo "document.write('<hr width=\"100%\" noshade=\"noshade\" />');\n";
      push_members();
   }

   if (substr($options,4,1)==1) {
      echo "document.write('<hr width=\"100%\" noshade=\"noshade\" />');\n";
      push_links();
   }

   push_menu();
   echo "document.write('</td></tr><tr><td align=\"center\">');\n";
   echo "document.write('<a href=\"http://www.npds.org\">NPDS Push System</a>');\n";
   push_footer();
}

function push_news() {
   global $push_news_limit;
   global $NPDS_Prefix;

   settype($push_news_limit,"integer");
   $result = sql_query("SELECT sid, title, ihome, catid FROM ".$NPDS_Prefix."stories ORDER BY sid DESC limit $push_news_limit");
   if ($result) {
       echo "document.write('<a name=\"article\"></a>');\n";
       echo "document.write('<li><b>".push_translate("Latest Articles")."</b></li><br />');\n";

       $ibid=sql_num_rows($result);
       for ($m=0; $m < $ibid; $m++) {
           list($sid, $title, $ihome, $catid) = sql_fetch_row($result);
           if (ctrl_aff($ihome, $catid)) {
              $title=str_replace("'","\'",$title);
              echo "document.write('&nbsp;-&nbsp;<a href=javascript:onclick=register(\"npds-push\",\"op=new_show&sid=$sid&offset=$m\"); style=\"font-size: 11px;\">".htmlspecialchars(aff_langue($title),ENT_COMPAT|ENT_HTML401,'UTF-8')."</a><br />');\n";
           }
       }
   }
   echo "document.write('<br />');\n";
   sql_free_result($result);
}

function new_show($sid, $offset) {
  global $nuke_url, $follow_links, $datetime;
  global $NPDS_Prefix;

  $result = sql_query("SELECT hometext, bodytext, notes, title, time, informant, topic FROM ".$NPDS_Prefix."stories WHERE sid='$sid'");
    if ($result) {
        push_header("suite");
        list($hometext, $bodytext,$notes, $title, $time, $informant, $topic) = sql_fetch_row($result);
        sql_free_result($result);
        $result = sql_query("SELECT topictext FROM ".$NPDS_Prefix."topics WHERE topicid='$topic'");
        if ($result) {
           list($topictext) = sql_fetch_row($result);
        }
        $title=str_replace("'","\'",$title);
        echo "document.write('<span style=\"font-size: 11px;\"><b>.:|<a href=\"$nuke_url/article.php?sid=$sid\" target=\"_blank\">".aff_langue($title)."</a>|:.</b></span><br />');\n";

        formatTimestamp($time);
        $topictext=str_replace("'","\'",$topictext);
        echo "document.write('".push_translate("Posted by")." <b>$informant</b> : $datetime (".htmlspecialchars($topictext,ENT_COMPAT|ENT_HTML401,'UTF-8').")');\n";
        echo "document.write('<br /><br />');\n";
        echo "document.write('".links(convert_nl(str_replace("'","\'",meta_lang(aff_code(aff_langue($hometext)))),"win","html"))."<br />');\n";
        if ($bodytext!="") {
           echo "document.write('<br />');\n";
           echo "document.write('".links(convert_nl(str_replace("'","\'",meta_lang(aff_code(aff_langue($bodytext)))),"win","html"))."<br />');\n";
        }
        if ($notes!="") {
           echo "document.write('<br />');\n";
           echo "document.write('".links(convert_nl(str_replace("'","\'",meta_lang(aff_code(aff_langue($notes)))),"win","html"))."');\n";
        }
        echo "document.write('<br /><span style=\"font-size: 11px;\">.: <a href=\"javascript: history.go(0)\">".push_translate("Home")."</a> :.</span>');\n";
        push_footer();
    }
    sql_free_result($result);
}

function push_poll() {
  global $NPDS_Prefix;

  echo "document.write('<a name=\"poll\"></a>');\n";
  echo "document.write('<li><b>".push_translate("Latest Poll Results")."</b></li>');\n";

  $result = sql_query("SELECT pollID, polltitle FROM ".$NPDS_Prefix."poll_desc ORDER BY pollID DESC LIMIT 1");
  list($pollID, $polltitle) = sql_fetch_row($result);
  sql_free_result($result);
  if ($pollID) {
     $result=sql_query("SELECT SUM(optionCount) FROM ".$NPDS_Prefix."poll_data WHERE pollID='$pollID'");
     list($sum)=sql_fetch_row($result);
     sql_free_result($result);

     $result = sql_query("SELECT optionText, optionCount FROM ".$NPDS_Prefix."poll_data WHERE pollID='$pollID' and optionText != \"\" ORDER BY voteID");
     echo "document.write('<p align=\"center\"><b>.:|".aff_langue($polltitle)."|:.</b></p><table width=\"100%\" border=\"0\">');\n";

     $ibid=sql_num_rows($result);
     for ($m=0; $m < $ibid; $m++) {
        list($optionText, $optionCount) = sql_fetch_row($result);
        if ($sum>0) {
           $percent = (int) 100*$optionCount/$sum;
        } else {
           $percent=0;
        }
        $optionText=str_replace("'","\'",$optionText);
        echo "document.write('<tr><td width=\"50%\" style=\"font-size: 11px;\">".aff_langue($optionText)."</td><td width=\"20%\" style=\"font-size: 11px;\">');\n";
        echo "document.write('".sprintf("%.1f%%",$percent)."');\n";
        echo "document.write('</td><td align=\"center\" style=\"font-size: 11px;\">($optionCount)</td></tr>');\n";
     }
     echo "document.write('<tr><td width=\"50%\" style=\"font-size: 11px;\">".push_translate("Total Votes:")."</td><td width=\"20%\">&nbsp;</td><td align=\"center\" style=\"font-size: 11px;\"><b>$sum</b></td>');\n";
     echo "document.write('</tr></table>');\n";
  }
  echo "document.write('<br />');\n";
}

function push_faq() {
   global $NPDS_Prefix;

   echo "document.write('<a name=\"faq\"></a>');\n";
   echo "document.write('<li><b>Faqs</b></li><br />');\n";
     $result = sql_query("SELECT id_cat, categories FROM ".$NPDS_Prefix."faqcategories ORDER BY id_cat ASC");
     while(list($id_cat, $categories) = sql_fetch_row($result)) {
        $categories=str_replace("'","\'",$categories);
        echo "document.write('&nbsp;-&nbsp;<a href=javascript:onclick=register(\"npds-push\",\"op=faq_show&id_cat=$id_cat\"); style=\"font-size: 11px;\">".aff_langue($categories)."</a><br />');\n";
     }
   echo "document.write('<br />');\n";
   sql_free_result($result);
}

function faq_show($id_cat) {
   global $NPDS_Prefix;

   push_header("suite");
   $result = sql_query("SELECT categories FROM ".$NPDS_Prefix."faqcategories WHERE id_cat='$id_cat'");
   list($categories) = sql_fetch_row($result);
   $categories=str_replace("'","\'",$categories);
   echo "document.write('<p align=\"center\"><a name=\"$id\"></a><b>".aff_langue($categories)."</b></p>');\n";

   $result = sql_query("SELECT id, id_cat, question, answer FROM ".$NPDS_Prefix."faqanswer WHERE id_cat='$id_cat'");
   while(list($id, $id_cat, $question, $answer) = sql_fetch_row($result)) {
      $question=str_replace("'","\'",$question);
      echo"document.write('<b>".aff_langue($question)."</b>');\n";
      echo "document.write('<p align=\"justify\">".links(convert_nl(str_replace("'","\'",meta_lang(aff_code(aff_langue($answer)))),"win","html"))."</p><br />');\n";
   }
   echo "document.write('.: <a href=\"javascript: history.go(0)\" style=\"font-size: 11px;\">".push_translate("Home")."</a> :.');\n";
   push_footer();
   sql_free_result($result);
}

function push_members () {
   global $anonymous;
   global $push_member_col, $push_member_limit, $nuke_url;
   global $page;
   global $NPDS_Prefix;

   echo "document.write('<a name=\"member\"></a>');\n";
   echo "document.write('<li><b>".push_translate("Member(s)")."</b></li><br />');\n";
   echo "document.write('<table border=\"0\" width=\"100%\"><tr>');\n";
   if (!$page) { $page=0; }
   $offset=0;
   $count_user=0;
   settype($page, "integer");
   settype($push_member_limit, "integer");
   $result = sql_query("SELECT uname FROM ".$NPDS_Prefix."users ORDER BY uname ASC LIMIT $page,$push_member_limit");
   while(list($uname) = sql_fetch_row($result)) {
      $offset=$offset+1;
      if ($uname!=$anonymous) {
         echo"document.write('<td><a href=\"$nuke_url/user.php?op=userinfo&amp;uname=$uname\" target=\"_blank\" style=\"font-size: 11px;\">$uname</a></td>');\n";
      } else {
         echo"document.write('<td style=\"font-size: 11px;\">$uname</td>');\n";
      }
      if ($offset==$push_member_col) {
         echo "document.write('</tr><tr>');\n";
         $offset=0;
      }
      $page=$page+1;
      $count_user=$count_user+1;
   }

   if ($count_user<$push_member_limit) {
      $page=0;
      echo "document.write('<td><b><a href=javascript:onclick=register(\"npds-push\",\"op=next_page&page=$page\"); style=\"font-size: 11px;\">".push_translate("Home")."</a></b></td>');\n";
   } else {
      echo "document.write('<td><b><a href=javascript:onclick=register(\"npds-push\",\"op=next_page&page=$page\"); style=\"font-size: 11px;\">".push_translate("Next")."</a></b></td>');\n";
   }
   echo "document.write('</tr></table>');\n";
   echo "document.write('<br />');\n";
   sql_free_result($result);
}

function push_links() {
   global $push_orderby;
   global $NPDS_Prefix;

   $orderby = "title ".$push_orderby;
   echo "document.write('<a name=\"link\"></a>');\n";
   echo "document.write('<li><b>".push_translate("Web links")."</b></li><br />');\n";

   echo "document.write('<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr>');\n";
   $result=sql_query("SELECT cid, title, cdescription FROM ".$NPDS_Prefix."links_categories ORDER BY $orderby");
   $count = 0;
   while(list($cid, $title, $cdescription) = sql_fetch_row($result)) {
      $cresult = sql_query("SELECT * FROM ".$NPDS_Prefix."links_links WHERE cid='$cid'");
      $cnumrows = sql_num_rows($cresult);
      $title=str_replace("'","\'",$title);
      echo "document.write('<td width=\"49%\" valign=\"top\"><a href=javascript:onclick=register(\"npds-push\",\"op=viewlink&cid=$cid\"); style=\"font-size: 11px;\"><b>".aff_langue($title)."</b></a> ($cnumrows)');\n";
      if ($cdescription) {
          $cdescription=links(convert_nl(str_replace("'","\'",$cdescription),"win","html"));
          echo "document.write('<br /><i>".aff_langue($cdescription)."</i><br />');\n";
      } else {
          echo "document.write('<br />');\n";
      }
      $result2 = sql_query("SELECT sid, title FROM ".$NPDS_Prefix."links_subcategories WHERE cid='$cid' ORDER BY $orderby LIMIT 0,3");
      $space = 0;
      while(list($sid, $stitle) = sql_fetch_row($result2)) {
         if ($space>0) {
            echo "document.write('<br />');\n";
         }
         $title=str_replace("'","\'",$title);
         echo "document.write('&nbsp;<a href=javascript:onclick=register(\"npds-push\",\"op=viewslink&sid=$sid\"); style=\"font-size: 11px;\">".aff_langue($stitle)."</a>');\n";
         $space++;
      }
      if ($count<1) {
         echo "document.write('</td><td>&nbsp;</td>');\n";
      }
      $count++;
      if ($count==2) {
         echo "document.write('</td></tr><tr><td>&nbsp;</td></tr><tr>');\n";
         $count = 0;
      }
   }
   echo "document.write('</td></tr></table>');\n";
   echo "document.write('<br />');\n";
   sql_free_result($result);
}

function viewlink_show($cid, $min) {
   global $follow_links, $nuke_url, $push_view_perpage, $push_orderby;
   global $NPDS_Prefix;

   push_header("suite");

    if (!isset($min)) $min=0;
    $perpage = $push_view_perpage;
    $orderby = "title ".$push_orderby;

    $result=sql_query("SELECT title FROM ".$NPDS_Prefix."links_categories WHERE cid='$cid'");
    list($title) = sql_fetch_row($result);
    $title=str_replace("'","\'",$title);
    echo "document.write('<span  style=\"font-size: 11px;\"><b>".aff_langue($title)."</b></span>');\n";
    $subresult=sql_query("SELECT sid, title FROM ".$NPDS_Prefix."links_subcategories WHERE cid='$cid' ORDER BY $orderby");
    $numrows = sql_num_rows($subresult);
    if ($numrows != 0) {
        echo "document.write('<b> / Sub-Cat</b><br />');\n";
        while(list($sid, $title) = sql_fetch_row($subresult)) {
           $result2 = sql_query("SELECT * FROM ".$NPDS_Prefix."links_links WHERE sid='$sid'");
           $numrows = sql_num_rows($result2);
           $title=str_replace("'","\'",$title);
           echo "document.write('<li><a href=javascript:onclick=register(\"npds-push\",\"op=viewslink&sid=$sid\"); style=\"font-size: 11px;\">".aff_langue($title)."</a> ($numrows)</li>');\n";
        }
        echo "document.write('<hr width=\"100%\" noshade=\"noshade\" />');\n";

    } else {
        echo "document.write('<br />');\n";
    }
    settype($min, "integer");
    settype($perpage, "integer");
    $result=sql_query("SELECT lid, title FROM ".$NPDS_Prefix."links_links WHERE cid='$cid' AND sid=0 ORDER BY $orderby LIMIT $min,$perpage");
    $fullcountresult=sql_query("SELECT lid, title FROM ".$NPDS_Prefix."links_links WHERE cid='$cid' AND sid=0");
    $totalselectedlinks = sql_num_rows($fullcountresult);

    while(list($lid, $title)=sql_fetch_row($result)) {
      $title=links(convert_nl(str_replace("'","\'",$title),"win","html"));
      echo "document.write('<li><a href=\"$nuke_url/links.php?op=visit&amp;lid=$lid\" target=\"_blank\" style=\"font-size: 11px;\">".aff_langue($title)."</a></li><br />');\n";
    }

    if (($totalselectedlinks-$min)>$perpage) {
       $min=$min+$perpage;
       if ($ibid=theme_image("box/right.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/download/right.gif";}
       echo "document.write('<a href=javascript:onclick=register(\"npds-push\",\"op=viewlink&cid=$cid&min=$min\"); style=\"font-size: 11px;\"><img src=\"$nuke_url/$imgtmp\" border=\"0\" alt=\"\" align=\"center\" /></a><br />');\n";
    }
   echo "document.write('<br />.: <a href=\"javascript: history.go(0)\" style=\"font-size: 11px;\">".push_translate("Home")."</a> :.');\n";
   push_footer();
   sql_free_result($result);
}

function viewslink_show($sid, $min) {
   global $follow_links, $nuke_url, $push_view_perpage, $push_orderby;
   global $NPDS_Prefix;

   push_header("suite");

    if (!isset($min)) $min=0;
    $perpage = $push_view_perpage;
    $orderby = "title ".$push_orderby;

    $result = sql_query("SELECT cid, title FROM ".$NPDS_Prefix."links_subcategories WHERE sid='$sid'");
    list($cid, $stitle) = sql_fetch_row($result);

    $result2 = sql_query("SELECT cid, title FROM ".$NPDS_Prefix."links_categories WHERE cid='$cid'");
    list($cid, $title) = sql_fetch_row($result2);

    $title=str_replace("'","\'",$title);
    $stitle=str_replace("'","\'",$stitle);
    echo "document.write('<span style=\"font-size: 11px;\"><b>".aff_langue($title)." / SubCat : ".aff_langue($stitle)."</b>'</span>);\n";

    settype($min, "integer");
    settype($perpage, "integer");
    $result=sql_query("SELECT lid, title FROM ".$NPDS_Prefix."links_links WHERE cid='$cid' AND sid='$sid' ORDER BY $orderby LIMIT $min,$perpage");
    $fullcountresult=sql_query("SELECT lid, title FROM ".$NPDS_Prefix."links_links WHERE cid='$cid' AND sid='$sid'");
    $totalselectedlinks = sql_num_rows($fullcountresult);

    echo "document.write('<br />');\n";
    while(list($lid, $title)=sql_fetch_row($result)) {
       $title=links(convert_nl(str_replace("'","\'",$title),"win","html"));
       echo "document.write('<li><a href=\"$nuke_url/links.php?op=visit&amp;lid=$lid\" target=\"_blank\" style=\"font-size: 11px;\">".aff_langue($title)."</a></li><br />');\n";
    }

    if (($totalselectedlinks-$min)>$perpage) {
       $min=$min+$perpage;
       if ($ibid=theme_image("box/right.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/download/right.gif";}
       echo "document.write('<a href=javascript:onclick=register(\"npds-push\",\"op=viewlink&cid=$cid&min=$min\"); style=\"font-size: 11px;\"><img src=\"$nuke_url/$imgtmp\" border=\"0\" alt=\"\" align=\"center\" /></a><br />');\n";
    }
   echo "document.write('<br />.: <a href=\"javascript: history.go(0)\" style=\"font-size: 11px;\">".push_translate("Home")."</a> :.');\n";
   push_footer();
   sql_free_result($result);
}

function convert_nl($string , $from , $to){
   $OS['mac'] = chr(13);
   $OS['win'] = chr(13).chr(10);
   $OS['nix'] = chr(10);
   $OS['html']="<br />";

   if ($to == $from)
   return TRUE;

   if (!in_array($from, array_keys($OS)))
   return FALSE;

   if (!in_array($to, array_keys($OS)))
   return FALSE;

   return str_replace($OS[$from], $OS[$to], $string);
}

function links($ibid) {
   global $follow_links, $nuke_url;

   if ($follow_links==false) {
      if (stristr($ibid,"<a href")==true) {
         $ibid=strip_tags($ibid);
      }
   }

   if ( (stristr($ibid,"<img src")) ) {
      if ( (!stristr($ibid,"<img src=http")) and (!stristr($ibid,"<img src=\"http")) ) {
         $ibid=str_replace("<img src=","<img src=$nuke_url/" ,$ibid);
      }
   }
   return $ibid;
}

   if ($SuperCache) {
      $cache_obj = new cacheManager();
      $cache_obj->startCachingPage();
   } else {
      $cache_obj = new SuperCacheEmpty();
   }

   if (($cache_obj->genereting_output==1) or ($cache_obj->genereting_output==-1) or (!$SuperCache)) {
      settype($op,'string');
      switch ($op) {
         case "new_show":
            new_show($sid, $offset);
            break;

         case "faq_show":
            faq_show($id_cat);
            break;

         case "viewlink":
            viewlink_show($cid,$min);
            break;
         case "viewslink":
            viewslink_show($sid,$min);
            break;

         default:
            index();
            break;
   }
   if ($SuperCache) {
      $cache_obj->endCachingPage();
   }
}
?>

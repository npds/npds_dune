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
    if ($oper=='links') {
       $DB=removeHack(stripslashes(htmlentities(urldecode($DB),ENT_NOQUOTES,cur_charset)));
       $result=sql_query("SELECT url, title, description, date FROM ".$DB."links_links WHERE lid='$sid'");
       list($url, $title, $description, $time)=sql_fetch_row($result);
       $title = stripslashes($title); $description = stripslashes($description);
    }   
    if ($oper=='static') {
       if (preg_match('#^[a-z0-9_\.-]#i',$sid) and !stristr($sid,".*://") and !stristr($sid,"..") and !stristr($sid,"../") and !stristr($sid, "script") and !stristr($sid, "cookie") and !stristr($sid, "iframe") and  !stristr($sid, "applet") and !stristr($sid, "object") and !stristr($sid, "meta"))  {
          if (file_exists("static/$sid")) {
             ob_start();
                include ("static/$sid");
                $remp=ob_get_contents();
             ob_end_clean();
             if ($DB)
                $remp=meta_lang(aff_code(aff_langue($remp)));
             if ($nl)
               $remp=nl2br(str_replace(' ','&nbsp;',htmlentities($remp,ENT_QUOTES,cur_charset)));
             $title=$sid;
          } else {
             $aff=false;
          }
       } else {
         $remp='<div class="alert alert-danger">'.translate("Please enter information according to the specifications").'</div>';
         $aff=false;
       }
    }
    if ($aff==true) {
       $Titlesitename="NPDS - ".translate("Printer Friendly Page")." / ".$title;
       if (isset($time))
          formatTimestamp($time);
       include("meta/meta.php");
       if (isset($user)) {
          if ($cookie[9]=='') $cookie[9]=$Default_Theme;
          if (isset($theme)) $cookie[9]=$theme;
          $tmp_theme=$cookie[9];
          if (!$file=@opendir("themes/$cookie[9]")) {
             $tmp_theme=$Default_Theme;
          }
       } else {
          $tmp_theme=$Default_Theme;
       }
       echo '
         <link rel="stylesheet" href="lib/bootstrap/dist/css/bootstrap.min.css" />';
       echo import_css($tmp_theme, $language, $site_font, '','');
       echo '
       </head>
       <body>
          <div max-width="640" class="container p-1">
             <div class="">';
       $pos = strpos($site_logo, '/');
       if ($pos)
          echo '<img class="img-fluid d-block mx-auto" src="'.$site_logo.'" alt="website logo" />';
       else
          echo '<img class="img-fluid d-block mx-auto" src="images/'.$site_logo.'" alt="website logo" />';
       echo '
               <h1 class="d-block text-xs-center my-2">'.aff_langue($title).'</h1>';
       if (($oper=='news') or ($oper=='archive')) {
          $hometext=meta_lang(aff_code(aff_langue($hometext)));
          $bodytext=meta_lang(aff_code(aff_langue($bodytext)));
          echo '
          <span style="font-size: .8rem;"> '.$datetime.' :: <b>'.translate("Topic:").'</b> '.aff_langue($topictext).'<br /><br /></span>
         </div>
         <div>'.$hometext.'<br /><br />';
          if ($bodytext!='') {
             echo $bodytext.'<br /><br />';
          }
          echo meta_lang(aff_code(aff_langue($notes)));
          echo '
          </div>';
          if ($oper=='news') {
             echo '
             <hr />
             <p class="text-xs-center">'.translate("This article comes from").' '.$sitename.'<br /><br />
             '.translate("The URL for this story is:").'
             <a href="'.$nuke_url.'/article.php?sid='.$sid.'">'.$nuke_url.'/article.php?sid='.$sid.'</a>
             </p>';
          } else {
             echo '
             <hr />
             <p class="text-xs-center">'.translate("This article comes from").' '.$sitename.'<br />
             '.translate("The URL for this story is:").'
             <a href="'.$nuke_url.'/article.php?sid='.$sid.'&amp;archive=1">'.$nuke_url.'/article.php?sid='.$sid.'&amp;archive=1</a>
             </p>';
          }
       }
       if ($oper=='links') {
          echo '<span style="font-size: .8rem;">'.$datetime;
          if ($url!='') {
             echo ' :: <strong>'.translate("Links").' : </strong> '.$url.'<br /><br />';
          }
          echo '</span></p>'.aff_langue($description);
          echo '
          <hr />
          <p class="text-xs-center">'.translate("This article comes from").' '.$sitename.'<br />
          <a href="'.$nuke_url.'">'.$nuke_url.'</a></p>';
       }
       if ($oper=='static') {
          echo '
          <div>
             '.$remp.'
          </div>
          <hr />
          <p class="text-xs-center">'.translate("This article comes from").' '.$sitename.'<br />
          <a href="'.$nuke_url.'/static.php?op='.$sid.'&npds=1">'.$nuke_url.'/static.php?op='.$sid.'&npds=1</a></p>';
       }
       echo '
      </div>
   </body>
</html>';
    } else {
       header("location: index.php");
    }
}
if (!empty($sid)) {
   $tab=explode(':',$sid);
   if ($tab[0]=="static") {
      settype ($metalang, 'integer');
      settype ($nl, 'integer');
      PrintPage("static", $metalang, $nl, $tab[1]);
   } else {
      settype ($sid, 'integer');
      if (!$archive) {
         PrintPage("news", '', '', $sid);
      } else {
         PrintPage("archive", '', '', $sid);
      }
   }
} elseif (!empty($lid)) {
   settype ($lid, "integer");
   PrintPage("links",$DB, '', $lid);
} else {
   header("location: index.php");
}
?>
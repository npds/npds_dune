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
if (!function_exists("Access_Error")) { die(""); }
if (!stristr($_SERVER['PHP_SELF'],"modules.php")) { Access_Error(); }

global $language, $links_DB, $NPDS_Prefix;

$pos = strpos($ModPath, "/admin");
include_once('modules/'.substr($ModPath,0,$pos).'/links.conf.php');
if ($links_DB=='') {
   $links_DB=$NPDS_Prefix;
}
$hlpfile = "modules/".substr($ModPath,0,$pos)."/manual/$language/mod-weblinks.html";

$result = sql_query("SELECT radminsuper FROM ".$NPDS_Prefix."authors WHERE aid='$aid'");
list($radminsuper) = sql_fetch_row($result);
if ($radminsuper!=1) {//intégrer les droits nouveau système
   Access_Error();
}

function links() {
   global $ModPath, $ModStart, $links_DB, $admin, $language, $hlpfile, $NPDS_Prefix;

   include ("header.php");
   echo '
   <script type="text/javascript">
   //<![CDATA[
   function openwindow(){
      window.open ("'.$hlpfile.'","Help","toolbar=no,location=no,directories=no,status=no,scrollbars=yes,resizable=no,copyhistory=no,width=600,height=400");
   }
   //]]>
   </script>';

   $result=sql_query("SELECT * FROM ".$links_DB."links_links");
   $numrows = sql_num_rows($result);
   echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\"><tr class=\"header\"><td>\n";
   echo "DB : $links_DB";
   echo translate("There are")." <b>$numrows</b> ".translate("Links in our Database")." ";
   echo "</td><td align=\"left\">[ <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath\" class=\"box\">".translate("Links Main")."</a> ]</td><td align=\"right\">[ <a href=\"javascript:openwindow();\" class=\"box\">".translate("Online Manual")."</a> ]";
   echo "</td></tr></table>\n";

   $result = sql_query("SELECT * FROM ".$links_DB."links_modrequest WHERE brokenlink=1");
   $totalbrokenlinks = sql_num_rows($result);
   $result2 = sql_query("SELECT * FROM ".$links_DB."links_modrequest WHERE brokenlink=0");
   $totalmodrequests = sql_num_rows($result2);
   echo "<br /><p align=\"center\">[ <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&op=LinksListBrokenLinks\" class=\"noir\">".translate("Broken Link Reports")." ($totalbrokenlinks)</a> - <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&op=LinksListModRequests\" class=\"noir\">".translate("Link Modification Requests")." ($totalmodrequests)</a> ]</p><br />";

   $result = sql_query("SELECT lid, cid, sid, title, url, description, name, email, submitter, topicid_card FROM ".$links_DB."links_newlink ORDER BY lid ASC LIMIT 0,1");
   $numrows = sql_num_rows($result);
   $adminform='';
   if ($numrows>0) {
      $adminform='adminForm';
      list($lid, $cid, $sid, $title, $url, $description, $name, $email, $submitter, $topicid_card) = sql_fetch_row($result);
      // Le lien existe déjà dans la table ?
      $resultAE = sql_query("SELECT url FROM ".$links_DB."links_links WHERE url='$url'");
      $numrowsAE = sql_num_rows($resultAE);
      echo '
   <div class="card card-block">
   <h3>'.translate("Links Waiting for Validation").'</h3>
   <form action="modules.php" method="post" name="'.$adminform.'">
      <input type="hidden" name="ModPath" value="'.$ModPath.'" />
      <input type="hidden" name="ModStart" value="'.$ModStart.'" />';
          echo translate("Link ID: ").'<b>'.$lid.'</b>';
          if ($numrowsAE>0)
             echo "&nbsp;&nbsp;<span class=\"rouge\">".translate("ERROR: This URL is already listed in the Database!")."</span>";
          echo "".translate("Author")." : $submitter <br />";
          echo '
      <div class="form-group row">
         <label class="form-control-label col-sm-3" for="title">'.translate("Title").'</label>
         <div class="col-sm-9">
            <input class="form-control" type="text" name="title" value="'.$title.'" maxlength="100" />
         </div>
      </div>';
          global $links_url;
          if ($links_url)
             echo '
         <div class="form-group row">
            <label class="form-control-label col-sm-3" for="url">URL</label>
            <div class="col-sm-9">
                <input class="form-control" type="url" name="url" value="'.$url.'" maxlength="100" /> <a href="'.$url.'" target="_blank" >'.translate("Visit").'</a>
            </div>
         </div>';
          $result2=sql_query("SELECT cid, title FROM ".$links_DB."links_categories ORDER BY title");
          echo '
         <div class="form-group row">
            <label class="form-control-label col-sm-3" for="cat">'.translate("Category").'</label>
            <div class="col-sm-9">
               <select class="c-select form-control" name="cat">';
          while (list($ccid, $ctitle) = sql_fetch_row($result2)) {
             $sel = "";
             if ($cid==$ccid AND $sid==0) {
                $sel = 'selected="selected"';
             }
             echo '
                  <option value="'.$ccid.'" '.$sel.'>'.aff_langue($ctitle).'</option>';
             $result3=sql_query("SELECT sid, title FROM ".$links_DB."links_subcategories WHERE cid='$ccid' ORDER BY title");
             while (list($ssid, $stitle) = sql_fetch_row($result3)) {
                   $sel = "";
                if ($sid==$ssid) {
                   $sel = 'selected="selected"';
                }
                echo "
                  <option value=\"$ccid-$ssid\" $sel>".aff_langue($ctitle)." / ".aff_langue($stitle)."</option>";
             }

          }
          echo '
               </select>
            </div>
         </div>';
          global $links_topic;
          if ($links_topic) {
             echo '
         <div class="form-group row">
            <label class="form-control-label col-sm-3" for="topicL">'.translate("Topics").'</label>
            <div class="col-sm-9">
                <select class="c-select form-control" name="topicL">';
             $toplist = sql_query("SELECT topicid, topictext FROM ".$NPDS_Prefix."topics ORDER BY topictext");
             echo '
                   <option value="">'.translate("All Topics").'</option>';
             while(list($topicid, $topics) = sql_fetch_row($toplist)) {
               if ($topicid==$topicid_card) { $sel = 'selected="selected" '; }
               echo '
                  <option '.$sel.' value="'.$topicid.'">'.aff_langue($topics).'</option>';
               $sel = '';
             }
             echo '
               </select>
            </div>
         </div>';
          }
         echo ' 
         <div class="form-group row">
            <label class="form-control-label col-sm-12" for="xtext">'.translate("Description").'</label>
            <div class="col-sm-12">
               <textarea class="tin form-control" name="xtext" rows="10" style="width: 100%;">'.$description.'</textarea>
            </div>
         </div>';
         echo aff_editeur('xtext','');
         echo '
          <div class="form-group row">
            <label class="form-control-label col-sm-3" for="name">'.translate("Name").'</label>
            <div class="col-sm-9">
               <input class="form-control" type="text" name="name" maxlength="100" value="'.$name.'" />
            </div>
         </div>
         <div class="form-group row">
            <label class="form-control-label col-sm-3" for="email">'.translate("Email").'</label>
            <div class="col-sm-9">
               <input class="form-control" type="email" name="email" maxlength="100" value="'.$email.'" />
            </div>
         </div>
         <div class="form-group row">
            <div class="col-sm-offset-3 col-sm-9">
               <input type="hidden" name="new" value="1" />
               <input type="hidden" name="lid" value="'.$lid.'" />
               <input type="hidden" name="submitter" value="'.$submitter.'" />
               <input type="hidden" name="op" value="LinksAddLink" />
               <input class="btn btn-primary" type="submit" value="'.translate("Add").'" />
               <a class="btn btn-danger" href="modules.php?ModStart='.$ModStart.'&amp;ModPath='.$ModPath.'&amp;op=LinksDelNew&amp;lid='.$lid.'">'.translate("Delete").'</a>
            </div>
         </div>
      </form>
   </div>';
   }

   // Add a New Link to Database
   $result = sql_query("SELECT cid, title FROM ".$links_DB."links_categories");
   $numrows = sql_num_rows($result);
   if ($numrows>0) {
      echo '
   <div class="card card-block">
      <h3>'.translate("Add a New Link").'</h3>';
      if ($adminform=='') {
         echo '
      <form method="post" action="modules.php" name="adminForm">';
       } else {
         echo '
      <form method="post" action="modules.php">';
       }
         echo '
         <input type="hidden" name="ModPath" value="'.$ModPath.'" />
         <input type="hidden" name="ModStart" value="'.$ModStart.'" />
         <div class="form-group row">
            <label class="form-control-label col-sm-3" for="title">'.translate("Title").'</label>
            <div class="col-sm-9">
               <input class="form-control" type="text" name="title" maxlength="100" />
            </div>
         </div>';
         
       global $links_url;
       if ($links_url)
          echo ' 
         <div class="form-group row">
            <label class="form-control-label col-sm-3" for="url">URL</label>
            <div class="col-sm-9">
               <input class="form-control" type="url" name="url" maxlength="100" value="http://" />
            </div>
         </div>';
       $result=sql_query("SELECT cid, title FROM ".$links_DB."links_categories ORDER BY title");
       echo '
         <div class="form-group row">
            <label class="form-control-label col-sm-3" for="cat">'.translate("Category").'</label>
            <div class="col-sm-9">
               <select class="c-select form-control" name="cat">';
       while (list($cid, $title) = sql_fetch_row($result)) {
          echo '
                  <option value="'.$cid.'">'.aff_langue($title).'</option>';
          $result2=sql_query("SELECT sid, title FROM ".$links_DB."links_subcategories WHERE cid='$cid' ORDER BY title");
          while (list($sid, $stitle) = sql_fetch_row($result2)) {
             echo '
                  <option value="'.$cid.'-'.$sid.'">'.aff_langue($title.' / '.$stitle).'</option>';
          }
       }
       echo '
               </select>
            </div>
         </div>';
       global $links_topic;
       if ($links_topic) {
         echo '
         <div class="form-group row">
            <label class="form-control-label col-sm-3" for="topicL">'.translate("Topics").'</label>
            <div class="col-sm-9">
               <select class="c-select form-control" name="topicL">';
          $toplist = sql_query("SELECT topicid, topictext FROM ".$NPDS_Prefix."topics ORDER BY topictext");
          echo '
                  <option value="">'.translate("All Topics").'</option>';
          while(list($topicid, $topics) = sql_fetch_row($toplist)) {
            echo '
                  <option value="'.$topicid.'">'.aff_langue($topics).'</option>';
          }
          echo '
               </select>
            </div>
         </div>';
       }
       echo '
      <div class="form-group row">
         <label class="form-control-label col-xs-12" for="xtext">'.translate("Description: (255 characters max)").'</label>
         <div class="col-xs-12">
            <textarea class="tin form-control" name="xtext" rows="10"></textarea>
          </div>
      </div>';
       if ($adminform=='')
          echo aff_editeur("xtext","false");
       echo '
      <div class="form-group row">
         <label class="form-control-label col-sm-3" for="name">'.translate("Name").'</label>
         <div class="col-sm-9">
            <input class="form-control" type="text" name="name" maxlength="60" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-3" for="email">'.translate("E-Mail").'</label>
            <div class="col-sm-9">
               <input class="form-control" type="email" name="email" maxlength="60" />
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-offset-3 col-sm-9">
            <input type="hidden" name="op" value="LinksAddLink" />
            <input type="hidden" name="new" value="0" />
            <input type="hidden" name="lid" value="0" />
            <input class="btn btn-primary" type="submit" value="'.translate("Add URL").'" />
         </div>
      </div>
   </form>
   </div>';
   }
   // Add a New Main Category
   echo '
   <div class="card card-block">
   <h3>'.translate("Add a MAIN Category").'</h3>
   <form method="post" action="modules.php">
      <div class="form-group row">
         <label class="form-control-label col-sm-3" for="title">'.translate("Name").'</label>
         <div class="col-sm-9">
            <input class="form-control" type="text" name="title" size="30" maxlength="100" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-12" for="cdescription">'.translate("Description").'</label>
         <div class="col-sm-12">
            <textarea class="form-control" name="cdescription" rows="10" ></textarea>
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-12">
            <input type="hidden" name="ModPath" value="'.$ModPath.'" />
            <input type="hidden" name="ModStart" value="'.$ModStart.'" />
            <input type="hidden" name="op" value="LinksAddCat" />
            <input class="btn btn-primary" type="submit" value="'.translate("Add").'" />
         </div>
      </div>
   </form>
   </div>';

   // Modify Category
   $result = sql_query("SELECT * FROM ".$links_DB."links_categories");
   $numrows = sql_num_rows($result);
   if ($numrows>0) {
      echo '
   <div class="card card-block">
   <h3>'.translate("Modify Category").'</h3>
   <form method="post" action="modules.php">
      <input type="hidden" name="ModPath" value="'.$ModPath.'" />
      <input type="hidden" name="ModStart" value="'.$ModStart.'" />';
      $result=sql_query("SELECT cid, title FROM ".$links_DB."links_categories ORDER BY title");
      echo '
      <div class="form-group row">
         <label class="form-control-label col-sm-3" for="cat">'.translate("Category").'</label>
         <div class="col-sm-9">
            <select class="c-select form-control" name="cat">';
      while(list($cid, $title) = sql_fetch_row($result)) {
         echo '
               <option value="'.$cid.'">'.aff_langue($title).'</option>';
         $result2=sql_query("SELECT sid, title FROM ".$links_DB."links_subcategories WHERE cid='$cid' ORDER BY title");
         while(list($sid, $stitle) = sql_fetch_row($result2)) {
            echo '
               <option value="'.$cid.'-'.$sid.'">'.aff_langue($title.' / '.$stitle).'</option>';
         }
      }
      echo '
            </select>
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-offset-3 col-sm-9">
            <input type="hidden" name="op" value="LinksModCat" />
            <input class="btn btn-primary" type="submit" value="'.translate("Modify").'" />
         </div>
      </div>
   </form>
   </div>';
   }

   // Add a New Sub-Category
   $result = sql_query("SELECT * FROM ".$links_DB."links_categories");
   $numrows = sql_num_rows($result);
   if ($numrows>0) {
      echo '
   <div class="card card-block">
   <h3>'.translate("Add a SUB-Category").'</h3>
   <form method="post" action="modules.php">
      <input type="hidden" name="ModPath" value="'.$ModPath.'" />
      <input type="hidden" name="ModStart" value="'.$ModStart.'" />
      <div class="form-group row">
         <label class="form-control-label col-sm-3" for="title">'.translate("Name").'</label>
         <div class="col-sm-9">
            <input class="form-control" type="text" name="title" maxlength="100" />
         </div>
      </div>';
       $result=sql_query("SELECT cid, title FROM ".$links_DB."links_categories ORDER BY title");
       echo '
      <div class="form-group row">
         <label class="form-control-label col-sm-3" for="cid">'.translate("in").'</label>
         <div class="col-sm-9">
            <select class="c-select form-control" name="cid">';
       while (list($ccid, $ctitle) = sql_fetch_row($result)) {
          echo '
               <option value="'.$ccid.'">'.aff_langue($ctitle).'</option>';
       }
       echo '
            </select>
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-offset-3 col-sm-9">
            <input type="hidden" name="op" value="LinksAddSubCat" />
            <input class="btn btn-primary " type="submit" value="'.translate("Add").'" />
         </div>
      </div>
   </form>
   </div>';
   }
   include ("footer.php");
}

// ------ Links
function LinksAddLink($new, $lid, $title, $url, $cat, $description, $name, $email, $submitter, $topicL) {
    global $ModPath, $ModStart, $links_DB;
    // Check if Title exist
    if ($title=='') {
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
    if ($description=='') {
       include("header.php");
       opentable();
       echo "<br /><span class=\"rouge\">".translate("ERROR: You need to type a DESCRIPTION for your URL!")."</span><br /><br />";
       echo "[ <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath\" class=\"noir\">".translate("Go Back")."</a> ]<br />";
       closetable();
       include("footer.php");
       exit();
    }
    $cat = explode('-', $cat);
    if (!array_key_exists(1,$cat)) {
       $cat[1] = 0;
    }
    $title = stripslashes(FixQuotes($title));
    $url = stripslashes(FixQuotes($url));
    $description = stripslashes(FixQuotes($description));
    $name = stripslashes(FixQuotes($name));
    $email = stripslashes(FixQuotes($email));
    sql_query("INSERT INTO ".$links_DB."links_links VALUES (NULL, '$cat[0]', '$cat[1]', '$title', '$url', '$description', now(), '$name', '$email', '0','$submitter',0,0,0,'$topicL')");
    include("header.php");
    echo translate("New Link added to the Database");
    echo "<br />";
    echo "[ <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath\" class=\"noir\">".translate("Go Back")."</a> ]<br />";
    if ($new==1) {
       sql_query("DELETE FROM ".$links_DB."links_newlink WHERE lid='$lid'");
       if ($email!='') {
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
   global $ModPath, $ModStart, $links_DB, $hlpfile, $NPDS_Prefix;
   include ("header.php");

   echo "<script type=\"text/javascript\">\n";
   echo "//<![CDATA[\n";
   echo "function openwindow(){\n";
   echo " window.open (\"$hlpfile\",\"Help\",\"toolbar=no,location=no,directories=no,status=no,scrollbars=yes,resizable=no,copyhistory=no,width=600,height=400\");\n";
   echo "}\n";
   echo "//]]>\n";
   echo "</script>\n";

    $result = sql_query("SELECT cid, sid, title, url, description, name, email, hits, topicid_card FROM ".$links_DB."links_links WHERE lid='$lid'");

    echo '
    <h2>'.translate("Modify Links").'</h2>
    <h3>'.translate("Web link").'&nbsp;<span class="text-muted">#'.$lid.'</span></h3>
    ';
    echo "[ <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModStart\" class=\"box\">".translate("Links Main")."</a> ][ <a href=\"javascript:openwindow();\" class=\"box\">".translate("Online Manual")."</a> ]";

    while (list($cid, $sid, $title, $url, $description, $name, $email, $hits, $topicid_card) = sql_fetch_row($result)) {
       $title = stripslashes($title); $description = stripslashes($description);

       echo '
      <form action="modules.php" method="post" name="adminForm">
         <input type="hidden" name="ModPath" value="'.$ModPath.'" />
         <input type="hidden" name="ModStart" value="'.$ModStart.'" />';

//       echo translate("Link ID: ")."<b>$lid</b><br />";
       echo '
         <div class="form-group row">
            <label class="form-control-label col-sm-4" for="title">'.translate("Title").'</label>
            <div class="col-sm-8">
               <input class="form-control" type="text" name="title" value="'.$title.'" maxlength="100" />
            </div>
         </div>';
       global $links_url;
       if (($links_url) or ($links_url==-1))
          echo '
         <div class="form-group row">
            <label class="form-control-label col-sm-4" for="url">URL</label>
            <div class="col-sm-8">
               <input class="form-control" type="text" name="url" value="'.$url.'" maxlength="100" /><a href="'.$url.'" target="_blank" >'.translate("Visit").'</a>
            </div>
         </div>';
       $result2=sql_query("SELECT cid, title FROM ".$links_DB."links_categories ORDER BY title");
       echo '
         <div class="form-group row">
            <label class="form-control-label col-sm-4" for="cat">'.translate("Category").'</label>
            <div class="col-sm-8">
               <select class="c-select form-control" name="cat">';
       while (list($ccid, $ctitle) = sql_fetch_row($result2)) {
          $sel = '';
          if ($cid==$ccid AND $sid==0) {
             $sel = 'selected="selected"';
          }
          echo '
                  <option value="'.$ccid.'" '.$sel.'>'.aff_langue($ctitle).'</option>';
          $result3=sql_query("SELECT sid, title FROM ".$links_DB."links_subcategories WHERE cid='$ccid' ORDER BY title");
          while (list($ssid, $stitle) = sql_fetch_row($result3)) {
             $sel = '';
             if ($sid==$ssid) {
                $sel = 'selected="selected"';
             }
             echo '
                  <option value="'.$ccid.'-'.$ssid.'" '.$sel.'>'.aff_langue($ctitle.' / '.$stitle).'</option>';
          }
       }
       echo '
               </select>
            </div>
         </div>';
       global $links_topic;
       if ($links_topic) {
          echo '
         <div class="form-group row">
            <label class="form-control-label col-sm-4" for="topicL">'.translate("Topics").'</label>
            <div class="col-sm-8">
               <select class="c-select form-control" name="topicL">';
          $toplist = sql_query("SELECT topicid, topictext FROM ".$NPDS_Prefix."topics ORDER BY topictext");
          echo '
                  <option value="">'.translate("All Topics").'</option>';
          while(list($topicid, $topics) = sql_fetch_row($toplist)) {
              if ($topicid==$topicid_card) { $sel = "selected=\"selected\" "; }
              echo '
                  <option '.$sel.' value="'.$topicid.'">'.aff_langue($topics).'</option>';
              $sel = '';
          }
          echo '
               </select>
            </div>
         </div>';
       }
       echo '
         <div class="form-group row">
            <label class="form-control-label col-sm-4" for="hits">'.translate("Hits").'</label>
            <div class="col-sm-8">
               <input class="form-control" type="number" name="hits" value="'.$hits.'" />
            </div>
         </div>
         <div class="form-group row">
            <label class="form-control-label col-sm-12" for="xtext">'.translate("Description").'</label>
            <div class="col-sm-12">
               <textarea class="tin form-control" name="xtext" rows="10">'.$description.'</textarea>
            </div>
         </div>';
       echo aff_editeur('xtext','');

       echo '
         <div class="form-group row">
            <label class="form-control-label col-sm-4" for="name">'.translate("Name").'</label>
            <div class="col-sm-8">
            <input class="form-control" type="text" name="name" maxlength="100" value="'.$name.'" />
            </div>
         </div>
         <div class="form-group row">
            <label class="form-control-label col-sm-4" for="email">'.translate("E-Mail").'</label>
            <div class="col-sm-8">
               <input class="form-control" type="text" name="email" maxlength="100" value="'.$email.'" />
            </div>
         </div>
         <div class="form-group row">
            <div class="col-sm-offset-4 col-sm-8">
               <input type="hidden" name="lid" value="'.$lid.'" />
               <input type="hidden" name="op" value="LinksModLinkS" />
               <input class="btn btn-primary" type="submit" value="'.translate("Modify").'" />&nbsp;
               <a class="btn btn-danger" href="modules.php?ModStart='.$ModStart.'&amp;ModPath='.$ModPath.'&op=LinksDelLink&lid='.$lid.'" >'.translate("Delete").'</a>
            </div>
         </div>
       </form>';

       echo "<hr noshade=\"noshade\" class=\"ongl\" />";
       $resulted2 = sql_query("SELECT adminid, editorialtimestamp, editorialtext, editorialtitle FROM ".$links_DB."links_editorials WHERE linkid='$lid'");
       $recordexist = sql_num_rows($resulted2);
       if ($recordexist == 0) {
          echo '
      <h4>'.translate("Add Editorial").'</h4>
      <form action="modules.php" method="post">
         <input type="hidden" name="ModPath" value="'.$ModPath.'" />
         <input type="hidden" name="ModStart" value="'.$ModStart.'" />
         <input type="hidden" name="linkid" value="'.$lid.'" />
         <div class="form-group row">
            <label class="form-control-label col-sm-4" for="editorialtitle">'.translate("Title").'</label>
            <div class="col-sm-8">
               <input class="form-control" type="text" name="editorialtitle" value="" maxlength="100" />
            </div>
         </div>
         <div class="form-group row">
            <label class="form-control-label col-sm-12" for="editorialtext">'.translate("Full Text").'</label>
            <div class="col-sm-12">
               <textarea class="form-control" name="editorialtext" rows="10"></textarea>
            </div>
         </div>
          <input type="hidden" name="op" value="LinksAddEditorial" />
          <input class="btn btn-primary" type="submit" value="'.translate("Add").'" />
      </form>';
       } else {
          list($adminid, $editorialtimestamp, $editorialtext, $editorialtitle) = sql_fetch_row($resulted2);
          $formatted_date=formatTimestamp($editorialtimestamp);
          echo translate("Modify Editorial")." : ".translate("Author")." : $adminid / $formatted_date<br /><br />";
          echo '
      <form action="modules.php" method="post">
         <input type="hidden" name="ModPath" value="'.$ModPath.'" />
         <input type="hidden" name="ModStart" value="'.$ModStart.'" />
         <input type="hidden" name="linkid" value="'.$lid.'" />
         <div class="form-group row">
            <label class="form-control-label col-sm-4" for="editorialtitle">'.translate("Title").'</label>
            <div class="col-sm-8">
               <input class="form-control" type="text" name="editorialtitle" value="'.$editorialtitle.'" maxlength="100" />
            </div>
         </div>
         <div class="form-group row">
            <label class="form-control-label col-sm-12" for="editorialtext">'.translate("Full Text").'</label>
            <div class="col-sm-12">
               <textarea class="form-control" name="editorialtext" rows="10">'.$editorialtext.'</textarea>
            </div>
         </div>
         <input type="hidden" name="op" value="LinksModEditorial" />
         <input class="btn btn-primary" type="submit" value="'.translate("Modify").'" />
         <a href="modules.php?ModStart='.$ModStart.'&amp;ModPath='.$ModPath.'&op=LinksDelEditorial&linkid='.$lid.'" >'.translate("Delete").'</a>
         </form>';
       }
       echo "<hr noshade=\"noshade\" class=\"ongl\" />";
       $pos = strpos($ModPath, "/admin");
       $browse_key=$lid;
       include ("modules/sform/".substr($ModPath,0,$pos)."/link_maj.php");
   }
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
    sql_query("UPDATE ".$links_DB."links_links SET cid='$cat[0]', sid='$cat[1]', title='$title', url='$url', description='$description', name='$name', email='$email', hits='$hits', submitter='$name', topicid_card='$topicL' WHERE lid='$lid'");
    Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath&op=LinksModLink&lid=$lid");
}
function LinksDelLink($lid) {
    global $ModPath, $ModStart, $links_DB;
    $pos = strpos($ModPath, '/admin');
    $modifylinkrequest_adv_infos='Supprimer_MySql';
    include_once("modules/sform/".substr($ModPath,0,$pos)."/link_maj.php");
    // Cette fonction fait partie du formulaire de SFROM !
    Supprimer_function($lid);
    sql_query("DELETE FROM ".$links_DB."links_editorials WHERE linkid='$lid'");
    sql_query("DELETE FROM ".$links_DB."links_links WHERE lid='$lid'");
}
function LinksDelNew($lid) {
    global $ModPath, $ModStart, $links_DB;
    sql_query("DELETE FROM ".$links_DB."links_newlink WHERE lid='$lid'");
    Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath");
}

// ----- Editorial
function LinksModEditorial($linkid, $editorialtitle, $editorialtext) {
    global $ModPath, $ModStart, $links_DB;
    $editorialtext = stripslashes(FixQuotes($editorialtext));
    sql_query("UPDATE ".$links_DB."links_editorials set editorialtext='$editorialtext', editorialtitle='$editorialtitle' WHERE linkid='$linkid'");
    Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath&op=LinksModLink&lid=$linkid");
}
function LinksDelEditorial($linkid) {
    global $ModPath, $ModStart, $links_DB;
    sql_query("DELETE FROM ".$links_DB."links_editorials WHERE linkid='$linkid'");
    Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath&op=LinksModLink&lid=$linkid");
}
function LinksAddEditorial($linkid, $editorialtitle, $editorialtext) {
    global $ModPath, $ModStart, $links_DB;
    $editorialtext = stripslashes(FixQuotes($editorialtext));
    global $aid;
    sql_query("INSERT INTO ".$links_DB."links_editorials VALUES ('$linkid', '$aid', now(), '$editorialtext', '$editorialtitle')");
    Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath&op=LinksModLink&lid=$linkid");
}

// ----- Categories
function LinksAddSubCat($cid, $title) {
    global $ModPath, $ModStart, $links_DB;
    $result = sql_query("SELECT cid FROM ".$links_DB."links_subcategories WHERE title='$title' AND cid='$cid'");
    $numrows = sql_num_rows($result);
    if ($numrows>0) {
        include("header.php");
        opentable();
        echo "<br /><span class=\"text-danger\">".translate("ERROR: The SubCategory")." $title ".translate("already exist!")."</span><br /><br />";
        echo "[ <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath\" class=\"noir\">".translate("Go Back")."</a>]<br />";
        closetable();
        include("footer.php");
    } else {
        sql_query("INSERT INTO ".$links_DB."links_subcategories VALUES (NULL, '$cid', '$title')");
        Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath");
    }
}
function LinksModCat($cat) {
   global $ModPath, $ModStart, $links_DB;
   include ("header.php");
   $cat = explode('-', $cat);
   if (!array_key_exists(1,$cat)) {
      $cat[1] = 0;
   }
   echo '
   <h2>'.translate("Links").'</h2>
   <hr />
   <h3>'.translate("Modify Category").'</h3>';
   if ($cat[1]==0) {
      $result=sql_query("SELECT title, cdescription FROM ".$links_DB."links_categories WHERE cid='$cat[0]'");
      list($title,$cdescription) = sql_fetch_row($result);
      $cdescription = stripslashes($cdescription);
      echo '
      <form method="post" action="modules.php">
         <input type="hidden" name="ModPath" value="'.$ModPath.'" />
         <input type="hidden" name="ModStart" value="'.$ModStart.'" />
         <div class="form-group row">
            <label class="form-control-label col-sm-3" for="title">'.translate("Name").'</label>
            <div class="col-sm-9">
               <input class="form-control" type="text" name="title" value="'.$title.'" maxlength="50" />
            </div>
         </div>
         <div class="form-group row">
            <label class="form-control-label col-sm-12" for="cdescription">'.translate("Description").'</label>
            <div class="col-sm-12">
               <textarea class="textbox_no_mceEditor" name="cdescription" cols="60" rows="10" style="width: 100%;">'.$cdescription.'</textarea></p>
            </div>
         </div>
         <input type="hidden" name="sub" value="0" />
         <input type="hidden" name="cid" value="'.$cat[0].'" />
         <input type="hidden" name="op" value="LinksModCatS" />
         <input class="btn btn-primary" type="submit" value="'.translate("Save Changes").'" />
      </form>
      <form method="post" action="modules.php">
         <input type="hidden" name="ModPath" value="'.$ModPath.'" />
         <input type="hidden" name="ModStart" value="'.$ModStart.'" />
         <input type="hidden" name="sub" value="0" />
         <input type="hidden" name="cid" value="'.$cat[0].'" />
         <input type="hidden" name="op" value="LinksDelCat" />
         <input type="submit" class="btn btn-danger" value="'.translate("Delete").'" />
      </form>';
   } else {
      $result=sql_query("SELECT title FROM ".$links_DB."links_categories WHERE cid='$cat[0]'");
      list($ctitle) = sql_fetch_row($result);
      $result2=sql_query("SELECT title FROM ".$links_DB."links_subcategories WHERE sid='$cat[1]'");
      list($stitle) = sql_fetch_row($result2);
      echo '
      <form method="post" action="modules.php">
      <input type="hidden" name="ModPath" value="'.$ModPath.'" />
      <input type="hidden" name="ModStart" value="'.$ModStart.'" />

      '.translate("Category Name: ").aff_langue($ctitle).'<br /><br />
      '.translate("Sub-Category Name: ").'
      <input class="form-control" type="text" name="title" value="'.$stitle.'" maxlength="250" /></span>
      <input type="hidden" name="sub" value="1" />
      <input type="hidden" name="cid" value="'.$cat[0].'" />
      <input type="hidden" name="sid" value="'.$cat[1].'" />
      <input type="hidden" name="op" value="LinksModCatS" />
      <input type="submit" class="btn btn-primary" value="'.translate("Save Changes").'">
      </form>
      <form method="post" action="modules.php">
      <input type="hidden" name="ModPath" value="'.$ModPath.'" />
      <input type="hidden" name="ModStart" value="'.$ModStart.'" />
      <input type="hidden" name="sub" value="1" />
      <input type="hidden" name="cid" value="'.$cat[0].'" />
      <input type="hidden" name="sid" value="'.$cat[1].'" />
      <input type="hidden" name="op" value="LinksDelCat" />
      <input type="submit" class="btn btn-danger" value="'.translate("Delete").'" />
      </form>';
    }
    include("footer.php");
}
function LinksAddCat($title, $cdescription) {
    global $ModPath, $ModStart, $links_DB;
    $result = sql_query("SELECT cid FROM ".$links_DB."links_categories WHERE title='$title'");
    $numrows = sql_num_rows($result);
    if ($numrows>0) {
        include("header.php");
        opentable();
        echo "<br /><span class=\"rouge\">".translate("ERROR: The Category")." $title ".translate("already exist!")."</span><br /><br />";
        echo "[ <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath\" class=\"noir\">".translate("Go Back")."</a>]<br /><br />";
        closetable();
        include("footer.php");
    } else {
        sql_query("INSERT INTO ".$links_DB."links_categories VALUES (NULL, '$title', '$cdescription')");
        Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath");
    }
}
function LinksModCatS($cid, $sid, $sub, $title, $cdescription) {
    global $ModPath, $ModStart, $links_DB;
    if ($sub==0) {
        sql_query("UPDATE ".$links_DB."links_categories set title='$title', cdescription='$cdescription' WHERE cid='$cid'");
    } else {
        sql_query("UPDATE ".$links_DB."links_subcategories set title='$title' WHERE sid='$sid'");
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
          $result=sql_query("SELECT lid FROM ".$links_DB."links_links WHERE sid='$sid'");
          while (list($lid)=sql_fetch_row($result)) {
             LinksDelLink($lid);
          }
          sql_query("DELETE FROM ".$links_DB."links_subcategories WHERE sid='$sid'");
          sql_query("DELETE FROM ".$links_DB."links_links WHERE sid='$sid'");
       } else {
          $result=sql_query("SELECT lid FROM ".$links_DB."links_links WHERE cid='$cid'");
          while (list($lid)=sql_fetch_row($result)) {
             LinksDelLink($lid);
          }
          sql_query("DELETE FROM ".$links_DB."links_categories WHERE cid='$cid'");
          sql_query("DELETE FROM ".$links_DB."links_subcategories WHERE cid='$cid'");
       }
       Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath");
    } else {
       include("header.php");
       opentable();
       echo "<br /><span class=\"text-danger\">".translate("WARNING: Are you sure you want to delete this Category and ALL its Links?")."</span><br /><br />";
       echo "[ <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&op=LinksDelCat&cid=$cid&sid=$sid&sub=$sub&ok=1\" class=\"rouge\">".translate("Yes")."</a> | <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath\" class=\"noir\">".translate("No")."</a> ]<br /><br />";
       closetable();
       include("footer.php");
   }
}

// ----- Broken and Changes
function LinksListModRequests() {
   global $ModPath, $ModStart, $links_DB, $NPDS_Prefix;

   $resultX = sql_query("SELECT requestid, lid, cid, sid, title, url, description, modifysubmitter, topicid_card FROM ".$links_DB."links_modrequest WHERE brokenlink=0 ORDER BY requestid");
   $totalmodrequests = sql_num_rows($resultX);
   if ($totalmodrequests==0) {
      Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath");
   }
   include ("header.php");
   $x_mod='';$x_ori='';
   function clformodif($x_ori,$x_mod){
   if ($x_ori != $x_mod) return ' class="text-danger" ';}

   echo '
   <h3>'.translate("User Link Modification Requests").' <span class="label label-default pull-right">'.$totalmodrequests.'</span></h3>';
   while (list($requestid, $lid, $cid, $sid, $title, $url, $description, $modifysubmitter, $topicid_card)=sql_fetch_row($resultX)) {
      $result2 = sql_query("SELECT cid, sid, title, url, description, submitter, topicid_card FROM ".$links_DB."links_links WHERE lid='$lid'");
      list($origcid, $origsid, $origtitle, $origurl, $origdescription, $owner, $oritopicid_card)=sql_fetch_row($result2);
      $result3 = sql_query("SELECT title FROM ".$links_DB."links_categories WHERE cid='$cid'");
      $result4 = sql_query("SELECT title FROM ".$links_DB."links_subcategories WHERE cid='$cid' AND sid='$sid'");
      $result5 = sql_query("SELECT title FROM ".$links_DB."links_categories WHERE cid='$origcid'");
      $result6 = sql_query("SELECT title FROM ".$links_DB."links_subcategories WHERE cid='$origcid' AND sid='$origsid'");
      $result7 = sql_query("SELECT email FROM ".$NPDS_Prefix."users WHERE uname='$modifysubmitter'");
      $result8 = sql_query("SELECT email FROM ".$NPDS_Prefix."users WHERE uname='$owner'");
      $result9 = sql_query("SELECT topictext FROM ".$NPDS_Prefix."topics WHERE topicid='$oritopicid_card'");
      $result9b = sql_query("SELECT topictext FROM ".$NPDS_Prefix."topics WHERE topicid='$topicid_card'");
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
      if ($owner=='') { $owner="administration"; }
      if ($origsidtitle=='') { $origsidtitle= "-----"; }
      if ($sidtitle=='') { $sidtitle= "-----"; }
      
      echo '
   <div class="card-deck-wrapper">
     <div class="card-deck">
       <div class="card card-block">
         <h4>'.translate("Original").'</h4>
         <strong>'.translate("Description:").'</strong> <div>'.$origdescription.'</div><br />
         <strong>'.translate("Title:").'</strong> '.$origtitle.'<br />
         <strong>'.translate("URL:").'</strong> <a href="'.$origurl.'" target="_blank" >'.$origurl.'</a><br />';
           global $links_topic;
           if ($links_topic)
              echo '
         <strong>'.translate("Topic").' :</strong> '.$oritopic.'<br />';
           echo '
         <strong>'.translate("Cat:").'</strong> '.$origcidtitle.'<br />
         <strong>'.translate("Subcat:").'</strong> '.$origsidtitle.'<br />
      </div>
      <div class="card card-block">
         <h4>'.translate("Proposed").'</h4>
         <strong>'.translate("Description:").'</strong><div'.clformodif($origdescription,$description).'>'.$description.'</div><br />
         <strong>'.translate("Title:").'</strong> <span'.clformodif($origtitle,$title).'>'.$title.'</span><br />
         <strong>'.translate("URL:").'</strong> <span'.clformodif($origurl,$url).'><a href="'.$url.'" target="_blank" >'.$url.'</a></span><br />';
           global $links_topic;
           if ($links_topic)
              echo '
         <strong>'.translate("Topic").' :</strong> <span'.clformodif($oritopic,$topic).'>'.$topic.'</span><br/>';
           echo '
         <strong>'.translate("Cat:").'</strong> <span'.clformodif($origcidtitle,$cidtitle).'>'.$cidtitle.'</span><br/>
         <strong>'.translate("Subcat:").'</strong> <span'.clformodif($origsidtitle,$sidtitle).'>'.$sidtitle.'</span><br/>
      </div>
   </div>';
          if ($modifysubmitteremail=='')
             echo "<td align=\"left\" class=\"ongl\" width=\"30%\">".translate("Submitter")." :  $modifysubmitter</td>";
          else
             echo "<td align=\"left\" class=\"ongl\" width=\"30%\">".translate("Submitter")." :  <a href=\"mailto:$modifysubmitteremail\" class=\"noir\">$modifysubmitter</a></td>";
          if ($owneremail=="")
             echo "<td align=\"center\" class=\"ongl\" width=\"30%\">".translate("Owner")." :  $owner</td>";
          else
             echo "<td align=\"center\" class=\"ongl\" width=\"30%\">".translate("Owner")." : <a href=\"mailto:$owneremail\" class=\"noir\">$owner</a></td>";
          echo "<td align=\"right\" class=\"ongl\">( <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&op=LinksChangeModRequests&requestid=$requestid\" class=\"noir\">".translate("Accept")."</a> / <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath&op=LinksChangeIgnoreRequests&requestid=$requestid\" class=\"noir\">".translate("Ignore")."</a> )</td>
        </tr>
   </div>";
   }
   sql_free_result;
   include ("footer.php");
}

// ----- Broken
function LinksListBrokenLinks() {
    global $ModPath, $ModStart, $links_DB, $NPDS_Prefix;

   $resultBrok = sql_query("SELECT requestid, lid, modifysubmitter FROM ".$links_DB."links_modrequest WHERE brokenlink=1 ORDER BY requestid");
   $totalbrokenlinks = sql_num_rows($resultBrok);
   if ($totalbrokenlinks==0) {
      Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath");
   } else {
   include ("header.php");
      echo '
   <h3>'.translate("User Reported Broken Links").' <span class="label label-default pull-right"> '.$totalbrokenlinks.'</span></h3>';
       echo "<br />
       ".translate("Ignore (Deletes all requests for a given link)")."<br /><br />
       ".translate("Delete (Deletes broken link and requests for a given link)")."<br /><br />";
       echo '
   <table data-toggle="table" >
      <thead>
         <tr>
            <th data-sortable="true" data-halign="center">'.translate("Links").'</th>
            <th data-sortable="true" data-halign="center">'.translate("Submitter").'</th>
            <th data-sortable="true" data-halign="center">'.translate("Owner").'</th>
            <th data-halign="center" data-align="right">'.translate("Ignore").'</th>
            <th data-halign="center" data-align="right">'.translate("Delete").'</th>
         </tr>
      </thead>
      <tbody>';
       while (list($requestid, $lid, $modifysubmitter)=sql_fetch_row($resultBrok)) {
          $rowcolor = tablos();
          $result2 = sql_query("SELECT title, url, submitter FROM ".$links_DB."links_links WHERE lid='$lid'");
          if ($modifysubmitter != '$anonymous') {
             $result3 = sql_query("SELECT email FROM ".$NPDS_Prefix."users WHERE uname='$modifysubmitter'");
             list($email)=sql_fetch_row($result3);
          }
          list($title, $url, $owner)=sql_fetch_row($result2);
          $result4 = sql_query("SELECT email FROM ".$NPDS_Prefix."users WHERE uname='$owner'");
          list($owneremail)=sql_fetch_row($result4);
          echo '
         <tr>
            <td><a href="'.$url.'"  target="_blank">'.$title.'</a></td>';
          if ($email=='') { echo '
            <td>'.$modifysubmitter; }
          else { echo '
            <td><a href="mailto:'.$email.'" >'.$modifysubmitter.'</a>'; }
          echo '</td>';
          if ($owneremail=='') { echo '
            <td>'.$owner; }
          else { echo '
            <td><a href="mailto:'.$owneremail.'" >'.$owner.'</a>'; }
          echo '</td>
            <td><a href="modules.php?ModStart='.$ModStart.'&amp;ModPath='.$ModPath.'&op=LinksIgnoreBrokenLinks&lid='.$lid.'" >'.translate("Ignore").'</a></td>
            <td><a href="modules.php?ModStart='.$ModStart.'&amp;ModPath='.$ModPath.'&op=LinksDelBrokenLinks&lid='.$lid.'" >'.translate("Delete").'</a></td>
         </tr>';
       }
       echo '
      </tbody>
   </table>';
       include ("footer.php");
   }
}
function LinksDelBrokenLinks($lid) {
   global $ModPath, $ModStart, $links_DB;
   sql_query("DELETE FROM ".$links_DB."links_modrequest WHERE lid='$lid'");
   LinksDelLink($lid);
   Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath&op=LinksListBrokenLinks");
}

function LinksIgnoreBrokenLinks($lid) {
    global $ModPath, $ModStart, $links_DB;
    sql_query("DELETE FROM ".$links_DB."links_modrequest WHERE lid='$lid' AND brokenlink=1");
    Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath&op=LinksListBrokenLinks");
}

// ----- Change Links
function LinksChangeModRequests($Xrequestid) {
   global $ModPath, $ModStart, $links_DB;
   $result = sql_query("SELECT requestid, lid, cid, sid, title, url, description, topicid_card FROM ".$links_DB."links_modrequest WHERE requestid='$Xrequestid'");
   while (list($requestid, $lid, $cid, $sid, $title, $url, $description, $topicid_card)=sql_fetch_row($result)) {
      $title = stripslashes($title);
      $description = stripslashes($description);
      sql_query("UPDATE ".$links_DB."links_links SET cid=$cid, sid=$sid, title='$title', url='$url', description='$description', topicid_card='$topicid_card' WHERE lid='$lid'");
   }
   sql_query("DELETE FROM ".$links_DB."links_modrequest WHERE requestid='$Xrequestid'");
   Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath&op=LinksListModRequests");
}
function LinksChangeIgnoreRequests($requestid) {
   global $ModPath, $ModStart, $links_DB;
   sql_query("DELETE FROM ".$links_DB."links_modrequest WHERE requestid='$requestid'");
   Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath&op=LinksListModRequests");
}

settype($op,'string');
switch ($op) {
   case 'LinksDelNew':
      LinksDelNew($lid);
      break;
   case 'LinksAddCat':
      LinksAddCat($title, $cdescription);
      break;
   case 'LinksAddSubCat':
      LinksAddSubCat($cid, $title);
      break;
   case 'LinksAddLink':
      if ($xtext=='') $xtext=$description;
      LinksAddLink($new, $lid, $title, $url, $cat, $xtext, $name, $email, $submitter, $topicL);
      break;
   case 'LinksAddEditorial':
      LinksAddEditorial($linkid, $editorialtitle, $editorialtext);
      break;
   case 'LinksModEditorial':
      LinksModEditorial($linkid, $editorialtitle, $editorialtext);
      break;
   case 'LinksDelEditorial':
      LinksDelEditorial($linkid);
      break;
   case 'LinksListBrokenLinks':
      LinksListBrokenLinks();
      break;
   case 'LinksDelBrokenLinks':
      LinksDelBrokenLinks($lid);
      break;
   case 'LinksIgnoreBrokenLinks':
      LinksIgnoreBrokenLinks($lid);
      break;
   case 'LinksListModRequests':
      LinksListModRequests();
      break;
   case 'LinksChangeModRequests':
      LinksChangeModRequests($requestid);
      break;
   case 'LinksChangeIgnoreRequests':
      LinksChangeIgnoreRequests($requestid);
      break;
   case 'LinksDelCat':
      LinksDelCat($cid, $sid, $sub, $ok);
      break;
   case 'LinksModCat':
      LinksModCat($cat);
      break;
   case 'LinksModCatS':
      LinksModCatS($cid, $sid, $sub, $title, $cdescription);
      break;
   case 'LinksModLink':
   case 'modifylinkrequest':
      settype($modifylinkrequest_adv_infos,'string');
      LinksModLink($lid, $modifylinkrequest_adv_infos);
      break;
   case 'LinksModLinkS':
      LinksModLinkS($lid, $title, $url, $xtext, $name, $email, $hits, $cat, $topicL);
      break;
   case 'LinksDelLink':
      LinksDelLink($lid);
      Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath");
      break;
   default:
      links();
      break;
}
?>
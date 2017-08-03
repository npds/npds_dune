<?php
/************************************************************************/                                                                                                                                  /* DUNE by NPDS                                                         */
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2017 by Philippe Brunier   */
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
   $mainlink = 'ad_l';
   menu($mainlink);
   SearchForm();
   echo '
   <div class="alert '.$class.'" role="alert" align="center">';
}
function error_foot() {
   echo '
   </div>';
   include("footer.php");
}

function AddLink() {
   global $ModPath, $ModStart, $links_DB, $NPDS_Prefix, $links_anonaddlinklock,$op;
   include("header.php");
   global $user,$ad_l;
   mainheader();
   if (autorisation($links_anonaddlinklock)) {
      echo '
   <div class="card card-body mb-3">
      <h3 class="mb-3">Proposer un lien</h3>
      <div class="card card-outline-secondary mb-3">
         <div class="card-body">
            <span class="help-block">'.translate("Submit a unique link only once.").'<br />'.translate("All links are posted pending verification.").'<br />'.translate("Username and IP are recorded, so please don't abuse the system.").'</span>
         </div>
      </div>
      <form method="post" action="modules.php" name="adminForm">
         <input type="hidden" name="ModPath" value="'.$ModPath.'" />
         <input type="hidden" name="ModStart" value="'.$ModStart.'" />
         <div class="form-group row">
            <label class="form-control-label col-sm-3" for="title">'.translate("Title").'</label>
            <div class="col-sm-9">
               <input class="form-control" type="text" id="title" name="title" maxlength="100" required="required" />
               <span class="help-block text-right"><span id="countcar_title"></span></span>
           </div>
        </div>';
        global $links_url;
        if (($links_url) or ($links_url==-1))
        echo'
         <div class="form-group row">
            <label class="form-control-label col-sm-3" for="url">URL</label>
            <div class="col-sm-9">
               <input class="form-control" type="url" id="url" name="url" maxlength="100" value="http://" required="required" />
               <span class="help-block text-right"><span id="countcar_url"></span></span>
           </div>
        </div>';
        $result=sql_query("SELECT cid, title FROM ".$links_DB."links_categories ORDER BY title");
        echo'
         <div class="form-group row">
            <label class="form-control-label col-sm-3" for="cat">'.translate("Category").'</label>
            <div class="col-sm-9">
               <select class="custom-select form-control" id="cat" name="cat">';
        while (list($cid, $title) = sql_fetch_row($result)) {
           echo '
                  <option value="'.$cid.'">'.aff_langue($title).'</option>';
           $result2=sql_query("select sid, title from ".$links_DB."links_subcategories WHERE cid='$cid' ORDER BY title");
           while (list($sid, $stitle) = sql_fetch_row($result2)) {
              echo '
                  <option value="'.$cid.'-'.$sid.'">'.aff_langue($title.'/'. $stitle).'</option>';
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
               <select class="custom-select form-control" name="topicL">';
           $toplist = sql_query("SELECT topicid, topictext FROM ".$NPDS_Prefix."topics ORDER BY topictext");
           echo '
                  <option value="">'.translate("All Topics").'</option>';
           while(list($topicid, $topics) = sql_fetch_row($toplist)) {
             echo '
                  <option value="'.$topicid.'">'.$topics.'</option>';
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
               <textarea class="tin form-control" name="xtext" id="xtext" rows="10"></textarea>
            </div>
         </div>';
        echo aff_editeur('xtext','');
        global $cookie;
        echo '
         <div class="form-group row">
            <label class="form-control-label col-sm-3" for="name">'.translate("Your Name").'</label>
            <div class="col-sm-9">
               <input type="text" class="form-control" id="name" name="name" maxlength="60" value="'.$cookie[1].'" required="required" />
            </div>
         </div>
         <div class="form-group row">
            <label class="form-control-label col-sm-3" for="email">'.translate("Your Email").'</label>
            <div class="col-sm-9">
               <input type="email" class="form-control" id="email" name="email" maxlength="60" required="required" />
            </div>
         </div>';
        echo Q_spambot();
        echo '
         <div class="form-group row">
            <input type="hidden" name="op" value="Add" />
            <div class="col-sm-9 ml-sm-auto">
               <input type="submit" class="btn btn-primary" value="'.translate("Add URL").'" />
            </div>
         </div>
      </form>
         </div>
         <div>
      <script type="text/javascript">
         //<![CDATA[
            $(document).ready(function() {
               inpandfieldlen("title",100);
               inpandfieldlen("url",100);
            });
         //]]>
      </script>
      </div>';
      SearchForm();
      adminfoot('fv','','','1');
      include("footer.php");
   } else {
      echo '
        <div class="alert alert-warning">'.translate("You are not a registered user or you have not logged in.").'<br />
        '.translate("If you were registered you could add links on this website.").'</div>';
      SearchForm();
      include("footer.php");
   }
}

function Add($title, $url, $name, $cat, $description, $email, $topicL, $asb_question, $asb_reponse) {
   global $ModPath, $ModStart, $links_DB, $troll_limit, $anonymous, $user, $admin;
   if (!$user and !$admin) {
      //anti_spambot
      if (!R_spambot($asb_question, $asb_reponse, '')) {
         Ecr_Log('security', 'Links Anti-Spam : url='.$url, '');
         redirect_url("index.php");
         die();
      }
   }

   $result = sql_query("SELECT lid FROM ".$links_DB."links_newlink");
   $numrows = sql_num_rows($result);
   if ($numrows>=$troll_limit) {
      error_head("alert-danger");
      echo translate("ERROR: This URL is already listed in the Database!").'<br />';
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
   if ($title=='') {
      error_head('alert-danger');
      echo translate("ERROR: You need to type a TITLE for your URL!").'<br />';
      error_foot();
      exit();
   }
   if ($email=='') {
      error_head('alert-danger');
      echo translate("ERROR: Invalid email").'<br />';
      error_foot();
      exit();
   }
   global $links_url;
   if (($url=='') and ($links_url==1)) {
      error_head('alert-danger');
      echo translate("ERROR: You need to type a URL for your URL!").'<br />';
      error_foot();
      exit();
   }
   if ($description=='') {
      error_head('alert-danger');
      echo translate("ERROR: You need to type a DESCRIPTION for your URL!").'<br />';
      error_foot();
      exit();
   }
   $cat = explode('-', $cat);
   if (!array_key_exists(1,$cat)) {
      $cat[1] = 0;
   }
   $title = removeHack(stripslashes(FixQuotes($title)));
   $url = removeHack(stripslashes(FixQuotes($url)));
   $description = removeHack(stripslashes(FixQuotes($description)));
   $name = removeHack(stripslashes(FixQuotes($name)));
   $email = removeHack(stripslashes(FixQuotes($email)));
   sql_query("INSERT INTO ".$links_DB."links_newlink VALUES (NULL, '$cat[0]', '$cat[1]', '$title', '$url', '$description', '$name', '$email', '$submitter', '$topicL')");
   error_head('alert-success');
   echo translate("We received your Link submission. Thanks!").'<br />';
   echo translate("You'll receive and E-mail when it's approved.").'<br />';
   error_foot();
}

function links_search($query, $topicL, $min, $max, $offset) {
   global $ModPath, $ModStart, $links_DB;
   include ("header.php");
   mainheader();
   $filen="modules/$ModPath/links.ban_02.php";
   if (file_exists($filen)) {include($filen);}
   $query = removeHack(stripslashes(htmlspecialchars($query,ENT_QUOTES,cur_charset))); // Romano et NoSP

   if ($topicL!='') {
      $result = sql_query("SELECT lid, url, title, description, date, hits, topicid_card, cid, sid FROM ".$links_DB."links_links WHERE topicid_card='$topicL' AND (title LIKE '%$query%' OR description LIKE '%$query%') ORDER BY lid ASC LIMIT $min,$offset");
   } else {
      $result = sql_query("SELECT lid, url, title, description, date, hits, topicid_card, cid, sid FROM ".$links_DB."links_links WHERE title LIKE '%$query%' OR description LIKE '%$query%' ORDER BY lid ASC LIMIT $min,$offset");
   }
   if ($result) {
      $link_fiche_detail='';
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
<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2024 by Philippe Brunier   */
/*                                                                      */
/* New Links.php Module with SFROM extentions                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],'modules.php')) die();

function modifylinkrequest($lid, $modifylinkrequest_adv_infos, $author) {
   global $ModPath, $ModStart, $links_DB, $NPDS_Prefix;

   if (autorise_mod($lid,false)) {
      if ($author=='-9')
         Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath/admin&op=LinksModLink&lid=$lid");
      include("header.php");
      mainheader();
      $result = sql_query("SELECT cid, sid, title, url, description, topicid_card FROM ".$links_DB."links_links WHERE lid='$lid'");
      list($cid, $sid, $title, $url, $description, $topicid_card) = sql_fetch_row($result);
      $title = stripslashes($title);
      $description = stripslashes($description);
      echo '
   <h3 class="my-3">'.translate("Proposition de modification").' : <span class="text-body-secondary">'.$title.'</span></h3>
   <form action="modules.php" method="post" name="adminForm">
      <input type="hidden" name="ModPath" value="'.$ModPath.'" />
      <input type="hidden" name="ModStart" value="'.$ModStart.'" />
      <div class="mb-3 row">
         <label class="col-form-label col-sm-3" for="title">'.translate("Titre").'</label>
         <div class="col-sm-9">
            <input class="form-control" type="text" id="title" name="title" value="'.$title.'"  maxlength="100" required="required" />
         </div>
      </div>';
      global $links_url;
      if ($links_url)
         echo '
      <div class="mb-3 row">
         <label class="col-form-label col-sm-3" for="url">URL</label>
         <div class="col-sm-9">
            <input class="form-control" type="url" id="url" name="url" value="'.$url.'" maxlength="100" required="required" />
         </div>
      </div>';
      echo '
      <div class="mb-3 row">
         <label class="col-form-label col-sm-3" for="cat">'.translate("Catégorie").'</label>
         <div class="col-sm-9">
            <select class="form-select" id="cat" name="cat">';
      $result2=sql_query("SELECT cid, title FROM ".$links_DB."links_categories ORDER BY title");
      while (list($ccid, $ctitle) = sql_fetch_row($result2)) {
         $sel = '';
         if ($cid==$ccid AND $sid==0) $sel = 'selected';
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
         echo'
      <div class="mb-3 row">
         <label class="col-form-label col-sm-3" for="topicL">'.translate("Sujets").'</label>
         <div class="col-sm-9">
            <select class="form-select" id="topicL" name="topicL">';
         $toplist = sql_query("SELECT topicid, topictext FROM ".$NPDS_Prefix."topics ORDER BY topictext");
         echo '
               <option value="">'.translate("Tous les sujets").'</option>';
         while(list($topicid, $topics) = sql_fetch_row($toplist)) {
           if ($topicid==$topicid_card) $sel = 'selected="selected" ';
           echo '
               <option value="'.$topicid.'" '.$sel.'>'.$topics.'</option>';
           $sel = '';
         }
         echo '
            </select>
         </div>
      </div>';
      }
      echo'
      <div class="mb-3 row">
         <label class="col-form-label col-sm-12" for="xtext">'.translate("Description : (255 caractères max)").'</label>
         <div class="col-sm-12">
            <textarea class="form-control tin" id="xtext" name="xtext" rows="10">'.$description.'</textarea>
         </div>
      </div>';
      aff_editeur('xtext','');
      echo '
      <div class="mb-3 row">
         <input type="hidden" name="lid" value="'.$lid.'" />
         <input type="hidden" name="modifysubmitter" value="'.$author.'" />
         <input type="hidden" name="op" value="modifylinkrequestS" />
         <div class="col-sm-12">
            <input type="submit" class="btn btn-primary" value="'.translate("Envoyer une demande").'" />
         </div>
      </div>
   </form>';
      $browse_key=$lid;
      include ("modules/sform/$ModPath/link_maj.php");
      adminfoot('fv','','','nodiv');
      include("footer.php");
   } else
      header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath");
}

function modifylinkrequestS($lid, $cat, $title, $url, $description, $modifysubmitter, $topicL) {
   global $links_DB;
   if (autorise_mod($lid,false)) {
      $cat = explode('-', $cat);
      if (!array_key_exists(1,$cat))
         $cat[1] = 0;
      $title = stripslashes(FixQuotes($title));
      $url = stripslashes(FixQuotes($url));
      $description = stripslashes(FixQuotes($description));
      if ($modifysubmitter==-9) $modifysubmitter='';
      $result=sql_query("INSERT INTO ".$links_DB."links_modrequest VALUES (NULL, $lid, $cat[0], $cat[1], '$title', '$url', '$description', '$modifysubmitter', '0', '$topicL')");

      global $ModPath, $ModStart;
      include("header.php");
      echo '
      <h3 class="my-3">'.translate("Liens").'</h3>
      <hr />
      <h4 class="my-3">'.translate("Proposition de modification").'</h4>
      <div class="alert alert-success">'.translate("Merci pour cette information. Nous allons l'examiner dès que possible.").'</div>
      <a class="btn btn-primary" href="modules.php?ModPath=links&amp;ModStart=links">Index </a>';
      include("footer.php");
   }
}

function brokenlink($lid) {
   global $ModPath, $ModStart, $links_DB, $anonymous;
   include("header.php");
   global $user;
   if (isset($user)) {
      global $cookie;
      $ratinguser=$cookie[1];
   } else
      $ratinguser=$anonymous;
   mainheader();
   echo '
   <h3>'.translate("Rapporter un lien rompu").'</h3>
   <div class="alert alert-success my-3">
          '.translate("Merci de contribuer à la maintenance du site.").'
          <br />
          <strong>'.translate("Pour des raisons de sécurité, votre nom d'utilisateur et votre adresse IP vont être momentanément conservés.").'</strong>
          <br />
   </div>
   <form method="post" action="modules.php">
      <input type="hidden" name="ModPath" value="'.$ModPath.'" />
      <input type="hidden" name="ModStart" value="'.$ModStart.'" />
      <input type="hidden" name="lid" value="'.$lid.'" />
      <input type="hidden" name="modifysubmitter" value="'.$ratinguser.'" />
      <input type="hidden" name="op" value="brokenlinkS" />
      <input type="submit" class="btn btn-success" value="'.translate("Rapporter un lien rompu").'" />
   </form>';
   include("footer.php");
}

function brokenlinkS($lid, $modifysubmitter) {
   global $user, $links_DB, $ModPath, $ModStart;
   if (isset($user)) {
      global $cookie;
      $ratinguser = $cookie[1];
   } else
      $ratinguser = $anonymous;
   if ($modifysubmitter==$ratinguser) {
      settype($lid,'integer');
      sql_query("INSERT INTO ".$links_DB."links_modrequest VALUES (NULL, $lid, 0, 0, '', '', '', '$ratinguser', 1,0)");
   }
   include("header.php");
   mainheader();
   echo '
   <h3>'.translate("Rapporter un lien rompu").'</h3>
   <div class="alert alert-success my-3">
   '.translate("Merci pour cette information. Nous allons l'examiner dès que possible.").'
   </div>';
   include("footer.php");
}
?>
<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2024 by Philippe Brunier   */
/*                                                                      */
/* New Links.php Module with SFORM extentions                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
if (!function_exists("Access_Error")) die();
if (!stristr($_SERVER['PHP_SELF'],"modules.php")) Access_Error();

global $language, $links_DB, $NPDS_Prefix;

$pos = strpos($ModPath, '/admin');
include_once('modules/'.substr($ModPath,0,$pos).'/links.conf.php');
if ($links_DB=='')
   $links_DB=$NPDS_Prefix;
$hlpfile = "modules/".substr($ModPath,0,$pos)."/manual/$language/mod-weblinks.html";

if(autorisation(-127)) {
   $result = sql_query("SELECT radminsuper FROM ".$NPDS_Prefix."authors WHERE aid='$aid'");
   list($radminsuper) = sql_fetch_row($result);
   if ($radminsuper!=1) //intégrer les droits nouveau système
   Access_Error();
}
else
   Access_Error();


function helpwindow() {
   global $hlpfile;
   echo '
   <script type="text/javascript">
   //<![CDATA[
      function openwindow() {
      window.open ("'.$hlpfile.'","Help","toolbar=no,location=no,directories=no,status=no,scrollbars=yes,resizable=no,copyhistory=no,width=600,height=400");
   }
   //]]>
   </script>';
}

function links() {
   global $ModPath, $ModStart, $links_DB, $admin, $language, $NPDS_Prefix, $hlpfile;
   include ("header.php");

/*
   echo '
   <script type="text/javascript">
   //<![CDATA[
   var e;
    function ouvrewindow() {
      e = window.open("'.$hlpfile.'","Help","toolbar=no,location=no,directories=no,status=no,scrollbars=yes,resizable=no,copyhistory=no,width=600,height=400");
   };
   //]]>
   </script>';
*/



   helpwindow();

   $result=sql_query("SELECT * FROM ".$links_DB."links_links");
   $numrows = sql_num_rows($result);
   echo '
   <h2>'.translate("Liens").'<span class="badge bg-secondary mx-2 float-end" title="DB : '.$links_DB.translate("Il y a").' '.$numrows.' '.translate("Liens").'" data-bs-toggle="tooltip">'.$numrows.'</span></h2>
   <hr class="mb-0" />
   <div class="text-end mt-1 mb-2"><a href="javascript:openwindow();">'.translate("Manuel en ligne").'</a><i class="fa fa-cogs ms-3 fa-lg text-body-secondary"></i></div>';
   echo '
   <nav aria-label="breadcrumb">
      <ol class="breadcrumb">';
   $result = sql_query("SELECT * FROM ".$links_DB."links_modrequest WHERE brokenlink=1");
   $totalbrokenlinks = sql_num_rows($result);
   $result2 = sql_query("SELECT * FROM ".$links_DB."links_modrequest WHERE brokenlink=0");
   $totalmodrequests = sql_num_rows($result2);
   if($totalbrokenlinks>0)
      echo '
         <li class="breadcrumb-item"><a href="modules.php?ModStart='.$ModStart.'&amp;ModPath='.$ModPath.'&amp;op=LinksListBrokenLinks">'.translate("Soumission de liens brisés").'</a><span class="badge bg-danger ms-1"> '.$totalbrokenlinks.'</span></li>';
   if($totalmodrequests>0)
      echo '
         <li class="breadcrumb-item"><a href="modules.php?ModStart='.$ModStart.'&amp;ModPath='.$ModPath.'&amp;op=LinksListModRequests">'.translate("Proposition de modifications de liens").'</a><span class="badge bg-danger ms-1">'.$totalmodrequests.'</span></li>';

   $result = sql_query("SELECT lid, cid, sid, title, url, description, name, email, submitter, topicid_card FROM ".$links_DB."links_newlink ORDER BY lid ASC LIMIT 0,1");
   $numrows = sql_num_rows($result);
   $adminform='';
   if ($numrows>0) {
      echo '
         <li class="breadcrumb-item">'.translate("Lien(s) en attente de validation").'<span class="badge bg-danger ms-1">'.$numrows.'</span></li>
      </ol>
   </nav>';
      $adminform='adminForm';
      list($lid, $cid, $sid, $title, $url, $description, $name, $email, $submitter, $topicid_card) = sql_fetch_row($result);
      // Le lien existe déjà dans la table ?
      $resultAE = sql_query("SELECT url FROM ".$links_DB."links_links WHERE url='$url'");
      $numrowsAE = sql_num_rows($resultAE);
      echo '
   <div class="card card-body mb-3">
   <h3 class="mb-3">'.translate("Lien").' <span class="text-body-secondary">#'.$lid.'</span> '.translate("en attente de validation").'</h3>
   <div class="lead">'.translate("Auteur").' : '.$submitter.' </div>
   <hr />
   <form action="modules.php" method="post" name="'.$adminform.'">
      <input type="hidden" name="ModPath" value="'.$ModPath.'" />
      <input type="hidden" name="ModStart" value="'.$ModStart.'" />';
          if ($numrowsAE>0)
             echo "&nbsp;&nbsp;<span class=\"rouge\">".translate("Erreur : cette url est déjà présente dans la base de données")."</span>";
          echo '
      <div class="mb-3 row">
         <label class="col-form-label col-sm-3" for="titlelinkvalid">'.translate("Titre").'</label>
         <div class="col-sm-9">
            <input class="form-control" type="text" id="titlelinkvalid" name="title" value="'.$title.'" maxlength="100" />
         </div>
      </div>';
          global $links_url;
          if ($links_url)
             echo '
         <div class="mb-3 row">
            <label class="col-form-label col-sm-3" for="urllinkvalid">URL</label>
            <div class="col-sm-9">
                <input class="form-control" type="url" id="urllinkvalid" name="url" value="'.$url.'" maxlength="255" /> <a href="'.$url.'" target="_blank" >'.translate("Visite").'</a>
            </div>
         </div>';
          $result2=sql_query("SELECT cid, title FROM ".$links_DB."links_categories ORDER BY title");
          echo '
         <div class="mb-3 row">
            <label class="col-form-label col-sm-3" for="catlinkvalid">'.translate("Catégorie").'</label>
            <div class="col-sm-9">
               <select class="form-select" id="catlinkvalid" name="cat">';
          while (list($ccid, $ctitle) = sql_fetch_row($result2)) {
             $sel = '';
             if ($cid==$ccid AND $sid==0)
                $sel = 'selected="selected"';
             echo '
                  <option value="'.$ccid.'" '.$sel.'>'.aff_langue($ctitle).'</option>';
             $result3=sql_query("SELECT sid, title FROM ".$links_DB."links_subcategories WHERE cid='$ccid' ORDER BY title");
             while (list($ssid, $stitle) = sql_fetch_row($result3)) {
                   $sel = '';
                if ($sid==$ssid)
                   $sel = 'selected="selected"';
                echo '
                  <option value="'.$ccid.'-'.$ssid.'" '.$sel.'>'.aff_langue($ctitle).' / '.aff_langue($stitle).'</option>';
             }
          }
          echo '
               </select>
            </div>
         </div>';
          global $links_topic;
          if ($links_topic) {
             echo '
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
                  <option '.$sel.' value="'.$topicid.'">'.aff_langue($topics).'</option>';
               $sel = '';
             }
             echo '
               </select>
            </div>
         </div>';
          }
         echo ' 
         <div class="mb-3 row">
            <label class="col-form-label col-sm-12" for="xtext">'.translate("Description").'</label>
            <div class="col-sm-12">
               <textarea class="tin form-control" name="xtext" rows="10" style="width: 100%;">'.$description.'</textarea>
            </div>
         </div>';
         echo aff_editeur('xtext','');
         echo '
          <div class="mb-3 row">
            <label class="col-form-label col-sm-3" for="name">'.translate("Nom").'</label>
            <div class="col-sm-9">
               <input class="form-control" type="text" name="name" maxlength="100" value="'.$name.'" />
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-3" for="email">'.translate("Email").'</label>
            <div class="col-sm-9">
               <input class="form-control" type="email" name="email" maxlength="100" value="'.$email.'" />
            </div>
         </div>
         <div class="mb-3 row">
            <div class="col-sm-9 ms-sm-auto">
               <input type="hidden" name="new" value="1" />
               <input type="hidden" name="lid" value="'.$lid.'" />
               <input type="hidden" name="submitter" value="'.$submitter.'" />
               <input type="hidden" name="op" value="LinksAddLink" />
               <input class="btn btn-primary" type="submit" value="'.translate("Ajouter").'" />
               <a class="btn btn-danger ms-2" href="modules.php?ModStart='.$ModStart.'&amp;ModPath='.$ModPath.'&amp;op=LinksDelNew&amp;lid='.$lid.'">'.translate("Effacer").'</a>
            </div>
         </div>
      </form>
   </div>';
   }
   else
      echo '
      </ol>
   </nav>';


   // Add a New Link to Database
   $result = sql_query("SELECT cid, title FROM ".$links_DB."links_categories");
   $numrows = sql_num_rows($result);
   if ($numrows>0) {
      echo '
   <div class="card card-body mb-3">
      <h3 class="mb-3">'.translate("Ajouter un nouveau lien").'</h3>';
      if ($adminform=='')
         echo '
      <form method="post" action="modules.php" name="adminForm">';
       else
         echo '
      <form method="post" action="modules.php">';
      echo '
         <input type="hidden" name="ModPath" value="'.$ModPath.'" />
         <input type="hidden" name="ModStart" value="'.$ModStart.'" />
         <div class="mb-3 row">
            <label class="col-form-label col-sm-3" for="titlelinkadd">'.translate("Titre").'</label>
            <div class="col-sm-9">
               <input class="form-control" type="text" id="titlelinkadd" name="title" maxlength="100" required="required"/>
            </div>
         </div>';
       global $links_url;
       if ($links_url)
          echo ' 
         <div class="mb-3 row">
            <label class="col-form-label col-sm-3" for="urllinkadd">URL</label>
            <div class="col-sm-9">
               <input class="form-control" type="url" id="urllinkadd" name="url" maxlength="255" value="http://" required="required" />
            </div>
         </div>';
       $result=sql_query("SELECT cid, title FROM ".$links_DB."links_categories ORDER BY title");
       echo '
         <div class="mb-3 row">
            <label class="col-form-label col-sm-3" for="catlinkadd">'.translate("Catégorie").'</label>
            <div class="col-sm-9">
               <select class="form-select" id="catlinkadd" name="cat">';
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
         <div class="mb-3 row">
            <label class="col-form-label col-sm-3" for="topiclinkadd">'.translate("Sujets").'</label>
            <div class="col-sm-9">
               <select class="form-select" id="topiclinkadd" name="topicL">';
          $toplist = sql_query("SELECT topicid, topictext FROM ".$NPDS_Prefix."topics ORDER BY topictext");
          echo '
                  <option value="">'.translate("Tous les sujets").'</option>';
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
      <div class="mb-3 row">
         <label class="col-form-label col-12" for="xtextlinkadd">'.translate("Description : (255 caractères max)").'</label>
         <div class="col-12">
            <textarea class="tin form-control" id="xtextlinkadd" name="xtext" rows="10"></textarea>
          </div>
      </div>';
       if ($adminform=='')
          echo aff_editeur("xtext","false");
       echo '
      <div class="mb-3 row">
         <label class="col-form-label col-sm-3" for="namelinkadd">'.translate("Nom").'</label>
         <div class="col-sm-9">
            <input class="form-control" type="text" id="namelinkadd" name="name" maxlength="60" />
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-3" for="emaillinkadd">'.translate("Email").'</label>
            <div class="col-sm-9">
               <input class="form-control" type="email" id="emaillinkadd" name="email" maxlength="60" />
         </div>
      </div>
      <div class="mb-3 row">
         <div class="col-sm-9 ms-sm-auto">
            <input type="hidden" name="op" value="LinksAddLink" />
            <input type="hidden" name="new" value="0" />
            <input type="hidden" name="lid" value="0" />
            <input class="btn btn-primary" type="submit" value="'.translate("Ajouter une url").'" />
         </div>
      </div>
   </form>
   </div>';
   }
   // Add a New Main Category
   echo '
   <div class="card card-body mb-3">
   <h3 class="mb-3">'.translate("Ajouter une catégorie principale").'</h3>
   <form method="post" action="modules.php">
      <div class="mb-3 row">
         <label class="col-form-label col-sm-3" for="titlecatadd">'.translate("Nom").'</label>
         <div class="col-sm-9">
            <input class="form-control" type="text" id="titlecatadd" name="title" size="30" maxlength="100" />
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-12" for="descricatadd">'.translate("Description").'</label>
         <div class="col-sm-12">
            <textarea class="form-control" id="descricatadd" name="cdescription" rows="10" ></textarea>
         </div>
      </div>
      <div class="mb-3 row">
         <div class="col-sm-12">
            <input type="hidden" name="ModPath" value="'.$ModPath.'" />
            <input type="hidden" name="ModStart" value="'.$ModStart.'" />
            <input type="hidden" name="op" value="LinksAddCat" />
            <input class="btn btn-primary" type="submit" value="'.translate("Ajouter").'" />
         </div>
      </div>
   </form>
   </div>';

   // Modify Category
   $result = sql_query("SELECT * FROM ".$links_DB."links_categories");
   $numrows = sql_num_rows($result);
   if ($numrows>0) {
      echo '
   <div class="card card-body mb-3">
   <h3 class="mb-3">'.translate("Modifier la catégorie").'</h3>
   <form method="post" action="modules.php">
      <input type="hidden" name="ModPath" value="'.$ModPath.'" />
      <input type="hidden" name="ModStart" value="'.$ModStart.'" />';
      $result=sql_query("SELECT cid, title FROM ".$links_DB."links_categories ORDER BY title");
      echo '
      <div class="mb-3 row">
         <label class="col-form-label col-sm-3" for="modcat">'.translate("Catégorie").'</label>
         <div class="col-sm-9">
            <select class="form-select" id="modcat" name="cat">';
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
      <div class="mb-3 row">
         <div class="col-sm-9 ms-sm-auto">
            <input type="hidden" name="op" value="LinksModCat" />
            <input class="btn btn-primary" type="submit" value="'.translate("Modifier").'" />
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
   <div class="card card-body mb-3">
   <h3 class="mb-3">'.translate("Ajouter une sous-catégorie").'</h3>
   <form method="post" action="modules.php">
      <input type="hidden" name="ModPath" value="'.$ModPath.'" />
      <input type="hidden" name="ModStart" value="'.$ModStart.'" />
      <div class="mb-3 row">
         <label class="col-form-label col-sm-3" for="titlesubcatadd">'.translate("Nom").'</label>
         <div class="col-sm-9">
            <input class="form-control" type="text" id="titlesubcatadd" name="title" maxlength="100" />
         </div>
      </div>';
       $result=sql_query("SELECT cid, title FROM ".$links_DB."links_categories ORDER BY title");
       echo '
      <div class="mb-3 row">
         <label class="col-form-label col-sm-3" for="cidsubcatadd">'.translate("dans").'</label>
         <div class="col-sm-9">
            <select class="form-select" id="cidsubcatadd" name="cid">';
       while (list($ccid, $ctitle) = sql_fetch_row($result)) {
          echo '
               <option value="'.$ccid.'">'.aff_langue($ctitle).'</option>';
       }
       echo '
            </select>
         </div>
      </div>
      <div class="mb-3 row">
         <div class="col-sm-9 ms-sm-auto">
            <input type="hidden" name="op" value="LinksAddSubCat" />
            <input class="btn btn-primary " type="submit" value="'.translate("Ajouter").'" />
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
      echo '
      <div class="alert alert-danger">'.translate("Erreur : vous devez saisir un titre pour votre lien").'</div>
      <a href="modules.php?ModStart='.$ModStart.'&amp;ModPath='.$ModPath.'" class="btn btn-secondary">'.translate("Retour en arrière").'</a>';
      include("footer.php");
      exit();
   }
   // Check if URL exist
   global $links_url;
   if (($url=='') and ($links_url==1)) {
      include("header.php");
      echo "<br /><span class=\"rouge\">".translate("Erreur : vous devez saisir une url pour votre lien")."</span><br /><br />";
      echo "[ <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModPath\" class=\"noir\">".translate("Retour en arrière")."</a> ]<br />";
      include("footer.php");
      exit();
   }
   // Check if Description exist
   if ($description=='') {
      include("header.php");
      echo '
      <div class="alert alert-danger">'.translate("Erreur : vous devez saisir une description pour votre lien").'</div>
      <a href="modules.php?ModStart='.$ModStart.'&amp;ModPath='.$ModPath.'" class="btn btn-secondary">'.translate("Retour en arrière").'</a>';
      include("footer.php");
      exit();
   }
   $cat = explode('-', $cat);
   if (!array_key_exists(1,$cat))
      $cat[1] = 0;
   $title = stripslashes(FixQuotes($title));
   $url = stripslashes(FixQuotes($url));
   $description = stripslashes(FixQuotes($description));
   $name = stripslashes(FixQuotes($name));
   $email = stripslashes(FixQuotes($email));
   sql_query("INSERT INTO ".$links_DB."links_links VALUES (NULL, '$cat[0]', '$cat[1]', '$title', '$url', '$description', now(), '$name', '$email', '0','$submitter',0,0,0,'$topicL')");
   include("header.php");
   echo '
    <div class="alert alert-success">'.translate("Nouveau lien ajouté dans la base de données").'</div>
    <a href="modules.php?ModStart='.$ModStart.'&amp;ModPath='.$ModPath.'" class="btn btn-secondary">'.translate("Retour en arrière").'</a>';
   if ($new==1) {
      sql_query("DELETE FROM ".$links_DB."links_newlink WHERE lid='$lid'");
      if ($email!='') {
         global $sitename, $nuke_url;
         $subject = html_entity_decode(translate("Votre lien"),ENT_COMPAT | ENT_HTML401,'UTF-8')." : $sitename";
         $message = translate("Bonjour")." $name :\n\n".translate("Nous avons approuvé votre contribution à notre moteur de recherche.")."\n\n".translate("Titre de la page : ")."$title\n".translate("Url de la page : ")."<a href=\"$url\">$url</a>\n".translate("Description : ")."$description\n".translate("Vous pouvez utiliser notre moteur de recherche sur : ")." <a href=\"$nuke_url/modules.php?ModPath=links&ModStart=links\">$nuke_url/modules.php?ModPath=links&ModStart=links</a>\n\n".translate("Merci pour votre contribution")."\n";
         include("signat.php");
         send_email($email, $subject, $message, '', false, 'html', '');
      }
   }
   include("footer.php");
}

function LinksModLink($lid, $modifylinkrequest_adv_infos) {
   global $ModPath, $ModStart, $links_DB, $hlpfile, $NPDS_Prefix;
   include ("header.php");
   echo helpwindow();

    $result = sql_query("SELECT cid, sid, title, url, description, name, email, hits, topicid_card FROM ".$links_DB."links_links WHERE lid='$lid'");

    echo '
    <h2>'.translate("Modifier les liens").'</h2>
    <h3>'.translate("Lien web").'&nbsp;<span class="text-body-secondary">#'.$lid.'</span></h3>
    ';
    echo "[ <a href=\"modules.php?ModStart=$ModStart&amp;ModPath=$ModStart\" class=\"box\">".translate("Index")."</a> ][ <a href=\"javascript:openwindow();\" class=\"box\">".translate("Manuel en ligne")."</a> ]";

    while (list($cid, $sid, $title, $url, $description, $name, $email, $hits, $topicid_card) = sql_fetch_row($result)) {
       $title = stripslashes($title); $description = stripslashes($description);

       echo '
      <form action="modules.php" method="post" name="adminForm">
         <input type="hidden" name="ModPath" value="'.$ModPath.'" />
         <input type="hidden" name="ModStart" value="'.$ModStart.'" />';

//       echo translate("Link ID: ")."<b>$lid</b><br />";
       echo '
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="title">'.translate("Titre").'</label>
            <div class="col-sm-8">
               <input class="form-control" type="text" name="title" value="'.$title.'" maxlength="100" />
            </div>
         </div>';
       global $links_url;
       if (($links_url) or ($links_url==-1))
          echo '
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="url">URL</label>
            <div class="col-sm-8">
               <input class="form-control" type="text" name="url" value="'.$url.'" maxlength="255" /><a href="'.$url.'" target="_blank" >'.translate("Visite").'</a>
            </div>
         </div>';
       $result2=sql_query("SELECT cid, title FROM ".$links_DB."links_categories ORDER BY title");
       echo '
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="cat">'.translate("Catégorie").'</label>
            <div class="col-sm-8">
               <select class="form-select" name="cat">';
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
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="topicL">'.translate("Sujets").'</label>
            <div class="col-sm-8">
               <select class="form-select" name="topicL">';
          $toplist = sql_query("SELECT topicid, topictext FROM ".$NPDS_Prefix."topics ORDER BY topictext");
          echo '
                  <option value="0">'.translate("Tous les sujets").'</option>';
          while(list($topicid, $topics) = sql_fetch_row($toplist)) {
              if ($topicid==$topicid_card) $sel = 'selected="selected"';
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
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="hits">'.translate("Hits").'</label>
            <div class="col-sm-8">
               <input class="form-control" type="number" name="hits" value="'.$hits.'" />
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-12" for="xtext">'.translate("Description").'</label>
            <div class="col-sm-12">
               <textarea class="tin form-control" name="xtext" rows="10">'.$description.'</textarea>
            </div>
         </div>';
       echo aff_editeur('xtext','');
       echo '
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="name">'.translate("Nom").'</label>
            <div class="col-sm-8">
            <input class="form-control" type="text" name="name" maxlength="100" value="'.$name.'" />
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="email">'.translate("Email").'</label>
            <div class="col-sm-8">
               <input class="form-control" type="text" name="email" maxlength="100" value="'.$email.'" />
            </div>
         </div>
         <div class="mb-3 row">
            <div class="col-sm-8 ms-sm-auto">
               <input type="hidden" name="lid" value="'.$lid.'" />
               <input type="hidden" name="op" value="LinksModLinkS" />
               <input class="btn btn-primary" type="submit" value="'.translate("Modifier").'" />&nbsp;
               <a class="btn btn-danger" href="modules.php?ModStart='.$ModStart.'&amp;ModPath='.$ModPath.'&op=LinksDelLink&lid='.$lid.'" >'.translate("Effacer").'</a>
            </div>
         </div>
       </form>';
       $resulted2 = sql_query("SELECT adminid, editorialtimestamp, editorialtext, editorialtitle FROM ".$links_DB."links_editorials WHERE linkid='$lid'");
       $recordexist = sql_num_rows($resulted2);
       if ($recordexist == 0) {
          echo '
      <hr />
      <h4>'.translate("Ajouter un édito").'</h4>
      <form action="modules.php" method="post">
         <input type="hidden" name="ModPath" value="'.$ModPath.'" />
         <input type="hidden" name="ModStart" value="'.$ModStart.'" />
         <input type="hidden" name="linkid" value="'.$lid.'" />
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="editorialtitle">'.translate("Titre").'</label>
            <div class="col-sm-8">
               <input class="form-control" type="text" name="editorialtitle" value="" maxlength="100" />
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-12" for="editorialtext">'.translate("Texte complet").'</label>
            <div class="col-sm-12">
               <textarea class="form-control" name="editorialtext" rows="10"></textarea>
            </div>
         </div>
          <input type="hidden" name="op" value="LinksAddEditorial" />
          <input class="btn btn-primary" type="submit" value="'.translate("Ajouter").'" />
      </form>';
       } else {
          list($adminid, $editorialtimestamp, $editorialtext, $editorialtitle) = sql_fetch_row($resulted2);
          $formatted_date=formatTimes($editorialtimestamp, IntlDateFormatter::MEDIUM, IntlDateFormatter::SHORT);
          echo translate("Modifier l'édito")." : ".translate("Auteur")." : $adminid / $formatted_date<br /><br />";
          echo '
      <form action="modules.php" method="post">
         <input type="hidden" name="ModPath" value="'.$ModPath.'" />
         <input type="hidden" name="ModStart" value="'.$ModStart.'" />
         <input type="hidden" name="linkid" value="'.$lid.'" />
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="editorialtitle">'.translate("Titre").'</label>
            <div class="col-sm-8">
               <input class="form-control" type="text" name="editorialtitle" value="'.$editorialtitle.'" maxlength="100" />
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-12" for="editorialtext">'.translate("Texte complet").'</label>
            <div class="col-sm-12">
               <textarea class="form-control" name="editorialtext" rows="10">'.$editorialtext.'</textarea>
            </div>
         </div>
         <input type="hidden" name="op" value="LinksModEditorial" />
         <input class="btn btn-primary" type="submit" value="'.translate("Modifier").'" />
         <a href="modules.php?ModStart='.$ModStart.'&amp;ModPath='.$ModPath.'&op=LinksDelEditorial&linkid='.$lid.'" >'.translate("Effacer").'</a>
         </form>';
       }
       echo '<hr />';
       $pos = strpos($ModPath, '/admin');
       $browse_key=$lid;
       include ("modules/sform/".substr($ModPath,0,$pos)."/link_maj.php");
   }
   include ("footer.php");
}
function LinksModLinkS($lid, $title, $url, $description, $name, $email, $hits, $cat, $topicL) {
   global $ModPath, $ModStart, $links_DB;
   $cat = explode('-', $cat);
   if (!array_key_exists(1,$cat))
      $cat[1] = 0;
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
   sql_query("UPDATE ".$links_DB."links_editorials SET editorialtext='$editorialtext', editorialtitle='$editorialtitle' WHERE linkid='$linkid'");
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
      echo '
      <h2>'.translate("Liens").'</h2>
      <hr />
      <div class="alert alert-danger">'.translate("Erreur : la sous-catégorie").' <span class="lead">'.$title.'</span> '.translate("existe déjà").'</div>
      <a href="modules.php?ModStart='.$ModStart.'&amp;ModPath='.$ModPath.'" class="btn btn-secondary">'.translate("Retour en arrière").'</a>';
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
   if (!array_key_exists(1,$cat))
      $cat[1] = 0;
   echo '
   <h2>'.translate("Liens").'</h2>
   <hr class="mb-0" />
   <div class="text-end"><a href="modules.php?ModStart='.$ModStart.'&amp;ModPath='.$ModPath.'"><i class="fa fa-cogs fa-lg me-2"></i></a></div>
   <h3 class="my-3">'.translate("Modifier la catégorie").'</h3>';
   if ($cat[1]==0) {
      $result=sql_query("SELECT title, cdescription FROM ".$links_DB."links_categories WHERE cid='$cat[0]'");
      list($title,$cdescription) = sql_fetch_row($result);
      $cdescription = stripslashes($cdescription);
      echo '
      <form method="post" action="modules.php">
         <input type="hidden" name="ModPath" value="'.$ModPath.'" />
         <input type="hidden" name="ModStart" value="'.$ModStart.'" />
         <div class="mb-3 row">
            <label class="col-form-label col-sm-3" for="title">'.translate("Nom").'</label>
            <div class="col-sm-9">
               <input class="form-control" type="text" name="title" value="'.$title.'" maxlength="50" />
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-12" for="cdescription">'.translate("Description").'</label>
            <div class="col-sm-12">
               <textarea class="form-control" id="cdescription" name="cdescription" rows="10">'.$cdescription.'</textarea>
            </div>
         </div>
         <input type="hidden" name="sub" value="0" />
         <input type="hidden" name="cid" value="'.$cat[0].'" />
         <input type="hidden" name="op" value="LinksModCatS" />
         <input class="btn btn-primary" type="submit" value="'.translate("Sauver les modifications").'" />
      </form>
      <form method="post" action="modules.php">
         <input type="hidden" name="ModPath" value="'.$ModPath.'" />
         <input type="hidden" name="ModStart" value="'.$ModStart.'" />
         <input type="hidden" name="sub" value="0" />
         <input type="hidden" name="cid" value="'.$cat[0].'" />
         <input type="hidden" name="op" value="LinksDelCat" />
         <input type="submit" class="btn btn-danger" value="'.translate("Effacer").'" />
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

      '.translate("Nom de la catégorie : ").aff_langue($ctitle).'<br /><br />
      '.translate("Nom de la sous-catégorie : ").'
      <input class="form-control" type="text" name="title" value="'.$stitle.'" maxlength="250" /></span>
      <input type="hidden" name="sub" value="1" />
      <input type="hidden" name="cid" value="'.$cat[0].'" />
      <input type="hidden" name="sid" value="'.$cat[1].'" />
      <input type="hidden" name="op" value="LinksModCatS" />
      <input type="submit" class="btn btn-primary" value="'.translate("Sauver les modifications").'">
      </form>
      <form method="post" action="modules.php">
      <input type="hidden" name="ModPath" value="'.$ModPath.'" />
      <input type="hidden" name="ModStart" value="'.$ModStart.'" />
      <input type="hidden" name="sub" value="1" />
      <input type="hidden" name="cid" value="'.$cat[0].'" />
      <input type="hidden" name="sid" value="'.$cat[1].'" />
      <input type="hidden" name="op" value="LinksDelCat" />
      <input type="submit" class="btn btn-danger my-4" value="'.translate("Effacer").'" />
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
      echo '
      <h3>'.translate("Liens").'</h3>
      <hr />
      <div class="alert alert-danger">'.translate("Erreur : la catégorie").' '.$title.' '.translate("existe déjà").'</div>
      <a href="modules.php?ModStart='.$ModStart.'&amp;ModPath='.$ModPath.'" class="btn btn-secondary">'.translate("Retour en arrière").'</a>';
      include("footer.php");
   } else {
      sql_query("INSERT INTO ".$links_DB."links_categories VALUES (NULL, '$title', '$cdescription')");
      Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath");
   }
}

function LinksModCatS($cid, $sid, $sub, $title, $cdescription) {
   global $ModPath, $ModStart, $links_DB;
   if ($sub==0)
      sql_query("UPDATE ".$links_DB."links_categories SET title='$title', cdescription='$cdescription' WHERE cid='$cid'");
   else
      sql_query("UPDATE ".$links_DB."links_subcategories SET title='$title' WHERE sid='$sid'");
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
      echo '
      <h3>'.translate("Liens").'</h3>
      <hr />
      <div class="alert alert-danger">'.translate("ATTENTION : Etes-vous certain de vouloir effacer cette catégorie et tous ses Liens ?").'</div>
      <a href="modules.php?ModStart='.$ModStart.'&amp;ModPath='.$ModPath.'&amp;op=LinksDelCat&amp;cid='.$cid.'&amp;sid='.$sid.'&amp;sub='.$sub.'&amp;ok=1" class="btn btn-danger me-2">'.translate("Oui").'</a><a href="modules.php?ModStart='.$ModStart.'&amp;ModPath='.$ModPath.'" class="btn btn-secondary">'.translate("Non").'</a>';
      include("footer.php");
   }
}

// ----- Broken and Changes
function LinksListModRequests() {
   global $ModPath, $ModStart, $links_DB, $NPDS_Prefix;

   $resultX = sql_query("SELECT requestid, lid, cid, sid, title, url, description, modifysubmitter, topicid_card FROM ".$links_DB."links_modrequest WHERE brokenlink=0 ORDER BY requestid");
   $totalmodrequests = sql_num_rows($resultX);
   if ($totalmodrequests==0)
      Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath");
   include ("header.php");
   $x_mod='';$x_ori='';
   function clformodif($x_ori,$x_mod){
      if ($x_ori != $x_mod) return ' class="text-danger" ';
   }

   echo '
   <h3 class="mb-3">'.translate("Requête de modification d'un lien utilisateur").' <span class="badge bg-danger float-end">'.$totalmodrequests.'</span></h3>
   <hr class="mb-0" />
   <div class="text-end"><a href="modules.php?ModStart='.$ModStart.'&amp;ModPath='.$ModPath.'"><i class="fa fa-cogs me-2 fa-lg"></i></a></div>';
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
      if ($owner=='') $owner="administration";
      if ($origsidtitle=='') $origsidtitle= "-----";
      if ($sidtitle=='') $sidtitle= "-----";
      
      echo '
   <div class="card-deck-wrapper mt-3">
     <div class="card-deck">
       <div class="card card-body">
         <h4 class="card-title">'.translate("Original").'</h4>
         <div class="card-text">
            <strong>'.translate("Description:").'</strong> <div>'.$origdescription.'</div>
            <strong>'.translate("Titre:").'</strong> '.$origtitle.'<br />
            <strong>'.translate("Url : ").'</strong> <a href="'.$origurl.'" target="_blank" >'.$origurl.'</a><br />';
      global $links_topic;
      if ($links_topic)
         echo '
            <strong>'.translate("Sujet").' :</strong> '.$oritopic.'<br />';
      echo '
            <strong>'.translate("Catégorie :").'</strong> '.$origcidtitle.'<br />
            <strong>'.translate("Sous-catégorie :").'</strong> '.$origsidtitle.'<br />';
      if ($owneremail=='')
         echo '
            <strong>'.translate("Propriétaire").':</strong> <span'.clformodif($origsidtitle,$sidtitle).'>'.$owner.'</span><br/>';
            else
         echo '
            <strong>'.translate("Propriétaire").':</strong> <a href="mailto:'.$owneremail.'">'.$owner.'</a></span><br/>';
      echo '
         </div>
      </div>
      <div class="card card-body border-danger">
         <h4 class="card-title">'.translate("Proposé").'</h4>
         <div class="card-text">
            <strong>'.translate("Description:").'</strong><div'.clformodif($origdescription,$description).'>'.$description.'</div>
            <strong>'.translate("Titre:").'</strong> <span'.clformodif($origtitle,$title).'>'.$title.'</span><br />
            <strong>'.translate("Url : ").'</strong> <span'.clformodif($origurl,$url).'><a href="'.$url.'" target="_blank" >'.$url.'</a></span><br />';
      global $links_topic;
      if ($links_topic)
         echo '
            <strong>'.translate("Sujet").' :</strong> <span'.clformodif($oritopic,$topic).'>'.$topic.'</span><br />';
      echo '
            <strong>'.translate("Catégorie :").'</strong> <span'.clformodif($origcidtitle,$cidtitle).'>'.$cidtitle.'</span><br />
            <strong>'.translate("Sous-catégorie :").'</strong> <span'.clformodif($origsidtitle,$sidtitle).'>'.$sidtitle.'</span><br />';
         
      if ($owneremail=='')
         echo '
            <strong>'.translate("Propriétaire").': </strong> <span>'.$owner.'</span><br />';
      else
         echo '<strong>'.translate("Propriétaire").' : </strong><a href="mailto:'.$owneremail.'" >'.$owner.'</span><br />';

      echo '
         </div>
      </div>
   </div>';
    if ($modifysubmitteremail=='')
       echo translate("Auteur").' : '.$modifysubmitter;
    else
       echo translate("Auteur").' :  <a href="mailto:'.$modifysubmitteremail.'">'.$modifysubmitter.'</a>';

    echo '
       <div class="mb-3">
          <a href="modules.php?ModStart='.$ModStart.'&amp;ModPath='.$ModPath.'&amp;op=LinksChangeModRequests&amp;requestid='.$requestid.'" class="btn btn-primary btn-sm">'.translate("Accepter").'</a>
          <a href="modules.php?ModStart='.$ModStart.'&amp;ModPath='.$ModPath.'&amp;op=LinksChangeIgnoreRequests&amp;requestid='.$requestid.'" class="btn btn-secondary btn-sm">'.translate("Ignorer").'</a>
       </div>
   </div>';
   }
   sql_free_result($resultX);
   include ("footer.php");
}

// ----- Broken
function LinksListBrokenLinks() {
   global $ModPath, $ModStart, $links_DB, $NPDS_Prefix;

   $resultBrok = sql_query("SELECT requestid, lid, modifysubmitter FROM ".$links_DB."links_modrequest WHERE brokenlink=1 ORDER BY requestid");
   $totalbrokenlinks = sql_num_rows($resultBrok);
   if ($totalbrokenlinks==0)
      Header("Location: modules.php?ModStart=$ModStart&ModPath=$ModPath");
   else {
      include ("header.php");
      echo '
   <h3 class="mb-3">'.translate("Liens cassés rapportés par un ou plusieurs utilisateurs").' <span class="badge bg-danger float-end"> '.$totalbrokenlinks.'</span></h3>
   <hr class="mb-0"/>
   <div class="text-end"><a href="modules.php?ModStart='.$ModStart.'&amp;ModPath='.$ModPath.'"><i class="fa fa-cogs fa-lg"></i></a></div>
   <div class="blockquote">
      <i class="fas fa-trash fa-lg text-primary me-2"></i>'.translate("Ignorer (Efface toutes les demandes pour un lien donné)").'<br />
      <i class="fas fa-trash fa-lg text-danger me-2"></i>'.translate("Effacer (Efface les liens cassés et les avis pour un lien donné)").'
   </div>
   <table id="tad_linkbrok" data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa">
      <thead>
         <tr>
            <th class="n-t-col-xs-4" data-sortable="true" data-halign="center">'.translate("Liens").'</th>
            <th data-sortable="true" data-halign="center">'.translate("Auteur").'</th>
            <th data-sortable="true" data-halign="center">'.translate("Propriétaire").'</th>
            <th class="n-t-col-xs-1" data-halign="center" data-align="center">'.translate("Ignorer").'</th>
            <th class="n-t-col-xs-1" data-halign="center" data-align="center">'.translate("Effacer").'</th>
         </tr>
      </thead>
      <tbody>';
       while (list($requestid, $lid, $modifysubmitter)=sql_fetch_row($resultBrok)) {
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
            <td><div>'.$title.'&nbsp;<span class="float-end"><a href="'.$url.'"  target="_blank"><i class="fas fa-external-link-alt fa-lg"></i></a></span></div></td>';
          if ($email=='') 
            echo '
            <td>'.$modifysubmitter;
          else 
            echo '
            <td><div>'.$modifysubmitter.'&nbsp;<span class="float-end"><a href="mailto:'.$email.'" ><i class="fa fa-at fa-lg"></i></a></span></div>';
          echo '</td>';
          if ($owneremail=='')
             echo '
            <td>'.$owner;
          else 
            echo '
            <td><a href="mailto:'.$owneremail.'" >'.$owner.'</a>';
          echo '</td>
            <td><a href="modules.php?ModStart='.$ModStart.'&amp;ModPath='.$ModPath.'&amp;op=LinksIgnoreBrokenLinks&amp;lid='.$lid.'" ><i class="fas fa-trash fa-lg" title="'.translate("Ignorer").'" data-bs-toggle="tooltip"></i></a></td>
            <td><a href="modules.php?ModStart='.$ModStart.'&amp;ModPath='.$ModPath.'&amp;op=LinksDelBrokenLinks&amp;lid='.$lid.'" ><i class="fas fa-trash text-danger fa-lg" title="'.translate("Effacer").'" data-bs-toggle="tooltip"></i></a></td>
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
settype($sid,'integer');
settype($ok,'integer');

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
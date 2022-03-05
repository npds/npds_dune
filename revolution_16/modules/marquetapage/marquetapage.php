<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* This module : MarqueTaPage  Copyright (c) 2012 by Philippe Brunier   */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2022 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

function marquetapage_add($uri, $topic, $action) {
   global $cookie, $NPDS_Prefix, $nuke_url;
   if (($action=='ad_tapage') and ($cookie[0])) {
      $drname=dirname($uri);
      if ($drname=='.') {
         $uri=$nuke_url.'/'.$uri;
      } elseif($drname=='/') {
         $uri=$nuke_url.$uri;
      } else {
         if ($_SERVER['SERVER_PORT']=="80")
            $uri="http://".$_SERVER['SERVER_NAME'].$uri;
         else
            $uri="http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'].$uri;
      }

      sql_query("INSERT INTO ".$NPDS_Prefix."marquetapage (uid, uri, topic) VALUES ('$cookie[0]', '$uri', '$topic')");
      redirect_url($uri);
   }
   if (($action=="sp_tapage") and ($cookie[0])) {
      $result = sql_query("SELECT uri FROM ".$NPDS_Prefix."marquetapage WHERE uid='$cookie[0]' AND uri='$uri'");
      if (sql_num_rows($result) > 0) {
         sql_query("DELETE FROM ".$NPDS_Prefix."marquetapage WHERE uid='$cookie[0]' AND uri='$uri'");
         redirect_url($uri);
      }
   }
   if (($action=='sp_tespages') and ($cookie[0])) {
      $result = sql_query("SELECT uri FROM ".$NPDS_Prefix."marquetapage WHERE uid='$cookie[0]'");
      if (sql_num_rows($result) > 0) {
         sql_query("DELETE FROM ".$NPDS_Prefix."marquetapage WHERE uid='$cookie[0]'");
         redirect_url($uri);
      }
   }
}

function marquetapage() {
   global $cookie;
   if ($cookie[0]!='') {
      global $REQUEST_URI, $title, $post, $NPDS_Prefix;
      if ($ibid=theme_image("modules/add.gif")) {$add=$ibid;} else {$add="modules/marquetapage/add.gif";}
      if ($ibid=theme_image("modules/addj.gif")) {$addj=$ibid;} else {$addj="modules/marquetapage/addj.gif";}
      $result=sql_query("SELECT uri, topic FROM ".$NPDS_Prefix."marquetapage WHERE uid='$cookie[0]' ORDER BY topic ASC");
      $content='';
      if (sql_num_rows($result)) {
         $content.='
   <h6>
   <a class="mb-2" data-bs-toggle="collapse" data-bs-target="#lst_fav" id="show_fav" title="'.translate("Déplier la liste").'"><i id="i_lst_fav" class="fa fa-caret-down fa-lg toggle-icon text-primary me-2" ></i></a><span class="align-top">Bookmarks</span><span class="badge bg-secondary float-end">'.sql_num_rows($result).'</span>
   </h6>
   <div id="lst_fav" class="collapse" >
      <a class="float-end" href="modules.php?ModPath=marquetapage&amp;ModStart=marquetapage&amp;op=supp_all&amp;uri='.$_SERVER['PHP_SELF'].'"><i class="fas fa-trash text-danger fa-lg" title="'.translate("Effacer").'" data-bs-toggle="tooltip" data-bs-placement="left"></i></a><br /><hr />';
         while(list($uri, $topic)=sql_fetch_row($result)) {
            $content.='
      <div class="row g-0 my-2">
         <div class="col-11 n-ellipses"><a href="'.$uri.'" class="small ">'.$topic.'</a></div>
         <div class="col-1"><a class="float-end" href="modules.php?ModPath=marquetapage&amp;ModStart=marquetapage&amp;op=supp&amp;uri='.urlencode($uri).'"><i class="fas fa-trash text-danger" title="'.translate("Effacer").'" data-bs-toggle="tooltip"></i></a></div>
      </div>';
         }
         $content.='
   </div>';
      }
      global $block_title;
      $uri=urlencode($REQUEST_URI);
      if ($post) {$title.='/'.$post;}
      $title_MTP= $title=='' ? basename(urldecode($uri)) : $title;
      $boxTitle='<span class="me-2 fs-4"><a href="modules.php?ModPath=marquetapage&amp;ModStart=marquetapage&amp;op=add&amp;uri='.$uri.'&amp;topic='.urlencode($title_MTP).'"><i class="fas fa-bookmark align-middle" title="'.translate("Ajouter").' '.translate("favori").'" data-bs-toggle="tooltip"></i></a></span>';
      $boxTitle.= $block_title=='' ? 'MarqueTaPage' : $block_title;
      themesidebox($boxTitle, $content);
   }
}
settype($op,'string');
if ($op=='add')
   marquetapage_add(removeHack($uri),removeHack($topic),'ad_tapage');
if ($op=='supp')
   marquetapage_add(removeHack($uri),'','sp_tapage');
if ($op=='supp_all')
   marquetapage_add(removeHack($uri),'','sp_tespages');
?>
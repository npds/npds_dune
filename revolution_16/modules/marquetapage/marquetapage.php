<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* This module : MarqueTaPage  Copyright (c) 2012 by Philippe Brunier   */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2020 by Philippe Brunier   */
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
   <a class="mb-2" data-toggle="collapse" data-target="#lst_fav" id="show_fav" title="'.translate("Déplier la liste").'"><i id="i_lst_fav" class="fa fa-caret-down fa-lg toggle-icon text-primary mr-2" ></i>&nbsp;</a><span class="align-top">Bookmarks</span><span class="badge badge-secondary float-right">'.sql_num_rows($result).'</span>
   </h6>
   <div id="lst_fav" class="collapse" >
   <a href="modules.php?ModPath=marquetapage&amp;ModStart=marquetapage&amp;op=supp_all&amp;uri='.$_SERVER['PHP_SELF'].'"><i class="far fa-trash-alt text-danger fa-lg" title="'.translate("Effacer").'" data-toggle="tooltip"></i></a>';
         while(list($uri, $topic)=sql_fetch_row($result)) {
            $content.='
            <div class="row">
               <div class="col-10 n-ellipses"><a href="'.$uri.'" class="small ">'.$topic.'</a></div>
               <div class="col-2 text-right"><a href="modules.php?ModPath=marquetapage&amp;ModStart=marquetapage&amp;op=supp&amp;uri='.urlencode($uri).'"><i class="far fa-trash-alt text-danger" title="'.translate("Effacer").'" data-toggle="tooltip"></i></a></div>
            </div>';
         }
         $content.='
   </div>';
      }
      global $block_title;
      $uri=urlencode($REQUEST_URI);
      if ($post) {$title.='/'.$post;}
      if ($title=='') {$title_MTP=basename(urldecode($uri));} else {$title_MTP=$title;}
      $boxTitle='<span><a href="modules.php?ModPath=marquetapage&amp;ModStart=marquetapage&amp;op=add&amp;uri='.$uri.'&amp;topic='.urlencode($title_MTP).'"><i class="far fa-bookmark " title="'.translate("Ajouter").' '.translate("favori").'" data-toggle="tooltip"></i></a></span>';
            if ($block_title=='')
         $boxTitle.='&nbsp;MarqueTaPage';
      else
         $boxTitle.='&nbsp;'.$block_title;
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
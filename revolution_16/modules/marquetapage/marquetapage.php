<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* This module : MarqueTaPage  Copyright (c) 2012 by Philippe Brunier   */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2017 by Philippe Brunier   */
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
      if (sql_num_rows($result)) {
         $tmp_toggle='<a class="tog" id="show_fav" title="'.translate("Show list").'"><i id="i_lst_fav" class="fa fa-caret-right fa-2x" ></i></a>';
         $content="
   <script type=\"text/javascript\">
   //<![CDATA[
   tog = function(lst,sho,hid){
      $(document).on('click', 'a.tog', function() {
         var buttonID = $(this).attr('id');
         lst_id = $('#'+lst);
         i_id=$('#i_'+lst);
         btn_show=$('#'+sho);
         btn_hide=$('#'+hid);
         if (buttonID == sho) {
            lst_id.fadeIn(1000);//show();
            btn_show.attr('id',hid)
            btn_show.attr('title','".translate("Hide list")."');
            i_id.attr('class','fa fa-caret-up fa-2x');
         } else if (buttonID == hid) {
            lst_id.fadeOut(1000);//hide();
            btn_hide=$('#'+hid);
            btn_hide.attr('id',sho);
            btn_hide.attr('title','".translate("Show list")."');
            i_id.attr('class','fa fa-caret-down fa-2x');
        }
       });
   };
   //]]>
   </script>";

         $content.='
   <h6>
   <a class="tog" id="show_fav" title="'.translate("Show list").'"><i id="i_lst_fav" class="fa fa-caret-right fa-2x" ></i>&nbsp;Bookmarks </a><span class="tag tag-pill tag-default pull-right">'.sql_num_rows($result).'</span>
   </h6>
   <ul id="lst_fav" style="display:none;" >
   
   <a href="modules.php?ModPath=marquetapage&amp;ModStart=marquetapage&amp;op=supp_all&amp;uri='.$_SERVER['PHP_SELF'].'"><i class="fa fa-trash-o text-danger" title="'.translate("Delete").'" data-toggle="tooltip"></i></a>';
         while(list($uri, $topic)=sql_fetch_row($result)) {
            $content.='
      <li><a href="'.$uri.'" style="font-size:.7rem;">'.$topic.'</a>
            <span class="float-xs-right"><a href="modules.php?ModPath=marquetapage&amp;ModStart=marquetapage&amp;op=supp&amp;uri='.urlencode($uri).'"><i class="fa fa-trash-o text-danger" title="'.translate("Delete").'" data-toggle="tooltip"></i></a></span></li>';
         }
         $content.='
   </ul>
   <script type="text/javascript">
   //<![CDATA[
      tog("lst_fav","show_fav","hide_fav");
   //]]>
   </script>';
      }
      global $block_title;
      $uri=urlencode($REQUEST_URI);
      if ($post) {$title.="/".$post;}
      if ($title=='') {$title_MTP=basename(urldecode($uri));} else {$title_MTP=$title;}
      $boxTitle='<span><a href="modules.php?ModPath=marquetapage&amp;ModStart=marquetapage&amp;op=add&amp;uri='.$uri.'&amp;topic='.urlencode($title_MTP).'"><i class="fa fa-bookmark-o " title="'.translate("Add").' '.translate("favourite").'" data-toggle="tooltip"></i></a></span>';
            if ($block_title=='')
         $boxTitle.='&nbsp;MarqueTaPage';
      else
         $boxTitle.='&nbsp;'.$block_title;
      themesidebox($boxTitle, $content);
   }
}

if ($op=='add') {
   marquetapage_add(removeHack($uri),removeHack($topic),'ad_tapage');
}
if ($op=='supp') {
   marquetapage_add(removeHack($uri),'','sp_tapage');
}
if ($op=='supp_all') {
   marquetapage_add(removeHack($uri),'','sp_tespages');
}
?>
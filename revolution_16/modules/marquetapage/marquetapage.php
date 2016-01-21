<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* This module : MarqueTaPage  Copyright (c) 2012 by Philippe Brunier   */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2012 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

function marquetapage_add($uri, $topic, $action) {
   global $cookie, $NPDS_Prefix, $nuke_url;
   if (($action=="ad_tapage") and ($cookie[0])) {
      $drname=dirname($uri);
      if ($drname==".") {
         $uri=$nuke_url."/".$uri;
      } elseif($drname=="/") {
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
      $result = sql_query("select uri from ".$NPDS_Prefix."marquetapage where uid='$cookie[0]' and uri='$uri'");
      if (sql_num_rows($result) > 0) {
         sql_query("DELETE FROM ".$NPDS_Prefix."marquetapage WHERE uid='$cookie[0]' and uri='$uri'");
         redirect_url($uri);
      }
   }
   if (($action=="sp_tespages") and ($cookie[0])) {
      $result = sql_query("select uri from ".$NPDS_Prefix."marquetapage where uid='$cookie[0]'");
      if (sql_num_rows($result) > 0) {
         sql_query("DELETE FROM ".$NPDS_Prefix."marquetapage WHERE uid='$cookie[0]'");
         redirect_url($uri);
      }
   }
}

function marquetapage() {
   global $cookie;
   if ($cookie[0]!="") {
      global $REQUEST_URI, $title, $post, $NPDS_Prefix;
      if ($ibid=theme_image("modules/add.gif")) {$add=$ibid;} else {$add="modules/marquetapage/add.gif";}
      if ($ibid=theme_image("modules/addj.gif")) {$addj=$ibid;} else {$addj="modules/marquetapage/addj.gif";}
      $result=sql_query("select uri, topic from ".$NPDS_Prefix."marquetapage where uid='$cookie[0]' order by topic ASC");
      if (sql_num_rows($result)) {
         $tmp_toggle='<span id="show_fav" title="'.translate("Show list").'"><img src="images/admin/ws/toggle_plus.gif" style="vertical-align:middle;" alt="'.translate("Show list").'" /></span>';

         $content="<script type=\"text/javascript\" src=\"lib/yui/build/yui/yui-min.js\"></script>";
         $content.="<script type=\"text/javascript\">
         //<![CDATA[
         tog =function(lst,sho,hid){
           YUI().use('transition', 'node-event-delegate', function (Y) {
             Y.delegate('click', function(e) {
              var buttonID = e.currentTarget.get('id'),
              lst_id = Y.one('#'+lst);
              btn_show=Y.one('#'+sho);
              btn_hide=Y.one('#'+hid);
              if (buttonID === sho) {
                 lst_id.show(true);
                 btn_show.set('id',hid);
                 btn_show.set('title','".translate("Hide list")."');
                 btn_show.setContent('<img src=\"images/admin/ws/toggle_minus.gif\" style=\"vertical-align:middle;\" alt=\"".translate("Hide list")."\" />');
              } else if (buttonID == hid) {
                 lst_id.transition({
                   duration: 0.2,
                   easing: 'ease-out',
                   opacity: 0
                 });
                 btn_hide=Y.one('#'+hid);
                 lst_id.hide(true);
                 btn_hide.set('id',sho);
                 btn_hide.set('title','".translate("Show list")."');
                 btn_hide.setContent('<img src=\"images/admin/ws/toggle_plus.gif\" style=\"vertical-align:middle;\" alt=\"".translate("Show list")."\" />');
              }
             }, document, 'span');
           });
         }
         //]]>
         </script>";

         $content.="\n<table width=\"100%\" id=\"lst_fav\" border=\"0\" cellspacing=\"1\" cellpadding=\"2\" style=\"display:none;\" >\n<thead>\n<tr>\n<th><img src=\"$addj\" border=\"0\" style=\"vertical-align:middle\" alt=\"".translate("Add")." ".translate("favourite")."\" title=\"".translate("Add")." ".translate("favourite")."\" />&nbsp;Bookmarks [".sql_num_rows($result)."]</th><th align=\"right\"><a href=\"modules.php?ModPath=marquetapage&amp;ModStart=marquetapage&amp;op=supp_all&amp;uri=".$_SERVER['PHP_SELF']."\"><img src=\"modules/marquetapage/del.gif\" border=\"0\" alt=\"".translate("Delete")."\" title=\"".translate("Delete")."\" style=\"vertical-align: middle;\" /></a></th>\n</tr>\n</thead>\n<tbody>";
         while(list($uri, $topic)=sql_fetch_row($result)) {
            $content.="\n<tr>\n<td align=\"left\" width=\"95%\"><a href=\"$uri\" style=\"font-size: 10px;\">".$topic."</a></td>\n";
            $content.="<td align=\"right\"><a href=\"modules.php?ModPath=marquetapage&amp;ModStart=marquetapage&amp;op=supp&amp;uri=".urlencode($uri)."\"><img src=\"modules/marquetapage/del.gif\" border=\"0\" alt=\"".translate("Delete")."\" title=\"".translate("Delete")."\" style=\"vertical-align: middle;\" /></a></td>\n</tr>\n";
         }
         $content.="</tbody>\n</table>\n";
         $content.="\n<script type=\"text/javascript\">
         //<![CDATA[
         tog('lst_fav','show_fav','hide_fav');
         //]]>
         </script>\n";
      }
      global $block_title;
      $uri=urlencode($REQUEST_URI);
      if ($post) {$title.="/".$post;}
      if ($title=="") {$title_MTP=basename(urldecode($uri));} else {$title_MTP=$title;}
      $boxTitle="<span><a href=\"modules.php?ModPath=marquetapage&amp;ModStart=marquetapage&amp;op=add&amp;uri=$uri&amp;topic=".urlencode($title_MTP)."\"><img src=\"$add\" name=\"image\" onmouseover=\"image.src='$addj';\" onmouseout=\"image.src='$add';\" border=\"0\" style=\"vertical-align:middle\" alt=\"".translate("Add")." ".translate("favourite")."\" title=\"".translate("Add")." ".translate("favourite")."\" /></a></span>";
            if ($block_title=="")
         $boxTitle.="&nbsp;MarqueTaPage ".$tmp_toggle;
      else
         $boxTitle.="&nbsp;".$block_title." ".$tmp_toggle;

      themesidebox($boxTitle, $content);
   }
}

if ($op=="add") {
   marquetapage_add(removeHack($uri),removeHack($topic),"ad_tapage");
}
if ($op=="supp") {
   marquetapage_add(removeHack($uri),"","sp_tapage");
}
if ($op=="supp_all") {
   marquetapage_add(removeHack($uri),"","sp_tespages");
}
?>
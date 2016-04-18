<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* DYNAMIC THEME engine for NPDS                                        */
/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
global $meta_glossaire;
function local_var($Xcontent) {
   if (strstr($Xcontent,"!var!")) {
      $deb=strpos($Xcontent,"!var!",0)+5;
      $fin=strpos($Xcontent,' ',$deb);
      if ($fin) {$H_var=substr($Xcontent,$deb,$fin-$deb);}
      else {$H_var=substr($Xcontent,$deb);}
      return ($H_var);
   }
}
function themeindex ($aid, $informant, $time, $title, $counter, $topic, $thetext, $notes, $morelink, $topicname, $topicimage, $topictext, $id) {
   global $tipath, $theme, $nuke_url;
   $inclusion=false;
   if (file_exists("themes/".$theme."/html/index-news.html")) {$inclusion="themes/".$theme."/html/index-news.html";}
   elseif (file_exists("themes/default/html/index-news.html")) {$inclusion="themes/default/html/index-news.html";}
   else {
      echo 'index-news.html manquant / not find !<br />';
      die();
   }
   $H_var=local_var($thetext);
   if ($H_var!='') {
      ${$H_var}=true;
      $thetext=str_replace("!var!$H_var","",$thetext);
   }
   if ($notes!= '') $notes='<div class="note">'.translate("Note").' : '.$notes.'</div>';
   ob_start();
   include($inclusion);
   $Xcontent=ob_get_contents();
   ob_end_clean();

   $lire_la_suite='';
   if ($morelink[0]) $lire_la_suite=$morelink[1].' '.$morelink[0].' | ';
   $commentaire='';
   if ($morelink[2])
      $commentaire=$morelink[2].' '.$morelink[3].' | ';
   else
      $commentaire=$morelink[3].' | ';
   $categorie='';
   if ($morelink[6]) $categorie=' : '.$morelink[6];
   $morel=$lire_la_suite.$commentaire.$morelink[4].' '.$morelink[5].$categorie;

   if (!$imgtmp=theme_image('topics/'.$topicimage)) {$imgtmp=$tipath.$topicimage;}
   $timage=$imgtmp;

   $npds_METALANG_words=array(
   "'!N_publicateur!'i"=>$aid,
   "'!N_emetteur!'i"=>"<a href=\"user.php?op=userinfo&amp;uname=$informant\">$informant</a>",

   "'!N_date!'i"=>formatTimestamp($time),
   "'!N_date_y!'i"=>substr($time,0,4),
   "'!N_date_m!'i"=>strftime("%B", mktime(0,0,0, substr($time,5,2),1,2000)),
   "'!N_date_d!'i"=>substr($time,8,2),
   "'!N_date_h!'i"=>substr($time,11),
   "'!N_print!'i"=>$morelink[4],
   "'!N_friend!'i"=>$morelink[5],

   "'!N_nb_carac!'i"=>$morelink[0],
   "'!N_read_more!'i"=>$morelink[1],
   "'!N_nb_comment!'i"=>$morelink[2],
   "'!N_link_comment!'i"=>$morelink[3],
   "'!N_categorie!'i"=>$morelink[6],

   "'!N_titre!'i"=>$title,
   "'!N_texte!'i"=>$thetext,
   "'!N_id!'i"=>$id,
   "'!N_sujet!'i"=>"<a href=\"search.php?query=&amp;topic=$topic\"><img class=\"img-fluid\" src=\"".$timage."\" alt=\"".translate("Search in")."&nbsp;".$topictext."\" border=\"0\" /></a>",
   "'!N_note!'i"=>$notes,
   "'!N_nb_lecture!'i"=>$counter,
   "'!N_suite!'i"=>$morel
   );
   echo meta_lang(aff_langue(preg_replace(array_keys($npds_METALANG_words),array_values($npds_METALANG_words), $Xcontent)));
}
function themearticle ($aid, $informant, $time, $title, $thetext, $topic, $topicname, $topicimage, $topictext, $id, $previous_sid, $next_sid, $archive) {
   global $tipath, $theme, $nuke_url, $counter;
   global $boxtitle, $boxstuff;
   $inclusion=false;
   if (file_exists("themes/".$theme."/html/detail-news.html")) {$inclusion="themes/".$theme."/html/detail-news.html";}
   elseif (file_exists("themes/default/html/detail-news.html")) {$inclusion="themes/default/html/detail-news.html";}
   else {
      echo 'detail-news.html manquant / not find !<br />';
      die();
   }
   $H_var=local_var($thetext);
   if ($H_var!='') {
      ${$H_var}=true;
      $thetext=str_replace("!var!$H_var","",$thetext);
   }
   ob_start();
   include($inclusion);
   $Xcontent=ob_get_contents();
   ob_end_clean();
   if ($previous_sid) {
      $prevArt="<a href=\"article.php?sid=".$previous_sid."&amp;archive=$archive\" class=\"noir\">".translate("Previous")."</a>";
      if ($next_sid) {$prevArt.="&nbsp;|&nbsp;";}
   }
   else $prevArt='';
   if ($next_sid) {$nextArt="<a href=\"article.php?sid=".$next_sid."&amp;archive=$archive\"  class=\"noir\">".translate("Next")."</a>";}
   else $nextArt='';

   if (!$imgtmp=theme_image("box/print.gif")) { $imgtmp="images/print.gif"; }
   $printP = "<a href=\"print.php?sid=$id\"><img src=\"$imgtmp\" border=\"0\" alt=\"".translate("Printer Friendly Page")."\" style=\"vertical-align: middle;\" /></a>";
   if (!$imgtmp=theme_image("box/friend.gif")) { $imgtmp="images/friend.gif"; }
   $sendF = "<a href=\"friend.php?op=FriendSend&amp;sid=$id\"><img src=\"$imgtmp\" border=\"0\" alt=\"".translate("Send this Story to a Friend")."\" style=\"vertical-align: middle;\" /></a>";

   if (!$imgtmp=theme_image('topics/'.$topicimage)) {$imgtmp=$tipath.$topicimage;}
   $timage=$imgtmp;

   $npds_METALANG_words=array(
   "'!N_publicateur!'i"=>$aid,
   "'!N_emetteur!'i"=>'<a href="user.php?op=userinfo&amp;uname='.$informant.'">'.$informant.'</a>',
   "'!N_date!'i"=>formatTimestamp($time),
   "'!N_date_y!'i"=>substr($time,0,4),
   "'!N_date_m!'i"=>strftime("%B", mktime(0,0,0, substr($time,5,2),1,2000)),
   "'!N_date_d!'i"=>substr($time,8,2),
   "'!N_date_h!'i"=>substr($time,11),
   "'!N_print!'i"=>$printP,
   "'!N_friend!'i"=>$sendF,
   "'!N_boxrel_title!'i"=>$boxtitle,
   "'!N_boxrel_stuff!'i"=>$boxstuff,
   "'!N_titre!'i"=>$title,
   "'!N_id!'i"=>$id,
   "'!N_previous_article!'i"=>$prevArt,
   "'!N_next_article!'i"=>$nextArt,
   "'!N_sujet!'i"=>'<a href="search.php?query=&amp;topic='.$topic.'"><img class="img-fluid" src="'.$timage.'" alt="'.translate("Search in").'&nbsp;'.$topictext.'" /></a>',
   "'!N_texte!'i"=>$thetext,
   "'!N_nb_lecture!'i"=>$counter
   );
   echo meta_lang(aff_langue(preg_replace(array_keys($npds_METALANG_words),array_values($npds_METALANG_words), $Xcontent)));
}
function themesidebox($title, $content) {
   global $theme, $B_class_title, $B_class_content, $bloc_side, $htvar;
   $inclusion=false;
   if (file_exists("themes/".$theme."/html/bloc-right.html") and ($bloc_side=="RIGHT")) {$inclusion='themes/'.$theme.'/html/bloc-right.html';}
   if (file_exists("themes/".$theme."/html/bloc-left.html") and ($bloc_side=="LEFT")) {$inclusion='themes/'.$theme.'/html/bloc-left.html';}
   if (!$inclusion) {
      if (file_exists("themes/".$theme."/html/bloc.html")) {$inclusion='themes/'.$theme.'/html/bloc.html';}
      elseif (file_exists("themes/default/html/footer.html")) {$inclusion='themes/default/html/bloc.html';}
      else {
         echo 'bloc.html manquant / not find !<br />';
         die();
      }
   }
   ob_start();
   include($inclusion);
   $Xcontent=ob_get_contents();
   ob_end_clean();
   if ($title=='no-title') {
      $Xcontent=str_replace("<div class=\"LB_title\">!B_title!</div>",'',$Xcontent);
      $title='';
   }
   $npds_METALANG_words=array(
   "'!B_title!'i"=>$title,
   "'!B_class_title!'i"=>$B_class_title,
   "'!B_class_content!'i"=>$B_class_content,
   "'!B_content!'i"=>$content
   );
   echo $htvar;// modif ji fantôme block
   echo meta_lang(preg_replace(array_keys($npds_METALANG_words),array_values($npds_METALANG_words), $Xcontent));
   echo '
            </div>';// modif ji fantôme block
}
function themedito($content) {
   global $theme;
   $inclusion=false;
   if (file_exists("themes/".$theme."/html/editorial.html")) {$inclusion="themes/".$theme."/html/editorial.html";}
   if ($inclusion) {
      ob_start();
      include($inclusion);
      $Xcontent=ob_get_contents();
      ob_end_clean();
      $npds_METALANG_words=array(
      "'!editorial_content!'i"=>$content
      );
      echo meta_lang(aff_langue(preg_replace(array_keys($npds_METALANG_words),array_values($npds_METALANG_words), $Xcontent)));
   }
   return ($inclusion);
}
?>
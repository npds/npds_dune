<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2026 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
// Modification pour IZ-Xinstall - EBH - JPB & PHR
if (file_exists('IZ-Xinstall.ok')) {
   if (file_exists('install.php') or is_dir('install')) {
      echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
   <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
         <title>NPDS IZ-Xinstall - Installation Configuration</title>
      </head>
      <body>
         <div style="text-align: center; font-size: 20px; font-family: Arial; font-weight: bold; color: #000000"><br />
            NPDS IZ-Xinstall - Installation &amp; Configuration
         </div>
         <div style="text-align: center; font-size: 20px; font-family: Arial; font-weight: bold; color: #ff0000"><br />
            Vous devez supprimer le r&eacute;pertoire "install" ET le fichier "install.php" avant de poursuivre !<br />
            You must remove the directory "install" as well as the file "install.php" before continuing!
         </div>
      </body>
   </html>';
      die();
   }
} else {
   if (file_exists('install.php') AND is_dir('install'))
      header('Location: install.php');
}

if (!function_exists('Mysql_Connexion'))
   include 'mainfile.php';

// Redirect for default Start Page of the portal - look at Admin Preferences for choice
function select_start_page($op) {
   global $Start_Page, $index;
   if (!AutoReg()) { global $user; unset($user); }
   if ($Start_Page == '' || $op == 'index.php' || $op == 'edito' || $op == 'edito-nonews') {
      $index = 1;
      theindex($op, '', '');
      die('');
   } else
      header('Location: '.$Start_Page);
}

function automatednews() {
   global $NPDS_Prefix;
   // Date/heure actuelle (utilise le timezone déjà défini dans mainfile.php)
   $current_timestamp = (new DateTime())->getTimestamp();
   $current_month = getPartOfTime($current_timestamp, 'yyyy-MM');
   // publication automatique
   $result = sql_query("SELECT anid, date_debval FROM ".$NPDS_Prefix."autonews WHERE date_debval LIKE '$current_month%'");
   while(list($anid, $date_debval) = sql_fetch_row($result)) {
      // Convertir la date de publication en timestamp
      $pub_timestamp = strtotime($date_debval);
      // Si la date de publication est passée
      if ($pub_timestamp <= $current_timestamp) {
         $result2 = sql_query("SELECT catid, aid, title, hometext, bodytext, topic, informant, notes, ihome, date_finval, auto_epur FROM ".$NPDS_Prefix."autonews WHERE anid='$anid'");
         while (list($catid, $aid, $title, $hometext, $bodytext, $topic, $author, $notes, $ihome, $date_finval, $epur) = sql_fetch_row($result2)) {
            $subject = stripslashes(FixQuotes($title));
            $hometext = stripslashes(FixQuotes($hometext));
            $bodytext = stripslashes(FixQuotes($bodytext));
            $notes = stripslashes(FixQuotes($notes));
            sql_query("INSERT INTO ".$NPDS_Prefix."stories VALUES (NULL, '$catid', '$aid', '$subject', now(), '$hometext', '$bodytext', '0', '0', '$topic', '$author', '$notes', '$ihome', '0', '$date_finval', '$epur')");
            sql_query("DELETE FROM ".$NPDS_Prefix."autonews WHERE anid='$anid'");
            global $subscribe;
            if ($subscribe)
               subscribe_mail('topic',$topic,'',$subject,'');
            // Réseaux sociaux
            if (file_exists('modules/npds_twi/npds_to_twi.php')) 
               include 'modules/npds_twi/npds_to_twi.php';
            if (file_exists('modules/npds_fbk/npds_to_fbk.php')) 
               include 'modules/npds_twi/npds_to_fbk.php';
         }
      }
   }
   // Purge automatique
   $result = sql_query("SELECT sid, date_finval, auto_epur FROM ".$NPDS_Prefix."stories WHERE date_finval LIKE '$current_month%'");
   while(list($sid, $date_finval, $epur) = sql_fetch_row($result)) {
      $expiry_timestamp = strtotime($date_finval);
      if ($expiry_timestamp <= $current_timestamp) {
         if ($epur == 1) {
            // Suppression définitive
            sql_query("DELETE FROM ".$NPDS_Prefix."stories WHERE sid='$sid'");
            // Purge des commentaires associés si le module existe
            if (file_exists('modules/comments/article.conf.php')) {
                include 'modules/comments/article.conf.php';
                sql_query("DELETE FROM ".$NPDS_Prefix."posts WHERE forum_id='$forum' AND topic_id='$topic'");
            }
            Ecr_Log('security', "removeStory ($sid, epur) by automated epur : system", '');
        } else
            // Archivage
            sql_query("UPDATE ".$NPDS_Prefix."stories SET archive='1' WHERE sid='$sid'");
      }
   }
}

function aff_edito() {
   list($affich,$Xcontents)=fab_edito();
   if (($affich) and ($Xcontents != '')) {
      $notitle = false;
      if (strstr($Xcontents,'!edito-notitle!')) {
         $notitle = 'notitle';
         $Xcontents = str_replace('!edito-notitle!','',$Xcontents);
      }
      $ret = false;
      if (function_exists('themedito'))
         $ret = themedito($Xcontents);
      else {
         if (function_exists('theme_centre_box')) {
            $title = (!$notitle) ? translate('EDITO') : '' ;
            theme_centre_box($title, $Xcontents);
            $ret = true;
         }
      }
      if ($ret == false) {
          if (!$notitle)
             echo '<span class="edito">'.translate('EDITO').'</span>';
          echo $Xcontents;
          echo '<br />';
      }
   }
}

function aff_news($op,$catid,$marqeur) {
   $url = $op;
   if ($op == 'edito-newindex') {
      if ($marqeur == 0) aff_edito();
      $op = 'news';
   }
   if ($op == 'newindex')
      $op = $catid == '' ? 'news' : 'categories' ;
   if ($op == 'newtopic')
      $op = 'topics';
   if ($op == 'newcategory')
      $op = 'categories';
   $news_tab = prepa_aff_news($op,$catid,$marqeur);
   $story_limit = 0;

   // si le tableau $news_tab est vide alors return 
   if(is_null($news_tab)) return;

   $newscount = sizeof($news_tab);
   while ($story_limit < $newscount) {
      $story_limit++;
      $aid = unserialize($news_tab[$story_limit]['aid']);
      $informant = unserialize($news_tab[$story_limit]['informant']);
      $datetime = unserialize($news_tab[$story_limit]['datetime']);
      $title = unserialize($news_tab[$story_limit]['title']);
      $counter = unserialize($news_tab[$story_limit]['counter']);
      $topic = unserialize($news_tab[$story_limit]['topic']);
      $hometext = unserialize($news_tab[$story_limit]['hometext']);
      $notes = unserialize($news_tab[$story_limit]['notes']);
      $morelink = unserialize($news_tab[$story_limit]['morelink']);
      $topicname = unserialize($news_tab[$story_limit]['topicname']);
      $topicimage = unserialize($news_tab[$story_limit]['topicimage']);
      $topictext = unserialize($news_tab[$story_limit]['topictext']);
      $s_id = unserialize($news_tab[$story_limit]['id']);
      themeindex($aid, $informant, $datetime, $title, $counter, $topic, $hometext, $notes, $morelink, $topicname, $topicimage, $topictext, $s_id);
   }

   $transl1 = translate('Page suivante');
   $transl2 = translate('Home');
   global $storyhome, $cookie;
   $storynum = isset($cookie[3]) ? $cookie[3] : $storyhome ;
   if ($op == 'categories') {
      if (sizeof($news_tab) == $storynum) {
         $marqeur = $marqeur + sizeof($news_tab);
         echo '
            <div class="text-end"><a href="index.php?op='.$url.'&amp;catid='.$catid.'&amp;marqeur='.$marqeur.'" class="page_suivante" >'.$transl1.'<i class="fa fa-chevron-right fa-lg ms-2" title="'.$transl1.'" data-bs-toggle="tooltip"></i></a></div>';
      } else {
         if ($marqeur >= $storynum)
            echo '
            <div class="text-end"><a href="index.php?op='.$url.'&amp;catid='.$catid.'&amp;marqeur=0" class="page_suivante" title="'.$transl2.'">'.$transl2.'</a></div>';
      }
   }
   if ($op == 'news') {
      if (sizeof($news_tab) == $storynum) {
         $marqeur = $marqeur + sizeof($news_tab);
         echo '
            <div class="text-end"><a href="index.php?op='.$url.'&amp;catid='.$catid.'&amp;marqeur='.$marqeur.'" class="page_suivante" >'.$transl1.'<i class="fa fa-chevron-right fa-lg ms-2" title="'.$transl1.'" data-bs-toggle="tooltip"></i></a></div>';
      } else {
         if ($marqeur >= $storynum)
            echo '
            <div class="text-end"><a href="index.php?op='.$url.'&amp;catid='.$catid.'&amp;marqeur=0" class="page_suivante" title="'.$transl2.'">'.$transl2.'</a></div>';
      }
   }
   if ($op == 'topics') {
      if (sizeof($news_tab) == $storynum) {
         $marqeur = $marqeur + sizeof($news_tab);
         echo '
            <div align="right"><a href="index.php?op=newtopic&amp;topic='.$topic.'&amp;marqeur='.$marqeur.'" class="page_suivante" >'.$transl1.'<i class="fa fa-chevron-right fa-lg ms-2" title="'.$transl1.'" data-bs-toggle="tooltip"></i></a></div>';
      } else {
         if ($marqeur >= $storynum)
            echo '
            <div class="text-end"><a href="index.php?op=newtopic&amp;topic='.$topic.'&amp;marqeur=0" class="page_suivante" title="'.$transl2.'">'.$transl2.'</a></div>';
      }
   }
}

function theindex($op, $catid, $marqeur) {
    include 'header.php';
    // Include cache manager
    global $SuperCache;
    if ($SuperCache) {
       $cache_obj = new cacheManager();
       $cache_obj->startCachingPage();
    } else
       $cache_obj = new SuperCacheEmpty();
    if (($cache_obj->genereting_output == 1) or ($cache_obj->genereting_output == -1) or (!$SuperCache)) {
       // Appel de la publication de News et la purge automatique
       automatednews();

       global $theme;
       if ($op == 'newcategory' || $op == 'newtopic' || $op == 'newindex' || $op == 'edito-newindex')
          aff_news($op, $catid, $marqeur);
       else {
          if (file_exists('themes/'.$theme.'/central.php'))
             include 'themes/'.$theme.'/central.php';
          else {
             if ($op == 'edito' || $op == 'edito-nonews') aff_edito();
             if ($op != 'edito-nonews') aff_news($op, $catid, $marqeur);
          }
       }
    }
    if ($SuperCache)
       $cache_obj->endCachingPage();
    include 'footer.php';
}

settype($op,'string');
settype($catid,'integer');
settype($marqeur,'integer');
switch ($op) {
   case 'newindex': case 'edito-newindex': case 'newcategory':
      theindex($op, $catid, $marqeur);
   break;
   case 'newtopic':
      theindex($op, $topic, $marqeur);
   break;
   default:
      select_start_page($op, '');
   break;
}
?>
<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2023 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/*                                                                      */
/* module npds_twi version 1.0                                          */
/* npds_to_twi.php file 2015 by Jean Pierre Barbary jpb                 */
/************************************************************************/
if (!function_exists("Mysql_Connexion")) die();

// Initialisation
global $nuke_url, $npds_twi, $NPDS_Prefix;
if (!isset($sid)) {
   $result = sql_query("SELECT max(sid) FROM ".$NPDS_Prefix."stories");
   list ($sid)=sql_fetch_row($result);
}

if ($npds_twi===1) {
   require_once('modules/npds_twi/include/ab-twitteroauth/twitteroauth/twitteroauth.php');
   require_once('modules/npds_twi/twi_conf.php');
   /*Crée un objet TwitterOauth*/
   $connection = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);
   $connection->host = "https://api.twitter.com/1.1/";
   
   /* parametres */
   $max_twi=280;
   $dif_len=0;
   $query_art_short='';// raccourci pour les articles
   $query_topi_short='';// raccourci pour les posts

   if ($npds_twi_arti===1) {
      $query_art_short='s';
      /* préparation du contenu du tweet */
      $subj_twi=strip_tags($subject);
      if(cur_charset!=='utf-8')
      $subj_twi=utf8_encode ($subj_twi);
      $subj_twi=preg_replace ( "#''#", '\'', $subj_twi);
      $subj_twi=html_entity_decode ($subj_twi);
      $text_twi=strip_tags($hometext);
      $text_twi=html_entity_decode ($text_twi);
      if(cur_charset!=='utf-8')
      $text_twi=utf8_encode ($text_twi);
      $text_twi=preg_replace ( "#''#", '\'', $text_twi);
      $text_twi=preg_replace ( "#yt_video\(([^,]*),([^,]*),([^\)]*)\)#", 'Voir la vidéo...', $text_twi);

      switch($npds_twi_urshort) {
        case 1:$link_twi=$nuke_url.'/'.$query_art_short.$sid; break;
        case 2:$link_twi=$nuke_url.'/'.$query_art_short.'/'.$sid; break;
        case 3:$link_twi=$nuke_url.'/'.$query_art_short.'.php/'.$sid; break;
        default:$link_twi=''; break;
      }
      $subj_len=strlen($subj_twi);
      $homtext_len =strlen($text_twi);
      $linkback_len=strlen($link_twi);
      $dif_len=$max_twi - ($subj_len+$linkback_len);

      if (($subj_len+$linkback_len) > $max_twi) {
         $subj_twi = substr($subj_twi,0,($dif_len-4)).'.. '.$link_twi;
      }
      if ((($subj_len+$linkback_len) < $max_twi) and ($dif_len > 10)) {
         $subj_twi = $subj_twi.' '.substr($text_twi,0,($dif_len-4)).'.. '.$link_twi;
      } else {
         $subj_twi = $subj_twi.'.. '.$link_twi;
      }

      /* envoi le tweet du nouvel article */
      $parameters = array('status' => $subj_twi);
      $status = $connection->post('statuses/update', $parameters);
   }
   //if ($npds_twi_post===1) {
   // a developper
   //}
}
?>
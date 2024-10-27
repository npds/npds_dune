<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/*                                                                      */
/* NPDS Copyright (c) 2001-2024 by Philippe Brunier                     */
/* =========================                                            */
/*                                                                      */
/* Based on phpmyadmin.net  grabber library                             */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
/* This library grabs the names and values of the variables sent or     */
/* posted to a script in superglobals arrays and sets simple globals    */
/* variables from them                                                  */
/************************************************************************/

if (stristr($_SERVER['PHP_SELF'],'grab_globals.php') and strlen($_SERVER['QUERY_STRING']) !='') 
   include('admin/die.php');

if (!defined('NPDS_GRAB_GLOBALS_INCLUDED')) {
   define('NPDS_GRAB_GLOBALS_INCLUDED', 1);

   // Modify the report level of PHP
   // error_reporting(0);// report NO ERROR
   //error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE); // Devel report
   error_reporting(E_ERROR | E_WARNING | E_PARSE); // standard ERROR report
   //error_reporting(E_ALL);
   function getip() {
      if (isset($_SERVER)) {
         if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
         elseif (isset($_SERVER['HTTP_CLIENT_IP']))
            $realip = $_SERVER['HTTP_CLIENT_IP'];
         else
            $realip = $_SERVER['REMOTE_ADDR'];
      } else {
         if (getenv('HTTP_X_FORWARDED_FOR'))
            $realip = getenv('HTTP_X_FORWARDED_FOR');
         elseif (getenv('HTTP_CLIENT_IP'))
            $realip = getenv('HTTP_CLIENT_IP');
         else
            $realip = getenv('REMOTE_ADDR');
    }
    if (strpos($realip, ",")>0)
       $realip=substr($realip,0,strpos($realip, ",")-1);
    // from Gu1ll4um3r0m41n - 08-05-2007 - dev 2012
    return (trim($realip));
   } 

   function access_denied() {
      include("admin/die.php");
   }

    // First of all : Spam from IP / |5 indicate that the same IP has passed 6 times with status KO in the anti_spambot function
   if (file_exists("slogs/spam.log"))
      $tab_spam=str_replace("\r\n","",file("slogs/spam.log"));
   if (is_array($tab_spam)) {
      $ipadr = getip();
      $ipv = strstr($ipadr, ':') ? '6' : '4';
      if (in_array($ipadr."|5",$tab_spam))
          access_denied();
      //=> nous pouvons bannir une plage d'adresse ip en V4 (dans l'admin IPban sous forme x.x.%|5 ou x.x.x.%|5)
      if($ipv=='4') {
         $ip4detail = explode('.', $ipadr);
         if (in_array($ip4detail[0].'.'.$ip4detail[1].'.%|5',$tab_spam))
            access_denied();
         if (in_array($ip4detail[0].'.'.$ip4detail[1].'.'.$ip4detail[2].'.%|5',$tab_spam))
            access_denied();
      }
      //=> nous pouvons bannir une plage d'adresse ip en V6 (dans l'admin IPban sous forme x:x:%|5 ou x:x:x:%|5)
      if($ipv=='6') {
         $ip6detail = explode(':', $ipadr);
         if (in_array($ip6detail[0].':'.$ip6detail[1].':%|5',$tab_spam))
            access_denied();
         if (in_array($ip6detail[0].':'.$ip6detail[1].':'.$ip6detail[2].':%|5',$tab_spam))
            access_denied();
      }
   }

   function addslashes_GPC(&$arr) {
      $arr=addslashes($arr);
   }

   // include url_protect Bad Words and create the filter function
   include ("modules/include/url_protect.php");

   function url_protect($arr,$key) {
      global $bad_uri_content, $bad_uri_key, $badname_in_uri;
      $ibid=true;
      // mieux faire face aux techniques d'évasion de code : base64_decode(utf8_decode(bin2hex($arr))));
      $arr=rawurldecode($arr);
      $RQ_tmp=strtolower($arr);
      $RQ_tmp_large=strtolower($key)."=".$RQ_tmp;
      if(
         in_array($RQ_tmp, $bad_uri_content)
         OR
         in_array($RQ_tmp_large, $bad_uri_content)
         OR
         in_array($key, $bad_uri_key,true)
         OR
         count($badname_in_uri)>0
      )
         access_denied();
   }
/*
var_dump($_POST);
var_dump($_SERVER['ORIG_PATH_INFO']);
*/
/*
   function post_protect($arr,$key) {
      global $bad_uri_key, $badname_in_uri;
      if(
         in_array($key, $bad_uri_key,true)
         OR
         count($badname_in_uri)>0
      )
         access_denied();
   }
*/
   // Get values, slash, filter and extract
   if (!empty($_GET)) {
      array_walk_recursive($_GET,'addslashes_GPC');
      reset($_GET);// no need
      array_walk_recursive($_GET,'url_protect');
      extract($_GET, EXTR_OVERWRITE);
   }
   if (!empty($_POST)) {
      array_walk_recursive($_POST,'addslashes_GPC');
/*
      array_walk_recursive($_POST,'post_protect');
      if(!isset($_SERVER['HTTP_REFERER'])) {
         Ecr_Log('security','Ghost form in '.$_SERVER['ORIG_PATH_INFO'].' => who playing with form ?','');
         L_spambot('',"false");
         access_denied();
      }
      else if ($_SERVER['HTTP_REFERER'] !== $nuke_url.$_SERVER['ORIG_PATH_INFO']) {
         Ecr_Log('security','Ghost form in '.$_SERVER['ORIG_PATH_INFO'].'. => '.$_SERVER["HTTP_REFERER"],'');
         L_spambot('',"false");
         access_denied();
      }
*/
      extract($_POST, EXTR_OVERWRITE);
   }
   // Cookies - analyse et purge - shiney 07-11-2010
   if (!empty($_COOKIE))
      extract($_COOKIE, EXTR_OVERWRITE);
   if (isset($user)) {
      $ibid=explode(':',base64_decode($user));
      array_walk($ibid,'url_protect');
      $user=base64_encode(str_replace("%3A",":", urlencode(base64_decode($user))));
   }
   if (isset($user_language)) {
      $ibid=explode(':',$user_language);
      array_walk($ibid,'url_protect');
      $user_language=str_replace("%3A",":", urlencode($user_language));
   }
   if (isset($admin)) {
      $ibid=explode(':',base64_decode($admin));
      array_walk($ibid,'url_protect');
      $admin=base64_encode(str_replace('%3A',':', urlencode(base64_decode($admin))));
   }
   // Cookies - analyse et purge - shiney 07-11-2010
   if (!empty($_SERVER))
      extract($_SERVER, EXTR_OVERWRITE);
   if (!empty($_ENV))
      extract($_ENV, EXTR_OVERWRITE);
   if (!empty($_FILES)) {
      foreach ($_FILES as $key => $value) {
         $$key=$value['tmp_name'];
      }
   }
   unset($bad_uri_content);
   unset($bad_uri_key);
   unset($badname_in_uri);
}
?>
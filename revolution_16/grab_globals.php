<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/*                                                                      */
/* NPDS Copyright (c) 2001-2018 by Philippe Brunier                     */
/* =========================                                            */
/*                                                                      */
/* Based on phpmyadmin.net  grabber library                             */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
/* This library grabs the names and values of the variables sent or     */
/* posted to a script in the '$HTTP_*_VARS' arrays and sets simple      */
/* globals variables from them and  use the new globals arrays defined  */
/* with php 4.1+                                                        */
/************************************************************************/

if (!defined('NPDS_GRAB_GLOBALS_INCLUDED')) {
    define('NPDS_GRAB_GLOBALS_INCLUDED', 1);

   // Modify the report level of PHP
   // error_reporting(0);// report NO ERROR
   error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE); // Devel report
   //error_reporting(E_ERROR | E_WARNING | E_PARSE); // standard ERROR report
   error_reporting(E_ALL);

    function getip() {
       if (isset($_SERVER)) {
         if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
         } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
         } else {
            $realip = $_SERVER['REMOTE_ADDR'];
         }
       } else {
         if ( getenv('HTTP_X_FORWARDED_FOR') ) {
            $realip = getenv('HTTP_X_FORWARDED_FOR');
         } elseif (getenv('HTTP_CLIENT_IP')) {
            $realip = getenv('HTTP_CLIENT_IP');
         } else {
            $realip = getenv('REMOTE_ADDR');
         }
       }
       if (strpos($realip, ",")>0) {
          $realip=substr($realip,0,strpos($realip, ",")-1);
       }
       // from Gu1ll4um3r0m41n - 08-05-2007 - dev 2012
       return (urlencode(trim($realip)));
    } 

    function access_denied() {
       include("admin/die.php");
    }

    // Boris 2012 - simulate PHP5 fonction array_walk_recursive / Mod by Dev to realy support PHP4 
    if (!function_exists("array_walk_recursive")) {
       function array_walk_recursive(&$tab, $callback, $userdata = null) {
          foreach($tab as $key => $dumy) {
             $value =& $tab[$key];
             if (is_array($value)) {
                if (!array_walk_recursive($value, $callback, $userdata)) {
                   return false;
                }
             } else {
                $callback($value, $key, $userdata);
             }
          }
          return true;
       }
    }
    
    // First of all : Spam from IP / :5 indicate that the same IP has passed 6 times with status KO in the anti_spambot function
    if (file_exists("slogs/spam.log")) {
       $tab_spam=str_replace("\r\n","",file("slogs/spam.log"));
    }
    if (is_array($tab_spam)) {
       if (in_array(getip().":5",$tab_spam)) {
          access_denied();
       }
    }

    if (get_magic_quotes_runtime()==1) {set_magic_quotes_runtime(0);}
    // To prevent SQL Injection when magic_quotes GPC is off 
    $magicquotesGPC=get_magic_quotes_gpc();
    function addslashes_GPC(&$arr) {
       $arr=addslashes($arr);
    }

    // include current charset
    if (file_exists("meta/cur_charset.php"))  {
       include ("meta/cur_charset.php");
     }
    // include url_protect Bad Words and create the filter function
    include ("modules/include/url_protect.php");

    function url_protect($arr,$key) {
       global $bad_uri_content;
       reset($bad_uri_content);
       $ibid=true;

       // mieux faire face aux techniques d'évasion de code : base64_decode(utf8_decode(bin2hex($arr))));
       $arr=rawurldecode($arr);
       $RQ_tmp=strtolower($arr);
       $RQ_tmp_large=strtolower($key)."=".$RQ_tmp;
       
       while($uri_content=each($bad_uri_content)) {
          $pos=strpos($RQ_tmp,$uri_content[1]);
          $pos_large=strpos($RQ_tmp_large,$uri_content[1]);
          if (($pos!==false) OR ($pos_large!==false)) {
             access_denied();
          }
       }
    }

    // Get values, slash, filter and extract
    if (!empty($_GET)) {
       if (!$magicquotesGPC)
          array_walk_recursive($_GET,'addslashes_GPC');
       reset($_GET);
       array_walk_recursive($_GET,'url_protect');
       extract($_GET, EXTR_OVERWRITE);
    } else if (!empty($HTTP_GET_VARS)) {
       if (!$magicquotesGPC)
          array_walk_recursive($HTTP_GET_VARS,'addslashes_GPC');
       reset($HTTP_GET_VARS);
       array_walk_recursive($HTTP_GET_VARS,'url_protect');
       extract($HTTP_GET_VARS, EXTR_OVERWRITE);
    }
    
    if (!empty($_POST)) {
       if (!$magicquotesGPC)
          array_walk_recursive($_POST,'addslashes_GPC');
       reset($_POST);
       //array_walk_recursive($_POST,'url_protect');
       extract($_POST, EXTR_OVERWRITE);
    } else if (!empty($HTTP_POST_VARS)) {
       if (!$magicquotesGPC)
          array_walk_recursive($HTTP_POST_VARS,'addslashes_GPC');
       reset($HTTP_POST_VARS);
       //array_walk_recursive($HTTP_POST_VARS,'url_protect');
       extract($HTTP_POST_VARS, EXTR_OVERWRITE);
    }

    // Cookies - analyse et purge - shiney 07-11-2010
    if (!empty($_COOKIE)) {
       extract($_COOKIE, EXTR_OVERWRITE);
    } else if (!empty($HTTP_COOKIE_VARS)) {
       extract($HTTP_COOKIE_VARS, EXTR_OVERWRITE);
    }
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

    if (!empty($_SERVER)) {
       extract($_SERVER, EXTR_OVERWRITE);
    } else if (!empty($HTTP_SERVER_VARS)) {
       extract($HTTP_SERVER_VARS, EXTR_OVERWRITE);
    }

    if (!empty($_ENV)) {
       extract($_ENV, EXTR_OVERWRITE);
    } else if (!empty($HTTP_ENV_VARS)) {
       extract($HTTP_ENV_VARS, EXTR_OVERWRITE);
    }

    if (!empty($_FILES)) {
       while (list($key,$value)=each($_FILES)) {
          $$key=$value['tmp_name'];
       }
    } else if (!empty($HTTP_POST_FILES)) {
       while (list($key,$value)=each($HTTP_POST_FILES)) {
          $$key=$value['tmp_name'];
      }
    }

    unset($bad_uri_content);
}
?>
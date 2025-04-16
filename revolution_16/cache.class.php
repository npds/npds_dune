<?php
/************************************************************************/
/* DUNE by NPDS / SUPER-CACHE engine                                    */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
/************************************************************************/
/*  Original Autor : Francisco Echarte [patxi@eslomas.com]              */
/*  Revision : 2004-03-15 Version: 1.1 / multi-language support by Dev  */
/*  Revision : 2004-08-10 Version: 1.2 / SQL support by Dev             */
/*  Revision : 2006-01-28 Version: 1.3 / .common support by Dev         */
/*  Revision : 2009-03-12 Version: 1.4 / clean_limit mods by Dev        */
/*  Revision : 2018 Version: 1.5 / support php 7                        */
/************************************************************************/
include_once('cache.config.php');
include_once('cache.timings.php');
if (!defined("LOCK_EX")) define ("LOCK_EX",2);
if (!defined("LOCK_EX")) define ("LOCK_UN",3);

class SuperCacheEmpty {
   var $genereting_output;
}

class cacheManager{
   var $request_uri;
   var $query_string;
   var $php_self;
   var $genereting_output;
   var $site_overload;

   public function __construct()
    {
      global $CACHE_CONFIG;

      $this->genereting_output = 0;
      if (!empty($_SERVER) && isset($_SERVER['REQUEST_URI'])) {
         $this->request_uri=$_SERVER['REQUEST_URI'];
      } else {
         $this->request_uri=getenv('REQUEST_URI');
      }
      if (!empty($_SERVER) && isset($_SERVER['QUERY_STRING'])) {
         $this->query_string=$_SERVER['QUERY_STRING'];
      } else {
         $this->query_string=getenv('QUERY_STRING');
      }
      if (!empty($_SERVER) && isset($_SERVER['PHP_SELF'])) {
         $this->php_self=basename($_SERVER['PHP_SELF']);
      } else {
         $this->php_self=basename($GLOBALS['PHP_SELF']);
      }

      $this->site_overload=false;
      if (file_exists("cache/site_load.log")) {
         $site_load=file("cache/site_load.log");
         if ($site_load[0]>=$CACHE_CONFIG['clean_limit']) {
            $this->site_overload=true;
         }
      }
      if (($CACHE_CONFIG['run_cleanup']==1) and (!$this->site_overload)) {
         $this->cacheCleanup();
      }
   }
   public function cacheManager() {
      self::__construct();
   }

   function startCachingPage() {
      global $CACHE_TIMINGS, $CACHE_CONFIG, $CACHE_QUERYS;
      if (array_key_exists($this->php_self, $CACHE_TIMINGS) && $CACHE_TIMINGS[$this->php_self] > 0 && ($this->query_string == '' || (array_key_exists($this->php_self, $CACHE_QUERYS) && preg_match("#".$CACHE_QUERYS[$this->php_self]."#", $this->query_string)))) {
         $cached_page = $this->checkCache($this->request_uri,$CACHE_TIMINGS[$this->php_self]);
         if ($cached_page != '') {
            echo $cached_page;
            global $npds_sc;
            $npds_sc=true;
            $this->logVisit($this->request_uri, 'HIT');
            if ($CACHE_CONFIG['exit'] == 1) { exit; }
         } else {
            ob_start();
            $this->genereting_output = 1;
            $this->logVisit($this->request_uri, 'MISS');
         }
      } else {
         $this->logVisit($this->request_uri, 'EXCL');
         $this->genereting_output = -1;
      }
   }

   function endCachingPage() {
      global $CACHE_CONFIG;

      if ($this->genereting_output == 1) {
         $output = ob_get_contents();
         // if you want to activate rewrite engine
         //if (file_exists("lib/rewrite_engine.php")) {
         //   include ("lib/rewrite_engine.php");
         //}
         ob_end_clean();
         $this->insertIntoCache($output, $this->request_uri);
      }
   }

   function checkCache($request,$refresh) {
      global $CACHE_CONFIG, $user, $language;

      if (!$CACHE_CONFIG['non_differentiate']) {
         if (isset($user) and $user!='') {
            $cookie = explode(':', base64_decode($user));
            $cookie=$cookie[1];
         } else
            $cookie='';
      }
      // the .common is used for non differentiate cache page (same page for user and anonymous)
      if (substr($request,-7)=='.common')
         $cookie='';

      $filename = $CACHE_CONFIG['data_dir'].$cookie.md5($request).'.'.$language;
      // Overload
      if ($this->site_overload)
         $refresh=$refresh*2;

      if (file_exists($filename)) {
         if (filemtime($filename) > time()-$refresh) {
            if (filesize($filename)>0) {
               $data = fread($fp = fopen($filename, 'r'), filesize($filename));
               fclose($fp);
               return($data);
            } else
               return('');
         } else
            return('');
      } else
         return('');
   }

   function insertIntoCache($content, $request) {
      global $CACHE_CONFIG, $user, $language;

      if (!$CACHE_CONFIG['non_differentiate']) {
         if (isset($user) and $user!='') {
            $cookie = explode(":", base64_decode($user));
            $cookie=$cookie[1];
         } else
            $cookie='';
      }
      // the .common is used for non differentiate cache page (same page for user and anonymous)
      if (substr($request,-7)=='.common')
         $cookie='';

      if (substr($request,0,5)=='objet') {
         $request=substr($request,5);
         $affich=false;
      } else
         $affich=true;

      $nombre = $CACHE_CONFIG['data_dir'].$cookie.md5($request).'.'.$language;

      if ($fp = fopen($nombre, 'w')) {
         flock($fp, LOCK_EX);
         fwrite($fp,$content);
         flock($fp, LOCK_UN);
         fclose($fp);
      }
      if ($affich)
         echo $content;

      global $npds_sc;
      $npds_sc=false;
   }

   function logVisit($request, $type) {
      global $CACHE_CONFIG;

      if (!$CACHE_CONFIG['save_stats']) return;
      $logfile = $CACHE_CONFIG['data_dir'].'stats.log';
      $fp = fopen($logfile, 'a');
      flock($fp, LOCK_EX);
      fseek($fp, filesize($logfile));
          $salida = sprintf("%-10s %-74s %-4s\r\n",time(),$request,$type);
          fwrite($fp, $salida);
      flock($fp, LOCK_UN);
      fclose($fp);
   }

   function cacheCleanup() {
      // Cette fonction n'est plus adaptée au nombre de fichiers manipulé par SuperCache
      global $CACHE_CONFIG;

      srand((double)microtime()*1000000);
      $num = rand(1,100);
      if ($num <= $CACHE_CONFIG['cleanup_freq']) {
         $dh = opendir($CACHE_CONFIG['data_dir']);
         $clean=false;
         // Clean SC directory
         $objet="SC";
         while (false!==($filename = readdir($dh))) {
            if ($filename === '.' OR $filename === '..' OR $filename === 'sql' OR $filename === 'index.html') continue;
            if (filemtime($CACHE_CONFIG['data_dir'].$filename) < time() - $CACHE_CONFIG['max_age']) {
               @unlink($CACHE_CONFIG['data_dir'].$filename);
               $clean=true;
            }
         }
         closedir($dh);
         // Clean SC/SQL directory
         $dh = opendir($CACHE_CONFIG['data_dir']."sql/");
         $objet.="+SQL";
         while (false!==($filename = readdir($dh))) {
            if ($filename === '.' OR $filename === '..') continue;
            if (filemtime($CACHE_CONFIG['data_dir']."sql/".$filename) < time() - $CACHE_CONFIG['max_age']) {
               @unlink($CACHE_CONFIG['data_dir']."sql/".$filename);
               $clean=true;
            }
         }
         closedir($dh);
         $fp = fopen($CACHE_CONFIG['data_dir']."sql/.htaccess", 'w');
         @fputs($fp, "Deny from All");
         fclose($fp);

         if ($clean)
            $this->logVisit($this->request_uri, 'CLEAN '.$objet);
      }
   }

   function UsercacheCleanup() {
      global $CACHE_CONFIG;
      global $user;
      if (isset($user)) {
         $cookie = explode(":", base64_decode($user));
      }

      $dh = opendir($CACHE_CONFIG['data_dir']);
      while(false!==($filename = readdir($dh))) {
         if ($filename === '.' OR $filename === '..') continue;
         // Le fichier appartient-il à l'utilisateur connecté ?
         if (substr($filename,0,strlen($cookie[1]))==$cookie[1]) {
            // Le calcul md5 fournit une chaine de 32 chars donc si ce n'est pas 32 c'est que c'est un homonyme ...
            $filename_final=explode(".",$filename);
            if (strlen(substr($filename_final[0],strlen($cookie[1])))==32) {
               unlink($CACHE_CONFIG['data_dir'].$filename);
            }
         }
      }
      closedir($dh);
   }

   function startCachingBlock($Xblock) {
      global $CACHE_TIMINGS, $CACHE_CONFIG, $CACHE_QUERYS;
      if ($CACHE_TIMINGS[$Xblock] > 0) {
         $cached_page = $this->checkCache($Xblock,$CACHE_TIMINGS[$Xblock]);
         if ($cached_page != '') {
            echo $cached_page;
            $this->logVisit($Xblock, 'HIT');
            if ($CACHE_CONFIG['exit'] == 1) { exit; }
         } else {
            ob_start();
            $this->genereting_output = 1;
            $this->logVisit($Xblock, 'MISS');
         }
      } else {
         $this->genereting_output = -1;
         $this->logVisit($Xblock, 'NO-CACHE');
      }
   }
   function endCachingBlock($Xblock) {
      global $CACHE_CONFIG;
      if ($this->genereting_output == 1) {
         $output = ob_get_contents();
         ob_end_clean();
         $this->insertIntoCache($output, $Xblock);
      }
   }

   function CachingQuery($Xquery, $retention) {
      global $CACHE_CONFIG;
      $filename = $CACHE_CONFIG['data_dir']."sql/".md5($Xquery);
      if (file_exists($filename)) {
         if (filemtime($filename) > time()-$retention) {
            if (filesize($filename)>0) {
               $data = fread($fp = fopen($filename, 'r'), filesize($filename));
               fclose($fp);
            } else {
               return(array());
            }
            $no_cache=false;
            $this->logVisit($Xquery, 'HIT');
            return (unserialize($data));
         } else
            $no_cache=true;
      } else
         $no_cache=true;

      if ($no_cache) {
         $result = @sql_query($Xquery);
         $tab_tmp=array();
         while($row = sql_fetch_assoc($result)) {
            $tab_tmp[]=$row;
         }
         if ($fp = fopen($filename, 'w')) {
            flock($fp, LOCK_EX);
            fwrite($fp,serialize($tab_tmp));
            flock($fp, LOCK_UN);
            fclose($fp);
         }
         $this->logVisit($Xquery, 'MISS');
         return ($tab_tmp);
      }
   }

   function startCachingObjet($Xobjet) {
      global $CACHE_TIMINGS, $CACHE_CONFIG, $CACHE_QUERYS;
      if ($CACHE_TIMINGS[$Xobjet] > 0) {
         $cached_page = $this->checkCache($Xobjet,$CACHE_TIMINGS[$Xobjet]);
         if ($cached_page != '') {
            $this->logVisit($Xobjet, 'HIT');
            if ($CACHE_CONFIG['exit'] == 1) { exit; }
            return (unserialize($cached_page));
         } else {
            $this->genereting_output = 1;
            $this->logVisit($Xobjet, 'MISS');
            return ("");
         }
      } else {
         $this->genereting_output = -1;
         $this->logVisit($Xobjet, 'NO-CACHE');
         return ("");
      }
   }
   function endCachingObjet($Xobjet, $Xtab){
      global $CACHE_CONFIG;
      if ($this->genereting_output == 1) {
         $this->insertIntoCache(serialize($Xtab), "objet".$Xobjet);
      }
   }
}

// Ces fonctions sont en dehors de la Classe pour permettre un appel sans instanciation d'objet
function Q_Select($Xquery, $retention=3600) {
   global $SuperCache, $cache_obj;
   if (($SuperCache) and ($cache_obj)) {
      $row=$cache_obj->CachingQuery($Xquery, $retention);
      return ($row);
   } else {
      $result = @sql_query($Xquery);
      $tab_tmp=array();
      while($row = sql_fetch_assoc($result)) {
         $tab_tmp[]=$row;
      }
      return ($tab_tmp);
   }
}

function PG_clean($request) {
    global $CACHE_CONFIG;
    $page = md5($request);
    $dh = opendir($CACHE_CONFIG['data_dir']);
    while(false!==($filename = readdir($dh))) {
       if ($filename === '.' OR $filename === '..' OR (strpos($filename,$page)===FALSE) ) continue;
          unlink($CACHE_CONFIG['data_dir'].$filename);
    }
    closedir($dh);
}

function Q_Clean() {
    global $CACHE_CONFIG;
    $dh = opendir($CACHE_CONFIG['data_dir']."sql");
    while(false!==($filename = readdir($dh))) {
       if ($filename === '.' OR $filename === '..') continue;
          if (is_file($CACHE_CONFIG['data_dir']."sql/".$filename))
             unlink($CACHE_CONFIG['data_dir']."sql/".$filename);
    }
    closedir($dh);
    $fp = fopen($CACHE_CONFIG['data_dir']."sql/.htaccess", 'w');
    @fputs($fp, "Deny from All");
    fclose($fp);
}

function SC_clean () {
   global $CACHE_CONFIG;
   $dh = opendir($CACHE_CONFIG['data_dir']);
   while (false!==($filename = readdir($dh))) {
      if ($filename === '.' OR $filename === '..' OR $filename === 'ultramode.txt' OR $filename === 'net2zone.txt' OR $filename === 'sql' OR $filename === 'index.html') continue;
         if (is_file($CACHE_CONFIG['data_dir'].$filename))
            unlink($CACHE_CONFIG['data_dir'].$filename);
   }
   closedir($dh);
   Q_Clean();
}
?>
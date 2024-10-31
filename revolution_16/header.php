<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion")) {
   include ("mainfile.php");
   die();
}

settype($m_keywords, 'string');
settype($m_description, 'string');
$skin='';

function head($tiny_mce_init, $css_pages_ref, $css, $tmp_theme, $skin, $js, $m_description,$m_keywords) {
   global $slogan, $Titlesitename, $banners, $Default_Theme, $theme, $gzhandler, $language, $topic, $hlpfile, $user, $hr, $long_chain, $theme_darkness;

   settype($m_keywords, 'string');
   settype($m_description, 'string');
   if ($gzhandler==1) ob_start("ob_gzhandler");
   include("themes/$tmp_theme/theme.php");

   // Meta
   if (file_exists("meta/meta.php")) {
      $meta_op='';
      include ("meta/meta.php");
   }

   // Favicon
   $favico = (file_exists("themes/$tmp_theme/images/favicon.ico")) ?
      'themes/'.$tmp_theme.'/images/favicon.ico' :
      'images/favicon.ico' ;

   echo '
      <link rel="shortcut icon" href="'.$favico.'" type="image/x-icon" />
      <link rel="apple-touch-icon" sizes="120x120" href="images/favicon-120.png" />
      <link rel="apple-touch-icon" sizes="152x152" href="images/favicon-152.png" />
      <link rel="apple-touch-icon" sizes="180x180" href="images/favicon-180.png" />';

   // Syndication RSS & autres
   global $sitename, $nuke_url;

   // Canonical
   $scheme = strtolower($_SERVER['REQUEST_SCHEME'] ?? 'http');
   $host = $_SERVER['HTTP_HOST'];
   $uri = $_SERVER['REQUEST_URI'];
   echo '
      <link rel="canonical" href="'.($scheme.'://'.$host.$uri).'" />';

   // humans.txt
   if (file_exists("humans.txt"))
      echo '
      <link type="text/plain" rel="author" href="'.$nuke_url.'/humans.txt" />';

   echo '
      <link href="backend.php?op=RSS0.91" title="'.$sitename.' - RSS 0.91" rel="alternate" type="text/xml" />
      <link href="backend.php?op=RSS1.0" title="'.$sitename.' - RSS 1.0" rel="alternate" type="text/xml" />
      <link href="backend.php?op=RSS2.0" title="'.$sitename.' - RSS 2.0" rel="alternate" type="text/xml" />
      <link href="backend.php?op=ATOM" title="'.$sitename.' - ATOM" rel="alternate" type="application/atom+xml" />
';

   // Tiny_mce
   if ($tiny_mce_init)
      echo aff_editeur("tiny_mce", "begin");

   // include externe JAVASCRIPT file from modules/include or themes/.../include for functions, codes in the <body onload="..." event...
   $body_onloadH ='
   <script type="text/javascript">
      //<![CDATA[
         function init() {';
   $body_onloadF ='
         }
      //]]>
   </script>';
   if (file_exists("modules/include/body_onload.inc")) {
      echo $body_onloadH;
      include ("modules/include/body_onload.inc");
      echo $body_onloadF;
   }
   if (file_exists("themes/$tmp_theme/include/body_onload.inc")) {
      echo $body_onloadH;
      include ("themes/$tmp_theme/include/body_onload.inc");
      echo $body_onloadF;
   }

   // include externe file from modules/include or themes/.../include for functions, codes ... - skin motor
   if (file_exists("modules/include/header_head.inc")) {
      ob_start();
      include "modules/include/header_head.inc";
      $hH = ob_get_contents();
      ob_end_clean();

      if ($skin!='' and substr($tmp_theme,-3)=="_sk") {
         $hH=str_replace ('lib/bootstrap/dist/css/bootstrap.min.css','themes/_skins/'.$skin.'/bootstrap.min.css',$hH);
         $hH=str_replace ('lib/bootstrap/dist/css/extra.css','themes/_skins/'.$skin.'/extra.css',$hH);
      }
      echo $hH;
   }
   if (file_exists("themes/$tmp_theme/include/header_head.inc")) include ("themes/$tmp_theme/include/header_head.inc");

   echo import_css($tmp_theme, $language, '', $css_pages_ref, $css);

   // Mod by Jireck - Chargeur de JS via PAGES.PHP
   if ($js) {
      if (is_array($js)) {
         foreach ($js as $k=>$tab_js) {
            if (stristr($tab_js, 'http://')||stristr($tab_js, 'https://'))
               echo '
      <script type="text/javascript" src="'.$tab_js.'"></script>';
            else {
               if (file_exists("themes/$tmp_theme/js/$tab_js") and ($tab_js!=''))
                  echo '
      <script type="text/javascript" src="themes/'.$tmp_theme.'/js/'.$tab_js.'"></script>';
               elseif (file_exists("$tab_js") and ($tab_js!=""))
                  echo '
      <script type="text/javascript" src="'.$tab_js.'"></script>';
            }
         }
      } else {
         if (file_exists("themes/$tmp_theme/js/$js"))
            echo '
      <script type="text/javascript" src="themes/'.$tmp_theme.'/js/'.$js.'"></script>';
         elseif (file_exists("$js"))
            echo '
      <script type="text/javascript" src="'.$js.'"></script>';
      }
   }
   echo '
   </head>';
   include("themes/$tmp_theme/header.php");
}

   // -----------------------
   $header=1;
   // -----------------------

   // include externe file from modules/include for functions, codes ...
   if (file_exists("modules/include/header_before.inc")) include ("modules/include/header_before.inc");

   // take the right theme location !
   global $Default_Theme, $Default_Skin, $user;
   if (isset($user) and $user !='') {
      global $cookie;
      if($cookie[9] !='') {
         $ibix=explode('+', urldecode($cookie[9]));
         if (array_key_exists(0, $ibix)) $theme=$ibix[0]; else $theme=$Default_Theme;
         if (array_key_exists(1, $ibix)) $skin=$ibix[1]; else $skin=$Default_Skin;
         $tmp_theme=$theme;
         if (!$file=@opendir("themes/$theme")) $tmp_theme=$Default_Theme;
      } else 
         $tmp_theme=$Default_Theme;
   } else {
      $theme=$Default_Theme;
      $skin=$Default_Skin;
      $tmp_theme=$theme;
   }

   // LOAD pages.php and Go ...
   settype($PAGES, 'array');
   global $pdst, $Titlesitename, $PAGES;
   require_once("themes/pages.php");

   // import pages.php specif values from theme (toutes valeurs déjà définies dans themes/pages.php seront donc modifiées !)
   if (file_exists("themes/".$tmp_theme."/pages.php"))
      include ("themes/".$tmp_theme."/pages.php");

   $page_uri=preg_split("#(&|\?)#",$_SERVER['REQUEST_URI']);
   $Npage_uri=count($page_uri);
   $pages_ref=basename($page_uri[0]);
   if ($pages_ref=="user.php")
      $pages_ref=substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'],"user.php"));
   // Static page and Module can have Bloc, Title ....
   if ($pages_ref=="static.php")
      $pages_ref=substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'],"static.php"));
   if ($pages_ref=="modules.php")
      $pages_ref = (isset($PAGES["modules.php?ModPath=$ModPath&ModStart=$ModStart*"]['title'])) ?
         "modules.php?ModPath=$ModPath&ModStart=$ModStart*" :
          substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'],"modules.php")) ;

   // Admin function can have all the PAGES attributs except Title
         if ($pages_ref=="admin.php") {
         if (array_key_exists(1,$page_uri)) {
            if (array_key_exists($pages_ref."?".$page_uri[1],$PAGES)) {
               if (array_key_exists('title',$PAGES[$pages_ref."?".$page_uri[1]]))
                  $pages_ref.="?".$page_uri[1];
            }
         }
      }
   /*
   if ($pages_ref=="admin.php") {
      $others='';
      if (array_key_exists(1,$page_uri)) {
         foreach($page_uri as $k => $partofurl) {
            if ($k==1)
               $firstPara ="?".$page_uri[$k];
            if($k>1)
               $others .="&".$page_uri[$k];
         }
      $pages_ref .= $firstPara.$others;
      }
   }
*/

   // extend usage of pages.php : blocking script with part of URI for user, admin or with the value of a VAR
   if ($Npage_uri>1) {
      for ($uri=1; $uri<$Npage_uri; $uri++) {
         if (array_key_exists($page_uri[$uri],$PAGES)) {
            if (!$$PAGES[$page_uri[$uri]]['run']) {
               header("location: ".$PAGES[$page_uri[$uri]]['title']);
               die();
            }
         }
      }
   }

   // -----------------------
   // A partir de ce niveau - $PAGES[$pages_ref] doit exister - sinon c'est que la page n'est pas dans pages.php
   // -----------------------
   if (array_key_exists($pages_ref,$PAGES)) {
      // what a bloc ... left, right, both, ...
      if (array_key_exists('blocs',$PAGES[$pages_ref]))
         $pdst=$PAGES[$pages_ref]['blocs'];

      // block execution of page with run attribute = no
      if ($PAGES[$pages_ref]['run']=="no") {
         if ($pages_ref=="index.php") {
            $Titlesitename="NPDS";
            if (file_exists("meta/meta.php"))
               include("meta/meta.php");
            if (file_exists("static/webclosed.txt"))
               include("static/webclosed.txt");
            die();
         } else
            header("location: index.php");
      // run script to another 'location'
      } elseif (($PAGES[$pages_ref]['run']!="yes") and (($PAGES[$pages_ref]['run']!="")))
         header("location: ".$PAGES[$pages_ref]['run']);

      // Assure la gestion des titres ALTERNATIFS
      $tab_page_ref=explode("|",$PAGES[$pages_ref]['title']);
      if (count($tab_page_ref)>1) {
         $PAGES[$pages_ref]['title'] = (strlen($tab_page_ref[1])>1) ? $tab_page_ref[1] : $tab_page_ref[0] ;
         $PAGES[$pages_ref]['title']=strip_tags($PAGES[$pages_ref]['title']);
      }
      $fin_title=substr($PAGES[$pages_ref]['title'],-1);
      $TitlesitenameX=aff_langue(substr($PAGES[$pages_ref]['title'],0,strlen($PAGES[$pages_ref]['title'])-1));
      if ($fin_title=="+")
         $Titlesitename=$TitlesitenameX." - ".$Titlesitename;
      else if ($fin_title=='-')
         $Titlesitename=$TitlesitenameX;
      if ($Titlesitename=='') $Titlesitename=$sitename;
      // globalisation de la variable title pour marquetapage mais protection pour la zone admin
      if ($pages_ref!="admin.php")
         global $title;
      if (!$title)
         $title = ($fin_title=="+" or $fin_title=="-") ?
            $TitlesitenameX :
            aff_langue(substr($PAGES[$pages_ref]['title'],0,strlen($PAGES[$pages_ref]['title']))) ;
      else
         $title=removeHack($title);
   
      // meta description
      settype($m_description, 'string');
      if (array_key_exists('meta-description',$PAGES[$pages_ref]) and ($m_description==''))
         $m_description=aff_langue($PAGES[$pages_ref]['meta-description']);
      // meta keywords
      settype($m_keywords, 'string');
      if (array_key_exists('meta-keywords',$PAGES[$pages_ref]) and ($m_keywords==''))
         $m_keywords=aff_langue($PAGES[$pages_ref]['meta-keywords']);
   }

   // Initialisation de TinyMCE
   global $tiny_mce,$tiny_mce_theme,$tiny_mce_relurl;
   if ($tiny_mce) {
      if (array_key_exists($pages_ref,$PAGES)) {
         if (array_key_exists('TinyMce',$PAGES[$pages_ref])) {
            $tiny_mce_init=true;
            if (array_key_exists('TinyMce-theme',$PAGES[$pages_ref]))
               $tiny_mce_theme=$PAGES[$pages_ref]['TinyMce-theme'];
            if (array_key_exists('TinyMceRelurl',$PAGES[$pages_ref]))
               $tiny_mce_relurl=$PAGES[$pages_ref]['TinyMceRelurl'];
         } else {
            $tiny_mce_init=false;
//            $tiny_mce=false; //pourquoi la redéfinir - cela affecte le controle de son état dans les préférences
         }
      } else {
         $tiny_mce_init=false;
//         $tiny_mce=false;// idem sup
      }
   } else
      $tiny_mce_init=false;

   // Chargeur de CSS via PAGES.PHP

   if (array_key_exists($pages_ref,$PAGES)) {
      if (array_key_exists('css',$PAGES[$pages_ref])) {
         $css_pages_ref=$pages_ref;
         $css=$PAGES[$pages_ref]['css'];
      } else {
         $css_pages_ref='';
         $css='';
      }
   } else {
      $css_pages_ref='';
      $css='';
   }

   // Mod by Jireck - Chargeur de JS via PAGES.PHP
   if (array_key_exists($pages_ref,$PAGES)) {
      if (array_key_exists('js',$PAGES[$pages_ref])) {
         $js=$PAGES[$pages_ref]['js'];
         if ($js!='') { global $pages_js; $pages_js=$js; }
      } else
         $js='';
   } else
      $js='';
   head($tiny_mce_init, $css_pages_ref, $css, $tmp_theme, $skin, $js, $m_description,$m_keywords);
   global $httpref, $nuke_url, $httprefmax, $admin, $NPDS_Prefix;
   if ($httpref==1) {
      $referer= htmlentities(strip_tags(removeHack(getenv("HTTP_REFERER"))),ENT_QUOTES,'UTF-8');
      if ($referer!='' and !strstr($referer,"unknown") and !stristr($referer,$_SERVER['SERVER_NAME']))
         sql_query("INSERT INTO ".$NPDS_Prefix."referer VALUES (NULL, '$referer')");
   }

   include("counter.php");

   // include externe file from modules/include for functions, codes ...
   if (file_exists("modules/include/header_after.inc")) include ("modules/include/header_after.inc");
?>
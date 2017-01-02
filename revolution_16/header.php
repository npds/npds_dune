<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2017 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion")) {
   include ("mainfile.php");
   die();
}

function head($tiny_mce_init, $css_pages_ref, $css, $tmp_theme, $js, $m_description,$m_keywords,$skin) {
   global $slogan, $site_font, $Titlesitename, $banners, $Default_Theme, $theme, $gzhandler, $language;
   global $topic, $hlpfile, $user, $hr, $bgcolor1, $bgcolor2, $bgcolor3, $bgcolor4, $bgcolor5, $bgcolor6, $textcolor1, $textcolor2, $long_chain;
   global $bargif, $theme_width, $bloc_width, $page_width, $skin;

   if ($gzhandler==1) {ob_start("ob_gzhandler");}
   include("themes/$tmp_theme/theme.php");

   // Meta
   if (file_exists("meta/meta.php")) {
      $meta_op='';
      include ("meta/meta.php");
   }

   // Favicon
   if (file_exists("themes/$tmp_theme/images/favicon.ico")) {
      $favico="themes/$tmp_theme/images/favicon.ico";
   } else {
      $favico='images/favicon.ico';
   }
   echo '
<link rel="shortcut icon" href="'.$favico.'" type="image/x-icon" />';

   // Syndication RSS & autres
   global $sitename, $nuke_url, $REQUEST_URI;

   // Canonical
   $uri = $REQUEST_URI;
   $drname=dirname($uri);
   if ($drname=='.') {
      $uri=$nuke_url.'/'.$uri;
   } elseif($drname=='/') {
      $uri=$nuke_url.$uri;
   } else {
      $uri='http://'.$_SERVER['SERVER_NAME'].$uri;
   }
   echo '
<link rel="canonical" href="'.str_replace('&','&amp;',str_replace('&amp;','&',$uri)).'" />';

   // humans.txt
   if (file_exists("humans.txt")) {
      echo '
<link type="text/plain" rel="author" href="'.$nuke_url.'/humans.txt" />';
   }

   echo '
<link href="backend.php?op=RSS0.91" title="'.$sitename.' - RSS 0.91" rel="alternate" type="text/xml" />
<link href="backend.php?op=RSS1.0" title="'.$sitename.' - RSS 1.0" rel="alternate" type="text/xml" />
<link href="backend.php?op=RSS2.0" title="'.$sitename.' - RSS 2.0" rel="alternate" type="text/xml" />
<link href="backend.php?op=ATOM" title="'.$sitename.' - ATOM" rel="alternate" type="application/atom+xml" />';
   
//   echo import_css($tmp_theme, $language, $site_font, $css_pages_ref, $css);//move down

   // Tiny_mce
   if ($tiny_mce_init)
      echo aff_editeur("tiny_mce", "begin");

   // include externe JAVASCRIPT file from modules/include or themes/.../include for functions, codes in the <body onload="..." event...
   $body_onloadH="<script type=\"text/javascript\">\n";
   $body_onloadH.="//<![CDATA[\n";
   $body_onloadH.="function init() {\n";
   $body_onloadF="}\n";
   $body_onloadF.="//]]>\n";
   $body_onloadF.="</script>\n";
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
   
      if (isset($user)) {
      global $cookie;
      $skin='';
      if (array_key_exists(11,$cookie)) {$skin=$cookie[11];}
      
   }


   // include externe file from modules/include or themes/.../include for functions, codes ...+ skin motor
   if (file_exists("modules/include/header_head.inc")) {
      ob_start();
      include "modules/include/header_head.inc";
      $hH = ob_get_contents();
      ob_end_clean();
      if($skin!='') {
         $hH=str_replace ('lib/bootstrap/dist/css/bootstrap.min.css','themes/_skins/'.$skin.'/bootstrap.min.css',$hH);
         $hH=str_replace ('lib/bootstrap/dist/css/extra.css','themes/_skins/'.$skin.'/extra.css',$hH);
      }
   echo $hH;
   }
   if (file_exists("themes/$tmp_theme/include/header_head.inc")) {include ("themes/$tmp_theme/include/header_head.inc");}

   echo import_css($tmp_theme, $language, $site_font, $css_pages_ref, $css);

   // Mod by Jireck - Chargeur de JS via PAGES.PHP
   if ($js) {
      if (is_array($js)) {
         foreach ($js as $k=>$tab_js) {
            if (stristr($tab_js, "http://")) {
               echo "<script type=\"text/javascript\" src=\"$tab_js\"></script>\n";
            } else {
               if (file_exists("themes/$tmp_theme/js/$tab_js") and ($tab_js!="")) {
                   echo "<script type=\"text/javascript\" src=\"themes/$tmp_theme/js/$tab_js\"></script>\n";
               } elseif (file_exists("$tab_js") and ($tab_js!="")) {
                   echo "<script type=\"text/javascript\" src=\"$tab_js\"></script>\n";
               }
            }
         }
      } else {
         if (file_exists("themes/$tmp_theme/js/$js")) {
            echo "<script type=\"text/javascript\" src=\"themes/$tmp_theme/js/$js\"></script>\n";
         } elseif (file_exists("$js")) {
            echo "<script type=\"text/javascript\" src=\"$js\"></script>\n";
         }
      }
   }
   
   echo "</head>\n";
   include("themes/$tmp_theme/header.php");
}

   // -----------------------
   $header=1;
   // -----------------------

   // include externe file from modules/include for functions, codes ...
   if (file_exists("modules/include/header_before.inc")) {include ("modules/include/header_before.inc");}

   // take the right theme location !
   global $Default_Theme, $user, $skin;
   if (isset($user)) {
      global $cookie;
      $skin='';
      if ($cookie[9]=='') $cookie[9]=$Default_Theme;
      if (isset($theme)) $cookie[9]=$theme;
      $tmp_theme=$cookie[9];
      if (!$file=@opendir("themes/$cookie[9]")) $tmp_theme=$Default_Theme;
      
      if (array_key_exists(11,$cookie)) {$skin=$cookie[11];}
      
   } else {
      $tmp_theme=$Default_Theme;
   }

   // LOAD pages.php and Go ...
   settype($PAGES, 'array');
   global $pdst, $Titlesitename, $REQUEST_URI;
   require_once("themes/pages.php");

   // import pages.php specif values from theme
   if (file_exists("themes/".$tmp_theme."/pages.php")) {
      include ("themes/".$tmp_theme."/pages.php");
   }

   $page_uri=preg_split("#(&|\?)#",$REQUEST_URI);
   $Npage_uri=count($page_uri);
   $pages_ref=basename($page_uri[0]);

   // Static page and Module can have Bloc, Title ....
   if ($pages_ref=="static.php") {
      $pages_ref=substr($REQUEST_URI,strpos($REQUEST_URI,"static.php"));
   }
   if ($pages_ref=="modules.php") {
      if (isset($PAGES["modules.php?ModPath=$ModPath&ModStart=$ModStart*"]['title'])) {
         $pages_ref="modules.php?ModPath=$ModPath&ModStart=$ModStart*";
      } else
         $pages_ref=substr($REQUEST_URI,strpos($REQUEST_URI,"modules.php"));
   }

   // Admin function can have all the PAGES attributs except Title
   if ($pages_ref=="admin.php") {
      if (array_key_exists(1,$page_uri)) {
         if (array_key_exists($pages_ref."?".$page_uri[1],$PAGES)) {
            if (array_key_exists('title',$PAGES[$pages_ref."?".$page_uri[1]])) {
               $pages_ref.="?".$page_uri[1];
            }
         }
      }
   }
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
      if (array_key_exists('blocs',$PAGES[$pages_ref])) {
         $pdst=$PAGES[$pages_ref]['blocs'];
      }

      // block execution of page with run attribute = no
      if ($PAGES[$pages_ref]['run']=="no") {
         if ($pages_ref=="index.php") {
            $Titlesitename="NPDS";
            if (file_exists("meta/meta.php"))
               include("meta/meta.php");
            if (file_exists("static/webclosed.txt"))
               include("static/webclosed.txt");
            die();
         } else {
            header("location: index.php");
         }
      // run script to another 'location'
      } elseif (($PAGES[$pages_ref]['run']!="yes") and (($PAGES[$pages_ref]['run']!=""))) {
         header("location: ".$PAGES[$pages_ref]['run']);
      }

      // Assure la gestion des titres ALTERNATIFS
      $tab_page_ref=explode("|",$PAGES[$pages_ref]['title']);
      if (count($tab_page_ref)>1) {
         if (strlen($tab_page_ref[1])>1) {
            $PAGES[$pages_ref]['title']=$tab_page_ref[1];
         } else {
            $PAGES[$pages_ref]['title']=$tab_page_ref[0];
         }
         $PAGES[$pages_ref]['title']=strip_tags($PAGES[$pages_ref]['title']);
      }
      $fin_title=substr($PAGES[$pages_ref]['title'],-1);
      $TitlesitenameX=aff_langue(substr($PAGES[$pages_ref]['title'],0,strlen($PAGES[$pages_ref]['title'])-1));
      if ($fin_title=="+") {
         $Titlesitename=$TitlesitenameX." - ".$Titlesitename;
      } else if ($fin_title=="-") {
         $Titlesitename=$TitlesitenameX;
      }
      if ($Titlesitename=="") {$Titlesitename=$sitename;}
      // globalisation de la variable title pour marquetapage mais protection pour la zone admin
      if ($pages_ref!="admin.php")
         global $title;
      if (!$title) {
         if ($fin_title=="+" or $fin_title=="-")
            $title=$TitlesitenameX;
         else
            $title=aff_langue(substr($PAGES[$pages_ref]['title'],0,strlen($PAGES[$pages_ref]['title'])));
      } else
         $title=removeHack($title);
   
      // meta description
      settype($m_description, 'string');
      if (array_key_exists('meta-description',$PAGES[$pages_ref]) and ($m_description=='')) {
         $m_description=aff_langue($PAGES[$pages_ref]['meta-description']);
      }
      // meta keywords
      settype($m_keywords, 'string');
      if (array_key_exists('meta-keywords',$PAGES[$pages_ref]) and ($m_keywords=='')) {
         $m_keywords=aff_langue($PAGES[$pages_ref]['meta-keywords']);
      }
   }

   // Initialisation de TinyMce
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
            $tiny_mce=false;
         }
      } else {
         $tiny_mce_init=false;
         $tiny_mce=false;
      }
   } else {
      $tiny_mce_init=false;
   }

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
      } else {
         $js='';
      }
   } else {
      $js='';
   }

   head($tiny_mce_init, $css_pages_ref, $css, $tmp_theme, $js, $m_description,$m_keywords,$skin);
   include("counter.php");

   // include externe file from modules/include for functions, codes ...
   if (file_exists("modules/include/header_after.inc")) {include ("modules/include/header_after.inc");}
?>
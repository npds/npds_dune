<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Kill the Ereg by JPB on 24-01-2011                                   */
/* This version name NPDS Copyright (c) 2001-2018 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

function GetMetaTags($filename) {
   if (file_exists($filename)) {
      $temp = file($filename);
      while($line = each($temp)) {
         $aline = trim(stripslashes($line['value']));
         if (preg_match("#<meta (name|http-equiv)=\"([^\"]*)\" content=\"([^\"]*)\"#i",$aline,$regs)) {
            $regs[2] = strtolower($regs[2]);
            $tags[$regs[2]] = $regs[3];
         }
      }
   }
   return $tags;
}

function MetaTagMakeSingleTag($name, $content, $type='name') {
   if ($content!="humans.txt") {
      if ($content!="")
         return "\$l_meta.=\"<meta $type=\\\"".$name."\\\" content=\\\"".$content."\\\" />\\n\";\n";
      else
         return "\$l_meta.=\"<meta $type=\\\"".$name."\\\" />\\n\";\n";
   } else
      return "\$l_meta.=\"<link type=\"text/plain\" rel=\"author\" href=\"http://humanstxt.org/humans.txt\" />\";\n";

}

function MetaTagSave($filename, $tags) {
   if (!is_array($tags)) { return false; }
   global $adminmail, $Version_Id, $Version_Num, $Version_Sub;
   $fh = fopen($filename, "w");
   if ($fh) {
      $content = "<?php\n/* Do not change anything in this file manually. Use the administration interface*/\n";
      $content.= "settype(\$meta_doctype,'string');\n";
      $content.= "settype(\$nuke_url,'string');\n";
      $content.= "settype(\$meta_op,'string');\n";
      $content.= "settype(\$m_description,'string');\n";
      $content.= "settype(\$m_keywords,'string');\n";
      $content.= "if (\$meta_doctype==\"\")\n";
      if (!empty($tags['doctype'])) {
         if ($tags['doctype']=="HTML 4.01 Transitional")
            $content .="   \$l_meta=\"<!DOCTYPE HTML PUBLIC \\\"-//W3C//DTD HTML 4.01 Transitional//EN\\\">\\n<html>\\n<head>\\n<title>\\n\\n\";\n";
         if ($tags['doctype']=="HTML 4.01 Strict")
            $content .="   \$l_meta=\"<!DOCTYPE HTML PUBLIC \\\"-//W3C//DTD HTML 4.01//EN\\\" \\\"http://www.w3.org/TR/html4/strict.dtd\\\">\\n<html>\\n<head>\\n<title>\\n\\n\";\n";
         if ($tags['doctype']=="XHTML 1.0 Transitional")
            $content .="   \$l_meta=\"<!DOCTYPE html PUBLIC \\\"-//W3C//DTD XHTML 1.0 Transitional//EN\\\" \\\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\\\">\\n<html xmlns=\\\"http://www.w3.org/1999/xhtml\\\">\\n<head><title>\\n\\n\";\n";
         if ($tags['doctype']=="XHTML 1.0 Strict")
            $content .="   \$l_meta=\"<!DOCTYPE html PUBLIC \\\"-//W3C//DTD XHTML 1.0 Strict//EN\\\" \\\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\\\">\\n<html xmlns=\\\"http://www.w3.org/1999/xhtml\\\">\\n<head>\\n<title>\\n\\n\";\n";
        if ($tags['doctype']=="HTML 5.0")
            $content .="   \$l_meta=\"<!DOCTYPE html>\\n<html>\\n<head>\\n<title>\\n\\n\";\n";
       } else {
         $tags['doctype']="HTML 4.01 Transitional";
         $content .="   \$l_meta=\"<!DOCTYPE HTML PUBLIC \\\"-//W3C//DTD HTML 4.01 Transitional//EN\\\" \\\"http://www.w3.org/TR/html4/loose.dtd\\\">\\n<html>\\n<head>\\n<title>\\n\\n\";\n";
      }

      $content.="else\n";
      $content.="   \$l_meta=\$meta_doctype.\"\\n<html>\\n<head><title>\\n\\n\";\n";

      if (!empty($tags['content-type'])) {
         $tags['content-type'] = htmlspecialchars(stripslashes($tags['content-type']),ENT_COMPAT|ENT_HTML401,cur_charset);
         $fp = fopen("meta/cur_charset.php", "w");
         if ($fp) {
            fwrite($fp, "<?php\nif (!defined(\"cur_charset\"))\n   define ('cur_charset', \"".substr($tags['content-type'],strpos($tags['content-type'],"charset=")+8)."\");\n");
            fwrite($fp, "if (!defined(\"doctype\"))\n   define ('doctype', \"".$tags['doctype']."\");\n?>");
         }
         fclose($fp);
         if ($tags['doctype']=="HTML 5.0") {
            $content .= MetaTagMakeSingleTag('content-type', 'text/html', 'http-equiv');
            $content .= MetaTagMakeSingleTag('utf-8', '', 'charset');
         } else
            $content .= MetaTagMakeSingleTag('content-type', $tags['content-type'], 'http-equiv');
      } else {
         $fp = fopen("meta/cur_charset.php", "w");
         if ($fp) {
            fwrite($fp, "<?php\nif (!defined(\"cur_charset\"))\n   define ('cur_charset', \"iso-8859-1\");\n");
            fwrite($fp, "if (!defined(\"doctype\"))\n   define ('doctype', \"".$tags['doctype']."\");\n?>");
         }
         fclose($fp);
         $content .= MetaTagMakeSingleTag('content-type', "text/html; charset=iso-8859-1", 'http-equiv');
      }
      
      $content .= MetaTagMakeSingleTag('viewport', 'width=device-width, initial-scale=1, shrink-to-fit=no');//to do =>put in admin interface
      $content .= MetaTagMakeSingleTag('X-UA-Compatible', 'IE=edge', 'http-equiv');//to do =>put in admin interface
      $content .= MetaTagMakeSingleTag('content-script-type', 'text/javascript', 'http-equiv');
      $content .= MetaTagMakeSingleTag('content-style-type', 'text/css', 'http-equiv');
      $content .= MetaTagMakeSingleTag('expires', '0', 'http-equiv');
      $content .= MetaTagMakeSingleTag('pragma', 'no-cache', 'http-equiv');
      $content .= MetaTagMakeSingleTag('cache-control', 'no-cache', 'http-equiv');
      $content .= MetaTagMakeSingleTag('identifier-url', '$nuke_url', 'http-equiv');
      if (!empty($tags['author'])) {
         $tags['author'] = htmlspecialchars(stripslashes($tags['author']),ENT_COMPAT|ENT_HTML401,cur_charset);
         $content .= MetaTagMakeSingleTag('author', $tags['author']);
      }

      if (!empty($tags['owner'])) {
         $tags['owner'] = htmlspecialchars(stripslashes($tags['owner']),ENT_COMPAT|ENT_HTML401,cur_charset);
         $content .= MetaTagMakeSingleTag('owner', $tags['owner']);
      }
      if (!empty($tags['reply-to'])) {
         $tags['reply-to'] = htmlspecialchars(stripslashes($tags['reply-to']),ENT_COMPAT|ENT_HTML401,cur_charset);
         $content .= MetaTagMakeSingleTag('reply-to', $tags['reply-to']);
      } else {
         $content .= MetaTagMakeSingleTag('reply-to', $adminmail);
      }
      if (!empty($tags['language'])) {
         $tags['language'] = htmlspecialchars(stripslashes($tags['language']),ENT_COMPAT|ENT_HTML401,cur_charset);
         $content .= MetaTagMakeSingleTag('language', $tags['language']);
         if ($tags['language'] == "fr") {
            $content .= MetaTagMakeSingleTag('content-language', 'fr, fr-be, fr-ca, fr-lu, fr-ch', 'http-equiv');
         } else {
            $content .= MetaTagMakeSingleTag('content-language', $tags['language'], 'http-equiv');
         }
      }
      if (!empty($tags['description'])) {
         $tags['description'] = htmlspecialchars(stripslashes($tags['description']),ENT_COMPAT|ENT_HTML401,cur_charset);
         $content .="if (\$m_description!=\"\")\n";
         $content .="   \$l_meta.=\"<meta name=\\\"description\\\" content=\\\"\$m_description\\\" />\\n\";\n";
         $content .="else\n";
         $content .= "   ".MetaTagMakeSingleTag('description', $tags['description']);
      }
      if (!empty($tags['keywords'])) {
         $tags['keywords'] = htmlspecialchars(stripslashes($tags['keywords']),ENT_COMPAT|ENT_HTML401,cur_charset);
         $content .="if (\$m_keywords!=\"\")\n";
         $content .="   \$l_meta.=\"<meta name=\\\"keywords\\\" content=\\\"\$m_keywords\\\" />\\n\";\n";
         $content .="else\n";
         $content .= "   ".MetaTagMakeSingleTag('keywords', $tags['keywords']);
      }
      if (!empty($tags['rating'])) {
         $tags['rating'] = htmlspecialchars(stripslashes($tags['rating']),ENT_COMPAT|ENT_HTML401,cur_charset);
         $content .= MetaTagMakeSingleTag('rating', $tags['rating']);
      }
      if (!empty($tags['distribution'])) {
         $tags['distribution'] = htmlspecialchars(stripslashes($tags['distribution']),ENT_COMPAT|ENT_HTML401,cur_charset);
         $content .= MetaTagMakeSingleTag('distribution', $tags['distribution']);
      }
      if (!empty($tags['copyright'])) {
         $tags['copyright'] = htmlspecialchars(stripslashes($tags['copyright']),ENT_COMPAT|ENT_HTML401,cur_charset);
         $content .= MetaTagMakeSingleTag('copyright', $tags['copyright']);
      }
      if (!empty($tags['revisit-after'])) {
         $tags['revisit-after'] = htmlspecialchars(stripslashes($tags['revisit-after']),ENT_COMPAT|ENT_HTML401,cur_charset);
         $content .= MetaTagMakeSingleTag('revisit-after', $tags['revisit-after']);
      } else {
         $content .= MetaTagMakeSingleTag('revisit-after', "14 days");
      }
      $content .= MetaTagMakeSingleTag('resource-type', "document");
      $content .= MetaTagMakeSingleTag('robots', $tags['robots']);
      $content .= MetaTagMakeSingleTag('generator', "$Version_Id $Version_Num $Version_Sub");

      $content .= "\$l_meta=str_replace(\"<title>\",\"<title>\$Titlesitename</title>\",\$l_meta);\n";
      $content .= "if (\$meta_op==\"\") echo \$l_meta; else \$l_meta=str_replace(\"\\n\",\"\",str_replace(\"\\\"\",\"'\",\$l_meta));\n?>";
      fwrite($fh, $content);
      fclose($fh);
      global $aid; Ecr_Log('security', "MetaTagsave() by AID : $aid", '');
      return true;
   }
   return false;
}

function ConfigSave($xparse,$xsitename,$xnuke_url,$xsite_logo,$xslogan,$xstartdate,$xadminmail,$xtop,$xstoryhome,$xoldnum,$xultramode,$xanonpost,$xDefault_Theme,$xbanners,$xmyIP,$xfoot1,$xfoot2,$xfoot3,$xfoot4,$xbackend_title,$xbackend_language,$xbackend_image,$xbackend_width,$xbackend_height,$xlanguage,$xlocale,$xperpage,$xpopular,$xnewlinks,$xtoplinks,$xlinksresults,$xlinks_anonaddlinklock,$xnotify,$xnotify_email,$xnotify_subject,$xnotify_message,$xnotify_from,$xmoderate,$xanonymous,$xmaxOptions,$xsetCookies,$xtipath,$xuserimg,$xadminimg,$xadmingraphic,$xsite_font,$xadmart,$xminpass,$xhttpref,$xhttprefmax,$xpollcomm,$xlinkmainlogo,$xstart_page,$xsmilies,$xOnCatNewLink,$xEmailFooter,$xshort_user,$xgzhandler,$xrss_host_verif,$xcache_verif,$xmember_list,$xdownload_cat,$xmod_admin_news,$xgmt,$xAutoRegUser,$xTitlesitename,$xfilemanager,$xshort_review,$xnot_admin_count,$xadmin_cook_duration,$xuser_cook_duration,$xtroll_limit,$xsubscribe,$xCloseRegUser,$xshort_menu_admin,$xmail_fonction,$xmemberpass,$xshow_user,$xdns_verif,$xmember_invisible,$xavatar_size,$xlever,$xcoucher,$xmulti_langue,$xadmf_ext,$xsavemysql_size,$xsavemysql_mode,$xtiny_mce,$xnpds_twi,$xnpds_fcb) {

    include ("config.php");
    if ($xparse==0) {
       $xsitename =  FixQuotes($xsitename);
       $xTitlesitename = FixQuotes($xTitlesitename);
    } else {
       $xsitename =  stripslashes($xsitename);
       $xTitlesitename = stripslashes($xTitlesitename);
    }

    $xnuke_url = FixQuotes($xnuke_url);
    $xsite_logo = FixQuotes($xsite_logo);

    if ($xparse==0) {
       $xslogan = FixQuotes($xslogan);
       $xstartdate = FixQuotes($xstartdate);
    } else {
       $xslogan = stripslashes($xslogan);
       $xstartdate = stripslashes($xstartdate);
    }
    // Theme
    $xDefault_Theme = FixQuotes($xDefault_Theme);
    if ($xDefault_Theme!=$Default_Theme) {
       include("cache.config.php");
       $dh = opendir($CACHE_CONFIG['data_dir']);
       while(false!==($filename = readdir($dh))) {
          if ($filename === '.' OR $filename === '..' OR $filename === 'ultramode.txt' OR $filename === 'net2zone.txt' OR $filename === 'sql') continue;
             unlink($CACHE_CONFIG['data_dir'].$filename);
       }
    }
    $xmyIP = FixQuotes($xmyIP);

    $xfoot1=str_replace(chr(13).chr(10),"\n",$xfoot1);
    $xfoot2=str_replace(chr(13).chr(10),"\n",$xfoot2);
    $xfoot3=str_replace(chr(13).chr(10),"\n",$xfoot3);
    $xfoot4=str_replace(chr(13).chr(10),"\n",$xfoot4);

    if ($xparse==0) {
       $xbackend_title = FixQuotes($xbackend_title);
    } else {
       $xbackend_title = stripslashes($xbackend_title);
    }

    $xbackend_language = FixQuotes($xbackend_language);
    $xbackend_image = FixQuotes($xbackend_image);
    $xbackend_width = FixQuotes($xbackend_width);
    $xbackend_height = FixQuotes($xbackend_height);
    $xlanguage = FixQuotes($xlanguage);
    $xlocale = FixQuotes($xlocale);
    $xnotify_email = FixQuotes($xnotify_email);

    if ($xparse==0) {
       $xnotify_subject = FixQuotes($xnotify_subject);
       $xdownload_cat = FixQuotes($xdownload_cat);
    } else {
       $xnotify_subject = stripslashes($xnotify_subject);
       $xdownload_cat = stripslashes($xdownload_cat);
    }
    $xnotify_message=str_replace(chr(13).chr(10),"\n",$xnotify_message);

    $xnotify_from = FixQuotes($xnotify_from);
    $xanonymous = FixQuotes($xanonymous);
    $xtipath = FixQuotes($xtipath);
    $xuserimg = FixQuotes($xuserimg);
    $xadminimg = FixQuotes($xadminimg);
    $xsite_font = FixQuotes($xsite_font);
    $file = fopen("config.php", "w");
    $line = "######################################################################\n";
    $content = "<?php\n";
    $content .= "$line";
    $content .= "# DUNE by NPDS : Net Portal Dynamic System\n";
    $content .= "# ===================================================\n";
    $content .= "#\n";
    $content .= "# This version name NPDS Copyright (c) 2001-2018 by Philippe Brunier\n";
    $content .= "#\n";
    $content .= "# This module is to configure the main options for your site\n";
    $content .= "#\n";
    $content .= "# This program is free software. You can redistribute it and/or modify\n";
    $content .= "# it under the terms of the GNU General Public License as published by\n";
    $content .= "# the Free Software Foundation; either version 2 of the License.\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "$line";
    $content .= "# ========================\n";
    $content .= "# Database & System Config\n";
    $content .= "# ========================\n";
    $content .= "# dbhost:      MySQL Database Hostname\n";
    $content .= "# dbuname:     MySQL Username\n";
    $content .= "# dbpass:      MySQL Password\n";
    $content .= "# dbname:      MySQL Database Name\n";
    $content .= "# mysql_p:     Persistent connection to MySQL Server (1) or Not (0)\n";
    $content .= "# mysql_i:     Use MySQLi (1) instead of MySQL interface (0)\n";
    $content .= "# =======================\n";
    $content .= "# system:      0 for Unix/Linux, 1 for Windows\n";
    $content .= "# system_md5:  0 for NOT USED Authors' Password encryption / 1 for USED Author's Password Encryption\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "\$dbhost = \"$dbhost\";\n";
    $content .= "\$dbuname = \"$dbuname\";\n";
    $content .= "\$dbpass = \"$dbpass\";\n";
    $content .= "\$dbname = \"$dbname\";\n";
    if (!isset($mysql_p)) {$mysql_p=1;}
    $content .= "\$mysql_p = $mysql_p;\n";
    if (!isset($mysql_i)) {$mysql_i=0;}
    $content .= "\$mysql_i = $mysql_i;\n";
    $content .= "# =======================\n";
    $content .= "\$system = $system;\n";
    if (!$system_md5) {$system_md5=0;}
    $content .= "\$system_md5 = $system_md5;\n";
    $content .= "\n";
    $content .= "/*********************************************************************/\n";
    $content .= "/* You finished to configure the Database. Now you can change all    */\n";
    $content .= "/* you want in the Administration Section.   To enter just launch    */\n";
    $content .= "/* you web browser pointing to http://yourdomain.com/admin.php       */\n";
    $content .= "/*                                                                   */\n";
    $content .= "/* At the prompt use the following ID to login (case sensitive):     */\n";
    $content .= "/*                                                                   */\n";
    $content .= "/* AdminID: Root                                                     */\n";
    $content .= "/* Password: Password                                                */\n";
    $content .= "/*                                                                   */\n";
    $content .= "/* Be sure to change inmediately the Root login & password clicking  */\n";
    $content .= "/* on Edit Admin in the Admin menu. After that, click on Preferences */\n";
    $content .= "/* to configure your new site. In that menu you can change all you   */\n";
    $content .= "/* need to change.                                                   */\n";
    $content .= "/*                                                                   */\n";
    $content .= "/*********************************************************************/\n";
    $content .= "\n\n";
    $content .= "\n";
    $content .= "$line";
    $content .= "# General Site Configuration\n";
    $content .= "#\n";
    $content .= "# \$parse:          Select the parse function you want to use for preference\n";
    $content .= "# \$gzhandler:      PHP > 5.x : default 0 / PHP < 5.x sending compressed html with zlib : 1 - be careful\n";
    $content .= "# \$admin_cook_duration : Duration in hour for Admin cookie (default 24)\n";
    $content .= "# \$user_cook_duration: Duration in hour for Admin cookie (default 24)\n";
    $content .= "# \$sitename:       Your Site Name\n";
    $content .= "# \$Titlesitename:  Your Site Phrase for the Title (html Title Tag) off the HTML Page\n";
    $content .= "# \$nuke_url:       Complete URL for your site (Do not put / at end)\n";
    $content .= "# \$site_logo:      Logo for Printer Friendly Page (It's good to have a Black/White graphic)\n";
    $content .= "# \$slogan:         Your site's slogan\n";
    $content .= "# \$startdate:      Start Date to display in Statistic Page\n";
    $content .= "# \$moderate:       Moderation of comments\n";
    $content .= "# \$anonpost:       Allow Anonymous to Post Comments? (1=Yes 0=No)\n";
    $content .= "# \$troll_limit:    Maximum Number off Comments per user (24H)\n";
    $content .= "# \$mod_admin_news  Allow only Moderator and Admin to Post News? (1=Yes 0=No)\n";
    $content .= "# \$not_admin_count Don't record Admin's Hits in stats (1=Yes=>don't rec 0=No=>rec)\n";
    $content .= "# \$Default_Theme:  Default Theme for your site (See /themes directory for the complete list, case sensitive!)\n";
    $content .= "# \$Start_Page:     Default Page for your site (default : index.php but you can use : topics.php, links.php ...)\n";
    $content .= "# \$foot(x):        Messages for all footer pages (Can include HTML code)\n";
    $content .= "# \$anonymous:      Anonymous users Default Name\n";
    $content .= "# \$site_font:      Font for your entire site (Comma separated for many fonts type)\n";
    $content .= "# \$minpass:        Minimum character for users passwords\n";
    $content .= "# \$show_user:      Number off user showed in memberslist page\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "\$parse = \"$xparse\";\n";
    $content .= "\$gzhandler = \"$xgzhandler\";\n";
    $content .= "\$admin_cook_duration = \"$xadmin_cook_duration\";\n";
    $content .= "\$user_cook_duration = \"$xuser_cook_duration\";\n";
    $content .= "\$sitename = \"$xsitename\";\n";
    $content .= "\$Titlesitename = \"$xTitlesitename\";\n";
    $content .= "\$nuke_url = \"$xnuke_url\";\n";
    $content .= "\$site_logo = \"$xsite_logo\";\n";
    $content .= "\$slogan = \"$xslogan\";\n";
    $content .= "\$startdate = \"$xstartdate\";\n";
    $content .= "\$anonpost = $xanonpost;\n";
    if (!$xtroll_limit) {$xtroll_limit=6;}
       $content .= "\$troll_limit = $xtroll_limit;\n";
    $content .= "\$moderate = $xmoderate;\n";
    $content .= "\$mod_admin_news = $xmod_admin_news;\n";
    $content .= "\$not_admin_count = $xnot_admin_count;\n";
    $content .= "\$Default_Theme = \"$xDefault_Theme\";\n";
    $content .= "\$Start_Page = \"$xstart_page\";\n";
    $content .= "\$foot1 = \"$xfoot1\";\n";
    $content .= "\$foot2 = \"$xfoot2\";\n";
    $content .= "\$foot3 = \"$xfoot3\";\n";
    $content .= "\$foot4 = \"$xfoot4\";\n";
    $content .= "\$anonymous = \"$xanonymous\";\n";
    $content .= "\$site_font = \"$xsite_font\";\n";
    $content .= "\$minpass = $xminpass;\n";
    $content .= "\$show_user = $xshow_user;\n";
    $content .= "\n";
    $content .= "$line";
    $content .= "# General Stories Options\n";
    $content .= "#\n";
    $content .= "# \$top:       How many items in Top Page?\n";
    $content .= "# \$storyhome: How many stories to display in Home Page?\n";
    $content .= "# \$oldnum:    How many stories in Old Articles Box?\n";
    $content .= "$line";
    $content .= "\n";
    if (!$xtop) {$xtop=10;}
       $content .= "\$top = $xtop;\n";
    if (!$xstoryhome) {$xstoryhome=10;}
       $content .= "\$storyhome = $xstoryhome;\n";
    if (!$xoldnum) {$xoldnum=10;}
       $content .= "\$oldnum = $xoldnum;\n";
    $content .= "\n";
    $content .= "$line";
    $content .= "# Banners/Advertising Configuration\n";
    $content .= "#\n";
    $content .= "# \$banners: Activate Banners Ads for your site? (1=Yes 0=No)\n";
    $content .= "# \$myIP:    Write your IP number to not count impressions, be fair about this!\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "\$banners = $xbanners;\n";
    $content .= "\$myIP = \"$xmyIP\";\n";
    $content .= "\n";
    $content .= "$line";
    $content .= "# XML/RDF Backend Configuration & Social Networks\n";
    $content .= "#\n";
    $content .= "# \$backend_title:    Backend title, can be your site's name and slogan\n";
    $content .= "# \$backend_language: Language format of your site\n";
    $content .= "# \$backend_image:    Image logo for your site\n";
    $content .= "# \$backend_width:    Image logo width\n";
    $content .= "# \$backend_height:   Image logo height\n";
    $content .= "# \$ultramode:        Activate ultramode plain text and XML files backend syndication? (1=Yes 0=No). locate in /cache directory\n";
    $content .= "# \$npds_twi:         Activate the Twitter syndication? (1=Yes 0=No).\n";
    $content .= "# \$npds_fcb:         Activate the Facebook syndication? (1=Yes 0=No).\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "\$backend_title = \"$xbackend_title\";\n";
    $content .= "\$backend_language = \"$xbackend_language\";\n";
    $content .= "\$backend_image = \"$xbackend_image\";\n";
    $content .= "\$backend_width = \"$xbackend_width\";\n";
    $content .= "\$backend_height = \"$xbackend_height\";\n";
    $content .= "\$ultramode = $xultramode;\n";
    if (!$xnpds_twi) {$xnpds_twi=0;}
    $content .= "\$npds_twi = $xnpds_twi;\n";
    if (!$xnpds_fcb) {$xnpds_fcb=0;}
    $content .= "\$npds_fcb = $xnpds_fcb;\n";
    $content .= "\n";
    $content .= "$line";
    $content .= "# Site Language Preferences\n";
    $content .= "#\n";
    $content .= "# \$language:     Language of your site (You need to have lang-xxxxxx.php file for your selected language in the /language directory of your site)\n";
    $content .= "# \$locale:       Locale configuration to correctly display date with your country format. (See /usr/share/locale)\n";
    $content .= "# \$gmt:          Locale configuration to correctly display date with your GMT offset.\n";
    $content .= "# \$lever:        HH:MM where Day become.\n";
    $content .= "# \$coucher:      HH:MM where Night become.\n";
    $content .= "# \$multi_langue: Activate Multi-langue NPDS'capability.\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "\$language = \"$xlanguage\";\n";
    $content .= "\$multi_langue = $xmulti_langue;\n";
    $content .= "\$locale = \"$xlocale\";\n";
    $content .= "\$gmt = \"$xgmt\";\n";
    $content .= "\$lever = \"$xlever\";\n";
    $content .= "\$coucher = \"$xcoucher\";\n";
    $content .= "\n";
    $content .= "$line";
    $content .= "# Web Links Preferences\n";
    $content .= "#\n";
    $content .= "# \$perpage:                  How many links to show on each page?\n";
    $content .= "# \$popular:                  How many hits need a link to be listed as popular?\n";
    $content .= "# \$newlinks:                 How many links to display in the New Links Page?\n";
    $content .= "# \$toplinks:                 How many links to display in The Best Links Page? (Most Popular)\n";
    $content .= "# \$linksresults:             How many links to display on each search result page?\n";
    $content .= "# \$links_anonaddlinklock:    Is Anonymous autorise to post new links? (0=Yes 1=No)\n";
    $content .= "# \$linkmainlogo:             Activate Logo on Main web Links Page (1=Yes 0=No)\n";
    $content .= "# \$OnCatNewLink:             Activate Icon for New Categorie on Main web Links Page (1=Yes 0=No)\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "\$perpage = $xperpage;\n";
    $content .= "\$popular = $xpopular;\n";
    $content .= "\$newlinks = $xnewlinks;\n";
    $content .= "\$toplinks = $xtoplinks;\n";
    $content .= "\$linksresults = $xlinksresults;\n";
    $content .= "\$links_anonaddlinklock = $xlinks_anonaddlinklock;\n";
    $content .= "\$linkmainlogo = $xlinkmainlogo;\n";
    $content .= "\$OnCatNewLink = $xOnCatNewLink;\n";
    $content .= "\n";
    $content .= "$line";
    $content .= "# Function Mail and Notification of News Submissions\n";
    $content .= "#\n";
    $content .= "# \$adminmail:      Site Administrator's Email\n";
    $content .= "# \$mail_fonction:  What Mail function to be used (1=mail, 2=email)\n";
    $content .= "# \$notify:         Notify you each time your site receives a news submission? (1=Yes 0=No)\n";
    $content .= "# \$notify_email:   Email, address to send the notification\n";
    $content .= "# \$notify_subject: Email subject\n";
    $content .= "# \$notify_message: Email body, message\n";
    $content .= "# \$notify_from:    account name to appear in From field of the Email\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "\$adminmail = \"$xadminmail\";\n";
    $content .= "\$mail_fonction = \"$xmail_fonction\";\n";
    $content .= "\$notify = $xnotify;\n";
    $content .= "\$notify_email = \"$xnotify_email\";\n";
    $content .= "\$notify_subject = \"$xnotify_subject\";\n";
    $content .= "\$notify_message = \"$xnotify_message\";\n";
    $content .= "\$notify_from = \"$xnotify_from\";\n";
    $content .= "\n";
    $content .= "$line";
    $content .= "# Survey/Polls Config\n";
    $content .= "#\n";
    $content .= "# \$maxOptions: Number of maximum options for each poll\n";
    $content .= "# \$setCookies: Set cookies to prevent visitors vote twice in a period of 24 hours? (0=Yes 1=No)\n";
    $content .= "# \$pollcomm:   Activate comments in Polls? (1=Yes 0=No)\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "\$maxOptions = $xmaxOptions;\n";
    $content .= "\$setCookies = $xsetCookies;\n";
    $content .= "\$pollcomm = $xpollcomm;\n";
    $content .= "\n";
    $content .= "$line";
    $content .= "# Some Graphics Options\n";
    $content .= "#\n";
    $content .= "# \$tipath:       Topics images path (put / only at the end, not at the begining)\n";
    $content .= "# \$userimg:      User images path (put / only at the end, not at the begining)\n";
    $content .= "# \$adminimg:     Administration system images path (put / only at the end, not at the begining)\n";
    $content .= "# \$admingraphic: Activate graphic menu for Administration Menu? (1=Yes 0=No)\n";
    $content .= "# \$short_menu_admin: Activate short Administration Menu? (1=Yes 0=No)\n";
    $content .= "# \$admf_ext:     Image Files'extesion for admin menu (default: gif)\n";
    $content .= "# \$admart:       How many articles to show in the admin section?\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "\$tipath = \"$xtipath\";\n";
    $content .= "\$userimg = \"$xuserimg\";\n";
    $content .= "\$adminimg = \"$xadminimg\";\n";
    $content .= "\$short_menu_admin = $xshort_menu_admin;\n";
    $content .= "\$admingraphic = $xadmingraphic;\n";
    if (!$xadmf_ext) {$xadmf_ext="gif";}
    $content .= "\$admf_ext = \"$xadmf_ext\";\n";
    $content .= "\$admart = $xadmart;\n";
    $content .= "\n";
    $content .= "$line";
    $content .= "# HTTP Referers Options\n";
    $content .= "#\n";
    $content .= "# \$httpref:    Activate HTTP referer logs to know who is linking to our site? (1=Yes 0=No)";
    $content .= "# \$httprefmax: Maximum number of HTTP referers to store in the Database (Try to not set this to a high number, 500 ~ 1000 is Ok)\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "\$httpref = $xhttpref;\n";
    $content .= "\$httprefmax = $xhttprefmax;\n";
    $content .= "\n";
    $content .= "$line";
    $content .= "# Miscelaneous Options\n";
    $content .= "#\n";
    $content .= "# \$smilies:          Activate Avatar? (1=Yes 0=No)\n";
    $content .= "# \$avatar_size:      Maximum size for uploaded avatars in pixel (width*height) \n";
    $content .= "# \$short_user:       Activate Short User registration (without ICQ, MSN, ...)? (1=Yes 0=No)\n";
    $content .= "# \$member_list:      Make the members List Private (only for members) or Public (Private=Yes Public=No)\n";
    $content .= "# \$download_cat:     Witch category do you want to show first in download section?\n";
    $content .= "# \$AutoRegUser:      Allow automated new-user creation (sending email and allowed connection)\n";
    $content .= "# \$short_review:     For transform reviews like \"gold book\" (1=Yes, 0=no)\n";
    $content .= "# \$subscribe:        Allow your members to subscribe to topics, ... (1=Yes, 0=no)\n";
    $content .= "# \$member_invisible: Allow members to hide from other members, ... (1=Yes, 0=no)\n";
    $content .= "# \$CloseRegUser:     Allow you to close New Member Registration (from Gawax Idea), ... (1=Yes, 0=no)\n";
    $content .= "# \$memberpass:       Allow user to choose alone the password (1=Yes, 0=no)\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "\$smilies = $xsmilies;\n";
    $content .= "\$avatar_size = \"$xavatar_size\";\n";
    $content .= "\$short_user = $xshort_user;\n";
    $content .= "\$member_list = $xmember_list;\n";
    $content .= "\$download_cat = \"$xdownload_cat\";\n";
    $content .= "\$AutoRegUser = $xAutoRegUser;\n";
    $content .= "\$short_review = $xshort_review;\n";
    $content .= "\$subscribe = $xsubscribe;\n";
    $content .= "\$member_invisible = $xmember_invisible;\n";
    $content .= "\$CloseRegUser = $xCloseRegUser;\n";
    $content .= "\$memberpass = $xmemberpass;\n";
    $content .= "\n";
    $content .= "$line";
    $content .= "# HTTP Miscelaneous Options\n";
    $content .= "#\n";
    $content .= "# \$rss_host_verif: Activate the validation of the existance of a web on Port 80 for Headlines (true=Yes false=No)\n";
    $content .= "# \$cache_verif:    Activate the Advance Caching Meta Tag (pragma ...) (true=Yes false=No)\n";
    $content .= "# \$dns_verif:      Activate the DNS resolution for posts (forum ...), IP-Ban, ... (true=Yes false=No)\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "\$rss_host_verif = $xrss_host_verif;\n";
    $content .= "\$cache_verif = $xcache_verif;\n";
    $content .= "\$dns_verif = $xdns_verif;\n";
    $content .= "\n";
    $content .= "$line";
    $content .= "# SYSTEM Miscelaneous Options\n";
    $content .= "#\n";
    $content .= "# \$savemysql_size:  Determine the maximum size for one file in the SaveMysql process\n";
    $content .= "# \$savemysql_mode:  Type of Myql process (1, 2 or 3)\n";
    $content .= "# \$tiny_mce:        true=Yes or false=No to use tiny_mce Editor or NO Editor\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "\$savemysql_size = $xsavemysql_size;\n";
    $content .= "\$savemysql_mode = $xsavemysql_mode;\n";
    $content .= "\$tiny_mce = $xtiny_mce;\n";
    $content .= "\n";
    $content .= "$line";
    $content .= "# Do not touch the following options !\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "\$NPDS_Prefix = \"$NPDS_Prefix\";\n";
    if ($NPDS_Key=="") {$NPDS_Key=uniqid("");}
    $content .= "\$NPDS_Key = \"$NPDS_Key\";\n";
    $content .= "\$Version_Num = \"v.16.0.3-beta\";\n";
    $content .= "\$Version_Id = \"NPDS\";\n";
    $content .= "\$Version_Sub = \"REvolution\";\n";
    $content .= "\n";
    $content .= "?>";
    fwrite($file, $content);
    fclose($file);

    $file = fopen("filemanager.conf", "w");
    $content = "<?php\n";
    $content .= "# ========================================\n";
    $content .= "# DUNE by NPDS : Net Portal Dynamic System\n";
    $content .= "# ========================================\n";
    $content .= "\$filemanager= $xfilemanager;\n";
    $content .= "?>";
    fwrite($file, $content);
    fclose($file);

    $xEmailFooter=str_replace(chr(13).chr(10),"\n",$xEmailFooter);
    $file = fopen("signat.php", "w");
    $content = "<?php\n";
    $content .= "$line";
    $content .= "# DUNE by NPDS : Net Portal Dynamic System\n";
    $content .= "# ===================================================\n";
    $content .= "#\n";
    $content .= "# This version name NPDS Copyright (c) 2001-2018 by Philippe Brunier\n";
    $content .= "#\n";
    $content .= "# This module is to configure Footer of Email send By NPDS\n";
    $content .= "#\n";
    $content .= "# This program is free software. You can redistribute it and/or modify\n";
    $content .= "# it under the terms of the GNU General Public License as published by\n";
    $content .= "# the Free Software Foundation; either version 2 of the License.\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "\$message .= \"$xEmailFooter\";\n";

    $content .= "?>";
    fwrite($file, $content);
    fclose($file);
    global $aid; Ecr_Log("security", "ConfigSave() by AID : $aid", "");

    SC_Clean();

    Header("Location: admin.php?op=AdminMain");
}
?>
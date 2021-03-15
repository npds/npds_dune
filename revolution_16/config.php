<?php
######################################################################
# DUNE by NPDS : Net Portal Dynamic System
# ===================================================
#
# This version name NPDS Copyright (c) 2001-2020 by Philippe Brunier
#
# This module is to configure the main options for your site
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
######################################################################

######################################################################
# ========================
# Database & System Config
# ========================
# dbhost:      MySQL Database Hostname
# dbuname:     MySQL Username
# dbpass:      MySQL Password
# dbname:      MySQL Database Name
# mysql_p:     Persistent connection to MySQL Server (1) or Not (0)
# mysql_i:     Use MySQLi (1) instead of MySQL interface (0)
# =======================
# system:      0 user password encryption DISABLE, 1 user password encryption ENABLE
# system_md5:  0 admin password encryption DISABLE, 1 admin password encryption ENABLE
######################################################################

$dbhost = "localhost";
$dbuname = "root";
$dbpass = "";
$dbname = "revolution";
$mysql_p = 1;
$mysql_i = 1;
# =======================
$system = 1;
$system_md5 = 1;

/*********************************************************************/
/* You finished to configure the Database. Now you can change all    */
/* you want in the Administration Section.   To enter just launch    */
/* you web browser pointing to http://yourdomain.com/admin.php       */
/*                                                                   */
/* At the prompt use the following ID to login (case sensitive):     */
/*                                                                   */
/* AdminID: Root                                                     */
/* Password: Password                                                */
/*                                                                   */
/* Be sure to change inmediately the Root login & password clicking  */
/* on Edit Admin in the Admin menu. After that, click on Preferences */
/* to configure your new site. In that menu you can change all you   */
/* need to change.                                                   */
/*                                                                   */
/*********************************************************************/



######################################################################
# General Site Configuration
#
# $parse:          Select the parse function you want to use for preference
# $gzhandler:      PHP > 5.x : default 0 / PHP < 5.x sending compressed html with zlib : 1 - be careful
# $admin_cook_duration : Duration in hour for Admin cookie (default 24)
# $user_cook_duration: Duration in hour for Admin cookie (default 24)
# $sitename:       Your Site Name
# $Titlesitename:  Your Site Phrase for the Title (html Title Tag) off the HTML Page
# $nuke_url:       Complete URL for your site (Do not put / at end)
# $site_logo:      Logo for Printer Friendly Page (It's good to have a Black/White graphic)
# $slogan:         Your site's slogan
# $startdate:      Start Date to display in Statistic Page
# $moderate:       Moderation of comments
# $anonpost:       Allow Anonymous to Post Comments? (1=Yes 0=No)
# $troll_limit:    Maximum Number off Comments per user (24H)
# $mod_admin_news  Allow only Moderator and Admin to Post News? (1=Yes 0=No)
# $not_admin_count Don't record Admin's Hits in stats (1=Yes=>don't rec 0=No=>rec)
# $Default_Theme:  Default Theme for your site (See /themes directory for the complete list, case sensitive!)
# $Default_Skin:   Default Skin for Theme ... with skins (See /themes/_skins directory for the complete list, case sensitive!)
# $Start_Page:     Default Page for your site (default : index.php but you can use : topics.php, links.php ...)
# $foot(x):        Messages for all footer pages (Can include HTML code)
# $anonymous:      Anonymous users Default Name
# $site_font:      Font for your entire site (Comma separated for many fonts type)
# $minpass:        Minimum character for users passwords
# $show_user:      Number off user showed in memberslist page
######################################################################

$parse = "1";
$gzhandler = "0";
$admin_cook_duration = "240";
$user_cook_duration = "8000";
$sitename = "NPDS REvolution 16";
$Titlesitename = "NPDS - Gestion de Contenu et de Communaut&eacute; - Open Source";
$nuke_url = "http://localhost";
$site_logo = "images/npds_p.gif";
$slogan = "NPDS REvolution 16";
$startdate = "21/08/2017";
$anonpost = 1;
$troll_limit = 5;
$moderate = 1;
$mod_admin_news = 0;
$not_admin_count = 1;
$Default_Theme = "npds-boost_sk";
$Default_Skin = "default";
$Start_Page = "index.php?op=edito";
$foot1 = "<a href=\"admin.php\" ><i class=\"fa fa-cogs fa-2x mr-3 align-middle\" title=\"Admin\" data-toggle=\"tooltip\"></i></a>
 <a href=\"https://www.mozilla.org/fr/\" target=\"_blank\"><i class=\"fab fa-firefox fa-2x  mr-1 align-middle\"  title=\"get Firefox\" data-toggle=\"tooltip\"></i></a>
 <a href=\"static.php?op=charte.html&amp;npds=0&amp;metalang=1\">Charte</a> 
 - <a href=\"modules.php?ModPath=contact&amp;ModStart=contact\" class=\"mr-3\">Contact</a> 
 <a href=\"backend.php\" target=\"_blank\" ><i class=\"fa fa-rss fa-2x  mr-3 align-middle\" title=\"RSS 1.0\" data-toggle=\"tooltip\"></i></a>&nbsp;
<a href=\"https://github.com/npds/npds_dune\" target=\"_blank\"><i class=\"fab fa-github fa-2x  mr-3 align-middle\"  title=\"NPDS Dune on Github ...\" data-toggle=\"tooltip\"></i></a>";
$foot2 = "Tous les Logos et Marques sont d&eacute;pos&eacute;s, les commentaires sont sous la responsabilit&eacute; de ceux qui les ont publi&eacute;s, le reste &copy; <a href=\"http://www.npds.org\" target=\"_blank\" >NPDS</a>";
$foot3 = "";
$foot4 = "";
$anonymous = "Visiteur";
$site_font = "Verdana, Arial, Helvetica";
$minpass = 8;
$show_user = 20;

######################################################################
# General Stories Options
#
# $top:       How many items in Top Page?
# $storyhome: How many stories to display in Home Page?
# $oldnum:    How many stories in Old Articles Box?
######################################################################

$top = 10;
$storyhome = 5;
$oldnum = 10;

######################################################################
# Banners/Advertising Configuration
#
# $banners: Activate Banners Ads for your site? (1=Yes 0=No)
# $myIP:    Write your IP number to not count impressions, be fair about this!
######################################################################

$banners = 1;
$myIP = "1.1.1.100";

######################################################################
# XML/RDF Backend Configuration & Social Networks
#
# $backend_title:    Backend title, can be your site's name and slogan
# $backend_language: Language format of your site
# $backend_image:    Image logo for your site
# $backend_width:    Image logo width
# $backend_height:   Image logo height
# $ultramode:        Activate ultramode plain text and XML files backend syndication? (1=Yes 0=No). locate in /cache directory
# $npds_twi:         Activate the Twitter syndication? (1=Yes 0=No).
# $npds_fcb:         Activate the Facebook syndication? (1=Yes 0=No).
######################################################################

$backend_title = "NPDS";
$backend_language = "fr-FR";
$backend_image = "";
$backend_width = "90";
$backend_height = "30";
$ultramode = 1;
$npds_twi = 0;
$npds_fcb = 0;

######################################################################
# Site Language Preferences
#
# $language:     Language of your site (You need to have lang-xxxxxx.php file for your selected language in the /language directory of your site)
# $locale:       Locale configuration to correctly display date with your country format. (See /usr/share/locale)
# $gmt:          Locale configuration to correctly display date with your GMT offset.
# $lever:        HH:MM where Day become.
# $coucher:      HH:MM where Night become.
# $multi_langue: Activate Multi-langue NPDS'capability.
######################################################################

$language = "french";
$multi_langue = false;
$locale = "fr_FR.UTF8";
$gmt = "0";
$lever = "08:00";
$coucher = "20:00";

######################################################################
# Web Links Preferences
#
# $perpage:                  How many links to show on each page?
# $popular:                  How many hits need a link to be listed as popular?
# $newlinks:                 How many links to display in the New Links Page?
# $toplinks:                 How many links to display in The Best Links Page? (Most Popular)
# $linksresults:             How many links to display on each search result page?
# $links_anonaddlinklock:    Is Anonymous autorise to post new links? (0=Yes 1=No)
# $linkmainlogo:             Activate Logo on Main web Links Page (1=Yes 0=No)
# $OnCatNewLink:             Activate Icon for New Categorie on Main web Links Page (1=Yes 0=No)
######################################################################

$perpage = 10;
$popular = 10;
$newlinks = 10;
$toplinks = 10;
$linksresults = 10;
$links_anonaddlinklock = 0;
$linkmainlogo = 0;
$OnCatNewLink = 1;

######################################################################
# Function Mail and Notification of News Submissions
#
# $adminmail:      Site Administrator's Email
# $mail_fonction:  What Mail function to be used (1=mail, 2=email)
# $notify:         Notify you each time your site receives a news submission? (1=Yes 0=No)
# $notify_email:   Email, address to send the notification
# $notify_subject: Email subject
# $notify_message: Email body, message
# $notify_from:    account name to appear in From field of the Email
######################################################################

$adminmail = "webmaster@site.fr";
$mail_fonction = "1";
$notify = 1;
$notify_email = "webmaster@site.fr";
$notify_subject = "Nouvelle soumission";
$notify_message = "Le site a recu une nouvelle soumission !";
$notify_from = "webmaster@site.fr";

######################################################################
# Survey/Polls Config
#
# $maxOptions: Number of maximum options for each poll
# $setCookies: Set cookies to prevent visitors vote twice in a period of 24 hours? (0=Yes 1=No)
# $pollcomm:   Activate comments in Polls? (1=Yes 0=No)
######################################################################

$maxOptions = 12;
$setCookies = 1;
$pollcomm = 1;

######################################################################
# Some Graphics Options
#
# $tipath:       Topics images path (put / only at the end, not at the begining)
# $userimg:      User images path (put / only at the end, not at the begining)
# $adminimg:     Administration system images path (put / only at the end, not at the begining)
# $admingraphic: Activate graphic menu for Administration Menu? (1=Yes 0=No)
# $short_menu_admin: Activate short Administration Menu? (1=Yes 0=No)
# $admf_ext:     Image Files'extesion for admin menu (default: gif)
# $admart:       How many articles to show in the admin section?
######################################################################

$tipath = "images/topics/";
$userimg = "images/menu/";
$adminimg = "images/admin/";
$short_menu_admin = 1;
$admingraphic = 1;
$admf_ext = "png";
$admart = 10;

######################################################################
# HTTP Referers Options
#
# $httpref:    Activate HTTP referer logs to know who is linking to our site? (1=Yes 0=No)# $httprefmax: Maximum number of HTTP referers to store in the Database (Try to not set this to a high number, 500 ~ 1000 is Ok)
######################################################################

$httpref = 1;
$httprefmax = 1000;

######################################################################
# Miscelaneous Options
#
# $smilies:          Activate Avatar? (1=Yes 0=No)
# $avatar_size:      Maximum size for uploaded avatars in pixel (width*height) 
# $short_user:       Activate Short User registration (without ICQ, MSN, ...)? (1=Yes 0=No)
# $member_list:      Make the members List Private (only for members) or Public (Private=Yes Public=No)
# $download_cat:     Witch category do you want to show first in download section?
# $AutoRegUser:      Allow automated new-user creation (sending email and allowed connection)
# $short_review:     For transform reviews like "gold book" (1=Yes, 0=no)
# $subscribe:        Allow your members to subscribe to topics, ... (1=Yes, 0=no)
# $member_invisible: Allow members to hide from other members, ... (1=Yes, 0=no)
# $CloseRegUser:     Allow you to close New Member Registration (from Gawax Idea), ... (1=Yes, 0=no)
# $memberpass:       Allow user to choose alone the password (1=Yes, 0=no)
######################################################################

$smilies = 1;
$avatar_size = "80*100";
$short_user = 1;
$member_list = 1;
$download_cat = "Tous";
$AutoRegUser = 1;
$short_review = 0;
$subscribe = 1;
$member_invisible = 0;
$CloseRegUser = 0;
$memberpass = 1;

######################################################################
# HTTP Miscelaneous Options
#
# $rss_host_verif: Activate the validation of the existance of a web on Port 80 for Headlines (true=Yes false=No)
# $cache_verif:    Activate the Advance Caching Meta Tag (pragma ...) (true=Yes false=No)
# $dns_verif:      Activate the DNS resolution for posts (forum ...), IP-Ban, ... (true=Yes false=No)
######################################################################

$rss_host_verif = false;
$cache_verif = true;
$dns_verif = false;

######################################################################
# SYSTEM Miscelaneous Options
#
# $savemysql_size:  Determine the maximum size for one file in the SaveMysql process
# $savemysql_mode:  Type of Myql process (1, 2 or 3)
# $tiny_mce:        true=Yes or false=No to use tiny_mce Editor or NO Editor
######################################################################

$savemysql_size = 256;
$savemysql_mode = 1;
$tiny_mce = true;

######################################################################
# Do not touch the following options !
######################################################################

$NPDS_Prefix = "";
$NPDS_Key = "";
$Version_Num = "v.16.3";
$Version_Id = "NPDS";
$Version_Sub = "REvolution";

?>

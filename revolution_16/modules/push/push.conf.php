<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/* Base on pda Addon by Christopher Bradford (csb@wpsf.com)             */
/*                                                                      */
/* NPDS Copyright (c) 2002-2017 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

########################################################################################################
# General Push Infos Addon Options
# $push_largeur       : Width of Menu block (ex : 120 pixel or "90%")
# $push_br            : After the Faq Menu put a html tag (for ex : "<br />")
# $push_largeur_suite : Width of "seconf page" block (ex : 200 pixel or "90%")
# $push_news_limit    : Number of news show
# $push_member_col    : Number of columm for member's list (1 to n)
# $push_member_limit  : Number of member show on each first page (default max=29)
# $push_titre         : Title of the block ("Npds Push Addon")
# $push_logo          : Logo (gif or jpg)
# $push_view_perpage  : Number of Web links per page (2 or more)
# $push_orderby       : ASCendind or DESCending orderby trigger for Web links ("ASC" or "DESC")
# $follow_links       : Follow <a Href ... Link in this module (True or False)
#######################################################################################################
$push_largeur="100%";
$push_br="";
$push_largeur_suite="100%";
$push_news_limit=10;
$push_member_col=3;
$push_member_limit=9;
$push_titre="-: NPDS :-";
$push_logo="modules/push/images/pushlogo.gif";
$push_view_perpage=6;
$push_orderby="ASC";
$follow_links=true;

// For NPDS SuperCache Config (or other SuperCache implementation)
$CACHE_TIMINGS['push.php'] = 4*3600; // default 4*3600 secondes = 4 Hours
$CACHE_QUERYS['push.php'] = "^";  // Don't modify this line !

function push_header($operation) {
   global $push_largeur, $push_largeur_suite, $push_titre, $push_logo;
   if ($operation=="suite") {$push_largeur=$push_largeur_suite;}

   $temp  ="<table width=\"$push_largeur\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">";
   $temp .="<tr><td width=\"100%\">";
   $temp .="<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">";
   $temp .="<tr>";
   $push_titre=str_replace("'","\'",$push_titre);
   $temp .="<td width=\"100%\" align=\"center\"><span style=\"font-size: 11px;\"><b>".htmlspecialchars($push_titre,ENT_COMPAT|ENT_HTML401,cur_charset)."</b></td>";
   if ($push_logo!="") {
      $temp .="</tr><tr><td width=\"100%\" background=\"$push_logo\">";
   } else {
      $temp .="</tr><tr><td width=\"100%\">";
   }
   echo "<script type=\"text/javascript\">\n//<![CDATA[\ndocument.write('$temp');\n//]]>\n</script>";
}

function push_footer() {
   $temp="</td></tr></table></td></tr></table>";
   echo "document.write('$temp');\n";
}
?>
<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2019 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion"))
   include ("mainfile.php");

global $admin, $not_admin_count;
if ((!$admin) or ($not_admin_count!=1)) {
   $user_agent=getenv("HTTP_USER_AGENT");

   if((stristr($user_agent,"Nav")) || (stristr($user_agent,"Gold")) || (stristr($user_agent,"X11")) || (stristr($user_agent,"Mozilla")) || (stristr($user_agent,"Netscape")) AND (!stristr($user_agent,"MSIE")) AND (!stristr($user_agent,"SAFARI")) AND (!stristr($user_agent,"IPHONE")) AND (!stristr($user_agent,"IPOD")) AND (!stristr($user_agent,"IPAD")) AND (!stristr($user_agent,"ANDROID"))) $browser = "Netscape";
   elseif(stristr($user_agent,"MSIE")) $browser = "MSIE";
   elseif(stristr($user_agent,"Trident")) $browser = "MSIE";
   elseif(stristr($user_agent,"Lynx")) $browser = "Lynx";
   elseif(stristr($user_agent,"Opera")) $browser = "Opera";
   elseif(stristr($user_agent,"WebTV")) $browser = "WebTV";
   elseif(stristr($user_agent,"Konqueror")) $browser = "Konqueror";
   elseif(stristr($user_agent,"Chrome")) $browser = "Chrome";
   elseif(stristr($user_agent,"Safari")) $browser = "Safari";
   elseif (preg_match('#([bB]ot|[sS]pider|[yY]ahoo)#',$user_agent)) $browser = "Bot";
   else $browser = "Other";

   if(stristr($user_agent,"Win")) $os="Windows";
   elseif((stristr($user_agent,"Mac")) || (stristr($user_agent,"PPC"))) $os = "Mac";
   elseif(stristr($user_agent,"Linux")) $os = "Linux";
   elseif(stristr($user_agent,"FreeBSD")) $os = "FreeBSD";
   elseif(stristr($user_agent,"SunOS")) $os = "SunOS";
   elseif(stristr($user_agent,"IRIX")) $os = "IRIX";
   elseif(stristr($user_agent,"BeOS")) $os = "BeOS";
   elseif(stristr($user_agent,"OS/2")) $os = "OS/2";
   elseif(stristr($user_agent,"AIX")) $os = "AIX";
   else $os = "Other";

   global $NPDS_Prefix;
   sql_query("UPDATE ".$NPDS_Prefix."counter SET count=count+1 WHERE (type='total' AND var='hits') OR (var='$browser' AND type='browser') OR (var='$os' AND type='os')");
}
?>

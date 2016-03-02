<?php 
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion")) {
   include ("mainfile.php");
}

function docookie($setuid, $setuname, $setpass, $setstorynum, $setumode, $setuorder, $setthold, $setnoscore, $setublockon, $settheme, $setcommentmax, $user_langue) {
    $info = base64_encode("$setuid:$setuname:".md5($setpass).":$setstorynum:$setumode:$setuorder:$setthold:$setnoscore:$setublockon:$settheme:$setcommentmax");
    global $user_cook_duration;
    if ($user_cook_duration<=0) {$user_cook_duration=1;}
    $timeX=time()+(3600*$user_cook_duration);
    setcookie("user","$info",$timeX);
    if ($user_langue!="") {
       setcookie("user_language","$user_langue",$timeX);
    }
}

function chgtheme() {
    global $user;
    include ("header.php"); 
    $userinfo=getusrinfo($user); 
    nav($userinfo[mns]); 
    opentable(); 
    echo "<br /><table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n"; 
    echo translate("Select One Theme"); 
    echo "</td></tr></table>\n"; 
    echo "<p align=\"center\"><form action=\"user.php\" method=\"post\"> 
          <select class=\"textbox_standard\" name=\"theme\">";
    include("themes/list.php"); 
    $themelist = explode(' ', $themelist); 
    for ($i=0; $i < sizeof($themelist); $i++) {
       if ($themelist[$i]!='') { 
          echo "<option value=\"$themelist[$i]\" "; 
          if ((($userinfo[theme]=="") && ($themelist[$i]=="$Default_Theme")) || ($userinfo[theme]==$themelist[$i])) echo "selected=\"selected\"";
             echo ">$themelist[$i]\n"; 
          } 
    } 
    if ($userinfo[theme]=='') $userinfo[theme] = "Default_Theme";
    echo "</select></p><br /><ul> 
         <li>".translate("This option will change the look for the whole site.")."</li> 
         <li>".translate("The changes will be valid only to you.")."</li> 
         <li>".translate("Each user can view the site with different theme.")."</li></ul>"; 

    echo "<input type=\"hidden\" name=\"uname\" value=\"$userinfo[uname]\" />
          <input type=\"hidden\" name=\"uid\" value=\"$userinfo[uid]\" />
          <input type=\"hidden\" name=\"op\" value=\"savetheme\" /><br /> 
          <input class=\"bouton_standard\" type=\"submit\" value=\"".translate("Save Changes!")."\" />
          </form>"; 
    closetable(); 
    include ("footer.php"); 
} 
function savetheme($uid, $theme) {
    global $NPDS_Prefix;
    global $user;
    $cookie=cookiedecode($user);
    $check = $cookie[1];
    $result = sql_query("SELECT uid FROM ".$NPDS_Prefix."users WHERE uname='$check'");
    list($vuid) = sql_fetch_row($result);
    if ($uid == $vuid) {
        sql_query("UPDATE ".$NPDS_Prefix."users SET theme='$theme' WHERE uid='$uid'");
        $userinfo=getusrinfo($user);
        docookie($userinfo[uid],$userinfo[uname],$userinfo[pass],$userinfo[storynum],$userinfo[umode],$userinfo[uorder],$userinfo[thold],$userinfo[noscore],$userinfo[ublockon],$userinfo[theme],$userinfo[commentmax], "");
        // Include cache manager for purge cache Page
        $cache_obj = new cacheManager();
        $cache_obj->UsercacheCleanup();
        Header("Location: index.php");
    } else {
       Header("Location: index.php");
    }
}

switch ($op) {
   case 'chgtheme':
      if ($user) chgtheme();
      else
      Header("Location: index.php");
   break;
   case 'savetheme': 
      savetheme($uid, $theme);
   break; 
   default: 
      if (!AutoReg()) { unset($user); } 
      main($user); 
   break; 
}
?>
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

global $language, $cookie, $user, $Default_Theme, $NPDS_Prefix, $themelist;
$content  = '';

   $userinfo=getusrinfo($user);
   $content .= translate("Select One Theme");
   $content .='
   <form action="user2.php" method="post">
      <select class="c-select form-control" name="theme" onchange="submit()";>';
   include("themes/list.php");
   $themelist = explode(' ', $themelist);
   for ($i=0; $i < sizeof($themelist); $i++) {
      if ($themelist[$i]!='') {
         $content .='
         <option value="'.$themelist[$i].'" ';
         if ((($userinfo[theme]=="") && ($themelist[$i]=="$Default_Theme")) || ($userinfo[theme]==$themelist[$i]))
         $content .= 'selected="selected"';
         $content .= '>'.$themelist[$i];
         $content .= '</option>';
      }
   }
   if ($userinfo[theme]=='') $userinfo[theme] = 'Default_Theme';
   $content .= '
      </select>
      <input type="hidden" name="uname" value="'.$userinfo[uname].'" />
      <input type="hidden" name="uid" value="'.$userinfo[uid].'" />
      <input type="hidden" name="op" value="savetheme" />
   </form>';
?>
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

   include("header.php");

   $dkn = sql_query("SELECT type, var, count FROM ".$NPDS_Prefix."counter ORDER BY type DESC");
   while (list($type, $var, $count) = sql_fetch_row($dkn)) {
      if (($type == "total") && ($var == "hits")) {
         $total = $count;
      } elseif ($type == "browser") {
         if ($var == "Netscape") {
            $netscape[] = $count;
            $netscape[] =  substr(sprintf('%f', 100 * $count / $total), 0, 5);
         } elseif($var == "MSIE") {
            $msie[] = $count;
            $msie[] =  substr(sprintf('%f', 100 * $count / $total), 0, 5);
         } elseif ($var == "Konqueror") {
            $konqueror[] = $count;
            $konqueror[] =  substr(sprintf('%f', 100 * $count / $total), 0, 5);
         } elseif($var == "Opera") {
            $opera[] = $count;
            $opera[] =  substr(sprintf('%f', 100 * $count / $total), 0, 5);
         } elseif($var == "Lynx") {
            $lynx[] = $count;
            $lynx[] =  substr(sprintf('%f', 100 * $count / $total), 0, 5);
         } elseif($var == "WebTV") {
            $webtv[] = $count;
            $webtv[] =  substr(sprintf('%f', 100 * $count / $total), 0, 5);
         } elseif($var == "Chrome") {
            $chrome[] = $count;
            $chrome[] =  substr(sprintf('%f', 100 * $count / $total), 0, 5);
         } elseif($var == "Safari") {
            $safari[] = $count;
            $safari[] =  substr(sprintf('%f', 100 * $count / $total), 0, 5);
         } elseif($var == "Bot") {
            $bot[] = $count;
            $bot[] =  substr(sprintf('%f', 100 * $count / $total), 0, 5);
         } elseif(($type == "browser") && ($var == "Other")) {
            $b_other[] = $count;
            $b_other[] =  substr(sprintf('%f', 100 * $count / $total), 0, 5);
         }
      } elseif($type == "os") {
         if ($var == "Windows") {
            $windows[] = $count;
            $windows[] =  substr(sprintf('%f', 100 * $count / $total), 0, 5);
         } elseif($var == "Mac") {
            $mac[] = $count;
            $mac[] =  substr(sprintf('%f', 100 * $count / $total), 0, 5);
         } elseif($var == "Linux") {
            $linux[] = $count;
            $linux[] =  substr(sprintf('%f', 100 * $count / $total), 0, 5);
         } elseif($var == "FreeBSD") {
            $freebsd[] = $count;
            $freebsd[] =  substr(sprintf('%f', 100 * $count / $total), 0, 5);
         } elseif($var == "SunOS") {
            $sunos[] = $count;
            $sunos[] =  substr(sprintf('%f', 100 * $count / $total), 0, 5);
         } elseif($var == "IRIX") {
            $irix[] = $count;
            $irix[] =  substr(sprintf('%f', 100 * $count / $total), 0, 5);
         } elseif($var == "BeOS") {
            $beos[] = $count;
            $beos[] =  substr(sprintf('%f', 100 * $count / $total), 0, 5);
         } elseif($var == "OS/2") {
            $os2[] = $count;
            $os2[] =  substr(sprintf('%f', 100 * $count / $total), 0, 5);
         } elseif($var == "AIX") {
            $aix[] = $count;
            $aix[] =  substr(sprintf('%f', 100 * $count / $total), 0, 5);
         } elseif(($type == "os") && ($var == "Other")) {
            $os_other[] = $count;
            $os_other[] =  substr(sprintf('%f', 100 * $count / $total), 0, 5);
         }
      }
   }
   
   echo '
   <h2>'.translate("Statistics").'</h2>
   <div class="card card-block lead">
   '.translate("We received").' <span class="label label-default">'.wrh($total).'</span> '.translate("views since").' '.$startdate.'
   </div>
   <h3>'.translate("Browsers").'</h3>
   <table data-toggle="table" data-striped="true" >
      <thead>
         <tr>
            <th data-sortable="true" >'.translate("Browsers").'</th>
            <th data-sortable="true" data-halign="center" data-align="right">%</th>
            <th data-align="right"></th>
         </tr>
      </thead>
      <tbody>';
   if ($ibid=theme_image("stats/explorer.gif")) {$imgtmp=$ibid;} else { $imgtmp="images/stats/explorer.gif";}
   echo '
         <tr>
            <td width="40%"><img src="'.$imgtmp.'" alt="" />MSIE</td>
            <td>
               <div class="graph"><strong class="bar" style="width: '.$msie[1].'%;">'.$msie[1].'%</strong></div>
               <progress class="progress" value="'.$msie[1].'" max="100">
                  <div class="progress">
                     <span class="progress-bar" style="width:'.$msie[1].'%;">'.$msie[1].'%</span>
                  </div>
               </progress>
            </td>
            <td align="left">'.wrh($msie[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/firefox.gif")) {$imgtmp=$ibid;} else { $imgtmp="images/stats/firefox.gif";}
      echo '
         <tr>
            <td width="40%"><img src="'.$imgtmp.'" alt="" /> Mozilla</td>
            <td>
               <div class="graph"><strong class="bar" style="width: '.$netscape[1].'%;">'.$netscape[1].'%</strong></div>
               <progress class="progress" value="'.$netscape[1].'" max="100">
                  <div class="progress">
                     <span class="progress-bar" style="width:'.$netscape[1].'%;">'.$netscape[1].'%</span>
                  </div>
               </progress>
            </td>
            <td> '.wrh($netscape[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/opera.gif")) {$imgtmp=$ibid;} else { $imgtmp="images/stats/opera.gif";}
     echo '
         <tr>
            <td width="40%"><img src="'.$imgtmp.'" alt="" /> Opera</td>
            <td>
            <div class="graph"><strong class="bar" style="width: '.$opera[1].'%;">'.$opera[1].'%</strong></div>
               <progress class="progress" value="'.$opera[1].'" max="100">
                  <div class="progress">
                     <span class="progress-bar" style="width:'.$opera[1].'%;">'.$opera[1].'%</span>
                  </div>
               </progress>
            </td>
            <td>'.wrh($opera[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/chrome.gif")) {$imgtmp=$ibid;} else { $imgtmp="images/stats/chrome.gif";}
      echo '
         <tr>
            <td width="40%"><img src="'.$imgtmp.'" alt="" /> Chrome</td>
            <td>
               <div class="graph"><strong class="bar" style="width: '.$chrome[1].'%;">'.$chrome[1].'%</strong></div>
               <progress class="progress" value="'.$chrome[1].'" max="100">
                  <div class="progress">
                     <span class="progress-bar" style="width:'.$chrome[1].'%;">'.$chrome[1].'%</span>
                  </div>
               </progress>
            </td>
            <td>'.wrh($chrome[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/safari.gif")) {$imgtmp=$ibid;} else { $imgtmp="images/stats/safari.gif";}
      echo '
         <tr>
            <td width="40%"><img src="'.$imgtmp.'" alt="" /> Safari</td>
            <td>
               <div class="graph"><strong class="bar" style="width: '.$safari[1].'%;">'.$safari[1].'%</strong></div>
               <progress class="progress" value="'.$safari[1].'" max="100">
                  <div class="progress">
                     <span class="progress-bar" style="width:'.$safari[1].'%;">'.$safari[1].'%</span>
                  </div>
               </progress>
            </td>
            <td>'.wrh($safari[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/webtv.gif")) {$imgtmp=$ibid;} else { $imgtmp="images/stats/webtv.gif";}
      echo '
         <tr>
            <td width="40%"><img src="'.$imgtmp.'"  alt="" />WebTV</td>
            <td>
               <div class="graph"><strong class="bar" style="width: '.$webtv[1].'%;">'.$webtv[1].'%</strong></div>
               <progress class="progress" value="'.$webtv[1].'" max="100">
                  <div class="progress">
                     <span class="progress-bar" style="width:'.$webtv[1].'%;">'.$webtv[1].'%</span>
                  </div>
               </progress>
            </td>
            <td>'.wrh($webtv[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/konqueror.gif")) {$imgtmp=$ibid;} else { $imgtmp="images/stats/konqueror.gif";}
      echo '
         <tr>
            <td width="40%"><img src="'.$imgtmp.'" alt="" />Konqueror</td>
            <td>
               <div class="graph"><strong class="bar" style="width: '.$konqueror[1].'%;">'.$konqueror[1].'%</strong></div>
               <progress class="progress" value="'.$konqueror[1].'" max="100">
                  <div class="progress">
                     <span class="progress-bar" style="width:'.$konqueror[1].'%;">'.$konqueror[1].'%</span>
                  </div>
               </progress>
            </td>
            <td>'.wrh($konqueror[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/lynx.gif")) {$imgtmp=$ibid;} else { $imgtmp="images/stats/lynx.gif";}
      echo '
         <tr>
            <td width="40%"><img src="'.$imgtmp.'" alt="" />Lynx</td>
            <td>
               <div class="graph"><strong class="bar" style="width: '.$lynx[1].'%;"> '.$lynx[1].'%</strong></div>
               <progress class="progress" value="'.$lynx[1].'" max="100">
                  <div class="progress">
                     <span class="progress-bar" style="width:'.$lynx[1].'%;">'.$lynx[1].'%</span>
                  </div>
               </progress>
            </td>
            <td>'.wrh($lynx[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/altavista.gif")) {$imgtmp=$ibid;} else { $imgtmp="images/stats/altavista.gif";}
      echo '
         <tr>
            <td width="40%"><img src="'.$imgtmp.'" alt="" />'.translate("Search Engines").'</td>
            <td>
               <div class="graph"><strong class="bar" style="width: '.$bot[1].'%;"> '.$bot[1].'%</strong></div>
               <progress class="progress" value="'.$bot[1].'" max="100">
                  <div class="progress">
                     <span class="progress-bar" style="width:'.$bot[1].'%;">'.$bot[1].'%</span>
                  </div>
               </progress>
            </td>
            <td>'.wrh($bot[0]).'</td>
         </tr>
         <tr>
            <td width="40%"><i class="fa fa-question fa-3x"></i>&nbsp;'.translate("Unknown").'</td>
            <td>
               <div class="graph"><strong class="bar" style="width: '.$b_other[1].'%;"> '.$b_other[1].'%</strong></div>
               <progress class="progress" value="'.$b_other[1].'" max="100">
                  <div class="progress">
                     <span class="progress-bar" style="width:'.$b_other[1].'%;">'.$b_other[1].'%</span>
                  </div>
               </progress>
               </td>
            <td>'.wrh($b_other[0]).'</td>
         </tr>
      </tbody>
   </table>
   <br />';
   echo '
   <h3>'.translate("Operating Systems").'</h3>
   <table data-toggle="table" data-striped="true" >
      <thead>
         <tr>
            <th data-sortable="true" >'.translate("Operating Systems").'</th>
            <th data-sortable="true" data-halign="center" data-align="right">%</th>
            <th data-align="right"></th>
         </tr>
      </thead>
      <tbody>';
   if ($ibid=theme_image("stats/windows.gif")) {$imgtmp=$ibid;} else { $imgtmp="images/stats/windows.gif";}
      echo '
         <tr>
            <td width="40%"><img src="'.$imgtmp.'"  alt="" />&nbsp;Windows</td>
            <td>
               <div class="graph"><strong class="bar" style="width: '.$windows[1].'%;">'.$windows[1].'%</strong></div>
               <progress class="progress" value="'.$windows[1].'" max="100">
                  <div class="progress">
                     <span class="progress-bar" style="width:'.$windows[1].'%;">'.$windows[1].'%</span>
                  </div>
               </progress>
            </td>
            <td>'.wrh($windows[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/linux.gif")) {$imgtmp=$ibid;} else { $imgtmp="images/stats/linux.gif";}
      echo '
         <tr>
            <td width="40%"><img src="'.$imgtmp.'"  alt="" />&nbsp;Linux</td>
            <td>
               <div class="graph"><strong class="bar" style="width: '.$linux[1].'%;">'.$linux[1].'%</strong></div>
               <progress class="progress" value="'.$linux[1].'" max="100">
                  <div class="progress">
                     <span class="progress-bar" style="width:'.$linux[1].'%;">'.$linux[1].'%</span>
                  </div>
               </progress>
            </td>
            <td>'.wrh($linux[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/mac.gif")) {$imgtmp=$ibid;} else { $imgtmp="images/stats/mac.gif";}
      echo '
         <tr>
            <td width="40%"><img src="'.$imgtmp.'"  alt="" />&nbsp;Mac/PPC</td>
            <td>
               <div class="graph"><strong class="bar" style="width: '.$mac[1].'%;">'.$mac[1].'%</strong></div>
               <progress class="progress" value="'.$mac[1].'" max="100">
                  <div class="progress">
                     <span class="progress-bar" style="width:'.$mac[1].'%;">'.$mac[1].'%</span>
                  </div>
               </progress>
            </td>
            <td>'.wrh($mac[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/bsd.gif")) {$imgtmp=$ibid;} else { $imgtmp="images/stats/bsd.gif";}
      echo '
         <tr>
            <td width="40%"><img src="'.$imgtmp.'"  alt="" />&nbsp;FreeBSD</td>
            <td>
               <div class="graph"><strong class="bar" style="width: '.$freebsd[1].'%;">'.$freebsd[1].'%</strong></div>
               <progress class="progress" value="'.$freebsd[1].'" max="100">
                  <div class="progress">
                     <span class="progress-bar" style="width:'.$freebsd[1].'%;">'.$freebsd[1].'%</span>
                  </div>
               </progress>
            </td>
            <td>'.wrh($freebsd[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/sun.gif")) {$imgtmp=$ibid;} else { $imgtmp="images/stats/sun.gif";}
      echo '
         <tr>
            <td width="40%"><img src="'.$imgtmp.'"  alt="" />&nbsp;SunOS</td>
            <td>
               <div class="graph"><strong class="bar" style="width: '.$sunos[1].'%;">'.$sunos[1].'%</strong></div>
               <progress class="progress" value="'.$sunos[1].'" max="100">
                  <div class="progress">
                     <span class="progress-bar" style="width:'.$sunos[1].'%;">'.$sunos[1].'%</span>
                  </div>
               </progress>
            </td>
            <td>'.wrh($sunos[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/irix.gif")) {$imgtmp=$ibid;} else { $imgtmp="images/stats/irix.gif";}
      echo '
         <tr>
            <td width="40%"><img src="'.$imgtmp.'"  alt="" />&nbsp;IRIX</td>
            <td>
               <div class="graph"><strong class="bar" style="width: '.$irix[1].'%;">'.$irix[1].'%</strong></div>
               <progress class="progress" value="'.$irix[1].'" max="100">
                  <div class="progress">
                     <span class="progress-bar" style="width:'.$irix[1].'%;">'.$irix[1].'%</span>
                  </div>
               </progress>
            </td>
            <td>'.wrh($irix[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/be.gif")) {$imgtmp=$ibid;} else { $imgtmp="images/stats/be.gif";}
      echo '
         <tr>
            <td width="40%"><img src="'.$imgtmp.'"  alt="" />&nbsp;BeOS</td>
            <td>
               <div class="graph"><strong class="bar" style="width: '.$beos[1].'%;">'.$beos[1].'%</strong></div>
               <progress class="progress" value="'.$beos[1].'" max="100">
                  <div class="progress">
                     <span class="progress-bar" style="width:'.$beos[1].'%;">'.$beos[1].'%</span>
                  </div>
               </progress>
            </td>
            <td>'.wrh($beos[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/os2.gif")) {$imgtmp=$ibid;} else { $imgtmp="images/stats/os2.gif";}
      echo '
         <tr>
            <td width="40%"><img src="'.$imgtmp.'"  alt="" />&nbsp;OS/2</td>
            <td>
               <div class="graph"><strong class="bar" style="width: '.$os2[1].'%;">'.$os2[1].'%</strong></div>
               <progress class="progress" value="'.$os2[1].'" max="100">
                  <div class="progress">
                     <span class="progress-bar" style="width:'.$os2[1].'%;">'.$os2[1].'%</span>
                  </div>
               </progress>
            </td>
            <td>'.wrh($os2[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/aix.gif")) {$imgtmp=$ibid;} else { $imgtmp="images/stats/aix.gif";}
      echo '
         <tr>
            <td width="40%"><img src="'.$imgtmp.'"  alt="" />&nbsp;AIX</td>
            <td>
               <div class="graph"><strong class="bar" style="width: '.$aix[1].'%;">'.$aix[1].'%</strong></div>
               <progress class="progress" value="'.$aix[1].'" max="100">
                  <div class="progress">
                     <span class="progress-bar" style="width:'.$aix[1].'%;">'.$aix[1].'%</span>
                  </div>
               </progress>
            </td>
            <td>'.wrh($aix[0]).'</td>
         </tr>
         <tr>
            <td width="40%"><i class="fa fa-question fa-3x"></i>&nbsp;'.translate("Unknown").'</td>
            <td>
               <div class="graph"><strong class="bar" style="width: '.$os_other[1].'%;">'.$os_other[1].'%</strong></div>
               <progress class="progress" value="'.$os_other[1].'" max="100">
                  <div class="progress">
                     <span class="progress-bar" style="width:'.$os_other[1].'%;">'.$os_other[1].'%</span>
                  </div>
               </progress>
            </td>
            <td>'.wrh($os_other[0]).'</td>
         </tr>
      </tbody>
   </table>
   <br />
   <h3>'.translate("Theme(s)").'</h3>
   <table data-toggle="table" data-striped="true">
      <thead>
      <tr>
         <th data-sortable="true">'.translate("Theme(s)").'</th>
         <th>'.translate("Number of users per theme").'</th>
         <th>'.translate("Status").'</th>
      </tr>
      </thead>
      <tbody>';

   $resultX = sql_query("SELECT DISTINCT(theme) FROM ".$NPDS_Prefix."users");
   global $Default_Theme;
   while(list($themelist)=sql_fetch_row($resultX)) {
      if ($themelist!='') {
         if (is_dir("themes/$themelist")) {$D_exist='';} else {$D_exist="<span class=\"rouge\">".translate("There is no such file...")."</span>";}
         if ($themelist==$Default_Theme) {
            $result = sql_query("SELECT uid FROM ".$NPDS_Prefix."users WHERE theme='$themelist'");
            if ($result) {$themeD1 = sql_num_rows($result);} else {$themeD1=0;}
            $result = sql_query("SELECT uid FROM ".$NPDS_Prefix."users WHERE theme=''");
            if ($result) {$themeD2 = sql_num_rows($result);}else {$themeD2=0;}
            echo "
            <tr>
               <td nowrap=\"nowrap\">&nbsp;$themelist <b>(".translate("Default").")</b></td>
               <td align=\"center\"><b>".wrh(($themeD1+$themeD2))."</b></td>
               <td>$D_exist</td>
            </tr>";
         } else {
            $result = sql_query("SELECT uid FROM ".$NPDS_Prefix."users WHERE theme='$themelist'");
            if ($result) {$themeU = sql_num_rows($result);} else {$themeU=0;}
            echo '
            <tr>
               <td>&nbsp;'.$themelist.' :</td>
               <td><b>'.wrh($themeU).'</b></td>
               <td>'.$D_exist.'</td>
            </tr>';
         }
      }
   }
   echo '
      </tbody>
   </table>';
   echo '<br />';

   $result = sql_query("SELECT uid FROM ".$NPDS_Prefix."users");
   if ($result) {$unum = sql_num_rows($result)-1;} else {$unum=0;}
   $result = sql_query("SELECT sid FROM ".$NPDS_Prefix."stories");
   if ($result) {$snum = sql_num_rows($result);} else {$snum=0;}
   $result = sql_query("SELECT aid FROM ".$NPDS_Prefix."authors");
   if ($result) {$anum = sql_num_rows($result);} else {$anum=0;}
   $result = sql_query("SELECT post_id FROM ".$NPDS_Prefix."posts WHERE forum_id<0");
   if ($result) {$cnum = sql_num_rows($result);} else {$cnum=0;}
   $result = sql_query("SELECT secid FROM ".$NPDS_Prefix."sections");
   if ($result) {$secnum = sql_num_rows($result);} else {$secnum=0;}
   $result = sql_query("SELECT artid FROM ".$NPDS_Prefix."seccont");
   if ($result) {$secanum = sql_num_rows($result);} else {$secanum=0;}
   $result = sql_query("SELECT gid FROM ".$NPDS_Prefix."queue");
   if ($result) {$subnum = sql_num_rows($result);} else {$subnum=0;}
   $result = sql_query("SELECT topicid FROM ".$NPDS_Prefix."topics");
   if ($result) {$tnum = sql_num_rows($result);} else {$tnum=0;}
   $result = sql_query("SELECT lid FROM ".$NPDS_Prefix."links_links");
   if ($result) {$links = sql_num_rows($result);} else {$links=0;}
   $result = sql_query("SELECT cid FROM ".$NPDS_Prefix."links_categories");
   if ($result) {$cat1 = sql_num_rows($result);} else {$cat1=0;}
   $result = sql_query("SELECT sid FROM ".$NPDS_Prefix."links_subcategories");
   if ($result) {$cat2 = sql_num_rows($result);} else {$cat2=0;}
   $cat = $cat1+$cat2;

   echo '
   <h3>'.translate("Miscelaneous Stats").'</h3>
   <ul class="list-group">';
   if ($ibid=theme_image("stats/users.gif")) {$imgtmp=$ibid;} else { $imgtmp="images/stats/users.gif";}
      echo '
      <li class="list-group-item"><img src="'.$imgtmp.'"  alt="" />&nbsp;'.translate("Registered Users: ").' <span class="label label-default pull-xs-right">'.wrh($unum).' </span></li>';
   if ($ibid=theme_image("stats/authors.gif")) {$imgtmp=$ibid;} else { $imgtmp="images/stats/authors.gif";}
      echo '
      <li class="list-group-item"><img src="'.$imgtmp.'"  alt="" />&nbsp;'.translate("Active Authors: ").' <span class="label label-default pull-xs-right">'.wrh($anum).' </span></li>';
   if ($ibid=theme_image("stats/news.gif")) {$imgtmp=$ibid;} else { $imgtmp="images/stats/news.gif";}
      echo '
      <li class="list-group-item"><img src="'.$imgtmp.'"  alt="" />&nbsp;'.translate("Stories Published: ").' <span class="label label-default pull-xs-right">'.wrh($snum).' </span></li>';
   if ($ibid=theme_image("stats/topics.gif")) {$imgtmp=$ibid;} else { $imgtmp="images/stats/topics.gif";}
      echo '
      <li class="list-group-item"><img src="'.$imgtmp.'"  alt="" />&nbsp;'.translate("Active Topics: ").' <span class="label label-default pull-xs-right">'.wrh($tnum).' </span></li>';
   if ($ibid=theme_image("stats/comments.gif")) {$imgtmp=$ibid;} else { $imgtmp="images/stats/comments.gif";}
      echo '
      <li class="list-group-item"><img src="'.$imgtmp.'"  alt="" />&nbsp;'.translate("Comments Posted: ").' <span class="label label-default pull-xs-right">'.wrh($cnum).' </span></li>';
   if ($ibid=theme_image("stats/sections.gif")) {$imgtmpS=$ibid;} else { $imgtmpS="images/stats/sections.gif";}
      echo '
      <li class="list-group-item"><img src="'.$imgtmpS.'"  alt="" />&nbsp;'.translate("Special Sections: ").' <span class="label label-default pull-xs-right">'.wrh($secnum).' </span></li>';
   if ($ibid=theme_image("stats/articles.gif")) {$imgtmp=$ibid;} else { $imgtmp="images/stats/articles.gif";}
      echo '
      <li class="list-group-item"><img src="'.$imgtmp.'"  alt="" />&nbsp;'.translate("Articles in Sections: ").' <span class="label label-default pull-xs-right">'.wrh($secanum).' </span></li>';
   if ($ibid=theme_image("stats/topics.gif")) {$imgtmp=$ibid;} else { $imgtmp="images/stats/topics.gif";}
      echo '
      <li class="list-group-item"><img src="'.$imgtmp.'"  alt="" />&nbsp;'.translate("Links in Web Links: ").' <span class="label label-default pull-xs-right">'.wrh($links).' </span></li>
      <li class="list-group-item"><img src="'.$imgtmpS.'"  alt="" />&nbsp;'.translate("Categories in Web Links: ").' <span class="label label-default pull-xs-right">'.wrh($cat).' </span></li>';
   if ($ibid=theme_image("stats/waiting.gif")) {$imgtmp=$ibid;} else { $imgtmp="images/stats/waiting.gif";}
      echo '
      <li class="list-group-item"><img src="'.$imgtmp.'"  alt="" />&nbsp;'.translate("News Waiting to be Published: ").' <span class="label label-default pull-xs-right">'.wrh($subnum).' </span></li>
      <li class="list-group-item"><img src="'.$imgtmpS.'"  alt="" />&nbsp;Version Num <span class="label label-danger pull-xs-right">'.$Version_Num.'</span></li>
      <li class="list-group-item"><img src="'.$imgtmpS.'"  alt="" />&nbsp;Version Id <span class="label label-danger pull-xs-right">'.$Version_Id.'</span></li>
      <li class="list-group-item"><img src="'.$imgtmpS.'"  alt="" />&nbsp;Version Sub <span class="label label-danger pull-xs-right">'.$Version_Sub.'</span></li>
   </ul>';
   echo "<br /><p align=\"center\"><a href=\"http://www.npds.org\" class=\"noir\">http://www.npds.org</a> - French Portal Generator Gnu/Gpl Licence</p><br />";

include("footer.php");
?>
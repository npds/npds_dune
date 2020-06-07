<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2020 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion"))
   include ("mainfile.php");

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
         } elseif($var == "Android") {
            $andro[] = $count;
            $andro[] =  substr(sprintf('%f', 100 * $count / $total), 0, 5);
         } elseif($var == "iOS") {
            $ios[] = $count;
            $ios[] =  substr(sprintf('%f', 100 * $count / $total), 0, 5);
         }

         elseif(($type == "os") && ($var == "Other")) {
            $os_other[] = $count;
            $os_other[] =  substr(sprintf('%f', 100 * $count / $total), 0, 5);
         }
      }
   }

   echo '
   <h2>'.translate("Statistiques").'</h2>
   <div class="card card-body lead">
      <div>
      '.translate("Nos visiteurs ont visualisé").' <span class="badge badge-secondary">'.wrh($total).'</span> '.translate("pages depuis le").' '.$startdate.'
      </div>
   </div>
   <h3 class="my-4">'.translate("Navigateurs web").'</h3>
   <table data-toggle="table" data-mobile-responsive="true">
      <thead>
         <tr>
            <th data-sortable="true" >'.translate("Navigateurs web").'</th>
            <th data-sortable="true" data-halign="center" data-align="right" >%</th>
            <th data-align="right" ></th>
         </tr>
      </thead>
      <tbody>';
   if ($ibid=theme_image("stats/explorer.gif")) $imgtmp=$ibid; else $imgtmp="images/stats/explorer.gif";
   echo '
         <tr>
            <td><img src="'.$imgtmp.'" alt="MSIE_ico" /> MSIE </td>
            <td>
               <div class="text-center small">'.$msie[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$msie[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$msie[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.wrh($msie[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/firefox.gif")) $imgtmp=$ibid; else $imgtmp="images/stats/firefox.gif";
      echo '
         <tr>
            <td><img src="'.$imgtmp.'" alt="Mozilla_ico" /> Mozilla </td>
            <td>
               <div class="text-center small">'.$netscape[1].' %</div>
                  <div class="progress bg-light">
                     <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$netscape[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$netscape[1].'%; height:1rem;"></div>
                  </div>
            </td>
            <td> '.wrh($netscape[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/opera.gif")) $imgtmp=$ibid; else $imgtmp="images/stats/opera.gif";
     echo '
         <tr>
            <td><img src="'.$imgtmp.'" alt="Opera_ico" /> Opera </td>
            <td>
               <div class="text-center small">'.$opera[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$opera[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$opera[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.wrh($opera[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/chrome.gif")) $imgtmp=$ibid; else $imgtmp="images/stats/chrome.gif";
      echo '
         <tr>
            <td><img src="'.$imgtmp.'" alt="Chrome_ico" /> Chrome </td>
            <td>
               <div class="text-center small">'.$chrome[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$chrome[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$chrome[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.wrh($chrome[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/safari.gif")) $imgtmp=$ibid; else $imgtmp="images/stats/safari.gif";
      echo '
         <tr>
            <td><img src="'.$imgtmp.'" alt="Safari_ico" /> Safari </td>
            <td>
               <div class="text-center small">'.$safari[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$safari[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$safari[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.wrh($safari[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/webtv.gif")) $imgtmp=$ibid; else $imgtmp="images/stats/webtv.gif";
      echo '
         <tr>
            <td><img src="'.$imgtmp.'"  alt="WebTV_ico" /> WebTV </td>
            <td>
               <div class="text-center small">'.$webtv[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$webtv[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$webtv[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.wrh($webtv[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/konqueror.gif")) $imgtmp=$ibid; else $imgtmp="images/stats/konqueror.gif";
      echo '
         <tr>
            <td><img src="'.$imgtmp.'" alt="Konqueror_ico" /> Konqueror </td>
            <td>
               <div class="text-center small">'.$konqueror[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$konqueror[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$konqueror[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.wrh($konqueror[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/lynx.gif")) $imgtmp=$ibid; else $imgtmp="images/stats/lynx.gif";
      echo '
         <tr>
            <td><img src="'.$imgtmp.'" alt="Lynx_ico" /> Lynx </td>
            <td>
               <div class="text-center small">'.$lynx[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$lynx[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$lynx[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.wrh($lynx[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/altavista.gif")) $imgtmp=$ibid; else $imgtmp="images/stats/altavista.gif";
      echo '
         <tr>
            <td><img src="'.$imgtmp.'" alt="'.translate("Moteurs de recherche").'_ico" /> '.translate("Moteurs de recherche").' </td>
            <td>
               <div class="text-center small">'.$bot[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$bot[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$bot[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.wrh($bot[0]).'</td>
         </tr>
         <tr>
            <td><i class="fa fa-question fa-3x align-middle"></i> '.translate("Inconnu").' </td>
            <td>
               <div class="text-center small">'.$b_other[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$b_other[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$b_other[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.wrh($b_other[0]).'</td>
         </tr>
      </tbody>
   </table>
   <br />
   <h3 class="my-4">'.translate("Systèmes d'exploitation").'</h3>
   <table data-toggle="table" data-mobile-responsive="true" >
      <thead>
         <tr>
            <th data-sortable="true" >'.translate("Systèmes d'exploitation").'</th>
            <th data-sortable="true" data-halign="center" data-align="right">%</th>
            <th data-align="right"></th>
         </tr>
      </thead>
      <tbody>';
   if ($ibid=theme_image("stats/windows.gif")) $imgtmp=$ibid; else $imgtmp="images/stats/windows.gif";
      echo '
         <tr>
            <td ><img src="'.$imgtmp.'"  alt="Windows" />&nbsp;Windows</td>
            <td>
               <div class="text-center small">'.$windows[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$windows[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$windows[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.wrh($windows[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/linux.gif")) $imgtmp=$ibid; else $imgtmp="images/stats/linux.gif";
      echo '
         <tr>
            <td ><img src="'.$imgtmp.'"  alt="Linux" />&nbsp;Linux</td>
            <td>
               <div class="text-center small">'.$linux[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$linux[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$linux[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.wrh($linux[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/mac.gif")) $imgtmp=$ibid; else $imgtmp="images/stats/mac.gif";
      echo '
         <tr>
            <td><img src="'.$imgtmp.'"  alt="Mac/PPC" />&nbsp;Mac/PPC</td>
            <td>
               <div class="text-center small">'.$mac[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$mac[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$mac[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.wrh($mac[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/bsd.gif")) $imgtmp=$ibid; else $imgtmp="images/stats/bsd.gif";
      echo '
         <tr>
            <td><img src="'.$imgtmp.'"  alt="FreeBSD" />&nbsp;FreeBSD</td>
            <td>
               <div class="text-center small">'.$freebsd[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$freebsd[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$freebsd[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.wrh($freebsd[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/sun.gif")) $imgtmp=$ibid; else $imgtmp="images/stats/sun.gif";
      echo '
         <tr>
            <td><img src="'.$imgtmp.'"  alt="SunOS" />&nbsp;SunOS</td>
            <td>
               <div class="text-center small">'.$sunos[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$sunos[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$sunos[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.wrh($sunos[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/irix.gif")) $imgtmp=$ibid; else $imgtmp="images/stats/irix.gif";
      echo '
         <tr>
            <td><img src="'.$imgtmp.'"  alt="IRIX" />&nbsp;IRIX</td>
            <td>
               <div class="text-center small">'.$irix[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$irix[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$irix[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.wrh($irix[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/be.gif")) $imgtmp=$ibid; else $imgtmp="images/stats/be.gif";
      echo '
         <tr>
            <td><img src="'.$imgtmp.'" alt="BeOS" />&nbsp;BeOS</td>
            <td>
               <div class="text-center small">'.$beos[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$beos[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$beos[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.wrh($beos[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/os2.gif")) $imgtmp=$ibid; else $imgtmp="images/stats/os2.gif";
      echo '
         <tr>
            <td><img src="'.$imgtmp.'" alt="OS/2" />&nbsp;OS/2</td>
            <td>
               <div class="text-center small">'.$os2[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$os2[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$os2[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.wrh($os2[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/aix.gif")) $imgtmp=$ibid; else $imgtmp="images/stats/aix.gif";
      echo '
         <tr>
            <td><img src="'.$imgtmp.'" alt="AIX" />&nbsp;AIX</td>
            <td>
               <div class="text-center small">'.$aix[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$aix[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$aix[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.wrh($aix[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/android.gif")) $imgtmp=$ibid; else $imgtmp="images/stats/android.gif";
      echo '
         <tr>
            <td><img src="'.$imgtmp.'" alt="Android" />&nbsp;Android</td>
            <td>
               <div class="text-center small">'.$andro[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$andro[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$andro[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.wrh($andro[0]).'</td>
         </tr>';
   if ($ibid=theme_image("stats/ios.gif")) $imgtmp=$ibid; else $imgtmp="images/stats/ios.gif";
      echo '
         <tr>
            <td><img src="'.$imgtmp.'" alt="Ios" /> Ios</td>
            <td>
               <div class="text-center small">'.$ios[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$ios[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$ios[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.wrh($ios[0]).'</td>
         </tr>
         <tr>
            <td><i class="fa fa-question fa-3x align-middle"></i>&nbsp;'.translate("Inconnu").'</td>
            <td>
               <div class="text-center small">'.$os_other[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$os_other[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$os_other[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.wrh($os_other[0]).'</td>
         </tr>
      </tbody>
   </table>
   <h3 class="my-4">'.translate("Thème(s)").'</h3>
   <table data-toggle="table" data-striped="true">
      <thead>
      <tr>
         <th data-sortable="true" data-halign="center">'.translate("Thème(s)").'</th>
         <th data-halign="center" data-align="right">'.translate("Nombre d'utilisateurs par thème").'</th>
         <th data-halign="center">'.translate("Status").'</th>
      </tr>
      </thead>
      <tbody>';

   $resultX = sql_query("SELECT DISTINCT(theme) FROM ".$NPDS_Prefix."users");
   global $Default_Theme;
   while(list($themelist)=sql_fetch_row($resultX)) {
      if ($themelist!='') {
      $ibix=explode('+',$themelist);
//      var_dump($ibix);
//      var_dump($Default_Theme);
         if (is_dir("themes/$ibix[0]")) $T_exist=''; else $T_exist='<span class="text-danger">'.translate("Ce fichier n'existe pas ...").'</span>';
         if ($themelist==$Default_Theme) {
            $result = sql_query("SELECT uid FROM ".$NPDS_Prefix."users WHERE theme='$themelist'");
            if ($result) $themeD1 = sql_num_rows($result); else $themeD1=0;
            $result = sql_query("SELECT uid FROM ".$NPDS_Prefix."users WHERE theme=''");
            if ($result) $themeD2 = sql_num_rows($result); else $themeD2=0;
            echo '
            <tr>
               <td>'.$themelist.' <b>('.translate("par défaut").')</b></td>
               <td><b>'.wrh(($themeD1+$themeD2)).'</b></td>
               <td>'.$T_exist.'</td>
            </tr>';
         } else {
            $result = sql_query("SELECT uid FROM ".$NPDS_Prefix."users WHERE theme='$themelist'");

            if ($result) {$themeU = sql_num_rows($result);} else {$themeU=0;}
            echo '
            <tr>';
            if(substr($ibix[0],-3)=="_sk") 
               echo '
               <td>'.$themelist.'</td>';
            else
               echo '
               <td>'.$ibix[0].'</td>';
            echo '
               <td><b>'.wrh($themeU).'</b></td>
               <td>'.$T_exist.'</td>
            </tr>';
         }
      }
   }
   echo '
      </tbody>
   </table>';

   $result = sql_query("SELECT uid FROM ".$NPDS_Prefix."users");
   if ($result) $unum = sql_num_rows($result)-1; else $unum=0;
   $result = sql_query("SELECT sid FROM ".$NPDS_Prefix."stories");
   if ($result) $snum = sql_num_rows($result); else $snum=0;
   $result = sql_query("SELECT aid FROM ".$NPDS_Prefix."authors");
   if ($result) $anum = sql_num_rows($result); else $anum=0;
   $result = sql_query("SELECT post_id FROM ".$NPDS_Prefix."posts WHERE forum_id<0");
   if ($result) $cnum = sql_num_rows($result); else $cnum=0;
   $result = sql_query("SELECT secid FROM ".$NPDS_Prefix."sections");
   if ($result) $secnum = sql_num_rows($result); else $secnum=0;
   $result = sql_query("SELECT artid FROM ".$NPDS_Prefix."seccont");
   if ($result) $secanum = sql_num_rows($result); else $secanum=0;
   $result = sql_query("SELECT gid FROM ".$NPDS_Prefix."queue");
   if ($result) $subnum = sql_num_rows($result); else $subnum=0;
   $result = sql_query("SELECT topicid FROM ".$NPDS_Prefix."topics");
   if ($result) $tnum = sql_num_rows($result); else $tnum=0;
   $result = sql_query("SELECT lid FROM ".$NPDS_Prefix."links_links");
   if ($result) $links = sql_num_rows($result); else $links=0;
   $result = sql_query("SELECT cid FROM ".$NPDS_Prefix."links_categories");
   if ($result) $cat1 = sql_num_rows($result); else $cat1=0;
   $result = sql_query("SELECT sid FROM ".$NPDS_Prefix."links_subcategories");
   if ($result) $cat2 = sql_num_rows($result); else $cat2=0;
   $cat = $cat1+$cat2;

   echo '
   <h3 class="my-4">'.translate("Statistiques diverses").'</h3>
   <ul class="list-group">
      <li class="list-group-item d-flex justify-content-start align-items-center"><i class="fa fa-users fa-2x text-muted"></i>&nbsp;'.translate("Utilisateurs enregistrés : ").' <span class="badge badge-secondary ml-auto">'.wrh($unum).' </span></li>
      <li class="list-group-item d-flex justify-content-start align-items-center"><i class="fa fa-user fa-2x text-muted"></i>&nbsp;<i class="fas fa-pencil-alt fa-lg text-muted"></i>&nbsp;'.translate("Auteurs actifs : ").' <span class="badge badge-secondary ml-auto">'.wrh($anum).' </span></li>';
   if ($ibid=theme_image("stats/postnew.png")) $imgtmp=$ibid; else $imgtmp="images/admin/postnew.png";
   echo '
      <li class="list-group-item d-flex justify-content-start align-items-center"><img src="'.$imgtmp.'" alt="" />&nbsp;'.translate("Articles publiés : ").' <span class="badge badge-secondary ml-auto">'.wrh($snum).' </span></li>';
   if ($ibid=theme_image("stats/topicsman.png")) $imgtmp=$ibid; else $imgtmp="images/admin/topicsman.png";
   echo '
      <li class="list-group-item d-flex justify-content-start align-items-center"><img src="'.$imgtmp.'" alt="" />&nbsp;'.translate("Sujets actifs : ").' <span class="badge badge-secondary ml-auto">'.wrh($tnum).' </span></li>
      <li class="list-group-item d-flex justify-content-start align-items-center"><i class="fa fa-comments fa-2x text-muted"></i>&nbsp;'.translate("Commentaires postés : ").' <span class="badge badge-secondary ml-auto">'.wrh($cnum).' </span></li>';
   if ($ibid=theme_image("stats/sections.png")) $imgtmpS=$ibid; else $imgtmpS="images/admin/sections.png";
   echo '
      <li class="list-group-item d-flex justify-content-start align-items-center"><img src="'.$imgtmpS.'" alt="" />&nbsp;'.translate("Rubriques spéciales : ").' <span class="badge badge-secondary ml-auto">'.wrh($secnum).' </span></li>';
   if ($ibid=theme_image("stats/sections.png")) $imgtmp=$ibid; else $imgtmp="images/admin/sections.png";
   echo '
      <li class="list-group-item d-flex justify-content-start align-items-center"><img src="'.$imgtmp.'" alt="" />&nbsp;'.translate("Articles présents dans les rubriques : ").' <span class="badge badge-secondary ml-auto">'.wrh($secanum).' </span></li>';
   echo '
      <li class="list-group-item d-flex justify-content-start align-items-center"><i class="fa fa-link fa-2x text-muted"></i>&nbsp;'.translate("Liens présents dans la rubrique des liens web : ").' <span class="badge badge-secondary ml-auto">'.wrh($links).' </span></li>
      <li class="list-group-item d-flex justify-content-start align-items-center"><i class="fa fa-link fa-2x text-muted"></i>&nbsp;'.translate("Catégories dans la rubrique des liens web : ").' <span class="badge badge-secondary ml-auto">'.wrh($cat).' </span></li>';
   if ($ibid=theme_image("stats/submissions.png")) $imgtmp=$ibid; else $imgtmp="images/admin/submissions.png";
   echo '
      <li class="list-group-item d-flex justify-content-start align-items-center"><img src="'.$imgtmp.'"  alt="" />&nbsp;'.translate("Article en attente d'édition : ").' <span class="badge badge-secondary ml-auto">'.wrh($subnum).' </span></li>
      <li class="list-group-item d-flex justify-content-start align-items-center"><i class="fa fa-cogs fa-2x text-muted"></i>&nbsp;Version Num <span class="badge badge-danger ml-auto">'.$Version_Num.'</span></li>
      <li class="list-group-item d-flex justify-content-start align-items-center"><i class="fa fa-cogs fa-2x text-muted"></i>&nbsp;Version Id <span class="badge badge-danger ml-auto">'.$Version_Id.'</span></li>
      <li class="list-group-item d-flex justify-content-start align-items-center"><i class="fa fa-cogs fa-2x text-muted"></i>&nbsp;Version Sub <span class="badge badge-danger ml-auto">'.$Version_Sub.'</span></li>
   </ul>
   <br />
   <p class="text-center"><a href="http://www.npds.org" >http://www.npds.org</a> - French Portal Generator Gnu/Gpl Licence</p><br />';

include("footer.php");
?>
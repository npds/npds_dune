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
if (!function_exists("Mysql_Connexion"))
   include ("mainfile.php");
include("header.php");
function generatePourcentageAndTotal($count, $total){
   $tab[] = wrh($count);
   $tab[] = substr(sprintf('%f', 100 * $count / $total), 0, 5);
   return $tab;
}

$dkn = sql_query("SELECT type, var, count FROM ".$NPDS_Prefix."counter ORDER BY type DESC");
$total = 0;
while (list($type, $var, $count) = sql_fetch_row($dkn)) {
   if ($type == "total" && $var == "hits")
      $total = $count;
   elseif ($type == "browser") {
      if ($var == "Other")
         $b_other = generatePourcentageAndTotal($count, $total);
      else
         ${strtolower($var)} = generatePourcentageAndTotal($count, $total);
   } elseif ($type == "os") {
      if ($var == "Other")
         $os_other = generatePourcentageAndTotal($count, $total);
      else
         ${strtolower(str_replace('/', '', $var))} = generatePourcentageAndTotal($count, $total);
   }
}
echo '
   <h2>'.translate("Statistiques").'</h2>
   <div class="card card-body lead">
      <div>
      '.translate("Nos visiteurs ont visualisé").' <span class="badge bg-secondary">'.wrh($total).'</span> '.translate("pages depuis le").' '.$startdate.'
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
$imgtmp = theme_image('stats/explorer.gif') ?: 'images/stats/explorer.gif'; 
echo '
         <tr>
            <td><img src="'.$imgtmp.'" alt="MSIE_ico" loading="lazy"/> MSIE </td>
            <td>
               <div class="text-center small">'.$msie[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$msie[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$msie[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.$msie[0].'</td>
         </tr>';
$imgtmp = theme_image('stats/firefox.gif') ?: 'images/stats/firefox.gif'; 
echo '
         <tr>
            <td><img src="'.$imgtmp.'" alt="Mozilla_ico" loading="lazy"/> Mozilla </td>
            <td>
               <div class="text-center small">'.$netscape[1].' %</div>
                  <div class="progress bg-light">
                     <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$netscape[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$netscape[1].'%; height:1rem;"></div>
                  </div>
            </td>
            <td> '.$netscape[0].'</td>
         </tr>';
$imgtmp = theme_image('stats/opera.gif') ?: 'images/stats/opera.gif'; 
echo '
         <tr>
            <td><img src="'.$imgtmp.'" alt="Opera_ico" loading="lazy"/> Opera </td>
            <td>
               <div class="text-center small">'.$opera[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$opera[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$opera[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.$opera[0].'</td>
         </tr>';
$imgtmp = theme_image('stats/chrome.gif') ?: 'images/stats/chrome.gif'; 
echo '
         <tr>
            <td><img src="'.$imgtmp.'" alt="Chrome_ico" loading="lazy"/> Chrome </td>
            <td>
               <div class="text-center small">'.$chrome[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$chrome[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$chrome[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.$chrome[0].'</td>
         </tr>';
$imgtmp = theme_image('stats/safari.gif') ?: 'images/stats/safari.gif';
echo '
         <tr>
            <td><img src="'.$imgtmp.'" alt="Safari_ico" loading="lazy"/> Safari </td>
            <td>
               <div class="text-center small">'.$safari[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$safari[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$safari[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.$safari[0].'</td>
         </tr>';
$imgtmp = theme_image('stats/webtv.gif') ?: 'images/stats/webtv.gif';
echo '
         <tr>
            <td><img src="'.$imgtmp.'"  alt="WebTV_ico" loading="lazy"/> WebTV </td>
            <td>
               <div class="text-center small">'.$webtv[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$webtv[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$webtv[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.$webtv[0].'</td>
         </tr>';
$imgtmp = theme_image('stats/konqueror.gif') ?: 'images/stats/konqueror.gif';
echo '
         <tr>
            <td><img src="'.$imgtmp.'" alt="Konqueror_ico" loading="lazy"/> Konqueror </td>
            <td>
               <div class="text-center small">'.$konqueror[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$konqueror[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$konqueror[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.$konqueror[0].'</td>
         </tr>';
$imgtmp = theme_image('stats/lynx.gif') ?: 'images/stats/lynx.gif';
echo '
         <tr>
            <td><img src="'.$imgtmp.'" alt="Lynx_ico" loading="lazy"/> Lynx </td>
            <td>
               <div class="text-center small">'.$lynx[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$lynx[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$lynx[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.$lynx[0].'</td>
         </tr>';
$imgtmp = theme_image('stats/altavista.gif') ?: 'images/stats/altavista.gif';
echo '
         <tr>
            <td><img src="'.$imgtmp.'" alt="'.translate("Moteurs de recherche").'_ico" /> '.translate("Moteurs de recherche").' </td>
            <td>
               <div class="text-center small">'.$bot[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$bot[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$bot[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.$bot[0].'</td>
         </tr>
         <tr>
            <td><i class="fa fa-question fa-3x align-middle"></i> '.translate("Inconnu").' </td>
            <td>
               <div class="text-center small">'.$b_other[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$b_other[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$b_other[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.$b_other[0].'</td>
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
$imgtmp = theme_image('stats/windows.gif') ?: 'images/stats/windows.gif';
echo '
         <tr>
            <td ><img src="'.$imgtmp.'"  alt="Windows" loading="lazy"/>&nbsp;Windows</td>
            <td>
               <div class="text-center small">'.$windows[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$windows[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$windows[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.$windows[0].'</td>
         </tr>';
$imgtmp = theme_image('stats/linux.gif') ?: 'images/stats/linux.gif';
echo '
         <tr>
            <td ><img src="'.$imgtmp.'"  alt="Linux" loading="lazy"/>&nbsp;Linux</td>
            <td>
               <div class="text-center small">'.$linux[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$linux[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$linux[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.$linux[0].'</td>
         </tr>';
$imgtmp = theme_image('stats/mac.gif') ?: 'images/stats/mac.gif';
echo '
         <tr>
            <td><img src="'.$imgtmp.'"  alt="Mac/PPC" loading="lazy"/>&nbsp;Mac/PPC</td>
            <td>
               <div class="text-center small">'.$mac[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$mac[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$mac[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.$mac[0].'</td>
         </tr>';
$imgtmp = theme_image('stats/bsd.gif') ?: 'images/stats/bsd.gif';
echo '
         <tr>
            <td><img src="'.$imgtmp.'"  alt="FreeBSD" loading="lazy"/>&nbsp;FreeBSD</td>
            <td>
               <div class="text-center small">'.$freebsd[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$freebsd[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$freebsd[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.$freebsd[0].'</td>
         </tr>';
$imgtmp = theme_image('stats/sun.gif') ?: 'images/stats/sun.gif';
echo '
         <tr>
            <td><img src="'.$imgtmp.'"  alt="SunOS" loading="lazy"/>&nbsp;SunOS</td>
            <td>
               <div class="text-center small">'.$sunos[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$sunos[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$sunos[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.$sunos[0].'</td>
         </tr>';
$imgtmp = theme_image('stats/irix.gif') ?: 'images/stats/irix.gif';
echo '
         <tr>
            <td><img src="'.$imgtmp.'"  alt="IRIX" loading="lazy"/>&nbsp;IRIX</td>
            <td>
               <div class="text-center small">'.$irix[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$irix[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$irix[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.$irix[0].'</td>
         </tr>';
$imgtmp = theme_image('stats/be.gif') ?: 'images/stats/be.gif';
echo '
         <tr>
            <td><img src="'.$imgtmp.'" alt="BeOS" loading="lazy"/>&nbsp;BeOS</td>
            <td>
               <div class="text-center small">'.$beos[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$beos[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$beos[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.$beos[0].'</td>
         </tr>';
$imgtmp = theme_image('stats/os2.gif') ?: 'images/stats/os2.gif';
echo '
         <tr>
            <td><img src="'.$imgtmp.'" alt="OS/2" loading="lazy"/>&nbsp;OS/2</td>
            <td>
               <div class="text-center small">'.$os2[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$os2[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$os2[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.$os2[0].'</td>
         </tr>';
$imgtmp = theme_image('stats/aix.gif') ?: 'images/stats/aix.gif';
echo '
         <tr>
            <td><img src="'.$imgtmp.'" alt="AIX" loading="lazy"/>&nbsp;AIX</td>
            <td>
               <div class="text-center small">'.$aix[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$aix[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$aix[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.$aix[0].'</td>
         </tr>';
$imgtmp = theme_image('stats/android.gif') ?: 'images/stats/android.gif';
echo '
         <tr>
            <td><img src="'.$imgtmp.'" alt="Android" loading="lazy"/>&nbsp;Android</td>
            <td>
               <div class="text-center small">'.$android[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$android[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$android[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.$android[0].'</td>
         </tr>';
$imgtmp = theme_image('stats/ios.gif') ?: 'images/stats/ios.gif';
echo '
         <tr>
            <td><img src="'.$imgtmp.'" alt="Ios" loading="lazy"/> Ios</td>
            <td>
               <div class="text-center small">'.$ios[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$ios[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$ios[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.$ios[0].'</td>
         </tr>
         <tr>
            <td><i class="fa fa-question fa-3x align-middle"></i>&nbsp;'.translate("Inconnu").'</td>
            <td>
               <div class="text-center small">'.$os_other[1].' %</div>
               <div class="progress bg-light">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$os_other[1].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$os_other[1].'%; height:1rem;"></div>
               </div>
            </td>
            <td>'.$os_other[0].'</td>
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
      $T_exist = is_dir("themes/$ibix[0]") ? '' : '<span class="text-danger">'.translate("Ce fichier n'existe pas ...").'</span>';
      if ($themelist==$Default_Theme) {
         $result = sql_query("SELECT uid FROM ".$NPDS_Prefix."users WHERE theme='$themelist'");
         $themeD1 = $result ? sql_num_rows($result) : 0 ;
         $result = sql_query("SELECT uid FROM ".$NPDS_Prefix."users WHERE theme=''");
         $themeD2 = $result ? sql_num_rows($result) : 0 ;
         echo '
            <tr>
               <td>'.$themelist.' <b>('.translate("par défaut").')</b></td>
               <td><b>'.wrh(($themeD1+$themeD2)).'</b></td>
               <td>'.$T_exist.'</td>
            </tr>';
      } else {
         $result = sql_query("SELECT uid FROM ".$NPDS_Prefix."users WHERE theme='$themelist'");
         $themeU = $result ? sql_num_rows($result) : 0 ;
         echo '
            <tr>';
         echo substr($ibix[0],-3)=="_sk" ? '
               <td>'.$themelist.'</td>': '
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
$unum = $result ? sql_num_rows($result)-1 : 0;
$result = sql_query("SELECT groupe_id FROM ".$NPDS_Prefix."groupes");
$gnum = $result ? sql_num_rows($result) : 0;
$result = sql_query("SELECT sid FROM ".$NPDS_Prefix."stories");
$snum = $result ? sql_num_rows($result) : 0;
$result = sql_query("SELECT aid FROM ".$NPDS_Prefix."authors");
$anum = $result ? sql_num_rows($result) : 0;
$result = sql_query("SELECT post_id FROM ".$NPDS_Prefix."posts WHERE forum_id<0");
$cnum = $result ? sql_num_rows($result) : 0;
$result = sql_query("SELECT secid FROM ".$NPDS_Prefix."sections");
$secnum = $result ? sql_num_rows($result) : 0;
$result = sql_query("SELECT artid FROM ".$NPDS_Prefix."seccont");
$secanum = $result ? sql_num_rows($result) : 0;
$result = sql_query("SELECT qid FROM ".$NPDS_Prefix."queue");
$subnum = $result ? sql_num_rows($result) : 0;
$result = sql_query("SELECT topicid FROM ".$NPDS_Prefix."topics");
$tnum = $result ? sql_num_rows($result) : 0;
$result = sql_query("SELECT lid FROM ".$NPDS_Prefix."links_links");
$links = $result ? sql_num_rows($result) : 0;
$result = sql_query("SELECT cid FROM ".$NPDS_Prefix."links_categories");
$cat1 = $result ? sql_num_rows($result) : 0;
$result = sql_query("SELECT sid FROM ".$NPDS_Prefix."links_subcategories");
$cat2 = $result ? sql_num_rows($result) : 0;
$cat = $cat1+$cat2;

echo '
   <h3 class="my-4">'.translate("Statistiques diverses").'</h3>
   <ul class="list-group">
      <li class="list-group-item d-flex justify-content-start align-items-center"><i class="fa fa-user fa-2x text-body-secondary me-1"></i>'.translate("Utilisateurs enregistrés").' <span class="badge bg-secondary ms-auto">'.wrh($unum).' </span></li>
      <li class="list-group-item d-flex justify-content-start align-items-center"><i class="fa fa-users fa-2x text-body-secondary me-1"></i>'.translate("Groupe").' <span class="badge bg-secondary ms-auto">'.wrh($gnum).' </span></li>
      <li class="list-group-item d-flex justify-content-start align-items-center"><i class="fa fa-user-edit fa-2x text-body-secondary me-1"></i>'.translate("Auteurs actifs").' <span class="badge bg-secondary ms-auto">'.wrh($anum).' </span></li>';
$imgtmp = theme_image('stats/postnew.png') ?: 'images/admin/postnew.png';
echo '
      <li class="list-group-item d-flex justify-content-start align-items-center"><img class="me-1" src="'.$imgtmp.'" alt="" loading="lazy"/>'.translate("Articles publiés").' <span class="badge bg-secondary ms-auto">'.wrh($snum).' </span></li>';
$imgtmp = theme_image('stats/topicsman.png') ?: 'images/admin/topicsman.png';
echo '
      <li class="list-group-item d-flex justify-content-start align-items-center"><img class="me-1" src="'.$imgtmp.'" alt="" loading="lazy"/>'.translate("Sujets actifs").' <span class="badge bg-secondary ms-auto">'.wrh($tnum).' </span></li>
      <li class="list-group-item d-flex justify-content-start align-items-center"><i class="fa fa-comments fa-2x text-body-secondary me-1"></i>'.translate("Commentaires").' <span class="badge bg-secondary ms-auto">'.wrh($cnum).' </span></li>';
$imgtmp = theme_image('stats/sections.png') ?: 'images/admin/sections.png';
echo '
      <li class="list-group-item d-flex justify-content-start align-items-center"><img class="me-1" src="'.$imgtmp.'" alt="" loading="lazy"/>'.translate("Rubriques spéciales").' <span class="badge bg-secondary ms-auto">'.wrh($secnum).' </span></li>';
$imgtmp = theme_image('stats/sections.png') ?: 'images/admin/sections.png';
echo '
      <li class="list-group-item d-flex justify-content-start align-items-center"><img class="me-1" src="'.$imgtmp.'" alt="" loading="lazy"/>'.translate("Articles présents dans les rubriques").' <span class="badge bg-secondary ms-auto">'.wrh($secanum).' </span></li>';
echo '
      <li class="list-group-item d-flex justify-content-start align-items-center"><i class="fa fa-link fa-2x text-body-secondary me-1"></i>'.translate("Liens présents dans la rubrique des liens web").' <span class="badge bg-secondary ms-auto">'.wrh($links).' </span></li>
      <li class="list-group-item d-flex justify-content-start align-items-center"><i class="fa fa-link fa-2x text-body-secondary me-1"></i>'.translate("Catégories dans la rubrique des liens web").' <span class="badge bg-secondary ms-auto">'.wrh($cat).' </span></li>';
$imgtmp = theme_image('stats/submissions.png') ?: 'images/admin/submissions.png';
echo '
      <li class="list-group-item d-flex justify-content-start align-items-center"><img class="me-1" src="'.$imgtmp.'"  alt="" />'.translate("Article en attente d'édition").' <span class="badge bg-secondary ms-auto">'.wrh($subnum).' </span></li>
      <li class="list-group-item d-flex justify-content-start align-items-center"><i class="fa fa-cogs fa-2x text-body-secondary me-1"></i>Version Num <span class="badge bg-danger ms-auto">'.$Version_Num.'</span></li>
      <li class="list-group-item d-flex justify-content-start align-items-center"><i class="fa fa-cogs fa-2x text-body-secondary me-1"></i>Version Id <span class="badge bg-danger ms-auto">'.$Version_Id.'</span></li>
      <li class="list-group-item d-flex justify-content-start align-items-center"><i class="fa fa-cogs fa-2x text-body-secondary me-1"></i>Version Sub <span class="badge bg-danger ms-auto">'.$Version_Sub.'</span></li>
   </ul>
   <br />
   <p class="text-center"><a href="https://www.npds.org" >https://www.npds.org</a> - French Portal Generator Gnu/Gpl Licence</p><br />';
include("footer.php");
?>
<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2018 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],'admin.php'))
   include("admin/die.php");
if (!function_exists("Mysql_Connexion"))
   include ("mainfile.php");
include("functions.php");
include("auth.php");

$f_meta_nom ='abla';
$f_titre = translate("Administration BlackBoard");
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
global $language;
$hlpfile = '';

global $admin;
if ($admin) {
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead($f_meta_nom, $f_titre, $adminimg);

   global $startdate;
   list($membres,$totala,$totalb,$totalc,$totald,$totalz)=req_stat();
   //LNL Email in outside table
   $result=sql_query("SELECT email FROM ".$NPDS_Prefix."lnl_outside_users");
   if ($result) {$totalnl = sql_num_rows($result);} else {$totalnl = "0";}

   include("abla.log.php");
   $timex=time()-$xdate;
   if ($timex>=86400) {
      $timex=round($timex/86400).' '.translate("Day(s)");
   } elseif ($timex>=3600) {
      $timex=round($timex/3600).' '.translate("Hour(s)");
   } elseif ($timex>=60) {
      $timex=round($timex/60).' '.translate("Minut(s)");
   } else {
      $timex=$timex.' '.translate("Second(s)");
   }
   echo '
   <hr />
   <p class="lead mb-3">'.translate("General Stats").' - '.translate("Past Stat").' : '.$timex.' </p>
   <table class="mb-2" data-toggle="table" data-classes="table mb-2">
      <thead class="collapse thead-default">
         <tr>
            <th class="n-t-col-xs-9"></th>
            <th class="text-right"></th>
         </tr>
      </thead>
      <tbody>
         <tr>
            <td>'.translate("Nb of Pages").' : </td>
            <td>'.wrh($totalz).' (';
   if ($totalz>$xtotalz)
      echo '<span class="text-success">+';
   elseif ($totalz<$xtotalz)
      echo '<span class="text-danger">';
   else
       echo '<span>';
   echo wrh($totalz-$xtotalz).'</span>)</td>
         </tr>
         <tr>
            <td>'.translate("Nb of members").' : </td>
            <td>'.wrh($membres).' (';
   if ($membres>$xmembres)
      echo '<span class="text-success">+';
   elseif ($membres<$xmembres)
      echo '<span class="text-danger">';
   else
      echo '<span>';
   echo wrh($membres-$xmembres).'</span>)</td>
         </tr>
         <tr>
            <td>'.translate("Nb of articles").' : </td>
            <td>'.wrh($totala).' (';
   if ($totala>$xtotala)
      echo '<span class="text-success">+';
   elseif ($totala<$xtotala)
      echo '<span class="text-danger">';
   else
      echo '<span>';
   echo wrh($totala-$xtotala).'</span>)</td>
         </tr>
         <tr>
            <td>'.translate("Nb of forums").' : </td>
            <td>'.wrh($totalc).' (';
   if ($totalc>$xtotalc)
      echo '<span class="text-success">+';
   elseif ($totalc<$xtotalc)
      echo '<span class="text-danger">';
   else
      echo '<span>';
   echo wrh($totalc-$xtotalc).'</span>)</td>
         </tr>
         <tr>
            <td>'.translate("Nb of topics").' : </td>
            <td>'.wrh($totald).' (';
   if ($totald>$xtotald)
      echo '<span class="text-success">+';
   elseif ($totald<$xtotald)
      echo '<span class="text-danger">';
   else
      echo '<span>';
   echo wrh($totald-$xtotald).'</span>)</td>
         </tr>
         <tr>
            <td>'.translate("Nb of reviews").' : </td>
            <td>'.wrh($totalb).' (';
   if ($totalb>$xtotalb)
      echo '<span class="text-success">+';
   elseif ($totalb<$xtotalb)
      echo '<span class="text-danger">';
   else
      echo '<span>';
   echo wrh($totalb-$xtotalb).'</span>)</td>
         </tr>
         <tr>
            <td>'.translate("Nb Outside Users for LNL").' : </td>
            <td>'.wrh($totalnl).' (';
   if ($totalnl>$xtotalnl)
      echo '<span class="text-success">+';
   elseif ($totalnl<$xtotalnl)
      echo '<span class="text-danger">';
   else
      echo '<span>';
   echo wrh($totalnl-$xtotalnl).'</span>)</td>
         </tr>';

    $xfile="<?php\n";
    $xfile.="\$xdate = ".time().";\n";
    $xfile.="\$xtotalz = $totalz;\n";
    $xfile.="\$xmembres = $membres;\n";
    $xfile.="\$xtotala = $totala;\n";
    $xfile.="\$xtotalc = $totalc;\n";
    $xfile.="\$xtotald = $totald;\n";
    $xfile.="\$xtotalb = $totalb;\n";
    $xfile.="\$xtotalnl = $totalnl;\n";
   echo '
      </tbody>
   </table>
   <p class="lead my-3">'.translate("Download Stats").'</p>
   <table data-toggle="table" data-classes="table">
      <thead class=" thead-default">
         <tr>
            <th class="n-t-col-xs-9"></th>
            <th class="text-right"></th>
         </tr>
      </thead>
      <tbody>';
   $num_dow=0;
   $result = sql_query("SELECT dcounter, dfilename FROM ".$NPDS_Prefix."downloads");
   while(list($dcounter, $dfilename) = sql_fetch_row($result)) {
      $num_dow++;
      echo '
         <tr>
            <td><span class="text-danger">';
      if (array_key_exists($num_dow, $xdownload))
         echo $xdownload[$num_dow][1];
      echo '</span> -/- '.$dfilename.'</td>
            <td><span class="text-danger">';
      if (array_key_exists($num_dow, $xdownload))
            echo $xdownload[$num_dow][2];
      echo '</span> -/- '.$dcounter.'</td>
         </tr>';
      $xfile.="\$xdownload[$num_dow][1] = \"$dfilename\";\n";
      $xfile.="\$xdownload[$num_dow][2] = \"$dcounter\";\n";
   }
   echo '
      </tbody>
   </table>
   <p class="lead my-3">Forums</p>
   <table class="table table-bordered table-sm" data-classes="table">
      <thead class="">
         <tr>
            <th>'.translate("Forum").'</th>
            <th class="n-t-col-xs-2 text-center">'.translate("Topics").'</th>
            <th class="n-t-col-xs-2 text-center">'.translate("Posts").'</th>
            <th class="n-t-col-xs-3 text-right">'.translate("Last Posts").'</th>
         </tr>
      </thead>';
   $result = sql_query("SELECT * FROM ".$NPDS_Prefix."catagories ORDER BY cat_id");
   $num_for=0;
   while (list($cat_id, $cat_title) = sql_fetch_row($result)) {
      $sub_sql = "SELECT f.*, u.uname FROM ".$NPDS_Prefix."forums f, ".$NPDS_Prefix."users u WHERE f.cat_id = '$cat_id' AND f.forum_moderator = u.uid ORDER BY forum_index,forum_id";
      if (!$sub_result = sql_query($sub_sql)) forumerror('0022');
      if ($myrow = sql_fetch_assoc($sub_result)) {
         echo '
         <tbody>
            <tr>
               <td class="table-active" colspan="4">'.stripslashes($cat_title).'</td>
            </tr>';
         do {
            $num_for++;
            $last_post = get_last_post($myrow['forum_id'], 'forum', 'infos',true);
            echo '
            <tr>';
            $total_topics = get_total_topics($myrow['forum_id']);
            $name = stripslashes($myrow['forum_name']);
            $xfile.="\$xforum[$num_for][1] = \"$name\";\n";
            $xfile.="\$xforum[$num_for][2] = $total_topics;\n";
            $desc = stripslashes($myrow['forum_desc']);
            echo '
               <td><a tabindex="0" role="button" data-trigger="focus" data-toggle="popover" data-placement="right" data-content="'.$desc.'"><i class="fa fa-lg fa-file-text-o mr-2"></i></a><a href="viewforum.php?forum='.$myrow['forum_id'].'" ><span class="text-danger">';
            if (array_key_exists($num_for, $xforum))
               echo $xforum[$num_for][1];
            echo '</span> -/- '.$name.' </a></td>
               <td class="text-center"><span class="text-danger">';
            if (array_key_exists($num_for, $xforum))
               echo $xforum[$num_for][2];
            echo '</span> -/- '.$total_topics.'</td>';
            $total_posts = get_total_posts($myrow['forum_id'], "", "forum",false);
            $xfile.="\$xforum[$num_for][3] = $total_posts;\n";
            echo '
            <td class="text-center"><span class="text-danger">';
            if (array_key_exists($num_for, $xforum))
               echo $xforum[$num_for][3];
            echo '</span> -/- '.$total_posts.'</td>
            <td class="text-right small">'.$last_post.'</td>';
         } while($myrow = sql_fetch_assoc($sub_result));
      }
   }
   echo '
         </tr>
      </tbody>
   </table>';

   $file = fopen("abla.log.php", "w");
   $xfile.="?>\n";
   fwrite($file, $xfile);
   fclose($file);

   adminfoot('','','','');
} else
   redirect_url("index.php");
?>

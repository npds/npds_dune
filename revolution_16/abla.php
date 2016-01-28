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
include("functions.php");
include("auth.php");

global $admin;
if ($admin) {
    include ("header.php");
    

    global $startdate;
    list($membres,$totala,$totalb,$totalc,$totald,$totalz)=req_stat();
    //LNL Email in outside table
    $result=sql_query("SELECT email FROM ".$NPDS_Prefix."lnl_outside_users");
    if ($result) {$totalnl = sql_num_rows($result);} else {$totalnl = "0";}

    echo '<h2>'.translate("Administration BlackBoard").'</h2>';
    echo '<p class="lead"><a href="admin.php">'.translate("Administration Tools").'</a></p>';

    include("abla.log.php");
    $timex=time()-$xdate;
    if ($timex>=86400) {
       $timex=round($timex/86400)." ".translate("Day(s)");
    } elseif ($timex>=3600) {
       $timex=round($timex/3600)." ".translate("Hour(s)");
    } elseif ($timex>=60) {
       $timex=round($timex/60)." ".translate("Minut(s)");
    } else {
       $timex=$timex." ".translate("Second(s)");
    }
    echo "<table class=\"table table-bordered\"><thead><tr class=\"info\"><th colspan=\"2\">".translate("General Stats")." - ".translate("Past Stat")." : $timex </th></tr></thead>";
    echo "<tbody><tr><td>".translate("Nb of Pages")." : </td><td>".wrh($totalz)." (";
    if ($totalz>$xtotalz)
       echo "<span class=\"text-success\">+";
    elseif ($totalz<$xtotalz)
       echo "<span class=\"text-danger\">";
    else
       echo "<span>";
    echo wrh($totalz-$xtotalz)."</span>)</td></tr>";

    echo "<tr><td>".translate("Nb of members")." : </td><td>".wrh($membres)." (";
    if ($membres>$xmembres)
       echo "<span class=\"text-success\">+";
    elseif ($membres<$xmembres)
       echo "<span class=\"text-danger\">";
    else
       echo "<span>";
    echo wrh($membres-$xmembres)."</span>)</td></tr>";

    echo "<tr><td>".translate("Nb of articles")." : </td><td>".wrh($totala)." (";
    if ($totala>$xtotala)
       echo "<span class=\"text-success\">+";
    elseif ($totala<$xtotala)
       echo "<span class=\"text-danger\">";
    else
       echo "<span>";
    echo wrh($totala-$xtotala)."</span>)</td></tr>";

    echo "<tr><td>".translate("Nb of forums")." : </td><td>".wrh($totalc)." (";
    if ($totalc>$xtotalc)
       echo "<span class=\"text-success\">+";
    elseif ($totalc<$xtotalc)
       echo "<span class=\"text-danger\">";
    else
       echo "<span>";
    echo wrh($totalc-$xtotalc)."</span>)</td></tr>";

    echo "<tr><td>".translate("Nb of topics")." : </td><td>".wrh($totald)." (";
    if ($totald>$xtotald)
       echo "<span class=\"text-success\">+";
    elseif ($totald<$xtotald)
       echo "<span class=\"text-danger\">";
    else
       echo "<span>";
    echo wrh($totald-$xtotald)."</span>)</td></tr>";

    echo "<tr><td>".translate("Nb of reviews")." : </td><td>".wrh($totalb)." (";
    if ($totalb>$xtotalb)
       echo "<span class=\"text-success\">+";
    elseif ($totalb<$xtotalb)
       echo "<span class=\"text-danger\">";
    else
       echo "<span>";
    echo wrh($totalb-$xtotalb)."</span>)</td></tr>";

    echo "<tr><td>".translate("Nb Outside Users for LNL")." : </td><td>".wrh($totalnl)." (";
    if ($totalnl>$xtotalnl)
       echo "<span class=\"text-success\">+";
    elseif ($totalnl<$xtotalnl)
       echo "<span class=\"text-danger\">";
    else
       echo "<span>";
    echo wrh($totalnl-$xtotalnl)."</span>)</td></tr>";


    $xfile="<?php\n";
    $xfile.="\$xdate = ".time().";\n";
    $xfile.="\$xtotalz = $totalz;\n";
    $xfile.="\$xmembres = $membres;\n";
    $xfile.="\$xtotala = $totala;\n";
    $xfile.="\$xtotalc = $totalc;\n";
    $xfile.="\$xtotald = $totald;\n";
    $xfile.="\$xtotalb = $totalb;\n";
    $xfile.="\$xtotalnl = $totalnl;\n";
    echo "</tbody></table><br />\n";

    echo "<table class=\"table table-bordered\"><thead><tr class=\"info\"><th colspan=\"2\">".translate("Download Stats")."</th></tr></thead>";
    $num_dow=0;
    $result = sql_query("SELECT dcounter, dfilename FROM ".$NPDS_Prefix."downloads");
    while(list($dcounter, $dfilename) = sql_fetch_row($result)) {
       
       $num_dow++;
       echo "<tbody><tr><td><span class=\"rouge\">".$xdownload[$num_dow][1]."</span> -/- $dfilename : </td><td><span class=\"rouge\">".$xdownload[$num_dow][2]."</span> -/- $dcounter</td></tr>";
       $xfile.="\$xdownload[$num_dow][1] = \"$dfilename\";\n";
       $xfile.="\$xdownload[$num_dow][2] = \"$dcounter\";\n";
    }
    echo "</tbody></table><br />\n";

    echo "<table class=\"table table-bordered\" >";
    echo "<thead><tr class=\"info\">";
    echo "<th>&nbsp;</th>";
    echo "<th>".translate("Forum")."</th>";
    echo "<th>".translate("Topics")."</th>";
    echo "<th>".translate("Posts")."</th>";
    echo "<th>".translate("Last Posts")."</th></tr></thead>";

    $result = sql_query("SELECT * FROM ".$NPDS_Prefix."catagories ORDER BY cat_id");
    $num_for=0;
    while (list($cat_id, $cat_title) = sql_fetch_row($result)) {
       $sub_sql = "SELECT f.*, u.uname FROM ".$NPDS_Prefix."forums f, ".$NPDS_Prefix."users u WHERE f.cat_id = '$cat_id' AND f.forum_moderator = u.uid ORDER BY forum_index,forum_id";
       if (!$sub_result = sql_query($sub_sql))
          forumerror('0022');
       if ($myrow = sql_fetch_assoc($sub_result)) {
          echo "<tbody><tr><td class=\"active\" colspan=\"7\">".stripslashes($cat_title)."</td></tr>";
          do {
             $num_for++;
             $last_post = get_last_post($myrow['forum_id'], "forum", "infos",true);
             echo "<tr>";
             $total_topics = get_total_topics($myrow['forum_id']);
             echo "<td><i class=\"fa fa-lg fa-file-text-o\"></i></td>";
             $name = stripslashes($myrow['forum_name']);
             $xfile.="\$xforum[$num_for][1] = \"$name\";\n";
             $xfile.="\$xforum[$num_for][2] = $total_topics;\n";
             echo "<td><a href=\"viewforum.php?forum=".$myrow['forum_id']."\" class=\"noir\"><span class=\"rouge\">".$xforum[$num_for][1]."</span> -/- $name </a>\n";
             $desc = stripslashes($myrow['forum_desc']);
             echo "<br />$desc</td>\n";
             echo "<td><span class=\"rouge\">".$xforum[$num_for][2]."</span> -/- $total_topics</td>\n";
             $total_posts = get_total_posts($myrow['forum_id'], "", "forum",false);
             $xfile.="\$xforum[$num_for][3] = $total_posts;\n";
             echo "<td><span class=\"rouge\">".$xforum[$num_for][3]."</span> -/- $total_posts</td>\n";

             echo "<td>$last_post</td>\n";
          } while($myrow = sql_fetch_assoc($sub_result));
       }
    }
    echo "</tr></tbody></table>";

    $file = fopen("abla.log.php", "w");
    $xfile.="?>\n";
    fwrite($file, $xfile);
    fclose($file);

    include ("footer.php");
} else {
    redirect_url("index.php");
}
?>

<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Admin DUNE Prototype                                                 */
/*                                                                      */
/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!stristr($_SERVER['PHP_SELF'],"admin.php")) { Access_Error(); }
$f_meta_nom ='hreferer';
$f_titre = adm_translate("Sites Référents");
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
global $language;
$hlpfile = "manuels/$language/referer.html";


function hreferer($filter) {
   global $hlpfile, $f_meta_nom, $adminimg, $admf_ext, $f_titre;
   include ("header.php");
   GraphicAdmin($hlpfile);

   adminhead ($f_meta_nom, $f_titre, $adminimg);

   settype($filter,"integer");
   if (!$filter) {$filter=2048;}
   echo '
   <h3>'.adm_translate("Qui parle de nous ?").'</h3>
   <form action="admin.php" method="post">
      <input type="hidden" name="op" value="hreferer" />
      <div class="form-group">
      <div class="row>">
         <label class="form-control-label col-sm-4" for="filter">'.adm_translate("Filtre").'</label>
         <div class="col-sm-4">
            <input type="number" class="form-control" name="filter" min="0" max="99999" value="'.$filter.'" />
         </div>
         <div class="col-sm-4 xs-hidden"></div><div class="clearfix"></div>
      </div>
   </div>
   </form>
   <table id ="tad_refe" data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa">
   <thead>
      <tr>
         <th data-sortable="true">Url</th>
         <th data-sortable="true">Hit</th>
      </tr>
   </thead>
   <tbody>';
   $hresult = sql_query("SELECT url, COUNT(url) AS TheCount, substring(url,1,$filter) AS filter FROM ".$NPDS_Prefix."referer GROUP BY filter ORDER BY TheCount DESC");
   while(list($url, $TheCount) = sql_fetch_row($hresult)) {
      echo '
      <tr>
         <td>';
      if($TheCount == 1) echo '<a href="'.$url.'" target="_blank">';
      if ($filter!=2048)
        echo '<span>'.substr($url,0,$filter).'</span><span class="text-muted">'.substr($url,$filter).'</span>';
      else
        echo $url;
      if($TheCount == 1) echo '</a>';
      echo '</a>
         </td>
         <td align="right">'.$TheCount.'</td>
      </tr>';
   }
    echo '
   </tbody>
   </table>
   <br />
   <ul class="nav nav-pills">
      <li><a href="admin.php?op=delreferer" class="text-danger">'.adm_translate("Effacer les Référants").'</a></li>
      <li><a href="admin.php?op=archreferer&amp;filter='.$filter.'">'.adm_translate("Archiver les Référants").'</a></li>
   </ul>';
   adminfoot('','','','');;
}

function delreferer() {
    global $NPDS_Prefix;

    sql_query("DELETE FROM ".$NPDS_Prefix."referer");
    Header("Location: admin.php?op=AdminMain");
}

function archreferer($filter) {
    global $NPDS_Prefix;

    $file = fopen("slogs/referers.log", "w");
    $content = "===================================================\n";
    $content .="Date : ".date("d-m-Y")."-/- NPDS - HTTP Referers\n";
    $content .= "===================================================\n";
    $result=sql_query("SELECT url FROM ".$NPDS_Prefix."referer");
    while(list($url)= sql_fetch_row($result)) {
       $content .= "$url\n";
    }
    $content .= "===================================================\n";
    fwrite($file, $content);
    fclose($file);
    Header("Location: admin.php?op=hreferer&filter=$filter");
}

switch ($op) {
   case "hreferer":
        hreferer($filter);
        break;

   case "archreferer":
        archreferer($filter);
        break;

   case "delreferer":
        delreferer();
        break;
}
?>
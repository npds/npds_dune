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
if (!stristr($_SERVER['PHP_SELF'],"admin.php")) Access_Error();
$f_meta_nom ='Ephemerids';
$f_titre = adm_translate("Ephémérides");
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
global $language;
$hlpfile = "manuels/$language/ephem.html";

function Ephemerids() {
   global $hlpfile, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   $nday = '1';
   echo '
   <hr />
   <h3>'.adm_translate("Ajouter un éphéméride").'</h3>
   <form action="admin.php" method="post">
      <div class="mb-3 row">
         <div class="col-4">
            <label class="col-form-label" for="did">'.adm_translate("Jour").'</label>
            <select class="form-select" id="did" name="did">';
   while ($nday<=31) {
      echo '
               <option name="did">'.$nday.'</option>';
      $nday++;
   }
   echo '
            </select>
         </div>';
    $nmonth = "1";
    echo '
         <div class="col-4">
            <label class="col-form-label" for="mid">'.adm_translate("Mois").'</label>
            <select class="form-select" id="mid" name="mid">';
    while ($nmonth<=12) {
       echo '
               <option name="mid">'.$nmonth.'</option>';
       $nmonth++;
    }
    echo '
            </select>
         </div>
         <div class="col-4">
            <label class="col-form-label" for="yid">'.adm_translate("Année").'</label>
            <input class="form-control" type="number" id="yid" name="yid" maxlength="4" size="5" />
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-12" for="content">'.adm_translate("Description de l'éphéméride").'</label>
         <div class="col-sm-12">
            <textarea name="content" class="form-control" cols="55" rows="10"></textarea>
         </div>
      </div>
      <div class="mb-3 row">
         <div class="col-sm-12">
            <button class="btn btn-primary" type="submit">'.adm_translate("Envoyer").'</button>
            <input type="hidden" name="op" value="Ephemeridsadd" />
         </div>
      </div>
   </form>
   <hr />
   <h3>'.adm_translate("Maintenance des Ephémérides (Editer/Effacer)").'</h3>
   <form action="admin.php" method="post">';
   $nday = "1";
   echo '
      <div class="mb-3 row">
         <div class="col-4">
            <label class="col-form-label" for="did">'.adm_translate("Jour").'</label>
            <select class="form-select" id="did" name="did">';
   while ($nday<=31) {
      echo '
               <option name="did">'.$nday.'</option>';
      $nday++;
   }
    echo '
            </select>
         </div>';
    $nmonth = "1";
    echo '
         <div class="col-4">
            <label class="col-form-label" for="mid">'.adm_translate("Mois").'</label>
            <select class="form-select" id="mid" name="mid">';
    while ($nmonth<=12) {
       echo '
               <option name="mid">'.$nmonth.'</option>';
       $nmonth++;
    }
    echo '
            </select>
         </div>
      </div>
      <div class="mb-3 row">
         <div class="col-sm-12">
            <input type="hidden" name="op" value="Ephemeridsmaintenance" />
            <button class="btn btn-primary" type="submit">'.adm_translate("Editer").'</button>
         </div>
      </div>
   </form>';
    adminfoot('','','','');
}

function Ephemeridsadd($did, $mid, $yid, $content) {
   global $NPDS_Prefix, $f_meta_nom;
   $content = stripslashes(FixQuotes($content)."");
   sql_query("INSERT into ".$NPDS_Prefix."ephem VALUES (NULL, '$did', '$mid', '$yid', '$content')");
   Header("Location: admin.php?op=Ephemerids");
}

function Ephemeridsmaintenance($did, $mid) {
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   $resultX=sql_query("SELECT eid, did, mid, yid, content FROM ".$NPDS_Prefix."ephem WHERE did='$did' AND mid='$mid' ORDER BY yid ASC");
   if (!sql_num_rows($resultX)) header("location: admin.php?op=Ephemerids");
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '
   <hr />
   <h3>'.adm_translate("Maintenance des Ephémérides").'</h3>
   <table data-toggle="table" data-striped="true" data-mobile-responsive="true" data-search="true" data-show-toggle="true" data-icons="icons" data-icons-prefix="fa">
      <thead>
         <tr>
            <th class="n-t-col-xs-2" data-sortable="true" data-halign="center" data-align="right" >'.adm_translate('Année').'</th>
            <th data-halign="center" >'.adm_translate('Description').'</th>
            <th class="n-t-col-xs-2" data-halign="center" data-align="center" >'.adm_translate('Fonctions').'</th>
         </tr>
      </thead>
      <tbody>';

   while(list($eid, $did, $mid, $yid, $content) = sql_fetch_row($resultX)) {
      echo '
         <tr>
            <td>'.$yid.'</td>
            <td>'.aff_langue($content).'</td>
            <td><a href="admin.php?op=Ephemeridsedit&amp;eid='.$eid.'&amp;did='.$did.'&amp;mid='.$mid.'" title="'.adm_translate("Editer").'" data-bs-toggle="tooltip" ><i class="fa fa-edit fa-lg me-2"></i></a>&nbsp;<a href="admin.php?op=Ephemeridsdel&amp;eid='.$eid.'&amp;did='.$did.'&amp;mid='.$mid.'" title="'.adm_translate("Effacer").'" data-bs-toggle="tooltip"><i class="fas fa-trash fa-lg text-danger"></i></a>
         </tr>';
   }
   echo '
        </tbody>
    </table>';
    adminfoot('','','','');
}

function Ephemeridsdel($eid, $did, $mid) {
   global $NPDS_Prefix;
   sql_query("DELETE FROM ".$NPDS_Prefix."ephem WHERE eid='$eid'");
   Header("Location: admin.php?op=Ephemeridsmaintenance&did=$did&mid=$mid");
}

function Ephemeridsedit($eid, $did, $mid) {
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   $result=sql_query("SELECT yid, content FROM ".$NPDS_Prefix."ephem WHERE eid='$eid'");
   list($yid, $content) = sql_fetch_row($result);
   echo '
   <hr />
   <h3>'.adm_translate("Editer éphéméride").'</h3>
   <form action="admin.php" method="post">
      <div class="mb-3 row">
          <label class="col-form-label col-sm-3" for="yid">'.adm_translate("Année").'</label>
          <div class="col-sm-9">
             <input class="form-control" type="number" name="yid" value="'.$yid.'" max="2500" />
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-12" for="content">'.adm_translate("Description de l'éphéméride").'</label>
         <div class="col-sm-12">
            <textarea name="content" id="content" class="form-control" rows="10">'.$content.'</textarea>
         </div>
      </div>
      <div class="mb-3 row">
         <div class="col-sm-12">
            <input type="hidden" name="did" value="'.$did.'" />
            <input type="hidden" name="mid" value="'.$mid.'" />
            <input type="hidden" name="eid" value="'.$eid.'" />
            <input type="hidden" name="op" value="Ephemeridschange" />
            <button class="btn btn-primary" type="submit">'.adm_translate("Envoyer").'</button>
         </div>
      </div>
   </form>';
   adminfoot('','','','');
}

function Ephemeridschange($eid, $did, $mid, $yid, $content) {
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   $content = stripslashes(FixQuotes($content)."");
   sql_query("UPDATE ".$NPDS_Prefix."ephem SET yid='$yid', content='$content' WHERE eid='$eid'");
   Header("Location: admin.php?op=Ephemeridsmaintenance&did=$did&mid=$mid");
}

switch ($op) {
   case 'Ephemeridsedit':
        Ephemeridsedit($eid, $did, $mid);
        break;
   case 'Ephemeridschange':
        Ephemeridschange($eid, $did, $mid, $yid, $content);
        break;
   case 'Ephemeridsdel':
        Ephemeridsdel($eid, $did, $mid);
        break;
   case 'Ephemeridsmaintenance':
        Ephemeridsmaintenance($did, $mid);
        break;
   case 'Ephemeridsadd':
        Ephemeridsadd($did, $mid, $yid, $content);
        break;
   case 'Ephemerids':
        Ephemerids();
        break;
}
?>
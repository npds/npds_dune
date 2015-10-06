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
if (!stristr($_SERVER['PHP_SELF'],"admin.php")) { Access_Error(); }
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
    echo '<h3>'.adm_translate("Ajouter un Epheméride").'</h3>
    <form action="admin.php" method="post">';
    $nday = "1";
    echo '<div class="form-group">
        <div class="row">
            <div class="col-xs-4">
                <label class="form-control-label" for="did">'.adm_translate("Jour : ").'</label>
                <select class="form-control" id="did" name="did">';
    while ($nday<=31) {
       echo '<option name="did">'.$nday.'</option>';
       $nday++;
    }
    echo '</select></div>';
    $nmonth = "1";
    echo '<div class="col-xs-4">
    <label class="form-control-label" for="mid">'.adm_translate("Mois : ").'</label>
    <select class="form-control" id="mid" name="mid">';
    while ($nmonth<=12) {
       echo '<option name="mid">'.$nmonth.'</option>';
       $nmonth++;
    }
    echo '
    </select></div>
    <div class="col-xs-4">
        <label class="form-control-label" for="yid">'.adm_translate("Année : ").'</label>
        <input class="form-control" type="number" id="yid" name="yid" maxlength="4" size="5" />
    </div>
    </div>
    
    <div class="form-group">
        <label class="form-control-label" for="content">'.adm_translate("Description de l'Ephéméride : ").'</label>
        <textarea name="content" class="form-control" cols="55" rows="10"></textarea>
    </div>
    <button class="btn btn-primary" type="submit">'.adm_translate("Envoyer").'</button>
    <input type="hidden" name="op" value="Ephemeridsadd" />
    </form>
    <h3>'.adm_translate("Maintenance des Ephémérides (Editer/Effacer)").'</h3>
    <form action="admin.php" method="post">';
    $nday = "1";
    echo '
    <div class="form-group">
        <div class="row">
            <div class="col-xs-4">
                <label class="form-control-label" for="did">'.adm_translate("Jour : ").'</label>
                <select class="form-control" id="did" name="did">';
    while ($nday<=31) {
       echo '<option name="did">'.$nday.'</option>';
       $nday++;
    }
    echo '</select></div>';
    $nmonth = "1";
    echo '
    <div class="col-xs-4">
        <label class="form-control-label" for="mid">'.adm_translate("Mois : ").'</label>
        <select class="form-control" id="mid" name="mid">';
    while ($nmonth<=12) {
       echo '<option name="mid">'.$nmonth.'</option>';
       $nmonth++;
    }
    echo '</select></div></div></div>
     <input type="hidden" name="op" value="Ephemeridsmaintenance" />
    <button class="btn btn-primary" type="submit">'.adm_translate("Editer").'</button>
    </form>';
//    adminfoot('','','','');
}

function Ephemeridsadd($did, $mid, $yid, $content) {
    global $NPDS_Prefix, $f_meta_nom;

    $content = stripslashes(FixQuotes($content)."");
    sql_query("insert into ".$NPDS_Prefix."ephem values (NULL, '$did', '$mid', '$yid', '$content')");
    Header("Location: admin.php?op=Ephemerids");
}

function Ephemeridsmaintenance($did, $mid) {
    global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
    $resultX=sql_query("select eid, did, mid, yid, content from ".$NPDS_Prefix."ephem where did='$did' AND mid='$mid' ORDER BY yid ASC");
    if (!sql_num_rows($resultX))
       header("location: admin.php?op=Ephemerids");
    include ("header.php");
    GraphicAdmin($hlpfile);
    adminhead ($f_meta_nom, $f_titre, $adminimg);
    echo '<h3>'.adm_translate("Maintenance des Ephémérides").'</h3>
    <table data-toggle="table" 
        data-striped="true" 
        data-search="true"
        data-show-toggle="true" data-icons="icons" data-icons-prefix="fa">
        <thead>
        <tr>
            <th data-sortable="true">'.adm_translate('Année').'</th>
            <th>'.adm_translate('Description').'</th>
            <th>'.adm_translate('Fonctions').'</th>
        </tr>
        </thead>
        <tbody>'."\n";

    while(list($eid, $did, $mid, $yid, $content) = sql_fetch_row($resultX)) {
       $rowcolor = tablos();
       echo '<tr>
            <td>'.$yid.'</td>
            <td>'.aff_langue($content).'</td>
            <td align="right">[ <a href="admin.php?op=Ephemeridsedit&amp;eid='.$eid.'&amp;did='.$did.'&amp;mid='.$mid.'" class="noir">'.adm_translate("Editer").'</a> | <a href="admin.php?op=Ephemeridsdel&amp;eid='.$eid.'&amp;did='.$did.'&amp;mid='.$mid.'" class="rouge">'.adm_translate("Effacer").'</a> ]<br />
       </tr>';
    }
    echo '
        </tbody>
    </table>';
    adminfoot('','','','');
}

function Ephemeridsdel($eid, $did, $mid) {
    global $NPDS_Prefix;

    sql_query("delete from ".$NPDS_Prefix."ephem where eid='$eid'");
    Header("Location: admin.php?op=Ephemeridsmaintenance&did=$did&mid=$mid");
}

function Ephemeridsedit($eid, $did, $mid) {
    global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
    include ("header.php");
    GraphicAdmin($hlpfile);
    adminhead ($f_meta_nom, $f_titre, $adminimg);
    $result=sql_query("select yid, content from ".$NPDS_Prefix."ephem where eid='$eid'");
    list($yid, $content) = sql_fetch_row($result);
    echo '<h3>'.adm_translate("Editer Ephéméride").'</h3>
    <form action="admin.php" method="post">
        <div class="form-group">
            <label class="form-control-label" for="yid">'.adm_translate("Année : ").'</label>
            <input class="form-control" type="number" name="yid" value="'.$yid.'" max="2500" />
        </div>
        <div class="form-group">
            <label class="form-control-label" for="content">'.adm_translate("Description de l'Ephéméride : ").'</label>
            <textarea name="content" id="content" cols="55" class="textbox" rows="10">'.$content.'</textarea>
        </div>
        <input type="hidden" name="did" value="'.$did.'" />
        <input type="hidden" name="mid" value="'.$mid.'" />
        <input type="hidden" name="eid" value="'.$eid.'" />
        <input type="hidden" name="op" value="Ephemeridschange" />
        <button class="btn btn-primary" type="submit">'.adm_translate("Envoyer").'</button>
    </form>';
    include ('footer.php');
}

function Ephemeridschange($eid, $did, $mid, $yid, $content) {
    global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;

    $content = stripslashes(FixQuotes($content)."");
    sql_query("update ".$NPDS_Prefix."ephem set yid='$yid', content='$content' where eid='$eid'");
    Header("Location: admin.php?op=Ephemeridsmaintenance&did=$did&mid=$mid");
}

switch ($op) {
    case "Ephemeridsedit":
         Ephemeridsedit($eid, $did, $mid);
         break;

    case "Ephemeridschange":
         Ephemeridschange($eid, $did, $mid, $yid, $content);
         break;

    case "Ephemeridsdel":
         Ephemeridsdel($eid, $did, $mid);
         break;

    case "Ephemeridsmaintenance":
         Ephemeridsmaintenance($did, $mid);
         break;

    case "Ephemeridsadd":
         Ephemeridsadd($did, $mid, $yid, $content);
         break;

    case "Ephemerids":
         Ephemerids();
         break;
}
?>
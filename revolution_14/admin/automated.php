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
$f_meta_nom ='autoStory';
$f_titre = adm_translate("Articles programmés");
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit

include ("publication.php");

global $language;
$hlpfile = "manuels/$language/automated.html";

function puthome($ihome) {
    echo "<br /><b>".adm_translate("Publier dans la racine ?")."</b>&nbsp;&nbsp;";
    $sel1 = "checked=\"checked\"";
    $sel2 = "";
    if ($ihome == 1) {
        $sel1 = "";
        $sel2 = "checked=\"checked\"";
    }
    echo "<input type=\"radio\" name=\"ihome\" value=\"0\" $sel1 />".adm_translate("Oui")."&nbsp;";
    echo "<input type=\"radio\" name=\"ihome\" value=\"1\" $sel2 />".adm_translate("Non");
    echo "&nbsp;&nbsp; <span class=\"noir\">[ ".adm_translate("Ne s'applique que si la catégorie : <b>'Articles'</b> n'est pas sélectionnée.")." ]</span><br /><br />";

    $sel1 = "";
    $sel2 = "checked=\"checked\"";
    echo "<span class=\"rouge\"><b>".adm_translate("Seulement aux membres")."</b></span> :";
    if ($ihome<0) {
       $sel1 = "checked=\"checked\"";
       $sel2 = "";
    }
    if (($ihome>1) and ($ihome<=127)) {
       $Mmembers=$ihome;
       $sel1 = "checked=\"checked\"";
       $sel2 = "";
    }
    echo "<input type=\"radio\" name=\"members\" value=\"1\" $sel1 />".adm_translate("Oui")."&nbsp; /
    ".adm_translate("Groupe")." : ";
    // ---- Groupes
    $mX=liste_group();
    $tmp_groupe="";
    while (list($groupe_id, $groupe_name)=each($mX)) {
       if ($groupe_id=="0") {$groupe_id="";}
       if ($Mmembers==$groupe_id) {$sel3="selected=\"selected\"";} else {$sel3="";}
       $tmp_groupe.="<option value=\"$groupe_id\" $sel3>$groupe_name</option>\n";
      $nbg++;
    }
    echo "<select class=\"textbox_standard\" name=\"Mmembers\">".$tmp_groupe."</select>&nbsp;";
    // ---- Groupes
    echo "<input type=\"radio\" name=\"members\" value=\"0\" $sel2 />".adm_translate("Non")."<br />";
}

function SelectCategory($cat) {
    global $NPDS_Prefix;

    $selcat = sql_query("select catid, title from ".$NPDS_Prefix."stories_cat");
    echo " <b>".adm_translate("Catégorie")."</b> ";
    echo "<select class=\"textbox_standard\" name=\"catid\">";
    if ($cat == 0) {
       $sel = "selected=\"selected\"";
    } else {
       $sel = "";
    }
    echo "<option name=\"catid\" value=\"0\" $sel>".adm_translate("Articles")."</option>";
    while(list($catidX, $title) = sql_fetch_row($selcat)) {
       if ($catidX==$cat) {
          $sel = "selected=\"selected\"";
       } else {
          $sel = "";
       }
       echo "<option name=\"catid\" value=\"$catidX\" $sel>".aff_langue($title)."</option>";
    }
    echo "</select> [ <a href=\"admin.php?op=AddCategory\" class=\"noir\">".adm_translate("Ajouter")."</a> | <a href=\"admin.php?op=EditCategory\" class=\"noir\">".adm_translate("Editer")."</a> | <a href=\"admin.php?op=DelCategory\" class=\"rouge\">".adm_translate("Effacer")."</a> ]";
}

function autoStory() {
    global $hlpfile, $aid, $NPDS_Prefix, $radminsuper, $gmt, $f_meta_nom, $f_titre, $adminimg;
    include ("header.php");
    GraphicAdmin($hlpfile);
    adminhead ($f_meta_nom, $f_titre, $adminimg);
    echo '<h3>'.adm_translate("Articles programmés").'</h3>
    <table id="tab_adm" data-toggle="table" data-striped="true" data-show-toggle="true" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa">
    <thead>
    <tr>
        <th data-sortable="true">'.adm_translate('Titre').'</th>
        <th data-sortable="true">'.adm_translate('Date pr&#xE9;vu de publication').'</th>
        <th>'.adm_translate('Fonctions').'</th>
    </tr>
    </thead>
    <tbody>'."\n";

    $result = sql_query("select anid, title, date_debval, topic from ".$NPDS_Prefix."autonews order by date_debval ASC");
    while(list($anid, $title, $time, $topic) = sql_fetch_row($result)) {
        if ($anid != "") {
           $affiche=false;
           $result2=sql_query("select topicadmin, topicname from ".$NPDS_Prefix."topics where topicid='$topic'");
           list ($topicadmin, $topicname)=sql_fetch_row($result2);
           if ($radminsuper) {
              $affiche=true;
           } else {
              $topicadminX=explode(",",$topicadmin);
              for ($i = 0; $i < count($topicadminX); $i++) {
                 if (trim($topicadminX[$i])==$aid) $affiche=true;
              }
           }
           if ($title=="") {$title=adm_translate("Aucun Sujet");}
           if ($affiche) {
              echo '
              <tr>
              <td><a href="admin.php?op=autoEdit&amp;anid='.$anid.'">'.aff_langue($title).'</a></td>
              <td>'.formatTimestamp("nogmt".$time).'</td>
              <td><a href="admin.php?op=autoEdit&amp;anid='.$anid.'"><i class="fa fa-edit fa-lg" title="'.adm_translate("Afficher l'article").'" data-toggle="tooltip"></i></a><a href=\"admin.php?op=autoDelete&amp;anid='.$anid.'">&nbsp;<i class="fa fa-trash-o fa-lg text-danger" title="'.adm_translate("Effacer l'Article").'" data-toggle="tooltip" ></i></a></td>
              </tr>';
           } else {
              echo '
              <tr>
              <td><i>'.aff_langue($title).'</i></td>
              <td>'.formatTimestamp("nogmt".$time).'</td>
              <td>&nbsp;</td>
              </tr>';
           }
        }
    }
    echo '</table>';
    adminfoot('','','','');
}

function autoDelete($anid) {
    global $NPDS_Prefix;

    sql_query("delete from ".$NPDS_Prefix."autonews where anid='$anid'");
    Header("Location: admin.php?op=autoStory");
}
function autoEdit($anid) {
    global $aid, $hlpfile, $tipath, $radminsuper;
    global $NPDS_Prefix;

    $result = sql_query("select catid, title, time, hometext, bodytext, topic, informant, notes, ihome, date_debval,date_finval,auto_epur from ".$NPDS_Prefix."autonews where anid='$anid'");
    list($catid, $title, $time, $hometext, $bodytext, $topic, $informant, $notes, $ihome, $date_debval,$date_finval,$epur) = sql_fetch_row($result);
    sql_free_result($result);
    $titre = stripslashes($title);
    $hometext = stripslashes($hometext);
    $bodytext = stripslashes($bodytext);
    $notes = stripslashes($notes);

    if ($topic<1) {$topic = 1;}
    $affiche=false;
    $result2=sql_query("select topictext, topicimage, topicadmin from ".$NPDS_Prefix."topics where topicid='$topic'");
    list ($topictext, $topicimage, $topicadmin)=sql_fetch_row($result2);
    if ($radminsuper) {
       $affiche=true;
    } else {
       $topicadminX=explode(",",$topicadmin);
       for ($i = 0; $i < count($topicadminX); $i++) {
          if (trim($topicadminX[$i])==$aid) $affiche=true;
       }
    }
    if (!$affiche) { header("location: admin.php?op=autoStory");}

    include ('header.php');
    GraphicAdmin($hlpfile);
    opentable();
    echo "<table width=100% cellspacing=2 cellpadding=2 border=0><tr><td class=\"header\">\n";
    echo adm_translate("Editer l'Article Automatique");
    echo "</td></tr></table><br />";
    $rowcolor = tablos();
    echo "<table border=\"0\" width=\"85%\" cellpadding=\"0\" cellspacing=\"1\"><tr $rowcolor><td>";
    $rowcolor = tablos();
    echo "<table width=\"100%\" border=\"0\" cellpadding=\"8\" cellspacing=\"1\"><tr $rowcolor>";
    echo "<td valign=\"top\">".aff_local_langue("<b>".adm_translate("Langue de Prévisualisation")."</b> : ","","local_user_language");
    $no_img=false;
    if ((file_exists("$tipath$topicimage")) and ($topicimage!="")) {
      echo "<img src=\"$tipath$topicimage\" border=\"0\" align=\"right\" alt=\"\" />";
    } else {
      $no_img=true;
    }
    code_aff($titre, $hometext, $bodytext, $notes);
    if ($no_img) {
       echo "</td><td width=\"10%\" align=\"center\" valign=\"middle\"><b>".aff_langue($topictext)."</b>";
    }
    echo "</td></tr></table></td></tr></table><br />";

    echo "<form action=\"admin.php\" method=\"post\" name=\"adminForm\">";
    opentable2();
    echo "<b>".adm_translate("Utilisateur")."</b> : $informant<br /><br />
    <b>".adm_translate("Titre")."</b>&nbsp;&nbsp;:
    <input class=\"textbox\" type=\"text\" name=\"title\" size=\"50\" value=\"$titre\" /><br />";
    echo "<b>".adm_translate("Sujet")."</b> : <select class=\"textbox_standard\" name=\"topic\">";
    $toplist = sql_query("select topicid, topictext, topicadmin from ".$NPDS_Prefix."topics order by topictext");
    if ($radminsuper) echo "<option value=\"\">".adm_translate("Tous les Sujets")."</option>\n";
    while(list($topicid, $topics, $topicadmin) = sql_fetch_row($toplist)) {
       $affiche=false;
       if ($radminsuper) {
          $affiche=true;
       } else {
          $topicadminX=explode(",",$topicadmin);
          for ($i = 0; $i < count($topicadminX); $i++) {
             if (trim($topicadminX[$i])==$aid) $affiche=true;
          }
       }
       if ($affiche) {
          if ($topicid==$topic) { $sel = "selected=\"selected\" "; }
          echo "<option $sel value=\"$topicid\">".aff_langue($topics)."</option>\n";
          $sel = "";
       }
    }
    echo "</select>";
    SelectCategory($catid);
    echo "<br />";
    puthome($ihome);
    closetable2();
    echo "<br /><b>".adm_translate("Texte d'introduction")."</b> :<br />
    <textarea class=\"textbox\" cols=\"70\" rows=\"25\" name=\"hometext\" style=\"width: 100%;\">$hometext</textarea>";
    echo aff_editeur("hometext", "true");
    echo "<br /><b>".adm_translate("Texte étendu")."</b> :<br />
    <textarea class=\"textbox\" cols=\"70\" rows=\"25\" name=\"bodytext\" style=\"width: 100%;\">$bodytext</textarea>";
    echo aff_editeur("bodytext", "true");
    if ($aid != $informant) {
       echo "<br /><b>".adm_translate("Notes")."</b> :<br />
       <textarea class=\"textbox\" cols=\"70\" rows=\"7\" name=\"notes\" style=\"width: 100%;\">$notes</textarea>";
       echo aff_editeur("notes", "true");
    }
    $deb_day=substr($date_debval,8,2);
    $deb_month=substr($date_debval,5,2);
    $deb_year=substr($date_debval,0,4);
    $deb_hour=substr($date_debval,11,2);
    $deb_min=substr($date_debval,14,2);
    //
    $fin_day=substr($date_finval,8,2);
    $fin_month=substr($date_finval,5,2);
    $fin_year=substr($date_finval,0,4);
    $fin_hour=substr($date_finval,11,2);
    $fin_min=substr($date_finval,14,2);
    //
    publication($deb_day,$deb_month,$deb_year,$deb_hour,$deb_min, $fin_day,$fin_month,$fin_year,$fin_hour,$fin_min, $epur);
    echo "<input type=\"hidden\" name=\"anid\" value=\"$anid\" />
    <input type=\"hidden\" name=\"op\" value=\"autoSaveEdit\" />
    <br /><input class=\"bouton_standard\" type=\"submit\" value=\"".adm_translate("Sauver les modifications")."\" />
    </form>";
    closetable();
    include ('footer.php');
}

function autoSaveEdit($anid, $title, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $informant, $members, $Mmembers, $date_debval,$date_finval,$epur) {
    global $aid, $ultramode;
    global $NPDS_Prefix;

    $title = stripslashes(FixQuotes(str_replace("\"","&quot;",$title)));
    $hometext = stripslashes(FixQuotes($hometext));
    $bodytext = stripslashes(FixQuotes($bodytext));
    $notes = stripslashes(FixQuotes($notes));
    if (($members==1) and ($Mmembers=="")) {$ihome="-127";}
    if (($members==1) and (($Mmembers>1) and ($Mmembers<=127))) {$ihome=$Mmembers;}

    $result = sql_query("update ".$NPDS_Prefix."autonews set catid='$catid', title='$title', time=now(), hometext='$hometext', bodytext='$bodytext', topic='$topic', notes='$notes', ihome='$ihome', date_debval='$date_debval', date_finval='$date_finval', auto_epur='$epur' where anid='$anid'");
    if ($ultramode) {
       ultramode();
    }
    Header("Location: admin.php?op=autoEdit&anid=$anid");
}

switch ($op) {
    case "autoStory":
         autoStory();
         break;
    case "autoDelete":
         autodelete($anid);
         break;
    case "autoEdit":
         autoEdit($anid);
         break;
    case "autoSaveEdit":
         if (!$date_debval) {
            if (strlen($deb_day)==1) {
               $deb_day = "0$deb_day";
            }
            if (strlen($deb_month)==1) {
               $deb_month = "0$deb_month";
            }
            $date_debval = "$deb_year-$deb_month-$deb_day $deb_hour:$deb_min:00";
         }
         if (!$date_finval) {
            if (strlen($fin_day)==1) {
               $fin_day = "0$fin_day";
            }
            if (strlen($fin_month)==1) {
               $fin_month = "0$fin_month";
            }
            $date_finval = "$fin_year-$fin_month-$fin_day $fin_hour:$fin_min:00";
         }
         if ($date_finval<$date_debval) {
            $date_finval = $date_debval;
         }
         autoSaveEdit($anid, $title, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $informant, $members, $Mmembers, $date_debval,$date_finval,$epur);
         break;
}
?>
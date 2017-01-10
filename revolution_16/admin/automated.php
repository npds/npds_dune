<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Admin DUNE Prototype                                                 */
/*                                                                      */
/* NPDS Copyright (c) 2002-2017 by Philippe Brunier                     */
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
   echo '
      <div class="form-group row">
         <label class="col-sm-4 form-control-label" for="ihome">'.adm_translate("Publier dans la racine ?").'</label>';
   $sel1 = 'checked="checked"';
   $sel2 = '';
   if ($ihome == 1) {
      $sel1 = '';
      $sel2 = 'checked="checked"';
   }
   echo '
         <div class="col-sm-8">
            <label class="radio-inline">
               <input type="radio" name="ihome" value="0" '.$sel1.' />'.adm_translate("Oui").'
            </label>
            <label class="radio-inline">
               <input type="radio" name="ihome" value="1" '.$sel2.' />'.adm_translate("Non").'
            </label>
            <p class="help-block">'.adm_translate("Ne s'applique que si la catégorie : 'Articles' n'est pas sélectionnée.").'</p>
         </div>
      </div>';
   $sel1 = '';
   $sel2 = 'checked="checked"';
   echo '
      <div class="form-group row">
         <label class="col-sm-4 form-control-label text-danger" for="members">'.adm_translate("Seulement aux membres").'</label>
         <div class="col-sm-8">
            <label class="radio-inline">';
   if ($ihome<0) {
      $sel1 = 'checked="checked"';
      $sel2 = '';
   }
   if (($ihome>1) and ($ihome<=127)) {
      $Mmembers=$ihome;
      $sel1 = 'checked="checked"';
      $sel2 = '';
   }
   echo '
               <input type="radio" name="members" value="1" '.$sel1.' />'.adm_translate("Oui").'
            </label>
            <label class="radio-inline">
               <input type="radio" name="members" value="0" '.$sel2.' />'.adm_translate("Non").'
            </label>
         </div>
      </div>';
    // ---- Groupes
    $mX=liste_group();
    $tmp_groupe='';
   while (list($groupe_id, $groupe_name)=each($mX)) {
      if ($groupe_id=='0') {$groupe_id='';}
      if ($Mmembers==$groupe_id) {$sel3='selected="selected"';} else {$sel3='';}
      $tmp_groupe.='
      <option value="'.$groupe_id.'" '.$sel3.'>'.$groupe_name.'</option>';
      $nbg++;
   }
   echo '
      <div class="form-group row">
         <label class="col-sm-4 form-control-label text-danger" for="Mmembers">'.adm_translate("Groupe").'</label>
         <div class="col-sm-8">
            <select class="custom-select form-control" name="Mmembers">'.$tmp_groupe.'</select>
         </div>
      </div>';
}

function SelectCategory($cat) {
   global $NPDS_Prefix;
   $selcat = sql_query("SELECT catid, title FROM ".$NPDS_Prefix."stories_cat");
   echo ' 
      <div class="form-group row">
         <label class="col-sm-4 form-control-label" for="catid">'.adm_translate("Catégorie").'</label>
         <div class="col-sm-8">
            <select class="custom-select form-control" name="catid">';
   if ($cat == 0) $sel = 'selected="selected"';
   else $sel = '';
   echo '
               <option name="catid" value="0" '.$sel.'>'.adm_translate("Articles").'</option>';
   while(list($catidX, $title) = sql_fetch_row($selcat)) {
      if ($catidX==$cat) $sel = 'selected';
      else $sel = '';
      echo '
               <option name="catid" value="'.$catidX.'" '.$sel.'>'.aff_langue($title).'</option>';
    }
   echo '
            </select>
            <p class="help-block text-right"><a href="admin.php?op=AddCategory" class="btn btn-outline-primary btn-sm" title="'.adm_translate("Ajouter").'" data-toggle="tooltip" ><i class="fa fa-plus-square fa-lg"></i></a>&nbsp;<a class="btn btn-outline-primary btn-sm" href="admin.php?op=EditCategory" title="'.adm_translate("Editer").'" data-toggle="tooltip" ><i class="fa fa-edit fa-lg"></i></a>&nbsp;<a class="btn btn-outline-danger btn-sm" href="admin.php?op=DelCategory" title="'.adm_translate("Effacer").'" data-toggle="tooltip"><i class="fa fa-trash-o fa-lg"></i></a></p>
         </div>
      </div>';
}


function autoStory() {
   global $hlpfile, $aid, $NPDS_Prefix, $radminsuper, $gmt, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '
   <h3>'.adm_translate("Liste des articles").'</h3>
   <table id="tab_adm" data-toggle="table" data-striped="true" data-show-toggle="true" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa">
      <thead>
         <tr>
            <th data-sortable="true" data-halign="center">'.adm_translate('Titre').'</th>
            <th data-sortable="true" data-align="center" data-align="right">'.adm_translate('Date prévue de publication').'</th>
            <th data-align="right">'.adm_translate('Fonctions').'</th>
         </tr>
      </thead>
      <tbody>';

   $result = sql_query("SELECT anid, title, date_debval, topic FROM ".$NPDS_Prefix."autonews ORDER BY date_debval ASC");
    while(list($anid, $title, $time, $topic) = sql_fetch_row($result)) {
        if ($anid != "") {
           $affiche=false;
           $result2=sql_query("SELECT topicadmin, topicname FROM ".$NPDS_Prefix."topics WHERE topicid='$topic'");
           list ($topicadmin, $topicname)=sql_fetch_row($result2);
           if ($radminsuper) {
              $affiche=true;
           } else {
              $topicadminX=explode(",",$topicadmin);
              for ($i = 0; $i < count($topicadminX); $i++) {
                 if (trim($topicadminX[$i])==$aid) $affiche=true;
              }
           }
           if ($title=='') {$title=adm_translate("Aucun Sujet");}
           if ($affiche) {
              echo '
         <tr>
            <td><a href="admin.php?op=autoEdit&amp;anid='.$anid.'">'.aff_langue($title).'</a></td>
            <td>'.formatTimestamp("nogmt".$time).'</td>
            <td><a href="admin.php?op=autoEdit&amp;anid='.$anid.'"><i class="fa fa-edit fa-lg" title="'.adm_translate("Afficher l'article").'" data-toggle="tooltip"></i></a><a href="admin.php?op=autoDelete&amp;anid='.$anid.'">&nbsp;<i class="fa fa-trash-o fa-lg text-danger" title="'.adm_translate("Effacer l'Article").'" data-toggle="tooltip" ></i></a></td>
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
   echo '
   </table>';
   adminfoot('','','','');
}

function autoDelete($anid) {
    global $NPDS_Prefix;
    sql_query("DELETE FROM ".$NPDS_Prefix."autonews WHERE anid='$anid'");
    Header("Location: admin.php?op=autoStory");
}
function autoEdit($anid) {
    global $aid, $hlpfile, $tipath, $radminsuper, $NPDS_Prefix, $adminimg;
    
   $f_meta_nom ='autoStory';
   $f_titre = adm_translate("Editer un Article");
   //==> controle droit
   admindroits($aid,$f_meta_nom);
   //<== controle droit

    $result = sql_query("SELECT catid, title, time, hometext, bodytext, topic, informant, notes, ihome, date_debval,date_finval,auto_epur FROM ".$NPDS_Prefix."autonews WHERE anid='$anid'");
    list($catid, $title, $time, $hometext, $bodytext, $topic, $informant, $notes, $ihome, $date_debval,$date_finval,$epur) = sql_fetch_row($result);
    sql_free_result($result);
    $titre = stripslashes($title);
    $hometext = stripslashes($hometext);
    $bodytext = stripslashes($bodytext);
    $notes = stripslashes($notes);

    if ($topic<1) {$topic = 1;}
    $affiche=false;
    $result2=sql_query("SELECT topictext, topicimage, topicadmin FROM ".$NPDS_Prefix."topics WHERE topicid='$topic'");
    list ($topictext, $topicimage, $topicadmin)=sql_fetch_row($result2);
    if ($radminsuper) {
       $affiche=true;
    } else {
       $topicadminX=explode(',',$topicadmin);
       for ($i = 0; $i < count($topicadminX); $i++) {
          if (trim($topicadminX[$i])==$aid) $affiche=true;
       }
    }
    if (!$affiche) { header("location: admin.php?op=autoStory");}
   $topiclogo = '<span class="badge badge-default pull-right"><strong>'.aff_langue($topictext).'</strong></span>';

   include ('header.php');
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);

    echo '<h3>'.adm_translate("Editer l'Article Automatique").'</h3>';

    echo aff_local_langue(adm_translate("Langue de Prévisualisation"),'','local_user_language');

   echo '<div class="card card-block">';

   if ($topicimage!=='') { 
      if (!$imgtmp=theme_image('topics/'.$topicimage)) {$imgtmp=$tipath.$topicimage;}
      $timage=$imgtmp;
      if (file_exists($imgtmp)) 
      $topiclogo = '<img class="img-fluid " src="'.$timage.'" align="right" alt="" />';
   }




//     $no_img=false;
//     if ((file_exists("$tipath$topicimage")) and ($topicimage!="")) {
//       echo "<img src=\"$tipath$topicimage\" border=\"0\" align=\"right\" alt=\"\" />";
//     } else {
//       $no_img=true;
//     }
    
    code_aff('<h3>'.$subject.$topiclogo.'</h3>', '<div class="text-muted">'.$hometext.'</div>', $bodytext, $notes);
    if ($no_img) {
       echo "<b>".aff_langue($topictext)."</b>";
    }

    echo '<b>'.adm_translate("Utilisateur").'</b>'.$informant.'<br /><br />';

    echo '
    </div>
   <form action="admin.php" method="post" name="adminForm">
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="title">'.adm_translate("Titre").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" name="title" size="50" value="'.$titre.'" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="topic">'.adm_translate("Sujet").'</label>
         <div class="col-sm-8">
            <select class="custom-select form-control" name="topic">';
    $toplist = sql_query("SELECT topicid, topictext, topicadmin FROM ".$NPDS_Prefix."topics ORDER BY topictext");
    if ($radminsuper) echo '
               <option value="">'.adm_translate("Tous les Sujets").'</option>';
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
          if ($topicid==$topic) { $sel = 'selected="selected" '; }
          echo "<option $sel value=\"$topicid\">".aff_langue($topics)."</option>\n";
          $sel = '';
       }
    }
    echo ' 
            </select>
         </div>
      </div>';
    SelectCategory($catid);
    echo "<br />";
    puthome($ihome);

    echo '
      <div class="form-group row">
         <label class="form-control-label col-sm-12" for="hometext">'.adm_translate("Texte d'introduction").'</label>
         <div class="col-sm-12">
            <textarea class="tin form-control" rows="25" name="hometext" >'.$hometext.'</textarea>
         </div>
      </div>
      '.aff_editeur('hometext', '').'
      <div class="form-group row">
         <label class="form-control-label col-sm-12" for="bodytext">'.adm_translate("Texte étendu").'</label>
         <div class="col-sm-12">
            <textarea class="tin form-control" rows="25" name="bodytext" >'.$bodytext.'</textarea>
         </div>
      </div>
      '.aff_editeur('bodytext', '');
   if ($aid != $informant) {
      echo '
      <div class="form-group row">
         <label class="form-control-label col-sm-12" for="notes">'.adm_translate("Notes").'</label>
         <div class="col-sm-12">
            <textarea class="tin form-control" rows="7" name="notes">'.$notes.'</textarea>
         </div>
      </div>
      '.aff_editeur('notes', '');
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
    echo '
      <div class="form-group row">
         <div class="col-sm-12">
            <input type="hidden" name="anid" value="'.$anid.'" />
            <input type="hidden" name="op" value="autoSaveEdit" />
            <input class="btn btn-primary" type="submit" value="'.adm_translate("Sauver les modifications").'" />
         </div>
      </div>
   </form>';
   adminfoot('fv','','','');
}

function autoSaveEdit($anid, $title, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $informant, $members, $Mmembers, $date_debval,$date_finval,$epur) {
   global $aid, $ultramode, $NPDS_Prefix;

   $title = stripslashes(FixQuotes(str_replace('"','&quot;',$title)));
   $hometext = stripslashes(FixQuotes($hometext));
   $bodytext = stripslashes(FixQuotes($bodytext));
   $notes = stripslashes(FixQuotes($notes));
   if (($members==1) and ($Mmembers=='')) {$ihome="-127";}
   if (($members==1) and (($Mmembers>1) and ($Mmembers<=127))) {$ihome=$Mmembers;}

   $result = sql_query("UPDATE ".$NPDS_Prefix."autonews SET catid='$catid', title='$title', time=now(), hometext='$hometext', bodytext='$bodytext', topic='$topic', notes='$notes', ihome='$ihome', date_debval='$date_debval', date_finval='$date_finval', auto_epur='$epur' WHERE anid='$anid'");
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
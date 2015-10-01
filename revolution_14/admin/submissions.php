<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2010 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],"admin.php")) { Access_Error(); }
$f_meta_nom ='submissions';
$f_titre = adm_translate('Article en attente de validation');
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit

global $language;
$hlpfile = "manuels/$language/submissions.html";

function submissions() {
    global $hlpfile, $NPDS_Prefix, $aid, $radminsuper, $f_meta_nom, $f_titre, $adminimg;
    $dummy = 0;
    include ("header.php");
    GraphicAdmin($hlpfile);
    adminhead ($f_meta_nom, $f_titre, $adminimg);
    $result = sql_query("SELECT qid, subject, timestamp, topic, uname FROM ".$NPDS_Prefix."queue ORDER BY timestamp");
    if (sql_num_rows($result) == 0) {
       echo '<h3>'.adm_translate("Pas de nouveaux Articles postés").'</h3>';
    } else {
       echo '<h3>'.adm_translate("Nouveaux Articles postés").'&nbsp;<span class="label label-pill label-default">'.sql_num_rows($result).'</span></h3>';
       echo '
       <table id="tad_subm" data-toggle="table" data-striped="true" data-show-toggle="true" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa">
       <thead>
           <tr>
               <th data-sortable="true"><i class="fa fa-user fa-lg"></i></th>
               <th data-sortable="true">'.adm_translate("Sujet").'</th>
               <th data-sortable="true">'.adm_translate("Titre").'</th>
               <th data-sortable="true">'.adm_translate("Date").'</th>
               <th>'.adm_translate("Fonctions").'</th>
           </tr>
       </thead>
       <tbody>';
       while (list($qid, $subject, $timestamp, $topic, $uname) = sql_fetch_row($result)) {
          if ($topic<1) {$topic = 1;}
          $affiche=false;
          $result2=sql_query("select topicadmin, topictext, topicimage from ".$NPDS_Prefix."topics where topicid='$topic'");
          list ($topicadmin, $topictext, $topicimage)=sql_fetch_row($result2);
          if ($radminsuper) {
             $affiche=true;
          } else {
             $topicadminX=explode(",",$topicadmin);
             for ($i = 0; $i < count($topicadminX); $i++) {
                if (trim($topicadminX[$i])==$aid) $affiche=true;
             }
          }
          echo '
          <tr>
          <td>'.$uname.'</td>
          <td>';
          if ($subject=="") { $subject=adm_translate("Aucun Sujet");}
          $subject= aff_langue($subject);
          if ($affiche) {
             echo '<img class=" " src="images/topics/'.$topicimage.'" height="30" width="30" alt="avatar" />&nbsp;<a href="admin.php?op=topicedit&amp;topicid='.$topic.'" class="adm_tooltip">'.aff_langue($topictext).'</a></td>
             <td align="left"><a href="admin.php?op=DisplayStory&amp;qid='.$qid.'">'.$subject.'</a></td>';
          } else {
             echo aff_langue($topictext).'</td>
             <td align="left"><i>'.$subject.'</i></td>';
          }
          echo '<td align="right">'.formatTimestamp($timestamp).'</td>';
          if ($affiche) {
             echo '
             <td><a class="" href="admin.php?op=DisplayStory&amp;qid='.$qid.'"><i class="fa fa-edit fa-lg" title="'.adm_translate("Editer").'" data-toggle="tooltip" ></i></a>&nbsp;<a class="text-danger" href="admin.php?op=DeleteStory&amp;qid='.$qid.'"><i class="fa fa-trash-o fa-lg" title="'.adm_translate("Effacer").'" data-toggle="tooltip" ></i></a></td>
             </tr>';
          } else {
             echo '<td>&nbsp;</td>
             </tr>';
          }
          $dummy++;
       }
       if ($dummy < 1) {
          echo '<h3>'.adm_translate("Pas de nouveaux Articles postés").'</h3>';
       } else {
          echo '</tbody>
        </table>';
       }
    }
    include ("footer.php");
}

switch ($op) {
    default:
        submissions();
        break;
}
?>
<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2017 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],"admin.php")) { Access_Error(); }
$f_meta_nom ='MaintForumAdmin';
$f_titre = adm_translate('Maintenance des Forums');
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit

global $language, $adminimg, $admf_ext;
$hlpfile = "manuels/$language/forummaint.html";
include ("auth.php");
include ("functions.php");

function ForumMaintMarkTopics() {
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '
   <h3>'.adm_translate("Marquer tous les Topics comme lus").'</h3>
   <table data-toggle="table" data-striped="true" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa">
      <thead>
         <tr>
            <th>ID</th>
            <th>Topics ID</th>
            <th>Status</th>
         </tr>
      </thead>
      <tbody>';
   if (!$r = sql_query("DELETE FROM ".$NPDS_Prefix."forum_read")) {
      forumerror('0001');
   } else {
      $resultF=sql_query("SELECT forum_id FROM ".$NPDS_Prefix."forums ORDER BY forum_id ASC");
      $time_actu=time()+($gmt*3600);
      while (list($forum_id)=sql_fetch_row($resultF)) {
         echo '
         <tr>
            <td align="center">'.$forum_id.'</td>
            <td align="left">';
         $resultT=sql_query("SELECT topic_id FROM ".$NPDS_Prefix."forumtopics WHERE forum_id='$forum_id' ORDER BY topic_id ASC");
         while (list($topic_id)=sql_fetch_row($resultT)) {
            $resultU=sql_query("SELECT uid FROM ".$NPDS_Prefix."users ORDER BY uid DESC");
            while (list($uid)=sql_fetch_row($resultU)) {
               if ($uid>1) {
                  $r=sql_query("INSERT INTO ".$NPDS_Prefix."forum_read (forum_id, topicid, uid, last_read, status) VALUES ('$forum_id', '$topic_id', '$uid', '$time_actu', '1')");
               }
            }
         echo $topic_id.' ';
         }
         echo '
            </td>
            <td align="center">'.translate("Ok").'</td>
         </tr>';
      }
   }
   echo '
   </tbody>
   </table>';
   sql_free_result;
   adminfoot('','','','');
}

function ForumMaintTopics($before,$forum_name) {
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);

   echo '
   <hr />
   <h3 class="text-danger">'.adm_translate("Supprimer massivement les Topics").'</h3>';
    if ($before!='') {
        echo '&nbsp;<span class="text-danger">< '.$before.'</span>';
       $add_sql="and topic_time<'$before'";
       $topic_check=' checked="checked"';
    } else {
       $add_sql='';
       $topic_check='';
    }
    if ($forum_name!='') {
       $add_sql2="WHERE forum_name='$forum_name'";
    } else {
       $add_sql2='';
    }

   echo '<form action="admin.php" method="post">';
    echo "<table>\n";
    $resultF=sql_query("SELECT forum_id, forum_name FROM ".$NPDS_Prefix."forums $add_sql2 ORDER BY forum_id ASC");
    while (list($forum_id, $forum_name)=sql_fetch_row($resultF)) {
       $rowcolor = tablos();
       echo "<tr $rowcolor><td align=\"left\"><b>$forum_name</b></td>";
       $resultT=sql_query("SELECT topic_id, topic_title FROM ".$NPDS_Prefix."forumtopics WHERE forum_id='$forum_id' $add_sql ORDER BY topic_id ASC");
       $ibid=0;
       while (list($topic_id, $topic_title)=sql_fetch_row($resultT)) {
          if ($parse==0) {
             $tt =  FixQuotes($topic_title);
          } else {
             $tt =  stripslashes($topic_title);
          }
          if ($ibid==20) {
             echo "</tr><tr><td>&nbsp;</td>";
             $ibid=0;
          }
          $ibid++;
          echo "<td align=\"center\"><a href=\"admin.php?op=MaintForumTopicDetail&amp;topic=$topic_id&amp;topic_title=".urlencode($tt)."\" title=\"$tt\" class=\"noir\">$topic_id</a><br />
          <input class=\"texbox\" type=\"checkbox\" name=\"topics[$topic_id]\" $topic_check /></td>";
       }
       if ($ibid<20)
          echo "<td colspan=\"".(20-$ibid)."\">&nbsp;</td>";
    }
    echo '
    </tr>
    </table>
    <input type="hidden" name="op" value="ForumMaintTopicMassiveSup" />';
    echo "
    <input class=\"btn btn-danger\" type=\"submit\" name=\"Topics_Del\" value=\"".adm_translate("Supprimer massivement les Topics")."\" />";
    echo '</form>';
    sql_free_result;
    adminfoot('','','','');
}

function ForumMaintTopicDetail($topic, $topic_title) {
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '
   <h3>'.adm_translate("Supprimer massivement les Topics").'</h3>
   <form action="admin.php" method="post">';
   $resultTT=sql_query("SELECT post_text, post_time FROM ".$NPDS_Prefix."posts WHERE topic_id='$topic' ORDER BY post_time DESC limit 0,1");
   list($post_text, $post_time)=sql_fetch_row($resultTT);
   echo "<input type=\"hidden\" name=\"op\" value=\"ForumMaintTopicSup\" /><input type=\"hidden\" name=\"topic\" value=\"$topic\" />";
   echo "<b>Topic : $topic | ".stripslashes($topic_title)."</b> | ";
   echo "<input class=\"btn btn-danger\" type=\"submit\" name=\"Topics_Del\" value=\"".adm_translate("Effacer")."\" /><hr noshade=\"noshade\" class=\"ongl\" />";
   echo '[ '.convertdate($post_time).' ]<br /><br />';
   echo stripslashes($post_text);
   echo '</form>';
   adminfoot('','','','');
}

function ForumMaintTopicMassiveSup($topics) {
    global $NPDS_Prefix;
    if ($topics) {
       while (list($topic_id,$value)=each($topics)) {
          if ($value=='on') {
             $sql = "DELETE FROM ".$NPDS_Prefix."posts WHERE topic_id = '$topic_id'";
             if (!$result = sql_query($sql))
                forumerror('0009');
             $sql = "DELETE FROM ".$NPDS_Prefix."forumtopics WHERE topic_id = '$topic_id'";
             if (!$result = sql_query($sql))
                forumerror('0010');
             $sql = "DELETE FROM ".$NPDS_Prefix."forum_read WHERE topicid = '$topic_id'";
             if (!$r = sql_query($sql))
                forumerror('0001');
             control_efface_post("forum_npds","",$topic_id,"");
          }
       }
       sql_free_result;
    }
    Q_Clean();
    header("location: admin.php?op=MaintForumAdmin");
}

function ForumMaintTopicSup($topic) {
    global $NPDS_Prefix;
    $sql = "DELETE FROM ".$NPDS_Prefix."posts WHERE topic_id = '$topic'";
    if (!$result = sql_query($sql))
       forumerror('0009');
    $sql = "DELETE FROM ".$NPDS_Prefix."forumtopics WHERE topic_id = '$topic'";
    if (!$result = sql_query($sql))
       forumerror('0010');
    $sql = "DELETE FROM ".$NPDS_Prefix."forum_read WHERE topicid = '$topic'";
    if (!$r = sql_query($sql))
       forumerror('0001');
    sql_free_result;
    control_efface_post("forum_npds","",$topic,"");
    Q_Clean();
    header("location: admin.php?op=MaintForumTopics");
}

function SynchroForum() {
    global $NPDS_Prefix;
    // affectation d'un topic à un forum
    if (!$result1 = sql_query("SELECT topic_id, forum_id FROM ".$NPDS_Prefix."forumtopics ORDER BY topic_id ASC"))
       forumerror('0009');
    while (list($topi_cid, $foru_mid)=sql_fetch_row($result1)) {
      sql_query("UPDATE ".$NPDS_Prefix."posts SET forum_id='$foru_mid' WHERE topic_id='$topi_cid' and forum_id>0");
    }
    sql_free_result;

    // table forum_read et contenu des topic
    if (!$result1 = sql_query("SELECT topicid, uid, rid FROM ".$NPDS_Prefix."forum_read ORDER BY topicid ASC"))
       forumerror('0009');
    while (list($topicid, $uid, $rid)=sql_fetch_row($result1)) {
       if (($topicid.$uid)==$tmp) {
          $resultD = sql_query("DELETE FROM ".$NPDS_Prefix."forum_read WHERE topicid='$topicid' and uid='$uid' and rid='$rid'");
       }
       $tmp=$topicid.$uid;
       if ($result2 = sql_query("SELECT topic_id FROM ".$NPDS_Prefix."forumtopics WHERE topic_id = '$topicid'")) {
          list($topic_id)=sql_fetch_row($result2);
          if (!$topic_id) {
             $result3 = sql_query("DELETE FROM ".$NPDS_Prefix."forum_read WHERE topicid='$topicid'");
          }
       }
    }
    sql_free_result;
    header("location: admin.php?op=MaintForumAdmin");
}

function MergeForum() {
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '
   <hr/>
   <h3>'.adm_translate("Fusionner des forums").'</h3>
   <form id="fad_mergeforum" action="admin.php" method="post">
      <fieldset>
         <div class="form-group row">
            <label class="form-control-label col-sm-4" for="oriforum">'.adm_translate("Forum d'origine").'</label>
            <div class="col-sm-8">
               <select class="custom-select form-control" name="oriforum">';
   $sql = "SELECT forum_id, forum_name FROM ".$NPDS_Prefix."forums ORDER BY forum_index,forum_id";
   if ($result = sql_query($sql)) {
      if ($myrow = sql_fetch_assoc($result)) {
         do {
            echo '
                  <option value="'.$myrow['forum_id'].'">'.$myrow['forum_name'].'</option>';
         } while($myrow = sql_fetch_assoc($result));
         } else {
            echo '
                  <option value="-1">'.translate("No More Forums").'</option>';
         }
      } else {
         echo '
                  <option value="-1">Database Error</option>';
      }
    echo '
               </select>
            </div>
         </div>
         <div class="form-group row">
            <label class="form-control-label col-sm-4" for="destforum">'.adm_translate("Forum de destination").'</label>
            <div class="col-sm-8">
               <select class="custom-select form-control" name="destforum">';
    if ($result = sql_query($sql)) {
       if ($myrow = sql_fetch_assoc($result)) {
          do {
             echo '
                  <option value="'.$myrow['forum_id'].'">'.$myrow['forum_name'].'</option>';
          } while($myrow = sql_fetch_assoc($result));
       } else {
          echo '
                  <option value="-1">'.translate("No More Forums").'</option>';
       }
    } else {
       echo '
                  <option value="-1">Database Error</option>';
    }
    echo '
               </select>
            </div>
         </div>
         <div class="form-group row">
            <div class="col-sm-8 ml-sm-auto">
               <input type="hidden" name="op" value="MergeForumAction" />
               <button class="btn btn-primary col-12" type="submit" name="Merge_Forum_Action">'.adm_translate("Fusionner").'</button>
            </div>
         </div>
      </fieldset>
   </form>';
    sql_free_result;
    adminfoot('','','','');
}
function MergeForumAction($oriforum,$destforum) {
    global $upload_table;
    global $NPDS_Prefix;
    $sql = "UPDATE ".$NPDS_Prefix."forumtopics SET forum_id='$destforum' WHERE forum_id='$oriforum'";
    if (!$r = sql_query($sql))
       forumerror('0010');
    $sql = "UPDATE ".$NPDS_Prefix."posts SET forum_id='$destforum' WHERE forum_id='$oriforum'";
    if (!$r = sql_query($sql))
       forumerror('0010');
    $sql = "UPDATE ".$NPDS_Prefix."forum_read SET forum_id='$destforum' WHERE forum_id='$oriforum'";
    if (!$r = sql_query($sql))
       forumerror('0001');
    $sql = "UPDATE $upload_table SET forum_id='$destforum' WHERE apli='forum_npds' and forum_id='$oriforum'";
    sql_query($sql);
    sql_free_result;
    Q_Clean();
    header("location: admin.php?op=MaintForumAdmin");
}

function ForumMaintAdmin() {
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg, $language;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '
   <hr />
   <h3>'.adm_translate("Maintenance des Forums").'</h3>';
   // Mark Topics, Synchro Forum_read table, Merge Forums
   echo '
   <div class="row">
      <div class="col-12">
         <form id="fad_forumaction" action="admin.php" method="post">
            <input type="hidden" name="op" value="MaintForumMarkTopics" />
            <button class="btn btn-primary btn-block btn-lg" type="submit" name="Topics_Mark"><i class="fa fa-check-square-o fa-lg"></i>&nbsp;'.adm_translate("Marquer tous les Topics comme lus").'</button>
         </form>
      </div>
      <div class="col-12">
         <form action="admin.php" method="post">
            <input type="hidden" name="op" value="SynchroForum" />
            <button class="btn btn-primary btn-block btn-lg" type="submit" name="Synchro_Forum"><i class="fa fa-refresh fa-lg"></i>&nbsp;'.adm_translate("Synchroniser les forums").'</button>
         </form>
      </div>
      <div class="col-12">
         <form action="admin.php" method="post">
            <input type="hidden" name="op" value="MergeForum" />
            <button class="btn btn-primary btn-block btn-lg" type="submit" name="Merge_Forum"><i class="fa fa-compress fa-lg"></i>&nbsp;'.adm_translate("Fusionner des forums").'</button>
         </form>
      </div>
   </div>
   <br />
   <form id="fad_forumdelete" action="admin.php" method="post">
      <legend>'.adm_translate("Supprimer massivement les Topics").'</legend>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="forum_name">'.adm_translate("Nom du forum").'</label>
         <div class="col-sm-8">
            <input type="text" class="form-control" name="forum_name" id="forum_name" maxlength="150" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="before">'.adm_translate("Date").'</label>
         <div class="col-sm-8">
            <div id="embeddingDatePicker"></div>
            <input type="hidden" class="form-control" name="before" id="before" value="" maxlength="11" placeholder="AAAA-MM-JJ" />
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-8 ml-sm-auto">
            <input type="hidden" name="op" value="MaintForumTopics" />
            <button class="btn btn-primary" type="submit" name="Topics_Mark">'.adm_translate("Envoyer").'</button>
         </div>
      </div>
   </form>
   <script type="text/javascript" src="lib/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" ></script>
   <script type="text/javascript" src="lib/bootstrap-datepicker/dist/locales/bootstrap-datepicker.'.language_iso(1,"","").'.min.js" ></script>
   <script>
   $(document).ready(function() {
      $("<link>")
         .appendTo("head")
         .attr({type: "text/css", rel: "stylesheet",href: "lib/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css"});
       $("#embeddingDatePicker")
        .datepicker({
            format: "yyyy-mm-dd",
            language:"'.language_iso(1,'','').'",
            title:"Avant cette date"
        })
        .on("changeDate", function(e) {
            $("#before").val($("#embeddingDatePicker").datepicker("getFormattedDate"));
            $("#fad_forumdelete").formValidation("revalidateField", "before");
        });
   });
   </script>';
   $fv_parametres ='
            before: {
                excluded: false,
                validators: {
                    notEmpty: {
                        message: "The date is required"
                    },
                    date: {
                        format: "YYYY-MM-DD",
                        message: "The date is not a valid"
                    }
                }
            },';

   echo auto_complete("forname","forum_name","forums","forum_name","86400");
   adminfoot('fv',$fv_parametres,'','');
}
?>
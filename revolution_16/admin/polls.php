<?php
/************************************************************************/
/* DUNE by NPDS - admin prototype                                       */
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

if (!function_exists('admindroits'))
   include('die.php');
$f_meta_nom ='create';
$f_titre = adm_translate("Les sondages");
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
global $language;
$hlpfile = "manuels/$language/surveys.html";

function poll_createPoll() {
   global $hlpfile, $maxOptions, $f_meta_nom, $f_titre, $adminimg, $NPDS_Prefix;
   include ('header.php');
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   global $id;
   echo '
      <hr />
        <h3 class="mb-3">'.adm_translate("Liste des sondages").'</h3>
        <table id="tad_pool" data-toggle="table" data-striped="true" data-show-toggle="true" data-search="true" data-mobile-responsive="true" data-buttons-class="outline-secondary" data-icons="icons" data-icons-prefix="fa">
         <thead>
            <tr>
               <th class="n-t-col-xs-1" data-sortable="true" data-halign="center" data-align="right">ID</th>
               <th data-sortable="true" data-halign="center">'.adm_translate("Intitulé du Sondage").'</th>
               <th class="n-t-col-xs-2" data-sortable="true" data-halign="center" data-align="right">'.adm_translate("Vote").'</th>
               <th class="n-t-col-xs-2" data-halign="center" data-align="center">'.adm_translate("Fonctions").'</th>
            </tr>
         </thead>
         <tbody>';
   $result = sql_query("SELECT pollID, pollTitle, voters FROM ".$NPDS_Prefix."poll_desc ORDER BY timeStamp");
   while($object = sql_fetch_assoc($result)) {
      echo '
            <tr>
               <td>'.$object["pollID"].'</td>
               <td>'.aff_langue($object["pollTitle"]).'</td>
               <td>'.$object["voters"].'</td>
               <td>
                  <a href="admin.php?op=editpollPosted&amp;id='.$object["pollID"].'"><i class="fa fa-edit fa-lg" title="'.adm_translate("Editer ce sondage").'" data-bs-toggle="tooltip"></i></a>
                  <a href="admin.php?op=removePosted&amp;id='.$object["pollID"].'"><i class="fas fa-trash fa-lg text-danger ms-2" title="'.adm_translate("Effacer ce sondage").'" data-bs-toggle="tooltip"></i></a>
               </td>
            </tr>';
        $result2 = sql_query("SELECT SUM(optionCount) AS SUM FROM ".$NPDS_Prefix."poll_data WHERE pollID='".$object["pollID"]."'");
        list ($sum) = sql_fetch_row($result2);
   }
   echo '
         </tbody>
      </table>
      <hr />
      <h3 class="mb-3">'.adm_translate("Créer un nouveau Sondage").'</h3>
      <form id="pollssondagenew" action="admin.php" method="post">
         <input type="hidden" name="op" value="createPosted" />
         <div class="form-floating mb-3">
            <input class="form-control" type="text" id="pollTitle" name="pollTitle" id="pollTitle" maxlength="100" required="required" />
            <label for="pollTitle">'.adm_translate("Intitulé du Sondage").'</label>
            <span class="help-block">'.adm_translate("S.V.P. entrez chaque option disponible dans un seul champ").'</span>
            <span class="help-block text-end"><span id="countcar_pollTitle"></span></span>
         </div>';
   $requi='';
   for ($i = 1; $i <= $maxOptions; $i++) {
      $requi = $i<3 ? ' required="required" ' : '';
      echo '
         <div class="form-floating mb-3">
            <input class="form-control" type="text" id="optionText'.$i.'" name="optionText['.$i.']" maxlength="255" '.$requi.' />
            <label for="optionText'.$i.'">'.adm_translate("Option").' '.$i.'</label>
            <span class="help-block text-end"><span id="countcar_optionText'.$i.'"></span></span>
         </div>';
   }
   echo '
         <div class="form-check form-check-inline mb-3">
            <input class="form-check-input" type="checkbox" id="poll_type" name="poll_type" value="1" />
            <label class="form-check-label" for="poll_type">'.adm_translate("Seulement aux membres").'</label>
         </div>
         <div>
            <button type="submit" class="btn btn-primary">'.adm_translate("Créer").'</button>
         </div>
      </form>';
   $arg1='
   var formulid = ["pollssondagenew"];
   inpandfieldlen("pollTitle",100)';
   for ($i = 1; $i <= $maxOptions; $i++) {
      $arg1.= '
   inpandfieldlen("optionText'.$i.'",255)';
   }
   adminfoot('fv','',$arg1,'');
}

function poll_createPosted() {
   global $maxOptions, $pollTitle, $optionText, $poll_type, $NPDS_Prefix;

   $timeStamp = time();
   $pollTitle = FixQuotes($pollTitle);
   $result = sql_query("INSERT INTO ".$NPDS_Prefix."poll_desc VALUES (0, '$pollTitle', '$timeStamp', 0)");
   $id = sql_last_id();
   for ($i = 1; $i <= sizeof($optionText); $i++) {
      if ($optionText[$i] != '')
         $optionText[$i] = FixQuotes($optionText[$i]);
      $result = sql_query("INSERT INTO ".$NPDS_Prefix."poll_data (pollID, optionText, optionCount, voteID, pollType) VALUES ('$id', '$optionText[$i]', 0, '$i', '$poll_type')");
   }
   Header("Location: admin.php?op=adminMain");
}

function poll_removePoll() {
   global $hlpfile, $f_meta_nom, $f_titre, $adminimg, $NPDS_Prefix;
   include ('header.php');
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '
   <hr />
   <h3 class="mb-3">'.adm_translate("Retirer un Sondage existant").'</h3>
   <span class="help-block">'.adm_translate("S.V.P. Choisissez un sondage dans la liste suivante.").'</span>
   <p align="center"><span class="text-danger">'.adm_translate("ATTENTION : Le Sondage choisi va être supprimé IMMEDIATEMENT de la base de données !").'</span></p>
   ';
   echo '
   <form action="admin.php" method="post">
      <input type="hidden" name="op" value="removePosted" />
      <table id="tad_delepool" data-toggle="table" data-striped="true" data-show-toggle="true" data-search="true" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa">
         <thead>
            <tr>
               <th></th>
               <th data-sortable="true">'.adm_translate("Intitulé du Sondage").'</th>
               <th data-sortable="true">ID</th>
            </tr>
         </thead>
         <tbody>';
   $result = sql_query("SELECT pollID, pollTitle FROM ".$NPDS_Prefix."poll_desc ORDER BY timeStamp");
   while ($object = sql_fetch_assoc($result)) {
      echo '
            <tr>
               <td><input type="radio" name="id" value="'.$object['pollID'].'" /></td>
               <td> '.$object['pollTitle'].'</td>
               <td>ID : '.$object['pollID'].'</td>
            </tr>
      ';
   }
   echo '
         </tbody>
      </table>
      <br />
      <div class="mb-3">
         <button class="btn btn-danger" type="submit">'.adm_translate("Retirer").'</button>
      </div>
   </form>';
   include ('footer.php');
}

function poll_removePosted() {
   global $id, $setCookies, $NPDS_Prefix;
   // ----------------------------------------------------------------------------
   // Specified the index and the name off the application for the table appli_log
   $al_id = 1;
   $al_nom = 'Poll';
   // ----------------------------------------------------------------------------
   if ($setCookies=='1'){
      $sql="DELETE FROM ".$NPDS_Prefix."appli_log WHERE al_id='$al_id' AND al_subid='$id'";
      sql_query($sql);
   }
   sql_query("DELETE FROM ".$NPDS_Prefix."poll_desc WHERE pollID='$id'");
   sql_query("DELETE FROM ".$NPDS_Prefix."poll_data WHERE pollID='$id'");
   include ('modules/comments/pollBoth.conf.php');
   sql_query("DELETE FROM ".$NPDS_Prefix."posts WHERE topic_id='$id' AND forum_id='$forum'");
   Header("Location: admin.php?op=create");
}

function poll_editPoll() {
   global $hlpfile, $f_meta_nom, $f_titre, $adminimg, $NPDS_Prefix;
   include ('header.php');
   $result = sql_query("SELECT pollID, pollTitle, timeStamp FROM ".$NPDS_Prefix."poll_desc ORDER BY timeStamp");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '
   <hr />
   <h3 class="mb-3">'.adm_translate("Edition des sondages").'</h3>
   <span class="help-block">'.adm_translate("S.V.P. Choisissez un sondage dans la liste suivante.").'</span>
   <form id="fad_editpool" action="admin.php" method="post">
      <input type="hidden" name="op" value="editpollPosted" />
      <table id="tad_editpool" data-toggle="table" data-striped="true" data-show-toggle="true" data-search="true" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa">
         <thead>
            <tr>
               <th></th>
               <th data-sortable="true">'.adm_translate("Intitulé du Sondage").'</th>
               <th data-sortable="true">ID</th>
            </tr>
         </thead>
         <tbody>';
   while ($object = sql_fetch_assoc($result)) {
   echo '
            <tr>
               <td><input type="radio" name="id" value="'.$object['pollID'].'" /></td>
               <td>'.$object['pollTitle'].'</td>
               <td>ID : '.$object['pollID'].'</td>
            </tr>';
   }
   echo '
         </tbody>
      </table>
      <br />
      <div class="mb-3">
         <button type="submit" class="btn btn-primary">'.adm_translate("Editer").'</button>
      </div>
   </form>';
//   adminfoot('','','','');
   include ('footer.php');
}

function poll_editPollPosted() {
   global $id, $maxOptions, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   if ($id) {
      global $hlpfile;
      include ('header.php');
      GraphicAdmin($hlpfile);
      adminhead ($f_meta_nom, $f_titre, $adminimg);
      $result = sql_query("SELECT pollID, pollTitle, timeStamp FROM ".$NPDS_Prefix."poll_desc WHERE pollID='$id'");
      $holdtitle = sql_fetch_row($result);
      $result = sql_query("SELECT optionText, voteID, pollType FROM ".$NPDS_Prefix."poll_data WHERE pollID='$id' ORDER BY voteID ASC");
      echo '
   <hr />
   <h3 class="mb-3">'.adm_translate("Edition des sondages").'</h3>
   <form id="pollssondageed" method="post" action="admin.php">
      <input type="hidden" name="op" value="SendEditPoll">
      <input type="hidden" name="pollID" value="'.$id.'" />
      <div class="form-floating mb-3">
         <input class="form-control" type="text" id="pollTitle" name="pollTitle" value="'.$holdtitle[1].'" maxlength="100" required="required" />
         <label for="pollTitle">'.adm_translate("Intitulé du Sondage").'</label>
         <span class="help-block">'.adm_translate("S.V.P. entrez chaque option disponible dans un seul champ").'</span>
         <span class="help-block text-end"><span id="countcar_pollTitle"></span></span>
      </div>';
      $requi='';
         for ($i = 1; $i <= $maxOptions; $i++) {
            if($i<3) $requi=' required="required" '; else $requi='';
         list($optionText, $voteID, $pollType) = sql_fetch_row($result);
         echo '
      <div class="form-floating mb-3">
         <input class="form-control" type="text" id="optionText'.$i.'" name="optionText['.$voteID.']" maxlength="255" value="'.$optionText.'" '.$requi.' />
         <label for="optionText'.$i.'">'.adm_translate("Option").' '.$i.'</label>
         <span class="help-block text-end"><span id="countcar_optionText'.$i.'"></span></span>
      </div>';
      }
      $pollClose = (($pollType / 128) >= 1 ? 1 : 0);
      $pollType = $pollType%128;
      echo '
      <div class="mb-3">
         <div class="form-check">
            <input class="form-check-input" type="checkbox" id="poll_type" name="poll_type" value="1"';
      if ($pollType == "1") echo ' checked="checked"';
      echo ' />
            <label class="form-check-label" for="poll_type">'.adm_translate("Seulement aux membres").'</label>
         </div>
      </div>
      <div class="mb-3">
         <div class="form-check text-danger">
            <input class="form-check-input" type="checkbox" id="poll_close" name="poll_close" value="1"';
      if ($pollClose == 1) echo ' checked="checked"';
      echo ' />
            <label class="form-check-label" for="poll_close">'.adm_translate("Vote fermé").'</label>
         </div>
      </div>
      <div class="mb-3">
            <button class="btn btn-primary" type="submit">Ok</button>
      </div>
   </form>';
      $arg1='
   var formulid = ["pollssondageed"];
   inpandfieldlen("pollTitle",100)';
      for ($i = 1; $i <= $maxOptions; $i++) {
         $arg1.= '
      inpandfieldlen("optionText'.$i.'",255)';
      }
      adminfoot('fv','',$arg1,'');
   } else
      header("location: admin.php?op=editpoll");
}

function poll_SendEditPoll() {
   global $maxOptions, $pollTitle, $optionText, $poll_type, $pollID, $poll_close, $NPDS_Prefix;
   $result = sql_query("UPDATE ".$NPDS_Prefix."poll_desc SET pollTitle='$pollTitle' WHERE pollID='$pollID'");
   $poll_type = $poll_type + 128 * $poll_close;
   for ($i = 1; $i <= sizeof($optionText); $i++) {
      if ($optionText[$i] != '')
         $optionText[$i] = FixQuotes($optionText[$i]);
      $result = sql_query("UPDATE ".$NPDS_Prefix."poll_data SET optionText='$optionText[$i]', pollType='$poll_type' WHERE pollID='$pollID' and voteID='$i'");
   }
   Header("Location: admin.php?op=create");
}

switch ($op) {
   case 'create':
      poll_createPoll();
   break;
   case 'createPosted':
      poll_createPosted();
   break;
   case 'remove':
      poll_removePoll();
   break;
   case 'removePosted':
      poll_removePosted();
   break;
   case 'editpoll':
      poll_editPoll();
   break;
   case 'editpollPosted':
      poll_editPollPosted();
   break;
   case 'SendEditPoll':
      poll_SendEditPoll();
   break;
}
?>
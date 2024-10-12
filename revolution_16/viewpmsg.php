<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
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
if (!function_exists("Mysql_Connexion"))
   include ("mainfile.php");
include("functions.php");
$cache_obj =  $SuperCache ? new cacheManager() : new SuperCacheEmpty() ;
include("auth.php");
   if (!$user)
      Header("Location: user.php");
   else {
      include("header.php");
      $userX = base64_decode($user);
      $userdata = explode(':', $userX);
      $userdata = get_userdata($userdata[1]);
      $sqlT = "SELECT DISTINCT dossier FROM ".$NPDS_Prefix."priv_msgs WHERE to_userid = '".$userdata['uid']."' AND dossier!='...' AND type_msg='0' ORDER BY dossier";
      $resultT = sql_query($sqlT);
      member_menu($userdata['mns'],$userdata['uname']);
   echo '
   <div class="card card-body mt-3">
      <h2><a href="replypmsg.php?send=1" title="'.translate("Ecrire un nouveau message privé").'" data-bs-toggle="tooltip" ><i class="fa fa-edit me-2"></i></a><span class="d-none d-xl-inline">&nbsp;'.translate("Message personnel")." - </span>".translate("Boîte de réception").'</h2>
      <form id="viewpmsg-dossier" action="viewpmsg.php" method="post">
         <div class="mb-3">
            <label class="sr-only" for="dossier" >'.translate("Sujet").'</label>
            <select class="form-select" name="dossier" onchange="document.forms[\'viewpmsg-dossier\'].submit()">
               <option value="...">'.translate("Choisir un dossier/sujet").'...</option>';
      $tempo["..."]=0;
      while (list($dossierX)=sql_fetch_row($resultT)) {
         if (AddSlashes($dossierX)==$dossier) $sel='selected="selected"'; else $sel='';
         echo '
               <option '.$sel.' value="'.$dossierX.'">'.$dossierX.'</option>';
         $tempo[$dossierX]=0;
      }
      $sel = (isset($dossier) and $dossier=='All') ? 'selected="selected"' : '';
      echo '
               <option '.$sel.' value="All">'.translate("Tous les sujets").'</option>
            </select>
         </div>
      </form>';

      settype($dossier,'string');

      $ibid = $dossier=="All" ? '' : "AND dossier='$dossier'" ;
      if (!$dossier) $ibid="AND dossier='...'";
      $sql = "SELECT * FROM ".$NPDS_Prefix."priv_msgs WHERE to_userid='".$userdata['uid']."' AND type_msg='0' $ibid ORDER BY msg_id DESC";
      $resultID = sql_query($sql);
      if (!$resultID) forumerror('0005');

      if (!$total_messages = sql_num_rows($resultID)) {
         echo '
      <div class="alert alert-danger lead">
         '.translate("Vous n'avez aucun message.").'
      </div>';
         $display=0;
      }
      else {
         $display=1;

      echo '
      <form name="prvmsg" method="get" action="replypmsg.php" onkeypress="return event.keyCode != 13;">
         <table class="mb-3" data-toggle="table" data-show-toggle="true" data-mobile-responsive="true" data-buttons-class="outline-secondary" data-icons="icons" data-icons-prefix="fa" data-search="true" data-search-align="left"
               data-buttons-align="left"
               data-toolbar-align="left">
            <thead class="thead-default">
               <tr>
                  <th class="n-t-col-xs-1" data-halign="center" data-align="center">
                     <div class="form-check">
                        <input class="form-check-input is-invalid" id="allbox" name="allbox" onclick="CheckAll();" type="checkbox" value="" />
                        <label class="form-check-label" for="allbox">&nbsp;&nbsp;</label>
                     </div>
                  </th>
                  <th class="n-t-col-xs-1" data-align="center" ><i class="fas fa-long-arrow-alt-down"></i></th>';
      if ($smilies) { echo '
                  <th class="n-t-col-xs-1" data-align="center" >&nbsp;</th>'; }
      echo '
                  <th data-halign="center" data-sortable="true" data-align="left">'.translate("de").'</th>
                  <th data-halign="center" data-sortable="true" >'.translate("Sujet").'</th>
                  <th data-halign="center" data-sortable="true" data-align="right">'.translate("Date").'</th>
               </tr>
            </thead>
            <tbody>';

      $count=0;
      while ($myrow = sql_fetch_assoc($resultID)) {
         $myrow['subject']=strip_tags($myrow['subject']);
         $posterdata = get_userdata_from_id($myrow['from_userid']);
         if ($dossier=="All") $myrow['dossier']="All";
         if (!array_key_exists($myrow['dossier'],$tempo)) $tempo[$myrow['dossier']]=0;
         echo '
               <tr>
                  <td>
                     <div class="form-check">
                        <input class="form-check-input is-invalid" type="checkbox" onclick="CheckCheckAll();" id="msg_id'.$count.'" name="msg_id['.$count.']" value="'.$myrow['msg_id'].'" />
                        <label class="form-check-label" for="msg_id'.$count.'">&nbsp;&nbsp;</label>
                     </div>
                  </td>';
         if ($myrow['read_msg'] == "1")
            echo '
                  <td><a href="readpmsg.php?start='.$tempo[$myrow['dossier']].'&amp;total_messages='.$total_messages.'&amp;dossier='.urlencode($myrow['dossier']).'" title="'.translate("Lu").'" data-bs-toggle="tooltip"><i class="far fa-envelope-open fa-lg "></i></a></td>';
         else
            echo '
                  <td><a href="readpmsg.php?start='.$tempo[$myrow['dossier']].'&amp;total_messages='.$total_messages.'&amp;dossier='.urlencode($myrow['dossier']).'" title="'.translate("Non lu").'" data-bs-toggle="tooltip"><i class="fa fa-envelope fa-lg faa-shake animated"></i></a></td>';
         if ($smilies) {
            if ($myrow['msg_image']!='') {
               if ($ibid=theme_image("forum/subject/".$myrow['msg_image'])) $imgtmp=$ibid; else $imgtmp="images/forum/subject/".$myrow['msg_image'];
               echo '
                  <td><img class="n-smil" src="'.$imgtmp.'" alt="" /></td>';
            } else
               echo '
                  <td></td>';
         }
         echo '
                  <td>'.userpopover($posterdata['uname'],40,2);
         echo ($posterdata['uid']<>1) ? $posterdata['uname'] : $sitename ;
         echo '</td>
                  <td>'.aff_langue($myrow['subject']).'</td>
                  <td class="small">'.formatTimes($myrow['msg_time'], IntlDateFormatter::SHORT, IntlDateFormatter::SHORT).'</td>
               </tr>';
         $tempo[$myrow['dossier']]=$tempo[$myrow['dossier']]+1;
         $count++;
      }
      echo '
            </tbody>
         </table>';
      if ($display) {
         echo '
         <div class="mb-3 mt-3">
            <button class="btn btn-outline-danger btn-sm" type="submit" name="delete_messages" value="delete_messages" >'.translate("Effacer").'</button>
            <input type="hidden" name="total_messages" value="'.$total_messages.'" />
            <input type="hidden" name="type" value="inbox" />
         </div>';
      }
      echo '
      </form>';
      }
      echo '
   </div>';

      $sql = "SELECT * FROM ".$NPDS_Prefix."priv_msgs WHERE from_userid = '".$userdata['uid']."' AND type_msg='1' ORDER BY msg_id DESC";
      $resultID = sql_query($sql);
      if (!$resultID)
         forumerror('0005');
      $total_messages = sql_num_rows($resultID);

      echo '
      <div class="card card-body mt-3">
      <h2><a href="replypmsg.php?send=1" title="'.translate("Ecrire un nouveau message privé").'" data-bs-toggle="tooltip" ><i class="fa fa-edit me-2"></i></a><span class="d-none d-xl-inline">&nbsp;'.translate("Message personnel")." - </span>".translate("Boîte d'émission").'<span class="badge bg-secondary float-end">'.$total_messages.'</span></h2>
      <form id="" name="prvmsgB" method="get" action="replypmsg.php">
         <table class="mb-3" data-toggle="table" data-show-toggle="true" data-mobile-responsive="true" data-buttons-class="outline-secondary" data-icons="icons" data-icons-prefix="fa">
            <thead class="thead-default">
               <tr>
                  <th class="n-t-col-xs-1" data-halign="center" data-align="center" >
                     <div class="form-check">
                        <input class="form-check-input is-invalid" id="allbox_b" name="allbox" onclick="CheckAllB();" type="checkbox" value="Check All" />
                        <label class="form-check-label" for="allbox_b">&nbsp;</label>
                     </div>
                  </th>';
      if ($smilies) 
         echo '
                  <th class="n-t-col-xs-1" data-align="center" >&nbsp;</th>';
      echo '
                  <th data-halign="center" data-sortable="true" data-align="center">'.translate("Envoyé à").'</th>
                  <th data-halign="center" data-sortable="true" align="center">'.translate("Sujet").'</th>
                  <th data-halign="center" data-align="right" data-sortable="true" align="center">'.translate("Date").'</th>
            </tr>
         </thead>
         <tbody>';
      if (!$total_messages) {
         $display=0;
         echo '
            <tr>
               <td colspan="6" align="center">'.translate("Vous n'avez aucun message.").'</td>
            </tr>';
      } else
         $display=1;
      $count=0;
      while ($myrow = sql_fetch_assoc($resultID)) {
         echo '
            <tr>
               <td>
                  <div class="form-check">
                     <input class="form-check-input is-invalid" type="checkbox" onclick="CheckCheckAllB();" id="msg_idB'.$count.'" name="msg_id['.$count.']" value="'.$myrow['msg_id'].'" />
                     <label class="form-check-label text-danger" for="msg_idB'.$count.'">&nbsp;</label>
                  </div>
               </td>';
         if ($smilies) {
            if ($myrow['msg_image']!='') {
               if ($ibid=theme_image("forum/subject/".$myrow['msg_image'])) $imgtmp=$ibid; else $imgtmp="images/forum/subject/".$myrow['msg_image'];
               echo '<td width="5%" align="center"><img class="n-smil" src="'.$imgtmp.'" alt="Image du topic" /></td>';
            } else {
               echo '<td width="5%" align="center">&nbsp;</td>';
            }
         }
         $myrow['subject']=strip_tags($myrow['subject']);
         $posterdata = get_userdata_from_id($myrow['to_userid']);
         echo '
               <td><a href="readpmsg.php?start='.$count.'&amp;total_messages='.$total_messages.'&amp;type=outbox" >'.$posterdata['uname'].'</a></td>
               <td>'.aff_langue($myrow['subject']).'</td>
               <td>'.$myrow['msg_time'].'</td>
            </tr>';
         $count++;
      }
      echo '
         </tbody>
      </table>';
      if ($display) {
         echo '
         <div class="mb-3 mt-3">
            <button class="btn btn-outline-danger btn-sm" type="submit" name="delete_messages" value="delete_messages" >'.translate("Effacer").'</button>
            <input type="hidden" name="total_messages" value="'.$total_messages.'" />
            <input type="hidden" name="type" value="outbox" />
         </div>';
      }
      echo '
      </form>
      </div>';
      ?>
      <script type="text/javascript">
      //<![CDATA[
      function CheckAll() {
         for (var i=0;i<document.prvmsg.elements.length;i++)
         {
            var e = document.prvmsg.elements[i];
            if ((e.name != 'allbox') && (e.type=='checkbox'))
            e.checked = document.prvmsg.allbox.checked;
         }
      }
      function CheckCheckAll() {
        var TotalBoxes = 0, TotalOn = 0;
        for (var i=0;i<document.prvmsg.elements.length;i++)
        {
            var e = document.prvmsg.elements[i];
            if ((e.name != 'allbox') && (e.type=='checkbox'))
            {
               TotalBoxes++;
               if (e.checked)
               {
                  TotalOn++;
               }
            }
        }
        if (TotalBoxes==TotalOn)
        {document.prvmsg.allbox.checked=true;}
        else
        {document.prvmsg.allbox.checked=false;}
      }
      function CheckAllB() {
        for (var i=0;i<document.prvmsgB.elements.length;i++)
        {
                var e = document.prvmsgB.elements[i];
                if ((e.name != 'allbox') && (e.type=='checkbox'))
                e.checked = document.prvmsgB.allbox.checked;
        }
      }
      function CheckCheckAllB() {
        var TotalBoxes = 0, TotalOn = 0;
        for (var i=0;i<document.prvmsgB.elements.length;i++)
        {
                var e = document.prvmsgB.elements[i];
                if ((e.name != 'allbox') && (e.type=='checkbox'))
                {
                   TotalBoxes++;
                      if (e.checked)
                      {
                         TotalOn++;
                      }
                }
        }
        if (TotalBoxes==TotalOn)
        {document.prvmsgB.allbox.checked=true;}
        else
        {document.prvmsgB.allbox.checked=false;}
      }
      //]]>
      </script>
      <?php
      include('footer.php');
   }
?>
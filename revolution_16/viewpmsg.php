<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2017 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion")) {
   include ("mainfile.php");
}
include("functions.php");
if ($SuperCache) {
   $cache_obj = new cacheManager();
} else {
   $cache_obj = new SuperCacheEmpty();
}
include("auth.php");
   if (!$user) {
      Header("Location: user.php");
   } else {
      include("header.php");
      $userX = base64_decode($user);
      $userdata = explode(':', $userX);
      $userdata = get_userdata($userdata[1]);
      $sqlT = "SELECT DISTINCT dossier FROM ".$NPDS_Prefix."priv_msgs WHERE to_userid = '".$userdata['uid']."' AND dossier!='...' AND type_msg='0' ORDER BY dossier";
      $resultT = sql_query($sqlT);

   echo '
   <ul class="nav nav-tabs d-flex flex-wrap"> 
      <li class="nav-item"><a class="nav-link " href="user.php?op=edituser" title="'.translate("Edit User").'" data-toggle="tooltip" ><i class="fa fa-user fa-2x hidden-xl-up"></i><span class="hidden-lg-down">&nbsp;'.translate("Edit User").'</span></a></li>
      <li class="nav-item"><a class="nav-link " href="user.php?op=editjournal" title="'.translate("Edit Journal").'" data-toggle="tooltip"><i class="fa fa-edit fa-2x hidden-xl-up"></i><span class="hidden-lg-down">&nbsp;'.translate("Journal").'</span></a></li>';
   include ("modules/upload/upload.conf.php");
   if (($userdata['mns']==1) and ($autorise_upload_p)) {
      include ("modules/blog/upload_minisite.php");
      $PopUp=win_upload("popup");
      echo '
      <li class="nav-item"><a class="nav-link" href="javascript:void(0);" onclick="window.open('.$PopUp.')" title="'.translate("Manage my Mini-Web site").'"  data-toggle="tooltip"><i class="fa fa-desktop fa-2x hidden-xl-up"></i><span class="hidden-lg-down">&nbsp;'.translate("Mini-Web site").'</span></a></li>';
   }
   echo '
      <li class="nav-item"><a class="nav-link " href="user.php?op=edithome" title="'.translate("Change the home").'" data-toggle="tooltip" ><i class="fa fa-edit fa-2x hidden-xl-up"></i><span class="hidden-lg-down">&nbsp;'.translate("Page").'</span></a></li>
      <li class="nav-item"><a class="nav-link " href="user.php?op=chgtheme" title="'.translate("Change Theme").'"  data-toggle="tooltip" ><i class="fa fa-paint-brush fa-2x hidden-xl-up"></i><span class="hidden-lg-down">&nbsp;'.translate("Theme").'</span></a></li>
      <li class="nav-item"><a class="nav-link " href="modules.php?ModPath=reseaux-sociaux&amp;ModStart=reseaux-sociaux" title="'.translate("Social networks").'"  data-toggle="tooltip" ><i class="fa fa-share-alt-square fa-2x hidden-xl-up"></i><span class="hidden-lg-down">&nbsp;'.translate("Social networks").'</span></a></li>
      <li class="nav-item"><a class="nav-link active" title="'.translate("Private Message").'"  data-toggle="tooltip" ><i class="fa fa-envelope fa-2x hidden-xl-up"></i><span class="hidden-lg-down">&nbsp;'.translate("Message").'</span></a></li>
      <li class="nav-item"><a class="nav-link " href="user.php?op=logout" title="'.translate("Logout").'" data-toggle="tooltip" ><i class="fa fa-sign-out fa-2x text-danger hidden-xl-up"></i><span class="hidden-lg-down text-danger">&nbsp;'.translate("Logout").'</span></a></li>
   </ul>
   <div class="card card-block mt-3">
      <h2><a href="replypmsg.php?send=1" title="'.translate("Write a new Private Message").'" data-toggle="tooltip" ><i class="fa fa-edit mr-1"></i></a><span class="hidden-sm-down">&nbsp;'.translate("Private Message")." - </span>".translate("Inbox").'</h2>
      <form id="viewpmsg-dossier" action="viewpmsg.php" method="post">
         <div class="form-group">
            <label class="sr-only" for="dossier" >'.translate("Topic").'</label>
            <select class="custom-select form-control" name="dossier" onchange="document.forms[\'viewpmsg-dossier\'].submit()">
               <option value="...">'.translate("Choose a folder/topic").'...</option>';
      $tempo["..."]=0;
      while (list($dossierX)=sql_fetch_row($resultT)) {
         if (AddSlashes($dossierX)==$dossier) {$sel='selected="selected"';} else {$sel='';}
         echo '
               <option '.$sel.' value="'.$dossierX.'">'.$dossierX.'</option>';
         $tempo[$dossierX]=0;
      }
      if ($dossier=='All') {$sel='selected="selected"';} else {$sel='';}
      echo '
               <option '.$sel.' value="All">'.translate("All Topics").'</option>
            </select>
         </div>
      </form>';

      settype($dossier,'string');
      if ($dossier=="All") {$ibid='';} else {$ibid="and dossier='$dossier'";}
      if (!$dossier) {$ibid="and dossier='...'";}
      $sql = "SELECT * FROM ".$NPDS_Prefix."priv_msgs WHERE to_userid='".$userdata['uid']."' AND type_msg='0' $ibid ORDER BY msg_id DESC";
      $resultID = sql_query($sql);
      if (!$resultID) { forumerror(0005);}

      if (!$total_messages = sql_num_rows($resultID)) {
         echo '
      <div class="alert alert-danger lead">
         '.translate("You don't have any Messages.").'
      </div>';
         $display=0;
      } else {
         $display=1;

      echo '
      <form name="prvmsg" method="get" action="replypmsg.php">
         <table class="mb-3" data-toggle="table" data-show-toggle="true" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa" >
            <thead class="thead-default">
               <tr>
                  <th class="n-t-col-xs-1" data-halign="center" data-align="center" >
                     <label class="custom-control custom-checkbox">
                        <input class="custom-control-input" name="allbox" onclick="CheckAll();" type="checkbox" value="Check All" />
                        <span class="custom-control-indicator bg-danger"></span>
                     </label>
                  </th>
                  <th class="n-t-col-xs-1" data-align="center" ><i class="fa fa-long-arrow-down"></i></th>';
      if ($smilies) { echo '
                  <th class="n-t-col-xs-1" align="center" >&nbsp;</th>'; }
      echo '
                  <th data-halign="center" data-sortable="true" data-align="center">'.translate("From").'</th>
                  <th data-halign="center" data-sortable="true" >'.translate("Subject").'</th>
                  <th data-halign="center" data-sortable="true" data-align="right">'.translate("Date").'</th>
               </tr>
            </thead>
            <tbody>';

      $count=0;
      while ($myrow = sql_fetch_assoc($resultID)) {
         $myrow['subject']=strip_tags($myrow['subject']);
         $posterdata = get_userdata_from_id($myrow['from_userid']);
         if ($dossier=="All") {$myrow['dossier']="All";}
         if (!array_key_exists($myrow['dossier'],$tempo)) {$tempo[$myrow['dossier']]=0;}
         echo '
               <tr>
                  <td>
                     <label class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" onclick="CheckCheckAll();" name="msg_id['.$count.']" value="'.$myrow['msg_id'].'" />
                        <span class="custom-control-indicator bg-danger"></span>
                     </label>
                  </td>';
         if ($myrow['read_msg'] == "1") {
            echo '
                  <td><a href="readpmsg.php?start='.$tempo[$myrow['dossier']].'&amp;total_messages='.$total_messages.'&amp;dossier='.urlencode($myrow['dossier']).'" title="'.translate("Read").'" data-toggle="tooltip"><i class="fa fa-file-o fa-lg "></i></a></td>';
         } else {
            echo '
                  <td><a href="readpmsg.php?start='.$tempo[$myrow['dossier']].'&amp;total_messages='.$total_messages.'&amp;dossier='.urlencode($myrow['dossier']).'" title="'.translate("Not Read").'" data-toggle="tooltip"><i class="fa fa-file fa-lg "></i></a></td>';
         }
         if ($smilies) {
            if ($myrow['msg_image']!='') {
               if ($ibid=theme_image("forum/subject/".$myrow['msg_image'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/subject/".$myrow['msg_image'];}
               echo '
                  <td><img class="n-smil" src="'.$imgtmp.'" alt="" /></td>';
            } else {
               echo '
                  <td></td>';
            }
         }
         echo '
                  <td align="center" width="10%"><a href="readpmsg.php?start='.$tempo[$myrow['dossier']].'&amp;total_messages='.$total_messages.'&amp;dossier='.urlencode($myrow['dossier']).'" >';
         if ($posterdata['uid']<>1) {
            echo $posterdata['uname'];
         } else {
            echo $sitename;
         }
         echo '</a></td>
                  <td>'.aff_langue($myrow['subject']).'</td>
                  <td align="center" width="20%">'.$myrow['msg_time'].'</td>
               </tr>';
         $tempo[$myrow['dossier']]=$tempo[$myrow['dossier']]+1;
         $count++;
      }
      echo '
            </tbody>
         </table>';
      if ($display) {
         echo '
         <div class="form-group mt-3">
            <button class="btn btn-outline-danger btn-sm" type="submit" name="delete_messages" value="delete_messages" >'.translate("Delete").'</button>
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
      if (!$resultID) {
         forumerror(0005);
      }
      $total_messages = sql_num_rows($resultID);

      echo '
      <div class="card card-block mt-3">
      <h2><a href="replypmsg.php?send=1" title="'.translate("Write a new Private Message").'" data-toggle="tooltip" ><i class="fa fa-edit mr-1"></i></a><span class="hidden-sm-down">&nbsp;'.translate("Private Message")." - </span>".translate("Outbox").'<span class="badge badge-default float-right">'.$total_messages.'</span></h2>
      <form id="" name="prvmsgB" method="get" action="replypmsg.php">
         <table class="mb-3" data-toggle="table" data-show-toggle="true" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa" >
            <thead class="thead-default">
               <tr>
                  <th class="n-t-col-xs-1" data-halign="center" data-align="center" >
                     <label class="custom-control custom-checkbox">
                        <input class="custom-control-input" name="allbox" onclick="CheckAllB();" type="checkbox" value="Check All" />
                        <span class="custom-control-indicator bg-danger"></span>
                     </label>
                  </th>';
      if ($smilies) { echo '
                  <th class="n-t-col-xs-1" align="center" >&nbsp;</th>';
      }
      echo '
                  <th data-halign="center" data-sortable="true" data-align="center">'.translate("To").'</th>
                  <th data-halign="center" data-sortable="true" align="center">'.translate("Subject").'</th>
                  <th data-halign="center" data-align="right" data-sortable="true" align="center">'.translate("Date").'</th>
            </tr>
         </thead>
         <tbody>';
      if (!$total_messages) {
         $display=0;
         echo '
            <tr>
               <td colspan="6" align="center">'.translate("You don't have any Messages.").'</td>
            </tr>';
      } else {
         $display=1;
      }
      $count=0;
      while ($myrow = sql_fetch_assoc($resultID)) {
         echo '
            <tr>
               <td>
                  <label class="custom-control custom-checkbox">
                     <input class="custom-control-input" type="checkbox" onclick="CheckCheckAllB();" name="msg_id['.$count.']" value="'.$myrow['msg_id'].'" />
                     <span class="custom-control-indicator bg-danger"></span>
                  </label>
               </td>';
         if ($smilies) {
            if ($myrow['msg_image']!='') {
               if ($ibid=theme_image("forum/subject/".$myrow['msg_image'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/subject/".$myrow['msg_image'];}
               echo '<td width="5%" align="center"><img class="n-smil" src="'.$imgtmp.'" alt="Image du topic" /></td>';
            } else {
               echo '<td width="5%" align="center">&nbsp;</td>';
            }
         }
         $myrow['subject']=strip_tags($myrow['subject']);
         $posterdata = get_userdata_from_id($myrow['to_userid']);
         echo "<td align=\"center\" width=\"10%\"><a href=\"readpmsg.php?start=$count&amp;total_messages=$total_messages&amp;type=outbox\" >".$posterdata['uname']."</a></td>";
         echo "<td>".aff_langue($myrow['subject'])."</td>";
         echo "<td align=\"center\" width=\"20%\">".$myrow['msg_time']."</td></tr>";
         $count++;
      }
      echo '
         </tbody>
      </table>';
      if ($display) {
         echo '
         <div class="form-group mt-3">
            <button class="btn btn-outline-danger btn-sm" type="submit" name="delete_messages" value="delete_messages" >'.translate("Delete").'</button>
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
<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2012 by Philippe Brunier                     */
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
      opentable();
      $userX = base64_decode($user);
      $userdata = explode(":", $userX);
      $userdata = get_userdata($userdata[1]);

      echo "<form action=\"viewpmsg.php\" method=\"post\">";
      echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n";
      echo translate("Private Message")." - ".translate("Inbox")."&nbsp;&nbsp;-&nbsp;&nbsp;";
      // Classement
      $sqlT = "SELECT distinct dossier FROM ".$NPDS_Prefix."priv_msgs WHERE to_userid = '".$userdata['uid']."' and dossier!='...' and type_msg='0' ORDER BY dossier";
      $resultT = sql_query($sqlT);
      echo "&nbsp;&nbsp;<b>".translate("Topic")."</b> : <select class=\"textbox_standard\" name=\"dossier\">";
      echo "<option value=\"...\">...</option>\n";
      $tempo["..."]=0;
      while (list($dossierX)=sql_fetch_row($resultT)) {
         if (AddSlashes($dossierX)==$dossier) {$sel="selected=\"selected\"";} else {$sel="";}
         echo "<option $sel value=\"$dossierX\">$dossierX</option>\n";
         $tempo[$dossierX]=0;
      }
      if ($dossier=='All') {$sel="selected=\"selected\"";} else {$sel="";}
      echo "<option $sel value=\"All\">".translate("All Topics")."</option>\n";
      echo "</select>";
      echo "&nbsp;<input type=\"submit\" class=\"bouton_standard\" name=\"classe\" value=\"OK\" />";
      echo "</td></tr></table>\n";
      echo "</form>";

      settype($dossier,'string');
      if ($dossier=="All") {$ibid="";} else {$ibid="and dossier='$dossier'";}
      if (!$dossier) {$ibid="and dossier='...'";}
      $sql = "SELECT * FROM ".$NPDS_Prefix."priv_msgs WHERE to_userid='".$userdata['uid']."' and type_msg='0' $ibid ORDER BY msg_id DESC";
      $resultID = sql_query($sql);
      if (!$resultID) {
         forumerror(0005);
      }

      echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"2\" border=\"0\">";
      echo "<form name=\"prvmsg\" method=\"get\" action=\"replypmsg.php\">";
      echo "<tr>";
      echo "<td class=\"header\" align=\"center\" valign=\"middle\"><input name=\"allbox\" onclick=\"CheckAll();\" type=\"checkbox\" value=\"Check All\" /></td>";
      echo "<td class=\"header\" align=\"center\" valign=\"middle\">";
      if ($ibid=theme_image("forum/download.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/download.gif";}
      echo "<img src=\"$imgtmp\" alt=\"\" border=\"\" /></td>";
      if ($smilies) { echo "<td class=\"header\" align=\"center\" valign=\"middle\">&nbsp;</td>"; }
      echo "<td class=\"header\" align=\"center\">".translate("From")."</td>";
      echo "<td class=\"header\" align=\"center\">".translate("Subject")."</td>";
      echo "<td class=\"header\" align=\"center\">".translate("Date")."</td>";
      echo "</tr>";
      if (!$total_messages = sql_num_rows($resultID)) {
         echo "<td colspan=\"6\" align=\"center\">".translate("You don't have any Messages.")."</td></tr>\n";
         $display=0;
      } else {
         $display=1;
      }

      $count=0;
      while ($myrow = sql_fetch_assoc($resultID)) {
         $rowcolor=tablos();
         echo "<tr $rowcolor>";
         echo "<td valign=\"top\" width=\"2%\" align=\"center\"><input type=\"checkbox\" onclick=\"CheckCheckAll();\" name=\"msg_id[$count]\" value=\"".$myrow['msg_id']."\" /></td>";
         if ($myrow['read_msg'] == "1") {
            echo "<td valign=\"top\" width=\"5%\" align=\"center\">&nbsp;</td>";
         } else {
            if ($ibid=theme_image("forum/read.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/read.gif";}
            echo "<td valign=\"top\" width=\"5%' align=\"center\"><img src=\"$imgtmp\" border=\"0\" alt=\"".translate("Not Read")."\" /></td>";
         }
         if ($smilies) {
            if ($myrow['msg_image']!="") {
               if ($ibid=theme_image("forum/subject/".$myrow['msg_image'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/subject/".$myrow['msg_image'];}
               echo "<td valign=\"top\" width=\"5%\" align=\"center\"><img src=\"$imgtmp\" alt=\"\" border=\"0\" /></td>";
            } else {
               echo "<td valign=\"top\" width=\"5%' align=\"center\">&nbsp;</td>";
            }
         }
         $myrow['subject']=strip_tags($myrow['subject']);
         $posterdata = get_userdata_from_id($myrow['from_userid']);
         if ($dossier=="All") {$myrow['dossier']="All";}
         if (!array_key_exists($myrow['dossier'],$tempo)) {$tempo[$myrow['dossier']]=0;}
         echo "<td valign=\"middle\" align=\"center\" width=\"10%\"><a href=\"readpmsg.php?start=".$tempo[$myrow['dossier']]."&amp;total_messages=$total_messages&amp;dossier=".urlencode($myrow['dossier'])."\" class=\"noir\">";
         if ($posterdata['uid']<>1) {
            echo $posterdata['uname'];
         } else {
            echo $sitename;
         }
         echo "</a></td>";
         echo "<td valign=\"middle\">".aff_langue($myrow['subject'])."</td>";
         echo "<td valign=\"middle\" align=\"center\" width=\"20%\">".$myrow['msg_time']."</td></tr>";
         $tempo[$myrow['dossier']]=$tempo[$myrow['dossier']]+1;
         $count++;
      }

      if ($ibid=theme_image("forum/icons/$language/send.gif")) {$imgtmpS=$ibid;} else {$imgtmpS="images/forum/icons/$language/send.gif";}
      if ($ibid=theme_image("forum/icons/$language/delete.gif")) {$imgtmpD=$ibid;} else {$imgtmpD="images/forum/icons/$language/delete.gif";}
      if ($display) {
         echo "<tr class=\"header\">";
         echo "<td colspan=\"6\" align=\"left\"><a href=\"replypmsg.php?send=1\"><img src=\"$imgtmpS\" alt=\"\" border=\"0\" /></a>&nbsp;<input type=\"image\" src=\"$imgtmpD\" name=\"delete_messages\" value=\"delete_messages\" border=\"0\" /></td></tr>";
         echo "<input type=\"hidden\" name=\"total_messages\" value=\"$total_messages\">";
         echo "</form>";
      } else {
         echo "<tr class=\"header\">";
         echo "<td colspan=\"6\"><a href=\"replypmsg.php?send=1\"><img src=\"$imgtmpS\" alt=\"\" border=\"0\" /></a></td></tr>";
         echo "</form>";
      }
      echo "</table>";
      echo "<br />";

      echo "<br />";
      echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n";
      echo translate("Private Message")." - ".translate("Outbox");
      echo "</td></tr></table>\n";
      echo "<br />";
      $sql = "SELECT * FROM ".$NPDS_Prefix."priv_msgs WHERE from_userid = '".$userdata['uid']."' and type_msg='1' ORDER BY msg_id DESC";
      $resultID = sql_query($sql);
      if (!$resultID) {
         forumerror(0005);
      }
      echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"2\" border=\"0\">";
      echo "<form name=\"prvmsgB\" method=\"get\" action=\"replypmsg.php\">";
      echo "<tr>";
      echo "<td class=\"header\" align=\"center\" valign=\"middle\"><input name=\"allbox\" onclick=\"CheckAllB();\" type=\"checkbox\" value=\"Check All\" /></td>";
      echo "<td class=\"header\" align=\"center\" valign=\"middle\">";
      if ($ibid=theme_image("forum/download.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/download.gif";}
      echo "<img src=\"$imgtmp\" alt=\"\" border=\"0\" /></td>";
      if ($smilies) { echo "<td class=\"header\" align=\"center\" valign=\"middle\">&nbsp;</td>"; }
      echo "<td class=\"header\" align=\"center\">".translate("To")."</td>";
      echo "<td class=\"header\" align=\"center\">".translate("Subject")."</td>";
      echo "<td class=\"header\" align=\"center\">".translate("Date")."</td>";
      echo "</tr>";
      if (!$total_messages = sql_num_rows($resultID)) {
         $display=0;
         echo "<td colspan=\"6\" align=\"center\">".translate("You don't have any Messages.")."</td></tr>\n";
      } else {
         $display=1;
      }
      $count=0;
      while ($myrow = sql_fetch_assoc($resultID)) {
         $rowcolor=tablos();
         echo "<tr $rowcolor>";
         echo "<td valign=top width=\"2%\" align=\"center\"><input type=\"checkbox\" onclick=\"CheckCheckAllB();\" name=\"msg_id[$count]\" value=\"".$myrow['msg_id']."\" /></td>";
         if ($myrow['read_msg']=="1") {
            echo "<td valign=\"top\" width=\"5%\" align=\"center\">&nbsp;</td>";
         } else {
            if ($ibid=theme_image("forum/read.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/read.gif";}
            echo "<td valign=\"top\" width=\"5%\" align=\"center\"><img src=\"$imgtmp\" border=\"0\" alt=\"".translate("Not Read")."\" /></td>";
         }
         if ($smilies) {
            if ($myrow['msg_image']!="") {
               if ($ibid=theme_image("forum/subject/".$myrow['msg_image'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/subject/".$myrow['msg_image'];}
               echo "<td valign=\"top\" width=\"5%\" align=\"center\"><img src=\"$imgtmp\" alt=\"\" border=\"0\" /></td>";
            } else {
               echo "<td valign=\"top\" width=\"5%\" align=\"center\">&nbsp;</td>";
            }
         }
         $myrow['subject']=strip_tags($myrow['subject']);
         $posterdata = get_userdata_from_id($myrow['to_userid']);
         echo "<td valign=\"middle\" align=\"center\" width=\"10%\"><a href=\"readpmsg.php?start=$count&amp;total_messages=$total_messages&amp;type=outbox\" class=\"noir\">".$posterdata['uname']."</a></td>";
         echo "<td valign=\"middle\">".aff_langue($myrow['subject'])."</td>";
         echo "<td valign=\"middle\" align=\"center\" width=\"20%\">".$myrow['msg_time']."</td></tr>";
         $count++;
      }
      if ($display) {
         echo "<tr class=\"header\">";
         if ($ibid=theme_image("forum/icons/$language/delete.gif")) {$imgtmpD=$ibid;} else {$imgtmpD="images/forum/icons/$language/delete.gif";}
         echo "<td colspan=\"6\"><input type=\"image\" src=\"$imgtmpD\" name=\"delete_messages\" value=\"delete_messages\" border=\"0\" /></td></tr>";
         echo "<input type=\"hidden\" name=\"total_messages\" value=\"$total_messages\" />";
         echo "<input type=\"hidden\" name=\"type\" value=\"outbox\" />";
      }
      echo "</form></table>";
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
        var TotalBoxes = 0;
        var TotalOn = 0;
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
        var TotalBoxes = 0;
        var TotalOn = 0;
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
      closetable();
      include('footer.php');
   }
?>
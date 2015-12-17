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
/* 2003 by snipe / vote unique, implémentation de la table appli_log    */
/************************************************************************/
if (!function_exists("Mysql_Connexion")) {
   include ("mainfile.php");
}
// ----------------------------------------------------------------------------
// Specified the index and the name of the application for the table appli_log
$al_id = 1;
$al_nom = "Poll";
// ----------------------------------------------------------------------------
function pollCollector($pollID, $voteID, $forwarder) {
     global $NPDS_Prefix;

     if ($voteID) {
        global $setCookies, $al_id, $al_nom, $dns_verif;

        $voteValid="1";
        $result = sql_query("SELECT timeStamp FROM ".$NPDS_Prefix."poll_desc WHERE pollID='$pollID'");
        list($timeStamp) = sql_fetch_row($result);
        $cookieName = "poll".$NPDS_Prefix.$timeStamp;
        global $$cookieName;
        if ($$cookieName=="1") {
           $voteValid="0";
        } else {
           setcookie("$cookieName","1",time()+86400);
        }

        global $user;
        if ($user) {
           global $cookie;
           $user_req="or al_uid='$cookie[0]'";
        } else {
           $cookie[0]="1";
           $user_req="";
        }
        if ($setCookies=="1") {
           $ip=getip();
           if ($dns_verif)
              $hostname="or al_hostname='".@gethostbyaddr($ip)."' ";
           else
              $hostname="";
           $sql="select al_id from ".$NPDS_Prefix."appli_log where al_id='$al_id' and al_subid='$pollID' and (al_ip='$ip' ".$hostname.$user_req.")";
           if ($result = sql_fetch_row(sql_query($sql))) {
              $voteValid="0";
           }
        }
        if ($voteValid=="1") {
           $ip=getip();
           if ($dns_verif)
              $hostname=@gethostbyaddr($ip);
           else
              $hostname="";
           sql_query("INSERT INTO ".$NPDS_Prefix."appli_log (al_id, al_name, al_subid, al_date, al_uid, al_data, al_ip, al_hostname) VALUES ('$al_id', '$al_nom', '$pollID', now(), '$cookie[0]', '$voteID', '$ip', '$hostname')");
           sql_query("UPDATE ".$NPDS_Prefix."poll_data SET optionCount=optionCount+1 WHERE (pollID='$pollID') AND (voteID='$voteID')");
           sql_query("UPDATE ".$NPDS_Prefix."poll_desc SET voters=voters+1 WHERE pollID='$pollID'");
        }
     }
     Header("Location: $forwarder");
}

function pollList() {
   global $NPDS_Prefix;
   echo '
   <h2>'.translate("Survey").'</h2>
   <div class="row">';
     $result = sql_query("SELECT pollID, pollTitle, voters FROM ".$NPDS_Prefix."poll_desc ORDER BY timeStamp");
     while($object = sql_fetch_assoc($result)) {
        $id = $object['pollID'];
        $pollTitle = $object['pollTitle'];
        $voters = $object['voters'];
        $result2 = sql_query("SELECT SUM(optionCount) AS SUM FROM ".$NPDS_Prefix."poll_data WHERE pollID='$id'");
        list ($sum) = sql_fetch_row($result2);
        echo '<div class="col-sm-8">'.aff_langue($pollTitle).'</div>';
        echo '<div class="col-sm-4 text-xs-right">(<a href="pollBooth.php?op=results&amp;pollID='.$id.'">'.translate("Results").'</a> - '.$sum.' '.translate("votes").')</div>';
     }
     echo '
     </div>';
}

function pollResults($pollID) {
   global $NPDS_Prefix, $maxOptions, $setCookies;

   if (!isset($pollID) OR empty($pollID)) $pollID = 1;
   $result = sql_query("SELECT pollID, pollTitle, timeStamp FROM ".$NPDS_Prefix."poll_desc WHERE pollID='$pollID'");
   $holdtitle = sql_fetch_row($result);
      list(,$pollTitle) = sql_fetch_row($result);

   echo '
   <h3>'.translate("Results").'</h3>
   <h4> '.$pollTitle.'</h4>';
     $result = sql_query("SELECT SUM(optionCount) AS SUM FROM ".$NPDS_Prefix."poll_data WHERE pollID='$pollID'");
     list($sum) = sql_fetch_row($result);
     for ($i = 1; $i <= $maxOptions; $i++) {
        $result = sql_query("SELECT optionText, optionCount, voteID FROM ".$NPDS_Prefix."poll_data WHERE (pollID='$pollID') AND (voteID='$i')");
        $object = sql_fetch_assoc($result);
        $optionText = $object['optionText'];
        $optionCount = $object['optionCount'];
        if ($optionText!= "") {
           if ($sum) {
              $percent = 100*$optionCount/$sum;
              $percentInt = (int)$percent;
           } else {
              $percentInt = 0;
           }
           echo '
         <div class="row">
            <div class="col-sm-4">'.aff_langue($optionText).'</div>
            <div class="col-sm-7">
               <progress class="progress  progress-striped" value="'.$percentInt.'" max="100">
                  <div class="progress">
                     <span class="progress-bar" role="progressbar" aria-valuenow="'.$percentInt.'%" aria-valuemin="0" aria-valuemax="100" style="width:'.$percentInt.'%;">&nbsp;'.$percentInt.'%</span>
                  </div>
               </progress>
            </div>
            <div class="col-sm-1">('.wrh($optionCount).')</div></div>';
        }
     }
     echo '<br />';
     echo '<p class="text-xs-center"><b>'.translate("Total Votes: ").' '.$sum.'</b></p><br />';
     if ($setCookies>0) {
        echo '<p class="text-danger">'.translate("We allow just one vote per poll.").'</p>';
     }
}

#autodoc pollboxbooth($pollID,$pollClose) : Construit le blocs sondages / code du mainfile avec autre présentation
function pollboxbooth($pollID,$pollClose) {
   global $NPDS_Prefix, $maxOptions, $boxTitle, $boxContent, $userimg, $language, $pollcomm;
   if (!isset($pollID)) $pollID = 1;
   if (!isset($url)) $url = sprintf("pollBooth.php?op=results&amp;pollID=%d", $pollID);
   $boxContent = '
   <form action="pollBooth.php" method="post">
         <input type="hidden" name="pollID" value="'.$pollID.'" />
         <input type="hidden" name="forwarder" value="'.$url.'" />';
   $result = sql_query("SELECT pollTitle, voters FROM ".$NPDS_Prefix."poll_desc WHERE pollID='$pollID'");
   list($pollTitle, $voters) = sql_fetch_row($result);
   global $block_title;
   if ($block_title=="")
      $boxTitle=translate("Survey");
   else
      $boxTitle=$block_title;
   $boxContent .= '
         <h4>'.aff_langue($pollTitle).'</h4>';

   $result = sql_query("SELECT pollID, optionText, optionCount, voteID FROM ".$NPDS_Prefix."poll_data WHERE (pollID='$pollID' and optionText<>'') ORDER BY voteID");
   $sum = 0;
   if (!$pollClose) {
      while($object=sql_fetch_assoc($result)) {
         $boxContent .= '
         <div class="c-inputs-stacked">
            <label class="c-input c-radio">
               <input type="radio" name="voteID" value="'.$object['voteID'].'" />&nbsp;'.aff_langue($object['optionText']).'
               <span class="c-indicator"></span>
            </label>';
         $sum = $sum + $object['optionCount'];
      }
   } else {
      while($object=sql_fetch_assoc($result)) {
         $boxContent .= "&nbsp;".aff_langue($object['optionText'])."<br />\n";
         $sum = $sum + $object['optionCount'];
      }
   }
   if (!$pollClose) {
      $inputvote = '
      <div class="col-sm-2">
      <button class="btn btn-primary-outline btn-sm btn-block" type="submit" value="'.translate("Vote").'" title="'.translate("Vote").'" /><i class="fa fa-check fa-lg"></i> '.translate("Vote").'</button>
      </div>';
   }
   $boxContent .= '
   <br /><div class="row">'.$inputvote.'
   <div class="col-sm-2"><button class="btn btn-secondary btn-sm btn-block" href="pollBooth.php?op=results&amp;pollID='.$pollID.'" >R&eacute;sultats</button>
   </div>
   </div>
   </div>
   </form>';
   $boxContent .= '<ul><li><a href="pollBooth.php">'.translate("Past Surveys").'</a></li>';
   if ($pollcomm) {
      if (file_exists("modules/comments/pollBoth.conf.php")) {
         include ("modules/comments/pollBoth.conf.php");
      }
      list($numcom) = sql_fetch_row(sql_query("SELECT COUNT(*) FROM ".$NPDS_Prefix."posts WHERE forum_id='$forum' AND topic_id='$pollID' AND post_aff='1'"));
      $boxContent .= '<li>'.translate("Votes: ").' '.$sum.'</li><li>'.translate("comments:").' '.$numcom.'</li>';
   } else {
      $boxContent .= '<li>'.translate("Votes: ").' '.$sum.'</li>';
   }
      $boxContent .= '</ul>';
   echo '<div>'.$boxContent.'</div>'; 
}

function PollMain_aff($pollID) {
   $boxContent = '<p align="center"><strong><a href="pollBooth.php">';
   $boxContent .= translate("Past Surveys").'</a></strong></p>';
   echo $boxContent;
}

if (!isset($pollID)) {
   include ('header.php');
   pollList();
   include ('footer.php');
}
settype($pollID,'integer');
if (isset($forwarder)) {
   if (isset($voteID))
      pollCollector($pollID, $voteID, $forwarder);
   else
      Header("Location: $forwarder");
} elseif ($op=="results") {
   list($ibid,$pollClose)=pollSecur($pollID);
   if ($pollID==$ibid) {
      include ("header.php");
      echo '<h2>'.translate("Survey").'</h2>';
      if (!$pollClose) {
         $block_title= '<h3>'.translate("Vote").'</h3>';
         echo $block_title;
        pollboxbooth($pollID,$pollClose);
         echo '';
      } else {
         echo '';
         PollMain_aff($pollID);
      }
      pollResults($pollID);
      echo '';
      if ($pollcomm) {
         if (file_exists("modules/comments/pollBoth.conf.php")) {
            include ("modules/comments/pollBoth.conf.php");
            if ($pollClose==99)
               $anonpost=0;
            include ("modules/comments/comments.php");
         }
      }
      include ("footer.php");
   } else {
      Header("Location: $forwarder");
   }
}
?>
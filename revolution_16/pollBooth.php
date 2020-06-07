<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2020 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
/* 2003 by snipe / vote unique, implémentation de la table appli_log    */
/************************************************************************/
if (!function_exists("Mysql_Connexion"))
   include ("mainfile.php");
// ----------------------------------------------------------------------------
// Specified the index and the name of the application for the table appli_log
$al_id = 1;
$al_nom = 'Poll';
// ----------------------------------------------------------------------------
function pollCollector($pollID, $voteID, $forwarder) {
     global $NPDS_Prefix;

     if ($voteID) {
        global $setCookies, $al_id, $al_nom, $dns_verif;

        $voteValid="1";
        $result = sql_query("SELECT timeStamp FROM ".$NPDS_Prefix."poll_desc WHERE pollID='$pollID'");
        list($timeStamp) = sql_fetch_row($result);
        $cookieName = 'poll'.$NPDS_Prefix.$timeStamp;
        global $$cookieName;
        if ($$cookieName=="1") {
           $voteValid="0";
        } else {
           setcookie("$cookieName","1",time()+86400);
        }

        global $user;
        if ($user) {
           global $cookie;
           $user_req="OR al_uid='$cookie[0]'";
        } else {
           $cookie[0]="1";
           $user_req='';
        }
        if ($setCookies=="1") {
           $ip=getip();
           if ($dns_verif)
              $hostname="OR al_hostname='".@gethostbyaddr($ip)."' ";
           else
              $hostname="";
           $sql="SELECT al_id FROM ".$NPDS_Prefix."appli_log WHERE al_id='$al_id' AND al_subid='$pollID' AND (al_ip='$ip' ".$hostname.$user_req.")";
           if ($result = sql_fetch_row(sql_query($sql))) {
              $voteValid="0";
           }
        }
        if ($voteValid=="1") {
           $ip=getip();
           if ($dns_verif)
              $hostname=@gethostbyaddr($ip);
           else
              $hostname='';
           sql_query("INSERT INTO ".$NPDS_Prefix."appli_log (al_id, al_name, al_subid, al_date, al_uid, al_data, al_ip, al_hostname) VALUES ('$al_id', '$al_nom', '$pollID', now(), '$cookie[0]', '$voteID', '$ip', '$hostname')");
           sql_query("UPDATE ".$NPDS_Prefix."poll_data SET optionCount=optionCount+1 WHERE (pollID='$pollID') AND (voteID='$voteID')");
           sql_query("UPDATE ".$NPDS_Prefix."poll_desc SET voters=voters+1 WHERE pollID='$pollID'");
        }
     }
     Header("Location: $forwarder");
}

function pollList() {
   global $NPDS_Prefix;
   $result = sql_query("SELECT pollID, pollTitle, voters FROM ".$NPDS_Prefix."poll_desc ORDER BY timeStamp");
   echo '
   <h2 class="mb-3">'.translate("Sondage").'</h2>
   <hr />
   <div class="row">';
   while($object = sql_fetch_assoc($result)) {
      $id = $object['pollID'];
      $pollTitle = $object['pollTitle'];
      $voters = $object['voters'];
      $result2 = sql_query("SELECT SUM(optionCount) AS SUM FROM ".$NPDS_Prefix."poll_data WHERE pollID='$id'");
      list ($sum) = sql_fetch_row($result2);
      echo '
      <div class="col-sm-8">'.aff_langue($pollTitle).'</div>
      <div class="col-sm-4 text-right">(<a href="pollBooth.php?op=results&amp;pollID='.$id.'">'.translate("Résultats").'</a> - '.$sum.' '.translate("votes").')</div>';
   }
   echo '
   </div>';
}

function pollResults($pollID) {
   global $NPDS_Prefix, $maxOptions, $setCookies;

   if (!isset($pollID) OR empty($pollID)) $pollID = 1;
   $result = sql_query("SELECT pollID, pollTitle, timeStamp FROM ".$NPDS_Prefix."poll_desc WHERE pollID='$pollID'");
      list(,$pollTitle) = sql_fetch_row($result);

   echo '
   <h3 class="my-3">'.$pollTitle.'</h3>';
     $result = sql_query("SELECT SUM(optionCount) AS SUM FROM ".$NPDS_Prefix."poll_data WHERE pollID='$pollID'");
     list($sum) = sql_fetch_row($result);
     echo '
   <h4><span class="badge badge-secondary">'.$sum.'</span>&nbsp;'.translate("Résultats").'</h4>';
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
      <div class="col-sm-5 mt-3">'.aff_langue($optionText).'</div>
      <div class="col-sm-7">
         <span class="badge badge-secondary mb-1">'.wrh($optionCount).'</span>
            <div class="progress">
               <span class="progress-bar" role="progressbar" aria-valuenow="'.$percentInt.'%" aria-valuemin="0" aria-valuemax="100" style="width:'.$percentInt.'%;" title="'.$percentInt.'%" data-toggle="tooltip"></span>
            </div>
      </div>
   </div>';
        }
     }
     echo '<br />';
     echo '<p class="text-center"><b>'.translate("Nombre total de votes: ").' '.$sum.'</b></p><br />';
     if ($setCookies>0) {
        echo '<p class="text-danger">'.translate("Un seul vote par sondage.").'</p>';
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
   if ($block_title=='')
      $boxTitle=translate("Sondage");
   else
      $boxTitle=$block_title;
   $boxContent .= '
         <h4>'.aff_langue($pollTitle).'</h4>';

   $result = sql_query("SELECT pollID, optionText, optionCount, voteID FROM ".$NPDS_Prefix."poll_data WHERE (pollID='$pollID' AND optionText<>'') ORDER BY voteID");
   $sum = 0; $j=0;
   if (!$pollClose) {
      $boxContent .= '
            <div class="custom-controls-stacked">';
      while($object=sql_fetch_assoc($result)) {
         $boxContent .= '
               <div class="custom-control custom-radio">
                  <input type="radio" class="custom-control-input" id="voteID'.$j.'" name="voteID" value="'.$object['voteID'].'" />
                  <label class="custom-control-label" for="voteID'.$j.'">'.aff_langue($object['optionText']).'</label>
               </div>';
         $sum = $sum + $object['optionCount'];
         $j++;
      }
      $boxContent .= '
            </div>
            <div class="clearfix"></div>';
   } else {
      while($object=sql_fetch_assoc($result)) {
         $boxContent .= "&nbsp;".aff_langue($object['optionText'])."<br />\n";
         $sum = $sum + $object['optionCount'];
      }
   }
   if (!$pollClose) {
      $inputvote = '
         <button class="btn btn-primary btn-sm my-2" type="submit" value="'.translate("Voter").'" title="'.translate("Voter").'" />'.translate("Voter").'</button>';
   }
   $boxContent .= '
         <div class="form-group">'.$inputvote.'</div>
   </form>';
   $boxContent .= '<div><ul><li><a href="pollBooth.php">'.translate("Anciens sondages").'</a></li>';
   if ($pollcomm) {
      if (file_exists("modules/comments/pollBoth.conf.php"))
         include ("modules/comments/pollBoth.conf.php");
      list($numcom) = sql_fetch_row(sql_query("SELECT COUNT(*) FROM ".$NPDS_Prefix."posts WHERE forum_id='$forum' AND topic_id='$pollID' AND post_aff='1'"));
      $boxContent .= '<li>'.translate("Votes : ").' '.$sum.'</li><li>'.translate("Commentaire(s) : ").' '.$numcom.'</li>';
   } else
      $boxContent .= '<li>'.translate("Votes : ").' '.$sum.'</li>';
   $boxContent .= '</ul></div>';
   echo '<div class="card card-body">'.$boxContent.'</div>'; 
}

function PollMain_aff($pollID) {
   $boxContent = '<p><strong><a href="pollBooth.php">'.translate("Anciens sondages").'</a></strong></p>';
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
} elseif ($op=='results') {
   list($ibid,$pollClose)=pollSecur($pollID);
   if ($pollID==$ibid) {
      include ("header.php");
      echo '<h2>'.translate("Survey").'</h2><hr />';
      pollResults($pollID);
      if (!$pollClose) {
         $block_title= '<h3>'.translate("Voter").'</h3>';
         echo $block_title;
        pollboxbooth($pollID,$pollClose);
      } else
         PollMain_aff($pollID);
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

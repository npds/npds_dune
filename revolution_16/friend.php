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

function FriendSend($sid, $archive) {
   global $NPDS_Prefix;
   settype($sid,"integer");
   settype($archive, "integer");
   $result=sql_query("SELECT title, aid FROM ".$NPDS_Prefix."stories WHERE sid='$sid'");
   list($title, $aid) = sql_fetch_row($result);
   if (!$aid) {
       header ("Location: index.php");
   }
   include ("header.php");

   echo '
   <div class="card card-block">
   <h2><i class="fa fa-at fa-lg text-muted"></i>&nbsp;'.translate("Send Story to a Friend").'</h2>
   <hr />
   <p class="lead">'.translate("You will send the story").' : <strong>'.aff_langue($title).'</strong></p>
   <form action="friend.php" method="post">
      <input type="hidden" name="sid" value="'.$sid.'" />';
   global $user;
   if ($user) {
      global $cookie;
      $result=sql_query("SELECT name, email FROM ".$NPDS_Prefix."users WHERE uname='$cookie[1]'");
      list($yn, $ye) = sql_fetch_row($result);
   }
   echo '
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="fname">'.translate("Friend Name").'</label>
         <div class="col-sm-8">
            <input type="text" class="form-control" id="fname" name="fname" required="required" maxlength="100" />
            <span class="help-block text-xs-right"><span class="muted" id="countcar_fname"></span></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="fmail">'.translate("Friend Email").'</label>
         <div class="col-sm-8">
            <input type="email" class="form-control" id="fmail" name="fmail" required="required" maxlength="100" />
            <span class="help-block text-xs-right"><span class="muted" id="countcar_fmail"></span></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="yname">'.translate("Your Name").'</label>
         <div class="col-sm-8">
            <input type="text" class="form-control" id="yname" name="yname" value="'.$yn.'" maxlength="100" required="required" />
            <span class="help-block text-xs-right"><span class="muted" id="countcar_yname"></span></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="ymail">'.translate("Your Email").'</label>
         <div class="col-sm-8">
            <input type="email" class="form-control" id="ymail" name="ymail" value="'.$ye.'" maxlength="100" required="required" />
            <span class="help-block text-xs-right"><span class="muted" id="countcar_ymail"></span></span>
         </div>
      </div>';
   echo ''.Q_spambot();
   echo '
   <input type="hidden" name="archive" value="'.$archive.'" />
   <input type="hidden" name="op" value="SendStory" />
      <div class="form-group row">
         <div class="col-sm-8 offset-sm-4">
            <button type="submit" class="btn btn-primary" title="'.translate("Send").'"><i class="fa fa-lg fa-at"></i>&nbsp;'.translate("Send").'</button>
         </div>
      </div>
   </form>
   <script type="text/javascript">
   //<![CDATA[
   $(document).ready(function() {
      inpandfieldlen("yname",100);
      inpandfieldlen("ymail",100);
      inpandfieldlen("fname",100);
      inpandfieldlen("fmail",100);
      });
   //]]>
   </script>';
   adminfoot('fv','','','');
}

function SendStory($sid, $yname, $ymail, $fname, $fmail, $archive, $asb_question, $asb_reponse) {
   global $user;
   if (!$user) {
      //anti_spambot
      if (!R_spambot($asb_question, $asb_reponse, '')) {
         Ecr_Log('security', "Send-Story Anti-Spam : name=".$yname." / mail=".$ymail, '');
         redirect_url("index.php");
         die();
      }
   }

   global $sitename, $nuke_url;
   global $NPDS_Prefix;
   settype($sid,'integer');
   settype($archive, 'integer');
   $result2=sql_query("SELECT title, time, topic FROM ".$NPDS_Prefix."stories WHERE sid='$sid'");
   list($title, $time, $topic) = sql_fetch_row($result2);
   $result3=sql_query("SELECT topictext FROM ".$NPDS_Prefix."topics WHERE topicid='$topic'");
   list($topictext) = sql_fetch_row($result3);
   $subject = translate("Interesting Article at")." $sitename";
   $fname=removeHack($fname);
   $message = "".translate("Hello")." $fname :\n\n".translate("Your Friend")." $yname ".translate("considered the following article interesting and wanted to send it to you.")."\n\n".aff_langue($title)."\n".translate("Date:")." $time\n".translate("Topic:")." ".aff_langue($topictext)."\n\n".translate("The Article")." : <a href=\"$nuke_url/article.php?sid=$sid&amp;archive=$archive\">$nuke_url/article.php?sid=$sid&amp;archive=$archive</a>\n\n";
   include("signat.php");
   $fmail=removeHack($fmail);
   $subject=removeHack($subject);
   $message=removeHack($message);
   $yname=removeHack($yname);
   $ymail=removeHack($ymail);
   $stop=false;
   if ((!$fmail) || ($fmail=="") || (!preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i',$fmail))) $stop = true;
   if ((!$ymail) || ($ymail=="") || (!preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i',$ymail))) $stop = true;
   if (!$stop) {
      send_email($fmail, $subject, $message, $ymail, false,'html');
   } else {
     $title='';
     $fname='';
   }
   $title = urlencode(aff_langue($title));
   $fname = urlencode($fname);
   Header("Location: friend.php?op=StorySent&title=$title&fname=$fname");
}

function StorySent($title, $fname) {
   include ("header.php");
   $title = urldecode($title);
   $fname = urldecode($fname);
   echo '<p class="lead text-xs-center">';
   if ($fname=='') {
      echo '<span class="text-danger">'.translate("ERROR: Invalid email").'</span>';
   } else {
      echo '<span class="text-success">'.translate("Story").' <strong>'.stripslashes($title).'</strong> '.translate("has been sent to").'&nbsp;'.$fname.'<br />'.translate("Thanks!").'</span>';
   }
   echo "</p>";
   
   include ("footer.php");
}

function RecommendSite() {
   global $user;
   if ($user) {
      global $cookie, $NPDS_Prefix;
      $result=sql_query("SELECT name, email FROM ".$NPDS_Prefix."users WHERE uname='$cookie[1]'");
      list($yn, $ye) = sql_fetch_row($result);
   } else {
      $yn=''; $ye='';
   }
   include ("header.php");
   echo '
   <div class="card card-block">
   <h2>'.translate("Recommend this Site to a Friend").'</h2>
   <hr />
   <form action="friend.php" method="post">
      <input type="hidden" name="op" value="SendSite" />
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="yname">'.translate("Your Name").'</label>
         <div class="col-sm-8">
            <input type="text" class="form-control" id="yname" name="yname" value="'.$yn.'" required="required" maxlength="100" />
            <span class="help-block text-xs-right"><span class="muted" id="countcar_yname"></span></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="ymail">'.translate("Your Email").'</label>
         <div class="col-sm-8">
            <input type="email" class="form-control" id="ymail" name="ymail" value="'.$ye.'" required="required" maxlength="100" />
            <span class="help-block text-xs-right"><span class="muted" id="countcar_ymail"></span></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="fname">'.translate("Friend Name").'</label>
         <div class="col-sm-8">
            <input type="text" class="form-control" id="fname" name="fname" required="required" maxlength="100" />
            <span class="help-block text-xs-right"><span class="muted" id="countcar_fname"></span></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="fmail">'.translate("Friend Email").'</label>
         <div class="col-sm-8">
            <input type="email" class="form-control" id="fmail" name="fmail" required="required" maxlength="100" />
            <span class="help-block text-xs-right"><span class="muted" id="countcar_fmail"></span></span>
         </div>
      </div>
      '.Q_spambot().'
      <div class="form-group row">
         <div class="col-sm-8 offset-sm-4">
            <button type="submit" class="btn btn-primary"><i class="fa fa-lg fa-at"></i>&nbsp;'.translate("Send").'</button>
         </div>
      </div>
   </form>
   <script type="text/javascript">
   //<![CDATA[
   $(document).ready(function() {
      inpandfieldlen("yname",100);
      inpandfieldlen("ymail",100);
      inpandfieldlen("fname",100);
      inpandfieldlen("fmail",100);
      });
   //]]>
   </script>';
   adminfoot('fv','','','');
}

function SendSite($yname, $ymail, $fname, $fmail, $asb_question, $asb_reponse) {
   global $user;
   if (!$user) {
      //anti_spambot
      if (!R_spambot($asb_question, $asb_reponse, "")) {
         Ecr_Log('security', "Friend Anti-Spam : name=".$yname." / mail=".$ymail, '');
         redirect_url("index.php");
         die();
      }
   }

   global $sitename, $nuke_url;
   $subject = translate("Interesting Site:")." $sitename";
   $fname=removeHack($fname);
   $message = translate("Hello")." $fname :\n\n".translate("Your Friend")." $yname ".translate("considered our site")." $sitename ".translate("interesting and wanted to send it to you.")."\n\n$sitename : <a href=\"$nuke_url\">$nuke_url</a>\n\n";
   include("signat.php");
   $fmail=removeHack($fmail);
   $subject=removeHack($subject);
   $message=removeHack($message);
   $yname=removeHack($yname);
   $ymail=removeHack($ymail);
   $stop=false;
   if ((!$fmail) || ($fmail=='') || (!preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i',$fmail))) $stop = true;
   if ((!$ymail) || ($ymail=='') || (!preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i',$ymail))) $stop = true;
   if (!$stop) {
      send_email($fmail, $subject, $message, $ymail, false,'html');
   } else {
     $fname='';
   }
   Header("Location: friend.php?op=SiteSent&fname=$fname");
}

function SiteSent($fname) {
   include ('header.php');
   if ($fname=='') {
      echo '
         <div class="alert alert-danger lead" role="alert">
            <i class="fa fa-exclamation-triangle fa-lg"></i>&nbsp;
            '.translate("ERROR: Invalid email").'
         </div>';
   } else {
      echo '
      <div class="alert alert-success lead" role="alert">
         <i class="fa fa-exclamation-triangle fa-lg"></i>&nbsp;
         '.translate("The reference to our site has been sent to").' '.$fname.', <br />
         <strong>'.translate("Thanks for recommend us!").'</strong>
      </div>';
   }
   include ('footer.php');
}

settype($op,'string');
switch ($op) {
   case 'FriendSend':
      FriendSend($sid, $archive);
   break;
   case 'SendStory':
      SendStory($sid, $yname, $ymail, $fname, $fmail, $archive, $asb_question, $asb_reponse);
   break;
   case 'StorySent':
      StorySent($title, $fname);
   break;
   case 'SendSite':
      SendSite($yname, $ymail, $fname, $fmail, $asb_question, $asb_reponse);
   break;
   case 'SiteSent':
      SiteSent($fname);
   break;
   default:
      RecommendSite();
   break;
}
?>

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

function FriendSend($sid, $archive) {
   global $NPDS_Prefix;
   settype($sid,"integer");
   settype($archive, "integer");
   $result=sql_query("select title, aid  from ".$NPDS_Prefix."stories where sid='$sid'");
   list($title, $aid) = sql_fetch_row($result);
   if (!$aid) {
       header ("Location: index.php");
   }
   include ("header.php");

   echo '<h2>'.translate("Send Story to a Friend").'</h2>';
   echo '<p class="lead">'.translate("You will send the story").' <strong>'.aff_langue($title).'</strong> '.translate("to a specified friend:").'</p>
   <form class="form-horizontal" role="form" action="friend.php" method="post"><input type="hidden" name="'.sid.'" value="'.$sid.'" />';
   global $user;
   if ($user) {
      global $cookie;
      $result=sql_query("select name, email from ".$NPDS_Prefix."users where uname='$cookie[1]'");
      list($yn, $ye) = sql_fetch_row($result);
   }
	echo '<div class="form-group">
			<div class="col-sm-4">
				<label class="form-control-label">'.translate("Your Name: ").'</label>
            </div>
			<div class="col-sm-8">
				<input type="text" class="form-control" name="yname" value="'.$yn.'" />
			</div>
		</div>';
	echo '<div class="form-group">
			<div class="col-sm-4">
				<label class="form-control-label">'.translate("Your Email: ").'</label>
            </div>
			<div class="col-sm-8">
				<input type="text" class="form-control" name="ymail" value="'.$ye.'" />
			</div>
		</div>';
	echo '<div class="form-group">
			<div class="col-sm-4">
				<label class="form-control-label">'.translate("Friend Name: ").'</label>
            </div>
			<div class="col-sm-8">
				<input type="text" class="form-control" name="fname" />
			</div>
		</div>';
	echo '<div class="form-group">
			<div class="col-sm-4">
				<label class="form-control-label">'.translate("Friend Email: ").'</label>
            </div>
			<div class="col-sm-8">
				<input type="text" class="form-control" name="fmail" />
			</div>
		</div>';
	echo "".Q_spambot();
	echo '<input type="hidden" name="archive" value="'.$archive.'" />';
	echo '<input type="hidden" name="op" value="SendStory" />';  
	echo '<div class="form-group">
			<div class="col-sm-offset-4 col-sm-8">
				<button type="submit" class="btn btn-primary" title="'.translate("Send").'"><i class="fa fa-lg fa-check"></i></button>
			</div>
		</div>';
	echo '</form>';
	include ('footer.php');
}

function SendStory($sid, $yname, $ymail, $fname, $fmail, $archive, $asb_question, $asb_reponse) {
   global $user;
   if (!$user) {
      //anti_spambot
      if (!R_spambot($asb_question, $asb_reponse, "")) {
         Ecr_Log("security", "Send-Story Anti-Spam : name=".$yname." / mail=".$ymail, "");
         redirect_url("index.php");
         die();
      }
   }

   global $sitename, $nuke_url;
   global $NPDS_Prefix;
   settype($sid,"integer");
   settype($archive, "integer");
   $result2=sql_query("select title, time, topic from ".$NPDS_Prefix."stories where sid='$sid'");
   list($title, $time, $topic) = sql_fetch_row($result2);
   $result3=sql_query("select topictext from ".$NPDS_Prefix."topics where topicid='$topic'");
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
      send_email($fmail, $subject, $message, $ymail, false,"html");
   } else {
     $title="";
     $fname="";
   }
   $title = urlencode(aff_langue($title));
   $fname = urlencode($fname);
   Header("Location: friend.php?op=StorySent&title=$title&fname=$fname");
}

function StorySent($title, $fname) {
   include ("header.php");
   
   $title = urldecode($title);
   $fname = urldecode($fname);
   echo '<p class="lead text-center">';
   if ($fname=="") {
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
      global $cookie;
      global $NPDS_Prefix;
      $result=sql_query("select name, email from ".$NPDS_Prefix."users where uname='$cookie[1]'");
      list($yn, $ye) = sql_fetch_row($result);
   } else {
      $yn=''; $ye='';
   }
   include ("header.php");
   echo '
   <h2>'.translate("Recommend this Site to a Friend").'</h2>
   <form class="" role="form" action="friend.php" method="post">
      <input type="hidden" name="op" value="SendSite" />
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="yname">'.translate("Your Name: ").'</label>
         <div class="col-sm-8">
            <input type="text" class="form-control" name="yname" value="'.$yn.'" required="required" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="ymail">'.translate("Your Email: ").'</label>
         <div class="col-sm-8">
            <input type="email" class="form-control" name="ymail" value="'.$ye.'" required="required" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="fname">'.translate("Friend Name: ").'</label>
         <div class="col-sm-8">
            <input type="text" class="form-control" name="fname" required="required" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="fmail">'.translate("Friend Email: ").'</label>
         <div class="col-sm-8">
            <input type="email" class="form-control" name="fmail" required="required" />
         </div>
      </div>
      '.Q_spambot().'
      <div class="form-group row">
         <div class="col-sm-offset-4 col-sm-8">
            <button type="submit" class="btn btn-primary" title="'.translate("Send").'"><i class="fa fa-lg fa-check"></i></button>
         </div>
      </div>
      </form>';

   include ('footer.php');
}

function SendSite($yname, $ymail, $fname, $fmail, $asb_question, $asb_reponse) {
   global $user;
   if (!$user) {
      //anti_spambot
      if (!R_spambot($asb_question, $asb_reponse, "")) {
         Ecr_Log("security", "Friend Anti-Spam : name=".$yname." / mail=".$ymail, "");
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
   if ((!$fmail) || ($fmail=="") || (!preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i',$fmail))) $stop = true;
   if ((!$ymail) || ($ymail=="") || (!preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i',$ymail))) $stop = true;
   if (!$stop) {
      send_email($fmail, $subject, $message, $ymail, false,"html");
   } else {
     $fname="";
   }
   Header("Location: friend.php?op=SiteSent&fname=$fname");
}

function SiteSent($fname) {
   include ('header.php');
   
   echo '<p class=" lead text-center">';
   if ($fname=="") {
      echo '<span class="text-danger">'.translate("ERROR: Invalid email").'</span>';
   } else {
      echo '
      <div class="alert alert-success" role="alert">
      '.translate("The reference to our site has been sent to").' '.$fname.', <br />
      <strong>'.translate("Thanks for recommend us!").'</strong>
      </div>';
   
   
   
//       echo '<span class="text-success">'.translate("The reference to our site has been sent to").' '.$fname,'';
//       echo '<br /><br />'.translate("Thanks for recommend us!").'</span>';
   }
   echo '</p>';
   
   include ('footer.php');
}

settype($op,'string');
switch ($op) {
   case "FriendSend":
        FriendSend($sid, $archive);
        break;

   case "SendStory":
        SendStory($sid, $yname, $ymail, $fname, $fmail, $archive, $asb_question, $asb_reponse);
        break;

   case "StorySent":
        StorySent($title, $fname);
        break;

   case "SendSite":
        SendSite($yname, $ymail, $fname, $fmail, $asb_question, $asb_reponse);
        break;

   case "SiteSent":
        SiteSent($fname);
        break;

   default:
        RecommendSite();
        break;
}
?>
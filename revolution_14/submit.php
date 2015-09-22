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

include ("publication.php");

if ($mod_admin_news>0) {
   if ($admin=="" and $user=="") {
      Header("Location: index.php");
      exit;
   }
   if ($mod_admin_news==1) {
      if ($user!="" and $admin=="") {
         global $cookie;
         $result = sql_query("select level from ".$NPDS_Prefix."users_status where uid='$cookie[0]'");
         if (sql_num_rows($result)==1) {
            list($userlevel) = sql_fetch_row($result);
            if ($userlevel==1) {
               Header("Location: index.php");
               exit;
            }
         }
      }
   }
}

function defaultDisplay() {
   global $NPDS_Prefix;

   include ('header.php');
   global $user, $anonymous;

   echo '<h2>'.translate("Submit News").'</h2>';

   if (isset($user)) $userinfo=getusrinfo($user);
   echo "<form class=\"form-horizontal\" rolr=\"form\" action=\"submit.php\" method=\"post\" name=\"adminForm\">";
   echo '<p class="lead"><strong>'.translate("Your Name").'</strong> : ';
   if ($user) {
      echo "<a href=\"user.php\">".$userinfo['name']."</a> [ <a href=\"user.php?op=logout\">".translate("Logout")."</a> ]</p>";
      echo "<input type=\"hidden\" name=\"name\" value=\"".$userinfo['name']."\" />";
   } else {
      echo "$anonymous [ <a href=\"user.php\">".translate("New User")."</a> ]</p>";
      echo "<input type=\"hidden\" name=\"name\" value=\"$anonymous\" />";
   }
	echo '<div class="form-group">
			<div class="col-sm-3">
				<label class="control-label"><strong>'.translate("Title").' </strong> ('.translate ("Be Descriptive, Clear and Simple").') : </label>
			</div>
			<div class="col-sm-9">
				<input type="text" name="subject" class="form-control">
			</div>
				<div class="col-sm-offset-3 col-sm-9">
					<p class="help-block"> '.translate("bad titles='Check This Out!' or 'An Article'.").'</p>
                </div>
			</div>';
	echo '<div class="form-group">
			<div class="col-sm-3">
				<label class="control-label"><strong>'.translate("Topic").'</strong> : </label>
			</div>
			<div class="col-sm-4">
				<select class="form-control" name="topic">';
	$toplist = sql_query("select topicid, topictext from ".$NPDS_Prefix."topics order by topictext");
	echo "<option value=\"\">".translate("Select Topic")."</option>\n";
	while (list($topicid, $topics) = sql_fetch_row($toplist)) {
		if ($topicid==$topic) { $sel = "selected=\"selected\" "; }
		echo "<option $sel value=\"$topicid\">".aff_langue($topics)."</option>\n";
		$sel = "";
   }				  
	echo '</select>
			</div>
		</div>'; 
   
	echo '<div class="form-group">
			<div class="col-sm-12">
				<label class="control-label"><strong>'.translate("Intro Text").'</strong> ( '.translate("HTML is fine, but double check those URLs and HTML tags!").' ) :</label>
			</div>
			<div class="col-sm-12">
				<textarea class="form-control" rows="25" name="story"></textarea>';
	echo aff_editeur("story", "true");				  
	echo '</div>
			</div>';
	echo '<div class="form-group">
			<div class="col-sm-12">
				<label class="control-label"><strong>'.translate("Full Text").'</strong> :</label>
			</div>
			<div class="col-sm-12">
				<textarea class="form-control" rows="25" name="bodytext"></textarea>';
	echo aff_editeur("bodytext", "true");				  
	echo '</div>
			</div>';

	publication(0,0,0,0,0, 0,0,0,0,0, 0);

	echo "<hr />";

	echo '<div class="form-group">
			<div class="col-sm-12">
				<input class="btn btn-default" type="submit" name="op" value="'.translate("Preview").'" />
					'.translate("You must preview once before you can submit").'
				</div>
			</div>';
	echo "</form>";
	
   include ('footer.php');
}

function PreviewStory($name, $subject, $story, $bodytext,$topic, $deb_day,$deb_month,$deb_year,$deb_hour,$deb_min, $fin_day,$fin_month,$fin_year,$fin_hour,$fin_min, $epur) {
	global $tipath, $NPDS_Prefix;

	include ('header.php');
	$subject = stripslashes(str_replace("\"","&quot;",(strip_tags($subject))));
	$story = stripslashes($story);
	$bodytext = stripslashes($bodytext);

	echo '<h2>'.translate("Submit News").'</h2>';
	echo '<form class="form-horizontal" role="form" action="submit.php" method="post" name="adminForm">';
	echo '<p class="lead"><strong>'.translate("Your Name").'</strong> : '.$name.'</p>';
	echo "<input type=\"hidden\" name=\"name\" value=\"$name\" />";

	if ($topic=="") {
		$topicimage="all-topics.gif";
		$warning = '<strong>'.translate("Select Topic").'</strong>';
	} else {
		$warning = "";
		$result = sql_query("select topictext, topicimage from ".$NPDS_Prefix."topics where topicid='$topic'");
		list($topictext, $topicimage) = sql_fetch_row($result);
	}
	$no_img=false;
	if ((file_exists("$tipath$topicimage")) and ($topicimage!="")) {
      echo "<p class=\"col-sm-offset-10\"><img class=\"img-responsive\" src=\"".$tipath.$topicimage."\" alt=\"\" /></p>";
	} else {
		echo "";
		$no_img=true;
	}
	$storyX=aff_code($story);
	$bodytextX=aff_code($bodytext);
	themepreview($subject, $storyX, $bodytextX);
	if ($no_img) {
		echo "<strong>".aff_langue($topictext)."</strong>";
	}
	echo "$warning";

	echo '<div class="form-group">
			<div class="col-sm-3">
				<label class="control-label"><strong>'.translate("Title").' </strong> :</label>
			</div>
			<div class="col-sm-9">
				<input type="text" name="subject" class="form-control" value="'.$subject.'" />
			</div>
		</div>';
   
	echo '<div class="form-group">
			<div class="col-sm-3">
				<label class="control-label"><strong>'.translate("Topic").'</strong> : </label>
			</div>
			<div class="col-sm-4">
				<select class="form-control" name="topic">';
	$toplist = sql_query("select topicid, topictext from ".$NPDS_Prefix."topics order by topictext");
	echo "<option value=\"\">".translate("Select Topic")."</option>\n";
	while (list($topicid, $topics) = sql_fetch_row($toplist)) {
		if ($topicid==$topic) { $sel = "selected=\"selected\" "; }
		echo "<option $sel value=\"$topicid\">".aff_langue($topics)."</option>\n";
		$sel = "";
   }				  
	echo '</select>
			</div>
		</div>';  

	echo '<div class="form-group">
			<div class="col-sm-12">
				<label class="control-label"><strong>'.translate("Intro Text").'</strong> ( '.translate("HTML is fine, but double check those URLs and HTML tags!").' ) :</label>
			</div>
			<div class="col-sm-12">
				<textarea class="form-control" rows="25" name="story">'.$story.'</textarea>';
	echo aff_editeur("story", "true");				  
	echo '</div>
			</div>';		
	echo '<div class="form-group">
			<div class="col-sm-12">
				<label class="control-label"><strong>'.translate("Full Text").'</strong> :</label>
			</div>
			<div class="col-sm-12">
				<textarea class="form-control" rows="25" name="bodytext">'.$bodytext.'</textarea>';
	echo aff_editeur("bodytext", "true");				  
	echo '</div>
			</div>';		

   publication($deb_day,$deb_month,$deb_year,$deb_hour,$deb_min, $fin_day,$fin_month,$fin_year,$fin_hour,$fin_min, $epur);
	echo '<br />';
	echo '<div class="form-group">
			<div class="col-sm-12">
				<input class="btn btn-default" type="submit" name="op" value="'.translate("Preview").'" />
			</div>
		</div>';

	echo ''.Q_spambot().'';
	echo '<div class="form-group">
			<div class="col-sm-12">
				<input class="btn btn-primary" type="submit" name="op" value="Ok" />
				</div>
			</div>';
	echo '</form>';
   
	include ('footer.php');
}

function submitStory($subject, $story, $bodytext, $topic, $date_debval,$date_finval,$epur, $asb_question, $asb_reponse) {
   global $user, $EditedMessage, $anonymous, $notify, $NPDS_Prefix;

   if ($user) {
      global $cookie;
      $uid = $cookie[0];
      $name = $cookie[1];
   } else {
      $uid = -1;
      $name = $anonymous;
      //anti_spambot
      if (!R_spambot($asb_question, $asb_reponse, "")) {
         Ecr_Log("security", "Submit Anti-Spam : name=".$yname." / mail=".$ymail, "");
         redirect_url("index.php");
         die();
      }
   }

   $subject=removeHack(stripslashes(FixQuotes(str_replace("\"","&quot;",(strip_tags($subject))))));
   $story=removeHack(stripslashes(FixQuotes($story)));
   $bodytext=removeHack(stripslashes(FixQuotes($bodytext)));

   $result = sql_query("insert into ".$NPDS_Prefix."queue values (NULL, '$uid', '$name', '$subject', '$story', '$bodytext', now(), '$topic','$date_debval','$date_finval','$epur')");
   if (sql_last_id()) {
      if ($notify) {
         global $notify_email, $notify_subject, $notify_message, $notify_from;
         send_email($notify_email, $notify_subject, $notify_message, $notify_from , false, "text");
      }
	include ('header.php');
	echo '<h2>'.translate("Submit News").'</h2>';      
	echo '<p class="lead text-info">'.translate("Thanks for your submission.").'</p>';
      
	include ('footer.php');
	} else {
	include ('header.php');
      
	echo sql_error();
      
	include ('footer.php');
	}
}

settype($op,'string');
switch ($op) {
	case "Preview":
	case translate("Preview"):
		PreviewStory($name, $subject, $story, $bodytext, $topic, $deb_day,$deb_month,$deb_year,$deb_hour,$deb_min, $fin_day,$fin_month,$fin_year,$fin_hour,$fin_min,  $epur);
        break;

	case "Ok":
        settype($date_debval,'string');
        if (!$date_debval) {
           if (strlen($deb_day)==1) {
              $deb_day = "0$deb_day";
           }
           if (strlen($deb_month)==1) {
              $deb_month = "0$deb_month";
           }
           $date_debval = "$deb_year-$deb_month-$deb_day $deb_hour:$deb_min:00";
        }
        settype($date_finval,'string');
        if (!$date_finval) {
           if (strlen($fin_day)==1) {
              $fin_day = "0$fin_day";
           }
           if (strlen($fin_month)==1) {
              $fin_month = "0$fin_month";
           }
           $date_finval = "$fin_year-$fin_month-$fin_day $fin_hour:$fin_min:00";
        }
        if ($date_finval<$date_debval) {
           $date_finval = $date_debval;
        }
        SubmitStory($subject, $story, $bodytext, $topic, $date_debval,$date_finval,$epur, $asb_question, $asb_reponse);
        break;

	default:
		defaultDisplay();
        break;
}
?>
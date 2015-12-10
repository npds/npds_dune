<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2015 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion")) {
   include ("mainfile.php");
}

function display_score($score) {

   $image = '<i class="fa fa-star"></i>';
   $halfimage = '<i class="fa fa-star-half-o"></i>';
   $full = '<i class="fa fa-star"></i>';

   if ($score == 10) {
      for ($i=0; $i < 5; $i++)
         echo $full;
   } else if ($score % 2) {
      $score -= 1;
      $score /= 2;
      for ($i=0; $i < $score; $i++)
         echo $image;
      echo $halfimage;
   } else {
      $score /= 2;
      for ($i=0; $i < $score; $i++)
         echo $image;
   }
}

function write_review() {
   global $admin, $sitename, $user, $cookie, $short_review;
   global $NPDS_Prefix;

   include ('header.php');
   echo '
   <h2>'.translate("Write a Review").'</h2>
   <form class="" role="form" method="post" action="reviews.php">
      <div class="form-group row">
         <div class="col-sm-4">
            <label class="control-label">'.translate("Product Title").'</label>
         </div>
         <div class="col-sm-8">
            <input type="text" class="form-control"  name="title">
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-4">
            <label class="control-label">'.translate("Text").'</label>
         </div>
         <div class="col-sm-8">
            <textarea class="form-control"  name="text" rows="15"></textarea>			
         </div>
         <div class="col-sm-offset-4 col-sm-8">
            <p class="help-block"><i>'.translate("Please observe proper grammar! Make it at least 100 words, OK? You may also use HTML tags if you know how to use them.").'</i></p>
         </div>
      </div>';
  
   if ($user) {
      $result=sql_query("select uname, email from ".$NPDS_Prefix."users where uname='$cookie[1]'");
      list($uname, $email) = sql_fetch_row($result);

      echo '<div class="form-group row">
				<div class="col-sm-4">
					<label class="control-label">'.translate("Your name").'</label>
				</div>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="reviewer" value="'.$uname.'" />
				</div>
			</div>';	  
      echo '<div class="form-group row">
				<div class="col-sm-4">
					<label class="control-label">'.translate("Your email").'</label>
				</div>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="email" value="'.$email.'" />
				</div>
			</div>';
   } else {	   
      echo '<div class="form-group row">
				<div class="col-sm-4">
					<label class="control-label">'.translate("Your name").'</label>
				</div>
				<div class="col-sm-8">
					<input class="form-control" type="text" name="reviewer" value="'.$name.'">
				</div>
			</div>';
      echo '<div class="form-group row">
				<div class="col-sm-4">
					<label class="control-label">'.translate("Your email").'</label>
				</div>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="email" value="'.$email.'">
				</div>
			</div>';
   }
      echo '<div class="form-group row">
				<div class="col-sm-4">
					<label class="control-label">'.translate("Score").'</label>
				</div>
				<div class="col-sm-2">
					<select class="form-control" name="score">
						<option value="10">10</option>
						<option value="9">9</option>
						<option value="8">8</option>
						<option value="7">7</option>
						<option value="6">6</option>
						<option value="5">5</option>
						<option value="4">4</option>
						<option value="3">3</option>
						<option value="2">2</option>
						<option value="1">1</option>
					</select>
				</div>
				<div class="col-sm-offset-4 col-sm-8">
					<p class="help-block"><i>'.translate("Select from 1=poor to 10=excelent.").'</i></p>
				</div>
			</div>';

   if (!$short_review) {
	   
		echo '<div class="form-group row">
				<div class="col-sm-4">
					<label class="control-label">'.translate("Related Link").'</label>
				</div>
				<div class="col-sm-8">
					<input type="text" class="form-control"  name="url">
				</div>
				<div class="col-sm-offset-4 col-sm-8">
					<p class="help-block"><i>'.translate("Product Official Website. Make sure your URL starts by").' http(s)://</i></p>
				</div>
			</div>';
		echo '<div class="form-group row">
				<div class="col-sm-4">
					<label class="control-label">'.translate("Link title").'</label>
				</div>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="url_title">
				</div>
				<div class="col-sm-offset-4 col-sm-8">
					<p class="help-block"><i>'.translate("Required if you have a related link, otherwise not required.").'</i></p>
				</div>
			</div>';
		
      if ($admin) {
		echo '<div class="form-group row">
				<div class="col-sm-4">
					<label class="control-label">'.translate("Image filename").'</label>
				</div>
				<div class="col-sm-8">
					<input type="text" class="form-control"  name="cover">
				</div>
				<div class="col-sm-offset-4 col-sm-8">
					<p class="help-block"><i>'.translate("Name of the cover image, located in images/reviews/. Not required.").'</i></p>
				</div>
			</div>';
      }
   }
		echo '<div class="form-group row">
				<div class="col-sm-12">
					<input type="hidden" name="op" value="preview_review" />				
					<button type="submit" class="btn btn-default" title="'.translate("Preview").'">'.translate("Preview").'</button>
					<button type="button" onclick="history.go(-1)" class="btn btn-default" title="'.translate("Go Back").'">'.translate("Go Back").'</button>
				</div>
				<div class="col-sm-12">
					<p class="help-block"><i>'.translate("Please make sure that the information entered is 100% valid and uses proper grammar and capitalization. For instance, please do not enter your text in ALL CAPS, as it will be rejected.").'</i></p>
				</div>
			</div>';
   echo '</form>';
   
   include ("footer.php");
}

function preview_review($title, $text, $reviewer, $email, $score, $cover, $url, $url_title, $hits, $id) {
   global $admin, $short_review;

   $title = stripslashes(strip_tags($title));
   $text = stripslashes(removeHack(conv2br($text)));
   $reviewer = stripslashes(strip_tags($reviewer));
   $url_title = stripslashes(strip_tags($url_title));

   include ('header.php');
   echo '<h2>'.translate("Write a Review").'</h2>';
   echo '<form method="post" action="reviews.php">';
   if ($title == "") {
      $error = 1;
      echo '<p class="lead text-danger">'.translate("Invalid Title... can not be blank").'</p>';
   }
   if ($text == "") {
      $error = 1;
      echo '<p class="lead text-danger">'.translate("Invalid review text... can not be blank").'</p>';
   }
   if (($score < 1) || ($score > 10)) {
      $error = 1;
      echo '<p class="lead text-danger">'.translate("Invalid score... must be between 1 and 10").'</p>';
   }
   if (($hits < 0) && ($id != 0)) {
      $error = 1;
      echo '<p class="lead text-danger">'.translate("Hits must be a positive integer").'</p>';
   }
   if ($reviewer == "" || $email == "") {
      $error = 1;
      echo '<p class="lead text-danger">'.translate("You must enter both your name and your email").'</p>';
   } else if ($reviewer != "" && $email != "")
      if (!preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i',$email)) {
         $error = 1;
         echo '<p class="lead text-danger">'.translate("Invalid email (eg: you@hotmail.com)").'</p>';
      }

   if ((($url_title != "" && $url =="") || ($url_title == "" && $url != "")) and (!$short_reviews)) {
      $error = 1;
      echo '<p class="lead text-danger">'.translate("You must enter BOTH a link title and a related link or leave both blank").'</p>';
   } else if (($url != "") && (!preg_match('#^http(s)?://#i',$url))) {
      $error = 1;
      echo '<p class="lead text-danger">'.translate("Product Official Website. Make sure your URL starts by").' http(s)://</p>';
   }

   if ($error == 1) {
   echo '<button class="btn btn-secondary" type="button" onclick="history.go(-1)"><i class="fa fa-lg fa-undo"></i></button>';
   } else {
      global $gmt;
      $fdate=date(str_replace("%","",translate("linksdatestring")),time()+($gmt*3600));
   
      echo ''.translate("Waiting Reviews").'';
   
      echo "<br />\n";
      echo ''.translate("Added:").' '.$fdate.'';
      echo "<hr noshade=\"noshade\" />";
      echo "<span>$title</span><br />";
      if ($cover != "")
         echo "<img src=\"images/reviews/$cover\" align=\"right\" hspace=\"10\" vspace=\"10\">";
      echo $text;
      echo "<hr noshade=\"noshade\" />";
      echo "<b>".translate("Reviewer:")."</b> <a href=\"mailto:$email\" target=\"_blank\">$reviewer</a><br />";
      echo "<b>".translate("Score:")."</b> ";
      display_score($score);
      if ($url != "")
         echo "<br /><b>".translate("Related Link")." :</b> <a href=\"$url\" target=\"_blank\">$url_title</a>";
      if ($id != 0) {
         echo "<br /><b>".translate("Review ID")." :</b> $id<br />";
         echo "<b>".translate("Hits")." :</b> $hits<br />";
      }
      $text = urlencode($text);
      echo "<input type=\"hidden\" name=\"id\" value=\"$id\" />
            <input type=\"hidden\" name=\"hits\" value=\"$hits\" />
            <input type=\"hidden\" name=\"date\" value=\"$fdate\" />
            <input type=\"hidden\" name=\"title\" value=\"$title\" />
            <input type=\"hidden\" name=\"text\" value=\"$text\" />
            <input type=\"hidden\" name=\"reviewer\" value=\"$reviewer\" />
            <input type=\"hidden\" name=\"email\" value=\"$email\" />
            <input type=\"hidden\" name=\"score\" value=\"$score\" />
            <input type=\"hidden\" name=\"url\" value=\"$url\" />
            <input type=\"hidden\" name=\"url_title\" value=\"$url_title\" />
            <input type=\"hidden\" name=\"cover\" value=\"$cover\" />
            <input type=\"hidden\" name=\"op\" value=\"add_reviews\" />";
      echo "<br /><br />".translate("Does this look right?")."&nbsp;&nbsp;";
      if (!$admin)
         echo Q_spambot();
      echo "<input type=\"submit\" value=\"".translate("Yes")."\" />&nbsp;&nbsp;<input type=\"button\" onclick=\"history.go(-1)\" value=\"".translate("No")."\" />";
      if ($id != 0)
         $word = translate("modified");
      else
         $word = translate("added");
      if ($admin)
         echo "<br /><br /><b>".translate("Note:")."</b> ".translate("Currently logged in as admin... this review will be")." $word ".translate("immediately").".";
   }
   echo "</form>";
   
   include ("footer.php");
}

function reversedate($myrow) {
   if (substr($myrow,2,1)=="-") {
      $day=substr($myrow,0,2);
      $month=substr($myrow,3,2);
      $year=substr($myrow,6,4);
   } else {
      $day=substr($myrow,8,2);
      $month=substr($myrow,5,2);
      $year=substr($myrow,0,4);
   }
   return ($year."-".$month."-".$day);
}

function send_review($date, $title, $text, $reviewer, $email, $score, $cover, $url, $url_title, $hits, $id, $asb_question, $asb_reponse) {
   global $admin, $user, $NPDS_Prefix;

   include ('header.php');
   $date=reversedate($date);
   $title = stripslashes(FixQuotes(strip_tags($title)));
   $text = stripslashes(Fixquotes(urldecode(removeHack($text))));

   if (!$user and !$admin) {
      //anti_spambot
      if (!R_spambot($asb_question, $asb_reponse, $text)) {
         Ecr_Log("security", "Review Anti-Spam : title=".$title, "");
         redirect_url("index.php");
         die();
      }
   }
   echo '<h2>'.translate("Write a Review").'</h2>';   
   echo '<br /><p class="lead text-danger">'.translate("Thanks for submitting this review").'';
   if ($id != 0)
      echo " ".translate("modification")."";
   else
      echo ", $reviewer";
   echo "<br /><br />";
   if (($admin) && ($id == 0)) {
      sql_query("INSERT INTO ".$NPDS_Prefix."reviews VALUES (NULL, '$date', '$title', '$text', '$reviewer', '$email', '$score', '$cover', '$url', '$url_title', '1')");
      echo translate("It is now available in the reviews database.");
   } else if (($admin) && ($id != 0)) {
      sql_query("UPDATE ".$NPDS_Prefix."reviews SET date='$date', title='$title', text='$text', reviewer='$reviewer', email='$email', score='$score', cover='$cover', url='$url', url_title='$url_title', hits='$hits' where id='$id'");
      echo translate("It is now available in the reviews database.");
   } else {
      sql_query("INSERT INTO ".$NPDS_Prefix."reviews_add VALUES (NULL, '$date', '$title', '$text', '$reviewer', '$email', '$score', '$url', '$url_title')");
      echo translate("The editors will look at your submission. It should be available soon!");
   }
   echo '</p><a class="btn btn-default" role="button" href="reviews.php" title="'.translate("Back to Reviews Index").'"><i class="fa fa-lg fa-undo"></i>
</a>';
   
   include ("footer.php");
}

function reviews($field, $order) {
   global $NPDS_Prefix;
   include ('header.php');

   echo '<h2>'.translate("Write a Review").'</h2>';
   $result = sql_query("select title, description from ".$NPDS_Prefix."reviews_main");
   list($title, $description) = sql_fetch_row($result);
   echo '<p>'.aff_langue($title).'</p>';
   echo '<p>'.aff_langue($description).'</p>';
   echo "<p class=\"text-xs-center\">";
   echo "<a class=\"btn btn-default\" role=\"button\" href=\"reviews.php?op=write_review\">".translate("Write a Review")."</a></p>\n";
   if ($order!="ASC" and $order!="DESC") $order="ASC";
   switch ($field) {
          case "reviewer":
               $result = sql_query("SELECT id, title, hits, reviewer, score, date FROM ".$NPDS_Prefix."reviews ORDER by reviewer $order");
               break;

          case "score":
               $result = sql_query("SELECT id, title, hits, reviewer, score, date FROM ".$NPDS_Prefix."reviews ORDER by score $order");
               break;

          case "hits":
               $result = sql_query("SELECT id, title, hits, reviewer, score, date FROM ".$NPDS_Prefix."reviews ORDER by hits $order");
               break;

          case "date":
               $result = sql_query("SELECT id, title, hits, reviewer, score, date FROM ".$NPDS_Prefix."reviews ORDER by id $order");
               break;

          default:
               $result = sql_query("SELECT id, title, hits, reviewer, score, date FROM ".$NPDS_Prefix."reviews ORDER by title $order");
               break;
   }
   $numresults = sql_num_rows($result);
   if ($numresults > 0) {
      echo "<table class=\"table text-xs-center\">
            <tr>
            <td>
            <a href=\"reviews.php?op=sort&amp;field=date&amp;order=ASC\"><i class=\"fa fa-arrow-circle-up\"></i> Date <a href=\"reviews.php?op=sort&amp;field=date&amp;order=DESC\"><i class=\"fa fa-arrow-circle-down\"></i></a>
            </td>
            <td>
            <a href=\"reviews.php?op=sort&amp;field=title&amp;order=ASC\"><i class=\"fa fa-arrow-circle-up\"></i></a> ".translate("Title")." <a href=\"reviews.php?op=sort&amp;field=title&amp;order=DESC\"><i class=\"fa fa-arrow-circle-down\"></i></a>
            </td>
            <td>
            <a href=\"reviews.php?op=sort&amp;field=reviewer&amp;order=ASC\"><i class=\"fa fa-arrow-circle-up\"></i></a> ".translate("Posted by")." <a href=\"reviews.php?op=sort&amp;field=reviewer&amp;order=DESC\"><i class=\"fa fa-arrow-circle-down\"></i></a>
            </td>
            <td>
            <a href=\"reviews.php?op=sort&amp;field=score&amp;order=ASC\"><i class=\"fa fa-arrow-circle-up\"></i></a> Score <a href=\"reviews.php?op=sort&amp;field=score&amp;order=DESC\"><i class=\"fa fa-arrow-circle-down\"></i></a>
            </td>
            <td>
            <a href=\"reviews.php?op=sort&amp;field=hits&amp;order=ASC\"><i class=\"fa fa-arrow-circle-up\"></i></a> Hits <a href=\"reviews.php?op=sort&amp;field=hits&amp;order=DESC\"><i class=\"fa fa-arrow-circle-down\"></i></a>
            </td>
            </tr>";
      while ($myrow=sql_fetch_assoc($result)) {
         $title = $myrow["title"];
         $id = $myrow["id"];
         $reviewer = $myrow["reviewer"];
         $score = $myrow["score"];
         $hits = $myrow["hits"];
         $date = $myrow["date"];
         echo "<tr>
               <td><span>".f_date ($date)."</span>&nbsp;</td>
               <td><a href=\"reviews.php?op=showcontent&amp;id=$id\">$title</a></td>
               <td>";
         if ($reviewer != "") echo $reviewer;
         echo "</td><td>";
         display_score($score);
         echo "</td><td>$hits</td></tr>";
      }
      echo "</table>";
   }
   echo "<p>$numresults ".translate("Total Review(s) found.")."</p>";

   sql_free_result($result);
   
   include ("footer.php");
}

function f_date($xdate) {
   $year = substr($xdate,0,4);
   $month = substr($xdate,5,2);
   $day = substr($xdate,8,2);
   $fdate=date(str_replace("%","",translate("linksdatestring")),mktime (0,0,0,$month,$day,$year));
   return $fdate;
}

function showcontent($id) {
   global $admin, $NPDS_Prefix;
   include ('header.php');
   
   settype($id,"integer");
   sql_query("UPDATE ".$NPDS_Prefix."reviews SET hits=hits+1 WHERE id='$id'");
   $result = sql_query("SELECT * FROM ".$NPDS_Prefix."reviews WHERE id='$id'");

   echo translate("Reviews")."<td align=\"right\">";
   echo "[ <a href=\"reviews.php\">".translate("Back to Reviews Index")."</a> ]";

   echo '<br />';
   $myrow = sql_fetch_assoc($result);
   $id =  $myrow["id"];
   $fdate=f_date($myrow["date"]);
   $title = $myrow["title"];
   $text = $myrow["text"];
   $cover = $myrow["cover"];
   $reviewer = $myrow["reviewer"];
   $email = $myrow["email"];
   $hits = $myrow["hits"];
   $url = $myrow["url"];
   $url_title = $myrow["url_title"];
   $score = $myrow["score"];
   echo "<b>".translate("Added:")."</b> $fdate<br />";
   echo "<hr noshade=\"noshade\" />";
   echo "<span\">$title</span><br />";
   if ($cover != "")
      echo "<img src=\"images/reviews/$cover\" align=\"right\" hspace=\"10\" vspace=\"10\" />";
   echo $text;
   echo '<hr noshade="noshade" />';
   if ($admin)
      echo "<p class=\"text-xs-right\"><b>".translate("Admin:")."</b> [ <a href=\"reviews.php?op=mod_review&amp;id=$id\">".translate("Edit")."</a> | <a href=\"reviews.php?op=del_review&amp;id_del=$id\" class=\"rouge\">".translate("Delete")."</a> ]</p>";
   if ($reviewer != "")
      echo "<b>".translate("Reviewer:")."</b> <a href=\"mailto:$email\" target=\"_blank\">$reviewer</a><br />";
   if ($score != '')
      echo "<b>".translate("Score:")."</b> ";
   display_score($score);
   if ($url != '')
      echo "<br /><b>".translate("Related Link:")."</b> <a href=\"$url\" target=\"_blank\">$url_title</a>";
   echo "<br /><b>".translate("Hits:")."</b> $hits";
   sql_free_result($result);
   

   global $anonpost, $moderate, $user;
   if (file_exists("modules/comments/reviews.conf.php")) {
      include ("modules/comments/reviews.conf.php");
      include ("modules/comments/comments.php");
   }
   include ("footer.php");
}

function mod_review($id) {
   global $admin, $NPDS_Prefix;
   include ('header.php');

   settype($id,"integer");
   if (($id != 0) && ($admin)) {
      $result = sql_query("select * from ".$NPDS_Prefix."reviews where id = '$id'");
      $myrow =  sql_fetch_assoc($result);
      $id =  $myrow["id"];
      $date = $myrow["date"];
      $title = $myrow["title"];
      $text = str_replace("<br />","\r\n",$myrow["text"]);
      $cover = $myrow["cover"];
      $reviewer = $myrow["reviewer"];
      $email = $myrow["email"];
      $hits = $myrow["hits"];
      $url = $myrow["url"];
      $url_title = $myrow["url_title"];
      $score = $myrow["score"];
   
      echo translate("Review Modification");
   
      echo "<br />";
      echo "<form method=\"post\" action=\"reviews.php?op=preview_review\"><input type=\"hidden\" name=\"id\" value=\"$id\">";
      echo "
           
           <b>".translate("Date:")."</b>
           <input type=\"text\" name=\"date\" size=\"15\" value=\"$date\" />
           
           <b>".translate("Title:")."</b>
           <input class=\"textbox\" type=\"text\" name=\"title\" value=\"$title\" />
           
           <b>".translate("Text")."</b>
           <textarea class=\"textbox\" name=\"text\">$text</textarea>
           
           <b>".translate("Reviewer:")."</b>
           <input type=\"text\" name=\"reviewer\" value=\"$reviewer\" />
           
           <b>".translate("Email:")."</b>
           <input class=\"textbox\" type=\"text\" name=\"email\" value=\"$email\" />
           
           <b>".translate("Score:")."</b>
           <input type=\"text\" name=\"score\" value=\"$score\" />
           
           <b>".translate("Link:")."</b>
           <input class=\"textbox\" type=\"text\" name=\"url\" value=\"$url\" />
           
           <b>".translate("Link title:")."</b>
           <input class=\"textbox\" type=\"text\" name=\"url_title\" value=\"$url_title\" />
           
           <b>".translate("Cover image:")."</b>
           <input class=\"textbox\" type=\"text\" name=\"cover\" value=\"$cover\" />
           
           <b>".translate("Hits:")."</b>
           <input type=\"text\" name=\"hits\" value=\"$hits\" />
           
           <br />";
      echo "<input type=\"hidden\" name=\"op\" value=\"preview_review\" /><input type=\"submit\" value=\"".translate("Preview Modifications")."\" />&nbsp;&nbsp;<input type=\"button\" onclick=\"history.go(-1)\" value=\"".translate("Cancel")."\" /></form>";
      sql_free_result($result);
   }
   
   include ("footer.php");
}

function del_review($id_del) {
   global $admin, $NPDS_Prefix;

   settype($id_del,"integer");
   if ($admin) {
      sql_query("delete from ".$NPDS_Prefix."reviews where id='$id_del'");
      // commentaires
      if (file_exists("modules/comments/reviews.conf.php")) {
          include ("modules/comments/reviews.conf.php");
          sql_query("DELETE FROM ".$NPDS_Prefix."posts WHERE forum_id='$forum' and topic_id='id_del'");
      }
   }
   redirect_url("reviews.php");
}

settype($op,'string');
switch ($op) {
   case "showcontent":
        showcontent($id);
        break;
   case "write_review":
        write_review();
        break;
   case "preview_review":
        preview_review($title, $text, $reviewer, $email, $score, $cover, $url, $url_title, $hits, $id);
        break;
   case "add_reviews":
        send_review($date, $title, $text, $reviewer, $email, $score, $cover, $url, $url_title, $hits, $id, $asb_question, $asb_reponse);
        break;
   case "del_review":
        del_review($id_del);
        break;
   case "mod_review":
        mod_review($id);
        break;
   case "sort":
        reviews($field,$order);
        break;
   default:
        reviews("date","DESC");
        break;
}
?>
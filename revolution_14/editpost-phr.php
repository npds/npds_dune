<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x and PhpBB integration source code               */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2013 by Philippe Brunier   */
/* Great mods by snipe                                                  */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/* version phr du 26/05/2015                                            */
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
global $NPDS_Prefix;

$rowQ1=Q_Select ("SELECT forum_name, forum_moderator, forum_type, forum_pass, forum_access, arbre FROM ".$NPDS_Prefix."forums WHERE forum_id = '$forum'", 3600);
if (!$rowQ1)
   forumerror('0001');
list(,$myrow) = each($rowQ1);
$forum_type = $myrow['forum_type'];
$forum_access = $myrow['forum_access'];
$moderator = get_moderator($myrow['forum_moderator']);
if (isset($user)) {
   $userX = base64_decode($user);
   $userdata = explode(":", $userX);
   $moderator=explode(" ",$moderator);
   $Mmod=false;
   for ($i = 0; $i < count($moderator); $i++) {
       if (($userdata[1]==$moderator[$i])) { $Mmod=true; break;}
   }
}
settype($submitS,'string');
if ($submitS) {
   include("header.php");
   $sql = "SELECT poster_id, topic_id FROM ".$NPDS_Prefix."posts WHERE post_id = '$post_id'";
   $result = sql_query($sql);
   if (!$result) {
    forumerror('0022');
   }
   $row = sql_fetch_assoc($result);

   if ($userdata[0]==$row['poster_id']) {
      $ok_maj=true;
   } else {
      if (!$Mmod) { forumerror('0035'); }
      if ((user_is_moderator($userdata[0],$userdata[2],$forum_access)<2) )  { forumerror('0036'); }
   }
   $userdata = get_userdata($userdata[1]);

   if ($allow_html == 0 || isset($html)) {
      $message = htmlspecialchars($message,ENT_COMPAT|ENT_HTML401,cur_charset);
   }

   if (($allow_bbcode==1) and ($forum_type!="6") and ($forum_type!="5")) {
      $message = smile($message);
   }
   if (($forum_type!=6) and ($forum_type!=5)) {
      $message = make_clickable($message);
      $message = aff_code($message);
      $message = str_replace("\n", "<br />", removeHack($message));
      $message .= "<br /><p class=\"lignb\">".translate("This message was edited by")." : ".$userdata['uname']." / ".post_convertdate(time()+($gmt*3600))."</p>";
   } else {
      $message .= "\n\n".translate("This message was edited by")." : ".$userdata['uname']." / ".post_convertdate(time()+($gmt*3600));
   }
   $message = addslashes($message);

   if ($subject=="") {$subject=translate("Untitled");}

   // Forum ARBRE
   if ($arbre)
      $hrefX="viewtopicH.php";
   else
      $hrefX="viewtopic.php";

   if (!isset($delete)) {
      $sql = "UPDATE ".$NPDS_Prefix."posts SET post_text = '$message', image='$image_subject' WHERE (post_id = '$post_id')";
      if (!$result = sql_query($sql))
         forumerror('0001');
      $sql = "UPDATE ".$NPDS_Prefix."forum_read SET status='0' where topicid = '".$row['topic_id']."'";
      if (!$r = sql_query($sql))
         forumerror('0001');

      $sql = "UPDATE ".$NPDS_Prefix."forumtopics SET topic_title = '$subject', topic_time = '".date("Y-m-d H:i:s",time()+($gmt*3600))."', current_poster='".$userdata['uid']."' WHERE topic_id = '".$row['topic_id']."'";
      if (!$result = sql_query($sql))
         forumerror('0020');
      redirect_url("$hrefX?topic=".$row['topic_id']."&forum=$forum");
   } else {
      $indice=sql_num_rows(sql_query("select post_id from ".$NPDS_Prefix."posts where post_idH='$post_id'"));
      if (!$indice) {
         $sql = "DELETE FROM ".$NPDS_Prefix."posts WHERE post_id='$post_id'";
         if (!$r = sql_query($sql))
            forumerror('0001');
         control_efface_post("forum_npds",$post_id,"","");
         if (get_total_posts($forum, $row['topic_id'], "topic",$Mmod) == 0) {
            $sql = "DELETE FROM ".$NPDS_Prefix."forumtopics WHERE topic_id = '".$row['topic_id']."'";
            if (!$r = sql_query($sql))
               forumerror('0001');
            $sql = "DELETE FROM ".$NPDS_Prefix."forum_read WHERE topicid = '".$row['topic_id']."'";
            @sql_query($sql);
            redirect_url("viewforum.php?forum=$forum");
            die();
         } else {
            $result=sql_query("SELECT post_time, poster_id FROM ".$NPDS_Prefix."posts where topic_id='".$row['topic_id']."' ORDER BY post_id DESC limit 0,1");
            $rowX=sql_fetch_row($result);
            $sql = "UPDATE ".$NPDS_Prefix."forumtopics SET topic_time = '$rowX[0]', current_poster='$rowX[1]' WHERE topic_id = '".$row['topic_id']."'";
            if (!$r = sql_query($sql))
               forumerror('0001');
         }
         redirect_url("$hrefX?topic=".$row['topic_id']."&forum=$forum");
      } else {

         echo "<p align=\"center\">".translate("Your post has NOT been deleted because one or more posts is already attached (TREE forum).")."</p>";

      }
   }
} else {
   include("header.php");
   if ($allow_bbcode==1) {
      include("lib/formhelp.java.php");
   }
   $sql = "SELECT p.*, u.uname, u.uid, u.user_sig FROM ".$NPDS_Prefix."posts p, ".$NPDS_Prefix."users u WHERE (p.post_id = '$post_id') AND (p.poster_id = u.uid)";
   if (!$result = sql_query($sql)) {
      forumerror('0001');
   }
   $myrow = sql_fetch_assoc($result);
   if ((!$Mmod) and ($userdata[0]!=$myrow['uid'])) { forumerror('0035'); }
   if (!$result = sql_query("select topic_title, topic_status from ".$NPDS_Prefix."forumtopics where topic_id='".$myrow['topic_id']."'")) {
      forumerror('0001');
   } else {
      list($title, $topic_status) = sql_fetch_row($result);
      if (($topic_status!=0) and !$Mmod)
         forumerror("0025");
   }
   settype($submitP,'string');
   if ($submitP) {
      $acc = "editpost";
      $title=stripslashes($subject);
      $message=stripslashes(make_clickable($message));
      include ("preview.php");
   } else {
      $image_subject = $myrow['image'];
      $title = stripslashes($title);
      $message = $myrow['post_text'];
      if (($forum_type!=6) and ($forum_type!=5))  {
         $message = str_replace("<br />", "\n", $message);
         $message = smile($message);
         $message = undo_htmlspecialchars($message,ENT_COMPAT|ENT_HTML401,cur_charset);
      } else {
         $message = htmlspecialchars($message,ENT_COMPAT|ENT_HTML401,cur_charset);
      }
      $message = stripslashes($message);
   }
   if ( (($Mmod) or ($userdata[0]==$myrow['uid'])) and ($forum_access!=9) ) {


   echo '<form class="form" role="form" action="editpost.php" method="post" name="coolsus">';
   echo '<div class="form-group">';   

      if ($Mmod)
         echo "<h4>".translate("Editing Post")."</h4><input class=\"form-control\" type=\"text\" name=\"subject\" value=\"".htmlspecialchars($title,ENT_COMPAT|ENT_HTML401,cur_charset)."\" />";
      else {
         echo "<h4>".translate("Editing Post")."</h4> : $title";
         echo "<input type=\"hidden\" name=\"subject\" value=\"".htmlspecialchars($title,ENT_COMPAT|ENT_HTML401,cur_charset)."\" />";
      }

      echo "<h4>".translate("Author")." :</h4>";
      echo $userdata[1];
   } else {
      forumerror('0036');
   }
   if ($smilies) {
      echo "
      <h4>".translate("Message Icon: ")."</h4>";
      echo emotion_add($image_subject);
   }
   echo "<h4>".translate("Message: ")."</h4>";
		echo '
		<div class="form-group">
			<div class="row">
				<label class="control-label col-sm-2 col-md-2" for="aid">'; 
   echo "HTML : ";
   if ($allow_html == 1) {
      echo translate("On")."<br />"; //faut-il ce br 
      echo HTML_Add();
   } else
      echo translate("Off")."<br />"; // faut-il ce br
   if ($allow_bbcode)
      $xJava = 'name="message" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" onfocus="storeForm(this)"';
		echo '
		</label>
			<div class="col-sm-10 col-md-10">
			<textarea class="form-control" '.$xJava.' name="message" rows="15">'.$message.'</textarea>
			</div>
			</div>
			</div>';
  
   if ($allow_bbcode)
      putitems();

   echo "<h4>".translate("Options: ")."</h4>";
		echo '
		<div class="form-group">
			<div class="row">
			<label class="control-label col-sm-4 col-md-4" for="aid"><h4>'.translate("Delete this Post").'</h4></label>
			<div class="col-sm-1 col-md-1">
			<input class="form-control" type="checkbox" name="delete" />
			</div>
			</div>
			</div>'; 
   if (($allow_html==1) and ($forum_type!=6)) {
      if (isset($html)) {
         $sethtml="checked=\"checked\"";
      } else {
         $sethtml="";
      }	  
		echo '
		<div class="form-group">
			<div class="row">
			<label class="control-label col-sm-4 col-md-4" for="aid"><h4>'.translate("Disable HTML on this Post").'</h4></label>
			<div class="col-sm-1 col-md-1">
			<input class="form-control" type="checkbox" name="html" '.$sethtml.' />
			</div>
			</div>
			</div>';	  
   }
		echo '
			<div class="btn-group-sm text-center" role="group">
				<input type="hidden" name="post_id" value="'.$post_id.'" />
				<input type="hidden" name="forum" value="'.$forum.'" />
				<input type="hidden" name="topic_id" value="'.$topic.'" />
				<input type="hidden" name="topic" value="'.$topic.'" />
				<input type="hidden" name="user_sig" value="'.$myrow['user_sig'].'" />
				<input type="hidden" name="arbre" value="'.$arbre.'" />
				<button class="btn btn-primary" type="submit" value="'.translate("Submit").'" name="submitS">'.translate("Submit").'</button>   
				<button class="btn btn-default" type="submit" value="'.translate("Preview").'" name="submitP">'.translate("Preview").'</button>   
				<button class="btn btn-warning" type="reset" value="'.translate("Clear").'">'.translate("Clear").'</button>
			</div>';
   echo "</div></form>";
}
include("footer.php");
?>
<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2018 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion")) {
   include ("mainfile.php");
}

function SuserCheck($email) {
    global $NPDS_Prefix, $stop;
    $stop='';
    if ((!$email) || ($email=="") || (!preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i',$email))) $stop = translate("ERROR: Invalid email")."<br />";
    if (strrpos($email,' ') > 0) $stop = translate("ERROR: Email addresses do not contain spaces.")."<br />";
    if (sql_num_rows(sql_query("SELECT email FROM ".$NPDS_Prefix."users WHERE email='$email'")) > 0) {
       $stop = translate("ERROR: Email address already registered")."<br />";
    }
    if (sql_num_rows(sql_query("SELECT email FROM ".$NPDS_Prefix."lnl_outside_users WHERE email='$email'")) > 0) {
       if (sql_num_rows(sql_query("SELECT email FROM ".$NPDS_Prefix."lnl_outside_users WHERE email='$email' AND status='NOK'")) >0) {
          sql_query("DELETE FROM ".$NPDS_Prefix."lnl_outside_users WHERE email='$email'");
       } else {
          $stop = translate("ERROR: Email address already registered")."<br />";
       }
    }
    return($stop);
}

function error_handler($ibid) {
   opentable();
   echo translate("Please enter information according to the specifications")."<br /><br />";
   echo "$ibid<br /><a href=\"index.php\" class=\"noir\">".translate("Go Back")."</a>";
   closetable();
}

function subscribe($var) {
   if ($var!="") {
      include("header.php");
      opentable();
      echo "<form action=\"lnl.php\" method=\"POST\">";
      echo translate("Manage your subscribes")." : <b>".$var."</b><br /><br />";
      echo Q_spambot()."<br /><br />";
      echo "<input type=\"hidden\" name=\"email\" value=\"$var\" />";
      echo "<input type=\"hidden\" name=\"op\" value=\"subscribeOK\" />";
      echo "<input type=\"submit\" class=\"bouton_standard\" value=\"".translate("Submit")."\" />&nbsp;&nbsp;&nbsp;&nbsp;";
      echo "<a href=\"index.php\" class=\"noir\">".translate("Go Back")."</a></form>\n";
      closetable();
      include("footer.php");
   }  else {
      header("location: index.php");
   }
}

function subscribe_ok($xemail) {
   global $NPDS_Prefix;
   global $stop;

   include("header.php");
   if ($xemail!="") {
      SuserCheck($xemail);
      if ($stop=="") {
         $host_name=getip();
         $timeX=strftime("%Y-%m-%d %H:%M:%S",time());
         // Troll Control
         list($troll) = sql_fetch_row(sql_query("SELECT COUNT(*) FROM ".$NPDS_Prefix."lnl_outside_users WHERE (host_name='$host_name') AND (to_days(now()) - to_days(date) < 3)"));
         if ($troll < 6) {
            sql_query("INSEERT INTO ".$NPDS_Prefix."lnl_outside_users VALUES ('$xemail', '$host_name', '$timeX', 'OK')");
            // Email validation + url to unsubscribe
            global $sitename, $nuke_url;
            $subject = "".translate("NewsLetter")." / $sitename";
            $message = "".translate("Thank you for taking the time to record you in or DataBase.")."\n\n";
            $message .= "".translate("For Unsubscribe, please goto")." :\n $nuke_url/lnl.php?op=unsubscribe&email=$xemail\n\n";
            include("signat.php");
            send_email($xemail, $subject, $message, "", true, "text");
            opentable();
            echo translate("Thank you for taking the time to record you in or DataBase.")."<br /><br />";
            echo "<a href=\"index.php\" class=\"noir\">".translate("Go Back")."</a>";
            closetable();
        } else {
            $stop=translate("This account or IP has been temporarily disabled. This means that either this IP, or user account has been moderated down more than x times in the last few hours. If you think this is unfair, you should contact the admin.")."<br />";
            error_handler($stop);
        }
      } else {
         error_handler($stop);
      }
   } else {
     error_handler(translate("Empty data not allowed.")."<br />");
   }
   include("footer.php");
}

function unsubscribe($xemail) {
   global $NPDS_Prefix;

   if ($xemail!="") {
      if ((!$xemail) || ($xemail=="") || (!preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i',$xemail))) header("location: index.php");
      if (strrpos($xemail,' ') > 0) header("location: index.php");
      if (sql_num_rows(sql_query("SELECT email FROM ".$NPDS_Prefix."lnl_outside_users WHERE email='$xemail'")) > 0) {
         $host_name=getip();
         $timeX=strftime("%Y-%m-%d %H:%M:%S",time());
         // Troll Control
         list($troll) = sql_fetch_row(sql_query("SELECT COUNT(*) FROM ".$NPDS_Prefix."lnl_outside_users WHERE (host_name='$host_name') AND (to_days(now()) - to_days(date) < 3)"));
         if ($troll < 6) {
            sql_query("UPDATE ".$NPDS_Prefix."lnl_outside_users SET status='NOK'  WHERE email='$xemail'");
            include("header.php");
            opentable();
            echo translate("Thanks!")."<br /><br />";
            echo "<a href=\"index.php\" class=\"noir\">".translate("Go Back")."</a>";
            closetable();
            include("footer.php");
         } else {
            include("header.php");
            $stop=translate("This account or IP has been temporarily disabled. This means that either this IP, or user account has been moderated down more than x times in the last few hours. If you think this is unfair, you should contact the admin.")."<br />";
            error_handler($stop);
            include("footer.php");
         }
      } else {
         redirect_url("index.php");
      }
   } else {
      redirect_url("index.php");
   }
}

settype($op,'string');
switch ($op) {
   case 'subscribe':
      subscribe($email);
   break;

   case 'subscribeOK':
      //anti_spambot
      if (!R_spambot($asb_question, $asb_reponse,"")) {
         Ecr_Log("security", "LNL Anti-Spam : email=".$email, "");
         redirect_url("index.php");
         die();
      }
      subscribe_ok($email);
   break;

   case 'unsubscribe':
      unsubscribe($email);
   break;
}
?>

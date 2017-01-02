<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/*                                                                      */
/* NPDS Copyright (c) 2001-2017 by Philippe Brunier                     */
/* =========================                                            */
/*                                                                      */
/* Based on PhpNuke 4.x and PhpBB integration source code               */
/* Great mods by snipe                                                  */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion")) {
   include ("mainfile.php");
}

include('functions.php');
if ($SuperCache) {
   $cache_obj = new cacheManager();
} else {
   $cache_obj = new SuperCacheEmpty();
}
include('auth.php');
global $NPDS_Prefix, $adminforum;

//==> droits des admin sur les forums (superadmin et admin avec droit gestion forum)
   $adminforum=false;
   if ($admin) {
      $adminforum=0;
      $adminX = base64_decode($admin);
      $adminR = explode(':', $adminX);
      $Q = sql_fetch_assoc(sql_query("SELECT * FROM ".$NPDS_Prefix."authors WHERE aid='$adminR[0]' LIMIT 1"));
      if ($Q['radminsuper']==1) {$adminforum=1;} else {
         $R = sql_query("SELECT fnom, fid, radminsuper FROM ".$NPDS_Prefix."authors a LEFT JOIN ".$NPDS_Prefix."droits d ON a.aid = d.d_aut_aid LEFT JOIN ".$NPDS_Prefix."fonctions f ON d.d_fon_fid = f.fid WHERE a.aid='$adminR[0]' and f.fid between 13 and 15");
         if (sql_num_rows($R) >=1) $adminforum=1;
      }
   }
//<== droits des admin sur les forums (superadmin et admin avec droit gestion forum)

   if (isset($arbre) and ($arbre=="1")) {$url_ret="viewtopicH.php";} else {$url_ret="viewtopic.php";}

   $Mmod=false;
   $userX = base64_decode($user);
   $userdata = explode(':', $userX);
   settype($forum, "integer");
   $rowQ1=Q_Select ("SELECT forum_name, forum_moderator, forum_type, forum_pass, forum_access, arbre FROM ".$NPDS_Prefix."forums WHERE forum_id = '$forum'", 3600);
   if (!$rowQ1)
      forumerror('0001');
   list(,$myrow) = each($rowQ1);
   $moderator=explode(" ",get_moderator($myrow['forum_moderator']));
   for ($i = 0; $i < count($moderator); $i++) {
       if (($userdata[1] == $moderator[$i])) {
          if (user_is_moderator($userdata[0],$userdata[2],$myrow['forum_access'])) {
             $Mmod=true;
          }
          break;
       }
   }
   if ((!$Mmod) and ($adminforum==0)) {
      forumerror('0007');
   }

   if ((isset($submit)) and ($mode=="move")) {
      $sql = "UPDATE ".$NPDS_Prefix."forumtopics SET forum_id='$newforum' WHERE topic_id='$topic'";
      if (!$r = sql_query($sql))
         forumerro('0010');
      $sql = "UPDATE ".$NPDS_Prefix."posts SET forum_id='$newforum' WHERE topic_id='$topic' AND forum_id='$forum'";
      if (!$r = sql_query($sql))
         forumerror('0010');
      $sql = "DELETE FROM ".$NPDS_Prefix."forum_read where topicid='$topic'";
      if (!$r = sql_query($sql))
         forumerror('0001');
      $sql = "UPDATE $upload_table SET forum_id='$newforum' WHERE apli='forum_npds' AND topic_id='$topic' AND forum_id='$forum'";
      sql_query($sql);
      $sql = "SELECT arbre FROM ".$NPDS_Prefix."forums WHERE forum_id='$newforum'";
      $arbre=sql_fetch_assoc(sql_query($sql));
      if ($arbre['arbre']) {$url_ret="viewtopicH.php";} else {$url_ret="viewtopic.php";}
      include("header.php");
      opentable();
      echo "<p align=\"center\" class=\"noir\">".translate("The topic has been moved.")."</p><br /> - <a href=\"$url_ret?topic=$topic&amp;forum=$newforum\" class=\"noir\">".translate("Click here to view the updated topic.")."</a><br /><br /> - <a href=\"forum.php\" class=\"noir\">".translate("Click here to return to the forum index.")."</a>";
      closetable();
      Q_Clean();
      include("footer.php");
   } else {
      if (($Mmod) or ($adminforum==1)) {
         switch ($mode) {
            case 'move':
               include("header.php");
               echo '
      <h2>'.translate("Forum").'</h2>
      <form action="topicadmin.php" method="post">
         <div class="form-group row">
            <label class="form-control-label col-sm-12" for="newforum">'.translate("Move Topic To: ").'</label>
            <div class="col-sm-12">
               <select class="custom-select form-control" name="newforum">';
               $sql = "SELECT forum_id, forum_name FROM ".$NPDS_Prefix."forums WHERE forum_id!='$forum' ORDER BY cat_id,forum_index,forum_id";
               if ($result = sql_query($sql)) {
                  if ($myrow = sql_fetch_assoc($result)) {
                     do {
                        echo '
                     <option value="'.$myrow['forum_id'].'">'.$myrow['forum_name'].'</option>';
                     } while($myrow = sql_fetch_assoc($result));
                  } else {
                     echo '
                     <option value="-1">'.translate("No More Forums").'</option>';
                  }
               } else {
                  echo '
                     <option value="-1">Database Error</option>';
               }
               echo '
                  </select>
            </div>
         </div>
         <div class="form-group row">
            <div class="col-sm-12">
               <input type="hidden" name="mode" value="move" />
               <input type="hidden" name="topic" value="'.$topic.'" />
               <input type="hidden" name="forum" value="'.$forum.'" />
               <input type="hidden" name="arbre" value="'.$arbre.'" />
               <input class="btn btn-primary" type="submit" name="submit" value="'.translate("Move Topic").'" />
            </div>
         </div>
      </form>';
               include("footer.php");
               break;
            case 'del':
               $sql = "DELETE FROM ".$NPDS_Prefix."posts WHERE topic_id='$topic' AND forum_id='$forum'";
               if (!$result = sql_query($sql))
                  forumerror('0009');
               $sql = "DELETE FROM ".$NPDS_Prefix."forumtopics WHERE topic_id='$topic'";
               if (!$result = sql_query($sql))
                  forumerror('0010');
               $sql = "DELETE FROM ".$NPDS_Prefix."forum_read WHERE topicid='$topic'";
               if (!$r = sql_query($sql))
                  forumerror('0001');
               control_efface_post ("forum_npds", "",$topic,"");
               header("location: viewforum.php?forum=$forum");
               break;
            case 'lock':
               $sql = "UPDATE ".$NPDS_Prefix."forumtopics SET topic_status=1 WHERE topic_id='$topic'";
               if (!$r = sql_query($sql))
                  forumerror('0011');
               header("location: $url_ret?topic=$topic&forum=$forum");
               break;
            case 'unlock':
               $topic_title="";
               $sql = "SELECT topic_title FROM ".$NPDS_Prefix."forumtopics WHERE topic_id = '$topic'";
               $r=sql_fetch_assoc(sql_query($sql));
               $topic_title=str_replace("[".translate("Solved")."] - ","",$r['topic_title']);
               $sql = "UPDATE ".$NPDS_Prefix."forumtopics SET topic_status = '0', topic_first='1', topic_title='".addslashes ($topic_title)."' WHERE topic_id = '$topic'";
               if (!$r = sql_query($sql))
                  forumerror('0012');
               header("location: $url_ret?topic=$topic&forum=$forum");
               break;
            case 'first':
               $sql = "UPDATE ".$NPDS_Prefix."forumtopics SET topic_status = '1', topic_first='0' WHERE topic_id = '$topic'";
               if (!$r = sql_query($sql))
                  forumerror('0011');
               header("location: $url_ret?topic=$topic&forum=$forum");
               break;
            case 'viewip':
               include("header.php");
               $sql = "SELECT u.uname, p.poster_ip, p.poster_dns FROM ".$NPDS_Prefix."users u, ".$NPDS_Prefix."posts p WHERE p.post_id = '$post' AND u.uid = p.poster_id";
               if (!$r = sql_query($sql))
                  forumerror('0013');
               if (!$m = sql_fetch_assoc($r))
                  forumerror('0014');
                  echo '
      <h2>'.translate("Forum").'</h2>
      <div class="card card-block">
         <h3 class="card-title" >'.translate("Users IP and Account information").'</h3>
         <div class="row">
           <div class="col-sm-5 text-muted">'.translate("Nickname: ").'</div>
           <div class="col-sm-7">'.$m['uname'].'</div>
         </div>
         <div class="row">
           <div class="col-sm-5 text-muted">'.translate("User IP: ").'</div>
           <div class="col-sm-7">'.$m['poster_ip'].' => <a href="topicadmin.php?mode=banip&topic='.$topic.'&post='.$post.'&forum='.$forum.'&arbre='.$arbre.'" >'.translate("Ban this @IP").'</a></div>
         </div>
         <div class="row">
           <div class="col-sm-5 text-muted">'.translate("User DNS: ").'</div>
           <div class="col-sm-7">'.$m['poster_dns'].'</div>
         </div>
         <div class="row">
           <div class="col-sm-5 text-muted">GeoTool</div>
           <div class="col-sm-7"><a href="http://geoip.flagfox.net/?ip='.$m['poster_ip'].'" target="_blank" >FlagFox</a></div>
         </div>
         <br />
         <a href="'.$url_ret.'?topic='.$topic.'&amp;forum='.$forum.'" class="btn btn-secondary">'.translate("Go Back").'</a>
      </div>';
               include("footer.php");
               break;
            case 'banip':
               $sql = "SELECT p.poster_ip FROM ".$NPDS_Prefix."users u, ".$NPDS_Prefix."posts p WHERE p.post_id = '$post' AND u.uid = p.poster_id";
               if (!$r = sql_query($sql))
                  forumerror('0013');
               if (!$m = sql_fetch_assoc($r))
                  forumerror('0014');
               L_spambot($m['poster_ip'],"ban");
               header("location: $url_ret?topic=$topic&forum=$forum");
               break;
            case 'aff':
               $sql = "UPDATE ".$NPDS_Prefix."posts SET post_aff = '$ordre' WHERE post_id = '$post'";
               sql_query($sql);
               header("location: $url_ret?topic=$topic&forum=$forum");
               break;
         }
      } else {
         include("header.php");
         echo "<p align=\"center\">".translate("You are not the moderator of this forum therefor you cannot perform this function.")."<br /><br />";
         echo "<a href=\"javascript:history.go(-1)\" class=\"noir\">".translate("Go Back")."</a></p>";
         include("footer.php");
      }
   }
?>

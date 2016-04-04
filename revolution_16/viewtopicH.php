<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on Parts of phpBB                                              */
/*                                                                      */
/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */
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
global $NPDS_Prefix;

if ($allow_upload_forum) {
   include("modules/upload/upload_forum.php");
}

$rowQ1=Q_Select ("SELECT forum_id FROM ".$NPDS_Prefix."forumtopics WHERE topic_id='$topic'", 3600);
if (!$rowQ1)
   forumerror('0001');
list(,$myrow) = each($rowQ1);
$forum=$myrow['forum_id'];

$rowQ1=Q_Select ("SELECT forum_name, forum_moderator, forum_type, forum_pass, forum_access, arbre FROM ".$NPDS_Prefix."forums WHERE forum_id = '$forum'", 3600);
if (!$rowQ1)
   forumerror('0001');
list(,$myrow) = each($rowQ1);
$forum_name = $myrow['forum_name'];
$mod = $myrow['forum_moderator'];
$forum_type=$myrow['forum_type'];
$forum_access=$myrow['forum_access'];

if ( ($forum_type == 1) and ($Forum_passwd != $myrow['forum_pass']) ) {
   redirect_url("forum.php");
}
if (($forum_type == 5) or ($forum_type == 7)) {
   $ok_affiche=false;
   $tab_groupe=valid_group($user);
   $ok_affiche=groupe_forum($myrow['forum_pass'], $tab_groupe);
   if (!$ok_affiche) {
      redirect_url("forum.php");
   }
}
if (($forum_type==9) and (!$user)) {
   redirect_url("forum.php");
}
// Moderator
if (isset($user)) {
   $userX = base64_decode($user);
   $userdata = explode(':', $userX);
}
$moderator=get_moderator($mod);
$moderator=explode(' ',$moderator);
$Mmod=false;
if (isset($user)) {
   for ($i = 0; $i < count($moderator); $i++) {
      if (($userdata[1]==$moderator[$i])) { $Mmod=true; break;}
   }
}
$sql = "SELECT topic_title, topic_status, topic_poster FROM ".$NPDS_Prefix."forumtopics WHERE topic_id = '$topic'";
$total = get_total_posts($forum, $topic, "topic",$Mmod);
if ($total > $posts_per_page) {
   $times = 0;
   for ($x = 0; $x < $total; $x += $posts_per_page)
       $times++;
   $pages = $times;
}

if (!$result = sql_query($sql))
   forumerror(0001);
$myrow = sql_fetch_assoc($result);
$topic_subject = stripslashes($myrow['topic_title']);
$lock_state = $myrow['topic_status'];
$original_poster=$myrow['topic_poster'];

function maketree($rootcatid,$sql,$maxlevel){
   $result=sql_query($sql);
   $max_post_id=0;
   while($tempo=sql_fetch_assoc($result)){
      $table[$tempo['post_idH']][$tempo['post_id']]=serialize($tempo);
      if ($max_post_id<$tempo['post_id']) {$max_post_id=$tempo['post_id'];}
   }
   global $toggle;
   $resultX='';
   $toggle = new ToggleDiv(count(array_keys($table))-1);
   echo "<p class=\"text-xs-right\">",$toggle->All(),"</p>";
   $resultX.=makebranch($rootcatid,$table,0,$maxlevel,$max_post_id);
   echo "<br /><p class=\"text-xs-right\">",$toggle->All(),"</p>";

   return ($resultX);
}

function makebranch($parcat,$table,$level,$maxlevel,$max_post_id) {
   global $imgtmpPI,$imgtmpPR,$imgtmpEM,$imgtmpWW,$imgtmpIC,$imgtmpAI,$imgtmpYI,$imgtmpMS,$imgtmpED,$imgtmpQU,$imgtmpIP,$imgtmpUP,$imgtmpLP, $imgtmpGF, $imgtmpRN, $imgtmpNE;
   global $smilies,$theme,$forum,$forum_type,$allow_bbcode,$allow_to_post,$forum_access,$Mmod,$topic,$lock_state, $userdata;
   global $allow_upload_forum, $att;
   global $anonymous, $short_user;
   global $row_color, $last_read, $toggle;
   settype($result,'string');

   $list=$table[$parcat];
   while(list($key,$val)=each($list)) {
      $output='';
      if ($level!="0") {
         if ($level==1) {
            $output="
                     <img src=\"images/pix.gif\" width=\"1\" height=\"1\" alt=\"\" /><img src=\"images/pix.gif\" width=\"6\" height=\"1\" alt=\"\" />
                     <img src=\"images/pix.gif\" width=\"6\" height=\"1\" alt=\"\" />
                    ";
         } else {
            $output="
                     <img src=\"images/pix.gif\" width=\"".(($level-1)*8+2)."\" height=\"1\" alt=\"\" /><td style=\"font-size: 10px;\">".$level."
                     
                     <img src=\"images/pix.gif\" width=\"1\" height=\"1\" alt=\"\" /><img src=\"images/pix.gif\" width=\"6\" height=\"1\" alt=\"\" />
                     <img src=\"images/pix.gif\" width=\"6\" height=\"1\" alt=\"\" />
                    ";
         }
         echo "$output<td width=\"100%\" align=\"left\">";
      } else {
         
      }
      //-----------------------
      $myrow=unserialize($val);

      $posterdata = get_userdata_from_id($myrow['poster_id']);
   
      echo '<a name="'.$forum.$topic.$myrow['post_id'].'"></a>';
      if ($myrow['post_id']==$max_post_id) echo '<a name="last-post"></a>';
      $posts = $posterdata['posts'];
      if ($posterdata['uname']!=$anonymous) {
         echo "<a href=\"powerpack.php?op=instant_message&amp;to_userid=".$posterdata['uname']."\" class=\"noir\">".$posterdata['uname']."</a>";
      } else {
         echo $posterdata['uname'];
      }
      echo "<br />";
      echo member_qualif($posterdata['uname'], $posts, $posterdata['rank']);
      echo "<br /><br />";
      if ($smilies) {
          if ($posterdata['user_avatar'] != '') {
             if (stristr($posterdata['user_avatar'],"users_private")) {
                $imgtmp=$posterdata['user_avatar'];
             } else {
                if ($ibid=theme_image("forum/avatar/".$posterdata['user_avatar'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/avatar/".$posterdata['user_avatar'];}
             }
             if ($posterdata['mns']) {
                echo "<div class=\"avatar_cadre\"><a href=\"minisite.php?op=".$posterdata['uname']."\" target=\"_blank\"><img src=\"".$imgtmp."\" alt=\"".$posterdata['uname']."\" border=\"0\" /></a></div>";
          } else {
                echo "<div class=\"avatar_cadre\"><img src=\"".$imgtmp."\" alt=\"".$posterdata['uname']."\" border=\"0\" /></div>";
             }
          }
      }

   
      if ($myrow['image'] != "") {
         if ($ibid=theme_image("forum/subject/".$myrow['image'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/subject/".$myrow['image'];}
         echo '<img class="smil" src="'.$imgtmp.'" alt="" />';
      } else {
         echo '<img class="smil" src="'.$imgtmpPI.'" alt="" />';
      }
      $date_post=convertdateTOtimestamp($myrow['post_time']);
      echo "&nbsp;&nbsp;".translate("Posted: ").post_convertdate($date_post);
      if ($last_read!='') {
         if (($last_read <= $date_post) AND $userdata[3]!='' AND $last_read !="0" AND $userdata[0]!=$myrow['poster_id']) {
            echo "&nbsp;<img src=\"$imgtmpNE\" border=\"0\" alt=\"\" />";
         }
      }
      echo "<br /><br />\n";
      $message = stripslashes($myrow['post_text']);
      if (($allow_bbcode) and ($forum_type!=6) and ($forum_type!=5)) {
         $message = smilie($message);
         $message = aff_video_yt($message);
      }
      // <a href in the message
      if (stristr($message,"<a href")) {
         $message=preg_replace('#_blank(")#i','_blank\1 class=\1\1',$message);
      }
      $message=split_string_without_space($message, 80);
      if (($forum_type=="6") or ($forum_type=="5")) {
         highlight_string(stripslashes($myrow['post_text']))."<br /><br />";
      } else {
         echo str_replace("[addsig]", "<br /><br />".nl2br($posterdata['user_sig']), $message);
      }
      if ($att>0) {
         $post_id=$myrow['post_id'];
      
      
         echo display_upload("forum_npds",$post_id,$Mmod);
      
      }
   
   
      if ($forum_access!=9) {
         if ($allow_to_post) {echo aff_pub_in($lock_state,$topic,$forum,$myrow['post_id'],1);}
      }
   
      if ($posterdata['uid']!= 1 and $posterdata['uid']!="") {
         echo "&nbsp;&nbsp;<a href=\"user.php?op=userinfo&amp;uname=".$posterdata['uname']."\" class=\"noir\" target=\"_blank\"><img src=\"$imgtmpPR\" border=\"0\" alt=\"\" /><span style=\"font-size: 10px;\">".translate("Profile")."</span></a>";
      }

      if ($posterdata['femail']!="") {
         echo "&nbsp;&nbsp;<a href=\"mailto:".anti_spam($posterdata['femail'],1)."\" class=\"noir\" target=\"_blank\"><img src=\"$imgtmpEM\" border=\"0\" alt=\"\" /><span style=\"font-size: 10px;\">".translate("Email")."</span></a>";
      }

      if ($posterdata['url']!="") {
         if (strstr("http://", $posterdata['url']))
            $posterdata['url'] = "http://" . $posterdata['url'];
         echo "&nbsp;&nbsp;<a href=\"".$posterdata['url']."\" class=\"noir\" target=\"_blank\"><img src=\"$imgtmpWW\" border=\"0\" alt=\"\" /><span style=\"font-size: 10px;\">www</span></a>";
      }
/*
      if (!$short_user) {
      }
      
*/
      if ($forum_access!=9) {
         if (($Mmod) or ($posterdata['uid']==$userdata[0]) and (!$lock_state) and ($posterdata['uid']!="")) {
            echo "&nbsp;&nbsp;<a href=\"editpost.php?post_id=".$myrow['post_id']."&amp;topic=$topic&amp;forum=$forum&amp;arbre=1\" class=\"noir\"><img src=\"$imgtmpED\" border=\"0\" alt=\"\" /><span style=\"font-size: 10px;\">".translate("Edit")."</span></a>\n";
            if ($allow_upload_forum) {
               $PopUp=win_upload("forum_npds",$myrow['post_id'],$forum,$topic,"popup");
               echo "&nbsp;&nbsp;<a href=\"javascript:void(0);\" onclick=\"window.open($PopUp);\" class=\"noir\"><img src=\"$imgtmpGF\" border=\"0\" alt=\"\" /><span style=\"font-size: 10px;\">".translate("Files")."</span></a>\n";
            }
         }

         if ($allow_to_post and !$lock_state and $posterdata['uid']!="") {
            echo "&nbsp;&nbsp;<a href=\"replyH.php?topic=$topic&amp;forum=$forum&amp;post=".$myrow['post_id']."&amp;citation=1\" class=\"noir\"><img src=\"$imgtmpQU\" border=\"0\" alt=\"\" /><span style=\"font-size: 10px;\">".translate("Quote")."</span></a>\n";
         }
         echo "&nbsp;&nbsp;<a href=\"prntopic.php?forum=$forum&amp;topic=$topic&amp;post_id=".$myrow['post_id']."\" class=\"noir\"><img src=\"$imgtmpRN\" border=\"0\" alt=\"\" /></a>\n";
         if ($Mmod) {
            echo "&nbsp;|&nbsp;";
            echo "<a href=\"topicadmin.php?mode=viewip&amp;topic=$topic&amp;post=".$myrow['post_id']."&amp;forum=$forum&amp;arbre=1\"><img src=\"$imgtmpIP\" border=\"0\" alt=\"\" /></a>&nbsp;<span style=\"font-size: 10px;\">ip</span>\n";
            if (!$myrow['post_aff']) {
               echo "&nbsp;<a href=\"topicadmin.php?mode=aff&amp;topic=$topic&amp;post=".$myrow['post_id']."&amp;ordre=1&amp;forum=$forum&amp;arbre=1\" class=\"noir\"><img src=\"$imgtmpUP\" border=\"0\" alt=\"\" /></a>&nbsp;<span style=\"font-size: 10px; color: red;\">".translate("Hidden post")."</span>\n";
            } else {
               echo "&nbsp;<a href=\"topicadmin.php?mode=aff&amp;topic=$topic&amp;post=".$myrow['post_id']."&amp;ordre=0&amp;forum=$forum&amp;arbre=1\" class=\"noir\"><img src=\"$imgtmpLP\" border=\"0\" alt=\"\" /></a>&nbsp;<span style=\"font-size: 10px;\">".translate("Normal post")."</span>\n";
            }
         }
      }
   
      //-----------------------
      if ($level!="0") {
      
      }
      if ((isset($table[$key])) AND (($maxlevel>$level+1) OR ($maxlevel=="0"))) {
         echo "<img src=\"images/pix.gif\" width=\"".($level*8)."\" height=\"1\" alt=\"\" />",$toggle->Img(),$toggle->Begin();
         $result.= makebranch($key,$table,$level+1,$maxlevel,$max_post_id);
         echo $toggle->End();
      }
   }
   return($result);
}

function aff_pub($lock_state, $topic, $forum, $post, $bouton) {
   global $language;
   $ibid='';
   if ($lock_state==0) {
//      if ($ibid=theme_image("forum/icons/$language/reply.gif")) {$imgtmpR=$ibid;} else {$imgtmpR="images/forum/icons/$language/reply.gif";}
//      if ($ibid=theme_image("forum/icons/$language/new_topic.gif")) {$imgtmpN=$ibid;} else {$imgtmpN="images/forum/icons/$language/new_topic.gif";}
//      if (($bouton==1) or ($bouton==9))
//         echo "<a href=\"replyH.php?topic=$topic&amp;forum=$forum&amp;post=$post\"><img src=\"$imgtmpR\" border=\"0\" alt=\"\" /></a>&nbsp;&nbsp;";
//         echo '<a class="" href="replyH.php?topic='.$topic.'&amp;forum='.$forum.'&amp;post='.$post.'" title="'.translate("Reply").'" data-toggle="tooltip"><i class="fa fa-reply"></i></a>&nbsp;';
      if (($bouton==2) or ($bouton==9))
//         echo "<a href=\"newtopic.php?forum=$forum\"><img src=\"$imgtmpN\" border=\"0\" alt=\"\" /></a>";
           $ibid = '<a class="" href="newtopic.php?forum='.$forum.'" title="'.translate("New Topic").'" data-toggle="tooltip" ><i class="fa fa-plus-square "></i></a>&nbsp;';
   } else {
      if ($ibid=theme_image("forum/icons/$language/reply_locked.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/icons/$language/reply_locked.gif";}
      echo "<img src=\"$imgtmp\" border=\"0\" alt=\"\" />";
   }
   return $ibid;
}

function aff_pub_in($lock_state, $topic, $forum,$post, $bouton) {
   global $language;
   $ibid='';
   if ($lock_state==0) {
      if (($bouton==1) or ($bouton==9))
         $ibid = '<a class="" href="replyH.php?topic='.$topic.'&amp;forum='.$forum.'&amp;post='.$post.'" title="'.translate("Reply").'" data-toggle="tooltip"><i class="fa fa-reply"></i></a>&nbsp;';
   }
   return $ibid;
}

$title=$forum_name; $post=$topic_subject;
include('header.php');
include_once ("lib/togglediv.class.php");
$r_to='';$n_to='';

   echo '
   <a name="topofpage"></a>
   <p class="lead">
      <a href="forum.php">'.translate("Forum Index").'</a>&nbsp;&raquo;&raquo;&nbsp;
      <a href="viewforum.php?forum='.$forum.'">'.stripslashes($forum_name).'</a>&nbsp;&raquo;&raquo;&nbsp;'.$topic_subject.'
   </p>
   <h3>';

   if ($forum_access!=9) {
      $allow_to_post=false;
      if ($forum_access==0) {
         $allow_to_post=true;
      } elseif ($forum_access==1) {
         if (isset($user)) {
            $allow_to_post=true;
         }
      } elseif ($forum_access==2) {
         if (user_is_moderator($userdata[0],$userdata[2],$forum_access)) {
            $allow_to_post=true;
         }
      }
      if ($allow_to_post) {
         $n_to = aff_pub($lock_state,$topic,$forum,0,9);
         $r_to = aff_pub_in($lock_state,$topic,$forum,0,9);
      }
   }
   echo $n_to.$topic_subject.' <span class="text-muted">&nbsp;#'.$topic.'</span> '.$r_to.'</h3>';
   
   
   echo '
      <div class="card">
         <div class="card-block-small">
         '.translate("Moderated By: ");
   for ($i = 0; $i < count($moderator); $i++) {
      $modera = get_userdata($moderator[$i]);
      if ($modera['user_avatar'] != '') {
         if (stristr($modera['user_avatar'],"users_private")) {
          $imgtmp=$modera['user_avatar'];
         } else {
          if ($ibid=theme_image("forum/avatar/".$modera['user_avatar'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/avatar/".$modera['user_avatar'];}
         }
      }
      echo '<a href="user.php?op=userinfo&amp;uname='.$moderator[$i].'"><img width="48" height="48" class=" img-thumbnail img-fluid n-ava" src="'.$imgtmp.'" alt="'.$modera['uname'].'" title="'.$modera['uname'].'" data-toggle="tooltip" /></a>';
   }   
echo '
         </div>
      </div>';

   // Forum Read
   if (isset($user)) {
      $time_actu=time()+($gmt*3600);
      $sqlR = "SELECT last_read FROM ".$NPDS_Prefix."forum_read WHERE forum_id='$forum' AND uid='$userdata[0]' AND topicid='$topic'";
      $result_LR=sql_query($sqlR);
      $last_read="";
      if (sql_num_rows($result_LR)==0) {
         $sqlR = "INSERT INTO ".$NPDS_Prefix."forum_read (forum_id, topicid, uid, last_read, status) VALUES ('$forum', '$topic', '$userdata[0]', '$time_actu', '1')";
         $resultR = sql_query($sqlR);
      } else {
         list($last_read)=sql_fetch_row($result_LR);
         $sqlR = "UPDATE ".$NPDS_Prefix."forum_read SET last_read='$time_actu', status='1' WHERE forum_id='$forum' AND uid='$userdata[0]' AND topicid='$topic'";
         $resultR = sql_query($sqlR);
      }
   }

   if ($ibid=theme_image("forum/icons/posticon.gif")) {$imgtmpPI=$ibid;} else {$imgtmpPI="images/forum/icons/posticon.gif";}
   if ($ibid=theme_image("forum/icons/profile.gif")) {$imgtmpPR=$ibid;} else {$imgtmpPR="images/forum/icons/profile.gif";}
   if ($ibid=theme_image("forum/icons/email.gif")) {$imgtmpEM=$ibid;} else {$imgtmpEM="images/forum/icons/email.gif";}
   if ($ibid=theme_image("forum/icons/www_icon.gif")) {$imgtmpWW=$ibid;} else {$imgtmpWW="images/forum/icons/www_icon.gif";}
   if ($ibid=theme_image("forum/icons/icq_on.gif")) {$imgtmpIC=$ibid;} else {$imgtmpIC="images/forum/icons/icq_on.gif";}
   if ($ibid=theme_image("forum/icons/aim.gif")) {$imgtmpAI=$ibid;} else {$imgtmpAI="images/forum/icons/aim.gif";}
   if ($ibid=theme_image("forum/icons/yim.gif")) {$imgtmpYI=$ibid;} else {$imgtmpYI="images/forum/icons/yim.gif";}
   if ($ibid=theme_image("forum/icons/msnm.gif")) {$imgtmpMS=$ibid;} else {$imgtmpMS="images/forum/icons/msnm.gif";}
   if ($ibid=theme_image("forum/icons/edit.gif")) {$imgtmpED=$ibid;} else {$imgtmpED="images/forum/icons/edit.gif";}
   if ($ibid=theme_image("forum/icons/quote.gif")) {$imgtmpQU=$ibid;} else {$imgtmpQU="images/forum/icons/quote.gif";}
   if ($ibid=theme_image("forum/icons/ip_logged.gif")) {$imgtmpIP=$ibid;} else {$imgtmpIP="images/forum/icons/ip_logged.gif";}
   if ($ibid=theme_image("forum/icons/unlock_post.gif")) {$imgtmpUP=$ibid;} else {$imgtmpUP="images/forum/icons/unlock_post.gif";}
   if ($ibid=theme_image("forum/icons/lock_post.gif")) {$imgtmpLP=$ibid;} else {$imgtmpLP="images/forum/icons/lock_post.gif";}
   if ($ibid=theme_image("forum/icons/gf.gif")) {$imgtmpGF=$ibid;} else {$imgtmpGF="images/forum/icons/gf.gif";}
   if ($ibid=theme_image("forum/icons/print.gif")) {$imgtmpRN=$ibid;} else {$imgtmpRN="images/forum/icons/print.gif";}
   if ($ibid=theme_image("forum/icons/new.gif")) {$imgtmpNE=$ibid;} else {$imgtmpNE="images/forum/icons/new.gif";}

   if ($Mmod) {
      $post_aff=" ";
   } else {
      $post_aff=" AND post_aff='1' ";
   }
   settype($start,"integer");
   settype($posts_per_page,"integer");
   if (isset($start)) {
      if ($start==9999) { $start=$posts_per_page*($pages-1); if ($start<0) {$start=0;}; }
      $sql = "SELECT * FROM ".$NPDS_Prefix."posts WHERE topic_id='$topic' AND forum_id='$forum'".$post_aff."ORDER BY post_id";
   } else {
      $sql = "SELECT * FROM ".$NPDS_Prefix."posts WHERE topic_id='$topic' AND forum_id='$forum'".$post_aff."ORDER BY post_id";
   }
   if ($allow_upload_forum) {
      $visibility = "";
      if (!$Mmod) {
         $visibility = " AND visible = 1";
      }
      $sql2 = "SELECT att_id FROM $upload_table WHERE apli='forum_npds' && topic_id = '$topic' $visible";
      $att = sql_num_rows(sql_query($sql2));
      if ($att>0) {
         include ("modules/upload/include_forum/upload.func.forum.php");
      }
   }
   echo maketree(0,$sql,0);
   if (isset($ancre)) {
      echo "<script type=\"text/javascript\">
      //<![CDATA[
      toggleall$toggle->id('block');
      //]]>
      </script>";
   }
   $sql = "UPDATE ".$NPDS_Prefix."forumtopics SET topic_views = topic_views + 1 WHERE topic_id = '$topic'";
   sql_query($sql);
   echo "<br />";


   echo "<td align=\"left\" valign=\"top\">";
   if ($forum_access!=9) {
      if ($ibid=theme_image("forum/icons/lock_topic.gif")) {$imgtmpLT=$ibid;} else {$imgtmpLT="images/forum/icons/lock_topic.gif";}
      if ($allow_to_post) {
         echo aff_pub($lock_state,$topic,$forum,0,9);
      }
      // un anonyme ne peut pas mettre un topic en resolu
      if (!isset($userdata)) $userdata[0]=0;
      if ((($Mmod) or ($original_poster==$userdata[0])) and (!$lock_state)) {
         $sec_clef=md5($forum.$topic.md5($NPDS_Key));
         echo "&nbsp;&nbsp;<a href=\"viewforum.php?forum=$forum&amp;topic_id=$topic&amp;topic_title=".rawurlencode($topic_subject)."&amp;op=solved&amp;sec_clef=$sec_clef\" class=\"noir\"><img src=\"$imgtmpLT\" border=\"0\" alt=\"\" />&nbsp;".translate("Solved")."</a>\n";
         unset($sec_clef);
      }
   }

   if ($SuperCache) {
      $cache_clef="forum-jump-to";
      $CACHE_TIMINGS[$cache_clef]=600;
      $cache_obj->startCachingBlock($cache_clef);
   }
   if (($cache_obj->genereting_output==1) or ($cache_obj->genereting_output==-1) or (!$SuperCache)) {
      echo '
<form class="" action="viewforum.php" method="post">
   <div class="form-group row">
      <div class="col-xs-12">
         <label class="sr-only" for="forum">'.translate("Jump To: ").'</label>
         <select class="form-control c-select" name="forum" onchange="submit();">
            <option value="index">'.translate("Jump To: ").'</option>
            <option value="index">'.translate("Forum Index").'</option>';
      $sub_sql = "SELECT forum_id, forum_name, forum_type, forum_pass FROM ".$NPDS_Prefix."forums ORDER BY cat_id,forum_index,forum_id";
      if ($res = sql_query($sub_sql)) {
         while (list($forum_id, $forum_name, $forum_type, $forum_pass)=sql_fetch_row($res)) {
            if (($forum_type != "9") or ($userdata)) {
               if (($forum_type == "7") or ($forum_type == "5")) {
                  $ok_affich=false;
               } else {
                  $ok_affich=true;
               }
                if ($ok_affich) echo '
            <option value="'.$forum_id.'">&nbsp;&nbsp;'.stripslashes($forum_name).'</option>';
            }
         }
      }
       echo '
         </select>
      </div>
   </div>
</form>
<a name="botofpage"></a>';
   }
   if ($SuperCache) {
      $cache_obj->endCachingBlock($cache_clef);
   }

   if (($Mmod) and ($forum_access!=9)) {// et le super admin ??
   
      echo '
      <nav class="text-xs-center">
         <ul class="pagination pagination-sm">
            <li class="page-item disabled">
               <a class="page-link" href="#"><i class="fa fa-cogs fa-lg"></i>&nbsp;'.translate("Administration Tools").'</a>
            </li>';
      if ($lock_state==0)
         echo '
            <li class="page-item">
               <a class="page-link" role="button" href="topicadmin.php?mode=lock&amp;topic='.$topic.'&amp;forum='.$forum.'&amp;arbre=1" title="'.translate("Lock this Topic").'" data-toggle="tooltip" ><i class="fa fa-lock fa-lg" aria-hidden="true"></i></a>
            </li>';
      else
         echo '
            <li class="page-item">
               <a class="page-link" role="button" href="topicadmin.php?mode=unlock&amp;topic='.$topic.'&amp;forum='.$forum.'&amp;arbre=1" title="'.translate("Unlock this Topic").'" data-toggle="tooltip"><i class ="fa fa-unlock fa-lg" aria-hidden="true"></i></a>
            </li>';
      echo '
            <li class="page-item">
               <a class="page-link" role="button" href="topicadmin.php?mode=move&amp;topic='.$topic.'&amp;forum='.$forum.'&amp;arbre=1" title="'.translate("Move this Topic").'" data-toggle="tooltip"><i class="fa fa-share fa-lg" aria-hidden="true"></i></a>
            </li>
            <li class="page-item">
               <a class="page-link" role="button" href="topicadmin.php?mode=first&amp;topic='.$topic.'&amp;forum='.$forum.'&amp;arbre=1" title="'.translate("Make this Topic the first one").'" data-toggle="tooltip"><i class="fa fa-level-up fa-lg" aria-hidden="true"></i></a>
            </li>
            <li class="page-item">
               <a class="page-link text-danger" role="button" href="topicadmin.php?mode=del&amp;topic='.$topic.'&amp;forum='.$forum.'&amp;arbre=1" title="'.translate("Delete this Topic").'" data-toggle="tooltip"><i class="fa fa-remove fa-lg" aria-hidden="true"></i></a>
            </li>
         </ul>
      </nav>';
   
   
   
  
//        echo "<p class=\"text-xs-center\"><b>".translate("Administration Tools")."</b><br />";
//        echo "-------------------------<br />";
//        if ($ibid=theme_image("forum/icons/unlock_topic.gif")) {$imgtmpUT=$ibid;} else {$imgtmpUT="images/forum/icons/unlock_topic.gif";}
//        if ($ibid=theme_image("forum/icons/move_topic.gif")) {$imgtmpMT=$ibid;} else {$imgtmpMT="images/forum/icons/move_topic.gif";}
//        if ($ibid=theme_image("forum/icons/del_topic.gif")) {$imgtmpDT=$ibid;} else {$imgtmpDT="images/forum/icons/del_topic.gif";}
//        if ($ibid=theme_image("forum/icons/first_topic.gif")) {$imgtmpFT=$ibid;} else {$imgtmpFT="images/forum/icons/first_topic.gif";}
//        if ($lock_state==0)
//           echo "<a href=\"topicadmin.php?mode=lock&amp;topic=$topic&amp;forum=$forum&amp;arbre=1\"><img src=\"$imgtmpLT\" alt=\"".translate("Lock this Topic")."\" border=\"0\" /></a> ";
//        else
//           echo "<a href=\"topicadmin.php?mode=unlock&amp;topic=$topic&amp;forum=$forum&amp;arbre=1\"><img src=\"$imgtmpUT\" alt=\"".translate("Unlock this Topic")."\" border=\"0\" /></a> ";
//        echo "<a href=\"topicadmin.php?mode=move&amp;topic=$topic&amp;forum=$forum&amp;arbre=1\"><img src=\"$imgtmpMT\" alt=\"".translate("Move this Topic")."\" border=\"0\" /></a> ";
//        echo "<a href=\"topicadmin.php?mode=del&amp;topic=$topic&amp;forum=$forum&amp;arbre=1\"><img src=\"$imgtmpDT\" alt=\"".translate("Delete this Topic")."\" border=\"0\" /></a> ";
//        echo "<a href=\"topicadmin.php?mode=first&amp;topic=$topic&amp;forum=$forum&amp;arbre=1\"><img src=\"$imgtmpFT\" alt=\"".translate("Make this Topic the first one")."\" border=\"0\" /></a></p>\n";
  
   }
include("footer.php");
?>
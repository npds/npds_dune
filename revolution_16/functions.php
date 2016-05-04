<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2001-2015 by Philippe Brunier                     */
/* =========================                                            */
/*                                                                      */
/* Based on PhpNuke 4.x and PhpBB integration source code               */
/* Great mods by snipe                                                  */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

function get_total_topics($forum_id) {
   global $NPDS_Prefix;
   $sql = "SELECT COUNT(*) AS total FROM ".$NPDS_Prefix."forumtopics WHERE forum_id='$forum_id'";
   if (!$result = sql_query($sql))
      return("ERROR");
   if (!$myrow = sql_fetch_assoc($result))
      return("ERROR");

   sql_free_result($result);
   return($myrow['total']);
}
// probablement ˆ supprimer lol il suffit de prendre le nombre de ligne  de getcontributeurs()
function get_total_contributeurs($fid, $tid) {
   global $NPDS_Prefix;
   $sql = "SELECT COUNT(DISTINCT poster_id) AS total_contributeurs FROM ".$NPDS_Prefix."posts WHERE topic_id='$tid' AND forum_id='$fid'";
   if (!$result = sql_query($sql))
      return("ERROR");
   if (!$myrow = sql_fetch_assoc($result))
      return("ERROR");

   sql_free_result($result);
   return($myrow['total_contributeurs']);
}

function get_contributeurs($fid, $tid) {
   global $NPDS_Prefix;
   $rowQ1=Q_Select("SELECT DISTINCT poster_id FROM ".$NPDS_Prefix."posts WHERE topic_id='$tid' AND forum_id='$fid'",2);
   $myrow['poster_id']="";
   while(list(,$poster_id) = each($rowQ1)) {
      $myrow['poster_id'].= $poster_id['poster_id']." ";
   }
   return(chop($myrow['poster_id']));
}

function get_total_posts($fid, $tid, $type, $Mmod) {
   global $NPDS_Prefix;
   if ($Mmod) {
      $post_aff='';
   } else {
      $post_aff=" AND post_aff='1'";
   }
   switch($type) {
      case 'forum':
           $sql = "SELECT COUNT(*) AS total FROM ".$NPDS_Prefix."posts WHERE forum_id='$fid'$post_aff";
           break;
      case 'topic':
           $sql = "SELECT COUNT(*) AS total FROM ".$NPDS_Prefix."posts WHERE topic_id='$tid' AND forum_id='$fid' $post_aff";
           break;
      case 'user':
           forumerror(0031);
   }

   if (!$result = sql_query($sql))
      return("ERROR");
   if (!$myrow = sql_fetch_assoc($result))
      return("0");

   sql_free_result($result);
   return($myrow['total']);
}

function get_last_post($id, $type, $cmd, $Mmod) {
   global $NPDS_Prefix;
   // $Mmod ne sert plus - maintenu pour compatibilité
   switch($type) {
      case 'forum':
           $sql1 = "SELECT topic_time, current_poster FROM ".$NPDS_Prefix."forumtopics WHERE forum_id = '$id' ORDER BY topic_time DESC LIMIT 0,1";
           $sql2 = "SELECT uname FROM ".$NPDS_Prefix."users WHERE uid=";
           break;

      case 'topic':
           $sql1 = "SELECT topic_time, current_poster FROM ".$NPDS_Prefix."forumtopics WHERE topic_id = '$id'";
           $sql2 = "SELECT uname FROM ".$NPDS_Prefix."users WHERE uid=";
           break;
   }
   if (!$result = sql_query($sql1))
      return("ERROR");

   if ($cmd=='infos') {
      if (!$myrow = sql_fetch_row($result)) {
         $val=translate("No posts");
      } else {
         $rowQ1=Q_Select ($sql2."'".$myrow[1]."'", 3600);
         $val=convertdate($myrow[0]).'<br /><a href="user.php?op=userinfo&amp;uname='.$rowQ1[0]['uname'].'" class="small">'.$rowQ1[0]['uname'].'</a>';
      }
   }
   sql_free_result($result);
   return($val);
}

function convertdateTOtimestamp($myrow) {
   if (substr($myrow,2,1)=="-") {
      $day=substr($myrow,0,2);
      $month=substr($myrow,3,2);
      $year=substr($myrow,6,4);
   } else {
      $day=substr($myrow,8,2);
      $month=substr($myrow,5,2);
      $year=substr($myrow,0,4);
   }
   $hour=substr($myrow,11,2);
   $mns=substr($myrow,14,2);
   $sec=substr($myrow,17,2);
   $tmst=mktime($hour,$mns,$sec,$month,$day,$year);
   return ($tmst);
}
function post_convertdate($tmst) {
   if ($tmst>0)
      $val=date(translate("dateinternal"),$tmst);
   else
      $val="";
   return ($val);
}
function convertdate($myrow) {
   $tmst=convertdateTOtimestamp($myrow);
   $val=post_convertdate($tmst);
   return ($val);
}

function get_moderator($user_id) {
   global $NPDS_Prefix;
   $user_id=str_replace(",","' or uid='",$user_id);
   if ($user_id == 0)
      return("None");

   $rowQ1=Q_Select("SELECT uname FROM ".$NPDS_Prefix."users WHERE uid='$user_id'", 3600);
   $myrow['uname']="";
   while(list(,$uname) = each($rowQ1)) {
      $myrow['uname'].=$uname['uname']." ";
   }
   return(chop($myrow['uname']));
}

function user_is_moderator($uidX,$passwordX,$forum_accessX) {
   global $NPDS_Prefix;
   $result1=sql_query("SELECT pass FROM ".$NPDS_Prefix."users WHERE uid = '$uidX'");
   $result2=sql_query("SELECT level FROM ".$NPDS_Prefix."users_status WHERE uid = '$uidX'");
   $userX=sql_fetch_assoc($result1);
   $password = $userX['pass'];
   $userX=sql_fetch_assoc($result2);
   if ((md5($password) == $passwordX) and ($forum_accessX<=$userX['level']) and ($userX['level']>1)) {
      return ($userX['level']);
   } else {
      return(false);
   }
}

function get_userdata_from_id($userid) {
   global $NPDS_Prefix;
   $sql1 = "SELECT * FROM ".$NPDS_Prefix."users WHERE uid='$userid'";
   $sql2 = "SELECT * FROM ".$NPDS_Prefix."users_status WHERE uid='$userid'";
   if (!$result = sql_query($sql1))
      forumerror('0016');
   if (!$myrow = sql_fetch_assoc($result))
      $myrow = array( "uid" => 1);
   else
      $myrow=array_merge($myrow,(array)sql_fetch_assoc(sql_query($sql2)));
   return($myrow);
}

function get_userdata_extend_from_id($userid) {
   global $NPDS_Prefix;
   $sql1 = "SELECT * FROM ".$NPDS_Prefix."users_extend WHERE uid='$userid'";
/*   $sql2 = "SELECT * FROM ".$NPDS_Prefix."users_status WHERE uid='$userid'";

   if (!$result = sql_query($sql1))  
      forumerror('0016');

   if (!$myrow = sql_fetch_assoc($result))
      $myrow = array( "uid" => 1);
   else
      $myrow=array_merge($myrow,(array)sql_fetch_assoc(sql_query($sql1)));
 */
$myrow= (array)sql_fetch_assoc(sql_query($sql1));
 
 return($myrow);
}


function get_userdata($username) {
   global $NPDS_Prefix;
   $sql = "SELECT * FROM ".$NPDS_Prefix."users WHERE uname='$username'";
   if (!$result = sql_query($sql))
      forumerror('0016');
   if (!$myrow = sql_fetch_assoc($result))
      $myrow = array( "uid" => 1);
   return($myrow);
}

function does_exists($id, $type) {
   global $NPDS_Prefix;
   switch($type) {
      case 'forum':
           $sql = "SELECT forum_id FROM ".$NPDS_Prefix."forums WHERE forum_id = '$id'";
           break;
      case 'topic':
           $sql = "SELECT topic_id FROM ".$NPDS_Prefix."forumtopics WHERE topic_id = '$id'";
           break;
   }
   if (!$result = sql_query($sql))
      return(0);
   if (!$myrow = sql_fetch_row($result))
      return(0);
   return(1);
}

function is_locked($topic) {
   global $NPDS_Prefix;
   $sql = "SELECT topic_status FROM ".$NPDS_Prefix."forumtopics WHERE topic_id = '$topic'";
   if (!$r = sql_query($sql))
      return(FALSE);
   if (!$m = sql_fetch_assoc($r))
      return(FALSE);
   if (($m['topic_status']==1) or ($m['topic_status']==2))
      return(TRUE);
   else
      return(FALSE);
}

function smilie($message) {
   // Tranforme un :-) en IMG
   global $theme;
   if ($ibid=theme_image("forum/smilies/smilies.php")) {$imgtmp="themes/$theme/images/forum/smilies/";} else {$imgtmp="images/forum/smilies/";}
   if (file_exists($imgtmp."smilies.php")) {
      include ($imgtmp."smilies.php");
      foreach ($smilies AS $tab_smilies) {
         $suffix=strtoLower(substr(strrchr($tab_smilies[1],'.'),1));
         if (($suffix=="gif") or ($suffix=="png"))
            $message = str_replace($tab_smilies[0], "<img class=\"smil\" src='".$imgtmp.$tab_smilies[1]."' />", $message);
         else
            $message = str_replace($tab_smilies[0], $tab_smilies[1], $message);
      }
   }
   if ($ibid=theme_image("forum/smilies/more/smilies.php")) {$imgtmp="themes/$theme/images/forum/smilies/more/";} else {$imgtmp="images/forum/smilies/more/";}
   if (file_exists($imgtmp."smilies.php")) {
      include ($imgtmp."smilies.php");
      foreach ($smilies AS $tab_smilies) {
         $message = str_replace($tab_smilies[0], "<img class='smil' src='".$imgtmp.$tab_smilies[1]."' />", $message);
      }
   }
   return($message);
}

function smile($message) {
   // Tranforme une IMG en :-)
   global $theme;
   if ($ibid=theme_image("forum/smilies/smilies.php")) {$imgtmp="themes/$theme/images/forum/smilies/";} else {$imgtmp="images/forum/smilies/";}
   if (file_exists($imgtmp."smilies.php")) {
      include ($imgtmp."smilies.php");
      foreach ($smilies AS $tab_smilies) {
         $message = str_replace("<img class='smil' src='".$imgtmp.$tab_smilies[1]."' />", $tab_smilies[0],  $message);
      }
   }
   if ($ibid=theme_image("forum/smilies/more/smilies.php")) {$imgtmp="themes/$theme/images/forum/smilies/more/";} else {$imgtmp="images/forum/smilies/more/";}
   if (file_exists($imgtmp."smilies.php")) {
      include ($imgtmp."smilies.php");
      foreach ($smilies AS $tab_smilies) {
         $message = str_replace("<img class='smil' src='".$imgtmp.$tab_smilies[1]."' />", $tab_smilies[0],  $message);
      }
   }
   return($message);
}

function aff_video_yt($ibid) {
   // analyse et génére un tag video_yt à la volée - JPB 01-2011
   $w_video=320;
   $h_video=265;
   $pasfin=true;
   while ($pasfin) {
      $pos_deb=strpos($ibid,"[video_yt]",0);
      $pos_fin=strpos($ibid,"[/video_yt]",0);
      // ne pas confondre la position ZERO et NON TROUVE !
      if ($pos_deb===false) {$pos_deb=-1;}
      if ($pos_fin===false) {$pos_fin=-1;}
      if (($pos_deb>=0) and ($pos_fin>=0)) {
         $id_vid= substr($ibid,$pos_deb+10,($pos_fin-$pos_deb-10));
         $fragment = substr( $ibid, 0,$pos_deb);
         $fragment2 = substr( $ibid,($pos_fin+11));
         $ibid_code = '<br /><div class="embed-responsive embed-responsive-16by9"><!-- video_yt #'.$id_vid.'# --><object width="'.$w_video.'" height="'.$h_video.'">
         <param name="movie" value="http://www.youtube.com/v/'.$id_vid.'=en&fs=1" /><param name="allowFullScreen" value="true" /><param name="allowscriptaccess" value="always" /><embed class="embed-responsive-item" src=http://www.youtube.com/v/'.$id_vid.'&fs=1 type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="'.$w_video.'" height="'.$h_video.'" /></object><!-- /video_yt --><br /></div>';
         $ibid= $fragment.$ibid_code.$fragment2;
      } else {
         $pasfin=false;
      }
   }
   return ($ibid);
}
// ne fonctionne pas dans tous les contextes car on a pas la variable du theme !!!!
function putitems_more() {
   global $theme,$tmp_theme;
   if (stristr($_SERVER['PHP_SELF'],"more_emoticon.php")) $theme=$tmp_theme;
   
   echo "<p class=\"noir\" align=\"center\">".translate("Click on Smilies to insert it on your Message")."</p>";
   if ($ibid=theme_image("forum/smilies/more/smilies.php"))
   {$imgtmp="themes/$theme/images/forum/smilies/more/";} 
   else 
   {$imgtmp="images/forum/smilies/more/";}

   if (file_exists($imgtmp."smilies.php")) {
      include ($imgtmp."smilies.php");
      $rowcolor = tablos();
      echo "<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">";
      $i=0;
      foreach ($smilies AS $tab_smilies) {
         if ($tab_smilies[3]) {
            if ($i==0) echo "<tr $rowcolor>";
            echo "<td width=\"20%\" align=\"center\">
            <a href=\"javascript: DoAdd('true','message',' ".$tab_smilies[0]." ');\"><img src=\"".$imgtmp.$tab_smilies[1]."\" width=\"32\" height=\"32\" border=\"0\" alt=\"$tab_smilies[2]";
            if ($tab_smilies[2]) echo " => ";
            echo $tab_smilies[0]."\" /></a></td>";
            $i++;
            if ($i==5) {
               $rowcolor = tablos();
               echo "</tr>";
               $i=0;
            }
         }
      }
      if ($i>0)
         echo "<td colspan=\"".(5-$i)."\"></td></tr></table>";
      else
         echo "</table>";
   }
}
function putitems() {
   global $theme;
//   echo '   <a href="#" class="" title="'.translate("Click on Smilies to insert it on your Message").'" data-toggle="tooltip"><i class="fa fa-smile-o fa-lg"></i> + </a>';
   
   echo '<span class ="fond_emot">';
   if ($ibid=theme_image("forum/smilies/smilies.php")) {$imgtmp="themes/$theme/images/forum/smilies/";} else {$imgtmp="images/forum/smilies/";}
   if (file_exists($imgtmp."smilies.php")) {
      include ($imgtmp."smilies.php");
      foreach ($smilies AS $tab_smilies) {
         if ($tab_smilies[3]) {
            echo "<a href=\"javascript: emoticon('".$tab_smilies[0]."');\"><img class=\"smil\" src=\"".$imgtmp.$tab_smilies[1]."\" border=\"0\" alt=\"$tab_smilies[2]";
            if ($tab_smilies[2]) echo " => ";
            echo $tab_smilies[0]."\" /></a> ";
         }
      }
   }
   echo '</span>';
   
//    global $allow_bbcode;
//    if ($allow_bbcode) {
      if ($ibid=theme_image("forum/smilies/more/smilies.php")) 
      {$imgtmp="themes/$theme/images/forum/smilies/more/";} 
      else 
      {$imgtmp="images/forum/smilies/more/";}
      
      if (file_exists($imgtmp."smilies.php"))
         echo '&nbsp;<a href="javascript:void(0);" onclick="window.open(\'more_emoticon.php\',\'EMOTICON\',\'menubar=no,location=no,directories=no,status=no,copyhistory=no,height=250,width=350,toolbar=no,scrollbars=yes,resizable=yes\');" title="'.translate("More smilies").'" data-toggle="tooltip"><i class="fa fa-smile-o fa-lg"></i>+</a>';
//   }
}

function HTML_Add() {
   $affich = '
         <div>'
         .'<a href="javascript: addText(\'&lt;b&gt;\',\'&lt;/b&gt;\');" title="'.translate("Bold").'" data-toggle="tooltip" ><i class="fa fa-bold fa-lg"></i></a>&nbsp;  '
         .'<a href="javascript: addText(\'&lt;i&gt;\',\'&lt;/i&gt;\');" title="'.translate("Italic").'" data-toggle="tooltip" ><i class="fa fa-italic fa-lg"></i></a>&nbsp;  '
         .'<a href="javascript: addText(\'&lt;u&gt;\',\'&lt;/u&gt;\');" title="'.translate("Underline").'" data-toggle="tooltip" ><i class="fa fa-underline fa-lg"></i></a>&nbsp;  '
         .'<a href="javascript: addText(\'&lt;p align=\\\'left\\\'&gt;\',\'&lt;/p&gt;\');" title="'.translate("Text align-left").'" data-toggle="tooltip" ><i class="fa fa-align-left fa-lg"></i></a>&nbsp;  '
         .'<a href="javascript: addText(\'&lt;p align=\\\'center\\\'&gt;\',\'&lt;/p&gt;\');" title="'.translate("Text center").'" data-toggle="tooltip" ><i class="fa fa-align-center fa-lg"></i></a>&nbsp;  '
         .'<a href="javascript: addText(\'&lt;p align=\\\'right\\\'&gt;\',\'&lt;/p&gt;\');" title="'.translate("Text align-left").'" data-toggle="tooltip" ><i class="fa fa-align-right fa-lg"></i></a>&nbsp;  '
         .'<a href="javascript: addText(\'&lt;p align=\\\'justify\\\'&gt;\',\'&lt;/p&gt;\');" title="'.translate("Text justified").'" data-toggle="tooltip" ><i class="fa fa-align-justify fa-lg"></i></a>&nbsp;  '
         .'<a href="javascript: addText(\'&lt;ul&gt;&lt;li&gt;\',\'&lt;/li&gt;&lt;/ul&gt;\');" title="'.translate("Unordered list").'" data-toggle="tooltip" ><i class="fa fa-list-ul fa-lg"></i></a>&nbsp;  '
         .'<a href="javascript: addText(\'&lt;ol&gt;&lt;li&gt;\',\'&lt;/li&gt;&lt;/ol&gt;\');" title="'.translate("Ordered list").'" data-toggle="tooltip" ><i class="fa fa-list-ol fa-lg"></i></a>&nbsp;  '
         .'<a href="javascript: addText(\' http://www.\',\'\');" title="'.translate("Web link").'" data-toggle="tooltip" ><i class="fa fa-link fa-lg"></i></a>&nbsp;  '
         ."<a href=\"javascript: addText('&lt;table width=\'90%\' cellspacing=\'1\' cellpadding=\'3\' border=\'0\' align=\'center\' class=\'ligna\'&gt;&lt;tr&gt;&lt;td&gt;Spoiler&lt;/td&gt;&lt;/tr&gt;&lt;tr&gt;&lt;td&gt;&lt;table width=\'100%\' border=\'0\' cellpadding=\'1\' cellspacing=\'0\'&gt;&lt;tr&gt;&lt;td style=\'background-color: #FFFFFF;\'&gt;&lt;span style=\'color: #FFFFFF;\'&gt; ...\\n&lt;/span&gt;&lt;/td&gt;&lt;/tr&gt;&lt;/table&gt;&lt;/td&gt;&lt;/tr&gt;&lt;/table&gt;','');\"><i class=\"fa fa-table fa-lg\"></i></a>&nbsp;  "
         ."<a href=\"javascript: addText('&lt;pre&gt;[code]','[/code]&lt;/pre&gt;');\"><i class=\"fa fa-code\"></i></a>&nbsp;  "
         .'<a href="javascript: addText(\'[video_yt]\',\'[/video_yt]\');" title="'.translate("Youtube video").' ID : [video_yt]_pnVFFgz[/video_yt] " data-toggle="tooltip"><i class="fa fa-youtube fa-lg"></i></a>&nbsp;  
          </div>';
   return($affich);
}

function emotion_add($image_subject) {
   global $theme;

   if ($ibid=theme_image("forum/subject/index.html")) {$imgtmp="themes/$theme/images/forum/subject";} else {$imgtmp="images/forum/subject";}
   $handle=opendir($imgtmp);
   while (false!==($file = readdir($handle))) {
      $filelist[] = $file;
   }
   asort($filelist);
   $temp='';
   while (list ($key, $file) = each ($filelist)) {
      if (!preg_match('#\.gif|\.jpg|\.png$#i', $file)) continue;
      if ($image_subject!='') {
         if ($file == $image_subject) {
            $temp .= '<input type="radio" value="'.$file.'" name="image_subject" checked="checked" />&nbsp;';
         } else {
            $temp .= '<input type="radio" value="'.$file.'" name="image_subject" />&nbsp;';
         }
      } else {
         $temp .= '<input type="radio" value="'.$file.'" name="image_subject" checked="checked" />&nbsp;';
         $image_subject='no image';
      }
      $temp .= '<img class="smil" src="'.$imgtmp.'/'.$file.'" border="0" alt="" />&nbsp;';
   }
   return $temp;
}

function make_clickable($text) {
   $ret='';
   $ret = preg_replace('#(^|\s)(http|https|ftp|sftp)(://)([^\s]*)#i',' <a href="$2$3$4" target="_blank" class="noir">$2$3$4</a>',$text);
   $ret = preg_replace_callback('#([_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4})#i',function (&$r) {return preg_anti_spam($r[1]);},$ret);//php7
   return($ret);
}

function undo_htmlspecialchars($input) {
   $input = preg_replace("/&gt;/i", ">", $input);
   $input = preg_replace("/&lt;/i", "<", $input);
   $input = preg_replace("/&quot;/i", "\"", $input);
   $input = preg_replace("/&amp;/i", "&", $input);
   return ($input);
}

function searchblock() {
   $ibid='
   <nav class="navbar navbar-light bg-faded">
      <form class="form-inline pull-xs-right" id="searchblock" action="searchbb.php" method="post" name="forum_search">
         <input type="hidden" name="addterm" value="any" />
         <input type="hidden" name="sortby" value="0" />
         <div class="form-group">
            <label class="sr-only" for="term">'.translate('Search').'</label>
            <input type="text" class="form-control" name="term" id="term" placeholder="'.translate('Search').'">
         </div>
         <button type="submit" class="btn btn-primary-outline">'.translate("Submit").'</button>
      </form>
   </nav>';
   return ($ibid);
}

function member_qualif($poster, $posts, $rank) {
   global $anonymous;
   $tmp='';
   if ($ibid=theme_image('forum/rank/post.gif')) {$imgtmpP=$ibid;} else {$imgtmpP='images/forum/rank/post.gif';}
   if ($ibid=theme_image('forum/rank/level.gif')) {$imgtmpN=$ibid;} else {$imgtmpN='images/forum/rank/level.gif';}
   $tmp='<img class="smil" src="'.$imgtmpP.'" border="0" alt="" />'.$posts.'&nbsp;';
   if ($poster!=$anonymous) {
      $nux=0;
      if ($posts>=10 and $posts<30) {$nux=1;}
      if ($posts>=30 and $posts<100) {$nux=2;}
      if ($posts>=100 and $posts<300) {$nux=3;}
      if ($posts>=300 and $posts<1000) {$nux=4;}
      if ($posts>=1000) {$nux=5;}
      for ($i=0; $i<$nux; $i++) {
         $tmp.='<i class="fa fa-star-o text-success"></i>&nbsp;';
      }

      if ($rank) {
         if ($ibid=theme_image("forum/rank/".$rank.".gif")) {$imgtmpA=$ibid;} else {$imgtmpA="images/forum/rank/".$rank.".gif";}
         $rank='rank'.$rank;
         global $$rank;
         $tmp.='<br /><img src="'.$imgtmpA.'" border="" alt="" />&nbsp;'.aff_langue($$rank);
      }
   }
   return ($tmp);
}

function forumerror($e_code) {
   global $sitename, $header;

   if ($e_code == "0001") {
      $error_msg = translate("Could not connect to the forums database.");
   }
   if ($e_code == "0002") {
      $error_msg = translate("The forum you selected does not exist. Please go back and try again.");
   }
   if ($e_code == "0004") {
      $error_msg = translate("Could not query the topics database.");
   }
   if ($e_code == "0005") {
      $error_msg = translate("Error getting messages from the database.");
   }
   if ($e_code == "0006") {
      $error_msg = translate("Please enter the Nickname and the Password.");
   }
   if ($e_code == "0007") {
      $error_msg = translate("You are not the Moderator of this forum therefore you can't perform this function.");
   }
   if ($e_code == "0008") {
      $error_msg = translate("You did not enter the correct password, please go back and try again.");
   }
   if ($e_code == "0009") {
      $error_msg = translate("Could not remove posts from the database.");
   }
   if ($e_code == "0010") {
      $error_msg = translate("Could not move selected topic to selected forum. Please go back and try again.");
   }
   if ($e_code == "0011") {
      $error_msg = translate("Could not lock the selected topic. Please go back and try again.");
   }
   if ($e_code == "0012") {
      $error_msg = translate("Could not unlock the selected topic. Please go back and try again.");
   }
   if ($e_code == "0013") {
      $error_msg = translate("Could not query the database.")."<br />Error: sql_error()";
   }
   if ($e_code == "0014") {
      $error_msg = translate("No such user or post in the database.");
   }
   if ($e_code == "0015") {
      $error_msg = translate("Search Engine was unable to query the forums database.");
   }
   if ($e_code == "0016") {
      $error_msg = translate("That user does not exist. Please go back and search again.");
   }
   if ($e_code == "0017") {
      $error_msg = translate("You must type a subject to post. You can't post an empty subject. Go back and enter the subject");
   }
   if ($e_code == "0018") {
      $error_msg = translate("You must choose message icon to post. Go back and choose message icon.");
   }
   if ($e_code == "0019") {
      $error_msg = translate("You must type a message to post. You can't post an empty message. Go back and enter a message.");
   }
   if ($e_code == "0020") {
      $error_msg = translate("Could not enter data into the database. Please go back and try again.");
   }
   if ($e_code == "0021") {
      $error_msg = translate("Can't delete the selected message.");
   }
   if ($e_code == "0022") {
      $error_msg = translate("An error ocurred while querying the database.");
   }
   if ($e_code == "0023") {
      $error_msg = translate("Selected message was not found in the forum database.");
   }
   if ($e_code == "0024") {
      $error_msg = translate("You can't reply to that message. It wasn't sent to you.");
   }
   if ($e_code == "0025") {
      $error_msg = translate("You can't post a reply to this topic, it has been locked. Contact the administrator if you have any question.");
   }
   if ($e_code == "0026") {
      $error_msg = translate("The forum or topic you are attempting to post to does not exist. Please try again.");
   }
   if ($e_code == "0027") {
      $error_msg = translate("You must enter your username and password. Go back and do so.");
   }
   if ($e_code == "0028") {
      $error_msg = translate("You have entered an incorrect password. Go back and try again.");
   }
   if ($e_code == "0029") {
      $error_msg = translate("Couldn't update post count.");
   }
   if ($e_code == "0030") {
      $error_msg = translate("The forum you are attempting to post to does not exist. Please try again.");
   }
   if ($e_code == "0031") {
      return(0);
   }
   if ($e_code == "0035") {
      $error_msg = translate("You can't edit a post that's not yours.");
   }
   if ($e_code == "0036") {
      $error_msg = translate("You do not have permission to edit this post.");
   }
   if ($e_code == "0037") {
      $error_msg = translate("You did not supply the correct password or do not have permission to edit this post. Please go back and try again.");
   }
   if ($e_code == "0101") {
      $error_msg = translate("You can't reply to that message.");
   }
   if (!isset($header)) {
      include("header.php");
   }
   echo '
   <div class="alert alert-danger"><strong>'.$sitename.'<br />'.translate("Forum Error").'</strong><br />';
   echo translate("Error Code:").' '.$e_code.'<br /><br />';
   echo $error_msg.'<br /><br />';
   echo '<a href="javascript:history.go(-1)" class="btn btn-secondary">'.translate("Go Back").'</a><br /></div>';
   include("footer.php");
   die("");
}

function control_efface_post($apli,$post_id,$topic_id,$IdForum) {
   global $upload_table;
   global $NPDS_Prefix;
   include ("modules/upload/include_forum/upload.conf.forum.php");
   $sql1= "SELECT att_id, att_name, att_path FROM ".$NPDS_Prefix."$upload_table WHERE apli='$apli' AND";
   $sql2= "DELETE FROM ".$NPDS_Prefix."$upload_table WHERE apli='$apli' AND";
   if ($IdForum!='') {
      $sql1.=" forum_id = '$IdForum'";
      $sql2.=" forum_id = '$IdForum'";
   } elseif ($post_id!='') {
      $sql1.=" post_id = '$post_id'";
      $sql2.=" post_id = '$post_id'";
   } elseif ($topic_id!='') {
      $sql1.=" topic_id = '$topic_id'";
      $sql2.=" topic_id = '$topic_id'";
   }
   $result=sql_query($sql1);
   while(list($att_id, $att_name, $att_path)=sql_fetch_row($result)){
      $fic=$DOCUMENTROOT.$att_path.$att_id.".".$apli.".".$att_name;
      @unlink($fic);
   }
   @sql_query($sql2);
}

function autorize() {
   global $apli,$IdPost,$IdTopic,$IdForum,$user;
   global $NPDS_Prefix;
   list($poster_id)= sql_fetch_row(sql_query("SELECT poster_id FROM ".$NPDS_Prefix."posts WHERE post_id='$IdPost' AND topic_id='$IdTopic'"));
   $Mmod=false;
   if ($poster_id) {
      $myrow = sql_fetch_assoc(sql_query("SELECT forum_moderator FROM ".$NPDS_Prefix."forums WHERE (forum_id='$IdForum')"));
      if ($myrow) {
         $moderator = get_moderator($myrow['forum_moderator']);
         $moderator=explode(" ",$moderator);
         if (isset($user)) {
            $userX = base64_decode($user);
            $userdata = explode(":", $userX);
            for ($i = 0; $i < count($moderator); $i++) {
               if (($userdata[1]==$moderator[$i])) { $Mmod=true; break;}
            }
            if ($userdata[0]==$poster_id) {
               $Mmod=true;
            }
         }
      }
   }
   return ($Mmod);
}

function anti_flood ($modoX, $paramAFX, $poster_ipX, $userdataX, $gmtX) {
   // anti_flood : nd de post dans les 90 puis 30 dernières minutes / les modérateurs echappent à cette règle
   // security.log est utilisée pour enregistrer les tentatives
   global $NPDS_Prefix;
   global $anonymous;
   if (!array_key_exists('uname',$userdataX)) $compte=$anonymous; else $compte=$userdataX['uname'];

   if ((!$modoX) AND ($paramAFX>0)) {
      $sql="SELECT COUNT(poster_ip) AS total FROM ".$NPDS_Prefix."posts WHERE post_time>'";
      if ($userdataX['uid']!=1)
         $sql2="' AND (poster_ip='$poster_ipX' OR poster_id='".$userdataX['uid']."')";
      else
         $sql2="' AND poster_ip='$poster_ipX'";

      $timebase=date("Y-m-d H:i",time()+($gmtX*3600)-5400);
      list($time90)=sql_fetch_row(sql_query ($sql.$timebase.$sql2));
      if ($time90>($paramAFX*2)) {
         Ecr_Log("security", "Forum Anti-Flood : ".$compte, "");
         forumerror(translate("You are not allowed to post in this forum"));
      } else {
         $timebase=date("Y-m-d H:i",time()+($gmtX*3600)-1800);
         list($time30)=sql_fetch_row(sql_query($sql.$timebase.$sql2));
         if ($time30>$paramAFX) {
            Ecr_Log("security", "Forum Anti-Flood : ".$compte, "");
            forumerror(translate("You are not allowed to post in this forum"));
         }
      }
   }
}

function forum($rowQ1) {
   global $user, $subscribe, $theme, $NPDS_Prefix, $admin, $adminforum;

//==> droits des admin sur les forums (superadmin et admin avec droit gestion forum)
   $adminforum=false;
   if ($admin) {
      $adminX = base64_decode($admin);
      $adminR = explode(':', $adminX);
      $Q = sql_fetch_assoc(sql_query("SELECT * FROM ".$NPDS_Prefix."authors WHERE aid='$adminR[0]' LIMIT 1"));
      if ($Q['radminsuper']==1) {$adminforum=1;} else {
         $R = sql_query("SELECT fnom, fid, radminsuper FROM ".$NPDS_Prefix."authors a LEFT JOIN ".$NPDS_Prefix."droits d ON a.aid = d.d_aut_aid LEFT JOIN ".$NPDS_Prefix."fonctions f ON d.d_fon_fid = f.fid WHERE a.aid='$adminR[0]' AND f.fid BETWEEN 13 AND 15");
         if (sql_num_rows($R) >=1) $adminforum=1;
      }
   }
//<== droits des admin sur les forums (superadmin et admin avec droit gestion forum)

   if ($user) {
      $userX = base64_decode($user);
      $userR = explode(':', $userX);
      $tab_groupe=valid_group($user);
   }

   if ($ibid=theme_image("forum/icons/red_folder.gif")) {$imgtmpR=$ibid;} else {$imgtmpR="images/forum/icons/red_folder.gif";}
   if ($ibid=theme_image("forum/icons/folder.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/icons/folder.gif";}
   $linked = "onmouseover=\"this.className='lignb'; this.style.cursor='default';\" "
   ." onmouseout=\"this.className='ligna';\"";

   // preparation de la gestion des folders
   $result = sql_query("SELECT forum_id, COUNT(topic_id) AS total FROM ".$NPDS_Prefix."forumtopics GROUP BY (forum_id)");
   while (list($forumid, $total)=sql_fetch_row($result)) {
      $tab_folder[$forumid][0]=$total; // Topic
   }
   $result = sql_query("SELECT forum_id, COUNT(DISTINCT topicid) AS total FROM ".$NPDS_Prefix."forum_read WHERE uid='$userR[0]' AND topicid>'0' AND status!='0' GROUP BY (forum_id)");
   while (list($forumid, $total)=sql_fetch_row($result)) {
      $tab_folder[$forumid][1]=$total; // Folder
   }
   // préparation de la gestion des abonnements
   $result = sql_query("SELECT forumid FROM ".$NPDS_Prefix."subscribe WHERE uid='$userR[0]'");
   while (list($forumid)=sql_fetch_row($result)) {
      $tab_subscribe[$forumid]=true;
   }
   // preparation du compteur total_post
   $rowQ0=Q_Select ("SELECT forum_id, COUNT(post_aff) AS total FROM ".$NPDS_Prefix."posts GROUP BY forum_id", 600);
   while (list(,$row0)=each($rowQ0)) {
      $tab_total_post[$row0['forum_id']]=$row0['total'];
   }

   if (($subscribe) and ($user)) {
      $colspanX=7;
   } else {
      $colspanX=6;
   }
   $ibid='';
   if ($rowQ1) {
      while (list(,$row) = each($rowQ1)) {
         $title_aff=true;
         $rowQ2=Q_Select ("SELECT * FROM ".$NPDS_Prefix."forums WHERE cat_id = '".$row['cat_id']."' AND SUBSTRING(forum_name,1,3)!='<!>' ORDER BY forum_index,forum_id", 21600);
         if ($rowQ2) {
            while(list(,$myrow) = each($rowQ2)) {
               // Gestion des Forums Cachés aux non-membres
               if (($myrow['forum_type'] != "9") or ($userR)) {
                  // Gestion des Forums réservés à un groupe de membre
                  if (($myrow['forum_type'] == "7") or ($myrow['forum_type'] == "5")){
                     $ok_affich=groupe_forum($myrow['forum_pass'], $tab_groupe);
                     if ( (isset($admin)) and ($adminforum==1) ) $ok_affich=true;// to see when admin mais pas assez precis
                  } else {
                     $ok_affich=true;
                  }
                  if ($ok_affich) {
                     if ($title_aff) {
                        $title = stripslashes($row['cat_title']);
                        if ((file_exists("themes/$theme/html/forum-cat".$row['cat_id'].".html")) OR (file_exists("themes/default/html/forum-cat".$row['cat_id'].".html"))) {
                           $ibid.='
         <tr valign="top">
            <td colspan="'.$colspanX.'" class="lignb"><a href="forum.php?catid='.$row['cat_id'].'">'.$title.'</a></td>
         </tr>';
                        } else {
                           $ibid.='
         <tr valign="top">
            <td colspan="'.$colspanX.'" class="lignb">'.$title.'</td>
         </tr>';
                        }
                        $title_aff=false;
                     }
                     $forum_moderator=explode(" ",get_moderator($myrow['forum_moderator']));
                     $Mmod=false;
                     for ($i = 0; $i < count($forum_moderator); $i++) {
                        if (($userR[1]==$forum_moderator[$i])) {$Mmod=true;}
                     }

                     $last_post = get_last_post($myrow['forum_id'], "forum","infos",$Mmod);
                     $ibid.='
         <tr align="left" valign="top">';
                     if (($tab_folder[$myrow['forum_id']][0]-$tab_folder[$myrow['forum_id']][1])>0)  {
                        $ibid.='
            <td class="lignb" align="center" valign="middle" width="5%"><img src="'.$imgtmpR.'" border="" alt="" /></td>';
                     } else {
                        $ibid.='
            <td class="lignb" align="center" valign="middle" width="5%"><img src="'.$imgtmp.'" border="" alt="" /></td>';
                     }
                     $name = stripslashes($myrow['forum_name']);
                     $redirect=false;
                     if (strstr(strtoupper($name),"<a HREF")) {
                        if ($colspanX==7)
                           $ibid.='
            <td width="40%" class="ligna" '.$linked.'>'.$name;
                        else
                           $ibid.='
            <td width="45%" class="ligna" '.$linked.'>'.$name;
                        $redirect=true;
                     } else {
                        if ($colspanX==7)
                           $ibid.='
            <td width="40%" class="ligna" '.$linked.' onclick=\'window.location.href="viewforum.php?forum='.$myrow['forum_id'].'"\'><a href="viewforum.php?forum='.$myrow['forum_id'].'" >'.$name.'</a>';
                        else
                           $ibid.='
            <td width="45%" class="ligna" '.$linked.' onclick=\'window.location.href="viewforum.php?forum='.$myrow['forum_id'].'"\'><a href="viewforum.php?forum='.$myrow['forum_id'].'" >'.$name.'</a>';
                     }
                     $desc = stripslashes(meta_lang($myrow['forum_desc']));
                     $ibid.='<br />'.$desc.'</td>';

                     // Subscribe
                     if (($subscribe) and ($user)) {
                        if (!$redirect) {
                           if ($tab_subscribe[$myrow['forum_id']]) {
                              $ibid.='
            <td class="lignb" width="5%" align="center" valign="middle"><input type="checkbox" name="Subforumid['.$myrow['forum_id'].']" checked="checked" /></td>';
                           } else {
                              $ibid.='
            <td class="lignb" width="5%" align="center" valign="middle"><input type="checkbox" name="Subforumid['.$myrow['forum_id'].']" /></td>';
                           }
                        } else {
                           $ibid.='
            <td class="lignb" width="5%" align="center" valign="middle">&nbsp;</td>';
                        }
                     }
                     if (!$redirect) {
                        $ibid.='
            <td width="15%" align="center" valign="middle" class="ligna">';
                        if ($myrow['forum_access']=="0" && $myrow['forum_type']=="0")
                        $ibid.=translate("Free for All");
                        if ($myrow['forum_type'] == "1")
                           $ibid.=translate("Private");
                        if ($myrow['forum_type'] == "5")
                           $ibid.="PHP Script + ".translate("Group");
                        if ($myrow['forum_type'] == "6")
                           $ibid.="PHP Script";
                        if ($myrow['forum_type'] == "7")
                           $ibid.=translate("Group");
                        if ($myrow['forum_type'] == "8")
                           $ibid.=translate("Extended Text");
                        if ($myrow['forum_type'] == "9")
                           $ibid.=translate("Hidden");
                        if ($myrow['forum_access']=="1" && $myrow['forum_type'] == "0")
                           $ibid.=translate("Registered User");
                        if ($myrow['forum_access']=="2" && $myrow['forum_type'] == "0")
                           $ibid.=translate("Moderator");
                        if ($myrow['forum_access']=="9")
                           $ibid.=' <strong>'.translate("Closed").'</strong>';
                        $ibid.='</td>
            <td width="10%" align="center" valign="middle" class="lignb">'.$tab_folder[$myrow['forum_id']][0].'</td>
            <td width="10%" align="center" valign="middle" class="ligna">'.$tab_total_post[$myrow['forum_id']].'</td>
            <td width="15%" align="center" valign="middle" class="lignb">'.$last_post.'</td>
         </tr>';
                     } else {
                        $ibid.='
            <td width="15%" align="center" valign="middle" class="ligna">&nbsp;</td>
            <td width="10%" align="center" valign="middle" class="lignb">&nbsp;</td>
            <td width="10%" align="center" valign="middle" class="ligna">&nbsp;</td>
            <td width="15%" align="center" valign="middle" class="ligna">&nbsp;</td>
         </tr>';
                     }
                  }
               }
            }
         }
      }
   }
   return ($ibid);
}

// fonction appelée par le meta-mot forum_subfolder()
function sub_forum_folder($forum) {
   global $user, $NPDS_Prefix;

   if ($user) {
      $userX = base64_decode($user);
      $userR = explode(':', $userX);
   }

   $result = sql_query("SELECT COUNT(topic_id) AS total FROM ".$NPDS_Prefix."forumtopics WHERE forum_id='$forum'");
   list($totalT)=sql_fetch_row($result);

   $result = sql_query("SELECT COUNT(DISTINCT topicid) AS total FROM ".$NPDS_Prefix."forum_read WHERE uid='$userR[0]' AND topicid>'0' AND status!='0' AND forum_id='$forum'");
   list($totalF)=sql_fetch_row($result);

   if ($ibid=theme_image("forum/icons/red_sub_folder.gif")) {$imgtmpR=$ibid;} else {$imgtmpR="images/forum/icons/red_sub_folder.gif";}
   if ($ibid=theme_image("forum/icons/sub_folder.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/icons/sub_folder.gif";}

   if (($totalT-$totalF)>0)  {
      $ibid='<img src="'.$imgtmpR.'" alt="" />';
   } else {
      $ibid='<img src="'.$imgtmp.'" alt="" />';
   }
   return ($ibid);
}
?>

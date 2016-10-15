<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */
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

   if (!$user) {
      Header('Location: user.php');
   } else {
      include('header.php');
      $userX = base64_decode($user);
      $userdata = explode(':', $userX);
      $userdata = get_userdata($userdata[1]);

      settype($start,'integer');
      settype($type,'string');
      if ($type=='outbox') {
         $sql = "SELECT * FROM ".$NPDS_Prefix."priv_msgs WHERE from_userid='".$userdata['uid']."' AND type_msg='1' ORDER BY msg_id DESC LIMIT $start,1";
      } else {
         if ($dossier=='All') {$ibid='';} else {$ibid="AND dossier='$dossier'";}
         if (!$dossier) {$ibid="AND dossier='...'";}
         $sql = "SELECT * FROM ".$NPDS_Prefix."priv_msgs WHERE to_userid='".$userdata['uid']."' AND type_msg='0' $ibid ORDER BY msg_id DESC LIMIT $start,1";
      }
      $resultID = sql_query($sql);
      if (!$resultID) {
         forumerror(0005);
      } else {
         $myrow = sql_fetch_assoc($resultID);
         if ($myrow['read_msg']!='1') {
            $sql = "UPDATE ".$NPDS_Prefix."priv_msgs SET read_msg='1' WHERE msg_id='".$myrow['msg_id']."'";
            $result = sql_query($sql);
            if (!$result) {
               forumerror(0005);
            }
         }
      }
      $myrow['subject']=strip_tags($myrow['subject']);
      echo '
      <h3>';
      if ($dossier=='All') {$Xdossier=translate("All Topics");} else {$Xdossier=StripSlashes($dossier);}
      echo translate("Private Message");
      echo '
      </h3>
      <hr />
      <p class="lead">
      <a href="viewpmsg.php">'.translate("Private Messages").'</a>&nbsp;&raquo;&raquo;&nbsp;'.$Xdossier.'&nbsp;&raquo;&raquo;&nbsp;'.aff_langue($myrow['subject']).'
      </p>
      <div class="card">
         <div class="card-header">';
      if ($type=='outbox') {
         $posterdata = get_userdata_from_id($myrow['to_userid']);
         echo translate("Recipient");
      } else {
         $posterdata = get_userdata_from_id($myrow['from_userid']);
         echo translate("Sender");
         if ($posterdata['uid']==1) echo ' : <span class="text-muted"><strong>'.$sitename.'</strong></span></div>';
      }
      if (!sql_num_rows($resultID)) {
         echo ''.translate("You don't have any Messages.").'</div>';//à traiter
      } else {
         if ($posterdata['uid']<>1) echo ' : <span class="text-muted"><strong>'.$posterdata['uname'].'</strong></span></div>';
echo '<div class="card-block">';

         $posts = $posterdata['posts'];
         if ($posterdata['uid']<>1) echo member_qualif($posterdata['uname'], $posts, $posterdata['rank']);
         echo '<br />';
         if ($smilies) {
            if ($posterdata['user_avatar']!='') {
               if (stristr($posterdata['user_avatar'],"users_private")) {
                  $imgtmp=$posterdata['user_avatar'];
               } else {
                  if ($ibid=theme_image("forum/avatar/".$posterdata['user_avatar'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/avatar/".$posterdata['user_avatar'];}
               }
               echo '<img class=" img-thumbnail img-fluid n-ava" src="'.$imgtmp.'" alt="'.$posterdata['uname'].'" />';
            }
         }

         if ($smilies) {
            if ($myrow['msg_image']!='') {
               if ($ibid=theme_image("forum/subject/".$myrow['msg_image'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/subject/".$myrow['msg_image'];}
               echo '<img class="n-smil" src="'.$imgtmp.'" alt="img_subject" />&nbsp;';
            }
         }
         echo translate("Sent")." : ".$myrow['msg_time']."&nbsp;&nbsp;&nbsp";
         echo "<hr noshade=\"noshade\" class=\"ongl\" /><b>".aff_langue($myrow['subject'])."</b><br />";
         $message = stripslashes($myrow['msg_text']);
         if ($allow_bbcode) {
            $message = smilie($message);
            $message = aff_video_yt($message);
         }
         $message = str_replace("[addsig]", '<br />' . nl2br($posterdata['user_sig']), aff_langue($message));
         echo $message;
         echo '</div></div>';

         if ($posterdata['uid']<>1) {
            echo '<hr />';
            if ($ibid=theme_image("forum/icons/profile.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/icons/profile.gif";}
            echo "&nbsp;&nbsp<a href=\"user.php?op=userinfo&amp;uname=".$posterdata['uname']."\" class=\"noir\"><img src=\"$imgtmp\" border=\"0\" alt=\"\" />".translate("Profile")."</a>";

            if ($posterdata["femail"]!="") {
               if ($ibid=theme_image("forum/icons/email.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/icons/email.gif";}
               echo "&nbsp;&nbsp;<a href=\"mailto:".$posterdata['femail']."\" class=\"noir\"><img src=\"$imgtmp\" border=\"0\" alt=\"\" />".translate("Email")."</a>";
            }

            if ($posterdata["url"]!="") {
               if (strstr("http://", $posterdata["url"]))
                  $posterdata["url"] = "http://" . $posterdata["url"];
               if ($ibid=theme_image("forum/icons/www_icon.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/icons/www_icon.gif";}
               echo "&nbsp;&nbsp;<a href=\"".$posterdata['url']."\" target=\"_blank\" class=\"noir\"><img src=\"$imgtmp\" border=\"0\" alt=\"\" />www</a>";
            }
         }
         $previous = $start-1;
         $next = $start+1;
         if ($type=='outbox') {
            $tmpx='&amp;type=outbox';
         } else {
            $tmpx='&amp;dossier='.urlencode(StripSlashes($dossier));
         }
         echo '
         <ul class="pagination ">';
         if ($type!='outbox') {
            if ($posterdata['uid']<>1)
            echo '
            <li class="page-item"><a class="page-link" href="replypmsg.php?reply=1&amp;msg_id='.$myrow['msg_id'].'"><i class="fa fa-reply fa-lg"></i></a></li>';
         }
         if ($previous >= 0) echo '
            <li class="page-item"><a class="page-link" href="readpmsg.php?start='.$previous.'&amp;total_messages='.$total_messages.$tmpx.'" >'.translate("Previous Messages").'</a></li>';
         else echo '
            <li class="page-item"><a class="page-link disabled" href="#">'.translate("Previous Messages").'</a></li>';
         if ($next < $total_messages) echo '
            <li class="page-item" ><a class="page-link" href="readpmsg.php?start='.$next.'&amp;total_messages='.$total_messages.$tmpx.'" >'.translate("Next Messages").'</a></li>';
         else echo '
            <li class="page-item"><a class="page-link disabled" href="#">'.translate("Next Messages").'</a></li>';
         if ($type!='outbox') {
            echo '
            <li class="page-item" ><a class="page-link " href="replypmsg.php?delete=1&amp;msg_id='.$myrow['msg_id'].'"><i class="fa fa-trash-o fa-lg text-danger"></i></a></li>';
         }
         else{
         echo '
            <li class="page-item"><a class="page-link " href="replypmsg.php?delete=1&amp;msg_id='.$myrow['msg_id'].'&amp;type=outbox"><i class="fa fa-trash-o fa-lg text-danger"></i></a></li>';
         }
         echo '
         </ul>';

         if ($type!='outbox') {
            $sql = "SELECT DISTINCT dossier FROM ".$NPDS_Prefix."priv_msgs WHERE to_userid='".$userdata['uid']."' AND type_msg='0' ORDER BY dossier";
            $result = sql_query($sql);
            echo '
      <form action="replypmsg.php" method="post">
         <div class="form-group row">
            <label class="form-control-label col-sm-3" for="dossier">'.translate("Topic").'</label>
            <div class="col-sm-9">
               <select class="custom-select form-control" name="dossier">';
            while (list($dossier)=sql_fetch_row($result)) {
               echo '
                  <option value="'.$dossier.'">'.$dossier.'</option>';
            }
            echo '
               </select>
            </div>
         </div>
         <div class="form-group row">
            <label class="form-control-label col-sm-3" for="dossier"></label>
            <div class="col-sm-9">
               <input type="texte" class="form-control" name="nouveau_dossier" value="" size="24" />
            </div>
         </div>
         <div class="form-group row">
            <div class="col-sm-12">
               <input type="hidden" name="msg_id" value="'.$myrow['msg_id'].'" />
               <input type="hidden" name="classement" value="1" />
               <input type="submit" class="btn btn-primary" name="classe" value="OK" />
            </div>
         </div>
      </form>';
         }
      }
      include('footer.php');
   }
?>

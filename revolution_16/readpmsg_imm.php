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

include('functions.php');
if ($SuperCache) {
   $cache_obj = new cacheManager();
} else {
   $cache_obj = new SuperCacheEmpty();
}
include('auth.php');

function cache_ctrl() {
   global $cache_verif;
   if ($cache_verif) {
      header("Expires: Sun, 01 Jul 1990 00:00:00 GMT");
      header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
      header("Cache-Control: no-cache, must revalidate");
      header("Pragma: no-cache");
   }
}

function show_imm($op) {
   global $smilies, $user, $allow_bbcode, $language, $Default_Theme, $theme, $site_font, $short_user, $Titlesitename;
   global $NPDS_Prefix;
   if (!$user) {
      Header("Location: user.php");
   } else {
      $userX = base64_decode($user);
      $userdata = explode(':', $userX);
      if ($userdata[9]!='') {
         if (!$file=@opendir("themes/$userdata[9]")) {
            $theme=$Default_Theme;
         } else {
            $theme=$userdata[9];
         }
      } else {
         $theme=$Default_Theme;
      }
      include("themes/$theme/theme.php");
      $userdata = get_userdata($userdata[1]);
      if ($op!='new_msg') {
         $sql = "SELECT * FROM ".$NPDS_Prefix."priv_msgs WHERE to_userid = '".$userdata['uid']."' AND read_msg='1' AND type_msg='0' AND dossier='...' ORDER BY msg_id DESC";
      } else {
         $sql = "SELECT * FROM ".$NPDS_Prefix."priv_msgs WHERE to_userid = '".$userdata['uid']."' AND read_msg='0' AND type_msg='0' ORDER BY msg_id ASC";
      }
      $result = sql_query($sql);
      $pasfin=false;
      while ($myrow = sql_fetch_assoc($result)) {
         if ($pasfin==false) {
            $pasfin=true;
            cache_ctrl();
            include("meta/meta.php");
            include("modules/include/header_head.inc");
            echo import_css($theme, $language, $site_font, '','');
            echo '
      </head>
      <body>
         <div class="card card-body">';
         }
         $posterdata = get_userdata_from_id($myrow['from_userid']);
         echo '
            <div class="card mb-3">
               <div class="card-body">
               <h3>'.translate("Private Message").' '.translate("From");
         if ($posterdata['uid']==1) {
            global $sitename;
            echo ' <span class="text-muted">'.$sitename.'</span></h3>';
         }
         if ($posterdata['uid']<>1) echo ' <span class="text-muted">'.$posterdata['uname'].'</span></h3>';

         $myrow['subject']=strip_tags($myrow['subject']);

         $posts = $posterdata['posts'];
         if ($posterdata['uid']<>1) echo member_qualif($posterdata['uname'], $posts, $posterdata['rank']);
         echo '<br /><br />';
         if ($smilies) {
            if ($posterdata['user_avatar'] != '') {
               if (stristr($posterdata['user_avatar'],"users_private")) {
                  $imgtmp=$posterdata['user_avatar'];
               } else {
                  if ($ibid=theme_image("forum/avatar/".$posterdata['user_avatar'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/avatar/".$posterdata['user_avatar'];}
               }
               echo '<img class="btn-secondary img-thumbnail img-fluid n-ava" src="'.$imgtmp.'" alt="'.$posterdata['uname'].'" />';
            }
         }

         if ($smilies) {
            if ($myrow['msg_image']!='') {
               if ($ibid=theme_image("forum/subject/".$myrow['msg_image'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/subject/".$myrow['msg_image'];}
               echo '<img class="n-smil" src="'.$imgtmp.'"  alt="" />&nbsp;';
            }
         }
         echo translate("Sent").' : '.$myrow['msg_time'].'&nbsp;&nbsp;&nbsp';
         echo '<h4>'.aff_langue($myrow['subject']).'</h4>';
         $message = stripslashes($myrow['msg_text']);
         if ($allow_bbcode) {
            $message = smilie($message);
            $message = aff_video_yt($message);
         }
         $message = str_replace("[addsig]", "<br /><br />" . nl2br($posterdata['user_sig']), aff_langue($message));
         echo $message.'<br />';


         if ($posterdata['uid']<>1) {
/*
            echo '<hr />';
            if ($ibid=theme_image("forum/icons/profile.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/icons/profile.gif";}
            echo "&nbsp;&nbsp<a href=\"user.php?op=userinfo&amp;uname=".$posterdata['uname']."\" class=\"noir\" target=\"_blank\"><img src=\"$imgtmp\" border=\"0\" alt=\"\" />".translate("Profile")."</a>";

            if ($posterdata["femail"]!="") {
               if ($ibid=theme_image("forum/icons/email.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/icons/email.gif";}
               echo "&nbsp;&nbsp;<a href=\"mailto:".$posterdata['femail']."\" class=\"noir\" target=\"_blank\"><img src=\"$imgtmp\" border=\"0\" alt=\"\" />".translate("Email")."</a>";
            }

            if ($posterdata['url']!="") {
               if (strstr("http://", $posterdata['url']))
                  $posterdata['url'] = "http://" . $posterdata['url'];
               if ($ibid=theme_image("forum/icons/www_icon.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/icons/www_icon.gif";}
               echo "&nbsp;&nbsp;<a href=\"".$posterdata['url']."\" class=\"noir\" target=\"_blank\"><img src=\"$imgtmp\" border=\"0\" alt=\"\" />www</a>";
            }
*/

            if (!$short_user) {
            
/*

*/
               
            }
         }
         echo '
         </div>
         <div class="card-footer">';
         if ($posterdata['uid']<>1) {
            echo '
         <a class="mr-3" href="readpmsg_imm.php?op=read_msg&amp;msg_id='.$myrow['msg_id'].'&amp;op_orig='.$op.'&amp;sub_op=reply" title="'.translate("Reply").'" data-toggle="tooltip"><i class="fa fa-reply fa-lg"></i></a>';
         }
         echo '
         <a class="mr-3" href="readpmsg_imm.php?op=read_msg&amp;msg_id='.$myrow['msg_id'].'&amp;op_orig='.$op.'&amp;sub_op=read" title="'.translate("Read").'" data-toggle="tooltip"><i class="fa fa-check-square-o fa-lg"></i></a>
         <a class="mr-3" href="readpmsg_imm.php?op=delete&amp;msg_id='.$myrow['msg_id'].'&amp;op_orig='.$op.'" title="'.translate("Delete").'" data-toggle="tooltip"><i class="fa fa-trash-o fa-lg text-danger"></i></a>
         </div>
         </div>';

      }
      if ($pasfin!=true) {
         cache_ctrl();
         echo '<body onload="self.close();">';
      }
   }
   echo '
         </div>
      </body>
   </html>';
}

function sup_imm($msg_id) {
   global $cookie, $NPDS_Prefix;
   if (!$cookie) {
      Header("Location: user.php");
   } else {
      $sql="DELETE FROM ".$NPDS_Prefix."priv_msgs WHERE msg_id='$msg_id' AND to_userid='$cookie[0]'";
      if (!sql_query($sql))
         forumerror('0021');
   }
}

function read_imm($msg_id, $sub_op) {
   global $cookie, $NPDS_Prefix;
   if (!$cookie) {
      Header("Location: user.php");
   } else {
      $sql="UPDATE ".$NPDS_Prefix."priv_msgs SET read_msg='1' WHERE msg_id='$msg_id' AND to_userid='$cookie[0]'";
      if (!sql_query($sql))
         forumerror('0021');
      if ($sub_op=='reply') {
         echo "<script type=\"text/javascript\">
               //<![CDATA[
               window.location='replypmsg.php?reply=1&msg_id=$msg_id&userid=$cookie[0]&full_interface=short';
               //]]>
               </script>";
               die();
      }
      echo '<script type="text/javascript">
            //<![CDATA[
            window.location="readpmsg_imm.php?op=new_msg";
            //]]>
            </script>';
      die();
   }
}

settype($op,'string');
switch ($op) {
   case 'new_msg':
      show_imm($op);
   break;
   case 'read_msg':
      read_imm($msg_id, $sub_op);
   break;
   case 'delete':
      sup_imm($msg_id);
      show_imm($op_orig);
   break;
   default:
      show_imm($op);
   break;
}
?>

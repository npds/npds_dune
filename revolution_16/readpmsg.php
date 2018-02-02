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
if (!function_exists("Mysql_Connexion"))
   include ("mainfile.php");

include('functions.php');
if ($SuperCache)
   $cache_obj = new cacheManager();
else
   $cache_obj = new SuperCacheEmpty();
include('auth.php');

   if (!$user)
      Header('Location: user.php');
   else {
      include('header.php');
      $userX = base64_decode($user);
      $userdata = explode(':', $userX);
      $userdata = get_userdata($userdata[1]);

      settype($start,'integer');
      settype($type,'string');
      settype($dossier,'string');

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
      <div class="card mb-3">
         <div class="card-header">';
      if ($type=='outbox') {
         $posterdata = get_userdata_from_id($myrow['to_userid']);
//         echo translate("Recipient");
      } else {
         $posterdata = get_userdata_from_id($myrow['from_userid']);
//         echo translate("Sender");
//         if ($posterdata['uid']==1) echo ' : <span class="text-muted"><strong>'.$sitename.'</strong></span>';
      }
      $posts = $posterdata['posts'];
      
      $socialnetworks=array(); $posterdata_extend=array();$res_id=array();$my_rs='';
      if (!$short_user) {
         $posterdata_extend = get_userdata_extend_from_id($posterdata['uid']);
         include('modules/reseaux-sociaux/reseaux-sociaux.conf.php');
         if ($posterdata_extend['M2']!='') {
            $socialnetworks= explode(';',$posterdata_extend['M2']);
            foreach ($socialnetworks as $socialnetwork) {
               $res_id[] = explode('|',$socialnetwork);
            }
            sort($res_id);
            sort($rs);
            foreach ($rs as $v1) {
               foreach($res_id as $y1) {
                  $k = array_search( $y1[0],$v1);
                  if (false !== $k) {
                     $my_rs.='<a class="mr-3" href="';
                     if($v1[2]=='skype') $my_rs.= $v1[1].$y1[1].'?chat'; else $my_rs.= $v1[1].$y1[1];
                     $my_rs.= '" target="_blank"><i class="fa fa-'.$v1[2].' fa-2x text-primary"></i></a> ';
                     break;
                  } 
                  else $my_rs.='';
               }
            }
            $my_rsos[]=$my_rs;
         }
         else $my_rsos[]='';
      }

      if ($posterdata['uid']<>1) {
         $useroutils = '';
         $useroutils .= '<hr />';
         if ($posterdata['uid']!= 1 and $posterdata['uid']!='')
            $useroutils .= '<a class="list-group-item text-primary" href="user.php?op=userinfo&amp;uname='.$posterdata['uname'].'" target="_blank" title="'.translate("Profile").'" data-toggle="tooltip"><i class="fa fa-2x fa-user"></i><span class="ml-3 d-none d-md-inline">'.translate("Profile").'</span></a>';
         if ($posterdata['uid']!= 1)
            $useroutils .= '<a class="list-group-item text-primary" href="powerpack.php?op=instant_message&amp;to_userid='.$posterdata["uname"].'" title="'.translate("Send internal Message").'" data-toggle="tooltip"><i class="fa fa-2x fa-envelope-o"></i><span class="ml-3 d-none d-md-inline">'.translate("Message").'</span></a>';
         if ($posterdata['femail']!='')
            $useroutils .= '<a class="list-group-item text-primary" href="mailto:'.anti_spam($posterdata['femail'],1).'" target="_blank" title="'.translate("Email").'" data-toggle="tooltip"><i class="fa fa-at fa-2x"></i><span class="ml-3 d-none d-md-inline">'.translate("Email").'</span></a>';
         if ($posterdata['url']!='')
            $useroutils .= '<a class="list-group-item text-primary" href="'.$posterdata['url'].'" target="_blank" title="'.translate("Visit this Website").'" data-toggle="tooltip"><i class="fa fa-2x fa-external-link"></i><span class="ml-3 d-none d-md-inline">'.translate("Visit this Website").'</span></a>';
         if ($posterdata['mns'])
             $useroutils .= '<a class="list-group-item text-primary" href="minisite.php?op='.$posterdata['uname'].'" target="_blank" target="_blank" title="'.translate("Visit the Mini Web Site !").'" data-toggle="tooltip"><i class="fa fa-2x fa-desktop"></i><span class="ml-3 d-none d-md-inline">'.translate("Visit the Mini Web Site !").'</span></a>';
      }

      if (!sql_num_rows($resultID)) {
         echo ''.translate("You don't have any Messages.").'</div>';//à traiter
      } else {
         if ($smilies) {
         if ($posterdata['user_avatar'] != '') {
            if (stristr($posterdata['user_avatar'],"users_private")) {
             $imgtmp=$posterdata['user_avatar'];
         } else {
             if ($ibid=theme_image("forum/avatar/".$posterdata['user_avatar'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/avatar/".$posterdata['user_avatar'];}
         }
          echo '
          <a style="position:absolute; top:1rem;" tabindex="0" data-toggle="popover" data-trigger="focus" data-html="true" data-title="'.$posterdata['uname'].'" data-content=\''.member_qualif($posterdata['uname'], $posts,$posterdata['rank']).'<br /><div class="list-group">'.$useroutils.'</div><hr />'.$my_rsos[0].'\'><img class=" btn-secondary img-thumbnail img-fluid n-ava" src="'.$imgtmp.'" alt="'.$posterdata['uname'].'" /></a>';
         }
      }
         if ($posterdata['uid']<>1) 

   echo '&nbsp;<span style="position:absolute; left:6em;" class="text-muted"><strong>'.$posterdata['uname'].'</strong></span>';
   echo '<span class="float-right">';
      if ($smilies) {
         if ($myrow['msg_image']!='') {
            if ($ibid=theme_image("forum/subject/".$myrow['msg_image'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/subject/".$myrow['msg_image'];}
            echo '<img class="n-smil" src="'.$imgtmp.'" alt="icon_post" />';
            } else {
               if ($ibid=theme_image("forum/icons/posticon.gif")) {$imgtmpPI=$ibid;} else {$imgtmpPI="images/forum/icons/posticon.gif";}
               echo '<img class="n-smil" src="'.$imgtmpPI.'" alt="icon_post" />';
            }
         }
      echo '</span>
            </div>
            <div class="card-body">
               <div class="card-text pt-2">
                  <div class="text-right small">'.translate("Sent").' : '.$myrow['msg_time'].'</div>
                  <hr /><strong>'.aff_langue($myrow['subject']).'</strong><br />';
         $message = stripslashes($myrow['msg_text']);
         if ($allow_bbcode) {
            $message = smilie($message);
            $message = aff_video_yt($message);
         }
         $message = str_replace('[addsig]', '<br />' . nl2br($posterdata['user_sig']), aff_langue($message));
         echo $message;
         echo '
               </div>
            </div>
         </div>';

         $previous = $start-1;
         $next = $start+1;
         if ($type=='outbox') {
            $tmpx='&amp;type=outbox';
         } else {
            $tmpx='&amp;dossier='.urlencode(StripSlashes($dossier));
         }
         echo '
         <ul class="pagination d-flex justify-content-center">';
         if ($type!='outbox') {
            if ($posterdata['uid']<>1)
            echo '
            <li class="page-item"><a class="page-link" href="replypmsg.php?reply=1&amp;msg_id='.$myrow['msg_id'].'"><i class="fa fa-reply fa-lg"></i></a></li>';
         }
         if ($previous >= 0) echo '
            <li class="page-item">
               <a class="page-link" href="readpmsg.php?start='.$previous.'&amp;total_messages='.$total_messages.$tmpx.'" >
                  <span class="d-none d-lg-inline">'.translate("Previous Messages").'</span>
                  <span class="d-lg-none" title="'.translate("Previous Messages").'" data-toggle="tooltip"><i class="fa fa-angle-double-left fa-lg"></i></span>
               </a>
            </li>';
         else echo '
            <li class="page-item">
               <a class="page-link disabled" href="#">
                  <span class="d-none d-lg-inline">'.translate("Previous Messages").'</span>
                  <span class="d-lg-none" title="'.translate("Previous Messages").'" data-toggle="tooltip"><i class="fa fa-angle-double-left fa-lg"></i></span>
               </a>
            </li>';
         if ($next < $total_messages) echo '
            <li class="page-item" >
               <a class="page-link" href="readpmsg.php?start='.$next.'&amp;total_messages='.$total_messages.$tmpx.'" >
                  <span class="d-none d-lg-inline">'.translate("Next Messages").'</span>
                  <span class="d-lg-none" title="'.translate("Next Messages").'" data-toggle="tooltip"><i class="fa fa-angle-double-right fa-lg"></i></span>
               </a>
            </li>';
         else echo '
            <li class="page-item">
               <a class="page-link disabled" href="#">
                  <span class="d-none d-lg-inline">'.translate("Next Messages").'</span>
                  <span class="d-lg-none" title="'.translate("Next Messages").'" data-toggle="tooltip"><i class="fa fa-angle-double-right fa-lg"></i></span>
               </a>
            </li>';
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
      <div class="card card-body">
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
            <div class="col-sm-9 ml-sm-auto">
               <input type="hidden" name="msg_id" value="'.$myrow['msg_id'].'" />
               <input type="hidden" name="classement" value="1" />
               <button type="submit" class="btn btn-primary" name="classe">OK</button>
            </div>
         </div>
      </form>
      </div>';
         }
      }
      include('footer.php');
   }
?>

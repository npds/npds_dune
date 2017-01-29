<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/* Based on Parts of phpBB                                              */
/*                                                                      */
/* NPDS Copyright (c) 2002-2017 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion")) {
   die();
}
include_once('functions.php');
include_once('auth.php');

settype($forum,'integer');
if ($forum>=0) die();

// gestion des params du 'forum' : type, accès, modérateur ...
$forum_name = 'comments';
$forum_type=0;
$allow_to_post=false;
if ($anonpost)
   $forum_access=0;
else
   $forum_access=1;

if (($moderate==1) and $admin)
   $Mmod=true;
elseif ($moderate==2) {
   $userX=base64_decode($user);
   $userdata=explode(':', $userX);
   $result=sql_query("SELECT level FROM ".$NPDS_Prefix."users_status WHERE uid='".$userdata[0]."'");
   list($level)=sql_fetch_row($result);
   if ($level>=2)
      $Mmod=true;
} else
   $Mmod=false;

function Caff_pub($topic, $file_name, $archive) {
   global $language;
   $tmp='<a href="modules.php?ModPath=comments&amp;ModStart=reply&amp;topic='.$topic.'&amp;file_name='.$file_name.'&amp;archive='.$archive.'" class="btn btn-primary btn-sm" role="button">'.translate("Comment").'</a>';
   return ($tmp);
}
   if ($forum_access==0) {
      $allow_to_post=true;
   } else {
      if ($user) {
         $allow_to_post=true;
      }
   }
   global $anonymous;
   settype($archive,'integer');
   if ($allow_to_post) {
      echo '<nav class="text-right my-2">'.Caff_pub($topic,$file_name, $archive).'</nav>';
   }

   // Pagination
   settype($C_start,'integer');
   settype($comments_per_page,'integer');
   $result=sql_query ("SELECT COUNT(*) AS total FROM ".$NPDS_Prefix."posts WHERE forum_id='$forum' AND topic_id='$topic' AND post_aff='1'");
   list($total)=sql_fetch_row($result);
   if ($total > $comments_per_page) {
      $times = 1; $current_page=0;
      echo '
      <div id="co-pagihaute" class="my-2">
         <ul class="pagination pagination-sm">
            <li class="page-item">
               <a class="page-link" href="#co-pagibasse"><i class="fa fa-angle-double-down" title="'.translate("Bottom page").'" data-toggle="tooltip"></i></a>
            </li>
            <li class="page-item disabled">
               <a class="page-link" href="#" aria-label="'.translate("Comments").'">'.$total.' '.translate("Comments").'</a>
            </li>
            <li class="page-item disabled">
               <a class="page-link"href="#" aria-label="'.translate("pages").'">'.$pages.' '.translate("pages").'</a>
            </li>';
       $pages_rapide='';
      for ($x = 0; $x < $total; $x += $comments_per_page) {
         if (($x>=$C_start) and ($current_page==0)) {
            $current_page=$times;
         }
         if ($current_page!=$times)
            $pages_rapide.='
             <li class="page-item"><a class="page-link" href="'.rawurldecode($url_ret).'&amp;C_start='.$x.'">'.$times.'</a></li>';
         else
            $pages_rapide.='
             <li class="page-item active"><a class="page-link" href="#">'.$times.'</a></li>';
         $times++;
      }
      echo $pages_rapide.'
         </ul>
      </div>';
   }

   if ($Mmod) {
      $post_aff=' ';
   } else {
      $post_aff=" AND post_aff='1' ";
   }
   $sql = "SELECT * FROM ".$NPDS_Prefix."posts WHERE forum_id='$forum' AND topic_id = '$topic'".$post_aff."ORDER BY post_id LIMIT $C_start, $comments_per_page";
   if (!$result = sql_query($sql)) forumerror(0001);
   $mycount = sql_num_rows($result);
   $myrow = sql_fetch_assoc($result);
   $count = 0;

if ($mycount) {

   if ($ibid=theme_image("forum/icons/posticon.gif")) {$imgtmpPI=$ibid;} else {$imgtmpPI="images/forum/icons/posticon.gif";}
      $my_rsos=array();
   do {
      $posterdata = get_userdata_from_id($myrow['poster_id']);
      $posts = $posterdata['posts'];

      $socialnetworks=array(); $posterdata_extend=array();$res_id=array();$my_rs='';
      if (!$short_user) {
         $posterdata_extend = get_userdata_extend_from_id($myrow['poster_id']);
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
                     $my_rs.='<a href="'.$v1[1].$y1[1].'" target="_blank"><i class="fa fa-'.$v1[2].' fa-2x text-primary"></i></a>&nbsp;';
                     break;
                  } 
                  else $my_rs.='';
               }
            }
            $my_rsos[]=$my_rs;
         }
         else $my_rsos[]='';
      }

      $useroutils = '';
      if ($posterdata['uid']!= 1 and $posterdata['uid']!='')
         $useroutils .= '<hr />';
      if ($posterdata['uid']!= 1 and $posterdata['uid']!='') {
         $useroutils .= '<a class="list-group-item text-primary" href="user.php?op=userinfo&amp;uname='.$posterdata['uname'].'" target="_blank" title="'.translate("Profile").'" data-toggle="tooltip"><i class="fa fa-2x fa-user"></i><span class="ml-3 hidden-sm-down">'.translate("Profile").'</span></a>';
      }
      if ($posterdata['uid']!= 1 and $posterdata['uid']!='') {
         $useroutils .= '<a class="list-group-item text-primary" href="powerpack.php?op=instant_message&amp;to_userid='.$posterdata["uname"].'" title="'.translate("Send internal Message").'" data-toggle="tooltip"><i class="fa fa-2x fa-envelope-o"></i><span class="ml-3 hidden-sm-down">'.translate("Send internal Message").'</span></a>';
      }
      if ($posterdata['femail']!='') {
         $useroutils .= '<a class="list-group-item text-primary" href="mailto:'.anti_spam($posterdata['femail'],1).'" target="_blank" title="'.translate("Email").'" data-toggle="tooltip"><i class="fa fa-at fa-2x"></i><span class="ml-3 hidden-sm-down">'.translate("Email").'</span></a>';
      }
      if ($posterdata['url']!='') {
         if (strstr('http://', $posterdata['url']))
            $posterdata['url'] = "http://" . $posterdata['url'];
         $useroutils .= '<a class="list-group-item text-primary" href="'.$posterdata['url'].'" target="_blank" title="'.translate("Visit this Website").'" data-toggle="tooltip"><i class="fa fa-2x fa-external-link"></i><span class="ml-3 hidden-sm-down">'.translate("Visit this Website").'</span></a>';
      }
      if ($posterdata['mns']) {
          $useroutils .= '<a class="list-group-item text-primary" href="minisite.php?op='.$posterdata['uname'].'" target="_blank" target="_blank" title="'.translate("Visit the Mini Web Site !").'" data-toggle="tooltip"><i class="fa fa-2x fa-desktop"></i><span class="ml-3 hidden-sm-down">'.translate("Visit the Mini Web Site !").'</span></a>';
      }
      echo '
      <div class="row">
         <a name="'.$forum.$topic.$myrow['post_id'].'"></a>';
      if (($count+2)==$mycount) echo '<a name="last-post"></a>';
      echo '
         <div class="col-12 mb-3">
            <div class="card">
               <div class="card-header">';
      global $smilies;
      if ($smilies) {
          if ($posterdata['user_avatar'] != '') {
             if (stristr($posterdata['user_avatar'],"users_private")) {
                $imgtmp=$posterdata['user_avatar'];
             } else {
                if ($ibid=theme_image("forum/avatar/".$posterdata['user_avatar'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/avatar/".$posterdata['user_avatar'];}
             }
              echo '
             <a style="position:absolute; top:1rem;" tabindex="0" data-toggle="popover" data-trigger="focus" data-html="true" data-title="'.$posterdata['uname'].'" data-content=\''.member_qualif($posterdata['uname'], $posts,$posterdata['rank']).'<br /><div class="list-group">'.$useroutils.'</div><hr />'.$my_rsos[$count].'\'><img class=" btn-secondary img-thumbnail img-fluid n-ava" src="'.$imgtmp.'" alt="'.$posterdata['uname'].'" /></a>';
          }
      }
             echo '&nbsp;<span style="position:absolute; left:6em;" class="text-muted"><strong>'.$posterdata['uname'].'</strong></span>';
      
      echo '
               </div>';
      $message=stripslashes($myrow['post_text']);
      $date_post=convertdateTOtimestamp($myrow['post_time']);
      echo '
               <div class="card-block">
                  <div class="card-text pt-3">';
      if ($allow_bbcode) {
         $message = smilie($message);
         $message = aff_video_yt($message);
      }

//      $message=split_string_without_space($message, 80);
      $message=str_replace("[addsig]", '<div class="n-signature">'.nl2br($posterdata['user_sig']).'</div>', $message);
         echo '
                     <div class="card-text fo-post-mes">';
         echo nl2br($message);
         echo '
                     </div>
                  </div>
               </div>
               <div class="card-footer">
                  <div class="row">
                     <div class=" col-sm-6 text-muted small">'.post_convertdate($date_post).'</div>
                     <div class=" col-sm-6 text-right">';
      if ($allow_to_post) {
         echo '<a class="mr-3" href="modules.php?ModPath=comments&amp;ModStart=reply&amp;topic='.$topic.'&amp;file_name='.$file_name.'&amp;archive='.$archive.'" title="'.translate("Comment").'" data-toggle="tooltip"><i class="fa fa-comment-o fa-lg"></i></a>';
      }
      if ($allow_to_post and $posterdata['uid']!='') {
         if ($formulaire=='') 
            echo '<a class="mr-3" href="modules.php?ModPath=comments&amp;ModStart=reply&amp;topic='.$topic.'&amp;file_name='.$file_name.'&amp;post='.$myrow['post_id'].'&amp;citation=1&amp;archive='.$archive.'" title="'.translate("Quote").'" data-toggle="tooltip" ><i class="fa fa-lg fa-quote-left"></i></a>';
      }
      if ($Mmod) {
         echo '<a class="mr-3" href="modules.php?ModPath=comments&amp;ModStart=admin&amp;mode=viewip&amp;topic='.$topic.'&amp;post='.$myrow['post_id'].'&amp;file_name='.$file_name.'&amp;archive='.$archive.'" title="IP" data-toggle="tooltip"><i class="fa fa-lg fa-laptop"></i></a>';
         if (!$myrow['post_aff']) {
            echo '<a class="mr-3" href="modules.php?ModPath=comments&amp;ModStart=admin&amp;mode=aff&amp;topic='.$topic.'&amp;post='.$myrow['post_id'].'&amp;ordre=1&amp;file_name='.$file_name.'&amp;archive='.$archive.'" title="'.translate("Show this comment").'" data-toggle="tooltip" data-placement="left"><i class="fa fa-lg fa-eye text-danger"></i></a>';
         } else {
            echo '<a class="mr-3" href="modules.php?ModPath=comments&amp;ModStart=admin&amp;mode=aff&amp;topic='.$topic.'&amp;post='.$myrow['post_id'].'&amp;ordre=0&amp;file_name='.$file_name.'&amp;archive='.$archive.'" title="'.translate("Hide this comment").'" data-toggle="tooltip" data-placement="left"><i class="fa fa-lg fa-eye-slash"></i></a>';
         }
      }
      echo '
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>';
      $count++;
    } while($myrow = sql_fetch_assoc($result));
    unset ($tmp_imp); // not sure we need ?

   if ($total > $comments_per_page) {
      echo '
   <nav id="co-pagibasse">
      <ul class="pagination pagination-sm d-flex flex-wrap  my-2">
         <li class="page-item">
            <a class="page-link" href="#co-pagihaute"><i class="fa fa-angle-double-up" title="'.translate("Back to Top").'" data-toggle="tooltip"></i></a>
         </li>
         <li class="page-item disabled">
            <a class="page-link" href="#">'.translate("Goto Page").'</a>
         </li>';
      echo $pages_rapide.'
      </ul>
   </nav>';
   }

   if ($allow_to_post) {
      echo '<nav class="text-right mb-2">'.Caff_pub($topic,$file_name, $archive).'</nav>';
   }
   echo '
   <br />
   <blockquote class="blockquote">'.translate("The comments are owned by the poster. We aren't responsible for their content.").'</blockquote>';

   if ($Mmod) {
       echo '
   <nav class="text-center">
      <ul class="pagination pagination-sm">
         <li class="page-item disabled">
            <a class="page-link" href="#"><i class="fa fa-cogs fa-lg"></i>&nbsp;'.translate("Administration Tools").'</a>
         </li>
         <li class="page-item">
            <a class="page-link text-danger" href="modules.php?ModPath=comments&amp;ModStart=admin&amp;mode=del&amp;topic='.$topic.'&amp;file_name='.$file_name.'&amp;archive='.$archive.' " title="'.translate("Delete comments.").'" data-toggle="tooltip"><i class="fa fa-remove fa-lg" ></i></a>
         </li>
      </ul>
   </nav>';
   }
}
?>
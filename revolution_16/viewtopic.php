<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
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
global $NPDS_Prefix, $admin, $adminforum;

if ($allow_upload_forum) {
   include("modules/upload/upload_forum.php");
}

//==> droits des admin sur les forums (superadmin et admin avec droit gestion forum)
   $adminforum=false;
   if ($admin) {
      $adminforum=0;
      $adminX = base64_decode($admin);
      $adminR = explode(':', $adminX);
      $Q = sql_fetch_assoc(sql_query("SELECT * FROM ".$NPDS_Prefix."authors WHERE aid='$adminR[0]' LIMIT 1"));
      if ($Q['radminsuper']==1) {$adminforum=1;} else {
         $R = sql_query("SELECT fnom, fid, radminsuper FROM ".$NPDS_Prefix."authors a LEFT JOIN ".$NPDS_Prefix."droits d ON a.aid = d.d_aut_aid LEFT JOIN ".$NPDS_Prefix."fonctions f ON d.d_fon_fid = f.fid WHERE a.aid='$adminR[0]' AND f.fid BETWEEN 13 AND 15");
         if (sql_num_rows($R) >=1) $adminforum=1;
      }
   }
//<== droits des admin sur les forums (superadmin et admin avec droit gestion forum)


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
   header("Location: forum.php");
}
if (($forum_type == 5) or ($forum_type == 7)) {
   $ok_affiche=false;
   $tab_groupe=valid_group($user);
   $ok_affiche=groupe_forum($myrow['forum_pass'], $tab_groupe);
   
   //:: ici 
   if ( (!$ok_affiche) and ($adminforum==0) ) {
      header("location: forum.php");
   }
}
if (($forum_type==9) and (!$user)) {
   header("location: forum.php");
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
   $times = 0; $current_page=0;
   for ($x = 0; $x < $total; $x += $posts_per_page) {
       if (($x>=$start) and ($current_page==0)) {
          $current_page=$times+1;
       }
       $times++;
   }
   $pages=$times;
}

if (!$result = sql_query($sql))
   forumerror('0001');
$myrow = sql_fetch_assoc($result);
$topic_subject = stripslashes($myrow['topic_title']);
$lock_state = $myrow['topic_status'];
$original_poster=$myrow['topic_poster'];

function aff_pub($lock_state, $topic, $forum,$mod) {
   global $language;
   if ($lock_state==0) {
   echo '<a class="" href="newtopic.php?forum='.$forum.'" title="'.translate("New Topic").'" data-toggle="tooltip" ><i class="fa fa-plus-square "></i></a>&nbsp;';
   }
}
function aff_pub_in($lock_state, $topic, $forum,$mod) {
   global $language;
   if ($lock_state==0) {
   echo '
   <a class="mr-1" href="reply.php?topic='.$topic.'&amp;forum='.$forum.'" title="'.translate("Reply").'" data-toggle="tooltip"><i class="fa fa-reply"></i></a>';
   }
}

$total_contributeurs = get_total_contributeurs($forum, $topic);
$contributeurs = get_contributeurs($forum, $topic);
$contributeurs=explode(' ',$contributeurs);

$title=$forum_name; $post=$topic_subject;
include('header.php');
   echo '
   <a name="topofpage"></a>
   <p class="lead">
      <a href="forum.php">'.translate("Forum Index").'</a>&nbsp;&raquo;&raquo;&nbsp;
      <a href="viewforum.php?forum='.$forum.'">'.stripslashes($forum_name).'</a>&nbsp;&raquo;&raquo;&nbsp;'.$topic_subject.'
   </p>';
   echo '
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
         aff_pub($lock_state,$topic,$forum,$mod);
      }
   }

   echo $topic_subject.'<span class="text-muted">&nbsp;#'.$topic.'</span>';
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
         aff_pub_in($lock_state,$topic,$forum,$mod);
      }
   }
   echo '</h3>';
   echo '
      <div class="card">
         <div class="card-block-small">
   '.translate("Contributors").' : '.$total_contributeurs;

   for ($i = 0; $i < count($contributeurs); $i++) {
      $contri = get_userdata_from_id($contributeurs[$i]);
      if ($contri['user_avatar'] != '') {
         if (stristr($contri['user_avatar'],"users_private")) {
            $imgtmp=$contri['user_avatar'];
         } else {
            if ($ibid=theme_image("forum/avatar/".$contri['user_avatar'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/avatar/".$contri['user_avatar'];}
         }
      }
      echo '<img class="img-thumbnail img-fluid n-ava-small" src="'.$imgtmp.'" alt="'.$contri['uname'].'" title="'.$contri['uname'].'" data-toggle="tooltip" />';
   }
      echo '<br />'.translate("Moderated By: ");
   for ($i = 0; $i < count($moderator); $i++) {
      $modera = get_userdata($moderator[$i]);
      if ($modera['user_avatar'] != '') {
         if (stristr($modera['user_avatar'],"users_private")) {
          $imgtmp=$modera['user_avatar'];
         } else {
          if ($ibid=theme_image("forum/avatar/".$modera['user_avatar'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/avatar/".$modera['user_avatar'];}
         }
      }
      echo '<a href="user.php?op=userinfo&amp;uname='.$moderator[$i].'"><img class=" img-thumbnail img-fluid n-ava-small" src="'.$imgtmp.'" alt="'.$modera['uname'].'" title="'.translate("Moderated By: ").' '.$modera['uname'].'" data-toggle="tooltip" /></a>';
   }
   
   echo '
         </div>
      </div>';

   if ($total > $posts_per_page) {
      $times = 1;
      echo '
      <div id="fo-postpagi">
         <ul class="pagination pagination-sm">
            <li class="page-item">
               <a class="page-link" href="#botofpage"><i class="fa fa-angle-double-down" title="'.translate("Bottom page").'" data-toggle="tooltip"></i></a>
            </li>
            <li class="page-item disabled">
               <a class="page-link" href="#" aria-label="'.translate("Posts").'">'.$total.' '.translate("Posts").'</a>
            </li>
            <li class="page-item disabled">
               <a class="page-link"href="#" aria-label="'.translate("pages").'">'.$pages.' '.translate("pages").'</a>
            </li>';
      $pages_rapide='';
      for ($x = 0; $x < $total; $x += $posts_per_page) {
         if ($current_page!=$times)
            $pages_rapide.='<li class="page-item"><a class="page-link" href="viewtopic.php?topic='.$topic.'&amp;forum='.$forum.'&amp;start='.$x.'">'.$times.'</a></li>';
         else
            $pages_rapide.='<li class="page-item active"><a class="page-link" href="#">'.$times.'</a></li>';
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
    settype($start,"integer");
    settype($posts_per_page,"integer");
    if (isset($start)) {
       if ($start==9999) { $start=$posts_per_page*($pages-1); if ($start<0) {$start=0;}; }
       $sql = "SELECT * FROM ".$NPDS_Prefix."posts WHERE topic_id='$topic' AND forum_id='$forum'".$post_aff."ORDER BY post_id LIMIT $start, $posts_per_page";
    } else {
       $sql = "SELECT * FROM ".$NPDS_Prefix."posts WHERE topic_id='$topic' AND forum_id='$forum'".$post_aff."ORDER BY post_id LIMIT $start, $posts_per_page";
    }
    if (!$result = sql_query($sql))
       forumerror(0001);
    $mycount = sql_num_rows($result);
    $myrow = sql_fetch_assoc($result);
    $count = 0;

    if ($allow_upload_forum) {
       $visible = '';
       if (!$Mmod) {
          $visible = ' AND visible = 1';
       }
       $sql = "SELECT att_id FROM $upload_table WHERE apli='forum_npds' && topic_id = '$topic' $visible";
       $att = sql_num_rows(sql_query($sql));
       if ($att>0) {
          include ("modules/upload/include_forum/upload.func.forum.php");
       }
    }
    // Forum Read
    if (isset($user)) {
       $time_actu=time()+($gmt*3600);
       $sqlR = "SELECT last_read FROM ".$NPDS_Prefix."forum_read WHERE forum_id='$forum' AND uid='$userdata[0]' AND topicid='$topic'";
       $result_LR=sql_query($sqlR);
       $last_read='';
       if (sql_num_rows($result_LR)==0) {
          $sqlR = "INSERT INTO ".$NPDS_Prefix."forum_read (forum_id, topicid, uid, last_read, status) VALUES ('$forum', '$topic', '$userdata[0]', '$time_actu', '1')";
          $resultR = sql_query($sqlR);
       } else {
          list($last_read)=sql_fetch_row($result_LR);
          $sqlR = "UPDATE ".$NPDS_Prefix."forum_read SET last_read='$time_actu', status='1' WHERE forum_id='$forum' AND uid='$userdata[0]' AND topicid='$topic'";
          $resultR = sql_query($sqlR);
       }
    }
    
   if ($ibid=theme_image('forum/rank/post.gif')) {$imgtmpP=$ibid;} else {$imgtmpP='images/forum/rank/post.gif';}
   if ($ibid=theme_image("forum/icons/posticon.gif")) {$imgtmpPI=$ibid;} else {$imgtmpPI="images/forum/icons/posticon.gif";}
   if ($ibid=theme_image("forum/icons/profile.gif")) {$imgtmpPR=$ibid;} else {$imgtmpPR="images/forum/icons/profile.gif";}
   if ($ibid=theme_image("forum/icons/gf.gif")) {$imgtmpGF=$ibid;} else {$imgtmpGF="images/forum/icons/gf.gif";}
   if ($ibid=theme_image("forum/icons/new.gif")) {$imgtmpNE=$ibid;} else {$imgtmpNE="images/forum/icons/new.gif";}
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
                     $my_rs.='<a class="mr-1" href="';
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

   $useroutils = '';
   $useroutils .= '<hr />';
      if ($posterdata['uid']!= 1 and $posterdata['uid']!='') {
         $useroutils .= '<a class="list-group-item text-primary" href="user.php?op=userinfo&amp;uname='.$posterdata['uname'].'" target="_blank" title="'.translate("Profile").'" data-toggle="tooltip"><i class="fa fa-2x fa-user"></i>&nbsp;'.translate("Profile").'</a>';
      }
      if ($user) {
         $useroutils .= '<a class="list-group-item text-primary" href="powerpack.php?op=instant_message&amp;to_userid='.$posterdata["uname"].'" title="'.translate("Send internal Message").'" data-toggle="tooltip"><i class="fa fa-2x fa-envelope-o"></i>&nbsp;'.translate("Send internal Message").'</a>';
      }
      if ($posterdata['femail']!='') {
         $useroutils .= '<a class="list-group-item text-primary" href="mailto:'.anti_spam($posterdata['femail'],1).'" target="_blank" title="'.translate("Email").'" data-toggle="tooltip"><i class="fa fa-at fa-2x"></i>&nbsp;'.translate("Email").'</a>';
      }
      if ($posterdata['url']!='') {
         if (strstr('http://', $posterdata['url']))
            $posterdata['url'] = 'http://' . $posterdata['url'];
         $useroutils .= '<a class="list-group-item text-primary" href="'.$posterdata['url'].'" target="_blank" title="'.translate("Visit this Website").'" data-toggle="tooltip"><i class="fa fa-2x fa-external-link"></i>&nbsp;'.translate("Visit this Website").'</a>';
      }
      if ($posterdata['mns']) {
          $useroutils .= '<a class="list-group-item text-primary" href="minisite.php?op='.$posterdata['uname'].'" target="_blank" target="_blank" title="'.translate("Visit the Mini Web Site !").'" data-toggle="tooltip"><i class="fa fa-2x fa-desktop"></i>&nbsp;'.translate("Visit the Mini Web Site !").'</a>';
      }
      
      echo '
      <div class="row">
         <a name="'.$forum.$topic.$myrow['post_id'].'"></a>';
      if (($count+2)==$mycount) echo '<a name="last-post"></a>';
      echo '
         <div class="col-xs-12">
            <div class="card">
               <div class="card-header">';
      if ($smilies) {
         if ($posterdata['user_avatar'] != '') {
            if (stristr($posterdata['user_avatar'],"users_private")) {
             $imgtmp=$posterdata['user_avatar'];
         } else {
             if ($ibid=theme_image("forum/avatar/".$posterdata['user_avatar'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/avatar/".$posterdata['user_avatar'];}
         }
          echo '
          <a style="position:absolute; top:1rem;" tabindex="0" data-toggle="popover" data-html="true" data-title="'.$posterdata['uname'].'" data-content=\''.member_qualif($posterdata['uname'], $posts,$posterdata['rank']).'<br /><div class="list-group">'.$useroutils.'</div><hr />'.$my_rsos[$count].'\'><img class=" btn-secondary img-thumbnail img-fluid n-ava" src="'.$imgtmp.'" alt="'.$posterdata['uname'].'" /></a>';
         }
      }

   echo '&nbsp;<span style="position:absolute; left:6em;" class="text-muted"><strong>'.$posterdata['uname'].'</strong></span>';
   echo '<span class="pull-xs-right">';
      if ($myrow['image'] != '') {
         if ($ibid=theme_image("forum/subject/".$myrow['image'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/subject/".$myrow['image'];}
         echo '<img class="n-smil" src="'.$imgtmp.'" alt="icon_post" />';
      } else {
         echo '<img class="n-smil" src="'.$imgtmpPI.'" alt="icon_post" />';
      }
      echo '</span>
            </div>';
      $message=stripslashes($myrow['post_text']);
      echo '
               <div class="card-block">
                  <div class="card-text pt-1">';
      $date_post=convertdateTOtimestamp($myrow['post_time']);
      if (isset($last_read)) {
         if (($last_read <= $date_post) AND $userdata[3]!='' AND $last_read !='0' AND $userdata[0]!=$myrow['poster_id']) {
            echo '&nbsp;<img src="'.$imgtmpNE.'" alt="" />
            ';
         }
      }

      echo '
               </div>
               <div class="card-text pt-1">';

      if (($allow_bbcode) and ($forum_type!=6) and ($forum_type!=5)) {
         $message = smilie($message);
         $message = aff_video_yt($message);
      }
//      $message=split_string_without_space($message, 80);
      if (($forum_type=='6') or ($forum_type=='5')) {
          highlight_string(stripslashes($myrow['post_text'])).'<br /><br />';
      } else {
         $message=str_replace('[addsig]', '<div class="n-signature">'.nl2br($posterdata['user_sig']).'</div>', $message);
         echo '<div class="card-text fo-post-mes">';
         echo $message;
         echo '</div>';

      }
      if ($allow_upload_forum and ($att>0)) {
         $post_id=$myrow['post_id'];
         echo '<div class="card-text">';
         echo display_upload("forum_npds",$post_id,$Mmod);
         echo '</div>';
      }
   echo '
               </div>
            </div>
               <div class="card-footer">
                  <div class="row">
                     <div class=" col-sm-6 text-muted small">'.post_convertdate($date_post).'</div>
                     <div class=" col-sm-6 text-xs-right">';

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
         aff_pub_in($lock_state,$topic,$forum,$mod);
      }
   }

   if ($forum_access!=9) {
      if (isset($user)) {
          if ($posterdata['uid']==$userdata[0])
             $postuser=true;
          else
             $postuser=false;
      } else
          $postuser=false;
      if (($Mmod) or ($postuser) and (!$lock_state) and ($posterdata['uid']!='')) {
         echo '<a class="mr-1" href="editpost.php?post_id='.$myrow["post_id"].'&amp;topic='.$topic.'&amp;forum='.$forum.'&amp;arbre=0" title="'.translate("Edit").'" data-toggle="tooltip"><i class="fa fa-edit fa-lg"></i></a>';
         if ($allow_upload_forum) {
         $PopUp=win_upload("forum_npds",$myrow['post_id'],$forum,$topic,"popup");
            echo '<a class="mr-1" href="javascript:void(0);" onclick="window.open('.$PopUp.');" title="'.translate("Files").'" data-toggle="tooltip"><i class="fa fa-download fa-lg"></i></a>';
         }
      }
      if ($allow_to_post and !$lock_state and $posterdata['uid']!='') {
         echo '<a class="mr-1" href="reply.php?topic='.$topic.'&amp;forum='.$forum.'&amp;post='.$myrow['post_id'].'&amp;citation=1" title="'.translate("Quote").'" data-toggle="tooltip"><i class="fa fa-quote-left fa-lg"></i></a>';
      }
      echo '<a class="mr-1" href="prntopic.php?forum='.$forum.'&amp;topic='.$topic.'&amp;post_id='.$myrow['post_id'].'" title="'.translate("Print").'" data-toggle="tooltip"><i class="fa fa-print fa-lg"></i></a>';
      if ($Mmod) {
         echo '<a class="mr-1" href="topicadmin.php?mode=viewip&amp;topic='.$topic.'&amp;post='.$myrow['post_id'].'&amp;forum='.$forum.'&amp;arbre=0" title="IP" data-toggle="tooltip" ><i class="fa fa-laptop fa-lg"></i></a>';
         if (!$myrow['post_aff']) {
            echo '&nbsp;<a href="topicadmin.php?mode=aff&amp;topic='.$topic.'&amp;post='.$myrow['post_id'].'&amp;ordre=1&amp;forum='.$forum.'&amp;arbre=0" title="'.translate("Show this post").'" data-toggle="tooltip"><i class="fa fa-eye text-danger fa-lg"></i></a>&nbsp;';
         } else {
            echo '&nbsp;<a href="topicadmin.php?mode=aff&amp;topic='.$topic.'&amp;post='.$myrow['post_id'].'&amp;ordre=0&amp;forum='.$forum.'&amp;arbre=0" title="'.translate("Hide this post").'" data-toggle="tooltip"><i class="fa fa-eye-slash fa-lg "></i></a>&nbsp;';
         }
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
    unset ($tmp_imp);
    $sql = "UPDATE ".$NPDS_Prefix."forumtopics SET topic_views = topic_views + 1 WHERE topic_id = '$topic'";
    sql_query($sql);
    if ($total > $posts_per_page) {
       echo '
       <nav>
         <ul class="pagination pagination-sm">
            <li class="page-item">
               <a class="page-link" href="#topofpage"><i class="fa fa-angle-double-up" title="'.translate("Back to Top").'" data-toggle="tooltip"></i></a>
            </li>
            <li class="page-item disabled">
               <a class="page-link" href="#">'.translate("Goto Page").'</a>
            </li>';
             echo $pages_rapide.'
         </ul>
      </nav>';
    }
    
    if ($forum_access!=9) {
       // un anonyme ne peut pas mettre un topic en resolu
       if (!isset($userdata)) $userdata[0]=0;
       if ((($Mmod) or ($original_poster==$userdata[0])) and (!$lock_state)) {
          $sec_clef=md5($forum.$topic.md5($NPDS_Key));
          echo '
          <p><a href="viewforum.php?forum='.$forum.'&amp;topic_id='.$topic.'&amp;topic_title='.rawurlencode($topic_subject).'&amp;op=solved&amp;sec_clef='.$sec_clef.'"><i class="fa fa-lock fa-2x"></i>&nbsp;'.translate("Solved").'</a></p>';
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
         <select class="form-control custom-select" name="forum" onchange="submit();">
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

    if ((($Mmod) and ($forum_access!=9)) or ($adminforum==1)) {
       echo '
      <nav class="text-xs-center">
         <ul class="pagination pagination-sm">
            <li class="page-item disabled">
               <a class="page-link" href="#"><i class="fa fa-cogs fa-lg"></i>&nbsp;'.translate("Administration Tools").'</a>
            </li>';
      if ($lock_state==0)
         echo '
            <li class="page-item">
               <a class="page-link" role="button" href="topicadmin.php?mode=lock&amp;topic='.$topic.'&amp;forum='.$forum.'" title="'.translate("Lock this Topic").'" data-toggle="tooltip" ><i class="fa fa-lock fa-lg" aria-hidden="true"></i></a>
            </li>';
      else
         echo '
            <li class="page-item">
               <a class="page-link" role="button" href="topicadmin.php?mode=unlock&amp;topic='.$topic.'&amp;forum='.$forum.'" title="'.translate("Unlock this Topic").'" data-toggle="tooltip"><i class ="fa fa-unlock fa-lg" aria-hidden="true"></i></a>
            </li>';
      echo '
            <li class="page-item">
               <a class="page-link" role="button" href="topicadmin.php?mode=move&amp;topic='.$topic.'&amp;forum='.$forum.'" title="'.translate("Move this Topic").'" data-toggle="tooltip"><i class="fa fa-share fa-lg" aria-hidden="true"></i></a>
            </li>
            <li class="page-item">
               <a class="page-link" role="button" href="topicadmin.php?mode=first&amp;topic='.$topic.'&amp;forum='.$forum.'" title="'.translate("Make this Topic the first one").'" data-toggle="tooltip"><i class="fa fa-level-up fa-lg" aria-hidden="true"></i></a>
            </li>
            <li class="page-item">
               <a class="page-link text-danger" role="button" href="topicadmin.php?mode=del&amp;topic='.$topic.'&amp;forum='.$forum.'" title="'.translate("Delete this Topic").'" data-toggle="tooltip"><i class="fa fa-remove fa-lg" aria-hidden="true"></i></a>
            </li>
         </ul>
      </nav>';
    }
   include("footer.php");
?>
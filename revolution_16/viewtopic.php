<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/* Based on Parts of phpBB                                              */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/* Great mods by snipe                                                  */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion"))
   include ("mainfile.php");

include('functions.php');
$cache_obj = ($SuperCache) ? new cacheManager() : new SuperCacheEmpty() ;
include('auth.php');
global $NPDS_Prefix, $admin, $adminforum;

if ($allow_upload_forum) 
   include("modules/upload/upload_forum.php");

settype($start,'integer');
settype($pages,'integer');//

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
$myrow=$rowQ1[0];
$forum=$myrow['forum_id'];
$rowQ1=Q_Select ("SELECT forum_name, forum_moderator, forum_type, forum_pass, forum_access, arbre FROM ".$NPDS_Prefix."forums WHERE forum_id = '$forum'", 3600);
if (!$rowQ1)
   forumerror('0001');
$myrow=$rowQ1[0];
$forum_name = $myrow['forum_name'];
$mod = $myrow['forum_moderator'];
$forum_type=$myrow['forum_type'];
$forum_access=$myrow['forum_access'];

if ( ($forum_type == 1) and ($Forum_passwd != $myrow['forum_pass']) )
   header("Location: forum.php");

if (($forum_type == 5) or ($forum_type == 7)) {
   $ok_affiche=false;
   if(isset($user)){
      $tab_groupe=valid_group($user);// en ano et admin $user n'existe pas ?  notice ....
      $ok_affiche=groupe_forum($myrow['forum_pass'], $tab_groupe);
   }
   //:: ici 
   if ( (!$ok_affiche) and ($adminforum==0) )
      header("location: forum.php");
}
if (($forum_type==9) and (!$user))
   header("location: forum.php");

// Moderator
if (isset($user)) {
   $userX = base64_decode($user);
   $userdata = explode(':', $userX);
}
$moderator = get_moderator($mod);
$moderator = explode(' ',$moderator);
$Mmod = false;
$countmoderator = count($moderator);
if (isset($user)) {
   for ($i = 0; $i < $countmoderator; $i++) {
      if (($userdata[1]==$moderator[$i])) { $Mmod=true; break;}
   }
}
$sql = "SELECT topic_title, topic_status, topic_poster FROM ".$NPDS_Prefix."forumtopics WHERE topic_id = '$topic'";
$total = get_total_posts($forum, $topic, "topic",$Mmod);
if ($total > $posts_per_page) {
   $times = 0; $current_page=0;
   for ($x = 0; $x < $total; $x += $posts_per_page) {
       if (($x>=$start) and ($current_page==0))
          $current_page=$times+1;
       $times++;
   }
   $pages=$times;
}

   if ($start==9999) { $start=$posts_per_page*($pages-1); if ($start<0) {$start=0;}; };
   $nbPages = ceil($total/$posts_per_page);
      $current = 1;
      if ($start >= 1)
         $current=$start/$posts_per_page;
      else if ($start < 1)
         $current=0;
      else
         $current = $nbPages;

if (!$result = sql_query($sql))
   forumerror('0001');
$myrow = sql_fetch_assoc($result);
$topic_subject = stripslashes($myrow['topic_title']);
$lock_state = $myrow['topic_status'];
$original_poster=$myrow['topic_poster'];

function aff_pub($lock_state, $topic, $forum, $mod) {
   global $language;
   if ($lock_state==0)
      echo '<a class="" href="newtopic.php?forum='.$forum.'" title="'.translate("Nouveau sujet").'" data-bs-toggle="tooltip" ><i class="fa fa-plus-square me-2"></i><span class="d-none d-md-inline">'.translate("Nouveau sujet").'<br /></span></a>&nbsp;';
   else if ($lock_state==1)
      echo '<i class="fa fa-lock fa-lg text-danger me-2" aria-hidden="true" title="'.translate("Vous ne pouvez répondre à ce topic il est verrouillé. Contacter l'administrateur du site.").'" data-bs-toggle="tooltip"></i>';
}
function aff_pub_in($lock_state, $topic, $forum, $mod) {
   global $language;
   if ($lock_state==0)
      echo '<a class="me-3" href="reply.php?topic='.$topic.'&amp;forum='.$forum.'" title="'.translate("Répondre").'" data-bs-toggle="tooltip"><span class="d-none d-md-inline"></span><i class="fa fa-reply me-2"></i><span class="d-none d-md-inline">'.translate("Répondre").'</span></a>';
}

$contributeurs = get_contributeurs($forum, $topic);
$contributeurs = explode(' ',$contributeurs);
$total_contributeurs = count($contributeurs);

$title=$forum_name; $post=$topic_subject;
include('header.php');
   echo '
   <a name="topofpage"></a>
   <p class="lead">
      <a href="forum.php">'.translate("Index du forum").'</a>&nbsp;&raquo;&raquo;&nbsp;
      <a href="viewforum.php?forum='.$forum.'">'.stripslashes($forum_name).'</a>&nbsp;&raquo;&raquo;&nbsp;'.$topic_subject.'
   </p>
   <h3 class="mb-3">';
   if ($forum_access!=9) {
      $allow_to_post=false;
      if ($forum_access==0) {
         $allow_to_post=true;
      } elseif ($forum_access==1) {
         if (isset($user))
            $allow_to_post=true;
      } elseif ($forum_access==2) {
         if (user_is_moderator($userdata[0],$userdata[2],$forum_access)) {
            $allow_to_post=true;
         }
      }
      if ($allow_to_post)
         aff_pub($lock_state,$topic,$forum,$mod);
   }

   echo $topic_subject.'<span class="text-body-secondary ms-1">#'.$topic.'</span>';
   if ($forum_access!=9) {
      $allow_to_post=false;
      if ($forum_access==0) {
         $allow_to_post=true;
      } elseif ($forum_access==1) {
         if (isset($user))
            $allow_to_post=true;
      } elseif ($forum_access==2) {
         if (user_is_moderator($userdata[0],$userdata[2],$forum_access))
            $allow_to_post=true;
      }
      if ($allow_to_post)
         aff_pub_in($lock_state,$topic,$forum,$mod);
   }
   echo '
      </h3>
      <div class="card mb-3">
         <div class="card-body p-2">
         <div class="d-flex ">
            <div class="text-end align-self-center mx-1 py-2"><span class=" me-1 lead">'.$total_contributeurs.'<i class="fa fa-edit fa-fw ms-1 d-inline d-md-none" title="'.translate("Contributeur(s)").'" data-bs-toggle="tooltip"></i></span><span class="d-none d-md-inline">'.translate("Contributeur(s)").'</span></div>
            <div class=" align-self-center me-auto">';
   for ($i = 0; $i < $total_contributeurs; $i++) {
      $contri = get_userdata_from_id($contributeurs[$i]);
      if($contributeurs[$i] !== '0') {
         if ($contri['user_avatar'] != '') {
            if (stristr($contri['user_avatar'],"users_private")) {
               $imgtmp=$contri['user_avatar'];
            } else {
               if ($ibid=theme_image("forum/avatar/".$contri['user_avatar'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/avatar/".$contri['user_avatar'];}
            }
         }
         echo '<img class="img-thumbnail img-fluid n-ava-small mb-1" src="'.$imgtmp.'" alt="'.$contri['uname'].'" title="'.$contri['uname'].'" data-bs-toggle="tooltip" />';
      }
      else 
         echo '<img class="img-thumbnail img-fluid n-ava-small mb-1" src="images/forum/avatar/blank.gif" alt="'.$anonymous.'" title="'.$anonymous.'" data-bs-toggle="tooltip" />';
   }
   echo '
            </div>
         </div>';
   $ibidcountmod = count($moderator);
   echo '
         <div class="d-flex">
            <div class="text-end align-self-center mx-1 py-2"><span class="me-1 lead">'.$ibidcountmod.' <i class="fa fa-balance-scale fa-fw ms-1 d-inline d-md-none" title="'.translate("Modérateur(s)").'" data-bs-toggle="tooltip"></i></span><span class="d-none d-md-inline">'.translate("Modérateur(s)").'</span></div>
            <div class=" align-self-center me-auto">';
   for ($i = 0; $i < $ibidcountmod; $i++) {
      $modera = get_userdata($moderator[$i]);
      if ($modera['user_avatar'] != '') {
         if (stristr($modera['user_avatar'],"users_private"))
          $imgtmp=$modera['user_avatar'];
         else {
          if ($ibid=theme_image("forum/avatar/".$modera['user_avatar'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/avatar/".$modera['user_avatar'];}
         }
      }
      echo '<a href="user.php?op=userinfo&amp;uname='.$moderator[$i].'"><img class=" img-thumbnail img-fluid n-ava-small mb-1" src="'.$imgtmp.'" alt="'.$modera['uname'].'" title="'.translate("Modéré par : ").' '.$modera['uname'].'" data-bs-toggle="tooltip" /></a>';
   }
   echo '
            </div>
         </div>
      </div>
   </div>';

   if ($total > $posts_per_page) {
      $times = 1;
      echo '
      <div class="d-flex my-2 justify-content-between flex-wrap">
         <div id="fo-postpagi">
            <ul class="pagination pagination-sm">
               <li class="page-item">
                  <a class="page-link" href="#botofpage"><i class="fa fa-angle-double-down" title="'.translate("Bas de page").'" data-bs-toggle="tooltip"></i></a>
               </li>
               <li class="page-item disabled">
                  <a class="page-link" href="#" aria-label="'.translate("Contributions").'">'.$total.' '.translate("Contributions").'</a>
               </li>
               <li class="page-item disabled">
                  <a class="page-link"href="#" aria-label="'.translate("pages").'">'.$pages.' '.translate("pages").'</a>
               </li>
            </ul>
         </div>';
      echo paginate('viewtopic.php?topic='.$topic.'&amp;forum='.$forum.'&amp;start=', '', $nbPages, $current, $adj=3, $posts_per_page, $start);
      echo '
      </div>';
   }

   if ($Mmod) $post_aff=' ';
   else $post_aff=" AND post_aff='1' ";

   settype($start,"integer");
   settype($posts_per_page,"integer");
   settype($pages,"integer");

   if (isset($start)) {
      if ($start==9999) { $start=$posts_per_page*($pages-1); if ($start<0) {$start=0;}; }
      $sql = "SELECT * FROM ".$NPDS_Prefix."posts WHERE topic_id='$topic' AND forum_id='$forum'".$post_aff."ORDER BY post_id LIMIT $start, $posts_per_page";
   } else
      $sql = "SELECT * FROM ".$NPDS_Prefix."posts WHERE topic_id='$topic' AND forum_id='$forum'".$post_aff."ORDER BY post_id LIMIT $start, $posts_per_page";

   if (!$result = sql_query($sql))
      forumerror('0001');
   $mycount = sql_num_rows($result);
   $myrow = sql_fetch_assoc($result);
   $count = 0;

   if ($allow_upload_forum) {
      $visible = '';
      if (!$Mmod)
         $visible = ' AND visible = 1';
      $sql = "SELECT att_id FROM $upload_table WHERE apli='forum_npds' && topic_id = '$topic' $visible";
      $att = sql_num_rows(sql_query($sql));
      if ($att>0)
         include ("modules/upload/include_forum/upload.func.forum.php");
   }
   // Forum Read
   if (isset($user)) {
      $time_actu=time()+((integer)$gmt*3600);
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
   if ($ibid=theme_image("forum/icons/new.gif")) {$imgtmpNE=$ibid;} else {$imgtmpNE="images/forum/icons/new.gif";}
   do {
      $posterdata = get_userdata_from_id($myrow['poster_id']);
      if($myrow['poster_id'] !== '0') {
         $posts = $posterdata['posts'];
         $socialnetworks=array(); $posterdata_extend=array();$res_id=array();$my_rs='';
         if (!$short_user) {
            $posterdata_extend = get_userdata_extend_from_id($myrow['poster_id']);
            include('modules/reseaux-sociaux/reseaux-sociaux.conf.php');
            include('modules/geoloc/geoloc.conf');
            if($user or autorisation(-127)) {
               if (array_key_exists('M2', $posterdata_extend)) {
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
                              $my_rs.='<a class="me-2" href="';
                              if($v1[2]=='skype') $my_rs.= $v1[1].$y1[1].'?chat'; else $my_rs.= $v1[1].$y1[1];
                              $my_rs.= '" target="_blank"><i class="fab fa-'.$v1[2].' fa-lg fa-fw mb-2"></i></a> ';
                              break;
                           } 
                           else $my_rs.='';
                        }
                     }
                  }
               }
            }
         }
         settype($ch_lat,'string');
         $useroutils = '';
         if($user or autorisation(-127)) {
            if ($posterdata['uid']!= 1 and $posterdata['uid']!='')
               $useroutils .= '<a class="list-group-item list-group-item-action text-primary text-center text-md-start" href="user.php?op=userinfo&amp;uname='.$posterdata['uname'].'" target="_blank" title="'.translate("Profil").'" data-bs-toggle="tooltip"><i class="fa fa-user fa-2x align-middle fa-fw"></i><span class="ms-3 d-none d-md-inline">'.translate("Profil").'</span></a>';
            if ($posterdata['uid']!= 1)
               $useroutils .= '<a class="list-group-item list-group-item-action text-primary text-center text-md-start" href="powerpack.php?op=instant_message&amp;to_userid='.$posterdata["uname"].'" title="'.translate("Envoyer un message interne").'" data-bs-toggle="tooltip"><i class="far fa-envelope fa-2x align-middle fa-fw"></i><span class="ms-3 d-none d-md-inline">'.translate("Message").'</span></a>';
            if ($posterdata['femail']!='')
               $useroutils .= '<a class="list-group-item list-group-item-action text-primary text-center text-md-start" href="mailto:'.anti_spam($posterdata['femail'],1).'" target="_blank" title="'.translate("Email").'" data-bs-toggle="tooltip"><i class="fa fa-at fa-2x align-middle fa-fw"></i><span class="ms-3 d-none d-md-inline">'.translate("Email").'</span></a>';
            if ($myrow['poster_id']!=1 and array_key_exists($ch_lat, $posterdata_extend)) {
               if ($posterdata_extend[$ch_lat] !='')
                  $useroutils .= '<a class="list-group-item list-group-item-action text-primary text-center text-md-start" href="modules.php?ModPath=geoloc&amp;ModStart=geoloc&amp;op=u'.$posterdata['uid'].'" title="'.translate("Localisation").'" ><i class="fas fa-map-marker-alt fa-2x align-middle fa-fw">&nbsp;</i><span class="ms-3 d-none d-md-inline">'.translate("Localisation").'</span></a>';
            }
         }
         if ($posterdata['url']!='')
            $useroutils .= '<a class="list-group-item list-group-item-action text-primary text-center text-md-start" href="'.$posterdata['url'].'" target="_blank" title="'.translate("Visiter ce site web").'" data-bs-toggle="tooltip"><i class="fas fa-external-link-alt fa-2x align-middle fa-fw"></i><span class="ms-3 d-none d-md-inline">'.translate("Visiter ce site web").'</span></a>';
         if ($posterdata['mns'])
             $useroutils .= '<a class="list-group-item list-group-item-action text-primary text-center text-md-start" href="minisite.php?op='.$posterdata['uname'].'" target="_blank" target="_blank" title="'.translate("Visitez le minisite").'" data-bs-toggle="tooltip"><i class="fa fa-desktop fa-2x align-middle fa-fw"></i><span class="ms-3 d-none d-md-inline">'.translate("Visitez le minisite").'</span></a>';
      }
      if (($count+1)==$mycount) echo '<a name="lastpost"></a>';
      echo '
      <div class="mb-3 shadow">
         <a name="'.$forum.$topic.$myrow['post_id'].'"></a>
         <div class="card">
            <div class="card-header">';
      if ($smilies) {
         if($myrow['poster_id'] !== '0') {
            if ($posterdata['user_avatar'] != '') {
               if (stristr($posterdata['user_avatar'],'users_private'))
                  $imgtmp=$posterdata['user_avatar'];
               else {
                  if ($ibid=theme_image('forum/avatar/'.$posterdata['user_avatar'])) {$imgtmp=$ibid;} else {$imgtmp='images/forum/avatar/'.$posterdata['user_avatar'];}
               }
            }
            echo '
          <a style="position:absolute; top:0.5rem;" tabindex="0" data-bs-toggle="popover" data-bs-trigger="focus" data-bs-html="true" data-bs-title="'.$posterdata['uname'].'" data-bs-content=\'<div class="my-2 border rounded p-2">'.member_qualif($posterdata['uname'], $posts,$posterdata['rang']).'</div><div class="list-group mb-3 text-center">'.$useroutils.'</div><div class="mx-auto text-center" style="max-width:170px;">'.$my_rs.'</div> \'><img class=" btn-outline-primary img-thumbnail img-fluid n-ava" src="'.$imgtmp.'" alt="'.$posterdata['uname'].'" /></a>
          <span style="position:absolute; left:6em;" class="text-body-secondary"><strong>'.$posterdata['uname'].'</strong></span>';
         } else {
            echo '<a style="position:absolute; top:0.5rem;" title="'.$anonymous.'" data-bs-toggle="tooltip"><img class=" btn-outline-primary img-thumbnail img-fluid n-ava" src="images/forum/avatar/blank.gif" alt="'.$anonymous.'" /></a>
            <span style="position:absolute; left:6em;" class="text-body-secondary"><strong>'.$anonymous.'</strong></span>'; }
      } else {
         if($myrow['poster_id'] !== '0')
            echo '<span style="position:absolute; left:6em;" class="text-body-secondary"><strong>'.$posterdata['uname'].'</strong></span>';
         else
            echo '<span class="text-body-secondary"><strong>'.$anonymous.'</strong></span>';
      }
      echo '<span class="float-end">';
      if ($myrow['image'] != '') {
         if ($ibid=theme_image("forum/subject/".$myrow['image'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/subject/".$myrow['image'];}
         echo '<img class="n-smil" src="'.$imgtmp.'" alt="icon_post" />';
      } else
         echo '<img class="n-smil" src="'.$imgtmpPI.'" alt="icon_post" />';
      echo '</span>
            </div>';
      $message=stripslashes($myrow['post_text']);
      echo '
            <div class="card-body">
               <div class="card-text pt-2">';
      $date_post = strtotime(getPartOfTime($myrow['post_time'],'yyyy-MM-dd H:m:s'));
      if (isset($last_read)) {
         if (($last_read <= $date_post) AND $userdata[3]!='' AND $last_read !='0' AND $userdata[0]!=$myrow['poster_id']) {
            //echo '&nbsp;<img src="'.$imgtmpNE.'" alt="" />';
            echo '<span class="me-2 badge bg-success animated faa-flash">NEW</span>';
         }
      }
      echo '
               </div>
               <div class="card-text pt-2">';

      if (($allow_bbcode) and ($forum_type!=6) and ($forum_type!=5)) {
         $message = smilie($message);
         $message = aff_video_yt($message);
         $message = af_cod($message);
         $message = str_replace("\n", '<br />', $message);
      }
      if (($forum_type=='6') or ($forum_type=='5')) {
          highlight_string(stripslashes($myrow['post_text'])).'<br /><br />';
      } else {
         if(array_key_exists('user_sig', $posterdata))
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
                  <div class=" col-sm-6 text-body-secondary small">'.formatTimes($myrow['post_time'], IntlDateFormatter::SHORT, IntlDateFormatter::SHORT).'</div>
                  <div class=" col-sm-6 text-end">';
   if ($forum_access!=9) {
      $allow_to_post=false;
      if ($forum_access==0) {
         $allow_to_post=true;
      } elseif ($forum_access==1) {
         if (isset($user))
            $allow_to_post=true;
      } elseif ($forum_access==2) {
         if (user_is_moderator($userdata[0],$userdata[2],$forum_access))
            $allow_to_post=true;
      }
      if ($allow_to_post)
         aff_pub_in($lock_state,$topic,$forum,$mod);
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
         echo '<a class="me-3" href="editpost.php?post_id='.$myrow["post_id"].'&amp;topic='.$topic.'&amp;forum='.$forum.'&amp;arbre=0" title="'.translate("Editer").'" data-bs-toggle="tooltip"><i class="fa fa-edit fa-lg"></i></a>';
         if ($allow_upload_forum) {
            $PopUp=win_upload("forum_npds",$myrow['post_id'],$forum,$topic,"popup");
            echo '<a class="me-3" href="javascript:void(0);" onclick="window.open('.$PopUp.');" title="'.translate("Fichiers").'" data-bs-toggle="tooltip"><i class="fa fa-download fa-lg"></i></a>';

/*
            echo '
            <a class="me-3" href="#themodal" data-remote="" data-bs-toggle="modal" data-bs-target="#themodal" title="'.translate("Files").'" data-bs-toggle="tooltip"><i class="fa fa-download fa-lg"></i></a>';
*/
         }
      }
      if ($allow_to_post and !$lock_state and $posterdata['uid']!='')
         echo '<a class="me-3" href="reply.php?topic='.$topic.'&amp;forum='.$forum.'&amp;post='.$myrow['post_id'].'&amp;citation=1" title="'.translate("Citation").'" data-bs-toggle="tooltip"><i class="fa fa-quote-left fa-lg"></i></a>';
      echo '<a class="me-3" href="prntopic.php?forum='.$forum.'&amp;topic='.$topic.'&amp;post_id='.$myrow['post_id'].'" title="'.translate("Imprimer").'" data-bs-toggle="tooltip"><i class="fa fa-print fa-lg"></i></a>';
      if ($Mmod or $adminforum) {
         echo '<a class="me-3" href="topicadmin.php?mode=viewip&amp;topic='.$topic.'&amp;post='.$myrow['post_id'].'&amp;forum='.$forum.'&amp;arbre=0" title="IP" data-bs-toggle="tooltip" ><i class="fa fa-laptop fa-lg"></i></a>';
         if (!$myrow['post_aff'])
            echo '&nbsp;<a href="topicadmin.php?mode=aff&amp;topic='.$topic.'&amp;post='.$myrow['post_id'].'&amp;ordre=1&amp;forum='.$forum.'&amp;arbre=0" title="'.translate("Afficher ce post").'" data-bs-toggle="tooltip"><i class="fa fa-eye text-danger fa-lg"></i></a>&nbsp;';
         else
            echo '&nbsp;<a href="topicadmin.php?mode=aff&amp;topic='.$topic.'&amp;post='.$myrow['post_id'].'&amp;ordre=0&amp;forum='.$forum.'&amp;arbre=0" title="'.translate("Masquer ce post").'" data-bs-toggle="tooltip"><i class="fa fa-eye-slash fa-lg "></i></a>&nbsp;';
      }
   }
      echo '
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
       <div class="d-flex my-2 justify-content-between flex-wrap">
          <nav>
            <ul class="pagination pagination-sm d-flex flex-wrap justify-content-end">
               <li class="page-item">
                  <a class="page-link" href="#topofpage"><i class="fa fa-angle-double-up" title="'.translate("Haut de page").'" data-bs-toggle="tooltip"></i></a>
               </li>
               <li class="page-item disabled">
                  <a class="page-link" href="#">'.translate("Haut de page").'</a>
               </li>
            </ul>
         </nav>'
        .paginate('viewtopic.php?topic='.$topic.'&amp;forum='.$forum.'&amp;start=', '', $nbPages, $current, $adj=3, $posts_per_page, $start).'
      </div>';
   }

   if ($forum_access!=9) {
      // un anonyme ne peut pas mettre un topic en resolu
      if (!isset($userdata)) $userdata[0]=0;
      if ((($Mmod) or ($original_poster==$userdata[0])) and (!$lock_state)) {
         $sec_clef=md5($forum.$topic.md5($NPDS_Key));
         echo '
          <p><a href="viewforum.php?forum='.$forum.'&amp;topic_id='.$topic.'&amp;topic_title='.rawurlencode($topic_subject).'&amp;op=solved&amp;sec_clef='.$sec_clef.'"><i class="fa fa-lock fa-2x align-middle me-1"></i>'.translate("Résolu").'</a></p>';
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
<form action="viewforum.php" method="post">
   <div class="mb-3 row">
      <div class="col-12">
         <label class="visually-hidden" for="forumjump">'.translate("Sauter à : ").'</label>
         <select class="form-select" id="forumjump" name="forum" onchange="submit();">
            <option value="index">'.translate("Sauter à : ").'</option>
            <option value="index">'.translate("Index du forum").'</option>';
      $sub_sql = "SELECT forum_id, forum_name, forum_type, forum_pass FROM ".$NPDS_Prefix."forums ORDER BY cat_id,forum_index,forum_id";
      if ($res = sql_query($sub_sql)) {
         while (list($forum_id, $forum_name, $forum_type, $forum_pass)=sql_fetch_row($res)) {
            if (($forum_type != '9') or ($userdata)) {
               $ok_affich = (($forum_type == '7') or ($forum_type == '5')) ? false : true ;
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
   if ($SuperCache)
      $cache_obj->endCachingBlock($cache_clef);

   if ((($Mmod) and ($forum_access!=9)) or ($adminforum==1)) {
      echo '
         <ul class="nav justify-content-center border rounded">
            <li class="nav-item ">
               <a class="nav-link disabled" href="#"><i class="fa fa-cogs fa-lg" title="'.translate("Outils administrateur").'" data-bs-toggle="tooltip"></i><span class="d-none d-md-inline">&nbsp;</span></a>
            </li>';
      if ($lock_state==0)
         echo '
            <li class="nav-item">
               <a class="nav-link" role="button" href="topicadmin.php?mode=lock&amp;topic='.$topic.'&amp;forum='.$forum.'" ><i class="fa fa-lock fa-lg d-md-none" title="'.translate("Fermer ce sujet").'" data-bs-toggle="tooltip" ></i><span class="ms-2 d-none d-md-inline">'.translate("Fermer ce sujet").'</span></a>
            </li>';
      else
         echo '
            <li class="nav-item">
               <a class="nav-link" role="button" href="topicadmin.php?mode=unlock&amp;topic='.$topic.'&amp;forum='.$forum.'" ><i class ="fa fa-unlock fa-lg d-md-none" title="'.translate("Ouvrir ce sujet").'" data-bs-toggle="tooltip"></i><span class="ms-2 d-none d-md-inline">'.translate("Ouvrir ce sujet").'</span></a>
            </li>';
      echo '
            <li class="nav-item">
               <a class="nav-link" role="button" href="topicadmin.php?mode=move&amp;topic='.$topic.'&amp;forum='.$forum.'" ><i class="fa fa-share fa-lg d-md-none" title="'.translate("Déplacer ce sujet").'" data-bs-toggle="tooltip"></i><span class="ms-2 d-none d-md-inline">'.translate("Déplacer ce sujet").'</span></a>
            </li>
            <li class="nav-item">
               <a class="nav-link" role="button" href="topicadmin.php?mode=first&amp;topic='.$topic.'&amp;forum='.$forum.'" ><i class="fas fa-level-up-alt fa-lg d-md-none" title="'.translate("Mettre ce sujet en premier").'" data-bs-toggle="tooltip"></i><span class="ms-2 d-none d-md-inline">'.translate("Mettre ce sujet en premier").'</span></a>
            </li>
            <li class="nav-item">
               <a class="nav-link text-danger" role="button" href="topicadmin.php?mode=del&amp;topic='.$topic.'&amp;forum='.$forum.'" ><i class="fas fa-trash fa-lg d-md-none" title="'.translate("Effacer ce sujet").'" data-bs-toggle="tooltip"></i><span class="ms-2 d-none d-md-inline">'.translate("Effacer ce sujet").'</span></a>
            </li>
         </ul>';
   }
   include("footer.php");
?>
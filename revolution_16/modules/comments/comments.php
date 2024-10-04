<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/* Based on Parts of phpBB                                              */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion"))
   die();
include_once('functions.php');
include_once('auth.php');

settype($forum,'integer');
settype($short_user,'integer');
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
      $tmp='<a href="modules.php?ModPath=comments&ModStart=reply&topic='.$topic.'&file_name='.$file_name.'&archive='.$archive.'" class="btn btn-primary btn-sm" role="button">'.translate("Commentaire").'</a>';
   return ($tmp);
}
   if ($forum_access==0)
      $allow_to_post=true;
   else
      if ($user)
         $allow_to_post=true;

   global $anonymous;
   if(!isset($archive))
      $archive=0;
   if ($allow_to_post)
      echo '<nav class="text-end my-2">'.Caff_pub($topic,$file_name, $archive).'</nav>';

   // Pagination
   if(!isset($C_start))
      $C_start=0;
   settype($comments_per_page,'integer');
   $result=sql_query ("SELECT COUNT(*) AS total FROM ".$NPDS_Prefix."posts WHERE forum_id='$forum' AND topic_id='$topic' AND post_aff='1'");
   list($total)=sql_fetch_row($result);

   $nbPages = ceil($total/$comments_per_page);
   $current = 1;
   if ($C_start >= 1)
      $current=$C_start/$comments_per_page;
   else if ($C_start < 1)
      $current=0;
   else
      $current = $nbPages;

   if($total>=1) {
      echo '
      <div class="d-flex mt-4 justify-content-between flex-wrap">
      <nav id="co-pagihaute">
         <ul class="pagination pagination-sm d-flex flex-wrap justify-content-end">
            <li class="page-item">
               <a class="page-link" href="#co-pagibasse"><i class="fa fa-angle-double-down" title="'.translate("Bas de page").'" data-bs-toggle="tooltip"></i></a>
            </li>
            <li class="page-item disabled">
               <a class="page-link" href="#" aria-label="'.translate("Commentaire(s)").'">'.$total.' '.translate("Commentaire(s)").'</a>
            </li>
            <li class="page-item disabled">
               <a class="page-link"href="#" aria-label="'.translate("pages").'">'.$nbPages.' '.translate("pages").'</a>
            </li>
         </ul>
      </nav>';
   if ($total > $comments_per_page)
      echo paginate(rawurldecode($url_ret).'&amp;C_start=', '', $nbPages, $current, 2, $comments_per_page, $C_start);
   echo '
      </div>';
   }
   if ($Mmod) $post_aff=' '; else $post_aff=" AND post_aff='1' ";
   $sql = "SELECT * FROM ".$NPDS_Prefix."posts WHERE forum_id='$forum' AND topic_id = '$topic'".$post_aff."ORDER BY post_id LIMIT $C_start, $comments_per_page";
   if (!$result = sql_query($sql)) forumerror('0001');
   $mycount = sql_num_rows($result);
   $myrow = sql_fetch_assoc($result);
   $count = 0;

if ($mycount) {
   if ($ibid=theme_image("forum/icons/posticon.gif")) {$imgtmpPI=$ibid;} else {$imgtmpPI="images/forum/icons/posticon.gif";}
   do {
      $posterdata = get_userdata_from_id($myrow['poster_id']);
      if($myrow['poster_id'] !== '0') {
         $posts = $posterdata['posts'];
         $socialnetworks=array(); $posterdata_extend=array(); $res_id=array(); $my_rs='';
         if (!$short_user) {
            $posterdata_extend = get_userdata_extend_from_id($myrow['poster_id']);
            include('modules/reseaux-sociaux/reseaux-sociaux.conf.php');
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
                              $my_rs.='<a class="me-2" href="'.$v1[1].$y1[1].'" target="_blank"><i class="fab fa-'.$v1[2].' fa-lg fa-fw mb-2"></i></a>';
                              break;
                           } 
                        }
                     }
                  }
               }
            }
         }
         include('modules/geoloc/geoloc.conf');
         settype($ch_lat,'string');

         $useroutils = '';
         if($user or autorisation(-127)) {
            if ($posterdata['uid']!= 1 and $posterdata['uid']!='')
               $useroutils .= '<a class="list-group-item text-primary text-center text-md-start" href="user.php?op=userinfo&amp;uname='.$posterdata['uname'].'" target="_blank" title="'.translate("Profil").'" data-bs-toggle="tooltip"><i class="fa fa-user fa-2x align-middle"></i><span class="ms-3 d-none d-md-inline">'.translate("Profil").'</span></a>';
            if ($posterdata['uid']!= 1 and $posterdata['uid']!='')
               $useroutils .= '<a class="list-group-item text-primary text-center text-md-start" href="powerpack.php?op=instant_message&amp;to_userid='.$posterdata["uname"].'" title="'.translate("Envoyer un message interne").'" data-bs-toggle="tooltip"><i class="far fa-envelope fa-2x align-middle"></i><span class="ms-3 d-none d-md-inline">'.translate("Message").'</span></a>';
            if ($posterdata['femail']!='')
               $useroutils .= '<a class="list-group-item text-primary text-center text-md-start" href="mailto:'.anti_spam($posterdata['femail'],1).'" target="_blank" title="'.translate("Email").'" data-bs-toggle="tooltip"><i class="fa fa-at fa-2x align-middle"></i><span class="ms-3 d-none d-md-inline">'.translate("Email").'</span></a>';
            if ($myrow['poster_id']!=1 and array_key_exists($ch_lat, $posterdata_extend)) {
               if ($posterdata_extend[$ch_lat] !='')
                  $useroutils .= '<a class="list-group-item list-group-item-action text-primary text-center text-md-start" href="modules.php?ModPath=geoloc&amp;ModStart=geoloc&amp;op='.$posterdata['uname'].'" title="'.translate("Localisation").'" ><i class="fas fa-map-marker-alt fa-2x align-middle"></i><span class="ms-3 d-none d-md-inline">'.translate("Localisation").'</span></a>';
            }
         }
         if ($posterdata['url']!='')
            $useroutils .= '<a class="list-group-item text-primary text-center text-md-start" href="'.$posterdata['url'].'" target="_blank" title="'.translate("Visiter ce site web").'" data-bs-toggle="tooltip"><i class="fas fa-external-link-alt fa-2x align-middle"></i><span class="ms-3 d-none d-md-inline">'.translate("Visiter ce site web").'</span></a>';
         if ($posterdata['mns'])
             $useroutils .= '<a class="list-group-item text-primary text-center text-md-start" href="minisite.php?op='.$posterdata['uname'].'" target="_blank" target="_blank" title="'.translate("Visitez le minisite").'" data-bs-toggle="tooltip"><i class="fa fa-desktop fa-2x align-middle"></i><span class="ms-3 d-none d-md-inline">'.translate("Visitez le minisite").'</span></a>';
      }
      echo '
      <div class="row">
         <a name="'.$forum.$topic.$myrow['post_id'].'"></a>';
      if (($count+2)==$mycount) echo '<a name="lastpost"></a>';
      echo '
         <div class="col-12 mb-3">
            <div class="card">
               <div class="card-header">';
      global $smilies;
      if ($smilies) {
         if($myrow['poster_id'] !== '0') {
            if ($posterdata['user_avatar'] != '') {
               if (stristr($posterdata['user_avatar'],"users_private"))
                   $imgtmp=$posterdata['user_avatar'];
               else
                  $imgtmp = $ibid=theme_image("forum/avatar/".$posterdata['user_avatar']) ?
                     $ibid :
                     "images/forum/avatar/".$posterdata['user_avatar'];
            }
            echo '
            <a style="position:absolute; top:1rem;" tabindex="0" data-bs-toggle="popover" data-bs-trigger="focus" data-bs-html="true" data-bs-title="'.$posterdata['uname'].'" data-bs-content=\'<div class="my-2 border rounded p-2">'.member_qualif($posterdata['uname'], $posts,$posterdata['rang']).'</div><div class="list-group mb-3 text-center">'.$useroutils.'</div><div class="mx-auto text-center" style="max-width:170px;">'.$my_rs.'</div>\'><img class=" btn-outline-primary img-thumbnail img-fluid n-ava" src="'.$imgtmp.'" alt="'.$posterdata['uname'].'" loading="lazy" /></a>
            <span style="position:absolute; left:6em;" class="text-body-secondary"><strong>'.$posterdata['uname'].'</strong></span>';
         } else
            echo '
            <a style="position:absolute; top:1rem;" title="'.$anonymous.'" data-bs-toggle="tooltip"><img class=" btn-outline-primary img-thumbnail img-fluid n-ava" src="images/forum/avatar/blank.gif" alt="'.$anonymous.'" loading="lazy" /></a>
            <span style="position:absolute; left:6em;" class="text-body-secondary"><strong>'.$anonymous.'</strong></span>';
      } else
         echo $myrow['poster_id'] !== '0' ?
            '<span style="position:absolute; left:6em;" class="text-body-secondary"><strong>'.$posterdata['uname'].'</strong></span>' :
            '<span class="text-body-secondary"><strong>'.$anonymous.'</strong></span>';
      $imgtmp = $ibid=theme_image("forum/subject/00.png") ? $ibid : 'images/forum/subject/00.png';
      echo '
                  <span class="float-end"><img class="n-smil" src="'.$imgtmp.'" alt="" /></span>
               </div>';
      $message=stripslashes($myrow['post_text']);
      echo '
               <div class="card-body">
                  <div class="card-text pt-3">';
      if ($allow_bbcode) {
         $message = smilie($message);
         $message = aff_video_yt($message);
      }
      if(array_key_exists('user_sig', $posterdata))
         $message=str_replace("[addsig]", '<div class="n-signature">'.nl2br($posterdata['user_sig']).'</div>', $message);
         echo '
                     <div class="card-text fo-post-mes">
                        '.nl2br($message).'
                     </div>
                  </div>
               </div>
               <div class="card-footer">
                  <div class="row">
                     <div class=" col-sm-6 text-body-secondary small">'.formatTimes($myrow['post_time'], IntlDateFormatter::MEDIUM, IntlDateFormatter::SHORT).'</div>
                     <div class=" col-sm-6 text-end">';
      if ($allow_to_post)
         echo '<a class="me-3" href="modules.php?ModPath=comments&amp;ModStart=reply&amp;topic='.$topic.'&amp;file_name='.$file_name.'&amp;archive='.$archive.'" title="'.translate("Commentaire").'" data-bs-toggle="tooltip"><i class="far fa-comment fa-lg"></i></a>';
      if ($allow_to_post and $posterdata['uid']!='') {
         if ($formulaire=='') 
            echo '<a class="me-3" href="modules.php?ModPath=comments&amp;ModStart=reply&amp;topic='.$topic.'&amp;file_name='.$file_name.'&amp;post='.$myrow['post_id'].'&amp;citation=1&amp;archive='.$archive.'" title="'.translate("Citation").'" data-bs-toggle="tooltip" ><i class="fa fa-lg fa-quote-left"></i></a>';
      }
      if ($Mmod) {
         echo '<a class="me-3" href="modules.php?ModPath=comments&amp;ModStart=admin&amp;mode=viewip&amp;topic='.$topic.'&amp;post='.$myrow['post_id'].'&amp;file_name='.$file_name.'&amp;archive='.$archive.'" title="IP" data-bs-toggle="tooltip"><i class="fa fa-lg fa-laptop"></i></a>';
         if (!$myrow['post_aff'])
            echo '<a class="me-3" href="modules.php?ModPath=comments&amp;ModStart=admin&amp;mode=aff&amp;topic='.$topic.'&amp;post='.$myrow['post_id'].'&amp;ordre=1&amp;file_name='.$file_name.'&amp;archive='.$archive.'" title="'.translate("Afficher ce commentaire").'" data-bs-toggle="tooltip" data-bs-placement="left"><i class="fa fa-lg fa-eye text-danger"></i></a>';
         else
            echo '<a class="me-3" href="modules.php?ModPath=comments&amp;ModStart=admin&amp;mode=aff&amp;topic='.$topic.'&amp;post='.$myrow['post_id'].'&amp;ordre=0&amp;file_name='.$file_name.'&amp;archive='.$archive.'" title="'.translate("Masquer ce commentaire").'" data-bs-toggle="tooltip" data-bs-placement="left"><i class="fa fa-lg fa-eye-slash"></i></a>';
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

   echo '
      <div class="d-flex my-2 justify-content-between flex-wrap">
         <nav id="co-pagibasse">
            <ul class="pagination pagination-sm d-flex flex-wrap justify-content-end">
               <li class="page-item">
                  <a class="page-link" href="#co-pagihaute"><i class="fa fa-angle-double-up" title="'.translate("Haut de page").'" data-bs-toggle="tooltip"></i></a>
               </li>
               <li class="page-item disabled">
                  <a class="page-link" href="#" aria-label="'.translate("Commentaire(s)").'">'.$total.' '.translate("Commentaire(s)").'</a>
               </li>
               <li class="page-item disabled">
                  <a class="page-link"href="#" aria-label="'.translate("pages").'">'.$nbPages.' '.translate("pages").'</a>
               </li>
            </ul>
         </nav>';
   if ($total > $comments_per_page)
      echo paginate(rawurldecode($url_ret).'&amp;C_start=', '', $nbPages, $current, 2, $comments_per_page, $C_start);
   echo '
      </div>';
   if ($allow_to_post)
      echo '
      <nav class="text-end mb-2">'.Caff_pub($topic, $file_name, $archive).'</nav>';
   echo '
      <blockquote class="blockquote my-3">'.translate("Les commentaires sont la propriété de leurs auteurs. Nous ne sommes pas responsables de leur contenu.").'</blockquote>';
   if ($Mmod) {
      echo '
      <nav class="text-center">
         <ul class="pagination pagination-sm">
            <li class="page-item disabled">
               <a class="page-link" href="#"><i class="fa fa-cogs fa-lg"></i>&nbsp;'.translate("Outils administrateur").'</a>
            </li>
            <li class="page-item">
               <a class="page-link text-danger" href="modules.php?ModPath=comments&amp;ModStart=admin&amp;mode=del&amp;topic='.$topic.'&amp;file_name='.$file_name.'&amp;archive='.$archive.'" title="'.translate("Effacer les commentaires.").'" data-bs-toggle="tooltip"><i class="fa fa-times fa-lg" ></i></a>
            </li>
         </ul>
      </nav>';
   }
}
?>
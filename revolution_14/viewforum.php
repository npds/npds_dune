<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/* Based on Parts of phpBB                                              */
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
global $NPDS_Prefix,$admin;

//==> droits des admin sur les forums (superadmin et admin avec droit gestion forum)
   $adminforum=false;
   if ($admin) {
      $adminX = base64_decode($admin);
      $adminR = explode(':', $adminX);
      $Q = sql_fetch_assoc(sql_query("SELECT * FROM ".$NPDS_Prefix."authors WHERE aid='$adminR[0]' LIMIT 1"));
      if ($Q['radminsuper']==1) {$adminforum=1;} else {
         $R = sql_query("SELECT fnom, fid, radminsuper FROM ".$NPDS_Prefix."authors a LEFT JOIN ".$NPDS_Prefix."droits d ON a.aid = d.d_aut_aid LEFT JOIN ".$NPDS_Prefix."fonctions f ON d.d_fon_fid = f.fid WHERE a.aid='$adminR[0]' and f.fid between 13 and 15");
         if (sql_num_rows($R) >=1) $adminforum=1;
      }
   }
//<== droits des admin sur les forums (superadmin et admin avec droit gestion forum)


settype($op,'string');
if (($op=="mark") and ($forum)) {
   if ($user) {
      $userX = base64_decode($user);
      $userR = explode(":", $userX);
      $resultT=sql_query("SELECT topic_id FROM ".$NPDS_Prefix."forumtopics WHERE forum_id='$forum' ORDER BY topic_id ASC");
      $time_actu = time()+($gmt*3600);
      while (list($topic_id)=sql_fetch_row($resultT)) {
         $r=sql_query("SELECT rid FROM ".$NPDS_Prefix."forum_read WHERE forum_id='$forum' AND uid='$userR[0]' AND topicid='$topic_id'");
         if ($r) {
            if (!list($rid)=sql_fetch_row($r)) {
               $r=sql_query("INSERT INTO ".$NPDS_Prefix."forum_read (forum_id, topicid, uid, last_read, status) VALUES ('$forum', '$topic_id', '$userR[0]', $time_actu, '1')");
            } else {
               $r=sql_query("UPDATE ".$NPDS_Prefix."forum_read SET last_read='$time_actu', status='1' WHERE rid='$rid'");
            }
         }
      }
      header("location: forum.php");
   }
}

if ($forum=="index")
   header("location: forum.php");
settype($forum, "integer");
$rowQ1=Q_Select ("SELECT forum_name, forum_moderator, forum_type, forum_pass, forum_access, arbre FROM ".$NPDS_Prefix."forums WHERE forum_id = '$forum'", 3600);
if (!$rowQ1)
   forumerror('0002');
list(,$myrow) = each($rowQ1);
$forum_name = stripslashes($myrow['forum_name']);
$moderator=get_moderator($myrow['forum_moderator']);
$forum_access=$myrow['forum_access'];
  
if (($op=="solved") and ($topic_id) and ($forum) and ($sec_clef)) {
   if ($user) {
      $local_sec_clef=md5($forum.$topic_id.md5($NPDS_Key));
      if ($local_sec_clef==$sec_clef) {
         $sqlS = "UPDATE ".$NPDS_Prefix."forumtopics SET topic_status='2', topic_title='[".translate("Solved")."] - ".removehack($topic_title)."' WHERE topic_id='$topic_id'";
         if (!$r = sql_query($sqlS))
            forumerror('0011');
      }
      unset($local_sec_clef);
   }
   unset($sec_clef);
}

// Pour les forums de type Groupe, le Mot de Passe stock l'ID du groupe ...
// Pour les forums de type Extended Text, le Mot de Passe stock le nom du fichier de formulaire ...
if (($myrow['forum_type'] == 5) or ($myrow['forum_type'] == 7)) {
   $ok_affiche=false;
   $tab_groupe=valid_group($user);
   $ok_affiche=groupe_forum($myrow['forum_pass'], $tab_groupe);
   if ($ok_affiche) {$Forum_passwd=$myrow['forum_pass'];}
}

if ($myrow['forum_type'] == 8) {$Forum_passwd=$myrow['forum_pass'];} else {settype($Forum_passwd,'string');}

// Forum ARBRE
if ($myrow['arbre'])
   $hrefX='viewtopicH.php';
else
   $hrefX='viewtopic.php';

if ( ($myrow['forum_type'] == 1) and ( ($myrow['forum_name'] != $forum_name) or ($Forum_passwd != $myrow['forum_pass'])) ) {
    include('header.php');

   echo '<p class="lead">'.translate("Moderated By: ").'';
    $moderator_data=explode(" ",$moderator);
    for ($i = 0; $i < count($moderator_data); $i++) {
       $modera = get_userdata($moderator[$i]);
          if ($modera['user_avatar'] != '') {
             if (stristr($modera['user_avatar'],"users_private")) {
                $imgtmp=$modera['user_avatar'];
             } else {
                if ($ibid=theme_image("forum/avatar/".$modera['user_avatar'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/avatar/".$modera['user_avatar'];}
             }
          }
      echo '<a href="user.php?op=userinfo&amp;uname='.$moderator_data[$i].'"><img width="48" height="48" class=" img-thumbnail img-fluid n-ava" src="'.$imgtmp.'" alt="'.$modera['uname'].'" title="'.$modera['uname'].'" data-toggle="tooltip" /></a>';
    }
   echo '</p>';
    echo '
   <p class="lead">
      <a href="forum.php">'.translate("Forum Index").'</a>&nbsp;&raquo;&raquo;&nbsp;'.stripslashes($forum_name).'
   </p>';

   echo '
      <form role="form" action="viewforum.php" method="post">
      <div class="form-group">
      <div class="text-xs-center">
      <label class="control-label">'.translate("This is a Private Forum. Please enter the password to gain access").'</label>
      </div>
      <div class="row">
      <div class="col-md-4 col-md-offset-4">
      <input class="form-control" type="password" name="Forum_passwd"  placeholder="'.translate("Password").'" />
      </div>
      </div>
      </div>
      <input type="hidden" name="forum" value="'.$forum.'" />
      <div class="text-xs-center">
      <button type="submit" class="btn btn-primary" name="submit" title="'.translate("Submit").'"><i class="fa fa-check"></i></button>&nbsp;
      <button type="reset" class="btn btn-default" name="reset" title="'.translate("Clear").'"><i class="fa fa-refresh"></i></button>
      </div>
      </form>';
   }
   
   //::ICI 
   elseif ( ($Forum_passwd == $myrow['forum_pass']) or ($adminforum==1) ) {
   if (($myrow['forum_type']== 9) and (!$user)) { header("location: forum.php"); }
   $title=$forum_name;
   include('header.php');
   echo '
      <p class="lead">
         <a href="forum.php" >'.translate("Forum Index").'</a>&nbsp;&raquo;&raquo;&nbsp;'.stripslashes($forum_name).'
      </p>';

   if ($user) {
      $userX = base64_decode($user);
      $userR = explode(':', $userX);
   }

   echo '
      <div class="card">
         <div class="card-block-small">
         '.translate("Moderated By: ");
   $Mmod=false;
   $moderator_data=explode(' ',$moderator);
   for ($i = 0; $i < count($moderator_data); $i++) {
      $modera = get_userdata($moderator_data[$i]);
      if ($modera['user_avatar'] != '') {
         if (stristr($modera['user_avatar'],"users_private")) {
            $imgtmp=$modera['user_avatar'];
         } else {
            if ($ibid=theme_image("forum/avatar/".$modera['user_avatar'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/avatar/".$modera['user_avatar'];}
         }
      }
      if ($user)
         if (($userR[1]==$moderator_data[$i])) {$Mmod=true;}
      echo '<a href="user.php?op=userinfo&amp;uname='.$moderator_data[$i].'"><img width="48" height="48" class=" img-thumbnail img-fluid n-ava" src="'.$imgtmp.'" alt="'.$modera['uname'].'" title="'.$modera['uname'].'" data-toggle="tooltip" /></a>';
   }
   echo '
         </div>
      </div>';
   
   echo '
   <h3>';
   if ($forum_access!=9) {
      $allow_to_post = true;
      if ($forum_access==2)
         if (!user_is_moderator($userR[0],$userR[2],$forum_access)) {$allow_to_post = false;}
      if ($allow_to_post) {
         echo '<a class="" href="newtopic.php?forum='.$forum.'" title="'.translate("New").'"><i class="fa fa-plus-square "></i></a>&nbsp;';
      }
   }
   echo stripslashes($forum_name).'<span class="text-muted">&nbsp;#'.$forum.'</span>
   </h3>';
   if ($solved) {
      if (isset($closoled)) {
         $closol="and topic_status='2'";
         $mess_closoled='<a href="viewforum.php?forum='.$forum.'">'.translate("Without").' '.translate("Solved").'</a>';
      } else {
         $closol="and topic_status!='2'";
         $mess_closoled='<a href="viewforum.php?forum='.$forum.'&amp;closoled=on">'.translate("Only").' '.translate("Solved").'</a>';
      }
   } else {
      $closol=''; $mess_closoled='';
   }
 
   echo '
   <h4>'.translate("Topics").'</h4>
   <table id ="lst_forum" data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-icons-prefix="fa" data-icons="icons">
         <thead>
            <tr>
               <th></th>
               <th></th>
               <th class="" data-sortable="true">'.translate("Topic").'&nbsp;&nbsp;'.$mess_closoled.'</th>
               <th class="text-xs-center" data-sortable="true" data-align="right" ><i class="fa fa-reply fa-lg text-muted" title="'.translate("Replies").'" data-toggle="tooltip" ></i></th>
               <th class="text-xs-center" data-sortable="true" data-align="center" ><i class="fa fa-user fa-lg text-muted" title="'.translate("Poster").'" data-toggle="tooltip"></i></th>
               <th class="text-xs-center" data-sortable="true" data-align="right" ><i class="fa fa-eye fa-lg text-muted" title="'.translate("Views").'" data-toggle="tooltip" ></i></th>
               <th data-sortable="true" data-align="right" ><i class="fa fa-calendar-o fa-lg text-muted" title="'.translate("Date").'" data-toggle="tooltip" ></i></th>
            </tr>
         </thead>
         <tbody>';
   settype($start,"integer");
   settype($topics_per_page,"integer");
   $sql = "SELECT * FROM ".$NPDS_Prefix."forumtopics WHERE forum_id='$forum' $closol ORDER BY topic_first,topic_time DESC LIMIT $start, $topics_per_page";
   if (!$result = sql_query($sql))
     forumerror('0004');

   if ($ibid=theme_image("forum/icons/hot_red_folder.gif")) {$imgtmpHR=$ibid;} else {$imgtmpHR="images/forum/icons/hot_red_folder.gif";}
   if ($ibid=theme_image("forum/icons/hot_folder.gif")) {$imgtmpH=$ibid;} else {$imgtmpH="images/forum/icons/hot_folder.gif";}
   if ($ibid=theme_image("forum/icons/red_folder.gif")) {$imgtmpR=$ibid;} else {$imgtmpR="images/forum/icons/red_folder.gif";}
   if ($ibid=theme_image("forum/icons/folder.gif")) {$imgtmpF=$ibid;} else {$imgtmpF="images/forum/icons/folder.gif";}
//   if ($ibid=theme_image("forum/icons/lock.gif")) {$imgtmpL=$ibid;} else {$imgtmpL="images/forum/icons/lock.gif";}
   if ($ibid=theme_image("forum/icons/posticon.gif")) {$imgtmpP=$ibid;} else {$imgtmpP="images/forum/icons/posticon.gif";}
   if ($ibid=theme_image("box/right.gif")) {$imgtmpRi=$ibid;} else {$imgtmpRi="images/download/right.gif";}

   if ($myrow = sql_fetch_assoc($result)) {
      $linked = "onmouseover=\"this.className='lignb'; this.style.cursor='default';\" "
      ." onmouseout=\"this.className='ligna';\"";
      do {
         echo'<tr>';
         $replys = get_total_posts($forum, $myrow['topic_id'], "topic", $Mmod);
         $replys--;
         if ($replys>=0) {
            global $smilies;
            if ($smilies) {
               $rowQ1=Q_Select ("SELECT image FROM ".$NPDS_Prefix."posts WHERE topic_id='".$myrow['topic_id']."' AND forum_id='$forum' LIMIT 0,1", 86400);
               $image_subject=$rowQ1[0]['image'];
            }

            if ($user) {
               $sqlR = "SELECT rid FROM ".$NPDS_Prefix."forum_read WHERE forum_id='$forum' AND uid='$userR[0]' AND topicid='".$myrow['topic_id']."' AND status!='0'";
               if ($replys >= $hot_threshold) {
                  if (sql_num_rows(sql_query($sqlR))==0)
                     $image = '<i class="fa fa-lg fa-file-text"></i>';
                  else
                     $image = '<i class="fa fa-lg fa-file"></i>';
               } else {
                  if (sql_num_rows(sql_query($sqlR))==0)
                     $image = '<i class="fa fa-lg fa-file-text-o"></i>';
                  else
                     $image = '<i class="fa fa-lg fa-file-o text-muted"></i>';
               }
            } else {
               if ($replys >= $hot_threshold)
                  $image = '<i class="fa fa-lg fa-file-text"></i>';
               else
                  $image = '<i class="fa fa-lg fa-file-text-o"></i>';
            }
            if ($myrow['topic_status']!=0)
               $image = '<i class="fa fa-lg fa-lock text-danger"></i>';
            echo '<td class="">'.$image.'</td>';

            if ($image_subject != "") {
               if ($ibid=theme_image("forum/subject/$image_subject")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/subject/$image_subject";}
               echo '<td><img class="smil" src="'.$imgtmp.'" border="0" alt="" /></td>';
            } else 
            echo '<td><img class="smil" src="'.$imgtmpP.'" border="0" alt="" /></td>';

            $topic_title = stripslashes($myrow['topic_title']);
            if (!stristr($topic_title,"<a href=")) {
               $last_post_url="$hrefX?topic=".$myrow['topic_id']."&amp;forum=$forum";
               echo "<td>&nbsp;<a href=\"".$last_post_url."\" >$topic_title</a></td>";
               $Sredirection=false;
            } else {
               echo '<td>&nbsp;'.$topic_title.'</td>';
               $Sredirection=true;
            }

            if ($Sredirection) {
               echo '<td>&nbsp;</td>';
            } else {
               if (($replys+1) > $posts_per_page) {
                  $pages=0;
                  for ($x = 0; $x < ($replys+1); $x += $posts_per_page)
                     $pages++;
                  $last_post_url="$hrefX?topic=".$myrow['topic_id']."&amp;forum=$forum&amp;start=".(($pages-1)*$posts_per_page);
               }
               echo "<td>$replys&nbsp;<a href=\"".$last_post_url."#last-post\"><img src=\"$imgtmpRi\" border=\"0\" align=\"center\" alt=\"".translate("Last Posts")."\" /></a>&nbsp;&nbsp;&nbsp;</td>";
            }
            if ($Sredirection) {
               if (!$Mmod) {
                  echo '<td>&nbsp;</td>';
               } else {
                  echo "<td>[ <a href=\"$hrefX?topic=".$myrow['topic_id']."&amp;forum=$forum\">".translate("Edit")."</a> ]</td>";
               }
               echo '<td>&nbsp;</td>';
            } else {
               $rowQ1=Q_Select ("SELECT uname FROM ".$NPDS_Prefix."users WHERE uid='".$myrow['topic_poster']."'", 3600);
               $uname=$rowQ1[0]['uname'];
               echo '<td>'.$uname.'</td>';
               echo '<td>'.$myrow['topic_views'].'</td>';
            }
            if ($Sredirection) {
               echo '<td>&nbsp;</td></tr>';
            } else {
               echo "<td>".get_last_post($myrow['topic_id'],"topic","infos",$Mmod)."</td></tr>";
            }
         }
      } while($myrow = sql_fetch_assoc($result));
      sql_free_result($result);
   } else {
      if ($forum_access!=9)
         echo '
         <td colspan="7"><span class="text-danger">'.translate("There are no topics for this forum. ").'</span><br /><a href="newtopic.php?forum='.$forum.'" >'.translate("You can post one here.").'</a></td></tr>';
   }
   echo '
         </tbody>
      </table>';

   if ($user) {
      echo '<br /><p><a href="viewforum.php?op=mark&amp;forum='.$forum.'"><i class="fa fa-lg fa-check-square-o"></i></a>&nbsp;'.translate("Mark all Topics to Read").'</p>';
   }
   $sql = "SELECT COUNT(*) AS total FROM ".$NPDS_Prefix."forumtopics WHERE forum_id='$forum' $closol";
   if (!$r = sql_query($sql))
      forumerror('0001');
   list($all_topics) = sql_fetch_row($r);
   sql_free_result($r);
   if (isset($closoled)) $closol='&amp;closoled=on';
   else $closol='';
   $count = 1;
   $next = $start + $topics_per_page;
   echo '
   <div class="xs-12">
      <ul class="pagination pagination-sm">';
   if ($all_topics > $topics_per_page) {
      if ($next >= $all_topics) {
         echo '
         <li class="page-item"><a class="page-link" href="viewforum.php?forum='.$forum.'&amp;start='.($next-2*$topics_per_page).$closol.'">'.translate("Previous Page").'</a></li>';
      }
      else {
          echo '
         <li class="page-item disabled text-xs-center"><a class="page-link" href="viewforum.php?forum='.$forum.'&amp;start='.$next.$closol.'">'.translate("Next Page").'</a></li>';
      }
    for($x = 0; $x < $all_topics; $x++) {
      if (!($x % $topics_per_page)) {
         if ($x == $start)
            echo '<li class="page-item active"><a class="page-link" href="#">'.$count.'</a></li>';
         else
            echo '<li class="page-item"><a class="page-link" href="viewforum.php?forum='.$forum.'&amp;start='.$x.$closol.'">'.$count.'</a></li>';
         if (($all_topics-($count*$topics_per_page))>0) {
            if (!($count % 20))
         echo '<br />';
         }
         $count++;
      }
    }
   }
   echo '
      </ul>
   </div>';
   echo searchblock();
   echo '<br />
   <blockquote class="blockquote">
      <i class="fa fa-file-text-o fa-lg"></i> = '.translate("New Posts since your last visit.").'<br />
      <i class="fa fa-file-text fa-lg"></i> = '.translate("More than").' '.$hot_threshold.' '.translate("Posts").'<br />
      <i class="fa fa-file-o fa-lg text-muted"></i> = '.translate("No New Posts since your last visit.").'<br />
      <i class="fa fa-file fa-lg"></i> = '.translate("More than").' '.$hot_threshold.' '.translate("Posts").'<br />
      <i class="fa fa-lock fa-lg text-danger"></i> = '.translate("Topic is Locked - No new posts may be made in it").'<br />
   </blockquote>';
   if ($SuperCache) {
      $cache_clef="forum-jump-to";
      $CACHE_TIMINGS[$cache_clef]=3600;
      $cache_obj->startCachingBlock($cache_clef);
   }
   if (($cache_obj->genereting_output==1) or ($cache_obj->genereting_output==-1) or (!$SuperCache)) {
      echo '
   <form class="" role="form" action="viewforum.php" method="post">
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
                if ($ok_affich) echo '<option value="'.$forum_id.'">&nbsp;&nbsp;'.stripslashes($forum_name).'</option>';
             }
          }
       }
       echo '
            </select>
      </div>
   </form>';
   }
   if ($SuperCache) {
      $cache_obj->endCachingBlock($cache_clef);
   }
} else {
   header("location: forum.php");
}
include("footer.php");
?>
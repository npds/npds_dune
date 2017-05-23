<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
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

// Make Member_list Private or not
if (!AutoReg()) { unset($user); }
if (($member_list==1) AND ($user=='') AND ($admin=='')) {
   Header("Location: user.php");
}

if (isset($gr_from_ws) and ($gr_from_ws!=0)) {
   settype($gr_from_ws, 'integer');
   $uid_from_ws="^(";
   $re = sql_query("SELECT uid, groupe FROM ".$NPDS_Prefix."users_status WHERE groupe regexp '[[:<:]]".$gr_from_ws."[[:>:]]'");
   while (list($ws_uid) = sql_fetch_row($re)) {
      $uid_from_ws.= $ws_uid."|";
   }
   $uid_from_ws=substr($uid_from_ws,0,-1).")\$";
} else
   $uid_from_ws='';

function alpha() {
   global $sortby, $list, $gr_from_ws, $uid_from_ws;
   $alphabet = array (translate("All"), "A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z",translate("Other"));
   $num = count($alphabet) - 1;
   $counter = 0;
   while (list(, $ltr) = each($alphabet)) {
      echo '<a href="memberslist.php?letter='.$ltr.'&amp;sortby='.$sortby.'&amp;list='.$list.'&amp;gr_from_ws='.$gr_from_ws.'">'.$ltr.'</a>';
      if ( $counter != $num ) { echo ' | ';}
      $counter++;
   }
   echo '
   <br />
   <form action="memberslist.php" method="post">
      <div class="form-group row">
         <label class="form-control-label col-sm-3" for="letter">'.translate("Search").'</label>
         <div class="col-sm-9">
            <input id="mblst_search" class="form-control" type="input" name="letter" />
            <input type="hidden" name="list" value="'.urldecode($list).'" />
            <input type="hidden" name="gr_from_ws" value="'.$gr_from_ws.'" />
         </div>
      </div>
   </form>';
}

function unique($ibid) {
   while (list(,$to_user) = each($ibid)) {
      settype($Xto_user,'array');
      if (!array_key_exists($to_user,$Xto_user)) {
         $Xto_user[$to_user]=$to_user;
      }
   }
   return ($Xto_user);
}

function SortLinks($letter) {
   global $sortby, $list, $admin, $gr_from_ws;

   if ($letter == 'front') {
      $letter = translate("All");
   }
   $sort=false;
   echo '
   <p class="">';
   echo translate("Sort by:")." ";
   if ($sortby == "uname ASC" OR !$sortby) {
      echo translate("nickname").' | ';
      $sort=true;
   } else {
      echo '<a href="memberslist.php?letter='.$letter.'&amp;sortby=uname%20ASC&amp;list='.$list.'&amp;gr_from_ws='.$gr_from_ws.'">'.translate("nickname").'</a> | ';
   }
   if ($sortby == 'name ASC') {
      echo translate("real name").' | ';
      $sort=true;
   } else {
      echo '<a href="memberslist.php?letter='.$letter.'&amp;sortby=name%20ASC&amp;list='.$list.'&amp;gr_from_ws='.$gr_from_ws.'">'.translate("real name").'</a> | ';
   }
   if ($sortby == 'user_avatar ASC') {
      echo translate("Avatar").' | ';
      $sort=true;
   } else {
      echo '<a href="memberslist.php?letter='.$letter.'&amp;sortby=user_avatar%20ASC&amp;list='.$list.'&amp;gr_from_ws='.$gr_from_ws.'">'.translate("Avatar").'</a> | ';
   }
   if (($sortby == 'femail ASC') or ($sortby == 'email ASC')) {
      echo translate("Email").' | ';
      $sort=true;
   } else {
      if ($admin) {
         echo '<a href="memberslist.php?letter='.$letter.'&amp;sortby=email%20ASC&amp;list='.$list.'&amp;gr_from_ws='.$gr_from_ws.'">'.translate("Email").'</a> | ';
      } else {
         echo '<a href="memberslist.php?letter='.$letter.'&amp;sortby=femail%20ASC&amp;list='.$list.'&amp;gr_from_ws='.$gr_from_ws.'">'.translate("Email").'</a> | ';
      }
   }
   if ($sortby == 'user_from ASC') {
      echo translate("Location").' | ';
      $sort=true;
   } else {
      echo '<a href="memberslist.php?letter='.$letter.'&amp;sortby=user_from%20ASC&amp;list='.$list.'&amp;gr_from_ws='.$gr_from_ws.'">'.translate("Location").'</a> | ';
   }
   if ($sortby == 'url DESC') {
      echo translate("url").' | ';
      $sort=true;
   } else {
      echo '<a href="memberslist.php?letter='.$letter.'&amp;sortby=url%20DESC&amp;list='.$list.'&amp;gr_from_ws='.$gr_from_ws.'">'.translate("url").'</a> | ';
   }
   if ($sortby == 'mns DESC') {
      echo translate("Mini-Web site").' | ';
      $sort=true;
   } else {
      echo '<a href="memberslist.php?letter='.$letter.'&amp;sortby=mns%20DESC&amp;list='.$list.'&amp;gr_from_ws='.$gr_from_ws.'">'.translate("Mini-Web site").'</a> | ';
   }
   if ($sortby == 'uid DESC') {
      echo "I.D";
      $sort=true;
   } else {
      echo '<a href="memberslist.php?letter='.$letter.'&amp;sortby=uid%20DESC&amp;list='.$list.'&amp;gr_from_ws='.$gr_from_ws.'">I.D</a>';
   }
   if (!$sort) {$sortby='uname ASC';}
   echo '</p>';
}

function avatar($user_avatar) {
   if (!$user_avatar) {
      $imgtmp="images/forum/avatar/blank.gif";
   } else if (stristr($user_avatar,"users_private")) {
      $imgtmp=$user_avatar;
   } else {
      if ($ibid=theme_image("forum/avatar/$user_avatar")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/avatar/$user_avatar";}
      if (!file_exists($imgtmp)) {$imgtmp="images/forum/avatar/blank.gif";}
   }
   return ($imgtmp);
}

   include("header.php");
   $pagesize = $show_user;

   if (!isset($letter) or ($letter=='')) { $letter = translate("All"); }
   $letter=removeHack(stripslashes(htmlspecialchars($letter,ENT_QUOTES,cur_charset)));
   if (!isset($sortby)) { $sortby = 'uid DESC'; }
   $sortby=removeHack($sortby);
   if (!isset($page)) { $page = 1; }

   if (isset($list)) {
      $tempo=unique(explode(',',$list));
      $list=urlencode(implode(',',$tempo));
   }

   $result = sql_query("SELECT u.uname, u.user_avatar FROM ".$NPDS_Prefix."users AS u LEFT JOIN users_status AS us ON u.uid = us.uid where us.open='1' ORDER BY u.uid DESC LIMIT 0,1");
   list($lastuser,$lastava) = sql_fetch_row($result);

   echo '
   <h2><img src="images/admin/users.png" alt="'.translate("Members List").'" />'.translate("Members List");
   if (isset ($uid_from_ws) and ($uid_from_ws!='')) echo '<span class="text-muted"> '.translate("for group").' #'.$gr_from_ws.'</span>';
   echo '</h2>
   <hr />';

   if (!isset($gr_from_ws)) {
      echo '
      <div class="media">';
      if ($ibid_avatar=avatar($lastava))
         echo '
         <div class="media-left media-middle">
            <img src="'.$ibid_avatar.'" class=" media-object n-ava img-thumbnail" alt="avatar" />
         </div>';
      echo '
         <div class="media-body ml-2">
         '.translate("Greetings to our latest registered user:").' <br /><h4 class="media-heading"><a href="user.php?op=userinfo&amp;uname='.$lastuser.'">'.$lastuser.'</a></h4>
         </div>
      </div>
      <hr />';
      }
      echo '
      <div class="card card-block">
         <p>';
      alpha();
      echo '</p>';
      SortLinks($letter);
      echo '
      </div>';

      $min = $pagesize * ($page - 1);
      $max = $pagesize;
      $ws_req='';
      if (isset($uid_from_ws) and ($uid_from_ws!='')) $ws_req= 'WHERE uid REGEXP \''.$uid_from_ws.'\' ';
      $count = "SELECT COUNT(uid) AS total FROM ".$NPDS_Prefix."users ";
      $select = "SELECT uid, name, uname, femail, url, user_regdate, user_from, email, is_visible, user_viewemail, user_avatar, mns, user_lastvisit FROM ".$NPDS_Prefix."users ";
      if (($letter != translate("Other")) AND ($letter != translate("All"))) {
         if ($admin and (preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i',$letter))) 
            $where = "WHERE uname LIKE '".$letter."%' OR email LIKE '%".strtolower($letter)."%'".str_replace ( 'WHERE',' AND',$ws_req );
         else
            $where = "WHERE uname LIKE '".$letter."%'".str_replace ( 'WHERE',' AND',$ws_req );
      } else if (($letter == translate("Other")) AND ($letter != translate("All"))) {
         $where = "WHERE uname REGEXP \"^\[1-9]\" ".str_replace ( 'WHERE',' AND',$ws_req );
      } else {
         $where = $ws_req;
      }
      global $member_invisible;
      if ($member_invisible) {
         if ($admin)
            $and='';
         else {
            if ($where)
               $and='AND is_visible=1 ';
            else
               $and='WHERE is_visible=1 ';
         }
      } else {
         $and='';
      }
      $sort = "ORDER BY $sortby";
      $limit = ' LIMIT '.$min.', '.$max;
      $count_result = sql_query($count.$where);
      list($num_rows_per_order) = sql_fetch_row($count_result);
      $result = sql_query($select.$where.$and.$sort.$limit);
      echo '<br />';
      if ( $letter != 'front' ) {
         echo '
         <table data-toggle="table" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa" >
            <thead>
               <tr>
                  <th data-halign="center" data-align="center" class="text-muted"><i class="fa fa-share-alt fa-lg"></i></th>
                  <th data-sortable="true">'.translate("Nickname").'<br /> '.translate("Identity").'</th>';
         if ($sortby!="user_from ASC") {
            echo '
                  <th data-sortable="true" data-halign="center">'.translate("Email").'</th>';
         } else {
            echo '
                  <th data-sortable="true" data-halign="center" >'.translate("Location").'</th>';
         }
         echo '
                  <th data-halign="center">'.translate("URL").'</th>';
         $cols = 6;
         if ($admin) {
            $cols = 7;
            echo '<th data-halign="center" data-align="right">'.translate("Functions").'</th>';
         }
         echo '
               </tr>
            </thead>
            <tbody>';
         $a = 0;
         $num_users = sql_num_rows($result);
         $my_rsos=array();
         if ( $num_rows_per_order > 0 ) {
            global $anonymous, $user;
            while($temp_user = sql_fetch_assoc($result) ) {
               $socialnetworks=array(); $posterdata_extend=array();$res_id=array();$my_rs='';
               if (!$short_user) {
                  $posterdata_extend = get_userdata_extend_from_id($temp_user['uid']);
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

               $useroutils = '';
               if ($temp_user['uid']!= 1 and $temp_user['uid']!='') {
                  $useroutils .= '<a class="list-group-item text-primary" href="user.php?op=userinfo&amp;uname='.$temp_user['uname'].'" target="_blank" title="'.translate("Profile").'" ><i class="fa fa-2x fa-user"></i><span class="ml-3 hidden-sm-down">'.translate("Profile").'</span></a>';
               }
               if ($temp_user['uid']!= 1 and $temp_user['uid']!='') {
                  $useroutils .= '<a class="list-group-item text-primary" href="powerpack.php?op=instant_message&amp;to_userid='.urlencode($temp_user['uname']).'" title="'.translate("Send internal Message").'" ><i class="fa fa-2x fa-envelope-o"></i><span class="ml-3 hidden-sm-down">'.translate("Send internal Message").'</span></a>';
               }
               if ($temp_user['femail']!='') {
                  $useroutils .= '<a class="list-group-item text-primary" href="mailto:'.anti_spam($temp_user['femail'],1).'" target="_blank" title="'.translate("Email").'" ><i class="fa fa-at fa-2x"></i><span class="ml-3 hidden-sm-down">'.translate("Email").'</span></a>';
               }
               if ($temp_user['url']!='') {
                  if (strstr('http://', $temp_user['url']))
                     $temp_user['url'] = 'http://' . $temp_user['url'];
                  $useroutils .= '<a class="list-group-item text-primary" href="'.$temp_user['url'].'" target="_blank" title="'.translate("Visit this Website").'"><i class="fa fa-2x fa-external-link"></i><span class="ml-3 hidden-sm-down">'.translate("Visit this Website").'</span></a>';
               }
               if ($temp_user['mns']) {
                   $useroutils .= '<a class="list-group-item text-primary" href="minisite.php?op='.$temp_user['uname'].'" target="_blank" target="_blank" title="'.translate("Visit the Mini Web Site !").'" ><i class="fa fa-2x fa-desktop"></i><span class="ml-3 hidden-sm-down">'.translate("Visit the Mini Web Site !").'</span></a>';
               }
               if ($user and $temp_user['uid']!= 1) {
                  $useroutils .= '<a class="list-group-item text-primary" href="memberslist.php?letter='.$letter.'&amp;sortby='.$sortby.'&amp;list='.$list.urlencode($temp_user['uname']).',&amp;page='.$page.'&amp;gr_from_ws='.$gr_from_ws.'" title="'.translate("Add to mailing list").'" ><i class="fa fa-plus-circle fa-2x">&nbsp;</i><span class="ml-3 hidden-sm-down">'.translate("Add to mailing list").'</span></a>';
               }
               if ($posterdata_extend['C7'] !='') {
                  $useroutils .= '<a class="list-group-item text-primary" href="modules.php?ModPath=geoloc&ModStart=geoloc" title="'.translate("Location").'" ><i class="fa fa-map-marker fa-2x">&nbsp;</i><span class="ml-3 hidden-sm-down">'.translate("Location").'</span></a>';
               }
               $op_result = sql_query("SELECT open FROM ".$NPDS_Prefix."users_status WHERE uid='".$temp_user['uid']."'");
               list($open_user) = sql_fetch_row($op_result);
               $clnoconnect ='';
               if ( ($open_user==1 and $user) || ($admin) ) {
                  if ($open_user==0) {
                     $clconnect ='danger';
                     echo '
               <tr class="table-danger" title="'.translate("Connection not allowed").'" data-toggle="tooltip">
                  <td title="'.translate("Connection not allowed").'" data-toggle="tooltip">';
                  }
                  else  {
                     $clconnect ='primary';
                     echo '
               <tr>
                  <td>';
                  }
               if ($ibid_avatar=avatar($temp_user['user_avatar']))
               echo '<a tabindex="0" data-toggle="popover" data-trigger="focus" data-html="true" data-title="<h4>'.$temp_user['uname'].'</h4>" data-content=\'<div class="list-group">'.$useroutils.'</div><hr />'.$my_rsos[$a].'\'></i><img data-html="true" title="" data-toggle="tooltip" class=" btn-outline-'.$clconnect.' img-thumbnail img-fluid n-ava-small" src="'.$ibid_avatar.'" alt="'.$temp_user['uname'].'" /></a>
                  </td>
                  <td><a href="user.php?op=userinfo&amp;uname='.$temp_user['uname'].'" title="'.date(translate("dateinternal"),$temp_user['user_regdate']);
               if ($admin and $temp_user['user_lastvisit']!='') {
                  echo ' => '.date(translate("dateinternal"),$temp_user['user_lastvisit']);
               }
               echo '" data-toggle="tooltip">'.$temp_user['uname'].'</a>
               <br />'.$temp_user['name'].'
                  </td>';
               if ($sortby!='user_from ASC') {
                  if ($admin) {
                     echo '
                  <td>'.preg_anti_spam($temp_user['email']).'</td>';
                  } else {
                     if ($temp_user['user_viewemail']) {
                        echo '
                  <td>'.preg_anti_spam($temp_user['email']).'</td>';
                     } else {
                        echo '
                  <td>'.substr($temp_user['femail'],0,strpos($temp_user['femail'],"@")).'</td>';
                     }
                  }
               } else {
                  echo '
                  <td>'.$temp_user['user_from'].'&nbsp;</td>';
               }
               echo '
                  <td>';
               if($temp_user['url']!='')
                  echo '<a href="'.$temp_user['url'].'" target="_blank">'.$temp_user['url'].'</a>';
               echo '</td>';
               if ($admin) {
                  echo '
                  <td>
                     <a class="mr-3" href="admin.php?chng_uid='.$temp_user['uid'].'&amp;op=modifyUser" ><i class="fa fa-edit fa-lg" title="'.translate("Edit").'" data-toggle="tooltip"></i></a> 
                     <a href="admin.php?op=delUser&amp;chng_uid='.$temp_user['uid'].'" ><i class="fa fa-trash-o fa-lg text-danger" title="'.translate("Delete").'" data-toggle="tooltip"></i></a>';
                  if (!$temp_user['is_visible']) {
                     echo '<img src="images/admin/ws/user_invisible.gif" alt="'.translate("Invisible' member").'" title="'.translate("Invisible' member").'" />';
                  } else {
                     echo '<img src="images/admin/ws/blank.gif" alt="" />';
                  }
                  echo '
                  </td>';
               }
               echo '
            </tr>';
            $a++;
            }
            }
         } else {
            echo '
            <tr>
               <td colspan="'.$cols.'"><strong>'.translate("No Members Found for").' '.$letter.'</strong></td>
            </tr>';
         }
         echo '
         </tbody>
      </table>';

            if ($user) {
               echo '
      <div class="mt-3 card card-block-small"><p class=""><strong>'.translate("Mailing list").' :</strong>&nbsp;';
               if ($list) {
               echo urldecode($list);
                  echo '
                  <span class="float-right">
                     <a href="replypmsg.php?send='.substr($list,0,strlen($list)-3).'" ><i class="fa fa-envelope-o fa-lg" title="'.translate("Write to the list").'" data-toggle="tooltip" ></i></a>
                     <a class="ml-3" href="memberslist.php?letter='.$letter.'&amp;sortby='.$sortby.'&amp;page='.$page.'&amp;gr_from_ws='.$gr_from_ws.'" ><i class="fa fa-trash-o fa-lg text-danger" title="'.translate("RAZ member's list").'" data-toggle="tooltip" ></i></a>
                  </span>';
               }
               echo '</p>
      </div>';
            }
            settype($total_pages,'integer');
            if ( $num_rows_per_order > $pagesize ) {
               echo "<p class=\"lead\">$num_rows_per_order ".translate("users found for")." <strong>$letter</strong> ($total_pages ".translate("pages").", $num_users ".translate("users shown").").</p>";
               echo '
               <ul class="pagination pagination-sm">';
               $total_pages = ceil($num_rows_per_order / $pagesize);
               $prev_page = $page - 1;
               if ( $prev_page > 0 ) {
                  echo '
                  <li class="page-item"><a class="page-link" href="memberslist.php?letter='.$letter.'&amp;sortby='.$sortby.'&amp;list='.$list.'&amp;page='.$prev_page.'&amp;gr_from_ws='.$gr_from_ws.'"><i class="fa fa-angle-double-left fa-lg"></i></a></li>';
               }
               $next_page = $page + 1;
               if ( $next_page <= $total_pages ) {
                  echo '
                  <li class="page-item"><a class="page-link" href="memberslist.php?letter='.$letter.'&amp;sortby='.$sortby.'&amp;list='.$list.'&amp;page='.$next_page.'&amp;gr_from_ws='.$gr_from_ws.'"><i class="fa fa-angle-double-right fa-lg"></i></a></li>';
               }
               for($n=1; $n < $total_pages; $n++) {
                  if ($n == $page) {
                     echo '
                  <li class="page-item active"><a class="page-link" href="#">'.$n.'</a></li>';
                  } else {
                     echo '
                  <li class="page-item"><a class="page-link" href="memberslist.php?letter='.$letter.'&amp;sortby='.$sortby.'&amp;list='.$list.'&amp;page='.$n.'&amp;gr_from_ws='.$gr_from_ws.'">'.$n.'</a></li>';
                  }
                  if ($n >= 22) {  // if more than 20 pages are required, break it at 22.
                     $break = true;
                     break;
                  }
               }
               if (!isset($break)) {
                  if ($n == $page) {
                     echo '
                  <li class="page-item active"><a class="page-link" href="#">'.$n.'</a></li>';
                  } else {
                     echo '
                  <li class="page-item"><a class="page-link" href="memberslist.php?letter='.$letter.'&amp;sortby='.$sortby.'&amp;list='.$list.'&amp;page='.$total_pages.'&amp;gr_from_ws='.$gr_from_ws.'">'.$n.'</a></li>';
                  }
               }
               echo '
               </ul>';
            } else {
               echo '<p class="mt-3 lead">'.$num_rows_per_order.' '.translate("users found").'</p>';
            }
      }
   include("footer.php");
?>
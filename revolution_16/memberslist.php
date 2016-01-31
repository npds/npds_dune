<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
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

// Make Member_list Private or not
if (!AutoReg()) { unset($user); }
if (($member_list==1) AND ($user=='') AND ($admin=='')) {
   Header("Location: index.php");
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
   
   $alphabet = array (translate("All"), "A","B","C","D","E","F","G","H","I","J","K","L","M",
                     "N","O","P","Q","R","S","T","U","V","W","X","Y","Z",translate("Other"));

   $num = count($alphabet) - 1;
   $counter = 0;
   while (list(, $ltr) = each($alphabet)) {
      echo "<a href=\"memberslist.php?letter=$ltr&amp;sortby=$sortby&amp;list=$list&amp;gr_from_ws=$gr_from_ws\">$ltr</a>";
   if ( $counter != $num ) {
         echo ' | ';
      }
      $counter++;
   }
   echo '
   <br /><br />
   <form action="memberslist.php" method="post">
      <div class="form-group">
         <label class="form-control-label col-sm-4" for="letter">'.translate("Search").' : </label>
         <div class="col-sm-8">
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

   if ($letter == "front") {
      $letter = translate("All");
   }
   $sort=false;
   echo "<p class=\"lead\">\n";
   echo translate("Sort by:")." ";
   if ($sortby == "uname ASC" OR !$sortby) {
      echo translate("nickname")."&nbsp;|&nbsp;";
      $sort=true;
   } else {
      echo "<a href=\"memberslist.php?letter=$letter&amp;sortby=uname%20ASC&amp;list=$list&amp;gr_from_ws=$gr_from_ws\">".translate("nickname")."</a>&nbsp;|&nbsp;";
   }
   if ($sortby == "name ASC") {
      echo translate("real name")."&nbsp;|&nbsp;";
      $sort=true;
   } else {
      echo "<a href=\"memberslist.php?letter=$letter&amp;sortby=name%20ASC&amp;list=$list&amp;gr_from_ws=$gr_from_ws\">".translate("real name")."</a>&nbsp;|&nbsp;";
   }
   if ($sortby == "user_avatar ASC") {
      echo translate("Avatar")."&nbsp;|&nbsp;";
      $sort=true;
   } else {
      echo "<a href=\"memberslist.php?letter=$letter&amp;sortby=user_avatar%20ASC&amp;list=$list&amp;gr_from_ws=$gr_from_ws\">".translate("Avatar")."</a>&nbsp;|&nbsp;";
   }
   if (($sortby == "femail ASC") or ($sortby == "email ASC")) {
      echo translate("Email")."&nbsp;|&nbsp;";
      $sort=true;
   } else {
      if ($admin) {
         echo "<a href=\"memberslist.php?letter=$letter&amp;sortby=email%20ASC&amp;list=$list&amp;gr_from_ws=$gr_from_ws\">".translate("Email")."</a>&nbsp;|&nbsp;";
      } else {
         echo "<a href=\"memberslist.php?letter=$letter&amp;sortby=femail%20ASC&amp;list=$list&amp;gr_from_ws=$gr_from_ws\">".translate("Email")."</a>&nbsp;|&nbsp;";
      }
   }
   if ($sortby == "user_from ASC") {
      echo translate("Location")."&nbsp;|&nbsp;";
      $sort=true;
   } else {
      echo "<a href=\"memberslist.php?letter=$letter&amp;sortby=user_from%20ASC&amp;list=$list&amp;gr_from_ws=$gr_from_ws\">".translate("Location")."</a>&nbsp;|&nbsp;";
   }
   if ($sortby == "url DESC") {
      echo translate("url")."&nbsp;|&nbsp;";
      $sort=true;
   } else {
      echo "<a href=\"memberslist.php?letter=$letter&amp;sortby=url%20DESC&amp;list=$list&amp;gr_from_ws=$gr_from_ws\">".translate("url")."</a>&nbsp;|&nbsp;";
   }
   if ($sortby == "mns DESC") {
      echo translate("Mini-Web site")."&nbsp;|&nbsp;";
      $sort=true;
   } else {
      echo "<a href=\"memberslist.php?letter=$letter&amp;sortby=mns%20DESC&amp;list=$list&amp;gr_from_ws=$gr_from_ws\">".translate("Mini-Web site")."</a>&nbsp;|&nbsp;";
   }
   if ($sortby == "uid DESC") {
      echo "I.D";
      $sort=true;
   } else {
      echo "<a href=\"memberslist.php?letter=$letter&amp;sortby=uid%20DESC&amp;list=$list&amp;gr_from_ws=$gr_from_ws\">I.D</a>";
   }
   if (!$sort) {$sortby="uname ASC";}

   echo "</p>\n";
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
   if (!isset($sortby)) { $sortby = "uid DESC"; }
   $sortby=removeHack($sortby);
   if (!isset($page)) { $page = 1; }

   if (isset($list)) {
      $tempo=unique(explode(",",$list));
      $list=urlencode(implode(",",$tempo));
   }

   $result = sql_query("SELECT uname FROM ".$NPDS_Prefix."users ORDER BY uid DESC limit 0,1");
   list($lastuser) = sql_fetch_row($result);
   
   echo'<h2><img src="images/admin/users.png" border="0" alt="'.translate("Members List").'" />'.translate("Members List");
   if (isset ($uid_from_ws) and ($uid_from_ws!="")) echo " ".translate("for group")." ".$gr_from_ws;
   echo '</h2>';

   if (!isset($gr_from_ws))
      echo "<p>".translate("Greetings to our latest registered user:")." <a href=\"user.php?op=userinfo&amp;uname=$lastuser\">$lastuser</a></p>";

   
      alpha();
      echo "<br />";

      SortLinks($letter);
      $min = $pagesize * ($page - 1);
      $max = $pagesize;
      $ws_req="";
      if (isset($uid_from_ws) and ($uid_from_ws!="")) $ws_req= 'WHERE uid REGEXP \''.$uid_from_ws.'\' ';
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
         $where = "$ws_req";
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
      $limit = " LIMIT ".$min.", ".$max;
      $count_result = sql_query($count.$where);
      list($num_rows_per_order) = sql_fetch_row($count_result);
      $result = sql_query($select.$where.$and.$sort.$limit);
      echo "<br />";
      if ( $letter != "front" ) {
         echo '
         <table data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa" >
            <thead>
               <tr>
                  <th>&nbsp;</th>
                  <th data-sortable="true">'.translate("Nickname").'</th>
                  <th>&nbsp;</th>
                  <th data-sortable="true">'.translate("Real Name").'</th>';
         if ($sortby!="user_from ASC") {
            echo '
                  <th data-sortable="true">'.translate("Email").'</th>';
         } else {
            echo '
                  <th data-sortable="true">'.translate("Location").'</th>';
         }
         echo '
                  <th>'.translate("URL").'</th>';
         $cols = 6;
         if ($admin) {
            $cols = 7;
            echo '<th>'.translate("Functions").'</th>';
         }
         echo '
               </tr>
            </thead>
            <tbody>';
         $a = 0;
         $num_users = sql_num_rows($result);
         if ( $num_rows_per_order > 0  ) {
            global $anonymous, $user;
            while($temp_user = sql_fetch_assoc($result) ) {
               if ($temp_user['mns']) {$mns="<a href=\"minisite.php?op=".$temp_user['uname']."\" title=\"".translate("Mini-Web site")."\" target=\"_blank\" title=\"".translate("Visit the Mini Web Site !")."\"><i class=\"fa fa-desktop fa-lg\"></i></a>&nbsp;";} else {$mns="<img src=\"images/admin/ws/blank.gif\" border=\"0\" />";}
               echo '
               <tr>
                  <td>';
               if ($ibid_avatar=avatar($temp_user['user_avatar']))
                  echo '<img src="'.$ibid_avatar.'" class="n-ava img-thumbnail" alt="avatar" />';
               else
                  echo "&nbsp;";
               echo "</td>\n";
               echo "<td><a href=\"user.php?op=userinfo&amp;uname=".$temp_user['uname']."\" title=\"".date(translate("dateinternal"),$temp_user['user_regdate']);
               if ($admin) 
                  echo " => ".date(translate("dateinternal"),$temp_user['user_lastvisit']);
               echo "\">".$temp_user['uname']."</a></td>\n";

               if ($temp_user['uname']!=$anonymous) {
                  if ($user) {
                     echo '
                  <td>'.$mns.'&nbsp;<a href="replypmsg.php?send='.urlencode($temp_user['uname']).'" title="'.translate("Send internal Message").'"><i class="fa fa-envelope-o fa-lg"></i></a>';
                     echo '&nbsp;<a href="memberslist.php?letter='.$letter.'&amp;sortby='.$sortby.'&amp;list='.$list.urlencode($temp_user['uname']).',&amp;page='.$page.'&amp;gr_from_ws='.$gr_from_ws.'" title="'.translate("Add to mailing list").'" >';
                     echo '<i class="fa fa-plus-circle fa-lg"></i></a></td>';
                  } else {
                     echo '
                  <td>'.$mns.'</td>';
                  }
               } else {
                  echo '
                  <td>&nbsp;</td>';
               }
               echo "<td>".$temp_user['name']."</td>\n";
               if ($sortby!="user_from ASC") {
                  if ($admin) {
                     echo "<td>".preg_anti_spam($temp_user['email'])."</td>\n";
                  } else {
                     if ($temp_user['user_viewemail']) {
                        echo "<td>".preg_anti_spam($temp_user['email'])."</td>\n";
                     } else {
                        echo "<td>".substr($temp_user['femail'],0,strpos($temp_user['femail'],"@"))."</td>\n";
                     }
                  }
               } else {
                  echo "<td>".$temp_user['user_from']."&nbsp;</td>\n";
               }
               echo "<td><a href=\"".$temp_user['url']."\" target=\"_blank\">".$temp_user['url']."</a></td>\n";
               if ($admin) {
                  echo "<td><a href=\"admin.php?chng_uid=".$temp_user['uid']."&amp;op=modifyUser\" title=\"".translate("Edit")." \"><i class=\"fa fa-pencil\"></i></a>";
                  echo "&nbsp;<a href=\"admin.php?op=delUser&amp;chng_uid=".$temp_user['uid']."\" title=\"".translate("Delete")."\"><i class=\"fa fa-trash-o\"></i></a>";
                  $op_result = sql_query("select open from ".$NPDS_Prefix."users_status where uid='".$temp_user['uid']."'");
                  list($open_user) = sql_fetch_row($op_result);
                  if ($open_user==1) {
                     echo "&nbsp;<i class=\"fa fa-chain\" title=\"".translate("Connection allowed")."\"></i>";
                  } else {
                     echo "&nbsp;<i class=\"fa fa-chain-broken\" title=\"".translate("Connection not allowed")."\"></i>"; 
                  }
                  if (!$temp_user['is_visible']) {
                     echo "<img src=\"images/admin/ws/user_invisible.gif\" border=\"0\" alt=\"".translate("Invisible' member")."\" title=\"".translate("Invisible' member")." \" /></td>\n";
                  } else {
                     echo "<img src=\"images/admin/ws/blank.gif\" border=\"0\" /></td>\n";
                  }
               }
               echo '
            </tr>';
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
               echo '<br /><p class="lead">'.translate("Mailing list").' : '.urldecode($list).'&nbsp;';
               echo "<a href=\"memberslist.php?letter=$letter&amp;sortby=$sortby&amp;page=$page&amp;gr_from_ws=$gr_from_ws\" title=\"".translate("RAZ member's list")."\"><i class=\"fa fa-lg fa-trash-o\"></i></a>";
               if ($list) {
                  echo "<a href=\"replypmsg.php?send=".substr($list,0,strlen($list)-3)."\" title=\"".translate("Write to the list")."\"><i class=\"fa fa-large fa-envelope\"></i></a>";
               }
               echo '</p>';
            }

            if ( $num_rows_per_order > $pagesize ) {
               echo "<p class=\"lead\">$num_rows_per_order ".translate("users found for")." <strong>$letter</strong> ($total_pages ".translate("pages").", $num_users ".translate("users shown").").</p>";
               echo '
               <ul class="pagination pagination-sm">';
               $total_pages = ceil($num_rows_per_order / $pagesize); // How many pages are we dealing with here ??
               $prev_page = $page - 1;
               if ( $prev_page > 0 ) {
                  echo '<li class="page-item"><a class="page-link" href="memberslist.php?letter='.$letter.'&amp;sortby='.$sortby.'&amp;list='.$list.'&amp;page='.$prev_page.'&amp;gr_from_ws='.$gr_from_ws.'">';
                  echo '<i class="fa fa-arrow-left"></i></a></li>';
               }
               $next_page = $page + 1;
               if ( $next_page <= $total_pages ) {
                  echo '<li class="page-item"><a class="page-link" href="memberslist.php?letter='.$letter.'&amp;sortby='.$sortby.'&amp;list='.$list.'&amp;page='.$next_page.'&amp;gr_from_ws='.$gr_from_ws.'">';
                  echo '<i class="fa fa-arrow-right"></i></a></li>';
               }
               for($n=1; $n < $total_pages; $n++) {
                  if ($n == $page) {
                     echo '<li class="page-item active"><a class="page-link" href="#">'.$n.'</a></li>';
                  } else {
                     echo '<li class="page-item"><a class="page-link" href="memberslist.php?letter='.$letter.'&amp;sortby='.$sortby.'&amp;list='.$list.'&amp;page='.$n.'&amp;gr_from_ws='.$gr_from_ws.'">'.$n.'</a></li>';
                  }
                  if ($n >= 22) {  // if more than 20 pages are required, break it at 22.
                     $break = true;
                     break;
                  }
               }
               if (!isset($break)) {
                  if ($n == $page) {
                     echo '<li class="page-item active"><a class="page-link" href="#">'.$n.'</a></li>';
                  } else {
                     echo '<li class="page-item"><a class="page-link" href="memberslist.php?letter='.$letter.'&amp;sortby='.$sortby.'&amp;list='.$list.'&amp;page='.$total_pages.'&amp;gr_from_ws='.$gr_from_ws.'">'.$n.'</a></li>';
                  }
               }
            } else {
               echo '<br /><p class="lead">'.$num_rows_per_order.' '.translate("users found").'</p>';
            }
      }
   include("footer.php");
?>
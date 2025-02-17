<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion"))
   header("location: index.php");

#autodoc Form_instant_message($to_userid) : Ouvre la page d'envoi d'un MI (Message Interne)
function Form_instant_message($to_userid) {
   include ("header.php");
   write_short_private_message(removeHack($to_userid));
   include ("footer.php");
}

#autodoc online_members () : liste des membres connect&eacute;s <br /> Retourne un tableau dont la position 0 est le nombre, puis la liste des username | time <br />Appel : $xx=online_members(); puis $xx[x]['username'] $xx[x]['time'] ...
function online_members () {
   global $NPDS_Prefix;

   $result = sql_query("SELECT username, guest, time FROM ".$NPDS_Prefix."session WHERE guest='0' ORDER BY username ASC");
   $i=0;
   $members_online[$i]=sql_num_rows($result);
   while ($session = sql_fetch_assoc($result)) {
      if (isset($session['guest']) and $session['guest'] == 0) {
         $i++;
         $members_online[$i]['username'] = $session['username'];
         $members_online[$i]['time'] = $session['time'];
      }
   }
   return $members_online;
}

#autodoc writeDB_private_message($to_userid,$image,$subject,$from_userid,$message, $copie) : Insère un MI dans la base et le cas échéant envoi un mail
function writeDB_private_message($to_userid,$image,$subject,$from_userid,$message, $copie) {
   global $NPDS_Prefix;

   $res = sql_query("SELECT uid, user_langue FROM ".$NPDS_Prefix."users WHERE uname='$to_userid'");
   list($to_useridx, $user_languex) = sql_fetch_row($res);

   if ($to_useridx == '')
      forumerror('0016');
   else {
      $time = getPartOfTime(time(), 'yyyy-MM-dd H:mm:ss');
      include_once("language/lang-multi.php");
      $subject=removeHack($subject);
      $message=str_replace("\n","<br />", $message);
      $message=addslashes(removeHack($message));
      $sql = "INSERT INTO ".$NPDS_Prefix."priv_msgs (msg_image, subject, from_userid, to_userid, msg_time, msg_text) ";
      $sql .= "VALUES ('$image', '$subject', '$from_userid', '$to_useridx', '$time', '$message')";
      if (!$result = sql_query($sql))
         forumerror('0020');
      if ($copie) {
         $sql = "INSERT INTO ".$NPDS_Prefix."priv_msgs (msg_image, subject, from_userid, to_userid, msg_time, msg_text, type_msg, read_msg) ";
         $sql .= "VALUES ('$image', '$subject', '$from_userid', '$to_useridx', '$time', '$message', '1', '1')";
         if (!$result = sql_query($sql))
            forumerror('0020');
      }
      global $subscribe, $nuke_url, $sitename;
      if ($subscribe) {
         $sujet=html_entity_decode(translate_ml($user_languex, "Notification message privé."),ENT_COMPAT | ENT_HTML401,'UTF-8').'['.$from_userid.'] / '.$sitename;
         $message = $time.'<br />'.translate_ml($user_languex, "Bonjour").'<br />'.translate_ml($user_languex, "Vous avez un nouveau message.").'<br /><br /><b>'.$subject.'</b><br /><br /><a href="'.$nuke_url.'/viewpmsg.php">'.translate_ml($user_languex, "Cliquez ici pour lire votre nouveau message.").'</a><br />';
         include("signat.php");
         copy_to_email($to_useridx,$sujet,stripslashes($message));
      }
   }
}

#autodoc write_short_private_message($to_userid) : Formulaire d'écriture d'un MI
function write_short_private_message($to_userid) {
   echo '
   <h2>'.translate("Message à un membre").'</h2>
   <h3><i class="fa fa-at me-1"></i>'.$to_userid.'</h3>
   <form id="sh_priv_mess" action="powerpack.php" method="post">
      <div class="mb-3 row">
         <label class="col-form-label col-sm-12" for="subject" >'.translate("Sujet").'</label>
         <div class="col-sm-12">
            <input class="form-control" type="text" id="subject" name="subject" maxlength="100" />
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-12" for="message" >'.translate("Message").'</label>
         <div class="col-sm-12">
            <textarea class="form-control"  id="message" name="message" rows="10"></textarea>
         </div>
      </div>
      <div class="mb-3 row">
         <div class="col-sm-12">
            <div class="form-check" >
               <input class="form-check-input" type="checkbox" id="copie" name="copie" />
               <label class="form-check-label" for="copie">'.translate("Conserver une copie").'</label>
            </div>
         </div>
      </div>
      <div class="mb-3 row">
         <input type="hidden" name="to_userid" value="'.$to_userid.'" />
         <input type="hidden" name="op" value="write_instant_message" />
         <div class="col-sm-12">
            <input class="btn btn-primary" type="submit" name="submit" value="'.translate("Valider").'" accesskey="s" />&nbsp;
            <button class="btn btn-secondary" type="reset">'.translate("Annuler").'</button>
         </div>
      </div>
   </form>';
}

#autodoc if_chat() : Retourne le nombre de connecté au Chat
function if_chat($pour) {
   global $NPDS_Prefix;
   $auto=autorisation_block("params#".$pour);
   $dimauto=count($auto);
   $numofchatters=0;

   if ($dimauto<=1) {
      $result=sql_query("SELECT DISTINCT ip FROM ".$NPDS_Prefix."chatbox WHERE id='".$auto[0]."' AND date >= ".(time()-(60*3))."");
      $numofchatters=sql_num_rows($result);
   }
   return ($numofchatters);
}

#autodoc insertChat($username, $message, $dbname, $id) : Insère un record dans la table Chat / on utilise id pour filtrer les messages - id = l'id du groupe
function insertChat($username, $message, $dbname,$id) {
   global $NPDS_Prefix;
   if ($message!='') {
      $username = removeHack(stripslashes(FixQuotes(strip_tags(trim($username)))));
      $message =  removeHack(stripslashes(FixQuotes(strip_tags(trim($message)))));
      $ip = getip();
      settype($id, 'integer');
      settype($dbname, 'integer');
      $result = sql_query("INSERT INTO ".$NPDS_Prefix."chatbox VALUES ('".$username."', '".$ip."', '".$message."', '".time()."', '$id', ".$dbname.")");
   }
}

#autodoc JavaPopUp($F,$T,$W,$H) : Personnalise une ouverture de fenêtre (popup)
function JavaPopUp($F,$T,$W,$H) {
   // 01.feb.2002 by GaWax
   if ($T=="") $T="@ ".time()." ";
   $PopUp = "'$F','$T','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=$H,width=$W,toolbar=no,scrollbars=yes,resizable=yes'";
   return $PopUp;
}

#autodoc:<Powerpack_f.php>
#autodoc <span class="text-success">BLOCS NPDS</span>:
#autodoc instant_members_message() : Bloc MI (Message Interne) <br />=> syntaxe : function#instant_members_message
function instant_members_message() {
   global $user, $admin, $long_chain, $NPDS_Prefix;
   settype($boxstuff,'string');
   if (!$long_chain) $long_chain=13;

   global $block_title;
   if ($block_title=='')
      $block_title=translate("M2M bloc");

   if ($user) {
      global $cookie;
      $boxstuff='
                              <ul>';
      $ibid=online_members();
      $rank1='';
      for ($i = 1; $i <= $ibid[0]; $i++) {
         $timex=time()-$ibid[$i]['time'];
         if ($timex>=60)
            $timex='<i class="fa fa-plug text-body-secondary" title="'.$ibid[$i]['username'].' '.translate("n'est pas connecté").'" data-bs-toggle="tooltip" data-bs-placement="right"></i>&nbsp;';
         else
            $timex='<i class="fa fa-plug faa-flash animated text-primary" title="'.$ibid[$i]['username'].' '.translate("est connecté").'" data-bs-toggle="tooltip" data-bs-placement="right" ></i>&nbsp;';
         global $member_invisible;
         if ($member_invisible) {
            if ($admin)
               $and='';
            else
               $and = ($ibid[$i]['username']==$cookie[1]) ? '' : 'AND is_visible=1' ;
         } else
            $and='';
         $result=sql_query("SELECT uid FROM ".$NPDS_Prefix."users WHERE uname='".$ibid[$i]['username']."' $and");
         list($userid)=sql_fetch_row($result);
         if ($userid) {
            $rowQ1=Q_Select("SELECT rang FROM ".$NPDS_Prefix."users_status WHERE uid='$userid'", 3600);
            $myrow=$rowQ1[0];
            $rank=$myrow['rang'];
            $tmpR='';
            if ($rank) {
               if ($rank1=='') {
                  if ($rowQ2 = Q_Select("SELECT rank1, rank2, rank3, rank4, rank5 FROM ".$NPDS_Prefix."config",86400)) {
                     $myrow=$rowQ2[0];
                     $rank1 = $myrow['rank1'];
                     $rank2 = $myrow['rank2'];
                     $rank3 = $myrow['rank3'];
                     $rank4 = $myrow['rank4'];
                     $rank5 = $myrow['rank5'];
                  }
               }
               if ($ibidR=theme_image("forum/rank/".$rank.".gif")) {$imgtmpA=$ibidR;} else {$imgtmpA="images/forum/rank/".$rank.".gif";}
               $messR='rank'.$rank;
               $tmpR="<img src=\"".$imgtmpA."\" border=\"0\" alt=\"".aff_langue($$messR)."\" title=\"".aff_langue($$messR)."\" loading=\"lazy\" />";
            } else
               $tmpR='&nbsp;';
            $new_messages = sql_num_rows(sql_query("SELECT msg_id FROM ".$NPDS_Prefix."priv_msgs WHERE to_userid = '$userid' AND read_msg='0' AND type_msg='0'"));
            if ($new_messages>0) {
               $PopUp=JavaPopUp("readpmsg_imm.php?op=new_msg","IMM",600,500);
               $PopUp="<a href=\"javascript:void(0);\" onclick=\"window.open($PopUp);\">";
               $icon = ($ibid[$i]['username']==$cookie[1]) ? $PopUp : '' ;
               $icon.='<i class="fa fa-envelope fa-lg faa-shake animated" title="'.translate("Nouveau").'<span class=\'px-2 rounded-pill bg-danger ms-2\'>'.$new_messages.'</span>" data-bs-html="true" data-bs-toggle="tooltip"></i>';
               if ($ibid[$i]['username']==$cookie[1]) {$icon.='</a>';}
            } else {
               $messages = sql_num_rows(sql_query("SELECT msg_id FROM ".$NPDS_Prefix."priv_msgs WHERE to_userid = '$userid' AND type_msg='0' AND dossier='...'"));
               if ($messages>0) {
                  $PopUp=JavaPopUp("readpmsg_imm.php?op=msg","IMM",600,500);
                  $PopUp='<a href="javascript:void(0);" onclick="window.open('.$PopUp.');">';
                  $icon = ($ibid[$i]['username']==$cookie[1]) ? $PopUp : '' ;
                  $icon.='<i class="far fa-envelope-open fa-lg " title="'.translate("Nouveau").' : '.$new_messages.'" data-bs-toggle="tooltip"></i></a>';
               } else
               $icon='&nbsp;';
            }
            $N = $ibid[$i]['username'];
            $M = (strlen($N)>$long_chain) ? substr($N,0,$long_chain).'.' : $N ;
            $boxstuff .='
                                 <li class="my-2">'.$timex.'&nbsp;<a href="powerpack.php?op=instant_message&amp;to_userid='.$N.'" title="'.translate("Envoyer un message interne").'" data-bs-toggle="tooltip" >'.$M.'</a><span class="float-end">'.$icon.'</span></li>';
         }//suppression temporaire ... rank  '.$tmpR.'
      }
      $boxstuff .='
                              </ul>
                           ';
      themesidebox($block_title, $boxstuff);
   } else {
      if ($admin) {
         $ibid=online_members();
         if ($ibid[0]) {
            for ($i = 1; $i <= $ibid[0]; $i++) {
               $N = $ibid[$i]['username'];
               $M = strlen($N)>$long_chain ? substr($N,0,$long_chain).'.' : $N ;
               $boxstuff .= $M.'<br />';
            }
            themesidebox('<i>'.$block_title.'</i>', $boxstuff);
         }
      }
   }
}

#autodoc makeChatBox($pour) : Bloc ChatBox <br />=> syntaxe : function#makeChatBox <br />params#chat_membres <br /> le parametre doit être en accord avec l'autorisation donc (chat_membres, chat_tous, chat_admin, chat_anonyme)
function makeChatBox($pour) {
   global $user, $admin, $member_list, $long_chain, $NPDS_Prefix;
   include_once('functions.php');
   $auto=autorisation_block('params#'.$pour);
   $dimauto=count($auto);

   if (!$long_chain) $long_chain=12;
   $thing=''; $une_ligne=false;

   if ($dimauto<=1) {
      $counter=sql_num_rows(sql_query("SELECT message FROM ".$NPDS_Prefix."chatbox WHERE id='".$auto[0]."'"))-6;
      if ($counter<0) $counter=0;
      $result=sql_query("SELECT username, message, dbname FROM ".$NPDS_Prefix."chatbox WHERE id='".$auto[0]."' ORDER BY date ASC LIMIT $counter,6");
      if ($result) {
         while (list($username, $message, $dbname) = sql_fetch_row($result)) {
            if (isset($username)) {
               if ($dbname==1) {
                  $thing.= ((!$user) and ($member_list==1) and (!$admin)) ?
                     '<span class="">'.substr($username,0,8).'.</span>' :
                     "<a href=\"user.php?op=userinfo&amp;uname=$username\">".substr($username,0,8).".</a>" ;
               } else
                  $thing.='<span class="">'.substr($username,0,8).'.</span>';
            }
            $une_ligne=true;
            $thing.= (strlen($message)>$long_chain)  ?
               "&gt;&nbsp;<span>".smilie(stripslashes(substr($message,0,$long_chain)))." </span><br />\n" :
               "&gt;&nbsp;<span>".smilie(stripslashes($message))." </span><br />\n" ;
         }
      }
      $PopUp = JavaPopUp("chat.php?id=".$auto[0]."&amp;auto=".encrypt(serialize($auto[0])),"chat".$auto[0],380,480);
      if ($une_ligne) $thing.='<hr />';
      $result=sql_query("SELECT DISTINCT ip FROM ".$NPDS_Prefix."chatbox WHERE id='".$auto[0]."' AND date >= ".(time()-(60*2))."");
      $numofchatters = sql_num_rows($result);
      $thing.= $numofchatters > 0 ?
         '<div class="d-flex"><a id="'.$pour.'_encours" class="fs-4" href="javascript:void(0);" onclick="window.open('.$PopUp.');" title="'.translate("Cliquez ici pour entrer").' '.$pour.'" data-bs-toggle="tooltip" data-bs-placement="right"><i class="fa fa-comments fa-2x nav-link faa-pulse animated faa-slow"></i></a><span class="badge rounded-pill bg-primary ms-auto align-self-center" title="'.translate("personne connectée.").'" data-bs-toggle="tooltip">'.$numofchatters.'</span></div>' :
         '<div><a id="'.$pour.'" href="javascript:void(0);" onclick="window.open('.$PopUp.');" title="'.translate("Cliquez ici pour entrer").'" data-bs-toggle="tooltip" data-bs-placement="right"><i class="fa fa-comments fa-2x "></i></a></div>' ;
   } else {
      if (count($auto)>1) {
         $numofchatters=0;
         $thing.='<ul>';
         foreach($auto as $autovalue) {
            $result=Q_select("SELECT groupe_id, groupe_name FROM ".$NPDS_Prefix."groupes WHERE groupe_id='$autovalue'",3600);
            $autovalueX = $result[0];
            $PopUp = JavaPopUp("chat.php?id=".$autovalueX['groupe_id']."&auto=".encrypt(serialize($autovalueX['groupe_id'])),"chat".$autovalueX['groupe_id'],380,480);
            $thing.="<li><a href=\"javascript:void(0);\" onclick=\"window.open($PopUp);\">".$autovalueX['groupe_name']."</a>";

            $result=sql_query("SELECT DISTINCT ip FROM ".$NPDS_Prefix."chatbox WHERE id='".$autovalueX['groupe_id']."' AND date >= ".(time()-(60*3))."");
            $numofchatters=sql_num_rows($result);
            if ($numofchatters) $thing.='&nbsp;(<span class="text-danger"><b>'.sql_num_rows($result).'</b></span>)';
            echo '</li>';
         }
         $thing.='</ul>';
      }
   }
   global $block_title;
   if ($block_title=='')
      $block_title=translate("Bloc Chat");
   themesidebox($block_title, $thing);
   sql_free_result($result);
}

#autodoc RecentForumPosts($title, $maxforums, $maxtopics, $dposter, $topicmaxchars,$hr,$decoration) : Bloc Forums <br />=> syntaxe :<br />function#RecentForumPosts<br />params#titre, nb_max_forum (O=tous), nb_max_topic, affiche_l'emetteur(true / false), topic_nb_max_char, affiche_HR(true / false),
function RecentForumPosts($title, $maxforums, $maxtopics, $displayposter=false, $topicmaxchars=15,$hr=false, $decoration='') {
   $boxstuff=RecentForumPosts_fab($title, $maxforums, $maxtopics, $displayposter, $topicmaxchars, $hr, $decoration);
   global $block_title;
   if ($title=='')
      $title = $block_title=='' ? translate("Forums infos") : $block_title ;
   themesidebox($title, $boxstuff);
}

function RecentForumPosts_fab($title, $maxforums, $maxtopics, $displayposter, $topicmaxchars, $hr,$decoration) {
   global $parse, $user, $NPDS_Prefix;

   $topics = 0;
   settype($maxforums,"integer");
   settype($maxtopics,"integer");

   $lim = $maxforums==0 ? '' : " LIMIT $maxforums";
   $query = $user ?
     "SELECT * FROM ".$NPDS_Prefix."forums ORDER BY cat_id,forum_index,forum_id".$lim :
     "SELECT * FROM ".$NPDS_Prefix."forums WHERE forum_type!='9' AND forum_type!='7' AND forum_type!='5' ORDER BY cat_id,forum_index,forum_id".$lim;
   $result = sql_query($query);

   if (!$result) exit();
   $boxstuff = '
                              <ul>';

   while ($row = sql_fetch_row($result)) {
      if (($row[6] == "5") or ($row[6] == "7")) {
         $ok_affich=false;
         $tab_groupe=valid_group($user);
         $ok_affich=groupe_forum($row[7], $tab_groupe);
      } else
         $ok_affich=true;
      if ($ok_affich) {
         $forumid = $row[0];
         $forumname = $row[1];
         $forum_desc =$row[2];
         if ($hr)
            $boxstuff .= '
                                 <li><hr /></li>';
         if ($parse==0) {
            $forumname = FixQuotes($forumname);
            $forum_desc = FixQuotes($forum_desc);
         } else {
            $forumname = stripslashes($forumname);
            $forum_desc = stripslashes($forum_desc);
         }

         $res = sql_query("SELECT * FROM ".$NPDS_Prefix."forumtopics WHERE forum_id = '$forumid' ORDER BY topic_time DESC");
         $ibidx = sql_num_rows($res);
         $boxstuff .= '
                                 <li class="list-unstyled border-0 p-2 mt-1"><h6><a href="viewforum.php?forum='.$forumid.'" title="'.strip_tags($forum_desc).'" data-bs-toggle="tooltip">'.$forumname.'</a><span class="float-end badge bg-primary" title="'.translate("Sujets").'" data-bs-toggle="tooltip">'.$ibidx.'</span></h6></li>';

         $topics = 0;
         while(($topics < $maxtopics) && ($topicrow = sql_fetch_row($res))) {
            $topicid = $topicrow[0];
            $tt = $topictitle = $topicrow[1];
            $date = $topicrow[3];
            $replies = 0;
            $postquery = "SELECT COUNT(*) AS total FROM ".$NPDS_Prefix."posts WHERE topic_id = '$topicid'";
            if ($pres = sql_query($postquery)) {
               if ($myrow = sql_fetch_assoc($pres))
                  $replies = $myrow['total'];
            }
            if (strlen($topictitle) > $topicmaxchars) {
               $topictitle = substr($topictitle,0,$topicmaxchars);
               $topictitle .= '..';
            }

            if ($displayposter) {
               $posterid = $topicrow[2];
               $RowQ1=Q_Select ("SELECT uname FROM ".$NPDS_Prefix."users WHERE uid = '$posterid'",3600);
               $myrow=$RowQ1[0];
               $postername = $myrow['uname'];
            }
            if ($parse==0) {
              $tt =  strip_tags(FixQuotes($tt));
              $topictitle= FixQuotes($topictitle);
            } else {
               $tt =  strip_tags(stripslashes($tt));
               $topictitle= stripslashes($topictitle);
            }
            $boxstuff .= '
                                 <li class="list-group-item p-1 border-right-0 border-left-0 list-group-item-action"><div class="n-ellipses"><span class="badge bg-secondary mx-2" title="'.translate("Réponses").'" data-bs-toggle="tooltip" data-bs-placement="top">'.$replies.'</span><a href="viewtopic.php?topic='.$topicid.'&amp;forum='.$forumid.'" >'.$topictitle.'</a></div>';
            if ($displayposter) $boxstuff .= $decoration.'<span class="ms-1">'.$postername.'</span>';
            $boxstuff .= '</li>';
            $topics++;
         }
      }
    }
   $boxstuff .= '
                              </ul>
                           ';
   return ($boxstuff);
}
#autodoc:</Powerpack_f.php>
?>

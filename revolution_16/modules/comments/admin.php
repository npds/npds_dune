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
include('functions.php');
include('auth.php');

filtre_module($file_name);
if (file_exists("modules/comments/$file_name.conf.php")) {
   include ("modules/comments/$file_name.conf.php");
} else {
   die();
}

settype($forum,"integer");
if ($forum>=0)
   die();

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
// gestion des params du 'forum' : type, accès, modérateur ...

   if ($Mmod) {
      switch ($mode) {
            case 'del':
               $sql = "DELETE FROM ".$NPDS_Prefix."posts WHERE forum_id='$forum' AND topic_id = '$topic'";
               if (!$result = sql_query($sql))
                  forumerror('0009');
               // ordre de mise à jour d'un champ externe ?
               if ($comments_req_raz!='')
                  sql_query("UPDATE ".$NPDS_Prefix.$comments_req_raz);
               redirect_url("$url_ret");
               break;
            case 'viewip':
               include("header.php");
               $sql = "SELECT u.uname, p.poster_ip, p.poster_dns FROM ".$NPDS_Prefix."users u, posts p WHERE p.post_id = '$post' AND u.uid = p.poster_id";
               if (!$r = sql_query($sql))
                  forumerror('0013');
               if (!$m = sql_fetch_assoc($r))
                  forumerror('0014');
               echo '
      <h3>'.translate("Users IP and Account information").'</h3>
      <div class="card card-block">
         <strong>'.translate("Nickname: ").'</strong> '.$m['uname'].'<br />
         <strong>'.translate("User IP: ").'</strong> '.$m['poster_ip'].'<br />
         <strong>'.translate("User DNS: ").'</strong> '.$m['poster_dns'].'<br />
      </div>
      <p><a href="'.rawurldecode($url_ret).'" class="btn btn-primary">'.translate("Go Back").'</a></p>';
               include("footer.php");
               break;
            case 'aff':
               $sql = "UPDATE ".$NPDS_Prefix."posts SET post_aff = '$ordre' WHERE post_id = '$post'";
               sql_query($sql);

               // ordre de mise à jour d'un champ externe ?
               if ($ordre) {
                  if ($comments_req_add!='')
                     sql_query("UPDATE ".$NPDS_Prefix.$comments_req_add);
               } else {
                  if ($comments_req_del!='')
                     sql_query("UPDATE ".$NPDS_Prefix.$comments_req_del);
               }
               redirect_url("$url_ret");
               break;
         }
   } else {
      include("header.php");
      opentable();
      echo "<p align=\"center\">".translate("You are not the moderator of this forum therefor you cannot perform this function.")."<br /><br />";
      echo "<a href=\"javascript:history.go(-1)\" class=\"noir\">".translate("Go Back")."</a></p>";
      closetable();
      include("footer.php");
   }
?>

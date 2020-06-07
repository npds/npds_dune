<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/* Based on Parts of phpBB                                              */
/*                                                                      */
/* NPDS Copyright (c) 2002-2019 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion"))
   die();
include('functions.php');
include('auth.php');
include('modules/geoloc/geoloc_locip.php');

filtre_module($file_name);
if (file_exists("modules/comments/$file_name.conf.php"))
   include ("modules/comments/$file_name.conf.php");
else
   die();

settype($forum,'integer');
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
      <h2 class="mb-3">'.translate("Commentaire").'</h2>
      <div class="card mb-3">
         <div class="card-body">
            <h3 class="card-title mb-3">'.translate("Adresses IP et informations sur les utilisateurs").'</h3>
            <div class="row">
               <div class="col mb-3">
                  <span class="text-muted">'.translate("Identifiant : ").'</span> '.$m['uname'].'<br />
                  <span class="text-muted">'.translate("Adresse IP de l'utilisateur : ").'</span> '.$m['poster_ip'].'<br />
                  <span class="text-muted">'.translate("Adresse DNS de l'utilisateur : ").'</span> '.$m['poster_dns'].'<br />
               </div>';
               echo localiser_ip($iptoshow=$m['poster_ip']);
               echo '
            </div>
         </div>';
      include('modules/geoloc/geoloc_conf.php');
      if($geo_ip==1)
         echo'
         <div class="card-footer text-right">
            <a href="modules.php?ModPath=geoloc&amp;ModStart=geoloc&amp;op=allip"><span><i class=" fa fa-globe fa-lg mr-1"></i><i class=" fa fa-tv fa-lg mr-2"></i></span><span class="d-none d-sm-inline">Carte des IP</span></a>
         </div>';
      echo '
      </div>
      <p><a href="'.rawurldecode($url_ret).'" class="btn btn-secondary">'.translate("Retour en arrière").'</a></p>';
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
      echo '
      <p class="text-center">'.translate("Vous n'êtes pas identifié comme modérateur de ce forum. Opération interdite.").'<br /><br />
      <a href="javascript:history.go(-1)" class="btn btn-secondary">'.translate("Retour en arrière").'</a></p>';
      include("footer.php");
   }
?>

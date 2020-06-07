<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2019 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/*                                                                      */
/* Module core reseaux-sociaux                                          */
/* reseaux-sociaux     file 2015 by jpb                                 */
/*                                                                      */
/* version 1.0 17/02/2016                                               */
/************************************************************************/

if (strstr($ModPath,'..') || strstr($ModStart,'..') || stristr($ModPath, 'script') || stristr($ModPath, 'cookie') || stristr($ModPath, 'iframe') || stristr($ModPath, 'applet') || stristr($ModPath, 'object') || stristr($ModPath, 'meta') || stristr($ModStart, 'script') || stristr($ModStart, 'cookie') || stristr($ModStart, 'iframe') || stristr($ModStart, 'applet') || stristr($ModStart, 'object') || stristr($ModStart, 'meta'))
   die();

if (!function_exists("Mysql_Connexion"))
   include ("mainfile.php");

include ('functions.php');
if (!$user) header('location:index.php');

global $cookie, $language;
$userdata = get_userdata_from_id($cookie[0]);

$ModStart='reseaux-sociaux';
include ("modules/$ModPath/lang/rs-$language.php");

function ListReseaux($ModPath, $ModStart) {
   global $userdata;
   if (file_exists("modules/$ModPath/reseaux-sociaux.conf.php"))
      include ("modules/$ModPath/reseaux-sociaux.conf.php");
   include("header.php");

   echo '
   <h2>'.translate("Utilisateur").'</h2>
   <ul class="nav nav-tabs d-flex flex-wrap"> 
      <li class="nav-item"><a class="nav-link " href="user.php?op=edituser" title="'.translate("Vous").'" data-toggle="tooltip" ><i class="fa fa-user fa-2x d-xl-none"></i><span class="d-none d-xl-inline">&nbsp;'.translate("Vous").'</span></a></li>
      <li class="nav-item"><a class="nav-link " href="user.php?op=editjournal" title="'.translate("Editer votre journal").'" data-toggle="tooltip"><i class="fa fa-edit fa-2x d-xl-none"></i><span class="d-none d-xl-inline">&nbsp;'.translate("Journal").'</span></a></li>';
   include ("modules/upload/upload.conf.php");

   if (($userdata['mns']) and ($autorise_upload_p)) {
      include ("modules/blog/upload_minisite.php");
      $PopUp=win_upload("popup");
      echo '
      <li class="nav-item"><a class="nav-link" href="javascript:void(0);" onclick="window.open('.$PopUp.')" title="'.translate("Gérer votre miniSite").'"  data-toggle="tooltip"><i class="fa fa-desktop fa-2x d-xl-none"></i><span class="d-none d-xl-inline">&nbsp;'.translate("MiniSite").'</span></a></li>';
   }
   echo '
      <li class="nav-item"><a class="nav-link " href="user.php?op=edithome" title="'.translate("Editer votre page principale").'" data-toggle="tooltip" ><i class="fa fa-edit fa-2x d-xl-none"></i><span class="d-none d-xl-inline">&nbsp;'.translate("Page").'</span></a></li>
      <li class="nav-item"><a class="nav-link " href="user.php?op=chgtheme" title="'.translate("Changer le thème").'"  data-toggle="tooltip" ><i class="fa fa-paint-brush fa-2x d-xl-none"></i><span class="d-none d-xl-inline">&nbsp;'.translate("Thème").'</span></a></li>
      <li class="nav-item"><a class="nav-link active" href="modules.php?ModPath=reseaux-sociaux&amp;ModStart=reseaux-sociaux" title="'.translate("Réseaux sociaux").'"  data-toggle="tooltip" ><i class="fa fa-share-alt-square fa-2x d-xl-none"></i><span class="d-none d-xl-inline">&nbsp;'.translate("Réseaux sociaux").'</span></a></li>
      <li class="nav-item"><a class="nav-link " href="viewpmsg.php" title="'.translate("Message personnel").'"  data-toggle="tooltip" ><i class="far fa-envelope fa-2x d-xl-none"></i><span class="d-none d-xl-inline">&nbsp;'.translate("Message").'</span></a></li>
      <li class="nav-item"><a class="nav-link " href="user.php?op=logout" title="'.translate("Déconnexion").'" data-toggle="tooltip" ><i class="fas fa-sign-out-alt fa-2x text-danger d-xl-none"></i><span class="d-none d-xl-inline text-danger">&nbsp;'.translate("Déconnexion").'</span></a></li>
   </ul>
   <h3 class="mt-3">'.rs_translate("Réseaux sociaux").'</h3>
   <div class="help-block">'.rs_translate("Liste des réseaux sociaux mis à disposition par l'administrateur.").'</div>
   <hr />
   <h3><a href="modules.php?ModPath='.$ModPath.'&amp;ModStart='.$ModStart.'&amp;op=EditReseaux"><i class="fa fa-edit fa-lg"></i></a>&nbsp;'.rs_translate("Editer").'</h3>
 <div class="row mt-3">';
   foreach ($rs as $v1) {
        echo '
         <div class="col-sm-3 col-6">
            <div class="card mb-4">
               <div class="card-body text-center">
               <i class="fab fa-'.$v1[2].' fa-2x text-primary"></i></br>'.$v1[0].'
               </div>
            </div>
        </div>';
   }
   echo '
      </div>';
   include("footer.php");
}

function EditReseaux($ModPath, $ModStart) {
   $res_id = array();
   global $userdata;
   if (file_exists("modules/$ModPath/reseaux-sociaux.conf.php"))
      include ("modules/$ModPath/reseaux-sociaux.conf.php");
   include_once ("functions.php");
   include("header.php");
   global $cookie;
   $posterdata_extend = get_userdata_extend_from_id($cookie[0]);
      if ($posterdata_extend['M2']!='') {
         $i=0;
         $socialnetworks= explode(';',$posterdata_extend['M2']);
         foreach ($socialnetworks as $socialnetwork) {
            $res_id[] = explode('|',$socialnetwork);
         }
         sort($res_id);
         sort($rs);
      }

   echo '
   <h2>'.translate("User").'</h2>
   <ul class="nav nav-tabs">
      <li class="nav-item"><a class="nav-link " href="user.php?op=edituser" title="'.translate("Vous").'" data-toggle="tooltip" ><i class="fa fa-user fa-2x d-xl-none"></i><span class="d-none d-xl-inline">&nbsp;'.translate("Vous").'</span></a></li>
      <li class="nav-item"><a class="nav-link " href="user.php?op=editjournal" title="'.translate("Editer votre journal").'" data-toggle="tooltip"><i class="fa fa-edit fa-2x d-xl-none"></i><span class="d-none d-xl-inline">&nbsp;'.translate("Journal").'</span></a></li>';
   include ("modules/upload/upload.conf.php");
   if (($userdata['mns']) and ($autorise_upload_p)) {
      include ("modules/blog/upload_minisite.php");
      $PopUp=win_upload("popup");
      echo '
      <li class="nav-item"><a class="nav-link" href="javascript:void(0);" onclick="window.open('.$PopUp.')" title="'.translate("Gérer votre miniSite").'"  data-toggle="tooltip"><i class="fa fa-desktop fa-2x d-xl-none"></i><span class="d-none d-xl-inline">&nbsp;'.translate("MiniSite").'</span></a></li>';
   }
   echo '
      <li class="nav-item"><a class="nav-link " href="user.php?op=edithome" title="'.translate("Editer votre page principale").'" data-toggle="tooltip" ><i class="fa fa-edit fa-2x d-xl-none"></i><span class="d-none d-xl-inline">&nbsp;'.translate("Page").'</span></a></li>
      <li class="nav-item"><a class="nav-link " href="user.php?op=chgtheme" title="'.translate("Changer le thème").'"  data-toggle="tooltip" ><i class="fa fa-paint-brush fa-2x d-xl-none"></i><span class="d-none d-xl-inline">&nbsp;'.translate("Thème").'</span></a></li>
      <li class="nav-item"><a class="nav-link active" href="modules.php?ModPath=reseaux-sociaux&amp;ModStart=reseaux-sociaux" title="'.translate("Réseaux sociaux").'"  data-toggle="tooltip" ><i class="fa fa-share-alt-square fa-2x d-xl-none"></i><span class="d-none d-xl-inline">&nbsp;'.translate("Réseaux sociaux").'</span></a></li>
      <li class="nav-item"><a class="nav-link " href="viewpmsg.php" title="'.translate("Message personnel").'"  data-toggle="tooltip" ><i class="fa fa-envelope fa-2x d-xl-none"></i><span class="d-none d-xl-inline">&nbsp;'.translate("Message").'</span></a></li>
      <li class="nav-item"><a class="nav-link " href="user.php?op=logout" title="'.translate("Déconnexion").'" data-toggle="tooltip" ><i class="fas fa-sign-out-alt fa-2x text-danger d-xl-none"></i><span class="d-none d-xl-inline text-danger">&nbsp;'.translate("Déconnexion").'</span></a></li>
   </ul>
   <h3 class="mt-1">'.rs_translate("Réseaux sociaux").'</h3>
   <div>
   <div class="help-block">'.rs_translate("Ajouter ou supprimer votre identifiant à ces réseaux sociaux.").'</div>
   <hr />
   <form id="reseaux_user" action="modules.php?ModStart='.$ModStart.'&amp;ModPath='.$ModPath.'&amp;op=SaveSetReseaux" method="post">';
   $i=0;
   $ident='';
   foreach ($rs as $v1) {
      if ($res_id){
         foreach($res_id as $y1) {
            $k = array_search( $y1[0],$v1);
            if (false !== $k) {
               $ident=$y1[1];
               break;
            }
            else $ident='';
         }
      }
      if($i==0) echo '
   <div class="row">';
   echo '
   <div class="col-sm-6">
   <fieldset>
   <legend><i class="fab fa-'.$v1[2].' fa-2x text-primary mr-2 align-middle"></i>'.$v1[0].'</legend>
      <div class="form-group row">
         <label class="col-form-label col-sm-12" for="rs_uid'.$i.'">'.rs_translate("Identifiant").'</label>
         <div class="col-sm-12">
            <input class="form-control" type="text" id="rs_uid'.$i.'" name="rs['.$i.'][uid]"  maxlength="50"  placeholder="'.rs_translate("Identifiant").' '.$v1[0].'" value="'.$ident.'"/>
            <span class="help-block text-right"><span id="countcar_rs_uid'.$i.'"></span></span>
            <input type="hidden" name="rs['.$i.'][id]" value="'.$v1[0].'" />
         </div>
      </div>
   </fieldset>
   </div>';
   if ($i%2 == 1) echo '
   </div>
   <div class="row">';
   $i++;
   }
echo '
   </div>
      <div class="form-group row">
         <div class="col-sm-6">
            <button class="btn btn-primary col-12" type="submit"><i class="fa fa-check fa-lg"></i>&nbsp;'.rs_translate("Sauvegarder").'</button>
            <input type="hidden" name="ModPath" value="'.$ModPath.'" />
            <input type="hidden" name="ModStart" value="'.$ModStart.'" />
            <input type="hidden" name="op" value="SaveSetReseaux" />
         </div>
      </div>
   </form>';
   adminfoot('','','','');
}

function SaveSetReseaux($ModPath, $ModStart) {
   global $cookie;
   $li_rs='';
   foreach ($_POST['rs'] as $v1){
      if($v1['uid']!=='')
         $li_rs.=$v1['id'].'|'.$v1['uid'].';';
   }
   $li_rs=rtrim($li_rs,';');
   $li_rs=removeHack(stripslashes(FixQuotes($li_rs)));
   sql_query("UPDATE ".$NPDS_Prefix."users_extend SET M2='$li_rs' WHERE uid='$cookie[0]'");
   Header("Location: modules.php?&ModPath=$ModPath&ModStart=$ModStart");

}
settype($op,'string');
switch ($op) {
   case 'SaveSetReseaux':
      SaveSetReseaux($ModPath, $ModStart);
   break;
   case 'EditReseaux':
      EditReseaux($ModPath, $ModStart);
   break;
   default:
      ListReseaux($ModPath, $ModStart);
   break;
}
?>
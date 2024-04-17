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
   '.member_menu($userdata['mns'],$userdata['uname']).'
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
   <h2>'.translate("Utilisateur").'</h2>';
   member_menu($userdata['mns'],$userdata['uname']);
   echo '
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
            <legend><i class="fab fa-'.$v1[2].' fs-1 text-primary me-2 align-middle"></i>'.$v1[0].'</legend>
            <div class="mb-3 form-floating">
               <input class="form-control" type="text" id="rs_uid'.$i.'" name="rs['.$i.'][uid]"  maxlength="50"  placeholder="'.rs_translate("Identifiant").' '.$v1[0].'" value="'.$ident.'"/>
               <label for="rs_uid'.$i.'">'.rs_translate("Identifiant").'</label>
            </div>
            <span class="help-block text-end"><span id="countcar_rs_uid'.$i.'"></span></span>
            <input type="hidden" name="rs['.$i.'][id]" value="'.$v1[0].'" />
         </fieldset>
      </div>';
   if ($i%2 == 1) echo '
   </div>
   <div class="row">';
   $i++;
   }
echo '
   </div>
      <div class="my-3 row">
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
   global $cookie, $NPDS_Prefix;
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
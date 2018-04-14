<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* BIG mod by JPB for NPDS-WS                                           */
/*                                                                      */
/* NPDS Copyright (c) 2002-2018 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!stristr($_SERVER['PHP_SELF'],"admin.php")) { Access_Error(); }
$f_meta_nom ='groupes';
$f_titre = adm_translate('Gestion des groupes');

//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
global $language,$adminimg, $admf_ext;
$hlpfile = "manuels/$language/groupes.html";

settype($al,'string');
if ($al) {
   if (preg_match ('#^mod#',$al)) {
      $al=explode ('_',$al);
      $mes=adm_translate("Vous ne pouvez pas exclure").' '.$al[1].' '.adm_translate("car il est modérateur unique de forum. Oter ses droits de modération puis retirer le du groupe.");
   }
}

function group_liste() {
   global $hlpfile, $NPDS_Prefix, $al, $mes, $f_meta_nom, $f_titre, $adminimg;

   include ('header.php');
   GraphicAdmin($hlpfile);
   $result = sql_query("SELECT uid, groupe FROM ".$NPDS_Prefix."users_status WHERE groupe!='' ORDER BY uid ASC");
   $one_gp=false;
   $tab_groupeII=array();
   $tab_groupeIII = array();
   $r = sql_query("SELECT groupe_id FROM ".$NPDS_Prefix."groupes ORDER BY groupe_id ASC");
   while($gl = sql_fetch_assoc($r)) {
      $tab_groupeII[$gl['groupe_id']]='';
   }
   while(list($uid, $groupe) = sql_fetch_row($result)) {
      $one_gp=true;
      $tab_groupe=explode(',',$groupe);
      if ($tab_groupe) {
         foreach($tab_groupe as $groupevalue) {
            if ($groupevalue!='') {
               $tab_groupeII[$groupevalue].=$uid.' ';
               $tab_groupeIII[$groupevalue]=$groupevalue;
            }
         }
      }
   }

   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '<script type="text/javascript">
   //<![CDATA[';
   if ($al) echo'bootbox.alert("'.$mes.'")';
   echo'
   tog(\'lst_gr\',\'show_lst_gr\',\'hide_lst_gr\');

   //==> choix moderateur
   function choisir_mod_forum(gp,gn,ar_user,ar_uid) {
      var user_json = ar_user.split(",");
      var uid_json = ar_uid.split(",");
      var choix_mod = prompt("'.html_entity_decode(adm_translate("Choisir un modérateur"),ENT_COMPAT | ENT_HTML401,cur_charset).' : \n"+user_json);
      if (choix_mod) {
         for (i=0; i<user_json.length; i++) {
            if (user_json[i] == choix_mod) {var ind_uid=i;}
         }
         var xhr_object = null;
         if (window.XMLHttpRequest) // FF
            xhr_object = new XMLHttpRequest();
         else if(window.ActiveXObject) // IE
            xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
         xhr_object.open("GET", "admin.php?op=forum_groupe_create&groupe_id="+gp+"&groupe_name="+gn+"&moder="+uid_json[ind_uid], false);
         xhr_object.send(null);
         document.location.href="admin.php?op=groupes";
      }
   } 
   //<== choix moderateur

   //==> confirmation suppression tous les membres du groupe (done in xhr)
   function delete_AllMembersGroup(grp,ugp) {
      var xhr_object = null;
      if (window.XMLHttpRequest) // FF
         xhr_object = new XMLHttpRequest();
      else if(window.ActiveXObject) // IE
         xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
      if (confirm("'.adm_translate("Vous allez exclure TOUS les membres du groupe").' "+grp+" !")) {
         xhr_object.open("GET", location.href="admin.php?op=retiredugroupe_all&groupe_id="+grp+"&tab_groupe="+ugp, false);
      }
   }
   //<== confirmation suppression tous les membres du groupe (done in xhr)

   //==> confirmation suppression groupe (done in xhr)
   function confirm_deleteGroup(gr) {
      var xhr_object = null;
      if (window.XMLHttpRequest) // FF
         xhr_object = new XMLHttpRequest();
      else if(window.ActiveXObject) // IE
         xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
      if (confirm("'.adm_translate("Vous allez supprimer le groupe").' "+gr)) {
         xhr_object.open("GET", location.href="admin.php?op=groupe_maj&groupe_id="+gr+"&sub_op='.adm_translate("Supprimer").'", false);
     }
   }
   //<== confirmation suppression groupe (done in xhr)
   //]]>
   </script>';

   echo '
   <hr />
   <form action="admin.php" method="post" name="nouveaugroupe">
      <input type="hidden" name="op" value="groupe_add" />
      <a href="#" onclick="document.forms[\'nouveaugroupe\'].submit()" title="'.adm_translate("Ajouter un groupe").'" data-toggle="tooltip" data-placement="right"><i class="fa fa-group fa-2x"></i><i class="fa fa-plus fa-lg mr-1"></i></a> 
   </form>
   <hr />
   <h3 class="my-3"><a class="tog small" id="hide_lst_gr" title="'.adm_translate("Replier la liste").'" ><i id="i_lst_gr" class="fa fa-caret-up fa-lg text-primary" ></i></a>&nbsp;'.adm_translate("Liste des groupes").'</h3>
   <div id="lst_gr" class="row">
      <div id="gr_dat" class="p-3">';
   $lst_gr_json='';
   if ($one_gp) {
      sort($tab_groupeIII);
      while (list($bidon,$gp)=each($tab_groupeIII)) {
         $lst_user_json='';
         $result=sql_fetch_assoc(sql_query("SELECT groupe_id, groupe_name, groupe_description, groupe_forum, groupe_mns, groupe_chat, groupe_blocnote, groupe_pad FROM ".$NPDS_Prefix."groupes WHERE groupe_id='$gp'"));
         echo '
         <div id="bloc_gr_'.$gp.'" class="row border rounded ml-1 p-sm-2 px-0 mb-2 w-100">
            <div class="col-lg-4 ">
               <span>'.$gp.'</span>
               <i class="fa fa-users fa-2x text-muted"></i><h4 class="my-2">'.aff_langue($result['groupe_name']).'</h4><p>'.aff_langue($result['groupe_description']);
         if (file_exists ('users_private/groupe/'.$gp.'/groupe.png'))
            echo'<img class="d-block my-2" src="users_private/groupe/'.$gp.'/groupe.png" width="80" height="80" alt="logo_groupe" />';
         echo '
            </div>
            <div class="col-lg-5">';
         $tab_groupe=explode(' ',ltrim($tab_groupeII[$gp]));
         $nb_mb=(count($tab_groupe))-1;
         echo '
               <a class="tog" id="show_lst_mb_'.$gp.'" title="'.adm_translate("Déplier la liste").'"><i id="i_lst_mb_gr_'.$gp.'" class="fa fa-caret-down fa-lg text-primary" ></i></a>&nbsp;&nbsp;
               <i class="fa fa-user fa-2x text-muted"></i> <span class=" align-top badge badge-secondary">&nbsp;'.$nb_mb.'</span>&nbsp;&nbsp;';
         $lst_uid_json='';
         $lst_uidna_json='';
         
         //==> liste membres du groupe
//         $lst_gr_json='';
         echo '<ul id="lst_mb_gr_'.$gp.'" style ="display:none; padding-left:0px; -webkit-padding-start: 0px;">';
         while (list($bidon,$uidX)=each($tab_groupe)) {
            if ($uidX) {
               list($uname,$user_avatar)=sql_fetch_row(sql_query("SELECT uname, user_avatar FROM ".$NPDS_Prefix."users WHERE uid='$uidX'"));
               $lst_user_json.= $uname.',';
               $lst_uid_json.= $uidX.',';
               $lst_gr_json.='\'mbgr_'.$gp.'\': { gp: \''.$gp.'\'},';
               if (!$user_avatar) {
                  $imgtmp="images/forum/avatar/blank.gif";
               } else if (stristr($user_avatar,"users_private")) {
                  $imgtmp=$user_avatar;
               } else {
                  if ($ibid=theme_image("forum/avatar/$user_avatar")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/avatar/$user_avatar";}
                  if (!file_exists($imgtmp)) {$imgtmp="images/forum/avatar/blank.gif";}
               }
               echo '
               <li id="'.$uname.$uidX.'_'.$gp.'" style="list-style-type:none;">
                  <div style="float:left;">
                     <a class="adm_tooltip">&nbsp;<em style="width:90px;"><img src="'.$imgtmp.'"  height="80" width="80" alt="avatar"/></em></a>
                  </div>
                  <div class="text-truncate" style="min-width:110px; width:110px; float:left;">
                     <a href="admin.php?chng_uid='.$uidX.'&amp;op=modifyUser">'.$uname.'</a>
                  </div>
                  <div>
                     <a href="admin.php?chng_uid='.$uidX.'&amp;op=modifyUser" title="'.adm_translate("Editer les informations concernant").' '.$uname.'" data-toggle="tooltip"><i class="fa fa-edit fa-lg fa-fw mr-1"></i></a>
                     <a href="admin.php?op=retiredugroupe&amp;uid='.$uidX.'&amp;uname='.$uname.'&amp;groupe_id='.$gp.'" title="'.adm_translate("Exclure").' '.$uname.' '.adm_translate("du groupe").' '.$gp.'" data-toggle="tooltip"><i class="fa fa-user-times fa-lg fa-fw text-danger mr-1"></i></a>
                     <a href="" data-toggle="collapse" data-target="#moderation_'.$uidX.'_'.$gp.'" ><i class="fa fa-balance-scale fa-lg fa-fw" title="'.adm_translate("Modérateur").'" data-toggle="tooltip"></i></a>
                     <div id="moderation_'.$uidX.'_'.$gp.'" class="collapse">';
               //=>traitement moderateur
               if ($result['groupe_forum']==1) {
                  $pat='#\b'.$uidX.'\b#';
                  $res=sql_query("SELECT f.forum_id, f.forum_name, f.forum_moderator FROM ".$NPDS_Prefix."forums f WHERE f.forum_pass='$gp'");
                  while ($row = sql_fetch_row($res)) {
                     $ar_moder = explode(',',$row[2]);
                     $tmp_moder=$ar_moder;
                     if (preg_match($pat, $row[2])) {
                        unset($tmp_moder[array_search($uidX, $tmp_moder)]);
                        $new_moder=implode ( ',',$tmp_moder );
                        if (count($tmp_moder)!= 0) {
                           echo'<a href="admin.php?op=moderateur_update&amp;forum_id='.$row[0].'&amp;forum_moderator='.$new_moder.'" title="'.adm_translate("Oter").' '.$uname.' '.adm_translate("des modérateurs du forum").' '.$row[0].'" data-toggle="tooltip" data-placement="right"><i class="fa fa-balance-scale fa-lg fa-fw text-danger mr-1"></i></a>';
                        } else {
                           echo'<i class="fa fa-balance-scale fa-lg fa-fw mr-1" title="'.adm_translate("Ce modérateur")." (".$uname.") ".adm_translate("n'est pas modifiable tant qu'un autre n'est pas nommé pour ce forum").' '.$row[0].'" data-toggle="tooltip" data-placement="right" ></i>';
                        }
                     } else {
                        $tmp_moder[]=$uidX;
                        asort ( $tmp_moder );
                        $new_moder=implode (',',$tmp_moder);
                        echo'<a href="admin.php?op=moderateur_update&amp;forum_id='.$row[0].'&amp;forum_moderator='.$new_moder.'" title="'.adm_translate("Nommer").' '.$uname.' '.adm_translate("comme modérateur du forum").' '.$row[1].' ('.$row[0].')" data-toggle="tooltip" data-placement="right" ><i class="fa fa-balance-scale fa-lg fa-fw mr-1"></i></a>';
                     }
                  }
               }
               echo '
                  </div>
               </div>
            </li>';
            }
         }
         echo '
         </ul>';
         $lst_user_json=rtrim($lst_user_json,',');
         $lst_uid_json=rtrim($lst_uid_json,',');

         //==> pliage repliage listes membres groupes
         echo'
         <script type="text/javascript">
         //<![CDATA[
         tog(\'lst_mb_gr_'.$gp.'\',\'show_lst_mb_'.$gp.'\',\'hide_lst_mb_'.$gp.'\');
         //]]>
         </script>
         <i class="fa fa-user-times fa-lg text-danger" title="'.adm_translate('Exclure TOUS les membres du groupe').' '.$gp.'" data-toggle="tooltip" data-placement="right" onclick="delete_AllMembersGroup(\''.$gp.'\',\''.$lst_uid_json.'\');"></i>';
         //<== liste membres du groupe

         //==> menu groupe
         echo '
         </div>
         <div class="col-lg-3 list-group-item px-0 mt-2">
            <a class="btn btn-outline-secondary btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="admin.php?op=groupe_edit&amp;groupe_id='.$gp.'" title="'.adm_translate("Editer groupe").' '.$gp.'" data-toggle="tooltip"  ><i class="fa fa-pencil fa-lg"></i></a><a class="btn btn-outline-danger btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="javascript:void(0);" onclick="bootbox.alert(\''.adm_translate("Avant de supprimer le groupe").' '.$gp.' '.adm_translate("vous devez supprimer TOUS ses membres !").'\');" title="'.adm_translate("Supprimer groupe").' '.$gp.'" data-toggle="tooltip"  ><i class="fa fa-trash-o fa-lg fa-fw"></i></a><a class="btn btn-outline-secondary btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="admin.php?op=membre_add&amp;groupe_id='.$gp.'" title="'.adm_translate("Ajouter un ou des membres au groupe").' '.$gp.'" data-toggle="tooltip"  ><i class="fa fa-user-plus fa-lg fa-fw"></i></a><a class="btn btn-outline-secondary btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="admin.php?op=bloc_groupe_create&amp;groupe_id='.$gp.'" title="'.adm_translate("Créer le bloc WS").' ('.$gp.')" data-toggle="tooltip"  ><i class="fa fa-clone fa-lg fa-fw"></i><i class="fa fa-plus"></i></a>';
         if ($result['groupe_pad']==1)
            echo'<a class="btn btn-outline-danger btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="admin.php?op=pad_remove&amp;groupe_id='.$gp.'" title="'.adm_translate("Désactiver PAD du groupe").' '.$gp.'" data-toggle="tooltip"  ><i class="fa fa-edit fa-lg fa-fw"></i><i class="fa fa-minus"></i></a>';
         else
            echo'<a class="btn btn-outline-secondary btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="admin.php?op=pad_create&amp;groupe_id='.$gp.'" title="'.adm_translate("Activer PAD du groupe").' '.$gp.'" data-toggle="tooltip"  ><i class="fa fa-edit fa-lg fa-fw"></i><i class="fa fa-plus"></i></a>';
         if ($result['groupe_blocnote']==1)
            echo'<a class="btn btn-outline-danger btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="admin.php?op=note_remove&amp;groupe_id='.$gp.'" title="'.adm_translate("Désactiver bloc-note du groupe").' '.$gp.'" data-toggle="tooltip"  ><i class="fa fa-sticky-note-o fa-lg fa-fw"></i><i class="fa fa-minus"></i></a>';
         else
            echo'<a class="btn btn-outline-secondary btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="admin.php?op=note_create&amp;groupe_id='.$gp.'" title="'.adm_translate("Activer bloc-note du groupe").' '.$gp.'" data-toggle="tooltip"  ><i class="fa fa-sticky-note-o fa-lg fa-fw"></i><i class="fa fa-plus"></i></a>';
         if (file_exists('modules/f-manager/users/groupe_'.$gp.'.conf.php'))
            echo'<a class="btn btn-outline-danger btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="admin.php?op=workspace_archive&amp;groupe_id='.$gp.'" title="'.adm_translate("Désactiver gestionnaire de fichiers du groupe").' '.$gp.'" data-toggle="tooltip"  ><i class="fa fa-folder-o fa-lg fa-fw"></i><i class="fa fa-minus"></i></a>';
         else
            echo'<a class="btn btn-outline-secondary btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="admin.php?op=workspace_create&amp;groupe_id='.$gp.'" title="'.adm_translate("Activer gestionnaire de fichiers du groupe").' '.$gp.'" data-toggle="tooltip"  ><i class="fa fa-folder-o fa-lg fa-fw"></i><i class="fa fa-plus"></i></a>';
         if ($result['groupe_forum']==1)
            echo'<a class="btn btn-outline-danger btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="admin.php?op=forum_groupe_delete&amp;groupe_id='.$gp.'" title="'.adm_translate("Supprimer forum du groupe").' '.$gp.'" data-toggle="tooltip"  ><i class="fa fa-list-alt fa-lg fa-fw"></i><i class="fa fa-minus"></i></a>';
         else
            echo'<a class="btn btn-outline-secondary btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="javascript:void(0);" onclick="javascript:choisir_mod_forum(\''.$gp.'\',\''.$result['groupe_name'].'\',\''.$lst_user_json.'\',\''.$lst_uid_json.'\');" title="'.adm_translate("Créer forum du groupe").' '.$gp.'" data-toggle="tooltip"  ><i class="fa fa-list-alt fa-lg fa-fw"></i> <i class="fa fa-plus"></i></a>';
         if ($result['groupe_mns']==1)
            echo'<a class="btn btn-outline-danger btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="admin.php?op=groupe_mns_delete&amp;groupe_id='.$gp.'" title="'.adm_translate("Supprimer MiniSite du groupe").' '.$gp.'" data-toggle="tooltip"  ><i class="fa fa-desktop fa-lg fa-fw"></i><i class="fa fa-minus"></i></a>';
         else
            echo'<a class="btn btn-outline-secondary btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="admin.php?op=groupe_mns_create&amp;groupe_id='.$gp.'" title="'.adm_translate("Créer MiniSite du groupe").' '.$gp.'" data-toggle="tooltip"  ><i class="fa fa-desktop fa-lg fa-fw"></i><i class="fa fa-plus"></i></a>';
         if ($result['groupe_chat']==0)
            echo'<a class="btn btn-outline-secondary btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="admin.php?op=groupe_chat_create&amp;groupe_id='.$gp.'" title="'.adm_translate("Activer chat du groupe").' '.$gp.'" data-toggle="tooltip"  ><i class="fa fa-comments-o fa-lg fa-fw"></i><i class="fa fa-plus"></i></a>';
         else
            echo'<a class="btn btn-outline-danger btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="admin.php?op=groupe_chat_delete&amp;groupe_id='.$gp.'" title="'.adm_translate("Désactiver chat du groupe").' '.$gp.'" data-toggle="tooltip"  ><i class="fa fa-comments-o fa-lg fa-fw"></i><i class="fa fa-minus"></i></a>';
         echo '
            </div>
         </div>';
         //<== menu groupe
      }
   }

   // groupes sans membre
   $result=sql_query("SELECT groupe_id, groupe_name, groupe_description FROM ".$NPDS_Prefix."groupes ORDER BY groupe_id ASC");
   while (list($gp, $gp_name, $gp_description)=sql_fetch_row($result)) {
      $gpA=true;
      if ($tab_groupeIII) {
         reset ($tab_groupeIII);
         while (list($bidon,$gpU)=each($tab_groupeIII)) {
            if ($gp==$gpU) {$gpA=false;}
         }
      }
      if ($gpA) {
         $lst_gr_json.='\'mbgr_'.$gp.'\': { gp: \''.$gp.'\'},';
         echo '
         <div class="row border rounded ml-1 p-sm-2 px-0 mb-2 w-100">
            <div id="bloc_gr_'.$gp.'" class="col-lg-5">
               <span class="text-danger">'.$gp.'</span>
               <i class="fa fa-users fa-2x text-muted"></i>
               <h4 class="my-2 text-muted">'.aff_langue($gp_name).'</h4>
               <p class="text-muted">'.aff_langue($gp_description);
         if (file_exists ('users_private/groupe/'.$gp.'/groupe.png'))
            echo'<img class="d-block my-2" src="users_private/groupe/'.$gp.'/groupe.png" width="80" height="80" />';
            echo'
               </p>
            </div>
            <div class="col-lg-4 ">
               <i class="fa fa-user-o fa-2x text-muted"></i><span class="align-top badge badge-secondary ml-1">0</span>
            </div>
            <div class="col-lg-3 list-group-item px-0 mt-2">
               <a class="btn btn-outline-secondary btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="admin.php?op=groupe_edit&amp;groupe_id='.$gp.'" title="'.adm_translate("Editer groupe").' '.$gp.'" data-toggle="tooltip"  ><i class="fa fa-pencil fa-lg"></i></a><a class="btn btn-outline-danger btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="#" onclick="confirm_deleteGroup(\''.$gp.'\');" title="'.adm_translate("Supprimer groupe").' '.$gp.'" data-toggle="tooltip" ><i class="fa fa-trash-o fa-lg"></i></a><a class="btn btn-outline-secondary btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="admin.php?op=membre_add&amp;groupe_id='.$gp.'" title="'.adm_translate("Ajouter un ou des membres au groupe").' '.$gp.'" data-toggle="tooltip" ><i class="fa fa-user-plus fa-lg"></i></a>
            </div>
         </div>';
      }
   }
   $lst_gr_json=rtrim ($lst_gr_json,',');
   echo '
      </div>
   </div>';
    adminfoot('','','','');
}

// MEMBRE
function membre_add($gp) {
   global $hlpfile, $f_meta_nom, $f_titre, $adminimg;
   include ('header.php');
   GraphicAdmin($hlpfile);
   adminhead($f_meta_nom, $f_titre, $adminimg);
   echo '
   <hr />
   <h3>'.adm_translate("Ajouter des membres").' / '.adm_translate("Groupe").' : '.$gp.'</h3>
   <form id="form_ad_mb_gr" class="admform" action="admin.php" method="post">
      <fieldset>
         <legend><i class="fa fa-users fa-2x text-muted"></i></legend>
         <div class="form-group">
            <label class="col-form-label" for="luname">'.adm_translate("Liste des membres").'</label>
            <input type="text" class="form-control" id="luname" name="luname" maxlength="255" value="" />
         </div>
         <input type="hidden" name="op" value="membre_add_finish" />
         <input type="hidden" name="groupe_id" value="'.$gp.'" />
         <div class="form-group">
            <input class="btn btn-primary" type="submit" name="sub_op" value="'.adm_translate("Sauver les modifications").'" />
         </div>
      </fieldset>
   </form>';
   echo auto_complete_multi ('membre','uname','users','luname','inner join users_status on users.uid=users_status.uid WHERE users.uid<>1 and groupe not regexp \'[[:<:]]'.$gp.'[[:>:]]\'');
   adminfoot('fv','','','');
}
function membre_add_finish($groupe_id, $luname) {
   global $NPDS_Prefix;
   include('powerpack_f.php');
   $image='18.png';
   $luname=rtrim ( $luname ,', ');
   $luname=str_replace(' ','',$luname);
   $list_membres=explode(',',$luname);
   $nbremembres=count($list_membres);
   $subject=adm_translate('Nouvelles du groupe');
   $message=adm_translate('Vous faites désormais partie des membres du groupe').' '.$groupe_id.'.';
   $copie='';
   $from_userid=1;

   for ($j=0;$j<$nbremembres;$j++) {
      $uname=$list_membres[$j];
      $result1 = sql_query("SELECT uid FROM ".$NPDS_Prefix."users WHERE uname='$uname'");
      $ibid=sql_fetch_assoc($result1);
      if ($ibid['uid']) {
         $to_userid=$uname;
         $result2 = sql_query("SELECT groupe FROM ".$NPDS_Prefix."users_status WHERE uid='".$ibid['uid']."'");
         $ibid2=sql_fetch_assoc($result2);
         $lesgroupes=explode(',',$ibid2['groupe']);
         $nbregroupes=count($lesgroupes);

         $groupeexistedeja=false;
         for ($i=0; $i<$nbregroupes;$i++) {
            if ($lesgroupes[$i]==$groupe_id) { $groupeexistedeja=true; break; }
         }
         if (!$groupeexistedeja) {
            if ($ibid2['groupe']) $groupesmodif=$ibid2['groupe'].','.$groupe_id;
            else $groupesmodif=$groupe_id;
            $resultat = sql_query("UPDATE ".$NPDS_Prefix."users_status SET groupe='$groupesmodif' WHERE uid='".$ibid['uid']."'");
         }
         writeDB_private_message($to_userid,$image,$subject,$from_userid,$message, $copie);
      }
   }
   global $aid; Ecr_Log('security', "AddMemberToGroup($groupe_id, $luname) by AID : $aid", '');
   Header("Location: admin.php?op=groupes");
}
function retiredugroupe($groupe_id, $uid, $uname) {
   global $NPDS_Prefix;
   include('powerpack_f.php');
   $image='18.png';
   $pat='#^\b'.$uid.'\b$#';
   $mes_sys='';
   $q='';
   $ok=0;
   $res=sql_query("SELECT f.forum_id, f.forum_name, f.forum_moderator FROM ".$NPDS_Prefix."forums f WHERE f.forum_pass='$groupe_id' AND cat_id='-1'");
   while ($row = sql_fetch_row($res)) {
      if (preg_match($pat, $row[2])) {
         $mes_sys='mod_'.$uname;
         $q='&al='.$mes_sys;
         $ok=1;
      }
   }

   if ($ok==0) {
      $pat='#\b'.$uid.'\b#';
      $res=sql_query("SELECT f.forum_id, f.forum_name, f.forum_moderator FROM ".$NPDS_Prefix."forums f WHERE f.forum_pass='$groupe_id' AND cat_id='-1'");
      while ($r = sql_fetch_row($res)) {
         $new_moder=preg_replace('#,,#',',',trim(preg_replace ($pat,'',$r[2]),','));
         sql_query("UPDATE ".$NPDS_Prefix."forums SET forum_moderator='$new_moder' WHERE forum_id='$r[0]'");
      };
   
      $resultat=sql_query("SELECT groupe FROM ".$NPDS_Prefix."users_status WHERE uid='$uid'");
      $subject=adm_translate('Nouvelles du groupe');
      $message=adm_translate('Vous ne faites plus partie des membres du groupe').' '.$groupe_id.'.';
      $copie='';
      $from_userid=1;
      $to_userid=$uname;
      $valeurs=sql_fetch_assoc($resultat);
      $lesgroupes=explode(',',$valeurs['groupe']);
      $nbregroupes=count($lesgroupes);
      $groupesmodif='';
      for ($i=0; $i<$nbregroupes;$i++) {
         if ($lesgroupes[$i]!=$groupe_id) {
            if ($groupesmodif=='') $groupesmodif.=$lesgroupes[$i];
            else $groupesmodif.=','.$lesgroupes[$i];
         }
      }
      $resultat = sql_query("UPDATE ".$NPDS_Prefix."users_status SET groupe='$groupesmodif' WHERE uid='$uid'");
      writeDB_private_message($to_userid,$image,$subject,$from_userid,$message, $copie);
      global $aid; Ecr_Log('security', "DeleteMemberToGroup($groupe_id, $uname) by AID : $aid", '');
   }
   Header("Location: admin.php?op=groupes".$q);
}
function retiredugroupe_all($groupe_id,$tab_groupe) {
   global $NPDS_Prefix;
   $tab_groupe=explode ( ',', $tab_groupe );
   while (list($bidon,$uidZ)=each($tab_groupe)) {
      if ($uidZ) {
         // a rajouter enlever modérateur forum
         $resultat=sql_query("SELECT groupe FROM ".$NPDS_Prefix."users_status WHERE uid='$uidZ'");
         $valeurs=sql_fetch_assoc($resultat);
         $lesgroupes=explode(',',$valeurs['groupe']);
         $nbregroupes=count($lesgroupes);
         $groupesmodif='';
         for ($i=0; $i<$nbregroupes;$i++) {
            if ($lesgroupes[$i]!=$groupe_id) {
               if ($groupesmodif=='') $groupesmodif.=$lesgroupes[$i];
               else $groupesmodif.=','.$lesgroupes[$i];
            }
         }
         $resultat = sql_query("UPDATE ".$NPDS_Prefix."users_status SET groupe='$groupesmodif' WHERE uid='$uidZ'");
         global $aid; Ecr_Log('security', "DeleteAllMemberToGroup($groupe_id, $uidZ) by AID : $aid", '');
      }
   }
   Header("Location: admin.php?op=groupes");
}

// GROUPES
function groupe_edit($groupe_id) {
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   include ('header.php');
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   $result=sql_fetch_assoc(sql_query("SELECT groupe_name, groupe_description FROM ".$NPDS_Prefix."groupes WHERE groupe_id='$groupe_id'"));

   if ($groupe_id!='groupe_add')
      echo '
      <hr />
      <h3>'.adm_translate("Modifier le groupe").' : '.$groupe_id.'</h3>';
   else
      echo '
      <hr />
      <h3>'.adm_translate("Créer un groupe.").'</h3>';
   echo '
   <form class="admform" id="form_aded_gr" action="admin.php" method="post">
      <fieldset>
         <legend><i class="fa fa-group fa-2x text-muted"></i></legend>'."\n";
   if ($groupe_id!='groupe_add')
      echo '<input type="hidden" name="groupe_id" value="'.$groupe_id.'" />';
   else
      echo '
         <div class="form-group">
            <label for="inp_gr_id" class="admform">ID</label>
            <input id="inp_gr_id" type="number" min="2" max="126" class="form-control" name="groupe_id" value="" required="required"/><span class="help-block">(2...126)</span>
         </div>';
      echo '
         <div class="form-group">
            <label class="col-form-label" for="inp_gr_na">'.adm_translate("Nom").'</label>
            <input id="inp_gr_na" type="text" class="form-control" name="groupe_name" maxlength="30" value="'.$result['groupe_name'].'" placeholder="'.adm_translate("Nom du groupe").'" required="required" />
         </div>
         <div class="form-group">
            <label class="col-form-label" for="groupe_description">'.adm_translate("Description").'</label>
            <textarea class="form-control" name="groupe_description" id="groupe_description" rows="11" maxlength="255" placeholder="'.adm_translate("Description du groupe").'" required="required">'.$result['groupe_description'].'</textarea>
         </div>';
   if ($groupe_id != 'groupe_add')
      echo '
         <input type="hidden" name="op" value="groupe_maj" />';
   else
      echo '
         <input type="hidden" name="op" value="groupe_add_finish" />';
   echo '
         <div class="form-group">
            <button class="btn btn-primary" type="submit" name="sub_op" value="'.adm_translate("Sauver les modifications").'">'.adm_translate("Sauver les modifications").'</button>
         </div>
      </fieldset>
   </form>';
   adminfoot('fv','','','');
}
function groupe_maj() {
   global $hlpfile, $NPDS_Prefix;
   global $groupe_id, $groupe_name, $groupe_description, $sub_op;

   if ($sub_op==adm_translate("Sauver les modifications")) {
      sql_query("UPDATE ".$NPDS_Prefix."groupes SET groupe_name='$groupe_name', groupe_description='$groupe_description' WHERE groupe_id='$groupe_id'");
      global $aid; Ecr_Log("security", "UpdateGroup($groupe_id) by AID : $aid", '');
   }
   if ($sub_op==adm_translate("Supprimer")) {
      $result = sql_query("SELECT uid, groupe FROM ".$NPDS_Prefix."users_status WHERE groupe!='' ORDER BY uid ASC");
      $maj_ok=true;
      while (list($to_userid, $groupeX) = sql_fetch_row($result)) {
         $tab_groupe=explode(',',$groupeX);
         if ($tab_groupe) {
            foreach($tab_groupe as $groupevalue) {
               if ($groupevalue==$groupe_id) {
                  $maj_ok=false;
                  break;
               }
            }
         }
      }
      if ($maj_ok) {
         groupe_delete($groupe_id);
      }
   }
   Header("Location: admin.php?op=groupes");
}
function groupe_delete($groupe_id) {
   global $hlpfile, $NPDS_Prefix, $groupe_name, $groupe_description, $sub_op;

   sql_query("DELETE FROM ".$NPDS_Prefix."lblocks WHERE member='$groupe_id'");
   sql_query("DELETE FROM ".$NPDS_Prefix."rblocks WHERE member='$groupe_id'");
   sql_query("DELETE FROM ".$NPDS_Prefix."groupes WHERE groupe_id='$groupe_id'");
   sql_query("DELETE FROM ".$NPDS_Prefix."blocnotes WHERE bnid='".md5("WS-BN".$groupe_id)."'");

   forum_groupe_delete($groupe_id);
   workspace_archive($groupe_id);
   groupe_mns_delete($groupe_id);

   global $aid; Ecr_Log('security', "DeleteGroup($groupe_id) by AID : $aid", '');
}

// --------------

// WORKSPACE
function workspace_create($groupe_id) {
   global $NPDS_Prefix;

   //==>creation fichier conf du groupe
   @copy('modules/f-manager/users/groupe.conf.php','modules/f-manager/users/groupe_'.$groupe_id.'.conf.php');
   $file = file('modules/f-manager/users/groupe_'.$groupe_id.'.conf.php');
   $file[29] ="   \$access_fma = \"$groupe_id\";\n";
   $fic = fopen('modules/f-manager/users/groupe_'.$groupe_id.'.conf.php', "w");
   while (list($n,$ligne) = each($file)) {
      fwrite($fic, $ligne);
   }
   fclose($fic);

   include ("modules/upload/upload.conf.php");
   if ($DOCUMENTROOT=='') {
      global $DOCUMENT_ROOT;
      if ($DOCUMENT_ROOT) {
         $DOCUMENTROOT=$DOCUMENT_ROOT;
      } else {
         $DOCUMENTROOT=$_SERVER['DOCUMENT_ROOT'];
      }
   }
   $user_dir=$DOCUMENTROOT.$racine.'/users_private/groupe/'.$groupe_id;

   // DOCUMENTS_GROUPE
   @mkdir('users_private/groupe/'.$groupe_id.'/documents_groupe');
   $repertoire=$user_dir.'/documents_groupe';
   $directory=$racine.'/modules/groupe/matrice/documents_groupe';
   $handle=opendir($DOCUMENTROOT.$directory);
   while (false!==($file = readdir($handle))) $filelist[] = $file;
   asort($filelist);
   while (list ($key, $file) = each ($filelist)) {
      if ($file<>'.' and $file<>'..') {
         @copy($DOCUMENTROOT.$directory.'/'.$file, $repertoire.'/'.$file);
      }
   }
   closedir($handle);
   unset ($filelist);

   // IMAGES_GROUPE
   @mkdir('users_private/groupe/'.$groupe_id.'/images_groupe');
   $repertoire=$user_dir.'/images_groupe';
   $directory=$racine.'/modules/groupe/matrice/images_groupe';
   $handle=opendir($DOCUMENTROOT.$directory);
   while (false!==($file = readdir($handle))) $filelist[] = $file;
   asort($filelist);
   while (list ($key, $file) = each ($filelist)) {
      if ($file<>'.' and $file<>'..') {
         @copy($DOCUMENTROOT.$directory.'/'.$file, $repertoire.'/'.$file);
      }
   }
   closedir($handle);
   unset ($filelist);
   @unlink('users_private/groupe/'.$groupe_id.'/delete');

   global $aid; Ecr_Log('security', "CreateWS($groupe_id) by AID : $aid", '');
}

// PAD
function pad_create($groupe_id) {
   global $NPDS_Prefix;
   sql_query("UPDATE ".$NPDS_Prefix."groupes SET groupe_pad = '1' WHERE groupe_id = '$groupe_id';");
   global $aid; Ecr_Log('security', "CreatePadWS($groupe_id) by AID : $aid", '');
}
function pad_remove($groupe_id) {
   global $NPDS_Prefix;
   sql_query("UPDATE ".$NPDS_Prefix."groupes SET groupe_pad = '0' WHERE groupe_id = '$groupe_id';");
   global $aid; Ecr_Log('security', "DeletePadWS($groupe_id) by AID : $aid", '');
}

// BLOC-NOTE
function note_create($groupe_id) {
   global $NPDS_Prefix;
   $type_engine='';
   // => Creation table blocnotes
   if (version_compare(PHP_VERSION, '7.0.0') >= 0) {$type_engine=6;}// a affiner
   else {$type_engine=(int)substr(mysql_get_server_info(), 0, 1);}// a affiner

   $sql="CREATE TABLE IF NOT EXISTS ".$NPDS_Prefix."blocnotes (
   bnid tinytext NOT NULL,
   texte text,
   PRIMARY KEY (bnid(32))
   )";
   if ($type_engine>=5)
      $sql.=" ENGINE=MyISAM";
   else
      $sql.=" TYPE=MyISAM";
   sql_query($sql);

   sql_query("UPDATE ".$NPDS_Prefix."groupes SET groupe_blocnote = '1' WHERE groupe_id = '$groupe_id';");
   global $aid; Ecr_Log('security', "CreateBlocnoteWS($groupe_id) by AID : $aid", '');
}
function note_remove($groupe_id) {
   global $NPDS_Prefix;
   
   sql_query("DELETE FROM ".$NPDS_Prefix."blocnotes WHERE bnid='".md5("WS-BN".$groupe_id)."'");
   sql_query("UPDATE ".$NPDS_Prefix."groupes SET groupe_blocnote = '0' WHERE groupe_id = '$groupe_id';");

   global $aid; Ecr_Log('security', "DeleteBlocnoteWS($groupe_id) by AID : $aid", '');
}

function workspace_archive($groupe_id) {
   //=> archivage espace groupe
   $fp=fopen ('users_private/groupe/'.$groupe_id.'/delete','w');
   fclose($fp);
   //suppression fichier conf
   @unlink('modules/f-manager/users/groupe_'.$groupe_id.'.conf.php');
   global $aid; Ecr_Log('security', "ArchiveWS($groupe_id) by AID : $aid", '');
}

// FORUMS
function forum_groupe_create($groupe_id,$groupe_name,$description,$moder) {
    global $NPDS_Prefix;

    // creation forum
    // creation catégorie forum_groupe
    $result=sql_query("SELECT cat_id FROM ".$NPDS_Prefix."catagories WHERE cat_id = -1;");
    list($cat_id)=sql_fetch_row($result);
    if (!$cat_id) {
       sql_query("INSERT INTO ".$NPDS_Prefix."catagories VALUES (-1, '".adm_translate("Groupe de travail")."')");
    };
    //==>creation forum

    echo "$groupe_id,$groupe_name,$description,$moder";

    sql_query("INSERT INTO ".$NPDS_Prefix."forums VALUES (NULL, '$groupe_name', '$description', '1', '$moder', '-1', '7', '$groupe_id', '0', '0', '0')");
    //=> ajout etat forum (1 ou 0) dans le groupe
    sql_query("UPDATE ".$NPDS_Prefix."groupes SET groupe_forum = '1' WHERE groupe_id = '$groupe_id';");
    global $aid; Ecr_Log("security", "CreateForumWS($groupe_id) by AID : $aid", '');
}

function moderateur_update($forum_id,$forum_moderator) {
    global $NPDS_Prefix;

    sql_query("UPDATE ".$NPDS_Prefix."forums SET forum_moderator = '$forum_moderator' WHERE forum_id='$forum_id'");
}
function forum_groupe_delete($groupe_id) {
    global $NPDS_Prefix;

    $result=sql_query("SELECT forum_id FROM ".$NPDS_Prefix."forums WHERE forum_pass='$groupe_id' and cat_id='-1'");
    list($forum_id) = sql_fetch_row($result);
    // suppression des topics
    sql_query("DELETE FROM ".$NPDS_Prefix."forumtopics WHERE forum_id='$forum_id'");
    // maj table lecture
    sql_query("DELETE FROM ".$NPDS_Prefix."forum_read WHERE forum_id='$forum_id'");

    //=> suppression du forum
    sql_query("DELETE FROM ".$NPDS_Prefix."forums WHERE forum_id='$forum_id'");
    // =>remise à 0 forum dans le groupe
    sql_query("UPDATE ".$NPDS_Prefix."groupes SET groupe_forum = '0' WHERE groupe_id='$groupe_id'");
    global $aid; Ecr_Log('security', "DeleteForumWS($forum_id) by AID : $aid", '');
}

// MNS
function groupe_mns_create($groupe_id) {
   global $NPDS_Prefix;
   include ("modules/upload/upload.conf.php");

   if ($DOCUMENTROOT=='') {
      global $DOCUMENT_ROOT;
      if ($DOCUMENT_ROOT) {
         $DOCUMENTROOT=$DOCUMENT_ROOT;
      } else {
         $DOCUMENTROOT=$_SERVER['DOCUMENT_ROOT'];
      }
   }
   $user_dir=$DOCUMENTROOT.$racine.'/users_private/groupe/'.$groupe_id;
   $repertoire=$user_dir.'/mns';
   if (!is_dir($user_dir)) {
      @umask("0000");
      if (@mkdir($user_dir,0777)) {
         $fp = fopen($user_dir.'/index.html', 'w');
         fclose($fp);
         @umask("0000");
         if (@mkdir($repertoire,0777)) {
            $fp = fopen($repertoire.'/index.html', 'w');
            fclose($fp);
            $fp = fopen($repertoire.'/.htaccess', 'w');
            @fputs($fp, 'Deny from All');
            fclose($fp);
         }
      }
   } else {
      @umask("0000");
      if (@mkdir($repertoire,0777)) {
         $fp = fopen($repertoire.'/index.html', 'w');
         fclose($fp);
         $fp = fopen($repertoire.'/.htaccess', 'w');
         @fputs($fp, 'Deny from All');
         fclose($fp);
      }
   }
   // copie de la matrice par défaut
   $directory=$racine.'/modules/groupe/matrice/mns_groupe';
   $handle=opendir($DOCUMENTROOT.$directory);
   while (false!==($file = readdir($handle))) $filelist[] = $file;
   asort($filelist);
   while (list ($key, $file) = each ($filelist)) {
      if ($file<>'.' and $file<>'..') {
         @copy($DOCUMENTROOT.$directory.'/'.$file, $repertoire.'/'.$file);
      }
   }
   closedir($handle);
   unset ($filelist);
   sql_query("UPDATE ".$NPDS_Prefix."groupes SET groupe_mns = '1' WHERE groupe_id = '$groupe_id';");
   global $aid; Ecr_Log('security', "CreateMnsWS($groupe_id) by AID : $aid", '');
}
function groupe_mns_delete($groupe_id) {
   global $NPDS_Prefix;
   include ("modules/upload/upload.conf.php");

   if ($DOCUMENTROOT=='') {
      global $DOCUMENT_ROOT;
      if ($DOCUMENT_ROOT) {
         $DOCUMENTROOT=$DOCUMENT_ROOT;
      } else {
         $DOCUMENTROOT=$_SERVER['DOCUMENT_ROOT'];
      }
   }
   $user_dir=$DOCUMENTROOT.$racine.'/users_private/groupe/'.$groupe_id;

   // Supprimer son ministe s'il existe
   if (is_dir($user_dir.'/mns')) {
      $dir = opendir($user_dir.'/mns');
      while(false!==($nom = readdir($dir))) {
         if ($nom != '.' && $nom != '..' && $nom != '') {
            @unlink($user_dir.'/mns/'.$nom);
         }
     }
     closedir($dir);
     @rmdir($user_dir.'/mns');
   }
   sql_query("UPDATE ".$NPDS_Prefix."groupes SET groupe_mns = '0' WHERE groupe_id = '$groupe_id';");
   global $aid; Ecr_Log('security', "DeleteMnsWS($groupe_id) by AID : $aid", '');
}

// CHAT
function groupe_chat_create($groupe_id) {
   global $NPDS_Prefix;
   sql_query("UPDATE ".$NPDS_Prefix."groupes SET groupe_chat = '1' WHERE groupe_id = '$groupe_id';");
   global $aid; Ecr_Log('security', "ActivateChatWS($groupe_id) by AID : $aid", '');
}
function groupe_chat_delete($groupe_id) {
   global $NPDS_Prefix;
   sql_query("UPDATE ".$NPDS_Prefix."groupes SET groupe_chat = '0' WHERE groupe_id = '$groupe_id';");
   global $aid; Ecr_Log('security', "DesactivateChatWS($groupe_id) by AID : $aid", '');
}

function bloc_groupe_create($groupe_id) {
   global $NPDS_Prefix;
   // Creation bloc espace de travail user
   // On créer le bloc s'il n'existe pas déjà
   $bloc=false;
   $menu_workspace="function#bloc_espace_groupe\r\nparams#$groupe_id,1";
   $row=sql_fetch_row(sql_query("SELECT COUNT(id) FROM ".$NPDS_Prefix."lblocks WHERE content='$menu_workspace'"));
   if ($row[0]==0) {
      $row=sql_fetch_row(sql_query("SELECT COUNT(id) FROM ".$NPDS_Prefix."rblocks WHERE content='$menu_workspace'"));
      if ($row[0]<>0)
         $bloc=true;
   } else {
      $bloc=true;
   }
   if ($bloc==false) {
      sql_query("INSERT INTO ".$NPDS_Prefix."lblocks VALUES (NULL, '', '$menu_workspace', '$groupe_id', '3', '0', '1', '0', NULL)");
   }
}

switch ($op) {
   case 'membre_add':
      membre_add($groupe_id);
   break;
   case 'membre_add_finish':
      membre_add_finish($groupe_id,$luname);
   break;
   case 'retiredugroupe':
      retiredugroupe($groupe_id,$uid,$uname);
   break;
   case 'retiredugroupe_all':
      retiredugroupe_all($groupe_id,$tab_groupe);
   break;

   case 'pad_create':
      pad_create($groupe_id);
      Header("Location: admin.php?op=groupes");
   break;
   case 'pad_remove':
      pad_remove($groupe_id);
      Header("Location: admin.php?op=groupes");
   break;

   case 'note_create':
      note_create($groupe_id);
      Header("Location: admin.php?op=groupes");
   break;
   case 'note_remove':
      note_remove($groupe_id);
      Header("Location: admin.php?op=groupes");
   break;

   case 'workspace_create':
      workspace_create($groupe_id);
      Header("Location: admin.php?op=groupes");
   break;
   case 'workspace_archive':
      workspace_archive($groupe_id);
      Header("Location: admin.php?op=groupes");
   break;

   case 'forum_groupe_create':
      forum_groupe_create($groupe_id,$groupe_name,$description,$moder);
   break;
   case 'moderateur_update':
      moderateur_update($forum_id,$forum_moderator);
      Header('location: admin.php?op=groupes');
   break;
   case 'forum_groupe_delete':
      forum_groupe_delete($groupe_id);
      Header('location: admin.php?op=groupes');
   break;

   case 'groupe_mns_create':
      groupe_mns_create($groupe_id);
      Header('location: admin.php?op=groupes');
   break;
   case 'groupe_mns_delete':
      groupe_mns_delete($groupe_id);
      Header('location: admin.php?op=groupes');
   break;
   case 'groupe_chat_create':
      groupe_chat_create($groupe_id);
      Header('location: admin.php?op=groupes');
   break;
   case 'groupe_chat_delete':
      groupe_chat_delete($groupe_id);
      Header('location: admin.php?op=groupes');
   break;
   case 'groupe_edit':
      groupe_edit($groupe_id);
   break;
   case 'groupe_maj':
      groupe_maj();
   break;
   case 'groupe_add':
      groupe_edit("groupe_add");
   break;
   case 'bloc_groupe_create':
      bloc_groupe_create($groupe_id);
      Header('location: admin.php?op=groupes');
   break;
   case 'groupe_add_finish':
      $ok_grp=false;
      if (($groupe_id=='') or ($groupe_id<2) or ($groupe_id>126)) {
         $row=sql_fetch_row(sql_query("SELECT MAX(groupe_id) FROM ".$NPDS_Prefix."groupes"));
         if ($row[0]<126) {
            if ($row[0]==0) $row[0]=1;
            $groupe_id=$row[0]+1;
            $ok_grp=true;
         }
      } else {
         $ok_grp=true;
      }
      if ($ok_grp) {
         sql_query("INSERT INTO ".$NPDS_Prefix."groupes VALUES ('$groupe_id', '$groupe_name','$groupe_description','0','0','0','0','0')");
         @mkdir('users_private/groupe/'.$groupe_id);
         $fp=fopen ('users_private/groupe/'.$groupe_id.'/index.html','w');
         fclose($fp);
         @copy('modules/groupe/matrice/groupe.png','users_private/groupe/'.$groupe_id.'/groupe.png');
         @unlink('users_private/groupe/'.$groupe_id.'/delete');

         global $aid; Ecr_Log('security', "CreateGroupe($groupe_id, $groupe_name) by AID : $aid", '');
      }
   default:
      group_liste();
   break;
}
?>
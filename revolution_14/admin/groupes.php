<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* BIG mod by JPB for NPDS-WS                                           */
/*                                                                      */
/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */
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
   $result = sql_query("select uid, groupe from ".$NPDS_Prefix."users_status where groupe!='' order by uid ASC");
   $one_gp=false;
   while(list($uid, $groupe) = sql_fetch_row($result)) {
      $one_gp=true;
      $tab_groupe=explode(",",$groupe);
      if ($tab_groupe) {
         foreach($tab_groupe as $groupevalue) {
            if ($groupevalue!="") {
               $tab_groupeII[$groupevalue].=$uid." ";
               $tab_groupeIII[$groupevalue]=$groupevalue;
            }
         }
      }
   }
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '<script type="text/javascript">
   //<![CDATA[';
   if ($al)
   echo'bootbox.alert("'.$mes.'")';
   echo'
   tog(\'lst_gr\',\'show_lst_gr\',\'hide_lst_gr\');

   //==> choix moderateur
   function choisir_mod_forum(gp,gn,ar_user,ar_uid) {
      var user_json = ar_user.split(",");
      var uid_json = ar_uid.split(",");
      var choix_mod = prompt("'.adm_translate("Choisir un modérateur").' : \n"+user_json);
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
   <form action="admin.php" method="post">
      <input type="hidden" name="op" value="groupe_add" />
      <input type="image" src="images/admin/ws/groupe_add.gif" title="'.adm_translate("Ajouter un groupe").'" style="background:none;vertical-align:middle;" border="0" alt="'.adm_translate("Ajouter un groupe").'" />
   </form>
   <h3><span class="tog small" id="hide_lst_gr" title="'.adm_translate("Replier la liste").'" ><i id="i_lst_gr" class="fa fa-minus-square-o" ></i></span>&nbsp;'.adm_translate("Liste des groupes").'</h3>
   <table id="lst_gr" class="table table-striped">
      <thead>
         <tr>
            <th align="left" width="5%">'.adm_translate("ID").'</th>
            <th width="30%">'.adm_translate("Nom").'</th>
            <th width="50%">'.adm_translate("Liste des membres").'</th>
            <th align="right" width="5%">'.adm_translate("Fonctions").'</th>
         </tr>
      </thead>
      <tbody id="gr_dat">';
   if ($one_gp) {
      sort($tab_groupeIII);
      while (list($bidon,$gp)=each($tab_groupeIII)) {
         $lst_user_json='';
         $result=sql_fetch_assoc(sql_query("select groupe_id, groupe_name, groupe_description, groupe_forum, groupe_mns, groupe_chat, groupe_blocnote, groupe_pad from ".$NPDS_Prefix."groupes where groupe_id='$gp'"));
         echo '
         <tr id="bloc_gr_'.$gp.'"'.$rowcolor.'>
            <td valign="top" align="left" width="5%">'.$gp.'</td>
            <td valign="top" align="left" width="20%"><i class="glyphicons glyphicons-group x2 drop"></i><br /><br /><b>'.aff_langue($result['groupe_name']).'</b><p>'.aff_langue($result['groupe_description']);
         if (file_exists ('users_private/groupe/'.$gp.'/groupe.png'))
            echo'<br /><br /><img src="users_private/groupe/'.$gp.'/groupe.png" width="80" height="80" alt="logo_groupe" />
            </p>
            </td>
            <td valign="top">';
         $tab_groupe=explode(" ",ltrim($tab_groupeII[$gp]));
         $nb_mb=(count($tab_groupe))-1;
         echo '
               <span class="tog" id="show_lst_mb_'.$gp.'" title="'.adm_translate("Déplier la liste").'"><i id="i_lst_mb_gr_'.$gp.'" class="fa fa-plus-square-o" ></i></span>&nbsp;&nbsp;
               <i class="glyphicons glyphicons-user x2 drop"></i> &nbsp;['.$nb_mb.']&nbsp;&nbsp;';
         $lst_uid_json='';
         $lst_uidna_json='';
         //==> liste membres du groupe
         echo '<ul id="lst_mb_gr_'.$gp.'" style ="display:none; padding-left:19px;">';
         while (list($bidon,$uidX)=each($tab_groupe)) {
            if ($uidX) {
               list($uname,$user_avatar)=sql_fetch_row(sql_query("select uname, user_avatar from ".$NPDS_Prefix."users where uid='$uidX'"));
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
               <li id="'.$uname.$uidX.'_'.$gp.'" style="list-style-type:none; line-height:30px;">
                  <div style="width:100px; float:left;">
                     <a href="admin.php?chng_uid='.$uidX.'&amp;op=modifyUser" class="adm_tooltip">'.$uname.'<em style="width:90px"><img src="'.$imgtmp.'"  height="80" width="80" alt="avatar"/></em></a>
                  </div>
                  <div>
                     <a href="admin.php?chng_uid='.$uidX.'&amp;op=modifyUser" title="'.adm_translate("Editer les informations concernant").' '.$uname.'" data-toggle="tooltip">
                     <i class="glyphicons glyphicons-edit"></i></a>
                     <a href="admin.php?op=retiredugroupe&amp;uid='.$uidX.'&amp;uname='.$uname.'&amp;groupe_id='.$gp.'" title="'.adm_translate("Exclure").' '.$uname.' '.adm_translate("du groupe").' '.$gp.'" data-toggle="tooltip"><i class="glyphicons glyphicons-user-remove text-danger"></i></a>&nbsp;';
               //=>traitement moderateur
               if ($result['groupe_forum']==1) {
                  $pat='#\b'.$uidX.'\b#';
                  $res=sql_query("select f.forum_id, f.forum_name, f.forum_moderator from ".$NPDS_Prefix."forums f where f.forum_pass='$gp'");
                  while ($row = sql_fetch_row($res)) {
                     $ar_moder = explode(',',$row[2]);
                     $tmp_moder=$ar_moder;
                     if (preg_match($pat, $row[2])) {
                        unset($tmp_moder[array_search($uidX, $tmp_moder)]);
                        $new_moder=implode ( ',',$tmp_moder );
                        if (count($tmp_moder)!= 0) {
                           echo'&nbsp;<a href="admin.php?op=moderateur_update&amp;forum_id='.$row[0].'&amp;forum_moderator='.$new_moder.'" title="'.adm_translate("Oter").' '.$uname.' '.adm_translate("des modérateurs du forum").' '.$row[0].'" data-toggle="tooltip" data-placement="right"><i class="glyphicons glyphicons-user-flag text-danger"></i></a>';
                        } else {
                           echo'&nbsp;<i class="glyphicons glyphicons-user-flag" title="'.adm_translate("Ce modérateur")." (".$uname.") ".adm_translate("n'est pas modifiable tant qu'un autre n'est pas nommé pour ce forum").' '.$row[0].'" data-toggle="tooltip" data-placement="right" ></i>';
                        }
                     } else {
                        $tmp_moder[]=$uidX;
                        asort ( $tmp_moder );
                        $new_moder=implode (',',$tmp_moder);
                        echo'&nbsp;<a href="admin.php?op=moderateur_update&amp;forum_id='.$row[0].'&amp;forum_moderator='.$new_moder.'" title="'.adm_translate("Nommer").' '.$uname.' '.adm_translate("comme modérateur du forum").' '.$row[1].' ('.$row[0].')" data-toggle="tooltip" data-placement="right" ><i class="glyphicons glyphicons-user-flag"></i></a>';
                     }
                  }
               }
               echo "</div>\n</li>\n";
            }
         }
         echo "\n</ul>\n";
         $lst_user_json=rtrim($lst_user_json,',');
         $lst_uid_json=rtrim($lst_uid_json,',');

         //==> pliage repliage listes membres groupes
         echo'
         <script type="text/javascript">
         //<![CDATA[
         tog(\'lst_mb_gr_'.$gp.'\',\'show_lst_mb_'.$gp.'\',\'hide_lst_mb_'.$gp.'\');
         //]]>
         </script>
         <i class="glyphicons glyphicons-user-remove text-danger drop" title="'.adm_translate('Exclure TOUS les membres du groupe').' '.$gp.'" data-toggle="tooltip" data-placement="right" onclick="delete_AllMembersGroup(\''.$gp.'\',\''.$lst_uid_json.'\');"></i>';
         //<== liste membres du groupe

         //==> menu groupe
         echo '
         </td>
         <td valign="top" align="right">
            <a class="btn btn-secondary btn-sm" href="admin.php?op=groupe_edit&amp;groupe_id='.$gp.'" title="'.adm_translate("Editer groupe").' '.$gp.'" data-toggle="tooltip" data-placement="left" ><img src="images/admin/ws/pencil.gif" class="vam" border="0" alt="'.adm_translate("Editer").'" /></a>
            <a class="btn btn-secondary btn-sm" href="javascript:void(0);" onclick="bootbox.alert(\''.adm_translate("Avant de supprimer le groupe").' '.$gp.' '.adm_translate("vous devez supprimer TOUS ses membres !").'\');" title="'.adm_translate("Supprimer groupe").' '.$gp.'" data-toggle="tooltip" data-placement="left" ><img src="images/admin/ws/trash.gif" class="vam" border="0" alt="'.adm_translate("Supprimer groupe").'" /></a>
            <a class="btn btn-secondary btn-sm" href="admin.php?op=membre_add&amp;groupe_id='.$gp.'" title="'.adm_translate("Ajouter un ou des membres au groupe").' '.$gp.'" data-toggle="tooltip" data-placement="left" ><img src="images/admin/ws/user_add.gif" class="vam" border="0" alt="'.adm_translate("Ajouter un ou des membres au groupe").'" /></a>
            <a class="btn btn-secondary btn-sm" href="admin.php?op=bloc_groupe_create&amp;groupe_id='.$gp.'" title="'.adm_translate("Créer le bloc WS").' ('.$gp.')" data-toggle="tooltip" data-placement="left" ><img src="images/admin/ws/wrench.gif" class="vam" border="0" alt="'.adm_translate("Créer le bloc WS").'" /></a>';

         if ($result['groupe_pad']==1) {
            echo'<a class="btn btn-secondary btn-sm" href="admin.php?op=pad_remove&amp;groupe_id='.$gp.'" title="'.adm_translate("Désactiver PAD du groupe").' '.$gp.'" data-toggle="tooltip" data-placement="left" ><img src="images/admin/ws/album_remove.gif" class="vam" border="0" alt="'.adm_translate("Désactiver PAD du groupe").' '.$gp.'" /></a>';
         } else {
            echo'<a class="btn btn-secondary btn-sm" href="admin.php?op=pad_create&amp;groupe_id='.$gp.'" title="'.adm_translate("Activer PAD du groupe").' '.$gp.'" data-toggle="tooltip" data-placement="left" ><img src="images/admin/ws/album_add.gif" class="vam" border="0" alt="'.adm_translate("Activer PAD du groupe").' '.$gp.'"   /></a>';
         }
         if ($result['groupe_blocnote']==1) {
            echo'<a class="btn btn-secondary btn-sm" href="admin.php?op=note_remove&amp;groupe_id='.$gp.'" title="'.adm_translate("Désactiver bloc-note du groupe").' '.$gp.'" data-toggle="tooltip" data-placement="left" ><img src="images/admin/ws/note_remove.gif" class="vam" border="0" alt="'.adm_translate("Désactiver bloc-note du groupe").' '.$gp.'" /></a>';
         } else {
            echo'<a class="btn btn-secondary btn-sm" href="admin.php?op=note_create&amp;groupe_id='.$gp.'" title="'.adm_translate("Activer bloc-note du groupe").' '.$gp.'" data-toggle="tooltip" data-placement="left" ><img src="images/admin/ws/note_add.gif" class="vam" border="0" alt="'.adm_translate("Activer bloc-note du groupe").' '.$gp.'" /></a>';
         }
         
         if (file_exists('modules/f-manager/users/groupe_'.$gp.'.conf.php')) {
            echo'<a class="btn btn-secondary btn-sm" href="admin.php?op=workspace_archive&amp;groupe_id='.$gp.'" title="'.adm_translate("Désactiver gestionnaire de fichiers du groupe").' '.$gp.'" data-toggle="tooltip" data-placement="left" ><img src="images/admin/ws/folder_remove.gif" class="vam" border="0" alt="'.adm_translate("Désactiver gestionnaire de fichiers du groupe").' '.$gp.'" /></a>';
         } else {
            echo'<a class="btn btn-secondary btn-sm" href="admin.php?op=workspace_create&amp;groupe_id='.$gp.'" title="'.adm_translate("Activer gestionnaire de fichiers du groupe").' '.$gp.'" data-toggle="tooltip" data-placement="left" ><img src="images/admin/ws/folder_folder.gif" class="vam" border="0" alt="'.adm_translate("Activer gestionnaire de fichiers du groupe").' '.$gp.'" /></a>';
         }
         if ($result['groupe_forum']==1) {
            echo'<a class="btn btn-secondary btn-sm" href="admin.php?op=forum_groupe_delete&amp;groupe_id='.$gp.'" title="'.adm_translate("Supprimer forum du groupe").' '.$gp.'" data-toggle="tooltip" data-placement="left" ><img src="images/admin/ws/document_remove.gif" class="vam" border="0" alt="'.adm_translate("Supprimer forum du groupe").'" /></a>';
         } else {
            echo'<a class="btn btn-secondary btn-sm" href="javascript:void(0);" onclick="javascript:choisir_mod_forum(\''.$gp.'\',\''.$result['groupe_name'].'\',\''.$lst_user_json.'\',\''.$lst_uid_json.'\');" title="'.adm_translate("Créer forum du groupe").' '.$gp.'" data-toggle="tooltip" data-placement="left" ><img src="images/admin/ws/document_add.gif" class="vam" border="0" alt="'.adm_translate("Créer forum du groupe").' '.$gp.'" /></a>';
         }
         if ($result['groupe_mns']==1) {
            echo'<a class="btn btn-secondary btn-sm" href="admin.php?op=groupe_mns_delete&amp;groupe_id='.$gp.'" title="'.adm_translate("Supprimer MiniSite du groupe").' '.$gp.'" data-toggle="tooltip" data-placement="left" ><img src="images/admin/ws/application_remove.gif" class="vam" border="0" alt="'.adm_translate("Supprimer MiniSite du groupe").'" /></a>';
         } else {
            echo'<a class="btn btn-secondary btn-sm" href="admin.php?op=groupe_mns_create&amp;groupe_id='.$gp.'" title="'.adm_translate("Créer MiniSite du groupe").' '.$gp.'" data-toggle="tooltip" data-placement="left" ><img src="images/admin/ws/application_add.gif" class="vam" border="0" alt="'.adm_translate("Créer MiniSite du groupe").' '.$gp.'" /></a>';
         }
         if ($result['groupe_chat']==0) {
            echo'<a class="btn btn-secondary btn-sm" href="admin.php?op=groupe_chat_create&amp;groupe_id='.$gp.'" title="'.adm_translate("Activer chat du groupe").' '.$gp.'" data-toggle="tooltip" data-placement="left" ><img src="images/admin/ws/comment_add.gif" class="vam" border="0" alt="'.adm_translate("Activer chat du groupe").'" /></a>';
         } else {
            echo'<a class="btn btn-secondary btn-sm" href="admin.php?op=groupe_chat_delete&amp;groupe_id='.$gp.'" title="'.adm_translate("Désactiver chat du groupe").' '.$gp.'" data-toggle="tooltip" data-placement="left" ><img src="images/admin/ws/comment_remove.gif" class="vam" border="0" alt="'.adm_translate("Désactiver chat du groupe").'" /></a>';
         }
         echo '
            </td>
         </tr>';
         //<== menu groupe
      }
   }

   // groupes sans membre
   $result=sql_query("select groupe_id, groupe_name, groupe_description from ".$NPDS_Prefix."groupes order by groupe_id ASC");
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
         <tr id="bloc_gr_'.$gp.'">
            <td align="left" valign="top" width="5%">
               <span class="rouge">'.$gp.'</span>
            </td>
            <td valign="top" align="left" width="20%">
               <i class="glyphicons glyphicons-group x2 light"></i><br /><br />'.aff_langue($gp_name).'<p class="text-muted">'.aff_langue($gp_description);
         if (file_exists ('users_private/groupe/'.$gp.'/groupe.png'))
            echo'<br /><br /><img class="img-responsive" src="users_private/groupe/'.$gp.'/groupe.png" />';
            echo'
            </p>
            </td>
            <td valign="top">
               <i class="glyphicons glyphicons-user x2 light"></i> &nbsp;[0]&nbsp;
            </td>
            <td valign="top" align="right">
               <a class="btn btn-secondary btn-sm" href="admin.php?op=groupe_edit&amp;groupe_id='.$gp.'" title="'.adm_translate("Editer groupe").' '.$gp.'" data-toggle="tooltip" data-placement="left" ><img src="images/admin/ws/pencil.gif" class="vam" border="0" alt="'.adm_translate("Editer").'" /></a>
               <a class="btn btn-secondary btn-sm" href="#" onclick="confirm_deleteGroup(\''.$gp.'\');" title="'.adm_translate("Supprimer groupe").' '.$gp.'" data-toggle="tooltip" data-placement="left"><img src="images/admin/ws/trash.gif" class="vam" border="0" alt="'.adm_translate("Supprimer groupe").'"  /></a>
               <a class="btn btn-secondary btn-sm" href="admin.php?op=membre_add&amp;groupe_id='.$gp.'" title="'.adm_translate("Ajouter un ou des membres au groupe").' '.$gp.'" data-toggle="tooltip" data-placement="left"><img src="images/admin/ws/user_add.gif" class="vam" border="0" alt="'.adm_translate("Ajouter un ou des membres au groupe").'" /></a><br />
            </td>
         </tr>';
      }
   }
   $lst_gr_json=rtrim ($lst_gr_json,',');
   echo '
      </tbody>
   </table>';
   
    adminfoot('','','','');

}

// MEMBRE
function membre_add($gp) {
   global $hlpfile, $f_meta_nom, $f_titre, $adminimg;
   include ('header.php');
   GraphicAdmin($hlpfile);
   adminhead($f_meta_nom, $f_titre, $adminimg);
   echo '
   <h3>'.adm_translate("Ajouter des membres").' / '.adm_translate("Groupe").' : '.$gp.'</h3>
   <form id="form_ad_mb_gr" class="admform" action="admin.php" method="post">
      <fieldset>
         <legend>&nbsp;&nbsp;<i class="glyphicons glyphicons-group x2 light"></i></legend>
         <div class="form-group">
            <label class="form-control-label" for="luname">'.adm_translate("Liste des membres").'</label>
            <input type="text" class="form-control" id="luname" name="luname" maxlength="255" value="" />
         </div>
         <input type="hidden" name="op" value="membre_add_finish" />
         <input type="hidden" name="groupe_id" value="'.$gp.'" />
         <div class="form-group">
            <input class="btn btn-primary" type="submit" name="sub_op" value="'.adm_translate("Sauver les modifications").'" />
         </div>
      </fieldset>
   </form>';
   echo auto_complete_multi ('membre','uname','users','luname','inner join users_status on users.uid=users_status.uid where users.uid<>1 and groupe not regexp \'[[:<:]]'.$gp.'[[:>:]]\'');
   adminfoot('fv','','','');
}
function membre_add_finish($groupe_id, $luname) {
   global $NPDS_Prefix;
   include('powerpack_f.php');

   $luname=rtrim ( $luname ,", ");
   $luname=str_replace(' ','',$luname);
   $list_membres=explode(",",$luname);
   $nbremembres=count($list_membres);
   $subject=adm_translate('Nouvelles du groupe');
   $message=adm_translate('Vous faites désormais partie des membres du groupe').' '.$groupe_id.'.';
   $copie='';
   $from_userid=1;

   for ($j=0;$j<$nbremembres;$j++) {
      $uname=$list_membres[$j];
      $result1 = sql_query("select uid from ".$NPDS_Prefix."users where uname='$uname'");
      $ibid=sql_fetch_assoc($result1);
      if ($ibid['uid']) {
         $to_userid=$uname;
         $result2 = sql_query("select groupe from ".$NPDS_Prefix."users_status where uid='".$ibid['uid']."'");
         $ibid2=sql_fetch_assoc($result2);
         $lesgroupes=explode(",",$ibid2['groupe']);
         $nbregroupes=count($lesgroupes);

         $groupeexistedeja=false;
         for ($i=0; $i<$nbregroupes;$i++) {
            if ($lesgroupes[$i]==$groupe_id) { $groupeexistedeja=true; break; }
         }
         if (!$groupeexistedeja) {
            if ($ibid2['groupe']) $groupesmodif=$ibid2['groupe'].",".$groupe_id;
            else $groupesmodif=$groupe_id;
            $resultat = sql_query("update ".$NPDS_Prefix."users_status set groupe='$groupesmodif' where uid='".$ibid['uid']."'");
         }
         writeDB_private_message($to_userid,$image,$subject,$from_userid,$message, $copie);
      }
   }
   global $aid; Ecr_Log("security", "AddMemberToGroup($groupe_id, $luname) by AID : $aid", "");
   Header("Location: admin.php?op=groupes");
}
function retiredugroupe($groupe_id, $uid, $uname) {
   global $NPDS_Prefix;
   include('powerpack_f.php');

   $pat='#^\b'.$uid.'\b$#';
   $mes_sys='';
   $q='';
   $ok=0;
   $res=sql_query("select f.forum_id, f.forum_name, f.forum_moderator from ".$NPDS_Prefix."forums f where f.forum_pass='$groupe_id' and cat_id='-1'");
   while ($row = sql_fetch_row($res)) {
      if (preg_match($pat, $row[2])) {
         $mes_sys='mod_'.$uname;
         $q='&al='.$mes_sys;
         $ok=1;
      }
   }

   if ($ok==0) {
      $pat='#\b'.$uid.'\b#';
      $res=sql_query("select f.forum_id, f.forum_name, f.forum_moderator from ".$NPDS_Prefix."forums f where f.forum_pass='$groupe_id' and cat_id='-1'");
      while ($r = sql_fetch_row($res)) {
         $new_moder=preg_replace('#,,#',',',trim(preg_replace ($pat,'',$r[2]),','));
         sql_query("update ".$NPDS_Prefix."forums set forum_moderator='$new_moder' where forum_id='$r[0]'");
      };
   
      $resultat=sql_query("select groupe from ".$NPDS_Prefix."users_status where uid='$uid'");
      $subject=adm_translate('Nouvelles du groupe');
      $message=adm_translate('Vous ne faites plus partie des membres du groupe').' '.$groupe_id.'.';
      $copie='';
      $from_userid=1;
      $to_userid=$uname;
      $valeurs=sql_fetch_assoc($resultat);
      $lesgroupes=explode(",",$valeurs['groupe']);
      $nbregroupes=count($lesgroupes);
      $groupesmodif="";
      for ($i=0; $i<$nbregroupes;$i++) {
         if ($lesgroupes[$i]!=$groupe_id) {
            if ($groupesmodif=="") $groupesmodif.=$lesgroupes[$i];
            else $groupesmodif.=",".$lesgroupes[$i];
         }
      }
      $resultat = sql_query("update ".$NPDS_Prefix."users_status set groupe='$groupesmodif' where uid='$uid'");
      writeDB_private_message($to_userid,$image,$subject,$from_userid,$message, $copie);
      global $aid; Ecr_Log("security", "DeleteMemberToGroup($groupe_id, $uname) by AID : $aid", "");
   }
   Header("Location: admin.php?op=groupes".$q);
}
function retiredugroupe_all($groupe_id,$tab_groupe) {
   global $NPDS_Prefix;
   $tab_groupe=explode ( ',', $tab_groupe );
   while (list($bidon,$uidZ)=each($tab_groupe)) {
      if ($uidZ) {
         // a rajouter enlever modérateur forum
         $resultat=sql_query("select groupe from ".$NPDS_Prefix."users_status where uid='$uidZ'");
         $valeurs=sql_fetch_assoc($resultat);
         $lesgroupes=explode(",",$valeurs['groupe']);
         $nbregroupes=count($lesgroupes);
         $groupesmodif="";
         for ($i=0; $i<$nbregroupes;$i++) {
            if ($lesgroupes[$i]!=$groupe_id) {
               if ($groupesmodif=="") $groupesmodif.=$lesgroupes[$i];
               else $groupesmodif.=",".$lesgroupes[$i];
            }
         }
         $resultat = sql_query("update ".$NPDS_Prefix."users_status set groupe='$groupesmodif' where uid='$uidZ'");
         global $aid; Ecr_Log("security", "DeleteAllMemberToGroup($groupe_id, $uidZ) by AID : $aid", "");
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

   if ($groupe_id!="groupe_add")
      echo '<h3>'.adm_translate("Modifier le groupe").' : '.$groupe_id.'</h3>';
   else
      echo '<h3>'.adm_translate("Créer un groupe.").'</h3>';
   echo '
   <form class="admform" id="form_aded_gr" action="admin.php" method="post">
      <fieldset>
         <legend>&nbsp;&nbsp;<i class="glyphicons glyphicons-group x2 light"></i></legend>'."\n";
   if ($groupe_id!="groupe_add")
      echo '<input type="hidden" name="groupe_id" value="'.$groupe_id.'" />';
   else
      echo '
         <div class="form-group">
            <label for="inp_gr_id" class="admform">ID</label>
            <input id="inp_gr_id" type="number" min="2" max="126" class="form-control" name="groupe_id" value="" required="required"/><span class="help-block">(2...126)</span>
         </div>';
      echo '
         <div class="form-group">
            <label class="form-control-label" for="inp_gr_na">'.adm_translate("Nom").'</label>
            <input id="inp_gr_na" type="text" class="form-control" name="groupe_name" maxlength="30" value="'.$result['groupe_name'].'" placeholder="'.adm_translate("Nom du groupe").'" required="required" />
         </div>
         <div class="form-group">
            <label class="form-control-label" for="groupe_description">'.adm_translate("Description").'</label>
            <textarea class="form-control" name="groupe_description" id="groupe_description" rows="11" maxlength="255" placeholder="'.adm_translate("Description du groupe").'" required="required">'.$result['groupe_description'].'</textarea>
         </div>';
   if ($groupe_id!="groupe_add")
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
      sql_query("update ".$NPDS_Prefix."groupes set groupe_name='$groupe_name', groupe_description='$groupe_description' where groupe_id='$groupe_id'");
      global $aid; Ecr_Log("security", "UpdateGroup($groupe_id) by AID : $aid", "");
   }
   if ($sub_op==adm_translate("Supprimer")) {
      $result = sql_query("select uid, groupe from ".$NPDS_Prefix."users_status where groupe!='' order by uid ASC");
      $maj_ok=true;
      while (list($to_userid, $groupeX) = sql_fetch_row($result)) {
         $tab_groupe=explode(",",$groupeX);
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
   global $hlpfile, $NPDS_Prefix;
   global $groupe_name, $groupe_description, $sub_op;

   sql_query("delete from ".$NPDS_Prefix."lblocks where member='$groupe_id'");
   sql_query("delete from ".$NPDS_Prefix."rblocks where member='$groupe_id'");
   sql_query("delete from ".$NPDS_Prefix."groupes where groupe_id='$groupe_id'");
   sql_query("delete FROM ".$NPDS_Prefix."blocnotes WHERE bnid='".md5("WS-BN".$groupe_id)."'");

   forum_groupe_delete($groupe_id);
   workspace_archive($groupe_id);
   groupe_mns_delete($groupe_id);

   //  todo - Supprimer YUI si plus de WorkSpace chargé - si YUI n'est utilisé que par WS ...

   global $aid; Ecr_Log("security", "DeleteGroup($groupe_id) by AID : $aid", "");
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
   if ($DOCUMENTROOT=="") {
      global $DOCUMENT_ROOT;
      if ($DOCUMENT_ROOT) {
         $DOCUMENTROOT=$DOCUMENT_ROOT;
      } else {
         $DOCUMENTROOT=$_SERVER['DOCUMENT_ROOT'];
      }
   }
   $user_dir=$DOCUMENTROOT.$racine."/users_private/groupe/".$groupe_id;

   // DOCUMENTS_GROUPE
   @mkdir('users_private/groupe/'.$groupe_id.'/documents_groupe');
   $repertoire=$user_dir."/documents_groupe";
   $directory=$racine."/modules/groupe/matrice/documents_groupe";
   $handle=opendir($DOCUMENTROOT.$directory);
   while (false!==($file = readdir($handle))) $filelist[] = $file;
   asort($filelist);
   while (list ($key, $file) = each ($filelist)) {
      if ($file<>"." and $file<>"..") {
         @copy($DOCUMENTROOT.$directory."/".$file, $repertoire."/".$file);
      }
   }
   closedir($handle);
   unset ($filelist);

   // IMAGES_GROUPE
   @mkdir('users_private/groupe/'.$groupe_id.'/images_groupe');
   $repertoire=$user_dir."/images_groupe";
   $directory=$racine."/modules/groupe/matrice/images_groupe";
   $handle=opendir($DOCUMENTROOT.$directory);
   while (false!==($file = readdir($handle))) $filelist[] = $file;
   asort($filelist);
   while (list ($key, $file) = each ($filelist)) {
      if ($file<>"." and $file<>"..") {
         @copy($DOCUMENTROOT.$directory."/".$file, $repertoire."/".$file);
      }
   }
   closedir($handle);
   unset ($filelist);
   @unlink('users_private/groupe/'.$groupe_id.'/delete');

   global $aid; Ecr_Log("security", "CreateWS($groupe_id) by AID : $aid", "");
}

// PAD
function pad_create($groupe_id) {
   global $NPDS_Prefix;

   sql_query("update ".$NPDS_Prefix."groupes set groupe_pad = '1' where groupe_id = '$groupe_id';");
   global $aid; Ecr_Log("security", "CreatePadWS($groupe_id) by AID : $aid", "");
}
function pad_remove($groupe_id) {
   global $NPDS_Prefix;
   
   sql_query("update ".$NPDS_Prefix."groupes set groupe_pad = '0' where groupe_id = '$groupe_id';");
   global $aid; Ecr_Log("security", "DeletePadWS($groupe_id) by AID : $aid", "");
}

// BLOC-NOTE
function note_create($groupe_id) {
   global $NPDS_Prefix;

   // => Creation table blocnotes
   $type_engine=(int)substr(mysql_get_server_info(), 0, 1);
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

   sql_query("update ".$NPDS_Prefix."groupes set groupe_blocnote = '1' where groupe_id = '$groupe_id';");
   global $aid; Ecr_Log("security", "CreateBlocnoteWS($groupe_id) by AID : $aid", "");
}
function note_remove($groupe_id) {
   global $NPDS_Prefix;
   
   sql_query("delete from ".$NPDS_Prefix."blocnotes where bnid='".md5("WS-BN".$groupe_id)."'");
   sql_query("update ".$NPDS_Prefix."groupes set groupe_blocnote = '0' where groupe_id = '$groupe_id';");

   global $aid; Ecr_Log("security", "DeleteBlocnoteWS($groupe_id) by AID : $aid", "");
}

function workspace_archive($groupe_id) {
   //=> archivage espace groupe
   $fp=fopen ('users_private/groupe/'.$groupe_id.'/delete','w');
   fclose($fp);
   //suppression fichier conf
   @unlink('modules/f-manager/users/groupe_'.$groupe_id.'.conf.php');
   global $aid; Ecr_Log("security", "ArchiveWS($groupe_id) by AID : $aid", "");
}

// FORUMS
function forum_groupe_create($groupe_id,$groupe_name,$description,$moder) {
    global $NPDS_Prefix;

    // creation forum
    // creation catégorie forum_groupe
    $result=sql_query("select cat_id from ".$NPDS_Prefix."catagories where cat_id = -1;");
    list($cat_id)=sql_fetch_row($result);
    if (!$cat_id) {
       sql_query("insert into ".$NPDS_Prefix."catagories values (-1, '".adm_translate("Groupe de travail")."')");
    };
    //==>creation forum

    echo "$groupe_id,$groupe_name,$description,$moder";

    sql_query("insert into ".$NPDS_Prefix."forums values (NULL, '$groupe_name', '$description', '1', '$moder', '-1', '7', '$groupe_id', '0', '0', '0')");
    //=> ajout etat forum (1 ou 0) dans le groupe
    sql_query("update ".$NPDS_Prefix."groupes set groupe_forum = '1' where groupe_id = '$groupe_id';");
    global $aid; Ecr_Log("security", "CreateForumWS($groupe_id) by AID : $aid", "");
}

function moderateur_update($forum_id,$forum_moderator) {
    global $NPDS_Prefix;

    sql_query("update ".$NPDS_Prefix."forums set forum_moderator = '$forum_moderator' where forum_id='$forum_id'");
}
function forum_groupe_delete($groupe_id) {
    global $NPDS_Prefix;

    $result=sql_query("select forum_id from ".$NPDS_Prefix."forums where forum_pass='$groupe_id' and cat_id='-1'");
    list($forum_id) = sql_fetch_row($result);
    // suppression des topics
    sql_query("delete from ".$NPDS_Prefix."forumtopics where forum_id='$forum_id'");
    // maj table lecture
    sql_query("delete from ".$NPDS_Prefix."forum_read where forum_id='$forum_id'");

    //=> suppression du forum
    sql_query("delete from ".$NPDS_Prefix."forums where forum_id='$forum_id'");
    // =>remise à 0 forum dans le groupe
    sql_query("update ".$NPDS_Prefix."groupes set groupe_forum = '0' where groupe_id='$groupe_id'");
    global $aid; Ecr_Log("security", "DeleteForumWS($forum_id) by AID : $aid", "");
}

// MNS
function groupe_mns_create($groupe_id) {
   global $NPDS_Prefix;
   include ("modules/upload/upload.conf.php");

   if ($DOCUMENTROOT=="") {
      global $DOCUMENT_ROOT;
      if ($DOCUMENT_ROOT) {
         $DOCUMENTROOT=$DOCUMENT_ROOT;
      } else {
         $DOCUMENTROOT=$_SERVER['DOCUMENT_ROOT'];
      }
   }
   $user_dir=$DOCUMENTROOT.$racine."/users_private/groupe/".$groupe_id;
   $repertoire=$user_dir."/mns";
   if (!is_dir($user_dir)) {
      @umask("0000");
      if (@mkdir($user_dir,0777)) {
         $fp = fopen($user_dir."/index.html", 'w');
         fclose($fp);
         @umask("0000");
         if (@mkdir($repertoire,0777)) {
            $fp = fopen($repertoire."/index.html", 'w');
            fclose($fp);
            $fp = fopen($repertoire."/.htaccess", 'w');
            @fputs($fp, "Deny from All");
            fclose($fp);
         }
      }
   } else {
      @umask("0000");
      if (@mkdir($repertoire,0777)) {
         $fp = fopen($repertoire."/index.html", 'w');
         fclose($fp);
         $fp = fopen($repertoire."/.htaccess", 'w');
         @fputs($fp, "Deny from All");
         fclose($fp);
      }
   }
   // copie de la matrice par défaut
   $directory=$racine."/modules/groupe/matrice/mns_groupe";
   $handle=opendir($DOCUMENTROOT.$directory);
   while (false!==($file = readdir($handle))) $filelist[] = $file;
   asort($filelist);
   while (list ($key, $file) = each ($filelist)) {
      if ($file<>"." and $file<>"..") {
         @copy($DOCUMENTROOT.$directory."/".$file, $repertoire."/".$file);
      }
   }
   closedir($handle);
   unset ($filelist);
   sql_query("update ".$NPDS_Prefix."groupes set groupe_mns = '1' where groupe_id = '$groupe_id';");
   global $aid; Ecr_Log("security", "CreateMnsWS($groupe_id) by AID : $aid", "");
}
function groupe_mns_delete($groupe_id) {
   global $NPDS_Prefix;
   include ("modules/upload/upload.conf.php");

   if ($DOCUMENTROOT=="") {
      global $DOCUMENT_ROOT;
      if ($DOCUMENT_ROOT) {
         $DOCUMENTROOT=$DOCUMENT_ROOT;
      } else {
         $DOCUMENTROOT=$_SERVER['DOCUMENT_ROOT'];
      }
   }
   $user_dir=$DOCUMENTROOT.$racine."/users_private/groupe/".$groupe_id;

   // Supprimer son ministe s'il existe
   if (is_dir($user_dir."/mns")) {
      $dir = opendir($user_dir."/mns");
      while(false!==($nom = readdir($dir))) {
         if ($nom != "." && $nom != ".." && $nom != "") {
            @unlink($user_dir."/mns/".$nom);
         }
     }
     closedir($dir);
     @rmdir($user_dir."/mns");
   }
   sql_query("update ".$NPDS_Prefix."groupes set groupe_mns = '0' where groupe_id = '$groupe_id';");
   global $aid; Ecr_Log("security", "DeleteMnsWS($groupe_id) by AID : $aid", "");
}

// CHAT
function groupe_chat_create($groupe_id) {
   global $NPDS_Prefix;

   sql_query("update ".$NPDS_Prefix."groupes set groupe_chat = '1' where groupe_id = '$groupe_id';");
   global $aid; Ecr_Log("security", "ActivateChatWS($groupe_id) by AID : $aid", "");
}
function groupe_chat_delete($groupe_id) {
   global $NPDS_Prefix;

   sql_query("update ".$NPDS_Prefix."groupes set groupe_chat = '0' where groupe_id = '$groupe_id';");
   global $aid; Ecr_Log("security", "DesactivateChatWS($groupe_id) by AID : $aid", "");
}

function bloc_groupe_create($groupe_id) {
   global $NPDS_Prefix;

   // Creation bloc espace de travail user et du chargeur YUI (si nécessaire)
   // On recherche si YUI est déjà installée via un bloc
   $yui=false;
   $row=sql_fetch_row(sql_query("SELECT count(id) FROM ".$NPDS_Prefix."lblocks WHERE content like '%yui-min.js%'"));
   if ($row[0]==0) {
      $row=sql_fetch_row(sql_query("SELECT count(id) FROM ".$NPDS_Prefix."rblocks WHERE content like '%yui-min.js%'"));
      if ($row[0]<>0)
         $yui=true;
   } else {
      $yui=true;
   }
   if ($yui==false) {
      sql_query("INSERT into ".$NPDS_Prefix."lblocks values (NULL, 'YUI loader', 'hidden#<script type=\"text/javascript\" src=\"lib/yui/build/yui/yui-min.js\"></script>', '0', '-99', '86400', '1', '0', 'Ce bloc charge YUI / This block load YUI')");
   }

   // On créer le bloc s'il n'existe pas déjà
   $bloc=false;
   $menu_workspace="function#bloc_espace_groupe\r\nparams#$groupe_id,1";
   $row=sql_fetch_row(sql_query("SELECT count(id) FROM ".$NPDS_Prefix."lblocks WHERE content='$menu_workspace'"));
   if ($row[0]==0) {
      $row=sql_fetch_row(sql_query("SELECT count(id) FROM ".$NPDS_Prefix."rblocks WHERE content='$menu_workspace'"));
      if ($row[0]<>0)
         $bloc=true;
   } else {
      $bloc=true;
   }
   if ($bloc==false) {
      sql_query("INSERT into ".$NPDS_Prefix."lblocks values (NULL, '', '$menu_workspace', '$groupe_id', '3', '0', '1', '0', NULL)");
   }
}

switch ($op) {
   case "membre_add":
        membre_add($groupe_id);
        break;
   case "membre_add_finish":
        membre_add_finish($groupe_id,$luname);
        break;
   case "retiredugroupe":
        retiredugroupe($groupe_id,$uid,$uname);
        break;
   case "retiredugroupe_all":
        retiredugroupe_all($groupe_id,$tab_groupe);
        break;

   case "pad_create":
        pad_create($groupe_id);
        Header("Location: admin.php?op=groupes");    
        break;
   case "pad_remove":
        pad_remove($groupe_id);
        Header("Location: admin.php?op=groupes");    
        break;
        
   case "note_create":
        note_create($groupe_id);
        Header("Location: admin.php?op=groupes");    
        break;
   case "note_remove":
        note_remove($groupe_id);
        Header("Location: admin.php?op=groupes");
        break;
   
   case "workspace_create":
        workspace_create($groupe_id);
        Header("Location: admin.php?op=groupes");
        break;
   case "workspace_archive":
        workspace_archive($groupe_id);
        Header("Location: admin.php?op=groupes");
        break;

   case "forum_groupe_create":
        forum_groupe_create($groupe_id,$groupe_name,$description,$moder);
        break;
   case "moderateur_update":
        moderateur_update($forum_id,$forum_moderator);
        Header('location: admin.php?op=groupes');
        break;
   case "forum_groupe_delete":
        forum_groupe_delete($groupe_id);
        Header('location: admin.php?op=groupes');
        break;

   case "groupe_mns_create":
        groupe_mns_create($groupe_id);
        Header('location: admin.php?op=groupes');
        break;
   case "groupe_mns_delete":
        groupe_mns_delete($groupe_id);
        Header('location: admin.php?op=groupes');
        break;

   case "groupe_chat_create":
        groupe_chat_create($groupe_id);
        Header('location: admin.php?op=groupes');
        break;
   case "groupe_chat_delete":
        groupe_chat_delete($groupe_id);
        Header('location: admin.php?op=groupes');
        break;

   case "groupe_edit":
        groupe_edit($groupe_id);
        break;
   case "groupe_maj":
        groupe_maj();
        break;
   case "groupe_add":
        groupe_edit("groupe_add");
        break;
   case "bloc_groupe_create":
        bloc_groupe_create($groupe_id);
        Header('location: admin.php?op=groupes');
        break;
   case "groupe_add_finish":
        $ok_grp=false;
        if (($groupe_id=="") or ($groupe_id<2) or ($groupe_id>126)) {
           $row=sql_fetch_row(sql_query("SELECT MAX(groupe_id) from ".$NPDS_Prefix."groupes"));      
           if ($row[0]<126) {
              if ($row[0]==0) $row[0]=1;
              $groupe_id=$row[0]+1;
              $ok_grp=true;
           }           
        }  else {
           $ok_grp=true;
        }
        if ($ok_grp) {
           sql_query("INSERT into ".$NPDS_Prefix."groupes values ('$groupe_id', '$groupe_name','$groupe_description','0','0','0','0','0')");
           @mkdir('users_private/groupe/'.$groupe_id);
           $fp=fopen ('users_private/groupe/'.$groupe_id.'/index.html','w');
           fclose($fp);
           @copy('modules/groupe/matrice/groupe.png','users_private/groupe/'.$groupe_id.'/groupe.png');
           @unlink('users_private/groupe/'.$groupe_id.'/delete');
           
           global $aid; Ecr_Log("security", "CreateGroupe($groupe_id, $groupe_name) by AID : $aid", "");
        }
   default:
        group_liste();
        break;
}
?>
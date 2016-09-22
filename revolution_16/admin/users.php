<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],"admin.php")) { Access_Error(); }
$f_meta_nom ='mod_users';
$f_titre = adm_translate("Edition des Utilisateurs");

//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
global $language;
$hlpfile = "manuels/$language/users.html";

function displayUsers() {
   global $hlpfile, $admf_ext, $f_meta_nom, $f_titre, $adminimg;
   include("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '
   <hr />
    <h3>'.adm_translate("Extraire l'annuaire").'</h3>
    <form method="post" class="form-inline" action="admin.php">
        <fieldset>
            <div class="form-group">
                <label for="op">'.adm_translate("Format de fichier").'</label>
                <select class="custom-select form-control" name="op">
                    <option value="extractUserCSV">'.adm_translate("Au format CSV").'</option>
                </select>
            </div>
            <button class="btn btn-primary" type="submit">'.adm_translate("Ok").' </button>
        </fieldset>
    </form>
    <hr />
    <h3>'.adm_translate("Rechercher utilisateur").'</h3>
    <form method="post" class="form-inline" action="admin.php">
        <fieldset>
            <div class="form-group">
               <label for="chng_uid">'.adm_translate("Identifiant Utilisateur").'</label>
               <input class="form-control" type="text" id="chng_uid" name="chng_uid" size="20" maxlength="10" />
            </div>
            <select class="custom-select form-control" name="op">
                <option value="modifyUser">'.adm_translate("Modifier un utilisateur").'</option>
                <option value="unsubUser">'.adm_translate("Désabonner un utilisateur").'</option>
                <option value="delUser">'.adm_translate("Supprimer un utilisateur").'</option>
            </select>
            <button class="btn btn-primary" type="submit" >'.adm_translate("Ok").' </button>
        </fieldset>
    </form>';
    $chng_is_visible=1;
    echo '
    <hr />
    <h3>'.adm_translate("Créer utilisateur").'</h3>';
   $op='displayUsers';
   include ("modules/sform/extend-user/adm_extend-user.php");
   echo auto_complete ('membre','uname','users','chng_uid','86400');
   adminfoot('','','','');
}

function extractUserCSV() {
   global $NPDS_Prefix;

   include("lib/archive.php");
   $MSos=get_os();
   if ($MSos) {$crlf="\r\n";} else {$crlf="\n";}
   $deliminator=';';
   $line = "UID;UNAME;NAME;URL;EMAIL;FEMAIL;C1;C2;C3;C4;C5;C6;C7;C8;M1;M2;T1;T2".$crlf;
   $result = sql_query("SELECT uid, uname, name, url, email, femail FROM ".$NPDS_Prefix."users WHERE uid!='1' ORDER BY uid");
   while($temp_user = sql_fetch_row($result) ) {
      foreach($temp_user as $val) {
        $val = str_replace("\r\n", "\n", $val);
        if (preg_match("#[$deliminator\"\n\r]#",$val)) {
           $val = '"'.str_replace('"', '""', $val).'"';
        }
        $line .= $val.$deliminator;
      }
      $result2=sql_query("SELECT C1, C2, C3, C4, C5, C6, C7, C8, M1, M2, T1, T2 FROM ".$NPDS_Prefix."users_extend WHERE uid='$temp_user[0]'");
      $temp_user2 = sql_fetch_row($result2);
      if ($temp_user2) {
         foreach($temp_user2 as $val2) {
           $val2 = str_replace("\r\n", "\n", $val2);
           if (preg_match("#[$deliminator\"\n\r]#",$val2)) {
              $val2 = '"'.str_replace('"', '""', $val2).'"';
           }
           $line .= $val2.$deliminator;
          }
      }
      $line = substr($line, 0, (strlen($deliminator) * -1));
      $line .= $crlf;
   }
   send_file($line,"annuaire","csv",$MSos);
   global $aid; Ecr_Log('security', "ExtractUserCSV() by AID : $aid", '');
}

function modifyUser($chng_user) {
   global $hlpfile, $NPDS_Prefix, $admf_ext, $f_meta_nom, $f_titre, $adminimg;
   include("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   $result = sql_query("SELECT uid, uname, name, url, email, femail, user_from, user_occ, user_intrest, user_viewemail, user_avatar, user_sig, bio, pass, send_email, is_visible, mns, user_lnl FROM ".$NPDS_Prefix."users WHERE uid='$chng_user' OR uname='$chng_user'");
   if (sql_num_rows($result) > 0) {
      list($chng_uid, $chng_uname, $chng_name, $chng_url, $chng_email, $chng_femail, $chng_user_from, $chng_user_occ, $chng_user_intrest, $chng_user_viewemail, $chng_avatar, $chng_user_sig, $chng_bio, $chng_pass, $chng_send_email, $chng_is_visible, $mns, $user_lnl) = sql_fetch_row($result);
      echo '
      <hr />
      <h3>'.adm_translate("Modifier un utilisateur").' : '.$chng_uname.' / '.$chng_uid.'</h3>';
      $op='ModifyUser';
      $result = sql_query("SELECT level, open, groupe, attachsig, rank FROM ".$NPDS_Prefix."users_status WHERE uid='$chng_uid'");
      list ($chng_level, $open_user, $groupe, $attach, $chng_rank) = sql_fetch_row($result);
      $result = sql_query("SELECT C1, C2, C3, C4, C5, C6, C7, C8, M1, M2, T1, T2, B1 FROM ".$NPDS_Prefix."users_extend WHERE uid='$chng_uid'");
      list($C1, $C2, $C3, $C4, $C5, $C6, $C7, $C8, $M1, $M2, $T1, $T2, $B1) = sql_fetch_row($result);
      include ("modules/sform/extend-user/adm_extend-user.php");
   } else {
      error_handler("Utilisateur inexistant !"."<br />");
   }
   adminfoot('','','','');
}

function error_handler($ibid) {
   echo '
   <div class="alert alert-danger" align="center">'.adm_translate("Merci d'entrer l'information en fonction des spécifications").'<br />
   <strong>'.$ibid.'</strong><br /><a class="btn btn-secondary" href="admin.php?op=mod_users" >'.adm_translate("Retour en arrière").'</a>
   </div>';
}

function Minisites($chng_mns,$chng_uname) {
   // Création de la structure pour les MiniSites dans users_private/$chng_uname
   if ($chng_mns) {
      include ("modules/upload/upload.conf.php");
      if ($DOCUMENTROOT=='') {
         global $DOCUMENT_ROOT;
         if ($DOCUMENT_ROOT) {
            $DOCUMENTROOT=$DOCUMENT_ROOT;
         } else {
            $DOCUMENTROOT=$_SERVER['DOCUMENT_ROOT'];
         }
      }
      $user_dir=$DOCUMENTROOT.$racine."/users_private/".$chng_uname;
      $repertoire=$user_dir."/mns";
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
            @fputs($fp, "Deny from All");
            fclose($fp);
         }
      }

      // copie de la matrice par défaut
      $directory=$racine.'/modules/blog/matrice';
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
      global $aid; Ecr_Log('security', "CreateMiniSite($chng_uname) by AID : $aid", '');
   }
}

function updateUser($chng_uid, $chng_uname, $chng_name, $chng_url, $chng_email, $chng_femail, $chng_user_from, $chng_user_occ, $chng_user_intrest, $chng_user_viewemail, $chng_avatar, $chng_user_sig, $chng_bio, $chng_pass, $chng_pass2, $level, $open_user, $chng_groupe, $chng_send_email, $chng_is_visible, $chng_mns, $C1,$C2,$C3,$C4,$C5,$C6,$C7,$C8,$M1,$M2,$T1,$T2,$B1,$raz_avatar, $chng_rank, $chng_lnl) {
   global $NPDS_Prefix;
   $tmp = 0;
   if ($chng_pass2 != '') {
      if ($chng_pass != $chng_pass2) {
         global $hlpfile,$f_meta_nom, $f_titre, $adminimg;
         include("header.php");
         GraphicAdmin($hlpfile);
         adminhead ($f_meta_nom, $f_titre, $adminimg);
         echo error_handler(adm_translate("Désolé, les nouveaux Mots de Passe ne correspondent pas. Cliquez sur retour et recommencez")."<br />");
         adminfoot('','','','');
         return;
      }
      $tmp=1;
   }
   
   $result = sql_query("SELECT mns FROM ".$NPDS_Prefix."users WHERE uid='$chng_uid'");
   list($tmp_mns)=sql_fetch_row($result);
   if ($tmp_mns==0 and $chng_mns==1) {
      Minisites($chng_mns,$chng_uname);
   }

   if ($chng_send_email=='') {$chng_send_email='0';}
   if ($chng_is_visible=='') {
      $chng_is_visible='1';
   } else {
      $chng_is_visible='0';
   }
   if ($raz_avatar) {$chng_avatar="blank.gif";}
   if ($tmp==0) {
      sql_query("UPDATE ".$NPDS_Prefix."users SET uname='$chng_uname', name='$chng_name', email='$chng_email', femail='$chng_femail', url='$chng_url', user_from='$chng_user_from', user_occ='$chng_user_occ', user_intrest='$chng_user_intrest', user_viewemail='$chng_user_viewemail', user_avatar='$chng_avatar', user_sig='$chng_user_sig', bio='$chng_bio', send_email='$chng_send_email', is_visible='$chng_is_visible', mns='$chng_mns', user_lnl='$chng_lnl' WHERE uid='$chng_uid'");
   }
   if ($tmp==1) {
      global $system;
      if (!$system) {
         $cpass = crypt($chng_pass,$chng_pass);
      } else {
         $cpass=$chng_pass;
      }
      sql_query("UPDATE ".$NPDS_Prefix."users SET uname='$chng_uname', name='$chng_name', email='$chng_email', femail='$chng_femail', url='$chng_url', user_from='$chng_user_from', user_occ='$chng_user_occ', user_intrest='$chng_user_intrest', user_viewemail='$chng_user_viewemail', user_avatar='$chng_avatar', user_sig='$chng_user_sig', bio='$chng_bio', send_email='$chng_send_email', is_visible='$chng_is_visible', mns='$chng_mns', pass='$cpass', user_lnl='$chng_lnl' WHERE uid='$chng_uid'");
   }
   if ($chng_user_viewemail) {
      $attach = 1;
   } else {
     $attach = 0;
   }
   if ($open_user=='') {$open_user=0;}
   if (preg_match('#[a-zA-Z_]#',$chng_groupe)) {$chng_groupe='';}
   if ($chng_groupe!='') {
      $tab_groupe=explode(',',$chng_groupe);
      if ($tab_groupe) {
         foreach($tab_groupe as $groupevalue) {
           if ( ($groupevalue=="0") and ($groupevalue!='') ) {$chng_groupe='';}
           if ($groupevalue=="1") {$chng_groupe='';}
           if ($groupevalue>"127") {$chng_groupe='';}
         }
      }
   }
   sql_query("UPDATE ".$NPDS_Prefix."users_status SET attachsig='$attach', level='$level', open='$open_user', groupe='$chng_groupe', rank='$chng_rank' WHERE uid='$chng_uid'");
   sql_query("UPDATE ".$NPDS_Prefix."users_extend SET C1='$C1', C2='$C2', C3='$C3', C4='$C4', C5='$C5', C6='$C6', C7='$C7', C8='$C8', M1='$M1', M2='$M2', T1='$T1', T2='$T2', B1='$B1' WHERE uid='$chng_uid'");
   
   global $aid; Ecr_Log("security", "UpdateUser($chng_uid, $chng_uname) by AID : $aid", "");

   global $referer;
   if ($referer!="memberslist.php")
      Header("Location: admin.php?op=mod_users");
   else
      Header("Location: memberslist.php");
}

switch ($op) {
   case 'extractUserCSV':
        extractUserCSV();
        break;

   case "modifyUser":
        modifyUser($chng_uid);
        break;

   case 'updateUser':
        if ($add_group) {$add_group=implode(',',$add_group);}
        updateUser($chng_uid, $add_uname, $add_name, $add_url, $add_email, $add_femail, $add_user_from, $add_user_occ, $add_user_intrest, $add_user_viewemail, $add_avatar, $add_user_sig, $add_bio, $add_pass, $add_pass2, $add_level, $add_open_user, $add_group, $add_send_email, $add_is_visible, $add_mns, $C1,$C2,$C3,$C4,$C5,$C6,$C7,$C8,$M1,$M2,$T1,$T2,$B1,$raz_avatar,$chng_rank,$user_lnl);
        break;

   case 'delUser':
        global $hlpfile;
        include("header.php");
        GraphicAdmin($hlpfile);
        opentable();
        echo "<p align=\"center\"><b>".adm_translate("Supprimer un utilisateur")."</b> : ";
        echo "<span class=\"rouge\">".adm_translate("Etes-vous sûr de vouloir effacer") . " " . adm_translate("Utilisateur") . " $chng_uid ? </span><br /><br />";
        echo "[ <a href=\"admin.php?op=delUserConf&amp;del_uid=$chng_uid&amp;referer=".basename($referer)."\" class=\"rouge\">".adm_translate("Oui")."</a> | ";
        if (basename($referer)!="memberslist.php")
           echo "<a href=\"admin.php?op=mod_users\" class=\"noir\">".adm_translate("Non")."</a> ]<br />";
        else
           echo "<a href=\"memberslist.php\" class=\"noir\">".adm_translate("Non")."</a> ]<br />";
        closetable();
        include("footer.php");
        break;

   case 'delUserConf':
        $result = sql_query("SELECT uid, uname FROM ".$NPDS_Prefix."users WHERE uid='$del_uid' or uname='$del_uid'");
        list($del_uid, $del_uname) = sql_fetch_row($result);
        if ($del_uid!=1) {
           sql_query("DELETE FROM ".$NPDS_Prefix."users WHERE uid='$del_uid'");
           sql_query("DELETE FROM ".$NPDS_Prefix."users_status WHERE uid='$del_uid'");
           sql_query("DELETE FROM ".$NPDS_Prefix."users_extend WHERE uid='$del_uid'");
           sql_query("DELETE FROM ".$NPDS_Prefix."subscribe WHERE uid='$del_uid'");

           //  Changer les articles et reviews pour les affecter à un pseudo utilisateurs  ( 0 comme uid et ' ' comme uname )
           sql_query("UPDATE ".$NPDS_Prefix."stories SET informant=' ' WHERE informant='$del_uname'");
           sql_query("UPDATE ".$NPDS_Prefix."reviews SET reviewer=' ' WHERE reviewer='$del_uname'");

           include ("modules/upload/upload.conf.php");
           if ($DOCUMENTROOT=='') {
              global $DOCUMENT_ROOT;
              if ($DOCUMENT_ROOT) {
                 $DOCUMENTROOT=$DOCUMENT_ROOT;
              } else {
                 $DOCUMENTROOT=$_SERVER['DOCUMENT_ROOT'];
              }
           }
           $user_dir=$DOCUMENTROOT.$racine.'/users_private/'.$del_uname;

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

           // Mettre un fichier 'delete' dans sa home_directory si elle existe
           if (is_dir($user_dir)) {
              $fp = fopen($repertoire.$user_dir.'/delete', 'w');
              fclose($fp);
           }

           // Changer les posts, les commentaires, ... pour les affecter à un pseudo utilisateurs  ( 0 comme uid et ' ' comme uname)
           sql_query("UPDATE ".$NPDS_Prefix."posts SET poster_id='0' WHERE poster_id='$del_uid'");

           // Met à jour les modérateurs des forums
           $pat='#\b'.$del_uid.'\b#';
           $res=sql_query("SELECT forum_id, forum_moderator FROM ".$NPDS_Prefix."forums");
           while ($row = sql_fetch_row($res)) {
               $tmp_moder = explode(',',$row[1]);
               if (preg_match($pat, $row[1])) {
                  unset($tmp_moder[array_search($del_uid, $tmp_moder)]);
                  sql_query("UPDATE ".$NPDS_Prefix."forums SET forum_moderator='".implode (',',$tmp_moder)."' WHERE forum_id='$row[0]'");
               }
           }
           global $aid; Ecr_Log('security', "DeleteUser($del_uid) by AID : $aid", '');
        }
        if ($referer!="memberslist.php")
           Header("Location: admin.php?op=mod_users");
        else
           Header("Location: memberslist.php");
        break;

   case 'addUser':
      if (!($add_uname && $add_email && $add_pass) or (preg_match('#[^a-zA-Z0-9_-]#',$add_uname))) {
         global $hlpfile;
         include("header.php");
         GraphicAdmin($hlpfile);
         adminhead ($f_meta_nom, $f_titre, $adminimg);
         echo error_handler(adm_translate("Vous devez remplir tous les Champs")."<br />");
         adminfoot('','','','');
         return;
      }
      if (!$system) {
         $add_pass = crypt($add_pass,$add_pass);
      }
      if ($add_is_visible=='') {
         $add_is_visible='1';
      } else {
         $add_is_visible='0';
      }
      $user_regdate = time()+$gmt*3600;
      $sql= 'INSERT INTO '.$NPDS_Prefix.'users ';
      $sql.= "(uid,name,uname,email,femail,url,user_regdate,user_from,user_occ,user_intrest,user_viewemail,user_avatar,user_sig,bio,pass,send_email,is_visible,mns) ";
      $sql.= "VALUES (NULL,'$add_name','$add_uname','$add_email','$add_femail','$add_url','$user_regdate','$add_user_from','$add_user_occ','$add_user_intrest','$add_user_viewemail','$add_avatar','$add_user_sig','$add_bio','$add_pass','$add_send_email','$add_is_visible','$add_mns')";
      $result = sql_query($sql);
      list($usr_id) = sql_fetch_row(sql_query("SELECT uid FROM ".$NPDS_Prefix."users WHERE uname='$add_uname'"));
      $result = sql_query("INSERT INTO ".$NPDS_Prefix."users_extend VALUES ('$usr_id','$C1','$C2','$C3','$C4','$C5','$C6','$C7','$C8','$M1','$M2','$T1','$T2', '$B1')");
      if ($add_user_viewemail) {
         $attach = 1;
      } else {
         $attach = 0;
      }
      if ($add_group==0) $add_group='';
      if ($add_group) {$add_group=implode(',',$add_group);}
      $result = sql_query("INSERT INTO ".$NPDS_Prefix."users_status VALUES ('$usr_id','0','$attach','$chng_rank','$add_level','1','$add_group')");

      Minisites($add_mns,$add_uname);

      global $aid; Ecr_Log('security', "AddUser($add_name, $add_uname) by AID : $aid", '');
      Header("Location: admin.php?op=mod_users");
   break;

   case 'unsubUser':
        $result = sql_query("SELECT uid FROM ".$NPDS_Prefix."users WHERE uid='$chng_uid' OR uname='$chng_uid'");
        list($chng_uid) = sql_fetch_row($result);
        if ($chng_uid!=1) {
           sql_query("DELETE FROM ".$NPDS_Prefix."subscribe WHERE uid='$chng_uid'");
           global $aid; Ecr_Log("security", "UnsubUser($chng_uid) by AID : $aid", "");
        }
        Header("Location: admin.php?op=mod_users");
        break;
   case 'mod_users':
   default:
        displayUsers();
        break;
}
?>
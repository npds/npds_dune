<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2015 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],"admin.php")) { Access_Error(); }
$f_meta_nom ='BannersAdmin';
$f_titre = adm_translate("Administration des Bannières");
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
global $language;

$hlpfile = "manuels/$language/banners.html";

function BannersAdmin() {
   global $NPDS_Prefix, $hlpfile, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '
   <hr />
   <h3>'.adm_translate("Bannières actives").'</h3>
   <table data-toggle="table" data-search="true" data-striped="true" data-mobile-responsive="true" data-show-export="true" data-show-toggle="true" data-show-columns="true" data-detail-view="true" data-icons="icons" data-icons-prefix="fa">
      <thead>
         <tr>
            <th data-sortable="true" data-halign="center" data-align="right" >'.adm_translate("ID").'</th>
            <th data-sortable="true" data-halign="center" data-align="center" >'.adm_translate("Nom de l'Annonceur").'</th>
            <th data-sortable="true" data-halign="center" data-align="right" >'.adm_translate("Impressions").'</th>
            <th data-sortable="true" data-halign="center" data-align="right" >'.adm_translate("Imp. restantes").'</th>
            <th data-sortable="true" data-halign="center" data-align="right">'.adm_translate("Clics").'</th>
            <th data-sortable="true" data-halign="center" data-align="right" >% '.adm_translate("Clics").'</th>
            <th data-halign="center" data-align="right">'.adm_translate("Fonctions").'</th>
         </tr>
      </thead>
      <tbody>';
   $result = sql_query("SELECT bid, cid, imageurl, imptotal, impmade, clicks, date FROM ".$NPDS_Prefix."banner WHERE userlevel!='9' ORDER BY bid");
   while (list($bid, $cid, $imageurl, $imptotal, $impmade, $clicks, $date) = sql_fetch_row($result)) {
      $result2 = sql_query("SELECT cid, name FROM ".$NPDS_Prefix."bannerclient WHERE cid='$cid'");
      list($cid, $name) = sql_fetch_row($result2);
      if ($impmade==0) {
         $percent = 0;
      } else {
         $percent = substr(100 * $clicks / $impmade, 0, 5);
      }
      if ($imptotal==0) {
         $left = adm_translate("Illimité");
      } else {
         $left = $imptotal-$impmade;
      }
      echo '
         <tr>
            <td>'.$bid.'</td>
            <td>'.$name.' | <span style="font-size: 10px;"><a href="#" class="tooltip">'.basename(aff_langue($imageurl)).'<em><img src="'.$imageurl.'" /></em></a></span></td>
            <td>'.$impmade.'</td>
            <td>'.$left.'</td>
            <td>'.$clicks.'</td>
            <td>'.$percent.'%</td>
            <td><a href="admin.php?op=BannerEdit&amp;bid='.$bid.'"><i class="fa fa-edit fa-lg" title="'.adm_translate("Editer").'" data-toggle="tooltip"></i></a>&nbsp;<a href="admin.php?op=BannerDelete&amp;bid='.$bid.'&amp;ok=0" class="text-danger"><i class="fa fa-trash-o fa-lg" title="'.adm_translate("Effacer").'" data-toggle="tooltip"></i></a></td>
         </tr>';
   }
   echo '
      </tbody>
   </table>';

   echo '
   <hr />
   <h3>'.adm_translate("Bannières inactives").'</h3>
   <table data-toggle="table" data-search="true" data-striped="true" data-mobile-responsive="true" data-show-export="true" data-show-toggle="true" data-show-columns="true" data-icons="icons" data-icons-prefix="fa">
      <thead>
         <tr>
            <th data-sortable="true" data-halign="center" data-align="right" >'.adm_translate("ID").'</th>
            <th data-sortable="true" data-halign="center" data-align="right" >'.adm_translate("Impressions").'</th>
            <th data-sortable="true" data-halign="center" data-align="right" >'.adm_translate("Imp. restantes").'</th>
            <th data-sortable="true" data-halign="center" data-align="right">'.adm_translate("Clics").'</th>
            <th data-sortable="true" data-halign="center" data-align="right">% '.adm_translate("Clics").'</th>
            <th data-sortable="true" data-halign="center" data-align="right">'.adm_translate("Nom de l'Annonceur").'</th>
            <th data-halign="center" data-align="right">'.adm_translate("Fonctions").'</th>
         </tr>
      </thead>
      <tbody>';
   $result = sql_query("SELECT bid, cid, imageurl, imptotal, impmade, clicks, date FROM ".$NPDS_Prefix."banner WHERE userlevel='9' order by bid");
   while (list($bid, $cid, $imageurl, $imptotal, $impmade, $clicks, $date) = sql_fetch_row($result)) {
   $result2 = sql_query("SELECT cid, name FROM ".$NPDS_Prefix."bannerclient WHERE cid='$cid'");
   list($cid, $name) = sql_fetch_row($result2);
   if ($impmade==0) {
   $percent = 0;
   } else {
   $percent = substr(100 * $clicks / $impmade, 0, 5);
   }
   if ($imptotal==0) {
   $left = adm_translate("Illimité");
   } else {
   $left = $imptotal-$impmade;
   }
   echo '
         <tr>
         <td>'.$bid.'</td>
         <td>'.$impmade.'</td>
         <td>'.$left.'</td>
         <td>'.$clicks.'</td>
         <td>'.$percent.'%</td>
         <td>'.$name.' | <span style="font-size: 10px;">'.basename(aff_langue($imageurl)).'</span></td>
         <td><a href="admin.php?op=BannerEdit&amp;bid='.$bid.'" ><i class="fa fa-edit fa-lg" title="'.adm_translate("Editer").'" data-toggle="tooltip"></i></a>&nbsp;<a href="admin.php?op=BannerDelete&amp;bid='.$bid.'&amp;ok=0" class="text-danger"><i class="fa fa-trash-o fa-lg" title="'.adm_translate("Effacer").'" data-toggle="tooltip"></i></a></td>
         </tr>';
   }
   echo '
      </tbody>
   </table>
   <hr />
   <h3>'.adm_translate("Bannières terminées").'</h3>
   <table data-toggle="table" data-search="true" data-striped="true" data-mobile-responsive="true" data-show-export="true" data-show-toggle="true" data-show-columns="true" data-icons="icons" data-icons-prefix="fa">
      <thead>
         <tr>
            <th data-sortable="true" data-halign="center" data-align="right" >'.adm_translate("ID").'</th>
            <th data-sortable="true" data-halign="center" data-align="right" >'.adm_translate("Imp.").'</th>
            <th data-sortable="true" data-halign="center" data-align="right" >'.adm_translate("Clics").'</th>
            <th data-sortable="true" data-halign="center" data-align="right" > % '.adm_translate("Clics").'</th>
            <th data-sortable="true" data-halign="center" data-align="center" >'.adm_translate("Date de début").'</th>
            <th data-sortable="true" data-halign="center" data-align="center" >'.adm_translate("Date de fin").'</th>
            <th data-sortable="true" data-halign="center" data-align="center">'.adm_translate("Nom de l'Annonceur").'</th>
            <th data-halign="center" data-align="right">'.adm_translate("Fonctions").'</th>
         </tr>
      </thead>
      <tbody>';
   $result = sql_query("SELECT bid, cid, impressions, clicks, datestart, dateend FROM ".$NPDS_Prefix."bannerfinish ORDER BY bid");
   while (list($bid, $cid, $impressions, $clicks, $datestart, $dateend) = sql_fetch_row($result)) {
   $result2 = sql_query("SELECT cid, name FROM ".$NPDS_Prefix."bannerclient WHERE cid='$cid'");
   list($cid, $name) = sql_fetch_row($result2);
   if ($impressions==0) {$impressions=1;}
   $percent = substr(100 * $clicks / $impressions, 0, 5);
   echo '
         <tr>
            <td>'.$bid.'</td>
            <td>'.$impressions.'</td>
            <td>'.$clicks.'</td>
            <td>'.$percent.'%</td>
            <td>'.$datestart.'</td>
            <td>'.$dateend.'</td>
            <td>'.$name.'</td>
            <td><a href="admin.php?op=BannerFinishDelete&amp;bid='.$bid.'" class="text-danger"><i class="fa fa-trash-o fa-lg" title="'.adm_translate("Effacer").'" data-toggle="tooltip"></i></a></td>
         </tr>';
   }
   echo '
      </tbody>
   </table>
   <hr />
   <h3>'.adm_translate("Annonceurs faisant de la publicité").'</h3>
   <table id="tad_banannon" data-toggle="table" data-search="true" data-striped="true" data-mobile-responsive="true" data-show-export="true" data-show-toggle="true" data-show-columns="true" data-icons="icons" data-icons-prefix="fa">
      <thead>
         <tr>
            <th data-sortable="true" data-halign="center" data-align="right" >'.adm_translate("ID").'</th>
            <th data-sortable="true" data-halign="center" data-align="center" >'.adm_translate("Nom de l'Annonceur").'</th>
            <th data-sortable="true" data-halign="center" data-align="right" >'.adm_translate("Bannières actives").'</th>
            <th data-sortable="true" data-halign="center" data-align="center" >'.adm_translate("Nom du Contact").'</th>
            <th data-sortable="true" data-halign="center" >'.adm_translate("E-mail").'</th>
            <th data-halign="center" data-align="right" >'.adm_translate("Fonctions").'</th>
         </tr>
      </thead>
      <tbody>';
   $result = sql_query("SELECT cid, name, contact, email FROM ".$NPDS_Prefix."bannerclient ORDER BY cid");
   while (list($cid, $name, $contact, $email) = sql_fetch_row($result)) {
   $result2 = sql_query("SELECT cid FROM ".$NPDS_Prefix."banner WHERE cid='$cid'");
   $numrows = sql_num_rows($result2);
   echo '
         <tr>
            <td>'.$cid.'</td>
            <td>'.$name.'</td>
            <td>'.$numrows.'</td>
            <td>'.$contact.'</td>
            <td>'.$email.'</td>
            <td><a href="admin.php?op=BannerClientEdit&amp;cid='.$cid.'"><i class="fa fa-edit fa-lg" title="'.adm_translate("Editer").'" data-toggle="tooltip"></i></a>&nbsp;<a href="admin.php?op=BannerClientDelete&amp;cid='.$cid.'" class="text-danger"><i class="fa fa-trash-o fa-lg text-danger" title="'.adm_translate("Effacer").'" data-toggle="tooltip"></i></a></td>
         </tr>';
   }
   echo '
      </tbody>
   </table>';
   // Add Banner
   $result = sql_query("SELECT * FROM ".$NPDS_Prefix."bannerclient");
   $numrows = sql_num_rows($result);
   if ($numrows>0) {
   echo '
   <hr />
   <h3>'.adm_translate("Ajouter une nouvelle Bannière").'</h3>
   <span class="help-block">'.adm_translate("Pour les Bannières Javascript, saisir seulement le code javascript dans la zone Url du Clik et laisser la zone image vide.").'</span>
   <span class="help-block">'.adm_translate("Pour les Bannières encore plus complexes (Flash, ...), saisir simplement la référence à votre_répertoire/votre_fichier .txt (fichier de code php) dans la zone Url du Clik et laisser la zone image vide.").'</span>
   <form id="fad_newbanner" action="admin.php" method="post">
      <div class="form-group row">
         <label class="form-control-label col-sm-4 " for="cid">'.adm_translate("Nom de l'Annonceur").'</label>
         <div class="col-sm-8">
            <select class="c-select form-control" name="cid">';
   $result = sql_query("SELECT cid, name FROM ".$NPDS_Prefix."bannerclient");
   while(list($cid, $name) = sql_fetch_row($result)) {
   echo '
               <option value="'.$cid.'">'.$name.'</option>';
   }
   echo '
            </select>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4 " for="imptotal">'.adm_translate("Impressions réservées").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="number" name="imptotal" min="0" max="99999999999" required="required" />
            <span class="help-block">0 = '.adm_translate("Illimité").'</span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4 " for="imageurl">'.adm_translate("URL de l'image").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="url" name="imageurl" maxlength="200" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4 " for="clickurl">'.adm_translate("URL du Clic").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" name="clickurl" maxlength="200" required="required" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4 " for="userlevel">'.adm_translate("Niveau de l'Utilisateur").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="number" name="userlevel" min="0" max="9" value="0" required="required" /> 
            <span class="help-block">'.adm_translate("0=Tout le monde, 1=Membre seulement, 3=Administrateur seulement, 9=Désactiver").'.</span>
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-offset-4 col-sm-8">
            <input type="hidden" name="op" value="BannersAdd" />
            <button class="btn btn-primary col-xs-12" type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;'.adm_translate("Ajouter une Bannière").' </button>
         </div>
      </div>
   </form>';
   }
   // Add Client
   echo '
   <hr />
   <h3>'.adm_translate("Ajouter un nouvel Annonceur").'</h3>
   <form id="fad_bananno" action="admin.php" method="post">
      <div class="form-group row">
         <label class="form-control-label col-sm-4 " for="name">'.adm_translate("Nom de l'Annonceur").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" name="name" maxlength="60" required="required" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4 " for="contact">'.adm_translate("Nom du Contact").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" name="contact" maxlength="60" required="required" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4 " for="email">'.adm_translate("E-mail").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="email" name="email" maxlength="60" required="required" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4 " for="login">'.adm_translate("Identifiant").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" name="login" maxlength="10" required="required" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4 " for="passwd">'.adm_translate("Mot de Passe").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="password" name="passwd" maxlength="10" required="required" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4 " for="extrainfo">'.adm_translate("Informations supplémentaires").'</label>
         <div class="col-sm-8">
            <textarea class="form-control" name="extrainfo" rows="10"></textarea>
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-offset-4 col-sm-8">
            <input type="hidden" name="op" value="BannerAddClient" />
            <button class="btn btn-primary col-xs-12" type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;'.adm_translate("Ajouter un annonceur").'</button>
         </div>
      </div>
   </form>';
   adminfoot('fv','','','');
}
function BannersAdd($name, $cid, $imptotal, $imageurl, $clickurl, $userlevel) {
    global $NPDS_Prefix;
    sql_query("INSERT INTO ".$NPDS_Prefix."banner VALUES (NULL, '$cid', '$imptotal', '1', '0', '$imageurl', '$clickurl', '$userlevel', now())");
    Header("Location: admin.php?op=BannersAdmin");
}
function BannerAddClient($name, $contact, $email, $login, $passwd, $extrainfo) {
    global $NPDS_Prefix;
    sql_query("INSERT INTO ".$NPDS_Prefix."bannerclient VALUES (NULL, '$name', '$contact', '$email', '$login', '$passwd', '$extrainfo')");
    Header("Location: admin.php?op=BannersAdmin");
}
function BannerFinishDelete($bid) {
    global $NPDS_Prefix;
    sql_query("DELETE FROM ".$NPDS_Prefix."bannerfinish WHERE bid='$bid'");
    Header("Location: admin.php?op=BannersAdmin");
}
function BannerDelete($bid, $ok=0) {
   global $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   if ($ok==1) {
      sql_query("DELETE FROM ".$NPDS_Prefix."banner WHERE bid='$bid'");
      Header("Location: admin.php?op=BannersAdmin");
   } else {
      global $hlpfile;
      include("header.php");
      GraphicAdmin($hlpfile);
      $result=sql_query("SELECT cid, imptotal, impmade, clicks, imageurl, clickurl FROM ".$NPDS_Prefix."banner WHERE bid='$bid'");
      list($cid, $imptotal, $impmade, $clicks, $imageurl, $clickurl) = sql_fetch_row($result);
      adminhead ($f_meta_nom, $f_titre, $adminimg);
      echo '
      <hr />
      <h3 class="text-danger">'.adm_translate("Effacer Bannière").'</h3>';
      if ($imageurl!='') {
         echo '<a href="'.aff_langue($clickurl).'"><img class="img-fluid" src="'.aff_langue($imageurl).'" alt="banner" /></a><br />';
      } else {
         echo $clickurl;
      }
      echo '
      <table data-toggle="table" data-mobile-responsive="true">
         <thead>
            <tr>
               <th data-halign="center" data-align="right">'.adm_translate("ID").'</th>
               <th data-halign="center" data-align="right">'.adm_translate("Impressions").'</th>
               <th data-halign="center" data-align="right">'.adm_translate("Imp. restantes").'</th>
               <th data-halign="center" data-align="right">'.adm_translate("Clics").'</th>
               <th data-halign="center" data-align="right">% '.adm_translate("Clics").'</th>
               <th data-halign="center" data-align="center">'.adm_translate("Nom de l'Annonceur").'</th>
            </tr>
         </thead>
         <tbody>';
       $result2 = sql_query("SELECT cid, name FROM ".$NPDS_Prefix."bannerclient WHERE cid='$cid'");
       list($cid, $name) = sql_fetch_row($result2);
       $percent = substr(100 * $clicks / $impmade, 0, 5);
       if ($imptotal==0) {
          $left = 'unlimited';// was with no quote ???
       } else {
          $left = $imptotal-$impmade;
       }
      echo '
            <tr>
               <td>'.$bid.'</td>
               <td>'.$impmade.'</td>
               <td>'.$left.'</td>
               <td>'.$clicks.'</td>
               <td>'.$percent.'%</td>
               <td>'.$name.'</td>
            </tr>';
   }
   echo '
         </tbody>
      </table>
    <br />
    <div class="alert alert-danger">'.adm_translate("Etes-vous sûr de vouloir effacer cette Bannière ?").'<br />
    <a class="btn btn-danger btn-sm m-t-1" href="admin.php?op=BannerDelete&amp;bid=$bid&amp;ok=1">'.adm_translate("Oui").'</a>&nbsp;<a class="btn btn-secondary btn-sm m-t-1" href="admin.php?op=BannersAdmin" >'.adm_translate("Non").'</a></div>';
   adminfoot('','','','');
}

function BannerEdit($bid) {
   global $NPDS_Prefix, $hlpfile, $f_meta_nom, $f_titre, $adminimg;
   include("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   $result=sql_query("SELECT cid, imptotal, impmade, clicks, imageurl, clickurl, userlevel FROM ".$NPDS_Prefix."banner WHERE bid='$bid'");
   list($cid, $imptotal, $impmade, $clicks, $imageurl, $clickurl, $userlevel) = sql_fetch_row($result);
   echo '
   <hr />
   <h3>'.adm_translate("Edition Bannière").'</h3>';
   if ($imageurl!='') {
   echo '<img class="img-fluid" src="'.aff_langue($imageurl).'" alt="banner" /><br />';
   } else {
   echo $clickurl;
   }
   echo '
   <span class="help-block">'.adm_translate("Pour les Bannières Javascript, saisir seulement le code javascript dans la zone Url du Clik et laisser la zone image vide.").'</span>
   <span class="help-block">'.adm_translate("Pour les Bannières encore plus complexes (Flash, ...), saisir simplement la référence à votre_répertoire/votre_fichier .txt (fichier de code php) dans la zone Url du Clik et laisser la zone image vide.").'</span>
   <form action="admin.php" method="post">
      <div class="form-group row">
         <label class="form-control-label col-sm-4 " for="cid">'.adm_translate("Nom de l'Annonceur").'</label>
         <div class="col-sm-8">
            <select class="c-select form-control" name="cid">';
   $result = sql_query("SELECT cid, name FROM ".$NPDS_Prefix."bannerclient WHERE cid='$cid'");
   list($cid, $name) = sql_fetch_row($result);
   echo '
               <option value="'.$cid.'" selected="selected">'.$name.'</option>';
   $result = sql_query("SELECT cid, name FROM ".$NPDS_Prefix."bannerclient");
   while (list($ccid, $name) = sql_fetch_row($result)) {
   if ($cid!=$ccid) {
   echo '
               <option value="'.$ccid.'">'.$name.'</option>';
   }
   }
   echo '
         </select>';
   if ($imptotal==0) {
   $impressions = adm_translate("Illimité");
   } else {
   $impressions = $imptotal;
   }
   echo'
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4 " for="impadded">'.adm_translate("Ajouter plus d'affichages").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="number" name="impadded" min="0" max="99999999999" required="required" />
            <span class="help-block">'.adm_translate("Réservé : ").'<strong>'.$impressions.'</strong> '.adm_translate("Fait : ").'<strong>'.$impmade.'</strong></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4 " for="imageurl">'.adm_translate("URL de l'image").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="url" name="imageurl" size="50" maxlength="200" value="'.$imageurl.'" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4 " for="clickurl">'.adm_translate("URL du Clic").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" name="clickurl" size="50" maxlength="200" value="'.htmlentities($clickurl,ENT_QUOTES,cur_charset).'" />
         </div>
      </div>
      <div class="form-group row"> 
         <label class="form-control-label col-sm-4 " for="userlevel">'.adm_translate("Niveau de l'Utilisateur").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="number" name="userlevel" min="0" max="9" value="'.$userlevel.'" required="required" />
            <span class="help-block">'.adm_translate("0=Tout le monde, 1=Membre seulement, 3=Administrateur seulement, 9=Désactiver").'.</span>
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-offset-4 col-sm-8">
            <input type="hidden" name="bid" value="'.$bid.'" />
            <input type="hidden" name="imptotal" value="'.$imptotal.'" />
            <input type="hidden" name="op" value="BannerChange" />
            <button class="btn btn-primary col-xs-12" type="submit"><i class="fa fa-check-square fa-lg"></i>&nbsp;'.adm_translate("Modifier la Bannière").'</button>
         </div>
      </div>
   </form>';
   adminfoot('fv','','','');
   }
function BannerChange($bid, $cid, $imptotal, $impadded, $imageurl, $clickurl, $userlevel) {
   global $NPDS_Prefix;
   $imp = $imptotal+$impadded;
   sql_query("UPDATE ".$NPDS_Prefix."banner SET cid='$cid', imptotal='$imp', imageurl='$imageurl', clickurl='$clickurl', userlevel='$userlevel' WHERE bid='$bid'");
   Header("Location: admin.php?op=BannersAdmin");
}
function BannerClientDelete($cid, $ok=0) {
    global $NPDS_Prefix, $sitename, $f_meta_nom, $f_titre, $adminimg;
    if ($ok==1) {
       sql_query("DELETE FROM ".$NPDS_Prefix."banner WHERE cid='$cid'");
       sql_query("DELETE FROM ".$NPDS_Prefix."bannerclient WHERE cid='$cid'");
       Header("Location: admin.php?op=BannersAdmin");
    } else {
       include("header.php");
       GraphicAdmin($hlpfile);
       adminhead ($f_meta_nom, $f_titre, $adminimg);
       $result=sql_query("SELECT cid, name FROM ".$NPDS_Prefix."bannerclient WHERE cid='$cid'");
       list($cid, $name) = sql_fetch_row($result);
       echo '
       <h3 class="text-danger">'.adm_translate("Supprimer l'Annonceur").'</h3>';
       echo "<br /><p align=\"center\">
       ".adm_translate("Vous êtes sur le point de supprimer cet Annonceur : ")." <strong>$name</strong> ".adm_translate("et toutes ses Bannières !!!")."</p><br /><br />";
       $result2 = sql_query("SELECT imageurl, clickurl FROM ".$NPDS_Prefix."banner WHERE cid='$cid'");
       $numrows = sql_num_rows($result2);
       if ($numrows==0) {
          echo adm_translate("Cet Annonceur n'a pas de Bannière active pour le moment.")."<br /><br />";
       } else {
          echo "<span class=\"text-danger\"><b>".adm_translate("ATTENTION !!!")."</b></span><br /><br />
          ".adm_translate("Cet Annonceur a les BANNIERES ACTIVES suivantes dans")." $sitename :<br /><br />";
       }
       while (list($imageurl, $clickurl) = sql_fetch_row($result2)) {
          if ($imageurl!="") {
             echo "<img src=\"".aff_langue($imageurl)."\" border=\"1\" alt=\"\" /><br /><br />";
          } else {
             echo $clickurl."<br /><br />";
          }
       }
    }
    echo "<p align=\"center\">".adm_translate("Etes-vous sûr de vouloir effacer cet Annonceur et TOUTES ses Bannières ?")." -
    [ <a href=\"admin.php?op=BannerClientDelete&amp;cid=$cid&amp;ok=1\" class=\"btn btn-danger\">".adm_translate("Oui")."</a> | <a href=\"admin.php?op=BannersAdmin\" class=\"btn btn-secondary\">".adm_translate("Non")."</a> ]</p><br /><br />";
    adminfoot('','','','');
}
function BannerClientEdit($cid) {
   global $NPDS_Prefix, $hlpfile, $f_meta_nom, $f_titre, $adminimg;
   include("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   $result = sql_query("SELECT name, contact, email, login, passwd, extrainfo FROM ".$NPDS_Prefix."bannerclient WHERE cid='$cid'");
   list($name, $contact, $email, $login, $passwd, $extrainfo) = sql_fetch_row($result);
   echo '
   <hr />
   <h3>'.adm_translate("Editer l'Annonceur").'</h3>
   <form action="admin.php" method="post">
      <div class="form-group row">
         <label class="form-control-label col-sm-4 " for="name">'.adm_translate("Nom de l'Annonceur").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" name="name" value="'.$name.'" maxlength="60" required="required" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4 " for="contact">'.adm_translate("Nom du Contact").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" name="contact" value="'.$contact.'" maxlength="60" required="required" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4 " for="email">'.adm_translate("E-mail").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" name="email" maxlength="60" value="'.$email.'" required="required" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4 " for="login">'.adm_translate("Identifiant").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" name="login" maxlength="10" value="'.$login.'" required="required" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4 " for="passwd">'.adm_translate("Mot de Passe").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="password" name="passwd" maxlength="10" value="'.$passwd.'" required="required" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4 " for="extrainfo">'.adm_translate("Informations supplémentaires").'</label>
         <div class="col-sm-8">
            <textarea class="form-control" name="extrainfo" rows="10">'.$extrainfo.'</textarea>
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-offset-4 col-sm-8">
            <input type="hidden" name="cid" value="'.$cid.'" />
            <input type="hidden" name="op" value="BannerClientChange" />
            <input class="btn btn-primary" type="submit" value="'.adm_translate("Modifier Annonceur").'" />
         </div>
      </div>
   </form>';
   adminfoot('fv','','','');
}
function BannerClientChange($cid, $name, $contact, $email, $extrainfo, $login, $passwd) {
   global $NPDS_Prefix;
   sql_query("UPDATE ".$NPDS_Prefix."bannerclient SET name='$name', contact='$contact', email='$email', login='$login', passwd='$passwd', extrainfo='$extrainfo' WHERE cid='$cid'");
   Header("Location: admin.php?op=BannersAdmin");
}

switch ($op) {
   case 'BannersAdmin':
      BannersAdmin();
   break;
   case 'BannersAdd':
      BannersAdd($name, $cid, $imptotal, $imageurl, $clickurl, $userlevel);
   break;
   case 'BannerAddClient':
      BannerAddClient($name, $contact, $email, $login, $passwd, $extrainfo);
   break;
   case 'BannerFinishDelete':
      BannerFinishDelete($bid);
   break;
   case 'BannerDelete':
      BannerDelete($bid, $ok);
   break;
   case 'BannerEdit':
      BannerEdit($bid);
   break;
   case 'BannerChange':
      BannerChange($bid, $cid, $imptotal, $impadded, $imageurl, $clickurl, $userlevel);
   break;
   case 'BannerClientDelete':
      BannerClientDelete($cid, $ok);
   break;
   case 'BannerClientEdit':
      BannerClientEdit($cid);
   break;
   case 'BannerClientChange':
      BannerClientChange($cid, $name, $contact, $email, $extrainfo, $login, $passwd);
   break;
//         default:BannersAdmin();break;
}
?>
<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2024 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

if (!function_exists('admindroits'))
   include('die.php');
$f_meta_nom ='BannersAdmin';
$f_titre = adm_translate("Administration des bannières");
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
   <table data-toggle="table" data-search="true" data-striped="true" data-mobile-responsive="true" data-show-export="true" data-show-toggle="true" data-show-columns="true" data-buttons-class="outline-secondary" data-icons="icons" data-icons-prefix="fa">
      <thead>
         <tr>
            <th class="n-t-col-xs-1" data-sortable="true" data-halign="center" data-align="right" >'.adm_translate("ID").'</th>
            <th data-sortable="true" data-halign="center" data-align="center" >'.adm_translate("Nom de l'annonceur").'</th>
            <th data-sortable="true" data-halign="center" data-align="right" >'.adm_translate("Impressions").'</th>
            <th data-sortable="true" data-halign="center" data-align="right" >'.adm_translate("Imp. restantes").'</th>
            <th data-sortable="true" data-halign="center" data-align="right">'.adm_translate("Clics").'</th>
            <th data-sortable="true" data-halign="center" data-align="right" >% '.adm_translate("Clics").'</th>
            <th data-halign="center" data-align="center">'.adm_translate("Fonctions").'</th>
         </tr>
      </thead>
      <tbody>';
   $result = sql_query("SELECT bid, cid, imageurl, imptotal, impmade, clicks, date FROM ".$NPDS_Prefix."banner WHERE userlevel!='9' ORDER BY bid");
   while (list($bid, $cid, $imageurl, $imptotal, $impmade, $clicks, $date) = sql_fetch_row($result)) {
      $result2 = sql_query("SELECT cid, name FROM ".$NPDS_Prefix."bannerclient WHERE cid='$cid'");
      list($cid, $name) = sql_fetch_row($result2);
      $percent = $impmade==0 ? '0' : substr(100 * $clicks / $impmade, 0, 5);
      $left = $imptotal==0 ? adm_translate("Illimité") : $imptotal-$impmade;

      //  | <span class="small"><a href="#" class="tooltip">'.basename(aff_langue($imageurl)).'<em><img src="'.$imageurl.'" /></em></a></span>
      echo '
         <tr>
            <td>'.$bid.'</td>
            <td>'.$name.'</td>
            <td>'.$impmade.'</td>
            <td>'.$left.'</td>
            <td>'.$clicks.'</td>
            <td>'.$percent.'%</td>
            <td><a href="admin.php?op=BannerEdit&amp;bid='.$bid.'"><i class="fa fa-edit fa-lg me-3" title="'.adm_translate("Editer").'" data-bs-toggle="tooltip"></i></a><a href="admin.php?op=BannerDelete&amp;bid='.$bid.'&amp;ok=0" class="text-danger"><i class="fas fa-trash fa-lg" title="'.adm_translate("Effacer").'" data-bs-toggle="tooltip"></i></a></td>
         </tr>';
   }
   echo '
      </tbody>
   </table>';

   echo '
   <hr />
   <h3>'.adm_translate("Bannières inactives").'</h3>
   <table data-toggle="table" data-search="true" data-striped="true" data-mobile-responsive="true" data-show-export="true" data-show-toggle="true" data-show-columns="true" data-buttons-class="outline-secondary" data-icons="icons" data-icons-prefix="fa">
      <thead>
         <tr>
            <th class="n-t-col-xs-1" data-sortable="true" data-halign="center" data-align="right" >'.adm_translate("ID").'</th>
            <th data-sortable="true" data-halign="center" data-align="right" >'.adm_translate("Impressions").'</th>
            <th data-sortable="true" data-halign="center" data-align="right" >'.adm_translate("Imp. restantes").'</th>
            <th class="n-t-col-xs-2" data-sortable="true" data-halign="center" data-align="right">'.adm_translate("Clics").'</th>
            <th class="n-t-col-xs-2" data-sortable="true" data-halign="center" data-align="right">% '.adm_translate("Clics").'</th>
            <th data-sortable="true" data-halign="center" data-align="right">'.adm_translate("Nom de l'annonceur").'</th>
            <th class="n-t-col-xs-1" data-halign="center" data-align="center">'.adm_translate("Fonctions").'</th>
         </tr>
      </thead>
      <tbody>';
   $result = sql_query("SELECT bid, cid, imageurl, imptotal, impmade, clicks, date FROM ".$NPDS_Prefix."banner WHERE userlevel='9' order by bid");
   while (list($bid, $cid, $imageurl, $imptotal, $impmade, $clicks, $date) = sql_fetch_row($result)) {
   $result2 = sql_query("SELECT cid, name FROM ".$NPDS_Prefix."bannerclient WHERE cid='$cid'");
   list($cid, $name) = sql_fetch_row($result2);
   $percent = $impmade==0 ? '0' : substr(100 * $clicks / $impmade, 0, 5);
   $left = $imptotal==0 ? adm_translate("Illimité") : $imptotal-$impmade ;
   echo '
         <tr>
         <td>'.$bid.'</td>
         <td>'.$impmade.'</td>
         <td>'.$left.'</td>
         <td>'.$clicks.'</td>
         <td>'.$percent.'%</td>
         <td>'.$name.' | <span class="small">'.basename(aff_langue($imageurl)).'</span></td>
         <td><a href="admin.php?op=BannerEdit&amp;bid='.$bid.'" ><i class="fa fa-edit fa-lg me-3" title="'.adm_translate("Editer").'" data-bs-toggle="tooltip"></i></a><a href="admin.php?op=BannerDelete&amp;bid='.$bid.'&amp;ok=0" class="text-danger"><i class="fas fa-trash fa-lg" title="'.adm_translate("Effacer").'" data-bs-toggle="tooltip"></i></a></td>
         </tr>';
   }
   echo '
      </tbody>
   </table>
   <hr />
   <h3>'.adm_translate("Bannières terminées").'</h3>
   <table data-toggle="table" data-search="true" data-striped="true" data-mobile-responsive="true" data-show-export="true" data-show-toggle="true" data-show-columns="true" data-buttons-class="outline-secondary" data-icons="icons" data-icons-prefix="fa">
      <thead>
         <tr>
            <th class="n-t-col-xs-1" data-sortable="true" data-halign="center" data-align="right" >'.adm_translate("ID").'</th>
            <th data-sortable="true" data-halign="center" data-align="right" >'.adm_translate("Imp.").'</th>
            <th data-sortable="true" data-halign="center" data-align="right" >'.adm_translate("Clics").'</th>
            <th data-sortable="true" data-halign="center" data-align="right" > % '.adm_translate("Clics").'</th>
            <th data-sortable="true" data-halign="center" data-align="center" >'.adm_translate("Date de début").'</th>
            <th data-sortable="true" data-halign="center" data-align="center" >'.adm_translate("Date de fin").'</th>
            <th data-sortable="true" data-halign="center" data-align="center">'.adm_translate("Nom de l'annonceur").'</th>
            <th data-halign="center" data-align="center">'.adm_translate("Fonctions").'</th>
         </tr>
      </thead>
      <tbody>';
   $result = sql_query("SELECT bid, cid, impressions, clicks, datestart, dateend FROM ".$NPDS_Prefix."bannerfinish ORDER BY bid");
   while (list($bid, $cid, $impressions, $clicks, $datestart, $dateend) = sql_fetch_row($result)) {
   $result2 = sql_query("SELECT cid, name FROM ".$NPDS_Prefix."bannerclient WHERE cid='$cid'");
   list($cid, $name) = sql_fetch_row($result2);
   if ($impressions==0) $impressions=1;
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
            <td><a href="admin.php?op=BannerFinishDelete&amp;bid='.$bid.'" class="text-danger"><i class="fas fa-trash fa-lg" title="'.adm_translate("Effacer").'" data-bs-toggle="tooltip"></i></a></td>
         </tr>';
   }
   echo '
      </tbody>
   </table>
   <hr />
   <h3>'.adm_translate("Annonceurs faisant de la publicité").'</h3>
   <table id="tad_banannon" data-toggle="table" data-search="true" data-striped="true" data-mobile-responsive="true" data-show-export="true" data-show-toggle="true" data-show-columns="true" data-buttons-class="outline-secondary" data-icons="icons" data-icons-prefix="fa">
      <thead>
         <tr>
            <th class="n-t-col-xs-1" data-sortable="true" data-halign="center" data-align="right" >'.adm_translate("ID").'</th>
            <th data-sortable="true" data-halign="center" data-align="center" >'.adm_translate("Nom de l'annonceur").'</th>
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
            <td><a href="admin.php?op=BannerClientEdit&amp;cid='.$cid.'"><i class="fa fa-edit fa-lg me-3" title="'.adm_translate("Editer").'" data-bs-toggle="tooltip"></i></a><a href="admin.php?op=BannerClientDelete&amp;cid='.$cid.'" class="text-danger"><i class="fas fa-trash fa-lg text-danger" title="'.adm_translate("Effacer").'" data-bs-toggle="tooltip"></i></a></td>
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
   <h3 class="my-3">'.adm_translate("Ajouter une nouvelle bannière").'</h3>
   <span class="help-block">'.adm_translate("Pour les bannières Javascript, saisir seulement le code javascript dans la zone URL du clic et laisser la zone image vide.").'</span>
   <span class="help-block">'.adm_translate("Pour les bannières encore plus complexes (Flash, ...), saisir simplement la référence à votre_répertoire/votre_fichier .txt (fichier de code php) dans la zone URL du clic et laisser la zone image vide.").'</span>
   <form id="bannersnewbanner" action="admin.php" method="post">
      <div class="form-floating mb-3">
         <select class="form-select" name="cid">';
   $result = sql_query("SELECT cid, name FROM ".$NPDS_Prefix."bannerclient");
   while(list($cid, $name) = sql_fetch_row($result)) {
   echo '
            <option value="'.$cid.'">'.$name.'</option>';
   }
   echo '
         </select>
         <label for="cid">'.adm_translate("Nom de l'annonceur").'</label>
      </div>
      <div class="form-floating mb-3">
         <input class="form-control" type="number" id="imptotal" name="imptotal" min="0" max="99999999999" required="required" />
         <label for="imptotal">'.adm_translate("Impressions réservées").'</label>
         <span class="help-block">0 = '.adm_translate("Illimité").'</span>
      </div>
      <div class="form-floating mb-3">
         <input class="form-control" type="text" id="imageurl" name="imageurl" maxlength="320" />
         <label for="imageurl">'.adm_translate("URL de l'image").'</label>
         <span class="help-block text-end"><span id="countcar_imageurl"></span></span>
      </div>
      <div class="form-floating mb-3">
         <input class="form-control" type="text" id="clickurl" name="clickurl" maxlength="320" required="required" />
         <label for="clickurl">'.adm_translate("URL du clic").'</label>
         <span class="help-block text-end"><span id="countcar_clickurl"></span></span>
      </div>
      <div class="form-floating mb-3">
         <input class="form-control" type="number" id="userlevel" name="userlevel" min="0" max="9" value="0" required="required" />
         <label for="userlevel">'.adm_translate("Niveau de l'Utilisateur").'</label>
         <span class="help-block">'.adm_translate("0=Tout le monde, 1=Membre seulement, 3=Administrateur seulement, 9=Désactiver").'.</span>
      </div>
      <input type="hidden" name="op" value="BannersAdd" />
      <button class="btn btn-primary my-3" type="submit"><i class="fa fa-plus-square fa-lg me-2"></i>'.adm_translate("Ajouter une bannière").' </button>
   </form>';
   }
   // Add Client
   echo '
   <hr />
   <h3 class="my-3">'.adm_translate("Ajouter un nouvel Annonceur").'</h3>
   <form id="bannersnewanno" action="admin.php" method="post">
      <div class="form-floating mb-3">
         <input class="form-control" type="text" id="name" name="name" maxlength="60" required="required" />
         <label for="name">'.adm_translate("Nom de l'annonceur").'</label>
         <span class="help-block text-end" id="countcar_name"></span>
      </div>
      <div class="form-floating mb-3">
         <input class="form-control" type="text" id="contact" name="contact" maxlength="60" required="required" />
         <label for="contact">'.adm_translate("Nom du Contact").'</label>
         <span class="help-block text-end" id="countcar_contact"></span>
      </div>
      <div class="form-floating mb-3">
         <input class="form-control" type="email" id="email" name="email" maxlength="254" required="required" />
         <label for="email">'.adm_translate("E-mail").'</label>
         <span class="help-block text-end" id="countcar_email"></span>
      </div>
      <div class="form-floating mb-3">
         <input class="form-control" type="text" id="login" name="login" maxlength="10" required="required" />
         <label for="login">'.adm_translate("Identifiant").'</label>
         <span class="help-block text-end" id="countcar_login"></span>
      </div>
      <div class="form-floating mb-3">
         <input class="form-control" type="password" id="passwd" name="passwd" maxlength="20" required="required" />
         <label for="passwd">'.adm_translate("Mot de Passe").'</label>
         <span class="help-block text-end" id="countcar_passwd"></span>
         <div class="progress" style="height: 0.4rem;">
            <div id="passwordMeter_cont" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
         </div>
      </div>
      <div class="form-floating mb-3">
         <textarea class="form-control" id="extrainfo" name="extrainfo" style="height:140px"></textarea>
         <label for="extrainfo">'.adm_translate("Informations supplémentaires").'</label>
      </div>
      <input type="hidden" name="op" value="BannerAddClient" />
      <button class="btn btn-primary my-3" type="submit"><i class="fa fa-plus-square fa-lg me-2"></i>'.adm_translate("Ajouter un annonceur").'</button>
   </form>';
   $arg1 = $numrows>0 ? 'var formulid = ["bannersnewbanner","bannersnewanno"];' : 'var formulid = ["bannersnewanno"];';
   $arg1 .='
      inpandfieldlen("imageurl",320);
      inpandfieldlen("clickurl",320);
      inpandfieldlen("name",60);
      inpandfieldlen("contact",60);
      inpandfieldlen("email",254);
      inpandfieldlen("login",10);
      inpandfieldlen("passwd",20);';
   $fv_parametres = '
   passwd: {
      validators: {
         checkPassword: {},
      }
   },';
   adminfoot('fv',$fv_parametres,$arg1,'');
}
function BannersAdd($cid, $imptotal, $imageurl, $clickurl, $userlevel) {
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
      echo $imageurl!='' ? 
         '<a href="'.aff_langue($clickurl).'"><img class="img-fluid" src="'.aff_langue($imageurl).'" alt="banner" /></a><br />' : 
         $clickurl;
      echo '
      <table data-toggle="table" data-mobile-responsive="true">
         <thead>
            <tr>
               <th data-halign="center" data-align="right">'.adm_translate("ID").'</th>
               <th data-halign="center" data-align="right">'.adm_translate("Impressions").'</th>
               <th data-halign="center" data-align="right">'.adm_translate("Imp. restantes").'</th>
               <th data-halign="center" data-align="right">'.adm_translate("Clics").'</th>
               <th data-halign="center" data-align="right">% '.adm_translate("Clics").'</th>
               <th data-halign="center" data-align="center">'.adm_translate("Nom de l'annonceur").'</th>
            </tr>
         </thead>
         <tbody>';
      $result2 = sql_query("SELECT cid, name FROM ".$NPDS_Prefix."bannerclient WHERE cid='$cid'");
      list($cid, $name) = sql_fetch_row($result2);
      $percent = substr(100 * $clicks / $impmade, 0, 5);
      $left = $imptotal==0 ? adm_translate("Illimité") : $imptotal-$impmade;
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
    <a class="btn btn-danger btn-sm mt-3" href="admin.php?op=BannerDelete&amp;bid='.$bid.'&amp;ok=1">'.adm_translate("Oui").'</a>&nbsp;<a class="btn btn-secondary btn-sm mt-3" href="admin.php?op=BannersAdmin" >'.adm_translate("Non").'</a></div>';
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
   <h3 class="mb-2">'.adm_translate("Edition Bannière").'</h3>';
   if ($imageurl!='')
      echo '<img class="img-fluid" src="'.aff_langue($imageurl).'" alt="banner" /><br />';
   else
      echo $clickurl;
   echo '
   <span class="help-block mt-2">'.adm_translate("Pour les bannières Javascript, saisir seulement le code javascript dans la zone URL du clic et laisser la zone image vide.").'</span>
   <span class="help-block">'.adm_translate("Pour les bannières encore plus complexes (Flash, ...), saisir simplement la référence à votre_répertoire/votre_fichier .txt (fichier de code php) dans la zone URL du clic et laisser la zone image vide.").'</span>
   <form id="bannersadm" action="admin.php" method="post">
      <div class="form-floating mb-3">
         <select class="form-select" id="cid" name="cid">';
   $result = sql_query("SELECT cid, name FROM ".$NPDS_Prefix."bannerclient WHERE cid='$cid'");
   list($cid, $name) = sql_fetch_row($result);
   echo '
            <option value="'.$cid.'" selected="selected">'.$name.'</option>';
   $result = sql_query("SELECT cid, name FROM ".$NPDS_Prefix."bannerclient");
   while (list($ccid, $name) = sql_fetch_row($result)) {
      if ($cid!=$ccid)
         echo '
            <option value="'.$ccid.'">'.$name.'</option>';
   }
   echo '
         </select>
         <label for="cid">'.adm_translate("Nom de l'annonceur").'</label>
      </div>';
   $impressions = $imptotal==0 ? adm_translate("Illimité") : $imptotal;
   echo'
      <div class="form-floating mb-3">
         <input class="form-control" type="number" id="impadded" name="impadded" min="0" max="99999999999" required="required" value="'.$imptotal.'"/>
         <label for="impadded">'.adm_translate("Ajouter plus d'affichages").'</label>
         <span class="help-block">'.adm_translate("Réservé : ").'<strong>'.$impressions.'</strong> '.adm_translate("Fait : ").'<strong>'.$impmade.'</strong></span>
      </div>
      <div class="form-floating mb-3">
         <input class="form-control" type="text" id="imageurl" name="imageurl" maxlength="320" value="'.$imageurl.'" />
         <label for="imageurl">'.adm_translate("URL de l'image").'</label>
         <span class="help-block text-end"><span id="countcar_imageurl"></span></span>
      </div>
      <div class="form-floating mb-3">
         <input class="form-control" type="text" id="clickurl" name="clickurl" maxlength="320" value="'.htmlentities($clickurl,ENT_QUOTES,'UTF-8').'" />
         <label for="clickurl">'.adm_translate("URL du clic").'</label>
         <span class="help-block text-end"><span id="countcar_clickurl"></span></span>
      </div>
      <div class="form-floating mb-3"> 
         <input class="form-control" type="number" name="userlevel" min="0" max="9" value="'.$userlevel.'" required="required" />
         <label for="userlevel">'.adm_translate("Niveau de l'Utilisateur").'</label>
         <span class="help-block">'.adm_translate("0=Tout le monde, 1=Membre seulement, 3=Administrateur seulement, 9=Désactiver").'.</span>
      </div>
      <input type="hidden" name="bid" value="'.$bid.'" />
      <input type="hidden" name="imptotal" value="'.$imptotal.'" />
      <input type="hidden" name="op" value="BannerChange" />
      <button class="btn btn-primary my-3" type="submit"><i class="fa fa-check-square fa-lg me-2"></i>'.adm_translate("Modifier la Bannière").'</button>
   </form>';
   $arg1='
      var formulid = ["bannersadm"];
      inpandfieldlen("imageurl",320);
      inpandfieldlen("clickurl",320);
   ';
   adminfoot('fv','',$arg1,'');
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
      <hr />
      <h3 class="text-danger">'.adm_translate("Supprimer l'Annonceur").'</h3>';
      echo '
      <div class="alert alert-secondary my-3">'.adm_translate("Vous êtes sur le point de supprimer cet annonceur : ").' <strong>'.$name.'</strong> '.adm_translate("et toutes ses bannières !!!");
      $result2 = sql_query("SELECT imageurl, clickurl FROM ".$NPDS_Prefix."banner WHERE cid='$cid'");
      $numrows = sql_num_rows($result2);
      if ($numrows==0)
         echo '<br />'.adm_translate("Cet annonceur n'a pas de bannière active pour le moment.").'</div>';
      else
         echo '
      <br /><span class="text-danger"><b>'.adm_translate("ATTENTION !!!").'</b></span><br />'.adm_translate("Cet annonceur a les BANNIERES ACTIVES suivantes dans").' '.$sitename.'</div>';
      while (list($imageurl, $clickurl) = sql_fetch_row($result2)) {
         echo $imageurl!='' ? 
            '<img class="img-fluid" src="'.aff_langue($imageurl).'" alt="" /><br />' : 
            $clickurl.'<br />';
      }
   }
   echo '<div class="alert alert-danger mt-3">'.adm_translate("Etes-vous sûr de vouloir effacer cet annonceur et TOUTES ses bannières ?").'</div>
   <a href="admin.php?op=BannerClientDelete&amp;cid='.$cid.'&amp;ok=1" class="btn btn-danger">'.adm_translate("Oui").'</a> <a href="admin.php?op=BannersAdmin" class="btn btn-secondary">'.adm_translate("Non").'</a>';
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
   <h3 class="mb-3">'.adm_translate("Editer l'annonceur").'</h3>
   <form action="admin.php" method="post" id="bannersedanno">
      <div class="form-floating mb-3">
         <input class="form-control" type="text" id="name" name="name" value="'.$name.'" maxlength="60" required="required" />
         <label for="name">'.adm_translate("Nom de l'annonceur").'</label>
         <span class="help-block text-end"><span id="countcar_name"></span></span>
      </div>
      <div class="form-floating mb-3">
         <input class="form-control" type="text" id="contact" name="contact" value="'.$contact.'" maxlength="60" required="required" />
         <label for="contact">'.adm_translate("Nom du Contact").'</label>
         <span class="help-block text-end"><span id="countcar_contact"></span></span>
      </div>
      <div class="form-floating mb-3">
         <input class="form-control" type="email" id="email" name="email" maxlength="254" value="'.$email.'" required="required" />
         <label for="email">'.adm_translate("E-mail").'</label>
         <span class="help-block text-end"><span id="countcar_email"></span></span>
      </div>
      <div class="form-floating mb-3">
         <input class="form-control" type="text" id="login" name="login" maxlength="10" value="'.$login.'" required="required" />
         <label for="login">'.adm_translate("Identifiant").'</label>
         <span class="help-block text-end"><span id="countcar_login"></span></span>
      </div>
      <div class="form-floating mb-3">
         <input class="form-control" type="password" id="passwd" name="passwd" maxlength="20" value="'.$passwd.'" required="required" />
         <label for="passwd">'.adm_translate("Mot de Passe").'</label>
         <span class="help-block text-end"><span id="countcar_passwd"></span></span>
         <div class="progress" style="height: 0.4rem;">
            <div id="passwordMeter_cont" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
         </div>
      </div>
      <div class="form-floating mb-3">
         <textarea class="form-control" id="extrainfo" name="extrainfo" style="height:140px">'.$extrainfo.'</textarea>
         <label for="extrainfo">'.adm_translate("Informations supplémentaires").'</label>
      </div>
      <input type="hidden" name="cid" value="'.$cid.'" />
      <input type="hidden" name="op" value="BannerClientChange" />
      <input class="btn btn-primary my-3" type="submit" value="'.adm_translate("Modifier annonceur").'" />
   </form>';
   $arg1='
      var formulid = ["bannersedanno"];
      inpandfieldlen("name",60);
      inpandfieldlen("contact",60);
      inpandfieldlen("email",254);
      inpandfieldlen("login",10);
      inpandfieldlen("passwd",20);
   ';
   $fv_parametres = '
   passwd: {
      validators: {
         checkPassword: {},
      }
   },';
   adminfoot('fv',$fv_parametres,$arg1,'');}
function BannerClientChange($cid, $name, $contact, $email, $extrainfo, $login, $passwd) {
   global $NPDS_Prefix;
   sql_query("UPDATE ".$NPDS_Prefix."bannerclient SET name='$name', contact='$contact', email='$email', login='$login', passwd='$passwd', extrainfo='$extrainfo' WHERE cid='$cid'");
   Header("Location: admin.php?op=BannersAdmin");
}

switch ($op) {
   case 'BannersAdd':
      BannersAdd($cid, $imptotal, $imageurl, $clickurl, $userlevel);
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
   case 'BannersAdmin':
   default:
      BannersAdmin();
   break;
}
?>
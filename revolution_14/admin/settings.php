<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2013 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],"admin.php")) { Access_Error(); }
$f_meta_nom ='Configure';
$f_titre ='Preferences';
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
global $language;
$hlpfile = "manuels/$language/config.html";

function Configure() {
   global $hlpfile, $filemanager,$f_meta_nom, $f_titre, $adminimg;
   include ("config.php");
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '
   <form id="fad_pref" action="admin.php" method="post">
   <fieldset>
   <legend><span class="tog" id="show_info_gene" title="'.adm_translate("Replier la liste").'"><i id="i_info_gene" class="fa fa-plus-square-o" ></i></span>&nbsp;'.adm_translate("Informations générales du site").'</legend>
      <div id="info_gene" class="adminsidefield" style="display:none;">
         <div class="form-group">
            <div class="row">
               <label class="form-control-label text-danger col-sm-4 col-md-4" for="xparse">Parse algo</label>
               <div class="col-sm-8 col-md-8">
                  <label class="radio-inline">';
   if ($parse==0) {
     echo '
                     <input type="radio" name="xparse" value="0" checked>FixQuotes
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xparse" value="1">StripSlashes';
   } else {
     echo '
                     <input type="radio" name="xparse" value="0" />FixQuotes
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xparse" value="1" checked="checked" />StripSlashes';
   }
   echo '
                  </label>
               </div>
            </div>
         </div>
         <input type="hidden" name="xgzhandler" value="0" />
   ';
   echo '
         <div class="form-group">
            <div class="row">
               <label class="form-control-label text-danger col-sm-4 col-md-4" for="xfilemanager">FileManager</label>
               <div class="col-sm-8 col-md-8">
                  <label class="radio-inline">';

   if ($filemanager==1) {
      echo '
                     <input type="radio" name="xfilemanager" value="1" checked="checked" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xfilemanager" value="0" />'.adm_translate("Non");
   } else {
      echo '
                     <input type="radio" name="xfilemanager" value="1" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xfilemanager" value="0" checked="checked" />'.adm_translate("Non");
   }
   echo '
                  </label>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label text-danger col-sm-4 col-md-4" for="xadmin_cook_duration">'.adm_translate("Durée de vie du cookie Admin (en heure)").'</label>
               <div class="col-sm-8 col-md-8">
                  <input class="form-control" type="number" name="xadmin_cook_duration" value="'.$admin_cook_duration.'" min="0" max="9999999999" />
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label text-danger col-sm-4 col-md-4" for="xuser_cook_duration">'.adm_translate("Durée de vie du cookie User (en heure)").'</label>
               <div class="col-sm-8 col-md-8">
                  <input class="form-control" type="number" name="xuser_cook_duration" value="'.$user_cook_duration.'" min="0" max="9999999999" />
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xsitename">'.adm_translate("Nom du site : ").'</label>
               <div class="col-sm-8 col-md-8">
                  <input class="form-control" type="text" name="xsitename" value="'.$sitename.'" size="50" maxlength="100" />
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xTitlesitename">'.adm_translate("Nom du site pour la balise <b>Title</b> : ").'</label>
               <div class="col-sm-8 col-md-8">
                  <input class="form-control" type="text" name="xTitlesitename" value="'.$Titlesitename.'" size="50" maxlength="100" />
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xnuke_url">'.adm_translate("URL du site : ").'</label>
               <div class="col-sm-8 col-md-8">
                  <input class="form-control" type="text" name="xnuke_url" value="'.$nuke_url.'" size="50" maxlength="200" />
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xsite_logo">'.adm_translate("Logo du site (pour les impressions) : ").'</label>
               <div class="col-sm-8 col-md-8">
                  <input class="form-control" type="text" name="xsite_logo" value="'.$site_logo.'" size="50" maxlength="255" />
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xslogan">'.adm_translate("Slogan du site : ").'</label> 
               <div class="col-sm-8 col-md-8">
                  <input class="form-control" type="text" name="xslogan" value="'.$slogan.'" size="50" maxlength="100" />
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xstartdate">'.adm_translate("Date de démarrage du site : ").'</label> 
               <div class="col-sm-8 col-md-8">
                  <input class="form-control" type="text" name="xstartdate" value="'.$startdate.'" size="20" maxlength="30" />
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xtop">'.adm_translate("Nombre d'éléments dans la page <b>Top</b> : ").'</label> 
               <div class="col-sm-8 col-md-8">
                  <input class="form-control" type="number" name="xtop" value="'.$top.'" size="5" maxlength="4" />
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xstoryhome">'.adm_translate("Nombre d'Articles en Page Principale : ").'</label>
               <div class="col-sm-8 col-md-8">
                  <input class="form-control" type="number" name="xstoryhome" value="'.$storyhome.'" min="0" max="9999" />
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xoldnum">'.adm_translate("Nombre d'Articles dans le Bloc des anciens Articles :").'</label>
               <div class="col-sm-8 col-md-8">
                   <input class="form-control" type="number" name="xoldnum" value="'.$oldnum.'" min="0" max="9999" />
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xanonymous">'.adm_translate("Nom par défaut de -anonyme- :").'</label>
               <div class="col-sm-8 col-md-8">
                  <input class="form-control" type="text" name="xanonymous" value="'.$anonymous.'" />
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xmod_admin_news">'.adm_translate("Autoriser la création de News pour").'</label>
               <div class="col-sm-8 col-md-8">
                  <label class="radio-inline">';
   if ($mod_admin_news==1) {
      echo '
                     <input type="radio" name="xmod_admin_news" value="1" checked="checked" />'.adm_translate("Administrateurs").' / '.adm_translate("Modérateurs").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xmod_admin_news" value="2" />'.adm_translate("Membres").'
                  </label>
                     <input type="radio" name="xmod_admin_news" value="0" />'.adm_translate("Tous");
   } elseif ($mod_admin_news==2) {
      echo '
                     <input type="radio" name="xmod_admin_news" value="1" />'.adm_translate("Administrateurs").' / '.adm_translate("Modérateurs").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xmod_admin_news" value="2" checked="checked" />'.adm_translate("Membres").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xmod_admin_news" value="0" />'.adm_translate("Tous");
   } else {
      echo '
                     <input type="radio" name="xmod_admin_news" value="1" />'.adm_translate("Administrateurs").' / '.adm_translate("Modérateurs").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xmod_admin_news" value="2" />'.adm_translate("Membres").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xmod_admin_news" value="0" checked="checked" />'.adm_translate("Tous");
   }
   echo '
                  </label>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xnot_admin_count">'.adm_translate("<b>Ne pas</b> enregistrer les 'Hits' des auteurs dans les statistiques ?").'</label>
               <div class="col-sm-8 col-md-8">
                  <label class="radio-inline">';
   if ($not_admin_count==1) {
      echo '
                     <input type="radio" name="xnot_admin_count" value="1" checked="checked" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xnot_admin_count" value="0" />'.adm_translate("Non");
   } else {
      echo '
                     <input type="radio" name="xnot_admin_count" value="1" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xnot_admin_count" value="0" checked="checked" />'.adm_translate("Non");
   }
   echo '
                  </label>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xDefault_Theme">'.adm_translate("Thème d'affichage par défaut : ").'</label>
               <div class="col-sm-8 col-md-8">
                  <select class="form-control" name="xDefault_Theme">';
   include("themes/list.php");
   $themelist = explode(" ", $themelist);
   for ($i=0; $i < sizeof($themelist); $i++) {
      if($themelist[$i]!="") {
         echo '
                     <option value="'.$themelist[$i].'" ';
             if($themelist[$i]==$Default_Theme) echo 'selected="selected"';
             echo '>'.$themelist[$i].'</option>';
      }
   }
    echo '
                  </select>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xstart_page">'.adm_translate("Page de démarrage").'</label>
               <div class="col-sm-8 col-md-8">
                  <input class="form-control" type="text" name="xstart_page" value="'.$Start_Page.'" size="50" maxlength="100" />
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xlanguage">'.adm_translate("Sélectionner la langue du site : ").'</label>
               <div class="col-sm-8 col-md-8">
                  <select class="form-control" name="xlanguage">';
    include("manuels/list.php");
    $languageslist = explode(" ", $languageslist);
    for ($i=0; $i < sizeof($languageslist); $i++) {
        if ($languageslist[$i]!="") {
           echo '
                     <option value="'.$languageslist[$i].'" ';
           if ($languageslist[$i]==$language) echo 'selected="selected"';
              echo '>'.$languageslist[$i].'</option>';
        }
    }
    echo '
                  </select>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xmulti_langue">'.adm_translate("Activer le Multi-Langue ?").'</label>
               <div class="col-sm-8 col-md-8">
                  <label class="radio-inline">';
   if ($multi_langue==true) {
      echo '
                     <input type="radio" name="xmulti_langue" value="true" checked="checked" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xmulti_langue" value="false" />'.adm_translate("Non");
   } else {
     echo '
                     <input type="radio" name="xmulti_langue" value="true" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xmulti_langue" value="false" checked="checked" />'.adm_translate("Non");
   }
   echo '
                  </label>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xlocale">'.adm_translate("Heure locale : ").'</label>
               <div class="col-sm-8 col-md-8">
                  <input class="form-control" type="text" name="xlocale" value="'.$locale.'" size="20" maxlength="100" />
               </div>
            </div>
         </div>';
      if ($lever=="") {$lever="08:00";}
      if ($coucher=="") {$coucher="20:00";}
      echo '
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xlever">'.adm_translate("Le jour commence à").'</label>
               <div class="col-sm-8 col-md-8">
                  <input class="form-control" type="text" name="xlever" value="'.$lever.'" size="6" maxlength="5" /> (HH:MM)";
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xcoucher">'.adm_translate("La nuit commence à").'</label>
               <div class="col-sm-8 col-md-8">
                  <input class="form-control" type="text" name="xcoucher" value="'.$coucher.'" size="6" maxlength="5" /> (HH:MM)
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xgmt">GMT :</label>
               <div class="col-sm-8 col-md-8">
                  <input class="form-control" type="text" name="xgmt" value="'.$gmt.'" size="4" maxlength="3" /> (+- x)
               </div>
            </div>
         </div>';
   echo '
      </div>
      <script type="text/javascript">
      //<![CDATA[
      tog(\'info_gene\',\'show_info_gene\',\'hide_info_gene\');
      //]]>
      </script>
   </fieldset>
   <fieldset>
   <legend><span class="tog" id="show_banner" title="'.adm_translate("Replier la liste").'"><i id="i_banner" class="fa fa-plus-square-o" ></i></span>&nbsp;'.adm_translate("Options pour les Bannières").'</legend>
      <div id="banner" class="adminsidefield" style="display:none;">
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xbanners">'.adm_translate("Options pour les Bannières").'</label>
               <div class="col-sm-8 col-md-8">
                  <label class="radio-inline">';
   if ($banners==1) {
      echo '
                     <input type="radio" name="xbanners" value="1" checked="checked" />'.adm_translate("Oui").' &nbsp;
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xbanners" value="0" />'.adm_translate("Non");
   } else {
      echo '
                     <input type="radio" name="xbanners" value="1" />'.adm_translate("Oui").' &nbsp;
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xbanners" value="0" checked="checked" />'.adm_translate("Non");
   }
   echo '
                  </label>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xbanners">'.adm_translate("Votre adresse IP (= ne pas comptabiliser les hits qui en proviennent) :").'</label>
               <div class="col-sm-8 col-md-8">
                  <input class="form-control" type="text" name="xmyIP" value="'.$myIP.'" />
               </div>
            </div>
         </div>
      </div>
      <script type="text/javascript">
      //<![CDATA[
      tog(\'banner\',\'show_banner\',\'hide_banner\');
      //]]>
      </script>
   </fieldset>
   <fieldset>
   <legend><span class="tog" id="show_mes_ppage" title="'.adm_translate("Replier la liste").'"><i id="i_mes_ppage" class="fa fa-plus-square-o" ></i></span>&nbsp;'.adm_translate("Message de pied de page").'</legend>
      <div id="mes_ppage" class="adminsidefield" style="display:none;">
         <div class="form-group">
            <label class="form-control-label" for="xfoot1">'.adm_translate("Ligne 1 : ").'</label>
            <textarea class="form-control" name="xfoot1" rows="5">'.htmlentities(stripslashes($foot1),ENT_QUOTES,cur_charset).'</textarea>
         </div>
         <div class="form-group">
            <label class="form-control-label" for="xfoot2">'.adm_translate("Ligne 2 : ").'</label>
            <textarea class="form-control" name="xfoot2" rows="5">'.htmlentities(stripslashes($foot2),ENT_QUOTES,cur_charset).'</textarea>
         </div>
         <div class="form-group">
            <label class="form-control-label" for="xfoot3">'.adm_translate("Ligne 3 : ").'</label>
            <textarea class="form-control" name="xfoot3" rows="5">'.htmlentities(stripslashes($foot3),ENT_QUOTES,cur_charset).'</textarea>
         </div>
         <div class="form-group">
            <label class="form-control-label" for="xfoot4">'.adm_translate("Ligne 4 : ").'</label>
            <textarea class="form-control" name="xfoot4" rows="5">'.htmlentities(stripslashes($foot4),ENT_QUOTES,cur_charset).'</textarea>
         </div>
      </div>
      <script type="text/javascript">
      //<![CDATA[
      tog(\'mes_ppage\',\'show_mes_ppage\',\'hide_mes_ppage\');
      //]]>
      </script>
   </fieldset>
   <fieldset>
   <legend><span class="tog" id="show_bakend_rs" title="'.adm_translate("Replier la liste").'"><i id="i_bakend_rs" class="fa fa-plus-square-o" ></i></span>&nbsp;'.adm_translate("Configuration des infos en Backend & Réseaux Sociaux").'</legend>
      <div id="bakend_rs" class="adminsidefield" style="display:none;">
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xbackend_title">'.adm_translate("Titre du Backend : ").'</label>
               <div class="col-sm-8 col-md-8">
                  <input class="form-control" type="text" name="xbackend_title" value="'.$backend_title.'" size="50" maxlength="100" />
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xbackend_language">'.adm_translate("Langue du  Backend : ").'</label>
               <div class="col-sm-8 col-md-8">
                  <input class="form-control" type="text" name="xbackend_language" value="'.$backend_language.'" size="11" maxlength="10" />
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xbackend_image">'.adm_translate("URL de l'image du Backend : ").'</label>
               <div class="col-sm-8 col-md-8">
                  <input class="form-control" type="text" name="xbackend_image" value="'.$backend_image.'" size="50" maxlength="200" />
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xbackend_width">'.adm_translate("Largeur de l'image du Backend : ").'</label>
               <div class="col-sm-8 col-md-8">
                  <input class="form-control" type="number" name="xbackend_width" value="'.$backend_width.'" min="0" max="9999" />
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xbackend_height">'.adm_translate("Hauteur de l'image du Backend : ").'</label>
               <div class="col-sm-8 col-md-8">
                  <input class="form-control" type="number" name="xbackend_height" value="'.$backend_height.'" min="0" max="9999" />
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xultramode">'.adm_translate("Activer Export-News ?").'</label>
               <div class="col-sm-8 col-md-8">
                  <label class="radio-inline">';
   if ($ultramode==1) {
      echo '
                     <input type="radio" name="xultramode" value="1" checked="checked" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xultramode" value="0" />'.adm_translate("Non");
   } else {
      echo '
                     <input type="radio" name="xultramode" value="1" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xultramode" value="0" checked="checked" />'.adm_translate("Non");
   }
    echo '
                  </label>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xnpds_twi">'.adm_translate("Activer Twitter ?").'</label>
               <div class="col-sm-8 col-md-8">
                  <label class="radio-inline">';
   if ($npds_twi==1) {
      echo '
                     <input type="radio" name="xnpds_twi" value="1" checked="checked" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xnpds_twi" value="0" />'.adm_translate("Non");
   } else {
      echo '
                     <input type="radio" name="xnpds_twi" value="1" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xnpds_twi" value="0" checked="checked" />'.adm_translate("Non");
   }
   echo '
                  </label>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xnpds_fcb">'.adm_translate("Activer Facebook ?").'</label>
               <div class="col-sm-8 col-md-8">
                  <label class="radio-inline">';
   if ($npds_fcb==1) {
      echo '
                     <input type="radio" name="xnpds_fcb" value="1" checked="checked" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xnpds_fcb" value="0" />'.adm_translate("Non");
   } else {
      echo '
                     <input type="radio" name="xnpds_fcb" value="1" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xnpds_fcb" value="0" checked="checked" />'.adm_translate("Non");
    }
    
   echo '
                  </label>
               </div>
            </div>
         </div>
      </div>
      <script type="text/javascript">
      //<![CDATA[
      tog(\'bakend_rs\',\'show_bakend_rs\',\'hide_bakend_rs\');
      //]]>
      </script>
   </fieldset>
   <fieldset>
   <legend><span class="tog" id="show_lien_web" title="'.adm_translate("Replier la liste").'"><i id="i_lien_web" class="fa fa-plus-square-o" ></i></span>&nbsp;'.adm_translate("Configuration par défaut des Liens Web").'</legend>
      <div id="lien_web" class="adminsidefield" style="display:none;">
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xperpage">'.adm_translate("Nombre de liens par page : ").'</label>
               <div class="col-sm-8 col-md-8">
                  <select class="form-control" name="xperpage">
                     <option  value="'.$perpage.'" selected="selected">'.$perpage.'</option>
                     <option value="10">10</option>
                     <option value="15">15</option>
                     <option value="20">20</option>
                     <option value="25">25</option>
                     <option value="30">30</option>
                     <option value="50">50</option>
                  </select>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xpopular">'.adm_translate("Nombre de Hits sur le Lien pour qu'il soit populaire : ").'</label>
               <div class="col-sm-8 col-md-8">
                  <select class="form-control" name="xpopular">
                     <option value="'.$popular.'" selected="selected">'.$popular.'</option>
                     <option value="10">10</option>
                     <option value="20">20</option>
                     <option value="50">50</option>
                     <option value="100">100</option>
                     <option value="250">250</option>
                     <option value="500">500</option>
                  </select>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xnewlinks">'.adm_translate("Nombre de Liens 'Nouveaux' : ").'</label>
               <div class="col-sm-8 col-md-8">
                  <select class="form-control" name="xnewlinks">
                     <option value="'.$newlinks.'" selected="selected">'.$newlinks.'</option>
                     <option value="10">10</option>
                     <option value="15">15</option>
                     <option value="20">20</option>
                     <option value="25">25</option>
                     <option value="30">30</option>
                     <option value="50">50</option>
                  </select>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xtoplinks">'.adm_translate("Nombre de Liens 'Meilleur' : ").'</label>
               <div class="col-sm-8 col-md-8">
                  <select class="form-control" name="xtoplinks">
                     <option value="'.$toplinks.'" selected="selected">'.$toplinks.'</option>
                     <option value="10">10</option>
                     <option value="15">15</option>
                     <option value="20">20</option>
                     <option value="25">25</option>
                     <option value="30">30</option>
                     <option value="50">50</option>
                  </select>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xlinksresults">'.adm_translate("Nombre de Liens dans les résultats des recherches : ").'</label>
               <div class="col-sm-8 col-md-8">
                  <select class="form-control" name="xlinksresults">
                     <option value="'.$linksresults.'" selected="selected">'.$linksresults.'</option>
                     <option value="10">10</option>
                     <option value="15">15</option>
                     <option value="20">20</option>
                     <option value="25">25</option>
                     <option value="30">30</option>
                     <option value="50">50</option>
                  </select>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xlinks_anonaddlinklock">'.adm_translate("Laisser les Utilisateurs anonymes poster de nouveaux Liens ?").'</label>
               <div class="col-sm-8 col-md-8">
                  <label class="radio-inline">';
   if ($links_anonaddlinklock==0) {
      echo '
                     <input type="radio" name="xlinks_anonaddlinklock" value="0" checked="checked" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xlinks_anonaddlinklock" value="1" />'.adm_translate("Non");
   } else {
      echo '
                     <input type="radio" name="xlinks_anonaddlinklock" value="0" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xlinks_anonaddlinklock" value="1" checked="checked" />'.adm_translate("Non");
    }
   echo '
                  </label>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xlinkmainlogo">'.adm_translate("Afficher le Logo sur la page Web Links ?").'</label>
               <div class="col-sm-8 col-md-8">
                  <label class="radio-inline">';
   if ($linkmainlogo==1) {
      echo '
                     <input type="radio" name="xlinkmainlogo" value="1" checked="checked" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xlinkmainlogo" value="0" />'.adm_translate("Non");
   } else {
      echo '
                     <input type="radio" name="xlinkmainlogo" value="1" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xlinkmainlogo" value="0" checked="checked" />'.adm_translate("Non");
   }
   echo '
                  </label>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xOnCatNewLink">'.adm_translate("Activer l'icône [N]ouveau pour les Catégories ?").'</label>
               <div class="col-sm-8 col-md-8">
                  <label class="radio-inline">';
   if ($OnCatNewLink==1) {
      echo '
                     <input type="radio" name="xOnCatNewLink" value="1" checked="checked" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xOnCatNewLink" value="0" />'.adm_translate("Non");
   } else {
      echo '
                     <input type="radio" name="xOnCatNewLink" value="1" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xOnCatNewLink" value="0" checked="checked" />'.adm_translate("Non");
    }

   echo'
                  </label>
               </div>
            </div>
         </div>
      </div>
      <script type="text/javascript">
      //<![CDATA[
      tog(\'lien_web\',\'show_lien_web\',\'hide_lien_web\');
      //]]>
      </script>
   </fieldset>
   <fieldset>
   <legend><span class="tog" id="show_sys_mes" title="'.adm_translate("Replier la liste").'"><i id="i_sys_mes" class="fa fa-plus-square-o" ></i></span>&nbsp;'.adm_translate("Système de Messagerie (Email)").'</legend>
      <div id="sys_mes" class="adminsidefield" style="display:none;">
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xadminmail">'.adm_translate("Adresse E-mail de l'Administrateur : ").'</label> 
               <div class="col-sm-8 col-md-8">
                  <input class="form-control" type="text" name="xadminmail" value="'.$adminmail.'" size="50" maxlength="100" />
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
            <label class="form-control-label col-sm-4 col-md-4" for="xmail_fonction">'.adm_translate("Quelle fonction Mail utilisée ?").'</label>
               <div class="col-sm-8 col-md-8">
                  <label class="radio-inline">
   ';
   if (!$mail_fonction) {$mail_fonction=1;}
   if ($mail_fonction==1) {
      echo '
                     <input type="radio" name="xmail_fonction" value="1" checked="checked" />function (fonction) => mail
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xmail_fonction" value="2" />function (fonction) => email';
   } else {
      echo '
                     <input type="radio" name="xmail_fonction" value="1" />function (fonction) => mail &nbsp;
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xmail_fonction" value="2" checked="checked" />function (fonction) => email';
   }

   // Footer of Email send by NPDS
   include ("signat.php");
   echo '
                  </label>
               </div>
            </div>
         </div>
         <div class="form-group">
            <label class="form-control-label" for="xEmailFooter">'.adm_translate("Pied").' '.adm_translate("de").' Email :</label> 
            <textarea class="form-control" name="xEmailFooter" cols="45" rows="8">'.$message.'</textarea>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xnotify">'.adm_translate("Notifier les nouvelles contributions par E-mail ?").'</label>
               <div class="col-sm-8 col-md-8">
                  <label class="radio-inline">';

//    adm_translate("Envoyer par E-mail les nouveaux Articles à l'Administrateur").
   if ($notify==1) {
      echo '
                    <input type="radio" name="xnotify" value="1" checked="checked" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xnotify" value="0" />'.adm_translate("Non");
   } else {
      echo '
                     <input type="radio" name="xnotify" value="1" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xnotify" value="0" checked="checked" />'.adm_translate("Non");
   }
   echo '
                  </label>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xnotify_email">'.adm_translate("Adresse E-mail où envoyer le message : ").'</label>
               <div class="col-sm-8 col-md-8">
                  <input class="form-control" type="text" name="xnotify_email" value="'.$notify_email.'" size="50" maxlength="100" />
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xnotify_subject">'.adm_translate("Sujet de l'E-mail : ").'</label>
               <div class="col-sm-8 col-md-8">
                  <input class="form-control" type="text" name="xnotify_subject" value="'.$notify_subject.'" size="50" maxlength="100" />
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xnotify_message">'.adm_translate("Message de l'E-mail : ").'</label>
               <div class="col-sm-8 col-md-8">
                  <textarea class="form-control" name="xnotify_message" cols="45" rows="8">'.$notify_message.'</textarea>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xnotify_from">'.adm_translate("Compte E-mail (Provenance) : ").'</label>
               <div class="col-sm-8 col-md-8">
                  <input class="form-control" type="text" name="xnotify_from" value="'.$notify_from.'" size="50" maxlength="100" />
               </div>
            </div>
         </div>
      </div>
      <script type="text/javascript">
      //<![CDATA[
      tog(\'sys_mes\',\'show_sys_mes\',\'hide_sys_mes\');
      //]]>
      </script>
   </fieldset>
   <fieldset>
   <legend><span class="tog" id="show_opt_comment" title="'.adm_translate("Replier la liste").'"><i id="i_opt_comment" class="fa fa-plus-square-o" ></i></span>&nbsp;'.adm_translate("Options pour les Commentaires").'</legend>
      <div id="opt_comment" class="adminsidefield" style="display:none;">
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-7 col-md-7" for="xmoderate">'.adm_translate("Type de modération : ").'</label>
               <div class="col-sm-5 col-md-5">
                  <select class="form-control" name="xmoderate">';
   if ($moderate==1) {
      echo '
                     <option value="1" selected="selected">'.adm_translate("Modération par l'Administrateur").'</option>
                     <option value="2">'.adm_translate("Modération par les Utilisateurs").'</option>
                     <option value="0">'.adm_translate("Pas de modération").'</option>';
   } elseif ($moderate==2) {
      echo '
                     <option value="1">'.adm_translate("Modération par l'Administrateur").'</option>
                     <option value="2" selected="selected">'.adm_translate("Modération par les Utilisateurs").'</option>
                     <option value="0">'.adm_translate("Pas de modération")."</option>";
   } elseif ($moderate==0) {
      echo '
                     <option value="1">'.adm_translate("Modération par l'Administrateur").'</option>
                     <option value="2">'.adm_translate("Modération par les Utilisateurs").'</option>
                     <option value="0" selected="selected">'.adm_translate("Pas de modération").'</option>';
   }
   echo '
                  </select>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-7 col-md-7" for="xanonpost">'.adm_translate("Autoriser les Commentaires anonymes ?").'</label>
               <div class="col-sm-5 col-md-5">
                  <label class="radio-inline">';

   if ($anonpost==1) {
      echo '
                     <input type="radio" name="xanonpost" value="1" checked="checked" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xanonpost" value="0" />'.adm_translate("Non");
   } else {
      echo '
                     <input type="radio" name="xanonpost" value="1" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xanonpost" value="0" checked="checked" />'.adm_translate("Non");
   }
   echo '
                  </label>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-7 col-md-7" for="xtroll_limit">'.adm_translate("Nombre Maximum de Commentaire par utilisateur (24H) ?").'</label>
               <div class="col-sm-5 col-md-5">';
   if ($troll_limit=="") $troll_limit="6";
   echo '
                  <input class="form-control" type="number" name="xtroll_limit" value="'.$troll_limit.'" min="0" max="99999" required="required" />
               </div>
            </div>
         </div>
      </div>
      <script type="text/javascript">
      //<![CDATA[
      tog(\'opt_comment\',\'show_opt_comment\',\'hide_opt_comment\');
      //]]>
      </script>
   </fieldset>
   <fieldset>
   <legend><span class="tog" id="show_opt_sond" title="'.adm_translate("Replier la liste").'"><i id="i_opt_sond" class="fa fa-plus-square-o" ></i></span>&nbsp;'.adm_translate("Options des sondages").'</legend>
      <div id="opt_sond" class="adminsidefield" style="display:none;">
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-5 col-md-5" for="xmaxOptions">'.adm_translate("Nombre maximum de choix pour les Sondages : ").'</label>
               <div class="col-sm-7 col-md-7">
                  <select class="form-control" name="xmaxOptions">
                     <option value="'.$maxOptions.'">'.$maxOptions.'</option>
                     <option value="10">10</option>
                     <option value="12">12</option>
                     <option value="15">15</option>
                     <option value="20">20</option>
                  </select>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-5 col-md-5" for="xsetCookies">'.adm_translate("Autoriser les Utilisateurs à voter plusieurs fois ?").'</label>
               <div class="col-sm-7 col-md-7">
                  <label class="radio-inline">';
   if ($setCookies==0) {
      echo '
                     <input type="radio" name="xsetCookies" value="0" checked="checked" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xsetCookies" value="1" />'.adm_translate("Non");
   } else {
      echo '
                     <input type="radio" name="xsetCookies" value="0" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xsetCookies" value="1" checked="checked" />'.adm_translate("Non");
   }
   echo'
                  </label>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-5 col-md-5" for="xpollcomm">'.adm_translate("Activer les Commentaires des Sondages ?").'</label>
               <div class="col-sm-7 col-md-7">
                  <label class="radio-inline">';

   if ($pollcomm==1) {
      echo '
                     <input type="radio" name="xpollcomm" value="1" checked="checked" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xpollcomm" value="0" />'.adm_translate("Non");
   } else {
      echo '
                     <input type="radio" name="xpollcomm" value="1" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xpollcomm" value="0" checked="checked" />'.adm_translate("Non");
   }
   echo '
                  </label>
               </div>
            </div>
         </div>
      </div>
      <script type="text/javascript">
      //<![CDATA[
      tog(\'opt_sond\',\'show_opt_sond\',\'hide_opt_sond\');
      //]]>
      </script>
   </fieldset>
   <fieldset>
   <legend><span class="tog" id="show_para_illu" title="'.adm_translate("Replier la liste").'"><i id="i_para_illu" class="fa fa-plus-square-o" ></i></span>&nbsp;'.adm_translate("Paramètres liés à l'illustration").'</legend>
      <div id="para_illu" class="adminsidefield" style="display:none;">
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xtipath">'.adm_translate("Chemin des images des Sujets : ").'</label>
               <div class="col-sm-8 col-md-8">
                  <input class="form-control" type="text" name="xtipath" value="'.$tipath.'" size="50" maxlength="100" />
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xuserimg">'.adm_translate("Chemin de certaines images (vote, ...) : ").'</label>
               <div class="col-sm-8 col-md-8">
                  <input class="form-control" type="text" name="xuserimg" value="'.$userimg.'" size="50" maxlength="100" />
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xadminimg">'.adm_translate("Chemin des images du Menu Administrateur : ").'</label>
               <div class="col-sm-8 col-md-8">
                  <input class="form-control" type="text" name="xadminimg" value="'.$adminimg.'" size="50" maxlength="100" />
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xadmingraphic">'.adm_translate("Activer les images dans le menu Administration ?").'</label>
               <div class="col-sm-8 col-md-8">
                  <label class="radio-inline">';
   if ($admingraphic==1) {
      echo '
                     <input type="radio" name="xadmingraphic" value="1" checked="checked" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xadmingraphic" value="0" />'.adm_translate("Non");
   } else {
      echo '
                     <input type="radio" name="xadmingraphic" value="1" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xadmingraphic" value="0" checked="checked" />'.adm_translate("Non");
   }
   echo '
                  </label>
               </div>
            </div>
         </div>';
   if (!$admf_ext) {$admf_ext="gif";}

   echo '
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xadmf_ext">'.adm_translate("Extension des fichiers d'image").'</label>
               <div class="col-sm-8 col-md-8">
                  <input class="form-control" type="text" name="xadmf_ext" value="'.$admf_ext.'" size="4" maxlength="3" />
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xshort_menu_admin">'.adm_translate("Activer les Menus courts pour l'administration ?").'</label>
               <div class="col-sm-8 col-md-8">
                  <label class="radio-inline">';

   if ($short_menu_admin==1) {
      echo '
                     <input type="radio" name="xshort_menu_admin" value="1" checked="checked" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xshort_menu_admin" value="0" />'.adm_translate("Non");
   } else {
      echo '
                     <input type="radio" name="xshort_menu_admin" value="1" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xshort_menu_admin" value="0" checked="checked" />'.adm_translate("Non");
   }
   echo '
                  </label>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xsite_font">'.adm_translate("Polices du site : ").'</label>
               <div class="col-sm-8 col-md-8">
                  <input class="form-control" type="text" name="xsite_font" value="'.$site_font.'" size="50" maxlength="100" />
               </div>
            </div>
         </div>
      </div>
      <script type="text/javascript">
      //<![CDATA[
      tog("para_illu","show_para_illu","hide_para_illu");
      //]]>
      </script>
   </fieldset>
   <fieldset>
   <legend><span class="tog" id="show_divers" title="'.adm_translate("Replier la liste").'"><i id="i_divers" class="fa fa-plus-square-o" ></i></span>&nbsp;'.adm_translate("Divers").'</legend>
      <div id="divers" class="adminsidefield" style="display:none;">
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xadmart">'.adm_translate("Nombres d'articles en mode Administration : ").'</label>
               <div class="col-sm-8 col-md-8">
                  <select class="form-control" name="xadmart">
                     <option value="'.$admart.'">'.$admart.'</option>
                     <option value="10">10</option>
                     <option value="15">15</option>
                     <option value="20">20</option>
                     <option value="25">25</option>
                     <option value="30">30</option>
                  </select>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xminpass">'.adm_translate("Longueur minimum du Mot de Passe des Utilisateurs : ").'</label>
               <div class="col-sm-8 col-md-8">
                  <select class="form-control" name="xminpass">
                     <option value="'.$minpass.'">'.$minpass.'</option>
                     <option value="3">3</option>
                     <option value="4">4</option>
                     <option value="5">5</option>
                     <option value="8">8</option>
                     <option value="10">10</option>
                  </select>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xshow_user">'.adm_translate("Nombre d'Utilisateurs listés : ").'</label>
               <div class="col-sm-8 col-md-8">
                  <select class="form-control" name="xshow_user">
                     <option value="'.$show_user.'">'.$show_user.'</option>
                     <option value="10">10</option>
                     <option value="20">20</option>
                     <option value="30">30</option>
                     <option value="40">40</option>
                  </select>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xsmilies">'.adm_translate("Activer les Avatars ?").'</label>
               <div class="col-sm-8 col-md-8">
                  <label class="radio-inline">';
   if ($smilies==1) {
      echo '
                     <input type="radio" name="xsmilies" value="1" checked="checked" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xsmilies" value="0" />'.adm_translate("Non");
   } else {
      echo '
                     <input type="radio" name="xsmilies" value="1" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xsmilies" value="0" checked="checked" />'.adm_translate("Non");
   }
    echo '
                  </label>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xavatar_size">'.adm_translate("Taille maximum des avatars personnels (largeur * hauteur / 60*80) en pixel ?").'</label>
               <div class="col-sm-8 col-md-8">';
   if (!$avatar_size) {$avatar_size="60*80";}
   echo '
                  <input class="form-control" type="text" name="xavatar_size" value="'.$avatar_size.'" size="11" maxlength="10" />
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xshort_user">'.adm_translate("Activer la description simplifiée des Utilisateurs ?").'</label>
               <div class="col-sm-8 col-md-8">
                  <label class="radio-inline">';
   if ($short_user==1) {
      echo '
                     <input type="radio" name="xshort_user" value="1" checked="checked" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xshort_user" value="0" />'.adm_translate("Non");
   } else {
      echo '
                     <input type="radio" name="xshort_user" value="1" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xshort_user" value="0" checked="checked" />'.adm_translate("Non");
   }
   echo '
                  </label>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xAutoRegUser">'.adm_translate("Autoriser la création automatique des membres ?").'</label>
               <div class="col-sm-8 col-md-8">
                  <label class="radio-inline">';
   if (($AutoRegUser=="") and ($AutoRegUser!=0)) {$AutoRegUser=1;}
   if ($AutoRegUser==1) {
      echo '
                     <input type="radio" name="xAutoRegUser" value="1" checked="checked" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xAutoRegUser" value="0" />'.adm_translate("Non");
   } else {
      echo '
                     <input type="radio" name="xAutoRegUser" value="1" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xAutoRegUser" value="0" checked="checked" />'.adm_translate("Non");
    }
    echo '
                  </label>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xmemberpass">'.adm_translate("Autoriser les utilisateurs à choisir leur Mot de Passe ?").'</label>
               <div class="col-sm-8 col-md-8">
                  <label class="radio-inline">';
   if (($memberpass=="") and ($memberpass!=0)) {$memberpass=1;}
   if ($memberpass==1) {
      echo '
                     <input type="radio" name="xmemberpass" value="1" checked="checked" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xmemberpass" value="0" />'.adm_translate("Non");
   } else {
      echo '
                     <input type="radio" name="xmemberpass" value="1" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xmemberpass" value="0" checked="checked" />'.adm_translate("Non");
   }
   echo '
                  </label>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xsubscribe">'.adm_translate("Autoriser les Abonnements ?").'</label>
               <div class="col-sm-8 col-md-8">
                  <label class="radio-inline">';
   if ($subscribe==1) {
      echo '
                     <input type="radio" name="xsubscribe" value="1" checked="checked" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xsubscribe" value="0" />'.adm_translate("Non");
   } else {
      echo '
                     <input type="radio" name="xsubscribe" value="1" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xsubscribe" value="0" checked="checked" />'.adm_translate("Non");
   }
   echo '
                  </label>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xmember_invisible">'.adm_translate("Autoriser les Membres Invisibles ?").'</label>
               <div class="col-sm-8 col-md-8">
                  <label class="radio-inline">';
   if ($member_invisible==1) {
      echo '
                     <input type="radio" name="xmember_invisible" value="1" checked="checked" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xmember_invisible" value="0" />'.adm_translate("Non");
   } else {
      echo '
                     <input type="radio" name="xmember_invisible" value="1" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xmember_invisible" value="0" checked="checked" />'.adm_translate("Non");
    }
   echo '
                  </label>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xCloseRegUser">'.adm_translate("Fermer les Nouvelles Inscriptions ?").'</label>
               <div class="col-sm-8 col-md-8">
                  <label class="radio-inline">';
   if (($CloseRegUser=="") and ($CloseRegUser!=1)) {$AutoRegUser=0;}
   if ($CloseRegUser==1) {
      echo '
                     <input type="radio" name="xCloseRegUser" value="1" checked="checked" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xCloseRegUser" value="0" />'.adm_translate("Non");
   } else {
      echo '
                     <input type="radio" name="xCloseRegUser" value="1" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xCloseRegUser" value="0" checked="checked" />'.adm_translate("Non");
   }
   echo '
                  </label>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xhttpref">'.adm_translate("Activer les référants HTTP ?").'</label>
               <div class="col-sm-8 col-md-8">
                  <label class="radio-inline">';
   if ($httpref==1) {
      echo '
                     <input type="radio" name="xhttpref" value="1" checked="checked" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xhttpref" value="0" />'.adm_translate("Non");
   } else {
      echo '
                     <input type="radio" name="xhttpref" value="1" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xhttpref" value="0" checked="checked" />'.adm_translate("Non");
    }
    echo '
                  </label>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xhttprefmax">'.adm_translate("Combien de référants au maximum ?").'</label>
               <div class="col-sm-8 col-md-8">
                  <select class="form-control" name="xhttprefmax">
                     <option value="'.$httprefmax.'">'.$httprefmax.'</option>
                     <option value="100">100</option>
                     <option value="250">250</option>
                     <option value="500">500</option>
                     <option value="1000">1000</option>
                     <option value="2000">2000</option>
                     <option value="4000">4000</option>
                     <option value="8000">8000</option>
                  </select>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xmember_list">'.adm_translate("Liste des membres").' : '.adm_translate("Privé").' ?</label>
               <div class="col-sm-8 col-md-8">
                  <label class="radio-inline">';
   if ($member_list==1) {
      echo '
                     <input type="radio" name="xmember_list" value="1" checked="checked" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xmember_list" value="0" />'.adm_translate("Non");
   } else {
      echo '
                     <input type="radio" name="xmember_list" value="1" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xmember_list" value="0" checked="checked" />'.adm_translate("Non");
   }
   echo '
                  </label>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xdownload_cat">'.adm_translate("Rubrique de téléchargement").'</label>
               <div class="col-sm-8 col-md-8">
                  <select class="form-control" name="xdownload_cat">
                     <option value="'.$download_cat.'">'.aff_langue($download_cat).'</option>';
   $result = sql_query("select distinct dcategory from ".$NPDS_Prefix."downloads");
   while (list($category) = sql_fetch_row($result)) {
      $category=stripslashes($category);
      echo '
                     <option value="'.$category.'">'.aff_langue($category).'</option>';
   }
   echo '
                     <option value="'.adm_translate("Tous").'">- '.adm_translate("Tous").'</option>
                     <option value="'.adm_translate("Aucune catégorie").'">- '.adm_translate("Aucune catégorie").'</option>
                  </select>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="xshort_review">'.adm_translate("Critiques").' : '.adm_translate("courtes").' ?</label>
               <div class="col-sm-8 col-md-8">
                  <label class="radio-inline">';
   if ($short_review==1) {
      echo '
                     <input type="radio" name="xshort_review" value="1" checked="checked" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xshort_review" value="0" />'.adm_translate("Non");
   } else {
      echo '
                     <input type="radio" name="xshort_review" value="1" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xshort_review" value="0" checked="checked" />'.adm_translate("Non");
   }
   echo '
                  </label>
               </div>
            </div>
         </div>
      </div>
      <script type="text/javascript">
      //<![CDATA[
      tog("divers","show_divers","hide_divers");
      //]]>
      </script>
   </fieldset>
   <fieldset>
   <legend><span class="tog" id="show_divers_http" title="'.adm_translate("Replier la liste").'"><i id="i_divers_http" class="fa fa-plus-square-o" ></i>&nbsp;</span>'.adm_translate("Divers").' HTTP</legend>
      <div id="divers_http" class="adminsidefield" style="display:none;">
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-8 col-md-8" for="xrss_host_verif">'.adm_translate("Pour les Grands Titres de sites de News, activer la vérification de l'existance d'un Web sur le Port 80 ?").'</label>
               <div class="col-sm-4 col-md-4">
                  <label class="radio-inline">
    ';
   if ($rss_host_verif==true) {
      echo '
                     <input type="radio" name="xrss_host_verif" value="true" checked="checked" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xrss_host_verif" value="false" />'.adm_translate("Non");
   } else {
      echo '
                     <input type="radio" name="xrss_host_verif" value="true" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xrss_host_verif" value="false" checked="checked" />'.adm_translate("Non");
    }
   echo '
                  </label>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-8 col-md-8" for="xcache_verif">'.adm_translate("Pour les pages HTML générées, activer les tags avancés de gestion du cache ?").'</label>
               <div class="col-sm-4 col-md-4">
                  <label class="radio-inline">';
    if ($cache_verif==true) {
        echo '
                     <input type="radio" name="xcache_verif" value="true" checked="checked" />'.adm_translate("Oui").' &nbsp;
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xcache_verif" value="false" />'.adm_translate("Non").' <span style="font-size: 10px;">(Multimania)</span>';
    } else {
        echo '
                     <input type="radio" name="xcache_verif" value="true" />'.adm_translate("Oui").' &nbsp;
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xcache_verif" value="false" checked="checked" />'.adm_translate("Non").' <span style="font-size: 10px;">(Multimania)</span>';
    }
   echo '
               </label>
            </div>
         </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-8 col-md-8" for="xdns_verif">'.adm_translate("Activer la résolution DNS pour les posts des forums, IP-Ban, ... ?").'</label>
               <div class="col-sm-4 col-md-4">
                  <label class="radio-inline">';
    if ($dns_verif==true) {
        echo '
                     <input type="radio" name="xdns_verif" value="true" checked="checked" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xdns_verif" value="false" />'.adm_translate("Non");
    } else {
        echo '
                     <input type="radio" name="xdns_verif" value="true" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="xdns_verif" value="false" checked="checked" />'.adm_translate("Non");
    }
    echo '
               </label>
            </div>
         </div>
      </div>
   </div>
   <script type="text/javascript">
      //<![CDATA[ 
      tog(\'divers_http\',\'show_divers_http\',\'hide_divers_http\');
       //]]>
   </script>
   </fieldset>
   <fieldset>
      <legend><span class="tog" id="show_divers_syst" title="'.adm_translate("Replier la liste").'"><i id="i_divers_syst" class="fa fa-plus-square-o" ></i>&nbsp;</span>'.adm_translate("Divers").' SYSTEM</legend>
      <div id="divers_syst" class="adminsidefield" style="display:none;">';
    if (!$savemysql_size) {
       $savemysql_size="256";
    } else {
       if ($savemysql_size=="256") $sel_size256='selected="selected"'; else $sel_size256='';
       if ($savemysql_size=="512") $sel_size512='selected="selected"'; else $sel_size512='';
       if ($savemysql_size=="1024") $sel_size1024='selected="selected"'; else $sel_size1024='';
    }
   echo '
   <div class="form-group">
      <div class="row">
         <label class="form-control-label col-sm-4 col-md-4" for="xsavemysql_size">'.adm_translate("Taille maximum des fichiers de sauvegarde SaveMysql").'</label>
         <div class="col-sm-8 col-md-8">
            <select class="form-control" name="xsavemysql_size">
               <option value="256" '.$sel_size256.'>256 Ko</option>
               <option value="512" '.$sel_size512.'>512 Ko</option>
               <option value="1024" '.$sel_size1024.'>1024 Ko</option>
            </select>
         </div>
      </div>
   </div>';
    if (!$savemysql_mode) {
       $savemysql_mode="1";
    } else {
       if ($savemysql_mode=="1") $type_save1='selected="selected"'; else $type_save1='';
       if ($savemysql_mode=="2") $type_save2='selected="selected"'; else $type_save2='';
       if ($savemysql_mode=="3") $type_save3='selected="selected"'; else $type_save3='';
    }
   echo '
   <div class="form-group">
      <div class="row">
         <label class="form-control-label col-sm-4 col-md-4" for="xsavemysql_mode">'.adm_translate("Type de sauvegarde SaveMysql").'</label>
         <div class="col-sm-8 col-md-8">
            <select class="form-control" name="xsavemysql_mode">
               <option value="1" '.$type_save1.'>'.adm_translate("Toute tables. Fichier envoyé au navigateur. Pas de limite de taille").'</option>
               <option value="2" '.$type_save2.'>'.adm_translate("Fichiers dans /slogs. table par table, tables non scindées : limite").'&nbsp;'.$savemysql_size.' Ko</option>
               <option value="3" '.$type_save3.'>'.adm_translate("Fichiers dans /slogs. table par table, lignes par lignes, tables scindées : limite").'&nbsp;'.$savemysql_size.' Ko</option>
            </select>
         </div>
      </div>
   </div>
   <div class="form-group">
      <div class="row">
         <label class="form-control-label col-sm-4 col-md-4" for="xtiny_mce">'.adm_translate("Activer l'éditeur Tiny-Mce").'</label>
         <div class="col-sm-8 col-md-8">
            <label class="radio-inline">';
   if ($tiny_mce) {
      echo '
               <input type="radio" name="xtiny_mce" value="true" checked="checked" />'.adm_translate("Oui").'
            </label>
            <label class="radio-inline">
               <input type="radio" name="xtiny_mce" value="false" />'.adm_translate("Non");
   } else {
      echo '
               <input type="radio" name="xtiny_mce" value="true" />'.adm_translate("Oui").'
            </label>
            <label class="radio-inline">
               <input type="radio" name="xtiny_mce" value="false" checked="checked" />'.adm_translate("Non");
   }
   echo '
            </label>
         </div>
      </div>
   </div>

   </div>
   <script type="text/javascript">
      //<![CDATA[
      tog(\'divers_syst\',\'show_divers_syst\',\'hide_divers_syst\');
      //]]>
   </script>
   </fieldset>
   <input type="hidden" name="op" value="ConfigSave" />
   <div class="form-group">
      <button class="btn btn-primary" type="submit">'.adm_translate("Sauver les modifications").'</button>
   </div>
   </form>';
    adminfoot('fv','','','');
}

switch ($op) {
   case "Configure":
      Configure();
      break;

   case "ConfigSave":
      include("admin/settings_save.php");
      ConfigSave($xparse,$xsitename,$xnuke_url,$xsite_logo,$xslogan,$xstartdate,$xadminmail,$xtop,$xstoryhome,$xoldnum,$xultramode,$xanonpost,$xDefault_Theme,$xbanners,$xmyIP,$xfoot1,$xfoot2,$xfoot3,$xfoot4,$xbackend_title,$xbackend_language,$xbackend_image,$xbackend_width,$xbackend_height,$xlanguage,$xlocale,$xperpage,$xpopular,$xnewlinks,$xtoplinks,$xlinksresults,$xlinks_anonaddlinklock,$xnotify,$xnotify_email,$xnotify_subject,$xnotify_message,$xnotify_from,$xmoderate,$xanonymous,$xmaxOptions,$xsetCookies,$xtipath,$xuserimg,$xadminimg,$xadmingraphic,$xsite_font,$xadmart,$xminpass,$xhttpref,$xhttprefmax,$xpollcomm,$xlinkmainlogo,$xstart_page,$xsmilies,$xOnCatNewLink,$xEmailFooter,$xshort_user,$xgzhandler,$xrss_host_verif,$xcache_verif,$xmember_list,$xdownload_cat,$xmod_admin_news,$xgmt,$xAutoRegUser,$xTitlesitename,$xfilemanager,$xshort_review,$xnot_admin_count,$xadmin_cook_duration,$xuser_cook_duration,$xtroll_limit,$xsubscribe,$xCloseRegUser,$xshort_menu_admin,$xmail_fonction,$xmemberpass,$xshow_user,$xdns_verif,$xmember_invisible,$xavatar_size,$xlever,$xcoucher,$xmulti_langue,$xadmf_ext,$xsavemysql_size,$xsavemysql_mode,$xtiny_mce,$xnpds_twi,$xnpds_fcb);
      break;
}
?>
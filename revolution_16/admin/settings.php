<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

if (!function_exists('admindroits'))
   include('die.php');
$f_meta_nom ='Configure';
$f_titre = adm_translate("Préférences");
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
global $language;
$hlpfile = "manuels/$language/config.html";

function Configure() {
   global $hlpfile, $filemanager,$f_meta_nom, $f_titre, $adminimg;
   include ("config.php");
   $notmodifiedlangue = $language; //ici la variable est conforme
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '
   <hr />
   <form id="settingspref" action="admin.php" method="post">
   <fieldset>
      <legend><a class="tog" id="show_info_gene" title="'.adm_translate("Replier la liste").'"><i id="i_info_gene" class="fa fa-caret-down fa-lg text-primary" ></i></a>&nbsp;'.adm_translate("Informations générales du site").'</legend>
      <div id="info_gene" class="adminsidefield card card-body mb-3" style="display:none;">
         <div class="row">
            <div class="col-md-6 mb-3">
               <div class="mb-1" for="xparse">Parse algo</div>';
   $cky='';$ckn='';
   if ($parse==0) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo '
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xparse_fix" name="xparse" value="0" '.$cky.' />
                  <label class="form-check-label" for="xparse_fix">FixQuotes</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xparse_str" name="xparse" value="1" '.$ckn.' />
                  <label class="form-check-label" for="xparse_str">StripSlashes</label>
               </div>
            </div>
            <input type="hidden" name="xgzhandler" value="0" />
            <div class="col-md-6 mb-3">
               <div class="mb-1" for="xfilemanager">FileManager</div>';
   $cky='';$ckn='';
   if ($filemanager==1) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo '
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xfilemanager_y" name="xfilemanager" value="1" '.$cky.' />
                  <label class="form-check-label" for="xfilemanager_y">'.adm_translate("Oui").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xfilemanager_n" name="xfilemanager" value="0" '.$ckn.' />
                  <label class="form-check-label" for="xfilemanager_n">'.adm_translate("Non").'</label>
               </div>
            </div>
         </div>
         <div class="row gy-0 gx-3">
            <div class="col-md-6">
               <div class="form-floating mb-3">
                  <input class="form-control js-dig10" id="xadmin_cook_duration" type="text" name="xadmin_cook_duration" value="'.$admin_cook_duration.'" min="1" maxlength="10" required="required" />
                  <label for="xadmin_cook_duration">'.adm_translate("Durée de vie en heure du cookie Admin").'<span class="text-danger"> *</span></label>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-floating mb-3">
                  <input class="form-control js-dig10" id="xuser_cook_duration" type="text" name="xuser_cook_duration" value="'.$user_cook_duration.'" min="1" maxlength="10" required="required" />
                  <label for="xuser_cook_duration">'.adm_translate("Durée de vie en heure du cookie User").'<span class="text-danger"> *</span></label>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-floating mb-3">
                  <input class="form-control" type="text" name="xsitename" id="xsitename" value="'.$sitename.'" maxlength="100" />
                  <label for="xsitename">'.adm_translate("Nom du site").'</label>
                  <span class="help-block text-end" id="countcar_xsitename"></span>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-floating mb-3">
                  <input class="form-control" type="text" name="xTitlesitename" id="xTitlesitename" value="'.$Titlesitename.'" maxlength="100" />
                  <label for="xTitlesitename">'.adm_translate("Nom du site pour la balise title").'</label>
                  <span class="help-block text-end" id="countcar_xTitlesitename"></span>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-floating mb-3">
                  <input class="form-control" type="url" name="xnuke_url" id="xnuke_url" value="'.$nuke_url.'" data-fv-uri___allow-local="true" maxlength="200" />
                  <label for="xnuke_url">'.adm_translate("URL du site").'</label>
                  <span class="help-block text-end" id="countcar_xnuke_url"></span>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-floating mb-3">
                  <input class="form-control" type="text" name="xsite_logo" id="xsite_logo" value="'.$site_logo.'" maxlength="255" />
                  <label for="xsite_logo">'.adm_translate("Logo du site pour les impressions").'</label>
                  <span class="help-block text-end" id="countcar_xsite_logo"></span>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-floating mb-3">
                  <input class="form-control" type="text" name="xslogan" id="xslogan" value="'.$slogan.'" maxlength="100" />
                  <label for="xslogan">'.adm_translate("Slogan du site").'</label> 
                  <span class="help-block text-end" id="countcar_xslogan"></span>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-floating mb-3">
                  <input class="form-control" type="text" name="xstartdate" id="xstartdate" value="'.$startdate.'" maxlength="30" />
                  <label for="xstartdate">'.adm_translate("Date de démarrage du site").'</label> 
                  <span class="help-block text-end" id="countcar_xstartdate"></span>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-floating mb-3">
                  <input class="form-control js-dig4" id="xtop" type="text" name="xtop" value="'.$top.'" min="1" maxlength="4" required="required" />
                  <label for="xtop">'.adm_translate("Nombre d'éléments dans la page top").'<span class="text-danger"> *</span></label> 
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-floating mb-3">
                  <input class="form-control js-dig4" id="xstoryhome" type="text" name="xstoryhome" value="'.$storyhome.'" min="1" maxlength="4" required="required" />
                  <label for="xstoryhome">'.adm_translate("Nombre d'articles en page principale").'<span class="text-danger"> *</span></label>
               </div>
            </div>
            <div class="col-12">
               <div class="form-floating mb-3">
                  <input class="form-control js-dig4" id="xoldnum" type="text" name="xoldnum" value="'.$oldnum.'" min="1" maxlength="4" required="required" />
                  <label for="xoldnum">'.adm_translate("Nombre d'articles dans le bloc des anciens articles").'<span class="text-danger"> *</span></label>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-floating mb-3">
                  <input class="form-control" id="xanonymous" type="text" name="xanonymous" value="'.$anonymous.'" maxlength="25" />
                  <label for="xanonymous">'.adm_translate("Nom d'utilisateur anonyme").'</label>
                  <span class="help-block text-end" id="countcar_xanonymous"></span>
               </div>
            </div>
         </div>
         <div class="mb-3">
            <div class="mb-1" for="xmod_admin_news">'.adm_translate("Autoriser la création de news pour").'</div>
               <div class="form-check form-check-inline">';
   if ($mod_admin_news==1) {
      echo '
                  <input type="radio" class="form-check-input" id="xmod_admin_news_a" name="xmod_admin_news" value="1" checked="checked" />
                  <label class="form-check-label" for="xmod_admin_news_a">'.adm_translate("Administrateurs").' / '.adm_translate("Modérateurs").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xmod_admin_news_m" name="xmod_admin_news" value="2" />
                  <label class="form-check-label" for="xmod_admin_news_m">'.adm_translate("Membres").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xmod_admin_news_t" name="xmod_admin_news" value="0" />
                  <label class="form-check-label" for="xmod_admin_news_t">'.adm_translate("Tous").'</label>';
   } elseif ($mod_admin_news==2) {
      echo '
                  <input type="radio" class="form-check-input" id="xmod_admin_news_a" name="xmod_admin_news" value="1" />
                  <label class="form-check-label" for="xmod_admin_news_a">'.adm_translate("Administrateurs").' / '.adm_translate("Modérateurs").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xmod_admin_news_m" name="xmod_admin_news" value="2" checked="checked" />
                  <label class="form-check-label" for="xmod_admin_news_m">'.adm_translate("Membres").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xmod_admin_news_t" name="xmod_admin_news" value="0" />
                  <label class="form-check-label" for="xmod_admin_news_t">'.adm_translate("Tous").'</label>';
   } else {
      echo '
                  <input type="radio" class="form-check-input" id="xmod_admin_news_a" name="xmod_admin_news" value="1" />
                  <label class="form-check-label" for="xmod_admin_news_a">'.adm_translate("Administrateurs").' / '.adm_translate("Modérateurs").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xmod_admin_news_m" name="xmod_admin_news" value="2" />
                  <label class="form-check-label" for="xmod_admin_news_m">'.adm_translate("Membres").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xmod_admin_news_t" name="xmod_admin_news" value="0" checked="checked" />
                  <label class="form-check-label" for="xmod_admin_news_t">'.adm_translate("Tous").'</label>';
   }
   echo '
            </div>
         </div>
         <div class="mb-3">
            <div class="mb-1" for="xnot_admin_count">'.adm_translate("Ne pas enregistrer les 'hits' des auteurs dans les statistiques").'</div>';
   $cky='';$ckn='';
   if ($not_admin_count==1) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo '
            <div class="form-check form-check-inline">
               <input type="radio" class="form-check-input" id="xnot_admin_count_y" name="xnot_admin_count" value="1" '.$cky.' />
               <label class="form-check-label" for="xnot_admin_count_y">'.adm_translate("Oui").'</label>
            </div>
            <div class="form-check form-check-inline">
               <input type="radio" class="form-check-input" id="xnot_admin_count_n" name="xnot_admin_count" value="0" '.$ckn.' />
               <label class="form-check-label" for="xnot_admin_count_n">'.adm_translate("Non").'</label>
            </div>
         </div>
         <div class="row gy-0 gx-3">
            <div class="col-md-6">
               <div class="form-floating mb-3">
                  <select class="form-select" id="xDefault_Theme" name="xDefault_Theme">';
   include("themes/list.php");
   $themelist = explode(" ", $themelist);
   for ($i=0; $i < sizeof($themelist); $i++) {
      if($themelist[$i]!='') {
         echo '
                     <option value="'.$themelist[$i].'" ';
             if($themelist[$i]==$Default_Theme) echo 'selected="selected"';
             echo '>'.$themelist[$i].'</option>';
      }
   }
   echo '
                  </select>
                  <label for="xDefault_Theme">'.adm_translate("Thème d'affichage par défaut").'</label>
               </div>
            </div>
         <div class="col-md-6">
            <div class="form-floating mb-3" id="skin_choice">
               <select class="form-select" id="xDefault_Skin" name="xDefault_Skin">';
      // les skins disponibles
      $handle=opendir('themes/_skins');
       while (false!==($file = readdir($handle))) {
         if ( ($file[0]!=='_') and (!strstr($file,'.')) and (!strstr($file,'assets')) and (!strstr($file,'fonts')) ) {
            $skins[] = array('name'=> $file, 'description'=> '', 'thumbnail'=> $file.'/thumbnail','preview'=> $file.'/','css'=> $file.'/bootstrap.css','cssMin'=> $file.'/bootstrap.min.css','cssxtra'=> $file.'/extra.css','scss'=> $file.'/_bootswatch.scss','scssVariables'=> $file.'/_variables.scss');
         }
      }
      closedir($handle);
       if (!isset($Default_Skin)) $Default_Skin="";
      asort($skins);
      foreach ($skins as $k => $v) {
         echo '
                  <option value="'.$skins[$k]['name'].'" ';
         if ($skins[$k]['name'] == $Default_Skin) echo 'selected="selected"';
         else if($Default_Skin=='' and $skins[$k]['name'] == 'default') echo 'selected="selected"';
         echo '>'.$skins[$k]['name'].'</option>';
      }
    echo '
               </select>
               <label for="xDefault_Skin">'.adm_translate("Skin d'affichage par défaut").'</label>
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-floating mb-3">
               <input class="form-control" type="text" name="xstart_page" id="xstart_page" value="'.$Start_Page.'" maxlength="100" />
               <label for="xstart_page">'.adm_translate("Page de démarrage").'</label>
               <span class="help-block text-end" id="countcar_xstart_page"></span>
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-floating mb-3">
               <select class="form-select" id="xlanguage" name="xlanguage">';
    include("manuels/list.php");
    // var_dump($language);// ici la valeur de la variable est celle choisi par l'utilisateur 
    $languageslist = explode(' ', $languageslist);
    $nb_language = sizeof($languageslist) ;
    for ($i=0; $i < $nb_language ; $i++) {
        if ($languageslist[$i]!='') {
           echo '
                  <option value="'.$languageslist[$i].'" ';
           if ($languageslist[$i]==$notmodifiedlangue) echo 'selected="selected"';
              echo '>'.$languageslist[$i].'</option>';
        }
    }
    echo '
               </select>
               <label for="xlanguage">'.adm_translate("Sélectionner la langue du site").'</label>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="xmulti_langue">'.adm_translate("Activer le multi-langue").'</label>
            <div class="col-sm-8 my-2">';
   $cky='';$ckn='';
   if ($multi_langue==true) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo '
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xmulti_langue_y" name="xmulti_langue" value="true" '.$cky.' />
                  <label class="form-check-label" for="xmulti_langue_y">'.adm_translate("Oui").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xmulti_langue_n" name="xmulti_langue" value="false" '.$ckn.' />
                  <label class="form-check-label" for="xmulti_langue_n">'.adm_translate("Non").'</label>
               </div>
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-floating mb-3">
               <input class="form-control" type="text" name="xlocale" id="xlocale" value="'.$locale.'" maxlength="100" />
               <label for="xlocale">'.adm_translate("Heure locale").'</label>
               <span class="help-block text-end" id="countcar_xlocale"></span>
            </div>
         </div>';
      if ($lever=='') $lever='08:00';
      if ($coucher=='') $coucher='20:00';
      echo '
         <div class="col-md-6">
            <div class="form-floating mb-3">
               <input class="form-control js-hhmm" type="text" name="xlever" id="xlever" value="'.$lever.'" maxlength="5" required="required" />
               <label for="xlever">'.adm_translate("Le jour commence à").'</label>
               <span class="help-block">(HH:MM)<span class="float-end ms-1" id="countcar_xlever"></span></span>
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-floating mb-3">
               <input class="form-control js-hhmm" type="text" name="xcoucher" id="xcoucher" value="'.$coucher.'" maxlength="5" required="required" />
               <label for="xcoucher">'.adm_translate("La nuit commence à").'</label>
               <span class="help-block">(HH:MM)<span class="float-end ms-1" id="countcar_xcoucher"></span></span>
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-floating mb-3">
               <select class="form-select" id="xgmt" name="xgmt">
                  <option value="'.$gmt.'" selected="selected">'.$gmt.'</option>
                  <option value="-1">UTC-01:00</option>
                  <option value="-2">UTC-02:00</option>
                  <option value="-3">UTC-03:00</option>
                  <option value="-3.5">UTC-03:30</option>
                  <option value="-4">UTC-04:00</option>
                  <option value="-5">UTC-05:00</option>
                  <option value="-6">UTC-06:00</option>
                  <option value="-7">UTC-07:00</option>
                  <option value="-8">UTC-08:00</option>
                  <option value="-9">UTC-09:00</option>
                  <option value="-9.5">UTC-09:30</option>
                  <option value="-10">UTC-10:00</option>
                  <option value="-11">UTC-11:00</option>
                  <option value="-12">UTC-12:00</option>
                  <option value="0">UTC±00:00</option>
                  <option value="+1">UTC+01:00</option>
                  <option value="+2">UTC+02:00</option>
                  <option value="+3">UTC+03:00</option>
                  <option value="+3.5">UTC+03:30</option>
                  <option value="+4">UTC+04:00</option>
                  <option value="+4.5">UTC+04:30</option>
                  <option value="+5">UTC+05:00</option>
                  <option value="+5.5">UTC+05:30</option>
                  <option value="+5.75">UTC+05:45</option>
                  <option value="+6">UTC+06:00</option>
                  <option value="+6.5">UTC+06:30</option>
                  <option value="+7">UTC+07:00</option>
                  <option value="+8">UTC+08:00</option>
                  <option value="+8.75">UTC+08:45</option>
                  <option value="+9">UTC+09:00</option>
                  <option value="+9.5">UTC+09:30</option>
                  <option value="+10">UTC+10:00</option>
                  <option value="+10.5">UTC+10:30</option>
                  <option value="+11">UTC+11:00</option>
                  <option value="+12">UTC+12:00</option>
                  <option value="+12.75">UTC+12:45</option>
                  <option value="+13">UTC+13:00</option>
                  <option value="+14">UTC+14:00</option>
               </select>
               <label for="xgmt">UTC</label>
            </div>
         </div>
      </div>
      <script type="text/javascript">
      //<![CDATA[
      tog(\'info_gene\',\'show_info_gene\',\'hide_info_gene\');
      $(function () {
         $("#xDefault_Theme").change(function () {
            sk = $("#xDefault_Theme option:selected").text().substr(-3);
            if(sk=="_sk") {
               $("#skin_choice").removeClass("collapse");
            } else {
               $("#skin_choice").addClass("collapse");
            }
         })
        .change();
       });
      //]]>
      </script>
   </fieldset>
   <fieldset>
   <legend><a class="tog" id="show_banner" title="'.adm_translate("Replier la liste").'"><i id="i_banner" class="fa fa-caret-down fa-lg text-primary" ></i></a>&nbsp;'.adm_translate("Options pour les Bannières").'</legend>
      <div id="banner" class="adminsidefield card card-body mb-3" style="display:none;">
         <div class="mb-3 row">
            <label class="col-form-label col-sm-8" for="xbanners">'.adm_translate("Options pour les Bannières").'</label>
            <div class="col-sm-4">';
   $cky='';$ckn='';
   if ($banners==1) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo '
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xbanners_y" name="xbanners" value="1" '.$cky.' />
                  <label class="form-check-label" for="xbanners_y">'.adm_translate("Oui").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xbanners_n" name="xbanners" value="0" '.$ckn.' />
                  <label class="form-check-label" for="xbanners_n">'.adm_translate("Non").'</label>
               </div>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-8" for="xbanners">'.adm_translate("Votre adresse IP (= ne pas comptabiliser les hits qui en proviennent)").'</label>
            <div class="col-sm-4">
               <input class="form-control" type="text" name="xmyIP" id="xmyIP" value="'.$myIP.'" />
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
   <legend><a class="tog" id="show_mes_ppage" title="'.adm_translate("Replier la liste").'"><i id="i_mes_ppage" class="fa fa-caret-down fa-lg text-primary" ></i></a>&nbsp;'.adm_translate("Message de pied de page").'</legend>
      <div id="mes_ppage" class="adminsidefield card card-body mb-3" style="display:none;">
         <div class="form-floating mb-3">
            <textarea class="form-control" id="xfoot1" name="xfoot1" style="height:100px;">'.htmlentities(stripslashes($foot1),ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8').'</textarea>
            <label for="xfoot1">'.adm_translate("Ligne 1").'</label>
         </div>
         <div class="form-floating mb-3">
            <textarea class="form-control" id="xfoot2" name="xfoot2" style="height:100px;">'.htmlentities(stripslashes($foot2),ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8').'</textarea>
            <label for="xfoot2">'.adm_translate("Ligne 2").'</label>
         </div>
         <div class="form-floating mb-3">
            <textarea class="form-control col-sm-12" id="xfoot3" name="xfoot3" style="height:100px;">'.htmlentities(stripslashes($foot3),ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8').'</textarea>
            <label for="xfoot3">'.adm_translate("Ligne 3").'</label>
         </div>
         <div class="form-floating mb-3">
            <textarea class="form-control" id="xfoot4" name="xfoot4" style="height:100px;">'.htmlentities(stripslashes($foot4),ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8').'</textarea>
            <label for="xfoot4">'.adm_translate("Ligne 4").'</label>
         </div>
      </div>
      <script type="text/javascript">
      //<![CDATA[
      tog(\'mes_ppage\',\'show_mes_ppage\',\'hide_mes_ppage\');
      //]]>
      </script>
   </fieldset>
   <fieldset>
   <legend><a class="tog" id="show_bakend_rs" title="'.adm_translate("Replier la liste").'"><i id="i_bakend_rs" class="fa fa-caret-down fa-lg text-primary" ></i></a>&nbsp;'.adm_translate("Configuration des infos en Backend & Réseaux Sociaux").'</legend>
      <div id="bakend_rs" class="adminsidefield card card-body mb-3" style="display:none;">
         <div class="row gy-0 gx-3">
            <div class="col-md-6">
               <div class="form-floating mb-3">
                  <input class="form-control" type="text" name="xbackend_title" id="xbackend_title" value="'.$backend_title.'" maxlength="100" />
                  <label for="xbackend_title">'.adm_translate("Titre du backend").'</label>
                  <span class="help-block text-end" id="countcar_xbackend_title"></span>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-floating mb-3">
                  <input class="form-control" type="text" name="xbackend_language" id="xbackend_language" value="'.$backend_language.'" maxlength="10" />
                  <label for="xbackend_language">'.adm_translate("Langue du backend").'</label>
                  <span class="help-block text-end" id="countcar_xbackend_language"></span>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-floating mb-3">
                  <input class="form-control" type="url" name="xbackend_image" id="xbackend_image" value="'.$backend_image.'" maxlength="200" />
                  <label for="xbackend_image">'.adm_translate("URL de l'image du backend").'</label>
                  <span class="help-block text-end" id="countcar_xbackend_image"></span>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-floating mb-3">
                  <input class="form-control" type="number" id="xbackend_width" name="xbackend_width" value="'.$backend_width.'" min="0" max="9999" />
                  <label for="xbackend_width">'.adm_translate("Largeur de l'image du backend").'</label>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-floating mb-3">
                  <input class="form-control" type="number" id="xbackend_height" name="xbackend_height" value="'.$backend_height.'" min="0" max="9999" />
                  <label for="xbackend_height">'.adm_translate("Hauteur de l'image du backend").'</label>
               </div>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="xultramode">'.adm_translate("Activer export-news").'</label>
            <div class="col-sm-8 my-2">';
   $cky='';$ckn='';
   if ($ultramode==1) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo '
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xultramode_y" name="xultramode" value="1" '.$cky.' />
                  <label class="form-check-label" for="xultramode_y">'.adm_translate("Oui").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xultramode_n" name="xultramode" value="0" '.$ckn.'/>
                  <label class="form-check-label" for="xultramode_n">'.adm_translate("Non").'</label>
               </div>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="xnpds_twi">'.adm_translate("Activer Twitter").'</label>
            <div class="col-sm-8 my-2">';
   $cky='';$ckn='';
   if ($npds_twi==1) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo '
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xnpds_twi_y" name="xnpds_twi" value="1" '.$cky.' />
                  <label class="form-check-label" for="xnpds_twi_y">'.adm_translate("Oui").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xnpds_twi_n" name="xnpds_twi" value="0" '.$ckn.' />
                  <label class="form-check-label" for="xnpds_twi_n">'.adm_translate("Non").'</label>
               </div>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="xnpds_fcb">'.adm_translate("Activer Facebook").'</label>
            <div class="col-sm-8 my-2">';
   $cky='';$ckn='';
   if ($npds_fcb==1) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo '
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xnpds_fcb_y" name="xnpds_fcb" value="1" '.$cky.' />
                  <label class="form-check-label" for="xnpds_fcb_y">'.adm_translate("Oui").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xnpds_fcb_n" name="xnpds_fcb" value="0" '.$ckn.' />
                  <label class="form-check-label" for="xnpds_fcb_n">'.adm_translate("Non").'</label>
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
   <legend><a class="tog" id="show_lien_web" title="'.adm_translate("Replier la liste").'"><i id="i_lien_web" class="fa fa-caret-down fa-lg text-primary" ></i></a>&nbsp;'.adm_translate("Configuration par défaut des Liens Web").'</legend>
      <div id="lien_web" class="adminsidefield card card-body mb-3" style="display:none;">
         <div class="mb-3 row">
            <label class="col-form-label col-sm-8" for="xperpage">'.adm_translate("Nombre de liens par page").'</label>
            <div class="col-sm-4">
               <select class="form-select" id="xperpage" name="xperpage">
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
         <div class="mb-3 row">
            <label class="col-form-label col-sm-8" for="xpopular">'.adm_translate("Nombre de clics sur un lien pour qu'il soit populaire").'</label>
            <div class="col-sm-4">
               <select class="form-select" id="xpopular" name="xpopular">
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
         <div class="mb-3 row">
            <label class="col-form-label col-sm-8" for="xnewlinks">'.adm_translate("Nombre de Liens 'Nouveaux'").'</label>
            <div class="col-sm-4">
               <select class="form-select" id="xnewlinks" name="xnewlinks">
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
         <div class="mb-3 row">
            <label class="col-form-label col-sm-8" for="xtoplinks">'.adm_translate("Nombre de Liens 'Meilleur'").'</label>
            <div class="col-sm-4">
               <select class="form-select" id="xtoplinks" name="xtoplinks">
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
         <div class="mb-3 row">
            <label class="col-form-label col-sm-8" for="xlinksresults">'.adm_translate("Nombre de liens dans les résultats des recherches").'</label>
            <div class="col-sm-4">
               <select class="form-select" id="xlinksresults" name="xlinksresults">
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
         <div class="mb-3 row">
            <label class="col-form-label col-sm-8" for="xlinks_anonaddlinklock">'.adm_translate("Laisser les utilisateurs anonymes poster de nouveaux liens").'</label>
            <div class="col-sm-4 my-2">';
   $cky='';$ckn='';
   if ($links_anonaddlinklock==0) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo '
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xlinks_anonaddlinklock_y" name="xlinks_anonaddlinklock" value="0" '.$cky.' />
                  <label class="form-check-label" for="xlinks_anonaddlinklock_y">'.adm_translate("Oui").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xlinks_anonaddlinklock_n" name="xlinks_anonaddlinklock" value="1" '.$ckn.'/>
                  <label class="form-check-label" for="xlinks_anonaddlinklock_n">'.adm_translate("Non").'</label>
               </div>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-8" for="xlinkmainlogo">'.adm_translate("Afficher le logo sur la page web links").'</label>
            <div class="col-sm-4 my-2">';
   $cky='';$ckn='';
   if ($linkmainlogo==1) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo '
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xlinkmainlogo_y" name="xlinkmainlogo" value="1" '.$cky.' />
                  <label class="form-check-label" for="xlinkmainlogo_y">'.adm_translate("Oui").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xlinkmainlogo_n" name="xlinkmainlogo" value="0" '.$ckn.' />
                  <label class="form-check-label" for="xlinkmainlogo_n">'.adm_translate("Non").'</label>
               </div>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-8" for="xOnCatNewLink">'.adm_translate("Activer l'icône [N]ouveau pour les catégories").'</label>
            <div class="col-sm-4 my-2">';
   $cky='';$ckn='';
   if ($OnCatNewLink==1) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo '
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xOnCatNewLink_y" name="xOnCatNewLink" value="1" '.$cky.' />
                  <label class="form-check-label" for="xOnCatNewLink_y">'.adm_translate("Oui").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xOnCatNewLink_n" name="xOnCatNewLink" value="0" '.$ckn.' />
                  <label class="form-check-label" for="xOnCatNewLink_n">'.adm_translate("Non").'</label>
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
   <legend><a class="tog" id="show_sys_mes" title="'.adm_translate("Replier la liste").'"><i id="i_sys_mes" class="fa fa-caret-down fa-lg text-primary" ></i></a>&nbsp;'.adm_translate("Système de Messagerie (Email)").'</legend>
      <div id="sys_mes" class="adminsidefield card card-body mb-3" style="display:none;">
         <div class="form-floating mb-3">
            <input class="form-control" type="email" name="xadminmail" id="xadminmail" value="'.$adminmail.'" maxlength="254" required="required" />
            <label for="xadminmail">'.adm_translate("Adresse E-mail de l'administrateur").'</label> 
            <span class="help-block text-end">'.adm_translate("Adresse E-mail valide, autorisée et associée au serveur d'envoi.").'<span id="countcar_xadminmail float-end"></span></span>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="xmail_fonction">'.adm_translate("Utiliser SMTP(S)").'</label>
            <div class="col-sm-8 my-2">';
   $cky='';$ckn='';
   if (!$mail_fonction) $mail_fonction=1;
   if ($mail_fonction==1) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo '
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xmail_fonction1" name="xmail_fonction" value="1" '.$cky.' />
                  <label class="form-check-label" for="xmail_fonction1">'.adm_translate("Non").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xmail_fonction2" name="xmail_fonction" value="2" '.$ckn.' />
                  <label class="form-check-label" for="xmail_fonction2">'.adm_translate("Oui").'</label>
               </div>
            </div>
         </div>';
   include "lib/PHPMailer/PHPmailer.conf.php";
   echo '
         <div id="smtp" class="row">
            <div class="form-label my-3">'.adm_translate("Configuration de PHPmailer SMTP(S)").'</div>
            <div class="mb-3 row">
               <div class="col-md-6">
                  <div class="form-floating mb-3">
                     <input class="form-control" type="text" name="xsmtp_host" id="xsmtp_host" value="'.$smtp_host.'" maxlength="100" required="required" />
                     <label for="xsmtp_host">'.adm_translate("Nom du serveur").'</label>
                     <span class="help-block text-end" id="countcar_xsmtp_host"></span>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-floating mb-3">
                     <input class="form-control" type="text" name="xsmtp_port" id="xsmtp_port" value="'.$smtp_port.'" maxlength="4" required="required" />
                     <label for="xsmtp_port">'.adm_translate("Port TCP").'</label>
                     <span class="help-block text-end">'.adm_translate("Utiliser 587 si vous avez activé le chiffrement TLS").'.<span class="float-end ms-1" id="countcar_xsmtp_port"></span></span>
                  </div>
               </div>
            </div>';
    $smtpaky = '';
    $smtpakn = '';
    if ($smtp_auth == 1) {
        $smtpaky = 'checked="checked"';
        $smtpakn = '';
    } else {
        $smtpaky = '';
        $smtpakn = 'checked="checked"';
    }

    echo '
            <div class="mb-3 row">
               <label class="col-form-label col-sm-6" for="xsmtp_auth">' . adm_translate("Activer l'authentification SMTP(S)") . '</label>
               <div class="col-sm-6 my-2">
                  <div class="form-check form-check-inline">
                     <input type="radio" class="form-check-input" id="xsmtp_auth_y" name="xsmtp_auth" value="1" ' . $smtpaky . ' />
                     <label class="form-check-label" for="xsmtp_auth_y">' . adm_translate("Oui") . '</label>
                  </div>
                  <div class="form-check form-check-inline">
                     <input type="radio" class="form-check-input" id="xsmtp_auth_n" name="xsmtp_auth" value="0" ' . $smtpakn . ' />
                     <label class="form-check-label" for="xsmtp_auth_n">' . adm_translate("Non") . '</label>
                  </div>
               </div>
            </div>
            <div id="auth" class="row">
               <div class="col-md-6">
                  <div class="form-floating mb-3">
                     <input class="form-control" type="text" name="xsmtp_username" id="xsmtp_username" value="' . $smtp_username . '" maxlength="100" required="required" />
                     <label for="xsmtp_username">' . adm_translate("Nom d'utilisateur") . '</label>
                     <span class="help-block text-end" id="countcar_xsmtp_username"></span>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-floating mb-3">
                     <input class="form-control" type="password" name="xsmtp_password" id="xsmtp_password" value="' . $smtp_password . '" maxlength="100" required="required" />
                     <label for="xsmtp_password">' . adm_translate("Mot de passe") . '</label>
                     <span class="help-block text-end" id="countcar_xsmtp_password"></span>
                  </div>
               </div>
            </div>';
   $smtpsky = '';
   $smtpskn = '';
   if ($smtp_secure == 1) {
      $smtpsky = 'checked="checked"';
      $smtpskn = '';
   } else {
      $smtpsky = '';
      $smtpskn = 'checked="checked"';
   }
   echo '
            <div class="mb-3 row">
               <div class="col-md-6 my-auto">
                  <label class="form-label me-4" for="xsmtp_secure">'.adm_translate("Activer le chiffrement").'</label>
                  <div class="form-check form-check-inline">
                     <input type="radio" class="form-check-input" id="xsmtp_secure_y" name="xsmtp_secure" value="1" ' . $smtpsky . ' />
                     <label class="form-check-label" for="xsmtp_secure_y">'.adm_translate("Oui").'</label>
                  </div>
                  <div class="form-check form-check-inline">
                     <input type="radio" class="form-check-input" id="xsmtp_secure_n" name="xsmtp_secure" value="0" ' . $smtpskn . ' />
                     <label class="form-check-label" for="xsmtp_secure_n">'.adm_translate("Non").'</label>
                  </div>
               </div>
               <div class="col-md-6" id="chifr">
                  <div class="form-floating mb-3">
                     <select class="form-select" id="xsmtp_crypt" name="xsmtp_crypt">
                        <option  value="'.$smtp_crypt.'" selected="selected">'.strtoupper($smtp_crypt).'</option>
                        <option value="tls">TLS</option>
                        <option value="ssl">SSL</option>
                     </select>
                     <label for="xsmtp_crypt">'.adm_translate("Protocole de chiffrement").'</label>
                  </div>
               </div>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="xdkim_auto">DKIM</label>
            <div class="col-sm-8 my-2">';
   $cky='';$ckn='';
   if (!$dkim_auto) $dkim_auto=1;
   if ($dkim_auto==1) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}

   echo '
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="dkim1" name="xdkim_auto" value="1" '.$cky.' />
                  <label class="form-check-label" for="dkim1">'.adm_translate("Du Dns").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="dkim2" name="xdkim_auto" value="2" '.$ckn.' />
                  <label class="form-check-label" for="dkim2">'.adm_translate("Automatique").'</label>
               </div>
               <span class="help-block">'.adm_translate("Du DNS").' ==> '.adm_translate("DKIM du DNS (si existant et valide).").'<br />'.adm_translate("Automatique").' ==> '.adm_translate("génération automatique du DKIM par le portail.").'</span>
            </div>
         </div>';

   // Footer of Email send by NPDS
   settype($message,'string');
   include "signat.php";
    echo '
        <div class="mb-3 row">
            <label class="col-form-label col-sm-12" for="xEmailFooter">'.adm_translate("Pied").' '.adm_translate("de").' Email</label> 
            <div class="col-sm-12">
               <textarea class="form-control" id="xEmailFooter" name="xEmailFooter" cols="45" rows="8">'.$message.'</textarea>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="xnotify">'.adm_translate("Notifier les nouvelles contributions par E-mail").'</label>
            <div class="col-sm-8 my-2">';
   $cky='';$ckn='';
   if ($notify==1) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo '
               <div class="form-check form-check-inline">
                 <input type="radio" class="form-check-input" id="xnotify_y" name="xnotify" value="1" '.$cky.' />
                  <label class="form-check-label" for="xnotify_y">'.adm_translate("Oui").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xnotify_n" name="xnotify" value="0" '.$ckn.' />
                  <label class="form-check-label" for="xnotify_n">'.adm_translate("Non").'</label>
               </div>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="xnotify_email">'.adm_translate("Adresse E-mail où envoyer le message").'</label>
            <div class="col-sm-8">
               <input class="form-control" type="email" name="xnotify_email" id="xnotify_email" value="'.$notify_email.'" maxlength="254" required="required" />
               <span class="help-block text-end" id="countcar_xnotify_email"></span>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="xnotify_subject">'.adm_translate("Sujet de l'E-mail").'</label>
            <div class="col-sm-8">
               <input class="form-control" type="text" name="xnotify_subject" id="xnotify_subject" value="'.$notify_subject.'" maxlength="100" required="required" />
               <span class="help-block text-end" id="countcar_xnotify_subject"></span>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="xnotify_message">'.adm_translate("Message de l'E-mail").'</label>
            <div class="col-sm-8">
               <textarea class="form-control" id="xnotify_message" name="xnotify_message" rows="8">'.$notify_message.'</textarea>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="xnotify_from">'.adm_translate("Compte E-mail (Provenance)").'</label>
            <div class="col-sm-8">
               <input class="form-control" type="email" name="xnotify_from" id="xnotify_from" value="'.$notify_from.'" maxlength="100" required="required" />
               <span class="help-block text-end">'.adm_translate("Adresse E-mail valide, autorisée et associée au serveur d'envoi.").' <span id="countcar_xnotify_from"></span></span>
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
   <legend><a class="tog" id="show_opt_comment" title="'.adm_translate("Replier la liste").'"><i id="i_opt_comment" class="fa fa-caret-down fa-lg text-primary" ></i></a>&nbsp;'.adm_translate("Options pour les Commentaires").'</legend>
      <div id="opt_comment" class="adminsidefield card card-body mb-3" style="display:none;">
         <div class="mb-3 row">
            <label class="col-form-label col-sm-7" for="xmoderate">'.adm_translate("Type de modération").'</label>
            <div class="col-sm-5">
               <select class="form-select" id="xmoderate" name="xmoderate">';
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
         <div class="mb-3 row">
            <label class="col-form-label col-sm-7" for="xanonpost">'.adm_translate("Autoriser les commentaires anonymes").'</label>
            <div class="col-sm-5 my-2">';
   $cky='';$ckn='';
   if ($anonpost==1) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo '
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xanonpost_y" name="xanonpost" value="1" '.$cky.' />
                  <label class="form-check-label" for="xanonpost_y">'.adm_translate("Oui").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xanonpost_n" name="xanonpost" value="0" '.$ckn.' />
                  <label class="form-check-label" for="xanonpost_n">'.adm_translate("Non").'</label>
               </div>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-7" for="xtroll_limit">'.adm_translate("Nombre maximum de commentaire par utilisateur en 24H").'</label>
            <div class="col-sm-5">';
   if ($troll_limit=='') $troll_limit="6";
   echo '
               <input class="form-control" id="xtroll_limit" type="text" name="xtroll_limit" value="'.$troll_limit.'" min="1" maxlength="3" required="required" />
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
   <legend><a class="tog" id="show_opt_sond" title="'.adm_translate("Replier la liste").'"><i id="i_opt_sond" class="fa fa-caret-down fa-lg text-primary" ></i></a>&nbsp;'.adm_translate("Options des sondages").'</legend>
      <div id="opt_sond" class="adminsidefield card card-body mb-3" style="display:none;">
         <div class="mb-3 row">
            <label class="col-form-label col-sm-8" for="xmaxOptions">'.adm_translate("Nombre maximum de choix pour les sondages").'</label>
            <div class="col-sm-4">
               <select class="form-select" id="xmaxOptions" name="xmaxOptions">
                  <option value="'.$maxOptions.'">'.$maxOptions.'</option>
                  <option value="10">10</option>
                  <option value="12">12</option>
                  <option value="15">15</option>
                  <option value="20">20</option>
               </select>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-8" for="xsetCookies">'.adm_translate("Autoriser les utilisateurs à voter plusieurs fois").'</label>
            <div class="col-sm-4 my-2">';
   $cky='';$ckn='';//???? valeur inversé ???
   if ($setCookies==0) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo '
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xsetCookies_y" name="xsetCookies" value="0" '.$cky.' />
                  <label class="form-check-label" for="xsetCookies_y">'.adm_translate("Oui").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xsetCookies_n" name="xsetCookies" value="1" '.$ckn.'/>
                  <label class="form-check-label" for="xsetCookies_n">'.adm_translate("Non").'</label>
               </div>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-8" for="xpollcomm">'.adm_translate("Activer les commentaires des sondages").'</label>
            <div class="col-sm-4 my-2">';
   $cky='';$ckn='';
   if ($pollcomm==1) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo '
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xpollcomm_y" name="xpollcomm" value="1" '.$cky.' />
                  <label class="form-check-label" for="xpollcomm_y">'.adm_translate("Oui").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xpollcomm_n" name="xpollcomm" value="0" '.$ckn.' />
                  <label class="form-check-label" for="xpollcomm_n" >'.adm_translate("Non").'</label>
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
   <legend><a class="tog" id="show_para_illu" title="'.adm_translate("Replier la liste").'"><i id="i_para_illu" class="fa fa-caret-down fa-lg text-primary" ></i></a>&nbsp;'.adm_translate("Paramètres liés à l'illustration").'</legend>
      <div id="para_illu" class="adminsidefield card card-body mb-3" style="display:none;">
         <div class="row">
            <div class="col-lg-6">
               <div class="form-floating mb-3">
                  <input class="form-control" type="text" name="xtipath" id="xtipath" value="'.$tipath.'" maxlength="100" />
                  <label for="xtipath">'.adm_translate("Chemin des images des sujets").'</label>
                  <span class="help-block text-end" id="countcar_xtipath"></span>
               </div>
            </div>
            <div class="col-lg-6">
               <div class="form-floating mb-3">
                  <input class="form-control" type="text" name="xuserimg" id="xuserimg" value="'.$userimg.'" maxlength="100" />
                  <label for="xuserimg">'.adm_translate("Chemin de certaines images (vote, ...)").'</label>
                  <span class="help-block text-end" id="countcar_xuserimg"></span>
               </div>
            </div>
            <div class="col-lg-6">
               <div class="form-floating mb-3">
                  <input class="form-control" type="text" name="xadminimg" id="xadminimg" value="'.$adminimg.'" maxlength="100" />
                  <label for="xadminimg">'.adm_translate("Chemin des images du menu administrateur").'</label>
                  <span class="help-block text-end" id="countcar_xadminimg"></span>
               </div>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="xadmingraphic">'.adm_translate("Activer les images dans le menu administration").'</label>
            <div class="col-sm-8 my-2">';
   $cky='';$ckn='';
   if ($admingraphic==1) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo '
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xadmingraphic_y" name="xadmingraphic" value="1" '.$cky.' />
                  <label class="form-check-label" for="xadmingraphic_y">'.adm_translate("Oui").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xadmingraphic_n" name="xadmingraphic" value="0" '.$ckn.' />
                  <label class="form-check-label" for="xadmingraphic_n">'.adm_translate("Non").'</label>
               </div>
            </div>
         </div>';
   if (!$admf_ext) $admf_ext="gif";
   echo '
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="xadmf_ext">'.adm_translate("Extension des fichiers d'image").'</label>
            <div class="col-sm-8">
               <input class="form-control" type="text" name="xadmf_ext" id="xadmf_ext" value="'.$admf_ext.'" maxlength="3" />
               <span class="help-block text-end" id="countcar_xadmf_ext"></span>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="xshort_menu_admin">'.adm_translate("Activer les menus courts pour l'administration").'</label>
            <div class="col-sm-8 my-2">';
   $cky='';$ckn='';
   if ($short_menu_admin==1) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo '
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xshort_menu_admin_y" name="xshort_menu_admin" value="1" '.$cky.' />
                  <label class="form-check-label" for="xshort_menu_admin_y">'.adm_translate("Oui").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xshort_menu_admin_n" name="xshort_menu_admin" value="0" '.$ckn.' />
                  <label class="form-check-label" for="xshort_menu_admin_n">'.adm_translate("Non").'</label>
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
   <legend><a class="tog" id="show_divers" title="'.adm_translate("Replier la liste").'"><i id="i_divers" class="fa fa-caret-down fa-lg text-primary" ></i></a>&nbsp;'.adm_translate("Divers").'</legend>
      <div id="divers" class="adminsidefield card card-body mb-3" style="display:none;">
         <div class="mb-3 row">
            <label class="col-form-label col-sm-8" for="xadmart">'.adm_translate("Nombres d'articles en mode administration").'</label>
            <div class="col-sm-4">
               <select class="form-select" id="xadmart" name="xadmart">
                  <option value="'.$admart.'">'.$admart.'</option>
                  <option value="10">10</option>
                  <option value="15">15</option>
                  <option value="20">20</option>
                  <option value="25">25</option>
                  <option value="30">30</option>
               </select>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-8" for="xminpass">'.adm_translate("Longueur minimum du mot de passe des utilisateurs").'</label>
            <div class="col-sm-4">
               <select class="form-select" id="xminpass" name="xminpass">
                  <option value="'.$minpass.'">'.$minpass.'</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="8">8</option>
                  <option value="10">10</option>
               </select>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-8" for="xshow_user">'.adm_translate("Nombre d'utilisateurs listés").'</label>
            <div class="col-sm-4">
               <select class="form-select" id="xshow_user" name="xshow_user">
                  <option value="'.$show_user.'">'.$show_user.'</option>
                  <option value="10">10</option>
                  <option value="20">20</option>
                  <option value="30">30</option>
                  <option value="40">40</option>
               </select>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-8" for="xsmilies">'.adm_translate("Activer les avatars").'</label>
            <div class="col-sm-4 my-2">';
   $cky='';$ckn='';
   if ($smilies==1) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo '
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xsmilies_y" name="xsmilies" value="1" '.$cky.' />
                  <label class="form-check-label" for="xsmilies_y">'.adm_translate("Oui").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xsmilies_n" name="xsmilies" value="0" '.$ckn.' />
                  <label class="form-check-label" for="xsmilies_n">'.adm_translate("Non").'</label>
               </div>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-8" for="xavatar_size">'.adm_translate("Taille maximum des avatars personnels (largeur * hauteur / 60*80) en pixel").'</label>
            <div class="col-sm-4">';
   if (!$avatar_size) $avatar_size="60*80";
   echo '
               <input class="form-control" type="text" id="xavatar_size" name="xavatar_size" value="'.$avatar_size.'" size="11" maxlength="10" />
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-8" for="xshort_user">'.adm_translate("Activer la description simplifiée des utilisateurs").'</label>
            <div class="col-sm-4 my-2">';
   $cky='';$ckn='';
   if ($short_user==1) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo '
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xshort_user_y" name="xshort_user" value="1" '.$cky.' />
                  <label class="form-check-label" for="xshort_user_y">'.adm_translate("Oui").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xshort_user_n" name="xshort_user" value="0" '.$ckn.' />
                  <label class="form-check-label" for="xshort_user_n">'.adm_translate("Non").'</label>
               </div>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-8" for="xAutoRegUser">'.adm_translate("Autoriser la création automatique des membres").'</label>
            <div class="col-sm-4 my-2">';
   $cky='';$ckn='';
   if (($AutoRegUser=='') and ($AutoRegUser!=0)) $AutoRegUser=1;
   if ($AutoRegUser==1) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo '
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xAutoRegUser_y" name="xAutoRegUser" value="1" '.$cky.' />
                  <label class="form-check-label" for="xAutoRegUser_y">'.adm_translate("Oui").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xAutoRegUser_n" name="xAutoRegUser" value="0" '.$ckn.' />
                  <label class="form-check-label" for="xAutoRegUser_n">'.adm_translate("Non").'</label>
               </div>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-8" for="xmemberpass">'.adm_translate("Autoriser les utilisateurs à choisir leur mot de passe").'</label>
            <div class="col-sm-4 my-2">';
   $cky='';$ckn='';
   if (($memberpass=='') and ($memberpass!=0)) $memberpass=1;
   if ($memberpass==1) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo '
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xmemberpass_y" name="xmemberpass" value="1" '.$cky.' />
                  <label class="form-check-label" for="xmemberpass_y">'.adm_translate("Oui").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xmemberpass_n" name="xmemberpass" value="0" '.$ckn.' />
                  <label class="form-check-label" for="xmemberpass_n">'.adm_translate("Non").'</label>
               </div>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-8" for="xsubscribe">'.adm_translate("Autoriser les abonnements").'</label>
            <div class="col-sm-4 my-2">';
   $cky='';$ckn='';
   if ($subscribe==1) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo '
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xsubscribe_y" name="xsubscribe" value="1" '.$cky.' />
                  <label class="form-check-label" for="xsubscribe_y">'.adm_translate("Oui").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xsubscribe_n" name="xsubscribe" value="0" '.$ckn.' />
                  <label class="form-check-label" for="xsubscribe_n">'.adm_translate("Non").'</label>
               </div>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-8" for="xmember_invisible">'.adm_translate("Autoriser les membres invisibles").'</label>
            <div class="col-sm-4 my-2">';
   $cky='';$ckn='';
   if ($member_invisible==1) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo '
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xmember_invisible_y" name="xmember_invisible" value="1" '.$cky.' />
                  <label class="form-check-label" for="xmember_invisible_y">'.adm_translate("Oui").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xmember_invisible_n" name="xmember_invisible" value="0" '.$ckn.' />
                  <label class="form-check-label" for="xmember_invisible_n">'.adm_translate("Non").'</label>
               </div>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-8" for="xCloseRegUser">'.adm_translate("Fermer les nouvelles inscriptions").'</label>
            <div class="col-sm-4 my-2">';
   if (($CloseRegUser=='') and ($CloseRegUser!=1)) $AutoRegUser=0;// ????????
   $cky='';$ckn='';
   if ($CloseRegUser==1) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo '
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xCloseRegUser_y" name="xCloseRegUser" value="1" '.$cky.' />
                  <label class="form-check-label" for="xCloseRegUser_y">'.adm_translate("Oui").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xCloseRegUser_n" name="xCloseRegUser" value="0" '.$ckn.' />
                  <label class="form-check-label" for="xCloseRegUser_n">'.adm_translate("Non").'</label>
               </div>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-8" for="xhttpref">'.adm_translate("Activer les référants HTTP").'</label>
            <div class="col-sm-4 my-2">';
   $cky='';$ckn='';
   if ($httpref==1) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo'
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xhttpref_y" name="xhttpref" value="1" '.$cky.' />
                  <label class="form-check-label" for="xhttpref_y">'.adm_translate("Oui").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xhttpref_n" name="xhttpref" value="0" '.$ckn.' />
                  <label class="form-check-label" for="xhttpref_n">'.adm_translate("Non").'</label>
               </div>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-8" for="xhttprefmax">'.adm_translate("Combien de référants au maximum").'</label>
            <div class="col-sm-4">
               <select class="form-select" id="xhttprefmax" name="xhttprefmax">
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
         <div class="mb-3 row">
            <label class="col-form-label col-sm-8" for="xmember_list">'.adm_translate("Liste des membres").' : '.adm_translate("Privé").'</label>
            <div class="col-sm-4 my-2">';
   $cky='';$ckn='';
   if ($member_list==1) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo'
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xmember_list_y" name="xmember_list" value="1" '.$cky.' />
                  <label class="form-check-label" for="xmember_list_y">'.adm_translate("Oui").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xmember_list_n" name="xmember_list" value="0" '.$ckn.' />
                  <label class="form-check-label" for="xmember_list_n">'.adm_translate("Non").'</label>
               </div>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-8" for="xdownload_cat">'.adm_translate("Rubrique de téléchargement").'</label>
            <div class="col-sm-4">
               <select class="form-select" id="xdownload_cat" name="xdownload_cat">
                  <option value="'.$download_cat.'">'.aff_langue($download_cat).'</option>';
   $result = sql_query("SELECT distinct dcategory FROM ".$NPDS_Prefix."downloads");
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
         <div class="mb-3 row">
            <label class="col-form-label col-sm-8" for="xshort_review">'.adm_translate("Critiques").' : '.adm_translate("courtes").'</label>
            <div class="col-sm-4 my-2">';
   $cky='';$ckn='';
   if ($short_review==1) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo'
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xshort_review_y" name="xshort_review" value="1" '.$cky.' />
                  <label class="form-check-label" for="xshort_review_y">'.adm_translate("Oui").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xshort_review_n" name="xshort_review" value="0" '.$ckn.'/>
                  <label class="form-check-label" for="xshort_review_n">'.adm_translate("Non").'</label>
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
   <legend><a class="tog" id="show_divers_http" title="'.adm_translate("Replier la liste").'"><i id="i_divers_http" class="fa fa-caret-down fa-lg text-primary" ></i>&nbsp;</a>'.adm_translate("Divers").' HTTP</legend>
      <div id="divers_http" class="adminsidefield card card-body mb-3" style="display:none;">
         <div class="mb-3 row">
            <label class="col-form-label col-sm-8" for="xrss_host_verif">'.adm_translate("Pour les grands titres de sites de news, activer la vérification de l'existance d'un web sur le Port 80").'</label>
            <div class="col-sm-4 my-2">';
   $cky='';$ckn='';
   if ($rss_host_verif==true) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo'
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xrss_host_verif_y" name="xrss_host_verif" value="true" '.$cky.' />
                  <label class="form-check-label" for="xrss_host_verif_y">'.adm_translate("Oui").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xrss_host_verif_n" name="xrss_host_verif" value="false" '.$ckn.' />
                  <label class="form-check-label" for="xrss_host_verif_n">'.adm_translate("Non").'</label>
               </div>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-8" for="xcache_verif">'.adm_translate("Pour les pages HTML générées, activer les tags avancés de gestion du cache").'</label>
            <div class="col-sm-4 my-2">';
   $cky='';$ckn='';
   if ($cache_verif==true) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo'
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xcache_verif_y" name="xcache_verif" value="true" '.$cky.' />
                  <label class="form-check-label" for="xcache_verif_y">'.adm_translate("Oui").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xcache_verif_n" name="xcache_verif" value="false" '.$ckn.' />
                  <label class="form-check-label" for="xcache_verif_n">'.adm_translate("Non").'</label> <span class="small help-text">(Multimania)</span>
               </div>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-8" for="xdns_verif">'.adm_translate("Activer la résolution DNS pour les posts des forums, IP-Ban, ...").'</label>';
   $cky='';$ckn='';
   if ($dns_verif==true) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
        echo '<div class="col-sm-4 my-2">
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xdns_verif_y" name="xdns_verif" value="true" '.$cky.' />
                  <label class="form-check-label" for="xdns_verif_y">'.adm_translate("Oui").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xdns_verif_n" name="xdns_verif" value="false" '.$ckn.' />
                  <label class="form-check-label" for="xdns_verif_n">'.adm_translate("Non").'</label>
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
      <legend><a class="tog" id="show_divers_syst" title="'.adm_translate("Replier la liste").'"><i id="i_divers_syst" class="fa fa-caret-down fa-lg text-primary" ></i>&nbsp;</a>'.adm_translate("Divers").' SYSTEM</legend>
      <div id="divers_syst" class="adminsidefield card card-body mb-3" style="display:none;">';
    if (!$savemysql_size)
       $savemysql_size='256';
    else {
      $sel_size256  = ($savemysql_size=='256')  ? 'selected="selected"' : '' ;
      $sel_size512  = ($savemysql_size=='512')  ? 'selected="selected"' : '' ;
      $sel_size1024 = ($savemysql_size=='1024') ? 'selected="selected"' : '' ;
    }
   echo '
   <div class="form-floating mb-3">
      <select class="form-select" id="xsavemysql_size" name="xsavemysql_size">
         <option value="256" '.$sel_size256.'>256 Ko</option>
         <option value="512" '.$sel_size512.'>512 Ko</option>
         <option value="1024" '.$sel_size1024.'>1024 Ko</option>
      </select>
      <label class="text-primary" for="xsavemysql_size">'.adm_translate("Taille maximum des fichiers de sauvegarde SaveMysql").'</label>
   </div>';
   if (!$savemysql_mode)
      $savemysql_mode='1';
   else {
      $type_save1 = $savemysql_mode=='1' ? 'selected="selected"' : '' ;
      $type_save2 = $savemysql_mode=='2' ? 'selected="selected"' : '' ;
      $type_save3 = $savemysql_mode=='3' ? 'selected="selected"' : '' ;
   }
   echo '
   <div class="form-floating mb-3">
      <select class="form-select" id="xsavemysql_mode" name="xsavemysql_mode">
         <option value="1" '.$type_save1.'>'.adm_translate("Toute tables. Fichier envoyé au navigateur. Pas de limite de taille").'</option>
         <option value="2" '.$type_save2.'>'.adm_translate("Fichiers dans /slogs. table par table, tables non scindées : limite").'&nbsp;'.$savemysql_size.' Ko</option>
         <option value="3" '.$type_save3.'>'.adm_translate("Fichiers dans /slogs. table par table, lignes par lignes, tables scindées : limite").'&nbsp;'.$savemysql_size.' Ko</option>
      </select>
      <label class="text-primary" for="xsavemysql_mode">'.adm_translate("Type de sauvegarde SaveMysql").'</label>
   </div>
   <div class="mb-3 row">
      <label class="col-form-label col-sm-4" for="xdebugmysql">'.adm_translate("Activer les logs mysql").'</label>';
   $cky='';$ckn='';
   if ($debugmysql) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo '
      <div class="col-sm-8 my-2">
         <div class="form-check form-check-inline">
            <input type="radio" class="form-check-input" id="xdebugmysql_y" name="xdebugmysql" value="true" '.$cky.' />
            <label class="form-check-label" for="xdebugmysql_y">'.adm_translate("Oui").'</label>
         </div>
         <div class="form-check form-check-inline">
            <input type="radio" class="form-check-input" id="xdebugmysql_n" name="xdebugmysql" value="false" '.$ckn.' />
            <label class="form-check-label" for="xdebugmysql_n">'.adm_translate("Non").'</label>
         </div>
      </div>
   </div>
   <div class="mb-3 row">
      <label class="col-form-label col-sm-4" for="xtiny_mce">'.adm_translate("Activer l'éditeur Tinymce").'</label>';
   $cky=''; $ckn='';
   if($tiny_mce) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo '
      <div class="col-sm-8 my-2">
         <div class="form-check form-check-inline">
            <input type="radio" class="form-check-input" id="xtiny_mce_y" name="xtiny_mce" value="true" '.$cky.' />
            <label class="form-check-label" for="xtiny_mce_y">'.adm_translate("Oui").'</label>
         </div>
         <div class="form-check form-check-inline">
            <input type="radio" class="form-check-input" id="xtiny_mce_n" name="xtiny_mce" value="false" '.$ckn.' />
            <label class="form-check-label" for="xtiny_mce_n">'.adm_translate("Non").'</label>
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
   <div class="my-3">
      <button class="btn btn-primary" type="submit">'.adm_translate("Sauver les modifications").'</button>
   </div>
   </form>';
   $fv_parametres = '
   xadmin_cook_duration: {
      validators: {
         regexp: {
            regexp:/^\d{1,10}$/,
            message: "0-9"
         },
         between: {
            min: 1,
            max: 9999999999,
            message: "1 ... 9999999999"
         }
      }
   },
   xuser_cook_duration: {
      validators: {
         regexp: {
            regexp:/^\d{1,10}$/,
            message: "0-9"
         },
         between: {
            min: 1,
            max: 9999999999,
            message: "1 ... 9999999999"
         }
      }
   },
   xtop: {
      validators: {
         regexp: {
            regexp:/^[1-9]\d{0,4}$/,
            message: "0-9"
         },
         between: {
            min: 1,
            max: 9999,
            message: "1 ... 9999"
         }
      }
   },
   xstoryhome: {
      validators: {
         regexp: {
            regexp:/^[1-9]\d{0,4}$/,
            message: "0-9"
         },
         between: {
            min: 1,
            max: 9999,
            message: "1 ... 9999"
         }
      }
   },
   xoldnum: {
      validators: {
         regexp: {
            regexp:/^[1-9]\d{0,4}$/,
            message: "0-9"
         },
         between: {
            min: 1,
            max: 9999,
            message: "1 ... 9999"
         }
      }
   },
   xmyIP: {
      validators: {
         ip: {
            message: "Please enter a valid IP address"
         }
      }
   },
   xlever: {
      validators: {
         regexp: {
            regexp: /^(2[0-3]|[0-1][0-9]):([0-5][0-9])$/,
            message: "00:00"
         }
      }
   },
   xcoucher: {
      validators: {
         regexp: {
            regexp: /^(2[0-3]|[0-1][0-9]):([0-5][0-9])$/,
            message: "00:00"
         }
      }
   },
   xtroll_limit: {
      validators: {
         regexp: {
            regexp:/^[1-9](\d{0,2})$/,
            message: "0-9"
         },
         between: {
            min: 1,
            max: 999,
            message: "1 ... 999"
         }
      }
   },
   xadminmail: {
      validators: {
         emailAddress: {
            message: "'.adm_translate("Merci de fournir une nouvelle adresse Email valide.").'",
         }
      }
   },
   xsmtp_host: {
      validators: {
         notEmpty: {
            enabled: true,
         },
      },
   },
   xsmtp_port: {
      validators: {
         notEmpty: {
            enabled: true,
         },
      },
   },
   xsmtp_username: {
      validators: {
         notEmpty: {
            enabled: true,
         },
      },
   },
   xsmtp_password: {
      validators: {
         notEmpty: {
            enabled: true,
         },
      },
   },
   !###!
   xmail1.addEventListener("change", function (e) {
      if(e.target.checked) {
         fvitem.disableValidator("xsmtp_host");
         fvitem.disableValidator("xsmtp_port");
         fvitem.disableValidator("xsmtp_username");
         fvitem.disableValidator("xsmtp_password");
         smtp.style.display="none";
      }
   });
   xmail2.addEventListener("change", function (e) {
      if(e.target.checked) {
         fvitem.enableValidator("xsmtp_host");
         fvitem.enableValidator("xsmtp_port");
         smtp.style.display="flex";
      }
      fvitem.revalidateField("xsmtp_host");
      fvitem.revalidateField("xsmtp_port");
   });
   auth_y.addEventListener("change", function (e) {
      if(e.target.checked) {
         fvitem.enableValidator("xsmtp_username");
         fvitem.enableValidator("xsmtp_password");
         auth.style.display="flex";
      }
     fvitem.revalidateField("xsmtp_username");
     fvitem.revalidateField("xsmtp_password");
   });
   auth_n.addEventListener("change", function (e) {
      if(e.target.checked) {
         fvitem.disableValidator("xsmtp_username");
         fvitem.disableValidator("xsmtp_password");
         auth.style.display="none"
      }
   });

   secu_y.addEventListener("change", function (e) {
     e.target.checked ? chifr.style.display="block" : chifr.style.display="none" ;
   });
   secu_n.addEventListener("change", function (e) {
     e.target.checked ? chifr.style.display="none" : chifr.style.display="block" ;
   });

   if(xmail1.checked) {
      fvitem.disableValidator("xsmtp_host");
      fvitem.disableValidator("xsmtp_port");
      fvitem.disableValidator("xsmtp_username");
      fvitem.disableValidator("xsmtp_password");
      smtp.style.display="none";
   }
   if(auth_n.checked) {
      fvitem.disableValidator("xsmtp_username");
      fvitem.disableValidator("xsmtp_password");
      auth.style.display="none";
   }
   ';
   $arg1='
   const settingspref = document.getElementById("settingspref");
   const smtp = document.getElementById("smtp");
   const auth = document.getElementById("auth");
   const chifr = document.getElementById("chifr");
   const xmail1 = document.querySelector("#xmail_fonction1");
   const xmail2 = document.querySelector("#xmail_fonction2");
   const auth_n = document.querySelector("#xsmtp_auth_n");
   const auth_y = document.querySelector("#xsmtp_auth_y");
   const secu_n = document.querySelector("#xsmtp_secure_n");
   const secu_y = document.querySelector("#xsmtp_secure_y");

   xmail2.checked ? "" : smtp.style.display="none" ;// no need ?...
   secu_y.checked ? "" : chifr.style.display="none" ;

   var formulid = ["settingspref"];
   inpandfieldlen("xsitename",100);
   inpandfieldlen("xTitlesitename",100);
   inpandfieldlen("xnuke_url",200);
   inpandfieldlen("xsite_logo",255);
   inpandfieldlen("xslogan",100);
   inpandfieldlen("xstartdate",30);
   inpandfieldlen("xanonymous",25);
   inpandfieldlen("xstart_page",100);
   inpandfieldlen("xlocale",100);
   inpandfieldlen("xlever",5);
   inpandfieldlen("xcoucher",5);
   inpandfieldlen("xgmt",5);
   inpandfieldlen("xbackend_title",100);
   inpandfieldlen("xbackend_language",10);
   inpandfieldlen("xbackend_image",200);
   inpandfieldlen("xadminmail",254);
   inpandfieldlen("xnotify_email",254);
   inpandfieldlen("xnotify_from",254);
   inpandfieldlen("xnotify_subject",100);
   inpandfieldlen("xtipath",100);
   inpandfieldlen("xuserimg",100);
   inpandfieldlen("xadminimg",100);
   inpandfieldlen("xadmf_ext",3);
   ';
   adminfoot('fv',$fv_parametres,$arg1,'');
}

switch ($op) {
   case 'Configure':
      Configure();
   break;
   case 'ConfigSave':
      include("admin/settings_save.php");
      ConfigSave($xdebugmysql,$xparse,$xsitename,$xnuke_url,$xsite_logo,$xslogan,$xstartdate,$xadminmail,$xtop,$xstoryhome,$xoldnum,$xultramode,$xanonpost,$xDefault_Theme,$xbanners,$xmyIP,$xfoot1,$xfoot2,$xfoot3,$xfoot4,$xbackend_title,$xbackend_language,$xbackend_image,$xbackend_width,$xbackend_height,$xlanguage,$xlocale,$xperpage,$xpopular,$xnewlinks,$xtoplinks,$xlinksresults,$xlinks_anonaddlinklock,$xnotify,$xnotify_email,$xnotify_subject,$xnotify_message,$xnotify_from,$xmoderate,$xanonymous,$xmaxOptions,$xsetCookies,$xtipath,$xuserimg,$xadminimg,$xadmingraphic,$xadmart,$xminpass,$xhttpref,$xhttprefmax,$xpollcomm,$xlinkmainlogo,$xstart_page,$xsmilies,$xOnCatNewLink,$xEmailFooter,$xshort_user,$xgzhandler,$xrss_host_verif,$xcache_verif,$xmember_list,$xdownload_cat,$xmod_admin_news,$xgmt,$xAutoRegUser,$xTitlesitename,$xfilemanager,$xshort_review,$xnot_admin_count,$xadmin_cook_duration,$xuser_cook_duration,$xtroll_limit,$xsubscribe,$xCloseRegUser,$xshort_menu_admin,$xmail_fonction,$xmemberpass,$xshow_user,$xdns_verif,$xmember_invisible,$xavatar_size,$xlever,$xcoucher,$xmulti_langue,$xadmf_ext,$xsavemysql_size,$xsavemysql_mode,$xtiny_mce,$xnpds_twi,$xnpds_fcb,$xDefault_Skin,$xsmtp_host,$xsmtp_auth,$xsmtp_username,$xsmtp_password,$xsmtp_secure,$xsmtp_crypt,$xsmtp_port,$xdkim_auto);
   break;
}
?>
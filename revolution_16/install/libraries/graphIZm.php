<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/* IZ-Xinstall version : 1.3                                            */
/*                                                                      */
/* Auteurs : v.0.1.0 EBH (plan.net@free.fr)                             */
/*         : v.1.1.1 jpb, phr                                           */
/*         : v.1.1.2 jpb, phr, dev, boris                               */
/*         : v.1.1.3 dev - 2013                                         */
/*         : v.1.2 phr, jpb - 2017                                      */
/*         : v.1.3 jpb - 2024                                           */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

function entete() {
   global $langue, $cms_logo, $cms_name, $stage, $Version_Sub, $phpver, $sqlver;
   echo '
   <!DOCTYPE html>
   <html lang="'.language_iso(1,0,0).'">
   <head>
      <meta charset="utf-8">
      <title>NPDS IZ-Xinstall - Installation &amp; Configuration</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="content-script-type" content="text/javascript" />
      <meta http-equiv="content-style-type" content="text/css" />
      <meta http-equiv="expires" content="0" />
      <meta http-equiv="pragma" content="no-cache" />
      <meta http-equiv="identifier-url" content="" />
      <meta name="author" content="Developpeur, EBH, jpb, phr" />
      <meta name="owner" content="npds.org" />
      <meta name="reply-to" content="developpeur@npds.org" />
      <meta name="description" content="NPDS IZ-Xinstall" />
      <meta name="keywords" content="NPDS, Installateur automatique" />
      <meta name="rating" content="general" />
      <meta name="distribution" content="global" />
      <meta name="copyright" content="npds.org 2001-2024" />
      <meta name="revisit-after" content="15 days" />
      <meta name="resource-type" content="document" />
      <meta name="robots" content="none" />
      <meta name="generator" content="NPDS IZ-Xinstall" />
      <link rel="stylesheet" href="lib/font-awesome/css/all.min.css" />
      <link rel="stylesheet" href="themes/_skins/cosmo/bootstrap.min.css" />
      <link rel="stylesheet" href="lib/formvalidation/dist/css/formValidation.min.css">
      <link rel="stylesheet" href="themes/npds-boost_sk/style/style.css">
      <link rel="shortcut icon" href="install/images/favicon.ico" type="image/x-icon">
      <script type="text/javascript" src="lib/js/jquery.min.js"></script>
      <script type="text/javascript" src="lib/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
      <script type="text/javascript" src="lib/js/npds_adapt.js"></script>
   </head>
   <body>
      <div class="p-2 mb-4 bg-light">
         <div class="row">
            <div class="col-sm-2 d-none d-md-inline-block"><img class="img-fluid" src="install/images/header.png" alt="NPDS logo" /></div>
            <div id="logo_header" class="col my-auto ps-3 ps-md-0">
               <h1 class="display-4">NPDS<br /><small class="text-body-secondary">'.ins_translate("Installation automatique").' <em> '.NEW_VERSION.'</em></small></h1>
            </div>
            <div class="col-sm-3 text-end small my-auto">
               <ul class="list-group list-group-flush">
                  <li class="bg-transparent border-0 p-0 list-group-item">'.$Version_Sub.' '.NEW_VERSION.'</li>
                  <li class="bg-transparent border-0 p-0 list-group-item">Php '.$phpver.'</li>
                  <li class="bg-transparent border-0 p-0 list-group-item">Sql '.$sqlver.'</li>
               </ul>
            </div>
         </div>
      </div>
      <div class="container-lg p-0">';
}

function pied_depage($etat) {
   global $stage;
   echo '
         </div>
      </div>
      <footer class="d-flex align-items-center bg-light p-4 mt-4">
         <div class=""><a href="http://www.npds.org" target="_blank">NPDS</a> IZ-Xinstall v.1.3</div>
         <div class="spinner-border ms-auto text-'.$etat.'" role="status" aria-hidden="true"></div>
      </footer>
   </body>
</html>';
   exit();
}

function page_message($chaine) {
   entete();
   echo '
   <h2>'.$chaine.'</h2>';
   pied_depage('success');
}

function menu() {
   global $menu, $langue, $colorst1, $colorst2, $colorst3, $colorst4, $colorst5, $colorst6, $colorst7, $colorst8, $colorst9, $colorst10;
   $menu='';
   $menu.= '
         <div class="row px-3 g-3">
            <div class="col-md-3">
               <ul class="list-group mb-3 small text-md-end">
                  <li class="list-group-item list-group-item'.$colorst1.' px-2 py-1 border-0">'.ins_translate('Langue').'</li>
                  <li class="list-group-item list-group-item'.$colorst2.' px-2 py-1 border-0">'.ins_translate('Bienvenue').'</li>
                  <li class="list-group-item list-group-item'.$colorst3.' px-2 py-1 border-0">'.ins_translate('Licence').'</li>
                  <li class="list-group-item list-group-item'.$colorst4.' px-2 py-1 border-0">'.ins_translate('Vérification des fichiers').'</li>
                  <li class="list-group-item list-group-item'.$colorst5.' px-2 py-1 border-0">'.ins_translate('Paramètres de connexion').'</li>
                  <li class="list-group-item list-group-item'.$colorst6.' px-2 py-1 border-0">'.ins_translate('Autres paramètres').'</li>
                  <li class="list-group-item list-group-item'.$colorst7.' px-2 py-1 border-0">'.ins_translate('Base de données').'</li>
                  <li class="list-group-item list-group-item'.$colorst8.' px-2 py-1 border-0">'.ins_translate('Compte Admin').'</li>
                  <li class="list-group-item list-group-item'.$colorst9.' px-2 py-1 border-0">'.ins_translate('Module UPload').'</li>
                  <li class="list-group-item list-group-item'.$colorst10.' px-2 py-1 border-0">'.ins_translate('Fin').'</li>
               </ul>
            </div>
            <div class="col-md-9">';
   return $menu;
}
?>
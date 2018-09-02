<?php
/************************************************************************/
/* NPDS DUNE : Net Portal Dynamic System .                              */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2018 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
define('CITRON','tarteaucitron');

// since NPDS Rev 16 this ressources are required dont remove //
echo '
<link rel="stylesheet" href="lib/font-awesome/css/font-awesome.min.css" />'; // web font
echo '
<link id="bsth" rel="stylesheet" href="lib/bootstrap/dist/css/bootstrap.min.css" />'; // framework
echo '
<link id="bsthxtra" rel="stylesheet" href="lib/bootstrap/dist/css/extra.css" />'; // develop
echo '
<link rel="stylesheet" href="lib/formvalidation/dist/css/formValidation.min.css" />'; // form control
echo '
<link rel="stylesheet" href="lib/jquery-ui.min.css">';
echo '
<link rel="stylesheet" href="lib/bootstrap-table/dist/bootstrap-table.min.css" />'; // table
echo '
<link rel="stylesheet" href="lib/css/prism.css" />
<script type="text/javascript" src="lib/js/jquery.min.js"></script>
';

if(defined('CITRON')){
   if (function_exists('language_iso'))
      echo '
<script type="text/javascript"> var tarteaucitronForceLanguage = "'.language_iso(1,'','').'"; </script>
<script type="text/javascript" src="/lib/tarteaucitron/tarteaucitron.js"></script>
<script type="text/javascript">
   //<![CDATA[
   tarteaucitron.init({
       "hashtag": "#tarteaucitron", /* Ouverture automatique du panel avec le hashtag */
       "highPrivacy": false, /* désactiver le consentement implicite (en naviguant) ? */
       "orientation": "top", /* le bandeau doit être en haut (top) ou en bas (bottom) ? */
       "adblocker": false, /* Afficher un message si un adblocker est détecté */
       "showAlertSmall": true, /* afficher le petit bandeau en bas à droite ? */
       "cookieslist": true, /* Afficher la liste des cookies installés ? */
       "removeCredit": true, /* supprimer le lien vers la source ? */
       "cookieDomain": "labo.infocapagde.com" /* Nom de domaine sur lequel sera posé le cookie - pour les multisites / sous-domaines - Facultatif */
   });
   //]]
</script>'; //RGPD tool
}
?>
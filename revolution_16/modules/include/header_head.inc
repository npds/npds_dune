<?php
/************************************************************************/
/* NPDS DUNE : Net Portal Dynamic System .                              */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2019 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
define('CITRON','tarteaucitron');

// since NPDS Rev 16 this ressources are required dont remove //
echo '
<link rel="stylesheet" href="lib/font-awesome/css/all.min.css" />'; // web font V5
echo '
<link id="bsth" rel="stylesheet" href="lib/bootstrap/dist/css/bootstrap.min.css" />'; // framework
echo '
<link id="bsthxtra" rel="stylesheet" href="lib/bootstrap/dist/css/extra.css" />'; // developpement
echo '
<link rel="stylesheet" href="lib/formvalidation/dist/css/formValidation.min.css" />'; // form control
echo '
<link rel="stylesheet" href="lib/jquery-ui.min.css" />';//interface
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
<script type="text/javascript" src="lib/tarteaucitron/tarteaucitron.js"></script>
<script type="text/javascript">
   //<![CDATA[
   tarteaucitron.init({
       "privacyUrl": "", /* Privacy policy url */
       "hashtag": "#tarteaucitron", /* Ouverture automatique du panel avec le hashtag */
       "cookieName": "tartaucitron", /* Cookie name */
       "orientation": "top", /* le bandeau doit être en haut (top) ou en bas (bottom) ? */
       "showAlertSmall": true, /* afficher le petit bandeau en bas à droite ? */
       "cookieslist": true, /* Afficher la liste des cookies installés ? */
       "adblocker": false, /* Afficher un message si un adblocker est détecté */
       "AcceptAllCta" : true, /* Show the accept all button when highPrivacy on */
       "highPrivacy": false, /* désactiver le consentement implicite (en naviguant) ? */
       "handleBrowserDNTRequest": false, /* If Do Not Track == 1, disallow all */
       "removeCredit": true, /* supprimer le lien vers la source ? */
       "moreInfoLink": true, /* Show more info link */
       "useExternalCss": false, /* If false, the tarteaucitron.css file will be loaded */
       "cookieDomain": "" /* Nom de domaine sur lequel sera posé le cookie - pour les multisites / sous-domaines - Facultatif */
   });
   //]]
</script>'; //RGPD tool
}
?>
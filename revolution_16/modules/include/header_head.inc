<?php
/************************************************************************/
/* NPDS DUNE : Net Portal Dynamic System .                              */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2024 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
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
      <script type="text/javascript" src="lib/tarteaucitron/tarteaucitron.min.js"></script>
      <script type="text/javascript" src="lib/js/npds_tarteaucitron.js"></script>'; //RGPD tool
}
?>
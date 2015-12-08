<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

echo '
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta http-equiv="x-ua-compatible" content="ie=edge" />
<link rel="stylesheet" href="lib/font-awesome-4.4.0/css/font-awesome.min.css" />';
//echo '<link rel="stylesheet" href="lib/bootstrap-4.0.0-alpha/dist/css/bootstrap.css" />';//V4
echo '<link rel="stylesheet" href="https://cdn.rawgit.com/twbs/bootstrap/v4-dev/dist/css/bootstrap.css">';
//echo '<link rel="stylesheet" href="lib/bootstrap-4.0.0-alpha/scss/bootstrap.scss" />';//V4

//echo '<link id="bsth" rel="stylesheet" href="themes/npds-boost/skin/journal/bootstrap.css" />';//V3

echo '
<link rel="stylesheet" href="lib/formvalidation-dist-v0.7.0/dist/css/formValidation.min.css" />
<link rel="stylesheet" href="lib/jquery-ui.css">
<link rel="stylesheet" href="lib/bootstrap-table/dist/bootstrap-table.css" />
<link rel="stylesheet" href="lib/formvalidation-dist-v0.7.0/dist/css/formValidation.min.css" />
<link rel="stylesheet" href="lib/css/glyphicons.css" />
<link rel="stylesheet" href="lib/css/glyphicons-bootstrap.css" />
<link rel="stylesheet" href="lib/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" />
<link rel="stylesheet" href="lib/css/octicons.css" />';

echo '<link rel="stylesheet" href="//cdn.jsdelivr.net/emojione/1.5.0/assets/css/emojione.min.css" />';
echo '<link rel="stylesheet" href="themes/'.$theme.'/style/ajust.css" />
<link rel="stylesheet" href="themes/'.$theme.'/js/highlight/styles/github-gist.css" />
<script type="text/javascript" src="lib/js/jquery-2.1.4.min.js"></script>';
//echo '<script type="text/javascript" src="lib/bootstrap-3.3.4/js/bootstrap.js"></script>';//V3
echo '<script type="text/javascript" src="lib/js/tether.min.js"></script>';//V4
echo '<script type="text/javascript" src="lib/bootstrap-4.0.0-alpha/dist/js/bootstrap.js"></script>';//V4

echo '
<script type="text/javascript" src="lib/bootstrap-table/dist/bootstrap-table.js"></script>
<script type="text/javascript" src="lib/bootstrap-table/dist/locale/bootstrap-table-'.language_iso(1,"-",1).'.min.js"></script>
<script type="text/javascript" src="lib/bootstrap-table/dist/extensions/mobile/bootstrap-table-mobile.min.js"></script>
<script type="text/javascript" src="lib/bootstrap-table/dist/extensions/export/bootstrap-table-export.min.js"></script>
<script type="text/javascript" src="lib/formvalidation-dist-v0.7.0/dist/js/formValidation.min.js"></script>
<script type="text/javascript" src="lib/formvalidation-dist-v0.7.0/dist/js/language/'.language_iso(1,"_",1).'.js"></script>
<script type="text/javascript" src="lib/formvalidation-dist-v0.7.0/dist/js/framework/bootstrap.min.js"></script>
<script type="text/javascript" src="lib/js/js.cookie.js"></script>
<script type="text/javascript" src="lib/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="lib/js/bootbox.min.js"></script>
<script type="text/javascript" src="themes/'.$theme.'/js/checkfieldinp.js"></script>
<script type="text/javascript" src="lib/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="lib/bootstrap-datepicker/dist/locales/bootstrap-datepicker.'.language_iso(1,"","").'.min.js"></script>

<script type="text/javascript" src="lib/tableExport.jquery.plugin-master/tableExport.js"></script>

<script type="text/javascript" src="themes/'.$theme.'/js/highlight/highlight.pack.js"></script>
';
echo '<script src="//cdn.jsdelivr.net/emojione/1.5.0/lib/js/emojione.min.js"></script>';

?>

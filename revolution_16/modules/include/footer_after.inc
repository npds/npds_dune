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

$time_end = microtime(true);
$time = round($time_end - TIMESTART,3);

//==> core required dont remove
echo '
      <script type="text/javascript" src="lib/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
      <script type="text/javascript" src="lib/bootstrap-table/dist/bootstrap-table.min.js"></script>
      <script type="text/javascript" src="lib/bootstrap-table/dist/locale/bootstrap-table-'.language_iso(1,"-",1).'.min.js" async="async"></script>
      <script type="text/javascript" src="lib/bootstrap-table/dist/extensions/mobile/bootstrap-table-mobile.min.js" async="async"></script>
      <script type="text/javascript" src="lib/bootstrap-table/dist/extensions/export/bootstrap-table-export.min.js" async="async"></script>
      <script type="text/javascript" src="lib/tableExport.jquery.plugin-master/tableExport.js" async="async"></script>
      <script type="text/javascript" src="lib/js/js.cookie.js" async="async"></script>
      <script type="text/javascript" src="lib/js/jquery-ui.min.js" ></script>
      <script type="text/javascript" src="lib/js/bootbox.min.js" async="async"></script>
      <script type="text/javascript" src="lib/js/prism.js"></script>
      <script type="text/javascript">
         //<![CDATA[
            (tarteaucitron.job = tarteaucitron.job || []).push("vimeo");
            (tarteaucitron.job = tarteaucitron.job || []).push("youtube");
            (tarteaucitron.job = tarteaucitron.job || []).push("dailymotion");
            tarteaucitron.user.gtagUa = "UA-6423983-2";
            tarteaucitron.user.gtagMore = function () { /* add here your optionnal gtag() */ };
            (tarteaucitron.job = tarteaucitron.job || []).push("gtag");
         //]]
      </script>';
// <== core
// ==> geoloc module required dont remove
if(!defined('GEO')) {
   if (defined('GEO_IP') or defined('GEO_US') or defined('GEO_BL') or defined('GEO_AD') or defined('GEO') ) {
      $fcall='';
      include('modules/geoloc/geoloc_conf.php');
      echo '
      <script id="fa_gog" type="text/javascript" src="modules/geoloc/include/fontawesome-markers.min.js"></script>';
      if (defined('GEO_IP') or defined('GEO_US'))
         $fcall='geoloc_init';
      else if(defined('GEO_BL') and !defined('GEO_AD') and !defined('GEO'))
         $fcall='geoloc_loadbloc';
      else if(defined('GEO_AD'))
         $fcall='geoloc_conf';
      echo '
      <script async="async" defer="defer" src="https://maps.googleapis.com/maps/api/js?key='.$api_key.'&callback='.$fcall.'&amp;language='.language_iso(1,'',0).'"></script>';
   }
}
// <== geoloc module
if(isset($time_end))
   echo '
      <span class="small" id="build_time">'.aff_langue('[french]Temps :[/french][english]Time:[/english][german]Zeit in Sekunden[/german][spanish]Tiempo en segundos :[/spanish][chinese]&#x5728;&#x51E0;&#x79D2;&#x949F;&#x7684;&#x65F6;&#x95F4; :[/chinese] '.$time.' [french]seconde(s)[/french][english]second(s)[/english]').'</span>';
echo '
   </footer>
</div>
<script type="text/javascript" src="lib/js/npds_adapt.js"></script>';
?>
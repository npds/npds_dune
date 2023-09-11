<?php
/************************************************************************/
/* NPDS DUNE : Net Portal Dynamic System .                              */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2023 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

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
            //tarteaucitron.user.gtagUa = ""; /*uncomment the line and add your gtag*/
            //tarteaucitron.user.gtagMore = function () { /* uncomment the line add here your optionnal gtag() */ };
            //(tarteaucitron.job = tarteaucitron.job || []).push("gtag");
         //]]
      </script>';
// <== core

//If you want to show the Time used to generate each page uncomment those lines
   /*
   $time_end = microtime(true);
   $time = round($time_end - TIMESTART,3);
   echo '
   <span class="small" id="build_time">'.aff_langue('[french]Temps :[/french][english]Time:[/english][german]Zeit in Sekunden[/german][spanish]Tiempo en segundos :[/spanish][chinese]&#x5728;&#x51E0;&#x79D2;&#x949F;&#x7684;&#x65F6;&#x95F4; :[/chinese] '.$time.' [french]seconde(s)[/french][english]second(s)[/english]').'</span>';
   */
//

echo '
   </footer>
</div>
<script type="text/javascript" src="lib/js/npds_adapt.js"></script>';
?>
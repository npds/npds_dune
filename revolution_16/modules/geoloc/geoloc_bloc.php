<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/*                                                                      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2018 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/*                                                                      */
/* module geoloc version 3.0                                            */
/* geoloc_bloc.php file 2008-2018 by Jean Pierre Barbary (jpb)          */
/* dev team : Philippe Revilliod (phr)                                  */
/************************************************************************/

if(autorisation_block('include#modules/geoloc/geoloc_bloc.php') !='') define('GEO_BL',true);

$ModPath='geoloc';
$content = '';
include ('modules/'.$ModPath.'/geoloc_conf.php'); 
$content .='
<div class="mb-2" id="map_bloc" style="width:100%; height:'.$h_b.'px;"></div>
<script type="text/javascript">
//<![CDATA[
   var 
   map_b,
   mapdivbl = document.getElementById("map_bloc");
   function geoloc_loadbloc() {
      var icon_bl = {
         url: "'.$ch_img.$img_mbgb.'",
         size: new google.maps.Size('.$w_ico_b.','.$h_ico_b.'),
         origin: new google.maps.Point(0, 0),
         anchor: new google.maps.Point(0, 0),
         scaledSize: new google.maps.Size('.$w_ico_b.', '.$h_ico_b.')
      };
      map_b = new google.maps.Map(mapdivbl,{
         center: new google.maps.LatLng(45, 0),
         zoom :3,
         zoomControl:false,
         streetViewControl:false,
         mapTypeControl: false,
         scrollwheel: false,
         disableDoubleClickZoom: true 
      });
      map_b.setMapTypeId(google.maps.MapTypeId.'.$cartyp_b.');
      function createMarkerB(point_b) {
         var marker_b = new google.maps.Marker({
            position: point_b,
            map: map_b,
            icon: icon_bl
         })
         return marker_b;
      }
      //== Fonction qui traite le fichier JSON ==
      $.getJSON("modules/'.$ModPath.'/include/data.json", {}, function(data){
         $.each(data.markers, function(i, item){
            var point_b = new google.maps.LatLng(item.lat,item.lng);
            var marker_b = createMarkerB(point_b);
         });
      });
    }
//]]>
</script>';

$content .='<div class="mt-1"><a href="modules.php?ModPath='.$ModPath.'&amp;ModStart=geoloc"><i class="fa fa-globe fa-lg mr-1"></i>[french]Carte[/french][english]Map[/english][chinese]&#x5730;&#x56FE;[/chinese][spanish]Mapa[/spanish][german]Karte[/german]</a>';
if($admin)
   $content .= '<a href="admin.php?op=Extend-Admin-SubModule&amp;ModPath=geoloc&amp;ModStart=admin/geoloc_set"><i class="fa fa-cogs fa-lg ml-1"></i>&nbsp;[french]Admin[/french][english]Admin[/english][chinese]Admin[/chinese][spanish]Admin[/spanish]</a>';
$content .= '</div>';
$content = aff_langue($content);
?>
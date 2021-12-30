<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/*                                                                      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2021 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/*                                                                      */
/* module geoloc version 4.1                                            */
/* geoloc_bloc.php file 2008-2021 by Jean Pierre Barbary (jpb)          */
/* dev team : Philippe Revilliod (Phr), A.NICOL                         */
/************************************************************************/

$ModPath='geoloc';
$content = '';
include('modules/'.$ModPath.'/geoloc.conf');
$source_fond='';
switch ($cartyp_b) {
   case 'OSM':
      $source_fond='new ol.source.OSM()';
   break;
   case 'sat-google':
      $source_fond=' new ol.source.XYZ({url: "https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}",crossOrigin: "Anonymous", attributions: " &middot; <a href=\"https://www.google.at/permissions/geoguidelines/attr-guide.html\">Map data ©2015 Google</a>"})';
   break;
   case 'Road':case 'Aerial':case 'AerialWithLabels':
      $source_fond='new ol.source.BingMaps({key: "'.$api_key_bing.'",imagerySet: "'.$cartyp_b.'"})';
   break;
   case 'natural-earth-hypso-bathy': case 'geography-class':
      $source_fond=' new ol.source.TileJSON({url: "https://api.tiles.mapbox.com/v4/mapbox.'.$cartyp_b.'.json?access_token='.$api_key_mapbox.'"})';
   break;
   case 'terrain':case 'toner':case 'watercolor':
      $source_fond='new ol.source.Stamen({layer:"'.$cartyp_b.'"})';
   break;
   default:
   $source_fond='new ol.source.OSM()';
}
$content .='
<div class="mb-2" id="map_bloc_ol" tabindex="200" style=" min-height:'.$h_b.'px;" lang="'.language_iso(1,0,0).'"></div>
<script type="text/javascript">
//<![CDATA[
      if (!$("link[href=\'/lib/ol/ol.css\']").length)
         $("head link[rel=\'stylesheet\']").last().after("<link rel=\'stylesheet\' href=\'/lib/ol/ol.css\' type=\'text/css\' media=\'screen\'>");
      $("head link[rel=\'stylesheet\']").last().after("<link rel=\'stylesheet\' href=\'/modules/geoloc/include/css/geoloc_bloc.css\' type=\'text/css\' media=\'screen\'>");
      if (typeof ol=="undefined")
         $("head").append($("<script />").attr({"type":"text/javascript","src":"/lib/ol/ol.js"}));
      $(function(){
      var
      georefUser_icon = new ol.style.Style({
         image: new ol.style.Icon({
            src: "'.$ch_img.$img_mbgb.'",
            imgSize:['.$w_ico_b.','.$h_ico_b.']
         })
      }),
      srcUsers = new ol.source.Vector({
         url: "modules/geoloc/include/user.geojson",
         format: new ol.format.GeoJSON()
      }),
      georeferencedUsers = new ol.layer.Vector({
         source: srcUsers,
         style: georefUser_icon
      }),
      attribution = new ol.control.Attribution({collapsible: true}),
      fullscreen = new ol.control.FullScreen();
      var map = new ol.Map({
         interactions: new ol.interaction.defaults({
            constrainResolution: true, onFocusOnly: true
         }),
         controls: new ol.control.defaults({attribution: false}).extend([attribution, fullscreen]),
         target: document.getElementById("map_bloc_ol"),
         layers: [
         new ol.layer.Tile({
            source: '.$source_fond.'
          }),
          georeferencedUsers
        ],
        view: new ol.View({
          center: ol.proj.fromLonLat([0, 45]),
          zoom: '.$z_b.'
        })
      });
      
//            console.log(georeferencedUsers.getSource().getExtent());

      function checkSize() {
        var small = map.getSize()[0] < 600;
        attribution.setCollapsible(small);
        attribution.setCollapsed(small);
      }
      window.addEventListener("resize", checkSize);
      checkSize();';

$content .= file_get_contents('modules/geoloc/include/ol-dico.js');
$content .='
      const targ = map.getTarget();
      const lang = targ.lang;
      for (var i in dic) {
         if (dic.hasOwnProperty(i)) {
            $("#map_bloc_ol "+dic[i].cla).prop("title", dic[i][lang]);
         }
      }

      fullscreen.on("enterfullscreen",function(){
         $(dic.olfullscreentrue.cla).attr("data-original-title", dic["olfullscreentrue"][lang]);
      })
      fullscreen.on("leavefullscreen",function(){
         $(dic.olfullscreenfalse.cla).attr("data-original-title", dic["olfullscreenfalse"][lang]);
      })
      $("#map_bloc_ol .ol-zoom-in, #map_bloc_ol .ol-zoom-out").tooltip({placement: "right", container: "#map_bloc_ol",});
      $("#map_bloc_ol .ol-full-screen-false, #map_bloc_ol .ol-rotate-reset, #map_bloc_ol .ol-attribution button[title]").tooltip({placement: "left", container: "#map_bloc_ol",});
   });

//]]>
</script>';

$content .='<div class="mt-1"><a href="modules.php?ModPath='.$ModPath.'&amp;ModStart=geoloc"><i class="fa fa-globe fa-lg me-1"></i>[french]Carte[/french][english]Map[/english][chinese]&#x5730;&#x56FE;[/chinese][spanish]Mapa[/spanish][german]Karte[/german]</a>';
if($admin)
   $content .= '<div class="text-right"><a class="tooltipbyclass" href="admin.php?op=Extend-Admin-SubModule&amp;ModPath=geoloc&amp;ModStart=admin/geoloc_set" title="[french]Administration[/french][english]Administration[/english][chinese]&#34892;&#25919;[/chinese][spanish]Administraci&oacute;n[/spanish][german]Verwaltung[/german]" data-bs-placement="left"><i class="fa fa-cogs fa-lg ms-1"></i></a></div>';
$content .= '</div>';
$content = aff_langue($content);
?>
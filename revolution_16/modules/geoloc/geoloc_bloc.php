<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/*                                                                      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2019 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/*                                                                      */
/* module geoloc version 4.0                                            */
/* geoloc_bloc.php file 2008-2019 by Jean Pierre Barbary (jpb)          */
/************************************************************************/

//if(autorisation_block('include#modules/geoloc/geoloc_bloc.php') !='') define('GEO_BL',true);

$ModPath='geoloc';
$content = '';
include('modules/'.$ModPath.'/geoloc_conf.php');
$source_fond='';
switch ($cartyp_b) {
   case 'OSM':
      $source_fond='new ol.source.OSM()';
   break;
   case 'SATELLITE': case 'TERRAIN': case 'HYBRID':
      $source_fond='';
   break;
   case 'Road':case 'Aerial':case 'AerialWithLabels':
      $source_fond='new ol.source.BingMaps({key: "'.$api_key_bing.'",imagerySet: "'.$cartyp_b.'"})';
   break;
   case 'natural-earth-hypso-bathy': case 'geography-class':
      $source_fond=' new ol.source.TileJSON({url: "https://api.tiles.mapbox.com/v4/mapbox.'.$cartyp_b.'.json?access_token='.$api_key_mapbox.'"})';
   break;
//   https://{a-d}.tiles.mapbox.com/v4/mapbox.mapbox-streets-v6/{z}/{x}/{y}.vector.pbf?access_token=pk.eyJ1IjoiamlwZXh1IiwiYSI6ImNqeGg5aDlvZTBjdXgzdm5yY25tYnU2eXgifQ.9SpaSxWvFrOEIq35wGITXw
   
   case 'terrain':case 'toner':case 'watercolor':
      $source_fond='new ol.source.Stamen({layer:"'.$cartyp_b.'"})';
   break;
   default:
   $source_fond='new ol.source.OSM()';
}
 
$content .='
<div class="mb-2" id="map_bloc_ol" tabindex="200" style="width:100%; min-height:'.$h_b.'px;"></div>
<script type="text/javascript">
//<![CDATA[
      $("head").append($("<script />").attr({"type":"text/javascript","src":"lib/ol/ol.js"}));
      $("head link[rel=\'stylesheet\']").last().after("<link rel=\'stylesheet\' href=\'/lib/ol/ol.css\' type=\'text/css\' media=\'screen\'>");

      var georefUser_icon = new ol.style.Style({
         image: new ol.style.Icon({
            src: "'.$ch_img.$img_mbgb.'",
            imgSize:['.$w_ico_b.','.$h_ico_b.']
         })
      });

      var georeferencedUsers = new ol.layer.Vector({
         source: new ol.source.Vector({
            url: "modules/geoloc/include/user.geojson",
            format: new ol.format.GeoJSON()
         }),
         style: georefUser_icon
      });
      var attribution = new ol.control.Attribution({collapsible: true});
      var map = new ol.Map({
         interactions: new ol.interaction.defaults({
            constrainResolution: true, onFocusOnly: true
         }),
         controls: new ol.control.defaults({attribution: false}).extend([attribution, new ol.control.FullScreen()]),
         target: "map_bloc_ol",
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

      function checkSize() {
        var small = map.getSize()[0] < 600;
        attribution.setCollapsible(small);
        attribution.setCollapsed(small);
      }
      window.addEventListener("resize", checkSize);
      checkSize();
//]]>
</script>';

$content .='<div class="mt-1"><a href="modules.php?ModPath='.$ModPath.'&amp;ModStart=geoloc"><i class="fa fa-globe fa-lg mr-1"></i>[french]Carte[/french][english]Map[/english][chinese]&#x5730;&#x56FE;[/chinese][spanish]Mapa[/spanish][german]Karte[/german]</a>';
if($admin)
   $content .= '<a href="admin.php?op=Extend-Admin-SubModule&amp;ModPath=geoloc&amp;ModStart=admin/geoloc_set"><i class="fa fa-cogs fa-lg ml-1"></i>&nbsp;[french]Admin[/french][english]Admin[/english][chinese]Admin[/chinese][spanish]Admin[/spanish][german]Admin[/german]</a>';
$content .= '</div>';
$content = aff_langue($content);
?>
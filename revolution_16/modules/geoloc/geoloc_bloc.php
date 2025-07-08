<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/*                                                                      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2025 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/*                                                                      */
/* module geoloc version 4.2                                            */
/* geoloc_bloc.php file 2008-2025 by Jean Pierre Barbary (jpb)          */
/* dev team : Philippe Revilliod (Phr), A.NICOL                         */
/************************************************************************/
$ModPath='geoloc';
global $nuke_url;
$content = '';
include('modules/'.$ModPath.'/geoloc.conf');
$source_fond='';
switch ($cartyp_b) {
   case 'sat-google':
      $source_fond='
      new ol.source.XYZ({
         url: "https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}",
         attributions: " &middot; <a href=\"https://www.google.at/permissions/geoguidelines/attr-guide.html\">Map data ©2015 Google</a>",
         crossOrigin: "Anonymous",
      })';
   break;
   case 'microsoft.base.road': case 'microsoft.imagery': case 'microsoft.base.darkgrey':
      $source_fond='
      new ol.source.ImageTile({
         url: `https://atlas.microsoft.com/map/tile?subscription-key='.$api_key_azure.'&api-version=2.0&tilesetId='.$cartyp_b.'&zoom={z}&x={x}&y={y}&tileSize=256&language=EN`,
         crossOrigin: "Anonymous",
         attributions: `© ${new Date().getFullYear()} TomTom, Microsoft`
      })';
   break;
   case 'natural-earth-hypso-bathy': case 'geography-class':
      $source_fond='
      new ol.source.TileJSON({
         url: "https://api.tiles.mapbox.com/v4/mapbox.'.$cartyp_b.'.json?access_token='.$api_key_mapbox.'",
         crossOrigin: "Anonymous",
      })';
   break;
   case 'World_Imagery': case 'World_Shaded_Relief': case 'World_Physical_Map': case 'World_Topo_Map':
      $source_fond='
      new ol.source.XYZ({
         attributions: ["Powered by Esri", "Source: Esri, DigitalGlobe, GeoEye, Earthstar Geographics, CNES/Airbus DS, USDA, USGS, AeroGRID, IGN, and the GIS User Community"],
         url: "https://services.arcgisonline.com/ArcGIS/rest/services/'.$cartyp_b.'/MapServer/tile/{z}/{y}/{x}",
         crossOrigin: "Anonymous",
         maxZoom: 23
     })';
      $max_r='40000';
      $min_r='0';
   break;
   case 'stamen_terrain': case 'stamen_watercolor': case 'alidade_smooth': case "stamen_toner":
      $source_fond='
      new ol.source.StadiaMaps({layer:"'.$cartyp_b.'"})';
   break;
   default:
   $source_fond='new ol.source.OSM()';
}
$content .='
<div class="mb-2" id="map_bloc_ol" tabindex="200" style=" min-height:'.$h_b.'px;" lang="'.language_iso(1,0,0).'"></div>';
if(!defined('OL')) {
   define('OL','ol');
   $content .= '<script type="text/javascript" src="'.$nuke_url.'/lib/ol/ol.js"></script>';
}
$content .='
<script type="text/javascript">
//<![CDATA[
      if (!$("link[href=\'/lib/ol/ol.css\']").length)
         $("head link[rel=\'stylesheet\']").last().after("<link rel=\'stylesheet\' href=\''.$nuke_url.'/lib/ol/ol.css\' type=\'text/css\' media=\'screen\'>");
      $("head link[rel=\'stylesheet\']").last().after("<link rel=\'stylesheet\' href=\''.$nuke_url.'/modules/geoloc/include/css/geoloc_bloc.css\' type=\'text/css\' media=\'screen\'>");
      $("head link[rel=\'stylesheet\']").last().after("<link rel=\'stylesheet\' href=\''.$nuke_url.'/lib/bootstrap/dist/css/bootstrap-icons.css\' type=\'text/css\' media=\'screen\'>");
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
      // ==> cluster users
      var
      clusterSource = new ol.source.Cluster({
         distance: "40",
         minDistance: "15",
         source: srcUsers
      }),
      styleCache = {},
      users_cluster = new ol.layer.Vector({
          id: "cluster_users",
          source: clusterSource,
          style: function(feature) {
              var size = feature.get("features").length;
              var style = styleCache[size];
              if (!style) {
                  let r = 19;
                  if (size < 10)
                      r = 15;
                  else if (size < 100)
                      r = 17;
                  else if (size > 999)
                      r = 24;
                  if (size > 1) {
                      style = new ol.style.Style({
                          image: new ol.style.Circle({
                              radius: r,
                              stroke: new ol.style.Stroke({
                                  color: "rgba(255, 255, 255,0.1)",
                                  width: 8
                              }),
                              fill: new ol.style.Fill({
                                  color: "rgba(99, 99, 98, 0.7)"
                              }),
                          }),
                          text: new ol.style.Text({
                              text: size.toString() + "\n" + "\uf4da",
                              font: "13px \'bootstrap-icons\'",
                              fill: new ol.style.Fill({
                                  color: "#fff"
                              }),
                              textBaseline: "bottom",
                              offsetY: 14,
                          })
                      });
                  }
                  else {
                      style = georefUser_icon;
                  }
                  styleCache[size] = style;
              }
              return style;
          }
      });
      // <== cluster users

      var map = new ol.Map({
         interactions: new ol.interaction.defaults.defaults({
            constrainResolution: true,
            onFocusOnly: true
         }),
         controls: new ol.control.defaults.defaults({attribution: false}).extend([attribution, fullscreen]),
         target: document.getElementById("map_bloc_ol"),
         layers: [
         new ol.layer.Tile({
            source: '.$source_fond.'
          }),
          users_cluster,
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
   $content .= '<div class="text-end"><a class="tooltipbyclass" href="admin.php?op=Extend-Admin-SubModule&amp;ModPath=geoloc&amp;ModStart=admin/geoloc_set" title="[french]Administration[/french][english]Administration[/english][chinese]&#34892;&#25919;[/chinese][spanish]Administraci&oacute;n[/spanish][german]Verwaltung[/german]" data-bs-placement="left"><i class="fa fa-cogs fa-lg ms-1"></i></a></div>';
$content .= '</div>';
$content = aff_langue($content);
?>
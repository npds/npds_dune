<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/*                                                                      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2020 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/*                                                                      */
/* module geoloc version 4.0                                            */
/* geoloc_locip.php file 2008-2020 by Jean Pierre Barbary (jpb)         */
/************************************************************************/

#autodoc localiser_ip() : construit la carte pour l'ip géoréférencée ($iptoshow) à localiser
function localiser_ip($iptoshow) {
   include('modules/geoloc/geoloc_conf.php');
   global $NPDS_Prefix, $iptoshow;
   $aff_location ='';
   if($geo_ip==1) {
      $ip_location = sql_query("SELECT * FROM ".$NPDS_Prefix."ip_loc WHERE ip_ip LIKE \"".$iptoshow."\"");
      if (sql_num_rows($ip_location) !== 0) {
         $row = sql_fetch_assoc($ip_location);
         $aff_location .= '
      <div class="col-md-5">
         <div id="map_ip" style=" min-height:240px;"></div>
      </div>
      <script type="module">
      //<![CDATA[
         $("head").append($("<script />").attr({"type":"text/javascript","src":"lib/ol/ol.js"}));
         $("head link[rel=\'stylesheet\']").last().after("<link rel=\'stylesheet\' href=\'lib/ol/ol.css\' type=\'text/css\' media=\'screen\'>");
         $("head link[rel=\'stylesheet\']").last().after("<link rel=\'stylesheet\' href=\'modules/geoloc/include/css/geoloc_locip.css\' type=\'text/css\' media=\'screen\'>");
      // Pour svg
      function pointStyleFunction(feature, resolution) {
        return  new ol.style.Style({
          image: new ol.style.Circle({
            radius: 30,
            fill: new ol.style.Fill({color: "rgba(255, 0, 0, 0.1)"}),
            stroke: new ol.style.Stroke({color: "red", width: 1})
          })
        });
      }

      var ipPoint = new ol.Feature({
        geometry: new ol.geom.Point(ol.proj.fromLonLat(['.$row['ip_long'].','.$row['ip_lat'].'])),
        name: "IP"
      });
       var iconStyle = new ol.style.Style({
        image: new ol.style.Icon(({
          src: "'.$ch_img.'ip_loc.svg",
          size:[100,100]
        }))
      });

      var vectorSource = new ol.source.Vector({
        features: [ipPoint]
      });
      var vectorLayer = new ol.layer.Vector({
        source: vectorSource,
        style: pointStyleFunction
      });

      var Controls = new ol.control.defaults;

      var map = new ol.Map({
         controls: Controls.extend([
            new ol.control.FullScreen()
         ]),
        target: "map_ip",
        layers: [
          new ol.layer.Tile({
            source: new ol.source.OSM()
          }),
          vectorLayer
        ],
        view: new ol.View({
          center: ol.proj.fromLonLat(['.$row['ip_long'].','.$row['ip_lat'].']),
          zoom: 12
        })
      });
      $(function(){
        $("#map_ip .ol-zoom-in, #map_ip .ol-zoom-out").tooltip({placement: "right", container: "#map_ip",});
        $("#map_ip .ol-full-screen-false, #map_ip .ol-rotate-reset, #map_ip .ol-attribution button[title]").tooltip({placement: "left", container: "#map_ip",});
      });

      //]]>
      </script>';
      }
   }
   return $aff_location;
}
?>

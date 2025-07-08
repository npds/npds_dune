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
/* geoloc_locip.php file 2008-2025 by Jean Pierre Barbary (jpb)         */
/* dev team : Philippe Revilliod (Phr), A.NICOL                         */
/************************************************************************/

#autodoc localiser_ip() : construit la carte pour l'ip géoréférencée ($iptoshow) à localiser
function localiser_ip($iptoshow) {
   include('modules/geoloc/geoloc.conf');
   global $NPDS_Prefix, $iptoshow;
   $aff_location ='';
   if($geo_ip==1) {
      $ip_location = sql_query("SELECT * FROM ".$NPDS_Prefix."ip_loc WHERE ip_ip LIKE \"".$iptoshow."\"");
      if (sql_num_rows($ip_location) !== 0) {
         $row = sql_fetch_assoc($ip_location);
         $aff_location .= '
      <div class="col-md-5">
         <div id="map_ip" style=" min-height:240px;" lang="'.language_iso(1,0,0).'"></div>
      </div>
      <script type="module">
      //<![CDATA[
         if (!$("link[href=\'/lib/ol/ol.css\']").length)
            $("head link[rel=\'stylesheet\']").last().after("<link rel=\'stylesheet\' href=\'lib/ol/ol.css\' type=\'text/css\' media=\'screen\'>");
         $("head link[rel=\'stylesheet\']").last().after("<link rel=\'stylesheet\' href=\'modules/geoloc/include/css/geoloc_locip.css\' type=\'text/css\' media=\'screen\'>");
         if (typeof ol=="undefined")
            $("head").append($("<script />").attr({"type":"text/javascript","src":"lib/ol/ol.js"}));

         $(function(){
            function pointStyleFunction(feature, resolution) {
              return  new ol.style.Style({
                image: new ol.style.Circle({
                  radius: 30,
                  fill: new ol.style.Fill({color: "rgba(255, 0, 0, 0.1)"}),
                  stroke: new ol.style.Stroke({color: "red", width: 1})
                })
              });
            }
            var
               ipPoint = new ol.Feature({
                  geometry: new ol.geom.Point(ol.proj.fromLonLat(['.$row['ip_long'].','.$row['ip_lat'].'])),
                  name: "IP"
               }),
               iconStyle = new ol.style.Style({
                  image: new ol.style.Icon(({
                     src: "'.$ch_img.'ip_loc.svg",
                     size:[100,100]
                  }))
               }),
               vectorSource = new ol.source.Vector({
                  features: [ipPoint]
               }),
               vectorLayer = new ol.layer.Vector({
                  source: vectorSource,
                  style: pointStyleFunction
               }),
               attribution = new ol.control.Attribution({collapsible: true}),
               fullscreen = new ol.control.FullScreen();
            var map = new ol.Map({
               controls: new ol.control.defaults.defaults({attribution: false}).extend([
                  attribution,
                  fullscreen
               ]),
               target: document.getElementById("map_ip"),
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
            });';
   $aff_location .= file_get_contents('modules/geoloc/include/ol-dico.js');
   $aff_location .= '
            const targ = map.getTarget();
            const lang = targ.lang;
            for (var i in dic) {
               if (dic.hasOwnProperty(i)) {
                  $("#map_ip "+dic[i].cla).prop("title", dic[i][lang]);
               }
            }
            fullscreen.on("enterfullscreen",function(){
               $(dic.olfullscreentrue.cla).attr("data-original-title", dic["olfullscreentrue"][lang]);
            })
            fullscreen.on("leavefullscreen",function(){
               $(dic.olfullscreenfalse.cla).attr("data-original-title", dic["olfullscreenfalse"][lang]);
            })
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

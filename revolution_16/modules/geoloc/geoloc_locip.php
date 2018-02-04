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
/* geoloc_geoloc_loc.php file 2008-2018 by Jean Pierre Barbary (jpb)    */
/* dev team : Philippe Revilliod (phr)                                  */
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
         <div id="map_ip" style="width:100%; height:240px;"></div>
      </div>
      <script type="text/javascript">
      //<![CDATA[
         $("head link[rel=\'stylesheet\']").last().after("<link rel=\'stylesheet\' href=\'/modules/geoloc/include/css/geoloc_style.css\' type=\'text/css\' media=\'screen\'>");
         $(document).ready(function() {
            if($("#map_bloc").length)
               console.log("map_bloc est dans la page");//debug
            else {
               $("head").append($("<script />").attr("src","https://maps.google.com/maps/api/js?v=3.exp&amp;key='.$api_key.'&amp;language='.language_iso(1,'',0).'"));
               $("head").append($("<script />").attr("src","modules/geoloc/include/fontawesome-markers.min.js"));
            }
         });
         var 
         map_ip, map_b,
         mapdivip = document.getElementById("map_ip"),
         mapdivbl = document.getElementById("map_bloc");
         function geoloc_loaduser() {
         icon_u = {
            path: fontawesome.markers.DESKTOP,
            scale: '.$acg_sc.',
            strokeWeight: '.$acg_t_ep.',
            strokeColor: "'.$acg_t_co.'",
            strokeOpacity: '.$acg_t_op.',
            fillColor: "red",
            fillOpacity: '.$acg_f_op.',
         };
         icon_ip = {
            url: "'.$ch_img.'ip_loc.svg",
            size: new google.maps.Size(200, 200),
            scaledSize: new google.maps.Size(200, 200),
            anchor: new google.maps.Point(100,100),
            origin: new google.maps.Point(0,0)
         };
         icon_bl = {
            url: "'.$ch_img.$img_mbgb.'",
            size: new google.maps.Size('.$w_ico_b.','.$h_ico_b.'),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(0, 0),
            scaledSize: new google.maps.Size('.$w_ico_b.', '.$h_ico_b.')
         };

         //==> carte du bloc
         if (document.getElementById("map_bloc")) {
            map_b = new google.maps.Map(mapdivbl,{
               center: new google.maps.LatLng(45, 0),
               zoom :3,
               zoomControl:false,
               streetViewControl:false,
               mapTypeControl: false,
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
            $.getJSON("modules/geoloc/include/data.json", {}, function(data){
               $.each(data.markers, function(i, item){
                  var point_b = new google.maps.LatLng(item.lat,item.lng);
                  var marker_b = createMarkerB(point_b);
               });
            });
         };
         //<== carte du bloc

            map_ip = new google.maps.Map(mapdivip,{
               center: new google.maps.LatLng('.$row['ip_lat'].', '.$row['ip_long'].'),
               zoom :7,
               zoomControl:true,
               streetViewControl:true,
               mapTypeControl: true,
               scrollwheel: false,
               disableDoubleClickZoom: true 
            });
            map_ip.setMapTypeId(google.maps.MapTypeId.'.$cartyp_b.');
            function createMarkerU(point_u) {
               var marker_u = new google.maps.Marker({
                  position: point_u,
                  map: map_ip,
                  title: "'.$iptoshow.'",
                  icon: icon_ip,
                  optimized: false
               })
               return marker_u;
            }
            var point_u = new google.maps.LatLng('.$row['ip_lat'].','.$row['ip_long'].');
            var marker_u = createMarkerU(point_u);
            var myoverlay = new google.maps.OverlayView();
            myoverlay.draw = function () {
               this.getPanes().markerLayer.id="markerLayer";
            };
            myoverlay.setMap(map_ip);
         }
         $(document.body).attr("onload", "geoloc_loaduser()");
      //]]>
      </script>';
      }
      else {
         $file_path = 'https://ipapi.co/'.$iptoshow.'/json';
         if(file_contents_exist($file_path)) {
            $loc = file_get_contents($file_path);
            $loc_obj = json_decode($loc);
            if($loc_obj) {
               $pay=removeHack($loc_obj->country_name);
               $codepay=removeHack($loc_obj->country);
               $vi=removeHack($loc_obj->city);
               $lat=(float)$loc_obj->latitude;
               $long=(float)$loc_obj->longitude;
               sql_query("INSERT INTO ".$NPDS_Prefix."ip_loc (ip_long, ip_lat, ip_ip, ip_country, ip_code_country, ip_city) VALUES ('$long', '$lat', '$iptoshow', '$pay', '$codepay', '$vi')");
            }
         }
      }
   }
   return $aff_location;
}
?>
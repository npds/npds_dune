<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/*                                                                      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/*                                                                      */
/* module carto version 2.0                                             */
/* développeur jpb phr                                                  */
/*                                                                      */
/************************************************************************/

/*Debut Securite*/
	if (!stristr($_SERVER['PHP_SELF'],"modules.php")) { die(); }
	if (strstr($ModPath,'..') || strstr($ModStart,'..') || stristr($ModPath,'script') || stristr($ModPath, 'cookie') || stristr($ModPath, 'iframe') || stristr($ModPath, 'applet') || stristr($ModPath, 'object') || stristr($ModPath, 'meta') || stristr($ModStart, 'script') || stristr($ModStart, 'cookie') || stristr($ModStart, 'iframe') || stristr($ModStart, 'applet') || stristr($ModStart, 'object') || stristr($ModStart, 'meta'))
	{
	die();
	}
/*Fin Securite*/
$pdst="-1";
$ModPath = carto;
if (file_exists('modules/'.$ModPath.'/admin/pages.php')) {
   include ('modules/'.$ModPath.'/admin/pages.php');
}
include ("header.php");
echo '<h3 align="center">WX Tiles sur la carte</h3>';

?>

     <div id="tSelect" class="form-control col-xs-12 col-sm-4 col-md-4 col-lg-4">S&eacute;lectionner une date : </div>
    <div id="wxSelect" class="form-control col-xs-12 col-sm-4 col-md-4 col-lg-4">Choix : </div>

	 <div id="map"></div>    
	
	<script type="text/javascript">
    var options = {
	  zoom: 14,
	  center: new google.maps.LatLng(43.265, 3.499),
	  mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	var map = new google.maps.Map(document.getElementById("map"),options);

//	var wxoverlay=new WXTiles();

//	wxoverlay.addToMap(map);
//	wxoverlay.addColorBar('big','horiz');
//	document.getElementById('tSelect').appendChild(wxoverlay.getTSelect());
//	document.getElementById('wxSelect').appendChild(wxoverlay.getVSelect());
	
	
	var wxoverlay=new WXTiles({'cview':'rain','vorder':['rain','wind','tmp','hs']});
	  wxoverlay.addToMap(map);
	  wxoverlay.addColorBar('big','horiz');
	  document.getElementById('tSelect').appendChild(wxoverlay.getTSelect());
	  document.getElementById('wxSelect').appendChild(wxoverlay.getVSelect());
	
//	    function updateTime(){
//        var t=parseInt($("[name=WXTiles_0_tSelect]").val());
//        wxoverlay.setTime(t);
//      }	
	
	
	
	


   </script>
   
<Iframe id="wcam" src="http://earth.nullschool.net/#current/wind/isobaric/1000hPa/orthographic=3.499,43.265,1850" width=731px height=600px scrolling=no style="border:2px solid #5DB4D9"></Iframe>   
   

 
   
   
   

<?php

include("footer.php");
?>
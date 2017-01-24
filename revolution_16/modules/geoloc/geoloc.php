<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/*                                                                      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2017 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/*                                                                      */
/* module geoloc version 3.0                                            */
/* geoloc_geoloc.php file 2008-2015 by Jean Pierre Barbary (jpb)        */
/* dev team : Philippe Revilliod (phr)                                  */
/************************************************************************/

/*
le géoréférencement des anonymes est basé sur un décodage des adresse ip
le géoréférencement des membres sur une géolocalisation exacte réalisé par l'utilisateur
la geolocalisation est réalisé par les api html5 de géolocalisation
*/
if (!stristr($_SERVER['PHP_SELF'],"modules.php")) { die(); }
if (strstr($ModPath,"..") || strstr($ModStart,"..") || stristr($ModPath, "script") || stristr($ModPath, "cookie") || stristr($ModPath, "iframe") || stristr($ModPath, "applet") || stristr($ModPath, "object") || stristr($ModPath, "meta") || stristr($ModStart, "script") || stristr($ModStart, "cookie") || stristr($ModStart, "iframe") || stristr($ModStart, "applet") || stristr($ModStart, "object") || stristr($ModStart, "meta")) {
   die();
}

global $pdst, $language;
if (file_exists('modules/'.$ModPath.'/admin/pages.php')) {
   include ('modules/'.$ModPath.'/admin/pages.php');
}
include ('modules/'.$ModPath.'/geoloc_conf.php');
if (file_exists('modules/'.$ModPath.'/lang/geoloc.lang-'.$language.'.php')) {
   include_once('modules/'.$ModPath.'/lang/geoloc.lang-'.$language.'.php');
}
else {
   include_once('modules/'.$ModPath.'/lang/geoloc.lang-french.php');
} 

$infooo='';
$js_dragtrue ='';
$js_dragfunc ='';
$lkadm ='';
$mess_adm ='';
// admin tool
if(autorisation(-127)) {
$mess_adm ='<span class="text-danger">'.geoloc_translate('Rappel : vous êtes en mode administrateur !').'</span>';
$lkadm = '<a href="admin.php?op=Extend-Admin-SubModule&amp;ModPath=geoloc&amp;ModStart=admin/geoloc_set"><i id="cogs" class="fa fa-cogs fa-lg"></i></a>';
$infooo = geoloc_translate('Modification administrateur');
$js_dragtrue ='draggable:true,';
$js_dragfunc ='
    google.maps.event.addListener(marker, "dragend", function(event) {
        var myLatLng = event.latLng;
        var lat = myLatLng.lat(); 
        var lng = myLatLng.lng();
        var id = marker.get("id");
        var us = marker.get("us");
        //=== creer un HTML DOM form element
        var inputForm = document.createElement("form");
        inputForm.setAttribute("action","");
        inputForm.onsubmit = function() {updateMarker(lat,lng,"mod",id); return false;};
        inputForm.innerHTML = \'<fieldset>\'
        + \'<legend>\' + us + \' </legend>\'
        + \'<span class="text-danger">'.$infooo.'</span>\'
        + \'<div id="lalo"><span class="sou_tit">Latitude : </span>\' + lat + \'<br /><span class="sou_tit">Longitude : </span>\' + lng + \'</div>\'
        + \'<input type="hidden" id="html" value="\' + id + \'" style="width:100%;"/>\'
        + \'<br />\'
        + \'<button type="submit" class ="btn btn-primary">Enregistrez</button>\'
        + \'<input type="hidden" id="longitude" value="\' + lng + \'"/>\'
        + \'<input type="hidden" id="latitude" value="\' + lat + \'"/>\'
        + \'<input type="hidden" id="modgeo" value="mod"/>\'
        + \'<input type="hidden" id="uid" value="id"/>\'
        + \'</fieldset>\';
        infoWindow.setContent(inputForm);
        infoWindow.setPosition(myLatLng);
        infoWindow.open(map);
    });
    

    var markers_temp = [];
    
    google.maps.event.addListener(marker, "dragstart", function(event){
        markers_temp.push(marker);
        var myLatLng = event.latLng;
        var marker_temp = new google.maps.Marker({
            position: myLatLng,
            map: map,
            icon: iconmb,
            animation: google.maps.Animation.BOUNCE
        });
        marker_temp.setOpacity(0.3);
        marker_temp.set("id", "temp_pos");
        google.maps.event.addListener(infoWindow,"closeclick",function(currentMark){
            marker_temp.setMap(null);
            markers_temp[0].setPosition(myLatLng);
        });
    });
';
}


$username = $cookie[1];//recupere le username
$f_new_lat = floatval ($_GET['lat']);// lat du form de géoreferencement
$f_new_long = floatval ($_GET['lng']);// long du form de géoreferencement
$f_geomod = $_GET['mod'];
$f_uid = $_GET['uid'];

$av_ch = '';//chemin pour l'avatar

//Le membre
//cherche info user
$result = sql_query('SELECT uid FROM '.$NPDS_Prefix.'users WHERE uname LIKE "'.$username.'"');
while ($row = sql_fetch_array($result)) 
{$uid = $row['uid'];}
// voir si user existe dans users_extend
$resul = sql_query('SELECT uid FROM '.$NPDS_Prefix.'users_extend WHERE uid = "'.$uid.'"');
$found = sql_num_rows($resul);
//mise à jour users_extend si besoin
if ($found == 0)
$res = sql_query("INSERT INTO users_extend VALUES ('$uid','','','','','','','','','','','','','')");
//==> georeferencement utilisateur
if ($f_new_lat !='' and $f_new_long !='' and $f_geomod="neo")
sql_query('UPDATE '.$NPDS_Prefix.'users_extend SET '.$ch_lat.' = "'.$f_new_lat.'", '.$ch_lon.' = "'.$f_new_long.'" WHERE uid = "'.$uid.'"');
if ($f_new_lat !='' and $f_new_long !='' and $f_geomod="mod")
sql_query('UPDATE '.$NPDS_Prefix.'users_extend SET '.$ch_lat.' = "'.$f_new_lat.'", '.$ch_lon.' = "'.$f_new_long.'" WHERE uid = "'.$f_uid.'"');
//<== georeferencement utilisateur
$result = sql_query('SELECT * FROM '.$NPDS_Prefix.'users u LEFT JOIN '.$NPDS_Prefix.'users_extend ue ON u.uid = ue.uid WHERE uname LIKE "'.$username.'"');
while ($row = sql_fetch_array($result)) 
{
$uid = $row['uid'];
$user_from = $row['user_from'];
$ue_lat = $row[''.$ch_lat.''];
$ue_long = $row[''.$ch_lon.''];
$user_avatar = $row['user_avatar'];

//determine si c un avatar perso ou standard et fixe l'url de l'image
if (preg_match('#\/#', $user_avatar) === 1)
{$the_av_ch = $user_avatar;}else{$the_av_ch = 'images/forum/avatar/'.$user_avatar;};
}
//les membres
    if ($ibid=theme_image('forum/icons/email.gif')) {$imgtmpEM=$ibid;} else {$imgtmpEM='images/forum/icons/email.gif';}
    if ($ibid=theme_image('forum/icons/www_icon.gif')) {$imgtmpWW=$ibid;} else {$imgtmpWW='images/forum/icons/www_icon.gif';}
    if ($ibid=theme_image('forum/icons/ip_logged.gif')) {$imgtmpIP=$ibid;} else {$imgtmpIP='images/forum/icons/ip_logged.gif';}

$mbgr = 0;
$membre = @sql_query ('SELECT * FROM '.$NPDS_Prefix.'users u LEFT JOIN '.$NPDS_Prefix.'users_extend ue ON u.uid = ue.uid ORDER BY u.uname');
$total_membre = @sql_num_rows($membre);//==> membres du site

while ($row = sql_fetch_array($membre))
{
$us_uid = $row['uid'];
$us_name = $row['name'];
$us_uname = $row['uname'];
$us_user_avatar = $row['user_avatar'];
$us_lat = $row[''.$ch_lat.''];
$us_long = $row[''.$ch_lon.''];
$us_url = $row['url'];
$us_lastvisit = $row['user_lastvisit'];
if ($us_lastvisit != '')
$us_visit = getdate($us_lastvisit);
$visit = $us_visit[mday].'/'.$us_visit[mon].'/'.$us_visit[year];

//==> determine si c avatar perso ou standard et fixe l'url de l'image
if (preg_match('#\/#', $us_user_avatar) === 1)
{$av_ch = $us_user_avatar;}else{$av_ch = 'images/forum/avatar/'.$us_user_avatar;};

 if ($us_lat !='') //==> les membres géoréferencés
 {$mbgr++;

 $im = '&nbsp;&nbsp;<a href=\\"user.php?op=userinfo&amp;uname='.$us_uname.'\\"  target=\\"_blank\\"><img src=\\"'.$imgtmpPR.'\\" border=\\"0\\" alt=\\"\\" />'.translate("Profile").'</a>';
 
 if ($us_url != '') {
  if (strstr("http://", $us_url))
  $us_url = 'http://'.$us_url;
  $im .= '&nbsp;&nbsp;<a href=\\"'.$us_url.'\\" target=\\"_blank\\" ><img src=\\"'.$imgtmpWW.'\\" border=\\"0\\" alt=\\"\\" />www</a>';
      }
 $im .= '';
 //
 
 //==> construction du fichier json
 $cont_json .='{"lat":'.$us_lat.', "lng":'.$us_long.', "html":"<img src=\\"'.$av_ch.'\\" width=\\"32\\" height=\\"32\\" align=\\"middle\\" border=\\"0\\" />&nbsp;'.addslashes($us_uname).'<br /><span class=\\"sou_tit\\">Derni&#xE8;re visite : </span>'.$visit.'<br />'.$im.'", "label":"<span class=\\"mbg\\">'. addslashes($us_uname) .'</span>", "icon":"icon"},';
//construction marker membre
$mb_gr .='
var point = new google.maps.LatLng('.$us_lat. ','.$us_long.');
var marker = createMarker(point,map,infoWindow,"<i style=\"color:#c00000; opacity:0.4;\" class=\"fa fa-'.strtolower($f_mbg).' fa-lg\"></i>&nbsp;<span>'. addslashes($us_uname) .'</span>", \'<div id="infowindow" style="white-space: nowrap;"><br /><img class="img-thumbnail" src="'.$av_ch.'" align="middle" />&nbsp;<a href="user.php?op=userinfo&amp;uname='.$us_uname.'">'. addslashes($us_uname) .'</a><br />'.$imm.'Derni&egrave;re visite : '.$visit.'</div><br />'.$imm.'\',\'member\',"'. addslashes($us_uid) .'","'. addslashes($us_uname) .'");
bounds.extend(point);
map.fitBounds(bounds);
'; 
 }
}
if ($mbgr == 0)
$cont_json .='{"lat":0, "lng":0, "html":"<img src=\\"'.$av_ch.'\\" width=\\"32\\" height=\\"32\\" align=\\"middle\\" border=\\"0\\" />&nbsp;NPDS", "label":"NPDS", "icon":"icon"},';
$ent_json = '{"markers": [';
$ent_json .= $cont_json;
$file_json = $ent_json;
$file_json = preg_replace("#,$#","]}", $file_json);
//== ouverture, écriture fermeture fichier data.json
$f = fopen("modules/".$ModPath."/include/data.json", "w");
$w = fwrite($f, $file_json);
@fclose($f);
@chmod('modules/'.$ModPath.'/include/data.json', 0666);
//<== construction du fichier json

//les membres
//cherche les connectés dans session
$mbcng = 0;//==> membre connecté non géoréférencé
$ac = 0; //==> anonnyme connectés
$acng = 0; //==> anonnyme connectés non géoréférencés
$acg = 0; //==> anonnyme connectés géoréférencés

//nombre total on line
$result_con = @sql_query ('SELECT * FROM '.$NPDS_Prefix.'session s');
$total_connect = @sql_num_rows($result_con);

//jointure des tables pour centralisation infos membres
$result = @sql_query ('SELECT * FROM '.$NPDS_Prefix.'session s
LEFT JOIN '.$NPDS_Prefix.'users u ON s.username = u.uname
LEFT JOIN '.$NPDS_Prefix.'users_extend ue ON u.uid = ue.uid');
$k=0;
while ($row = sql_fetch_array($result)) 
{
   $session_guest = $row['guest'];
   $session_host_addr = $row['host_addr'];
   $users_name = $row['name'];
   $users_uname = $row['uname'];
   $users_user_avatar = $row['user_avatar'];
   $session_user_name = $row['username'];
   $session_host_addr = $row['host_addr'];
   $user_lat = $row[''.$ch_lat.''];
   $user_long = $row[''.$ch_lon.''];
   $us_url = $row['url'];
   $us_mns = $row['mns'];
   $us_rs = $row['M2'];

   //determine si c un avatar perso ou standard et fixe l'url de l'image
   if (preg_match('#\/#', $users_user_avatar) === 1)
   {$av_ch = $users_user_avatar;}else{$av_ch = 'images/forum/avatar/'.$users_user_avatar;};

   $socialnetworks=array(); $res_id=array();$my_rs='';
   if (!$short_user) {
      include('modules/reseaux-sociaux/reseaux-sociaux.conf.php');
      if ($us_rs!='') {
         $socialnetworks= explode(';',$us_rs);
         foreach ($socialnetworks as $socialnetwork) {
            $res_id[] = explode('|',$socialnetwork);
         }
         sort($res_id);
         sort($rs);
         foreach ($rs as $v1) {
            foreach($res_id as $y1) {
               $k = array_search( $y1[0],$v1);
               if (false !== $k) {
                  $my_rs.='<a href="'.$v1[1].$y1[1].'" target="_blank"><i class="fa fa-'.$v1[2].' fa-2x text-primary mr-2"></i></a>&nbsp;';
                  break;
               } 
               else $my_rs.='';
            }
         }
         $my_rsos[]=$my_rs;
      }
      else $my_rsos[]='';
   }

   if ($user_lat !='') { // c un membre géoréférencé
      $mbcg++;
      //=== menu fenetre info
      $imm = ' <a href="user.php?op=userinfo&amp;uname='.$users_uname.'"  target="_blank" ><i class="fa fa-user fa-2x mr-2" title="'.translate("Profile").'" data-toggle="tooltip"></i></a>';
      if ($user)
         $imm .= ' <a href="powerpack.php?op=instant_message&to_userid='.$users_uname.'" title="Envoyez moi un message interne"><i class="fa fa-envelope-o fa-2x mr-2"></i></a>';
      if ($us_url != '') {
         if (strstr("http://", $us_url))
         $us_url = 'http://'.$us_url;
         $imm .= ' <a href="'.$us_url.'" target="_blank" title="Visitez mon site"><i class="fa fa-external-link fa-2x mr-2"></i></a>';
      }
      if ($us_mns != '') {
         $imm .='&nbsp;<a href="minisite.php?op='.$users_uname.'" target="_blank" title="Visitez le minisite" data-toggle="tooltip"><i class="fa fa-desktop fa-2x mr-2"></i></a>';
      }


      //construction marker membre on line
      $mb_con_g .='
      var point = new google.maps.LatLng('.$user_lat. ','.$user_long.');
      var marker = createMarker(point,map, infoWindow,"<i style=\"color:'.$mbgc_f_co.';\" class=\"fa fa-'.strtolower($f_mbg).' fa-lg animated faa-pulse\"></i>&nbsp;<span>'. addslashes($users_uname) .'</span>", \'<div id="infowindow" style="white-space: nowrap; text-align:center;">'.$imm.'<hr /><img src="'.$av_ch.'" class="img-thumbnail" />&nbsp;<br /><a href="user.php?op=userinfo&amp;uname='.$users_uname.'">'. addslashes($users_uname) .'</a> @ '.$session_host_addr.'</div><hr />'.$my_rsos[$k].'\',\'c\',"'. addslashes($us_uid) .'","'. addslashes($users_uname) .'" );
      marker.setMap(map);
      bounds.extend(point);
      map.fitBounds(bounds);
      ';
   }
   if ($session_guest !=1 and !$user_lat) {
      $mb_con_ng .= '&nbsp;'.$session_user_name.'<br />'; $mbcng++;
   }
/*
//==> cherche si l'adresse IP est dans la base

$tres=sql_query("SELECT * FROM ".$NPDS_Prefix."ip_loc i WHERE ip_ip LIKE \"$session_host_addr\"");
//$iptronq = explode('.',$session_host_addr,-1);
while ($row1 = sql_fetch_array($tres)) {
$ip_lat1 = $row1['ip_lat'];
$ip_long1 = $row1['ip_long'];
$ip_ip1 = $row1['ip_ip'];
$ip_country1 = $row1['ip_country'];
$ip_city1 = $row1['ip_city'];
$ip_visi_pag = $row1['ip_visi_pag'];
$ip_visite = $row1['ip_visite'];

    if ($ip_lat1 != 0 and $ip_long1 != 0) {
        $ac++;$acg++;
        //gestion drapeau
        preg_match('#\(([^)]*)\)#',$ip_country1,$regs);
        $fla = $regs[1];
        //construction marker anonyme on line
        $ano_conn.= '
        var point = new google.maps.LatLng('.$ip_lat1.','.$ip_long1.');
        var marker = createMarker(point,map,infowindow,"<span class=\"accon\">'.geoloc_translate('Anonyme').$ac.'</span>", \'<div id="infowindow" style="white-space: nowrap;"><br /><img src="'.$ch_img.$nm_img_acg.'"width="32" height="32" align="middle" border="0" />&nbsp;<a href="user.php?op=userinfo&amp;uname='.$users_uname.'">'. addslashes($users_uname) .'</a>'.geoloc_translate('Anonyme').' '.$ac.' @ '.$session_host_addr.' <br /><p><span class="sou_tit">Hôte : </span>'.@gethostbyaddr($ip_ip1).'<br /><span class="sou_tit">Pays : </span>'.$ip_country1.'&nbsp;<img src="'.$ch_img.'flags/gif/'.strtolower($fla).'.gif"/><br /><span class="sou_tit">Ville : </span>'.$ip_city1.'<br /><span class="sou_tit">En visite ici : </span> '.$ip_visi_pag.'<br /><span class="sou_tit">Visites : </span>['.$ip_visite.']</p></div>\',\'ac\'); 
        marker.setMap(map);
        bounds.extend(point);
        map.fitBounds(bounds);
        ';
    }
    else {
        if ($users_uname != $session_user_name) {
            $acng++;
            $test_ip.='<i class="fa fa-tv fa-lg" title="IP non géoréférencé en ligne"></i> IP '.$acng.' '.$session_host_addr.' '.@gethostbyaddr($ip_ip1).' '.$ousursit.'<br />';
        }
    }
 }*/
 $k++;
};

if ($test_ip !='')
$temp_ip = $test_ip;
$test_ip = $temp_ip;
$olng = $mbcng+$acng;//==> on line non géoréférencés anonyme et membres
$olg = $mbcg+$acg;//==> on line géoréférencés anonyme et membres

//==> construction script pour google
$ecr_scr = '<script type="text/javascript">
//<![CDATA[
    var 
    map_b,
    mapdiv = document.getElementById("map"),
    sideba = document.getElementById("sidebar"),
    icon_bl = {
    url: "'.$ch_img.$img_mbgb.'",
    size: new google.maps.Size('.$w_ico_b.', '.$h_ico_b.'),
    origin: new google.maps.Point(0, 0),
    anchor: new google.maps.Point(0, 0),
    scaledSize: new google.maps.Size('.$w_ico_b.', '.$h_ico_b.')
    },
    icon = {
    url: "'.$ch_img.$nm_img_mbg.'",
    size: new google.maps.Size('.$w_ico.', '.$h_ico.'),
    origin: new google.maps.Point(0, 0),
    anchor: new google.maps.Point(0, 0),
    scaledSize: new google.maps.Size('.$w_ico.', '.$h_ico.')
    },
    ';
    if($mark_typ !== 1) { // marker svg
    $f_mbg=str_replace('-', '_',$f_mbg);
    $ecr_scr .='
    iconmb = {
        path: fontawesome.markers.'.$f_mbg.',
        scale: '.$mbg_sc.',
        strokeWeight: '.$mbg_t_ep.',
        strokeColor: "'.$mbg_t_co.'",
        strokeOpacity: '.$mbg_t_op.',
        fillColor: "'.$mbg_f_co.'",
        fillOpacity: '.$mbg_f_op.',
    },
    iconmbc = {
        path: fontawesome.markers.'.$f_mbg.',
        scale: '.$mbgc_sc.',
        strokeWeight: '.$mbgc_t_ep.',
        strokeColor: "'.$mbgc_t_co.'",
        strokeOpacity: '.$mbgc_t_op.',
        fillColor: "'.$mbgc_f_co.'",
        fillOpacity: '.$mbgc_f_op.',
    },
    iconac = {
        path: fontawesome.markers.'.$f_mbg.',
        scale: '.$acg_sc.',
        strokeWeight: '.$acg_t_ep.',
        strokeColor: "'.$acg_t_co.'",
        strokeOpacity: '.$acg_t_op.',
        fillColor: "'.$acg_f_co.'",
        fillOpacity: '.$acg_f_op.',
    },';
    } 
    else { // marker image
    $ecr_scr .='
    iconmb = {
    url: "'.$ch_img.$nm_img_mbg.'",
    size: new google.maps.Size('.$w_ico.', '.$h_ico.'),
    origin: new google.maps.Point(0, 0),
    anchor: new google.maps.Point(0, 0),
    scaledSize: new google.maps.Size('.$w_ico.', '.$h_ico.')
    },
    iconmbc = {
    url: "'.$ch_img.$nm_img_mbcg.'",
    size: new google.maps.Size('.$w_ico.', '.$h_ico.'),
    origin: new google.maps.Point(0, 0),
    anchor: new google.maps.Point(0, 0),
    scaledSize: new google.maps.Size('.$w_ico.', '.$h_ico.')
    },
    iconac = {
    url: "'.$ch_img.$nm_img_acg.'",
    size: new google.maps.Size('.$w_ico.', '.$h_ico.'),
    origin: new google.maps.Point(0, 0),
    anchor: new google.maps.Point(0, 0),
    scaledSize: new google.maps.Size('.$w_ico.', '.$h_ico.')
    },
    
    icont = {
    url: "'.$ch_img.'connect.gif",
    size: new google.maps.Size(32, 32),
    origin: new google.maps.Point(0, 0),
    anchor: new google.maps.Point(17, 34),
    scaledSize: new google.maps.Size(32, 32)
    },
    iconbt = {
    url: "'.$ch_img.'mbg.png",
    size: new google.maps.Size(16, 16),
    origin: new google.maps.Point(0, 0),
    anchor: new google.maps.Point(0, 0),
    scaledSize: new google.maps.Size(16, 16)
    },';
    };

    $ecr_scr .= '
    infoWindow = new google.maps.InfoWindow({}),
    bounds = new google.maps.LatLngBounds(),
    gmarkers = [],
    gmarkers_g = [];

//== variable pour le html de sidebar et tableau markers ==
var i = 0;

//== montre tous les markers d\'un type, et coche la checkbox
    function show(type) {
        for (var i=0; i<gmarkers.length; i++) {
            if (gmarkers[i].mytype == type) {gmarkers[i].setVisible(true)}
        }
        for (var i=0; i<gmarkers_g.length; i++) {
            if (gmarkers_g[i].mytype == type) {gmarkers_g[i].setVisible(true)}
        }
        // == coche la checkbox
        document.getElementById(type+\'box\').checked = true;
    }

//== cache les markers par type, et decoche la checkbox
    function hide(type) {
        for (var i=0; i<gmarkers.length; i++) {
            if (gmarkers[i].mytype == type) {gmarkers[i].setVisible(false)}
        }
        for (var i=0; i<gmarkers_g.length; i++) {
            if (gmarkers_g[i].mytype == type) {gmarkers_g[i].setVisible(false)}
        }
        //== checkbox sans coche
        document.getElementById(type+\'box\').checked = false;
        infoWindow.close();
    }

//== checkbox avec coche
    function boxclick(box,type) {
        if (box.checked) {
          show(type);
        } else {
          hide(type);
        }
       makeSidebar();
    }

//==> construction sidebar
    function makeSidebar() {
        var html = "";
        for (var i=0; i<gmarkers.length; i++) {
        if (gmarkers[i].getVisible()) {
            html += \'<a class="list-group-item list-group-item-default" href="javascript:myclick(\' + i + \')">\' + gmarkers[i].myname + \'</a>\';
            }
        }
      sideba.innerHTML = \'<div class="list-group">\'+ html +\'</div>\';
    }

    function myclick(i) {
        google.maps.event.trigger(gmarkers[i],"click");
        gmarkers[i].setMap(map);
        gmarkers[i].setAnimation(google.maps.Animation.BOUNCE);
        setTimeout(function(){ gmarkers[i].setAnimation(null); }, 3500);
    }
      
function geoloc_load() {
//==> carte du bloc
    if (document.getElementById("map_bloc")) {
        mapdivbl = document.getElementById("map_bloc");
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
    };
//<== carte du bloc

   map = new google.maps.Map(mapdiv,{
      center: new google.maps.LatLng(43.27, 3.5056),
      mapTypeControl: true,
      zoomControlOptions: {
         position: google.maps.ControlPosition.TOP_LEFT
      },
      mapTypeControlOptions: {
         style: google.maps.MapTypeControlStyle.DEFAULT,
         position: google.maps.ControlPosition.TOP_LEFT,
         mapTypeIds: [
            google.maps.MapTypeId.ROADMAP,
            google.maps.MapTypeId.TERRAIN,
            google.maps.MapTypeId.SATELLITE
         ]
     },
     streetViewControl:false,
     zoom :16,
     scrollwheel: false,
     scaleControl: true,
     disableDoubleClickZoom: true 
   });
   
   map.setMapTypeId(google.maps.MapTypeId.'.$cartyp.');

//== fabrique le marker et le event window
    function createMarker(marker,map,infoWindow,name,html,type,id,us) {
       if(type=="c") {
       var marker = new google.maps.Marker({
            position: point,
            map: map,
            title:us,
            icon: iconmbc
        })
        marker.set("id", id);
        marker.set("us", us);
       }
       if(type=="ac") {
       var marker = new google.maps.Marker({
            position: point,
            map: map,
            title:us,
            icon: iconac
        })
        marker.set("id", id);
        marker.set("us", us);
       }
       if(type=="member") {
       var marker = new google.maps.Marker({
            position: point,
            map: map,
            title:us,
            icon: iconmb,
            '.$js_dragtrue.'
        })
        marker.set("id", id);
        marker.set("us", us);
        }
      if(type=="wpo") {
       var marker = new google.maps.Marker({
            position: point,
            map: map,
            icon: icongeo
        })
        marker.set("id", id);
        marker.set("us", us);
       }

       marker.mytype = type;
       marker.myname = name;
       google.maps.event.addDomListener(marker, "click", function() {
       infoWindow.setContent(html);
       infoWindow.open(map, marker);
       });
       
       '.$js_dragfunc.'
       
//== save the info we need to use later for the sidebar
       gmarkers.push(marker);
       return marker;
    }
';

$ecr_scr .= '
    function updateMarker(Laa,loo,mod,id) {
        var getVars =  "&lng=" + loo + "&lat=" + Laa + "&mod=" + mod + "&uid=" + id ;
        window.location.href = "modules.php?ModPath=geoloc&ModStart=geoloc" + getVars;
    }
';

$mess_mb='';
if ($username !='') {
  if ($ue_lat !='' and $ue_long !='') {
    $infooo ='<div id="oldloc"><strong>'.geoloc_translate("Coordonnées enregistrées :").'</strong><br /><span class="sou_tit">Latitude :</span> '.$ue_lat.'<br /><span class="sou_tit">Longitude :</span> '.$ue_long.'<br /></div><br /><strong>'.geoloc_translate("Voulez vous changer pour :").'</strong><br />';
    $mess_mb = geoloc_translate('Cliquer sur la carte pour modifier votre position.');
  }
  else {
    $infooo = '<div id="newloc"><strong>'.geoloc_translate("Vous n\'êtes pas géoréférencé.").'</strong></div><br /><strong>'.geoloc_translate("Voulez vous le faire à cette position :").'</strong><br />';
    $mess_mb = geoloc_translate('Cliquer sur la carte pour définir votre géolocalisation.');
  }
$ecr_scr .= '
//== Ajoute un formulaire pour la localisation
    google.maps.event.addDomListener(map, "click", function(event) {
        var myLatLng = event.latLng;
        var lat = myLatLng.lat(); 
        var lng = myLatLng.lng();
        //=== creer un HTML DOM form element
        var inputForm = document.createElement("form");
        inputForm.setAttribute("action","");
        inputForm.onsubmit = function() {updateMarker(lat,lng,"neo",""); return false;};
        inputForm.innerHTML = \'<fieldset>\'
        + \'<legend><img src="'.$the_av_ch.'" img-thumbnail /> '.$username.' </legend>\'
        + \''.$infooo.'\'
        + \'<div id="lalo"><span class="sou_tit">'.geoloc_translate("Latitude").' : </span>\' + lat + \'<br /><span class="sou_tit">'.geoloc_translate("Longitude").' : </span>\' + lng + \'</div>\'
        + \'<input type="hidden" id="html" value="'.addslashes($user_from).'" />\'
        + \'<br />\'
        + \'<button type="submit" class ="btn btn-primary btn-sm">'.geoloc_translate("Enregistrez").'</button>\'
        + \'<input type="hidden" id="longitude" value="\' + lng + \'"/>\'
        + \'<input type="hidden" id="latitude" value="\' + lat + \'"/>\'
        + \'<input type="hidden" id="modgeo" value="neo"/>\'
        + \'</fieldset>\';
        infoWindow.setContent(inputForm);
        infoWindow.setPosition(myLatLng);
        infoWindow.open(map);
    });';
}
else
{$mess_mb='';}

//==> ecriture des markers pour les membres connectés et les anonymes 
$ecr_scr .= $mb_con_g.''.$ano_conn.''.$mb_gr ;
//<==
$ecr_scr .= '
document.getElementById("mess_info").innerHTML = \''.$mess_mb.' '.$mess_adm.'\';

show("member");
show("ac");
show("c");

show("wpo");

makeSidebar();

//===> infos cartes
// ==> classe de conversion unit
/* Written by Sparky Spider (http://sparkyspider.blogspot.com) */
function Coordinates() {
 // Properties
 this._latitude = 0;
 this._longitude = 0;
 this.LATITUDE = 0;
 this.LONGITUDE = 1;
 // Constuctor
 this.latitude = new DMSCalculator(this.LATITUDE, this);
 this.longitude = new DMSCalculator(this.LONGITUDE, this);
 // Methods
 this.setLatitude = function setLatitude(lat) {this._latitude = lat;};
 this.setLongitude = function setLongitude(lng) {this._longitude = lng;}
 this.getLatitude = function getLatitude() {return this._latitude;}
 this.getLongitude = function getLongitude() {return this._longitude;}
 // SubClasses
 function DMSCalculator (coordSet, object) {
  var degrees = new Array (0, 0);
  var minutes = new Array (0, 0);
  var seconds = new Array (0, 0);
  var direction = new Array (\' \', \' \');
  var lastValue = new Array (0, 0);
  var hundredths = new Array (0.0, 0.0);
  var calc = function calc(object) {
   var val = 0;
   if (coordSet == object.LATITUDE) {
    val = object._latitude;
   } else {
    val = object._longitude;
   }
   if (lastValue[coordSet] != val) {
    lastValue[coordSet] = val;
    if (val > 0) {
     direction[coordSet] = (coordSet == object.LATITUDE)?\'N\':\'E\';
    }
    else {
     direction[coordSet] = (coordSet == object.LATITUDE)?\'S\':\'W\';
    }
    val = Math.abs(val);
    degrees[coordSet] = parseInt (val);
    var leftover = (val - degrees[coordSet]) * 60;
    minutes[coordSet] = parseInt (leftover)
    leftover = (leftover - minutes[coordSet]) * 60;
    seconds[coordSet] = parseInt (leftover)
    hundredths[coordSet] = parseInt ((leftover - seconds[coordSet]) * 100);
   }
  }
  this.getDegrees = function getDegrees() {
   calc(object);
   return degrees[coordSet];
  }
  this.getMinutes = function getMinutes() {
   calc(object);
   return minutes[coordSet];
  }
  this.getSeconds = function getSeconds() {
   calc(object);
   return seconds[coordSet];
  }
  this.getDirection = function getDirection() {
   calc(object);
   return direction[coordSet];
  }
  this.getHundredths = function getHundredths() {
   calc(object);
   return hundredths[coordSet];
  }
  this.getSecondsDecimal = function getSecondsDecimal() {
   calc(object);
   return seconds[coordSet] + (hundredths[coordSet] / 100);
  }
  this.setDMS = function setDMS (degrees, minutes, seconds, direction) {
   var val = degrees + (minutes / 60) + (seconds / 3600);
   if (direction == \'W\' || direction == \'S\') {
    val *=-1;
   }
   if (coordSet == object.LATITUDE) {
    object._latitude = val;
   }
   else {
    object._longitude = val;
   } // if
  } // this.setDMS
 } // DMSCalculator
} // Coordinates
// <== classe de conversion unit
';


$ecr_scr .= '
    google.maps.event.addListener(map, "mousemove", function(point){
        afflatlon = point.latLng.toUrlValue(6);
        var lalo = afflatlon.split(",");
        var coords = new Coordinates();
        coords.setLatitude (lalo[0]);
        coords.setLongitude (lalo[1]);
        DMS_Lat = coords.latitude.getDegrees() + "&#xB0;" + coords.latitude.getMinutes() + \'\\\' \' + Math.round(coords.latitude.getSecondsDecimal()) + "&quot; " + coords.latitude.getDirection();
        DMS_Lng = coords.longitude.getDegrees() + "&#xB0;" + coords.longitude.getMinutes() + \'\\\' \' + Math.round(coords.longitude.getSecondsDecimal()) + "&quot; " + coords.longitude.getDirection();
        document.getElementById("mypoint").innerHTML = DMS_Lat+ " | "+ DMS_Lng;
    });
    
function setAllMap(map) {
  for (var i = 0; i < gmarkers.length; i++) {
    gmarkers[i].setMap(map);
  }
    for (var y = 0; y < gmarkers_g.length; y++) {
    gmarkers_g[y].setMap(map);
  }

}

}

hi = function(){$("#eye").toggleClass("fa-eye-slash",false).toggleClass("fa-eye",true).attr("onclick","showMarkers()");}
sh = function(){$("#eye").toggleClass("fa-eye-slash",true).toggleClass("fa-eye",false).attr("onclick","clearMarkers()");}
function clearMarkers() {setAllMap(null);hi();}
function showMarkers() {setAllMap(map);sh();}

$(\'[data-toggle="tab_ajax"]\').click(function(e) {
    var $this = $(this),
        loadurl = $this.attr(\'href\'),
        targ = $this.attr(\'data-target\');
    $.get(loadurl, function(data) {
        $(targ).html(data);
    });
    $this.tab(\'show\');
    return false;
});

$(document.body).attr("onload", "geoloc_load()");

//]]>
</script>';
//<== construction script pour google

//==> affichage
include ('header.php');
//==> ecriture des div contenants
$affi .= '
<h3 class="mb-4">Géolocalisation des membres du site<span class="float-right badge badge-default" title ="'.geoloc_translate('Membres du site').'" data-toggle="tooltip" data-placement="left">'.$total_membre.'</span></h3>
<div class="card mb-4">
   <div class=" card-header">
      <div class="row">
         <div class=" col-12 col-sm-9 ">
            <span style="font-size:1rem">
            <span class="badge badge-default mr-2">'.$mbcg.'</span><i title="'.geoloc_translate('Membre géoréférencé en ligne').'" data-toggle="tooltip" style="color:'.$mbgc_f_co.'; opacity:'.$mbgc_f_op.';" class="fa fa-'.str_replace('_', '-',strtolower($f_mbg)).' fa-2x"></i> <span class="mr-4"><input type="checkbox" title="'.geoloc_translate('Voir ou masquer membres géoréférencés en ligne').'" id="cbox" onclick="boxclick(this,\'c\')" /></span>
            <span class="badge badge-default mr-2">'.$acg.'</span><i title="'.geoloc_translate('Anonyme géoréférencé en ligne').'" data-toggle="tooltip" style="color:'.$acg_f_co.'; opacity:'.$acg_f_op.';" class="fa fa-'.str_replace('_', '-',strtolower($f_mbg)).' fa-2x"></i> <span class="mr-4" ><input  type="checkbox" title="'.geoloc_translate('Voir ou masquer anonymes géoréférencés').'" id="acbox" onclick="boxclick(this,\'ac\')" /></span>
            <span class="badge badge-default mr-2">'.$mbgr.'</span><i title="'.geoloc_translate('Membre géoréférencé').'" data-toggle="tooltip" style="color:'.$mbg_f_co.'; opacity:'.$mbg_f_op.';" class="fa fa-'.str_replace('_', '-',strtolower($f_mbg)).' fa-2x"></i> <span class="mr-4" ><input class="mr-4" type="checkbox" title="'.geoloc_translate('Voir ou masquer membres géoréférencés').'" id="memberbox" onclick="boxclick(this,\'member\')" /></span>
            </span>
         </div>
         <div class="col-12 col-sm-3 "><span class="float-right">'.$lkadm.'<a></a><a href="#" data-target="#sidebar" data-toggle="collapse" aria-expanded="false" aria-controls="collapsesidebar"><i class="fa fa-bars fa-lg ml-3"></i></a></span></div>
     </div>
   </div>
   <div>
   <div id="content">
      <div id="map-wrapper" >
         <div id="map">
            <div style="z-index:1000; position:absolute; right:4px; left:4px; top:3px; bottom:3px;" class=" alert alert-danger"><i style=" opacity:0.2;" class="fa fa-refresh fa-3x fa-pulse"></i>&nbsp '.geoloc_translate('Chargement en cours...Ou serveurs Google HS...Ou erreur...').'</div>
         </div>
         <div id="sidebar" class= "collapse">
            <ul id="sidebar-list list-group"></ul>
         </div>
      </div>
      </div>
   </div>
</div>

   <ul class="nav nav-tabs">
      <li class="nav-item"><a class="nav-link active" href="#infocart" data-toggle="tab_ajax">Infos carte</a></li>
      <li class="nav-item"><a class="nav-link" href="#geolocalisation" data-toggle="tab_ajax">Geolocalisation</a></li>
      <li class="nav-item"><a class="nav-link" href="modules/geoloc/doc/aide_geo.html" data-target="#aide" data-toggle="tab_ajax">Aide</a></li>
      <li class="nav-item"><a class="nav-link" href="#iplist" data-toggle="tab_ajax">Ip liste</a></li>
   </ul>
   <div class="tab-content">
      <div class="tab-pane fade show active" id="infocart">
         <span class="small text-muted float-right" id="mypoint"></span>
         <div id="mess_info" class=" col-12 mt-3"></div>
      </div>
      <div class="tab-pane fade" id="aide"></div>
      <div class="tab-pane fade" id="iplist">'.$test_ip.'</div>
      <div class="tab-pane fade" id="geolocalisation"><input type="checkbox" title="'.geoloc_translate('Voir ou masquer les waypoints').'" id="wpobox" onclick="boxclick(this,\'wpo\')" />&nbsp;'.geoloc_translate('Voir ou masquer les waypoints').' <span id="envoyer">Ex</span>
   </div>
</div>
';
//==> affichage des div contenants et écriture du script
echo $affi;
echo $ecr_scr;
include ('footer.php');

   switch ($op) {
       case 'wp':
   wp_fill();
    break;
   }
/*
<svg width="32" height="32" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
<path d="M320 256q0 72-64 110v1266q0 13-9.5 22.5t-22.5 9.5h-64q-13 0-22.5-9.5t-9.5-22.5v-1266q-64-38-64-110 0-53 37.5-90.5t90.5-37.5 90.5 37.5 37.5 90.5zm1472 64v763q0 25-12.5 38.5t-39.5 27.5q-215 116-369 116-61 0-123.5-22t-108.5-48-115.5-48-142.5-22q-192 0-464 146-17 9-33 9-26 0-45-19t-19-45v-742q0-32 31-55 21-14 79-43 236-120 421-120 107 0 200 29t219 88q38 19 88 19 54 0 117.5-21t110-47 88-47 54.5-21q26 0 45 19t19 45z"
 style="stroke: '.$mbg_t_co.'; stroke-width: '.$mbg_t_ep.'px; fill : '.$mbg_f_co.';"
/>
</svg>
*/

   
?>
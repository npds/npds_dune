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
/* geoloc_geoloc.php file 2008-2018 by Jean Pierre Barbary (jpb)        */
/* dev team : Philippe Revilliod (phr)                                  */
/************************************************************************/

/*
le géoréférencement des anonymes est basé sur un décodage des adresse ip
le géoréférencement des membres sur une géolocalisation exacte réalisé par l'utilisateur
la geolocalisation instantanée est réalisé par les api html5 de géolocalisation (!! elle n'est disponible que pour un site en https)
*/
if (!stristr($_SERVER['PHP_SELF'],"modules.php"))
   die();
if (strstr($ModPath,'..') || strstr($ModStart,'..') || stristr($ModPath, 'script') || stristr($ModPath, 'cookie') || stristr($ModPath, 'iframe') || stristr($ModPath, 'applet') || stristr($ModPath, 'object') || stristr($ModPath, 'meta') || stristr($ModStart, 'script') || stristr($ModStart, 'cookie') || stristr($ModStart, 'iframe') || stristr($ModStart, 'applet') || stristr($ModStart, 'object') || stristr($ModStart, 'meta')) 
   die();

global $pdst, $language, $title;
define('GEO', true);
include ('modules/'.$ModPath.'/geoloc_conf.php');
if (file_exists('modules/'.$ModPath.'/lang/geoloc.lang-'.$language.'.php'))
   include_once('modules/'.$ModPath.'/lang/geoloc.lang-'.$language.'.php');
else
   include_once('modules/'.$ModPath.'/lang/geoloc.lang-french.php');

$infooo='';
$js_dragtrue ='';
$js_dragfunc ='';
$lkadm ='';
$mess_adm ='';
$tab_ip ='';
$ano_ip='';
settype($op,'string');
settype($ipnb,'integer');

// admin tools
if(autorisation(-127)) {
$mess_adm ='<p class="text-danger">'.geoloc_translate('Rappel : vous êtes en mode administrateur !').'</p>';
$lkadm = '<a href="admin.php?op=Extend-Admin-SubModule&amp;ModPath=geoloc&amp;ModStart=admin/geoloc_set" title="'.geoloc_translate("Admin").'" data-toggle="tooltip"><i id="cogs" class="fa fa-cogs fa-lg"></i></a>';
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
        + \'<div id="lalo"><span class="text-muted">'.geoloc_translate("Latitude").' : </span>\' + lat + \'<br /><span class="text-muted">'.geoloc_translate("Longitude").' : </span>\' + lng + \'</div>\'
        + \'<input type="hidden" id="html" value="\' + id + \'" />\'
        + \'<br />\'
        + \'<button type="submit" class ="btn btn-primary btn-sm">'.geoloc_translate("Enregistrez").'</button>\'
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
   // IP géoréférencées
   if($geo_ip==1) {
      $affi_ip='';$ano_ip='';
      $tab_ip='';
      $result_ip = sql_query('SELECT * FROM '.$NPDS_Prefix.'ip_loc ORDER BY ip_visite DESC');
      $ipnb = sql_num_rows($result_ip);
      while ($row_ip = sql_fetch_array($result_ip)) {
         $ip_lt = $row_ip['ip_lat'];
         $ip_lg = $row_ip['ip_long'];
         $ip_ip1 = $row_ip['ip_ip'];
         $ip_country1 = $row_ip['ip_country'];
         $ip_code_country1 = $row_ip['ip_code_country'];
         $ip_city1 = $row_ip['ip_city'];
         $ip_visite = $row_ip['ip_visite'];
//         $ip_hote = @gethostbyaddr(urldecode($ip_ip1));

         if ($ip_lt != 0 and $ip_lg != 0) {
            //construction marker ip géoréférencés
            $ano_ip.= '
            var point = new google.maps.LatLng('.$ip_lt.','.$ip_lg.');
            var marker = createMarker(point,map,infoWindow,"<i style=\"color:'.$acg_f_co.';\" class=\"fa fa-desktop fa-lg mr-1\"></i>'.urldecode($ip_ip1).'", \'<div id="infowindow" style="white-space: nowrap;"><br /><i class="fa fa-tv fa-2x text-muted mr-1 align-middle"></i>IP @ '.urldecode($ip_ip1).'<br /><hr /><span class="text-muted">'.geoloc_translate("Visites").' : </span>'.$ip_visite.'</span><br /><span class="text-muted">'.geoloc_translate("Ville").' : </span>'.addslashes($ip_city1).'<br /><span class="text-muted">'.geoloc_translate("Pays").' : </span>'.addslashes($ip_country1).'<hr /><img src="'.$ch_img.'flags/'.strtolower($ip_code_country1).'.png" class="n-smil" alt="flag" /></p></div>\',\'ip\',\''.urldecode($row_ip['ip_ip']).'\'); 
            marker.setMap(map);
            bounds.extend(point);
            map.fitBounds(bounds);
            ';
            $tab_ip .='
         <p class="list-group-item list-group-item-action flex-column align-items-start">
            <span class="d-flex w-100 mt-1">
            <span><img class=" img-fluid n-ava-small mr-1 mb-1" src="'.$ch_img.'flags/'.strtolower($ip_code_country1).'.png" alt="'.$ip_country1.'"> '.urldecode($ip_ip1).'</span>
            <span class="ml-auto">
               <span class="badge badge-secondary ml-1" title="'.geoloc_translate("Visites").'" data-toggle="tooltip" data-placement="left" >'.$ip_visite.'</span>
            </span>
            </span>
            <span class="d-flex w-100">'.$ip_country1.' '.$ip_city1.'<span class="ml-auto"><i class="fa fa-desktop fa-lg text-muted"></i></span></span>
         </p>';
         }
      }
      $f = fopen("modules/".$ModPath."/include/iplist.html", "w");
      $w = fwrite($f, $tab_ip);
      @fclose($f);
      @chmod('modules/'.$ModPath.'/include/iplist.html', 0666);

   }
}

$username = $cookie[1];//recupere le username

if (array_key_exists('lat',$_GET)) $f_new_lat = floatval ($_GET['lat']);// lat du form de géoreferencement
if (array_key_exists('lng',$_GET)) $f_new_long = floatval ($_GET['lng']);// long du form de géoreferencement
if (array_key_exists('mod',$_GET)) $f_geomod = $_GET['mod'];
if (array_key_exists('uid',$_GET)) $f_uid = $_GET['uid'];

$av_ch = '';//chemin pour l'avatar

//Le membre
//cherche info user
if(isset($cookie)) {
   $result = sql_query('SELECT uid FROM '.$NPDS_Prefix.'users WHERE uname LIKE "'.$username.'"');
   $row = sql_fetch_array($result); 
   $uid = $row['uid'];
   // voir si user existe dans users_extend
   $resul = sql_query('SELECT uid FROM '.$NPDS_Prefix.'users_extend WHERE uid = "'.$uid.'"');
   $found = sql_num_rows($resul);
   //mise à jour users_extend si besoin
   if ($found == 0)
      $res = sql_query("INSERT INTO users_extend VALUES ('$uid','','','','','','','','','','','','','')");
}
//==> georeferencement utilisateur
if (array_key_exists('mod',$_GET)) {
   if ($f_new_lat !='' and $f_new_long !='' and $f_geomod="neo")
   sql_query('UPDATE '.$NPDS_Prefix.'users_extend SET '.$ch_lat.' = "'.$f_new_lat.'", '.$ch_lon.' = "'.$f_new_long.'" WHERE uid = "'.$uid.'"');
   if ($f_new_lat !='' and $f_new_long !='' and $f_geomod="mod")
   sql_query('UPDATE '.$NPDS_Prefix.'users_extend SET '.$ch_lat.' = "'.$f_new_lat.'", '.$ch_lon.' = "'.$f_new_long.'" WHERE uid = "'.$f_uid.'"');
}

$result = sql_query('SELECT * FROM '.$NPDS_Prefix.'users u LEFT JOIN '.$NPDS_Prefix.'users_extend ue ON u.uid = ue.uid WHERE uname LIKE "'.$username.'"');
while ($row = sql_fetch_array($result)) {
   $uid = $row['uid'];
   $user_from = $row['user_from'];
   $ue_lat = $row[''.$ch_lat.''];
   $ue_long = $row[''.$ch_lon.''];
   $user_avatar = $row['user_avatar'];

   //determine si c un avatar perso ou standard et fixe l'url de l'image
   if (preg_match('#\/#', $user_avatar) === 1)
      $the_av_ch = $user_avatar; else $the_av_ch = 'images/forum/avatar/'.$user_avatar;
}
//<== georeferencement utilisateur

//les membres

$mbgr = 0;
$membre = sql_query ('SELECT * FROM '.$NPDS_Prefix.'users u LEFT JOIN '.$NPDS_Prefix.'users_extend ue ON u.uid = ue.uid ORDER BY u.uname');
$total_membre = sql_num_rows($membre);//==> membres du site
$k=0;
$us_visit=array();
$cont_json = '';$mb_gr='';

while ($row = sql_fetch_array($membre)) {
   $us_uid = $row['uid'];
   $us_name = $row['name'];
   $us_uname = $row['uname'];
   $us_user_avatar = $row['user_avatar'];
   $us_lat = $row[''.$ch_lat.''];
   $us_long = $row[''.$ch_lon.''];
   $us_url = $row['url'];
   $us_mns =$row['mns'];
   $us_lastvisit = $row['user_lastvisit'];
   if ($us_lastvisit != '')  {
      $us_visit = getdate($us_lastvisit);
      $visit = $us_visit['mday'].'/'.$us_visit['mon'].'/'.$us_visit['year'];
   }
   $us_rs = $row['M2'];

   //==> determine si c avatar perso ou standard et fixe l'url de l'image
   if (preg_match('#\/#', $us_user_avatar) === 1)
       $av_ch = $us_user_avatar; else $av_ch = 'images/forum/avatar/'.$us_user_avatar;
   //==> les membres géoréferencés
   if ($us_lat !='') {
      $mbgr++;
      //=== menu fenetre info
      $imm = '<a href="user.php?op=userinfo&amp;uname='.$us_uname.'"  target="_blank" ><i class="fa fa-user fa-2x mr-2" title="'.translate("Profile").'" data-toggle="tooltip"></i></a>';
      if ($user)
         $imm .= '<a href="powerpack.php?op=instant_message&to_userid='.$us_uname.'" title="Envoyez moi un message interne"><i class="fa fa-envelope-o fa-2x mr-2"></i></a>';
      if ($us_url != '')
         $imm .= '<a href="'.$us_url.'" target="_blank" title="Visitez mon site"><i class="fa fa-external-link fa-2x mr-2"></i></a>';
      if ($us_mns != '')
         $imm .='<a href="minisite.php?op='.$us_uname.'" target="_blank" title="Visitez le minisite" data-toggle="tooltip"><i class="fa fa-desktop fa-2x mr-2"></i></a>';
 
      //==> construction du fichier json
      $cont_json .='{"lat":'.$us_lat.', "lng":'.$us_long.', "html":"<img src=\\"'.$av_ch.'\\" width=\\"32\\" height=\\"32\\" align=\\"middle\\" />&nbsp;'.addslashes($us_uname).'<br /><span class=\\"text-muted\\">'.geoloc_translate("Dernière visite").' : </span>'.$visit.'<br />", "label":"<span>'. addslashes($us_uname) .'</span>", "icon":"icon"},';
      //construction marker membre

      if($mark_typ !==1) $ic_sb_mbg ='<i style=\"color:'.$mbg_f_co.'; opacity:0.4;\" class=\"fa fa-'.strtolower($f_mbg).' fa-lg mr-1\"></i>';
      else $ic_sb_mbg ='<img src=\"'.$ch_img.$nm_img_mbg.'\" /> ';

      $mb_gr .='
      var point = new google.maps.LatLng('.$us_lat. ','.$us_long.');
      var u'.$us_uid.' = {lat: '.$us_lat. ', lng: '.$us_long.'};
      var marker = createMarker(point,map,infoWindow,"'.$ic_sb_mbg.'&nbsp;<span>'. addslashes($us_uname) .'</span>", \'<div id="infowindow" style="white-space: nowrap; text-align:center;">'.$imm.'<hr /><img class="img-thumbnail n-ava" src="'.$av_ch.'" align="middle" />&nbsp;<a href="user.php?op=userinfo&amp;uname='.$us_uname.'">'. addslashes($us_uname) .'</a><br /><div class="my-2">'.geoloc_translate("Dernière visite").' : '.$visit.'</div></div>\',\'member\',"'. addslashes($us_uid) .'","'. addslashes($us_uname) .'");
      bounds.extend(point);
      map.fitBounds(bounds);
      '; 
   }
   $k++;
}

if ($mbgr == 0)
   $cont_json .='{"lat":0, "lng":0, "html":"<img src=\\"'.$av_ch.'\\" width=\\"32\\" height=\\"32\\" align=\\"middle\\" />&nbsp;NPDS", "label":"NPDS", "icon":"icon"},';
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
$ac = 0; //==> anonyme connectés
$acng = 0; //==> anonyme connectés non géoréférencés
$acg = 0; //==> anonyme connectés géoréférencés
$mbcg = 0;

//nombre total on line
$result_con = sql_query ('SELECT * FROM '.$NPDS_Prefix.'session s');
$total_connect = sql_num_rows($result_con);

//jointure des tables pour centralisation infos membres
$result = sql_query ('SELECT * FROM '.$NPDS_Prefix.'session s
LEFT JOIN '.$NPDS_Prefix.'users u ON s.username = u.uname
LEFT JOIN '.$NPDS_Prefix.'users_extend ue ON u.uid = ue.uid');
$krs=0; $mb_con_g=''; $ano_conn='';
while ($row = sql_fetch_array($result)) {
   $session_guest = $row['guest'];
   $session_host_addr = $row['host_addr'];
   $users_name = $row['name'];
   $users_uname = $row['uname'];
   $users_user_avatar = $row['user_avatar'];
   $session_user_name = $row['username'];
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
      include_once('modules/reseaux-sociaux/reseaux-sociaux.conf.php');
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
      if ($us_url != '')
         $imm .= ' <a href="'.$us_url.'" target="_blank" title="Visitez mon site"><i class="fa fa-external-link fa-2x mr-2"></i></a>';
      if ($us_mns != '')
         $imm .='&nbsp;<a href="minisite.php?op='.$users_uname.'" target="_blank" title="Visitez le minisite" data-toggle="tooltip"><i class="fa fa-desktop fa-2x mr-2"></i></a>';

      //construction marker membre on line
      if($mark_typ !==1) $ic_sb_mbgc ='<i style=\"color:'.$mbgc_f_co.';\" class=\"fa fa-'.strtolower($f_mbg).' fa-lg animated faa-pulse mr-1\"></i>';
      else $ic_sb_mbgc ='<img src=\"'.$ch_img.$nm_img_mbcg.'\" /> ';
      $mb_con_g .='
      var point = new google.maps.LatLng('.$user_lat. ','.$user_long.');
      var marker = createMarker(point,map,infoWindow,"'.$ic_sb_mbgc.'<span>'. addslashes($users_uname) .'</span>", \'<div id="infowindow" style="white-space: nowrap; text-align:center;">'.$imm.'<hr /><img class="img-thumbnail n-ava" src="'.$av_ch.'" align="middle" /><br /><div class="mt-2"><a href="user.php?op=userinfo&amp;uname='.$users_uname.'">'. addslashes($users_uname) .'</a> @ '.$session_host_addr.'<hr />'.$my_rsos[$krs].'</div>\',\'c\',"'. addslashes($us_uid) .'","'. addslashes($users_uname) .'" );
      marker.setMap(map);
      bounds.extend(point);
      map.fitBounds(bounds);';
   }

   settype($mb_con_ng,'string');
   if ($session_guest !=1 and !$user_lat) {
      $mb_con_ng .= '&nbsp;'.$session_user_name.'<br />'; $mbcng++; // not use ?..
   }
//
//   if ($geo_ip == 1) {
   // Anonyme géoreferencé en ligne

   //==> cherche si l'adresse IP est dans la base
   $tres=sql_query("SELECT * FROM ".$NPDS_Prefix."ip_loc i WHERE ip_ip LIKE \"$session_host_addr\"");
   settype($test_ip,'string');
   $r=sql_num_rows($tres);
   if($r == 0) {
      if ($users_uname != $session_user_name) {
         $acg++;
         $test_ip .='<br /><i class="fa fa-tv fa-lg" title="IP non géoréférencé en ligne"></i> IP '.$acng.' <small>'.$session_host_addr.'</small>';
      }
   }
   while ($row1 = sql_fetch_array($tres)) {
      $ip_lat1 = $row1['ip_lat'];
      $ip_long1 = $row1['ip_long'];
      $ip_ip1 = $row1['ip_ip'];
      $ip_country1 = $row1['ip_country'];
      $ip_code_country1 = $row1['ip_code_country'];
      $ip_city1 = $row1['ip_city'];
      $ip_visi_pag = $row1['ip_visi_pag'];
      $ip_visite = $row1['ip_visite'];

      if ($ip_lat1 != 0 and $ip_long1 != 0) {
      if(!strstr($mb_con_g, $ip_ip1)) {
         $acg++;
         $ano_conn.= '
      var point = new google.maps.LatLng('.$ip_lat1.','.$ip_long1.');
      var marker = createMarker(point,map,infoWindow,"<i style=\"color:'.$acg_f_co.';\" class=\"fa fa-'.strtolower($f_mbg).' fa-lg animated faa-pulse mr-1\"></i>'.geoloc_translate('Anonyme').$ac.'", \'<div id="infowindow" style="white-space: nowrap;"><br /><i class="fa fa-tv fa-2x text-muted mr-1 align-middle"></i>'.geoloc_translate('Anonyme').' '.$ac.' @ '.$session_host_addr.' <br /><hr /><span class="text-muted">'.geoloc_translate("Hôte").' : </span>'.@gethostbyaddr($ip_ip1).'<br /><span class="text-muted">'.geoloc_translate("En visite ici").' : </span> '.$ip_visi_pag.'<br /><span class="text-muted">'.geoloc_translate("Visites").' : </span>['.$ip_visite.']<br /><span class="text-muted">'.geoloc_translate("Ville").' : </span>'.$ip_city1.'<br /><span class="text-muted">'.geoloc_translate("Pays").' : </span>'.$ip_country1.'<hr /><img src="'.$ch_img.'flags/'.strtolower($ip_code_country1).'.png" class="n-smil" alt="flag" /></p></div>\',\'ac\'); 
      marker.setMap(map);
      bounds.extend(point);
      map.fitBounds(bounds);
      ';
      }
         $ac++;
         //construction marker anonyme on line
      }
   }
   $krs++;
}

$olng = $mbcng+$acng;//==> on line non géoréférencés anonyme et membres
$olg = $mbcg+$acg;//==> on line géoréférencés anonyme et membres

//==> construction script pour google
$ecr_scr = '
<script type="text/javascript">
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
    icongeocode ={
        path: fontawesome.markers.THUMB_TACK,
        scale: '.$mbg_sc.',
        strokeWeight: '.$mbg_t_ep.',
        strokeColor: "'.$mbg_t_co.'",
        strokeOpacity: '.$mbg_t_op.',
        fillColor: "'.$mbg_f_co.'",
        fillOpacity: 1,
    },
    iconip ={
        path: fontawesome.markers.DESKTOP,
        scale: 0.3,
        strokeWeight: '.$acg_t_ep.',
        strokeColor: "'.$acg_t_co.'",
        strokeOpacity: '.$acg_t_op.',
        fillColor: "'.$acg_f_co.'",
        fillOpacity: 1,
    },';
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
            map.fitBounds(bounds);
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
        if (box.checked)
          show(type);
        else
          hide(type);
       makeSidebar();
    }

//==> construction sidebar
    function makeSidebar() {
        var html = "";
        for (var i=0; i<gmarkers.length; i++) {
        if (gmarkers[i].getVisible()) {
            html += \'<a class="list-group-item list-group-item-action" onmouseout="stopmyani(\' + i + \')" onmouseover="myho(\' + i + \')" href="javascript:myclick(\' + i + \')">\' + gmarkers[i].myname + \'</a>\';
            }
        }
      sideba.innerHTML = \'<div class="list-group"><a class="list-group-item text-muted" ><i class="fa fa-plug faa-flash animated text-danger mr-1"></i>'.geoloc_translate("En ligne").'<span class="badge badge-danger float-right">'.$total_connect.'</span>'.$test_ip.'</a>\'+ html +\'</div>\';
    }

   function myclick(i) {
      google.maps.event.trigger(gmarkers[i],"click");
      gmarkers[i].setMap(map);
      gmarkers[i].setAnimation(google.maps.Animation.BOUNCE);
      setTimeout(function(){ gmarkers[i].setAnimation(null); }, 3500);
   }
   function myho(i) {
      google.maps.event.trigger(gmarkers[i],"mouseover");
      gmarkers[i].setMap(map);
      gmarkers[i].setAnimation(google.maps.Animation.BOUNCE);
      setTimeout(function(){ gmarkers[i].setAnimation(null); }, 3500);
   }
   function stopmyani(i) {
      google.maps.event.trigger(gmarkers[i],"mouseout");
      gmarkers[i].setAnimation(null);
   }

function geoloc_load() {
   //==> carte du bloc
   $(document).ready(function() {
      if($("#map_bloc").length) {
         icon_bl = {
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
   });
   //<== carte du bloc

   map = new google.maps.Map(mapdiv,{
      center: new google.maps.LatLng(43.27, 3.5056),
      mapTypeControl: true,
      zoomControlOptions: {
         position: google.maps.ControlPosition.TOP_LEFT
      },
      mapTypeControlOptions: {
         style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
         position: google.maps.ControlPosition.TOP_LEFT,
         mapTypeIds: [
            google.maps.MapTypeId.ROADMAP,
            google.maps.MapTypeId.TERRAIN,
            google.maps.MapTypeId.SATELLITE,
            google.maps.MapTypeId.HYBRID
         ]
     },
     streetViewControl:false,
     fullscreenControl: true,
     fullscreenControlOptions: {
     position: google.maps.ControlPosition.BOTTOM_LEFT
     },
     zoom :10,
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
       if(type=="ip") {
       var marker = new google.maps.Marker({
            position: point,
            map: map,
            title:id,
            icon: iconip
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
      $infooo ='<div id="oldloc"><strong>'.geoloc_translate("Coordonnées enregistrées :").'</strong><br /><span class="text-muted">Latitude :</span> '.$ue_lat.'<br /><span class="text-muted">Longitude :</span> '.$ue_long.'<br /></div><br /><strong>'.geoloc_translate("Voulez vous changer pour :").'</strong><br />';
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
        + \'<div id="lalo"><span class="text-muted">'.geoloc_translate("Latitude").' : </span>\' + lat + \'<br /><span class="text-muted">'.geoloc_translate("Longitude").' : </span>\' + lng + \'</div>\'
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
   $mess_mb='';

//==> ecriture des markers pour les membres connectés et les anonymes 
$ecr_scr .= $mb_con_g.''.$ano_conn.''.$mb_gr.$ano_ip ;
//<==
$ecr_scr .= '
   document.getElementById("mess_info").innerHTML = \''.$mess_mb.' '.$mess_adm.'\';';
if($op)
   if ($op[0]=='u') //pour zoom sur user back with u1
      $ecr_scr .= '
   var listener = google.maps.event.addListener(map, "idle", function() { 
      map.setCenter('.$op.');
      if (map.getZoom() > 1) map.setZoom(16); 
      google.maps.event.removeListener(listener); 
   });';


if($op=='allip')
   $ecr_scr .= '
   hide("member");
   hide("ac");
   hide("c");
   //show("wpo");
   show("ip");';
else
   $ecr_scr .= '
   show("member");
   show("ac");
   show("c");
   //show("wpo");
   hide("ip");';

$ecr_scr .= '
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
            if (coordSet == object.LATITUDE)
               val = object._latitude;
            else
               val = object._longitude;
            if (lastValue[coordSet] != val) {
               lastValue[coordSet] = val;
               if (val > 0)
                  direction[coordSet] = (coordSet == object.LATITUDE)?\'N\':\'E\';
               else
                  direction[coordSet] = (coordSet == object.LATITUDE)?\'S\':\'W\';
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
            if (direction == \'W\' || direction == \'S\')
               val *=-1;
            if (coordSet == object.LATITUDE)
               object._latitude = val;
            else
               object._longitude = val;
         }
      }
   }
   // <== classe de conversion unit

   var co_unit="'.$co_unit.'";
   google.maps.event.addListener(map, "mousemove", function(point){
      afflatlon = point.latLng.toUrlValue(6);
      var lalo = afflatlon.split(",");
      var coords = new Coordinates();
      coords.setLatitude (lalo[0]);
      coords.setLongitude (lalo[1]);
      if (co_unit=="dms") {
         DMS_Lat = coords.latitude.getDegrees() + "&#xB0;" + coords.latitude.getMinutes() + \'\\\'\' + Math.round(coords.latitude.getSecondsDecimal()) + "&quot; " + coords.latitude.getDirection();
         DMS_Lng = coords.longitude.getDegrees() + "&#xB0;" + coords.longitude.getMinutes() + \'\\\'\' + Math.round(coords.longitude.getSecondsDecimal()) + "&quot; " + coords.longitude.getDirection();
      }
      else {
         DMS_Lat=lalo[0];
         DMS_Lng=lalo[1];
      }
      document.getElementById("mypoint").innerHTML = DMS_Lat+ " | "+ DMS_Lng;
   });

   var geocoder = new google.maps.Geocoder();
   document.getElementById("geocode_submit").addEventListener("click", function() {
      geocodeAddress(geocoder, map);
   });
} //<= geoloc_load
   function setAllMap(map) {
     for (var i = 0; i < gmarkers.length; i++) {
       gmarkers[i].setMap(map);
     }
       for (var y = 0; y < gmarkers_g.length; y++) {
       gmarkers_g[y].setMap(map);
     }
   }

   hi = function(){$("#eye").toggleClass("fa-eye-slash",false).toggleClass("fa-eye",true).attr("onclick","showMarkers()");}
   sh = function(){$("#eye").toggleClass("fa-eye-slash",true).toggleClass("fa-eye",false).attr("onclick","clearMarkers()");}
   function clearMarkers() {setAllMap(null);hi();}
   function showMarkers() {setAllMap(map);sh();}

   geocode_markers=[];
   geocode_adresses=[];

function geocodeAddress(geocoder, map) {
  var address = document.getElementById("address").value;
  geocoder.geocode({"address": address}, function(results, status) {
    if (status === google.maps.GeocoderStatus.OK) {
      map.setCenter(results[0].geometry.location);
      var marker = new google.maps.Marker({
        map: map,
        icon:icongeocode,
        animation: google.maps.Animation.DROP,
        position: results[0].geometry.location
      });
      geocode_adresses.push(address);
      geocode_markers.push(marker);
    }
    else
      bootbox.alert("'.geoloc_translate("Géocodage a échoué pour la raison suivante").' : " + status);
  });
}

function setMapOnAll_geo(map) {
  for (var i = 0; i < geocode_markers.length; i++) {
    geocode_markers[i].setMap(map);
  }
}
function clearMarkers_geo() {
  setMapOnAll_geo(null);
}
function showMarkers_geo() {
  setMapOnAll_geo(map);
}
function deleteMarkers_geo() {
  clearMarkers_geo();
  geocode_markers = [];
}

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

$(function(){
   $(\'.n-filtrable p\').each(function(){
      $(this).attr(\'data-search-term\', $(this).text().toLowerCase());
      $(\'.n_filtrbox\').on(\'keyup\', function(){
         var searchTerm = $(this).val().toLowerCase();
         $(\'.n-filtrable p\').each(function(){
            if ($(this).filter(\'[data-search-term *= \' + searchTerm + \']\').length > 0 || searchTerm.length < 1)
               $(this).show();
            else
               $(this).hide();
          });
      });
   });
});

$(document).ready(function() {
    checkSize();
    $(window).resize(checkSize);
});
function checkSize(){
    if ($(".n-media-repere").css("color") == "rgb(255, 0, 0)" ){
       $("#sidebar").removeClass("show");
    }
}

$(document.body).attr("onload", "geoloc_load()");

//]]>
</script>';
//<== construction script pour google

//==> affichage
include ('header.php');
//==> ecriture des div contenants
      if($mark_typ !==1) {
         $ic_b_mbg ='<i title="'.geoloc_translate('Membre géoréférencé').'" data-toggle="tooltip" style="color:'.$mbg_f_co.'; opacity:'.$mbg_f_op.';" class="fa fa-'.str_replace('_', '-',strtolower($f_mbg)).' fa-2x align-middle"></i>';
         $ic_b_mbgc ='<i title="'.geoloc_translate('Membre géoréférencé en ligne').'" data-toggle="tooltip" style="color:'.$mbgc_f_co.'; opacity:'.$mbgc_f_op.';" class="fa fa-'.str_replace('_', '-',strtolower($f_mbg)).' fa-2x align-middle"></i>';
         $ic_b_acg ='<i title="'.geoloc_translate('Anonyme géoréférencé en ligne').'" data-toggle="tooltip" style="color:'.$acg_f_co.'; opacity:'.$acg_f_op.';" class="fa fa-'.str_replace('_', '-',strtolower($f_mbg)).' fa-2x align-middle"></i>';
      }
      else {
         $ic_b_mbg ='<img src="'.$ch_img.$nm_img_mbg.'" title="'.geoloc_translate('Membre géoréférencé').'" data-toggle="tooltip" alt="'.geoloc_translate('Membre géoréférencé').'" /> ';
         $ic_b_mbgc ='<img src="'.$ch_img.$nm_img_mbcg.'" title="'.geoloc_translate('Membre géoréférencé en ligne').'" data-toggle="tooltip" alt="'.geoloc_translate('Membre géoréférencé en ligne').'" /> ';
         $ic_b_acg ='<img src="'.$ch_img.$nm_img_acg.'" title="'.geoloc_translate('Anonyme géoréférencé en ligne').'" data-toggle="tooltip" alt="'.geoloc_translate('Anonyme géoréférencé en ligne').'" /> ';
      }

$affi='';
$affi .= '
<h3 class="mb-4">'.geoloc_translate("Géolocalisation des membres du site").'<span class="float-right badge badge-secondary" title ="'.geoloc_translate('Membres du site').'" data-toggle="tooltip" data-placement="left">'.$total_membre.'</span></h3>
<div class="card mb-4">
   <div class="d-flex flex-row justify-content-start">
      <div class="p-2">
         <span class="badge badge-secondary mr-1">'.$mbcg.'</span>'.$ic_b_mbgc.' <span class="mr-2"><input type="checkbox" data-toggle="tooltip" title="'.geoloc_translate('Voir ou masquer membres géoréférencés en ligne').'" id="cbox" onclick="boxclick(this,\'c\')" /></span>
      </div>';
//if($geo_ip==1) 
   $affi .='
      <div class="p-2">
         <span class="badge badge-secondary mr-1">'.$acg.'</span>'.$ic_b_acg.' <span class="mr-2" ><input  type="checkbox" data-toggle="tooltip" title="'.geoloc_translate('Voir ou masquer anonymes géoréférencés').'" id="acbox" onclick="boxclick(this,\'ac\')" /></span>
      </div>';
$affi .= '
      <div class="p-2">
         <span class="badge badge-secondary mr-1">'.$mbgr.'</span>'.$ic_b_mbg.' <span class="mr-2" ><input class="mr-4" type="checkbox" data-toggle="tooltip" title="'.geoloc_translate('Voir ou masquer membres géoréférencés').'" id="memberbox" onclick="boxclick(this,\'member\')" /></span>
      </div>
      <div class="ml-auto p-2">
         <span class="float-right"><button class="navbar-light navbar-toggler" href="#" data-target="#sidebar" data-toggle="collapse" aria-expanded="true" aria-controls="collapsesidebar"><span class="navbar-toggler-icon"></span></button></span>
      </div>
   </div>
   <span class="n-media-repere"></span>
   <div id="content">
      <div id="map-wrapper" >
         <div id="map">
            <div style="z-index:1000; position:absolute; right:4px; left:4px; top:3px; bottom:3px;" class=" alert alert-danger"><i style=" opacity:0.2;" class="fa fa-refresh fa-3x fa-pulse"></i>&nbsp '.geoloc_translate('Chargement en cours...Ou serveurs Google HS...Ou erreur...').'</div>
         </div>
         <div id="sidebar" class= "collapse show col-sm-4 col-md-3 col-6 px-0">
            <ul id="sidebar-list" class="list-group"></ul>
         </div>
      </div>
   </div>
   <div class=" p-2">
      <button id="eye" onclick="clearMarkers();" class="btn-link fa fa-eye-slash fa-lg mr-2" title="" data-toggle="tooltip"></button>
      <span>'.$lkadm.'</span>
      <span class="small text-muted float-right" id="mypoint"></span>
   </div>
</div>

   <ul class="nav nav-tabs">
      <li class="nav-item"><a class="nav-link active" href="#infocart" data-toggle="tab_ajax"><span class="d-sm-none"><i class=" fa fa-globe fa-lg mr-2"></i><i class=" fa fa-info fa-lg"></i></span><span class="d-none d-sm-inline">'.geoloc_translate("Infos carte").'</span></a></li>
      <li class="nav-item"><a class="nav-link" href="#geocodage" data-toggle="tab_ajax"><span class="d-sm-none"><i class=" fa fa-globe fa-lg mr-2"></i><i class=" fa fa-map-marker fa-lg"></i></span><span class="d-none d-sm-inline">'.geoloc_translate("Géocodage").'</span></a></li>
      <li class="nav-item"><a class="nav-link" href="modules/geoloc/doc/aide_geo.html" data-target="#aide" data-toggle="tab_ajax"><span class="d-sm-none"><i class=" fa fa-globe fa-lg mr-2"></i><i class=" fa fa-question fa-lg"></i></span><span class="d-none d-sm-inline">'.geoloc_translate("Aide").'</span></a></li>';
if(autorisation(-127) and $geo_ip==1)
   $affi .= '
      <li class="nav-item"><a class="nav-link " href="#geolocalisation" data-toggle="tab_ajax"><span class="d-sm-none"><i class=" fa fa-globe fa-lg mr-2"></i><i class=" fa fa-tv fa-lg"></i></span><span class="d-none d-sm-inline">'.geoloc_translate("Ip liste").'</span></a></li>';
$affi .= '
   </ul>
   <div class="tab-content">
      <div class="tab-pane fade show active" id="infocart">
         <div id="mess_info" class=" col-12 mt-3"></div>
      </div>
      <div class="tab-pane fade" id="aide"></div>
      <div class="tab-pane fade" id="geocodage">
         <div class="form-group mt-3">
            <div class="input-group">
               <span class="input-group-btn">
                  <button id="geocode_submit" class="btn btn-primary" type="button">'.geoloc_translate("Géocoder").'</button>
               </span>
               <input id="address" type="textbox" class="form-control" placeholder="'.geoloc_translate("Entrez une adresse").'..." />
               <span class="input-group-btn"><button class="btn btn-outline-danger" type="button" id="trash" onclick="deleteMarkers_geo();"><i class="fa fa-trash-o fa-lg"></i></button></span>
            </div>
         </div>
      </div>
      <div class="tab-pane fade mt-2" id="geolocalisation">
         <h5 class="mt-3">
            <i title="'.geoloc_translate('IP géoréférencées').'" data-toggle="tooltip" style="color:'.$acg_t_co.'; opacity:'.$acg_t_op.';" class="fa fa-desktop fa-lg mr-2 align-middle"></i>
            <span class="badge badge-secondary mr-2 float-right">'.$ipnb.'</span>
         </h5>
         <div class="custom-control custom-checkbox my-2">
            <input class="custom-control-input" type="checkbox" title="'.geoloc_translate('Voir ou masquer les IP').'" id="ipbox" onclick="boxclick(this,\'ip\')" />
            <label class="custom-control-label" for="ipbox">'.geoloc_translate('Voir ou masquer les IP').'</label>
         </div>
         <div class="form-group row">
            <div class="col-sm-12">
               <input type="text" class="mb-3 form-control form-control-sm n_filtrbox" placeholder="Filtrer les résultats" />
            </div>
         </div>
         <div class="n-filtrable">
            '.$tab_ip.'
         </div>
      </div>
      <div class="tab-pane fade" id="tracker">
         <input type="checkbox" title="'.geoloc_translate('Voir ou masquer les waypoints').'" id="wpobox" onclick="boxclick(this,\'wpo\')" />&nbsp;'.geoloc_translate('Voir ou masquer les waypoints').' <span id="envoyer">Ex</span>
         <input type="checkbox" title="'.geoloc_translate('Activer désactiver la géolocalisation').'" id="geolobox" onclick="" />&nbsp;'.geoloc_translate('Activer désactiver la géolocalisation').'
      </div>
   </div>';
//==> affichage des div contenants et écriture du script
echo $affi;
echo $ecr_scr;
include ('footer.php');

switch ($op) {
   case 'wp':
      wp_fill();
   break;
}
?>
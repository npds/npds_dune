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
/* module geoloc version 4.0                                            */
/* geoloc_geoloc.php file 2008-2021 by Jean Pierre Barbary (jpb)        */
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
$ip_o='';
settype($op,'string');
settype($ipnb,'integer');
$date_jour = date('Y-m-d');

// admin tools
if(autorisation(-127)) {
$mess_adm ='<p class="text-danger">'.geoloc_translate('Rappel : vous êtes en mode administrateur !').'</p>';
$lkadm = '<a href="admin.php?op=Extend-Admin-SubModule&amp;ModPath=geoloc&amp;ModStart=admin/geoloc_set" title="'.geoloc_translate("Admin").'" data-toggle="tooltip"><i id="cogs" class="fa fa-cogs fa-lg"></i></a>';
$infooo = geoloc_translate('Modification administrateur');
//$js_dragtrue ='draggable:true,';
//$js_dragfunc ='';

   // IP géoréférencées
   if($geo_ip==1) {
      $affi_ip='';
      $tab_ip=''; $ip_o= 'const ip_features=[';
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
            $ip_o .= '[['.$ip_lg.','.$ip_lt.'],"'.urldecode($ip_ip1).'","'.$ip_visite.'"],';
            $tab_ip .='
         <p class="col-sm-12 col-md-3 p-2  border rounded flex-column align-items-start list-group-item-action">
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
      $ip_o = trim($ip_o,',').'
   ];';
      $f = fopen("modules/".$ModPath."/include/iplist.html", "w");
      $w = fwrite($f, $tab_ip);
      @fclose($f);
      @chmod('modules/'.$ModPath.'/include/iplist.html', 0666);

   }
}
$username = isset($cookie) ? $cookie[1] : '';

if (array_key_exists('lat',$_GET)) $f_new_lat = floatval ($_GET['lat']);// lat du form de géoreferencement
if (array_key_exists('lng',$_GET)) $f_new_long = floatval ($_GET['lng']);// long du form de géoreferencement
if (array_key_exists('mod',$_GET)) $f_geomod = removeHack($_GET['mod']);
if (array_key_exists('uid',$_GET)) $f_uid = removeHack($_GET['uid']);

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
$cont_json = ''; $cont_geojson = ''; $mbr_geo_off ='const mbr_features=['; $mbr_geo_off_v='';

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
      $imm = '<a class="tooltipbyclass" href="user.php?op=userinfo&amp;uname='.$us_uname.'" title="'.translate("Profil").'" target="_blank" ><i class="fa fa-user fa-lg mr-3" ></i></a>';
      if ($user)
         $imm .= '<a class="tooltipbyclass" href="powerpack.php?op=instant_message&to_userid='.$us_uname.'" title="'.geoloc_translate("Envoyez un message interne").'"><i class="far fa-envelope fa-lg mr-3"></i></a>';
      if ($us_url != '')
         $imm .= '<a class="tooltipbyclass" href="'.$us_url.'" target="_blank" title="'.geoloc_translate("Visitez le site").'"><i class="fas fa-external-link-alt fa-lg mr-3"></i></a>';
      if ($us_mns != '')
         $imm .='<a class="tooltipbyclass" href="minisite.php?op='.$us_uname.'" target="_blank" title="'.geoloc_translate("Visitez le minisite").'"><i class="fa fa-desktop fa-lg mr-3"></i></a>';

      //==> construction du fichier json
      $cont_json .='{"lat":'.$us_lat.', "lng":'.$us_long.', "html":"<img src=\\"'.$av_ch.'\\" width=\\"32\\" height=\\"32\\" align=\\"middle\\" />&nbsp;'.addslashes($us_uname).'<br /><span class=\\"text-muted\\">'.geoloc_translate("Dernière visite").' : </span>'.$visit.'<br />", "label":"<span>'. addslashes($us_uname) .'</span>", "icon":"icon"},';
      $cont_geojson .='
   {"type": "Feature", "id": "u_'.$us_uid.'", "properties": { "name": "'.addslashes($us_uname).'", "description": ""}, "geometry": { "type": "Point", "coordinates": ['.$us_long.','.$us_lat.'] } },';

      //construction marker membre
      if($mark_typ !==1) $ic_sb_mbg ='<i style=\"color:'.$mbg_f_co.'; opacity:0.4;\" class=\"fa fa-'.strtolower($f_mbg).' fa-lg mr-1\"></i>';
      else $ic_sb_mbg ='<img src=\"'.$ch_img.$nm_img_mbg.'\" /> ';

      $mbr_geo_off .= '
      [['.$us_long.','.$us_lat.'], "u'.$us_uid.'","'. addslashes($us_uname) .'","'.$av_ch.'","'.$visit.'","'.addslashes($imm).'"],';
      $mbr_geo_off_v .= '
   var u'.$us_uid.' = ol.proj.transform(['.$us_long.', '.$us_lat.'], "EPSG:4326", "EPSG:3857");';
   }
   $k++;
}
$mbr_geo_off = trim($mbr_geo_off,',').'
   ];';

if ($mbgr == 0)
   $cont_json .='{"lat":0, "lng":0, "html":"<img src=\\"'.$av_ch.'\\" width=\\"32\\" height=\\"32\\" align=\\"middle\\" />&nbsp;NPDS", "label":"NPDS", "icon":"icon"},';

$ent_geojson = '{ "type": "FeatureCollection", "features": ['.preg_replace("#,$#","\n]}", $cont_geojson);
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
$f = fopen("modules/".$ModPath."/include/user.geojson", "w");
$w = fwrite($f, $ent_geojson);
fclose($f);

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

// récupération des infos des connectés (membres et ano)
$result = sql_query ('SELECT * FROM '.$NPDS_Prefix.'session s
LEFT JOIN '.$NPDS_Prefix.'users u ON s.username = u.uname
LEFT JOIN '.$NPDS_Prefix.'users_extend ue ON u.uid = ue.uid');
$krs=0; $mb_con_g=''; $ano_o_conn='const ano_features=['; $mbr_geo_on='const mbrOn_features=['; $mbr_geo_on_v ='';
while ($row = sql_fetch_array($result)) {
   $users_uid = $row['uid'];//
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
                  $my_rs.='<a href="'.$v1[1].$y1[1].'" target="_blank"><i class="fab fa-'.$v1[2].' fa-2x text-primary mr-2"></i></a>&nbsp;';
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
      $imm = ' <a class="tooltipbyclass" href="user.php?op=userinfo&amp;uname='.$users_uname.'" title="'.translate("Profil").'" target="_blank" ><i class="fa fa-user fa-2x mr-2"></i></a>';
      if ($user)
         $imm .= ' <a class="tooltipbyclass" href="powerpack.php?op=instant_message&to_userid='.$users_uname.'" title="'.geoloc_translate("Envoyez un message interne").'"><i class="far fa-envelope fa-2x mr-2"></i></a>';
      if ($us_url != '')
         $imm .= ' <a class="tooltipbyclass" href="'.$us_url.'" target="_blank" title="'.geoloc_translate("Visitez le site").'"><i class="fas fa-external-link-alt fa-2x mr-2"></i></a>';
      if ($us_mns != '')
         $imm .='&nbsp;<a class="tooltipbyclass" href="minisite.php?op='.$users_uname.'" target="_blank" title="'.geoloc_translate("Visitez le minisite").'"><i class="fa fa-desktop fa-2x mr-2"></i></a>';

      //construction marker membre on line
      if($mark_typ !==1) $ic_sb_mbgc ='<i style=\"color:'.$mbgc_f_co.';\" class=\"fa fa-'.strtolower($f_mbg).' fa-lg animated faa-pulse mr-1\"></i>';
      else $ic_sb_mbgc ='<img src=\"'.$ch_img.$nm_img_mbcg.'\" /> ';
      $mb_con_g .='';
      $mbr_geo_on .= '[['.$user_long.','.$user_lat.'], "u'.$users_uid.'","'. addslashes($users_uname) .'","'.$av_ch.'", "'.addslashes($imm).'","'.addslashes($my_rsos[$krs]).'"],';
   }

   settype($mb_con_ng,'string');
   if ($session_guest !=1 and !$user_lat) {
      $mb_con_ng .= '&nbsp;'.$session_user_name.'<br />'; $mbcng++; // not use ?..
   }

   //==> cherche si l'adresse IP est dans la base
   $tres=sql_query("SELECT * FROM ".$NPDS_Prefix."ip_loc i WHERE ip_ip LIKE \"$session_host_addr\"");
   settype($test_ip,'string');
   $r=sql_num_rows($tres);
   if($r == 0) {
      if ($users_uname != $session_user_name) {
         $acg++; $acng++;
         $test_ip .='<a title="IP non géoréférencé en ligne" class="sb_ano list-group-item  py-1">IP '.$acng.' <small>'.$session_host_addr.'</small></a>';
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
            $ano_o_conn.= '
         [['.$ip_long1.','.$ip_lat1.'],"'.$ip_ip1.'","'.$session_host_addr.'","'.$ip_visi_pag.'","'.$ip_visite.'","'.$ip_city1.'","'.$ip_country1.'","'.$ch_img.'flags/'.strtolower($ip_code_country1).'","'.@gethostbyaddr($ip_ip1).'"],';
         }
         $ac++;
         //construction marker anonyme on line
      }
   }
   $krs++;
}
$ano_o_conn = trim($ano_o_conn,',').'
   ];';
$mbr_geo_on = trim($mbr_geo_on,',').'
   ];';

$olng = $mbcng+$acng;//==> on line non géoréférencés anonyme et membres
$olg = $mbcg+$acg;//==> on line géoréférencés anonyme et membres

$fond_provider=array(
   ['OSM', geoloc_translate("Plan").' (OpenStreetMap)'],
   ['toner', geoloc_translate("Noir et blanc").' (Stamen)'],
   ['watercolor', geoloc_translate("Dessin").' (Stamen)'],
   ['terrain', geoloc_translate("Relief").' (Stamen)'],
   ['modisterra', geoloc_translate("Satellite").' (NASA)'],
   ['natural-earth-hypso-bathy', geoloc_translate("Relief").' (mapbox)'],
   ['geography-class', geoloc_translate("Carte").' (mapbox)'],
   ['Road', geoloc_translate("Plan").' (Bing maps)'],
   ['Aerial', geoloc_translate("Satellite").' (Bing maps)'],
   ['AerialWithLabels', geoloc_translate("Satellite").' et label (Bing maps)'],
   ['sat-google', geoloc_translate("Satellite").' (Google maps)']
);
if($api_key_bing=='' and $api_key_mapbox=='') {unset($fond_provider[5],$fond_provider[6],$fond_provider[7],$fond_provider[8],$fond_provider[9]);}
elseif($api_key_bing=='') {unset($fond_provider[7],$fond_provider[8],$fond_provider[9]);}
elseif($api_key_mapbox=='') {unset($fond_provider[5],$fond_provider[6]);}

$fonts_svg=array(
   ['user','uf007','Utilisateur'],
   ['userCircle','uf2bd','Utilisateur en cercle'],
   ['userCircle','uf2be','Utilisateur en cercle'],
   ['users','uf0c0','Utilisateurs'],
   ['heart','uf004','Coeur'],
   ['thumbtack','uf08d','Punaise'],
   ['circle','uf111','Cercle'],
   ['camera','uf030','Appareil photo'],
   ['anchor','uf13d','Ancre'],
   ['mapMarker','uf041','Marqueur carte'],
   ['plane','uf072','Avion'],
   ['star','uf005','Etoile'],
   ['home','uf015','Maison'],
   ['flag','uf024','Drapeau'],
   ['crosshairs','uf05b','Croix'],
   ['asterisk','uf069','Astérisque'],
   ['fire','uf06d','Flamme'],
   ['comment','uf075','Commentaire']
);

//==> construction js
$ecr_scr = '
<script type="text/javascript">
//<![CDATA[
   var map;
   var user_markers={};// need to have on debug console
   var ano_markers={};
   var dd = new Date().toISOString().split("T");

   if (!$("link[href=\'/lib/ol/ol.css\']").length)
      $("head link[rel=\'stylesheet\']").last().after("<link rel=\'stylesheet\' href=\'/lib/ol/ol.css\' type=\'text/css\' media=\'screen\'>");
   if (!$("link[href=\'modules/geoloc/include/css/geoloc_style.css\']").length)
      $("head link[rel=\'stylesheet\']").last().after("<link rel=\'stylesheet\' href=\'/modules/geoloc/include/css/geoloc_style.css\' type=\'text/css\' media=\'screen\'>");

   $(function () {
   //==>  affichage des coordonnées...
      var co_unit="'.$co_unit.'";
      var mousePositionControl = new ol.control.MousePosition({';
$ecr_scr .= $co_unit=='dms'?
      '
        coordinateFormat: function(coord) {return ol.coordinate.toStringHDMS(coord,2)},':
      '
        coordinateFormat: function(coord) {return ol.coordinate.format(coord, "Lat. {y}, Long. {x}", 4);},';
$ecr_scr .= '
        projection: "EPSG:4326",
        className: "custom-mouse-position",
        undefinedHTML: "&nbsp;"
      });
   //<==
   '.$mbr_geo_on.$ano_o_conn.$mbr_geo_off.$mbr_geo_off_v.$ip_o.'
   var popup = new ol.Overlay({
     element: document.getElementById("ol_popup"),
     offset : [0,-10]
   });

   var src_anno = new ol.source.Vector({});
   var src_anno_length = ano_features.length;
   for (var i = 0; i < src_anno_length; i++){
      var iconFeature = new ol.Feature({
         geometry: new ol.geom.Point(ol.proj.transform(ano_features[i][0], "EPSG:4326","EPSG:3857")),
         ip: ano_features[i][1],
         ip_hostaddr: ano_features[i][2],
         ip_visitpage: ano_features[i][3],
         ip_visit: ano_features[i][4],
         ip_city: ano_features[i][5],
         ip_country: ano_features[i][6],
         ip_flagsrc: ano_features[i][7],
         ip_hote: ano_features[i][8],
      });
         iconFeature.setId(("a"+i));
         src_anno.addFeature(iconFeature);
   }
   var src_user = new ol.source.Vector({});
   var src_user_length = mbr_features.length;
   for (var i = 0; i < src_user_length; i++){
      var iconFeature = new ol.Feature({
         geometry: new ol.geom.Point(ol.proj.transform(mbr_features[i][0], "EPSG:4326","EPSG:3857")),
         pseudo: mbr_features[i][2],
         ava: mbr_features[i][3],
         lastvisit: mbr_features[i][4],
         userlinks: mbr_features[i][5]
      });
      iconFeature.setId(mbr_features[i][1]);
      src_user.addFeature(iconFeature);
   }
   var src_userOn = new ol.source.Vector({});
   var src_userOn_length = mbrOn_features.length;
   for (var i = 0; i < src_userOn_length; i++){
      var iconFeature = new ol.Feature({
         geometry: new ol.geom.Point(ol.proj.transform(mbrOn_features[i][0], "EPSG:4326","EPSG:3857")),
         pseudo: mbrOn_features[i][2],
         ava: mbrOn_features[i][3],
         userlinks : mbrOn_features[i][4],
         social: mbrOn_features[i][5]
      });
      iconFeature.setId("on"+mbrOn_features[i][1]);
      src_userOn.addFeature(iconFeature);
   }';
if(autorisation(-127) and $geo_ip==1)
   $ecr_scr .='
   var src_ip = new ol.source.Vector({});
   var src_ip_length = ip_features.length;
   for (var i = 0; i < src_ip_length; i++){
      var iconFeature = new ol.Feature({
         geometry: new ol.geom.Point(ol.proj.transform(ip_features[i][0], "EPSG:4326","EPSG:3857")),
         ip: ip_features[i][1],
         visit: ip_features[i][2],
      });
      iconFeature.setId(("ip"+i));
      src_ip.addFeature(iconFeature);
   }';
$ecr_scr .='
   var src_countries = new ol.source.Vector({
     url: "/modules/geoloc/include/countries.geojson",
     format: new ol.format.GeoJSON()
   });
   ';

if($mark_typ !==1) {
   $fafont=''; $fafont_js='';
   foreach ($fonts_svg as $v) {
      if($v[0]==$f_mbg) {
         $fafont = '&#x'.substr($v[1],1).';';
         $fafont_js= '\\'.$v[1];
      }
   }
   $ic_b_mbg ='<span title="'.geoloc_translate('Membre géoréférencé').'" data-toggle="tooltip" style="font-size:1.8rem; color:'.$mbg_f_co.';" class="fa fa align-middle">'.$fafont.'</span>';
   $ic_b_mbgc ='<span title="'.geoloc_translate('Membre géoréférencé en ligne').'" data-toggle="tooltip" style="font-size:1.8rem; color:'.$mbgc_f_co.';" class="fa fa-2x align-middle">'.$fafont.'</span>';
   $ic_b_acg ='<span title="'.geoloc_translate('Anonyme géoréférencé en ligne').'" data-toggle="tooltip" style="font-size:1.8rem; color:'.$acg_f_co.';" class="fa fa-2x align-middle">'.$fafont.'</span>';
}
else {
   $ic_b_mbg ='<img src="'.$ch_img.$nm_img_mbg.'" title="'.geoloc_translate('Membre géoréférencé').'" data-toggle="tooltip" alt="'.geoloc_translate('Membre géoréférencé').'" /> ';
   $ic_b_mbgc ='<img src="'.$ch_img.$nm_img_mbcg.'" title="'.geoloc_translate('Membre géoréférencé en ligne').'" data-toggle="tooltip" alt="'.geoloc_translate('Membre géoréférencé en ligne').'" /> ';
   $ic_b_acg ='<img src="'.$ch_img.$nm_img_acg.'" title="'.geoloc_translate('Anonyme géoréférencé en ligne').'" data-toggle="tooltip" alt="'.geoloc_translate('Anonyme géoréférencé en ligne').'" /> ';
}

if($mark_typ !== 1) { // marker svg
   $ecr_scr .='
      //==> marker svg
      function pointStyleFunction(feature, resolution) {
        return new ol.style.Style({
          image: new ol.style.Circle({
            radius: 3,
            fill: new ol.style.Fill({color: "rgba(255, 0, 0, 0.1)"}),
            stroke: new ol.style.Stroke({color: "red", width: 1})
          })
        });
      }
      var iconUser = new ol.style.Style({
         text: new ol.style.Text({
            text: "'.$fafont_js.'",
            font: "900 '.$mbg_sc.'px \'Font Awesome 5 Free\'",
            bottom: "Bottom",
            fill: new ol.style.Fill({color: "'.$mbg_f_co.'"}),
            stroke: new ol.style.Stroke({color: "'.$mbg_t_co.'", width: '.$mbg_t_ep.'})
        })
      });
      var iconUserOn = new ol.style.Style({
         text: new ol.style.Text({
            text: "'.$fafont_js.'",
            font: "900 '.$mbg_sc.'px \'Font Awesome 5 Free\'",
            bottom: "Bottom",
            fill: new ol.style.Fill({color: "'.$mbgc_f_co.'"}),
            stroke: new ol.style.Stroke({color: "'.$mbgc_t_co.'", width: '.$mbgc_t_ep.'})
         })
      });
      var iconAnoOn = new ol.style.Style({
         text: new ol.style.Text({
            text: "'.$fafont_js.'",
            font: "900 '.$mbg_sc.'px \'Font Awesome 5 Free\'",
            bottom: "Bottom",
            fill: new ol.style.Fill({color: "'.$acg_f_co.'"}),
            stroke: new ol.style.Stroke({color: "'.$acg_t_co.'", width: '.$acg_t_ep.'})
         })
      });';
}
else { // markers images
   $ecr_scr .='
      //==> markers images
      var
         iconUser = new ol.style.Style({
            image: new ol.style.Icon({
               src: "'.$ch_img.$nm_img_mbg.'",
               imgSize:['.$w_ico_b.','.$h_ico_b.']
            })
         }),
         iconUserOn = new ol.style.Style({
            image: new ol.style.Icon({
               src: "'.$ch_img.$nm_img_mbcg.'",
               imgSize:['.$w_ico_b.','.$h_ico_b.']
            })
         }),
         iconAnoOn = new ol.style.Style({
            image: new ol.style.Icon({
               src: "'.$ch_img.$nm_img_acg.'",
               imgSize:['.$w_ico_b.','.$h_ico_b.']
            })
         });';
}
   
$ecr_scr .='
      var 
         iconGeoref = new ol.style.Style({
            text: new ol.style.Text({
               text: "\uf192",
               font: "900 18px \'Font Awesome 5 Free\'",
               bottom: "Bottom",
               fill: new ol.style.Fill({color: "rgba(255, 0, 0, 90)"}),
               stroke: new ol.style.Stroke({color: "rgba(0, 0, 0, 100)", width: 0.1})
            })
         }),';
if(autorisation(-127) and $geo_ip==1)
   $ecr_scr .='
         iconIp = new ol.style.Style({
            text: new ol.style.Text({
               text: "\uf108",
               font: "900 24px \'Font Awesome 5 Free\'",
               bottom: "Bottom",
               fill: new ol.style.Fill({color: "rgba(0, 0, 0,0.5)"}),
               stroke: new ol.style.Stroke({color: "rgba(0, 0, 0,0.5)", width: 0.2})
            })
         }),';
$ecr_scr .='
         stylecountries = new ol.style.Style({
            fill: new ol.style.Fill({
               color: "rgba(255, 255, 255, 0.1)"
            }),
            stroke: new ol.style.Stroke({
               color: "#319FD3",
               width: 1
            })
         }),
         user_markers = new ol.layer.Vector({
            id: "utilisateurs",
            source: src_user,
            style: iconUser
         }),
         userOn_markers = new ol.layer.Vector({
            id: "utilisateursOn",
            source: src_userOn,
            style: iconUserOn
         }),
         ano_markers = new ol.layer.Vector({
            id: "anonymes",
            source: src_anno,
            style: iconAnoOn
         }),';
if(autorisation(-127) and $geo_ip==1)
   $ecr_scr .='
         ip_markers = new ol.layer.Vector({
            id: "ipInDb",
            source: src_ip,
            style: iconIp,
            visible: false
         }),';
$ecr_scr .='
         countries = new ol.layer.Vector({
            id: "countries",
            source: src_countries,
            style: stylecountries,
            visible: false
         });';

$source_fond=''; $max_r=''; $min_r='';$layer_id='';
switch ($cartyp) {
   case 'sat-google':
      $source_fond=' new ol.source.XYZ({url: "https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}",crossOrigin: "Anonymous", attributions: " &middot; <a href=\"https://www.google.at/permissions/geoguidelines/attr-guide.html\">Map data ©2015 Google</a>"})';
      $max_r='40000';
      $min_r='0';
      $layer_id= $cartyp;
   break;
   case 'Road': case 'Aerial': case 'AerialWithLabels':
      $source_fond='new ol.source.BingMaps({key: "'.$api_key_bing.'",imagerySet: "'.$cartyp.'"})';
      $max_r='40000';
      $min_r='0';
      $layer_id= $cartyp;
   break;
   case 'natural-earth-hypso-bathy': case 'geography-class':
      $source_fond=' new ol.source.TileJSON({url: "https://api.tiles.mapbox.com/v4/mapbox.'.$cartyp.'.json?access_token='.$api_key_mapbox.'"})';
      $max_r='40000';
      $min_r='2000';
      $layer_id= $cartyp;
   break;
   case 'terrain':case 'toner':case 'watercolor':
      $source_fond='new ol.source.Stamen({layer:"'.$cartyp.'"})';
      $max_r='40000';
      $min_r='0';
      $layer_id= $cartyp;
   break;
   case 'modisterra':
      $source_fond='new ol.source.XYZ({url: "https://gibs-{a-c}.earthdata.nasa.gov/wmts/epsg3857/best/MODIS_Terra_CorrectedReflectance_TrueColor/default/2013-06-15/GoogleMapsCompatible_Level13/{z}/{y}/{x}.jpg"})';
      $max_r='40000';
      $min_r='0';
      $layer_id= $cartyp;
   break;
   default:
      $source_fond='new ol.source.OSM()';
      $max_r='40000';
      $min_r='0';
      $layer_id= 'OSM';
}
if(autorisation(-127) and $geo_ip==1)
   $ecr_scr .='
      // ==> cluster IPs
      var
         clusterSource = new ol.source.Cluster({
            distance: "30",
            source: src_ip
         }),
         styleCache = {},
         ip_cluster = new ol.layer.Vector({
            id: "cluster_ip",
            source: clusterSource,
            visible: false,
            style: function(feature) {
               var size = feature.get("features").length;
               var style = styleCache[size];
               if (!style) {
                  var r=20;
                  if(size < 10) r=14;
                  else if(size < 100) r=16;
                  if (size > 1) {
                     style = new ol.style.Style({
                        image: new ol.style.Circle({
                           radius: r,
                           stroke: new ol.style.Stroke({color: "#fff"}),
                           fill: new ol.style.Fill({color: "rgba(99, 99, 98, 0.7)"})
                        }),
                        text: new ol.style.Text({
                           text: "\uf108 "+size.toString(),
                           font: "900 10px \'Font Awesome 5 Free\'",
                           fill: new ol.style.Fill({color: "#fff"})
                        })
                     });
                  }
                  else {style=iconIp}
                  styleCache[size] = style;
               }
               return style;
            }
         });
      // <== cluster IPs';
$ecr_scr .='
      var src_fond = '.$source_fond.',
          minR='.$min_r.',
          maxR='.$max_r.',
          layer_id="'.$layer_id.'",
          fond_carte = new ol.layer.Tile({
            id:layer_id,
            source: src_fond,
            minResolution: minR,
            maxResolution: maxR
          }),
          attribution = new ol.control.Attribution({collapsible: true}),
          view = new ol.View({
            center: ol.proj.fromLonLat([13, 46]),
            zoom: 5,
            minZoom:2
          }),
          fullscreen = new ol.control.FullScreen({source: "map-wrapper"});

      var map = new ol.Map({
         interactions: new ol.interaction.defaults({
            constrainResolution: true, onFocusOnly: true
         }),
         controls: new ol.control.defaults({attribution: false}).extend([attribution,fullscreen, mousePositionControl, new ol.control.ScaleLine]),
         target: document.getElementById("map"),
         layers: [
            fond_carte,
            countries,
            user_markers,
            userOn_markers,
            ano_markers,';
if(autorisation(-127) and $geo_ip==1)
   $ecr_scr .='
            ip_markers,
            ip_cluster,';
$ecr_scr .='
         ],
         view: view
      });

      map.addOverlay(popup);
      var zoomslider = new ol.control.ZoomSlider();
      map.addControl(zoomslider);
      var graticule = new ol.layer.Graticule();

      var button = document.createElement("button");
      button.innerHTML = "&#xf0d8";
      button.setAttribute("title", "'.geoloc_translate('Masquer').'")
      var sidebarSwitch = function(e) {
         if($("#sidebar").hasClass("show")) {
            $("#sidebar").collapse("toggle");
            button.innerHTML = "&#xf0d7";
            button.setAttribute("data-original-title", "'.geoloc_translate('Voir').'")
         }
         else {
            $("#sidebar").collapse("show");
            button.innerHTML = "&#xf0d8";
            button.setAttribute("data-original-title", "'.geoloc_translate('Masquer').'")
         }
      };
      button.addEventListener("click", sidebarSwitch, true);
      var element = document.createElement("div");
      element.className = "ol-sidebar ol-unselectable ol-control fa";
      element.appendChild(button);
      var sidebarControl = new ol.control.Control({
          element: element
      });
      map.addControl(sidebarControl);

      var select = null; // ref to currently selected interaction
      // select interaction working on "singleclick"
      var selectSingleClick = new ol.interaction.Select();
      // select interaction working on "click"
      var selectClick = new ol.interaction.Select({
        condition: ol.events.condition.click
      });
      // select interaction working on "pointermove"
      var selectPointerMove = new ol.interaction.Select({
        condition: ol.events.condition.pointerMove
      });
      var selectAltClick = new ol.interaction.Select({
        condition: function(mapBrowserEvent) {
          return ol.events.condition.click(mapBrowserEvent) && ol.events.condition.altKeyOnly(mapBrowserEvent);
        }
      });

/* source: http://github.com/eneko/Array.sortBy */
   (function(){
      var keyPaths = [];
      var saveKeyPath = function(path) {
         keyPaths.push({
            sign: (path[0] === "+" || path[0] === "-")? parseInt(path.shift()+1) : 1,
            path: path
         });
      };
      var valueOf = function(object, path) {
         var ptr = object;
         for (var i=0,l=path.length; i<l; i++) ptr = ptr[path[i]];
         return ptr;
      };
      var comparer = function(a, b) {
         for (var i = 0, l = keyPaths.length; i < l; i++) {
            aVal = valueOf(a, keyPaths[i].path);
            bVal = valueOf(b, keyPaths[i].path);
            if (typeof valueOf(a, keyPaths[i].path) == "string" && typeof valueOf(b, keyPaths[i].path) == "string"){
                aVal = aVal.toLowerCase();
                bVal = bVal.toLowerCase();
            }
            if (aVal > bVal) return keyPaths[i].sign;
            if (aVal < bVal) return -keyPaths[i].sign;
         }
         return 0;
      };
      Array.prototype.sortBy = function() {
         keyPaths = [];
         for (var i=0,l=arguments.length; i<l; i++) {
            switch (typeof(arguments[i])) {
               case "object": saveKeyPath(arguments[i]); break;
               case "string": saveKeyPath(arguments[i].match(/[+-]|[^.]+/g)); break;
            }
         }
         return this.sort(comparer);
      };
   })();
/* source: http://github.com/eneko/Array.sortBy */

//==> construction sidebar
      var uOn_feat = src_userOn.getFeatures(),
          sbuOn=\'<div id="sb_member_on" class="list-group mb-2"><div class="list-group-item bg-light text-dark font-weight-light p-2"><a id="carrets_mb_on" class="" data-toggle="collapse" href="#l_sb_memberon"><i class="toggle-icon fa fa-caret-down fa-lg mr-2" style="font-size:1.6rem;"></i></a><div class="custom-control custom-switch d-inline"><input class="custom-control-input" type="checkbox" checked="checked" data-toggle="tooltip" title="'.geoloc_translate('Voir ou masquer membres géoréférencés en ligne').'" id="cbox" /><label class="custom-control-label" for="cbox">'.$ic_b_mbgc.' '.geoloc_translate('Membre en ligne').'</label></div><span class="h6"><span class="badge badge-danger badge-pill float-right">'.$mbcg.'</span></span></div><div class="collapse" id="l_sb_memberon">\';
      uOn_feat = uOn_feat.sortBy("A.pseudo");
      for (var key in uOn_feat) {
         if (uOn_feat.hasOwnProperty(key)) {
            sbuOn += \'<a id="\'+ uOn_feat[key].W +\'" href="#" onclick="centeronMe(\\\'\'+ uOn_feat[key].W +\'\\\');return false;" class="sb_memberon list-group-item list-group-item-action py-1" href="#">'.$ic_b_mbgc.'<span class="ml-2">\' + uOn_feat[key].A.pseudo + \'</span></a>\';
         }
      }
      sbuOn += \'</div></div>\'

      var u_feat = src_user.getFeatures(),
          sbu=\'<div id="sb_member" class="list-group mb-2"><div class="list-group-item bg-light text-dark font-weight-light p-2"><a id="carrets_mb" class="" data-toggle="collapse" href="#l_sb_member"><i class="toggle-icon fa fa-caret-down fa-lg mr-2" style="font-size:1.6rem;"></i></a><div class="custom-control custom-switch d-inline"><input class="custom-control-input" type="checkbox" checked="checked" data-toggle="tooltip" title="'.geoloc_translate('Voir ou masquer membres géoréférencés').'" id="memberbox" /><label class="custom-control-label" for="memberbox">'.$ic_b_mbg.' '.geoloc_translate('Membre').'</label></div><span class="h6"><span class="badge badge-secondary badge-pill float-right">'.$mbgr.'</span></span></div><div class="collapse" id="l_sb_member"><a class="sb_member list-group-item list-group-item-action py-1" ><input id="n_filtremember" placeholder="'.geoloc_translate('Filtrer les utilisateurs').'" class="my-1 form-control form-control-sm" type="text" /></a>\';
      u_feat = u_feat.sortBy("A.pseudo");
      for (var key in u_feat) {
         if (u_feat.hasOwnProperty(key)) {
            sbu += \'<a id="\'+ u_feat[key].W +\'" href="#" onclick="centeronMe(\\\'\'+ u_feat[key].W +\'\\\');return false;" class="sb_member list-group-item list-group-item-action py-1" >'.$ic_b_mbg.'<span class="ml-2 nlfilt">\' + u_feat[key].A.pseudo + \'</span></a>\';
         }
      }
      sbu +=\'</div></div>\';

      var a_feat = src_anno.getFeatures(),i=0;
      var sba=\'<div id="sb_ano" class="list-group mb-2"><div class="list-group-item bg-light text-dark font-weight-light p-2"><a id="carrets_ac" class="link" data-toggle="collapse" href="#l_sb_ano"><i class="toggle-icon fa fa-caret-down fa-lg mr-2" style="font-size:1.6rem;"></i></a><div class="custom-control custom-switch d-inline"><input class="custom-control-input" type="checkbox" checked="checked" data-toggle="tooltip" title="'.geoloc_translate('Voir ou masquer anonymes géoréférencés').'" id="acbox" /><label class="custom-control-label" for="acbox">'.$ic_b_acg.' '.geoloc_translate('Anonyme').'</label></div><span class="h6"><span class="badge badge-danger badge-pill float-right">'.$acg.'</span></span></div><div class="collapse" id="l_sb_ano">'.$test_ip.'\';
      for (var key in a_feat) {
         if (a_feat.hasOwnProperty(key)) {
            sba += \'<a id="\'+ a_feat[key].W +\'" href="#" onclick="centeronMe(\\\'\'+ a_feat[key].W +\'\\\');return false;" class="sb_ano list-group-item list-group-item-action py-1">'.$ic_b_acg.'<span class="ml-2">'.geoloc_translate('Anonyme').' \' + i + \'</span></a>\';
         }
         i++;
      }
      sba +=\'</div></div>\';
      var sbi="";
';
if(autorisation(-127) and $geo_ip==1)
   $ecr_scr .='
      var i_feat = src_ip.getFeatures(),i=0;
      sbi+=\'<div id="sb_ip" class="list-group mb-2"><div class="list-group-item bg-light text-dark font-weight-light p-2"><a id="carrets_ip" class="link" data-toggle="collapse" href="#l_sb_ip"><i class="toggle-icon fa sr-only fa-lg mr-2" style="font-size:1.6rem;"></i></a><div class="custom-control custom-switch d-inline"><input class="custom-control-input" type="checkbox" data-toggle="tooltip" title="'.geoloc_translate('Voir ou masquer les IP').'" id="ipbox" /><label class="custom-control-label" for="ipbox"><span class="fa fa-desktop fa-lg text-muted"></span> IP</label></div><span class="h6"><span class="badge badge-secondary badge-pill float-right">'.$ipnb.'</span></span></div><div class="collapse" id="l_sb_ip">\';
      for (var key in i_feat) {
         if (i_feat.hasOwnProperty(key)) {
            sbi += \'<a id="\'+ i_feat[key].W +\'" href="#" onclick="centeronMe(\\\'\'+ i_feat[key].W +\'\\\');return false;" class="sb_ip list-group-item list-group-item-action py-1"><span class="fa fa-desktop fa-lg text-muted"></span><span class="ml-2 small">\'+ i_feat[key].A.ip +\'</span></a>\';
         }
         i++;
      }
      sbi +=\'</div></div>\';';

$mess_mb='';
$sb_georef='';
$sb_layers='<div class="list-group-item list-group-item-action py-1">'.geoloc_translate('Type de carte').'<div class="custom-control custom-radio"><input class="custom-control-input" type="radio" data-toggle="tooltip" title="" name="layername" id="lay_osm" /><label for="lay_osm" class="custom-control-label">Routes (OSM)</label></div><div class="custom-control custom-radio"><input class="custom-control-input" type="radio" data-toggle="tooltip" title="" name="layername" id="lay_bing" /><label for="lay_bing" class="custom-control-label">Aérienne (Bing)</label></div></div>';

if ($username !='') {
   if ($ue_lat !='' and $ue_long !='') {
      $infooo ='<div id="oldloc"><strong>'.geoloc_translate("Coordonnées enregistrées :").'</strong><br /><span class="text-muted">Latitude :</span> '.$ue_lat.'<br /><span class="text-muted">Longitude :</span> '.$ue_long.'<br /></div><br /><strong>'.geoloc_translate("Voulez vous changer pour :").'</strong><br />';
      $mess_mb = geoloc_translate('Cliquer sur la carte pour modifier votre position.');
   }
   else {
      $infooo = '<div id="newloc"><strong>'.geoloc_translate("Vous n'êtes pas géoréférencé.").'</strong></div><br /><strong>'.geoloc_translate("Voulez vous le faire à cette position:").'</strong><br />';
      $mess_mb = geoloc_translate('Cliquer sur la carte pour définir votre géolocalisation.');
   }
   $sb_georef.='   <div class="list-group-item list-group-item-action py-1"><div class="custom-control custom-switch"><input class="custom-control-input" type="checkbox" data-toggle="tooltip" title="'.geoloc_translate("Définir ou modifier votre position.").'" id="georefbox" /><label for="georefbox" class="custom-control-label">'.geoloc_translate("Définir ou modifier votre position.").'</label></div><span class="help-block small muted">'.$mess_mb.'</span></div>';

   $ecr_scr .= '
//==> Georeferencement par user
      var src_georef = new ol.source.Vector({});
      var pointGeoref = new ol.Feature({
        geometry: new ol.geom.Point(ol.proj.transform([0, 0], "EPSG:4326","EPSG:3857"))
      });
      src_georef.addFeature(pointGeoref);
      var georef_marker = new ol.layer.Vector({
        source: src_georef,
         style: iconGeoref
      });

      var georef_popup = new ol.Overlay({element: document.getElementById("georefpopup")});
      map.on("click", function(evt) {
         pointGeoref.getGeometry().setCoordinates(evt.coordinate);
         var element = georef_popup.getElement(),
             coordinate = evt.coordinate,
             coordWgs = ol.proj.transform(evt.coordinate, "EPSG:3857", "EPSG:4326"),
             lat = coordWgs[1],
             lng = coordWgs[0];
         $(element).popover("dispose");
         georef_popup.setPosition(coordinate);
         $(element).popover({
            container: element,
            sanitize:false,
            placement: "top",
            animation: true,
            html: true,
            title:\'&nbsp;Géolocalisation<button type="button" id="close" class="close" onclick="$(\\\'.popover\\\').hide();">&times;</button>\',
            content: \'<form action="" onsubmit=" window.location.href = \\\'modules.php?ModPath=geoloc&ModStart=geoloc&lng=\'+lng+\'&lat=\'+lat+\'&mod=neo&uid=\\\'; return false;">\'
        + \'<legend><img src="'.$the_av_ch.'" class="img-thumbnail n-ava-64" /> '.$username.' </legend>\'
        + \''.$infooo.'\'
        + \'<div id="lalo"><span class="text-muted">'.geoloc_translate("Latitude").' : </span>\' + lat + \'<br /><span class="text-muted">'.geoloc_translate("Longitude").' : </span>\' + lng + \'</div>\'
        + \'<input type="hidden" id="html" value="'.addslashes($user_from).'" />\'
        + \'<br />\'
        + \'<button type="submit" class ="btn btn-primary btn-sm">'.geoloc_translate("Enregistrez").'</button>\'
        + \'<input type="hidden" id="longitude" value="\' + lng + \'"/>\'
        + \'<input type="hidden" id="latitude" value="\' + lat + \'"/>\'
        + \'<input type="hidden" id="modgeo" value="neo"/>\'
        + \'</form>\'
         });
         $(element).popover("show");
      });
//<== Georeferencement par user
';
}
else
   $mess_mb='';

$ecr_scr .='
      $("#sidebar").append(sbuOn+sba+sbu+sbi);
      $(\'.sb_member span:last-child\').each(function(){
         $(this).attr(\'data-search-term\', $(this).text().toLowerCase());
      });
      $(\'#n_filtremember\').on(\'keyup\', function(){
         var searchTerm = $(this).val().toLowerCase();
         $(\'.nlfilt\').each(function(){
            if ($(this).filter(\'[data-search-term *= \' + searchTerm + \']\').length > 0 || searchTerm.length < 1)
               $(this).parents("a").show();
            else
               $(this).parents("a").hide();
          });
      });

//<== construction sidebar

//==> comportement sidebar et layers
   $(document).ready(function () {
      centeronMe = function(u) {
         $(".sb_member,.sb_memberon, .sb_ano, .sb_ip").removeClass( "animated faa-horizontal faa-slow" );
         u.substr(0,1) == "i" ? view.setCenter(src_ip.getFeatureById(u).getGeometry().getFlatCoordinates()):"";
         u.substr(0,1) == "a" ? view.setCenter(src_anno.getFeatureById(u).getGeometry().getFlatCoordinates()):"";
         if(u.substr(0,1) == "o") {
            let ici = src_userOn.getFeatureById(u).getGeometry().getFlatCoordinates();
            view.setCenter(ici);
            $("#ol_popup").show();
            container.innerHTML = \'<div class="text-center">\' + src_userOn.Tu[u].A.userlinks + \'</div><hr /><img class="mr-2 img-thumbnail n-ava" src="\' + src_userOn.Tu[u].A.ava + \'" align="middle" /><i class="fa fa-plug faa-flash animated text-primary mr-1" data-toggle="tooltip" title="\' + src_userOn.Tu[u].A.pseudo + \' est connecté"></i><span class="lead">\' + src_userOn.Tu[u].A.pseudo + \'</span><hr /><div class="text-center">\' + src_userOn.Tu[u].A.social + \'</div>\';
            popup.setPosition(ici);
         }
         if(u.substr(0,1) == "u") {
            let ici = src_user.getFeatureById(u).getGeometry().getFlatCoordinates();
            view.setCenter(ici);
            view.adjustCenter([240,0]);
            map.getView().setZoom(5);
            $("#ol_popup").show();
            container.innerHTML = \'<img class="mr-2 img-thumbnail n-ava" src="\' + src_user.Tu[u].A.ava + \'" align="middle" /><span class="lead">\' + src_user.Tu[u].A.pseudo + \'</span><div class="my-2">'.geoloc_translate("Dernière visite").' : \' + src_user.Tu[u].A.lastvisit + \'</div><hr /><div class="text-center lead">\' + src_user.Tu[u].A.userlinks + \'</div>\';
            popup.setPosition(ici);
         }
         $("#"+u).addClass("animated faa-horizontal faa-slow" );
         map.getView().setZoom(17);
         $("#map .tooltipbyclass").tooltip({placement: "right", container:"#map",});
      }

      $("#georefbox").change("click", function () {
         if(this.checked) {
            $("#memberbox, #cbox, #acbox, #ipbox").prop("checked", false);
//            $("#carrets_mb_on i,#carrets_ac i, #carrets_mb i").removeClass("fa-caret-down").addClass("fa-caret-up sr-only");
            user_markers.setVisible(false);
            userOn_markers.setVisible(false);';
if(autorisation(-127) and $geo_ip==1)
   $ecr_scr .='
            ip_cluster.setVisible(false);';
$ecr_scr .='
            ano_markers.setVisible(false);
            map.addOverlay(georef_popup);
            map.addLayer(georef_marker);
            $("#l_sb_member, #l_sb_memberon, #l_sb_ip, #l_sb_ano").removeClass("show");
         } else {
            map.removeOverlay(georef_popup);
            var element = georef_popup.getElement();
            $(element).popover("dispose");
            map.removeLayer(georef_marker);
            $("#memberbox, #cbox, #acbox, #ipbox").prop("checked", true);
            user_markers.setVisible(true);
            userOn_markers.setVisible(true);
            //ip_cluster.setVisible(true);
            ano_markers.setVisible(true);
         }
      });

      $("#grillebox").change("click", function () {
         this.checked ? graticule.setMap(map) : graticule.setMap(null);
      });

      $("#cbox").change("click", function () {
         if(this.checked) {
            $("#carrets_mb_on i").removeClass("fa-caret-down sr-only").addClass("fa-caret-up");
            userOn_markers.setVisible(true);
            $("#l_sb_memberon").addClass("show");
         } else {
            $("#carrets_mb_on i").removeClass("fa-caret-up").addClass("fa-caret-down sr-only");
            $("#ol_popup").hide();
            userOn_markers.setVisible(false);
            $("#l_sb_memberon").removeClass("show");
            $(".sb_member,.sb_memberon, .sb_ano, .sb_ip").removeClass( "animated faa-horizontal faa-slow" );
         }
      });
      $("#ipbox").change("click", function () {
         if(this.checked) {
            $("#carrets_ip i").removeClass("fa-caret-down sr-only").addClass("fa-caret-up");
            ip_cluster.setVisible(true);
            $("#l_sb_ip").addClass("show");
         } else {
            $("#carrets_ip i").removeClass("fa-caret-up").addClass("fa-caret-down sr-only");
            $("#ol_popup").hide();
            ip_cluster.setVisible(false);
            $("#l_sb_ip").removeClass("show");
            $(".sb_member,.sb_memberon, .sb_ano, .sb_ip").removeClass( "animated faa-horizontal faa-slow" );
         }
      });
      $("#acbox").change("click", function () {
         if(this.checked) {
            $("#carrets_ac i").removeClass("fa-caret-down sr-only").addClass("fa-caret-up");
            ano_markers.setVisible(true);
            $("#l_sb_ano").addClass("show");
         } else {
            $("#carrets_ac i").removeClass("fa-caret-up").addClass("fa-caret-down sr-only");
            $("#ol_popup").hide();
            ano_markers.setVisible(false);
            $("#l_sb_ano").removeClass("show");
            $(".sb_member,.sb_memberon, .sb_ano, .sb_ip").removeClass( "animated faa-horizontal faa-slow" );
         }
      });
      $("#memberbox").change("click", function () {
         if(this.checked) {
            $("#carrets_mb i").removeClass("fa-caret-down sr-only").addClass("fa-caret-up");
            user_markers.setVisible(true);
            $("#l_sb_member").addClass("show");
         } else {
            $("#carrets_mb i").removeClass("fa-caret-up").addClass("fa-caret-down sr-only");
            $("#ol_popup").hide();user_markers.setVisible(false);
            $("#l_sb_member").removeClass("show");
            $(".sb_member,.sb_memberon, .sb_ano, .sb_ip").removeClass( "animated faa-horizontal faa-slow" );
         }
      });
      $("#coastandborder").change("click", function () {
         this.checked ? countries.setVisible(true) : countries.setVisible(false);
      });
   });

   $("#cartyp").on("change", function() {
      cartyp = $( "#cartyp option:selected" ).val();
      $("#dayslider").removeClass("show");
      switch (cartyp) {
         case "OSM":
            fond_carte.setSource(new ol.source.OSM());
            map.getLayers().R[0].setProperties({"id":cartyp});
            fond_carte.setMinResolution(1);
         break;
         case "sat-google":
            fond_carte.setSource(new ol.source.XYZ({url: "https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}",crossOrigin: "Anonymous", attributions: " &middot; <a href=\"https://www.google.at/permissions/geoguidelines/attr-guide.html\">Map data ©2015 Google</a>"}));
            map.getLayers().R[0].setProperties({"id":cartyp});
         break;
         case "Road":case "Aerial":case "AerialWithLabels":
            fond_carte.setSource(new ol.source.BingMaps({key: "'.$api_key_bing.'",imagerySet: cartyp }));
            map.getLayers().R[0].setProperties({"id":cartyp});
            fond_carte.setMinResolution(1);
         break;
         case "natural-earth-hypso-bathy": case "geography-class":
            fond_carte.setSource(new ol.source.TileJSON({url: "https://api.tiles.mapbox.com/v4/mapbox."+cartyp+".json?access_token='.$api_key_mapbox.'"}));
            fond_carte.setMinResolution(2000);
            fond_carte.setMaxResolution(40000);
            map.getLayers().R[0].setProperties({"id":cartyp});
         break;
         case "terrain": case "toner": case "watercolor":
            fond_carte.setSource(new ol.source.Stamen({layer:cartyp}));
            fond_carte.setMinResolution(0);
            fond_carte.setMaxResolution(40000);
            map.getLayers().R[0].setProperties({"id":cartyp});
         break;
         case "modisterra":
            $("#dayslider").addClass("show");
            var datejour="'.$date_jour.'";
            var today = new Date();
            fond_carte.setSource(new ol.source.XYZ({url: "https://gibs-{a-c}.earthdata.nasa.gov/wmts/epsg3857/best/VIIRS_SNPP_CorrectedReflectance_TrueColor/default/"+datejour+"/GoogleMapsCompatible_Level9/{z}/{y}/{x}.jpg"}));
            $("#nasaday").on("input change", function(event) {
               var newDay = new Date(today.getTime());
               newDay.setUTCDate(today.getUTCDate() + Number.parseInt(event.target.value));
               datejour = newDay.toISOString().split("T")[0];
               var datejourFr = datejour.split("-");
               $("#dateimages").html(datejourFr[2]+"/"+datejourFr[1]+"/"+datejourFr[0]);
               fond_carte.setSource(new ol.source.XYZ({url: "https://gibs-{a-c}.earthdata.nasa.gov/wmts/epsg3857/best/VIIRS_SNPP_CorrectedReflectance_TrueColor/default/"+datejour+"/GoogleMapsCompatible_Level9/{z}/{y}/{x}.jpg"}));
            });
            fond_carte.setMinResolution(2);
            fond_carte.setMaxResolution(40000);
            map.getLayers().R[0].setProperties({"id":cartyp});
         break;
      }
   });

//<== comportement sidebar et layers

// ==> fallback de résolution
   map.getView().on("propertychange", function(e) {
      switch (e.key) {
         case "resolution":
            var idLayer = map.getLayers().R[0].A.id;
            if((idLayer=="natural-earth-hypso-bathy" || idLayer=="geography-class") && e.oldValue<2000) {
               fond_carte.setSource(new ol.source.OSM());
               fond_carte.setProperties({"id":"OSM", "minResolution":"0", "maxResolution":"40000" });
               $("#cartyp option[value=\'OSM\']").prop("selected", true);
            }
           break;
      }
   });

// <== fallback de résolution

// ==> opacité sur couche de base
   $("#baselayeropacity").on("input change", function() {
      map.getLayers().R[0].setOpacity(parseFloat(this.value));
   });
// <== opacité sur couche de base

/*

//==> Georeferencement par admin
      var popup = new ol.Overlay({element: document.getElementById("georefpopup")});
      map.addOverlay(popup);
      map.on("click", function(evt) {
         var element = popup.getElement(),
             coordinate = evt.coordinate,
             lat = coordinate[1],
             lng = coordinate[0],
             hdms = ol.coordinate.toStringHDMS(ol.proj.toLonLat(coordinate));

         $(element).popover("hide");
         popup.setPosition(coordinate);
         $(element).popover({
            placement: "top",
            animation: false,
            html: true,
            content: \'<fieldset>\'
            + \'<span class="text-danger">'.$infooo.'</span>\'
            + \'<p>The location you clicked was:</p><code>\' + hdms + \'</code>\'
            + \'<button type="submit" class ="btn btn-primary btn-sm">'.geoloc_translate("Enregistrez").'</button>\'
            + \'<input type="hidden" id="longitude" value="\'+lng+\'"/>\'
            + \'<input type="hidden" id="latitude" value=""/>\'
            + \'<input type="hidden" id="modgeo" value="mod"/>\'
            + \'<input type="hidden" id="uid" value="id"/>\'
            + \'</fieldset>\'      });
         $(element).popover("show");
      });
//<== Georeferencement par admin
*/

//==> les fenetres popup pour les markers
  var container = document.getElementById("ol_popup"),
      OpenPopup = function (evt) {
      $("#ol_popup").show();
      popup.setPosition(undefined);
      $(".sb_member, .sb_ano, .sb_memberon").removeClass("animated faa-horizontal faa-slow" );
      var feature = map.forEachFeatureAtPixel(evt.pixel, function (feature, layer) {
         if (feature) {
            $("#"+feature.getId()).addClass("animated faa-horizontal faa-slow" );
            var coord = map.getCoordinateFromPixel(evt.pixel);
            if (typeof feature.get("features") === "undefined") {
               if(feature.getId().substr(0,1) == "u") {
                   container.innerHTML = \'<img class="mr-2 img-thumbnail n-ava" src="\' + feature.get("ava") + \'" align="middle" /><span class="lead">\' + feature.get("pseudo") + \'</span><div class="my-2">'.geoloc_translate("Dernière visite").' : \' + feature.get("lastvisit") + \'</div><hr /><div class="text-center lead">\' + feature.get("userlinks") + \'</div>\';
               }
               if(feature.getId().substr(0,1) == "o") {
                   container.innerHTML = \'<div class="text-center">\' + feature.get("userlinks") + \'</div><hr /><img class="mr-2 img-thumbnail n-ava" src="\' + feature.get("ava") + \'" align="middle" /><span class="lead">\' + feature.get("pseudo") + \'</span><hr /><div class="text-center">\' + feature.get("social") + \'</div>\';
               }
               if(feature.getId().substr(0,1) =="a") {
                  container.innerHTML = \'<i class="fa fa-tv fa-2x text-muted mr-1 align-middle"></i>'.geoloc_translate('Anonyme').' @ \' + feature.get("ip_hostaddr") + \' <br /><hr /><span class="text-muted">'.geoloc_translate("Hôte").' : </span>\'+ feature.get("ip_hote") +\'<br /><span class="text-muted">'.geoloc_translate("En visite ici").' : </span> \' + feature.get("ip_visitpage") +\'<br /><span class="text-muted">'.geoloc_translate("Visites").' : </span>[\' + feature.get("ip_visit") +\']<br /><span class="text-muted">'.geoloc_translate("Ville").' : </span>\'+ feature.get("ip_city") +\'<br /><span class="text-muted">'.geoloc_translate("Pays").' : </span>\'+ feature.get("ip_country") +\'<hr /><img src="\'+ feature.get("ip_flagsrc") +\'.png" class="n-smil" alt="flag" />\' || "(unknown)";
               }
            } else {
                var cfeatures = feature.get("features");
                if (cfeatures.length > 1) {
                  var l_ip="";
                  l_ip += \'<div class="ol_li"><h4><i class="fa fa-desktop fa-lg text-muted mr-2 align-middle"></i>IP<span class="badge badge-secondary float-right">\'+cfeatures.length+\'</span></h4><hr />\';
                    container.innerHTML = \'\';
                    for (var i = 0; i < cfeatures.length; i++) {
                        l_ip += \'<small><a href="#" onclick="centeronMe(\\\'\' + cfeatures[i].id_ + \'\\\')">\' + cfeatures[i].get("ip") + \'</small></a><br />\';
                    }
                    l_ip += \'</div>\';
                    $(container).append(l_ip);
                }
                if (cfeatures.length == 1) {
                    container.innerHTML = \'<i class="fa fa-desktop fa-lg text-muted mr-1 align-middle"></i>@ \' + cfeatures[0].get("ip") + \'<hr /><span class="text-muted">'.geoloc_translate("Visites").' : </span>[\' + cfeatures[0].get("visit") + \']<br /><hr />\';
                }
            }
            popup.setPosition(coord);
            $("#map .tooltipbyclass").tooltip({placement: "bottom", container:"#map"});
         } else {
            popup.setPosition(undefined);
         }
      });
   };
   map.on("click", OpenPopup);
//<== les fenêtres popup pour les markers

//==> changement etat pointeur sur les markers
   map.on("pointermove", function(e) {
        if (e.dragging) {
          $(".popover").popover("dispose");
          return;
        }
        var pixel = map.getEventPixel(e.originalEvent);
        var hit = map.hasFeatureAtPixel(pixel);
        map.getTarget().style.cursor = hit ? "pointer" : "";
      });
//<== changement etat pointeur sur les markers
';

/*
$ecr_scr .= '
    function updateMarker(Laa,loo,mod,id) {
        var getVars =  "&lng=" + loo + "&lat=" + Laa + "&mod=" + mod + "&uid=" + id ;
        window.location.href = "modules.php?ModPath=geoloc&ModStart=geoloc" + getVars;
    }
';
*/

//==> ecriture des markers pour les membres connectés et les anonymes
$ecr_scr .= $mb_con_g;
//<==
$ecr_scr .= '
   document.getElementById("mess_info").innerHTML = \''.$mess_adm.'\';';

if($op)
   if ($op[0]=='u') //pour zoom sur user back with u1
      $ecr_scr .= '
   map.getView().setCenter(src_user.Tu.'.$op.'.A.geometry.flatCoordinates);
   map.getView().setZoom(15);';
if($op=='allip' and $geo_ip==1 and autorisation(-127))
   $ecr_scr .= '
   ip_cluster.setVisible(true);
   user_markers.setVisible(false);
   ano_markers.setVisible(false);
   userOn_markers.setVisible(false);
   $("#acbox, #cbox, #memberbox").prop("checked", false);
   $("#ipbox").prop("checked", "checked");';

$ecr_scr .= '
   function checkSize() {
      var small = map.getSize()[0] < 600;
      attribution.setCollapsible(small);
      attribution.setCollapsed(small);
      $(".n-media-repere").css("color") == "rgb(255, 0, 0)" ? $("#sidebar").removeClass("show") : $("#sidebar").addClass("show");
   }
   window.addEventListener("resize", checkSize);';

$ecr_scr .= file_get_contents('modules/geoloc/include/ol-dico.js');
$ecr_scr .= '
   const targ = map.getTarget();
   const lang = targ.lang;
   for (var i in dic) {
      if (dic.hasOwnProperty(i)) {
         $("#map "+dic[i].cla).prop("title", dic[i][lang]);
      }
   }

   fullscreen.on("enterfullscreen",function(){
      $(dic.olfullscreentrue.cla).attr("data-original-title", dic["olfullscreentrue"][lang]);
   })
   fullscreen.on("leavefullscreen",function(){
      $(dic.olfullscreenfalse.cla).attr("data-original-title", dic["olfullscreenfalse"][lang]);
   })
   $("#map .ol-zoom-in, #map .ol-zoom-out").tooltip({placement: "right", container:"#map",});
   $(".ol-sidebar button[title], .ol-full-screen-false, .ol-full-screen-true, .ol-rotate-reset, .ol-attribution button[title]").tooltip({placement: "left", container:"#map",});

   $(\'a[data-toggle="collapse"]\').click(function () {
      $(this).find("i.toggle-icon").toggleClass(\'fa-caret-down fa-caret-up\',6000);
   })


});

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
   });
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

$(document).ready(function() {
   $(\'a[data-toggle="collapse"]\').click(function () {
      $(this).find("i.toggle-icon").toggleClass(\'fa-caret-down fa-caret-up\',6000);
   });
});

window.addEventListener("load", (event) => {
  console.log("page is fully loaded");
     window.scroll({
     top: 100,
     left: 100,
     behavior: "smooth"
   });
});
//]]>
</script>';
//<== construction js

//==> affichage
include ('header.php');
//==> ecriture des div contenants

$affi='';
$affi .= '
   <span class="n-media-repere"></span>
   <h3 class="mt-4 mb-3">'.geoloc_translate("Géolocalisation des membres du site").'<span class="float-right"><span class="badge badge-secondary mr-2" title ="'.geoloc_translate('Membres du site').'" data-toggle="tooltip" data-placement="left">'.$total_membre.'</span><span class="badge badge-danger" data-toggle="tooltip" title="'.geoloc_translate("En ligne").'">'.$total_connect.'</span></span></h3>
   <div class=" mb-4">
      <div id="map-wrapper" class="ol-fullscreen my-3">
         <div id="map" lang="'.language_iso(1,0,0).'" class="map" tabindex="20">
            <div id="ol_popup" class="ol-popup"></div>
            <div style="display: none;">
               <div id="georefpopup"></div>
            </div>
         </div>
         <div id="sidebar" class= "collapse show col-sm-4 col-md-3 col-6 px-0">
            <div id="sb_tools" class="list-group mb-2">
               <div class="list-group-item bg-light text-dark font-weight-light p-2"><a class="link" data-toggle="collapse" href="#l_sb_tools"><i class="toggle-icon fa fa-caret-down fa-lg mr-2" style="font-size:1.6rem;"></i></a>'.geoloc_translate('Fonctions').'<span class="float-right">'.$lkadm.'</span></div>
               <div class="collapse" id="l_sb_tools">
                  '.$sb_georef.'
                  <div class="list-group-item list-group-item-action py-1">
                     <div class="form-group row">
                        <label class="col-form-label col-sm-12" for="cartyp">'.geoloc_translate('Type de carte').'</label>
                        <div class="col-sm-12">
                           <select class="custom-select form-control" name="cartyp" id="cartyp">';
   $j=0;
   foreach ($fond_provider as $v) {
      if($v[0]==$cartyp) $sel='selected="selected"'; else $sel='';
      switch($j){
         case '0': $affi .= '
                              <optgroup label="OpenStreetMap">';break;
         case '1': $affi .= '
                              <optgroup label="Stamen">';break;
         case '4': $affi .= '
                              <optgroup label="NASA">';break;
         case '5': if($api_key_mapbox==!'') 
                     $affi .= '
                              <optgroup label="Mapbox">';
                   elseif($api_key_bing==!'')
                     $affi .= '
                              <optgroup label="Bing maps">'; break;
         case '7': if($api_key_bing==!'' and $api_key_mapbox!=='') 
                     $affi .= '
                              <optgroup label="Bing maps">'; break;
         case '10': $affi .= '
                              <optgroup label="Google">';break;
      }
      $affi .= '
                                 <option '.$sel.' value="'.$v[0].'">'.$v[1].'</option>';
      switch($j){
         case '0': case '3': case '4': case '11': $affi .= '
                              </optgroup>'; break;
         case '6': if($api_key_mapbox==!'') $affi .= '
                              </optgroup>'; break;
         case '7': if($api_key_mapbox=='' and $api_key_bing==!'') $affi .= '
                              </optgroup>'; break;
      }
      $j++;
   }
$affi .= '
                           </select>
                           <input type="range" value="1" class="custom-range mt-1" min="0" max="1" step="0.1" id="baselayeropacity" />
                           <label class="mt-0 float-right small" for="baselayeropacity">'.geoloc_translate('Opacité').'</label>
                           <div id="dayslider" class="collapse">
                              <input type="range" value="1" class="custom-range mt-1" min="-6" max="0" value="0" id="nasaday" />
                              <label id="dateimages" class="mt-0 float-right small" for="nasaday">'.$date_jour.'</label>
                           </div>
                        </div>
                     </div>
                     <hr />
                     <div>Couches utilitaires</div>
                     <div class="custom-control custom-switch d-inline">
                        <input class="custom-control-input" type="checkbox" data-toggle="tooltip" title="'.geoloc_translate('Voir ou masquer la grille').'" id="grillebox" />
                        <label class="custom-control-label" for="grillebox">'.geoloc_translate('Grille').'</label>
                     </div>
                     <br />
                     <div class="custom-control custom-switch d-inline">
                        <input class="custom-control-input" type="checkbox" data-toggle="tooltip" title="'.geoloc_translate('Voir ou masquer la couche').'" id="coastandborder" />
                        <label class="custom-control-label" for="coastandborder">'.geoloc_translate('Côtes et frontières').'</label>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <ul class="nav nav-tabs mt-4">
         <li class="nav-item"><a id="messinfo-tab" class="nav-link active" href="#infocart" data-toggle="tab_ajax"><span class="d-sm-none"><i class=" fa fa-globe fa-lg mr-2"></i><i class=" fa fa-info fa-lg"></i></span><span class="d-none d-sm-inline">'.geoloc_translate("Infos carte").'</span></a></li>
         <li class="nav-item"><a id="aide-tab" class="nav-link" href="modules/geoloc/doc/aide_geo-'.$language.'.html" data-target="#aide" data-toggle="tab_ajax"><span class="d-sm-none"><i class=" fa fa-globe fa-lg mr-2"></i><i class=" fa fa-question fa-lg"></i></span><span class="d-none d-sm-inline">'.geoloc_translate("Aide").'</span></a></li>';
if(autorisation(-127) and $geo_ip==1)
   $affi .= '
         <li class="nav-item"><a id="iplist-tab" class="nav-link " href="#ipgeolocalisation" data-toggle="tab_ajax"><span class="d-sm-none"><i class=" fa fa-globe fa-lg mr-2"></i><i class=" fa fa-tv fa-lg"></i></span><span class="d-none d-sm-inline">'.geoloc_translate("Ip liste").'</span></a></li>';
$affi .= '
      </ul>
   <div class="tab-content">
      <div class="tab-pane fade show active" id="infocart">
         <div id="mess_info" class=" col-12 mt-3"></div>
      </div>
      <div class="tab-pane fade" id="aide"></div>
      <div class="tab-pane fade mt-2" id="ipgeolocalisation">
         <h5 class="mt-3">
            <i title="'.geoloc_translate('IP géoréférencées').'" data-toggle="tooltip" style="color:'.$acg_t_co.'; opacity:'.$acg_t_op.';" class="fa fa-desktop fa-lg mr-2 align-middle"></i>
            <span class="badge badge-secondary mr-2 float-right">'.$ipnb.'</span>
         </h5>
         <div class="form-group row">
            <div class="col-sm-12">
               <input type="text" class="mb-3 form-control form-control-sm n_filtrbox" placeholder="'.geoloc_translate('Filtrer les résultats').'" />
            </div>
         </div>
         <div class="n-filtrable row mx-0">
            '.$tab_ip.'
         </div>
      </div>
      <div class="tab-pane fade" id="tracker">
         <input type="checkbox" title="'.geoloc_translate('Voir ou masquer les waypoints').'" id="wpobox" />&nbsp;'.geoloc_translate('Voir ou masquer les waypoints').' <span id="envoyer">Ex</span>
         <input type="checkbox" title="'.geoloc_translate('Activer désactiver la géolocalisation').'" id="geolobox" onclick="" />&nbsp;'.geoloc_translate('Activer désactiver la géolocalisation').'
      </div>
   </div>
</div>';
//==> affichage des div contenants et écriture du script
echo $affi.$ecr_scr;

include ('footer.php');

switch ($op) {
   case 'wp':
      wp_fill();
   break;
}
?>

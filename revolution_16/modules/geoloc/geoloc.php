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
/* geoloc_geoloc.php file 2008-2025 by Jean Pierre Barbary (jpb)        */
/* dev team : Philippe Revilliod (Phr), A.NICOL                         */
/************************************************************************/

/*
le géoréférencement des anonymes est basé sur un décodage des adresse ip
le géoréférencement des membres sur une géolocalisation exacte réalisé par l'utilisateur
*/

if (!stristr($_SERVER['PHP_SELF'],"modules.php"))
   die();
if (strstr($ModPath,'..') || strstr($ModStart,'..') || stristr($ModPath, 'script') || stristr($ModPath, 'cookie') || stristr($ModPath, 'iframe') || stristr($ModPath, 'applet') || stristr($ModPath, 'object') || stristr($ModPath, 'meta') || stristr($ModStart, 'script') || stristr($ModStart, 'cookie') || stristr($ModStart, 'iframe') || stristr($ModStart, 'applet') || stristr($ModStart, 'object') || stristr($ModStart, 'meta'))
   die();

global $pdst, $language, $title;
include ('modules/'.$ModPath.'/geoloc.conf');
include_once('modules/'.$ModPath.'/lang/geoloc.lang-'.$language.'.php');

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

//==> for admin
if(autorisation(-127)) {
   $mess_adm ='<p class="text-danger">'.geoloc_translate('Rappel : vous êtes en mode administrateur !').'</p>';
   $lkadm = '<a href="admin.php?op=Extend-Admin-SubModule&amp;ModPath=geoloc&amp;ModStart=admin/geoloc_set" title="'.geoloc_translate("Admin").'" data-bs-toggle="tooltip"><i id="cogs" class="fa fa-cogs fa-lg"></i></a>';
   $infooo = geoloc_translate('Modification administrateur');
   // IP géoréférencées
   if($geo_ip==1) {
      $arpostip = array();
      $result_postip = sql_query('SELECT COUNT(*) AS nbpost , poster_ip FROM '.$NPDS_Prefix.'posts GROUP BY poster_ip');
      $postnb = sql_num_rows($result_postip);
      while ($p = sql_fetch_array($result_postip)){
         $arpostip[$p['poster_ip']] = $p['nbpost'];
      }

      $spamip = array();
      $spamipfile = file("slogs/spam.log");
      foreach($spamipfile as $v){
         $ab = explode('|', $v);
         $a[] = $ab[0];
         $b[]= trim($ab[1]);
      }
      $spamip = array_combine($a, $b);

      $sidebarip='';
      $tab_ip=''; $ip_o= 'const ip_features=[';
      $result_ip = sql_query('SELECT * FROM '.$NPDS_Prefix.'ip_loc ORDER BY ip_visite DESC');
      $ipnb = sql_num_rows($result_ip);
      $i=0;
      while ($row_ip = sql_fetch_array($result_ip)) {
         $ip_lt = $row_ip['ip_lat'];
         $ip_lg = $row_ip['ip_long'];
         $ip_ip1 = $row_ip['ip_ip'];
         $ip_country1 = $row_ip['ip_country'];
         $ip_code_country1 = $row_ip['ip_code_country'];
         $ip_city1 = $row_ip['ip_city'];
         $ip_visite = $row_ip['ip_visite'];
         $ip_spam = array_key_exists($ip_ip1, $spamip) ? $spamip[$ip_ip1] : '';
         $ip_post = array_key_exists($ip_ip1, $arpostip) ? $arpostip[$ip_ip1] : 'no';
         if ($ip_lt != 0 and $ip_lg != 0) {
            $ip_o .= '[['.$ip_lg.','.$ip_lt.'],"'.urldecode($ip_ip1).'","'.$ip_visite.'","'.$ch_img.'flags/'.strtolower($ip_code_country1).'","'.$ip_country1.'","'.$ip_city1.'","'.$ip_post.'","'.$ip_spam.'"],';
            $cl = $ip_spam >= 1 ? ' text-danger' : '';
            $infospam ='';
            switch($ip_spam){
               case '': $infospam =''; break;
               case '5': $infospam = '<span class="small badge bg-danger rounded-pill float-end tooltipbyclass" title="'.geoloc_translate("Adresse IP bannie !").'">'.$ip_spam.'</span>';break;
               default : $infospam = '<span class="small badge bg-danger rounded-pill float-end tooltipbyclass" title="'.geoloc_translate("Adresse IP signalée par l’antispam !").'">'.$ip_spam.'</span>';
            }
            $sidebarip .='
            <a id="ip'.$i.'" class="filtrip_js sb_js sb_ip list-group-item list-group-item-action py-1 px-2 border-left-0 border-right-0'.$cl.'"><span class="" data-bs-toggle="tooltip" title="'.geoloc_translate('Voir sur la carte').'."><i class="me-1 bi bi-display h4 align-middle"></i></span><span class="small nlfiltrip" data-searchterm="'.urldecode($ip_ip1).'">'.urldecode($ip_ip1).'</span>'.$infospam.'</a>';

            $tab_ip .='
         <p class="col-sm-12 col-md-3 p-2  border rounded flex-column align-items-start list-group-item-action">
            <span class="d-flex w-100 mt-1">
            <span><img class=" img-fluid n-ava-small me-1 mb-1" src="'.$ch_img.'flags/'.strtolower($ip_code_country1).'.png" alt="'.$ip_country1.'" loading="lazy"> '.urldecode($ip_ip1).'</span>
            <span class="ms-auto">
               <span class="badge bg-secondary ms-1" title="'.geoloc_translate("Visites").'" data-bs-toggle="tooltip" data-bs-placement="left" >'.$ip_visite.'</span>
            </span>
            </span>
            <span class="d-flex w-100">'.$ip_country1.' '.$ip_city1.'<span class="ms-auto"><i class="fa fa-desktop fa-lg text-body-secondary"></i></span></span>
         </p>';
         }
         $i++;
      }
      $ip_o = trim($ip_o,',').'
   ];';
   
   $sidebarip = '
   <div id="sb_ip" class="list-group mb-2">
      <div class="bg-light text-dark fw-light p-2">
         <a id="carrets_ip" class="link" data-bs-toggle="collapse" href="#l_sb_ip"><i class="toggle-icon fa visually-hidden fa-lg me-2" style="font-size:1.6rem;"></i></a>
         <div class="form-check form-switch d-inline-block">
            <input class="form-check-input" type="checkbox" id="ipbox" /><label class="form-check-label" for="ipbox"><span class="text-body-secondary" data-bs-toggle="tooltip" title="'.geoloc_translate('Voir ou masquer les IP').'"><i class="bi bi-display me-1 h3 align-middle"></i></span>IP</label>
         </div>
         <span class="h6"><span id="ipnb" class="badge bg-secondary rounded-pill float-end">'.$ipnb.'</span></span>
      </div>
      <div class="collapse" id="l_sb_ip">
         <div class="sb_ip list-group-item py-1 border-left-0 border-right-0" >
            <input id="n_filtreip" placeholder="'.geoloc_translate('Filtrer les résultats').'" class="my-1 form-control form-control-sm" type="text" />
         </div>
         '.$sidebarip.'
      </div>
   </div>';
   
      $f = fopen("modules/".$ModPath."/include/iplist.html", "w");
      $w = fwrite($f, $tab_ip);
      @fclose($f);
      @chmod('modules/'.$ModPath.'/include/iplist.html', 0666);
   }
}
//<== for admin

if (array_key_exists('lat',$_GET)) $f_new_lat = floatval ($_GET['lat']);// lat du form de géoreferencement
if (array_key_exists('lng',$_GET)) $f_new_long = floatval ($_GET['lng']);// long du form de géoreferencement
if (array_key_exists('mod',$_GET)) $f_geomod = removeHack($_GET['mod']);
if (array_key_exists('uid',$_GET)) $f_uid = removeHack($_GET['uid']);

$av_ch = '';//chemin pour l'avatar

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

if($mark_typ !==1) {
   $fafont=''; $fafont_js='';
   foreach ($fonts_svg as $v) {
      if($v[0]==$f_mbg) {
         $fafont = '&#x'.substr($v[1],1).';';
         $fafont_js= '\\'.$v[1];
      }
   }
   $ic_b_mbgt ='<span title="'.geoloc_translate('Voir ou masquer membres géoréférencés').'." data-bs-toggle="tooltip" style="font-size:1.8rem; color:'.$mbg_f_co.';" class="fa fa align-middle">'.$fafont.'</span>';
   $ic_b_mbg ='<span title="'.geoloc_translate('Voir sur la carte').'." data-bs-toggle="tooltip" style="font-size:1.8rem; color:'.$mbg_f_co.';" class="fa fa align-middle">'.$fafont.'</span>';
   $ic_b_mbgct ='<span title="'.geoloc_translate('Voir ou masquer membres géoréférencés en ligne').'." data-bs-toggle="tooltip" style="font-size:1.8rem; color:'.$mbgc_f_co.';" class="fa fa-2x align-middle">'.$fafont.'</span>';
   $ic_b_mbgc ='<span title="'.geoloc_translate('Voir sur la carte').'." data-bs-toggle="tooltip" style="font-size:1.8rem; color:'.$mbgc_f_co.';" class="fa fa-2x align-middle">'.$fafont.'</span>';
   $ic_b_acgt ='<span title="'.geoloc_translate('Voir ou masquer anonymes géoréférencés').'." data-bs-toggle="tooltip" style="font-size:1.8rem; color:'.$acg_f_co.';" class="fa fa-2x align-middle">'.$fafont.'</span>';
   $ic_b_acg ='<span title="'.geoloc_translate('Voir sur la carte').'." data-bs-toggle="tooltip" style="font-size:1.8rem; color:'.$acg_f_co.';" class="fa fa-2x align-middle">'.$fafont.'</span>';
}
else {
   $ic_b_mbgt ='<img src="'.$ch_img.$nm_img_mbg.'" title="'.geoloc_translate('Voir ou masquer membres géoréférencés').'." data-bs-toggle="tooltip" alt="'.geoloc_translate('Membre géoréférencé').'" /> ';
   $ic_b_mbg ='<img src="'.$ch_img.$nm_img_mbg.'" title="'.geoloc_translate('Voir sur la carte').'." data-bs-toggle="tooltip" alt="'.geoloc_translate('Membre géoréférencé').'" /> ';
   $ic_b_mbgct ='<img src="'.$ch_img.$nm_img_mbcg.'" title="'.geoloc_translate('Voir ou masquer membres géoréférencés en ligne').'." data-bs-toggle="tooltip" alt="'.geoloc_translate('Membre géoréférencé en ligne').'" /> ';
   $ic_b_mbgc ='<img src="'.$ch_img.$nm_img_mbcg.'" title="'.geoloc_translate('Voir sur la carte').'." data-bs-toggle="tooltip" alt="'.geoloc_translate('Membre géoréférencé en ligne').'" /> ';
   $ic_b_acgt ='<img src="'.$ch_img.$nm_img_acg.'" title="'.geoloc_translate("Voir ou masquer anonymes géoréférencés").'." data-bs-toggle="tooltip" alt="'.geoloc_translate('Anonyme géoréférencé en ligne').'" /> ';
   $ic_b_acg ='<img src="'.$ch_img.$nm_img_acg.'" title="'.geoloc_translate('Voir sur la carte').'." data-bs-toggle="tooltip" alt="'.geoloc_translate('Anonyme géoréférencé en ligne').'" /> ';
}

//Le membre
//cherche info user
$username = isset($cookie) ? $cookie[1] : '';
if(isset($cookie)) {
   $result = sql_query('SELECT uid FROM '.$NPDS_Prefix.'users WHERE uname LIKE "'.$username.'"');
   $row = sql_fetch_array($result);
   $uid = $row['uid'];
   // voir si user existe dans users_extend
   $resul = sql_query('SELECT uid FROM '.$NPDS_Prefix.'users_extend WHERE uid = "'.$uid.'"');
   $found = sql_num_rows($resul);
   //mise à jour users_extend si besoin
   if ($found == 0)
      $res = sql_query("INSERT INTO ".$NPDS_Prefix."users_extend VALUES ('$uid','','','','','','','','','','','','','')");
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

//==> les connectés dans session
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
$krs=0; $mb_con_g='#######'; $ano_o_conn='const ano_features=['; $mbr_geo_on_v ='';
$sidebaronline = '';   $i=0;

while ($row = sql_fetch_array($result)) {
   $users_uid = $row['uid'];//
   $session_guest = $row['guest'];
   $session_host_addr = $row['host_addr'];
   $users_name = $row['name'];
   $users_uname = $row['uname'];
   $users_user_avatar = isset($users_user_avatar) ? $row['user_avatar'] : '';
   $session_user_name = $row['username'];
   $user_lat = $row[''.$ch_lat.''];
   $user_long = $row[''.$ch_lon.''];
   $us_url = $row['url'];
   $us_mns = $row['mns'];
   $us_rs = $row['M2'];

   //determine si c un avatar perso ou standard et fixe l'url de l'image
   $av_ch = preg_match('#\/#', $users_user_avatar) === 1 ?
      $users_user_avatar :
      'images/forum/avatar/'.$users_user_avatar;

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
                  $my_rs.='<a href="'.$v1[1].$y1[1].'" target="_blank"><i class="fab fa-'.$v1[2].' fa-2x text-primary me-2"></i></a>&nbsp;';
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
      $ip_visi_pag ='';
      //=== menu fenetre info
      $imm = ' <a href="user.php?op=userinfo&amp;uname='.$users_uname.'"  target="_blank" ><i class="fa fa-user fa-2x me-2 tooltipbyclass" title="'.translate("Profil").'"></i></a>';
      if ($user)
         $imm .= ' <a href="powerpack.php?op=instant_message&to_userid='.$users_uname.'" ><i class="far fa-envelope fa-2x me-2 tooltipbyclass" title="'.geoloc_translate("Envoyez un message interne").'"></i></a>';
      if ($us_url != '')
         $imm .= ' <a href="'.$us_url.'" target="_blank" ><i class="fas fa-external-link-alt fa-2x me-2 tooltipbyclass" title="'.geoloc_translate("Visitez le site").'"></i></a>';
      if ($us_mns != '')
         $imm .='<a href="minisite.php?op='.$users_uname.'" target="_blank" ><i class="fa fa-desktop fa-2x me-2 tooltipbyclass" title="'.geoloc_translate("Visitez le minisite").'"></i>'.$us_mns.'</a>';

      //construction marker membre on line
      if($mark_typ !==1) $ic_sb_mbgc ='<i style=\"color:'.$mbgc_f_co.';\" class=\"fa fa-'.strtolower($f_mbg).' fa-lg animated faa-pulse me-1\"></i>';
      else $ic_sb_mbgc ='<img src=\"'.$ch_img.$nm_img_mbcg.'\" /> ';
      $mb_con_g = $session_host_addr;
//      $mbr_geo_on .= '[['.$user_long.','.$user_lat.'], "u'.$users_uid.'","'. addslashes($users_uname) .'","'.$av_ch.'", "'.addslashes($imm).'","'.addslashes($my_rsos[$krs]).'"],';
      $sidebaronline .= '';

/*
      $ano_o_conn.= '
      [['.$user_long.','.$user_lat.'],"'. addslashes($users_uname) .'","'.$session_host_addr.'","'.$ip_visi_pag.'","'.$ip_visite.'","'.$ip_city1.'","'.$ip_country1.'","'.$ch_img.'flags/'.strtolower($ip_code_country1).'","'.@gethostbyaddr($ip_ip1).'","AM","'.$av_ch.'", "'.addslashes($imm).'","'.addslashes($my_rsos[$krs]).'"],';
      $sidebaronline .= '
      <a id="a'.$i.'" style="min-height:38px;" class="sb_js sb_ano list-group-item list-group-item-action py-1 px-2 border-left-0 border-right-0 small">'.$ic_b_mbgc.'<span class="ms-2"><span class="float-end">'.addslashes($users_uname).'</span></span></a>';
*/
      $ac++;
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
         $acg++; $acng++;$ac++;
         $test_ip .='<a class="sb_ano list-group-item px-1 py-1 small" title="IP non géoréférencé en ligne" data-bs-toggle="tooltip" >IP '.$acng.' <span style="font-size:0.75rem;">'.urldecode($session_host_addr).'</span></a>';
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
         if(!strstr($ip_ip1, $mb_con_g)) {
            $acg++;
            $ano_o_conn.= '
         [['.$ip_long1.','.$ip_lat1.'],"'.$ip_ip1.'","'.$session_host_addr.'","'.$ip_visi_pag.'","'.$ip_visite.'","'.$ip_city1.'","'.$ip_country1.'","'.$ch_img.'flags/'.strtolower($ip_code_country1).'","'.@gethostbyaddr($ip_ip1).'","A"],';
            $sidebaronline .= '
            <a id="a'.$i.'" style="min-height:38px;" class="sb_js sb_ano list-group-item list-group-item-action py-1 px-2 border-left-0 border-right-0 small">'.$ic_b_acg.'<span class="ms-2">'.$i.'<span class="float-end">'.$ip_ip1.'</span></span></a>';
         }
         else{
            $ano_o_conn.= '
         [['.$ip_long1.','.$ip_lat1.'],"'. addslashes($users_uname) .'","'.$session_host_addr.'","'.$ip_visi_pag.'","'.$ip_visite.'","'.$ip_city1.'","'.$ip_country1.'","'.$ch_img.'flags/'.strtolower($ip_code_country1).'","'.@gethostbyaddr($ip_ip1).'","AM"],';
            $sidebaronline .= '
            <a id="a'.$i.'" style="min-height:38px;" class="sb_js sb_ano list-group-item list-group-item-action py-1 px-2 border-left-0 border-right-0 small">'.$ic_b_mbgc.'<span class="ms-2"><span class="float-end">'.addslashes($users_uname).'</span></span></a>';
         }
         $ac++;
         //construction marker anonyme on line
      }
   }
   $krs++;
   $i++;
}
$ano_o_conn = trim($ano_o_conn,',').'
   ];';
$sidebaronline ='
            <div id="sb_ano" class="list-group mb-2">
               <div class="bg-light text-dark fw-light p-2"><a id="carrets_ac" class="link" data-bs-toggle="collapse" href="#l_sb_ano"><i class="toggle-icon fa fa-caret-down fa-lg me-2" style="font-size:1.6rem;"></i></a>
                  <div class="form-check form-switch d-inline-block">
                     <input class="form-check-input" type="checkbox" checked="checked" data-bs-toggle="tooltip" title="'.geoloc_translate('Voir ou masquer anonymes géoréférencés').'" id="conbox" /><label class="form-check-label" for="conbox"> '.geoloc_translate('Visiteur en ligne').'</label>
                  </div>
                  <span class="h6"><span class="badge bg-danger rounded-pill float-end">'.$ac.'</span></span>
                  </div><div class="collapse" id="l_sb_ano">
                  '.$test_ip
                  .$sidebaronline.'
               </div>
            </div>';

//==>les membres
$mbgr = 0;
$membre = sql_query ('SELECT * FROM '.$NPDS_Prefix.'users u LEFT JOIN '.$NPDS_Prefix.'users_extend ue ON u.uid = ue.uid ORDER BY u.uname');
$total_membre = sql_num_rows($membre);//==> membres du site
$k=0;
$us_visit=array();$sidebarmembres='';
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
      $imm = '<a href="user.php?op=userinfo&amp;uname='.$us_uname.'"  target="_blank" ><i class="fa fa-user fa-lg me-3 tooltipbyclass" title="'.translate("Profil").'"></i></a>';
      if ($user)
         $imm .= '<a href="powerpack.php?op=instant_message&to_userid='.$us_uname.'" ><i class="far fa-envelope fa-lg me-3 tooltipbyclass" title="'.geoloc_translate("Envoyez un message interne").'"></i></a>';
      if ($us_url != '')
         $imm .= '<a href="'.$us_url.'" target="_blank" ><i class="fas fa-external-link-alt fa-lg me-3 tooltipbyclass" title="'.geoloc_translate("Visitez le site").'"></i></a>';
      if ($us_mns)
         $imm .='<a href="minisite.php?op='.$us_uname.'" target="_blank" ><i class="fa fa-desktop fa-lg me-3 tooltipbyclass" title="'.geoloc_translate("Visitez le minisite").'"></i></a>';

      //==> construction du fichier json
      $cont_json .='{"lat":'.$us_lat.', "lng":'.$us_long.', "html":"<img src=\\"'.$av_ch.'\\" width=\\"32\\" height=\\"32\\" align=\\"middle\\" />&nbsp;'.addslashes($us_uname).'<br /><span class=\\"text-body-secondary\\">'.geoloc_translate("Dernière visite").' : </span>'.$visit.'<br />", "label":"<span>'. addslashes($us_uname) .'</span>", "icon":"icon"},';
      $cont_geojson .='
   {"type": "Feature", "id": "u_'.$us_uid.'", "properties": { "name": "'.addslashes($us_uname).'", "description": ""}, "geometry": { "type": "Point", "coordinates": ['.$us_long.','.$us_lat.'] } },';

      //construction marker membre
      if($mark_typ !==1) $ic_sb_mbg ='<i style=\"color:'.$mbg_f_co.'; opacity:0.4;\" class=\"fa fa-'.strtolower($f_mbg).' fa-lg me-1\"></i>';
      else $ic_sb_mbg ='<img src=\"'.$ch_img.$nm_img_mbg.'\" /> ';

      $sidebarmembres .='
      <a id="u'.$us_uid.'" style="min-height:38px;" class="filtrmb_js sb_js sb_member list-group-item list-group-item-action py-1 px-2 border-left-0 border-right-0 small">'.$ic_b_mbg.'<span class="ms-2 nlfiltrmb" data-searchterm="'. addslashes($us_uname) .'">'. addslashes($us_uname) .'</span><img alt="avatar'. addslashes($us_uname) .'" class="float-end n-ava-32" src="'.$av_ch.'" loading="lazy"/></a>';

      $mbr_geo_off .= '
      [['.$us_long.','.$us_lat.'], "u'.$us_uid.'","'. addslashes($us_uname) .'","'.$av_ch.'","'.$visit.'","'.addslashes($imm).'"],';
      $mbr_geo_off_v .= '
   var u'.$us_uid.' = ol.proj.transform(['.$us_long.', '.$us_lat.'], "EPSG:4326", "EPSG:3857");';
   }
   $k++;
}
$mbr_geo_off = trim($mbr_geo_off,',').'
   ];';

$sidebarmembres ='
            <div id="sb_member" class="list-group mb-2">
               <div class="bg-light text-dark fw-light p-2"><a id="carrets_mb" class="" data-bs-toggle="collapse" href="#l_sb_member"><i class="toggle-icon fa fa-caret-down fa-lg me-2" style="font-size:1.6rem;"></i></a>
                  <div class="form-check form-switch d-inline-block">
                     <input class="form-check-input" type="checkbox" checked="checked" data-bs-toggle="tooltip" title="'.geoloc_translate('Voir ou masquer membres géoréférencés').'" id="memberbox" /><label class="form-check-label" for="memberbox">'.$ic_b_mbgt.' '.geoloc_translate('Membre').'</label>
                  </div>
                  <span class="h6"><span id="mbnb" class="badge bg-secondary rounded-pill float-end">'.$mbgr.'</span></span>
               </div>
               <div class="collapse" id="l_sb_member">
                  <div class="sb_member list-group-item py-1 border-left-0 border-right-0" >
                     <input id="n_filtrmb" placeholder="'.geoloc_translate('Filtrer les utilisateurs').'" class="my-1 form-control form-control-sm" type="text" />
                  </div>
                  '.$sidebarmembres.'
               </div>
            </div>';


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
//<== les membres

$olng = $mbcng+$acng;//==> on line non géoréférencés anonyme et membres
$olg = $mbcg+$acg;//==> on line géoréférencés anonyme et membres

$fond_provider = array(
   ['OSM', geoloc_translate("Plan").' (OpenStreetMap)'],
   ['modisterra', geoloc_translate("Satellite").' (NASA)'],
   ['natural-earth-hypso-bathy', geoloc_translate("Relief").' (mapbox)'],
   ['geography-class', geoloc_translate("Carte").' (mapbox)'],
   ['microsoft.base.road', geoloc_translate("Plan").' (Azure maps)'],
   ['microsoft.imagery', geoloc_translate("Satellite").' (Azure maps)'],
   ['microsoft.base.darkgrey', geoloc_translate("Sombre").' (Azure maps)'],
   ['sat-google', geoloc_translate("Satellite").' (Google maps)'],
   ['World_Imagery', geoloc_translate("Satellite").' (ESRI)'],
   ['World_Shaded_Relief', geoloc_translate("Relief").' (ESRI)'],
   ['World_Physical_Map', geoloc_translate("Physique").' (ESRI)'],
   ['World_Topo_Map', geoloc_translate("Topo").' (ESRI)'],
   ['stamen_terrain', geoloc_translate("Plan").' (Stadia maps)'],
   ['stamen_watercolor', geoloc_translate("Dessin").' (Stadia maps)'],
   ['alidade_smooth', geoloc_translate("Plan clair").' (Stadia maps)'],
   ['stamen_toner', geoloc_translate("Plan sombre").' (Stadia maps)'],
);
if($api_key_azure=='' and $api_key_mapbox=='')
   unset($fond_provider[2],$fond_provider[3],$fond_provider[4],$fond_provider[5],$fond_provider[6]);
elseif($api_key_azure=='')
   unset($fond_provider[4],$fond_provider[5],$fond_provider[6]);
elseif($api_key_mapbox=='')
   unset($fond_provider[2],$fond_provider[3]);
$optcart = '';
foreach ($fond_provider as $k => $v) {
   $sel = $v[0]==$cartyp ? 'selected="selected"' : '';
   switch($k){
      case '0': $optcart .= '
                           <optgroup label="OpenStreetMap">';break;
      case '1': $optcart .= '
                           <optgroup label="NASA">';break;
      case '2': $optcart .= '
                           <optgroup label="Mapbox">';break;
      case '4': $optcart .= '
                           <optgroup label="Azure Maps">'; break;
      case '7': $optcart .= '
                           <optgroup label="Google">'; break;
      case '8': $optcart .= '
                           <optgroup label="ESRI">';break;
      case '12': $optcart .= '
                           <optgroup label="Stadia Maps">';break;

   }
   $optcart .= '
                              <option '.$sel.' value="'.$v[0].'">'.$v[1].'</option>';
   switch($k){
      case '0': case '1': case '3': case '6': case '7': case '11': case '15': $optcart .= '
                           </optgroup>'; break;
   }
}

$source_fond=''; $max_r=''; $min_r='';$layer_id='';
switch ($cartyp) {
   case 'sat-google':
      $source_fond='
      new ol.source.XYZ({
         url: "https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}",
         crossOrigin: "Anonymous",
         attributions: " &middot; <a href=\"https://www.google.at/permissions/geoguidelines/attr-guide.html\">Map data ©2015 Google</a>"
      })';
      $max_r='40000';
      $min_r='0';
      $layer_id= $cartyp;
   break;
   case 'microsoft.base.road': case 'microsoft.imagery': case 'microsoft.base.darkgrey':
      $source_fond='
      new ol.source.ImageTile({
         url: `https://atlas.microsoft.com/map/tile?subscription-key='.$api_key_azure.'&api-version=2.0&tilesetId='.$cartyp.'&zoom={z}&x={x}&y={y}&tileSize=256&language=EN`,
         attributions: `© ${new Date().getFullYear()} TomTom, Microsoft`
      })';
      $max_r='40000';
      $min_r='0';
      $layer_id= $cartyp;
   break;
   case 'natural-earth-hypso-bathy': case 'geography-class':
      $source_fond='
      new ol.source.TileJSON({
         url: "https://api.tiles.mapbox.com/v4/mapbox.'.$cartyp.'.json?access_token='.$api_key_mapbox.'",
         attributions: "© <a href=\"https://www.mapbox.com/about/maps/\">Mapbox</a> © <a href=\"http://www.openstreetmap.org/copyright\">OpenStreetMap</a> <strong><a href=\"https://www.mapbox.com/map-feedback/\" target=\"_blank\">Improve this map</a></strong>"
      })';
      $max_r='40000';
      $min_r='2000';
      $layer_id= $cartyp;
   break;
   case 'World_Imagery':case 'World_Shaded_Relief':case 'World_Physical_Map':case 'World_Topo_Map':
      $source_fond='new ol.source.XYZ({
         attributions: ["Powered by Esri", "Source: Esri, DigitalGlobe, GeoEye, Earthstar Geographics, CNES/Airbus DS, USDA, USGS, AeroGRID, IGN, and the GIS User Community"],
         url: "https://services.arcgisonline.com/ArcGIS/rest/services/'.$cartyp.'/MapServer/tile/{z}/{y}/{x}",
         maxZoom: 23
     })';
      $max_r='40000';
      $min_r='0';
      $layer_id= $cartyp;
   break;
   case 'stamen_terrain': case 'stamen_watercolor': case 'alidade_smooth': case "stamen_toner":
      $source_fond='
      new ol.source.StadiaMaps({})';
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

//==> construction js
if(!defined('OL')) {
   define('OL','ol');
   $ecr_scr ='
<script type="text/javascript" src="'.$nuke_url.'/lib/ol/ol.js"></script>';
}
$ecr_scr .= '
<script type="text/javascript">
//<![CDATA[
   var map;
   var dd = new Date().toISOString().split("T");

   if (!$("link[href=\'/lib/ol/ol.css\']").length)
      $("head link[rel=\'stylesheet\']").last().after("<link rel=\'stylesheet\' href=\''.$nuke_url.'/lib/ol/ol.css\' type=\'text/css\' media=\'screen\'>");
   if (!$("link[href=\'modules/geoloc/include/css/geoloc_style.css\']").length)
      $("head link[rel=\'stylesheet\']").last().after("<link rel=\'stylesheet\' href=\''.$nuke_url.'/modules/geoloc/include/css/geoloc_style.css\' type=\'text/css\' media=\'screen\'>");
   if (!$("link[href=\'lib/bootstrap/dist/css/bootstrap-icons.css\']").length)
      $("head link[rel=\'stylesheet\']").last().after("<link rel=\'stylesheet\' href=\''.$nuke_url.'/lib/bootstrap/dist/css/bootstrap-icons.css\' type=\'text/css\' media=\'screen\'>");

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
   '.$ano_o_conn.$mbr_geo_off.$mbr_geo_off_v.$ip_o.'
   var popup = new ol.Overlay({
      element: document.getElementById("ol_popup"),
      offset : [0,-10],
      autoPan: true,
      autoPanAnimation: {
         duration: 1000
      },
      insertFirst : false,
      stopEvent : true,
   }),
   popuptooltip = new ol.Overlay({
     element: document.getElementById("ol_tooltip"),
     stopEvent : true,
   });

   var src_con = new ol.source.Vector({}),
       src_con_length = ano_features.length;
   for (let i = 0; i < src_con_length; i++){
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
         ip_qui: ano_features[i][9],
      });
         iconFeature.setId(("a"+i));
         src_con.addFeature(iconFeature);
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
   ';
if(autorisation(-127) and $geo_ip==1)
   $ecr_scr .='
   var src_ip = new ol.source.Vector({});
   var src_ip_length = ip_features.length;
   for (var i = 0; i < src_ip_length; i++){
      var iconFeature = new ol.Feature({
         geometry: new ol.geom.Point(ol.proj.transform(ip_features[i][0], "EPSG:4326","EPSG:3857")),
         ip: ip_features[i][1],
         visit: ip_features[i][2],
         flag : ip_features[i][3],
         pays : ip_features[i][4],
         city : ip_features[i][5],
         post : ip_features[i][6],
         spam : ip_features[i][7],
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
if($mark_typ !== 1) { // marker webfont
   $ecr_scr .='
      //==> marker webfont
      var styleUser = new ol.style.Style({
         text: new ol.style.Text({
            text: "'.$fafont_js.'",
            font: "900 '.$mbg_sc.'px \'Font Awesome 5 Free\'",
            bottom: "Bottom",
            fill: new ol.style.Fill({color: "'.$mbg_f_co.'"}),
            stroke: new ol.style.Stroke({color: "'.$mbg_t_co.'", width: '.$mbg_t_ep.'})
        })
      });
      var styleCon = function(feature) {
         let fill = new ol.style.Fill({color: "'.$acg_f_co.'"});
         let stroke =  new ol.style.Stroke({color: "'.$acg_t_co.'", width: '.$acg_t_ep.'});
         if (feature.get("ip_qui") == "AM") {
            fill = new ol.style.Fill({color: "'.$mbgc_f_co.'"});
            stroke = new ol.style.Stroke({color: "'.$mbgc_t_co.'", width: '.$mbgc_t_ep.'})
         } 
         let style = new ol.style.Style({
            text: new ol.style.Text({
               text: "'.$fafont_js.'",
               font: "900 '.$mbg_sc.'px \'Font Awesome 5 Free\'",
               bottom: "Bottom",
               fill: fill,
               stroke: stroke
            })
         });
         return style;
      }';
}
else { // markers images
   $ecr_scr .='
      //==> markers images
      var
         styleUser = new ol.style.Style({
            image: new ol.style.Icon({
               src: "'.$ch_img.$nm_img_mbg.'",
               imgSize:['.$w_ico_b.','.$h_ico_b.']
            })
         }),
         styleCon = function(feature) {
            let src = "'.$ch_img.$nm_img_acg.'";
            if (feature.get("ip_qui") == "AM")
               src = "'.$ch_img.$nm_img_mbcg.'";
            let style = new ol.style.Style({
               image: new ol.style.Icon({
                  src: src,
                  imgSize:['.$w_ico_b.','.$h_ico_b.']
               })
            })
            return style;
         };';
}
$ecr_scr .='
var iconGeoref = function(feature) {
   const el = document.createElement("div");
   el.style.color = "var(--bs-primary, black)";
   if (el.style.color !== "") {
      document.body.appendChild(el);
      const rgb = getComputedStyle(el).color;
      document.body.removeChild(el);
      let style = new ol.style.Style({
         text: new ol.style.Text({
            text: "\uf4ed",
            font: " 36px \'bootstrap-icons\'",
            bottom: "Bottom",
            rotation : 0.9,
            fill: new ol.style.Fill({color: rgb}),
         })
      })
      return style;
   }
},';
if(autorisation(-127) and $geo_ip==1)
   $ecr_scr .='
         styleIp = new ol.style.Style({
            text: new ol.style.Text({
               text: "\uf302",
               font: " 24px \'bootstrap-icons\'",
               bottom: "Bottom",
               fill: new ol.style.Fill({color: "rgba(0, 0, 0, 1)"}),
               stroke : new ol.style.Stroke({color: "rgba(255, 255, 255,0.8)", width: 3})
            })
         }),
         styleIpdanger = new ol.style.Style({
            text: new ol.style.Text({
               text: "\uf302",
               font: " 24px \'bootstrap-icons\'",
               bottom: "Bottom",
               fill: new ol.style.Fill({color: "rgba(220, 53, 69, 1)"}),
               stroke : new ol.style.Stroke({color: "rgba(255, 255, 255,0.8)", width: 3})
            })
         }),';
$ecr_scr .='
         styleCountries = new ol.style.Style({
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
            style: styleUser
         }),
         con_markers = new ol.layer.Vector({
            id: "anony",
            source: src_con,
            style: styleCon
         }),
         grouputilisateurs = new ol.layer.Group({
            id :"grouputilisateurs",
            layers : [
               user_markers,
               con_markers,
            ],
         }),
         countries = new ol.layer.Vector({
            id: "countries",
            source: src_countries,
            style: styleCountries,
            visible: false
         })
         graticule = new ol.layer.Graticule();

         const extmb = src_user.getExtent();
         const extcon = src_con.getExtent();
         var extgroup = ol.extent.createEmpty();
         grouputilisateurs.getLayers().forEach(l=>{ol.extent.extend(extgroup,l.getSource().getExtent())});';

if(autorisation(-127) and $geo_ip==1)
   $ecr_scr .='
      // ==> cluster IPs
      const extip = src_ip.getExtent();
      var
         clusterSource = new ol.source.Cluster({
            distance: "40",
            minDistance : "15",
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
                  let r=19;
                  if(size < 10) r=15;
                  else if(size < 100) r=17;
                  else if(size > 999) r=24;
                  if (size > 1) {
                     let color = "rgba(99, 99, 98, 0.7)";
                     feature.get("features").every(ip => {
                        if(ip.values_.spam !="") {
                           color = "rgba(220, 53, 69, 0.7)";
                           return false;
                        } return true;
                     })
                     style = new ol.style.Style({
                        image: new ol.style.Circle({
                           radius: r,
                           stroke: new ol.style.Stroke({color: "rgba(255, 255, 255,0.1)",width:8}),
                           fill: new ol.style.Fill({color: color}),
                        }),
                        text: new ol.style.Text({
                           text: size.toString()+"\n"+"\uF5ED",
                           font: "12px \'bootstrap-icons\'",
                           fill: new ol.style.Fill({color: "#fff"}),
                           textBaseline:"bottom",
                           offsetY: 14,
                        })
                     });
                  }
                  else {
                     style = feature.get("features")[0].values_.spam !="" ? styleIpdanger : styleIp;
                     styleCache[size] = "";
                  }
                  //styleCache[size] = style;
               }
               return style;
            }
         });
      // <== cluster IPs';
$ecr_scr .='
      const extent = ol.proj.get("EPSG:3857").getExtent().slice();
      extent[0] += extent[0];
      extent[2] += extent[2];
      var src_fond = '.$source_fond.',
          minR='.$min_r.',
          maxR='.$max_r.',
          layer_id="'.$layer_id.'",
          fond_carte = new ol.layer.Tile({
            id:layer_id,
            source: src_fond,
            minResolution: minR,
            maxResolution: maxR,
            preload: Infinity,
          }),
          attribution = new ol.control.Attribution({collapsible: true}),
          view = new ol.View({
            center: ol.proj.fromLonLat([13, 46]),
            zoom: 5,
            minZoom:2,
//                        maxZoom:19,//////

            extent,
          }),
          fullscreen = new ol.control.FullScreen({
             source: "map-wrapper",
          }),
          scaleline = new ol.control.ScaleLine,
          zoomslider = new ol.control.ZoomSlider();

      var map = new ol.Map({
         interactions: new ol.interaction.defaults.defaults({
            constrainResolution: true, onFocusOnly: true
         }),
         controls: new ol.control.defaults.defaults({attribution: false}).extend([attribution,fullscreen, mousePositionControl, scaleline, zoomslider]),
         target: document.getElementById("map"),
         layers: [
            fond_carte,
            countries,
            grouputilisateurs,';
if(autorisation(-127) and $geo_ip==1)
   $ecr_scr .='
            ip_cluster,';
            
$ecr_scr .='
         ],
         overlays: [popup,popuptooltip],
         view: view
      });
      view.fit(extgroup);
      let z = view.getZoom();
      z>14 ? view.setZoom(14) : view.setZoom(z-1);

      var button = document.createElement("button");
      button.innerHTML = "&#xf0d8";
      button.setAttribute("title", "'.geoloc_translate('Masquer').'")
      var sidebarSwitch = function(e) {
         if($("#sidebar").hasClass("show")) {
            $("#sidebar").collapse("toggle");
            button.innerHTML = "&#xf0d7";
            button.setAttribute("data-bs-original-title", "'.geoloc_translate('Voir').'")
         }
         else {
            $("#sidebar").collapse("show");
            button.innerHTML = "&#xf0d8";
            button.setAttribute("data-bs-original-title", "'.geoloc_translate('Masquer').'")
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
//==> construction sidebar';

$mess_mb='';
$sb_georef='';

if ($username !='') {
   if ($ue_lat !='' and $ue_long !='') {
      $infooo ='<div id="oldloc"><strong>'.geoloc_translate("Coordonnées enregistrées :").'</strong><br /><span class="text-body-secondary">'.geoloc_translate("Latitude").' :</span> '.$ue_lat.'<br /><span class="text-body-secondary">'.geoloc_translate("Longitude").' :</span> '.$ue_long.'<br /></div><br /><strong>'.geoloc_translate("Voulez vous changer pour :").'</strong><br />';
      $mess_mb = geoloc_translate('Cliquer sur la carte pour modifier votre position.');
   }
   else {
      $infooo = '<div id="newloc"><strong>'.geoloc_translate("Vous n'êtes pas géoréférencé.").'</strong></div><br /><strong>'.geoloc_translate("Voulez vous le faire à cette position:").'</strong><br />';
      $mess_mb = geoloc_translate('Cliquer sur la carte pour définir votre géolocalisation.');
   }
   $sb_georef.='   <div class="list-group-item list-group-item-action py-1 border-left-0 border-right-0"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" data-bs-toggle="tooltip" title="'.geoloc_translate("Définir ou modifier votre position.").'" id="georefbox" /><label for="georefbox" class="form-check-label">'.geoloc_translate("Définir ou modifier votre position.").'</label></div><span class="help-block small muted"><span style="display: inline-block; transform: rotate(45deg);"><i class="bi bi-pin me-1 h3 text-primary"></i></span>'.$mess_mb.'</span></div>';

   $ecr_scr .= '
//==> Georeferencement par user
      var src_georef = new ol.source.Vector({});
      var pointGeoref = new ol.Feature({
        geometry: new ol.geom.Point(ol.proj.transform([0, 0], "EPSG:4326","EPSG:3857"))
      });
      pointGeoref.setId("g");
      src_georef.addFeature(pointGeoref);
      var georef_marker = new ol.layer.Vector({
         id: "georeferencement",
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
            placement: "auto",
            offset:"0,18",
            animation: false,
            html: true,
            title:\'<span class="fw-light">'.geoloc_translate("Géolocalisation").'</span><button type="button" id="close" class="btn-close float-end" onclick="$(\\\'.popover\\\').hide();"></button>\',
            content: \'<form action="" onsubmit=" window.location.href = \\\'modules.php?ModPath=geoloc&ModStart=geoloc&lng=\'+lng.toFixed(6)+\'&lat=\'+lat.toFixed(6)+\'&mod=neo&uid=\\\'; return false;">\'
        + \'<img src="'.$the_av_ch.'" class="img-thumbnail n-ava-40 me-2" loading="lazy" /><span class="lead">'.$username.'</span>\'
        + \''.$infooo.'\'
        + \'<div id="lalo"><span class="text-body-secondary">'.geoloc_translate("Latitude").' : </span>\' + lat.toFixed(6) + \'<br /><span class="text-body-secondary">'.geoloc_translate("Longitude").' : </span>\' + lng.toFixed(6) + \'</div>\'
        + \'<button type="submit" class ="btn btn-primary btn-sm mt-2">'.geoloc_translate("Enregistrez").'</button>\'
        + \'<input type="hidden" id="html" value="'.addslashes($user_from).'" />\'
        + \'<input type="hidden" id="longitude" value="\' + lng.toFixed(6) + \'" />\'
        + \'<input type="hidden" id="latitude" value="\' + lat.toFixed(6) + \'" />\'
        + \'<input type="hidden" id="modgeo" value="neo" />\'
        + \'</form>\'
         });
         $(element).popover("show");
      });
//<== Georeferencement par user';
}
else
   $mess_mb='';

$ecr_scr .='

//<== construction sidebar

//==> comportement sidebar et layers
   $(document).ready(function () {
     centeronMe = function(u) {
         if (u.substr(0,1) == "i") {
            view.setCenter(src_ip.getFeatureById(u).getGeometry().getFlatCoordinates());
            view.adjustCenter([240,0]);
         }
         if (u.substr(0,1) == "a") {
            view.setCenter(src_con.getFeatureById(u).getGeometry().getFlatCoordinates());
            view.adjustCenter([240,80]);
         }
         if(u.substr(0,1) == "u") {
            let mb = src_user.getFeatureById(u);
            let ici = mb.getGeometry().getFlatCoordinates();
            view.setCenter(ici);
            view.adjustCenter([240,0]);
            map.getView().setZoom(5);
            $("#ol_popup").show();
            container.innerHTML = \'<img class="me-2 img-thumbnail n-ava" src="\' + mb.get("ava") + \'" align="middle" /><span class="lead">\' + mb.get("pseudo") + \'</span><div class="my-2">'.geoloc_translate("Dernière visite").' : \' + mb.get("lastvisit") + \'</div><hr /><div class="text-center lead">\' + mb.get("userlinks") + \'</div>\';
            popup.setPosition(ici);
         }
         $("#"+u).addClass("animated faa-horizontal faa-slow" );
         map.getView().setZoom(17);
         $("#map .tooltipbyclass").tooltip({placement: "top", container:"#map",});
      }

      const usersidebar = document.querySelectorAll("a.sb_js");
      usersidebar.forEach(item=>{
         item.style.cursor="pointer";
         item.addEventListener("click",()=> {
            $(".sb_js").removeClass( "animated faa-horizontal faa-slow" );
            centeronMe(item.getAttribute("id"));
         });
      });

      $("#georefbox").change("click", function () {
         if(this.checked) {
            popuptooltip.setPosition(undefined);
            popup.setPosition(null);
            $("#memberbox, #conbox").prop("disabled", true);
            $("#carrets_mb i,#carrets_ac i").removeClass("fa-caret-up").addClass("fa-caret-down visually-hidden");
            grouputilisateurs.getLayers().forEach(l=>{l.setVisible(false)});';
if(autorisation(-127) and $geo_ip==1)
   $ecr_scr .='
            $("#ipbox").prop("disabled", true);
            $("#carrets_ip i").removeClass("fa-caret-up").addClass("fa-caret-down visually-hidden");
            $("#l_sb_ip").removeClass("show");
            ip_cluster.setVisible(false);';
$ecr_scr .='
            map.addOverlay(georef_popup);
            map.addLayer(georef_marker);
            containertip.attr("data-bs-original-title", "'.geoloc_translate('Cliquer sur la carte pour modifier votre position.').'").tooltip("show");
            pointGeoref.getGeometry().setCoordinates(view.getCenter());
            popuptooltip.setPosition(view.getCenter());
            $("#l_sb_member, #l_sb_ip, #l_sb_ano").removeClass("show");
         } else {
            popuptooltip.setPosition(undefined);
            containertip.tooltip("hide");
            map.removeOverlay(georef_popup);
            var element = georef_popup.getElement();
            $(element).popover("dispose");
            map.removeLayer(georef_marker);
            $("#memberbox, #conbox").prop("disabled", false);
            $("#memberbox, #conbox").prop("checked", true);
            $("#carrets_mb i,#carrets_ac i").removeClass("fa-caret-up visually-hidden").addClass("fa-caret-down");
            grouputilisateurs.getLayers().forEach(l=>{l.setVisible(true)});';
if(autorisation(-127) and $geo_ip==1)
   $ecr_scr .='
            $("#ipbox").prop("disabled", false);
            $("#ipbox").prop("checked", false);
            $("#carrets_ip i").removeClass("fa-caret-down").addClass("visually-hidden");
            ip_cluster.setVisible(false);';
$ecr_scr .='
         }
      });

      $("#grillebox").change("click", function () {
         this.checked ? graticule.setMap(map) : graticule.setMap(null);
      });
      $("#conbox").change("click", function () {
         if(this.checked) {
            $("#carrets_ac i").removeClass("fa-caret-down visually-hidden").addClass("fa-caret-up");
            con_markers.setVisible(true);
            console.log(extcon);
            if(extcon[0] != "Infinity")
               view.fit(extcon);
            let z = view.getZoom();
            z>14 ? view.setZoom(14) : view.setZoom(z-0.5);
            $("#l_sb_ano").addClass("show");
         } else {
            $("#carrets_ac i").removeClass("fa-caret-up").addClass("fa-caret-down visually-hidden");
            $("#ol_popup").hide();
            con_markers.setVisible(false);
            $("#l_sb_ano").removeClass("show");
            $(".sb_js").removeClass( "animated faa-horizontal faa-slow" );
         }
      });
      $("#memberbox").change("click", function () {
         if(this.checked) {
            $("#carrets_mb i").removeClass("fa-caret-down visually-hidden").addClass("fa-caret-up");
            user_markers.setVisible(true);
            if(extmb[0] != "Infinity")
               view.fit(extmb);
            let z = view.getZoom();
            z>14 ? view.setZoom(14) : view.setZoom(z-0.5);
            $("#l_sb_member").addClass("show");
         } else {
            $("#carrets_mb i").removeClass("fa-caret-up").addClass("fa-caret-down visually-hidden");
            $("#ol_popup").hide();user_markers.setVisible(false);
            $("#l_sb_member").removeClass("show");
            $(".sb_js").removeClass( "animated faa-horizontal faa-slow" );
         }
      });';
if(autorisation(-127) and $geo_ip==1)
   $ecr_scr .='
      $("#ipbox").change("click", function () {
         $("#ol_popup").hide();
         if(this.checked) {
            $("#carrets_ip i").removeClass("fa-caret-down visually-hidden").addClass("fa-caret-up");
            ip_cluster.setVisible(true);
            view.fit(extip);
            $("#l_sb_ip").addClass("show");
         } else {
            $("#carrets_ip i").removeClass("fa-caret-up").addClass("fa-caret-down visually-hidden");
            ip_cluster.setVisible(false);
            $("#l_sb_ip").removeClass("show");
            $(".sb_js").removeClass( "animated faa-horizontal faa-slow" );
         }
      });';
$ecr_scr .='
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
            map.getLayers().item(0).setProperties({"id":cartyp});
         break;
         case "sat-google":
            fond_carte.setSource(new ol.source.XYZ({
               url: "https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}",
               crossOrigin: "Anonymous",
               attributions: " &middot; <a href=\"https://www.google.at/permissions/geoguidelines/attr-guide.html\">Map data ©2015 Google</a>",
               maxZoom: 19,
            }));
            map.getLayers().item(0).setProperties({"id":cartyp});
         break;
         case "microsoft.base.road": case "microsoft.imagery": case "microsoft.base.darkgrey":
            fond_carte.setSource(new ol.source.ImageTile({
               url: `https://atlas.microsoft.com/map/tile?subscription-key='.$api_key_azure.'&api-version=2.0&tilesetId=`+cartyp+`&zoom={z}&x={x}&y={y}&tileSize=256&language=EN`,
               crossOrigin: "anonymous",
               attributions: `© ${new Date().getFullYear()} TomTom, Microsoft`,
            }));
            map.getLayers().item(0).setProperties({"id":cartyp});
            fond_carte.setMinResolution(1);
         break;

         case "natural-earth-hypso-bathy": case "geography-class":
            fond_carte.setSource(new ol.source.TileJSON({
               url: "https://api.tiles.mapbox.com/v4/mapbox."+cartyp+".json?access_token='.$api_key_mapbox.'",
               attributions:"© <a href=\"https://www.mapbox.com/about/maps/\">Mapbox</a> © <a href=\"http://www.openstreetmap.org/copyright\">OpenStreetMap</a> <strong><a href=\"https://www.mapbox.com/map-feedback/\" target=\"_blank\">Improve this map</a></strong>"
            }));
            fond_carte.setMinResolution(3000);
            fond_carte.setMaxResolution(40000);
            map.getLayers().item(0).setProperties({"id":cartyp});
         break;

         case "stamen_terrain": case "stamen_watercolor": case "alidade_smooth": case "stamen_toner":
            fond_carte.setSource(new ol.source.StadiaMaps({layer:cartyp}));
            map.getLayers().item(0).setProperties({"id":cartyp});
         break;
         
         case "modisterra":
            $("#dayslider").addClass("show");
            var datejour="'.$date_jour.'";
            var today = new Date();
            fond_carte.setSource(new ol.source.XYZ({
               url: "https://gibs-{a-c}.earthdata.nasa.gov/wmts/epsg3857/best/VIIRS_SNPP_CorrectedReflectance_TrueColor/default/"+datejour+"/GoogleMapsCompatible_Level9/{z}/{y}/{x}.jpg",
               attributions: "We acknowledge the use of imagery provided by services from NASA\'s Global Imagery Browse Services (GIBS), part of NASA\'s Earth Observing System Data and Information System (EOSDIS)."
            }));
            $("#nasaday").on("input change", function(event) {
               var newDay = new Date(today.getTime());
               newDay.setUTCDate(today.getUTCDate() + Number.parseInt(event.target.value));
               datejour = newDay.toISOString().split("T")[0];
               var datejourFr = datejour.split("-");
               $("#dateimages").html(datejourFr[2]+"/"+datejourFr[1]+"/"+datejourFr[0]);
               fond_carte.setSource(new ol.source.XYZ({
                  url: "https://gibs-{a-c}.earthdata.nasa.gov/wmts/epsg3857/best/VIIRS_SNPP_CorrectedReflectance_TrueColor/default/"+datejour+"/GoogleMapsCompatible_Level9/{z}/{y}/{x}.jpg",
                  attributions: "We acknowledge the use of imagery provided by services from NASA\'s Global Imagery Browse Services (GIBS), part of NASA\'s Earth Observing System Data and Information System (EOSDIS)."
               }));
            });
            fond_carte.setMinResolution(2);
            fond_carte.setMaxResolution(40000);
            map.getLayers().item(0).setProperties({"id":cartyp});
         break;
         case "World_Imagery":case "World_Shaded_Relief":case "World_Physical_Map":case "World_Topo_Map":
            fond_carte.setSource(new ol.source.XYZ({
               attributions: ["Powered by Esri", "Source: Esri, DigitalGlobe, GeoEye, Earthstar Geographics, CNES/Airbus DS, USDA, USGS, AeroGRID, IGN, and the GIS User Community"],
               attributionsCollapsible: true,
               url: "https://services.arcgisonline.com/ArcGIS/rest/services/"+cartyp+"/MapServer/tile/{z}/{y}/{x}",
               maxZoom: 23
           }));
           map.getLayers().item(0).setProperties({"id":cartyp});
         break;
      }
   });
//<== comportement sidebar et layers

// ==> fallback de résolution
   map.getView().on("propertychange", function(e) {
      switch (e.key) {
         case "resolution":
            var idLayer = map.getLayers().item(0).get("id");
            if((idLayer=="natural-earth-hypso-bathy" || idLayer=="geography-class") && e.oldValue < 3000) {
               fond_carte.setSource(new ol.source.OSM());
               fond_carte.setProperties({"id":"OSM", "minResolution":"0", "maxResolution":"40000" });
               $("#cartyp option[value=\'OSM\']").prop("selected", true);
               //disabled the choice
               $("#cartyp option[value=\'natural-earth-hypso-bathy\']").prop("disabled", true);
               $("#cartyp option[value=\'geography-class\']").prop("disabled", true);
            } else { 
               if(e.oldValue > 3000) {
                  $("#cartyp option[value=\'natural-earth-hypso-bathy\']").prop("disabled", null);
                  $("#cartyp option[value=\'geography-class\']").prop("disabled", null);
               }
            }
           break;
      }
   });
// <== fallback de résolution

// ==> opacité sur couche de base
   $("#baselayeropacity").on("input change", function() {
      map.getLayers().item(0).setOpacity(parseFloat(this.value));
   });
// <== opacité sur couche de base

//==> popup des markers
   var container = document.getElementById("ol_popup");
   container.addEventListener("pointermove", (e) => e.stopPropagation());
   function OpenPopup(evt) {
      $("#ol_popup").show();
      popup.setPosition(undefined);
      $(".sb_js").removeClass("animated faa-horizontal faa-slow" );
      var feature = map.forEachFeatureAtPixel(evt.pixel, function (feature, layer) {
         if (feature) {
            $("#"+feature.getId()).addClass("animated faa-horizontal faa-slow" );
            var coord = map.getCoordinateFromPixel(evt.pixel);
            let layerid = layer.get("id");
            if (typeof feature.get("features") === "undefined") {
               if(layerid =="utilisateurs")
                   container.innerHTML = \'<img class="me-2 img-thumbnail n-ava" src="\' + feature.get("ava") + \'" align="middle" /><span class="lead">\' + feature.get("pseudo") + \'</span><div class="my-2">'.geoloc_translate("Dernière visite").' : \' + feature.get("lastvisit") + \'</div><hr /><div class="text-center lead">\' + feature.get("userlinks") + \'</div>\';
               if(layerid =="utilisateursOn")
                   container.innerHTML = \'<div class="text-center">\' + feature.get("userlinks") + \'</div><hr /><img class="me-2 img-thumbnail n-ava" src="\' + feature.get("ava") + \'" align="middle" /><span class="lead">\' + feature.get("pseudo") + \'</span><hr /><div class="text-center">\' + feature.get("social") + \'</div>\';
               if(layerid =="anony") {
                  if(feature.get("ip_qui") == "A")
                     container.innerHTML = \''.$ic_b_acg.' \' + feature.getId() + \' @ \' + feature.get("ip_hostaddr") + \' <br /><hr /><span class="text-body-secondary">'.geoloc_translate("Hôte").' : </span>\'+ feature.get("ip_hote") +\'<br /><span class="text-body-secondary">'.geoloc_translate("En visite ici").' : </span> \' + feature.get("ip_visitpage") +\'<br /><span class="text-body-secondary">'.geoloc_translate("Visites").' : </span>[\' + feature.get("ip_visit") +\']<br /><span class="text-body-secondary">'.geoloc_translate("Ville").' : </span>\'+ feature.get("ip_city") +\'<br /><span class="text-body-secondary">'.geoloc_translate("Pays").' : </span>\'+ feature.get("ip_country") +\'<hr /><img src="\'+ feature.get("ip_flagsrc") +\'.png" class="n-smil" alt="flag" />\' || "(unknown)";
                  else
                     container.innerHTML = \''.$ic_b_mbgc.' \' + feature.get("ip") + \' @ \' + feature.get("ip_hostaddr") + \' <br /><hr /><span class="text-body-secondary">'.geoloc_translate("Hôte").' : </span>\'+ feature.get("ip_hote") +\'<br /><span class="text-body-secondary">'.geoloc_translate("En visite ici").' : </span> \' + feature.get("ip_visitpage") +\'<br /><span class="text-body-secondary">'.geoloc_translate("Visites").' : </span>[\' + feature.get("ip_visit") +\']<br /><span class="text-body-secondary">'.geoloc_translate("Ville").' : </span>\'+ feature.get("ip_city") +\'<br /><span class="text-body-secondary">'.geoloc_translate("Pays").' : </span>\'+ feature.get("ip_country") +\'<hr /><img src="\'+ feature.get("ip_flagsrc") +\'.png" class="n-smil" alt="flag" />\' || "(unknown)";
               }
            } else {
               var cfeatures = feature.get("features");
               if (cfeatures.length > 1) {
                  var l_ip="";
                  l_ip += \'<div class="ol_li"><h5><i class="me-1 bi bi-display align-middle h4"></i>IP<span class="badge bg-secondary float-end">\'+cfeatures.length+\'</span></h5><hr />\';
                  container.innerHTML = \'\';
                  for (var i = 0; i < cfeatures.length; i++) {
                     let cl = cfeatures[i].get("spam") >= 1 ? " text-danger" : "";
                     let infospam ="";
                     switch(cfeatures[i].get("spam")) {
                        case "" : infospam = ""; break;
                        case "5" : infospam = \'<br /><span class="text-danger">'.geoloc_translate("Adresse IP bannie !").' [\'+ cfeatures[i].get("spam") +\']</span>\'; break;
                        default : infospam = \'<br /><span class="text-danger">'.geoloc_translate("Adresse IP signalée par l’antispam !").' [\'+ cfeatures[i].get("spam") +\']</span>\'; break;
                     }
                     l_ip += \'<div class=""><a class="\'+cl+\'" href="#div\' + cfeatures[i].getId() + \'" data-bs-toggle="collapse">\' + cfeatures[i].get("ip") + \'</a></div><div class="collapse small border-bottom pb-1 mb-1" id="div\' + cfeatures[i].getId() + \'"><span class="text-body-secondary">'.geoloc_translate("Visites").' : </span>[\' + cfeatures[i].get("visit") + \']<br /><img class="n-smil me-2" alt="flag" src="\' + cfeatures[i].get("flag") + \'.png" loading="lazy"/>\' + cfeatures[i].get("pays") + \' \' + cfeatures[i].get("city") + \'<br /><span class="text-body-secondary">'.geoloc_translate("Posts/Commentaires").' : </span>[\' + cfeatures[i].get("post") + \']\'+infospam+\'</div>\';
                  }
                  l_ip += \'</div>\';
                  $(container).append(l_ip);
               }
               if (cfeatures.length == 1) {
                  let cl = cfeatures[0].get("spam") >= 1 ? " text-danger" : "";
                  let infospam ="";
                  switch(cfeatures[0].get("spam")) {
                     case "" : infospam = ""; break;
                     case "5" : infospam = \'<br /><hr /><span class="text-danger">'.geoloc_translate("Adresse IP bannie !").' [\'+ cfeatures[0].get("spam") +\']</span>\'; break;
                     default : infospam = \'<br /><hr /><span class="text-danger">'.geoloc_translate("Adresse IP signalée par l’antispam !").' [\'+ cfeatures[0].get("spam") +\']</span>\'; break;
                  }
                  container.innerHTML = \'<div class="small"><span class="\' + cl + \'"><i class="me-1 bi bi-display h4 align-middle"></i>@ \' + cfeatures[0].get("ip") + \'</span><hr /><span class="text-body-secondary">'.geoloc_translate("Visites").' : </span>[\' + cfeatures[0].get("visit") + \']<br /><img class="n-smil me-2" alt="flag" src="\' + cfeatures[0].get("flag") + \'.png" loading="lazy"/>\' + cfeatures[0].get("pays")+ \' \' + cfeatures[0].get("city")+ \'<br /><span class="text-body-secondary">'.geoloc_translate("Posts/Commentaires").' : </span>[\' + cfeatures[0].get("post") + \']\'+infospam+\'</div>\';
               }
            }
            popup.setPosition(coord);
            $("#map .tooltipbyclass").tooltip({placement: "top", container:"#map"});
         } else
            popup.setPosition(undefined);
      }, {layerFilter : function(layer){return layer.get("id") != "countries" && layer.get("id") != "georeferencement";}}
      )
   };
   map.on("click", OpenPopup);
//<== popup des markers

//==> changement etat pointeur sur les markers
   map.on("pointermove", function(e) {
     if (e.dragging) {
       $(".popover").popover("dispose");
       return;
     }
     var pixel = map.getEventPixel(e.originalEvent);
     var hit = map.hasFeatureAtPixel(pixel);
     map.getTarget().style.cursor = hit ? "pointer" : "crosshair";
   });
//<== changement etat pointeur sur les markers';

//==> ecriture des markers pour les membres connectés et les anonymes
$ecr_scr .= $mb_con_g;
//<==
$ecr_scr .= '
   document.getElementById("mess_info").innerHTML = \''.$mess_adm.'\';';

if($op)
   if ($op[0]=='u') //pour zoom sur user back with u1
      $ecr_scr .= '
   map.getView().setCenter(src_user.getFeatureById("'.$op.'").getGeometry().getFlatCoordinates());
   map.getView().setZoom(15);';
if($op=='allip' and $geo_ip==1 and autorisation(-127))
   $ecr_scr .= '
   ip_cluster.setVisible(true);
   grouputilisateurs.getLayers().forEach(l=>{l.setVisible(false)});
   $("#conbox, #memberbox").prop("checked", false);
   $("#ipbox").prop("checked", "checked");
   $("#l_sb_ip").removeClass("collapse").addClass("collapse show");
   $("#carrets_ip i").removeClass("fa-caret-down visually-hidden").addClass("fa-caret-up");
   $("#carrets_ac i,#carrets_mb i").addClass("fa-caret-down visually-hidden");';

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
   const lang = map.getTarget().lang;
   for (var i in dic) {
      if (dic.hasOwnProperty(i)) {
         $("#map "+dic[i].cla).prop("title", dic[i][lang]);
      }
   }
   fullscreen.on("enterfullscreen",function(){
      $(dic.olfullscreentrue.cla).attr("data-bs-original-title", dic["olfullscreentrue"][lang]);
   })
   fullscreen.on("leavefullscreen",function(){
      $(dic.olfullscreenfalse.cla).attr("data-bs-original-title", dic["olfullscreenfalse"][lang]);
   })
   $("#map .ol-zoom-in, #map .ol-zoom-out").tooltip({placement: "right", container:"#map",});
   $(".ol-sidebar button[title], .ol-full-screen-false, .ol-full-screen-true, .ol-rotate-reset, .ol-attribution button[title]").tooltip({placement: "left", container:"#map",});

   $(\'a[data-bs-toggle="collapse"]\').click(function () {
      $(this).find("i.toggle-icon").toggleClass(\'fa-caret-down fa-caret-up\',6000);
   })

//==> filtrage des markers dans sidebar
   const mbLigne = [].slice.call(document.getElementsByClassName("filtrmb_js"));
   const mbInput = document.getElementById("n_filtrmb");
   const mbAfiltrer = [].slice.call(document.getElementsByClassName("nlfiltrmb"));
   mbInput.addEventListener("input", filtrMb);
   function filtrMb(e) {
      document.getElementById("mbnb").innerText="0";
      var searchTerm = e.target.value.toLowerCase();
      if(searchTerm.length >= 2) {
         let i = 0;
         mbAfiltrer.forEach((item) => {
            if(item.getAttribute("data-searchterm").indexOf(searchTerm) >-1 ) {
               i++;
               item.parentNode.style.display="block";
               src_user.getFeatureById(item.parentNode.id).setStyle(null);
               document.getElementById("mbnb").innerText=i;
            } 
            else {
               item.parentNode.style.display="none";
               src_user.getFeatureById(item.parentNode.id).setStyle(new ol.style.Style({}));
            }
         });
      }
      else {
         mbLigne.forEach((item) => {
            item.style.display="block";
         });
         src_user.forEachFeature(function(e) {e.setStyle(styleUser)});
         document.getElementById("mbnb").innerText='.$mbgr.';
      }
   }';
if(autorisation(-127) and $geo_ip==1)
   $ecr_scr .='
   const ipLigne = [].slice.call(document.getElementsByClassName("filtrip_js"));
   const ipInput = document.getElementById("n_filtreip");
   const ipAfiltrer = [].slice.call(document.getElementsByClassName("nlfiltrip"));
   ipInput.addEventListener("input", filtrIp);
   function filtrIp(e) {
      document.getElementById("ipnb").innerText="0";
      var searchTerm = e.target.value.toLowerCase();
      if(searchTerm.length >= 3) {
         let i = 0;
         ipAfiltrer.forEach((item) => {
            if(item.getAttribute("data-searchterm").indexOf(searchTerm) >-1) {
               i++;
               item.parentNode.style.display="block";
               document.getElementById("ipnb").innerText=i;
            } else {
               item.parentNode.style.display="none";
               //src_ip.removeFeature(src_ip.getFeatureById(item.parentNode.id));//trop lent
            }
         });
      }
      else {
         ipLigne.forEach((item) => {
            item.style.display="block";
         });
         document.getElementById("ipnb").innerText='.$ipnb.';
      }
   }';
$ecr_scr .='
//<== filtrage des markers dans sidebar

//==> tooltip des markers
   var containertip = $("#ol_tooltip");
   containertip.tooltip({
      animation: false,
      container: "#map",
      trigger: "manual",
      placement:"bottom",
      offset:"10,10",
      html : true,
   });
   function voirInfo(evt) {
      containertip.tooltip("hide");
      popuptooltip.setPosition(undefined);
      var feature = map.forEachFeatureAtPixel(evt.pixel, function (feature, layer) {
         if (feature) {
            var coord = map.getCoordinateFromPixel(evt.pixel);
            if (typeof feature.get("features") === "undefined") {
               if(layer.get("id") == "utilisateurs")
                   containertip.attr("data-bs-original-title", feature.get("pseudo")).tooltip("show");
               if(layer.get("id") == "georeferencement")
                   containertip.attr("data-bs-original-title", "'.geoloc_translate('Cliquer sur la carte pour modifier votre position.').'").tooltip("show");
               if(layer.get("id") == "anony")
                   containertip.attr("data-bs-original-title", "IP : "+feature.get("ip")).tooltip("show");
            }
            popuptooltip.setPosition(coord);
         } else {
            popuptooltip.setPosition(undefined);
            containertip.tooltip("hide");
         }
         containertip.tooltip();
      });
   }
   map.on("pointermove", voirInfo);
//<== tooltip des markers

   map.on("rendercomplete",function(e) {
     var zoomLevel = map.getView().getZoom();
     var zoomRounded = Math.round(zoomLevel*10)/10;
     document.getElementById("ZoomElement").innerHTML = zoomRounded;
   })
   
   map.on("loadstart", function () {
      map.getTargetElement().classList.add("spinner");
   });
   map.on("loadend", function () {
     map.getTargetElement().classList.remove("spinner");
   });
});

$(\'[data-bs-toggle="tab_ajax"]\').click(function(e) {
    var $this = $(this),
        loadurl = $this.attr(\'href\'),
        targ = $this.attr(\'data-bs-target\');
    $.get(loadurl, function(data) {
        $(targ).html(data);
    });
    $this.tab(\'show\');
    return false;
});

$(function(){
   $(".n-filtrable p").each(function(){
      $(this).attr("data-search-term", $(this).text().toLowerCase());
   });
   $(".n_filtrbox").on("keyup", function(){
      var searchTerm = $(this).val().toLowerCase();
      $(".n-filtrable p").each(function(){
         if ($(this).filter(\'[data-search-term *= "\' + searchTerm + \'"]\').length > 0 || searchTerm.length < 1)
            $(this).show();
         else
            $(this).hide();
       });
   });

});

$(document).ready(function() {
   $(\'a[data-bs-toggle="collapse"]\').click(function () {
      $(this).find("i.toggle-icon").toggleClass(\'fa-caret-down fa-caret-up\',6000);
   });
});

//]]>
</script>';
//<== construction js

//==> affichage
include ('header.php');
$affi = '
   <span class="n-media-repere"></span>
   <h3 class="mt-4 mb-3">'.geoloc_translate("Géolocalisation des membres du site").'<span class="float-end"><span class="badge bg-secondary me-2" title ="'.geoloc_translate('Membres du site').'" data-bs-toggle="tooltip" data-bs-placement="left">'.$total_membre.'</span></span></h3>
   <div class=" mb-4">
      <div id="map-wrapper" class="ol-fullscreen my-3">
         <div id="map" lang="'.language_iso(1,0,0).'" class="map" tabindex="20">
            <div id="ol_tooltip"></div>
            <div id="ol_popup" class="ol-popup"></div>
            <div style="display: none;">
               <div id="georefpopup"></div>
            </div>
         </div>
         <div id="sidebar" class= "collapse show col-sm-4 col-md-3 col-6 px-0">
            <div id="sb_tools" class="list-group mb-2">
               <div class="bg-light text-dark fw-light p-2"><a class="link" data-bs-toggle="collapse" href="#l_sb_tools"><i class="toggle-icon fa fa-caret-down fa-lg me-2" style="font-size:1.6rem;"></i></a>'.geoloc_translate('Fonctions').'<span class="float-end">'.$lkadm.'</span></div>
               <div class="collapse" id="l_sb_tools">
                  '.$sb_georef.'
                  <div class="list-group-item list-group-item-action py-1 border-left-0 border-right-0">
                     <div class="mb-3 row">
                        <label class="col-form-label col-sm-12" for="cartyp"><span class="align-middle bi bi-layers-half me-2 h3 text-primary"></span>'.geoloc_translate('Type de carte').'</label>
                        <div class="col-sm-12">
                           <select class="form-select" name="cartyp" id="cartyp">
                           '.$optcart.'
                           </select>
                           <input type="range" value="1" class="form-range mt-1" min="0" max="1" step="0.1" id="baselayeropacity" />
                           <label class="my-0 float-end small form-label" for="baselayeropacity">'.geoloc_translate('Opacité').'</label>
                           <div id="dayslider" class="collapse">
                              <input type="range" value="1" class="form-range mt-1" min="-6" max="0" value="0" id="nasaday" />
                              <label id="dateimages" class="form-label mt-0 float-end small" for="nasaday">'.$date_jour.'</label>
                           </div>
                        </div>
                     </div>
                     <hr class="mb-1"/>
                     <div><span class="align-middle bi bi-layers me-2 h3 text-primary"></span>'.geoloc_translate('Couches utilitaires').'</div>
                     <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" data-bs-toggle="tooltip" title="'.geoloc_translate('Voir ou masquer la grille').'" id="grillebox" />
                        <label class="form-check-label" for="grillebox">'.geoloc_translate('Grille').'</label>
                     </div>
                     <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" data-bs-toggle="tooltip" title="'.geoloc_translate('Voir ou masquer la couche').'" id="coastandborder" />
                        <label class="form-check-label" for="coastandborder">'.geoloc_translate('Côtes et frontières').'</label>
                     </div>
                     <div id="ZoomElement"></div>
                  </div>
               </div>
            </div>
            '.$sidebaronline
            .$sidebarmembres;
if(autorisation(-127) and $geo_ip==1)
   $affi .= $sidebarip;
$affi .= '
         </div>
      </div>
      <ul class="nav nav-tabs mt-4">
         <li class="nav-item"><a id="messinfo-tab" class="nav-link active" href="#infocart" data-bs-toggle="tab_ajax"><span class="d-sm-none"><i class=" fa fa-globe fa-lg me-2"></i><i class=" fa fa-info fa-lg"></i></span><span class="d-none d-sm-inline">'.geoloc_translate("Infos carte").'</span></a></li>
         <li class="nav-item"><a id="aide-tab" class="nav-link" href="modules/geoloc/doc/aide_geo-'.$language.'.html" data-bs-target="#aide" data-bs-toggle="tab_ajax"><span class="d-sm-none"><i class=" fa fa-globe fa-lg me-2"></i><i class=" fa fa-question fa-lg"></i></span><span class="d-none d-sm-inline">'.geoloc_translate("Aide").'</span></a></li>';
if(autorisation(-127) and $geo_ip==1)
   $affi .= '
         <li class="nav-item"><a id="iplist-tab" class="nav-link " href="#ipgeolocalisation" data-bs-toggle="tab_ajax"><span class="d-sm-none"><i class=" fa fa-globe fa-lg me-2"></i><i class=" fa fa-tv fa-lg"></i></span><span class="d-none d-sm-inline">'.geoloc_translate("Ip liste").'</span></a></li>';
$affi .= '
      </ul>
   <div class="tab-content">
      <div class="tab-pane fade show active" id="infocart">
         <div id="mess_info" class=" col-12 mt-3"></div>
      </div>
      <div class="tab-pane fade" id="aide"></div>
      <div class="tab-pane fade mt-2" id="ipgeolocalisation">
         <h5 class="mt-3">
            <i title="'.geoloc_translate('IP géoréférencées').'" data-bs-toggle="tooltip" style="color:'.$acg_t_co.'; opacity:'.$acg_t_op.';" class="fa fa-desktop fa-lg me-2 align-middle"></i>
            <span class="badge bg-secondary me-2 float-end">'.$ipnb.'</span>
         </h5>
         <div class="mb-3 row">
            <div class="col-sm-12">
               <input id="filtreur" type="text" class="mb-3 form-control form-control-sm n_filtrbox" placeholder="'.geoloc_translate('Filtrer les résultats').'" />
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
echo $affi.$ecr_scr;

include ('footer.php');

switch ($op) {
   case 'wp':
      wp_fill();
   break;
//   default: geoloc();
}
?>
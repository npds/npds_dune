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
/* geoloc_set.php file 2007-2017 by Jean Pierre Barbary (jpb)           */
/* dev team : Philippe Revilliod (Phr)                                  */
/************************************************************************/

if (!strstr($_SERVER['PHP_SELF'],'admin.php')) Access_Error();
if (strstr($ModPath,'..') || strstr($ModStart,'..') || stristr($ModPath, 'script') || stristr($ModPath, 'cookie') || stristr($ModPath, 'iframe') || stristr($ModPath, 'applet') || stristr($ModPath, 'object') || stristr($ModPath, 'meta') || stristr($ModStart, 'script') || stristr($ModStart, 'cookie') || stristr($ModStart, 'iframe') || stristr($ModStart, 'applet') || stristr($ModStart, 'object') || stristr($ModStart, 'meta'))
   die();

$f_meta_nom ='geoloc';
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
include ('modules/'.$ModPath.'/lang/geoloc.lang-'.$language.'.php');
$f_titre= geoloc_translate("Configuration du module Geoloc");

   settype($subop,'string');
   settype($geo_ip,'integer');
   settype($cartyp,'string');
   settype($ch_lat,'string');
   settype($ch_lon,'string');


function vidip(){
   global $NPDS_Prefix;
   $sql = "DELETE FROM ".$NPDS_Prefix."ip_loc WHERE ip_id >=1";
   if ($result = sql_query($sql)) 
      sql_query( "ALTER TABLE ".$NPDS_Prefix."ip_loc AUTO_INCREMENT = 0;");
}

function Configuregeoloc($subop, $ModPath, $ModStart, $ch_lat, $ch_lon, $cartyp, $geo_ip) {
   global $hlpfile, $language, $f_meta_nom, $f_titre, $adminimg, $dbname, $NPDS_Prefix, $subop;
   if (file_exists('modules/'.$ModPath.'/geoloc_conf.php'))
   include ('modules/'.$ModPath.'/geoloc_conf.php');
   $hlpfile = 'modules/'.$ModPath.'/doc/aide_admgeo.html';

   $result=sql_query("SELECT CONCAT(ROUND(((DATA_LENGTH + INDEX_LENGTH - DATA_FREE) / 1024 / 1024), 2), ' Mo') AS TailleMo FROM information_schema.TABLES WHERE TABLE_SCHEMA = '$dbname' AND TABLE_NAME = ".$NPDS_Prefix."'ip_loc'");
   $row = sql_fetch_array($result);

   $ar_fields=array('C3','C4','C5','C6','C7','C8');
   reset($ar_fields);
   while (list($k, $v) = each($ar_fields)) {
      $req='';
      $req=sql_query("SELECT $v FROM users_extend WHERE $v !=''");
      if(!sql_num_rows($req)) $dispofield[]=$v;
   }

   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);

   $fonts_svg=array('USER','USERS','HEART-O','THUMB-TACK','CIRCLE-O','CAMERA','ANCHOR','MAP-MARKER','PLANE','STAR-O','HOME','FLAG','CROSSHAIRS','ASTERISK','FIRE','COMMENT');
   echo '
   <hr />
   <a href="modules.php?ModPath=geoloc&amp;ModStart=geoloc"><i class="fa fa-globe fa-lg mr-2 "></i>'.geoloc_translate('Carte').'</a>

   <form id="geolocset" name="geoloc_set" action="admin.php" method="post">
      <h4 class="my-3">'.geoloc_translate('Paramètres système').'</h4>
      <fieldset id="para_sys" class="" style="padding-top: 16px; padding-right: 3px; padding-bottom: 6px;padding-left: 3px;">
         <span class="text-danger">* '.geoloc_translate("requis").'</span>
         <div class="form-group row">
            <label class="form-control-label col-sm-6" for="api_key">'.geoloc_translate("Clef d'API").'<span class="text-danger ml-1">*</span> : '.$api_key.'</label>
            <div class="col-sm-6">
               <input type="text" class="form-control" name="api_key" id="api_key" placeholder="" value="'.$api_key.'" required="required" />
            </div>
         </div>
         <div class="form-group row ">
            <label class="form-control-label col-sm-6" for="ch_lat">'.geoloc_translate('Champ de table pour latitude').'<span class="text-danger ml-1">*</span></label>
            <div class="col-sm-6">
               <select class="custom-select form-control" name="ch_lat" id="ch_lat">
                  <option selected="selected">'.$ch_lat.'</option>';
   reset($dispofield);
   while (list($ke, $va) = each($dispofield)) {
      echo '
                  <option>'.$va.'</option>';
   }
   echo '
               </select>
            </div>
         </div>
         <div class="form-group row ">
            <label class="form-control-label col-sm-6" for="ch_lon">'.geoloc_translate('Champ de table pour longitude').'<span class="text-danger ml-1">*</span></label>
            <div class="col-sm-6">
               <select class="custom-select form-control" name="ch_lon" id="ch_lon">
                  <option selected="selected">'.$ch_lon.'</option>';
   reset($dispofield);
   while (list($ke, $va) = each($dispofield)) {
      echo '
                  <option>'.$va.'</option>';
   }
   echo '
               </select>
            </div>
         </div>
         <div class="form-group row">
            <label class="form-control-label col-sm-6" for="ch_img">'.geoloc_translate('Chemin des images').'<span class="text-danger ml-1">*</span></label>
            <div class="col-sm-6">
               <input type="text" class="form-control" name="ch_img" id="ch_img" placeholder="Chemin des images" value="'.$ch_img.'" required="required" />
            </div>
         </div>
         <div class="form-group row">
            <label class="col-sm-6 form-control-label" for="geo_ip">'.geoloc_translate('Géolocalisation des IP').'</label>
            <div class="col-sm-6">
               <label class="custom-control custom-radio">';
   if ($geo_ip==1) { 
      echo'
                  <input class="custom-control-input" type="radio" id="geo_oui" name="geo_ip" value="1" checked="checked" />
                  <span class="custom-control-indicator"></span>
                  <span class="custom-control-description">'.geoloc_translate('Oui').'</span>
               </label>
               <label class="custom-control custom-radio">
                  <input class="custom-control-input" type="radio" id="geo_no" name="geo_ip" value="0" />
                  <span class="custom-control-indicator"></span>
                  <span class="custom-control-description">'.geoloc_translate('Non').'</span>
               </label>';
   } else {
      echo'
                  <input class="custom-control-input" type="radio" id="geo_oui" name="geo_ip" value="1" />
                  <span class="custom-control-indicator"></span>
                  <span class="custom-control-description">'.geoloc_translate('Oui').'</span>
               </label>
               <label class="custom-control custom-radio">
                  <input class="custom-control-input" type="radio" id="geo_no" name="geo_ip" value="0" checked="checked"/>
                  <span class="custom-control-indicator"></span>
                  <span class="custom-control-description">'.geoloc_translate('Non').'</span>
               </label>';
   }
   echo '
            </div>
         </div>
         <div class="form-group row">
            <div class="col-sm-12"><span class="form-control-label">'.geoloc_translate('Taille de la table').' ip_loc '.$row['TailleMo'].'</span> <span class="float-right"><a href="admin.php?op=Extend-Admin-SubModule&ModPath='.$ModPath.'&ModStart='.$ModStart.'&subop=vidip" title="'.geoloc_translate('Vider la table des IP géoréférencées').'" data-toggle="tooltip" data-placement="left"><i class="fa fa-trash-o fa-lg text-danger"></i></a></span></div>
         </div>
      </fieldset>
      <hr />
      <h4 class="my-3" >'.geoloc_translate('Interface carte').'</h4>
      <div class="row">
         <div class="col-sm-8">
            <fieldset id="para_car" class="" style="padding-top: 16px; padding-right: 3px; padding-bottom: 6px;padding-left: 3px;">
               <div class="form-group row ">
                  <label class="form-control-label col-sm-6" for="cartyp">'.geoloc_translate('Type de carte').'<span class="text-danger ml-1">*</span></label>
                  <div class="col-sm-6">
                     <select class="custom-select form-control" name="cartyp" id="cartyp">
                        <option>ROADMAP</option>
                        <option>SATELLITE</option>
                        <option>HYBRID</option>
                        <option>TERRAIN</option>
                        <option selected="selected">'.$cartyp.'</option>
                     </select>
                  </div>
               </div>
               <div class="form-group row">
                  <label class="form-control-label col-sm-6" for="co_unit">'.geoloc_translate('Unité des coordonnées').'<span class="text-danger ml-1">*</span></label>
                  <div class="col-sm-6">
                     <select class="custom-select form-control" name="co_unit" id="co_unit">
                        <option>dd</option>
                        <option>dms</option>
                        <option selected>'.$co_unit.'</option>
                     </select>
                  </div>
               </div>
               <div class="form-group row">
                  <div class="col-sm-12 form-control-label">
                  <label for="mark_typ">'.geoloc_translate('Type de marqueur').'</label>
                  </div>
                  <div class="col-sm-12">
                  <label class="custom-control custom-radio">';
   if ($mark_typ==1) { 
      echo'
                     <input class="custom-control-input" type="radio" id="img_img" name="mark_typ" value="1" checked="checked" />
                     <span class="custom-control-indicator"></span>
                     <span class="custom-control-description">'.geoloc_translate('Marqueur images de type png, gif, jpeg.').'</span>
                  </label><br />
                  <label class="custom-control custom-radio">
                     <input class="custom-control-input" type="radio" id="img_svg" name="mark_typ" value="0" />
                     <span class="custom-control-indicator"></span>
                     <span class="custom-control-description">'.geoloc_translate('Marqueur SVG font ou objet vectoriel.').'</span>
                  </label>';
   } else {
      echo'
                     <input class="custom-control-input" type="radio" id="img_img" name="mark_typ" value="1" />
                     <span class="custom-control-indicator"></span>
                     <span class="custom-control-description">'.geoloc_translate('Marqueur images de type png, gif, jpeg.').'</span>
                  </label><br />
                  <label class="custom-control custom-radio">
                     <input class="custom-control-input" type="radio" id="img_svg" name="mark_typ" value="0" checked="checked"/>
                     <span class="custom-control-indicator"></span>
                     <span class="custom-control-description">'.geoloc_translate('Marqueur SVG font ou objet vectoriel.').'</span>
                  </label>';
   }
   echo'
               </div>
            </div>
         </fieldset>
         <fieldset id="para_ima" class="" style="padding-top: 16px; padding-right: 3px; padding-bottom: 6px;padding-left: 3px;">
            <div class="form-group row">
               <label class="form-control-label col-sm-6" for="nm_img_mbg">'.geoloc_translate('Image membre géoréférencé').'<span class="text-danger ml-1">*</span></label>
               <div class="col-sm-6">
                  <div class="input-group ">
                     <div id="v_img_mbg" class="input-group-addon "><img src="'.$ch_img.$nm_img_mbg.'" /></div>
                     <input type="text" class="form-control input-lg" name="nm_img_mbg" id="nm_img_mbg" placeholder="'.geoloc_translate('Nom du fichier image').'" value="'.$nm_img_mbg.'" required="required" />
                  </div>
               </div>
            </div>
            <div class="form-group row">
               <label class="form-control-label col-sm-6" for="nm_img_mbcg">'.geoloc_translate('Image membre géoréférencé en ligne').'<span class="text-danger ml-1">*</span></label>
               <div class="col-sm-6">
                  <div class="input-group ">
                     <div id="v_img_mbcg" class="input-group-addon "><img src="'.$ch_img.$nm_img_mbcg.'" /></div>
                     <input type="text" class="form-control input-lg" name="nm_img_mbcg" id="nm_img_mbcg" placeholder="'.geoloc_translate('Nom du fichier image').'" value="'.$nm_img_mbcg.'" required="required" />
                  </div>
               </div>
            </div>
            <div class="form-group row">
                <label class="form-control-label col-sm-6" for="nm_img_acg">'.geoloc_translate('Image anonyme géoréférencé en ligne').'<span class="text-danger ml-1">*</span></label>
                <div class="col-sm-6">
                    <div class="input-group">
                        <div id="v_img_acg" class="input-group-addon"><img src="'.$ch_img.$nm_img_acg.'" /></div>
                        <input type="text" class="form-control input-lg" name="nm_img_acg" id="nm_img_acg" placeholder="'.geoloc_translate('Nom du fichier image').'" value="'.$nm_img_acg.'" required="required" />
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="form-control-label col-sm-6" for="w_ico">'.geoloc_translate('Largeur icône des marqueurs').'<span class="text-danger ml-1">*</span></label>
                <div class="col-sm-6">
                    <input type="number" class="form-control" name="w_ico" id="w_ico" maxlength="3" placeholder="Largeur des images" value="'.$w_ico.'" required="required" />
                </div>
            </div>
            <div class="form-group row">
               <label class="form-control-label col-sm-6" for="h_ico">'.geoloc_translate('Hauteur icône des marqueurs').'<span class="text-danger ml-1">*</span></label>
               <div class="col-sm-6">
                  <input type="number" class="form-control" name="h_ico" id="h_ico" maxlength="3" placeholder="Hauteur des images" value="'.$h_ico.'" required="required" />
               </div>
            </div>
         </fieldset>
         <fieldset id="para_svg" class="" style="padding-top: 16px; padding-right: 3px; padding-bottom: 6px;padding-left: 3px;">
            <div class="form-group row">
               <label class="form-control-label col-sm-6" for="f_mbg">'.geoloc_translate('Marqueur font SVG').'<span class="text-danger ml-1">*</span></label>
               <div class="col-sm-6">
                  <div class="input-group">
                     <div id="vis_ic" class="input-group-addon"></div>
                     <select class="custom-select form-control input-lg" name="f_mbg" id="f_mbg">
                        <option selected="selected">'.$f_mbg.'</option>';
   foreach ($fonts_svg as $v) {
      echo '
                         <option>'.$v.'</option>';
   }
   echo '
                     </select>
                  </div>
               </div>
            </div>
            <div class="form-group row">
               <div class="col-4">
                  <div><i id="f_choice_mbg" class="fa fa-'.strtolower($f_mbg).' fa-2x" style="color:'.$mbg_f_co.' ; opacity:'.$mbg_f_op.'" ></i>&nbsp;<span>'.geoloc_translate('Membre').'</span></div>
               </div>
               <div class="col-4">
                  <div><i id="f_choice_mbgc" class="fa fa-'.strtolower($f_mbg).' fa-2x" style="color:'.$mbgc_f_co.' ; opacity:'.$mbgc_f_op.'" ></i>&nbsp;<span>'.geoloc_translate('Membre en ligne').'</span></div>
               </div>
               <div class="col-4">
                  <div><i id="f_choice_acg" class="fa fa-'.strtolower($f_mbg).' fa-2x" style="color:'.$acg_f_co.'; opacity:'.$acg_f_op.'" ></i>&nbsp;<span>'.geoloc_translate('Anonyme en ligne').'</span></div>
               </div>
            </div>
            <div class="row">
                <div class="col-4 bkmbg">
                    <label class="form-control-label" for="mbg_f_co">'.geoloc_translate('Couleur fond').'</label>
                    <div class="input-group pickcol_fmb pickol">
                        <div class="input-group-addon "><i></i></div>
                        <input type="text" class="form-control" name="mbg_f_co" id="mbg_f_co" placeholder="'.geoloc_translate('Couleur du fond').'" value="'.$mbg_f_co.'" />
                    </div>
                </div>
                <div class="col-4">
                    <label class="form-control-label" for="mbgc_f_co">'.geoloc_translate('Couleur fond').'</label>
                    <div class="input-group pickcol_fmbc pickol">
                        <div class="input-group-addon"><i></i></div>
                        <input type="text" class="form-control" name="mbgc_f_co" id="mbgc_f_co" placeholder="'.geoloc_translate('Couleur du fond').'" value="'.$mbgc_f_co.'" />
                    </div>
                </div>
                <div class="col-4">
                    <label class="form-control-label" for="acg_f_co">'.geoloc_translate('Couleur fond').'</label>
                    <div class="input-group pickcol_fac pickol">
                        <div class="input-group-addon "><i></i></div>
                        <input type="text" class="form-control" name="acg_f_co" id="acg_f_co" placeholder="'.geoloc_translate('Couleur du fond').'" value="'.$acg_f_co.'" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4 bkmbg">
                    <label class="form-control-label" for="mbg_t_co">'.geoloc_translate('Couleur du trait').'</label>
                    <div class="input-group pickcol_tmb pickol">
                        <div class="input-group-addon "><i></i></div>
                        <input type="text" class="form-control" name="mbg_t_co" id="mbg_t_co" placeholder="'.geoloc_translate('Couleur du trait').'" value="'.$mbg_t_co.'" />
                    </div>
                </div>
                <div class="col-4">
                    <label class="form-control-label" for="mbgc_t_co">'.geoloc_translate('Couleur du trait').'</label>
                    <div class="input-group pickcol_tmbc pickol">
                        <div class="input-group-addon "><i></i></div>
                        <input type="text" class="form-control" name="mbgc_t_co" id="mbgc_t_co" placeholder="'.geoloc_translate('Couleur du trait').'" value="'.$mbgc_t_co.'" />
                    </div>
                </div>
                <div class="col-4" >
                    <label class="form-control-label" for="acg_t_co">'.geoloc_translate('Couleur du trait').'</label>
                    <div class="input-group pickcol_tac pickol">
                        <div class="input-group-addon "><i></i></div>
                        <input type="text" class="form-control" name="acg_t_co" id="acg_t_co" placeholder="'.geoloc_translate('Couleur du trait').'" value="'.$acg_t_co.'" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4 bkmbg">
                    <label class="form-control-label" for="mbg_f_op">'.geoloc_translate('Opacité du fond').'</label>
                    <input type="number" step="any" min="0" max="1" class="form-control" name="mbg_f_op" id="mbg_f_op" value="'.$mbg_f_op.'" required="required" />
                </div>
                <div class="col-4">
                    <label class="form-control-label" for="mbgc_f_op">'.geoloc_translate('Opacité du fond').'</label>
                    <input type="number" step="any" min="0" max="1" class="form-control" name="mbgc_f_op" id="mbgc_f_op" value="'.$mbgc_f_op.'" required="required" />
                </div>
                <div class="col-4" >
                    <label class="form-control-label" for="acg_f_op">'.geoloc_translate('Opacité du fond').'</label>
                    <input type="number" step="any" min="0" max="1" class="form-control" name="acg_f_op" id="acg_f_op" value="'.$acg_f_op.'" required="required" />
                </div>
            </div>
            <div class="row">
                <div class="col-4 bkmbg">
                    <label class="form-control-label" for="mbg_t_op">'.geoloc_translate('Opacité du trait').'</label>
                    <input type="number" step="any" min="0" max="1" class="form-control" name="mbg_t_op" id="mbg_t_op" value="'.$mbg_t_op.'" required="required" />
                </div>
                <div class="col-4">
                    <label class="form-control-label" for="mbgc_t_op">'.geoloc_translate('Opacité du trait').'</label>
                    <input type="number" step="any" min="0" max="1" class="form-control" name="mbgc_t_op" id="mbgc_t_op" value="'.$mbgc_t_op.'" required="required" />
                </div>
                <div class="col-4" >
                    <label class="form-control-label" for="acg_t_op">'.geoloc_translate('Opacité du trait').'</label>
                    <input type="number" step="any" min="0" max="1" class="form-control" name="acg_t_op" id="acg_t_op" value="'.$acg_t_op.'" required="required" />
                </div>
            </div>
            <div class="row">
                <div class="col-4 bkmbg">
                    <label class="form-control-label" for="mbg_t_ep">'.geoloc_translate('Epaisseur du trait').'</label>
                    <input type="number" step="any" min="0" class="form-control" name="mbg_t_ep" id="mbg_t_ep" value="'.$mbg_t_ep.'" required="required" />
                </div>
                <div class="col-4">
                    <label class="form-control-label" for="mbgc_t_ep">'.geoloc_translate('Epaisseur du trait').'</label>
                    <input type="number" step="any" min="0" class="form-control" name="mbgc_t_ep" id="mbgc_t_ep" value="'.$mbgc_t_ep.'" required="required" />
                </div>
                <div class="col-4">
                    <label class="form-control-label" for="acg_t_ep">'.geoloc_translate('Epaisseur du trait').'</label>
                    <input type="number" step="any" min="0" class="form-control" name="acg_t_ep" id="acg_t_ep" value="'.$acg_t_ep.'" required="required" />
                </div>
            </div>
            <div class="row">
                <div class="col-4 bkmbg">
                    <label class="form-control-label" for="mbg_sc">'.geoloc_translate('Echelle').'</label>
                    <input type="number" step="any" min="0" max="3" class="form-control" name="mbg_sc" id="mbg_sc" placeholder="'.geoloc_translate('Echelle').'" value="'.$mbg_sc.'" required="required" />
                </div>
                <div class="col-4">
                    <label class="form-control-label" for="mbgc_sc">'.geoloc_translate('Echelle').'</label>
                    <input type="number" step="any" min="0" max="3" class="form-control" name="mbgc_sc" id="mbgc_sc" placeholder="'.geoloc_translate('Echelle').'" value="'.$mbgc_sc.'" required="required" />
                </div>
                <div class="col-4" >
                    <label class="form-control-label" for="acg_sc">'.geoloc_translate('Echelle').'</label>
                    <input type="number" step="any" min="0" max="3" class="form-control" name="acg_sc" id="acg_sc" placeholder="'.geoloc_translate('Echelle').'" value="'.$acg_sc.'" required="required" />
                </div>
            </div>
        </fieldset>
        <hr />
   <h4 class="my-3">'.geoloc_translate('Interface bloc').'</h4>
   <fieldset class="" style="padding-top: 16px; padding-right: 3px; padding-bottom: 6px;padding-left: 3px;">
      <div class="form-group row">
         <label class="form-control-label col-sm-6" for="cartyp_b">'.geoloc_translate('Type de carte').'<span class="text-danger ml-1">*</span></label>
         <div class="col-sm-6">
            <select class="custom-select form-control" name="cartyp_b" id="cartyp_b">
               <option>ROADMAP</option>
               <option>SATELLITE</option>
               <option>HYBRID</option>
               <option>TERRAIN</option>
               <option selected>'.$cartyp_b.'</option>
            </select>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-6" for="img_mbgb">'.geoloc_translate('Image membre géoréférencé').'<span class="text-danger ml-1">*</span></label>
         <div class="col-sm-6">
            <div class="input-group">
               <div class="input-group-addon"><img src="'.$ch_img.$img_mbgb.'" /></div>
               <input type="text" class="form-control" name="img_mbgb" id="img_mbgb" placeholder="Nom du fichier image" value="'.$img_mbgb.'" required="required" />
            </div>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-6" for="w_ico_b">'.geoloc_translate('Largeur icône des marqueurs').'<span class="text-danger ml-1">*</span></label>
         <div class="col-sm-6">
            <input type="number" class="form-control" name="w_ico_b" id="w_ico_b" placeholder="Chemin des images" value="'.$w_ico_b.'" required="required" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-6" for="h_ico_b">'.geoloc_translate('Hauteur icône des marqueurs').'<span class="text-danger ml-1">*</span></label>
         <div class="col-sm-6">
            <input type="number" class="form-control" name="h_ico_b" id="h_ico_b" placeholder="Chemin des images" value="'.$h_ico_b.'" required="required" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-6" for="h_b">'.geoloc_translate('Hauteur de la carte dans le bloc').'<span class="text-danger ml-1">*</span></label>
         <div class="col-sm-6">
            <input type="number" class="form-control" name="h_b" id="h_b" placeholder="'.geoloc_translate('Hauteur de la carte dans le bloc').'" value="'.$h_b.'" required="required" />
         </div>
      </div>
      </fieldset>
      <div class="form-group row">
         <div class="col-sm-6 ml-sm-auto">
            <button type="submit" class="btn btn-primary">'.geoloc_translate('Sauver').'</button>
         </div>
      </div>
       <input type="hidden" name="op" value="Extend-Admin-SubModule" />
       <input type="hidden" name="ModPath" value="'.$ModPath.'" />
       <input type="hidden" name="ModStart" value="'.$ModStart.'" />
       <input type="hidden" name="subop" value="SaveSetgeoloc" />
       <input type="hidden" name="svg_path" value="" />
   </form>
</div>
<div class="col-sm-4">
   <div id="map_conf" style="height:900px;"></div>
       Icones en service
   </div>

</div>';

   echo '
<script type="text/javascript">
//<![CDATA[
   $(document).ready(function() {
      $("head").append($("<script />").attr("src","lib/bootstrap-colorpicker-master/dist/js/bootstrap-colorpicker.min.js"));

      if($("#map_bloc").length) { 
         console.log("map_bloc est dans la page");//debug
         var 
         map_b,
         mapdivbl = document.getElementById("map_bloc"),
         icon_bl = {
            url: "'.$ch_img.$img_mbgb.'",
            size: new google.maps.Size('.$w_ico_b.','.$h_ico_b.'),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(0, 0),
            scaledSize: new google.maps.Size('.$w_ico_b.', '.$h_ico_b.')
         }
      }
      else {
         $("head").append($("<script />").attr("src","http://maps.google.com/maps/api/js?v=3.exp&amp;key='.$api_key.'&amp;language='.language_iso(1,'',0).'"));
         $("head").append($("<script />").attr("src","modules/geoloc/include/fontawesome-markers.min.js"));
      }
   });

function geoloc_conf() {
   $(document).ready(function() {
      if($("#map_bloc").length) { 
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

   var
   w_ico_size = $("#w_ico").val(),
   h_ico_size = $("#h_ico").val();

$(document).ready(function() {

    if(img_svg.checked) {$("#para_ima input").prop("readonly", true), $("#para_svg input, #f_mbg").prop("disabled", false)}
    if(img_img.checked) {$("#para_svg input, #f_mbg").prop("disabled", true)}

    $("#geolocset").on("submit", function() {
        $(".pickol").colorpicker("enable");
        $("#f_mbg").prop("disabled", false);
    });

    $("#img_img").on("click", function(){
        $("#para_svg input").prop("readonly", true);
//        $("#para_svg input").prop("disabled", true);
        $("#f_mbg").prop("disabled", true);
        $(".pickol").colorpicker("disable");
        $("#para_ima input").prop("readonly", false);
    });

    $("#img_svg").on("click", function(){
        $("#para_svg input").prop("readonly", false);
        $("#para_svg input").prop("disabled", false);
        $("#f_mbg").prop("disabled", false);
        $(".pickol").colorpicker("enable");
        $("#para_ima input").prop("readonly", true);
    });

    $("#f_mbg").change(function() {
        var str = $("#f_mbg option:selected").text();
        $("#vis_ic").html(\'<i id="fontchoice" class="fa fa-\'+ str.toLowerCase() +\' fa-lg "></i>\');
        $("#f_choice_mbg,#f_choice_mbgc,#f_choice_acg").attr("class","fa fa-"+ str.toLowerCase() +" fa-2x ");
    }).trigger("change");

$( "#w_ico, #h_ico, #ch_img, #nm_img_mbg, #nm_img_mbcg, #nm_img_acg, #f_mbg" ).change(function() {
    w_ico_size = $("#w_ico").val();
    h_ico_size = $("#h_ico").val();
    i_path_mbg = $("#ch_img").val()+$("#nm_img_mbg").val();
    i_path_mbcg = $("#ch_img").val()+$("#nm_img_mbcg").val();
    i_path_acg = $("#ch_img").val()+$("#nm_img_acg").val();
    f_pa = $("#f_mbg option:selected").text();
    
    
/*
    icon_cmbg.url=i_path_mbg;
    icon_cmbg.size=new google.maps.Size(w_ico_size, h_ico_size);
    icon_cmbg.scaledSize=new google.maps.Size(w_ico_size, h_ico_size);
*/

    icon_mbg_temp = {
                    url: i_path_mbg,
                    size: new google.maps.Size(w_ico_size, h_ico_size),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(0, 0),
                    scaledSize: new google.maps.Size(w_ico_size, h_ico_size)
                    };
  icon_mbgc_temp = {
                    url: i_path_mbcg,
                    size: new google.maps.Size(w_ico_size, h_ico_size),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(0, 0),
                    scaledSize: new google.maps.Size(w_ico_size, h_ico_size)
                    };
    icon_acg_temp = {
                    url: i_path_acg,
                    size: new google.maps.Size(w_ico_size, h_ico_size),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(0, 0),
                    scaledSize: new google.maps.Size(w_ico_size, h_ico_size)
                    };
                  
    }).trigger("change");
 
    $(".pickcol_fmb, .pickcol_fmbc, .pickcol_fac, .pickcol_tmb, .pickcol_tmbc, .pickcol_tac").colorpicker({format:"hex"});

        var 
        map_c, w_ico_size, h_ico_size, mark_cmbg, cartyp, pAth,mark_acg_svg,
        mapdivconf = document.getElementById("map_conf"),
            icon_cmbg = {
                        url: "'.$ch_img.$nm_img_mbg.'",
                        size: new google.maps.Size('.$w_ico.','.$h_ico.'),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(0, 0),
                        scaledSize: new google.maps.Size('.$w_ico.', '.$h_ico.')
                        },
            icon_cmbgc= {
                        url: "'.$ch_img.$nm_img_mbcg.'",
                        size: new google.maps.Size('.$w_ico.','.$h_ico.'),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(0, 0),
                        scaledSize: new google.maps.Size('.$w_ico.', '.$h_ico.')
                        },
            icon_cacg = {
                        url: "'.$ch_img.$nm_img_acg.'",
                        size: new google.maps.Size('.$w_ico.','.$h_ico.'),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(0, 0),
                        scaledSize: new google.maps.Size('.$w_ico.', '.$h_ico.')
                        },
        icon_mbg_svg = {
                        path: fontawesome.markers.'.str_replace('-', '_',$f_mbg).',
                        scale: '.$mbg_sc.',
                        strokeWeight: '.$mbg_t_ep.',
                        strokeColor: "'.$mbg_t_co.'",
                        strokeOpacity: '.$mbg_t_op.',
                        fillColor: "'.$mbg_f_co.'",
                        fillOpacity: '.$mbg_f_op.',
                        },
        icon_cmbg_svg = {
                        path: fontawesome.markers.'.str_replace('-', '_',$f_mbg).',
                        scale: '.$mbgc_sc.',
                        strokeWeight: '.$mbgc_t_ep.',
                        strokeColor: "'.$mbgc_t_co.'",
                        strokeOpacity: '.$mbgc_t_op.',
                        fillColor: "'.$mbgc_f_co.'",
                        fillOpacity: '.$mbgc_f_op.',
                        },
        icon_cacg_svg = {
                        path: fontawesome.markers.'.str_replace('-', '_',$f_mbg).',
                        scale: '.$acg_sc.',
                        strokeWeight: '.$acg_t_ep.',
                        strokeColor: "'.$acg_t_co.'",
                        strokeOpacity: '.$acg_t_op.',
                        fillColor: "'.$acg_f_co.'",
                        fillOpacity: '.$acg_f_op.',
                        }
                        ;

    var coul_temp,
        infoWindow = new google.maps.InfoWindow({maxWidth: 160}),
        map_c = new google.maps.Map(map_conf,{
            center: new google.maps.LatLng(45, 0),
            zoom :3,
            zoomControl:true,
            streetViewControl:false,
            mapTypeControl: false,
            scrollwheel: false,
            disableDoubleClickZoom: true 
        });
        map_c.setMapTypeId(google.maps.MapTypeId.'.$cartyp.');
        
        function createMarkerconf(point_b,map,icon,infoWindow,html) {
        var marker = new google.maps.Marker({
            position: point,
            map: map,
            icon: icon
        })
        google.maps.event.addDomListener(marker, "click", function() {
            infoWindow.setContent(html);
            infoWindow.open(map, marker);
        });
       return marker;
        }
        
        var point = new google.maps.LatLng(48,12);
        var mark_cmbg = createMarkerconf(point,map_c,icon_cmbg,infoWindow,"Je suis le marker (image au format .gif .jpg .png) symbolisant un membre du site g&#xE9;or&#xE9;f&#xE9;renc&#xE9;.");
        var point = new google.maps.LatLng(45,6);
        var mark_cmbgc = createMarkerconf(point,map_c,icon_cmbgc,infoWindow,"Je suis le marker (image au format .gif .jpg .png) symbolisant un membre du site g&#xE9;or&#xE9;f&#xE9;renc&#xE9; actuellement connecté sur le site.");
        var point = new google.maps.LatLng(40,12);
        var mark_cacg = createMarkerconf(point,map_c,icon_cacg,infoWindow,"Je suis le marker (image au format .gif .jpg .png) symbolisant un visiteur actuellement connecté sur le site géolocalisé par son adresse IP");

        var point = new google.maps.LatLng(10,10);
        var mark_cmbg_svg = createMarkerconf(point,map_c,icon_mbg_svg,infoWindow,"Je suis le marker (image au format SVG) symbolisant un membre du site g&#xE9;or&#xE9;f&#xE9;renc&#xE9;");
        var point = new google.maps.LatLng(47,1);
        var mark_cmbgc_svg = createMarkerconf(point,map_c,icon_cmbg_svg,infoWindow,"Je suis le marker (image au format SVG) symbolisant un membre du site g&#xE9;or&#xE9;f&#xE9;renc&#xE9; actuellement connecté sur le site.");
        var point = new google.maps.LatLng(60,5);
        var mark_acg_svg = createMarkerconf(point,map_c,icon_cacg_svg,infoWindow,"Je suis le marker (image au format SVG) symbolisant un visiteur actuellement connecté sur le site géolocalisé par son adresse IP.");

   $( "#w_ico, #h_ico, #ch_img, #nm_img_mbg, #nm_img_mbcg, #nm_img_acg" ).change(function() {
      w_ico_size = $("#w_ico").val();
      h_ico_size = $("#h_ico").val();

      icon_cmbg.url= $("#ch_img").val()+$("#nm_img_mbg").val();
      icon_cmbg.size=new google.maps.Size(w_ico_size, h_ico_size);
      icon_cmbg.scaledSize=new google.maps.Size(w_ico_size, h_ico_size);
      icon_cmbgc.url= $("#ch_img").val()+$("#nm_img_mbcg").val();
      icon_cmbgc.size=new google.maps.Size(w_ico_size, h_ico_size);
      icon_cmbgc.scaledSize=new google.maps.Size(w_ico_size, h_ico_size);
      icon_cacg.url= $("#ch_img").val()+$("#nm_img_acg").val();
      icon_cacg.size=new google.maps.Size(w_ico_size, h_ico_size);
      icon_cacg.scaledSize=new google.maps.Size(w_ico_size, h_ico_size);
      mark_cmbg.setIcon(icon_cmbg);
      mark_cmbgc.setIcon(icon_cmbgc);
      mark_cacg.setIcon(icon_cacg);

      $("#v_img_mbg").html("<img src=\""+$("#ch_img").val()+$("#nm_img_mbg").val()+"\" />");
      $("#v_img_mbcg").html("<img src=\""+$("#ch_img").val()+$("#nm_img_mbcg").val()+"\" />");
      $("#v_img_acg").html("<img src=\""+$("#ch_img").val()+$("#nm_img_acg").val()+"\" />");
   })

//==> change font
   $("#f_mbg").change(function(event) {
      var f_pa = $("#f_mbg option:selected").text();
      var fc= $("#mbg_f_co").val();
      switch (f_pa) {
         case "PLANE": var pAth = fontawesome.markers.PLANE; break;
         case "USERS": var pAth = fontawesome.markers.USERS; break;
         case "CIRCLE-O": var pAth = fontawesome.markers.CIRCLE_O; break;
         case "THUMB-TACK": var pAth = fontawesome.markers.THUMB_TACK; break;
         case "MAP-MARKER": var pAth = fontawesome.markers.MAP_MARKER; break;
         case "CROSSHAIRS": var pAth = fontawesome.markers.CROSSHAIRS; break;
         case "ASTERISK": var pAth = fontawesome.markers.ASTERISK; break;
         case "EYE": var pAth = fontawesome.markers.FIRE; break;
         case "COMMENT": var pAth = fontawesome.markers.COMMENT; break;
         case "STAR-O": var pAth = fontawesome.markers.STAR_O; break;
         case "HEART-O": var pAth = fontawesome.markers.HEART_O; break;
         case "CAMERA": var pAth = fontawesome.markers.CAMERA; break;
         case "ANCHOR": var pAth = fontawesome.markers.ANCHOR; break;
         case "FLAG": var pAth = fontawesome.markers.FLAG; break;
         case "HOME": var pAth = fontawesome.markers.HOME; break;
         case "FIRE": var pAth = fontawesome.markers.FIRE; break;
         default: var pAth = fontawesome.markers.USER;break;
      }
      console.log(pAth);//debug
      console.log(icon_mbg_svg);//debug

      icon_mbg_svg.path=pAth;
      icon_cmbg_svg.path=pAth;
      icon_cacg_svg.path=pAth;

      $("#fontchoice").attr("style","color:"+coul_temp);
      mark_cmbg_svg.setIcon(icon_mbg_svg);
      mark_cmbgc_svg.setIcon(icon_cmbg_svg);
      mark_acg_svg.setIcon(icon_cacg_svg);
   })
   
    $("#mbg_sc").change(function() {
        icon_mbg_svg.scale = Number($("#mbg_sc").val());
        mark_cmbg_svg.setIcon(icon_mbg_svg);
    });
    $("#mbgc_sc").change(function() {
        icon_cmbg_svg.scale = Number($("#mbgc_sc").val());
        mark_cmbgc_svg.setIcon(icon_cmbg_svg);
    });
    $("#acg_sc").change(function() {
        icon_cacg_svg.scale = Number($("#acg_sc").val());
        mark_acg_svg.setIcon(icon_cacg_svg);
    });

    $("#mbg_f_op").change(function() {
        icon_mbg_svg.fillOpacity = Number($("#mbg_f_op").val());
        mark_cmbg_svg.setIcon(icon_mbg_svg);
    });
    $("#mbgc_f_op").change(function() {
        icon_cmbg_svg.fillOpacity = Number($("#mbgc_f_op").val());
        mark_cmbgc_svg.setIcon(icon_cmbg_svg);
    });
    $("#acg_f_op").change(function() {
        icon_cacg_svg.fillOpacity = Number($("#acg_f_op").val());
        mark_acg_svg.setIcon(icon_cacg_svg);
    });

    $("#mbg_t_op").change(function() {
        icon_mbg_svg.strokeOpacity = Number($("#mbg_t_op").val());
        mark_cmbg_svg.setIcon(icon_mbg_svg);
    });
    $("#mbgc_t_op").change(function() {
        icon_cmbg_svg.strokeOpacity = Number($("#mbgc_t_op").val());
        mark_cmbgc_svg.setIcon(icon_cmbg_svg);
    });
    $("#acg_t_op").change(function() {
        icon_cacg_svg.strokeOpacity = Number($("#acg_t_op").val());
        mark_acg_svg.setIcon(icon_cacg_svg);
    });

    $("#mbg_t_ep").change(function() {
        icon_mbg_svg.strokeWeight = Number($("#mbg_t_ep").val());
        mark_cmbg_svg.setIcon(icon_mbg_svg);
    });
    $("#mbgc_t_ep").change(function() {
        icon_cmbg_svg.strokeWeight = Number($("#mbgc_t_ep").val());
        mark_cmbgc_svg.setIcon(icon_cmbg_svg);
    });
    $("#acg_t_ep").change(function() {
        icon_cacg_svg.strokeWeight = Number($("#acg_t_ep").val());
        mark_acg_svg.setIcon(icon_cacg_svg);
    });

    $(".pickcol_fmb").colorpicker().on("changeColor.colorpicker", function(event){
        var coul = event.color.toHex()
        $("#f_choice_mbg").attr("style","color:"+coul);
        icon_mbg_svg.fillColor=coul;
        mark_cmbg_svg.setIcon(icon_mbg_svg);
    });
    $(".pickcol_fmbc").colorpicker().on("changeColor.colorpicker", function(event){
        var coul = event.color.toHex()
        $("#f_choice_mbgc").attr("style","color:"+coul);
        icon_cmbg_svg.fillColor=coul;
        mark_cmbgc_svg.setIcon(icon_cmbg_svg);
    });
    $(".pickcol_fac").colorpicker().on("changeColor.colorpicker", function(event){
        var coul = event.color.toHex()
        $("#f_choice_acg").attr("style","color:"+coul);
        icon_cacg_svg.fillColor=coul;
        mark_acg_svg.setIcon(icon_cacg_svg);
    });
    
    $(".pickcol_tmb").colorpicker().on("changeColor.colorpicker", function(event){
        var coul = event.color.toHex()
        icon_mbg_svg.strokeColor=coul;
        mark_cmbg_svg.setIcon(icon_mbg_svg);
    });
    $(".pickcol_tmbc").colorpicker().on("changeColor.colorpicker", function(event){
        var coul = event.color.toHex()
        icon_cmbg_svg.strokeColor=coul;
        mark_cmbgc_svg.setIcon(icon_cmbg_svg);
    });
    $(".pickcol_tac").colorpicker().on("changeColor.colorpicker", function(event){
        var coul = event.color.toHex()
        icon_cacg_svg.strokeColor=coul;
        mark_acg_svg.setIcon(icon_cacg_svg);
    });

    $( "#cartyp" ).change(function() {
    cartyp = $( "#cartyp option:selected" ).text();
    switch (cartyp)
    {
        case "TERRAIN":
        map_c.setMapTypeId(google.maps.MapTypeId.TERRAIN);
        break;
        case "SATELLITE":
        map_c.setMapTypeId(google.maps.MapTypeId.SATELLITE);
        break;
        case "ROADMAP":
        map_c.setMapTypeId(google.maps.MapTypeId.ROADMAP);
        break;
        case "HYBRID":
        map_c.setMapTypeId(google.maps.MapTypeId.HYBRID);
        break;
    }
    })
    $( "#cartyp_b" ).change(function() {
        cartyp_b = $( "#cartyp_b option:selected" ).text();
        switch (cartyp_b)
        {
        case "TERRAIN":
        map_c.setMapTypeId(google.maps.MapTypeId.TERRAIN);
        break;
        case "SATELLITE":
        map_c.setMapTypeId(google.maps.MapTypeId.SATELLITE);
        break;
        case "ROADMAP":
        map_c.setMapTypeId(google.maps.MapTypeId.ROADMAP);
        break;
        case "HYBRID":
        map_c.setMapTypeId(google.maps.MapTypeId.HYBRID);
        break;
        }
    })

});
}

window.onload = geoloc_conf;

//]]>
</script>';
adminfoot('','','','');

}

function SaveSetgeoloc($api_key, $ch_lat, $ch_lon, $cartyp, $geo_ip, $co_unit, $mark_typ, $ch_img, $nm_img_acg, $nm_img_mbcg, $nm_img_mbg, $w_ico, $h_ico, $f_mbg, $mbg_sc, $mbg_t_ep, $mbg_t_co, $mbg_t_op, $mbg_f_co, $mbg_f_op, $mbgc_sc, $mbgc_t_ep, $mbgc_t_co, $mbgc_t_op, $mbgc_f_co, $mbgc_f_op, $acg_sc, $acg_t_ep, $acg_t_co, $acg_t_op, $acg_f_co, $acg_f_op, $cartyp_b, $img_mbgb, $w_ico_b, $h_ico_b, $h_b, $ModPath, $ModStart) {
   //==> modifie le fichier de configuration
   $file_conf = fopen("modules/$ModPath/geoloc_conf.php", "w+");
   $content = "<?php \n";
   $content .= "/************************************************************************/\n";
   $content .= "/* DUNE by NPDS                                                         */\n";
   $content .= "/* ===========================                                          */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* NPDS Copyright (c) 2002-2017 by Philippe Brunier                     */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* This program is free software. You can redistribute it and/or modify */\n";
   $content .= "/* it under the terms of the GNU General Public License as published by */\n";
   $content .= "/* the Free Software Foundation; either version 2 of the License.       */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* module geoloc version 3.0                                            */\n";
   $content .= "/* geoloc_conf.php file 2008-2017 by Jean Pierre Barbary (jpb)          */\n";
   $content .= "/* dev team : Philippe Revilliod (Phr)                                  */\n";
   $content .= "/************************************************************************/\n";
   $content .= "\$api_key = \"$api_key\"; // clef api google \n";
   $content .= "\$ch_lat = \"$ch_lat\"; // Champ lat dans sql \n";
   $content .= "\$ch_lon = \"$ch_lon\"; // Champ long dans sql \n";
   $content .= "// interface carte \n";
   $content .= "\$cartyp = \"$cartyp\"; // Type de carte \n";
   $content .= "\$co_unit = \"$co_unit\"; // Coordinates Units\n";
   $content .= "\$ch_img = \"$ch_img\"; // Chemin des images \n";
   $content .= "\$geo_ip = $geo_ip; // Autorisation de géolocalisation des IP \n";
   $content .= "\$nm_img_acg = \"$nm_img_acg\"; // Nom fichier image anonyme géoréférencé en ligne \n";
   $content .= "\$nm_img_mbcg = \"$nm_img_mbcg\"; // Nom fichier image membre géoréférencé en ligne \n";
   $content .= "\$nm_img_mbg = \"$nm_img_mbg\"; // Nom fichier image membre géoréférencé \n";
   $content .= "\$mark_typ = \"$mark_typ\"; // Type de marker \n";
   $content .= "\$w_ico = \"$w_ico\"; // Largeur icone des markers \n";
   $content .= "\$h_ico = \"$h_ico\"; // Hauteur icone des markers\n";
   $content .= "\$f_mbg = \"$f_mbg\"; // Font SVG \n";
//   $content .= "\$svg_path = \"$svg_path\"; // path \n";
   $content .= "\$mbg_sc = \"$mbg_sc\"; // Echelle du Font SVG du membre \n";
   $content .= "\$mbg_t_ep = \"$mbg_t_ep\"; // Epaisseur trait Font SVG du membre \n";
   $content .= "\$mbg_t_co = \"$mbg_t_co\"; // Couleur trait SVG du membre \n";
   $content .= "\$mbg_t_op = \"$mbg_t_op\"; // Opacité trait SVG du membre \n";
   $content .= "\$mbg_f_co = \"$mbg_f_co\"; // Couleur fond SVG du membre \n";
   $content .= "\$mbg_f_op = \"$mbg_f_op\"; // Opacité fond SVG du membre \n";
   $content .= "\$mbgc_sc = \"$mbgc_sc\"; // Echelle du Font SVG du membre géoréférencé \n";
   $content .= "\$mbgc_t_ep = \"$mbgc_t_ep\"; // Epaisseur trait Font SVG du membre géoréférencé \n";
   $content .= "\$mbgc_t_co = \"$mbgc_t_co\"; // Couleur trait SVG du membre géoréférencé \n";
   $content .= "\$mbgc_t_op = \"$mbgc_t_op\"; // Opacité trait SVG du membre géoréférencé \n";
   $content .= "\$mbgc_f_co = \"$mbgc_f_co\"; // Couleur fond SVG du membre géoréférencé \n";
   $content .= "\$mbgc_f_op = \"$mbgc_f_op\"; // Opacité fond SVG du membre géoréférencé \n";
   $content .= "\$acg_sc = \"$acg_sc\"; // Echelle du Font SVG pour anonyme en ligne \n";
   $content .= "\$acg_t_ep = \"$acg_t_ep\"; // Epaisseur trait Font SVG pour anonyme en ligne \n";
   $content .= "\$acg_t_co = \"$acg_t_co\"; // Couleur trait SVG pour anonyme en ligne \n";
   $content .= "\$acg_t_op = \"$acg_t_op\"; // Opacité trait SVG pour anonyme en ligne \n";
   $content .= "\$acg_f_co = \"$acg_f_co\"; // Couleur fond SVG pour anonyme en ligne \n";
   $content .= "\$acg_f_op = \"$acg_f_op\"; // Opacité fond SVG pour anonyme en ligne \n";
   $content .= "// interface bloc \n";
   $content .= "\$cartyp_b = \"$cartyp_b\"; // Type de carte pour le bloc \n";
   $content .= "\$img_mbgb = \"$img_mbgb\"; // Nom fichier image membre géoréférencé pour le bloc \n";
   $content .= "\$w_ico_b = \"$w_ico_b\"; // Largeur icone marker dans le bloc \n"; 
   $content .= "\$h_ico_b = \"$h_ico_b\"; // Hauteur icone marker dans le bloc\n";
   $content .= "\$h_b = \"$h_b\"; // hauteur carte dans bloc\n";
   $content .= "?>";

   fwrite($file_conf, $content);
   fclose($file_conf);
   //<== modifie le fichier de configuration
}

if ($admin) {

   switch ($subop) {
      case 'vidip':
          vidip();
          Configuregeoloc($subop, $ModPath, $ModStart, $ch_lat, $ch_lon, $cartyp, $geo_ip);
      break;
      case 'SaveSetgeoloc':
         SaveSetgeoloc($api_key, $ch_lat, $ch_lon, $cartyp, $geo_ip, $co_unit, $mark_typ, $ch_img, $nm_img_acg, $nm_img_mbcg, $nm_img_mbg, $w_ico, $h_ico, $f_mbg, $mbg_sc, $mbg_t_ep, $mbg_t_co, $mbg_t_op, $mbg_f_co, $mbg_f_op, $mbgc_sc, $mbgc_t_ep, $mbgc_t_co, $mbgc_t_op, $mbgc_f_co, $mbgc_f_op, $acg_sc, $acg_t_ep, $acg_t_co, $acg_t_op, $acg_f_co, $acg_f_op, $cartyp_b, $img_mbgb, $w_ico_b, $h_ico_b, $h_b, $ModPath, $ModStart);
         Configuregeoloc($subop, $ModPath, $ModStart, $ch_lat, $ch_lon, $cartyp, $geo_ip);
      break;
      default:
         Configuregeoloc($subop, $ModPath, $ModStart, $ch_lat, $ch_lon, $cartyp, $geo_ip);
      break;
   }
}
?>
<?php
/************************************************************************/
/* DUNE by NPDS - admin prototype                                       */
/* ===========================                                          */
/*                                                                      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2014 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!stristr($_SERVER['PHP_SELF'],"admin.php")) { Access_Error(); }
$f_meta_nom ='mod_authors';
$f_titre = adm_translate("Gestion des Administrateurs");
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit

global $language, $adminimg, $admf_ext;
$listdroits='';$listdroitsmodulo='';
$hlpfile = "manuels/$language/authors.html";

// sélection des fonctions sauf les fonctions de type alerte 
   $R = sql_query("SELECT fid, fnom, fnom_affich, fcategorie FROM ".$NPDS_Prefix."fonctions f WHERE f.finterface =1 and fcategorie < 7 order by f.fcategorie");
   while(list($fid, $fnom, $fnom_affich, $fcategorie) = sql_fetch_row($R)) { 
      if($fcategorie==6) {
         $listdroitsmodulo .= '
         <div class="col-md-4">
            <label class="" for="ad_d_m_'.$fid.'">
               <input id="ad_d_m_'.$fnom.'" type="checkbox" name="ad_d_m_'.$fnom.'" value="'.$fid.'" /> '.$fnom_affich.'
            </label>
         </div>'."\n";
      } else {
         $listdroits .='
         <div class="col-md-4">
            <label class="" for="ad_d_'.$fid.'">
               <input id="ad_d_'.$fid.'" type="checkbox" name="ad_d_'.$fid.'" value="'.$fid.'" /> '.$fnom_affich.'
            </label>
         </div>'."\n";
      }
   }

$scri_check ='
<script type="text/javascript">
    //<![CDATA[
    document.addEventListener("DOMContentLoaded", rtu, false)
            function rtu() {
            // bascule toutes les checkbox
            var checkUncheck = document.getElementById("cb_radminsuper");
            var admdroits = document.getElementById("adm_droi_f");
            checkUncheck.addEventListener("click", function() {
                var inputs = adm_droi_f.getElementsByTagName("input");
                for (var i = 0; i < inputs.length ; i++) {
                        if (inputs[i].type == "checkbox") {
                        inputs[i].checked = checkUncheck.checked;
                        inputs[i].disabled = checkUncheck.checked;
                    }
                }
                var inputs_m = adm_droi_m.getElementsByTagName("input");
                for (var i = 0; i < inputs_m.length ; i++) {
                        if (inputs_m[i].type == "checkbox") {
                        inputs_m[i].checked = checkUncheck.checked;
                        inputs_m[i].disabled = checkUncheck.checked;
                    }
                }
            }, false);
            
            // coche toutes les checkbox des droits admin
            document.getElementById("checkAllButton").addEventListener("click", function() {
                var inputs = adm_droi_f.getElementsByTagName("input");
                for (var i = 0; i < inputs.length ; i++) {
                    if (inputs[i].type == "checkbox") {
                        inputs[i].checked = true;
                    }
                }
            }, false);
            // décoche toutes les checkbox des droits admin
            document.getElementById("uncheckAllButton").addEventListener("click", function() {
                var inputs = adm_droi_f.getElementsByTagName("input");
                for (var i = 0; i < inputs.length ; i++) {
                    if (inputs[i].type == "checkbox") {
                        inputs[i].checked = false;
                    }
                }
            }, false);
            // coche toutes les checkbox des droits modules
            document.getElementById("checkAllButton_m").addEventListener("click", function() {
                var inputs = adm_droi_m.getElementsByTagName("input");
                for (var i = 0; i < inputs.length ; i++) {
                    if (inputs[i].type == "checkbox") {
                        inputs[i].checked = true;
                    }
                }
            }, false);
            // décoche toutes les checkbox des droits modules
            document.getElementById("uncheckAllButton_m").addEventListener("click", function() {
                var inputs = adm_droi_m.getElementsByTagName("input");
                for (var i = 0; i < inputs.length ; i++) {
                    if (inputs[i].type == "checkbox") {
                        inputs[i].checked = false;
                    }
                }
            }, false);
            }
    //]]>
    </script>'."\n";

function modulesadmin ($chng_moduadmin) {
global $modu,$fieldnames,$NPDS_Prefix;
// from plugins.php ... analyse extend-modules.txt, construction list des droits admin module
   if (file_exists("admin/extend-modules.txt")) {
    // ==> tableau des droits de l'admin (le nom de la fonction(= champ) si droit est à 1) a little bit obsolete since lesradmin ne sont plus la a l'exception de fm et super
    $result=sql_query("select * from ".$NPDS_Prefix."authors where aid='$chng_moduadmin'");
    $row = mysql_fetch_assoc ($result);
      $droit_adm_fonct=array();
      foreach ($fieldnames as $k =>$v) {
        if ($row[$v]==1) {$droit_adm_fonct[]=$v;}
      }
    // <== tableau des droits de l'admin (le nom de la fonction(= champ) si droit est à 1)
   
      $fp=fopen("admin/extend-modules.txt","r");
      if (filesize("admin/extend-modules.txt")>0)
         $Xcontent=fread($fp,filesize("admin/extend-modules.txt"));
      fclose($fp);
      $tmp = explode("[/module]",$Xcontent);
      array_pop($tmp);
      $modu=array();
// faut encore voir la contradiction entre les droits génériques (no right etc) et les droits individuels et comment on imbrique .. si on considère que extend est par défaut le paramétrage puis on récupère pour la base et faudra ensuite regénérer extend ?....donc ce serait les droits individuels (dans la bd...) + les droits généraux
      foreach ($tmp as $ibid) {
         $Tnom=explode("[/nom]",$ibid);
         if ($ibid) {
            $Tlevel=explode("[/niveau]",$ibid);
            if (strpos($ibid,"[/niveau]")==0) {};//pas besoin ? c'est un superadmin?...

               $TModPath=explode("[/ModPath]",$ibid);
               $chemin=substr($TModPath[0], strpos($TModPath[0],"[ModPath]")+9);
               $modu[]=$chemin;
               // tableau des admins du modules (|aid|aid)

               $result = sql_query("select madmin from ".$NPDS_Prefix."modules where mnom='$chemin'");
               
               
               list($radminmodule) = sql_fetch_row($result);
               $listradminmodule[]=$radminmodule;
               // si on netrouve pas l'administrateur dans le module extend ne peut pas etre noright...mais que super admin...
               //               if(!strstr ( $radminmodule, $chng_moduadmin ) {};
              if(substr($Tlevel[0], strpos($Tlevel[0],"[niveau]")+8)!=="no-right" and !in_array (substr($Tlevel[0], strpos($Tlevel[0],"[niveau]")+8), $droit_adm_fonct)){};

               if(strstr ( $radminmodule, $chng_moduadmin ) or substr($Tlevel[0], strpos($Tlevel[0],"[niveau]")+8)=="no-right" or in_array (substr($Tlevel[0], strpos($Tlevel[0],"[niveau]")+8), $droit_adm_fonct)) {
               $listdroits_mod .= '<li><input type="checkbox" name="chng_'.$chemin.'" value="'.$chng_moduadmin.'" checked="checked" /> '.substr($Tnom[0], strpos($Tnom[0],"[nom]")+5).'</li>'."\n";
               }
               else
               {
               $listdroits_mod .= '<li><input type="checkbox" name="chng_'.$chemin.'" value="'.$chng_moduadmin.'" /> '.substr($Tnom[0], strpos($Tnom[0],"[nom]")+5).'</li>'."\n";
               }
         }
         }//fin de la boucle
   }
   return array ($listdroits_mod,$modu,$listradminmodule);
}


function displayadmins() {
   global $hlpfile, $NPDS_Prefix, $admf_ext, $fieldnames, $listdroits, $listdroitsmodulo, $f_meta_nom, $f_titre, $adminimg, $scri_check;
   include("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   $result = sql_query("select aid, name, url, email from ".$NPDS_Prefix."authors");
   echo '
   <h3>'.adm_translate("Les administrateurs").'</h3>
   <table id="tab_adm" data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-show-export="true">
      <thead>
         <tr>
            <th data-sortable="true">'.adm_translate('Nom').'</th>
            <th data-sortable="true">'.adm_translate('Email').'</th>
            <th>'.adm_translate('Fonctions').'</th>
         </tr>
      </thead>
      <tbody>';
   while(list($a_aid, $name, $url, $email) = sql_fetch_row($result)) {
      echo '
         <tr>
            <td>'.$a_aid.'</td>
            <td>'.$email.'</td>
            <td align="right" nowrap="nowrap">
               <a href="admin.php?op=modifyadmin&amp;chng_aid='.$a_aid.'" class=""><i class="fa fa-edit fa-lg" title="'.adm_translate("Modifier l'information").'" data-toggle="tooltip"></i></a>&nbsp;
               <a href="mailto:'.$email.'"><i class="fa fa-envelope-o fa-lg" title="'.adm_translate("Envoyer un courriel &#xE0;").' '.$a_aid.'" data-toggle="tooltip"></i></a>&nbsp;';
      if($url!='')
         echo'
               <a href="'.$url.'"><i class="fa fa-external-link fa-lg" title="'.adm_translate("Visiter le site web").'" data-toggle="tooltip"></i></a>&nbsp;';
         echo '
               <a href="admin.php?op=deladmin&amp;del_aid='.$a_aid.'" ><i class="fa fa-trash-o fa-lg text-danger" title="'.adm_translate("Effacer l'Auteur").'" data-toggle="tooltip" ></i></a>
            </td>
         </tr>';
   }
   echo '
      </tbody>
   </table>
    <h3>'.adm_translate("Nouvel administrateur").'</h3>
    <form id="nou_adm" class="form-horizontal" action="admin.php" method="post">
        <fieldset>
        <legend><img src="'.$adminimg.'authors.'.$admf_ext.'" class="vam" border="0" width="24" height="24" alt="'.adm_translate("Informations").'" /> '.adm_translate("Informations").' </legend>
        <br /><div class="form-group">
            <label class="control-label col-sm-4" for="add_aid">'.adm_translate("Surnom").'</label>
            <div class="col-sm-8">
                <input id="add_aid" class="form-control" type="text" name="add_aid" maxlength="30" placeholder="'.adm_translate("Surnom").'" required="required" />
                <span class="help-block text-right"><span id="countcar_add_aid"></span></span>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4 col-md-4" for="add_name">'.adm_translate("Nom").'</label>
            <div class="col-sm-8 col-md-8">
                <input id="add_name" class="form-control" type="text" name="add_name" maxlength="50" placeholder="'.adm_translate("Nom").'" required="required" />
                <span class="help-block text-right"><span id="countcar_add_name"></span></span>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4 col-md-4" for="add_email">'.adm_translate("E-mail").'</label>
            <div class="col-sm-8 col-md-8">
                <input id="add_email" class="form-control" type="email" name="add_email" maxlength="60" placeholder="'.adm_translate("E-mail").'" required="required" />
                <span class="help-block text-right"><span id="countcar_add_email"></span></span>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4 col-md-4" for="add_url">'.adm_translate("URL").'</label>
            <div class="col-sm-8 col-md-8">
                <input id="add_url" class="form-control" type="url" name="add_url" maxlength="60" placeholder="'.adm_translate("URL").'" />
                <span class="help-block text-right"><span id="countcar_add_url"></span></span>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4 col-md-4" for="add_pwd">'.adm_translate("Mot de Passe").'</label>
            <div class="col-sm-8 col-md-8">
               <input id="add_pwd" class="form-control" type="password" name="add_pwd" maxlength="12" placeholder="'.adm_translate("Mot de Passe").'" required="required" />
               <span class="help-block text-right"><span id="countcar_add_pwd"></span></span>
               <div class="progress password-meter" id="passwordMeter">
                  <div class="progress-bar"></div>
               </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4 col-md-4 text-danger" for="cb_radminsuper" >'.adm_translate("Super administrateur").'</label>
            <div class="col-sm-8 col-md-8">
                <input id="cb_radminsuper" class="form-control" type="checkbox" name="add_radminsuper" value="1" data-toggle="collapse" data-target="#adm_droi_f,#adm_droi_m" />'."\n".'
            </div>
        </div>
    </fieldset>
    <fieldset>
    <legend><img src="'.$adminimg.'authors.'.$admf_ext.'" class="vam" border="0" width="24" height="24" alt="'.adm_translate("Droits").'" /> '.adm_translate("Droits").' </legend>
    <div id="adm_droi_f" class="container-fluid collapse in">
        <div class="form-group"><a id="checkAllButton"><i class="fa fa-check-square-o fa-lg"></i></a>&nbsp;<a id="uncheckAllButton"><i class="fa fa-square-o fa-lg"></i></a></div>'."\n";
    echo $listdroits."\n";
    echo'
    </div>
    </fieldset>
    <fieldset>
    <legend><img src="'.$adminimg.'authors.'.$admf_ext.'" class="vam" border="0" width="24" height="24" alt="'.adm_translate("Droits Modules").'" /> '.adm_translate("Droits Modules").' </legend>
    <div id="adm_droi_m" class="container-fluid collapse in">
        <div class="form-group"><a id="checkAllButton_m"><i class="fa fa-check-square-o fa-lg"></i></a>&nbsp<a id="uncheckAllButton_m"><i class="fa fa-square-o fa-lg"></i></a></div>'."\n";
    echo $listdroitsmodulo;
    echo'
    </div>
    <br />
    <div class="form-group">
        <div class=" col-md-6 col-xs-12">
            <button class="btn btn-primary col-xs-12" type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;'.adm_translate(" Ajouter un Administrateur ").'</button>
        </div>
    </div>
    <input type="hidden" name="op" value="AddAuthor" />
    </fieldset>
    </form>
    </div>';
    echo $scri_check;
    
   echo '
   <script type="text/javascript">
   //<![CDATA[
      inpandfieldlen("add_aid",30);
      inpandfieldlen("add_name",50);
      inpandfieldlen("add_email",60);
      inpandfieldlen("add_url",60);
      inpandfieldlen("add_pwd",12);
   //]]>
   </script>';
   $fv_parametres = '
   add_aid: {
      validators: {
         callback: {
            message: "Ce surnom n\'est pas disponible",
            callback: function(value, validator, $field) {
            return $.inArray(value, admin) == -1;
            }
         }
      }
   },
   add_name: {
      validators: {
         callback: {
            message: "Ce nom n\'est pas disponible",
            callback: function(value, validator, $field) {
               return $.inArray(value, adminname) == -1;
            }
         }
      }
   },
   add_email: {
   },
   add_url: {
   },
   add_pwd: {
      validators: {
         notEmpty: {
            message: "The password is required and cannot be empty"
         },
         callback: {
            callback: function(value, validator, $field) {
               var score = 0;
               if (value === "") {
                  return {
                     valid: true,
                     score: null
                  };
               }
               // Check the password strength
               score += ((value.length >= 8) ? 1 : -1);
               // The password contains uppercase character
               if (/[A-Z]/.test(value)) {score += 1;}
               // The password contains uppercase character
               if (/[a-z]/.test(value)) {score += 1;}
               // The password contains number
               if (/[0-9]/.test(value)) {score += 1;}
               // The password contains special characters
               if (/[!#$%&^~*_]/.test(value)) {score += 1;}
               return {
               valid: true,
               score: score    // We will get the score later
               };
            }
         }
      }
   },
   ';
   echo auto_complete ('admin', 'aid', 'authors', '', '0');
   echo auto_complete ('adminname', 'name', 'authors', '', '0');
   adminfoot('fv',$fv_parametres,'','');
}

function modifyadmin($chng_aid) {
   global $hlpfile, $aid, $NPDS_Prefix, $admf_ext, $f_meta_nom, $f_titre, $adminimg, $scri_check, $fv_parametres;
   include("header.php");
//   list ($listdroits_mod,$modu) = modulesadmin($chng_aid);
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '
   <h3>'.adm_translate("Mise &#xE0; jour de l'administrateur").' : '.$chng_aid.'</h3>';
    
   $result = sql_query("select aid, name, url, email, pwd, radminfilem, radminsuper from ".$NPDS_Prefix."authors where aid='$chng_aid'");
   list($chng_aid, $chng_name, $chng_url, $chng_email, $chng_pwd, $chng_radminfilem, $chng_radminsuper) = sql_fetch_row($result);

   if ($chng_radminsuper==1) {
      $supadm_inp = ' checked="checked"';
      $ldvisi = '';
   } else {
      $supadm_inp ='';
      $ldvisi = ' in';
   };
    
   //==> construction des check-box des droits
   $listdroits ='';$listdroitsmodulo='';
   $result3 = sql_query("SELECT * FROM ".$NPDS_Prefix."droits WHERE d_aut_aid ='$chng_aid'");
   $datas=array();
   while ($data = sql_fetch_row($result3)) {
      $datas[] = $data[1];
   }
   $R = sql_query("SELECT fid, fnom, fnom_affich, fcategorie FROM ".$NPDS_Prefix."fonctions f WHERE f.finterface =1 and fcategorie < 7 order by f.fcategorie");
   while(list($fid, $fnom, $fnom_affich, $fcategorie) = sql_fetch_row($R)) {
      if (in_array($fid, $datas)) $chec='checked="checked"'; else $chec='';
      if($fcategorie==6) {
        $listdroitsmodulo .='
        <div class="col-md-4">
        <label class="" for="ad_d_m_'.$fid.'">
            <input id="ad_d_m_'.$fnom.'" type="checkbox" '.$chec.' name="ad_d_m_'.$fnom.'" value="'.$fid.'" /> '.$fnom_affich.'
        </label>
        </div>';
      }
      else { 
         $listdroits .='
    <div class="col-md-4">
    <label class="" for="ad_d_'.$fid.'">
        <input id="ad_d_'.$fid.'" type="checkbox" '.$chec.' name="ad_d_'.$fid.'" value="'.$fid.'" /> '.$fnom_affich.'
    </label>
    </div>';
      }
   } 
   //<== construction des check-box des droits
    
   echo '
   <form id="mod_adm" class="form-horizontal" action="admin.php" method="post">
      <fieldset>
         <legend><img src="'.$adminimg.'authors.'.$admf_ext.'" class="vam" border="0" width="24" height="24" alt="'.adm_translate("Informations").'" title="'.$chng_aid.'" /> '.adm_translate("Informations").'</legend>
         <br />
         <div class="form-group">
            <label class="control-label col-sm-4 col-md-4" for="chng_name">'.adm_translate("Nom").'</label>
            <div class="col-sm-8 col-md-8">
               <input id="chng_name" class="form-control" type="text" name="chng_name" value="'.$chng_name.'" maxlength="30" placeholder="'.adm_translate("Nom").'" required="required" />
               <span class="help-block text-right"><span id="countcar_chng_name"></span></span>
            </div>
         </div>
         <div class="form-group">
            <label class="control-label col-sm-4 col-md-4" for="chng_email">'.adm_translate("E-mail").'</label>
            <div class="col-sm-8 col-md-8">
               <input id="chng_email" class="form-control" type="text" name="chng_email" value="'.$chng_email.'" maxlength="60" placeholder="'.adm_translate("E-mail").'" required="required" />
               <span class="help-block text-right"><span id="countcar_chng_email"></span></span>
            </div>
         </div>
         <div class="form-group">
            <label class="control-label col-sm-4 col-md-4" for="chng_url">'.adm_translate("URL").'</label>
            <div class="col-sm-8 col-md-8">
               <input id="chng_url" class="form-control" type="url" name="chng_url" value="'.$chng_url.'" maxlength="60" placeholder="'.adm_translate("URL").'" />
               <span class="help-block text-right"><span id="countcar_chng_url"></span></span>
            </div>
         </div>
         <div class="form-group">
            <label class="control-label col-sm-4 col-md-4" for="chng_pwd">'.adm_translate("Mot de Passe").'</label>
            <div class="col-sm-8 col-md-8">
               <input id="chng_pwd" class="form-control" type="password" name="chng_pwd" maxlength="12" placeholder="'.adm_translate("Mot de Passe").'" title="'.adm_translate("Entrez votre nouveau Mot de Passe").'" />
               <span class="help-block text-right"><span id="countcar_chng_pwd"></span></span>
               <div class="progress password-meter" id="passwordMeter">
                  <div class="progress-bar"></div>
               </div>
            </div>
         </div>
         <div class="form-group">
            <label class="control-label col-sm-4 col-md-4" for="chng_pwd2">'.adm_translate("Mot de Passe").'</label>
            <div class="col-sm-8 col-md-8">
               <input id="chng_pwd2" class="form-control" type="password" name="chng_pwd2" maxlength="12" placeholder="'.adm_translate("Mot de Passe").'" title="'.adm_translate("Entrez votre nouveau Mot de Passe").'" />
               <span class="help-block text-right"><span id="countcar_chng_pwd2"></span></span>
            </div>
         </div>
         <div class="form-group">
            <label class="control-label col-sm-4 col-md-4 text-danger" for="chng_radminsuper" >'.adm_translate("Super administrateur").'</label>
            <div class="col-sm-8 col-md-8">
               <input id="cb_radminsuper" class="form-control" type="checkbox" name="chng_radminsuper" value="1" '.$supadm_inp.' data-toggle="collapse" data-target="#adm_droi_f,#adm_droi_m" />
            </div>
         </div>
            <input type="hidden" name="chng_aid" value="'.$chng_aid.'" />
      </fieldset>
      <fieldset>
         <legend><img src="'.$adminimg.'authors.'.$admf_ext.'" class="vam" border="0" width="24" height="24" alt="'.adm_translate("Droits").'" /> '.adm_translate("Droits").' </legend>
         <div id="adm_droi_f" class="container-fluid collapse '.$ldvisi.' ">
            <div class="form-group"><a id="checkAllButton"><i class="fa fa-check-square-o fa-lg"></i></a>&nbsp;<a id="uncheckAllButton"><i class="fa fa-square-o fa-lg"></i></a></div>';
   echo $listdroits;
   echo'
        </div>
      </fieldset>
      <fieldset>
         <legend><img src="'.$adminimg.'authors.'.$admf_ext.'" class="vam" border="0" width="24" height="24" alt="'.adm_translate("Droits Modules").'" /> '.adm_translate("Droits Modules").' </legend>
         <div id="adm_droi_m" class="container-fluid collapse '.$ldvisi.' ">
            <div class="form-group"><a id="checkAllButton_m"><i class="fa fa-check-square-o fa-lg"></i></a>&nbsp;<a id="uncheckAllButton_m"><i class="fa fa-square-o fa-lg"></i></a></div>'."\n";
   echo $listdroitsmodulo;
   echo'
         </div>
         <br />
         <div class="form-group">
            <div class="col-sm-offset-4 col-sm-8">
               <button class="btn btn-primary" type="submit"><i class="fa fa-check-square fa-lg"></i>&nbsp;'.adm_translate("Actualiser l'administrateur").'</button>
               <input type="hidden" name="op" value="UpdateAuthor">
            </div>
         </div>
      </fieldset>
   </form>
   </div>';
   echo $scri_check;
   echo '
   <script type="text/javascript">
   //<![CDATA[
      inpandfieldlen("chng_name",50);
      inpandfieldlen("chng_email",60);
      inpandfieldlen("chng_url",60);
      inpandfieldlen("chng_pwd",12);
      inpandfieldlen("chng_pwd2",12);
   //]]>
   </script>';
   $fv_parametres = '
   chng_name: {
   },
   chng_email: {
   },
   chng_url: {
   },
   chng_pwd: {
      validators: {
         callback: {
            callback: function(value, validator, $field) {
               var score = 0;
               if (value === "") {
                  return {
                     valid: true,
                     score: null
                  };
               }
               // Check the password strength
               score += ((value.length >= 8) ? 1 : -1);
               // The password contains uppercase character
               if (/[A-Z]/.test(value)) {score += 1;}
               // The password contains uppercase character
               if (/[a-z]/.test(value)) {score += 1;}
               // The password contains number
               if (/[0-9]/.test(value)) {score += 1;}
               // The password contains special characters
               if (/[!#$%&^~*_]/.test(value)) {score += 1;}
               return {
               valid: true,
               score: score    // We will get the score later
               };
            }
         }
      }
   },
   chng_pwd2: {
      validators: {
          identical: {
              field: "chng_pwd",
              message: "The password and its confirm are not the same"
          }
      }
   },
   ';
   adminfoot('fv',$fv_parametres,'','');
}

function deletedroits($del_dr_aid) {
   global $NPDS_Prefix;
   $res=sql_query("DELETE FROM ".$NPDS_Prefix."droits WHERE d_aut_aid='$del_dr_aid'");
}

function updatedroits($chng_aid) {
   global $NPDS_Prefix;
   foreach ( $_POST as $y=>$w) {
      if(stristr("$y", 'ad_d_')) $res= sql_query("INSERT INTO ".$NPDS_Prefix."droits VALUES ('$chng_aid', '$w', 11111)");
   }
}
// la meme chose ?....
function addroits($add_aid) {
   global $NPDS_Prefix;
   foreach ( $_POST as $y=>$w) {
      if(stristr("$y", 'ad_d_')) $res= sql_query("INSERT INTO ".$NPDS_Prefix."droits VALUES ('$add_aid', '$w', 11111)");
   } 
}


function updateadmin($chng_aid, $chng_name, $chng_email, $chng_url, $chng_radminfilem, $chng_radminsuper, $chng_pwd, $chng_pwd2, $temp_system_md5) {
    global $NPDS_Prefix, $modu;

    if (!($chng_aid && $chng_name && $chng_email))
       Header("Location: admin.php?op=mod_authors");

//    list ($listdroits_mod,$modu,$listradminmodule) = modulesadmin($chng_aid);

    // Gestion du fichier pour filemanager
    $result=sql_query("select radminfilem,radminsuper from ".$NPDS_Prefix."authors where aid='$chng_aid'");
    list($ori_radminfilem, $ori_radminsuper) = sql_fetch_row($result);
    if ($ori_radminsuper and !$chng_radminsuper)
       @unlink("modules/f-manager/users/".strtolower($chng_aid).".conf.php");
    if (!$ori_radminsuper and $chng_radminsuper)
       @copy("modules/f-manager/users/modele.admin.conf.php","modules/f-manager/users/".strtolower($chng_aid).".conf.php");

    if ($ori_radminfilem and !$chng_radminfilem)
       @unlink("modules/f-manager/users/".strtolower($chng_aid).".conf.php");
    if (!$ori_radminfilem and $chng_radminfilem)
       @copy("modules/f-manager/users/modele.admin.conf.php","modules/f-manager/users/".strtolower($chng_aid).".conf.php");

    if ($chng_pwd2 != "") {
       if($chng_pwd != $chng_pwd2) {
          global $hlpfile;
          include("header.php");
          GraphicAdmin($hlpfile);
          echo error_handler(adm_translate("DÈsolÈ, les nouveaux Mots de Passe ne correspondent pas. Cliquez sur retour et recommencez")."<br />");
          include("footer.php");
          exit;
       }
       global $system_md5;
       if (($system_md5) or ($temp_system_md5)) {
          $chng_pwd=crypt($chng_pwd2,$chng_pwd);
       }
       if ($chng_radminsuper==1) {
          $result = sql_query("UPDATE ".$NPDS_Prefix."authors SET name='$chng_name', email='$chng_email', url='$chng_url', radminfilem='0', radminsuper='$chng_radminsuper', pwd='$chng_pwd' WHERE aid='$chng_aid'");
       } else {
          $result = sql_query("UPDATE ".$NPDS_Prefix."authors SET name='$chng_name', email='$chng_email', url='$chng_url', radminfilem='$chng_radminfilem', radminsuper='0', pwd='$chng_pwd' WHERE aid='$chng_aid'");
       }
    } else {
       if ($chng_radminsuper==1) {
          $result = sql_query("UPDATE ".$NPDS_Prefix."authors SET name='$chng_name', email='$chng_email', url='$chng_url', radminfilem='0', radminsuper='$chng_radminsuper' WHERE aid='$chng_aid'");
          deletedroits($chng_aid);
       } else {
          $result = sql_query("UPDATE ".$NPDS_Prefix."authors SET name='$chng_name', email='$chng_email', url='$chng_url', radminfilem='$chng_radminfilem', radminsuper='0' WHERE aid='$chng_aid'");
          deletedroits($chng_aid);
          updatedroits($chng_aid);
       }
    }
    global $aid; Ecr_Log("security", "ModifyAuthor($chng_name) by AID : $aid", "");
    Header("Location: admin.php?op=mod_authors");
}

function error_handler($ibid) {
   opentable();
   echo "<p class=\"errorhandler\" align=\"center\">".adm_translate("Merci d'entrer l'information en fonction des spÈcifications")."<br /><br />";
   echo "$ibid<br /><a href=\"admin.php?op=mod_authors\" class=\"noir\">".adm_translate("Retour en arriËre")."</a></p>";
   closetable();
}

switch ($op) {
   case "mod_authors":
        displayadmins();
        break;

   case "modifyadmin":
        modifyadmin($chng_aid);
        break;

   case "UpdateAuthor":
        updateadmin($chng_aid, $chng_name, $chng_email, $chng_url, $chng_radminfilem, $chng_radminsuper, $chng_pwd, $chng_pwd2, $temp_system_md5);
        break;

   case "AddAuthor":
        if (!($add_aid && $add_name && $add_email && $add_pwd)) {
           global $hlpfile;
           include("header.php");
           GraphicAdmin($hlpfile);
           echo error_handler(adm_translate("Vous devez remplir tous les Champs")."<br />");
           include("footer.php");
           return;
        }
        if ($system_md5) {
           $add_pwdX=crypt($add_pwd,$add_pwdX);
        }
        
        list ($listdroits_mod,$modu,$listradminmodule) = modulesadmin($add_aid);
        
        $result = sql_query("INSERT INTO ".$NPDS_Prefix."authors VALUES ('$add_aid', '$add_name', '$add_url', '$add_email', '$add_pwdX', '0','$add_radminfilem', '$add_radminsuper')");
        addroits($add_aid);

            
          //==> maj des droits admin modules
          $i=0;$upd='';
          foreach($modu as $k=>$v){
            if(!$_POST['chng_'.$v]) {
            $upd=str_replace ('|'.$add_aid,"",$listradminmodule[$i]);} else {
               if(strstr ( $listradminmodule[$i], '|'.$add_aid )) $upd=$listradminmodule[$i];
               else
               $upd=$listradminmodule[$i].'|'.$add_aid;
            } 
            sql_query("UPDATE ".$NPDS_Prefix."modules SET madmin='".$upd."' WHERE mnom='$v'");
            $i++;
          }
          //==> maj des droits admin modules


        // Copie du fichier pour filemanager
        if ($add_radminsuper or $add_radminfilem)
           @copy("modules/f-manager/users/modele.admin.conf.php","modules/f-manager/users/".strtolower($add_aid).".conf.php");

        global $aid; Ecr_Log("security", "AddAuthor($add_aid) by AID : $aid", "");
        Header("Location: admin.php?op=mod_authors");
        break;

   case "deladmin":
        global $hlpfile, $entete_au;
        include("header.php");
        GraphicAdmin($hlpfile);
        opentable();
        echo $entete_au;
        echo "<div class=\"header\" style=\"font-size:100%; padding:3px\";>\n";
        echo adm_translate("Effacer l'Administrateur")." : $del_aid";
        echo "</div>\n";
        echo "<p id=\"mes_delauthor\" align=\"center\"><span class=\"rouge\">".adm_translate("Etes-vous s˚r de vouloir effacer")." $del_aid ? </span><br /><br />";
        echo "[ <a href=\"admin.php?op=deladminconf&amp;del_aid=$del_aid\" class=\"rouge\">".adm_translate("Oui")."</a>&nbsp;|&nbsp;<a href=\"admin.php?op=mod_authors\" class=\"noir\">".adm_translate("Non")."</a> ]</p>\n</div>";
        
        closetable();
        include("footer.php");
        break;

   case "deladminconf":
        sql_query("delete from ".$NPDS_Prefix."authors where aid='$del_aid'");
        deletedroits($chng_aid=$del_aid);
        // Supression du fichier pour filemanager
        @unlink("modules/f-manager/users/".strtolower($del_aid).".conf.php");
        global $aid; Ecr_Log("security", "DeleteAuthor($del_aid) by AID : $aid", "");
        Header("Location: admin.php?op=mod_authors");
        break;
}
?>
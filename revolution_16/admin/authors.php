<?php
/************************************************************************/
/* DUNE by NPDS - admin prototype                                       */
/* ===========================                                          */
/*                                                                      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

if (!function_exists('admindroits'))
   include('die.php');
$f_meta_nom ='mod_authors';
$f_titre = adm_translate("Administrateurs");
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
if($radminsuper!=1) Access_Error();

global $language, $adminimg, $admf_ext;
$listdroits='';$listdroitsmodulo='';
$hlpfile = "manuels/$language/authors.html";

// sélection des fonctions sauf les fonctions de type alerte 
   $R = sql_query("SELECT fid, fnom, fnom_affich, fcategorie FROM ".$NPDS_Prefix."fonctions f WHERE f.finterface =1 AND fcategorie < 7 ORDER BY f.fcategorie");
   while(list($fid, $fnom, $fnom_affich, $fcategorie) = sql_fetch_row($R)) {
      if($fcategorie==6) {
         $listdroitsmodulo .= '
         <div class="col-md-4 col-sm-6">
            <div class="form-check">
               <input class="ckbm form-check-input" id="ad_d_m_'.$fnom.'" type="checkbox" name="ad_d_m_'.$fnom.'" value="'.$fid.'" />
               <label class="form-check-label" for="ad_d_m_'.$fnom.'">'.$fnom_affich.'</label>
            </div>
         </div>';
      } else {
         if ($fid!=12)
         $listdroits .='
         <div class="col-md-4 col-sm-6">
            <div class="form-check">
               <input class="ckbf form-check-input" id="ad_d_'.$fid.'" type="checkbox" name="ad_d_'.$fid.'" value="'.$fid.'" />
               <label class="form-check-label" for="ad_d_'.$fid.'">'.adm_translate($fnom_affich).'</label>
            </div>
         </div>';
      }
   }

$scri_check ='
<script type="text/javascript">
   //<![CDATA[
   $(function () {
      check = $("#cb_radminsuper").is(":checked");
      if(check) {
         $("#adm_droi_f, #adm_droi_m").addClass("collapse");
      }
   });
   $("#cb_radminsuper").on("click", function(){
      check = $("#cb_radminsuper").is(":checked");
      if(check) {
         $("#adm_droi_f, #adm_droi_m").toggleClass("collapse","collapse show");
         $(".ckbf, .ckbm, #ckball_f, #ckball_m").prop("checked", false);
      } else {
         $("#adm_droi_f, #adm_droi_m").toggleClass("collapse","collapse show");
      }
   }); 
   $(document).ready(function(){ 
      $("#ckball_f").change(function(){
         check_a_f = $("#ckball_f").is(":checked");
         if(check_a_f) {
            $("#ckb_status_f").text("'.html_entity_decode(adm_translate("Tout décocher"),ENT_COMPAT | ENT_HTML401,'UTF-8').'");
         } else {
            $("#ckb_status_f").text("'.html_entity_decode(adm_translate("Tout cocher"),ENT_COMPAT | ENT_HTML401,'UTF-8').'");
         }
         $(".ckbf").prop("checked", $(this).prop("checked"));
      });
      
      $("#ckball_m").change(function(){
         check_a_m = $("#ckball_m").is(":checked");
         if(check_a_m) {
            $("#ckb_status_m").text("'.html_entity_decode(adm_translate("Tout décocher"),ENT_COMPAT | ENT_HTML401,'UTF-8').'");
         } else {
            $("#ckb_status_m").text("'.html_entity_decode(adm_translate("Tout cocher"),ENT_COMPAT | ENT_HTML401,'UTF-8').'");
         }
         $(".ckbm").prop("checked", $(this).prop("checked"));
      });
   });
   //]]>
</script>';

function displayadmins() {
   global $hlpfile, $NPDS_Prefix, $admf_ext, $fieldnames, $listdroits, $listdroitsmodulo, $f_meta_nom, $f_titre, $adminimg, $scri_check;
   include("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   $result = sql_query("SELECT aid, name, url, email, radminsuper FROM ".$NPDS_Prefix."authors");
   echo '
   <hr />
   <h3>'.adm_translate("Les administrateurs").'</h3>
   <table id="tab_adm" data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-buttons-class="outline-secondary" data-show-export="true" data-icons="icons" data-icons-prefix="fa">
      <thead>
         <tr>
            <th data-sortable="true" data-halign="center">'.adm_translate('Nom').'</th>
            <th data-sortable="true" data-halign="center">'.adm_translate('E-mail').'</th>
            <th data-halign="center" data-align="right">'.adm_translate('Fonctions').'</th>
         </tr>
      </thead>
      <tbody>';
   while(list($a_aid, $name, $url, $email, $supadm) = sql_fetch_row($result)) {
      if ($supadm==1) echo'
         <tr class="table-danger">'; else echo'
         <tr>';
      echo '
            <td>'.$a_aid.'</td>
            <td>'.$email.'</td>
            <td align="right" nowrap="nowrap">
               <a href="admin.php?op=modifyadmin&amp;chng_aid='.$a_aid.'" class=""><i class="fa fa-edit fa-lg" title="'.adm_translate("Modifier l'information").'" data-bs-toggle="tooltip"></i></a>&nbsp;
               <a href="mailto:'.$email.'"><i class="fa fa-at fa-lg" title="'.adm_translate("Envoyer un courriel à").' '.$a_aid.'" data-bs-toggle="tooltip"></i></a>&nbsp;';
      if($url!='')
         echo'
               <a href="'.$url.'"><i class="fas fa-external-link-alt fa-lg" title="'.adm_translate("Visiter le site web").'" data-bs-toggle="tooltip"></i></a>&nbsp;';
         echo '
               <a href="admin.php?op=deladmin&amp;del_aid='.$a_aid.'" ><i class="fas fa-trash fa-lg text-danger" title="'.adm_translate("Effacer l'Auteur").'" data-bs-toggle="tooltip" ></i></a>
            </td>
         </tr>';
   }
   echo '
      </tbody>
   </table>
   <hr />
   <h3>'.adm_translate("Nouvel administrateur").'</h3>
   <form id="nou_adm" action="admin.php" method="post">
      <fieldset>
         <legend><img src="'.$adminimg.'authors.'.$admf_ext.'" class="vam" border="0" width="24" height="24" alt="'.adm_translate("Informations").'" /> '.adm_translate("Informations").' </legend>
         <div class="form-floating mb-3 mt-3">
            <input id="add_aid" class="form-control" type="text" name="add_aid" maxlength="30" placeholder="'.adm_translate("Surnom").'" required="required" />
            <label for="add_aid">'.adm_translate("Surnom").' <span class="text-danger">*</span></label>
            <span class="help-block text-end"><span id="countcar_add_aid"></span></span>
         </div>
         <div class="form-floating mb-3">
            <input id="add_name" class="form-control" type="text" name="add_name" maxlength="50" placeholder="'.adm_translate("Nom").'" required="required" />
            <label for="add_name">'.adm_translate("Nom").' <span class="text-danger">*</span></label>
            <span class="help-block text-end"><span id="countcar_add_name"></span></span>
         </div>
         <div class="form-floating mb-3">
            <input id="add_email" class="form-control" type="email" name="add_email" maxlength="254" placeholder="'.adm_translate("E-mail").'" required="required" />
            <label for="add_email">'.adm_translate("E-mail").' <span class="text-danger">*</span></label>
            <span class="help-block text-end"><span id="countcar_add_email"></span></span>
         </div>
         <div class="form-floating mb-3">
            <input id="add_url" class="form-control" type="url" name="add_url" maxlength="320" placeholder="'.adm_translate("URL").'" />
            <label for="add_url">'.adm_translate("URL").'</label>
            <span class="help-block text-end"><span id="countcar_add_url"></span></span>
         </div>
         <div class="form-floating mb-3">
            <input id="add_pwd" class="form-control" type="password" name="add_pwd" maxlength="20" placeholder="'.adm_translate("Mot de Passe").'" required="required" />
            <label for="add_pwd">'.adm_translate("Mot de Passe").' <span class="text-danger">*</span></label>
            <span class="help-block text-end" id="countcar_add_pwd"></span>
            <div class="progress mt-2" style="height: 0.4rem;">
               <div id="passwordMeter_cont" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
            </div>
         </div>
         <div class="mb-3">
            <div class="form-check">
               <input id="cb_radminsuper" class="form-check-input" type="checkbox" name="add_radminsuper" value="1" />
               <label class="form-check-label text-danger" for="cb_radminsuper">'.adm_translate("Super administrateur").'</label>
            </div>
            <span class="help-block">'.adm_translate("Si Super administrateur est coché, cet administrateur aura TOUS les droits.").'</span>
         </div>
      </fieldset>
      <fieldset>
         <legend><img src="'.$adminimg.'authors.'.$admf_ext.'" class="vam" border="0" width="24" height="24" alt="'.adm_translate("Droits").'" /> '.adm_translate("Droits").' </legend>
         <div id="adm_droi_f" class="container-fluid ">
            <div class="mb-3">
               <input type="checkbox" id="ckball_f" />&nbsp;<span class="small text-body-secondary" id="ckb_status_f">'.adm_translate("Tout cocher").'</span>
            </div>
            <div class="row">
               '.$listdroits.'
            </div>
         </div>
      </fieldset>
      <fieldset>
         <legend><img src="'.$adminimg.'authors.'.$admf_ext.'" class="vam" border="0" width="24" height="24" alt="'.adm_translate("Droits modules").'" /> '.adm_translate("Droits modules").' </legend>
         <div id="adm_droi_m" class="container-fluid">
            <div class="mb-3">
               <input type="checkbox" id="ckball_m" />&nbsp;<span class="small text-body-secondary" id="ckb_status_m">'.adm_translate("Tout cocher").'</span>
            </div>
            <div class="row">
            '.$listdroitsmodulo.'
            </div>
         </div>
         <button class="btn btn-primary my-3" type="submit"><i class="fa fa-plus-square fa-lg me-2"></i>'.adm_translate("Ajouter un administrateur").'</button>
         </div>
         <input type="hidden" name="op" value="AddAuthor" />
      </fieldset>
   </form>
   '.$scri_check;
   $arg1 ='
      var formulid = ["nou_adm"];
      '.auto_complete ('admin', 'aid', 'authors', '', '0').'
      '.auto_complete ('adminname', 'name', 'authors', '', '0').'
      inpandfieldlen("add_aid",30);
      inpandfieldlen("add_name",50);
      inpandfieldlen("add_email",254);
      inpandfieldlen("add_url",320);
      inpandfieldlen("add_pwd",20);
      ';
   $fv_parametres = '
   add_aid: {
      validators: {
         callback: {
            message: "'.translate("Ce surnom n\'est pas disponible").'",
            callback: function(input) {
               if($.inArray(btoa(input.value), admin) !== -1)
                  return false;
               else
                  return true;
            }
         }
      }
   },
   add_name: {
      validators: {
         callback: {
            message: "'.translate("Ce nom n\'est pas disponible").'",
            callback: function(input) {
               if($.inArray(btoa(input.value), adminname) !== -1)
                  return false;
               else
                  return true;
            }
         }
      }
   },
   add_pwd: {
      validators: {
         checkPassword: {},
      }
   },';
   adminfoot('fv',$fv_parametres,$arg1,'');
}

function modifyadmin($chng_aid) {
   global $hlpfile, $aid, $NPDS_Prefix, $admf_ext, $f_meta_nom, $f_titre, $adminimg, $scri_check, $fv_parametres;
   include("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '
   <hr />
   <h3>'.adm_translate("Actualiser l'administrateur").' : <span class="text-body-secondary">'.$chng_aid.'</span></h3>';

   $result = sql_query("SELECT aid, name, url, email, pwd, radminsuper FROM ".$NPDS_Prefix."authors WHERE aid='$chng_aid'");
   list($chng_aid, $chng_name, $chng_url, $chng_email, $chng_pwd, $chng_radminsuper) = sql_fetch_row($result);
   $supadm_inp = $chng_radminsuper==1? ' checked="checked"':'';
   //==> construction des check-box des droits
   $listdroits ='';$listdroitsmodulo='';
   $result3 = sql_query("SELECT * FROM ".$NPDS_Prefix."droits WHERE d_aut_aid ='$chng_aid'");
   $datas=array();
   while ($data = sql_fetch_row($result3)) {
      $datas[] = $data[1];
   }
   $R = sql_query("SELECT fid, fnom, fnom_affich, fcategorie FROM ".$NPDS_Prefix."fonctions f WHERE f.finterface =1 AND fcategorie < 7 ORDER BY f.fcategorie");
   while(list($fid, $fnom, $fnom_affich, $fcategorie) = sql_fetch_row($R)) {
      $chec = in_array($fid, $datas) ? 'checked="checked"' : '';
      if($fcategorie==6) {
         $listdroitsmodulo .='
         <div class="col-md-4 col-sm-6">
            <div class="form-check">
               <input class="ckbm form-check-input" id="ad_d_m_'.$fnom.'" type="checkbox" '.$chec.' name="ad_d_m_'.$fnom.'" value="'.$fid.'" />
               <label class="form-check-label" for="ad_d_m_'.$fnom.'">'.$fnom_affich.'</label>
            </div>
         </div>';
      }
      else { 
         if ($fid!=12)
         $listdroits .='
         <div class="col-md-4 col-sm-6">
            <div class="form-check">
               <input class="ckbf form-check-input" id="ad_d_'.$fid.'" type="checkbox" '.$chec.' name="ad_d_'.$fid.'" value="'.$fid.'" />
               <label class="form-check-label" for="ad_d_'.$fid.'">'.adm_translate($fnom_affich).'</label>
            </div>
         </div>';
      }
   } 
   //<== construction des check-box des droits
   echo '
   <form id="mod_adm" class="" action="admin.php" method="post">
      <fieldset>
         <legend><img src="'.$adminimg.'authors.'.$admf_ext.'" class="vam" border="0" width="24" height="24" alt="'.adm_translate("Informations").'" title="'.$chng_aid.'" /> '.adm_translate("Informations").'</legend>
         <div class="form-floating mb-3 mt-3">
            <input id="chng_name" class="form-control" type="text" name="chng_name" value="'.$chng_name.'" maxlength="30" placeholder="'.adm_translate("Nom").'" required="required" />
            <label for="chng_name">'.adm_translate("Nom").' <span class="text-danger">*</span></label>
            <span class="help-block text-end"><span id="countcar_chng_name"></span></span>
         </div>
         <div class="form-floating mb-3">
            <input id="chng_email" class="form-control" type="text" name="chng_email" value="'.$chng_email.'" maxlength="254" placeholder="'.adm_translate("E-mail").'" required="required" />
            <label for="chng_email">'.adm_translate("E-mail").' <span class="text-danger">*</span></label>
            <span class="help-block text-end"><span id="countcar_chng_email"></span></span>
         </div>
         <div class="form-floating mb-3">
            <input id="chng_url" class="form-control" type="url" name="chng_url" value="'.$chng_url.'" maxlength="320" placeholder="'.adm_translate("URL").'" />
            <label for="chng_url">'.adm_translate("URL").'</label>
            <span class="help-block text-end"><span id="countcar_chng_url"></span></span>
         </div>
         <div class="form-floating mb-3">
            <input id="chng_pwd" class="form-control" type="password" name="chng_pwd" maxlength="20" placeholder="'.adm_translate("Mot de Passe").'" title="'.adm_translate("Entrez votre nouveau Mot de Passe").'" />
            <label for="chng_pwd">'.adm_translate("Mot de Passe").' <span class="text-danger">*</span></label>
            <span class="help-block text-end" id="countcar_chng_pwd"></span>
            <div class="progress" style="height: 0.4rem;">
               <div id="passwordMeter_cont" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
            </div>
         </div>
         <div class="form-floating mb-3">
            <input id="chng_pwd2" class="form-control" type="password" name="chng_pwd2" maxlength="20" placeholder="'.adm_translate("Mot de Passe").'" title="'.adm_translate("Entrez votre nouveau Mot de Passe").'" />
            <label for="chng_pwd2">'.adm_translate("Mot de Passe").' <span class="text-danger">*</span></label>
            <span class="help-block text-end"><span id="countcar_chng_pwd2"></span></span>
         </div>
         <div class="mb-3">
            <div class="form-check">
               <input id="cb_radminsuper" class="form-check-input" type="checkbox" name="chng_radminsuper" value="1" '.$supadm_inp.' />
               <label class="form-check-label text-danger" for="cb_radminsuper">'.adm_translate("Super administrateur").'</label>
            </div>
            <span class="help-block">'.adm_translate("Si Super administrateur est coché, cet administrateur aura TOUS les droits.").'</span>
         </div>
         <input type="hidden" name="chng_aid" value="'.$chng_aid.'" />
      </fieldset>
      <fieldset>
         <legend><img src="'.$adminimg.'authors.'.$admf_ext.'" class="vam" border="0" width="24" height="24" alt="'.adm_translate("Droits").'" /> '.adm_translate("Droits").' </legend>
         <div id="adm_droi_f" class="container-fluid ">
            <div class="mb-3">
               <input type="checkbox" id="ckball_f" />&nbsp;<span class="small text-body-secondary" id="ckb_status_f">'.adm_translate("Tout cocher").'</span>
            </div>
            <div class="row">
            '.$listdroits.'
            </div>
        </div>
      </fieldset>
      <fieldset>
         <legend><img src="'.$adminimg.'authors.'.$admf_ext.'" class="vam" border="0" width="24" height="24" alt="'.adm_translate("Droits modules").'" /> '.adm_translate("Droits modules").' </legend>
         <div id="adm_droi_m" class="container-fluid ">
            <div class="mb-3">
               <input type="checkbox" id="ckball_m" />&nbsp;<span class="small text-body-secondary" id="ckb_status_m">'.adm_translate("Tout cocher").'</span>
            </div>
            <div class="row">
               '.$listdroitsmodulo.'
            </div>
         </div>
         <input type="hidden" name="old_pwd" value="'.$chng_pwd.'" />
         <input type="hidden" name="op" value="UpdateAuthor" />
         <button class="btn btn-primary my-3" type="submit"><i class="fa fa-check fa-lg me-2"></i>'.adm_translate("Actualiser l'administrateur").'</button>
      </fieldset>
   </form>';
   echo $scri_check;
$arg1 ='
      var formulid = ["mod_adm"]
         inpandfieldlen("chng_name",50);
         inpandfieldlen("chng_email",254);
         inpandfieldlen("chng_url",320);
         inpandfieldlen("chng_pwd",20);
         inpandfieldlen("chng_pwd2",20);
      ';
   $fv_parametres = '
   chng_pwd: {
      validators: {
         checkPassword: {},
      }
   },
   chng_pwd2: {
      validators: {
         identical: {
            compare: function() {
               return mod_adm.querySelector(\'[name="chng_pwd"]\').value;
            },
         }
      }
   },
   !###!
   mod_adm.querySelector(\'[name="chng_pwd"]\').addEventListener("input", function() {
      fvitem.revalidateField("chng_pwd2");
   });';
   adminfoot('fv',$fv_parametres,$arg1,'');
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

function updateadmin($chng_aid, $chng_name, $chng_email, $chng_url, $chng_radminsuper, $chng_pwd, $chng_pwd2, $ad_d_27, $old_pwd) {
   global $NPDS_Prefix;

   if (!($chng_aid && $chng_name && $chng_email))
      Header("Location: admin.php?op=mod_authors");
   include_once('functions.php');
   if(checkdnsmail($chng_email) === false) {
      global $hlpfile;
      include("header.php");
      GraphicAdmin($hlpfile);
      echo error_handler(adm_translate("ERREUR : DNS ou serveur de mail incorrect").'<br />');
      include("footer.php");
      return;
   }

   $result=sql_query("SELECT radminsuper FROM ".$NPDS_Prefix."authors WHERE aid='$chng_aid'");
   list($ori_radminsuper) = sql_fetch_row($result);
   if (!$ori_radminsuper and $chng_radminsuper) {
      @copy("modules/f-manager/users/modele.admin.conf.php","modules/f-manager/users/".strtolower($chng_aid).".conf.php");
      deletedroits($chng_aid);
   }
   if ($ori_radminsuper and !$chng_radminsuper) {
      @unlink("modules/f-manager/users/".strtolower($chng_aid).".conf.php");
      updatedroits($chng_aid);
   }
   if(file_exists("modules/f-manager/users/".strtolower($chng_aid).".conf.php") and $ad_d_27!='27')
      @unlink("modules/f-manager/users/".strtolower($chng_aid).".conf.php");
   if(($chng_radminsuper or $ad_d_27 !='') and !file_exists("modules/f-manager/users/".strtolower($chng_aid).".conf.php")) {
      @copy("modules/f-manager/users/modele.admin.conf.php","modules/f-manager/users/".strtolower($chng_aid).".conf.php");
   }

   if ($chng_pwd2 != '') {
      if($chng_pwd != $chng_pwd2) {
         global $hlpfile;
         include("header.php");
         GraphicAdmin($hlpfile);
         echo error_handler(adm_translate("Désolé, les nouveaux Mots de Passe ne correspondent pas. Cliquez sur retour et recommencez").'<br />');
         include("footer.php");
         exit;
      }

      $AlgoCrypt = PASSWORD_BCRYPT;
      $min_ms = 100;
      $options = ['cost' => getOptimalBcryptCostParameter($chng_pwd, $AlgoCrypt, $min_ms)];
      $hashpass = password_hash($chng_pwd, $AlgoCrypt, $options);
      $chng_pwd=crypt($chng_pwd, $hashpass);

      if ($old_pwd) {
         global $admin, $admin_cook_duration;
         $Xadmin = base64_decode($admin);
         $Xadmin = explode(':', $Xadmin);
         $aid = urlencode($Xadmin[0]);
         $AIpwd = $Xadmin[1];
         if ($aid == $chng_aid) {
            if (md5($old_pwd) == $AIpwd and $chng_pwd != '') {
               $admin = base64_encode("$aid:".md5($chng_pwd));
               if ($admin_cook_duration<=0) 
                  $admin_cook_duration=1;
               $timeX=time()+(3600*$admin_cook_duration);
               setcookie('admin',$admin,$timeX);
               setcookie('adm_exp',$timeX,$timeX);
            }
         }
      }
      $result = $chng_radminsuper==1 ?
         sql_query("UPDATE ".$NPDS_Prefix."authors SET name='$chng_name', email='$chng_email', url='$chng_url', radminsuper='$chng_radminsuper', pwd='$chng_pwd', hashkey='1' WHERE aid='$chng_aid'"):
         sql_query("UPDATE ".$NPDS_Prefix."authors SET name='$chng_name', email='$chng_email', url='$chng_url', radminsuper='0', pwd='$chng_pwd', hashkey='1' WHERE aid='$chng_aid'");
   } else {
      if ($chng_radminsuper==1) {
         $result = sql_query("UPDATE ".$NPDS_Prefix."authors SET name='$chng_name', email='$chng_email', url='$chng_url', radminsuper='$chng_radminsuper' WHERE aid='$chng_aid'");
         deletedroits($chng_aid);
      } else {
         $result = sql_query("UPDATE ".$NPDS_Prefix."authors SET name='$chng_name', email='$chng_email', url='$chng_url', radminsuper='0' WHERE aid='$chng_aid'");
         deletedroits($chng_aid);
         updatedroits($chng_aid);
      }
   }
   global $aid; Ecr_Log('security', "ModifyAuthor($chng_name) by AID : $aid", '');
   Header("Location: admin.php?op=mod_authors");
}

function error_handler($ibid) {
   echo '
   <div class="alert alert-danger mb-3">
   '.adm_translate("Merci d'entrer l'information en fonction des spécifications").'<br />'.$ibid.'
   </div>
   <a class="btn btn-outline-secondary" href="admin.php?op=mod_authors" >'.adm_translate("Retour en arrière").'</a>';
}

switch ($op) {
   case 'mod_authors':
      displayadmins();
   break;
   case 'modifyadmin':
      modifyadmin($chng_aid);
   break;
   case 'UpdateAuthor':
      settype( $chng_radminsuper,'int');
      settype( $ad_d_27,'int');
      updateadmin($chng_aid, $chng_name, $chng_email, $chng_url, $chng_radminsuper, $chng_pwd, $chng_pwd2, $ad_d_27, $old_pwd);
   break;
   case 'AddAuthor':
         settype( $add_radminsuper,'int');

      if (!($add_aid && $add_name && $add_email && $add_pwd)) {
         global $hlpfile;
         include("header.php");
         GraphicAdmin($hlpfile);
         echo error_handler(adm_translate("Vous devez remplir tous les Champs").'<br />');
         include("footer.php");
         return;
      }
      include_once('functions.php');
      if(checkdnsmail($add_email) === false) {
         global $hlpfile;
         include("header.php");
         GraphicAdmin($hlpfile);
         echo error_handler(adm_translate("ERREUR : DNS ou serveur de mail incorrect").'<br />');
         include("footer.php");
         return;
      }

      $AlgoCrypt = PASSWORD_BCRYPT;
      $min_ms = 100;
      $options = ['cost' => getOptimalBcryptCostParameter($add_pwd, $AlgoCrypt, $min_ms)];
      $hashpass = password_hash($add_pwd, $AlgoCrypt, $options);
      $add_pwdX=crypt($add_pwd, $hashpass);

      $result = sql_query("INSERT INTO ".$NPDS_Prefix."authors VALUES ('$add_aid', '$add_name', '$add_url', '$add_email', '$add_pwdX', '1', '0', '$add_radminsuper')");
      updatedroits($add_aid);
      // Copie du fichier pour filemanager
      if ($add_radminsuper or isset($ad_d_27)) // $ad_d_27 pas là ?
         @copy("modules/f-manager/users/modele.admin.conf.php","modules/f-manager/users/".strtolower($add_aid).".conf.php");
      global $aid; Ecr_Log('security', "AddAuthor($add_aid) by AID : $aid", '');
      Header("Location: admin.php?op=mod_authors");
   break;
   case 'deladmin':
      global $hlpfile;
      include("header.php");
      GraphicAdmin($hlpfile);
      adminhead ($f_meta_nom, $f_titre, $adminimg);
      echo '
      <hr />
      <h3>'.adm_translate("Effacer l'Administrateur").' : <span class="text-body-secondary">'.$del_aid.'</span></h3>
      <div class="alert alert-danger">
         <p><strong>'.adm_translate("Etes-vous sûr de vouloir effacer").' '.$del_aid.' ? </strong></p>
         <a href="admin.php?op=deladminconf&amp;del_aid='.$del_aid.'" class="btn btn-danger btn-sm">'.adm_translate("Oui").'</a>&nbsp;<a href="admin.php?op=mod_authors" class="btn btn-secondary btn-sm">'.adm_translate("Non").'</a>
      </div>';
      adminfoot('','','','');
   break;
   case 'deladminconf':
      sql_query("DELETE FROM ".$NPDS_Prefix."authors WHERE aid='$del_aid'");
      deletedroits($chng_aid=$del_aid);
      sql_query("DELETE FROM ".$NPDS_Prefix."publisujet WHERE aid='$del_aid'");
      // Supression du fichier pour filemanager
      @unlink("modules/f-manager/users/".strtolower($del_aid).".conf.php");
      global $aid; Ecr_Log('security', "DeleteAuthor($del_aid) by AID : $aid", '');
      Header("Location: admin.php?op=mod_authors");
   break;
}
?>
<?php
/************************************************************************/
/* DUNE by NPDS - admin prototype                                       */
/* ===========================                                          */
/*                                                                      */
/* Manage the EDITO (static/edito.txt) of your web site                 */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
if (!function_exists('admindroits'))
   include('die.php');
$f_meta_nom ='edito';
$f_titre = adm_translate("Edito");
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
global $language;
$hlpfile = "manuels/$language/edito.html";

function edito($edito_type, $contents, $Xaff_jours, $Xaff_jour, $Xaff_nuit) {
   global $hlpfile, $language, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '<hr />';
   if ($contents=='') {
      echo '
      <form id="fad_edi_choix" action="admin.php?op=Edito_load" method="post">
         <fieldset>
            <legend>'.adm_translate("Type d'éditorial").'</legend>
            <div class="mb-3">
               <select class="form-select" name="edito_type" onchange="submit()">
                  <option value="0">'.adm_translate("Modifier l'Editorial").' ...</option>
                  <option value="G">'.adm_translate("Anonyme").'</option>
                  <option value="M">'.adm_translate("Membre").'</option>
               </select>
            </div>
         </fieldset>
      </form>';
   adminfoot('','','','');

   } else {
      if ($edito_type=='G')
         $edito_typeL=' '.adm_translate("Anonyme");
      elseif ($edito_type=='M')
         $edito_typeL=' '.adm_translate("Membre");;
      if (strpos($contents,'[/jour]')>0) {
         $contentJ=substr($contents,strpos($contents,'[jour]')+6,strpos($contents,'[/jour]')-6);
         $contentN=substr($contents,strpos($contents,'[nuit]')+6,strpos($contents,'[/nuit]')-19-strlen($contentJ));
      }
      if (!$contentJ and !$contentN and !strpos($contents,'[/jour]')) $contentJ=$contents;
      echo '
      <form id="admineditomod" action="admin.php" method="post" name="adminForm">
         <fieldset>
         <legend>'.adm_translate("Edito").' :'.$edito_typeL.'</legend>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-12" for="XeditoJ">'.adm_translate("Le jour").'</label>
            <div class="col-sm-12">
               <textarea class="tin form-control" name="XeditoJ" rows="20" >';
      echo htmlspecialchars($contentJ,ENT_COMPAT|ENT_SUBSTITUTE|ENT_HTML401,'UTF-8');
      echo '</textarea>
            </div>
         </div>';
      echo aff_editeur('XeditoJ','');
      echo '
         <div class="mb-3 row">
            <label class="col-form-label col-sm-12" for="XeditoN">'.adm_translate("La nuit").'</label>';
      echo aff_editeur('XeditoN','');
      echo '
            <div class="col-sm-12">
               <textarea class="tin form-control" name="XeditoN" rows="20">';
      echo htmlspecialchars($contentN,ENT_COMPAT|ENT_SUBSTITUTE|ENT_HTML401,'UTF-8');
      echo '</textarea>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-sm-4 col-form-label" for="aff_jours">'.adm_translate("Afficher pendant").'</label>
            <div class="col-sm-8">
               <div class="input-group">
                  <span class="input-group-text">'.adm_translate("jour(s)").'</span>
                  <input class="form-control" type="number" name="aff_jours" id="aff_jours" min="0" step="1" max="999" value="'.$Xaff_jours.'" data-fv-digits="true" required="required" />
               </div>
            </div>
         </div>
         <div class="mb-3 row">
            <div class="col-sm-8 ms-sm-auto">
               <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="aff_jour" name="aff_jour" value="checked" '.$Xaff_jour.' />
                  <label class="form-check-label" for="aff_jour">'.adm_translate("Le jour").'</label>
               </div>
               <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="aff_nuit" name="aff_nuit" value="checked" '.$Xaff_nuit.' />
                  <label class="form-check-label" for="aff_nuit">'.adm_translate("La nuit").'</label>
               </div>
            </div>
         </div>

      <input type="hidden" name="op" value="Edito_save" />
      <input type="hidden" name="edito_type" value="'.$edito_type.'" />
      <div class="mb-3 row">
         <div class="col-sm-8 ms-sm-auto ">
            <button class="btn btn-primary col-12" type="submit" name="edito_confirm"><i class="fa fa-check fa-lg"></i>&nbsp;'.adm_translate("Sauver les modifications").' </button>
         </div>
      </div>
      <div class="mb-3 row">
         <div class="col-sm-8 ms-sm-auto ">
            <a href="admin.php?op=Edito" class="btn btn-secondary col-12">'.adm_translate("Abandonner").'</a>
         </div>
      </div>
      </fieldset>
      </form>';
   $arg1='
   var formulid = ["admineditomod"];
   ';
   $fv_parametres='
      aff_jours: {
      validators: {
         digits: {
            message: "This must be a number"
         }
      }
   },
   ';
   adminfoot('fv',$fv_parametres,$arg1,'');
   }
}

function edito_mod_save($edito_type, $XeditoJ, $XeditoN, $aff_jours, $aff_jour, $aff_nuit) {
   if ($aff_jours<=0) {$aff_jours='999';}
   if ($edito_type=='G') {
      $fp=fopen("static/edito.txt","w");
      fputs($fp,"[jour]".str_replace('&quot;','"',stripslashes($XeditoJ)).'[/jour][nuit]'.str_replace('&quot;','"',stripslashes($XeditoN)).'[/nuit]');
      fputs($fp,'aff_jours='.$aff_jours);
      fputs($fp,'&aff_jour='.$aff_jour);
      fputs($fp,'&aff_nuit='.$aff_nuit);
      fputs($fp,'&aff_date='.time());
      fclose($fp);
   }  elseif ($edito_type=='M') {
      $fp=fopen('static/edito_membres.txt','w');
      fputs($fp,'[jour]'.str_replace('&quot;','"',stripslashes($XeditoJ)).'[/jour][nuit]'.str_replace('&quot;','"',stripslashes($XeditoN)).'[/nuit]');
      fputs($fp,'aff_jours='.$aff_jours);
      fputs($fp,'&aff_jour='.$aff_jour);
      fputs($fp,'&aff_nuit='.$aff_nuit);
      fputs($fp,'&aff_date='.time());
      fclose($fp);
   }
   global $aid; Ecr_Log('security', "editoSave () by AID : $aid", '');

   redirect_url('admin.php?op=Edito');
}

switch ($op) {
   case 'Edito_save':
      edito_mod_save($edito_type, $XeditoJ, $XeditoN, $aff_jours, $aff_jour, $aff_nuit);
   break;
   case 'Edito_load':
      if ($edito_type=='G') {
         if (file_exists('static/edito.txt')) {
            $fp=fopen('static/edito.txt','r');
            if (filesize('static/edito.txt')>0)
               $Xcontents=fread($fp,filesize('static/edito.txt'));
            fclose($fp);
         }
      } elseif ($edito_type=='M') {
         if (file_exists('static/edito_membres.txt')) {
            $fp=fopen('static/edito_membres.txt','r');
            if (filesize('static/edito_membres.txt')>0)
               $Xcontents=fread($fp,filesize('static/edito_membres.txt'));
            fclose($fp);
         }
      }
      $Xcontents=preg_replace('#<!--|/-->#', '', $Xcontents);
      if ($Xcontents=='')
         $Xcontents='Edito ...';
      else {
         $ibid=strstr($Xcontents,'aff_jours');
         parse_str($ibid,$Xibidout);
      }
      if ($Xibidout['aff_jours'])
         $Xcontents=substr($Xcontents,0,strpos($Xcontents,'aff_jours'));
      else {
         $Xibidout['aff_jours']=20;
         $Xibidout['aff_jour']='checked="checked"';
         $Xibidout['aff_nuit']='checked="checked"';
      }
      edito($edito_type, $Xcontents, $Xibidout['aff_jours'], $Xibidout['aff_jour'], $Xibidout['aff_nuit']);
   break;
   default:
     edito('','','','','');
   break;
}
?>
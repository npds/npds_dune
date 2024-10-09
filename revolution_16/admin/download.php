<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2024 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

if (!function_exists('admindroits'))
   include('die.php');
$f_meta_nom ='DownloadAdmin';
$f_titre = adm_translate('Téléchargements');
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
include("lib/file.class.php");
global $language;
$hlpfile = "manuels/$language/downloads.html";

function groupe($groupe) {
   $les_groupes=explode(',',$groupe);
   $mX=liste_group();
   $nbg=0; $str='';
   foreach($mX as $groupe_id => $groupe_name) {
      $selectionne=0;
      if ($les_groupes) {
         foreach ($les_groupes as $groupevalue) {
            if (($groupe_id==$groupevalue) and ($groupe_id!=0)) $selectionne=1;
         }
      }
      $str.= ($selectionne==1) ?
         '<option value="'.$groupe_id.'" selected="selected">'.$groupe_name.'</option>' :
         '<option value="'.$groupe_id.'">'.$groupe_name.'</option>' ;
      $nbg++;
   }
   if ($nbg>5) $nbg=5;
   // si on veux traiter groupe multiple multiple="multiple"  et name="Mprivs"
   return ('
   <select multiple="multiple" class="form-select" id="mpri" name="Mprivs[]" size="'.$nbg.'">
   '.$str.'
   </select>');
}

function droits($member) {
   echo '
   <div class="mb-3">
      <div class="form-check form-check-inline">';
   $checked = ($member==-127) ? ' checked="checked"' : '' ;
   echo '
         <input type="radio" id="adm" name="privs" class="form-check-input" value="-127" '.$checked.' />
         <label class="form-check-label" for="adm">'.adm_translate("Administrateurs").'</label>
      </div>
      <div class="form-check form-check-inline">';
   $checked = ($member==-1) ? ' checked="checked"' : '' ;
   echo '
         <input type="radio" id="ano" name="privs" class="form-check-input" value="-1" '.$checked.' />
         <label class="form-check-label" for="ano">'.adm_translate("Anonymes").'</label>
      </div>';
   echo '
      <div class="form-check form-check-inline">';
   if ($member>0) {
      echo '
         <input type="radio" id="mem" name="privs" value="1" class="form-check-input" checked="checked" />
         <label class="form-check-label" for="mem">'.adm_translate("Membres").'</label>
      </div>
      <div class="form-check form-check-inline">
         <input type="radio" id="tous" name="privs" class="form-check-input" value="0" />
         <label class="form-check-label" for="tous">'.adm_translate("Tous").'</label>
      </div>
   </div>
   <div class="mb-3 row">
      <label class="col-form-label col-sm-12" for="mpri">'.adm_translate("Groupes").'</label>
      <div class="col-sm-12">';
         echo groupe($member).'
      </div>
   </div>';
   } else {
      $checked = ($member==0) ? ' checked="checked"' : '' ;
      echo '
         <input type="radio" id="mem" name="privs" class="form-check-input" value="1" />
         <label class="form-check-label" for="mem">'.adm_translate("Membres").'</label>
      </div>
      <div class="form-check form-check-inline">
         <input type="radio" id="tous" name="privs" class="form-check-input" value="0"'.$checked.' />
         <label class="form-check-label" for="tous">'.adm_translate("Tous").'</label>
      </div>
   </div>
   <div class="mb-3 row">
      <label class="col-form-label col-sm-12" for="mpri">'.adm_translate("Groupes").'</label>
      <div class="col-sm-12">';
      echo groupe($member).'
      </div>
   </div>';
   }
}

function DownloadAdmin() {
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   $resultX = sql_query("SELECT DISTINCT dcategory FROM ".$NPDS_Prefix."downloads ORDER BY dcategory");
   $num_row=sql_num_rows($resultX);

   echo '
   <hr />
   <h3 class="my-3">'.adm_translate("Catégories").'</h3>';
   $pseudocatid ='';
   while(list($dcategory) = sql_fetch_row($resultX)) {
      $pseudocatid++;
      echo '
   <h4 class="mb-2"><a class="tog" id="show_cat_'.$pseudocatid.'" title="Déplier la liste"><i id="i_cat_'.$pseudocatid.'" class="fa fa-caret-down fa-lg text-primary"></i></a>
      '.aff_langue(stripslashes($dcategory)).'</h4>';
      echo '
   <div class="mb-3" id="cat_'.$pseudocatid.'" style="display:none;">
      <table data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-show-columns="true" data-mobile-responsive="true" data-buttons-class="outline-secondary" data-icons-prefix="fa" data-icons="icons">
         <thead>
            <tr>
               <th data-sortable="true" data-halign="center" data-align="right">'.adm_translate("ID").'</th>
               <th data-sortable="true" data-halign="center" data-align="right">'.adm_translate("Compteur").'</th>
               <th data-sortable="true" data-halign="center" data-align="center">Typ.</th>
               <th data-halign="center" data-align="center">'.adm_translate("URL").'</th>
               <th data-sortable="true" data-halign="center" >'.adm_translate("Nom de fichier").'</th>
               <th data-halign="center" data-align="center">'.adm_translate("Version").'</th>
               <th data-halign="center" data-align="right">'.adm_translate("Taille de fichier").'</th>
               <th data-halign="center" >'.adm_translate("Date").'</th>
               <th data-halign="center" data-align="center">'.adm_translate("Fonctions").'</th>
            </tr>
         </thead>
         <tbody>';
      $result = sql_query("SELECT did, dcounter, durl, dfilename, dfilesize, ddate, dver, perms FROM ".$NPDS_Prefix."downloads WHERE dcategory='".addslashes($dcategory)."' ORDER BY did ASC");
      while(list($did, $dcounter, $durl, $dfilename, $dfilesize, $ddate, $dver, $dperm) = sql_fetch_row($result)) {
         if ($dperm=='0') $dperm='<span title="'.adm_translate("Anonymes").'<br />'.adm_translate("Membres").'<br />'.adm_translate("Administrateurs").'" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true"><i class="far fa-user fa-lg"></i><i class="fas fa-user fa-lg"></i><i class="fa fa-user-cog fa-lg"></i></span>';
         else if ($dperm=='1') $dperm='<span title="'.adm_translate("Membres").'" data-bs-toggle="tooltip" data-bs-placement="right"><i class="fas fa-user fa-lg"></i></span>';
         else if ($dperm=='-127') $dperm='<span title="'.adm_translate("Administrateurs").'" data-bs-toggle="tooltip" data-bs-placement="right"><i class="fas fa-user-cog fa-lg"></i></span>';
         else if ($dperm=='-1') $dperm='<span title="'.adm_translate("Anonymes").'"  data-bs-toggle="tooltip" data-bs-placement="right"><i class="far fa-user fa-lg"></i></span>';
         else $dperm='<span title="'.adm_translate("Groupes").'" data-bs-toggle="tooltip" data-bs-placement="right"><i class="fa fa-users fa-lg"></i></span>';
         echo '
            <tr>
               <td>'.$did.'</td>
               <td>'.$dcounter.'</td>
               <td>'.$dperm.'</td>
               <td><a href="'.$durl.'" title="'.adm_translate("Téléchargements").'<br />'.$durl.'" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true"><i class="fa fa-download fa-2x"></i></a></td>
               <td>'.$dfilename.'</td>
               <td><span class="small">'.$dver.'</span></td>
               <td><span class="small">';
         $Fichier = new FileManagement;
         if ($dfilesize!=0)
            echo $Fichier->file_size_format($dfilesize, 1);
         else
            echo $Fichier->file_size_auto($durl, 2);
         echo '</span></td>
               <td class="small">'.$ddate.'</td>
               <td>
                  <a href="admin.php?op=DownloadEdit&amp;did='.$did.'" title="'.adm_translate("Editer").'" data-bs-toggle="tooltip" data-bs-placement="right"><i class="fa fa-edit fa-lg"></i></a>
                  <a href="admin.php?op=DownloadDel&amp;did='.$did.'&amp;ok=0" title="'.adm_translate("Effacer").'" data-bs-toggle="tooltip" data-bs-placement="right"><i class="fas fa-trash fa-lg text-danger ms-2"></i></a>
               </td>
               </tr>';
       }
       echo '
         </tbody>
      </table>
   </div>';
   echo '
   <script type="text/javascript">
      //<![CDATA[
         $( document ).ready(function() {
            tog("cat_'.$pseudocatid.'","show_cat_'.$pseudocatid.'","hide_cat_'.$pseudocatid.'");
         })
      //]]>
   </script>';
   }
   echo '
   <hr />
   <h3 class="mb-3">'.adm_translate("Ajouter un Téléchargement").'</h3>
   <form action="admin.php" method="post" id="downloadadd" name="adminForm">
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="durl">'.adm_translate("Télécharger URL").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="durl" name="durl" maxlength="320" required="required" />
   &nbsp;<a href="javascript:void(0);" onclick="window.open(\'admin.php?op=FileManagerDisplay\', \'wdir\', \'width=650, height=450, menubar=no, location=no, directories=no, status=no, copyhistory=no, toolbar=no, scrollbars=yes, resizable=yes\');">
   <span class="">['.adm_translate("Parcourir").']</span></a>
            <span class="help-block text-end" id="countcar_durl"></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="dcounter">'.adm_translate("Compteur").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="number" id="dcounter" name="dcounter" maxlength="30" />
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="dfilename">'.adm_translate("Nom de fichier").'</label>
            <div class="col-sm-8">
               <input class="form-control" type="text" id="dfilename" name="dfilename" maxlength="255" required="required" />
               <span class="help-block text-end" id="countcar_dfilename"></span>
            </div>
         </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="dver">'.adm_translate("Version").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" name="dver" id="dver" maxlength="6" />
            <span class="help-block text-end" id="countcar_dver"></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="dfilesize">'.adm_translate("Taille de fichier").' (bytes)</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="dfilesize" name="dfilesize" maxlength="31" />
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="dweb">'.adm_translate("Propriétaire de la page Web").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="dweb" name="dweb" maxlength="255" />
            <span class="help-block text-end" id="countcar_dweb"></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="duser">'.adm_translate("Propriétaire").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="duser" name="duser" maxlength="30" />
            <span class="help-block text-end" id="countcar_duser"></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="dcategory">'.adm_translate("Catégorie").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="dcategory" name="dcategory" maxlength="250" required="required"/>
            <span class="help-block text-end" id="countcar_dcategory"></span>
            <select class="form-select" name="sdcategory" onchange="adminForm.dcategory.value=options[selectedIndex].value">
               <option>'.adm_translate("Catégorie").'</option>';
   $result = sql_query("SELECT DISTINCT dcategory FROM ".$NPDS_Prefix."downloads ORDER BY dcategory");
   while (list($dcategory) = sql_fetch_row($result)) {
      $dcategory=stripslashes($dcategory);
      echo '
               <option value="'.$dcategory.'">'.aff_langue($dcategory).'</option>';
    }
   echo '
            </select>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-12" for="xtext">'.adm_translate("Description").'</label>
         <div class="col-sm-12">
            <textarea class="tin form-control" id="xtext" name="xtext" rows="20" ></textarea>
         </div>
      </div>
      '.aff_editeur('xtext','').'
      <fieldset>
         <legend>'.adm_translate("Droits").'</legend>';
         droits('0');
   echo '
      </fieldset>
      <input type="hidden" name="op" value="DownloadAdd" />
      <div class="mb-3 row">
         <div class="col-sm-12">
            <button class="btn btn-primary" type="submit">'.adm_translate("Ajouter").'</button>
         </div>
      </div>
   </form>';
   $arg1='
         var formulid = ["downloadadd"];
         inpandfieldlen("durl",320);
         inpandfieldlen("dfilename",255);
         inpandfieldlen("dver",6);
         inpandfieldlen("dfilesize",31);
         inpandfieldlen("dweb",255);
         inpandfieldlen("duser",30);
         inpandfieldlen("dcategory",250);
   ';
   adminfoot('fv','',$arg1,'');
}

function DownloadEdit($did) {
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   $result = sql_query("SELECT did, dcounter, durl, dfilename, dfilesize, ddate, dweb, duser, dver, dcategory, ddescription, perms FROM ".$NPDS_Prefix."downloads WHERE did='$did'");
   list($did, $dcounter, $durl, $dfilename, $dfilesize, $ddate, $dweb, $duser, $dver, $dcategory, $ddescription, $privs) = sql_fetch_row($result);
   $ddescription=stripslashes($ddescription);
   echo '
   <hr />
   <h3 class="mb-3">'.adm_translate("Editer un Téléchargement").'</h3>
   <form action="admin.php" method="post" id="downloaded" name="adminForm">
      <input type="hidden" name="did" value="'.$did.'" />
      <input type="hidden" name="dcounter" value="'.$dcounter.'" />
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="durl">'.adm_translate("Télécharger URL").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="durl" name="durl" value="'.$durl.'" maxlength="320" required="required" />
            <span class="help-block text-end" id="countcar_durl"></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="dfilename">'.adm_translate("Nom de fichier").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="dfilename" name="dfilename" id="dfilename" value="'.$dfilename.'" maxlength="255" required="required" />
            <span class="help-block text-end" id="countcar_dfilename"></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="dver">'.adm_translate("Version").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" name="dver" id="dver" value="'.$dver.'" maxlength="6" />
            <span class="help-block text-end" id="countcar_dver"></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="dfilesize">'.adm_translate("Taille de fichier").' (bytes)</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="dfilesize" name="dfilesize" value="'.$dfilesize.'" maxlength="31" />
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="dweb">'.adm_translate("Propriétaire de la page Web").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="dweb" name="dweb" value="'.$dweb.'" maxlength="255" />
            <span class="help-block text-end" id="countcar_dweb"></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="duser">'.adm_translate("Propriétaire").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="duser" name="duser" value="'.$duser.'" maxlength="30" />
            <span class="help-block text-end" id="countcar_duser"></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="dcategory">'.adm_translate("Catégorie").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="dcategory" name="dcategory" value="'.stripslashes($dcategory).'" maxlength="250" required="required" />
            <span class="help-block text-end"><span id="countcar_dcategory"></span></span>
            <select class="form-select" name="sdcategory" onchange="adminForm.dcategory.value=options[selectedIndex].value">';
   $result = sql_query("SELECT DISTINCT dcategory FROM ".$NPDS_Prefix."downloads ORDER BY dcategory");
   while (list($Xdcategory) = sql_fetch_row($result)) {
      $sel = $Xdcategory==$dcategory ? 'selected' : '';
      $Xdcategory=stripslashes($Xdcategory);
      echo '
               <option '.$sel.' value="'.$Xdcategory.'">'.aff_langue($Xdcategory).'</option>';
   }
   echo '
            </select>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-12" for="xtext">'.adm_translate("Description").'</label>
         <div class="col-sm-12">
            <textarea class="tin form-control" id="xtext" name="xtext" rows="20" >'.$ddescription.'</textarea>
         </div>
      </div>
      '.aff_editeur('xtext','');
   echo '
      <fieldset>
         <legend>'.adm_translate("Droits").'</legend>';
       droits($privs);
   echo '
      </fieldset>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4">'.adm_translate("Changer la date").'</label>
         <div class="col-sm-8">
            <div class="form-check my-2">
               <input type="checkbox" id="ddate" name="ddate" class="form-check-input" value="yes" />
               <label class="form-check-label" for="ddate">'.adm_translate("Oui").'</label>
            </div>
         </div>
      </div>
      <div class="mb-3 row">
         <div class="col-sm-12">
            <input type="hidden" name="op" value="DownloadSave" />
            <input class="btn btn-primary" type="submit" value="'.adm_translate("Sauver les modifications").'" />
         </div>
      </div>
   </form>';
   $arg1='
      var formulid = ["downloaded"];
      inpandfieldlen("durl",320);
      inpandfieldlen("dfilename",255);
      inpandfieldlen("dver",6);
      inpandfieldlen("dfilesize",31);
      inpandfieldlen("dweb",255);
      inpandfieldlen("duser",30);
      inpandfieldlen("dcategory",250);
';
   adminfoot('fv','',$arg1,'');
}

function DownloadSave($did, $dcounter, $durl, $dfilename, $dfilesize, $dweb, $duser, $ddate, $dver, $dcategory, $sdcategory, $description, $privs, $Mprivs) {
   global $NPDS_Prefix;
   if ($privs==1) {
      if ($Mprivs!='')
         $privs = implode(',', $Mprivs);
   }
   $sdcategory=addslashes($sdcategory);
   $dcategory = (!$dcategory) ? $sdcategory : addslashes($dcategory) ;
   $description=addslashes($description);
   if ($ddate=="yes") {
      $time = date("Y-m-d");
      sql_query("UPDATE ".$NPDS_Prefix."downloads SET dcounter='$dcounter', durl='$durl', dfilename='$dfilename', dfilesize='$dfilesize', ddate='$time', dweb='$dweb', duser='$duser', dver='$dver', dcategory='$dcategory', ddescription='$description', perms='$privs' WHERE did='$did'");
   } else
      sql_query("UPDATE ".$NPDS_Prefix."downloads SET dcounter='$dcounter', durl='$durl', dfilename='$dfilename', dfilesize='$dfilesize', dweb='$dweb', duser='$duser', dver='$dver', dcategory='$dcategory', ddescription='$description', perms='$privs' WHERE did='$did'");
   Header("Location: admin.php?op=DownloadAdmin");
}

function DownloadAdd($dcounter, $durl, $dfilename, $dfilesize, $dweb, $duser, $dver, $dcategory, $sdcategory, $description, $privs, $Mprivs) {
   global $NPDS_Prefix;
   if ($privs==1) {
      if ($Mprivs>1 and $Mprivs<=127 and $Mprivs!='') $privs=$Mprivs;
   }
   $sdcategory=addslashes($sdcategory);
   $dcategory = (!$dcategory) ? $sdcategory : addslashes($dcategory) ;
   $description=addslashes($description);
   $time = date("Y-m-d");
   if (($durl) and ($dfilename))
      sql_query("INSERT INTO ".$NPDS_Prefix."downloads VALUES ('0', '0', '$durl', '$dfilename', '0', '$time', '$dweb', '$duser', '$dver', '$dcategory', '$description', '$privs')");
   Header("Location: admin.php?op=DownloadAdmin");
}

function DownloadDel($did, $ok=0) {
   global $NPDS_Prefix, $f_meta_nom;
   if ($ok==1) {
      sql_query("DELETE FROM ".$NPDS_Prefix."downloads WHERE did='$did'");
      Header("Location: admin.php?op=DownloadAdmin");
   } else {
   global $hlpfile, $f_titre, $adminimg;
   include("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);

   echo' 
       <div class="alert alert-danger">
           <strong>'.adm_translate("ATTENTION : êtes-vous sûr de vouloir supprimer ce fichier téléchargeable ?").'</strong>
       </div>
       <a class="btn btn-danger" href="admin.php?op=DownloadDel&amp;did='.$did.'&amp;ok=1" >'.adm_translate("Oui").'</a>&nbsp;<a class="btn btn-secondary" href="admin.php?op=DownloadAdmin" >'.adm_translate("Non").'</a>';
   adminfoot('','','','');
   }
}
?>
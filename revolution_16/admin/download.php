<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2017 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],"admin.php")) { Access_Error(); }
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
   while (list($groupe_id, $groupe_name)=each($mX)) {
      $selectionne=0;
      if ($les_groupes) {
         foreach ($les_groupes as $groupevalue) {
            if (($groupe_id==$groupevalue) and ($groupe_id!=0)) {$selectionne=1;}
         }
      }
      if ($selectionne==1) {
         $str.='
         <option value="'.$groupe_id.'" selected="selected">'.$groupe_name.'</option>';
      } else {
         $str.='
         <option value="'.$groupe_id.'">'.$groupe_name.'</option>';
      }
      $nbg++;
   }
   if ($nbg>5) {$nbg=5;}
   return ('
   <select multiple="multiple" class="form-control" name="Mprivs[]" size="'.$nbg.'">
   '.$str.'
   </select>');
}

function droits($member) {
   echo '
   <div class="form-group">
      <label class="radio-inline text-danger">';
   if ($member==-127) {$checked=' checked="checked"';} else {$checked='';}
   echo '
         <input type="radio" name="privs" value="-127" '.$checked.' />'.adm_translate("Administrateurs").'
      </label>
      <label class="radio-inline text-danger">';
   if ($member==-1) {$checked=' checked="checked"';} else {$checked='';}
   echo '
         <input type="radio" name="privs" value="-1" '.$checked.' />'.adm_translate("Anonymes").'
      </label>';
   echo '
      <label class="radio-inline text-danger">';
   if ($member>0) {
      echo '
         <input type="radio" name="privs" value="1" checked="checked" />'.adm_translate("Membres").'
      </label>
      <label class="radio-inline">
         <input type="radio" name="privs" value="0" />'.adm_translate("Tous").'
      </label>
   </div>
   <div class="form-group row">
      <label class="form-control-label col-sm-12" for="Mmember[]">'.adm_translate("Groupes").'</label>
      <div class="col-sm-12">';
         echo groupe($member).'
      </div>
   </div>';
   } else {
      if ($member==0) {$checked=' checked="checked"';} else {$checked='';}
      echo '
      <input type="radio" name="privs" value="1" />'.adm_translate("Membres").'
      </label>
      <label class="radio-inline">
         <input type="radio" name="privs" value="0"'.$checked.' />'.adm_translate("Tous").'
      </label>
   </div>
   <div class="form-group row">
      <label class="form-control-label col-sm-12" for="Mmember[]">'.adm_translate("Groupes").'</label>
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
   <h3>'.adm_translate("Catégories").'</h3>';
   $pseudocatid ='';
   while(list($dcategory) = sql_fetch_row($resultX)) {
      $pseudocatid++;
      echo '
   <h4 class="mb-2"><a class="tog" id="show_cat_'.$pseudocatid.'" title="Déplier la liste"><i id="i_cat_'.$pseudocatid.'" class="fa fa-caret-down fa-lg text-primary"></i></a>
      '.aff_langue(stripslashes($dcategory)).'</h4>';
      echo '
   <div class="mb-3" id="cat_'.$pseudocatid.'" style="display:none;">
   <table data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-show-columns="true" data-mobile-responsive="true" data-icons-prefix="fa" data-icons="icons">
      <thead>
         <tr>
            <th data-sortable="true" data-halign="center" data-align="right">'.adm_translate("ID").'</th>
            <th data-sortable="true" data-halign="center" data-align="right">'.adm_translate("Compt.").'</th>
            <th data-sortable="true" data-halign="center" data-align="center">Typ.</th>
            <th data-halign="center" data-align="right">'.adm_translate("URL").'</th>
            <th data-sortable="true" data-halign="center" >'.adm_translate("Nom de fichier").'</th>
            <th data-halign="center" >'.adm_translate("Ver.").'</th>
            <th data-halign="center" data-align="right">'.adm_translate("Taille de fichier").'</th>
            <th data-halign="center" >'.adm_translate("Date").'</th>
            <th data-halign="center" data-align="right">'.adm_translate("Fonctions").'</th>
         </tr>
      </thead>
      <tbody>';
       $result = sql_query("SELECT did, dcounter, durl, dfilename, dfilesize, ddate, dver, perms FROM ".$NPDS_Prefix."downloads WHERE dcategory='".addslashes($dcategory)."' ORDER BY did ASC");
       while(list($did, $dcounter, $durl, $dfilename, $dfilesize, $ddate, $dver, $dperm) = sql_fetch_row($result)) {
          echo '
            <tr>
               <td>'.$did.'</td>
               <td>'.$dcounter.'</td>';
          if ($dperm==0) {$dperm='Al';}
          if ($dperm>=1) {$dperm='Mb';}
          if ($dperm==-127) {$dperm='Ad';}
          if ($dperm==-1) {$dperm='An';}
          echo '
               <td>'.$dperm.'</td>
               <td><a href="'.$durl.'" title="'.adm_translate("Téléchargements").'" data-toggle="tooltip"><i class="fa fa-download fa-2x"></i></a></td>
               <td>'.$dfilename.'</td>
               <td>'.$dver.'</td>
               <td>';
                  $Fichier = new FileManagement;
                if ($dfilesize!=0) {
               echo $Fichier->file_size_auto($durl, 2);
                } else {
                echo $Fichier->file_size_auto($durl, 2);
                }
                echo '</td>
                <td>'.$ddate.'</td>
                <td>
                   <a href="admin.php?op=DownloadEdit&amp;did='.$did.'" title="'.adm_translate("Editer").'" data-toggle="tooltip"><i class="fa fa-edit fa-lg"></i></a>&nbsp;
                   <a href="admin.php?op=DownloadDel&amp;did='.$did.'&amp;ok=0" title="'.adm_translate("Effacer").'" data-toggle="tooltip"><i class="fa fa-trash-o fa-lg text-danger"></i></a>
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
   <form action="admin.php" method="post" name="adminForm">
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="durl">'.adm_translate("Télécharger URL").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="durl" name="durl" maxlength="255" required="required" />
   &nbsp;<a href="javascript:void(0);" onclick="window.open(\'admin.php?op=FileManagerDisplay\', \'wdir\', \'width=650, height=450, menubar=no, location=no, directories=no, status=no, copyhistory=no, toolbar=no, scrollbars=yes, resizable=yes\');">
   <span class="">['.adm_translate("Parcourir").']</span></a>
            <span class="help-block text-right"><span id="countcar_durl"></span></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="dcounter">'.adm_translate("Compteur").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="number" name="dcounter" maxlength="30" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="dfilename">'.adm_translate("Nom de fichier").'</label>
            <div class="col-sm-8">
               <input class="form-control" type="text" id="dfilename" name="dfilename" maxlength="255" required="required" />
               <span class="help-block text-right"><span id="countcar_dfilename"></span></span>
            </div>
         </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="dver">'.adm_translate("Version").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" name="dver" id="dver" maxlength="6" />
            <span class="help-block text-right"><span id="countcar_dver"></span></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="dfilesize">'.adm_translate("Taille de fichier").' (bytes)</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" name="dfilesize" maxlength="31" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="dweb">'.adm_translate("Propriétaire de la page Web").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="dweb" name="dweb" maxlength="255" />
            <span class="help-block text-right"><span id="countcar_dweb"></span></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="duser">'.adm_translate("Propriétaire").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="duser" name="duser" maxlength="30" />
            <span class="help-block text-right"><span id="countcar_duser"></span></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="dcategory">'.adm_translate("Catégorie").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="dcategory" name="dcategory" maxlength="250" />
            <span class="help-block text-right"><span id="countcar_dcategory"></span></span>
            <select class="custom-select form-control" name="sdcategory">';
   $result = sql_query("SELECT DISTINCT dcategory FROM ".$NPDS_Prefix."downloads ORDER BY dcategory");
   while (list($dcategory) = sql_fetch_row($result)) {
      $dcategory=stripslashes($dcategory);
      echo '
               <option '.$sel.' value="'.$dcategory.'">'.aff_langue($dcategory).'</option>';
    }
   echo '
            </select>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-12" for="xtext">'.adm_translate("Description").'</label>
         <div class="col-sm-12">
            <textarea class="tin form-control" name="xtext" rows="20" ></textarea>
         </div>
      </div>
      '.aff_editeur('xtext','').'
      <fieldset>
         <legend>'.adm_translate("Droits").'</legend>';
         droits('');
         echo '
      </fieldset>
      <input type="hidden" name="op" value="DownloadAdd" />
      <div class="form-group row">
         <div class="col-sm-12">
            <input class="btn btn-primary" type="submit" value="'.adm_translate("Ajouter").'" />
         </div>
      </div>
   </form>
   <script type="text/javascript">
   //<![CDATA[
      $(document).ready(function() {
         inpandfieldlen("durl",255);
         inpandfieldlen("dfilename",255);
         inpandfieldlen("dver",6);
         inpandfieldlen("dfilesize",31);
         inpandfieldlen("dweb",255);
         inpandfieldlen("duser",30);
         inpandfieldlen("dcategory",250);
      });
   //]]>
   </script>';
   adminfoot('fv','','','');
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
   <form action="admin.php" method="post" name="adminForm">
      <input type="hidden" name="did" value="'.$did.'" />
      <input type="hidden" name="dcounter" value="'.$dcounter.'" />
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="durl">'.adm_translate("Télécharger URL").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="durl" name="durl" value="'.$durl.'" maxlength="255" required="required" />
            <span class="help-block text-right"><span id="countcar_durl"></span></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="dfilename">'.adm_translate("Nom de fichier").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="dfilename" name="dfilename" id="dfilename" value="'.$dfilename.'" maxlength="255" required="required" />
            <span class="help-block text-right"><span id="countcar_dfilename"></span></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="dver">'.adm_translate("Version").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" name="dver" id="dver" value="'.$dver.'" maxlength="6" />
            <span class="help-block text-right"><span id="countcar_dver"></span></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="dfilesize">'.adm_translate("Taille de fichier").' (bytes)</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" name="dfilesize" value="'.$dfilesize.'" maxlength="31" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="dweb">'.adm_translate("Propriétaire de la page Web").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="dweb" name="dweb" value="'.$dweb.'" maxlength="255" />
            <span class="help-block text-right"><span id="countcar_dweb"></span></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="duser">'.adm_translate("Propriétaire").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="duser" name="duser" value="'.$duser.'" maxlength="30" />
            <span class="help-block text-right"><span id="countcar_duser"></span></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="dcategory">'.adm_translate("Catégorie").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="dcategory" name="dcategory" value="'.stripslashes($dcategory).'" maxlength="250" />
            <span class="help-block text-right"><span id="countcar_dcategory"></span></span>
            <select class="custom-select form-control" name="sdcategory" onchange="adminForm.dcategory.value=options[selectedIndex].value">';
   $result = sql_query("SELECT distinct dcategory FROM ".$NPDS_Prefix."downloads ORDER BY dcategory");
   while (list($Xdcategory) = sql_fetch_row($result)) {
      if ($Xdcategory==$dcategory) $sel='selected';
      else $sel='';
      $Xdcategory=stripslashes($Xdcategory);
      echo '
               <option '.$sel.' value="'.$Xdcategory.'">'.aff_langue($Xdcategory).'</option>';
   }
   echo '
            </select>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-12" for="xtext">'.adm_translate("Description").'</label>
         <div class="col-sm-12">
            <textarea class="tin form-control" name="xtext" rows="20" >'.$ddescription.'</textarea>
         </div>
      </div>
      '.aff_editeur('xtext','');
       echo '
      <fieldset>
         <legend>'.adm_translate("Droits").'</legend>';
       droits($privs);
       echo '
      </fieldset>
      <div class="form-group row">
         <div class="col-sm-12">
            <label>'.adm_translate("Changer la date").'
               <input type="checkbox" name="ddate" value="yes" />&nbsp;'.adm_translate("Oui").'
            </label>
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-12">
            <input type="hidden" name="op" value="DownloadSave" />
            <input class="btn btn-primary" type="submit" value="'.adm_translate("Sauver les modifications").'" />
         </div>
      </div>
   </form>
   <script type="text/javascript">
   //<![CDATA[
      $(document).ready(function() {
         inpandfieldlen("durl",255);
         inpandfieldlen("dfilename",255);
         inpandfieldlen("dver",6);
         inpandfieldlen("dfilesize",31);
         inpandfieldlen("dweb",255);
         inpandfieldlen("duser",30);
         inpandfieldlen("dcategory",250);
      });
   //]]>
   </script>';
   adminfoot('fv','','','');
}

function DownloadSave($did, $dcounter, $durl, $dfilename, $dfilesize, $dweb, $duser, $ddate, $dver, $dcategory, $sdcategory, $description, $privs, $Mprivs) {
    global $NPDS_Prefix;
   if ($privs==1) {
       if ($Mprivs>1 and $Mprivs<=127 and $Mprivs!="") {$privs=$Mprivs;}
    }
    $sdcategory=addslashes($sdcategory);
    if (!$dcategory) {
       $dcategory = $sdcategory;
    } else {
       $dcategory=addslashes($dcategory);
    }
    $description=addslashes($description);
    if ($ddate=="yes") {
       $time = date("Y-m-d");
       sql_query("UPDATE ".$NPDS_Prefix."downloads SET dcounter='$dcounter', durl='$durl', dfilename='$dfilename', dfilesize='$dfilesize', ddate='$time', dweb='$dweb', duser='$duser', dver='$dver', dcategory='$dcategory', ddescription='$description', perms='$privs' WHERE did='$did'");
    } else {
       sql_query("UPDATE ".$NPDS_Prefix."downloads SET dcounter='$dcounter', durl='$durl', dfilename='$dfilename', dfilesize='$dfilesize', dweb='$dweb', duser='$duser', dver='$dver', dcategory='$dcategory', ddescription='$description', perms='$privs' WHERE did='$did'");
    }
    Header("Location: admin.php?op=DownloadAdmin");
}

function DownloadAdd($dcounter, $durl, $dfilename, $dfilesize, $dweb, $duser, $dver, $dcategory, $sdcategory, $description, $privs, $Mprivs) {
    global $NPDS_Prefix;
   if ($privs==1) {
       if ($Mprivs>1 and $Mprivs<=127 and $Mprivs!='') {$privs=$Mprivs;}
    }
    $sdcategory=addslashes($sdcategory);
    if (!$dcategory) {
       $dcategory = $sdcategory;
    } else {
       $dcategory=addslashes($dcategory);
    }
    $description=addslashes($description);
    $time = date("Y-m-d");
    if (($durl) and ($dfilename))
       sql_query("INSERT INTO ".$NPDS_Prefix."downloads VALUES (NULL, '$dcounter', '$durl', '$dfilename', '$dfilesize', '$time', '$dweb', '$duser', '$dver', '$dcategory', '$description', '$privs')");
    Header("Location: admin.php?op=DownloadAdmin");
}

function DownloadDel($did, $ok=0) {
global $NPDS_Prefix;
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
           <button class="close" data-dismiss="alert">×</button>
           <strong>'.adm_translate("ATTENTION : êtes-vous sûr de vouloir supprimer ce fichier téléchargeable ?").'</strong>
       </div>
       <a class="btn btn-danger" href="admin.php?op=DownloadDel&amp;did='.$did.'&amp;ok=1" >'.adm_translate("Oui").'</a>&nbsp;<a class="btn btn-secondary" href="admin.php?op=DownloadAdmin" >'.adm_translate("Non").'</a>';
   adminfoot('','','','');
   }
}
?>
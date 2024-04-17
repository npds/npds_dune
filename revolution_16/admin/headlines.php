<?php
/************************************************************************/
/* DUNE by NPDS - admin prototype                                       */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

if (!function_exists('admindroits'))
   include('die.php');
$f_meta_nom ='HeadlinesAdmin';
$f_titre = adm_translate("Grands Titres de sites de News");
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
global $language;
$hlpfile = "manuels/$language/headlines.html";

function HeadlinesAdmin() {
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo'
   <hr />
   <h3 class="mb-3">'.adm_translate("Liste des Grands Titres de sites de News").'</h3>
   <table id="tad_headline" data-toggle="table" data-classes="table table-striped table-borderless" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa">
      <thead>
         <tr>
           <th class="n-t-col-xs-1" data-sortable="true" data-halign="center" data-align="right">'.adm_translate("ID").'</th>
           <th data-sortable="true" data-halign="center" >'.adm_translate("Nom du site").'</th>
           <th data-sortable="true" data-halign="center" >'.adm_translate("URL").'</th>
           <th data-sortable="true" data-halign="center" data-align="right" >'.adm_translate("Etat").'</th>
           <th class="n-t-col-xs-2" data-halign="center" data-align="center" >'.adm_translate("Fonctions").'</th>
         </tr>
      </thead>
      <tbody>';
   $result = sql_query("SELECT hid, sitename, url, headlinesurl, status FROM ".$NPDS_Prefix."headlines ORDER BY hid");
   while(list($hid, $sitename, $url, $headlinesurl, $status) = sql_fetch_row($result)) {
      echo '
         <tr>
            <td>'.$hid.'</td>
            <td>'.$sitename.'</td>
            <td>'.$url.'</td>';
      if($status == 1) {
      $status = '<span class="text-success">'.adm_translate("Actif(s)").'</span>';
      } else {
      $status = '<span class="text-danger">'.adm_translate("Inactif(s)").'</span>';
      }
      echo '
            <td>'.$status.'</td>
            <td>
               <a href="admin.php?op=HeadlinesEdit&amp;hid='.$hid.'"><i class="fa fa-edit fa-lg" title="'.adm_translate("Editer").'" data-bs-toggle="tooltip"></i></a>&nbsp;
               <a href="'.$url.'" target="_blank"><i class="fas fa-external-link-alt fa-lg" title="'.adm_translate("Visiter").'" data-bs-toggle="tooltip"></i></a>&nbsp;
               <a href="admin.php?op=HeadlinesDel&amp;hid='.$hid.'&amp;ok=0" class="text-danger"><i class="fas fa-trash fa-lg" title="'.adm_translate("Effacer").'" data-bs-toggle="tooltip"></i></a>
            </td>
         </tr>';
      }
      echo '
      </tbody>
   </table>
   <hr />
   <h3 class="mb-3">'.adm_translate("Nouveau Grand Titre").'</h3>
   <form id="fad_newheadline" action="admin.php" method="post">
      <fieldset>
         <div class="form-floating mb-3">
            <input id="xsitename" class="form-control" type="text" name="xsitename" placeholder="'.adm_translate("Nom du site").'" maxlength="30" required="required" />
            <label for="xsitename">'.adm_translate("Nom du site").'</label>
         </div>
         <div class="form-floating mb-3">
            <input id="url" class="form-control" type="url" name="url" placeholder="'.adm_translate("URL").'" maxlength="320" required="required" />
            <label for="url">'.adm_translate("URL").'</label>
            <span class="help-block text-end"><span id="countcar_url"></span></span>
         </div>
         <div class="form-floating mb-3">
            <input id="headlinesurl" class="form-control" type="url" name="headlinesurl" placeholder="'.adm_translate("URL pour le fichier RDF/XML").'" maxlength="320" required="required" />
            <label for="headlinesurl">'.adm_translate("URL pour le fichier RDF/XML").'</label>
            <span class="help-block text-end"><span id="countcar_headlinesurl"></span></span>
         </div>
         <div class="form-floating mb-3">
            <select class="form-select" id="status" name="status">
               <option name="status" value="1">'.adm_translate("Actif(s)").'</option>
               <option name="status" value="0" selected="selected">'.adm_translate("Inactif(s)").'</option>
            </select>
            <label class="col-form-label col-sm-4" for="status">'.adm_translate("Etat").'</label>
         </div>
         <button class="btn btn-primary" type="submit"><i class="fa fa-plus-square fa-lg me-2"></i>'.adm_translate("Ajouter").'</button>
         <input type="hidden" name="op" value="HeadlinesAdd" />
      </fieldset>
   </form>';
   $arg1='
      var formulid = ["fad_newheadline"];
      inpandfieldlen("xsitename",30);
      inpandfieldlen("url",320);
      inpandfieldlen("headlinesurl",320);';
   adminfoot('fv','',$arg1,'');
}

function HeadlinesEdit($hid) {
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   $result = sql_query("SELECT sitename, url, headlinesurl, status FROM ".$NPDS_Prefix."headlines WHERE hid='$hid'");
   list($xsitename, $url, $headlinesurl, $status) = sql_fetch_row($result);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '
   <hr />
   <h3 class="mb-3">'.adm_translate("Editer paramètres Grand Titre").'</h3>
   <form id="fed_headline" action="admin.php" method="post">
      <fieldset>
         <input type="hidden" name="hid" value="'.$hid.'" />
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="xsitename">'.adm_translate("Nom du site").'</label>
            <div class="col-sm-8">
               <input class="form-control" type="text" name="xsitename" id="xsitename"  maxlength="30" value="'.$xsitename.'" required="required" />
               <span class="help-block text-end"><span id="countcar_xsitename"></span></span>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="url">'.adm_translate("URL").'&nbsp;<a href="'.$url.'" target="_blank"><i class="fas fa-external-link-alt fa-lg"></i></a></label>
            <div class="col-sm-8">
               <input class="form-control" type="url" id="url" name="url" maxlength="320" value="'.$url.'" required="required" />
               <span class="help-block text-end"><span id="countcar_url"></span></span>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="headlinesurl">'.adm_translate("URL pour le fichier RDF/XML").'&nbsp;<a href="'.$headlinesurl.'" target="_blank"><i class="fas fa-external-link-alt fa-lg"></i></a></label>
            <div class="col-sm-8">
               <input class="form-control" type="url" name="headlinesurl" id="headlinesurl" maxlength="320" value="'.$headlinesurl.'" required="required" />
               <span class="help-block text-end"><span id="countcar_headlinesurl"></span></span>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="status">'.adm_translate("Etat").'</label>
            <div class="col-sm-8">
               <select class="form-select" name="status">';
   $sel_a = $status == 1 ? 'selected="selected"' : '';
   $sel_i = $status == 0 ? 'selected="selected"' : '';
   echo '

                  <option name="status" value="1" '.$sel_a.'>'.adm_translate("Actif(s)").'</option>
                  <option name="status" value="0" '.$sel_i.'>'.adm_translate("Inactif(s)").'</option>
               </select>
            </div>
         </div>
         <div class="mb-3 row">
            <input type="hidden" name="op" value="HeadlinesSave" />
            <div class="col-sm-8 ms-sm-auto">
               <button class="btn btn-primary col-12" type="submit"><i class="fa fa-edit fa-lg"></i>&nbsp;'.adm_translate("Sauver les modifications").'</button>
            </div>
        </div>
      </fieldset>
   </form>';
   $arg1='
      var formulid = ["fed_headline"];
      inpandfieldlen("xsitename",30);
      inpandfieldlen("url",320);
      inpandfieldlen("headlinesurl",320);';
   adminfoot('fv','',$arg1,'');
}

function HeadlinesSave($hid, $xsitename, $url, $headlinesurl, $status) {
    global $NPDS_Prefix;
    sql_query("UPDATE ".$NPDS_Prefix."headlines SET sitename='$xsitename', url='$url', headlinesurl='$headlinesurl', status='$status' WHERE hid='$hid'");
    Header("Location: admin.php?op=HeadlinesAdmin");
}

function HeadlinesAdd($xsitename, $url, $headlinesurl, $status) {
    global $NPDS_Prefix;
    sql_query("INSERT INTO ".$NPDS_Prefix."headlines VALUES (NULL, '$xsitename', '$url', '$headlinesurl', '$status')");
    Header("Location: admin.php?op=HeadlinesAdmin");
}

function HeadlinesDel($hid, $ok=0) {
   global $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   if ($ok==1) {
      sql_query("DELETE FROM ".$NPDS_Prefix."headlines WHERE hid='$hid'");
      Header("Location: admin.php?op=HeadlinesAdmin");
   } else {
      global $hlpfile;
      include("header.php");
      GraphicAdmin($hlpfile);
      adminhead($f_meta_nom, $f_titre, $adminimg);
      echo '
      <hr />
      <p class="alert alert-danger">
         <strong class="d-block mb-1">'.adm_translate("Etes-vous sûr de vouloir supprimer cette boîte de Titres ?").'</strong>
         <a class="btn btn-danger btn-sm" href="admin.php?op=HeadlinesDel&amp;hid='.$hid.'&amp;ok=1" role="button">'.adm_translate("Oui").'</a>&nbsp;<a class="btn btn-secondary btn-sm" href="admin.php?op=HeadlinesAdmin" role="button">'.adm_translate("Non").'</a>
      </p>';
      include("footer.php");
   }
}

switch ($op) {
   case 'HeadlinesDel':
      HeadlinesDel($hid, $ok);
   break;
   case 'HeadlinesAdd':
      HeadlinesAdd($xsitename, $url, $headlinesurl, $status);
   break;
   case 'HeadlinesSave':
      HeadlinesSave($hid, $xsitename, $url, $headlinesurl, $status);
   break;
   case 'HeadlinesAdmin':
      HeadlinesAdmin();
   break;
   case 'HeadlinesEdit':
      HeadlinesEdit($hid);
   break;
}
?>
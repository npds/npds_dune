<?php
/************************************************************************/
/* DUNE by NPDS - admin prototype                                       */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!stristr($_SERVER['PHP_SELF'],"admin.php")) { Access_Error(); }
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
   echo'<h3 class="m-t-md">'.adm_translate("Liste des Grands Titres de sites de News").'</h3>';
   echo'
   <table id="tad_headline" data-toggle="table" data-striped="true" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa">
      <thead>
         <tr>
           <th data-sortable="true" data-halign="center" data-align="right">'.adm_translate("ID").'</th>
           <th data-sortable="true" data-halign="center" >'.adm_translate("Nom du site").'</th>
           <th data-sortable="true" data-halign="center" >'.adm_translate("URL").'</th>
           <th data-sortable="true" data-halign="center" data-align="right" >'.adm_translate("Etat").'</th>
           <th data-align="right" data-halign="center" >'.adm_translate("Fonctions").'</th>
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
               <a href="admin.php?op=HeadlinesEdit&amp;hid='.$hid.'"><i class="fa fa-edit fa-lg" title="'.adm_translate("Editer").'" data-toggle="tooltip"></i></a>&nbsp;
               <a href="'.$url.'" target="_blank"><i class="fa fa-external-link fa-lg" title="'.adm_translate("Visiter").'" data-toggle="tooltip"></i></a>&nbsp;
               <a href="admin.php?op=HeadlinesDel&amp;hid='.$hid.'&amp;ok=0" class="text-danger"><i class="fa fa-trash-o fa-lg" title="'.adm_translate("Effacer").'" data-toggle="tooltip"></i></a>
            </td>
         </tr>';
      }
      echo '
      </tbody>
   </table>
   <h3 class="m-t-md">'.adm_translate("Nouveau Grand Titre").'</h3>
   <form id="fad_newheadline" class="" action="admin.php" method="post">
      <fieldset>
         <div class="form-group row">
            <label class="form-control-label col-sm-4" for="xsitename">'.adm_translate("Nom du site").'</label>
            <div class="col-sm-8">
               <input id="xsitename" class="form-control" type="text" name="xsitename" placeholder="'.adm_translate("Nom du site").'" maxlength="30" required="required" />
            </div>
         </div>
         <div class="form-group row">
            <label class="form-control-label col-sm-4" for="url">'.adm_translate("URL").'</label>
            <div class="col-sm-8">
               <input id="url" class="form-control" type="url" name="url" placeholder="'.adm_translate("URL").'" maxlength="100" required="required" />
               <span class="help-block text-xs-right"><span id="countcar_url"></span></span>
            </div>
         </div>
         <div class="form-group row">
            <label class="form-control-label col-sm-4" for="headlinesurl">'.adm_translate("URL pour le fichier RDF/XML").'</label>
            <div class="col-sm-8">
                <input id="headlinesurl" class="form-control" type="url" name="headlinesurl" placeholder="'.adm_translate("URL pour le fichier RDF/XML").'" maxlength="200" required="required" />
                <span class="help-block text-xs-right"><span id="countcar_headlinesurl"></span></span>
            </div>
         </div>
         <div class="form-group row">
            <label class="form-control-label col-sm-4" for="status">'.adm_translate("Etat").'</label>
            <div class="col-sm-8">
               <select class="c-select form-control" id="status" name="status">
                  <option name="status" value="1">'.adm_translate("Actif(s)").'</option>
                  <option name="status" value="0" selected="selected">'.adm_translate("Inactif(s)").'</option>
               </select>
            </div>
         </div>
         <div class="form-group row">
            <div class="col-sm-offset-4 col-sm-8">
               <button class="btn btn-primary col-xs-12" type="submit"><i class="fa fa-plus-square fa-lg">&nbsp;</i>'.adm_translate("Ajouter").'</button>
            </div>
         </div>
         <input type="hidden" name="op" value="HeadlinesAdd" />
      </fieldset>
   </form>';
   echo '
   <script type="text/javascript">
   //<![CDATA[
   
   var fields=$( "#fad_newheadline").serializeArray();
   jQuery.each( fields, function( i, field ) {
      console.log( field.name+ " " );
    });
   
      
     console.log( $( "#fad_newheadline").serializeArray() );
   //]]>
   </script>';
   
   adminfieldinp($result);
   adminfoot('fv','','','');
}

function HeadlinesEdit($hid) {
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;

   include ("header.php");
  $vars = get_defined_vars();
   GraphicAdmin($hlpfile);
   $result = sql_query("SELECT sitename, url, headlinesurl, status FROM ".$NPDS_Prefix."headlines WHERE hid='$hid'");
   list($xsitename, $url, $headlinesurl, $status) = sql_fetch_row($result);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '
   <h3 class="m-t-md">'.adm_translate("Editer paramètres Grand Titre").'</h3>
   <form action="admin.php" method="post">
      <fieldset>
         <input type="hidden" name="hid" value="'.$hid.'" />
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4" for="xsitename">'.adm_translate("Nom du site").'</label>
               <div class="col-sm-8">
                  <input class="form-control" type="text" name="xsitename" maxlength="30" value="'.$xsitename.'" required="required" />
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4" for="url">'.adm_translate("URL").'</label>
               <div class="col-sm-8">
                  <div class="input-group">
                     <span class="input-group-btn">
                       <button class="btn btn-secondary" ><a href="'.$url.'" target="_blank"><i class="fa fa-external-link fa-lg"></i></a></button>
                     </span>
                     <input class="form-control" type="url" id="url" name="url" maxlength="100" value="'.$url.'" required="required" />
                     <span class="help-block text-xs-right"><span id="countcar_url"></span></span>
                  </div>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4" for="headlinesurl">'.adm_translate("URL pour le fichier RDF/XML").'</label>
               <div class="col-sm-8">
                  <div class="input-group">
                     <span class="input-group-btn">
                       <button class="btn btn-secondary" ><a href="'.$headlinesurl.'" target="_blank"><i class="fa fa-external-link "></i></a></button>
                     </span>
                     <input class="form-control" type="url" name="headlinesurl" maxlength="200" value="'.$headlinesurl.'" required="required" />
                  </div>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4" for="status">'.adm_translate("Etat").'</label>
               <div class="col-sm-8">
                  <select class="c-select form-control" name="status">';
   if ($status == 1) {
      $sel_a = 'selected="selected"';
   } else {
      $sel_i = 'selected="selected"';
   }
   echo '
                     <option name="status" value="1" '.$sel_a.'>'.adm_translate("Actif(s)").'</option>
                     <option name="status" value="0" '.$sel_i.'>'.adm_translate("Inactif(s)").'</option>
                  </select>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <input type="hidden" name="op" value="HeadlinesSave" />
               <div class="col-sm-offset-4 col-sm-8">
                  <button class="btn btn-primary col-xs-12" type="submit"><i class="fa fa-edit fa-lg"></i>&nbsp;'.adm_translate("Sauver les modifications").'</button>
               </div>
           </div>
        </div>
      </fieldset>
   </form>';
   
   adminfieldinp($result);
   adminfoot('fv','','','');
}

function HeadlinesSave($hid, $xsitename, $url, $headlinesurl, $status) {
    global $NPDS_Prefix;

    $xsitename = str_replace(' ','',$xsitename);
    sql_query("UPDATE ".$NPDS_Prefix."headlines SET sitename='$xsitename', url='$url', headlinesurl='$headlinesurl', status='$status' WHERE hid='$hid'");
    Header("Location: admin.php?op=HeadlinesAdmin");
}

function HeadlinesAdd($xsitename, $url, $headlinesurl, $status) {
    global $NPDS_Prefix;

    $xsitename = str_replace(' ','',$xsitename);
    sql_query("INSERT INTO ".$NPDS_Prefix."headlines VALUES (NULL, '$xsitename', '$url', '$headlinesurl', '$status')");
    Header("Location: admin.php?op=HeadlinesAdmin");
}

function HeadlinesDel($hid, $ok=0) {
    global $NPDS_Prefix;

    if ($ok==1) {
       sql_query("DELETE FROM ".$NPDS_Prefix."headlines WHERE hid='$hid'");
       Header("Location: admin.php?op=HeadlinesAdmin");
    } else {
       global $hlpfile;
       include("header.php");
       GraphicAdmin($hlpfile);
       echo "<p align=\"center\"><br />";
       echo "<span class=\"rouge\">";
       echo "<b>".adm_translate("Etes-vous sûr de vouloir supprimer cette boÓte de Titres ?")."</b><br /><br /></span>";
    }
    echo "[ <a href=\"admin.php?op=HeadlinesDel&amp;hid=$hid&amp;ok=1\" class=\"rouge\">".adm_translate("Oui")."</a> | <a href=\"admin.php?op=HeadlinesAdmin\" class=\"noir\">".adm_translate("Non")."</a> ]<br /><br />";
    include("footer.php");
}

switch ($op) {
   case "HeadlinesDel":
      HeadlinesDel($hid, $ok);
      break;
   case "HeadlinesAdd":
      HeadlinesAdd($xsitename, $url, $headlinesurl, $status);
      break;
   case "HeadlinesSave":
      HeadlinesSave($hid, $xsitename, $url, $headlinesurl, $status);
      break;
   case "HeadlinesAdmin":
      HeadlinesAdmin();
      break;
   case "HeadlinesEdit":
      HeadlinesEdit($hid);
      break;
}
?>
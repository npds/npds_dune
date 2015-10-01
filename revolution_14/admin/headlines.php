<?php
/************************************************************************/
/* DUNE by NPDS - admin prototype                                       */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2014 by Philippe Brunier                     */
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
   echo'<h3>'.adm_translate("Liste des Grands Titres de sites de News").'</h3>';
   echo'
   <table id="tad_headline" data-toggle="table" data-striped="true" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa">
      <thead>
         <tr>
           <th data-sortable="true" class="">'.adm_translate("ID").'</th>
           <th data-sortable="true" class="">'.adm_translate("Nom du site").'</th>
           <th data-sortable="true" class="">'.adm_translate("URL").'</th>
           <th data-sortable="true" class="">'.adm_translate("Etat").'</th>
           <th class="">'.adm_translate("Fonctions").'</th>
         </tr>
      </thead>
      <tbody>';
   $result = sql_query("select hid, sitename, url, headlinesurl, status from ".$NPDS_Prefix."headlines order by hid");
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
   <h3>'.adm_translate("Nouveau Grand Titre").'</h3>
   <form id="fad_newheadline" class="" action="admin.php" method="post">
      <fieldset>
         <div class="form-group">
            <label class="form-control-label col-sm-4" for="xsitename">'.adm_translate("Nom du site").'</label>
            <div class="col-sm-8">
               <input id="xsitename" class="form-control" type="text" name="xsitename" placeholder="'.adm_translate("Nom du site").'" maxlength="30" required="required" />
            </div>
        </div>
         <div class="form-group">
            <label class="form-control-label col-sm-4" for="url">'.adm_translate("URL").'</label>
            <div class="col-sm-8">
               <input id="url" class="form-control" type="url" name="url" placeholder="'.adm_translate("URL").'" maxlength="100" required="required" />
               <span class="help-block text-right"><span id="countcar_url"></span></span>
            </div>
         </div>
         <div class="form-group">
            <label class="form-control-label col-sm-4" for="headlinesurl">'.adm_translate("URL pour le fichier RDF/XML").'</label>
            <div class="col-sm-8">
                <input id="headlinesurl" class="form-control" type="url" name="headlinesurl" placeholder="'.adm_translate("URL pour le fichier RDF/XML").'" maxlength="200" required="required" />
                <span class="help-block text-right"><span id="countcar_headlinesurl"></span></span>
            </div>
         </div>
         <div class="form-group">
            <label class="form-control-label col-sm-4" for="status">'.adm_translate("Etat").'</label>
            <div class="col-sm-8">
               <select class="form-control" id="status" name="status">
                  <option name="status" value="1">'.adm_translate("Actif(s)").'</option>
                  <option name="status" value="0" selected="selected">'.adm_translate("Inactif(s)").'</option>
               </select>
            </div>
         </div>
         <div class="form-group">
            <div class="col-sm-offset-4 col-sm-8">
               <button class="btn btn-primary col-sm-12" type="submit"><i class="fa fa-plus-square fa-lg">&nbsp;</i>'.adm_translate("Ajouter").'</button>
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
   $result = sql_query("select sitename, url, headlinesurl, status from ".$NPDS_Prefix."headlines where hid='$hid'");
   list($xsitename, $url, $headlinesurl, $status) = sql_fetch_row($result);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '
   <h3>'.adm_translate("Editer paramètres Grand Titre").'</h3>
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
                     <input class="form-control" type="url" name="url" maxlength="100" value="'.$url.'" required="required" />
                     <span class="help-block text-right"><span id="countcar_url"></span></span>
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
                       <button class="btn btn-secondary" ><a href="'.$headlinesurl.'" target="_blank"><i class="fa fa-external-link fa-lg"></i></a></button>
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
                  <select class="form-control" name="status">';
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
   
   $non_user_vars = ["_COOKIE", "_ENV", "_FILES", "_GET", "_POST", "_REQUEST", 
                  "_SERVER", "_SESSION", "argc", "argv", "GLOBALS",
                  "HTTP_RAW_POST_DATA", "http_response_header",
                  "ignore", "php_errormsg"];


//$all_vars = array_keys($GLOBALS);

//$user_vars = array_diff($all_vars, $non_user_vars);

// foreach($user_vars as $variable) {
//     unset($GLOBALS[$variable]);
// }
   
   
   $vars = array_diff(get_defined_vars(),$vars);
   echo '<pre>';
   print_r ( $vars );
   echo '</pre>';

   
   
   adminfieldinp($result);
   adminfoot('fv','','','');
}

function HeadlinesSave($hid, $xsitename, $url, $headlinesurl, $status) {
    global $NPDS_Prefix;

    $xsitename = str_replace(' ','',$xsitename);
    sql_query("update ".$NPDS_Prefix."headlines set sitename='$xsitename', url='$url', headlinesurl='$headlinesurl', status='$status' where hid='$hid'");
    Header("Location: admin.php?op=HeadlinesAdmin");
}

function HeadlinesAdd($xsitename, $url, $headlinesurl, $status) {
    global $NPDS_Prefix;

    $xsitename = str_replace(' ','',$xsitename);
    sql_query("insert into ".$NPDS_Prefix."headlines values (NULL, '$xsitename', '$url', '$headlinesurl', '$status')");
    Header("Location: admin.php?op=HeadlinesAdmin");
}

function HeadlinesDel($hid, $ok=0) {
    global $NPDS_Prefix;

    if ($ok==1) {
       sql_query("delete from ".$NPDS_Prefix."headlines where hid='$hid'");
       Header("Location: admin.php?op=HeadlinesAdmin");
    } else {
       global $hlpfile;
       include("header.php");
       GraphicAdmin($hlpfile);
       opentable();
       echo "<p align=\"center\"><br />";
       echo "<span class=\"rouge\">";
       echo "<b>".adm_translate("Etes-vous sûr de vouloir supprimer cette boîte de Titres ?")."</b><br /><br /></span>";
    }
    echo "[ <a href=\"admin.php?op=HeadlinesDel&amp;hid=$hid&amp;ok=1\" class=\"rouge\">".adm_translate("Oui")."</a> | <a href=\"admin.php?op=HeadlinesAdmin\" class=\"noir\">".adm_translate("Non")."</a> ]<br /><br />";
    closetable();
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
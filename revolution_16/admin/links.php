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
$f_meta_nom ='links';
$f_titre = 'Liens';
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
global $language;
$hlpfile = "manuels/$language/weblinks.html";

// valeur du pas de pagination
$rupture=100;//100

function links() {
   global $NPDS_Prefix, $hlpfile, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);

   $results=sql_query("SELECT * FROM ".$NPDS_Prefix."links_links");
   $numrows = sql_num_rows($results);
   $result = sql_query("SELECT * FROM ".$NPDS_Prefix."links_modrequest WHERE brokenlink=1");
   $totalbrokenlinks = sql_num_rows($result);
   $result2 = sql_query("SELECT * FROM ".$NPDS_Prefix."links_modrequest WHERE brokenlink=0");
   $totalmodrequests = sql_num_rows($result2);

   echo '
   <hr />
   <h3>'.adm_translate("Liens").' <span class="">'.$numrows.'</span></h3>' ;
   echo '[ <a href="admin.php?op=LinksListBrokenLinks">'.adm_translate("Soumission de Liens brisés").' ('.$totalbrokenlinks.')</a> -
   <a href="admin.php?op=LinksListModRequests">'.adm_translate("Proposition de modifications de Liens").' ('.$totalmodrequests.')</a> ]';

   $result = sql_query("SELECT lid, cid, sid, title, url, description, name, email, submitter FROM ".$NPDS_Prefix."links_newlink ORDER BY lid ASC LIMIT 0,1");
   $numrows = sql_num_rows($result);
   $adminform='';
   if ($numrows>0) {
      $adminform='adminForm';
      echo '
   <hr />
   <h3>'.adm_translate("Liens en attente de validation").'</h3>';
       list($lid, $cid, $sid, $title, $url, $xtext, $name, $email, $submitter) = sql_fetch_row($result);
       echo '
   <form action="admin.php" method="post" name="'.$adminform.'" id="linksattente">';
       echo 
       adm_translate("Lien N° : ").'<b>'.$lid.'</b> - '.adm_translate("Auteur").' : '.$submitter.' <br /><br />
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4 " for="titleenattente">'.adm_translate("Titre de la page").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="titleenattente" name="title" value="'.$title.'" maxlength="100" required="required"/>
            <span class="help-block text-end"><span id="countcar_titleenattente"></span></span>

         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4 " for="urlenattente">'.adm_translate("URL de la Page").'</label>
         <div class="col-sm-8">
            <div class="input-group">
               <span class="input-group-text">
                 <a href="'.$url.'" target="_blank"><i class="fas fa-external-link-alt fa-lg"></i></a>
               </span>
               <input class="form-control" id="urlenattente" type="url" name="url" value="'.$url.'" maxlength="255" required="required" />
            </div>
            <span class="help-block text-end"><span id="countcar_urlenattente"></span></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-12 " for="xtextenattente">'.adm_translate("Description").'</label>
         <div class="col-sm-12">
            <textarea class="tin form-control" id="xtextenattente" name="xtext" rows="10">'.$xtext.'</textarea>
         </div>
      </div>
      '.aff_editeur('xtext','').'
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4 " for="nameenattente">'.adm_translate("Nom").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="nameenattente" name="name" maxlength="100" value="'.$name.'" />
            <span class="help-block text-end"><span id="countcar_nameenattente"></span></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4 " for="emailenattente">'.adm_translate("E-mail").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="email" id="emailenattente" name="email" maxlength="100" value="'.$email.'">
            <span class="help-block text-end"><span id="countcar_emailenattente"></span></span>
         </div>
      </div>';
       $result2=sql_query("SELECT cid, title FROM ".$NPDS_Prefix."links_categories ORDER BY title");
       echo '
      <input type="hidden" name="new" value="1">
      <input type="hidden" name="lid" value="'.$lid.'">
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4 " for="catenattente">'.adm_translate("Catégorie").'</label>
         <div class="col-sm-8">
            <select class="form-select" id="catenattente" name="cat">';
       while(list($ccid, $ctitle) = sql_fetch_row($result2)) {
          $sel = '';
          if ($cid==$ccid AND $sid==0)
             $sel = 'selected="selected" ';
          echo '
               <option value="'.$ccid.'" '.$sel.'>'.aff_langue($ctitle).'</option>';
          $result3=sql_query("SELECT sid, title FROM ".$NPDS_Prefix."links_subcategories WHERE cid='$ccid' ORDER BY title");
          while (list($ssid, $stitle) = sql_fetch_row($result3)) {
             $sel = '';
             if ($sid==$ssid)
                $sel = 'selected="selected" ';
             echo '
               <option value="'.$ccid.'-'.$ssid.'" '.$sel.'>'.aff_langue($ctitle).' / '.aff_langue($stitle).'</option>';
          }
       }
       echo '
            </select>
         </div>
      </div>
      <div class="mb-3 row">
         <div class="col-sm-8 ms-sm-auto">
            <input type="hidden" name="submitter" value="'.$submitter.'">
            <input type="hidden" name="op" value="LinksAddLink">
            <input class="btn btn-primary" type="submit" value="'.adm_translate("Ajouter").'" />&nbsp;
            <a class="btn btn-danger" href="admin.php?op=LinksDelNew&amp;lid='.$lid.'" >'.adm_translate("Effacer").'</a>
         </div>
      </div>
   </form>
      <script type="text/javascript">
      //<![CDATA[
         $(document).ready(function() {
            inpandfieldlen("titleenattente",100);
            inpandfieldlen("urlenattente",255);
            inpandfieldlen("nameenattente",100);
            inpandfieldlen("emailenattente",100);
         });
      //]]>
   </script>';
    // Fin de List
   }

   // Add a Link to Database
   $result = sql_query("SELECT cid, title FROM ".$NPDS_Prefix."links_categories");
   $numrows = sql_num_rows($result);
   if ($numrows>0) {
      echo '
   <div class="card card-body mb-3">
   <h3>'.adm_translate("Ajouter un lien").'</h3>';
      if ($adminform=='') {
       echo '<form method="post" action="admin.php" id="addlink" name="adminForm">';
      } else {
       echo '<form method="post" action="admin.php" id="addlink">';
      }
    echo '
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4 " for="title">'.adm_translate("Titre de la page").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" name="title" id="title" maxlength="100" required="required" />
            <span class="help-block text-end"><span id="countcar_title"></span></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4 " for="url">'.adm_translate("URL de la Page").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="url" name="url" id="url" maxlength="255" placeholder="http(s)://" required="required" />
            <span class="help-block text-end"><span id="countcar_url"></span></span>
         </div>
      </div>
      <div class="mb-3 row">';
    $result=sql_query("SELECT cid, title FROM ".$NPDS_Prefix."links_categories ORDER BY title");
    echo '
         <label class="col-form-label col-sm-4" for="cat">'.adm_translate("Catégorie").'</label>
         <div class="col-sm-8">
            <select class="form-select" id="cat" name="cat" id="cat">';
    while(list($cid, $title) = sql_fetch_row($result)) {
      echo '
               <option value="'.$cid.'">'.aff_langue($title).'</option>';
      $result2=sql_query("SELECT sid, title FROM ".$NPDS_Prefix."links_subcategories WHERE cid='$cid' ORDER BY title");
      while(list($sid, $stitle) = sql_fetch_row($result2)) {
         echo '
               <option value="'.$cid.'-'.$sid.'">'.aff_langue($title).' / '.aff_langue($stitle).'</option>';
      }
    }
   echo '
            </select>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="xtext">'.adm_translate("Description").'</label>
         <div class="col-sm-8">
            <textarea class="tin form-control" id="xtext" name="xtext" rows="6"></textarea>
         </div>
      </div>';
   if ($adminform=='') echo aff_editeur('xtext','');
   echo '
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="name">'.adm_translate("Nom").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" name="name" id="name" maxlength="60" />
            <span class="help-block text-end"><span id="countcar_name"></span></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4 " for="email">'.adm_translate("E-mail").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="email" name="email" id="email" maxlength="60" />
            <span class="help-block text-end"><span id="countcar_email"></span></span>
         </div>
      </div>
      <div class="mb-3 row">
         <div class="col-sm-8 ms-sm-auto">
            <input type="hidden" name="op" value="LinksAddLink">
            <input type="hidden" name="new" value="0">
            <input type="hidden" name="lid" value="0">
            <button class="btn btn-primary col-12" type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;'.adm_translate("Ajouter une URL").'</button>
         </div>
      </div>
   </form>
   </div>';
   }
   // Add a Main category
   echo '
   <div class="card card-body mb-3">
      <h3>'.adm_translate("Ajouter une catégorie").'</h3>
      <form action="admin.php" method="post" id="linksaddcat">
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4 " for="cattitle" >'.adm_translate("Nom").'</label>
            <div class="col-sm-8">
               <input class="form-control" type="text" id="cattitle" name="title" maxlength="100" required="required"/>
               <span class="help-block text-end"><span id="countcar_cattitle"></span></span>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4 " for="cdescription">'.adm_translate("Description").'</label>
            <div class="col-sm-8">
               <textarea class="form-control" id="cdescription" name="cdescription" rows="7"></textarea>
            </div>
         </div>
         <div class="mb-3 row">
            <div class="col-sm-8 ms-sm-auto">
               <input type="hidden" name="op" value="LinksAddCat">
               <button class="btn btn-primary col-12" type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;'.adm_translate("Ajouter une catégorie").'</button>
            </div>
         </div>
      </form>
   </div>';

   // Add a New Sub-Category
   $result = sql_query("SELECT * FROM ".$NPDS_Prefix."links_categories");
   $numrows = sql_num_rows($result);
   if ($numrows>0) {
      echo '
   <div class="card card-body mb-3">
      <h3 class="mb-3">'.adm_translate("Ajouter une Sous-catégorie").'</h3>
      <form method="post" action="admin.php" id="linksaddsubcat">
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="subcattitle">'.adm_translate("Nom").'</label>
            <div class="col-sm-8">
               <input class="form-control" type="text" id="subcattitle" name="title" maxlength="100" required="required">
               <span class="help-block text-end"><span id="countcar_subcattitle"></span></span>
            </div>
         </div>';
      $result=sql_query("SELECT cid, title FROM ".$NPDS_Prefix."links_categories ORDER BY title");
      echo '
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="cid">'.adm_translate("Catégorie").'</label>
            <div class="col-sm-8">
               <select class="form-select" name="cid">';
      while(list($ccid, $ctitle) = sql_fetch_row($result)) {
         echo '
                  <option value="'.$ccid.'">'.aff_langue($ctitle).'</option>';
      }
      echo '
               </select>
            </div>
         </div>
         <div class="mb-3 row">
            <div class="col-sm-8 ms-sm-auto">
               <input type="hidden" name="op" value="LinksAddSubCat">
               <button class="btn btn-primary col-12" type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;'.adm_translate("Ajouter une Sous-catégorie").'</button>
            </div>
         </div>
      </form>
   </div>';
   }

   // Modify Category
   $result = sql_query("SELECT * FROM ".$NPDS_Prefix."links_categories");
   $numrows = sql_num_rows($result);
   if ($numrows>0) {
    $result=sql_query("SELECT cid, title FROM ".$NPDS_Prefix."links_categories ORDER BY title");
    echo '
   <div class="card card-body">
      <h3 class="mb-3">'.adm_translate("Modifier la Catégorie").'</h3>
      <form method="post" action="admin.php">
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="cat">'.adm_translate("Catégorie").'</label>
            <div class="col-sm-8">
               <select class="form-select" name="cat">';
    while(list($cid, $title) = sql_fetch_row($result)) {
       echo '
                  <option value="'.$cid.'">'.aff_langue($title).'</option>';
       $result2=sql_query("SELECT sid, title FROM ".$NPDS_Prefix."links_subcategories WHERE cid='$cid' ORDER BY title");
       while(list($sid, $stitle) = sql_fetch_row($result2)) {
          echo '
                  <option value="'.$cid.'-'.$sid.'">'.aff_langue($title).' / '.aff_langue($stitle).'</option>';
       }
    }
    echo '
                </select>
               </div>
            </div>
         <div class="mb-3 row">
            <div class="col-sm-8 ms-sm-auto">
               <input type="hidden" name="op" value="LinksModCat">
               <button class="btn btn-primary col-12" type="submit"><i class="fa fa-edit fa-lg"></i>&nbsp;'.adm_translate("Editer une catégorie").'</button>
            </div>
         </div>
      </form>
   </div>';
   }

   // Modify Links
   $result=sql_query("SELECT lid FROM ".$NPDS_Prefix."links_links");
   $numrow=sql_num_rows($result);
   echo '
   <hr />
   <h3 class="mb-3">'.adm_translate("Liste des liens").' <span class="badge bg-secondary float-end">'.$numrow.'</span></h3>
   <table id="tad_link" data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-buttons-class="outline-secondary" data-icons="icons" data-icons-prefix="fa">
      <thead>
         <tr>
            <th class="n-t-col-xs-1" data-sortable="true" data-halign="center" data-align="right">ID</th>
            <th data-sortable="true" data-halign="center" >'.adm_translate('Titre').'</th>
            <th data-sortable="true" data-sorter="htmlSorter" data-halign="center" >URL</th>
            <th class="n-t-col-xs-2" data-halign="center" data-align="center" data-align="right">'.adm_translate('Fonctions').'</th>
         </tr>
      </thead>
      <tbody>';
   global $rupture,$deja_affiches;
   settype ($deja_affiches, "integer");
   if ($deja_affiches<0) {$sens=-1;} else {$sens=+1;}
   $deja_affiches=abs($deja_affiches);
   $result = sql_query("SELECT lid, title, url FROM ".$NPDS_Prefix."links_links ORDER BY lid ASC LIMIT $deja_affiches,$rupture");
   while (list($lid, $title, $url) = sql_fetch_row($result)) {
    echo '
         <tr>
            <td>'.$lid.'</td>
            <td>'.$title.'</td>
            <td><a href="'.$url.'" target="_blank">'.$url.'</a></td>
            <td>
               <a href="admin.php?op=LinksModLink&amp;lid='.$lid.'" ><i class="fas fa-edit fa-lg"></i></a>
               <a href="admin.php?op=LinksDelLink&amp;lid='.$lid.'" class="text-danger"><i class="fas fa-trash fa-lg ms-3"></i></a>
            </td>
         </tr>';
   }
   echo '
      </tbody>
   </table>';

   $deja_affiches_plus=$deja_affiches+$rupture;
   $deja_affiches_moin=$deja_affiches-$rupture;
   $precedent=false;
   echo '
   <ul class="pagination pagination-sm mt-3">
      <li class="page-item disabled"><a class="page-link" href="#">'.$numrow.'</a></li>';
   if ($deja_affiches>=$rupture) {
    echo '
      <li class="page-item"><a class="page-link" href="admin.php?op=suite_links&amp;deja_affiches=-'.$deja_affiches_moin.'" >'.adm_translate("Précédent").'</a></li>';
    $precedent=true;
   }
   if ($deja_affiches_plus<$numrow) {
    echo '<li class="page-item"><a class="page-link" href="admin.php?op=suite_links&amp;deja_affiches='.$deja_affiches_plus.'" >'.adm_translate("Suivant").'</a></li>';
   }
   echo '
   </ul>';
   $arg1='
      var formulid = ["addlink","linksaddcat","linksaddsubcat"];
      inpandfieldlen("title",100);
      inpandfieldlen("url",255);
      inpandfieldlen("name",100);
      inpandfieldlen("email",100);
      inpandfieldlen("cattitle",100);
      inpandfieldlen("subcattitle",100);
   ';
   adminfoot('fv','',$arg1,'');
}

function LinksModLink($lid) {
   global $NPDS_Prefix, $hlpfile, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   global $anonymous;
   $result = sql_query("SELECT cid, sid, title, url, description, name, email, hits FROM ".$NPDS_Prefix."links_links WHERE lid='$lid'");
   adminhead($f_meta_nom, $f_titre, $adminimg);
   echo '
   <hr />
   <h3 class="mb-3">'.adm_translate("Modifier le lien").' - '.$lid.'</h3>';
    list($cid, $sid, $title, $url, $xtext, $name, $email, $hits) = sql_fetch_row($result);
    $title = stripslashes($title); $xtext = stripslashes($xtext);
    echo '
   <form action="admin.php" method="post" name="adminForm" id="linksmodlink">
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4 " for="title">'.adm_translate("Titre de la page").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" name="title" id="title" value="'.$title.'" maxlength="100" required="required" />
            <span class="help-block text-end"><span id="countcar_title"></span></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4 " for="url">'.adm_translate("URL de la Page").'</label>
         <div class="col-sm-8">
            <div class="input-group">
               <span class="input-group-text">
                 <a href="'.$url.'" target="_blank"><i class="fas fa-external-link-alt fa-lg"></i></a>
               </span>
               <input class="form-control" type="url" name="url" id="url" value="'.$url.'" maxlength="255" required="required" />
             </div>
             <span class="help-block text-end"><span id="countcar_url"></span></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4 " for="xtext">'.adm_translate("Description").'</label>
         <div class="col-sm-8">
            <textarea class="form-control tin" id="xtext" name="xtext" rows="10">'.$xtext.'</textarea>
         </div>
      </div>';
    echo aff_editeur('xtext','');
    echo '
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4 " for="name">'.adm_translate("Nom").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" name="name" id="name" maxlength="100" value="'.$name.'" />
            <span class="help-block text-end"><span id="countcar_name"></span></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4 " for="email">'.adm_translate("E-mail").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="email" name="email" id="email" maxlength="100" value="'.$email.'" />
            <span class="help-block text-end"><span id="countcar_email"></span></span>
         </div>
      </div>
      <div class="mb-3">
         <div class="row">
            <label class="col-form-label col-sm-4 " for="hits">'.adm_translate("Nombre de Hits").'</label>
            <div class="col-sm-8">
               <input class="form-control" type="number" id="hits" name="hits" value="'.$hits.'" min="0" max="99999999999" />
            </div>
         </div>
      </div>
      <div class="mb-3 row">';
    $result2=sql_query("SELECT cid, title FROM ".$NPDS_Prefix."links_categories ORDER BY title");
    echo '
         <input type="hidden" name="lid" value="'.$lid.'" />
         <label class="col-form-label col-sm-4 " for="cat">'.adm_translate("Catégorie").'</label>
         <div class="col-sm-8">
            <select class="form-select" id="cat" name="cat">';
    while (list($ccid, $ctitle) = sql_fetch_row($result2)) {
       $sel = "";
       if ($cid==$ccid AND $sid==0) {
          $sel = "selected";
       }
       echo '
               <option value="'.$ccid.'" '.$sel.'>'.aff_langue($ctitle).'</option>';
       $result3=sql_query("SELECT sid, title FROM ".$NPDS_Prefix."links_subcategories WHERE cid='$ccid' ORDER BY title");
       while (list($ssid, $stitle) = sql_fetch_row($result3)) {
          $sel = '';
          if ($sid==$ssid) {
             $sel = 'selected';
          }
          echo '
               <option value="'.$ccid.'-'.$ssid.'" $sel>'.aff_langue($ctitle).' / '.aff_langue($stitle).'</option>';
       }
    }

    echo '
            </select>
         </div>
      </div>
      <div class="mb-3 row">
         <div class="col-sm-8 ms-sm-auto">
            <input type="hidden" name="op" value="LinksModLinkS" />
            <button type="submit" class="btn btn-primary" ><i class="fa fa-check fa-lg"></i>&nbsp;'.adm_translate("Modifier").' </button>
            <a href="admin.php?op=LinksDelLink&amp;lid='.$lid.'" class="btn btn-danger"><i class="fas fa-trash fa-lg"></i>&nbsp;'.adm_translate("Effacer").'</a>
         </div>
      </div>
   </form>';

   //Modify or Add Editorial
   $resulted2 = sql_query("SELECT adminid, editorialtimestamp, editorialtext, editorialtitle FROM ".$NPDS_Prefix."links_editorials WHERE linkid='$lid'");
   $recordexist = sql_num_rows($resulted2);
   if ($recordexist == 0) {
      echo '
   <h3 class="mb-3">'.adm_translate("Ajouter un Editorial").'</h3>
   <form action="admin.php" method="post" id="linkseditorial">
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4 " for="editorialtitle">'.adm_translate("Titre").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" name="editorialtitle" id="editorialtitle" maxlength="100" />
            <span class="help-block text-end"><span id="countcar_editorialtitle"></span></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4 " for="editorialtext">'.adm_translate("Texte complet").'</label>
         <div class="col-sm-8">
            <textarea class="form-control" id="editorialtext" name="editorialtext" rows="10"></textarea>
         </div>
      </div>
      <div class="mb-3 row">
         <div class="col-sm-8 ms-sm-auto">
            <input type="hidden" name="linkid" value="'.$lid.'" />
            <input type="hidden" name="op" value="LinksAddEditorial" />
            <button class="btn btn-primary col-12" type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;'.adm_translate("Ajouter un Editorial").'</button>
         </div>
      </div>';
   } else {
      while(list($adminid, $editorialtimestamp, $editorialtext, $editorialtitle) = sql_fetch_row($resulted2)) {
         $editorialtitle = stripslashes($editorialtitle);
         $editorialtext = stripslashes($editorialtext);

         echo '
   <h3 class="mb-3">'.adm_translate("Modifier l'Editorial").'</h3> - '.adm_translate("Auteur").' : '.$adminid.' : '.formatTimes($editorialtimestamp, IntlDateFormatter::FULL, IntlDateFormatter::SHORT);
         echo '
   <form action="admin.php" method="post" id="linkseditorial">
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4 " for="editorialtitle">'.adm_translate("Titre").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" name="editorialtitle" id="editorialtitle" value="'.$editorialtitle.'" maxlength="100" />
            <span class="help-block text-end"><span id="countcar_editorialtitle"></span></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4 " for="editorialtext">'.adm_translate("Texte complet").'</label>
         <div class="col-sm-8">
            <textarea class="form-control" id="editorialtext" name="editorialtext" rows="10">'.$editorialtext.'</textarea>
         </div>
      </div>
      <div class="mb-3 row">
         <div class="col-sm-8 ms-sm-auto">
            <input type="hidden" name="linkid" value="'.$lid.'" />
            <input type="hidden" name="op" value="LinksModEditorial" />
            <button class="btn btn-primary" type="submit"><i class="fa fa-check fa-lg"></i>&nbsp;'.adm_translate("Modifier").'</button>
            <button href="admin.php?op=LinksDelEditorial&amp;linkid='.$lid.'" class="btn btn-danger"><i class="fas fa-trash fa-lg"></i>&nbsp;'.adm_translate("Effacer").'</button>
         </div>
      </div>';
      }
    }
    $fv_parametres='
/*    
    email: {
                    validators: {
                        emailAddress: {
                            message: "The value is not a valid email address"
                        }
                    }
                },
      title: {
         validators: {
               notEmpty: {
                  message: "pas vide."
            }
         }
      },

       url: {
         validators: {
               uri: {
                  allowLocal: false,
                  message: "valid url please.",
                  allowEmptyProtocol:false,
            }
         }
      },
*/
    
    ';
    $arg1='
      var formulid = ["linksmodlink","linkseditorial"];
      inpandfieldlen("title",100);
      inpandfieldlen("url",255);
      inpandfieldlen("editorialtitle",100);
';
   echo '
   </form>';
   adminfoot('fv',$fv_parametres,$arg1,'');
}

function LinksListBrokenLinks() {
   global $NPDS_Prefix, $hlpfile, $anonymous, $f_meta_nom, $f_titre, $adminimg;
   $resultBrok = sql_query("SELECT requestid, lid, modifysubmitter FROM ".$NPDS_Prefix."links_modrequest WHERE brokenlink='1' ORDER BY requestid");
   $totalbrokenlinks = sql_num_rows($resultBrok);
   if ($totalbrokenlinks==0)
      header("location: admin.php?op=links");
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead($f_meta_nom, $f_titre, $adminimg);

   echo '
   <hr />
   <h3>'.adm_translate("Liens cassés rapportés par un ou plusieurs Utilisateurs").' <span class="badge bg-danger float-end">'.$totalbrokenlinks.'</span></h3>
   <div class="blockquote">
      <i class="fas fa-trash fa-lg text-primary me-2"></i>'.adm_translate("Ignorer (Efface toutes les demandes pour un Lien donné)").'<br />
      <i class="fas fa-trash fa-lg text-danger me-2"></i>'.adm_translate("Effacer (Efface les Liens cassés et les avis pour un Lien donné)").'
   </div>';

   if ($totalbrokenlinks==0) {
      echo '
   <div class="alert alert-success lead">'.adm_translate("Aucun lien brisé rapporté.").'</div>';
   } else {
      echo '
   <table id="tad_linkbrok" data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-buttons-class="outline-secondary" data-icons="icons" data-icons-prefix="fa">
      <thead>
         <tr>
            <th class="n-t-col-xs-4" data-sortable="true" data-halign="center" >'.adm_translate("Liens").'</th>
            <th data-sortable="true" data-halign="center" >'.adm_translate("Auteur").'</th>
            <th data-sortable="true" data-halign="center" >'.adm_translate("Propriétaire").'</th>
            <th class="n-t-col-xs-1" data-halign="center" data-align="center" >'.adm_translate("Ignorer").'</th>
            <th class="n-t-col-xs-1" data-halign="center" data-align="center" >'.adm_translate("Effacer").'</th>
         </tr>
      </thead>
      <tbody>';
      while (list($requestid, $lid, $modifysubmitter)=sql_fetch_row($resultBrok)) {
         $result2 = sql_query("SELECT title, url, submitter FROM ".$NPDS_Prefix."links_links WHERE lid='$lid'");
         if ($modifysubmitter != $anonymous) {
            $result3 = sql_query("SELECT email FROM ".$NPDS_Prefix."users WHERE uname='$modifysubmitter'");
            list ($email)=sql_fetch_row($result3);
         }
         list ($title, $url, $owner)=sql_fetch_row($result2);
         $result4 = sql_query("SELECT email FROM ".$NPDS_Prefix."users WHERE uname='$owner'");
         list($owneremail)=sql_fetch_row($result4);
         echo '
         <tr>
            <td><div>'.$title.'&nbsp;<span class="float-end"><a href="'.$url.'" target="_blank" ><i class="fas fa-external-link-alt fa-lg"></i></a></span></div></td>';
         if ($email=='')
            echo '
            <td>'.$modifysubmitter;
         else
            echo '
            <td><div>'.$modifysubmitter.'&nbsp;<span class="float-end"><a href="mailto:'.$email.'" ><i class="fa fa-at fa-lg"></i></a></span></div>';
         echo '</td>';
         if ($owneremail=='')
            echo '
             <td>'.$owner;
         else
            echo '
             <td><div>'.$owner.'&nbsp;<span class="float-end"><a href="mailto:'.$owneremail.'"><i class="fa fa-at fa-lg"></i></a></span></div>';
         echo '
            </td>
            <td><a href="admin.php?op=LinksIgnoreBrokenLinks&amp;lid='.$lid.'" ><i class="fas fa-trash fa-lg" title="'.adm_translate("Ignorer (Efface toutes les demandes pour un Lien donné)").'" data-bs-toggle="tooltip" data-bs-placement="left"></i></a></td>
            <td><a href=admin.php?op=LinksDelBrokenLinks&amp;lid='.$lid.'" ><i class="fas fa-trash text-danger fa-lg" title="'.adm_translate("Effacer (Efface les Liens cassés et les avis pour un Lien donné)").'" data-bs-toggle="tooltip" data-bs-placement="left"></i></a></td>
         </tr>';
      }
   }
   echo '
      </tbody>
   </table>';
   adminfoot('','','','');
}

function LinksDelBrokenLinks($lid) {
   global $NPDS_Prefix;
   sql_query("DELETE FROM ".$NPDS_Prefix."links_modrequest WHERE lid='$lid'");
   sql_query("DELETE FROM ".$NPDS_Prefix."links_links WHERE lid='$lid'");
   global $aid; Ecr_Log('security', "DeleteBrokensLinks($lid) by AID : $aid", '');
   Header("Location: admin.php?op=LinksListBrokenLinks");
}

function LinksIgnoreBrokenLinks($lid) {
   global $NPDS_Prefix;
   sql_query("DELETE FROM ".$NPDS_Prefix."links_modrequest WHERE lid='$lid' AND brokenlink='1'");
   Header("Location: admin.php?op=LinksListBrokenLinks");
}

function LinksListModRequests() {
   global $NPDS_Prefix, $hlpfile;
   $resultLink = sql_query("SELECT requestid, lid, cid, sid, title, url, description, modifysubmitter FROM ".$NPDS_Prefix."links_modrequest WHERE brokenlink='0' ORDER BY requestid");
   $totalmodrequests = sql_num_rows($resultLink);
   if ($totalmodrequests==0)
      header("location: admin.php?op=links");
   include ("header.php");
   $x_mod='';$x_ori='';
   function clformodif($x_ori,$x_mod){
      if ($x_ori != $x_mod) return ' class="text-danger" ';
   }
   GraphicAdmin($hlpfile);
   echo '<h3 class="my-3">'.adm_translate("Requête de modification d'un Lien Utilisateur").'<span class="badge bg-danger float-end">'.$totalmodrequests.'</span></h3>';
   while(list($requestid, $lid, $cid, $sid, $title, $url, $description, $modifysubmitter)=sql_fetch_row($resultLink)) {
      $result2 = sql_query("SELECT cid, sid, title, url, description, submitter FROM ".$NPDS_Prefix."links_links WHERE lid='$lid'");
      list($origcid, $origsid, $origtitle, $origurl, $origdescription, $owner)=sql_fetch_row($result2);
      $result3 = sql_query("SELECT title FROM ".$NPDS_Prefix."links_categories WHERE cid='$cid'");
      $result4 = sql_query("SELECT title FROM ".$NPDS_Prefix."links_subcategories WHERE cid='$cid' AND sid='$sid'");
      $result5 = sql_query("SELECT title FROM ".$NPDS_Prefix."links_categories WHERE cid='$origcid'");
      $result6 = sql_query("SELECT title FROM ".$NPDS_Prefix."links_subcategories WHERE cid='$origcid' AND sid='$origsid'");
      $result7 = sql_query("SELECT email FROM ".$NPDS_Prefix."users WHERE uname='$modifysubmitter'");
      $result8 = sql_query("SELECT email FROM ".$NPDS_Prefix."users WHERE uname='$owner'");
      list($cidtitle)=sql_fetch_row($result3);
      list($sidtitle)=sql_fetch_row($result4);
      list($origcidtitle)=sql_fetch_row($result5);
      list($origsidtitle)=sql_fetch_row($result6);
      list($modifysubmitteremail)=sql_fetch_row($result7);
      list($owneremail)=sql_fetch_row($result8);
      $title = stripslashes($title);
      $description = stripslashes($description);
      if ($owner=='') $owner='administration';
      if ($origsidtitle=='') $origsidtitle= '-----';
      if ($sidtitle=='') $sidtitle= '-----';
      
      echo '
   <div class="card-deck-wrapper mt-3">
     <div class="card-deck">
       <div class="card card-body">
         <h4 class="card-title">'.adm_translate("Original").'</h4>
         <div class="card-text">
            <strong>'.adm_translate("Description:").'</strong> <div>'.$origdescription.'</div>
            <strong>'.adm_translate("Titre :").'</strong> '.$origtitle.'<br />
            <strong>'.adm_translate("URL : ").'</strong> <a href="'.$origurl.'" target="_blank" >'.$origurl.'</a><br />';
      global $links_topic;
      if ($links_topic)
         echo '
            <strong>'.adm_translate("Topic").' :</strong> '.$oritopic.'<br />';
      echo '
            <strong>'.adm_translate("Catégorie :").'</strong> '.$origcidtitle.'<br />
            <strong>'.adm_translate("Sous-catégorie :").'</strong> '.$origsidtitle.'<br />';

      if ($owneremail=='')
         echo '
            <strong>'.adm_translate("Propriétaire").':</strong> <span'.clformodif($origsidtitle,$sidtitle).'>'.$owner.'</span><br />';
            else
         echo '
            <strong>'.adm_translate("Propriétaire").':</strong> <a href="mailto:'.$owneremail.'">'.$owner.'</a></span><br />';

      echo '
         </div>
      </div>
      <div class="card card-body border-danger">
         <h4 class="card-title">'.adm_translate("Proposé").'</h4>
         <div class="card-text">
            <strong>'.adm_translate("Description:").'</strong><div'.clformodif($origdescription,$description).'>'.$description.'</div>
            <strong>'.adm_translate("Titre :").'</strong> <span'.clformodif($origtitle,$title).'>'.$title.'</span><br />
            <strong>'.adm_translate("URL : ").'</strong> <span'.clformodif($origurl,$url).'><a href="'.$url.'" target="_blank" >'.$url.'</a></span><br />';
      global $links_topic;
      if ($links_topic)
         echo '
            <strong>'.adm_translate("Topic").' :</strong> '.$oritopic.'<br />';
      echo '
             <strong>'.adm_translate("Catégorie :").'</strong> <span'.clformodif($origcidtitle,$cidtitle).'>'.$cidtitle.'</span><br />
             <strong>'.adm_translate("Sous-catégorie :").'</strong> <span'.clformodif($origsidtitle,$sidtitle).'>'.$sidtitle.'</span><br />';
       if ($owneremail=='')
         echo '
            <strong>'.adm_translate("Propriétaire").' : </strong> <span>'.$owner.'</span><br />';
       else
         echo '
            <strong>'.adm_translate("Propriétaire").' :  </strong> <span><a href="mailto:'.$owneremail.'" >'.$owner.'</span><br />';
      echo '
         </div>
      </div>
   </div>';
       if ($modifysubmitteremail=='')
          echo adm_translate("Auteur").' : '.$modifysubmitter;
       else
          echo adm_translate("Auteur").' : <a href="mailto:'.$modifysubmitteremail.'">'.$modifysubmitter.'</a>';

    echo '
       <div class="mb-3">
          <a href="admin.php?op=LinksChangeModRequests&amp;requestid='.$requestid.'" class="btn btn-primary btn-sm">'.adm_translate("Accepter").'</a>
          <a href="admin.php?op=LinksChangeIgnoreRequests&amp;requestid='.$requestid.'" class="btn btn-secondary btn-sm">'.adm_translate("Ignorer").'</a>
      </div>
   </div>';

    }
    include ("footer.php");
}

function LinksChangeModRequests($Xrequestid) {
   global $NPDS_Prefix;
   $result = sql_query("SELECT requestid, lid, cid, sid, title, url, description FROM ".$NPDS_Prefix."links_modrequest WHERE requestid='$Xrequestid'");
   while (list($requestid, $lid, $cid, $sid, $title, $url, $description)=sql_fetch_row($result)) {
      $title = stripslashes($title);
      $description = stripslashes($description);
      sql_query("UPDATE ".$NPDS_Prefix."links_links SET cid='$cid', sid='$sid', title='$title', url='$url', description='$description' WHERE lid = '$lid'");
   }
   sql_query("DELETE FROM ".$NPDS_Prefix."links_modrequest WHERE requestid='$Xrequestid'");

   global $aid; Ecr_Log('security', "UpdateModRequestLinks($Xrequestid) by AID : $aid", '');
   Header("Location: admin.php?op=LinksListModRequests");
}

function LinksChangeIgnoreRequests($requestid) {
   global $NPDS_Prefix;
   sql_query("DELETE FROM ".$NPDS_Prefix."links_modrequest WHERE requestid='$requestid'");
   Header("Location: admin.php?op=LinksListModRequests");
}

function LinksModLinkS($lid, $title, $url, $xtext, $name, $email, $hits, $cat) {
   global $NPDS_Prefix;
   $cat = explode('-', $cat);
   if (!array_key_exists(1,$cat))
      $cat[1] = 0;
   $title = stripslashes(FixQuotes($title));
   $url = stripslashes(FixQuotes($url));
   $xtext = stripslashes(FixQuotes($xtext));
   $name = stripslashes(FixQuotes($name));
   $email = stripslashes(FixQuotes($email));
   sql_query("UPDATE ".$NPDS_Prefix."links_links SET cid='$cat[0]', sid='$cat[1]', title='$title', url='$url', description='$xtext', name='$name', email='$email', hits='$hits' WHERE lid='$lid'");
   global $aid; Ecr_Log('security', "UpdateLinks($lid, $title) by AID : $aid", '');
   Header("Location: admin.php?op=links");
}

function LinksDelLink($lid) {
   global $NPDS_Prefix;
   sql_query("DELETE FROM ".$NPDS_Prefix."links_links WHERE lid='$lid'");
   global $aid; Ecr_Log('security', "DeleteLinks($lid) by AID : $aid", '');
   Header("Location: admin.php?op=links");
}

function LinksModCat($cat) {
   global $NPDS_Prefix, $hlpfile, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   $cat = explode('-', $cat);
   if (!array_key_exists(1,$cat))
      $cat[1] = 0;
   adminhead($f_meta_nom, $f_titre, $adminimg);
   if ($cat[1]==0) {
      echo '
        <hr />
        <h3>'.adm_translate("Modifier la Catégorie").'</h3>';
        $result=sql_query("SELECT title, cdescription FROM ".$NPDS_Prefix."links_categories WHERE cid='$cat[0]'");
        list($title,$cdescription) = sql_fetch_row($result);
        $cdescription = stripslashes($cdescription);
      echo '
   <form action="admin.php" method="get" id="linksmodcat">
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4 " for="title">'.adm_translate("Nom").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="title" name="title" value="'.$title.'" maxlength="255" required="required" />
            <span class="help-block text-end"><span id="countcar_title"></span></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4 " for="cdescription">'.adm_translate("Description").'</label>
         <div class="col-sm-8">
            <textarea class="form-control" id="cdescription" name="cdescription" rows="10" >'.$cdescription.'</textarea>
         </div>
      </div>
      <div class="mb-3 row">
         <div class="col-sm-8 ms-sm-auto">
            <input type="hidden" name="sub" value="0">
            <input type="hidden" name="cid" value="'.$cat[0].'">
            <input type="hidden" name="op" value="LinksModCatS">
            <button class="btn btn-primary" type="submit"><i class="fa fa-check fa-lg"></i>&nbsp;'.adm_translate("Modifier").'</button>
            <a href="admin.php?op=LinksDelCat&amp;sub=0&amp;cid='.$cat[0].'" class="btn btn-danger"><i class="fas fa-trash fa-lg"></i>&nbsp;'.adm_translate("Effacer").'</a>
         </div>
      </div>
   </form>';
   } else {
      $result=sql_query("SELECT title FROM ".$NPDS_Prefix."links_categories WHERE cid='$cat[0]'");
      list($ctitle) = sql_fetch_row($result);
      $result2=sql_query("SELECT title FROM ".$NPDS_Prefix."links_subcategories WHERE sid='$cat[1]'");
      list($stitle) = sql_fetch_row($result2);
      echo '
   <hr />
   <h3>'.adm_translate("Modifier la Catégorie").' </h3>
   <p class="lead">'.adm_translate("Nom de la Catégorie : ").aff_langue($ctitle).'</p>
   <form action="admin.php" method="get" id="linksmodcat">
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4 " for="title">'.adm_translate("Nom de la Sous-catégorie").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="title" name="title" value="'.$stitle.'" maxlength="255" required="required">
            <span class="help-block text-end"><span id="countcar_title"></span></span>
         </div>
      </div>
      <div class="mb-3 row">
         <div class="col-sm-8 offset-sm-4">
            <input type="hidden" name="sub" value="1">
            <input type="hidden" name="cid" value="'.$cat[0].'">
            <input type="hidden" name="sid" value="'.$cat[1].'">
            <input type="hidden" name="op" value="LinksModCatS">
            <button class="btn btn-primary" type="submit"><i class="fa fa-check fa-lg"></i>&nbsp;'.adm_translate("Modifier").'</button>
            <a href="admin.php?op=LinksDelCat&amp;sub=1&amp;cid='.$cat[0].'&amp;sid='.$cat[1].'" class="btn btn-danger"><i class="fas fa-trash fa-lg"></i>&nbsp;'.adm_translate("Effacer").'</a>
         </div>
      </div>
   </form>';
   }
   $arg1='
      var formulid = ["linksmodcat"];
      inpandfieldlen("title",255);
   ';
   adminfoot('fv','',$arg1,'');
}

function LinksModCatS($cid, $sid, $sub, $title, $cdescription) {
   global $NPDS_Prefix;
   if ($sub==0) {
      sql_query("UPDATE ".$NPDS_Prefix."links_categories SET title='$title', cdescription='$cdescription' WHERE cid='$cid'");
      global $aid; Ecr_Log('security', "UpdateCatLinks($cid, $title) by AID : $aid", '');
   } else {
      sql_query("UPDATE ".$NPDS_Prefix."links_subcategories SET title='$title' WHERE sid='$sid'");
      global $aid; Ecr_Log('security', "UpdateSubCatLinks($cid, $title) by AID : $aid", '');
   }
   Header("Location: admin.php?op=links");
}

function LinksDelCat($cid, $sid, $sub, $ok=0) {
   global $NPDS_Prefix;
   if ($ok==1) {
      if ($sub>0) {
         sql_query("DELETE FROM ".$NPDS_Prefix."links_subcategories WHERE sid='$sid'");
         sql_query("DELETE FROM ".$NPDS_Prefix."links_links WHERE sid='$sid'");
         global $aid; Ecr_Log('security', "DeleteSubCatLinks($sid) by AID : $aid", '');
      } else {
         sql_query("DELETE FROM ".$NPDS_Prefix."links_categories WHERE cid='$cid'");
         sql_query("DELETE FROM ".$NPDS_Prefix."links_subcategories WHERE cid='$cid'");
         sql_query("DELETE FROM ".$NPDS_Prefix."links_links WHERE cid='$cid' AND sid=0");
         global $aid; Ecr_Log('security', "DeleteCatLinks($cid) by AID : $aid", '');
      }
   Header("Location: admin.php?op=links");
   } else
      message_error('<div class="alert alert-danger">'.adm_translate("ATTENTION : Etes-vous sûr de vouloir effacer cette Catégorie et tous ses Liens ?").'</div><br />
      <a class="btn btn-danger me-2" href="admin.php?op=LinksDelCat&amp;cid='.$cid.'&amp;sid='.$sid.'&amp;sub='.$sub.'&amp;ok=1" >'.adm_translate("Oui").'</a>');
}

function LinksDelNew($lid) {
   global $NPDS_Prefix;
   sql_query("DELETE FROM ".$NPDS_Prefix."links_newlink WHERE lid='$lid'");
   global $aid; Ecr_Log('security', "DeleteNewLinks($lid) by AID : $aid", '');
   Header("Location: admin.php?op=links");
}

function LinksAddCat($title, $cdescription) {
   global $NPDS_Prefix;
   $result = sql_query("SELECT cid FROM ".$NPDS_Prefix."links_categories WHERE title='$title'");
   $numrows = sql_num_rows($result);
   if ($numrows>0)
        message_error('<div class="alert alert-danger"><strong>'.adm_translate("Erreur : La Catégorie")." $title ".adm_translate("existe déjà !").'</strong></div>');
   else {
      sql_query("INSERT INTO ".$NPDS_Prefix."links_categories VALUES (NULL, '$title', '$cdescription')");
      global $aid; Ecr_Log('security', "AddCatLinks($title) by AID : $aid", '');
      Header("Location: admin.php?op=links");
   }
}

function LinksAddSubCat($cid, $title) {
   global $NPDS_Prefix;
   $result = sql_query("SELECT cid FROM ".$NPDS_Prefix."links_subcategories WHERE title='$title' AND cid='$cid'");
   $numrows = sql_num_rows($result);
   if ($numrows>0)
      message_error('<div class="alert alert-danger"><strong>'.adm_translate("Erreur : La Sous-catégorie")." $title ".adm_translate("existe déjà !").'</strong></div>');
   else {
      sql_query("INSERT INTO ".$NPDS_Prefix."links_subcategories VALUES (NULL, '$cid', '$title')");
      global $aid; Ecr_Log('security', "AddSubCatLinks($title) by AID : $aid", '');
      Header("Location: admin.php?op=links");
   }
}

function LinksAddEditorial($linkid, $editorialtitle, $editorialtext) {
   global $NPDS_Prefix, $aid;
   $editorialtext = stripslashes(FixQuotes($editorialtext));
   sql_query("INSERT INTO ".$NPDS_Prefix."links_editorials VALUES ('$linkid', '$aid', now(), '$editorialtext', '$editorialtitle')");
   Ecr_Log('security', "AddEditorialLinks($linkid, $editorialtitle) by AID : $aid", '');
   message_error('<div class="alert alert-success"><strong>'.adm_translate("Editorial ajouté à la base de données").'</strong></div>');
}

function LinksModEditorial($linkid, $editorialtitle, $editorialtext) {
   global $NPDS_Prefix;
   $editorialtext = stripslashes(FixQuotes($editorialtext));
   sql_query("UPDATE ".$NPDS_Prefix."links_editorials SET editorialtext='$editorialtext', editorialtitle='$editorialtitle' WHERE linkid='$linkid'");
   global $aid; Ecr_Log('security', "ModEditorialLinks($linkid, $editorialtitle) by AID : $aid", '');
   message_error('<div class="alert alert-success"><strong>'.adm_translate("Editorial modifié").'</strong></div>');
}

function LinksDelEditorial($linkid) {
   global $NPDS_Prefix;
   sql_query("DELETE FROM ".$NPDS_Prefix."links_editorials WHERE linkid='$linkid'");
   global $aid; Ecr_Log('security', "DeteteEditorialLinks($linkid) by AID : $aid", '');
   message_error('<div class="alert alert-success"><strong>'.adm_translate("Editorial supprimé de la base de données").'</strong></div>');
}

function message_error($ibid) {
   global $hlpfile,$f_meta_nom, $f_titre, $adminimg;
   include("header.php");
   GraphicAdmin($hlpfile);
   adminhead($f_meta_nom, $f_titre, $adminimg);
   echo '<hr />';
   echo $ibid;
   echo '<a href="admin.php?op=links" class="btn btn-secondary">'.adm_translate("Retour en arrière").'</a>';
   adminfoot('','','','');
}

function LinksAddLink($new, $lid, $title, $url, $cat, $xtext, $name, $email, $submitter) {
   global $NPDS_Prefix;
   $result = sql_query("SELECT url FROM ".$NPDS_Prefix."links_links WHERE url='$url'");
   $numrows = sql_num_rows($result);
   if ($numrows>0)
        message_error('<div class="alert alert-danger"><strong>'.adm_translate("Erreur : cette URL est déjà présente dans la base de données !").'</strong></div>');
   else {
      if ($title=='')
         message_error('<div class="alert alert-danger"><strong>'.adm_translate("Erreur : vous devez saisir un TITRE pour votre Lien !").'</strong></div>');
      if ($url=='')
         message_error('<div class="alert alert-danger"><strong>'.adm_translate("Erreur : vous devez saisir une URL pour votre Lien !").'</strong></div>');
      if ($xtext=='')
          message_error('<div class="alert alert-danger"><strong>'.adm_translate("Erreur : vous devez saisir une DESCRIPTION pour votre Lien !").'</strong></div>');
      $cat = explode('-', $cat);
      if (!array_key_exists(1,$cat))
         $cat[1] = 0;
      $title = stripslashes(FixQuotes($title));
      $url = stripslashes(FixQuotes($url));
      $xtext = stripslashes(FixQuotes($xtext));
      $name = stripslashes(FixQuotes($name));
      $email = stripslashes(FixQuotes($email));
      sql_query("INSERT INTO ".$NPDS_Prefix."links_links VALUES (NULL, '$cat[0]', '$cat[1]', '$title', '$url', '$xtext', now(), '$name', '$email', '0','$submitter',0,0,0,0)");
      if ($new==1) {
         sql_query("DELETE FROM ".$NPDS_Prefix."links_newlink WHERE lid='$lid'");
         if ($email!='') {
            global $sitename, $nuke_url;
            $subject = html_entity_decode(adm_translate("Votre Lien"),ENT_COMPAT | ENT_HTML401,'UTF-8')." : $sitename";
            $message = adm_translate("Bonjour")." $name :\n\n".adm_translate("Nous avons approuvé votre contribution à notre moteur de recherche.")."\n\n".adm_translate("Titre de la page")." : $title\n".adm_translate("URL de la Page : ")."<a href=\"$url\">$url</a>\n".adm_translate("Description : ")."$xtext\n".adm_translate("Vous pouvez utiliser notre moteur de recherche sur : ")." <a href=\"$nuke_url/modules.php?ModPath=links&ModStart=links\">$nuke_url/modules.php?ModPath=links&ModStart=links</a>\n\n".adm_translate("Merci pour votre Contribution !")."\n";
            include("signat.php");
            send_email($email, $subject, $message, '', false, 'html', '');
         }
      }
      global $aid; Ecr_Log('security', "AddLinks($title) by AID : $aid", '');
      message_error('<div class="alert alert-success"><strong>'.adm_translate("Nouveau Lien ajouté dans la base de données").'</strong></div>');
   }
}

settype($op,'string');
settype($sid,'integer');
settype($ok,'integer');

switch ($op) {
   case 'links':
   case 'suite_links':
      links();
   break;
   case 'LinksDelNew':
      LinksDelNew($lid);
   break;
   case 'LinksAddCat':
      LinksAddCat($title, $cdescription);
   break;
   case 'LinksAddSubCat':
      LinksAddSubCat($cid, $title);
   break;
   case 'LinksAddLink':
      LinksAddLink($new, $lid, $title, $url, $cat, $xtext, $name, $email, $submitter);
   break;
   case 'LinksAddEditorial':
      LinksAddEditorial($linkid, $editorialtitle, $editorialtext);
   break;
   case 'LinksModEditorial':
      LinksModEditorial($linkid, $editorialtitle, $editorialtext);
   break;
   case 'LinksDelEditorial':
      LinksDelEditorial($linkid);
   break;
   case 'LinksListBrokenLinks':
      LinksListBrokenLinks();
   break;
   case 'LinksDelBrokenLinks':
      LinksDelBrokenLinks($lid);
   break;
   case 'LinksIgnoreBrokenLinks':
      LinksIgnoreBrokenLinks($lid);
   break;
   case 'LinksListModRequests':
      LinksListModRequests();
   break;
   case 'LinksChangeModRequests':
      LinksChangeModRequests($requestid);
   break;
   case 'LinksChangeIgnoreRequests':
      LinksChangeIgnoreRequests($requestid);
   break;
   case 'LinksDelCat':
      LinksDelCat($cid, $sid, $sub, $ok);
   break;
   case 'LinksModCat':
      LinksModCat($cat);
   break;
   case 'LinksModCatS':
      LinksModCatS($cid, $sid, $sub, $title, $cdescription);
   break;
   case 'LinksModLink':
      LinksModLink($lid);
   break;
   case 'LinksModLinkS':
      LinksModLinkS($lid, $title, $url, $xtext, $name, $email, $hits, $cat);
   break;
   case 'LinksDelLink':
      LinksDelLink($lid);
   break;
}
?>
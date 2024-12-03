<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
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
$f_meta_nom ='lnl';
$f_titre = adm_translate("Petite Lettre D'information");
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
global $language, $NPDS_Prefix;
$hlpfile = "manuels/$language/lnl.html";

$rowH = array();
$result = sql_query("SELECT ref, text, html FROM ".$NPDS_Prefix."lnl_head_foot WHERE type='HED' ORDER BY ref ");
while ($row=sql_fetch_assoc($result)) {$rowH[] = $row;}
sql_free_result($result);
$rowB = array();
$result = sql_query("SELECT ref, text, html FROM ".$NPDS_Prefix."lnl_body ORDER BY ref ");
while ($row=sql_fetch_assoc($result)) {$rowB[] = $row;}
sql_free_result($result);
$rowF = array();
$result = sql_query("SELECT ref, text, html FROM ".$NPDS_Prefix."lnl_head_foot WHERE type='FOT' ORDER BY ref ");
while ($row=sql_fetch_assoc($result)) {$rowF[] = $row;}
sql_free_result($result);

function ShowHeader() {
   global $rowH;
   echo '
   <table data-toggle="table" class="table-no-bordered">
      <thead class="d-none">
         <tr>
            <th class="n-t-col-xs-1" data-align="center">ID</th>
            <th class="n-t-col-xs-8">'.adm_translate("Entête").'</th>
            <th class="n-t-col-xs-1" data-align="center">Type</th>
            <th class="n-t-col-xs-2" data-align="center">'.adm_translate("Fonctions").'</th>
         </tr>
      </thead>
      <tbody>';
   foreach($rowH as $row) {
      $text=nl2br(htmlspecialchars($row['text'],ENT_COMPAT|ENT_HTML401,'UTF-8'));
      if (strlen($text)>100) 
         $text=substr($text,0,100).'<span class="text-danger"> .....</span>';
      $html = ($row['html']==1) ? 'html' : 'txt' ;
      echo '
         <tr>
            <td>'.$row['ref'].'</td>
            <td>'.$text.'</td>
            <td><code>'.$html.'</code></td>
            <td><a href="admin.php?op=lnl_Shw_Header&amp;Headerid='.$row['ref'].'" ><i class="fa fa-edit fa-lg me-3" title="'.adm_translate("Editer").'" data-bs-toggle="tooltip" data-bs-placement="left"></i></a><a href="admin.php?op=lnl_Sup_Header&amp;Headerid='.$row['ref'].'" class="text-danger"><i class="fas fa-trash fa-lg" title="'.adm_translate("Effacer").'" data-bs-toggle="tooltip" data-bs-placement="left"></i></a></td>
         </tr>';
   }
   echo '
      </tbody>
   </table>';
}

function Detail_Header_Footer($ibid, $type) {
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead($f_meta_nom, $f_titre, $adminimg);
   // $type = HED or FOT
   $result = sql_query("SELECT text, html FROM ".$NPDS_Prefix."lnl_head_foot WHERE type='$type' AND ref='$ibid'");
   $tmp = sql_fetch_row($result);
   sql_free_result($result);
   echo '
   <hr />
   <h3 class="mb-2">';
   echo ($type=="HED") ?
      adm_translate("Message d'entête") :
      adm_translate("Message de pied de page");
   echo ' - '.adm_translate("Prévisualiser");
   if ($tmp[1]==1)
      echo '<code> HTML</code></h3>
      <div class="card card-body">'.meta_lang($tmp[0]).'</div>';
   else
      echo '<code>'.adm_translate("TEXTE").'</code></h3>
      <div class="card card-body">'.nl2br($tmp[0]).'</div>';
   echo '
   <hr />
   <form action="admin.php" method="post" name="adminForm">
      <div class="mb-3 row">
         <label class="col-form-label col-sm-12" for="xtext">'.adm_translate("Texte").'</label>
         <div class="col-sm-12">
            <textarea class="tin form-control" cols="70" rows="20" name="xtext" >'.htmlspecialchars($tmp[0],ENT_COMPAT|ENT_HTML401,'UTF-8').'</textarea>
         </div>
      </div>';
   if ($tmp[1]==1) {
      global $tiny_mce_relurl;
      $tiny_mce_relurl='false';
      echo aff_editeur('xtext', '');
   }
   echo ($type=='HED') ?
      '
      <input type="hidden" name="op" value="lnl_Add_Header_Mod" />' :
      '
      <input type="hidden" name="op" value="lnl_Add_Footer_Mod" />' ;
   echo '
      <input type="hidden" name="ref" value="'.$ibid.'" />
      <div class="mb-3 row">
         <div class="col-sm-12">
            <button class="btn btn-primary me-1" type="submit">'.adm_translate("Valider").'</button>
            <a class="btn btn-secondary" href="admin.php?op=lnl" >'.adm_translate("Retour en arrière").'</a>
         </div>
      </div>
   </form>';
   adminfoot('','','','');
}

function ShowBody() {
   global $rowB;
   echo '
   <table data-toggle="table" class="table-no-bordered">
      <thead class="d-none">
         <tr>
            <th class="n-t-col-xs-1" data-align="center">ID</th>
            <th class="n-t-col-xs-8">'.adm_translate("Corps de message").'</th>
            <th class="n-t-col-xs-1" data-align="center">Type</th>
            <th class="n-t-col-xs-2" data-align="center">'.adm_translate("Fonctions").'</th>
         </tr>
      </thead>
      <tbody>';
   foreach($rowB as $row) {
      $text=nl2br(htmlspecialchars($row['text'],ENT_COMPAT|ENT_HTML401,'UTF-8'));
      if (strlen($text)>200) 
         $text=substr($text,0,200).'<span class="text-danger"> .....</span>';
      $html = ($row['html']==1) ? 'html' : 'txt' ;
      echo '
      <tr>
         <td>'.$row['ref'].'</td>
         <td>'.$text.'</td>
         <td><code>'.$html.'</code></td>
         <td><a href="admin.php?op=lnl_Shw_Body&amp;Bodyid='.$row['ref'].'"><i class="fa fa-edit fa-lg me-3" title="'.adm_translate("Editer").'" data-bs-toggle="tooltip" data-bs-placement="left"></i></a><a href="admin.php?op=lnl_Sup_Body&amp;Bodyid='.$row['ref'].'" class="text-danger"><i class="fas fa-trash fa-lg" title="'.adm_translate("Effacer").'" data-bs-toggle="tooltip" data-bs-placement="left"></i></a></td>
      </tr>';
   }
   echo '
      </tbody>
   </table>';
}

function Detail_Body($ibid) {
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead($f_meta_nom, $f_titre, $adminimg);
   echo '
   <hr />
   <h3 class="mb-2">'.adm_translate("Corps de message").' - ';
   $result = sql_query("SELECT text, html FROM ".$NPDS_Prefix."lnl_body WHERE ref='$ibid'");
   $tmp=sql_fetch_row($result);
   if ($tmp[1]==1)
      echo adm_translate("Prévisualiser").' <code>HTML</code></h3>
      <div class="card card-body">'.meta_lang($tmp[0]).'</div>';
   else
      echo adm_translate("Prévisualiser").' <code>'.adm_translate("TEXTE").'</code></h3>
      <div class="card card-body">'.nl2br($tmp[0]).'</div>';
   echo '
   <form action="admin.php" method="post" name="adminForm">
      <div class="mb-3 row">
         <label class="col-form-label col-sm-12" for="xtext">'.adm_translate("Corps de message").'</label>
         <div class="col-sm-12">
            <textarea class="tin form-control" rows="30" name="xtext" >'.htmlspecialchars($tmp[0],ENT_COMPAT|ENT_HTML401,'UTF-8').'</textarea>
         </div>
      </div>';
   if ($tmp[1]==1) {
      global $tiny_mce_relurl;
      $tiny_mce_relurl="false";
      echo aff_editeur("xtext", "false");
   }
   echo '
      <input type="hidden" name="op" value="lnl_Add_Body_Mod" />
      <input type="hidden" name="ref" value="'.$ibid.'" />
      <div class="mb-3 row">
         <div class="col-sm-12">
            <button class="btn btn-primary" type="submit">'.adm_translate("Valider").'</button>&nbsp;
            <button href="javascript:history.go(-1)" class="btn btn-secondary">'.adm_translate("Retour en arrière").'</button>
         </div>
      </div>
   </form>';
   adminfoot('','','','');
}

Function Add_Body() {
   global $hlpfile, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead($f_meta_nom, $f_titre, $adminimg);
   echo '
   <hr />
   <h3 class="mb-2">'.adm_translate("Corps de message").'</h3>
   <form id="lnlbody" action="admin.php" method="post" name="adminForm">
      <fieldset>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="html">'.adm_translate("Format de données").'</label>
            <div class="col-sm-8">
               <input class="form-control" id="html" type="number" min="0" max="1" step="1" value="1" name="html" required="required" />
               <span class="help-block"> <code>html</code> ==&#x3E; [1] / <code>text</code> ==&#x3E; [0]</span>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-12" for="xtext">'.adm_translate("Texte").'</label>
            <div class="col-sm-12">
               <textarea class="tin form-control" id="xtext" rows="30" name="xtext" ></textarea>
            </div>
         </div>';
   global $tiny_mce_relurl;
   $tiny_mce_relurl="false";
   echo aff_editeur("xtext", "false");
   echo '
         <div class="mb-3 row">
            <input type="hidden" name="op" value="lnl_Add_Body_Submit" />
            <button class="btn btn-primary col-sm-12 col-md-6" type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;'.adm_translate("Ajouter").' '.adm_translate("corps de message").'</button>
            <a href="admin.php?op=lnl" class="btn btn-secondary col-sm-12 col-md-6">'.adm_translate("Retour en arrière").'</a>
         </div>
      </fieldset>
   </form>';
   $fv_parametres='
     html: {
      validators: {
          regexp: {
            regexp:/[0-1]$/,
            message: "0 | 1"
          }
      }
   },
   ';
   $arg1='
   var formulid = ["lnlbody"];
   ';
   adminfoot('fv',$fv_parametres,$arg1,'');
}

Function Add_Body_Submit($Ytext, $Yhtml) {
   global $NPDS_Prefix;
   sql_query("INSERT INTO ".$NPDS_Prefix."lnl_body VALUES ('0', '$Yhtml', '$Ytext', 'OK')");
}

function ShowFooter() {
   global $rowF;
   echo '
   <table data-toggle="table" class="table-no-bordered">
      <thead class="d-none">
         <tr>
            <th class="n-t-col-xs-1" data-align="center">ID</th>
            <th class="n-t-col-xs-8">'.adm_translate("Pied").'</th>
            <th class="n-t-col-xs-1" data-align="center">Type</th>
            <th class="n-t-col-xs-2" data-align="center">'.adm_translate("Fonctions").'</th>
         </tr>
      </thead>
      <tbody>';
   foreach($rowF as $row) {
      $text=nl2br(htmlspecialchars($row['text'],ENT_COMPAT|ENT_HTML401,'UTF-8'));
      if (strlen($text)>100) 
         $text=substr($text,0,100).'<span class="text-danger"> .....</span>';
      $html = ($row['html']==1) ? 'html' : 'txt' ;
      echo '
         <tr>
            <td>'.$row['ref'].'</td>
            <td>'.$text.'</td>
            <td><code>'.$html.'</code></td>
            <td><a href="admin.php?op=lnl_Shw_Footer&amp;Footerid='.$row['ref'].'" ><i class="fa fa-edit fa-lg me-3" title="'.adm_translate("Editer").'" data-bs-toggle="tooltip" data-bs-placement="left"></i></a><a href="admin.php?op=lnl_Sup_Footer&amp;Footerid='.$row['ref'].'" class="text-danger"><i class="fas fa-trash fa-lg" title="'.adm_translate("Effacer").'" data-bs-toggle="tooltip" data-bs-placement="left"></i></a></td>
         </tr>';
   }
   echo '
      </tbody>
   </table>';
}

Function Add_Header_Footer($ibid) {
   global $hlpfile, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead($f_meta_nom, $f_titre, $adminimg);
   $t='';$v='';
   if ($ibid=='HED') {
      $ti="message d'entête";
      $va='lnl_Add_Header_Submit';
   } else {
      $ti="Message de pied de page";
      $va='lnl_Add_Footer_Submit';
   }
   echo '
      <hr />
      <h3 class="mb-2">'.ucfirst(adm_translate("$ti")).'</h3>
      <form id="lnlheadfooter" action="admin.php" method="post" name="adminForm">
      <fieldset>
         <div class="mb-3">
               <label class="col-form-label" for="html">'.adm_translate("Format de données").'</label>
               <div>
                  <input class="form-control" id="html" type="number" min="0" max="1" value="1" name="html" required="required" />
                  <span class="help-block"> <code>html</code> ==&#x3E; [1] / <code>text</code> ==&#x3E; [0]</span>
               </div>
            </div>
         <div class="mb-3">
            <label class="col-form-label" for="xtext">'.adm_translate("Texte").'</label>
            <div>
               <textarea class="form-control" id="xtext" rows="20" name="xtext" ></textarea>
            </div>
         </div>
         <div class="mb-3">';
   global $tiny_mce_relurl;
   $tiny_mce_relurl='false';
   echo aff_editeur('xtext', 'false');
   echo '
            <input type="hidden" name="op" value="'.$va.'" />
            <button class="btn btn-primary col-sm-12 col-md-6" type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;'.adm_translate("Ajouter").' '.adm_translate("$ti").'</button>
         </div>
      </fieldset>
   </form>';
   $fv_parametres='
     html: {
      validators: {
          regexp: {
            regexp:/[0-1]$/,
            message: "0 | 1"
          }
      }
   },
   ';
   $arg1='
   var formulid = ["lnlheadfooter"];
   ';

   adminfoot('fv',$fv_parametres,$arg1,'');
}

Function Add_Header_Footer_Submit($ibid, $xtext, $xhtml) {
   global $NPDS_Prefix;
   if ($ibid=="HED")
      sql_query("INSERT INTO ".$NPDS_Prefix."lnl_head_foot VALUES ('0', 'HED','$xhtml', '$xtext', 'OK')");
   else
      sql_query("INSERT INTO ".$NPDS_Prefix."lnl_head_foot VALUES ('0', 'FOT', '$xhtml', '$xtext', 'OK')");
}

function main() {
   global $hlpfile, $f_meta_nom, $f_titre, $adminimg, $rowH, $rowB, $rowF;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead($f_meta_nom, $f_titre, $adminimg);
   echo '
   <hr />
   <h3 class="mb-2">'.adm_translate("Petite Lettre D'information").'</h3>
   <ul class="nav flex-md-row flex-column">
      <li class="nav-item">
         <a class="nav-link active" href="admin.php?op=lnl_List">'.adm_translate("Liste des LNL envoyées").'</a>
      </li>
      <li class="nav-item">
         <a class="nav-link active" href="admin.php?op=lnl_User_List">'.adm_translate("Afficher la liste des prospects").'</a>
      </li>
   </ul>
   <h4 class="my-3"><a href="admin.php?op=lnl_Add_Header" ><i class="fa fa-plus-square me-2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.adm_translate("Ajouter").' '.adm_translate("Message d'entête").'"></i></a>'.adm_translate("Message d'entête").'</h4>';
      ShowHeader();
   echo '
   <h4 class="my-3"><a href="admin.php?op=lnl_Add_Body" ><i class="fa fa-plus-square me-2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.adm_translate("Ajouter").' '.adm_translate("Corps de message").'"></i></a>'.adm_translate("Corps de message").'</h4>';
      ShowBody();
      echo '
   <h4 class="my-3"><a href="admin.php?op=lnl_Add_Footer"><i class="fa fa-plus-square me-2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.adm_translate("Ajouter").' '.adm_translate("Message de pied de page").'"></i></a>'.adm_translate("Message de pied de page").'</h4>';
      ShowFooter();
   echo '
   <hr />
   <h4>'.adm_translate("Assembler une lettre et la tester").'</h4>
   <form id="ltesto" action="admin.php" method="post">
      <div class="row g-2">
         <div class="col-sm-4 mb-3">
            <select class="form-select form-select-sm" name="Xheader" id="testXheader" aria-label="select_'.adm_translate("Entête").' ">
               <option selected="selected">'.adm_translate("Entête").'</option>';
   foreach($rowH as $row) {
      echo '
               <option value="'.$row['ref'].'">'.$row['ref'].'  '.substr($row['text'],0,30).'</option>';
   }
   echo '
            </select>
         </div>
         <div class="col-sm-4 mb-3">
            <select class="form-select form-select-sm" name="Xbody" id="testXbody" aria-label="select_'.adm_translate("Corps").' ">
               <option selected="selected">'.adm_translate("Corps").'</option>';
   foreach($rowB as $row) {
      echo '
               <option value="'.$row['ref'].'">'.$row['ref'].'  '.substr($row['text'],0,30).'</option>';
   }
   echo '
            </select>
         </div>
         <div class="col-sm-4 mb-3">
            <select class="form-select form-select-sm" name="Xfooter" id="testXfooter" aria-label="select_'.adm_translate("Pied").' ">
               <option selected="selected">'.adm_translate("Pied").'</option>';
   foreach($rowF as $row) {
      echo '
               <option value="'.$row['ref'].'">'.$row['ref'].'  '.substr($row['text'],0,30).'</option>';
   }
   echo '
            </select>
         </div>
         <div class="mb-3 col-sm-12">
            <input type="hidden" name="op" value="lnl_Test" />
            <button class="btn btn-primary" type="submit">'.adm_translate("Valider").'</button>
         </div>
      </div>
   </form>
   <hr />
   <h4>'.adm_translate("Envoyer La Lettre").'</h4>
   <form id="lsendo" action="admin.php" method="post">
      <div class="row g-2">
         <div class="col-sm-4 mb-3">
            <select class="form-select form-select-sm" name="Xheader" id="Xheader" aria-label="select_'.adm_translate("Entête").' ">
               <option selected="selected">'.adm_translate("Entête").'</option>';
   foreach($rowH as $row) {
      echo '
               <option value="'.$row['ref'].'">'.$row['ref'].'  '.substr($row['text'],0,30).'</option>';
   }
   echo '
            </select>
         </div>
         <div class="col-sm-4 mb-3">
            <select class="form-select form-select-sm" name="Xbody" id="Xbody" aria-label="select_'.adm_translate("Corps").' ">
               <option selected="selected">'.adm_translate("Corps").'</option>';
   foreach($rowB as $row) {
      echo '
               <option value="'.$row['ref'].'">'.$row['ref'].'  '.substr($row['text'],0,30).'</option>';
   }
   echo '
            </select>
         </div>
         <div class="col-sm-4 mb-3">
            <select class="form-select form-select-sm" name="Xfooter" id="Xfooter" aria-label="select_'.adm_translate("Pied").' ">
               <option selected="selected">'.adm_translate("Pied").'</option>';
   foreach($rowF as $row) {
      echo '
               <option value="'.$row['ref'].'">'.$row['ref'].'  '.substr($row['text'],0,30).'</option>';
   }
   echo '
            </select>
         </div>
         <div class="col-sm-12">
            <div class="form-floating mb-3">
               <input class="form-control" type="text" maxlength="255" id="Xsubject" name="Xsubject" required="required"/>
               <label for="Xsubject">'.adm_translate("Sujet").'</label>
               <span class="help-block text-end"><span id="countcar_Xsubject"></span></span>
            </div>
         </div>
         <hr />
         <div class="mb-3 col-sm-12">
            <div class="form-check form-check-inline">
               <input type="radio" class="form-check-input" value="All" checked="checked" id="tous" name="Xtype" />
               <label class="form-check-label" for="tous">'.adm_translate("Tous les Utilisateurs").'</label>
            </div>
            <div class="form-check form-check-inline">
               <input type="radio" class="form-check-input" value="Mbr" id="mem" name="Xtype" />
               <label class="form-check-label" for="mem">'.adm_translate("Seulement aux membres").'</label>
            </div>
            <div class="form-check form-check-inline">
               <input type="radio" class="form-check-input" value="Out" id="prosp" name="Xtype" />
               <label class="form-check-label" for="prosp">'.adm_translate("Seulement aux prospects").'</label>
            </div>
         </div>';
      $mX=liste_group();
      $tmp_groupe='';
      foreach($mX as $groupe_id => $groupe_name) {
         if ($groupe_id=='0') $groupe_id='';
         $tmp_groupe.='
         <option value="'.$groupe_id.'">'.$groupe_name.'</option>';
      }
      echo '
         <div class="mb-3 col-sm-12">
            <select class="form-select" name="Xgroupe">'.$tmp_groupe.'</select>
         </div>
         <input type="hidden" name="op" value="lnl_Send" />
         <div class="mb-3 col-sm-12">
            <button class="btn btn-primary" type="submit">'.adm_translate("Valider").'</button>
         </div>
      </div>
      </form>';
      $fv_parametres='
      Xbody: {
         validators: {
             regexp: {
               regexp:/^\d{1,11}$/,
               message: "0 | 1"
             }
         }
      },
      Xheader: {
         validators: {
             regexp: {
               regexp:/^\d{1,11}$/,
               message: "0 | 1"
             }
         }
      },
      Xfooter: {
         validators: {
             regexp: {
               regexp:/^\d{1,11}$/,
               message: "0 | 1"
             }
         }
      },
     ';

      $arg1='
      var formulid = ["ltesto","lsendo"];
      inpandfieldlen("Xsubject",255);
      ';
   adminfoot('fv',$fv_parametres,$arg1,'');
}

function Del_Question($retour,$param) {
   global $hlpfile, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead($f_meta_nom, $f_titre, $adminimg);
   echo '
   <hr />
   <div class="alert alert-danger">'.adm_translate("Etes-vous sûr de vouloir effacer cet Article ?").'</div>
   <a href="admin.php?op='.$retour.'&amp;'.$param.'" class="btn btn-danger btn-sm">'.adm_translate("Oui").'</a>
   <a href="javascript:history.go(-1)" class="btn btn-secondary btn-sm">'.adm_translate("Non").'</a>';
   adminfoot('','','','');
}

function Test($Yheader, $Ybody, $Yfooter) {
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead($f_meta_nom, $f_titre, $adminimg);
   $result = sql_query("SELECT text, html FROM ".$NPDS_Prefix."lnl_head_foot WHERE type='HED' AND ref='$Yheader'");
   $Xheader=sql_fetch_row($result);
   $result = sql_query("SELECT text, html FROM ".$NPDS_Prefix."lnl_body WHERE html='$Xheader[1]' AND ref='$Ybody'");
   $Xbody=sql_fetch_row($result);
   $result = sql_query("SELECT text, html FROM ".$NPDS_Prefix."lnl_head_foot WHERE type='FOT' AND html='$Xheader[1]' AND ref='$Yfooter'");
   $Xfooter=sql_fetch_row($result);
   // For Meta-Lang
/* celà génère une erreur dans certains cas
   global $cookie;
   $uid=$cookie[0];
*/
   if ($Xheader[1]==1) {
      echo '
      <hr />
      <h3 class="mb-3">'.adm_translate("Prévisualiser").' HTML</h3>';
      $Xmime='html-nobr';
      $message=meta_lang($Xheader[0].$Xbody[0].$Xfooter[0]);
   } else {
      echo '
      <hr />
      <h3 class="mb-3">'.adm_translate("Prévisualiser").' '.adm_translate("TEXTE").'</h3>';
      $Xmime='text';
      $message=$Xheader[0]."\n".$Xbody[0]."\n".$Xfooter[0];
   }
   echo '
   <div class="card card-body">
   '.nl2br($message).'
   </div>
   <a class="btn btn-secondary my-3" href="javascript:history.go(-1)" >'.adm_translate("Retour en arrière").'</a>';
   global $adminmail;
   send_email($adminmail,'LNL TEST',$message, $adminmail, true, $Xmime, '');
   adminfoot('','','','');
}

function lnl_list() {
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead($f_meta_nom, $f_titre, $adminimg);
   $result = sql_query("SELECT ref, header , body, footer, number_send, type_send, date, status FROM ".$NPDS_Prefix."lnl_send ORDER BY date");

   echo '
   <hr />
   <h3 class="mb-3">'.adm_translate("Liste des LNL envoyées").'</h3>
   <table data-toggle="table" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa">
      <thead>
         <tr>
            <th class="n-t-col-xs-1" data-sortable="true" data-halign="center" data-align="right">ID</th>
            <th class="n-t-col-xs-1" data-halign="center" data-align="right">'.adm_translate("Entête").'</th>
            <th class="n-t-col-xs-1" data-halign="center" data-align="right">'.adm_translate("Corps").'</th>
            <th class="n-t-col-xs-1" data-halign="center" data-align="right">'.adm_translate("Pied").'</th>
            <th data-halign="center" data-align="right">'.adm_translate("Nbre d'envois effectués").'</th>
            <th data-halign="center" data-align="center">'.adm_translate("Type").'</th>
            <th data-halign="center" data-sortable="true" data-align="right">'.adm_translate("Date").'</th>
            <th data-halign="center" data-align="center">'.adm_translate("Etat").'</th>
         </tr>
      </thead>
      <tbody>';
   while (list($ref, $header, $body, $footer, $number_send, $type_send, $date, $status) = sql_fetch_row($result)) {
      $ico='';
      switch($type_send) {
         case 'Mbr':
            $ico = '<i class="fa fa-user fa-lg me-2"></i>';
         break;
         case 'Out':
            $ico = '<i class="fa fa-user fa-lg me-2 text-body-tertiary"></i>';
         break;
         case 'All':
            $ico = '<i class="fa fa-users fa-lg me-2"></i>';
         break;
         default :
            $ico = '<i class="fa fa-users fa-lg me-3"></i>';
         break;
      }
   echo '
         <tr>
            <td>'.$ref.'</td>
            <td>'.$header.'</td>
            <td>'.$body.'</td>
            <td>'.$footer.'</td>
            <td>'.$number_send.'</td>
            <td>'.$ico.$type_send.'</td>
            <td class="small">'.formatTimes($date, IntlDateFormatter::SHORT, IntlDateFormatter::SHORT).'</td>';
         if ($status=="NOK") {
            echo '
            <td class="text-danger">'.$status.'</td>';
         } else {
            echo '
            <td>'.$status.'</td>';
         }
      echo '
         </tr>';
   }
   echo '
      </tbody>
   </table>';
   adminfoot('','','','');
}

function lnl_user_list() {
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   $result = sql_query("SELECT email, date, status FROM ".$NPDS_Prefix."lnl_outside_users ORDER BY date");
   $nb_prospect = $result ? sql_num_rows($result) : '';
   echo '
   <hr />
   <h3 class="mb-2">'.adm_translate("Liste des prospects").'<span class="badge bg-secondary float-end">'.$nb_prospect.'</span></h3>
   <table id="tad_prospect" data-toggle="table" data-search="true" data-striped="true" data-mobile-responsive="true" data-show-export="true" data-icons="icons" data-icons-prefix="fa">
      <thead>
         <tr>
            <th class="n-t-col-xs-5" data-halign="center" data-sortable="true">'.adm_translate("E-mail").'</th>
            <th class="n-t-col-xs-3" data-halign="center" data-align="right" data-sortable="true">'.adm_translate("Date").'</th>
            <th class="n-t-col-xs-2" data-halign="center" data-align="center" data-sortable="true">'.adm_translate("Etat").'</th>
            <th class="n-t-col-xs-2" data-halign="center" data-align="right" data-sortable="true">'.adm_translate("Fonctions").'</th>
         </tr>
      </thead>
      <tbody>';
   while (list($email, $date, $status) = sql_fetch_row($result)) {
      echo '
         <tr>
            <td>'.$email.'</td>
            <td>'.formatTimes($date, IntlDateFormatter::SHORT, IntlDateFormatter::SHORT).'</td>';
      if ($status=="NOK")
         echo '
            <td class="text-danger">'.$status.'</td>';
      else
         echo '
            <td class="text-success">'.$status.'</td>';
      echo '
            <td><a href="admin.php?op=lnl_Sup_User&amp;lnl_user_email='.$email.'" class="text-danger"><i class="fas fa-trash fa-lg text-danger" data-bs-toggle="tooltip" title="'.adm_translate("Effacer").'"></i></a></td>
         </tr>';
   }
   sql_free_result($result);
   echo '
      </tbody>
   </table>
   <br /><a href="javascript:history.go(-1)" class="btn btn-secondary">'.adm_translate("Retour en arrière").'</a>';
   adminfoot('','','','');
}

switch ($op) {
   case "Sup_Header":
      Del_Question("lnl_Sup_HeaderOK","Headerid=$Headerid");
   break;
   case "Sup_Body":
      Del_Question("lnl_Sup_BodyOK","Bodyid=$Bodyid");
   break;
   case "Sup_Footer":
      Del_Question("lnl_Sup_FooterOK","Footerid=$Footerid");
   break;
   case "Sup_HeaderOK":
      sql_query("DELETE FROM ".$NPDS_Prefix."lnl_head_foot WHERE ref='$Headerid'");
      header("location: admin.php?op=lnl");
   break;
   case "Sup_BodyOK":
      sql_query("DELETE FROM ".$NPDS_Prefix."lnl_body WHERE ref='$Bodyid'");
      header("location: admin.php?op=lnl");
   break;
   case "Sup_FooterOK":
      sql_query("DELETE FROM ".$NPDS_Prefix."lnl_head_foot WHERE ref='$Footerid'");
      header("location: admin.php?op=lnl");
   break;
   case "Shw_Header":
      Detail_Header_Footer($Headerid, "HED");
   break;
   case "Shw_Body":
      Detail_Body($Bodyid);
   break;
   case "Shw_Footer":
      Detail_Header_Footer($Footerid, "FOT");
   break;

   case "Add_Header":
      Add_Header_Footer("HED");
   break;
   case "Add_Header_Submit":
      Add_Header_Footer_Submit("HED", $xtext, $html);
      header("location: admin.php?op=lnl");
   break;
   case "Add_Header_Mod":
      sql_query("UPDATE ".$NPDS_Prefix."lnl_head_foot SET text='$xtext' WHERE ref='$ref'");
      header("location: admin.php?op=lnl_Shw_Header&Headerid=$ref");
   break;

   case "Add_Body":
      Add_Body();
   break;
   case "Add_Body_Submit":
      Add_Body_Submit($xtext, $html);
      header("location: admin.php?op=lnl");
   break;
   case "Add_Body_Mod":
      sql_query("UPDATE ".$NPDS_Prefix."lnl_body SET text='$xtext' WHERE ref='$ref'");
      header("location: admin.php?op=lnl_Shw_Body&Bodyid=$ref");
   break;

   case "Add_Footer":
      Add_Header_Footer("FOT");
   break;
   case "Add_Footer_Submit":
      Add_Header_Footer_Submit("FOT", $xtext, $html);
      header("location: admin.php?op=lnl");
   break;
   case "Add_Footer_Mod":
      sql_query("UPDATE ".$NPDS_Prefix."lnl_head_foot SET text='$xtext' WHERE ref='$ref'");
      header("location: admin.php?op=lnl_Shw_Footer&Footerid=$ref");
   break;

   case "Test":
      Test($Xheader, $Xbody, $Xfooter);
   break;
   case "List":
      lnl_list();
   break;

   case "User_List":
      lnl_user_list();
   break;
   case "Sup_User":
      sql_query("DELETE FROM ".$NPDS_Prefix."lnl_outside_users WHERE email='$lnl_user_email'");
      header("location: admin.php?op=lnl_User_List");
   break;

   case "Send":
      $deb=0;
      $limit=50; // nombre de messages envoyé par boucle.
      if (!isset($debut)) $debut=0;
      if (!isset($number_send)) $number_send=0;

      global $nuke_url;
      $result = sql_query("SELECT text, html FROM ".$NPDS_Prefix."lnl_head_foot WHERE type='HED' AND ref='$Xheader'");
      $Yheader=sql_fetch_row($result);
      $result = sql_query("SELECT text, html FROM ".$NPDS_Prefix."lnl_body WHERE html='$Yheader[1]' AND ref='$Xbody'");
      $Ybody=sql_fetch_row($result);
      $result = sql_query("SELECT text, html FROM ".$NPDS_Prefix."lnl_head_foot WHERE type='FOT' AND html='$Yheader[1]' AND ref='$Xfooter'");
      $Yfooter=sql_fetch_row($result);
      $subject=stripslashes($Xsubject);
      if(!isset($Yheader[0]) or !isset($Ybody[0]) or !isset($Yfooter[0]) ) {
         header("location: admin.php?op=lnl&lnlerror=true");
         exit();
      }
      $message = $Yheader[0]."\n".$Ybody[0]."\n".$Yfooter[0];
      global $sitename;
      $Xmime = $Yheader[1]==1 ? 'html-nobr' : 'text';
      $message = ($Xmime=='html-nobr') ? meta_lang($message) : $message; 

      if ($Xtype=="All") {
         $Xtype="Out";
         $OXtype="All";
      }

      // Outside Users
      if ($Xtype=="Out") {
         $mysql_result=sql_query("SELECT email FROM ".$NPDS_Prefix."lnl_outside_users WHERE status='OK'");
         $nrows=sql_num_rows($mysql_result);
         $result = sql_query("SELECT email FROM ".$NPDS_Prefix."lnl_outside_users WHERE status='OK' ORDER BY email limit $debut,$limit");
         while (list($email) = sql_fetch_row($result)) {
            if (($email!="Anonyme") or ($email!="Anonymous")) {
               if ($email!='') {
                  if (($message!='') and ($subject!='')) {
                     if ($Xmime=="html-nobr") {
                        $Xmessage=$message."<br /><br /><hr />";
                        $Xmessage.= adm_translate("Pour supprimer votre abonnement à notre Lettre, suivez ce lien")." : <a href=\"$nuke_url/lnl.php?op=unsubscribe&email=$email\">".adm_translate("Modifier")."</a>";
                     } else {
                        $Xmessage=$message."\n\n------------------------------------------------------------------\n";
                        $Xmessage.= adm_translate("Pour supprimer votre abonnement à notre Lettre, suivez ce lien")." : $nuke_url/lnl.php?op=unsubscribe&email=$email";
                     }
                     send_email($email, $subject, $Xmessage, '', true, $Xmime, '');
                     $number_send++;
                  }
               }
            }
         }
      }
      // NPDS Users
      if ($Xtype=='Mbr') {
         if ($Xgroupe!='') {
            $mysql_result=sql_query("SELECT u.uid FROM ".$NPDS_Prefix."users u, ".$NPDS_Prefix."users_status s WHERE s.open='1' AND u.uid=s.uid AND u.email!='' AND (s.groupe LIKE '%$Xgroupe,%' OR s.groupe LIKE '%,$Xgroupe' OR s.groupe='$Xgroupe') AND u.user_lnl='1'");
            $nrows=sql_num_rows($mysql_result);
            $resultGP = sql_query("SELECT u.email, u.uid, s.groupe FROM ".$NPDS_Prefix."users u, ".$NPDS_Prefix."users_status s WHERE s.open='1' AND u.uid=s.uid AND u.email!='' AND (s.groupe LIKE '%$Xgroupe,%' OR s.groupe LIKE '%,$Xgroupe' OR s.groupe='$Xgroupe') AND u.user_lnl='1' ORDER BY u.email LIMIT $debut,$limit");
            $result = array();
            while(list($email, $uid, $groupe) = sql_fetch_row($resultGP)) {
               $re = "#^$Xgroupe{1}|,$Xgroupe,{1}|,$Xgroupe$#";
               if(preg_match($re, $groupe))
                  $result[] = $email;
            }
            $boucle = true;
         } else {
            $mysql_result=sql_query("SELECT u.uid FROM ".$NPDS_Prefix."users u, ".$NPDS_Prefix."users_status s WHERE s.open='1' AND u.uid=s.uid AND u.email!='' AND u.user_lnl='1'");
            $nrows=sql_num_rows($mysql_result);
            $query = sql_query("SELECT u.uid, u.email FROM ".$NPDS_Prefix."users u, ".$NPDS_Prefix."users_status s WHERE s.open='1' AND u.uid=s.uid AND u.user_lnl='1' ORDER BY email LIMIT $debut,$limit");
            $result = array();
            while(list($uid,$email) = sql_fetch_row($query)) {$result[]=$email;}
            $boucle = true;
         }
         if ($boucle) {
            foreach($result as $email) {
               if (($email!="Anonyme") or ($email!="Anonymous")) {
                  if ($email!='') {
                     if (($message!='') and ($subject!='')) {
                        send_email($email, $subject, $message, '', true, $Xmime, '');
                        $number_send++;
                     }
                  }
               }
            }
         }
      }
      $deb=$debut+$limit;
      $chartmp='';
      settype($OXtype, 'string');
      if ($deb>=$nrows) {
         if ((($OXtype=="All") and ($Xtype=="Mbr")) OR ($OXtype=="")) {
            if (($message!='') and ($subject!='')) {
               $timeX=getPartOfTime(time(), 'yyyy-MM-dd H:mm:ss');
               if ($OXtype=="All") $Xtype="All";
               if (($Xtype=="Mbr") and ($Xgroupe!="")) $Xtype=$Xgroupe;
               sql_query("INSERT INTO ".$NPDS_Prefix."lnl_send VALUES ('0', '$Xheader', '$Xbody', '$Xfooter', '$number_send', '$Xtype', '$timeX', 'OK')");
            }
            header("location: admin.php?op=lnl");
            break;
         } else {
           if ($OXtype=="All") {
              $chartmp="$Xtype : $nrows / $nrows";
              $deb=0;
              $Xtype="Mbr";
              $mysql_result=sql_query("SELECT u.uid FROM ".$NPDS_Prefix."users u, ".$NPDS_Prefix."users_status s WHERE s.open='1' and u.uid=s.uid and u.email!='' and u.user_lnl='1'");
              $nrows=sql_num_rows($mysql_result);
           }
         }
      }
      if ($chartmp=='') $chartmp="$Xtype : $deb / $nrows";
      include("meta/meta.php");
      echo "<script type=\"text/javascript\">
               //<![CDATA[
               function redirect() {
                  window.location=\"admin.php?op=lnl_Send&debut=".$deb."&OXtype=$OXtype&Xtype=$Xtype&Xgroupe=$Xgroupe&Xheader=".$Xheader."&Xbody=".$Xbody."&Xfooter=".$Xfooter."&number_send=".$number_send."&Xsubject=".$Xsubject."\";
               }
               setTimeout(\"redirect()\",10000);
               //]]>
            </script>";
      echo '
         <link href="'.$nuke_url.'/themes/npds-boost_sk/style/style.css" title="default" rel="stylesheet" type="text/css" media="all">
         <link id="bsth" rel="stylesheet" href="'.$nuke_url.'/themes/_skins/default/bootstrap.min.css">
         </head>
            <body>
               <div class="d-flex justify-content-center mt-4">
                 <div class="spinner-border text-success" role="status">
                   <span class="visually-hidden">Loading...</span>
                 </div>
               </div>
               <div class="d-flex justify-content-center mt-4">
                  <div class="text-center mt-4">
                  '.adm_translate("Transmission LNL en cours").' => '.$chartmp.'<br /><br />NPDS - Portal System
                  </div>
               </div>
            </body>
         </html>';
   break;

   default:
      main();
   break;
}
?>
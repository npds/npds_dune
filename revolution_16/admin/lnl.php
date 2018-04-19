<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2018 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],"admin.php")) Access_Error();
$f_meta_nom ='lnl';
$f_titre = adm_translate("Petite Lettre D'information");

//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
global $language;
$hlpfile = "manuels/$language/lnl.html";

function error_handler($ibid) {
   echo "<p align=\"center\"><span class=\"rouge\">".adm_translate("Merci d'entrer l'information en fonction des spécifications")."<br /><br />";
   echo "$ibid</span><br /><a href=\"index.php\" class=\"noir\">".adm_translate("Retour en arrière")."</a></p>";
}


function ShowHeader() {
   global $NPDS_Prefix;
   $result = sql_query("SELECT ref, text, html FROM ".$NPDS_Prefix."lnl_head_foot WHERE type='HED' ORDER BY ref ");
   echo '
   <table data-toggle="table" class="table-no-bordered">
      <thead class="d-none">
         <tr>
            <th class="n-t-col-xs-1" data-align="">ID</th>
            <th class="n-t-col-xs-8" data-align="">'.adm_translate("Entête").'</th>
            <th class="n-t-col-xs-1" data-align="">Type</th>
            <th class="n-t-col-xs-2" data-align="right">'.adm_translate("Fonctions").'</th>
         </tr>
      </thead>
      <tbody>';
   while (list($ref, $text, $html) = sql_fetch_row($result)) {
      $text=nl2br(htmlspecialchars($text,ENT_COMPAT|ENT_HTML401,cur_charset));
      if (strlen($text)>100) 
      $text=substr($text,0,100).'<span class="text-danger"> .....</span>';
      if ($html==1) $html='html'; else $html='txt';
      echo '
         <tr>
            <td>'.$ref.'</td>
            <td>'.$text.'</td>
            <td><code>'.$html.'</code></td>
            <td><a href="admin.php?op=lnl_Shw_Header&amp;Headerid='.$ref.'" ><i class="fa fa-edit fa-lg mr-2" title="'.adm_translate("Editer").'" data-toggle="tooltip"></i></a><a href="admin.php?op=lnl_Sup_Header&amp;Headerid='.$ref.'" class="text-danger"><i class="fa fa-trash-o fa-lg" title="'.adm_translate("Effacer").'" data-toggle="tooltip"></i></a></td>
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
   $result = sql_query("SELECT text, html FROM ".$NPDS_Prefix."lnl_head_foot WHERE type='$type' and ref='$ibid'");
   $tmp=sql_fetch_row($result);
   echo '
   <hr />
   <h3 class="mb-2">';
   if ($type=="HED")
      echo adm_translate("Message d'entête");
   else
      echo adm_translate("Message de pied de page");
   echo ' - '.adm_translate("Prévisualiser");
   if ($tmp[1]==1)
      echo '<code> HTML</code></h3>
      <div class="card card-body">'.$tmp[0].'</div>';
   else
      echo '<code>'.adm_translate("TEXTE").'</code></h3>
      <div class="card card-body">'.nl2br($tmp[0]).'</div>';
   echo '
   <hr />
   <form action="admin.php" method="post" name="adminForm">
      <div class="form-group row">
      <label class="col-form-label col-sm-12" for="xtext">Code Detail</label>
         <div class="col-sm-12">
            <textarea class="tin form-control" cols="70" rows="20" name="xtext" >'.htmlspecialchars($tmp[0],ENT_COMPAT|ENT_HTML401,cur_charset).'</textarea>
         </div>
      </div>';
   if ($tmp[1]==1) {
      global $tiny_mce_relurl;
      $tiny_mce_relurl='false';
      echo aff_editeur('xtext', '');
   }
   if ($type=='HED')
      echo '
      <input type="hidden" name="op" value="lnl_Add_Header_Mod" />';
   else
      echo '
      <input type="hidden" name="op" value="lnl_Add_Footer_Mod" />';
   echo '
      <input type="hidden" name="ref" value="'.$ibid.'" />
      <div class="form-group row">
         <div class="col-sm-12">
            <button class="btn btn-primary mr-1" type="submit">'.adm_translate("Valider").'</button>
            <a class="btn btn-secondary" href="admin.php?op=lnl" >'.adm_translate("Retour en arrière").'</a>
         </div>
      </div>
   </form>';
   include ("footer.php");
}

function ShowBody() {
   global $NPDS_Prefix;
   $result = sql_query("SELECT ref, text, html FROM ".$NPDS_Prefix."lnl_body ORDER BY ref ");
   echo '
   <table data-toggle="table" class="table-no-bordered">
      <thead class="d-none">
         <tr>
            <th class="n-t-col-xs-1" data-align="">ID</th>
            <th class="n-t-col-xs-8" data-align="">'.adm_translate("Corps de message").'</th>
            <th class="n-t-col-xs-1" data-align="">Type</th>
            <th class="n-t-col-xs-2" data-align="right">'.adm_translate("Fonctions").'</th>
         </tr>
      </thead>
      <tbody>';
   while (list($ref, $text, $html) = sql_fetch_row($result)) {
      $text=nl2br(htmlspecialchars($text,ENT_COMPAT|ENT_HTML401,cur_charset));
      if (strlen($text)>200) 
         $text=substr($text,0,200).'<span class="text-danger"> .....</span>';
      if ($html==1) $html='html'; else $html='txt';
      echo '
      <tr>
         <td>'.$ref.'</td>
         <td>'.$text.'</td>
         <td><code>'.$html.'</code></td>
         <td><a href="admin.php?op=lnl_Shw_Body&amp;Bodyid='.$ref.'"><i class="fa fa-edit fa-lg mr-2" title="'.adm_translate("Editer").'" data-toggle="tooltip"></i></a><a href="admin.php?op=lnl_Sup_Body&amp;Bodyid='.$ref.'" class="text-danger"><i class="fa fa-trash-o fa-lg" title="'.adm_translate("Effacer").'" data-toggle="tooltip"></i></a></td>
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
      <div class="card card-body">'.$tmp[0].'</div>';
   else
      echo adm_translate("Prévisualiser").' <code>'.adm_translate("TEXTE").'</code></h3>
      <div class="card card-body">'.nl2br($tmp[0]).'</div>';
   echo '
   <form action="admin.php" method="post" name="adminForm">
      <div class="form-group row">
         <label class="col-form-label col-sm-12" for="xtext">'.adm_translate("Corps de message").'</label>
         <div class="col-sm-12">
            <textarea class="tin form-control" rows="30" name="xtext" >'.htmlspecialchars($tmp[0],ENT_COMPAT|ENT_HTML401,cur_charset).'</textarea>
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
      <div class="form-group row">
         <div class="col-sm-12">
            <button class="btn btn-primary" type="submit">'.adm_translate("Valider").'</button>&nbsp;
            <button href="javascript:history.go(-1)" class="btn btn-secondary">'.adm_translate("Retour en arrière").'</button>
         </div>
      </div>
   </form>';

   include ("footer.php");
}

Function Add_Body() {
   global $hlpfile, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead($f_meta_nom, $f_titre, $adminimg);
   echo '
   <hr />
   <h3 class="mb-2">'.adm_translate("Corps de message").'</h3>
   <form action="admin.php" method="post" name="adminForm">
      <fieldset>
         <div class="form-group row">
            <label class="col-form-label col-sm-4" for="html">'.adm_translate("Format de données").'</label>
            <div class="col-sm-8">
               <input class="form-control" type="number" min="0" max="1" value="1" name="html" required="required" />
               <span class="help-block"> <code>html</code> ==&#x3E; [1] / <code>text</code> ==&#x3E; [0]</span>
            </div>
         </div>
         <div class="form-group row">
            <label class="col-form-label col-sm-12" for="xtext">'.adm_translate("Texte").'</label>
            <div class="col-sm-12">
               <textarea class="tin form-control" rows="30" name="xtext" ></textarea>
            </div>
         </div>';
   global $tiny_mce_relurl;
   $tiny_mce_relurl="false";
   echo aff_editeur("xtext", "false");
   echo '
         <div class="form-group row">
            <input type="hidden" name="op" value="lnl_Add_Body_Submit" />
            <button class="btn btn-primary col-sm-12 col-md-6" type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;'.adm_translate("Ajouter").' '.adm_translate("corps de message").'</button>
            <a href="admin.php?op=lnl" class="btn btn-secondary col-sm-12 col-md-6">'.adm_translate("Retour en arrière").'</a>
         </div>
      </fieldset>
   </form>';
   adminfoot('fv','','','');
}

Function Add_Body_Submit($Ytext, $Yhtml) {
   global $NPDS_Prefix;
   sql_query("INSERT INTO ".$NPDS_Prefix."lnl_body VALUES ('', '$Yhtml', '$Ytext', 'OK')");
}

function ShowFooter() {
   global $NPDS_Prefix;
   $result = sql_query("SELECT ref, text, html FROM ".$NPDS_Prefix."lnl_head_foot WHERE type='FOT' ORDER BY ref ");
   echo '
   <table data-toggle="table" class="table-no-bordered">
      <thead class="d-none">
         <tr>
            <th class="n-t-col-xs-1" data-align="">ID</th>
            <th class="n-t-col-xs-8" data-align="">'.adm_translate("Pied").'</th>
            <th class="n-t-col-xs-1" data-align="">Type</th>
            <th class="n-t-col-xs-2" data-align="right">'.adm_translate("Fonctions").'</th>
         </tr>
      </thead>
      <tbody>';
   while (list($ref, $text, $html) = sql_fetch_row($result)) {
      $text=nl2br(htmlspecialchars($text,ENT_COMPAT|ENT_HTML401,cur_charset));
      if (strlen($text)>100) 
         $text=substr($text,0,100).'<span class="text-danger"> .....</span>';
      if ($html==1) $html='html'; else $html='txt';
      echo '
         <tr>
            <td>'.$ref.'</td>
            <td>'.$text.'</td>
            <td><code>'.$html.'</code></td>
            <td><a href="admin.php?op=lnl_Shw_Footer&amp;Footerid='.$ref.'" ><i class="fa fa-edit fa-lg mr-2" title="'.adm_translate("Editer").'" data-toggle="tooltip"></i></a><a href="admin.php?op=lnl_Sup_Footer&amp;Footerid='.$ref.'" class="text-danger"><i class="fa fa-trash-o fa-lg" title="'.adm_translate("Effacer").'" data-toggle="tooltip"></i></a></td>
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
      <form action="admin.php" method="post" name="adminForm">
      <fieldset>
         <div class="form-group">
               <label class="col-form-label" for="html">'.adm_translate("Format de données").'</label>
               <div>
                  <input class="form-control" type="number" min="0" max="1" value="1" name="html" required="required" />
                  <span class="help-block"> <code>html</code> ==&#x3E; [1] / <code>text</code> ==&#x3E; [0]</span>
               </div>
            </div>
         <div class="form-group">
               <label class="col-form-label" for="xtext">'.adm_translate("Texte").'</label>
               <div>
                  <textarea class="form-control" rows="20" name="xtext" ></textarea>
               </div>
         </div>
         <div class="form-group">';
   global $tiny_mce_relurl;
   $tiny_mce_relurl='false';
   echo aff_editeur('xtext', 'false');
   echo '
            <input type="hidden" name="op" value="'.$va.'" />
            <button class="btn btn-primary col-sm-12 col-md-6" type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;'.adm_translate("Ajouter").' '.adm_translate("$ti").'</button>
         </div>
      </fieldset>
   </form>';
   adminfoot('fv','','','');
}

Function Add_Header_Footer_Submit($ibid, $xtext, $xhtml) {
   global $NPDS_Prefix;
   if ($ibid=="HED")
      sql_query("INSERT INTO ".$NPDS_Prefix."lnl_head_foot VALUES ('', 'HED','$xhtml', '$xtext', 'OK')");
   else
      sql_query("INSERT INTO ".$NPDS_Prefix."lnl_head_foot VALUES ('', 'FOT', '$xhtml', '$xtext', 'OK')");
}

function main() {
   global $hlpfile, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead($f_meta_nom, $f_titre, $adminimg);
   echo '
   <hr />
   <h3 class="mb-2">'.adm_translate("Petite Lettre D'information").'</h3>
   <a href="admin.php?op=lnl_List">'.adm_translate("Liste des LNL envoyées").'</a>
   <a href="admin.php?op=lnl_User_List">'.adm_translate("Afficher la liste des prospects").'</a>
   <h4 class="my-3"><a href="admin.php?op=lnl_Add_Header" ><i class="fa fa-plus-square mr-2"></i></a>'.adm_translate("Message d'entête").'</h4>';
      ShowHeader();
   echo '
   <h4 class="my-3"><a href="admin.php?op=lnl_Add_Body" ><i class="fa fa-plus-square mr-2"></i></a>'.adm_translate("Corps de message").'</h4>';
      ShowBody();
      echo '
   <h4 class="my-3"><a href="admin.php?op=lnl_Add_Footer"><i class="fa fa-plus-square mr-2"></i></a>'.adm_translate("Message de pied de page").'</h4>';
      ShowFooter();
   echo '
   <hr />
   <h4>'.adm_translate("Assembler une lettre et la tester").'</h4>
   <form action="admin.php" method="post">
   <div class="form-row">
      <div class="col">
         <label class="col-form-label" for="Xheader">'.adm_translate("Entête").'</label>
         <input class="form-control" type="number" name="Xheader" id="Xheader" max="11" />
      </div>
      <div class="col">
         <label class="col-form-label" for="Xbody">'.adm_translate("Corps").'</label>
         <input class="form-control" type="number" name="Xbody" id="Xbody" max="11" />
      </div>
      <div class="col">
         <label class="col-form-label" for="Xfooter">'.adm_translate("Pied").'</label>
         <input class="form-control" type="number" name="Xfooter" id="Xfooter" max="11" />
      </div>
   </div>
      <input type="hidden" name="op" value="lnl_Test" />
      <button class="btn btn-primary my-3" type="submit">'.adm_translate("Valider").'</button>
   </form>
   <hr />
   <h4>'.adm_translate("Envoyer La Lettre").'</h4>
   <form action="admin.php" method="post">
      <div class="form-row">
         <div class="col">
            <label class="col-form-label" for="Xheader">'.adm_translate("Entête").'</label>
            <input class="form-control" type="number" name="Xheader" id="Xheader" max="11" />
         </div>
         <div class="col">
            <label class="col-form-label" for="Xbody">'.adm_translate("Corps").'</label>
            <input class="form-control" type="number" name="Xbody" id="Xbody" max="11" />
         </div>
         <div class="col">
            <label class="col-form-label" for="Xfooter">'.adm_translate("Pied").'</label>
            <input class="form-control" type="number" name="Xfooter" id="Xfooter" max="11" />
         </div>
      </div>
      <div class="form-group">
         <label class="col-form-label" for="Xsubject">'.adm_translate("Sujet").'</label>
         <input class="form-control" type="text" max="255" id="Xsubject" name="Xsubject" />
      </div>
      <hr />
      <div class="form-group">
         <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" class="custom-control-input" value="All" checked="checked" id="tous" name="Xtype" />
            <label class="custom-control-label" for="tous">'.adm_translate("Tous les Utilisateurs").'</label>
         </div>
         <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" class="custom-control-input" value="Mbr" id="mem" name="Xtype" />
            <label class="custom-control-label" for="mem">'.adm_translate("Seulement aux membres").'</label>
         </div>
         <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" class="custom-control-input" value="Out" id="prosp" name="Xtype" />
            <label class="custom-control-label" for="prosp">'.adm_translate("Seulement aux prospects").'</label>
         </div>
      </div>';
      $mX=liste_group();
      $tmp_groupe='';
      while (list($groupe_id, $groupe_name)=each($mX)) {
         if ($groupe_id=='0') $groupe_id='';
         $tmp_groupe.='
         <option value="'.$groupe_id.'">'.$groupe_name.'</option>';
      }
      echo '
      <div class="form-group">
         <select class="custom-select form-control" name="Xgroupe">'.$tmp_groupe.'</select>
      </div>
      <input type="hidden" name="op" value="lnl_Send" />
      <div class="form-group">
         <input class="btn btn-primary" type="submit" value="'.adm_translate("Valider").'" />
      </div>
      </form>';
   adminfoot('','','','');
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
   global $cookie;
   $uid=$cookie[0];
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
      $message=meta_lang(nl2br($Xheader[0]).nl2br($Xbody[0]).nl2br($Xfooter[0]));
   }
   echo '
   <div class="card card-body">
   '.$message.'
   </div>
   <a class="btn btn-secondary my-3" href="javascript:history.go(-1)" >'.adm_translate("Retour en arrière").'</a>';
   global $adminmail;
   send_email($adminmail,'LNL TEST',$message, '', true, $Xmime);
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
            <th class="n-t-col-xs-1" data-halign="center" data-align="right">ID</th>
            <th class="n-t-col-xs-1" data-halign="center" data-align="right">'.adm_translate("Entête").'</th>
            <th class="n-t-col-xs-1" data-halign="center" data-align="right">'.adm_translate("Corps").'</th>
            <th class="n-t-col-xs-1" data-halign="center" data-align="right">'.adm_translate("Pied").'</th>
            <th data-halign="center" data-align="right">'.adm_translate("Nbre d'envois effectués").'</th>
            <th data-halign="center" data-align="center">'.adm_translate("Type").'</th>
            <th data-halign="center" data-align="right">Date</th>
            <th data-halign="center" data-align="center">Status</th>
         </tr>
      </thead>
      <tbody>';
   while (list($ref, $header, $body, $footer, $number_send, $type_send, $date, $status) = sql_fetch_row($result)) {
      echo '
         <tr>
            <td>'.$ref.'</td>
            <td>'.$header.'</td>
            <td>'.$body.'</td>
            <td>'.$footer.'</td>
            <td>'.$number_send.'</td>
            <td>'.$type_send.'</td>
            <td>'.$date.'</td>';
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
   echo '
   <hr />
   <h3 class="mb-2">'.adm_translate("Liste des prospects").'</h3>
   <table id="tad_prospect" data-toggle="table" data-search="true" data-striped="true" data-mobile-responsive="true" data-show-export="true" data-icons="icons" data-icons-prefix="fa">
      <thead>
         <tr>
            <th class="n-t-col-xs-5" data-halign="center" data-sortable="true">'.adm_translate("E-mail").'</th>
            <th class="n-t-col-xs-3" data-halign="center" data-align="right" data-sortable="true">'.adm_translate("Date").'</th>
            <th class="n-t-col-xs-2" data-halign="center" data-align="center" data-sortable="true">'.adm_translate("Status").'</th>
            <th class="n-t-col-xs-2" data-halign="center" data-align="right" data-sortable="true">'.adm_translate("Fonctions").'</th>
         </tr>
      </thead>
      <tbody>';
   while (list($email, $date, $status) = sql_fetch_row($result)) {
      echo '
         <tr>
            <td>'.$email.'</td>
            <td>'.$date.'</td>';
      if ($status=="NOK")
         echo '
            <td class="text-danger">'.$status.'</td>';
      else
         echo '
            <td class="text-success">'.$status.'</td>';
      echo '
            <td><a href="admin.php?op=lnl_Sup_User&amp;lnl_user_email='.$email.'" class="text-danger"><i class="fa fa-trash-o fa-lg text-danger" data-toggle="tooltip" title="'.adm_translate("Effacer").'"></i></a></td>
         </tr>';
   }
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
         $message =$Yheader[0].$Ybody[0].$Yfooter[0];

         global $sitename;
         if ($Yheader[1]==1)
            $Xmime='html-nobr';
         else
            $Xmime='text';

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
                           $Xmessage=$message."<br /><br /><hr noshade>";
                           $Xmessage.= adm_translate("Pour supprimer votre abonnement à notre Lettre, suivez ce lien")." : <a href=\"$nuke_url/lnl.php?op=unsubscribe&email=$email\">".adm_translate("Modifier")."</a>";
                        } else {
                           $Xmessage=$message."\n\n------------------------------------------------------------------\n";
                           $Xmessage.= adm_translate("Pour supprimer votre abonnement à notre Lettre, suivez ce lien")." : $nuke_url/lnl.php?op=unsubscribe&email=$email";
                        }
                        send_email($email, $subject, meta_lang($Xmessage), "", true, $Xmime);
                        $number_send++;
                     }
                  }
               }
            }
         }
         // NPDS Users
         if ($Xtype=='Mbr') {
            if ($Xgroupe!='') {
               $result='';
               $mysql_result=sql_query("SELECT u.uid FROM ".$NPDS_Prefix."users u, ".$NPDS_Prefix."users_status s WHERE s.open='1' AND u.uid=s.uid AND u.email!='' AND (s.groupe LIKE '%$Xgroupe,%' OR s.groupe LIKE '%,$Xgroupe' OR s.groupe='$Xgroupe') AND u.user_lnl='1'");
               $nrows=sql_num_rows($mysql_result);
               $resultGP = sql_query("SELECT u.email, u.uid, s.groupe FROM ".$NPDS_Prefix."users u, ".$NPDS_Prefix."users_status s WHERE s.open='1' AND u.uid=s.uid AND u.email!='' AND (s.groupe LIKE '%$Xgroupe,%' OR s.groupe LIKE '%,$Xgroupe' OR s.groupe='$Xgroupe') AND u.user_lnl='1' ORDER BY u.email LIMIT $debut,$limit");
               while(list($email, $uid, $groupe) = sql_fetch_row($resultGP)) {
                  $tab_groupe=explode(',',$groupe);
                  if ($tab_groupe) {
                     foreach($tab_groupe as $groupevalue) {
                        if ($groupevalue==$Xgroupe) {
                           $result[]=$email;
                        }
                     }
                  }
               }
               $fonction="each";
               if (is_array($result)) $boucle=true; else $boucle=false;
            } else {
               $mysql_result=sql_query("SELECT u.uid FROM ".$NPDS_Prefix."users u, ".$NPDS_Prefix."users_status s WHERE s.open='1' AND u.uid=s.uid AND u.email!='' AND u.user_lnl='1'");
               $nrows=sql_num_rows($mysql_result);
               $result = sql_query("SELECT u.uid, u.email FROM ".$NPDS_Prefix."users u, ".$NPDS_Prefix."users_status s WHERE s.open='1' AND u.uid=s.uid AND u.user_lnl='1' ORDER BY email LIMIT $debut,$limit");
               $fonction="sql_fetch_row";
               $boucle=true;
            }
            if ($boucle) {
               while (list($bidon, $email) = $fonction($result)) {
                  if (($email!="Anonyme") or ($email!="Anonymous")) {
                     if ($email!='') {
                        if (($message!='') and ($subject!='')) {
                           send_email($email, $subject, meta_lang($message), "", true, $Xmime);
                           $number_send++;
                        }
                     }
                  }
               }
            }
         }
         $deb=$debut+$limit;
         $chartmp='';
         if ($deb>=$nrows) {
            if ((($OXtype=="All") and ($Xtype=="Mbr")) OR ($OXtype=="")) {
               if (($message!='') and ($subject!='')) {
                  $timeX=strftime("%Y-%m-%d %H:%M:%S",time());
                  if ($OXtype=="All") {$Xtype="All";}
                  if (($Xtype=="Mbr") and ($Xgroupe!="")) {$Xtype=$Xgroupe;}
                  sql_query("INSERT INTO ".$NPDS_Prefix."lnl_send VALUES ('', '$Xheader', '$Xbody', '$Xfooter', '$number_send', '$Xtype', '$timeX', 'OK')");
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
         if ($chartmp=='') {$chartmp="$Xtype : $deb / $nrows";}
         include("meta/meta.php");
         echo "<script type=\"text/javascript\">
               //<![CDATA[
               function redirect() {
                  window.location=\"admin.php?op=lnl_Send&debut=".$deb."&OXtype=$OXtype&Xtype=$Xtype&Xgroupe=$Xgroupe&Xheader=".$Xheader."&Xbody=".$Xbody."&Xfooter=".$Xfooter."&number_send=".$number_send."&Xsubject=".$Xsubject."\";
               }
               setTimeout(\"redirect()\",10000);
               //]]>
               </script>";
         echo "</head>\n<body style=\"background-color: #FFFFFF;\"><br /><p align=\"center\" style=\"font-size: 12px; font-family: Arial; font-weight: bold; color: black;\">";
         echo adm_translate("Transmission LNL en cours")." => ".$chartmp;
         echo "<br /><br />NPDS - Portal System";
         echo "</p></body></html>";
         break;

   default:
      main();
   break;
}
?>
<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2017 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],"admin.php")) { Access_Error(); }
$f_meta_nom ='lnl';
$f_titre = adm_translate("Petite Lettre D'information");

//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
global $language;
$hlpfile = "manuels/$language/lnl.html";

function error_handler($ibid) {
   echo "<p align=\"center\"><span class=\"rouge\">".adm_translate("Merci d'entrer l'information en fonction des spécifications")."<br /><br />";
   echo "$ibid</span><br /><a href=\"index.php\" class=\"noir\">".adm_translate("Retour en arriére")."</a></p>";
}

function opentableL() {
   echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"1\">";
}

function ShowHeader() {
   global $NPDS_Prefix;

   $result = sql_query("SELECT ref, text, html FROM ".$NPDS_Prefix."lnl_head_foot WHERE type='HED' ORDER BY ref ");
   echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\">";
   while (list($ref, $text, $html) = sql_fetch_row($result)) {
      $text=nl2br(htmlspecialchars($text,ENT_COMPAT|ENT_HTML401,cur_charset));
      $rowcolor = tablos();
      if (strlen($text)>100) {$text=substr($text,0,100)."<span class=\"rouge\"> .....</span>";};
      if ($html==1) {$html="htm";} else {$html="txt";}
      echo "<tr><td width=\"5%\" >$ref</td><td width=\"85%\">$text&nbsp;</td><td align=\"center\" nowrap=\"nowrap\">[ $html ]</td>";
      echo "<td nowrap=\"nowrap\"><a href=\"admin.php?op=lnl_Shw_Header&amp;Headerid=$ref\" class=\"noir\">".adm_translate("Editer")."</a> | <a href=\"admin.php?op=lnl_Sup_Header&amp;Headerid=$ref\" class=\"rouge\">".adm_translate("Effacer")."</a></td></tr>";
   }
   echo "</table>";
}

function Detail_Header_Footer($ibid, $type) {
   global $hlpfile;
   global $NPDS_Prefix;

   include ("header.php");
   GraphicAdmin($hlpfile);
   // $type = HED or FOT
   $result = sql_query("SELECT text, html FROM ".$NPDS_Prefix."lnl_head_foot WHERE type='$type' and ref='$ibid'");
   $tmp=sql_fetch_row($result);
   echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n";
   if ($type=="HED") {
      echo adm_translate("Message d'entête");
   } else {
      echo adm_translate("Message de pied de page");
   }
   echo " - ";
   if ($tmp[1]==1) {
      echo adm_translate("Prévisualiser")." HTML<hr noshade=\"noshade\" />";
      echo $tmp[0];
   } else {
      echo adm_translate("Prévisualiser")." ".adm_translate("TEXTE")."<hr noshade=\"noshade\" />";
      echo nl2br($tmp[0]);
   }
   echo "</td></tr></table>\n";
   echo "<br />\n";
   echo "<form action=\"admin.php\" method=\"post\" name=\"adminForm\">";
   echo "<b>Code Detail</b><br />";
   echo "<textarea class=\"textbox\" cols=\"70\" rows=\"20\" name=\"xtext\" style=\"width: 100%;\">".htmlspecialchars($tmp[0],ENT_COMPAT|ENT_HTML401,cur_charset)."</textarea>";
   if ($tmp[1]==1) {
      global $tiny_mce_relurl;
      $tiny_mce_relurl="false";
      echo aff_editeur("xtext", "false");
      echo "<br />";
   }
   echo "<br />";
   if ($type=="HED") {
      echo "<input type=\"hidden\" name=\"op\" value=\"lnl_Add_Header_Mod\" />";
   } else {
      echo "<input type=\"hidden\" name=\"op\" value=\"lnl_Add_Footer_Mod\" />";
   }
   echo "<input type=\"hidden\" name=\"ref\" value=\"$ibid\" />";
   echo "<input class=\"btn btn-primary\" type=\"submit\" value=\"".adm_translate("Valider")."\"> - ";
   echo "[ <a href=\"admin.php?op=lnl\" >".adm_translate("Retour en arriére")."</a> ]";
   echo "</form>";
   include ("footer.php");
}
function ShowBody() {
   global $NPDS_Prefix;

   $result = sql_query("SELECT ref, text, html FROM ".$NPDS_Prefix."lnl_body ORDER BY ref ");
   echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\">";
   while (list($ref, $text, $html) = sql_fetch_row($result)) {
      $rowcolor = tablos();
      $text=nl2br(htmlspecialchars($text,ENT_COMPAT|ENT_HTML401,cur_charset));
      if (strlen($text)>200) {$text=substr($text,0,200)."<span class=\"rouge\"> .....</span>";};
      if ($html==1) {$html="htm";} else {$html="txt";}
      echo "<tr $rowcolor><td width=\"5%\" align=\"center\">$ref</td><td width=\"85%\">$text&nbsp;</td><td align=\"center\" nowrap=\"nowrap\">[ $html ]</td>";
      echo"<td nowrap=\"nowrap\"><a href=\"admin.php?op=lnl_Shw_Body&amp;Bodyid=$ref\" class=\"noir\">".adm_translate("Editer")."</a> | <a href=\"admin.php?op=lnl_Sup_Body&amp;Bodyid=$ref\" class=\"rouge\">".adm_translate("Effacer")."</a></td></tr>";
   }
   echo "</table>";
}

function Detail_Body($ibid) {
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead($f_meta_nom, $f_titre, $adminimg);
   echo '
   <h3>'.adm_translate("Corps de message").' - ';
   $result = sql_query("SELECT text, html FROM ".$NPDS_Prefix."lnl_body WHERE ref='$ibid'");
   $tmp=sql_fetch_row($result);
   if ($tmp[1]==1) {
      echo adm_translate("Prévisualiser").' HTML</h3>';
      echo '
      <div class="card card-block">'.$tmp[0].'</div>';
   } else {
      echo adm_translate("Prévisualiser").' '.adm_translate("TEXTE").'</h3>';
      echo '
      <div class="card card-block">'.nl2br($tmp[0]).'</div>';
   }

   echo '
   <form action="admin.php" method="post" name="adminForm">
      <div class="form-group row">
         <label class="form-control-label col-xs-12" for="xtext">'.adm_translate("Corps de message").'</label>
         <div class="col-xs-12">
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
         <div class="col-xs-12">
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
   <h3>'.adm_translate("Corps de message").'</h3>
   <form action="admin.php" method="post" name="adminForm">
      <fieldset>
         <div class="form-group row">
            <label class="form-control-label col-xs-4" for="html">'.adm_translate("Format de données").'</label>
            <div class="col-xs-8">
               <input class="form-control" type="number" min="0" max="1" value="1" name="html" required="required" />
               <span class="help-block"> <code>html</code> ==&#x3E; [1] / <code>text</code> ==&#x3E; [0]</span>
            </div>
         </div>
         <div class="form-group row">
            <label class="form-control-label col-xs-12" for="xtext">'.adm_translate("Texte").'</label>
            <div class="col-xs-12">
               <textarea class="tin form-control" rows="30" name="xtext" ></textarea>
            </div>
         </div>';
   global $tiny_mce_relurl;
   $tiny_mce_relurl="false";
   echo aff_editeur("xtext", "false");
   echo '
         <div class="form-group">
            <input type="hidden" name="op" value="lnl_Add_Body_Submit" />
            <button class="btn btn-primary col-xs-12 col-md-6" type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;'.adm_translate("Ajouter").' '.adm_translate("corps de message").'</button>
            <a href="admin.php?op=lnl" class="btn btn-secondary col-xs-12 col-md-6">'.adm_translate("Retour en arriére").'</a>
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
   echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\">";
   while (list($ref, $text, $html) = sql_fetch_row($result)) {
      $rowcolor = tablos();
      $text=nl2br(htmlspecialchars($text,ENT_COMPAT|ENT_HTML401,cur_charset));
      if (strlen($text)>100) {$text=substr($text,0,100).'<span class="text-danger"> .....</span>';};
      if ($html==1) {$html="htm";} else {$html="txt";}
      echo "<tr><td width=\"5%\" align=\"center\">$ref</td><td width=\"85%\">$text&nbsp;</td><td align=\"center\" nowrap=\"nowrap\"><code>[ $html ]</code></td>";
      echo "<td nowrap=\"nowrap\"><a href=\"admin.php?op=lnl_Shw_Footer&amp;Footerid=$ref\" class=\"noir\">".adm_translate("Editer")."</a> | <a href=\"admin.php?op=lnl_Sup_Footer&amp;Footerid=$ref\" class=\"rouge\">".adm_translate("Effacer")."</a></td></tr>";
   }
   echo "</table>";
}

Function Add_Header_Footer($ibid) {
   global $hlpfile, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead($f_meta_nom, $f_titre, $adminimg);
   $t='';$v='';
   if ($ibid=="HED") {
      $ti="message d'entête";
      $va='lnl_Add_Header_Submit';
   } else {
      $ti="Message de pied de page";
      $va='lnl_Add_Footer_Submit';
   }
   echo '
      <h3>'.ucfirst(adm_translate("$ti")).'</h3>
      <form action="admin.php" method="post" name="adminForm">
      <fieldset>
         <div class="form-group">
               <label class="form-control-label" for="html">'.adm_translate("Format de donn&#xE9;es").'</label>
               <div>
                  <input class="form-control" type="number" min="0" max="1" value="1" name="html" required="required" />
                  <span class="help-block"> <code>html</code> ==&#x3E; [1] / <code>text</code> ==&#x3E; [0]</span>
               </div>
            </div>
         <div class="form-group">
               <label class="form-control-label" for="xtext">'.adm_translate("Texte").'</label>
               <div>
                  <textarea class="form-control" rows="20" name="xtext" ></textarea>
               </div>
         </div>
         <div class="form-group">';
   global $tiny_mce_relurl;
   $tiny_mce_relurl="false";
   echo aff_editeur("xtext", "false");
   echo '
            <input type="hidden" name="op" value="'.$va.'" />
            <button class="btn btn-primary col-xs-12 col-md-6" type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;'.adm_translate("Ajouter").' '.adm_translate("$ti").'</button>
            <a href="admin.php?op=lnl" class="btn btn-secondary col-xs-12 col-md-6">'.adm_translate("Retour en arriére").'</a>
         </div>
      </fieldset>
   </form>';
   adminfoot('fv','','','');
}

Function Add_Header_Footer_Submit($ibid, $xtext, $xhtml) {
   global $NPDS_Prefix;

   if ($ibid=="HED") {
      sql_query("INSERT INTO ".$NPDS_Prefix."lnl_head_foot VALUES ('', 'HED','$xhtml', '$xtext', 'OK')");
   } else {
      sql_query("INSERT INTO ".$NPDS_Prefix."lnl_head_foot VALUES ('', 'FOT', '$xhtml', '$xtext', 'OK')");
   }
}

function main() {
   global $hlpfile, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead($f_meta_nom, $f_titre, $adminimg);
   echo '<h3>'.adm_translate("Petite Lettre D'information").'</h3>';

   echo "
   <a href=\"admin.php?op=lnl_List\" class=\"noir\">".adm_translate("Liste des LNL envoyées")."</a>
   <a href=\"admin.php?op=lnl_User_List\" class=\"noir\">".adm_translate("Afficher la liste des prospects")."</a>";
   echo '<h4>'.adm_translate("Message d'entête").'</h4><a href="admin.php?op=lnl_Add_Header" class="noir">'.adm_translate("Ajouter un article").'</a>';
      ShowHeader();
   echo '<h4>'.adm_translate("Corps de message").'</h4><a href="admin.php?op=lnl_Add_Body" class="noir">'.adm_translate("Ajouter un article").'</a>';
      ShowBody();
      echo '<h4>'.adm_translate("Message de pied de page").'</h4><a href="admin.php?op=lnl_Add_Footer" class="noir">'.adm_translate("Ajouter un article").'</a>';
      ShowFooter();
      
   echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n";
   echo adm_translate("Assembler une lettre et la tester");
   echo "</td></tr></table>\n";
      echo "<form action=\"admin.php\" method=\"post\">";
      echo "<p align=\"center\">".adm_translate("Entête")." : <input class=\"textbox_standard\" type=\"text\" name=\"Xheader\" size=\"12\" max=\"11\" />&nbsp;".adm_translate("Corps")." : <input class=\"textbox_standard\" type=\"text\" name=\"Xbody\" size=\"12\" max=\"11\" />";
      echo "&nbsp;".adm_translate("Pied")." : <input class=\"textbox_standard\" type=\"text\" name=\"Xfooter\" size=\"12\" max=\"11\" />";
      echo "<input type=\"hidden\" name=\"op\" value=\"lnl_Test\" />";
      echo " - <input class=\"bouton_standard\" type=\"submit\" value=\"".adm_translate("Valider")."\" /></p>";
      echo "</form></td></tr>";
      echo "<tr>";
      echo "<td>";
   echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n";
   echo adm_translate("Envoyer La Lettre");
   echo "</td></tr></table>\n";
      echo '<form action="admin.php" method="post">';
      echo adm_translate("Entête")." : <input class=\"textbox_standard\" type=\"text\" name=\"Xheader\" size=\"12\" max=\"11\" />&nbsp;".adm_translate("Corps")." : <input class=\"textbox_standard\" type=\"text\" name=\"Xbody\" size=\"12\" max=\"11\" />";
      echo "&nbsp;".adm_translate("Pied")." :&nbsp;&nbsp;<input class=\"textbox_standard\" type=\"text\" name=\"Xfooter\" size=\"12\" max=\"11\" /><br />";
      echo adm_translate("Sujet")." :&nbsp;&nbsp;&nbsp;<input class=\"textbox_standard\" type=\"text\" size=\"80\" max=\"255\" name=\"Xsubject\" /><hr noshade=\"noshade\" class=\"ongl\" />";
      echo "<p align=\"center\"><input type=\"radio\" value=\"All\" checked=\"checked\" name=\"Xtype\" /> ".adm_translate("Tous les Utilisateurs")." -";
      echo "<input type=\"radio\" value=\"Mbr\" name=\"Xtype\" /> ".adm_translate("Seulement aux membres")." : ";
      // ---- Groupes
      $mX=liste_group();
      $tmp_groupe="";
      while (list($groupe_id, $groupe_name)=each($mX)) {
         if ($groupe_id=="0") {$groupe_id="";}
         $tmp_groupe.="<option value=\"$groupe_id\" $sel3>$groupe_name</option>\n";
      }
      echo '<select class="custom-select form-control" name="Xgroupe">'.$tmp_groupe.'</select>';
      // ---- Groupes
      echo "<input type=\"radio\" value=\"Out\" name=\"Xtype\" /> ".adm_translate("Seulement aux prospects")." - ";
      echo '
      <input type="hidden" name="op" value="lnl_Send" />
      <input class="btn btn-primary" type="submit" value="'.adm_translate("Valider").'" />
      </form>';
   adminfoot('','','','');

}

function Del_Question($retour,$param) {
   global $hlpfile;
   include ("header.php");
   GraphicAdmin($hlpfile);
   opentable();
   echo "<p align=\"center\"><span class=\"rouge\">".adm_translate("Etes-vous sûr de vouloir effacer cet Article ?")."</span><br /><br />";
   echo "[ <a href=\"admin.php?op=$retour&amp;$param\" class=\"rouge\">".adm_translate("Oui")."</a> | ";
   echo "<a href=\"javascript:history.go(-1)\" class=\"noir\">".adm_translate("Non")."</a> ]</p>";
   closetable();
   include("footer.php");
}

function Test($Yheader, $Ybody, $Yfooter) {
   global $hlpfile;
   global $NPDS_Prefix;

   include ("header.php");
   GraphicAdmin($hlpfile);
   opentable();
   // $type = HED or FOT
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
      echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n";
      echo adm_translate("Prévisualiser")." HTML";
      echo "</td></tr></table>\n";
      echo "<br />\n";
      $Xmime="html-nobr";
      $message=meta_lang($Xheader[0].$Xbody[0].$Xfooter[0]);
   } else {
      echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n";
      echo adm_translate("Prévisualiser")." ".adm_translate("TEXTE")."";
      echo "</td></tr></table>\n";
      echo "<br />\n";
      $Xmime="text";
      $message=meta_lang(nl2br($Xheader[0]).nl2br($Xbody[0]).nl2br($Xfooter[0]));
   }
   echo $message;
   echo "<br /><br />\n";
   echo "[ <a href=\"javascript:history.go(-1)\" class=\"noir\">".adm_translate("Retour en arriére")."</a> ]";
   closetable();

   global $adminmail;
   send_email($adminmail,"LNL TEST",$message, "", true, $Xmime);

   include ("footer.php");
}

function lnl_list() {
   global $hlpfile;
   global $NPDS_Prefix;

   include ("header.php");
   GraphicAdmin($hlpfile);
   opentable();
   $result = sql_query("SELECT ref, header , body, footer, number_send, type_send, date, status FROM ".$NPDS_Prefix."lnl_send ORDER BY date");
   echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n";
   echo adm_translate("Liste des LNL envoyées");
   echo "</td></tr></table>\n";
   echo "<br /><table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\">";
   echo "<tr>";
   echo "<td>Num.</td>";
   echo "<td>".adm_translate("Entête")."</td>";
   echo "<td>".adm_translate("Corps")."</td>";
   echo "<td>".adm_translate("Pied")."</td>";
   echo "<td>".adm_translate("Nbre d'envois effectués")."</td>";
   echo "<td>".adm_translate("Type")."</td>";
   echo "<td>Date</td>";
   echo "<td>Status</td>";
   echo "</tr>";
   while (list($ref, $header, $body, $footer, $number_send, $type_send, $date, $status) = sql_fetch_row($result)) {
         $rowcolor = tablos();
         echo "<tr>";
         echo "<td>$ref</td>";
         echo "<td>$header</td>";
         echo "<td>$body</td>";
         echo "<td>$footer</td>";
         echo "<td>$number_send</td>";
         echo "<td>$type_send</td>";
         echo "<td>$date</td>";
         if ($status=="NOK") {
            echo "<td class=\"rouge\">$status</td>";
         } else {
            echo "<td>$status</td>";
         }
      echo "</tr>";
   }
   echo "</table><br />";
   echo "[ <a href=\"javascript:history.go(-1)\" class=\"noir\">".adm_translate("Retour en arriére")."</a> ]";
   closetable();
   include ("footer.php");
}

function lnl_user_list() {
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   $result = sql_query("SELECT email, date, status FROM ".$NPDS_Prefix."lnl_outside_users ORDER BY date");
   echo '
   <h3>'.adm_translate("Liste des prospects").'</h3>
   <table id="tad_prospect" data-toggle="table" data-search="true" data-striped="true" data-mobile-responsive="true" data-show-export="true" data-icons="icons" data-icons-prefix="fa">
      <thead>
         <tr>
            <th class="col-md-6" data-sortable="true">'.adm_translate("E-mail").'</th>
            <th class="col-md-4" data-sortable="true">'.adm_translate("Date").'</th>
            <th class="col-md-1" data-sortable="true">'.adm_translate("Status").'</th>
            <th class="col-md-1" data-sortable="true">'.adm_translate("Fonctions").'</th>
         </tr>
      </thead>
      <tbody>';
   while (list($email, $date, $status) = sql_fetch_row($result)) {
      echo '
         <tr>
            <td>'.$email.'</td>
            <td>'.$date.'</td>';
      if ($status=="NOK") {
         echo '
            <td class="text-danger">'.$status.'</td>';
      } else {
         echo '
            <td class="text-success">'.$status.'</td>';
      }
      echo '
            <td><a href="admin.php?op=lnl_Sup_User&amp;lnl_user_email='.$email.'" class="text-danger"><i class="fa fa-trash-o fa-lg text-danger" data-toggle="tooltip" title="'.adm_translate("Effacer").'"></i></a></td>
         </tr>';
   }
   echo '
      </tbody>
   </table>
   <br /><a href="javascript:history.go(-1)" class="btn btn-secondary">'.adm_translate("Retour en arriére").'</a>';
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
         if ($Yheader[1]==1) {
            $Xmime="html-nobr";
         } else {
            $Xmime="text";
         }

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
                  if ($email!="") {
                     if (($message!="") and ($subject!="")) {
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
         if ($Xtype=="Mbr") {
            if ($Xgroupe!='') {
               $result='';
               $mysql_result=sql_query("SELECT u.uid FROM ".$NPDS_Prefix."users u, ".$NPDS_Prefix."users_status s WHERE s.open='1' AND u.uid=s.uid AND u.email!='' AND (s.groupe LIKE '%$Xgroupe,%' OR s.groupe LIKE '%,$Xgroupe' OR s.groupe='$Xgroupe') AND u.user_lnl='1'");
               $nrows=sql_num_rows($mysql_result);
               $resultGP = sql_query("SELECT u.email, u.uid, s.groupe FROM ".$NPDS_Prefix."users u, ".$NPDS_Prefix."users_status s WHERE s.open='1' AND u.uid=s.uid AND u.email!='' AND (s.groupe LIKE '%$Xgroupe,%' OR s.groupe LIKE '%,$Xgroupe' OR s.groupe='$Xgroupe') AND u.user_lnl='1' ORDER BY u.email LIMIT $debut,$limit");
               while(list($email, $uid, $groupe) = sql_fetch_row($resultGP)) {
                  $tab_groupe=explode(",",$groupe);
                  if ($tab_groupe) {
                     foreach($tab_groupe as $groupevalue) {
                        if ($groupevalue==$Xgroupe) {
                           $result[]=$email;
                        }
                     }
                  }
               }
               $fonction="each";
               if (is_array($result)) {$boucle=true;} else {$boucle=false;}
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
         $chartmp="";
         if ($deb>=$nrows) {
            if ((($OXtype=="All") and ($Xtype=="Mbr")) OR ($OXtype=="")) {
               if (($message!="") and ($subject!="")) {
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
         if ($chartmp=="") {$chartmp="$Xtype : $deb / $nrows";}
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
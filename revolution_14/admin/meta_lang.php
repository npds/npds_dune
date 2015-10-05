<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NEO - 2007                                                           */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2015 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],"admin.php")) { Access_Error(); }
$f_meta_nom ='MetaLangAdmin';
$f_titre = 'META-LANG';
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
global $language, $NPDS_Prefix;
$hlpfile = "manuels/".$language."/meta_lang.html";

function go_back($label) {
   if (!$label)
      $label = adm_translate("Retour en arrière");
   echo '
   <script type="text/javascript">
   //<![CDATA[
   function precedent() {
   document.write(\'<button onclick="history.back();" class="btn btn-secondary">'.$label.'</button>\');
   }
   precedent();
   //]]>
   </script>';
}

function inc_head($title) {
   echo $title." META-MOT\n";
}

function list_meta($meta, $type_meta) {
   global $NPDS_Prefix;

   $list = '<select class="form-control" name="meta" onchange="window.location=eval(\'this.options[this.selectedIndex].value\')">';
   $list .= '<option value="'.$url.'">META-MOT</option>';
   if (!empty($type_meta)) $Q = sql_query("SELECT def FROM ".$NPDS_Prefix."metalang WHERE type_meta = '".$type_meta."' ORDER BY type_meta, def ASC");
   else $Q = sql_query("SELECT def FROM ".$NPDS_Prefix."metalang ORDER BY 'def' ASC");
   while ($resultat = sql_fetch_row($Q))  {
      if ($meta == $resultat[0]) { $sel = "selected=\"selected\""; }
      $list .= "<option ".$sel." value=\"admin.php?op=Meta-LangAdmin&amp;meta=".$resultat[0]."\">".$resultat[0]."</option>\n";
      $sel = "";
   }
   sql_free_result($Q);
   $list .= '</select>';
   return ($list);
}
function list_meta_type() {
   $list = '
   <select class="form-control" name="type_meta" onchange="window.location=eval(\'this.options[this.selectedIndex].value\')">
      <option value="">Type</option>
      <option value="admin.php?op=Creat_Meta_Lang&amp;type_meta=meta">meta</option>
      <option value="admin.php?op=Creat_Meta_Lang&amp;type_meta=mot">mot</option>
      <option value="admin.php?op=Creat_Meta_Lang&amp;type_meta=smil">smil</option>
      <option value="admin.php?op=Creat_Meta_Lang&amp;type_meta=them">them</option>
   </select>';
   return ($list);
}
function list_type_meta($type_meta) {
   global $NPDS_Prefix;
   $list = '
   <select class="form-control" name="type_meta" onchange="window.location=eval(\'this.options[this.selectedIndex].value\')">
      <option value="'.$url.'">Type</option>';
   $Q = sql_query("SELECT type_meta FROM ".$NPDS_Prefix."metalang GROUP BY type_meta ORDER BY 'type_meta' ASC");
   while ($resultat = sql_fetch_row($Q))  {
      if ($type_meta == $resultat[0]) { $sel = "selected "; }
      $list .= '<option '.$sel.' value="admin.php?op=Meta-LangAdmin&amp;type_meta='.$resultat[0].'">'.$resultat[0].'</option>';
      $sel = "";
   }
   sql_free_result($Q);
   $list .= '</select>';
   return $list;
}
function List_Meta_Lang() {
   global $hlpfile, $NPDS_Prefix, $meta, $type_meta, $f_meta_nom, $f_titre, $adminimg;

   if (!empty($meta)) $Q = sql_query("SELECT def, content, type_meta, type_uri, uri, description, obligatoire FROM ".$NPDS_Prefix."metalang WHERE def = '".$meta."' ORDER BY type_meta, def ASC");
   else if (!empty($type_meta)) $Q = sql_query("SELECT def, content, type_meta, type_uri, uri, description, obligatoire FROM ".$NPDS_Prefix."metalang WHERE type_meta = '".$type_meta."' ORDER BY type_meta, def ASC");
   else $Q = sql_query("SELECT def, content, type_meta, type_uri, uri, description, obligatoire FROM ".$NPDS_Prefix."metalang ORDER BY 'type_meta','def' ASC");
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   $tablmeta=''; $tablmeta_c=''; $cur_type=""; $ibid=0;
   while (list($def, $content, $type_meta, $type_uri, $uri, $description, $obligatoire)= sql_fetch_row($Q)) {
      if ($cur_type=="")
         $cur_type=$type_meta;
/*      if ($type_meta!=$cur_type) {
         echo '
         <tr>
            <td colspan="4"></td>
         </tr>';
         $cur_type=$type_meta;
      }
*/
      $tablmeta_c.= '
         <tr>
            <td><code>'.$def.'</code></td>
            <td>'.$type_meta.'</td>';
      if ($type_meta=="smil") {
         eval($content);
         $tablmeta_c.= '
            <td>'.$cmd.'</td>';
      }
      else if ($type_meta=="mot") {
         $tablmeta_c.= '
         <td>'.$content.'</td>';
      }
      else {
         $tablmeta_c.= '
         <td>'.aff_langue($description).'</td>';
      }
      $tablmeta_c.='
         <td>
            <input type="hidden" name="nbr" value="'.$ibid.'" />';
      if ($obligatoire == false) 
      $tablmeta_c.= '<a href="admin.php?op=Edit_Meta_Lang&amp;ml='.urlencode($def).'"><i class="fa fa-edit fa-lg" title="Editer ce m&#xE9;ta-mot" data-toggle="tooltip" data-placement="right"></i></a>&nbsp;&nbsp;<i class="fa fa-trash-o fa-lg text-danger" title="Effacer ce m&#xE9;ta-mot" data-toggle="tooltip" data-placement="right"></i>&nbsp;<input type="checkbox" name="action['.$ibid.']" value="'.$def.'" />';
      else $tablmeta_c.= '<a href="admin.php?op=Edit_Meta_Lang&amp;ml='.urlencode($def).'" ><i class="fa fa-eye fa-lg" title="Voir le code de ce m&#xE9;ta-mot" data-toggle="tooltip" ></i></a>      ';
      $tablmeta_c.='
         </td>
      </tr>';
      
      $ibid++;
   }
   sql_free_result($Q);
   
   $tablmeta.= '
   <a href="admin.php?op=Creat_Meta_Lang">'.adm_translate("Créer un nouveau").' META-MOT</a>
   <h3>'. adm_translate("Recherche rapide").'</h3>
   <div class="row">
      <div class="col-sm-3">'.list_meta($meta, $type_meta).'</div>
      <div class="col-sm-3">'.list_type_meta($type_meta).'</div>
   </div>
   <h3>META-MOT <small>'.$ibid.'</small></h3>
   <form name="admin_meta_lang" action="admin.php" method="post" onsubmit="return confirm(\''.adm_translate("Supprimer").' ?\')">
   <table data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa">
      <thead>
         <tr>
            <th data-sortable="true">'.adm_translate("Nom").'</th>
            <th data-sortable="true">'.adm_translate("Type").'</th>
            <th data-sortable="true">'.adm_translate("Description").'</th>
            <th data-sortable="true">'.adm_translate("Fonction").'</th>
         </tr>
      </thead>
      <tbody>';
   $tablmeta.= $tablmeta_c;
   $tablmeta.= '
         <tr>
            <td colspan="4">
               <input type="hidden" name="op" value="Kill_Meta_Lang" />
               <input type="image" src="images/admin/metalang/delete.gif" name="submit" value="kill" title="'.adm_translate("Tout supprimer").'" alt="'.adm_translate("Tout supprimer").'" />
            </td>
         </tr>
      </tbody>
   </table>
   </form>'."\n";
   
   echo $tablmeta;
//   adminfoot('','','','');
}
function Edit_Meta_Lang() {
   global $hlpfile, $NPDS_Prefix, $ml, $local_user_language, $language, $f_meta_nom, $f_titre, $adminimg;

   $Q = sql_query("SELECT def, content, type_meta, type_uri, uri, description, obligatoire FROM ".$NPDS_Prefix."metalang WHERE def = '".$ml."'");
   $Q = sql_fetch_assoc($Q);
   sql_free_result($Q);
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);

   if ($Q['obligatoire'] != true) 
   echo '<h3>'.adm_translate("Modifier un ").' META-MOT</h3>';
//   inc_head($title);
   
   echo '
   <table border="0" width="100%" cellpadding="0" cellspacing="1" class="header">
   <tr>
   <td>';
   echo "<table width=\"100%\" border=\"0\" cellpadding=\"8\" cellspacing=\"1\" class=\"lignb\">\n";
   echo "<tr>\n";
   echo "<td colspan=\"2\" valign=\"top\"><b>".aff_local_langue("<b>".adm_translate("Langue de Prévisualisation")."</b> : ","","local_user_language")."<br /></td>\n";
   echo "</tr>\n";
   echo "<tr>\n";
   echo "<td width=\"110\"><b>META</b> : </td><td>".$Q['def']."</td>\n";
   echo "</tr>\n";
   echo "<tr>\n";
   echo "<td><b>Type</b> :</td><td>".$Q['type_meta']."</td>\n";
   echo "</tr>\n";
   echo "<tr>\n";
   echo "<td><b>".adm_translate("Description")."</b></td>\n";
   if ($Q['type_meta']=="smil") {
      eval($Q['content']);
      echo "<td>".$cmd."</td>\n";
   }
   else {
      echo '<td>'.preview_local_langue($local_user_language, aff_langue($Q['description'])).'</td>'."\n";
   }
   echo "</tr>\n";
   if ($Q['type_meta']!="docu" and $Q['type_meta']!="them") {
      echo "<tr ".$rowcolor.">\n";
      echo "<td valign=\"top\"><b>".adm_translate("Script")."</b> :</td>\n";
      echo "<td>";
      echo "<textarea name=\"content\" class=\"textbox_standard\" rows=\"20\" style=\"width: 100%;\" readonly=\"readonly\">\n";
      echo $Q['content'];
      echo "</textarea>";
      echo '</td>
      </tr>';
   }
   echo '
   </table>
   </td>
   </tr>
   </table>'."\n";

   if ($Q['obligatoire'] != true) {
      opentable();
      echo "<table width=\"100%\" border=\"0\" cellpadding=\"8\" cellspacing=\"1\">\n";
      echo "<form name=\"edit_meta_lang\" action=\"admin.php\" method=\"post\">\n";
      echo "<tr>\n";
      echo "<td width=\"110\"><b>META</b> : </td>\n";
      echo "<td>";
      echo "<input class=\"textbox_standard\" type=\"text\" name=\"def\" value=\"".$Q['def']."\" size=\"50\" readonly=\"readonly\" /></td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
      echo "<td><b>".adm_translate("Type")."</b> :</td>\n";
      echo "<td>";
      echo "<input class=\"textbox_standard\" type=\"text\" name=\"type_meta\" value=\"".$Q['type_meta']."\" size=\"10\" maxlength=\"10\" readonly=\"readonly\" />";
      echo "</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
      echo "<td><b>".adm_translate("Description")."</b> :</td>\n";
      if ($Q['type_meta']=="smil") {
          eval($Q['content']);
          echo "<td>".$cmd."</td>\n";
      } else {
         echo "<td>";
         echo "<textarea class=\"textbox_standard\" name=\"desc\" rows=\"7\" style=\"width: 100%;\" >";
         echo $Q['description'];
         echo "</textarea>";
         echo "</td>\n";
      }
      echo "</tr>\n";
      if ($Q['type_meta']!="docu" and $Q['type_meta']!="them") {
         echo "<tr>\n";
         echo "<td valign=\"top\"><b>".adm_translate("Script")."</b> : </td>\n";
         echo "<td>";
         echo "<textarea class=\"textbox_standard\" name=\"content\" rows=\"20\" style=\"width: 100%;\" >\n";
         echo $Q['content'];
         echo "</textarea>";
         echo "</td>\n";
         echo "</tr>\n";
      }
      echo "<tr>\n";
      echo "<td><b>".adm_translate("Restriction")." :</b></td>\n";
      echo "<td>";
      if ($Q['type_uri'] == "+") {
         if ($Q['obligatoire'] == true) {$sel1 = "selected=\"selected\"";}
         else  {$sel1 = " selected";}
      }
      else {
         if ($Q['obligatoire'] == true) {$sel0 = "selected=\"selected\"";}
         else {$sel0 = " selected";}
      }
      echo '
      <select class="textbox_standard" name="type_uri">
         <option'.$sel0.' value="moins">'.adm_translate("Tous sauf pour ...").'</option>
         <option'.$sel1.' value="plus">'.adm_translate("Seulement pour ...").'</option>
      </select>';
      echo "&nbsp;...".adm_translate("les URLs que vous aurez renseignés ci-après&nbsp;<i>(ne renseigner que la racine de l'URI)</i>")."";
      echo "<br />".adm_translate("Exemple")." : index.php user.php forum.php static.php\n";
      echo "<br />".adm_translate("Par defaut, rien ou Tout sauf pour ... [aucune URI] = aucune restriction");
      echo "<br />\n";
      echo "<textarea class=\"textbox_standard\" name=\"uri\" rows=\"7\" style=\"width: 100%;\" >";
      echo $Q['uri']."</textarea>";
      echo '
         </td>
      </tr>
      <tr>
         <td width="100%" colspan="2" align="center">
            <input type="hidden" name="Maj_Bdd_ML" value="edit_meta" />
            <input type="hidden" name="op" value="Valid_Meta_Lang" />
            <button class="btn btn-primary" type="submit">'.adm_translate("Valider").'</button>
         </td>
      </tr>
      </table>
      </form>'."\n";
      closetable();
   } else {
      go_back("");
   }
   closetable();
   include ("footer.php");
}


function Creat_Meta_Lang() {
   global $NPDS_Prefix, $hlpfile, $type_meta, $f_meta_nom, $f_titre, $adminimg;

   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '
   <h3>'.adm_translate("Créer un nouveau").' META-MOT : <small>de type '.$type_meta.'</small></h3>
   <form name="creat_meta_lang" action="admin.php" method="post">';
   if (!$type_meta)
      echo adm_translate("Veuillez choisir un type de META-MOT")." ";
   echo list_meta_type($type_meta);
//   echo $type_meta;
   if ($type_meta) {
      echo '
      <label class="form-control-label" for="def">META-MOT</label>
      <input class="form-control" type="text" name="def" id="def" size="50" maxlength="50" />
      ';
      if ($type_meta != "smil") {
         echo '
      <label class="form-control-label" for="desc">'.adm_translate("Description").'</label>
      <textarea class="form-control" name="desc" id="desc" rows="7" style="width: 100%;">[french]...[/french][english]...[/english]</textarea>
         ';
      }
      if ($type_meta != "them") {
         echo '<label class="form-control-label" for="content">'.adm_translate("Script").'</label>';
         if ($type_meta == "smil") {
            echo adm_translate("Chemin et nom de l'image du Smiley").'&nbsp;&nbsp;<span class="rouge">Ex. : forum/smilies/pafmur.gif</span>';
            echo '<input class="form-control" type="text" name="content" id="content" maxlength="255" />';
         } else
            echo '<textarea class="form-control" name="content" id="content" rows="20">';
            if ($type_meta=="meta") echo "function MM_XYZ (\$arg) {\n   global \$NPDS_Prefix;\n   \$arg = arg_filter(\$arg);\n\n   return(\$content);\n}";
            echo '</textarea>';
      }
      echo '
      
      <div class="form-group">
      <label class="form-control-label" for="type_uri">'.adm_translate("Restriction").'</label>
      <select class="form-control" name="type_uri">
         <option'.$sel.' value="moins">'.adm_translate("Tous sauf pour ...").'</option>
         <option'.$sel.' value="plus">'.adm_translate("Seulement pour ...").'</option>
      </select>
      </div>
      <div class="form-group">
         <span class="help-block">'.adm_translate("les URLs que vous aurez renseignés ci-après&nbsp;<i>(ne renseigner que la racine de l'URI)</i>").'<br />'.adm_translate("Exemple").' : index.php user.php forum.php static.php<br />'.adm_translate("Par defaut, rien ou Tout sauf pour ... [aucune URI] = aucune restriction").'</span>
         <textarea class="form-control" name="uri" rows="7"></textarea>
      </div>
      <div class="form-group">
         <input type="hidden" name="type_meta" value="'.$type_meta.'" />
         <input type="hidden" name="Maj_Bdd_ML" value="creat_meta" />
         <input type="hidden" name="op" value="Valid_Meta_Lang" />
         <button class="btn btn-primary" type="submit">'.adm_translate("Valider").'</button>
      </div>
      ';
   }
   echo '
   </form>';
   adminfoot('fv','','','');

//   include ("footer.php");
}
function kill_Meta_Lang($nbr, $action) {
   global $NPDS_Prefix;
   $i=0;
   while($i <= $nbr) {
      if (!empty($action[$i])) {sql_query("DELETE FROM ".$NPDS_Prefix."metalang WHERE def='".$action[$i]."' ");}
      $i++;
   }
   Header("Location: admin.php?op=Meta-LangAdmin");
}
function meta_exist($def) {
    global $hlpfile, $language;
    include("header.php");
    GraphicAdmin($hlpfile);
    echo "<p align=\"center\"><br />";
    echo "<span class=\"rouge\"><b>";
    echo $def."</b></span><br /><br />";
    echo adm_translate("Ce META-MOT existe déjà");
    echo "<br /><br />".adm_translate("Veuillez nommer différement ce nouveau META-MOT").".";
    go_back("");
    
    include("footer.php");
}
function Maj_Bdd_ML($Maj_Bdd_ML, $def, $content, $type_meta, $type_uri, $uri, $desc) {
   global $NPDS_Prefix;

   if ($type_uri =="plus") {$type_uri = "+";} else {$type_uri = "-";}

   if ($Maj_Bdd_ML=="creat_meta") {
      $def=trim($def);
      $Q = sql_query("SELECT def FROM ".$NPDS_Prefix."metalang WHERE def='".$def."'");
      $Q = sql_fetch_assoc($Q);
      sql_free_result($Q);
      if ($Q['def']) {
         meta_exist($Q['def']);
      } else {
         if ($type_meta=="smil")
            $content="\$cmd=MM_img(\"$content\");";
         if ($def!="")
            sql_query("INSERT INTO ".$NPDS_Prefix."metalang SET def='".$def."', content='$content', type_meta='".$type_meta."', type_uri='".$type_uri."', uri='".$uri."', description='".$desc."', obligatoire='0'");
         Header("Location: admin.php?op=Meta-LangAdmin");
      }
   }
   if ($Maj_Bdd_ML=="edit_meta") {
      sql_query("UPDATE ".$NPDS_Prefix."metalang SET content='".$content."', type_meta='".$type_meta."', type_uri='".$type_uri."', uri='".$uri."', description='".$desc."' WHERE def='".$def."'");
      Header("Location: admin.php?op=Meta-LangAdmin");
   }
}

switch ($op) {
   case "List_Meta_Lang":
      List_Meta_Lang();
      break;

   case "Creat_Meta_Lang":
      Creat_Meta_Lang();
      break;

   case "Edit_Meta_Lang":
      Edit_Meta_Lang();
      break;

   case "Kill_Meta_Lang":
      kill_Meta_Lang($nbr, $action);
      break;

   case "Valid_Meta_Lang":
      Maj_Bdd_ML($Maj_Bdd_ML, $def, $content, $type_meta, $type_uri, $uri, $desc);
      break;

   default:
      List_Meta_Lang();
      break;
}
?>
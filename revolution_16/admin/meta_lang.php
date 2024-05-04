<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NEO - 2007                                                           */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2024 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

if (!function_exists('admindroits'))
   include('die.php');
$f_meta_nom ='MetaLangAdmin';
$f_titre = 'META-LANG';
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
global $language;
$hlpfile = 'manuels/'.$language.'/meta_lang.html';

function go_back($label) {
   if (!$label) $label = adm_translate("Retour en arrière");
      echo '
   <script type="text/javascript">
   //<![CDATA[
   function precedent() {
      document.write(\'<div class="mb-3 row"><div class="col-sm-12"><button class="btn btn-secondary my-3" onclick="history.back();" >'.$label.'</button></div></div>\');
   }
   precedent();
   //]]>
   </script>';
}

function list_meta($meta, $type_meta) {
   global $NPDS_Prefix;
   $sel='';
   $list = '
   <select class="form-select" name="meta" onchange="window.location=eval(\'this.options[this.selectedIndex].value\')">
      <option value="">META-MOT</option>';
   if (!empty($type_meta)) $Q = sql_query("SELECT def FROM ".$NPDS_Prefix."metalang WHERE type_meta = '".$type_meta."' ORDER BY type_meta, def ASC");
   else $Q = sql_query("SELECT def FROM ".$NPDS_Prefix."metalang ORDER BY 'def' ASC");
   while ($resultat = sql_fetch_row($Q))  {
      if ($meta == $resultat[0]) $sel = 'selected="selected"';
      $list .= '
      <option '.$sel.' value="admin.php?op=Meta-LangAdmin&amp;meta='.$resultat[0].'">'.$resultat[0].'</option>';
      $sel = '';
   }
   sql_free_result($Q);
   $list .= '
   </select>';
   return ($list);
}

function list_meta_type() {
   $list = '
   <select class="form-select" name="type_meta" onchange="window.location=eval(\'this.options[this.selectedIndex].value\')">
      <option value="">'.adm_translate("Type").'</option>
      <option value="admin.php?op=Creat_Meta_Lang&amp;type_meta=meta">meta</option>
      <option value="admin.php?op=Creat_Meta_Lang&amp;type_meta=mot">mot</option>
      <option value="admin.php?op=Creat_Meta_Lang&amp;type_meta=smil">smil</option>
      <option value="admin.php?op=Creat_Meta_Lang&amp;type_meta=them">them</option>
   </select>';
   return ($list);
}

function list_type_meta($type_meta) {
   global $NPDS_Prefix;
   $sel = ''; settype($url,'string');
   $list = '
   <select class="form-select" name="type_meta" onchange="window.location=eval(\'this.options[this.selectedIndex].value\')">
      <option value="'.$url.'">Type</option>';
   $Q = sql_query("SELECT type_meta FROM ".$NPDS_Prefix."metalang GROUP BY type_meta ORDER BY 'type_meta' ASC");
   while ($resultat = sql_fetch_row($Q))  {
      if ($type_meta == $resultat[0]) $sel = 'selected="selected"';
      $list .= '
      <option '.$sel.' value="admin.php?op=Meta-LangAdmin&amp;type_meta='.$resultat[0].'">'.$resultat[0].'</option>';
      $sel = '';
   }
   sql_free_result($Q);
   $list .= '
   </select>';
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
   $tablmeta=''; $tablmeta_c=''; $ibid=0;
   while (list($def, $content, $type_meta, $type_uri, $uri, $description, $obligatoire)= sql_fetch_row($Q)) {
      $tablmeta_c.= '
         <tr>
            <td>
               <input type="hidden" name="nbr" value="'.$ibid.'" />';
      if ($obligatoire == false) 
         $tablmeta_c.= '<a href="admin.php?op=Edit_Meta_Lang&amp;ml='.urlencode($def).'"><i class="fa fa-edit fa-lg" title="Editer ce m&#xE9;ta-mot" data-bs-toggle="tooltip" data-bs-placement="right"></i></a>&nbsp;&nbsp;<i class="fas fa-trash fa-lg text-body-secondary" title="Effacer ce m&#xE9;ta-mot" data-bs-toggle="tooltip" data-bs-placement="right"></i>&nbsp;<input type="checkbox" name="action['.$ibid.']" value="'.$def.'" />';
      else $tablmeta_c.= '<a href="admin.php?op=Edit_Meta_Lang&amp;ml='.urlencode($def).'" ><i class="fa fa-eye fa-lg" title="Voir le code de ce m&#xE9;ta-mot" data-bs-toggle="tooltip" ></i></a>';
      $tablmeta_c.='
            </td>
            <td><code>'.$def.'</code></td>
            <td>'.$type_meta.'</td>';
      if ($type_meta=='smil') {
         eval($content);
         $tablmeta_c.= '
            <td>'.$cmd.'</td>';
      }
      else if ($type_meta=='mot') {
         $tablmeta_c.= '
         <td>'.$content.'</td>';
      }
      else {
         $tablmeta_c.= '
         <td>'.aff_langue($description).'</td>';
      }
      $tablmeta_c.='
      </tr>';
      $ibid++;
   }
   sql_free_result($Q);

   $tablmeta.= '
   <hr />
   <h3><a href="admin.php?op=Creat_Meta_Lang"><i class="fa fa-plus-square"></i></a>&nbsp;'.adm_translate("Créer un nouveau").' META-MOT</h3>
   <hr />
   <h3>'. adm_translate("Recherche rapide").'</h3>
   <div class="row">
      <div class="col-sm-3">'.list_meta($meta, $type_meta).'</div>
      <div class="col-sm-3">'.list_type_meta($type_meta).'</div>
   </div>
   <hr />
   <h3>META-MOT <span class="tag tag-default float-end">'.$ibid.'</span></h3>
   <form name="admin_meta_lang" action="admin.php" method="post" onkeypress="return event.keyCode != 13;" onsubmit="return confirm(\''.adm_translate("Supprimer").' ?\')">
   <table data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-icons-prefix="fa" data-icons="icons" >
      <thead>
         <tr>
            <th class="n-t-col-xs-2" data-sortable="true" data-halign="center" data-align="right">'.adm_translate("Fonctions").'</th>
            <th data-sortable="true" data-halign="center" >'.adm_translate("Nom").'</th>
            <th class="n-t-col-xs-2" data-sortable="true" data-halign="center" >'.adm_translate("Type").'</th>
            <th data-sortable="true" data-halign="center" >'.adm_translate("Description").'</th>
         </tr>
      </thead>
      <tbody>';
   $tablmeta.= $tablmeta_c;
   $tablmeta.= '
      </tbody>
   </table>
   <div class="">
      <input type="hidden" name="op" value="Kill_Meta_Lang" />
      <button class="btn btn-danger my-2" type="submit" value="kill" title="'.adm_translate("Tout supprimer").'" data-bs-toggle="tooltip" data-bs-placement="right"><i class="fas fa-trash fa-lg"></i></button>
   </div>
   </form>';
   echo $tablmeta;
   adminfoot('','','','');
}

function Edit_Meta_Lang() {
   global $hlpfile, $NPDS_Prefix, $ml, $local_user_language, $language, $f_meta_nom, $f_titre, $adminimg;

   $Q = sql_query("SELECT def, content, type_meta, type_uri, uri, description, obligatoire FROM ".$NPDS_Prefix."metalang WHERE def = '".$ml."'");
   $Q = sql_fetch_assoc($Q);
   sql_free_result($Q);
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '<hr />';
   if ($Q['obligatoire'] != true) 
      echo '
   <h3>'.adm_translate("Modifier un ").' META-MOT</h3>';
   echo aff_local_langue('','local_user_language').'<br />','<label class="col-form-label">'.adm_translate("Langue de Prévisualisation").'</label>';
   echo '
   <div class="row">
      <div class="text-body-secondary col-sm-3">META</div>
      <div class="col-sm-9"><code>'.$Q['def'].'</code></div>
   </div>
   <div class="row">
      <div class="text-body-secondary col-sm-3">'.adm_translate("Type").'</div>
      <div class="col-sm-9">'.$Q['type_meta'].'</div>
   </div>
   <div class="row">
      <div class="text-body-secondary col-sm-3">'.adm_translate("Description").'</div>
      <div class="col-sm-9">';
   if ($Q['type_meta']=='smil') {
      eval($Q['content']);
      echo $cmd;
   }
   else
      echo preview_local_langue($local_user_language, aff_langue($Q['description']));
   echo '
      </div>
   </div>';
   if ($Q['type_meta']!='docu' and $Q['type_meta']!='them') {
      echo '
   <div class="row">
      <div class="text-body-secondary col-sm-12">'.adm_translate("Script").'</div>
      <div class=" col-sm-12">
         <pre class="language-php"><code class="language-php">'.htmlspecialchars($Q['content'], ENT_QUOTES).'</code></pre>
      </div>
   </div>';
   }
   if ($Q['obligatoire'] != true) {
      echo '
   <form id="metalangedit" name="edit_meta_lang" action="admin.php" method="post">
      <div class="form-floating mb-3">
         <input class="form-control" type="text" id="def" name="def" value="'.$Q['def'].'" readonly="readonly" />
         <label for="def">META</label>
     </div>
      <div class="form-floating mb-3">
         <input class="form-control" type="text" id="typemeta" name="type_meta" value="'.$Q['type_meta'].'" maxlength="10" readonly="readonly" />
         <label for="typemeta">'.adm_translate("Type").'</label>
     </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-12" for="desc">'.adm_translate("Description").'</label>
         <div class="col-sm-12">';
      if ($Q['type_meta']=='smil') {
          eval($Q['content']);
          echo $cmd.'</div></div>';
      } else
         echo '
            <textarea class="form-control" id="desc" name="desc" rows="7" >'.$Q['description'].'</textarea>
         </div>
      </div>';

      if ($Q['type_meta']!="docu" and $Q['type_meta']!="them") {
         echo '
      <div class="mb-3 row">
         <label class="col-form-label col-sm-12" for="content">'.adm_translate("Script").'</label>
         <div class="col-sm-12">
            <textarea class="form-control" id="content" name="content" rows="20"required="required" >'.$Q['content'].'</textarea>
         </div>
      </div>';
      }
      echo '
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="typeuri">'.adm_translate("Restriction").'</label>';
      $sel0='';$sel1='';
      if ($Q['type_uri'] == '+') {
         if ($Q['obligatoire'] == true) $sel1 = 'selected="selected"';
         else $sel1 = ' selected';
      }
      else {
         if ($Q['obligatoire'] == true) $sel0 = 'selected="selected"';
         else $sel0 = ' selected';
      }
      echo '
      <div class="col-sm-8">
         <select class="form-select" id="typeuri" name="type_uri">
            <option'.$sel0.' value="moins">'.adm_translate("Tous sauf pour ...").'</option>
            <option'.$sel1.' value="plus">'.adm_translate("Seulement pour ...").'</option>
         </select>
         <div class="help-block">...
      '.adm_translate("les URLs que vous aurez renseignés ci-après (ne renseigner que la racine de l'URI)").'<br />
      '.adm_translate("Exemple").' : index.php user.php forum.php static.php<br />
      '.adm_translate("Par défaut, rien ou Tout sauf pour ... [aucune URI] = aucune restriction").'
         </div>
         </div>
      </div>
      <div class="mb-3 row">
         <div class="col-sm-12">
            <textarea class="form-control" id="uri" name="uri" rows="7" maxlength="255">'.$Q['uri'].'</textarea>
            <span class="help-block text-end"><span id="countcar_uri"></span></span>
         </div>
      </div>
      <div class="mb-3 row">
         <div class="col-sm-12">
            <input type="hidden" name="Maj_Bdd_ML" value="edit_meta" />
            <input type="hidden" name="op" value="Valid_Meta_Lang" />
            <button class="btn btn-primary" type="submit">'.adm_translate("Valider").'</button>
         </div>
      </div>
   </form>';
   
     $arg1='
   var formulid = ["metalangedit"];
   inpandfieldlen("uri",255);';
   adminfoot('fv','',$arg1,'');

   }
   else {
      go_back('');
      adminfoot('','','','');
   }
}

function Creat_Meta_Lang() {
   global $hlpfile, $type_meta, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '
   <hr />
   <h3>'.adm_translate("Créer un nouveau").' META-MOT : <small>de type '.$type_meta.'</small></h3>
   <form id="metalangcreat" name="creat_meta_lang" action="admin.php" method="post">';
   if (!$type_meta)
      echo adm_translate("Veuillez choisir un type de META-MOT").' ';
   echo list_meta_type($type_meta);
   if ($type_meta) {
      echo '
      <div class="mb-3 row">
         <label class="col-form-label col-sm-12" for="def">META-MOT</label>
         <div class="col-sm-12">
            <input class="form-control" type="text" name="def" id="def" maxlength="50" required="required"/>
            <span class="help-block text-end"><span id="countcar_def"></span></span>
         </div>
      </div>';
      if ($type_meta != "smil") {
         echo '
      <div class="mb-3 row">
         <label class="col-form-label col-sm-12" for="desc">'.adm_translate("Description").'</label>
         <div class="col-sm-12">
            <textarea class="form-control" name="desc" id="desc" rows="7">[french]...[/french][english]...[/english]</textarea>
         </div>
      </div>';
      }
      if ($type_meta != "them") {
         echo '
      <div class="mb-3 row">
         <label class="col-form-label col-sm-12" for="content">'.adm_translate("Script").'</label>
         <div class="col-sm-12">';
         if ($type_meta == "smil")
            echo '
               <input class="form-control" type="text" name="content" id="content" maxlength="255" required="required" />
               <span class="help-block">'.adm_translate("Chemin et nom de l'image du Smiley").' Ex. : forum/smilies/pafmur.gif<span class="float-end ms-1" id="countcar_content"></span></span>
            </div>
         </div>';
         else
            echo '
            <textarea class="form-control" name="content" id="content" rows="20" required="required">';
            if ($type_meta=="meta") echo "function MM_XYZ (\$arg) {\n   global \$NPDS_Prefix;\n   \$arg = arg_filter(\$arg);\n\n   return(\$content);\n}";
            echo '
            </textarea>
         </div>
      </div>';
      }
      echo '
      <div class="mb-3 row">
         <label class="col-form-label col-sm-12" for="typeuri">'.adm_translate("Restriction").'</label>
         <div class="col-sm-12">
            <select class="form-select" id="typeuri" name="type_uri">
               <option value="moins">'.adm_translate("Tous sauf pour ...").'</option>
               <option value="plus">'.adm_translate("Seulement pour ...").'</option>
            </select>
         </div>
      </div>
      <div class="mb-3 row">
        <div class="col-sm-12">
           <div class="help-block">
            '.adm_translate("les URLs que vous aurez renseignés ci-après (ne renseigner que la racine de l'URI)").'<br />
            '.adm_translate("Exemple").' : index.php user.php forum.php static.php<br />
            '.adm_translate("Par defaut, rien ou Tout sauf pour ... [aucune URI] = aucune restriction").'
            </div>
            <textarea class="form-control" id="uri" name="uri" rows="7" maxlength="255"></textarea>
            <span class="help-block text-end"><span id="countcar_uri"></span></span>
         </div>
      </div>
      <div class="mb-3 row">
         <div class="col-sm-12">
            <input type="hidden" name="type_meta" value="'.$type_meta.'" />
            <input type="hidden" name="Maj_Bdd_ML" value="creat_meta" />
            <input type="hidden" name="op" value="Valid_Meta_Lang" />
            <button class="btn btn-primary" type="submit">'.adm_translate("Valider").'</button>
         </div>
      </div>';
   }
   echo '
   </form>';
   $arg1='
   var formulid = ["metalangcreat"];
   inpandfieldlen("def",50);
   inpandfieldlen("uri",255);';
   adminfoot('fv','',$arg1,'');
}

function kill_Meta_Lang($nbr, $action) {
   global $NPDS_Prefix;
   $i=0;
   while($i <= $nbr) {
      if (!empty($action[$i])) 
         sql_query("DELETE FROM ".$NPDS_Prefix."metalang WHERE def='".$action[$i]."' ");
      $i++;
   }
   Header("Location: admin.php?op=Meta-LangAdmin");
}

function meta_exist($def) {
   global $hlpfile, $language, $f_meta_nom, $f_titre, $adminimg;
   include("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '
   <hr />
   <div class="alert alert-danger">
      <strong>'.$def.'</strong>
      <br />'.adm_translate("Ce META-MOT existe déjà").'<br />'.adm_translate("Veuillez nommer différement ce nouveau META-MOT").'<br /><br />';
   echo go_back('');
   echo '
   </div>';
   adminfoot('','','','');
}

function Maj_Bdd_ML($Maj_Bdd_ML, $def, $content, $type_meta, $type_uri, $uri, $desc) {
   global $NPDS_Prefix;
   if ($type_uri =='plus') {$type_uri = '+';} else {$type_uri = '-';}
   if ($Maj_Bdd_ML=='creat_meta') {
      $def=trim($def);
      $Q = sql_query("SELECT def FROM ".$NPDS_Prefix."metalang WHERE def='".$def."'");
      $Q = sql_fetch_assoc($Q);
      sql_free_result($Q);
      if ($Q['def']) {
         meta_exist($Q['def']);
      } else {
         if ($type_meta=='smil')
            $content="\$cmd=MM_img(\"$content\");";
         if ($def!='')
            sql_query("INSERT INTO ".$NPDS_Prefix."metalang SET def='".$def."', content='$content', type_meta='".$type_meta."', type_uri='".$type_uri."', uri='".$uri."', description='".$desc."', obligatoire='0'");
         Header('Location: admin.php?op=Meta-LangAdmin');
      }
   }
   if ($Maj_Bdd_ML=='edit_meta') {
      sql_query("UPDATE ".$NPDS_Prefix."metalang SET content='".$content."', type_meta='".$type_meta."', type_uri='".$type_uri."', uri='".$uri."', description='".$desc."' WHERE def='".$def."'");
      Header('Location: admin.php?op=Meta-LangAdmin');
   }
}

switch ($op) {
   case 'List_Meta_Lang':
      List_Meta_Lang();
   break;
   case 'Creat_Meta_Lang':
      Creat_Meta_Lang();
   break;
   case 'Edit_Meta_Lang':
      Edit_Meta_Lang();
   break;
   case 'Kill_Meta_Lang':
      kill_Meta_Lang($nbr, $action);
   break;
   case 'Valid_Meta_Lang':
      Maj_Bdd_ML($Maj_Bdd_ML, $def, $content, $type_meta, $type_uri, $uri, $desc);
   break;
   default:
      List_Meta_Lang();
   break;
}
?>
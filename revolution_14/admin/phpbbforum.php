<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on Parts of phpBB                                              */
/*                                                                      */
/* NPDS Copyright (c) 2002-2012 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],"admin.php")) { Access_Error(); }
$f_meta_nom ='ForumAdmin';
$f_titre = adm_translate('Gestion des forums');
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit

global $language, $adminimg, $admf_ext;
$hlpfile = "manuels/$language/forumcat.html";
include ("auth.php");
include ("functions.php");

function ForumAdmin() {
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '
   <h3>'.adm_translate("Catégories de Forum").'</h3>
   <table data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-mobile-responsive="true">
      <thead>
         <tr>
            <th data-sortable="true">'.adm_translate("Index").'&nbsp;</th>
            <th data-sortable="true">'.adm_translate("Nom").'&nbsp;</th>
            <th>'.adm_translate("Nombre de Forum(s)").'&nbsp;</th>
            <th>'.adm_translate("Fonctions").'&nbsp;</th>
         </tr>
      </thead>
      <tbody>';
   $result = sql_query("SELECT cat_id, cat_title FROM ".$NPDS_Prefix."catagories ORDER BY cat_id");
   while (list($cat_id, $cat_title) = sql_fetch_row($result)) {
      $gets = sql_query("SELECT count(*) as total FROM ".$NPDS_Prefix."forums WHERE cat_id='$cat_id'");
      $numbers= sql_fetch_assoc($gets);
      echo '
         <tr>
            <td align="left">'.$cat_id.'</td>
            <td align="left">'.StripSlashes($cat_title).'</td>
            <td align="center">'.$numbers['total'].' <a href="admin.php?op=ForumGo&amp;cat_id='.$cat_id.'"><i class="fa fa-eye fa-lg" title="'.adm_translate("Voir les forums de cette catégorie").': '.StripSlashes($cat_title).'." data-toggle="tooltip"></i></a></td>
            <td align="right"></a><a href="admin.php?op=ForumCatEdit&amp;cat_id='.$cat_id.'"><i class="fa fa-edit fa-lg" title="'.adm_translate("Editer").'" data-toggle="tooltip"></i></a> <a href="admin.php?op=ForumCatDel&amp;cat_id='.$cat_id.'&amp;ok=0"><i class="fa fa-trash-o fa-lg text-danger" title="'.adm_translate("Effacer").'" data-toggle="tooltip" ></i></a></td>
         </tr>';
   }
   echo '
       </tbody>
   </table>
   <h3>'.adm_translate("Ajouter une Catégorie").'</h3>
   <form action="admin.php" method="post">
      <div class="form-group">
         <div class="row">
            <label class="control-label col-sm-4 col-md-4" for="catagories">'.adm_translate("Nom").'</label>
            <div class="col-sm-8 col-md-8">
               <textarea class="form-control" type="text" name="catagories" id="catagories" rows="3" required="required"></textarea>
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
            <div class="col-sm-offset-4 col-sm-8">
               <input type="hidden" name="op" value="ForumCatAdd" />
               <button class="btn btn-primary col-xs-12" type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;'.adm_translate("Ajouter une Catégorie").'</button>
            </div>
         </div>
      </div>
   </form>';
   adminfoot('fv','','','');
}

function ForumGo($cat_id) {
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);

   $result = sql_query("select cat_title from ".$NPDS_Prefix."catagories where cat_id='$cat_id'");
   list($cat_title) = sql_fetch_row($result);
   $ctg=StripSlashes($cat_title);
   echo '
   <h3>'.adm_translate("Forum classé en").' '.$ctg.'</h3>
   <table data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-show-columns="true" data-mobile-responsive="true">
      <thead>
         <tr>
            <th data-sortable="true">'.adm_translate("Index").'&nbsp;</th>
            <th data-sortable="true">'.adm_translate("Nom").'&nbsp;</th>
            <th data-sortable="true">'.adm_translate("Modérateur(s)").'&nbsp;</th>
            <th data-sortable="true">'.adm_translate("Accès").'&nbsp;</th>
            <th data-sortable="true">'.adm_translate("Type").'&nbsp;</th>
            <th data-sortable="true">'.adm_translate("Mode").'&nbsp;</th>
            <th data-sortable="true">'.adm_translate("Attachement").'&nbsp;</th>
            <th data-sortable="true">'.adm_translate("Fonctions").'&nbsp;</th>
         </tr>
      </thead>
      <tbody>';
    $result = sql_query("select forum_id, forum_name, forum_access, forum_moderator, forum_type, arbre, attachement, forum_index from ".$NPDS_Prefix."forums where cat_id='$cat_id' order by forum_index,forum_id");
    while(list($forum_id, $forum_name, $forum_access, $forum_moderator, $forum_type, $arbre, $attachement, $forum_index) = sql_fetch_row($result)) {
        $moderator=str_replace(" ",", ",get_moderator($forum_moderator));
        echo '
         <tr>
            <td>'.$forum_index.'</td>
            <td>'.$forum_name.'</td>
            <td><i class="glyphicons glyphicons-user-flag"></i>&nbsp;'.$moderator.'</td>';
        switch($forum_access) {
        case (0):
           echo '
            <td>'.adm_translate("Publication Anonyme autorisée").'</td>';
           break;
        case (1):
           echo '
            <td>'.adm_translate("Utilisateur enregistré").'</td>';
           break;
        case (2):
           echo '
            <td>'.adm_translate("Modérateurs").'</td>';
           break;
        case (9):
           echo '
            <td>Forum '.adm_translate("Fermé").'</td>';
           break;
        }
        if ($forum_type==0) {
        echo '
            <td>'.adm_translate("Public").'</td>';
        } elseif ($forum_type==1) {
        echo '
            <td>'.adm_translate("Privé").'</td>';
        } elseif ($forum_type==5) {
        echo '
            <td>PHP + '.adm_translate("Groupe").'</td>';
        } elseif ($forum_type==6) {
        echo '
            <td>PHP</td>';
        } elseif ($forum_type==7) {
        echo '
            <td>'.adm_translate("Groupe").'</td>';
        } elseif ($forum_type==8) {
        echo '
            <td>'.adm_translate("Texte étendu").'</td>';
        } else {
        echo '
            <td>'.adm_translate("Caché").'</td>';
        }

        if ($arbre)
           echo '<td>'.adm_translate("Arbre").'</td>';
        else
           echo '<td>'.adm_translate("Standard").'</td>';

        if ($attachement)
           echo '<td class="text-danger">'.adm_translate("Oui").'</td>';
        else
           echo '<td>'.adm_translate("Non").'</td>';

        echo '<td><a href="admin.php?op=ForumGoEdit&amp;forum_id='.$forum_id.'&amp;ctg='.urlencode($ctg).'"><i class="fa fa-edit fa-lg" title="'.adm_translate("Editer").'" data-toggle="tooltip"></i></a>&nbsp;<a href="admin.php?op=ForumGoDel&amp;forum_id='.$forum_id.'&amp;ok=0"><i class="fa fa-trash-o fa-lg text-danger" title="'.adm_translate("Effacer").'" data-toggle="tooltip" ></i></a></td>
        </tr>';
    }
    echo '
      </tbody>
   </table>
   <h3>'.adm_translate("Ajouter plus de Forum pour").' '.$ctg.'</h3>
   <form action="admin.php" method="post">
      <div class="form-group">
         <div class="row">
            <label class="control-label col-sm-4 col-md-4" for="forum_index">'.adm_translate("Index").'</label>
            <div class="col-sm-8 col-md-8">
               <input class="form-control" type="number" name="forum_index" max="9999" />
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
            <label class="control-label col-sm-4 col-md-4" for="forum_name">'.adm_translate("Nom du forum").'</label>
            <div class="col-sm-8 col-md-8">
               <input class="form-control" type="text" name="forum_name" />
               <span class="help-block">'.adm_translate("(Redirection sur un forum externe : <.a href...)").'</span>
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
            <label class="control-label col-sm-4 col-md-4" for="forum_desc">'.adm_translate("Description").'</label>
            <div class="col-sm-8 col-md-8">
               <textarea class="form-control" name="forum_desc" rows="5"></textarea>
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
            <label class="control-label col-sm-4 col-md-4" for="forum_mod">'.adm_translate("Modérateur(s)").'</label>
            <div class="col-sm-8 col-md-8">
               <input id="l_forum_mod" class="form-control" type="text" name="forum_mod" required="required"/>
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
            <label class="control-label col-sm-4 col-md-4" for="forum_access">'.adm_translate("Niveau d'accès").'</label>
            <div class="col-sm-8 col-md-8">
                  <select class="form-control" name="forum_access">
                     <option value="0">'.adm_translate("Publication Anonyme autorisée").'</option>
                     <option value="1">'.adm_translate("Utilisateur enregistré uniquement").'</option>
                     <option value="2">'.adm_translate("Modérateurs uniquement").'</option>
                     <option value="9">'.adm_translate("Fermé").'</option>
                  </select>
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
            <label class="control-label col-sm-4 col-md-4" for="forum_type">'.adm_translate("Type").'</label>
            <div class="col-sm-8 col-md-8">
               <select class="form-control" name="forum_type" id="forum_type">
                  <option value="0">'.adm_translate("Public").'</option>
                  <option value="1">'.adm_translate("Privé").'</option>
                  <option value="5">PHP Script + '.adm_translate("Groupe").'</option>
                  <option value="6">PHP Script</option>
                  <option value="7">'.adm_translate("Groupe").'</option>
                  <option value="8">'.adm_translate("Texte étendu").'</option>
                  <option value="9">'.adm_translate("Caché").'</option>
               </select>
            </div>
         </div>
      </div>
      <div class="form-group" id="the_multi_input_lol">
         <div class="row">
            <label class="control-label col-sm-4 col-md-4" for="forum_pass">'.adm_translate("- Mot de Passe (si Privé)<br />- Le nom du fichier de formulaire (si Texte étendu)<br /><span style=\"font-size: 10px;\">&nbsp;&nbsp;=> modules/sform/forum</span><br />- Les Groupes ID (si Groupe)").'</label>
            <div class="col-sm-8 col-md-8">
               <input class="form-control" type="text" name="forum_pass" id="forum_pass">
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
            <label class="control-label col-sm-4 col-md-4" for="arbre">'.adm_translate("Mode").'</label>
            <div class="col-sm-8 col-md-8">
               <select class="form-control" name="arbre">
                  <option value="0">'.adm_translate("Standard").'</option>
                  <option value="1">'.adm_translate("Arbre").'</option>
               </select>
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
            <label class="control-label col-sm-4 col-md-4" for="attachement">'.adm_translate("Attachement").'</label>
            <div class="col-sm-8 col-md-8">
                  <select class="form-control" name="attachement">
                     <option value="0">'.adm_translate("Non").'</option>
                     <option value="1">'.adm_translate("Oui").'</option>
                  </select>
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
            <div class="col-sm-offset-4 col-sm-8">
               <input type="hidden" name="ctg" value="'.$ctg.'" />
               <input type="hidden" name="cat_id" value="'.$cat_id.'" />
               <input type="hidden" name="op" value="ForumGoAdd" />
               <button class="btn btn-primary col-xs-12" type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;'.adm_translate("Ajouter").' </button>
            </div>
         </div>
      </div>
    </form>';
   echo auto_complete_multi ('modera','uname','users','l_forum_mod','where uid<>1');
   echo'
   <script type="text/javascript">
   //<![CDATA[
      var inp = $("#the_multi_input_lol");
      var htmh="",htmhe="",htmf="",lab="",inp_para="";
      htmh +=\'      <div class="form-group" id="the_multi_input_lol">\n\';
      htmh +=\'         <div class="row">\n\';
      htmh +=\'            <label class="control-label col-sm-4 col-md-4" for="forum_pass">\'
      htmhe +=\'</label>\n\';
      htmhe +=\'            <div class="col-sm-8 col-md-8">\n\';   
      htmf +=\'            </div>\n\';
      htmf +=\'         </div>\n\';
      htmf +=\'      </div>\n\';
      var select = $("#forum_type");
      select.change(function(){
         var type = $(this).val();
         switch (type) {
            case "1":
            lab="'.adm_translate("Mot de Passe").'";
            inp_para=\'<input class="form-control" type="password" name="forum_pass" id="forum_pass">\n\';
            inp.html(htmh+lab+htmhe+inp_para+htmf);
            break;
            case "7": case"5":
            lab="'.adm_translate("Groupe ID").'";
            inp_para=\'<input class="form-control" type="number" name="forum_pass" id="forum_pass" required="required">\n\';
            inp.html(htmh+lab+htmhe+inp_para+htmf);
            break;
            case "8":
            lab="'.adm_translate("Fichier de formulaire").'";
            inp_para=\'<input class="form-control" type="text" name="forum_pass" id="forum_pass">\n<span class="help-block">=> modules/sform/forum</span>\n\';
            inp.html(htmh+lab+htmhe+inp_para+htmf);
            break;
            default:
            inp.html("");
            break;
         }
      }).change();
   //]]>
   </script>';
    adminfoot('fv','','','');
}

function ForumGoEdit($forum_id, $ctg) {
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   $result = sql_query("select forum_id, forum_name, forum_desc, forum_access, forum_moderator, cat_id, forum_type, forum_pass, arbre, attachement, forum_index from ".$NPDS_Prefix."forums where forum_id='$forum_id'");
   list($forum_id, $forum_name, $forum_desc, $forum_access, $forum_mod, $cat_id_1, $forum_type, $forum_pass, $arbre, $attachement, $forum_index) = sql_fetch_row($result);
   adminhead ($f_meta_nom, $f_titre, $adminimg);

   echo '
   <h3>'.adm_translate("Editer").' : '.$forum_name.'</h3>
   <form id="fad_editforu" action="admin.php" method="post">
   <input type="hidden" name="forum_id" value="'.$forum_id.'" />
      <div class="form-group">
         <div class="row">
            <label class="control-label col-sm-4 col-md-4" for="forum_index">'.adm_translate("Index").'</label>
            <div class="col-sm-8 col-md-8">
               <input class="form-control" type="number" name="forum_index" max="9999" value="'.$forum_index.'" />
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
            <label class="control-label col-sm-4 col-md-4" for="forum_name">'.adm_translate("Nom du forum").'</label>
            <div class="col-sm-8 col-md-8">
               <input class="form-control" type="text" name="forum_name" value="'.$forum_name.'" />
               <span class="help-block">'.adm_translate("(Redirection sur un forum externe : <.a href...)").'</span>
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
            <label class="control-label col-sm-4 col-md-4" for="forum_desc">'.adm_translate("Description").'</label>
            <div class="col-sm-8 col-md-8">
               <textarea class="form-control" name="forum_desc" rows="5">'.$forum_desc.'</textarea>
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
            <label class="control-label col-sm-4 col-md-4" for="forum_mod">'.adm_translate("Modérateur(s)").'</label>';
   $moderator=str_replace(" ",",",get_moderator($forum_mod));
   echo '
            <div class="col-sm-8 col-md-8">
               <input id="forum_mod" class="form-control" type="text" name="forum_mod" value="'.$moderator.'," />
            </div>
         </div>
      </div>';
   echo '
      <div class="form-group">
         <div class="row">
            <label class="control-label col-sm-4 col-md-4" for="forum_access">'.adm_translate("Niveau d'accès").'</label>
            <div class="col-sm-8 col-md-8">
               <select class="form-control" name="forum_access">';
   if ($forum_access == 0) { $sel0=' selected="selected"'; }
   if ($forum_access == 1) { $sel1=' selected="selected"'; }
   if ($forum_access == 2) { $sel2=' selected="selected"'; }
   if ($forum_access == 9) { $sel9=' selected="selected"'; }
   echo '
                  <option value="0"'.$sel0.'>'.adm_translate("Publication Anonyme autorisée").'</option>
                  <option value="1"'.$sel1.'>'.adm_translate("Utilisateur enregistré uniquement").'</option>
                  <option value="2"'.$sel2.'>'.adm_translate("Modérateurs uniquement").'</option>
                  <option value="9"'.$sel9.'>'.adm_translate("Fermé").'</option>
               </select>
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
            <label class="control-label col-sm-4 col-md-4" for="cat_id">'.adm_translate("Changer les Catégories : ").' </label>
            <div class="col-sm-8 col-md-8">
               <select class="form-control" name="cat_id">';
   $result = sql_query("select cat_id, cat_title from ".$NPDS_Prefix."catagories");
   while(list($cat_id, $cat_title) = sql_fetch_row($result)) {
      if ($cat_id == $cat_id_1) {
         echo '
                  <option value="'.$cat_id.'" selected="selected">'.StripSlashes($cat_title).'</option>';
      } else {
      echo '
                  <option value="'.$cat_id.'">'.StripSlashes($cat_title).'</option>';
      }
   }
   echo '
               </select>
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
            <label class="control-label col-sm-4 col-md-4" for="forum_type">'.adm_translate("Type").'</label>
            <div class="col-sm-8 col-md-8">
               <select class="form-control" name="forum_type">';
   if ($forum_type == 0) $sel0=' selected="selected"'; else $sel0='';
   if ($forum_type == 1) $sel1=' selected="selected"'; else $sel1='';
   if ($forum_type == 5) $sel5=' selected="selected"'; else $sel5='';
   if ($forum_type == 6) $sel6=' selected="selected"'; else $sel6='';
   if ($forum_type == 7) $sel7=' selected="selected"'; else $sel7='';
   if ($forum_type == 8) $sel8=' selected="selected"'; else $sel8='';
   if ($forum_type == 9) $sel9=' selected="selected"'; else $sel9='';

   echo '
                  <option value="0"'.$sel0.'>'.adm_translate("Public").'</option>
                  <option value="1"'.$sel1.'>'.adm_translate("Privé").'</option>
                  <option value="5"'.$sel5.'>PHP Script + '.adm_translate("Groupe").'</option>
                  <option value="6"'.$sel6.'>PHP Script</option>
                  <option value="7"'.$sel7.'>'.adm_translate("Groupe").'</option>
                  <option value="8"'.$sel8.'>'.adm_translate("Texte étendu").'</option>
                  <option value="9"'.$sel9.'>'.adm_translate("Caché").'</option>
               </select>
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
            <label class="control-label col-sm-4 col-md-4" for="arbre">'.adm_translate("Mode").'</label>
            <div class="col-sm-8 col-md-8">
               <select class="form-control" name="arbre">';
   if ($arbre)
      echo '
                  <option value="0">'.adm_translate("Standard").'</option>
                  <option value="1" selected="selected">'.adm_translate("Arbre").'</option>';
   else
      echo '
                  <option value="0" selected="selected">'.adm_translate("Standard").'</option>
                  <option value="1">'.adm_translate("Arbre").'</option>';

   echo '
               </select>
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
            <label class="control-label col-sm-4 col-md-4" for="attachement">'.adm_translate("Attachement").'</label>
            <div class="col-sm-8 col-md-8">
               <select class="form-control" name="attachement">';
   if ($attachement)
      echo '
                  <option value="0">'.adm_translate("Non").'</option>
                  <option value="1" selected="selected">'.adm_translate("Oui").'</option>';
   else
      echo '
                  <option value="0" selected="selected">'.adm_translate("Non").'</option>
                  <option value="1">'.adm_translate("Oui").'</option>';
   echo '
               </select>
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
            <label class="control-label col-sm-4 col-md-4" for="forum_pass">'.adm_translate("- Mot de Passe (si Privé)<br />- Le nom du fichier de formulaire (si Texte étendu)<br /><span style=\"font-size: 10px;\">&nbsp;&nbsp;=> modules/sform/forum</span><br />- Les Groupes ID (si Groupe)").'</label>
            <div class="col-sm-8 col-md-8">
               <input class="form-control" type="text" name="forum_pass" value="'.$forum_pass.'" />
            </div>
         </div>
      </div>
      <input type="hidden" name="ctg" value="'.StripSlashes($ctg).'" />
      <input type="hidden" name="op" value="ForumGoSave" />
      <div class="form-group">
         <div class="row">
            <div class="col-sm-offset-4 col-sm-8">
                <button class="btn btn-primary" type="submit">'.adm_translate("Sauver les modifications").'</button>
            </div>
         </div>
      </div>
   </form>';
   echo auto_complete_multi ('modera','uname','users','forum_mod','where uid<>1');
   adminfoot('fv','','','');
}

function ForumCatEdit($cat_id) {
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   $result = sql_query("select cat_id, cat_title from ".$NPDS_Prefix."catagories where cat_id='$cat_id'");
   list($cat_id, $cat_title) = sql_fetch_row($result);
   echo '
   <h3>'.adm_translate("Editer les Catégories").'</h3>
   <form class="" action="admin.php" method="post">
      <div class="form-group">
         <div class="row">
            <label class="control-label col-sm-4" for="cat_id">ID</label>
            <div class="col-sm-8">
               <input class="form-control" type="number" name="cat_id" id="cat_id" value="'.$cat_id.'" required="required" />
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
            <label class="control-label col-sm-4" for="cat_title">'.adm_translate("Catégorie").'</label>
            <div class="col-sm-8">
               <input class="form-control" type="text" name="cat_title" value="'.StripSlashes($cat_title).'" required="required"/>
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
               <input type="hidden" name="old_cat_id" value="'.$cat_id.'" />
               <input type="hidden" name="op" value="ForumCatSave" />
               <div class="col-sm-offset-4 col-sm-8">
                  <button class="btn btn-primary col-sm-12" type="submit"><i class="fa fa-check-square fa-lg"></i>&nbsp;'.adm_translate("Sauver les modifications").'</button>
               </div>
         </div>
      </div>
   </form>';
   adminfoot('fv','','','');
}

function ForumCatSave($old_catid, $cat_id, $cat_title) {
    global $NPDS_Prefix;

    $return=sql_query("update ".$NPDS_Prefix."catagories set cat_id='$cat_id', cat_title='".AddSlashes($cat_title)."' where cat_id='$old_catid'");
    if ($return) {
       sql_query("update ".$NPDS_Prefix."forums set cat_id='$cat_id' where cat_id='$old_catid'");
    }
    Q_Clean();

    global $aid; Ecr_Log("security", "UpdateForumCat($old_catid, $cat_id, $cat_title) by AID : $aid", "");
    Header("Location: admin.php?op=ForumAdmin");
}

function ForumGoSave($forum_id, $forum_name, $forum_desc, $forum_access, $forum_mod, $cat_id, $forum_type, $forum_pass, $arbre, $attachement, $forum_index, $ctg) {
    global $hlpfile;
    global $NPDS_Prefix;

    // il faut supprimer le dernier , à cause de l'auto-complete
    $forum_mod=rtrim(chop($forum_mod),",");
    $moderator=explode(",",$forum_mod);

    $forum_mod="";
    $error_mod="";
    for ($i = 0; $i < count($moderator); $i++) {
       $result = sql_query("select uid from ".$NPDS_Prefix."users where uname='".trim($moderator[$i])."'");
       list($forum_moderator) = sql_fetch_row($result);
       if ($forum_moderator!="") {
          $forum_mod.=$forum_moderator." ";
          sql_query("update ".$NPDS_Prefix."users_status set level='2' where uid='$forum_moderator'");
       } else {
          $error_mod.=$moderator[$i]." ";
       }
    }
    if ($error_mod!="") {
       include ("header.php");
       GraphicAdmin($hlpfile);
       opentable();
       echo "<p align=\"center\">".adm_translate("Le Modérateur sélectionné n'existe pas.")." : $error_mod<br />";
       echo "[ <a href=\"javascript:history.go(-1)\" class=\"noir\">".adm_translate("Retour en arrière")."</a> ]</p>";
       closetable();
       include("footer.php");
    } else {
       $forum_mod=str_replace(" ",",",chop($forum_mod));
       if ($arbre>1) $arbre=1;
       if ($forum_pass) {
          if (($forum_type==7) and ($forum_access==0) ) {$forum_access=1;}
          sql_query("update ".$NPDS_Prefix."forums set forum_name='$forum_name', forum_desc='$forum_desc', forum_access='$forum_access', forum_moderator='$forum_mod', cat_id='$cat_id', forum_type='$forum_type', forum_pass='$forum_pass', arbre='$arbre', attachement='$attachement', forum_index='$forum_index' where forum_id='$forum_id'");
       } else {
          sql_query("update ".$NPDS_Prefix."forums set forum_name='$forum_name', forum_desc='$forum_desc', forum_access='$forum_access', forum_moderator='$forum_mod', cat_id='$cat_id', forum_type='$forum_type', forum_pass='', arbre='$arbre', attachement='$attachement', forum_index='$forum_index' where forum_id='$forum_id'");
       }
       Q_Clean();

       global $aid; Ecr_Log("security", "UpdateForum($forum_id, $forum_name) by AID : $aid", "");
       Header("Location: admin.php?op=ForumGo&cat_id=$cat_id");
    }
}

function ForumCatAdd($catagories) {
    global $NPDS_Prefix;

    sql_query("insert into ".$NPDS_Prefix."catagories values (NULL, '$catagories')");

    global $aid; Ecr_Log("security", "AddForumCat($catagories) by AID : $aid", "");
    Header("Location: admin.php?op=ForumAdmin");
}

function ForumGoAdd($forum_name, $forum_desc, $forum_access, $forum_mod, $cat_id, $forum_type, $forum_pass, $arbre, $attachement, $forum_index, $ctg) {
    global $hlpfile;
    global $NPDS_Prefix;

    // il faut supprimer le dernier , à cause de l'auto-complete
    $forum_mod=rtrim(chop($forum_mod),",");
    $moderator=explode(",",$forum_mod);

    $forum_mod="";
    $error_mod="";
    for ($i = 0; $i < count($moderator); $i++) {
       $result = sql_query("select uid from ".$NPDS_Prefix."users where uname='".trim($moderator[$i])."'");
       list($forum_moderator) = sql_fetch_row($result);
       if ($forum_moderator!="") {
          $forum_mod.=$forum_moderator." ";
          sql_query("update ".$NPDS_Prefix."users_status set level='2' where uid='$forum_moderator'");
       } else {
          $error_mod.=$moderator[$i]." ";
       }
    }
    if ($error_mod!="") {
       include ("header.php");
       GraphicAdmin($hlpfile);
       opentable();
       echo "<p align=\"center\">".adm_translate("Le Modérateur sélectionné n'existe pas.")." : $error_mod<br />";
       echo "[ <a href=\"javascript:history.go(-1)\" class=\"noir\">".adm_translate("Retour en arrière")."</a> ]</p>";
       closetable();
       include("footer.php");
    } else {
       if ($arbre>1) $arbre=1;
       $forum_mod=str_replace(" ",",",chop($forum_mod));
       sql_query("insert into ".$NPDS_Prefix."forums values (NULL, '$forum_name', '$forum_desc', '$forum_access', '$forum_mod', '$cat_id', '$forum_type', '$forum_pass', '$arbre', '$attachement', '$forum_index')");
       Q_Clean();

       global $aid; Ecr_Log("security", "AddForum($forum_name) by AID : $aid", "");
       Header("Location: admin.php?op=ForumGo&cat_id=$cat_id");
    }
}

function ForumCatDel($cat_id, $ok=0) {
    global $NPDS_Prefix, $hlpfile, $f_meta_nom, $f_titre, $adminimg;
    if ($ok==1) {
       $result = sql_query("select forum_id from ".$NPDS_Prefix."forums where cat_id='$cat_id'");
       while(list($forum_id) = sql_fetch_row($result)) {
           sql_query("delete from ".$NPDS_Prefix."forumtopics where forum_id='$forum_id'");
           sql_query("delete from ".$NPDS_Prefix."forum_read where forum_id='$forum_id'");
           control_efface_post("forum_npds","","",$forum_id);
       }
       sql_query("delete from ".$NPDS_Prefix."forums where cat_id='$cat_id'");
       sql_query("delete from ".$NPDS_Prefix."catagories where cat_id='$cat_id'");
       Q_Clean();

       global $aid; Ecr_Log("security", "DeleteForumCat($cat_id) by AID : $aid", "");
       Header("Location: admin.php?op=ForumAdmin");
    } else {
       include("header.php");
       GraphicAdmin($hlpfile);
       adminhead ($f_meta_nom, $f_titre, $adminimg);
       echo '
       <div class="jumbotron">
       <p class="text-danger">'.adm_translate("ATTENTION :  êtes-vous sûr de vouloir supprimer cette Catégorie, ses Forums et tous ses Sujets ?").'</p>';
    }
    echo '<a href="admin.php?op=ForumCatDel&amp;cat_id='.$cat_id.'&amp;ok=1" class="btn btn-danger ">'.adm_translate("Oui").'</a>&nbsp;<a href="admin.php?op=ForumAdmin" class="btn btn-secondary">'.adm_translate("Non").'</a>';
   echo '</div>';
   adminfoot('','','','');
}

function ForumGoDel($forum_id, $ok=0) {
    global $hlpfile;
    global $NPDS_Prefix;

    if ($ok==1) {
       sql_query("delete from ".$NPDS_Prefix."forumtopics where forum_id='$forum_id'");
       sql_query("delete from ".$NPDS_Prefix."forum_read where forum_id='$forum_id'");
       control_efface_post("forum_npds","","",$forum_id);
       sql_query("delete from ".$NPDS_Prefix."forums where forum_id='$forum_id'");
       Q_Clean();

       global $aid; Ecr_Log("security", "DeleteForum($forum_id) by AID : $aid", "");
       Header("Location: admin.php?op=ForumAdmin");
    } else {
       include("header.php");
       GraphicAdmin($hlpfile);
       opentable();
       echo "<p align=\"center\" class=\"rouge\"><b>".adm_translate("ATTENTION :  êtes-vous certain de vouloir effacer ce Forum et tous ses Sujets ?")."</b><br /><br />";
    }
    echo "[ <a href=\"admin.php?op=ForumGoDel&amp;forum_id=$forum_id&amp;ok=1\" class=\"rouge\">".adm_translate("Oui")."</a> | <a href=\"admin.php?op=ForumAdmin\" class=\"noir\">".adm_translate("Non")."</a> ]<br /><br />";
    closetable();
    include("footer.php");
}
?>
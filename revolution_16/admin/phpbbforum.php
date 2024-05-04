<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on Parts of phpBB                                              */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

if (!function_exists('admindroits'))
   include('die.php');
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
   <hr />
   <h3 class="mb-3">'.adm_translate("Catégories de Forum").'</h3>
   <table data-toggle="table" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-buttons-class="outline-secondary" data-icons="icons" data-icons-prefix="fa">
      <thead>
         <tr>
            <th class="n-t-col-xs-2" data-sortable="true" data-halign="center" data-align="right">'.adm_translate("Index").'&nbsp;</th>
            <th class="n-t-col-xs-5" data-sortable="true" data-halign="center">'.adm_translate("Nom").'&nbsp;</th>
            <th class="n-t-col-xs-3" data-halign="center" data-align="right">'.adm_translate("Nombre de Forum(s)").'&nbsp;</th>
            <th class="n-t-col-xs-2" data-halign="center" data-align="center">'.adm_translate("Fonctions").'&nbsp;</th>
         </tr>
      </thead>
      <tbody>';
   $result = sql_query("SELECT cat_id, cat_title FROM ".$NPDS_Prefix."catagories ORDER BY cat_id");
   while (list($cat_id, $cat_title) = sql_fetch_row($result)) {
      $gets = sql_query("SELECT COUNT(*) AS total FROM ".$NPDS_Prefix."forums WHERE cat_id='$cat_id'");
      $numbers= sql_fetch_assoc($gets);
      echo '
         <tr>
            <td>'.$cat_id.'</td>
            <td>'.StripSlashes($cat_title).'</td>
            <td>'.$numbers['total'].' <a href="admin.php?op=ForumGo&amp;cat_id='.$cat_id.'"><i class="fa fa-eye fa-lg align-middle" title="'.adm_translate("Voir les forums de cette catégorie").': '.StripSlashes($cat_title).'." data-bs-toggle="tooltip" data-bs-placement="right"></i></a></td>
            <td><a href="admin.php?op=ForumCatEdit&amp;cat_id='.$cat_id.'"><i class="fa fa-edit fa-lg" title="'.adm_translate("Editer").'" data-bs-toggle="tooltip"></i></a><a href="admin.php?op=ForumCatDel&amp;cat_id='.$cat_id.'&amp;ok=0"><i class="fas fa-trash fa-lg text-danger ms-3" title="'.adm_translate("Effacer").'" data-bs-toggle="tooltip" ></i></a></td>
         </tr>';
   }
   echo '
       </tbody>
   </table>
   <h3 class="my-3">'.adm_translate("Ajouter une catégorie").'</h3>
   <form id="forumaddcat" action="admin.php" method="post">
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="catagories">'.adm_translate("Nom").'</label>
         <div class="col-sm-8">
            <textarea class="form-control" name="catagories" id="catagories" rows="3" required="required"></textarea>
         </div>
      </div>
      <div class="mb-3 row">
         <div class="col-sm-8 ms-sm-auto">
            <input type="hidden" name="op" value="ForumCatAdd" />
            <button class="btn btn-primary col-12" type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;'.adm_translate("Ajouter une catégorie").'</button>
         </div>
      </div>
   </form>';
   $arg1='
   var formulid = ["forumaddcat"];';
   adminfoot('fv','',$arg1,'');
}

function ForumGo($cat_id) {
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);

   $result = sql_query("SELECT cat_title FROM ".$NPDS_Prefix."catagories WHERE cat_id='$cat_id'");
   list($cat_title) = sql_fetch_row($result);
   $ctg=StripSlashes($cat_title);
   echo '
   <hr />
   <h3 class="mb-3">'.adm_translate("Forum classé en").' '.$ctg.'</h3>
   <table data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-show-columns="true" data-mobile-responsive="true" data-buttons-class="outline-secondary" data-icons="icons" data-icons-prefix="fa">
      <thead>
         <tr>
            <th class="n-t-col-xs-1" data-sortable="true" data-halign="center" data-align="right">'.adm_translate("Index").'&nbsp;</th>
            <th data-sortable="true" data-halign="center">'.adm_translate("Nom").'&nbsp;</th>
            <th data-sortable="true" data-halign="center">'.adm_translate("Modérateur(s)").'&nbsp;</th>
            <th data-sortable="true" data-halign="center">'.adm_translate("Accès").'&nbsp;</th>
            <th data-sortable="true" data-halign="center">'.adm_translate("Type").'&nbsp;</th>
            <th data-sortable="true" data-halign="center">'.adm_translate("Mode").'&nbsp;</th>
            <th class="n-t-col-xs-1" data-sortable="true" data-halign="center" data-align="center"><img class="n-smil" src="images/forum/subject/07.png" alt="icon_pieces jointes" title="'.adm_translate("Attachement").'" data-bs-toggle="tooltip"></th>
            <th data-sortable="true" data-halign="center" data-align="center">'.adm_translate("Fonctions").'&nbsp;</th>
         </tr>
      </thead>
      <tbody>';
   $result = sql_query("SELECT forum_id, forum_name, forum_access, forum_moderator, forum_type, arbre, attachement, forum_index FROM ".$NPDS_Prefix."forums WHERE cat_id='$cat_id' ORDER BY forum_index,forum_id");
   while(list($forum_id, $forum_name, $forum_access, $forum_moderator, $forum_type, $arbre, $attachement, $forum_index) = sql_fetch_row($result)) {
      $moderator=str_replace(' ',', ',get_moderator($forum_moderator));
      echo '
         <tr>
            <td>'.$forum_index.'</td>
            <td>'.$forum_name.'</td>
            <td><i class="fa fa-balance-scale fa-lg fa-fw me-1"></i>'.$moderator.'</td>';
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
      if ($forum_type==0)
         echo '
            <td>'.adm_translate("Public").'</td>';
      elseif ($forum_type==1)
         echo '
            <td>'.adm_translate("Privé").'</td>';
      elseif ($forum_type==5)
         echo '
            <td>PHP + '.adm_translate("Groupe").'</td>';
      elseif ($forum_type==6)
         echo '
            <td>PHP</td>';
      elseif ($forum_type==7)
         echo '
            <td>'.adm_translate("Groupe").'</td>';
      elseif ($forum_type==8)
         echo '
            <td>'.adm_translate("Texte étendu").'</td>';
      else
         echo '
            <td>'.adm_translate("Caché").'</td>';
      if ($arbre)
         echo '
            <td>'.adm_translate("Arbre").'</td>';
      else
         echo '
            <td>'.adm_translate("Standard").'</td>';
      if ($attachement)
         echo '
            <td class="text-danger">'.adm_translate("Oui").'</td>';
      else
         echo '
            <td>'.adm_translate("Non").'</td>';
      echo '
            <td><a href="admin.php?op=ForumGoEdit&amp;forum_id='.$forum_id.'&amp;ctg='.urlencode($ctg).'"><i class="fa fa-edit fa-lg" title="'.adm_translate("Editer").'" data-bs-toggle="tooltip"></i></a><a href="admin.php?op=ForumGoDel&amp;forum_id='.$forum_id.'&amp;ok=0"><i class="fas fa-trash fa-lg text-danger ms-3" title="'.adm_translate("Effacer").'" data-bs-toggle="tooltip" ></i></a></td>
        </tr>';
   }
   echo '
      </tbody>
   </table>
   <h3 class="my-3">'.adm_translate("Ajouter plus de Forum pour").' : <span class="text-body-secondary">'.$ctg.'</span></h3>
   <form id="fadaddforu" action="admin.php" method="post">
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="forum_index">'.adm_translate("Index").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="forum_index" name="forum_index" max="9999" />
            <span class="help-block text-end" id="countcar_forum_index"></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="forum_name">'.adm_translate("Nom du forum").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="forum_name" name="forum_name" maxlength="150" required="required" />
            <span class="help-block">'.adm_translate("(Redirection sur un forum externe : <.a href...)").'<span class="float-end" id="countcar_forum_name"></span></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="forum_desc">'.adm_translate("Description").'</label>
         <div class="col-sm-8">
            <textarea class="form-control" id="forum_desc" name="forum_desc" rows="5"></textarea>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="forum_mod">'.adm_translate("Modérateur(s)").'</label>
         <div class="col-sm-8">
            <input id="l_forum_mod" class="form-control" type="text" id="forum_mod" name="forum_mod" required="required" />
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="forum_access">'.adm_translate("Niveau d'accès").'</label>
         <div class="col-sm-8">
            <select class="form-select" id="forum_access" name="forum_access">
               <option value="0">'.adm_translate("Publication Anonyme autorisée").'</option>
               <option value="1">'.adm_translate("Utilisateur enregistré uniquement").'</option>
               <option value="2">'.adm_translate("Modérateurs uniquement").'</option>
               <option value="9">'.adm_translate("Fermé").'</option>
            </select>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="forum_type">'.adm_translate("Type").'</label>
         <div class="col-sm-8">
            <select class="form-select" id="forum_type" name="forum_type" >
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
      <div class="mb-3 row d-none" id="the_multi_input">
         <label id="labmulti" class="col-form-label col-sm-4" for="forum_pass"></label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="forum_pass" name="forum_pass" />
            <span id="help_forum_pass" class="help-block"><span class="float-end" id="countcar_forum_pass"></span></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="arbre">'.adm_translate("Mode").'</label>
         <div class="col-sm-8">
            <select class="form-select" id="arbre" name="arbre">
               <option value="0">'.adm_translate("Standard").'</option>
               <option value="1">'.adm_translate("Arbre").'</option>
            </select>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="attachement">'.adm_translate("Attachement").'</label>
         <div class="col-sm-8">
            <select class="form-select" id="attachement" name="attachement">
               <option value="0">'.adm_translate("Non").'</option>
               <option value="1">'.adm_translate("Oui").'</option>
            </select>
         </div>
      </div>
      <div class="mb-3 row">
         <div class="col-sm-8 ms-sm-auto">
            <input type="hidden" name="ctg" value="'.$ctg.'" />
            <input type="hidden" name="cat_id" value="'.$cat_id.'" />
            <input type="hidden" name="op" value="ForumGoAdd" />
            <button class="btn btn-primary col-12" type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;'.adm_translate("Ajouter").' </button>
         </div>
      </div>
    </form>';
   echo auto_complete_multi ('modera','uname','users','l_forum_mod','WHERE uid<>1');
   $arg1='
   var formulid=["fadaddforu"];
   inpandfieldlen("forum_index",4);
   inpandfieldlen("forum_name",150);';
   $fv_parametres='
   forum_pass:{
      validators: {
         regexp: {
            enabled: false,
            regexp: /^([2-9]|[1-9][0-9]|[1][0-2][0-6])$/,
            message: "2...126",
         },
         stringLength: {
             min: 8,
             max:60,
             message: "> 8 < 60"
         },
         notEmpty: {
            enabled: true,
            message: "Required",
         }
      },
   },
   forum_index: {
      validators: {
         regexp: {
            regexp:/^([0-9]|[1-9][0-9]|[1-9][0-9][0-9]|[1-9][0-9][0-9][0-9])$/,
            message: "0-9999"
         }
      }
   },
   !###!
   var 
   inpOri = $("#the_multi_input"),
   helptext = $("#help_forum_pass"),
   oo = $("#forum_type").val(),
   labelo = $("#labmulti");
   const form  = document.getElementById("fadaddforu");
   const impu = document.getElementById("forum_pass");
   switch (oo){
      case "1":
         fvitem.enableValidator("forum_pass","notEmpty").disableValidator("forum_pass","regexp").enableValidator("forum_pass","stringLength")
      break;
      case "5": case "7":
         fvitem.enableValidator("forum_pass","notEmpty").enableValidator("forum_pass","regexp").disableValidator("forum_pass","stringLength");
      break;
      case "8":
         fvitem.enableValidator("forum_pass","notEmpty").disableValidator("forum_pass","regexp").disableValidator("forum_pass","stringLength");
      break;
      default:
         fvitem.disableValidator("forum_pass","notEmpty").disableValidator("forum_pass","regexp").disableValidator("forum_pass","stringLength");
      break;
   }
   

   form.querySelector(\'[name="forum_type"]\').addEventListener("change", function(e) {
      switch (e.target.value) {
         case "1":
            inpOri.removeClass("d-none").addClass("d-flex");
            $("#forum_pass").val("").attr({type:"password", maxlength:"60", required:"required"});
            helptext.html("<span class=\"float-end\" id=\"countcar_forum_pass\"></span>")
            labelo.html("'.adm_translate("Mot de Passe").'");
            fvitem.enableValidator("forum_pass","notEmpty").disableValidator("forum_pass","regexp").enableValidator("forum_pass","stringLength")
         break;
         case "5": case "7":
            inpOri.removeClass("d-none").addClass("d-flex");
            $("#forum_pass").val("").attr({type:"text", maxlength:"3", required:"required"});
            helptext.html("2...126<span class=\"float-end\" id=\"countcar_forum_pass\"></span>");
            labelo.html("'.adm_translate("Groupe ID").'");
            fvitem.enableValidator("forum_pass","notEmpty").enableValidator("forum_pass","regexp").disableValidator("forum_pass","stringLength");
        break;
         case "8":
            inpOri.removeClass("d-none").addClass("d-flex");
            $("#forum_pass").val("").attr({type:"text", maxlength:"60", required:"required"});
            helptext.html("=> modules/sform/forum<span class=\"float-end\" id=\"countcar_forum_pass\"></span>")
            labelo.html("'.adm_translate("Fichier de formulaire").'");
            fvitem.enableValidator("forum_pass","notEmpty").disableValidator("forum_pass","regexp").disableValidator("forum_pass","stringLength");
         break;
         default:
            inpOri.removeClass("d-flex").addClass("d-none");
            $("#forum_pass").val("");
            fvitem.disableValidator("forum_pass","notEmpty").disableValidator("forum_pass","regexp").disableValidator("forum_pass","stringLength")
         break;
      }
   });
   impu.addEventListener("input", function(e) {fvitem.revalidateField("forum_pass");});';

   adminfoot('fv',$fv_parametres,$arg1,'');
}

function ForumGoEdit($forum_id, $ctg) {
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   $result = sql_query("SELECT forum_id, forum_name, forum_desc, forum_access, forum_moderator, cat_id, forum_type, forum_pass, arbre, attachement, forum_index FROM ".$NPDS_Prefix."forums WHERE forum_id='$forum_id'");
   list($forum_id, $forum_name, $forum_desc, $forum_access, $forum_mod, $cat_id_1, $forum_type, $forum_pass, $arbre, $attachement, $forum_index) = sql_fetch_row($result);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   settype($sel0,'string');
   settype($sel1,'string');
   settype($sel2,'string');
   settype($sel5,'string');
   settype($sel6,'string');
   settype($sel7,'string');
   settype($sel8,'string');
   settype($sel9,'string');
   echo '
   <hr />
   <h3 class="mb-3">'.adm_translate("Editer").' : <span class="text-body-secondary">'.$forum_name.'</span></h3>
   <form id="fadeditforu" action="admin.php" method="post">
   <input type="hidden" name="forum_id" value="'.$forum_id.'" />
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="forum_index">'.adm_translate("Index").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="forum_index" name="forum_index" value="'.$forum_index.'" required="required" />
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="forum_name">'.adm_translate("Nom du forum").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="forum_name" name="forum_name" value="'.$forum_name.'" required="required" />
            <span class="help-block">'.adm_translate("(Redirection sur un forum externe : <.a href...)").'<span class="float-end" id="countcar_forum_name"></span></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="forum_desc">'.adm_translate("Description").'</label>
         <div class="col-sm-8">
            <textarea class="form-control" id="forum_desc" name="forum_desc" rows="5">'.$forum_desc.'</textarea>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="forum_mod">'.adm_translate("Modérateur(s)").'</label>';
   $moderator=str_replace(' ',',',get_moderator($forum_mod));
   echo '
         <div class="col-sm-8">
            <input id="forum_mod" class="form-control" type="text" id="forum_mod" name="forum_mod" value="'.$moderator.'," />
         </div>
      </div>';
   echo '
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="forum_access">'.adm_translate("Niveau d'accès").'</label>
         <div class="col-sm-8">
            <select class="form-select" id="forum_access" name="forum_access">';
   if ($forum_access == 0) $sel0=' selected="selected"';
   if ($forum_access == 1) $sel1=' selected="selected"';
   if ($forum_access == 2) $sel2=' selected="selected"';
   if ($forum_access == 9) $sel9=' selected="selected"';
   echo '
               <option value="0"'.$sel0.'>'.adm_translate("Publication Anonyme autorisée").'</option>
               <option value="1"'.$sel1.'>'.adm_translate("Utilisateur enregistré uniquement").'</option>
               <option value="2"'.$sel2.'>'.adm_translate("Modérateurs uniquement").'</option>
               <option value="9"'.$sel9.'>'.adm_translate("Fermé").'</option>
            </select>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="cat_id">'.adm_translate("Catégories").' </label>
         <div class="col-sm-8">
            <select class="form-select" id="cat_id" name="cat_id">';
   $result = sql_query("SELECT cat_id, cat_title FROM ".$NPDS_Prefix."catagories");
   while(list($cat_id, $cat_title) = sql_fetch_row($result)) {
      if ($cat_id == $cat_id_1)
         echo '
               <option value="'.$cat_id.'" selected="selected">'.StripSlashes($cat_title).'</option>';
      else
         echo '
               <option value="'.$cat_id.'">'.StripSlashes($cat_title).'</option>';
   }
   echo '
            </select>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="forum_type">'.adm_translate("Type").'</label>
         <div class="col-sm-8">
            <select class="form-select" id="forum_type" name="forum_type">';
   if ($forum_type == 0) $sel0=' selected="selected"'; else $sel0='';
   if ($forum_type == 1) $sel1=' selected="selected"'; else $sel1='';
   if ($forum_type == 5) $sel5=' selected="selected"'; else $sel5='';
   if ($forum_type == 6) $sel6=' selected="selected"'; else $sel6='';
   if ($forum_type == 7) $sel7=' selected="selected"'; else $sel7='';
   if ($forum_type == 8) $sel8=' selected="selected"'; else $sel8='';
   if ($forum_type == 9) $sel9=' selected="selected"'; else $sel9='';

   $lana='';$dinp='d-none';
   $attinp ='type="text" ';
   $helpinp='';
   switch($forum_type){
   case '1':
      $dinp='d-flex';
      $lana='Mot de Passe';
      $attinp =' type="password" maxlength="60"';
      $helpinp='';
   break;
   case '5':
      $dinp='d-flex';
      $lana='Groupe ID';
      $attinp =' type="text" maxlength="3"';
      $helpinp='';
   break;
   case '7':
      $dinp='d-flex';
      $lana='Groupe ID';
      $attinp =' type="text" maxlength="3"';
      $helpinp='';
   break;
   case '8':
      $dinp='d-flex';
      $lana='Fichier de formulaire';
      $attinp ='type="text" maxlength="60"';
      $helpinp='=> modules/sform/forum';
   break;
   }

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
      <div class="mb-3 row '.$dinp.'" id="the_multi_input">
         <label id="labmulti" class="col-form-label col-sm-4" for="forum_pass">'.adm_translate($lana).'</label>
         <div class="col-sm-8">
            <input class="form-control" '.$attinp.' id="forum_pass" name="forum_pass" value="'.$forum_pass.'" />
            <span id="help_forum_pass" class="help-block">'.$helpinp.'<span class="float-end" id="countcar_forum_pass"></span></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="arbre">'.adm_translate("Mode").'</label>
         <div class="col-sm-8">
            <select class="form-select" id="arbre" name="arbre">';
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
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="attachement">'.adm_translate("Attachement").'</label>
         <div class="col-sm-8">
            <select class="form-select" id="attachement" name="attachement">';
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
      <input type="hidden" name="ctg" value="'.StripSlashes($ctg).'" />
      <input type="hidden" name="op" value="ForumGoSave" />
      <div class="mb-3 row">
         <div class="col-sm-8 ms-sm-auto">
             <button class="btn btn-primary" type="submit">'.adm_translate("Sauver les modifications").'</button>
         </div>
      </div>
   </form>';
   echo auto_complete_multi ('modera','uname','users','forum_mod','WHERE uid<>1');
   $arg1='
   var formulid=["fadeditforu"];
   inpandfieldlen("forum_name",150);';
   $fv_parametres='
   forum_pass:{
      validators: {
         regexp: {
            enabled: false,
            regexp: /^([2-9]|[1-9][0-9]|[1][0-2][0-6])$/,
            message: "2...126",
         },
         stringLength: {
            enabled: false,
            min: 8,
            max:60,
            message: "> 8 < 60"
         },
         notEmpty: {
            enabled: true,
            message: "Required",
         }
      },
   },
   forum_index: {
      validators: {
         regexp: {
            regexp:/^([0-9]|[1-9][0-9]|[1-9][0-9][0-9]|[1-9][0-9][0-9][0-9])$/,
            message: "0-9999"
         }
      }
   },
   !###!
   var 
   inpOri = $("#the_multi_input"),
   helptext = $("#help_forum_pass"),
   oo = $("#forum_type").val(),
   labelo = $("#labmulti");
   const form  = document.getElementById("fadeditforu");
   const impu = document.getElementById("forum_pass");
   switch (oo){
      case "1":
         fvitem.enableValidator("forum_pass","notEmpty").disableValidator("forum_pass","regexp").enableValidator("forum_pass","stringLength")
      break;
      case "5": case "7":
         fvitem.enableValidator("forum_pass","notEmpty").enableValidator("forum_pass","regexp").disableValidator("forum_pass","stringLength");
      break;
      case "8":
         fvitem.enableValidator("forum_pass","notEmpty").disableValidator("forum_pass","regexp").disableValidator("forum_pass","stringLength");
      break;
      default:
         fvitem.disableValidator("forum_pass","notEmpty").disableValidator("forum_pass","regexp").disableValidator("forum_pass","stringLength");
      break;
   }
   form.querySelector(\'[name="forum_type"]\').addEventListener("change", function(e) {
      switch (e.target.value) {
         case "1":
            inpOri.removeClass("d-none").addClass("d-flex");
            $("#forum_pass").val("").attr({type:"password", maxlength:"60", required:"required"});
            helptext.html("<span class=\"float-end\" id=\"countcar_forum_pass\"></span>")
            labelo.html("'.adm_translate("Mot de Passe").'");
            fvitem.enableValidator("forum_pass","notEmpty").disableValidator("forum_pass","regexp").enableValidator("forum_pass","stringLength");
         break;
         case "5": case "7":
            inpOri.removeClass("d-none").addClass("d-flex");
            $("#forum_pass").val("").attr({type:"text", maxlength:"3", required:"required"});
            helptext.html("2...126<span class=\"float-end\" id=\"countcar_forum_pass\"></span>");
            labelo.html("'.adm_translate("Groupe ID").'");
            fvitem.enableValidator("forum_pass","notEmpty").enableValidator("forum_pass","regexp").disableValidator("forum_pass","stringLength");
        break;
         case "8":
            inpOri.removeClass("d-none").addClass("d-flex");
            $("#forum_pass").val("").attr({type:"text", maxlength:"60", required:"required"});
            helptext.html("=> modules/sform/forum<span class=\"float-end\" id=\"countcar_forum_pass\"></span>")
            labelo.html("'.adm_translate("Fichier de formulaire").'");
            fvitem.enableValidator("forum_pass","notEmpty").disableValidator("forum_pass","regexp").disableValidator("forum_pass","stringLength");
         break;
         default:
            inpOri.removeClass("d-flex").addClass("d-none");
            $("#forum_pass").val("");
            fvitem.disableValidator("forum_pass","notEmpty").disableValidator("forum_pass","regexp").disableValidator("forum_pass","stringLength");
         break;
      }
   });
   impu.addEventListener("input", function(e) {fvitem.revalidateField("forum_pass");});';
   adminfoot('fv',$fv_parametres,$arg1,'');
}

function ForumCatEdit($cat_id) {
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   $result = sql_query("SELECT cat_id, cat_title FROM ".$NPDS_Prefix."catagories WHERE cat_id='$cat_id'");
   list($cat_id, $cat_title) = sql_fetch_row($result);
   echo '
   <hr />
   <h3 class="mb-3">'.adm_translate("Editer la catégorie").'</h3>
   <form id="phpbbforumedcat" action="admin.php" method="post">
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="cat_id">ID</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" name="cat_id" id="cat_id" value="'.$cat_id.'" required="required" />
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="cat_title">'.adm_translate("Catégorie").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="cat_title" name="cat_title" value="'.StripSlashes($cat_title).'" required="required" />
         </div>
      </div>
      <div class="mb-3 row">
         <input type="hidden" name="old_cat_id" value="'.$cat_id.'" />
         <input type="hidden" name="op" value="ForumCatSave" />
         <div class="col-sm-8 ms-sm-auto">
            <button class="btn btn-primary col-sm-12" type="submit"><i class="fa fa-check-square fa-lg"></i>&nbsp;'.adm_translate("Sauver les modifications").'</button>
         </div>
      </div>
   </form>';
   $fv_parametres='
   cat_id: {
      validators: {
         regexp: {
            regexp:/^(-|[1-9])(\d{0,10})$/,
            message: "0-9"
         },
         between: {
            min: -2147483648,
            max: 2147483647,
            message: "-2147483648 ... 2147483647"
         }
      }
   },';
   $arg1='
   var formulid=["phpbbforumedcat"];';
   adminfoot('fv',$fv_parametres,$arg1,'');
}

function ForumCatSave($old_catid, $cat_id, $cat_title) {
   global $NPDS_Prefix;
   $return=sql_query("UPDATE ".$NPDS_Prefix."catagories SET cat_id='$cat_id', cat_title='".AddSlashes($cat_title)."' WHERE cat_id='$old_catid'");
   if ($return)
      sql_query("UPDATE ".$NPDS_Prefix."forums SET cat_id='$cat_id' WHERE cat_id='$old_catid'");
   Q_Clean();
   global $aid; Ecr_Log("security", "UpdateForumCat($old_catid, $cat_id, $cat_title) by AID : $aid", '');
   Header("Location: admin.php?op=ForumAdmin");
}

function ForumGoSave($forum_id, $forum_name, $forum_desc, $forum_access, $forum_mod, $cat_id, $forum_type, $forum_pass, $arbre, $attachement, $forum_index, $ctg) {
   global $hlpfile, $NPDS_Prefix;

   // il faut supprimer le dernier , à cause de l'auto-complete
   $forum_mod=rtrim(chop($forum_mod),',');
   $moderator=explode(',',$forum_mod);
   $forum_mod='';
   $error_mod='';
   for ($i = 0; $i < count($moderator); $i++) {
      $result = sql_query("SELECT uid FROM ".$NPDS_Prefix."users WHERE uname='".trim($moderator[$i])."'");
      list($forum_moderator) = sql_fetch_row($result);
      if ($forum_moderator!='') {
         $forum_mod.=$forum_moderator.' ';
         sql_query("UPDATE ".$NPDS_Prefix."users_status SET level='2' WHERE uid='$forum_moderator'");
      } else
         $error_mod.=$moderator[$i].' ';
   }
   if ($error_mod!='') {
      include ("header.php");
      GraphicAdmin($hlpfile);
      echo "<div><p align=\"center\">".adm_translate("Le Modérateur sélectionné n'existe pas.")." : $error_mod<br />";
      echo "[ <a href=\"javascript:history.go(-1)\" >".adm_translate("Retour en arrière")."</a> ]</p></div>";
      include("footer.php");
   } else {
      $forum_mod=str_replace(' ',',',chop($forum_mod));
      if ($arbre>1) $arbre=1;
      if ($forum_pass) {
         if (($forum_type==7) and ($forum_access==0) ) {$forum_access=1;}
         sql_query("UPDATE ".$NPDS_Prefix."forums SET forum_name='$forum_name', forum_desc='$forum_desc', forum_access='$forum_access', forum_moderator='$forum_mod', cat_id='$cat_id', forum_type='$forum_type', forum_pass='$forum_pass', arbre='$arbre', attachement='$attachement', forum_index='$forum_index' WHERE forum_id='$forum_id'");
      } else
         sql_query("UPDATE ".$NPDS_Prefix."forums SET forum_name='$forum_name', forum_desc='$forum_desc', forum_access='$forum_access', forum_moderator='$forum_mod', cat_id='$cat_id', forum_type='$forum_type', forum_pass='', arbre='$arbre', attachement='$attachement', forum_index='$forum_index' WHERE forum_id='$forum_id'");
      Q_Clean();
      global $aid; Ecr_Log("security", "UpdateForum($forum_id, $forum_name) by AID : $aid", '');
      Header("Location: admin.php?op=ForumGo&cat_id=$cat_id");
   }
}

function ForumCatAdd($catagories) {
   global $NPDS_Prefix;
   sql_query("INSERT INTO ".$NPDS_Prefix."catagories VALUES (NULL, '$catagories')");
   global $aid; Ecr_Log('security', "AddForumCat($catagories) by AID : $aid", '');
   Header("Location: admin.php?op=ForumAdmin");
}

function ForumGoAdd($forum_name, $forum_desc, $forum_access, $forum_mod, $cat_id, $forum_type, $forum_pass, $arbre, $attachement, $forum_index, $ctg) {
   global $hlpfile, $NPDS_Prefix;
   // il faut supprimer le dernier , à cause de l'auto-complete
   $forum_mod=rtrim(chop($forum_mod),',');
   $moderator=explode(",",$forum_mod);
   $forum_mod='';
   $error_mod='';
   for ($i = 0; $i < count($moderator); $i++) {
      $result = sql_query("SELECT uid FROM ".$NPDS_Prefix."users WHERE uname='".trim($moderator[$i])."'");
      list($forum_moderator) = sql_fetch_row($result);
      if ($forum_moderator!='') {
         $forum_mod.=$forum_moderator.' ';
         sql_query("UPDATE ".$NPDS_Prefix."users_status SET level='2' WHERE uid='$forum_moderator'");
      } else
         $error_mod.=$moderator[$i].' ';
   }
   if ($error_mod!='') {
      include ("header.php");
      GraphicAdmin($hlpfile);
      echo '
      <div class="alert alert-danger">
         <p>'.adm_translate("Le Modérateur sélectionné n'existe pas.").' : '.$error_mod.'</p>
         <a href="javascript:history.go(-1)" class="btn btn-secondary">'.adm_translate("Retour en arrière").'</a>
      </div>';
      include("footer.php");
   } else {
      if ($arbre>1) $arbre=1;
      $forum_mod=str_replace(' ',',',chop($forum_mod));
      sql_query("INSERT INTO ".$NPDS_Prefix."forums VALUES (NULL, '$forum_name', '$forum_desc', '$forum_access', '$forum_mod', '$cat_id', '$forum_type', '$forum_pass', '$arbre', '$attachement', '$forum_index')");
      Q_Clean();
      global $aid; Ecr_Log("security", "AddForum($forum_name) by AID : $aid", "");
      Header("Location: admin.php?op=ForumGo&cat_id=$cat_id");
   }
}

function ForumCatDel($cat_id, $ok=0) {
   global $NPDS_Prefix, $hlpfile, $f_meta_nom, $f_titre, $adminimg;
   if ($ok==1) {
      $result = sql_query("SELECT forum_id FROM ".$NPDS_Prefix."forums WHERE cat_id='$cat_id'");
      while(list($forum_id) = sql_fetch_row($result)) {
         sql_query("DELETE FROM ".$NPDS_Prefix."forumtopics WHERE forum_id='$forum_id'");
         sql_query("DELETE FROM ".$NPDS_Prefix."forum_read WHERE forum_id='$forum_id'");
         control_efface_post("forum_npds","","",$forum_id);
         sql_query("DELETE FROM ".$NPDS_Prefix."posts WHERE forum_id='$forum_id'");
      }
      sql_query("DELETE FROM ".$NPDS_Prefix."forums WHERE cat_id='$cat_id'");
      sql_query("DELETE FROM ".$NPDS_Prefix."catagories WHERE cat_id='$cat_id'");
      Q_Clean();
      global $aid; Ecr_Log("security", "DeleteForumCat($cat_id) by AID : $aid", "");
      Header("Location: admin.php?op=ForumAdmin");
   } else {
      include("header.php");
      GraphicAdmin($hlpfile);
      adminhead ($f_meta_nom, $f_titre, $adminimg);
      echo '
      <hr />
      <div class="alert alert-danger">
         <p>'.adm_translate("ATTENTION :  êtes-vous sûr de vouloir supprimer cette Catégorie, ses Forums et tous ses Sujets ?").'</p>
         <a href="admin.php?op=ForumCatDel&amp;cat_id='.$cat_id.'&amp;ok=1" class="btn btn-danger me-2">'.adm_translate("Oui").'</a>
         <a href="admin.php?op=ForumAdmin" class="btn btn-secondary">'.adm_translate("Non").'</a>
      </div>';
   adminfoot('','','','');
   }
}

function ForumGoDel($forum_id, $ok=0) {
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   if ($ok==1) {
      sql_query("DELETE FROM ".$NPDS_Prefix."forumtopics WHERE forum_id='$forum_id'");
      sql_query("DELETE FROM ".$NPDS_Prefix."forum_read WHERE forum_id='$forum_id'");
      control_efface_post('forum_npds','','',$forum_id);
      sql_query("DELETE FROM ".$NPDS_Prefix."forums WHERE forum_id='$forum_id'");
      sql_query("DELETE FROM ".$NPDS_Prefix."posts WHERE forum_id='$forum_id'");
      Q_Clean();
      global $aid; Ecr_Log('security', "DeleteForum($forum_id) by AID : $aid", '');
      Header("Location: admin.php?op=ForumAdmin");
   } else {
      include('header.php');
      GraphicAdmin($hlpfile);
      adminhead ($f_meta_nom, $f_titre, $adminimg);
      echo '
      <hr />
      <div class="alert alert-danger">
         <p>'.adm_translate("ATTENTION :  êtes-vous certain de vouloir effacer ce Forum et tous ses Sujets ?").'</p>
         <a class="btn btn-danger me-2" href="admin.php?op=ForumGoDel&amp;forum_id='.$forum_id.'&amp;ok=1">'.adm_translate("Oui").'</a>
         <a class="btn btn-secondary" href="admin.php?op=ForumAdmin" >'.adm_translate("Non").'</a>
      </div>';
      adminfoot('','','','');
   }
}
?>
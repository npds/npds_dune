<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2023 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

if (!function_exists('admindroits'))
   include('die.php');
$f_meta_nom ='FaqAdmin';
$f_titre = adm_translate("Faq");
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
global $language;
$hlpfile = "manuels/$language/faqs.html";

function FaqAdmin() {
   global $hlpfile, $NPDS_Prefix, $admf_ext, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '
   <hr />
   <h3 class="mb-3">'.adm_translate("Liste des catégories").'</h3>
   <table id="tad_faq" data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-icons-prefix="fa" data-icons="icons" data-buttons-class="outline-secondary">
      <thead class="thead-infos">
         <tr>
            <th data-sortable="true" data-halign="center" class="n-t-col-xs-10">'.adm_translate("Catégories").'</th>
            <th data-halign="center" data-align="center" class="n-t-col-xs-2">'.adm_translate("Fonctions").'</th>
         </tr>
      </thead>
      <tbody>';
   $result = sql_query("SELECT id_cat, categories FROM ".$NPDS_Prefix."faqcategories order by id_cat ASC");
   while(list($id_cat, $categories) = sql_fetch_row($result)) {
      echo '
         <tr>
            <td><span title="ID : '.$id_cat.'">'.aff_langue($categories).'</span><br /><a href="admin.php?op=FaqCatGo&amp;id_cat='.$id_cat.'" class="noir"><i class="fa fa-level-up-alt fa-lg fa-rotate-90 " title="'.adm_translate("Voir").'" data-bs-toggle="tooltip"></i>&nbsp;&nbsp;'.adm_translate("Questions & Réponses").'&nbsp;</a></td>
            <td><a href="admin.php?op=FaqCatEdit&amp;id_cat='.$id_cat.'"><i class="fa fa-edit fa-lg me-2" title="'.adm_translate("Editer").'" data-bs-toggle="tooltip"></i></a><a href="admin.php?op=FaqCatDel&amp;id_cat='.$id_cat.'&amp;ok=0"><i class="fas fa-trash fa-lg text-danger" title="'.adm_translate("Effacer").'" data-bs-toggle="tooltip"></a></td>
         </tr>';
   }
   echo '
      </tbody>
   </table>
   <hr />
   <h3 class="mb-3">'.adm_translate("Ajouter une catégorie").'</h3>
   <form id="adminfaqcatad" action="admin.php" method="post">
      <fieldset>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-12" for="categories">'.adm_translate("Nom").'</label>
            <div class="col-sm-12">
               <textarea class="form-control" type="text" name="categories" id="categories" maxlength="255" placeholder="'.adm_translate("Catégories").'" rows="3" required="required" ></textarea>
               <span class="help-block text-end"><span id="countcar_categories"></span></span>
            </div>
         </div>
         <div class="mb-3 row">
            <div class="col-sm-12">
               <button class="btn btn-outline-primary col-12" type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;'.adm_translate("Ajouter une catégorie").'</button>
               <input type="hidden" name="op" value="FaqCatAdd" />
            </div>
         </div>
      </fieldset>
   </form>';  
      $arg1='
      var formulid = ["adminfaqcatad"];
      inpandfieldlen("categories",255);
   ';
   adminfoot('fv','',$arg1,'');
}

function FaqCatGo($id_cat) {
   global $hlpfile, $NPDS_Prefix, $admf_ext, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   $lst_qr ='';

   $result = sql_query("SELECT fa.id, fa.question, fa.answer, fc.categories FROM ".$NPDS_Prefix."faqanswer fa LEFT JOIN ".$NPDS_Prefix."faqcategories fc ON fa.id_cat = fc.id_cat WHERE fa.id_cat='$id_cat' ORDER BY id");
   while(list($id, $question, $answer, $categories) = sql_fetch_row($result)) {
      $faq_cat = aff_langue($categories);
      $answer = aff_code(aff_langue($answer));
      $lst_qr.= '
      <li id="qr_'.$id.'" class="list-group-item">
         <div class="topi">
            <h5 id="q_'.$id.'" class="list-group-item-heading"><a class="" href="admin.php?op=FaqCatGoEdit&amp;id='.$id.'" title="'.adm_translate("Editer la question réponse").'" data-bs-toggle="tooltip">'.aff_langue($question).'</a></h5>
            <p class="list-group-item-text">'.meta_lang($answer).'</p>
            <div id="shortcut-tools_'.$id.'" class="n-shortcut-tools" style="display:none;"><a class="text-danger btn" href="admin.php?op=FaqCatGoDel&amp;id='.$id.'&amp;ok=0" ><i class="fas fa-trash fa-2x" title="'.adm_translate("Supprimer la question réponse").'" data-bs-toggle="tooltip" data-bs-placement="left"></i></a></div>
         </div>
      </li>';
   }
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '
   <hr />
   <h3 class="mb-3">'.$faq_cat.'</h3>
   <h4>'.adm_translate("Ajouter une question réponse").'</h4>
   <form id="adminfaqquest" action="admin.php" method="post" name="adminForm">
      <fieldset>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-12" for="question">'.adm_translate("Question").'</label>
            <div class="col-sm-12">
               <textarea class="form-control" type="text" name="question" id="question" maxlength="255"></textarea>
               <span class="help-block text-end"><span id="countcar_question"></span></span>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-12" for="answer">'.adm_translate("Réponse").'</label>
            <div class="col-sm-12">
               <textarea class="tin form-control" id="answer" name="answer" rows="15"></textarea>
            </div>
         </div>';
   echo aff_editeur("answer","false");
   echo '
         <div class="mb-3 row">
            <div class="col-sm-12 d-flex flex-row justify-content-start flex-wrap">
               <input type="hidden" name="id_cat" value="'.$id_cat.'" />
               <input type="hidden" name="op" value="FaqCatGoAdd" />'."\n".'
               <button class="btn btn-primary mb-2 " type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;'.adm_translate("Ajouter").'</button>&nbsp;
               <button class="btn btn-secondary mb-2 " href="admin.php?op=FaqAdmin">'.adm_translate("Retour en arrière").'</button>
            </div>
         </div>
      </fieldset>
   </form>
   <h4>'.adm_translate("Liste des questions réponses").'</h4>
   <ul class="list-group">
      '.$lst_qr.'
   </ul>
   <script type="text/javascript">
      //<![CDATA[
         $(document).ready(function() {
            var topid="";
            $(".topi").hover(function(){
               topid = $(this).parent().attr("id");
               topid = topid.substr(topid.search(/\d/))
               $button=$("#shortcut-tools_"+topid);
               $button.show();
            }, function(){
             $button.hide();
           });
        });
      //]]>
   </script>';
   $arg1='
      var formulid = ["adminfaqquest"];
      inpandfieldlen("question",255);
   ';
   adminfoot('fv','',$arg1,'');
}

function FaqCatEdit($id_cat) {
   global $hlpfile, $NPDS_Prefix, $admf_ext, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   $result = sql_query("SELECT categories FROM ".$NPDS_Prefix."faqcategories WHERE id_cat='$id_cat'");
   list($categories) = sql_fetch_row($result);
   echo '
   <hr />
   <h3 class="mb-3">'.adm_translate("Editer la catégorie").'</h3>
   <h4><a href="admin.php?op=FaqCatGo&amp;id_cat='.$id_cat.'">'.$categories.'</a></h4>
   <form id="adminfaqcated" action="admin.php" method="post">
      <fieldset>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-12" for="categories">'.adm_translate("Nom").'</label>
            <div class="col-sm-12">
               <textarea class="form-control" type="text" name="categories" id="categories" maxlength="255" rows="3" required="required" >'.$categories.'</textarea>
               <span class="help-block text-end"><span id="countcar_categories"></span></span>
            </div>
         </div>
         <div class="mb-3 row">
            <div class="col-sm-12">
               <input type="hidden" name="op" value="FaqCatSave" />
               <input type="hidden" name="old_id_cat" value="'.$id_cat.'" />
               <input type="hidden" name="id_cat" value="'.$id_cat.'" />
               <button class="btn btn-outline-primary col-12" type="submit"><i class="fa fa-check-square fa-lg"></i>&nbsp;'.adm_translate("Sauver les modifications").'</button>
            </div>
         </div>
      </fieldset>
   </form>';
   $arg1='
      var formulid = ["adminfaqcated"];
      inpandfieldlen("categories",255);
   ';
   adminfoot('fv','',$arg1,'');
}

function FaqCatGoEdit($id) {
   global $hlpfile, $NPDS_Prefix, $local_user_language, $admf_ext, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);

   $result = sql_query("SELECT fa.question, fa.answer, fa.id_cat, fc.categories FROM ".$NPDS_Prefix."faqanswer fa LEFT JOIN ".$NPDS_Prefix."faqcategories fc ON fa.id_cat = fc.id_cat WHERE fa.id='$id'");
   list($question, $answer, $id_cat, $faq_cat) = sql_fetch_row($result);

   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '
   <hr />
   <h3 class="mb-3">'.$faq_cat.'</h3>
   <h4>'.$question.'</h4>
   <h4>'.adm_translate("Prévisualiser").'</h4>';
   echo'
   <label class="col-form-label" for="">'
      .aff_local_langue('','local_user_language',adm_translate("Langue de Prévisualisation")).'
   </label>
   <div class="card card-body mb-3">
   <p>'.preview_local_langue($local_user_language, $question).'</p>';
   $answer= aff_code($answer);
   echo '<p>'.meta_lang(preview_local_langue($local_user_language, $answer)).'</p>
   </div>';

   echo '
   <h4>'.adm_translate("Editer Question & Réponse").'</h4>
   <form id="adminfaqquested" action="admin.php" method="post" name="adminForm">
      <fieldset>
         <div class="mb-3 row">
            <label class="col-form-label col-12" for="question">'.adm_translate("Question").'</label>
            <div class="col-sm-12">
               <textarea class="form-control" type="text" name="question" id="question" maxlength="255">'.$question.'</textarea>
               <span class="help-block text-end"><span id="countcar_question"></span></span>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-12" for="answer">'.adm_translate("Réponse").'</label>
            <div class="col-sm-12">
               <textarea class="tin form-control" name="answer" rows="15">'.$answer.'</textarea>
            </div>
         </div>
         '.aff_editeur('answer','').'
         <div class="mb-3 row">
            <div class="col-sm-12 d-flex flex-row justify-content-center flex-wrap">
               <input type="hidden" name="id" value="'.$id.'" />
               <input type="hidden" name="op" value="FaqCatGoSave" />
               <button class="btn btn-outline-primary col-sm-6 mb-2 " type="submit">'.adm_translate("Sauver les modifications").'</button>
               <button class="btn btn-outline-secondary col-sm-6 mb-2 " href="admin.php?op=FaqCatGo&amp;id_cat='.$id_cat.'" >'.adm_translate("Retour en arrière").'</a>
            </div>
         </div>
      </fieldset>
   </form>';
   $arg1='
      var formulid = ["adminfaqquested"];
      inpandfieldlen("question",255);
   ';
   adminfoot('fv','',$arg1,'');
}

function FaqCatSave($old_id_cat, $id_cat, $categories) {
   global $NPDS_Prefix;
   $categories = stripslashes(FixQuotes($categories));
   if ($old_id_cat!=$id_cat) {
      sql_query("UPDATE ".$NPDS_Prefix."faqanswer SET id_cat='$id_cat' WHERE id_cat='$old_id_cat'");
   }
   sql_query("UPDATE ".$NPDS_Prefix."faqcategories SET id_cat='$id_cat', categories='$categories' WHERE id_cat='$old_id_cat'");
   Header("Location: admin.php?op=FaqAdmin");
}

function FaqCatGoSave($id, $question, $answer) {
   global $NPDS_Prefix;
   $question = stripslashes(FixQuotes($question));
   $answer = stripslashes(FixQuotes($answer));
   sql_query("UPDATE ".$NPDS_Prefix."faqanswer SET question='$question', answer='$answer' WHERE id='$id'");
   Header("Location: admin.php?op=FaqCatGoEdit&id=$id");
}

function FaqCatAdd($categories) {
   global $NPDS_Prefix;
   $categories = stripslashes(FixQuotes($categories));
   sql_query("INSERT INTO ".$NPDS_Prefix."faqcategories VALUES (NULL, '$categories')");
   Header("Location: admin.php?op=FaqAdmin");
}

function FaqCatGoAdd($id_cat, $question, $answer) {
   global $NPDS_Prefix;
   $question = stripslashes(FixQuotes($question));
   $answer = stripslashes(FixQuotes($answer));
   sql_query("INSERT INTO ".$NPDS_Prefix."faqanswer VALUES (NULL, '$id_cat', '$question', '$answer')");
   Header("Location: admin.php?op=FaqCatGo&id_cat=$id_cat");
}

function FaqCatDel($id_cat, $ok=0) {
   global $NPDS_Prefix;
   if($ok==1) {
      sql_query("DELETE FROM ".$NPDS_Prefix."faqcategories WHERE id_cat='$id_cat'");
      sql_query("DELETE FROM ".$NPDS_Prefix."faqanswer WHERE id_cat='$id_cat'");
      Header("Location: admin.php?op=FaqAdmin");
   } else {
      global $hlpfile, $f_meta_nom, $f_titre, $adminimg;
      include("header.php");
      GraphicAdmin($hlpfile);
      adminhead ($f_meta_nom, $f_titre, $adminimg);
      echo '
      <hr />
      <div class="alert alert-danger">
         <p><strong>'.adm_translate("ATTENTION : êtes-vous sûr de vouloir effacer cette FAQ et toutes ses questions ?").'</strong></p>
         <a href="admin.php?op=FaqCatDel&amp;id_cat='.$id_cat.'&amp;ok=1" class="btn btn-danger btn-sm">'.adm_translate("Oui").'</a>&nbsp;<a href="admin.php?op=FaqAdmin" class="btn btn-secondary btn-sm">'.adm_translate("Non").'</a>
      </div>';
      include("footer.php");
   }
}

function FaqCatGoDel($id, $ok=0) {
   global $NPDS_Prefix;
   if($ok==1) {
      sql_query("DELETE FROM ".$NPDS_Prefix."faqanswer WHERE id='$id'");
      Header("Location: admin.php?op=FaqAdmin");
   } else {
      global $hlpfile, $f_meta_nom, $f_titre, $adminimg;
      include("header.php");
      GraphicAdmin($hlpfile);
      adminhead ($f_meta_nom, $f_titre, $adminimg);
      echo '
      <hr />
      <div class="alert alert-danger">
         <p><strong>'.adm_translate("ATTENTION : êtes-vous sûr de vouloir effacer cette question ?").'</strong></p>
         <a href="admin.php?op=FaqCatGoDel&amp;id='.$id.'&amp;ok=1" class="btn btn-danger btn-sm">'.adm_translate("Oui").'</a>&nbsp;<a href="admin.php?op=FaqAdmin" class="btn btn-secondary btn-sm">'.adm_translate("Non").'</a>
      </div>';
      include("footer.php");
   }
}
?>
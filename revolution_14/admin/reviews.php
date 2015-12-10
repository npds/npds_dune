<?php
/************************************************************************/
/* DUNE by NPDS - admin prototype                                       */
/* ===========================                                          */
/*                                                                      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!stristr($_SERVER['PHP_SELF'],"admin.php")) { Access_Error(); }
$f_meta_nom ='reviews';
$f_titre = adm_translate("Critiques");
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit

global $language;
$hlpfile = "manuels/$language/reviews.html";

function mod_main($title, $description) {
    global $NPDS_Prefix;

    $title = stripslashes(FixQuotes($title));
    $description = stripslashes(FixQuotes($description));
    sql_query("update ".$NPDS_Prefix."reviews_main set title='$title', description='$description'");
    Header("Location: admin.php?op=reviews");
}

function reviews() {
   global $hlpfile;
   global $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   $resultrm = sql_query("select title, description from ".$NPDS_Prefix."reviews_main");
   list($title, $description) = sql_fetch_row($resultrm);
         
   echo '
   <h3>'.adm_translate("Configuration de la page").'</h3>
   <form id="fad_pagereviews" class="" action="admin.php" method="post">
      <fieldset>
         <div class="form-group">
            <label for="tit_cri">'.adm_translate("Titre de la Page des Critiques").'</label>
            <input id="tit_cri" type="text" class="form-control" name="title" value="'.$title.'" maxlength="100" />
         </div>
         <div class="form-group">
            <label for="description">'.adm_translate("Description de la Page des Critiques").'</label>
            <textarea id="description" class="form-control" name="description" rows="10">'.$description.'</textarea>
         </div>
         <div class="form-group">
            <input type="hidden" name="op" value="mod_main" />
            <button class="btn btn-primary col-xs-12" type="submit"><i class="fa fa-check-square fa-lg"></i>&nbsp;'.adm_translate("Sauver les modifications").'</button>
         </div>
      </fieldset>
   </form>
   <h3>'.adm_translate("Critiques en attente de validation").'</h3>';
   $result = sql_query("select * from ".$NPDS_Prefix."reviews_add order by id");
   $numrows = sql_num_rows($result);
   if ($numrows>0) {
     while(list($id, $date, $title, $text, $reviewer, $email, $score, $url, $url_title) = sql_fetch_row($result)) {
        $title = stripslashes($title);
        $text = stripslashes($text);
        echo '
   <h4>'.adm_translate("Ajouter la critique N° : ").' '.$id.'</h4>
   <form id="fad_valreviews'.$id.'" action="admin.php" method="post">
   <input type="hidden" name="id" value="'.$id.'" />
      <div class="form-group">
         <div class="row">
            <label class="form-control-label col-sm-4" for="date">'.adm_translate("Date").'</label>
            <div class="col-sm-8">
               <div class="input-group input-append date" id="datePicker">
                  <input class="form-control" type="text" name="date" value="'.$date.'" maxlength="10" data-provide="datepicker" data-date-format="yyyy-mm-dd"  data-date-language="'.language_iso(1,'','').'" />
                  <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
               </div>
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
            <label class="form-control-label col-sm-4" for="title">'.adm_translate("Nom du produit").'</label>
            <div class="col-sm-8">
               <input class="form-control" type="text" name="title" value="'.$title.'" maxlength="40" />
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
         <label class="form-control-label col-sm-4 col-md-4" for="text'.$id.'">'.adm_translate("Texte").'</label>
            <div class="col-sm-8 col-md-8">
               <textarea class="form-control" name="text" rows="6">'.$text.'</textarea>
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
            <label class="form-control-label col-sm-4 col-md-4" for="reviewer">'.adm_translate("Le critique").'</label>
            <div class="col-sm-8 col-md-8">
               <input class="form-control" type="text" name="reviewer" value="'.$reviewer.'" maxlength="20" />
               <span class="help-block text-xs-right"><span id="countcar_reviewer"></span></span>
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
            <label class="form-control-label col-sm-4 col-md-4" for="email">'.adm_translate("E-mail").'</label>
            <div class="col-sm-8 col-md-8">
               <input class="form-control" type="email" id="email" name="email" value="'.$email.'" maxlength="30" />
               <span class="help-block text-xs-right"><span id="countcar_email"></span></span>
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
            <label class="form-control-label col-sm-4 col-md-4" for="score">'.adm_translate("Note").'</label>
            <div class="col-sm-8 col-md-8">
               <input class="form-control" type="number" id="score" name="score" value="'.$score.'"  min="1" max="10" />
            </div>
         </div>
      </div>';
         if ($url != "") {
            echo '
      <div class="form-group">
         <div class="row">
            <label class="form-control-label col-sm-4 col-md-4" for="url">'.adm_translate("Liens relatifs").'</label>
            <div class="col-sm-8 col-md-8">
               <input class="form-control" type="url" id="url" name="url" value="'.$url.'" maxlength="100" />
               <span class="help-block text-xs-right"><span id="countcar_url"></span></span>
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
            <label class="form-control-label col-sm-4 col-md-4" for="url_title">'.adm_translate("Titre du lien").'</label>
            <div class="col-sm-8 col-md-8">
               <input class="form-control" type="text" id="url_title" name="url_title" value="'.$url_title.'" maxlength="50" />
               <span class="help-block text-xs-right"><span id="countcar_url_title"></span></span>
            </div>
         </div>
      </div>';
        }
         echo '
      <div class="form-group">
         <div class="row">
            <label class="form-control-label col-sm-4" for="cover">'.adm_translate("Image de garde").'</label>
            <div class="col-sm-8">
               <input class="form-control" type="text" id="cover" name="cover" maxlength="100" />
               <span class="help-block">150*150 pixel => images/covers</span>
            </div>
         </div>
      </div>
      <div class="form-group">
         <input type="hidden" name="op" value="add_review">
         <button class="btn btn-primary" type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;'.adm_translate("Ajouter cette critique").'</button>
         <a href="admin.php?op=deleteNotice&amp;id=$id&amp;op_back=reviews" class="btn btn-danger" role="button">'.adm_translate("Supprimer cette Critique").'</a>
      </div>
   </form>';
     }
   } else {
     echo "<br />".adm_translate("Aucune critique à ajouter")."<br />";
   }
   echo "<hr noshade=\"noshade\" class=\"ongl\" /><p align=\"center\"><a href=\"reviews.php?op=write_review\" class=\"noir\">".adm_translate("Cliquer ici pour proposer une Critique.")."</a></p><hr noshade class=\"ongl\">";
   echo adm_translate("Effacer / Modifier une Critique");
   echo "<br />";
   echo adm_translate("Vous pouvez simplement Effacer / Modifier les Critiques en naviguant sur")." <a href=\"reviews.php\" class=\"noir\">reviews.php</a> ".adm_translate("en tant qu'Administrateur.")."<br />";

   adminfieldinp($result);
   sql_free_result($result);
   adminfoot('fv','','','');
}

function add_review($id, $date, $title, $text, $reviewer, $email, $score, $cover, $url, $url_title) {
    global $NPDS_Prefix;

    $title = stripslashes(FixQuotes($title));
    $text = stripslashes(FixQuotes($text));
    $reviewer = stripslashes(FixQuotes($reviewer));
    $email = stripslashes(FixQuotes($email));
    sql_query("insert into ".$NPDS_Prefix."reviews values (NULL, '$date', '$title', '$text', '$reviewer', '$email', '$score', '$cover', '$url', '$url_title', '1')");
    sql_query("delete from ".$NPDS_Prefix."reviews_add WHERE id = '$id'");
    Header("Location: admin.php?op=reviews");
}

switch ($op){
    case "reviews":
         reviews();
         break;

    case "add_review":
         add_review($id, $date, $title, $text, $reviewer, $email, $score, $cover, $url, $url_title);
         break;

    case "mod_main":
         mod_main($title, $description);
         break;
}
?>
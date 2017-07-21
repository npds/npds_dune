<?php
/************************************************************************/
/* DUNE by NPDS - admin prototype                                       */
/* ===========================                                          */
/*                                                                      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2017 by Philippe Brunier                     */
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
    sql_query("UPDATE ".$NPDS_Prefix."reviews_main SET title='$title', description='$description'");
    Header("Location: admin.php?op=reviews");
}

function reviews() {
   global $hlpfile;
   global $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   $resultrm = sql_query("SELECT title, description FROM ".$NPDS_Prefix."reviews_main");
   list($title, $description) = sql_fetch_row($resultrm);

   echo '
   <hr />
   <h3>'.adm_translate("Configuration de la page").'</h3>
   <form id="fad_pagereviews" class="" action="admin.php" method="post">
      <fieldset>
         <div class="form-group row">
            <label class="form-control-label col-sm-12" for="tit_cri">'.adm_translate("Titre de la Page des Critiques").'</label>
            <div class="col-sm-12">
               <input id="tit_cri" type="text" class="form-control" name="title" value="'.$title.'" maxlength="100" />
            </div>
         </div>
         <div class="form-group row">
            <label class="form-control-label col-sm-12" for="description">'.adm_translate("Description de la Page des Critiques").'</label>
            <div class="col-sm-12">
               <textarea id="description" class="form-control" name="description" rows="10">'.$description.'</textarea>
            </div>
         </div>
         <div class="form-group row">
            <div class="col-sm-12">
               <input type="hidden" name="op" value="mod_main" />
               <button class="btn btn-primary col-12" type="submit"><i class="fa fa-check-square fa-lg"></i>&nbsp;'.adm_translate("Sauver les modifications").'</button>
            </div>
         </div>
      </fieldset>
   </form>
   <hr />
   <h3>'.adm_translate("Critiques en attente de validation").'</h3>';
   $result = sql_query("SELECT * FROM ".$NPDS_Prefix."reviews_add ORDER BY id");
   $numrows = sql_num_rows($result);
   if ($numrows>0) {
     while(list($id, $date, $title, $text, $reviewer, $email, $score, $url, $url_title) = sql_fetch_row($result)) {
        $title = stripslashes($title);
        $text = stripslashes($text);
        echo '
   <h4>'.adm_translate("Ajouter la critique N° : ").' '.$id.'</h4>
   <form id="fad_valreviews'.$id.'" action="admin.php" method="post">
   <input type="hidden" name="id" value="'.$id.'" />
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="date">'.adm_translate("Date").'</label>
         <div class="col-sm-8">
            <div class="input-group input-append date" id="datePicker">
               <input class="form-control" type="text" name="date" value="'.$date.'" maxlength="10" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-language="'.language_iso(1,'','').'" />
               <span class="input-group-addon add-on"><span class="fa fa-calendar-check-o fa-lg"></span></span>
            </div>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="title">'.adm_translate("Nom du produit").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="title" name="title" value="'.$title.'" maxlength="40" />
            <span class="help-block text-right"><span id="countcar_title"></span></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4 " for="text'.$id.'">'.adm_translate("Texte").'</label>
         <div class="col-sm-8">
            <textarea class="form-control" name="text" rows="6">'.$text.'</textarea>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4 " for="reviewer">'.adm_translate("Le critique").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="reviewer" name="reviewer" value="'.$reviewer.'" maxlength="20" />
            <span class="help-block text-right"><span id="countcar_reviewer"></span></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4 " for="email">'.adm_translate("E-mail").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="email" id="email" name="email" value="'.$email.'" maxlength="60" />
            <span class="help-block text-right"><span id="countcar_email"></span></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4 " for="score">'.adm_translate("Note").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="number" id="score" name="score" value="'.$score.'"  min="1" max="10" />
         </div>
      </div>';
         if ($url != '') {
            echo '
      <div class="form-group row">
         <label class="form-control-label col-sm-4 " for="url">'.adm_translate("Liens relatifs").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="url" id="url" name="url" value="'.$url.'" maxlength="100" />
            <span class="help-block text-right"><span id="countcar_url"></span></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4 " for="url_title">'.adm_translate("Titre du lien").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="url_title" name="url_title" value="'.$url_title.'" maxlength="50" />
            <span class="help-block text-right"><span id="countcar_url_title"></span></span>
         </div>
      </div>';
        }
         echo '
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="cover">'.adm_translate("Image de garde").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="cover" name="cover" maxlength="100" />
            <span class="help-block">150*150 pixel => images/covers<span id="countcar_cover"></span></span>
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-8 ml-sm-auto">
            <input type="hidden" name="op" value="add_review">
            <button class="btn btn-primary" type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;'.adm_translate("Ajouter").'</button>
            <a href="admin.php?op=deleteNotice&amp;id='.$id.'&amp;op_back=reviews" class="btn btn-danger" role="button">'.adm_translate("Supprimer").'</a>
         </div>
      </div>
   </form>
   <script type="text/javascript" src="lib/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" async="async"></script>
   <script type="text/javascript" src="lib/bootstrap-datepicker/dist/locales/bootstrap-datepicker.'.language_iso(1,"","").'.min.js"></script>
   <script type="text/javascript">
      //<![CDATA[
      $(document).ready(function() {
         $("<link>")
            .appendTo("head")
            .attr({type: "text/css", rel: "stylesheet",href: "lib/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css"});

         inpandfieldlen("title",40);
         inpandfieldlen("reviewer",20);
         inpandfieldlen("email",60);
         inpandfieldlen("url",100);
         inpandfieldlen("url_title",50);
         inpandfieldlen("cover",100);
      });
      //]]>
      </script>';
     }
   } else {
     echo '<br />'.adm_translate("Aucune critique à ajouter").'<br />';
   }
   echo "<hr /><p align=\"center\"><a href=\"reviews.php?op=write_review\" >".adm_translate("Cliquer ici pour proposer une Critique.")."</a></p><hr />";
   echo '<h3>'.adm_translate("Effacer / Modifier une Critique").'</h3>';
   echo adm_translate("Vous pouvez simplement Effacer / Modifier les Critiques en naviguant sur").' <a href="reviews.php" >reviews.php</a> '.adm_translate("en tant qu'Administrateur.").'<br />';

   sql_free_result($result);
   adminfoot('fv','','','');
}

function add_review($id, $date, $title, $text, $reviewer, $email, $score, $cover, $url, $url_title) {
   global $NPDS_Prefix;

   $title = stripslashes(FixQuotes($title));
   $text = stripslashes(FixQuotes($text));
   $reviewer = stripslashes(FixQuotes($reviewer));
   $email = stripslashes(FixQuotes($email));
   sql_query("INSERT INTO ".$NPDS_Prefix."reviews VALUES (NULL, '$date', '$title', '$text', '$reviewer', '$email', '$score', '$cover', '$url', '$url_title', '1')");
   sql_query("DELETE FROM ".$NPDS_Prefix."reviews_add WHERE id = '$id'");
   Header("Location: admin.php?op=reviews");
}

switch ($op){
   case 'reviews':
      reviews();
   break;
   case 'add_review':
      add_review($id, $date, $title, $text, $reviewer, $email, $score, $cover, $url, $url_title);
   break;
   case 'mod_main':
      mod_main($title, $description);
   break;
}
?>
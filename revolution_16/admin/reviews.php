<?php
/************************************************************************/
/* DUNE by NPDS - admin prototype                                       */
/* ===========================                                          */
/*                                                                      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

if (!function_exists('admindroits'))
   include('die.php');
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
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   $resultrm = sql_query("SELECT title, description FROM ".$NPDS_Prefix."reviews_main");
   list($title, $description) = sql_fetch_row($resultrm);

   echo '
   <hr />
   <h3>'.adm_translate("Configuration de la page").'</h3>
   <form id="reviewspagecfg" class="" action="admin.php" method="post">
      <fieldset>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-12" for="tit_cri">'.adm_translate("Titre de la Page des Critiques").'</label>
            <div class="col-sm-12">
               <input class="form-control" type="text" id="tit_cri" name="title" value="'.$title.'" maxlength="100" />
               <span class="help-block text-end" id="countcar_tit_cri"></span>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-12" for="description">'.adm_translate("Description de la Page des Critiques").'</label>
            <div class="col-sm-12">
               <textarea class="form-control" id="description" name="description" rows="10">'.$description.'</textarea>
            </div>
         </div>
         <div class="mb-3 row">
            <div class="col-sm-12">
               <input type="hidden" name="op" value="mod_main" />
               <button class="btn btn-primary col-12" type="submit"><i class="fa fa-check-square fa-lg"></i>&nbsp;'.adm_translate("Sauver les modifications").'</button>
            </div>
         </div>
      </fieldset>
   </form>
   <hr />';
   $result = sql_query("SELECT * FROM ".$NPDS_Prefix."reviews_add ORDER BY id");
   $numrows = sql_num_rows($result);
   echo '<h3>'.adm_translate("Critiques en attente de validation").'<span class="badge bg-danger float-end">'.$numrows.'</span></h3>';
   $jsfvc='';$jsfvf='';
   if ($numrows>0) {
      while(list($id, $date, $title, $text, $reviewer, $email, $score, $url, $url_title) = sql_fetch_row($result)) {
         $title = stripslashes($title);
         $text = stripslashes($text);
         echo '
   <h4 class="my-3">'.adm_translate("Ajouter la critique N° : ").' '.$id.'</h4>
   <form id="reviewsaddcr'.$id.'" action="admin.php" method="post">
   <input type="hidden" name="id" value="'.$id.'" />
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="reviewdate">'.adm_translate("Date").'</label>
         <div class="col-sm-8">
            <div class="input-group">
               <span class="input-group-text"><i class="far fa-calendar-check fa-lg"></i></span>
               <input class="form-control reviewdate-js" type="text" id="reviewdate" name="date" value="'.$date.'" maxlength="10" required="required" />
            </div>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="title'.$id.'">'.adm_translate("Nom du produit").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="title'.$id.'" name="title" value="'.$title.'" maxlength="40" required="required" />
            <span class="help-block text-end" id="countcar_title'.$id.'"></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4 " for="text'.$id.'">'.adm_translate("Texte").'</label>
         <div class="col-sm-8">
            <textarea class="form-control" id="text'.$id.' name="text" rows="6">'.$text.'</textarea>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4 " for="reviewer'.$id.'">'.adm_translate("Le critique").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="reviewer'.$id.'" name="reviewer" value="'.$reviewer.'" maxlength="20" required="required" />
            <span class="help-block text-end" id="countcar_reviewer'.$id.'"></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4 " for="email'.$id.'">'.adm_translate("E-mail").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="email" id="email'.$id.'" name="email" value="'.$email.'" maxlength="60" required="required" />
            <span class="help-block text-end" id="countcar_email'.$id.'"></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4 " for="score'.$id.'">'.adm_translate("Note").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="number" id="score'.$id.'" name="score" value="'.$score.'"  min="1" max="10" />
         </div>
      </div>';
         if ($url != '') {
            echo '
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4 " for="url'.$id.'">'.adm_translate("Liens relatifs").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="url" id="url'.$id.'" name="url" value="'.$url.'" maxlength="100" />
            <span class="help-block text-end" id="countcar_url'.$id.'"></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4 " for="url_title'.$id.'">'.adm_translate("Titre du lien").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="url_title'.$id.'" name="url_title" value="'.$url_title.'" maxlength="50" />
            <span class="help-block text-end" id="countcar_url_title'.$id.'"></span>
         </div>
      </div>';
         }
         echo '
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="cover'.$id.'">'.adm_translate("Image de garde").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="cover'.$id.'" name="cover" maxlength="100" />
            <span class="help-block">150*150 pixel => images/covers<span class="float-end ms-1" id="countcar_cover'.$id.'"></span></span>
         </div>
      </div>
      <div class="mb-3 row">
         <div class="col-sm-8 ms-sm-auto">
            <input type="hidden" name="op" value="add_review">
            <button class="btn btn-primary" type="submit">'.adm_translate("Ajouter").'</button>
            <a href="admin.php?op=deleteNotice&amp;id='.$id.'&amp;op_back=reviews" class="btn btn-danger" role="button">'.adm_translate("Supprimer").'</a>
         </div>
      </div>
   </form>';
         $jsfvf.=',"reviewsaddcr'.$id.'"';
         $jsfvc.='
         inpandfieldlen("title'.$id.'",40);
         inpandfieldlen("reviewer'.$id.'",20);
         inpandfieldlen("email'.$id.'",60);
         inpandfieldlen("url'.$id.'",100);
         inpandfieldlen("url_title'.$id.'",50);
         inpandfieldlen("cover'.$id.'",100);';
      }
      $arg1='
         var formulid = ["reviewspagecfg"'.$jsfvf.'];
         inpandfieldlen("tit_cri",100);'.$jsfvc;

      echo '
   <script type="text/javascript" src="lib/flatpickr/dist/flatpickr.min.js"></script>
   <script type="text/javascript" src="lib/flatpickr/dist/l10n/'.language_iso(1,'','').'.js"></script>
   <script type="text/javascript">
   //<![CDATA[
      $(document).ready(function() {
         $("<link>").appendTo("head").attr({type: "text/css", rel: "stylesheet",href: "lib/flatpickr/dist/themes/npds.css"});
      })
      flatpickr(".reviewdate-js", {
         altInput: true,
         altFormat: "l j F Y",
         dateFormat:"Y-m-d",
         "locale": "'.language_iso(1,'','').'",
      });
   //]]>
   </script>';
   } else {
      echo '
      <div class="alert alert-success my-3">'.adm_translate("Aucune critique à ajouter").'</div>';
   $arg1='
      var formulid = ["reviewspagecfg"];
      inpandfieldlen("tit_cri",100);';
   }
   echo '
   <hr />
   <p><a href="reviews.php?op=write_review" >'.adm_translate("Cliquer ici pour proposer une Critique.").'</a></p>
   <hr />
   <h3 class="my-3">'.adm_translate("Effacer / Modifier une Critique").'</h3>
   <div class="alert alert-success">'
   .adm_translate("Vous pouvez simplement Effacer / Modifier les Critiques en naviguant sur").' <a href="reviews.php" >reviews.php</a> '.adm_translate("en tant qu'Administrateur.").'
   </div>';

   sql_free_result($result);
   adminfoot('fv','',$arg1,'');
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
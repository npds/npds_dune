<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2021 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion"))
   include ("mainfile.php");

function display_score($score) {
   $image = '<i class="fa fa-star"></i>';
   $halfimage = '<i class="fa fa-star-half-o"></i>';
   $full = '<i class="fa fa-star"></i>';
   if ($score == 10) {
      for ($i=0; $i < 5; $i++)
         echo $full;
   } else if ($score % 2) {
      $score -= 1;
      $score /= 2;
      for ($i=0; $i < $score; $i++)
         echo $image;
      echo $halfimage;
   } else {
      $score /= 2;
      for ($i=0; $i < $score; $i++)
         echo $image;
   }
}

function write_review() {
   global $admin, $sitename, $user, $cookie, $short_review, $NPDS_Prefix;
   include ('header.php');
   echo '
   <h2>'.translate("Ecrire une critique").'</h2>
   <hr />
   <form id="writereview" method="post" action="reviews.php">
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="title_rev">'.translate("Objet").'</label>
         <div class="col-sm-8">
            <textarea class="form-control" id="title_rev" name="title" rows="2" required="required" maxlength="150"></textarea>
            <span class="help-block text-end" id="countcar_title_rev"></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="text_rev">'.translate("Texte").'</label>
         <div class="col-sm-8">
            <textarea class="form-control" id="text_rev" name="text" rows="15" required="required"></textarea>
            <span class="help-block">'.translate("Attention à votre expression écrite. Vous pouvez utiliser du code html si vous savez le faire").'</span>
         </div>
      </div>';
  
   if ($user) {
      $result=sql_query("SELECT uname, email FROM ".$NPDS_Prefix."users WHERE uname='$cookie[1]'");
      list($uname, $email) = sql_fetch_row($result);

      echo '
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="reviewer_rev">'.translate("Votre nom").'</label>
         <div class="col-sm-8">
            <input type="text" class="form-control" id="reviewer_rev" name="reviewer" value="'.$uname.'" maxlength="25" required="required" />
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="email_rev">'.translate("Votre adresse Email").'</label>
         <div class="col-sm-8">
            <input type="email" class="form-control" id="email_rev" name="email" value="'.$email.'" maxlength="254" required="required" />
            <span class="help-block text-end" id="countcar_email_rev"></span>
         </div>
      </div>';
   } else {
      echo '
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="reviewer_rev">'.translate("Votre nom").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="reviewer_rev" name="reviewer" required="required" />
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="email_rev">'.translate("Votre adresse Email").'</label>
         <div class="col-sm-8">
            <input type="email" class="form-control" id="email_rev" name="email" maxlength="254" required="required" />
            <span class="help-block text-end" id="countcar_email_rev"></span>
         </div>
      </div>';
   }
      echo '
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="score_rev">'.translate("Evaluation").'</label>
         <div class="col-sm-8">
            <select class="form-select" id="score_rev" name="score">
               <option value="10">10</option>
               <option value="9">9</option>
               <option value="8">8</option>
               <option value="7">7</option>
               <option value="6">6</option>
               <option value="5">5</option>
               <option value="4">4</option>
               <option value="3">3</option>
               <option value="2">2</option>
               <option value="1">1</option>
            </select>
            <span class="help-block">'.translate("Choisir entre 1 et 10 (1=nul 10=excellent)").'</span>
         </div>
      </div>';

   if (!$short_review) {
      echo '
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="url_rev">'.translate("Lien relatif").'</label>
         <div class="col-sm-8">
            <input type="url" class="form-control" id="url_rev" name="url" maxlength="320" />
            <span class="help-block">'.translate("Site web officiel. Veillez à ce que votre url commence bien par").' http(s)://<span class="float-end" id="countcar_url_rev"></span></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="url_title_rev">'.translate("Titre du lien").'</label>
         <div class="col-sm-8">
            <input type="text" class="form-control" id="url_title_rev" name="url_title" maxlength="50" />
            <span class="help-block">'.translate("Obligatoire seulement si vous soumettez un lien relatif").'<span class="float-end" id="countcar_url_title_rev"></span></span>
         </div>
      </div>';
      if ($admin) {
         echo '
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="cover_rev">'.translate("Nom de fichier de l'image").'</label>
         <div class="col-sm-8">
            <input type="text" class="form-control" id="cover_rev" name="cover" maxlength="50" />
            <span class="help-block">'.translate("Nom de l'image principale non obligatoire, la mettre dans images/reviews/").'<span class="float-end" id="countcar_cover_rev"></span></span>
         </div>
      </div>';
      }
   }
   echo '
      <div class="mb-3 row">
         <div class="col-sm-8 ms-sm-auto">
            <input type="hidden" name="op" value="preview_review" />
            <button type="submit" class="btn btn-primary" >'.translate("Prévisualiser").'</button>
            <button type="button" onclick="history.go(-1)" class="btn btn-secondary" title="'.translate("Retour en arrière").'">'.translate("Retour en arrière").'</button>
            <p class="help-block">'.translate("Assurez-vous de l'exactitude de votre information avant de la communiquer. N'écrivez pas en majuscules, votre texte serait automatiquement rejeté").'</p>
         </div>
      </div>
   </form>';
   $arg1 ='
      var formulid = ["writereview"];
      inpandfieldlen("title_rev",150);
      inpandfieldlen("email_rev",254);
      inpandfieldlen("url_rev",320);
      inpandfieldlen("url_title_rev",50);
      inpandfieldlen("cover_rev",100);';
   adminfoot('fv','',$arg1,'foo');
}

function preview_review($title, $text, $reviewer, $email, $score, $cover, $url, $url_title, $hits, $id) {
   global $admin, $short_review;

   $title = stripslashes(strip_tags($title));
   $text = stripslashes(removeHack(conv2br($text)));
   $reviewer = stripslashes(strip_tags($reviewer));
   $url_title = stripslashes(strip_tags($url_title));
   $error='';

   include ('header.php');
   if ($id != 0)
      echo '
      <h2 class="mb-4">'.translate("Modification d'une critique").'</h2>';
   else
      echo '
      <h2 class="mb-4">'.translate("Ecrire une critique").'</h2>';
   echo '
   <form id="prevreview" method="post" action="reviews.php">';
   if ($title == '') {
      $error = 1;
      echo '<div class="alert alert-danger">'.translate("Titre non valide... Il ne peut pas être vide").'</div>';
   }
   if ($text == '') {
      $error = 1;
      echo '<div class="alert alert-danger">'.translate("Texte de critique non valide... Il ne peut pas être vide").'</div>';
   }
   if (($score < 1) || ($score > 10)) {
      $error = 1;
      echo '<div class="alert alert-danger">'.translate("Note non valide... Elle doit se situer entre 1 et 10").'</div>';
   }
   if (($hits < 0) && ($id != 0)) {
      $error = 1;
      echo '<div class="alert alert-danger">'.translate("Le nombre de hits doit être un entier positif").'</div>';
   }
   if ($reviewer == '' || $email == '') {
      $error = 1;
      echo '<div class="alert alert-danger">'.translate("Vous devez entrer votre nom et votre adresse Email").'</div>';
   } else if ($reviewer != '' && $email != '') {
      if (!preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i',$email)) {
         $error = 1;
         echo '<div class="alert alert-danger">'.translate("Email non valide (ex.: prenom.nom@hotmail.com)").'</div>';
      }
      include_once('functions.php');
      if(checkdnsmail($email) === false) {
         $error = 1;
         echo '<div class="alert alert-danger">'.translate("Erreur : DNS ou serveur de mail incorrect").'</div>';
      }
   }
   if ((($url_title != '' && $url =='') || ($url_title == "" && $url != "")) and (!$short_reviews)) {
      $error = 1;
      echo '<div class="alert alert-danger">'.translate("Vous devez entrer un titre de lien et une adresse relative, ou laisser les deux zones vides").'</div>';
   } else if (($url != "") && (!preg_match('#^http(s)?://#i',$url))) {
      $error = 1;
      echo '<div class="alert alert-danger">'.translate("Site web officiel. Veillez à ce que votre url commence bien par").' http(s)://</div>';
   }

   if ($error == 1)
      echo '<button class="btn btn-secondary" type="button" onclick="history.go(-1)"><i class="fa fa-lg fa-undo"></i></button>';
   else {
      global $gmt;
      $fdate=date(str_replace('%','',translate("linksdatestring")),time()+((integer)$gmt*3600));

      echo translate("Critique");

      echo '
      <br />'.translate("Ajouté :").' '.$fdate.'
      <hr />
      <h3>'.stripslashes($title).'</h3>';
      if ($cover != '')
         echo '<img class="img-fluid" src="images/reviews/'.$cover.'" alt="img_" />';
      echo $text;
      echo '
      <hr />
      <strong>'.translate("Le critique").' :</strong> <a href="mailto:'.$email.'" target="_blank">'.$reviewer.'</a><br />
      <strong>'.translate("Note").'</strong>
      <span class="text-success">';
      display_score($score); 
      echo'</span>';
      if ($url != '')
         echo '<br /><strong>'.translate("Lien relatif").' :</strong> <a href="'.$url.'" target="_blank">'.$url_title.'</a>';
      if ($id != 0) {
         echo '<br /><strong>'.translate("ID de la critique").' :</strong> '.$id.'<br />
         <strong>'.translate("Hits").' :</strong> '.$hits.'<br />';
      }
      $text = urlencode($text);
      echo '
            <input type="hidden" name="id" value="'.$id.'" />
            <input type="hidden" name="hits" value="'.$hits.'" />
            <input type="hidden" name="date" value="'.$fdate.'" />
            <input type="hidden" name="title" value="'.$title.'" />
            <input type="hidden" name="text" value="'.$text.'" />
            <input type="hidden" name="reviewer" value="'.$reviewer.'" />
            <input type="hidden" name="email" value="'.$email.'" />
            <input type="hidden" name="score" value="'.$score.'" />
            <input type="hidden" name="url" value="'.$url.'" />
            <input type="hidden" name="url_title" value="'.$url_title.'" />
            <input type="hidden" name="cover" value="'.$cover.'" />
            <input type="hidden" name="op" value="add_reviews" />
            <p class="my-3">'.translate("Cela semble-t-il correct ?").'</p>';
      if (!$admin) echo Q_spambot();
      $consent = '[french]Pour conna&icirc;tre et exercer vos droits notamment de retrait de votre consentement &agrave; l\'utilisation des donn&eacute;es collect&eacute;es veuillez consulter notre <a href="static.php?op=politiqueconf.html&amp;npds=1&amp;metalang=1">politique de confidentialit&eacute;</a>.[/french][english]To know and exercise your rights, in particular to withdraw your consent to the use of the data collected, please consult our <a href="static.php?op=politiqueconf.html&amp;npds=1&amp;metalang=1">privacy policy</a>.[/english][spanish]Para conocer y ejercer sus derechos, en particular para retirar su consentimiento para el uso de los datos recopilados, consulte nuestra <a href="static.php?op=politiqueconf.html&amp;npds=1&amp;metalang=1">pol&iacute;tica de privacidad</a>.[/spanish][german]Um Ihre Rechte zu kennen und auszu&uuml;ben, insbesondere um Ihre Einwilligung zur Nutzung der erhobenen Daten zu widerrufen, konsultieren Sie bitte unsere <a href="static.php?op=politiqueconf.html&amp;npds=1&amp;metalang=1">Datenschutzerkl&auml;rung</a>.[/german][chinese]&#x8981;&#x4E86;&#x89E3;&#x5E76;&#x884C;&#x4F7F;&#x60A8;&#x7684;&#x6743;&#x5229;&#xFF0C;&#x5C24;&#x5176;&#x662F;&#x8981;&#x64A4;&#x56DE;&#x60A8;&#x5BF9;&#x6240;&#x6536;&#x96C6;&#x6570;&#x636E;&#x7684;&#x4F7F;&#x7528;&#x7684;&#x540C;&#x610F;&#xFF0C;&#x8BF7;&#x67E5;&#x9605;&#x6211;&#x4EEC;<a href="static.php?op=politiqueconf.html&#x26;npds=1&#x26;metalang=1">&#x7684;&#x9690;&#x79C1;&#x653F;&#x7B56;</a>&#x3002;[/chinese]';
      $accept = "[french]En soumettant ce formulaire j'accepte que les informations saisies soient exploit&#xE9;es dans le cadre de l'utilisation et du fonctionnement de ce site.[/french][english]By submitting this form, I accept that the information entered will be used in the context of the use and operation of this website.[/english][spanish]Al enviar este formulario, acepto que la informaci&oacute;n ingresada se utilizar&aacute; en el contexto del uso y funcionamiento de este sitio web.[/spanish][german]Mit dem Absenden dieses Formulars erkl&auml;re ich mich damit einverstanden, dass die eingegebenen Informationen im Rahmen der Nutzung und des Betriebs dieser Website verwendet werden.[/german][chinese]&#x63D0;&#x4EA4;&#x6B64;&#x8868;&#x683C;&#x5373;&#x8868;&#x793A;&#x6211;&#x63A5;&#x53D7;&#x6240;&#x8F93;&#x5165;&#x7684;&#x4FE1;&#x606F;&#x5C06;&#x5728;&#x672C;&#x7F51;&#x7AD9;&#x7684;&#x4F7F;&#x7528;&#x548C;&#x64CD;&#x4F5C;&#x8303;&#x56F4;&#x5185;&#x4F7F;&#x7528;&#x3002;[/chinese]";
      echo '
       <div class="mb-3 row">
           <div class="col-sm-12">
               <div class="form-check">
                   <input class="form-check-input" type="checkbox" id="consent" name="consent" value="1" required="required"/>
                   <label class="form-check-label" for="consent">'
                       .aff_langue($accept).'
                       <span class="text-danger"> *</span>
                   </label>
               </div>
           </div>
       </div>
      <div class="mb-3 row">
         <div class="col-sm-12">
            <input class="btn btn-primary" type="submit" value="'.translate("Oui").'" />&nbsp;
            <input class="btn btn-secondary" type="button" onclick="history.go(-1)" value="'.translate("Non").'" />
         </div>
      </div>
      <div class="mb-3 row">
         <div class="col small" >'.aff_langue($consent).'
         </div>
      </div>';
      if ($id != 0) $word = translate("modifié");
      else $word = translate("ajouté");
      if ($admin)
         echo '
         <div class="alert alert-success"><strong>'.translate("Note :").'</strong> '.translate("Actuellement connecté en administrateur... Cette critique sera").' '.$word.' '.translate("immédiatement").'.</div>';
   }
   echo '
   </form>';
   $arg1 ='
      var formulid = ["prevreview"];';

   adminfoot('fv','',$arg1,'foo');
}

function reversedate($myrow) {
   if (substr($myrow,2,1)=='-') {
      $day=substr($myrow,0,2);
      $month=substr($myrow,3,2);
      $year=substr($myrow,6,4);
   } else {
      $day=substr($myrow,8,2);
      $month=substr($myrow,5,2);
      $year=substr($myrow,0,4);
   }
   return ($year.'-'.$month.'-'.$day);
}

function send_review($date, $title, $text, $reviewer, $email, $score, $cover, $url, $url_title, $hits, $id, $asb_question, $asb_reponse) {
   global $admin, $user, $NPDS_Prefix;

   include ('header.php');
   $date=reversedate($date);
   $title = stripslashes(FixQuotes(strip_tags($title)));
   $text = stripslashes(Fixquotes(urldecode(removeHack($text))));

   if (!$user and !$admin) {
      //anti_spambot
      if (!R_spambot($asb_question, $asb_reponse, $text)) {
         Ecr_Log('security', 'Review Anti-Spam : title='.$title, '');
         redirect_url("index.php");
         die();
      }
   }
   if ($id != 0)
      echo '
      <h2>'.translate("Modification d'une critique").'</h2>';
   else
      echo '
      <h2>'.translate("Ecrire une critique").'</h2>';
   echo '
   <hr />
   <div class="alert alert-success">';
   if ($id != 0)
      echo translate("Merci d'avoir modifié cette critique").'.';
   else
      echo translate("Merci d'avoir posté cette critique").', '.$reviewer;
   echo '<br />';
   if (($admin) && ($id == 0)) {
      sql_query("INSERT INTO ".$NPDS_Prefix."reviews VALUES (NULL, '$date', '$title', '$text', '$reviewer', '$email', '$score', '$cover', '$url', '$url_title', '1')");
      echo translate("Dès maintenant disponible dans la base de données des critiques.");
   } else if (($admin) && ($id != 0)) {
      sql_query("UPDATE ".$NPDS_Prefix."reviews SET date='$date', title='$title', text='$text', reviewer='$reviewer', email='$email', score='$score', cover='$cover', url='$url', url_title='$url_title', hits='$hits' WHERE id='$id'");
      echo translate("Dès maintenant disponible dans la base de données des critiques.");
   } else {
      sql_query("INSERT INTO ".$NPDS_Prefix."reviews_add VALUES (NULL, '$date', '$title', '$text', '$reviewer', '$email', '$score', '$url', '$url_title')");
      echo translate("Nous allons vérifier votre contribution. Elle devrait bientôt être disponible !");
   }
   echo '
   </div>
   <a class="btn btn-secondary" href="reviews.php" title="'.translate("Retour à l'index des critiques").'"><i class="fa fa-lg fa-undo"></i>  '.translate("Retour à l'index des critiques").'</a>';
   include ("footer.php");
}

function reviews($field, $order) {
   global $NPDS_Prefix;
   include ('header.php');
   $r_result = sql_query("SELECT title, description FROM ".$NPDS_Prefix."reviews_main");
   list($r_title, $r_description) = sql_fetch_row($r_result);
   if ($order!="ASC" and $order!="DESC") $order="ASC";
   switch ($field) {
      case 'reviewer':
         $result = sql_query("SELECT id, title, hits, reviewer, score, date FROM ".$NPDS_Prefix."reviews ORDER BY reviewer $order");
      break;
      case 'score':
         $result = sql_query("SELECT id, title, hits, reviewer, score, date FROM ".$NPDS_Prefix."reviews ORDER BY score $order");
      break;
      case 'hits':
         $result = sql_query("SELECT id, title, hits, reviewer, score, date FROM ".$NPDS_Prefix."reviews ORDER BY hits $order");
      break;
      case 'date':
         $result = sql_query("SELECT id, title, hits, reviewer, score, date FROM ".$NPDS_Prefix."reviews ORDER BY id $order");
      break;
      default:
         $result = sql_query("SELECT id, title, hits, reviewer, score, date FROM ".$NPDS_Prefix."reviews ORDER BY title $order");
      break;
   }
   $numresults = sql_num_rows($result);

   echo '
   <h2>'.translate("Critiques").'<span class="badge bg-secondary float-end" title="'.$numresults.' '.translate("Critique(s) trouvée(s).").'" data-bs-toggle="tooltip">'.$numresults.'</span></h2>
   <hr />
   <h3>'.aff_langue($r_title).'</h3>
   <p class="lead">'.aff_langue($r_description).'</p>
   <h4><a href="reviews.php?op=write_review"><i class="fa fa-edit"></i></a>&nbsp;'.translate("Ecrire une critique").'</h4><br />
   ';
   echo'
   <div class="dropdown">
      <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
         <i class="fa fa-sort-amount-down me-2"></i>'.translate("Critiques").'
      </a>
      <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
         <a class="dropdown-item" href="reviews.php?op=sort&amp;field=date&amp;order=ASC"><i class="fa fa-sort-amount-down me-2"></i>'.translate("Date").'</a>
         <a class="dropdown-item" href="reviews.php?op=sort&amp;field=date&amp;order=DESC"><i class="fa fa-sort-amount-up me-2"></i>'.translate("Date").'</a>
         <a class="dropdown-item" href="reviews.php?op=sort&amp;field=title&amp;order=ASC"><i class="fa fa-sort-amount-down me-2"></i>'.translate("Titre").'</a>
         <a class="dropdown-item" href="reviews.php?op=sort&amp;field=title&amp;order=DESC"><i class="fa fa-sort-amount-up me-2"></i>'.translate("Titre").'</a>
         <a class="dropdown-item" href="reviews.php?op=sort&amp;field=reviewer&amp;order=ASC"><i class="fa fa-sort-amount-down me-2"></i>'.translate("Posté par").'</a>
         <a class="dropdown-item" href="reviews.php?op=sort&amp;field=reviewer&amp;order=DESC"><i class="fa fa-sort-amount-up me-2"></i>'.translate("Posté par").'</a>
         <a class="dropdown-item" href="reviews.php?op=sort&amp;field=score&amp;order=ASC"><i class="fa fa-sort-amount-down me-2"></i>Score</a>
         <a class="dropdown-item" href="reviews.php?op=sort&amp;field=score&amp;order=DESC"><i class="fa fa-sort-amount-up me-2"></i>Score</a>
         <a class="dropdown-item" href="reviews.php?op=sort&amp;field=hits&amp;order=ASC"><i class="fa fa-sort-amount-down"></i>Hits</a>
         <a class="dropdown-item" href="reviews.php?op=sort&amp;field=hits&amp;order=DESC"><i class="fa fa-sort-amount-up"></i>Hits</a>
      </div>
   </div>';

   if ($numresults > 0) {
      echo '
      <table data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-buttons-class="outline-secondary" data-icons-prefix="fa" data-icons="icons">
         <thead>
            <tr>
               <th data-align="center">
                  <a href="reviews.php?op=sort&amp;field=date&amp;order=ASC"><i class="fa fa-sort-amount-down"></i></a> '.translate("Date").' <a href="reviews.php?op=sort&amp;field=date&amp;order=DESC"><i class="fa fa-sort-amount-up"></i></a>
               </th>
               <th data-align="left" data-halign="center" data-sortable="true" data-sorter="htmlSorter">
                  <a href="reviews.php?op=sort&amp;field=title&amp;order=ASC"><i class="fa fa-sort-amount-down"></i></a> '.translate("Titre").' <a href="reviews.php?op=sort&amp;field=title&amp;order=DESC"><i class="fa fa-sort-amount-up"></i></a>
               </th>
               <th data-align="center" data-sortable="true">
                  <a href="reviews.php?op=sort&amp;field=reviewer&amp;order=ASC"><i class="fa fa-sort-amount-down"></i></a> '.translate("Posté par").' <a href="reviews.php?op=sort&amp;field=reviewer&amp;order=DESC"><i class="fa fa-sort-amount-up"></i></a>
               </th>
               <th class="n-t-col-xs-2" data-align="center" data-sortable="true">
                  <a href="reviews.php?op=sort&amp;field=score&amp;order=ASC"><i class="fa fa-sort-amount-down"></i></a> Score <a href="reviews.php?op=sort&amp;field=score&amp;order=DESC"><i class="fa fa-sort-amount-up"></i></a>
               </th>
               <th class="n-t-col-xs-2" data-align="right" data-sortable="true">
                  <a href="reviews.php?op=sort&amp;field=hits&amp;order=ASC"><i class="fa fa-sort-amount-down"></i></a> Hits <a href="reviews.php?op=sort&amp;field=hits&amp;order=DESC"><i class="fa fa-sort-amount-up"></i></a>
               </th>
            </tr>
      </thead>
      <tbody>';
      
      while ($myrow=sql_fetch_assoc($result)) {
         $title = $myrow['title'];
         $id = $myrow['id'];
         $reviewer = $myrow['reviewer'];
         $score = $myrow['score'];
         $hits = $myrow['hits'];
         $date = $myrow['date'];
         echo '
            <tr>
               <td>'.f_date ($date).'</td>
               <td><a href="reviews.php?op=showcontent&amp;id='.$id.'">'.ucfirst($title).'</a></td>
               <td>';
         if ($reviewer != '') echo $reviewer;
         echo '</td>
               <td><span class="text-success">';
         display_score($score);
         echo '</span></td>
               <td>'.$hits.'</td>
            </tr>';
      }
      echo '
         </tbody>
      </table>';
   }
   sql_free_result($result);
   include ("footer.php");
}

function f_date($xdate) {
   $year = substr($xdate,0,4);
   $month = substr($xdate,5,2);
   $day = substr($xdate,8,2);
   $fdate=date(str_replace("%",'',translate("linksdatestring")),mktime (0,0,0,$month,$day,$year));
   return $fdate;
}

function showcontent($id) {
   global $admin, $NPDS_Prefix;
   include ('header.php');
   settype($id,'integer');
   sql_query("UPDATE ".$NPDS_Prefix."reviews SET hits=hits+1 WHERE id='$id'");
   $result = sql_query("SELECT * FROM ".$NPDS_Prefix."reviews WHERE id='$id'");
   $myrow = sql_fetch_assoc($result);
   $id =  $myrow['id'];
   $fdate=f_date($myrow['date']);
   $title = $myrow['title'];
   $text = $myrow['text'];
   $cover = $myrow['cover'];
   $reviewer = $myrow['reviewer'];
   $email = $myrow['email'];
   $hits = $myrow['hits'];
   $url = $myrow['url'];
   $url_title = $myrow['url_title'];
   $score = $myrow['score'];

   echo '
   <h2>'.translate("Critiques").'</h2>
   <hr />
   <a href="reviews.php">'.translate("Retour à l'index des critiques").'</a>
   <div class="card card-body my-3">
      <div class="card-text text-muted text-end small">
   '.translate("Ajouté :").' '.$fdate.'<br />
      </div>
   <hr />
   <h3 class="mb-3">'.$title.'</h3><br />';
   if ($cover != '')
      echo '<img class="img-fluid" src="images/reviews/'.$cover.'" />';
   echo $text;

   echo '
      <br /><br />
      <div class="card card-body mb-3">';
   if ($reviewer != '')
      echo '<div class="mb-2"><strong>'.translate("Le critique").' :</strong> <a href="mailto:'.anti_spam($email,1).'" >'.$reviewer.'</a></div>';
   if ($score != '')
      echo '<div class="mb-2"><strong>'.translate("Note").' : </strong>';
   echo '<span class="text-success">';
   display_score($score);
   echo '</span>
   </div>';
   if ($url != '')
      echo '<div class="mb-2"><strong>'.translate("Lien relatif").' : </strong> <a href="'.$url.'" target="_blank">'.$url_title.'</a></div>';
   echo '<div><strong>'.translate("Hits : ").'</strong><span class="badge bg-secondary">'.$hits.'</span></div>
      </div>';
   if ($admin)
      echo '
      <nav class="d-flex justify-content-center">
         <ul class="pagination pagination-sm">
            <li class="page-item disabled">
               <a class="page-link" href="#"><i class="fa fa-cogs fa-lg"></i><span class="ms-2 d-none d-lg-inline">'.translate("Outils administrateur").'</span></a>
            </li>
            <li class="page-item">
               <a class="page-link" role="button" href="reviews.php?op=mod_review&amp;id='.$id.'" title="'.translate("Editer").'" data-bs-toggle="tooltip" ><i class="fa fa-lg fa-edit" ></i></a>
            </li>
            <li class="page-item">
               <a class="page-link text-danger" role="button" href="reviews.php?op=del_review&amp;id_del='.$id.'" title="'.translate("Effacer").'" data-bs-toggle="tooltip" ><i class="fas fa-trash fa-lg" ></i></a>
            </li>
         </ul>
      </nav>';
   echo '
   </div>';

   sql_free_result($result);

   global $anonpost, $moderate, $user;
   if (file_exists("modules/comments/reviews.conf.php")) {
      include ("modules/comments/reviews.conf.php");
      include ("modules/comments/comments.php");
   }
   include ("footer.php");
}

function mod_review($id) {
   global $admin, $NPDS_Prefix;
   include ('header.php');

   settype($id,'integer');
   if (($id != 0) && ($admin)) {
      $result = sql_query("SELECT * FROM ".$NPDS_Prefix."reviews WHERE id = '$id'");
      $myrow =  sql_fetch_assoc($result);
      $id =  $myrow['id'];
      $date = $myrow['date'];
      $title = $myrow['title'];
      $text = str_replace('<br />','\r\n',$myrow['text']);
      $cover = $myrow['cover'];
      $reviewer = $myrow['reviewer'];
      $email = $myrow['email'];
      $hits = $myrow['hits'];
      $url = $myrow['url'];
      $url_title = $myrow['url_title'];
      $score = $myrow['score'];

   echo '
   <h2 class="mb-4">'.translate("Modification d'une critique").'</h2>
   <hr />
   <form id="modreview" method="post" action="reviews.php?op=preview_review">
      <input type="hidden" name="id" value="'.$id.'">
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="date_modrev">'.translate("Date").'</label>
         <div class="col-sm-8">
            <input type="text" class="form-control w-100" id="date_modrev" name="date" value="'.$date.'" />
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="title_modrev">'.translate("Titre").'</label>
         <div class="col-sm-8">
            <textarea class="form-control" id="title_modrev" name="title" rows="2" required="required" maxlength="150">'.$title.'</textarea>
            <span class="help-block text-end" id="countcar_title_modrev"></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="text_modrev">'.translate("Texte").'</label>
         <div class="col-sm-8">
            <textarea class="form-control" id="text_modrev" name="text" rows="15" required="required">'.$text.'</textarea>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="reviewer_modrev">'.translate("Le critique").'</label>
         <div class="col-sm-8">
            <input type="text" class="form-control" id="reviewer_modrev" name="reviewer" value="'.$reviewer.'" required="required" maxlength="25"/>
            <span class="help-block text-end" id="countcar_reviewer_modrev"></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="email_modrev">'.translate("Email").'</label>
         <div class="col-sm-8">
            <input type="email" class="form-control" id="email_modrev" name="email" value="'.$email.'" maxlength="254" required="required"/>
            <span class="help-block text-end" id="countcar_email_modrev"></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="score_modrev">'.translate("Evaluation").'</label>
         <div class="col-sm-8">
            <select class="form-select" id="score_modrev" name="score">';
      $i=1;$sel='';
      do {
         if ($i==$score) $sel='selected="selected" '; else $sel='';
         echo '
         <option value="'.$i.'" '.$sel.'>'.$i.'</option>';
         $i++;
      }
      while($i<=10);
      echo '
            </select>
            <span class="help-block">'.translate("Choisir entre 1 et 10 (1=nul 10=excellent)").'</span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="url_modrev">'.translate("Lien").'</label>
         <div class="col-sm-8">
            <input type="url" class="form-control" id="url_modrev" name="url" maxlength="320" value="'.$url.'" />
            <span class="help-block">'.translate("Site web officiel. Veillez à ce que votre url commence bien par").' http(s)://<span class="float-end" id="countcar_url_modrev"></span></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="url_title_modrev">'.translate("Titre du lien").'</label>
         <div class="col-sm-8">
            <input type="text" class="form-control" id="url_title_modrev" name="url_title" value="'.$url_title.'"  maxlength="50" />
            <span class="help-block">'.translate("Obligatoire seulement si vous soumettez un lien relatif").'<span class="float-end" id="countcar_url_title_modrev"></span></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="cover_modrev">'.translate("Image de garde").'</label>
         <div class="col-sm-8">
            <input type="text" class="form-control" id="cover_modrev" name="cover" value="'.$cover.'" maxlength="100"/>
            <span class="help-block">'.translate("Nom de l'image principale non obligatoire, la mettre dans images/reviews/").'<span class="float-end" id="countcar_cover_modrev"></span></span>
         </div>
      </div>
      <div class="mb-3 row">
         <label class="col-form-label col-sm-4" for="hits_modrev">'.translate("Hits").'</label>
         <div class="col-sm-8">
            <input type="text" class="form-control" id="hits_modrev" name="hits" value="'.$hits.'" maxlength="9" />
         </div>
      </div>
      <div class="mb-3 row">
         <div class="col-sm-8 ms-sm-auto">
            <input type="hidden" name="op" value="preview_review" />
            <input class="btn btn-primary col-12 mb-2" type="submit" value="'.translate("Prévisualiser les modifications").'" />
            <input class="btn btn-secondary col-12" type="button" onclick="history.go(-1)" value="'.translate("Annuler").'" />
         </div>
      </div>
      </form>
      <script type="text/javascript" src="lib/flatpickr/dist/flatpickr.min.js"></script>
      <script type="text/javascript" src="lib/flatpickr/dist/l10n/'.language_iso(1,'','').'.js"></script>
      <script type="text/javascript">
      //<![CDATA[
         $(document).ready(function() {
            $("<link>").appendTo("head").attr({type: "text/css", rel: "stylesheet",href: "lib/flatpickr/dist/themes/npds.css"});
         })
         
      //]]>
      </script>';
      $fv_parametres = '
      date:{},
      hits: {
         validators: {
            regexp: {
               regexp:/^\d{1,9}$/,
               message: "0-9"
            },
            between: {
               min: 1,
               max: 999999999,
               message: "1 ... 999999999"
            }
         }
      },
      !###!
      flatpickr("#date_modrev", {
         altInput: true,
         altFormat: "l j F Y",
         dateFormat:"Y-m-d",
         "locale": "'.language_iso(1,'','').'",
         onChange: function() {
            fvitem.revalidateField(\'date\');
         }
      });
      ';
      $arg1 ='
      var formulid = ["modreview"];
      inpandfieldlen("title_modrev",150);
      inpandfieldlen("reviewer_modrev",25);
      inpandfieldlen("email_modrev",254);
      inpandfieldlen("url_modrev",320);
      inpandfieldlen("url_title_modrev",50);
      inpandfieldlen("cover_modrev",100);';

      sql_free_result($result);
   }
   adminfoot('fv',$fv_parametres,$arg1,'foo');
}

function del_review($id_del) {
   global $admin, $NPDS_Prefix;

   settype($id_del,"integer");
   if ($admin) {
      sql_query("DELETE FROM ".$NPDS_Prefix."reviews WHERE id='$id_del'");
      // commentaires
      if (file_exists("modules/comments/reviews.conf.php")) {
          include ("modules/comments/reviews.conf.php");
          sql_query("DELETE FROM ".$NPDS_Prefix."posts WHERE forum_id='$forum' AND topic_id='$id_del'");
      }
   }
   redirect_url("reviews.php");
}

settype($op,'string');
settype($hits,'integer');
settype($id,'integer');
settype($cover,'string');
settype($asb_question,'string');
settype($asb_reponse,'string');

switch ($op) {
   case 'showcontent':
      showcontent($id);
   break;
   case 'write_review':
      write_review();
   break;
   case 'preview_review':
      preview_review($title, $text, $reviewer, $email, $score, $cover, $url, $url_title, $hits, $id);
   break;
   case 'add_reviews':
      send_review($date, $title, $text, $reviewer, $email, $score, $cover, $url, $url_title, $hits, $id, $asb_question, $asb_reponse);
   break;
   case 'del_review':
      del_review($id_del);
   break;
   case 'mod_review':
      mod_review($id);
   break;
   case 'sort':
      reviews($field,$order);
   break;
   default:
      reviews('date','DESC');
   break;
}
?>
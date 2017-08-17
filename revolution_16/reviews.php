<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2017 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion")) {
   include ("mainfile.php");
}

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
   <h2>'.translate("Write a Review").'</h2>
   <form class="" method="post" action="reviews.php">
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="title">'.translate("Product Title").'</label>
         <div class="col-sm-8">
            <input type="text" class="form-control" name="title">
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="text">'.translate("Text").'</label>
         <div class="col-sm-8">
            <textarea class="form-control" name="text" rows="15"></textarea>
         </div>
         <div class="col-sm-8 ml-sm-auto">
            <p class="help-block">'.translate("Please observe proper grammar! Make it at least 100 words, OK? You may also use HTML tags if you know how to use them.").'</p>
         </div>
      </div>';
  
   if ($user) {
      $result=sql_query("SELECT uname, email FROM ".$NPDS_Prefix."users WHERE uname='$cookie[1]'");
      list($uname, $email) = sql_fetch_row($result);

      echo '
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="reviewer">'.translate("Your name").'</label>
         <div class="col-sm-8">
            <input type="text" class="form-control" name="reviewer" value="'.$uname.'" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="email">'.translate("Your email").'</label>
         <div class="col-sm-8">
            <input type="text" class="form-control" name="email" value="'.$email.'" />
         </div>
      </div>';
   } else {
      echo '
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="reviewer">'.translate("Your name").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" name="reviewer" value="'.$name.'">
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="email">'.translate("Your email").'</label>
         <div class="col-sm-8">
            <input type="email" class="form-control" name="email" value="'.$email.'">
         </div>
      </div>';
   }
      echo '
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="score">'.translate("Score").'</label>
         <div class="col-sm-8">
            <select class="custom-select form-control" name="score">
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
            <p class="help-block">'.translate("Select from 1=poor to 10=excelent.").'</p>
         </div>
      </div>';

   if (!$short_review) {
      echo '
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="url">'.translate("Related Link").'</label>
         <div class="col-sm-8">
            <input type="url" class="form-control" name="url">
            <p class="help-block">'.translate("Product Official Website. Make sure your URL starts by").' http(s)://</p>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="url_title">'.translate("Link title").'</label>
         <div class="col-sm-8">
            <input type="text" class="form-control" name="url_title">
            <p class="help-block">'.translate("Required if you have a related link, otherwise not required.").'</p>
         </div>
      </div>';
      if ($admin) {
         echo '
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="cover">'.translate("Image filename").'</label>
         <div class="col-sm-8">
            <input type="text" class="form-control" name="cover">
            <p class="help-block">'.translate("Name of the cover image, located in images/reviews/. Not required.").'</p>
         </div>
      </div>';
      }
   }
   echo '
      <div class="form-group row">
         <div class="col-sm-8 ml-sm-auto">
            <input type="hidden" name="op" value="preview_review" />
            <button type="submit" class="btn btn-primary" >'.translate("Preview").'</button>
            <button type="button" onclick="history.go(-1)" class="btn btn-secondary" title="'.translate("Go Back").'">'.translate("Go Back").'</button>
            <p class="help-block">'.translate("Please make sure that the information entered is 100% valid and uses proper grammar and capitalization. For instance, please do not enter your text in ALL CAPS, as it will be rejected.").'</p>
         </div>
      </div>
   </form>';
   include ("footer.php");
}

function preview_review($title, $text, $reviewer, $email, $score, $cover, $url, $url_title, $hits, $id) {
   global $admin, $short_review;

   $title = stripslashes(strip_tags($title));
   $text = stripslashes(removeHack(conv2br($text)));
   $reviewer = stripslashes(strip_tags($reviewer));
   $url_title = stripslashes(strip_tags($url_title));

   include ('header.php');
   echo '
   <h2 class="mb-4">'.translate("Write a Review").'</h2>
   <form method="post" action="reviews.php">';
   if ($title == '') {
      $error = 1;
      echo '<div class="alert alert-danger">'.translate("Invalid Title... can not be blank").'</div>';
   }
   if ($text == '') {
      $error = 1;
      echo '<div class="alert alert-danger">'.translate("Invalid review text... can not be blank").'</div>';
   }
   if (($score < 1) || ($score > 10)) {
      $error = 1;
      echo '<div class="alert alert-danger">'.translate("Invalid score... must be between 1 and 10").'</div>';
   }
   if (($hits < 0) && ($id != 0)) {
      $error = 1;
      echo '<div class="alert alert-danger">'.translate("Hits must be a positive integer").'</div>';
   }
   if ($reviewer == '' || $email == '') {
      $error = 1;
      echo '<div class="alert alert-danger">'.translate("You must enter both your name and your email").'</div>';
   } else if ($reviewer != "" && $email != "")
      if (!preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i',$email)) {
         $error = 1;
         echo '<div class="alert alert-danger">'.translate("Invalid email (eg: you@hotmail.com)").'</div>';
      }

   if ((($url_title != '' && $url =='') || ($url_title == "" && $url != "")) and (!$short_reviews)) {
      $error = 1;
      echo '<div class="alert alert-danger">'.translate("You must enter BOTH a link title and a related link or leave both blank").'</div>';
   } else if (($url != "") && (!preg_match('#^http(s)?://#i',$url))) {
      $error = 1;
      echo '<div class="alert alert-danger">'.translate("Product Official Website. Make sure your URL starts by").' http(s)://</div>';
   }

   if ($error == 1) {
   echo '<button class="btn btn-secondary" type="button" onclick="history.go(-1)"><i class="fa fa-lg fa-undo"></i></button>';
   } else {
      global $gmt;
      $fdate=date(str_replace('%','',translate("linksdatestring")),time()+($gmt*3600));

      echo ''.translate("Waiting Reviews").'';

      echo '
      <br />'.translate("Added:").' '.$fdate.'
      <hr noshade="noshade" />
      <h3>'.$title.'</h3>';
      if ($cover != '')
         echo '<img class="img-fluid" src="images/reviews/'.$cover.'" alt="img_" />';
      echo $text;
      echo '
      <hr noshade="noshade" />
      <strong>'.translate("Reviewer").' :</strong> <a href="mailto:'.$email.'" target="_blank">'.$reviewer.'</a><br />
      <strong>'.translate("Score:").'</strong>
      <span class="text-success">';
      display_score($score); 
      echo'</span>';
      if ($url != '')
         echo '<br /><strong>'.translate("Related Link").' :</strong> <a href="'.$url.'" target="_blank">'.$url_title.'</a>';
      if ($id != 0) {
         echo '<br /><strong>'.translate("Review ID").' :</strong> '.$id.'<br />
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
            <br />'.translate("Does this look right?");
      if (!$admin) echo Q_spambot();
      echo '
      <div class="form-group row">
         <div class="col-sm-12">
            <input class="btn btn-primary" type="submit" value="'.translate("Yes").'" />&nbsp;
            <input class="btn btn-secondary" type="button" onclick="history.go(-1)" value="'.translate("No").'" />
         </div>
      </div>';
      if ($id != 0) $word = translate("modified");
      else $word = translate("added");
      if ($admin)
         echo '
         <div class="alert alert-success"><strong>'.translate("Note:").'</strong> '.translate("Currently logged in as admin... this review will be").' '.$word.' '.translate("immediately").'.</div>';
   }
   echo '</form>';
   include ("footer.php");
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
   echo '
   <h2>'.translate("Write a Review").'</h2>
   <div class="alert alert-success">'.translate("Thanks for submitting this review").'';
   if ($id != 0)
      echo " ".translate("modification")."";
   else
      echo ", $reviewer";
   echo '<br />';
   if (($admin) && ($id == 0)) {
      sql_query("INSERT INTO ".$NPDS_Prefix."reviews VALUES (NULL, '$date', '$title', '$text', '$reviewer', '$email', '$score', '$cover', '$url', '$url_title', '1')");
      echo translate("It is now available in the reviews database.");
   } else if (($admin) && ($id != 0)) {
      sql_query("UPDATE ".$NPDS_Prefix."reviews SET date='$date', title='$title', text='$text', reviewer='$reviewer', email='$email', score='$score', cover='$cover', url='$url', url_title='$url_title', hits='$hits' WHERE id='$id'");
      echo translate("It is now available in the reviews database.");
   } else {
      sql_query("INSERT INTO ".$NPDS_Prefix."reviews_add VALUES (NULL, '$date', '$title', '$text', '$reviewer', '$email', '$score', '$url', '$url_title')");
      echo translate("The editors will look at your submission. It should be available soon!");
   }
   echo '</div><a class="btn btn-secondary" role="button" href="reviews.php" title="'.translate("Back to Reviews Index").'"><i class="fa fa-lg fa-undo"></i>
</a>';
   include ("footer.php");
}

function reviews($field, $order) {
   global $NPDS_Prefix;
   include ('header.php');
   $result = sql_query("SELECT title, description FROM ".$NPDS_Prefix."reviews_main");
   list($title, $description) = sql_fetch_row($result);

   echo '
   <h2>'.translate("Reviews").'</h2>
   <h3>'.aff_langue($title).'</h3>
   <p class="lead">'.aff_langue($description).'</p>
   <h4><a href="reviews.php?op=write_review"><i class="fa fa-edit"></i></a>&nbsp;'.translate("Write a Review").'</h4><br />';
   if ($order!="ASC" and $order!="DESC") $order="ASC";
   switch ($field) {
          case 'reviewer':
               $result = sql_query("SELECT id, title, hits, reviewer, score, date FROM ".$NPDS_Prefix."reviews ORDER by reviewer $order");
               break;
          case 'score':
               $result = sql_query("SELECT id, title, hits, reviewer, score, date FROM ".$NPDS_Prefix."reviews ORDER by score $order");
               break;
          case 'hits':
               $result = sql_query("SELECT id, title, hits, reviewer, score, date FROM ".$NPDS_Prefix."reviews ORDER by hits $order");
               break;
          case 'date':
               $result = sql_query("SELECT id, title, hits, reviewer, score, date FROM ".$NPDS_Prefix."reviews ORDER by id $order");
               break;
          default:
               $result = sql_query("SELECT id, title, hits, reviewer, score, date FROM ".$NPDS_Prefix."reviews ORDER by title $order");
               break;
   }
   $numresults = sql_num_rows($result);
   if ($numresults > 0) {
      echo '
      <table data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-icons-prefix="fa" data-icons="icons">
         <thead>
            <tr>
               <th data-align="center" data-sortable="true">
                  <a href="reviews.php?op=sort&amp;field=date&amp;order=ASC"><i class="fa fa-sort-amount-asc"></i></a> '.translate("Date").' <a href="reviews.php?op=sort&amp;field=date&amp;order=DESC"><i class="fa fa-sort-amount-desc"></i></a>
               </th>
               <th data-align="center" data-sortable="true">
                  <a href="reviews.php?op=sort&amp;field=title&amp;order=ASC"><i class="fa fa-sort-amount-asc"></i></a> '.translate("Title").' <a href="reviews.php?op=sort&amp;field=title&amp;order=DESC"><i class="fa fa-sort-amount-desc"></i></a>
               </th>
               <th data-align="center" data-sortable="true">
                  <a href="reviews.php?op=sort&amp;field=reviewer&amp;order=ASC"><i class="fa fa-sort-amount-asc"></i></a> '.translate("Posted by").' <a href="reviews.php?op=sort&amp;field=reviewer&amp;order=DESC"><i class="fa fa-sort-amount-desc"></i></a>
               </th>
               <th data-align="center" data-sortable="true">
                  <a href="reviews.php?op=sort&amp;field=score&amp;order=ASC"><i class="fa fa-sort-amount-asc"></i></a> Score <a href="reviews.php?op=sort&amp;field=score&amp;order=DESC"><i class="fa fa-sort-amount-desc"></i></a>
               </th>
               <th data-align="right" data-sortable="true">
                  <a href="reviews.php?op=sort&amp;field=hits&amp;order=ASC"><i class="fa fa-sort-amount-asc"></i></a> Hits <a href="reviews.php?op=sort&amp;field=hits&amp;order=DESC"><i class="fa fa-sort-amount-desc"></i></a>
               </th>
            </tr>
      </thead>
      <tbody>';
      
      while ($myrow=sql_fetch_assoc($result)) {
         $title = $myrow["title"];
         $id = $myrow["id"];
         $reviewer = $myrow["reviewer"];
         $score = $myrow["score"];
         $hits = $myrow["hits"];
         $date = $myrow["date"];
         echo '
            <tr>
               <td>'.f_date ($date).'</td>
               <td><a href="reviews.php?op=showcontent&amp;id='.$id.'">'.$title.'</a></td>
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
   echo '<p class="badge badge-secondary">'.$numresults.' '.translate("Total Review(s) found.").'</p>';

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
   <h2>'.translate("Reviews").'</h2>
   <hr />
   <a href="reviews.php">'.translate("Back to Reviews Index").'</a>
   <div class="card card-body my-3">
      <div class="card-text text-muted text-right small">
   '.translate("Added:").' '.$fdate.'<br />
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
      echo '<div class="mb-2"><strong>'.translate("Reviewer").' :</strong> <a href="mailto:'.anti_spam($email,1).'" >'.$reviewer.'</a></div>';
   if ($score != '')
      echo '<div class="mb-2"><strong>'.translate("Score:").' : </strong>';
   echo '<span class="text-success">';
   display_score($score);
   echo '</span>
   </div>';
   if ($url != '')
      echo '<div class="mb-2"><strong>'.translate("Related Link").' : </strong> <a href="'.$url.'" target="_blank">'.$url_title.'</a></div>';
   echo '<div><strong>'.translate("Hits:").'</strong><span class="badge badge-secondary">'.$hits.'</span></div>
      </div>';
   if ($admin)
      echo '
      <nav class="d-flex justify-content-center">
         <ul class="pagination pagination-sm">
            <li class="page-item disabled">
               <a class="page-link" href="#"><i class="fa fa-cogs fa-lg"></i><span class="ml-2 d-none d-lg-inline">'.translate("Administration Tools").'</span></a>
            </li>
            <li class="page-item">
               <a class="page-link" role="button" href="reviews.php?op=mod_review&amp;id='.$id.'" title="'.translate("Edit").'" data-toggle="tooltip" ><i class="fa fa-lg fa-edit" ></i></a>
            </li>
            <li class="page-item">
               <a class="page-link text-danger" role="button" href="reviews.php?op=del_review&amp;id_del='.$id.'" title="'.translate("Delete").'" data-toggle="tooltip" ><i class="fa fa-lg fa-trash-o" ></i></a>
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
   <h2 class="mb-4">'.translate("Review Modification").'</h2>
   <form class="" method="post" action="reviews.php?op=preview_review">
   <input type="hidden" name="id" value="'.$id.'">
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="date">'.translate("Date").'</label>
         <div class="col-sm-8">
            <input type="text" class="form-control" name="date" value="'.$date.'" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="title">'.translate("Title").'</label>
         <div class="col-sm-8">
            <input type="text" class="form-control" name="title" value="'.$title.'" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="text">'.translate("Text").'</label>
         <div class="col-sm-8">
            <textarea class="form-control" name="text">'.$text.'</textarea>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="reviewer">'.translate("Reviewer").'</label>
         <div class="col-sm-8">
            <input type="text" class="form-control" name="reviewer" value="'.$reviewer.'" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="email">'.translate("Email").'</label>
         <div class="col-sm-8">
            <input type="email" class="form-control" name="email" value="'.$email.'" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="score">'.translate("Score").'</label>
         <div class="col-sm-8">
            <input type="text" class="form-control" name="score" value="'.$score.'" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="url">'.translate("Link").'</label>
         <div class="col-sm-8">
            <input type="text" class="form-control" name="url" value="'.$url.'" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="url_title">'.translate("Link title").'</label>
         <div class="col-sm-8">
            <input type="text" class="form-control" name="url_title" value="'.$url_title.'" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="cover">'.translate("Cover image").'</label>
         <div class="col-sm-8">
            <input type="text" class="form-control" name="cover" value="'.$cover.'" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-4" for="hits">'.translate("Hits").'</label>
         <div class="col-sm-8">
            <input type="text" class="form-control" name="hits" value="'.$hits.'" />
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-8 ml-sm-auto">
            <input type="hidden" name="op" value="preview_review" />
            <input class="btn btn-primary col-12 mb-2" type="submit" value="'.translate("Preview Modifications").'" />
            <input class="btn btn-secondary col-12" type="button" onclick="history.go(-1)" value="'.translate("Cancel").'" />
         </div>
      </div>
      </form>';
      sql_free_result($result);
   }
   include ("footer.php");
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
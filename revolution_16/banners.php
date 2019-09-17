<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2019 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
include_once("mainfile.php");

function viewbanner() {
   global $NPDS_Prefix;
   $okprint=false; $while_limit=3; $while_cpt=0;
   $bresult = sql_query("SELECT bid FROM ".$NPDS_Prefix."banner WHERE userlevel!='9'");
   $numrows = sql_num_rows($bresult);
   while ((!$okprint) and ($while_cpt<$while_limit)) {
      // More efficient random stuff, thanks to Cristian Arroyo from http://www.planetalinux.com.ar
      if ($numrows>0) {
         mt_srand((double)microtime()*1000000);
         $bannum = mt_rand(0, $numrows);
      } else
         break;
      $bresult2 = sql_query("SELECT bid, userlevel FROM ".$NPDS_Prefix."banner WHERE userlevel!='9' LIMIT $bannum,1");
      list($bid, $userlevel) = sql_fetch_row($bresult2);
      if ($userlevel==0) {
         $okprint=true;
      } else {
         if ($userlevel==1) {
            if (secur_static("member")) {$okprint=true;}
         }
         if ($userlevel==3) {
            if (secur_static("admin")) {$okprint=true;}
         }
      }
      $while_cpt=$while_cpt+1;
   }
   // Le risque est de sortir sans un BID valide
   if (!isset($bid)) {
      $rowQ1=Q_Select("SELECT bid FROM ".$NPDS_Prefix."banner WHERE userlevel='0' LIMIT 0,1",86400);
      $myrow=$rowQ1[0];
      $bid=$myrow['bid'];
      $okprint=true;
   }

   if ($okprint) {
      global $myIP;
      $myhost = getip();
      if ($myIP!=$myhost) {
         sql_query("UPDATE ".$NPDS_Prefix."banner SET impmade=impmade+1 WHERE bid='$bid'");
      }
      if (($numrows>0) and ($bid)) {
         $aborrar = sql_query("SELECT cid, imptotal, impmade, clicks, imageurl, clickurl, date FROM ".$NPDS_Prefix."banner WHERE bid='$bid'");
         list($cid, $imptotal, $impmade, $clicks, $imageurl, $clickurl, $date) = sql_fetch_row($aborrar);
         if ($imptotal==$impmade) {
            sql_query("INSERT INTO ".$NPDS_Prefix."bannerfinish VALUES (NULL, '$cid', '$impmade', '$clicks', '$date', now())");
            sql_query("DELETE FROM ".$NPDS_Prefix."banner WHERE bid='$bid'");
         }

         if ($imageurl!='') {
            echo'<a href="banners.php?op=click&amp;bid='.$bid.'" target="_blank"><img class="img-fluid" src="'.aff_langue($imageurl).'" alt="" /></a>';
         } else {
            if (stristr($clickurl,'.txt')) {
               if (file_exists($clickurl)) {
                  include_once($clickurl);
               }
            } else {
               echo $clickurl;
            }
         }
      }
   }
}

function clickbanner($bid) {
    global $NPDS_Prefix;
    $bresult = sql_query("SELECT clickurl FROM ".$NPDS_Prefix."banner WHERE bid='$bid'");
    list($clickurl) = sql_fetch_row($bresult);
    sql_query("UPDATE ".$NPDS_Prefix."banner SET clicks=clicks+1 WHERE bid='$bid'");
    sql_free_result($bresult);
    if ($clickurl=='') {
       global $nuke_url;
       $clickurl=$nuke_url;
    }
    Header("Location: ".aff_langue($clickurl));
}

function clientlogin() {
   header_page();
   echo '
      <div class="card card-body mb-3">
      <h3 class="mb-4"><i class="fa fa-sign-in fa-lg mr-3"></i>'.translate("Connection").'</h3>
         <form action="banners.php" method="post">
            <fieldset>
               <div class="form-group row">
                  <label class="form-control-label col-sm-4" for="login">'.translate("Login").'</label>
                  <div class="col-sm-8">
                     <input class="form-control" type="text" id="login" name="login" maxlength="10" required="required" />
                  </div>
               </div>
               <div class="form-group row">
                  <label class="form-control-label col-sm-4" for="pass">'.translate("Password").'</label>
                  <div class="col-sm-8">
                     <input class="form-control" type="password" id="pass" name="pass" maxlength="10" required="required" />
                     <span class="help-block">'.translate("Please type your client informations").'</span>
                  </div>
               </div>
               <div class="form-group row">
                  <div class="col-sm-8 ml-sm-auto">
                     <input type="hidden" name="op" value="Ok" />
                     <button class="btn btn-primary col-sm-6 col-12" type="submit">'.translate("Submit").'</button>
                  </div>
               </div>
            </fieldset>
         </form>
      </div>';
      adminfoot('fv','','','no');
      footer_page();
}

function IncorrectLogin() {
   header_page();
   echo '<div class="alert alert-danger lead">'.translate("Incorrect Login!").'<br /><button class="btn btn-secondary mt-2" onclick="javascript:history.go(-1)" >'.translate("Go Back").'</button></div>';
   footer_page();
}

function header_page() {
   global $Titlesitename, $Default_Theme, $language;
   include_once("modules/upload/upload.conf.php");
   include("meta/meta.php");
   if ($url_upload_css) {
      $url_upload_cssX=str_replace('style.css',$language.'-style.css',$url_upload_css);
      if (is_readable($url_upload.$url_upload_cssX))
         $url_upload_css=$url_upload_cssX;
      print ("<link href=\"".$url_upload.$url_upload_css."\" title=\"default\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />\n");
   }
   if(file_exists ('modules/include/header_head.inc'))
      include('modules/include/header_head.inc');
   if(file_exists ('themes/'.$Default_Theme.'/include/header_head.inc'))
      include('themes/'.$Default_Theme.'/include/header_head.inc');
   if(file_exists ('themes/'.$Default_Theme.'/style/style.css'))
      echo '<link href="themes/'.$Default_Theme.'/style/style.css" rel="stylesheet" type=\"text/css\" media="all" />';

    echo '
   </head>
   <body style="margin-top:64px;">
      <div class="container-fluid">
      <nav class="navbar fixed-top navbar-toggleable-md navbar-inverse bg-primary">
        <a class="navbar-brand" href="index.php">Home</a>
     </nav>
         <h2 class="mt-4">'.translate("Advertising Statistics").' @ '.$Titlesitename.'</h2>
         <p align="center">';
}

function footer_page() {
   echo '</p>
      </div>
      <script type="text/javascript" src="lib/js/npds_adapt.js"></script>
   </body>
</html>';
}

function bannerstats($login, $pass) {
   global $NPDS_Prefix;
   $result = sql_query("SELECT cid, name, passwd FROM ".$NPDS_Prefix."bannerclient WHERE login='$login'");
   list($cid, $name, $passwd) = sql_fetch_row($result);
   if ($login=='' AND $pass=='' OR $pass=='') {
      IncorrectLogin();
   } else {
      if ($pass==$passwd) {
         header_page();
         echo '
         <h3>'.translate ("Current Active Banners for").' '.$name.'</h3>
         <table data-toggle="table" data-search="true" data-striped="true" data-mobile-responsive="true" data-show-export="true" data-show-columns="true" data-icons="icons" data-icons-prefix="fa">
            <thead>
               <tr>
                  <th class="n-t-col-xs-1" data-halign="center" data-align="right"  data-sortable="true">ID</th>
                  <th class="n-t-col-xs-2" data-halign="center" data-align="right" data-sortable="true">'.translate("Made").'</th>
                  <th class="n-t-col-xs-2" data-halign="center" data-align="right" data-sortable="true">'.translate("Impressions").'</th>
                  <th class="n-t-col-xs-2" data-halign="center" data-align="right" data-sortable="true">'.translate("Imp. Left").'</th>
                  <th class="n-t-col-xs-2" data-halign="center" data-align="right" data-sortable="true">'.translate("Clicks").'</th>
                  <th class="n-t-col-xs-1" data-halign="center" data-align="right" data-sortable="true">% '.translate("Clicks").'</th>
                  <th class="n-t-col-xs-1" data-halign="center" data-align="right">'.translate("Functions").'</th>
               </tr>
            </thead>
            <tbody>';
         $result = sql_query("SELECT bid, imptotal, impmade, clicks, date FROM ".$NPDS_Prefix."banner WHERE cid='$cid'");
         while (list($bid, $imptotal, $impmade, $clicks, $date) = sql_fetch_row($result)) {
            $rowcolor = tablos();
            if ($impmade==0) {
               $percent = 0;
            } else {
               $percent = substr(100 * $clicks / $impmade, 0, 5);
            }
            if ($imptotal==0) {
               $left = translate("Unlimited");
            } else {
               $left = $imptotal-$impmade;
            }
            echo '
               <tr>
                  <td>'.$bid.'</td>
                  <td>'.$impmade.'</td>
                  <td>'.$imptotal.'</td>
                  <td>'.$left.'</td>
                  <td>'.$clicks.'</td>
                  <td>'.$percent.'%</td>
                  <td><a href="banners.php?op=EmailStats&amp;login='.$login.'&amp;cid='.$cid.'&amp;bid='.$bid.'" ><i class="fa fa-envelope-o fa-lg mr-2" title="E-mail Stats"></i></a></td>
               </tr>';
         }
         
         global $nuke_url, $sitename;
         echo '
            </tbody>
         </table>
         <a href="'.$nuke_url.'" class="header" target="_blank">'.$sitename.'</a>';
         $result = sql_query("SELECT bid, imageurl, clickurl FROM ".$NPDS_Prefix."banner WHERE cid='$cid'");

         while (list($bid, $imageurl, $clickurl) = sql_fetch_row($result)) {
            $numrows = sql_num_rows($result);
            echo '<div class="card card-body mb-3">';

            if ($imageurl!='') {
               echo '
               <p><img src="'.aff_langue($imageurl).'" class="img-fluid" />';//pourquoi aff_langue ??
            } else {
               echo '<p>';
               echo $clickurl;
            }
            echo '
            <h4 class="mb-2">Banner ID : '.$bid.'</h4>';
            if ($imageurl!='') {
               echo '<p>'.translate("This Banners points to").' : <a href="'.aff_langue($clickurl).'" target="_Blank" >[ URL ]</a></p>';
            }
            echo '
            <form action="banners.php" method="get">';
            if ($imageurl!='') {
               echo '
               <div class="form-group row">
                  <label class="control-label col-sm-12" for="url">'.translate("Change").' URL</label>
                  <div class="col-sm-12">
                     <input class="form-control" type="text" name="url" maxlength="200" value="'.$clickurl.'" />
                  </div>
               </div>';
            } else {
               echo '
               <div class="form-group row">
                  <label class="control-label col-sm-12" for="url">'.translate("Change").' URL</label>
                  <div class="col-sm-12">
                     <input class="form-control" type="text" name="url" maxlength="200" value="'.htmlentities($clickurl, ENT_QUOTES, cur_charset).'" />
                  </div>
               </div>';
            }
            echo '
            <input type="hidden" name="login" value="'.$login.'" />
            <input type="hidden" name="bid" value="'.$bid.'" />
            <input type="hidden" name="pass" value="'.$pass.'" />
            <input type="hidden" name="cid" value="'.$cid.'" />
            <input class="btn btn-primary" type="submit" name="op" value="'.translate("Change").'" />
            </form>
            </p>
            </div>';
         }
         // Finnished Banners
         echo "<br />";
         echo '
         <h3>'.translate("Banners Finished for").' '.$name.'</h3>
         <table data-toggle="table" data-search="true" data-striped="true" data-mobile-responsive="true" data-show-export="true" data-show-columns="true" data-icons="icons" data-icons-prefix="fa">
            <thead>
               <tr>
                  <th class="n-t-col-xs-1" data-halign="center" data-align="right" data-sortable="true">ID</td>
                  <th data-halign="center" data-align="right" data-sortable="true">'.translate("Impressions").'</th>
                  <th data-halign="center" data-align="right" data-sortable="true">'.translate("Clicks").'</th>
                  <th class="n-t-col-xs-1" data-halign="center" data-align="right" data-sortable="true">% '.translate("Clicks").'</th>
                  <th data-halign="center" data-align="right" data-sortable="true">'.translate("Start Date").'</th>
                  <th data-halign="center" data-align="right" data-sortable="true">'.translate("End Date").'</th>
               </tr>
            </thead>
            <tbody>';
         $result = sql_query("SELECT bid, impressions, clicks, datestart, dateend FROM ".$NPDS_Prefix."bannerfinish WHERE cid='$cid'");
         while (list($bid, $impressions, $clicks, $datestart, $dateend) = sql_fetch_row($result)) {
            $percent = substr(100 * $clicks / $impressions, 0, 5);
            echo '
               <tr>
                  <td>'.$bid.'</td>
                  <td>'.wrh($impressions).'</td>
                  <td>'.$clicks.'</td>
                  <td>'.$percent.' %</td>
                  <td><small>'.$datestart.'</small></td>
                  <td><small>'.$dateend.'</small></td>
               </tr>';
         }
         echo '
            </tbody>
         </table>';
         adminfoot('fv','','','no');
         footer_page();
      } else {
         IncorrectLogin();
      }
   }
}

function EmailStats($login, $cid, $bid) {
   global $NPDS_Prefix;
   $result = sql_query("SELECT login FROM ".$NPDS_Prefix."bannerclient WHERE cid='$cid'");
   list($loginBD) = sql_fetch_row($result);
   if ($login==$loginBD) {
      $result2 = sql_query("SELECT name, email FROM ".$NPDS_Prefix."bannerclient WHERE cid='$cid'");
      list($name, $email) = sql_fetch_row($result2);
      if ($email=='') {
         header_page();
            echo "<p align=\"center\"><br />".translate("Statistics for Banner ID")." : $bid ".translate("can't be send because")."<br /><br />
            ".translate("there isn't an email associated with client")." $name<br /><br /><a href=\"javascript:history.go(-1)\" >".translate("Go Back")."</a></p>";
         footer_page();
      } else {
         $result = sql_query("SELECT bid, imptotal, impmade, clicks, imageurl, clickurl, date FROM ".$NPDS_Prefix."banner WHERE bid='$bid' AND cid='$cid'");
         list($bid, $imptotal, $impmade, $clicks, $imageurl, $clickurl, $date) = sql_fetch_row($result);
         if ($impmade==0) {
            $percent = 0;
         } else {
            $percent = substr(100 * $clicks / $impmade, 0, 5);
         }

         if ($imptotal==0) {
            $left = translate("Unlimited");
            $imptotal = translate("Unlimited");
         } else {
            $left = $imptotal-$impmade;
         }
         global $sitename, $gmt;
         $fecha = date(translate("dateinternal"),time()+((integer)$gmt*3600));
         $subject = translate("Advertising Statistics").' : '.$sitename;
         $message  = "Client : $name\n".translate("Banner")." ID : $bid\n".translate("Banner")." Image : $imageurl\n".translate("Banner")." URL : $clickurl\n\n";
         $message .= "Impressions ".translate("Purchased")." : $imptotal\nImpressions ".translate("Maded")." : $impmade\nImpressions ".translate("Lefted")." : $left\nClicks ".translate("Received")." : $clicks\nClicks ".translate("Percent")." : $percent%\n\n";
         $message .= translate("Report Generated on").' : '."$fecha\n\n";
         include("signat.php");

         send_email($email, $subject, $message, '', true, 'text');
         header_page();
         echo '
         <div class="jumbotron">
            <p>'.$fecha.'</p>
            <p>'.translate("Statistics for Banner ID").' : '.$bid.' '.translate("has been send to").'</p>
            <p>'.$email.' : Client : '.$name.'</p>
            <p><a href="javascript:history.go(-1)" class="btn btn-primary btn-lg">'.translate("Go Back").'</a></p>
         </div>';
      }
   } else {
      header_page();
      echo "<p align=\"center\"><br />".translate("Incorrect Login!")."<br /><br />".translate("Please")." <a href=\"banners.php?op=login\" class=\"noir\">".translate("login again")."</a></p>";
   }
   footer_page();
}
function change_banner_url_by_client($login, $pass, $cid, $bid, $url) {
    global $NPDS_Prefix;
    header_page();
    $result = sql_query("SELECT passwd FROM ".$NPDS_Prefix."bannerclient WHERE cid='$cid'");
    list($passwd) = sql_fetch_row($result);
    if (!empty($pass) AND $pass==$passwd) {
        sql_query("UPDATE ".$NPDS_Prefix."banner SET clickurl='$url' WHERE bid='$bid'");
        sql_query("UPDATE ".$NPDS_Prefix."banner SET clickurl='$url' WHERE bid='$bid'");
        echo "<p align=\"center\"><br />".translate("You changed the URL")."<br /><br /><a href=\"javascript:history.go(-1)\" class=\"noir\">".translate("Go Back")."</a></p>";
    } else {
        echo "<p align=\"center\"><br />".translate("Incorrect Login!")."<br /><br />".translate("Please")." <a href=\"banners.php?op=login\" class=\"noir\">".translate("login again")."</a></p>";
    }
    footer_page();
}
settype($op,'string');
switch ($op) {
   case 'click':
      clickbanner($bid);
   break;
   case 'login':
      clientlogin();
   break;
   case 'Ok':
      bannerstats($login, $pass);
   break;
   case translate('Change'):
      change_banner_url_by_client($login, $pass, $cid, $bid, $url);
   break;
   case 'EmailStats':
      EmailStats($login, $cid, $bid);
   break;
   default:
      if ($banners) {
         viewbanner();
      } else {
         redirect_url('index.php');
      }
   break;
}
?>

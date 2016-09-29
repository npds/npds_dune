<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2016 by Philippe Brunier                     */
/* IZ-Xinstall version : 1.2                                            */
/*                                                                      */
/* Auteurs : v.0.1.0 EBH (plan.net@free.fr)                             */
/*         : v.1.1.1 jpb, phr                                           */
/*         : v.1.1.2 jpb, phr, dev, boris                               */
/*         : v.1.1.3 dev - 2013                                         */
/*         : v.1.2 phr, jpb - 2016                                      */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
      include_once('lib/mysqli.php');
   } else {
      include_once('lib/mysql.php');
   }

   $lang_symb = substr($langue, 0, 3);
   if(file_exists($fichier_lang = 'install/languages/'.$langue.'/install-'.$lang_symb.'.php')) {
      include_once $fichier_lang;
   }
   else {
      include_once('install/languages/francais/install-fre.php');
   }

#autodoc FixQuotes($what) : Quote une chaîne contenant des '
function FixQuotes($what = '') {
   $what = str_replace("&#39;","'",$what);
   $what = str_replace("'","''",$what);
   while (preg_match("#\\\\'#", $what)) {
      $what = str_replace("\\\\'","'",$what);
   }
   return $what;
}

function verif_php() {
   global $stopphp, $phpver;
   $stopphp = 0;
   if(phpversion() < "4.0.6") { 
      $phpver = phpversion();
      $stopphp = 1;
   }
}

function verif_chmod() {
   global $stopngo, $listfich;
   $file_to_check = array("abla.log.php","cache.config.php","config.php","filemanager.conf","slogs/security.log","meta/meta.php","static/edito.txt","modules/upload/upload.conf.php");
   $i=0; $listfich='';
   foreach ($file_to_check as $v) {
      if(file_exists($v))
      {
         if(is_writeable($v)) {
            $listfich .= '<li class="list-group-item">'.ins_translate("Droits d'accès du fichier ").'<code class="code">'.$v.'</code> :  <span class="text-success">'.ins_translate("corrects").' !</span></li>';
         }
         else {
            $listfich .=  '<li class="list-group-item list-group-item-danger">'.ins_translate("Droits d'accès du fichier ").'<code class="code">'.$v.'</code> :  <span class="">'.ins_translate("incorrects").' !</span><br />
            <span class="">'.ins_translate("Vous devez modifier les droits d'accès (lecture/écriture) du fichier ") .$v. ' (chmod 666)</li>';
            $stopngo = 1;
         }
      }
      else {
         $listfich .=  '
         <li class="list-group-item list-group-item-danger">'.ins_translate("Le fichier").' '.$v.' '.ins_translate("est introuvable !").'</li>';
         $stopngo = 1;
      }
      $i++;
   }
   return $listfich;
}

function write_parameters($new_dbhost, $new_dbuname, $new_dbpass, $new_dbname, $new_NPDS_Prefix, $new_mysql_p, $new_system, $new_system_md5, $new_adminmail) {
   global $stage4_ok;
   $stage4_ok = 0;

   $file = file("config.php");
   $file[29] ="\$dbhost = \"$new_dbhost\";\n";
   $file[30] ="\$dbuname = \"$new_dbuname\";\n";
   $file[31] ="\$dbpass = \"$new_dbpass\";\n";
   $file[32] ="\$dbname = \"$new_dbname\";\n";
   $file[33] ="\$mysql_p = \"$new_mysql_p\";\n";
   $file[36] ="\$system = $new_system;\n";
   $file[37] ="\$system_md5 = $new_system_md5;\n";
   $file[213]="\$adminmail = \"$new_adminmail\";\n";
   $file[317]="\$NPDS_Prefix = \"$new_NPDS_Prefix\";\n";
   $NPDS_Key=uniqid("");
   $file[318]="\$NPDS_Key = \"$NPDS_Key\";\n";

   $fic = fopen("config.php", "w");
      while(list($n,$ligne) = each($file)) {
         fwrite($fic, $ligne);
   }
   fclose($fic);

   $stage4_ok = 1;
   return($stage4_ok);
}

function write_others($new_nuke_url, $new_sitename, $new_Titlesitename, $new_slogan, $new_Default_Theme, $new_startdate) {
   global $stage5_ok;
   $stage5_ok = 0;

   // Par défaut $parse=1 dans le config.php
   $new_sitename =  htmlentities(stripslashes($new_sitename));
   $new_Titlesitename = htmlentities(stripslashes($new_Titlesitename));
   $new_slogan = htmlentities(stripslashes($new_slogan));
   $new_startdate = stripslashes($new_startdate);
   $new_nuke_url = FixQuotes($new_nuke_url);

   $file = file("config.php");
   $file[89] ="\$sitename = \"$new_sitename\";\n";
   $file[90] ="\$Titlesitename = \"$new_Titlesitename\";\n";
   $file[91] ="\$nuke_url = \"$new_nuke_url\";\n";
   $file[93] ="\$slogan = \"$new_slogan\";\n";
   $file[94] ="\$startdate = \"$new_startdate\";\n";
   $file[100] ="\$Default_Theme = \"$new_Default_Theme\";\n";

   $fic = fopen("config.php", "w");
      while(list($n,$ligne) = each($file)) {
         fwrite($fic, $ligne);
   }
   fclose($fic);

   $stage5_ok = 1;
   return($stage5_ok);
}

function msg_erreur($message) {
   echo '<html>
   <body bgcolor="white"><br />
      <div style="text-align: center; font-weight: bold">
         <div style="font-face: arial; font-size: 22px; color: #ff0000">'.ins_translate($message).'</div>
      </div>
      </body>
</html>';
   die();
}
function mysql_connex() {
   global $dbhost, $dbuname, $dbname, $dbpass;
   $db_connect = sql_connect($dbhost, $dbuname, $dbpass);
   if($db_connect != FALSE)
   {
/*      if(!mysql_select_db("$dbname"))
      {
         $request = sql_query("CREATE DATABASE $dbname");
         if(!$request)
         {
            msg_erreur("Erreur : la base de données est inexistante et n'a pas pu être créée. Vous devez la créer manuellement ou demander à votre hébergeur !");
         }
         else
         {
            @mysql_select_db("$dbname");
            return($db_connect);
         }
      }
      else
      {*/
         return($db_connect);
//      }
   }
   else
   {
      msg_erreur("Erreur : la connexion à la base de données a échoué. Vérifiez vos paramètres !");
   }
}

function write_users($adminlogin, $adminpass1, $adminpass2, $NPDS_Prefix)
{
   include_once('config.php');
   global $system, $system_md5, $minpass, $stage7_ok, $NPDS_Prefix;
   if ($adminlogin != '')
   {
      if($adminpass1 != $adminpass2)
      {
         $stage7_ok = 2;
      }
      else
      {
         if(strlen($adminpass1) < $minpass)
         {
            $stage7_ok = 2;
         }
         else
         {
            $stage7_ok = 1;
            if($system_md5)
            {
               $adminpwd = crypt($adminpass2, $adminpass1);
            }
            else
            {
               $adminpwd = $adminpass1;
            }
            sql_connect();
            $result1 = sql_query("UPDATE ".$NPDS_Prefix."authors SET aid='$adminlogin', pwd='$adminpwd' WHERE radminsuper='1'");
            copy("modules/f-manager/users/modele.admin.conf.php","modules/f-manager/users/".strtolower($adminlogin).".conf.php");

            if(!$result1)
            {
               $stage7_ok = 0;
            }
         }
      }
   }
   else
   {
      $stage7_ok = 2;
   }
   return($stage7_ok);
}

function write_upload($new_max_size, $new_DOCUMENTROOT, $new_autorise_upload_p, $new_racine, $new_rep_upload, $new_rep_cache, $new_rep_log, $new_url_upload)
{
   global $langue, $nuke_url, $stage8_ok;
   $stage8_ok = 0;

   $file = file("modules/upload/upload.conf.php");
   $file[16] = "\$max_size = $new_max_size;\n";
   $file[21] = "\$DOCUMENTROOT = \"$new_DOCUMENTROOT\";\n";
   $file[24] = "\$autorise_upload_p = \"$new_autorise_upload_p\";\n";
   $file[28] = "\$racine = \"$new_racine\";\n";
   $file[31] = "\$rep_upload = \$racine.\"$new_rep_upload\";\n";
   $file[34] = "\$rep_cache = \$racine.\"$new_rep_cache\";\n";
   $file[37] = "\$rep_log = \$racine.\"$new_rep_log\";\n";
   $file[40] = "\$url_upload = \"$new_url_upload\";\n";

   $fic = fopen("modules/upload/upload.conf.php", "w");
      while(list($n,$ligne) = each($file)) {
         fwrite($fic, $ligne);
   }
   fclose($fic);
   
   $stage8_ok = 1;
   return($stage8_ok);
}


#autodoc language_iso($l,$s,$c) : renvoi le code language iso 639-1 et code pays ISO 3166-2  $l=> 0 ou 1(requis), $s, $c=> 0 ou 1 (requis)
function language_iso($l,$s,$c) {
    global $langue;
    $iso_lang='';$iso_country='';$ietf='';
    switch ($langue) {
        case "french": $iso_lang ='fr';$iso_country='FR'; break;
        case "english":$iso_lang ='en';$iso_country='US'; break;
        case "spanish":$iso_lang ='es';$iso_country='ES'; break;
        case "german":$iso_lang ='de';$iso_country='DE'; break;
        case "chinese":$iso_lang ='zh';$iso_country='CN'; break;
        default:
        break;
    }
    if ($c!==1) $ietf= $iso_lang;
    if (($l==1) and ($c==1)) $ietf=$iso_lang.$s.$iso_country;
    if (($l!==1) and ($c==1)) $ietf=$iso_country;
    if (($l!==1) and ($c!==1)) $ietf='';
    if (($l==1) and ($c!==1)) $ietf=$iso_lang;
    return ($ietf);
}

#autodoc formval($fv,$fv_parametres,$arg1,$foo) : fin d'affichage avec form validateur ou pas, ses parametres, fermeture div admin et inclusion footer.php  $fv=> fv : inclusion du validateur de form , $fv_parametres=> parametres particuliers pour differents input (objet js ex :   xxx: {},...), $arg1=>inutilisé,  $foo =='' ==> </div> et inclusion footer.php
function formval($fv,$fv_parametres,$arg1,$foo) {
if ($fv=='fv') {
echo '
<script type="text/javascript" src="lib/formvalidation/dist/js/formValidation.min.js"></script>
<script type="text/javascript" src="lib/formvalidation/dist/js/language/'.language_iso(1,"_",1).'.js"></script>
<script type="text/javascript" src="lib/formvalidation/dist/js/framework/bootstrap4.min.js"></script>
<script type="text/javascript" src="lib/js/checkfieldinp.js"></script>
<script type="text/javascript">
//<![CDATA[
'.$arg1.'
$(document).ready(function() {
   $("form")
   .attr("autocomplete", "off")
   
   .on("init.field.fv", function(e, data) {
      var $parent = data.element.parents(".form-group"),
       $icon   = $parent.find(\'.fv-control-feedback[data-fv-icon-for="\' + data.field + \'"]\');
      $icon.on("click.clearing", function() {
          if ($icon.hasClass("fv-control-feedback fa fa-ban fa-lg")) {
              data.fv.resetField(data.element);
          }
      })
   })

   .formValidation({
      locale: "'.language_iso(1,"_",1).'",
      framework: "bootstrap4",
      icon: {
         required: "glyphicon glyphicon-asterisk",

         valid: "fa fa-check fa-lg",
         invalid: "fa fa-ban fa-lg",
         validating: "glyphicon glyphicon-refresh"
      },
      fields: {
         alpha: {
         },';
echo '
         '.$fv_parametres;
echo '
         dzeta: {
         }
      }
   })

   .on("success.validator.fv", function(e, data) {
   // The password passes the callback validator
   // voir si on a plus de champs mot de passe : changer par un array de champs ...
   if ((data.field === "add_pwd" || data.field === "chng_pwd" || data.field === "pass") && data.validator === "callback") {
      // Get the score
      var score = data.result.score,$bar_cont=$("#passwordMeter_cont"),$pass_level=$("#pass-level"),
          $bar = $("#passwordMeter").find(".progress-bar");
      switch (true) {
        case (score === null):
            $bar.html("").css("width", "0%").removeClass().addClass("progress-bar");
            $bar_cont.attr("value","0");
            break;
        case (score <= 0):
            $bar.html("Tr&#xE8;s faible").css("width", "25%").removeClass().addClass("progress progress-striped progress-danger");
            $bar_cont.attr("value","25").removeClass().addClass("progress progress-striped progress-danger");
            $pass_level.html("Tr&#xE8;s faible").addClass("text-danger");
            break;
        case (score > 0 && score <= 2):
            $bar.html("Faible").css("width", "50%").removeClass().addClass("progress progress-striped progress-warning");
            $bar_cont.attr("value","50").removeClass().addClass("progress progress-striped progress-warning");
            $pass_level.html("Faible").addClass("text-warning");
            break;
        case (score > 2 && score <= 4):
            $bar.html("Moyen").css("width", "75%").removeClass().addClass("progress progress-striped progress-info");
            $bar_cont.attr("value","75").removeClass().addClass("progress progress-striped progress-info");
            $pass_level.html("Moyen").addClass("text-info");
            break;
        case (score > 4):
            $bar.html("Fort").css("width", "100%").removeClass().addClass("progress progress-striped progress-success");
            $bar_cont.attr("value","100").removeClass().addClass("progress progress-striped progress-success");
            $pass_level.html("Fort").addClass("text-success");
            break;
        default:
            break;
      }
      }
   });

})

//]]>
</script>'."\n";
}
if ($foo=='') {
echo '
</div>';
}
}
?>
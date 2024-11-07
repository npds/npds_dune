<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/* IZ-Xinstall version : 1.3                                            */
/*                                                                      */
/* Auteurs : v.0.1.0 EBH (plan.net@free.fr)                             */
/*         : v.1.1.1 jpb, phr                                           */
/*         : v.1.1.2 jpb, phr, dev, boris                               */
/*         : v.1.1.3 dev - 2013                                         */
/*         : v.1.2 phr, jpb - 2016                                      */
/*         : v.1.3 jpb - 2024                                           */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

// ==> définition des versions requises pour la MAJ
define("NEW_VERSION","v.16.8");
include_once('lib/mysqli.php');

#autodoc Mysql_Connexion() : Connexion plus détaillée ($mysql_p=true => persistente connexion) - Attention : le type de SGBD n'a pas de lien avec le nom de cette fonction
function Mysql_Connexion() {
   global $mysql_error, $dbhost, $dbname;
   $ret_p=sql_connect();
   return ($ret_p);
}
/****************/

$langue = isset($langue) ? $langue : 'fr';
if($langue) {
   if(file_exists($fichier_lang = 'install/languages/install-'.language_iso(1,0,0).'.php')) {
      include_once $fichier_lang;
   }
   else
      include_once('install/languages/install-'.$langue.'.php');
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

// ==> renvoi la version Php et une variable de blocage si elle est inférieure à celle désirée : 5.6
function verif_php() {
   global $stopphp, $phpver;
   $stopphp = 0;
   if(phpversion() < "5.6.0") { 
      $phpver = phpversion();
      $stopphp = 1;
   }
   else
      $phpver = phpversion();
   return ($phpver);
}

// ==> renvoi la version sql // à revoir dispo que quand on a établi une connection !
function verif_sql() {
   global $sqlver;
   $sqlgetver = (mysqli_get_server_version(Mysql_Connexion()))/10000;
   $mainversion = intval($sqlgetver);
   $subversion = ($sqlgetver-$mainversion)*10000/100;
   $sqlver = "$mainversion.$subversion";
   return ($sqlver);
}

// ==> controle le droit des fichiers et une variable de blocage si non writable ...
function verif_chmod() {
   global $stopngo, $listfich;
   $file_to_check = array('abla.log.php','cache.config.php','config.php','filemanager.conf','slogs/security.log','meta/meta.php','static/edito.txt','modules/upload/upload.conf.php');
   $i=0; $listfich='';
   foreach ($file_to_check as $v) {
      if(file_exists($v)) {
         if(is_writeable($v))
            $listfich .= '<li class="list-group-item">'.ins_translate("Droits d'accès du fichier ").'<code class="code">'.$v.'</code> :<span class="ms-1 text-success">'.ins_translate("corrects").' !</span></li>';
         else {
            $listfich .=  '<li class="list-group-item list-group-item-danger">'.ins_translate("Droits d'accès du fichier ").'<code class="code">'.$v.'</code> :<span class="ms-1">'.ins_translate("incorrects").' !</span><br />
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

function write_parameters($new_dbhost, $new_dbuname, $new_dbpass, $new_dbname, $new_NPDS_Prefix, $new_mysql_p, $new_adminmail) {
   global $stage4_ok;
   $stage4_ok = 0;

   $file = file("config.php");
   $file[29] ="\$dbhost = \"$new_dbhost\";\n";
   $file[30] ="\$dbuname = \"$new_dbuname\";\n";
   $file[31] ="\$dbpass = \"$new_dbpass\";\n";
   $file[32] ="\$dbname = \"$new_dbname\";\n";
   $file[33] ="\$mysql_p = \"$new_mysql_p\";\n";
   $file[214]="\$adminmail = \"$new_adminmail\";\n";
   $file[319]="\$NPDS_Prefix = \"$new_NPDS_Prefix\";\n";
   $NPDS_Key=uniqid("");
   $file[320]="\$NPDS_Key = \"$NPDS_Key\";\n";

   $fic = fopen("config.php", "w");
   foreach($file as $n => $ligne) {
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
   $file[90] ="\$sitename = \"$new_sitename\";\n";
   $file[91] ="\$Titlesitename = \"$new_Titlesitename\";\n";
   $file[92] ="\$nuke_url = \"$new_nuke_url\";\n";
   $file[94] ="\$slogan = \"$new_slogan\";\n";
   $file[95] ="\$startdate = \"$new_startdate\";\n";
   $file[101] ="\$Default_Theme = \"$new_Default_Theme\";\n";

   $fic = fopen("config.php", "w");
   foreach($file as $n => $ligne) {
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

function write_users($adminlogin, $adminpass1, $adminpass2, $NPDS_Prefix) {
   include_once('config.php');
   global $minpass, $stage7_ok, $NPDS_Prefix;
   if ($adminlogin != '') {
      if($adminpass1 != $adminpass2)
         $stage7_ok = 2;
      else {
         if(strlen($adminpass1) < $minpass)
            $stage7_ok = 2;
         else {
            $stage7_ok = 1;
               $AlgoCrypt = PASSWORD_BCRYPT;
               $min_ms = 100;
               $options = ['cost' => getOptimalBcryptCostParameter($adminpass1, $AlgoCrypt, $min_ms)];
               $hashpass = password_hash($adminpass1, $AlgoCrypt, $options);
               $adminpwd=crypt($adminpass1, $hashpass);
            sql_connect();
            $result1 = sql_query("UPDATE ".$NPDS_Prefix."authors SET aid='$adminlogin', pwd='$adminpwd', hashkey='1' WHERE radminsuper='1'");
            copy("modules/f-manager/users/modele.admin.conf.php","modules/f-manager/users/".strtolower($adminlogin).".conf.php");
            if(!$result1)
               $stage7_ok = 0;
         }
      }
   }
   else
      $stage7_ok = 2;
   return($stage7_ok);
}

function write_upload($new_max_size, $new_DOCUMENTROOT, $new_autorise_upload_p, $new_racine, $new_rep_upload, $new_rep_cache, $new_rep_log, $new_url_upload) {
   global $langue, $nuke_url, $stage8_ok;
   $stage8_ok = 0;

   $file = file("modules/upload/upload.conf.php");
   $file[16] = "\$max_size = $new_max_size;\n";
   $file[21] = "\$DOCUMENTROOT = \"$new_DOCUMENTROOT\";\n";
   $file[24] = "\$autorise_upload_p = \"$new_autorise_upload_p\";\n";
   $file[28] = "\$racine = \"$new_racine\";\n";
   $file[31] = "\$rep_upload = \"$new_rep_upload\";\n";
   $file[34] = "\$rep_cache = \"$new_rep_cache\";\n";
   $file[37] = "\$rep_log = \"$new_rep_log\";\n";
   $file[40] = "\$url_upload = \"$new_url_upload\";\n";

   $fic = fopen("modules/upload/upload.conf.php", "w");
   foreach($file as $n => $ligne) {
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

function formval($fv,$fv_parametres,$arg1,$foo) {
   global $minpass;
   if ($fv=='fv') {
      if($fv_parametres!='') $fv_parametres = explode('!###!',$fv_parametres);
      echo '
   <script type="text/javascript" src="lib/js/es6-shim.min.js"></script>
   <script type="text/javascript" src="lib/formvalidation/dist/js/FormValidation.full.min.js"></script>
   <script type="text/javascript" src="lib/formvalidation/dist/js/locales/'.language_iso(1,"_",1).'.min.js"></script>
   <script type="text/javascript" src="lib/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
   <script type="text/javascript" src="lib/formvalidation/dist/js/plugins/L10n.min.js"></script>
   <script type="text/javascript" src="lib/js/checkfieldinp.js"></script>
   <script type="text/javascript">
   //<![CDATA[
   '.$arg1.'
   var diff;
   document.addEventListener("DOMContentLoaded", function(e) {
      const strongPassword = function() {
         let score=0;
         return {
            validate: function(input) {
               const value = input.value;
               if (value === "") {
                  return {
                     valid: true,
                     meta:{score:null},
                  };
               }
               if (value === value.toLowerCase()) {
                  return {
                     valid: false,
                     message: "'.ins_translate("Le mot de passe doit contenir au moins un caractère en majuscule.").'",
                     meta:{score: score-1},
                  };
               }
               if (value === value.toUpperCase()) {
                  return {
                     valid: false,
                     message: "'.ins_translate("Le mot de passe doit contenir au moins un caractère en minuscule.").'",
                     meta:{score: score-2},
                  };
               }
               if (value.search(/[0-9]/) < 0) {
                  return {
                     valid: false,
                     message: "'.ins_translate("Le mot de passe doit contenir au moins un chiffre.").'",
                     meta:{score: score-3},
                  };
               }
               if (value.search(/[@\+\-!#$%&^~*_]/) < 0) {
                  return {
                     valid: false,
                     message: "'.ins_translate("Le mot de passe doit contenir au moins un caractère non alphanumérique.").'",
                     meta:{score: score-4},
                  };
               }
               if (value.length < 8) {
                  return {
                     valid: false,
                     message: "'.ins_translate("Le mot de passe doit contenir").' '.$minpass.' '.ins_translate("caractères au minimum").'",
                     meta:{score: score-5},
                  };
               }
               score += ((value.length >= 8) ? 1 : -1);
               if (/[A-Z]/.test(value)) score += 1;
               if (/[a-z]/.test(value)) score += 1; 
               if (/[0-9]/.test(value)) score += 1;
               if (/[@\+\-!#$%&^~*_]/.test(value)) score += 1;
               return {
                  valid: true,
                  meta:{score: score},
               };
            },
         };
      };
      FormValidation.validators.checkPassword = strongPassword;
      formulid.forEach(function(item, index, array) {
         const fvitem = FormValidation.formValidation(
            document.getElementById(item),{
               locale: "'.language_iso(1,"_",1).'",
               localization: FormValidation.locales.'.language_iso(1,"_",1).',
            fields: {
            ';
   if($fv_parametres!='')
      echo '
            '.$fv_parametres[0];
   echo '
            },
            plugins: {
               declarative: new FormValidation.plugins.Declarative({
                  html5Input: true,
               }),
               trigger: new FormValidation.plugins.Trigger(),
               submitButton: new FormValidation.plugins.SubmitButton(),
               defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
               bootstrap5: new FormValidation.plugins.Bootstrap5({rowSelector: ".mb-3"}),
               icon: new FormValidation.plugins.Icon({
                  valid: "fa fa-check",
                  invalid: "fa fa-times",
                  validating: "fas fa-sync",
                  onPlaced: function(e) {
                     e.iconElement.addEventListener("click", function() {
                        fvitem.resetField(e.field);
                     });
                  },
               }),
            },
         })
         .on("core.validator.validated", function(e) {
            if ((e.field === "adminpass1") && e.validator === "checkPassword") {
               var score = e.result.meta.score;
               const barre = document.querySelector("#passwordMeter_cont");
               const width = (score < 0) ? score * -18 + "%" : "100%";
               barre.style.width = width;
               barre.classList.add("progress-bar","progress-bar-striped","progress-bar-animated","bg-success");
               barre.setAttribute("aria-valuenow", width);
               if (score === null) {
                  barre.style.width = "100%";
                  barre.setAttribute("aria-valuenow", "100%");
                  barre.classList.replace("bg-success","bg-danger");
               } else 
                  barre.classList.replace("bg-danger","bg-success");
            }
         })';
      if($fv_parametres!='')
         if(array_key_exists(1, $fv_parametres))
            echo '
               '.$fv_parametres[1];
   echo '
      })
   });
   //]]>
   </script>';
   }
   switch($foo) {
      case '' :
         echo '
      </div>';
         include ('footer.php');
      break;
      case 'foo' :
         include ('footer.php');
      break;
   }
}

#autodoc getOptimalBcryptCostParameter($pass, $AlgoCrypt, $min_ms=100) : permet de calculer le cout algorythmique optimum pour la procédure de hashage
function getOptimalBcryptCostParameter($pass, $AlgoCrypt, $min_ms=100) {
   for ($i = 4; $i < 13; $i++) {
      $calculCost = [ 'cost' => $i ];
      $time_start = microtime(true);
      password_hash($pass, $AlgoCrypt, $calculCost);
      $time_end = microtime(true);
      if (($time_end - $time_start) * 1000 > $min_ms)
         return $i;
   }
}

?>
<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/* IZ-Xinstall-MAJ v.1.3                                                */
/*                                                                      */
/* Auteurs : v.0.1.0 EBH (plan.net@free.fr)                             */
/*         : v.1.1.1 jpb, phr                                           */
/*         : v.1.1.2 jpb, phr, dev, boris                               */
/*         : v.1.1.3 dev - 2013                                         */
/*         : v.1.2 phr, jpb - 2017                                      */
/*         : v.1.3 jpb - 2024                                           */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

// ==> suppression du fichier d'install et définition des versions requises pour la MAJ
if (file_exists('IZ-Xinstall.ok'))
   unlink('IZ-Xinstall.ok');
define("OLD_VERSION","v.16.3");
define("NEW_VERSION","v.16.4");
include_once('lib/mysqli.php');

// ==> infos provenant du fichier config.php en service pour langue
$file = file("config.php");
preg_match('#=\s\"(.*)\"#', $file[173], $r);
settype($langue,'string');
$langue = $r[1];
if($langue) {
   $lang_symb = substr($langue, 0, 3);
   if(file_exists($fichier_lang = 'install/languages/'.$langue.'/install-'.$lang_symb.'.php'))
      include_once $fichier_lang;
   else
      include_once('install/languages/french/install-fre.php');
}

// ==> contrôle de la version en service et forçage interface php pour mysql
function verif_npds() {
   $file = file("config.php");
   preg_match('#=\s\"(.*)\"#', $file[321], $r);
   $checkvers = $r[1] == OLD_VERSION ? 1 : 0;
   if ($checkvers == 0) {
      $message = '<strong class="fs-2">🚫 '.ins_translate("Mise à jour interrompue").' !</strong><br /><strong>'.$r[1].'</strong> : '.ins_translate("Cette version de npds définie dans votre fichier config.php est incompatible").' !<br />' .ins_translate("Cette mise à jour est uniquement compatible avec ces versions").' : <strong>'.OLD_VERSION.'</strong> '.ins_translate("vers").' <strong>'.NEW_VERSION.'</strong> !';
      msg_erreur($message);
      exit();
   }
   $file[33] ="\$mysql_p = 1;\n";
   $file[34] ="\$mysql_i = 1;\n";
   $fic = fopen("config.php", "w");
   foreach($file as $n => $ligne) {
      fwrite($fic, $ligne);
   }
   fclose($fic);
   unset($langue);
}

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

function verif_sql() {
   global $sqlver;
   $sqlgetver = (mysqli_get_server_version(sql_connect()))/10000;
   $mainversion = intval($sqlgetver);
   $subversion = ($sqlgetver-$mainversion)*10000/100;
   $sqlver = "$mainversion.$subversion";
   return ($sqlver);
}

function verif_chmod() {
   global $stopngo, $listfich;
   $file_to_check = array('config.php','slogs/security.log','meta/meta.php');
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

function msg_erreur($message) {
   entete();
   echo '
      <div class="alert alert-danger lead">
         <div>'.$message.'</div>
      </div>';
   pied_depage('danger');
   die();
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

?>
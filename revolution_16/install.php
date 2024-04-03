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

/*
# Inclusions des lib et contrôle versions
*/
include ('grab_globals.php');
include ('install/libraries/graphIZm.php');
include ('install/libraries/lib-inc.php');
if(!isset($stage)) {
   verif_npds();
   $stage = 1;
}
include('config.php');
verif_php();
verif_sql();

/*
# Paramètres install
*/
$cms_logo = 'install/images/header.png';
$cms_name = 'NPDS REvolution 16';
global $cms_logo, $cms_name, $Version_Num, $Version_Sub, $phpver;

/*
# install/etape_1.php
# Bienvenue :
#
*/
if($stage == 1) {
/*
   $file = file("config.php");
   $file[173] ="\$language = \"$langue\";\n";
   $fic = fopen("config.php", "w");
      foreach($file as $n => $ligne) {
         fwrite($fic, $ligne);
      }
   fclose($fic);
*/

   $colorst1 = '-success';
   $colorst2 = ' active';
   entete();
   menu();
   echo $menu;
   require('install/etape_1.php');
   if(!isset($op)) $op = 'etape_1';
   switch($op) {
      case 'etape_1':
      default:
      etape_1();
      break;
   }
 pied_depage('success');
}

/*
# install/etape_2.php
# Licence GNU GPL en fonction de la langue :
#   => install/languages/english/licence-eng.txt
#   => install/languages/french/licence-fra.txt
*/
settype($qi,'integer');
if($stage == 2 and $qi!=1) {
   $colorst1 = '-success';
   $colorst2 = '-success';
   $colorst3 = ' active';
   entete();
   menu();
   echo $menu;
   require('install/etape_2.php');
   if(!isset($op)) $op = 'etape_2';
   switch($op) {
      case 'etape_2':
      default:
      etape_2();
      break;
   }
   pied_depage('success');
};

/*
# install/etape_3.php
# Vérifications :
#   => install/libraries/lib-inc.php
#      fonctions : verif_php() (version de PHP, magic_quotes)
#                  verif_chmod (présence et droits des fichiers de configuration)
*/
if(($stage == 3) or ($stage == 2 and $qi==1)){
   require('install/etape_3.php');
   if(!isset($op)) $op = 'etape_3';
   switch($op) {
      case 'etape_3':
      default:
      etape_3();
      break;
   }
}

/*
# install/etape_6.php
# Mise à jour de la base de données
*/
if($stage == 6) {
   require('install/etape_6.php');
   if(!isset($op)) $op = 'etape_6';
   for($i=1;$i<=6;$i++) {
      ${"colorst".$i} ='-success';
   }
   $colorst7 = ' active';
   switch($op) {
      case 'write_database':
         global $stage, $langue, $stage6_ok, $NPDS_Prefix, $pre_tab, $sql_com, $qi, $aff_log;
         settype($out,'string');
         include('install/sql/build_sql-maj.php');
         // modification de structure et suppression de données
         sql_connect();
         maj_db_163to164();
         // réécriture de données
         build_sql_maj($NPDS_Prefix);
         require('install/sql/sql-maj.php');
         write_database();

         if($stage6_ok == 1) {
            $Xinst_log = date('d/m/y  H:j:s').' : Modification base de donnée mise à jour '.OLD_VERSION.' to '.NEW_VERSION."\n";
            $file = fopen("slogs/install.log", "a");
            fwrite($file, $Xinst_log);
            fclose($file);
            $colorst7 = ' active';
            $msg = '
                  <div class="alert alert-success">'.ins_translate('La base de données a été mise à jour avec succès !').'</div>'.$aff_log;
         }
         elseif($stage6_ok == 0) {
            $colorst7 = '-danger';
            $msg = '
                  <div class="alert alert-danger">'.ins_translate("La base de données n'a pas pu être modifiée. Vérifiez les paramètres ainsi que vos fichiers, puis réessayez à nouveau.").'</div>';
         }
         entete();
         menu();
         echo $menu;
         $out.= '
               <h3 class="mb-3">'.ins_translate('Base de données').'</h3>'.$msg;
         if($stage6_ok == 1) {
            $out.= '
               <form name="next" method="post" action="install.php">
                  <input type="hidden" name="langue" value="'.$langue.'" />
                  <input type="hidden" name="stage" value="9" />
                  <button type="submit" class="mt-3 btn btn-success">'.ins_translate('Etape suivante').'</button>
               </form>';
         }
         $out.= '
      </div>';
         echo $out;
         unset($stage6_ok);
      break;
      case 'etape_6':
      default:
         entete();
         menu();
         echo $menu;
      etape_6();
      break;
   }
   pied_depage('success');
}


/*
# install/etape_9.php
# Fin
*/
if($stage == 9) {
   for($i=1;$i<=9;$i++) {
      ${"colorst".$i} ='-success';
   }
   $colorst10 = ' active';

   entete();
   menu();
   echo $menu;
   require('install/etape_9.php');
   if(!isset($op)) $op = 'etape_9';
   switch($op) {
      case 'write_ok':
         // mise à jour version
         $file = file("config.php");
         $file[321] ="\$Version_Num = \"".NEW_VERSION."\";\n";
         $fic = fopen("config.php", "w");
         foreach($file as $n => $ligne) {
            fwrite($fic, $ligne);
         }
         fclose($fic);
         // creation fichier témoin
         $fp = fopen('IZ-Xinstall.ok', 'w');
         fclose($fp);
         // La suppression de l'installation
         function icare_delete_Dir($rep) {
            $dir = opendir($rep);
            chdir($rep);
            while($nom = readdir($dir)) {
               if ($nom != '.' && $nom != '..' && $nom != '') {
                  if (is_dir($nom)) {
                     $archive[$nom] = icare_delete_Dir($nom);
                     rmdir($nom);
                  } elseif(is_file($nom)) {
                     @unlink($nom);
                 }
               }
            }
            chdir('..');
            closedir($dir);
            return $archive;
         }

/*
         if (file_exists('IZ-Xinstall.ok')) {
            if (file_exists('install.php') OR is_dir('install')) {
               icare_delete_Dir('install');
               @rmdir ('install');
               @unlink('install.php');
            }
         }
*/
         
         echo '<script type="text/javascript">'."\n".'//<![CDATA['."\n".'document.location.href=\'index.php\';'."\n".'//]]>'."\n".'</script>';
         break;

      case 'etape_9':
      default;
         etape_9();
         break;
   }
   pied_depage('success');
}
?>
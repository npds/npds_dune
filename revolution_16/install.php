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

// Bloque le lancement de l'install si IZ-Xinstall.ok existe
if (file_exists('IZ-Xinstall.ok')) {include('admin/die.php');}

/*
# Contrôle des fichiers de base de IZ-Xinstall
*/
if(file_exists('grab_globals.php')) include ('grab_globals.php');
if(file_exists('install/libraries/graphIZm.php')) include ('install/libraries/graphIZm.php');
if(file_exists('install/libraries/lib-inc.php')) include ('install/libraries/lib-inc.php');
   include('config.php');

/*
# Paramètres install
*/
$cms_logo = 'install/images/header.png';
$cms_name = 'NPDS REvolution 16';
global $cms_logo, $cms_name, $Version_Num, $Version_Id, $Version_Sub;

if(!isset($stage)) { $stage = 0; }

/*
# install/etape_0.php
# Accueil :
#   => install/languages/english/welcome.txt
#   => install/languages/french/bienvenue.txt
#   0 : nouvelle installation (par defaut à supprimer)
*/
if($stage == 0) {
   entete();
   require('install/etape_0.php');
   etape_0();
   pied_depage();
}

/*
# install/etape_1.php
# Choix de la langue :
#   => install/languages
*/
if($stage == 1) {

   $file = file("config.php");
   $file[171] ="\$language = \"$langue\";\n";
   $fic = fopen("config.php", "w");
      while(list($n,$ligne) = each($file)) {
         fwrite($fic, $ligne);
   }
   fclose($fic);



   $colorst1 = '-success';
   $colorst2 = ' active';
   entete();
   menu();
   echo $menu;
   require('install/etape_1.php');
   if(!isset($op)) { $op = 'etape_1'; }
   switch($op) {
      case 'etape_1':
      default:
      etape_1();
      break;
   }
 pied_depage();
}

/*
# install/etape_2.php
# Licence GNU GPL en fonction de la langue :
#   => install/languages/english/licence-eng.txt
#   => install/languages/french/licence-fra.txt
*/
if($stage == 2 and $qi!=1) {
   $colorst1 = '-success';
   $colorst2 = '-success';
   $colorst3 = ' active';
   entete();
   menu();
   echo $menu;
   require('install/etape_2.php');
   if(!isset($op)) { $op = 'etape_2'; }
   switch($op) {
      case 'etape_2':
      default:
      etape_2();
      break;
   }
   pied_depage();
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
   if(!isset($op)) { $op = 'etape_3'; }
   switch($op) {
      case 'etape_3':
      default:
      etape_3();
      break;
   }
}

/*
# install/etape_4.php
# Définition des paramètres de connexion à la base de données (config.php)
#   => install/libraries/lib-inc.php
#      fonction : write_config()
*/
if($stage == 4) {
   $out='';
   $colorst1 = '-success';
   $colorst2 = '-success';
   $colorst3 = '-success';
   $colorst4 = '-success';
   $colorst5 = ' active';
   require('install/etape_4.php');
   if(!isset($op)) { $op = 'etape_4'; }
   switch($op) {
      case 'write_parameters':
         global $stage, $langue, $stage4_ok, $qi;
         write_parameters($new_dbhost, $new_dbuname, $new_dbpass, $new_dbname, $new_NPDS_Prefix, $new_mysql_p, $new_system, $new_system_md5, $new_adminmail);
         if($stage4_ok == 1) {
            $msg = $qi.'<div class="alert alert-success">'.ins_translate("Le fichier de configuration a été écrit avec succès !").'</div>';
            $Xinst_log = date('d/m/y  H:j:s').' : Ecriture paramètres de config pour '.$cms_name."\n";
            $file = fopen('slogs/install.log', 'a');
            fwrite($file, $Xinst_log);
            fclose($file);
            if($qi == 1) {Header('Location: install.php?stage=5&qi=1&langue='.$langue);exit;};
         }
         elseif($stage4_ok == 0) {
            $msg = '<div class="alert alert-danger">'.ins_translate("Le fichier de configuration n'a pas pu être modifié. Vérifiez les droits d'accès au fichier 'config.php', puis réessayez à nouveau.").'</div>';
         }
         entete();
         menu();
         echo $menu;
         $out .= '
                  <h3 class="m-b-2">'.ins_translate('Paramètres de connexion').'</h3>'.$msg;
         if($stage4_ok == 1 and $qi !=1) {
            $out.= '
                  <form name="submit" method="post" action="install.php">
                     <input type="hidden" name="langue" value="'.$langue.'" />
                     <input type="hidden" name="stage" value="5" />
                     <input type="submit" class="btn btn-success" value="'.ins_translate(" Etape suivante ").'" />
                  </form>';
         }
         $out.= '
               </div>';
         echo $out;
         unset($stage4_ok);
      break;
      case 'etape_4':
      default:
         entete();
         menu();
         echo $menu;
         etape_4();
      break;
   }
   pied_depage();
}

/*
# install/etape_5.php
# Définition d'autres paramètres (config.php)
#   => install/libraries/lib-inc.php
#      fonction : write_others()
*/
if($stage == 5){
   $out='';
   $colorst1 = '-success';
   $colorst2 = '-success';
   $colorst3 = '-success';
   $colorst4 = '-success';
   $colorst5 = '-success';
   $colorst6 = ' active';
   require('install/etape_5.php');
   if(!isset($op)) { $op = 'etape_5'; }
   switch($op) {
      case 'write_others':
         global $stage, $langue, $stage5_ok, $qi;
         write_others($new_nuke_url, $new_sitename, $new_Titlesitename, $new_slogan, $new_Default_Theme, $new_startdate);
         if($stage5_ok == 1) {
            $msg = '<div class="alert alert-success">'.ins_translate('Le fichier de configuration a été écrit avec succès !').'</div>';
            if($qi == 1) {Header('Location: install.php?stage=6&qi=1&langue='.$langue);exit;};
         }
         elseif($stage5_ok == 0) {
            $msg = '<div class="alert alert-danger">'.ins_translate("Le fichier de configuration n'a pas pu être modifié. Vérifiez les droits d'accès au fichier 'config.php', puis réessayez à nouveau.").'</div>';
         }
         entete();
         menu();
         echo $menu;
         $out .= '
               <h3 class="m-b-2">'.ins_translate("Fichier de configuration").'</h3>'.$msg;
         if($stage5_ok == 1 and $qi !=1) {
            $out.= '
               <form name="next" method="post" action="install.php">
                  <input type="hidden" name="langue" value="'.$langue.'" />
                  <input type="hidden" name="stage" value="6" />
                  <input type="submit" class="btn btn-success" value="'.ins_translate(' Etape suivante ').'" />
               </form>';
         }
      $out.= '
            </div>';
      echo $out;
      unset($stage5_ok);
      break;
      case "etape_5":
      default:
         entete();
         menu();
         echo $menu;
         etape_5();
      break;
   }
   pied_depage();
}

/*
# install/etape_6.php
# Création/Mise à jour de la base de données
*/
if($stage == 6) {
   require('install/etape_6.php');
   if(!isset($op)) { $op = 'etape_6'; }
   $colorst1 = '-success';
   $colorst2 = '-success';
   $colorst3 = '-success';
   $colorst4 = '-success';
   $colorst5 = '-success';
   $colorst6 = '-success';
   $colorst7 = ' active';
   entete();
   switch($op) {
      case 'write_database':
         global $stage, $langue, $stage6_ok, $NPDS_Prefix, $pre_tab, $sql_com, $qi;
         require('install/sql/build_sql-create.php');
         build_sql_create($NPDS_Prefix);
         sql_connect();
         require('install/sql/sql-create.php');
         write_database();
         if($stage6_ok == 1) {
         $colorst7 = ' active';
            $msg = '
                  <div class="alert alert-success">'.ins_translate('La base de données a été créée avec succès !').'</div>';
         }
         elseif($stage6_ok == 0) {
            $colorst7 = '-danger';
            $msg = '
                  <div class="alert alert-danger">'.ins_translate("La base de données n'a pas pu être créée. Vérifiez les paramètres ainsi que vos fichiers, puis réessayez à nouveau.").'</div>';
         }
         menu();
         echo $menu;
         echo '
               <h3 class="m-b-2">'.ins_translate('Base de données').'</h3>';
         echo $msg;
         if($stage6_ok == 1) {
            $Xinst_log = date('d/m/y  H:j:s').' : Création tables et/ou base de donnée pour '.$cms_name."\n";
            $file = fopen("slogs/install.log", "a");
            fwrite($file, $Xinst_log);
            fclose($file);
            echo '
               <form name="next" method="post" action="install.php">
                  <input type="hidden" name="langue" value="'.$langue.'" />
                  <input type="hidden" name="stage" value="7" />
                  <input type="submit" class="btn btn-success" value="'.ins_translate(' Etape suivante ').'" />
               </form>';
         }
         echo '
      </div>';
         unset($stage6_ok);
      break;
      case "etape_6":
      default:
         menu();
         echo $menu;
      etape_6();
      break;
   }
   pied_depage();
}

/*
# install/etape_7.php
# Définition du mot de passe administrateur et premier utilisateur
*/
if($stage == 7) {
   $colorst1 = '-success';
   $colorst2 = '-success';
   $colorst3 = '-success';
   $colorst4 = '-success';
   $colorst5 = '-success';
   $colorst6 = '-success';
   $colorst7 = '-success';
   $colorst8 = ' active';
   entete();
   menu();
   echo $menu;
   require('install/etape_7.php');
   if(!isset($op)) { $op = 'etape_7'; }
   switch($op) {
      case 'write_users':
      global $stage, $langue, $stage7_ok,$NPDS_Prefix;
      if(($adminpass1 != '') AND ($adminpass2 != '')) {
         include('config.php');
         sql_connect();
         write_users($adminlogin, $adminpass1, $adminpass2, $NPDS_Prefix);
         if($stage7_ok == 2) {
            echo '<script type="text/javascript">'."\n".'//<![CDATA['."\n".'document.location.href=\'install.php?op=etape_7&stage=7&classe=0&langue='.$langue.'\';'."\n".'//]]>'."\n".'</script>';
         }
         else {
            echo '
               <h3 class="m-b-2">'.ins_translate('Compte Admin').'</h3>';
            if($stage7_ok == 1) {
               $msg = '
               <div class="alert alert-success">'.ins_translate("Le compte Admin a été modifié avec succès !").'</div>';
               @unlink("modules/f-manager/users/root.conf.php");
               @copy("modules/f-manager/users/modele.admin.conf.php","modules/f-manager/users/".strtolower($adminlogin).".conf.php");
            }
            elseif($stage7_ok == 0) {
               $msg = '
               <div class="alert alert-danger">'.ins_translate("Le compte Admin n'a pas pu être modifié. Vérifiez les paramètres ainsi que vos fichiers, puis réessayez à nouveau.").'</div>';
            }
            echo $msg;
                if($stage7_ok == 1) {
                   echo '
                   <form name="next" method="post" action="install.php">
                      <input type="hidden" name="langue" value="'.$langue.'" />
                      <input type="hidden" name="stage" value="8" />
                      <br />
                      <input type="submit" class="btn btn-success" value="'.ins_translate(" Etape suivante ").'" />
                  </form>';
                }
                echo '
                </div>';
             unset($stage7_ok);
          }
       }
      else {
         echo '<script type="text/javascript">'."\n".'//<![CDATA['."\n".'document.location.href=\'install.php?op=etape_7&stage=7&classe=0&langue='.$langue.'\';'."\n".'//]]>'."\n".'</script>';
      }
      break;
      case 'etape_7':
      default:
      include('config.php');
      etape_7();
      break;
   }
   pied_depage();
}

/*
# install/etape_8.php
# Configuration du module Upload
*/
if($stage == 8) {
   $colorst1 = '-success';
   $colorst2 = '-success';
   $colorst3 = '-success';
   $colorst4 = '-success';
   $colorst5 = '-success';
   $colorst6 = '-success';
   $colorst7 = '-success';
   $colorst8 = '-success';
   $colorst9 = ' active';
   $colorst10 = '';
   entete();
   menu();
   echo $menu;
   require('install/etape_8.php');
   if(!isset($op)) { $op = 'etape_8'; }
   switch($op) {
    case 'write_upload':
       global $stage, $langue, $stage8_ok;
       include('config.php');
       write_upload($new_max_size,$new_DOCUMENTROOT,$new_autorise_upload_p,$new_racine,$new_rep_upload,$new_rep_cache,$new_rep_log,$new_url_upload);
       echo '
               <h3 class="m-b-2">'.ins_translate("Configuration du module UPload").'</h3>';
       if($stage8_ok == 1) {
          $msg = 'Le fichier de configuration a été écrit avec succès !';
       }
       elseif($stage8_ok == 0) {// ce controle de fichier à déjà été effectué
          $msg = "Le fichier de configuration n'a pas pu être modifié. Vérifiez les droits d'accès au fichier 'config.php', puis réessayez à nouveau.";
       }
          echo '<form name="next" method="post" action="install.php">
             '.ins_translate($msg);
          if($stage8_ok == 1) {
             echo '
                <input type="hidden" name="langue" value="'.$langue.'" />
                <input type="hidden" name="stage" value="9" />
                <br />
                <input type="submit" class="btn btn-success" value="'.ins_translate(" Etape suivante ").'" />';
          }
          echo '
            </form>
          </div>';
       unset($stage8_ok);
       break;
    case 'etape_8':
    default:
       etape_8();
       break;
   }
   pied_depage();
}

/*
# install/etape_9.php
# Fin
*/
if($stage == 9) {
   $colorst1 = '-success';
   $colorst2 = '-success';
   $colorst3 = '-success';
   $colorst4 = '-success';
   $colorst5 = '-success';
   $colorst6 = '-success';
   $colorst7 = '-success';
   $colorst8 = '-success';
   $colorst9 = '-success';
   $colorst10 = ' active';
   entete();
   menu();
   echo $menu;
   require('install/etape_9.php');
   if(!isset($op)) { $op = 'etape_9'; }
   switch($op) {
      case 'write_ok':
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
         if (file_exists('IZ-Xinstall.ok')) {
            if (file_exists('install.php') OR is_dir('install')) {
               icare_delete_Dir('install');
               @rmdir ('install');
               @unlink('install.php');
            }
         }
         echo '<script type="text/javascript">'."\n".'//<![CDATA['."\n".'document.location.href=\'index.php\';'."\n".'//]]>'."\n".'</script>';
         break;

      case 'etape_9':
      default;
         etape_9();
         break;
   }
   pied_depage();
}
?>
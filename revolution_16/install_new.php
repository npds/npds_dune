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
/*
 _________o$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$o
 ________o$$$$$$$$$_$$$$$$$$$$$$$$$$$_$$$$$$$$$$$o
 _______o$$$$$$$$$____$$$$$$$$$$$$$____$$$$$$$$$$o
 _____$$$$$$$$$$$______$$$$$$$$$$$______$$$$$$$$$$$$$
 ____$$$$$$$$$$$$$____$$$$$$$$$$$$$____$$$$$$$$$$$$$$
 ___$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
 __$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
 __$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
 ___$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$"_"$$$$$
 ___$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$___o$$$$
 ___$$$___$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$_____$$$$$
 ____$$____"$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$"______o$$$
 _____"$$$o_____"""$$$$$$$$$$$$$$$$$$"$$"_________$$$
 _______$$$o__________"$$""$$$$$$""""___________o$$$
 _______$$$$o________________________________o$$$"
 ________"$$$$o_____o$$$$$$o"$$$$o________o$$$$
 _________-"$$$$$oo_____""$$$$o$$$$$o___o$$$$""
 ____________""$$$$$oooo__"$$$o$$$$$$$$$"""
 _______________""$$$$$$$oo $$$$$$$$$$
 __________________________$$$$$$$$$$$
 __________________________$$$$$$$$$$$
 __________________________"$$$$$$$$$$"
 ___________________________"$$$$$$$$
 
 */
// Bloque le lancement de l'install si IZ-Xinstall.ok existe
if (file_exists('IZ-Xinstall.ok')) {
   include('admin/die.php');
}

#####################################################################
# Paramètres du CMS
#####################################################################

$cms_logo = 'install/images/header.png';
$cms_name = 'NPDS REvolution 16';
global $cms_logo,$cms_name;

#####################################################################
# Contrôle des fichiers de base de IZ-Xinstall
#####################################################################

if(file_exists('grab_globals.php')) include ('grab_globals.php');
if(file_exists('install/libraries/graphIZm.php')) include ('install/libraries/graphIZm.php');
if(file_exists('install/libraries/lib-inc.php')) include ('install/libraries/lib-inc.php');
if(!isset($stage)) { $stage = 0; }

#################################################################################
# install/etape_0.php
# Accueil :
#   => install/languages/english/welcome.txt
#   => install/languages/francais/accueil.txt
#   0 : nouvelle installation (par defaut ˆ supprimer)
#################################################################################

if($stage == 0) {
 $colorst1 = '#000000';
 $colorst2 = '#000000';
 $colorst3 = '#000000';
 $colorst4 = '#000000';
 $colorst5 = '#000000';
 $colorst6 = '#000000';
 $colorst7 = '#000000';
 $colorst8 = '#000000';
 $colorst9 = '#000000';
 $colorst10 = '#000000';
 entete();
 require('install/etape_0.php');
  etape_0();
 pied_depage();
}

#################################################################################
# install/etape_1.php
# Choix de la langue :
#   => install/languages/*
#################################################################################

if($stage == 1) {
 $colorst1 = '#ffffff';
 $colorst2 = '#FF9900';
 $colorst3 = '#000000';
 $colorst4 = '#000000';
 $colorst5 = '#000000';
 $colorst6 = '#000000';
 $colorst7 = '#000000';
 $colorst8 = '#000000';
 $colorst9 = '#000000';
 $colorst10 = '#000000';
 entete();
 menu();
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

#################################################################################
# install/etape_2.php
# Licence GNU GPL en fonction de la langue :
#   => install/languages/english/licence-eng.txt
#   => install/languages/francais/licence-fra.txt
#################################################################################

if($stage == 2) {
 $colorst1 = '#ffffff';
 $colorst2 = '#ffffff';
 $colorst3 = '#FF9900';
 $colorst4 = '#000000';
 $colorst5 = '#000000';
 $colorst6 = '#000000';
 $colorst7 = '#000000';
 $colorst8 = '#000000';
 $colorst9 = '#000000';
 $colorst10 = '#000000';
 entete();
 menu();
 require('install/etape_2.php');
 if(!isset($op)) { $op = 'etape_2'; }
 switch($op) {
  case 'etape_2':
  default:
  etape_2();
  break;
 }
 pied_depage();
}

#################################################################################
# install/etape_3.php
# Vérifications :
#   => install/libraries/lib-inc.php
#      fonctions : verif_php() (version de PHP, magic_quotes)
#                  verif_chmod (présence et droits des fichiers de configuration)
#################################################################################

if($stage == 3) {
 $colorst1 = '#ffffff';
 $colorst2 = '#ffffff';
 $colorst3 = '#ffffff';
 $colorst4 = '#FF9900';
 $colorst5 = '#000000';
 $colorst6 = '#000000';
 $colorst7 = '#000000';
 $colorst8 = '#000000';
 $colorst9 = '#000000';
 $colorst10 = '#000000';
 entete();
 menu();
 require('install/etape_3.php');
 if(!isset($op)) { $op = 'etape_3'; }
 switch($op) {
  case 'etape_3':
  default:
  etape_3();
  break;
 }
 pied_depage();
}

#################################################################################
# install/etape_4.php
# Définition des paramètres de connexion à la base de données (config.php)
#   => install/libraries/lib-inc.php
#      fonction : write_config()
#################################################################################

if($stage == 4) {
 $colorst1 = '#ffffff';
 $colorst2 = '#ffffff';
 $colorst3 = '#ffffff';
 $colorst4 = '#ffffff';
 $colorst5 = '#FF9900';
 $colorst6 = '#000000';
 $colorst7 = '#000000';
 $colorst8 = '#000000';
 $colorst9 = '#000000';
 entete();
 menu();
 require('install/etape_4.php');
 if(!isset($op)) { $op = 'etape_4'; }
 switch($op) {
  case 'write_parameters':
  global $stage, $langue, $stage4_ok;
  write_parameters($new_dbhost, $new_dbuname, $new_dbpass, $new_dbname, $new_NPDS_Prefix, $new_mysql_p, $new_system, $new_system_md5, $new_adminmail);
  echo '<h3>'.ins_translate('Paramètres de connexion').'</h3>';
  if($stage4_ok == 1) {
   $msg = 'Le fichier de configuration a été écrit avec succès !';
   $Xinst_log = date('d/m/y  H:j:s').' : Ecriture paramètres de config pour '.$cms_name."\n";
   $file = fopen("slogs/install.log", "a");
   fwrite($file, $Xinst_log);
   fclose($file);
  }
  elseif($stage4_ok == 0) {
   $msg = "Le fichier de configuration n'a pas pu être modifié. Vérifiez les droits d'accès au fichier 'config.php', puis réessayez à nouveau.";
  }
  echo '<form name="submit" method="post" action="install.php">'.ins_translate($msg);
 if($stage4_ok == 1) {
  echo '
   <input type="hidden" name="langue" value="'.$langue.'" />
   <input type="hidden" name="stage" value="5" />
   <br /><br />
   <input type="submit" class="btn btn-secondary" value="'.ins_translate(" Etape suivante ").'" />';
 }
  echo '</form></div>
';
   unset($stage4_ok);
   break;
  case "etape_4":
  default:
  etape_4();
  break;
 }
 pied_depage();
}

#################################################################################
# install/etape_5.php
# Définition d'autres paramètres (config.php)
#   => install/libraries/lib-inc.php
#      fonction : write_others()
#################################################################################

if($stage == 5) {
 $colorst1 = '#ffffff';
 $colorst2 = '#ffffff';
 $colorst3 = '#ffffff';
 $colorst4 = '#ffffff';
 $colorst5 = '#ffffff';
 $colorst6 = '#FF9900';
 $colorst7 = '#000000';
 $colorst8 = '#000000';
 $colorst9 = '#000000';
 $colorst10 = '#000000';
 entete();
 menu();
 require('install/etape_5.php');
 if(!isset($op)) { $op = 'etape_5'; }
 switch($op) {
  case 'write_others':
  global $stage, $langue, $stage5_ok;
  write_others($new_nuke_url, $new_sitename, $new_Titlesitename, $new_slogan, $new_Default_Theme, $new_startdate);
  echo '<h3>'.ins_translate("Fichier de configuration").'</h3>';
  if($stage5_ok == 1) {
   $msg = 'Le fichier de configuration a été écrit avec succès !';
  }
  elseif($stage5_ok == 0) {
   $msg = "Le fichier de configuration n'a pas pu être modifié. Vérifiez les droits d'accès au fichier 'config.php', puis réessayez à nouveau.";
  }
  echo '<form name="next" method="post" action="install.php">'.ins_translate($msg);
  if($stage5_ok == 1) {
   echo '
    <input type="hidden" name="langue" value="'.$langue.'" />
    <input type="hidden" name="stage" value="6" />
    <br /><br />
    <input type="submit" class="btn btn-secondary" value="'.ins_translate(' Etape suivante ').'" />
   ';
  }
  echo '</form></div>';
  unset($stage5_ok);
  break;
  case "etape_5":
  default:
  etape_5();
  break;
 }
 pied_depage();
}

#################################################################################
# install/etape_6.php
# Création/Mise à jour de la base de données
#################################################################################

if($stage == 6) {
 $colorst1 = '#ffffff';
 $colorst2 = '#ffffff';
 $colorst3 = '#ffffff';
 $colorst4 = '#ffffff';
 $colorst5 = '#ffffff';
 $colorst6 = '#ffffff';
 $colorst7 = '#FF9900';
 $colorst8 = '#000000';
 $colorst9 = '#000000';
 $colorst10 = '#000000';
 entete();
 menu();
 require('install/etape_6.php');
   if(!isset($op)) { $op = 'etape_6'; }
   switch($op) {
    case 'write_database':
     global $stage, $langue, $stage6_ok,$NPDS_Prefix,$pre_tab, $sql_com;

//        include('config.php');
//          if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
//       include_once('lib/mysqli.php');
//    } else{
//       include_once('lib/mysql.php');
//    }
        
        require('install/sql/build_sql-create.php');
        build_sql_create($NPDS_Prefix);
//        mysql_connex();
        sql_connect();
        require('install/sql/sql-create.php');
        write_database();
        
        echo '
        <h3>'.ins_translate('Base de données').'</h3>';
        if($stage6_ok == 1) {
           $msg = 'La base de données a été créée avec succès !';
        }
        elseif($stage6_ok == 0) {
           $msg = "La base de données n'a pas pu être créée. Vérifiez les paramètres ainsi que vos fichiers, puis réessayez à nouveau.";
        }
           echo '<form name="next" method="post" action="install.php">'.ins_translate($msg);
           if($stage6_ok == 1) {
             $Xinst_log = date('d/m/y  H:j:s').' : Création tables et/ou base de donnée pour '.$cms_name."\n";
             $file = fopen("slogs/install.log", "a");
             fwrite($file, $Xinst_log);
             fclose($file);
              echo '
                 <input type="hidden" name="langue" value="'.$langue.'" />
                 <input type="hidden" name="stage" value="7" />
                 <br /><br />
                 <input type="submit" class="btn btn-secondary" value="'.ins_translate(' Etape suivante ').'" />';
           }
           echo '</form></div>
           ';
        unset($stage6_ok);
     break;
    case "etape_6":
    default:
       etape_6();
       break;
   }
   pied_depage();
}

#################################################################################
# install/etape_7.php
# Définition du mot de passe administrateur et premier utilisateur
#################################################################################

if($stage == 7) {
   $colorst1 = '#ffffff';
   $colorst2 = '#ffffff';
   $colorst3 = '#ffffff';
   $colorst4 = '#ffffff';
   $colorst5 = '#ffffff';
   $colorst6 = '#ffffff';
   $colorst7 = '#ffffff';
   $colorst8 = '#FF9900';
   $colorst9 = '#000000';
   $colorst10 = '#000000';

   entete();
   menu();
   require('install/etape_7.php');
   if(!isset($op)) { $op = 'etape_7'; }
   switch($op) {
    case 'write_users':
       global $stage, $langue, $stage7_ok,$NPDS_Prefix;
       if(($adminpass1 != '') AND ($adminpass2 != ''))
       {
          include('config.php');
          sql_connect();
          write_users($adminlogin, $adminpass1, $adminpass2, $NPDS_Prefix);
          if($stage7_ok == 2) {
             echo '<script type="text/javascript">'."\n".'//<![CDATA['."\n".'document.location.href=\'install.php?op=etape_7&stage=7&classe=0&langue='.$langue.'\';'."\n".'//]]>'."\n".'</script>';
          }
          else
          {
             echo '
             <h3>'.ins_translate('Compte Admin').'</h3>';
             if($stage7_ok == 1) {
                $msg = 'Le compte Admin a été modifié avec succès !';
                @unlink("modules/f-manager/users/root.conf.php");
                @copy("modules/f-manager/users/modele.admin.conf.php","modules/f-manager/users/".strtolower($adminlogin).".conf.php");
             }
             elseif($stage7_ok == 0) {
                $msg = "Le compte Admin n'a pas pu être modifié. Vérifiez les paramètres ainsi que vos fichiers, puis réessayez à nouveau.";
             }
                echo '<form name="next" method="post" action="install.php">'.ins_translate($msg);
                if($stage7_ok == 1) {
                   echo '
                      <input type="hidden" name="langue" value="'.$langue.'" />
                      <input type="hidden" name="stage" value="8" />
                      <br /><br />
                      <input type="submit" class="btn btn-secondary" value="'.ins_translate(" Etape suivante ").'" />
                      ';
                }
                echo '</form></div>
             ';
             unset($stage7_ok);
          }
       }
       else
       {
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

#################################################################################
# install/etape_8.php
# Configuration du module Upload
#################################################################################

if($stage == 8) {
   $colorst1 = '#ffffff';
   $colorst2 = '#ffffff';
   $colorst3 = '#ffffff';
   $colorst4 = '#ffffff';
   $colorst5 = '#ffffff';
   $colorst6 = '#ffffff';
   $colorst7 = '#ffffff';
   $colorst8 = '#ffffff';
   $colorst9 = '#FF9900';
   $colorst10 = '#000000';

   entete();
   menu();
   require('install/etape_8.php');
   if(!isset($op)) { $op = 'etape_8'; }
   switch($op) {
    case 'write_upload':
       global $stage, $langue, $stage8_ok;
       include('config.php');
//       mysql_connex();
       write_upload($new_max_size,$new_DOCUMENTROOT,$new_autorise_upload_p,$new_racine,$new_rep_upload,$new_rep_cache,$new_rep_log,$new_url_upload);
       echo '
       <h3>'.ins_translate("Configuration du module UPload").'</h3>';
       if($stage8_ok == 1) {
          $msg = 'Le fichier de configuration a été écrit avec succès !';
       }
       elseif($stage8_ok == 0) {
          $msg = "Le fichier de configuration n'a pas pu être modifié. Vérifiez les droits d'accès au fichier 'config.php', puis réessayez à nouveau.";
       }
          echo '<form name="next" method="post" action="install.php">
             '.ins_translate($msg);
          if($stage8_ok == 1) {
             echo '
                <input type="hidden" name="langue" value="'.$langue.'" />
                <input type="hidden" name="stage" value="9" />
                <br /><br />
                <input type="submit" class="btn btn-secondary" value="'.ins_translate(" Etape suivante ").'" />';
          }
          echo '</form></div>
          ';
       unset($stage8_ok);
       break;
    case 'etape_8':
    default:
       etape_8();
       break;
   }
   pied_depage();
}

#################################################################################
# install/etape_9.php
# Fin
#################################################################################

if($stage == 9) {
   $colorst1 = '#ffffff';
   $colorst2 = '#ffffff';
   $colorst3 = '#ffffff';
   $colorst4 = '#ffffff';
   $colorst5 = '#ffffff';
   $colorst6 = '#ffffff';
   $colorst7 = '#ffffff';
   $colorst8 = '#ffffff';
   $colorst9 = '#ffffff';
   $colorst10 = '#FF9900';

   entete();
   menu();
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
            if ($nom != "." && $nom != ".." && $nom != "") {
               if (is_dir($nom)) {
                  $archive[$nom] = icare_delete_Dir($nom);
                  rmdir($nom);
               } elseif(is_file($nom)) {
                  @unlink($nom);
              }
            }
         }
         chdir("..");
         closedir($dir);
         return $archive;
         }

/*         if (file_exists('IZ-Xinstall.ok')) {
            if (file_exists('install.php') OR is_dir('install')) {
               icare_delete_Dir('install');
               @rmdir ('install');
               @unlink('install.php');
            }
         }*/
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
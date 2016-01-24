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
   } else{
      include_once('lib/mysql.php');
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
   global $stopngo;
   $stopngo = 0;
   if(phpversion() < "4.0.6")
   {
      $phpver = phpversion();
      echo "<ul><li>".ins_translate("Version actuelle de PHP")." : ".$phpver."</li></ul>"
      ."<div style=\"color: #FF0000\">".ins_translate("NPDS nécessite une version 4.0.6 ou supérieure !")."</div>";
      $stopngo = 1;
   }
}
function verif_chmod() {
   global $stopngo;
   $file_to_check = array("abla.log.php","cache.config.php","config.php","filemanager.conf","slogs/security.log","meta/meta.php","static/edito.txt","modules/upload/upload.conf.php");
   $i=0;
   foreach ($file_to_check as $v) {
      if(file_exists($v))
      {
         echo "<li>".ins_translate("Droits d'accès du fichier ")."<span class=\"fich\">".$v."</span> : ";
         if(is_writeable($v))
         {
            echo " <span class=\"vert\">".ins_translate("corrects")." !</span></li>\n";
         }
         else
         {
            echo " <span class=\"rouge\">".ins_translate("incorrects")." !</span></li>
            <ul><li style=\"color: #ff0000\">".ins_translate("Vous devez modifier les droits d'accès (lecture/écriture) du fichier ") .$v. " (chmod 666)</ul></li>\n";
            $stopngo = 1;
         }
      }
      else
      {
         echo "<li style=\"color: #ff0000\">".ins_translate("Le fichier")." $v ".ins_translate("est introuvable !")."</li>\n";
         $stopngo = 1;
      }
      $i++;
   }
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
?>
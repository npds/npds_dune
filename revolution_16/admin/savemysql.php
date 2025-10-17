<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2025 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
/*********************************************************************************************/
/* NPDS V - SavemySQL_Databases 0.5     (20040611)                                           */
/* based on SaveDB addon by Thomas Rudant (thomas.rudant@grunk.net)                          */
/* originally inspired by the build_dump librarie of phpMyAdmin (http://www.phpmyadmin.org)  */
/* Adapted by : M. PASCAL aKa EBH (plan.net@free.fr)                                         */
/*********************************************************************************************/

if (!function_exists('admindroits'))
   include 'die.php';
$f_meta_nom = 'SavemySQL';
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
$name = $aid;
include 'lib/archive.php';

function PrepareString($a_string = '') {
   $search       = array('\\','\'',"\x00", "\x0a", "\x0d", "\x1a"); //\x08\\x09, not required
   $replace      = array('\\\\','\\\'','\0', '\n', '\r', '\Z');
   return str_replace($search, $replace, $a_string);
}

function get_table_def($table) {
   global $dbname, $crlf, $dblink;
   settype($index, 'array');
   $k = 0;
   $result = sql_query('SELECT * FROM '.$table.' LIMIT 1');
   $count = sql_num_fields($result);

   $schema_create = '';
   $schema_create .= "DROP TABLE IF EXISTS $table;$crlf";
   $schema_create .= "CREATE TABLE $table ($crlf";//
   $result = sql_query('SHOW FIELDS FROM '.$table);
   while($row = sql_fetch_assoc($result)) {
      $schema_create .= " ".$row['Field']." ".$row['Type'];
      if(isset($row['Default']) && (!empty($row['Default']) || $row['Default'] == "0"))
         $schema_create .= " DEFAULT '".$row['Default']."'";
      if($row['Null'] != 'YES')
         $schema_create .= ' NOT NULL';
      if($row['Extra'] != '')
         $schema_create .= ' '.$row['Extra'];
      if ($k < ($count - 1)) $schema_create .= ','.$crlf;
      $k++;
   }
   $result = sql_query('SHOW KEYS FROM '.$table);
   while($row = sql_fetch_assoc($result)) {
      $kname = $row['Key_name'];
      if(($kname != 'PRIMARY') && ($row['Non_unique'] == 0))
         $kname = "UNIQUE|$kname";
      if(!isset($index[$kname]))
         $index[$kname] = array();
      $index[$kname][] = $row['Column_name'];
   }
   foreach($index as $x => $columns) {
      $schema_create .= ",$crlf";
      if($x == 'PRIMARY')
         $schema_create .= ' PRIMARY KEY ('.implode(', ', $columns).')';
      elseif (substr($x,0,6) == 'UNIQUE')
         $schema_create .= ' UNIQUE '.substr($x,7).' ('.implode(', ', $columns).')';
      else
         $schema_create .= " KEY $x (".implode(", ", $columns).")";
   }
   $schema_create .= "$crlf)";
   $schema_create = stripslashes($schema_create);
   $schema_create .= ' ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;';
   sql_free_result($result);
   return($schema_create);
}


   function get_table_content($table) {
      global $dbname, $crlf;

      $table_list = '';
      $schema_insert = '';
      $result = sql_query('SELECT * FROM '.$table);
      $count = sql_num_fields($result);
      while($row = sql_fetch_row($result)) {
         $schema_insert .= 'INSERT INTO '.$table.' VALUES (';
         for($j = 0; $j < $count; $j++) {
            if(!isset($row[$j]))
               $schema_insert .= ' NULL';
            else
               if ($row[$j] != '')
                  $schema_insert .= " '".PrepareString($row[$j])."'";
               else
                  $schema_insert .= " ''";
            if ($j < ($count -1))
               $schema_insert .= ",";
         }
         $schema_insert .= ");$crlf";//
      }
      if($schema_insert != '') {
         $schema_insert = trim($schema_insert);
         return($schema_insert);
      }
   }

   function dbSave() {
      global $dbname, $name, $MSos, $crlf;

      @set_time_limit(600);
      $date_jour = formatTimes(time(), IntlDateFormatter::MEDIUM, IntlDateFormatter::NONE);
      $date_op = date('Ymd');
      $filename = $dbname.'-'.$date_op;
      $tables = sql_list_tables($dbname);
      $num_tables = sql_num_rows($tables);
      if($num_tables == 0)
         echo '&nbsp;'.html_entity_decode(adm_translate("Aucune table n'a été trouvée"),ENT_COMPAT | ENT_HTML401,'UTF-8')."\n";
      else {
         $heure_jour = date('H:i:s');
         $data = "# ========================================================$crlf"
            ."# $crlf"
            ."# ".html_entity_decode(adm_translate("Sauvegarde de la base de données"),ENT_COMPAT | ENT_HTML401,'UTF-8')." : ".$dbname." $crlf"
            ."# ".html_entity_decode(adm_translate("Effectuée le"),ENT_COMPAT | ENT_HTML401,'UTF-8')." ".$date_jour." : ".$heure_jour." ".html_entity_decode(adm_translate("par"),ENT_COMPAT | ENT_HTML401,'UTF-8')." ".$name." $crlf"
            ."# $crlf"
            ."# ========================================================$crlf";
         while($row = sql_fetch_row($tables)) {
            $table = $row[0];
            $data .= "$crlf"
               ."# --------------------------------------------------------$crlf"
               ."# $crlf"
               ."# ".html_entity_decode(adm_translate("Structure de la table"),ENT_COMPAT | ENT_HTML401,'UTF-8')." '".$table."' $crlf"
               ."# $crlf$crlf";
            $data .= get_table_def($table)
               ."$crlf$crlf"
               ."# $crlf"
               ."# ".html_entity_decode(adm_translate("Contenu de la table"),ENT_COMPAT | ENT_HTML401,'UTF-8')." '".$table."' $crlf"
               ."# $crlf$crlf";
            $data .= get_table_content($table)
               ."$crlf$crlf"
               ."# --------------------------------------------------------$crlf";
         }
      }
      send_file($data,$filename,'sql',$MSos);
   }

   function dbSave_tofile($repertoire, $linebyline = 0, $savemysql_size = 256) {
      global $dbname, $name, $MSos, $crlf;

      @set_time_limit(600);
      $date_jour = formatTimes(time(), IntlDateFormatter::MEDIUM, IntlDateFormatter::NONE);
      $date_op = date('dmY');
      $filename = $dbname.'-'.$date_op;
      $tables = sql_list_tables($dbname);
      $num_tables = sql_num_rows($tables);
      if($num_tables == 0)
         echo '&nbsp;'.html_entity_decode(adm_translate("Aucune table n'a été trouvée"),ENT_COMPAT | ENT_HTML401,'UTF-8')."\n";
      else {
         if ((!isset($repertoire)) or ($repertoire == '')) $repertoire = '.';
         if (!is_dir($repertoire)) {
            @umask('0000');
            @mkdir($repertoire,0777);
            $fp = fopen($repertoire.'/index.html', 'w');
            fclose($fp);
         }

         $heure_jour = date('H:i:s');
         $data0 = "# ========================================================$crlf"
            ."# $crlf"
            ."# ".html_entity_decode(adm_translate("Sauvegarde de la base de données"),ENT_COMPAT | ENT_HTML401,'UTF-8')." : ".$dbname." $crlf"
            ."# ".html_entity_decode(adm_translate("Effectuée le"),ENT_COMPAT | ENT_HTML401,'UTF-8')." ".$date_jour." : ".$heure_jour." par ".$name." $crlf"
            ."# $crlf"
            ."# ========================================================$crlf";
         $data1 = '';
         $ifile = 0;
         while($row = sql_fetch_row($tables)) {
            $table = $row[0];
            $data1 .= "$crlf"
               ."# --------------------------------------------------------$crlf"
               ."# $crlf"
               ."# ".html_entity_decode(adm_translate("Structure de la table"),ENT_COMPAT | ENT_HTML401,'UTF-8')." '".$table."' $crlf"
               ."# $crlf$crlf";
            $data1 .= get_table_def($table)
               ."$crlf$crlf"
               ."# $crlf"
               ."# ".html_entity_decode(adm_translate("Contenu de la table"),ENT_COMPAT | ENT_HTML401,'UTF-8')." '".$table."' $crlf"
               ."# $crlf$crlf";
            $result = sql_query('SELECT * FROM '.$table);
            $count_line = sql_num_fields($result);
            while($row = sql_fetch_row($result)) {
               $schema_insert = 'INSERT INTO '.$table.' VALUES (';
               for($j = 0; $j < $count_line; $j++) {
                  if(!isset($row[$j]))
                     $schema_insert .= ' NULL';
                  else
                  if ($row[$j] != '')
                     $schema_insert .= " '". PrepareString($row[$j])."'";
                  else
                     $schema_insert .= " ''";
                  if($j < ($count_line -1))
                     $schema_insert .= ',';
               }
               $schema_insert .= ");$crlf";

               $data1 .= $schema_insert;

               if ($linebyline == 1) {
                  if (strlen($data1) > ($savemysql_size * 1024)) {
                     send_tofile($data0.$data1,$repertoire,$filename."-".sprintf("%03d", $ifile),"sql",$MSos);
                     $data1 = '';
                     $ifile++;
                  }
               }
            }

            $data1 .= "$crlf$crlf"
               ."# --------------------------------------------------------$crlf";

            if ($linebyline == 0) {
               if (strlen($data1) > ($savemysql_size * 1024)) {
                  send_tofile($data0.$data1,$repertoire,$filename.'-'.sprintf("%03d", $ifile),'sql',$MSos);
                  $data1 = '';
                  $ifile++;
               }
            }
         }
         if (strlen($data1) > 0) {
            send_tofile($data0.$data1,$repertoire,$filename.'-'.sprintf("%03d", $ifile),'sql',$MSos);
            $data1 = '';
            $ifile++;
         }
      }
   }

   switch ($op) {
      case 'SavemySQL':
         $MSos = get_os();
         $crlf = $MSos ? "\r\n" : "\n" ;
         if ($savemysql_mode == 2) {
            dbSave_tofile('slogs',0, $savemysql_size);
            echo '<script type="text/javascript">
                  //<![CDATA[
                     alert("'.html_entity_decode(adm_translate('Sauvegarde terminée. Les fichiers sont disponibles dans le répertoire /slogs'),ENT_COMPAT | ENT_HTML401,'UTF-8').'");
                  //]]>
                  </script>';
            redirect_url('admin.php');
         } else if ($savemysql_mode == 3) {
            dbSave_tofile('slogs',1, $savemysql_size);
            echo '<script type="text/javascript">
                  //<![CDATA[
                     alert("'.html_entity_decode(adm_translate('Sauvegarde terminée. Les fichiers sont disponibles dans le répertoire /slogs'),ENT_COMPAT | ENT_HTML401,'UTF-8').'");
                  //]]>
                  </script>';
            redirect_url('admin.php');
         } else {
            dbSave();
            redirect_url('admin.php');
         }
      break;
      default:
         header('Location: index.php');
      break;
   }
?>
<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/*                                                                      */
/* NPDS Copyright (c) 2001-2024 by Philippe Brunier                     */
/* =========================                                            */
/*                                                                      */
/* Multi DataBase Support - MysqlI                                      */
/* Copyright (c) JIRECK 2013                                            */
/* Mise à jour 2017/2024 jpb, nicolas2                                  */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
global $debugmysql; 
define('NPDS_DEBUG', $debugmysql);
$sql_nbREQ=0;

// Connexion
   function sql_connect() {
      global $mysql_p, $dbhost, $dbuname, $dbpass, $dbname, $dblink, $mysql_error;
      try {
         mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
         $host = ($mysql_p || !isset($mysql_p)) ? 'p:'.$dbhost : $dbhost;
         $dblink = mysqli_init();
         $dblink->options(MYSQLI_OPT_CONNECT_TIMEOUT, 10);
         $dblink->options(MYSQLI_INIT_COMMAND, "SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'");
         $dblink->options(MYSQLI_SET_CHARSET_NAME, "utf8mb4");
         if (!$dblink->real_connect($host, $dbuname, $dbpass, $dbname)) {
            throw new mysqli_sql_exception('Impossible de se connecter à la base de données');
         }
        return $dblink;
      }
      catch (mysqli_sql_exception $e) {
         $mysql_error = $e->getMessage();
         error_log("Erreur de connexion SQL : " . $mysql_error);
         if (defined('NPDS_DEBUG') && NPDS_DEBUG)
            Ecr_Log('mysql', "Erreur de connexion SQL :" . $mysql_error, ""); 
         return false;
       }
   }
// Erreur survenue
   function sql_error() {
      global $dblink;
      if (!$dblink)
        return "Pas de connexion à la base de données";
      $error = mysqli_error($dblink);
      if ($error) {
        // Log l'erreur pour le debugging
        error_log("Erreur SQL : " . $error);
      }
      return $error;
   }
// Exécution de requête
   function sql_query($sql) {
      global $sql_nbREQ, $dblink;
      $sql_nbREQ++;
      //var_dump($sql);// affiche toutes les requêtes du portail //
      // Fonction d'échappement améliorée
      $escape_value = function($value) use ($dblink) {
         // D'abord on retire les slashes existants
         $value = stripslashes($value);
         // On échappe avec mysqli_real_escape_string
         $value = mysqli_real_escape_string($dblink, $value);
         // Debug
         if (defined('NPDS_DEBUG') && NPDS_DEBUG) {
            error_log("Valeur avant échappement : " . $value);
            error_log("Valeur après échappement : " . $value);
         }
         return $value;
      };

      if (stripos($sql, 'INSERT') === 0 || stripos($sql, 'UPDATE') === 0) {
         $pattern = '/^(INSERT\s+INTO.*?VALUES\s*\()(.*)(\))$|^(UPDATE.*?SET\s+)(.*?)(\s*WHERE.*|\s*$)/is';
         if (preg_match($pattern, $sql, $matches)) {
            if (!empty($matches[2])) { // INSERT
               $values = $matches[2];
               // On traite chaque valeur entre guillemets
               $values = preg_replace_callback(
                  '/\'((?:[^\'\\\\]|\\\\.)*)\'/s',
                  function($m) use ($escape_value) {
                     return "'" . $escape_value($m[1]) . "'";
                  },
                  $values
               );
               $sql = $matches[1] . $values . $matches[3];
            }
            elseif (!empty($matches[5])) { // UPDATE
                $values = $matches[5];
                $values = preg_replace_callback(
                    '/=\s*\'((?:[^\'\\\\]|\\\\.)*)\'/s',
                    function($m) use ($escape_value) {
                        return "= '" . $escape_value($m[1]) . "'";
                    },
                    $values
                );
                $sql = $matches[4] . $values . $matches[6];
            }
        }
      }
      if (defined('NPDS_DEBUG') && NPDS_DEBUG) {
         error_log("Requête finale : " . $sql);
         Ecr_Log('mysql', 'Requête finale : ' . $sql, '');
      }

      $query_id = mysqli_query($dblink, $sql);
      if (!$query_id) {
         // Utilisation de sql_error() pour récupérer l'erreur de requête
         $error = sql_error();
         error_log("Échec de la requête : $sql - Erreur : $error");
         if (defined('NPDS_DEBUG') && NPDS_DEBUG)
            Ecr_Log('mysql', 'Échec de la requête : '.$sql.' - Erreur :'.$error, ""); 
         return false;
      }
      return $query_id;
   }
// Tableau Associatif du résultat
   function sql_fetch_assoc($q_id='') {
      if (empty($q_id)) {
         global $query_id;
         $q_id = $query_id;
      }
        return mysqli_fetch_assoc($q_id);
   }
// Tableau Numérique du résultat
   function sql_fetch_row($q_id='') {
      if (empty($q_id)) {
         global $query_id;
         $q_id = $query_id;
      }
      return mysqli_fetch_row($q_id);
   }
// Tableau du résultat
   function sql_fetch_array($q_id='') {
      if (empty($q_id)) {
         global $query_id;
         $q_id = $query_id;
      }
      return mysqli_fetch_array($q_id);
   }
// Resultat sous forme d'objet
   function sql_fetch_object($q_id='') {
      if (empty($q_id)) {
         global $query_id;
         $q_id = $query_id;
      }
      return mysqli_fetch_object($q_id);
   }
// Nombre de lignes d'un résultat
   function sql_num_rows($q_id='') {
      if (empty($q_id)) {
         global $query_id;
         $q_id = $query_id;
      }
      return mysqli_num_rows($q_id);
   }
// Nombre de champs d'une requête
   function sql_num_fields($q_id='') {
      global $dblink;
      if (empty($q_id)) {
        global $query_id;
        $q_id = $query_id;
      }
      return mysqli_field_count($dblink);
   }
// Nombre de lignes affectées par les requêtes de type INSERT, UPDATE et DELETE
   function sql_affected_rows() {
      global $dblink;
      return mysqli_affected_rows($dblink);
   }
// Le dernier identifiant généré par un champ de type AUTO_INCREMENT
   function sql_last_id() {
      global $dblink;
      return mysqli_insert_id($dblink);
   }
// Lister les tables
   function sql_list_tables($dbnom='') {
      if (empty($dbnom)) {
         global $dbname;
         $dbnom = $dbname;
      }
      return sql_query("SHOW TABLES FROM $dbnom");
   }

// Contrôle
   function sql_select_db() {
      global $dbname, $dblink;
      if (!mysqli_select_db($dblink, $dbname))
         return (false);
      else
         return (true);
   }
// Libère toute la mémoire et les ressources utilisées par la requête $query_id
   function sql_free_result($q_id) {
      if ($q_id instanceof mysqli_result) 
         return mysqli_free_result($q_id);
   }
// Ferme la connexion avec la Base de données
   function sql_close() {
      global $dblink, $mysql_p;
      if (!$mysql_p)
         return mysqli_close($dblink);
   }
?>
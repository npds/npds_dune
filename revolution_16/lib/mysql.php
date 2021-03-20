<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/*                                                                      */
/* NPDS Copyright (c) 2001-2021 by Philippe Brunier                     */
/* =========================                                            */
/*                                                                      */
/* Multi DataBase Support                                               */
/* Copyright (c) Tribal-Dolphin (www.tribal-dolphin.net) 2005           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

$sql_nbREQ=0;

// Escape string
   function SQL_escape_string ($arr) {
      global $dblink;
      if (function_exists("mysql_real_escape_string"))
         @mysql_real_escape_string($arr, $dblink);
      return ($arr);
   }
// Connexion
   function sql_connect() {
      global $mysql_p, $dbhost, $dbuname, $dbpass, $dbname;

      if (($mysql_p) or (!isset($mysql_p)))
         $dblink=@mysql_pconnect($dbhost, $dbuname, $dbpass);
      else
         $dblink=@mysql_connect($dbhost, $dbuname, $dbpass);

      if (!$dblink)
         return (false);
      else {
         if (!@mysql_select_db($dbname, $dblink))
            return (false);
         else
            return ($dblink);
      }
   }
// Erreur survenue
   function sql_error() {
      return @mysql_error();
   }
// Exécution de requête
   function sql_query($sql) {
      global $sql_nbREQ;
      $sql_nbREQ++;
      if (!$query_id = @mysql_query(SQL_escape_string($sql)))
         return false;
      else
         return $query_id;
   }

   function sql_fetch_array($q_id='') {
      if (empty($q_id)) {
         global $query_id;
         $q_id = $query_id;
      }
      return @mysql_fetch_array($q_id);
   }


// Tableau Associatif du résultat
   function sql_fetch_assoc($q_id="") {
      if (empty($q_id)) {
         global $query_id;
         $q_id = $query_id;
      }
        return @mysql_fetch_assoc($q_id);
   }
// Tableau Numérique du résultat
   function sql_fetch_row($q_id="") {
      if (empty($q_id)) {
         global $query_id;
         $q_id = $query_id;
      }
      return @mysql_fetch_row($q_id);
   }
// Resultat sous forme d'objet
   function sql_fetch_object($q_id="") {
      if (empty($q_id)) {
         global $query_id;
         $q_id = $query_id;
      }
      return @mysql_fetch_object($q_id);
   }
// Nombre de lignes d'un résultat
   function sql_num_rows($q_id="") {
      if (empty($q_id)) {
         global $query_id;
         $q_id = $query_id;
      }
      return @mysql_num_rows($q_id);
   }
// Nombre de champs d'une requête
   function sql_num_fields($q_id="") {
      if (empty($q_id)) {
        global $query_id;
        $q_id = $query_id;
      }
      return @mysql_num_fields($q_id);
   }
// Nombre de lignes affectées par les requêtes de type INSERT, UPDATE et DELETE
   function sql_affected_rows() {
      return @mysql_affected_rows();
   }
// Le dernier identifiant généré par un champ de type AUTO_INCREMENT
   function sql_last_id() {
      return @mysql_insert_id();
   }
// Lister les tables
   function sql_list_tables($dbnom="") {
      if (empty($dbnom)) {
         global $dbname;
         $dbnom = $dbname;
      }
      return @sql_query("SHOW TABLES FROM $dbnom");
   }
// Controle
   function sql_select_db() {
      global $dbname, $dblink;
      if (!@mysql_select_db($dblink, $dbname))
         return (false);
      else
         return (true);
   }
// Libère toute la mémoire et les ressources utilisées par la requête $query_id
   function sql_free_result($q_id="") {
      return @mysql_free_result($q_id);
   }
// Ferme la connexion avec la Base de données
   function sql_close($dblink) {
      return @mysql_close($dblink);
   }
?>
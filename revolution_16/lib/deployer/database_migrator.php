<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2025 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* The Free Software Foundation; either version 3 of the License.       */
/*                                                                      */
/* database_migrator.php  v.1.0                                         */
/* jpb & DeepSeek 2025                                                  */
/************************************************************************/
if (!function_exists('admindroits'))
   include 'die.php';

class NPDSDatabaseMigrator {
   private $currentSqlFile;
   private $newSqlFile;

   public function __construct($currentSqlFile, $newSqlFile) {
      error_log("MIGRATOR CONSTRUCT: currentSqlFile = " . $currentSqlFile);
      error_log("MIGRATOR CONSTRUCT: newSqlFile = " . $newSqlFile);
      $this->currentSqlFile = $currentSqlFile;
      $this->newSqlFile = $newSqlFile;
      //error_log("MIGRATOR CONSTRUCT: AFTER SET - current = " . $this->currentSqlFile);
      //error_log("MIGRATOR CONSTRUCT: AFTER SET - new = " . $this->newSqlFile);
   }

   /**
   * Parse un fichier SQL et extrait les structures de tables
   */
   private function parseSQLFile($filePath) {
      if (!file_exists($filePath))
         throw new Exception("Fichier SQL introuvable: " . $filePath);
      $content = file_get_contents($filePath);
      $tables = [];
      // Extraire les CREATE TABLE
      preg_match_all('/CREATE TABLE ([a-zA-Z_][a-zA-Z0-9_]*)\s*\(([^;]+)\)[^;]*;/is', $content, $matches, PREG_SET_ORDER);
      error_log("PARSING: Tables trouvées: " . count($matches));
      foreach ($matches as $i => $match) {
         error_log("PARSING: Table " . ($i+1) . ": " . $match[1]);
         //error_log("PARSING: Définition: " . substr($match[2], 0, 100) . "...");
      }
      foreach ($matches as $match) {
         $tableName = $match[1];
         $tableDefinition = $match[2];
         // Extraire les colonnes
         $columns = [];
         $lines = explode("\n", $tableDefinition);
         foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || $line[0] == ')' || preg_match('/^(PRIMARY KEY|KEY|UNIQUE KEY|CONSTRAINT)/i', $line))
               continue;
            // Extraire le nom de la colonne
            if (preg_match('/^([a-zA-Z_][a-zA-Z0-9_]*)\s+(.+)$/i', $line, $colMatch)) {
               $columnName = $colMatch[1];
               $columnDefinition = trim(rtrim($colMatch[2], ','));
               $columns[$columnName] = $columnDefinition;
            }
         }
         $tables[$tableName] = [
            'columns' => $columns,
            'definition' => $tableDefinition
         ];
      }
      return $tables;
   }

   /**
   * Compare deux structures de tables et retourne les différences
   */
   public function compareSchemas() {
      $this->debugToFile("=== COMPARE_SCHEMAS START ===");
      $currentSchema = $this->parseSQLFile($this->currentSqlFile);
      $newSchema = $this->parseSQLFile($this->newSqlFile);
      $this->debugToFile("Tables parsed", [
         'current' => count($currentSchema),
         'new' => count($newSchema)
      ]);
      // Afficher toutes les tables pour référence
      $this->debugToFile("Toutes les tables current", array_keys($currentSchema));
      $this->debugToFile("Toutes les tables new", array_keys($newSchema));
      $differences = [
         'new_tables' => [],
         'dropped_tables' => [],
         'new_columns' => [],
         'modified_columns' => [],
         'dropped_columns' => []
      ];
      // Tables supprimées
      $differences['dropped_tables'] = array_diff(array_keys($currentSchema), array_keys($newSchema));
      // Nouvelles tables
      $differences['new_tables'] = array_diff(array_keys($newSchema), array_keys($currentSchema));
      $this->debugToFile("Différences tables", [
         'new_tables' => $differences['new_tables'],
         'dropped_tables' => $differences['dropped_tables']
      ]);
      // DEBUG: Vérifier si les tables ont des colonnes
      $this->debugToFile("=== VÉRIFICATION COLONNES ===");
      foreach (['access', 'authors'] as $testTable) {
         if (isset($currentSchema[$testTable]) && isset($newSchema[$testTable])) {
            $this->debugToFile("Table $testTable - Colonnes current", array_keys($currentSchema[$testTable]['columns']));
            $this->debugToFile("Table $testTable - Colonnes new", array_keys($newSchema[$testTable]['columns']));
         }
      }

      // Comparaison des tables existantes
      $tableCount = 0;
      foreach ($newSchema as $tableName => $newTable) {
         if (!isset($currentSchema[$tableName])) 
            continue;
         $currentTable = $currentSchema[$tableName];
         $tableCount++;
         $this->debugToFile("=== TRAITEMENT TABLE $tableName ($tableCount/".count($newSchema).") ===");
         // Colonnes supprimées
         $droppedCols = array_diff(
            array_keys($currentTable['columns']),
            array_keys($newTable['columns'])
         );
         $differences['dropped_columns'][$tableName] = $droppedCols;
         // Nouvelles colonnes
         $newCols = array_diff(
            array_keys($newTable['columns']),
            array_keys($currentTable['columns'])
         );
         $differences['new_columns'][$tableName] = $newCols;
         $this->debugToFile("Colonnes table $tableName", [
            'current_count' => count($currentTable['columns']),
            'new_count' => count($newTable['columns']),
            'dropped' => $droppedCols,
            'new' => $newCols
         ]);
         // Colonnes modifiées - DEBUG COMPLET
         $columnComparisons = 0;
         foreach ($newTable['columns'] as $columnName => $newDefinition) {
            if (isset($currentTable['columns'][$columnName])) {
               $columnComparisons++;
               $currentDefinition = $currentTable['columns'][$columnName];
               // Debug pour TOUTES les colonnes des premières tables
               if ($tableCount <= 3) { // Juste les 3 premières tables pour éviter la surcharge
                  $this->debugToFile("Comparaison $tableName.$columnName", [
                     'current' => $currentDefinition,
                     'new' => $newDefinition,
                     'identique' => $currentDefinition === $newDefinition
                  ]);
               }
               // COMPARAISON DIRECTE sans normalisation
               if ($currentDefinition !== $newDefinition) {
                  $differences['modified_columns'][$tableName][$columnName] = [
                     'old' => $currentDefinition,
                     'new' => $newDefinition
                  ];
                  $this->debugToFile("*** COLONNE MODIFIÉE DÉTECTÉE: $tableName.$columnName ***");
               }
            }
         }
         $this->debugToFile("Comparaisons effectuées pour $tableName: $columnComparisons");
      }
      $this->debugToFile("=== COMPARE_SCHEMAS COMPLETE ===", [
         'total_new_tables' => count($differences['new_tables']),
         'total_dropped_tables' => count($differences['dropped_tables']),
         'total_new_columns' => array_sum(array_map('count', $differences['new_columns'])),
         'total_dropped_columns' => array_sum(array_map('count', $differences['dropped_columns'])),
         'total_modified_columns' => array_sum(array_map('count', $differences['modified_columns']))
      ]);
      return $differences;
   }

   /**
   * Debug ciblé vers un fichier pour éviter la surcharge des logs
   */
   private function debugToFile($message, $data = null) {
      $debugFile = 'slogs/install.log';
      $timestamp = date('d-m-Y H:i:s');
      $logMessage = "[$timestamp] $message";
      if ($data !== null)
         $logMessage .= " | " . (is_array($data) ? json_encode($data) : $data);
      $logMessage .= "\n";
      file_put_contents($debugFile, $logMessage, FILE_APPEND | LOCK_EX);
   }

   /**
   * Génère les requêtes SQL de migration
   */
   public function generateMigrationSQL($differences) {
      $queries = [];
      // Tables à supprimer (attention: données perdues!)
      foreach ($differences['dropped_tables'] as $table) {
         $queries[] = "DROP TABLE IF EXISTS `$table`;";
      }
      // Nouvelles tables (à créer)
      $newSchema = $this->parseSQLFile($this->newSqlFile);
      foreach ($differences['new_tables'] as $table) {
         if (isset($newSchema[$table]))
            $queries[] = "CREATE TABLE IF NOT EXISTS `$table` (" . $newSchema[$table]['definition'] . ");";
      }
      // Colonnes à supprimer
      foreach ($differences['dropped_columns'] as $table => $columns) {
         foreach ($columns as $column) {
            $queries[] = "ALTER TABLE `$table` DROP COLUMN `$column`;";
         }
      }
      // Nouvelles colonnes
      $newSchema = $this->parseSQLFile($this->newSqlFile);
      foreach ($differences['new_columns'] as $table => $columns) {
         foreach ($columns as $column) {
            if (isset($newSchema[$table]['columns'][$column])) {
               $definition = $newSchema[$table]['columns'][$column];
               $queries[] = "ALTER TABLE `$table` ADD COLUMN IF NOT EXISTS `$column` $definition;";
            }
         }
      }
      // Colonnes modifiées
      foreach ($differences['modified_columns'] as $table => $columns) {
         foreach ($columns as $column => $definitions) {
            $queries[] = "ALTER TABLE `$table` MODIFY COLUMN `$column` " . $definitions['new'] . ";";
         }
      }
      return $queries;
   }

   /**
   * Génère les requêtes de migration des données pour fonctions et metalang
   */
   public function generateDataMigrationQueries($newSqlContent, $structuralChanges = []) {
      $queries = [];
      // DEBUG
      error_log("=== DEBUG DATA MIGRATION ===");
      error_log("StructuralChanges reçus: " . print_r($structuralChanges, true));
      error_log("Fonctions modifiées: " . (isset($structuralChanges['modified_columns']['fonctions']) ? 'OUI' : 'NON'));
      // 1. FONCTIONS - Choix stratégique basé sur les modifications
      $functionInserts = $this->extractInsertStatements($newSqlContent, 'fonctions');
      $functionData = $this->parseFunctionInserts($functionInserts);
      $systemFunctionIds = range(1, 75);
      foreach ($systemFunctionIds as $id) {
         if (isset($functionData[$id])) {
            $queries[] = "DELETE FROM fonctions WHERE fid = $id;";
            $queries[] = $functionData[$id]['full_insert'];
         }
      }
      // 2. METALANG - Toujours DELETE+INSERT (structure simple + obligatoire='1')
      $metalangInserts = $this->extractInsertStatements($newSqlContent, 'metalang');
      $metalangData = $this->parseMetalangInserts($metalangInserts);
      foreach ($metalangData as $def => $data) {
         $queries[] = "DELETE FROM metalang WHERE def = '$def' AND obligatoire = '1';";
         $queries[] = $data['full_insert'];
      }
      $queries[] = "ALTER TABLE fonctions ORDER BY fid;";
      return $queries;
   }

   /**
   * Génère un UPDATE pour une fonction à partir de son INSERT
   */
/*
   private function generateFunctionUpdate($insert, $id) {
      // Mix de champs avec et sans quotes : 'text', number, 'text', number...
      if (preg_match("#VALUES\((\d+),\s*'([^']*)',\s*(\d+),\s*'([^']*)',\s*(\d+),\s*(\d+),\s*'([^']*)',\s*'([^']*)',\s*'([^']*)',\s*'([^']*)',\s*'([^']*)',\s*(\d+),\s*'([^']*)',\s*(\d+)\)#", $insert, $match)) {
         return "UPDATE fonctions SET 
               fnom = '{$match[2]}',
               fdroits1 = {$match[3]},
               fdroits1_descr = '{$match[4]}',
               finterface = {$match[5]},
               fetat = {$match[6]},
               fretour = '{$match[7]}',
               fretour_h = '{$match[8]}',
               fnom_affich = '{$match[9]}',
               ficone = '{$match[10]}',
               furlscript = '{$match[11]}',
               fcategorie = {$match[12]},
               fcategorie_nom = '{$match[13]}',
               fordre = {$match[14]}
               WHERE fid = $id;";
      }
      error_log("Échec parsing INSERT fonction ID $id");
      return "DELETE FROM fonctions WHERE fid = $id;\n" . $insert;
   }
*/

   /**
   * Exécute les requêtes de migration
   */
   public function executeMigration($queries) {
      $results = [
         'success' => [],
         'errors' => []
      ];

      foreach ($queries as $query) {
         try {
            $result = sql_query($query);
            if ($result !== false) {
               $results['success'][] = $query;
                  if (is_resource($result) || $result instanceof mysqli_result)
                     sql_free_result($result);
            } else {
               $results['errors'][] = [
                  'query' => $query,
                  'error' => sql_error()
               ];
            }
         } catch (Exception $e) {
            $results['errors'][] = [
               'query' => $query,
               'error' => $e->getMessage()
            ];
         }
      }
      return $results;
   }

   /**
   * Extrait les statements INSERT d'une table spécifique
   */
   public function extractInsertStatements($sqlContent, $tableName) {
      // On cherche juste la présence de "INSERT" et du nom de la table
      $lines = explode("\n", $sqlContent);
      $inserts = [];
      foreach ($lines as $line) {
         $line = trim($line);
         // Vérifie que c'est bien un INSERT pour la table spécifique
         if (strpos($line, "INSERT INTO $tableName") === 0)
            $inserts[] = $line;
      }
      return $inserts;
   }

   /**
   * Parse les INSERT de la table fonctions pour extraire les données par ID
   */
   public function parseFunctionInserts($insertStatements) {
      $functions = [];
      foreach ($insertStatements as $insert) {
         // Debug: voir l'INSERT complet
         error_log("INSERT FONCTION: " . $insert);
         // Regex plus permissive
         if (preg_match("#VALUES\(\s*(\d+)\s*,#", $insert, $match)) {
            $id = (int)$match[1];
            $functions[$id] = [
               'fid' => $id,
               'full_insert' => $insert
            ];
            error_log("FONCTION CAPTURÉE: ID $id");
         }
      }
      return $functions;
   }

   /**
   * Parse les INSERT de la table metalang 
   * Ne garde que les metamots avec obligatoire='1'
   */
   public function parseMetalangInserts($insertStatements) {
      $metalangs = [];
      foreach ($insertStatements as $insert) {
         // Vérifie que ça se termine par ,'1');
         if (substr($insert, -6) === ",'1');" || substr($insert, -7) === ", '1');") {
            if (preg_match("#VALUES\(\s*'([^']*)'#", $insert, $match)) {
                $def = $match[1];
                $metalangs[$def] = [
                    'def' => $def,
                    'full_insert' => $insert
                ];
            }
         }
      }
      return $metalangs;
   }

   /**
   * Génère un rapport de migration
   */
   public function generateReport($differences, $queries, $executionResults = null) {
      $report = "=== RAPPORT DE MIGRATION DE BASE DE DONNÉES ===\n\n";
      $report .= "Fichier actuel: " . $this->currentSqlFile . "\n";
      $report .= "Nouveau fichier: " . $this->newSqlFile . "\n\n";
      $report .= "DIFFÉRENCES DÉTECTÉES:\n";
      $report .= "----------------------\n";
      $report .= "Nouvelles tables: " . count($differences['new_tables']) . "\n";
      $report .= "Tables supprimées: " . count($differences['dropped_tables']) . "\n";
      $report .= "Nouvelles colonnes: " . array_sum(array_map('count', $differences['new_columns'])) . "\n";
      $report .= "Colonnes supprimées: " . array_sum(array_map('count', $differences['dropped_columns'])) . "\n";
      $report .= "Colonnes modifiées: " . array_sum(array_map('count', $differences['modified_columns'])) . "\n\n";
      $report .= "REQUÊTES GÉNÉRÉES:\n";
      $report .= "------------------\n";
      foreach ($queries as $index => $query) {
         $report .= ($index + 1) . ". " . $query . "\n";
      }
      if ($executionResults) {
         $report .= "\nRÉSULTATS D'EXÉCUTION:\n";
         $report .= "---------------------\n";
         $report .= "Requêtes réussies: " . count($executionResults['success']) . "\n";
         $report .= "Erreurs: " . count($executionResults['errors']) . "\n";
         if (!empty($executionResults['errors'])) {
            $report .= "\nERREURS DÉTAILLÉES:\n";
            foreach ($executionResults['errors'] as $error) {
               $report .= "Query: " . $error['query'] . "\n";
               $report .= "Error: " . $error['error'] . "\n\n";
            }
         }
      }
      return $report;
   }

}

// ==================== UTILISATION ====================

/*
// Exemple d'utilisation
try {
    $migrator = new NPDSDatabaseMigrator(
        'sql/revolution_16_current.sql',  // Version actuelle
        'sql/revolution_16_new.sql'       // Nouvelle version
    );
    // Analyser les différences
    $differences = $migrator->compareSchemas();
    // Générer les requêtes de migration
    $queries = $migrator->generateMigrationSQL($differences);
    // Générer le rapport
    $report = $migrator->generateReport($differences, $queries);
    // Sauvegarder le rapport
    file_put_contents('migration_report.txt', $report);
    // Afficher un résumé
    echo "Migration analysée avec succès!\n";
    echo "Rapport sauvegardé dans: migration_report.txt\n";
    echo "Nombre de requêtes générées: " . count($queries) . "\n";
    // Pour exécuter automatiquement (décommenter avec prudence)
    /*
    $results = $migrator->executeMigration($queries);
    $finalReport = $migrator->generateReport($differences, $queries, $results);
    file_put_contents('migration_execution_report.txt', $finalReport);
    *\/
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
*/
?>
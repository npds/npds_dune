<?php
/************************************************************************/
/* DUNE by NPDS - admin prototype                                       */
/* ===========================                                          */
/*                                                                      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2025 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
/* mise à jour du portail fichiers et base de données                   */
/* utilise /lib/ npds_deployer et database_migrator                     */
/* jpb & DeepSeek 2025                                                  */
/************************************************************************/
if (!function_exists('admindroits'))
   include 'die.php';
$f_meta_nom = 'maj_npds';
$f_titre = 'Mise à jour';
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
global $language, $nuke_url, $Version_Num;
$hlpfile = 'manuels/'.$language.'/maj.html';

// TEMPORAIRE - Pour debug et comprendre le code
if (isset($_GET['debug_success']) && empty($_POST)) {
    // Scénarios de test - UNIQUEMENT pour les requêtes GET (pas les POST de formulaire)
    $scenarios = [
        'avec_backup' => [
            'version' => '16.8.1',
            'backup_file' => 'sql/backups/revolution_16_demo.sql',
            'old_version' => '16.8.0'
        ],
        'sans_backup' => [
            'version' => '16.8.1', 
            'backup_file' => '',
            'old_version' => '16.8.0'
        ]
    ];
    $scenario = $_GET['scenario'] ?? 'avec_backup';
    $params = $scenarios[$scenario] ?? $scenarios['avec_backup'];
    $_GET = array_merge($_GET, $params);
    maj_success();
    exit;
}

#autodoc getlatestRelease() : retourne la dernière version contenue dans le fichier versus.txt de github sous forme v.16.8 ou  si non disponible 16.8.1
function getlatestRelease() {
   $getversustxt = @file_get_contents('https://raw.githubusercontent.com/npds/npds_dune/master/versus.txt');
   if (!$getversustxt)
      return '16.8.1'; // Fallback si GitHub inaccessible
   $getlineversustxt = explode("\n", $getversustxt);
   array_pop($getlineversustxt);
   $versus_info = explode('|', $getlineversustxt[0]);
   return isset($versus_info[2]) ? $versus_info[2] : '16.8.1';
}

#autodoc getUpdateOptions($currentVersion) : filtre et retourne les options pour le select de la mise à jour fichiers en fonction de $currentVersion
function getUpdateOptions($currentVersion) {
   $latestStable = getlatestRelease();
   $currentClean = str_replace('v.', '', $currentVersion);
   $latestClean = str_replace('v.', '', $latestStable);
   $options = [];
   // Si version actuelle >= dernière stable → proposer seulement master
   if (version_compare($currentClean, $latestClean, '>='))
      $options['master'] = adm_translate('Version développement').' (master) - '.adm_translate('Mise à jour vers la dernière version de développement');
   // Si version actuelle < dernière stable → proposer stable ET master
   else {
      $options[$latestStable] = adm_translate('Version stable'). ' '. $latestStable . ' - '.adm_translate('Mise à jour recommandée');
      $options['master'] = adm_translate('Version développement').' (master) - ⚠️ '.adm_translate('Non recommandé en production');
   }
   return $options;
}

#autodoc getUpdateLog() : Retourne les 100 dernières lignes du fichier /slogs/install.log
function getUpdateLog() {
   $logFile = 'slogs/install.log';
   if (file_exists($logFile)) {
      $logContent = file_get_contents($logFile);
      $lines = explode("\n", $logContent);
      // Retourner les 100 dernières lignes
      return array_slice($lines, -100);
   }
   return [];
}

#autodoc backupCurrentSQL() : sauvegarde le schéma sql de la version en service dans /sql/backups/
function backupCurrentSQL($newVersion) {
   global $Version_Num;
   $backupDir = 'sql/backups/';
   if (!is_dir($backupDir))
      mkdir($backupDir, 0755, true);
   $currentSqlFile = "sql/revolution_16.sql";
   $backupFile = $backupDir . 'backup_'.$Version_Num.'_to_'.$newVersion.'_' . date('Y-m-d_His') . '.sql';
   if (file_exists($currentSqlFile)) {
      if (copy($currentSqlFile, $backupFile)) {
         return [
            'success' => true, 
            'file' => $backupFile,
            'message' => 'Sauvegarde SQL créée: ' . basename($backupFile)
         ];
      } else {
         return [
            'success' => false, 
            'error' => 'Échec de la copie du fichier SQL'
         ];
      }
   }
   return [
      'success' => false, 
      'error' => 'Fichier SQL actuel introuvable: ' . $currentSqlFile
   ];
}

#autodoc maj_main() : Affiche le premier écran de l'interface de mise à jour du portail
function maj_main() {
   global $Version_Num, $hlpfile, $f_meta_nom, $f_titre, $adminimg;
   include 'header.php';
   GraphicAdmin($hlpfile);
   adminhead($f_meta_nom, $f_titre, $adminimg);
   $currentVersion = $Version_Num;
   $latestStable = getlatestRelease();
   $updateOptions = getUpdateOptions($currentVersion);
   echo '
   <hr />
   <div class="row">
       <div class="col-md-6">
           <div class="alert alert-info">
               <h4>'.adm_translate('Version en service').'</h4>
               <p class="display-6">' . $currentVersion . '</p>
           </div>
       </div>
       <div class="col-md-6">
           <div class="alert alert-success">
               <h4>'.adm_translate('Dernière version').'</h4>
               <p class="display-6">' . $latestStable . '</p>
           </div>
       </div>
   </div>';
   // Comparaison des versions (nettoyer pour la comparaison)
   $currentClean = str_replace('v.', '', $currentVersion);
   $latestClean = str_replace('v.', '', $latestStable);
   if (version_compare($currentClean, $latestClean, '<')) {
      echo '
   <div class="alert alert-warning">
      <h4>📦 '.adm_translate('Mise à jour disponible').'</h4>
      <p>'.adm_translate('Une nouvelle version stable de NPDS est disponible').'.</p>
   </div>';
   } 
   else if (version_compare($currentClean, $latestClean, '>')) {
      echo '
   <div class="alert alert-info">
      <h4>🔬 '.adm_translate('Version avancée').'</h4>
      <p>'.adm_translate('Vous utilisez une version plus récente que la dernière version stable').'.</p>
   </div>';
   }
   else {
      echo '
   <div class="alert alert-success">
      <h4>✅ '.adm_translate('À jour').'</h4>
      <p>'.adm_translate('Votre installation NPDS est à jour avec la dernière version stable').'.</p>
   </div>';
   }
   // Formulaire de mise à jour
   echo '
   <div class="card text-bg-light mt-4">
      <div class="card-body">
         <h4>'.adm_translate('Options de mise à jour').'</h4>
         <form action="admin.php" method="get">
            <input type="hidden" name="op" value="maj" />
            <input type="hidden" name="action" value="preupdate" />
            <div class="mb-3">
               <label for="version" class="form-label">'.adm_translate('Choisir la version à installer').'</label>
               <select name="version" id="version" class="form-select">';
   foreach ($updateOptions as $value => $label) {
      $selected = ($value === $latestStable) ? 'selected="selected"' : '';
      echo '
                  <option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
   }
   echo '
              </select>
            </div>
            <button type="submit" class="btn btn-success">
              '.adm_translate('Préparer la mise à jour').'
            </button>
         </form>
      </div>
   </div>';
   include 'footer.php';
}

#autodoc maj_preupdate() : Affiche le deuxième écran de l'interface de mise à jour du portail recommandations, rappel des paramètres, et lancement ou pas de la mise à jour
function maj_preupdate() {
   global $hlpfile, $f_meta_nom, $f_titre, $adminimg;
   include 'header.php';
   GraphicAdmin($hlpfile);
   adminhead($f_meta_nom, $f_titre, $adminimg);
   $version = $_GET['version'] ?? getlatestRelease();
   echo '
   <hr />
   <div class="alert alert-info">
      <h4>📋 '.adm_translate('Recommandations préalables').'</h4>
      <p>'.adm_translate('Avant de lancer la mise à jour, vous pouvez').' :</p>
      <ul>
         <li>✅ '.adm_translate('Faire une sauvegarde complète du site').'...</li>
         <li>✅ '.adm_translate('Sauvegarder la base de données').'...</li>
      </ul>
      <h4>⚠️ '.adm_translate('Rappel des paramètres de mise à jour').'</h4>
      <p><strong>'.adm_translate('Version').' :</strong> ' . htmlspecialchars($version) . '</p>
      <p><strong>'.adm_translate('Sauvegarde automatique').' :</strong> ✅ Activée</p>
   </div>
   <div class="mt-4">
      <a href="admin.php?op=maj&action=update&version=' . urlencode($version) . '&path=' . urlencode(realpath(__DIR__ . '/..')) . '" class="btn btn-success me-2" onclick="return confirm(\'⚠️ Lancer la mise à jour vers ' . htmlspecialchars($version) . ' ?\')">
         🚀 '.adm_translate('Lancer la mise à jour').'
      </a>
      <a href="admin.php?op=maj" class="btn btn-secondary">'.adm_translate('Retour en arrière').'</a>
   </div>';
   include 'footer.php';
}

#autodoc maj_update() : sauvegarde le schéma sql de la version en service (/sql) et lance le déployeur
function maj_update() {
   $version = $_GET['version'] ?? getlatestRelease();
   $targetPath = $_GET['path'] ?? realpath(__DIR__ . '/..');
   // Étape 1: Sauvegarder le SQL actuel AVANT mise à jour
   $backupResult = backupCurrentSQL($version);
   // Étape 2: Déployer les nouveaux fichiers
   $deployerUrl = "/lib/deployer/npds_deployer.php?op=deploy&version=$version&confirm=yes&path=" . 
                   urlencode($targetPath) . "&return_url=" . 
                   urlencode("admin.php?op=maj");
   header("Location: $deployerUrl");
   exit;
}

#autodoc maj_success() : Affiche la réussite et les logs de la mise à jour et le bouton de migration de la base de données
function maj_success() {
   global $Version_Num, $hlpfile, $f_meta_nom, $f_titre, $adminimg;
   include 'header.php';
   GraphicAdmin($hlpfile);
   adminhead($f_meta_nom, $f_titre, $adminimg);
   $newVersion = $_GET['version'] ?? '16.8.0';
   $backupFile = $_GET['backup_file'] ?? '';
   $oldVersion = $_GET['old_version'] ?? $Version_Num ;
   echo '
         <hr />
         <h3 class="mb-3">'.adm_translate('Mise à jour fichiers terminée').'</h3>
         <div class="alert alert-success">
             <h4>✅ '.adm_translate('Mise à jour des fichiers réussie').'</h4>
             <p>'.adm_translate('La mise à jour a été effectuée avec succès').'.<br />
             <strong>'.adm_translate('Ancienne version').' :</strong> ' . $Version_Num . ' ==> <strong>'.adm_translate('Nouvelle version').' :</strong> ' . htmlspecialchars($newVersion) . '</p>
         </div>';
    // Vérifier si la migration BD est possible
    if ($backupFile && file_exists($backupFile)) {
        echo '
            <div class="alert alert-success">
               <h4>🔄 '.adm_translate('Migration de base de données disponible').'</h4>
               <p><strong>'.adm_translate('La mise à jour de la base de données est indispensable').'.</strong></p>
               <form action="admin.php" method="post" class="mt-3">
                  <input type="hidden" name="op" value="maj" />
                  <input type="hidden" name="action" value="migrate_db" />
                  <input type="hidden" name="old_version" value="' . $oldVersion . '" />
                  <input type="hidden" name="new_version" value="' . $newVersion . '" />
                  <input type="hidden" name="backup_file" value="' . htmlspecialchars($backupFile) . '" />
                  <button type="submit" class="btn btn-success" onclick="return confirm(\'Lancer la migration de la base de données?\')">
                     '.adm_translate('Migrer la Base de Données').'
                  </button>
               </form>
            </div>';
      } else {
         echo '
            <div class="alert alert-danger">
               <h4>ℹ️ '.adm_translate('Migration BD non disponible').'</h4>
               <p>'.adm_translate('La sauvegarde du schéma sql précédent n\'est pas disponible').'.</p>
               <p>'.adm_translate('Seules les nouvelles tables seront créées automatiquement').'.</p>
            </div>';
      }
      echo '
            <div class="alert alert-secondary">
               <h4 class="mb-3">📊 '.adm_translate('Logs de mise à jour').'</h4>
               <div class="small rounded" style="height: 150px; overflow-y: auto; padding: 10px; background: var(--bs-gray-100)">';

   $logLines = getUpdateLog();
   if (!empty($logLines))
      foreach ($logLines as $line) {
         echo htmlspecialchars($line) . '<br />';
      }
   else 
      echo '<em>'.adm_translate('Aucun log disponible').'</em>';
   echo '
        </div>
    </div>';
   include 'footer.php';
}

#autodoc maj_migrate_db() : Affiche les résultats des comparaison structurelles des deux fichiers sql et des requêtes générées nécessaires
function maj_migrate_db() {
   global $hlpfile, $f_meta_nom, $f_titre, $adminimg;
   include 'header.php';
   GraphicAdmin($hlpfile);
   adminhead($f_meta_nom, $f_titre, $adminimg);
   $oldVersion = $_POST['old_version'] ?? '';
   $newVersion = $_POST['new_version'] ?? '';
   $backupFile = $_POST['backup_file'] ?? $_GET['backup_file'] ?? '';
   echo '
         <hr />
         <h3 class="mb-3">'.adm_translate('Migration de la base de données').'</h3>';
   if (!$backupFile || !file_exists($backupFile)) {
      echo '
            <div class="alert alert-danger">
                <h5>❌ '.adm_translate('Erreur').'</h5>
                <p>'.adm_translate('Fichier de sauvegarde introuvable').'.</p>
                <a href="admin.php?op=maj&action=success&version=' . $newVersion . '" class="btn btn-secondary">
                    '.adm_translate('Retour en arrière').'
                </a>
            </div>';
      include 'footer.php';
   return;
   }
   // inclure le migrator
   require_once 'lib/deployer/database_migrator.php';
   $newSchemaFile = "sql/revolution_16.sql"; // Le NOUVEAU fichier
   if (!file_exists($newSchemaFile)) {
      echo '
            <div class="alert alert-danger">
                <h5>❌ '.adm_translate('Erreur').'</h5>
                <p>'.adm_translate('Nouveau schéma SQL introuvable').'.</p>
            </div>';
      include 'footer.php';
      return;
   }
   try {
      // DEBUG - vérifier le contenu AVANT la comparaison
      $backupContent = file_get_contents($backupFile);
      $newContent = file_get_contents($newSchemaFile);
      echo '
         <div class="alert alert-light">
            <h5>🔍 '.adm_translate('Comparaison').'</h5>
            <p>
               <strong>Fichier de backup :</strong> <code>' . $backupFile . '</code> (' . strlen($backupContent) . ' octets)<br />
               <strong>Fichier nouveau :</strong> <code>' . $newSchemaFile . '</code> (' . strlen($newContent) . ' octets)<br />
               <strong>'.adm_translate('Identiques').' ?</strong> : ' . ($backupContent === $newContent ? '✅ '.adm_translate('Oui') : '❌ '.adm_translate('Non')) . '
            </p>
         </div>';
      $migrator = new NPDSDatabaseMigrator($backupFile, $newSchemaFile);
      // 1. ANALYSER
      $differences = $migrator->compareSchemas();
      // SI DIFFÉRENCES AFFICHER
      if (!empty($differences['modified_columns'])) {
         echo '
            <div class="alert alert-light my-3">
               <h5>🔍 '.adm_translate('Analyse des différences structurelles').'</h5>';
         foreach ($differences['modified_columns'] as $tableName => $columns) {
            echo '
                  <h6>Table: <code>' . $tableName . '</code></h6>
                  <ul>';
            foreach ($columns as $columnName => $definitions) {
               echo '
                     <li><strong>' . $columnName . '</strong>: 
                        <br />Ancien : <code>' . htmlspecialchars($definitions['old']) . '</code>
                        <br />Nouveau : <code>' . htmlspecialchars($definitions['new']) . '</code>
                     </li>';
            }
            echo '
                  </ul>';
         }
         echo '
            </div>';
      }
      // 2. GÉNÉRER les requêtes
      $structureQueries = $migrator->generateMigrationSQL($differences);
      $dataQueries = $migrator->generateDataMigrationQueries(file_get_contents($newSchemaFile), $differences);
      $allQueries = array_merge($structureQueries, $dataQueries);
      if (empty($allQueries)) {
         echo '
         <div class="alert alert-success">
            <h5>✅ '.adm_translate('Aucune migration nécessaire').'</h5>
            <p>'.adm_translate('La structure de la base de données est déjà à jour').'.</p>
         </div>';
      } else {
         echo '
            <div class="alert alert-light">
               <h5>📜 '.adm_translate('Requêtes à exécuter').'</h5>
               <p>
                  Requêtes structurelles : '.count($structureQueries).'<br />
                  Requêtes de données : '.count($dataQueries).'<br />
                  '.adm_translate('Nombre de requêtes à exécuter').' : <strong>' . count($allQueries) . '</strong>
               </p>
               <div class="border rounded" style="max-height: 200px; overflow: auto; background: #f8f9fa; padding: 1rem;">';
         foreach ($allQueries as $i => $query) {
            // if($i==5) break;
            echo '
                  <div class="mb-0" style="white-space:nowrap">
                     <small class="text-dark">' . ($i + 1) . '.</small>
                     <code class="small">' . htmlspecialchars($query) . '</code>
                  </div>';
            $i++;
         }
         echo '
               </div>
               <div class="my-3">
                  <small><i>'.adm_translate('Ces requêtes peuvent modifier la structure de la base de données et le contenu total ou partiel de 2 tables fonctions et metalang').'</i></small>
               </div>
               <form action="admin.php" method="post">
                  <input type="hidden" name="op" value="maj" />
                  <input type="hidden" name="action" value="execute_migration" />
                  <input type="hidden" name="queries" value="' . htmlspecialchars(json_encode($allQueries)) . '" />
                  <input type="hidden" name="old_version" value="' . $oldVersion . '" />
                  <input type="hidden" name="new_version" value="' . $newVersion . '" />
                  <input type="hidden" name="backup_file" value="' . $backupFile . '" />
                  <button type="submit" class="btn btn-success me-2" onclick="return confirm(\''.html_entity_decode(adm_translate('EXÉCUTER les requêtes de migration? Cette action est irréversible.'),ENT_COMPAT | ENT_HTML401,'UTF-8').'\')">
                    '.adm_translate('Exécuter la Migration').'
                  </button>
                  <a href="admin.php?op=maj&action=success&version=' . $newVersion . '" class="btn btn-secondary">
                    '.adm_translate('Annuler').'
                  </a>
               </form>
            </div>';
      }
   } catch (Exception $e) {
      echo '
        <div class="alert alert-danger">
            <h5>❌ '.adm_translate('Erreur d\'analyse').'</h5>
            <p>' . htmlspecialchars($e->getMessage()) . '</p>
        </div>';
   }
   echo '
        </div>
    </div>';
   include 'footer.php';
}

#autodoc maj_execute_migration() : exécute les requetes sql et Affiche les résultats
function maj_execute_migration() {
   global $hlpfile, $f_meta_nom, $f_titre, $adminimg;
   include 'header.php';
   GraphicAdmin($hlpfile);
   adminhead($f_meta_nom, $f_titre, $adminimg);
   echo '
   <hr />
   <h3 class="mb-3">'.adm_translate('Migration de la base de données').'</h3>';
   require_once 'lib/deployer/database_migrator.php';
   try {
      $queriesJson = stripslashes($_POST['queries'] ?? '[]');
      $queries = json_decode($queriesJson, true);        // DEBUG
      if (json_last_error() !== JSON_ERROR_NONE)
         throw new Exception('Erreur JSON: ' . json_last_error_msg());
      if (empty($queries))
         throw new Exception('Aucune requête à exécuter');
      $oldVersion = $_POST['old_version'] ?? '';
      $newVersion = $_POST['new_version'] ?? '';
      $backupFile = $_POST['backup_file'] ?? '';
      if (empty($queries))
         throw new Exception('Aucune requête à exécuter');
      // Créer un migrator temporaire pour exécution
      $migrator = new NPDSDatabaseMigrator('', '');
      $results = $migrator->executeMigration($queries);
      echo '
         <div class="alert alert-success">
            <h4 class="mb-3">✅ '.adm_translate('Migration base de données terminée').'</h4>
            <p>'.adm_translate('Requêtes exécutées').' : <strong>' . count($results['success']) . '</strong><br />
            Erreurs: ' . count($results['errors']) . '</p>
            <h4 class="mb-3">✅ '.adm_translate('Mise à jour du portail terminée').'</h4>
            <h4 class="mb-3">💡 '.adm_translate('Prochaines étapes recommandées').'</h4>
                <ol>
                    <li>'.adm_translate('Vérifier le fonctionnement général du site').'</li>
                    <li>'.adm_translate('Tester les fonctionnalités principales (articles, forums, membres)').'</li>
                    <li>'.adm_translate('Vérifier la compatibilité des modules et thèmes').'</li>
                    <li>'.adm_translate('Consulter les logs d\'installation pour détecter d\'éventuels problèmes').'</li>
                </ol>
         </div>';

//      $testMode = true; // ← À désactiver en production
      if ($testMode) {
         // SCÉNARIO SUCCÈS + ÉCHEC SIMULTANÉS
         $results['errors'] = [
            [
               'query' => "ALTER TABLE test_table ADD COLUMN test_column INT;",
               'error' => "Table 'test_table' doesn't exist"
            ],
            [
               'query' => "UPDATE fonctions SET fnom='test' WHERE fid=999;", 
               'error' => "Unknown column 'fnom' in 'field list'"
            ]
         ];
         // Gardez aussi les succès réels
         $results['success'] = array_slice($queries, 0, 10); // 10 premières requêtes
      }
         
      if (!empty($results['errors'])) {
         echo '
            <div class="alert alert-danger">
            <h4>❌ '.adm_translate('Migration base de données interrompue').'</h4>
            <p>'.adm_translate('La migration de la base de données a échoué').'.</p>
            <h5>Erreurs rencontrées : ' . count($results['errors']) . '</h5>';
         foreach ($results['errors'] as $error) {
            echo '
            <div class="small mb-2">
               <code>' . htmlspecialchars($error['query']) . '</code><br>
               <strong>'.adm_translate('Erreur').' : </strong> ' . htmlspecialchars($error['error']) .'
            </div>';
         }
         echo '
            <h5>🚨 '.adm_translate('État actuel').'</h5>
            <ul>
               <li>'.adm_translate('La structure de la base de données est partiellement migrée').'</li>
               <li>'.adm_translate('Certaines données système peuvent être incohérentes').'</li>
            </ul>
            <h5>💡 '.adm_translate('Recommandations').'</h5>
            <ol>
               <li><strong>'.adm_translate('Option recommandée').' :</strong> '.adm_translate('Contacter le support').'</li>
               <li><strong>'.adm_translate('Option avancée').' :</strong> '.adm_translate('Exécuter manuellement les requêtes en erreur via phpMyAdmin').'</li>
               <li><strong>'.adm_translate('Option risquée').' :</strong> <a class="alert-link" href="admin.php?op=maj&action=migrate_db&old_version=' . urlencode($oldVersion) . '&new_version=' . urlencode($newVersion) . '&backup_file='.urlencode($backupFile).'">'.adm_translate('Relancer la migration').'</a></li>
            </ol>
            <h4 class="mb-3">❌ '.adm_translate('Mise à jour du portail non terminée').'</h4>';
         echo '
      </div>';
      }
   } catch (Exception $e) {
      echo '
      <div class="alert alert-danger">
         <h5>❌ Erreur lors de l\'exécution</h5>
         <p>' . htmlspecialchars($e->getMessage()) . '</p>
      </div>';
   }
   echo '
      </div>
   </div>';
   include 'footer.php';
}

/////// Fonction de test dédiée ///////////<== to delete
function maj_test_migration() {
    global $hlpfile, $f_meta_nom, $f_titre, $adminimg, $Version_Num;
    include 'header.php';
    GraphicAdmin($hlpfile);
    adminhead($f_meta_nom, $f_titre, $adminimg);
    echo '
    <div class="card">
        <div class="card-header">
            <h4>🧪 Test de Migration BD</h4>
        </div>
        <div class="card-body">
            <p>Cette page permet de tester la migration sans passer par le processus complet.</p>
            
            <form action="admin.php" method="post">
                <input type="hidden" name="op" value="maj" />
                <input type="hidden" name="action" value="test_migration_execute" />
                
                <div class="mb-3">
                    <label class="form-label">Ancienne version:</label>
                    <input type="text" name="old_version" class="form-control" value="'.$Version_Num.'" />
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Nouvelle version:</label>
                    <input type="text" name="new_version" class="form-control" value="16.8.1" />
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Fichier ancien schéma (optionnel):</label>
                    <input type="text" name="old_schema" class="form-control" value="sql/revolution_16.sql" />
                    <div class="form-text">Laisser vide pour utiliser la sauvegarde automatique</div>
                </div>
                
                <button type="submit" class="btn btn-warning">Tester la Migration</button>
                <a href="admin.php?op=maj" class="btn btn-secondary">Retour</a>
            </form>
        </div>
    </div>';
    
    include 'footer.php';
}

function maj_test_migration_execute() {
   global $hlpfile, $f_meta_nom, $f_titre, $adminimg;
   include 'header.php';
   GraphicAdmin($hlpfile);
   adminhead($f_meta_nom, $f_titre, $adminimg);
   $oldVersion = $_POST['old_version'] ?? '16.0.0';
   $newVersion = $_POST['new_version'] ?? '16.8.1';
   $oldSchema = $_POST['old_schema'] ?? '';
   echo '
    <div class="card">
        <div class="card-header">
            <h4>🧪 Résultat du Test Migration</h4>
        </div>
        <div class="card-body">';
   require_once 'lib/deployer/database_migrator.php';
   try {
      // Déterminer les fichiers source
      if ($oldSchema && file_exists($oldSchema)) {
         $oldFile = $oldSchema;
      } else {
         // Créer une sauvegarde de test
         $oldFile = backupCurrentSQL($newVersion)['file'] ?? '';
      }
      $newFile = "sql/revolution_16.sql";
      if (!file_exists($oldFile) || !file_exists($newFile)) {
         throw new Exception("Fichiers SQL manquants: $oldFile ou $newFile");
      }
      $migrator = new NPDSDatabaseMigrator($oldFile, $newFile);
      echo '<div class="alert alert-info"><h5>🔍 Analyse en cours...</h5></div>';
      $differences = $migrator->compareSchemas();
      $queries = $migrator->generateMigrationSQL($differences);
      echo '<div class="alert alert-success">
                <h5>✅ Test réussi</h5>
                <p>Migration de <strong>'.$oldVersion.'</strong> à <strong>'.$newVersion.'</strong></p>
                <p>Requêtes générées: <strong>'.count($queries).'</strong></p>
            </div>';
      // Afficher le détail
      echo '<h5>📋 Requêtes générées:</h5>
              <div style="max-height: 300px; overflow-y: auto; background: #f8f9fa; padding: 15px; border-radius: 5px;">';
      if (empty($queries)) {
         echo '<p class="text-muted">Aucune requête nécessaire - schémas identiques</p>';
      } else {
         foreach ($queries as $i => $query) {
            echo '<div class="mb-2"><small class="text-muted">'.($i+1).'.</small> <code>'.htmlspecialchars($query).'</code></div>';
         }
      }
      echo '</div>';
      // Option d'exécution réelle (pour tests avancés)
      if (!empty($queries)) {
         echo '
            <div class="mt-4 alert alert-warning">
                <h6>⚡ Exécution de test</h6>
                <p><small>Attention: cette action modifie la base de données</small></p>
                <form action="admin.php" method="post" class="mt-2">
                    <input type="hidden" name="op" value="maj" />
                    <input type="hidden" name="action" value="execute_migration" />
                    <input type="hidden" name="queries" value=\''.htmlspecialchars(json_encode($queries)).'\' />
                    <input type="hidden" name="old_version" value="'.$oldVersion.'" />
                    <input type="hidden" name="new_version" value="'.$newVersion.'" />
                    <input type="hidden" name="test_mode" value="1" />
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'EXÉCUTER les requêtes en mode TEST?\')">
                        Exécuter pour de vrai (TEST)
                    </button>
                </form>
            </div>';
      }
   } catch (Exception $e) {
        echo '<div class="alert alert-danger">
                <h5>❌ Erreur de test</h5>
                <p>'.htmlspecialchars($e->getMessage()).'</p>
            </div>';
   }
   echo '
        </div>
        <div class="card-footer">
            <a href="admin.php?op=maj&action=test_migration" class="btn btn-secondary">Nouveau test</a>
            <a href="admin.php?op=maj" class="btn btn-primary">Retour aux mises à jour</a>
        </div>
    </div>';
    include 'footer.php';
}

// Fonction de test dédiée pour mise à jour bd
function maj_test_data_migration() {
   global $hlpfile, $f_meta_nom, $f_titre, $adminimg;
   include 'header.php';
   GraphicAdmin($hlpfile);
   adminhead($f_meta_nom, $f_titre, $adminimg);
   echo '<div class="card">
        <div class="card-header"><h4>🧪 Test Migration Données</h4></div>
        <div class="card-body">';
   require_once 'lib/deployer/database_migrator.php';
   try {
      $backupFile = 'sql/backups/revolution_16_demo.sql';
      $newSchemaFile = "sql/revolution_16.sql";
      if (!file_exists($backupFile) || !file_exists($newSchemaFile))
         throw new Exception("Fichiers manquants: $backupFile ou $newSchemaFile");
      $migrator = new NPDSDatabaseMigrator($backupFile, $newSchemaFile);
      // TEST EXTRACTION FONCTIONS
      
      // AJOUTEZ CE DEBUG :
echo '<div class="alert alert-warning">';
echo '<h6>🔍 Test différences structurelles:</h6>';

$differences = $migrator->compareSchemas();
echo '<p>Tables modifiées: ' . (isset($differences['modified_columns']) ? count($differences['modified_columns']) : 0) . '</p>';

if (isset($differences['modified_columns']['fonctions'])) {
    echo '<p>✅ Table FONCTIONS modifiée - devrait utiliser DELETE+INSERT</p>';
    echo '<pre>Modifications: ' . htmlspecialchars(print_r($differences['modified_columns']['fonctions'], true)) . '</pre>';
} else {
    echo '<p>❌ Table FONCTIONS non modifiée - devrait utiliser UPDATE</p>';
}

echo '</div>';

// PUIS CONTINUEZ AVEC VOTRE CODE EXISTANT :
// TEST EXTRACTION FONCTIONS
      $newSqlContent = file_get_contents($newSchemaFile);
      $functionInserts = $migrator->extractInsertStatements($newSqlContent, 'fonctions');
      $functionData = $migrator->parseFunctionInserts($functionInserts);

      echo '
         <div class="alert alert-info">
            <h5>📊 Résultat Extraction Fonctions</h5>
            <p>INSERT trouvés: ' . count($functionInserts) . '</p>
            <p>Fonctions parsées: ' . count($functionData) . '</p>
         </div>';
         // Afficher les 3 premiers INSERT complets pour vérifier
         echo '<h6>INSERT fonctions complets (premiers 3):</h6>';
         $count = 0;
         foreach ($functionInserts as $insert) {
            if ($count++ >= 3) break;
            echo '<pre style="font-size: 0.8em; background: #f8f9fa; padding: 10px; border-radius: 5px;">' . 
            htmlspecialchars($insert) . '</pre>';
         }
         // TEST EXTRACTION METALANG
         $metalangInserts = $migrator->extractInsertStatements($newSqlContent, 'metalang');
         $metalangData = $migrator->parseMetalangInserts($metalangInserts);
         echo '<div class="alert alert-info mt-4">
            <h5>📊 Résultat Extraction Metalang</h5>
            <p>INSERT trouvés: ' . count($metalangInserts) . '</p>
            <p>Metalang parsés: ' . count($metalangData) . '</p>
        </div>';
        // Afficher quelques exemples metalang
         if (!empty($metalangInserts)) {
            echo '<h6>INSERT metalang complets (premiers 3):</h6>';
            $count = 0;
            foreach ($metalangInserts as $insert) {
               if ($count++ >= 3) break;
               echo '<pre style="font-size: 0.8em; background: #f8f9fa; padding: 10px; border-radius: 5px;">' . 
               htmlspecialchars($insert) . '</pre>';
            }
         }
      } catch (Exception $e) {
        echo '<div class="alert alert-danger">Erreur: ' . htmlspecialchars($e->getMessage()) . '</div>';
   }
// TEST GÉNÉRATION DES REQUÊTES
    echo '<div class="card mt-4">';
    echo '<div class="card-header"><h4>🚀 Test Génération Requêtes Migration</h4></div>';
    echo '<div class="card-body">';
    
    try {
        $dataQueries = $migrator->generateDataMigrationQueries($newSqlContent,$differences);
        echo '<div class="alert alert-success">';
        echo '<h5>✅ Requêtes de migration générées: ' . count($dataQueries) . '</h5>';
        echo '</div>';
        
        // Afficher les premières requêtes
        echo '<h6>Premières requêtes générées (10 premières):</h6>';
        echo '<div style="max-height: 400px; overflow-y: auto; background: #f8f9fa; padding: 15px; border-radius: 5px;">';
        $count = 0;
        foreach ($dataQueries as $query) {
            if ($count++ >= 10) break;
            echo '<div class="mb-2">';
            echo '<small class="text-muted">' . $count . '.</small> ';
            if (strpos($query, '--') === 0) {
                echo '<strong style="color: #6c757d;">' . htmlspecialchars($query) . '</strong>';
            } else {
                echo '<code style="background: transparent;">' . htmlspecialchars($query) . '</code>';
            }
            echo '</div>';
        }
        echo '</div>';
        
    } catch (Exception $e) {
        echo '<div class="alert alert-danger">Erreur: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
    
    echo '</div></div>';
   echo '</div></div>';
   include 'footer.php';
}
/////// Fonction de test dédiée /////////// <== to delete


// Le Routeur "On the road again !"
$action = $_POST['action'] ?? $_GET['action'] ?? 'main';
switch ($action) {
   case 'preupdate': maj_preupdate(); break;
   case 'update': maj_update(); break;
   case 'success': maj_success(); break;
   case 'migrate_db': maj_migrate_db(); break;
   case 'execute_migration': maj_execute_migration(); break;   
   case 'test_data_migration': maj_test_data_migration(); break;
//   case 'database_migration': maj_database_migration(); break;// <== to delete
//   case 'analyze_migration': maj_analyze_migration(); break;// <== to delete
//   case 'test_migration': maj_test_migration(); break;// <== to delete
   default: maj_main(); break;
}
?>
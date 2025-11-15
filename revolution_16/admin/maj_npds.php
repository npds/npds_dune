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

// ==> TEMPORAIRE - Pour debug à commenter
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
// <== TEMPORAIRE - Pour debug à commenter

#autodoc getlatestRelease() : retourne la dernière version contenue dans le fichier versus.txt de github sous forme v.16.8 ou si non disponible 16.8.1
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
function backupCurrentSQL() {
   global $Version_Num;
   $backupDir = 'sql/backups/';
   if (!is_dir($backupDir))
      mkdir($backupDir, 0755, true);
   $currentSqlFile = "sql/revolution_16.sql";
   $backupFile = $backupDir . $Version_Num . '_' . time() . '.sql';
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
   $backupResult = backupCurrentSQL();
   if ($backupResult['success'])
      file_put_contents('sql/backups/last_backup.txt', $backupResult['file']);
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

   $oldVersion = $_GET['old_version'] ?? $Version_Num ;
   $lockFile = 'sql/backups/last_backup.txt';
   $backupFile = '';
   if (file_exists($lockFile)) {
      $backupFile = file_get_contents($lockFile);
      unlink($lockFile);
      if (!$backupFile || !file_exists($backupFile))
         $backupFile = '';
    }
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
                  <button type="submit" class="btn btn-success" onclick="return confirm(\'Migrer la Base de Données?\')">
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
   global $hlpfile, $f_meta_nom, $f_titre, $adminimg, $NPDS_Prefix;
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
      $migrator = new NPDSDatabaseMigrator($backupFile, $newSchemaFile, $NPDS_Prefix);
      // 1. ANALYSER
      $differences = $migrator->compareSchemas();
      // SI DIFFÉRENCES AFFICHER
      if (!empty($differences['modified_columns'])) {
         echo '
            <div class="alert alert-light my-3">
               <h5>🔍 '.adm_translate('Analyse des différences structurelles').'</h5>';
         foreach ($differences['modified_columns'] as $tableName => $columns) {
            echo '
                  <h6>'.adm_translate('Table').': <code>' . $tableName . '</code></h6>
                  <ul>';
            foreach ($columns as $columnName => $definitions) {
               echo '
                     <li><strong>' . $columnName . '</strong>: 
                        <br />'.adm_translate('Ancien').' : <code>' . htmlspecialchars($definitions['old']) . '</code>
                        <br />'.adm_translate('Nouveau').' : <code>' . htmlspecialchars($definitions['new']) . '</code>
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
         echo '
                  <div class="mb-0" style="white-space:nowrap">
                     <small class="text-dark">' . ($i + 1) . '.</small>
                     <code class="small">' . htmlspecialchars($query) . '</code>
                  </div>';
         $i++;
      }
      $encodedQueries = base64_encode(json_encode($allQueries));
      echo '
               </div>
               <div class="my-3">
                  <small><i>'.adm_translate('Ces requêtes peuvent modifier la structure de la base de données et le contenu total ou partiel de 2 tables fonctions et metalang').'</i></small>
               </div>
               <form action="admin.php" method="post">
                  <input type="hidden" name="op" value="maj" />
                  <input type="hidden" name="action" value="execute_migration" />
                  <input type="hidden" name="queries" value="' . htmlspecialchars($encodedQueries) . '" />
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
      $encodedQueries = $_POST['queries'] ?? '';
      if (empty($encodedQueries))
         throw new Exception('Aucune donnée de requêtes reçue');
      $queriesJson = base64_decode($encodedQueries);
      if ($queriesJson === false)
         throw new Exception('Erreur de décodage base64 des requêtes');
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
      $migrator = new NPDSDatabaseMigrator('','');
      $results = $migrator->executeMigration($queries);
      if (empty($results['errors'])) {
         $configFile = 'config.php';
         $content = file_get_contents($configFile);
         $content = preg_replace('#^\$Version_Num\s*=\s*"[^"]*";#m','$Version_Num = "' . $newVersion . '";',$content);
         file_put_contents($configFile, $content);
         // Mettre à jour la date de modification (approche tableau pour la structure fixe)
         $lines = file($configFile);
         if (isset($lines[13])) {
            $lines[13] = '# modifié le : ' . date('d-M-Y H:i:s') . ';' . " \n";
            file_put_contents($configFile, implode('', $lines));
         }
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
      } else {
         echo '
            <div class="alert alert-danger">
            <h4>❌ '.adm_translate('Migration base de données interrompue').'</h4>
            <p>'.adm_translate('La migration de la base de données a échoué').'.</p>
            <h5>'.adm_translate('Erreurs rencontrées').' : ' . count($results['errors']) . '</h5>';
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
               <li>'.adm_translate('Contacter le support').'</li>
               <li>'.adm_translate('Exécuter manuellement les requêtes en erreur via phpMyAdmin').'</li>
               <li><a class="alert-link" href="admin.php?op=maj&action=migrate_db&old_version=' . urlencode($oldVersion) . '&new_version=' . urlencode($newVersion) . '&backup_file='.urlencode($backupFile).'">'.adm_translate('Relancer la migration').'</a></li>
            </ol>
            <h4 class="mb-3">❌ '.adm_translate('Mise à jour du portail non terminée').'</h4>';
         echo '
      </div>';
      }
   } catch (Exception $e) {
      echo '
      <div class="alert alert-danger">
         <h5>❌ '.adm_translate("Erreur lors de l'exécution").'</h5>
         <p>' . htmlspecialchars($e->getMessage()) . '</p>
      </div>';
   }
   echo '
      </div>
   </div>';
   include 'footer.php';
}

// Le Routeur "On the road again !"
$action = $_POST['action'] ?? $_GET['action'] ?? 'main';
switch ($action) {
   case 'preupdate': maj_preupdate(); break;
   case 'update': maj_update(); break;
   case 'success': maj_success(); break;
   case 'migrate_db': maj_migrate_db(); break;
   case 'execute_migration': maj_execute_migration(); break;   
   case 'test_data_migration': maj_test_data_migration(); break;
   default: maj_main(); break;
}
?>
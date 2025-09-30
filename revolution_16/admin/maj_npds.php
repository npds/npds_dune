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
/* mise à jour utilisant npds_deployer                                  */
/************************************************************************/
if (!function_exists('admindroits'))
   include 'die.php';
$f_meta_nom = 'maj_npds';
$f_titre = 'Mise à jour';
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
global $language, $nuke_url, $Version_Num;
$hlpfile = 'manuels/'.$language.'/meta_lang.html'; // à écrire ...

function getlatestRelease() {
   $getversustxt = @file_get_contents('https://raw.githubusercontent.com/npds/npds_dune/master/versus.txt');
   if (!$getversustxt)
      return '16.8.1'; // Fallback si GitHub inaccessible
   $getlineversustxt = explode("\n", $getversustxt);
   array_pop($getlineversustxt);
   $versus_info = explode('|', $getlineversustxt[0]);
   return isset($versus_info[2]) ? $versus_info[2] : '16.8.1';
}

function getUpdateOptions($currentVersion) {
   $latestStable = getlatestRelease();
   $currentClean = str_replace('v.', '', $currentVersion);
   $latestClean = str_replace('v.', '', $latestStable);
   $options = [];
   // Si version actuelle >= dernière stable → proposer seulement master
   if (version_compare($currentClean, $latestClean, '>='))
      $options['master'] = 'Version développement (master) - Mise à jour vers la dernière version de développement';
   // Si version actuelle < dernière stable → proposer stable ET master
   else {
      $options[$latestStable] = 'Version stable ' . $latestStable . ' - Mise à jour recommandée';
      $options['master'] = 'Version développement (master) - ⚠️ Non recommandé en production';
   }
   return $options;
}

function getUpdateLog() {
   $logFile = 'slogs/install.log';
   if (file_exists($logFile)) {
      $logContent = file_get_contents($logFile);
      $lines = explode("\n", $logContent);
      // Retourner les 20 dernières lignes
      return array_slice($lines, -20);
   }
   return [];
}

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
   <h3 class="mb-3">🔧 Interface de mise à jour</h3>
   <div class="alert alert-success">
      <h4>✅ Déployeur disponible</h4>
      <p>Le système de mise à jour est opérationnel.</p>
   </div>
   <div class="row">
       <div class="col-md-6">
           <div class="alert alert-info">
               <h4>Version en service</h4>
               <p class="display-6">' . $currentVersion . '</p>
           </div>
       </div>
       <div class="col-md-6">
           <div class="alert alert-success">
               <h4>Dernière version</h4>
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
      <h4>📦 Mise à jour disponible</h4>
      <p>Une nouvelle version stable de NPDS est disponible.</p>
   </div>';
   } 
   else if (version_compare($currentClean, $latestClean, '>')) {
      echo '
   <div class="alert alert-info">
      <h4>🔬 Version avancée</h4>
      <p>Vous utilisez une version plus récente que la dernière version stable.</p>
   </div>';
   }
   else {
      echo '
   <div class="alert alert-success">
      <h4>✅ À jour</h4>
      <p>Votre installation NPDS est à jour avec la dernière version stable.</p>
   </div>';
   }
   // Formulaire de mise à jour
   echo '
   <div class="card text-bg-light mt-4">
      <div class="card-body">
      <h4>🚀 Options de mise à jour</h4>
      <form action="admin.php" method="get">
          <input type="hidden" name="op" value="maj" />
          <input type="hidden" name="action" value="preupdate" />
          <div class="mb-3">
              <label for="version" class="form-label">Choisir la version à installer :</label>
              <select name="version" id="version" class="form-select">';
   foreach ($updateOptions as $value => $label) {
      $selected = ($value === $latestStable) ? 'selected="selected"' : '';
      echo '
                  <option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
   }
   echo '
              </select>
          </div>
          <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" id="backup" name="backup" value="1" checked="checked" />
              <label class="form-check-label" for="backup">
                  📦 Créer une sauvegarde automatique
              </label>
          </div>
          <button type="submit" class="btn btn-success">
              🚀 Préparer la mise à jour
          </button>
      </form>
      </div>
   </div>';
   include 'footer.php';
}

function maj_preupdate() {
   global $hlpfile, $f_meta_nom, $f_titre, $adminimg;
   include 'header.php';
   GraphicAdmin($hlpfile);
   adminhead($f_meta_nom, $f_titre, $adminimg);
   $version = $_GET['version'] ?? getlatestRelease();
   $backup = isset($_GET['backup']) ? 'true' : 'false';
   echo '
   <hr />
   <h3 class="mb-3">Préparation de la mise à jour</h3>
   <div class="alert alert-info">
      <h4>📋 Vérifications préalables</h4>
      <p>Avant de lancer la mise à jour, assurez-vous que :</p>
      <ul>
         <li>✅ Une sauvegarde complète du site a été effectuée</li>
         <li>✅ La base de données est sauvegardée</li>
         <li>✅ Aucune modification importante n\'est en cours</li>
      </ul>
   </div>
   <div class="alert alert-warning">
      <h4>⚠️ Paramètres de mise à jour</h4>
      <p><strong>Version cible :</strong> ' . htmlspecialchars($version) . '</p>
      <p><strong>Sauvegarde automatique :</strong> ' . ($backup === 'true' ? '✅ Activée' : '❌ Désactivée') . '</p>
   </div>
   <div class="mt-4">
      <a href="admin.php?op=maj&action=update&version=' . urlencode($version) . '&backup=' . $backup . '&path=' . urlencode(realpath(__DIR__ . '/..')) . '" class="btn btn-success" onclick="return confirm(\'⚠️ Lancer la mise à jour vers ' . htmlspecialchars($version) . ' ?\')">
         🚀 Lancer la mise à jour
      </a>
      <a href="admin.php?op=maj" class="btn btn-secondary">Retour</a>
   </div>';
   include 'footer.php';
}

function maj_update() {
   $version = $_GET['version'] ?? getlatestRelease();
   $targetPath = $_GET['path'] ?? realpath(__DIR__ . '/..');
   $deployerUrl = "/lib/deployer/npds_deployer.php?op=deploy&version=$version&confirm=yes&path=" . 
                   urlencode($targetPath) . "&return_url=" . 
                   urlencode("admin.php?op=maj");
   header("Location: $deployerUrl");
   exit;
}

function maj_success() {
   global $Version_Num, $hlpfile, $f_meta_nom, $f_titre, $adminimg;
   include 'header.php';
   GraphicAdmin($hlpfile);
   adminhead($f_meta_nom, $f_titre, $adminimg);
   $newVersion = $_GET['version'] ?? '16.8.0';

   echo '
    <h3 class="mb-3">Mise à jour terminée</h3>
    <div class="card">
        <div class="card-body">
            <div class="alert alert-success">
                <h4>✅ Mise à jour réussie</h4>
                <p>La mise à jour de NPDS a été effectuée avec succès.</p>
                <p><strong>Ancienne version :</strong> ' . $Version_Num . ' ==> <strong>Nouvelle version :</strong> ' . htmlspecialchars($newVersion) . '</p>
            </div>
            <div class="mt-4">
                <h5>📋 Prochaines étapes recommandées :</h5>
                <ol>
                    <li>Vérifier le fonctionnement général du site</li>
                    <li>Tester les fonctionnalités principales (articles, forums, membres)</li>
                    <li>Vérifier la compatibilité des modules et thèmes</li>
                    <li>Consulter les logs d\'installation pour détecter d\'éventuels problèmes</li>
                </ol>
            </div>
            <div class="mt-4">
                <h5>📊 Logs de mise à jour :</h5>
                <div style="height: 150px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; background: #f8f9fa;">';
    
   $logLines = getUpdateLog();
   if (!empty($logLines))
      foreach ($logLines as $line) {
         echo htmlspecialchars($line) . '<br />';
      }
   else 
      echo '<em>Aucun log disponible</em>';
   echo '
                </div>
            </div>
        </div>
    </div>';
   include 'footer.php';
}

// Routeur du module
$action = $_GET['action'] ?? 'main';
switch ($action) {
   case 'preupdate': maj_preupdate(); break;
   case 'update': maj_update(); break;
   case 'success': maj_success(); break;
   default: maj_main(); break;
}
?>
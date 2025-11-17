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
/* npds_deployer.php  v.1.1                                             */
/* jpb & DeepSeek 2025                                                  */
/************************************************************************/
if (ob_get_level() > 0) ob_end_clean();
header('X-LiteSpeed-No-Buffering: 1');
header('X-LiteSpeed-Cache-Control: no-cache, no-store');
header('X-Accel-Buffering: no');
ini_set('zlib.output_compression', '0');
ini_set('output_buffering', '0');
date_default_timezone_set('Europe/Paris');

// ==================== VERROU SIMPLE DANS LE DOSSIER COURANT ====================
$globalLockFile = __DIR__ . '/global_deploy.lock';
$apiLockFile = __DIR__ . '/api_deploy.lock';
$lockTimeout = 420;
$isApiCall = isset($_GET['api']) && $_GET['api'] === 'deploy';
$isRealDeployment = $isApiCall || (isset($_GET['confirm']) && $_GET['confirm'] === 'yes');

// ⭐ VERROU GLOBAL : SEULEMENT POUR L'API (pas pour l'interface Ajax)
if ($isApiCall) {
   error_log("🔍 Lock file: " . $globalLockFile);
   if (file_exists($globalLockFile)) {
      $lockContent = @file_get_contents($globalLockFile);
      $lockData = $lockContent ? json_decode($lockContent, true) : null;
      if ($lockData && isset($lockData['timestamp'])) {
         $elapsed = time() - $lockData['timestamp'];
         $processId = $lockData['process_id'] ?? 'inconnu';
         if ($elapsed > $lockTimeout) {
            error_log("🚨 DÉPLOYEUR BLOQUÉ - Processus $processId en cours depuis $elapsed secondes");
            error_log("🚨 URL bloquée: " . ($_SERVER['REQUEST_URI'] ?? 'inconnu'));
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => "🚨 Un déploiement est déjà en cours (débuté il y a $elapsed secondes). Veuillez patienter."
            ]);
            exit;
         }
      }
      @unlink($globalLockFile);
   }
   // ⭐⭐ CRÉATION AVEC PLUS D'INFORMATIONS
   $lockData = [
      'timestamp' => time(),
      'process_id' => getmypid() ?: 'unknown',
      'url' => $_SERVER['REQUEST_URI'] ?? 'unknown',
      'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
   ];
   if (!@file_put_contents($globalLockFile, json_encode($lockData), LOCK_EX)) {
      error_log("🚨 IMPOSSIBLE DE CRÉER LE VERROU DANS " . __DIR__);
      header('Content-Type: application/json');
      echo json_encode([
          'success' => false, 
          'message' => "🚨 Erreur de permissions - vérifiez les droits en écriture"
      ]);
      exit;
   }
   error_log("✅ Verrou global créé pour API: " . $globalLockFile);
}

// error_log("🧨 DÉPLOYEUR DÉMARRÉ - " . date('H:i:s') . " - " . $_SERVER['REQUEST_URI']);
$requestUri = $_SERVER['REQUEST_URI'] ?? 'unknown';
$queryString = $_SERVER['QUERY_STRING'] ?? '';
// Analyse de la requête
$isPollingRequest = strpos($requestUri, 'api=logs') !== false;
$isDeploymentApi = isset($_GET['api']) && $_GET['api'] === 'deploy';
$isMainInterface = empty($queryString) || strpos($queryString, 'op=') !== false;
$isCleanOperation = isset($_GET['op']) && $_GET['op'] === 'clean';
$isDeployOperation = isset($_GET['op']) && $_GET['op'] === 'deploy';
if ($isPollingRequest) {
   // Silence pour le polling - trop bruyant
} elseif ($isDeploymentApi)
   error_log("🚀 DÉPLOIEMENT API - " . date('H:i:s') . " - Version: " . ($_GET['version'] ?? 'unknown'));
elseif ($isDeployOperation && isset($_GET['confirm']) && $_GET['confirm'] === 'yes')
   error_log("🟡 CONFIRMATION DÉPLOIEMENT - " . date('H:i:s') . " - " . ($_GET['version'] ?? 'unknown'));
elseif ($isDeployOperation)
   error_log("📋 PRÉPARATION DÉPLOIEMENT - " . date('H:i:s') . " - " . ($_GET['version'] ?? 'unknown'));
elseif ($isCleanOperation)
   error_log("🧹 NETTOYAGE - " . date('H:i:s'));
elseif ($isMainInterface)
   error_log("🌐 INTERFACE PRINCIPALE - " . date('H:i:s'));
else
   error_log("🔧 ACTION DÉPLOYEUR - " . date('H:i:s') . " - " . $requestUri);

$headers_already_sent = headers_sent();

// ==================== GESTION DU BLOCAGE ====================

function shouldBlockAccess() {
   // ⭐⭐ TOUJOURS AUTORISER L'API LOGS - CRITIQUE
   if (isset($_GET['api']) && $_GET['api'] === 'logs')
      return false;
   $rootDir = $_SERVER['DOCUMENT_ROOT'];
   $installFiles = ['config.php', 'IZ-Xinstall.ok', 'mainfile.php', 'grab_globals.php'];
   // Vérifier si NPDS est installé dans la racine
   $npdsInstalled = false;
   foreach ($installFiles as $file) {
      if (file_exists($rootDir . '/' . $file)) {
         $npdsInstalled = true;
         break;
      }
   }
   // Détecter le contexte
   $isStandalone = (strpos(__DIR__, 'lib/deployer') === false);

   // ⭐⭐ CORRECTION : Détecter si la cible est la racine
   $targetDir = $_GET['path'] ?? '.';
   $isRootTarget = ($targetDir === '.' || $targetDir === './' || getAbsoluteTargetPath($targetDir) === $rootDir);
   // ⭐⭐ DEBUG : Log pour comprendre ce qui se passe
   error_log("🔍 DEBUG shouldBlockAccess:");
   error_log("  - return_url: " . ($_GET['return_url'] ?? 'NON'));
   error_log("  - cookie admin: " . (isset($_COOKIE['admin']) ? 'OUI' : 'NON'));
   error_log("  - NPDS installé: " . ($npdsInstalled ? 'OUI' : 'NON'));
   error_log("  - isRootTarget: " . ($isRootTarget ? 'OUI' : 'NON'));
   error_log("  - isStandalone: " . ($isStandalone ? 'OUI' : 'NON'));
   error_log("  - targetDir: " . $targetDir);
   error_log("  - rootDir: " . $rootDir);
   // 1. Mise à jour depuis l'admin (return_url contient admin.php) → TOUJOURS AUTORISÉ
   if (isset($_GET['return_url']) && strpos($_GET['return_url'], 'admin.php') !== false && isset($_COOKIE['admin'])) {
      error_log("✅ Condition 1 PASSÉE: Mise à jour admin autorisée");
      return false;
   }
   // 2. API de déploiement avec cookie admin → AUTORISÉ (même sans return_url)
   // C'est le cas des appels API légitimes depuis l'interface admin
   if (isset($_GET['api']) && $_GET['api'] === 'deploy' && isset($_COOKIE['admin'])) {
      error_log("✅ Condition 2 PASSÉE: API déploiement admin autorisée");
      return false;
   }
   // 3. Admin direct + NPDS installé en racine + cible racine → BLOQUÉ
   if (isset($_COOKIE['admin']) && $npdsInstalled && $isRootTarget) {
      error_log("🚨 Condition 3 PASSÉE: Admin direct bloqué");
      return true;
   }
   // 4. Admin avec cookie → AUTORISÉ (sauf cas bloqué ci-dessus)
   if (isset($_COOKIE['admin'])) {
      error_log("✅ Condition 4 PASSÉE: Admin autorisé");
      return false;
   }
   // 5. Standalone + NPDS installé + racine → BLOQUÉ (tentative de réinstallation directe)
   if ($isStandalone && $npdsInstalled && $isRootTarget) {
      error_log("🚨 Condition 5 PASSÉE: Standalone bloqué");
      return true;
   }
   // 6. Non-admin avec NPDS installé → BLOQUÉ   if ($npdsInstalled)
   if ($npdsInstalled) {
      error_log("🚨 Condition 6 PASSÉE: Non-admin avec NPDS bloqué");
      return true;
   }
   // 7. Non-admin sans NPDS installé → AUTORISER
   error_log("✅ Condition 7 PASSÉE: Nouvelle installation autorisée");
   return false;
}

if (shouldBlockAccess()) {
   if (!$headers_already_sent)
      header('HTTP/1.0 403 Forbidden');
   die('
   <!DOCTYPE html>
   <html>
      <head>
         <title>🚫 Accès au déployeur refusé</title>
         <style>body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }</style>
      </head>
      <body>
         <div><h1>🚫 Accès au déployeur refusé</h1><p>Vous devez être administrateur ! <br /><strong>OU</strong><br /> NPDS ne doit pas être déjà installé !</p></div>
      </body>
   </html>');
}

function getAbsoluteTargetPath($relativePath) {
   if ($relativePath === '.' || $relativePath === './')
      return $_SERVER['DOCUMENT_ROOT'];
   else if ($relativePath[0] !== '/')
      return $_SERVER['DOCUMENT_ROOT'] . '/' . $relativePath;
   else
      return $relativePath;
}

// ==================== TRADUCTIONS ===========================
$translations = [
   'fr' => [
      'access_url' => 'URL d\'accès',
      'advanced_options' => 'Options avancées',
      'already_in_progress' => 'Déploiement déjà en cours depuis',
      'already_installed_explanation' => 'Le déployeur ne peut être utilisé que pour une nouvelle installation.',
      'already_installed_message' => 'NPDS est déjà installé sur ce site.',
      'already_installed_reinstall' => 'Si vous souhaitez réinstaller, supprimez d\'abord le fichier',
      'already_installed_title' => '🚫 NPDS Déjà Installé',
      'backup_created' => 'Sauvegarde créée',
      'backup' => 'Sauvegarde',
      'cancel' => 'Annuler',
      'clean_confirm' => 'Confirmez le nettoyage avec &confirm=yes',
      'clean_done' => 'Les fichiers temporaires ont été supprimés.',
      'clean_finish' => 'Nettoyage terminé',
      'clean_message' => 'Cette action va supprimer tous les fichiers temporaires du déploiement.',
      'clean_temp' => 'Nettoyer fichiers temporaires',
      'cleanup_error' => 'Erreur de nettoyage',
      'connection_lost' => 'Connexion client perdue',
      'copied' => 'Copie',
      'copy_complete' => 'Copie terminée',
      'copy_error' => 'Impossible de copier',
      'copy_finished' => 'Copie terminée',
      'copy_started' => 'Début de la copie',
      'copying_files' => 'Début de la copie des fichiers',
      'dangerous_path' => 'Chemin système dangereux détecté.',
      'deploy_master_dev' => 'Déployer MASTER dans /npds_dev',
      'deploy_master_root' => 'Déployer MASTER à la racine',
      'deploy_succes_newinst' => 'Nouvelle installation déployée avec succès',
      'deploy_succes_update' => 'Mise à jour déployée avec succès',
      'deploy_v163' => 'Déployer v.16.3 dans /npds_163',
      'deploy_v164_root' => 'Déployer v.16.4 à la racine',
      'deploy_v164_stable' => 'Déployer v.16.4 dans /npds_stable',
      'deploy' => 'Déployer',
      'deployed_size' => 'déployés',
      'deploying' => 'Déploiement en cours',
      'deployment_complete' => 'Déploiement terminé avec succès',
      'deployment_failed' => 'DÉPLOIEMENT ÉCHOUÉ',
      'deployment_finished' => 'DÉPLOIEMENT TERMINÉ', 
      'deployment_in_progress' => 'Un déploiement est déjà en cours (débuté il y a',
      'deployment_started' => 'DÉPLOIEMENT DÉMARRÉ',
      'dev_version' => 'Version développement',
      'dev_warning' => 'La version master est une version de développement qui peut contenir des bugs, des fonctionnalités incomplètes ou être instable. Ne pas utiliser en production!',
      'development_version' => 'Version développement',
      'double_slash_not_allowed' => 'Les chemins avec "//" ne sont pas autorisés.',
      'download_success' => 'Téléchargement réussi',
      'downloading' => 'Téléchargement',
      'error' => 'Erreur',
      'example_parent' => 'dossier adjacent au site actuel',
      'example_root' => 'racine du site actuel',
      'example_subfolder' => 'dossier dans le site actuel',
      'external_urls_not_allowed' => 'Les URLs externes ne sont pas autorisées.',
      'extracting' => 'Début de l\'extraction',
      'extraction_complete' => 'Extraction terminée avec succès',
      'extraction_error' => 'Erreur d\'extraction',
      'extraction_finished' => 'Extraction terminée',
      'extraction_progress' => 'Extraction de l\'archive (2-3 minutes)',
      'extraction_success' => 'Extraction réussie',
      'failed_download' => 'Échec du téléchargement',
      'file_download_finished' => 'Téléchargement terminé',
      'file_download_start' => 'Début du téléchargement',
      'files' => 'fichiers',
      'finish_installation' => 'Pour terminer l\'installation',
      'folders' => 'dossiers',
      'go_admin' => 'Accéder à l\'administration',
      'go_back' => 'Retour',
      'go_install' => 'Poursuivre l\'installation',
      'initializing' => 'Initialisation du téléchargement',
      'invalid_zip' => 'Le contenu n\'est pas une archive ZIP valide',
      'items_installed' => 'éléments installés',
      'launch_installation' => 'Lancer l\'installation de NPDS',
      'let_emptyroot' => 'laisser vide pour racine',
      'lock_error' => 'Impossible de créer le verrou de sécurité',
      'lock_expired' => 'Verrou expiré et supprimé',
      'maintenance' => 'Maintenance',
      'master_warning' => 'Master : Version de développement, peut être instable - Ne pas utiliser en production!',
      'max_exec_time' => 'Temps maxi d\'exécution',
      'memory_limit' => 'Mémoire limite',
      'network_paths_not_allowed' => 'Les chemins réseau ne sont pas autorisés.',
      'new_install' => 'Nouvelle installation', 
      'no_files_to_copy' => 'Aucun fichier à copier dans',
      'no_folder_in_archive' => 'Aucun dossier trouvé dans l\'archive',
      'overwrite_warning' => 'Le déploiement écrase les fichiers existants!',
      'path' => 'Chemin',
      'processing_result' => 'Traitement terminé, analyse du résultat',
      'protocols_not_allowed' => 'Les protocoles spéciaux ne sont pas autorisés.',
      'secondes' => 'secondes',
      'security_warning' => 'Sécurité : Ajoutez &confirm=yes pour lancer le déploiement',
      'server' => 'Serveur',
      'stable_versions' => 'Versions stables',
      'start_extraction' => 'Début extraction',
      'success' => 'Déploiement réussi',
      'system_info' => 'Info système',
      'target_dir_error' => 'Impossible de créer le répertoire cible',
      'target_permission_error' => 'Répertoire cible non accessible en écriture',
      'temp_dir_error' => 'Impossible de créer le répertoire temporaire',
      'too_many_parent_dirs' => 'Trop de remontées de répertoire pour la sécurité.',
      'type' => 'Type',
      'update_interface' => 'Interface de mise à jour',
      'update_to_version' => 'Mettre à jour vers',
      'update' => 'Mise à jour',
      'valid_examples' => 'Exemples valides',
      'version' => 'Version',
      'warning' => 'Attention',
      'welcome' => 'Déploiement',
      'write_error' => 'Impossible d\'écrire le fichier',
      'zip_open_error' => 'Impossible d\'ouvrir l\'archive ZIP',
   ],
   'en' => [
      'access_url' => 'Access URL',
      'advanced_options' => 'Advanced options',
      'already_in_progress' => 'Deployment already in progress for',
      'already_installed_explanation' => 'The deployer can only be used for a new installation.',
      'already_installed_message' => 'NPDS is already installed on this site.',
      'already_installed_reinstall' => 'If you want to reinstall, first delete the file',
      'already_installed_title' => '🚫 NPDS Already Installed',
      'backup_created' => 'Backup created',
      'backup' => 'Backup',
      'cancel' => 'Cancel',
      'clean_confirm' => 'Confirm cleanup with &confirm=yes',
      'clean_done' => 'Temporary files have been deleted.',
      'clean_finish' => 'Cleanup finished',
      'clean_message' => 'This action will delete all temporary deployment files.',
      'clean_temp' => 'Clean temporary files',
      'cleanup_error' => 'Cleanup error',
      'connection_lost' => 'Client connection lost',
      'copied' => 'copied',
      'copy_complete' => 'Copy complete',
      'copy_error' => 'Cannot copy',
      'copy_finished' => 'Copy finished',
      'copy_started' => 'Copy started',
      'copying_files' => 'Starting file copy',
      'dangerous_path' => 'Dangerous system path detected.',
      'deploy_master_dev' => 'Deploy MASTER in /npds_dev',
      'deploy_master_root' => 'Deploy MASTER at root',
      'deploy_succes_newinst' => 'New installation deployed successfully',
      'deploy_succes_update' => 'Update deployed successfully',
      'deploy_v163' => 'Deploy v.16.3 in /npds_163',
      'deploy_v164_root' => 'Deploy v.16.4 at root',
      'deploy_v164_stable' => 'Deploy v.16.4 in /npds_stable',
      'deploy' => 'Deploy',
      'deployed_size' => 'deployed',
      'deploying' => 'Deployment in progress',
      'deployment_complete' => 'Deployment completed successfully',
      'deployment_failed' => 'DEPLOYMENT FAILED', 
      'deployment_finished' => 'DEPLOYMENT FINISHED',
      'deployment_in_progress' => 'A deployment is already in progress (started',
      'deployment_started' => 'DEPLOYMENT STARTED',
      'dev_version' => 'Development version',
      'dev_warning' => 'The master version is a development version that may contain bugs, incomplete features, or be unstable. Do not use in production!',
      'development_version' => 'Development version',
      'double_slash_not_allowed' => 'Paths with "//" are not allowed.',
      'download_success' => 'Download successful',
      'downloading' => 'Downloading',
      'error' => 'Error',
      'example_parent' => 'folder adjacent to current site',
      'example_root' => 'root of current site',
      'example_subfolder' => 'subfolder in current site',
      'external_urls_not_allowed' => 'External URLs are not allowed.',
      'extracting' => 'Starting extraction',
      'extraction_complete' => 'Extraction completed successfully',
      'extraction_error' => 'Extraction error',
      'extraction_finished' => 'Extraction finished',
      'extraction_progress' => 'Extracting archive (2-3 minutes)',
      'extraction_success' => 'Extraction successful',
      'failed_download' => 'Download failed',
      'file_download_finished' => 'Download finished',
      'file_download_start' => 'Start download',
      'files' => 'files',
      'finish_installation' => 'To finish installation',
      'folders' => 'folders',
      'go_admin' => 'Access administration', 
      'go_back' => 'Back',
      'go_install' => 'Continue installation',
      'initializing' => 'Initializing download',
      'invalid_zip' => 'Content is not a valid ZIP archive',
      'items_installed' => 'items installed',
      'launch_installation' => 'Launch NPDS installation',
      'let_emptyroot' => 'leave empty for root',
      'lock_error' => 'Cannot create security lock',
      'lock_expired' => 'Lock expired and removed',
      'maintenance' => 'Maintenance',
      'master_warning' => 'Master: Development version, may be unstable - Do not use in production!',
      'max_exec_time' => 'Max execution time',
      'memory_limit' => 'Memory limit', 
      'network_paths_not_allowed' => 'Network paths are not allowed.',
      'new_install' => 'New installation',
      'no_files_to_copy' => 'No files to copy in',
      'no_folder_in_archive' => 'No folder found in archive',
      'overwrite_warning' => 'Deployment overwrites existing files!',
      'path' => 'Path',
      'processing_result' => 'Processing complete, analyzing result',
      'protocols_not_allowed' => 'Special protocols are not allowed.',
      'secondes' => 'seconds',
      'security_warning' => 'Security: Add &confirm=yes to launch deployment',
      'stable_versions' => 'Stable versions',
      'start_extraction' => 'Start extraction',
      'success' => 'Deployment successful',
      'system_info' => 'System info',
      'target_dir_error' => 'Cannot create target directory',
      'target_permission_error' => 'Target directory not writable',
      'temp_dir_error' => 'Cannot create temporary directory',
      'too_many_parent_dirs' => 'Too many parent directory traversals for security.',
      'type' => 'Type',
      'update_interface' => 'Update interface',
      'update_to_version' => 'Update to version',
      'update' => 'Update',
      'valid_examples' => 'Valid examples',
      'version' => 'Version',
      'warning' => 'Warning',
      'welcome' => 'Deployment',
      'write_error' => 'Cannot write file',
      'zip_open_error' => 'Cannot open ZIP archive',
   ],
   'es' => [
      'access_url' => 'URL de acceso',
      'advanced_options' => 'Opciones avanzadas',
      'already_in_progress' => 'Despliegue ya en curso desde',
      'already_installed_explanation' => 'El implementador solo se puede usar para una nueva instalación.',
      'already_installed_message' => 'NPDS ya está instalado en este sitio.',
      'already_installed_reinstall' => 'Si desea reinstalar, primero elimine el archivo',
      'already_installed_title' => '🚫 NPDS Ya Instalado',
      'backup_created' => 'Copia de seguridad creada',
      'backup' => 'Copia de seguridad',
      'cancel' => 'Cancelar',
      'clean_confirm' => 'Confirme la limpieza con &confirm=yes',
      'clean_done' => 'Los archivos temporales han sido eliminados.',
      'clean_finish' => 'Limpieza terminada',
      'clean_message' => 'Esta acción eliminará todos los archivos temporales del despliegue.',
      'clean_temp' => 'Limpiar archivos temporales',
      'cleanup_error' => 'Error de limpieza',
      'connection_lost' => 'Conexión cliente perdida',
      'copied' => 'copiado',
      'copy_complete' => 'Copia completada',
      'copy_error' => 'No se puede copiar',
      'copy_finished' => 'Copia terminada',
      'copy_started' => 'Copia iniciada',
      'copying_files' => 'Iniciando copia de archivos',
      'dangerous_path' => 'Ruta del sistema peligrosa detectada.',
      'deploy_master_dev' => 'Implementar MASTER en /npds_dev',
      'deploy_master_root' => 'Implementar MASTER en raíz',
      'deploy_succes_newinst' => 'Nueva instalación desplegada con éxito',
      'deploy_succes_update' => 'Actualización desplegada con éxito',
      'deploy_v163' => 'Implementar v.16.3 en /npds_163',
      'deploy_v164_root' => 'Implementar v.16.4 en raíz',
      'deploy_v164_stable' => 'Implementar v.16.4 en /npds_stable',
      'deploy' => 'Implementar',
      'deployed_size' => 'implementado',
      'deploying' => 'Implementación en curso',
      'deployment_complete' => 'Implementación completada con éxito',
      'deployment_failed' => 'DESPLIEGUE FALLIDO',
      'deployment_finished' => 'DESPLIEGUE TERMINADO',
      'deployment_in_progress' => 'Ya hay una implementación en curso (iniciada hace',
      'deployment_started' => 'DESPLIEGUE INICIADO',
      'dev_version' => 'Versión de desarrollo',
      'dev_warning' => 'La versión master es una versión de desarrollo que puede contener errores, características incompletas o ser inestable. ¡No usar en producción!',
      'development_version' => 'Versión de desarrollo',
      'double_slash_not_allowed' => 'Las rutas con "//" no están permitidas.',
      'download_success' => 'Descarga exitosa',
      'downloading' => 'Descargando',
      'error' => 'Error',
      'example_parent' => 'carpeta adyacente al sitio actual',
      'example_root' => 'raíz del sitio actual',
      'example_subfolder' => 'subcarpeta en el sitio actual',
      'external_urls_not_allowed' => 'Las URLs externas no están permitidas.',
      'extracting' => 'Iniciando extracción',
      'extraction_complete' => 'Extracción completada con éxito',
      'extraction_error' => 'Error de extracción',
      'extraction_finished' => 'Extracción terminada',
      'extraction_progress' => 'Extrayendo archivo (2-3 minutos)',
      'extraction_success' => 'Extracción exitosa',
      'failed_download' => 'Descarga fallida',
      'file_download_finished' => 'Descarga terminada',
      'file_download_start' => 'Inicio descarga',
      'files' => 'archivos',
      'finish_installation' => 'Para finalizar la instalación',
      'folders' => 'carpetas',
      'go_admin' => 'Acceder a la administración',
      'go_back' => 'Volver', 
      'go_install' => 'Continuar instalación',
      'initializing' => 'Inicializando descarga',
      'invalid_zip' => 'El contenido no es un archivo ZIP válido',
      'items_installed' => 'elementos instalados',
      'launch_installation' => 'Iniciar instalación de NPDS',
      'let_emptyroot' => 'dejar vacío para raíz',
      'lock_error' => 'No se puede crear el bloqueo de seguridad',
      'lock_expired' => 'Bloqueo expirado eliminado',
      'maintenance' => 'Mantenimiento',
      'master_warning' => 'Master: Versión de desarrollo, puede ser inestable - ¡No usar en producción!',
      'max_exec_time' => 'Tiempo máximo de ejecución',
      'memory_limit' => 'Límite de memoria',
      'network_paths_not_allowed' => 'Las rutas de red no están permitidas.',
      'new_install' => 'Nueva instalación',
      'no_files_to_copy' => 'No hay archivos para copiar en',
      'no_folder_in_archive' => 'No se encontró carpeta en el archivo',
      'overwrite_warning' => '¡La implementación sobrescribe los archivos existentes!',
      'path' => 'Ruta',
      'processing_result' => 'Procesamiento completado, analizando resultado',
      'protocols_not_allowed' => 'Los protocolos especiales no están permitidos.',
      'secondes' => 'segundos',
      'security_warning' => 'Seguridad: Agregue &confirm=yes para iniciar la implementación',
      'server' => 'Servidor',
      'stable_versions' => 'Versiones estables',
      'start_extraction' => 'Inicio extracción',
      'success' => 'Implementación exitosa',
      'system_info' => 'Información del sistema',
      'target_dir_error' => 'No se puede crear el directorio de destino',
      'target_permission_error' => 'Directorio de destino sin permisos de escritura',
      'temp_dir_error' => 'No se puede crear el directorio temporal',
      'too_many_parent_dirs' => 'Demasiados directorios padre por seguridad.',
      'type' => 'Tipo',
      'update_interface' => 'Interfaz de actualización',
      'update_to_version' => 'Actualizar a versión',
      'update' => 'Actualización',
      'valid_examples' => 'Ejemplos válidos',
      'version' => 'Versión',
      'warning' => 'Advertencia',
      'welcome' => 'Implementación',
      'write_error' => 'No se puede escribir el archivo',
      'zip_open_error' => 'No se puede abrir el archivo ZIP',
   ],
   'de' => [
      'access_url' => 'Zugriffs-URL',
      'advanced_options' => 'Erweiterte Optionen',
      'already_in_progress' => 'Bereitstellung bereits im Gange seit',
      'already_installed_explanation' => 'Der Bereitsteller kann nur für eine neue Installation verwendet werden.',
      'already_installed_message' => 'NPDS ist bereits auf dieser Website installiert.',
      'already_installed_reinstall' => 'Wenn Sie neu installieren möchten, löschen Sie zuerst die Datei',
      'already_installed_title' => '🚫 NPDS Bereits Installiert',
      'backup_created' => 'Sicherung erstellt',
      'backup' => 'Sicherung',
      'cancel' => 'Abbrechen',
      'clean_confirm' => 'Bereinigung mit &confirm=yes bestätigen',
      'clean_done' => 'Die temporären Dateien wurden gelöscht.',
      'clean_finish' => 'Bereinigung abgeschlossen',
      'clean_message' => 'Diese Aktion löscht alle temporären Bereitstellungsdateien.',
      'clean_temp' => 'Temporäre Dateien bereinigen',
      'cleanup_error' => 'Bereinigungsfehler',
      'connection_lost' => 'Client-Verbindung verloren',
      'copied' => 'kopiert',
      'copy_complete' => 'Kopie abgeschlossen',
      'copy_error' => 'Kann nicht kopiert werden',
      'copy_finished' => 'Kopie beendet',
      'copy_started' => 'Kopie gestartet',
      'copying_files' => 'Beginne Dateikopie',
      'dangerous_path' => 'Gefährlicher Systempfad erkannt.',
      'deploy_master_dev' => 'Stelle MASTER in /npds_dev bereit',
      'deploy_master_root' => 'Stelle MASTER im Stammverzeichnis bereit',
      'deploy_succes_newinst' => 'Neue Installation erfolgreich bereitgestellt',
      'deploy_succes_update' => 'Update erfolgreich bereitgestellt',
      'deploy_v163' => 'Stelle v.16.3 in /npds_163 bereit',
      'deploy_v164_root' => 'Stelle v.16.4 im Stammverzeichnis bereit',
      'deploy_v164_stable' => 'Stelle v.16.4 in /npds_stable bereit',
      'deploy' => 'Bereitstellen',
      'deployed_size' => 'bereitgestellt',
      'deploying' => 'Bereitstellung läuft',
      'deployment_complete' => 'Bereitstellung erfolgreich abgeschlossen',
      'deployment_failed' => 'BEREITSTELLUNG FEHLGESCHLAGEN',
      'deployment_finished' => 'BEREITSTELLUNG BEENDET',
      'deployment_in_progress' => 'Eine Bereitstellung läuft bereits (gestartet vor',
      'deployment_started' => 'BEREITSTELLUNG GESTARTET',
      'dev_version' => 'Entwicklungsversion',
      'dev_warning' => 'Die Master-Version ist eine Entwicklungsversion, die Fehler, unvollständige Funktionen enthalten oder instabil sein kann. Nicht in der Produktion verwenden!',
      'development_version' => 'Entwicklungsversion',
      'double_slash_not_allowed' => 'Pfade mit "//" sind nicht erlaubt.',
      'download_success' => 'Download erfolgreich',
      'downloading' => 'Herunterladen',
      'error' => 'Fehler',
      'example_parent' => 'Benachbarter Ordner zur aktuellen Website',
      'example_root' => 'Wurzel der aktuellen Website',
      'example_subfolder' => 'Unterordner in aktueller Website',
      'external_urls_not_allowed' => 'Externe URLs sind nicht erlaubt.',
      'extracting' => 'Beginne Extraktion',
      'extraction_complete' => 'Extraktion erfolgreich abgeschlossen',
      'extraction_error' => 'Extraktionsfehler',
      'extraction_finished' => 'Extraktion beendet',
      'extraction_progress' => 'Extrahiere Archiv (2-3 Minuten)',
      'extraction_success' => 'Extraktion erfolgreich',
      'failed_download' => 'Download fehlgeschlagen',
      'file_download_finished' => 'Download beendet',
      'file_download_start' => 'Download starten',
      'files' => 'Dateien',
      'finish_installation' => 'Um die Installation abzuschließen',
      'folders' => 'Ordner',
      'go_admin' => 'Zur Administration',
      'go_back' => 'Zurück',
      'go_install' => 'Installation fortsetzen',
      'initializing' => 'Initialisiere Download',
      'invalid_zip' => 'Inhalt ist kein gültiges ZIP-Archiv',
      'items_installed' => 'Elemente installiert',
      'launch_installation' => 'NPDS-Installation starten',
      'let_emptyroot' => 'leer lassen für Stammverzeichnis',
      'lock_error' => 'Sicherheitssperre kann nicht erstellt werden',
      'lock_expired' => 'Sperre abgelaufen entfernt',
      'maintenance' => 'Wartung',
      'master_warning' => 'Master: Entwicklungsversion, kann instabil sein - Nicht in der Produktion verwenden!',
      'max_exec_time' => 'Maximale Ausführungszeit',
      'memory_limit' => 'Speicherlimit',
      'network_paths_not_allowed' => 'Netzwerkpfade sind nicht erlaubt.',
      'new_install' => 'Neue Installation',
      'no_files_to_copy' => 'Keine Dateien zum Kopieren in',
      'no_folder_in_archive' => 'Kein Ordner im Archiv gefunden',
      'overwrite_warning' => 'Bereitstellung überschreibt vorhandene Dateien!',
      'path' => 'Pfad',
      'processing_result' => 'Verarbeitung abgeschlossen, analysiere Ergebnis',
      'protocols_not_allowed' => 'Spezielle Protokolle sind nicht erlaubt.',
      'secondes' => 'Sekunden',
      'security_warning' => 'Sicherheit: Fügen Sie &confirm=yes hinzu, um die Bereitstellung zu starten',
      'server' => 'Server',
      'stable_versions' => 'Stabile Versionen',
      'start_extraction' => 'Extraktion starten',
      'success' => 'Bereitstellung erfolgreich',
      'system_info' => 'Systeminformationen',
      'target_dir_error' => 'Zielverzeichnis kann nicht erstellt werden',
      'target_permission_error' => 'Zielverzeichnis nicht beschreibbar',
      'temp_dir_error' => 'Temporäres Verzeichnis kann nicht erstellt werden',
      'too_many_parent_dirs' => 'Zu viele übergeordnete Verzeichnisse aus Sicherheitsgründen.',
      'type' => 'Typ',
      'update_interface' => 'Update-Schnittstelle',
      'update_to_version' => 'Aktualisieren auf Version',
      'update' => 'Aktualisierung',
      'valid_examples' => 'Gültige Beispiele',
      'version' => 'Version',
      'warning' => 'Warnung',
      'welcome' => 'Bereitstellung',
      'write_error' => 'Datei kann nicht geschrieben werden',
      'zip_open_error' => 'ZIP-Archiv kann nicht geöffnet werden',
   ],
   'zh' => [
      'access_url' => '访问网址',
      'advanced_options' => '高级选项',
      'already_in_progress' => '部署已在进行中，开始于',
      'already_installed_explanation' => '部署器只能用于新安装。',
      'already_installed_message' => 'NPDS 已在此站点上安装。',
      'already_installed_reinstall' => '如果您想重新安装，请先删除文件',
      'already_installed_title' => '🚫 NPDS 已安装',
      'backup_created' => '备份已创建',
      'backup' => '备份',
      'cancel' => '取消',
      'clean_confirm' => '使用 &confirm=yes 确认清理',
      'clean_done' => '临时文件已被删除。',
      'clean_finish' => '清理完成',
      'clean_message' => '此操作将删除所有临时部署文件。',
      'clean_temp' => '清理临时文件',
      'cleanup_error' => '清理错误',
      'connection_lost' => '客户端连接丢失',
      'copied' => '已复制',
      'copy_complete' => '复制完成',
      'copy_error' => '无法复制',
      'copy_finished' => '复制完成',
      'copy_started' => '复制开始',
      'copying_files' => '开始文件复制',
      'dangerous_path' => '检测到危险的系统路径。',
      'deploy_master_dev' => '在 /npds_dev 中部署 MASTER',
      'deploy_master_root' => '在根目录部署 MASTER',
      'deploy_succes_newinst' => '新安装部署成功',
      'deploy_succes_update' => '更新部署成功',
      'deploy_v163' => '在 /npds_163 中部署 v.16.3',
      'deploy_v164_root' => '在根目录部署 v.16.4',
      'deploy_v164_stable' => '在 /npds_stable 中部署 v.16.4',
      'deploy' => '部署',
      'deployed_size' => '已部署',
      'deploying' => '部署进行中',
      'deployment_complete' => '部署成功完成',
      'deployment_failed' => '部署失败',
      'deployment_finished' => '部署已完成',
      'deployment_in_progress' => '已有部署正在进行中（开始于',
      'deployment_started' => '部署已开始',
      'dev_version' => '开发版本',
      'dev_warning' => 'master版本是开发版本，可能包含错误、不完整的功能或不稳定。请勿在生产环境中使用！',
      'development_version' => '开发版本',
      'double_slash_not_allowed' => '不允许使用带有"//"的路径。',
      'download_success' => '下载成功',
      'downloading' => '下载中',
      'error' => '错误',
      'example_parent' => '当前站点相邻的文件夹',
      'example_root' => '当前站点的根目录',
      'example_subfolder' => '当前站点的子文件夹',
      'external_urls_not_allowed' => '不允许使用外部URL。',
      'extracting' => '开始解压',
      'extraction_complete' => '提取成功完成',
      'extraction_error' => '解压错误',
      'extraction_finished' => '提取完成',
      'extraction_progress' => '解压文件中（2-3分钟）',
      'extraction_success' => '提取成功',
      'failed_download' => '下载失败',
      'file_download_finished' => '下载完成',
      'file_download_start' => '开始下载',
      'files' => '文件',
      'finish_installation' => '完成安装',
      'folders' => '文件夹',
      'go_admin' => '进入管理',
      'go_back' => '返回',
      'go_install' => '继续安装',
      'initializing' => '初始化下载',
      'invalid_zip' => '内容不是有效的ZIP存档',
      'items_installed' => '个项目已安装',
      'launch_installation' => '启动NPDS安装',
      'let_emptyroot' => '留空为根目录',
      'lock_error' => '无法创建安全锁',
      'lock_expired' => '锁定已过期并删除',
      'maintenance' => '维护',
      'master_warning' => 'Master：开发版本，可能不稳定 - 请勿在生产环境中使用！',
      'max_exec_time' => '最大执行时间',
      'memory_limit' => '内存限制',
      'network_paths_not_allowed' => '不允许使用网络路径。',
      'new_install' => '新安装',
      'no_files_to_copy' => '没有文件可复制到',
      'no_folder_in_archive' => '在存档中未找到文件夹',
      'overwrite_warning' => '部署会覆盖现有文件！',
      'path' => '路径',
      'processing_result' => '处理完成，分析结果中',
      'protocols_not_allowed' => '不允许使用特殊协议。',
      'secondes' => '秒',
      'security_warning' => '安全：添加 &confirm=yes 以启动部署',
      'server' => '服务器',
      'stable_versions' => '稳定版本',
      'start_extraction' => '开始提取',
      'success' => '部署成功',
      'system_info' => '系统信息',
      'target_dir_error' => '无法创建目标目录',
      'target_permission_error' => '目标目录不可写',
      'temp_dir_error' => '无法创建临时目录',
      'too_many_parent_dirs' => '出于安全原因，父目录遍历过多。',
      'type' => '类型',
      'update_interface' => '更新界面',
      'update_to_version' => '更新到版本',
      'update' => '更新',
      'valid_examples' => '有效示例',
      'version' => '版本',
      'warning' => '警告',
      'welcome' => '部署',
      'write_error' => '无法写入文件',
      'zip_open_error' => '无法打开ZIP存档',
   ],
];
// Gestion de la langue
$lang = $_GET['lang'] ?? $_SESSION['npds_lang'] ?? 'fr';
if (!in_array($lang, ['fr', 'en', 'es', 'de', 'zh']))
   $lang = 'fr';
$_SESSION['npds_lang'] = $lang;

function t($key) {
   global $translations, $lang;
   return $translations[$lang][$key] ?? $translations['fr'][$key] ?? $key;
}

// ==================== VALIDATION DES CHEMINS ====================
function validateTargetPath($path) {
   $path = trim($path);
   // URLs externes
   if (preg_match('#^(https?|ftp)://#i', $path)) {
      error_log("🚨 TENTATIVE DÉPLOIEMENT EXTERNE: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown') . " - " . $path);
      return ['valid' => false, 'message' => t('external_urls_not_allowed')];
   }
   // Protocoles spéciaux
   if (preg_match('#^[a-z]+:#i', $path)) 
      return ['valid' => false, 'message' => t('protocols_not_allowed')];
   // Double slash
   if (strpos($path, '//') !== false) 
      return ['valid' => false, 'message' => t('double_slash_not_allowed')];
   // Chemins réseau
   if (preg_match('#^\\\\\\\\#', $path))
      return ['valid' => false, 'message' => t('network_paths_not_allowed')];
   // Trop de remontées
   if (substr_count($path, '..') > 2)
      return ['valid' => false, 'message' => t('too_many_parent_dirs')];
   return ['valid' => true, 'message' => ''];
}

// ==================== MODE API POUR DÉPLOIEMENT ====================
if (isset($_GET['api']) && $_GET['api'] === 'deploy') {
   header('Content-Type: application/json');
   // ⭐⭐ VERROU API IMMÉDIAT - AVANT TOUT ⭐⭐
   $apiLockFile = __DIR__ . '/api_deploy.lock';
   $lockTimeout = 600; // 10 minutes
   // Vérifier si un déploiement API est déjà en cours
   if (file_exists($apiLockFile)) {
      $lockContent = @file_get_contents($apiLockFile);
      $lockData = $lockContent ? json_decode($lockContent, true) : null;
      if ($lockData && isset($lockData['timestamp'])) {
         $elapsed = time() - $lockData['timestamp'];
         if ($elapsed < $lockTimeout) {
            // Déploiement déjà en cours
            header('Content-Type: application/json');
            echo json_encode([
              'success' => false,
              'message' => "🚨 Déploiement API déjà en cours (débuté il y a $elapsed secondes)"
            ]);
            exit;
         } else 
            @unlink($apiLockFile); // Verrou expiré, le supprimer
      }
   }
   // Créer un NOUVEAU verrou API
   $lockData = [
      'timestamp' => time(),
      'process_id' => getmypid() ?: 'unknown',
      'url' => $_SERVER['REQUEST_URI'] ?? 'unknown',
      'type' => 'api'
   ];

   if (!@file_put_contents($apiLockFile, json_encode($lockData), LOCK_EX)) {
      header('Content-Type: application/json');
      echo json_encode([
         'success' => false, 
         'message' => "❌ Impossible de créer le verrou API"
      ]);
      exit;
   }
   error_log("🔒 VERROU API CRÉÉ: $apiLockFile");
   // Mode API - traitement en arrière-plan
   // header('Content-Type: application/json');
   try {
      // Nettoyer tous les buffers
      while (ob_get_level() > 0) ob_end_clean();
      error_log("🎯 API DEPLOY CALLED");
      // Vérifier les paramètres requis
      if (!isset($_GET['version']))
         throw new Exception("Version manquante");
      if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'yes')
         throw new Exception("Confirmation manquante");
      $version = $_GET['version'];
      $targetDir = $_GET['path'] ?? '.';
      // ⭐⭐ UTILISER LE CHEMIN ABSOLU
      $absoluteTargetPath = getAbsoluteTargetPath($targetDir);
      error_log("🎯 API DEPLOY - Chemin absolu: $absoluteTargetPath");
      error_log("📋 Paramètres API: version=$version, path=$targetDir");
      // Démarrer immédiatement le déploiement
      $result = executeDeployment($version, $absoluteTargetPath);
      error_log("✅ API DEPLOY SUCCESS: " . $result['message']);
      echo json_encode($result);
      // Supprimer le verrou API
      @unlink($apiLockFile);
      error_log("🔓 VERROU API SUPPRIMÉ");
      exit;
   } catch (Exception $e) {
      // Supprimer le verrou API en cas d'erreur
      @unlink($apiLockFile);
      error_log("🔓 VERROU API SUPPRIMÉ (erreur)");
      error_log("💥 API ERROR: " . $e->getMessage());
      echo json_encode([
         'success' => false,
         'message' => 'Erreur API: ' . $e->getMessage()
      ]);
      exit;
   }
}

// ==================== LECTURE DES LOGS DE PROGRESSION ====================
if (isset($_GET['api']) && $_GET['api'] === 'logs') {
   header('Content-Type: application/json');
   $deploymentId = $_GET['deploy_id'] ?? '';
   $sinceTime = $_GET['since'] ?? 0;
   $targetDir = $_GET['target'] ?? '.';

   // ⭐⭐ UTILISER LE CHEMIN ABSOLU
    $absoluteTargetPath = getAbsoluteTargetPath($targetDir);
    $targetLogFile = $absoluteTargetPath . '/slogs/install.log';
   // ⭐⭐ DEBUG CRITIQUE
   error_log("🔍 API LOGS APPELÉE: deploy_id=$deploymentId, since=$sinceTime, target=$targetDir");
   // Lire le log depuis le dossier cible
   $messages = [];
   $targetLogFile = $absoluteTargetPath . '/slogs/install.log';
   //error_log("📁 FICHIER LOG RECHERCHÉ: $targetLogFile");
   //error_log("📁 EXISTE: " . (file_exists($targetLogFile) ? 'OUI' : 'NON'));
   if (!file_exists($targetLogFile)) {
        $slogsDir = dirname($targetLogFile);
        if (!is_dir($slogsDir))
            @mkdir($slogsDir, 0755, true);
        $timestamp = date('d-M-Y H:i:s');
        $initialMessage = "[$timestamp] [$deploymentId] [INFO] Déploiement initialisé...\n";
        file_put_contents($targetLogFile, $initialMessage);
    }

   if (file_exists($targetLogFile)) {
      //error_log("📖 LECTURE DU FICHIER LOG...");
      $content = file_get_contents($targetLogFile);
      //error_log("📄 CONTENU LOG (" . strlen($content) . " bytes): " . substr($content, 0, 200) . "...");
      $lines = @file($targetLogFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      //error_log("📊 LIGNES TROUVÉES: " . count($lines));
      if ($lines) {
         foreach ($lines as $line) {
            if (preg_match('/^\[([^\]]+)\]\s+\[([^\]]+)\]\s+\[([^\]]+)\]\s+(.+)$/', $line, $matches)) {
               $timestamp = strtotime($matches[1]);
               $logDeployId = $matches[2];
               $type = $matches[3];
               $message = $matches[4];
               if ($logDeployId === $deploymentId && $timestamp > $sinceTime) {
                  //error_log("✅ MATCH EXACT - AJOUT MESSAGE");
                  $messages[] = [
                  'timestamp' => $timestamp,
                  'type' => strtolower($type),
                  'message' => $message,
                  'time' => $matches[1]
                  ];
               }
            }
         }
      }
   }
   //error_log("📤 ENVOI " . count($messages) . " MESSAGES");
   echo json_encode([
      'messages' => $messages,
      'last_update' => time()
   ]);
   exit;
}

// ==================== DÉTECTION DU CONTEXTE AMÉLIORÉE ====================
// $headers_already_sent = headers_sent();

// ==================== CONFIGURATIONS ====================
set_time_limit(0);
ini_set('max_execution_time', 600);
ini_set('default_socket_timeout', 600);
ini_set('memory_limit', '512M');

// ==================== HEADERS UNIQUEMENT EN MODE STANDALONE ====================
if (!$headers_already_sent && !isset($_GET['api'])) {
   if (session_status() === PHP_SESSION_NONE)
      session_start();
   
   header('X-LiteSpeed-No-Buffering: 1');
   header('X-Accel-Buffering: no');
   header('Content-Encoding: none');
   header('Content-Type: text/html; charset=utf-8');
   header('Cache-Control: no-cache, no-store, must-revalidate');
   header('Pragma: no-cache');
   header('Expires: 0');
   header('X-Robots-Tag: noindex, nofollow');
   header('X-Content-Type-Options: nosniff');
   header('Connection: keep-alive');
   header('Keep-Alive: timeout=300, max=1000');
}

// ==================== FONCTION DE DÉPLOIEMENT API ====================
function executeDeployment($version, $targetDir) {
// ⭐⭐ UTILISER LE CHEMIN ABSOLU
    $absoluteTargetPath = getAbsoluteTargetPath($targetDir);
    error_log("🎯 EXECUTE DEPLOYMENT - Chemin absolu: $absoluteTargetPath");

   $start_time = time();
   $apiLockFile = __DIR__ . '/api_deploy.lock';
   $globalLockFile = __DIR__ . '/global_deploy.lock';
   $deploymentId = $_GET['deploy_id'] ?? uniqid('deploy_');
   $deployer = new GithubDeployer();

// ✅ Détection d'installation : utilise déjà la logique correcte
   $isUpdate = $deployer->isNPDSInstalled($absoluteTargetPath);
   $logMessage = function($message, $type = 'INFO') use ($absoluteTargetPath, $deploymentId) {
      $timestamp = date('d-M-Y H:i:s');
      $logEntry = "[$timestamp] [$deploymentId] [$type] $message\n";
      // Créer slogs/ dans la cible du déploiement
      $targetSlogsDir = $absoluteTargetPath . '/slogs';
      if (!is_dir($targetSlogsDir))
         @mkdir($targetSlogsDir, 0755, true);
      $targetLogFile = $targetSlogsDir . '/install.log';
      file_put_contents($targetLogFile, $logEntry, FILE_APPEND | LOCK_EX);
      error_log("📢 DEPLOY: $message");
   };
   // Fonction helper pour supprimer le verrou
   $removeApiLock = function() use ($apiLockFile) {
      if (file_exists($apiLockFile)) {
         @unlink($apiLockFile);
         error_log("🔓 VERROU API SUPPRIMÉ");
      }
   };
   try {
      $logMessage('🚀 '. t('deployment_started') .' - '.t('version').' : '.$version.' - '.t('path').': '.$targetDir.' - '.t('type').': ' . ($isUpdate ? t('update') : t('new_install')));
      error_log("🚀 DÉPLOIEMENT DÉMARRÉ - Version: $version - Cible: $targetDir - Type: " . ($isUpdate ? "Mise à jour" : "Nouvelle installation"));
      $deployer = new GithubDeployer();
      // Backup si mise à jour
      if ($isUpdate) {
         $logMessage("PROCESS:BACKUP");
         $logMessage("PROGRESS:0");
         error_log("💾 Création du backup...");
         $logMessage("PROGRESS:30");
         $logMessage("PROGRESS:60");
         $logMessage("PROGRESS:90");
         $backupManager = new NPDSBackupManager();
         $backupResult = $backupManager->backupCriticalFiles($targetDir);
         if (!$backupResult['success'])
            throw new Exception("Échec du backup: " . $backupResult['message']);
         $logMessage("PROGRESS:100");
         $addedFiles = $backupResult['file_count'];
         $size = filesize($backupResult['file']);
         $size_mb = round($size/1024/1024, 2);
         $logMessage('💾 Backup créé: '.$addedFiles.' '.t('files').' ('.$size_mb.' MB)');
         error_log("✅ Backup créé");
      }
      // Téléchargement
      error_log("📦 Téléchargement de $version...");
      $logMessage("PROCESS:DOWNLOAD");
      $logMessage("PROGRESS:0");
      $logMessage("PROGRESS:5");
      $logMessage("PROGRESS:10");
      $logMessage("PROGRESS:15");
      $logMessage('📦 '.t('downloading').' : '.$version.'...');
      // URLs GitHub correctes
      if ($version === 'master')
         $url = $deployer->buildVersionUrl('https://github.com/npds/npds_dune/archive/refs/heads/', $version, 'zip');
      else
         $url = $deployer->buildVersionUrl('https://github.com/npds/npds_dune/archive/refs/tags/', $version, 'zip');
      error_log("🔗 URL: $url");
      $tempFile = $deployer->getTempDir() . '/' . uniqid('github_') . '.zip';
      $downloadResult = $deployer->downloadFile($url, $tempFile);
//      $logMessage("PROGRESS:100");
      if (!$downloadResult['success'])
         throw new Exception("Échec téléchargement: " . $downloadResult['message']);
      $fileSize = filesize($tempFile);
      $logMessage("📦 " .t('download_success').": " . round($fileSize/1024/1024, 2) . " MB");
      // Extraction
      $logMessage("PROCESS:EXTRACT");
      $logMessage("PROGRESS:20");
      $logMessage("📂 ".t('extraction_progress')."...");
      $logMessage("PROGRESS:35");
      $logMessage("PROGRESS:38");
      $logMessage("PROGRESS:41");
      $logMessage("PROGRESS:44");
      $logMessage("PROGRESS:47");

      $extractResult = $deployer->extractFirstFolderContent($tempFile, $absoluteTargetPath, 'zip', $version, $isUpdate);
      if (!$extractResult['success']) 
         throw new Exception("Échec extraction: " . $extractResult['message']);
      $logMessage("PROGRESS:50");
      $logMessage("PROGRESS:53");
      $logMessage("PROGRESS:57");
      $logMessage('🔧 '.t('extraction_finished'));
      $logMessage('📁 '.t('copying_files').'...');
      sleep(2);
      $logMessage("PROCESS:COPY");
      $logMessage("PROGRESS:65");
      $logMessage("PROGRESS:80");
      $logMessage("PROGRESS:90");
      $logMessage('🔧 '.t('copied').'...');
      $logMessage("PROGRESS:99");
      $logMessage('📋 '.t('copy_finished'));

      // === MISE À JOUR TIMEZONE CONFIG.PHP (VERSION SIMPLIFIÉE) ===
      if ($isUpdate) {
         $configFile = $absoluteTargetPath . '/config.php';
         // Lire le fichier en tableau pour la date (ligne fixe) et en string pour le timezone (regex)
         $lines = file($configFile);
         $content = file_get_contents($configFile);
         $timezoneUpdated = false;
         $oldValue = '';
         if (preg_match('#^\$gmt\s*=\s*"([^"]*)";#m', $content, $matches)) {
            $fullMatch = $matches[0];  // La ligne
            $value = $matches[1];      // La valeur
            $oldValue = $value;
            // Si la valeur ne contient aucune lettre (ancien format numérique)
            if (!preg_match('#[a-zA-Z]#', $value)) {
               // Remplacer l'ancienne ligne par la nouvelle dans le contenu
               $newGmtLine = '$gmt = "' . date_default_timezone_get() . '";';
               $content = str_replace($fullMatch, $newGmtLine, $content);
               $timezoneUpdated = true;
            }
         }
         // Si le timezone a été mis à jour, mettre à jour aussi la date de génération
         if ($timezoneUpdated) {
            // Mettre à jour la date de génération (ligne 14 = index 13 - structure fixe)
            if (isset($lines[13]))
               $lines[13] = '# modifié le : ' . date('d-M-Y H:i:s') . ';' . " \n";
            // Réécrire le fichier avec les deux modifications
            $fic = fopen($configFile, 'w');
            foreach($lines as $ligne) {
               fwrite($fic, $ligne);
            }
            fclose($fic);
            $logMessage('🕒 Timezone config.php mis à jour: ' . $oldValue . ' → ' . date_default_timezone_get());
         } else if ($oldValue)
            $logMessage('✅ Timezone config.php déjà correct: ' . $oldValue);
         else 
            $logMessage('⚠️ Variable $gmt non trouvée dans config.php');
      }
      // === FIN MISE À JOUR ===
      
      $logMessage("PROGRESS:100");

      // Nettoyage
      @unlink($tempFile);

      $duration = time() - $start_time;
      $completionMessage = $isUpdate ? 
            t('deploy_succes_update') : 
            t('deploy_succes_newinst') ;
      error_log('🎉 ' . $completionMessage . ' : ' . $duration . ' secondes');
      $logMessage('🎉 ' . $completionMessage . ' (' . $duration . ' '.t('secondes').')', "SUCCESS");

      // SUPPRIMER LES DEUX VERROUS DIRECTEMENT
      if (file_exists($apiLockFile)) {
         @unlink($apiLockFile);
         error_log("🔓 VERROU API SUPPRIMÉ");
      }
      if (file_exists($globalLockFile)) {
         @unlink($globalLockFile); 
         error_log("🔓 VERROU GLOBAL SUPPRIMÉ");
      }

      return [
         'success' => true,
         'message' => $completionMessage . ' : ' . $duration . ' secondes',
         'duration' => $duration,
         'version' => $version,
         'is_update' => $isUpdate
      ];
   } catch (Exception $e) {
      // ⭐⭐ SUPPRIMER LE VERROU API APRÈS ERREUR ⭐⭐
      $removeApiLock();
      error_log("💥 ERREUR DÉPLOIEMENT: " . $e->getMessage());
      return [
         'success' => false,
         'message' => '💥 ' . $e->getMessage(),
         'duration' => time() - $start_time
      ];
   }
}

// ==================== CLASSES PRINCIPALES ====================
class NPDSExclusions {
   private static $alwaysExcluded = [
      'install', 'install/', 'install/*', 'install.php',
      // Ces éléments ne doivent JAMAIS être copiés en production
   ];
   private static $excludedIfExists = [
      // === FICHIERS DE CONFIGURATION CRITIQUES ===
      'config.php',               // configuration générale du site
      'IZ-Xinstall.ok',           // témoin d'install-auto
      '.htaccess',                // pour le serveur
      'robots.txt',               // welcome to the machine
      'filemanager.conf',         // file manager config général
      // === FICHIERS DE DONNEES ===
      'abla.log.php',             // statistiques
      'signat.php',               // pied d'email
      // === DOSSIERS UTILISATEURS COMPLETS (IMMUABLES) ===
      'users_private/groupe',           // Protège le dossier et son contenu existant
      'users_private/user',             // Protège le dossier user si existe
      'users_private/usersbadmail.txt', // Protège le fichier si existe
      'slogs/*',                        // Logs
      'cache/*',                        // Cache système
      'meta/meta.php',                  // Stockage metatags
      // === FICHIERS/DOSSIERS CONFIGURATION ET DATA MODULES (À PRÉSERVER) ===
      'modules/archive-stories/archive-stories.conf.php',
      'modules/archive-stories/cache.timings.php',
      'modules/geoloc/geoloc.conf',
      'modules/npds_twi/twi_conf.php',
      'modules/push/push.conf.php',
      'modules/push/push.js',
      'modules/reseaux-sociaux/reseaux-sociaux.conf.php',
      'modules/sform/contact/contact.php',
      'modules/sform/contact/formulaire.php',
      'modules/sform/forum/formulaire.php',
      'modules/sform/forum/forum_extender.php',
      'modules/upload/upload.conf.php',
      'modules/upload/tmp/*',
      'modules/upload/upload/*',
      'modules/upload/upload_forum/*',
      'modules/upload/include_editeur/upload.conf.editeur.php',
      'modules/upload/include_forum/upload.conf.forum.php',
      'modules/wspad/config.php',
      'modules/wspad/locks/*',
      // === FICHIERS DOSSIERS CONFIGURATION ET DATA DES LIB ===
      'lib/PHPMailer/PHPmailer.conf.php',     // conf npds de la lib
      'lib/PHPMailer/key/*',                   // stockage keys
      'lib/js/npds_tarteaucitron.js',         // paramètre initialisation
      'lib/js/npds_tarteaucitron_service.js', // paramètre services
      // === FICHIERS PERSONNALISÉS ===
      'language/lang-mods.php',   // fichiers langue personnalisable
      'language/lang-multi.php',  // fichiers langue personnalisable
      'static/edito.txt',         // page statique
      // === IMAGES systeme PERSONALISABLE ===
      'images/ogimg_square.png',
      'images/ogimg_rect.png',
      'images/favicon.ico',
      'images/favicon-180.png',
      'images/favicon-152.png',
      'images/favicon-120.png',
      // === BACKUPS ET SAUVEGARDES ===
      'backup/*',
      'sauvegardes/*',
      '*.zip',                    // Archives de backup
      '*.tar.gz',
      '*.backup*',               // Fichiers de backup existants
   ];
   private static $siteRoot = null;

   private static function getSiteRoot() {
      if (self::$siteRoot !== null)
         return self::$siteRoot;
      // Racine NPDS = 2 niveaux au-dessus de lib/deployer/
      self::$siteRoot = dirname(dirname(__DIR__));
      return self::$siteRoot;
   }
   
   /**
   * Vérifie si un fichier doit être exclu de l'écrasement
   * UNIQUEMENT en mise à jour
   */
   public static function shouldExclude($filePath, $version = null, $isUpdate = false) {
      // 🔥 IMPORTANT : En installation neuve, AUCUNE exclusion
      if (!$isUpdate)
         return false; // Tout peut être écrasé
      // 🔥 Seulement en mise à jour : vérifier les exclusions
      // 1. Vérifier les exclusions ABSOLUES (toujours exclure)
      foreach (self::$alwaysExcluded as $pattern) {
         if (self::matchesPattern($filePath, $pattern))
            return true; // ← TOUJOURS exclure, peu importe l'existence
      }
      // 2. Vérifier les exclusions CONDITIONNELLES (uniquement si existent)
      foreach (self::$excludedIfExists as $pattern) {
         if (self::matchesPattern($filePath, $pattern)) {
            $rootPath = self::getSiteRoot();
            $fullPath = $rootPath . '/' . $filePath;
            // Exclure le fichier SEULEMENT s'il existe déjà dans le site actuel
            return file_exists($fullPath);
         }
      }
      return false;
   }

   /**
   * Vérifie si un chemin correspond à un pattern
   */
   private static function matchesPattern($filePath, $pattern) {
      $regex = str_replace('/', '\/', $pattern);
      $regex = str_replace('*', '.*', $regex);
      $regex = '/^' . $regex . '$/';
      return preg_match($regex, $filePath) === 1;
   }
}

// ==================== GESTION DES BACKUPS ====================
class NPDSBackupManager {
   private $backupDir = 'npds_backups';

   public function __construct($customBackupDir = null) {
      if ($customBackupDir)
         $this->backupDir = $customBackupDir;
      else
         $this->backupDir = dirname(__FILE__) . '/npds_backups';
      if (!is_dir($this->backupDir))
         @mkdir($this->backupDir, 0755, true);
      error_log("📁 Backup path: " . $this->backupDir);
   }

   /**
   * Crée un backup des fichiers critiques
   */
   public function backupCriticalFiles($targetDir) {
      error_log("💾 Démarrage du backup...");
      $timestamp = date('Y-m-d_His');
      $backupFile = $this->backupDir . '/files_backup_' . $timestamp . '.zip';
      try {
         $zip = new ZipArchive();
         if ($zip->open($backupFile, ZipArchive::CREATE) === true) {
            $addedFiles = 0;
            // Backup des fichiers critiques
            $criticalFiles = $this->getCriticalFilesList($targetDir);
            foreach ($criticalFiles as $filePattern) {
               $files = glob($targetDir . '/' . $filePattern);
               foreach ($files as $file) {
                  if (is_file($file)) {
                     $relativePath = str_replace($targetDir . '/', '', $file);
                     if ($zip->addFile($file, $relativePath))
                        $addedFiles++;
                  } elseif (is_dir($file)) {
                     $iterator = new RecursiveIteratorIterator(
                        new RecursiveDirectoryIterator($file, RecursiveDirectoryIterator::SKIP_DOTS),
                        RecursiveIteratorIterator::SELF_FIRST
                     );
                     foreach ($iterator as $item) {
                        if ($item->isFile()) {
                           $relativePath = str_replace($targetDir . '/', '', $item->getRealPath());
                           if ($zip->addFile($item->getRealPath(), $relativePath))
                              $addedFiles++;
                        }
                     }
                  }
               }
            }
            $zip->close();
            if (file_exists($backupFile)) {
               $size = filesize($backupFile);
               $size_mb = round($size/1024/1024, 2);
               error_log("✅ Backup TERMINÉ: " . $addedFiles . " fichiers (" . $size_mb . " MB)");
               return [
                   'success' => true,
                   'message' => 'Backup fichiers créé',
                   'file' => $backupFile,
                   'size' => $size,
                   'file_count' => $addedFiles
               ];
            }
         }
      } catch (Exception $e) {
         error_log("❌ Erreur backup fichiers: " . $e->getMessage());
      }
      return ['success' => false, 'message' => 'Échec création backup fichiers'];
   }

   /**
   * Liste des fichiers critiques à backuper
   */
   private function getCriticalFilesList($targetDir) {
      return [
         'config.php',
         'IZ-Xinstall.ok',
         '.htaccess',
         'robots.txt',
         'users_private/*',
         'static/*',
         'meta/meta.php',
         'slogs/*',
         'images/*',
         'themes/*/images/*',
         'themes/pages.php',
         'modules/*/config.php',
         'modules/*.conf.php',
         'language/lang-multi.php',
         'language/lang-mods.php',
      ];
   }

   /**
   * Nettoyage des vieux backups
   */
   public function cleanupOldBackups($keepLast = 5) {
      $backupFiles = array_merge(
         glob($this->backupDir . '/*.zip'),
         glob($this->backupDir . '/*.sql'),
         glob($this->backupDir . '/*.tar.gz'),
         glob($this->backupDir . '/*.backup*')
      );
      usort($backupFiles, function($a, $b) {
         return filemtime($b) - filemtime($a);
      });
      $deleted = 0;
      for ($i = $keepLast; $i < count($backupFiles); $i++) {
         if (@unlink($backupFiles[$i]))
            $deleted++;
      }
      return $deleted;
   }
}

class GithubDeployer {
   private $userAgent = 'Mozilla/5.0 (compatible; GitHubDownloader/1.0)';
   private $timeout = 120;
   private $tempDir = 'npds_deployer_temp';
   private $lastDownloadSize = 0;

   public function __construct(array $config = []) {
      foreach ($config as $key => $value) {
         if (property_exists($this, $key))
            $this->$key = $value;
      }
      if (!is_dir($this->tempDir))
         @mkdir($this->tempDir, 0755, true);
      $this->cleanupOldFiles();
   }

   public function isNPDSInstalled($targetDir) {
      if (isset($_GET['return_url']) && strpos($_GET['return_url'], 'admin.php') !== false)
         return true;
      // Construire le chemin absolu de la cible
      if ($targetDir === '.' || $targetDir === './')
         $absolutePath = $_SERVER['DOCUMENT_ROOT'];
      else if ($targetDir[0] !== '/') {
         // Chemin relatif : on le rapporte à la racine du site
         $absolutePath = $_SERVER['DOCUMENT_ROOT'] . '/' . $targetDir;
      } else
         $absolutePath = $targetDir; // Chemin absolu
      // Vérifier les fichiers spécifiques à npds
      $installFiles = ['mainfile.php','config.php', 'grab_globals.php', 'IZ-Xinstall.ok'];
      foreach ($installFiles as $file) {
         $fullPath = $absolutePath . '/' . $file;
         error_log("🔍 Vérification: $fullPath");
         if (file_exists($fullPath)) {
            error_log("🔍 💥 FICHIER TROUVÉ: $fullPath - MISE À JOUR");
            return true;
         }
      }
      error_log("🔍 ❌ NOUVELLE INSTALLATION dans: $targetDir");
      return false;
   }

   private function validateReceptacle($targetDir) {
      if (!is_dir($targetDir)) return ['safe' => true, 'warnings' => []];
      
      $existingItems = scandir($targetDir);
      $existingItems = array_diff($existingItems, ['.', '..']);
      $warnings = [];
      
      $npdsItems = ['index.php', 'admin.php', 'config.php', 'lib/', 'modules/', 'themes/'];
      
      foreach ($existingItems as $item) {
         if (in_array($item, $npdsItems)) {
            $warnings[] = [
               'type' => 'conflit_npds',
               'item' => $item,
               'message' => 'Ce fichier/dossier existe déjà dans NPDS'
            ];
         }
      }
      
      return ['safe' => empty($warnings), 'warnings' => $warnings];
   }

   private function showInstallationWarnings($validation, $targetDir, $version) {
      echo head_html();
      echo '
      <h2 class="ms-3"><span class="display-6">🚨 </span>Vérification du réceptacle</h2>
      <div class="section-danger py-2">
         <h3>🚨 Réceptacle non sécurisé détecté</h3>
         <p>Le dossier <strong>' . htmlspecialchars($targetDir) . '</strong> contient des éléments problématiques :</p>
         <div class="mt-3">
            <h4>Éléments détectés :</h4>
            <ul>';
      foreach ($validation['warnings'] as $warning) {
         $icon = $warning['type'] === 'conflit_npds' ? '🔄' : '⚠️';
         echo '<li>' . $icon . ' <strong>' . htmlspecialchars($warning['item']) . '</strong> : ' . $warning['message'] . '</li>';
      }
      echo '
            </ul>
         </div>
         <div class="mt-3">
            <a href="?op=deploy&version=' . urlencode($version) . '&path=' . urlencode($targetDir) . '&confirm=yes&force=yes" class="btn btn-danger" onclick="return confirm(\'🚨 FORCER L\\\'INSTALLATION ?\')">🚨 Forcer l\'installation</a>
            <a href="?" class="btn btn-secondary">Annuler</a>
         </div>
      </div>';
      echo foot_html();
   }

   private function updateProgress($message, $progress = null) {
      error_log("DEPLOY: " . $message);
      echo '<script>if(typeof updateStatus !== "undefined") updateStatus("' . addslashes($message) . '");</script>';
      echo ' ';
      for ($i = 0; $i < ob_get_level(); $i++) {
          ob_flush();
      }
      flush();
      usleep(10000);
   }

   public function extractFirstFolderContent(string $archivePath, string $targetDir, string $format, string $version, bool $isUpdate = false): array {
      global $lang;
      $tempExtractDir = $this->tempDir . '/' . uniqid('extract_');
      if (!@mkdir($tempExtractDir, 0755, true))
         return $this->createResult(false, t('temp_dir_error'));
      try {
         if ($format === 'zip') {
            $zip = new ZipArchive();
            if ($zip->open($archivePath) !== true) {
               $this->removeDirectory($tempExtractDir);
               return $this->createResult(false, t('zip_open_error'));
            }
            $zip->extractTo($tempExtractDir);
            $zip->close();
         }
         $firstFolder = $this->findFirstFolder($tempExtractDir);
         if (!$firstFolder) {
            $this->removeDirectory($tempExtractDir);
            return $this->createResult(false, t('no_folder_in_archive'));
         }
         error_log("✅ " . t('extraction_finished'));
         error_log("🔍 Premier dossier trouvé: " . basename($firstFolder));
         $revolutionPath = $firstFolder . '/revolution_16';
         if (is_dir($revolutionPath)) {
            $firstFolder = $revolutionPath;
            error_log("✅ Dossier revolution_16 trouvé - utilisation: " . $firstFolder);
         } else {
            error_log("❌ Dossier revolution_16 NON trouvé dans: " . $firstFolder);
            // Debug: lister le contenu du premier dossier
            $items = scandir($firstFolder);
            error_log("📁 Contenu du premier dossier: " . implode(', ', $items));
         }
         error_log("🚀 Copie depuis: " . $firstFolder . " vers: " . $targetDir);
         $this->copyDirectoryContentsFlat($firstFolder, $targetDir, $version, $isUpdate);
         $this->removeDirectory($tempExtractDir);
         return $this->createResult(true, "Contenu extrait avec succès");
      } catch (Exception $e) {
         $this->removeDirectory($tempExtractDir);
         return $this->createResult(false, t('extraction_error') . ': ' . $e->getMessage());
      }
   }

   private function copyDirectoryContentsFlat(string $source, string $destination, $version = null, $isUpdate = false): void {
      if (!is_dir($destination))
         mkdir($destination, 0755, true);
      $iterator = new RecursiveIteratorIterator(
         new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
         RecursiveIteratorIterator::SELF_FIRST
      );
      $fileCount = 0;
      foreach ($iterator as $item) {
         $relativePath = $iterator->getSubPathName();
         $targetPath = $destination . DIRECTORY_SEPARATOR . $relativePath;
         if ($isUpdate && NPDSExclusions::shouldExclude($relativePath, $version, $isUpdate))
            continue;
         if ($item->isDir()) {
            if (!is_dir($targetPath))
               mkdir($targetPath, 0755);
         } else {
            $parentDir = dirname($targetPath);
            if (!is_dir($parentDir))
               mkdir($parentDir, 0755, true);
            copy($item->getRealPath(), $targetPath);
            $fileCount++;
         }
      }
      error_log("✅ Copie terminée: $fileCount fichiers");
   }

   private function findFirstFolder(string $directory): ?string {
      $items = scandir($directory);
      foreach ($items as $item) {
         if ($item !== '.' && $item !== '..' && is_dir($directory . '/' . $item))
            return $directory . '/' . $item;
      }
      return null;
   }

   public function downloadFile(string $url, string $destination): array {
      $context = stream_context_create([
         'http' => ['timeout' => $this->timeout],
         'ssl' => ['verify_peer' => false]
      ]);
      $source = @fopen($url, 'rb', false, $context);
      if (!$source)
         return $this->createResult(false, t('failed_download'));
      $dest = @fopen($destination, 'wb');
      if (!$dest) {
         fclose($source);
         return $this->createResult(false, t('write_error'));
      }
      stream_copy_to_stream($source, $dest);
      fclose($source);
      fclose($dest);
      $fileSize = filesize($destination);
      error_log("✅ Téléchargement réussi: " . round($fileSize/1024/1024, 2) . " MB");
      return $this->createResult(true, t('download_success'), ['size' => filesize($destination)]);
   }

   public function buildVersionUrl(string $baseUrl, string $version, string $format): string {
      return rtrim($baseUrl, '/') . '/' . $version . '.' . $format;
   }

   private function removeDirectory(string $directory): void {
      if (!is_dir($directory)) return;
      $files = new RecursiveIteratorIterator(
         new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS),
         RecursiveIteratorIterator::CHILD_FIRST
      );
      foreach ($files as $file) {
         if ($file->isDir())
            @rmdir($file->getRealPath());
         else
            @unlink($file->getRealPath());
      }
      @rmdir($directory);
   }

   private function createResult(bool $success, string $message, array $data = []): array {
      return [
         'success' => $success,
         'message' => $message,
         'data' => $data,
         'timestamp' => time()
      ];
   }

   private function cleanupOldFiles(): void {
      if (!is_dir($this->tempDir)) return;
      $files = glob($this->tempDir . '/*');
      $now = time();
      foreach ($files as $file) {
         if (filemtime($file) < ($now - 3600)) {
            @unlink($file);
         }
      }
   }

   public function cleanupDirectory(string $directory): array {
      try {
         $this->removeDirectory($directory);
         return $this->createResult(true, "Dossier nettoyé: " . $directory);
      } catch (Exception $e) {
         return $this->createResult(false, t('cleanup_error') . ": " . $e->getMessage());
      }
   }
   
   public function getTempDir(): string {
      return $this->tempDir;
   }
}

// ==================== FONCTIONS D'INTERFACE ====================
function head_html_deploy($title = 'Déploiement en cours') {
   global $lang;
   return '<!DOCTYPE html>
<html lang="'.$lang.'">
   <head>
      <meta charset="utf-8">
      <title>' . htmlspecialchars($title) . '</title>
      <style>
         :root {
         --bs-body-color: #212529;
         --bs-success: #198754;
         --bs-primary: #0d6efd;
         --bs-secondary: #6c757d;
         --bs-info: #0dcaf0;
         --bs-warning: #ffc107;
         --bs-danger: #dc3545;
         --bs-light: #f8f9fa;
         --bs-dark: #212529;
         --bs-border-radius: 0.375rem;
         --bs-border-width: 1px;
         --bs-border-color: #dee2e6;
      }

         body {font-family: Arial, sans-serif; margin: 0; color:var(--bs-body-color);}
         #page {min-height:100vh; display:flex; flex-direction:column;}
         .container { max-width: 800px; margin: 1.5rem auto; background: var(--bs-light); padding: 1rem; border-radius: 8px; flex-grow:1;}
         .spinner { border: 4px solid #f3f3f3; border-top: 4px solid #3498db; border-radius: 50%; width: 40px; height: 40px; animation: spin 2s linear infinite; margin: 20px auto; }
         @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
         .status { text-align: center; font-size: 18px; margin: 20px 0; color: #333; }
         .progress-container { margin: 20px 0; text-align: center; }
         .progress-bar { width: 100%; height: 20px; background: #f0f0f0; border-radius: 10px; overflow: hidden; }
         .progress-fill { height: 100%; background: linear-gradient(90deg, #2196F3, #1976D2); transition: width 2.5s ease; border-radius: 10px; }
         .progress-text { margin-top: 5px; font-weight: bold; color: #333; }
         .process-label { margin-top: 3px; font-size: 12px; color: #666; font-style: italic; }
         .logs { background: #1e1e1e; color: #00ff00; padding: 15px; border-radius: 4px; font-family: monospace; font-size: 12px; overflow-y: auto; margin-top: 20px; }
         .success {color: var(--bs-success);}
         .error { color: var(--bs-danger); }
         .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; margin: 10px; }
         .btn { display: inline-block; padding: 0.375rem 0.75rem; border: 1px solid; border-radius: 0.375rem; text-decoration: none; margin: 0.25rem; }
         .btn-success { background: var(--bs-success); color: white; border-color:  var(--bs-success); }
         .btn-danger { background: var(--bs-danger); color: white; border-color: var(--bs-danger); }
         .btn-secondary { background: var(--bs-secondary); color: white; border-color:var(--bs-secondary); }
         .bg-light {background-color: var(--bs-light); padding: 0 1rem;}
         .d-flex {display: flex !important;}
         .align-items-center {align-items: center !important;}
         .ms-auto {margin-left: auto!important}
         ul { list-style-type: none; padding: 0; }
         li { margin: 6px 0; }
         a { color: #007bff; text-decoration: none; }
         .small, small {font-size: .875em;}
         .m-0 {margin:0;}

      </style>
   </head>
   <body>
      <div id="page">
         <div class="d-flex align-items-center bg-light">
            <h1>NPDS<br /><small>' . t('welcome') . '</small><br /><span class="display-6">🚀</span></h1>
         </div>
         <div class="container">
            <div class="progress-container">
               <div class="progress-bar">
                  <div class="progress-fill" id="progressFill" style="width: 0%"></div>
               </div>
               <div class="progress-text" id="progressText">0%</div>
               <div class="process-label" id="processLabel"></div>
            </div>
            <div class="spinner"></div>
            <div class="status" id="status">Initialisation...</div>
            <div class="logs" id="logs"></div>
            <div id="result" style="display: none;"></div>
            <script>
               function updateStatus(message) {
                  console.log("📨 updateStatus appelé avec:", message);
                  const cleanMessage = message.replace(/[\r\n]+/g, " • ").trim();
                  if (message.startsWith("PROCESS:")) {
                     console.log("🎯 PROCESS détecté:", message);
                     const processName = message.split(":")[1];
                     changeProcess(processName);
                     return;
                  }
                  if (message.startsWith("PROGRESS:")) {
                     console.log("📊 PROGRESS détecté:", message);
                     const percent = parseInt(message.split(":")[1]);
                     updateProgressBar(percent);
                     return;
                  }
                  document.getElementById("status").textContent = cleanMessage;
                  addLog("> " + cleanMessage);
               }

               function addLog(message) {
                  const logsElement = document.getElementById("logs");
                  const cleanMessage = message.replace(/[\r\n]+/g, " • ");
                  logsElement.innerHTML += cleanMessage + "<br>";
                  logsElement.scrollTop = logsElement.scrollHeight;
               }

               function changeProcess(processName) {
                   console.log("🔄 Changement de processus:", processName);
                   const processLabel = document.getElementById("processLabel");
                   const progressFill = document.getElementById("progressFill");
                   progressFill.style.width = "0%";
                   progressFill.style.transition = "none";
                   const colors = {
                       "BACKUP": "linear-gradient(90deg, #4CAF50, #388E3C)",
                       "DOWNLOAD": "linear-gradient(90deg, #4CAF50, #388E3C)", 
                       "EXTRACT": "linear-gradient(90deg, #4CAF50, #388E3C)",
                       "COPY": "linear-gradient(90deg, #4CAF50, #388E3C)"
                   };
                   progressFill.style.background = colors[processName] || colors["DOWNLOAD"];
                   const labels = {
                       "BACKUP": "Sauvegarde en cours...",
                       "DOWNLOAD": "Téléchargement en cours...",
                       "EXTRACT": "Extraction en cours...",
                       "COPY": "Copie finale en cours..."
                   };
                   processLabel.textContent = labels[processName] || "";
                   setTimeout(() => {
                       progressFill.style.transition = "width 0.5s ease";
                   }, 50);
               }
   
               function updateProgressBar(percent) {
                  console.log("📊 Mise à jour barre:", percent + "%");
                  const progressFill = document.getElementById("progressFill");
                  const progressText = document.getElementById("progressText");
                  progressFill.style.width = percent + "%";
                  progressText.textContent = percent + "%";
               }
            </script>';
}

function foot_html_deploy() {
   return '
            </div>
            <footer class="d-flex align-items-center bg-light">
               <div class="ps-3"><a href="https://www.npds.org" target="_blank">NPDS</a> <br />npds_deployer v.1.1</div>
               <div class="ms-auto small px-3">
                  <ul id="infos-system">
                     <li class="m-0">PHP : <span class="">' .phpversion(). '</span></li>
                     <li class="m-0">'.t('memory_limit').' : '. ini_get('memory_limit'). '</li>
                     <li class="m-0">'.t('max_exec_time').' : '.ini_get('max_execution_time').'</li>
                     <li class="m-0">'.t('server').' : '.$_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'.'</li>
                  </ul>
               </div>
            </footer>
         </div>
      </body>
   </html>';
}

/**
* Fonction de construction du sélecteur de language
*/
function renderLanguageSelector($currentLang) {
   $languages = [
      'fr' => 'Français', 
      'en' => 'English',
      'es' => 'Español',
      'de' => 'Deutsch',
      'zh' => '中文'
   ];
   $html = '
   <div class="float-end small">
      <img width="40" height="40" src="data:image/png;base64,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" alt="langage selector" />
         <ul id="language_selector" class="pe-3 mt-0">
    ';
   foreach ($languages as $code => $name) {
      $active = $code === $currentLang ? 'active' : '';
      $html .= '<li><a href="?lang=' . $code . '"class="ms-3 ' . $active . '">' . $name . '</a></li>';
   }
   $html .= '
      <ul>
    </div>';
   return $html;
}

function head_html(){
   global $lang;
   return '<!DOCTYPE html>
   <html lang="'.$lang.'">
      <head>
         <meta charset="utf-8">
         <title>' . t('welcome') . '</title>
         <style>
            :root {
               --bs-body-color: #212529;
               --bs-success: #198754;
               --bs-primary: #0d6efd;
               --bs-secondary: #6c757d;
               --bs-info: #0dcaf0;
               --bs-warning: #ffc107;
               --bs-danger: #dc3545;
               --bs-light: #f8f9fa;
               --bs-dark: #212529;
               --bs-border-radius: 0.375rem;
               --bs-border-width: 1px;
               --bs-border-color: #dee2e6;
            }
            body {font-family: Arial, sans-serif; margin:0; color:var(--bs-body-color);}
            #page {min-height:100vh; display:flex; flex-direction:column;}
            .container-sm { max-width: 800px; margin: 0 auto; padding: 20px; flex-grow:1;}
            .bg-light {background-color: var(--bs-light); padding: 0 1rem;}
            .section-stable, .section-dev, .section-maintenance, .section-advanced, .section-danger {
               border-radius: 0.375rem; padding: 0.1rem 1rem; margin-bottom: 1rem; border-left: 1.2rem solid;
            }
            .section-stable {border-color: #198754; background-color: #e3eed7; color: var(--bs-success); }
            .section-dev, .section-danger {border-color: var(--bs-danger); background-color: #f4d2d3; color: var(--bs-danger); }
            .section-maintenance {border-color: #6c757d; background-color:#f6f7f9; color: #6c757d;}
            .section-advanced {border-color: #6c757d; background-color:#f6f7f9; }
            .btn { display: inline-block; padding: 0.375rem 0.75rem; border: 1px solid; border-radius: 0.375rem; text-decoration: none; margin: 0.25rem; }
            .btn-success { background: var(--bs-success); color: white; border-color:  var(--bs-success); }
            .btn-danger { background: var(--bs-danger); color: white; border-color: var(--bs-danger); }
            .btn-secondary { background: var(--bs-secondary); color: white; border-color:var(--bs-secondary); }
            .form-control { padding: 0.375rem; border: 1px solid #dee2e6; border-radius: var(--bs-border-radius); width: 100%; }
            .form-select { padding: 0.375rem; border: 1px solid #dee2e6; border-radius: var(--bs-border-radius); width: 100%; }
            .row { display: flex; flex-wrap: wrap; margin: 0 -0.5rem; }
            .col { flex: 1; padding: 0 0.5rem; }
            .ms-3 { margin-left: 1rem; }
            .m-0 {margin: 0;}
            .mt-0 {margin-top: 0 !important;}
            .mt-1 { margin-top: 0.25rem; }
            .mt-3 { margin-top: 1rem; }
            .mb-0 {margin-bottom: 0 !important;}
            .mb-3 {margin-bottom: 1rem !important;}
            .mb-4 {margin-bottom: 1.5rem !important;}
            .ps-1 {padding-left: 0.25rem !important;}
            .ps-3 {padding-left: 1rem!important;}
            .pe-3 {padding-right: 1rem!important}
            .display-6 { font-size: 2rem; font-weight: 300; }
            ul { list-style-type: none; padding: 0; }
            li { margin: 6px 0; }
            a { color: #007bff; text-decoration: none; }
            a:hover { color: #0056b3; }
            .d-flex {display: flex !important;}
            .align-items-center {align-items: center !important;}
            .ms-auto {margin-left: auto!important}
            .small, small {font-size: .875em;}
            #language_selector a.active {color: black; font-weight: bold;}
            #language_selector a:hover{color: black;}
            #language_selector ul {padding: 0; margin-left: 0.5rem !important;}
            .section-stable a {color:green; font-weight: bold;}
            .section-dev a {color: #DC3545; font-weight: bold;}
            .section-danger a:hover{color:white;}
            #infos-system ul li {margin:0;}
         </style>
      </head>
      <body>
         <div id="page">
            <div class="d-flex align-items-center bg-light">
               <h1>NPDS<br /><small>' . t('welcome') . '</small><br /><span class="display-6">🚀</span></h1>
               <div class="ms-auto my-auto ps-3">
               '.renderLanguageSelector($lang).'
               </div>
            </div>
            <div class="container-sm">';
}

function foot_html() {
   return '
            </div>
            <footer class="d-flex align-items-center bg-light">
               <div class="ps-3"><a href="https://www.npds.org" target="_blank">NPDS</a> <br />npds_deployer v.1.1</div>
               <div class="ms-auto small px-3">
                  <ul id="infos-system">
                     <li class="m-0">PHP : <span class="">' .phpversion(). '</span></li>
                     <li class="m-0">'.t('memory_limit').' : '. ini_get('memory_limit'). '</li>
                     <li class="m-0">'.t('max_exec_time').' : '.ini_get('max_execution_time').'</li>
                     <li class="m-0">'.t('server').' : '.$_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'.'</li>
                  </ul>
               </div
            </footer>
         </div>
      </body>
   </html>';
}

// ==================== GESTION DES OPÉRATIONS ====================

function handleDeployOperation() {
   $isFromAdmin = isset($_GET['return_url']) || isset($_COOKIE['admin']);
   $isConfirmed = isset($_GET['confirm']) && $_GET['confirm'] === 'yes';
   if ($isFromAdmin && $isConfirmed) {
      showAjaxDeployInterface();
      exit;
   }
   if (!$isConfirmed) {
      echo head_html();
      echo '
      <div class="section-danger">
         <h2>🚨 ' . t('warning') . '</h2>
         <p>' . t('overwrite_warning') . '</p>
         <a href="?op=deploy&version=' . urlencode($_GET['version']) . '&path=' . urlencode($_GET['path']) . '&confirm=yes" class="btn btn-danger mb-3">' . t('deploy') . '</a>
         <a href="?" class="btn btn-secondary mb-3">' . t('cancel') . '</a>
      </div>';
      echo foot_html();
      return;
   }
   showAjaxDeployInterface();
   exit;
}

// ==================== FONCTION DE DÉPLOIEMENT API ====================

function handleCleanOperation() {
   if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'yes') {
      echo head_html();
      echo '<div class="section-danger">
         <h2>🚨 ' . t('warning') . '</h2>
         <p>Cette action va supprimer tous les fichiers temporaires du déploiement.</p>
         <p>' . t('security_warning') . '</p>
         <a href="?op=clean&confirm=yes" class="btn btn-danger">' . t('clean_temp') . '</a>
         <a href="?" class="btn btn-secondary">Annuler</a>
      </div>';
      echo foot_html();
      return;
   }

   $deployer = new GithubDeployer();
   $result = $deployer->cleanupDirectory('npds_deployer_temp');

   echo head_html();
   echo '<div class="container">';
   if ($result['success'])
      echo '
      <h2 style="color: green;">✅ Nettoyage terminé</h2>
      <p>Les fichiers temporaires ont été supprimés.</p>';
   else
      echo '
      <h2 style="color: red;">❌ '.t('cleanup_error').'</h2>
      <p>' . htmlspecialchars($result['message']) . '</p>';
   echo '
      <p><a href="?" class="btn btn-secondary">'.t('go_back').'</a></p>
   </div>';
   echo foot_html();
}

// ==================== INTERFACE AVEC AJAX ====================

function showAjaxDeployInterface() {
   global $lang;
   $version = $_GET['version'] ?? 'v.16.4';
   $targetDir = $_GET['path'] ?? '.';
   $deployer = new GithubDeployer();
   $isUpdate = $deployer->isNPDSInstalled($targetDir);
   $githubVersion = $version;
   $deploymentId = 'deploy_' . (int)(microtime(true) * 1000);
   echo head_html_deploy('Déploiement NPDS ' . htmlspecialchars($version));
   echo '
      <script>
         // Variables spécifiques
         const deploymentId = "' . $deploymentId . '";
         const phpIsUpdate = ' . ($isUpdate ? 'true' : 'false') . ';
         let logsElement = document.getElementById("logs");
         let statusElement = document.getElementById("status"); 
         let resultElement = document.getElementById("result");
         let lastUpdateTime = 0;
         let globalTimeoutId = null;
         let messageQueue = [];
         let isProcessingQueue = false;
         let lastProcessedTimestamp = 0;
         let shouldStopPolling = false;

         function hideSpinner() {
            const spinner = document.querySelector(".spinner");
            if (spinner)
               spinner.style.display = "none";
         }
         
         function hideStatus() {
            const status = document.querySelector(".status");
            if (status)
               status.style.display = "none";
         }

         function processMessageQueue() {
          console.log("🔍 processMessageQueue() - Début - isProcessingQueue:", isProcessingQueue, "Queue:", messageQueue.length);
            if (isProcessingQueue || messageQueue.length === 0) {
               console.log("⏸️  processMessageQueue() SKIPPÉ");
               return;
            }
            isProcessingQueue = true;
            console.log("🚀 DEBUT processMessageQueue() - Flag TRUE");
            try {
               const message = messageQueue.shift();
               console.log("📝 Traitement message:", message.message.substring(0, 50));
               // Traitement des messages spéciaux (PROCESS:, PROGRESS:)
               if (message.message.startsWith("PROCESS:")) {
                  const processName = message.message.split(":")[1];
                  changeProcess(processName);
               } else if (message.message.startsWith("PROGRESS:")) {
                  const percent = parseInt(message.message.split(":")[1]);
                  updateProgressBar(percent);
               } else {
                  updateStatus(message.message);
               }
            } catch (error) {
               console.error("💥 ERREUR dans processMessageQueue():", error);
            }
            // Délai de 300ms entre chaque message
            setTimeout(() => {
               console.log("🏁 FIN processMessageQueue() - Remise à FALSE");
               isProcessingQueue = false;
               if (messageQueue.length > 0) {
                  console.log("🔄 Queue non vide - Rappel automatique");
                  processMessageQueue();
               } else {console.log("💤 Queue vide - Attente");}
            }, 300);
         }

         function checkLogs() {
            console.log("🔄 checkLogs() appelé - shouldStopPolling:", shouldStopPolling, "lastUpdateTime:", lastUpdateTime);
            if (shouldStopPolling) {
               console.log("🛑 Polling déjà arrêté");
               return;
            }
            fetch("?api=logs&deploy_id=" + deploymentId + "&since=" + lastUpdateTime + "&target='.urlencode($targetDir).'&t=" + Date.now())
               .then(response => {
                  console.log("📨 Réponse status:", response.status);
                  if (!response.ok) throw new Error("HTTP " + response.status);
                  return response.json();
               })
               .then(data => {
                  console.log("📝 Données reçues - Messages:", data.messages ? data.messages.length : 0, "Last update:", data.last_update);
                  if (data.messages && data.messages.length > 0) {
                     console.log("➕ Ajout de", data.messages.length, "messages à la file");
                     messageQueue.push(...data.messages);
                     const lastMessage = data.messages[data.messages.length - 1];
                     if (data.messages.length > 0) {
                        lastUpdateTime = lastMessage.timestamp;
                        console.log("🕒 Nouveau lastUpdateTime:", lastUpdateTime);
                     }
                     console.log("🔍 Dernier message brut:", data.messages[data.messages.length - 1]);
                     const isSuccessEnd = 
                                       lastMessage.message.includes("🎉") ||
                                       lastMessage.type === "SUCCESS";
                     const isErrorEnd = lastMessage.message.includes("💥") ||   // Erreur explosive
                                        lastMessage.message.includes("🚨") ||   // Alerte bloquante  
                                        lastMessage.message.includes("❌") ||    // Échec confirmé
                                        lastMessage.type === "ERROR";           // Type système de log

                     console.log("🎯 Détection fin - isSuccessEnd:", isSuccessEnd, "isErrorEnd:", isErrorEnd);

                     if (isSuccessEnd || isErrorEnd) {
                        console.log("🎯 FIN DÉTECTÉE DANS checkLogs() - Arrêt dans 7 secondes");
                        shouldStopPolling = true;
                        setTimeout(() => {
                           console.log("🏁 Affichage résultat final après 7 secondes");
                           hideSpinner();
                           hideStatus();
                           showResult(isSuccessEnd, lastMessage.message, phpIsUpdate);
                           if (globalTimeoutId) {
                              console.log("⏰ Timeout global annulé");
                              clearTimeout(globalTimeoutId);
                           }
                        },7000);

                        // ⭐⭐ DÉMARRER LE TRAITEMENT DES DERNIERS MESSAGES AVANT ARRÊT
                        if (!isProcessingQueue && messageQueue.length > 0) {
                           console.log("🚀 Lancement processMessageQueue() pour les derniers messages");
                           processMessageQueue();
                        }
                        return; // ⭐️ ARRÊT IMMÉDIAT
                     }
                     if (!isProcessingQueue) {
                        console.log("🚀 Lancement processMessageQueue()");
                        processMessageQueue();
                     } else {
                        console.log("⏳ processMessageQueue() déjà en cours");
                     } 
                  }
                  else {
                     console.log("📭 Aucun nouveau message");
                  }
                  // ⭐️ CONTINUER LE POLLING SI PAS ARRÊTÉ
                  if (!shouldStopPolling) {
                     const nextDelay = messageQueue.length > 0 ? 1000 : 3000;
                     console.log("⏱️ Prochain checkLogs() dans", nextDelay, "ms");
                     setTimeout(checkLogs, nextDelay);
                  } else {
                     console.log("🛑 Plus de polling - shouldStopPolling = true");
                  }
               })
               .catch(error => {
                  if (!shouldStopPolling) {
                     console.error("💥 ERREUR:", error);
                     updateStatus("⏳ Reconnexion au serveur...");
                     console.log("🔁 Reconnexion dans 5s");
                     setTimeout(checkLogs, 5000);
                  }
               });
            }

         function showResult(success, message, isUpdate) {
            const progressContainer = document.querySelector(".progress-container");
            if (progressContainer)
               progressContainer.style.display = "none";
            resultElement.style.display = "block";
            resultElement.className = success ? "success" : "error";
            if (success) {
               if (isUpdate) {
                  resultElement.innerHTML = "<h2>🚀 '.t('deployment_complete').'!</h2><p>" + message + "</p>" +
                  "<p><a href=\"../../admin.php?op=maj&version='.$version.'&action=success\" class=\"btn btn-success\">'.t('go_admin').'</a></p>";
               } else {
                  resultElement.innerHTML = "<h2>🚀 '.t('deployment_complete').'!</h2><p>" + message + "</p>" +
                  "<p><a href=\"'.$targetDir.'/install.php?langue='.$lang.'&stage=1\" class=\"btn btn-success\">'.t('go_install').'</a></p>";
               }
            } else {
               resultElement.innerHTML = "<h2>❌ '.t('deployment_failed').'</h2><p>" + message + "</p>" +
               "<p><a href=\"?\" class=\"btn btn-secondary\">'.t('go_back').'</a></p>";
            }
            // Scroller vers le résultat
            resultElement.scrollIntoView({ behavior: "smooth" });
         }
         // ⭐⭐ DÉMARRAGE
         updateStatus("Initialisation du déploiement...");
         setTimeout(checkLogs, 1000);

         // Timeout global de sécurité
         globalTimeoutId = setTimeout(() => {
            updateStatus("💥 Déploiement trop long - vérifiez les logs serveur");
            showResult(false, "Timeout après 7 minutes - Le déploiement peut continuer en arrière-plan");
         }, 420000);

         // Lancer le déploiement
         setTimeout(() => {
            const apiUrl = "?api=deploy&version=' . urlencode($githubVersion) . '&path=' . urlencode($targetDir) . '&confirm=yes&deploy_id=' . $deploymentId . '&nocache=" + Date.now();
            fetch(apiUrl)
            .then(response => {
               if (!response.ok)
                  throw new Error("HTTP error " + response.status);
               return response.json();
            })
            .then(data => {
               console.log("Déploiement lancé, suivi via logs...");
            })
            .catch(error => {
               console.error("Erreur lancement API:", error);
            });
         }, 1500);
      </script>
    '.foot_html_deploy().'
   </body>
</html>';
}

// ==================== INTERFACE PRINCIPALE ====================
function showMainInterface() {
   echo head_html();
   echo '
   <div class="section-stable">
      <h3 class="mb-0 mt-0"><span class="display-6">🧪 </span>' . t('stable_versions') . '</h3>
      <ul class="mt-0">
         <li><a href="?op=deploy&version=v.16.4&path=npds_stable">' . t('deploy_v164_stable') . '</a></li>
         <li><a href="?op=deploy&version=v.16.4&path=.">' . t('deploy_v164_root') . '</a></li>
      </ul>
   </div>
   <div class="section-dev">
      <h3 class="mb-0 mt-0"><span class="display-6">🌶 </span>' . t('development_version') . '</h3>
      <p><small>' . t('dev_warning') . '</small></p>
      <ul class="mt-0">
         <li><a href="?op=deploy&version=master&path=npds_dev">' . t('deploy_master_dev') . '</a></li>
         <li><a href="?op=deploy&version=master&path=.">' . t('deploy_master_root') . '</a></li>
      </ul>
   </div>
   <div class="section-advanced">
      <h3 class="mb-0 mt-0"><span class="display-6">⚙️ </span>' . t('advanced_options') . '</h3>
      <form method="GET">
         <div class="row ps-3">
            <div class="col-sm-3">
               <select class="form-select" name="version" aria-label="version">
                  <option value="">' . t('version') . '</option>
                  <option value="v.16.4">v.16.4</option>
                  <option value="v.16.3">v.16.3</option>
                  <option value="master">master</option>
               </select>
            </div>
            <div class="col ps-3">
               <input class="form-control mb-3 w-90" type="text" name="path" id="choix_path" placeholder="'.t('path').'... '.t('let_emptyroot').'" aria-label="path" />
            </div>
         </div>
         <div class="ps-1">
            <button class="btn btn-success mb-3" type="submit" >' . t('deploy') . '</button>
         </div>
         <input type="hidden" name="op" value="deploy" />
      </form>
   </div>
   <div class="section-maintenance">
      <h3>🔧 ' . t('maintenance') . '</h3>
      <ul>
         <li><a href="?op=clean">' . t('clean_temp') . '</a></li>
      </ul>
   </div>';
   echo foot_html();
}

// ==================== ROUTAGE PRINCIPAL ====================
try {
   $operation = $_GET['op'] ?? '';
   // ⭐⭐ VALIDATION GLOBALE - TOUTES LES REQUÊTES ⭐⭐
   $targetDir = $_GET['path'] ?? '.';
   $validation = validateTargetPath($targetDir);
   if (!$validation['valid']) {
      echo head_html();
      echo '
        <div class="section-danger">
            <h2>🚨 '.t('error').'</h2>
            <p>'.htmlspecialchars($validation['message']).'</p>
            <p>'.t('path').' : <code>'.htmlspecialchars($targetDir).'</code></p>
            <p><strong>'.t('valid_examples').' :</strong></p>
            <ul>
                <li><code>npds_stable</code> ('.t('example_subfolder').')</li>
                <li><code>npds_dev</code> ('.t('example_subfolder').')</li>
                <li><code>.</code> ('.t('example_root').')</li>
                <li><code>../autre-projet</code> ('.t('example_parent').')</li>
            </ul>
            <a href="?" class="btn btn-secondary">'.t('go_back').'</a>
        </div>';
      echo foot_html();
      exit;
   }

   switch ($operation) {
      case 'deploy': handleDeployOperation(); break;
      case 'clean': handleCleanOperation(); break;
      default: showMainInterface(); break;
   }
} catch (Exception $e) {
   error_log("ERREUR DÉPLOYEUR: " . $e->getMessage());
   echo head_html();
   echo '<div class="section-danger"><h2>💥 Erreur</h2><p>' . htmlspecialchars($e->getMessage()) . '</p></div>';
   echo foot_html();
} finally {
   // Verrou global : le supprimer seulement pour les interfaces, PAS pour l'API
   $isApiCall = isset($_GET['api']) && $_GET['api'] === 'deploy';
   if (!$isApiCall && file_exists($globalLockFile)) {
      @unlink($globalLockFile);
      error_log("🧹 Verrou global supprimé (interface): " . $globalLockFile);
   }
   // Pour l'API, le verrou global reste jusqu'à la fin du déploiement
   if ($isApiCall && file_exists($globalLockFile)) {
      error_log("🔒 Verrou global MAINtenu (API en cours): " . $globalLockFile);
   }
}
?>
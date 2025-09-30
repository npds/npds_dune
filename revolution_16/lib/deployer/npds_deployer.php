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
/* the Free Software Foundation; either version 3 of the License.       */
/*                                                                      */
/* npds_deployer.php                                                    */
/* jpb & DeepSeek 2025                                                  */
/************************************************************************/
date_default_timezone_set('Europe/Paris');
error_log("🧨 DÉPLOYEUR DÉMARRÉ - " . date('H:i:s') . " - " . $_SERVER['REQUEST_URI']);
error_log("🔍 CONFIGURATION SERVEUR:");
error_log("Server software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Inconnu'));
error_log("PHP SAPI: " . php_sapi_name());

// Compteur d'exécutions
static $execution_count = 0;
$execution_count++;
error_log("🧨 Exécution #$execution_count");

if ($execution_count > 1) {
    error_log("🚨 DÉPLOYEUR EXÉCUTÉ PLUSIEURS FOIS !");
    // Ne pas afficher le header si déjà fait
    if (!function_exists('head_html_printed')) {
        function head_html_printed() { return true; }
    } else {
        exit("Déployeur déjà en cours");
    }
}
// ==================== VÉRIFICATION IMMÉDIATE + CONTEXTE SIMPLIFIÉ ====================
$headers_already_sent = headers_sent();

// Détection basique du contexte SANS headers
function getSimpleContext() {
   // Mode CLI
   if (php_sapi_name() === 'cli') return 'cli';
   // Vérification fichiers d'installation (sans dépendances)
   $installFiles = ['config.php', 'IZ-Xinstall.ok', 'lib/constants.php', 'slogs/install.log'];
   foreach ($installFiles as $file) {
      if (file_exists($file)) {
         // NPDS installé - vérifier si admin
         if (isset($_COOKIE['admin']) || isset($_COOKIE['adm']))
            return 'update';
         else
            return 'blocked'; // Installé mais pas admin
      }
   }
   return 'deploy'; // Pas installé
}

$context = getSimpleContext();

// ==================== GESTION DU BLOCAGE (sans headers si possible) ====================
if ($context === 'blocked') {
   if (!$headers_already_sent)
      header('HTTP/1.0 403 Forbidden');
   die('
   <!DOCTYPE html>
   <html>
   <head><title>🚫 NPDS Déjà Installé</title><style>body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }</style></head>
   <body>
      <div><h1>🚫 Accès Refusé</h1><p>NPDS est déjà installé.</p><p><a href="admin.php">➡️ Accéder à l\'administration</a></p></div>
   </body>
   </html>');
}

// ==================== CONFIGURATIONS NEUTRES ====================
set_time_limit(0);
ini_set('max_execution_time', 600);
ini_set('default_socket_timeout', 600);
ini_set('memory_limit', '512M');

// ==================== DIAGNOSTIC SAFE MODE ====================
if (ini_get('safe_mode')) {
    error_log("🚨 SAFE MODE ACTIVÉ - les ini_set() peuvent être ignorés!");
} else {
    error_log("✅ Safe mode désactivé - limites PHP modifiables");
}
// Vérification que les limites sont bien appliquées
error_log("🔍 LIMITES APPLIQUÉES:");
error_log("memory_limit: " . ini_get('memory_limit'));
error_log("max_execution_time: " . ini_get('max_execution_time'));
error_log("default_socket_timeout: " . ini_get('default_socket_timeout'));
// ==================== HEADERS UNIQUEMENT EN MODE STANDALONE ====================
if (!$headers_already_sent && ($context === 'deploy' || $context === 'update')) {
   // Session
   if (session_status() === PHP_SESSION_NONE)
      session_start();
   // Headers de sécurité
   header('Content-Type: text/html; charset=utf-8');
   header('Cache-Control: no-cache, no-store, must-revalidate');
   header('Pragma: no-cache');
   header('Expires: 0');
   header('X-Robots-Tag: noindex, nofollow');
   header('X-Content-Type-Options: nosniff');
   header('X-Accel-Buffering: no');
   header('Connection: keep-alive');
   header('Keep-Alive: timeout=300, max=1000');
   
   // Bufferisation
   if (ob_get_level() > 0) ob_end_clean();
   ob_start();
   ini_set('zlib.output_compression', '0');
} else {
   // Mode inclusion - configuration minimale
   //ini_set('zlib.output_compression', '0');
}

/**
* Vérifie si NPDS est installé de manière robuste (version complète)
*/
function checkIfNPDSInstalled() {
   $installFiles = [
      'config.php', 'IZ-Xinstall.ok', 'lib/constants.php', 'slogs/install.log',
      '../config.php', '../IZ-Xinstall.ok',
   ];
   foreach ($installFiles as $file) {
      if (file_exists($file)) return true;
   }
   return false;
}

/**
* Initialisation complète du contexte (pour usage interne)
*/
function initializeContext() {
   // Cette fonction peut être utilisée dans le code, mais pas pour les headers
   $isInstalled = checkIfNPDSInstalled();
   if (php_sapi_name() === 'cli') return 'cli';
   if (!$isInstalled) return 'deploy';
   if (isset($_COOKIE['admin']) || isset($_COOKIE['adm'])) return 'update';
   return 'blocked';
}
/**
* Affiche l'erreur "déjà installé"
*/
function showAlreadyInstalledError() {
   // Vérifier si on peut encore envoyer des headers
   if (!headers_sent())
      header('HTTP/1.0 403 Forbidden');
   if (isset($_SERVER['REQUEST_METHOD'])) {
      die('
            <!DOCTYPE html>
            <html>
            <head>
                <title>🚫 NPDS Déjà Installé</title>
                <style>
                    body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
                    .container { max-width: 600px; margin: 0 auto; }
                </style>
            </head>
            <body>
                <div class="container">
                    <h1>🚫 Accès Refusé</h1>
                    <p>NPDS est déjà installé sur ce site.</p>
                    <p>Le déployeur ne peut être utilisé que pour une nouvelle installation.</p>
                    <p>Si vous souhaitez réinstaller, supprimez d\'abord les fichiers indicateurs d\'installation.</p>
                    <p><small>Fichiers indicateurs: config.php, IZ-Xinstall.ok, etc.</small></p>
                    <p><a href="admin.php">➡️ Accéder à l\'administration NPDS</a></p>
                </div>
            </body>
            </html>
        ');
   }
   return true;
}

// ==================== GESTION DE LA LANGUE ====================
// Définition des traductions
$translations = [
   'fr' => [
      'access_url' => 'URL d\'accès',
      'advanced_options' => 'Options avancées',
      'already_in_progress' => 'Déploiement déjà en cours depuis',
      'already_installed_explanation' => 'Le déployeur ne peut être utilisé que pour une nouvelle installation.',
      'already_installed_message' => 'NPDS est déjà installé sur ce site.',
      'already_installed_reinstall' => 'Si vous souhaitez réinstaller, supprimez d\'abord le fichier',
      'already_installed_title' => '🚫 NPDS Déjà Installé',
      'clean_confirm' => 'Confirmez le nettoyage avec &confirm=yes',
      'clean_temp' => 'Nettoyer fichiers temporaires',
      'cleanup_error' => 'Erreur nettoyage',
      'connection_lost' => 'Connexion client perdue',
      'copied' => 'Copie',
      'copy_complete' => 'Copie terminée',
      'copy_error' => 'Impossible de copier',
      'copy_finished' => 'Copie terminée',
      'copy_started' => 'Début de la copie',
      'copying_files' => 'Début de la copie des fichiers',
      'deploy_master_dev' => 'Déployer MASTER dans /npds_dev',
      'deploy_master_root' => 'Déployer MASTER à la racine',
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
      'development_version' => 'VERSION DÉVELOPPEMENT',
      'download_success' => 'Téléchargement réussi',
      'downloading' => 'Téléchargement',
      'error' => 'Erreur',
      'extracting' => 'Début de l\'extraction',
      'extraction_complete' => 'Extraction terminée avec succès',
      'extraction_error' => 'Erreur d\'extraction',
      'extraction_finished' => 'Extraction terminée',
      'extraction_progress' => 'Extraction de l\'archive (3-4 minutes)',
      'extraction_success' => 'Extraction réussie',
      'failed_download' => 'Échec du téléchargement',
      'file_download_finished' => 'Téléchargement terminé',
      'file_download_start' => 'Début du téléchargement',
      'files' => 'fichiers',
      'finish_installation' => 'Pour terminer l\'installation',
      'folders' => 'dossiers',
      'initializing' => 'Initialisation du téléchargement',
      'invalid_zip' => 'Le contenu n\'est pas une archive ZIP valide',
      'items_installed' => 'éléments installés',
      'launch_installation' => 'Lancer l\'installation de NPDS',
      'let_emptyroot' => 'laisser vide pour racine',
      'lock_error' => 'Impossible de créer le verrou de sécurité',
      'lock_expired' => 'Verrou expiré et supprimé',
      'lock_expired' => 'Verrou expiré supprimé',
      'maintenance' => 'Maintenance',
      'master_warning' => 'Master : Version de développement, peut être instable - Ne pas utiliser en production!',
      'max_exec_time' => 'Temps maxi d\'exécution',
      'memory_limit' => 'Mémoire limite',
      'no_files_to_copy' => 'Aucun fichier à copier dans',
      'no_folder_in_archive' => 'Aucun dossier trouvé dans l\'archive',
      'overwrite_warning' => 'Le déploiement écrase les fichiers existants!',
      'path' => 'Chemin',
      'processing_result' => 'Traitement terminé, analyse du résultat',
      'security_warning' => 'Sécurité : Ajoutez &confirm=yes pour lancer le déploiement',
      'server' => 'Serveur',
      'stable_versions' => 'Versions stables',
      'start_extraction' => 'Début extraction',
      'success' => 'Déploiement réussi',
      'system_info' => 'Info système',
      'target_dir_error' => 'Impossible de créer le répertoire cible',
      'target_permission_error' => 'Répertoire cible non accessible en écriture',
      'temp_dir_error' => 'Impossible de créer le répertoire temporaire',
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
      'clean_confirm' => 'Confirm cleanup with &confirm=yes',
      'clean_temp' => 'Clean temporary files',
      'cleanup_error' => 'Cleanup error',
      'connection_lost' => 'Client connection lost',
      'copied' => 'copied',
      'copy_complete' => 'Copy complete',
      'copy_error' => 'Cannot copy',
      'copy_finished' => 'Copy finished',
      'copy_started' => 'Copy started',
      'copying_files' => 'Starting file copy',
      'deploy_master_dev' => 'Deploy MASTER in /npds_dev',
      'deploy_master_root' => 'Deploy MASTER at root',
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
      'development_version' => 'DEVELOPMENT VERSION',
      'download_success' => 'Download successful',
      'downloading' => 'Downloading',
      'error' => 'Error',
      'extracting' => 'Starting extraction',
      'extraction_complete' => 'Extraction completed successfully',
      'extraction_error' => 'Extraction error',
      'extraction_finished' => 'Extraction finished',
      'extraction_progress' => 'Extracting archive (3-4 minutes)',
      'extraction_success' => 'Extraction successful',
      'failed_download' => 'Download failed',
      'file_download_finished' => 'Download finished',
      'file_download_start' => 'Start download',
      'files' => 'files',
      'finish_installation' => 'To finish installation',
      'folders' => 'folders',
      'initializing' => 'Initializing download',
      'invalid_zip' => 'Content is not a valid ZIP archive',
      'items_installed' => 'items installed',
      'launch_installation' => 'Launch NPDS installation',
      'let_emptyroot' => 'leave empty for root',
      'lock_error' => 'Cannot create security lock',
      'lock_expired' => 'Lock expired and removed',
      'lock_expired' => 'Lock expired removed',
      'maintenance' => 'Maintenance',
      'master_warning' => 'Master: Development version, may be unstable - Do not use in production!',
      'max_exec_time' => 'Max execution time',
      'memory_limit' => 'Memory limit', 
      'no_files_to_copy' => 'No files to copy in',
      'no_folder_in_archive' => 'No folder found in archive',
      'overwrite_warning' => 'Deployment overwrites existing files!',
      'path' => 'Path',
      'processing_result' => 'Processing complete, analyzing result',
      'security_warning' => 'Security: Add &confirm=yes to launch deployment',
      'stable_versions' => 'Stable versions',
      'start_extraction' => 'Start extraction',
      'success' => 'Deployment successful',
      'system_info' => 'System info',
      'target_dir_error' => 'Cannot create target directory',
      'target_permission_error' => 'Target directory not writable',
      'temp_dir_error' => 'Cannot create temporary directory',
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
      'clean_confirm' => 'Confirme la limpieza con &confirm=yes',
      'clean_temp' => 'Limpiar archivos temporales',
      'cleanup_error' => 'Error de limpieza',
      'connection_lost' => 'Conexión cliente perdida',
      'copied' => 'copiado',
      'copy_complete' => 'Copia completada',
      'copy_error' => 'No se puede copiar',
      'copy_finished' => 'Copia terminada',
      'copy_started' => 'Copia iniciada',
      'copying_files' => 'Iniciando copia de archivos',
      'deploy_master_dev' => 'Implementar MASTER en /npds_dev',
      'deploy_master_root' => 'Implementar MASTER en raíz',
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
      'development_version' => 'VERSIÓN DE DESARROLLO',
      'download_success' => 'Descarga exitosa',
      'downloading' => 'Descargando',
      'error' => 'Error',
      'extracting' => 'Iniciando extracción',
      'extraction_complete' => 'Extracción completada con éxito',
      'extraction_error' => 'Error de extracción',
      'extraction_finished' => 'Extracción terminada',
      'extraction_progress' => 'Extrayendo archivo (3-4 minutos)',
      'extraction_success' => 'Extracción exitosa',
      'failed_download' => 'Descarga fallida',
      'file_download_finished' => 'Descarga terminada',
      'file_download_start' => 'Inicio descarga',
      'files' => 'archivos',
      'finish_installation' => 'Para finalizar la instalación',
      'folders' => 'carpetas',
      'initializing' => 'Inicializando descarga',
      'invalid_zip' => 'El contenido no es un archivo ZIP válido',
      'items_installed' => 'elementos instalados',
      'launch_installation' => 'Iniciar instalación de NPDS',
      'let_emptyroot' => 'dejar vacío para raíz',
      'lock_error' => 'No se puede crear el bloqueo de seguridad',
      'lock_expired' => 'Bloqueo expirado eliminado',
      'lock_expired' => 'Bloqueo expirado y eliminado',
      'maintenance' => 'Mantenimiento',
      'master_warning' => 'Master: Versión de desarrollo, puede ser inestable - ¡No usar en producción!',
      'max_exec_time' => 'Tiempo máximo de ejecución',
      'memory_limit' => 'Límite de memoria',
      'no_files_to_copy' => 'No hay archivos para copiar en',
      'no_folder_in_archive' => 'No se encontró carpeta en el archivo',
      'overwrite_warning' => '¡La implementación sobrescribe los archivos existentes!',
      'path' => 'Ruta',
      'processing_result' => 'Procesamiento completado, analizando resultado',
      'security_warning' => 'Seguridad: Agregue &confirm=yes para iniciar la implementación',
      'server' => 'Servidor',
      'stable_versions' => 'Versiones estables',
      'start_extraction' => 'Inicio extracción',
      'success' => 'Implementación exitosa',
      'system_info' => 'Información del sistema',
      'target_dir_error' => 'No se puede crear el directorio de destino',
      'target_permission_error' => 'Directorio de destino sin permisos de escritura',
      'temp_dir_error' => 'No se puede crear el directorio temporal',
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
      'clean_confirm' => 'Bereinigung mit &confirm=yes bestätigen',
      'clean_temp' => 'Temporäre Dateien bereinigen',
      'cleanup_error' => 'Bereinigungsfehler',
      'connection_lost' => 'Client-Verbindung verloren',
      'copied' => 'kopiert',
      'copy_complete' => 'Kopie abgeschlossen',
      'copy_error' => 'Kann nicht kopiert werden',
      'copy_finished' => 'Kopie beendet',
      'copy_started' => 'Kopie gestartet',
      'copying_files' => 'Beginne Dateikopie',
      'deploy_master_dev' => 'Stelle MASTER in /npds_dev bereit',
      'deploy_master_root' => 'Stelle MASTER im Stammverzeichnis bereit',
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
      'development_version' => 'ENTWICKLUNGSVERSION',
      'download_success' => 'Download erfolgreich',
      'downloading' => 'Herunterladen',
      'error' => 'Fehler',
      'extracting' => 'Beginne Extraktion',
      'extraction_complete' => 'Extraktion erfolgreich abgeschlossen',
      'extraction_error' => 'Extraktionsfehler',
      'extraction_finished' => 'Extraktion beendet',
      'extraction_progress' => 'Extrahiere Archiv (3-4 Minuten)',
      'extraction_success' => 'Extraktion erfolgreich',
      'failed_download' => 'Download fehlgeschlagen',
      'file_download_finished' => 'Download beendet',
      'file_download_start' => 'Download starten',
      'files' => 'Dateien',
      'finish_installation' => 'Um die Installation abzuschließen',
      'folders' => 'Ordner',
      'initializing' => 'Initialisiere Download',
      'invalid_zip' => 'Inhalt ist kein gültiges ZIP-Archiv',
      'items_installed' => 'Elemente installiert',
      'launch_installation' => 'NPDS-Installation starten',
      'let_emptyroot' => 'leer lassen für Stammverzeichnis',
      'lock_error' => 'Sicherheitssperre kann nicht erstellt werden',
      'lock_expired' => 'Sperre abgelaufen entfernt',
      'lock_expired' => 'Sperre abgelaufen und entfernt',
      'maintenance' => 'Wartung',
      'master_warning' => 'Master: Entwicklungsversion, kann instabil sein - Nicht in der Produktion verwenden!',
      'max_exec_time' => 'Maximale Ausführungszeit',
      'memory_limit' => 'Speicherlimit',
      'no_files_to_copy' => 'Keine Dateien zum Kopieren in',
      'no_folder_in_archive' => 'Kein Ordner im Archiv gefunden',
      'overwrite_warning' => 'Bereitstellung überschreibt vorhandene Dateien!',
      'path' => 'Pfad',
      'processing_result' => 'Verarbeitung abgeschlossen, analysiere Ergebnis',
      'security_warning' => 'Sicherheit: Fügen Sie &confirm=yes hinzu, um die Bereitstellung zu starten',
      'server' => 'Server',
      'stable_versions' => 'Stabile Versionen',
      'start_extraction' => 'Extraktion starten',
      'success' => 'Bereitstellung erfolgreich',
      'system_info' => 'Systeminformationen',
      'target_dir_error' => 'Zielverzeichnis kann nicht erstellt werden',
      'target_permission_error' => 'Zielverzeichnis nicht beschreibbar',
      'temp_dir_error' => 'Temporäres Verzeichnis kann nicht erstellt werden',
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
      'clean_confirm' => '使用 &confirm=yes 确认清理',
      'clean_temp' => '清理临时文件',
      'cleanup_error' => '清理错误',
      'connection_lost' => '客户端连接丢失',
      'copied' => '已复制',
      'copy_complete' => '复制完成',
      'copy_error' => '无法复制',
      'copy_finished' => '复制完成',
      'copy_started' => '复制开始',
      'copying_files' => '开始文件复制',
      'deploy_master_dev' => '在 /npds_dev 中部署 MASTER',
      'deploy_master_root' => '在根目录部署 MASTER',
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
      'download_success' => '下载成功',
      'downloading' => '下载中',
      'error' => '错误',
      'extracting' => '开始解压',
      'extraction_complete' => '提取成功完成',
      'extraction_error' => '解压错误',
      'extraction_finished' => '提取完成',
      'extraction_progress' => '解压文件中（3-4分钟）',
      'extraction_success' => '提取成功',
      'failed_download' => '下载失败',
      'file_download_finished' => '下载完成',
      'file_download_start' => '开始下载',
      'files' => '文件',
      'finish_installation' => '完成安装',
      'folders' => '文件夹',
      'initializing' => '初始化下载',
      'invalid_zip' => '内容不是有效的ZIP存档',
      'items_installed' => '个项目已安装',
      'launch_installation' => '启动NPDS安装',
      'let_emptyroot' => '留空为根目录',
      'lock_error' => '无法创建安全锁',
      'lock_expired' => '锁定已过期并删除',
      'lock_expired' => '锁定已过期并已删除',
      'maintenance' => '维护',
      'master_warning' => 'Master：开发版本，可能不稳定 - 请勿在生产环境中使用！',
      'max_exec_time' => '最大执行时间',
      'memory_limit' => '内存限制',
      'no_files_to_copy' => '没有文件可复制到',
      'no_folder_in_archive' => '在存档中未找到文件夹',
      'overwrite_warning' => '部署会覆盖现有文件！',
      'path' => '路径',
      'processing_result' => '处理完成，分析结果中',
      'security_warning' => '安全：添加 &confirm=yes 以启动部署',
      'server' => '服务器',
      'stable_versions' => '稳定版本',
      'start_extraction' => '开始提取',
      'success' => '部署成功',
      'system_info' => '系统信息',
      'target_dir_error' => '无法创建目标目录',
      'target_permission_error' => '目标目录不可写',
      'temp_dir_error' => '无法创建临时目录',
      'version' => '版本',
      'warning' => '警告',
      'welcome' => '部署',
      'write_error' => '无法写入文件',
      'zip_open_error' => '无法打开ZIP存档',
   ],
];
// Défaut: français
$lang = $_GET['lang'] ?? $_SESSION['npds_lang'] ?? 'fr';
// Validation
if (!in_array($lang, ['fr', 'en', 'es', 'de', 'zh']))
    $lang = 'fr';
// Sauvegarde en session
$_SESSION['npds_lang'] = $lang;

// ==================== CONFIGURATION DES EXCLUSIONS ====================
class NPDSExclusions {
   private static $excludedFiles = [
    // ⭐⭐ Ne pas couper la branche sur laquelle on est assis ⭐⭐
/*
     'lib/deployer/',
     'lib/deployer/*',
     'lib/deployer/npds-deployer.php',
*/
      // === FICHIERS/DOSSIERS INSTALLATION AUTO ===
      'install/',                 // installation automatique
      'install.php',              // installation automatique
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
      'users_private/',           // Données utilisateurs et groupes
      'slogs/',                   // Logs
      'cache/',                   // Cache système
      'meta/',                    // Stockage metatags
      // === FICHIERS/DOSSIERS CONFIGURATION ET DATA MODULES (À PRÉSERVER) ===
      'modules/archive-stories/archive-stories.conf.php',
      'modules/archive-stories/cache.timings.php',
      'modules/geoloc/geoloc.conf',
      'modules/npds_twi/twi_conf.php',
      'modules/push/push.conf.php',
      'modules/push/push.js',
      'modules/reseaux-sociaux/reseaux-sociaux.conf.php',
      'modules/sform/contact/',
      'modules/sform/forum/',
      'modules/upload/upload.conf.php',
      'modules/upload/tmp/',
      'modules/upload/upload/',
      'modules/upload/upload_forum/',
      'modules/upload/include_editeur/upload.conf.editeur.php',
      'modules/upload/include_forum/upload.conf.forum.php',
      'modules/wspad/config.php',
      'modules/wspad/locks/',
      // === FICHIERS DOSSIERS CONFIGURATION ET DATA DES LIB ===
      'lib/PHPMailer/PHPmailer.conf.php',     // conf npds de la lib
      'lib/PHPMailer/key/',                   // stockage keys
      'lib/js/npds_tarteaucitron.js',         // paramètre initialisation
      'lib/js/npds_tarteaucitron_service.js', // paramètre services
      // === FICHIERS PERSONNALISÉS ===
      'language/lang-mods.php',   // fichiers langue personnalisable
      'language/lang-multi.php',  // fichiers langue personnalisable
      'static/edito.txt',         // page statique
      // === BACKUPS ET SAUVEGARDES === ????
      'backup/',
      'sauvegardes/',
      '*.sql',                    // Tous les fichiers SQL
      '*.zip',                    // Archives de backup
      '*.tar.gz',
      '*.backup*',               // Fichiers de backup existants
   ];

   /**
   * Vérifie si un fichier doit être exclu de l'écrasement
   * UNIQUEMENT en mise à jour
   */
   public static function shouldExclude($filePath, $version = null, $isUpdate = false) {
      // 🔥 IMPORTANT : En installation neuve, AUCUNE exclusion
      if (!$isUpdate)
         return false; // Tout peut être écrasé
      // 🔥 Seulement en mise à jour : vérifier les exclusions
      foreach (self::$excludedFiles as $pattern) {
         if (self::matchesPattern($filePath, $pattern)) {
            error_log("🔒 Fichier exclu en mise à jour: $filePath");
            return true;
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

// ==================== VÉRIFICATION DU RÉCEPTACLE ====================
class InstallationValidator {
   private static $npdsFirstLevel = [
    // === FICHIERS RACINE ===
    'abla.log.php', 'abla.php', 'admin.php', 'article.php', 'auth.inc.php', 'auth.php', 'autodoc.php', 'backend.php', 'banners.php', 'cache.class.php', 'cache.config.php', 'cache.timings.php', 'carnet.php', 'chat.php', 'chatinput.php', 'chatrafraich.php', 'chattop.php', 'config.php', 'counter.php', 'download.php', 'editpost.php', 'faq.php', 'filemanager.conf', 'footer.php', 'forum.php', 'friend.php', 'functions.php', 'getfile.php', 'grab_globals.php', 'header.php', 'humans.txt', 'index.php', 'install.php', 'licence-english.txt', 'licence-french.txt', 'licence.txt', 'lnl.php', 'mainfile.php', 'map.php', 'memberslist.php', 'minisite.php', 'modules.php', 'more_emoticon.php', 'newtopic.php', 'npds_api.php', 'pollBooth.php', 'powerpack_f.php', 'powerpack.php', 'preview.php', 'print.php', 'prntopic.php', 'publication.php', 'readpmsg_imm.php', 'readpmsg.php', 'reply.php', 'replyH.php', 'replypmsg.php', 'reviews.php', 'robots.txt', 'sample.proxy.conf.php', 'search.php', 'searchbb.php', 'sections.config.php', 'sections.php', 'signat.php', 'sitemap.php', 'static.php', 'stats.php', 'submit.php', 'top.php', 'topicadmin.php', 'topics.php', 'user.php', 'viewforum.php', 'viewpmsg.php', 'viewtopic.php', 'viewtopicH.php',
    // === DOSSIERS RACINE ===
    'admin', 'api', 'cache', 'editeur', 'images', 'install', 'language', 'lib', 'manuels', 'meta', 'modules', 'slogs', 'sql', 'static', 'themes', 'users_private',
   ];
   private static $serverAllowed = [
        '.htaccess', 'robots.txt', '.well-known', '.git', '.github',
        'README', 'LICENSE', 'composer.json', 'package.json', 'web.config'
   ];

   /**
   * Vérifie si le réceptacle est propre pour l'installation
   */
   public static function validateReceptacle($targetDir) {
      if (!is_dir($targetDir)) 
         return ['safe' => true, 'warnings' => []]; // Dossier vide
      $existingItems = scandir($targetDir);
      $existingItems = array_diff($existingItems, ['.', '..']); // Retirer . et ..
      $warnings = [];
      $allowedFound = [];
      foreach ($existingItems as $item) {
         $fullPath = $targetDir . '/' . $item;
         // Vérifier si c'est un fichier/dossier autorisé (serveur)
         if (in_array($item, self::$serverAllowed) || 
                in_array(pathinfo($item, PATHINFO_EXTENSION), ['log', 'txt', 'md'])) {
                $allowedFound[] = $item;
                continue;
         }
         // Vérifier si c'est un élément NPDS (conflit potentiel)
         if (in_array($item, self::$npdsFirstLevel))
            $warnings[] = [
               'type' => 'conflit_npds',
               'item' => $item,
               'message' => 'Ce fichier/dossier existe déjà dans NPDS et sera écrasé'
            ];
         else
            $warnings[] = [
               'type' => 'element_etranger', 
               'item' => $item,
               'message' => 'Élément non-NPDS détecté - risque de conflit'
            ];
      }
      return [
         'safe' => empty($warnings),
         'warnings' => $warnings,
         'allowed_items' => $allowedFound
      ];
   }
}

// ==================== GESTION DES BACKUPS ====================
class NPDSBackupManager {
   private $backupDir = 'npds_backups';
   private $maxDbSizeMB = 50; // Taille max pour backup DB automatique

   public function __construct($customBackupDir = null) {
      if ($customBackupDir)
         $this->backupDir = $customBackupDir;
      else
         $this->backupDir = dirname(__FILE__) . '/npds_backups';
      if (!is_dir($this->backupDir))
         @mkdir($this->backupDir, 0755, true);
      // Log pour debug
      error_log("📁 Backup path: " . $this->backupDir);
   }

   /**
   * Estime le nombre total de fichiers à backuper
   */
   private function estimateTotalBackupFiles($targetDir) {
      $criticalFiles = $this->getCriticalFilesList($targetDir);
      $totalEstimate = 0;
      foreach ($criticalFiles as $filePattern) {
         $files = glob($targetDir . '/' . $filePattern);
         foreach ($files as $file) {
            if (is_file($file))
               $totalEstimate++;
            elseif (is_dir($file)) {
               try {
               $iterator = new RecursiveIteratorIterator(
                  new RecursiveDirectoryIterator($file, RecursiveDirectoryIterator::SKIP_DOTS)
               );
               $totalEstimate += iterator_count($iterator);
               } catch (Exception $e) {
                  error_log("⚠️ Impossible de compter les fichiers dans: $file - " . $e->getMessage());
                  $totalEstimate += 10; // Estimation de secours
               }
            }
         }
      }
       return max($totalEstimate, 1); // Au moins 1 pour éviter division par zéro
   }

   public function getBackupDir() {
      return $this->backupDir;
   }

   /**
   * Crée un backup de la base de données (si elle n'est pas trop grosse)
   */
    public function backupDatabase($maxSizeMB = null) {
        global $lang;
        
        if ($maxSizeMB) {
            $this->maxDbSizeMB = $maxSizeMB;
        }
        
        // Vérifier si config.php existe pour récupérer les infos DB
        if (!file_exists('config.php')) {
            error_log("❌ config.php non trouvé - backup DB ignoré");
            return ['success' => false, 'message' => 'Config DB non trouvée'];
        }
        
        // Vérifier la taille de la DB (estimation sécurisée)
        $dbSize = $this->estimateDatabaseSize();
        $maxSizeBytes = $this->maxDbSizeMB * 1024 * 1024;
        
        if ($dbSize > $maxSizeBytes) {
            error_log("⚠️ Base trop volumineuse ($dbSize bytes > $maxSizeBytes bytes) - backup DB ignoré");
            return [
                'success' => false, 
                'message' => t('backup_skipped_large_db', $lang),
                'size' => $dbSize,
                'max_size' => $maxSizeBytes
            ];
        }
        
        $timestamp = date('Y-m-d_His');
        $backupFile = $this->backupDir . '/db_backup_' . $timestamp . '.sql';
        
        try {
            // Tentative de backup via mysqldump
            $command = $this->buildDumpCommand($backupFile);
            
            if ($command && $this->executeBackupCommand($command)) {
                $size = filesize($backupFile);
                error_log("✅ Backup DB créé: $backupFile ($size bytes)");
                
                return [
                    'success' => true,
                    'message' => t('backup_db_created', $lang),
                    'file' => $backupFile,
                    'size' => $size
                ];
            } else {
                // Fallback: backup manuel des tables principales
                return $this->createManualBackup($backupFile);
            }
        } catch (Exception $e) {
            error_log("❌ Erreur backup DB: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

   /**
   * Crée un backup des fichiers critiques
   */
/*    public function backupCriticalFiles($targetDir) {
        global $lang;
        $timestamp = date('Y-m-d_His');
        $backupFile = $this->backupDir . '/files_backup_' . $timestamp . '.zip';
        try {
            $zip = new ZipArchive();
            if ($zip->open($backupFile, ZipArchive::CREATE) === true) {
                $addedFiles = 0;
                $fileCount = 0;
                // Backup des fichiers critiques
                $criticalFiles = $this->getCriticalFilesList($targetDir);
                foreach ($criticalFiles as $filePattern) {
                  $files = glob($targetDir . '/' . $filePattern);
                  foreach ($files as $file) {
                     $fileCount++;
                     // KeepAlive toutes les 50 fichiers
                     if ($fileCount % 50 === 0) {
                        echo '<script>console.log("💾 Backup: ' . $fileCount . ' fichiers");</script>';
                        flush();
                        error_log("💾 Backup: ' . $fileCount . ' fichiers");
                     }
                  }
                     $addedFiles += $this->addFilesToZip($zip, $targetDir, $filePattern);
               }
                $zip->close();
                $size = filesize($backupFile);
                error_log("✅ Backup fichiers créé: $backupFile ($size bytes, $addedFiles fichiers)");
                return [
                    'success' => true,
                    'message' => t('backup_files_created', $lang),
                    'file' => $backupFile,
                    'size' => $size,
                    'file_count' => $addedFiles
                ];
            }
        } catch (Exception $e) {
            error_log("❌ Erreur backup fichiers: " . $e->getMessage());
        }
        return ['success' => false, 'message' => 'Échec création backup fichiers'];
   } */
   public function backupCriticalFiles($targetDir) {
      global $lang;
      $timestamp = date('Y-m-d_His');
      $backupFile = $this->backupDir . '/files_backup_' . $timestamp . '.zip';
      try {
         $zip = new ZipArchive();
         if ($zip->open($backupFile, ZipArchive::CREATE) === true) {
            $addedFiles = 0;
            $fileCount = 0;
            $totalEstimate = $this->estimateTotalBackupFiles($targetDir);
            // Backup des fichiers critiques
            $criticalFiles = $this->getCriticalFilesList($targetDir);
            foreach ($criticalFiles as $filePattern) {
               $files = glob($targetDir . '/' . $filePattern);
               foreach ($files as $file) {
                  $fileCount++;
                  // KeepAlive avec progression interface
                  if ($fileCount % 50 === 0) {
                     $percent = round(($fileCount / $totalEstimate) * 100);
                     echo '<script>document.getElementById("progress").innerHTML = "💾 Backup: ' . $percent . '% (' . $fileCount . '/' . $totalEstimate . ' fichiers)";</script>';
                     echo ' ';
                     flush();
                  }
                  // Ajout au ZIP...
                  if (is_file($file)) {
                     $relativePath = str_replace($targetDir . '/', '', $file);
                     if ($zip->addFile($file, $relativePath))
                        $addedFiles++;
                  } elseif (is_dir($file)) {
                     // Ajout récursif du dossier
                     $iterator = new RecursiveIteratorIterator(
                        new RecursiveDirectoryIterator($file, RecursiveDirectoryIterator::SKIP_DOTS),
                        RecursiveIteratorIterator::SELF_FIRST
                     );

                     foreach ($iterator as $item) {
                        if ($item->isFile()) {
                           $fileCount++;
                           $relativePath = str_replace($targetDir . '/', '', $item->getRealPath());
                           if ($zip->addFile($item->getRealPath(), $relativePath))
                              $addedFiles++;
                           // KeepAlive aussi dans les sous-dossiers
                           if ($fileCount % 50 === 0) {
                              $percent = round(($fileCount / $totalEstimate) * 100);
                              echo '<script>document.getElementById("progress").innerHTML = "💾 Backup: ' . $percent . '% (' . $fileCount . '/' . $totalEstimate . ' fichiers)";</script>';
                              echo ' ';
                              flush();
                           }
                        }
                     }
                 }
             }
         }
         // Message final
         echo '<script>document.getElementById("progress").innerHTML = "💾 Backup terminé: ' . $addedFiles . ' fichiers";</script>';
         flush();
         
         $zip->close();
         $size = filesize($backupFile);
            
         error_log("✅ Backup fichiers créé: $backupFile ($size bytes, $addedFiles fichiers)");
            
         return [
             'success' => true,
             'message' => t('backup_files_created', $lang),
             'file' => $backupFile,
             'size' => $size,
             'file_count' => $addedFiles
         ];
      }
   } catch (Exception $e) {
        error_log("❌ Erreur backup fichiers: " . $e->getMessage());
   }

   return ['success' => false, 'message' => 'Échec création backup fichiers'];
}

   /**
   * Crée un backup complet (DB + fichiers)
   */
    public function createFullBackup($targetDir) {
        $results = [];
        
        $results['files'] = $this->backupCriticalFiles($targetDir);
        $results['database'] = $this->backupDatabase();
        
        return $results;
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
            'slogs/*',
            'images/*',
            'themes/*/images/*',
            'modules/*/config.php',
            'modules/*.conf.php',
            'language/lang-*.php'
        ];
    }
    
    /**
     * Estimation sécurisée de la taille de la DB
     */
    private function estimateDatabaseSize() {
        $size = 0;
        
        // Estimation basée sur les dossiers de données
        $dataDirs = ['slogs/', 'users_private/', 'cache/', 'meta/'];
        
        foreach ($dataDirs as $dir) {
            if (is_dir($dir)) {
                $iterator = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
                );
                
                foreach ($iterator as $file) {
                    if ($file->isFile()) {
                        $size += $file->getSize();
                        // Limiter le scan pour éviter les timeouts
                        if ($size > (100 * 1024 * 1024)) { // 100MB max de scan
                            break 2;
                        }
                    }
                }
            }
        }
        
        return $size;
    }
    
    /**
     * Construction de la commande mysqldump (sécurisée)
     */
    private function buildDumpCommand($backupFile) {
        // IMPORTANT: Méthode désactivée par défaut pour sécurité
        // À n'activer que si l'environnement est sécurisé
        
        if (!file_exists('config.php')) {
            return null;
        }
        
        // Lecture sécurisée de config.php
        $configContent = file_get_contents('config.php');
        
        // Extraction basique des infos DB (simplifiée)
        preg_match('/\$user\s*=\s*[\'"]([^\'"]*)[\'"]/', $configContent, $userMatch);
        preg_match('/\$db\s*=\s*[\'"]([^\'"]*)[\'"]/', $configContent, $dbMatch);
        preg_match('/\$host\s*=\s*[\'"]([^\'"]*)[\'"]/', $configContent, $hostMatch);
        
        if (!$userMatch || !$dbMatch || !$hostMatch) {
            return null;
        }
        
        $user = $userMatch[1];
        $db = $dbMatch[1];
        $host = $hostMatch[1];
        
        // Construction de la commande (adaptée à l'environnement)
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Windows
            $command = "mysqldump -h $host -u $user -p $db > \"$backupFile\" 2>nul";
        } else {
            // Linux/Unix
            $command = "mysqldump -h $host -u $user -p $db > \"$backupFile\" 2>/dev/null";
        }
        
        return $command;
    }
    
    /**
     * Exécution sécurisée de la commande de backup
     */
    private function executeBackupCommand($command) {
        // Désactivé par défaut - trop risqué
        return false;
        
        /* 
        // Version activable si environnement contrôlé
        $output = [];
        $returnCode = 0;
        
        exec($command, $output, $returnCode);
        
        return $returnCode === 0 && file_exists($backupFile) && filesize($backupFile) > 0;
        */
    }
    
    /**
     * Backup manuel alternatif
     */
    private function createManualBackup($backupFile) {
        // Créer un backup minimal avec les infos système
        $backupContent = "-- NPDS Manual Backup - " . date('Y-m-d H:i:s') . "\n";
        $backupContent .= "-- Cette installation ne supporte pas mysqldump automatique\n";
        $backupContent .= "-- Veuillez faire un backup manuel via l'admin NPDS\n";
        
        if (file_put_contents($backupFile, $backupContent) !== false) {
            return [
                'success' => true,
                'message' => 'Backup manuel créé (veuillez utiliser l\'outil NPDS)',
                'file' => $backupFile,
                'size' => filesize($backupFile),
                'manual' => true
            ];
        }
        
        return ['success' => false, 'message' => 'Échec création backup manuel'];
    }
    
    /**
     * Ajout récursif de fichiers au ZIP
     */
    private function addFilesToZip($zip, $basePath, $pattern, $localPath = '') {
        $addedCount = 0;
        
        $files = glob($basePath . '/' . $pattern);
        foreach ($files as $file) {
            if (is_file($file)) {
                $relativePath = $localPath . basename($file);
                if ($zip->addFile($file, $relativePath)) {
                    $addedCount++;
                }
            } elseif (is_dir($file)) {
                // Ajout récursif du dossier
                $iterator = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($file, RecursiveDirectoryIterator::SKIP_DOTS),
                    RecursiveIteratorIterator::SELF_FIRST
                );
                
                foreach ($iterator as $item) {
                    if ($item->isFile()) {
                        $relativePath = $localPath . $iterator->getSubPathName();
                        if ($zip->addFile($item->getRealPath(), $relativePath)) {
                            $addedCount++;
                        }
                    }
                }
            }
        }
        
        return $addedCount;
    }
    
    /**
     * Nettoyage des vieux backups
     */
    public function cleanupOldBackups($keepLast = 5) {
        $backupFiles = glob($this->backupDir . '/*.{zip,sql}', GLOB_BRACE);
        
        // Trier par date de modification (plus récent en premier)
        usort($backupFiles, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });
        
        $deleted = 0;
        for ($i = $keepLast; $i < count($backupFiles); $i++) {
            if (@unlink($backupFiles[$i])) {
                $deleted++;
            }
        }
        
        return $deleted;
    }
}

// ==================== CLASSE PRINCIPALE DU DÉPLOYEUR ====================
class GithubDeployer {
   private $userAgent = 'Mozilla/5.0 (compatible; GitHubDownloader/1.0)';
   private $timeout = 120;
   private $connectTimeout = 30;
   private $maxRedirects = 5;
   private $tempDir = 'npds_deployer_temp';
   private $lastDownloadSize = 0;

   private function isNPDSInstalled($targetDir) {
      // Si on vient de l'admin NPDS, c'est forcément une mise à jour
      if (isset($_GET['return_url']) && strpos($_GET['return_url'], 'admin.php') !== false) {
         error_log("✅ Mise à jour détectée via return_url admin");
         return true;
      }
      // Si l'URL contient 'update' dans les paramètres
      if (isset($_GET['context']) && $_GET['context'] === 'update') {
         error_log("✅ Mise à jour détectée via paramètre context");
         return true;
      }
      error_log("❌ Nouvelle installation détectée");
      return false;
   }

   private function showInstallationWarnings($validation, $targetDir, $version) {
      // CORRECTION : Remonter à la racine si on est dans lib/deployer/
      if (basename($targetDir) === 'deployer' && basename(dirname($targetDir)) === 'lib') {
         $targetDir = dirname(dirname($targetDir));
         error_log("🔧 Correction targetDir: $targetDir");
      }
      global $lang;
      echo '
      <div class="section-danger py-2">
         <h3>🚨 Réceptacle non sécurisé détecté</h3>
            <p>Le dossier <strong>' . htmlspecialchars($targetDir) . '</strong> contient des éléments problématiques :</p>
         <div class="mt-3">
            <h4>Éléments détectés :</h4>
            <ul>';
      foreach ($validation['warnings'] as $warning) {
         $icon = $warning['type'] === 'conflit_npds' ? '🔄' : '⚠️';
         echo '
               <li>' . $icon . ' <strong>' . htmlspecialchars($warning['item']) . '</strong> : ' . $warning['message'] . '</li>';
      }
      echo '
            </ul>
         </div>';
      if (!empty($validation['allowed_items'])) {
         echo '
         <div class="mt-2">
            <h4>Éléments autorisés :</h4>
            <ul>';
         foreach ($validation['allowed_items'] as $item) {
            echo '
               <li>✅ ' . htmlspecialchars($item) . ' (fichier serveur)</li>';
         }
         echo '
            </ul>
         </div>';
      }
      echo '
         <div class="mt-4">
            <p><strong>Recommandations :</strong></p>
            <ul>
               <li>✅ Utilisez un dossier vide pour une installation propre</li>
               <li>✅ Supprimez les éléments listés ci-dessus</li>
               <li>🚨 Les fichiers NPDS du même nom seront écrasés</li>
            </ul>
            <div class="mt-3">
               <a href="?op=deploy&version=' . urlencode($version) . '&path=' . urlencode($targetDir) . '&confirm=yes&force=yes" class="btn btn-danger"  onclick="return confirm(\'🚨 FORCER L\\\'INSTALLATION ?\')">🚨 Forcer l\'installation</a>
               <a href="?" class="btn btn-secondary">Annuler</a>
            </div>
         </div>
      </div>';
   }

   public function getTempDir(): string {
      return $this->tempDir;
   }

   public function getLastDownloadSize(): int {
      return $this->lastDownloadSize;
   }

   public function __construct(array $config = []) {
      foreach ($config as $key => $value) {
         if (property_exists($this, $key))
            $this->$key = $value;
      }
      if (!is_dir($this->tempDir))
         @mkdir($this->tempDir, 0755, true);
      // Nettoyage automatique des anciens fichiers
      $this->cleanupOldFiles();
   }

   public function deployMaster(?string $targetDir = null): array {
      if ($targetDir === null)
         $targetDir = __DIR__;
      return $this->deployVersion(
         'https://github.com/npds/npds_dune/archive/refs/heads',
         'master',
         'zip',
         $targetDir
      );
   }

   /**
   * Télécharge une archive depuis GitHub avec version variable
   * et extrait uniquement le contenu du premier dossier
   */
   public function deployVersion(string $baseUrl, string $version, string $format = 'zip',? string $targetDir = null): array {
      global $lang;
      // ==================== VÉRIFICATION PRÉ-INSTALLATION ====================
      $isUpdate = $this->isNPDSInstalled($targetDir);

      if ($isUpdate) {
         echo '<li class="progress" id="backup-step">💾 Sauvegarde de sécurité...</li>';
         flush();
         error_log("💾 Backup immédiat avant tout traitement...");
         try {
            $backupManager = new NPDSBackupManager();
            $backupResult = $backupManager->backupCriticalFiles($targetDir);
            if ($backupResult['success']) {
               $sizeMB = round($backupResult['size'] / 1024 / 1024, 2);
               echo '<script>document.getElementById("backup-step").innerHTML = "✅ Backup créé: ' . $sizeMB . ' MB";</script>';
               flush();
               error_log("✅ Backup réussi: " . $backupResult['file']);
            } else {
                error_log("❌ Backup échoué - arrêt du déploiement");
                @unlink($lockFile);
                return $this->createResult(false, "Échec du backup - déploiement annulé pour sécurité");
            }
         } catch (Exception $e) {
            error_log("💥 Exception backup: " . $e->getMessage());
            @unlink($lockFile);
            return $this->createResult(false, "Erreur lors du backup: " . $e->getMessage());
         }
      }
      if (!$isUpdate) { // Installation neuve uniquement
         $validation = InstallationValidator::validateReceptacle($targetDir);
         if (!$validation['safe'] && (!isset($_GET['force']) || $_GET['force'] !== 'yes')) {
            // Afficher les warnings immédiatement
            echo head_html();
            echo '<h2 class="ms-3"><span class="display-6">🚨 </span>Vérification du réceptacle</h2>';
            $this->showInstallationWarnings($validation, $targetDir, $version);
            echo foot_html();
            @unlink($lockFile);
            return $this->createResult(false, "Réceptacle non sécurisé");
         }
      }
      // ==================== VERROUILLAGE RENFORCÉ ====================
      $lockFile = $this->tempDir . '/deploy.lock';
      $lockTimeout = 600; // 10 minutes
      // Vérifier si un déploiement est déjà en cours
      if (file_exists($lockFile)) {
         $lockTime = (int)file_get_contents($lockFile);
         $elapsed = time() - $lockTime;
         if ($elapsed < $lockTimeout) {
            error_log('💥 '. t('deployment_in_progress', $lang) . ' ' . $elapsed . "s");
            $this->logToInstallLog('💥 '. t('deployment_in_progress', $lang) . ' ' . $elapsed . "s)", 'WARNING', $targetDir);
            return $this->createResult(false, t('deployment_in_progress', $lang) . " " . $elapsed . "s)");
         } else {
            // Lock expiré, le supprimer
            @unlink($lockFile);
            error_log('🔓 ' . t('lock_expired', $lang));
            $this->logToInstallLog('🔓 ' . t('lock_expired', $lang), 'INFO', $targetDir);
         }
      }
      // Créer le verrou avec timestamp actuel
      if (!file_put_contents($lockFile, time())) {
         error_log('❌ ' . t('lock_error', $lang));
         $this->logToInstallLog('❌ ' . t('lock_error', $lang), 'ERROR', $targetDir);
         return $this->createResult(false, t('lock_error', $lang));
      }
      // ==================== LOGS DE DÉBOGAGE ====================
      error_log('=== ' . t('deployment_started',$lang) . ' ===');
      error_log(t('version',$lang) . ": $version | " . t('path',$lang) . ": " . ($targetDir ?? 'racine'));
      error_log("URL: " . $this->buildVersionUrl($baseUrl, $version, $format));
      error_log("Lock file: " . str_replace('//', '/', $lockFile));
      error_log("Temp dir: " . str_replace('//', '/', $this->tempDir));
      $this->logToInstallLog('=== ' . t('deployment_started',$lang) . ' ===', 'INFO', $targetDir);
      $this->logToInstallLog(t('version',$lang) . ": $version | " . t('path',$lang) . ": " . ($targetDir ?? 'racine'), 'INFO', $targetDir);
      $this->logToInstallLog("URL: " . $this->buildVersionUrl($baseUrl, $version, $format), 'INFO', $targetDir);

      // Validation des paramètres
      if (empty($baseUrl) || empty($version)) {
         error_log("❌ Paramètres manquants: baseUrl ou version vide");
         $this->logToInstallLog("❌ Paramètres manquants: baseUrl ou version vide", 'ERROR', $targetDir);
         return $this->createResult(false, "URL de base et version sont requis");
      }
      if (!in_array($format, ['zip', 'tar.gz'])) {
         error_log("❌ Format non supporté: $format");
         $this->logToInstallLog("❌ Format non supporté: $format", 'ERROR', $targetDir);
         return $this->createResult(false, "Format d'archive non supporté");
      }
      // Construction de l'URL complète
      $url = $this->buildVersionUrl($baseUrl, $version, $format);
      // Téléchargement du fichier
      $tempFile = $this->tempDir . '/' . uniqid('github_') . '.' . $format;
      try {
         error_log('📦 ' . t('initializing', $lang) . '...');
         $this->logToInstallLog('📦 ' . t('initializing', $lang) . '...', 'INFO', $targetDir);
         // Envoyer du feedback au navigateur
         echo '<li class="progress">📦 ' . t('initializing', $lang) . '...</li>';
         $this->keepAlive();
         // Téléchargement avec suivi des redirections
         $downloadResult = $this->downloadFile($url, $tempFile);
         if (!$downloadResult['success']) {
            error_log("❌ Échec du téléchargement: " . $downloadResult['message']);
            $this->logToInstallLog("❌ Échec du téléchargement: " . $downloadResult['message'], 'ERROR', $targetDir);
            @unlink($lockFile);
            return $downloadResult;
         }
         $this->lastDownloadSize = filesize($tempFile);
         $sizeMB = round($this->lastDownloadSize / 1024 / 1024, 2);
         error_log('✅ ' . t('download_success', $lang) . ': ' .$sizeMB. 'MB');
         $this->logToInstallLog('✅ ' . t('download_success', $lang) . ': ' .$sizeMB. 'MB', 'SUCCESS', $targetDir);
         echo '<li class="progress">✅ ' . t('download_success', $lang) . ' (' . $sizeMB . ' MB)</li>';         $this->keepAlive();
         // Vérification du fichier téléchargé
         if (!file_exists($tempFile) || filesize($tempFile) === 0) {
            error_log("❌ Fichier téléchargé vide ou inexistant");
            $this->logToInstallLog("❌ Fichier téléchargé vide ou inexistant", 'ERROR', $targetDir);
            @unlink($lockFile);
            return $this->createResult(false, "Fichier téléchargé vide ou inexistant");
         }

         // Extraction si un répertoire cible est spécifié
         if ($targetDir) {
            error_log('📂 ' . t('extracting',$lang) . '...');
            $this->logToInstallLog('📂 ' . t('extracting',$lang) . '...', 'INFO', $targetDir);
            $extractResult = $this->extractFirstFolderContent($tempFile, $targetDir, $format, $version, $isUpdate);
            if (!$extractResult['success']) {
               error_log("❌ Échec de l'extraction: " . $extractResult['message']);
               $this->logToInstallLog("❌ Échec de l'extraction: " . $extractResult['message'], 'ERROR', $targetDir);
               @unlink($tempFile);
               @unlink($lockFile);
               return $extractResult;
            }
            error_log("✅ Extraction réussie");
            $this->logToInstallLog("✅ Extraction réussie", 'SUCCESS', $targetDir);
            echo '<script>document.getElementById("extraction-step").innerHTML = "✅ Extraction terminée avec succès";</script>';
            $this->keepAlive("Extraction terminée");
         }

         // Nettoyage
         @unlink($tempFile);
         @unlink($lockFile);
         error_log('🎉 ' . t('deployment_complete', $lang) . '!');
         $this->logToInstallLog('🎉 ' . t('deployment_complete', $lang) . '!', 'SUCCESS', $targetDir);
         $this->logToInstallLog('=== ' . t('deployment_finished',$lang) .' ===', 'INFO', $targetDir);
         echo '<li class="progress">🎉 ' . t('deployment_complete', $lang) . '!</li>';
         return $this->createResult(true, t('success', $lang), [
            'url' => $url,
            'temp_file' => $tempFile,
            'target_dir' => $targetDir,
            'size' => $this->lastDownloadSize,
            'version' => $version,
            'extracted_folder' => $extractResult['data']['extracted_folder'] ?? null
         ]);
      } catch (Exception $e) {
         error_log("💥 EXCEPTION: " . $e->getMessage());
         $this->logToInstallLog("💥 EXCEPTION: " . $e->getMessage(), 'ERROR', $targetDir);
         $this->logToInstallLog('=== ' . t('deployment_failed',$lang) .' ===', 'ERROR', $targetDir);
         @unlink($tempFile);
         @unlink($lockFile);
         return $this->createResult(false, t('error',$lang) . $e->getMessage());
      }
   }

   /**
   * Nettoie les anciens fichiers temporaires
   */
   private function cleanupOldFiles(): void {
      if (!is_dir($this->tempDir)) return;
         $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->tempDir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
      );
      $now = time();
      foreach ($files as $file) {
      // Supprimer tout ce qui a plus de 1 heure
         if ($file->getMTime() < ($now - 3600)) {
             if ($file->isDir())
                 @rmdir($file->getRealPath());
             else
                 @unlink($file->getRealPath());
         }
      }
   }

   /**
   * Envoie du feedback au navigateur
   */
   private function keepAlive($message = ''): void {
      global $lang;
      // Vérifier si la connexion est toujours active (OPTIONNEL)
      if (connection_aborted()) {
         error_log('⚠️ ' . t('connection_lost',$lang));
         exit(0);
      }
      // Commentaire HTML minimal pour maintenir la connexion
      echo " " . str_repeat(' ', 1024*8) . "\n"; // Buffer de maintien
      echo "<!-- keep-alive: " . date('H:i:s') . " " . htmlspecialchars($message) . " -->\n";
      // Envoyer effectivement les données au navigateur
      if (ob_get_level() > 0)
         ob_flush();
      flush();
      // Petite pause pour éviter la surcharge CPU
      usleep(50000); // 50ms
   }

   /**
   * Nettoie un répertoire (méthode publique)
   */
   public function cleanupDirectory(string $directory): array {
      global $lang;
      try {
         $this->removeDirectory($directory);
         return $this->createResult(true, "Dossier nettoyé: " . $directory);
      } catch (Exception $e) {
         return $this->createResult(false, t('cleanup_error', $lang) . ": " . $e->getMessage());
      }
   }

   /**
   * Extrait uniquement le contenu du premier dossier de l'archive
   */
   private function extractFirstFolderContent(string $archivePath, string $targetDir, string $format,string $version, bool $isUpdate = false): array {
      global $lang;
      error_log('🔍 '. t('extracting' ,$lang) . ': ' . filesize($archivePath) . " bytes");
      $this->logToInstallLog('🔍 '. t('extracting' ,$lang) . ': ' . filesize($archivePath) . " bytes", 'INFO', $targetDir);
      $this->keepAlive(t('start_extraction',$lang));
      // Vérification du répertoire cible
      if (!is_dir($targetDir) && !@mkdir($targetDir, 0755, true)) {
         error_log('❌ ' . t('target_dir_error', $lang) . ': ' . $targetDir);
         $this->logToInstallLog('❌ ' . t('target_dir_error', $lang) . ': ' . $targetDir, 'ERROR', $targetDir);
         return $this->createResult(false, t('target_dir_error', $lang));
      }
      if (!is_writable($targetDir)) {
         error_log('❌ ' . t('target_permission_error', $lang) . ': '. $targetDir);
         $this->logToInstallLog('❌ ' . t('target_permission_error', $lang) . ': ' . $targetDir, 'ERROR', $targetDir);
         return $this->createResult(false, t('target_permission_error', $lang));
      }
      echo '<li class="progress" id="extraction-step">📂 ' . t('extraction_progress', $lang) . '...</li>';
      $this->keepAlive(t('start_extraction',$lang));
      try {
         // Créer un répertoire temporaire pour l'extraction complète
         $tempExtractDir = $this->tempDir . '/' . uniqid('extract_');
         if (!@mkdir($tempExtractDir, 0755, true)) {
            error_log('❌ ' . t('temp_dir_error', $lang) . ': ' . $tempExtractDir);
            $this->logToInstallLog('❌ ' . t('temp_dir_error', $lang) . ': ' . $tempExtractDir, 'ERROR', $targetDir);
            return $this->createResult(false, t('temp_dir_error', $lang));
         }
         echo '<script>document.getElementById("extraction-step").innerHTML = "🔄 Extraction de l\'archive en cours...";</script>';
         $this->keepAlive("Extraction archive");
         // Extraction complète de l'archive dans le répertoire temporaire
         if ($format === 'zip') {
            $zip = new ZipArchive();
            if ($zip->open($archivePath) !== true) {
               $this->removeDirectory($tempExtractDir);
               error_log('❌ ' . t('zip_open_error', $lang));
               $this->logToInstallLog('❌ ' . t('zip_open_error', $lang), 'ERROR', $targetDir);
               return $this->createResult(false, t('zip_open_error', $lang));
            }
            $totalFiles = $zip->numFiles;
            echo '<script>document.getElementById("extraction-step").innerHTML = "📄 Extraction: 0/' . $totalFiles . ' fichiers";</script>';
            $this->keepAlive("Extraction: 0/$totalFiles fichiers");
            // Extraire avec progression

            for ($i = 0; $i < $totalFiles; $i++) {
               $zip->extractTo($tempExtractDir, $zip->getNameIndex($i));
               // Feedback toutes les 50 fichiers
               if ($i % 50 === 0) {
                  $percent = round(($i / $totalFiles) * 100);
                  echo '<script>document.getElementById("progress").innerHTML = "📄 Extraction: ' . $percent . '% (' . $i . '/' . $totalFiles . ')"</script>';
                  echo '<!-- progression: ' . $percent . '% -->';
                  $this->keepAlive("Extraction: $i/$totalFiles fichiers");
                 if (ob_get_level() > 0)
                     ob_flush();
                 flush();
                }
            }
            error_log("🔄 Début extraction - $totalFiles fichiers total");

            for ($i = 0; $i < $totalFiles; $i++) {
               // DIAGNOSTIC CRITIQUE - Avant chaque extraction
               if ($i % 100 === 0) {
                  $memory = round(memory_get_usage(true) / 1024 / 1024, 2);
                  error_log("🔍 Fichier $i/$totalFiles - Mémoire: {$memory}MB");
               }
               // RESET TIMEOUT agressif
               if ($i % 100 === 0)
                  set_time_limit(300);
               // EXTRACTION avec gestion d'erreur
               $filename = $zip->getNameIndex($i);
               $success = $zip->extractTo($tempExtractDir, $filename);
               if (!$success)
                  error_log("❌ Échec extraction $i: $filename"); // Mais on CONTINUE
               // KEEPALIVE renforcé
               if ($i % 20 === 0) {
                  $percent = round(($i / $totalFiles) * 100);
                  echo '<script>document.getElementById("progress").innerHTML = "📄 Extraction: ' . $percent . '% (' . $i . '/' . $totalFiles . ')"</script>';
                  echo ' '; // Micro keepalive
                  flush();
               }
               // KeepAlive fréquent
               if ($i % 10 === 0) {
               $this->keepAlive("Extraction $i/$totalFiles");
               }
            }
            error_log("✅ Extraction TERMINÉE - $i fichiers traités");
            $zip->close();
            echo '<script>document.getElementById("extraction-step").innerHTML = "✅ ' . t('extraction_finished',$lang) .': ' . $totalFiles . ' fichiers";</script>';
         } else {
            $phar = new PharData($archivePath);
            $phar->extractTo($tempExtractDir);
            echo '<li class="progress">✅ Extraction TAR.GZ terminée</li>';
         }
         $this->keepAlive(t('extraction_finished',$lang));

         // Trouver le premier dossier dans l'archive extraite
         $firstFolder = $this->findFirstFolder($tempExtractDir);
         if (!$firstFolder) {
            $this->removeDirectory($tempExtractDir);
            error_log('❌ ' . t('no_folder_in_archive', $lang));
            $this->logToInstallLog('❌ ' . t('no_folder_in_archive', $lang), 'ERROR', $targetDir);
            echo '<li class="progress">❌ '. t('no_folder_in_archive', $lang) .'</li>';
            return $this->createResult(false, t('no_folder_in_archive', $lang));
         }
         // VÉRIFICATION SUPPLEMENTAIRE : Si le dossier contient revolution_16, on l'utilise
         $revolutionPath = $firstFolder . '/revolution_16';
         if (is_dir($revolutionPath)) {
            $firstFolder = $revolutionPath;
            error_log("✅ Dossier revolution_16 trouvé à l'intérieur");
            echo '<script>document.getElementById("extraction-step").innerHTML = "✅ Dossier revolution_16 détecté";</script>';
            $this->keepAlive("Dossier revolution_16 détecté");
         }
         // Copier le contenu DIRECTEMENT sans le dossier parent
         echo '<li class="progress" id="copy-step">📋 '. t('copying_files',$lang) .'...</li>';
         $this->keepAlive(t('copying_files',$lang));
         $this->copyDirectoryContentsFlat($firstFolder, $targetDir, $version, $isUpdate);
         // Nettoyer le répertoire temporaire
         $this->removeDirectory($tempExtractDir);
         echo '<script>document.getElementById("extraction-step").innerHTML = "✅ ' . t('extraction_finished',$lang) .': ' . $totalFiles . ' fichiers";</script>';
         $this->keepAlive("Extraction et copie terminées");
         return $this->createResult(true, "Contenu du premier dossier extrait avec succès", [
             'extracted_folder' => basename($firstFolder)
         ]);
      } catch (Exception $e) {
         if (isset($tempExtractDir) && is_dir($tempExtractDir))
            $this->removeDirectory($tempExtractDir);
         error_log('💥 ' . t('extraction_error', $lang) . ': ' . $e->getMessage());
         $this->logToInstallLog('💥 ' . t('extraction_error', $lang) . ': ' . $e->getMessage(), 'ERROR', $targetDir);
         echo '<li class="progress">❌ ' . t('extraction_error', $lang) .'</li>';
         return $this->createResult(false, t('extraction_error', $lang) . ': ' . $e->getMessage());
      }
   }

   /**
   * Copie le contenu d'un répertoire sans le dossier parent
   */
   private function copyDirectoryContentsFlat(string $source, string $destination, $version = null, $isUpdate = false): void {
      global $lang;
      error_log('🔄 ' . t('copying_files', $lang). ' - Update: ' . ($isUpdate ? 'OUI' : 'NON'));
      echo '<script>document.getElementById("copy-step").innerHTML = "📂 ' . t('copying_files', $lang) . '...";</script>';
      flush();
      if (!is_dir($destination))
         mkdir($destination, 0755, true);
      $dirIterator = new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS);
      $iterator = new RecursiveIteratorIterator($dirIterator, RecursiveIteratorIterator::SELF_FIRST);
      $totalFiles = iterator_count($iterator);
      if ($totalFiles === 0) 
         throw new Exception(t('no_files_to_copy',$lang) . ': ' . $source);
      $fileCount = 0;
      $skippedCount = 0;
      foreach ($iterator as $item) {
         $fileCount++;
         $relativePath = $iterator->getSubPathName();
         $targetPath = $destination . DIRECTORY_SEPARATOR . $relativePath;
         // VÉRIFICATION D'EXCLUSION (uniquement en mise à jour)
         if ($isUpdate && NPDSExclusions::shouldExclude($relativePath, $version, $isUpdate)) {
         $skippedCount++;
         continue; // Exclure même si le fichier n'existe pas encore
         }
        if ($fileCount % 25 === 0) {
            $percent = round(($fileCount / $totalFiles) * 100);
            $status = '📁 ' . t('copied',$lang) . ": $percent% ($fileCount/$totalFiles)";
            if ($isUpdate)
               $status .= " - Ignorés: $skippedCount";
            echo '<script>document.getElementById("progress").innerHTML = "'.$status.'";</script>';
            echo '<div style="display:none">Progression: ' . $percent . '%</div>';
            echo str_repeat(' ', 262144);
            if (ob_get_level() > 0)
                ob_flush();
            flush();
         }

         $targetPath = $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName();

         if ($item->isDir()) {
            if (!is_dir($targetPath))
               mkdir($targetPath, 0755);
         } else {
            $parentDir = dirname($targetPath);
            if (!is_dir($parentDir))
               mkdir($parentDir, 0755, true);
            if (!copy($item->getRealPath(), $targetPath))
               throw new Exception(t('copy_error',$lang) .': '. $item->getFilename());
         }
      }
      $finalStatus = '✅ ' . t('copy_complete',$lang) . ': ' .$fileCount.' éléments';
      if ($isUpdate)
         $finalStatus .= " ($skippedCount ignorés)";
      echo '<script>document.getElementById("copy-step").innerHTML = "'.$finalStatus.'";</script>';
      if (ob_get_level() > 0)
         ob_flush();
      flush();
      error_log("✅ copyDirectoryContentsFlat terminée: $fileCount fichiers" . ($isUpdate ? ", $skippedCount ignorés" : ''));
   }

   /**
   * Trouve le premier dossier dans un répertoire
   */
   private function findFirstFolder(string $directory): ?string {
      $items = scandir($directory);
      foreach ($items as $item) {
         if ($item !== '.' && $item !== '..' && is_dir($directory . '/' . $item))
            return $directory . '/' . $item;
      }
      return null;
   }

   /**
   * Supprime récursivement un répertoire
   */
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

   /**
   * Construit l'URL de téléchargement
   */
   private function buildVersionUrl(string $baseUrl, string $version, string $format): string {
     return rtrim($baseUrl, '/') . '/' . $version . '.' . $format;
   }

   /**
   * Télécharge un fichier avec gestion des redirections et suivi de progression
   */
   private function downloadFile(string $url, string $destination,?string $targetDir = null): array {
      global $lang;
      error_log('📥 ' . t('file_download_start', $lang) . ": " . basename($destination));
      $this->logToInstallLog('📥 ' . t('file_download_start', $lang) . ': ' . basename($destination), 'INFO', $targetDir);
      $context = $this->createStreamContext();
      $source = @fopen($url, 'rb', false, $context);
      if (!$source) {
         error_log("❌ Impossible d'ouvrir l'URL: $url");
         $this->logToInstallLog("❌ Impossible d'ouvrir l'URL: $url", 'ERROR', $targetDir);
         return $this->createResult(false, t('failed_download', $lang));
      }
      $dest = @fopen($destination, 'wb');
      if (!$dest) {
         fclose($source);
         error_log("❌ Impossible de créer le fichier: $destination");
         $this->logToInstallLog("❌ Impossible de créer le fichier: $destination", 'ERROR', $targetDir);
         return $this->createResult(false, t('write_error', $lang));
      }
      // Copie avec feedback
      $downloaded = 0;
      while (!feof($source)) {
         $data = fread($source, 8192);
         fwrite($dest, $data);
         $downloaded += strlen($data);
         // Feedback toutes les 100KB
         if ($downloaded % (100 * 1024) === 0) {
             $mb = round($downloaded / 1024 / 1024, 2);
             echo '<script>document.getElementById("progress").innerHTML = "📥 '.t('downloading', $lang).': ' . $mb . ' MB"</script>';
             $this->keepAlive("Downloaded: $mb MB");
         }
      }
      fclose($source);
      fclose($dest);
      $finalSize = filesize($destination);
      $finalSizeMB = round($finalSize / 1024 / 1024, 2);
      error_log('✅ ' . t('file_download_finished', $lang) . ': ' .$finalSizeMB. ' MB');
      $this->logToInstallLog('✅ ' . t('file_download_finished', $lang) . ': ' .$finalSizeMB. ' MB', 'SUCCESS', $targetDir);
      return $this->createResult(true, t('download_success',$lang), ['size' => $finalSize]);
   }

   /**
   * Crée le contexte de stream pour les téléchargements
   */
   private function createStreamContext() {
      $options = [
         'http' => [
            'method' => 'GET',
            'header' => [
               'User-Agent: ' . $this->userAgent,
               'Accept: application/octet-stream',
               'Connection: close'
            ],
            'timeout' => $this->timeout,
            'ignore_errors' => true
         ],
         'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false
         ]
      ];
      return stream_context_create($options);
   }

   /**
   * Crée un résultat standardisé et log
   */
   private function createResult(bool $success, string $message, array $data = []): array {
      $result = [
         'success' => $success,
         'message' => $message,
         'data' => $data,
         'timestamp' => time()
      ];
      return $result;
   }

   /**
   * Calcule la taille d'un dossier
   */
   private function getDirectorySize(string $path): string {
      if (!is_dir($path)) return '0 bytes';
      $size = 0;
      $files = new RecursiveIteratorIterator(
         new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS)
      );
      foreach ($files as $file) {
         $size += $file->getSize();
      }
      return round($size / 1024 / 1024, 2) . ' Mo';
   }

   public function getDeployedSize($path): string {
      return $this->getDirectorySize($path);
   }

   public function logToInstallLog($message, $type = 'INFO', $targetDir = null): void {
      $baseDir = $targetDir ?? __DIR__;
      $logDir = $baseDir . '/slogs';
      $logFile = $logDir . '/install.log';
      $timestamp = date('d/m/y H:i:s');
      $logEntry = "$timestamp : $type : $message\n";
      // Créer le dossier slogs s'il n'existe pas
      if (!is_dir($logDir))
         @mkdir($logDir, 0755, true);
      // Ajouter au fichier log
      @file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
   }

}

// ==================== INTERFACE TEMPORAIRE DE MISE À JOUR ====================
/**
* Interface temporaire pour migration 16.4 → 16.8
*/
function showUpdateInterface() {
    return '
    <div class="section-maintenance py-1">
        <h3 class="my-1"><span class="display-6">🔄 </span>Mise à jour NPDS 16.4 → 16.8</h3>
        <div class="alert alert-warning">
            <small>⚠️ Interface temporaire de migration</small>
        </div>
        <ul class="mt-1">
            <li><a href="?op=update&confirm=yes" onclick="return confirm(\'⚠️ Mettre à jour NPDS 16.4 vers 16.8 ?\')">
                🚀 Lancer la mise à jour vers NPDS 16.8
            </a></li>
        </ul>
    </div>';
}

function processTemporaryUpdate() {
   global $lang;
   echo head_html();
   echo '<h2 class="ms-3"><span class="display-6">🔄 </span>Mise à jour NPDS 16.4 → 16.8</h2>';
   echo '<div class="alert alert-info">Migration vers NPDS 16.8 en cours...</div>';
   flush();
   // Utiliser le déployeur pour la mise à jour
   $deployer = new GithubDeployer(['tempDir' => __DIR__ . '/npds_deployer_temp/']);
   $result = $deployer->deployVersion(
   'https://github.com/npds/npds_dune/archive/refs/tags',
   'v.16.8', // VERSION FIXE - pas besoin de détection
   'zip',
   __DIR__ // RACINE DU DOMAINE (npds_deployer.php est à la racine)
   );
   if ($result['success']) {
      echo '<div class="alert alert-success">✅ Mise à jour réussie !</div>';
      echo '<p><a href="admin.php">➡️ Accéder à la nouvelle administration NPDS 16.8</a></p>';
   } else 
      echo '<div class="alert alert-danger">❌ Erreur: ' . htmlspecialchars($result['message']) . '</div>';
   echo foot_html();
}

/**
* Fonction principale de déploiement
*/
function deployNPDS($version = null, $installPath = null) {
   global $lang;
   // VÉRIFICATION DE SÉCURITÉ
   if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'yes')
      die("❌ " . t('security_warning', $lang));

   // Vérification supplémentaire en mode update
   global $context;

   if ($version === null)
      $version = $_GET['version'] ?? 'v.16.4';
   if ($installPath === null)
      $installPath = isset($_GET['path']) ? $_GET['path'] : __DIR__;
   $installPath = rtrim($installPath, '/');
   if (($context === 'deploy' || $context === 'update') && !headers_sent())
      header('Content-Type: text/html; charset=utf-8');
   echo head_html();
   echo '
      <h2 class="ms-3"><span class="display-6">🚀 </span>' . t('deploying', $lang) . '</h2>
      <p><strong>' . t('version', $lang) . ':</strong> ' . htmlspecialchars($version) . ' ==> <strong>' . t('path', $lang) . ':</strong> ' . htmlspecialchars($installPath) . '</p>';
   if ($version === 'master') {
      echo '
        <div class="section-danger py-2">
           <strong>‼️ ' . t('development_version', $lang) . '</strong><br />' . t('dev_warning', $lang) .'
        </div>';
   }
   echo '
    <div class="section-maintenance py-2"
      <ul style="list-style-type: none;">
         <li class="progress" id="progress">📦 ' . t('initializing', $lang) . '...</li>
         <li><hr /></li>';
   flush();
   $deployer = new GithubDeployer(['tempDir' => __DIR__ . '/npds_deployer_temp/']);
   if ($version === 'master')
      $result = $deployer->deployMaster($installPath);
   else {
      $result = $deployer->deployVersion(
         'https://github.com/npds/npds_dune/archive/refs/tags',
         $version,
         'zip',
         $installPath
      );
   }
   echo '
       </ul>
    </div>';
   flush();

   if ($result['success']) {
      echo '
        <script>document.getElementById("progress").innerHTML = "✅ ' . t('processing_result', $lang) . '";</script>
        <div class="section-success py-2">
           <h3><span class="display-6">🎉 </span>' . t('success', $lang) . ' !</h3>
           <ul>';
      // Log final détaillé
      $deployer->logToInstallLog(t('deployment_complete',$lang), 'SUCCESS');
      $deployer->logToInstallLog(t('version',$lang). ' : ' . ($result['data']['version'] ?? 'inconnue'), 'INFO');
      $deployer->logToInstallLog("Dossier cible: " . $installPath, 'INFO');
      $sizeInMB = $deployer->getDeployedSize($installPath);
      echo '<li>📦 ' . $sizeInMB . ' ' . t('deployed_size', $lang) . '</li>';
      $fileCount = 0;
      $dirCount = 0;
      if (is_dir($installPath)) {
         $items = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($installPath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
         );
         foreach ($items as $item) {
            if ($item->isFile()) $fileCount++;
            else $dirCount++;
         }
      }
      echo '<li>📁 ' . ($fileCount + $dirCount) . ' ' . t('items_installed', $lang) . ' (' . $fileCount . ' ' . t('files', $lang) . ', ' . $dirCount . ' ' . t('folders', $lang) . ')</li>';
      $relativePath = str_replace(__DIR__, '', $installPath);
      if ($relativePath === '')
         $relativePath = '';
      else
         $relativePath = '/' . trim($relativePath, '/');
      $baseUrl = 'https://' . $_SERVER['HTTP_HOST'] . $relativePath;
      // ==================== GESTION RETOUR ADMIN ====================
      if (isset($_GET['return_url'])) {
         $returnUrl = $_GET['return_url'];
         // Ajouter le paramètre success seulement maintenant qu'on sait que c'est réussi
         $returnUrl .= (strpos($returnUrl, '?') === false ? '?' : '&') . 'action=success&version=' . urlencode($version);
         echo '
         <div class="mt-3 alert alert-info">
            <p>✅ Redirection vers l\'administration dans 5 secondes...</p>
            <p><a href="' . $returnUrl . '" class="btn btn-primary">Cliquer ici pour retourner maintenant</a></p>
         </div>
         <script>
            setTimeout(function() {
               window.location.href = "' . $returnUrl . '";
            }, 10000);
         </script>';
      } else
         echo '
         <p><a class="btn btn-success" style="color:white;" href="' . $baseUrl . '/install.php?langue='.$lang.'&amp;stage=1" target="_blank" >' . t('launch_installation', $GLOBALS['lang']) . '</a></p>';
      echo '</div>';
   } else
      echo '
        <div class="error">
           <h2>❌ ' . t('error', $GLOBALS['lang']) . '</h2>
            <p>' . htmlspecialchars($result['message']) . '</p>
        </div>';
   echo foot_html();
}

/**
* Fonction de traduction
*/
function t($key, $lang = 'fr') {
   global $translations;
   return $translations[$lang][$key] ?? $translations['fr'][$key] ?? $key;
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
      <img width="48" height="48" src="data:image/png;base64,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" alt="langage selector" />
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

/**
* Fonction de construction du header html
*/
function head_html(){
/*
static $header_printed = false;
    
    if ($header_printed) {
        error_log("🚨 HEADER DÉJÀ AFFICHÉ - DOUBLE DÉTECTÉ");
        return ''; // Ne pas réafficher
    }
    
    $header_printed = true;
*/
   global $lang;
   return '<!DOCTYPE html>
   <html lang="'.$lang.'">
      <head>
         <meta charset="utf-8">
         <title>' . t('welcome', $lang) . '</title>
         <style>
            :root {
               --bs-body-color: #212529;
               --bs-success: #198754;
               --bs-primary: #0d6efd;
               --bs-secondary: #6c757d;
               --bs-success: #198754;
               --bs-info: #0dcaf0;
               --bs-warning: #ffc107;
               --bs-danger: #dc3545;
               --bs-light: #f8f9fa;
               --bs-dark: #212529;
               --bs-border-radius: 0.375rem;
               --bs-border-width: 1px;
               --bs-border-color: #dee2e6;
            }

            body {font-family: Arial, sans-serif; margin:0; color:var(--bs-body-color)}
            img, svg {vertical-align: middle;}
            a {color: #007bff; text-decoration: none;}
            ul {list-style-type: none; padding: 0;}
            li {margin: 6px 0;}
            li#progress {
               font-size: 1.1rem;
               color: green;
               font-family: monospace;
            }
            #language_selector a.active {color: black; font-weight: bold;}
            #language_selector a:hover{color: black;}
            #language_selector ul {padding: 0; margin-left: 0.5rem !important;}

            .bg-light {background-color: rgba(248,249,250,1)!important;}
            .p-0 {padding: 0 !important;}
            .p-2 {padding: .5rem !important;}
            .p-4 {padding: 1.5rem !important;}
            .pe-3 {padding-right: 1rem!important}
            .py-1 {
               padding-top: 0.25rem !important;
               padding-bottom: 0.25rem !important;
            }
            .py-2 {
               padding-top: 0.5rem !important;
               padding-bottom: 0.5rem !important;
            }

            .ps-0 {padding-left: 0;}
            .ps-1 {padding-left: 0.25rem !important;}
            .ps-3 {padding-left: 1rem!important;}
            .ps-md-0 {padding-left: 0 !important;}
            .ps-0 {padding-left: 0 !important;}
            .px 0 {
               padding-right: 0 !important;
               padding-left: 0 !important;
            }
            .px-1 {
               padding-right: 0.25rem !important;
               padding-left: 0.25rem !important;
            }
            .px-2 {
               padding-right: 0.5rem !important;
               padding-left: 0.5rem !important;
            }
            .px-3 {
                padding-right: 1.5rem!important;
                padding-left: 1.5rem!important;
            }
            .mb-3 {margin-bottom: 1rem !important;}
            .mb-4 {margin-bottom: 1.5rem !important;}
            .mt-0 {margin-top: 0 !important;}
            .mt-1 {margin-top: 0.25rem !important;}
            .mt-2 {margin-top: 0.5rem !important;}
            .mt-3 {margin-top: 1rem !important;}
            .mt-4 {margin-top: 1.5rem !important;}
            .mt-5 {margin-top: 3rem !important;}
            .me-2 {margin-right: 0.5rem !important;}
            .me-3 {margin-right: 3rem;}
            .ms-3 {margin-left: 1rem !important;}
            .ms-auto {margin-left: auto!important}
            .align-items-center {align-items: center !important;}
             g-3, .gx-3 {--bs-gutter-x: 1rem;}
            .g-3, .gy-3 {--bs-gutter-y: 1rem;}
            .d-flex {display: flex !important;}
            .d-none {display: none !important;}
            .d-md-inline-block {display: inline-block !important;}
            .float-end {float: right !important;}
            .text-end {text-align: right !important;}
            .text-center {text-align: center !important;}
            .text-danger {color: rgba( 220, 53, 69, 1) !important;}
            .text-success {color: rgba(63, 182, 24, 1)!important}

            .w-75 {width: 50%!important}
            .my-0 {
               margin-top: 0 !important;
               margin-bottom: 0 !important;
            }
            .my-1 {
               margin-top: 0.25rem !important;
               margin-bottom: 0.25rem !important;
            }
            .my-2 {
               margin-top: 0.5rem !important;
               margin-bottom: 0.5rem !important;
            }
            .my-3 {
               margin-top: 1rem !important;
               margin-bottom: 1rem !important;
            }
            .my-4 {
               margin-top: 1.5rem !important;
               margin-bottom: 1.5rem !important;
            }
            .my-5 {
               margin-top: 3rem !important;
               margin-bottom: 3rem !important;
            }
            .my-auto {
                margin-top: auto !important;
                margin-bottom: auto !important;
            }
            .small, small {font-size: .875em;}
            .spinner-border,.spinner-grow {
                display: inline-block;
                width: var(--bs-spinner-width);
                height: var(--bs-spinner-height);
                vertical-align: var(--bs-spinner-vertical-align);
                border-radius: 50%;
                animation: var(--bs-spinner-animation-speed) linear infinite var(--bs-spinner-animation-name)
            }
            @keyframes spinner-border {
                to {transform: rotate(360deg)}
            }
            .spinner-border {
                --bs-spinner-width: 1.8rem;
                --bs-spinner-height: 1.8rem;
                --bs-spinner-vertical-align: -0.125em;
                --bs-spinner-border-width: 0.25em;
                --bs-spinner-animation-speed: 0.75s;
                --bs-spinner-animation-name: spinner-border;
                border: 0.25rem solid currentcolor;
                border-right-color: transparent
            }
            .row {
              --bs-gutter-x: 1.5rem;
              --bs-gutter-y: 0;
               display: flex;
               flex-wrap: wrap;
               margin-top: calc(-1 * 0);
               margin-right: calc(-.5 * 1.5rem);
               margin-left: calc(-.5 * var(--bs-gutter-x));
            }
            .col {flex: 1 0 0;}
            .col-sm-2 {
               flex: 0 0 auto;
               width: 16.66666667%;
            }
            .container,
            .container-fluid,
            .container-xxl,
            .container-xl,
            .container-lg,
            .container-md,
            .container-sm {
               width: 100%;
               padding-right: calc(1.5rem * 0.5);
               padding-left: calc(1.5rem * 0.5);
               margin-right: auto;
               margin-left: auto;
            }
            @media (min-width: 576px) {
              .container-sm, .container {
                max-width: 540px;
              }
            }
            @media (min-width: 768px) {
              .container-md, .container-sm, .container {
                max-width: 720px;
              }
            }
            @media (min-width: 992px) {
              .container-lg, .container-md, .container-sm, .container {
                max-width: 960px;
              }
            }
            @media (min-width: 1200px) {
              .container-xl, .container-lg, .container-md, .container-sm, .container {
                max-width: 1140px;
              }
            }
            @media (min-width: 1400px) {
              .container-xxl, .container-xl, .container-lg, .container-md, .container-sm, .container {
                max-width: 1320px;
              }
            }
            .img-fluid {max-width: 100%; height: auto;}
            .display-4 {
               font-weight: 300;
               line-height: 1.2;
               font-size: calc(1.475rem + 2.7vw);
            }
            @media (min-width: 1200px) {
              .display-4 {
                font-size: 3.5rem;
              }
            }
            .display-5 {
               font-weight: 300;
               line-height: 1.2;
               font-size: calc(1.425rem + 2.1vw);
            }
            @media (min-width: 1200px) {
               .display-5 {font-size: 3rem;}
            }
            .display-6 {
               font-weight: 300;
               line-height: 1.2;
               font-size: calc(1.375rem + 1.5vw);
            }
            @media (min-width: 1200px) {
               .display-6 {
                  font-size: 2.5rem;
               }
            }
            .form-control {
               display: block;
               width: 100%;
               padding: 0.375rem 0.75rem;
               font-size: 1rem;
               font-weight: 400;
               line-height: 1.5;
               color: var(--bs-body-color);
               -webkit-appearance: none;
               -moz-appearance: none;
               appearance: none;
               background-color: var(--bs-body-bg);
               background-clip: padding-box;
               border: 1px solid #dee2e6;
               border-radius: 0.375rem;
               transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            }
            .form-label {margin-bottom: 0.5rem;}
            .w-90 {width: 90% !important;}
            .section-dev , .section-maintenance, .section-stable, .section-advanced, .section-success, .section-danger {
               border-radius: 0.375rem;
               padding-left: 1rem!important;
               padding-right: 1rem!important;
               border-left: 1.5rem solid;
               margin-bottom : 1rem;
               a {color:inherit; font-weight: 700;}
               h3 {color:var(--bs-body-color);}
               ul {margin-left: 1rem !important; list-style-type: disc;}
            }
            .resultats {ul {list-style-type: none;}}
            .section-stable, .section-success {border-color: var(--bs-success);background-color: #e3eed7; color: var(--bs-success); }
            .section-dev, .section-danger {border-color: var(--bs-danger);background-color: #f4d2d3; color: var(--bs-danger); }
            .section-maintenance {border-color: var(--bs-secondary); background-color:#f6f7f9; color: var(--bs-secondary);}
            .section-advanced {border-color: var(--bs-secondary); background-color:#f6f7f9; }
            .btn {
               --bs-btn-padding-x: 0.75rem;
               --bs-btn-padding-y: 0.375rem;
               --bs-btn-font-family: ;
               --bs-btn-font-size: 1rem;
               --bs-btn-font-weight: 400;
               --bs-btn-line-height: 1.5;
               --bs-btn-color: var(--bs-body-color);
               --bs-btn-bg: transparent;
               --bs-btn-border-width: var(--bs-border-width);
               --bs-btn-border-color: transparent;
               --bs-btn-border-radius: var(--bs-border-radius);
               --bs-btn-hover-border-color: transparent;
               --bs-btn-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075);
               --bs-btn-disabled-opacity: 0.65;
               --bs-btn-focus-box-shadow: 0 0 0 0.25rem rgba(var(--bs-btn-focus-shadow-rgb), .5);
               display: inline-block;
               padding: var(--bs-btn-padding-y) var(--bs-btn-padding-x);
               font-family: var(--bs-btn-font-family);
               font-size: var(--bs-btn-font-size);
               font-weight: var(--bs-btn-font-weight);
               line-height: var(--bs-btn-line-height);
               color: var(--bs-btn-color);
               text-align: center;
               text-decoration: none;
               vertical-align: middle;
               cursor: pointer;
               -webkit-user-select: none;
               -moz-user-select: none;
               user-select: none;
               border: var(--bs-btn-border-width) solid var(--bs-btn-border-color);
               border-radius: var(--bs-btn-border-radius);
               background-color: var(--bs-btn-bg);
               transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            }
            .btn-success {
               --bs-btn-color: #fff;
               --bs-btn-bg: #198754;
               --bs-btn-border-color: #198754;
               --bs-btn-hover-color: #fff;
               --bs-btn-hover-bg: #157347;
               --bs-btn-hover-border-color: #146c43;
               --bs-btn-focus-shadow-rgb: 60, 153, 110;
               --bs-btn-active-color: #fff;
               --bs-btn-active-bg: #146c43;
               --bs-btn-active-border-color: #13653f;
               --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
               --bs-btn-disabled-color: #fff;
               --bs-btn-disabled-bg: #198754;
               --bs-btn-disabled-border-color: #198754;
            }
            .btn:hover {
               color: var(--bs-btn-hover-color);
               background-color: var(--bs-btn-hover-bg);
               border-color: var(--bs-btn-hover-border-color);
            }
            .form-select {
               --bs-form-select-bg-img: url("data:image/svg+xml,%3csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 16 16\'%3e%3cpath fill=\'none\' stroke=\'%23343a40\' stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'m2 5 6 6 6-6\'/%3e%3c/svg%3e");
               display: block;
               width: 100%;
               padding: 0.375rem 2.25rem 0.375rem 0.75rem;
               font-size: 1rem;
               font-weight: 400;
               line-height: 1.5;
               color: var(--bs-body-color);
               -webkit-appearance: none;
               -moz-appearance: none;
               appearance: none;
               background-color: var(--bs-body-bg);
               background-image: var(--bs-form-select-bg-img), var(--bs-form-select-bg-icon, none);
               background-repeat: no-repeat;
               background-position: right 0.75rem center;
               background-size: 16px 12px;
               border: var(--bs-border-width) solid var(--bs-border-color);
               border-radius: var(--bs-border-radius);
               transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
               }
               @media (prefers-reduced-motion: reduce) {
                 .form-select {transition: none;}
               }
               .form-select:focus {
                  border-color: #86b7fe;
                  outline: 0;
                  box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
               }
         </style>
      </head>
      <body>
         <div class="d-flex align-items-center bg-light">
            <div class="col-sm-2 d-none d-md-inline-block">
               <img class="img-fluid p-2 mt-4" src="data:image/png;base64,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" alt="NPDS logo" />
            </div>
            <div class="col my-auto ps-3">
               <h1 class="display-5">NPDS<br /><small class="text-body-secondary">' . t('welcome', $lang) . ' </small></h1>
            </div>
            <div class="col-sm-2 my-auto ps-3">
            '.renderLanguageSelector($lang).'
            </div>
         </div>
         <div class="container-sm">';
}

/**
* Fonction de construction du footer html
*/
function foot_html(){
   global $lang;
   return '
         </div>
         <footer class="d-flex align-items-center bg-light">
            <div class="ps-3"><a href="https://www.npds.org" target="_blank">NPDS</a> <br />npds_deployer v.1.0</div>
            <div class="spinner-border ms-auto text-success" role="status" aria-hidden="true"></div>
            <div class="small px-3">
               <ul>
                  <li>PHP : <span class="">' .phpversion(). '</span></li>
                  <li>'.t('memory_limit',$lang).' : '. ini_get('memory_limit'). '</li>
                  <li>'.t('max_exec_time',$lang).' : '.ini_get('max_execution_time').'</li>
                  <li>'.t('server',$lang).' : '.$_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'.'</li>
               </ul>
            </div
         </footer>
      </body>
   </html>';
}

// ==================== ROUTEUR PRINCIPAL ====================
$confirm = $_GET['confirm'] ?? '';
$result = null;
$operation = $_GET['op'] ?? 'menu';
switch ($operation) {
   case 'deploy':
      if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'yes')
         die("❌ " . t('security_warning', $lang));
      deployNPDS();
   break;
   case 'update':
      // Interface temporaire de mise à jour 16.4 → 16.8
      if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'yes') {
         die("❌ Confirmation requise");
      }
      processTemporaryUpdate();
   break;
   case 'clean':
      if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'yes')
         die("❌ " . t('clean_confirm', $lang));
      header('Content-Type: text/html; charset=utf-8');
      $deployer = new GithubDeployer();
      $tempDir = $deployer->getTempDir();
      $result = $deployer->cleanupDirectory($tempDir);
      echo $result['success'] ? "✅ " : "❌ ";
      echo $result['message'];
   break;
   case 'info':
      phpinfo();
   break;
   case 'menu':
   default:
      header('Content-Type: text/html; charset=utf-8');
      echo head_html();

      // Afficher le mode approprié
        if ($context === 'update') {
            echo '<div class="alert alert-success">';
            echo '<strong>🔧 Mode mise à jour détecté</strong><br>';
            echo 'Vous êtes connecté en tant qu\'administrateur NPDS.';
            echo '</div>';
        } else {
            echo '<div class="alert alert-info">';
            echo '<strong>🚀 Mode déploiement détecté</strong><br>';
            echo 'Nouvelle installation NPDS';
            echo '</div>';
        }

      echo '
         <p class="text-danger mb-3"><strong>‼️ ' . t('warning', $lang) . ' :</strong> ' . t('overwrite_warning', $lang) . '</p>
         <div class="section-stable py-1">
            <h3 class="my-1"><span class="display-6">🧪 </span>' . t('stable_versions', $lang) . '</h3>
            <ul class="mt-1">
               <li><a href="?op=deploy&version=v.16.4&path=npds_stable&confirm=yes" onclick="return confirm(\'⚠️ ' . t('deploy_v164_stable', $lang) . ' ?\')">' . t('deploy_v164_stable', $lang) . '</a></li>
               <li><a href="?op=deploy&version=v.16.4&confirm=yes" onclick="return confirm(\'⚠️ ' . t('deploy_v164_root', $lang) . ' ?\')">' . t('deploy_v164_root', $lang) . '</a></li>
               <li><a href="?op=deploy&version=v.16.3&path=npds_163&confirm=yes" onclick="return confirm(\'⚠️ ' . t('deploy_v163', $lang) . ' ?\')">' . t('deploy_v163', $lang) . '</a></li>
            </ul>
         </div>';
         // Afficher le lien mise à jour seulement si en mode update
     if ($context === 'update') {
         echo '
         <div class="section-maintenance py-1">
             <h3 class="my-1"><span class="display-6">🔄 </span>Mise à jour</h3>
             <ul class="mt-1">
                 <li><a href="?op=update">Mise à jour v.16.4 → v.16.8</a></li>
             </ul>
         </div>';
     }
      echo '
         <div class="section-dev py-1 ">
            <h3 class="my-1"><span class="display-6">🌶 </span>' . t('dev_version', $lang) . '</h3>
            <ul class="mt-1">
               <li><a href="?op=deploy&version=master&path=npds_dev&confirm=yes" onclick="return confirm(\'⚠️ ' . t('deploy_master_dev', $lang) . ' ?\')">' . t('deploy_master_dev', $lang) . '</a></li>
               <li><a href="?op=deploy&version=master&confirm=yes" onclick="return confirm(\'🚨 ' . t('deploy_master_root', $lang) . ' ?\')">' . t('deploy_master_root', $lang) . '</a></li>
            </ul>
            <p class="text-danger">‼️ ' . t('master_warning', $lang) . '</p>
         </div>
         <div class="section-maintenance py-1">
            <h3 class="my-1"><span class="display-6">🛠 </span>' . t('maintenance', $lang) . '</h3>
            <ul class="mt-1">
               <li><a href="?op=clean&confirm=yes" onclick="return confirm(\'' . t('clean_temp', $lang) . ' ?\')">' . t('clean_temp', $lang) . '</a></li>
               <li><a href="?op=info">' . t('system_info', $lang) . '</a></li>
            </ul>
         </div>
         <div class="section-advanced py-1">
            <h3 class="my-1"><span class="display-6">⚙️ </span>' . t('advanced_options', $lang) . '</h3>
            <form method="GET">
               <div class="row ps-3">
                 <div class="col-sm-3">
                    <select class="form-select" name="version" aria-label="version">
                        <option selected="selected">' . t('version', $lang) . '</option>
                        <option value="master">master</option>
                        <option value="v.16.4">v.16.4</option>
                        <option value="v.16.3">v.16.3</option>
                     </select>
                  </div>
                  <div class="col ps-3">
                     <input class="form-control mb-3 w-90" type="text" name="path" id="choix_path" placeholder="'.t('path',$lang).'... '.t('let_emptyroot',$lang).'" aria-label="path" />
                  </div>
               </div>
               <div class="ps-1">
                  <button class="btn btn-success mb-3" type="submit" onclick="return confirm(\'⚠️ ' . t('deploy', $lang) . ' ?\')" >' . t('deploy', $lang) . '</button>
               </div>
               <input type="hidden" name="confirm" value="yes" />
               <input type="hidden" name="op" value="deploy" />
            </form>
         </div>
      </div>';
      echo foot_html();
   break;
}
?>
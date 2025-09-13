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

// ==================== SÉCURITÉ - BLOCAGE SI DÉJÀ INSTALLÉ ====================
/**
* Empêche l'exécution du déployeur si NPDS est déjà installé
*/
function checkAlreadyInstalled() {
    $lockFiles = [
        'IZ-Xinstall.ok',
        '../IZ-Xinstall.ok', 
        '../../IZ-Xinstall.ok'
    ];
    foreach ($lockFiles as $lockFile) {
        if (file_exists($lockFile)) {
            if (php_sapi_name() !== 'cli' && isset($_SERVER['REQUEST_METHOD'])) {
                header('HTTP/1.0 403 Forbidden');
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
                            <p>Si vous souhaitez réinstaller, supprimez d\'abord le fichier <code>IZ-Xinstall.ok</code></p>
                        </div>
                    </body>
                    </html>
                ');
            }
            return true;
        }
    }
    return false;
}
// Vérifier si NPDS est déjà installé
checkAlreadyInstalled();


// ==================== CONFIGURATION SÉCURITÉ ====================
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
header('X-Accel-Buffering: no'); // Critical for Nginx

set_time_limit(0); // No time limit
ini_set('max_execution_time', 0);
ini_set('default_socket_timeout', 300);
ini_set('memory_limit', '512M');
ini_set('zlib.output_compression', '0');

// Bufferisation avancée
if (ob_get_level() > 0) ob_end_clean();
ob_start();

class GithubDeployer {
    private $userAgent = 'Mozilla/5.0 (compatible; GitHubDownloader/1.0)';
    private $timeout = 120;
    private $connectTimeout = 30;
    private $maxRedirects = 5;
    private $tempDir = 'npds_deployer_temp';
    private $lastDownloadSize = 0;

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
       if ($targetDir === null) {
           $targetDir = __DIR__;
       }
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
    public function deployVersion(
        string $baseUrl,
        string $version,
        string $format = 'zip',
        ?string $targetDir = null
    ): array {
       // ==================== VERROUILLAGE RENFORCÉ ====================
       $lockFile = $this->tempDir . '/deploy.lock';
       $lockTimeout = 600; // 10 minutes
       
       // Vérifier si un déploiement est déjà en cours
       if (file_exists($lockFile)) {
           $lockTime = (int)file_get_contents($lockFile);
           $elapsed = time() - $lockTime;
           
           if ($elapsed < $lockTimeout) {
               error_log("🚨 BLOCAGE: Déploiement déjà en cours depuis " . $elapsed . "s");
               return $this->createResult(false, "🚨 Un déploiement est déjà en cours (débuté il y a " . $elapsed . "s). Attendez 10 minutes.");
           } else {
               // Lock expiré, le supprimer
               @unlink($lockFile);
               error_log("🔓 Verrou expiré et supprimé");
           }
       }
       
       // Créer le verrou avec timestamp actuel
       if (!file_put_contents($lockFile, time())) {
           return $this->createResult(false, "Impossible de créer le verrou de sécurité");
       }
       // ==================== FIN VERROUILLAGE ====================
       
       // ==================== LOGS DE DÉBOGAGE ====================
       error_log("=== DÉPLOIEMENT DÉMARRÉ ===");
       error_log("Version: $version, Cible: " . ($targetDir ?? 'racine'));
       error_log("URL: " . $this->buildVersionUrl($baseUrl, $version, $format));
       error_log("Lock file: " . str_replace('//', '/', $lockFile));
       error_log("Temp dir: " . str_replace('//', '/', $this->tempDir));
       // ==================== FIN LOGS DE DÉBOGAGE ====================

        // Validation des paramètres
        if (empty($baseUrl) || empty($version))
            return $this->createResult(false, "URL de base et version sont requis");
        if (!in_array($format, ['zip', 'tar.gz']))
            return $this->createResult(false, "Format d'archive non supporté");
        // Vérifier si le dossier cible est vide
/*
        if ($targetDir && is_dir($targetDir) && count(scandir($targetDir)) > 2) {
            @unlink($lockFile);
            return $this->createResult(false, "Dossier cible non vide! Choisissez un dossier vide.");
        }
*/
        // Construction de l'URL complète
        $url = $this->buildVersionUrl($baseUrl, $version, $format);
        // Téléchargement du fichier
        $tempFile = $this->tempDir . '/' . uniqid('github_') . '.' . $format;
        try {
            // Envoyer du feedback au navigateur
            echo '<div class="progress">📦 Initialisation du téléchargement...</div>';
            $this->keepAlive();
            // Téléchargement avec suivi des redirections
            $downloadResult = $this->downloadFile($url, $tempFile);
            if (!$downloadResult['success']) {
                @unlink($lockFile);
                return $downloadResult;
            }
            $this->lastDownloadSize = filesize($tempFile);
            echo'<div class="progress">✅ Téléchargement réussi (' . round($this->lastDownloadSize / 1024 / 1024, 2) . ' MB)</div>';
            $this->keepAlive();
            // Vérification du fichier téléchargé
            if (!file_exists($tempFile) || filesize($tempFile) === 0) {
                @unlink($lockFile);
                return $this->createResult(false, "Fichier téléchargé vide ou inexistant");
            }

            // Extraction si un répertoire cible est spécifié
            if ($targetDir) {
                echo '<div class="progress">📂 Début de l\'extraction...</div>';
                $this->keepAlive();
                $extractResult = $this->extractFirstFolderContent($tempFile, $targetDir, $format);
                if (!$extractResult['success']) {
                    @unlink($tempFile);
                    @unlink($lockFile);
                    return $extractResult;
                }
            }

            // Nettoyage
            @unlink($tempFile);
            @unlink($lockFile);
            echo '<div class="progress">🎉 Déploiement terminé avec succès!</div>';
            return $this->createResult(true, "Déploiement réussi", [
                'url' => $url,
                'temp_file' => $tempFile,
                'target_dir' => $targetDir,
                'size' => $this->lastDownloadSize,
                'version' => $version,
                'extracted_folder' => $extractResult['data']['extracted_folder'] ?? null
            ]);
        } catch (Exception $e) {
            @unlink($tempFile);
            @unlink($lockFile);
            return $this->createResult(false, "Erreur: " . $e->getMessage());
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
        // Commentaire HTML minimal pour maintenir la connexion
        echo "<!-- keep-alive: " . date('H:i:s') . " " . htmlspecialchars($message) . " -->\n";
        // Envoyer effectivement les données au navigateur
        if (ob_get_level() > 0) {
            ob_flush();
        }
        flush();
    }

    /**
    * Nettoie un répertoire (méthode publique)
    */
    public function cleanupDirectory(string $directory): array {
       try {
           $this->removeDirectory($directory);
           return $this->createResult(true, "Dossier nettoyé: " . $directory);
       } catch (Exception $e) {
           return $this->createResult(false, "Erreur nettoyage: " . $e->getMessage());
       }
    }

    /**
    * Extrait uniquement le contenu du premier dossier de l'archive
    */
    private function extractFirstFolderContent(string $archivePath, string $targetDir, string $format): array {
        error_log("Début extraction: " . filesize($archivePath) . " bytes");
        // Vérification du répertoire cible
        if (!is_dir($targetDir) && !@mkdir($targetDir, 0755, true))
            return $this->createResult(false, "Impossible de créer le répertoire cible");
        if (!is_writable($targetDir))
            return $this->createResult(false, "Répertoire cible non accessible en écriture");
        echo '<div class="progress">📂 Extraction de l\'archive (3-4 minutes)...</div>';
        $this->keepAlive();
        try {
            // Créer un répertoire temporaire pour l'extraction complète
            $tempExtractDir = $this->tempDir . '/' . uniqid('extract_');
            if (!@mkdir($tempExtractDir, 0755, true))
                return $this->createResult(false, "Impossible de créer le répertoire temporaire");
            // Extraction complète de l'archive dans le répertoire temporaire
            if ($format === 'zip') {
                $zip = new ZipArchive();
                if ($zip->open($archivePath) !== true) {
                    $this->removeDirectory($tempExtractDir);
                    return $this->createResult(false, "Impossible d'ouvrir l'archive ZIP");
                }
                // EXTRACTION DIRECTE (beaucoup plus rapide)
                $zip->extractTo($tempExtractDir);
                $this->keepAlive("Extraction terminée");
                $zip->close();
            } else {
                $phar = new PharData($archivePath);
                $phar->extractTo($tempExtractDir);
            }
            // Trouver le premier dossier dans l'archive extraite
            $firstFolder = $this->findFirstFolder($tempExtractDir);
            if (!$firstFolder) {
                $this->removeDirectory($tempExtractDir);
                return $this->createResult(false, "Aucun dossier trouvé dans l'archive");
            }
            // VÉRIFICATION SUPPLEMENTAIRE : Si le dossier contient revolution_16, on l'utilise
            $revolutionPath = $firstFolder . '/revolution_16';
            if (is_dir($revolutionPath)) {
                $firstFolder = $revolutionPath;
                error_log("✅ Dossier revolution_16 trouvé à l'intérieur");
            }
            // Copier le contenu DIRECTEMENT sans le dossier parent
            $this->copyDirectoryContentsFlat($firstFolder, $targetDir);
            // Nettoyer le répertoire temporaire
            $this->removeDirectory($tempExtractDir);
            return $this->createResult(true, "Contenu du premier dossier extrait avec succès", [
                'extracted_folder' => basename($firstFolder)
            ]);
        } catch (Exception $e) {
            if (isset($tempExtractDir) && is_dir($tempExtractDir))
                $this->removeDirectory($tempExtractDir);
            return $this->createResult(false, "Erreur d'extraction: " . $e->getMessage());
        }
    }

    /**
    * Trouve le premier dossier dans le répertoire extrait
    */
    private function findFirstFolder(string $directory): ?string {
          $items = scandir($directory);
          $preferredDirs = ['revolution_16', 'npds_dune-v.16.4', 'npds_dune-v.16.3', 'npds_dune-master'];
          // D'abord chercher les dossiers préférés
          foreach ($preferredDirs as $preferred) {
              if (in_array($preferred, $items) && is_dir($directory . '/' . $preferred))
                  return $directory . '/' . $preferred;
          }
          // Fallback: premier dossier valide
          foreach ($items as $item) {
              if ($item !== '.' && $item !== '..' && is_dir($directory . '/' . $item))
                  return $directory . '/' . $item;
          }
          return null;
      }

    /**
    * Construit l'URL complète pour télécharger une version depuis GitHub
    */
    private function buildVersionUrl(string $baseUrl, string $version, string $format): string {
        $baseUrl = rtrim($baseUrl, '/');
        $extension = $format === 'tar.gz' ? 'tar.gz' : 'zip';
        if ($version === 'master')
            return $baseUrl . '/master.' . $extension;
        return $baseUrl . '/' . urlencode($version) . '.' . $extension;
    }

private function copyDirectoryContentsFlat(string $source, string $destination): void {
    error_log("🔄 copyDirectoryContentsFlat démarrée");
    
    echo '<div class="progress">📂 Début de la copie des fichiers...</div>';
    flush();

    if (!is_dir($destination))
        mkdir($destination, 0755, true);

    $dirIterator = new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS);
    $iterator = new RecursiveIteratorIterator($dirIterator, RecursiveIteratorIterator::SELF_FIRST);
    
    // ⚡ CALCUL DU NOMBRE TOTAL DE FICHIERS
    $totalFiles = iterator_count($iterator);
    if ($totalFiles === 0) {
        throw new Exception("Aucun fichier à copier dans: $source");
    }
    
    $fileCount = 0;
    
    foreach ($iterator as $item) {
        $fileCount++;
        
        // ⚡ OUTPUT TOUS LES 50 FICHIERS
        if ($fileCount % 50 === 0) {
            $percent = round(($fileCount / $totalFiles) * 100);
            echo '<script>document.getElementById("progress").innerHTML = "📁 Copie: '.$percent.'% ('.$fileCount.'/'.$totalFiles.')";</script>';
            echo str_repeat(' ', 4096);
            
            if (ob_get_level() > 0) {
                ob_flush();
            }
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
                throw new Exception("Impossible de copier: " . $item->getFilename());
        }
    }
    
    echo '<div class="progress">✅ Copie terminée: '.$fileCount.' fichiers</div>';
    if (ob_get_level() > 0) {
        ob_flush();
    }
    flush();
    
    error_log("✅ copyDirectoryContentsFlat terminée: $fileCount fichiers");
}
    /**
    * Supprime récursivement un répertoire
    */
    private function removeDirectory(string $directory): void {
        if (!is_dir($directory))
            return;
        $items = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($items as $item) {
            if ($item->isDir())
                rmdir($item->getRealPath());
            else
                unlink($item->getRealPath());
        }
        rmdir($directory);
    }

    /**
    * Télécharge un fichier avec gestion des redirections
    */
    private function downloadFile(string $url, string $destination): array {
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => "User-Agent: {$this->userAgent}\r\n",
                'timeout' => $this->timeout,
                'ignore_errors' => true
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ]
        ]);
        $content = @file_get_contents($url, false, $context);
        if ($content === false)
            return $this->createResult(false, "Échec du téléchargement (file_get_contents)");
        if (strlen($content) < 4 || substr($content, 0, 4) !== "PK\x03\x04")
            return $this->createResult(false, "Le contenu n'est pas une archive ZIP valide");
        if (file_put_contents($destination, $content) === false)
            return $this->createResult(false, "Impossible d'écrire le fichier");
        return $this->createResult(true, "Fichier téléchargé avec succès");
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
        // Loguer le résultat
         $logType = $success ? 'SUCCESS' : 'ERROR';
         $logMessage = $success ? "Déploiement réussi" : "Échec déploiement: $message";
         $this->logToInstallLog($logMessage, $logType);
          // Loguer les détails supplémentaires si disponibles
          if (!empty($data['version'])) {
              $this->logToInstallLog("Version: " . $data['version'], 'INFO');
          }
          if (!empty($data['size'])) {
              $sizeMB = round($data['size'] / 1024 / 1024, 2);
              $this->logToInstallLog("Taille: " . $sizeMB . " MB", 'INFO');
          }
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

    private function formatNpdsTimestamp(): string {
       date_default_timezone_set('Europe/Paris');
       $date = date('d/m/y');
       $time = date('H:i:s'); 
       return $date . '  ' . $time;
    }

    private function logToInstallLog($message, $type = 'INFO'): void {
       $logFile = 'slogs/install.log';
       $timestamp = date('d/m/y  H:i:s'); // Format avec zéros
       $logEntry = "$timestamp : $type : $message\n";
       // Créer le dossier slogs s'il n'existe pas
       if (!is_dir('slogs')) {
           @mkdir('slogs', 0755, true);
       }
       // Ajouter au fichier log
       @file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }

}

/**
 * Fonction principale de déploiement
 */
function deployNPDS($version = null, $installPath = null) {
    // VÉRIFICATION DE SÉCURITÉ
    if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'yes')
        die("❌ Sécurité : Ajoutez &confirm=yes pour lancer le déploiement");
    if ($version === null)
        $version = $_GET['version'] ?? 'v.16.4';
    if ($installPath === null)
        $installPath = isset($_GET['path']) ? $_GET['path'] : __DIR__;
    $installPath = rtrim($installPath, '/');

    header('Content-Type: text/html; charset=utf-8');

    echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Déploiement NPDS</title>';
    echo '<style>body{font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5;}';
    echo '.container{background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);}';
    echo '.success{color: #28a745; font-weight: bold;} .error{color: #dc3545;}';
    echo '.progress{background: #f8f9fa; border: 1px solid #e9ecef; padding: 10px; margin: 10px 0; border-radius: 4px;}';
    echo 'a{color: #007bff; text-decoration: none;} a:hover{text-decoration: underline;}</style></head><body>';
    echo '<div class="container">';
    
    echo "<h1>🚀 Déploiement NPDS</h1>";
    echo "<p><strong>Version:</strong> " . htmlspecialchars($version) . "</p>";
    echo "<p><strong>Chemin:</strong> " . htmlspecialchars($installPath) . "</p>";
    
    if ($version === 'master') {
        echo "<div style='background: #fff3cd; border: 1px solid #ffeaa7; padding: 10px; margin: 10px 0; border-radius: 4px;'>";
        echo "<strong>⚠️ VERSION DÉVELOPPEMENT</strong><br>";
        echo "La version master est une version de développement qui peut contenir des bugs, des fonctionnalités incomplètes ou être instable. Ne pas utiliser en production!";
        echo "</div>";
    }
    echo '<div class="progress" id="progress">📦 Initialisation du déploiement...</div>';
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
    
    echo '<script>document.getElementById("progress").innerHTML = "✅ Traitement terminé, analyse du résultat...";</script>';
    flush();

    if ($result['success']) {
        echo "<div class='success'>";
        echo "<h2>🎉 DÉPLOIEMENT RÉUSSI !</h2>";
        // Log final détaillé
        $this->logToInstallLog("Déploiement NPDS terminé avec succès", 'SUCCESS');
        $this->logToInstallLog("Version: " . ($result['data']['version'] ?? 'inconnue'), 'INFO');
        $this->logToInstallLog("Dossier cible: " . $installPath, 'INFO');
        $sizeInMB = $deployer->getDeployedSize($installPath);
        echo "<p>📦 " . $sizeInMB . " déployés</p>";
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
        
        echo "<p>📁 " . ($fileCount + $dirCount) . " éléments installés (" . $fileCount . " fichiers, " . $dirCount . " dossiers)</p>";
        
        $relativePath = str_replace(__DIR__, '', $installPath);
        if ($relativePath === '')
            $relativePath = ''; //là
        else
            $relativePath = '/' . trim($relativePath, '/');

        $baseUrl = 'https://' . $_SERVER['HTTP_HOST'] . $relativePath;

        echo "<h3>🌐 URL d'accès :</h3>";
        echo "<p><a href='" . $baseUrl . "' target='_blank'>" . $baseUrl . "</a></p>";
        
        echo "<h3>⏭️ Pour terminer l'installation :</h3>";
        echo "<p><a href='" . $baseUrl . "/index.php' target='_blank' style='background: #007bff; color: white; padding: 10px 15px; border-radius: 4px; display: inline-block;'>";
        echo "📋 Lancer l'installation de NPDS</a></p>";
        
        echo "</div>";
    } else {
        echo "<div class='error'>";
        echo "<h2>❌ ERREUR</h2>";
        echo "<p>" . htmlspecialchars($result['message']) . "</p>";
        echo "</div>";
    }

    echo '
         </div>
      </body>
   </html>';
}

// Routeur principal
$operation = $_GET['op'] ?? 'menu';

switch ($operation) {
    case 'deploy':
        if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'yes') {
            die("❌ Sécurité : Confirmez avec &confirm=yes");
        }
        deployNPDS();
        break;
        
    case 'clean':
        if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'yes') {
            die("❌ Confirmez le nettoyage avec &confirm=yes");
        }
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
        echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Déployeur NPDS</title>';
        echo '<style>body{font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5;}';
        echo '.container{background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 800px; margin: 0 auto;}';
        echo 'a{color: #007bff; text-decoration: none;} a:hover{text-decoration: underline;}';
        echo 'ul{list-style: none; padding: 0;} li{margin: 10px 0;}';
        echo '.btn{background: #007bff; color: white; padding: 10px 15px; border-radius: 4px; display: inline-block;}';
        echo '.warning{background: #fff3cd; border: 1px solid #ffeaa7; padding: 10px; margin: 10px 0; border-radius: 4px;}</style></head><body>';
        echo '<div class="container">';
        echo '<h1>🚀 Déployer NPDS</h1>';
        echo '<div class="warning"><strong>⚠️ Attention :</strong> Le déploiement écrase les fichiers existants!</div>';
        
        echo '<h2>🚀 Versions stables :</h2>';
        echo '<ul>';
        echo '<li><a href="?op=deploy&version=v.16.4&path=npds_stable&confirm=yes" onclick="return confirm(\'⚠️ Déployer v.16.4 dans /npds_stable ?\')">Déployer v.16.4 dans /npds_stable</a></li>';
        echo '<li><a href="?op=deploy&version=v.16.4&confirm=yes" onclick="return confirm(\'⚠️ Déployer v.16.4 à la RACINE ?\')">Déployer v.16.4 à la racine</a></li>';
        echo '<li><a href="?op=deploy&version=v.16.3&path=npds_163&confirm=yes" onclick="return confirm(\'⚠️ Déployer v.16.3 dans /npds_163 ?\')">Déployer v.16.3 dans /npds_163</a></li>';
        echo '</ul>';
        
        echo '<h2>🧪 Version développement :</h2>';
        echo '<ul>';
        echo '<li><a href="?op=deploy&version=master&path=npds_dev&confirm=yes" onclick="return confirm(\'⚠️ Déployer MASTER dans /npds_dev ?\')">Déployer MASTER dans /npds_dev</a></li>';
        echo '<li><a href="?op=deploy&version=master&confirm=yes" onclick="return confirm(\'🚨 DANGER : Déployer MASTER à la RACINE ?\')">Déployer MASTER à la racine</a></li>';
        echo '</ul>';
        echo '<p>⚠️ <strong>Master</strong> : Version de développement, peut être instable - Ne pas utiliser en production!</p>';
        
        echo '<h2>🧹 Maintenance :</h2>';
        echo '<ul>';
        echo '<li><a href="?op=clean&confirm=yes" onclick="return confirm(\'Nettoyer les fichiers temporaires ?\')">Nettoyer fichiers temporaires</a></li>';
        echo '<li><a href="?op=info">Info système</a></li>';
        echo '</ul>';
        
        echo '<h2>⚙️ Options avancées :</h2>';
        echo '<form method="GET" style="border: 1px solid #ccc; padding: 15px; border-radius: 5px;">';
        echo '<input type="hidden" name="op" value="deploy" />';
        echo '<label>Version: <input type="text" name="version" value="v.16.4" placeholder="v.16.4 ou master"></label><br /><br />';
        echo '<label>Dossier: <input type="text" name="path" placeholder="npds_portail (laisser vide pour racine)"></label><br /><br />';
        echo '<button type="submit" onclick="return confirm(\'⚠️ Confirmer le déploiement ?\')" class="btn">Déployer</button>';
        echo '<input type="hidden" name="confirm" value="yes" />';
        echo '</form>';
        
        echo '</div></body></html>';
        break;
}
?>
<?php
/**
 * Restore Script for E-Pasien Application
 * Created: 2025-03-26
 * 
 * This script will restore the application files from the backup directory
 */

// Configuration
$backup_dir = __DIR__ . '/backup_2025-03-26_1913';
$target_dir = __DIR__ . '/pasien';

// Function to copy directory
function copyDirectory($src, $dst) {
    if (!file_exists($dst)) {
        mkdir($dst, 0777, true);
    }
    
    $dir = opendir($src);
    while (($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            $srcFile = $src . '/' . $file;
            $dstFile = $dst . '/' . $file;
            if (is_dir($srcFile)) {
                copyDirectory($srcFile, $dstFile);
            } else {
                copy($srcFile, $dstFile);
            }
        }
    }
    closedir($dir);
}

// HTML output
echo "<!DOCTYPE html>
<html>
<head>
    <title>E-Pasien Restore Point</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
        pre { background: #f4f4f4; padding: 10px; }
    </style>
</head>
<body>
    <h1>E-Pasien Restore Point</h1>";

if (isset($_POST['restore'])) {
    try {
        // Backup current files before restore
        $timestamp = date('Y-m-d_His');
        $current_backup = __DIR__ . '/pre_restore_' . $timestamp;
        
        if (file_exists($target_dir)) {
            echo "<p class='info'>Creating backup of current files...</p>";
            copyDirectory($target_dir, $current_backup);
        }

        // Perform restore
        echo "<p class='info'>Restoring files from backup...</p>";
        if (!file_exists($backup_dir)) {
            throw new Exception("Backup directory not found: $backup_dir");
        }

        // Remove current files
        if (file_exists($target_dir)) {
            echo "<p class='info'>Removing current files...</p>";
            system('rm -rf ' . escapeshellarg($target_dir));
        }

        // Copy backup files
        copyDirectory($backup_dir, $target_dir);
        
        echo "<p class='success'>Restore completed successfully!</p>";
        echo "<p>A backup of your previous files was created at: pre_restore_$timestamp</p>";
        
    } catch (Exception $e) {
        echo "<p class='error'>Error during restore: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
} else {
    // Show restore form
    echo "<p>This will restore the E-Pasien application to the working state from backup_2025-03-26_1913.</p>
          <p><strong>Warning:</strong> This will overwrite current files. A backup of current files will be created before restore.</p>
          <form method='post'>
              <input type='submit' name='restore' value='Restore Files' onclick='return confirm(\"Are you sure you want to restore the files?\");'>
          </form>";
}

// Show backup information
echo "<h2>Backup Information:</h2>
<pre>";
echo "Backup Location: " . htmlspecialchars($backup_dir) . "\n";
echo "Target Location: " . htmlspecialchars($target_dir) . "\n";
echo "Backup Date: 2025-03-26_1913\n";
echo "</pre>

<h2>Files Included in Backup:</h2>
<pre>";
function listFiles($dir, $prefix = '') {
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                echo htmlspecialchars($prefix . $file . '/') . "\n";
                listFiles($path, $prefix . '  ');
            } else {
                echo htmlspecialchars($prefix . $file) . "\n";
            }
        }
    }
}
listFiles($backup_dir);
echo "</pre>
</body>
</html>";
?>

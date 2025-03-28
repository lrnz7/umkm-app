<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>
<html>
<head>
    <title>UMKM System Backup/Restore</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: 0 auto; }
        .success { color: green; padding: 10px; background: #e8f5e9; border-radius: 5px; margin: 5px 0; }
        .error { color: red; padding: 10px; background: #ffebee; border-radius: 5px; margin: 5px 0; }
        .info { background: #e3f2fd; padding: 10px; border-radius: 5px; margin: 5px 0; }
        .button { display: inline-block; padding: 10px 20px; background: #4CAF50; color: white; 
                 text-decoration: none; border-radius: 5px; margin: 10px 0; border: none; cursor: pointer; }
        .button.danger { background: #f44336; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
<div class='container'>";

function createBackup($koneksi) {
    $backup_dir = __DIR__ . '/backups';
    if (!file_exists($backup_dir)) {
        mkdir($backup_dir, 0777, true);
    }

    $timestamp = date('Y-m-d_H-i-s');
    $backup_file = $backup_dir . "/backup_$timestamp.sql";
    
    // Get all tables
    $tables = [];
    $result = mysqli_query($koneksi, "SHOW TABLES");
    while ($row = mysqli_fetch_row($result)) {
        $tables[] = $row[0];
    }

    $output = "-- UMKM System Backup\n";
    $output .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";

    // Export structure and data for each table
    foreach ($tables as $table) {
        // Get create table syntax
        $result = mysqli_query($koneksi, "SHOW CREATE TABLE $table");
        $row = mysqli_fetch_row($result);
        $output .= "\n\n" . $row[1] . ";\n\n";

        // Get table data
        $result = mysqli_query($koneksi, "SELECT * FROM $table");
        while ($row = mysqli_fetch_assoc($result)) {
            $fields = array_map(function($value) use ($koneksi) {
                if ($value === null) return 'NULL';
                return "'" . mysqli_real_escape_string($koneksi, $value) . "'";
            }, $row);
            
            $output .= "INSERT INTO $table VALUES (" . implode(", ", $fields) . ");\n";
        }
    }

    // Save backup file
    if (file_put_contents($backup_file, $output)) {
        return basename($backup_file);
    }
    return false;
}

function restoreBackup($koneksi, $file) {
    $backup_dir = __DIR__ . '/backups';
    $backup_file = $backup_dir . '/' . $file;
    
    if (!file_exists($backup_file)) {
        throw new Exception("Backup file not found");
    }

    $sql = file_get_contents($backup_file);
    $queries = array_filter(explode(';', $sql), 'trim');
    
    mysqli_query($koneksi, "SET foreign_key_checks = 0");
    
    foreach ($queries as $query) {
        if (!empty(trim($query))) {
            if (!mysqli_query($koneksi, $query)) {
                throw new Exception("Error executing query: " . mysqli_error($koneksi));
            }
        }
    }
    
    mysqli_query($koneksi, "SET foreign_key_checks = 1");
    return true;
}

try {
    require_once 'pasien/config/koneksi.php';

    // Handle backup request
    if (isset($_POST['backup'])) {
        $backup_file = createBackup($koneksi);
        if ($backup_file) {
            echo "<div class='success'>✓ Backup created successfully: $backup_file</div>";
        } else {
            throw new Exception("Failed to create backup");
        }
    }

    // Handle restore request
    if (isset($_POST['restore']) && isset($_POST['file'])) {
        if (restoreBackup($koneksi, $_POST['file'])) {
            echo "<div class='success'>✓ System restored successfully from backup: {$_POST['file']}</div>";
        }
    }

    // List available backups
    $backup_dir = __DIR__ . '/backups';
    $backups = [];
    if (file_exists($backup_dir)) {
        $backups = array_diff(scandir($backup_dir), ['.', '..']);
    }

    echo "<h1>UMKM System Backup/Restore</h1>";
    
    // Backup form
    echo "<form method='post'>
            <button type='submit' name='backup' class='button'>Create New Backup</button>
          </form>";

    // List backups
    if (!empty($backups)) {
        echo "<h2>Available Backups</h2>
              <form method='post'>";
        
        foreach ($backups as $backup) {
            $timestamp = substr($backup, 7, -4); // Extract timestamp from filename
            $formatted_time = str_replace('_', ' ', $timestamp);
            
            echo "<div class='info'>
                    <input type='radio' name='file' value='$backup' required>
                    $formatted_time
                  </div>";
        }
        
        echo "<button type='submit' name='restore' class='button danger' 
              onclick='return confirm(\"Are you sure you want to restore the system to this backup? Current data will be replaced.\");'>
              Restore Selected Backup</button>
              </form>";
    } else {
        echo "<div class='info'>No backups available</div>";
    }

} catch (Exception $e) {
    echo "<div class='error'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
}

echo "</div></body></html>";
?>

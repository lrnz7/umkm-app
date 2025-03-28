<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>
<html>
<head>
    <title>UMKM System Verification</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: 0 auto; }
        .card { border: 1px solid #ddd; border-radius: 5px; padding: 20px; margin: 10px 0; }
        .success { color: green; background: #e8f5e9; }
        .error { color: red; background: #ffebee; }
        .warning { color: orange; background: #fff3e0; }
        .button { display: inline-block; padding: 10px 20px; background: #4CAF50; color: white; 
                 text-decoration: none; border-radius: 5px; margin: 10px 0; }
        .steps { background: #f5f5f5; padding: 20px; border-radius: 5px; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 3px; }
    </style>
</head>
<body>
<div class='container'>";

try {
    echo "<h1>🔍 UMKM System Verification</h1>";

    // Step 1: Check XAMPP
    echo "<div class='card'>";
    echo "<h2>Step 1: Checking XAMPP Services</h2>";
    
    // Check MySQL
    $mysql_running = @fsockopen("localhost", 3306);
    if ($mysql_running) {
        echo "<p class='success'>✓ MySQL is running</p>";
        fclose($mysql_running);
    } else {
        echo "<p class='error'>✗ MySQL is not running</p>";
    }
    
    // Check Apache
    $apache_running = @fsockopen("localhost", 80);
    if ($apache_running) {
        echo "<p class='success'>✓ Apache is running</p>";
        fclose($apache_running);
    } else {
        echo "<p class='error'>✗ Apache is not running</p>";
    }
    echo "</div>";

    // Step 2: Check Database
    echo "<div class='card'>";
    echo "<h2>Step 2: Checking Database</h2>";
    
    require_once 'pasien/config/koneksi.php';
    
    if (isset($koneksi)) {
        echo "<p class='success'>✓ Database connection successful</p>";
        
        // Check tables
        $required_tables = ['users', 'customers', 'products', 'transactions', 'transaction_details', 'expenses'];
        $missing_tables = [];
        
        foreach ($required_tables as $table) {
            $result = mysqli_query($koneksi, "SHOW TABLES LIKE '$table'");
            if (mysqli_num_rows($result) == 0) {
                $missing_tables[] = $table;
            }
        }
        
        if (empty($missing_tables)) {
            echo "<p class='success'>✓ All required tables exist</p>";
        } else {
            echo "<p class='error'>✗ Missing tables: " . implode(', ', $missing_tables) . "</p>";
            echo "<p><a href='setup_umkm.php' class='button'>Run Database Setup</a></p>";
        }
    }
    echo "</div>";

    // Step 3: Check File Structure
    echo "<div class='card'>";
    echo "<h2>Step 3: Checking File Structure</h2>";
    
    $required_files = [
        'setup_umkm.php',
        'test_umkm_simple.php',
        'database/umkm_simple.sql',
        'pasien/config/koneksi.php'
    ];
    
    $missing_files = [];
    foreach ($required_files as $file) {
        if (!file_exists($file)) {
            $missing_files[] = $file;
        }
    }
    
    if (empty($missing_files)) {
        echo "<p class='success'>✓ All required files exist</p>";
    } else {
        echo "<p class='error'>✗ Missing files: " . implode(', ', $missing_files) . "</p>";
    }
    echo "</div>";

    // Next Steps
    echo "<div class='card steps'>";
    echo "<h2>Next Steps:</h2>";
    echo "<ol>
            <li>If any checks failed:
                <ul>
                    <li>Make sure XAMPP is running</li>
                    <li>Run <a href='setup_umkm.php'>Database Setup</a></li>
                    <li>Check file permissions</li>
                </ul>
            </li>
            <li>If all checks passed:
                <ul>
                    <li><a href='test_umkm_simple.php'>Run the Test Script</a></li>
                    <li>Access the system at <a href='pasien/pasien/'>UMKM System</a></li>
                    <li>Log in with default credentials:
                        <pre>Username: admin
Password: admin123</pre>
                    </li>
                </ul>
            </li>
          </ol>";
    echo "</div>";

} catch (Exception $e) {
    echo "<div class='card error'>";
    echo "<h2>Error Occurred</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "</div>";
}

echo "</div></body></html>";
?>

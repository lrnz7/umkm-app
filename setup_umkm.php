<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>
<html>
<head>
    <title>UMKM Simple Setup</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: 0 auto; }
        .success { color: green; padding: 10px; background: #e8f5e9; border-radius: 5px; margin: 5px 0; }
        .error { color: red; padding: 10px; background: #ffebee; border-radius: 5px; margin: 5px 0; }
        .info { color: blue; padding: 10px; background: #e3f2fd; border-radius: 5px; margin: 5px 0; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f5f5f5; }
        .button { display: inline-block; padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px; margin: 10px 0; }
        .button.red { background: #f44336; }
    </style>
</head>
<body>
<div class='container'>
    <h1>UMKM Simple Database Setup</h1>";

try {
    // Database configuration
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $database = "umkm_db";

    // Create connection
    echo "<div class='info'>Connecting to MySQL...</div>";
    $conn = new mysqli($hostname, $username, $password);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    echo "<div class='success'>Connected to MySQL successfully</div>";

    // Check if setup has been run before
    if (isset($_POST['force_setup'])) {
        // Drop database if exists
        $conn->query("DROP DATABASE IF EXISTS $database");
        echo "<div class='info'>Existing database dropped</div>";
    }

    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS $database";
    if ($conn->query($sql)) {
        echo "<div class='success'>Database '$database' created/verified</div>";
    }

    // Select the database
    $conn->select_db($database);

    if (isset($_POST['force_setup'])) {
        // Read and execute SQL file
        echo "<div class='info'>Reading SQL file...</div>";
        $sql = file_get_contents(__DIR__ . '/database/umkm_simple.sql');
        if (!$sql) {
            throw new Exception("Error reading SQL file");
        }

        // Split SQL into individual queries
        $queries = array_filter(explode(';', $sql), 'trim');
        
        foreach ($queries as $query) {
            if (trim($query)) {
                if (!$conn->query($query)) {
                    throw new Exception("Error executing query: " . $conn->error . "\nQuery: " . $query);
                }
            }
        }
        echo "<div class='success'>Database structure created successfully</div>";
    }

    // Check if tables exist
    $tables = ['users', 'customers', 'products', 'transactions', 'transaction_details', 'expenses'];
    $existing_tables = [];
    
    foreach ($tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if ($result->num_rows > 0) {
            $existing_tables[] = $table;
        }
    }

    if (!empty($existing_tables) && !isset($_POST['force_setup'])) {
        echo "<div class='info'>
            <p>Found existing tables: " . implode(', ', $existing_tables) . "</p>
            <p>Do you want to reset the database? This will delete all existing data!</p>
            <form method='post'>
                <input type='submit' name='force_setup' value='Reset Database' class='button red' 
                       onclick='return confirm(\"Are you sure? This will delete all existing data!\");'>
            </form>
        </div>";
    }

    if (empty($existing_tables) || isset($_POST['force_setup'])) {
        // Verify tables
        echo "<h2>Database Structure:</h2>";
        
        foreach ($tables as $table) {
            $result = $conn->query("DESCRIBE $table");
            if (!$result) {
                throw new Exception("Error describing table $table: " . $conn->error);
            }

            echo "<h3>Table: $table</h3>";
            echo "<table>
                    <tr>
                        <th>Field</th>
                        <th>Type</th>
                        <th>Null</th>
                        <th>Key</th>
                        <th>Default</th>
                    </tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['Field']}</td>
                        <td>{$row['Type']}</td>
                        <td>{$row['Null']}</td>
                        <td>{$row['Key']}</td>
                        <td>{$row['Default']}</td>
                    </tr>";
            }
            echo "</table>";
        }

        echo "<div class='success'>
            <h2>✅ Setup Complete!</h2>
            <p>The database has been set up successfully.</p>
            <p><a href='test_umkm_simple.php' class='button'>Run Tests</a></p>
            <h3>Default Admin Account:</h3>
            <pre>Username: admin
Password: admin123</pre>
        </div>";
    }

} catch (Exception $e) {
    echo "<div class='error'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

if (isset($conn)) {
    $conn->close();
}

echo "</div></body></html>";
?>

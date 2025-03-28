<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>
<html>
<head>
    <title>UMKM Database Setup</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        pre { background: #f4f4f4; padding: 10px; }
        .container { max-width: 800px; margin: 0 auto; }
    </style>
</head>
<body>
<div class='container'>
    <h1>UMKM Database Setup</h1>";

try {
    // Database configuration
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $database = "umkm_db";

    // Create connection
    echo "<h2>Connecting to MySQL...</h2>";
    $conn = new mysqli($hostname, $username, $password);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    echo "<p class='success'>✓ Connected to MySQL successfully</p>";

    // Create database if not exists
    echo "<h2>Setting up database...</h2>";
    $sql = "CREATE DATABASE IF NOT EXISTS $database";
    if ($conn->query($sql) === TRUE) {
        echo "<p class='success'>✓ Database '$database' created successfully</p>";
    } else {
        throw new Exception("Error creating database: " . $conn->error);
    }

    // Select the database
    $conn->select_db($database);
    echo "<p class='success'>✓ Selected database '$database'</p>";

    // Read and execute SQL file
    echo "<h2>Importing database structure...</h2>";
    $sql_file = file_get_contents(__DIR__ . '/database/umkm_db.sql');
    
    if ($sql_file === false) {
        throw new Exception("Error reading SQL file");
    }

    // Split SQL file into individual queries
    $queries = array_filter(explode(';', $sql_file), 'trim');
    
    foreach ($queries as $query) {
        if (trim($query)) {
            if ($conn->query($query) === FALSE) {
                throw new Exception("Error executing query: " . $conn->error . "\n\nQuery: " . $query);
            }
        }
    }
    echo "<p class='success'>✓ Database structure imported successfully</p>";

    // Verify tables
    echo "<h2>Verifying database structure...</h2>";
    $tables = ['customers', 'products', 'transactions', 'expenses', 'users'];
    
    foreach ($tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if ($result->num_rows > 0) {
            echo "<p class='success'>✓ Table '$table' exists</p>";
            
            // Show table structure
            $structure = $conn->query("DESCRIBE $table");
            echo "<pre>";
            while ($row = $structure->fetch_assoc()) {
                echo $row['Field'] . " - " . $row['Type'] . 
                     ($row['Null'] === 'NO' ? ' NOT NULL' : '') . 
                     ($row['Key'] === 'PRI' ? ' PRIMARY KEY' : '') . "\n";
            }
            echo "</pre>";
        } else {
            throw new Exception("Table '$table' was not created properly");
        }
    }

    echo "<h2>Setup Complete!</h2>";
    echo "<p class='success'>The UMKM database has been successfully set up.</p>";
    
    echo "<h3>Default Admin Account:</h3>";
    echo "<pre>
Username: admin
Password: password
</pre>";

    echo "<h3>Next Steps:</h3>";
    echo "<ol>
        <li>Update the database connection settings in your application</li>
        <li>Change the default admin password</li>
        <li>Start adding products and customers</li>
    </ol>";

} catch (Exception $e) {
    echo "<p class='error'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

if (isset($conn)) {
    $conn->close();
}

echo "</div></body></html>";
?>

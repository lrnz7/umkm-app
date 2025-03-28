<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$hostname = "localhost";
$username = "root";
$password = "";
$database = "dbpasien";

echo "<h2>Database Connection Test</h2>";

// Try to connect to MySQL
$conn = mysqli_connect($hostname, $username, $password);
if (!$conn) {
    die("<p style='color:red'>MySQL Connection failed: " . mysqli_connect_error() . "</p>");
}
echo "<p style='color:green'>✓ MySQL Connection successful</p>";

// Check if database exists
$db_check = mysqli_select_db($conn, $database);
if (!$db_check) {
    die("<p style='color:red'>Database '$database' does not exist</p>");
}
echo "<p style='color:green'>✓ Database '$database' exists</p>";

// Check if tables exist
$tables = ['tabel_pasien', 'tabel_dokter', 'tabel_status', 'user'];
foreach ($tables as $table) {
    $result = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
    if (mysqli_num_rows($result) > 0) {
        echo "<p style='color:green'>✓ Table '$table' exists</p>";
        
        // Check table structure
        $cols = mysqli_query($conn, "SHOW COLUMNS FROM $table");
        echo "<div style='margin-left:20px'>";
        echo "<strong>Columns in '$table':</strong><br>";
        while ($col = mysqli_fetch_assoc($cols)) {
            echo "- " . $col['Field'] . " (" . $col['Type'] . ")<br>";
        }
        echo "</div>";
    } else {
        echo "<p style='color:red'>✗ Table '$table' does not exist</p>";
    }
}

mysqli_close($conn);
?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>
<html>
<head>
    <title>UMKM Login Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: 0 auto; }
        .success { color: green; padding: 10px; background: #e8f5e9; border-radius: 5px; margin: 5px 0; }
        .error { color: red; padding: 10px; background: #ffebee; border-radius: 5px; margin: 5px 0; }
        .info { background: #e3f2fd; padding: 10px; border-radius: 5px; margin: 5px 0; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
<div class='container'>
    <h1>UMKM Login System Test</h1>";

try {
    require_once 'pasien/config/koneksi.php';

    // Test 1: Database Connection
    echo "<h2>Test 1: Database Connection</h2>";
    if ($koneksi) {
        echo "<div class='success'>✓ Database connection successful</div>";
    } else {
        throw new Exception("Database connection failed");
    }

    // Test 2: Check Users Table
    echo "<h2>Test 2: Users Table</h2>";
    $result = mysqli_query($koneksi, "SELECT * FROM users WHERE username = 'admin'");
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        echo "<div class='success'>✓ Admin user exists</div>";
        echo "<div class='info'>
            Username: admin<br>
            Role: {$user['role']}<br>
            Email: {$user['email']}
        </div>";
    } else {
        throw new Exception("Admin user not found");
    }

    // Test 3: Password Verification
    echo "<h2>Test 3: Password Verification</h2>";
    if (password_verify('admin123', $user['password'])) {
        echo "<div class='success'>✓ Password verification working</div>";
    } else {
        throw new Exception("Password verification failed");
    }

    // Test 4: Login Logs
    echo "<h2>Test 4: Login Logs</h2>";
    $result = mysqli_query($koneksi, "SHOW TABLES LIKE 'login_logs'");
    if (mysqli_num_rows($result) > 0) {
        echo "<div class='success'>✓ Login logs table exists</div>";
        
        // Test inserting a log
        $sql = "INSERT INTO login_logs (user_id, login_time, status) VALUES (?, NOW(), 'Success')";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "i", $user['user_id']);
        if (mysqli_stmt_execute($stmt)) {
            echo "<div class='success'>✓ Successfully logged login attempt</div>";
        }
    } else {
        throw new Exception("Login logs table not found");
    }

    echo "<div class='info'>
        <h3>Next Steps:</h3>
        <ol>
            <li>Visit the main system: <a href='/pasien/pasien/'>http://localhost/pasien/pasien/</a></li>
            <li>Log in with:
                <pre>Username: admin
Password: admin123</pre>
            </li>
            <li>After successful login, you should see the UMKM dashboard</li>
        </ol>
    </div>";

} catch (Exception $e) {
    echo "<div class='error'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    echo "<div class='info'>
        <h3>Troubleshooting:</h3>
        <ol>
            <li>Make sure you've run setup_umkm.php first</li>
            <li>Verify XAMPP is running (Apache & MySQL)</li>
            <li>Check database credentials in config/koneksi.php</li>
        </ol>
    </div>";
}

echo "</div></body></html>";
?>

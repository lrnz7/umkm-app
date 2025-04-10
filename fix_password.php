<?php
require_once 'pasien/config/koneksi.php';

// Create a known password hash
$password = 'admin';
$hash = password_hash($password, PASSWORD_DEFAULT);

// Update the admin user's password
$sql = "UPDATE users SET password = ? WHERE username = 'admin'";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "s", $hash);

if (mysqli_stmt_execute($stmt)) {
    echo "Password successfully reset to: admin";
} else {
    echo "Error updating password: " . mysqli_error($koneksi);
}

// Verify the password was set correctly
$sql = "SELECT password FROM users WHERE username = 'admin'";
$result = mysqli_query($koneksi, $sql);
$user = mysqli_fetch_assoc($result);

if (password_verify('admin', $user['password'])) {
    echo "\nPassword verification successful!";
} else {
    echo "\nPassword verification failed!";
}
?>

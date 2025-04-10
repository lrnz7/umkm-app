<?php
require_once 'pasien/config/koneksi.php';

$password = 'admin';
$hash = password_hash($password, PASSWORD_DEFAULT);

$sql = "UPDATE users SET password = ? WHERE username = 'admin'";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "s", $hash);

if (mysqli_stmt_execute($stmt)) {
    echo "Password successfully reset to: admin\n";
} else {
    echo "Error updating password: " . mysqli_error($koneksi) . "\n";
}

mysqli_stmt_close($stmt);
mysqli_close($koneksi);
?>

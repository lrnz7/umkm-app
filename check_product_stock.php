<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'pasien/config/koneksi.php';

$result = mysqli_query($koneksi, "SELECT product_name, stock FROM products ORDER BY product_name");
if (!$result) {
    die("Query failed: " . mysqli_error($koneksi));
}

if (mysqli_num_rows($result) === 0) {
    echo "No products found in the database.\n";
    exit;
}

echo "Product stock in database:\n";
while ($row = mysqli_fetch_assoc($result)) {
    echo $row['product_name'] . " - Stock: " . $row['stock'] . "\n";
}
?>

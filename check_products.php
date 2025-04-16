<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'pasien/config/koneksi.php';

$result = mysqli_query($koneksi, "SELECT product_name, price FROM products ORDER BY product_name");
if (!$result) {
    die("Query failed: " . mysqli_error($koneksi));
}

if (mysqli_num_rows($result) === 0) {
    file_put_contents('product_prices_output.txt', "No products found in the database.\n");
    exit;
}

$output = "Products in database:\n";
while ($row = mysqli_fetch_assoc($result)) {
    $output .= $row['product_name'] . " - Price: " . $row['price'] . "\n";
}

file_put_contents('product_prices_output.txt', $output);
?>

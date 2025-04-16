<?php
require 'pasien/config/koneksi.php';

// Get all product IDs and names
$result = mysqli_query($koneksi, "SELECT product_id, product_name FROM products");
if (!$result) {
    die("Query failed: " . mysqli_error($koneksi));
}

echo "Updating product stock with random values between 1 and 5...\n";

while ($row = mysqli_fetch_assoc($result)) {
    $product_id = $row['product_id'];
    $product_name = $row['product_name'];
    $random_stock = rand(1, 5);

    $sql = "UPDATE products SET stock = ? WHERE product_id = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    if (!$stmt) {
        echo "Prepare failed for product '$product_name': " . mysqli_error($koneksi) . "\n";
        continue;
    }
    mysqli_stmt_bind_param($stmt, "ii", $random_stock, $product_id);
    $exec = mysqli_stmt_execute($stmt);
    if (!$exec) {
        echo "Execute failed for product '$product_name': " . mysqli_stmt_error($stmt) . "\n";
        continue;
    }
    mysqli_stmt_close($stmt);

    echo "Updated '$product_name' stock to $random_stock\n";
}

echo "Product stock update completed.\n";
?>

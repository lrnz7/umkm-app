<?php
require 'pasien/config/koneksi.php';

// Prices for each product
$products = [
    'iPhone X 64GB' => 3000000,
    'iPhone X 128GB' => 3300000,
    'iPhone XS 256GB' => 3600000,
    'iPhone 11 64GB' => 4300000,
    'iPhone 11 128GB' => 4600000,
    'iPhone 11 Pro 128GB' => 5000000,
    'iPhone 11 Pro 512GB' => 5200000,
    'iPhone 11 Pro Max 512GB' => 5500000,
    'iPhone 12 128GB' => 5800000,
    'iPhone 12 Pro 128GB' => 6100000,
    'iPhone 12 Pro Max 512GB' => 6300000,
    'iPhone 13 128GB' => 6800000,
    'iPhone 13 Pro 128GB' => 7000000,
    'iPhone 13 Pro Max 512GB' => 7600000,
    'iPhone 14 128GB' => 8200000,
    'iPhone 14 Pro 128GB' => 9300000,
    'iPhone 14 Pro Max 512GB' => 9700000,
    'iPhone 15 128GB' => 12000000,
    'iPhone 15 Pro 128GB' => 13300000,
    'iPhone 15 Pro Max 512GB' => 145000000,
];

// Update prices and set random stock
foreach ($products as $product_name => $price) {
    $stock = rand(1, 3); // Random stock between 1 and 3
    $sql = "UPDATE products SET price = ?, stock = ? WHERE product_name = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "dis", $price, $stock, $product_name);
    mysqli_stmt_execute($stmt);
}

echo "Stock and prices updated successfully!";
?>

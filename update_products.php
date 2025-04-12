<?php
require 'pasien/config/koneksi.php';

// New product names
$new_products = [
    'iPhone X 64GB',
    'iPhone X 128GB',
    'iPhone XS 256GB',
    'iPhone 11 64GB',
    'iPhone 11 128GB',
    'iPhone 11 Pro 128GB',
    'iPhone 11 Pro 512GB',
    'iPhone 11 Pro Max 512GB',
    'iPhone 12 128GB',
    'iPhone 12 Pro 128GB',
    'iPhone 12 Pro Max 512GB',
    'iPhone 13 128GB',
    'iPhone 13 Pro 128GB',
    'iPhone 13 Pro Max 512GB',
    'iPhone 14 128GB',
    'iPhone 14 Pro 128GB',
    'iPhone 14 Pro Max 512GB',
    'iPhone 15 128GB',
    'iPhone 15 Pro 128GB',
    'iPhone 15 Pro Max 512GB'
];

// Clear existing products
mysqli_query($koneksi, "DELETE FROM products");

// Insert new products
foreach ($new_products as $product_name) {
    $sql = "INSERT INTO products (product_name, category, price, stock) VALUES (?, 'Smartphone', 0, 0)";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "s", $product_name);
    mysqli_stmt_execute($stmt);
}

echo "Products updated successfully!";
?>

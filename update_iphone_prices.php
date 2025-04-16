<?php
require 'pasien/config/koneksi.php';

// Updated used/inter iPhone prices in IDR (based on typical Indonesian market prices)
$products = [
    'iPhone X 64GB' => 3500000,      // Used X series around 3-4jt
    'iPhone X 128GB' => 3800000,
    'iPhone XS 256GB' => 4200000,
    'iPhone 11 64GB' => 5000000,     // Used iPhone 11 around 5-6jt
    'iPhone 11 128GB' => 5500000,
    'iPhone 11 Pro 128GB' => 6500000,
    'iPhone 11 Pro 512GB' => 7000000,
    'iPhone 11 Pro Max 512GB' => 7500000,
    'iPhone 12 128GB' => 7000000,    // Used iPhone 12 around 7-8jt
    'iPhone 12 Pro 128GB' => 8500000,
    'iPhone 12 Pro Max 512GB' => 9500000,
    'iPhone 13 128GB' => 9000000,    // Used iPhone 13 around 9-11jt
    'iPhone 13 Pro 128GB' => 11000000,
    'iPhone 13 Pro Max 512GB' => 13000000,
    'iPhone 14 128GB' => 12000000,   // Used iPhone 14 around 12-14jt
    'iPhone 14 Pro 128GB' => 14000000,
    'iPhone 14 Pro Max 512GB' => 16000000,
    'iPhone 15 128GB' => 15000000,   // Used/Inter iPhone 15 around 15-18jt
    'iPhone 15 Pro 128GB' => 17000000,
    'iPhone 15 Pro Max 512GB' => 19000000
];

// Update prices in database
foreach ($products as $name => $price) {
    $sql = "UPDATE products SET price = ? WHERE product_name = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    if (!$stmt) {
        echo "Prepare failed for product '$name': " . mysqli_error($koneksi) . "\n";
        continue;
    }
    $bind = mysqli_stmt_bind_param($stmt, "ds", $price, $name);
    if (!$bind) {
        echo "Bind param failed for product '$name': " . mysqli_stmt_error($stmt) . "\n";
        continue;
    }
    $exec = mysqli_stmt_execute($stmt);
    if (!$exec) {
        echo "Execute failed for product '$name': " . mysqli_stmt_error($stmt) . "\n";
        continue;
    }
    $affected_rows = mysqli_stmt_affected_rows($stmt);
    if ($affected_rows === 0) {
        echo "No rows updated for product '$name'. Check if product_name matches.\n";
    }
    mysqli_stmt_close($stmt);
}

// Verify updates
$result = mysqli_query($koneksi, "SELECT product_name, price FROM products ORDER BY price DESC");
echo "Updated iPhone Prices (Used/Inter):\n\n";
while ($row = mysqli_fetch_assoc($result)) {
    echo $row['product_name'] . ": Rp " . number_format($row['price'], 0, ',', '.') . "\n";
}
?>

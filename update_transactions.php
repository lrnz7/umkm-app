<?php
require 'pasien/config/koneksi.php';

// Get admin user ID
$result = mysqli_query($koneksi, "SELECT user_id FROM users WHERE username = 'admin' LIMIT 1");
$admin = mysqli_fetch_assoc($result);
$admin_id = $admin['user_id'];

// Get some product IDs
$result = mysqli_query($koneksi, "SELECT product_id, price FROM products LIMIT 5");
$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

// Clear existing transactions
mysqli_query($koneksi, "DELETE FROM transactions");
mysqli_query($koneksi, "DELETE FROM transaction_details");

// Insert sample transactions
$payment_methods = ['Cash', 'Bank Transfer', 'E-Wallet', 'QRIS'];
$dates = [
    date('Y-m-d H:i:s'),
    date('Y-m-d H:i:s', strtotime('-1 day')),
    date('Y-m-d H:i:s', strtotime('-2 days')),
    date('Y-m-d H:i:s', strtotime('-3 days')),
    date('Y-m-d H:i:s', strtotime('-4 days'))
];

foreach ($dates as $date) {
    // Create transaction
    $payment_method = $payment_methods[array_rand($payment_methods)];
    $total_amount = 0;
    
    $sql = "INSERT INTO transactions (user_id, transaction_date, transaction_type, payment_method, total_amount) 
            VALUES (?, ?, 'Income', ?, 0)";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "iss", $admin_id, $date, $payment_method);
    mysqli_stmt_execute($stmt);
    
    $transaction_id = mysqli_insert_id($koneksi);
    
    // Add 1-3 random products to this transaction
    $num_products = rand(1, 3);
    $transaction_total = 0;
    
    for ($i = 0; $i < $num_products; $i++) {
        $product = $products[array_rand($products)];
        $quantity = rand(1, 2);
        $subtotal = $product['price'] * $quantity;
        $transaction_total += $subtotal;
        
        $sql = "INSERT INTO transaction_details (transaction_id, product_id, quantity, price_at_transaction, subtotal) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "iiidi", $transaction_id, $product['product_id'], $quantity, $product['price'], $subtotal);
        mysqli_stmt_execute($stmt);
    }
    
    // Update transaction total
    $sql = "UPDATE transactions SET total_amount = ? WHERE transaction_id = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "di", $transaction_total, $transaction_id);
    mysqli_stmt_execute($stmt);
}

echo "Sample transactions have been added successfully!";
?>

<?php
require 'pasien/config/koneksi.php';

// Get admin user ID
$result = mysqli_query($koneksi, "SELECT user_id FROM users WHERE username = 'admin' LIMIT 1");
$admin = mysqli_fetch_assoc($result);
$admin_id = $admin['user_id'];

// Clear existing expenses
mysqli_query($koneksi, "DELETE FROM expenses");

// Add some sample expenses for today
$expense_categories = ['Rent', 'Utilities', 'Supplies', 'Maintenance'];
$expense_amounts = [1500000, 500000, 750000, 300000];

for ($i = 0; $i < count($expense_categories); $i++) {
    $sql = "INSERT INTO expenses (user_id, expense_date, expense_category, amount, notes) 
            VALUES (?, CURDATE(), ?, ?, 'Monthly expense')";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "isd", $admin_id, $expense_categories[$i], $expense_amounts[$i]);
    mysqli_stmt_execute($stmt);
}

// Add some transactions for today
$sql = "INSERT INTO transactions (user_id, transaction_date, transaction_type, payment_method, total_amount) 
        VALUES (?, CURDATE(), 'Income', 'Cash', 5000000)";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "i", $admin_id);
mysqli_stmt_execute($stmt);

$transaction_id = mysqli_insert_id($koneksi);

// Get a random product
$result = mysqli_query($koneksi, "SELECT product_id, price FROM products ORDER BY RAND() LIMIT 1");
$product = mysqli_fetch_assoc($result);

// Add transaction details
$quantity = 2;
$subtotal = $product['price'] * $quantity;
$sql = "INSERT INTO transaction_details (transaction_id, product_id, quantity, price_at_transaction, subtotal) 
        VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "iiidi", $transaction_id, $product['product_id'], $quantity, $product['price'], $subtotal);
mysqli_stmt_execute($stmt);

echo "Today's income and expenses have been updated!\n";
echo "\nToday's Income: Rp " . number_format(5000000, 0, ',', '.') . "\n";
echo "Today's Expenses: Rp " . number_format(array_sum($expense_amounts), 0, ',', '.');
?>

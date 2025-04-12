<?php
require 'pasien/config/koneksi.php';

// First, let's check if we have the customer_type column
$result = mysqli_query($koneksi, "SHOW COLUMNS FROM transactions LIKE 'customer_type'");
if (mysqli_num_rows($result) == 0) {
    // Add customer_type column if it doesn't exist
    mysqli_query($koneksi, "ALTER TABLE transactions ADD COLUMN customer_type VARCHAR(50) AFTER transaction_id");
}

// Get admin user ID
$result = mysqli_query($koneksi, "SELECT user_id FROM users WHERE username = 'admin' LIMIT 1");
$admin = mysqli_fetch_assoc($result);
$admin_id = $admin['user_id'];

// Clear existing transactions
mysqli_query($koneksi, "DELETE FROM transaction_details");
mysqli_query($koneksi, "DELETE FROM transactions");

// Reset auto-increment
mysqli_query($koneksi, "ALTER TABLE transactions AUTO_INCREMENT = 1");
mysqli_query($koneksi, "ALTER TABLE transaction_details AUTO_INCREMENT = 1");

// Sample transactions with reasonable quantities and totals
$transactions = [
    [
        'type' => 'Walk-in Customer',
        'product' => 'iPhone 11 64GB',
        'quantity' => 1,
        'payment' => 'Cash',
        'total' => 5000000 // Reasonable total
    ],
    [
        'type' => 'Online Customer',
        'product' => 'iPhone 12 128GB',
        'quantity' => 1,
        'payment' => 'Bank Transfer',
        'total' => 7000000 // Reasonable total
    ],
    [
        'type' => 'Walk-in Customer',
        'product' => 'iPhone X 64GB',
        'quantity' => 1,
        'payment' => 'Cash',
        'total' => 3500000 // Reasonable total
    ],
    [
        'type' => 'Online Customer',
        'product' => 'iPhone 13 128GB',
        'quantity' => 1,
        'payment' => 'QRIS',
        'total' => 9000000 // Reasonable total
    ],
    [
        'type' => 'Walk-in Customer',
        'product' => 'iPhone 14 128GB',
        'quantity' => 1,
        'payment' => 'E-Wallet',
        'total' => 12000000 // Reasonable total
    ]
];

// Add sample transactions
foreach ($transactions as $t) {
    // Get product details
    $sql = "SELECT product_id, price FROM products WHERE product_name = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "s", $t['product']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($result);
    
    if ($product) {
        // Insert transaction
        $sql = "INSERT INTO transactions (customer_type, user_id, transaction_date, transaction_type, payment_method, total_amount) 
                VALUES (?, ?, DATE_SUB(NOW(), INTERVAL RAND()*7 DAY), 'Income', ?, ?)";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "sisd", 
            $t['type'],
            $admin_id,
            $t['payment'],
            $t['total']
        );
        mysqli_stmt_execute($stmt);
        
        $transaction_id = mysqli_insert_id($koneksi);
        
        // Insert transaction details
        $sql = "INSERT INTO transaction_details (transaction_id, product_id, quantity, price_at_transaction, subtotal) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($koneksi, $sql);
        $subtotal = $t['total'];
        mysqli_stmt_bind_param($stmt, "iiidd", 
            $transaction_id,
            $product['product_id'],
            $t['quantity'],
            $product['price'],
            $subtotal
        );
        mysqli_stmt_execute($stmt);
    }
}

echo "Transactions have been reset with reasonable prices and quantities.\n\n";

// Show the new transactions
$sql = "SELECT t.transaction_date, t.customer_type, p.product_name, 
        td.quantity, t.total_amount, t.payment_method
        FROM transactions t
        JOIN transaction_details td ON t.transaction_id = td.transaction_id
        JOIN products p ON td.product_id = p.product_id
        ORDER BY t.transaction_date DESC";

$result = mysqli_query($koneksi, $sql);
echo "New Transactions:\n";
while ($row = mysqli_fetch_assoc($result)) {
    echo "\nDate: " . $row['transaction_date'];
    echo "\nCustomer: " . $row['customer_type'];
    echo "\nProduct: " . $row['product_name'];
    echo "\nQuantity: " . $row['quantity'];
    echo "\nTotal: Rp " . number_format($row['total_amount'], 0, ',', '.');
    echo "\nPayment: " . $row['payment_method'];
    echo "\n-------------------";
}
?>

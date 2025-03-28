<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>
<html>
<head>
    <title>UMKM Simple Database Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: 0 auto; }
        .success { color: green; padding: 10px; background: #e8f5e9; border-radius: 5px; margin: 5px 0; }
        .error { color: red; padding: 10px; background: #ffebee; border-radius: 5px; margin: 5px 0; }
        .info { background: #e3f2fd; padding: 10px; border-radius: 5px; margin: 5px 0; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f5f5f5; }
    </style>
</head>
<body>
<div class='container'>
    <h1>UMKM Simple Database Test</h1>";

try {
    require_once 'pasien/config/koneksi.php';

    // Test 1: Add New Customer
    echo "<h2>Test 1: Add New Customer</h2>";
    $customerData = [
        'customer_name' => 'Test Customer',
        'phone_number' => '081234567890',
        'email' => 'test@customer.com',
        'address' => 'Test Address 123'
    ];
    $sql = "INSERT INTO customers (customer_name, phone_number, email, address) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", 
        $customerData['customer_name'],
        $customerData['phone_number'],
        $customerData['email'],
        $customerData['address']
    );
    mysqli_stmt_execute($stmt);
    echo "<div class='success'>✓ Customer added successfully</div>";

    // Test 2: Add New Product
    echo "<h2>Test 2: Add New Product</h2>";
    $productData = [
        'product_name' => 'Test Product',
        'category' => 'Test Category',
        'price' => 25000,
        'stock' => 50
    ];
    $sql = "INSERT INTO products (product_name, category, price, stock) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "ssdi", 
        $productData['product_name'],
        $productData['category'],
        $productData['price'],
        $productData['stock']
    );
    mysqli_stmt_execute($stmt);
    echo "<div class='success'>✓ Product added successfully</div>";

    // Test 3: Create Transaction
    echo "<h2>Test 3: Create Transaction</h2>";
    // First get customer_id and product_id
    $customer_id = mysqli_insert_id($koneksi);
    $product_id = mysqli_insert_id($koneksi);
    
    // Start transaction
    mysqli_begin_transaction($koneksi);
    
    try {
        // Insert main transaction
        $sql = "INSERT INTO transactions (customer_id, user_id, transaction_type, payment_method, total_amount, notes) 
                VALUES (?, 1, 'Income', 'Cash', ?, 'Test transaction')";
        $stmt = mysqli_prepare($koneksi, $sql);
        $total = 50000;
        mysqli_stmt_bind_param($stmt, "id", $customer_id, $total);
        mysqli_stmt_execute($stmt);
        $transaction_id = mysqli_insert_id($koneksi);

        // Insert transaction detail
        $sql = "INSERT INTO transaction_details (transaction_id, product_id, quantity, price_at_transaction, subtotal) 
                VALUES (?, ?, 2, 25000, 50000)";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $transaction_id, $product_id);
        mysqli_stmt_execute($stmt);

        mysqli_commit($koneksi);
        echo "<div class='success'>✓ Transaction created successfully</div>";
    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        throw $e;
    }

    // Test 4: Record Expense
    echo "<h2>Test 4: Record Expense</h2>";
    $sql = "INSERT INTO expenses (user_id, expense_category, amount, notes) VALUES (1, 'Raw Materials', 30000, 'Test expense')";
    mysqli_query($koneksi, $sql);
    echo "<div class='success'>✓ Expense recorded successfully</div>";

    // Test 5: View Transaction Summary
    echo "<h2>Test 5: Transaction Summary</h2>";
    $sql = "SELECT t.transaction_id, c.customer_name, t.transaction_type, 
            t.payment_method, t.total_amount, t.transaction_date,
            td.quantity, p.product_name, td.price_at_transaction
            FROM transactions t
            JOIN customers c ON t.customer_id = c.customer_id
            JOIN transaction_details td ON t.transaction_id = td.transaction_id
            JOIN products p ON td.product_id = p.product_id
            ORDER BY t.transaction_date DESC LIMIT 5";
    
    $result = mysqli_query($koneksi, $sql);
    
    echo "<table>
            <tr>
                <th>Transaction ID</th>
                <th>Customer</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
                <th>Type</th>
                <th>Payment</th>
                <th>Date</th>
            </tr>";
    
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['transaction_id']}</td>
                <td>{$row['customer_name']}</td>
                <td>{$row['product_name']}</td>
                <td>{$row['quantity']}</td>
                <td>{$row['price_at_transaction']}</td>
                <td>{$row['total_amount']}</td>
                <td>{$row['transaction_type']}</td>
                <td>{$row['payment_method']}</td>
                <td>{$row['transaction_date']}</td>
            </tr>";
    }
    echo "</table>";

    echo "<div class='info'>
        <h3>Next Steps:</h3>
        <ol>
            <li>Access the system at: <a href='/pasien/pasien/'>http://localhost/pasien/pasien/</a></li>
            <li>Login with:
                <pre>Username: admin
Password: admin123</pre>
            </li>
            <li>Start using the system for real transactions</li>
        </ol>
    </div>";

} catch (Exception $e) {
    echo "<div class='error'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "</div></body></html>";
?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'pasien/config/koneksi.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>UMKM Database Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        pre { background: #f4f4f4; padding: 10px; }
        .container { max-width: 800px; margin: 0 auto; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
<div class='container'>
    <h1>UMKM Database Test</h1>";

try {
    // Test 1: Insert Customer
    echo "<h2>Test 1: Insert Customer</h2>";
    $customerData = [
        'customer_name' => 'John Doe',
        'address' => 'Test Address 123',
        'phone_number' => '08123456789',
        'email' => 'john@example.com',
        'join_date' => date('Y-m-d')
    ];
    insertData('customers', $customerData);
    echo "<p class='success'>✓ Customer inserted successfully</p>";

    // Test 2: Insert Product
    echo "<h2>Test 2: Insert Product</h2>";
    $productData = [
        'product_name' => 'Test Product',
        'category' => 'Electronics',
        'price' => 100000.00,
        'stock' => 10,
        'description' => 'Test product description'
    ];
    insertData('products', $productData);
    echo "<p class='success'>✓ Product inserted successfully</p>";

    // Test 3: Insert Transaction
    echo "<h2>Test 3: Insert Transaction</h2>";
    $transactionData = [
        'customer_id' => 1,
        'product_id' => 1,
        'quantity' => 2,
        'total_price' => 200000.00,
        'transaction_date' => date('Y-m-d H:i:s'),
        'payment_method' => 'Cash'
    ];
    insertData('transactions', $transactionData);
    echo "<p class='success'>✓ Transaction inserted successfully</p>";

    // Test 4: Insert Expense
    echo "<h2>Test 4: Insert Expense</h2>";
    $expenseData = [
        'category' => 'Raw Materials',
        'amount' => 50000.00,
        'expense_date' => date('Y-m-d'),
        'description' => 'Test expense'
    ];
    insertData('expenses', $expenseData);
    echo "<p class='success'>✓ Expense inserted successfully</p>";

    // Test 5: Query Relationships
    echo "<h2>Test 5: Query Relationships</h2>";
    $sql = "
        SELECT 
            t.transaction_id,
            c.customer_name,
            p.product_name,
            t.quantity,
            t.total_price,
            t.payment_method
        FROM transactions t
        JOIN customers c ON t.customer_id = c.customer_id
        JOIN products p ON t.product_id = p.product_id
        ORDER BY t.transaction_date DESC
        LIMIT 5
    ";
    
    $transactions = getRows($sql);
    
    if (!empty($transactions)) {
        echo "<table>
                <tr>
                    <th>Transaction ID</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Payment Method</th>
                </tr>";
        
        foreach ($transactions as $t) {
            echo "<tr>
                    <td>{$t['transaction_id']}</td>
                    <td>{$t['customer_name']}</td>
                    <td>{$t['product_name']}</td>
                    <td>{$t['quantity']}</td>
                    <td>{$t['total_price']}</td>
                    <td>{$t['payment_method']}</td>
                </tr>";
        }
        echo "</table>";
        echo "<p class='success'>✓ Relationships working correctly</p>";
    }

    // Test 6: Check Database Structure
    echo "<h2>Test 6: Database Structure</h2>";
    $tables = ['customers', 'products', 'transactions', 'expenses', 'users'];
    
    foreach ($tables as $table) {
        $result = mysqli_query($koneksi, "SHOW COLUMNS FROM $table");
        echo "<h3>Table: $table</h3>";
        echo "<table>
                <tr>
                    <th>Field</th>
                    <th>Type</th>
                    <th>Null</th>
                    <th>Key</th>
                    <th>Default</th>
                </tr>";
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['Field']}</td>
                    <td>{$row['Type']}</td>
                    <td>{$row['Null']}</td>
                    <td>{$row['Key']}</td>
                    <td>{$row['Default']}</td>
                </tr>";
        }
        echo "</table>";
    }

    echo "<h2>All Tests Completed Successfully!</h2>";
    echo "<p class='success'>The database structure and relationships are working as expected.</p>";

} catch (Exception $e) {
    echo "<p class='error'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "</div></body></html>";
?>

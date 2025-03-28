<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>
<html>
<head>
    <title>Load Sample Data - UMKM System</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: 0 auto; }
        .success { color: green; padding: 10px; background: #e8f5e9; border-radius: 5px; margin: 5px 0; }
        .error { color: red; padding: 10px; background: #ffebee; border-radius: 5px; margin: 5px 0; }
        .info { background: #e3f2fd; padding: 10px; border-radius: 5px; margin: 5px 0; }
        .warning { background: #fff3e0; padding: 10px; border-radius: 5px; margin: 5px 0; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }
        .button { display: inline-block; padding: 10px 20px; background: #f44336; color: white; 
                 text-decoration: none; border-radius: 5px; margin: 10px 0; border: none; cursor: pointer; }
    </style>
</head>
<body>
<div class='container'>
    <h1>UMKM Sample Data Loader</h1>";

try {
    require_once 'pasien/config/koneksi.php';

    // Function to count records in a table
    function getTableCount($table) {
        global $koneksi;
        $result = mysqli_query($koneksi, "SELECT COUNT(*) as count FROM $table");
        $row = mysqli_fetch_assoc($result);
        return $row['count'];
    }

    // Check if tables have existing data
    $tables = ['users', 'customers', 'products', 'transactions', 'transaction_details', 'expenses', 'login_logs'];
    $has_data = false;
    
    echo "<div class='info'><h3>Current Record Counts:</h3>";
    foreach ($tables as $table) {
        $count = getTableCount($table);
        echo "$table: $count records<br>";
        if ($count > 0) $has_data = true;
    }
    echo "</div>";

    if ($has_data && !isset($_POST['confirm_reset'])) {
        echo "<div class='warning'>
            <h3>⚠️ Existing Data Found</h3>
            <p>Some tables already contain data. Loading sample data will delete existing records.</p>
            <form method='post'>
                <input type='submit' name='confirm_reset' value='Clear Existing Data and Load Samples' class='button'>
            </form>
        </div>";
    } else {
        // Clear existing data in reverse order of dependencies
        $clear_queries = [
            "DELETE FROM login_logs",
            "DELETE FROM transaction_details",
            "DELETE FROM transactions",
            "DELETE FROM expenses",
            "DELETE FROM products",
            "DELETE FROM customers",
            "DELETE FROM users"
        ];

        foreach ($clear_queries as $query) {
            mysqli_query($koneksi, $query);
        }

        echo "<div class='success'>Existing data cleared successfully</div>";

        // Load sample data
        echo "<h2>Loading Sample Data...</h2>";
        
        // Insert users
        $users = [
            ['owner', 'password', 'owner@umkm.local', 'John Owner', 'Owner'],
            ['cashier1', 'password', 'cashier1@umkm.local', 'Sarah Cashier', 'Cashier'],
            ['cashier2', 'password', 'cashier2@umkm.local', 'Mike Cashier', 'Cashier'],
            ['accountant', 'password', 'accountant@umkm.local', 'Lisa Accountant', 'Accountant']
        ];

        foreach ($users as $user) {
            $password_hash = password_hash($user[1], PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, password, email, full_name, role) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, "sssss", $user[0], $password_hash, $user[2], $user[3], $user[4]);
            mysqli_stmt_execute($stmt);
        }
        echo "<div class='success'>✓ Users created</div>";

        // Insert customers
        $customers = [
            ['PT. Maju Jaya', '081234567891', 'info@majujaya.com', 'Jl. Raya Utama No. 123'],
            ['CV. Sukses Abadi', '082345678902', 'sukses@abadi.com', 'Jl. Bisnis No. 45'],
            ['Toko Sejahtera', '083456789013', 'toko@sejahtera.com', 'Jl. Pasar Baru No. 67'],
            ['Ibu Maria', '084567890124', 'maria@email.com', 'Jl. Melati No. 89'],
            ['Pak Ahmad', '085678901235', 'ahmad@email.com', 'Jl. Anggrek No. 12']
        ];

        foreach ($customers as $customer) {
            $sql = "INSERT INTO customers (customer_name, phone_number, email, address) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, "ssss", $customer[0], $customer[1], $customer[2], $customer[3]);
            mysqli_stmt_execute($stmt);
        }
        echo "<div class='success'>✓ Customers created</div>";

        // Insert products
        $products = [
            ['Beras Premium', 'Groceries', 15000.00, 100],
            ['Minyak Goreng 1L', 'Groceries', 20000.00, 150],
            ['Gula Pasir 1kg', 'Groceries', 12500.00, 200],
            ['Telur 1kg', 'Groceries', 25000.00, 100],
            ['Tepung Terigu 1kg', 'Groceries', 10000.00, 150]
        ];

        foreach ($products as $product) {
            $sql = "INSERT INTO products (product_name, category, price, stock) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, "ssdi", $product[0], $product[1], $product[2], $product[3]);
            mysqli_stmt_execute($stmt);
        }
        echo "<div class='success'>✓ Products created</div>";

        // Create some transactions
        for ($i = 0; $i < 5; $i++) {
            $customer_id = rand(1, 5);
            $user_id = rand(1, 4);
            $total = rand(5, 20) * 10000;
            $date = date('Y-m-d H:i:s', strtotime("-$i days"));
            
            $sql = "INSERT INTO transactions (customer_id, user_id, transaction_date, transaction_type, payment_method, total_amount) 
                    VALUES (?, ?, ?, 'Income', 'Cash', ?)";
            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, "iiss", $customer_id, $user_id, $date, $total);
            mysqli_stmt_execute($stmt);
            
            $transaction_id = mysqli_insert_id($koneksi);
            
            // Add transaction details
            $product_id = rand(1, 5);
            $quantity = rand(1, 5);
            $price = rand(10000, 50000);
            $subtotal = $quantity * $price;
            
            $sql = "INSERT INTO transaction_details (transaction_id, product_id, quantity, price_at_transaction, subtotal) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, "iiiii", $transaction_id, $product_id, $quantity, $price, $subtotal);
            mysqli_stmt_execute($stmt);
        }
        echo "<div class='success'>✓ Transactions created</div>";

        // Add some expenses
        $expense_categories = ['Rent', 'Utilities', 'Salaries', 'Marketing', 'Other'];
        for ($i = 0; $i < 5; $i++) {
            $user_id = 1; // Owner
            $category = $expense_categories[$i];
            $amount = rand(100000, 1000000);
            $date = date('Y-m-d H:i:s', strtotime("-$i days"));
            
            $sql = "INSERT INTO expenses (user_id, expense_date, expense_category, amount, notes) 
                    VALUES (?, ?, ?, ?, 'Monthly expense')";
            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, "issd", $user_id, $date, $category, $amount);
            mysqli_stmt_execute($stmt);
        }
        echo "<div class='success'>✓ Expenses created</div>";

        // Display final counts
        echo "<div class='info'><h3>Final Record Counts:</h3>";
        foreach ($tables as $table) {
            echo "$table: " . getTableCount($table) . " records<br>";
        }
        echo "</div>";

        echo "<div class='success'>
            <h2>✓ Sample Data Loaded Successfully!</h2>
            <h3>Login Credentials (all use password 'password'):</h3>
            <pre>
Owner:     username: owner
Cashier 1: username: cashier1
Cashier 2: username: cashier2
Accountant: username: accountant</pre>
            <p><a href='/pasien/pasien/' class='button' style='background: #4CAF50;'>Go to UMKM System</a></p>
        </div>";
    }

} catch (Exception $e) {
    echo "<div class='error'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "</div></body></html>";
?>

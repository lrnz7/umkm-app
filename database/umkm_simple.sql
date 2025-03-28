-- Drop existing tables in correct order (due to foreign key constraints)
DROP TABLE IF EXISTS login_logs;
DROP TABLE IF EXISTS transaction_details;
DROP TABLE IF EXISTS transactions;
DROP TABLE IF EXISTS expenses;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS customers;
DROP TABLE IF EXISTS users;

-- Create users table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('Owner', 'Cashier', 'Accountant') NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create login_logs table
CREATE TABLE login_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    username VARCHAR(50) NULL,
    login_time DATETIME NOT NULL,
    status ENUM('Success', 'Failed') NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create customers table
CREATE TABLE customers (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    phone_number VARCHAR(20) UNIQUE,
    email VARCHAR(100) UNIQUE,
    address TEXT,
    registered_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create products table
CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    price DECIMAL(12,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create transactions table
CREATE TABLE transactions (
    transaction_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NULL,
    user_id INT NOT NULL,
    transaction_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    transaction_type ENUM('Income', 'Expense') NOT NULL,
    payment_method ENUM('Cash', 'Bank Transfer', 'E-Wallet', 'QRIS') NOT NULL,
    total_amount DECIMAL(12,2) NOT NULL,
    notes TEXT,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create transaction_details table
CREATE TABLE transaction_details (
    detail_id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price_at_transaction DECIMAL(12,2) NOT NULL,
    subtotal DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (transaction_id) REFERENCES transactions(transaction_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create expenses table
CREATE TABLE expenses (
    expense_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    expense_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    expense_category VARCHAR(50) NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    notes TEXT,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create indexes for better performance
CREATE INDEX idx_customer_name ON customers(customer_name);
CREATE INDEX idx_product_name ON products(product_name);
CREATE INDEX idx_product_category ON products(category);
CREATE INDEX idx_transaction_date ON transactions(transaction_date);
CREATE INDEX idx_transaction_type ON transactions(transaction_type);
CREATE INDEX idx_expense_category ON expenses(expense_category);
CREATE INDEX idx_login_time ON login_logs(login_time);
CREATE INDEX idx_login_status ON login_logs(status);

-- Insert default admin user (password: password)
-- Password hash for 'password'
INSERT INTO users (username, password, email, full_name, role) 
VALUES ('admin', '$2y$10$Eoc/X2MgWEAeJX9mUGKWUOBlwXQ6NHDAJj7EJCwRzwt7tQ4REBpz.', 'admin@umkm.local', 'System Administrator', 'Owner');

-- Insert sample data
INSERT INTO customers (customer_name, phone_number, email, address) 
VALUES ('Walk-in Customer', '000000000', 'walkin@umkm.local', 'Walk-in');

INSERT INTO products (product_name, category, price, stock) 
VALUES 
('Product A', 'Category 1', 10000, 100),
('Product B', 'Category 1', 20000, 50),
('Service X', 'Services', 50000, 999);

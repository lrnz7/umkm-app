-- Sample Users
INSERT INTO users (username, password, email, full_name, role) VALUES
('owner', '$2y$10$Eoc/X2MgWEAeJX9mUGKWUOBlwXQ6NHDAJj7EJCwRzwt7tQ4REBpz.', 'owner@umkm.local', 'John Owner', 'Owner'),
('cashier1', '$2y$10$Eoc/X2MgWEAeJX9mUGKWUOBlwXQ6NHDAJj7EJCwRzwt7tQ4REBpz.', 'cashier1@umkm.local', 'Sarah Cashier', 'Cashier'),
('cashier2', '$2y$10$Eoc/X2MgWEAeJX9mUGKWUOBlwXQ6NHDAJj7EJCwRzwt7tQ4REBpz.', 'cashier2@umkm.local', 'Mike Cashier', 'Cashier'),
('accountant', '$2y$10$Eoc/X2MgWEAeJX9mUGKWUOBlwXQ6NHDAJj7EJCwRzwt7tQ4REBpz.', 'accountant@umkm.local', 'Lisa Accountant', 'Accountant');
-- Note: All passwords are 'password'

-- Sample Customers
INSERT INTO customers (customer_name, phone_number, email, address) VALUES
('PT. Maju Jaya', '081234567890', 'info@majujaya.com', 'Jl. Raya Utama No. 123'),
('CV. Sukses Abadi', '082345678901', 'sukses@abadi.com', 'Jl. Bisnis No. 45'),
('Toko Sejahtera', '083456789012', 'toko@sejahtera.com', 'Jl. Pasar Baru No. 67'),
('Ibu Maria', '084567890123', 'maria@email.com', 'Jl. Melati No. 89'),
('Pak Ahmad', '085678901234', 'ahmad@email.com', 'Jl. Anggrek No. 12');

-- Sample Products
INSERT INTO products (product_name, category, price, stock) VALUES
('Beras Premium', 'Groceries', 15000.00, 100),
('Minyak Goreng 1L', 'Groceries', 20000.00, 150),
('Gula Pasir 1kg', 'Groceries', 12500.00, 200),
('Telur 1kg', 'Groceries', 25000.00, 100),
('Tepung Terigu 1kg', 'Groceries', 10000.00, 150),
('Sabun Mandi', 'Toiletries', 5000.00, 200),
('Shampo Sachet', 'Toiletries', 1000.00, 500),
('Pasta Gigi', 'Toiletries', 15000.00, 100),
('Snack Pack A', 'Snacks', 10000.00, 50),
('Snack Pack B', 'Snacks', 12000.00, 50);

-- Sample Transactions and Details (Last 7 days)
INSERT INTO transactions (customer_id, user_id, transaction_date, transaction_type, payment_method, total_amount, notes) VALUES
(1, 2, DATE_SUB(NOW(), INTERVAL 6 DAY), 'Income', 'Cash', 275000.00, 'Bulk purchase'),
(3, 2, DATE_SUB(NOW(), INTERVAL 5 DAY), 'Income', 'Bank Transfer', 150000.00, 'Regular order'),
(4, 3, DATE_SUB(NOW(), INTERVAL 4 DAY), 'Income', 'Cash', 50000.00, 'Small purchase'),
(2, 2, DATE_SUB(NOW(), INTERVAL 3 DAY), 'Income', 'QRIS', 325000.00, 'Monthly supply'),
(5, 3, DATE_SUB(NOW(), INTERVAL 2 DAY), 'Income', 'E-Wallet', 75000.00, 'Weekly groceries'),
(1, 2, DATE_SUB(NOW(), INTERVAL 1 DAY), 'Income', 'Bank Transfer', 225000.00, 'Regular order'),
(3, 3, CURRENT_DATE(), 'Income', 'Cash', 100000.00, 'Basic necessities');

-- Transaction Details
INSERT INTO transaction_details (transaction_id, product_id, quantity, price_at_transaction, subtotal) VALUES
-- Transaction 1 (275000)
(1, 1, 10, 15000.00, 150000.00),
(1, 2, 5, 20000.00, 100000.00),
(1, 3, 2, 12500.00, 25000.00),
-- Transaction 2 (150000)
(2, 4, 4, 25000.00, 100000.00),
(2, 5, 5, 10000.00, 50000.00),
-- Transaction 3 (50000)
(3, 6, 10, 5000.00, 50000.00),
-- Transaction 4 (325000)
(4, 1, 15, 15000.00, 225000.00),
(4, 2, 5, 20000.00, 100000.00),
-- Transaction 5 (75000)
(5, 8, 5, 15000.00, 75000.00),
-- Transaction 6 (225000)
(6, 4, 5, 25000.00, 125000.00),
(6, 5, 10, 10000.00, 100000.00),
-- Transaction 7 (100000)
(7, 9, 5, 10000.00, 50000.00),
(7, 10, 5, 12000.00, 50000.00);

-- Sample Expenses
INSERT INTO expenses (user_id, expense_date, expense_category, amount, notes) VALUES
(1, DATE_SUB(NOW(), INTERVAL 6 DAY), 'Rent', 2000000.00, 'Monthly store rent'),
(1, DATE_SUB(NOW(), INTERVAL 5 DAY), 'Utilities', 500000.00, 'Electricity and water'),
(1, DATE_SUB(NOW(), INTERVAL 4 DAY), 'Salaries', 3000000.00, 'Staff salaries'),
(1, DATE_SUB(NOW(), INTERVAL 3 DAY), 'Raw Materials', 1500000.00, 'Stock replenishment'),
(1, DATE_SUB(NOW(), INTERVAL 2 DAY), 'Marketing', 300000.00, 'Local advertising'),
(1, DATE_SUB(NOW(), INTERVAL 1 DAY), 'Utilities', 100000.00, 'Internet bill'),
(1, CURRENT_DATE(), 'Other', 250000.00, 'Miscellaneous supplies');

-- Sample Login Logs
INSERT INTO login_logs (user_id, username, login_time, status) VALUES
(1, 'owner', DATE_SUB(NOW(), INTERVAL 2 HOUR), 'Success'),
(2, 'cashier1', DATE_SUB(NOW(), INTERVAL 1 HOUR), 'Success'),
(NULL, 'unknown', DATE_SUB(NOW(), INTERVAL 30 MINUTE), 'Failed'),
(3, 'cashier2', DATE_SUB(NOW(), INTERVAL 15 MINUTE), 'Success'),
(4, 'accountant', NOW(), 'Success');

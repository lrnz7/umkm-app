-- UMKM System Backup
-- Generated: 2025-03-26 18:38:57



CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(100) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `registered_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`customer_id`),
  UNIQUE KEY `phone_number` (`phone_number`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_customer_name` (`customer_name`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO customers VALUES ('23', 'PT. Maju Jaya', '081234567891', 'info@majujaya.com', 'Jl. Raya Utama No. 123', '2025-03-27 00:09:02');
INSERT INTO customers VALUES ('24', 'CV. Sukses Abadi', '082345678902', 'sukses@abadi.com', 'Jl. Bisnis No. 45', '2025-03-27 00:09:02');
INSERT INTO customers VALUES ('25', 'Toko Sejahtera', '083456789013', 'toko@sejahtera.com', 'Jl. Pasar Baru No. 67', '2025-03-27 00:09:02');
INSERT INTO customers VALUES ('26', 'Ibu Maria', '084567890124', 'maria@email.com', 'Jl. Melati No. 89', '2025-03-27 00:09:02');
INSERT INTO customers VALUES ('27', 'Pak Ahmad', '085678901235', 'ahmad@email.com', 'Jl. Anggrek No. 12', '2025-03-27 00:09:02');


CREATE TABLE `expenses` (
  `expense_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `expense_date` datetime DEFAULT current_timestamp(),
  `expense_category` varchar(50) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `notes` text DEFAULT NULL,
  PRIMARY KEY (`expense_id`),
  KEY `user_id` (`user_id`),
  KEY `idx_expense_category` (`expense_category`),
  CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `login_logs` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `login_time` datetime NOT NULL,
  `status` enum('Success','Failed') NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `user_id` (`user_id`),
  KEY `idx_login_time` (`login_time`),
  KEY `idx_login_status` (`status`),
  CONSTRAINT `login_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `products` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`product_id`),
  KEY `idx_product_name` (`product_name`),
  KEY `idx_product_category` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO products VALUES ('20', 'Beras Premium', 'Groceries', '15000.00', '100', '2025-03-27 00:09:02');
INSERT INTO products VALUES ('21', 'Minyak Goreng 1L', 'Groceries', '20000.00', '150', '2025-03-27 00:09:02');
INSERT INTO products VALUES ('22', 'Gula Pasir 1kg', 'Groceries', '12500.00', '200', '2025-03-27 00:09:02');
INSERT INTO products VALUES ('23', 'Telur 1kg', 'Groceries', '25000.00', '100', '2025-03-27 00:09:02');
INSERT INTO products VALUES ('24', 'Tepung Terigu 1kg', 'Groceries', '10000.00', '150', '2025-03-27 00:09:02');


CREATE TABLE `transaction_details` (
  `detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_at_transaction` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  PRIMARY KEY (`detail_id`),
  KEY `transaction_id` (`transaction_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `transaction_details_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`transaction_id`),
  CONSTRAINT `transaction_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `transaction_date` datetime DEFAULT current_timestamp(),
  `transaction_type` enum('Income','Expense') NOT NULL,
  `payment_method` enum('Cash','Bank Transfer','E-Wallet','QRIS') NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `notes` text DEFAULT NULL,
  PRIMARY KEY (`transaction_id`),
  KEY `customer_id` (`customer_id`),
  KEY `user_id` (`user_id`),
  KEY `idx_transaction_date` (`transaction_date`),
  KEY `idx_transaction_type` (`transaction_type`),
  CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE SET NULL,
  CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` enum('Owner','Cashier','Accountant') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO users VALUES ('18', 'owner', '$2y$10$tsekFvklCxeseAy70cVJiOex96XVqDlYWYyGZEcXSOeXpl/zh4SnC', 'owner@umkm.local', 'John Owner', 'Owner', '2025-03-27 00:09:01');
INSERT INTO users VALUES ('19', 'cashier1', '$2y$10$YdSg9nOXugerH9/aRMIEPeR/Cg/luvNrEvNExPrJDigMPY94SdtnK', 'cashier1@umkm.local', 'Sarah Cashier', 'Cashier', '2025-03-27 00:09:01');
INSERT INTO users VALUES ('20', 'cashier2', '$2y$10$YXfVoommVBzEs2Tu3faMIe72up.mONnCyvqW9xK/Gu7OZyJt5F3vi', 'cashier2@umkm.local', 'Mike Cashier', 'Cashier', '2025-03-27 00:09:01');
INSERT INTO users VALUES ('21', 'accountant', '$2y$10$ZMGAvzD3mZXPMcI/IwIDXu8JOrDTxw9smbSeZNc/GrV1K5/i3kSNG', 'accountant@umkm.local', 'Lisa Accountant', 'Accountant', '2025-03-27 00:09:01');

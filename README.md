# UMKM Financial Management System

## Database Migration Notice
The system has been updated from a patient management system to an UMKM (Micro, Small, and Medium Enterprises) financial management system.

## New Database Structure

### Tables
1. **customers**
   - customer_id (Primary Key)
   - customer_name
   - address
   - phone_number
   - email
   - join_date

2. **products**
   - product_id (Primary Key)
   - product_name
   - category
   - price
   - stock
   - description

3. **transactions**
   - transaction_id (Primary Key)
   - customer_id (Foreign Key)
   - product_id (Foreign Key)
   - quantity
   - total_price
   - transaction_date
   - payment_method

4. **expenses**
   - expense_id (Primary Key)
   - category
   - amount
   - expense_date
   - description

5. **users**
   - username (Primary Key)
   - password
   - email
   - full_name
   - role

## Setup Instructions

1. **Database Setup**
   - Visit http://localhost/pasien/setup_db.php
   - This will create the database and all required tables
   - Default admin account will be created:
     - Username: admin
     - Password: password

2. **System Requirements**
   - XAMPP with PHP 7.4+ and MySQL 5.7+
   - Web browser (Chrome/Firefox recommended)

3. **Installation Steps**
   ```bash
   1. Start XAMPP (Apache and MySQL)
   2. Visit http://localhost/pasien/setup_db.php
   3. Follow the setup wizard
   4. Log in with default admin credentials
   5. Change admin password immediately
   ```

## Backup & Restore
- Previous system backup is available in: `/backup_2025-03-26_1913/`
- To restore previous system: Visit http://localhost/pasien/restore.php

## Database Features
- Optimized indexes for better performance
- Foreign key constraints for data integrity
- Transaction support
- Audit trails (created_at, updated_at timestamps)

## Security Features
- Password hashing
- Prepared statements for SQL injection prevention
- Role-based access control
- Input validation and sanitization

## Helper Functions
The system includes several helper functions in koneksi.php:
```php
executeQuery($sql, $params = [])  // Safe query execution
getRow($sql, $params = [])       // Get single row
getRows($sql, $params = [])      // Get multiple rows
insertData($table, $data)        // Insert new record
updateData($table, $data, $where) // Update existing record
```

## Troubleshooting
1. **Database Connection Issues**
   - Verify XAMPP is running
   - Check database credentials in config/koneksi.php
   - Visit setup_db.php to verify database structure

2. **Access Issues**
   - Default URL: http://localhost/pasien/pasien/
   - Enable debug mode by adding ?debug=1 to URL

## Support
For any issues:
1. Check error logs in XAMPP/logs
2. Use test_connection.php for database connectivity
3. Enable debug mode for detailed error information

## Security Notes
1. Change default admin password immediately
2. Regularly backup your database
3. Keep PHP and MySQL updated
4. Monitor error logs regularly

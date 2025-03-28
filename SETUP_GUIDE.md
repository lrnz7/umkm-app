# UMKM System Setup Guide

## Step 1: Database Setup

1. First, visit:
   ```
   http://localhost/pasien/setup_db.php
   ```
   This will:
   - Create the new UMKM database
   - Set up all required tables
   - Create indexes for optimization
   - Create default admin account

2. Verify the setup by visiting:
   ```
   http://localhost/pasien/test_umkm_db.php
   ```
   This will:
   - Test database connections
   - Insert test data
   - Verify relationships
   - Show table structures

## Step 2: Database Structure

The new system includes these tables:

### customers
- Stores customer information
- Primary key: customer_id
- Includes: name, address, contact details

### products
- Manages product inventory
- Primary key: product_id
- Tracks: name, price, stock, category

### transactions
- Records sales transactions
- Links customers and products
- Includes payment information

### expenses
- Tracks business expenses
- Categorizes spending
- Maintains expense history

### users
- Manages system access
- Supports multiple roles
- Secures user data

## Step 3: Default Access

Admin Account:
```
Username: admin
Password: password
```
**Important**: Change this password immediately after first login!

## Step 4: Testing

1. Database Connection:
   ```
   http://localhost/pasien/test_connection.php
   ```

2. UMKM Features:
   ```
   http://localhost/pasien/test_umkm_db.php
   ```

## Step 5: Data Migration

If you have existing data:
1. Back up your current database
2. Use the provided restore point if needed:
   ```
   http://localhost/pasien/restore.php
   ```

## Step 6: Security Measures

1. Update config/koneksi.php with your database credentials
2. Change default admin password
3. Set proper file permissions
4. Regular backups

## Step 7: Optimization

The database includes:
- Indexed fields for faster searches
- Optimized data types
- Foreign key constraints
- Audit trails

## Troubleshooting

### Common Issues:

1. Database Connection Errors
   - Check XAMPP is running
   - Verify database credentials
   - Confirm database exists

2. Access Denied
   - Check user permissions
   - Verify login credentials
   - Check file permissions

3. Missing Tables
   - Run setup_db.php again
   - Check for SQL errors
   - Verify database name

### Debug Mode

Add ?debug=1 to any URL for detailed error information:
```
http://localhost/pasien/pasien/index.php?debug=1
```

## Support Files

1. setup_db.php
   - Creates database structure
   - Sets up initial data

2. test_umkm_db.php
   - Verifies database functionality
   - Tests relationships
   - Shows table structures

3. koneksi.php
   - Manages database connections
   - Provides helper functions
   - Handles errors

## Next Steps

1. Start adding real data:
   - Products
   - Customers
   - Initial expenses

2. Configure user accounts:
   - Add staff accounts
   - Set proper roles
   - Update permissions

3. Regular maintenance:
   - Daily backups
   - Monitor logs
   - Update passwords

## Additional Resources

- README.md - Full system documentation
- Database schema in database/umkm_db.sql
- Test scripts for verification
- Backup and restore utilities

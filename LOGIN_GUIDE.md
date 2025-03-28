# UMKM System Login Guide

## Step-by-Step Setup

1. Reset Database:
   ```
   http://localhost/pasien/setup_umkm.php
   ```
   - Click "Reset Database" button
   - Wait for confirmation that all tables are created

2. Access Main System:
   ```
   http://localhost/pasien/pasien/
   ```

## Login Credentials

Default Admin Account:
```
Username: admin
Password: password
```

⚠️ IMPORTANT: Change the password after first login!

## Troubleshooting

If login fails:

1. Verify database setup:
   ```
   http://localhost/pasien/verify_setup.php
   ```

2. Test login system:
   ```
   http://localhost/pasien/test_login.php
   ```

3. Common Issues:
   - Make sure XAMPP is running
   - Database might need reset (use setup_umkm.php)
   - Clear browser cache and cookies

## User Roles

The system supports three roles:
1. Owner
   - Full access to all features
   - Can manage users
   - Can view all reports

2. Cashier
   - Can process transactions
   - Can manage products
   - Can view basic reports

3. Accountant
   - Can view all reports
   - Can manage expenses
   - Can't process transactions

## Security Notes

1. Change default password immediately
2. Use strong passwords
3. Don't share login credentials
4. Log out when not in use

## Need Help?

If you can't log in:
1. Try the default credentials exactly as shown
2. Reset the database if needed
3. Check the error message for specific issues
4. Verify all services are running

## Quick Links

- Main System: http://localhost/pasien/pasien/
- Setup Page: http://localhost/pasien/setup_umkm.php
- Verify Setup: http://localhost/pasien/verify_setup.php
- Test Login: http://localhost/pasien/test_login.php

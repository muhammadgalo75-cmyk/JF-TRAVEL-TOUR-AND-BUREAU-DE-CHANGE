# Backend API Debugging Report

## Issues Found

### 1. **MySQL Database Connection Failed** ⚠️ CRITICAL
The backend cannot save data to the database because **MySQL is not running or not accessible**.

**Error in logs:**
```
SQLSTATE[HY000] [2002] No connection could be made because the target machine actively refused it
```

**Root Cause:** The MySQL server is not running on `127.0.0.1:3306`

**Current Configuration (in `.env`):**
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jf-api
DB_USERNAME=root
DB_PASSWORD=
```

### 2. **CORS Configuration** ✅ FIXED
Added frontend port 5174 to allowed origins since the app runs on that port.

**Updated allowed origins:**
```php
'allowed_origins' => [
    'http://localhost:5173',
    'http://localhost:5174',
    'http://localhost:3000',
    'http://127.0.0.1:5173',
    'http://127.0.0.1:5174',
    'http://127.0.0.1:3000',
],
```

### 3. **API Endpoint Logging** ✅ ENHANCED
Added detailed logging to the `firebaseSignup` endpoint in `AuthController.php` to track:
- Incoming request body
- Validation pass/fail
- User creation/update success
- Any exceptions with stack traces

This will help diagnose issues via logs in `storage/logs/laravel.log`.

## How to Fix the Database Issue

### Option 1: Start MySQL Server (Recommended)
If you have MySQL installed, start the MySQL service:

**Windows (Command Prompt as Administrator):**
```cmd
net start MySQL80
```

Or if you're using a MySQL manager like XAMPP, HeidiSQL, or phpMyAdmin:
- Open XAMPP Control Panel and click "Start" next to MySQL
- Or use your MySQL installation's service manager

**Verify MySQL is running:**
```cmd
mysql -h 127.0.0.1 -u root -p
```
(Press Enter if no password, or type your MySQL root password)

### Option 2: Check Database Connection Credentials
If MySQL is running but still won't connect, verify:
1. MySQL is listening on `127.0.0.1:3306`
2. The `root` user password in `.env` matches your MySQL installation
3. The database `jf-api` exists, or create it:
```sql
CREATE DATABASE `jf-api` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Option 3: Run Migrations After Connection is Fixed
Once MySQL is running and connected, run migrations:
```bash
cd C:\Users\USER\Desktop\JF-TRAVEL-TOUR-AND-BUERU-DE-CHANGE\backend\jf-api
php artisan migrate
```

This will create all necessary tables including:
- `users` (stores user profiles from Firebase)
- `password_reset_tokens`
- `sessions`
- All tour-related tables

## Verification Steps

### Step 1: Check MySQL Status
```cmd
mysql -h 127.0.0.1 -u root -e "SELECT 1"
```
Should return: `1` (if successful)

### Step 2: Check Laravel Database Connection
```cmd
cd C:\Users\USER\Desktop\JF-TRAVEL-TOUR-AND-BUERU-DE-CHANGE\backend\jf-api
php artisan tinker
>>> \DB::connection()->getPdo()
```
Should return a PDO object (not an error)

### Step 3: Check API Endpoint Logs
After making a registration request:
```bash
tail -f storage/logs/laravel.log
```
Look for lines starting with `firebaseSignup:` to see detailed request/response logs.

## What Happens After Database is Fixed

1. **User Registration Flow:**
   - Firebase: User creates account (email/password or OAuth)
   - Frontend calls: `POST /api/auth/firebase-signup` with `name`, `email`, `firebaseUid`
   - Backend logs: `firebaseSignup: Saving user...`
   - Database: User record created in `users` table
   - Backend logs: `firebaseSignup: User created/updated`
   - Frontend: Shows success toast and redirects to dashboard

2. **Role Checking:**
   - After login, frontend calls: `POST /api/auth/check-admin` with user `email`
   - Backend queries: `users` table for matching email
   - Returns: `{ "isAdmin": true/false }`
   - Frontend: Shows "Admin Panel" link if admin

## Current API Status

**Backend Server:** ✅ Running on `http://localhost:8000`
**Frontend Server:** ✅ Running on `http://localhost:5174`
**Database Connection:** ❌ FAILED - MySQL not running
**CORS Configuration:** ✅ Fixed to include port 5174
**API Logging:** ✅ Enhanced with detailed debugging

## Next Steps

1. **Start MySQL Server** - This is the critical blocker
2. **Verify Connection** - Test with `mysql` command or Laravel tinker
3. **Run Migrations** - Create database tables
4. **Test Registration** - Create a new user and check console logs
5. **Monitor Logs** - Watch `storage/logs/laravel.log` for debugging

---

**Generated:** December 28, 2025
**Framework:** Laravel 11 + React + Firebase
**Database:** MySQL 5.7+ (not currently running)

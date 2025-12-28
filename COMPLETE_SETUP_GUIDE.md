# JF Travel & Bureau de Change - Complete Setup Guide

## 📋 Table of Contents
1. [Backend Database Setup](#backend-database-setup)
2. [Manual Database Setup](#manual-database-setup)
3. [Laravel Setup](#laravel-setup)
4. [Frontend Setup](#frontend-setup)
5. [Running the Application](#running-the-application)

---

## Backend Database Setup

### Option 1: Automated Laravel Migration (Recommended)

#### Prerequisites
- PHP 8.1+
- MySQL/MariaDB running
- Composer installed

#### Steps

1. **Navigate to backend directory**
   ```bash
   cd backend
   ```

2. **Install Composer dependencies**
   ```bash
   composer install
   ```

3. **Generate Laravel app key**
   ```bash
   php artisan key:generate
   ```

4. **Ensure database exists**
   ```bash
   mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS jf;"
   ```

5. **Run migrations**
   ```bash
   php artisan migrate
   ```

6. **Verify migrations**
   ```bash
   php artisan migrate:status
   ```

---

### Option 2: Manual Database Setup (SQL)

If Laravel is not available or you prefer to set up manually:

1. **Open MySQL Command Line**
   ```bash
   mysql -u root -p
   ```

2. **Run the setup script**
   ```sql
   source database/setup.sql;
   ```
   
   Or copy and run the SQL commands from `database/setup.sql` manually

3. **Verify setup**
   ```sql
   USE jf;
   SHOW TABLES;
   SELECT * FROM migrations;
   ```

---

## Manual Database Setup

### Using MySQL GUI (phpMyAdmin, MySQL Workbench, etc.)

1. **Create Database**
   - Database name: `jf`
   - Charset: `utf8mb4`
   - Collation: `utf8mb4_unicode_ci`

2. **Run setup.sql file**
   - Open `backend/database/setup.sql` in your MySQL client
   - Execute the file
   - This will create all tables with proper relationships

3. **Verify Tables Created**
   ```sql
   SHOW TABLES;
   ```

Expected tables:
- `migrations`
- `users`
- `tours`
- `tour_bookings`
- `currency_exchanges`

---

## Laravel Setup

### Running the Backend Server

```bash
cd backend
php artisan serve
```

Server runs at: `http://localhost:8000`

### Database Configuration

Edit `backend/.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jf
DB_USERNAME=root
DB_PASSWORD=
```

Adjust `DB_PASSWORD` if your MySQL has a password.

### Useful Laravel Commands

```bash
# Show all routes
php artisan route:list

# Interactive shell
php artisan tinker

# Clear cache
php artisan config:cache
php artisan cache:clear

# Rollback migrations
php artisan migrate:rollback

# Reset database
php artisan migrate:reset
php artisan migrate
```

---

## Frontend Setup

### Prerequisites
- Node.js 16+
- npm or yarn

### Steps

```bash
cd frontend

# Install dependencies
npm install

# Run development server
npm run dev

# Build for production
npm run build
```

Frontend runs at: `http://localhost:5173` (Vite default)

---

## Running the Application

### Terminal 1: Backend API
```bash
cd backend
php artisan serve
# Runs on http://localhost:8000
```

### Terminal 2: Frontend
```bash
cd frontend
npm run dev
# Runs on http://localhost:5173
```

---

## Database Schema Overview

### users
- Stores user accounts
- Fields: id, name, email, password, phone, address, country, role

### tours
- Stores tour packages
- Fields: id, title, description, destination, price, duration_days, dates, images, activities

### tour_bookings
- Stores tour reservations
- Fields: id, user_id, tour_id, number_of_people, total_price, status

### currency_exchanges
- Stores exchange transactions
- Fields: id, user_id, from_currency, to_currency, amounts, rate, status

---

## Sample Test Data

### Admin User
- Email: `admin@jftravel.com`
- Password: `password` (default Laravel hash)
- Role: admin

### Regular User
- Email: `john@example.com`
- Password: `password` (default Laravel hash)
- Role: user

---

## Troubleshooting

### Database Connection Error
- Ensure MySQL is running
- Verify credentials in `.env`
- Check if database `jf` exists
- Verify user has access permissions

### Missing Tables
- Run migrations: `php artisan migrate`
- Or run SQL setup: Load `database/setup.sql`

### Port Already in Use
```bash
# Frontend (change port)
npm run dev -- --port 3000

# Backend (change port)
php artisan serve --port 8001
```

### Permission Denied
```bash
# Make storage and bootstrap writable
chmod -R 775 storage bootstrap/cache
```

---

## Next Steps

1. ✅ Database created with tables
2. ⬜ Create API Controllers
3. ⬜ Create Routes (api.php)
4. ⬜ Create Models
5. ⬜ Add authentication
6. ⬜ Connect Frontend to Backend APIs

---

## File Locations

```
JF-TRAVEL-TOUR-AND-BUERU-DE-CHANGE/
├── backend/
│   ├── .env (Configuration)
│   ├── database/
│   │   ├── migrations/ (Table definitions)
│   │   └── setup.sql (Manual setup)
│   ├── app/
│   │   ├── Models/
│   │   └── Http/Controllers/
│   └── routes/
│       └── api.php
├── frontend/
│   ├── src/
│   │   ├── app/ (React components)
│   │   └── utils/ (Utilities)
│   └── package.json
└── README.md
```

---

## Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [MySQL Manual](https://dev.mysql.com/doc/)
- [React Documentation](https://react.dev)
- [Tailwind CSS](https://tailwindcss.com)

---

**Last Updated:** December 27, 2025
**Status:** ✅ Database Setup Complete

# JF Travel Tour & Bureau de Change - Full Stack Project

A complete full-stack web application built with React (Frontend) and Laravel (Backend) for travel tours and currency exchange services.

## Project Structure

```
JF TRAVEL TOUR & BUERU DE CHANGE/
├── frontend/          # React application
│   ├── src/
│   │   ├── components/   # Reusable React components from Figma
│   │   ├── pages/        # Page components
│   │   ├── api/          # API integration with axios
│   │   ├── App.js
│   │   └── index.js
│   ├── public/
│   ├── package.json
│   └── .env.example
│
└── backend/           # Laravel API
    ├── app/
    │   ├── Models/       # User, Tour, TourBooking, CurrencyExchange
    │   └── Http/Controllers/
    ├── database/
    │   └── migrations/   # Database schema
    ├── routes/
    │   └── api.php       # API endpoints
    ├── composer.json
    └── .env.example
```

## Quick Start Guide

### Frontend Setup (React)

1. Navigate to the frontend directory:
```bash
cd frontend
```

2. Install dependencies:
```bash
npm install
```

3. Create `.env` file from `.env.example`:
```bash
cp .env.example .env
```

4. Start the development server:
```bash
npm start
```

The React app will run on `http://localhost:3000`

### Backend Setup (Laravel)

1. Navigate to the backend directory:
```bash
cd backend
```

2. Install dependencies:
```bash
composer install
```

3. Create `.env` file from `.env.example`:
```bash
cp .env.example .env
```

4. Generate application key:
```bash
php artisan key:generate
```

5. Create database and run migrations:
```bash
php artisan migrate
```

6. Start the development server:
```bash
php artisan serve
```

The API will run on `http://localhost:8000`

## Features

### Frontend (React)
- User registration and login
- Browse available tours
- Book tours
- Currency exchange interface
- User dashboard and profile
- Responsive design
- Modern UI from Figma components

### Backend (Laravel)
- User authentication with Laravel Sanctum (tokens)
- Tour management (CRUD operations)
- Tour booking system
- Currency exchange tracking
- RESTful API
- Database migrations and models
- CORS support for frontend

## API Documentation

### Authentication Endpoints
- **POST** `/api/auth/register` - Register a new user
- **POST** `/api/auth/login` - Login user
- **POST** `/api/auth/logout` - Logout user (requires auth)
- **GET** `/api/auth/me` - Get current user (requires auth)
- **POST** `/api/auth/refresh` - Refresh token (requires auth)

### Tour Endpoints
- **GET** `/api/tours` - Get all tours
- **GET** `/api/tours/{id}` - Get tour details
- **POST** `/api/tours` - Create tour (admin only)
- **PUT** `/api/tours/{id}` - Update tour (admin only)
- **DELETE** `/api/tours/{id}` - Delete tour (admin only)

### More Features to Implement
- Tour booking endpoints
- Currency exchange endpoints
- User profile management
- Payment integration

## Technologies Used

### Frontend
- React 18
- React Router
- Axios (for API calls)
- CSS3

### Backend
- Laravel 10
- Laravel Sanctum (for API tokens)
- MySQL/SQLite Database
- Composer (dependency management)

## Environment Variables

### Frontend (.env)
```
REACT_APP_API_URL=http://localhost:8000/api
```

### Backend (.env)
```
APP_KEY=your-generated-key
DB_CONNECTION=mysql
DB_DATABASE=travel_tour_db
DB_USERNAME=root
DB_PASSWORD=
FRONTEND_URL=http://localhost:3000
```

## Database Schema

### Users Table
- id, name, email, password, phone, address, country, role, timestamps

### Tours Table
- id, title, description, destination, price, duration_days, start_date, end_date, image_url, included_activities, accommodation, transport, status, timestamps

### Tour Bookings Table
- id, user_id, tour_id, number_of_people, total_price, status, booking_date, notes, timestamps

### Currency Exchanges Table
- id, user_id, from_currency, to_currency, from_amount, to_amount, exchange_rate, status, exchange_date, timestamps

## Next Steps

1. **Add Figma Components** - Import and integrate your React components from Figma into the `/frontend/src/components` folder
2. **Implement Pages** - Create page components in `/frontend/src/pages` for different routes
3. **Complete API Controllers** - Implement remaining controllers for bookings and currency exchange
4. **Add Validation** - Implement form validation on both frontend and backend
5. **Set Up Database** - Configure MySQL database and run migrations
6. **Add Authentication UI** - Create login/register pages with your Figma components
7. **Style with Figma** - Apply Figma design system styles to React components
8. **Testing** - Add unit and integration tests

## Contributing

- Keep frontend and backend separate in their directories
- Use RESTful conventions for API endpoints
- Follow Laravel and React coding standards
- Comment complex logic

## Support

For more details on Laravel, visit: https://laravel.com/docs
For more details on React, visit: https://react.dev

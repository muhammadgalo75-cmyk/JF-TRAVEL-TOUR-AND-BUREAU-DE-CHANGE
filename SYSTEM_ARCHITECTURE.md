# 🏗️ Complete System Architecture

## Frontend → Backend Data Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                      FRONTEND (React)                            │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  src/app/pages/                                                 │
│  ├─ ToursPage.tsx          ──┐                                 │
│  ├─ TourDetailsPage.tsx    ──┼─→ GET /api/tours             │
│  ├─ BookingPage.tsx        ──┼─→ POST /api/bookings         │
│  ├─ UserDashboard.tsx      ──┼─→ GET /api/user/bookings     │
│  ├─ CurrencyExchangePage   ──┼─→ POST /api/currency-exchange│
│  └─ DepositPage.tsx        ──┘─→ POST /api/deposits         │
│                                                                  │
│  src/utils/currencyConverter.ts                                │
│  └─ convertCurrency() ─────────→ Use currency_rates data      │
│                                                                  │
└──────────────────────┬──────────────────────────────────────────┘
                       │ HTTP / JSON
                       ▼
┌─────────────────────────────────────────────────────────────────┐
│                   BACKEND (Laravel)                              │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  routes/api.php                                                 │
│  ├─ GET    /tours              → TourController@index          │
│  ├─ GET    /tours/{id}         → TourController@show           │
│  ├─ POST   /bookings           → BookingController@store       │
│  ├─ GET    /user/bookings      → BookingController@userList    │
│  ├─ POST   /currency-exchange  → CurrencyController@exchange   │
│  ├─ POST   /deposits           → DepositController@store       │
│  ├─ GET    /currency-rates     → CurrencyController@rates      │
│  └─ POST   /auth/register      → AuthController@register       │
│                                                                  │
│  app/Models/                                                    │
│  ├─ User.php         ─────────┐                               │
│  ├─ Tour.php         ─────────┼─→ Eloquent ORM                │
│  ├─ TourBooking.php  ─────────┤   (Database models)           │
│  ├─ CurrencyExchange ─────────┤                               │
│  ├─ Deposit.php      ─────────┤                               │
│  ├─ Destination.php  ─────────┤                               │
│  ├─ CurrencyRate.php ─────────┤                               │
│  └─ Testimonial.php  ─────────┘                               │
│                                                                  │
│  app/Http/Controllers/                                          │
│  ├─ TourController.php         (Business logic)               │
│  ├─ BookingController.php                                      │
│  ├─ CurrencyController.php                                     │
│  ├─ DepositController.php                                      │
│  └─ UserController.php                                         │
│                                                                  │
└──────────────────────┬──────────────────────────────────────────┘
                       │ SQL Queries
                       ▼
┌─────────────────────────────────────────────────────────────────┐
│                     DATABASE (MySQL)                             │
├─────────────────────────────────────────────────────────────────┤
│  Database: jf                                                    │
│                                                                  │
│  Tables (8 total):                                              │
│  ├─ users                    (User accounts)                   │
│  ├─ tours                    (Tour packages)                   │
│  ├─ tour_bookings            (Reservations)                    │
│  ├─ currency_exchanges       (Exchange transactions)           │
│  ├─ deposits                 (Wallet deposits)                 │
│  ├─ destinations             (Popular destinations)            │
│  ├─ currency_rates           (Exchange rates)                  │
│  └─ testimonials             (User reviews)                    │
│                                                                  │
│  Plus system tables:                                            │
│  ├─ migrations               (Laravel tracking)                │
│  └─ password_reset_tokens    (Auth)                            │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

---

## 📊 Request/Response Examples

### Example 1: Get All Tours
```
REQUEST:
  GET /api/tours
  Headers: Authorization: Bearer {token}

BACKEND PROCESSING:
  1. TourController@index
  2. Tour::all() — Query tours table
  3. Apply filtering (country, category, price)
  4. Return JSON response

RESPONSE:
  200 OK
  [
    {
      "id": 1,
      "name": "Tropical Paradise",
      "destination": "Bali",
      "country": "Indonesia",
      "price": 1299.00,
      "duration": "5 Days / 4 Nights",
      "rating": 4.8,
      "category": "beach",
      "itinerary": [...],
      "included": [...],
      "excluded": [...]
    },
    ...
  ]

FRONTEND RECEIVES:
  Display tours list with currency conversion
  Convert price: 1299 USD → 590,000 NGN (if user selected NGN)
```

---

### Example 2: Create a Booking
```
REQUEST:
  POST /api/bookings
  Headers: Authorization: Bearer {token}
  Body: {
    "tour_id": 1,
    "travel_date": "2024-02-01",
    "number_of_travelers": 2
  }

BACKEND PROCESSING:
  1. BookingController@store
  2. Validate input
  3. Get tour: Tour::find(1) — price = 1299 USD
  4. Calculate total: 1299 × 2 = 2598 USD
  5. Create booking record in tour_bookings table
  6. User wallet: wallet_balance -= 2598
  7. Return created booking

RESPONSE:
  201 CREATED
  {
    "id": "BK001",
    "user_id": 1,
    "tour_id": 1,
    "booking_date": "2024-01-15",
    "travel_date": "2024-02-01",
    "number_of_travelers": 2,
    "total_price": 2598.00,
    "status": "confirmed",
    "created_at": "2024-01-15T10:30:00Z"
  }

FRONTEND DISPLAYS:
  Confirmation: "Booking successful!"
  Deducted: 2598 USD from wallet
  New balance: 2500 - 2598 = -98 (or display error if insufficient)
```

---

### Example 3: Currency Exchange
```
REQUEST:
  POST /api/currency-exchange
  Headers: Authorization: Bearer {token}
  Body: {
    "from_currency": "USD",
    "to_currency": "NGN",
    "amount": 100
  }

BACKEND PROCESSING:
  1. CurrencyController@exchange
  2. Get rates: CurrencyRate::where('code', 'USD|NGN')
  3. Get exchange rate: 456.5000 (1 USD = 456.50 NGN)
  4. Calculate: 100 × 456.50 = 45,650 NGN
  5. Deduct 100 USD from wallet
  6. Add 45,650 NGN to wallet
  7. Record transaction: currency_exchanges table
  8. Return exchange confirmation

RESPONSE:
  200 OK
  {
    "id": "EX001",
    "user_id": 1,
    "from_currency": "USD",
    "to_currency": "NGN",
    "from_amount": 100.00,
    "to_amount": 45650.00,
    "exchange_rate": 456.5000,
    "status": "success",
    "exchange_date": "2024-01-15T11:00:00Z"
  }

FRONTEND DISPLAYS:
  "100 USD = 45,650 NGN"
  Updated wallet
```

---

## 🔄 User Journey Maps

### Journey 1: Browse & Book Tour
```
User Opens App
    ↓
ToursPage
    ↓
Fetch GET /api/tours
    ↓
Display tours (with selected currency conversion)
    ↓
User clicks tour
    ↓
TourDetailsPage
    ↓
Show detailed info + price in selected currency
    ↓
Click "Book Now"
    ↓
BookingPage
    ↓
Enter travel date + number of travelers
    ↓
POST /api/bookings
    ↓
Booking confirmed
    ↓
UserDashboard shows new booking
```

---

### Journey 2: Exchange Currency
```
User visits CurrencyExchangePage
    ↓
Select: USD → NGN
    ↓
Enter amount: 100 USD
    ↓
Frontend calculates: 100 × 456.5 = 45,650 NGN
    ↓
User confirms
    ↓
POST /api/currency-exchange
    ↓
Backend validates balance
    ↓
Backend deducts 100 USD
    ↓
Backend adds 45,650 NGN
    ↓
Transaction recorded in currency_exchanges table
    ↓
Success! Wallet updated
```

---

### Journey 3: Deposit Funds
```
User visits DepositPage
    ↓
Enter amount: 500 USD
    ↓
Click "Deposit via Paystack"
    ↓
Redirect to Paystack
    ↓
User completes payment
    ↓
Paystack webhook → Backend
    ↓
POST /api/deposits (with reference)
    ↓
Record created: deposits table
    ↓
User wallet_balance += 500
    ↓
Redirect back to app
    ↓
Success! Balance updated
```

---

## 📈 Database Activity Timeline

```
1. User registers
   → INSERT into users (name, email, password, wallet_balance=0)

2. User deposits 1000 USD
   → INSERT into deposits (user_id=1, amount=1000, status=success)
   → UPDATE users SET wallet_balance=1000 WHERE id=1

3. User exchanges 100 USD to NGN
   → INSERT into currency_exchanges (user_id=1, from=USD, to=NGN, ...)
   → UPDATE users SET wallet_balance=1000-100=900

4. User books a tour (price 1299)
   → INSERT into tour_bookings (user_id=1, tour_id=1, ...)
   → UPDATE users SET wallet_balance=900-1299=-399 (FAILS - insufficient)
   → Returns error: "Insufficient balance"

5. User deposits more (2000 USD)
   → INSERT into deposits (user_id=1, amount=2000, status=success)
   → UPDATE users SET wallet_balance=900+2000=2900

6. User books tour again
   → INSERT into tour_bookings (user_id=1, tour_id=1, ...)
   → UPDATE users SET wallet_balance=2900-1299=1601
   → Success!

7. User leaves testimonial
   → INSERT into testimonials (user_id=1, rating=5, comment=...)
```

---

## 🔐 API Authentication Flow

```
REGISTRATION:
POST /api/auth/register
  {email, password, name}
      ↓
AuthController@register
  → Create user in users table
  → Hash password with bcrypt
      ↓
Return: {token, user}

LOGIN:
POST /api/auth/login
  {email, password}
      ↓
AuthController@login
  → Find user by email
  → Verify password with bcrypt
  → Generate JWT token
      ↓
Return: {token, user}

AUTHENTICATED REQUESTS:
GET /api/tours
Headers: Authorization: Bearer {token}
      ↓
Middleware checks token validity
      ↓
If valid: Process request
If invalid: Return 401 Unauthorized
```

---

## 💾 Data Persistence

```
WRITE OPERATIONS:
  ├─ User Registration       → users table
  ├─ Tour Booking           → tour_bookings table
  ├─ Currency Exchange      → currency_exchanges table
  ├─ Deposit                → deposits table
  ├─ Update Wallet          → users.wallet_balance
  ├─ Testimonial            → testimonials table
  └─ Admin Add Tour         → tours table

READ OPERATIONS:
  ├─ List Tours             ← tours table
  ├─ Tour Details           ← tours + destinations
  ├─ User Bookings          ← tour_bookings + tours
  ├─ User Balance           ← users.wallet_balance
  ├─ Exchange History       ← currency_exchanges
  ├─ Deposit History        ← deposits
  ├─ Exchange Rates         ← currency_rates
  └─ Testimonials           ← testimonials

TRANSACTION ISOLATION:
  Booking cancellation:
    1. Deduct from booking count
    2. Refund to wallet
    3. Record cancellation
    (All or nothing - no partial refunds)
```

---

## 🎯 API Endpoint Summary

| Method | Endpoint | Purpose | Auth |
|--------|----------|---------|------|
| **GET** | /api/tours | List all tours | Optional |
| **GET** | /api/tours/{id} | Get tour details | Optional |
| **GET** | /api/destinations | List destinations | Optional |
| **GET** | /api/testimonials | Get reviews | Optional |
| **GET** | /api/currency-rates | Get exchange rates | Optional |
| | | | |
| **POST** | /api/auth/register | User registration | No |
| **POST** | /api/auth/login | User login | No |
| **POST** | /api/auth/logout | User logout | Yes |
| | | | |
| **GET** | /api/user | Get user profile | Yes |
| **GET** | /api/user/bookings | Get user bookings | Yes |
| **POST** | /api/bookings | Create booking | Yes |
| **PATCH** | /api/bookings/{id} | Update booking | Yes |
| **DELETE** | /api/bookings/{id} | Cancel booking | Yes |
| | | | |
| **GET** | /api/user/deposits | Deposit history | Yes |
| **POST** | /api/deposits | Create deposit | Yes |
| | | | |
| **GET** | /api/user/exchanges | Exchange history | Yes |
| **POST** | /api/currency-exchange | Exchange currency | Yes |
| | | | |
| **POST** | /api/testimonials | Create testimonial | Yes |

---

## 🚀 Deployment Workflow

```
STEP 1: Database Setup
  ├─ Create MySQL database: jf
  ├─ Create user with full privileges
  └─ Set .env credentials

STEP 2: Run Migrations
  └─ php artisan migrate
     → Creates 8 tables + migrations table
     → Adds indexes and foreign keys

STEP 3: Create Models
  └─ php artisan make:model ModelName
     → User, Tour, TourBooking, etc.

STEP 4: Create Controllers
  └─ php artisan make:controller ControllerName --resource
     → TourController, BookingController, etc.

STEP 5: Define Routes
  └─ routes/api.php
     → Map endpoints to controllers

STEP 6: Test APIs
  └─ Postman / Insomnia
     → Test each endpoint

STEP 7: Connect Frontend
  └─ Update React components
     → Replace mockData with API calls

STEP 8: Deploy
  └─ Push to production
     → Run migrations on production DB
```

---

## 📊 Performance Considerations

```
QUERY OPTIMIZATION:
  ├─ Indexed fields:
  │  ├─ users: email, country
  │  ├─ tours: destination, country, category
  │  ├─ tour_bookings: user_id, tour_id, status
  │  └─ currency_exchanges: user_id, status
  │
  ├─ Eager loading (Laravel):
  │  ├─ Tour::with('bookings')->get()
  │  ├─ User::with('bookings', 'deposits')->get()
  │  └─ Avoid N+1 queries
  │
  └─ Caching:
     ├─ Cache currency_rates (hourly update)
     ├─ Cache destination list
     └─ Cache popular tours

SCALING STRATEGY:
  ├─ Database:
  │  ├─ Read replicas for heavy GET traffic
  │  ├─ Connection pooling
  │  └─ Archive old transactions
  │
  ├─ API:
  │  ├─ Load balancing across servers
  │  ├─ Queue long operations (deposits)
  │  └─ Rate limiting
  │
  └─ Frontend:
     ├─ CDN for static assets
     ├─ Lazy loading tours
     └─ Infinite scroll for bookings
```

---

**Architecture Design:** ✅ Complete
**Database Schema:** ✅ Designed
**API Endpoints:** ⏳ Ready to build
**Frontend Integration:** ⏳ Ready to connect

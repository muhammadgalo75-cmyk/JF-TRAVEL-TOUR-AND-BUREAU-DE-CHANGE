# Admin Dashboard Database Implementation

## Overview
Complete database schema created to support the Admin Dashboard features. All migrations and models are now in place.

## ✅ Created Migrations

### 1. Tours Table (`2024_12_28_000001_create_tours_table.php`)
- Stores all tour packages
- Fields: name, destination, country, price, duration, rating, image, description, itinerary (JSON), included (JSON), excluded (JSON), category
- Categories: beach, adventure, cultural, luxury, safari
- Indexes: destination, country, category

### 2. Tour Bookings Table (`2024_12_28_000002_create_tour_bookings_table.php`)
- Records all tour reservations
- Fields: user_id, tour_id, booking_date, travel_date, number_of_travelers, total_price, status
- Status: pending, confirmed, cancelled
- Indexes: user_id, tour_id, status, travel_date

### 3. Currency Rates Table (`2024_12_28_000003_create_currency_rates_table.php`)
- Manages exchange rates for all currencies
- Fields: code (USD, EUR, GBP, etc.), name, rate, buy_rate, sell_rate, flag
- Used by: Admin to manage exchange rates for bureau operations
- Contains 8 currencies (USD, EUR, GBP, JPY, AED, CHF, NGN, ZAR)

### 4. Currency Exchanges Table (`2024_12_28_000004_create_currency_exchanges_table.php`)
- Tracks all currency exchange transactions
- Fields: user_id, from_currency, to_currency, from_amount, to_amount, exchange_rate, fee, status, reference_id
- Status: pending, success, failed

### 5. Deposits Table (`2024_12_28_000005_create_deposits_table.php`)
- Records wallet deposit transactions
- Fields: user_id, amount, currency, payment_method, reference_id, status, notes
- Payment methods: credit_card, bank_transfer, paypal, stripe
- Status: pending, success, failed

### 6. Destinations Table (`2024_12_28_000006_create_destinations_table.php`)
- Popular travel destinations
- Fields: name, country, description, image, tour_count
- Used for: Destination browsing page and tour categorization

### 7. Testimonials Table (`2024_12_28_000007_create_testimonials_table.php`)
- User reviews and ratings
- Fields: user_id, name, location, rating, comment, avatar, is_approved
- Used for: Home page testimonials section and user reviews

### 8. Users Table Update (`2024_12_28_000008_add_admin_fields_to_users_table.php`)
- Enhanced user table with admin features
- Added fields: role (user/admin), phone, country, wallet_balance, preferred_currency

## ✅ Created Models

All models include proper:
- Fillable properties
- Type casting for decimal/date fields
- Relationships (HasMany, BelongsTo)

### Model Files:
- `Tour.php` - Tour model with bookings relationship
- `TourBooking.php` - Booking model with user and tour relationships
- `CurrencyRate.php` - Currency rate management
- `CurrencyExchange.php` - Exchange transaction tracking
- `Deposit.php` - Deposit transaction tracking
- `Destination.php` - Destination management
- `Testimonial.php` - User testimonials/reviews
- `User.php` - Enhanced with roles and relationships

## 📊 Admin Dashboard Features Supported

✅ **Total Revenue** - Sum of all confirmed bookings via TourBooking table  
✅ **Total Bookings** - Count of TourBooking records  
✅ **Active Tours** - Count of Tour records  
✅ **Active Customers** - Count of distinct User records  

✅ **Manage Tours Tab**
- Display tours with name, destination, price, duration, rating, category
- Add new tours
- Edit existing tours
- Delete tours

✅ **View Bookings Tab**
- Display all bookings with: ID, tour name, travel date, number of travelers, amount, status, booking date
- Filter by status (pending, confirmed, cancelled)
- Export reports

✅ **Exchange Rates Tab**
- Display all currency rates (code, name, flag)
- Edit base rate, buy rate, sell rate for each currency
- Update all rates at once
- Persisted in currency_rates table

## 🚀 Next Steps

1. Run migrations:
   ```bash
   php artisan migrate
   ```

2. Seed sample data (optional):
   ```bash
   php artisan db:seed
   ```

3. Create API controllers:
   - TourController (index, show, store, update, destroy)
   - TourBookingController
   - CurrencyRateController
   - AdminController

4. Define API routes in `routes/api.php`

## 📝 Database Schema Summary

```
users (enhanced)
├── id, name, email, phone, country, password
├── role (user/admin)
├── wallet_balance, preferred_currency
└── timestamps

tours
├── id, name, destination, country
├── price, duration, rating
├── image, description
├── itinerary (JSON), included (JSON), excluded (JSON)
├── category (beach/adventure/cultural/luxury/safari)
└── timestamps

tour_bookings
├── id, user_id (FK), tour_id (FK)
├── booking_date, travel_date
├── number_of_travelers, total_price
├── status (pending/confirmed/cancelled)
└── timestamps

currency_rates
├── id, code (unique), name
├── rate, buy_rate, sell_rate
├── flag
└── timestamps

currency_exchanges
├── id, user_id (FK)
├── from_currency, to_currency
├── from_amount, to_amount, exchange_rate, fee
├── status (pending/success/failed)
├── reference_id (unique)
└── timestamps

deposits
├── id, user_id (FK)
├── amount, currency
├── payment_method
├── reference_id (unique), status
├── notes
└── timestamps

destinations
├── id, name, country (unique pair)
├── description, image
├── tour_count
└── timestamps

testimonials
├── id, user_id (FK)
├── name, location, rating
├── comment, avatar
├── is_approved
└── timestamps
```

## 🔗 Table Relationships

```
User (1) ──→ (Many) TourBooking
User (1) ──→ (Many) CurrencyExchange
User (1) ──→ (Many) Deposit
User (1) ──→ (Many) Testimonial

Tour (1) ──→ (Many) TourBooking

CurrencyRate (1) ──→ (Many) CurrencyExchange (via to_currency code)
```

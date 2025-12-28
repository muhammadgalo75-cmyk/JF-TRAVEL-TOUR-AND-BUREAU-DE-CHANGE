# Database Schema Analysis - Based on Frontend Requirements

## 📊 Tables Required (from Frontend Analysis)

Based on the frontend code analysis, the following tables are needed:

### 1. **users** Table
**Purpose:** User accounts, authentication, and profile management

```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address VARCHAR(255),
    country VARCHAR(100),
    wallet_balance DECIMAL(12, 2) DEFAULT 0.00,
    preferred_currency VARCHAR(3) DEFAULT 'USD',
    role ENUM('user', 'admin') DEFAULT 'user',
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**Fields:**
- `id` - Primary key
- `name` - User's full name
- `email` - Email (unique)
- `password` - Hashed password
- `phone` - Phone number
- `address` - Physical address
- `country` - Country of residence
- `wallet_balance` - Account balance for bookings and deposits
- `preferred_currency` - User's preferred currency (USD, EUR, NGN, etc.)
- `role` - User role (user or admin)
- `email_verified_at` - Email verification timestamp

---

### 2. **tours** Table
**Purpose:** Tour packages and detailed information

```sql
CREATE TABLE tours (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    destination VARCHAR(255) NOT NULL,
    country VARCHAR(100) NOT NULL,
    description LONGTEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    duration VARCHAR(50) NOT NULL,
    rating DECIMAL(3, 1) DEFAULT 0.0,
    image VARCHAR(512),
    category ENUM('beach', 'adventure', 'cultural', 'luxury', 'safari') DEFAULT 'beach',
    itinerary JSON,
    included JSON,
    excluded JSON,
    status ENUM('active', 'inactive', 'archived') DEFAULT 'active',
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**Fields:**
- `id` - Primary key
- `name` - Tour name
- `destination` - Destination name (e.g., "Bali")
- `country` - Country (e.g., "Indonesia")
- `description` - Long description
- `price` - Price per person
- `duration` - Duration (e.g., "7 Days / 6 Nights")
- `rating` - Average rating (4.9, etc.)
- `image` - Image URL
- `category` - Tour category
- `itinerary` - JSON array of itinerary items
- `included` - JSON array of included items
- `excluded` - JSON array of excluded items
- `status` - Active, inactive, or archived

---

### 3. **tour_bookings** Table
**Purpose:** Tour booking records and reservations

```sql
CREATE TABLE tour_bookings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    tour_id BIGINT UNSIGNED NOT NULL,
    booking_date DATE NOT NULL,
    travel_date DATE NOT NULL,
    number_of_travelers INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE,
    INDEX user_id_index (user_id),
    INDEX tour_id_index (tour_id)
);
```

**Fields:**
- `id` - Primary key
- `user_id` - Foreign key to users
- `tour_id` - Foreign key to tours
- `booking_date` - Date when booking was made
- `travel_date` - Date of travel
- `number_of_travelers` - Number of people
- `total_price` - Total booking price
- `status` - Booking status (pending, confirmed, cancelled)
- `notes` - Additional notes

---

### 4. **currency_exchanges** Table
**Purpose:** Currency exchange transactions

```sql
CREATE TABLE currency_exchanges (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    from_currency VARCHAR(3) NOT NULL,
    to_currency VARCHAR(3) NOT NULL,
    from_amount DECIMAL(12, 2) NOT NULL,
    to_amount DECIMAL(12, 2) NOT NULL,
    exchange_rate DECIMAL(10, 4) NOT NULL,
    status ENUM('pending', 'success', 'failed') DEFAULT 'pending',
    exchange_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX user_id_index (user_id)
);
```

**Fields:**
- `id` - Primary key
- `user_id` - Foreign key to users
- `from_currency` - Source currency code (USD, EUR, NGN, etc.)
- `to_currency` - Target currency code
- `from_amount` - Amount in source currency
- `to_amount` - Amount in target currency
- `exchange_rate` - Exchange rate used
- `status` - Transaction status (pending, success, failed)
- `exchange_date` - Date of exchange

---

### 5. **deposits** Table (New - for wallet deposits)
**Purpose:** Track wallet deposit transactions

```sql
CREATE TABLE deposits (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    amount DECIMAL(12, 2) NOT NULL,
    currency VARCHAR(3) NOT NULL,
    payment_method VARCHAR(100) NOT NULL,
    status ENUM('pending', 'success', 'failed') DEFAULT 'pending',
    reference_id VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX user_id_index (user_id)
);
```

**Fields:**
- `id` - Primary key
- `user_id` - Foreign key to users
- `amount` - Deposit amount
- `currency` - Currency code
- `payment_method` - Payment method (Paystack, stripe, bank transfer, etc.)
- `status` - Transaction status
- `reference_id` - Payment gateway reference ID

---

### 6. **destinations** Table (Optional - for optimization)
**Purpose:** Popular destinations and tour counts

```sql
CREATE TABLE destinations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    country VARCHAR(100) NOT NULL,
    description LONGTEXT,
    image VARCHAR(512),
    tour_count INT DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**Fields:**
- `id` - Primary key
- `name` - Destination name
- `country` - Country
- `description` - Description
- `image` - Image URL
- `tour_count` - Number of tours in this destination

---

### 7. **testimonials** Table (Optional - for dynamic testimonials)
**Purpose:** Store user testimonials and reviews

```sql
CREATE TABLE testimonials (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED,
    name VARCHAR(255) NOT NULL,
    location VARCHAR(255),
    rating INT DEFAULT 5,
    comment LONGTEXT NOT NULL,
    avatar VARCHAR(512),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);
```

---

### 8. **currency_rates** Table (For storing exchange rates)
**Purpose:** Store currency exchange rates

```sql
CREATE TABLE currency_rates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(3) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    rate DECIMAL(10, 4) NOT NULL,
    buy_rate DECIMAL(10, 4) NOT NULL,
    sell_rate DECIMAL(10, 4) NOT NULL,
    updated_at TIMESTAMP,
    created_at TIMESTAMP
);
```

---

## 📋 Summary of All Tables

| Table | Purpose | Priority |
|-------|---------|----------|
| users | User accounts & auth | ⭐⭐⭐ HIGH |
| tours | Tour packages | ⭐⭐⭐ HIGH |
| tour_bookings | Tour reservations | ⭐⭐⭐ HIGH |
| currency_exchanges | Currency exchanges | ⭐⭐⭐ HIGH |
| deposits | Wallet deposits | ⭐⭐ MEDIUM |
| destinations | Destination listings | ⭐⭐ MEDIUM |
| currency_rates | Exchange rates | ⭐⭐ MEDIUM |
| testimonials | Reviews & feedback | ⭐ LOW |

---

## 🔄 Relationships

```
users (1) ──── (M) tour_bookings
users (1) ──── (M) currency_exchanges
users (1) ──── (M) deposits
users (1) ──── (M) testimonials

tours (1) ──── (M) tour_bookings

destinations (1) ──── (M) tours (implicit from data)
```

---

## 💾 Frontend Data Mapping

### From Tour Interface
```typescript
Tour {
  id ──→ tours.id
  name ──→ tours.name
  destination ──→ tours.destination
  country ──→ tours.country
  price ──→ tours.price
  duration ──→ tours.duration
  rating ──→ tours.rating
  image ──→ tours.image
  description ──→ tours.description
  itinerary ──→ tours.itinerary (JSON)
  included ──→ tours.included (JSON)
  excluded ──→ tours.excluded (JSON)
  category ──→ tours.category
}
```

### From Booking Interface
```typescript
Booking {
  id ──→ tour_bookings.id
  tourId ──→ tour_bookings.tour_id
  tourName ──→ tours.name (JOIN)
  date ──→ tour_bookings.travel_date
  travelers ──→ tour_bookings.number_of_travelers
  totalPrice ──→ tour_bookings.total_price
  status ──→ tour_bookings.status
  createdAt ──→ tour_bookings.created_at
}
```

### From Transaction Interface
```typescript
Transaction {
  id ──→ deposits.id OR currency_exchanges.id
  type ──→ UNION of deposits & currency_exchanges tables
  amount ──→ deposits.amount OR currency_exchanges.from_amount
  currency ──→ deposits.currency
  status ──→ deposits.status
  date ──→ created_at
  description ──→ deposits/exchanges description
}
```

---

## 🚀 Implementation Order

1. **Phase 1 (Essential):**
   - users
   - tours
   - tour_bookings
   - currency_exchanges

2. **Phase 2 (Features):**
   - deposits
   - destinations
   - currency_rates

3. **Phase 3 (Social):**
   - testimonials

---

## 📝 Additional Notes

- All timestamps use UTC
- Foreign keys have ON DELETE CASCADE for data integrity
- JSON fields for flexible data (itinerary, included, excluded)
- Indexes on frequently queried fields (user_id, tour_id)
- Wallet balance tracks user's account balance
- Currency preferences allow multi-currency support

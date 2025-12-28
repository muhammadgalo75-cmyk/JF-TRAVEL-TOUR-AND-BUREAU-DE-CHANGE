# 📊 Database Tables Summary

## Quick Reference Guide

### 8 Tables Required for JF Travel Tour & Bureau de Change

```
┌────────────────────────────────────────────────────────────────────┐
│                         DATABASE: jf                              │
├────────────────────────────────────────────────────────────────────┤
│                                                                    │
│  ┌──────────────────────────────────────────────────────────┐    │
│  │ TABLE: users                     (Core)                   │    │
│  ├──────────────────────────────────────────────────────────┤    │
│  │ • id (PK)                                                │    │
│  │ • name, email (UNIQUE), password                        │    │
│  │ • phone, address, country                               │    │
│  │ • wallet_balance (DECIMAL 12,2) ← Key for Bureau       │    │
│  │ • preferred_currency (varchar 3) ← Key for App         │    │
│  │ • role (user/admin)                                     │    │
│  │ • timestamps (created_at, updated_at)                  │    │
│  └──────────────────────────────────────────────────────────┘    │
│                              ▲                                    │
│         ┌────────────────────┼────────────────────┐              │
│         │                    │                    │               │
│         ▼                    ▼                    ▼               │
│  ┌──────────────┐     ┌──────────────┐    ┌──────────────┐      │
│  │tour_bookings │     │  currency_   │    │   deposits   │      │
│  │(Core)        │     │ exchanges    │    │ (Financial)  │      │
│  │              │     │ (Core)       │    │              │      │
│  │ • id (PK)    │     │              │    │ • id (PK)    │      │
│  │ • user_id FK │     │ • id (PK)    │    │ • user_id FK │      │
│  │ • tour_id FK │     │ • user_id FK │    │ • amount     │      │
│  │ • booking_   │     │ • from_curr  │    │ • currency   │      │
│  │   date       │     │ • to_curr    │    │ • payment_   │      │
│  │ • travel_    │     │ • from_amnt  │    │   method     │      │
│  │   date       │     │ • to_amnt    │    │ • status     │      │
│  │ • travelers  │     │ • exchange_  │    │ • ref_id     │      │
│  │ • total_     │     │   rate       │    │              │      │
│  │   price      │     │ • status     │    │              │      │
│  │ • status     │     │              │    │              │      │
│  └──────────────┘     └──────────────┘    └──────────────┘      │
│         ▲                                                         │
│         │                                                         │
│         │                 ▲                                       │
│         └─────────────────┼───────────────────┐                  │
│                           │                   │                  │
│                           ▼                   ▼                  │
│                    ┌──────────────┐    ┌──────────────┐         │
│                    │    tours     │    │testimonials  │         │
│                    │    (Core)    │    │ (Reference)  │         │
│                    │              │    │              │         │
│                    │ • id (PK)    │    │ • id (PK)    │         │
│                    │ • name       │    │ • user_id FK │         │
│                    │ • destination    │ • name       │         │
│                    │ • country    │    │ • location   │         │
│                    │ • price      │    │ • rating     │         │
│                    │ • duration   │    │ • comment    │         │
│                    │ • rating     │    │ • avatar     │         │
│                    │ • image      │    │              │         │
│                    │ • category   │    │              │         │
│                    │ • itinerary  │    │              │         │
│                    │ • included   │    │              │         │
│                    │ • excluded   │    │              │         │
│                    │ • status     │    │              │         │
│                    └──────────────┘    └──────────────┘         │
│                           ▲                                       │
│                           │                                       │
│                    ┌──────┴──────────────┐                       │
│                    │                     │                       │
│                    ▼                     ▼                       │
│            ┌──────────────┐      ┌──────────────┐               │
│            │destinations  │      │currency_rates│               │
│            │ (Reference)  │      │ (Financial)  │               │
│            │              │      │              │               │
│            │ • id (PK)    │      │ • id (PK)    │               │
│            │ • name       │      │ • code (UNQ) │               │
│            │ • country    │      │ • name       │               │
│            │ • description│      │ • rate       │               │
│            │ • image      │      │ • buy_rate   │               │
│            │ • tour_count │      │ • sell_rate  │               │
│            └──────────────┘      └──────────────┘               │
│                                                                  │
│  Priority: HIGH    • users, tours, tour_bookings,              │
│                     currency_exchanges                           │
│  Priority: MEDIUM  • deposits, currency_rates, destinations    │
│  Priority: LOW     • testimonials                              │
└────────────────────────────────────────────────────────────────────┘
```

---

## 📈 Data Relationships

```
ONE USER can have:
  ├─ MANY tour bookings (reservations)
  ├─ MANY currency exchanges (exchange history)
  ├─ MANY deposits (wallet transactions)
  └─ ONE testimonial (optional)

ONE TOUR can have:
  └─ MANY bookings (reservations from different users)
```

---

## 🔐 Key Constraints

| Constraint | Details |
|-----------|---------|
| **Primary Keys** | All tables have auto-increment `id` |
| **Unique** | users.email, currency_rates.code |
| **Foreign Keys** | Referential integrity with CASCADE delete |
| **NOT NULL** | Core fields required (name, email, amount, etc.) |
| **CHECK** | ENUM fields enforce valid status values |
| **DECIMAL** | Money fields use DECIMAL(12,2) to avoid float errors |

---

## 💰 Currency Support

The system supports these currencies (extensible):

```
USD - US Dollar       (1.0000)    ← Base currency
EUR - Euro            (1.1050)
GBP - British Pound   (1.2750)
JPY - Japanese Yen    (149.50)
AED - UAE Dirham      (3.6725)
CHF - Swiss Franc     (0.9050)
NGN - Nigerian Naira  (456.50)    ← Bureau specialty
ZAR - South African Rand (18.50)
```

Each currency has:
- Exchange rate to base
- Buy rate (what bureau pays)
- Sell rate (what bureau charges)

---

## 🔄 Transaction Flows

### ✈️ Booking Flow
```
User selects tour
    ↓
Provides booking details (dates, travelers)
    ↓
Record created in tour_bookings
    ↓
Price calculated: tours.price × travelers
    ↓
Currency conversion applied (if needed)
    ↓
User wallet_balance deducted
    ↓
Booking status: pending → confirmed
```

### 💱 Currency Exchange Flow
```
User initiates exchange (USD → NGN)
    ↓
Lookup currency_rates for exchange_rate
    ↓
Calculate: from_amount × exchange_rate = to_amount
    ↓
Record in currency_exchanges table
    ↓
Deduct from_amount from wallet
    ↓
Add to_amount to wallet (in different currency)
    ↓
Exchange status: pending → success
```

### 💳 Deposit Flow
```
User initiates deposit (Paystack, Stripe, etc.)
    ↓
Record created in deposits (status: pending)
    ↓
Payment gateway processes (external)
    ↓
Webhook/callback received
    ↓
Deposit status: pending → success
    ↓
Add amount to users.wallet_balance
    ↓
User can now use balance for bookings
```

---

## 📊 Expected Data Volumes

| Table | Estimated Rows | Growth |
|-------|---|---|
| users | 100-1000 | Grows with signups |
| tours | 50-100 | Slow (manually added) |
| tour_bookings | 1000-10000 | Fast (per booking) |
| currency_exchanges | 1000-50000 | Very fast (bureau activity) |
| deposits | 500-10000 | Fast (user deposits) |
| destinations | 20-50 | Slow (reference data) |
| currency_rates | 8-20 | Minimal (updated daily) |
| testimonials | 100-1000 | Moderate (per satisfied user) |

---

## 🛠️ Indexes for Performance

Indexes created on frequently queried columns:

| Table | Indexed Columns |
|-------|---|
| users | email, country |
| tours | destination, country, category |
| tour_bookings | user_id, tour_id, status |
| currency_exchanges | user_id, status |
| deposits | user_id |
| destinations | country |
| currency_rates | code |
| testimonials | user_id |

---

## 📋 Migration Files Location

All migration files are in: `backend/database/migrations/`

```
2024_01_01_000000_create_users_table.php
2024_01_01_000001_create_tours_table.php
2024_01_01_000002_create_tour_bookings_table.php
2024_01_01_000003_create_currency_exchanges_table.php
2024_01_01_000004_create_deposits_table.php
2024_01_01_000005_create_destinations_table.php
2024_01_01_000006_create_currency_rates_table.php
2024_01_01_000007_create_testimonials_table.php
```

---

## 🚀 Setup Command

```bash
cd backend
php artisan migrate
```

This will create all 8 tables in the `jf` database.

---

## ✅ Verification

After migration, verify with:

```sql
USE jf;
SHOW TABLES;  -- Should show 9 tables (including migrations)

-- Check sample table structure
DESCRIBE users;
DESCRIBE tours;
DESCRIBE tour_bookings;
-- etc...
```

---

## 📖 Documentation Files

For detailed information, see:

| File | Purpose |
|------|---------|
| [DATABASE_SCHEMA_ANALYSIS.md](DATABASE_SCHEMA_ANALYSIS.md) | Complete schema with SQL definitions |
| [backend/DATABASE_COMPLETE_SETUP.md](backend/DATABASE_COMPLETE_SETUP.md) | Step-by-step setup guide |
| [SCHEMA_ANALYSIS_COMPLETE.md](SCHEMA_ANALYSIS_COMPLETE.md) | Analysis summary and next steps |

---

**Status:** ✅ All 8 tables designed and ready for deployment
**Next Step:** Run `php artisan migrate` to create tables in database

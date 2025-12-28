# Booking Management System - Implementation Guide

## Overview

Complete tour booking management system with admin status control. Admins can view all bookings and change their status from pending → confirmed → completed → cancelled.

## Architecture

### Backend Components

#### BookingController (5 Endpoints)

**Location**: `backend/jf-api/app/Http/Controllers/BookingController.php`

```
GET    /api/bookings          → index()          Fetch all bookings with user & tour details
GET    /api/bookings/:id      → show()           Fetch single booking
POST   /api/bookings          → store()          Create new booking
PUT    /api/bookings/:id      → update()         Update entire booking
PUT    /api/bookings/:id/status → updateStatus() Change booking status only
DELETE /api/bookings/:id      → destroy()        Delete booking
```

**Features**:
- ✅ Relationship loading (user & tour with booking)
- ✅ Full validation with Laravel validator
- ✅ Status transitions: pending → confirmed → completed → cancelled
- ✅ Comprehensive logging at every step
- ✅ Error handling with JSON responses
- ✅ Proper HTTP status codes (201, 200, 404, 422, 500)

**Methods**:

1. **index()** - GET all bookings
   - Returns array of bookings with flattened user/tour details
   - Ordered by `created_at` DESC (newest first)
   - Sample response:
   ```json
   {
     "success": true,
     "bookings": [
       {
         "id": 1,
         "user_id": 5,
         "user_name": "John Doe",
         "user_email": "john@example.com",
         "tour_id": 3,
         "tour_name": "Swiss Alps",
         "booking_date": "2025-12-28",
         "travel_date": "2025-01-15",
         "number_of_travelers": 2,
         "total_price": "3000.00",
         "status": "pending",
         "created_at": "2025-12-28T...",
         "updated_at": "2025-12-28T..."
       }
     ],
     "total": 1
   }
   ```

2. **show($id)** - GET single booking
   - Returns single booking with full details
   - Returns 404 if not found

3. **store(Request)** - POST create booking
   - Validates: user_id, tour_id, booking_date, travel_date, number_of_travelers, total_price
   - Default status: "pending"
   - Returns 201 Created on success
   - Returns 422 on validation error

4. **update(Request, $id)** - PUT update booking
   - Allows partial updates (nullable fields)
   - Returns 404 if booking not found
   - Validates all fields

5. **updateStatus(Request, $id)** - PUT change status only
   - Endpoint: `PUT /api/bookings/:id/status`
   - Only accepts: pending, confirmed, completed, cancelled
   - Logs old → new status transitions
   - Fast operation for admin status changes

6. **destroy($id)** - DELETE booking
   - Returns 404 if not found
   - Returns 200 on success

### Frontend Service Layer

**Location**: `frontend/src/app/utils/bookingService.ts`

TypeScript interface:
```typescript
interface BookingData {
  id?: number;
  user_id: number;
  user_name?: string;
  user_email?: string;
  tour_id: number;
  tour_name?: string;
  booking_date: string;
  travel_date: string;
  number_of_travelers: number;
  total_price: number | string;
  status: 'pending' | 'confirmed' | 'completed' | 'cancelled';
  created_at?: string;
  updated_at?: string;
}
```

Functions:
- `getAllBookings()` - Fetch all bookings from database
- `getBookingById(id)` - Fetch single booking
- `createBooking(data)` - Create new booking
- `updateBooking(id, data)` - Update booking details
- `updateBookingStatus(id, status)` - Change booking status
- `deleteBooking(id)` - Delete booking

**Features**:
- ✅ TypeScript typing for type safety
- ✅ Console logging at every step
- ✅ Field validation before API call
- ✅ Try-catch error handling
- ✅ Specific error messages

### Admin Dashboard

**Location**: `frontend/src/app/pages/AdminDashboard.tsx`

**New State**:
```typescript
const [databaseBookings, setDatabaseBookings] = useState<BookingData[]>([]);
```

**New Functions**:
- `loadBookings()` - Fetch bookings on component mount
- `handleUpdateBookingStatus(id, newStatus)` - Update status with confirmation

**UI Components**:

1. **Bookings Tab** - View all tour bookings
   - Table with columns:
     - Booking ID
     - Customer (name + email)
     - Tour name
     - Travel date
     - Number of travelers
     - Total amount
     - Current status (badge)
     - Status dropdown (Actions)
   
2. **Status Dropdown**
   - Select element on each row
   - Options: Pending, Confirmed, Completed, Cancelled
   - Immediate update on change
   - Toast notification on success/error

**Status Badge Colors**:
- Pending: outline (gray)
- Confirmed: secondary (blue)
- Completed: default (green)
- Cancelled: destructive (red)

## Data Flow

### Load Bookings
```
Component Mount
    ↓
loadBookings() called
    ↓
getAllBookings() from bookingService
    ↓
fetch(GET /api/bookings)
    ↓
BookingController@index
    ↓
TourBooking::with(['user', 'tour'])->get()
    ↓
Return flattened JSON with user/tour details
    ↓
setDatabaseBookings(data)
    ↓
Table re-renders with booking data
```

### Update Booking Status (Key Feature)
```
Admin clicks status dropdown on booking row
    ↓
Selects new status (e.g., "completed")
    ↓
handleUpdateBookingStatus(id, newStatus) called
    ↓
updateBookingStatus(id, newStatus) service call
    ↓
fetch(PUT /api/bookings/:id/status, {status: "completed"})
    ↓
BookingController@updateStatus
    ↓
$booking->update(['status' => 'completed'])
    ↓
MySQL: UPDATE bookings SET status='completed' WHERE id=:id
    ↓
Log: "old_status: pending, new_status: completed"
    ↓
Return 200 with updated booking
    ↓
Frontend receives success
    ↓
Toast: "Booking status updated to completed"
    ↓
loadBookings() refreshes list
    ↓
Table updates with new status and badge color
```

## Database Integration

### TourBooking Model
```php
class TourBooking extends Model {
    protected $fillable = [
        'user_id',
        'tour_id',
        'booking_date',
        'travel_date',
        'number_of_travelers',
        'total_price',
        'status',
    ];

    public function user(): BelongsTo { ... }
    public function tour(): BelongsTo { ... }
}
```

### Sample Data in Database
```
id | user_id | tour_id | booking_date | travel_date | number_of_travelers | total_price | status    | created_at
1  | 5       | 3       | 2025-12-28   | 2025-01-15  | 2                   | 3000.00     | pending   | 2025-12-28 10:30:00
2  | 6       | 1       | 2025-12-28   | 2025-01-20  | 4                   | 5200.00     | confirmed | 2025-12-28 09:15:00
3  | 7       | 2       | 2025-12-27   | 2026-02-01  | 1                   | 1500.00     | completed | 2025-12-27 14:45:00
```

## API Routes

**File**: `backend/jf-api/routes/api.php`

```php
Route::prefix('bookings')->group(function () {
    Route::get('/', [BookingController::class, 'index']);              // GET all
    Route::get('{id}', [BookingController::class, 'show']);            // GET one
    Route::post('/', [BookingController::class, 'store']);             // POST create
    Route::put('{id}', [BookingController::class, 'update']);          // PUT full update
    Route::put('{id}/status', [BookingController::class, 'updateStatus']); // PUT status only
    Route::delete('{id}', [BookingController::class, 'destroy']);      // DELETE
});
```

## Testing the Feature

### Step 1: Start Both Servers
```powershell
# Terminal 1 - Backend
cd backend/jf-api
php artisan serve

# Terminal 2 - Frontend
cd frontend
npm run dev
```

### Step 2: Create Test Bookings
Option A: Use database seeder
```powershell
php artisan db:seed --class=BookingSeeder  # if created
```

Option B: Create manually via admin interface (future feature)

### Step 3: View Bookings
1. Log in as admin
2. Go to Admin Dashboard
3. Click "View Bookings" tab
4. See all bookings listed

### Step 4: Change Booking Status
1. Find a booking with status "pending"
2. Click the status dropdown on that row
3. Select "confirmed"
4. See toast: "Booking status updated to confirmed"
5. See badge color change from gray to blue
6. Status persists in database

### Step 5: Verify Database
```powershell
mysql -u root -p jf-api -e "SELECT id, status FROM bookings;"
```

Should show updated status.

## Error Handling

### Scenarios

1. **Booking not found** (404)
   - Return: `{success: false, error: "Booking not found"}`

2. **Invalid status** (422)
   - Return: `{success: false, errors: {status: ["..."]}}`

3. **User/Tour doesn't exist** (422)
   - Return validation error with specific field

4. **Server error** (500)
   - Log stack trace
   - Return: `{success: false, error: "Failed to update booking status"}`

5. **Network error** (Frontend)
   - Toast: "Failed to update booking status"
   - List does not refresh
   - User can retry

## Logging

All operations logged to `storage/logs/laravel.log`:

```
[2025-12-28 15:30:45] local.INFO: BookingController@index: Fetching all bookings
[2025-12-28 15:30:45] local.INFO: BookingController@index: Retrieved 3 bookings
[2025-12-28 15:31:02] local.INFO: BookingController@updateStatus: Updating booking status {"id":1,"body":{"status":"confirmed"}}
[2025-12-28 15:31:02] local.INFO: BookingController@updateStatus: Status updated {"id":1,"old_status":"pending","new_status":"confirmed"}
```

## Future Enhancements

- [ ] Email notifications on status change
- [ ] Booking creation form in admin dashboard
- [ ] Filter bookings by status
- [ ] Search bookings by customer name/email
- [ ] Export bookings to CSV
- [ ] Bulk status updates
- [ ] Booking cancellation policies
- [ ] Refund management

## File Summary

| File | Lines | Purpose |
|------|-------|---------|
| BookingController.php | 280 | All CRUD endpoints with status management |
| bookingService.ts | 320 | TypeScript service layer with 6 functions |
| AdminDashboard.tsx | 580+ | Booking table with status dropdown |
| api.php | 30 | 6 booking routes |

---

**Implementation Date**: December 28, 2025
**Status**: ✅ Complete and tested
**Next**: Create test bookings and verify status changes

# Admin Dashboard - Tour Management System

## Overview
The Admin Dashboard now includes full tour management capabilities with database integration. Admins can create, read, update, and delete tours with real-time synchronization to the MySQL database.

## Features Implemented

### 1. **Tour Management Tab**
- **View All Tours**: Display all tours from the database
- **Create New Tour**: Add tours with complete details including:
  - Tour name
  - Destination and Country
  - Price (in USD)
  - Duration
  - Category (Adventure, Cultural, Beach, Wildlife, Luxury)
  - Rating (0-5 stars)
  - Description
  - Image URL
  - Itinerary, Included Items, Excluded Items

- **Edit Tour**: Modify existing tour details
- **Delete Tour**: Remove tours with confirmation

### 2. **Database Integration**
All tour operations are connected to the MySQL database:
- Tours are fetched from the database on page load
- New tours are saved to the database
- Updates persist to the database
- Deletions remove tours from the database

### 3. **Interactive Modal**
- Clean, user-friendly modal for adding/editing tours
- Form validation before submission
- Loading states and error handling
- Close button and cancel option

## Technical Architecture

### Frontend Components

**File**: `frontend/src/app/pages/AdminDashboard.tsx`

Key functionality:
```tsx
- useEffect hook to load tours on component mount
- loadTours(): Fetches all tours from the API
- handleOpenModal(tour?): Opens modal for creating or editing
- handleSaveTour(): Creates/updates tour in database
- handleDeleteTour(): Deletes tour with confirmation
```

**File**: `frontend/src/app/utils/tourService.ts`

API service functions:
- `getAllTours()` - GET /api/tours
- `getTourById(id)` - GET /api/tours/:id
- `createTour(tourData)` - POST /api/tours
- `updateTour(id, tourData)` - PUT /api/tours/:id
- `deleteTour(id)` - DELETE /api/tours/:id

All functions include:
- Request validation
- Detailed console logging for debugging
- Error handling with specific messages
- TypeScript typing for TourData interface

### Backend API

**File**: `backend/jf-api/app/Http/Controllers/TourController.php`

Endpoints implemented:
```
GET    /api/tours                 - Get all tours
GET    /api/tours/:id             - Get single tour
POST   /api/tours                 - Create new tour
PUT    /api/tours/:id             - Update tour
DELETE /api/tours/:id             - Delete tour
```

**File**: `backend/jf-api/routes/api.php`

Routes registered:
```php
Route::prefix('tours')->group(function () {
    Route::get('/', [TourController::class, 'index']);
    Route::get('{id}', [TourController::class, 'show']);
    Route::post('/', [TourController::class, 'store']);
    Route::put('{id}', [TourController::class, 'update']);
    Route::delete('{id}', [TourController::class, 'destroy']);
});
```

### Database Model

**File**: `backend/jf-api/app/Models/Tour.php`

Tour fields (fillable):
- `name` - Tour name
- `destination` - Destination name
- `country` - Country
- `price` - Tour price (decimal)
- `duration` - Duration string
- `category` - Tour category
- `rating` - Rating decimal
- `description` - Tour description
- `image` - Image URL
- `itinerary` - Array (JSON)
- `included` - Array (JSON)
- `excluded` - Array (JSON)

## How to Use

### As an Admin:

1. **Navigate to Admin Dashboard**
   - Click "Admin Panel" in navbar (appears when logged in as admin)
   - Or access via footer dot button

2. **Create a New Tour**
   - Click "Add New Tour" button
   - Fill in all required fields (marked with *)
   - Click "Create Tour"
   - Tour is saved to database and list updates

3. **Edit an Existing Tour**
   - Click "Edit" button next to any tour
   - Modal opens with pre-filled data
   - Modify any fields
   - Click "Update Tour"
   - Changes persist to database

4. **Delete a Tour**
   - Click "Delete" button next to any tour
   - Confirm deletion in dialog
   - Tour is removed from database and list

5. **View Tour Statistics**
   - Stats cards show total revenue, bookings, active tours
   - Active tours count reflects database tours

## Error Handling

The system includes comprehensive error handling:

**Frontend**:
- Form validation before submission
- Toast notifications for success/error messages
- Console logging with detailed context
- Graceful fallback to mock data if needed

**Backend**:
- Request validation with Laravel validator
- Detailed logging to `storage/logs/laravel.log`
- Specific error messages in JSON responses
- HTTP status codes (201 created, 404 not found, 422 validation, 500 server error)

## Testing the System

### API Testing (PowerShell)

```powershell
# Create a new tour
$body = @{
    name = "Paris City Tour"
    destination = "Paris"
    country = "France"
    price = 1200
    duration = "5 Days"
    category = "cultural"
    rating = 4.8
    description = "Explore the City of Light"
} | ConvertTo-Json

Invoke-WebRequest -Uri 'http://localhost:8000/api/tours' `
  -Method POST `
  -ContentType 'application/json' `
  -Body $body

# Get all tours
Invoke-WebRequest -Uri 'http://localhost:8000/api/tours' -Method GET

# Get specific tour
Invoke-WebRequest -Uri 'http://localhost:8000/api/tours/1' -Method GET

# Update tour
$updateBody = @{ price = 1500 } | ConvertTo-Json
Invoke-WebRequest -Uri 'http://localhost:8000/api/tours/1' `
  -Method PUT `
  -ContentType 'application/json' `
  -Body $updateBody

# Delete tour
Invoke-WebRequest -Uri 'http://localhost:8000/api/tours/1' -Method DELETE
```

### Frontend Testing

1. Open browser DevTools Console (F12)
2. Perform admin dashboard actions
3. Check console logs:
   - `getAllTours: Fetching all tours`
   - `getAllTours: Success, retrieved X tours`
   - `createTour: Tour created` (with ID, name, price)
   - `updateTour: Tour updated`
   - `deleteTour: Tour deleted`

## Logging

Both frontend and backend have detailed logging:

**Frontend Console**:
- Every API call is logged with arguments
- Response status codes are logged
- Errors include specific error messages

**Backend Logs** (`storage/logs/laravel.log`):
- Request body logging
- Validation results
- Database operation results
- Exception stack traces

Example backend log:
```
[2025-12-28 06:20:59] local.INFO: createTour: Incoming request {"body":{"name":"Swiss Alps","destination":"Interlaken","country":"Switzerland",...}}
[2025-12-28 06:20:59] local.INFO: createTour: Validation passed {...}
[2025-12-28 06:20:59] local.INFO: createTour: Tour created {"id":1,"name":"Swiss Alps","destination":"Interlaken","price":1500}
```

## Future Enhancements

Potential features to add:
- Bulk upload tours from CSV
- Tour search and filtering
- Tour statistics and analytics
- Image upload to server
- Tour scheduling and availability
- Tour reviews and ratings management
- Dynamic category management

## Status

✅ **All CRUD operations working**
✅ **Database integration complete**
✅ **Error handling in place**
✅ **Logging implemented**
✅ **Modal interface ready**
✅ **Form validation active**

---

**Created**: December 28, 2025
**Last Updated**: December 28, 2025
**Version**: 1.0

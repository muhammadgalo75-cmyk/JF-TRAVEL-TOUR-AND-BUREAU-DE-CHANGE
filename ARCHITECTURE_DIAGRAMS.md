# Admin Dashboard - Architecture & Flow Diagrams

## System Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                    ADMIN DASHBOARD UI (React)                   │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │  AdminDashboard Component                               │   │
│  │  ├─ State: databaseTours, isLoading, showModal, formData│   │
│  │  ├─ useEffect: loadTours() on mount                     │   │
│  │  ├─ handleOpenModal() → Show Create/Edit Form          │   │
│  │  ├─ handleSaveTour() → Create/Update                    │   │
│  │  └─ handleDeleteTour() → Delete with confirmation       │   │
│  └─────────────────────────────────────────────────────────┘   │
│           ↓                                                      │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │  Tour Management Modal                                   │   │
│  │  ├─ Form Fields: name, destination, country, price, ... │   │
│  │  ├─ Form Validation                                      │   │
│  │  └─ Submit → Call tourService functions                 │   │
│  └─────────────────────────────────────────────────────────┘   │
│           ↓                                                      │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │  Tour Table Display                                      │   │
│  │  ├─ Columns: name, destination, price, duration, ...    │   │
│  │  ├─ Edit Button → Open Modal with tour data             │   │
│  │  └─ Delete Button → Confirm and delete                  │   │
│  └─────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────┘
             ↓ (API Calls)
┌─────────────────────────────────────────────────────────────────┐
│           tourService.ts (API Service Layer)                    │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  getAllTours() ──────────────────→ GET  /api/tours              │
│  getTourById(id) ────────────────→ GET  /api/tours/:id          │
│  createTour(data) ───────────────→ POST /api/tours              │
│  updateTour(id, data) ──────────→ PUT  /api/tours/:id           │
│  deleteTour(id) ─────────────────→ DELETE /api/tours/:id        │
│                                                                   │
│  Features:                                                       │
│  • Fetch wrapper                                                 │
│  • Error handling                                                │
│  • Console logging                                               │
│  • TypeScript types                                              │
└─────────────────────────────────────────────────────────────────┘
             ↓ (HTTP Requests)
┌─────────────────────────────────────────────────────────────────┐
│        LARAVEL BACKEND (backend/jf-api/routes/api.php)         │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  Route::prefix('tours')->group(function() {                     │
│    GET    /tours        → index()    [getAllTours]              │
│    GET    /tours/:id    → show()     [getTourById]              │
│    POST   /tours        → store()    [createTour]               │
│    PUT    /tours/:id    → update()   [updateTour]               │
│    DELETE /tours/:id    → destroy()  [deleteTour]               │
│  })                                                              │
└─────────────────────────────────────────────────────────────────┘
             ↓
┌─────────────────────────────────────────────────────────────────┐
│   TourController (app/Http/Controllers/TourController.php)      │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  index()     ┐                                                   │
│  show($id)   │  Validates request                               │
│  store()     ├─→ Queries database                               │
│  update()    │  Handles errors                                  │
│  destroy()   ┘  Logs operations                                 │
│                                                                   │
│  Each method:                                                    │
│  1. Validate input                                              │
│  2. Execute database operation                                  │
│  3. Log operation                                               │
│  4. Return JSON response                                        │
└─────────────────────────────────────────────────────────────────┘
             ↓
┌─────────────────────────────────────────────────────────────────┐
│      Tour Model (app/Models/Tour.php)                           │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  Fillable: name, destination, country, price, duration,        │
│            category, rating, description, image,                │
│            itinerary, included, excluded                        │
│                                                                   │
│  Casts:                                                          │
│    itinerary → array                                            │
│    included → array                                             │
│    excluded → array                                             │
│    price → decimal:2                                            │
│    rating → decimal:1                                           │
└─────────────────────────────────────────────────────────────────┘
             ↓
┌─────────────────────────────────────────────────────────────────┐
│         MySQL Database                                          │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  Table: tours                                                    │
│  ├── id (Primary Key)                                           │
│  ├── name (varchar)                                             │
│  ├── destination (varchar)                                      │
│  ├── country (varchar)                                          │
│  ├── price (decimal)                                            │
│  ├── duration (varchar)                                         │
│  ├── category (varchar)                                         │
│  ├── rating (decimal)                                           │
│  ├── description (text)                                         │
│  ├── image (varchar)                                            │
│  ├── itinerary (json)                                           │
│  ├── included (json)                                            │
│  ├── excluded (json)                                            │
│  ├── created_at (timestamp)                                     │
│  └── updated_at (timestamp)                                     │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
```

## Data Flow - Create Tour

```
User clicks "Add New Tour"
    ↓
Modal opens with empty form
    ↓
User fills in:
  • Tour Name: "Swiss Alps"
  • Destination: "Interlaken"
  • Country: "Switzerland"
  • Price: 1500
  • Duration: "7 Days"
  • Category: "Adventure"
    ↓
User clicks "Create Tour"
    ↓
Form validation (required fields check)
    ↓
handleSaveTour() called
    ↓
createTour(formData) called from tourService
    ↓
fetch(POST /api/tours) with JSON body
    ↓
TourController@store receives request
    ↓
Validates input (all fields required)
    ↓
Tour::create($validated)
    ↓
MySQL: INSERT INTO tours (...)
    ↓
Returns 201 Created with tour object
    ↓
Frontend receives {success: true, tour: {...}}
    ↓
Toast: "Tour created successfully"
    ↓
Modal closes
    ↓
loadTours() refreshes the list
    ↓
User sees new tour in table ✅
```

## Data Flow - Edit Tour

```
User sees tour in table
    ↓
Clicks "Edit" button
    ↓
handleOpenModal(tour) called
    ↓
Modal opens with form
    ↓
Form pre-filled with tour data:
  - name: "Swiss Alps"
  - price: 1500
  - etc...
    ↓
User modifies field:
  - price: 1500 → 1800
    ↓
Clicks "Update Tour"
    ↓
updateTour(id, {price: 1800}) called
    ↓
fetch(PUT /api/tours/1) with JSON body
    ↓
TourController@update receives request
    ↓
Validates input (nullable fields)
    ↓
Tour::find(1)->update({price: 1800})
    ↓
MySQL: UPDATE tours SET price=1800 WHERE id=1
    ↓
Returns 200 OK with updated tour object
    ↓
Frontend receives {success: true, tour: {...}}
    ↓
Toast: "Tour updated successfully"
    ↓
Modal closes
    ↓
loadTours() refreshes
    ↓
User sees updated price in table ✅
```

## Data Flow - Delete Tour

```
User sees tour in table
    ↓
Clicks "Delete" button
    ↓
Browser confirm(): "Are you sure?"
    ↓
User clicks "OK"
    ↓
handleDeleteTour(1) called
    ↓
deleteTour(1) called from tourService
    ↓
fetch(DELETE /api/tours/1)
    ↓
TourController@destroy receives request
    ↓
Tour::find(1)
    ↓
$tour->delete()
    ↓
MySQL: DELETE FROM tours WHERE id=1
    ↓
Returns 200 OK with success message
    ↓
Frontend receives {success: true}
    ↓
Toast: "Tour deleted successfully"
    ↓
loadTours() refreshes
    ↓
User sees tour removed from table ✅
```

## Component State Management

```
AdminDashboard Component State:

┌─────────────────────────────────────────┐
│  databaseTours: TourData[]              │ ← Tours fetched from DB
│  isLoading: boolean                     │ ← Loading indicator
│  showModal: boolean                     │ ← Modal visibility
│  editingTour: TourData | null           │ ← Current edit target
│  formData: Partial<TourData>            │ ← Form input values
│  selectedRate: string | null            │ ← Currency selection
└─────────────────────────────────────────┘

State Updates Flow:

loadTours()
  ↓
setDatabaseTours(tours)
  ↓
Table re-renders with new data

handleOpenModal(tour)
  ↓
setEditingTour(tour)
  ↓
setFormData(tour)
  ↓
setShowModal(true)
  ↓
Modal displays with data

setFormData({...formData, name: 'New Name'})
  ↓
Input updates in real-time
  ↓
User sees their typing immediately

handleSaveTour()
  ↓
setIsLoading(true)
  ↓
API call happens
  ↓
setIsLoading(false)
  ↓
Buttons enabled again
```

## Error Handling Flow

```
Try to create tour with missing name
    ↓
Form validation catches it
    ↓
Button disabled until field filled
    ↓
---
Try to create tour successfully
    ↓
handleSaveTour() called
    ↓
createTour(formData) called
    ↓
Fetch request sent to server
    ↓
│
├─→ Server error (500)
│   ├─ TourController catches exception
│   ├─ Logs error with stack trace
│   ├─ Returns {success: false, error: "..."}
│   ├─ Frontend receives error
│   └─ Toast: "An error occurred..."
│
├─→ Validation error (422)
│   ├─ TourController validates input
│   ├─ Returns {success: false, errors: {...}}
│   ├─ Frontend displays in toast
│   └─ Form remains open for correction
│
└─→ Success (201/200)
    ├─ Modal closes
    ├─ Toast: "Success!"
    ├─ loadTours() refreshes list
    └─ User sees updated data
```

## API Request/Response Examples

### Create Tour Request
```http
POST /api/tours HTTP/1.1
Host: localhost:8000
Content-Type: application/json

{
  "name": "Swiss Alps Adventure",
  "destination": "Interlaken",
  "country": "Switzerland",
  "price": 1500,
  "duration": "7 Days",
  "category": "adventure",
  "rating": 4.5,
  "description": "Explore breathtaking mountains",
  "image": "https://...",
  "itinerary": [],
  "included": [],
  "excluded": []
}
```

### Success Response (201)
```json
{
  "success": true,
  "tour": {
    "id": 1,
    "name": "Swiss Alps Adventure",
    "destination": "Interlaken",
    "country": "Switzerland",
    "price": "1500.00",
    "duration": "7 Days",
    "category": "adventure",
    "rating": "4.5",
    "description": "Explore breathtaking mountains",
    "image": "https://...",
    "itinerary": [],
    "included": [],
    "excluded": [],
    "created_at": "2025-12-28T12:30:00Z",
    "updated_at": "2025-12-28T12:30:00Z"
  }
}
```

### Validation Error Response (422)
```json
{
  "success": false,
  "errors": {
    "name": ["The name field is required."],
    "price": ["The price must be a number."]
  }
}
```

---

**Diagram Version**: 1.0
**Created**: December 28, 2025

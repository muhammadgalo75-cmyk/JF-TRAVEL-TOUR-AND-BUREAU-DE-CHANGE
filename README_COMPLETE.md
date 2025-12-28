# JF Travel & Bureau de Change Platform

A full-stack travel booking and currency exchange platform built with modern web technologies.

## 🌟 Features

- **🔐 Firebase Authentication** - Secure email/password and OAuth login
- **👨‍💼 Admin Dashboard** - Manage tours, bookings, and currency rates
- **✈️ Tour Management** - Create, edit, and manage travel tours with real-time updates
- **📅 Booking System** - Complete booking workflow with status management
- **💱 Currency Exchange** - Live currency converter with 10+ currencies
- **💾 Database Integration** - Real-time synchronization with MySQL database
- **📱 Responsive Design** - Works perfectly on desktop, tablet, and mobile
- **🎨 Modern UI** - Beautiful gradient designs with Tailwind CSS

## 📋 Tech Stack

### Frontend
- **React 18** - Modern UI library
- **TypeScript** - Type-safe JavaScript
- **Vite** - Next-gen build tool
- **Tailwind CSS** - Utility-first CSS framework
- **Shadcn/UI** - High-quality component library
- **Firebase SDK** - Authentication & real-time features
- **Sonner** - Toast notifications
- **Lucide Icons** - Beautiful icon library

### Backend
- **Laravel 11** - Modern PHP framework
- **MySQL** - Relational database
- **Eloquent ORM** - Database abstraction layer
- **REST API** - RESTful JSON endpoints
- **CORS Middleware** - Cross-origin resource sharing

## 🚀 Quick Start

### Prerequisites
- Node.js 16+ and npm
- PHP 8.1+
- MySQL 8.0+
- Git

### Installation

#### 1. Clone the Repository
```bash
git clone https://github.com/YOUR_USERNAME/JF-TRAVEL-TOUR-AND-BUERU-DE-CHANGE.git
cd JF-TRAVEL-TOUR-AND-BUERU-DE-CHANGE
```

#### 2. Frontend Setup
```bash
cd frontend
npm install
cp .env.example .env
npm run dev
```
Frontend will run on `http://localhost:5174`

#### 3. Backend Setup
```bash
cd ../backend/jf-api
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve --host=localhost --port=8000
```
Backend will run on `http://localhost:8000`

#### 4. Access the Application
- **Frontend**: http://localhost:5174
- **API**: http://localhost:8000/api

## 📚 Usage

### For Users
1. Visit the website
2. Sign up or login with Firebase
3. Browse tours and book your trip
4. Use the currency converter for exchange rates

### For Admins
1. Login with admin credentials
2. Access the admin dashboard
3. **Manage Tours**: Add, edit, delete tour packages
4. **View Bookings**: Monitor all bookings and update status (pending → confirmed → completed)
5. **Exchange Rates**: Create and manage currency exchange rates for the converter

## 📂 Project Structure

```
JF-TRAVEL-TOUR-AND-BUERU-DE-CHANGE/
├── frontend/                    # React/Vite application
│   ├── src/
│   │   ├── app/
│   │   │   ├── components/     # Reusable UI components
│   │   │   ├── pages/          # Page components
│   │   │   └── utils/          # API services
│   │   └── config/             # Configuration files
│   └── package.json
├── backend/jf-api/              # Laravel application
│   ├── app/
│   │   ├── Http/
│   │   │   └── Controllers/    # API controllers
│   │   ├── Models/             # Database models
│   │   └── Providers/          # Service providers
│   ├── database/
│   │   ├── migrations/         # Database migrations
│   │   └── seeders/            # Sample data
│   ├── routes/
│   │   └── api.php             # API routes
│   └── .env                    # Environment variables
├── DEPLOYMENT_GUIDE.md          # Detailed deployment instructions
├── QUICK_DEPLOYMENT.md          # Quick deployment checklist
└── README.md                    # This file
```

## 🔑 Key Endpoints

### Authentication
- `POST /api/users/check-admin` - Check if user is admin

### Tours
- `GET /api/tours` - Get all tours
- `POST /api/tours` - Create new tour
- `PUT /api/tours/{id}` - Update tour
- `DELETE /api/tours/{id}` - Delete tour

### Bookings
- `GET /api/bookings` - Get all bookings
- `POST /api/bookings` - Create booking
- `PUT /api/bookings/{id}/status` - Update booking status
- `DELETE /api/bookings/{id}` - Delete booking

### Exchange Rates
- `GET /api/exchange-rates` - Get all rates
- `POST /api/exchange-rates` - Create rate
- `PUT /api/exchange-rates/{id}` - Update rate
- `DELETE /api/exchange-rates/{id}` - Delete rate

## 🌐 Deployment

### Deploy to Vercel (Frontend) + Railway (Backend)

For detailed deployment instructions, see:
- **Complete Guide**: [DEPLOYMENT_GUIDE.md](./DEPLOYMENT_GUIDE.md)
- **Quick Steps**: [QUICK_DEPLOYMENT.md](./QUICK_DEPLOYMENT.md)

### Quick Deployment Steps:
1. Push code to GitHub
2. Deploy frontend to Vercel
3. Deploy backend to Railway
4. Configure environment variables
5. Run database migrations
6. Test and verify

After deployment, your app will be accessible at:
- **Frontend**: `https://YOUR_PROJECT.vercel.app`
- **API**: `https://YOUR_SERVICE.up.railway.app`

## 🧪 Testing

### Test Tour Management
1. Login as admin
2. Navigate to "Manage Tours" tab
3. Add new tour with details
4. Edit and verify updates
5. Delete tour

### Test Currency Converter
1. Go to Currency Exchange page
2. Select different currencies
3. Enter amount
4. Verify conversion (1 USD = 1540 NGN)

### Test Bookings
1. Create a booking
2. View in admin dashboard
3. Change status: pending → confirmed → completed
4. Verify status update

## 🐛 Troubleshooting

### Frontend issues
- Clear browser cache: `Ctrl+Shift+Delete`
- Rebuild frontend: `npm run build`
- Check console: `F12 → Console`

### Backend issues
- Check logs: `tail -f storage/logs/laravel.log`
- Verify database connection: Check `.env` file
- Run migrations: `php artisan migrate:fresh`

### API connection
- Verify `VITE_API_URL` in `.env`
- Ensure backend is running
- Check CORS settings in `bootstrap/app.php`

## 📖 Database Schema

### Users Table
- id, name, email, password, role (admin/user), timestamps

### Tours Table
- id, name, destination, country, description, price, image, timestamps

### TourBookings Table
- id, user_id, tour_id, status (pending/confirmed/completed/cancelled), timestamps

### CurrencyRates Table
- id, code (USD/NGN/EUR), name, rate, buy_rate, sell_rate, timestamps

## 🔐 Security

- ✅ Firebase authentication for secure login
- ✅ Role-based access control (admin/user)
- ✅ CORS enabled for frontend domain
- ✅ MySQL parameterized queries prevent SQL injection
- ✅ Input validation on all API endpoints
- ✅ Environment variables for sensitive data

## 📝 Environment Variables

### Frontend (.env)
```
VITE_API_URL=http://localhost:8000/api
VITE_FIREBASE_API_KEY=your_firebase_key
VITE_FIREBASE_AUTH_DOMAIN=your_firebase_domain
VITE_FIREBASE_PROJECT_ID=your_firebase_project
VITE_FIREBASE_STORAGE_BUCKET=your_firebase_bucket
VITE_FIREBASE_MESSAGING_SENDER_ID=your_sender_id
VITE_FIREBASE_APP_ID=your_app_id
```

### Backend (.env)
```
APP_NAME=JF-Travel-API
APP_ENV=production
APP_KEY=your_app_key
APP_DEBUG=false
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jf-api
DB_USERNAME=root
DB_PASSWORD=
```

## 📞 Support

For issues or questions:
1. Check the troubleshooting section
2. Review deployment guides
3. Check browser console (F12)
4. Review backend logs in `storage/logs/`

## 📄 License

This project is private and confidential.

## ✨ Credits

Built with ❤️ using modern web technologies.

---

**Ready to Deploy?** 🚀

Start with [QUICK_DEPLOYMENT.md](./QUICK_DEPLOYMENT.md) for a quick overview, or read [DEPLOYMENT_GUIDE.md](./DEPLOYMENT_GUIDE.md) for detailed instructions.

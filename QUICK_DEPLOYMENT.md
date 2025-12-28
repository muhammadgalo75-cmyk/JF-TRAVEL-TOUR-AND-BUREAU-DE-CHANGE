# Quick Deployment Checklist

## What You Need to Do (Copy & Paste Ready)

### 1️⃣ ENSURE CODE IS ON GITHUB

```bash
# Run this in your project folder
cd C:\Users\USER\Desktop\JF-TRAVEL-TOUR-AND-BUERU-DE-CHANGE
git add .
git commit -m "Final deployment prep"
git push origin main
```

### 2️⃣ DEPLOY FRONTEND (Vercel)

1. Go to: https://vercel.com/new
2. Select your GitHub repository
3. Vercel auto-detects Vite
4. Add environment variables:
   - `VITE_API_URL` = `http://localhost:8000/api` (temporary, update later)
   - Copy all `VITE_FIREBASE_*` variables from `.env` file
5. Click **Deploy** ✅
6. Your frontend URL: `https://YOUR_NAME.vercel.app`

### 3️⃣ DEPLOY BACKEND (Railway)

1. Go to: https://railway.app/new
2. Select "Deploy from GitHub repo"
3. Choose your repository
4. Select root directory: `backend/jf-api`
5. Add MySQL database (click + Plugin → MySQL)
6. Set environment variables:
   ```
   APP_NAME=JF-Travel-API
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:ghq4CW0mi2osMdZvL0jkiuZsDVH51hHXX/iHD5XTUJM=
   DB_CONNECTION=mysql
   SESSION_DRIVER=database
   ```
7. Click **Deploy** ✅
8. Your backend URL: `https://SOMETHING.up.railway.app`

### 4️⃣ RUN DATABASE MIGRATIONS

In Railway dashboard:
- Open Terminal for your backend service
- Run: `php artisan migrate --force`

### 5️⃣ UPDATE FRONTEND WITH BACKEND URL

1. Go to Vercel dashboard → Settings → Environment Variables
2. Update `VITE_API_URL` = `https://YOUR_RAILWAY_SERVICE.up.railway.app/api`
3. Go to Deployments → Latest → Click **Redeploy**

### 6️⃣ TEST YOUR DEPLOYMENT

Visit your frontend URL and test:
- ✅ Login/Register
- ✅ Admin dashboard
- ✅ Tour management
- ✅ Booking management
- ✅ Exchange rate management
- ✅ Currency converter (1 USD = 1540 NGN)

---

## YOUR SHAREABLE LINKS

After deployment:
- **Live App:** `https://YOUR_VERCEL_PROJECT.vercel.app`
- **Code on GitHub:** `https://github.com/YOUR_USERNAME/JF-TRAVEL-TOUR-AND-BUERU-DE-CHANGE`
- **API:** `https://YOUR_RAILWAY_SERVICE.up.railway.app/api`

Share these with anyone! 🚀

---

## NEED HELP?

See `DEPLOYMENT_GUIDE.md` for detailed troubleshooting and setup instructions.

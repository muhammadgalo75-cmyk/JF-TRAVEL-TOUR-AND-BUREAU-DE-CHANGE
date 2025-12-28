# DEPLOYMENT GUIDE: JF Travel & Bureau de Change

## Complete Step-by-Step Deployment Instructions

This guide will help you deploy your application to make it accessible via a shareable link.

---

## PHASE 1: GITHUB SETUP

### Step 1.1: Initialize GitHub Repository

If you haven't already created a GitHub repository:

```bash
cd C:\Users\USER\Desktop\JF-TRAVEL-TOUR-AND-BUERU-DE-CHANGE
git init
git add .
git commit -m "Initial commit: Complete travel and currency exchange platform"
git branch -M main
git remote add origin https://github.com/YOUR_USERNAME/JF-TRAVEL-TOUR-AND-BUERU-DE-CHANGE.git
git push -u origin main
```

### Step 1.2: If Repository Already Exists

```bash
cd C:\Users\USER\Desktop\JF-TRAVEL-TOUR-AND-BUERU-DE-CHANGE
git add .
git commit -m "Prepare for deployment: Environment variables and configs"
git push origin main
```

---

## PHASE 2: FRONTEND DEPLOYMENT (React/Vite + Vercel)

### Step 2.1: Create Vercel Account
1. Go to https://vercel.com
2. Sign up with your GitHub account
3. Click "New Project"

### Step 2.2: Deploy Frontend

1. **Connect GitHub Repository**
   - Select "JF-TRAVEL-TOUR-AND-BUERU-DE-CHANGE" from your repositories
   - Vercel will auto-detect Vite configuration

2. **Configure Environment Variables**
   - In Vercel dashboard, go to: Settings → Environment Variables
   - Add the following variables:

   ```
   VITE_API_URL = https://your-railway-backend-url.up.railway.app/api
   VITE_FIREBASE_API_KEY = AIzaSyAbfjAxZ3NTJ2VxfWgAhjUlRDJi5-eWfoY
   VITE_FIREBASE_AUTH_DOMAIN = jf-travel.firebaseapp.com
   VITE_FIREBASE_PROJECT_ID = jf-travel
   VITE_FIREBASE_STORAGE_BUCKET = jf-travel.firebasestorage.app
   VITE_FIREBASE_MESSAGING_SENDER_ID = 205354834016
   VITE_FIREBASE_APP_ID = 1:205354834016:web:94d9f82b9d7c57aeca8b05
   ```

3. **Deploy**
   - Click "Deploy"
   - Wait for build to complete (usually 2-3 minutes)
   - Your frontend will be available at: `https://YOUR_PROJECT_NAME.vercel.app`

**Note:** You'll update `VITE_API_URL` after deploying the backend.

---

## PHASE 3: BACKEND DEPLOYMENT (Laravel + Railway)

### Step 3.1: Create Railway Account
1. Go to https://railway.app
2. Sign up with your GitHub account

### Step 3.2: Deploy Backend

1. **Create New Project**
   - Click "New Project"
   - Select "Deploy from GitHub repo"
   - Choose: JF-TRAVEL-TOUR-AND-BUERU-DE-CHANGE

2. **Configure Build Settings**
   - Railway might auto-detect PHP
   - If not, create `railway.json` in `/backend/jf-api/` with:

   ```json
   {
     "build": {
       "builder": "nixpacks"
     }
   }
   ```

3. **Set Environment Variables in Railway**
   - In Railway dashboard → Variables
   - Add these variables:

   ```
   APP_NAME=JF-Travel-API
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:ghq4CW0mi2osMdZvL0jkiuZsDVH51hHXX/iHD5XTUJM=
   
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=jf_api_prod
   DB_USERNAME=root
   DB_PASSWORD=your_secure_password
   
   SESSION_DRIVER=database
   QUEUE_CONNECTION=sync
   CACHE_DRIVER=file
   ```

4. **Deploy Database (MySQL)**
   - In Railway, add MySQL plugin:
     - New → Add database plugin → MySQL
   - Railway will automatically configure `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`

5. **Configure Root Directory**
   - In Service Settings → Root Directory
   - Set to: `backend/jf-api`

6. **Deploy**
   - Click "Deploy"
   - Wait for build (5-10 minutes for first deploy)
   - Your API will be available at: `https://YOUR_SERVICE_NAME.up.railway.app`

### Step 3.3: Run Database Migrations

After deployment, run Laravel migrations on Railway:

1. In Railway dashboard, open Terminal
2. Run these commands:

```bash
php artisan migrate --force
php artisan db:seed --force
```

Or use Railway CLI:

```bash
railway run php artisan migrate --force
railway run php artisan db:seed --force
```

---

## PHASE 4: UPDATE FRONTEND WITH BACKEND URL

### Step 4.1: Update API URL in Vercel

After you have your Railway backend URL (e.g., `https://jf-api-prod.up.railway.app`):

1. In Vercel Dashboard → Settings → Environment Variables
2. Update `VITE_API_URL`:
   ```
   VITE_API_URL = https://jf-api-prod.up.railway.app/api
   ```

3. Trigger redeploy:
   - Go to Deployments
   - Click on latest deployment
   - Click "Redeploy"

---

## PHASE 5: VERIFY DEPLOYMENT

### Test Frontend
- Visit your Vercel URL: `https://your-project.vercel.app`
- Try to login/register
- Check if all pages load

### Test Backend API
- Open your browser console
- Check Network tab during API calls
- Verify requests go to: `https://YOUR_RAILWAY_SERVICE.up.railway.app/api`

### Test Specific Features

1. **Authentication**
   - Sign up with email/password
   - Try OAuth (Google/Apple)
   - Verify role-based access

2. **Admin Dashboard**
   - Login as admin
   - Test Tour management (add/edit/delete)
   - Test Booking management
   - Test Exchange rate management
   - Verify stats show real database data

3. **Currency Exchange**
   - Test converter with different currencies
   - Verify calculations (1 USD = 1540 NGN)
   - Check if rates from database display correctly

4. **Database**
   - In Railway dashboard, verify data is being saved
   - Check MySQL database directly if needed

---

## PHASE 6: ENABLE CORS ON BACKEND (If Needed)

If you get CORS errors, update `bootstrap/app.php`:

```php
// Add this to your middleware or routes:
->withMiddleware(function (Middleware $middleware) {
    $middleware->web([\App\Http\Middleware\HandleCors::class]);
})
```

Or check your API routes and ensure all endpoints accept the frontend domain.

---

## TROUBLESHOOTING

### Issue: API calls return 404

**Solution:**
- Verify `VITE_API_URL` is set correctly in Vercel
- Ensure Railway backend is running (check Railway dashboard)
- Verify migrations ran successfully
- Check Laravel logs: `railway run tail storage/logs/laravel.log`

### Issue: Database connection fails

**Solution:**
- Verify `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD` are set in Railway
- Ensure MySQL plugin is added to Railway project
- Run migrations: `railway run php artisan migrate --force`

### Issue: Firebase authentication fails in production

**Solution:**
- Verify Firebase domain settings allow your Vercel domain
- Check Firebase Console → Authentication → Settings
- Ensure all `VITE_FIREBASE_*` variables match your Firebase project

### Issue: Static assets (CSS, images) not loading

**Solution:**
- Vercel should auto-configure this
- Check in Network tab what assets are failing
- Ensure `vite.config.ts` is correct
- Run: `npm run build` locally to verify

### Issue: Environment variables not loading

**Solution (Vercel):**
- Clear Vercel build cache: Settings → Advanced → Purge Cache → Redeploy
- Re-add environment variables
- Wait 5 minutes for changes to propagate

**Solution (Railway):**
- Restart service: Dashboard → Service → More → Restart
- Verify variables are in correct environment

---

## SHARING YOUR PROJECT

### Share Your Live Links

Once deployed, you have these shareable links:

- **Frontend:** `https://your-project.vercel.app`
- **Backend API:** `https://your-service.up.railway.app`
- **GitHub:** `https://github.com/YOUR_USERNAME/JF-TRAVEL-TOUR-AND-BUERU-DE-CHANGE`

You can now:
1. Share the frontend link with users
2. Share the GitHub link for code review
3. Share the backend API documentation

---

## NEXT STEPS

1. ✅ Push to GitHub
2. ✅ Deploy frontend to Vercel
3. ✅ Deploy backend to Railway
4. ✅ Configure database on Railway
5. ✅ Update API URL in Vercel
6. ✅ Test all functionality
7. ✅ Share your live links!

---

## PRODUCTION CHECKLIST

Before going live, verify:

- [ ] All environment variables are set
- [ ] Database migrations ran successfully
- [ ] Firebase authentication works
- [ ] API requests return correct data
- [ ] Admin dashboard shows real data
- [ ] Currency converter calculates correctly (1 USD = 1540 NGN)
- [ ] Booking status changes work
- [ ] Exchange rates can be managed
- [ ] No console errors on frontend
- [ ] No errors in backend logs

---

## SUPPORT

If you encounter issues:
1. Check Railway logs: `railway run tail storage/logs/laravel.log`
2. Check Vercel logs: Dashboard → Deployments → Select deployment
3. Check browser console: F12 → Console tab
4. Check Network tab: F12 → Network tab → See API requests

Good luck with your deployment! 🚀

# 🚀 ANR Constructions - cPanel Installation Guide

## Easy Installation (Just Like AMT!)

This guide will help you install the ANR Constructions website on cPanel in **5 simple steps** - no SSH or terminal needed!

---

## 📋 Method 1: Web Installer (Recommended - Easiest!)

### Step 1: Create Database in cPanel
1. Login to **cPanel** → **MySQL Databases**
2. Create a new database (e.g., `youruser_anr_website`)
3. Create a new MySQL user (e.g., `youruser_anr_admin`) with a strong password
4. Add the user to the database with **ALL PRIVILEGES**

### Step 2: Upload Files
1. Go to **cPanel** → **File Manager**
2. Navigate to `public_html/` (or your subdomain folder)
3. Upload the **ZIP file** of the project
4. **Extract** the ZIP file in place
5. Make sure all files are directly inside `public_html/` (not in a subfolder)

### Step 3: Set File Permissions
In cPanel File Manager, right-click these folders and set permissions:
- `storage/` → **755** (or 775)
- `storage/framework/` → **755**
- `storage/framework/cache/` → **755**
- `storage/framework/sessions/` → **755**
- `storage/framework/views/` → **755**
- `storage/logs/` → **755**
- `storage/app/` → **755**
- `bootstrap/cache/` → **755**

### Step 4: Run Web Installer
1. Open your browser and go to:
   ```
   https://yourdomain.com/install/
   ```
2. Follow the on-screen wizard:
   - **Step 1:** Welcome & Requirements Check
   - **Step 2:** Enter Database Credentials (from Step 1)
   - **Step 3:** Import Database Tables & Sample Data
   - **Step 4:** Set Admin Name, Email & Password
   - **Step 5:** Review & Install
   - **Step 6:** Done! 🎉

### Step 5: Delete Installer (Important!)
After installation, **delete the `install/` folder** for security:
- In File Manager, right-click `install/` → Delete

### ✅ You're Done!
- **Website:** https://yourdomain.com
- **Admin Panel:** https://yourdomain.com/admin/login
- Login with the email & password you set in the installer

---

## 📋 Method 2: Manual Installation (phpMyAdmin)

If you prefer doing it manually (like AMT project):

### Step 1: Create Database
Same as Method 1, Step 1.

### Step 2: Import SQL File
1. Go to **cPanel** → **phpMyAdmin**
2. Select your database
3. Click **Import** tab
4. Choose file: `install/anr_database.sql`
5. Click **Go**

### Step 3: Configure .env File
1. In File Manager, rename `.env.production` to `.env`
2. Edit `.env` and update these lines:
   ```
   DB_DATABASE=youruser_anr_website
   DB_USERNAME=youruser_anr_admin
   DB_PASSWORD=your_actual_password
   APP_URL=https://yourdomain.com
   ```

### Step 4: Generate App Key
Use cPanel → **Terminal** (or ask your host):
```bash
cd /home/youruser/public_html
php artisan key:generate
```

Or manually generate a key:
1. Visit: https://yourdomain.com/install/ (it handles key generation)

### Step 5: Create Storage Link
In cPanel Terminal:
```bash
cd /home/youruser/public_html
php artisan storage:link
```

Or manually: In File Manager, create a symlink from `public/storage` → `storage/app/public`

---

## 📋 Method 3: For XAMPP / Local Development

### Quick Start
```bash
cd /Applications/XAMPP/xamppfiles/htdocs/anr-website

# 1. Install dependencies
composer install
npm install

# 2. Configure environment
cp .env.example .env
php artisan key:generate

# 3. Edit .env with your database credentials
# DB_DATABASE=anr_constructions
# DB_USERNAME=root
# DB_PASSWORD=

# 4. Run migrations & seed data
php artisan migrate --seed

# 5. Create storage link
php artisan storage:link

# 6. Build assets
npm run build

# 7. Start server
php artisan serve
```

### Access
- **Frontend:** http://localhost:8000
- **Admin Panel:** http://localhost:8000/admin/login
- **Default Admin:** admin@anrconstructions.com / admin@123

---

## 🔐 Default Admin Credentials
- **Email:** admin@anrconstructions.com
- **Password:** admin@123

⚠️ **Change these immediately after first login!**

---

## 📁 Folder Structure (Same Pattern as AMT)

```
public_html/                    ← Upload everything here
├── index.php                   ← Main entry point (like AMT's index.php)
├── .htaccess                   ← URL routing (like AMT's .htaccess)
├── .env                        ← Database config (like AMT's database.php)
├── install/                    ← Web installer (delete after install)
│   ├── index.php               ← Installation wizard
│   └── anr_database.sql        ← Complete SQL file
├── app/                        ← Application code
│   ├── Http/Controllers/       ← Controllers
│   └── Models/                 ← Database models
├── resources/views/            ← Templates (like AMT's views)
├── public/                     ← Public assets
│   ├── images/                 ← Logos, default images
│   └── build/                  ← Compiled CSS/JS
├── storage/                    ← Uploads & cache
│   └── app/public/             ← Uploaded images
├── database/                   ← Migrations & seeders
├── routes/                     ← URL routing
├── config/                     ← App configuration
└── vendor/                     ← Composer packages
```

---

## 🗄️ Database Tables Created

| Table | Purpose |
|-------|---------|
| `users` | Admin users |
| `projects` | Real estate projects |
| `floor_plans` | Floor plans per project |
| `amenities` | Project amenities |
| `galleries` | Image gallery |
| `home_sliders` | Homepage hero sliders |
| `testimonials` | Customer testimonials |
| `enquiries` | Contact/enquiry submissions |
| `site_settings` | Dynamic site configuration |
| `sessions` | User sessions |
| `cache` | Cache storage |
| `jobs` | Background job queue |

---

## ❓ Troubleshooting

### "500 Internal Server Error"
- Check `.env` file exists and has correct database credentials
- Ensure `storage/` and `bootstrap/cache/` are writable (755)
- Check `APP_KEY` is set in `.env`

### "Blank White Page"
- Set `APP_DEBUG=true` in `.env` to see errors
- Check PHP version is 8.1 or higher in cPanel → PHP Version

### "Database Connection Error"
- Double-check database name, username, password in `.env`
- In cPanel, ensure the MySQL user is added to the database
- Check database host (usually `localhost`)

### "Images Not Loading"
- Run `php artisan storage:link` or create symlink manually
- Check `storage/app/public/` directory exists and is writable
- Check `public/storage` symlink exists

### "CSS/JS Not Loading"
- Ensure `public/build/` folder exists with `manifest.json`
- Run `npm run build` if developing locally

### "Storage Link Not Working on cPanel"
Create a file `public/storage` as a symlink manually:
```bash
ln -s ../storage/app/public public/storage
```
Or use `.htaccess` rewrite (already configured).

### "Permission Denied"
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

---

## 🔄 Post-Installation Checklist

- [ ] Delete the `install/` folder
- [ ] Change admin password from default
- [ ] Update site name, phone, email in Admin → Site Settings
- [ ] Upload your logo to `public/images/`
- [ ] Replace default slider images
- [ ] Add your actual projects
- [ ] Configure SMTP email in Admin → SMTP Settings
- [ ] Set `APP_DEBUG=false` in `.env` for production
- [ ] Test the contact/enquiry form

---

## 📞 Need Help?

If you face any issues:
1. Check the **Troubleshooting** section above
2. Set `APP_DEBUG=true` in `.env` to see detailed errors
3. Check `storage/logs/laravel.log` for error details

# 🚀 cPanel Deployment Guide - ANR Constructions Website

## Prerequisites
- cPanel hosting with **PHP 8.3+** and **MySQL**
- SSH access (optional but recommended)
- phpMyAdmin access

---

## STEP 1: Prepare the Database on cPanel

1. Login to your **cPanel**
2. Go to **MySQL Databases**
3. **Create a new database**: e.g., `anr_constructions`
4. **Create a MySQL user**: e.g., `anr_user` with a strong password
5. **Add user to database**: Give **ALL PRIVILEGES**
6. **Note down** these values:
   - Database name: `your_cpaneluser_anr_constructions`
   - Username: `your_cpaneluser_anr_user`
   - Password: (the one you set)
   - Host: `localhost`

---

## STEP 2: Upload Files to cPanel

### Option A: Using File Manager (Easier)

1. **Zip** the entire project (excluding `node_modules` and `.git`):
   - On your Mac, run:
     ```bash
     cd /Users/sunil/Desktop/anrcontrustions
     zip -r anr-website.zip anr-website/ \
       -x "anr-website/node_modules/*" \
       -x "anr-website/.git/*"
     ```

2. Go to **cPanel → File Manager**
3. Navigate to your domain's root folder:
   - Usually: `public_html/` (for main domain)
   - Or: `yourdomain.com/` (for addon domain)
4. **Upload** the `anr-website.zip`
5. **Extract** the zip file

### ⚠️ IMPORTANT: Folder Structure on Server

Your Laravel project files should be arranged like this on the server:

```
/home/your_cpanel_username/
├── anr-website/              ← Put ALL Laravel files here (outside public_html)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   ├── .env
│   ├── artisan
│   └── ...
├── public_html/              ← This is the web root
│   ├── .htaccess             ← (modified - see below)
│   ├── index.php             ← (modified - see below)
│   ├── build/                ← CSS/JS assets
│   ├── css/
│   ├── images/
│   ├── js/
│   ├── favicon.ico
│   ├── robots.txt
│   └── storage → ../anr-website/storage/app/public  (symlink)
```

### Option B: Using SSH (Recommended)

```bash
# SSH into your server
ssh your_cpanel_user@your_server_ip

# Navigate to home directory
cd ~

# Upload via SCP from your Mac (run on your Mac):
scp -r /Users/sunil/Desktop/anrcontrustions/anr-website/ \
  --exclude=node_modules \
  --exclude=.git \
  your_cpanel_user@your_server_ip:~/anr-website
```

---

## STEP 3: Move Public Files to public_html

```bash
# SSH into your server
cd ~

# Copy public assets to public_html
cp -r anr-website/public/* public_html/
cp anr-website/public/.htaccess public_html/
```

---

## STEP 4: Edit `public_html/index.php`

Replace the contents of `public_html/index.php` with:

```php
<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if we are running in the console
if (! defined('LARAVEL_BASE_PATH')) {
    define('LARAVEL_BASE_PATH', dirname(__DIR__).'/anr-website');
}

// 🔧 Point to the Laravel app directory OUTSIDE public_html
require LARAVEL_BASE_PATH.'/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once LARAVEL_BASE_PATH.'/bootstrap/app.php';

// Handle the request
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
```

**Key change:** `dirname(__DIR__).'/anr-website'` points to your Laravel app folder outside `public_html`.

---

## STEP 5: Edit `.env` File for Production

SSH into server and edit `~/anr-website/.env`:

```env
APP_NAME="ARN Constructions"
APP_ENV=production
APP_KEY=base64:B+ETSuJmB3iwxsOGS1o7L6k+0H7cx+h63hCjT5wgkpI=
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_cpaneluser_anr_constructions
DB_USERNAME=your_cpaneluser_anr_user
DB_PASSWORD=your_strong_password

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.yourdomain.com
MAIL_PORT=465
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=your_email_password
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## STEP 6: Set Folder Permissions (via SSH)

```bash
cd ~/anr-website

chmod -R 755 .
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# If storage link doesn't exist:
cd ~/public_html
rm -f storage
ln -s ../anr-website/storage/app/public storage
```

### If you DON'T have SSH access:
Use **cPanel → Terminal** (if available) or set permissions via **File Manager**:
- Right-click `storage/` → Change Permissions → `755`
- Right-click `bootstrap/cache/` → Change Permissions → `755`
- Check "Recurse into subdirectories"

---

## STEP 7: Run Database Migrations (via SSH or cPanel Terminal)

```bash
cd ~/anr-website

# Run migrations
php artisan migrate --force

# If you have seeders:
php artisan db:seed --force
```

### If you DON'T have SSH:
1. Export your local database using phpMyAdmin or:
   ```bash
   # On your Mac:
   mysqldump -u root anr_constructions > anr_constructions.sql
   ```
2. Go to **cPanel → phpMyAdmin**
3. Select the database you created
4. **Import** the `anr_constructions.sql` file

---

## STEP 8: Optimize for Production (via SSH)

```bash
cd ~/anr-website

# Clear and cache configs
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

---

## STEP 9: Setup Storage Link

```bash
cd ~/anr-website
php artisan storage:link
```

Or manually:
```bash
cd ~/public_html
rm -f storage
ln -s ../anr-website/storage/app/public storage
```

---

## STEP 10: Verify `.htaccess` in public_html

Make sure `public_html/.htaccess` has:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Handle X-XSRF-Token Header
    RewriteCond %{HTTP:x-xsrf-token} .
    RewriteRule .* - [E=HTTP_X_XSRF_TOKEN:%{HTTP:X-XSRF-Token}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

---

## STEP 11: Set PHP Version in cPanel

1. Go to **cPanel → MultiPHP Manager** (or **Select PHP Version**)
2. Select your domain
3. Set PHP version to **8.3** or higher
4. Make sure these extensions are enabled:
   - `mysqli`, `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`
   - `xml`, `ctype`, `json`, `bcmath`, `fileinfo`
   - `curl`, `gd` or `imagick`

---

## STEP 12: SSL Certificate

1. Go to **cPanel → SSL/TLS Status** or **Let's Encrypt**
2. Issue a free SSL certificate for your domain
3. Enable **Force HTTPS** in cPanel

---

## 🔄 Quick Deployment Script

After the first setup, use this for future updates:

```bash
#!/bin/bash
# Save as deploy.sh and run via SSH

echo "🚀 Deploying ANR Constructions..."

cd ~/anr-website

# Pull latest code (if using Git)
# git pull origin main

# Or upload updated files via SCP/File Manager

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run migrations if any
php artisan migrate --force

# Re-cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Copy new public assets
cp -r public/build ~/public_html/
cp -r public/css ~/public_html/
cp -r public/js ~/public_html/
cp -r public/images ~/public_html/

echo "✅ Deployment complete!"
```

---

## 🛠️ Troubleshooting

| Issue | Solution |
|-------|----------|
| **500 Internal Server Error** | Check `storage/logs/laravel.log`. Usually permissions issue. Run `chmod -R 775 storage bootstrap/cache` |
| **Blank white page** | Check `APP_KEY` in `.env`. Run `php artisan key:generate` |
| **Database connection error** | Verify DB credentials in `.env`. Use full cPanel DB name like `cpaneluser_dbname` |
| **CSS/JS not loading** | Run `npm run build` locally, re-upload `public/build/` folder |
| **Images not showing** | Re-create storage symlink: `php artisan storage:link` |
| **404 on subpages** | Check `.htaccess` is in `public_html/` and `mod_rewrite` is enabled |
| **"The /storage directory must be writable"** | `chmod -R 775 storage` |

---

## 📁 Final Server Structure Summary

```
/home/cpanel_username/
│
├── anr-website/                    ← Laravel app (NOT publicly accessible)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/                    ← (chmod 775)
│   ├── vendor/
│   ├── .env                        ← Production config
│   ├── artisan
│   └── composer.json
│
└── public_html/                    ← Web root (publicly accessible)
    ├── .htaccess
    ├── index.php                   ← Modified to point to ../anr-website
    ├── build/                      ← Vite compiled assets
    │   ├── manifest.json
    │   └── assets/
    ├── css/
    ├── images/
    ├── js/
    ├── favicon.ico
    ├── robots.txt
    └── storage -> ../anr-website/storage/app/public/
```

---

## ✅ Post-Deployment Checklist

- [ ] Website loads at `https://yourdomain.com`
- [ ] All pages work (Home, About, Projects, Contact, etc.)
- [ ] Admin panel works
- [ ] Contact form sends emails
- [ ] Images display correctly
- [ ] SSL/HTTPS is active
- [ ] `APP_DEBUG=false` in `.env`
- [ ] Error pages show properly (not stack traces)

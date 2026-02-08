# Deploying to InfinityFree - Complete Guide

This guide will walk you through deploying your **Unique Hardware Inventory Management System** to InfinityFree, a free web hosting platform.

---

## 📋 Prerequisites

Before you begin, make sure you have:
- ✅ An InfinityFree account (sign up at [infinityfree.net](https://infinityfree.net))
- ✅ FTP client installed (recommended: [FileZilla](https://filezilla-project.org/))
- ✅ Your application tested locally
- ✅ Production files built (see Step 1 below)

---

## 🚀 Deployment Steps

### Step 1: Build Production Assets Locally

> [!IMPORTANT]
> InfinityFree doesn't allow you to run build commands on the server. You **must** build everything locally first.

1. Open a command prompt in your project directory
2. Run the production build script:
   ```powershell
   .\build_for_production.bat
   ```
3. Wait for the script to complete - this will:
   - Install production Composer dependencies
   - Build frontend assets with Vite
   - Optimize Laravel configuration
   - Prepare files for deployment

> [!NOTE]
> If you encounter any errors during the build, fix them before proceeding.

---

### Step 2: Export Your Database

If you have existing data in your SQLite database that you want to transfer:

1. Run the database export script:
   ```powershell
   .\export_database.bat
   ```
2. This creates a file called `database_export.sql`
3. You'll import this file later in Step 6

> [!TIP]
> If you're starting fresh without existing data, you can skip this step and just run migrations later.

---

### Step 3: Create InfinityFree Account & Get Database Credentials

1. **Sign up at InfinityFree**
   - Go to [infinityfree.net](https://infinityfree.net)
   - Create a free account
   - Create a new website/hosting account

2. **Access Control Panel (cPanel)**
   - Login to your control panel
   - You'll see your account details

3. **Create MySQL Database**
   - In cPanel, find **MySQL Databases**
   - Click **Create Database**
   - Name it something like `uniquehardware` or `inventory`
   - Note down the full database name (usually `epiz_XXXXXXXX_dbname`)

4. **Create Database User**
   - Still in MySQL Databases section
   - Create a new user with a strong password
   - Note down the username (usually `epiz_XXXXXXXX_username`)
   - **Important:** Save this password - you'll need it!

5. **Add User to Database**
   - Assign the user you just created to the database
   - Grant **ALL PRIVILEGES**

6. **Note Your Database Host**
   - Usually shown in cPanel
   - Typically looks like: `sql000.infinityfree.com` or similar
   - Port is usually `3306`

---

### Step 4: Prepare Your Environment File

1. In your project folder, find the file `infinityfree.env`
2. Make a copy and rename it to `.env`
3. Edit the `.env` file and update these values:

```env
APP_URL=https://your-subdomain.infinityfreeapp.com

DB_HOST=sql000.infinityfree.com          # Your actual DB host from Step 3
DB_DATABASE=epiz_12345678_uniquehardware  # Your actual DB name from Step 3
DB_USERNAME=epiz_12345678_dbuser          # Your actual DB username from Step 3
DB_PASSWORD=your_secure_password_here     # Your actual DB password from Step 3
```

> [!WARNING]
> Make sure to replace ALL the placeholder values with your actual InfinityFree credentials!

---

### Step 5: Upload Files via FTP

1. **Get FTP Credentials**
   - In your InfinityFree control panel, find **FTP Details**
   - Note: FTP hostname, username, and password

2. **Connect with FileZilla**
   - Open FileZilla
   - Enter your FTP credentials:
     - **Host:** ftp.yourdomain.infinityfreeapp.com
     - **Username:** Your FTP username
     - **Password:** Your FTP password
     - **Port:** 21
   - Click **Quickconnect**

3. **Upload Application Files**
   
   > [!CAUTION]
   > Pay close attention to where you upload files!

   **A. Upload Laravel root files to `/htdocs`** (NOT inside public_html):
   - `.env` (the one you configured in Step 4)
   - `artisan`
   - `composer.json`
   - **Folders:** `app`, `bootstrap`, `config`, `database`, `resources`, `routes`, `storage`, `vendor`
   
   **B. Upload public folder contents to `/htdocs/public_html`:**
   - All files from your local `public` folder
   - Including: `index.php`, `.htaccess`, `favicon.ico`, `robots.txt`
   - The `build` folder (created by Vite)

   **Folder Structure on Server:**
   ```
   /htdocs/
   ├── app/
   ├── bootstrap/
   ├── config/
   ├── database/
   ├── resources/
   ├── routes/
   ├── storage/
   ├── vendor/
   ├── .env
   ├── artisan
   ├── composer.json
   └── public_html/
       ├── build/
       ├── .htaccess
       ├── favicon.ico
       ├── index.php
       └── robots.txt
   ```

4. **Set Permissions**
   - Right-click on `storage` folder → **File permissions** → Set to `755`
   - Right-click on `bootstrap/cache` folder → **File permissions** → Set to `755`

> [!TIP]
> Upload can take 10-20 minutes depending on your internet speed. Be patient!

---

### Step 6: Configure Laravel on Server

Since you can't run Artisan commands on InfinityFree, you need to make a small change to `index.php`:

1. **Edit `/htdocs/public_html/index.php`** via FTP or cPanel File Manager
2. Find this line (around line 17):
   ```php
   $app = require_once __DIR__.'/../bootstrap/app.php';
   ```
3. Make sure it correctly points to your bootstrap folder (should already be correct)

---

### Step 7: Import Database

1. **Access phpMyAdmin**
   - In InfinityFree cPanel, click **phpMyAdmin**
   - Select your database from the left sidebar

2. **Import Your Data** (if you have the export from Step 2)
   - Click **Import** tab
   - Click **Choose File**
   - Select `database_export.sql`
   - Click **Go** at the bottom
   - Wait for import to complete

   OR

3. **Run Fresh Migrations** (if starting without data)
   - Since you can't run `php artisan migrate` on InfinityFree, you need to:
   - **Option A:** Run migrations locally with MySQL, then export and import
   - **Option B:** Create tables manually using the migration files as reference
   - **Option C:** Use a migration tool or script

> [!NOTE]
> The easiest approach is to set up a local MySQL database, run migrations there, then export and import to InfinityFree.

---

### Step 8: Clear Configuration Cache

Since you can't run Artisan commands, we need to manually clear the cache:

1. Via FTP or File Manager, delete these folders if they exist:
   - `/htdocs/bootstrap/cache/routes-v7.php`
   - `/htdocs/bootstrap/cache/config.php`
   - `/htdocs/bootstrap/cache/packages.php`
   - `/htdocs/bootstrap/cache/services.php`

2. Make sure `storage` and `bootstrap/cache` folders are writable (755 permissions)

---

### Step 9: Test Your Application

1. **Visit Your Website**
   - Open your browser
   - Go to: `https://your-subdomain.infinityfreeapp.com`
   
2. **Test Core Functions**
   - ✅ Homepage loads correctly
   - ✅ Can view products
   - ✅ Can add new products
   - ✅ Can add new sales
   - ✅ Can view categories
   - ✅ CSS and JavaScript work properly

> [!WARNING]
> If you see errors, check the troubleshooting section below.

---

## 🔧 Troubleshooting

### Error: "500 Internal Server Error"
**Possible causes:**
1. Incorrect `.env` configuration - double-check database credentials
2. Wrong file permissions on `storage` or `bootstrap/cache`
3. Missing `.htaccess` file in public_html

**Solutions:**
- Check your `.env` file for typos
- Set `storage` and `bootstrap/cache` to 755 permissions
- Verify `.htaccess` exists in `public_html`

### Error: "SQLSTATE[HY000] [1045] Access denied"
**Cause:** Wrong database credentials

**Solution:**
- Verify your database credentials in InfinityFree cPanel
- Update `.env` file with correct values
- Make sure user has privileges on the database

### CSS/JavaScript Not Loading
**Cause:** Asset paths incorrect

**Solutions:**
- Check that `build` folder is in `public_html`
- Verify `APP_URL` in `.env` matches your domain
- Clear browser cache

### Session Issues / Login Not Working
**Cause:** Session storage not configured

**Solutions:**
- Ensure `SESSION_DRIVER=database` in `.env`
- Verify `sessions` table exists in database
- Check `storage/framework/sessions` has 755 permissions

### "Too Many Connections" Error
**Cause:** InfinityFree has connection limits

**Solutions:**
- Change `QUEUE_CONNECTION=sync` in `.env`
- Optimize database queries
- Add connection pooling if possible

---

## 📝 Important Notes

> [!IMPORTANT]
> **Limitations of InfinityFree:**
> - No SSH/command-line access
> - Limited execution time (60 seconds)
> - Database connection limits
> - No real-time features (pusher, websockets)
> - Limited to file-based queues

> [!TIP]
> **For Better Performance:**
> - Enable SSL certificate in InfinityFree cPanel (free with Let's Encrypt)
> - Use caching where possible
> - Optimize images before uploading
> - Keep database queries efficient

---

## 🔄 Updating Your Application

When you make changes to your application:

1. Build production assets locally: `.\build_for_production.bat`
2. Upload only the changed files via FTP
3. Clear cache files on server (delete files in `bootstrap/cache`)
4. If database changes: export migrations and import via phpMyAdmin

---

## 🆘 Getting Help

If you encounter issues:
1. Check InfinityFree documentation: [infinityfree.net/support](https://infinityfree.net/support)
2. Check Laravel logs (if accessible via FTP): `storage/logs/laravel.log`
3. Review InfinityFree forum for common issues

---

## ✅ Post-Deployment Checklist

- [ ] Application loads without errors
- [ ] Database connection successful
- [ ] Can create/read/update products
- [ ] Can create/read sales records
- [ ] Can manage categories
- [ ] CSS styles loading correctly
- [ ] JavaScript functionality working
- [ ] Session/authentication working (if applicable)
- [ ] SSL certificate enabled (optional but recommended)

---

**Congratulations! 🎉** Your Unique Hardware Inventory Management System should now be live on InfinityFree!

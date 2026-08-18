# Longguard Tankcare Solutions Ltd — Website Setup Guide

## STEP 1 — Copy Files to XAMPP
Place the entire `longguard/` folder inside:
```
C:\xampp\htdocs\longguard\
```

## STEP 2 — Create the Database
1. Open your browser and go to: http://localhost/phpmyadmin
2. Click **"New"** on the left sidebar
3. Create a database named: `longguard_db`
4. Click the `longguard_db` database
5. Click **"Import"** tab at the top
6. Click **"Choose File"** → select `database_setup.sql` from this folder
7. Click **"Go"** to run it

## STEP 3 — Check Database Settings
Open `includes/db.php` and confirm:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');         // Leave blank for default XAMPP
define('DB_NAME', 'longguard_db');
```
If you set a MySQL password in XAMPP, enter it in DB_PASS.

## STEP 4 — Visit Your Website
Open your browser and go to:
```
http://localhost/longguard/
```

---

## STEP 5 — Admin Dashboard (UPDATED SECURITY)

Go to: http://localhost/longguard/admin/login.php

### Default credentials:
- **Username:** `longguard_admin`
- **Password:** `longguard2024`

### ⚠️ IMPORTANT — Change the Admin Password Before Going Live:

1. Generate a new bcrypt hash by running this in a PHP file (or php -r in terminal):
   ```php
   echo password_hash('YourNewSecurePassword', PASSWORD_BCRYPT);
   ```
2. Open `admin/auth.php` and replace the ADMIN_HASH value with your new hash.
3. Also change ADMIN_USERNAME if desired.

### Admin Features:
- Secure login with bcrypt password hashing
- Session timeout (1 hour of inactivity)
- Brute-force protection (5 attempts → 15-minute lockout)
- CSRF protection on all admin actions
- View all bookings and contact messages
- **Send SMS confirmations** directly from the booking list (see SMS setup below)
- Mark messages as read
- Delete bookings

---

## STEP 6 — SMS Gateway Setup (Africa's Talking)

Africa's Talking is Kenya's leading SMS API, supporting Safaricom, Airtel & Telkom.

1. **Sign up** at https://africastalking.com (free sandbox account for testing)
2. **Get your API key** from the dashboard → Settings → API Key
3. **Register a sender name** (alphanumeric, e.g. "LongGuard") — takes 24–48 hrs to activate on live
4. **Open `includes/sms.php`** and set:
   ```php
   define('AT_USERNAME', 'your_at_username');
   define('AT_API_KEY',  'your_api_key_here');
   define('AT_SENDER',   'LongGuard');
   ```
5. For **sandbox testing**, change AT_ENDPOINT to:
   ```
   https://api.sandbox.africastalking.com/version1/messaging
   ```
   And use the sandbox API key.
6. For **live production**, use:
   ```
   https://api.africastalking.com/version1/messaging
   ```

### How SMS works:
- Admin opens a booking in the dashboard
- Clicks **"Confirm SMS"** button
- Fills in confirmed date (e.g. "Monday, 14 July 2025") and arrival window (e.g. "9:00 AM – 11:00 AM")
- Live preview of the message is shown before sending
- Click "Send SMS Now" — the customer receives a branded text message
- Booking status is automatically updated to "confirmed"

---

## FILE STRUCTURE
```
longguard/
├── index.php               ← Homepage (unchanged)
├── booking.php             ← Booking form (SQL-safe, CSRF, validated)
├── contact.php             ← Contact form (SQL-safe, CSRF, validated)
├── database_setup.sql      ← Run this in phpMyAdmin
├── includes/
│   ├── db.php              ← Database connection
│   ├── sms.php             ← SMS gateway (Africa's Talking)  ← NEW
│   ├── header.php          ← Navigation bar
│   └── footer.php          ← Footer + WhatsApp button
├── assets/
│   ├── css/style.css       ← All styling
│   ├── js/main.js          ← Interactions & animations
│   └── images/             ← Add your photos here!
└── admin/
    ├── auth.php            ← Secure authentication helper  ← NEW
    ├── login.php           ← Admin login page              ← NEW
    └── index.php           ← Admin dashboard (bookings + messages + SMS)
```

---

## SECURITY IMPROVEMENTS IN THIS VERSION

| Area | What changed |
|------|-------------|
| SQL Injection | All queries use prepared statements with `bind_param` — zero raw string interpolation |
| XSS | All output escaped with `htmlspecialchars(…, ENT_QUOTES, 'UTF-8')` |
| CSRF | Every form includes a hidden CSRF token; server verifies with `hash_equals` |
| Admin auth | bcrypt password hashing (cost 12); plain-text password removed |
| Brute force | 5-attempt lockout for 15 minutes (file-based, no extra dependencies) |
| Session | `session_regenerate_id(true)` on login; 1-hour idle timeout |
| Input | `strip_tags` + `trim` on all POST fields; whitelist validation on select fields |
| Date | Server validates `Y-m-d` format and rejects past dates |
| Links | External links use `rel="noopener noreferrer"` |

---

## ADDING YOUR REAL PHOTOS
Place photos in `assets/images/` and update index.php gallery section.
Recommended photo names:
- before-1.jpg, before-2.jpg  (dirty tanks)
- process-1.jpg               (cleaning in progress)
- after-1.jpg, after-2.jpg   (clean results)
- about-team.jpg              (your team)

---

## GOING LIVE (Hosting)
1. Upload all files to your web host via FTP (FileZilla recommended)
2. Create a MySQL database in your hosting cPanel
3. Import database_setup.sql
4. Update includes/db.php with your live database credentials
5. Update includes/sms.php with your live Africa's Talking credentials
6. Change the admin password hash in admin/auth.php
7. Point your domain (longguardtankcare.co.ke) to the hosting
8. Ensure PHP sessions directory is writable on the server

---

## CONTACT
Company: Longguard Tankcare Solutions Ltd
Phone: 0114676477 | 0104852047
Email: info@longguardtankcare.co.ke
Location: Nairobi, Kenya

# Ariyan Mirza — Wedding Photo Editor Portfolio

Premium responsive portfolio + admin panel built with PHP 8+, MySQL, HTML5, CSS3, JavaScript.

## 🔑 Your Credentials (already configured)

- **Admin URL:** `/admin/login.php`
- **Username:** `@Admin@Ariyan`
- **Password:** `ariyanmirza@@`
- **DB Name:** `ariyanmirzawedding`
- **DB User:** `ariyanmirza1432`
- **Email:** ariyanopu21@gmail.com
- **WhatsApp:** +8801337902836
- **Facebook:** https://www.facebook.com/ariyanmirzash

## 🚀 Setup

1. cPanel → MySQL Databases → create database `ariyanmirzawedding` and user `ariyanmirza1432` with password `XTmJ3sAtJKKQf#Av`, assign user with ALL PRIVILEGES.
2. Upload all files to `public_html/`.
3. Ensure `uploads/`, `uploads/photos/`, `uploads/thumbnails/`, `uploads/albums/` are writable (755 or 775).
4. Visit `https://yoursite.com/install.php` in browser.
5. **DELETE `install.php`** immediately.
6. Visit `/admin/login.php` and login with your credentials.
7. Replace the 5 images in `assets/images/` with your own.
8. Create your first album from Admin → Create Album.

## 📁 Structure
- `config/` — DB connection
- `includes/` — bootstrap, header, navbar, footer, helpers
- `admin/` — admin panel
- `languages/` — en.php, bn.php
- `assets/` — css, js, images
- `uploads/` — photos, thumbnails (PHP exec blocked)
- `database/` — SQL schema

## 🔒 Security
- PDO prepared statements
- CSRF tokens on all forms
- Bcrypt password hashing
- Upload MIME + extension validation
- Uploads folder blocks PHP execution
- Session regeneration on login

## 📸 Images needed (place in `assets/images/`)
- `profile.jpg` — hero (recommended 1200×1500)
- `about-1.jpg` — About section large image
- `about-2.jpg` — About section small image
- `placeholder.jpg` — fallback album cover
- `favicon.png` — 64×64 browser icon
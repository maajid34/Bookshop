# BlueBook Store (Pure PHP + MySQL + Tailwind)

A simple online bookstore with cart, checkout, orders, and an admin dashboard.

## Tech
- PHP 8+ (PDO)
- MySQL 8
- Tailwind CSS (CDN)
- Vanilla JS (no frameworks, no APIs)

## Colors
Primary: #007BFF, Accent: #FFD43B

## Setup
1. Upload the project to your Namecheap cPanel under `public_html/` (you can keep this repo root there).
2. Create a MySQL database and user in cPanel.
3. Open **phpMyAdmin** and import `database.sql`.
4. Edit `config/config.php` with your DB credentials and BASE_URL if needed.
5. Ensure the directory `/public/assets/uploads/covers` is writable (permissions 755 or 775).
6. Visit `/admin/login.php` and sign in with:
   - Email: `admin@example.com`
   - Password: `Admin@123` (change immediately in DB).

## File Map
See folders: `public/` (site pages), `admin/` (dashboard), `includes/` (helpers), `config/` (env).

## Notes
- Cart is session-based; stock is reduced on order placement.
- Image uploads are limited to 2MB and png/jpg/jpeg/gif.
- This is a starter you can extend (tax/shipping, coupons, etc.).

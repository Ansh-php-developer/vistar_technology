# Vistar Technology website (PHP + MySQL)

## Setup (XAMPP/WAMP)
1. Copy this folder to `htdocs/vistar_technology`.
2. Start Apache and MySQL, open phpMyAdmin and import `database/vistar_technology.sql`.
3. Check `config/db.php` (DB user/password and BASE_URL).
4. Open http://localhost/vistar_technology/ for the site and /admin/login.php for the admin panel.

Default admin login: `admin` / `password`. Change it in Admin > Settings straight away.

Requires PHP 8.0 or newer. Uploaded images go to `uploads/<section>/` (must be writable).
Admin pages share two helper files, `admin/_layout.php` and `admin/_crud.php`.

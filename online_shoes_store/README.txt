Online Shoes Store - PHP Mini Project
====================================

Contents:
- config.php             : Database connection (update DB credentials here)
- database.sql           : SQL script to create database and tables + sample data
- index.php              : Home / product listing
- product.php            : Single product details + Add to Cart
- register.php           : User registration
- login.php              : User login
- logout.php             : Logout
- cart.php               : View and manage cart
- checkout.php           : Place order (user must be logged in)
- orders.php             : User order history
- assets/                : CSS and image placeholders
- admin/                 : Admin panel for product management and viewing orders

How to run locally:
1. Install XAMPP/WAMP or any PHP+MySQL server.
2. Copy the "online_shoes_store" folder to your server's web root (e.g., C:/xampp/htdocs/).
3. Open phpMyAdmin (or MySQL client) and create a database named `online_shoes`.
4. Import the included `database.sql` into the `online_shoes` database.
5. Edit `config.php` and set DB_HOST, DB_USER, DB_PASS if needed.
6. Open http://localhost/online_shoes_store/ in your browser.

Default admin login:
- username: admin@store.com
- password: admin123

Security notes:
- This project is intentionally simple for learning. In production, add CSRF protection, stronger password rules, file upload validation, and HTTPS.

Enjoy — modify styles and features as you like!

# myweb2

## Overview

`myweb2` is a PHP web application that provides user authentication, profile management, shopping flow, and event/product CRUD operations.

The app uses PDO for database access and includes a sample database schema file for MySQL.

## Features

- User registration and login
- Profile update and password change
- Account deletion with password confirmation
- Session-based shopping cart flow
- Product/event management via `events.php`
- Static pages for home, about us, and navigation
- Image asset support in `IMAGENES/`

## Project Structure

- `CONTROLLER/`
  - `UserController.php` — handles registration, login, logout, profile updates, password changes, and account deletion.
  - `EventController.php` — provides CRUD operations for the `productos` table and returns JSON data for reads.
- `MODEL/`
  - `db.php` — sets up the PDO database connection.
  - `Users.php` — implements user registration, login, profile update, password change, and account deletion.
  - `BBDDTransversal.sql` — database schema and table definitions.
- `VIEW/`
  - `index.html` — landing page.
  - `home.php` — home/dashboard page.
  - `aboutus.html` — about us page.
  - `login.html` — login page.
  - `register.html` — registration page.
  - `registerError.html` — registration/login error page.
  - `shop.php` — shop page.
  - `checkout.php` — checkout page.
  - `events.php` — product/event CRUD page.
  - `Profile.php` — profile management page.
- `IMAGENES/` — image assets used by the site.

## Database Setup

1. Create or use a MySQL database named `BBDDTransversal`.
2. Import `MODEL/BBDDTransversal.sql`.
3. Update `MODEL/db.php` with your MySQL host, username, password, and database name.

## How it works

1. The user submits the registration form (`register.html`) or login form (`login.html`).
2. `CONTROLLER/UserController.php` processes the request and uses `MODEL/Users.php` for authentication.
3. On successful login or registration, session variables are set and the user is redirected to `VIEW/shop.php`.
4. Users can manage their profile in `VIEW/Profile.php`, change passwords, or delete their account.
5. `CONTROLLER/EventController.php` handles creating, reading, updating, and deleting `productos` records for `VIEW/events.php`.
6. The shopping flow and session state are preserved across pages until logout.

## Important Files

- `CONTROLLER/UserController.php`
- `CONTROLLER/EventController.php`
- `MODEL/db.php`
- `MODEL/Users.php`
- `MODEL/BBDDTransversal.sql`
- `VIEW/shop.php`
- `VIEW/events.php`
- `VIEW/Profile.php`
- `IMAGENES/` assets

## Notes

- Passwords use `password_hash()` and `password_verify()`.
- `EventController` uses the `productos` table and returns JSON when reading records.
- `UserController` requires `session_start()` to manage user sessions.
- Ensure the `users` table exists in the database and that PDO is configured correctly.

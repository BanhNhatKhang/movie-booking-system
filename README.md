# README - HR Guide for Cloning and Testing

This document helps HR clone and test the project quickly on a local machine.

## 1) Overview

- Architecture: PHP MVC (custom), Blade template engine (jenssegers/blade)
- Database: PostgreSQL
- Web root directory: public/
- Database schema file: Table.sql

## 2) Prerequisites

- PHP 8.1+
- Composer 2+
- PostgreSQL 14+
- Web browser (Chrome or Edge)

## 3) Clone the repository

```bash
git clone <REPO_URL>
cd movie
```

Replace <REPO_URL> with the actual repository link.

## 4) Install PHP dependencies

```bash
composer install
```

## 5) Create and import the database

1. Create a new PostgreSQL database (for example: CT275_Project).
2. Import the schema:

```bash
psql -U postgres -d CT275_Project -f Table.sql
```

If you use pgAdmin, open Table.sql and run the full script.

## 6) Configure database connection

Update app/config/database.php with your local settings:

```php
<?php
return [
	'driver'   => 'pgsql',
	'host'     => 'localhost',
	'port'     => 5432,
	'database' => 'CT275_Project',
	'username' => 'postgres',
	'password' => 'YOUR_PASSWORD',
	'options'  => [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES => false,
	]
];
```

Recommended: use app/config/database.example.php as a reference.

## 7) Google OAuth setup (optional)

If you want to test Google login:

1. Create app/config/client_secret.json from Google Cloud Console.
2. Make sure redirect_uri matches your local URL.

If Google login is not required for testing, you can skip this step.

## 8) Run the project

From the movie/ root folder, run:

```bash
php -S localhost:8000 -t public
```

Open in browser:

- Home page: http://localhost:8000/
- Login page: http://localhost:8000/dang-nhap

## 9) Quick test checklist for HR

- Home page loads successfully.
- Register a new account successfully.
- Login with the new account successfully.
- Browse user pages (movies, promotions, personal profile).
- Access admin routes if permission is provided (for example: /dashboard, /quan-ly-phim).

## 10) Common issues

- Class not found: composer install has not been run.
- PostgreSQL connection error: check host/user/password/database in app/config/database.php.
- Blank page or 404 at root: make sure the server document root is public/.

## 11) Quick setup commands (copy/paste)

```bash
git clone <REPO_URL>
cd movie
composer install
# Create database CT275_Project first
psql -U postgres -d CT275_Project -f Table.sql
php -S localhost:8000 -t public
```

## 12) Team information

- Student ID 1: B2205878 - Banh Nhat Khang
- Student ID 2: B2205870 - Do Quang Huy


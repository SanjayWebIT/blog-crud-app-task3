# Basic CRUD Blog Application (PHP + MySQL)

A small PHP application with registration, login, sessions, and post CRUD.

## Requirements
- PHP 8.0+ (PDO MySQL extension and mbstring enabled)
- MySQL or MariaDB (MAMP/XAMPP/WAMP)
- Web browser

## Setup
1. Copy this folder into your local web root, for example:
   - MAMP: `C:\MAMP\htdocs\blog-crud-app`
   - XAMPP: `C:\xampp\htdocs\blog-crud-app`
2. Start Apache and MySQL/MariaDB.
3. Open phpMyAdmin, select **Import**, and import `database.sql`.
4. Open `config.php` and set the database username/password for your local MySQL setup.
5. Visit `http://localhost/blog-crud-app/` (MAMP may use a configured port such as `8888`).

## Features
- Register users with `password_hash()`
- Login with `password_verify()` and session ID regeneration
- Logout using POST
- Create, read, update, delete posts
- Prepared statements, output escaping, CSRF tokens
- Users can edit/delete only their own posts

## Database schema
- `users`: id, username, password, created_at
- `posts`: id, user_id, title, content, created_at

`user_id` is an additional foreign key in `posts` so each account manages only its own posts. The requested fields are retained.

## Important
This is a learning project. Before production deployment, add HTTPS, rate limiting, password reset, email verification, stronger operational logging, and secure production configuration. Do not publish real credentials.

## Task 3: Advanced features
- Search posts by matching title or content.
- Pagination with five posts per page.
- Search term is preserved while navigating pages.
- Responsive refreshed interface using custom CSS.

## Creative UI refresh
- Warm editorial theme with cream, charcoal, olive, and coral accents.
- Refined typography, responsive navigation, elevated post cards, and improved form styling.
- Responsive layout for desktop and mobile screens.

## Single blog article page (new)
- Every post title and **Read full article** link opens `single.php?id=POST_ID`.
- The article page includes a responsive two-column magazine layout, article metadata, featured image, formatted paragraphs, quote styling, author panel, related posts, social links, category cards, popular posts, and tag cloud.
- The article text and author/date are loaded from the existing `posts` and `users` tables. No database migration is required.
- The featured and inline editorial photos are sample remote images; they are not uploaded per post. Add an image-upload field and database column in a later enhancement if each author should choose their own photos.

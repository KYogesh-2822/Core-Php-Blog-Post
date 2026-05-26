# Core PHP Blog Project

A full-featured blog application built with **Core PHP**, **MySQL**, **Apache**, and **Docker**. Users can register, verify their email, login, create blog posts, upload images, like posts, comment, and reply to comments.

---

## Features

### User Authentication
- User registration with form validation
- Email verification using 6-digit OTP code
- Login only after email verification
- Secure password hashing using `password_hash()`
- Session-based authentication
- Logout functionality

### Blog Post System
- Create blog posts with title and short description
- Upload single or multiple blog images (1 to 5)
- Upload optional full blog file for long content
- Show blog preview on home page with Read More
- Show uploaded blog file on blog detail page

### Like System
- Logged-in users can like blog posts
- One user can like a blog only once (toggle like/unlike)
- Like count shown on listing and detail page

### Comment and Reply System
- Logged-in users can comment on blog posts
- Blog owner can reply to comments on their own blog
- Replies shown below comments in hierarchy format
- Comment and reply counts shown dynamically

### Database Relations
- Primary keys and foreign keys
- JOIN queries for related data
- One-to-many relationships
- Self-referencing table for comment replies

---

## Technology Stack

| Technology | Purpose |
|------------|---------|
| Core PHP 8.2 | Backend logic |
| MySQL 8.0 | Database |
| Apache | Web server |
| Docker + Docker Compose | Containerization |
| phpMyAdmin | Database management UI |
| PHPMailer | Email sending |
| Mailtrap | Fake email inbox for development |
| HTML + CSS | Frontend |
| JavaScript | Client-side form validation |

---

## Project Structure

```
core-php-project/
├── docker-compose.yml
├── Dockerfile
├── mysql/
│   └── init.sql                  ← Database tables
└── Core-Php/                     ← Main application
    ├── index.php                 ← Single entry point
    ├── .htaccess                 ← Clean URL routing
    ├── vendor/                   ← PHPMailer (manual install)
    │   ├── autoload.php
    │   └── phpmailer/
    │       └── phpmailer/
    │           └── src/
    │               ├── PHPMailer.php
    │               ├── SMTP.php
    │               └── Exception.php
    ├── config/
    │   ├── db.php                ← PDO database connection
    │   ├── logger.php            ← Logging helper
    │   ├── queries.php           ← All SQL query functions
    │   ├── bootstrap.php         ← Loads everything, env config
    │   └── router.php            ← URL routing
    ├── controllers/
    │   ├── HomeController.php
    │   ├── RegisterController.php
    │   ├── VerifyController.php
    │   └── LoginController.php   ← coming soon
    ├── views/
    │   ├── home.php
    │   ├── register.php
    │   └── verify.php
    ├── helpers/
    │   └── mail.php              ← All email functions
    ├── includes/
    │   ├── layout.php            ← renderLayout() function
    │   ├── header.php            ← Nav + CSS
    │   └── footer.php            ← JS + closing tags
    ├── assets/
    │   ├── css/
    │   │   └── style.css
    │   └── js/
    │       └── main.js           ← JS form validation
    ├── uploads/
    │   ├── images/               ← Blog images
    │   └── files/                ← Blog files
    └── logs/
        ├── YYYY-MM-DD.log        ← Daily logs
        └── verification_log.txt  ← OTP codes for development
```

---

## Docker Setup

### Services

| Service | Container | Port | Purpose |
|---------|-----------|------|---------|
| PHP + Apache | core_php_blog_app | 9001 | Main application |
| MySQL | core_php_blog_db | 3307 | Database |
| phpMyAdmin | core_php_blog_phpmyadmin | 8081 | DB management |
| Mailtrap | blog_mailtrap | 8082 | Fake email inbox |

### Start Project

```bash
docker compose up -d --build
```

### Stop Project

```bash
docker compose down
```

### Rebuild from Scratch

```bash
docker compose down --rmi all --volumes
docker compose up -d --build
```

### Access URLs

| URL | Purpose |
|-----|---------|
| http://localhost:9001 | Main application |
| http://localhost:8081 | phpMyAdmin |
| http://localhost:8082 | Mailtrap inbox |

---

## Database Tables

### `users`
| Column | Type | Description |
|--------|------|-------------|
| id | INT AUTO_INCREMENT | Primary key |
| name | VARCHAR(100) | Full name |
| email | VARCHAR(150) UNIQUE | Email address |
| password | VARCHAR(255) | Hashed password |
| is_verified | TINYINT(1) | 0 = not verified, 1 = verified |
| created_at | TIMESTAMP | Registration time |

### `email_verifications`
| Column | Type | Description |
|--------|------|-------------|
| id | INT AUTO_INCREMENT | Primary key |
| user_id | INT | FK → users.id |
| code | VARCHAR(10) | 6-digit code |
| created_at | TIMESTAMP | Code generation time |

### `posts`
| Column | Type | Description |
|--------|------|-------------|
| id | INT AUTO_INCREMENT | Primary key |
| user_id | INT | FK → users.id |
| title | VARCHAR(255) | Blog title |
| short_description | TEXT | Preview text |
| blog_file | VARCHAR(255) | Optional file path |
| created_at | TIMESTAMP | Post time |

### `post_images`
| Column | Type | Description |
|--------|------|-------------|
| id | INT AUTO_INCREMENT | Primary key |
| post_id | INT | FK → posts.id |
| image_path | VARCHAR(255) | Image file path |

### `post_likes`
| Column | Type | Description |
|--------|------|-------------|
| id | INT AUTO_INCREMENT | Primary key |
| post_id | INT | FK → posts.id |
| user_id | INT | FK → users.id |
| created_at | TIMESTAMP | Like time |
| UNIQUE KEY | (post_id, user_id) | One like per user |

### `comments`
| Column | Type | Description |
|--------|------|-------------|
| id | INT AUTO_INCREMENT | Primary key |
| post_id | INT | FK → posts.id |
| user_id | INT | FK → users.id |
| parent_id | INT NULL | FK → comments.id (for replies) |
| body | TEXT | Comment content |
| created_at | TIMESTAMP | Comment time |

---

## Architecture Pattern

This project follows an MVC-like pattern in Core PHP:

```
URL Request
    │
    ▼
.htaccess → index.php?page=register
    │
    ▼
router.php → finds route → loads controller + view
    │
    ├── bootstrap.php     loads: db, logger, queries, layout, router, controllers
    ├── RegisterController.php   logic only, returns $data array
    └── renderLayout('Register', function() {
            extract($data);
            require views/register.php;   ← HTML only
        })
```

### Key Concepts Used

| Concept | Implementation |
|---------|---------------|
| MVC Pattern | controllers/ + views/ + queries.php |
| Output Buffering | ob_start() in renderLayout() |
| Prepared Statements | PDO with ? placeholders |
| Password Security | password_hash() + password_verify() |
| Session Management | $_SESSION for auth state |
| Clean URLs | .htaccess RewriteRule |
| Auto Controller Loading | glob() in bootstrap.php |
| Environment Config | getenv() for mail/db settings |

---

## Email System

### Development (Mailtrap)
All emails go to fake inbox at http://localhost:8082. No real emails sent.

```
MAIL_HOST=mailtrap
MAIL_PORT=25
MAIL_USERNAME=""
MAIL_PASSWORD=""
```

### Production (Gmail SMTP)
1. Enable 2-Step Verification on Gmail
2. Generate App Password: Google Account → Security → App Passwords
3. Update environment variables:

```
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@gmail.com
MAIL_PASSWORD=your_16_char_app_password
MAIL_ENCRYPTION=tls
```

### Available Email Functions

| Function | When Used |
|----------|-----------|
| `sendVerificationEmail($email, $name, $code)` | After registration |
| `sendWelcomeEmail($email, $name)` | After email verified |
| `sendPasswordResetEmail($email, $name, $link)` | Password reset (future) |

### Adding New Email
Add a new function to `helpers/mail.php`:
```php
function sendNewCommentEmail(string $toEmail, string $toName, string $postTitle) {
    $subject = "New comment on: $postTitle";
    $body    = "...your HTML template...";
    return sendMail($toEmail, $toName, $subject, $body);
}
```

---

## Logging System

Logs are written directly to `Core-Php/logs/` — visible on Windows without opening Docker.

### Log Files
| File | Contents |
|------|---------|
| `logs/YYYY-MM-DD.log` | Daily application logs |
| `logs/verification_log.txt` | OTP codes for development |
| `logs/php_errors.log` | PHP errors (production only) |

### Log Functions

```php
logInfo("message", ['key' => 'value']);
logError("message", ['error' => $e->getMessage()]);
logWarning("message");
logDebug("message");
logAuth("message", ['user_id' => 1]);
logDb("message");
logVerificationCode($email, $code, 'NEW');    // NEW or RESEND
```

### Log Format
```
[2026-05-25 14:32:01] [AUTH] New user registered | {"user_id":1,"email":"test@test.com"}
[2026-05-25 14:32:45] [AUTH] Email verified successfully | {"user_id":1}
[2026-05-25 14:33:10] [ERROR] Database connection failed | {"error":"..."}
```

---

## JavaScript Validation

All forms use `data-validate` attribute to trigger JS validation.

```html
<form method="POST" data-validate>
```

### Validation Rules Defined in `assets/js/main.js`

| Field | Rules |
|-------|-------|
| name | Required, min 3 characters |
| email | Required, valid email format |
| password | Required, min 6 characters |
| confirm_password | Required, must match password |
| code | Required, exactly 6 digits |

### Adding New Field Validation
Add to `validationRules` object in `main.js`:
```javascript
phone: [
    { test: value => value.trim() !== '', message: 'Phone is required.' },
    { test: value => /^\d{10,}$/.test(value), message: 'Enter valid phone.' }
]
```

---

## Registration and Verification Flow

```
User submits register form
    │
    ├── Email new → create user → send OTP → /verify
    ├── Email exists + verified → "Please login" error
    └── Email exists + NOT verified → resend OTP → /verify

On /verify page
    ├── Session exists → show code input form
    └── Session missing (browser closed) → show email input → resend OTP

OTP entry
    ├── Wrong code → show error
    ├── Expired (10 mins) → show expired error
    └── Correct → mark verified → send welcome email → /login
```

---

## Common Errors and Fixes

### `could not find driver`
PDO MySQL extension not installed.
```bash
docker compose down --rmi all --volumes
docker compose up -d --build
```
Check `phpinfo()` for `pdo_mysql` in PDO drivers.

### `getaddrinfo for mysql failed`
Wrong hostname in `config/db.php`. Use Docker service name:
```php
$hostname = "db";  // must match service name in docker-compose.yml
```

### `Call to undefined function logInfo()`
`logger.php` loads after `db.php`. Fix load order in `bootstrap.php`:
```php
require_once ROOT . '/config/logger.php';  // ← first
require_once ROOT . '/config/db.php';      // ← second
```

### `AllowOverride` — `.htaccess` not working
Add to `Dockerfile`:
```dockerfile
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf
```

### `Headers already sent`
HTML or echo before `header()` call. Keep all PHP logic before any HTML output.

### `Constant already defined`
File loaded twice. Use `require_once` instead of `require` and wrap defines:
```php
if (!defined('LOG_INFO')) {
    define('LOG_INFO', 'INFO');
}
```

### Composer SSL Error in Docker
Use manual PHPMailer install:
```bash
docker exec -it --user root core_php_blog_app bash
mkdir -p /var/www/html/vendor/phpmailer/phpmailer/src
cd /var/www/html/vendor/phpmailer/phpmailer/src
curl -k -O https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/PHPMailer.php
curl -k -O https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/SMTP.php
curl -k -O https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/Exception.php
```

### 500 Internal Server Error
Turn on error display temporarily in `index.php`:
```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```
Or check Docker logs:
```bash
docker logs core_php_blog_app
```

### ROOT Path Wrong
`bootstrap.php` is inside `config/` so:
```php
define('ROOT', __DIR__ . '/..');
// __DIR__ = /var/www/html/config
// ROOT    = /var/www/html  ✅
```

---

## Steps Completed

- [x] Step 1 — Docker setup (PHP, MySQL, phpMyAdmin, Mailtrap)
- [x] Step 2 — Database tables with PKs and FKs
- [x] Step 3 — Project architecture (MVC pattern, router, bootstrap)
- [x] Step 4 — Register user with validation
- [x] Step 5 — Email verification with OTP
- [x] Step 6 — Logger system
- [x] Step 7 — Mail helper with PHPMailer
- [ ] Step 8 — Login user
- [ ] Step 9 — Create blog post
- [ ] Step 10 — Home page with all blogs
- [ ] Step 11 — Like system
- [ ] Step 12 — Comment and reply system

---

## Installing PHPMailer Manually

```bash
docker exec -it --user root core_php_blog_app bash
mkdir -p /var/www/html/vendor/phpmailer/phpmailer/src
cd /var/www/html/vendor/phpmailer/phpmailer/src
curl -k -O https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/PHPMailer.php
curl -k -O https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/SMTP.php
curl -k -O https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/Exception.php
cat > /var/www/html/vendor/autoload.php << 'EOF'
<?php
require_once __DIR__ . '/phpmailer/phpmailer/src/Exception.php';
require_once __DIR__ . '/phpmailer/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/phpmailer/phpmailer/src/SMTP.php';
EOF
chmod -R 755 /var/www/html/vendor
exit
```

---

### `.gitignore`
```
logs/
vendor/
verification_log.txt
uploads/images/
uploads/files/
```
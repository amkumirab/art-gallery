# 🎨 Aurelia Art Gallery

> A sophisticated full-stack web application for managing and discovering fine art collections. Built with modern web technologies and security-first principles.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-777BB4.svg?logo=php)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-blue.svg?logo=mysql)](https://www.mysql.com/)
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6-F7DF1E.svg?logo=javascript)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)

---

## ✨ Features

### 👥 User Features
- **User Authentication** - Secure registration and login with Bcrypt password hashing
- **Live Gallery Search** - AJAX-powered search with debouncing for optimal performance
- **Advanced Filtering** - Filter artworks by category, artist, year, and price range
- **Favorite System** - Save artworks to personal collection with AJAX toggle
- **Reviews & Ratings** - Leave 1-5 star reviews with comments on artworks
- **Interactive Quiz** - Gamified art knowledge quiz with scoring
- **Responsive Design** - Mobile-friendly interface for all screen sizes

### 🔧 Admin Features
- **Admin Dashboard** - Statistics overview with animated counters
- **Artwork Management** - Full CRUD operations for artworks
- **Image Upload** - Upload artwork images with live preview before saving
- **Artist Management** - Manage artist profiles and information
- **Category Management** - Organize artwork by categories
- **User Management** - Manage user accounts and roles
- **Review Moderation** - Monitor and moderate user reviews

### 🔐 Security Features
- **Password Hashing** - Bcrypt encryption for secure password storage
- **Prepared Statements** - PDO prepared statements prevent SQL injection
- **XSS Prevention** - HTML escaping and sanitization throughout
- **Session Security** - Role-based access control (RBAC)
- **File Upload Validation** - MIME type and size restrictions
- **CSRF Protection** - Built-in session management

---

## 🛠️ Technology Stack

### Frontend
- **HTML5** - Semantic markup for accessibility and SEO
- **CSS3** - Flexbox/Grid layouts with responsive media queries
- **JavaScript (ES6)** - Client-side interactivity and AJAX
- **jQuery** - DOM manipulation and event handling

### Backend
- **PHP 7.4+** - Object-Oriented Programming with design patterns
- **PDO** - Database abstraction with prepared statements
- **JSON APIs** - RESTful endpoints for AJAX communication

### Database
- **MySQL 5.7+** - Normalized relational database design
- **6 Tables** - Users, Artworks, Artists, Categories, Favorites, Reviews

### Design Patterns
- **Singleton Pattern** - Database connection management
- **MVC-like Architecture** - Separation of concerns
- **OOP Principles** - Encapsulation, inheritance, polymorphism

---

## 📦 Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache recommended)
- XAMPP, LAMP, or similar local development environment

### Setup Steps

#### 1. Clone the Repository
```bash
git clone https://github.com/yourusername/aurelia-art-gallery.git
cd aurelia-art-gallery
```

#### 2. Copy to Web Server
```bash
# On XAMPP (Windows/Mac/Linux)
cp -r aurelia-art-gallery /path/to/xampp/htdocs/

# Or create a virtual host (Linux/Mac)
```

#### 3. Create Database
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Click **Import** tab
3. Select `database/art_gallery.sql`
4. Click **Go**

#### 4. Configure Database Connection
Edit `includes/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');        // Default MySQL port
define('DB_NAME', 'art_gallery');
define('DB_USER', 'root');        // Default XAMPP user
define('DB_PASS', '');            // Default XAMPP password (empty)
```

#### 5. Set File Permissions
```bash
# Ensure uploads folder is writable
chmod 755 assets/uploads/
```

#### 6. Open in Browser
```
http://localhost/aurelia-art-gallery/index.php
```

---

## 🎯 Quick Start Guide

### Demo Accounts

| Role | Username | Password | Access |
|------|----------|----------|--------|
| **User** | `demo` | `demo123` | Browse, search, review, favorite |
| **Admin** | `admin` | `admin123` | Full management at `/admin/login.php` |

### First Time Users
1. Visit homepage: `http://localhost/aurelia-art-gallery/`
2. Browse featured artworks on the home page
3. Go to Gallery page to search and filter
4. Click on any artwork for details
5. Register a new account or login with demo account
6. Leave a review or add to favorites

### Admin Users
1. Navigate to: `http://localhost/aurelia-art-gallery/admin/login.php`
2. Login with admin credentials
3. Access dashboard for statistics
4. Manage artworks, artists, categories
5. Upload new artwork images
6. Monitor user reviews

---



## 📁 Project Structure

```
aurelia-art-gallery/
├── 📄 Public Pages (User-facing)
│   ├── index.php                  # Homepage with featured artworks
│   ├── gallery.php                # Main gallery with AJAX search/filter
│   ├── artwork.php                # Artwork detail page + reviews + quiz
│   ├── artists.php                # Browse all artists
│   ├── about.php                  # Gallery information & contact
│   ├── favorites.php              # User's saved artworks (login required)
│   ├── register.php               # User registration
│   ├── login.php                  # User login
│   └── logout.php                 # Logout user
│
├── 🔑 Admin Panel (/admin)
│   ├── login.php                  # Admin-only login
│   ├── index.php                  # Dashboard with statistics
│   ├── artworks.php               # Manage artworks
│   ├── artwork-form.php           # Add/edit artworks with image upload
│   ├── artists.php                # Manage artists
│   ├── categories.php             # Manage categories
│   ├── users.php                  # Manage user accounts
│   ├── reviews.php                # Moderate reviews
│   └── logout.php                 # Admin logout
│
├── 🔌 API Endpoints (/api)
│   ├── artworks.php               # GET filtered artworks (JSON)
│   ├── favorite.php               # POST toggle favorite
│   ├── review.php                 # POST submit review
│   └── upload-image.php           # POST upload image (admin only)
│
├── 📦 Backend Code (/includes)
│   ├── config.php                 # Database configuration & constants
│   ├── Database.php               # PDO Singleton connection
│   ├── User.php                   # User authentication class
│   ├── Artwork.php                # Artwork CRUD operations
│   ├── Artist.php                 # Artist management
│   ├── Category.php               # Category management
│   ├── Favorite.php               # Favorite toggle logic
│   ├── Review.php                 # Review management
│   ├── helpers.php                # Security & utility functions
│   ├── header.php                 # Shared page header
│   └── footer.php                 # Shared page footer
│
├── 🎨 Frontend Assets (/assets)
│   ├── css/
│   │   ├── main.css               # Base styles & CSS variables
│   │   ├── gallery.css            # Gallery-specific styling
│   │   ├── forms.css              # Form styling
│   │   ├── admin.css              # Admin panel styling
│   │   └── responsive.css         # Mobile/tablet media queries
│   ├── js/
│   │   ├── main.js                # Common JavaScript functions
│   │   ├── gallery.js             # Search & filter AJAX
│   │   ├── favorite.js            # Favorite toggle
│   │   ├── review.js              # Review submission
│   │   ├── quiz.js                # Interactive quiz game
│   │   └── admin.js               # Admin tools & animations
│   └── uploads/                   # User-uploaded artwork images
│
├── 🗄️ Database (/database)
│   └── art_gallery.sql            # Database schema & sample data
│
└── 📚 Documentation
    ├── README.md                  # This file
    ├── CONTRIBUTING.md            # Contribution guidelines
    └── LICENSE                    # MIT License
```

---

## 🔐 Security Implementation

### Password Security
- **Bcrypt Hashing**: Uses PHP's `password_hash()` with `PASSWORD_DEFAULT`
- **Verification**: `password_verify()` for secure comparison
- **One-Way Encryption**: Impossible to reverse engineer original password

```php
// Registration
$hash = password_hash($password, PASSWORD_DEFAULT);

// Login
if (password_verify($input, $stored_hash)) {
    $_SESSION['user_id'] = $user['id'];
}
```

### SQL Injection Prevention
- **Prepared Statements**: PDO with parameterized queries
- **Parameter Binding**: Separates SQL structure from data
- **Type Casting**: Ensures data types (int, float)

```php
$stmt = $this->db->prepare('SELECT * FROM users WHERE username = :u');
$stmt->execute([':u' => $username]);  // Safe!
```

### XSS Prevention
- **HTML Escaping**: `htmlspecialchars()` with `ENT_QUOTES`
- **JavaScript Escaping**: `escapeHtml()` function in JS
- **Applied Everywhere**: All user input is sanitized

```php
<?= sanitize($user_input) ?>  // Converts < > " ' to HTML entities
```

### Access Control
- **Session-Based**: Uses `$_SESSION` for authenticated users
- **Role-Based**: Admin vs regular user roles
- **Guard Functions**: `require_login()`, `require_admin()`

```php
<?php require_admin(); ?>  // Page dies here if not admin
```

### File Upload Security
- **MIME Type Validation**: Only allows JPEG, PNG, WebP
- **Size Limits**: Maximum 5MB per file
- **Unique Filenames**: Uses `uniqid()` to prevent collisions and overwrites
- **Secure Moving**: Uses `move_uploaded_file()` only

---

## 📊 Database Schema

### Users Table
```sql
id (Primary Key)
username (Unique)
email (Unique)
password (Bcrypt hash)
role (user | admin)
created_at (Timestamp)
```

### Artworks Table
```sql
id (Primary Key)
title
description
price
image_filename
artist_id (Foreign Key → artists)
category_id (Foreign Key → categories)
year
created_at
```

### Additional Tables
- **artists** - Artist profiles and information
- **categories** - Art types and classifications
- **favorites** - User-saved artworks (user_id, artwork_id)
- **reviews** - User ratings and comments (user_id, artwork_id, rating, comment)

**Relationships**: Normalized design with proper foreign keys and referential integrity.

---

## 🎮 AJAX Features

### Live Search with Debouncing
```javascript
// Wait 350ms after user stops typing
// Then send ONE AJAX request
// Much more efficient than sending request per keystroke
```

### Favorite Toggle
```javascript
// Click heart button → AJAX POST to api/favorite.php
// Server toggles favorite in database
// Button color changes without page reload
```

### Review Submission
```javascript
// Submit form → AJAX POST
// Review added to database
// Page updates without reload
```

---

## 🧪 Testing

### Manual Testing Checklist

#### User Features
- [ ] Register new account (valid/invalid inputs)
- [ ] Login/logout functionality
- [ ] Search artworks (live AJAX updates)
- [ ] Filter by category, artist, price
- [ ] Favorite/unfavorite artwork
- [ ] Submit review and rating
- [ ] Play quiz game (scoring works)

#### Admin Features
- [ ] Login to admin panel
- [ ] View dashboard statistics
- [ ] Add new artwork with image upload
- [ ] Edit existing artwork
- [ ] Delete artwork
- [ ] Manage artists and categories
- [ ] Promote/delete users
- [ ] Moderate reviews

#### Security Tests
- [ ] Try SQL injection in search: `' OR '1'='1`
- [ ] Try XSS in review: `<script>alert('xss')</script>`
- [ ] Try accessing admin pages without login
- [ ] Try uploading non-image file
- [ ] Try uploading huge file (>5MB)

---

## 🚀 Performance Optimizations

- **Debouncing** - Reduces AJAX requests during typing
- **Lazy Loading** - Images load as needed
- **Query Optimization** - Single database connection (Singleton)
- **Prepared Statements** - Faster than string concatenation
- **CSS Custom Properties** - Reduces code duplication
- **Minified Assets** - Smaller file sizes (production ready)

---

## 📝 API Documentation

### `/api/artworks.php` (GET)
Returns filtered artwork list as JSON
```bash
Query Parameters:
  ?search=monet          # Search title, artist
  ?category_id=2         # Filter by category
  ?artist_id=1           # Filter by artist
  ?year=1889             # Filter by year
  ?price_min=100&price_max=500  # Price range
```

### `/api/favorite.php` (POST)
Toggle user's favorite for artwork
```bash
Requires: Login
POST Data: { artwork_id: 42 }
Response: { success: true }
```

### `/api/review.php` (POST)
Submit artwork review
```bash
Requires: Login
POST Data: { artwork_id: 42, rating: 5, comment: "Amazing!" }
Response: { success: true }
```

### `/api/upload-image.php` (POST)
Upload artwork image (admin only)
```bash
Requires: Admin role
POST Data: FormData with 'image' file
Response: { filename: "art_64a3f5b1c2d3e.jpg" }
```

---

## 🤝 Contributing

Contributions are welcome! Here's how to contribute:

1. **Fork** the repository
2. **Create** a feature branch (`git checkout -b feature/AmazingFeature`)
3. **Commit** your changes (`git commit -m 'Add some AmazingFeature'`)
4. **Push** to the branch (`git push origin feature/AmazingFeature`)
5. **Open** a Pull Request

### Contribution Guidelines
- Follow existing code style
- Add comments for complex logic
- Test thoroughly before submitting
- Update README if adding features
- Keep commits atomic and descriptive

---

## 📚 Course Coverage

This project demonstrates proficiency in Web Programming fundamentals:

| Chapter | Topic | Implementation |
|---------|-------|-----------------|
| Ch 3 | HTML Semantics | `<header>`, `<nav>`, `<main>`, `<article>`, `<figure>`, `<footer>` |
| Ch 4 | CSS Fundamentals | Selectors, cascade, typography, box model |
| Ch 5 | Tables & Forms | User forms, admin tables, file uploads |
| Ch 7 | Advanced CSS | Flexbox, Grid, media queries, responsive design |
| Ch 8 | JavaScript | Quiz logic, form validation, DOM manipulation |
| Ch 10 | jQuery | AJAX, event handling, animations, DOM creation |
| Ch 11-13 | PHP & OOP | Classes, PDO, sessions, prepared statements |

---

## 📖 Learning Resources

- [PHP Official Documentation](https://www.php.net/docs.php)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [MDN Web Docs](https://developer.mozilla.org/)
- [jQuery Documentation](https://jquery.com/docs/)
- [OWASP Security Guidelines](https://owasp.org/)

---

## 🐛 Known Issues

None currently. Please report any bugs via GitHub Issues.

---

## 🗺️ Roadmap

### Planned Features
- [ ] User profile pages
- [ ] Wishlist functionality
- [ ] Advanced filtering (multi-select)
- [ ] Email notifications
- [ ] Payment processing (Stripe integration)
- [ ] Mobile app (React Native)
- [ ] API documentation (Swagger)
- [ ] User comments on reviews
- [ ] Artwork recommendations
- [ ] Admin analytics dashboard

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 👨‍💻 Author

**Amirali Mirabzadeh Ardekani**
- Student ID: 559219
- University: University of Messina, Sicily, Italy
- Course: Web Programming Final Project
- GitHub: [@amkumirab](https://github.com/amkumirab)
- Email: amiralimirabzadeh@gmail.com

---

## 🙏 Acknowledgments

- Course Instructor: Prof. Armando Ruggeri
- University of Messina - Web Programming Course
- Inspiration from modern art gallery websites
- Security best practices from OWASP

---

## 📞 Support

For issues, questions, or suggestions:
- **GitHub Issues**: [Create an issue](https://github.com/amkumirab/aurelia-art-gallery/issues)
- **Email**: amiralimirabzadeh@gmail.com
- **GitHub Profile**: [@amkumirab](https://github.com/amkumirab)
- **Discussion**: [GitHub Discussions](https://github.com/amkumirab/aurelia-art-gallery/discussions)

---

## 🌟 Show Your Support

If you find this project helpful, please consider:
- ⭐ Starring the repository
- 🍴 Forking for your own use
- 📢 Sharing with others
- 💬 Providing feedback

---

<div align="center">

**Built with ❤️ using PHP, MySQL, and JavaScript**

Made with passion for fine art and clean code.

</div>
